<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserController extends Controller
{

    public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id_outlet' => 'required',
			'name' => 'required|string|max:255',
			'username' => 'required|string|max:50|unique:Users',
			'password' => 'required|string|min:6',
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
		$user->role 	    = 'admin';
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
