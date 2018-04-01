<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DuplicateInfoException;
use App\NganhHang;
use App\SanPham;
use App\Models;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class ModelController extends Controller
{
    public function getAll(Request $request)
    {
        $models = DB::table('models')->where('to_chuc_id',$request->get('to_chuc')->id)->get();
        if($models)
        {
            return response()->json($models, 200);

        }
        else{
            return response()->json('Not found', 404);

        }

    }
    public function getPagination(Request $request)
    {
        $model=DB::table('models')->where('to_chuc_id',$request->get('to_chuc')->id)
            ->paginate(15);
        if($model)
        {
            return response()->json($model, 200);

        }
        else{
            return response()->json('Not found', 404);

        }

    }

    public function getbyModel(Request $request)
    {
        $model_id= $request->get('model');
        $model =  DB::table('models')->where('san_pham_id', '=',$model_id)->get();
        return Response::json($model, 200);


    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'to_chuc_id' => 'required',
            'id' => 'required'
        ]);
        $id= $request->get('id');
        $to_chuc_id=$request->get('to_chuc')->id;

        $models= DB::table('models')->where(array('id'=>$id,'to_chuc_id'=>$to_chuc_id))->first();

        if ($models) {
            try {
                DB::table('models')->where('id', '=', $id)->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 200);
            }
        } else {
            return response()->json('Not found', 404);
        }


    }

    public function uploadCsv(Request $request)
    {
        $data_string =$request->get('data');
        $to_chuc_id=$request->get('to_chuc_id');
        $to_chuc_id=str_replace('"', '', $to_chuc_id);
        $data_string=str_replace('"', '', $data_string);

        $data_insert =array();
        $data_string = explode("\n", $data_string);
        $i=0;
        foreach ($data_string as $value){
            $i++;
            if($i >7)
            {
                $row = explode(",",$value);
                $ma= $row[1];
                $ten= $row[2];
                $ma_sp= $row[3];
                $nganh= $row[4];
                if($ma !="" && $ten !="" && $ma_sp !="" && $nganh !="" && $to_chuc_id !="")
                {
                    $check_model =  DB::table('models')->where(array('ma'=>$ma,'to_chuc_id'=>$to_chuc_id))->first();
                    if ( !$check_model)
                    {
                        $san_phams= DB::table('san_phams')->where(array('ma'=>$ma_sp,'to_chuc_id'=>$to_chuc_id))->first();
                        if($san_phams)
                        {
                            $id_sp =$san_phams->id;
                        }
                        else{
                            $nganh_hang = DB::table('nganh_hangs')->where(array('ma'=>$nganh,'to_chuc_id'=>$to_chuc_id))->first();
                            if($nganh_hang)
                            {
                                $nganh_hang_id = $nganh_hang->id;
                            }
                            else{
                                $data_nganh['ma'] =$nganh;
                                $data_nganh['ten'] =$nganh;
                                $data_nganh['kich_hoat']  = 1;
                                $data_nganh['to_chuc_id'] =$to_chuc_id;
                                $data_nganh['created_at']=date('Y-m-d H:i:s');
                                $data_nganh['updated_at']=date('Y-m-d H:i:s');

                                $result = null;
                                try{
                                    $id=DB::table('nganh_hangs')->insertGetId($data_nganh);
                                    $nganh_hang_id = $id;
                                }catch (\Exception $e){
                                    throw new DuplicateInfoException();
                                }

                            }
                            $data_sp['to_chuc_id']  =$to_chuc_id;
                            $data_sp['ma'] =$ma_sp;
                            $data_sp['ten'] =$ma_sp;
                            $data_sp['kich_hoat']  = 1;
                            $data_sp['nganh_hang_id']  = $nganh_hang_id;
                            $data_sp['created_at']=date('Y-m-d H:i:s');
                            $data_sp['updated_at']=date('Y-m-d H:i:s');

                            $result = null;
                            try{
                                $id_sp =DB::table('san_phams')->insertGetId($data_sp);
                            }catch (\Exception $e){
                                throw new DuplicateInfoException();
                            }
                        }

                        $data['ma']=$ma;
                        $data['ten']=$ten;
                        $data['san_pham_id'] = $id_sp;
                        $data['to_chuc_id'] = $to_chuc_id;
                        $data['kich_hoat']  = 1;
                        $data['created_at']=date('Y-m-d H:i:s');
                        $data['updated_at']=date('Y-m-d H:i:s');
                        $data_insert[]=$data;
                    }

                }


            }
        }
        if(count($data_insert)>0)
        {
            try{
            DB::table("models")->insert($data_insert);
                return response()->json($data_insert, 200);

            }catch (\Exception $e){
                throw new DuplicateInfoException();
            }

        }
        else{
            return response()->json('Exit data', 404);

        }



    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'to_chuc_id' => 'required',
            'ma' => 'required',
            'ten' => 'required',
            'san_pham_id' => 'required',

        ]);
        $id= $request->get('id');
        $tochuc =$request->get('to_chuc')->id;
        $data['ma'] = $request->get('ma');
        $data['ten'] = $request->get('ten');
        $data['san_pham_id'] = $request->get('san_pham_id');
        $data['kich_hoat']  = $request->get('kich_hoat');
        $models = Models::find($id);
        if($models)
        {

            try {
                $models->ma = $request->get('ma');
                $models->ten = $request->get('ten');
                $models->kich_hoat = $request->get('kich_hoat');
                $models->san_pham_id = $request->get('san_pham_id');
                $models->updated_at = date('Y-m-d H:i:s');
                $models->save();
                return Response::json($models, 200);
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
            'to_chuc_id' => 'required',
            'ma' => 'required',
            'ten' => 'required',
            'san_pham_id' => 'required',
            'kich_hoat' => 'required',

        ]);
        $data['ma'] = $request->get('ma');
        $data['ten'] = $request->get('ten');
        $data['san_pham_id'] = $request->get('san_pham_id');
        $data['to_chuc_id'] =$request->get('to_chuc')->id;
        $data['kich_hoat']  = $request->get('kich_hoat');
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        $check_ma = DB::table('models')->where(array('ma'=>$request->get('ma')))->first();
        if(!$check_ma)
        {
            try{
                $id=DB::table('models')->insertGetId($data);
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
