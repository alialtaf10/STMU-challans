<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChallanLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $link;

    public function __construct($student, $link)
    {
        $this->student = $student;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('Fee Challan Link')
                    ->view('emails.challan_link');
    }
}