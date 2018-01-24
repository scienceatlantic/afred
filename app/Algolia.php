<?php

namespace App;

use AlgoliaSearch\Client as AlgoliaClient;

class Algolia
{   
    public static function addObjects(FormEntry $formEntry)
    {
        $client = self::getClient();

        $data = $formEntry->data;

        foreach($data['resource'] as $objectKey => $sections) {
            $formSection = $formEntry->form->formSections()
                ->where('object_key', $objectKey)->first();

            // Skip if not a resource.
            if (!$formSection->is_resource) {
                continue;
            }

            // Get all search indices for this particular section.
            $searchIndices = $formSection->getSearchIndices();

            // Create search object (exclude the target entry section - 
            // will add them one-by-one below).
            $searchObject = array_except($data['resource'], $objectKey);

            foreach($sections as $section) {
                // ID that will be used by Algolia.s
                $searchObject['objectID'] = $section['entry_section_id'];

                $searchObject[$objectKey] = $section;

                // Add object to each linked search index.
                foreach($searchIndices as $searchIndex) {
                    $client->initIndex($searchIndex)->addObject($searchObject);
                }          
            }
        }
    }

    public function deleteObjects(FormEntry $formEntry)
    {
/*         $client = self::getClient();

        foreach($formEntry->form->formSection as $formSection) {
            if (!$formSection->is_resource) {
                continue;
            }
            
            foreach($formSection->getSearchIndices() as $searchIndex) {

            }
        }
        $client->initIndex($index)->deleteObjects([]); */
    }

    private static function getClient()
    {
        return new AlgoliaClient(env('ALGOLIA_APP_ID'), env('ALGOLIA_SECRET'));
    }
}
