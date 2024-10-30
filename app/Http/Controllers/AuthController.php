<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;


class AuthController extends Controller
{
    
    public function create(Request $request){
		$rules =[
			'name' => 'required|string|max:100',
			'email' => 'required|email|max:100|unique:users',
			'password' => 'required|string|min:8'
		];
		$validator = Validator::make($request->input(),$rules);
		if($validator->fails()){
			return response()->json([
				'status' => false,
				'errors' => $validator->errors()->all()
			],400);
		}
		$user = User::create([
			'name' => $request->name, 
			'email' => $request->email,
			'password' => Hash::make($request->password)
		]);
			return response()->json([
			'status' => true,
			'message' => 'Usuario creado exitosamente',
			'token' => $user->createToken('API TOKEN')->plainTextToken
			],200);
	}
    
    public function login(Request $request){
        $rules =[
            'email' => 'required|email', // Se eliminó la regla unique aquí
            'password' => 'required|string'
        ];
        $validator = Validator::make($request->input(), $rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'errors' => ['No autorizado']
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'El usuario inició sesión exitosamente',
            'data' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'El usuario cerró sesión exitosamente'
        ], 200);
    }
}
