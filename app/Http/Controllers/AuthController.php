<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\RoleUser;
use App\User;
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

            return response()->json($data, 400);
        }
        else {
            $user_count = User::where('email', '=', $request->email)->count();

            if ($user_count > 0) {
                $user_find = User::where('email', '=', $request->email)->first();

                if ($user_find->status == '1') {
                    if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
                        $user = Auth::user();
                        $success['token'] =  $user->createToken('MyApp')-> accessToken;
                        return response()->json($success, 200);
                    }
                    else{
                        return response()->json(['errors'=>'User not found'], 400);
                    }
                } else {
                    return response()->json(['errors'=>'User not Active'], 400);
                }
            } else {
                return response()->json(['errors'=>'User not found'], 400);
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
        $role = RoleUser::where('user_id', $user->id)->get();

        $data = array();
        $data['user'] = $user;

        foreach ($role as $key => $r) {
            $data['role'][$key] = $r->role_id;
        }

        return response()->json($data, 200);
    }

    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json(['message' => 'Logout success'], 200);
    }

    public function token_notfound($value='')
    {
        return response()->json(['message' => 'token not found'], 401);
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
