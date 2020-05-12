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
        //for user profile
        Route::get('profile/show', 'User\UserProfileController@show');
        Route::get('profile/show_user/{username}', 'User\UserProfileController@showUser');
        Route::post('profile/update', 'User\UserProfileController@update');
    });
    //for beauty or salon
    Route::group(['prefix'=>'stylist'], function () {
    Route::post('register', 'Stylist\AuthController@register');
    Route::get('find/salon/{id}', 'Stylist\SalonController@findSalon');
        Route::group(['middleware' => 'auth:api'], function () {
            //for stylist info
            Route::get('show_my_profile','Stylist\ProfileController@showMyProfile');
            Route::post('add_salon', 'Stylist\SalonController@addSalon');
            Route::get('show/{username}','Stylist\ProfileController@show');
            Route::post('update','User\UserProfileController@update');
            //for follow/unfollow
            Route::post('follow/{username}','Stylist\ProfileController@follow');
            Route::post('unfollow/{username}','Stylist\ProfileController@unfollow');
            //reviews
            Route::get('show_reviews/{username}','Stylist\ProfileController@showReviews');
            Route::post('add_review/{username}','Stylist\ProfileController@addReview');
            //for salon
            Route::get('show_salon/{username}','Stylist\ProfileController@showSalon');
            //for salon
            Route::post('add_salon', 'Stylist\SalonController@addSalon');
            Route::get('profile','Stylist\SalonController@index');
            Route::post('add_salon', 'Stylist\SalonController@addSalon');
            Route::post('update_salon', 'Stylist\SalonController@updateSalonInfo');
            Route::post('update_images', 'Stylist\SalonController@updateImages');
            Route::post('update_location', 'Stylist\SalonController@updateLocation');
            Route::get('show_my_salon', 'Stylist\SalonController@showMysalon');
            Route::get('show_my_location', 'Stylist\SalonController@showMyLocation');
            Route::post('salon/invite', 'Stylist\SalonController@sendInvitation');
            //services
            Route::post('add_service', 'Stylist\SalonController@addService');
            Route::get('my_services', 'Stylist\SalonController@listServices');
            Route::get('delete_service/{id}', 'Stylist\SalonController@deleteService');
            Route::get('show_service/{id}', 'Stylist\SalonController@showService');
            Route::post('update_service/{id}', 'Stylist\SalonController@updateService');
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
        //  Articles
        Route::post('add_article', 'Admin\ArticlesController@addArticle');
        Route::get('show_article', 'Admin\ArticlesController@showArticle');
        Route::post('update_article/{article}', 'Admin\ArticlesController@updateArticle');
    });

