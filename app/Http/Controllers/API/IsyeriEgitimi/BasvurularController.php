<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\Firma;
use App\Islem;
use App\ApiCode;
use App\IsyeriEgitimiBasvuru;
use App\Tamamlanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\IsyeriEgitimi\BasvuruGunHesapRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IsyeriEgitimi\BasvuruRequest;
use App\Http\Resources\Basvuru as BasvuruResources;
use App\Http\Resources\Tamamlanan as TamamlananResources;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class BasvurularController extends Controller
{
    public function __construct()
    {
        $this->middleware(['check_tamamlanan_islem:1']);
        $this->middleware(['check_isdeneyimi_tarih:İşyeri Eğitimi Başvurusu']);
        $this->min_gun = config('global.isyeri.min_gun');
        $this->max_gun = config('global.isyeri.max_gun');
        $this->basvuru = config('global.isyeri.basvuru');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $basvuru = IsyeriEgitimiBasvuru::active()->where('user_id', Auth::user()->id)->with(['firma', 'tamamlanan'])->first();
        if($basvuru)
            return ResponseBuilder::success(new BasvuruResources($basvuru));

        $basvuru = Tamamlanan::where('user_id', Auth::user()->id)->first();
        if ($basvuru)
            return ResponseBuilder::success(['tamamlanan' => new TamamlananResources($basvuru)]);

        return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('Aktif Başvurunuz Bulunmamaktadır')->build();

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
    public function store(BasvuruRequest $request)
    {
        //return Carbon::parse($request['baslangic_tarihi'])->diffInDays(Carbon::parse($request['bitis_tarihi']));

        if (IsyeriEgitimiBasvuru::active()->where('user_id', Auth::user()->id)->first())
            return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('Zaten değerlendirilmemiş bir başvurunuz bulunmaktadır')->build();

        $basvuruGun = IsyeriEgitimiBasvuru::businessDays($request, [$this->min_gun, $this->max_gun]);

        if ($basvuruGun===false)
            return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage($this->min_gun . ' günün altında ve ' . $this->max_gun . ' günün üzerinde işyeri eğitimi yapamazsınız. Lütfen işyeri eğitimi tarihlerinizi güncelleyiniz.')->build();

        if (!(IsyeriEgitimiBasvuru::datePeriod($request->baslangic_tarihi)))
            return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('İşyeri eğitimine başlama tarihine göre evrakları işyeri eğitimi komisyonuna teslim tarihini geçirdiniz. İşyeri ile iletişime geçerek işyeri eğitimi başlama tarihini değiştiriniz.')->build();

        DB::beginTransaction();
        try {
            if ($request['firma']['id'])
                $firma = Firma::find($request['firma']['id']);
            else
                $firma = Firma::create([
                    'adi'           => $request['firma']['adi'],
                    'telefon'       => $request['firma']['telefon'],
                    'email'         => $request['firma']['email'],
                ]);

            $basvuru = IsyeriEgitimiBasvuru::create([
                'user_id'           => Auth::user()->id,
                'islem_id'          => 1,
                'baslangic_tarihi'  => $request['baslangic_tarihi'],
                'bitis_tarihi'      => $request['bitis_tarihi'],
                'cumartesi'      => $request['cumartesi'],
                'firma_id'          => $firma->id,
                'staj_gun_sayisi'   => $basvuruGun
            ]);

            $basvuru->refresh();

            DB::commit();

            return ResponseBuilder::success(new BasvuruResources($basvuru));
        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('İşlem Başarısız. Lütfen Bilgileri Kontrol Ediniz.')->build();
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $basvuru = IsyeriEgitimiBasvuru::active()->reject()->where('id', $id)->where('user_id', Auth::user()->id);
        if ($basvuru) {
            $basvuru->delete();
            return ResponseBuilder::success();
        }
        return ResponseBuilder::error();

    }

    public function sumDays()
    {
        $sql = IsyeriEgitimiBasvuru::where('user_id', Auth::user()->id);
        $tamamlanan = Tamamlanan::where('user_id', Auth::user()->id)->where('islem_id', 4)->first();

        $kabul_edilen_gun_sayisi = 0;

        if ($sql)
            $kabul_edilen_gun_sayisi = $sql->where('islem_id', 4)->where('statu', 1)->sum('kabul_edilen_gun_sayisi');

        return ResponseBuilder::success(
            [
                'toplam_gun' => $kabul_edilen_gun_sayisi,
                'kalan_gun' => $this->max_gun>= $kabul_edilen_gun_sayisi?$this->max_gun - $kabul_edilen_gun_sayisi:0,
                'bitis_durum' => $tamamlanan?$tamamlanan->statu:null
            ]
        );
    }
}
