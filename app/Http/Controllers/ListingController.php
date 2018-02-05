<?php

namespace App\Http\Controllers;

use App\Directory;
use Log;
use View;
use App\Http\Requests\ListingRequest;

class ListingController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        ListingRequest $request,
        $directoryId,
        $formId,
        $formEntryId,
        $entrySectionId,
        $listingId
    ) {
        $listing = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries()
            ->findOrFail($formEntryId)
            ->entrySections()
            ->findOrFail($entrySectionId)
            ->listings()
            ->with('entrySection.formEntry')
            ->findOrFail($listingId);
        
        $data = $listing->entrySection->formEntry->toArray();
        $data['entrySectionId'] = $listing->entry_section_id;

        if (View::exists($listing->template)) {
            try {
                return [
                    'html' => View::make($listing->template, $data)->render()
                ];
            } catch (\Exception $e) {
                // Log and go to abort command below.
                Log::error($e->getMessage());
            }
        }

        abort(400);
    }
}
