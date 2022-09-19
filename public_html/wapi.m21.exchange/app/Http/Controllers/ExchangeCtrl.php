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

use Exception;
use App\Notifications\Login;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExchangeCtrl extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        
   
   
            
            
    }
 
 
 
 
  public function swapcoinprocessExchangeRate ( )
{ 
    
           $coin1= $this->request['coin1'];
           
           $coin2= $this->request['coin2'];
            
            
             $r=1;
             
             
            if($coin1=='Casinokoin'  && $coin2=='BRL'  )
            {
                
                $r=1;
            }
           else
            
            if($coin2=='Casinokoin'  && $coin1=='BRL'  )
            {
                
                $r=1;
            }
           
           
           
           
    
               
       else if($coin1=='Casinokoin'  && $coin2=='BTC'  )
            {
                
                
        $f=file_get_contents("https://api.bitfinex.com/v1/pubticker/btcusd");
          $s=json_decode($f);
         
          $r =$s->bid;
         
         
          
            }
            
            
 /*
 if()
 
  $this->request['coin1'],
  
  
   $this->request['coin1'],
   
        $ss  =System_Settings::select('exchange_rate','coin_name1','coin_name2','coin_name3','coin2_deposit_address')->first();
        
        
    
    $ar=  array();
    
    $ar[$ss->coin_name1 ]=$ss->exchange_rate;
    
      $ar[$ss->coin_name2 ]=$ss->exchange_rate;
    
    
    
    
        $f=file_get_contents("https://api.bitfinex.com/v1/pubticker/btcusd");
          $s=json_decode($f);
         
          $rt=$s->bid;
         
        
           
   
       $ar[$ss->coin_name3]=  $rt;
    
     */
      $ar=  array();
      
   
       $ar[ 'rate']=   $r;
    
       $ar[ 'coin1']= $this->request['coin1'];
    
      
   return response()->json($ar);
  
   
    }
 
 
 
 
 
  public function erate_v2 ( )
{ 
     
 $data=DB::table('erate_v4')->orderBy('time', 'ASC')->get()  ;
      
  return response()->json($data );
    
}
 
 
 
 
 
 public function process_limit_trade()
 {
     
     $buy = DB::table('exchange_bookings')->where('status', 'pending')->where('type', 'buy')->orderByRaw('rate DESC')->get();


foreach($buy as $b)
     {
         
      print_r($b);
         
      
  $this->process_limit_trade_finish($b );
 



        
     }
 }
 
 
 
 public function process_limit_trade_finish($buy)
 {
   /*
   1 find partner
   2 update both partners order
   3 debit one account 
   4 credit other account
   5 update trade rate for last  buy /sell
   6 take processing charges as per membership  plan
   */
   
   // step 1
   
     
     
   
 

$sell = DB::table('exchange_bookings')->where('status', 'pending')->where('type', 'sell')->where('rate', $buy->rate)->where('c1_value', $buy->c1_value)->first();



if (!$sell) {
           return "dddsdfdsfds";
        }
        
        echo __LINE__;
        

print_r($sell->id);

// step 2
echo __LINE__;
            
           
DB::table('exchange_bookings')
      ->where('id', $sell->id)
       ->update(['status' => 'completed']);
       echo __LINE__;
       
DB::table('exchange_bookings')
            ->where('id', $buy->id)
            ->update(['status' => 'completed']);
            
// update trade rate
               
// step 3

// debit usd   and credit btc


// user already being debited when order was booked so this step remvoed
      //$trid = DB::table('system_transactions')->insertGetId(
   // [ 'order_type' => "exchange_trade" ,  'user_id' => $buy->user_id ,'coin' => $buy->c2  ,'cr' => 0 ,'dr' =>$buy->c2_value ,'status' =>"Success" ,'description' =>"Widthdraw Request ". $buy->id  ]
//);


//      $trid = DB::table('system_transactions')->insertGetId(
  //  [ 'order_type' => "exchange_trade" ,'user_id' => $buy->user_id    ,'coin' => $buy->c1  ,'dr' => 0 ,'cr' =>$buy->c1_value ,'status' =>"Success" ,'description' =>"trade processed   ". $buy->id  ]
//);

echo __LINE__;

$pl=new Paymentcls();
$buy_user = User::findOrFail($buy->user_id );


 $pl->credit_user(  $buy_user, $buy->c1_value ,$buy->c1,'Process Exchange order');

   //$m = Membership::find( $buy_user->membership) ;
   $charge="0.001";//(double)$buy->c2* $m->row1 /  100;
   
   
      
 $pl->debit_user( $buy_user,  $charge ,$buy->c2,'Charges Exchange order');
 

// step 4

// debit btc   and credit usd


echo __LINE__;
      
$sell_user = User::findOrFail($sell->user_id );


 $pl->credit_user(  $sell_user, $sell->c2_value ,$sell->c2, 'Process Exchange order');


 
  // $m = Membership::find( $sell_user->membership) ;
   $charge="0.001" ;//  "//(double)$sell->c2* $m->row1 /  100;
   
   
      
 $pl->debit_user( $sell_user,  $charge ,$sell->c2,'Charges Exchange order');
 

echo __LINE__;

// step 5

// update trade rate of last trade 

$trid = DB::table('exchange_rate_buy')->insertGetId(
    [ 'pair' =>   $buy->c1. $buy->c2 ,  'rate' => $buy->rate , 'c1' => $buy->c1 ,'c2' => $buy->c2     ]
);

$trid = DB::table('exchange_rate_sale')->insertGetId(
    [ 'pair' =>   $buy->c1. $buy->c2 ,  'rate' => $sell->rate , 'c1' => $sell->c1 ,'c2' => $sell->c2     ]
);

echo __LINE__;
 }
 

// Order Booking

public function book_order_auth2dummy($type,$c1,$c2)
{
    
  //   $rt=file_get_contents("https://api-pub.bitfinex.com/v2/ticker/tBTCUSD");

// /$//r=json_decode( $rt) ;

 
//$rate=$r[0];
//
$user = Auth::user();
       
       $user_id = $user->id;
       
       echo $user_id;
      
   $eb=new Exchange_bookings();
   $eb->user_id = $user_id;
  $eb->order_type=$this->request->order_type;
  
  $eb->pair=$c1.$c2;
  $eb->c1=$c1;
  $eb->c2=$c2;
   $eb->c1_value=$this->request->c1_value;
  $eb->c2_value=$this->request->c2_value;
  
  $rate= $eb->c2_value /  $eb->c1_value;
  
  
  $eb->quantity=1;
  $eb->amount=1;  // dyanamic calculation
  $eb->rate=$rate;   // dyanamic calculation
  
  
  $eb->status="Pending";
  $eb->ip="localhost"; // dyanamic calculation
  $eb->type=$type;
  $eb->description="description pending";
    $eb->save();
 $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Order booked successfully";
    
    return response()->json($ar);

}


public function book_order_auth($type,$c1,$c2)
{
    
  //   $rt=file_get_contents("https://api-pub.bitfinex.com/v2/ticker/tBTCUSD");

// /$//r=json_decode( $rt) ;

 
//$rate=$r[0];
//

// debit user 

 $ar=array();
$user=Auth::user();
  $user_id= $user->id;
  

$pl=new Paymentcls();
//$pl->credit_user( $user, "1000",$c1);

if(!$pl->debit_user( $user,$this->request->c1_value,$c1,'Process Exchange order'))
{



      
   $eb=new Exchange_bookings();
   $eb->user_id = $user_id;
  $eb->order_type=$this->request->order_type;
  
  $eb->pair=$c1.$c2;
  $eb->c1=$c1;
  $eb->c2=$c2;
   $eb->c1_value=$this->request->c1_value;
  $eb->c2_value=$this->request->c2_value;
  
  $rate= $eb->c2_value /  $eb->c1_value;
  
  
  $eb->quantity=1;
  $eb->amount=1;  // dyanamic calculation
  $eb->rate=$rate;   // dyanamic calculation
  
  
  $eb->status="Pending";
  $eb->ip="localhost"; // dyanamic calculation
  $eb->type=$type;
  $eb->description="description pending";
    $eb->save();
 $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Order booked successfully";
    
   
    
    
}
else
{
    
   
     $ar['Error']=true; 
    
    $ar['Message']=  "Order booking failure";
    
    
   
} return response()->json($ar);

}



// all users order list
  public function book_orderlist($c1,$c2)
{ 
    
    //$user_id= Auth::user()->id;
 $data=DB::table('exchange_bookings')->where('c1',$c1)->where('c2',$c2)->orderBy('id', 'desc')->get()  ;
      
  return response()->json($data );
    
}
 

// order bookings by user id 

   public function orderbookingsbyuser($status,$c1,$c2)
{ 
    
  $user_id= 81;//Auth::user()->id;
 
 $data=DB::table('exchange_bookings')->where('status',$status)->where('c1',$c1)->where('c2',$c2)-> where('user_id', $user_id)->orderBy('id', 'desc')->take(20)->get()  ;
  return response()->json($data );
    
}
      


// all users order history which are completed.
        public function allorderhistory($c1,$c2)
{ 
     
 $data=DB::table('exchange_bookings')->where('c1',$c1)->where('c2',$c2)->where('status',"completed")->orderBy('id', 'desc')->take(20)->get()  ;
  return response()->json($data );
    
}  

// order bookings  by type for all users 
  public function orderbookings($type)
{ 
    
    
  $qr="SELECT count(*) count ,c1_value amount , count(*) * c1_value total , rate price FROM `exchange_bookings` WHERE type='".$type."' and status='Pending' group by rate,c1_value order by rate desc LIMIT 20 ";
  
   //$data = DB::select('select * from users where active = ?', [1]);

  $data=  DB::select($qr);
    
   // var_dump($data);var_dump($qr);
    
    
// $data=DB::table('exchange_bookings')->where('status',"Pending")->where('type', $type)->orderBy('id', 'desc')->get()  ;
  return response()->json($data );
    
} 

//  Balance by user

  public function balance()
{ 
    
 
    
    $user=Auth::user();
    
  
         
           $call=  "https://api-pub.bitfinex.com/v2/tickers?symbols=ALL";
          
         $response =json_decode( file_get_contents( $call));
  
  
  
        $sum=0;
             
        
foreach($response as $r)
 
      {
        
           
           
		  
          if($r[0]=="tBTCUSD" )
           $sum=$sum+$r[1]*  $user->BTC;
          
          
        
          if($r[0]=="tLTCUSD" )
          //  $sum=$sum+$r[1]*$user->LTC;
          
		  
		  
        
          if($r[0]=="tETHUSD" )
       $sum=$sum+$r[1]*$user->ETH;
		   
		  
          
      }
        
          
		  
 $user->sUSD  = $sum;
  
   return response()->json( $user  );
       
    
}

// Tickers   checked working perfect
  public function tickers()
{ 
      $ar=array();
       $query = "SELECT pair
          FROM exchange_rate_buy";


 $pairs = DB::select("select distinct pair from exchange_rate_buy  ");

 
foreach($pairs as $p)
{
    
    
$pairs_rates = DB::select("select * from exchange_rate_buy where pair  ='".$p->pair."'  order by id desc limit 1 ");

 
foreach($pairs_rates as $pr)
{
 array_push($ar, $pr);
}

 

}
 return response()->json($ar);
  
  
    
}



// Tickers   checked working perfect
  public function rates($c1,$c2)
{ 
       
     

$pairs_rates = DB::table('exchange_rate_buy')->where('pair', $c1.$c2)->orderBy('id', 'desc')->first();

 
 
 
 

 
 return response()->json($pairs_rates);
  
  
    
}



    
}