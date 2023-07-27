<?php

namespace App\Http\Controllers\API\Bbys;

use App\FirmaYetkilisi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\FirmaYetkilisi as FirmaYetkilisiResource;

class FirmaYetkilisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kullanici_bilgileri = User::role('Firma Yetkilisi')->get();
        return ResponseBuilder::success(['firma_yetkilileri' =>  FirmaYetkilisiResource::collection($kullanici_bilgileri)]);
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
        DB::beginTransaction();
        try {

            $user = User::forceCreate([

                'name'        => $request['name'],
                'email'             => $request['email'],
                'status'             => $request['status'],
                'password'          => bcrypt($request['password']),
                'email_verified_at' => now()
            ]);
            $firma_yetkilisi = FirmaYetkilisi::forceCreate([
                'user_id' => $user->id,
                'firma_id'=> $request['firma']['id'],
                'telefon' => $request['telefon'],
            ]);
            $user->sendPasswordNotification($request);
            $user->syncRoles(['Firma Yetkilisi']);
            DB::commit();

            return ResponseBuilder::success(new FirmaYetkilisiResource($user));
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
        $user = User::find($id);
        if (!$user->hasRole('Firma Yetkilisi'))
        return ResponseBuilder::error();

        DB::beginTransaction();
        try {

            if (is_object($user->firmayetkilisi)) {
                $user->firmayetkilisi()->update([
                    'telefon' => $request['telefon'],
                    'firma_id' => $request['firma']['id']
                ]);
            } else {
                $firma_yetkilisi = FirmaYetkilisi::forceCreate([
                    'user_id' => $user->id,
                    'firma_id' => $request['firma']['id'],
                    'telefon' => $request['telefon']
                ]);
            }

            $user->update([
                'name' => $request['name'],
                'status' => $request['status'],
                'email' => $request['email']
            ]);

            DB::commit();

            return ResponseBuilder::success(new FirmaYetkilisiResource($user));
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
