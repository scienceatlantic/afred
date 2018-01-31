<?php

namespace App;

use GuzzleHttp\Client as GuzzleHttp;

class WordPress
{
    public static function addListing(Listing $listing)
    {
        // Aliases.
        $rootForm = $listing->entrySection->formEntry->form;
        $rootDirectory = $rootForm->directory;
        $targetFormSection = $listing->formSection;
        $targetDirectory = $targetFormSection->form->directory;

        // Generate URL and slug.
        $url = $targetDirectory->wp_api_base_url
            . '/'
            . env('WP_CUSTOM_POST_TYPE_REST_BASE');
        $slug = $targetFormSection->slug_prefix 
            . '_' 
            . $listing->entrySection->id;

        // Generate content.
        $content = "[afredwp_resource "
            . "directoryId='{$rootDirectory->id}' "
            . "formId='{$rootForm->id}' "
            . "formEntryId='{$listing->entrySection->formEntry->id}' "
            . "entrySectionId='{$listing->entrySection->id}' "
            . "listingId='{$listing->id}']";

        // Append WordPress post id to URL if available. This will turn the
        // operation into an update.
        if ($listing->wp_post_id) {
            $url .= '/' . $listing->wp_post_id;
        }

        return self::addResource(
            $url,
            $targetDirectory->wp_api_password,
            $slug,
            $listing->entrySection->title,
            $content
        );
    }

    public static function deleteListing(
        Listing $listing,
        $bypassTrash = false
    ) {
        // Get target directory.
        $targetDirectory = $listing->formSection->form->directory;

        // Generate URL.
        $url = $targetDirectory->wp_api_base_url
            . '/'
            . env('WP_CUSTOM_POST_TYPE_REST_BASE')
            . '/'
            . $listing->wp_post_id;

        return self::deleteResource(
            $url,
            $targetDirectory->wp_api_password,
            $bypassTrash
        );
    }

    private static function addResource(
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
            ],
            'http_errors' => false
        ]);
    }

    private static function deleteResource(
        $url,
        $password,
        $bypassTrash = false
    ) {
        return (new GuzzleHttp())->delete($url, [
            'headers' => [
                'Authorization' => "Basic {$password}"
            ],
            'json' => [
                'force' => $bypassTrash
            ],
            'http_errors' => false
        ]);        
    }
}
