<?php

namespace App\Http\Controllers\API\Bbys;

use App\Traits\RoleControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Resources\Role as RoleResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class RollerController extends Controller
{
    use RoleControl;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = collect(Role::select('id', 'name')->get());
        $diff = $collection->diffKeys(RoleControl::getImportantRolesWithName());
        return ResponseBuilder::success(['roller' => RoleResource::collection($diff->values()->all())]);
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

        if (Role::where('name', $request['name'])->first())
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {

            $role = Role::create(['guard_name' => 'web', 'name' => $request['name']]);

            DB::commit();

            return ResponseBuilder::success(new RoleResource($role));

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
        $role = Role::where('id', $id)->first();
        if (!$role)
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {

            $role->name = $request['name'];
            $role->save();

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
        $role = Role::find($id);
        //if role has permission, role do not deleted
        if (count($role->getPermissionNames()))
            return ResponseBuilder::error();

        $role->delete();
        return ResponseBuilder::success();
    }
}
