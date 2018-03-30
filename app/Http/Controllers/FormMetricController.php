<?php

namespace App\Http\Controllers;

use App\Directory;
use App\EntrySection;
use App\Http\Requests\FormMetricIndexRequest;
use Illuminate\Http\Request;

class FormMetricController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        FormMetricIndexRequest $request,
        $directoryId,
        $formId
    ) {
        $form = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId);

        $publishedIds = $form->formEntries()->published()->pluck('id');
        $hiddenIds = $form->formEntries()->hidden()->pluck('id');
        $approvedIds = $publishedIds->concat($hiddenIds);

        $sections = [];

        $formSections = $form
            ->formSections()
            ->orderBy('placement_order', 'asc')
            ->get();

        foreach($formSections as $formSection) {
            if (!$formSection->is_resource) {
                continue;
            }

            array_push($sections, [
                'label'  => $formSection->label_plural,
                'values' => [
                    'total'   => EntrySection
                        ::whereIn('form_entry_id', $approvedIds)
                        ->where('form_section_id', $formSection->id)
                        ->count(),
                    'public'  => EntrySection
                        ::whereIn('form_entry_id', $publishedIds)
                        ->where('form_section_id', $formSection->id)
                        ->where('is_public', true)
                        ->count(),
                    'private' => EntrySection
                        ::whereIn('form_entry_id', $publishedIds)
                        ->where('form_section_id', $formSection->id)
                        ->where('is_public', false)
                        ->count(),
                    'hidden' => EntrySection
                        ::whereIn('form_entry_id', $hiddenIds)
                        ->where('form_section_id', $formSection->id)
                        ->count()
                ]
            ]);
        }

        $statuses = [];
        foreach(['submitted', 'published', 'hidden', 'rejected', 'deleted'] as $status) {
            $statuses[$status] = [
                'value' => $form
                    ->formEntries()
                    ->$status()
                    ->count(),
                'wp_admin_url' => $form->directory->getTargetWpAdminBaseUrl()
                    . '/admin.php?page=afredwp-resources&afredwp-directory-id='
                    . $form->directory->id
                    . '&afredwp-form-id='
                    . $form->id
                    . '&afredwp-status='
                    . ucfirst($status)
            ];
        }

        return [
            'statuses' => $statuses,
            'approved_sections' => $sections
        ];
    }
}
