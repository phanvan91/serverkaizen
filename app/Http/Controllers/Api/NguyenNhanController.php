<?php

namespace App\Http\Controllers\Api;

use App\NguyenNhan;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;
use Response;

class NguyenNhanController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            $nguyenNhanHuHong = \App\NguyenNhan::where('to_chuc_id', $request->get('to_chuc')->id)->get();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($nguyenNhanHuHong, 200);
    }
    public function getPagination(Request $request)
    {
        $nguyen_nhans=DB::table('nguyen_nhans')
            ->where('to_chuc_id',$request->get('to_chuc')->id)
            ->paginate(15);
        return Response::json($nguyen_nhans, 200);


    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'ma_nguyen_nhan' => 'required|unique:nguyen_nhans',
            'nganh_hang_id' => 'required',
        ]);
        try {
            $nguyenNhanHuHong = new \App\NguyenNhan();
            $nguyenNhanHuHong->ma_nguyen_nhan = $request['ma_nguyen_nhan'];
            $nguyenNhanHuHong->mo_ta = $request['mo_ta'];
            $nguyenNhanHuHong->nganh_hang_id = $request['nganh_hang_id'];
            $nguyenNhanHuHong->to_chuc_id = $request->get('to_chuc')->id;
            $nguyenNhanHuHong->save();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($nguyenNhanHuHong, 200);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'ma_nguyen_nhan' => 'required',
            'nganh_hang_id' => 'required',
            'mo_ta' => 'required',

        ]);
        try {
            $nguyenNhanHuHong = \App\NguyenNhan::find($request['id']);
            $checkNguyenNhan = NguyenNhan::where('ma_nguyen_nhan',$request->get('ma_nguyen_nhan'))->first();

            if($checkNguyenNhan->id !== $nguyenNhanHuHong->id){
                return response()->json('Ma_nguyen_nhan da ton tai', 500);
            }

            if ($nguyenNhanHuHong->to_chuc_id != $request->get('to_chuc')->id) {
                return response()->json('Nguyen nhan khong co trong to chuc', 404);
            }
            $nguyenNhanHuHong->ma_nguyen_nhan = $request['ma_nguyen_nhan'];
            $nguyenNhanHuHong->mo_ta = $request['mo_ta'];
            $nguyenNhanHuHong->nganh_hang_id = $request['nganh_hang_id'];

            $nguyenNhanHuHong->save();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($nguyenNhanHuHong, 200);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        try {
            $nguyenNhanHuHong = \App\NguyenNhan::find($request->get('id'));
            if ($nguyenNhanHuHong->to_chuc_id = $request->get('to_chuc')->id) {
                $nguyenNhanHuHong->delete();
            } else {
                return response()->json('Nguyen nhan khong co trong to chuc', 404);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        return response()->json('success', 200);
    }

    public function filter(Request $request)
    {
        $result = NguyenNhan::
        where(DB::raw("CONCAT(`ma_nguyen_nhan`, ' ', `mo_ta`)"), 'LIKE', "%" . $request->get('query') . "%")
            ->where('to_chuc_id', $request->get('to_chuc')->id)
            ->limit(10)->get();
        if (count($result) <= 0)
        {
            $result =DB::table('nguyen_nhans')
                ->where('to_chuc_id', $request->get('to_chuc')->id)
                ->limit(10)->get();
        }
        return response()->json($result, 200);
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'data' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        try {
            $data = $request->get('data');
            $dataSet = [];
            foreach ($data as $item) {
                $nguyenNhan = [
                    'ma_nguyen_nhan' => $item['ma_nguyen_nhan'],
                    'nganh_hang_id' => $item['nganh_hang_id'],
                    'to_chuc_id' => $toChuc->id,
                    'mo_ta' => $item['mo_ta']
                ];
                array_push($dataSet, $nguyenNhan);
            }
            DB::beginTransaction();
            NguyenNhan::insert($dataSet);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($data, 200);
    }
}
