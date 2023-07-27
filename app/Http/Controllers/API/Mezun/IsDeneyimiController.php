<?php

namespace App\Http\Controllers\API\Mezun;

use App\Http\Controllers\Controller;
use App\Http\Requests\IsDeneyimi\StoreRequest;
use App\IsDeneyimi;
use App\Mezun;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\IsDeneyimi as IsDeneyimiResource;

class IsDeneyimiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['Mezun', 'Paydas']))
            return ResponseBuilder::error();

        $user = User::where('id', $user->id)->with('isdeneyimi')->with('mezun')->with('paydas')->get();

        return ResponseBuilder::success(new IsDeneyimiResource($user));

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
        $user = Auth::user();
        if (!$user->hasAnyRole(['Mezun', 'Paydas']))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {
                $isdeneyimi = IsDeneyimi::forceCreate([
                    'user_id' => $user->id,
                    'baslama_tarihi' => $request['baslama_tarihi'],
                    'ayrilma_tarihi' => $request['ayrilma_tarihi'],
                    'kurum' => $request['kurum'],
                    'birim' => $request['birim'],
                    'gorev' => $request['gorev'],
                    'maas' => $request['maas'],
                    'created_id' => $user->id,
                ]);

            DB::commit();

            return ResponseBuilder::success($isdeneyimi);

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
    public function update(StoreRequest $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['Mezun', 'Paydas']))
            return ResponseBuilder::error();

        DB::beginTransaction();
        try {
            $isdeneyimi = IsDeneyimi::where('id', $id)
            ->update([
                'baslama_tarihi' => $request['baslama_tarihi'],
                'ayrilma_tarihi' => $request['ayrilma_tarihi'],
                'kurum' => $request['kurum'],
                'birim' => $request['birim'],
                'gorev' => $request['gorev'],
                'maas' => $request['maas'],
                'created_id' => $user->id,
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
        $user = Auth::user();
        if (!$user->hasAnyRole('Mezun', 'Paydas'))
            return ResponseBuilder::error();

        $isdeneyimi = IsDeneyimi::findOrFail($id);
        $isdeneyimi->delete();
        return ResponseBuilder::success();
    }
}
