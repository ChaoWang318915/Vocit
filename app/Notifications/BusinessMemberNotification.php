<?php

namespace App\Notifications;

use App\Models\Business;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BusinessMemberNotification extends Notification
{
    protected $businessId;
    protected $userId;
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userId, $businessId)
    {
        $this->userId = $userId;
        $this->businessId = $businessId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = User::find($this->userId);
        $business = Business::find($this->businessId);
        return (new MailMessage)
                    ->greeting('Hello '. $user->first_name .',')
            ->line('You\'ve been invited by the admin of the '.$business->name.' to join as manager. Please follow the link below to join.')
                    ->action('Join', url('/business/invitation?email='. $user->email. '&bid='. $business->id))
                    ->line('Thank you!');
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
            //
        ];
    }
}
