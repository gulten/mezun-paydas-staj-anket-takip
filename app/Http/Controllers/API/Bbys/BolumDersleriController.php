<?php

namespace App\Http\Controllers\API\Bbys;

use App\BolumDersleri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BolumDersleri\StoreRequest;
use App\Http\Resources\BolumDersleri as BolumDersleriResource;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class BolumDersleriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $ders_bilgileri = BolumDersleri::all();
        return ResponseBuilder::success(['bolum_dersleri' => BolumDersleriResource::collection($ders_bilgileri)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $ders = new BolumDersleri();
            $ders->sinif = $request['sinif'];
            $ders->donem = $request['donem'];
            $ders->durum = $request['durum'];
            $ders->ders_kodu = $request['ders_kodu'];
            $ders->ders_adi = $request['ders_adi'];
            $ders->haftalik_ders_saati = $request['haftalik_ders_saati'];
            $ders->amac = $request['amac'];
            $ders->icerik = $request['icerik'];
            $ders->save();

            DB::commit();

            return ResponseBuilder::success($ders);

        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(StoreRequest $request, $id)
    {
        $ders = BolumDersleri::find($id);

        DB::beginTransaction();
        try {

            $ders->sinif = $request['sinif'];
            $ders->donem = $request['donem'];
            $ders->durum = $request['durum'];
            $ders->ders_kodu = $request['ders_kodu'];
            $ders->ders_adi = $request['ders_adi'];
            $ders->haftalik_ders_saati = $request['haftalik_ders_saati'];
            $ders->amac = $request['amac'];
            $ders->icerik = $request['icerik'];
            $ders->save();

            DB::commit();

            return ResponseBuilder::success();

        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::error();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ders = BolumDersleri::find($id);
        $ders->delete();
        return ResponseBuilder::success();
    }
}
