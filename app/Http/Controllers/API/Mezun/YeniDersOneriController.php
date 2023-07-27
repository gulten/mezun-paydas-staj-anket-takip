<?php

namespace App\Http\Controllers\API\Mezun;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\YeniDersOneri\StoreRequest;
use App\YeniDersOneri;
use Illuminate\Support\Facades\Auth;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class YeniDersOneriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasAnyRole('Mezun', 'Paydas'))
            return ResponseBuilder::error();

        $oneriler = YeniDersOneri::where('user_id', $user->id)
            ->get();

        return ResponseBuilder::success(['yenidersoneriler' => $oneriler]);
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
        $user = Auth::user();
        if (!$user->hasAnyRole(['Mezun', 'Paydas']))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {

            $oneri = YeniDersOneri::forceCreate([
                'user_id' => $user->id,
                'ders_adi' => $request['ders_adi'],
                'icerik' => $request['icerik'],
                'on_sartlar' => $request['on_sartlar'],
                'kaynaklar' => $request['kaynaklar'],
                'aciklamalar' => $request['aciklamalar']
            ]);

            DB::commit();

            return ResponseBuilder::success($oneri);
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
        $user = Auth::user();
        if (!$user->hasAnyRole(['Mezun', 'Paydas']))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {
            $oneri = YeniDersOneri::where('id', $id)
                ->update([
                    'user_id' => $user->id,
                    'ders_adi' => $request['ders_adi'],
                    'icerik' => $request['icerik'],
                    'on_sartlar' => $request['on_sartlar'],
                    'kaynaklar' => $request['kaynaklar'],
                    'aciklamalar' => $request['aciklamalar']
                ]);

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
        $user = Auth::user();
        if (!$user->hasAnyRole('Mezun', 'Paydas'))
            return ResponseBuilder::error();

        $oneri = YeniDersOneri::where('id', $id)->where('user_id', $user->id)->first();
        $oneri->delete();
        return ResponseBuilder::success();
    }
}
