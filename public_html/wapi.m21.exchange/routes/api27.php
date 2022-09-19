<?php
 

Route::any('auth/send_otp/{email}','Auth\AuthController@send_otp')->name('RobotIls')  ;




 Route::middleware(['auth:api'])->group(function () {

 Route::get('auth/api27/referral_income_list','ReferCtrl@referral_income_list')  ;
 
 });
 
 
   
   Route::any('refer_income','InvestmentCtrl@refer_income')   ;