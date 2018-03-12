<?php

namespace App;

use App\Form;
use App\FormReport;
use Illuminate\Database\Eloquent\Model;
use Log;
use Maatwebsite\Excel\Facades\Excel;

class FormReport extends Model
{
    public static $mimeTypeMap = [
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'csv'  => 'text/csv'
    ];

    /** 
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cache'
    ];    
    
    public function form()
    {
        return $this->belongsTo('App\Form');
    }

    public function statuses()
    {
        return $this->belongsToMany(
            'App\FormEntryStatus',
            'form_entry_status_form_report',
            'form_report_id',
            'form_entry_status_id'
        )->withTimestamps();
    }

    public function generate($fileType = 'xlsx')
    {
        $filename = $this->filename
            . ' '
            . now()->format('(M j, Y) (h-i-s A)')
            . '_'
            . str_random(8);

        $report = Excel::create($filename, function($excel) {
            $excel->sheet('Sheet 1', function($sheet) {
                $self = $this->fresh();
                $formEntries = $self->form->formEntries();

                list($headers, $sections, $columns) = $self->report_columns;

                if ($self->statuses->count()) {
                    $formEntries->whereIn(
                        'form_entry_status_id',
                        $self->statuses()->pluck('form_entry_status_id')
                    );
                }

                $sheet->row(1, $headers);
                foreach($formEntries->get() as $formEntry) {
                    $sheet->rows(self::getRows($formEntry, $sections, $columns));
                }
            });
        })->store($fileType, false, true);

        return [
            $report['full'],
            $report['file'],
            $report['title'],
            self::$mimeTypeMap[$report['ext']],
        ];
    }

    public function getCacheAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return json_decode($value, true);
    }

    public function setCacheAttribute($value)
    {
        $this->attributes['cache'] = json_encode($value);
    }

    public function getReportColumnsAttribute($value)
    {
        if ($cache = $this->cache) {
            return $cache;
        }

        $self = $this->fresh();
        $headers = [];
        $sectionsMap = [];
        $sections = [];
        $columns = [];

        foreach(explode(',', $value) as $reportColumn) {
            list($section, $i, $field) = explode('.', $reportColumn);

            // Store section if not already added.
            if (!array_key_exists($section, $sectionsMap)) {
                array_push($sections, $section);
            }

            $formSection = $self
                ->form
                ->formSections()
                ->where('object_key', $section)
                ->first();

            if (!$formSection) {
                self::warnFormSectionMissing($section);
                continue;
            }                

            // '*' Wildcard operator, get all fields.
            if ($field === '*') {
                $formFields = $formSection
                    ->formFields()
                    ->orderBy('placement_order', 'desc')
                    ->get();

                foreach($formFields as $formField) {
                    array_push($columns, [
                        $section,
                        $i,
                        $formField->object_key
                    ]);

                    array_push($headers, $formField->label);
                }
            } 
            // Get specified field.
            else {
                $formField = $formSection
                    ->formFields()
                    ->where('object_key', $field)
                    ->first();
                
                if (!$formField) {
                    self::warnFormFieldMissing($field);
                }

                array_push($headers, $formField->label);
                array_push($columns, [$section, (int) $i, $field]);
            }
        }

        $cache = [$headers, $sections, $columns];
        $this->cache = $cache;
        $this->update();

        return $cache;
    }

    private static function getRows(FormEntry $formEntry, $sections, $columns)
    {
        $dataSections = $formEntry->data['sections'];
        $max = 0;
        $rows = [];

        // Get max section.
        foreach($sections as $section) {
            $sectionLength = count($dataSections[$section]);
            $max = $max < $sectionLength ? $sectionLength : $max;
        }
        
        for ($index = 0; $index < $max; $index++) {
            $row = [];

            foreach($columns as $column) {
                list($s, $i, $f) = $column;
    
                $i = $i === '*' ? $index : $i;
    
                // For radio and dropdown fields.
                if (isset($dataSections[$s][$i][$f]['value'])) {
                    array_push($row, $dataSections[$s][$i][$f]['value']);
                }
                // For other fields.
                else if (isset($dataSections[$s][$i][$f])) {
                    // Empty string for checkbox values (can't store arrays in
                    // cells).
                    if (is_array($dataSections[$s][$i][$f])) {
                        array_push($row, '');
                    } else {
                        array_push($row, $dataSections[$s][$i][$f]);
                    }
                }
                // Empty string if field is empty.
                else {
                    array_push($row, '');
                }
            }

            array_push($rows, $row);
        }

        return $rows;
    }

    private static function warnFormSectionMissing($section)
    {
        $msg = 'Form section missing. Skipping section in report';
        Log::warning($msg, [
            'formSectionObjectKey' => $section
        ]);
    }

    private static function warnFormFieldMissing($field)
    {
        $msg = 'Form field missing. Skipping field in report';
        Log::warning($msg, [
            'formFieldObjectKey' => $field
        ]);
    }
}
