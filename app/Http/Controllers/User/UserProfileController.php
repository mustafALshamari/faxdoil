<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use App\User;


/**
 * Class UserProfileController
 * @package App\Http\Controllers\User
 */
class UserProfileController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/profile/show_user/{username}",
     *     summary="Get user profile",
     *     tags={"User Profile"},
     *     description="Get another user profile",
     *     @SWG\Parameter(
     *         name="username",
     *         in="path",
     *         description="User username",
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
     *         description="Unauthorized user",
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     )
     * )
     */
    public function showUser($username)
    {
        $user_data = User::where('username', $username)->first();

        $data['username']         = $user_data->username;
        $data['email']            = $user_data->email;
        $data['nickname']         = $user_data->nickname;
        $data['phone_number']     = $user_data->phone_number;
        $data['location']         = [
                'address'   => $user_data->address,
                'latitude'  => $user_data->latitude ,
                'longitude' => $user_data->longitude
            ];
        $data['introduction']     = $user_data->introduction;
        $data['profile_photo']    = $user_data->profile_photo;
        $data['background_photo'] = $user_data->background_photo;

        return response()->json([
            'data'    => $data,
            'status'  => 'success',
            'message' => ''
        ]);
    }

    /**
     * @SWG\Get(
     *     path="/api/profile/show",
     *     summary="Get user profile",
     *     tags={"User Profile"},
     *     description="Get user profile",
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     )
     * )
     */
    public function show()
    {
        $id = Auth::id();

        $user_data = User::findOrFail($id);

        $data['username']             = $user_data->username;
        $data['email']            = $user_data->email;
        $data['nickname']         = $user_data->nickname;
        $data['phone_number']     = $user_data->phone_number;
        $data['location']         = [
            'address'   => $user_data->address,
            'latitude'  => $user_data->latitude ,
            'longitude' => $user_data->longitude
        ];
        $data['introduction']     = $user_data->introduction;
        $data['profile_photo']    = $user_data->profile_photo;
        $data['background_photo'] = $user_data->background_photo;

        return response()->json([
            'data'    => $data,
            'status'  => 'success',
            'message' => ''
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/api/profile/update",
     *     summary="Update user profile",
     *     tags={"User Profile"},
     *     description="Update profile data",
     *     @SWG\Parameter(
     *         name="username",
     *         in="path",
     *         description="User username",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         description="User email",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="nickname",
     *         in="path",
     *         description="User nickname",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="phone_number",
     *         in="path",
     *         description="User phone number",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="location",
     *         in="path",
     *         description="User location",
     *         required=false,
     *         type="array",
     *         @SWG\Items(type="string")
     *     ),
     *     @SWG\Parameter(
     *         name="introduction",
     *         in="path",
     *         description="User introduction",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="profile_photo",
     *         in="path",
     *         description="User profile_photo",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="background_photo",
     *         in="path",
     *         description="User background_photo",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="validation error",
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     )
     * )
     */
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "username"  => "required",
            "email"     => "required"
        ]);

        if ($validate->fails()){
            return response()->json(
                ['errors' => $validate->errors()],
                401
            );
        }

        $user               = Auth::user();
        $user->username     = $request->username;
        $user->email        = $request->email;
        $user->fullname     = $request->fullname;
        $user->phone_number = $request->phone_number;

        if ($request->age) {
            $user->age = $request->age;
        }

        if ($request->introduction) {
            $user->introduction = $request->introduction;
        }

        if ($request->hasFile("profile_photo")) {
            $photo               = $request->profile_photo;
            $profilePhotoName  = time(). '.' .$request->profile_photo->extension();
            $photo->move(public_path('uploads/account/' . $user->id), $profilePhotoName);
            $user->profile_photo = public_path('uploads/account/' . $user->id) . $profilePhotoName;
        }

        if ($request->hasFile("background_photo")) {
            $photo            = $request->background_photo;
            $backgroundPhotoName   = time(). '.' .$request->background_photo->extension();
            $photo->move(public_path('uploads/account/' . $user->id), $backgroundPhotoName);
            $user->background_photo = public_path('uploads/account/' . $user->id) . $backgroundPhotoName;
        }

        if ($request->location) {
            foreach ($request->location as $key => $value) {
                $user->address   = $value['address'];
                $user->latitude  = $value['latitude'];
                $user->longitude = $value['longitude'];
            }
        }

        $data['username']           = $user->username;
        $data['email']              = $user->email;
        $data['fullname']           = $user->fullname;
        $data['phone_number']       = $user->phone_number;
        $data['introduction']       = $user->introduction;
        $data['profile_photo']      = $user->profile_photo;
        $data['background_photo']   = $user->background_photo;
        $data['address']            = $user->address;
        $data['latitude']           = $user->latitude;
        $data['longitude']          = $user->longitude;

        $user->save();

        return response()->json([
            'data'    => $data,
            'status'  => 'success',
            'message' => 'The profile has been updated successfully'
        ]);
    }
}
