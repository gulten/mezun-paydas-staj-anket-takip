<?php

namespace App\Http\Middleware;

use Closure;
use App\ApiCode;
use App\Tamamlanan;
use Illuminate\Support\Facades\Auth;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class TamamlananIslem
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $islem_id)
    {
        if(Tamamlanan::where('user_id', Auth::user()->id)->where('islem_id', $islem_id)->first())
        return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('Bu başvuru sizin için aktif değildir')->build();
            return $next($request);

    }
}
