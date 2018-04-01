<?php

namespace App\Http\Controllers\Api;

use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;


class TestController extends Controller
{
    public function index(Request $request) {
//        Excel::create('Laravel Excel', function($excel) {
//            $excel->sheet('Excel sheet', function($sheet) {
//
//                $sheet->setOrientation('landscape');
//
//            });
//        })->export('xls');
    }

    public function import(Request $request) {

        $this->validate($request, [
            'tram_bao_hanh_id' => 'required',
            'excel' => 'required|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);


        $sheet = Excel::load($request->file('excel'), function($reader) {
        })->get();



        return response()->json($sheet, 200);
    }
}
