<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tamamlanan extends Model
{
    protected $table = "tamamlananlar";
    protected $appends = ['statu'];
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'islem_id'
    ];

    public function getStatuAttribute(){
        return $this->user_id?'tamamlandı':'tamamlanmadı';
    }

    public function basvuru()
    {
        return $this->belongsTo(Basvuru::class, 'user_id', 'user_id');
    }
}
