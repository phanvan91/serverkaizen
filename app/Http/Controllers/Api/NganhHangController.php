<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DuplicateInfoException;
use App\NganhHang;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class NganhHangController extends Controller
{
    public function getAll(Request $request)
    {
        $nganh_hangs = DB::table('nganh_hangs')->where('to_chuc_id',$request->get('to_chuc')->id)->get();
        if($nganh_hangs)
        {
            return response()->json($nganh_hangs, 200);

        }
        else{
            return response()->json('Not found', 404);

        }

    }


    public function getPagination(Request $request)
    {
        $nganh_hangs=DB::table('nganh_hangs')->where('to_chuc_id',$request->get('to_chuc')->id)->paginate(15);
        return Response::json($nganh_hangs, 200);


    }


    public function delete(Request $request)
    {
        $this->validate($request, [
            'to_chuc_id' => 'required',
            'id' => 'required'
        ]);
        $id= $request->get('id');
        $to_chuc_id=$request->get('to_chuc')->id;

        $nganh_hang = DB::table('nganh_hangs')->where(array('id'=>$id,'to_chuc_id'=>$to_chuc_id))->first();

        if ($nganh_hang) {
            try {
                DB::table('nganh_hangs')->where('id', '=', $id)->delete();
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
            'ma'=> 'required',
            'ten'=> 'required',
            'kich_hoat'=> 'required',
        ]);
        $id= $request->get('id');
        $tochuc =$request->get('to_chuc')->id;
        $data['ma'] = $request->get('ma');
        $data['ten'] = $request->get('ten');
        $data['kich_hoat']  = $request->get('kich_hoat');
        $data['updated_at']=date('Y-m-d H:i:s');

        $nganh_hang = NganhHang::find($id);
        if($nganh_hang)
        {

            try {
                $nganh_hang->ma = $request->get('ma');
                $nganh_hang->ten = $request->get('ten');
                $nganh_hang->kich_hoat = $request->get('kich_hoat');
                $nganh_hang->updated_at = date('Y-m-d H:i:s');
                $nganh_hang->save();
                return Response::json($nganh_hang, 200);
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
            'ma'=> 'required',
            'ten'=> 'required',
            'kich_hoat'=> 'required',
        ]);
        $data = $request->all();
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        $check_ma = DB::table('nganh_hangs')->where(array('ma'=>$request->get('ma')))->first();
        if(!$check_ma)
        {
            try{
                $id=DB::table('nganh_hangs')->insertGetId($data);
                $data['id']=$id;
            }catch (\Exception $e){
                throw new DuplicateInfoException();
            }
            return Response::json($data, 200);
        }
        else{
            return response()->json('Mã này đã tồn tại', 500);

        }


    }

}
