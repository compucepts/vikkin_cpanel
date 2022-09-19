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




 

include "testing.php";

Route::get('Convert','BackgroundCtrl@Convert');


Route::get('capture_robot_income','InvestmentCtrl@capture_robot_income');



Route::get('RefundRobotIncome','InvestmentCtrl@RefundRobotIncome');



Route::get('processInvestmentsHourlyBonus','InvestmentCtrl@processInvestmentsHourlyBonus');




//getbonusin24hour

Route::get('widthdraw_approval','SystemWithdrawCtrl@widthdraw_approval');

Route::get('SpecialRobotPay','InvestmentCtrl@SpecialRobotPay');



Route::get('Bonus_claim','InvestmentCtrl@Bonus_claim');

Route::get('completeMarkInvestment','InvestmentCtrl@completeMarkInvestment');


//Route::get('RobotAutoPayv2','InvestmentCtrl@RobotAutoPayv2');

Route::get('robotautopay','InvestmentCtrl@RobotAutoPayv2');

Route::get('Notification_test','SystemCtrl@Notification_test')->name('system.btcdeposit')  ;



Route::get('system/btcdeposit','BackgroundCtrl@btcdeposit')->name('system.btcdeposit')  ;