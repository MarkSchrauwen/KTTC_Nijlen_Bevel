<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateUserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $concernedUser, $connectedMember, $role)
    {
        $this->user = $user;
        $this->concernedUser = $concernedUser;
        $this->connectedMember = $connectedMember;
        $this->role = $role;
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
            "concernedUser" => $this->concernedUser->firstname . " " . $this->concernedUser->lastname,
            "connectedMember" => $this->connectedMember 
                ? $this->connectedMember->name
                : null,
            "role" => $this->role,
        ];
    }
}
