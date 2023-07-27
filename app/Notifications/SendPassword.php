<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPassword  extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
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
        $verificationUrl = env('APP_URL', 'https://simatakip.com');
        $urlPDF = url('https://www.ktu.edu.tr/dosyalar/ofyazilim_8def6.pdf');

        return (new MailMessage())
            ->greeting(__('Değerli paydaşımız ' . $notifiable->name . ', KTÜ Yazılım Mühendisliği bölümüne ait Simatakip uygulamasına kaydınız yapılmıştır.'))
            ->subject(__('Simatakip Sistem Kayıt'))
            ->line(
            'Simatakip uygulaması bölümümüzden mezun öğrencilerimizi bir
            arada tutmak ve öğrencilerimiz ile üniversite ilişkilerini sürdürmek
            amacıyla tasarlanmış, Karadeniz Teknik Üniversitesi Yazılım Mühendisliği
            bölümüne ait bir yazılımdır. Bu uygulama sayesinde öğrencilerimizle,
            sektördeki paydaşlarımızla ve mezunlarımızla irtibatımızı kesintisiz bir
            şekilde sürdürmekle beraber eğitim kalitemizi arttırmayı hedefliyoruz.'
            )
            ->line('Uygulamanın test edilmesi ve geliştirilmesi sürecinde desteğinize
            ihtiyacımız var. Sisteme giriş yaparak bilgilerinizi güncellemeniz,
            eksik bilgilerinizi tamamlamanız, anketleri ve formları doldurmanız
            son derece önemlidir. Böylelikle sizlerin görüşlerini ve önerilerini
            dikkate alarak simatakip uygulamasını sorunsuz çalışır hale getirmemiz mümkün olabilecektir. ')
            ->line('Sisteme giriş bilgileriniz: ')
            ->line('e-mail: ' . $notifiable->email)
            ->line('Şifre: ' . $this->request->password)
            ->action(__('Siteyi Ziyaret Edin'), $verificationUrl);
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
