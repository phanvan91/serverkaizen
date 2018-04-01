<?php

namespace App\Http\Controllers\Api;

use App\LinhKien;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\DeNghiCapLinhKienChiTiet;
use App\PhieuDeNghiCapLinhKien;
use Carbon\Carbon;
use Response;

class LinhKienController extends Controller
{
    public function search(Request $request) {
        $key_word =  $request->get('key_word');

        $khoId = $request->get('kho_id');
        $isKhoTot = $request->get('is_kho_tot')?$request->get('is_kho_tot') : true;
        $tonKhoTable = $isKhoTot?'ton_kho_tots' : 'ton_kho_xacs';

        if ($khoId){
            $result = DB::select(DB::raw('select * from 
                    (SELECT * FROM linh_kiens where (ten like \'%'.$key_word.'%\' or ma like \'%'.$key_word.'%\') limit 20) a
                    left join (select ton_cuoi,linh_kien_id from '.$tonKhoTable.' where kho_id='.$khoId.') b 
                    on a.id = b.linh_kien_id'));
        }else {
            $result = DB::select(DB::raw('SELECT * FROM linh_kiens where (ten like \'%'.$key_word.'%\' or ma like \'%'.$key_word.'%\') limit 20'));
        }

        return response()->json($result,200);

    }
    public function searchList(Request $request) {
        $key_word =  $request->get('key_word');

        $khoId = $request->get('kho_id');
        if ($khoId){
            $result = DB::select(DB::raw('select * from 
                    (SELECT * FROM linh_kiens where (ten like \'%'.$key_word.'%\' or ma like \'%'.$key_word.'%\') limit 20) a
                    left join (select ton_cuoi,linh_kien_id from ton_kho_tots where kho_id='.$khoId.') b 
                    on a.id = b.linh_kien_id'));
        }else {
            $result = DB::select(DB::raw('SELECT * FROM linh_kiens where (ten like \'%'.$key_word.'%\' or ma like \'%'.$key_word.'%\') limit 20'));
        }
        $all = array('id'=>'0','ten'=>'Tất cả ');
        array_unshift($result, $all);
        return response()->json($result,200);

    }

    public function phieutraLK(Request $request) {

        $list = DB::table('psc_lks')
            ->join('linh_kiens', 'psc_lks.linh_kien_id', '=', 'linh_kiens.id')
            ->where('phieu_sua_chua_id',$request->get('phieu_sua_chua_id'))
            ->select('psc_lks.*', 'linh_kiens.ma', 'linh_kiens.ten')
            ->get();
        if(count($list) >0){
            if($list[0]->so_luong_tra > 0)
            {
                $data['check_tra'] =true;

            }
            else{
                $data['check_tra'] =false;

            }
            $phieu_de_nghi_cap_linh_kien= DB::table('phieu_de_nghi_cap_linh_kiens')->where(array('phieu_sua_chua_id'=>$request->get('phieu_sua_chua_id')))->first();
            $chung_tu_kho_tots= DB::table('chung_tu_kho_tots')
                ->where('phieu_sua_chua_id',$request->get('phieu_sua_chua_id'))
                ->where('loai_ct',1)
                ->first();

            $data['data'] =$list;
            $data['kho_tram_id'] = $phieu_de_nghi_cap_linh_kien->kho_tram_id;
            $data['cong_ty_id'] = $phieu_de_nghi_cap_linh_kien->cong_ty_id;
            if($chung_tu_kho_tots)
            {
                $data['chung_tu_id'] = $chung_tu_kho_tots->id;

            }




            return response()->json($data, 200);
        }

    }

    public function all(Request $request) {

        $list = \App\LinhKien::where('to_chuc_id',$request->get('to_chuc')->id)->get();

        return response()->json($list, 200);
    }

    public function paginate(Request $request) {
        $keyword = $request->get('key_word');
        if($keyword !=='undefined'){
            $list = \App\LinhKien::where('to_chuc_id',$request->get('to_chuc')->id)
            ->where('ma', 'like' , '%'.$keyword.'%')
            ->paginate(15);
        }else{
            $list = \App\LinhKien::where('to_chuc_id',$request->get('to_chuc')->id)
            ->paginate(15);
        }
        

        return response()->json($list, 200);
    }

    public function create(Request $request){
        $this->validate($request,[
            'ma' => 'required',
            'ten' => 'required',
            'don_vi' => 'required',
            'gia_ban' => 'required',
        ]);
        $linhKienExist = LinhKien::where('ma',$request->get('ma'))->first();
        if($linhKienExist) {
            return response()->json('Mã linh kiện đã tồn tại', 500);
        }
        try {
            $linhKien = new \App\LinhKien();
            $linhKien->ma = $request->get('ma');
            $linhKien->ten = $request->get('ten');
            $linhKien->don_vi = $request->get('don_vi');
            if($request->get('thang_gia_han_sau_bao_hanh')){
                $linhKien->thang_gia_han_sau_bao_hanh = $request->get('thang_gia_han_sau_bao_hanh');
            }
            $linhKien->gia_ban = $request->get('gia_ban');
            $linhKien->san_pham_id = $request->get('san_pham_id');
            $linhKien->to_chuc_id = $request->get('to_chuc')->id;

            if($request->get('nhom_linh_kien_id')){
                $linhKien->nhom_linh_kien_id = $request->get('nhom_linh_kien_id');
            }
            if($request->get('linh_kien_ao')){
                $linhKien->nhom_linh_kien_id = $request->get('linh_kien_ao');
            }
            $linhKien->save();
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($linhKien, 200);
    }
    public function update(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'ma' => 'required',
            'ten' => 'required',
            'don_vi' => 'required',
            'gia_ban' => 'required',
        ]);
        try {
            $linhKien = \App\LinhKien::find($request->get('id'));
            $linhKienCheck = LinhKien::where('ma',$request->get('ma'))->first();
            if($linhKien->id !== $linhKienCheck->id) {
                return response()->json('Mã linh kiện đã tồn tại', 500);
            }
            $linhKien->ma = $request->get('ma');
            $linhKien->ten = $request->get('ten');
            $linhKien->don_vi = $request->get('don_vi');
            if($request->get('thang_gia_han_sau_bao_hanh')){
                $linhKien->thang_gia_han_sau_bao_hanh = $request->get('thang_gia_han_sau_bao_hanh');
            }
            $linhKien->gia_ban = $request->get('gia_ban');
            $linhKien->san_pham_id = $request->get('san_pham_id');
            $linhKien->to_chuc_id = $request->get('to_chuc')->id;

            if($request->get('nhom_linh_kien_id')){
                $linhKien->nhom_linh_kien_id = $request->get('nhom_linh_kien_id');
            }
            if($request->get('linh_kien_ao')){
                $linhKien->nhom_linh_kien_id = $request->get('linh_kien_ao');
            }
            $linhKien->save();
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($linhKien, 200);
    }
    public function delete(Request $request){
        $this->validate($request,[
            'id' => 'required'
        ]);
        try{
            $linhKien = \App\LinhKien::find($request->get('id'));
            if($linhKien->to_chuc_id = $request->get('to_chuc')->id){
                $linhKien->delete();
            }else{
                return response()->json('Linh kien khong co trong to chuc', 404);
            }
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json('success', 200);
    }

    public function checkExist(Request $request){
        $this->validate($request,[
            'data' => 'required'
        ]);
        $data = $request->get('data');
        $linhKien = \App\LinhKien::whereIn('ma',$data)->get();
        if(count($linhKien)>0){ return response()->json(['exist'=>'true','data'=>$linhKien], 200); }
        else{ return response()->json(['exist'=>'false','data'=>null], 200);}
    }
    public function import(Request $request){
        $this->validate($request,[
            'data' => 'required'
        ]);
        try {
            $data = $request->get('data');
            $dataSet = [];
            foreach ($data as $item) {
                $linhKien = [
                    'ma' => $item['ma'],
                    'ten' => $item['ten'],
                    'gia_ban' => $item['gia_ban']?$item['gia_ban']:0,
                    'don_vi' => $item['don_vi'],
                    'thang_gia_han_sau_bao_hanh' => $item['thoi_han']?$item['thoi_han']:0,
                    'san_pham_id' => $item['san_pham_id'],
                    'to_chuc_id' => $request->get('to_chuc')->id
                ];
                array_push($dataSet, $linhKien);
            }
            LinhKien::insert($dataSet);
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($dataSet, 200);
    }

    public function getDeNghiCapLK(Request $request)
    {
        $phieu_de_nghis=DB::table('phieu_de_nghi_cap_linh_kiens')
            ->where('phieu_sua_chua_id',$request->get('phieu_sua_chua_id'))
            ->get();
        if ($phieu_de_nghis)
        {
            foreach ($phieu_de_nghis as $phieu_de_nghi)
            {
                $id=$phieu_de_nghi->id;
                $chi_tiet=DB::table('de_nghi_cap_linh_kien_chi_tiets')
                    ->join('linh_kiens', 'de_nghi_cap_linh_kien_chi_tiets.linh_kien_id', '=', 'linh_kiens.id')
                    ->where('phieu_de_nghi_id',$id)
                    ->select('de_nghi_cap_linh_kien_chi_tiets.*', 'linh_kiens.ten', 'linh_kiens.gia_ban', 'linh_kiens.ma')
                    ->get();
                $tong_chi_phi = 0;
                foreach ($chi_tiet as $value) {
                    $thanh_tien = ($value->don_gia * $value->so_luong);
                    $tong_chi_phi += $thanh_tien;
                }
                $phieu_de_nghi->tong_chi_phi = $tong_chi_phi;

                $phieu_de_nghi->chi_tiet_de_nghi = $chi_tiet;
            }

        }
        return Response::json($phieu_de_nghis, 200);


    }
    public function getDeNghiCapLKID(Request $request)
    {
        $phieu_de_nghi=DB::table('phieu_de_nghi_cap_linh_kiens')
            ->join('users', 'phieu_de_nghi_cap_linh_kiens.nguoi_tao_id', '=', 'users.id')
            ->where('phieu_de_nghi_cap_linh_kiens.id',$request->get('phieu_de_nghi_id'))
            ->select('phieu_de_nghi_cap_linh_kiens.*', 'users.name')
            ->first();
        $id=$phieu_de_nghi->id;
        $chi_tiet=DB::table('de_nghi_cap_linh_kien_chi_tiets')
            ->join('linh_kiens', 'de_nghi_cap_linh_kien_chi_tiets.linh_kien_id', '=', 'linh_kiens.id')
            ->where('phieu_de_nghi_id',$id)
            ->select('de_nghi_cap_linh_kien_chi_tiets.*', 'linh_kiens.ten', 'linh_kiens.gia_ban', 'linh_kiens.ma')

            ->get();
        $phieu_de_nghi->chi_tiet_de_nghi = $chi_tiet;
        return Response::json($phieu_de_nghi, 200);


    }

    public function deletePDN(Request $request){
        $this->validate($request, [
            'id' => 'required'
        ]);
        $id= $request->get('id');
        $pdn = DB::table('phieu_de_nghi_cap_linh_kiens')->where(array('id'=>$id))->first();

        if ($pdn) {
            try {
                DB::table('de_nghi_cap_linh_kien_chi_tiets')->where('phieu_de_nghi_id', '=', $id)->delete();
                DB::table('phieu_de_nghi_cap_linh_kiens')->where('id', '=', $id)->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 200);
            }
        } else {
            return response()->json('Not found', 404);
        }
    }

}
