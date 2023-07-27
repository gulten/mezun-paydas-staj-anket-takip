<?php

namespace App;

use App\Notifications\DraftEmail;
use Illuminate\Notifications\Notifiable;

class MailSendStrategy implements SendStrategy
{
    use Notifiable;
    public function send($request) {
        //Mail Gönder fonksiyonu
        $this->notify(new DraftEmail($request));
    }
}
