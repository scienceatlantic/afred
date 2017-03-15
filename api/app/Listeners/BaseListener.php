<?php

namespace App\Listeners;

// Misc.
use Log;
use Mail;

// Models.
use App\Setting;

abstract class BaseListener
{
    protected $settings;
    
    public function __construct()
    {
        $this->settings = Setting::lookup([
            'appName',
            'appShortName',
            'appAddress',
            'emailSubjectPrefix',
            'generalContactEmail',
            'generalContactTelephone',
            'personalContactName',
            'personalContactTitle',
            'personalContactEmail',
            'twitterHandle'
        ], null, true);
    }
    
    protected function mail($template,
                            $subject,
                            $data,
                            $to,
                            $cc = null,
                            $bcc = null,
                            $replyTo = null,
                            $attachments = null)
    {
        try {
            Mail::queue(['text' => $template], $data, function($message)
                use ($subject, $to, $cc, $bcc, $replyTo, $attachments) {
                    $recipients = [
                        'to'  => $to,
                        'cc'  => $cc ?: [], // If null, return empty array.
                        'bcc' => $bcc ?: [] // Ditto.
                    ];
                    
                    $replyTo = $replyTo ?: [];
                    $attachments = $attachments ?: [];
                    
                    foreach($recipients as $type => $recipient) {
                        // Will hold all the recipients after their details
                        // have been validated.
                        $validRecipients = [];
                        
                        // Check if it's an array of recipients or just a
                        // single recipient. This part is for arrays.
                        if (!array_key_exists('name', $recipient)) {
                            foreach($recipient as $r) {
                                if ($this->validateRecipient($r)) {
                                    array_push($validRecipients, $r);
                                }
                            }
                        // And this part is for a single recipient.
                        } else {
                            if ($this->validateRecipient($recipient)) {
                                array_push($validRecipients, $recipient);
                            }
                        }
                        
                        // Attach recipients to email message.
                        foreach($validRecipients as $r) {
                            $message->$type($r['email'], $r['name']);
                        }                            
                    }
                    
                    $message->subject($subject);
                    if ($this->validateRecipient($replyTo)) {
                        $message->replyTo($replyTo['email'], $replyTo['name']);   
                    }

                    foreach($attachments as $attachment) {
                        $message->attach($attachment);
                    }                    
                }
            );            
            
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
    
    private function validateRecipient($r)
    {
        return array_key_exists('name', $r)
            && array_key_exists('email', $r)
            && $r['name']
            && $r['email'];     
    }
}
