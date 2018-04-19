<?php

namespace App;

use AlgoliaSearch\Client as AlgoliaClient;
use App\FormEntry;
use App\FormSection;
use App\Listing;

class Algolia
{   
    /**
     * Adds a listing to Algolia.
     */
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

    /**
     * Deletes a listing from Algolia.
     */
    public static function deleteListing(
        FormSection $targetFormSection,
        $publishedEntrySectionId
    ) {
        $client = self::getClient();
        
        return $client
            ->initIndex($targetFormSection->search_index)
            ->deleteObject($publishedEntrySectionId);
    }

    /**
     * Get an Algolia Client instance.
     */
    public static function getClient()
    {
        return new AlgoliaClient(env('ALGOLIA_APP_ID'), env('ALGOLIA_SECRET'));
    }

    /**
     * Creates the search object that will be uploaded to Algolia.
     */
    private static function getSearchObject(
        FormEntry $formEntry,
        Listing $listing
    ) {
        // Get target section.
        $targetSection = $listing->formSection;

        // Alias
        $targetSectObjKey = $targetSection->object_key;

        // Create the search object.
        $data = [];

        // The target section is added as a single object (instead of all the
        // elements in that array).
        $data['sections'][$targetSectObjKey] = self::findFieldset(
            $listing->entrySection->id,
            $formEntry->data['sections'][$targetSectObjKey]
        );

        // Remove private fields from target section's fieldset.
        $data['sections'][$targetSectObjKey] = self::getPublicFields(
            $targetSection,
            $data['sections'][$targetSectObjKey]
        );

        // Add other sections that should also be included in the search object.
        foreach($targetSection->formSectionsIncludedInSearch as $formSection) {
            $key = $formSection->object_key;

            // Skip section if it doesn't exist in the form entry.
            if (!isset($formEntry->data['sections'][$key])) {
                continue;
            }

            // Get public fieldsets.
            $data['sections'][$key] = self::getPublicFieldsets(
                $formEntry->data['sections'][$key]
            );

            // Filter out private fields from each fieldset.
            foreach($data['sections'][$key] as $i => $fieldset) {
                $data['sections'][$key][$i] = self::getPublicFields(
                    $formSection,
                    $fieldset
                );
            }
        }

        // ID that will be used by Algolia to uniquely identify the object.
        $data['objectID'] = $listing->published_entry_section_id;

        // Search template's object key.
        $data['search_template_object_key'] = $listing->search_template_object_key;

        // Add other meta properties.
        $data['pagination_title'] = $formEntry->data['pagination_title'];
        $data['form_entry_id'] = $formEntry->id;
        $data['root_directory_id'] = $listing->root_directory->id;
        $data['target_directory_id'] = $listing->target_directory->id;
        $data['wp_post_id'] = $listing->wp_post_id;
        $data['wp_post_url'] = $listing->wp_post_url;

        return $data;
    }

    /**
     * Looks for a specific fieldset (i.e. entry section) within a provided
     * set of fieldsets (i.e. entry sections).
     */
    private static function findFieldset(
        $entrySectionId,
        $fieldsets
    ) {
        return array_first($fieldsets,
            function($fieldset) use ($entrySectionId) {
                return $fieldset['entry_section']['id'] === $entrySectionId;
            },
        null);
    }

    /**
     * Returns an array of public (i.e. is_public = true) fieldsets (i.e.
     * entry sections).
     */
    private static function getPublicFieldsets($fieldsets)
    {
        return array_where($fieldsets, function($fieldset) {
            return $fieldset['entry_section']['is_public'];
        });
    }

    /**
     * Returns an array of public (i.e. is_searchable = true) fields (i.e. entry
     * fields).
     */
    private static function getPublicFields(
        FormSection $formSection,
        $fieldset
    ) {
        return array_except(
            $fieldset,
            $formSection
                ->formFields()
                ->where('is_searchable', 0)
                ->pluck('object_key')
                ->toArray()
        );
    }
}
