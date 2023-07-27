<?php

namespace App\Observers;

use App\User;
use App\Duyuru;
use App\ContextSend;
use App\DuyuruMailList;
use App\MailSendStrategy;
use App\NotifySendStrategy;

class DuyuruObserver
{
    /**
     * Handle the duyuru "created" event.
     *
     * @param  \App\Duyuru  $duyuru
     * @return void
     */
    /*protected $send = [
        'mail' => MailSendStrategy::class,
        'bildirim' => NotifySendStrategy::class,
    ];*/

    public function created(Duyuru $duyuru)
    {
        //Tüm kullanıcılara mail gideceği kısım
        //$this->sendMailable($duyuru, DuyuruMailList::all());
        $this->send($duyuru, DuyuruMailList::all());
    }

    /**
     * Handle the duyuru "updated" event.
     *
     * @param  \App\Duyuru  $duyuru
     * @return void
     */
    public function updated(Duyuru $duyuru)
    {
        //
    }

    /**
     * Handle the duyuru "deleted" event.
     *
     * @param  \App\Duyuru  $duyuru
     * @return void
     */
    public function deleted(Duyuru $duyuru)
    {
        //
    }

    /**
     * Handle the duyuru "restored" event.
     *
     * @param  \App\Duyuru  $duyuru
     * @return void
     */
    public function restored(Duyuru $duyuru)
    {
        //
    }

    /**
     * Handle the duyuru "force deleted" event.
     *
     * @param  \App\Duyuru  $duyuru
     * @return void
     */
    public function forceDeleted(Duyuru $duyuru)
    {
        //
    }

    public function sendMailable(Duyuru $duyuru, $list): void
    {
        echo "Subject: Notifying observers...\n";
        foreach ($list as $item) {
            $item->sendEmail($this);
        }
    }

    public function send(Duyuru $duyuru, $list): void
    {
        echo "Subject: Notifying observers...\n";
        foreach ($list as $item) {
            $context = new ContextSend(new $item->tip);
            echo "Client: Strategy is set to normal sorting.\n";
            $context->doSomeBusinessLogic($item);
        }
    }
}
