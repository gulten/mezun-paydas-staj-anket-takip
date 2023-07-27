<?php

namespace App\Http\Controllers\API\Bbys;

use App\Firma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Firma\StoreRequest;
use Spatie\QueryBuilder\QueryBuilder;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class FirmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $firmalar = Firma::all();
        return ResponseBuilder::success(['firmalar' => $firmalar]);
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
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {

            $firma = Firma::forceCreate([
                'adi'        => $request['adi'],
                'email'       => $request['email'],
                'telefon'     => $request['telefon'],
            ]);
            DB::commit();

            return ResponseBuilder::success($firma);
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
    public function update(StoreRequest $request, $id)
    {
        $firma = Firma::find($id);
        DB::beginTransaction();
        try {

            $firma->update([
                'adi'        => $request['adi'],
                'email'       => $request['email'],
                'telefon'     => $request['telefon'],
            ]);
            DB::commit();

            return ResponseBuilder::success($firma);
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
        $firma = Firma::find($id);
        $firma->delete();
        return ResponseBuilder::success();
    }

    public function filter()
    {
        $firmalar = QueryBuilder::for(Firma::class)
            ->allowedFilters('adi')
            ->get();
        return ResponseBuilder::success(['firmalar' => $firmalar]);
    }
}
