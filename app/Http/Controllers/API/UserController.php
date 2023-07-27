<?php

namespace App\Http\Controllers\API;

use App\User;
use App\ApiCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\Login as LoginResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->only('logout');
    }

    public function login(LoginRequest $request)
    {

        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
            'status' => 'active',
        ];

        $user = User::where('email', $request['email'])
            ->first();

        if ($user === null) {
            return ResponseBuilder::asError(ApiCode::AuthenticationFailed)->withMessage(__('Bu Mail Adresi Sistemde Kayıtlı Değil, Lütfen Sistem Yönetici İle İletişim Kurunuz.'))->build();
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return ResponseBuilder::success(new LoginResource($user));
        } else {
            return ResponseBuilder::asError(ApiCode::AuthenticationFailed)->withMessage(__('Lütfen Bilgilerinizi Kontrol Ediniz'))->build();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return ResponseBuilder::asSuccess()->withMessage(__('Sistemden Çıkış Yaptınız.'))->build();
    }

}
