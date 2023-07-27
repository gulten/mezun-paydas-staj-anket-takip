<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\Islem;
use App\IslemTarih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class BasvuruTarihleriController extends Controller
{
    public function __construct()
    {
        $this->islem_adi = 'İşyeri Eğitimi Başvurusu';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tarih = Islem::where('adi', $this->islem_adi)->with('islemtarih')->first();
        return ResponseBuilder::success($tarih);
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
        $islem = Islem::where('adi', $this->islem_adi)->first();

        $tarih = IslemTarih::find($islem->id);

        DB::beginTransaction();
        try {
            $tarih->update([
                'baslangic_tarihi'    => $request['baslangic_tarihi'],
                'bitis_tarihi'        => $request['bitis_tarihi'],
            ]);
            DB::commit();

            return ResponseBuilder::success($tarih);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

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

    public function listTarih()
    {
        $tarih = Islem::where('adi', $this->islem_adi)->with('islemtarih')->first();
        return ResponseBuilder::success($tarih);
    }
}
