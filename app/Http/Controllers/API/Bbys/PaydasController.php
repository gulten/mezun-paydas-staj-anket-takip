<?php

namespace App\Http\Controllers\API\Bbys;

use App\Paydas;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Paydas\StoreRequest;
use App\Http\Requests\Paydas\UpdateRequest;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\Paydas as PaydasResource;

class PaydasController extends Controller
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
        $kullanici_bilgileri = User::role('Paydas')->get();
        return ResponseBuilder::success(['paydaslar' => PaydasResource::collection($kullanici_bilgileri)]);
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
            $paydas = Paydas::forceCreate([
                'user_id' => $user->id,
                'telefon' => $request['telefon'],
            ]);
            $user->sendPasswordNotification($request);
            $user->syncRoles(['Paydas']);
            DB::commit();

            return ResponseBuilder::success(new PaydasResource($user));

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
        if (!$user->hasRole('Paydas'))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {

            if (is_object($user->paydas)) {
                $user->paydas()->update([
                    'telefon' => $request['telefon']
                ]);
            }
            else {
                $paydas = Paydas::forceCreate([
                    'user_id' => $user->id,
                    'telefon' => $request['telefon']
                ]);
            }

            $user->update([
                'name' => $request['name'],
                'status' => $request['status'],
                'email' => $request['email']
            ]);

            DB::commit();

            return ResponseBuilder::success(new PaydasResource($user));

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

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => ['string', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();
        if (!$user->hasRole('Paydas'))
            return ResponseBuilder::error();

        $user->update([
            'password' => bcrypt($request->input('password'))
        ]);

        return ResponseBuilder::success();
    }
}
