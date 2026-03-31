<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        // Generate signed verification URL
        // $verificationUrl = URL::temporarySignedRoute(
        //     'customers.verify', 
        //     Carbon::now()->addMinutes(5),
        //     ['id' => $notifiable->id]
        // );
        //  $frontendUrl = url("/customers/verify/?url=" . urlencode($verificationUrl));

        // return (new MailMessage)
        //     ->subject('Verify Your Email')
        //     ->view('mail.custom_verify', ['url' => $frontendUrl]);

        $verificationUrl = URL::temporarySignedRoute(
            'customers.verify',
            Carbon::now()->addMinutes(5), 
            ['id' => $notifiable->id]
        );

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->view('mail.custom_verify', ['url' => $verificationUrl]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}