<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::namespace('api')->group(function(){

    Route::prefix('/auth')->namespace('Auth')->group(function (){
         Route::post('/login', 'AuthController@login');
         Route::post('/register', 'RegisterController@store');
         Route::post('/forgot', 'ForgotPasswordController@reset')->name('forgotPassword.sendResetLinkEmail');
         Route::get('/logout', 'AuthController@logout');
    });

    Route::prefix('/profile')->namespace('profile')->group(function (){
        Route::post('/{id}/follow', 'ProfileController@follow');
        Route::get('/{id}', 'ProfileController@show')->name('profile.show');
        Route::put('/{id}', 'ProfileController@update')->name('profile.update');
    });

    Route::prefix('/skill')->namespace('skill')->group(function (){
        Route::get('/', 'SkillController@index')->name('skill.index');
        Route::post('/{id}', 'SkillController@store')->name('skill.store');
    });

    Route::prefix('/user')->namespace('user')->group(function (){
        Route::get('/search', 'UserController@search')->name('profile.search');
        Route::put('/{id}', 'UserController@update')->name('profile.update');
    });

    Route::prefix('/email')->namespace('email')->group(function (){
        Route::get('/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
        Route::get('/resend', 'VerificationController@resend')->name('verification.resend');
    });

    Route::prefix('/post')->namespace('post')->group(function (){
        Route::get('/{id}/like', 'PostController@like')->name('post.like');

        Route::post('/', 'PostController@store')->name('post.store');
        Route::get('/{id}', 'PostController@show')->name('post.show');
        Route::put('/{id}', 'PostController@update')->name('post.update');
        Route::delete('/{id}', 'PostController@destroy')->name('post.delete');

        Route::prefix('{id}/comment')->group(function (){
            Route::post('/', 'CommentController@store')->name('comment.store');
            Route::put('/{commentId}', 'CommentController@update')->name('comment.update');
            Route::delete('/{commentId}', 'CommentController@destroy')->name('comment.destroy');
            Route::get('/{commentId}/like', 'CommentController@like')->name('comment.like');
            Route::get('/', 'CommentController@show')->name('comment.show');
 });

    });




});


