<?php

namespace App\Http\Controllers\API\Bbys;

use App\MailDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\DraftRequest;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class MailDraftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taslaklar = MailDraft::all();
        return ResponseBuilder::success(['mail_taslaklari' => $taslaklar]);
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
    public function store(DraftRequest $request)
    {
        DB::beginTransaction();
        try {
            $taslak = new MailDraft();
            $taslak->baslik = $request['baslik'];
            $taslak->metin = $request['metin'];
            $taslak->save();

            DB::commit();

            return ResponseBuilder::success($taslak);
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
    public function update(DraftRequest $request, $id)
    {
        $taslak = MailDraft::find($id);

        DB::beginTransaction();
        try {

            $taslak->baslik = $request['baslik'];
            $taslak->metin = $request['metin'];
            $taslak->save();

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
        $taslak = MailDraft::find($id);
        $taslak->delete();
        return ResponseBuilder::success();
    }
}
