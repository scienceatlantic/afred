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
                $query->orderBy('form_placement_order');
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
            'fields.labelledValues' => function($query) {
                $query->whereIn('labelled_values.id',
                    DB::table('form_entry_labelled_value')
                        ->where('form_entry_id', $this->id)
                        ->pluck('labelled_value_id')
                );
            }
        ])->get();        
    }
}
