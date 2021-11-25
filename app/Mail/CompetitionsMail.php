<?php

namespace App\Mail;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompetitionsMail extends Mailable
{
    use Queueable, SerializesModels;
    public $competitions;
    public $member;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($competitions,$memberId)
    {
        $this->competitions = $competitions;
        $this->member = Member::find($memberId);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.competitions-mail',["competitions" => $this->competitions, "member" => $this->member]);
    }
}
