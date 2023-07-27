<?php

namespace App\Http\Controllers\API\Mezun;

use App\DersKalite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DersKalite\StoreRequest;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class DersKaliteController extends Controller
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

        $gereklilik = DersKalite::where('user_id', $user->id)
            ->with('bolumders')
            ->get();

        return ResponseBuilder::success(['derskalite' => $gereklilik]);
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
            $oneri_kontrol = DersKalite::where('user_id', $user->id)
                ->where('ders_id', $request['ders_id'])
                ->first();
            if ($oneri_kontrol)
                $oneri = DersKalite::where('id', $oneri_kontrol->id)
                    ->update([
                        'user_id' => $user->id,
                        'ders_id' => $request['ders_id'],
                        'kalite' => $request['kalite'],
                        'sebep' => $request['sebep']
                    ]);
            else
                $oneri = DersKalite::forceCreate([
                    'user_id' => $user->id,
                    'ders_id' => $request['ders_id'],
                    'kalite' => $request['kalite'],
                    'sebep' => $request['sebep']
                ]);

            $oneri_kontrol = DersKalite::where('id', $oneri->id)
                ->with('bolumders')
                ->first();

            DB::commit();

            return ResponseBuilder::success($oneri_kontrol);
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
            $oneri = DersKalite::where('id', $id)
                ->update([
                    'user_id' => $user->id,
                    'ders_id' => $request['ders_id'],
                    'kalite' => $request['kalite'],
                    'sebep' => $request['sebep']
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

        $oneri = DersKalite::where('id', $id)->where('user_id', $user->id)->first();
        $oneri->delete();
        return ResponseBuilder::success();
    }
}
