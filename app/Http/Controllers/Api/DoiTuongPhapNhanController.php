<?php

namespace App\Http\Controllers\Api;

use App\DoiTuongPhapNhan;
use App\ToChuc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class DoiTuongPhapNhanController extends Controller
{
    public function getList(Request $request)
    {
        $to_chuc_id = $request->get('to_chuc_id');
        $key_word = $request->get('key_word');
        $result = DB::table('doi_tuong_phap_nhans')
            ->where('id', '=', $to_chuc_id)
            ->where('ma', 'like', '%' . $key_word . '%')->limit(10)->get();

        return response()->json($result, 200);
    }

    public function search(Request $request) {
        $key_word =  $request->get('key_word');
        $result = DB::table('doi_tuong_phap_nhans')
            ->where('ma','like','%'.$key_word.'%')
            ->orWhere('ten','like','%'.$key_word.'%')->limit(20)->get();

        return response()->json($result,200);
    }

    public function all(Request $request) {
        $list = $request->get('to_chuc')->danhSachDoiTuongPhapNhan;
        return response()->json($list, 200);

    }
    public function getPagination(Request $request)
    {
        $doi_tuong_phap_nhans=DB::table('doi_tuong_phap_nhans')->where('to_chuc_id',$request->get('to_chuc')->id)->paginate(15);
        return response()->json($doi_tuong_phap_nhans, 200);


    }

    public function create(Request $request) {
        $this->validate($request, [
            'ten' => 'required',
            'ma' => 'required',
            'loai' => 'required',
            'to_chuc_id' => 'required',
        ]);

        $toChuc = ToChuc::find($request->get('to_chuc')->id);

        try {
            $doiTuongPhapNhan = $toChuc->danhSachDoiTuongPhapNhan()->create([
                'ten' => $request->get('ten'),
                'ma' => $request->get('ma'),
                'loai' => $request->get('loai'),
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($doiTuongPhapNhan, 200);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'ten' => 'required',
            'ma' => 'required',
            'loai' => 'required',
            'to_chuc_id' => 'required',
            'id' => 'required'
        ]);

        try {
            $doiTuongPhapNhan = DoiTuongPhapNhan::find( $request->get('id'));
            $doiTuongPhapNhan->ten = $request->get('ten');
            $doiTuongPhapNhan->ma = $request->get('ma');
            $doiTuongPhapNhan->loai = $request->get('loai');
            $doiTuongPhapNhan->save();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }


        return response()->json($doiTuongPhapNhan, 200);
    }

    public function delete(Request $request) {
        $this->validate($request, [
            'to_chuc_id' => 'required',
            'id' => 'required'
        ]);

        $toChuc = ToChuc::find($request->get('to_chuc')->id);
        $doiTuongPhapNhan = $toChuc->danhSachDoiTuongPhapNhan()->where('id', $request->get('id'))->first();

        if ($doiTuongPhapNhan) {
            try {
                $doiTuongPhapNhan->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($doiTuongPhapNhan, 200);
    }
}
