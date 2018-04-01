<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoaiChungTuController extends Controller
{

    public function all(Request $request) {

        $list = $request->get('to_chuc')->danhSachLoaiChungTu;

        return response()->json($list, 200);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'loai_chung_tu' => 'required',
            'ten_chung_tu' => 'required',
            'muc_dich_su_dung' => 'required',
        ]);

        $toChuc = $request->get('to_chuc');

        try {
            $loaiChungTu = $toChuc->danhSachLoaiChungTu()->create([
                'loai_chung_tu' => $request->get('loai_chung_tu'),
                'ten_chung_tu' => $request->get('ten_chung_tu'),
                'muc_dich_su_dung' => $request->get('muc_dich_su_dung'),
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($loaiChungTu, 200);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'loai_chung_tu' => 'required',
            'ten_chung_tu' => 'required',
            'muc_dich_su_dung' => 'required',
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');

        $loaiChungTu = $toChuc->danhSachLoaiChungTu()->where('id', $request->get('id'))->first();

        if ($loaiChungTu) {
            try {
                $loaiChungTu->loai_chung_tu = $request->get('loai_chung_tu');
                $loaiChungTu->ten_chung_tu = $request->get('ten_chung_tu');
                $loaiChungTu->muc_dich_su_dung = $request->get('muc_dich_su_dung');

                $loaiChungTu->save();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 200);
            }

        } else {
            return response()->json('Not found', 404);
        }
        return response()->json($loaiChungTu, 200);
    }

    public function delete(Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $loaiChungTu = $toChuc->danhSachLoaiChungTu()->where('id', $request->get('id'))->first();

        if ($loaiChungTu) {
            try {
                $loaiChungTu->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($loaiChungTu, 200);
    }
}
