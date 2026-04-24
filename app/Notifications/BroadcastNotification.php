<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BroadcastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;
    public $url;
    public $excerpt;
    public $imageUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $url, $excerpt = null, $imageUrl = null)
    {
        $this->title = $title;
        $this->url = $url;
        $this->excerpt = $excerpt;
        $this->imageUrl = $imageUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('📺 New Story Alert: ' . $this->title)
            ->greeting('Hello from News The Truth!')
            ->line('We just published a new story that we think you\'ll find interesting.')
            ->line('**Title:** ' . $this->title);

        if ($this->excerpt) {
            $mail->line('**Summary:** ' . $this->excerpt);
        }

        $mail->action('Read Full Story', $this->url)
            ->line('Stay informed with the latest updates from NTT.')
            ->salutation('Best Regards, \n The NTT Team');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
        ];
    }
}
