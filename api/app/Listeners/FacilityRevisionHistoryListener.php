<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;
use App\SystemUser;
use App\Events\FacilityRevisionHistoryEvent;
use Mail;

class FacilityRevisionHistoryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FacilityRevisionHistoryEvent  $event
     * @return void
     */
    public function handle(FacilityRevisionHistoryEvent $event)
    {
        $state = $event->frh->state;
        $subjectPrefix = 'AFRED 2.0 TEST - ';
        $templatePrefix = 'emails.events.frh.';
        $emails = [];
        
        /*$emails = [
            'administrators' => [
                'to' => [
                    [
                        'name' => '',
                        'email' => '',                        
                    ],
                ],
                
                'subject' => '',
                'template' => ''                
            ],
            
            /*'primaryContact' => [

            ],
            
            'ilos'           => [
                
            ],
        ];*/
        
        switch ($state) {
            case 'PENDING_APPROVAL':
                $emails['administrators']['to'] = [];
                $administrators = SystemUser::all();
                
                foreach($administrators as $a) {                    
                    array_push($emails['administrators']['to'], [
                        'name'  => $a->firstName . ' ' . $a->lastName,
                        'email' => $a->username
                    ]);
                    
                    $emails['administrators']['subject'] =
                        "{$subjectPrefix}New Record Submitted";
                    $emails['administrators']['template'] =
                        "{$templatePrefix}submitted";
                }
                
                break;
            
            case 'sd':
                break;
        }
                
        try {
            Log::debug($emails);
            
            foreach($emails as $user => $params) {
                Mail::send(
                    ['text' => $params['template']],
                    
                    [$event->frh->data],
                    
                    function ($message) use ($params) {
                        foreach($params['to'] as $to) {
                            $message->to($to['email'], $to['name']);                       
                        }
                        
                        if (array_key_exists('cc', $params)) {
                            foreach($params['cc'] as $bcc) {
                                $message->cc($cc['email'], $cc['name']); 
                            }                            
                        }
                        
                        if (array_key_exists('bcc', $params)) {
                            foreach($params['bcc'] as $bcc) {
                                $message->bcc($bcc['email'], $bcc['name']); 
                            }                            
                        }
                        
                        $message->subject($params['subject']);
                    }
                );                
            }            
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
