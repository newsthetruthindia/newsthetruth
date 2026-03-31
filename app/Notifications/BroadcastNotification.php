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

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $url)
    {
        $this->title = $title;
        $this->url = $url;
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
        return (new MailMessage)
            ->subject('📺 New Video Alert: ' . $this->title)
            ->greeting('Hello from News The Truth!')
            ->line('We just published a new video that we think you\'ll find interesting.')
            ->line('**Title:** ' . $this->title)
            ->action('Watch on YouTube', $this->url)
            ->line('Stay informed with the latest updates from NTT.')
            ->salutation('Best Regards, \n The NTT Team');
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
