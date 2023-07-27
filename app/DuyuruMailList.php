<?php

namespace App;

use App\Notifications\DraftEmail;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

class DuyuruMailList extends Model
{
    use Notifiable;
    protected $table = "duyuru_mail_list"; //pivot table

    public function sendEmail($request)
    {
        $this->notify(new DraftEmail($request));
    }

}
