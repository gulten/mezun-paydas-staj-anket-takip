<?php

namespace App\Http\Controllers\API\Anket;

use App\Anket;
use App\AnketUser;
use App\AnketCevap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\AnketUser as AnketUserResource;
use App\Http\Resources\AnketCevap as AnketCevapResource;

class RaporController extends Controller
{
    public function List()
    {
        $anketler = Anket::all();
        return ResponseBuilder::success(['anketler' => $anketler]);
    }

    public function AnketUser($id)
    {
        $list = AnketUser::where('anket_id', $id)->with('user')->get();
        return ResponseBuilder::success(['anketler' => $list]);
    }

    public function AnketCevap($id)
    {
        $anket = AnketUser::where('id',$id)->with(['user', 'anket'])->first();
        $cevaplar = AnketCevap::where('anket_id', $anket->anket_id)->where('user_id', $anket->user_id)->with('anketsoru')->get();

        return ResponseBuilder::success([
            'anket' => new AnketUserResource($anket),
            'cevaplar' => AnketCevapResource::collection($cevaplar)
        ]);
    }
    public function ViewChart($id)
    {
        $aranan = AnketCevap::where('id',$id)->with('anketsoru')->first();

        $cevaplar = DB::table('anket_cevaplari')
            ->select(DB::raw('count(*) as user_count, cevap, id'))
            ->where('anket_id', $aranan->anket_id)
            ->where('soru_id', $aranan->soru_id)
            ->groupBy('cevap')
            ->get();

        return ResponseBuilder::success([
            'soru' => $aranan->anketsoru->soru,
            'chart' => $cevaplar
        ]);
    }
    public function ViewTable($id)
    {
        $aranan = AnketCevap::where('id', $id)
                            ->first();

        $cevaplar = AnketCevap::where('anket_id', $aranan->anket_id)
            ->where('soru_id', $aranan->soru_id)
            ->with('anketsoru')
            ->with('user')
            ->get();

        return ResponseBuilder::success([
            'table' => $cevaplar
        ]);
    }
}
