<?php

namespace App\Http\Controllers\Stylist;

use App\Http\Controllers\Controller;
use Auth;
use App\User;
use DB;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Stylist
 */
class ProfileController extends Controller
{
    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        $id = Auth::id();

        $selectAllInfoForStylist = DB::table('users')
            ->join('stylists', 'stylists.user_id', '=', 'users.id')
            ->where('stylists.user_id', $id)
            ->get();

        return response()->json($selectAllInfoForStylist);
     }
}
