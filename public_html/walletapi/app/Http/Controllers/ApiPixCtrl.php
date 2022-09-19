<?php


namespace App\Http\Controllers;

use Mail; 
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
use App\Exchange_g2f;
use App\Exchange_widthdraw;
use App\Exchange_bookings;
use Illuminate\Support\Facades\DB;

  
use App\Http\Controllers\Controller;
use App\System_Deposit;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

use Redirect;
use App\Notifications\Login;

 

class ApiPixCtrl extends Controller
{
    private $request;

   
   
   
   
   
   


    public function __construct( )
    {
        
        
           
            
            
    }
  
    
     
    

    
    function Transactions(Request $request)
    {
        
         
        
        
        
 
 $qr=""; 
 
       $user_id= Auth::user()->id;
       
       
       
$document= $request['document'];

 
$a=file_get_contents("https://alphakoin.conectate.club/amazon.php?document=".$document);


         return  $a ;

     
       
       
    }
    
    
    
    
      public   function getQRCode($amount,$ref)
    {
        
        
        
$curl = curl_init();


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.profitshare.com.br/v2/pix/dynamicqrcode',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('key' => 'b7c5565b-3c33-4fee-83ea-0142baab3619','amount' => $amount,'reference' =>  $ref),
  CURLOPT_HTTPHEADER => array(
    'ApiKey: g3jBrrbKTmXSb2TQpc6DPE0DvCW2',
    'ApiSecret: A5440C11-00AD-A181-8CBA-854BEFD1C302'
  ),
));




$response = curl_exec($curl);

curl_close($curl);


$ar=json_decode($response);

//$src= $ar->instantPayment->generateImage->imageContent;


return $ar;
//return '<img src="data:image/png;base64, '.$src.'" alt="Red dot" />';
        
         

    }
    
    
    
    
      public   function getQRCode1111($amount,$ref)
    {
        
        
        
$curl = curl_init();


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.profitshare.com.br/v2/pix/dynamicqrcode',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('key' => 'b7c5565b-3c33-4fee-83ea-0142baab3619','amount' => $amount,'reference' =>  $ref),
  CURLOPT_HTTPHEADER => array(
    'ApiKey: g3jBrrbKTmXSb2TQpc6DPE0DvCW2',
    'ApiSecret: A5440C11-00AD-A181-8CBA-854BEFD1C302'
  ),
));




$response = curl_exec($curl);

curl_close($curl);


 return $response;

//$ar=json_decode($response);

//$src= $ar->instantPayment->generateImage->imageContent;


//return $ar;
//return '<img src="data:image/png;base64, '.$src.'" alt="Red dot" />';
        
         

    }
    
    
    function checkTransactions(Request $request)
    {
        
         
        
$transactionId= $request['transactionId'];
        
        
      $user_id= Auth::user()->id;
      
       
    
$deposit_address = DB::table('deposit_address')->where('user_id', $user_id)->where('transactionId', $transactionId)->first();
    
    
    
    if(!$deposit_address ) return "Incorrect Auth Token or transaction ID";
    
    
        
     //   return $transactionId;
        
        
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.profitshare.com.br/v2/pix/checkdynamicqrcode/'.$transactionId,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'ApiKey: g3jBrrbKTmXSb2TQpc6DPE0DvCW2',
    'ApiSecret: A5440C11-00AD-A181-8CBA-854BEFD1C302'
  ),
));

$res  =  curl_exec($curl);

curl_close($curl);





$response = json_decode($res);

 
 
 if(  $response->transactions[0]->transactionStatus  == "APPROVED") 
 {
     
 
     
$deposit_address = DB::table('deposit_address')->where('status',"CREATED")->where('user_id', $user_id)->where('transactionId', $transactionId)->first();
    
    if($deposit_address)
    {
 DB::table('deposit_address')
         ->where('transactionId', $transactionId)
            ->where('id', $deposit_address->id)
            ->update(['status' => $response->transactions[0]->transactionStatus   ]);
          
          
          
               
     $pl=new   Paymentcls();
     
       $user = User::find( $deposit_address->user_id);
       
  
       
       $description="Deposit by pix ".$transactionId;
       
    // return   $description;
     $pl-> credit_user( $user, $response->transactions[0]->paidAmount  , "BRL",$description);
     
   
 
 
    }
       
       
            
 
 
 }
 
  
  
   return response()->json($response);

    }
    
    function getQRCodeDynamic(Request $request)
    {
        
         
        
        
        
 
 $qr=""; 
 
       $user_id= Auth::user()->id;
        
$amount= $request['amount'];

$reference= $request['reference'];



       
  $deposit= $qr;


$id = DB::table('deposit_address')->insertGetId(
    [ 'user_id' => $user_id ,'coin' =>  "PIX" ,'address' => $deposit   ]
);

 
 
 $ar1=$this->getQRCode1111( $amount,$reference);
 
$ar1=json_decode($ar1);
 
  $deposit_address=$ar1->instantPayment->generateImage->imageContent;
 
 $ar['address'] = $deposit_address;
 
 DB::table('deposit_address')
            ->where('id', $id)
            ->update(['address' => $deposit_address,'transactionId' => $ar1->transactionId  ,'status' => $ar1->financialStatement->status     ,'url_res' =>json_encode( $ar1)  ]);
      
     
   $r=array();
   $r['status']=200;
 $r['result']=$ar; 
   
     $r['transactionId']=  $ar1->transactionId;
   
     $r['amount']=  $amount;
   
     $r['reference']= $reference;
   
//   $r['dump']=$ar1; 
      
   
   return response()->json($r);    
}








}


