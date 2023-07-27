<?php

namespace App\Http\Controllers\API\Bbys;

use App\BolumBaskani;
use App\Http\Resources\Kullanici as KullaniciResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BolumBaskani\StoreRequest;
use App\Http\Requests\BolumBaskani\UpdateRequest;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\BolumBaskani as BolumBaskaniResource;

class BolumBaskaniController extends Controller
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
        $kullanici_bilgileri = User::role('Bolum Baskani')->with('bolumbaskani')->get();
        return ResponseBuilder::success(new BolumBaskaniResource($kullanici_bilgileri));
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            DB::table('bolum_baskani')->truncate();
            $user = User::forceCreate([
                'name'        => $request['name'],
                'email'             => $request['email'],
                'password'          => bcrypt($request['password']),
                'status'             => 'active',
                'email_verified_at' => now()
            ]);

            $user->assignRole('Bolum Baskani');

            $bolum_baskani = BolumBaskani::forceCreate([
                'user_id' => $user->id,
                'telefon' => $request['telefon'],
                'email'  => $request['is_email']
            ]);

            $user->sendPasswordNotification($request);

            DB::commit();

            return ResponseBuilder::success();

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
        if (!$user->hasRole('Bolum Baskani'))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {
            DB::table('bolum_baskani')->truncate();
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->save();

            $bolum_baskani = BolumBaskani::forceCreate([
                'user_id' => $user->id,
                'telefon' => $request['telefon'],
                'email'  => $request['is_email']
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
        //
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => ['string', 'confirmed', 'min:8'],
        ]);

        $kullanici = User::find($id);

        $kullanici->update([
            'password' => bcrypt($request->input('password'))
        ]);

        return ResponseBuilder::success();
    }
}
