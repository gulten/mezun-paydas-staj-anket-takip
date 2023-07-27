<?php

namespace App\Http\Controllers\API\Bbys;

use App\Komisyon;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\Komisyon as KomisyonResource;
use Spatie\Permission\Contracts\Role;

class KomisyonController extends Controller
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
        $roles = DB::table('komisyonlar')
            ->join('roles', 'komisyonlar.role_id', '=', 'roles.id')
            ->pluck('name');

        $kullanici_bilgileri = User::role($roles)->get();

        return ResponseBuilder::success(['komisyonlar' => KomisyonResource::collection($kullanici_bilgileri)]);
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

            $filteredRoles = User::OneRolesControl(collect($request->roles));//bu kontrol request'e alınacak
            $filteredRoles = User::RoleConflictControl(collect($filteredRoles));//bu kontrol request'e alınacak

            $kullanici = User::forceCreate([
                'name'        => $request['name'],
                'email'             => $request['email'],
                'status'             => $request['status'],
                'password'          => bcrypt($request['password']),
                'email_verified_at' => now()
            ]);
            $kullanici->syncRoles($filteredRoles);
            DB::commit();

            return ResponseBuilder::success(['roles' => $filteredRoles]);

        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::error();
        }
        return ResponseBuilder::success();
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

        DB::beginTransaction();
        try {

            if (!($kullanici->hasRole('Süper Admin'))) {
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
        $kullanici = User::find($id);
        $kullanici->delete();
        return ResponseBuilder::success();
    }

    public function allRoles()
    {
        $roller = DB::table('komisyonlar')
            ->join('roles', 'komisyonlar.role_id', '=', 'roles.id')
            ->get();
        return ResponseBuilder::success(['roller' => $roller]);
    }
}
