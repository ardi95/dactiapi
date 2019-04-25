<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' 	=> 'required',
        	'password' 	=> 'required'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();

            return response()->json($data, 404);
        }
        else {
            if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
                $user = Auth::user();
                $success['token'] =  $user->createToken('MyApp')-> accessToken;
                return response()->json($success, 200);
            }
            else{
                return response()->json(['errors'=>'User not found'], 404);
            }
        }
    }

    public function checktoken()
    {
        $data['success'] = 'Token valid';
        return response()->json($data, 200);
    }

    public function detail()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], 200);
    }

    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json(['message' => 'Logout success'], 200);
    }

    public function contohpost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kirim' 	=> 'required'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();

            return response()->json($data, 401);
        }
        else {
            $data['result'] = $request->kirim;

            return response()->json($data);
        }
    }
}
