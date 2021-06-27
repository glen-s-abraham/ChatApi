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

Route::group(['middleware'=>['auth:sanctum'],'prefix'=>'chat'],function(){
    Route::post('/message','Chat\ChatController@sendMessage');
    Route::get('/message/{userId}/get','Chat\ChatController@getConversation');
    Route::get('/message/unread/count','Chat\ChatController@getUnreadMessageCount');
    Route::put('/message/{messageId}/markAsRead','Chat\ChatController@markAsRead');
    Route::put('/message/{userId}/markAllAsRead','Chat\ChatController@markAllAsRead');
    Route::get('/my-channel','Chat\ChatController@getMyBroadcastChannel');
    Route::get('/my-conversationList','Chat\ChatController@getMyConversationList');
});

Route::group(['middleware'=>['auth:sanctum'],'prefix'=>'user'],function(){
    Route::get('/profile','User\UserController@profile');
    Route::put('/status/online','Chat\UserStatusController@setMyStatusOnline');
    Route::put('/status/offline','Chat\UserStatusController@setMyStatusOffline');
    Route::get('/status/{userId}','Chat\UserStatusController@getUserStatus');
    Route::get('/logout','User\UserController@logout');

});