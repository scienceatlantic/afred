<?php

namespace App;

use App\Form;
use App\FormReport;
use Illuminate\Database\Eloquent\Model;
use Log;
use Maatwebsite\Excel\Facades\Excel;

class FormReport extends Model
{
    /**
     * Map of file extensions with their respective MIME types.
     */
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
    
    /**
     * Relationship with the form it belongs to.
     */
    public function form()
    {
        return $this->belongsTo('App\Form');
    }

    /**
     * Relationship with the form entry statuses a report is being narrowed down
     * to.
     * 
     * The point of this relationship is to narrow down a report to a set of 
     * statuses. I.e. you could create a report called "List of published
     * equipment" and use this relationship to attach the report to the 
     * "Published" form entry status so that only published data is returned.
     */
    public function statuses()
    {
        return $this->belongsToMany(
            'App\FormEntryStatus',
            'form_entry_status_form_report',
            'form_report_id',
            'form_entry_status_id'
        )->withTimestamps();
    }

    /**
     * Generates the report.
     * 
     * @param $fileType {string=xlsx} "xlsx", "xls", or "csv"
     * 
     * @return {array} An array containing:
     * 
     * "full"  => absolute path + filename
     * "file"  => just the file name (without the path)
     * "title" => title of the report
     * "ext"   => MIME type
     */
    public function generate($fileType = 'xlsx')
    {
        // Generate the file name (attach a random string at the end to avoid
        // clashes).
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

                // Narrow down by status if applicable
                if ($self->statuses->count()) {
                    $formEntries->whereIn(
                        'form_entry_status_id',
                        $self->statuses()->pluck('form_entry_status_id')
                    );
                }

                // First row contains the headers
                $sheet->row(1, $headers);

                // Extract row(s) from each form entry.
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

    /**
     * Laravel getter method to access the cache.
     * 
     * If the cache is not empty, it will return an associative array.
     * 
     * The cache contains parsed data from information stored in the
     * `report_columns` attribute.
     */
    public function getCacheAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return json_decode($value, true);
    }

    /**
     * Laravel setter method to set the cache.
     * 
     * The value provided is encoded into JSON. Setting the cache to null will
     * force the API to generate it.
     */
    public function setCacheAttribute($value)
    {
        $this->attributes['cache'] = json_encode($value);
    }

    /**
     * Laravel getter method for the report_columns column.
     * 
     * Returns an array in this format:
     * 
     * 0 => [headers] (e.g. ["name", "type", ...])
     * 1 => [form section object keys] (e.g. ["facilities", "equipment"])
     * 2 => [
     *   {"form section object key"."index"."form field object key"},
     *   ...
     * ] (e.g. 
     * [  
     *   "facilities",
     *   0,
     *   "name"
     * ])
     * 
     * The value returned from this method will be used to generate the report.
     */
    public function getReportColumnsAttribute($value)
    {
        // Return cache if available.
        if ($cache = $this->cache) {
            return $cache;
        }

        $self = $this->fresh();
        $headers = [];
        $sectionsMap = [];
        $sections = [];
        $columns = [];

        // Parse the values.
        foreach(explode(',', $value) as $reportColumn) {
            // Breakdown each segment (delimited by a comma)
            list($section, $indexOp, $field) = explode('.', $reportColumn);

            // Store section if not already added.
            if (!array_key_exists($section, $sectionsMap)) {
                array_push($sections, $section);
            }

            // Get the form section if it exists, otherwise warn and skip.
            $formSection = $self
                ->form
                ->formSections()
                ->where('object_key', $section)
                ->first();
            if (!$formSection) {
                self::warnFormSectionMissing($section);
                continue;
            } 
            
            // Parse index operator (cast to int if not '*').
            $i = $indexOp === '*' ? '*' : (int) $indexOp;

            // '*' Wildcard operator, get all fields.
            if ($field === '*') {
                $formFields = $formSection
                    ->formFields()
                    ->orderBy('placement_order', 'asc')
                    ->get();

                foreach($formFields as $formField) {
                    array_push($columns, [$section, $i, $formField->object_key]);
                    array_push($headers, $formField->label);
                }
            } 
            // Get specified field if it exists, otherwise warn and skip.
            else {
                $formField = $formSection
                    ->formFields()
                    ->where('object_key', $field)
                    ->first();
                if (!$formField) {
                    self::warnFormFieldMissing($field);
                    continue;
                }

                array_push($headers, $formField->label);
                array_push($columns, [$section, $i, $field]);
            }
        }

        // Store parsed values into cache.
        $cache = [$headers, $sections, $columns];
        $this->cache = $cache;
        $this->update();

        return $cache;
    }

    /**
     * Private helper method to extract the row(s) from a form entry's dataset.
     * 
     * Each form entry's data set will (probably) generate multiple rows of
     * data.
     */
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
                list($s, $indexOp, $f) = $column;
    
                // If index is a star, replace with current index value,
                // otherwise use indexOp's value.
                $i = $indexOp === '*' ? $index : $indexOp;
    
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

    /**
     * Logs a warning message if a form section's object key is missing (i.e.
     * specified in the `report_columns` column but does not exist in the
     * dataset).
     */
    private static function warnFormSectionMissing($section)
    {
        $msg = 'Form section missing. Skipping section in report';
        Log::warning($msg, [
            'formSectionObjectKey' => $section
        ]);
    }

    /**
     * Logs a warning message if a form field's object key is missing (i.e.
     * specified in the `report_columns` column but does not exist in the
     * dataset).
     */    
    private static function warnFormFieldMissing($field)
    {
        $msg = 'Form field missing. Skipping field in report';
        Log::warning($msg, [
            'formFieldObjectKey' => $field
        ]);
    }
}
