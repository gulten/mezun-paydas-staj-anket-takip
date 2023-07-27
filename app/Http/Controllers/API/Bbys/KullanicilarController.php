<?php

namespace App\Http\Controllers\API\Bbys;

use App\Http\Requests\User\StoreRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Anket\UpdateRequest;
use App\Http\Resources\Kullanici as KullaniciResource;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class KullanicilarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $kullanici_bilgileri = User::all();
        return ResponseBuilder::success(['kullanicilar' => KullaniciResource::collection($kullanici_bilgileri)]);
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
        return ResponseBuilder::error();
        DB::beginTransaction();
        try {

            $filteredRoles = User::OneRolesControl(collect($request->roles));//bu kontrol request'e alınacak
            $filteredRoles = User::RoleConflictControl(collect($filteredRoles));//bu kontrol request'e alınacak

            $kullanici = User::forceCreate([
                'name'              => $request['name'],
                'email'             => $request['email'],
                'status'            => $request['status'],
                'password'          => bcrypt($request['password']),
                'email_verified_at' => now()
            ]);
            $kullanici->syncRoles($filteredRoles);
            DB::commit();


            //return ResponseBuilder::success(new KullaniciResource($kullanici));

            return ResponseBuilder::success([
                'roles' => $filteredRoles,
                'user' => new KullaniciResource($kullanici),
                ]);

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
        $kullanici = User::find($id);
        if (!$kullanici)
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {

            if (!($kullanici->hasRole('Super Admin'))) {
                $kullanici->bootHasRoles();

                $filteredRoles = User::OneRolesControl(collect($request->roles));

                $filteredRoles = User::RoleConflictControl(collect($filteredRoles));

                $kullanici->syncRoles($filteredRoles);

            }
            $kullanici->name = $request['name'];
            $kullanici->email = $request['email'];
            $kullanici->status = $request['status'];
            $kullanici->save();

            DB::commit();

            return ResponseBuilder::success(['roles' => $filteredRoles]);

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
        return ResponseBuilder::error();
        $kullanici = User::find($id);
        $kullanici->delete();
        return ResponseBuilder::success();
    }

    public function toggleStatus($id)
    {
        $kullanici = User::find($id);

        if ($kullanici->status==='active')
        $kullanici->update([
            'status' => 'passive'
        ]);
        else
            $kullanici->update([
                'status' => 'active'
            ]);
        return ResponseBuilder::success();
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $kullanici = User::find($id);

        $kullanici->sendPasswordNotification($request);

        $kullanici->update([
            'password' => bcrypt($request->input('password'))
        ]);



        return ResponseBuilder::success();
    }
    public function getRoleHasKullanici(Request $request)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $kullanici_bilgileri = User::role($request->role)->get();
        return ResponseBuilder::success(['kullanicilar' => $kullanici_bilgileri]);
    }
}
