<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\User;
use App\ApiCode;
use App\FirmaYetkilisi;
use Illuminate\Http\Request;
use App\IsyeriEgitimiBasvuru;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class BasvuruYonetimController extends Controller
{
    public function __construct()
    {
        $this->middleware(['check_isdeneyimi_tarih:İşyeri Eğitimi Başvurusu']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ResponseBuilder::success([
            'basvurular' => IsyeriEgitimiBasvuru::active()->with('user')->with('firma')->with('firmayetkilisi')->get(),
            ]);
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
        //
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
        $yetkili = FirmaYetkilisi::where('user_id', $request['firmayetkilisi']['user']['id'])->first();
        $basvuru = IsyeriEgitimiBasvuru::active()->with('user')->with('firma')->with('firmayetkilisi')->find($id);
        $basvuru->update([
            'yetkili_id' => $yetkili->user_id
        ]);

        return ResponseBuilder::success($basvuru->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $basvuru = IsyeriEgitimiBasvuru::find($id);

        $basvuru->delete();
        return ResponseBuilder::success();
    }

    public function toggleDocuments($id)
    {
        $basvuru = IsyeriEgitimiBasvuru::active()->find($id);

        if(!$basvuru->yetkili_id)
            return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('Başvuruyu bir yetkili ile ilişkilendirmelisiniz.')->build();

            DB::beginTransaction();
        try {
            if ($basvuru->basvuru_belge_teslim === 'teslim edildi'){
                $basvuru->update([
                    'islem_id' => 1,
                    'basvuru_belge_teslim' => 'bekleniyor',
                ]);
            }
            else{
                $basvuru->update([
                    'islem_id' => 2,
                    'basvuru_belge_teslim' => 'teslim edildi',
                ]);

                $user = User::find($basvuru->user_id);
                $request = new Request();
                $request->baslik = "İşyeri Eğitimi Başvunuz Hakkında";
                $request->metin = "İşyeri eğitimi başvurunuz komisyon tarafından uygun görülmüştür";

                $user->sendEmailSecretNotification($request);
            }
            DB::commit();

            return ResponseBuilder::success($basvuru);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('Başvuru Belgeleri Onaylanamaz.')->build();
        }
    }

}
