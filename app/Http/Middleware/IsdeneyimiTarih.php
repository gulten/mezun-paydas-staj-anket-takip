<?php

namespace App\Http\Middleware;

use Closure;
use App\Islem;
use App\ApiCode;
use Carbon\Carbon;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class IsdeneyimiTarih
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $islem)
    {
        $date = Islem::where('adi', $islem)->with('islemtarih')->first();
        $now = Carbon::now();

        $start = Carbon::parse($date->islemtarih->baslangic_tarihi);
        //$end = Carbon::parse($date->islemtarih->bitis_tarihi);

        //if ($now->between($start, $end))
        if ($now > $start)
            return $next($request);
        else
            return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('Şu işlem için tarihler aktif değildir : ' . $islem)->build();

    }
}
