<?php

namespace App\Http\Controllers\Stylist;
use App\Stylist;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use DB;
class ProfileController extends Controller
{
    /**
     * @return
     * the Stylist info from both tables
     * User and Styist
     */
    public function index(User $user)
    {
        $id = Auth::id();
        $selectAllinfoForStylist =  DB::table('users')
                                    ->join('stylists', 'stylists.user_id', '=', 'users.id')
                                    ->where('stylists.user_id', $id)
                                    ->get();
                                //  $stylist = User::findOrfail($id)->user;

                  return response()->json($selectAllinfoForStylist);
     }



}
