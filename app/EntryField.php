<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntryField extends Model
{
    /**
     * Relationship with the entry section that its values belong to.
     */
    public function entrySection()
    {
        return $this->belongsTo('App\EntrySection');
    }

    /**
     * Relationship with the form field it belongs to.
     */
    public function formField()
    {
        return $this->belongsTo('App\FormField');
    }

    /**
     * Relationship that contains its string value (if applicable).
     */
    public function stringValue()
    {
        return $this->hasOne('App\StringValue');
    }

    /**
     * Relationship that contains its text value (if applicable).
     */    
    public function textValue()
    {
        return $this->hasOne('App\TextValue');
    }

    /**
     * Relationship that contains its numeric value (if applicable).
     */    
    public function numberValue()
    {
        return $this->hasOne('App\NumberValue');
    }

    /**
     * Relationship that contains its date value (if applicable).
     */    
    public function dateValue()
    {
        return $this->hasOne('App\DateValue');
    }

    /**
     * Relationship that contains its radio, dropdown, or checkbox value(s)
     * (if applicable).
     */    
    public function labelledValues()
    {
        return $this->belongsToMany('App\LabelledValue')->withTimestamps();
    }

    /**
     * Returns the entry field's value.
     * 
     * This method will return the correct value based on the form field's type
     * this entry field belongs to.
     * 
     * @param {boolean=false} $returnValueObject Return the value property
     *     directly or the entire table itself.
     */
    public function getValue($returnValueObject = false)
    {
        $type = $this->fresh()->formField->type->name;
        
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
                    'labelled_value_id' => $this->labelledValues[0]->id,
                    'value'             => $this->labelledValues[0]->label
                ];
            case 'checkbox':
                if ($returnValueObject) {
                    return $this->labelledValues;
                }
                $values = [];
                foreach($this->labelledValues as $labelledValue) {
                    array_push($values, [
                        'labelled_value_id' => $labelledValue->id,
                        'value'             => $labelledValue->label
                    ]);
                }
                return $values;
            default:
                return null;
        }
    }

    /**
     * Generated "value" attribute.
     * 
     * This method utilises the `getValue()` method above to always include
     * the entry field's value with all JSON responses.
     */
    public function getValueAttribute()
    {
        return $this->getValue(false);
    }

    /**
     * Use this method to set entry field's (dynamic) value property.
     * 
     * This is a helper method. Instead of manually attaching the right table
     * (i.e. string_values, date_values, etc.) to the entry field based on its
     * form field's type, this method will take care of that for you.
     * 
     * Just use it like this:
     * $entryField->value = '2018-01-01 12:00:00';
     * 
     * Please note that the entry field has to already exist in the database
     * before this method is used.
     */
    public function setValue($value)
    {
        $type = $this->fresh()->formField->type->name;

        // Determine the right model class to use (i.e. StringValue,
        // DateValue, etc.)
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

        // Attach the *_values table to the entry field and save it.
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
