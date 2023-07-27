<?php

namespace App\Http\Controllers\API\Bbys;

use App\User;
use Illuminate\Http\Request;
use App\Notifications\DraftEmail;
use App\Notifications\ResetEmail;
use App\Notifications\VerifyEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\User\ResetRequest;
use App\Http\Requests\User\ForgotRequest;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;


class MailController extends Controller
{

    public function send_mail($id)
    {
        $user = User::find($id);

        $user->sendEmailVerificationNotification();

        return ResponseBuilder::success();
    }

    public function secret_mail(Request $request, $id)
    {
        $user = User::find($id);

        $user->sendEmailSecretNotification($request);

        return ResponseBuilder::success();
    }

    public function forgot(ForgotRequest $request)
    {
        return ResponseBuilder::success();
    }

    public function reset(ResetRequest $request)
    {
        return ResponseBuilder::success();
    }

    public function sendOldMail($id)
    {
        $user = User::find($id);
        Mail::send("email.test", ["name" => "Deneme"], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject("Simatakip Sistemine Hoşgeldiniz");
        });
        return ResponseBuilder::success();
    }
}
