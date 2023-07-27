<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\IsyeriEgitimiBasvuru;
use App\Tamamlanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\IsyeriEgitimi\DegerlendirmeRequest;
use App\Http\Resources\Basvuru as BasvuruResources;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class DegerlendirmeYonetimController extends Controller
{
    public function __construct()
    {
        $this->middleware(['check_isdeneyimi_tarih:İşyeri Eğitimi Mülakat']);
        $this->toplam_gun = config('global.isyeri.toplam');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $basvurular = IsyeriEgitimiBasvuru::active()->mulakat()->with('user')->with('firma')->with('tamamlanan')->get();
        return ResponseBuilder::success(['basvurular' => $basvurular]);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(DegerlendirmeRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $basvuru = IsyeriEgitimiBasvuru::active()->mulakat()->anket()->with('user')->with('firma')->with('tamamlanan')->find($id);

            if ($basvuru->staj_gun_sayisi<$request['kabul_edilen_gun_sayisi'])
                $request['kabul_edilen_gun_sayisi'] = $basvuru->staj_gun_sayisi;


            if ( ($request['sicil_fisi_notu']>=60) && ($request['dosya_notu'] >= 60) && ($request['mulakat_notu'] >= 60) && ($request['kabul_edilen_gun_sayisi']>=15) )
                $statu = true;
            else
                $statu = false;

                $basvuru->update([
                    'islem_id' => 4,
                    'mulakat' => 'tamamlandı',
                    'sicil_fisi_notu' => $request['sicil_fisi_notu'],
                    'dosya_notu' => $request['dosya_notu'],
                    'mulakat_notu' => $request['mulakat_notu'],
                    'kabul_edilen_gun_sayisi' => $request['kabul_edilen_gun_sayisi'],
                    'red_edilen_gun_sayisi' => $basvuru->staj_gun_sayisi - $request['kabul_edilen_gun_sayisi'],
                    'statu' => $statu
                ]);
                $basvuru->refresh();

                //Değerlendirme olumlu ise öğrencinin tamamlanan günlerini hesapla
                $kabul_edilen_gun_sayisi = IsyeriEgitimiBasvuru::where('user_id', $basvuru->user_id)->where('islem_id', 4)->where('statu', 1)->sum('kabul_edilen_gun_sayisi');
                if(($statu)&&($kabul_edilen_gun_sayisi>=$this->toplam_gun)&&(!Tamamlanan::where('user_id', $basvuru->user_id)->where('islem_id', 4)->first())) {
                    //Belirlenen gün sayısı tamamlandıysa Tamamlanan İşlemler Tablosuna Kayıt Et
                    Tamamlanan::create([
                        'user_id' => $basvuru->user_id,
                        'islem_id' => 4
                    ]);
                }
                else {
                    //Belirlenen gün sayısı eksik ise Tamamlanan İşlemler Tablosunu kontrol et, kayıt varsa sil
                    $tamamlanan = Tamamlanan::where('user_id', $basvuru->user_id)->where('islem_id', 4);
                    if($tamamlanan) $tamamlanan->delete();
                }

                $basvuru = IsyeriEgitimiBasvuru::with('tamamlanan')->find($id);
                DB::commit();

                return ResponseBuilder::success($basvuru);
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
        //
    }

}
