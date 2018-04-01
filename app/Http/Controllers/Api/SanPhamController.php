<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DuplicateInfoException;
use App\NganhHang;
use App\SanPham;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class SanPhamController extends Controller
{
    public function getAll(Request $request)
    {

        $sanpham = DB::table('san_phams')->where('to_chuc_id',$request->get('to_chuc')->id)->get();
        if($sanpham)
        {
            return response()->json($sanpham, 200);

        }
        else{
            return response()->json('Not found', 404);

        }


    }
    public function getbyNganh(Request $request)
    {
        $this->validate($request, [
            'nganh' => 'required',
        ]);
        $nganh_id= $request->get('nganh');
        $sanpham =  DB::table('san_phams')->where('nganh_hang_id', '=',$nganh_id)->get();
        return Response::json($sanpham, 200);


    }
    public function getPagination(Request $request)
    {
        $sanpham=DB::table('san_phams')->where('to_chuc_id',$request->get('to_chuc')->id)->paginate(15);
        return Response::json($sanpham, 200);


    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'to_chuc_id' => 'required',
            'id' => 'required'
        ]);
        $id= $request->get('id');
        $to_chuc_id=$request->get('to_chuc')->id;

        $san_phams = DB::table('san_phams')->where(array('id'=>$id,'to_chuc_id'=>$to_chuc_id))->first();

        if ($san_phams) {
            try {
                DB::table('san_phams')->where('id', '=', $id)->delete();
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
            'nganh_hang_id' => 'required',

        ]);
        $id= $request->get('id');
        $tochuc =$request->get('to_chuc')->id;
        $data['ma'] = $request->get('ma');
        $data['ten'] = $request->get('ten');
        $data['kich_hoat']  = $request->get('kich_hoat');
        $data['nganh_hang_id']  = $request->get('nganh_hang_id');
        $san_phams = SanPham::find($id);
        if($san_phams)
        {

            try {
                $san_phams->ma = $request->get('ma');
                $san_phams->ten = $request->get('ten');
                $san_phams->kich_hoat = $request->get('kich_hoat');
                $san_phams->nganh_hang_id = $request->get('nganh_hang_id');
                $san_phams->updated_at = date('Y-m-d H:i:s');
                $san_phams->save();
                return Response::json($san_phams, 200);
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
        $this->validate($request, [
            'ma' => 'required',
            'ten' => 'required',
            'nganh_hang_id' => 'required',

        ]);
        $data['to_chuc_id']  =$request->get('to_chuc')->id;
        $data['ma'] = $request->get('ma');
        $data['ten'] = $request->get('ten');
        $data['kich_hoat']  = $request->get('kich_hoat');
        $data['nganh_hang_id']  = $request->get('nganh_hang_id');
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');

        $check_ma = DB::table('san_phams')->where(array('ma'=>$request->get('ma')))->first();
        if(!$check_ma)
        {
            try{
                $id=DB::table('san_phams')->insertGetId($data);
                $data['id']=$id;
            }catch (\Exception $e){
                throw new DuplicateInfoException();
            }
            return Response::json($data, 200);
        }
        else{
            return response()->json('Mã đã tồn tại', 404);

        }
    }

}
