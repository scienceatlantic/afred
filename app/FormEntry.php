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
                $query->orderBy('placement_order');
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

    public function getSectionTotal($sectionId)
    {
        if (!$section = FormSection::find($sectionId)) {
            return 0;
        }

        $fieldIds = $section->fields()->get()->implode('id', ',');
        $query = "
            SELECT
                MAX(indx) AS total
            FROM (
                SELECT
                    MAX(section_repeat_index) AS indx FROM string_values
                WHERE
                    string_values.form_entry_id = {$this->id}
                AND
                    string_values.form_field_id IN ($fieldIds)
                UNION ALL
                SELECT
                    MAX(section_repeat_index) AS indx FROM text_values
                WHERE
                    text_values.form_entry_id = {$this->id}
                AND
                    text_values.form_field_id IN ($fieldIds)           
                UNION ALL
                SELECT
                    MAX(section_repeat_index) AS indx FROM number_values
                WHERE
                    number_values.form_entry_id = {$this->id}
                AND
                    number_values.form_field_id IN ($fieldIds)
                UNION ALL
                SELECT
                    MAX(section_repeat_index) AS indx FROM date_values
                WHERE
                    date_values.form_entry_id = {$this->id}
                AND
                    date_values.form_field_id IN ($fieldIds)
            ) AS subquery
        ";

        return count($results = DB::select($query)) ? $results[0]->total : 0;
    }
}
