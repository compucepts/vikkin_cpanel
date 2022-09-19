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

use App\Exchange_deposit;
use App\Bnext_Wallet;
use App\Duplicate_Bnext_Wallet;
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


class BTVCCtrl extends Controller
{
    
    private $ip;
    private $request;
private $private_blockchain_host;
    public function __construct(Request $request)
    {
        $this->request = $request;
        
    
            
            // $this->private_blockchain_host="http://167.99.255.217/btcrpc/";
            
             //  $this->private_blockchain_host="http://64.227.28.52/btcrpc/";
       //$this->ip="http://64.227.28.52";
       
         $this->private_blockchain_host="http://159.65.220.60/btcrpc/";
       $this->ip="http://159.65.220.60";
            
    }
    
    
    
    // Transaction List
    
    
    function listunspentbywallet( $wallet_name)
    {
        
 $user= Auth::user();
    //  var_dump($user);  
     $user_id=$user->id;
        
        
 $bn = Bnext_Wallet::where('user_id',$user_id)->where('wallet_name',$wallet_name)->first();
 
 
           
 $b=$this->listunspentbygivenID( $bn->wallet_name  );
   
   $s=json_decode($b);
 
 
   return json_encode($s->transactions);       
   
          
        
 
    }
    
    
     function listunspentbygivenID( $wallet_name )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?a=23&q=listunspent&w='.$wallet_name);


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
 

    return $result ;
 

 
 
    }
    
    
    // Get new wallet
    
    
    
        public function getNewwallet(){
           $user_id= Auth::user()->id;
         $bn1 = Bnext_Wallet::where('user_id',$user_id)->get();

  if(count($bn1) > 0)
  {
        $bn=new Duplicate_Bnext_Wallet();
   
          $wallet_name= "Dwallet_".rand()."".$user_id;
        
      
  }
  else
  {
      
      
     $bn=new Bnext_Wallet();
   
          $wallet_name= "wallet_".rand()."".$user_id;
        
  }
  
        $ar=array();
 
   

$ch = curl_init();
$url=$this->private_blockchain_host.'?q=createwallet&w='.$wallet_name;


curl_setopt($ch, CURLOPT_URL,$url );


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
$res=json_decode($result);
curl_close($ch);

//$balance=walletbalance( $wallet_name );

$bn->user_id=$user_id;
 


  $bn->address=  $res->address;

 // $bn->wallet_id=  $res->address;
  $bn->wallet_name=  $wallet_name; 
          
 
$bn->save();
return   $bn ;


 

}


// New Balance by wallet name


    function NewBalance(Request $request){
        
  $ar=array();      
             
 $address=$request->address;
 
 $bn = Bnext_Wallet::where('address',$address)->first();
   $b=$this->BalancebygivenID($bn->wallet_name  );
   $e=  number_format(  $b,8);

$ar['balance']=$e;
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
    
    
    // Send Fund
    
    function sendFund()
    {
        
   $ar=array();
                 $user_id= Auth::user()->id;
  $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
  
  $toAddress=$this->request['toAddress'];
   
       $amount=(float)$this->request['amount']   ; 
       
$ch = curl_init();

$ar=array();


$ar['wallet_name']=  $bn1->wallet_name;//  $this->request['wallet_name']; 

$ar['toAddress']=$toAddress;
$ar["amount"]=$amount;
$json=json_encode($ar);
  
$u=$this->private_blockchain_host.'?q=sendfund&w='.$ar['wallet_name'].'&a='.$ar['toAddress'].'&amount='.$ar["amount"];


curl_setopt($ch, CURLOPT_URL, $u);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, $json );



$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
$headers[] = 'Password: '.$bn1['password'];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = json_decode(curl_exec($ch));
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 
$r=array();
   $r['status']=200;
   $r['result']['hash']= $result->txid ;
   
    $r['result']['id']= $result->txid ;
   
   
   $r['rawresult']= $result ;     
  $r['hash']= $result->txid ; 
 //$r['u']= $u. $user_id;// $result ;    
           
  $r['message']="Successfully";
    return response()->json($r); 
 
    }
     
     // Wallet balance
     
       function Wallet( )
    {
        
        
        
        
        $user= Auth::user();
        
     $user_id=$user->id;
        
        
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


    
    
    
   return json_encode($ar);       
   
          
        
 
    }
    

}
 