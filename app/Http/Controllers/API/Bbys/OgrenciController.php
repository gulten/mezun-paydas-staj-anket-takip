<?php

namespace App\Http\Controllers\API\Bbys;

use App\Ogrenci;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ogrenci\StoreRequest;
use App\Http\Requests\Ogrenci\UpdateRequest;
use App\Http\Resources\Ogrenci as OgrenciResource;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class OgrenciController extends Controller
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
        $kullanici_bilgileri = User::role('Ogrenci')->get();
        return ResponseBuilder::success(['ogrenciler' => OgrenciResource::collection($kullanici_bilgileri)]);
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

            $user = User::forceCreate([
                'name'        => $request['name'],
                'email'             => $request['email'],
                'status'             => $request['status'],
                'password'          => bcrypt($request['password']),
                'email_verified_at' => now()
            ]);
            $user->assignRole('Ogrenci');
            $ogrenci = Ogrenci::forceCreate([
                'user_id' => $user->id,
                'telefon' => $request['telefon'],
                'ogrenci_no' => $request['ogrenci_no'],
                'kayit_sekli' => $request['kayit_sekli'],
                'kayit_yili' => $request['kayit_yili'],
                'adres' => $request['adres']
            ]);

            $user->sendPasswordNotification($request);

            DB::commit();

            return ResponseBuilder::success(new OgrenciResource($user));

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user->hasRole('Ogrenci'))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {

            if (is_object($user->ogrenci)) {
                $user->ogrenci()->update([
                    'telefon' => $request['telefon'],
                    'ogrenci_no' => $request['ogrenci_no'],
                    'kayit_sekli' => $request['kayit_sekli'],
                    'kayit_yili' => $request['kayit_yili'],
                    'adres' => $request['adres']
                ]);
            }
            else {
                $ogrenci = Ogrenci::forceCreate([
                    'user_id' => $user->id,
                    'telefon' => $request['telefon'],
                    'ogrenci_no' => $request['ogrenci_no'],
                    'kayit_sekli' => $request['kayit_sekli'],
                    'kayit_yili' => $request['kayit_yili'],
                    'adres' => $request['adres']
                ]);
            }

             $user->update([
                'name' => $request['name'],
                'status' => $request['status'],
                'email' => $request['email']
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
        $user = User::findOrFail($id);
        $user->delete();
        Ogrenci::where('user_id', $id)->delete();
        return ResponseBuilder::success();
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|password|min:8|confirmed',
        ]);
        $kullanici = User::find($id);

        $kullanici->update([
            'password' => bcrypt($request->input('password'))
        ]);

        return ResponseBuilder::success();
    }
}
