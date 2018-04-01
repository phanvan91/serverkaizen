<?php

namespace App\Http\Controllers\Api;

use App\HuongKhacPhuc;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class HuongKhacPhucController extends Controller
{
    public function getAll(Request $request)
    {
        try{
            $huongKhacPhuc = \App\HuongKhacPhuc::where('to_chuc_id',$request->get('to_chuc')->id)->get();
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($huongKhacPhuc, 200);
    }
    public function getPagination(Request $request)
    {
        $huong_khac_phucs=DB::table('huong_khac_phucs')
            ->where('to_chuc_id',$request->get('to_chuc')->id)
            ->paginate(15);
        return Response::json($huong_khac_phucs, 200);


    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'ma_huong_khac_phuc' => 'required|unique:huong_khac_phucs',
            'nganh_hang_id' => 'required'
        ]);
        try {
            $huongKhacPhuc = new \App\HuongKhacPhuc();
            $huongKhacPhuc->ma_huong_khac_phuc = $request['ma_huong_khac_phuc'];
            $huongKhacPhuc->mo_ta = $request['mo_ta'];
            $huongKhacPhuc->nganh_hang_id = $request['nganh_hang_id'];
            $huongKhacPhuc->to_chuc_id = $request->get('to_chuc')->id;
            $huongKhacPhuc->save();
        }
        catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($huongKhacPhuc, 200);
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'ma_huong_khac_phuc' => 'required',
            'nganh_hang_id' => 'required'
        ]);
        try {
            $huongKhacPhuc = \App\HuongKhacPhuc::find($request['id']);
            $checkHuongKhacPhuc = HuongKhacPhuc::where('ma_huong_khac_phuc',$request->get('ma_huong_khac_phuc'))
                ->first();
            if($checkHuongKhacPhuc->id !== $huongKhacPhuc->id){
                return response()->json('Ma_huong_khac_phuc da ton tai', 500);
            }
            if($huongKhacPhuc->to_chuc_id!=$request->get('to_chuc')->id){
                return response()->json('Huong khac phuc khong co trong to chuc', 404);
            }
            $huongKhacPhuc->ma_huong_khac_phuc = $request['ma_huong_khac_phuc'];
            $huongKhacPhuc->mo_ta = $request['mo_ta'];
            $huongKhacPhuc->nganh_hang_id = $request['nganh_hang_id'];

            $huongKhacPhuc->save();
        }
        catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($huongKhacPhuc, 200);
    }
    public function delete(Request $request){
        try{
            $huongKhacPhuc = \App\HuongKhacPhuc::find($request->get('id'));
            if($huongKhacPhuc->to_chuc_id = $request->get('to_chuc')->id){
                $huongKhacPhuc->delete();
            }else{
                return response()->json('Huong khac phuc khong co trong to chuc', 404);
            }
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json('success', 200);
    }

    public function filter(Request $request) {
        $result = HuongKhacPhuc::
        where(DB::raw("CONCAT(`ma_huong_khac_phuc`, ' ', `mo_ta`)"), 'LIKE', "%". $request->get('query') ."%")
            ->where('to_chuc_id', $request->get('to_chuc')->id)
            ->limit(10)->get();
        return response()->json($result, 200);
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
                $hkp = [
                    'ma_huong_khac_phuc' => $item['ma_huong_khac_phuc'],
                    'nganh_hang_id' => $item['nganh_hang_id'],
                    'to_chuc_id' => $toChuc->id,
                    'mo_ta' => $item['mo_ta']
                ];
                array_push($dataSet, $hkp);
            }
            DB::beginTransaction();
            HuongKhacPhuc::insert($dataSet);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($data, 200);
    }
}
