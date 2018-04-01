<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\Controller;
use Response;

class AuthController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function show(Request $request) {
        return response()->json($request->get('user'), 200);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function updateAccount(Request $request)
    {
        $dien_thoai=$request->get('dien_thoai');
        $password=$request->get('password');
        $new_password = $request->get('new_password');
        $confirm_password = $request->get('confirm_password');

        $id=$request->get('id');
        $user = User::find($id);

        if($user)
        {
            if(!$new_password)
            {
                $new_password = $user->password;
            }
            else{
                $credentials = $request->only('email', 'password');

                if ( $token = auth()->attempt($credentials)) {

                    if($new_password != $confirm_password)
                    {
                        return response()->json(['status' => 'Mật khẩu không trùng khớp'], 200);

                    }
                    $new_password = bcrypt($new_password);

                }
                else{
                    return response()->json(['status' => 'Mật khẩu cũ không đúng'], 200);

                }

            }
            try {
                $user->password = $new_password;
                $user->dien_thoai = $dien_thoai;
                $user->save();
                return Response::json($user, 200);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        }
        else{
            return response()->json(['error' => 'Not found user'], 500);

        }


    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'to_chuc_id' => \Auth::user()->toChuc->id
        ]);
    }
}
