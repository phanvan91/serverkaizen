<?php

namespace App\Http\Controllers\Api;

use App\CauHoi;
use App\Exceptions\DuplicateInfoException;
use App\KhachHang;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\PhieuSuaChua;
use App\LogCongViec;

class HappyCallController extends Controller
{
    public function getAll(Request $request)
    {

        $cau_hois = DB::table('cau_hois')->where('to_chuc_id',$request->get('to_chuc')->id)->get();
        if($cau_hois)
        {
            return response()->json($cau_hois, 200);

        }
        else{
            return response()->json('Not found', 404);

        }


    }
    public function get_list_hpcall (Request $request){
        $to_chuc = 1;
        $phieu_sua_chua_id =1;
        $second = DB::table('cau_tra_lois')
            ->rightJoin('cau_hois', 'cau_hois.id', '=', 'cau_tra_lois.cau_hoi_id')
            ->where(array('cau_tra_lois.to_chuc_id'=>$to_chuc,'cau_tra_lois.phieu_sua_chua_id'=>$phieu_sua_chua_id));
        $cau_hois = DB::table('cau_hois')
            ->leftJoin('cau_tra_lois', 'cau_hois.id', '=', 'cau_tra_lois.cau_hoi_id')
            ->where(array('cau_tra_lois.to_chuc_id'=>$to_chuc,'cau_tra_lois.phieu_sua_chua_id'=>$phieu_sua_chua_id))
            ->union($second)
            ->get();
        return response()->json($cau_hois, 200);
    }

    public function getHpCallNoPagination(Request $request)
    {
        $to_chuc_id =$request->get('to_chuc')->id;

        $cau_tra_hois=DB::table('cau_tra_lois')->selectRaw('DISTINCT phieu_sua_chua_id')
            ->where([
                ['to_chuc_id', '=', $to_chuc_id],
                ['da_thuc_hien', '=', 0],
            ])
            ->paginate(15);
        foreach ($cau_tra_hois as $value)
        {
            $phieu_sua_chua_id=$value->phieu_sua_chua_id;
            $info_phieu=DB::table('phieu_sua_chuas')
                ->join('khach_hangs', 'phieu_sua_chuas.khach_hang_id', '=', 'khach_hangs.id')
                ->where([
                    ['phieu_sua_chuas.id', '=', $phieu_sua_chua_id],
                ])
                ->select('phieu_sua_chuas.*', 'khach_hangs.ten','khach_hangs.dien_thoai')

                ->first();
            foreach ($info_phieu as $k=>$v)
            {
                $value->$k=$v;
                if($k=='serial_id')
                {
                    $info_serial=DB::table('serials')
                        ->where([
                            ['serials.id', '=', $v],
                        ])->first();
                    if($info_serial)
                    {
                        $value->serial=$info_serial->serial;
                        $value->ngay_kich_hoat_bh=$info_serial->ngay_kich_hoat_bh;
                        $value->ngay_het_han=$info_serial->ngay_het_han;


                    }
                }

            }
        }


        return Response::json($cau_tra_hois, 200);

    }

    public function getHpCallYesPagination(Request $request)
    {
        $to_chuc_id =$request->get('to_chuc')->id;

        $cau_tra_hois=DB::table('cau_tra_lois')->selectRaw('DISTINCT phieu_sua_chua_id')
            ->where([
                ['to_chuc_id', '=', $to_chuc_id],
                ['da_thuc_hien', '=', 1],
            ])
            ->paginate(15);
        foreach ($cau_tra_hois as $value)
        {
            $phieu_sua_chua_id=$value->phieu_sua_chua_id;
            $info_phieu=DB::table('phieu_sua_chuas')
                ->join('khach_hangs', 'phieu_sua_chuas.khach_hang_id', '=', 'khach_hangs.id')
                ->where([
                    ['phieu_sua_chuas.id', '=', $phieu_sua_chua_id],
                ])
                ->select('phieu_sua_chuas.*', 'khach_hangs.ten','khach_hangs.dien_thoai')

                ->first();
            foreach ($info_phieu as $k=>$v)
            {
                $value->$k=$v;
                if($k=='serial_id')
                {
                    $info_serial=DB::table('serials')
                        ->where([
                            ['serials.id', '=', $v],
                        ])->first();
                    if($info_serial)
                    {
                        $value->serial=$info_serial->serial;
                        $value->ngay_kich_hoat_bh=$info_serial->ngay_kich_hoat_bh;
                        $value->ngay_het_han=$info_serial->ngay_het_han;


                    }
                }

            }
        }


        return Response::json($cau_tra_hois, 200);

    }

    public function getPagination(Request $request)
    {
        $cau_hois=DB::table('cau_hois')->where('to_chuc_id',$request->get('to_chuc')->id)->paginate(15);
        return Response::json($cau_hois, 200);


    }


    public function delete(Request $request)
    {
        $this->validate($request, [
            'to_chuc_id' => 'required',
            'id' => 'required'
        ]);
        $id= $request->get('id');
        $to_chuc_id=$request->get('to_chuc')->id;

        $cau_hois = DB::table('cau_hois')->where(array('id'=>$id,'to_chuc_id'=>$to_chuc_id))->first();

        if ($cau_hois) {
            try {
            //  DB::table('cau_hois')->where('id', '=', $id)->delete();
                CauHoi::find($id)->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 200);
            }
        } else {
            return response()->json('Not found', 404);
        }



    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'cau_hoi' => 'required',
            'loai' => 'required',
            'to_chuc_id' => 'required',
        ]);
//        $data = $request->all();
        $id= $request->get('id');
        $tochuc =$request->get('to_chuc')->id;
        $data['id'] = $request->get('id');
        $data['cau_hoi'] = $request->get('cau_hoi');
        $data['loai'] = $request->get('loai');
        $data['to_chuc_id'] =$request->get('to_chuc')->id;
        $data['updated_at']=date('Y-m-d H:i:s');

        $cau_hois = CauHoi::find($id);
        if($cau_hois)
        {

            try {
                $cau_hois->cau_hoi = $request->get('cau_hoi');
                $cau_hois->loai = $request->get('loai');
                $cau_hois->updated_at = date('Y-m-d H:i:s');
                $cau_hois->save();
                return Response::json($cau_hois, 200);

            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }

        }
        else{
            return response()->json('Something wrong', 500);

        }

        }
        public function actionHpcall(Request $request)
        {
//            $data= $request->all();
            $cau_tra_loi = $request->get('cau_tra_loi');
            $phieu_sua_chua_id =$request->get('phieu_sua_chua_id');
            $khach_hang_id =$request->get('khach_hang_id');
            $to_chuc_id =  $request->get('to_chuc')->id;
            $da_thuc_hien =$request->get('da_thuc_hien');
            $i=0;
            $user_id = $request->get('user_id');
            if(!$khach_hang_id)
            {
                $info_psc = DB::table('phieu_sua_chuas')->where(array('id'=>$phieu_sua_chua_id,'to_chuc_id'=>$to_chuc_id))->first();
                $khach_hang_id = $info_psc->khach_hang_id;
            }

            foreach ($cau_tra_loi as $key =>$value)
            {
                $i++;
                $cau_hoi_id =$key;
                $data['cau_tra_loi']=$value;
                $data['da_thuc_hien']=$da_thuc_hien;
                $data['to_chuc_id']=$to_chuc_id;
                $data['khach_hang_id']=$khach_hang_id;
                $data['phieu_sua_chua_id']=$phieu_sua_chua_id;
                $data['cau_hoi_id']=$cau_hoi_id;
                $data['user_id']=$user_id;

                $data['updated_at']=date('Y-m-d H:i:s');
                $data['created_at']=date('Y-m-d H:i:s');

                $check_cau_tra_loi = DB::table('cau_tra_lois')->where(array('khach_hang_id'=>$khach_hang_id,'cau_hoi_id'=>$cau_hoi_id,'phieu_sua_chua_id'=>$phieu_sua_chua_id))->first();
                if($check_cau_tra_loi)
                {

                    try {
                        DB::table('cau_tra_lois')
                            ->where('id', $check_cau_tra_loi->id)
                            ->update($data);
                    } catch (\Exception $e) {
                        return response()->json($e->getMessage(), 500);
                    }
                }
                else{

                    DB::table('cau_tra_lois')->insertGetId($data);

                }
                $pSC = PhieuSuaChua::find($phieu_sua_chua_id);
                if($pSC->tra_xac_linh_kien)
                {
                    $pSC->status = 1; // Hoàn tất phieu bao hành
                    $pSC->save();

                }
                else{
                    $pSC->status = 7; // Hoàn tất HPCall chưa trả LK
                    $pSC->save();
                }
                // Update requests
                $requests= DB::table('requests')
                    ->where(array('doi_tuong'=>2 ,'doi_tuong_id'=>$phieu_sua_chua_id,'loai_cong_viec'=>3))
                    ->first();

                if($requests)
                {
                    $request_update = \App\Request::find($requests->id);
                    if( $pSC->status==1)
                    {
                        $request_update->da_xu_ly = 1;

                    }
                    $request_update->ben_nhan_la_nhom = 0;
                    $request_update->ben_nhan_id = $user_id;
                    $request_update->user_log = $user_id;
                    $request_update->hoan_thanh = 1;
                    $request_update->updated_at = date('Y-m-d H:i:s');
                    $request_update->da_xem = 1;
                    $request_update->trang_thai = $pSC->status;
                    $request_update->save();

                    DB::table('requests')
                        ->where(array('doi_tuong'=>2 ,'doi_tuong_id'=>$phieu_sua_chua_id,))
                        ->update(['trang_thai'=>$pSC->status]);


                }



            }
            $data['trang_thai_psc']=$pSC->status;

            return Response::json($data, 200);

        }
        public function getHpcallIndex(Request $request)
        {
            $phieu_sua_chua_id = $request->get('phieu_sua_chua_id');
            $khach_hang_id = $request->get('khach_hang_id');
            $to_chuc= $request->get('to_chuc')->id;
            $count_cau_tra_lois = DB::table('cau_tra_lois')->where('phieu_sua_chua_id',$phieu_sua_chua_id)->count();
            if($count_cau_tra_lois >0)
            {
                $data = DB::table('cau_tra_lois')
                    ->join('cau_hois', 'cau_tra_lois.cau_hoi_id', '=', 'cau_hois.id')
                    ->where(array('cau_tra_lois.to_chuc_id'=>$to_chuc,'cau_tra_lois.phieu_sua_chua_id'=>$phieu_sua_chua_id))
                    ->get();
            }else{
                $data = DB::table('cau_hois')->where('to_chuc_id',$request->get('to_chuc')->id)->get();

            }



            return Response::json($data, 200);

        }
    public function create(Request $request)
    {
        $this->validate($request, [
            'cau_hoi' => 'required',
            'loai' => 'required',
            'to_chuc_id'=> 'required',
        ]);
        $data['cau_hoi'] = $request->get('cau_hoi');
        $data['to_chuc_id'] =$request->get('to_chuc')->id;
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        $data['loai'] = $request->get('loai');

        $result = null;
        try{
            $id=DB::table('cau_hois')->insertGetId($data);
            $data['id']=$id;

        }catch (\Exception $e){
            throw new DuplicateInfoException();
        }
        return Response::json($data, 200);
    }

}
