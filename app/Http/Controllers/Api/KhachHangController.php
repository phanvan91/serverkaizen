<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DuplicateInfoException;
use App\KhachHang;
use App\Serial;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class KhachHangController extends Controller
{
    public function getAll(Request $request)
    {
        $khach_hangs = DB::table('khach_hangs')->where('to_chuc_id',$request->get('to_chuc')->id)->get();
        if($khach_hangs)
        {
            return response()->json($khach_hangs, 200);

        }
        else{
            return response()->json('Not found', 404);

        }

    }


    public function getPagination(Request $request)
    {
        $khach_hangs=DB::table('khach_hangs')->where('to_chuc_id',$request->get('to_chuc')->id)->paginate(15);
        foreach ($khach_hangs as $value)
        {
            $id_khach_hang = $value->id;
            $serial = DB::table('phieu_sua_chuas')
                ->join('serials', 'phieu_sua_chuas.serial_id', '=', 'serials.id')
                ->where('phieu_sua_chuas.khach_hang_id',$id_khach_hang)->get();
            $value->serials = $serial;

        }
        return Response::json($khach_hangs, 200);


    }


    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $id= $request->get('id');
        $to_chuc_id=$request->get('to_chuc')->id;

        $khach_hangs = DB::table('khach_hangs')->where(array('id'=>$id,'to_chuc_id'=>$to_chuc_id))->first();

        if ($khach_hangs) {
            try {
                DB::table('khach_hangs')->where('id', '=', $id)->delete();
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
            'ma' => 'required',
            'ten' => 'required',
            'loai' => 'required',
            'dien_thoai' => 'required',
//            'email' => 'required',
            'tinh_tp' => 'required',
            'quan_huyen' => 'required',
            'dia_chi' => 'required',
            'to_chuc_id' => 'required',
        ]);
//        $data = $request->all();
        $id= $request->get('id');
        $tochuc =$request->get('to_chuc')->id;
        $data['id'] = $request->get('id');
        $data['ma'] = $request->get('ma');
        $data['ten'] = $request->get('ten');
        $data['loai'] = $request->get('loai');
        $data['dien_thoai'] = $request->get('dien_thoai');
        $data['email'] = $request->get('email');
        $data['tinh_tp'] = $request->get('tinh_tp');
        $data['quan_huyen'] = $request->get('quan_huyen');
        $data['dia_chi'] = $request->get('dia_chi');
        $data['to_chuc_id'] =$request->get('to_chuc')->id;
        $data['updated_at']=date('Y-m-d H:i:s');

        $khach_hangs = KhachHang::find($id);
        if($khach_hangs)
        {

            try {
                $khach_hangs->ma = $request->get('ma');
                $khach_hangs->ten = $request->get('ten');
                $khach_hangs->loai = $request->get('loai');
                $khach_hangs->dien_thoai = $request->get('dien_thoai');
                if($request->get('email')) { $khach_hangs->email = $request->get('email'); }
                $khach_hangs->tinh_tp = $request->get('tinh_tp');
                $khach_hangs->quan_huyen = $request->get('quan_huyen');
                $khach_hangs->phuong_xa = $request->get('phuong_xa');
                $khach_hangs->dia_chi = $request->get('dia_chi');
                $khach_hangs->updated_at = date('Y-m-d H:i:s');
                $khach_hangs->save();
                return Response::json($khach_hangs, 200);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }

        }
        else{
            return response()->json('Something wrong', 404);

        }

    }
    public function create(Request $request)
    {
//        $data = $request->all();
        $this->validate($request, [
            'ma' => 'required',
            'ten' => 'required',
            'loai' => 'required',
            'dien_thoai' => 'required',
//            'email' => 'required',
            'tinh_tp' => 'required',
            'quan_huyen' => 'required',
            'dia_chi' => 'required',
            'to_chuc_id' => 'required',
        ]);
        $data['ma'] = $request->get('ma');
        $data['ten'] = $request->get('ten');
        $data['loai'] = $request->get('loai');
        $data['dien_thoai'] = $request->get('dien_thoai');
        if($request->get('email')) { $data['email'] = $request->get('email'); }
        $data['tinh_tp'] = $request->get('tinh_tp');
        $data['quan_huyen'] = $request->get('quan_huyen');
        $data['phuong_xa'] = $request->get('phuong_xa');
        $data['dia_chi'] = $request->get('dia_chi');
        $data['to_chuc_id'] =$request->get('to_chuc')->id;
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');

        $check_ma = DB::table('khach_hangs')->where(array('ma'=>$request->get('ma')))->first();
        if(!$check_ma)
        {
            try{
                $id=DB::table('khach_hangs')->insertGetId($data);
                $data['id']=$id;

            }catch (\Exception $e){
                throw new DuplicateInfoException();
            }
            return Response::json($data, 200);
        }
        else{
            return response()->json('Mã đã tồn tại', 500);

        }
    }

    public function filter(Request $request) {

        $toChuc = $request->get('to_chuc');
        $customers = $toChuc->danhSachKhachHang()->where('ten', 'like', '%' . $request->get('name') . '%')->get();

        return response()->json($customers, 200);
    }
    public function search(Request $request) {

            $customers =DB::table('khach_hangs')
                ->where('dien_thoai', 'like', '%' . $request->get('query') . '%')
                ->orWhere('dia_chi', 'like', '%' . $request->get('query') . '%')

                ->limit(10)
                ->orderByDesc('id')
                ->get();
        return response()->json($customers, 200);
    }
    public function show_customer(Request $request) {

        $customers =DB::table('khach_hangs')
            ->where('id', $request->get('id'))->first();
        return response()->json($customers, 200);
    }

    public function show(Request $request, $id) {
        $toChuc = $request->get('to_chuc');
        $customers = $toChuc->danhSachKhachHang()->where('id', $id)->first();

        return response()->json($customers, 200);
    }

    public function getMaTuSinhKH(){
        $maKhoiTaoKH = 'KH0000000';
        try{
            $maKhachHangMax = KhachHang::max('ma');
            $maKhachHangMax = $maKhachHangMax? $maKhachHangMax : $maKhoiTaoKH;
            $maTuSinh = (string) intval(substr($maKhachHangMax,2)) + 1;
            $lengthNumber = 7 - strlen($maTuSinh);
            for ($i = 0; $i < $lengthNumber; $i++) {
                $maTuSinh = '0'.$maTuSinh;
            }
            $maTuSinh = 'KH'.$maTuSinh;
            return response()->json(['current' => $maKhachHangMax,'ma' => $maTuSinh], 200);
        }catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function createPublic(Request $request)
    {
        $this->validate($request, [
            'ten' => 'required',
            'dien_thoai' => 'required',
            'tinh_tp' => 'required',
            'quan_huyen' => 'required',
            'dia_chi' => 'required',
            'to_chuc_id' => 'required',
        ]);
        $data['ma'] = 'KH0000000'.rand(10000,999999999);
        $data['ten'] = $request->get('ten');
        $data['loai'] = 1;
        $data['dien_thoai'] = $request->get('dien_thoai');
        if($request->get('email')) { $data['email'] = $request->get('email'); }
        $data['tinh_tp'] = $request->get('tinh_tp');
        $data['quan_huyen'] = $request->get('quan_huyen');
        $data['phuong_xa'] = $request->get('phuong_xa');
        $data['dia_chi'] = $request->get('dia_chi');
        $data['to_chuc_id'] =$request->get('to_chuc_id');
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');

        $check_ma = DB::table('khach_hangs')->where(array('ma'=>$request->get('ma')))->first();
        if(!$check_ma)
        {

            try{
                $id=DB::table('khach_hangs')->insertGetId($data);
                $data['id']=$id;
                                if($request->get('serial_id') > 0)
                {
                    $serial = Serial::find($request->get('serial_id'));
                    if($serial){
                        $serial->khach_hang_id = $id;
                        $serial->save();
                    }

                }

            }catch (\Exception $e){
                throw new DuplicateInfoException();
            }
            return Response::json($data, 200);
        }
        else{
            return response()->json('Mã đã tồn tại', 500);

        }
    }

}
