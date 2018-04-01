<?php

namespace App\Http\Controllers\Api;

use App\CongTy;
use App\Kho;
use App\ToChuc;
use App\TrungTamBaoHanh;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class TrungTamBaoHanhController extends Controller
{

    public function all(Request $request) {

        $list = TrungTamBaoHanh::with('congTy')
            ->where('to_chuc_id',$request->get('to_chuc')->id)->get();
        return response()->json($list, 200);
    }

    public function pagination(Request $request) {

        $ttamBaoHanh=DB::table('trung_tam_bao_hanhs')
            ->join('cong_ties', 'trung_tam_bao_hanhs.cong_ty_id', '=', 'cong_ties.id')

            ->where(array('trung_tam_bao_hanhs.to_chuc_id'=>$request->get('to_chuc')->id))
            ->select('trung_tam_bao_hanhs.*', 'cong_ties.ma as ma_cong_ty')

            ->paginate(15);
        return response()->json($ttamBaoHanh, 200);
    }

    /**
     * @SWG\Post(
     *      path="/trung-tam-bao-hanh/create",
     *      operationId="trungTamBaoHanhCreate",
     *      tags={"TrungTamBaoHanh"},
     *      summary="Create trung tam bao hanh",
     *      description="Return TrungTamBaoHanh object",
     *      consumes={"application/x-www-form-urlencoded"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          type="string",
     *          name="Authorization",
     *          in="header",
     *          required=true
     *     ),
     *      @SWG\Parameter(
     *          name="to_chuc_id",
     *          description="To chuc id",
     *          required=true,
     *          in="formData",
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="ma",
     *          description="Ma trung tam",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="ten",
     *          description="Ten trung tam",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="dia_chi",
     *          description="Dia chi",
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
            'ma' => 'required',
            'ten' => 'required',
            'dia_chi' => 'required',
            'cong_ty_id' => 'required',
        ]);

        $toChuc = $request->get('to_chuc');
        $congTy = $toChuc->congTy()->where('id', $request->get('cong_ty_id'))->first();

        try {
            DB::beginTransaction();
            $tTBaoHanhId = TrungTamBaoHanh::insertGetId([
                'ma' => $request->get('ma'),
                'ten' => $request->get('ten'),
                'dia_chi' => $request->get('dia_chi'),
                'email' => $request->get('email'),
                'so_dien_thoai' =>$request->get('so_dien_thoai'),
                'cong_ty_id' =>  $congTy->id,
                'to_chuc_id' => $congTy->to_chuc_id
            ]);
            $tTBaoHanh = TrungTamBaoHanh::find($tTBaoHanhId);
            for ( $loaiKho = 1; $loaiKho <= 3; $loaiKho++){
                Kho::insert([
                    'ten_kho' => 'Kho ' . $request->get('ten'),
                    'ma_kho' => 'KH-' . $request->get('ma'),
                    'loai_kho' => $loaiKho,
                    'tram_bao_hanh_id' => null,
                    'dia_chi' => $request->get('dia_chi'),
                    'trung_tam_bao_hanh_id' => $tTBaoHanhId,
                    'to_chuc_id' => $congTy->to_chuc_id,
                    'cong_ty_id' =>  $congTy->id
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($tTBaoHanh, 200);

    }
    public function update(Request $request) {

        $this->validate($request, [
            'id' => 'required',
            'ma' => 'required',
            'ten' => 'required',
            'dia_chi' => 'required',
            'cong_ty_id' => 'required',
        ]);
        $toChuc = $request->get('to_chuc');
        $congTy = $toChuc->congTy()->where('id', $request->get('cong_ty_id'))->first();

        try {
            $tTBaoHanh = $congTy->trungTamBaoHanh()->where('id',$request->get('id'))->first();
            $tTBaoHanh->ma = $request->get('ma');
            $tTBaoHanh->ten = $request->get('ten');
            $tTBaoHanh->dia_chi = $request->get('dia_chi');
            $tTBaoHanh->so_dien_thoai = $request->get('so_dien_thoai');
            $tTBaoHanh->email = $request->get('email');
            $tTBaoHanh->save();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        $tTBaoHanh=DB::table('trung_tam_bao_hanhs')
            ->join('cong_ties', 'trung_tam_bao_hanhs.cong_ty_id', '=', 'cong_ties.id')
            ->where(array('trung_tam_bao_hanhs.id'=>$request->get('id')))
            ->select('trung_tam_bao_hanhs.*', 'cong_ties.ma as ma_cong_ty')
            ->first();
        return response()->json($tTBaoHanh, 200);

    }
    public function delete(Request $request){
        $this->validate($request, [
            'id' => 'required',
            'cong_ty_id' => 'required'
        ]);
        try{
            $trungTamBaoHanh = $request->get('to_chuc')
                ->congTy()
                ->where('id', $request->get('cong_ty_id'))
                ->first()
                ->trungTamBaoHanh()
                ->where('id', $request->get('id'))
                ->first();
//            $currentUser = $request->get('user');

//            $trungTamBaoHanh->danhSachBangTinhCongSuaChua()->delete();
//            foreach ($trungTamBaoHanh->kho as $kho){
//                $kho->tonKhoTot()->delete();
//                $kho->tonKhoXac()->delete();
//            }
//            $trungTamBaoHanh->kho()->delete();
//            foreach ($trungTamBaoHanh->donDatHang as $donDatHang){
//                $donDatHang->danhSachDonDatHangChiTiet()->delete();
//            }
//            $trungTamBaoHanh->donDatHang()->delete();
//            $trungTamBaoHanh->users()->forceDelete();
//            $trungTamBaoHanh->tramBaoHanh()->delete();
            $trungTamBaoHanh->forceDelete();
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json('success', 200);
    }

    public function getList(Request $request){
        $tTamBaoHanh = TrungTamBaoHanh::with('tramBaoHanh')
            ->where('to_chuc_id',$request->get('to_chuc')->id)->get();
        return response()->json($tTamBaoHanh, 200);
    }

    public function search(Request $request){
        $key_word =  $request->get('key_word');
        try{
            $tTamBaoHanh = $request->get('to_chuc')->trungTamBaoHanh()
                ->where('ma','like','%'.$key_word.'%')
                ->orWhere('ten','like','%'.$key_word.'%')->take(20)->get();
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($tTamBaoHanh, 200);
    }

}
