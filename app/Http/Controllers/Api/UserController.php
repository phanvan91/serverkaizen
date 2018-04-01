<?php

namespace App\Http\Controllers\Api;

use App\ToChuc;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use DB;

class UserController extends Controller
{
    public function getPagination(Request $request)
    {
        $trung_tam_id = $request->get('trung_tam_id');
        $tram_id = $request->get('tram_id');
        if($trung_tam_id=='null' && $tram_id=='null' )
        {
            $where= array('to_chuc_id'=>$request->get('to_chuc')->id);
        }
        if($trung_tam_id >0 && $tram_id=='null' )
        {
            $where= array('trung_tam_bao_hanh_id'=>$trung_tam_id,'to_chuc_id'=>$request->get('to_chuc')->id);
        }
        if($tram_id > 0 )
        {
            $where= array('tram_bao_hanh_id'=>$tram_id,'to_chuc_id'=>$request->get('to_chuc')->id);
        }

        $users=DB::table('users')
                ->where($where)
            ->paginate(15);
        return response()->json($users, 200);


    }

    public function all(Request $request) {

        $list = $request->get('to_chuc')->users;

        return response()->json($list, 200);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'email' => 'required',
            'name' => 'required',
            'password' => 'required',
            'loai_nguoi_dung_id' => 'required',
        ]);

        $toChuc = $request->get('to_chuc');

        try {
            if (!User::where('email', $request->get('email'))->first()) {
                if ($request->get('tram_bao_hanh_id') != 0) {
                    $user = $toChuc->users()->create([
                        'email' => $request->get('email'),
                        'name' => $request->get('name'),
                        'password' => bcrypt($request->get('password')),
                        'loai_nguoi_dung_id' => $request->get('loai_nguoi_dung_id'),
                        'tram_bao_hanh_id' => $request->get('tram_bao_hanh_id'),
                        'trung_tam_bao_hanh_id' => $request->get('trung_tam_bao_hanh_id'),
                        'dien_thoai' => $request->get('dien_thoai')
                    ]);
                } else {
                    $user = $toChuc->users()->create([
                        'email' => $request->get('email'),
                        'name' => $request->get('name'),
                        'password' => bcrypt($request->get('password')),
                        'loai_nguoi_dung_id' => $request->get('loai_nguoi_dung_id'),
                        'trung_tam_bao_hanh_id' => $request->get('trung_tam_bao_hanh_id'),
                        'dien_thoai' => $request->get('dien_thoai')
                    ]);
                }
            } else {
                return response()->json('conflict', 409);
            }


        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($user, 200);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'loai_nguoi_dung_id' => 'required',
            'trung_tam_bao_hanh_id' => 'required|numeric',
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');

        try {
            $data = $request->only('loai_nguoi_dung_id', 'trung_tam_bao_hanh_id', 'password', 'dien_thoai', 'tram_bao_hanh_id', 'name');

            if ($data['password']) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            $user = $toChuc->users()->where('id', $request->get('id'))->first();

            foreach ($data as $key => $value) {
                if ($key == 'tram_bao_hanh_id' && ($value == -1 || $value == 0)) {
                    $user->$key = null;
                } else {
                    $user->$key = $value;
                }
            }

            $user->save();

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json($user, 200);
    }

    public function delete(Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $toChuc = $request->get('to_chuc');
        $user = $toChuc->users()->where('id', $request->get('id'))->first();

        if ($user) {
            try {
                $user->delete();
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('Not found', 404);
        }

        return response()->json($user, 200);
    }
    public function search(Request $request) {
        $loai_nguoi_dungs = DB::table('loai_nguoi_dungs')->where('ten_loai',$request->get('type'))->first();

        $where = array('tram_bao_hanh_id'=>$request->get('tram_bao_hanh_id'),'loai_nguoi_dung_id'=>$loai_nguoi_dungs->id);
        $user =DB::table('users')
            ->where($where)
            ->orderByDesc('id')
            ->get();
        return response()->json($user, 200);
    }

    public function searchNhanVien(Request $request) {
        $key_word = $request->get('key_word');
        $type = $request->get('type');
        $user = $request->get('to_chuc')->users()
            ->where('loai_nguoi_dung_id', 'like','%'.$type.'%')
            ->where('name','like','%'.$key_word.'%')->take(20)->get();

        return response()->json($user, 200);
    }
}
