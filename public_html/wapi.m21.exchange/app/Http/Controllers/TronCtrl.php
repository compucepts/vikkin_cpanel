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


class TronCtrl extends Controller
{
    
    private $ip;
    private $request;
private $private_blockchain_host;
    public function __construct(Request $request)
    {
        $this->request = $request;
        
     
     
    }
 

public function processAddress($address){
    $ar=  array();
    
    
    
    
    $bal=$this->getBalanceFloat($da->address )  ;
    
    
    
  //  $address=$this->getUSDTTronAddress( );
    $ar['address']= $address  ;
return response()->json($ar );
}







public function sendUSDT(){
    $ar=  array();
    
    if($_SERVER['SERVER_NAME']=="api.vikkin.ltd")
    $address= $this->getTronAddress( );
    else
    $address= $this->FetchOneTronAddress( );
    
    
    $ar['address']= $address  ;
    $ar['balance']= $address  ;
return response()->json($ar );
}







public function USDTTron1(){
    $ar=  array();
    
   // if($_SERVER['SERVER_NAME']=="api.vikkin.ltd")
  //  $address= $this->getTronAddress( );
  //  else
    $address= $this->FetchOneTronAddress( );
    
    
    $ar['address']= $address  ;
    

//$bal=$this->getBalanceFloat($address )  ;

    $ar['balance']=0; $bal ;
    
    
return response()->json($ar );
}





// process all deposits


 function checkDeposits11111(  )
{
    
    /*
$deposit_address = DB::table('deposit_address')  ->where ("coin","USDT Tron")->take(30)->get();
  // $deposit_address = DB::table('deposit_address')->whereNull("status" )->where('created_at', '>', Carbon::now()->subMinutes(30)->toDateTimeString())->where ("coin","USDT Tron")->get();
    
 
    $a=array();
    
    foreach($deposit_address as $da)
{

var_dump($da->address);


$bal=$this->getBalanceFloat($da->address )  ;

  
  
var_dump($bal);


 
if($bal>0 )
{
    $user_id=$da->user_id;
    
 
 
 exit();
 $id =  DB::table('deposit_address')
            ->where('id',$da->id)
            ->update([ 'status' =>  "Credited" ]);
            
            
            
 //   var_dump($da);
    
    $ar=array();
$ar['address']=$da->address;
    
  $ar['balance']=$bal;
 
  $ar['coin']= "USDT";
 
   $pl=new Paymentcls();
 
  $user =DB::table('users')->where('id',$user_id)->first(); 
 
    $des='Tron deposit on address '. $da->address ;
    
    
     $res=   $pl->credit_user( $user  ,  $bal,     "USDT"  ,$des,$da->address  );
     
   
     
     $id=$da->address;
     
     //////////////
     
     
     
       $admin=DB::table('users')->where('email', "susheel3010@gmail.com")->first()  ;
    
    
  $step1=  $pl->payment_transfer( $user , $admin , $bal, "USDT" , $des  ,$id   );
    
    
  $step2=     $pl->payment_transfer( $admin , $user , $bal,"DBIK" , $des  ,$id   );
    //////////////////////
     
  
// $this->credit_tron_payment($da  );
 
 array_push($a,$ar);
 
 
 
 
}
 


 $id =  DB::table('deposit_address')
            ->where('id',$da->id)
            ->update([ 'status' =>  "Checked" ]);

}
return response()->json($a );
    
 */
 
}


 public  function credit_tron_payment($deposit_data  )
{
     
 $ar=array();
 
 $private_key= $deposit_data->private_key;
     
 $address= $deposit_data->address;
 
 
  $root_url = 'http://162.255.116.244:3000/ToDepositNewFund/'.$private_key.'/'.$address;
 
 $response = file_get_contents( $root_url);
 }







   function sendnotification(  )
{
    $deposit_address = DB::table('deposit_address')->select("address","notification_url") ->whereNotNull("notification_url" )->where('coin', 'Tron')->get();
    
    $a=array();
    
    foreach($deposit_address as $da)
{

$ar=array();
$ar['address']=$da->address;


$bal=$this->getBalanceFloat($da->address )  ;


if($bal>0 )
{
 $ar['balance']=$bal;
 
 if(  $this->isurl($da->notification_url)  )
 {
     $this->processnotification($da->notification_url,$bal);
 
 $ar['data']=$da;

}
}
else
{
  $ar['balance']="0";
  
}
array_push($a,$ar);

}
return response()->json($a );
    
 

}



 public   function isurl($notification_url  )
 {
     return true;
 }
 

public  function processnotification($notification_url ,$bal )
 {
     file_get_contents($notification_url."?balance=".$bal );
     
     return "";
 }
 
 
 public  function get_new_tron_address(  )
{
    
    return $this->request()->getHttpHost();
    
    
   //  if($this->request()->getHttpHost() =="vikkin")
   // return $this->getTronAddress();
     
     $notification_url=$this->request['notification_url'];
     
 $ar=array();
 
 
   $deposit=   "";
   
   
      $user_id= Auth::user()->id;
     
      
     
    

$root_url = 'http://45.63.85.100/generateAccount';

 
 
$response = file_get_contents( $root_url);
 
// return $response;
 
 
      
   $object = json_decode($response);
 
//$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
 $privateKey=  $object->privateKey;
  $address=$object->address->base58;
   $publicKeyy=  $object->publicKey;
  $Mnemonic=  "";//$object->Mnemonic;
  
DB::table('deposit_address')->insert(['coin'=>"Tron", 'user_id' => $user_id, 'address' =>$address,'url_hit' =>  $root_url ,'url_res' =>$response ,'Mnemonic'=>$Mnemonic,'publicKey'=>$publicKeyy,'privateKey'=>$privateKey,'notification_url'=>$notification_url]);
 
 
 
      $ar=  array();
     
    $ar['address']= $address  ;
return response()->json($ar );
   
    
    
    
    
}
 public  function getOneTronAddress(  )
{
     
     
      $user_id= Auth::user()->id;
     
     
     
$deposit_address = DB::table('deposit_address')->select("address")->where('user_id', $user_id)->where('coin', 'Tron')->first();
    
    if($deposit_address)
    {
        


$ar=array();
$ar['address']=$deposit_address->address;
 $ar['balance']=$this->getBalanceFloat($deposit_address->address )  ;

 

return response()->json($ar );
   
    }
 
    



     
     
 $ar=array();
 
 
   $deposit=   "";
   
   
      
     
    

$root_url = 'http://45.63.85.100/generateAccount';

 
 
$response = file_get_contents( $root_url);
 
// return $response;
 
 
      
   $object = json_decode($response);
 
//$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
 $privateKey=  $object->privateKey;
  $address=$object->address->base58;
   $publicKeyy=  $object->publicKey;
  $Mnemonic=  "";//$object->Mnemonic;
  
DB::table('deposit_address')->insert(['coin'=>"Tron", 'user_id' => $user_id, 'address' =>$address,'url_hit' =>  $root_url ,'url_res' =>$response ,'Mnemonic'=>$Mnemonic,'publicKey'=>$publicKeyy,'privateKey'=>$privateKey,'notification_url'=>$notification_url]);
 
 
 
      $ar=  array();
     
    $ar['address']= $address  ;
    
     $ar['balance']=$this->getBalanceFloat($address )  ;

 
 
 
return response()->json($ar );
   
    
    
    
    
}

 public  function FetchOneTronAddress(  )
{
     
     
      $user_id= Auth::user()->id;
     
     
     
$deposit_address = DB::table('deposit_address')->select("address")->where('user_id', $user_id)->where('coin', 'Tron')->first();
    
    if($deposit_address)
    {
        


$ar=array();
$ar['address']=$deposit_address->address;
 $ar['balance']=$this->getBalanceFloat($deposit_address->address )  ;

 

return $deposit_address->address;
   
    }
 
    



     
     
 $ar=array();
 
 
   $deposit=   "";
   
   
      
     
    

$root_url = 'http://45.63.85.100/generateAccount';

 
 
$response = file_get_contents( $root_url);
 
// return $response;
 
 
      
   $object = json_decode($response);
 
//$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
 $privateKey=  $object->privateKey;
  $address=$object->address->base58;
   $publicKeyy=  $object->publicKey;
  $Mnemonic=  "";//$object->Mnemonic;
  
DB::table('deposit_address')->insert(['coin'=>"Tron", 'user_id' => $user_id, 'address' =>$address,'url_hit' =>  $root_url ,'url_res' =>$response ,'Mnemonic'=>$Mnemonic,'publicKey'=>$publicKeyy,'privateKey'=>$privateKey,'notification_url'=>$notification_url]);
 
 
 
  
 
return $address;
   
    
    
    
    
}





public function all_address_balance()
{
    
      $user_id= Auth::user()->id;
      
      
$deposit_address = DB::table('deposit_address')->select("address")->where('user_id', $user_id)->where('coin', 'Tron')->get();
    
    $a=array();
    
    foreach($deposit_address as $da)
{

$ar=array();
$ar['address']=$da->address;
 $ar['balance']=$this->getBalanceFloat($da->address )  ;

array_push($a,$ar);

}


  return $a;
    
}

 function ProcessUSDTDeposit($address  )
{
     
  $da = DB::table('deposit_address')->where('address',  $address)->first();
 
$bal=$this->getBalanceFloat($da->address )  ;

  
  
 //var_dump($bal);

 
if($bal>0 )
{
    
    
    
    
           

 
var_dump($da->address );

 var_dump($bal);
 
 
  // $id =  DB::table('deposit_address')
  //->where('id',$da->id)
  //     ->update([ 'status' =>  "Checked" ]);
           
             
            
     $this->checkDepositsAddress($da->address ,$da->user_id);
     
     
//     $this->checkDepositsAddress($da->address ,$da->user_id);
     
     
  $root_url = 'http://45.77.229.137/ActivateWallet/'.$da->address ;
 //echo  $root_url;
 
 // $root_url = 'http://162.255.116.244:3000/ToDepositNewFund/'.$private_key.'/'.$address;
 
 $response = @file_get_contents( $root_url);
 
 var_dump($response);
 
 
 
    
  $root_url = 'http://45.77.229.137/ForwardFund/'.$da->privateKey.'/'.$bal."000000";
 //echo  $root_url;
 
 // $root_url = 'http://162.255.116.244:3000/ToDepositNewFund/'.$private_key.'/'.$address;
 
 $response = @file_get_contents( $root_url);
 
 var_dump($response);

            
} 
else
{
    echo "no balance";
}
 


}

 function USDTbalances(  )
{
      
  $deposit_address = DB::table('deposit_address')  ->where ("coin","USDT Tron") ->orderBy('id', 'DESC')->take(10000)->get();
     
    $a=array();
    
    foreach($deposit_address as $da)
{
 

$bal=$this->getBalanceFloat($da->address )  ;

  
  

 
if($bal>0 )
{
   
var_dump($da->address );

   
var_dump($bal);

}


}


}

 function getUSDTDeposits(  )
{
     
//  $deposit_address = DB::table('deposit_address')   ->where('created_at', '>', Carbon::now()->subMinutes(120)->toDateTimeString()) ->where ("coin","USDT Tron")->get();
   
  $deposit_address = DB::table('deposit_address')   ->where ("coin","USDT Tron")->where("address","<>","TKv9cZnWAaoE7334iuHVdy3HKAGQys1iR3") ->orderBy('id', 'DESC')->take(100)->get();
    
   //$deposit_address = DB::table('deposit_address')->where('status','<>' ,   "Checked")     ->where('status',  "Credited") ->where ("coin","USDT Tron")->get();

//print_r($deposit_address);
 
    $a=array();
    
    foreach($deposit_address as $da)
{


var_dump($da->address );


$bal=$this->getBalanceFloat($da->address )  ;

  
  

var_dump($bal);

 
if($bal>0 )
{
    
    
    
    
           

 
var_dump($da->address );

 var_dump($bal);
 
 
 $id =  DB::table('deposit_address')
 ->where('id',$da->id)
  ->update([ 'status' =>  "Checked" ]);
           
             
            
     $this->checkDepositsAddress($da->address ,$da->user_id);
     
     
//     $this->checkDepositsAddress($da->address ,$da->user_id);
     
     
       $root_url = 'http://45.77.229.137/ActivateWallet/'.$da->address ;
 //echo  $root_url;
 
 // $root_url = 'http://162.255.116.244:3000/ToDepositNewFund/'.$private_key.'/'.$address;
 
 $response = @file_get_contents( $root_url);
 
 var_dump($response);
 
 
 
     
    
  $root_url = 'http://45.77.229.137/ForwardFund/'.$da->privateKey.'/'.$bal."000000";
 //echo  $root_url;
 
 // $root_url = 'http://162.255.116.244:3000/ToDepositNewFund/'.$private_key.'/'.$address;
 
 $response = @file_get_contents( $root_url);
 
 var_dump($response);

            
} 

}


}

 function checkDeposits_v2(  )
{
     
 //  $deposit_address = DB::table('deposit_address')   ->where('created_at', '>', Carbon::now()->subMinutes(200)->toDateTimeString()) ->where ("coin","USDT Tron")->get();
   
   $deposit_address = DB::table('deposit_address')  ->where ("coin","USDT Tron")->get();
    
   //$deposit_address = DB::table('deposit_address')->where('status','<>' ,   "Checked")     ->where('status',  "Credited") ->where ("coin","USDT Tron")->get();

//print_r($deposit_address);
 
    $a=array();
    
    foreach($deposit_address as $da)
{

//var_dump($da->address );


$bal=$this->getBalanceFloat($da->address )  ;

  
  
//var_dump($bal);

 
if($bal>0 )
{
    
var_dump($da->address );

var_dump($bal);
    
    
    
   $id =  DB::table('deposit_address')
  ->where('id',$da->id)
       ->update([ 'status' =>  "Completed" ]);
           
            
            
     $this->checkDepositsAddress($da->address ,$da->user_id);
     
     
   


            
} 

}


}

public function checkDepositsAddress($address ,$user_id,$contract_address="TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t"){
    
    
      
      $tran=file_get_contents("https://api.trongrid.io/v1/accounts/".$address."/transactions/trc20?limit=20&contract_address=".$contract_address);
      
       $ar=json_decode($tran);
       
     var_dump($ar);
        if(isset($ar->data[0]))
        
        {
            
            
            foreach ($ar->data as $tr)
            $this->creditTransaction($tr ,$user_id);
            
            
        }
        
       
      return $tran;
       
      
    
    
}



public function creditUSDT( $address)
{
    
    
     $deposit_wallet = DB::table('deposit_address') ->where('address',   $address)  ->first();
     
     
      // var_dump($deposit_wallet);
    
 $status=  $this->checkDepositsAddress($deposit_wallet->address ,$deposit_wallet->user_id);
  
 var_dump($status);
  
}

public function creditTransaction( $tran ,$user_id)
{
    
    
   
 //   $tron=file_get_contents("https://apilist.tronscan.org/api/transaction?sort=-timestamp&count=true&limit=20&start=0&address=".$address);
    
    
//    $tron1=json_decode($tron);
    
    
 
    
     $system_transactions=   DB::table('wallet_transactions') ->where('reference', $tran->transaction_id)->first(); 
     
     
    // print_r($system_transactions);
 
 
 
   if($system_transactions)
   
   {
 echo "duplicate";   
       return "";
     
   }
   
      $pl=new Paymentcls();
    
    $des="Credit against the transaction id ".$tran->transaction_id ." and  address ".$tran->to  ;
    
   
     $user=   DB::table('users')->where('id'   ,$user_id) ->first();
    // print_r($user);
     
   $amount=  substr_replace( $tran->value ,"",-6) ;
     
     
     $res=   $pl->credit_user( $user  ,  $amount,     "USDT"  ,$des,$tran->transaction_id );
     
     
     
     
     
       $admin=DB::table('users')->where('email', "globocoins.trade@gmail.com")->first()  ;
    
    
  $step1=  $pl->payment_transfer( $user , $admin , $amount, "USDT" , $des  ,$tran->transaction_id   );
    
    
  $step2=     $pl->payment_transfer( $admin , $user , $amount,"DBIK" , $des  ,$tran->transaction_id   );
  
  
  
  
  
  
  
    // $system_transactions= DB::table('system_transactions')->where('reference', $tran->transaction_id)->get(); 
     
 //  if($system_transactions) return "";
     
     //print_r($system_transactions);
    
    
    
}
//


public function get_tron_address(){
    
    
    $url="testurl";
    
    
    
    $ar=  array();
    
    
    $address=$this->get_address( );
    


     
     
    $ar['address']= $address  ;
    
    
    
    
    
  
return response()->json($ar );
}
 
public function get_tron_address_details(){
    $ar=  array();
    $address=$this->get_address( );
    
    // https://apilist.tronscan.org/api/transaction?sort=-timestamp&count=true&limit=20&start=0&address=TBNxdYocipiha6QJimHXVZhFwtHc5BNrwp
    // echo $address;
    
    $tron=file_get_contents("https://apilist.tronscan.org/api/transaction?sort=-timestamp&count=true&limit=20&start=0&address=".$address);
    
    
    $tron1=json_decode($tron);
    
    $ar['tron']['transactions']=$tron1->data;
    
    
    
    
    
    $AUSDT=file_get_contents("https://api.trongrid.io/v1/accounts/".$address."/transactions/trc20?limit=100&contract_address=TNB6C4uGRbtJwhrrurXCMxbyLcTF6Mcm4y");
    
      $AUSDT1=json_decode($AUSDT);
    
    $ar['AUSDT']['transactions']=$AUSDT1->data;
    
    
    
    
    
    $USDT=file_get_contents("https://api.trongrid.io/v1/accounts/".$address."/transactions/trc20?limit=100&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t");
    
      $USDT1=json_decode($USDT);
    
    $ar['USDT']['transactions']=$USDT1->data;
    
    
     
     
     
     
    
    $ar['balance']=$this->getBalance($address )  ;
   
    $ar['address']= $address  ;
    
    
    
    
    
  
return response()->json($ar );
}

 


public function getBalance($address )
{
    
   
    
      $tran=file_get_contents("https://apilist.tronscan.org/api/account?address=".$address);
      
       $ar=json_decode($tran);
       
      
       
        $balance="";
       foreach($ar->tokens as $a)
       {
           
          $balance=$balance."<br /> ".$a->tokenName." ".$a->balance;
          
          
           
       } 
       
   $balance=    str_replace("trx","Tron",$balance);
       
       
   $balance=    str_replace("YourTokenName","Alpha USDT ",$balance);
       
        return $balance;
    
}
public function getBalanceFloat($address )
{
    
   
    
      $tran=file_get_contents("https://apilist.tronscan.org/api/account?address=".$address);
      
       $ar=json_decode($tran);
       
     // print_r($ar);
       
        $balance="";
       foreach($ar->tokens as $a)
       {
           if( $a->tokenAbbr=="USDT"  )
          return  substr_replace($a->balance ,"",-6)    ;
          
          
           
       } 
      
        return $balance;
    
}

public function getBalanceFloat11($address )
{
    
   $address="TJnUmoueePUSpHafPPxYUnhrh7NtUtnK3B";
   
    
      $tran=file_get_contents("https://apilist.tronscan.org/api/account?address=".$address);
      
       $ar=json_decode($tran);
       
     // print_r($ar);
       
        $balance="";
       foreach($ar->tokens as $a)
       {
           if( $a->tokenAbbr=="USDT"  )
          return  substr_replace($a->balance ,"",-6)    ;
          
          
           
       } 
      
        return $balance;
    
}

 
public function get_tron_address_balance(){
    
    $address="TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as";
    
    
    $tran=file_get_contents("http://162.255.116.244/tronapi/balanceOf.php?address=".$address);
    
    return    $tran;
    
    /*
    $tr=json_decode($tran);
    
 
    
    
  
return response()->json($tr );

*/
}


public function get_USDTaddress( )
{ 
    
}

public function get_address( )
{
 $ar=array();
 
 
   $deposit=   "";
      $user_id= Auth::user()->id;
     
       // $user_id=1358;
     
    
     
$deposit_address = DB::table('deposit_address')->where('user_id', $user_id)->where('coin', 'Tron')->count();
    
    
   
   if( $deposit_address==0)
   {
 
 $deposit=   $this->getTronAddress();
  
   }
   
      
   
     $data=   DB::table('deposit_address')->where('user_id', $user_id)->where('coin',  'Tron')->first(); 
  
  
   $ar['address']=  $data->address;
    $ar['id']=  $data->id;
    $ar['coin']= "Tron";
   
    
    return  $data->address;
  
   
} 

 
 
 
 
 
   function getTronAddress(  )
{
     
  
 
 $user_id= Auth::user()->id;
    
      // $user_id=1358;



$root_url = 'http://45.63.85.100/generateAccount';

 
 
$response = file_get_contents( $root_url);
 
// return $response;
 
 
      
   $object = json_decode($response);
 
//$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
 $privateKey=  $object->privateKey;
  $address=$object->address->base58;
   $publicKeyy=  $object->publicKey;
  $Mnemonic=  "";//$object->Mnemonic;
  
  
  /*

$root_url = 'http://162.255.116.244/ethapi/?q=createUSTTronWallet&w='.$user_id ;

 
 
$response = file_get_contents( $root_url);

////////////////////////////////
 
       
      

 $object = json_decode($response);
 
//$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
 $privateKey=  $object->privateKey;
  $address=$object->address->base58;
   $publicKeyy= "";//$object->publicKey;
  $Mnemonic=  "";//$object->Mnemonic;
  */
DB::table('deposit_address')->insert(['coin'=>"Tron", 'user_id' => $user_id, 'address' =>$address,'url_hit' =>  $root_url ,'url_res' =>$response ,'Mnemonic'=>$Mnemonic,'publicKey'=>$publicKeyy,'privateKey'=>$privateKey]);
 
  return $address;	
}



 public function callTronAddressApi(  )
{
     
  
  

$root_url = 'http://162.255.116.244/ethapi/?q=createUSTTronWallet&w='.rand(3000,9000);

 
 
$response = file_get_contents( $root_url);

 //$object = json_decode($response);
 
 
  return $response;	
}





















   function getUSDTTronAddress(  )
{
     
  
 
 $user_id= Auth::user()->id;
    
      // $user_id=1358;


$root_url = 'http://162.255.116.244/ethapi/?q=createUSTTronWallet&w='.$user_id ;

 
 
$response = $this->file_get_contents_curl( $root_url);

 $object = json_decode($response);
 
//$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
 $privateKey=  $object->privateKey;
  $address=$object->address->base58;
   $publicKeyy= "";//$object->publicKey;
  $Mnemonic=  "";//$object->Mnemonic;
  
DB::table('deposit_address')->insert(['coin'=>"USDT Tron", 'user_id' => $user_id, 'address' =>$address,'url_hit' =>  $root_url ,'url_res' =>$response ,'Mnemonic'=>$Mnemonic,'publicKey'=>$publicKeyy,'privateKey'=>$privateKey]);
 
  return $address;	
}





















 
 function file_get_contents_curl($url) {
$ch = curl_init();

curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

$data = curl_exec($ch);
curl_close($ch);

return $data;
}
  
  
} 