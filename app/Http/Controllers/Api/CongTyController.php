<?php

namespace App\Http\Controllers\Api;

use App\CongTy;
use App\ToChuc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class CongTyController extends Controller
{
    public function getPagination(Request $request)
    {
        $cong_ties=DB::table('cong_ties')->where('to_chuc_id',$request->get('to_chuc')->id)->paginate(15);
        return response()->json($cong_ties, 200);


    }

    public function all(Request $request) {
        $toChuc = $request->get('to_chuc');
        $congTy = $toChuc->congTy;

        return response()->json($congTy, 200);
    }

    /**
     * @SWG\Post(
     *      path="/cong-ty/create",
     *      operationId="congTyCreate",
     *      tags={"CongTy"},
     *      summary="Create cong ty",
     *      description="Return CongTy object",
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
     *          description="Ma cong ty",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="ten",
     *          description="Ten cong ty",
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
     *          name="ma_so_thue",
     *          description="Ma so thue",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="dien_thoai",
     *          description="Dien thoai",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="email",
     *          description="Email lien lac",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="web",
     *          description="Website cong ty",
     *          in="formData",
     *          type="string"
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
            'ma' => 'required',
            'ten' => 'required',
            'dia_chi' => 'required',
            // 'ma_so_thue' => 'required',
            'dien_thoai' => 'required',
            // 'email' => 'required',
        ]);

        $toChuc = $request->get('to_chuc');

        $check_ma = $toChuc->congTy()->where('ma', $request->get('ma'))->first();
        if(!$check_ma)
        {
            try {
                $congTy = $toChuc->congTy()->create([
                    'ma' => $request->get('ma'),
                    'ten' => $request->get('ten'),
                    'dia_chi' => $request->get('dia_chi'),
                    'ma_so_thue' => $request->get('ma_so_thue'),
                    'dien_thoai' => $request->get('dien_thoai'),
                    'email' => $request->get('email'),
                    'web' => $request->get('web')
                ]);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }

            return response()->json($congTy, 200);
        }
        else{
            return response()->json('Mã đã tồn tại', 500);

        }


    }


    /**
     * @SWG\Put(
     *      path="/cong-ty/{id}",
     *      operationId="update",
     *      tags={"CongTy"},
     *      summary="Update cong ty",
     *      description="return cong ty object",
     *      produces={"application/json"},
     * 		@SWG\Parameter(
     * 			name="id",
     * 			in="path",
     * 			required=true,
     * 			type="string",
     * 			description="UUID",
     * 		),
     *     @SWG\Parameter(
     *         description="body",
     *         in="body",
     *         name="to_chuc_id",
     *         required=true,
     *         @SWG\Schema(
     *             required={"to_chuc_id"},
     *             @SWG\Property(property="to_chuc_id", type="integer"),
     *             @SWG\Property(property="ma", type="string", example="CTHN1"),
     *             @SWG\Property(property="ten", type="string", example="Cong ty Ha Noi 1"),
     *             @SWG\Property(property="dia_chi", type="string", example="66 Vo Van Tan"),
     *             @SWG\Property(property="ma_so_thue", type="string", example="ABCXYZ"),
     *             @SWG\Property(property="dien_thoai", type="string", example="09000 biet"),
     *             @SWG\Property(property="email", type="string", example="joe-doe@gmail.com"),
     *             @SWG\Property(property="web", type="string", example="*.*"),
     *         ),
     *     ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @SWG\Response(response=500, description="Internal server error."),
     *       security={
     *           {"auth": {}}
     *       }
     *     )
     *
     *     Returns CongTy object
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) {

        $this->validate($request, [
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $congTy = $toChuc->congTy()->where('id', $request->get('id'))->first();

        if ($congTy) {
            $data = $request->only('ma', 'ten', 'dia_chi', 'ma_so_thue', 'dien_thoai', 'email', 'web');

            foreach ($data as $key => $value) {
                $congTy->$key = $value;
            }

            try {
                $congTy->save();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 200);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($congTy, 200);
    }

    public function delete(Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $congTy = $toChuc->congTy()->where('id', $request->get('id'))->first();

        if ($congTy) {
            try {
                $congTy->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 200);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($congTy, 200);

    }

}

