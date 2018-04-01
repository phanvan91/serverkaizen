<?php

namespace App\Http\Controllers\Api;

use App\ToChuc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoaiNguoiDungController extends Controller
{
    public function all(Request $request) {

        $list = $request->get('to_chuc')->danhSachLoaiNguoiDung;
        return response()->json($list, 200);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'ten_loai' => 'required',
            'dien_giai' => 'required',
        ]);

        $toChuc = $request->get('to_chuc');

        try {
            $loaiNguoiDung = $toChuc->danhSachLoaiNguoiDung()->create([
                'ten_loai' => $request->get('ten_loai'),
                'dien_giai' => $request->get('dien_giai'),
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($loaiNguoiDung, 200);

    }

    public function update(Request $request) {
        $this->validate($request, [
            'ten_loai' => 'required',
            'dien_giai' => 'required',
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');

        try {
            $loaiNguoiDung = $toChuc->danhSachLoaiNguoiDung()->where('id', $request->get('id'))->first();
            $loaiNguoiDung->ten_loai = $request->get('ten_loai');
            $loaiNguoiDung->dien_giai = $request->get('dien_giai');
            $loaiNguoiDung->save();

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($loaiNguoiDung, 200);
    }

    public function delete(Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $loaiNguoiDung = $toChuc->danhSachLoaiNguoiDung()->where('id', $request->get('id'))->first();

        if ($loaiNguoiDung) {
            try {
                $loaiNguoiDung->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($loaiNguoiDung, 200);
    }
}
