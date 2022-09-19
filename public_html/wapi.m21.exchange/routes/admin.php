<?php
 


 Route::get('/auth/first/startInvestment/{id}','Admin2Ctrl@startInvestment')   ;
 
 
 
 

Route::any('private_blockchian_deposit','BackgroundCtrl@private_blockchian_deposit')->name('RobotIls')  ;


 

  Route::middleware(['auth:api'])->group(function () {

 Route::get('/auth/system/users','Admin2Ctrl@LedgetUsers')   ;
 Route::get('/auth/system/users/{query}','Admin2Ctrl@getUsersByQuery')   ;
 
 
 
 
 
 
 Route::get('/auth/first/fetch_investment/{robot_id}','Admin2Ctrl@fetch_investment')   ;
 
 
 
 
 Route::get('/auth/first/fetch_investments/{user_id}','Admin2Ctrl@fetch_investments')   ;
 
 
 
 
 Route::get('/auth/first/fetch_transactions/{user_id}','Admin2Ctrl@fetch_transactions')   ;
 
 
 
 
 Route::post('/auth/first/widthdraw_completion_form_submit','Admin2Ctrl@widthdraw_completion_form_submit')   ;
 
 
 
 Route::get('/auth/first/fetch_widthdraw_requests_for_user/{user_id}','Admin2Ctrl@fetch_widthdraw_requests_for_user')   ;
 
 
 
 
 Route::get('/auth/admin/fetch_all_investments','Admin2Ctrl@fetch_all_investments')   ;
 
 
 Route::get('/auth/admin/fetch_recovery_robot','Admin2Ctrl@fetch_recovery_robot')   ;
 
 
 
 
 

 Route::get('/auth/first/fetchreferral_bonusAdmin','Admin2Ctrl@fetchreferral_bonusAdmin')   ;


 Route::get('/auth/first/fetchrobot_bonusAdmin','Admin2Ctrl@fetchrobot_bonusAdmin')   ;



 Route::get('/auth/first/LedgetTransactionsdepositsWithCoin','Admin2Ctrl@LedgetTransactionsdepositsWithCoin')   ;



 Route::get('/auth/first/fetch_bonus_claims','Admin2Ctrl@fetch_bonus_claims')   ;



 Route::get('/auth/first/fetch_widthdraw_requests','Admin2Ctrl@fetch_widthdraw_requests')   ;
 
 
 
 Route::get('/auth/first/dashboardv3','Admin2Ctrl@admindashboard')   ;
 

 Route::post('/auth/admin/wallet/debit_credit_user','Admin2Ctrl@debit_credit_user')   ;
 
 
// Route::post('/auth/admin/wallet/debit_credit_user','Admin2Ctrl@debit_credit_user')   ;
 
 
 

 Route::post('/auth/admin/investment/delete','Admin2Ctrl@investment_delete')   ;
 
 
 Route::post('/auth/admin/robot/speical_robot_submit','Admin2Ctrl@speical_robot_submit')   ;
 
 
 
 Route::get('/auth/admin/user/userinfobyid/{user_id}','Admin2Ctrl@getuserinfo')   ;
 
 
 
  });