<?php

namespace App\Http\Middleware;

use Closure;
use App\IsyeriEgitimiBasvuru;
use Illuminate\Support\Facades\Auth;
use App\ApiCode;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class IslemAnket
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (IsyeriEgitimiBasvuru::active()->bitis()->where('user_id', Auth::user()->id)->first())
            return $next($request);

        return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('Bu başvuru sizin için aktif değildir')->build();

    }
}
