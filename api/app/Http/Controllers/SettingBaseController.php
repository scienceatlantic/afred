<?php

namespace App\Http\Controllers;

// Requests.
use Illuminate\Http\Request;

class SettingBaseController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function updateRecord(Request $request, $record)
    {
        switch ($request->type) {
            case 'INT':
            case 'BOOLEAN':
            case 'DOUBLE':
            case 'DATE':
            case 'DATETIME':
            case 'EMAIL':
            case 'URL':
            case 'STRING':
            case 'JSON':
                $record->value = $request->value;
                break;
            case 'TEXT':
            case 'JSONTEXT':
                if (!$text = $record->text()->first()) {
                    $text = $record->text()->create([]);
                } 
                $text->value = $request->value;
                $text->save();
                break;
        }
        $record->dateUpdated = $this->now();
        $record->save();

        return $record;
    }
}
