<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\FirmaYetkilisi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Resources\FirmaYetkilisi as FirmaYetkilisiResource;

class FirmaYetkilisiController extends Controller
{
    public function search($id)
    {
        $firma_yetkilileri = FirmaYetkilisi::where('firma_id', $id)->with('user')->get();
        return ResponseBuilder::success(['firma_yetkilileri' =>  $firma_yetkilileri]);
    }
}
