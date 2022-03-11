<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Transaksi;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserController extends Controller
{
    public function getAll($limit = NULL, $offset = NULL)
    {
        $data["count"] = User::count();

        if($limit == NULL && $offset == NULL){
            $data["user"] = User::get();
        } else {
            $data["user"] = User::take($limit)->skip($offset)->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Mendapatkan data user!',
            'data' => $data
        ]);
    }

    public function getById($id)
    {
        $data["user"] = User::where('id_user', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan user baru!',
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
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
			'name' => 'required|string',
            'username' => 'required|string',
		]);

		if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' =>$validator->errors(),
            ]);
		}

		$user = user::where('id_user','=', $id)->first();
		$user->name = $request->name;
        $user->username = $request->username;
        if($request->input('role')){
            $user->role = $request->role;
        }
        $user->username = $request->username;
        if($request->input('password')){
            $user->password = $request->password;
        }
		$user->save();

        $data = user::where('id_user','=', $user->id_user)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Update user!',
            'data' => $data
        ]);
    }

    public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id_outlet' => 'required',
			'name' => 'required|string|max:255',
			'username' => 'required|string|max:50|unique:Users',
			'password' => 'required|string|min:6',
			'role' => 'required|string',
		]);

		if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' =>$validator->errors(),
            ]);
		}

		$user = new User();
		$user->id_outlet 	= $request->id_outlet;
		$user->name 	    = $request->name;
		$user->username     = $request->username;
		$user->role 	    = $request->role;
		$user->password     = Hash::make($request->password);
		$user->save();

        $data = User::where('username','=', $request->username)->first();
        //return $this->response->successResponseData('Data masyarakat berhasil ditambahkan', $data);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan user baru!',
            'data' => $data
        ]);
	}

    public function login(Request $request){
		$credentials = $request->only('username', 'password');

		$validator = Validator::make($request->all(), [
			'username' => 'required|string',
			'password' => 'required|string',
		]);

		try {
			if(!$token = JWTAuth::attempt($credentials)){
				return response()->json([
					'success' => false,
					'message' => 'Username dan password salah.'
				],400);
			}
		} catch(JWTException $e){
            return response()->json([
                'success' => true,
                'message' => 'Username dan password salah.'
            ],400);
		}

        $data = [
			'token' => $token,
			'user'  => JWTAuth::user()
		];
        return response()->json([
            'success' => true,
            'message' => 'Anda telah berhasil melakukan login',
            'data' => $data
        ]);
	}

	public function loginCheck(){
		try {
			if(!$user = JWTAuth::parseToken()->authenticate()){
				return $this->response->errorResponse('Invalid token!');
			}
		} catch (TokenExpiredException $e){
			return response()->json([
                'success' => false,
                'message' => 'Token Expired.',
            ],400);
		} catch (TokenInvalidException $e){
			return response()->json([
                'success' => false,
                'message' => 'Token invalid.',
            ],400);
		} catch (JWTException $e){
			return response()->json([
                'success' => false,
                'message' => 'Authorization token not found.',
            ]);
		}

        return response()->json([
            'success' => true,
            'message' => 'Authentication success',
            'data' => $user
        ]);

	}

    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json([
                'success' => true,
                'message' => 'You are logged out.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Logged out failed.',
            ],400);
        }
    }
}
