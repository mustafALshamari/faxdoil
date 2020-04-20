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
    Route::post('logout','User\AuthController@logout');
  

//for beauty or salon 
    Route::group(['prefix'=>'stylist'], function () {
      Route::post('register', 'Stylist\AuthController@register');




      Route::group(['middleware' => 'auth:api'], function () {
      Route::get('profile','Stylist\ProfileController@index');
      Route::post('logout','User\AuthController@logout');
    
      });

});
//for admin 


Route::group(['prefix' => 'admin'], function () {
    Route::post('register', 'AdminController@register');
    Route::post('login', 'AdminController@login');

});


Route::group(['middleware' => 'auth:api'], function(){

    
});