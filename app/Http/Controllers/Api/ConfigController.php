<?php

namespace App\Http\Controllers\Api;

use Cache;
use File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

class ConfigController extends Controller
{
    public function cityProvinceInfo() {
        if ($file = Cache::get('city')) {
            return $file;
        }
        $path = public_path('files/tree_city.json');
        $fileCity = File::get($path);
        Cache::forever('tree_city', $fileCity);

        $path = public_path('files/tinh_tp.json');
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        Cache::forever('city', $file);
        return $response;
    }

    public function getDistrict(Request $request) {

        if ($request->get('parent_code')) {
            $file = json_decode(Cache::get('tree_city'), true);
            return response()->json($file[$request->get('parent_code')]['quan-huyen'], 200);

        }


        if ($file = Cache::get('district')) {
            return $file;
        }
        $path = public_path('files/quan_huyen.json');
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        Cache::forever('district', $file);
        return $response;
    }

    public function getVillage(Request $request) {

        if ($request->get('parent_code')) {
            $file = json_decode(Cache::get('tree_city'), true);
            return response()->json($file[$request->get('city_code')]['quan-huyen'][$request->get('parent_code')]['xa-phuong'], 200);

        }

        if ($file = Cache::get('village')) {
            return $file;
        }
        $path = public_path('files/xa_phuong.json');
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        Cache::forever('village', $file);
        return $response;
    }
}
