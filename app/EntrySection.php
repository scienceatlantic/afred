<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntrySection extends Model
{
    public function formSectionsAttachedTo()
    {
        return $this->belongsToMany('App\FormSection')
            ->withPivot('wp_post_id', 'wp_slug')
            ->withTimestamps();
    }

    public function formEntry()
    {
        return $this->belongsTo('App\FormEntry');
    }

    public function formSection()
    {
        return $this->belongsTo('App\FormSection');
    }

    public function entryFields()
    {
        return $this->hasMany('App\EntryField');
    }

    public function getTitleAttribute()
    {
        $key = $this->formSection()->first()->field_resource_title_object_key;
        $fields = $this->entryFields()->get()->keyBy('formField.object_key');
        
        if (isset($fields[$key])) {
            return $fields[$key]->value;
        }
        return null;
    }
}
