<?php

namespace App\Http\Controllers\Stylist;

use App\User; 
use App\Stylist;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; 
use Validator;

class AuthController extends Controller
{
    public $successStatus = 200;
    
     /**
     * @SWG\Post(
     *     path="/api/stylist/register",
     *     summary="register new stylist",
     *     tags={"Auth"},
     *     description="register new stylist",
     *     @SWG\Parameter(
     *         name="username",
     *         in="path",
     *         description="username",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         description="email",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="path",
     *         description="password",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="validation error",
     *     ),
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
            'username'       => 'required|unique:users',
            'email'          => 'required|unique:users',
            'password'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $input              = $request->all();
        $input['email']     = strtolower($input['email']); 
        $input['password']  = bcrypt($input['password']);
        $input['user_type'] = 'stylist';

        $user = User::create($input);

        $success['message']   = 'You have successfully been registered';
        $success['token']     = $user->createToken('kaiApp')->accessToken;
        $success['username']  = $user->username;
        $success['user_type'] = $user->user_type;

        return response()->json(['success'=>$success], $this->successStatus);
    }
}
