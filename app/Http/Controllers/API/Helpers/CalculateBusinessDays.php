<?php
namespace App\Http\Controllers\Api\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait CalculateBusinessDays
{
    public static function businessDays(Request $request, $days) //baslangic_tarihi, bitis_tarihi, [min,max days]
    {
        if ($request['cumartesi'])
            Carbon::setWeekendDays([Carbon::SUNDAY]);

        $baslangic = Carbon::parse($request['baslangic_tarihi']);
        $bitis = Carbon::parse($request['bitis_tarihi']);
        $bitis->addDays(1);
        $daysForExtraCoding = $baslangic->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $bitis);

        if (($daysForExtraCoding >= $days[0]) && ($daysForExtraCoding <= $days[1]))
            return ($daysForExtraCoding);
        else
            return false;


    }


}
