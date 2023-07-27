<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\Anket;
use App\AnketUser;
use App\AnketCevap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Anket\CreateRequest;
use App\Http\Resources\Anket as AnketResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\AnketCevap as AnketCevapResource;

class FirmaAnketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $anket_user = AnketUser::where('user_id', $user->id)->first();
        if ($anket_user)
            return ResponseBuilder::success($anket_user);

        $anket = Anket::where('id', 6)->with('anketsorulari')->first();

        return ResponseBuilder::success(new AnketResource($anket));
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
    public function store(Request $request)
    {
        $user = Auth::user();

        if (AnketUser::where('user_id', $user->id)->count())
            return ResponseBuilder::error();

        $anket = Anket::where('id', 6)->with('anketsorulari')->first();
        $cevap = '';

        DB::beginTransaction();
        try {

            if (count($anket['anketsorulari']) !== count($request['anket_sorular'])) {
                DB::rollback();
                return ResponseBuilder::error();
            }

            foreach ($anket['anketsorulari'] as $key => $item) {

                if ((int) $item['id'] === (int) $request['anket_sorular'][$key]['id']) {
                    if (isset($request['anket_sorular'][$key]['digerCevap']))
                    $cevap = $request['anket_sorular'][$key]['digerCevap'];
                    else if (isset($request['anket_sorular'][$key]['cevap']))
                    $cevap = $request['anket_sorular'][$key]['cevap'];
                    else
                        $cevap = '';

                    if (($item['required']) && ($cevap === '')) {
                        DB::rollback();
                        return ResponseBuilder::error();
                    }

                    AnketCevap::create(
                        [
                            'user_id' => $user->id,
                            'anket_id' => 1,
                            'soru_id' => $request['anket_sorular'][$key]['id'],
                            'cevap' => $cevap,
                        ]
                    );
                } else {
                    DB::rollback();
                    return ResponseBuilder::error();
                }
            }
            $anket_user = AnketUser::create(
                [
                    'user_id' => $user->id,
                    'anket_id' => 1,
                    'complate' => 1,
                ]
            );
            DB::commit();

            return ResponseBuilder::success($anket_user);
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

    public function answerList()
    {
        $user = Auth::user();

        $anket = AnketCevap::where('user_id', $user->id)->get();

        return ResponseBuilder::success(['anket_cevaplar' => AnketCevapResource::collection($anket)]);
    }
}
