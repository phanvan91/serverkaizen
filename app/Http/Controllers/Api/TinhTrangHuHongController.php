<?php

namespace App\Http\Controllers\Api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TinhTrangHuHong;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class TinhTrangHuHongController extends Controller
{
    public function getAll(Request $request)
    {
        try{

            $tinhTrangHuHongs = TinhTrangHuHong::where('to_chuc_id',$request->get('to_chuc')->id)->get();
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($tinhTrangHuHongs, 200);
    }
    public function getPagination(Request $request)
    {
        $list=DB::table('tinh_trang_hu_hongs')
            ->where('to_chuc_id',$request->get('to_chuc')->id)
            ->paginate(15);
        return Response::json($list, 200);


    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'ma_tinh_trang_hu_hong' => 'required|unique:tinh_trang_hu_hongs',
            'nganh_hang_id' => 'required'
        ]);
        try {
            $tinhTrangHuHong = new \App\TinhTrangHuHong();
            $tinhTrangHuHong->ma_tinh_trang_hu_hong = $request['ma_tinh_trang_hu_hong'];
            $tinhTrangHuHong->mo_ta = $request['mo_ta'];
            $tinhTrangHuHong->nganh_hang_id = $request['nganh_hang_id'];
            $tinhTrangHuHong->to_chuc_id = $request->get('to_chuc')->id;
            $tinhTrangHuHong->save();
        }
        catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($tinhTrangHuHong, 200);
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'ma_tinh_trang_hu_hong' => 'required',
            'nganh_hang_id' => 'required'
        ]);
        try {

            $checkTinhTrangHH = TinhTrangHuHong::where('ma_tinh_trang_hu_hong',$request->get('ma_tinh_trang_hu_hong'))->first();

            $tinhTrangHuHong = \App\TinhTrangHuHong::find($request['id']);

            if($checkTinhTrangHH->id != $tinhTrangHuHong->id) {
                return response()->json('Ma_tinh_trang_hu_hong da ton tai', 500);
            }

            if($tinhTrangHuHong->to_chuc_id!=$request->get('to_chuc')->id){
                return response()->json('Tinh trang hu hong khong co trong to chuc', 404);
            }
            $tinhTrangHuHong->ma_tinh_trang_hu_hong = $request['ma_tinh_trang_hu_hong'];
            $tinhTrangHuHong->mo_ta = $request['mo_ta'];
            $tinhTrangHuHong->nganh_hang_id = $request['nganh_hang_id'];
            $tinhTrangHuHong->save();
        }
        catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($tinhTrangHuHong, 200);
    }
    public function delete(Request $request){
        try{
            $tinhTrangHuHong = \App\TinhTrangHuHong::find($request->get('id'));
            if($tinhTrangHuHong->to_chuc_id = $request->get('to_chuc')->id){
                $tinhTrangHuHong->delete();
            }else{
                return response()->json('Tinh trang hu hong khong co trong to chuc', 404);
            }
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json('success', 200);
    }

    public function import(Request $request) {
        $this->validate($request, [
            'data' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        try {
            $data = $request->get('data');
            $dataSet = [];
            foreach ($data as $item) {
                $tthh = [
                    'ma_tinh_trang_hu_hong' => $item['ma_tinh_trang_hu_hong'],
                    'nganh_hang_id' => $item['nganh_hang_id'],
                    'to_chuc_id' => $toChuc->id,
                    'mo_ta' => $item['mo_ta']
                ];
                array_push($dataSet, $tthh);
            }
            DB::beginTransaction();
            TinhTrangHuHong::insert($dataSet);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($data, 200);
    }
    public function search(Request $request) {

        $tthh =DB::table('tinh_trang_hu_hongs')
            ->where('ma_tinh_trang_hu_hong', 'like', '%' . $request->get('query') . '%')
            ->orWhere('mo_ta', 'like', '%' . $request->get('query') . '%')

            ->limit(10)
            ->orderByDesc('id')
            ->get();
        return response()->json($tthh, 200);
    }
}
