<?php

namespace App;

class NotifySendStrategy implements SendStrategy
{
    public function send($request) {
        //Bildirim Gönder Fonksiyonu
        echo('bildirim gönderildi...');
        //kodlar.....
    }
}
