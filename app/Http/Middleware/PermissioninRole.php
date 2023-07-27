<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissioninRole
{
    public function handle($request, Closure $next, $permission)
    {

        if (app('auth')->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        $get_permissions = auth()->user()->getAllPermissions()->toArray();


        foreach ($permissions as $permission) {
            foreach ($get_permissions as $get) {
                if ($permission=='bölüm bilgileri düzenle') {
                    return $next($request);
                }
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
