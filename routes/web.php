<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', 'Auth\AuthController@login')->name('login');
Route::post('login', 'Auth\AuthController@check')->name('login');
Route::any('logout', 'Auth\AuthController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function() {
    // Categories
    Route::resource('categories', 'Categories\IndexController')->except([]);
    Route::group(['prefix' => 'categories', 'namespace' => 'Categories'], function(){
        // Remove selected Category
        Route::get('remove/{category_id}', 'IndexController@remove')->name('category.remove');
    });

    // Media
    Route::resource('media', 'Media\IndexController')->except(['store', 'update']);

    Route::group(['prefix' => 'media', 'namespace' => 'Media'], function(){
        // Store new Media
        Route::post('store-photo', 'IndexController@storePhoto')->name('media.store-photo');
        Route::post('store-video', 'IndexController@storeVideo')->name('media.store-video');
        // Update new Media
        Route::put('update-photo/{media_id}', 'IndexController@updatePhoto')->name('media.update-photo');
        Route::put('update-video/{media_id}', 'IndexController@updateVideo')->name('media.update-video');
        // Upload temporary file
        Route::post('upload', 'IndexController@temporary')->name('media.temporary');
        // Remove selected Media
        Route::get('remove/{media_id}', 'IndexController@remove')->name('media.remove');
    });

    // Tags
    Route::resource('tags', 'Tags\IndexController')->except(['show', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::group(['prefix' => 'tags', 'namespace' => 'Tags'], function(){
        // Add Tag
        Route::post('add', 'IndexController@add')->name('tags.add');
        // Remove selected Tag
        Route::get('remove/{tag_id}', 'IndexController@remove')->name('tags.remove');
    });

    // Settings
    Route::group(['prefix' => 'settings', 'namespace' => 'Settings'], function(){
        // Random Media
        Route::get('random', 'IndexController@random')->name('settings.random');
    });

    // Home route
    Route::get('/', 'IndexController@index')->name('home');
});

