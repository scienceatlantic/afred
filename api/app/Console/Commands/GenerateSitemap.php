<?php

namespace App\Console\Commands;

// Laravel.
use Illuminate\Console\Command;

// Misc.
use Log;
use SimpleXMLElement;

// Models.
use App\Facility;
use App\Setting;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a sitemap.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Flags.
        // Add 'lastmod' tags to sitemap. Default is false.
        $addLastmod = false;

        // Hard-coded (i.e. not from database) URLs.
        $fixedUrls = [
            '/search',
            '/facilities/form/create',
            '/facilities/update',
            '/about',
            '/about/legal/privacy-policy',
            '/about/legal/terms-of-service',
            '/about/legal/disclaimer',
            '/contact'
        ];

        // If sitemap filename is not set in .env, quit.
        if (!($smFilename = env('_SITEMAP_FILENAME'))) {
            $msg = '"_SITEMAP_FILENAME" key not found in ".env". Exiting...';
            $this->error($msg);
            return;
        }

        // Get app address (base of all URLs).
        try {
            $base = Setting::find('appAddress')->value;
        } catch (Exception $e) {
            $msg = 'Failed to retrieve "appAddress" from Settings table.'
                 . 'Exiting...';
            Log::error($msg);
            $this->error($msg);
            return;
        }

        // Create XML object.
        $sm = '<?xml version="1.0" encoding="UTF-8"?>';
        $sm .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $sm .= '</urlset>';
        $sm = new SimpleXMLElement($sm);

        // Insert fixed URLs.
        foreach($fixedUrls as $url) {
            $urlElement = $sm->addChild('url');
            $locElement = $urlElement->addChild('loc', $base . $url);
        }

        // Insert dynamic (public facilities and equipment) URLs.
        foreach(Facility::notHidden()->with('equipment')->get() as $f) {
            // Insert public facility URL and lastmod.
            // I.e. https://server/facilities{f-id}.
            $fUrl = $base . '/facilities/' . $f->id;
            $urlElement = $sm->addChild('url');
            $urlElement->addChild('loc', $fUrl);
            if ($addLastmod) {
                $lastMod = $f->dateUpdated->toIso8601String();
                $urlElement->addChild('lastmod', $lastMod);
            }

            // Insert public equipment URLs lastmods.
            // I.e. https://server/facilities/{f-id}/equipment/{e-id}.
            foreach($f->equipment as $e) {
                if ($e->isPublic) {
                    $eUrl = $fUrl . '/equipment/' . $e->id;
                    $urlElement = $sm->addChild('url');
                    $urlElement->addChild('loc', $eUrl);
                    if ($addLastmod) {
                        $urlElement->addChild('lastmod', $lastMod);
                    }
                }
            }
        }

        // Create sitemap file.
        try {
            $handle = fopen($smFilename, 'w');
            fwrite($handle, $sm->asXML());
            fclose($handle);
        } catch (Exception $e) {
            $msg = 'Failed to write:' . $smFileName . '. Exiting...';
            $this->error($msg);
            return;
        }

        $this->info('Sitemap generated.');
    }
}
