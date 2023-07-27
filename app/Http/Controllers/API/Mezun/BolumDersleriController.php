<?php

namespace App\Http\Controllers\API\Mezun;

use App\BolumDersleri;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\BolumDersleri as BolumDersleriResource;

class BolumDersleriController extends Controller
{
    public function index()
    {
        $dersler = BolumDersleri::all();
        return ResponseBuilder::success(['dersler' => BolumDersleriResource::collection($dersler)]);
    }

    public function getDerslerWithoutOneri()
    {
        $user = Auth::user();
            if (!$user->hasAnyRole('Mezun', 'Paydas'))
                return ResponseBuilder::error();

        $dersler = BolumDersleri::with(['bolumeoneri' => function ($query) use ($user) {
                                        $query->where('user_id', $user->id);
                                    }])
                                ->with(['dersgereklilik' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                }])
                                ->with(['derskalite' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                }])
                                ->get();


        return ResponseBuilder::success(['dersler' => $dersler]);
    }
}
