<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Komisyon extends Model
{
    public $timestamps = false;
    protected $table = "komisyonlar";


}
