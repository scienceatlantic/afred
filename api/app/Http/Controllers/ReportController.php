<?php

namespace App\Http\Controllers;

// Events.
use App\Events\ReportEvent;

// Misc.
use Auth;
use DB;
use Excel;

// Models.
use App\Equipment;
use App\Facility;
use App\Setting;

// Requests.
use Illuminate\Http\Request;
use App\Http\Requests\ReportRequest;

class ReportController extends Controller
{
    public function index(ReportRequest $request)
    {
        // Cleanup. If there are no pending jobs, then it is safe to remove old
        // exports.
        if (!DB::table('jobs')->count()) {
            // Delete files.
            foreach(glob(config('excel.export.store.path') . '/*/*') as $file) {
                unlink($file);
            }

            // Delete directories.
            foreach(glob(config('excel.export.store.path') . '/*') as $dir) {
                rmdir($dir);
            }
        }

        $filename = Setting::lookup('appShortName') . ' Report ('
                  . $this->now(false)->format('M j, o - h_i_s A') . ')';
        $subdir = strtolower(str_random(5));
        $path = config('excel.export.store.path') . '/' . $subdir;

        $report = Excel::create($filename, function($excel) use ($filename) {
            $excel->setCreator(Auth::user()->getFullName());
            $excel->setCompany(Setting::lookup('organizationName'));
            $excel->setTitle(str_replace('_', ':', $filename));
            $excel->setLastModifiedBy(Auth::user()->getFullName());

            $excel->sheet('Facilities', function(\PHPExcel_Worksheet $sheet) {
                $data = [[
                    'Organization',
                    'Facility',
                    'City',
                    'Province',
                    'Description',
                    'Primary Contact First Name',
                    'Primary Contact Last Name',
                    'Primary Contact Email',
                    'Primary Contact Telephone',
                    'Primary Contact Position',
                    '# Equipment',
                    '# Public Equipment',
                    '# Hidden Equipment',
                    '# Equipment With Excess Capacity',
                    '# Equipment Without Excess Capacity',
                    'Location',
                ]];

                $facilities = Facility::with('organization', 'primaryContact');
                $url = Setting::lookup('appAddress') . '/facilities/';

                foreach($facilities->get() as $f) {
                    array_push($data, [
                        $f->organization->name,
                        $f->name,
                        $f->city,
                        $f->province->name,
                        $f->descriptionNoHtml,
                        $f->primaryContact->firstName,
                        $f->primaryContact->lastName,
                        $f->primaryContact->email,
                        $f->primaryContact->telephone,
                        $f->primaryContact->position,
                        $f->equipment()->count() ?: 0,
                        $f->equipment()->notHidden()->count() ?: 0,
                        $f->equipment()->hidden()->count() ?: 0,
                        $f->equipment()->excessCapacity(true)->count() ?: 0,
                        $f->equipment()->excessCapacity(false)->count() ?: 0,
                        $url . $f->id
                    ]);
                }

                $sheet->fromArray($data, '', 'A1', false, false);
            });

            $excel->sheet('Equipment', function(\PHPExcel_Worksheet $sheet) {
                $data = [[
                    'Organization',
                    'Facility',
                    'Type',
                    'Manufacturer',
                    'Model',
                    'Purpose',
                    'Specifications',
                    'Searchable',
                    'Has Excess Capacity',
                    'Year Purchased',
                    'Year Manufactured',
                    'Keywords',
                    'Location',
                ]];

                $equipment = Equipment::with('facility.organization');
                $url = Setting::lookup('appAddress') . '/facilities/';

                foreach($equipment->get() as $e) {
                    if (!$e->facility->isPublic && !$e->isPublic) {
                        $e->isPublic = 'No and facility is hidden';
                    } else if (!$e->facility->isPublic && $e->isPublic) {
                        $e->isPublic = 'No because facility is hidden';
                    } else if ($e->isPublic) {
                        $e->isPublic = 'Yes';
                    } else {
                        $e->isPublic = 'No';
                    }

                    array_push($data, [
                        $e->facility->organization->name,
                        $e->facility->name,
                        $e->type,
                        $e->manufacturer,
                        $e->model,
                        $e->purposeNoHtml,
                        $e->specificationsNoHtml,
                        $e->hasExcessCapacity ? 'Yes' : 'No',
                        $e->isPublic,
                        $e->yearPurchased,
                        $e->yearManufactured,
                        $e->keywords,
                        $url . $e->facility->id . '/equipment/' . $e->id
                    ]);
                }

                $sheet->fromArray($data, '', 'A1', false, false);                
            });
        })->store('xlsx', $path);

        // Replace title property.
        $report['title'] = str_replace('_', ':', $filename);

        // Generate event.
        event(new ReportEvent(Auth::user(), $report));

        // Return subdirectory and filename.
        return ['filename' => $subdir . '/' . $report['file']];
    }
}
