<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\ApiCode;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class CheckRole
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
        $user = Auth::user();
        if ($request->header('Role')&&$user->hasRole($request->header('Role')))
            return $next($request);
        return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('Rol seçiminizde hata var veya hiç yetkiniz yok')->build();
    }
}
