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

 

class PixCtrlV4 extends Controller
{
    private $request;

   
   
   
   
   
   


    public function __construct( )
    {
        
        
           
            
            
    }
  
  
 
      
  public function webhook($transaction_id)
  {
      

$curl = curl_init();

curl_setopt_array($curl, array(
  
  CURLOPT_URL => 'https://pay.richpay.io/api/invoice/check/'.$transaction_id,
  

 CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  
  
));

 


$response = curl_exec($curl);



$file="../transactions_v4_".__LINE__.".txt";
         

file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);




curl_close($curl);
 
$res=json_decode($response);
 
 
 

 DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'url_res' =>$response
            ]);
  
  


 $deposit_address = DB::table('deposit_address')->where('transactionId', $transaction_id)->orderBy('id', 'desc')->first();
   
 if( $res->status  == true )
  {
      
      if( $deposit_address->status  <>  "COMPLETED")
      
      {
          
          DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'status' =>  "COMPLETED"
            ]);
  
        $amount= $deposit_address->amount;
        
$user= User::findOrFail( $deposit_address->user_id);
 
 
$pl=new Paymentcls();
     $r=   $pl->credit_user( $user  ,  $amount, "BRL"  ,'PIX deposit '.$transaction_id  );
             
             
               
           

          
          
      }
      
      
      
      
  
     
 }
     // step 1
     // get transaction status
     
     // if completed but in table it was not completed then credit the payment otherwise no
     
     

 
 
 $ar=array();
 
 $ar['id']=$res->id;
 
 $ar['callback']=$deposit_address->notification_url;
 
 
 //$ar['authorizationCode']=$res->authorizationCode;
 
 
// $ar['amount']=$res->amount  ;
 
 
 //$ar['fee']=$res->fee;
 
 $ar['status']=$res->status;
 
 
 //$ar['grossAmount']=$res->grossAmount;
 
 
 //$ar['metadata']['payer_document']=$res->metadata->payer_document;
//$ar['metadata']=$res->metadata;

/*
 $ar['metadata']['qrcode']=$res->metadata->qrcode;
 $ar['metadata']['brcode']=$res->metadata->payer_document;
 $ar['metadata']['payer_document']=$res->metadata->payer_document;
 
 $ar['metadata']['status']=$res->metadata->status;
 $ar['metadata']['updated_at']=$res->metadata->updated_at;
   $ar['metadata']['payer_name']=$res->metadata->payer_name;
  
  
  
/*
 $ar['metadata']['qrcode']=$res->metadata->qrcode;
 $ar['metadata']['brcode']=$res->metadata->payer_document;
 $ar['metadata']['payer_document']=$res->metadata->payer_document;
 
 $ar['metadata']['status']=$res->metadata->status;
  */
 
 


      
      
       
      $this->forwarddata($deposit_address->notification_url,$ar);
     

 
      $this->forwarddata( "https://pixpay.trade/callback.php",$ar);
      
      
   return response()->json($ar);    
  }
 
 
 
      
      
 
 public function forwarddata($notification_url,$ar)
 {

  
  
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL =>$notification_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($ar),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
 
 

$file="../notification__v4_".__LINE__.".txt";
         

file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

 
 

}



public function getMetaData( $token, $transaction_id)
{
  //  $transaction_id=$res->id;
         
  

$curl = curl_init();

curl_setopt_array($curl, array(
  
  CURLOPT_URL => 'https://api.onixsolucoes.com/api/v1/accounts/d24fb6a8-a8d8-4537-8df4-738cd36398be/transactions/'.$transaction_id,
  
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token
  ),
));

$response = curl_exec($curl);

 

curl_close($curl);
 
$res=json_decode($response);


  

 
 if(!isset($res->metadata->payer_document))
 {
     

      


     sleep(1);
     return   $this->getMetaData($token,  $transaction_id);
 }
 
 else
 { 

 

 
   return  $res ; 


}


    
}

  public function FetchQRcode(Request $request)
  {
       
    
       $user_id= Auth::user()->id;
        
$amount= $request['amount']  ;


$amt=$amount  *100;

 $token=$this->getToken();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.onixsolucoes.com/api/v1/accounts/d24fb6a8-a8d8-4537-8df4-738cd36398be/deposit',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "amount": '.$amt.'
}
',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
 

curl_close($curl);
 

$res=json_decode($response);
 
 
 
$transaction_id=$res->id;
         
         
         

 $id = DB::table('deposit_address')->insertGetId(
    [ 'user_id' => $user_id ,'status' =>  "NEW" ,'coin' =>  "BRL" ,'amount' =>   $amount  ,'notification_url' =>   $request['notification_url'] ,'transactionId' => $transaction_id,'dump' => $response  ]
);




 $res=$this->getMetaData($token,  $transaction_id);
 
   


   
 $ar=array();
 
 $ar['id']=$res->id;
 
 
  
 
 $ar['amount']=$request['amount']   ;
 
 
 $ar['payment_link']= "https://link.pixpay.trade/".$ar['id']  ;
 
 
 $ar['status']=$res->status;
 
 
 //$ar['metadata']=$res->metadata;
 
 

 $ar['metadata']['qrcode']=$res->metadata->qrcode;
 $ar['metadata']['brcode']=$res->metadata->brcode;
 //$ar['metadata']['payer_document']=$res->metadata->payer_document;
 
// $ar['metadata']['status']=$res->metadata->status;
 //$ar['metadata']['updated_at']=$res->metadata->updated_at;
  // $ar['metadata']['payer_name']=$res->metadata->payer_name;
  
  
  
  
  

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://pixpay.trade/webhook.php?id='.$ar['id'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl); 
 
 

  return $ar;
      
      
      
      
      
      
      
      
      
      
    }    
      
      
  public function getQRcode(Request $request)
  {
       
  $ar=$this->FetchQRcode(  $request);

 
   return response()->json($ar); 
      
     
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
      
      
      
  public function getStatus(Request $request)
  {
 
        
$transaction_id= $request['transaction_id'];

    
  
 $deposit_address = DB::table('deposit_address')->select("id","qrcode_image","qrcode_value","transactionId","amount","status","created_at")->where('transactionId',$transaction_id)->where('coin',"BRL")  ->first();
 
 
   /*
 
 $ar=array();
 
 $ar['id']=$res->id;
  
 
 
  $ar['amount']=$amount  ;
 
 
 //$ar['fee']=$res->fee;
 
 $ar['status']=$res->status;
 
 
 $ar['payment_link']= "https://link.pixpay.trade/".$ar['id']  ;
 
 //$ar['grossAmount']=$res->grossAmount;
 
 
 //$ar['metadata']['payer_document']=$res->metadata->payer_document;  updated_at
 //$ar['metadata1']=$res->metadata;

 
 $ar['metadata']['qrcode']=$res->metadata->qrcode;
 $ar['metadata']['brcode']=$res->metadata->payer_document;
 $ar['metadata']['payer_document']=$res->metadata->payer_document;
 
 $ar['metadata']['status']=$res->metadata->status;
 $ar['metadata']['updated_at']=$res->metadata->updated_at;
   $ar['metadata']['payer_name']=$res->metadata->payer_name;
   $ar['metadata']['brcode']=$res->metadata->brcode;
  
 */
 
 
 
   return response()->json($deposit_address);    
  }
      
      
      
      
      
      
 
   public function Deposits(Request $request)
  {
      
       $user= Auth::user() ;
        
       $brl= $user->BRL;
         


$user_id=$user->id;

 $deposit_address = DB::table('deposit_address')->select("id","transactionId","amount","status","created_at")->where('user_id',$user_id)->where('coin',"BRL") ->orderBy('id', 'desc')->take(100)->get();


 $ar=array();
 
 $ar['Error']=false;
 
 $ar['Balance']=$brl;
 
 $ar['Transactions']=$deposit_address;
 
 return response()->json($ar);    


}
  
 
 
   public function getBalance(Request $request)
  {
      
       $user= Auth::user() ;
        
       $brl= $user->BRL;
         

$user_id=$user->id;


 $ar=array();
 
 $ar['Error']=false;
 
 $ar['Balance']=$brl;
 
 return response()->json($ar);    


}
  
 
  public function getWithdraw(Request $request)
  {

      
      
       $user= Auth::user() ;
        
       $user_id= $user->id;
        
$amount= $request['amount'];// floatval( $request['amount'] )*100;

$pix_key= $request['pix_key'] ;

 $amt= floatval( $request['amount'] )*100;


 $trid = DB::table('system_widthdraw')->insertGetId(    [ 'user_id' => $user_id ,'coin' => "BRL"    ,'amount' => $amount  ,'widthdraw_address' => $pix_key    ]);

 


$pl=new Paymentcls();
 
if(!$pl->debit_user( $user ,  $amount  , "BRL",'Withdraw '. $trid ))
{
 
 
 
 

 $token=$this->getToken();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://pay.richpay.io
/api/withdrawal/pix',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "api_key": "acded95a-eb3d-fd5d-f764-8e9a08088ff9",
  "pix_key": "'.$pix_key.'",
  "value": '.$amt.'
}
',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token,
    'Content-Type: application/json'
  ),
));





$response = curl_exec($curl);




  
          DB::table('system_widthdraw')
            ->where('id',  $trid )
            ->update( [ 'status' =>"Success",
            'widthdraw_transaction_dump' =>  $response
            ]);
            
            
            
            
$file="../withdraw_v4_".__LINE__.".txt";
         

file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);




curl_close($curl);
 
 
 $ar=array();
 
 $ar['Error']=false;
 
 
 $ar['withdraw_id']=$trid;
  
 $ar['Message']="Request submitted.";
 
 return response()->json($ar);    
 
 
 


}
else
{
    

 $ar=array();
 
 $ar['Error']="true";
 
 $ar['Message']="Insufficient Balance";
 
 return response()->json($ar);    
 
}
 

  }
  
  
  
  
  
  
  public function getDeposit(Request $request)
  {
  /*    
       $user_id= Auth::user()->id;
        
$amount= $request['amount'] ;
$amt= floatval( $request['amount'])*100;

 $token=$this->getToken();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.onixsolucoes.com/api/v1/accounts/d24fb6a8-a8d8-4537-8df4-738cd36398be/deposit',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "amount": '.$amt.'
}
',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);


$file="../deposit_v4_".__LINE__.".txt";
         

file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);




curl_close($curl);
 

$res=json_decode($response);






 $ar=array();
 
 $ar['id']=$res->id;
 
 
 $ar['payment_link']= "https://link.pixpay.trade/".$ar['id']  ;
 


 $ar['status']=$res->status;
 
 
 
 
 
 $id = DB::table('deposit_address')->insertGetId(
    [ 'user_id' => $user_id ,'status' =>  "NEW" ,'coin' =>  "BRL" ,'amount' =>    $amount ,'notification_url' =>   $request['notification_url'] ,'transactionId' =>  $ar['id'],'dump' => $response  ]
);


  

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://pixpay.trade/webhook.php?id='.$ar['id'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl); 

 return response()->json($ar);    


*/


  }




    
     public function getToken()
     {
         
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://oauth2.onixsolucoes.com/auth/realms/digital-banking/protocol/openid-connect/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'client_id=bacd2a73-0813-4a8e-a5c2-f01e20cc134c&client_secret=60oldS5jEPk3lOLHIPVIxzvRxHM8f2sI&username=30098174000108&password=b0d88d2c-aeeb-44aa-a25b-730bb637ab0d&grant_type=password',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl); 

$ar=json_decode($response);

return $ar->access_token;

     }
     

}


