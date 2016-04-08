<?php

namespace App\Listeners;

// Misc.
use Log;
use Mail;

// Models.
use App\Setting;

abstract class BaseListener
{
    protected $_settings;
    
    public function __construct()
    {
        $this->_settings = [
            'APP_NAME'             => Setting::find('APP_NAME')->value,
            'APP_ACRONYM'          => Setting::find('APP_ACRONYM')->value,
            'APP_ADDRESS'          => Setting::find('APP_ADDRESS')->value,
            'EMAIL_ADDRESS'        => Setting::find('EMAIL_ADDRESS')->value,
            'EMAIL_SUBJECT_PREFIX' => Setting::find('EMAIL_SUBJECT_PREFIX')
                                          ->value
        ];
    }
    
    protected function _mail($template,
                             $subject,
                             $data,
                             $to,
                             $cc = null,
                             $bcc = null)
    {
        try {
            Mail::send(['text' => $template], $data, function($message)
                use ($subject, $to, $cc, $bcc) {
                    $recipients = [
                        'to'  => $to,
                        'cc'  => $cc ?: [], // If null, return empty array.
                        'bcc' => $bcc ?: [] // Ditto.
                    ];
                    
                    $validRecipients = [];
                    foreach($recipients as $type => $recipient) {
                        if (!array_key_exists('name', $recipient)) {
                            foreach($recipient as $r) {
                                if ($this->_validateRecipient($r)) {
                                    array_push($validRecipients, $r);
                                }
                            }
                        } else {
                            if ($this->_validateRecipient($recipient)) {
                                array_push($validRecipients, $recipient);
                            }
                        }
                        
                        foreach($validRecipients as $r) {
                            $message->$type($r['email'], $r['name']);
                        }                            
                    }
                    
                    $message->subject($subject);
                }
            );            
            
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
    
    private function _validateRecipient($r)
    {
        return array_key_exists('name', $r)
            && array_key_exists('email', $r)
            && $r['name']
            && $r['email'];     
    }
}
