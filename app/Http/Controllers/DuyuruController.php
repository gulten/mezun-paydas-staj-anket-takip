<?php

namespace App\Http\Controllers;

use App\Duyuru;
use Illuminate\Http\Request;

class DuyuruController extends Controller
{
    public function store()
    {
        Duyuru::create(request()->all());
        return response()->json(['message' => 'Duyuru kaydedildi.'], 200);
    }
}
