<?php

namespace App\Http\Controllers\API\Bbys;

use App\BolumBilgileri;
use App\Http\Requests\BolumBilgileri\UpdateRequest;
use App\Http\Resources\BolumBilgileri as BolumBilgileriResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class BolumController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $bolum_bilgileri = BolumBilgileri::all()->first();
        return  ResponseBuilder::success(new BolumBilgileriResource($bolum_bilgileri));
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
        return  ResponseBuilder::success();
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
    public function update(UpdateRequest $request, $id)
    {

        $bolum = BolumBilgileri::find($id);
        $bolum->update([
            'universite_adi' => $request['universite_adi'],
            'fakulte_adi' => $request['fakulte_adi'],
            'adi' => $request['adi'],
            'kurulus_yili' => $request['kurulus_yili'],
            'faaliyet_baslangic_tarihi' => $request['faaliyet_baslangic_tarihi'],
            'adres' => $request['adres'],
            'telefon' => $request['telefon'],
            'email' => $request['email'],
            ]);
        return ResponseBuilder::success();
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
