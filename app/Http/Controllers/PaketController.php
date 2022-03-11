<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//--import untuk mengenalkan file validator dan paket
use Illuminate\Support\Facades\Validator;
use App\Models\Paket;

class PaketController extends Controller
{

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'jenis' => 'required|string',
            'harga' => 'required|numeric'
		]);

		if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' =>$validator->errors(),
            ]);
		}

		$paket = new Paket();
		$paket->jenis = $request->jenis;
        $paket->harga = $request->harga;
		$paket->save();

        $data = Paket::where('id_paket','=', $paket->id_paket)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil memilih paket!',
            'data' => $data
        ]);
    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
			'jenis' => 'required|string',
            'harga' => 'required|numeric'
		]);

		if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' =>$validator->errors(),
            ]);
		}

		$paket = Paket::where('id_paket','=', $id)->first();
		$paket->jenis = $request->jenis;
        $paket->harga = $request->harga;
		$paket->save();

        $data = Paket::where('id_paket','=', $paket->id_paket)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil memilih paket!',
            'data' => $data
        ]);
    }
    public function getAll($limit = NULL, $offset = NULL)
    {
        $data["count"] = Paket::count();

        if($limit == NULL && $offset == NULL){
            $data["paket"] = Paket::get();
        } else {
            $data["paket"] = Paket::take($limit)->skip($offset)->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Mendapatkan data paket!',
            'data' => $data
        ]);
    }
    public function getById($id)
    {
        $data["paket"] = Paket::where('id_paket', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan paket baru!',
            'data' => $data
        ]);
    }
    public function delete($id)
    {
        $delete = Paket::where('id_paket', $id)->delete();

        if($delete){
            return response()->json([
                'success' => true,
                'message' => 'Data paket berhasil didapus!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data paket gagal dihapus!'
            ]);
        }
    }
}
