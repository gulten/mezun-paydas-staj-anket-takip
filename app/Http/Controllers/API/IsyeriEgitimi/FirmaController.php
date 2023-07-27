<?php

namespace App\Http\Controllers\API\IsyeriEgitimi;

use App\Firma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class FirmaController extends Controller
{
    public function filter(){
        $firmalar = QueryBuilder::for(Firma::class)
            ->allowedFilters('adi')
            ->get();
        return ResponseBuilder::success(['firmalar' => $firmalar]);
    }
}
