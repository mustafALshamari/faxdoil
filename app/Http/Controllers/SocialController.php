<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Socialite;
use App\User;

/**
 * Class SocialController
 * @package App\Http\Controllers
 */
class SocialController extends Controller
{
    /**
     * @param $provider
     *
     * @return mixed
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param $provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->stateless()->user();
        $user    = $this->createUser($getInfo,$provider);

        auth()->login($user);

        return redirect()->to('/home');

    }

    /**
     * @param $getInfo
     * @param $provider
     *
     * @return mixed
     */
    public function createUser($getInfo,$provider)
    {
        $user = User::where('provider_id', $getInfo->id)->first();

        if (!$user) {
            $user = User::create([
                'username'     => $getInfo->name,
                'email'        => $getInfo->email,
                'provider'     => $provider,
                'provider_id'  => $getInfo->id
            ]);
        }

        return $user;
    }
}
