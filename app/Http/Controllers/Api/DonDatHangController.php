<?php

namespace App\Http\Controllers\Api;

use App\CongTy;
use App\DonDatHang;
use App\DonDatHangChiTiet;
use App\ToChuc;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DonDatHangController extends Controller
{
    public function all(Request $request) {

        $tochuc = $request->get('to_chuc');
        $list = $tochuc->danhSachDonDatHang()->orderBy('id', 'DESC')->paginate(15);
        foreach ($list as $item) {
            $tTamBH = $tochuc->trungTamBaoHanh()->where('id',$item->trung_tam_bao_hanh_id)->first()->ten;
            $user = $tochuc->users()->where('id',$item->nguoi_dat_id)->first()->name;
            $item->{"tenTrungTamBH"} = $tTamBH;
            $item->{"tenUser"} = $user;
        }

        return response()->json($list, 200);
    }



    public function getDonDatHang(Request $request){
        $result = DonDatHang::with('danhSachDonDatHangChiTiet.linhKien',
            'user','trungTamBaoHanh.congTy')
            ->where('id',$request->get('don_dat_hang_id'))->first();
        return  response()->json($result, 200);
    }

    public function filter(Request $request) {

        $tochuc = $request->get('to_chuc');
        $list = $tochuc->danhSachDonDatHang()->where('ngay_dat_hang','>=',$request->get('ngay_bat_dau'))
        ->where('ngay_dat_hang','<=',$request->get('ngay_ket_thuc'))->paginate(15);
        foreach ($list as $item) {
            $tTamBH = $tochuc->trungTamBaoHanh()->where('id',$item->trung_tam_bao_hanh_id)->first()->ten;
            $user = $tochuc->users()->where('id',$item->nguoi_dat_id)->first()->name;
            $item->{"tenTrungTamBH"} = $tTamBH;
            $item->{"tenUser"} = $user;
        }

        return response()->json($list, 200);
    }

    public function search(Request $request) {
        $key_word =  $request->get('key_word');
        $result = DB::table('don_dat_hangs')
            ->where('so_ct','like', '%'.$key_word.'%')
            ->limit(20)->get();
        return response()->json($result,200);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'ngay_dat_hang' => 'required|date',
            'ngay_nhan_hang' => 'required|date',
            'so_ct' => 'required',
            'ly_do' => 'required',
            'data_chitiet' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');


        $user = \Auth::user();
        $trungTamBaoHanh = $user->trungTamBaoHanh;

        if(!$trungTamBaoHanh){
            return response()->json('Bạn không thuộc trung tâm bảo hành nào', 500);
        }

        try {
            DB::beginTransaction();
            if($request->get('id')){
                $donDatHang = DonDatHang::find($request->get('id'));
                $checkDonDatHang = DonDatHang::where('so_ct', $request->get('so_ct'))->first();
                if($checkDonDatHang->id !== $donDatHang->id){
                    return response()->json('Số chứng từ bị trùng', 500);
                }
                $donDatHang->ngay_dat_hang = $request->get('ngay_dat_hang');
                $donDatHang->ngay_nhan_hang = $request->get('ngay_nhan_hang');
                $donDatHang->so_ct = $request->get('so_ct');
                $donDatHang->ly_do = $request->get('ly_do');
                $donDatHang->trung_tam_bao_hanh_id = $trungTamBaoHanh->id;
                $donDatHang->nguoi_dat_id = $user->id;
                $donDatHang->save();

                DonDatHangChiTiet::where('don_dat_hang_id',$request->get('id'))->delete();

                $dataChiTiet = $request->get('data_chitiet');
                $data = [];
                foreach ($dataChiTiet as $chitiet){
                    $chiTietItem = [
                        'so_luong'=> $chitiet['so_luong'],
                        'linh_kien_id'=> $chitiet['linh_kien_id'],
                        'don_dat_hang_id' => $donDatHang->id
                    ];
                    array_push($data,$chiTietItem);
                }
                DonDatHangChiTiet::insert($data);
            }else{
                if(DonDatHang::where('so_ct',$request->get('so_ct'))->count()){
                    return response()->json('Số chứng từ bị trùng',500);
                }
                $donDatHang = $toChuc->danhSachDonDatHang()->create([
                    'ngay_dat_hang' => Carbon::parse($request->get('ngay_dat_hang')),
                    'ngay_nhan_hang' => Carbon::parse($request->get('ngay_nhan_hang')),
                    'so_ct' => $request->get('so_ct'),
                    'ly_do' => $request->get('ly_do'),
                    'trung_tam_bao_hanh_id' => $trungTamBaoHanh->id,
                    'nguoi_dat_id' => $user->id,
                ]);

                $dataChiTiet = $request->get('data_chitiet');
                $data = [];
                foreach ($dataChiTiet as $chitiet){
                    $chiTietItem = [
                        'so_luong'=> $chitiet['so_luong'],
                        'linh_kien_id'=> $chitiet['linh_kien_id'],
                        'don_dat_hang_id' => $donDatHang->id
                    ];
                    array_push($data,$chiTietItem);
                }

                DonDatHangChiTiet::insert($data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($donDatHang, 200);
    }

    public function delete(Request $request) {

        $this->validate($request, [
            'id' => 'required',
            'don_dat_hang_chi_tiet_id'
        ]);
        $toChuc = $request->get('to_chuc');
        try {
            $donDatHangChiTiet = $toChuc->danhSachDonDatHang()
                ->where('id', $request->get('id'))
                ->first()
                ->danhSachDonDatHangChiTiet()
                ->where('id', $request->get('don_dat_hang_chi_tiet_id'))
                ->first();

            if ($donDatHangChiTiet) {
                $donDatHangChiTiet->delete();
            } else {
                return response()->json('Not found', 404);
            }

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }


        return response()->json($donDatHangChiTiet, 200);
    }
}
