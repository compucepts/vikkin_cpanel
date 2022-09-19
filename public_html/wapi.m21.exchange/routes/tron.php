<?php
 
 
  Route::get('/tron/process/{address}','TronCtrl@ProcessUSDTDeposit')   ;
 
  Route::get('/tron/balances','TronCtrl@USDTbalances')   ;
 
 

   