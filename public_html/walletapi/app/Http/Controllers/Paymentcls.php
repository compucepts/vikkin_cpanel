<?php


namespace App\Http\Controllers;

 
use App\Page;
use App;
use View;
use MetaTag; 

use App\ChargeCommision;
use App\Income;
use App\MemberExtra;
use App\Deposit;
use App\Gateway;
use App\Lib\GoogleAuthenticator;
use App\Transaction;
use App\User;
use Config;
use App\Coins;

use App\Exchange_deposit;

use App\System_Settings;

use App\Exchange_widthdraw;
use App\Exchange_bookings;
use Illuminate\Support\Facades\DB;

 
use App\Http\Controllers\Controller;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

 
class Paymentcls extends Controller
{ 

    public function __construct( )
    {
         
    
            
            
    }
 
  

public function  credit_user( $user,$amount ,$coin,$description)
{ 
    
    
    $error=true;
    $old_balance= $user->$coin  ;
    $new_balance=  (double)$old_balance+(double)$amount;
    
    
     
    
  $trid = DB::table('system_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin  ,'type' =>  'credit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' =>$description   ]
);


// $user->$coin =$new_balance;

// $user->save();

$updateDetails = [
    $coin => $new_balance,
    
];

DB::table('users')
    ->where('id', $user->id)
    ->update($updateDetails);
  
$error=false;
 
 return $error;
    
    
}


public function debit_user( $user,$amount,$coin,$description )
{ 
    
    $error=true;
     $old_balance= $user->$coin;
     
    $new_balance=   (double)$old_balance- (double)$amount;
    
     
    if(   (double) $new_balance > 0)
    {
 $trid = DB::table('system_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin  ,'type' =>  'debit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' => $description  ]
);


$updateDetails = [
    $coin => $new_balance,
    
];

DB::table('users')
    ->where('id', $user->id)
    ->update($updateDetails);
$error=false;
}
else
{
       $error=true;
}
 

 
 return $error;
    
    
}
            
}