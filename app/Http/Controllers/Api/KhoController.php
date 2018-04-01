<?php

namespace App\Http\Controllers\Api;
use App\Kho;
use App\TramBaoHanh;
use App\LinhKien;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;
use Log;

class KhoController extends Controller
{
    public function getList(Request $request) {
        $to_chuc_id = $request->get('to_chuc')->id;
        $loai_kho =  $request->get('loai_kho');
        $keyWord = $request->get('key_word');

        if ($keyWord)
            $result = DB::table('khos')
                ->where('id','=',$to_chuc_id)
                ->where('loai_kho','=',$loai_kho)
                ->limit(20)->get();

        return response()->json($result,200);
    }

    public function search(Request $request) {
        //$to_chu_id = $request->get('to_chuc')->id;
        $trung_tam_id = $request->get('trung_tam_id');
        $loai_kho =  $request->get('loai_kho');
        $keyWord = $request->get('key_word');
        $except_kho_id = $request->get('except_kho_id');
        if ($keyWord){
            $result = DB::table('khos')
                ->where('trung_tam_bao_hanh_id','=',$trung_tam_id)
                ->where('loai_kho','=',$loai_kho)
                ->where('ten_kho','like','%' . $keyWord . '%')
                ->where('id','<>',$except_kho_id)
                ->limit(20)->get();
        }else{
            $result = DB::table('khos')
                ->where('trung_tam_bao_hanh_id','=',$trung_tam_id)
                ->where('id','<>',$except_kho_id)
                ->where('loai_kho','=',$loai_kho)
                ->limit(20)->get();
        }
        return response()->json($result,200);
    }

    public function searchKhoXuat(Request $request) {
        try{
            $key_word = $request->get('key_word');
            $khos = Kho::where('to_chuc_id',$request->get('to_chuc')->id)
                ->where('loai_kho', $request->get('loai_kho'))
                ->where(function ($query) use ($key_word){
                    $query->where('ten_kho', 'like', '%'. $key_word .'%')
                        ->orWhere('ma_kho', 'like', '%'. $key_word .'%');
                })->take(20)->get();
            return response()->json($khos, 200);
        }catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
    public function getKhoTotDetail(Request $request){
        $tramId = $request->get('tram_id');
        $result = DB::select(DB::raw('select * from khos where tram_bao_hanh_id ='.$tramId.' and loai_kho=1'))[0];
        return response()->json($result,200);
    }

    public function getKhoByTramId(Request $request){
        $tramId = $request->get('tram_id');
        $trungTamId = TramBaoHanh::find($tramId)->get()[0]->trung_tam_bao_hanh_id;
        $result = DB::select(DB::raw('SELECT * FROM khos
            where (tram_bao_hanh_id = '.$tramId.' or (tram_bao_hanh_id is null and trung_tam_bao_hanh_id = '.$trungTamId.'))
            and loai_kho=1'));
        return response()->json($result,200);
    }

    public function all(Request $request) {
        $loaiKho = $request->get('loai_kho');
        $list = DB::select(DB::raw('select * from khos where to_chuc_id = '.$request->get('to_chuc')->id.' AND loai_kho='. $loaiKho));
        //$request->get('to_chuc')->danhSachKho->where('loai_kho','=',$loaiKho)->get();
        return response()->json($list,200);
    }

    public function getKhoTrungTamBaoHanh(Request $request){
        try {
            if ($request->get('tram_bao_hanh_id')) {
                $khos = Kho::where('trung_tam_bao_hanh_id', $request->get('trung_tam_bao_hanh_id'))
                    ->where('tram_bao_hanh_id', $request->get('tram_bao_hanh_id'))
                    ->get();
            } else {
                $khos = Kho::where('trung_tam_bao_hanh_id', $request->get('trung_tam_bao_hanh_id'))
                    ->where('tram_bao_hanh_id', $request->get('tram_bao_hanh_id'))
                    ->get();
            }
        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }
        return response()->json($khos,200);
    }


    public function tonkhototPagination(Request $request)
    {
        $khoId = $request->get('khoNhap');
        $linh_kien_id = $request->get('linh_kien_id');
        if ($linh_kien_id > 0) {
            $where = array('ton_kho_tots.kho_id'=>$khoId, 'linh_kien_id'=>$linh_kien_id);
        }else{
            $where = array('ton_kho_tots.kho_id'=>$khoId);
        }

        $ton_kho=DB::table('ton_kho_tots')
            ->join('linh_kiens', 'ton_kho_tots.linh_kien_id', '=', 'linh_kiens.id')
            ->join('khos', 'ton_kho_tots.kho_id', '=', 'khos.id')
            ->select('ton_kho_tots.*', 'linh_kiens.ten', 'linh_kiens.gia_ban', 'linh_kiens.don_vi', 'khos.ten_kho')
            ->where($where)
            ->orderBy('ton_kho_tots.ton_cuoi','desc')
            ->paginate(100);

        return Response::json($ton_kho, 200);
    }

    public function tonkhoxauPagination(Request $request)
    {
        $khoNhap = $request->get('khoNhap');
        $linh_kien_id = $request->get('linh_kien_id');
        $where = array('ton_kho_xacs.kho_id'=>$khoNhap);
        if ($linh_kien_id > 0) {
            $where = array('ton_kho_xacs.kho_id'=>$khoNhap, 'linh_kien_id'=>$linh_kien_id);

        }

        $ton_kho=DB::table('ton_kho_xacs')
            ->join('linh_kiens', 'ton_kho_xacs.linh_kien_id', '=', 'linh_kiens.id')
            ->join('khos', 'ton_kho_xacs.kho_id', '=', 'khos.id')
            ->select('ton_kho_xacs.*', 'linh_kiens.ten', 'linh_kiens.gia_ban', 'linh_kiens.don_vi', 'khos.ten_kho')
            ->where($where)
            ->orderBy('ton_kho_xacs.ton_cuoi','desc')
            ->paginate(100);

        return Response::json($ton_kho, 200);



    }
    public function linhkienxacPagination(Request $request)
    {
        $linh_kien_xac=DB::table('no_linh_kien_xacs')
            ->join('linh_kiens', 'no_linh_kien_xacs.linh_kien_cap_id', '=', 'linh_kiens.id')
            ->join('linh_kiens as lk_thu', 'no_linh_kien_xacs.linh_kien_thu_hoi_id', '=', 'lk_thu.id')

            ->join('users', 'no_linh_kien_xacs.nhan_vien_id', '=', 'users.id')
            ->join('tram_bao_hanhs', 'no_linh_kien_xacs.tram_bao_hanh_id', '=', 'tram_bao_hanhs.id')
            ->select('no_linh_kien_xacs.*','lk_thu.ten as ten_linh_kien_thu', 'linh_kiens.ten as ten_linh_kien', 'users.name as ten_nhan_vien', 'linh_kiens.don_vi', 'tram_bao_hanhs.ten as ten_tram')
            ->paginate(15);

        return Response::json($linh_kien_xac, 200);
    }
    public function update_don_gia (Request $request) {
        $this->validate($request,[
            'id' => 'required',
            'gia_ban' => 'required',
        ]);
        $id = $request->get('id');
        $linhkien = LinhKien::find($id);
        if($linhkien){
            try {
                    $linhkien->gia_ban = $request->get('gia_ban');
                    $linhkien->save();
                    return Response::json($linhkien, 200);
                } catch (Exception $e) {
                    return response()->json($e->getMessage(), 500);
                }
        }else{
            return response()->json(['error' => 'Something wrong'], 500);
        }
        
    }
}
