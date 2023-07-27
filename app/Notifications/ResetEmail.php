<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
class ResetEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $link = url("/reset-password/" . $this->token);
        return (new MailMessage)
            ->greeting('Merhaba!')
            ->subject('Simatakip | Parola Sıfırlama Maili')
            ->line("Merhaba, Parolanızın Sıfırlanabilmesi İçin Aşağıdaki Linki Tıklayınız.")
            ->action('Parolayo Sıfırla', $link)
            ->line("Bu link için geçerlilik 1 haftadır")
            ->line("Bu eylem bilginiz dahilinde değilse lütfen dikkate almayınız.");
    }

}
