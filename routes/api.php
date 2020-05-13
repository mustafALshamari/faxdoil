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
            Route::post('add_salon', 'Stylist\SalonController@addSalon');
            Route::get('profile','Stylist\SalonController@index');
            Route::post('add_salon', 'Stylist\SalonController@addSalon');
            Route::post('update_salon', 'Stylist\SalonController@updateSalon');
            Route::post('update_images', 'Stylist\SalonController@updateImages');
            Route::post('update_location', 'Stylist\SalonController@updateLocation');
            Route::get('show_my_salon', 'Stylist\SalonController@showMysalon');
            Route::get('show_my_location', 'Stylist\SalonController@showMyLocation');
            Route::get('salon/show_my_beauty_pro', 'Stylist\SalonController@showMyBeautyPro');
            Route::get('salon/exclude/{username}', 'Stylist\SalonController@deleteMyBeautyPro');
            Route::post('salon/invite', 'Stylist\SalonController@sendInvitation');
            //services
            Route::get('my_services', 'Stylist\SalonController@listServices');
            Route::get('delete_service/{id}', 'Stylist\SalonController@deleteService');
            Route::get('show_service/{id}', 'Stylist\SalonController@showService');
            Route::post('update_service/{id}', 'Stylist\SalonController@updateService');
            //postStyile
            Route::post('make_style_post', 'Stylist\StylePostController@createStylePost');
            Route::get('delete_post/{id}', 'Stylist\StylePostController@deletePost');
            Route::get('show_my_posts', 'Stylist\StylePostController@showAllPosts');
            Route::get('show_post/{id}', 'Stylist\StylePostController@showPost');
            Route::post('update_post/{id}', 'Stylist\StylePostController@updateStylePost');
            //post put like
            Route::post('put_like/{post_id}', 'Stylist\StylePostController@putLikeToPost');
            //post report
            Route::post('report_post/{post_id}', 'Stylist\StylePostController@reportPost');
            //comment 
            Route::post('create_comment/{post_id}', 'Stylist\StylePostController@createComment');
            Route::post('update_comment/{id}', 'Stylist\StylePostController@updateComment');
            Route::get('delete_comment/{id}', 'Stylist\StylePostController@deleteComment');
        });
    });

    //for admin
    Route::group(['prefix'=>'admin'], function () {
        Route::post('register', 'Admin\AuthController@register');
    });

