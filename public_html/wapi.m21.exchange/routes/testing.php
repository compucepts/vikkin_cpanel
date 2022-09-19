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

 Route::get('checkCompletedRobot','TestBotCtrl@checkCompletedRobot');

/*
 Route::get('checkGiftingRobots','TestBotCtrl@checkGiftingRobots');


//Route::get('testGiftingRobot/{id}','TestBotCtrl@testGiftingRobot');

Route::get('testGiftingRobot','TestBotCtrl@testGiftingRobot22');



 Route::get('startInvestment/{id}','TestBotCtrl@startInvestment')   ;
 
 
 
 
Route::get('checkActiveRobot','CheckPoint@checkActiveRobot');

 */