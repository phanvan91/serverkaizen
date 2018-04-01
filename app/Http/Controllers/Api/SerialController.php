<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DuplicateInfoException;
use App\NganhHang;
use App\SanPham;
use App\Serial;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class SerialController extends Controller
{

    public function getAll(Request $request)
    {
        $serial = Serial::all();
        return Response::json($serial, 200);


    }
    public function getPagination(Request $request)
    {
        $serial=DB::table('serials')->paginate(15);
        return Response::json($serial, 200);


    }

    public function delete(Request $request)
    {

        $this->validate($request, [
            'id' => 'required'
        ]);
        $id= $request->get('id');
        $serials = DB::table('serials')->where(array('id'=>$id))->first();
        if ($serials) {
            try {
                DB::table('serials')->where('id', '=', $id)->delete();
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
            'serial' => 'required',
            'trang_thai' => 'required',
            'model_id' => 'required',
            'san_pham_id' => 'required',
            'nganh_hang_id' => 'required',
            'ngay_san_xuat' => 'required',
            // 'ngay_xuat_kho' => 'required',
            // 'ngay_kich_hoat_bh' => 'required',
            // 'ngay_het_han' => 'required',
        ]);
        $id= $request->get('id');
        $data['serial'] = $request->get('serial');
        $data['trang_thai'] = $request->get('trang_thai');
        $data['model_id']  = $request->get('model_id');
        $data['san_pham_id']  = $request->get('san_pham_id');
        $data['nganh_hang_id']  = $request->get('nganh_hang_id');
        $data['ngay_san_xuat']  = $request->get('ngay_san_xuat');
        $data['ngay_xuat_kho']  = $request->get('ngay_xuat_kho');
        $data['ngay_kich_hoat_bh']  = $request->get('ngay_kich_hoat_bh');
        $data['ngay_het_han']  = $request->get('ngay_het_han');

        $serials = Serial::find($id);
        if($serials)
        {

            try {
                $serials->serial = $request->get('serial');
                $serials->trang_thai = $request->get('trang_thai');
                $serials->model_id = $request->get('model_id');
                $serials->san_pham_id = $request->get('san_pham_id');
                $serials->nganh_hang_id = $request->get('nganh_hang_id');
                $serials->ngay_san_xuat = $request->get('ngay_san_xuat');
                $serials->ngay_xuat_kho = $request->get('ngay_xuat_kho');
                $serials->ngay_kich_hoat_bh = $request->get('ngay_kich_hoat_bh');
                $serials->ngay_het_han = $request->get('ngay_het_han');
                $serials->updated_at = date('Y-m-d H:i:s');
                if($request->get('khach_hang_id')>0){
                    $serials->khach_hang_id =  $request->get('khach_hang_id');
                }
                $serials->save();
                $serials =DB::table('serials')
                    ->join('models', 'serials.model_id', '=', 'models.id')
                    ->join('san_phams', 'serials.san_pham_id', '=', 'san_phams.id')
                    ->join('nganh_hangs', 'serials.nganh_hang_id', '=', 'nganh_hangs.id')
                    ->where('serials.id',$id)
                    ->select('serials.*', 'models.ten as ten_model', 'san_phams.ten as ten_sp', 'nganh_hangs.ten as ten_nganh')
                    ->first();
                $today = date('Y-m-d');
                if ($today < $serials->ngay_het_han)
                {
                    $serials->het_han = false;
                }
                else{
                    $serials->het_han = true;

                }
                return Response::json($serials, 200);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }

        }
        else{
            return response()->json(['error' => 'Something wrong'], 500);

        }

    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'serial' => 'required',
            'trang_thai' => 'required',
            'model_id' => 'required',
            'san_pham_id' => 'required',
            'nganh_hang_id' => 'required',
            'ngay_san_xuat' => 'required',
            // 'ngay_xuat_kho' => 'required',
            // 'ngay_kich_hoat_bh' => 'required',
            // 'ngay_het_han' => 'required',
            // 'ngay_het_han' => 'required',


        ]);
        $data['serial'] = $request->get('serial');
        $data['to_chuc_id'] = $request->get('to_chuc')->id;
        $data['trang_thai'] = $request->get('trang_thai');
        $data['model_id']  = $request->get('model_id');
        $data['san_pham_id']  = $request->get('san_pham_id');
        $data['nganh_hang_id']  = $request->get('nganh_hang_id');
        $data['ngay_san_xuat']  = $request->get('ngay_san_xuat');
        $data['ngay_xuat_kho']  = $request->get('ngay_xuat_kho');
        $data['ngay_kich_hoat_bh']  = $request->get('ngay_kich_hoat_bh');
        $data['ngay_het_han']  = $request->get('ngay_het_han');
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        if($request->get('khach_hang_id')>0){
            $data['khach_hang_id']= $request->get('khach_hang_id');
        }
        $check_ma = DB::table('serials')->where(array('serial'=>$request->get('serial')))->first();
        if(!$check_ma)
        {
            try{
                $id=DB::table('serials')->insertGetId($data);
                $data['id']=$id;
                $serial =DB::table('serials')
                    ->join('models', 'serials.model_id', '=', 'models.id')
                    ->join('san_phams', 'serials.san_pham_id', '=', 'san_phams.id')
                    ->join('nganh_hangs', 'serials.nganh_hang_id', '=', 'nganh_hangs.id')
                    ->select('serials.*', 'models.ten as ten_model', 'san_phams.ten as ten_sp', 'nganh_hangs.ten as ten_nganh')
                    ->where('serials.id', $id)->first();
                $today = date('Y-m-d');
                if ($today < $serial->ngay_het_han)
                {
                    $serial->het_han = false;
                }
                else{
                    $serial->het_han = true;

                }

            }catch (\Exception $e){
                throw new DuplicateInfoException();
            }
            return Response::json($serial, 200);
        }
        else{
            return response()->json(['error' => 'Mã đã tồn tại'], 500);

        }

    }

    public function filter(Request $request) {
        $results = $request->get('to_chuc')
            ->serials()
            ->where('serial', 'like', '%' . $request->get('code') . '%')->get();

        return response()->json($results, 200);
    }

    public function show(Request $request, $id) {
        $result = $request->get('to_chuc')->serials()->where('id', $id)->with(['nganhHang', 'sanPham', 'model'])->first();
        $today = date('Y-m-d');
        if ($today < $result->ngay_het_han)
        {
            $result->het_han = false;
        }
        else{
            $result->het_han = true;

        }


        return response()->json($result, 200);
    }

     public function uploadCsv(Request $request) {
            $data_string =$request->get('data');
            $to_chuc_id=$request->get('to_chuc_id');
             $to_chuc_id=str_replace('"', '', $to_chuc_id);
         $data_string=str_replace('"', '', $data_string);

         $data_insert =array();
         $data_string = explode("\n", $data_string);
             $i=0;
         foreach ($data_string as $value){
                 $i++;
                 if($i >1)
                 {
                     $row = explode(",",$value);
                     $ma= $row[0];
                     $trang_thai=  $row[1];
                     $ma_model=  $row[2];
                     $ma_sp=  $row[3];
                     $nganh=  $row[4];
                     $ngay_sx=$row[5];
                     $ngay_xk=$row[6];
                     $ngay_bh=$row[7];
                     $ngay_het_han=$row[8];
                     if ($ma !="" && $ma != 'Serial')
                     {

                         $check_serials = DB::table('serials')->where('serial',$ma)->count();
                         if($check_serials <= 0)
                         {
                             $data['serial']=$ma;
                             $data['to_chuc_id']=$to_chuc_id;
                             if ($ngay_sx != "")
                             {
                                 $ngay_sx = date( 'Y-m-d H:i:s', strtotime( $ngay_sx ) );
                                 $data['ngay_san_xuat']=$ngay_sx;


                             }
                             if($ngay_xk != "")
                             {
                                 $ngay_xk = date( 'Y-m-d H:i:s', strtotime( $ngay_sx ) );
                                 $data['ngay_xuat_kho']=$ngay_xk;

                             }
                             if ($ngay_bh != "")
                             {
                                 $ngay_bh = date( 'Y-m-d H:i:s', strtotime( $ngay_bh ) );
                                 $data['ngay_kich_hoat_bh']=$ngay_bh;

                             }
                             if ($ngay_het_han) {
                                 $ngay_het_han = strtotime( $ngay_sx );
                                 $ngay_het_han = date( 'Y-m-d H:i:s', strtotime( $ngay_het_han ) );
                                 $data['ngay_het_han']=$ngay_het_han;

                             }

                             $data['trang_thai'] = 1;

                             $check_model =  DB::table('models')->where(array('ma'=>$ma_model,'to_chuc_id'=>$to_chuc_id))->first();
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


                                 $data_model['ma']=$ma;
                                 $data_model['ten']=$ma;
                                 $data_model['san_pham_id'] = $id_sp;
                                 $data_model['to_chuc_id'] = $to_chuc_id;
                                 $data_model['kich_hoat']  = 1;
                                 $data_model['created_at']=date('Y-m-d H:i:s');
                                 $data_model['updated_at']=date('Y-m-d H:i:s');

                                 $result = null;
                                 try{
                                     $id_model=DB::table('models')->insertGetId($data_model);

                                 }catch (\Exception $e){
                                     throw new DuplicateInfoException();
                                 }
                                 //
                             }
                             else{
                                 $id_model = $check_model->id;
                                 $san_phams= DB::table('san_phams')->where(array('ma'=>$ma_sp,'to_chuc_id'=>$to_chuc_id))->first();
                                 $nganh_hang = DB::table('nganh_hangs')->where(array('ma'=>$nganh,'to_chuc_id'=>$to_chuc_id))->first();
                                 $data['model_id']=$id_model;
                                 $data['san_pham_id']=$san_phams->id;
                                 $data['nganh_hang_id']=$nganh_hang->id;
                                 $data['created_at']=date('Y-m-d H:i:s');

                             }
                             DB::table("serials")->insert($data);

                             $data_insert[]=$data;

                         }

                     }

                 }
            }
//         return response()->json($data_insert, 200);

         if(count($data_insert)>0)
         {

             return response()->json($data_insert, 200);
         }
         else{
             return response()->json(['status' => 'Exit data'], 200);

         }




     }
     public function uploadExcel(Request $request) {
        $data_excel =$request->get('data');
         $to_chuc_id=$request->get('to_chuc')->id;
         $data_insert=array();
         for ($i=0;$i<count($data_excel);$i++) {
             $i++;
             if ($i > 2) {
                 $ma = $data_excel[$i][6];
                 $trang_thai = 1;
                 $ma_model = $data_excel[$i][10];
                 $ngay_sx = $data_excel[$i][1];
                 $day = substr($ngay_sx,0,2);
                 $month = substr($ngay_sx,3,2);
                 $year = '20' . substr($ngay_sx,6,2);
                 $output_date = $year . "/" . $month . "/" . $day ;
                 if ($output_date) {
                     $changedDate = date( 'Y-m-d H:i:s', strtotime( $output_date ) );
                     $data['ngay_san_xuat'] = $changedDate;


                 }
                 $data['to_chuc_id']=$to_chuc_id;

                 $data['serial']=$ma;
                 $data['trang_thai']=$trang_thai;
                 $data['created_at'] = date('Y-m-d H:i:s');

                 $check_serials = DB::table('serials')->where('serial',$ma)->count();
                 if($check_serials <= 0) {
                     $check_model = DB::table('models')->where(array('ma' => $ma_model))->first();
                     if ($check_model) {
                         $id_model = $check_model->id;
                         $san_phams = DB::table('san_phams')->where(array('id' => $check_model->san_pham_id))->first();
                         $data['model_id'] = $id_model;
                         $data['san_pham_id'] = $check_model->san_pham_id;
                         if($san_phams){
                             $data['nganh_hang_id'] = $san_phams->nganh_hang_id;
                             DB::table("serials")->insert($data);
                             $data_insert[] = $data;

                         }

                     }


                 }




             }
         }

                  return response()->json($data_insert, 200);

     }
     public function pbhSerial (Request $request)
     {
         $serial_id = 1;
         $phb = DB::table('phieu_sua_chuas')->where('serial_id',$serial_id)->get();

         foreach ( $phb as $value)
         {
             $arr[$value->id] = 'PBH-'.$value->id;
         }
         $arr = json_encode($arr);
         $data['PBH']=$arr;

         DB::table('serials')
             ->where('id', $serial_id)
             ->update($data);
         return Response::json($arr, 200);

     }
     public function serialKhachHang (Request $request)
     {

         $khach_hang_id = $request->get('khach_hang_id');
         $serial = DB::table('phieu_sua_chuas')
             ->selectRaw('DISTINCT phieu_sua_chuas.serial_id')
             ->where('phieu_sua_chuas.khach_hang_id',$khach_hang_id)->get();
         foreach ($serial as $value)
         {
             $serials = DB::table('serials')->where('id',$value->serial_id)->first();
             $value->serial = $serials->serial;

         }
         return Response::json($serial, 200);

     }
    public function search(Request $request) {

        $serial =DB::table('serials')
            ->where('serial', 'like', '%' . $request->get('query') . '%')
            ->limit(10)
            ->orderByDesc('id')
            ->get();
        return response()->json($serial, 200);
    }

    public function getSerial(Request $request) {

        $serial =DB::table('serials')
            ->join('models', 'serials.model_id', '=', 'models.id')
            ->join('san_phams', 'serials.san_pham_id', '=', 'san_phams.id')
            ->join('nganh_hangs', 'serials.nganh_hang_id', '=', 'nganh_hangs.id')

            ->where('serials.id', $request->get('id'))
            ->select('serials.*', 'models.ten as ten_model', 'san_phams.ten as ten_sp', 'nganh_hangs.ten as ten_nganh')
            ->first();
        $today = date('Y-m-d');
        if ($today < $serial->ngay_het_han)
        {
            $serial->het_han = false;
        }
        else{
            $serial->het_han = true;

        }
        return response()->json($serial, 200);
    }
    public function getSerialbyMa(Request $request) {
        $data = array();
        $serial =DB::table('serials')
            ->join('models', 'serials.model_id', '=', 'models.id')
            ->join('san_phams', 'serials.san_pham_id', '=', 'san_phams.id')
            ->join('nganh_hangs', 'serials.nganh_hang_id', '=', 'nganh_hangs.id')

            ->where('serials.serial', $request->get('key_search'))
            ->select('serials.*', 'models.ten as ten_model', 'san_phams.ten as ten_sp', 'nganh_hangs.ten as ten_nganh')
            ->first();
        $today = date('Y-m-d');
        if($serial)
        {
            if ($today < $serial->ngay_het_han)
            {
                $serial->het_han = false;
            }
            else{
                $serial->het_han = true;

            }
            $customers =DB::table('khach_hangs')
                ->where('id', $serial->khach_hang_id)
                ->first();
            $serial->customer =$customers;

            $psc  =DB::table('phieu_sua_chuas')
                ->where('serial_id', $serial->id)
                ->get();
            $serial->psc =$psc;


            return response()->json($serial, 200);


        }
        else{
            $customers =DB::table('khach_hangs')
                ->where('dien_thoai', $request->get('key_search'))
                ->first();
            if($customers) {
                $serials =DB::table('serials')
                    ->join('models', 'serials.model_id', '=', 'models.id')
                    ->join('san_phams', 'serials.san_pham_id', '=', 'san_phams.id')
                    ->join('nganh_hangs', 'serials.nganh_hang_id', '=', 'nganh_hangs.id')

                    ->where('serials.khach_hang_id', $customers->id)
                    ->select('serials.*', 'models.ten as ten_model', 'san_phams.ten as ten_sp', 'nganh_hangs.ten as ten_nganh')
                    ->get();
                $today = date('Y-m-d');
                foreach ($serials as $value) {
                    if ($today < $value->ngay_het_han)
                    {
                        $value->het_han = false;
                    }
                    else{
                        $value->het_han = true;

                    }
                }
                $data['serials'] = $serials;
                $data['customer']=$customers;
                $data['show_list']=true;

            }

            return response()->json($data, 200);

        }


    }
    public function kichhoatBH(Request $request)
    {

        $serial = Serial::find($request->get('id'));

        $today = date('Y-m-d');
        $ngay_het_han =  date("Y-m-d", strtotime("+ 730 day"));

        if($serial)
        {
            $serial->ngay_kich_hoat_bh = $today;
            $serial->ngay_het_han = $ngay_het_han;
            $serial->save();
            $serial =DB::table('serials')
                ->join('models', 'serials.model_id', '=', 'models.id')
                ->join('san_phams', 'serials.san_pham_id', '=', 'san_phams.id')
                ->join('nganh_hangs', 'serials.nganh_hang_id', '=', 'nganh_hangs.id')
                ->where('serials.id', $request->get('id'))
                ->select('serials.*', 'models.ten as ten_model', 'san_phams.ten as ten_sp', 'nganh_hangs.ten as ten_nganh')
                ->first();

            if ($today < $serial->ngay_het_han)
            {
                $serial->het_han = false;
            }
            else{
                $serial->het_han = true;

            }
            $customers =DB::table('khach_hangs')
                ->where('id', $serial->khach_hang_id)
                ->first();
            $serial->customer =$customers;



        }
        return response()->json($serial, 200);


    }


}
