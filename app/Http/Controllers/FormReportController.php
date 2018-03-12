<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Events\FormReportRequested;
use App\Http\Requests\FormReportShowRequest;

class FormReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(
        FormReportShowRequest $request,
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

        // TODO
        // e.g. facilities.*.name, facilities.*.*
        // e.g. facilities.0.name, facilities.0.organization.value, equipment.*.*
        // e.g. facilities.

        event(new FormReportRequested(
            $formReport,
            $request->user(),
            $request->fileType ?: 'csv'
        ));
    }
}
