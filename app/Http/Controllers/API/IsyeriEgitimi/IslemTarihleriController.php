<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\Islem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class IslemTarihleriController extends Controller
{
    public function listTarih()
    {
        $basvuru_tarih = Islem::where('adi', 'İşyeri Eğitimi Başvurusu')->with('islemtarih')->first();
        $bitis_tarih = Islem::where('adi', 'İşyeri Eğitimi Bitiş Evrakları Teslimi')->with('islemtarih')->first();
        $mulakat_tarih = Islem::where('adi', 'İşyeri Eğitimi Mülakat')->with('islemtarih')->first();
        return ResponseBuilder::success([
            'basvuru_tarih' => $basvuru_tarih,
            'bitis_tarih' => $bitis_tarih,
            'mulakat_tarih' => $mulakat_tarih,
        ]);
    }
}
