<?php

namespace App\Http\Controllers\Api;
use App\Exceptions\DuplicateInfoException;

use App\LinhKien;
use App\NoLinhKienXac;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class NoLinhKienXacController extends Controller
{
    public function getList(Request $request) {  // get No linh kien xac
        try {
            $list = NoLinhKienXac::with('user', 'tramBaoHanh', 'linhKienCap', 'linhKienThuHoi')
                ->where('trang_thai', 'like', '%' . $request->get('trang_thai') . '%')
                ->where('phieu_sua_chua_id', 'like', '%' . $request->get('so_phieu') . '%')
                ->where('nhan_vien_id', 'like', '%' . $request->get('user_id') . '%')
                ->where('tram_bao_hanh_id', 'like', '%' . $request->get('tram_bao_hanh_id') . '%');
            if ($request->get('startDate')) {
                $list = $list->where('created_at', '>=', $request->get('startDate'))
                    ->where('created_at', '<=', $request->get('endDate'));
            }
            $list = $list->get();
        }catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($list, 200);

    }
    public function getListbyPSC(Request $request) {

        $list = NoLinhKienXac::with('user', 'tramBaoHanh', 'linhKienCap', 'linhKienThuHoi')
            ->where('phieu_sua_chua_id',$request->get('phieu_sua_chua_id'))
            ->get();
        foreach ($list as $value)
        {
            $linh_kien_cap_id = $value->linh_kien_cap_id;
            $linh_lien_thu_hoi_id =$value->linh_kien_thu_hoi_id;
            $value->linh_kien_cap =  DB::table('linh_kiens')->where(array('id'=>$linh_kien_cap_id))->first();
            $value->linh_kien_thu_hoi =  DB::table('linh_kiens')->where(array('id'=>$linh_lien_thu_hoi_id))->first();

        }
        return response()->json($list, 200);

    }
    public function getNoLKXbyPSC(Request $request) {

        $noLKX = NoLinhKienXac::with('user', 'tramBaoHanh', 'linhKienCap', 'linhKienThuHoi')
            ->where('id',$request->get('id'))
            ->first();
        $linh_kien_cap_id = $noLKX->linh_kien_cap_id;
        $linh_lien_thu_hoi_id =$noLKX->linh_kien_thu_hoi_id;
        $noLKX->linh_kien_cap =  DB::table('linh_kiens')->where(array('id'=>$linh_kien_cap_id))->first();
        $noLKX->linh_kien_thu_hoi =  DB::table('linh_kiens')->where(array('id'=>$linh_lien_thu_hoi_id))->first();

        return response()->json($noLKX, 200);

    }
    public function updateLKX(Request $request)
    {
        $data = $request->all();

        try{

            DB::table('no_linh_kien_xacs')
                ->where('id', $request->get('id'))
                ->update($data);
            $noLKX = DB::table('no_linh_kien_xacs')->where(array('id'=>$request->get('id')))->first();
            $linh_kien_cap_id = $noLKX->linh_kien_cap_id;
            $linh_lien_thu_hoi_id =$noLKX->linh_kien_thu_hoi_id;
            $noLKX->linh_kien_cap =  DB::table('linh_kiens')->where(array('id'=>$linh_kien_cap_id))->first();
            $noLKX->linh_kien_thu_hoi =  DB::table('linh_kiens')->where(array('id'=>$linh_lien_thu_hoi_id))->first();
            return Response::json($noLKX, 200);

        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }


    public function dsChoNhapKho(Request $request) {
        try {
            $list = NoLinhKienXac::with('user', 'tramBaoHanh', 'linhKienCap', 'linhKienThuHoi')
                ->where('trang_thai', 'like', '%' . $request->get('trang_thai') . '%')
                ->where('so_luong_thu','>',0)
                ->where('hoan_thanh_tra_xac', 1)
                ->where('phieu_sua_chua_id', 'like', '%' . $request->get('so_phieu') . '%')
                ->where('nhan_vien_id', 'like', '%' . $request->get('user_id') . '%')
                ->where('tram_bao_hanh_id', 'like', '%' . $request->get('tram_bao_hanh_id') . '%');
            if ($request->get('startDate')) {
                $list = $list->where('created_at', '>=', $request->get('startDate'))
                    ->where('created_at', '<=', $request->get('endDate'));
            }
            $list = $list->get();
        }catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($list, 200);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'id' => 'required',
            'so_luong' => 'required'
        ]);

        try {
            $noLinhKienXac = NoLinhKienXac::find($request->get('id'));

            if($request->get('so_luong') == 0){
                $noLinhKienXac->trang_thai = 0;
            }else if($request->get('so_luong') <= $noLinhKienXac->so_luong_cap) {
                $noLinhKienXac->trang_thai = 1;
                $noLinhKienXac->so_luong_thu = $request->get('so_luong');
                if($request->get('so_luong') >= $noLinhKienXac->so_luong_cap) {
                    $noLinhKienXac->hoan_thanh_tra_xac = 1;
                }
            }

            if($request->get('ghi_chu')) {
                $noLinhKienXac->ghi_chu = $request->get('ghi_chu');
            }

            $noLinhKienXac->save();
        }catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($noLinhKienXac, 200);

    }
}
