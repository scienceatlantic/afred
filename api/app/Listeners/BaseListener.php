<?php

namespace App\Listeners;

use Log;
use Mail;

abstract class BaseListener
{   
    protected function _mail($emails, $event)
    {
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
