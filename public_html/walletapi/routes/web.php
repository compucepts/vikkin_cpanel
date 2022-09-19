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

Route::get('/', function () {
    return view('welcome');
});


 
 	



Route::get('Notification_test','SystemCtrl@Notification_test')->name('system.btcdeposit')  ;



Route::get('system/btcdeposit','BackgroundCtrl@btcdeposit')->name('system.btcdeposit')  ;