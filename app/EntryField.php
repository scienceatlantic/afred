<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntryField extends Model
{
    public function entrySection()
    {
        return $this->belongsTo('App\EntrySection');
    }

    public function formField()
    {
        return $this->belongsTo('App\FormField');
    }

    public function stringValue()
    {
        return $this->hasOne('App\StringValue');
    }

    public function textValue()
    {
        return $this->hasOne('App\TextValue');
    }

    public function numberValue()
    {
        return $this->hasOne('App\NumberValue');
    }

    public function dateValue()
    {
        return $this->hasOne('App\DateValue');
    }

    public function labelledValues()
    {
        return $this->belongsToMany('App\LabelledValue')->withTimestamps();
    }

    public function getValue($returnValueObject = false)
    {
        $type = $this->formField()->first()->type->name;
        
        switch ($type) {
            case 'string':
            case 'number':
            case 'date':
                $method = strtolower($type) . 'Value';
                return $returnValueObject ? $this->$method
                    : $this->$method->value;
            case 'richtext':
            case 'plaintext':
                return $returnValueObject ? $this->textValue 
                    : $this->textValue->value;
            case 'dropdown':
            case 'radio':
                return $returnValueObject ? $this->labelledValues[0] 
                    : $this->labelledValues[0]->value;
            case 'checkbox':
                return $returnValueObject ? $this->labelledValues 
                    : $this->labelledValues()->pluck('label');
            default:
                return null;
        }
    }

    public function getValueAttribute()
    {
        return $this->getValue(false);
    }

    public function setValue($value)
    {
        $type = $this->formField()->first()->type->name;

        $valueModelClass = 'App\\';
        switch ($type) {
            case 'richtext':
            case 'plaintext':
                $valueModelClass .= 'Text';
                break;
            default:
                $valueModelClass .= ucfirst($type);
        }
        $valueModelClass .= 'Value';

        switch ($type) {
            case 'checkbox':
            case 'radio':
            case 'dropdown':
                $this->labelledValues()->attach($value);
                break;
            default:
                $v = new $valueModelClass();
                $v->entry_field_id = $this->id;
                $v->value = $value;
                $v->save();                            
        }
    }
}
