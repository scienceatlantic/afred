<?php

namespace App;

use AlgoliaSearch\Client as AlgoliaClient;
use App\FormEntry;
use App\FormSection;
use App\Listing;

class Algolia
{   
    public static function addListing(
        FormEntry $formEntry,
        Listing $listing
    ) {
        // Skip if already added.
        if ($listing->is_in_algolia) {
            return $listing;
        }

        $client = self::getClient();

        // Get target form section.
        $targetFormSection = $listing->formSection;

        // Add each listing to Algolia.
        $client
            ->initIndex($targetFormSection->search_index)
            ->addObject(self::getSearchObject($formEntry, $listing));

        $listing->is_in_algolia = true;
        $listing->update();
        
        return $listing;
    }

    public static function deleteListing(
        FormSection $targetFormSection,
        $publishedEntrySectionId
    ) {
        $client = self::getClient();
        
        return $client
            ->initIndex($targetFormSection->search_index)
            ->deleteObject($publishedEntrySectionId);
    }

    public static function getClient()
    {
        return new AlgoliaClient(env('ALGOLIA_APP_ID'), env('ALGOLIA_SECRET'));
    }

    private static function getSearchObject(
        FormEntry $formEntry,
        Listing $listing
    ) {
        // Get target section.
        $targetSection = $listing->entrySection->formSection;

        // Create the search object.
        $data = [];

        // The target section is added as a single object (instead of all the
        // elements in that array).
        $data['sections'][$targetSection->object_key] = self::findFieldset(
            $listing->entrySection->id,
            $formEntry->data['sections'][$targetSection->object_key]
        );

        // Add other sections that should also be included in the search object.
        foreach($targetSection->formSectionsIncludedInSearch as $formSection) {
            $key = $formSection->object_key;

            // Add section only if it exists in the dataset.
            if (isset($formEntry->data['sections'][$key])) {
                $fieldsets = $formEntry->data['sections'][$key];
                $data['sections'][$key] = self::getPublicFieldsets($fieldsets);
            }
        }

        // ID that will be used by Algolia to uniquely identify the object.
        $data['objectID'] = $listing->published_entry_section_id;

        // Add other meta properties.
        $data['pagination_title'] = $formEntry->data['pagination_title'];
        $data['form_entry_id'] = $formEntry->id;
        $data['root_directory_id'] = $listing->root_directory->id;
        $data['target_directory_id'] = $listing->target_directory->id;
        $data['wp_post_id'] = $listing->wp_post_id;
        $data['wp_post_url'] = $listing->wp_post_url;

        return $data;
    }

    private static function findFieldset(
        $entrySectionId,
        $fieldsets
    ) {
        foreach($fieldsets as $fieldset) {
            if ($fieldset['entry_section']['id'] === $entrySectionId) {
                return $fieldset;
            }
        }
        return null;
    }

    private static function getPublicFieldsets($fieldsets)
    {
        $data = [];

        foreach($fieldsets as $fieldset) {
            if ($fieldset['entry_section']['is_public']) {
                array_push($data, $fieldset);
            }
        }

        return $data;
    }
}
