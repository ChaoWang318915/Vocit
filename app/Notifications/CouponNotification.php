<?php

namespace App\Notifications;

use CodeItNow\BarcodeBundle\Utils\QrCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CouponNotification extends Notification
{
    use Queueable;
    protected $userName;
    protected $business;
    protected $coupon;
    protected $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userName, $business, $coupon, $post)
    {
        $this->userName = $userName;
        $this->business = $business;
        $this->coupon = $coupon;
        $this->post = $post;
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
        $userName = $this->userName;
        $businessName = $this->business->name;
        $businessLogo = $this->business->logo;
        $couponCode = $this->coupon;
        $rules = $this->post->content;
        $text = $this->post->short_description;

        return (new MailMessage)
            ->greeting('Hello '. $userName.',')
            ->line('Thanks for sharing your pictures with <strong>'. ucwords($businessName). '</strong>. You have received a coupon as reward.')
            ->line('<div class="coupon-container"><p><img class="business-logo" src="'.$businessLogo.'"></p> <h1>'.$text.'</h1> <p><img src="'.$this->coupon->qr_code.'"></p></div>')
            ->line($rules)
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
