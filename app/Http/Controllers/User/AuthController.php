<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Support\Str;
use App\PasswordReset;
use Validator;
use DB;

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
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $success['token']    = $user->createToken('MyApp')->accessToken;
            $success['username'] = $user->username;

            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error'=>'Your email or password is invalid'], 401);
        }
    }

    /**
     * @SWG\Post(
     *     path="/api/register",
     *     summary="register new user",
     *     tags={"Auth"},
     *     description="register new user",
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
     *         description="successful operation message with user Token and user name",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="422",
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
        $input['user_type'] = 'user';

        $user = User::create($input);

        $success['message']  = 'You have successfully been registered';
        $success['token']    = $user->createToken('kaiApp')->accessToken;
        $success['username'] = $user->username;
        $success['user_type'] = $user->user_type;

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
     * )
     */
    public function logout()
    {
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true ]);

        $accessToken->revoke();

        return response()->json(['message' => 'See you soon!'] ,  $this->successStatus);
    }

    /**
     * @SWG\Post(
     *     path="/api/forgot/password",
     *     summary="register new user",
     *     tags={"Auth"},
     *     description="check email if exist and send reset email link",
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         description="email",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="We have e-mailed your password reset link!",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="We can't find a user with that e-mail address.",
     *     ),
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
                ], 404);

        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
             [
                'email' => $user->email,
                'token' => Str::random(60)
             ]
        );

        if ($user && $passwordReset) {
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );

            return response()->json([
                'message' => 'We have e-mailed your password reset link!',
                200]);
        }
    }

     /**
     * @SWG\Get(
     *     path="/api/find/{token}",
     *     summary="find token password reset",
     *     tags={"Auth"},
     *     description="find token password reset",
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         description="token",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="return json PasswordRest object ",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="This password reset token is invalid..",
     *     ),
     * )
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'This password reset token is invalid.'
                ], 404);
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        return response()->json($passwordReset , 200);
    }

     /**
     * @SWG\Post(
     *     path="/api/email/reset",
     *     summary="register new user",
     *     tags={"Auth"},
     *     description="check email if exist and send reset email link",
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         description="email",
     *         required=true,
     *         type="string",
     *     ),
     *  @SWG\Parameter(
     *         name="password",
     *         in="path",
     *         description="password",
     *         required=true,
     *         type="string",
     *     ),
     *  @SWG\Parameter(
     *         name="token",
     *         in="path",
     *         description="token",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="user object",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="This password reset token is invalid.",
     *     ),
     * )
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
            'token'     => 'required|string'
            ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
            ])->first();

        if (!$passwordReset) {

            return response()->json([
                'message' => 'This password reset token is invalid.'
                ], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {

            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
                ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));

        return response()->json($user, 200);
    }
}
