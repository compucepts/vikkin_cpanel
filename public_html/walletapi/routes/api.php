<?php

use Illuminate\Http\Request;

header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: *');
 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|/home/ytubhhsc/alphakoin.conectate.club/walletapiv3/routes/api.php
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/   

Route::post('RegisterAPI','Auth\AuthController@getRegisterAPI')->name('RegisterAPIgetRegisterAPI')  ;


 Route::middleware(['auth:api'])->group(function () {
    
 Route::post('v1/Transactions','ApiPixCtrl@Transactions') ->name('sApiPixCtrgetQRCodeDynamicQRCode');
 
});

    
//Route::post('RegisterAPI', 'Auth\AuthController@getRegisterAPI')->name('AuthToken');
     


Route::get('send_notification_background_job','BTVCv2Ctrl@send_notification_background_job')->name('send_notification_background_job')  ;



Route::get('send_notification/{address}','BTVCv2Ctrl@send_notification')->name('RegisterAPIsend_notification')  ;



//  Route::prefix('pixcoinCtrl')->group( function() {
        Route::get('getdeposit/{amount}/{currency}','pixcoinCtrl@getdeposit')->name('pixcoinCtrl@getdeposit')  ;
          Route::get('getdepositbyid/{id}/','pixcoinCtrl@getdepositbyid')->name('pixcoinCtrl@getdepositbyid')  ;
//  });
 
 Route::middleware(['auth:api'])->group(function () {
    
        
Route::get('auth/AllNotification','SystemCtrl@AllNotification')->name('AllNotification')  ;



Route::get('auth/FiatBalance','SystemCtrl@FiatBalance')->name('FiatBalance')  ;

Route::get('auth/get_notification_url','SystemCtrl@get_notification_url')->name('get_notification_url')  ;



Route::post('auth/post_notification_url','SystemCtrl@post_notification_url')->name('post_notification_url')  ;



});


Route::get('dashboard','SystemCtrl@dashboard')->name('ExchangeCtrlpdashboardrocess_limit_trade')  ;


Route::post('swapcoinprocessExchangeRate','ExchangeCtrl@swapcoinprocessExchangeRate')->name('ExchangeCtgetExchangeRate')  ;




Route::get('processTrade','ExchangeCtrl@process_limit_trade')->name('ExchangeCtrlprocess_limit_trade')  ;


  Route::get('erate_v2','ExchangeCtrl@erate_v2')->name('ExchangeCtrlerate_v2')  ;
 
   Route::get('mailtest','Auth\AuthController@mailtest')->name('ExchangeCtmailtest')  ;
 
 
 
   Route::get('makePreimum','BackgroundCtrl@makePreimum')->name('BackgroundCtrlmakePreimum')  ;
 
 
   Route::get('mine/{{address}}','BackgroundCtrl@mine')->name('BackgroundCtrlmakePreimum');
 
 Route::get('rate','SystemCtrl@rate')->name('system.rate')  ;
 
   Route::get('refermember', 'Auth\AuthController@refermember')->name('AuthControllerrefermember');
    
     
   Route::get('auth/loginguard', 'Auth\AuthController@loginguard')->name('AuthControllerfloginguardord');
     
     
   Route::post('auth/forgetpassword', 'Auth\AuthController@forgetpassword')->name('AuthControllerforgetpassword');
   
     
     
   Route::post('auth/resetforgetpassword', 'Auth\AuthController@resetforgetpassword')->name('AuthControllerforgetpassword');
   
      Route::post('auth/googleauthforgetpassword', 'Auth\AuthController@googleauthforgetpassword')->name('AuthControllergoogleauthforgetpassword');
   
     
     
   Route::post('auth/googleauthresetforgetpassword', 'Auth\AuthController@googleauthresetforgetpassword')->name('AuthControllergoogleauthresetforgetpassword');
   
   
   
   
   
   Route::get('wallet_list','CoinCls@wallet_list')->name('SystemCtrlwallet_listgetlogo')  ;
     
   Route::get('getlogo','SystemCtrl@getlogo')->name('SystemCtrlgetlogo')  ;
   
           
   Route::get('auth/getCoin','SystemCtrl@getCoin')->name('SystemCtrlgetCoin')  ;
   
           
         
   Route::get('getRateHistory','SystemCtrl@getRateHistory')->name('SystemCtrlgetRateHistory')  ;
   
         
   Route::get('getRateHistoryTime','SystemCtrl@getRateHistory')->name('SystemCtrlgetRateHistory')  ;
   
         
   Route::get('getRateHistoryTime2','SystemCtrl@getRateHistoryWithTime')->name('SystemCtrlgetRateHistory')  ;
   
         
      
    
       
Route::get('blocks','SystemCtrl@blocks')->name('SystemCtrlblocks')  ;
   
   
 Route::get('v1/networkfee','SystemCtrl@networkfee')->name('system.rate')  ;
 
 
 




    
   Route::get('all','SystemCtrl@allWallet')->name('SystemCtrl@NewWallet')  ;
 
 
    
   Route::get('getsettings1','SystemCtrl@getsettings')->name('SystemCgetsettingsoin')  ;
   
    
   Route::get('getrates','SystemCtrl@getrates')->name('SystemCgegetratestsettingsoin')  ;
   
   Route::get('dashboard','SystemCtrl@dashboard')->name('SystemCtrlgetdashboard')  ;
   
    
 Route::middleware(['auth:api'])->group(function () {
     
    
        
Route::prefix('v1')->group( function() {
    
    
 //Route::post('getQRCode','ApiPixCtrl@getQRCodeDynamic') ->name('systApiPixCtrlem.getQRCode');
 //Route::post('checkTransactions','ApiPixCtrl@checkTransactions') ->name('sApiPixCtrlystem.getQRCode');
 // Route::post('getTransactions','ApiPixCtrl@getTransactions') ->name('sApiPixCtrlystem.getQRCode');
 
  //Route::post('Transactions','ApiPixCtrl@getQRCodeDynamic') ->name('sApiPixCtrgetQRCodeDynamicQRCode');
 
  
});
    



    Route::prefix('v3')->group( function() {
     
 Route::prefix('wallet')->group( function() {
     
   
   Route::get('Dashboard','SystemCtrlV3@Dashboard')->name('SystemCtrlV3Dashboard')  ;
 
 
     
    });
     
    });
    
    
    
    
    //trade
    
     Route::prefix('trade')->group( function() {
     
   
   Route::post('posttrade','Tradectrl@posttrade')->name('Tradectrlposttrade')  ;
 
 
     
    });
    
    
    
    
    
    
    
Route::get('get_notification_url','SystemCtrl@get_notification_url')->name('get_notification_url')  ;



Route::post('post_notification_url','SystemCtrl@post_notification_url')->name('post_notification_url')  ;


    
    Route::prefix('v1')->group( function() {
    
    //btvc controller

Route::get('listunspentbywallet/{wallet}','BTVCCtrl@listunspentbywallet')->name('BTVCCtrl@listunspentbywallet')  ;
 Route::get('getNewwallet','BTVCCtrl@getNewwallet')->name('BTVCCtrl@getNewwallet')  ;
 Route::post('NewBalance','BTVCCtrl@NewBalance')->name('BTVCCtrlNewBalance')  ;
// Route::post('sendFund','BTVCCtrl@sendFund')->name('BTVCCtrlsendFund')  ;
Route::get('Wallet','BTVCCtrl@Wallet')->name('BTVCCtrl@Wallet')  ;

});



   
    Route::prefix('v2')->group( function() {
    
    //btvc controller
    
    
    
    
    
     Route::get('walletreport','BTVCv2Ctrl@walletreport')->name('BTVCv2Ctrl@walletreportwalletreport')  ;
     
     
     
     
    
     Route::get('walletlist','BTVCv2Ctrl@walletlist')->name('BTVCv2Ctrl@walletlist')  ;
     
     
     
     
     
      Route::get('walletaddress','BTVCv2Ctrl@walletaddress')->name('BTVCv2Ctrl@walletaddress')  ;
      
      
      
       Route::get('addressbybalance','BTVCv2Ctrl@addressbybalance')->name('BTVCv2Ctrl@addressbybalance')  ;
     

Route::get('listunspentbywallet/{wallet}','BTVCv2Ctrl@listunspentbywallet')->name('BTVCv2Ctrl@listunspentbywallet')  ;


 Route::get('getNewwallet','BTVCv2Ctrl@getNewwallet')->name('BTVCv2Ctrl@getNewwallet')  ;
 
 

 Route::get('getAllAddress','BTVCv2Ctrl@getAllAddress')->name('BTVCv2Ctrl@getAllAddress')  ;
 Route::get('NewAddress','BTVCv2Ctrl@getNewAddress')->name('BTVCv2Ctrl@getNewAddress')  ;
 
 
 Route::post('InvoiceAddress','BTVCv2Ctrl@InvoiceAddress')->name('BTVCv2Ctrl@InvoiceAddress')  ;
 
 
 Route::post('NewBalance','BTVCv2Ctrl@NewBalance')->name('BTVCv2Ctrl')  ;
 
 
 Route::get('AddressBalance/{address}','BTVCv2Ctrl@AddressBalance')->name('BTVCvAddressBalance2Ctrl')  ;
 
 
 //Route::post('sendFund','BTVCv2Ctrl@sendFund')->name('BTVCv2Ctrl3434')  ;
 
 
Route::get('Wallet','BTVCv2Ctrl@Wallet')->name('BTVCv2Ctrl@Wallet')  ;



});



 
 
 
 Route::prefix('wallet')->group( function() {
     
     /* new calls */
     
     
     
       Route::get('send_fund_email_otp','SystemCtrl@send_fund_email_otp')->name('SystemCtrl@send_fund_email_otp')  ;
     
   Route::get('v2/Balance','SystemCtrl@Balance_v2')->name('SystemCtrl@Balance_v2')  ;
   
   
   Route::get('v2/Balance','SystemCtrl@Balance_v2')->name('SystemCtrl@Balance_v2')  ;
   
       
   Route::get('v2/getExchangeRate','SystemCtrl@getExchangeRate')->name('SystemCtrl@getExchangeRate')  ;
   
   
   
Route::get('system_notification','SystemCtrl@system_notification') ->name('SystemCtrl@system_notification')  ;

Route::get('login_history','SystemCtrl@login_history') ->name('SystemCtrl@login_history')  ;

   
Route::get('pix_deposit_history','PixCtrl@pix_deposit_history') ->name('PixCtrl@pix_deposit_history')  ;
Route::get('getdepositdetails/{id}/','PixCtrl@getdepositdetails')->name('PixCtrl@getdepositdetails')  ;
   
   
     /* new calls end */
     
    
       Route::get('transaction_details/{txid}','SystemCtrl@transaction_details')->name('SystemCtrltransaction_details')  ;
       
   Route::get('getsettings','SystemCtrl@getsettings')->name('SystemCtrlgetsettingsoin')  ;
    //btvc controller

Route::get('listunspentbywallet/{wallet}','BTVCCtrl@listunspentbywallet')->name('BTVCCtrl@listunspentbywallet')  ;
 Route::get('getNewwallet','BTVCCtrl@getNewwallet')->name('BTVCCtrl@getNewwallet')  ;
 Route::post('NewBalance','BTVCCtrl@NewBalance')->name('BTVCCtrlNewBalance')  ;
 Route::post('sendFund','BTVCv2Ctrl@sendFund')->name('BTVCCtrlsendFund')  ;
Route::get('Wallet','BTVCCtrl@Wallet')->name('BTVCCtrl@Wallet')  ;

    Route::post('send_payment_user','SystemCtrl@send_payment_user')->name('SystemCtrlsend_payment_user');
    
   Route::post('setsettings','SystemCtrl@setsettings')->name('SystemCtrlgetCexchange_rateoin')  ;
   
      Route::get('ETHgenerateAddressPrivateBlockChain','SystemCtrl@ETHgenerateAddressPrivateBlockChain')->name('SystemCtrlETHgenerateAddressPrivateBlockChain')  ;
      
           Route::get('BalanceETH','SystemCtrl@BalanceETH')->name('SystemCtrlBalanceETH')  ;
   
   
    
 Route::get('/getBtcAddress', 'SystemCtrl@getBitcoinAddress')->name('user.ExchangeCtrlgetBtcAddress') ; 
 
    
 Route::post('/getInvoiceBtc', 'SystemCtrl@getInvoiceBtc')->name('user.ExchangeCtrlgetBtcAddress') ; 
    
 Route::post('/getInvoiceFiat', 'SystemCtrl@getInvoiceFiat')->name('user.ExchangeCtrlgetInvoiceFiat') ; 
 
    
 Route::post('/swapcoinprocess', 'SystemCtrl@swapcoinprocess')->name('user.ExchangeCtrlswapcoinprocess') ;
    
 Route::post('/widthdraw_coin_process', 'SystemCtrl@widthdraw_coin_process')->name('widthdraw_coin_process') ;
 
 
 Route::get('/getallwithdrawal', 'SystemCtrl@getallwithdrawal')->name('user.getallsawp') ; 
 
 
  Route::get('/getallwithdrawalusers', 'SystemCtrl@getallwithdrawalusers')->name('SystemCtrl@getallwithdrawalusers') ;
  Route::get('/getwithdrawal_details/{i}', 'SystemCtrl@getwithdrawal_details')->name('SystemCtrl@getwithdrawal_details') ;
  
  
    Route::get('/getwithdrawal_update', 'SystemCtrl@getwithdrawal_update')->name('SystemCtrl@getwithdrawal_update') ;
 
   
    Route::post('/deposit_slip', 'SystemCtrl@deposit_slip')->name('SystemCtrl@deposit_slip') ;
    
    
    
    
 Route::post('/buycoinprocess', 'SystemCtrl@buycoinprocessFiat')->name('user.ExchangeCtrlgetBtcAddress') ; 
    
 Route::get('/deposit_details/{id}', 'SystemCtrl@deposit_details')->name('deposit_detailstcAddress') ; 
 
 Route::get('/getallsawp', 'SystemCtrl@getallsawp')->name('user.getallsawp') ; 
 
 
 Route::get('/getalldeposits', 'SystemCtrl@getalldeposits')->name('user.getalldeposits') ; 
 
 

 
 

 
Route::get('blocks','SystemCtrl@blocks')->name('SystemCtrlblocks')  ;

Route::get('SearchHash','SystemCtrl@SearchHash')->name('SystemCtrlSearchHash')  ;


Route::get('UnconfirmedTransactions','SystemCtrl@UnconfirmedTransactions')->name('SystemCtrlUnconfirmedTransactions')  ;



 Route::get('latest_blocks','SystemCtrl@latest_blocks')->name('SystemCtrllatest_blocks')  ;
 
 
 
  Route::get('transactions','SystemCtrl@transactions')->name('SystemCtrltransactions')  ;
   Route::post('NewWallet','SystemCtrl@NewWallet')->name('SystemCtrl@NewWallet')  ;
 
 
  Route::get('generatenewaddress','SystemCtrl@generatenewaddress')->name('SystemCtrl@generatenewaddress')  ;
  
    Route::get('newaddressbalance','SystemCtrl@newaddressbalance')->name('SystemCtrl@newaddressbalance')  ;
  
 Route::get('Balance','SystemCtrl@Balance')->name('SystemCtrl@Balance')  ; 
 
 
 // fetch balance api
   
   
 
  // Route::any('Balance','SystemCtrl@Balance')->name('SystemCtrl@Balance')  ;
   
   
   
 
 Route::get('getBalance/{address}','SystemCtrl@getBalancebyaddress')->name('SystemCtrl@getBalancebyaddress')  ;
 
   Route::get('BalanceByAddress/{address}','SystemCtrl@BalancebygivenID')->name('SystemCtrl@Balance')  ;
 

Route::get('listunspent','SystemCtrl@listunspent')->name('SystemCtrl@listunspent')  ;




 
 
    Route::get('listwallets','SystemCtrl@listwallets')->name('SystemCtrl@listwallets')  ;
   
  Route::get('peerinfo','SystemCtrl@peerinfo')->name('SystemCtrl@peerinfo')  ;
   Route::get('blockcount','SystemCtrl@blockcount')->name('SystemCtrl@blockcount')  ;
   
   

 
   Route::get('sendFund/{user_key}/{toAddress}/{amount}','SystemCtrl@transferbnexx')->name('SystemCtrltransferbnexx')  ;

Route::get('btcdeposit','BackgroundCtrl@btcdeposit')->name('system.btcdeposit')  ;




 Route::get('notification','SystemCtrl@allNotification')->name('SystemCtrlnotification')  ;



 Route::get('account','SystemCtrl@index')->name('system.account')  ;


Route::get('widthdraw/{i}','SystemCtrl@getwidthdrawbyid')->name('system.widthdraw')  ;

Route::get('deposits/{i}','SystemCtrl@getdepositsbyid') ->name('system.deposits.i')  ;



Route::get('widthdraw','SystemCtrl@getwidthdraw') ->name('system.getwidthdraw')  ;
Route::post('widthdraw','SystemCtrl@postwidthdraw') ->name('system.postwidthdraw')  ;
Route::get('rate','SystemCtrl@rate')->name('system.rate')  ;
Route::get('deposits','SystemCtrl@getdeposits')->name('system.getdeposits')  ;
Route::post('deposits','SystemCtrl@postdeposits') ->name('system.postdeposits')  ;
Route::post('send_payment','SystemCtrl@send_payment') ->name('system.postsend_payment');

Route::post('add_plan','SystemCtrl@add_plan') ->name('system.postadd_plan');

Route::post('receive_payment','SystemCtrl@receive_payment') ->name('system.postreceive_payment');
Route::get('confirm_payment','SystemCtrl@confirm_payment')->name('system.SystemCtrlconfirm_payment')  ;

Route::get('payment/{id}', 'PaymentSystemCtrl@deposit');
 

 
Route::get('transactions','SystemCtrl@LedgetTransactions')->name('system.SystemCtrlLedgetTransactions')  ;


Route::get('transactions_deposits11','SystemCtrl@LedgetTransactionsdeposits')->name('system.SystemCtrlLedgetTransactions')  ;




Route::get('confirm_payment','SystemCtrl@confirm_payment')->name('system.SystemCtrlconfirm_payment')  ;

Route::get('transactions_widthdraws','SystemCtrl@LedgetTransactionswidthdraws')->name('system.SystemCtrlLedgetTransactions')  ;

Route::get('users','SystemCtrl@LedgetUsers')->name('system.SystemCtrlLedgetUsers')  ;

Route::get('kyc_users','SystemCtrl@KYCUsers')->name('system.SystemCtrlKYCUsers')  ;

Route::get('non_kyc_users','SystemCtrl@NonKYCUsers')->name('system.SystemCtrlNonKYCUsers')  ;


Route::get('delete_user_account_request','SystemCtrl@DeleteUsersAccountRequest')->name('system.SystemCtrlDeleteUsersAccountRequest')  ;

Route::get('membership','SystemCtrl@Ledgetmembership')->name('system.SystemCtrlLedgetmembership') ;

Route::get('coins','SystemCtrl@CoinsMaster')->name('system.SystemCtrlCoinsMaster')  ;



Route::get('Xpub','SystemCtrl@getBtcAddress')->name('system.SystemCtrlgetBtcAddress')  ;




    
    
    

 	});
  
    

 	});
  
         










Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Auth\AuthController@login')->name('login');
    
       
    
    
     Route::post('AuthToken', 'Auth\AuthController@AuthToken')->name('AuthToken');
     
     
    
     Route::post('RegisterAPI', 'Auth\AuthController@getRegisterAPI')->name('AuthToken');
     
     
     
     Route::post('googleauthtest', 'Auth\AuthController@googleauthtest')->name('googleauthtest');
     
     Route::post('sendemail', 'Auth\AuthController@sendemail')->name('sendemail');
         
     Route::post('googleauthverify', 'Auth\AuthController@googleauthverify')->name('googleauthverify');
     
              
     Route::post('emailotpverify', 'Auth\AuthController@emailotpverify')->name('emailotpverify');
     
     
     
    Route::post('register', 'Auth\AuthController@register');
    Route::post('register_otp', 'Auth\AuthController@register_otp');
    
     

 
 Route::middleware(['auth:api'])->group(function () {
     
 
 
 






   Route::get('wallet/all','SystemCtrl@allWallet')->name('SystemCtrl@NewWallet')  ;
 
        
         Route::post('book_order_auth/{type}/{c1}/{c2}', 'ExchangeCtrl@book_order_auth') ->name('exchange.postbook_order')  ;
        
//Route::get('balance', 'ExchangeCtrl@balance') ->name('exchange.ExchangeCtrlbalance')  ;
Route::get('balance', 'ExchangeCtrl@balance') ->name('exchange.ExchangeCtrlbalance')  ;

 //admin
 
 Route::get('admin/xpubs', 'AdminCtrl@getxpubs') ->name('AdminCtrlgetxpubs')  ;
 
 
  Route::post('admin/complete_widthdraw_coin_process', 'AdminCtrl@complete_widthdraw_coin_process')->name('complete_widthdraw_coin_process') ;

 
  Route::post('admin/complete_deposit_process', 'AdminCtrl@complete_deposit_process')->name('complete_deposit_process') ;

 
 Route::get('admin/orders', 'AdminCtrl@getorders') ->name('AdminCtrlgeorderstxpubs')  ;



  Route::post('admin/addxpub', 'AdminCtrl@add_xpub') ->name('AdminCtrladd_xpub')  ;

  Route::get('admin/deletexpub', 'AdminCtrl@deletexpub') ->name('AdminCtrldeletexpub')  ;

   Route::get('admin/deleteg2f','Auth\AuthController@deleteg2f') ->name('AdminCtrldeletexpubdeleteg2f')  ;
   Route::get('admin/changelogin','Auth\AuthController@changelogin') ->name('AdminCtrlchangelogin')  ;

 
               
  Route::post('admin/deleteuser', 'AdminCtrl@deleteuser') ->name('AdminCtrldeletexpub')  ;
  
  
  
  // admin 
  
        Route::get('logout', 'Auth\AuthController@logout');
        
        
        Route::get('user', 'Auth\AuthController@user');
        
         
        Route::get('me', 'Auth\AuthController@getme');
        
          	
Route::post('postlogo', 'SystemCtrl@postlogo')->name('SystemCtrlpostlogo');



          
         	Route::post('CreateTradeBuy','P2PTradeCtrl@createtradeBuy')->name('p2p.P2PTradeCtrlcreatetradeBuy');


 	Route::get('tradeinfo/{id}','P2PTradeCtrl@process_trade_order')->name('p2p.P2PTradeCtrlprocess_trade_order');
 	
 	 	Route::get('process_trade_buy/{id}','P2PTradeCtrl@process_trade_buy')->name('p2p.P2PTradeCtrlprocess_trade_buy');
 	 	
 	 	
 	Route::get('process_trade_sell/{id}','P2PTradeCtrl@process_trade_sell')->name('p2p.P2PTradeCtrlprocess_trade_sell');

	Route::get('mark_payment_as_paid/{id}','P2PTradeCtrl@mark_payment_as_paid')->name('p2p.P2PTradeCtrlmark_payment_as_paid');

       Route::post('changepassword', 'Auth\AuthController@changepassword');
        
	Route::post('release_coin','P2PTradeCtrl@release_coin')->name('p2p.P2PTradeCtrlrelease_coin');
	
Route::post('postkycnew', 'Auth\AuthController@postkycnew')->name('auth.AuthControllerpostkycnew');
    
	Route::get('all_trades','P2PTradeCtrl@all_trades')->name('p2p.P2PTradeCtrlall_trades');


   Route::post('postprofile', 'Auth\AuthController@postprofile');
        

   Route::post('postupdatemembership', 'Auth\AuthController@postupdatemembership');

 Route::get('changeStatus', 'Auth\AuthController@changeStatus');
 Route::get('accountStatus', 'Auth\AuthController@accountStatus');
  Route::get('deleteplan', 'Auth\AuthController@deleteplan');
  Route::get('notificationfundStatus', 'Auth\AuthController@notificationfundStatus');
  Route::get('deleteaccount', 'Auth\AuthController@deleteaccount');
   Route::get('get_client_ip', 'Auth\AuthController@get_client_ip');

Route::get('enableg2f', 'GoogleAuthCtrl@getenableg2f')->name('profile.enableg2f');
    
    Route::post('enableg2f', 'GoogleAuthCtrl@postenableg2f')->name('profile.enableg2f');
    
    
    Route::get('getQRCodeGoogleUrl', 'GoogleAuthCtrl@getQRCodeGoogleUrl')->name('profile.getQRCodeGoogleUrl');
    


Route::prefix('systemplan')->group( function() {
     
 Route::get('/investment/{i}', 'SystemplanCtrl@investment')->name('system.investment') ; 
 
   Route::get('investplanfetch','SystemplanCtrl@investplanfetch')->name('system.SystemplanCtrlinvestplanfetch')  ;
   
     Route::get('allbalance','SystemplanCtrl@allbalance')->name('system.SystemplanCtrlallbalance')  ;
     
        Route::get('investmentsreport','SystemplanCtrl@investmentsreport')->name('system.SystemplanCtrlinvestmentsreport')  ;
     
      Route::post('pixdepositaddress','SystemplanCtrl@pixdepositaddress')->name('system.SystemplanCtrlpixdepositaddress')  ;
     
       Route::get('getdepositreport','SystemplanCtrl@getdepositreport')->name('system.SystemplanCtrlgetdepositreport')  ;
     
    
});

Route::prefix('system')->group( function() {
     

    Route::post('investments','SystemCtrl@investments')->name('system.SystemCtrlinvestments')  ;
    
     Route::get('planfetch','SystemCtrl@planfetch')->name('system.SystemCtrlplanfetch')  ;
    
     Route::get('investment_report','SystemCtrl@investment_report')->name('system.SystemCtrlinvestment_report')  ;
    
    
     Route::post('depositapprove','SystemCtrl@depositapprove') ->name('SystemCtrldepositapprove')  ;
     
     
     Route::post('swapcoin','SystemCtrl@swapcoin') ->name('SystemCtrlswapcoin')  ;
    Route::post('searchaddress','SystemCtrl@searchaddress')->name('SystemCtrlsearchaddress')  ;
          Route::get('usersemails','SystemCtrl@LedgetUsersEmails')->name('users')  ;
    
   Route::get('alldatetransaction','SystemCtrl@alldatetransaction')->name('SystemCtrlalldatetransaction')  ;
     Route::get('datefetch','SystemCtrl@datefetch')->name('SystemCtrldatefetch')  ;
     Route::get('deleteuserotp','SystemCtrl@deleteuserotp') ->name('SystemCtrldeleteuserotp')  ;
    
         Route::get('alldepositaddress','SystemCtrl@alldepositaddress') ->name('SystemCtrlalldepositaddress')  ;
      Route::post('add_ticket','SystemCtrl@add_ticket') ->name('SystemCtrladd_ticket')  ;
     Route::get('ticket_list','SystemCtrl@ticket_list') ->name('SystemCtrlticket_list')  ;
     
      Route::get('ticket_by_id/{id}','SystemCtrl@ticket_by_id')->name('SystemCtrlticket_by_id')  ;
      Route::post('addticket_message','SystemCtrl@addticket_message') ->name('SystemCtrladdticket_message')  ;
      Route::get('getticket_message/{id}','SystemCtrl@getticket_message')->name('SystemCtrlgetticket_message')  ;
     
          Route::get('totallgetNewAdrressist','SystemCtrl@totallist') ->name('SystemCtrltotallist')  ;
     
       Route::get('currencylist','SystemCtrl@currencylist') ->name('SystemCtrlcurrencylist')  ;
     
        Route::post('send_payment_user', 'SystemCtrl@send_payment_user')->name('SystemCtrl@send_payment_user') ; 
 
             Route::post('exchangeform', 'SystemCtrl@exchangeform')->name('SystemCtrl@exchangeform') ; 
                  Route::post('pixdepositaddress', 'SystemCtrl@pixdepositaddress')->name('SystemCtrl@pixdepositaddress') ; 
          
        Route::get('getorderlistbycoin/{c1}/{c2}', 'SystemCtrl@getorderlistbycoin')->name('SystemCtrlgetorderlistbycoin') ; 
        
        Route::get('getuserinfo', 'SystemCtrl@getuserinfo')->name('SystemCtrlgetuserinfo') ; 
 
     
         Route::get('user_details','SystemCtrl@user_details')->name('SystemCtrluser_details')  ;
         
         
     Route::get('alldatetransaction','SystemCtrl@alldatetransaction')->name('SystemCtrlalldatetransaction')  ;
     Route::get('datefetch','SystemCtrl@datefetch')->name('SystemCtrldatefetch')  ;
     Route::get('users_notificationbyid','SystemCtrl@users_notificationbyid')->name('SystemCtrlusers_notificationbyid')  ;
      Route::get('users_loginhistorybyid','SystemCtrl@users_loginhistorybyid')->name('SystemCtrlusers_loginhistorybyid')  ;
       Route::get('users_transactionbyid','SystemCtrl@users_transactionbyid')->name('SystemCtrlusers_transactionbyid')  ;
        Route::get('users_withdrawbyid','SystemCtrl@users_withdrawbyid')->name('SystemCtrlusers_withdrawbyid')  ;
         Route::get('users_depositbyid','SystemCtrl@users_depositbyid')->name('SystemCtrlusers_depositbyid')  ;
    
Route::get('blocks','SystemCtrl@blocks')->name('SystemCtrlblocks')  ;

Route::get('SearchHash','SystemCtrl@SearchHash')->name('SystemCtrlSearchHash')  ;


Route::get('UnconfirmedTransactions','SystemCtrl@UnconfirmedTransactions')->name('SystemCtrlUnconfirmedTransactions')  ;



 Route::get('latest_blocks','SystemCtrl@latest_blocks')->name('SystemCtrllatest_blocks')  ;
 
 
 
  Route::get('transactions','SystemCtrl@transactions')->name('SystemCtrltransactions')  ;
   Route::post('NewWallet','SystemCtrl@NewWallet')->name('SystemCtrl@NewWallet')  ;

   Route::get('getNewAdrress','SystemCtrl@getNewAdrress')->name('SystemCtrl getNewAdrress');
   
   Route::get('Balance','SystemCtrl@Balance')->name('SystemCtrl@Balance')  ;
    Route::get('BalancebygivenID/{wallet_name}','SystemCtrl@BalancebygivenID')->name('SystemCtrl@BalancebygivenID')  ;

 
   Route::get('BalanceByAddress/{address}','SystemCtrl@BalancebygivenID')->name('SystemCtrl@Balance')  ;
   
   
  Route::post('sendFund','SystemCtrl@sendFund')->name('SystemCtrlsendFund')  ;
 

 
   Route::get('sendFund/{user_key}/{toAddress}/{amount}','SystemCtrl@transferbnexx')->name('SystemCtrltransferbnexx')  ;

Route::get('btcdeposit','BackgroundCtrl@btcdeposit')->name('system.btcdeposit')  ;




 Route::get('notification','SystemCtrl@allNotification')->name('SystemCtrlnotification')  ;



 Route::get('account','SystemCtrl@index')->name('system.account')  ;


Route::get('widthdraw/{i}','SystemCtrl@getwidthdrawbyid')->name('system.widthdraw')  ;

Route::get('deposits/{i}','SystemCtrl@getdepositsbyid') ->name('system.deposits.i')  ;


Route::get('widthdraw','SystemCtrl@getwidthdraw') ->name('system.getwidthdraw')  ;
Route::post('widthdraw','SystemCtrl@postwidthdraw') ->name('system.postwidthdraw')  ;
Route::get('rate','SystemCtrl@rate')->name('system.rate')  ;
Route::get('deposits','SystemCtrl@getdeposits')->name('system.getdeposits')  ;
Route::post('deposits','SystemCtrl@postdeposits') ->name('system.postdeposits')  ;
Route::post('send_payment','SystemCtrl@send_payment') ->name('system.postsend_payment');

Route::post('add_plan','SystemCtrl@add_plan') ->name('system.postadd_plan');

Route::post('receive_payment','SystemCtrl@receive_payment') ->name('system.postreceive_payment');
Route::get('confirm_payment','SystemCtrl@confirm_payment')->name('system.SystemCtrlconfirm_payment')  ;

Route::get('payment/{id}', 'PaymentSystemCtrl@deposit');
 
 
 
 
Route::get('transactions','SystemCtrl@LedgetTransactions')->name('system.SystemCtrlLedgetTransactions')  ;


Route::get('transactions_deposits','SystemCtrl@LedgetTransactionsdeposits')->name('system.SystemCtrlLedgetTransactions')  ;
Route::get('confirm_payment','SystemCtrl@confirm_payment')->name('system.SystemCtrlconfirm_payment')  ;

Route::get('transactions_widthdraws','SystemCtrl@LedgetTransactionswidthdraws')->name('system.SystemCtrlLedgetTransactions')  ;

Route::get('users','SystemCtrl@LedgetUsers')->name('system.SystemCtrlLedgetUsers')  ;

Route::get('kyc_users','SystemCtrl@KYCUsers')->name('system.SystemCtrlKYCUsers')  ;

Route::get('non_kyc_users','SystemCtrl@NonKYCUsers')->name('system.SystemCtrlNonKYCUsers')  ;


Route::get('delete_user_account_request','SystemCtrl@DeleteUsersAccountRequest')->name('system.SystemCtrlDeleteUsersAccountRequest')  ;

Route::get('membership','SystemCtrl@Ledgetmembership')->name('system.SystemCtrlLedgetmembership') ;

Route::get('coins','SystemCtrl@CoinsMaster')->name('system.SystemCtrlCoinsMaster')  ;



Route::get('Xpub','SystemCtrl@getBtcAddress')->name('system.SystemCtrlgetBtcAddress')  ;



 	});
  
        
    });

});

        

    
    
    
    
Route::get('lang/{locale}', 'FrontSystemCtrl@lang');
 
//Auth::routes(['verify' => true]);
  

    Route::get('/welcome', function () {
        return view('welcome');
    });

 Route::get('home', 'SystemCtrl@index')->name('auth.profile');
 
 Route::get('/', 'SystemCtrl@index')->name('auth.profile');
 
 
 
 Route::get('/nadmin', 'AuthAdminCtrl@customlogin')->name('AuthAdminCtrlcustomlogin');
 
 
 
 Route::post('/nadmin', 'AuthAdminCtrl@customadminauthenticate')->name('AuthAdminCtrlcustomadminauthenticate');
 
 


Auth::routes( );

 
Route::any('sendtologin', 'AuthController@systemlogin') ;
 

Route::any('sendtologin2', 'AuthController@systemlogin2') ;

 
  
 
    
    Route::get('/profile/enableg2f', 'GoogleAuthCtrl@getenableg2f')->name('profile.enableg2f');
    
    Route::post('/profile/enableg2f', 'GoogleAuthCtrl@postenableg2f')->name('profile.enableg2f');
    
    
    Route::get('/profile/getQRCodeGoogleUrl', 'GoogleAuthCtrl@getQRCodeGoogleUrl')->name('profile.getQRCodeGoogleUrl');
    
    
    
    Route::get('/profile', 'HomeController@profileIndex')->name('profile.index');
    
    
    
    
Route::get('auth/profile/submitforkyc', 'AuthController@submitforkyc')->name('auth.AuthControllersubmitforkyc');


    
Route::post('auth/kycnew', 'AuthController@postkycnew')->name('auth.AuthControllerpostkycnew');
    
Route::post('auth/profile', 'AuthController@profile')->name('auth.AuthControllerprofile');

Route::post('auth/profile', 'AuthController@postprofile')->name('auth.AuthControllerpostprofile');

Route::post('auth/changePassword','AuthController@postchangePassword')->name('auth.changePassword');





Route::get('/logout', 'FrontSystemCtrl@logout')->name('auth.FrontSystemCtrllogout');;




Route::get('system/bonusprocess','FrontSystemCtrl@ProcessBonus') ->name('auth.FrontSystemCtrlProcessBonus');;

        
Route::prefix('aff')->group( function() {


 
 
Route::get('packages','ReferCtrl@getPackages')->name('system.ReferCtrlgetPackages');

 
Route::post('investnow','ReferCtrl@investnow')->name('system.ReferCtrlinvestnow');




Route::get('refers','ReferCtrl@getrefer')->name('system.ReferCtrlgetrefer');
Route::get('up_earning','ReferCtrl@up_earning')->name('system.ReferCtrlup_earning');

Route::get('ref_earning','ReferCtrl@ref_earning')->name('system.ReferCtrlref_earning');

 
Route::get('testrefers','ReferCtrl@test_awardreferincome')->name('system.ReferCtrltest_awardreferincome');

 
Route::get('refers/{i}','ReferCtrl@getreferbylevelid')->name('system.ReferCtrlgetreferbylevelid');




Route::get('investment_order_history','ReferCtrl@investment_order_history')->name('system.ReferCtrlinvestment_order_history');

Route::get('investment_order_profit','ReferCtrl@investment_order_profit')->name('system.ReferCtrlinvestment_order_profit') ;



});
    
    
    
Route::prefix('background')->group(function() {


Route::get('dailybonus','BackgroundCtrl@dailybonus')->name('system.BackgroundCtrldailybonus') ;



});
    





    
 Route::get('/trade/{c1}/{c2}', 'TradeController@trade_theme_one')->name('system.radeControllertrade_theme_one')  ;
 
  Route::any('store','P2PTradeCtrl@posttradesubmit')->name('system.P2PTradeCtrlposttradesubmit')  ;
  
  
Route::prefix('p2p')->group(function() {

        
 Route::get('postTrade', 'P2PTradeCtrl@posttrade')->name('p2p.P2PTradeCtrlpostTrade')   ;
 
 	Route::get('getratec1/{id}/{c1value}','P2PTradeCtrl@getratec1')->name('p2p.P2PTradeCtrlgetratec1')   ;
 
 	Route::get('getratec2/{id}/{c1value}','P2PTradeCtrl@getratec2')->name('p2p.P2PTradeCtrlgetratec2') ;
 
 	Route::post('postTrade','P2PTradeCtrl@posttradesubmit')->name('p2p.P2PTradeCtrlposttradesubmit');
 	Route::post('trade','P2PTradeCtrl@trade')->name('p2p.P2PTradeCtrltrade');
 	
 	Route::get('store','P2PTradeCtrl@posttradesubmit')->name('p2p.P2PTradeCtrlposttradesubmit');
 
 
 
 	Route::get('buy_bitcoins','P2PTradeCtrl@buy_bitcoins')->name('p2p.P2PTradeCtrlbuy_bitcoins');
 
 
 	Route::get('sell_bitcoins','P2PTradeCtrl@sell_bitcoins')->name('p2p.P2PTradeCtrlsell_bitcoins');
 	
 	
 //	Route::get('CreateTrade','P2PTradeCtrl@createtradePost');
 
 	Route::post('CreateTradeBuy','P2PTradeCtrl@createtradeBuy')->name('p2p.P2PTradeCtrlcreatetradeBuy');
 	;
 	Route::get('CreateTradeSell','P2PTradeCtrl@createtradeSell')->name('p2p.P2PTradeCtrlcreatetradeSell');
 
 
 	Route::get('trade/{id}','P2PTradeCtrl@startTrade')->name('p2p.P2PTradeCtrlstartTrade219');
 
 	
 	Route::get('all_trade','P2PTradeCtrl@all_trades')->name('p2p.P2PTradeCtrlall_trades');
 	

 	
 	
 //	Route::get('process_trade/{id}','P2PTradeCtrl@process_trade');
 	
 	

 	
 	
 	
 	Route::post('process_trade_chat','P2PTradeCtrl@submit_process_trade_chats')->name('p2p.P2PTradeCtrlsubmit_process_trade_chats');
 	
 	 	Route::get('release_coin/{id}','P2PTradeCtrl@get_release_coin')->name('p2p.P2PTradeCtrlget_release_coin'); 
 	 	
 	 	
 	 	
 	 	Route::post('release_coin','P2PTradeCtrl@release_coin')->name('p2p.P2PTradeCtrlrelease_coin');
 	 	
 	 	Route::get('mark_payment_as_paid/{id}','P2PTradeCtrl@mark_payment_as_paid')->name('p2p.P2PTradeCtrlmark_payment_as_paid');
 	 	
 	 	Route::post('dispute_trade','P2PTradeCtrl@dispute_trade')->name('p2p.P2PTradeCtrldispute_trade');
 	
 	  	Route::post('leave_feedback','P2PTradeCtrl@leave_feedback')->name('p2p.P2PTradeCtrlleave_feedback');
 	
 	 
        
 Route::post('socketchat', 'P2PTradeCtrl@submitsocketchat')->name('p2p.P2PTradeCtrlsubmitsocketchat');
 Route::get('socketchat/{id}', 'P2PTradeCtrl@socketchat')->name('p2p.P2PTradeCtrlsocketchat');
 
  Route::get('socketchat1/{id}', 'P2PTradeCtrl@socketchat1')->name('p2p.P2PTradeCtrlsocketchat1');
  
    Route::get('paid/{id}', 'P2PTradeCtrl@paid')->name('p2p.P2PTradeCtrlspaid');

 	});
 
 
 
  
Route::prefix('exchange')->group( function() {

        
 Route::get('/Ticker', 'TickerController@GetTickerCoins')->name('user.TickerControllerGetTickerCoins') ;
 
    
     
   
 Route::get('/sending', 'RedirectManagementController@ipredirect')->name('user.RedirectManagementControlleripredirect') ;
   
     
      
 Route::get('/UserBalance', 'TradeController@getUserBalance')->name('user.TradeControllergetUserBalance') ;
 
 
 Route::get('/Withdraw_Fund_History', 'ExchangeCtrl@Withdraw_Fund_History')->name('user.ExchangeCtrlWithdraw_Fund_History') ; 
 
 
 
 Route::get('/Withdraw_Fund', 'ExchangeCtrl@Withdraw_Fund')->name('user.ExchangeCtrlWithdraw_Fund') ; 
 
 
 Route::post('/Withdraw_Fund', 'ExchangeCtrl@SubmitWithdraw_Fund')->name('user.ExchangeCtrlSubmitWithdraw_Fund') ; 
 
 
  Route::get('/deposit/{deposit_id}', 'ExchangeCtrl@getdepositdetails')->name('user.ExchangeCtrlgetdepositdetails') ; 
  
  
 Route::get('/deposit', 'ExchangeCtrl@getBtcAddress')->name('user.ExchangeCtrlgetBtcAddress') ; 
  
  Route::post('/deposit', 'ExchangeCtrl@getDepositSubmit')->name('user.getDepositSubmitgetDepositSubmit') ; 
  
 
 
   Route::any('/deposit_history', 'ExchangeCtrl@getDepositList')->name('user.ExchangeCtrlgetDepositList') ; 
  
 
 
 Route::get('/deposit/process', 'ExchangeCtrl@getBtcProcess')->name('user.ExchangeCtrlgetBtcProcess') ; 
  
  
  
  
 Route::get('/transactions', 'ExchangeCtrl@LedgetTransactions')->name('user.ExchangeCtrlLedgetTransactions') ; 
  
  
    
    
 Route::get('/tradeMarketBuy', 'TradeController@FinishTradeBuyOrderTest')->name('user.TradeControllerFinishTradeBuyOrderTest') ;
 
  Route::get('/tradeMarketSell', 'TradeController@FinishTradeSellOrderTest')->name('user.TradeControllerFinishTradeSellOrderTest') ;
 
 
    
     
 
 

Route::get('/newsfeed', 'TradeController@newsfeed')->name('user.TradeControllernewsfeed') ;


 Route::get('/ticker/{c1}/{c2}', 'TradeController@GetTicker')->name('user.TradeControllerGetTicker') ; 
 
 
 Route::get('/tickerValueBuy/{c1}/{c2}/{total}', 'TradeController@GetTValueBuy')->name('user.TradeControllerGetTValueBuy') ; 
 
  Route::get('/tickerValueSell/{c1}/{c2}/{total}', 'TradeController@GetTValueSell')->name('user.manage') ; 
 
 
  
 Route::get('/tickerValueBuyR/{c1}/{c2}/{total}', 'TradeController@GetTValueBuyR')->name('user.TradeControllerGetTValueBuyR') ; 
 
  Route::get('/tickerValueSellR/{c1}/{c2}/{total}', 'TradeController@GetTValueSellR')->name('user.TradeControllerGetTValueSellR') ; 
 
 
 
 Route::post('/tradeorder', 'TradeController@CreateTradeOrder')->name('user.TradeControllerCreateTradeOrder') ; 
  
  
  
 Route::get('/trades/{type}/{c2}', function ($type,$c2) { 
      
      if($type=="buy")
      {
    return  response(DB::table('exchange_bookings')->where('pair',"BTC".$c2)->where('type',$type)->where('status',"Pending")->orderBy('rate', 'desc')->limit(5)->get()->toJson())->header('Content-Type', 'application/json');  
    
      }
      
      else
      {
          
          return  response(DB::table('exchange_bookings')->where('pair',"BTC".$c2)->where('type',$type)->where('status',"Pending")->orderBy('rate', 'asc')->limit(5)->get()->toJson())->header('Content-Type', 'application/json');  
    
      }
});



 Route::get('/owntrades', function () { 
      
      
    return  response(DB::table('exchange_bookings')->where('user_id', Auth::user()->id)->where('status',"Pending")->orderBy('id', 'desc')->limit(5)->get()->toJson())->header('Content-Type', 'application/json');  ;
});





Route::get('/ticker', 'TickerController@SelfDBTicker')->name('user.TickerControllerSelfDBTicker') ; 
  
  
  
  
Route::get('/ticker/buy', function () { 
      
      
    return  response(DB::table('exchange_rate_buy')->select("pair","rate")->where('pair',"BTCUSD")->orderBy('timest', 'desc')->limit(1)->get()->toJson())->header('Content-Type', 'application/json');  ;
});



Route::get('/ticker/sale', function () { 
      
      
    return  response(DB::table('exchange_rate_sale')->select("pair","rate")->where('pair',"BTCUSD")->orderBy('timest', 'desc')->limit(1)->get()->toJson())->header('Content-Type', 'application/json');  ;
});


 
  Route::get('/widthdraw_crypto', 'Trade_TransactionController@withdraw')->name('user.Trade_TransactionControllerwithdraw');
  
  
  	});





Route::prefix('exchange')->group( function() {
    
Route::post('book_order/{type}/{c1}/{c2}', 'ExchangeCtrl@book_order') ->name('exchange.postbook_order')  ;
 
Route::any('book_orderlist/{c1}/{c2}', 'ExchangeCtrl@book_orderlist') ->name('exchange.ExchangeCtrl@book_orderlist')  ;



Route::any('orderbookingsbyuser/{status}/{c1}/{c2}', 'ExchangeCtrl@orderbookingsbyuser') ->name('exchange.ExchangeCtrlorderbookingsbyuser')  ;


      
Route::any('allorderhistory/{c1}/{c2}', 'ExchangeCtrl@allorderhistory') ->name('exchange.ExchangeCtrl@completebookingallusers')  ;
            
 
             
Route::any('orderbookings/{type}/{c1}/{c2}', 'ExchangeCtrl@orderbookings') ->name('exchange.ExchangeCtrlorderbookings')  ;



Route::any('tickers', 'ExchangeCtrl@tickers') ->name('exchange.ExchangeCtrltickers')  ;


Route::any('rate/{c1}/{c2}', 'ExchangeCtrl@rates') ->name('exchange.ExchangeCtrltickers')  ;

    });
    
    
    
Route::prefix('admin')->group(function() {




 Route::middleware(['auth:api'])->group(function () {
     

   Route::post('setpassword','Auth\AuthController@setpassword')->name('SystemCsetpassword')  ;
 
 
 
 
    
    Route::post('/getuserinfo', 'AdminController@getuserinfo')->name('AdminCogetuserinfontrolleralldeposits');
    
    
    
    
 });
    
 
 
   		Route::get('/login',

   		'Auth\AdminLoginController@showLoginForm')->name('admin.AdminLoginControllershowLoginForm');

   		Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.AdminLoginController.login');

   		Route::get('logout/', 'Auth\AdminLoginController@logout')->name('admin.AdminLoginControllerlogout');
 //Route::get('/', 'AdminController@allwidthdraw')->name('admin.dashboard');
    		
    // Route::post('/', 'AdminController@allwidthdraw')->name('admin.dashboard');
    		
    		
    			
  
   // Route::get('/settings', 'AdminController@systemsettings')->name('admin.AdminControllersystemsettings') ;
	
	
    Route::get('/', 'AdminController@dashboard')->name('admin.dashboard') ;
	
	
	
    
    
    
  
Route::get('/autologin/{id}', 'AdminController@autoLoginbyID')->name('AdminControllerautologin');

 

    Route::get('/kycs/no', 'AdminController@getnokycall')->name('AdminControllerkycs');
    
    
    Route::get('/kycs/approved', 'AdminController@getapprovedkycall')->name('AdminControllergetapprovedkycall');
    
    
    
    Route::get('/kycs/pending', 'AdminController@getpendingkycall')->name('AdminControllergetpendingkycall');
    
    
  
    Route::get('/kycs', 'AdminController@getkycsall')->name('AdminControllergetkycsall');
    
    
  
    Route::get('/submitforkyc/{id}', 'AdminController@submitforkyc')->name('AdminControllersubmitforkyc');
    
  
    
    Route::get('/g2f', 'AdminController@getg2fall')->name('AdminControllergetg2fall');
    
       
    Route::get('/g2f/delete/{id}', 'AdminController@g2fdelete') ->name('AdminControllerg2fdelete');
    
    
    Route::get('/deposits', 'AdminController@alldeposits')->name('AdminControlleralldeposits');
    
    
    
    Route::get('/deposits/{id}', 'AdminController@depositsdetails')->name('AdminControllerdepositsdetails');
    
  //  Route::get('deposits_details', 'SystemCtrl@alldeposits')->name('deposits');
    
    

    
    
    Route::get('/widthdraw', 'AdminController@allwidthdraw')->name('AdminControllerallwidthdraw');
    
  Route::get('/widthdraw/{id}', 'AdminController@widthdrawdetails'  )->name('AdminControllerallwidthdraw');
    
  Route::post('/updatewidthdraw/{id}', 'AdminController@updatewidthdraw'  )->name('AdminControllerallwidthdraw');
    
    Route::get('/transactions', 'AdminController@alltransactions')->name('transactions');
    
      Route::get('/transactions/{id}', 'AdminController@transactionsdetails'  )->name('AdminControllerallwidthdraw');
      
    
    Route::get('/currencies', 'AdminCurrencies@allcurrencies')->name('currencies');
      
      
         
    Route::post('/addcurrency', 'AdminCurrencies@addcurrency')->name('addcurrency');
      
      
    Route::post('/disablecurrency', 'AdminCurrencies@disablecurrency')->name('disablecurrency');
      
      
    Route::post('/deletecurrency', 'AdminCurrencies@deletecurrency')->name('deletecurrency');
      
      
      
    
    Route::get('/getallcurrencies', 'AdminCurrencies@getallcurrencies')->name('AdminCurrenciesgetallcurrencies');
      
     
   
 //  Route::get('/users', 'AdminController@index');
    
   Route::get('/users', 'AdminController@index')->name('backend.users');
    
    
    Route::get('/users/add', 'AdminController@add')->name('backend.users.add');
    Route::post('/users/add', 'AdminController@addPost')->name('backend.users.add.post');
    
    Route::get('/users/edit/{id}', 'AdminController@edit')->name('backend.users.edit');
    
    
    Route::post('/users/profile/{id}', 'AdminController@editprofile')->name('backend.users.edit');
    Route::get('/users/profile/{id}', 'AdminController@profile')->name('backend.users.edit');
    
    Route::post('/users/edit/{id}', 'AdminController@editPost')->name('backend.users.edit.post');
    
    
    Route::get('/users/delete/{id}', 'AdminController@delete')->name('AdminControllerdelete');
    
    
       Route::get('/settings', 'AdminSettingsController@settings')->name('AdminSettingsControllersettings');
    
    
    
    
    Route::post('/netellersettings', 'AdminSettingsController@postnetellersettings')->name('AdminSettingsControllerpostsettings') ;
    
    
    Route::post('/paypalsettings', 'AdminSettingsController@postpaypalsettings')->name('AdminSettingsControllerpaypalpostsettings') ;
	
     
     
	
    

  	});


 

