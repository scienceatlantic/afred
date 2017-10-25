<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    public function section()
    {
        return $this->belongsTo('App\FormSection', 'form_section_id');
    }

    public function type()
    {
        return $this->belongsTo('App\FieldType', 'field_type_id');
    }

    public function stringValues()
    {
        return $this->hasMany('App\StringValue');
    }

    public function textValues()
    {
        return $this->hasMany('App\TextValue');
    }

    public function numberValues()
    {
        return $this->hasMany('App\NumberValue');
    }

    public function dateValues()
    {
        return $this->hasMany('App\DateValue');
    }

    public function labelledValues()
    {
        return $this->belongsToMany('App\LabelledValue');
    }
}
