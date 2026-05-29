<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyNewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $posts;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $posts)
    {
        $this->user = $user;
        $this->posts = $posts;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('The Evening Truth: Today\'s Top 5 Stories')
                    ->view('emails.newsletter');
    }
}
