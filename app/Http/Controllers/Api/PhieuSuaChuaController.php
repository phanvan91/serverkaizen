<?php

namespace App\Http\Controllers\Api;

use App\ChiPhiDiLaiPhieuSuaChua;
use App\DanhSachChiPhiDiLai;
use App\GhiChu;
use App\KhachHang;
use App\PhieuSuaChua;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Serial;
use App\Exceptions\DuplicateInfoException;
use File;


class PhieuSuaChuaController extends Controller
{
    public function getAll(Request $request)
    {
        $psc = DB::table('phieu_sua_chuas')->where('to_chuc_id', $request->get('to_chuc')->id)->get();
        if ($psc) {
            return response()->json($psc, 200);

        } else {
            return response()->json('Not found', 404);

        }

    }


    public function paginate(Request $request)
    {
        $status = $request->get('trang_thai');
        $to_chuc_id = $request->get('to_chuc')->id;
        $serial = $request->get('serial');
        $uuTien = $request->get('uu_tien');
        $tramBH = $request->get('tram_bao_hanh_id');

        if ($status == 'all') {
            $where = array('phieu_sua_chuas.to_chuc_id' => $to_chuc_id);
        }else {
            $where = array('phieu_sua_chuas.status' => $status, 'phieu_sua_chuas.to_chuc_id' => $to_chuc_id);
        }




        $psc = DB::table('phieu_sua_chuas')
            ->join('serials', 'phieu_sua_chuas.serial_id', '=', 'serials.id')
            ->join('tram_bao_hanhs', 'phieu_sua_chuas.tram_bao_hanh_id', '=', 'tram_bao_hanhs.id')
            ->where($where)
            ->where('serials.serial','like','%'.$serial.'%')
            ->where('phieu_sua_chuas.uu_tien','like','%'.$uuTien.'%');

        if($tramBH){
            $psc->where('tram_bao_hanhs.id',$tramBH);
        }
        $psc = $psc->select('phieu_sua_chuas.*', 'serials.serial', 'tram_bao_hanhs.ten')
                    ->orderBy('phieu_sua_chuas.id', 'DESC')
                    ->paginate(15);

        return Response::json($psc, 200);
    }


    public function create(Request $request)
    {
//        $this->validate($request, [
//            'images' => 'required',
//            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
//        ]);
        $data = $request->only('ghi_chu', 'kenh_tiep_nhan', 'khach_hang_id', 'loai_hinh_dv', 'ngay_hoan_tat_mong_muon', 'ngay_tiep_nhan', 'noi_thuc_hien', 'serial_id',
            'tinh_trang_hu_hongs', 'user_id', 'uu_tien', 'tram_bao_hanh_id', 'tong_tien');

        $toChuc = $request->get('to_chuc');

        DB::beginTransaction();
        try {

            $pSC = $toChuc->danhSachPhieuSuaChua()->create([
                'kenh_tiep_nhan' => $data['kenh_tiep_nhan'],
                'uu_tien' => $data['uu_tien'],
                'loai_hinh_dv' => $data['loai_hinh_dv'],
                'ngay_tiep_nhan' => Carbon::parse($data['ngay_tiep_nhan']),
                'noi_thuc_hien' => $data['noi_thuc_hien'],
                'ngay_hoan_tat_mong_muon' => Carbon::parse($data['ngay_hoan_tat_mong_muon']),
                'ghi_chu' => $data['ghi_chu'],
                'user_id' => $data['user_id'],
                'khach_hang_id' => $data['khach_hang_id'],
                'serial_id' => $data['serial_id'],
                'tram_bao_hanh_id' => $data['tram_bao_hanh_id'],
                'tong_tien' => $data['tong_tien'],
                'tra_xac_linh_kien' =>1

            ]);

            $tinhTrangHuHongList = $request->get('tinh_trang_hu_hongs');
            $tthhIds = [];

            foreach ($tinhTrangHuHongList as $value) {
                $tthhIds[] = $value['id'];
            }
            $pSC->danhSachTinhTrangHuHong()->sync($tthhIds);
            $serials = Serial::find($data['serial_id']);
            if($serials)
            {
                $serials->khach_hang_id =  $request->get('khach_hang_id');
                $serials->save();

            }
            $trung_tam_bh= DB::table('tram_bao_hanhs')->where(array('id'=>$data['tram_bao_hanh_id']))->first();


            $loai_nguoi_dungs = DB::table('loai_nguoi_dungs')->where('ten_loai','QUANLY')->first();

            // Tao requests , log psc
            $data_requests['nguoi_gui_id']= $data['user_id'];
            $data_requests['ben_nhan_id']= $loai_nguoi_dungs->id;
            $data_requests['ben_nhan_la_nhom']= 1; //true
            $data_requests['tram_bao_hanh_id']= $data['tram_bao_hanh_id'];
            $data_requests['trung_tam_bao_hanh_id']= $trung_tam_bh->trung_tam_bao_hanh_id;
            $data_requests['da_xem']= false;
            $data_requests['da_xu_ly']= false;
            $data_requests['doi_tuong']= 2;
            $data_requests['doi_tuong_id']= $pSC->id;
            $data_requests['ghi_chu']= '';
            $data_requests['user_log']= $loai_nguoi_dungs->id;
            $data_requests['trang_thai']= 0;
            $data_requests['loai_cong_viec']= 1;
            $data_requests['created_at']= date('Y-m-d H:i:s');
            $data_requests['updated_at']= date('Y-m-d H:i:s');
            $id_requests = DB::table('requests')->insertGetId($data_requests);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 200);
        }

        return response()->json($pSC, 200);
    }
    public function update(Request $request)
    {
//        $this->validate($request, [
//            'images' => 'required',
//            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
//        ]);
        $data = $request->only('id','ghi_chu', 'kenh_tiep_nhan', 'khach_hang_id', 'loai_hinh_dv', 'ngay_hoan_tat_mong_muon', 'ngay_tiep_nhan', 'noi_thuc_hien', 'serial_id',
            'tinh_trang_hu_hongs', 'user_id', 'uu_tien', 'tram_bao_hanh_id','tong_tien','user_log');

        $toChuc = $request->get('to_chuc');
        $pSC = PhieuSuaChua::find($request->get('id'));
        if($pSC)
        {

            try {
                $pSC->ghi_chu = $request->get('ghi_chu');
                $pSC->kenh_tiep_nhan = $request->get('kenh_tiep_nhan');
                $pSC->khach_hang_id = $request->get('khach_hang_id');
                $pSC->loai_hinh_dv = $request->get('loai_hinh_dv');
                $pSC->ngay_hoan_tat_mong_muon = Carbon::parse($request->get('ngay_hoan_tat_mong_muon'));
                $pSC->ngay_tiep_nhan = Carbon::parse($request->get('ngay_tiep_nhan'));
                $pSC->noi_thuc_hien = $request->get('noi_thuc_hien');
                $pSC->serial_id = $request->get('serial_id');
                $pSC->user_id = $request->get('user_id');
                $pSC->uu_tien = $request->get('uu_tien');
                $pSC->tram_bao_hanh_id = $request->get('tram_bao_hanh_id');
                $pSC->tong_tien = $request->get('tong_tien');
                $pSC->updated_at = date('Y-m-d H:i:s');
                if( $request->get('checked_fix') && $pSC->status != 1 && $pSC->status != 6)
                {
                    $pSC->status = 6; // Hoàn tất sửa chữa
                    // Update requests
                    $requests= DB::table('requests')
                        ->where(array('doi_tuong'=>2 ,'doi_tuong_id'=>$pSC->id,'user_log'=>$request->get('user_log')))
                        ->first();
                    if ($requests) {
                        $loai_nguoi_dungs = DB::table('loai_nguoi_dungs')->where('ten_loai','CSKH')->first();
                        $request_update = \App\Request::find($requests->id);
                        $request_update->ben_nhan_la_nhom = 1;
                        $request_update->ben_nhan_id = $loai_nguoi_dungs->id;
                        $request_update->nguoi_gui_id = $request->get('user_id');
                        $request_update->hoan_thanh = 1;
                        $request_update->da_xem = 1;
                        $request_update->trang_thai = 6;
                        $request_update->updated_at = date('Y-m-d H:i:s');
                        $request_update->save();
                        DB::table('requests')
                            ->where(array('doi_tuong'=>2 ,'doi_tuong_id'=>$pSC->id,'loai_cong_viec'=>2))
                            ->update(['hoan_thanh'=>1]);

                        DB::table('requests')
                            ->where(array('doi_tuong'=>2 ,'doi_tuong_id'=>$pSC->id,))
                            ->update(['trang_thai'=>$pSC->status]);



                        // tạo công việc HpCall cho cskh

                        $data_log['doi_tuong']= 2;
                        $data_log['doi_tuong_id']= $request_update->doi_tuong_id;
                        $data_log['nguoi_gui_id']= $request->get('user_log');
                        $data_log['ben_nhan_id']=  $loai_nguoi_dungs->id;
                        $data_log['ben_nhan_la_nhom']= 1; //true
                        $data_log['ghi_chu']= $request_update->ghi_chu;
                        $data_log['trang_thai']= 6;
                        $data_log['da_xem']= false;
                        $data_log['tram_bao_hanh_id']= $request_update->tram_bao_hanh_id;
                        $data_log['trung_tam_bao_hanh_id']= $request_update->trung_tam_bao_hanh_id;
                        $data_log['loai_cong_viec']= 3;
                        $data_log['user_log']= $loai_nguoi_dungs->id;
                        $data_log['created_at']= date('Y-m-d H:i:s');
                        $data_log['updated_at']= date('Y-m-d H:i:s');
                        $id_log = DB::table('requests')->insertGetId($data_log);

                    }



                }
                if(!$request->get('checked_fix') && ($pSC->status == 6 || $pSC->status == 7)){
                    $pSC->status = 4; // Cập nhật Trạng thái đang sửa chữa

                }
                $pSC->save();
                $tinhTrangHuHongList = $request->get('tinh_trang_hu_hongs');
                $tthhIds = [];

                foreach ($tinhTrangHuHongList as $value) {
                    $tthhIds[] = $value['id'];
                }
                $pSC->danhSachTinhTrangHuHong()->sync($tthhIds);
                $serials = Serial::find($data['serial_id']);
                if($serials)
                {
                    $serials->khach_hang_id =  $request->get('khach_hang_id');
                    $serials->save();

                }

            return Response::json($pSC, 200);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }

        }
        else{
            return response()->json(['error' => 'Something wrong'], 500);

        }

    }




    public function create_phieu_nhap_kho(Request $request)
    {

        $data_serial = $request->get('data_serial');
       $khach_hang_id = $request->get('id_khach_hang');
       $tram_bao_hanh_id = $request->get('tram_bao_hanh_id');
       $user_id = $request->get('user_id');
       $kenh_tiep_nhan =1;
       $uu_tien=3;
       $loai_hinh_dv =1;
       $noi_thuc_hien=1;
       $ngay_tiep_nhan = date('Y-m-d');
       $ngay_hoan_tat =  date("Y-m-d", strtotime("+ 7 day"));
       foreach ($data_serial as $value)
       {
           $data_psc['kenh_tiep_nhan']=$kenh_tiep_nhan;
           $data_psc['uu_tien']=$uu_tien;
           $data_psc['loai_hinh_dv']=$loai_hinh_dv;
           $data_psc['ngay_tiep_nhan']=$ngay_tiep_nhan;
           $data_psc['ngay_hoan_tat_mong_muon']=$ngay_hoan_tat;
           $data_psc['noi_thuc_hien']=$noi_thuc_hien;
           $data_psc['user_id']=$user_id;
           $data_psc['khach_hang_id']=$khach_hang_id;
           $data_psc['serial_id']=$value['id'];
           $data_psc['to_chuc_id']= $request->get('to_chuc_id');
           $data_psc['status'] = 0;
           $data_psc['tram_bao_hanh_id'] = $tram_bao_hanh_id;
           $data_psc['created_at']=date('Y-m-d H:i:s');
           $data_psc['updated_at']=date('Y-m-d H:i:s');
           try{
               $id_psc=DB::table('phieu_sua_chuas')->insertGetId($data_psc);
               // Thêm tình trang hu hong
               if (isset($value['tinh_trang_hu_hong_id'])){
                   $data_psc_tthh['phieu_sua_chua_id'] = $id_psc;
                   $data_psc_tthh['tinh_trang_hu_hong_id']=$value['tinh_trang_hu_hong_id'];
                   $data_psc_tthh['created_at']=date('Y-m-d H:i:s');
                   $data_psc_tthh['updated_at']=date('Y-m-d H:i:s');
                   DB::table('phieusuachua_tinhtranghuhong')->insertGetId($data_psc_tthh);

               }
               $serials = DB::table('serials')->where('id',$value['id'])->first();
               $tram_bh = DB::table('tram_bao_hanhs')->where('id',$tram_bao_hanh_id)->first();

               $data_ct['cong_ty_id']=$tram_bh->cong_ty_id;
               $data_ct['phieu_sua_chua_id']=$id_psc;
               $data_ct['tk_no_id']=1;
               $data_ct['tk_co_id']=1;
               $data_ct['doi_tuong_no_id']=1;
               $data_ct['doi_tuong_co_id']=1;
               $data_ct['loai_ct']=1;
               $data_ct['ngay_ct']=date('Y-m-d H:i:s');
               $data_ct['so_ct']=1;
               $data_ct['ma_ct_id']=1;
               $data_ct['ngay_nhan']=date('Y-m-d H:i:s');
               $data_ct['tong_so_tien_truoc_thue']='0.00';
               $data_ct['phan_tram_thue']='0.00';
               $data_ct['trang_thai']=1;
               $data_ct['created_at']=date('Y-m-d H:i:s');
               $data_ct['updated_at']=date('Y-m-d H:i:s');
               $data_ct['nguoi_tao_id']=$user_id;
               $data_ct['nguoi_sua_id']=$user_id;
               $data_ct['serial_id']=$value['id'];
               $data_ct['model_id']=$serials->model_id;
               $id_ct=DB::table('chung_tu_kho_tots')->insertGetId($data_ct);
               $data_id[]=$id_ct;


               $khos = DB::table('khos')->where('tram_bao_hanh_id',$tram_bao_hanh_id)->first();

               $data_pnk['ton_dau_ky']=1;
               $data_pnk['chung_tu_kho_tot_id']=$id_ct;
               $data_pnk['serial_id']=$value['id'];
               $data_pnk['so_luong_yc']=1;
               $data_pnk['loai_giao_dich']=1;
               $data_pnk['so_luong_thuc']=1;
               $data_pnk['ton_cuoi_ky']=1;
               $data_pnk['kho_id']=$khos->id;
               $data_pnk['ghi_chu']='';
               $data_pnk['da_duyet']=false;
               $data_pnk['created_at']=date('Y-m-d H:i:s');
               $data_pnk['updated_at']=date('Y-m-d H:i:s');
               $id_pnk=DB::table('phieu_nhap_kho')->insertGetId($data_pnk);
               DB::table('phieu_sua_chuas')->where('id', $id_psc)->update(['phieu_nhap_kho_id' => $id_pnk]);


           }catch (\Exception $e){
               return response()->json($e->getMessage(), 500);
           }




       }
        return response()->json($data_serial, 200);

    }
    public function show(Request $request, $id)
    {
        $psc = $request->get('to_chuc')->danhSachPhieuSuaChua()->with(['danhSachTinhTrangHuHong',
            'danhSachChiPhiDiLai' => function ($query) {

                $query->select('dscpdl_psc.*');
            }, 'notes', 'danhSachNguyenNhan', 'danhSachBangTinhCongSuaChua'])->where('id', $id)->first();
        $serial_id = $psc->serial_id;
        $serial =DB::table('serials')
            ->join('models', 'serials.model_id', '=', 'models.id')
            ->join('san_phams', 'serials.san_pham_id', '=', 'san_phams.id')
            ->join('nganh_hangs', 'serials.nganh_hang_id', '=', 'nganh_hangs.id')

            ->where('serials.id',$serial_id)
            ->select('serials.*', 'models.ten as ten_model', 'san_phams.ten as ten_sp', 'nganh_hangs.ten as ten_nganh')
            ->first();
        $today = date('Y-m-d');
        if ($today < $serial->ngay_het_han)
        {
            $serial->het_han = false;
        }
        else{
            $serial->het_han = true;

        }
        $psc->serial = $serial;

        $toChuc = $request->get('to_chuc');
        $customers = $toChuc->danhSachKhachHang()->where('id', $psc->khach_hang_id)->first();
        $psc->customer = $customers;

        if($psc->nhan_vien_bao_hanh_id > 0)
        {
            $nvbh  = DB::table('users')
                ->where('id',$psc->nhan_vien_bao_hanh_id)
                ->first();
            if($nvbh)
            {
                $psc->nhan_vien_bao_hanh = $nvbh->name;

            }

        }

        return response()->json($psc, 200);
    }

    public function checkIn(Request $request)
    {
        $this->validate($request, [
            'tram_bao_hanh_id' => 'required',
            'khach_hang_id' => 'required',
            'id' => 'required',
            'created_at' => 'required',
            'updated_at' => 'required'
        ]);


        $khachHang = KhachHang::find($request->get('khach_hang_id'));
        if ($request->get('lat') && $request->get('lat'))
        {
            $latlng =  $request->get('lat').','. $request->get('lng');

            $result_Address = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$latlng);
            $result_Address = json_decode($result_Address);
            $address_checkIn = $result_Address->results[0]->formatted_address;
        }
        else{
            $address_checkIn = "Not found";
        }

        $dscpdl = DanhSachChiPhiDiLai::where('tram_bao_hanh_id', $request->get('tram_bao_hanh_id'))
            ->where('thanh_pho', $khachHang->tinh_tp)
            ->where('quan', $khachHang->quan_huyen)
            ->where('phuong', $khachHang->phuong_xa)
            ->first();
        $dscpdl->danhSachPhieuSuaChua()->attach($request->get('id'),
            [
                'lat' => $request->get('lat'),
                'lng' => $request->get('lng'),
                'address' => $address_checkIn,
                'tong_tien' => $dscpdl->thanh_tien_mot,
                'created_at' => Carbon::parse($request->get('created_at')),
                'updated_at' => Carbon::parse($request->get('updated_at')),
            ]
        );


        $psc = $request->get('to_chuc')->danhSachPhieuSuaChua()->with(['danhSachTinhTrangHuHong',
            'danhSachChiPhiDiLai' => function ($query) {
                $query->select('dscpdl_psc.*');
            }, 'notes', 'danhSachNguyenNhan', 'danhSachBangTinhCongSuaChua'])->where('id', $request->get('id'))->first();
//


        return response()->json($psc, 200);
    }

    public function removeChiPhiDiLai(Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        try {
            $cpdil = ChiPhiDiLaiPhieuSuaChua::find($request->get('id'));
            if ($cpdil) {
                $cpdil->delete();
            } else {
                return response()->json('Not found', 404);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($cpdil, 200);
    }

    public function updateIMG(Request $request) {
        $psc = PhieuSuaChua::find($request->get('id'));

            if ($psc) {
                $images = $request->get('hinh_anh');
                $filename = public_path().$request->get('filename');

                if ($images) {
                    File::delete($filename);
                    $psc->hinh_anh = '';
                    foreach ($images as $image) {
                        $psc->hinh_anh = trim($psc->hinh_anh . ',' . $image, ',');
                    }
                    $psc->save();
                }
                else{
                    $psc->hinh_anh = '';
                    $psc->save();
                    \File::delete($filename);

                }
            }


        return response()->json($filename, 200);


    }

    public function updateThongTinDichVu(Request $request) {

        $data = $request->only('id', 'huong_khac_phucs', 'nguyen_nhans', 'notes');

        $data['huong_khac_phucs'] = json_decode($data['huong_khac_phucs']);
        $data['nguyen_nhans'] = json_decode($data['nguyen_nhans']);
        $data['notes'] = json_decode($data['notes']);


        $nguyenNhanIds = [];
        $huongKhacPhucIds = [];

        $psc = PhieuSuaChua::find($request->get('id'));

        try {
            if ($psc) {
                DB::beginTransaction();
                foreach ($data['huong_khac_phucs'] as $huong_khac_phuc) {
                    $huongKhacPhucIds[] = $huong_khac_phuc->id;
                }
                foreach ($data['nguyen_nhans'] as $nguyen_nhan) {
                    $nguyenNhanIds[] = $nguyen_nhan->id;
                }

                $noteDatas = [];
                foreach ($data['notes'] as $value) {
                    if ($value->id == null) {
                        $noteDatas[] = [
                            'phieu_sua_chua_id' => $psc->id,
                            'note' => $value->note,
                            'user_id' => $request->get('user')->id,
                        ];
                    }
                }

                if (!empty($noteDatas)) {
                    GhiChu::insert($noteDatas);
                }
                $psc->danhSachBangTinhCongSuaChua()->sync($huongKhacPhucIds);
                $psc->danhSachNguyenNhan()->sync($nguyenNhanIds);
                    $images = $request->file('images');
                    if ($images) {
                        foreach ($images as $image) {
                            $name = time() .'-'. $image->getClientOriginalName();
                            $image->move('uploads/psc/'.$psc->id.'/',$name);
                            $filePath =  '/uploads/psc/'.$psc->id.'/'.$name;
                            $psc->hinh_anh = trim($psc->hinh_anh . ',' . $filePath, ',');
                        }
                        $psc->save();
                    }




                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($psc, 200);
    }

    public function updateChiPhiDiLai(Request $request) {
        $this->validate($request, [
            'id' => 'required',
            'ghi_chu' => 'required'
        ]);

        $cpdl = ChiPhiDiLaiPhieuSuaChua::find($request->get('id'));

        try {
            if ($cpdl) {
                $cpdl->ghi_chu = $request->get('ghi_chu');
                $cpdl->save();
            } else {
                return response()->json('Not found', 404);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($cpdl, 200);
    }
    public function checkPSC(Request $request)
    {
        $serial_id = $request->get('serial_id');
//        Log::info($serial_id);
        $psc = DB::table('phieu_sua_chuas')
            ->where('serial_id',$serial_id)
            ->where('status','!=' ,1)
            ->where('status','!=' ,6)
            ->where('status','!=' ,7)

            ->first();
        return response()->json($psc, 200);


    }

}
