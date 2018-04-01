<?php

namespace App\Http\Controllers\Api;

use App\ChungTuKhoTot;
use App\ChungTuKhoXac;
use App\NhapXuatTot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Log;
use App\PhieuDeNghiCapLinhKien;

class ChungTuController extends Controller
{
    private function updateNhapXuatTable($tableName, $gap, $khoId, $linhKienId, $ngayCt, $id){
        DB::select(DB::raw('update '.$tableName.' set ton_dau_ky=ton_dau_ky+' .$gap . ', ton_cuoi_ky=ton_cuoi_ky+' .
            $gap .' where kho_id=' . $khoId . ' and linh_kien_id =' . $linhKienId .' and (ngay_ct >\''.$ngayCt .
            '\' OR (ngay_ct=\''.$ngayCt.'\' and id >'.$id.'))'));
    }

    private function updateTonKho($tableName, $soLuong, $khoId, $linhKienId, $loaiGiaoDich, $xuatBaoHanh){
        $query = null;
        if ($loaiGiaoDich === \Config::get('constant.loai_giao_dich.nhap_kho')){
            $query = 'INSERT INTO '.$tableName.' (nhap_kho,ton_cuoi,kho_id,linh_kien_id)
                            VALUES ('.$soLuong.','.$soLuong.','.$khoId.','.$linhKienId.')
                            ON DUPLICATE KEY UPDATE 
                              nhap_kho=nhap_kho + '.$soLuong.
                ', ton_cuoi = ton_cuoi + '.$soLuong;
        }else{
            if ($xuatBaoHanh){
                $query = 'UPDATE '. $tableName . ' set xuat_bh=xuat_bh+'.$soLuong.', ton_cuoi=ton_cuoi-'.$soLuong.
                    ' where kho_id ='.$khoId.' and linh_kien_id='.$linhKienId;
            }else{
                $query = 'UPDATE '. $tableName . ' set xuat_ngoai_bh=xuat_ngoai_bh+'.$soLuong.', ton_cuoi=ton_cuoi-'.$soLuong.
                    ' where kho_id ='.$khoId.' and linh_kien_id='.$linhKienId;
            }
        }
        DB::select(DB::raw($query));
    }

    private function updateTonKhoKhiXoaCT($tableName, $soLuong, $khoId, $linhKienId, $loaiGiaoDich, $isXuatBH){
        $query = null;
        if ($loaiGiaoDich === \Config::get('constant.loai_giao_dich.nhap_kho')){
            $query = 'UPDATE '. $tableName . ' set nhap_kho=nhap_kho-'.$soLuong.', ton_cuoi=ton_cuoi-'.$soLuong.'
                 where kho_id ='.$khoId.' and linh_kien_id='.$linhKienId;
        }else {
            if ($isXuatBH)
                $query = 'UPDATE '. $tableName . ' set xuat_bh=xuat_bh-'.$soLuong.', ton_cuoi=ton_cuoi+'.$soLuong.'
                    where kho_id ='.$khoId.' and linh_kien_id='.$linhKienId;
            else
                $query = 'UPDATE '. $tableName . ' set xuat_ngoai_bh=xuat_ngoai_bh-'.$soLuong.', ton_cuoi=ton_cuoi+'.$soLuong.'
                    where kho_id ='.$khoId.' and linh_kien_id='.$linhKienId;
        }
        DB::select(DB::raw($query));
    }

    public function getTonKho(Request $request)
    {
        $khoId = $request->get('kho_id');
        $linhKienId = $request->get('linh_kien_id');
        $ngayCt = $request->get('ngay_ct');

        $tonKhos = DB::select(DB::raw('select * from nhap_xuat_tots where kho_id = ' . $khoId .
            ' AND linh_kien_id = ' . $linhKienId . ' AND ngay_ct <=\'' . $ngayCt . '\' order by id desc'));
        if ($tonKhos && sizeof($tonKhos) > 0) {
             $tonKho = $tonKhos[0];
        } else {
            $tonKho = [];
            $tonKho['ton_cuoi'] = 0;
        }
        return response()->json($tonKho, 200);
    }

    public function createNewRowInXuatNhapTable($linhKien,$loaiGiaoDich, $isKhoTot){
        $xuatNhapTable = $isKhoTot? 'nhap_xuat_tots' : 'nhap_xuat_xacs';
        $tonKhoTable = $isKhoTot? 'ton_kho_tots' : 'ton_kho_xacs';

        $linhKien['loai_giao_dich'] = $loaiGiaoDich;

        $tonKhos = DB::select(DB::raw('select * from '.$xuatNhapTable.' where kho_id = '.$linhKien['kho_id'].
            ' AND linh_kien_id = '.$linhKien['linh_kien_id'].
            ' AND ngay_ct <=\''. $linhKien['ngay_ct']. '\' order by id desc limit 1'));
         $thucNhap =  $loaiGiaoDich * $linhKien['so_luong_thuc'];
        if ($tonKhos && sizeof($tonKhos) > 0){
            $tonKho = $tonKhos[0];
            $linhKien['ton_dau_ky'] = $tonKho->ton_cuoi_ky;
            $linhKien['ton_cuoi_ky'] = $tonKho->ton_cuoi_ky + $thucNhap;
        }else{
            $linhKien['ton_dau_ky'] = 0;
            $linhKien['ton_cuoi_ky'] =  $thucNhap;
        }

        if ($loaiGiaoDich == \Config::get('constant.loai_giao_dich.xuat_kho')
                        && $linhKien['ton_cuoi_ky'] < 0) {
            //TODO: throw exception 
        }
        //create new record in nhap_xuat_tons table
        $linhKienId = DB::table($xuatNhapTable)->insertGetId($linhKien);
        //update ton_dau ton_cuoi of other record following by ngay_ct
        $this->updateNhapXuatTable($xuatNhapTable,$thucNhap,$linhKien['kho_id'],$linhKien['linh_kien_id'],$linhKien['ngay_ct'],$linhKienId);
        //update ton_khos table
        $this->updateTonKho($tonKhoTable, $linhKien['so_luong_thuc'], $linhKien['kho_id'], $linhKien['linh_kien_id'], $linhKien['loai_giao_dich'],false);
    }

    private function taoCTKhoTot($data, $user, $isTraLinhKien) {
        $linhKiens = $data['linh_kiens'];
        unset($data['linh_kiens']);
        $now = Carbon::now()->toDateTimeString();
        $data['created_at'] = $now;
        $data['updated_at'] = $now;
        $data['nguoi_tao_id'] = $user->id;
        $data['trang_thai'] = 1;
        unset($data['xac_nhan_nhan_hang']);
        if ($data['loai_ct'] == 2){
            $loaiGiaoDich = -1;
        }else if ($data['loai_ct'] == 1){
            $loaiGiaoDich = 1;
        }else {
            $loaiGiaoDich = 0;
        }
        $chungTuId = null;
        $listLKId = [];
        $linhKien = null;

        foreach ($linhKiens as $key => $linhKien) {
            array_push($listLKId,$linhKien['linh_kien_id']);
        }

        try{
            DB::beginTransaction();
            $khoId = ($loaiGiaoDich == 1)?  $data['kho_nhap_id'] :  $data['kho_xuat_id'];
            DB::table('ton_kho_tots')->where('kho_id','=', $khoId) ->whereIn('linh_kien_id',$listLKId)->lockForUpdate();
            $data['id'] = DB::table('chung_tu_kho_tots')->insertGetId($data);
            $chungTuId = $data['id'];

            $this->themMoiLinhKiens($linhKiens, $data['id'], $data, $loaiGiaoDich, true);

            //if this chungtu belongs phieu sua chua then add no_linh_kien
            if (array_key_exists('phieu_sua_chua_id',$data) && $data['phieu_sua_chua_id']
            && array_key_exists('phieu_de_nghi_id',$data) && $data['phieu_de_nghi_id']){
                //incase phieu xuat kho => add linh kien no
                if ($data['loai_ct'] == \Config::get('constant.loai_chung_tu.xuat_kho')){
                    $this->addNoLinhKien($data, $linhKiens, $user->id);
                    DB::select(DB::raw('update phieu_de_nghi_cap_linh_kiens set trang_thai='.
                        \Config::get('constant.trang_thai_dncvt.da_cap_phat').
                        ' where id='.$data['phieu_de_nghi_id']));
                    //TODO: finish cap phat linh kien
                    $this->capNhatPhieuSuaChuaLinhKienTable($linhKiens,  $data['phieu_sua_chua_id']);

                    // update request trang thai công việc
                    DB::select(DB::raw('update requests set trang_thai='.
                        \Config::get('constant.trang_thai_dncvt.da_cap_phat').
                        ' where doi_tuong=1 and doi_tuong_id='.$data['phieu_de_nghi_id']));

                    // update request hoan thanh công việc cap linh kien cua ke toan
                    DB::select(DB::raw('update requests set hoan_thanh= 1, user_log ='.$data['nguoi_tao_id'].',ben_nhan_la_nhom=0,ben_nhan_id='.$data['nguoi_tao_id'].' where loai_cong_viec=5 and doi_tuong=1 and doi_tuong_id='.$data['phieu_de_nghi_id']));


                    // update requset hoan thanh cong viec cua cskh

                    DB::select(DB::raw('update requests set hoan_thanh= 1 where loai_cong_viec=4 and doi_tuong=1 and doi_tuong_id='.$data['phieu_de_nghi_id']));

                    // update phieu_sua_chuas tro ve trang thai dang sua chua
                    DB::select(DB::raw('update phieu_sua_chuas set status= 4 where id='.$data['phieu_sua_chua_id']));
                    DB::select(DB::raw('update requests set trang_thai= 4 where doi_tuong = 2 and doi_tuong_id='.$data['phieu_sua_chua_id']));



                } else { //incase phieu chuyen kho => update trang thai of phieu_de_nghi_cap_linh_kiens
                    DB::select(DB::raw('update phieu_de_nghi_cap_linh_kiens set trang_thai = '.
                        \Config::get('constant.trang_thai_dncvt.trung_tam_chuyen_kho').', phieu_chuyen_kho_id='.$data['id']
                        .' where id='.$data['phieu_de_nghi_id']));
                }
            }else if (array_key_exists('phieu_de_nghi_id',$data) && $data['phieu_de_nghi_id']) {
                DB::select(DB::raw('update phieu_de_nghi_cap_linh_kiens set trang_thai='.
                    \Config::get('constant.trang_thai_dncvt.da_cap_phat').
                    ' where id='.$data['phieu_de_nghi_id']));
            }


            if ($isTraLinhKien){
                $this->traLinhKienThua($linhKiens,$data['phieu_sua_chua_id']);
            }
            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
        return $chungTuId;
    }

    private function taoCTKhoXac($data, $user) {
        $linhKiens = $data['linh_kiens'];
        unset($data['linh_kiens']);
        $now = Carbon::now()->toDateTimeString();
        $data['created_at'] = $now;
        $data['updated_at'] = $now;
        $data['nguoi_tao_id'] = $user->id;
        $data['trang_thai'] = 1;

        if ($data['loai_ct'] == 2){
            $loaiGiaoDich = -1;
        }else if ($data['loai_ct'] == 1){
            $loaiGiaoDich = 1;
        }else {
            $loaiGiaoDich = 0;
        }
        $chungTuId = null;
        $listLKId = [];
        $linhKien = null;

        foreach ($linhKiens as $key => $linhKien) {
            array_push($listLKId,$linhKien['linh_kien_id']);
        }

        try{
            DB::beginTransaction();
            $khoId = ($loaiGiaoDich == 1)?  $data['kho_nhap_id'] :  $data['kho_xuat_id'];
            DB::table('ton_kho_xacs')->where('kho_id','=', $khoId) 
                ->whereIn('linh_kien_id',$listLKId)->lockForUpdate();
            $data['id'] = DB::table('chung_tu_kho_xacs')->insertGetId($data);
            $chungTuId = $data['id'];

            $noItemIds = '';
            foreach ($linhKiens as $key => $linhKien) {
               if (strlen($noItemIds) == 0){
                   $noItemIds = $linhKien['no_id'];
               }else {
                   $noItemIds = $noItemIds . ','. $linhKien['no_id'];
               }
            }

            $this->themMoiLinhKiens($linhKiens, $data['id'], $data, $loaiGiaoDich, false);

            DB::select(DB::raw('update no_linh_kien_xacs set trang_thai = '
                .\Config::get('constant.trang_thai_tra_xac.da_nhap_kho').' where id in ('.$noItemIds.')'));
            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
        return $chungTuId;
    }

    public function taoPhieuTraLinhKien(Request $request){
        $data = $request->get('data');
        $user = $request->get('user');
        $now = Carbon::now()->toDateTimeString();
        $linhKiens = $data['linh_kiens'];
        $data['loai_ct'] =\Config::get('constant.loai_chung_tu.nhap_kho');
        $data['ngay_ct'] = $now;
        $phieuNhapKho['cong_ty_id'] = $data['cong_ty_id'];

        $phieuNhapKho['phieu_sua_chua_id'] = $data['phieu_sua_chua_id'];
        $phieuNhapKho['loai_ct'] = \Config::get('constant.loai_chung_tu.nhap_kho');
        $phieuNhapKho['ngay_ct'] = $now;
        $phieuNhapKho['kho_nhap_id'] = $data['kho_nhap_id'];
        $phieuNhapKho['tao_tu_dong'] = true;
        $xuatLKs = [];
        $lk = [];
        foreach ($linhKiens as $key => $linhKien) {
            $lk['linh_kien_id'] = $linhKien['linh_kien_id'];
            $lk['so_luong_yc'] = $linhKien['so_luong_cap'];
            $lk['so_luong_thuc'] = $linhKien['so_luong_thuc'];
            $lk['don_gia'] = $linhKien['don_gia'];
            $lk['kho_id'] = $linhKien['kho_id'];
            $lk['loai_ct'] = $data['loai_ct'];

            array_push($xuatLKs,$lk);
        }

        $phieuNhapKho['linh_kiens'] = $xuatLKs;
        $chungTuId = $this->taoCTKhoTot($phieuNhapKho,$user,true);
        if($chungTuId)
        {
            $list = DB::table('psc_lks')
                ->join('linh_kiens', 'psc_lks.linh_kien_id', '=', 'linh_kiens.id')
                ->where('phieu_sua_chua_id',$data['phieu_sua_chua_id'])
                ->select('psc_lks.*', 'linh_kiens.ma', 'linh_kiens.ten')
                ->get();
            if($list[0]->so_luong_tra > 0)
            {
                $data['check_tra'] =true;

            }
            else{
                $data['check_tra'] =false;

            }
            $phieu_de_nghi_cap_linh_kien= DB::table('phieu_de_nghi_cap_linh_kiens')->where(array('phieu_sua_chua_id'=>$data['phieu_sua_chua_id']))->first();

            $data['data'] =$list;
            $data['kho_tram_id'] = $phieu_de_nghi_cap_linh_kien->kho_tram_id;
            $data['cong_ty_id'] = $phieu_de_nghi_cap_linh_kien->cong_ty_id;
            return response()->json($data, 200);

        }
        else{
            return response()->json('Error', 500);

        }
    }


    private function addNoLinhKien($chungTuKhoTot, $linhKiens, $userId){
        $now = Carbon::now()->toDateTimeString();
        /*$khoIds = DB::select('select * from khos where loai_kho='.\Config::get('constant.loai_kho.xac').
            ' and tram_bao_hanh_id in  (select tram_bao_hanh_id from homestead.khos where id = '.$chungTuKhoTot['kho_nhap_id'].')');
        $khoId = $khoIds[0]->id;*/
        $tramIds = DB::select('select tram_bao_hanh_id from khos where id = '.$chungTuKhoTot['kho_xuat_id']);
        $tramId = $tramIds[0]->tram_bao_hanh_id;
        $dataSet = [];
        foreach ($linhKiens as $key => $linhKien){
            $dataSet[] = [
                'nhan_vien_id' => $userId,
                'tram_bao_hanh_id'=> $tramId,
                'phieu_sua_chua_id' => $chungTuKhoTot['phieu_sua_chua_id'],
                'chung_tu_id' => $chungTuKhoTot['id'],
                'so_luong_cap'  => $linhKien['so_luong_thuc'],
                'linh_kien_cap_id'    =>  $linhKien['linh_kien_id'],
                'so_luong_thu'       => 0,
                'linh_kien_thu_hoi_id' => $linhKien['linh_kien_id'],
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        DB::table('no_linh_kien_xacs')->insert($dataSet);
    }

    public function createCTKhoTot(Request $request)
    {
        $user = $request->get('user');
        $data = $request->get('data');
        $chungTuId = $this->taoCTKhoTot($data,$user,false);
        return response()->json($chungTuId, 200);
    }

    public function createCTKhoXac(Request $request)
    {
        $user = $request->get('user');
        $data = $request->get('data');
        $chungTuId = $this->taoCTKhoXac($data,$user);
        return response()->json($chungTuId, 200);
    }


    public function taoXuatKhoTram(Request $request) {
        $user = $request->get('user');
        $deNghiId = $request->get('phieu_de_nghi_id');
        $chungTuId = $this->taoPhieuXuatKho($deNghiId, $user);
        return response()->json($chungTuId, 200);
    }

    private function taoPhieuXuatKho($deNghiId, $user) {
        $now = Carbon::now()->toDateTimeString();
        $deNghi = PhieuDeNghiCapLinhKien::find($deNghiId);
        if ($deNghi){
            Log::info($deNghi->phieu_sua_chua_id);
            $linhKiens = DB::table('de_nghi_cap_linh_kien_chi_tiets')
                ->where('phieu_de_nghi_id','=', $deNghiId)->get();
            $phieuXuatKho = [];
            $phieuXuatKho['cong_ty_id'] = $deNghi['cong_ty_id'];
            $phieuXuatKho['phieu_sua_chua_id'] = $deNghi->phieu_sua_chua_id;
            $phieuXuatKho['phieu_de_nghi_id'] = $deNghiId;
            $phieuXuatKho['loai_ct'] = \Config::get('constant.loai_chung_tu.xuat_kho');
            $phieuXuatKho['ngay_ct'] = $now;
            //$phieuNhapKho['serial_id'] = $now;
            //$phieuNhapKho['serial_id'] = $now;
            //$phieuNhapKho['model_id'] = $now;
            $phieuXuatKho['kho_xuat_id'] = $deNghi->kho_tram_id;
            $phieuXuatKho['tao_tu_dong'] = true;

            $xuatLKs = [];
            $lk = [];
            foreach ($linhKiens as $key => $linhKien) {
                $lk['linh_kien_id'] = $linhKien->linh_kien_id;
                $lk['so_luong_yc'] = $linhKien->so_luong;
                $lk['so_luong_thuc'] = $linhKien->so_luong;
                $lk['don_gia'] = $linhKien->don_gia;
                $lk['kho_id'] = $deNghi->kho_tram_id;
                $lk['loai_ct'] = $phieuXuatKho['loai_ct'];
                array_push($xuatLKs,$lk);
            }

            $phieuXuatKho['linh_kiens'] = $xuatLKs;

            return $this->taoCTKhoTot($phieuXuatKho,$user,false);
        }else {
            //throw exception
        }
    }

    private function taoPhieuXuatKhoByHoaDonChuyen($hoaDonChuyenId, $user, $linhKiens) {
        $now = Carbon::now()->toDateTimeString();
        $hoaDon = ChungTuKhoTot::find($hoaDonChuyenId);
        $phieuNhapKho = [];
        $phieuNhapKho['phieu_sua_chua_id'] = $hoaDon['phieu_sua_chua_id'];
        $phieuNhapKho['phieu_de_nghi_id'] = $hoaDon['phieu_de_nghi_id'];
        $phieuNhapKho['loai_ct'] = \Config::get('constant.loai_chung_tu.xuat_kho');
        $phieuNhapKho['ngay_ct'] = $now;
        $phieuNhapKho['cong_ty_id'] = $hoaDon->cong_ty_id;
        //$phieuNhapKho['serial_id'] = $now;
        //$phieuNhapKho['model_id'] = $now;
        $phieuNhapKho['kho_xuat_id'] = $hoaDon['kho_nhap_id'];
        $phieuNhapKho['tao_tu_dong'] = true;

        $xuatLKs = [];
        $lk = [];
        foreach ($linhKiens as $key => $linhKien) {
            $lk['linh_kien_id'] = $linhKien['linh_kien_id'];
            $lk['so_luong_yc'] = $linhKien['so_luong_yc'];
            $lk['so_luong_thuc'] = $linhKien['so_luong_nhan'];
            $lk['don_gia'] = $linhKien['don_gia'];
            $lk['kho_id'] = $phieuNhapKho['kho_xuat_id'];
            $lk['loai_ct'] =  $phieuNhapKho['loai_ct'];

            array_push($xuatLKs,$lk);
        }

        $phieuNhapKho['linh_kiens'] = $xuatLKs;

        $this->taoCTKhoTot($phieuNhapKho, $user,false);
    }

    private function themMoiLinhKiens($linhKiens, $chungTuId, $data, $loaiGiaoDich, $isKhoTot){
        if (sizeof($linhKiens) > 0) {
            foreach ($linhKiens as $key => $linhKien) {
                unset($linhKien['no_id']);
                $linhKien['ngay_ct'] = $data['ngay_ct'];
                $linhKien['chung_tu_id'] = $chungTuId;

                //for phieu nhap
                if ($loaiGiaoDich == 1) {
                    $linhKien['kho_id'] = $data['kho_nhap_id'];
                    $this->createNewRowInXuatNhapTable($linhKien, 1, $isKhoTot);
                } //for phieu xuat
                else if ($loaiGiaoDich == -1) {
                    $linhKien['kho_id'] = $data['kho_xuat_id'];
                    $this->createNewRowInXuatNhapTable($linhKien, -1, $isKhoTot);
                } //for phieu chuyen kho
                else if ($loaiGiaoDich == 0) {
                    $linhKien['kho_id'] = $data['kho_xuat_id'];
                    $this->createNewRowInXuatNhapTable($linhKien, -1, $isKhoTot);

                    $linhKien['kho_id'] = $data['kho_nhap_id'];
                    $linhKien['so_luong_thuc'] = 0;
                    $this->createNewRowInXuatNhapTable($linhKien, 1, $isKhoTot);
                }
            }
        }
    }

    private function xoaLinhKiens($deletedlinhKiens, $isXuatBH){
        if (sizeof($deletedlinhKiens) > 0){
            $listLKId = [];
            foreach ($deletedlinhKiens as $key => $linhKien) {
                array_push($listLKId, $linhKien->linh_kien_id);
                $this->updateNhapXuatTable('nhap_xuat_tots', -$linhKien->so_luong_thuc,
                    $linhKien->kho_id, $linhKien->linh_kien_id, $linhKien->ngay_ct, $linhKien->id);
                $this->updateTonKhoKhiXoaCT('ton_kho_tots', $linhKien->so_luong_thuc,
                    $linhKien->kho_id, $linhKien->linh_kien_id, $linhKien->loai_giao_dich, $isXuatBH);
            }
            DB::select(DB::raw('delete from nhap_xuat_tots where id IN '.$listLKId));
        }
    }


    public function deleteCTKhoTot(Request $request){
        $user = $request->get('user');
        $chungTuId = $request->get('chung_tu_id');
        $khoNhap = $request->get('kho_nhap_id');
        $khoXuat = $request->get('kho_xuat_id');
        $phieuSuaChuaId =  $request->get('phieu_sua_chua_id');
        $isXuatBH = $phieuSuaChuaId != null? true:false;
        $deletedlinhKiens = DB::select(DB::raw('select * from nhap_xuat_tots where chung_tu_id=' . $chungTuId));
        $linhKien = null;
        $listLKId = [];
        foreach ($deletedlinhKiens as $key => $linhKien){
           array_push($listLKId, $linhKien->linh_kien_id);
        }
        try{
            DB::beginTransaction();
            //lock the ton_kho_tots so other transaction relating to this record will be delayed until this transaction is done
            //so there is no rare condition happen
            if ($khoNhap)
                DB::table('ton_kho_tots')->where('kho_id','=', $khoNhap) ->whereIn('linh_kien_id',$listLKId)->lockForUpdate();
            if ($khoXuat)
                DB::table('ton_kho_tots')->where('kho_id','=', $khoXuat) ->whereIn('linh_kien_id',$listLKId)->lockForUpdate();

            $this->xoaLinhKiens($deletedlinhKiens, $isXuatBH, $listLKId);
            //update to know who already deleted this document
            DB::select(DB::raw('update chung_tu_kho_tots set nguoi_xoa_id ='.$user->id));
            DB::select(DB::raw('delete from chung_tu_kho_tots where id='.$chungTuId));
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
        return response()->json($chungTuId, 200);
    }

    public function updateCTKhoTot(Request $request){
        $user = $request->get('user');
        $data = $request->get('data');
        $linhKiens = $data['linh_kiens'];
        $chungTuId = $data['id'];
        $isXacNhanNhanHang = $data['xac_nhan_nhan_hang'];
        unset($data['linh_kiens']);
        unset($data['xac_nhan_nhan_hang']);
        $now = Carbon::now()->toDateTimeString();
        $data['updated_at'] = $now;
        $data['nguoi_sua_id'] = $user->id;
        $data['trang_thai'] = 1;
        $khoNhap = $request->get('kho_nhap_id');
        $khoXuat = $request->get('kho_xuat_id');

        if ($isXacNhanNhanHang){
            $data['ngay_nhan'] = $now;
        }
        try{
            $prevLinhKiens = DB::select(DB::raw('select * from nhap_xuat_tots where chung_tu_id=' . $chungTuId));
            $linhKien = null;
            $listLKId = [];
            foreach ($prevLinhKiens as $key => $linhKien){
                array_push($listLKId, $linhKien->linh_kien_id);
            }
            foreach ($linhKiens as $key => $linhKien){
                array_push($listLKId, $linhKien['linh_kien_id']);
            }
            DB::beginTransaction();
            //lock the ton_kho_tots so other transaction relating to this record will be delayed until this transaction is done
            //so there is no rare condition happen
            if ($khoNhap)
                DB::table('ton_kho_tots')->where('kho_id','=', $khoNhap) ->whereIn('linh_kien_id',$listLKId)->lockForUpdate();
            if ($khoXuat)
                DB::table('ton_kho_tots')->where('kho_id','=', $khoXuat) ->whereIn('linh_kien_id',$listLKId)->lockForUpdate();

            $this->updateLinhKien($linhKiens,$chungTuId,$data,true);
            DB::table('chung_tu_kho_tots')->where('id', $chungTuId)->update($data);

            //incase chuyen kho click "xac nhan nhan hang" feature
            if ($isXacNhanNhanHang){
                if (array_key_exists('phieu_sua_chua_id',$data) && $data['phieu_sua_chua_id']){
                   $this->taoPhieuXuatKhoByHoaDonChuyen($data['id'],  $user, $linhKiens);
                   $this->capNhatPhieuSuaChuaLinhKienTable($linhKiens, $data['phieu_sua_chua_id']);
                }else if (array_key_exists('phieu_de_nghi_id',$data) && $data['phieu_de_nghi_id']){
                    DB::select(DB::raw('update phieu_de_nghi_cap_linh_kiens set trang_thai = '.
                        \Config::get('constant.trang_thai_dncvt.da_cap_phat').
                        ', phieu_chuyen_kho_id='.$data['id'].
                        ' where id='.$data['phieu_de_nghi_id']));
                }
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
        return response()->json($chungTuId, 200);
    }

    private function capNhatPhieuSuaChuaLinhKienTable($linhKiens, $phieuSuaChuaId) {
        foreach ($linhKiens as $key => $linhKien){
            $query = 'INSERT INTO psc_lks (phieu_sua_chua_id,linh_kien_id,so_luong_cap,don_gia)
                            VALUES ('.$phieuSuaChuaId.','.$linhKien['linh_kien_id'].','.$linhKien['so_luong_thuc'].','.$linhKien['don_gia'].')
                            ON DUPLICATE KEY UPDATE 
                              so_luong_cap=so_luong_cap + '.$linhKien['so_luong_thuc'];
            DB::select(DB::raw($query));
        }
    }

    private function traLinhKienThua($linhKiens, $phieuSuaChuaId) {
        foreach ($linhKiens as $key => $linhKien){
            $query = 'UPDATE psc_lks set  
                              so_luong_tra='.$linhKien['so_luong_thuc'].' where linh_kien_id='.$linhKien['linh_kien_id'].' AND 
                              phieu_sua_chua_id='.$phieuSuaChuaId;
            DB::select(DB::raw($query));
        }
    }

    private function modifyLinhKien($linhKienUpdate,$loaiGiaoDich, $data,$isXuatBH){
        foreach ($linhKienUpdate as $key => $linhKien) {
            $oldLinhKien = NhapXuatTot::find($linhKien['id']);
            $soLuongGap = ($linhKien['so_luong_thuc'] - $oldLinhKien->so_luong_thuc);
            if ($oldLinhKien->so_luong_thuc != $linhKien['so_luong_thuc']){
                $this->updateNhapXuatTable('nhap_xuat_tots',$soLuongGap*$oldLinhKien->loai_giao_dich,
                    $linhKien['kho_id'],$linhKien['linh_kien_id'],$data['ngay_ct'],$linhKien['id']);
                $this->updateTonKho('ton_kho_tots',$soLuongGap,$linhKien['kho_id'],$linhKien['linh_kien_id']);
            }
            DB::select(DB::raw('update nhap_xuat_tots set so_luong_thuc ='.$linhKien['so_luong_thuc'].
                ', ghi_chu=\''.$linhKien['ghi_chu'].
            '\' , so_luong_nhan ='.$linhKien['so_luong_nhan'].' where id = '.$linhKien['id']));

            if ($loaiGiaoDich == 0){//chuyen kho
                //incase chuyen kho, update data in kho nhap
                $oldLinhKien = DB::table('nhap_xuat_tots')
                    ->where('linh_kien_id','=', $linhKien['linh_kien_id'])
                    ->where('chung_tu_id','=', $data['id'])
                    ->where('loai_giao_dich',1)
                    ->get()->first();
                $oldLinhKien->so_luong_thuc = $oldLinhKien->so_luong_thuc? $oldLinhKien->so_luong_thuc : 0;
                //so_luong_thuc = so_luong_nhan
                $soLuongGap = ($linhKien['so_luong_nhan'] - $oldLinhKien->so_luong_thuc);
                if ($oldLinhKien->so_luong_thuc != $linhKien['so_luong_nhan']){
                    $this->updateNhapXuatTable('nhap_xuat_tots',$soLuongGap*$oldLinhKien->loai_giao_dich,
                        $oldLinhKien->kho_id, $linhKien['linh_kien_id'],$data['ngay_ct'],$linhKien['id']);
                    $this->updateTonKho('ton_kho_tots', $soLuongGap, $oldLinhKien->kho_id,
                        $linhKien['linh_kien_id'],$oldLinhKien->loai_giao_dich,$isXuatBH);
                }
            }
        }
    }

    private function updateLinhKien($newLinhKiens, $chungTuId, $data, $isKhoTot){
        if ($data['loai_ct'] == 2){
            $loaiGiaoDich = -1;
        }else if ($data['loai_ct'] == 1){
            $loaiGiaoDich = 1;
        }else {
            $loaiGiaoDich = 0;
        }

        $linhKien = null;
        //$oldLinhKien = null;
        $phieuSuaChuaId =  $data['phieu_sua_chua_id'];
        $isXuatBH = $phieuSuaChuaId != null? true:false;

        if ($newLinhKiens && sizeof($newLinhKiens) > 0) {
            $linhKienUpdate = [];
            $linhKienUpdateId = [];
            $linhKienMoi = [];

            foreach ($newLinhKiens as $key => $linhKien) {
                if (array_key_exists('id', $linhKien) && $linhKien['id'] != null) {
                    array_push($linhKienUpdateId, $linhKien['linh_kien_id']);
                    array_push($linhKienUpdate, $linhKien);
                }else{
                    array_push($linhKienMoi, $linhKien);
                }
            }

            //deleted items
            $deletedlinhKiens = DB::select(DB::raw('select * from nhap_xuat_tots where chung_tu_id='.
                $chungTuId . ' and linh_kien_id not in (' . implode(',',$linhKienUpdateId) .')'));
            $this->xoaLinhKiens($deletedlinhKiens,$isXuatBH);

            //new items
            $this->themMoiLinhKiens($linhKienMoi,$chungTuId,$data, $loaiGiaoDich, $isKhoTot);

            //updated items
            $this->modifyLinhKien($linhKienUpdate, $loaiGiaoDich, $data, $isXuatBH);
        }
    }

    public function getChungTuKhoTot(Request $request)
    {
        $isKhoTot = $request->get('is_kho_tot');
        $chungTuId = $request->get('chung_tu_id');
        $chungTu = null;
        if ($isKhoTot){
            $chungTu = ChungTuKhoTot::with([
                'Congty' => function ($query) {
                    $query->get();
                },
                'loai_chung_tu' => function ($query) {
                    $query->get();
                },
                'tk_no' => function ($query) {
                    $query->get();
                },
                'tk_co' => function ($query) {
                    $query->get();
                },
                'doi_tuong_no' => function ($query) {
                    $query->get();
                },
                'doi_tuong_co' => function ($query) {
                    $query->get();
                },
                'kho_nhap' => function ($query) {
                    $query->get();
                },
                'kho_xuat' => function ($query) {
                    $query->get();
                },
                'don_dat_hang' => function ($query) {
                    $query->get();
                },
                'phieu_sua_chua' => function ($query) {
                    $query->get();
                },
                'linh_kiens' => function ($query) {
                    $query->with(['linh_kien'=>function($query){$query->get();}]);
                },
                'nguoi_tao' => function ($query) {
                    $query->get();
                }/*,
            'nguoi_sua' => function ($query) {
                $query->get();
            }*/
            ])->where('chung_tu_kho_tots.id', $chungTuId)->first();
        }else {
            $chungTu = ChungTuKhoXac::with([
                'Congty' => function ($query) {
                    $query->get();
                },
                'loai_chung_tu' => function ($query) {
                    $query->get();
                },
                'tk_no' => function ($query) {
                    $query->get();
                },
                'tk_co' => function ($query) {
                    $query->get();
                },
                'doi_tuong_no' => function ($query) {
                    $query->get();
                },
                'doi_tuong_co' => function ($query) {
                    $query->get();
                },
                'kho_nhap' => function ($query) {
                    $query->get();
                },
                'kho_xuat' => function ($query) {
                    $query->get();
                },
                'linh_kiens' => function ($query) {
                    $query->with(['linh_kien'=>function($query){$query->get();}]);
                },
                'nguoi_tao' => function ($query) {
                    $query->get();
                }
            ])->where('chung_tu_kho_xas.id', $chungTuId)->first();
        }
        return response()->json($chungTu, 200);
    }

    public function  getListChungTuKhoTot(Request $request){
        $this->validate($request, [
            'loai_ct' => 'required',
            'kho_id' => 'required'
        ]);
        try {
            if ($request->get('loai_ct') === \Config::get('constant.loai_chung_tu.nhap_kho')) {
                //  chung tu nhap kho
                $chungTuKhoTot = ChungTuKhoTot::with('nguoi_tao','kho_nhap','kho_xuat')
                    ->where('loai_ct', $request->get('loai_ct'))
                    ->where('kho_nhap_id', $request->get('kho_id'))->orderBy('id', 'DESC')->paginate(15);
            } else if ($request->get('loai_ct')  === \Config::get('constant.loai_chung_tu.xuat_kho')) {
                //  chung tu xuat kho
                $chungTuKhoTot = ChungTuKhoTot::with('nguoi_tao','kho_nhap','kho_xuat')
                    ->where('loai_ct', $request->get('loai_ct'))
                    ->where('kho_xuat_id', $request->get('kho_id'))
                    ->orderBy('id', 'DESC')
                    ->paginate(15);
            } else {
                $khoId = $request->get('kho_id');
                $chungTuKhoTot = ChungTuKhoTot::with('nguoi_tao','kho_nhap','kho_xuat')
                    ->where('loai_ct', $request->get('loai_ct'))
                    ->where(function ($query) use ($khoId){
                        $query->where('kho_xuat_id', $khoId)
                            ->orWhere('kho_nhap_id', $khoId);
                    })->orderBy('id', 'DESC')->paginate(15);
            }
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($chungTuKhoTot, 200);
    }

    public function  filterListChungTuKhoTot(Request $request){
        $this->validate($request, [
            'loai_ct' => 'required',
            'kho_id' => 'required',
            'ngay_bat_dau' => 'required',
            'ngay_ket_thuc' => 'required'
        ]);
        try {
            if ($request->get('loai_ct') === \Config::get('constant.loai_chung_tu.nhap_kho')) {
                //  chung tu nhap kho
                $chungTuKhoTot = ChungTuKhoTot::with('nguoi_tao','kho_nhap','kho_xuat')
                    ->where('loai_ct', $request->get('loai_ct'))
                    ->where('kho_nhap_id', $request->get('kho_id'))
                    ->where('ngay_ct','>=',$request->get('ngay_bat_dau'))
                    ->where('ngay_ct','<=',$request->get('ngay_ket_thuc'))
                    ->orderBy('id', 'DESC')
                    ->paginate(15);
            } else if ($request->get('loai_ct')  === \Config::get('constant.loai_chung_tu.xuat_kho')) {
                //  chung tu xuat kho
                $chungTuKhoTot = ChungTuKhoTot::with('nguoi_tao','kho_nhap','kho_xuat')
                    ->where('loai_ct', $request->get('loai_ct'))
                    ->where('kho_xuat_id', $request->get('kho_id'))
                    ->where('ngay_ct','>=',$request->get('ngay_bat_dau'))
                    ->where('ngay_ct','<=',$request->get('ngay_ket_thuc'))
                    ->orderBy('id', 'DESC')
                    ->paginate(15);
            }else {
                $khoId = $request->get('kho_id');
                $chungTuKhoTot = ChungTuKhoTot::with('nguoi_tao','kho_nhap','kho_xuat')
                    ->where('loai_ct', $request->get('loai_ct'))
                    ->where(function ($query) use ($khoId){
                        $query->where('kho_xuat_id', $khoId)
                            ->orWhere('kho_nhap_id', $khoId);
                    })->where('ngay_ct','>=',$request->get('ngay_bat_dau'))
                    ->where('ngay_ct','<=',$request->get('ngay_ket_thuc'))
                    ->orderBy('id', 'DESC')
                    ->paginate(15);
            }
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($chungTuKhoTot, 200);
    }

}

