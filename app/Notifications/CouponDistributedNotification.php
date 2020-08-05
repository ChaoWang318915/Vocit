<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CouponDistributedNotification extends Notification
{
    use Queueable;
    protected $couponCode;
    protected $postId;
    protected $userName;
    protected $exchangeId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($couponCode, $postId, $userName, $exchangeId)
    {
        $this->couponCode = $couponCode;
        $this->postId = $postId;
        $this->userName = $userName;
        $this->exchangeId = $exchangeId;
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
        return (new MailMessage)
            ->greeting('Hello,')
            ->line('A new coupon has been distributed to ' . $this->userName. ' for exchanging a picture with your request. To view the exchange follow the link below.')
            ->action('View Exchange', url('/post/'. $this->postId.'#'.$this->exchangeId))
            ->line('Coupon Code: '. $this->couponCode)
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
