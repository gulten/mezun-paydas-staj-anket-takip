<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\User;
use App\ApiCode;
use Illuminate\Http\Request;
use App\IsyeriEgitimiBasvuru;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class BitisYonetimController extends Controller
{
    public function __construct()
    {
        $this->middleware(['check_isdeneyimi_tarih:İşyeri Eğitimi Bitiş Evrakları Teslimi']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $basvurular = IsyeriEgitimiBasvuru::active()->bitis()->with('user')->with('firma')->get();
        return ResponseBuilder::success(['basvurular' => $basvurular]);
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

    public function toggleDocuments($id)
    {
        $basvuru = IsyeriEgitimiBasvuru::active()->bitis()->anket()->find($id);

        DB::beginTransaction();
        try {
            if ($basvuru->bitis_belge_teslim === 'teslim edildi'){
                $basvuru->update([
                    'islem_id' => 2,
                    'bitis_belge_teslim' => 'bekleniyor',
                ]);
            }
            else{
                $basvuru->update([
                    'islem_id' => 3,
                    'bitis_belge_teslim' => 'teslim edildi',
                ]);

                $user = User::find($basvuru->user_id);
                $request = new Request();
                $request->baslik = "İşyeri Eğitimi Başvunuz Hakkında";
                $request->metin = "İşyeri eğitimi bitiş belgeleriniz komisyon tarafından uygun görülmüştür.
                Mülakat tarihlerini sistemden takip ediniz.";

                $user->sendEmailSecretNotification($request);
            }

            DB::commit();

            return ResponseBuilder::success($basvuru);
        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::asError(ApiCode::UnprocessableEntity)->withMessage('Bitiş Belgeleri Onaylanamaz.')->build();
        }
    }
}
