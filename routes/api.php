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

Route::post('user/register','User\UserController@register');
Route::post('user/login','User\UserController@login');

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('chat/message','Chat\ChatController@message');
    Route::get('chat/broadcast-channel/my','Chat\ChatController@getMyBroadcastChannel');
    Route::get('user/profile','User\UserController@profile');
    Route::get('user/logout','User\UserController@logout');
    
});