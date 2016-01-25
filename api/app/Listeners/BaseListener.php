<?php

namespace App\Listeners;

use Log;
use Mail;
use App\Setting;

abstract class BaseListener
{    
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
