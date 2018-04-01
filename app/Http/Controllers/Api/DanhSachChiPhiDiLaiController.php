<?php

namespace App\Http\Controllers\Api;

use App\DanhSachChiPhiDiLai;
use App\ToChuc;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Response;
class DanhSachChiPhiDiLaiController extends Controller
{

    public function all(Request $request) {

        $list = $request->get('to_chuc')->danhSachChiPhiDiLai;

        return response()->json($list, 200);
    }


    public function getPagination(Request $request)
    {
        $keyword = $request->get('key_word');
        if($keyword !='undefined'){
            $cpdl=DB::table('danh_sach_chi_phi_di_lais')
            ->join('tram_bao_hanhs', 'danh_sach_chi_phi_di_lais.tram_bao_hanh_id', '=', 'tram_bao_hanhs.id')
            ->where('danh_sach_chi_phi_di_lais.to_chuc_id',$request->get('to_chuc')->id)
            ->where('danh_sach_chi_phi_di_lais.ten_phuong','like', '%'.$keyword.'%')

            ->select('danh_sach_chi_phi_di_lais.*', 'tram_bao_hanhs.ten as ten_tram')

            ->paginate(15);
        return Response::json($cpdl, 200);
    }else{
        $cpdl=DB::table('danh_sach_chi_phi_di_lais')
            ->join('tram_bao_hanhs', 'danh_sach_chi_phi_di_lais.tram_bao_hanh_id', '=', 'tram_bao_hanhs.id')
            ->where('danh_sach_chi_phi_di_lais.to_chuc_id',$request->get('to_chuc')->id)

            ->select('danh_sach_chi_phi_di_lais.*', 'tram_bao_hanhs.ten as ten_tram')

            ->paginate(15);
        return Response::json($cpdl, 200);
    }

        
    }

    /**
     * @SWG\Post(
     *      path="/danh-sach-chi-phi-di-lai/create",
     *      operationId="danhSachChiPhiDiLaiCreate",
     *      tags={"DanhSachChiPhiDiLai"},
     *      summary="Create danh sach chi phi di lai",
     *      description="Return DanhSachChiPhiDiLai object",
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
     *          name="tinh",
     *          description="Ma tinh",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="thanh_pho",
     *          description="Ma thanh pho",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="quan",
     *          description="Ma quan",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="km_mot_chieu",
     *          description="Km mot chieu (type: Float)",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="km_khu_hoi",
     *          description="Km khu hoi (type: Float)",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="don_gia",
     *          description="Don gia (type: Float)",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="thanh_tien_mot",
     *          description="Thanh tien mot (type: Float)",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="thanh_tien_hai",
     *          description="Thanh tien hai (type: Float)",
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
     *      @SWG\Parameter(
     *          name="tram_bao_hanh_id",
     *          description="Tram bao hanh id",
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
    public function create(Request $request)
    {
        $this->validate($request, [
            'phuong' => 'required',
            'thanh_pho' => 'required',
            'quan' => 'required',
            'km_mot_chieu' => 'required',
            'km_khu_hoi' => 'required',
            'don_gia' => 'required',
            'thanh_tien_mot' => 'required',
            'thanh_tien_hai' => 'required',
            'tram_bao_hanh_id' => 'required',
        ]);

        $tramBaoHanh = $request->get('to_chuc')
            ->first()
            ->tramBaoHanh()
            ->where('id', $request->get('tram_bao_hanh_id'))
            ->first();

        try {
            $checkChiPhi = DanhSachChiPhiDiLai::where('tram_bao_hanh_id',$tramBaoHanh->id)
                ->where('phuong', $request->get('phuong'))->count();

            if($checkChiPhi){
                return response()->json('Ton tai chi phi di lai', 500);
            }
            $dscpdl = $tramBaoHanh->danhSachChiPhiDiLai()->create([
                'quan' => $request->get('quan'),
                'thanh_pho' => $request->get('thanh_pho'),
                'phuong' => $request->get('phuong'),
                'km_mot_chieu' => $request->get('km_mot_chieu'),
                'km_khu_hoi' => $request->get('km_khu_hoi'),
                'don_gia' => $request->get('don_gia'),
                'thanh_tien_mot' => $request->get('thanh_tien_mot'),
                'thanh_tien_hai' => $request->get('thanh_tien_hai'),
                'trung_tam_bao_hanh_id' => $tramBaoHanh->trung_tam_bao_hanh_id,
                'to_chuc_id' =>$request->get('to_chuc')->id,
                'ten_thanh_pho' => $request->get('ten_thanh_pho'),
                'ten_quan' => $request->get('ten_quan'),
                'ten_phuong' => $request->get('ten_phuong')
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($dscpdl, 200);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'phuong' => 'required',
            'thanh_pho' => 'required',
            'quan' => 'required',
            'km_mot_chieu' => 'required',
            'km_khu_hoi' => 'required',
            'don_gia' => 'required',
            'thanh_tien_mot' => 'required',
            'thanh_tien_hai' => 'required',
            'tram_bao_hanh_id' => 'required',
            'id' => 'required'
        ]);

        $tramBaoHanh = $request->get('to_chuc')
            ->first()
            ->tramBaoHanh()
            ->where('id', $request->get('tram_bao_hanh_id'))
            ->first();

        if ($tramBaoHanh) {
            $dscpdl = $request->get('to_chuc')
                ->first()
                ->danhSachChiPhiDiLai()
                ->where('id', $request->get('id'))
                ->first();

            if ($dscpdl) {
                try {
                    $data = $request->only('quan', 'thanh_pho', 'phuong', 'km_mot_chieu', 'km_khu_hoi', 'don_gia', 'thanh_tien_mot', 'thanh_tien_hai','ten_thanh_pho','ten_quan','ten_phuong');

                    foreach ($data as $key => $value) {
                        $dscpdl->$key = $value;
                    }
                    $dscpdl->trung_tam_bao_hanh_id = $tramBaoHanh->trung_tam_bao_hanh_id;
                    $dscpdl->tram_bao_hanh_id = $tramBaoHanh->id;

                    $dscpdl->save();
                } catch (\Exception $e) {
                    return response()->json($e->getMessage(), 500);
                }
            } else {
                return response()->json('Not Found', 404);
            }
        } else {
            return response()->json('Not Found', 404);
        }

        return response()->json($dscpdl, 200);
    }

    public function delete(Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $cpdl = $toChuc->danhSachChiPhiDiLai()->where('id', $request->get('id'))->first();

        if ($cpdl) {
            try {
                $cpdl->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($cpdl, 200);
    }

    public function filter(Request $request) {

        $this->validate($request, [
           'tram' => 'required',
           'thanh_pho' => 'required',
           'quan' => 'required',
           'phuong' => 'required',
        ]);

        $cpdl = $request->get('to_chuc')->danhSachChiPhiDiLai()
            ->where('tram_bao_hanh_id', $request->get('tram'))
            ->where('thanh_pho', $request->get('thanh_pho'))
            ->where('quan', $request->get('quan'))
            ->where('phuong', $request->get('phuong'))
            ->first();


        return response()->json($cpdl, 200);
    }

    public function import(Request $request) {

        $this->validate($request,[
            'tram_bao_hanh_id' => 'required',
            'data' => 'required'
        ]);
        $tramBH = $request->get('to_chuc')->tramBaoHanh()
            ->where('id',$request->get('tram_bao_hanh_id'))->first();
        try {
            $data = $request->get('data');
            $dataSet = [];
            foreach ($data as $item) {
                $cpdl = [
                    'thanh_pho' => $item['thanh_pho'],
                    'quan' => $item['quan'],
                    'phuong' => $item['phuong'],
                    'km_mot_chieu' => $item['km_mot_chieu'],
                    'km_khu_hoi' => $item['km_khu_hoi'],
                    'don_gia' => $item['don_gia'],
                    'thanh_tien_mot' => $item['thanh_tien_mot'],
                    'thanh_tien_hai' => $item['thanh_tien_hai'],
                    'ten_thanh_pho' => $item['ten_thanh_pho'],
                    'ten_quan' => $item['ten_quan'],
                    'ten_phuong' => $item['ten_phuong'],
                    'to_chuc_id' => $request->get('to_chuc')->id,
                    'tram_bao_hanh_id' => $tramBH->id,
                    'trung_tam_bao_hanh_id' => $tramBH->trung_tam_bao_hanh_id
                ];

                $checkCpdl = DanhSachChiPhiDiLai::where('tram_bao_hanh_id',$tramBH->id)
                    ->where('phuong',$item['phuong'])->first();
                if($checkCpdl){
                    $checkCpdl->thanh_pho = $item['thanh_pho'];
                    $checkCpdl->quan = $item['quan'];
                    $checkCpdl->phuong = $item['phuong'];
                    $checkCpdl->km_mot_chieu = $item['km_mot_chieu'];
                    $checkCpdl->km_khu_hoi = $item['km_khu_hoi'];
                    $checkCpdl->don_gia = $item['don_gia'];
                    $checkCpdl->thanh_tien_mot = $item['thanh_tien_mot'];
                    $checkCpdl->ten_thanh_pho = $item['ten_thanh_pho'];
                    $checkCpdl->ten_quan = $item['ten_quan'];
                    $checkCpdl->tram_bao_hanh_id = $item['tram_bao_hanh_id'];
                    $checkCpdl->tram_bao_hanh_id = $item['tram_bao_hanh_id'];
                    $checkCpdl->save();
                }
                else {
                    array_push($dataSet, $cpdl);
                }
            }

            DanhSachChiPhiDiLai::insert($dataSet);
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
        return response()->json($dataSet, 200);
    }
}
