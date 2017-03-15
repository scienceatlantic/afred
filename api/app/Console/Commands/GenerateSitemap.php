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
        Log::info('Console: `sitemap:generate` called.');

        // Parameters.
        $addFacilityLastMod     = false;
        $addFacilityPriority    = false;
        $addFacilityChangeFreq  = false;
        $addEquipmentLastMod    = false;
        $addEquipmentPriority   = false;
        $addEquipmentChangeFreq = false;

        // Sitemap settings from database.
        list($base, $filename, $fixedUrls, $fileToPing) = Setting::lookup([
            'appAddress',
            'sitemapFilename',
            'sitemapFixedUrls'    => [],
            'sitemapPingFilename' => false
        ]);

        // Create XML object.
        $sm = '<?xml version="1.0" encoding="UTF-8"?>';
        $sm .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $sm .= '</urlset>';
        $sm = new SimpleXMLElement($sm);

        // Insert fixed URLs.
        foreach($fixedUrls as $url) {
            $urlElmt = $sm->addChild('url');
            $urlElmt->addChild('loc', $base . $url);
        }

        // Insert dynamic (public facilities and equipment) URLs.
        $facilities = Facility::notHidden()->with([
            'equipment' => function($query) {
                $query->notHidden();
            }
        ])->get();
        foreach($facilities as $f) {
            // Insert public facility URL and lastmod.
            // i.e. https://appAddress/facilities/{f-id}.
            $fUrl = $base . '/facilities/' . $f->id;
            $urlElmt = $sm->addChild('url');
            $urlElmt->addChild('loc', $fUrl);
            if ($addFacilityLastMod) {
                $lastMod = $f->dateUpdated->toIso8601String();
                $urlElmt->addChild('lastmod', $lastMod);
            }
            if ($addFacilityChangeFreq) {
                $urlElmt->addChild('changefreq', $addEquipmentChangeFreq);
            }
            if ($addFacilityPriority) {
                $urlElmt->addChild('priority', $addFacilityPriority);
            }

            // Insert public equipment URLs lastmods.
            // i.e. https://appAddress/facilities/{f-id}/equipment/{e-id}.
            foreach($f->equipment as $e) {
                $eUrl = $fUrl . '/equipment/' . $e->id;
                $urlElmt = $sm->addChild('url');
                $urlElmt->addChild('loc', $eUrl);
                if ($addEquipmentLastMod) {
                    $urlElmt->addChild('lastmod', $lastMod);
                }
                if ($addEquipmentChangeFreq) {
                    $urlElmt->addChild('changefreq', $addEquipmentChangeFreq);
                }
                if ($addEquipmentPriority) {
                    $urlElmt->addChild('priority', $addEquipmentPriority);
                }
            }
        }

        // Create sitemap file.
        $tempFilename = $filename . '-' . str_random(15);
        $handle = fopen($tempFilename, 'w');
        fwrite($handle, $sm->asXML());
        fclose($handle);

        // Check if the files are different, if they are, override the existing
        // sitemap, if they are not, discard the sitemap that was just
        // generated.
        if (self::areFilesDiff($filename, $tempFilename)) {
            rename($tempFilename, $filename);
            Log::info('Sitemap generated.');
            self::ping($base, $fileToPing);
        } else {
            unlink($tempFilename);
            Log::info('No change to sitemap.');
        }
    }

    private static function areFilesDiff($f1, $f2) 
    {
        if (file_exists($f1) && file_exists($f2)) {
            // Filesize check.
            $f1Size = filesize($f1);
            $f2Size = filesize($f2);
            if ($f1Size != $f2Size) {
                return true; // Files are not the same.
            }

            // If the filesizes are the same, then go through the the files byte
            // by byte.
            $f1Handle = fopen($f1, 'r');
            $f2Handle = fopen($f2, 'r');
            $areTheyDiff = false;
            while (!feof($f1Handle)) {
                if (fread($f1Handle, $f1Size) != fread($f2Handle, $f2Size)) {                    
                    $areTheyDiff = true; // Don't return from loop because
                                         // we need to close the handles.
                    break;
                }
            }
            fclose($f1Handle);
            fclose($f2Handle);

            return $areTheyDiff;
        }

        // If any of the files do not exist, assume they are different.
        return true;
    }

    private static function ping($base, $sitemap = null)
    {
        if ($sitemap) {
            if (!($services = Setting::lookup('sitemapPingServices', false))) {
                Log::warning('Failed to ping sitemap - no services specified');
            }

            foreach($services as $service) {
                $ch = curl_init($service . $base . $sitemap);
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode >= 200 && $httpCode < 300) {
                    Log::info('Sitemap pinged.', [
                        'service' => $service
                    ]);
                } else {
                    Log::error('Error pinging sitemap service.', [
                        'curlError' => curl_error($ch),
                        'service'   => $service
                    ]);
                }
                curl_close($ch);
            }
        } else {
            Log::warning('Failed to ping sitemap - location not specified');
        }
    }
}
