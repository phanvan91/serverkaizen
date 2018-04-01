<?php

namespace App\Http\Controllers\Api;

use App\BangTinhCongSuaChua;
use App\ToChuc;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BangTinhCongSuaChuaController extends Controller
{

    public function all(Request $request) {

        $list = $request->get('to_chuc')->danhSachBangTinhCongSuaChua;

        return response()->json($list, 200);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'ma' => 'required',
            'don_gia' => 'required',
            'ten' => 'required',
            'trung_tam_bao_hanh_id' => 'required',
        ]);

        $toChuc = $request->get('to_chuc');
        $trungTamBaoHanh = $toChuc->trungTamBaoHanh()->where('id', $request->get('trung_tam_bao_hanh_id'))->first();
        $btcsc = DB::table('bang_tinh_cong_sua_chuas')->where(array('ma_huong_khac_phuc'=>$request->get('ma')))->first();
        if(!$btcsc)
        {
            if ($trungTamBaoHanh) {
                try {
                    $btcsc = $trungTamBaoHanh->danhSachBangTinhCongSuaChua()->create([
                        'ma_huong_khac_phuc' => $request->get('ma'),
                        'ten_huong_khac_phuc' => $request->get('ten'),
                        'don_gia' => $request->get('don_gia'),
                        'to_chuc_id' => $toChuc->id
                    ]);
                } catch (\Exception $e) {
                    return response()->json($e->getMessage(), 500);
                }
            } else {
                return response()->json('Not found', 404);
            }

        }
        else{
            return response()->json('Mã này đã tồn tại', 404);

        }


        return response()->json($btcsc, 200);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'ma' => 'required',
            'ten' => 'required',
            'trung_tam_bao_hanh_id' => 'required',
            'don_gia' => 'required',
            'id' => 'required'
        ]);
        try {
            $data['ma_huong_khac_phuc'] = $request->get('ma');
            $data['ten_huong_khac_phuc'] = $request->get('ten');
            $data['trung_tam_bao_hanh_id'] = $request->get('trung_tam_bao_hanh_id');
            $data['don_gia']= $request->get('don_gia');
            DB::table('bang_tinh_cong_sua_chuas')
                ->where('id', $request->get('id'))
                ->update($data);
            return response()->json($data, 200);


        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $btcsc = DB::table('bang_tinh_cong_sua_chuas')->where(array('id'=>$request->get('id')))->first();
        if ($btcsc) {
            try {
                DB::table('bang_tinh_cong_sua_chuas')->where('id', '=', $request->get('id'))->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('Not found', 404);
        }
    }
    public function getPagination(Request $request)
    {
        $btcsc=DB::table('bang_tinh_cong_sua_chuas')
            ->where('to_chuc_id',$request->get('to_chuc')->id)
            ->paginate(15);
        return response()->json($btcsc, 200);


    }

    public function filter(Request $request) {
        $result = BangTinhCongSuaChua::
        where(DB::raw("CONCAT(`ma_huong_khac_phuc`, ' ', `ten_huong_Khac_phuc`)"), 'LIKE', "%". $request->get('query') ."%")
            ->where('to_chuc_id', $request->get('to_chuc')->id)
            ->limit(10)->get();
        if (count($result) <= 0)
        {
            $result =DB::table('bang_tinh_cong_sua_chuas')
                ->where('to_chuc_id', $request->get('to_chuc')->id)
                ->limit(10)->get();
        }
        return response()->json($result, 200);
    }

    public function import(Request $request) {
        $this->validate($request, [
            'data' => 'required',
            'trung_tam_bao_hanh_id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $trungTamBaoHanh = $toChuc->trungTamBaoHanh()->where('id', $request->get('trung_tam_bao_hanh_id'))->first();

        if ($trungTamBaoHanh) {
            try {
                $data = $request->get('data');
                $dataSet = [];
                foreach ($data as $item) {
                    $btcsc = [
                        'ma_huong_khac_phuc' => $item['ma'],
                        'ten_huong_khac_phuc' => $item['ten'],
                        'don_gia' =>$item['don_gia'],
                        'to_chuc_id' => $toChuc->id,
                        'trung_tam_bao_hanh_id' => $trungTamBaoHanh->id
                    ];
                    array_push($dataSet, $btcsc);
                }
                BangTinhCongSuaChua::insert($dataSet);
            }catch (\Exception $e){
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($dataSet, 200);
    }
}
