<?php

namespace App\Http\Controllers\Api;

use App\ChungTuKhoTot;
use App\CongTy;
use App\DeNghiCapLinhKienChiTiet;
use App\LoaiChungTu;
use App\NhapXuatTot;
use App\PhieuDeNghiCapLinhKien;
use App\PhieuSuaChua;
use App\ToChuc;
use App\TonKhoTot;
use App\Kho;
use App\TramBaoHanh;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Log;
use Response;
use Validator;

class DeNghiCapLinhKienController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->get('user');
        $data = $request->get('data');

        $linhKiens = $data['linh_kiens'];
        unset($data['linh_kiens']);
        $now = Carbon::now()->toDateTimeString();
        $data['created_at'] = $now;
        $data['updated_at'] = $now;
        $data['nguoi_tao_id'] = $user->id;
        $data['kho_trung_tam_id'] = $data['kho_tram_id'];
        $data['trang_thai'] = array_key_exists('phieu_sua_chua_id', $data)? \Config::get('constant.trang_thai_dncvt.de_nghi') : \Config::get('constant.trang_thai_dncvt.gui_trung_tam');
        $khoTram = Kho::find($data['kho_tram_id']);
        $khoTrungTam = DB::table('khos')->where('loai_kho','=',\Config::get('constant.loai_kho.tot'))
            ->where('trung_tam_bao_hanh_id','=',$khoTram->trung_tam_bao_hanh_id)
            ->whereNull('tram_bao_hanh_id')->first();
        Log::info($khoTrungTam->id);
        $data['kho_trung_tam_id'] = $khoTrungTam->id;
        $data['cong_ty_id'] = Kho::find($data['kho_tram_id'])->cong_ty_id;
        try{
            DB::beginTransaction();
            $chungTuId = DB::table('phieu_de_nghi_cap_linh_kiens')->insertGetId($data);
            $linhKien = null;
            foreach ($linhKiens as $key => $linhKien){
                $linhKien['phieu_de_nghi_id'] = $chungTuId;
                if ($data['trang_thai'] == \Config::get('constant.trang_thai_dncvt.gui_trung_tam')){
                    $linhKien['so_luong_gui_trung_tam'] = $linhKien['so_luong'];
                }
                DB::table('de_nghi_cap_linh_kien_chi_tiets')->insertGetId($linhKien);
            }

            $objectType = \Config::get('constant.request_doi_tuong.de_nghi_cap_vt');
            $objectTypeId = $chungTuId;

            if (array_key_exists('phieu_sua_chua_id', $data)){
                $receiverId = PhieuSuaChua::find($data['phieu_sua_chua_id'])->user_id;
                // Tạo công việc Yêu cầu kế toán cấp linh kiện

                $this->createRequests($user, $receiverId, false, null,
                    null, $objectType, $objectTypeId, 4, 1);

            }


            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
        return response()->json($chungTuId, 200);
    }

    public function delete(Request $request){
        $chungTuId = $request->get('id');
        try{
            DB::beginTransaction();
            DB::select(DB::raw('delete de_nghi_cap_linh_kien_chi_tiets where phieu_de_nghi_id='.$chungTuId));
            DB::select(DB::raw('delete phieu_de_nghi_cap_linh_kiens where id='.$chungTuId));
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
        return response()->json($chungTuId, 200);
    }

    public function update(Request $request){
        $data = $request->get('data');
        $linhKiens = $data['linh_kiens'];
        $chungTuId = $data['id'];
        unset($data['linh_kiens']);
        $now = Carbon::now()->toDateTimeString();
        $data['updated_at'] = $now;
        try{
            DB::beginTransaction();
            DB::select(DB::raw('delete from de_nghi_cap_linh_kien_chi_tiets where phieu_de_nghi_id='.$chungTuId));
            foreach ($linhKiens as $key => $linhKien){
                $linhKien['phieu_de_nghi_id'] = $chungTuId;
                unset($linhKien['id']);
                DB::table('de_nghi_cap_linh_kien_chi_tiets')->insertGetId($linhKien);
            }
            DB::table('phieu_de_nghi_cap_linh_kiens')->where('id', $chungTuId)->update($data);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
        return response()->json($chungTuId, 200);
    }

    public function changeStatus(Request $request) {
        try{
            $trang_thai = $request->get('trang_thai');
            $objectTypeId = $request->get('phieu_de_nghi_id');
            $phieuDeNghi = PhieuDeNghiCapLinhKien::find($objectTypeId);
            if ($phieuDeNghi){
                $objectType = \Config::get('constant.request_doi_tuong.de_nghi_cap_vt');
                $user = $request->get('user');
                $loai_nguoi_dungs = DB::table('loai_nguoi_dungs')->where('ten_loai','KETOAN')->first();
                $tramBH = Kho::find($phieuDeNghi->kho_tram_id);

                switch ($trang_thai){
                    case \Config::get('constant.trang_thai_dncvt.tu_choi'):
                        $isGroupReceiver = false;
                        $receiverId = $phieuDeNghi->nguoi_tao_id;
                        $receiverTramId = $tramBH->id;
                        $receiverTrungTamId = $tramBH->trung_tam_bao_hanh_id;
                        break;
                    case \Config::get('constant.trang_thai_dncvt.dong_y'):
                        $isGroupReceiver = true;
                        $receiverId =  $loai_nguoi_dungs->id;
                        $receiverTramId = $tramBH->id;
                        $receiverTrungTamId = $tramBH->trung_tam_bao_hanh_id;

                        break;
                    case \Config::get('constant.gui_trung_tam.gui_trung_tam'):
                        $isGroupReceiver = true;
                        $receiverId =  $loai_nguoi_dungs->id;
                        $receiverTrungTamId = TramBaoHanh::find($phieuDeNghi->kho_tram_id)->trung_tam_bao_hanh_id;
                        break;
                }
                DB::table('phieu_de_nghi_cap_linh_kiens')
                    ->where(array('id'=>$objectTypeId))
                    ->update(['trang_thai'=>$trang_thai]);
                $this->createRequests($user, $receiverId, $isGroupReceiver, $receiverTramId,
                    $receiverTrungTamId, $objectType, $objectTypeId, 5, $trang_thai);
                return response()->json($objectTypeId, 200);
            }
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 200);

        }
    }

    public function actionDNLK(Request $request) {
        try{
            $trang_thai = $request->get('trang_thai');
            $objectTypeId = $request->get('phieu_de_nghi_id');
            $request_id = $request->get('request_id');
            $user_id = $request->get('user_id');
            $ghi_chu = $request->get('ghi_chu');
            $receiverTramId = null;
            $receiverTrungTamId = null;
            $loai_cong_viec = $request->get('loai_cong_viec');
            $phieuDeNghi = PhieuDeNghiCapLinhKien::find($objectTypeId);
            if ($phieuDeNghi){
                $objectType = \Config::get('constant.request_doi_tuong.de_nghi_cap_vt');
                $loai_nguoi_dungs = DB::table('loai_nguoi_dungs')->where('ten_loai','KETOAN')->first();
                if($trang_thai == 1) {
                    $isGroupReceiver = false;
                    $receiverId = $phieuDeNghi->nguoi_tao_id;
                }
                else{
                    $isGroupReceiver = true;
                    $receiverId =  $loai_nguoi_dungs->id;
                }
                DB::table('phieu_de_nghi_cap_linh_kiens')
                    ->where(array('id'=>$objectTypeId))
                    ->update(['trang_thai'=>$trang_thai]);
                if($loai_cong_viec == 4){
                    $request_create['nguoi_gui_id'] = $user_id;
                    $request_create['ben_nhan_id'] = $receiverId;
                    $request_create['ben_nhan_la_nhom'] = $isGroupReceiver;
                    $request_create['doi_tuong'] = $objectType;
                    $request_create['doi_tuong_id'] = $objectTypeId;
                    $request_create['tram_bao_hanh_id'] = $receiverTramId;
                    $request_create['trung_tam_bao_hanh_id'] = $receiverTrungTamId;
                    $request_create['user_log']= $receiverId;
                    $request_create['ghi_chu'] = $ghi_chu;
                    $request_create['loai_cong_viec'] = 5;
                    $request_create['created_at']= date('Y-m-d H:i:s');
                    $request_create['updated_at']= date('Y-m-d H:i:s');
                    DB::table('requests')->insertGetId($request_create);
                    DB::table('requests')
                        ->where(array('loai_cong_viec'=>4,'doi_tuong_id'=>$objectTypeId,'doi_tuong'=>1))
                        ->update(['ghi_chu'=>$ghi_chu,'ben_nhan_id'=>$loai_nguoi_dungs->id,'ben_nhan_la_nhom'=>1,'user_log'=>$user_id,'hoan_thanh'=>1,'da_xu_ly'=>1]);

                    DB::table('requests')
                        ->where(array('doi_tuong'=>1 ,'doi_tuong_id'=>$objectTypeId))
                        ->update(['trang_thai'=>$trang_thai]);
                    $chi_tiets = DB::table('de_nghi_cap_linh_kien_chi_tiets')->where('phieu_de_nghi_id',$objectTypeId)->get();
                    if(count($chi_tiets) >0 )
                    {
                        foreach ($chi_tiets as $chi_tiet)
                        {
                            DB::table('de_nghi_cap_linh_kien_chi_tiets')
                                ->where(array('id'=>$chi_tiet->id))
                                ->update(['so_luong_gui_trung_tam'=>$chi_tiet->so_luong]);
                        }

                    }



                }


                $logCongViec = DB::table('requests')
                    ->join('users', 'requests.nguoi_gui_id', '=', 'users.id')
                    ->select('requests.*', 'users.name as ten_nguoi_tao')
                    ->where('requests.id',$request_id)
                    ->first();
                if ($logCongViec->ben_nhan_la_nhom == 1) {
                    $nhom_tai_khoan = \App\LoaiNguoiDung::find($logCongViec->ben_nhan_id);
                    $logCongViec->ben_nhan = $nhom_tai_khoan->ten_loai;

                } else {
                    $user_nhan = User::find($logCongViec->ben_nhan_id);
                    $logCongViec->ben_nhan = $user_nhan->name;

                }
                return response()->json($logCongViec, 200);

            }
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 200);

        }
    }


    public function createRequest($sender, $receiverId, $isGroupReceiver, $receiverTramId,
                                  $receiverTrungTamId, $objectType, $objectTypeId) {
        $request = [];
        $request['nguoi_gui_id'] = $sender->id;
        $request['ben_nhan_id'] = $receiverId;
        $request['ben_nhan_la_nhom'] = $isGroupReceiver;
        $request['doi_tuong'] = $objectType;
        $request['doi_tuong_id'] = $objectTypeId;
        $request['tram_bao_hanh_id'] = $receiverTramId;
        $request['trung_tam_bao_hanh_id'] = $receiverTrungTamId;
        $request['user_log']= $receiverId;
        $request['created_at']= date('Y-m-d H:i:s');
        $request['updated_at']= date('Y-m-d H:i:s');
        DB::table('requests')->insertGetId($request);
    }

    public function createRequests($sender, $receiverId, $isGroupReceiver, $receiverTramId,
                                   $receiverTrungTamId, $objectType, $objectTypeId, $job_type, $status) {
        if($job_type==4)
        {
            $loai_nguoi_dungs = DB::table('loai_nguoi_dungs')->where('ten_loai','CSKH')->first();

            // Công việc CSKH Yêu cầu kế toán cấp linh kiện
            $request = [];
            $request['nguoi_gui_id'] = $sender->id;
            $request['ben_nhan_id'] = $loai_nguoi_dungs->id;
            $request['ben_nhan_la_nhom'] = 1;
            $request['doi_tuong'] = $objectType;
            $request['doi_tuong_id'] = $objectTypeId;
            $request['tram_bao_hanh_id'] = $receiverTramId;
            $request['trung_tam_bao_hanh_id'] = $receiverTrungTamId;
            $request['user_log'] = $loai_nguoi_dungs->id;
            $request['trang_thai'] = 1; // trang tahi de nghi cap LK
            $request['loai_cong_viec'] = 4; // CSKH Yêu cầu kế toán cấp linh kiện
            $request['created_at']= date('Y-m-d H:i:s');
            $request['updated_at']= date('Y-m-d H:i:s');
            DB::table('requests')->insertGetId($request);

            // Update trang thai request PSC cho linh kien va cong viec cua NVBH
            $phieuDeNghi = PhieuDeNghiCapLinhKien::find($objectTypeId);
            $phieu_sua_chua_id = $phieuDeNghi->phieu_sua_chua_id;
            DB::table('requests')
                ->where(array('doi_tuong'=>2 ,'doi_tuong_id'=>$phieu_sua_chua_id,'loai_cong_viec'=>2))
                ->update(['ben_nhan_id'=>$loai_nguoi_dungs->id,'ben_nhan_la_nhom'=>1]);

            DB::table('requests')
                ->where(array('doi_tuong'=>2 ,'doi_tuong_id'=>$phieu_sua_chua_id))
                ->update(['trang_thai'=>3]);
            // Cap nhat trang thai phieu sua chua cho linh kien
            DB::table('phieu_sua_chuas')
                ->where(array('id'=>$phieu_sua_chua_id))
                ->update(['status'=>3]);


        }
        else{
            $request = [];
            $request['nguoi_gui_id'] = $sender->id;
            $request['ben_nhan_id'] = $receiverId;
            $request['ben_nhan_la_nhom'] = $isGroupReceiver;
            $request['doi_tuong'] = $objectType;
            $request['doi_tuong_id'] = $objectTypeId;
            $request['tram_bao_hanh_id'] = $receiverTramId;
            $request['trung_tam_bao_hanh_id'] = $receiverTrungTamId;
            $request['user_log']= $receiverId;
            $request['trang_thai'] = $status;
            $request['loai_cong_viec'] = $job_type;
            $request['created_at']= date('Y-m-d H:i:s');
            $request['updated_at']= date('Y-m-d H:i:s');
            DB::table('requests')->insertGetId($request);
        }


    }

    public function get(Request $request)
    {
        $chungTuId = $request->get('id');
        $chungTu = PhieuDeNghiCapLinhKien::with([
            'Congty' => function ($query) {
                $query->get();
            },
            'kho_tram' => function ($query) {
                $query->get();
            },
            'kho_trung_tam' => function ($query) {
                $query->get();
            },
            'linh_kiens' => function ($query) {
                $query->with(['linh_kien'=>function($query){$query->get();}]);
            },
            'nguoi_tao' => function ($query) {
                $query->get();
            }
        ])->where('phieu_de_nghi_cap_linh_kiens.id', $chungTuId)->first();
        if ($chungTu->trang_thai === 5 || $chungTu->trang_thai === 1){
            $khoTramId = $chungTu->kho_tram_id;
            $khoTrungTamId = $chungTu->kho_trung_tam_id;
            $query = 'select de_nghi_cap_linh_kien_chi_tiets.id,ton_kho_tots.ton_cuoi as ton_tram
                         from de_nghi_cap_linh_kien_chi_tiets left join ton_kho_tots 
                        on de_nghi_cap_linh_kien_chi_tiets.linh_kien_id = ton_kho_tots.linh_kien_id 
                        and ton_kho_tots.kho_id = '. $khoTramId .' where de_nghi_cap_linh_kien_chi_tiets.phieu_de_nghi_id ='.$chungTuId;
            $tonKhoTram = DB::select($query);
            $tonKhoTramMap = [];
            foreach ($tonKhoTram as $ton){
                $tonKhoTramMap[$ton->id] = $ton->ton_tram? $ton->ton_tram : 0;
            }
            $query = 'select de_nghi_cap_linh_kien_chi_tiets.id,ton_kho_tots.ton_cuoi as ton_trung_tam
                         from de_nghi_cap_linh_kien_chi_tiets left join ton_kho_tots 
                        on de_nghi_cap_linh_kien_chi_tiets.linh_kien_id = ton_kho_tots.linh_kien_id
                        and ton_kho_tots.kho_id ='.$khoTrungTamId.' where de_nghi_cap_linh_kien_chi_tiets.phieu_de_nghi_id ='.$chungTuId;
            $tonKhoTrungTam = DB::select($query);
            $tonKhoTrungTamMap = [];
            foreach ($tonKhoTrungTam as $ton){
                $tonKhoTrungTamMap[$ton->id] = $ton->ton_trung_tam?$ton->ton_trung_tam:0;
            }
            foreach ($chungTu->linh_kiens as $linh_kien){
                $linh_kien->ton_tram = $tonKhoTramMap[$linh_kien->id];
                $linh_kien->ton_trung_tam = $tonKhoTrungTamMap[$linh_kien->id];
            }
        }
        return response()->json($chungTu, 200);
    }

    public function getPagination(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'startDate' => 'date',
            'endDate' => 'date',
        ]);
        $where_status = array();
        if ($request->get('kho_trung_tam_id')> 0)
        {
            $arr_where= array('kho_trung_tam_id'=>$request->get('kho_trung_tam_id'));
        }
        else{
            $arr_where= array('kho_tram_id'=>$request->get('kho_tram_id'));

        }
        if ($request->get('trang_thai') != 'all') {
            $where_status = array('phieu_de_nghi_cap_linh_kiens.trang_thai' => $request->get('trang_thai'));
        }


        if (!$validator->fails()) {
         $from = $request->get('startDate');
         $to = $request->get('endDate');
         $to = date('Y-m-d',strtotime($to . "+1 days"));
            $phieuDNVTLK=DB::table('phieu_de_nghi_cap_linh_kiens')
                ->join('users', 'phieu_de_nghi_cap_linh_kiens.nguoi_tao_id', '=', 'users.id')
                ->select('phieu_de_nghi_cap_linh_kiens.*', 'users.name')
                ->where($arr_where)
                ->where($where_status)
                ->where('phieu_de_nghi_cap_linh_kiens.created_at','>=',$from)
                ->where('phieu_de_nghi_cap_linh_kiens.created_at','<=',$to)
                ->orderBy('phieu_de_nghi_cap_linh_kiens.thoi_han_can_vat_tu','asc')
                ->orderBy('phieu_de_nghi_cap_linh_kiens.created_at','desc')
                ->paginate(15);
        }
        else{
            $phieuDNVTLK=DB::table('phieu_de_nghi_cap_linh_kiens')
                ->join('users', 'phieu_de_nghi_cap_linh_kiens.nguoi_tao_id', '=', 'users.id')
                ->select('phieu_de_nghi_cap_linh_kiens.*', 'users.name')
                ->where($arr_where)
                ->orderBy('phieu_de_nghi_cap_linh_kiens.thoi_han_can_vat_tu','asc')
                ->orderBy('phieu_de_nghi_cap_linh_kiens.created_at','desc')
                ->paginate(15);
        }

        return Response::json($phieuDNVTLK, 200);


    }
}

