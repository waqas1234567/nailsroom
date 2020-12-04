<?php

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
use Vimeo\Laravel\Facades\Vimeo;
Route::get('/', 'MarkiController@index');

Auth::routes();

Route::get('/marki', 'MarkiController@index')->name('home')->middleware('checkPermission:1');
Route::get('/ajaxMarki', 'MarkiController@ajaxMarki')->name('ajaxMarki');

//Route::get('/marki', 'MarkiController@ajaxMarki')->name('markiAjax');


Route::get('/newsy', 'NewsController@index')->name('news')->middleware('checkPermission:5');
Route::get('/ajaxnewsy', 'NewsController@ajaxnewsy')->name('ajaxnewsy');

Route::get('/add-newsy', 'NewsController@create')->middleware('checkPermission:5');
Route::post('/add-newsy', 'NewsController@store')->middleware('checkPermission:5');
Route::get('/editNewsy/{id}', 'NewsController@edit')->middleware('checkPermission:5');
Route::post('/update-newsy', 'NewsController@update')->middleware('checkPermission:5');
Route::get('/delete-news/{id}', 'NewsController@destroy')->middleware('checkPermission:5');
Route::get('/logout', 'HomeController@logout');

//marki routes

Route::get('/marki/add-marki', 'MarkiController@create')->middleware('checkPermission:1');
Route::post('/marki/add-marki', 'MarkiController@store')->middleware('checkPermission:1');
Route::get('/marki/edit-marki/{id}', 'MarkiController@edit')->middleware('checkPermission:1');
Route::post('/marki/edit-marki', 'MarkiController@update')->middleware('checkPermission:1');
Route::get('/marki/delete-marki/{id}', 'MarkiController@destroy')->middleware('checkPermission:1');

//collections routes



Route::get('/marki/kolekcje/{id}', 'CollectionController@show')->middleware('checkPermission:1');
Route::get('/ajaxKolekcje/{id}', 'CollectionController@ajaxKolekcje')->name('ajaxKolekcje');

Route::get('/marki/dodaj-kolekcje/{id}', 'CollectionController@create')->middleware('checkPermission:1');
Route::post('/marki/dodaj-kolekcje', 'CollectionController@store')->middleware('checkPermission:1');
Route::get('/marki/edytuj-kolekcje/{id}', 'CollectionController@edit')->middleware('checkPermission:1');
Route::post('/marki/edytuj-kolekcje', 'CollectionController@update')->middleware('checkPermission:1');
Route::get('/marki/delete-collection/{id}/{brand_id}', 'CollectionController@destroy')->middleware('checkPermission:1');

//pattern

Route::get('/marki/wzór/{id}', 'patternController@show')->middleware('checkPermission:1');
Route::get('/ajaxpattern/{id}', 'patternController@ajaxpattern')->name('ajaxpattern');
Route::get('/marki/add-wzór/{id}', 'patternController@create')->middleware('checkPermission:1');
Route::post('/marki/dodaj-wzór', 'patternController@store')->middleware('checkPermission:1');
Route::get('/pattern/delete/{id}/{brand_id}', 'patternController@destroy')->middleware('checkPermission:1');
Route::get('/marki/edytuj-wzór/{id}', 'patternController@edit')->middleware('checkPermission:1');
Route::post('/marki/edytuj-wzór', 'patternController@update')->middleware('checkPermission:1');




//color routes

Route::get('/marki/kolor/{id}', 'ColorController@create')->middleware('checkPermission:1');
Route::post('/marki/dodaj-kolor', 'ColorController@store')->middleware('checkPermission:1');
Route::get('/marki/delete-color/{id}', 'ColorController@destroy')->middleware('checkPermission:1');

//stores

Route::get('/sklepy', 'StoreController@index')->name('stores')->middleware('checkPermission:2');
Route::get('/ajaxsklepy', 'StoreController@ajaxsklepy')->name('ajaxsklepy');

Route::get('/sklepy/dodaj-sklepy', 'StoreController@create')->middleware('checkPermission:2');
Route::get('/sklepy/delete-sklepy/{id}', 'StoreController@destroy')->middleware('checkPermission:2');
Route::get('/sklepy/edytować-sklepy/{id}', 'StoreController@edit')->middleware('checkPermission:2');
Route::post('/dodaj-sklepy', 'StoreController@store')->middleware('checkPermission:2');
Route::post('/getCollections', 'StoreController@getCollections')->middleware('checkPermission:2');
Route::post('/getCollectionsedit', 'StoreController@getCollectionsedit')->middleware('checkPermission:2');
Route::post('/saveCollections', 'StoreController@saveCollections')->middleware('checkPermission:2');
Route::post('/editCollections', 'StoreController@editCollections')->middleware('checkPermission:2');
Route::post('/sklepy/update-store', 'StoreController@update')->middleware('checkPermission:2');

//users
Route::get('/uzytkownicy', 'UserController@index');
Route::get('/ajaxuser', 'UserController@ajaxuser')->name('ajaxuser');

Route::get('uzytkownicy/delete-user/{id}', 'UserController@destroy');

//notificatiom

Route::get('/powiadomienia', 'NotificationController@index')->middleware('checkPermission:6');
Route::get('/ajaxnotification', 'NotificationController@ajaxnotification')->name('ajaxnotification');
Route::get('/powiadomienia/dodaj-powiadomienia', 'NotificationController@create')->middleware('checkPermission:6');
Route::post('/powiadomienia/dodaj-powiadomienia', 'NotificationController@store')->middleware('checkPermission:6');
Route::get('/powiadomienia/edytowac-powiadomienia/{id}', 'NotificationController@edit')->middleware('checkPermission:6');
Route::post('/powiadomienia/edytowac-powiadomienia', 'NotificationController@update')->middleware('checkPermission:6');
Route::get('/powiadomienia/delete-notification/{id}', 'NotificationController@destroy')->middleware('checkPermission:6');



//Admin
Route::get('/admin', 'AdminController@index');
Route::get('/ajaxadmin', 'AdminController@ajaxadmin')->name('ajaxadmin');


Route::get('/admin/dodaj-administratora', 'AdminController@create');
Route::get('/admin/edytowac-administratora/{id}', 'AdminController@edit');
Route::post('/admin/edytowac-administratora', 'AdminController@update');
Route::get('/admin/delete-admin/{id}', 'AdminController@destroy');

Route::post('/admin/dodaj-administratora', 'AdminController@store');


//privacy

Route::get('/Prywatność', 'PrivacyController@create');
Route::post('/Prywatność', 'PrivacyController@store');

//pre


Route::get('/Premium', 'premiumController@index');

Route::post('/addPremium','premiumController@store');
Route::get('/checkSubscriptionStatus', 'premiumController@checkSubscriptionStatus');
Route::get('/paymentstatus/', 'premiumController@paymentstatus');
Route::post('/checkStatus/', 'premiumController@checkStatus');



Route::get('/verifyPayment/', 'premiumController@verifyPayment');
Route::get('/generateSign', 'premiumController@generateSign');


//Route::get('/getPremiumNewsContent',function () {
//});


Route::get('/email',function (){
    return new App\Mail\subscriptionEmail();
});
