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
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="validation error",
     *     ),
     * 
     * )
     */
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
    
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 

        $thisUser = User::findOrfail($user->id);
        $stylist = Stylist::create(['user_id'=> $thisUser->id]);

        $success['message'] =  'You have successfully been registered';
        $success['token'] =  $user->createToken('kaiApp')->accessToken; 
        $success['name'] =  $user->name;
        
        return response()->json(['success'=>$success], $this->successStatus); 
    }
    
}
