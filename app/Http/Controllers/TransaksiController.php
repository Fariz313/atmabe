<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//--import untuk mengenalkan file validator dan transaksi
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransaksiController extends Controller
{

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'id_member'=>'required|numeric',
            'tgl'=>'required|date',
            'tgl_bayar'=>'required|date',
            'batas_waktu'=>'required|date',
            'status'=>'required|in:baru,selesai,proses,diambil',
            'dibayar'=>'required|in:dibayar,belum_dibayar',
            'id_paket'=>'required|numeric',
            'berat'=>'required|numeric'
		]);

		if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' =>$validator->errors(),
            ]);
		}
        if(!$user = JWTAuth::parseToken()->authenticate()){
            return $this->response->errorResponse('Invalid token!');
        }
		$transaksi = new Transaksi();
		$transaksi->id_member = $request->id_member;
        $transaksi->tgl = $request->tgl;
        $transaksi->tgl_bayar = $request->tgl_bayar;
        $transaksi->batas_waktu = $request->batas_waktu;
        $transaksi->status = $request->status;
        $transaksi->dibayar = $request->dibayar;
        $transaksi->id_user = $user->id_user;
        $transaksi->id_paket = $request->id_paket;
        $transaksi->berat = $request->berat;
		$transaksi->save();

        $data = Transaksi::where('id_transaksi','=', $transaksi->id_transaksi)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil memilih transaksi!',
            'data' => $data
        ]);
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
			'id_member'=>'numeric',
            'tgl'=>'date',
            'tgl_bayar'=>'date',
            'batas_waktu'=>'date',
            'status'=>'in:baru,selesai,proses,diambil',
            'dibayar'=>'in:dibayar,belum_dibayar',
            'id_paket'=>'numeric',
            'berat'=>'numeric'
		]);

		if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' =>$validator->errors(),
            ]);
		}

		$transaksi = Transaksi::where('id_transaksi','=', $id)->first();
		$transaksi->id_member = $request->id_member;
        $transaksi->tgl = $request->tgl;
        $transaksi->tgl_bayar = $request->tgl_bayar;
        $transaksi->batas_waktu = $request->batas_waktu;
        $transaksi->status = $request->status;
        $transaksi->dibayar = $request->dibayar;
        $transaksi->id_user = Auth::user()->id_user;
        $transaksi->id_paket = $request->id_paket;
        $transaksi->berat = $request->berat;
		$transaksi->save();

        $data = Transaksi::where('id_transaksi','=', $transaksi->id_transaksi)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil memilih transaksi!',
            'data' => $data
        ]);
    }
    public function delete($id)
    {
        $delete = Transaksi::where('id_transaksi', $id)->delete();

        if($delete){
            return response()->json([
                'success' => true,
                'message' => 'Data outlet berhasil didapus!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data outlet gagal dihapus!'
            ]);
        }
    }
    public function getAll($limit = NULL, $offset = NULL)
    {
        $data["count"] = Transaksi::count();

        if($limit == NULL && $offset == NULL){
            $data["transaksi"] = Transaksi::with(['member','paket','user'])->get();
        } else {
            $data["transaksi"] = Transaksi::take($limit)->skip($offset)->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Mendapatkan data transaksi!',
            'data' => $data
        ]);
    }
    public function getById($id)
    {
        $data["transaksi"] = Transaksi::where('id_transaksi', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan transaksi baru!',
            'data' => $data
        ]);
    }
}
