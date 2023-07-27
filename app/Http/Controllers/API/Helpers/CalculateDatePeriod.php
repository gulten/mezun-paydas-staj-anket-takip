<?php

namespace App\Http\Controllers\Api\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait CalculateDatePeriod
{
    public static function datePeriod($day)
    {
        $day = Carbon::parse($day);
        $now = Carbon::now();
        return $now->diff($day)->days >= config('global.isyeri.basvuru');
    }
}
