<?php

namespace App\Http\Controllers\API\Anket;

use App\Anket;
use App\AnketSoru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Anket\UpdateRequest;
use App\Http\Resources\Anket as AnketResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class AnketController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:sanctum');
    }

    public function show($id){

        $anket = Anket::where('id', $id)->with('anketsorulari')->first();

        return ResponseBuilder::success(new AnketResource($anket));

    }

    public function store(UpdateRequest $request)
    {
        DB::beginTransaction();
        try {
        foreach ($request['anket_sorular'] as $key => $item) {

            if ($item['id']) {
                AnketSoru::where('id', $item['id'])
                ->update(
                    [
                        'soru' => $item['soru'],
                        'soru_tipi' => $item['soru_tipi'],
                        'detay' => $item['detay'],
                        'required' => $item['required'],
                        'sira' => $key
                    ]
                );
            }
            else{
                AnketSoru::create(
                    [
                        'anket_id' => $request['id'],
                        'soru_tipi' => $item['soru_tipi'],
                        'soru' => $item['soru'],
                        'detay' => json_encode(
                                $item['detay']
                            ),
                        'required' => $item['required'],
                        'sira' => $key
                    ]
                );
            }
        }
            DB::commit();

            return ResponseBuilder::success();
        } catch (\Exception $e) {
            DB::rollback();

            return ResponseBuilder::error();
        }
    }

    public function destroy($id)
    {
        $deletedRows = AnketSoru::where('id', $id)->delete();
        return ResponseBuilder::success();
    }
}
