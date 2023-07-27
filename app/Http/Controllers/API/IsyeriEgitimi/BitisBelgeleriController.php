<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\Belge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class BitisBelgeleriController extends Controller
{
    public function __construct()
    {
        $this->file_path = config('global.upload');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $belgeler = Belge::where('belge_id', 2)->get();
        return ResponseBuilder::success(['belgeler' => $belgeler]);
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
        if ($request->hasFile('belge_yolu')) {
            $file_name = 'isyeri_' . rand() . '.' . $request->belge_yolu->getClientOriginalExtension();
            $path = $request->file('belge_yolu')->move(public_path($this->file_path), $file_name);
            $file_url = $file_name;
        }

        DB::beginTransaction();
        try {

            $belge = Belge::create([
                'belge_id'        => 2,
                'belge_adi'        => $request['belge_adi'],
                'belge_yolu'       => $file_url,
                'belge_aciklama'   => $request['belge_aciklama'],
                'status'         => $request['status'],
            ]);
            DB::commit();
            return ResponseBuilder::success($belge);
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
        $belge = Belge::find($id);
        if (!$belge)
            return ResponseBuilder::error();

        if ($request->hasFile('belge_yolu')) {

            $validator = $request->validate([
                'belge_yolu' => 'file|max:50000|mimes:pdf,application/excel,docx,doc,xls',
            ]);

            $file_name = 'isyeri_' . rand() . '.' . $request->belge_yolu->getClientOriginalExtension();
            $path = $request->file('belge_yolu')->move(public_path($this->file_path), $file_name);
            $belge->update([
                'belge_yolu'       => $file_name
            ]);
            if (is_file(public_path($belge->belge_yolu)))
                unlink(public_path($belge->belge_yolu));
        }

        DB::beginTransaction();
        try {
            $belge->update([
                'belge_adi'        => $request['belge_adi'],
                'belge_aciklama'   => $request['belge_aciklama'],
                'status'         => $request['status'],
            ]);
            DB::commit();

            return ResponseBuilder::success($belge);
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
        $belge = Belge::find($id);

        if (is_file(public_path($belge->belge_yolu)))
            unlink(public_path($belge->belge_yolu));

        $belge->delete();
        return ResponseBuilder::success();
    }

    public function toggleStatus($id)
    {
        $belge = Belge::find($id);

        if ($belge->status === 'active')
        $belge->update([
            'status' => 'passive'
        ]);
        else
            $belge->update([
                'status' => 'active'
            ]);
        return ResponseBuilder::success();
    }

    public function listBelgeler()
    {
        $belgeler = Belge::where('belge_id', 2)->where('status', 'active')->get();
        return ResponseBuilder::success(['belgeler' => $belgeler]);
    }
}
