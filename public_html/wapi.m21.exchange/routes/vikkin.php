<?php
 
 Route::middleware(['auth:api'])->group(function () {


    Route::prefix('auth')->group( function() {

    Route::prefix('robot')->group( function() {


   
   
    Route::prefix('investment')->group( function() {


Route::get('investmentinfobyid/{id}/','Robot\RobotInvestmentCtrl@investmentinfobyid')->name('RobotInvestment ils')  ;
 
    Route::get('/updateStatus/{i}/{status}', 'Robot\RobotInvestmentCtrl@updateInvestment')->name('robot.investment') ; 
  
    
    
        Route::get('/robot_income_daily/{i}', 'Robot\RobotInvestmentCtrl@robot_income_daily')->name('rrobot_income_dailystment') ; 
  
        Route::get('/ActivateInvestment/{i}', 'Robot\RobotInvestmentCtrl@ActivateInvestment')->name('robot.investment') ; 
  
    
        Route::get('/buy_this_robot/{i}', 'Robot\RobotInvestmentCtrl@buy_this_robot')->name('robot.investment') ; 
  
    
    
        Route::get('/buy_sell_robots', 'Robot\RobotInvestmentCtrl@buy_sell_robots')->name('robot.investment') ; 
  
    
        Route::post('/donate_robot', 'Robot\RobotInvestmentCtrl@donate_robot')->name('robot.investment') ; 
  
    
        Route::post('/sell_robot', 'Robot\RobotInvestmentCtrl@sell_robot')->name('robot.investment') ; 
  
    
    
    
    
    });
    
    });
    
    });
    });
    