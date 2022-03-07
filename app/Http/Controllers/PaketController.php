<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//--import untuk mengenalkan file validator dan outlet
use Illuminate\Support\Facades\Validator;
use App\Models\paket;

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

		$paket = new paket();
		$paket->jenis = $request->jenis;
        $paket->harga = $request->harga;
		$paket->save();

        $data = paket::where('id_paket','=', $paket->id_paket)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil memilih paket!',
            'data' => $data
        ]); 

        
    }
}
