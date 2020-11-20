<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\FacebookPoster\FacebookPosterChannel;
use NotificationChannels\FacebookPoster\FacebookPosterPost;
use App\Models\Post;
use Intervention\Image\ImageManagerStatic as IntImage;
use Intervention\Image\Font;

class FacebookAutoPost extends Notification
{
    use Queueable;
    //protected $post;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FacebookPosterChannel::class];
    }


    /**
     * @param $post
     */
    public function toFacebookPoster($post)
    {

         //facebook autopost making image step
         //$post = Post::findorFail($postId);          
         //step 1 - getting top bg 
        //  $top_bg = IntImage::make('storage/top-bg.jpg');   
        //  $top_mask = IntImage::make('storage/mask.png');    
        //  //step 2 - get the width of the parent post image and resize the top image
        //  $post_image = IntImage::make($post->attachments[0]->lg_url);
        //  $width = $post_image->width();
        //  $top_bg->resize($width,156);                
        //  //step 3 - insert logo ,business name
        //  $logo_image = IntImage::make($post->business->logo)->resize(150,150)->mask($top_mask);
        //  $business_name = $post->business->name;
        //  $top_bg->insert($logo_image,'left',30,3);        
        //  $fonts = ['anydore', 'gladifilthefte', 'momentus', 'roboto-regular'];
        //  $top_bg->text($business_name, 220, 90, function ($font) use ($fonts) {     
        //     $index = rand(0, 3);
        //     $font->file(public_path("fonts/{$fonts[0]}.ttf"));
        //     $font->size(40);
        // });           
        // $merge_image = IntImage::canvas($width,$post_image->height()+156);
        // $merge_image->insert($top_bg,'top',0,0);
        // $merge_image->insert($post_image,'top',0,156);
        // $merge_image->save('storage/post_image.jpg');
        // return (new FacebookPosterPost('Laravel notifications are awesome!'))
        // ->withImage(url('storage/post_image.jpg'));      
        return (new FacebookPosterPost('Laravel notifications are awesome!'));
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
