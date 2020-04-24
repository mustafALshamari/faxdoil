<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/





// for user

    Route::post('register', 'User\AuthController@register');
    Route::post('login', 'User\AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout','User\AuthController@logout');
    });


//for beauty or salon
    Route::group(['prefix'=>'stylist'], function () {
    Route::post('register', 'Stylist\AuthController@register');

    Route::group(['middleware' => 'auth:api'], function () {
    Route::get('profile','Stylist\SalonController@index');
    Route::post('add_salon', 'Stylist\SalonController@createSalon');
    Route::post('update_salon', 'Stylist\SalonController@updateSalonInfo');
    Route::post('update_images', 'Stylist\SalonController@updateImages');
    Route::post('update_location', 'Stylist\SalonController@updateLocation');
    Route::get('show_my_salon', 'Stylist\SalonController@showMysalon');
    Route::get('show_my_location', 'Stylist\SalonController@showMyLocation');

    });


//for user profile
Route::group(['prefix' => 'profile'], function (){
    Route::get('show', 'User\UserProfileController@show');
    Route::post('update', 'User\UserProfileController@update');
});

Route::group(['middleware' => 'auth:api'], function(){


});

    });
