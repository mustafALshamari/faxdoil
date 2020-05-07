<?php

namespace App\Http\Controllers\Stylist;

use App\Http\Controllers\Controller;
use App\Salon;
use App\StylePost;
use App\Stylist;
use App\StylistFollower;
use App\StylistReview;
use Auth;
use App\User;
use DB;
use Illuminate\Http\Request;
use Validator;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Stylist
 */
class ProfileController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/stylist/show_my_profile",
     *     summary="Get stylist profile",
     *     tags={"Stylist"},
     *     description="Get profile data",
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
    public function showMyProfile()
    {
        $id = Auth::id();

        $selectAllInfoForStylist = DB::table('users')
                                    ->join('stylists', 'stylists.user_id', '=', 'users.id')
                                    ->select('stylists.id as stylist_id',
                                            'stylists.salon_id',
                                            'stylists.is_salon_owner',
                                            'users.id as user_id',
                                            'users.username',
                                            'users.email',
                                            'users.fullname',
                                            'users.age',
                                            'users.phone_number',
                                            'users.whats_app',
                                            'users.address',
                                            'users.introduction',
                                            'users.longitude',
                                            'users.latitude',
                                            'users.profile_photo',
                                            'users.background_photo',
                                            'users.user_type'
                                    )
                                    ->where('stylists.user_id', $id)
                                    ->first();

        $reviews   = DB::table('stylist_reviews')
                        ->where('stylist_id', $selectAllInfoForStylist->stylist_id)
                        ->get();
        $posts     = StylePost::where('stylist_id', $selectAllInfoForStylist->stylist_id)->get();
        $followers = StylistFollower::where('stylist_id', $selectAllInfoForStylist->stylist_id)->count();

        return response()->json([
            'stylist'   => $selectAllInfoForStylist,
            'reviews'   => $reviews,
            'posts'     => $posts,
            'followers' => $followers
        ]);
    }

    /**
     * @SWG\Get(
     *     path="/api/stylist/show/{username}",
     *     summary="Get another stylist profile",
     *     tags={"Stylist"},
     *     description="Get profile data",
     *     @SWG\Parameter(
     *         name="username",
     *         in="path",
     *         description="Username",
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
     *     )
     * )
     */
    public function show($username)
    {
        $stylistData = $this->getStylist($username);
        $reviews     = DB::table('stylist_reviews')
                        ->where('stylist_id', $stylistData->stylist_id)
                        ->get();
        $posts       = StylePost::where('stylist_id', $stylistData->stylist_id)->get();

        return response()->json([
            'stylist' => $stylistData,
            'reviews' => $reviews,
            'posts'   => $posts
        ]);
    }

    /**
     * @SWG\Get(
     *     path="/api/stylist/show_review/{username}",
     *     summary="Get stylist profile id",
     *     tags={"Stylist"},
     *     description="Get profile data",
     *     @SWG\Parameter(
     *         name="username",
     *         in="path",
     *         description="Username",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/StylistReview"),
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
    public function showReviews($username)
    {
        $stylist = $this->getStylist($username);
        $reviews = DB::table('stylist_reviews')
                    ->where('stylist_id', '=',  $stylist->id)
                    ->get();

        return response()->json([
            'data'    => $reviews,
            'status'  => 'success',
            'message' => ''
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/api/stylist/add_review/{username}",
     *     summary="Add user review for stylist",
     *     tags={"Stylist"},
     *     description="Add review",
     *     @SWG\Parameter(
     *         name="username",
     *         in="path",
     *         description="Username",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="comment",
     *         in="path",
     *         description="User comment",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="rate",
     *         in="path",
     *         description="User rate",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
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
    public function addReview($username, Request $request)
    {
        $validator = Validator::make($request->all() ,[
            'comment' => 'required',
            'rate'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],422);
        }

        $stylist = $this->getStylist($username);
        $user_id = Auth::id();
        $review  = StylistReview::create([
            'comment'    => $request->comment,
            'rate'       => $request->rate,
            'user_id'    => $user_id,
            'stylist_id' => $stylist->stylist_id
        ]);

        return response()->json([
            'data'    => $review,
            'status'  => 'success',
            'message' => 'The review has been added successfully'
        ]);
    }

    /**
     * @SWG\Get(
     *     path="/api/stylist/show_salon/{username}",
     *     summary="Get stylist profile id",
     *     tags={"Stylist"},
     *     description="Get profile data",
     *     @SWG\Parameter(
     *         name="username",
     *         in="path",
     *         description="Username",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/StylistReview"),
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
    public function showSalon($username)
    {
        $stylist    = $this->getStylist($username);
        $salon      = Stylist::find($stylist->id);
        $salon_data = Salon::find($salon->salon_id);

        return response()->json([
            'data'    => $salon_data,
            'status'  => 'success',
            'message' => ''
        ]);
    }

    private function getStylist($username)
    {
        $stylist = DB::table('stylists')
                    ->join('users', 'stylists.user_id', '=', 'users.id')
                    ->select('stylists.id as stylist_id',
                             'stylists.salon_id',
                             'stylists.is_salon_owner',
                             'users.id as user_id',
                             'users.username',
                             'users.email',
                             'users.fullname',
                             'users.age',
                             'users.phone_number',
                             'users.whats_app',
                             'users.address',
                             'users.introduction',
                             'users.longitude',
                             'users.latitude',
                             'users.profile_photo',
                             'users.background_photo',
                             'users.user_type'
                    )
                    ->where('users.username', 'like', '%' . $username .'%')
                    ->first();

        return $stylist;
    }

    /**
     * @SWG\Post(
     *     path="/api/stylist/follow/{username}",
     *     summary="Add follow",
     *     tags={"Stylist"},
     *     description="Add follow between user and stylist",
     *     @SWG\Parameter(
     *         name="username",
     *         in="path",
     *         description="Username of stylist",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="You have successfully subscribed to username",
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="You are already subscribed to to username",
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     )
     * )
     */
    public function follow($username)
    {
        $user_id      = Auth::id();
        $stylist      = $this->getStylist($username);
        $haveFollower = StylistFollower::where('user_id', $user_id)
                                        ->where('stylist_id', $stylist->stylist_id)
                                        ->get();

        if (!count($haveFollower)) {
            StylistFollower::create([
                'user_id'    => $user_id,
                'stylist_id' => $stylist->stylist_id
            ]);

            $message = "You have successfully subscribed to " . $stylist->username;
            $status  = 200;
        } else {
            $message = "You are already subscribed to " . $stylist->username;
            $status  = 422;
        }

        return response()->json([
            'status'  => $status,
            'message' => $message
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/api/stylist/unfollow/{username}",
     *     summary="Delete follow",
     *     tags={"Stylist"},
     *     description="Delete follow between user and stylist",
     *     @SWG\Parameter(
     *         name="username",
     *         in="path",
     *         description="Username of stylist",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Subscription was successfully deleted",
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="Something went wrong",
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     )
     * )
     */
    public function unfollow($username)
    {
        $user_id      = Auth::id();
        $stylist      = $this->getStylist($username);
        $deleteFollow = StylistFollower::where('user_id', $user_id)
                                        ->where('stylist_id', $stylist->stylist_id)
                                        ->delete();

        if ($deleteFollow) {
            $message = "Subscription was successfully deleted";
            $status  = 200;
        } else {
            $message = "Something went wrong";
            $status  = 422;
        }

        return response()->json([
            'status'  => $status,
            'message' => $message
        ]);
    }
}
