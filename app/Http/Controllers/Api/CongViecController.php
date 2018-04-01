<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DuplicateInfoException;
use App\PhieuSuaChua;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\User;
use Validator;
class CongViecController extends Controller
{
    public function getPagination(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'startDate' => 'date',
            'endDate' => 'date',
        ]);
        $uid= $request->get('uid');
        $user = User::find($uid);
        $loai_nguoi_dung = $user->loai_nguoi_dung_id;
        if (!$validator->fails()) {
            $from = $request->get('startDate');
            $to = $request->get('endDate');
            $to = date('Y-m-d',strtotime($to . "+1 days"));
            if($loai_nguoi_dung != 3 && $loai_nguoi_dung != 5  && $loai_nguoi_dung != 4){
                $query = '(requests.ben_nhan_la_nhom = 0 and requests.user_log = 
                '.$uid.' and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'"  ) or 
                 (requests.ben_nhan_la_nhom = 1 and requests.user_log = 
                '.$loai_nguoi_dung.' and hoan_thanh = 0 and requests.loai_cong_viec != 2   and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'"  ) or 
                 (requests.hoan_thanh = 1 and requests.ben_nhan_la_nhom = 1  and requests.user_log = 
                '.$uid.' and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'"  )  or 
                 (requests.loai_cong_viec = 2 and requests.ben_nhan_la_nhom = 1  and requests.user_log = 
                '.$uid.' and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'"  )';

            }
            if($loai_nguoi_dung == 3){
                $query = '(requests.loai_cong_viec = 5 and hoan_thanh = 0 and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'" ) or
                (requests.loai_cong_viec = 5 and hoan_thanh = 1 and user_log = '.$uid.'  and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'" ) ';

            }
            if($loai_nguoi_dung == 5){
                $query = '((requests.loai_cong_viec = 3 or requests.loai_cong_viec = 4)  and hoan_thanh = 0 and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'" ) or
                ((requests.loai_cong_viec = 3 or requests.loai_cong_viec = 4)  and hoan_thanh = 1 and user_log = '.$uid.'  and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'" ) ';

            }

            if($loai_nguoi_dung == 4){
                $query = '(requests.loai_cong_viec = 2 and hoan_thanh = 0 and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'" ) or
                (requests.loai_cong_viec = 2 and hoan_thanh = 1 and user_log = '.$uid.'  and requests.created_at >= "'.$from.'" and requests.created_at <= "'.$to.'" ) ';

            }

            $cong_viec = DB::table('requests')
                ->join('users', 'requests.nguoi_gui_id', '=', 'users.id')
                ->select('requests.*', 'users.name as ten_nguoi_tao')
                ->whereRaw($query, [200])
                ->orderBy('requests.hoan_thanh', 'asc')
                ->orderBy('requests.created_at', 'desc')
                ->paginate(15);
                }
                else{
                    $cong_viec = DB::table('requests')
                        ->join('users', 'requests.nguoi_gui_id', '=', 'users.id')
                        ->select('requests.*', 'users.name as ten_nguoi_tao')
                        ->whereRaw('(requests.ben_nhan_la_nhom = 0 and requests.user_log = 
                        '.$uid.' ) or (requests.ben_nhan_la_nhom = 1 and requests.user_log = '.$loai_nguoi_dung.' ) or (requests.loai_cong_viec = 2 and requests.user_log = '.$uid.' )', [200])
                        ->orderBy('requests.hoan_thanh', 'asc')
                        ->orderBy('requests.created_at', 'desc')
                        ->paginate(15);
                }
                foreach ($cong_viec as $value) {
                        if ($value->ben_nhan_la_nhom == 1) {
                            $nhom_tai_khoan = \App\LoaiNguoiDung::find($value->ben_nhan_id);
                            $value->ben_nhan = $nhom_tai_khoan->ten_loai;

                        } else {
                            $user_nhan = User::find($value->ben_nhan_id);
                            $value->ben_nhan = $user_nhan->name;

                        }
                    }



        return Response::json($cong_viec, 200);



    }
    public function phancongBH(Request $request)
    {
        $nhan_vien_bao_hanh_id= $request->get('nhan_vien_bao_hanh_id');
        $ghi_chu =  $request->get('ghi_chu');
        $request_id =  $request->get('request_id');
        $user_id = $request->get('user_id');
        $request_update = \App\Request::find($request_id);
        $pSc = PhieuSuaChua::find($request_update->doi_tuong_id);

        try {
            // update request chuyển pbh sang NVBH
            $request_update->ben_nhan_la_nhom = 0;
            $request_update->ben_nhan_id = $nhan_vien_bao_hanh_id;
            $request_update->nguoi_gui_id = $user_id;
            $request_update->ghi_chu = $ghi_chu;
            $request_update->hoan_thanh = 1;
            $request_update->user_log = $user_id;
            $request_update->da_xem = 1;
            $request_update->trang_thai = 2;
            $request_update->updated_at = date('Y-m-d H:i:s');
            $request_update->save();

            // update trang thai PSC
            $pSc->status = 2;
            $pSc->nhan_vien_bao_hanh_id = $nhan_vien_bao_hanh_id;

            $pSc->save();

            // tạo công việc cho NVBH

            $data_log['doi_tuong']= 2;
            $data_log['doi_tuong_id']= $request_update->doi_tuong_id;
            $data_log['nguoi_gui_id']= $user_id;
            $data_log['ben_nhan_id']= $nhan_vien_bao_hanh_id;
            $data_log['ben_nhan_la_nhom']= 0; //false
            $data_log['ghi_chu']= $ghi_chu;
            $data_log['trang_thai']= 2;
            $data_log['da_xem']= false;
            $data_log['tram_bao_hanh_id']= $request_update->tram_bao_hanh_id;
            $data_log['trung_tam_bao_hanh_id']= $request_update->trung_tam_bao_hanh_id;
            $data_log['loai_cong_viec']= 2;
            $data_log['user_log']= $nhan_vien_bao_hanh_id;
            $data_log['created_at']= date('Y-m-d H:i:s');
            $data_log['updated_at']= date('Y-m-d H:i:s');
            $id_log = DB::table('requests')->insertGetId($data_log);



            $logCongViec = DB::table('requests')
                ->join('users', 'requests.nguoi_gui_id', '=', 'users.id')
                ->select('requests.*', 'users.name as ten_nguoi_tao')
                ->where('requests.id',$id_log)
                ->first();
            if ($logCongViec->ben_nhan_la_nhom == 1) {
                $nhom_tai_khoan = \App\LoaiNguoiDung::find($logCongViec->ben_nhan_id);
                $logCongViec->ben_nhan = $nhom_tai_khoan->ten_loai;

            } else {
                $user_nhan = User::find($logCongViec->ben_nhan_id);
                $logCongViec->ben_nhan = $user_nhan->name;

            }

            return Response::json($logCongViec, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }

}
