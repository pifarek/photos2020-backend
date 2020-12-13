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

// Categories
Route::group(['prefix' => 'categories', 'namespace' => 'Api'], function(){
   Route::get('list', 'Categories@list');
});

// Tags
Route::group(['prefix' => 'tags', 'namespace' => 'Api'], function(){
    Route::get('list', 'Tags@list');
});

// Media
Route::group(['prefix' => 'media', 'namespace' => 'Api'], function(){
    Route::get('list', 'Media@list');
    Route::get('get/{media_id}', 'Media@get');
});

// Others
Route::group(['prefix' => 'mail', 'namespace' => 'Api'], function() {
    // Send message
    Route::post('send', 'MailController@send');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
