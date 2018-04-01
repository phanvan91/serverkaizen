<?php

namespace App\Http\Controllers\Api;

use App\PhieuDeNghiCapLinhKien;
use App\ToChuc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class HeThongTaiKhoangKeToanController extends Controller
{
    public function all(Request $request) {
        $list = $request->get('to_chuc')->danhSachHeThongTaiKhoangKeToan;

        return response()->json($list, 200);
    }
    public function getPagination(Request $request)
    {
        $list=DB::table('he_thong_tai_khoang_ke_toans')->where('to_chuc_id',$request->get('to_chuc')->id)->paginate(15);
        return response()->json($list, 200);


    }
    public function search(Request $request) {
        $key_word =  $request->get('key_word');
        $result = DB::table('he_thong_tai_khoang_ke_toans')
            ->where('so_hieu_tai_khoang','like', '%'.$key_word.'%')
            ->limit(20)->get();
        return response()->json($result,200);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'so_hieu_tai_khoang' => 'required',
            'ten_tai_khoang' => 'required',
        ]);

        $toChuc = $request->get('to_chuc');
        $checkSoHieuTK = $toChuc->danhSachHeThongTaiKhoangKeToan()
            ->where('so_hieu_tai_khoang', $request->get('so_hieu_tai_khoang'))->first();
        if($checkSoHieuTK) {
            return response()->json('Số hiệu tài khoản đã tồn tại', 500);
        }
        try {
            $taiKhoangKeToan = $toChuc->danhSachHeThongTaiKhoangKeToan()->create([
                'so_hieu_tai_khoang' => $request->get('so_hieu_tai_khoang'),
                'ten_tai_khoang' => $request->get('ten_tai_khoang'),
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($taiKhoangKeToan, 200);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'so_hieu_tai_khoang' => 'required',
            'ten_tai_khoang' => 'required',
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');

        try {
            $taiKhoangKeToan = $toChuc->danhSachHeThongTaiKhoangKeToan()->where('id', $request->get('id'))->first();
            $checkSoHieuTK = $toChuc->danhSachHeThongTaiKhoangKeToan()
                ->where('so_hieu_tai_khoang', $request->get('so_hieu_tai_khoang'))->first();
            if($checkSoHieuTK->id != $taiKhoangKeToan->id) {
                return response()->json('Số hiệu tài khoản đã tồn tại', 500);
            }
            $taiKhoangKeToan->so_hieu_tai_khoang = $request->get('so_hieu_tai_khoang');
            $taiKhoangKeToan->ten_tai_khoang = $request->get('ten_tai_khoang');
            $taiKhoangKeToan->save();

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }


        return response()->json($taiKhoangKeToan, 200);
    }

    public function delete(Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $taiKhoangKeToan = $toChuc->danhSachHeThongTaiKhoangKeToan()->where('id', $request->get('id'))->first();

        if ($taiKhoangKeToan) {
            try {
                $taiKhoangKeToan->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($taiKhoangKeToan, 200);
    }
}
