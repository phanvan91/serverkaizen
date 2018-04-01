<?php

namespace App\Http\Controllers\Api;

use App\ToChuc;
use App\TramBaoHanh;
use App\Kho;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class TramBaoHanhController extends Controller
{

    public function all(Request $request) {

        $list = $request->get('to_chuc')->tramBaoHanh;

        return response()->json($list, 200);
    }
    public function trambyTinh(Request $request) {

        $tramBaoHanh=DB::table('tram_bao_hanhs')
            ->where(array('tinh'=>$request->get('tinh'),'to_chuc_id'=>$request->get('to_chuc')->id))
            ->get();
        return response()->json($tramBaoHanh, 200);
    }

    public function pagination(Request $request) {

        $tramBaoHanh=DB::table('tram_bao_hanhs')
            ->where(array('to_chuc_id'=>$request->get('to_chuc')->id))
            ->paginate(15);
        return response()->json($tramBaoHanh, 200);
    }

    /**
     * @SWG\Post(
     *      path="/tram-bao-hanh/create",
     *      operationId="tramBaoHanhCreate",
     *      tags={"TramBaoHanh"},
     *      summary="Create trung tam bao hanh",
     *      description="Return TramBaoHanh object",
     *      consumes={"application/x-www-form-urlencoded"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="to_chuc_id",
     *          description="To chuc id",
     *          required=true,
     *          in="formData",
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="ma",
     *          description="Ma tram",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="ten",
     *          description="Ten tram",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="dia_chi",
     *          description="Dia chi tram",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="to_chuc_id",
     *          description="To chuc id",
     *          required=true,
     *          in="formData",
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="cong_ty_id",
     *          description="Cong ty id",
     *          required=true,
     *          in="formData",
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="trung_tam_bao_hanh_id",
     *          description="Trung tam bao hanh id",
     *          required=true,
     *          in="formData",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @SWG\Response(response=500, description="Internal server error."),
     *       security={
     *           {"auth": {}}
     *       }
     *     )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) {
        $this->validate($request, [
            'ma' => 'required|unique:tram_bao_hanhs',
            'ten' => 'required',
            'dia_chi' => 'required',
            'cong_ty_id' => 'required',
            'trung_tam_bao_hanh_id' => 'required',
            'loai_tram' => 'required'
        ]);

        $tTramBaoHanh = $request->get('to_chuc')
            ->congTy()
            ->where('id', $request->get('cong_ty_id'))
            ->first()
            ->trungTamBaoHanh()
            ->where('id', $request->get('trung_tam_bao_hanh_id'))
            ->first();

        try {
            DB::beginTransaction();
            $tramBaoHanhId = TramBaoHanh::insertGetId([
                'ma' => $request->get('ma'),
                'ten' => $request->get('ten'),
                'dia_chi' => $request->get('dia_chi'),
                'cong_ty_id' => $request->get('cong_ty_id'),
                'to_chuc_id' => $request->get('to_chuc')->id,
                'loai_tram' => $request->get('loai_tram'),
                'trung_tam_bao_hanh_id' => $tTramBaoHanh->id,
                'so_dien_thoai' =>  $request->get('so_dien_thoai'),
                'don_vi_van_chuyen' => $request->get('don_vi_van_chuyen'),
                'nguoi_dai_dien' => $request->get('nguoi_dai_dien')
            ]);
            for ( $loaiKho = 1; $loaiKho <= 3; $loaiKho++){
                Kho::insert([
                    'ten_kho' => 'Kho ' . $request->get('ten'),
                    'ma_kho' => 'KH-' . $request->get('ma'),
                    'loai_kho' => $loaiKho,
                    'tram_bao_hanh_id' => $tramBaoHanhId,
                    'trung_tam_bao_hanh_id' => $tTramBaoHanh->id,
                    'to_chuc_id' => $request->get('to_chuc')->id,
                    'cong_ty_id' =>  $request->get('cong_ty_id'),
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
        $tramBaoHanh = TramBaoHanh::find($tramBaoHanhId);
        return response()->json($tramBaoHanh, 200);
    }
    public function import(Request $request) {
        $this->validate($request, [
            'cong_ty_id' => 'required',
            'trung_tam_bao_hanh_id' => 'required',
            'data' => 'required',
            'ma' => 'required'
        ]);
        $data = $request->get('data');
        $tTramBaoHanh = $request->get('to_chuc')
            ->trungTamBaoHanh()
            ->where('id', $request->get('trung_tam_bao_hanh_id'))
            ->first();

        try{
            DB::beginTransaction();
            $maTram = TramBaoHanh::whereIn('ma',$request->get('ma'))->get(); // check exist in table tram_bao_hanh
            if(count($maTram)>0){ return response()->json('Data is existed', 500); }

            $dataSetKho = [];
            foreach ($data as $item) {
                $tramBh = [
                    'ma' => $item['ma'],
                    'ten' => $item['ten'],
                    'dia_chi' =>  $item['dia_chi'],
                    'cong_ty_id' => $request->get('cong_ty_id'),
                    'to_chuc_id' => $request->get('to_chuc')->id,
                    'loai_tram' =>  $item['loai_tram'],
                    'trung_tam_bao_hanh_id' => $tTramBaoHanh->id,
                    'so_dien_thoai' =>  $item['so_dien_thoai']
                ];

                $tramId = TramBaoHanh::insertGetId($tramBh);

                for ( $loaiKho = 1; $loaiKho <= 3; $loaiKho++){
                    $kho = [
                        'ten_kho' => 'Kho ' . $item['ten'],
                        'ma_kho' => 'KH-' . $item['ma'],
                        'loai_kho' => $loaiKho,
                        'dia_chi' => $item['dia_chi'],
                        'tram_bao_hanh_id' => $tramId,
                        'trung_tam_bao_hanh_id' => $tTramBaoHanh->id,
                        'to_chuc_id' => $request->get('to_chuc')->id,
                        'cong_ty_id' =>  $request->get('cong_ty_id'),
                    ];
                    array_push($dataSetKho, $kho);
                }

            }

            Kho::insert($dataSetKho);
            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($dataSetKho, 200);
    }
    public function update(Request $request) {

        $this->validate($request, [
            'id' => 'required',
            'ma' => 'required',
            'ten' => 'required',
            'dia_chi' => 'required',
            'cong_ty_id' => 'required',
            'trung_tam_bao_hanh_id' => 'required',
            'loai_tram' => 'required',
        ]);

        $tTamBaoHanh = $request->get('to_chuc')
            ->congTy()
            ->where('id', $request->get('cong_ty_id'))
            ->first()
            ->trungTamBaoHanh()
            ->where('id', $request->get('trung_tam_bao_hanh_id'))
            ->first();

        try {
            $tramBaoHanh = $tTamBaoHanh->tramBaoHanh()->where('id', $request->get('id'))->first();
            $tramBaoHanh->ma = $request->get('ma');
            $tramBaoHanh->ten = $request->get('ten');
            $tramBaoHanh->dia_chi = $request->get('dia_chi');
            $tramBaoHanh->loai_tram = $request->get('loai_tram');
            $tramBaoHanh->trung_tam_bao_hanh_id = $request->get('trung_tam_bao_hanh_id');
            $tramBaoHanh->so_dien_thoai =  $request->get('so_dien_thoai');
            $tramBaoHanh->don_vi_van_chuyen = $request->get('don_vi_van_chuyen');
            $tramBaoHanh->nguoi_dai_dien = $request->get('nguoi_dai_dien');
            $tramBaoHanh->save();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($tramBaoHanh, 200);

    }
    public function delete(Request $request){
        $this->validate($request, [
            'id' => 'required',
            'cong_ty_id' => 'required'
        ]);
        try{
            $tramBaoHanh = $request->get('to_chuc')
                ->congTy()
                ->where('id', $request->get('cong_ty_id'))
                ->first()
                ->tramBaoHanh()
                ->where('id', $request->get('id'))
                ->first();
            $tramBaoHanh->kho()->delete();
//            $user = \Auth::user();
//            if($tramBaoHanh->id !== $user->tram_bao_hanh_id){
//                return response()->json('Bạn không ở trạm bảo hành này nên không thể xóa', 500);
//            }
            $tramBaoHanh->danhSachChiPhiDiLai()->delete();
            $tramBaoHanh->delete();
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json('success', 200);
    }
    public function getTramByTrungTam(Request $request) {
        $tramBh = $request->get('to_chuc')
            ->tramBaoHanh()
            ->where('trung_tam_bao_hanh_id',$request->get('trung_tam_bao_hanh_id'))
            ->get();
        return response()->json($tramBh, 200);
    }
    public function search(Request $request) {
        $key_word =  $request->get('key_word');
        try{
            $trams = $request->get('to_chuc')->tramBaoHanh()
                ->where('ma','like','%'.$key_word.'%')
                ->orWhere('ten','like','%'.$key_word.'%')->take(20)->get();
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($trams, 200);
    }
}
