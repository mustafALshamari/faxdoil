<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateProfileRequest;
use Validator;
use Illuminate\Http\Request;
use App\User;
use Auth;


class UserProfileController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/profile/show",
     *     summary="Get blog post by id",
     *     tags={"Profile"},
     *     description="Get profile data",
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     )
     * )
     */
    public function show()
    {
        $id = Auth::id();

        $user_data = User::findOrFail(1);

        return response()->json([
            'data'    => $user_data,
            'status'  => 'success',
            'message' => ''
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/api/profile/update",
     *     summary="Update user profile",
     *     tags={"Profile"},
     *     description="Update profile data",
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/User"),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     )
     * )
     */
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(),[
            "name"      => "required",
            "email"     => "required"
        ]);

        if ($validate->fails()){
            return response()->json(
                ['errors' => $validate->errors()],
                401
            );
        }
        $user = Auth::user();
        $user = User::findOrFail(1);

        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->nickname     = $request->nickname;
        $user->phone_number = $request->phone_number;

        if ($request->location) {
            $user->location     = $request->location;
        }

        if ($request->introduction) {
            $user->introduction     = $request->introduction;
        }

        if ($request->introduction) {
            $user->introduction     = $request->introduction;
        }

        if ($request->hasFile("photo_name")) {
            $photo            = $request->photo_name;
            $photo_new_name   = time(). '.' .$request->photo_name->extension();
            $photo->move('some_path', $photo_new_name);
            $user->photo_name = 'some_path'.$photo_new_name;
        }

        $user->save();

        return response()->json([
            'data'    => $user,
            'status'  => 'success',
            'message' => 'The profile has been updated successfully.'
        ]);


    }
}
