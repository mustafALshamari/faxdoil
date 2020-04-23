<?php

namespace App\Http\Controllers\User;
use App\User; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;


class AuthController extends Controller
{
    public $successStatus = 200;
    /** 
         * login api 
         * 
         * @return \Illuminate\Http\Response 
         */ 
        public function login(Request $request){ 


            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
                $success['token'] =  $user->createToken('MyApp')->accessToken; 
                return response()->json(['success' => $success], $this->successStatus); 
               
            } 
            else{ 
                return response()->json(['error'=>'Unauthorised'], 401); 
            } 
        }
        /** 
         * Register api 
         * 
         * @return \Illuminate\Http\Response 
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
            $success['token'] =  $user->createToken('kaiApp')->accessToken; 
            $success['name'] =  $user->name;
            return response()->json(['success'=>$success], $this->successStatus); 
        }

        /** 
         * logout api 
         * 
         * 
         */ 

        public function logout() {
            $accessToken = Auth::user()->token();
            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update([
                    'revoked' => true
                ]);
    
            $accessToken->revoke();
            return response()->json(null, 204);
        }
      
    }