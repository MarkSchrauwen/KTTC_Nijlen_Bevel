<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateMemberNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $member, $connectedUser)
    {
        $this->user = $user;
        $this->member = $member;
        $this->connectedUser = $connectedUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "admin" => $this->user->firstname . " " . $this->user->lastname,
            "member" => $this->member->name,
            'connectedUser' => $this->connectedUser 
                ? $this->connectedUser->firstname . " " . $this->connectedUser->lastname
                : null,
        ];
    }
}
