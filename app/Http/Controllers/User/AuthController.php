<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

/**
 * Class AuthController
 * @package App\Http\Controllers\User
 */
class AuthController extends Controller
{
    public $successStatus = 200;

    /**
     * @SWG\Post(
     *     path="/api/login",
     *     summary="login",
     *     tags={"Auth"},
     *     description="get token after login",
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
     *         description="successful operation with token and user's name",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     )
     * )
     */
    public function login(Request $request)
    {
        if(Auth::attempt([ 'email' => $request->email, 'password' => $request->password ])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name']  = $user->name;

            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    
    /**
     * @SWG\Post(
     *     path="/api/register",
     *     summary="register new user",
     *     tags={"Auth"},
     *     description="register new user",
     *     @SWG\Parameter(
     *         name="name",
     *         in="path",
     *         description="user's name",
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
     *         description="successful operation message with user Token and user name",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="validation error",
     *     ),
     * 
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'required|unique:users',
            'password'   => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success = [
            'message' => 'You have successfully been registered',
            'token'   => $user->createToken('kaiApp')->accessToken,
            'name'    => $user->name
        ];

        return response()->json(['success'=>$success], $this->successStatus);
    }

     /**
     * @SWG\Post(
     *     path="/api/logout",
     *     summary="logout for both user and stylist",
     *     tags={"Auth"},
     *     description="logout",
     *     security={{"passport": {}}},
     *     @SWG\Response(
     *         response="200",
     *         description="message : see you soon",
     *     ),
     * 
     * )
     */
    public function logout()
    {
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['logout'=>'See you soon!'] ,  $this->successStatus);
    }
}
