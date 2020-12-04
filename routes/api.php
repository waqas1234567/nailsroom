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

Route::post('/registerUser', 'api\apiController@store');
Route::post('/loginUser', 'api\apiController@login');
Route::post('/forgetPassword', 'api\apiController@forgetPassword');
Route::post('/resetPassword', 'api\apiController@resetPassword');
Route::get('/email/verify/{id}', 'api\VerificationApiController@verify')->name('verificationapi.verify');
Route::get('/email/resend', 'api\VerificationApiController@resend')->name('verificationapi.resend');
Route::post('/checkStatus', 'api\apiController@checkStatus');


Route::middleware('auth:api')->get('/user', function(Request $request) {
    return $request->user();
});



Route::middleware('auth:api')->get('/news/{category}', 'api\apiController@getNews');
Route::middleware('auth:api')->get('/brands', 'api\apiController@getBrands');
Route::middleware('auth:api')->get('/stores', 'api\apiController@getStores');
Route::middleware('auth:api')->post('/Notifications', 'api\apiController@getNotifications');
Route::middleware('auth:api')->post('/readNotification', 'api\apiController@readNotification');
Route::middleware('auth:api')->post('/resetEmail', 'api\apiController@resetEmail');
Route::middleware('auth:api')->get('/privacy', 'api\apiController@privacy');
Route::middleware('auth:api')->get('/getPremiumNews', 'api\apiController@getPremiumNews');
Route::middleware('auth:api')->get('/getSubscriptionPrice', 'api\apiController@getSubscriptionPrice');
Route::middleware('auth:api')->post('/addSubscriptionData', 'api\apiController@addSubscriptionData');
Route::middleware('auth:api')->post('/getPaymentMethods', 'api\apiController@getPaymentMethods');
Route::middleware('auth:api')->post('/registerPayment', 'api\apiController@registerPayment');
Route::middleware('auth:api')->post('/subscriptionStatus', 'api\apiController@subscriptionStatus');
Route::middleware('auth:api')->post('/cancelSubscription', 'api\apiController@cancelSubscription');
Route::middleware('auth:api')->post('/getPatterns', 'api\apiController@getPatterns');
