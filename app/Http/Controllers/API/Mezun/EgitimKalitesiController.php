<?php

namespace App\Http\Controllers\API\Mezun;

use App\EgitimKalitesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\EgitimKalitesi\StoreRequest;
use Illuminate\Support\Facades\Auth;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class EgitimKalitesiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasRole('Mezun'))
            return ResponseBuilder::error();

        $gereklilik = EgitimKalitesi::where('user_id', $user->id)
            ->get();

        return ResponseBuilder::success(['egitimkalite' => $gereklilik]);
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
        if (!$user->hasRole('Mezun'))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {

                $oneri = EgitimKalitesi::forceCreate([
                    'user_id' => $user->id,
                    'kalite' => $request['kalite'],
                    'sebep' => $request['sebep']
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
        if (!$user->hasRole('Mezun'))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {
            $oneri = EgitimKalitesi::where('id', $id)
                ->update([
                    'user_id' => $user->id,
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

        $oneri = EgitimKalitesi::where('id', $id)->where('user_id', $user->id)->first();
        $oneri->delete();
        return ResponseBuilder::success();
    }
}
