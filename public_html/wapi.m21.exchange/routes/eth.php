<?php
  
 
 Route::middleware(['auth:api'])->group(function () {
     
     
  Route::get('eth/EthereumDeposit','EthereumCtrl@EthereumDeposit')   ;
     
  Route::post('eth/EthereumDeposit','EthereumCtrl@EthereumDeposit')   ;
 
 
});