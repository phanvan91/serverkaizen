<?php

namespace App\Http\Controllers;

use App\ChiPhiDiLaiPhieuSuaChua;
use App\PhieuSuaChua;
use Cache;
use File;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class TestController extends Controller
{
    public function index() {
        var_dump('123');
//        $path = storage_path('app/public/tree_city.json');
//        $file = File::get($path);
//        $type = File::mimeType($path);
//        $response = Response::make($file, 200);
//        $response->header("Content-Type", $type);
//        Cache::forever('tree_city', $file);
//
//        $file  = json_decode(Cache::get('tree_city'), true);
//
//
//        dd($file['01']['quan-huyen']['004']);

//        $psc = ChiPhiDiLaiPhieuSuaChua::find(34)->delete();
//
//        dd($psc);
    }
}
