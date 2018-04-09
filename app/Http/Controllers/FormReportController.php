<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Events\FormReportRequested;
use App\Http\Requests\FormReportGenerateRequest;
use App\Http\Requests\FormReportIndexRequest;

class FormReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        FormReportIndexRequest $request,
        $directoryId,
        $formId
    ) {
        return $this->pageOrGet(
            Directory
                ::findOrFail($directoryId)
                ->forms()
                ->findOrFail($formId)
                ->formReports()
        );
    }

    public function generate(
        FormReportGenerateRequest $request,
        $directoryId,
        $formId,
        $formReportId
    ) {
        $formReport = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formReports()
            ->findOrFail($formReportId);

        event(new FormReportRequested(
            $formReport,
            $request->user(),
            $request->fileType ?: 'csv'
        ));        
    }
}
