<?php

namespace App\Http\Controllers\API\Bbys;

use App\OgretimElemani;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OgretimElemani\UpdateRequest;
use App\Http\Requests\OgretimElemani\StoreRequest;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\OgretimElemani as OgretimElemaniResource;

class OgretimElemaniController extends Controller
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
        $kullanici_bilgileri = OgretimElemani::all();
        return ResponseBuilder::success(['ogretim_elemanlari' => OgretimElemaniResource::collection($kullanici_bilgileri)]);
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
            $kullanici = User::forceCreate([
                'name'        => $request['name'],
                'email'             => $request['email'],
                'password'          => bcrypt($request['password']),
                'email_verified_at' => now()
            ]);

            $ogretim_elemani = OgretimElemani::forceCreate([
                'user_id'      => $kullanici->id,
                'unvan'        => $request['unvan'],
                'cep_telefonu' => $request['cep_telefonu'],
                'is_telefonu'  => $request['is_telefonu']
            ]);

            $kullanici->sendPasswordNotification($request);

            DB::commit();

            return ResponseBuilder::success(new OgretimElemaniResource($ogretim_elemani));

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
    public function update(UpdateRequest $request, $id)
    {
        $user = User::find($id);
        $ogretim_elemani = OgretimElemani::where('user_id', $id)->first();

        DB::beginTransaction();
        try {

            $ogretim_elemani->unvan = $request['unvan'];
            $ogretim_elemani->cep_telefonu = $request['cep_telefonu'];
            $ogretim_elemani->is_telefonu = $request['is_telefonu'];
            $ogretim_elemani->save();

            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->status = $request['status'];
            $user->save();

            DB::commit();

            return ResponseBuilder::success();

        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::error();
        }
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
        OgretimElemani::where('user_id', $id)->delete();
        $user = User::findOrFail($id);
        $user->delete();
        return ResponseBuilder::success();
    }
    public function toggleStatus($id)
    {
        $ogretim_elemani = User::find($id);

        if ($ogretim_elemani->status==='active')
            $ogretim_elemani->update([
                'status' => 'passive'
            ]);
        else
            $ogretim_elemani->update([
                'status' => 'active'
            ]);
        return ResponseBuilder::success();
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|password|min:8|confirmed',
        ]);

        $ogretim_elemani = User::find($id);

        $ogretim_elemani->update([
            'password' => bcrypt($request->input('password'))
        ]);

        return ResponseBuilder::success();
    }
}
