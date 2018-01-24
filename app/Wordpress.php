<?php

namespace App;

use GuzzleHttp\Client as GuzzleHttp;

class Wordpress
{
    public static function saveResources(FormEntry $formEntry)
    {
        $form = $formEntry->form;
        $directory = $form->directory;

        foreach($form->formSections as $formSection) {
            // Skip if not a resource.
            if (!$formSection->is_resource) {
                continue;
            }

            // Get all entry sections of `$formSection` type.
            $entrySections = $formEntry
                ->entrySections()
                ->where('form_section_id', $formSection->id)
                ->get();

            
            foreach($entrySections as $entrySection) {
                foreach($entrySection->formSectionsAttachedTo as $formSectionAttachedTo) {
                    $url = $directory->wp_api_base_url . '/r';
                    $slug = $formSection->slug_prefix . '_' . $entrySection->id;

                    foreach($formSection->getResourceTemplates() as $template) {
                        $content = "[afredwp_resource "
                        . "directoryId='{$directory->id}' "
                        . "formId='{$form->id}' "
                        . "formEntryId='{$formEntry->id}' "
                        . "entrySectionId='{$entrySection->id}' "
                        . "template='{$template}']";

                        $response = self::saveResource(
                            $url, 
                            $directory->wp_api_password,
                            $slug,
                            $entrySection->title,
                            $content
                        );

                        $wpPost = json_decode($response->getBody(), true);
                        
                        $entrySection
                            ->formSectionsAttachedTo()
                            ->updateExistingPivot($formSectionAttachedTo->id, [
                                'wp_post_id' => $wpPost['id'],
                                'wp_slug'    => $wpPost['slug']
                            ]);                        
                    }
                }
            }
        }
    }

    public static function updateResources(
        FormEntry $oldFormEntry,
        FormEntry $newFormEntry
    ) {

    }

    public static function saveResource(
        $url,
        $password,
        $slug,
        $title,
        $content
    ) {
        return (new GuzzleHttp())->post($url, [
            'headers' => [
                'Authorization' => "Basic {$password}"
            ],
            'json'    => [
                'slug'    => $slug,
                'title'   => $title,
                'content' => $content,
                'status'  => 'publish'
            ]
        ]);
    }
}
