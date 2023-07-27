<?php

namespace App\Http\Controllers\API\Mezun;

use App\User;
use App\Anket;
use App\AnketSoru;
use App\AnketCevap;
use App\AnketUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Anket\CreateRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Anket as AnketResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\AnketCevap as AnketCevapResource;

class AnketController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user->hasRole('Mezun'))
            return ResponseBuilder::error();

        $anket_user = AnketUser::where('user_id', $user->id)->first();
        if ($anket_user)
            return ResponseBuilder::success($anket_user);

        $anket = Anket::where('id', 1)->with('anketsorulari')->first();

        return ResponseBuilder::success(new AnketResource($anket));
    }

    public function store(CreateRequest $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('Mezun'))
            return ResponseBuilder::error();

        if (AnketUser::where('user_id', $user->id)->count())
            return ResponseBuilder::error();

        $anket = Anket::where('id', 1)->with('anketsorulari')->first();
        $cevap = '';

        DB::beginTransaction();
        try {

            if (count($anket['anketsorulari']) !== count($request['anket_sorular'])) {
                DB::rollback();
                return ResponseBuilder::error();
            }

            foreach ($anket['anketsorulari'] as $key => $item) {

                if ((int)$item['id'] === (int)$request['anket_sorular'][$key]['id'])
                {
                    if (isset($request['anket_sorular'][$key]['digerCevap']))
                        $cevap = $request['anket_sorular'][$key]['digerCevap'];
                    else if (isset($request['anket_sorular'][$key]['cevap']))
                        $cevap = $request['anket_sorular'][$key]['cevap'];
                    else
                        $cevap = '';

                        if (($item['required'])&&($cevap === '')){
                            DB::rollback();
                            return ResponseBuilder::error();
                        }

                    AnketCevap::create(
                        [
                            'user_id' => $user->id,
                            'anket_id' => 1,
                            'soru_id' => $request['anket_sorular'][$key]['id'],
                            'cevap' => $cevap,
                        ]
                    );
                }
                else {
                    DB::rollback();
                    return ResponseBuilder::error();
                }
            }
            $anket_user = AnketUser::create(
                [
                    'user_id' => $user->id,
                    'anket_id' => 1,
                    'complate' => 1,
                ]
            );
            DB::commit();

            return ResponseBuilder::success($anket_user);
        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::error();
        }
    }

    public function answerList()
    {
        $user = Auth::user();
        if (!$user->hasRole('Mezun'))
            return ResponseBuilder::error();

        $anket = AnketCevap::where('user_id', $user->id)->get();

        return ResponseBuilder::success(['anket_cevaplar' => AnketCevapResource::collection($anket)]);
    }

}
