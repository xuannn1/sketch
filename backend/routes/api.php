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

Route::middleware('auth:api')->group( function(){
    //Route::resource('thread', 'API\ThreadController');
});
Route::post('register', 'API\PassportController@register');
Route::post('login', 'API\PassportController@login')->name('login');

Route::apiResource('thread', 'API\ThreadController');
Route::get('book/{thread}','API\ThreadController@showbook');

Route::apiResource('/thread/{thread}/post', 'API\PostController');
Route::apiResource('/thread/{thread}/chapter', 'API\ChapterController');
Route::apiResource('user', 'API\UserController');
Route::apiResource('status', 'API\StatusController');
Route::apiResource('recommendation', 'API\RecommendationController');

Route::get('/', 'API\PagesController@home')->name('home');
Route::get('config/allTags', 'API\PagesController@allTags');

Route::post('quote', 'API\QuoteController@store');
Route::post('/message/store/{user}', 'API\MessageController@store');

