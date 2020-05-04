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
    Route::post('forgot/password', 'User\AuthController@create');
    Route::get('find/{token}', 'User\AuthController@find');
    Route::post('email/reset', 'User\AuthController@reset');

    Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout','User\AuthController@logout');
    });


    //for beauty or salon
    Route::group(['prefix'=>'stylist'], function () {
    Route::post('register', 'Stylist\AuthController@register');

    Route::group(['middleware' => 'auth:api'], function () {
    Route::get('profile','Stylist\SalonController@index');
    Route::post('add_salon', 'Stylist\SalonController@addSalon');
    Route::post('update_salon', 'Stylist\SalonController@updateSalonInfo');
    Route::post('update_images', 'Stylist\SalonController@updateImages');
    Route::post('update_location', 'Stylist\SalonController@updateLocation');
    Route::get('show_my_salon', 'Stylist\SalonController@showMysalon');
    Route::get('show_my_location', 'Stylist\SalonController@showMyLocation');
    //services
    Route::post('add_service', 'Stylist\SalonController@addService');
    Route::get('my_services', 'Stylist\SalonController@listServices');
    Route::get('delete_service/{id}', 'Stylist\SalonController@deleteService');
    //postStyile 
    Route::post('make_style_post', 'Stylist\StylePostController@createStylePost');
    Route::get('delete_post/{id}', 'Stylist\StylePostController@deletePost');
    Route::get('show_all_post', 'Stylist\StylePostController@showAllPosts');
    Route::get('show_post/{id}', 'Stylist\StylePostController@showPost');
    Route::post('update_post/{id}', 'Stylist\StylePostController@updateStylePost');
    });
    });
    
    //for admin
    Route::group(['prefix'=>'admin'], function () {
    Route::post('register', 'Admin\AuthController@register');
    });

