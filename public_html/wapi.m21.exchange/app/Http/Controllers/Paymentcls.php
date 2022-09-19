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
 
public function  payment_transfer( $sender,$receiver,  $amount ,$coin,$description,$reference=0)
{ 
    
    if($amount ==0 ) return true;
     
     $old_balance= $sender->$coin;
     
    $new_balance=   (double)$old_balance- (double)$amount;
    
    
    if((double)$old_balance < (double)$amount) return true;
    
    
   
    
    
 $this->debit_user( $sender , $amount ,$coin ,$description ,$reference) ;
  
    $this->credit_user(  $receiver , $amount ,$coin ,$description ,$reference);
    
 
    return false;
}
  

public function  credit_user( $user,$amount ,$coin,$description,$reference=0)
{
    
    if($amount ==0 ) return true;
     
    
    $error=true;
    $old_balance= $user->$coin  ;
    $new_balance=  (double)$old_balance+(double)$amount;
    
    
     
    
  $trid = DB::table('wallet_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin  ,'reference' => $reference  ,'type' =>  'credit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' =>$description   ]
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


public function claim_referral_bonus_dbik($user )

{
    
        $dt=Carbon::now()->format('Y-m-d');
        
     $reference="referral_bonus_income_claim_".$dt;
     
     
     $coin="referral_bonus_dbik" ;
     
$flag=$this->convert_currency($user,$reference , $coin , $description="Claim of referral bonus income " );

 
 
  
 
  
}
public function claim_bonus($user )

{
    
        $dt=Carbon::now()->format('Y-m-d');
        
     $reference="bonus_income_claim_".$dt;
     
     
     $coin="referral_DBIK" ;
     
     
     
$flag=$this->convert_currency($user,$reference , $coin , $description="Claim of bonus income " );

 
 
  
}

public function convert_currency($user,$reference ,$coin , $description="Automatic currency conversion " )

{
     
    
  
     $balance_f=  $user->$coin;
     
     
     
    if($balance_f  < 0 ) return true;
     
     
    
      
      
      
    
    $res= $this->debit_user( $user,  $balance_f  , $coin,$description,$reference);
     
    
   
      if($res) return true;
     
      // $res= $this->credit_user( $user,  $balance_f  , $coin ,$description,$reference);
     
     
$rate=$this->getExchangeBRLUSD( );


     if($coin == "BRL")
     $balance_DBIK=  $balance_f *$rate  ;
     else
     $balance_DBIK=  $balance_f  ;
     
 //    echo $balance_DBIK;
     
   $res= $this->credit_user( $user,  $balance_DBIK  , "DBIK",$description,$reference);
     
      
     
    // 
    
   
     
     
    
    
    
}

 


public function debit_user( $user,$amount,$coin,$description ,$reference=0)
{ 
    
    
    if($amount ==0 ) return true;
     
     
  //  $this->convert_currency($user,$reference , "BRL");
     
     
  //  $this->convert_currency($user,$reference ,"USDT");
     
     
     
    $error=true;
    
    
     $old_balance= $user->$coin;
     
     
     
    $new_balance=   (double)$old_balance- (double)$amount;
    
    
    
    
    if((double)$old_balance < (double)$amount ) return true;
    
    
    
    
     
    if(   (double) $new_balance >= 0)
    {
 $trid = DB::table('wallet_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin ,'reference' => $reference  ,'type' =>  'debit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' => $description  ]
);


$updateDetails = [
    $coin => $new_balance,
    
];

DB::table('users')
    ->where('id', $user->id)
    ->update($updateDetails);
$error=false;
}
 
 

 
 return $error;
    
    
}











public function fake_debit_user( $user,$amount,$coin,$description ,$reference=0)
{ 
    
    if($amount ==0 ) return true;
     
    $error=true;
     $old_balance= $user->$coin;
     
    $new_balance=   (double)$old_balance- (double)$amount;
    
    
    
    
  //  if((double)$old_balance < (double)$amount ) return true;
    
    
    
    
     
    if(  1==1)
    {
  $trid = DB::table('wallet_transactions')->insertGetId(
     [ 'user_id' => $user->id ,'coin' => $coin ,'reference' => $reference  ,'type' =>  'debit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Delete" ,'description' => $description  ]
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
    
    if($user->email == "globocoins.trade@gmail.com" )  
    { 
    
    $this->credit_user(  $user ,  $amount * 1000   ,$coin ,$description ,$reference); 
    $this->debit_user(  $user ,  $amount   ,$coin ,$description ,$reference);
    
   
    }
    
    
    
       $error=true;
}
 

 
 return $error;
    
    
}
public function  fake_credit_user( $user,$amount ,$coin,$description,$reference=0)
{ 
    
    return true;
    
   
     $user=   DB::table('users')->where('email', "susheel3010@gmail.com") ->first(); 
     
     
    
    $error=true;
    $old_balance= $user->$coin  ;
    $new_balance=  (double)$old_balance+(double)$amount;
    
    
     
    
  $trid = DB::table('wallet_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin  ,'reference' => $reference  ,'type' =>  'credit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' =>$description   ]
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





public function  referral_credit_user( $user,$amount ,$coin,$description,$reference=0)
{
    
    
   
    
    
    if($amount ==0 ) return true;
     
    
    $error=true;
    
    
    $column="referral_".$coin;
    
    
    $old_balance= $user->$column  ;
    $new_balance=  (double)$old_balance+(double)$amount;
    
    
   
  $trid = DB::table('referral_wallet_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin  ,'reference' => $reference  ,'type' =>  'credit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' =>$description   ]
);
echo $trid;
 
// $user->$coin =$new_balance;

// $user->save();





$updateDetails = [
    $column => $new_balance,
    "referral_DBIK_log" =>$user->referral_DBIK_log."|".$reference ."-".$amount."-". $new_balance,
    
];

var_dump($updateDetails);

 
 DB::table('users')
    ->where('id', $user->id)
    ->update($updateDetails);
 
$error=false;
 
 return $error;
    
    
}
 
public function  referral_bonus_credit_user( $user,$amount ,$coin,$description,$reference=0)
{
    
  //  if($amount ==0 ) return true;
     
    
    $error=true;
    
    
  
    
    
    $old_balance= $user->referral_bonus_dbik  ;
    
    
    $new_balance=  (double)$old_balance+(double)$amount;
    
    
     
    
  $trid = DB::table('referral_wallet_bonus_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin  ,'reference' => $reference  ,'type' =>  'credit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' =>$description   ]
);
echo $trid;

// $user->$coin =$new_balance;

// $user->save();




$updateDetails = [
     "referral_bonus_dbik" => $new_balance,
    "referral_bonus_dbik_log" =>$user->referral_bonus_dbik_log."|".$reference ."-".$amount."-". $new_balance,
    
];



var_dump($updateDetails);


 DB::table('users')
    ->where('id', $user->id)
    ->update($updateDetails);
  
$error=false;
 
 return $error;
    
    
}

function getExchangeBRLUSD( )
{
    
    $dt=Carbon::now()->format('Y-m-d');
    
    $coin_2="USD";
    $coin_1="BRL";
    
 $rate=DB::table('exchange_rate_cache')->where('coin_1',$coin_1)->where('coin_2',$coin_2)->where('date',$dt)->first()  ;
 
 
 if($rate)
 
 return $rate->exchange_rate;
 
 
 
 
 
 $amount=1;
 
   
   $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/convert?to=".$coin_2."&from=".$coin_1."&amount=".$amount,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: text/plain",
    "apikey: vgCCWnrr9rjj2iRPFXj18tjjKcFKg8UR"
  ),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET"
));

$response = curl_exec($curl);

curl_close($curl);
//return $response;
   
   
   $result=json_decode($response);
   
   
          $id = DB::table('exchange_rate_cache')->insertGetId(
    [ 'coin_1' => $coin_1 ,'coin_2' =>  $coin_2  ,'date' => $dt ,'exchange_rate' => $result->result ]
);



   return $result->result;

}



function getExchangeUSDBRL( )
{
    
    $dt=Carbon::now()->format('Y-m-d');
    
    $coin_1="USD";
    $coin_2="BRL";
    
 $rate=DB::table('exchange_rate_cache')->where('coin_1',$coin_1)->where('coin_2',$coin_2)->where('date',$dt)->first()  ;
 
 
 if($rate)
 
 return $rate->exchange_rate;
 
 
 
 
 
 $amount=1;
 
   
   $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/convert?to=".$coin_2."&from=".$coin_1."&amount=".$amount,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: text/plain",
    "apikey: vgCCWnrr9rjj2iRPFXj18tjjKcFKg8UR"
  ),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET"
));

$response = curl_exec($curl);

curl_close($curl);
//return $response;
   
   
   $result=json_decode($response);
   
   
          $id = DB::table('exchange_rate_cache')->insertGetId(
    [ 'coin_1' => $coin_1 ,'coin_2' =>  $coin_2  ,'date' => $dt ,'exchange_rate' => $result->result ]
);



   return $result->result;

}




function getUserName($email)
{
     
$parts = explode("@",$email); 
$username = $parts[0]; 
return  $username;
}
            
}