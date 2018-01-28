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
                if ($returnValueObject) {
                    return $this->$method;
                }
                return $this->$method->value;
            case 'richtext':
            case 'plaintext':
                if ($returnValueObject) {
                    return $this->textValue;
                }
                return $this->textValue->value;
            case 'dropdown':
            case 'radio':
                if ($returnValueObject) {
                    return $this->labelledValues[0];
                }
                return [
                    $this->labelledValues[0]->id
                        => $this->labelledValues[0]->label
                ];
            case 'checkbox':
                if ($returnValueObject) {
                    return $this->labelledValues;
                }
                $values = [];
                foreach($this->labelledValues as $labelledValue) {
                    $values[$labelledValue->id] = $labelledValue->label;
                }
                return $values;
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
