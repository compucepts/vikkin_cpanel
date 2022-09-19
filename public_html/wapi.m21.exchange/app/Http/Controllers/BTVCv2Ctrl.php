<?php


namespace App\Http\Controllers;

 
use App\Page;
use App;
use View;
use MetaTag; 
use Mail;

use App\Http\Controllers\GoogleAuthCtrl;

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
use Exception;

use App\Notifications\Login;


class BTVCv2Ctrl extends Controller
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
       
       
       
      //  $this->private_blockchain_host="http://146.70.81.96/btcrpc/";
       
         //$this->private_blockchain_host="http://159.65.220.60/btcrpc/";
       $this->private_blockchain_host="http://162.255.116.244/btcrpc_0507/";
     //  $this->ip="http://159.65.220.60";
      
    $this->private_blockchain_host="http://149.28.241.163/btcrpc/";       
    }
    
  //  list all wallets api
      public function walletlist(){
                  
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=listwallets');


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
      
      
        //  get all address by wallet name
      public function walletaddress($wallet_name){
                  
       
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=listreceivedbyaddress&w='.$wallet_name);


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
 
   return $result;
      }
      
      
        //address by balance api
    function addressbybalance( $address,$wallet_name )
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

      
        
     public function getAllAddress(){
            
            
           $user_id= Auth::user()->id;
         $bn  = Bnext_Wallet::where('user_id',$user_id)->first();
         
         $m=array();
         echo $bn->wallet_name ;
         
         $r=json_decode($this-> getAllAddressbyWallet($bn->wallet_name ));
 
         print_r($r);
//          foreach($r as $a)
//          {
             
//                   // print_r($a);
   
//          $d=array();
//          $address=$a->address;
         
         
    
//     $d['address']=$a->address;
    
   
//      $d['amount']=$a->amount;
    
   
//      $d['confirmations']=$a->confirmations;
    
   
//   //  $d['txids']=$a->txids;
    
//       $dc=DB::table('invoice_address')->where('address',$address)->count()  ;
//       if($dc>0)
//       {
      
//         $data=DB::table('invoice_address')->where('address',$address)->first()  ;
   
//      $d['invoice_id']=$data->invoice_id;
    
    
   
//      $d['full_name']=$data->full_name;
    
    
   
//      $d['email']=$data->email;
    
     
//       }
//   array_push($m,$d);
    
      
    
             
//          }
         
         
      return $m;
         
         
     }
    
    
    
    
    // Transaction List
    
     //
     
     function send_notification_background_job()
     {
         
         // fetch an address
         
         
         $send_fund = DB::table('send_fund')
                ->where ('status',0)
                ->get();
                
                
              
                
                foreach($send_fund as $s)
              
              
              {  print_r($s);
              $this->send_notification( $s->address);
              }
                
                
         // fetch balance 
         
     }
     
     
    function send_notification( $address)
    {  
        
        
        //invoice_address
        
        
         $data=DB::table('invoice_address')->where('address',$address)->first()  ;
         
         
         
  if(   !isset($data))
  {
      
      
      return "";
  }
      
      $user_id=$data->user_id;
      
   
  
       
      $notification_url = $data->notification_url;
      
      
      // prepare data to send
      
      $res=array();
  
 
 

     $r =  $this->AddressBalance_with_user_id( $address, $user_id );
     
     
     $res['data']=$r['data'] ;
     
     
   //    $res['Balance']=$r['Balance'] ;
     
    $amount  =  $r['data']->amount;
    
    
    
          if(  $amount > 0 )
          {
        
        
 
$result=  $this->sendJSON($res, $notification_url);
 
 
     $id = DB::table('notification_log')->insertGetId([
         
         'amount' =>    $amount,
         'address' =>  $address,
         'json' =>print_r($res,true),
         'notification_url' => $notification_url,
         'notification_response' => $result ,
         'user_id' => $user_id 
         
         ]);
 
      
    
    
        
    }
   
    
    
        
    }
    
    
    
    
    function send_notification_invoice( $address)
    {  
         try
         {
        
    $data=DB::table('invoice_address')->where('address',$address)->first()  ;
    
    
   
         
        // $bn1 = Bnext_Wallet::where('address',$address)->first();
 
 $u= User::where('id',$data->user_id)->first();
 
    $res=array();
  
        $r =  $this->AddressBalance_with_user_id( $address, $data->user_id );
        
        
        
        $bal= $r  ;
        
 
 
     //  print_r($r)  ;
       
    
 
 
        
      if(!$bal['Error'])
      {
          $d=$bal['data'];
          
          
          if($d->amount > 0 )
          {
              $data=DB::table('invoice_address')->where('address',$address)->first()  ;
 
 $res['invoice']=$data;
 
 
 $res['invoice']=$d;
 
 
$result= $this->sendJSON($res, $data->notification_url);
 
 
     $id = DB::table('notification_log')->insertGetId(
    [   'amount' =>  $bal->amount,
    'address' =>  $address,
      'json' =>$res,
    'notification_url' => $data->notification_url,
      'notification_response' => $result ,
          'user_id' => $bn1->user_id ]
);
 
 
          } 
          
          
          
      }
      
 
 
    } catch (Exception $e) {

    }  
    
     
catch (ModelNotFoundException $e)
{

}




}
    
    
    
    
    
    
    function sendJSON($payload, $url)
    {
     
    //  $url="https://casinokoin.com/t.php";
        
        
      $data = array(
    "name" => "John",
    "email" => "john@test.com"
);
 
// Convert the PHP array into a JSON format
 
// Initialise new cURL session
$ch = curl_init( $url);

// Return result of POST request
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Get information about last transfer
curl_setopt($ch, CURLINFO_HEADER_OUT, true);

// Use POST request
curl_setopt($ch, CURLOPT_POST, true);

// Set payload for POST request
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode( $payload));
 
// Set HTTP Header for POST request 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json'  )
);
 
// Execute a cURL session
$result = curl_exec($ch);
 
// Close cURL session
curl_close($ch);
return $result;
 

    }
    
    
    
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
    
    
      
     function AddressBalance_with_user_id( $address,$user_id )
    {
       
        
 $ar=array();
 
$count = Bnext_Wallet::where('user_id',$user_id)->count();


         if( $count < 1 )
         {
              $ar['Error']=true;
              $ar['Message']="No Wallet find";
              
             return  $ar ;
             
         }
         
         
         $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
         
         
         
   $wallet_name=$bn1->wallet_name;
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?a=23&q=listunspent&w='.$wallet_name);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$b = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

 
   $s=json_decode($b);
   
   
   
     $ar['line']  ="";
     
 $ar['Balance']=0;
 
 if($s->transactions){
 
 


 foreach($s->transactions as $tr)
 {
     
     
     
    // $ar['line']  .=PHP_EOL .__LINE__."--------------------------------".$tr->address."--ts-".$address;
     
     if($tr->address  ==  $address)
     {
 $ar['line']  .=__LINE__."-----------------".$tr->address.$address;
      $ar['Error']=false;
      
      
           $ar['Balance']=$ar['Balance']+$tr->amount;
                   
              
              $ar['Message']="Success!";
              
              
             $ar['data']=    $tr;
              
        
     }
     
 }
 
 }
   return $ar;      
 
 
 
 
 
    }
    
    
    
    
    
     function AddressBalance( $address )
    {
       
          $user_id= Auth::user()->id; 
          
          return $this->AddressBalance_with_user_id( $address,$user_id );
          
          
    }
    
    
     function InvoiceAddress( Request $r  )
    {
          $user_id= Auth::user()->id;
          
          
         $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
   $wallet_name=$bn1->wallet_name;
 

$result=$this->getAddress($wallet_name,   $user_id );
 
 
$address=json_decode($result)  ;
 
     $id = DB::table('invoice_address')->insertGetId(
    ['amount' =>  $r->amount,
    'address' =>   $address->address,
    'invoice_id' =>   $r->invoice_id,
    'full_name' =>   $r->full_name,
    'email' =>   $r->email,
     'json' => $result ,
    'notification_url' =>  $r->notification_url, 
          'user_id' =>$user_id ]
);


 
   return $result;
 
    }
    
     
     function getAddress($wallet_name,   $user_id )
    {
         // $user_id= Auth::user()->id;
          
      
         
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?a=23&q=createaddress&w='.$wallet_name);


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
 
 
return $result;
 
 
 
    }
    
    
    
     function InvoiceAddress11( Request $r  )
    {
          $user_id= Auth::user()->id;
         $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
   $wallet_name=$bn1->wallet_name;
      //  echo    $wallet_name;
      //   var_dump($bn1);
         
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?a=23&q=createaddress&w='.$wallet_name);


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
 
 

$address=print_r(json_decode($result) ,true);


     $id = DB::table('invoice_address')->insertGetId(
    ['amount' =>  $r->amount,
    'address' =>   $address,
    'invoice_id' =>   $r->invoice_id,
    'full_name' =>   $r->full_name,
    'email' =>   $r->email,
     'json' => $result ,
    'notification_url' =>  $r->notification_url, 
          'user_id' => $r->user_id ]
);


 
    return $result ;
 
 
 
 
    }
    
     function getNewAddress(   )
    {
          $user_id= Auth::user()->id;
         $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
   
   
   $wallet_name=$bn1->wallet_name;
   
   
   
      //  echo    $wallet_name;
      //   var_dump($bn1);
         
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?a=23&q=createaddress&w='.$wallet_name);


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
 
  $d=json_decode($result);



     

  
  
    return $result ;
 
 
 
 
    }
    
    // Get new wallet
    
    
    
        public function getNewwallet(){
            
            
           $user_id= Auth::user()->id;
         $bn1 = Bnext_Wallet::where('user_id',$user_id)->get();

 
  
    if(count($bn1) > 0)
  {
      
      


      return   $bn1 ;
      
  }
  
  else
  {
      
      
     $bn=new Bnext_Wallet();
   
          $wallet_name= "wallet_".rand()."".$user_id;
        

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

$this->getNewAddress();

return   $bn ;

  }
  
 

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

    
     public function getAllAddress22(){
            
            
           $user_id= Auth::user()->id;
         $bn  = Bnext_Wallet::where('user_id',$user_id)->first();
         
         
         $r=$this-> getAllAddressbyWallet(  $bn->wallet_name );
         
         
      return $r;
         
         
     }
     
     
     
     
        function getAllAddressbyWallet( $wallet_name )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=listreceivedbyaddress&w='.$wallet_name);


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
 
 
 
   return $result;
 

 
 
    }
    
    function sendFund(){
        $user_id= Auth::user()->id;
                 
  $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
  
  
                 // fetch parameters 
  
  $toAddress=$this->request['toAddress'];
 $amount=(float)$this->request['amount']   ; 
       
       
       
        

$ar['wallet_name']=  $bn1->wallet_name;//  $this->request['wallet_name']; 

$ar['toAddress']=$toAddress;
$ar["amount"]=$amount;

 $balance=$this->BalancebygivenID($ar['wallet_name'] );
 
if($balance < $amount )
{
    $r=array();
    $r['Error']=true;
     $r['message']="Insufficient Balance in the wallet to complete transaction.";
    return response()->json($r);    
}



// send notification

	$n=array();
	$n['message']="Send Fund Request Forwarded";
	
	$n['type']="success";
	$n['url']="localhost";
	
	 
	
	$n['user_id']=  $user_id;
	
	$noti=new Notification();
    $noti->aistore_notification_new($n  );
    
    
    
    
$json=json_encode($ar);


$ch = curl_init();


  
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
$r=array();
$result = json_decode(curl_exec($ch));
//print_r($result->txid);

if (curl_errno($ch)) {
    //echo 'Error:' . curl_error($ch);
    $r['message']='Error:' . curl_error($ch);
}

 
else{
  $r['status']=200;
  $r['result']['hash']= $result->txid ;
   
    $r['result']['id']= $result->txid ;
   
   
  $r['rawresult']= $result->txid ;     
   $r['hash']= $result->txid ; 

           
            
         
         
            
  $r['message']="Successfully";
  
}
curl_close($ch);
   return response()->json($r); 
 

    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // Send Fund
    
//     function sendFund()
//     {
//       // some data fetch 
//   $ar=array();
   
   
   
//                          $user_email= Auth::user()->email;
//         $send_fund_email_otp=$this->request['email_otp'];
        
        
//         $user_email_otp= Auth::user()->send_fund_email_otp;
//       // echo $user_email_otp;
        
//         if($user_email_otp==$send_fund_email_otp){
            
            
//          $auth_code=$this->request['auth_code'];
//      $secret=DB::table('exchange_g2f')->where('email',$user_email)->first()  ;
//  //  var_dump($secret);
   
//   // echo $secret->secret;
//      	$googleauth=new GoogleAuthCtrl($this->request);
   
    
//          $checkResult = $googleauth->verifyCode( $secret->secret, $this->request['auth_code']  ,   5);    
 
//  if ( $checkResult) {
     
                 
//  $user_id= Auth::user()->id;
                 
//   $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
  
  
//                  // fetch parameters 
  
//   $toAddress=$this->request['toAddress'];
//  $amount=(float)$this->request['amount']   ; 
       
       
       
        

// $ar['wallet_name']=  $bn1->wallet_name;//  $this->request['wallet_name']; 

// $ar['toAddress']=$toAddress;
// $ar["amount"]=$amount;

//  $balance=$this->BalancebygivenID($ar['wallet_name'] );
 
// if($balance < $amount )
// {
//     $r=array();
//     $r['Error']=true;
//      $r['message']="Insufficient Balance in the wallet to complete transaction.";
//     return response()->json($r);    
// }



// // send notification

// 	$n=array();
// 	$n['message']="Send Fund Request Forwarded";
	
// 	$n['type']="success";
// 	$n['url']="localhost";
	
	 
	
// 	$n['user_id']=  $user_id;
	
// 	$noti=new Notification();
//     $noti->aistore_notification_new($n  );
    
    
    
    
// $json=json_encode($ar);


// $ch = curl_init();


  
// $u=$this->private_blockchain_host.'?q=sendfund&w='.$ar['wallet_name'].'&a='.$ar['toAddress'].'&amount='.$ar["amount"];


// curl_setopt($ch, CURLOPT_URL, $u);

// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_POST, 1);

// curl_setopt($ch, CURLOPT_POSTFIELDS, $json );



// $headers = array();
// $headers[] = 'Content-Type: application/json';
// $headers[] = 'Accept: application/json';
// $headers[] = 'Password: '.$bn1['password'];
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// $r=array();
// $result = json_decode(curl_exec($ch));

// if (curl_errno($ch)) {
//     //echo 'Error:' . curl_error($ch);
//     $r['message']='Error:' . curl_error($ch);
// }

 
// else{
//   $r['status']=200;
//   $r['result']['hash']= $result->txid ;
   
//     $r['result']['id']= $result->txid ;
   
   
//   $r['rawresult']= $result ;     
//   $r['hash']= $result->txid ; 

           
           
//   $id = DB::table('send_fund')->insertGetId([
         
//          'amount' =>    $amount,
//          'address' =>  $toAddress 
        
         
//          ]);
         
         
         
            
//   $r['message']="Successfully";
  
// }


// }
 
//  else{
//      $r=array();
//   $r['Error']=true;
   
//   $r['message']="Incorrect Auth Code";
//     return response()->json($r);
//  }
 
 
//         }
        
//         else{
//               $r=array();
//   $r['Error']=true;
   
//   $r['message']="Incorrect OTP";
//     return response()->json($r);
//         }
        
        
// curl_close($ch);
//     return response()->json($r); 
 
//     }
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     function sendFundtoaddress($toAddress, $amount)
    {
        
   $ar=array();
                 $user_id= Auth::user()->id;
  $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
  
  //$toAddress=$this->request['toAddress'];
   
     //  $amount=(float)$this->request['amount']   ; 
       
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
   
    $r['hash']=  'hash'; 
    
    
    
 /*  $r['result']['hash']= $result->txid ;
   
    $r['result']['id']= $result->txid ;
   
   
   $r['rawresult']= $result ;     
  $r['hash']= $result->txid ; 
 //$r['u']= $u. $user_id;// $result ;    
           
           
           
       //   $this->send_notification( $toAddress);
            
            */
            
            
            
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
 