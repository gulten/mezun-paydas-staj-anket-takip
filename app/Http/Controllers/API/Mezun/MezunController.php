<?php

namespace App\Http\Controllers\API\Mezun;

use App\User;
use App\Mezun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Mezun\MezunRequest;
use App\Http\Resources\Mezun as MezunResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class MezunController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:sanctum');
    }
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

        $user = User::where('id', $user->id)->with('mezun')->first();

        return ResponseBuilder::success(new MezunResource($user));
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
    public function store(MezunRequest $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('Mezun'))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {
             $user = User::find($user->id);
            if (is_object($user->mezun)) {
                $user->mezun()->update([
                    'telefon' => $request['telefon'],
                    'mezuniyet_tarihi' => $request['mezuniyet_tarihi'],
                    'mezuniyet_suresi' => $request['mezuniyet_suresi']
                ]);
            } else {
                $mezun = Mezun::forceCreate([
                    'user_id' => $user->id,
                    'telefon' => $request['telefon'],
                    'mezuniyet_tarihi' => $request['mezuniyet_tarihi'],
                    'mezuniyet_suresi' => $request['mezuniyet_suresi']
                ]);
            }

            $user->update([
                'name' => $request['name'],
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
        //
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
            'password' => 'required|password|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!$user->hasRole('Mezun'))
            return ResponseBuilder::error();

        $user->update([
            'password' => bcrypt($request->input('password'))
        ]);

        return ResponseBuilder::success();
    }
}
