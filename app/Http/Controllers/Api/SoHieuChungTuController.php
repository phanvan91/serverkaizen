<?php

namespace App\Http\Controllers\Api;

use App\SoHieuChungTu;
use App\ToChuc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class SoHieuChungTuController extends Controller
{

    public function all(Request $request) {
        $list = $request->get('to_chuc')->danhSachSoHieuChungTu;
        return response()->json($list, 200);

    }
    public function getPagination(Request $request)
    {
        $list=DB::table('so_hieu_chung_tus')->where('to_chuc_id',$request->get('to_chuc')->id)->paginate(15);
        return response()->json($list, 200);


    }

    public function getByType(Request $request) {
        $loai_chung_tu = $request->get('loai_chung_tu');
        $loai_kho = $request->get('loai_kho');
        $maLoaiCT = '';
        if ($loai_chung_tu == \Config::get('constant.loai_ct.xuat_kho')){
            $maLoaiCT = 'PXK';
        }else  if ($loai_chung_tu == \Config::get('constant.loai_ct.nhap_kho')){
            $maLoaiCT = 'PNK';
        }else  if ($loai_chung_tu == \Config::get('constant.loai_ct.chuyen_kho')){
            $maLoaiCT = 'PCK';
        }

        if ($loai_kho == \Config::get('constant.loai_kho.tot')){
            $maLoaiCT = $maLoaiCT.'LKT';
        }else  if ($loai_chung_tu == \Config::get('constant.loai_kho.xac')){
            $maLoaiCT = $maLoaiCT.'LKX';
        }else  if ($loai_chung_tu == \Config::get('constant.loai_kho.thanh_pham')){
            $maLoaiCT = $maLoaiCT.'TPBH';
        }

        $loai_chung_tu_id = '';
        $loai_chung_tu_ids = DB::select(DB::raw('select * from loai_chung_tus where loai_chung_tu =\''.$maLoaiCT.'\''));
        if ($loai_chung_tu_ids && count($loai_chung_tu_ids) > 0){
            $loai_chung_tu_id = $loai_chung_tu_ids [0]->id;
        }
        $list = $request->get('to_chuc')->danhSachSoHieuChungTu()->where('loai_chung_tu_id',$loai_chung_tu)->get();
        return response()->json($list, 200);

    }

    public function getDetail(Request $request) {
        $id = $request->get('so_hieu_id');
        $soHieuCT = SoHieuChungTu::with([
            'tai_khoan_co' => function ($query) {
                $query->get();
            },
            'tai_khoan_no' => function ($query) {
                $query->get();
            }
        ])->where('so_hieu_chung_tus.id', $id)->first();

        return response()->json($soHieuCT, 200);
    }

    public function search(Request $request){
        $this->validate($request, [
            'to_chuc_id' => 'required',
        ]);

        $loai_chung_tu = $request->get('loai_chung_tu');
        $loai_chung_tu_id = ToChuc::findOrFail($request->get('to_chuc_id'))
            ->danhSachLoaiChungTu()->where('loai_chung_tu',$loai_chung_tu)->first()->id;
        $list = ToChuc::findOrFail($request->get('to_chuc_id'))->danhSachSoHieuChungTu()
            ->where('loai_chung_tu_id',$loai_chung_tu_id)->get();

        return response()->json($list, 200);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'so_hieu_chung_tu' => 'required',
            'ten_chung_tu' => 'required',
            // 'muc_dich_su_dung' => 'required',
            'tai_khoang_no_id' => 'required',
            'tai_khoang_co_id' => 'required',
            'loai_chung_tu_id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');

        try {
            $soHieuChungTu = $toChuc->danhSachSoHieuChungTu()->create([
                'so_hieu_chung_tu' => $request->get('so_hieu_chung_tu'),
                'ten_chung_tu' => $request->get('ten_chung_tu'),
                'muc_dich_su_dung' => $request->get('muc_dich_su_dung'),
                'tai_khoang_no_id' => $request->get('tai_khoang_no_id'),
                'tai_khoang_co_id' => $request->get('tai_khoang_co_id'),
                'loai_chung_tu_id' => $request->get('loai_chung_tu_id'),
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($soHieuChungTu, 200);

    }

    public function update(Request $request) {
        $this->validate($request, [
            'so_hieu_chung_tu' => 'required',
            'ten_chung_tu' => 'required',
            // 'muc_dich_su_dung' => 'required',
            'tai_khoang_no_id' => 'required',
            'tai_khoang_co_id' => 'required',
            'loai_chung_tu_id' => 'required',
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');

        try {
            $soHieuChungTu = SoHieuChungTu::find($request->get('id'));
            $soHieuChungTu->so_hieu_chung_tu = $request->get('so_hieu_chung_tu');
            $soHieuChungTu->ten_chung_tu = $request->get('ten_chung_tu');
            $soHieuChungTu->muc_dich_su_dung = $request->get('muc_dich_su_dung');
            $soHieuChungTu->tai_khoang_no_id = $request->get('tai_khoang_no_id');
            $soHieuChungTu->tai_khoang_co_id = $request->get('tai_khoang_co_id');
            $soHieuChungTu->loai_chung_tu_id = $request->get('loai_chung_tu_id');
            $soHieuChungTu->save();

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($soHieuChungTu, 200);
    }

    public function delete(Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $soHieuChungTu = $toChuc->danhSachSoHieuChungTu()->where('id', $request->get('id'))->first();

        if ($soHieuChungTu) {
            try {
                $soHieuChungTu->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($soHieuChungTu, 200);
    }
}
