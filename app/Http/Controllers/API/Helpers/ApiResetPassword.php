<?php

namespace App\Http\Controllers\Api\Helpers;

use App\ApiCode;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

trait ApiResetPassword
{
    use ResetsPasswords;

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));
    }

    protected function sendResetResponse(Request $request, $response)
    {
        $rb = ResponseBuilder::asSuccess();

        return $rb->withMessage(__($response))->build();
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        if ($response === PasswordBroker::INVALID_USER) {
            return ResponseBuilder::asError(ApiCode::InvalidUser)->withMessage(__($response))->build();
        }

        if ($response === PasswordBroker::INVALID_TOKEN) {
            return ResponseBuilder::asError(ApiCode::TokenNotValid)->withMessage(__($response))->build();
        }
    }
}
