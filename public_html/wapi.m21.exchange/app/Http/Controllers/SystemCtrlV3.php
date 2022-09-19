<?php


namespace App\Http\Controllers;

 
use App\Page;
use App;
use View;
use MetaTag; 
use Mail;

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
use App\ETH_Wallet;
use App\Exchange_deposit;
use App\Bnext_Wallet;
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


use App\Notifications\Login;


class SystemCtrlV3 extends Controller
{
    
    private $ip;
    private $request;
private $private_blockchain_host;
    public function __construct(Request $request)
    {
        $this->request = $request;
        
    
            
            // $this->private_blockchain_host="http://167.99.255.217/btcrpc/";
            
        //       $this->private_blockchain_host="http://64.227.28.52/btcrpc/";
        // $this->ip="http://64.227.28.52";
        //       http://
               
        $this->private_ethereum_host="http://64.227.28.52:8080/";
                
        $this->private_blockchain_host="http://162.255.116.244/btcrpc/";
       $this->ip="http://159.65.220.60";
       
       
    $this->private_blockchain_host="http://149.28.241.163/btcrpc/"; 
    
    
    }
 
 
 
 
  function Dashboard(  )
    {
        
        
        // show last login
  // show balance

  // show deposit
  
  
  
  

  // show last 3 notifiction
  // total login failure since your  last logintw
  // your current IP address+++
  
  
        $user= Auth::user();
        
     $user_id=     $user->id;
     
     
  $login_history = DB::table('login_history')->where('user_id',   $user_id) ->latest()->first();

     
  $system_notification = DB::table('system_notification')->where('user_id',   $user_id) ->latest()->first();

 

 $bn = Bnext_Wallet::where('user_id',$user_id)->get();
           
         
              $bal=0;
          foreach ($bn as $f) {
  
    
  $b=$this->BalancebygivenID( $f->wallet_name  );
  
   
    
 $bal=$bal+$b;
 
 
}
$ar=array();


$bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
      
      
          if(is_null( $bn1)  )
   {
 
 
   $bn1= $this->generateAddressPrivateBlockChain($user_id);
   
   }
   
   
   
        $address=$bn1->address;
           
           
$e=  number_format(  $bal,8);

$ar['balance']=$e;
$ar['address']=$address;


$ar['btc_address']=  "";//$this->getBtcAddress( );
$ar['btc_balance']=  $user->BTC;
 
 
 
     
     
        $f=file_get_contents("https://api.bitfinex.com/v1/pubticker/btcusd");
          $s=json_decode($f);
         
          $rt=$s->bid;
         
        
          
  
   $ar['btc_rate']=sprintf('%0.2f',$rt)   ;  //  round($no,2);
    
 
 
 
  $ar['login_history']= $login_history;
  $ar['system_notification']= $system_notification;
    
   return json_encode($ar);       
   
          
     
          
 
    }
    
    
    
       function BalancebygivenID( $wallet_name )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=balance&w='.$wallet_name);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 

$res=json_decode($result);
 
 if ($res) {
    return $res->balance  ;
} else {
   return 0;
}

 
 
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    public function generateAddressPrivateBlockChain($user_id)
    {
        
         
    
    $bn1 = Bnext_Wallet::where('user_id',$user_id)  ->get();
               
               
    
   
   if(count( $bn1) > 0)
   {
 
 
        
        return   $bn1[0] ;
   
   }
   
   
   
        $ar=array();
        
        
        
        
        
        
        $bn=new Bnext_Wallet();
        
          
         
         
         $wallet_name= "dubaikoin_".rand()."wallet_".$user_id;
        $wallet_name= "wallet_".rand()."".$user_id;
$ch = curl_init();
$url=$this->private_blockchain_host.'?q=createwallet&w='.$wallet_name;


curl_setopt($ch, CURLOPT_URL,$url );

DB::insert('insert into logpb (  msg) values  (?)', [$url]);



curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($ar));

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

//print($result);



DB::insert('insert into logpb (  msg) values  (?)', [$result]);


$res=json_decode($result);

$bn->user_id=$user_id;
 


  $bn->address=  $res->address;

  $bn->wallet_id=  $res->address;
   $bn->wallet_name=  $wallet_name; 
          
 
curl_close($ch);

$bn->save();

return   $bn;
     
    }
    
    
    
    
    
}
 
 ?>