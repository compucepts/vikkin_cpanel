<?php


namespace App\Http\Controllers;

 
use App\Page;
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

use App\Exchange_widthdraw;
use App\Exchange_bookings;
use Illuminate\Support\Facades\DB;

 
use App\System_transactions;
use App\Http\Controllers\Controller;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;



class BackgroundCtrl  extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        
           
            
            
    }
  
  
  
  
  
  

    
function   private_blockchian_deposit(){
   
   
     DB::table('system_logs')-> Insert(
    [ 
    'dump' => print_r($_REQUEST,true)]
);

 
  
$address=$_REQUEST['address'];


 $wallet=DB::table('bnext_wallet')->where("address",$address)->first()  ;
 
 
 $amount=$_REQUEST['amount'];
 $txid=$_REQUEST['txid'];
 
$user= User::findOrFail($wallet->user_id);
$coin="DBIK";
 
  
  
    $pl=new Paymentcls();
   $pl->credit_user( $user ,  $amount,$coin  ,'DBIK deposit '.$txid  );
         
   
 
 
 
 
 
  
   
}








  public function wallet_list()
{ 
    
    $coincls=new CoinCls();
    
    
    //$user_id= Auth::user()->id;
 $data=DB::table('bnext_wallet')->orderBy('id', 'desc')->get()  ;
      
foreach($data as $d)
{
    
    echo "<hr />";
  echo $d->address;
    echo "<br />";echo "<br />";
   print_r(    $d );
   
  echo "<br />"; echo "<br />"; echo "<br />"; echo "<br />"; echo "<br /> balance  --";

try {
 print_r(   $coincls->balance(  $d->address  ) );
 
  $coincls->sendFund(  $d ,1   );
 
 
} catch (\Throwable $e) { // For PHP 7
  // handle $e
} catch (\Exception $e) { // For PHP 5
  // handle $e
}


   
  
    
}
    
}
  
  
function   mine($address){
  
$ar=  rand(1,100000);

if($ar==1000)
{
    return true;
exit();
    
}

  $ar=array();
  $ar['rewardAddress']=$address;
   $ar['feeAddress']=$feeAddress;
  
   $json=json_decode($ar);
   
  
  
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://192.241.137.168/miner/mine');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

}


    
function   btcdeposit(){
   
   
     DB::table('system_logs')-> Insert(
    [ 
    'dump' => print_r($_REQUEST,true)]
);


 if ($_GET['confirmations'] <> 3)
  {
 exit();
  }
  


 if ($_GET['confirmations']  > 5 )
  {
 echo '*ok*';
  exit();
  }
  
 
    $user_id=$_REQUEST['user_id'];
    
    $confirmations=$_REQUEST['confirmations'];
$secret=$_REQUEST['secret'];
$transaction_hash=$_REQUEST['transaction_hash'];
$value=$_REQUEST['value'];
$address=$_REQUEST['address'];

 $value=$value * 0.00000001;

$hit_url=$_SERVER['HTTP_HOST'] ."/". $_SERVER['REQUEST_URI'];



 
$user= User::findOrFail($user_id);

    //DB::delete("delete from system_transactions where transaction_hash ='". $transaction_hash."' and user_id ='". $user_id."'");
  
  

   $old_balance= $user->BTC  ;
    
    $new_balance=  (double)$old_balance+(double)$value;
    
    
    
     DB::table('system_transactions')-> Insert(
    ['status'=> "Success",'user_id' => $user_id ,
    'confirmations' => $confirmations , 
    'coin' => 'BTC', 'amount' => '0',
    
      'type' =>  'credit' ,'amount' => $value  ,'balance' => $new_balance ,
    
    
    'cr' => $value,'transaction_hash' => $transaction_hash
    ,'hit_url' => $hit_url , 'address' => $address , 
    'dump' => print_r($_REQUEST,true)]
);

/*
 
     
    
  $trid = DB::table('system_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin  ,'type' =>  'credit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' =>$description   ]
);
*/

$user->BTC =$new_balance;

$user->save();
 echo '*ok*';
 
 
 
 
 
  
   
}




            public function dailybonus( )
    {
     
    
    $users = DB::table('users')->orderBy('id','desc')->get();
    foreach($users as $u)
    {
         $bal=$this->UserBalance("BTC",$u->id);
        if($bal<>0)
        {
          $st = new system_transactions;
        $st->user_id =$u->id;
        
        $pc=floatval($u->dailybonus) /3000;
        echo $pc;
        $st->cr =$bal*$pc;
       
             $st->dr = 0;
       
             $st->coin ="BTC";
       
           $st->status =  "Success";
       
             $st->invoice_id = "BONUS".$u->id.date("Ymd");
       
             $st->description =  "Daily bonus award " ;
         
        
        if( $st->cr<> 0)
        $save =    $st->save();
        
        }
        
        
    }
    
    
    
     

      }    
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      



 public function awardprofitplanA( )
    {
     
    
    $orders = DB::table('mlm_orders_investment')->where('package_name',  "package_one")->where('status',  "In Process")->orderBy('id','desc')->get();
    
    
    foreach($orders as $o)
    {  
        if($this->revieworders($o ) <  5)
        {
         $this->awardprofit($o,"0.08" );
          
        }
        else
        {
            
           $this->complete_order($o  );
            
            
            $this->transferpayment($o  );
        }
    }
    
    
  }    
  
  
  

 public function awardprofitplanB( )
    {
     
    
    $orders = DB::table('mlm_orders_investment')->where('package_name',  "package_two")->where('status',  "In Process")->orderBy('id','desc')->get();
    
    
    foreach($orders as $o)
    {  
        if($this->revieworders($o ) <  2)
        {
         $this->awardprofit($o,"19.50" );
          
        }
        else
        {
            
           $this->complete_order($o  );
            
            
            $this->transferpayment($o  );
        }
    }
    
    
  }    
  
  
  
  
  
 public function awardprofitplanC( )
    {
     
    
    $orders = DB::table('mlm_orders_investment')->where('package_name',  "package_three")->where('status',  "In Process")->orderBy('id','desc')->get();
    
    
    foreach($orders as $o)
    {  
        if($this->revieworders($o ) <  2)
        {
         $this->awardprofit($o,"25.00" );
          
        }
        else
        {
            
           $this->complete_order($o  );
            
            
            $this->transferpayment($o  );
        }
    }
    
    
  }    
  
  
  
 public function awardprofitplanD( )
    {
     
    
    $orders = DB::table('mlm_orders_investment')->where('package_name',  "package_four")->where('status',  "In Process")->orderBy('id','desc')->get();
    
    
    foreach($orders as $o)
    {  
        if($this->revieworders($o ) <  24*28*6)
        {
         $this->awardprofit($o,"25.00" );
          
        }
        else
        {
            
           $this->complete_order($o  );
            
            
            $this->transferpayment($o  );
        }
    }
    
    
  }    
  
  
  
            private function transferpayment($o  )
    {
     
     $bal= $this->UserInvestmentBalance($o->coin,$o->user_id);
     
     
            //////////////////////
        $st = new system_transactions;
   
    
        $st->user_id =$o->user_id;   
        
        
        $st->cr =  $bal; 
       
             $st->dr =0; 
       
             $st->coin =  $o->coin; 
             
             $st->invoice_id = "INVESTMENT".$o->id; 
       
           $st->status =  "Success";
        
       
 $st->description = "Investment of ".$o->amount." for the package ".$o->package_name ;
         
         
       
        
        $save =    $st->save();
        
        
        ////////////////////////////
        
          $si = new mlm_system_investments;
   
    
        $si ->user_id =$o->user_id;   
        
        
        $si->dr =$bal;
       
             $si->cr =0;
       
             $si->coin = $o->coin; 
             
             $si->order_id =  $o->id; 
       
           $si->status =  "Success";
        
       
             $si->description =  "Earning from the investment ".$o->id ;
         
       
        
        $save =    $si->save();
        
        
        
        ////////////////
    }

  
  
  
            private function complete_order($o  )
    {
     
     
     DB::update("update mlm_orders_investment set status = 'Complete' where id = ".$o->id);
            
    
    }


            private function revieworders($o  )
    {
     
     
    $count = DB::table('mlm_system_investments')->where('order_id',  $o->id)->count();
    
    echo $count;
    
    return $count;
    
    }




            private function checkbeforeaward($user_id,$total_award )
    {
     
     
    $users = DB::table('system_transactions')->where('package_name',  "Package one")->orderBy('id','desc')->count();
    
    }















            private function awardprofit($o,$earning_pc )
    {
     
  
          
          $st = new mlm_system_investments;
      $st->user_id =$o->user_id;
        
               $st->order_id =$o->id;
        
        
        
        $st->cr =$o->amount* floatval($earning_pc) /100;
       
             $st->dr = 0;
       
             $st->coin ="BTC";
       
           $st->status =  "INVESTMENT";
       
             $st->invoice_id = "INVESTMENT".$o->user_id.date("Ymdh");
       
             $st->description =  "Daily INVESTMENT award " ;
         
        
        $save =    $st->save();
        
       

      }   
      
      
      
      
      
      
      
      
      
      
          
        private function UserInvestmentBalance($coin,$user_id)
    { 
  
         $data = DB::table('mlm_system_investments')
                     ->select(DB::raw('sum(cr) - sum(dr) as balance '))
                     ->where('status', '=', "INVESTMENT")
                        ->where('coin', $coin)
                         ->where('user_id', $user_id)
                        ->get();
      return  floatval($data[0]->balance );
            
    
    }   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
            public function makePreimum( )
    {
       /*   $call=  "https://api-pub.bitfinex.com/v2/tickers?symbols=ALL";
          
         $response =json_decode( file_get_contents( $call));
  
  $json='{ "backoffice_user_email": "",
  "backoffice_user_id": "",
  "bonus_amount" : "" }';
  */
  
    
    $records = DB::table('users')->select(DB::raw('email'))
                  ->whereRaw('Date(created_at) = CURDATE()')->get();
                  
          
          
 return response()->json($records);
 
    
    }
    
          
            public function UserBalance($coin,$user_id)
    {
         
  
         $data = DB::table('system_transactions')
                     ->select(DB::raw('sum(cr) - sum(dr) as balance '))
                     ->where('status', '=', "Success")
                        ->where('coin', $coin)
                         ->where('user_id', $user_id)
                        ->get();
     
                     
      //return $data[0]->balance * 0.00000001;  
      
     
          return  floatval($data[0]->balance );
            
    
    }
    
    
    
            
}