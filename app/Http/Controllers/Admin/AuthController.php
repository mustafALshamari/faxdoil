<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

/**
 * Class AuthController
 * @package App\Http\Controllers\Admin
 */
class AuthController extends Controller
{
    public $successStatus = 200;

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'       => 'required|unique:users',
            'email'          => 'required|unique:users',
            'password'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['email']     = strtolower($input['email']);
        $input['password']  = bcrypt($input['password']);
        $input['user_type'] = 'admin';

        $user                 = User::create($input);

        $success['token']     = $user->createToken('kaiApp')->accessToken;
        $success['username']  = $user->username;
        $success['user_type'] = $user->user_type;

        return response()->json(['success'=>$success], $this->successStatus);
    }
}
