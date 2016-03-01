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
                    if (array_key_exists('name', $to)) {
                        $message->to($to['email'], $to['name']);
                    } else {
                        foreach($to as $rcpt) {
                            $message->to($rcpt['email'], $rcpt['name']);
                        }                       
                    }
                    
                    if ($cc) {
                        if (array_key_exists('name', $cc)) {
                            $message->cc($cc['email'], $cc['name']);
                        } else {
                            foreach($cc as $rcpt) {
                                $message->to($rcpt['email'], $rcpt['name']);
                            }                       
                        }
                    }
                    
                    if ($bcc) {
                        if (array_key_exists('name', $bcc)) {
                            $message->bcc($bcc['email'], $bcc['name']);
                        } else {
                            foreach($bcc as $rcpt) {
                                $message->to($rcpt['email'], $rcpt['name']);
                            }                       
                        }
                    }
                    
                    $message->subject($subject);
                }
            );            
            
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
