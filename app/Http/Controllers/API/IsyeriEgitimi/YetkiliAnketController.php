<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\Anket;
use App\IsyeriEgitimiBasvuru;
use App\AnketUser;
use App\AnketCevap;
use App\FirmaYetkilisi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Anket\CreateRequest;
use App\Http\Resources\Anket as AnketResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\AnketCevap as AnketCevapResource;
use App\Http\Resources\AnketUser as AnketUserResource;

class YetkiliAnketController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check_islem_anket');
        $this->anket_id = 7;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $basvuru = IsyeriEgitimiBasvuru::active()
            ->bitis()
            ->where('yetkili_id', Auth::user()->id)
            ->where('id', $id)
            ->first();

        if ($basvuru->yetkili_anket_id){
            $anket_user = AnketUser::where('id', $basvuru->yetkili_anket_id)->first();
            return ResponseBuilder::success($anket_user);
        }

        $anket = Anket::where('id', $this->anket_id)->with('anketsorulari')->first();

        return ResponseBuilder::success(new AnketResource($anket));
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
        $sql = IsyeriEgitimiBasvuru::active()
            ->bitis()
            ->where('yetkili_id', Auth::user()->id)
            ->where('id', $id);
        $basvuru = $sql
            ->first();

        if ($basvuru->yetkili_anket_id)
            return ResponseBuilder::success($basvuru);

        $anket = Anket::where('id', $this->anket_id)->with('anketsorulari')->first();
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
                            'user_id' => Auth::user()->id,
                            'anket_id' => $this->anket_id,
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
                    'user_id' => Auth::user()->id,
                    'anket_id' => $this->anket_id,
                ]
            );

            $sql
                ->update([
                'yetkili_anket_id' => $anket_user->id,
                ]);

            DB::commit();

            return ResponseBuilder::success($anket_user);
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

    public function answerList()
    {
        $user = Auth::user();

        $anket = AnketCevap::where('user_id', $user->id)->get();

        return ResponseBuilder::success(['anket_cevaplar' => AnketCevapResource::collection($anket)]);
    }

    public function ogrenciList()
    {
        $basvurular = IsyeriEgitimiBasvuru::active()
                                ->where('yetkili_id', Auth::user()->id)
                                ->with('user')
                                ->get();
        return ResponseBuilder::success(['basvurular' => $basvurular]);
    }

    public function userAnswers($id)
    {
        $sql = IsyeriEgitimiBasvuru::active()
            ->bitis()
            ->where('yetkili_id', Auth::user()->id)
            ->where('id', $id);
        $basvuru = $sql
            ->first();

        if ($basvuru->yetkili_anket_id) {
            $anket = AnketUser::where('id', $basvuru->yetkili_anket_id)->with(['user', 'anket'])->first();
            $cevaplar = AnketCevap::where('anket_id', $anket->anket_id)->where('user_id', $anket->user_id)->with('anketsoru')->get();
            return ResponseBuilder::success([
                'anket' => new AnketUserResource($anket),
                'anket_cevaplar' => AnketCevapResource::collection($cevaplar)
            ]);
        }

        $anket = Anket::where('id', $this->anket_id)->with('anketsorulari')->first();

        return ResponseBuilder::success(new AnketResource($anket));
    }
}
