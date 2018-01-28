<?php

namespace App;

use GuzzleHttp\Client as GuzzleHttp;

class WordPress
{
    public static function addResources(FormEntry $formEntry)
    {
        $homeForm = $formEntry->form;

        foreach($homeForm->formSections as $homeFormSection) {
            // Skip if not a resource.
            if (!$homeFormSection->is_resource) {
                continue;
            }

            // Get all entry sections of `$homeFormSection` type.
            $entrySections = $formEntry
                ->entrySections()
                ->where('form_section_id', $homeFormSection->id)
                ->get();

            // Add each entry section to each WordPress installation that it is
            // linked (attached) to.
            foreach($entrySections as $entrySection) {
                foreach($entrySection->formSectionsAttachedTo as $formSectionAttachedTo) {
                    $formAttachedTo = $formSectionAttachedTo->form;
                    $directoryAttachedTo = $formAttachedTo->directory;

                    $url = $directoryAttachedTo->wp_api_base_url 
                        . '/'
                        . env('WP_CUSTOM_POST_TYPE_REST_BASE');
                    $slug = $homeFormSection->slug_prefix . '_' . $entrySection->id;

                    foreach($homeFormSection->getResourceTemplates() as $template) {
                        $content = "[afredwp_resource "
                            . "directoryId='{$directoryAttachedTo->id}' "
                            . "formId='{$formAttachedTo->id}' "
                            . "formEntryId='{$formEntry->id}' "
                            . "entrySectionId='{$entrySection->id}' "
                            . "template='{$template}']";

                        $response = self::addResource(
                            $url, 
                            $directoryAttachedTo->wp_api_password,
                            $slug,
                            $entrySection->title,
                            $content
                        );

                        // TODO: What if it fails?

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

    public static function deleteResources(FormEntry $formEntry)
    {
        $entrySections = $formEntry->entrySections;

        foreach($entrySections as $entrySection) {
            foreach($entrySection->formSectionsAttachedTo as $formSectionAttachedTo) {
                $directoryAttachedTo = $formSectionAttachedTo->form->directory;

                // Skip if WordPress post id does not exist.
                if (!$wpPostId = $formSectionAttachedTo->pivot->wp_post_id) {
                    continue;
                }

                $url = $directoryAttachedTo->wp_api_base_url 
                    . '/' 
                    . env('WP_CUSTOM_POST_TYPE_REST_BASE')
                    . '/'
                    . $wpPostId;
                
                $response = self::deleteResource(
                    $url,
                    $directoryAttachedTo->wp_api_password
                );
            }
        }
    }

    public static function addResource(
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

    public static function deleteResource(
        $url,
        $password,
        $bypassTrash = false
    ) {
        return (new GuzzleHttp())->delete($url, [
            'headers' => [
                'Authorization' => "Basic {$password}"
            ],
            'json'   => [
                'force' => $bypassTrash
            ]
        ]);        
    }
}
