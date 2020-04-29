<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateProfileRequest;
use Validator;
use Illuminate\Http\Request;
use App\User;
use Auth;

/**
 * Class UserProfileController
 * @package App\Http\Controllers\User
 */
class UserProfileController extends Controller
{
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

        $data['name']         = $user_data->name;
        $data['email']        = $user_data->email;
        $data['nickname']     = $user_data->nickname;
        $data['phone_number'] = $user_data->phone_number;
        $data['location']     = $user_data->location;
        $data['introduction'] = $user_data->introduction;
        $data['photo_name']   = $user_data->photo_name;

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
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         description="User email",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="nickname",
     *         in="path",
     *         description="User nickname",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="phone_number",
     *         in="path",
     *         description="User phone number",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="location",
     *         in="path",
     *         description="User location",
     *         required=true,
     *         type="array",
     *         @SWG\Items(type="string")
     *     ),
     *     @SWG\Parameter(
     *         name="introduction",
     *         in="path",
     *         description="User introduction",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="profile_photo",
     *         in="path",
     *         description="User profile_photo",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="background_photo",
     *         in="path",
     *         description="User background_photo",
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
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "name"  => "required",
            "email" => "required"
        ]);

        if ($validate->fails()){
            return response()->json(
                ['errors' => $validate->errors()],
                401
            );
        }

        $user               = Auth::user();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->nickname     = $request->nickname;
        $user->phone_number = $request->phone_number;

        if ($request->location) {
            $user->location = $request->location;
        }

        if ($request->introduction) {
            $user->introduction = $request->introduction;
        }

        if ($request->hasFile("photo_name")) {
            $photo            = $request->photo_name;
            $photo_new_name   = time(). '.' .$request->photo_name->extension();
            $photo->move('some_path', $photo_new_name);
            $user->photo_name = 'some_path'.$photo_new_name;
        }

        $data['name']         = $user->name;
        $data['email']        = $user->email;
        $data['nickname']     = $user->nickname;
        $data['phone_number'] = $user->phone_number;
        $data['location']     = $user->location;
        $data['introduction'] = $user->introduction;
        $data['photo_name']   = $user->photo_name;

        $user->save();

        return response()->json([
            'data'    => $data,
            'status'  => 'success',
            'message' => 'The profile has been updated successfully.'
        ]);
    }
}
