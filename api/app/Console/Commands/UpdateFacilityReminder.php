<?php

namespace App\Console\Commands;

// Events.
use App\Events\UpdateFacilityReminderEvent;

// Laravel.
use Illuminate\Console\Command;

// Misc.
use Carbon\Carbon;
use Log;

// Models.
use App\Facility;
use App\Setting;

class UpdateFacilityReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:update-facility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Emails primary contacts a reminder informing them
                              that it has been at least <INTERVAL> months since
                              they last updated their facility.';

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
        Log::info('Console: `reminder:update-facility` called.');

        // Get today's date (without time).
        $now = Carbon::parse(Carbon::now()->toDateString());
        
        // Get settings.
        $s = Setting::lookup([
            'updateFacilityReminderDateLastRun'      => 'dateLastRun',
            'updateFacilityReminderIntervalInMonths' => 'interval'
        ]);

        // Set default date.
        $s['dateLastRun'] = $s['dateLastRun'] ?: Carbon::parse('1990-01-01');

        // Make sure command is not run more than once a day.
        if ($s['dateLastRun']->gte($now)) {
            Log::warning('Not allowed to run command more than once a day. '
                . 'Aborting!');
            abort(500);
        }

        // Make copy before modifying Carbon object.
        $nowString = $now->toDateString();

        // Get outdated facilities.
        $facilities = Facility::with('currentRevision', 'primaryContact')
            ->whereBetween('dateUpdated', [
                $s['dateLastRun']->subMonths($s['interval'])->addDay(),
                $now->subMonths($s['interval']),
            ])->get();

        // Generate events (that send out emails).
        foreach($facilities as $f) {
            if (!$f->currentRevision->updateRequests()->notClosed()->count()) {
                event(new UpdateFacilityReminderEvent($f, $s['interval']));
            } else {
                Log::info('Facility is being updated. Skipping!', [
                    'id'   => $f->id,
                    'name' => $f->name
                ]);
            }
        };

        // Update date last run.
        Setting::findByName('updateFacilityReminderDateLastRun')
            ->updateValue($nowString);
    }
}
