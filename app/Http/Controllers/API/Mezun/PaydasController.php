<?php

namespace App\Http\Controllers\API\Mezun;

use App\User;
use App\Paydas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Paydas\PaydasRequest;
use Illuminate\Support\Facades\Auth;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasRole('Paydas'))
            return ResponseBuilder::error();

        $user = User::where('id', $user->id)->with('paydas')->first();

        return ResponseBuilder::success(new PaydasResource($user));
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
    public function store(PaydasRequest $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('Paydas'))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {
            $user = User::find($user->id);
            if (!$user->paydas) {
                $mezun = Paydas::forceCreate([
                    'user_id' => $user->id,
                    'user_id' => $user->id,
                    'telefon' => $request['telefon'],
                ]);
            } else {
                Paydas::where('user_id', $user->id)
                    ->update([
                        'user_id' => $user->id,
                        'telefon' => $request['telefon'],
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
        if (!$user->hasRole('Paydas'))
            return ResponseBuilder::error();

        $user->update([
            'password' => bcrypt($request->input('password'))
        ]);

        return ResponseBuilder::success();
    }
}
