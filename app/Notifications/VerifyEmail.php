<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;

class VerifyEmail extends VerifyEmailNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        /* */
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
         $verificationUrl = env('APP_URL', 'http://simatakip.test');

        return (new MailMessage())
            ->greeting(__('Bu Hesap Simatakip Sistem Yöneticisi Tarafından oluşturuldu.'))
            ->subject(__('Simatakip Sistem Kayıt'))
            ->line(
                'Değerli paydaşımız, Simatakip uygulamasının test edilmesi ve geliştirilmesi sürecinde
                    desteğinize ihtiyacımız var. Bu amaçla sisteme giriş yaparak bilgilerinizi
                    güncellemeniz, eksik bilgilerinizi tamamlamanız, anketleri ve formları
                    doldurmanız son derece önemlidir. Böylelikle sizlerin görüşlerini ve önerilerini
                    dikkate alarak simatakip uygulamasını sorunsuz çalışır hale getirmemiz mümkün
                    olabilecektir.')
            ->line('Simatakip uygulamasını geliştirmedeki başlıca amacımız eğitim kalitemizi artırmaktır.
                    Bu uygulama sayesinde öğrencilerimizle, sektördeki paydaşlarımızla ve
                    mezunlarımızla irtibatımızı kesintisiz bir şekilde sürdürmek istiyoruz. Öğrencilerimizi
                    ve mezunlarımızı en güçlü destekçilerimiz ve paydaşlarımız olarak görüyoruz.
                    Bu amaçla, Simatakip uygulamasındaki bilgilerinizi güncel tutmanızı ve eksik/hatalı
                    gördüğünüz işlevlere yönelik bizlere geri bildirimde bulunmanızı rica ederiz. Geri
                    bildirimlerinizi ym@ktu.edu.tr ya da htolgakahraman@ktu.edu.tr hesapları üzerinden
                    gerçekleştirebilirsiniz.')
            ->line('Simatakip uygulaması öncelikle öğrencilerimiz ve mezunlarımız için geliştirilmektedir.
                    Verdiğiniz destekten ötürü teşekkür ederiz.')
                     ->action(__('Siteyi Ziyaret Edin'), $verificationUrl);
    }
}
