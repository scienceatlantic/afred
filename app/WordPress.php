<?php

namespace App;

use App\Directory;
use GuzzleHttp\Client as GuzzleHttp;
use Log;

class WordPress
{
    public static function addListing(Listing $listing)
    {
        // Skip if already in WP.
        if ($listing->is_in_wp) {
            return $listing;
        }

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

        // Get Vue root container id.
        $vueRootId = env('VUE_ROOT_ID');

        // Generate content.
        $content = "<div id='{$vueRootId}'>"
            . "[afredwp_resource "
            . "directoryId='{$rootDirectory->id}' "
            . "formId='{$rootForm->id}' "
            . "formEntryId='{$listing->entrySection->formEntry->id}' "
            . "listingId='{$listing->id}']"
            . "</div>";

        // Append WordPress post ID to URL if available. This will turn the
        // operation into an update.
        if ($listing->wp_post_id) {
            $url .= '/' . $listing->wp_post_id;
        }

        $response = (new GuzzleHttp())->post($url, [
            'headers' => [
                'Authorization' => "Basic {$targetDirectory->wp_api_password}"
            ],
            'json'    => [
                'slug'    => $slug,
                'title'   => $listing->entrySection->title,
                'content' => $content,
                'status'  => 'publish'
            ],
            'http_errors' => true
        ]);

        $wpPost = json_decode($response->getBody(), true);

        if (!isset($wpPost['id'])) {
            $msg = 'Failed to add listing to WordPress.';
            Log::error($msg, ['response body' => $wpPost]);
            abort(500);
        }

        $listing->wp_post_id = $wpPost['id'];
        $listing->wp_slug = $wpPost['slug'];
        $listing->is_in_wp = true;
        $listing->update();

        return $listing;
    }

    public static function deleteListing(
        Directory $targetDirectory,
        $wpPostId,
        $bypassTrash = true
    ) {
        $url = $targetDirectory->wp_api_base_url
            . '/'
            . env('WP_CUSTOM_POST_TYPE_REST_BASE')
            . '/'
            . $wpPostId;

        return (new GuzzleHttp())->delete($url, [
            'headers' => [
                'Authorization' => "Basic {$targetDirectory->wp_api_password}"
            ],
            'json' => [
                'force' => $bypassTrash
            ],
            'http_errors' => true
        ]);
    }

    public static function hideListing(
        Directory $targetDirectory,
        $wpPostId
    ) {
        $url = $targetDirectory->wp_api_base_url
            . '/'
            . env('WP_CUSTOM_POST_TYPE_REST_BASE')
            . '/'
            . $wpPostId;        

        return (new GuzzleHttp())->post($url, [
            'headers' => [
                'Authorization' => "Basic {$targetDirectory->wp_api_password}"
            ],
            'json' => [
                'status' => 'private'
            ],
            'http_errors' => true
        ]);
    }

    public static function unhideListing(
        Directory $targetDirectory,
        $wpPostId
    ) {
        $url = $targetDirectory->wp_api_base_url
            . '/'
            . env('WP_CUSTOM_POST_TYPE_REST_BASE')
            . '/'
            . $wpPostId;        

        return (new GuzzleHttp())->post($url, [
            'headers' => [
                'Authorization' => "Basic {$targetDirectory->wp_api_password}"
            ],
            'json' => [
                'status' => 'publish'
            ],
            'http_errors' => true
        ]);        
    }
}
