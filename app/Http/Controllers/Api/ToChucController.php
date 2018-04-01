<?php

namespace App\Http\Controllers\Api;

use App\ToChuc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ToChucController extends Controller
{
    /**
     * @SWG\Swagger(
     *     basePath="/api/v1",
     *     schemes={"http", "https"},
     *     host=L5_SWAGGER_CONST_HOST,
     *     @SWG\Info(
     *         version="1.0.0",
     *         title="SServer api",
     *         description="SServer api description",
     *         @SWG\Contact(
     *             email="ngocvietlala@gmail.com"
     *         ),
     *     ),
     *      @SWG\SecurityScheme(
     *          securityDefinition="auth",
     *          type="apiKey",
     *          in="header",
     *          name="Authorization"
     *      )
     * )
     */

    /**
     * @SWG\Post(
     *      path="/to-chuc/create",
     *      operationId="toChucCreate",
     *      tags={"ToChuc"},
     *      summary="Create to chuc",
     *      description="Return ToChuc object",
     *      consumes={"application/x-www-form-urlencoded"},
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="ten",
     *          description="Ten to chuc",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="ngay_bd",
     *          description="Ngay bat dau",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="ngay_kt",
     *          description="Ngay ket thuc",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @SWG\Response(response=500, description="Internal server error."),
     *     )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) {
        $this->validate($request, [
           'ten' => 'required'
        ]);

        try {
            $tochuc = ToChuc::create([
                'ten' => $request->get('ten')
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($tochuc, 200);
    }

    public function show(Request $request) {
        return response()->json($request->get('to_chuc'), 200);
    }
}
