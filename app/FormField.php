<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    public function type()
    {
        return $this->belongsTo('App\FormFieldType', 'form_field_type_id');
    }

    public function stringValues()
    {
        return $this->hasMany('App\FormFieldStringValue');
    }

    public function textValues()
    {
        return $this->hasMany('App\FormFieldTextValue');
    }

    public function numberValues()
    {
        return $this->hasMany('App\FormFieldNumberValue');
    }

    public function dateValues()
    {
        return $this->hasMany('App\FormFieldDateValue');
    }

    public function radioValues()
    {
        return $this->belongsToMany('App\FormFieldRadioValue');
    }

    public function dropdownValues()
    {
        return $this->belongsToMany('App\FormFieldDropdownValue');
    }

    public function checkboxValues()
    {
        return $this->belongsToMany('App\FormFieldCheckboxValue');
    }
}
