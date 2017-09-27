<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class FormEntry extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['sections'];

    public function getSectionsAttribute()
    {
        return FormSection::with([
            'fields' => function($query) {
                $query->orderBy('page_placement_order');
            },
            'fields.type',
            'fields.stringValues' => function($query) {
                $query->where('form_entry_id', $this->id);
            },
            'fields.textValues' => function($query) {
                $query->where('form_entry_id', $this->id);
            },
            'fields.numberValues' => function($query) {
                $query->where('form_entry_id', $this->id);
            },
            'fields.dateValues' => function($query) {
                $query->where('form_entry_id', $this->id);
            },
            'fields.radioValues' => function($query) {
                $ids = DB::table('form_entry_form_field_radio_value')
                    ->where('form_entry_id', $this->id)
                    ->pluck('form_field_radio_value_id');

                $query->whereIn('form_field_radio_values.id', $ids);
            }
        ])->get();        
    }
}
