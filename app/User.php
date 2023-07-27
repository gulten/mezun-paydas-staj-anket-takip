<?php

namespace App;

use App\Traits\RoleControl;
use App\Notifications\DraftEmail;
use App\Notifications\ResetEmail;
use App\Notifications\SendPassword;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles, RoleControl, HasApiTokens, SoftDeletes;
    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'name', 'email', 'password','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordNotification($request)
    {
        $this->notify(new SendPassword($request));
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetEmail($token));
    }
    public function sendEmailSecretNotification($request)
    {
        $this->notify(new DraftEmail($request));
    }

    public function bolumbaskani(){
        return $this->hasOne(BolumBaskani::class);
    }
    public function bolumbaskanyardimcisi(){
        return $this->hasOne(BolumBaskaniYardimcisi::class);
    }
    public function mezun(){
        return $this->hasOne(Mezun::class);
    }
    public function ogrenci(){
        return $this->hasOne(Ogrenci::class);
    }
    public function paydas(){
        return $this->hasOne(Paydas::class);
    }
    public function firmayetkilisi()
    {
        return $this->hasOne(FirmaYetkilisi::class)
        ->with('firma');
    }
    public function ogretimelemani(){
        return $this->belongsTo(OgretimElemani::class);
    }

    public function isdeneyimi(){
        return $this->hasMany(IsDeneyimi::class);
    }

    public function anket()
    {
        return $this->hasOne(AnketUser::class);
    }

    public function bolumeoneri()
    {
        return $this->hasMany(BolumeOneriler::class)
        ->with('bolumders');
    }

    public function dersgereklilik()
    {
        return $this->hasMany(DersGereklilik::class)
            ->with('bolumders');
    }

    public function derskalite()
    {
        return $this->hasMany(DersKalite::class)
            ->with('bolumders');
    }

    public function yenidersoneri()
    {
        return $this->hasMany(YeniDersOneri::class);
    }

    public function populerteknoloji()
    {
        return $this->hasMany(PopulerTeknoloji::class);
    }

    public function egitimkalite()
    {
        return $this->hasMany(EgitimKalitesi::class);
    }

}
