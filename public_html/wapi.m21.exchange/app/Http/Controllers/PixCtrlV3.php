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

 

class PixCtrlV3 extends Controller
{
    private $request;

   
   
   
   
   
   


    public function __construct( )
    {
        
        
           
            
            
    }
  
  
 
      
  public function test(Request $request)
  {
      // return "";

$file="test.txt";
        
$transaction_id= $request['transaction_id'];

file_put_contents($file, $transaction_id.PHP_EOL, FILE_APPEND | LOCK_EX);

return "test";  

}
      
  public function webhook(Request $request)
  {
 

$transaction_id= $request['transaction_id'];
$status= $request['status'];
/*
 $token=$this->getToken();   

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


 var_dump($response);
 

curl_close($curl);
 
$res=json_decode($response);
 
 */

$response="";
 $deposit_address = DB::table('deposit_address')->where('transactionId', $transaction_id)->orderBy('id', 'desc')->first();
 
 var_dump( $deposit_address->user_id );
 


 //////////////////
 
   
        $amount= $deposit_address->amount;
        
$user= User::findOrFail( $deposit_address->user_id);
 
 

///////////////////////////////////////////////
 
  //var_dump($deposit_address );
  
  //var_dump($res );
  
  
  //echo __LINE__;
  
 $paid="NEW";
 
 $paid=$status;
   
 //if( $res->invoice->paid_with  <>  NULL )
 if(1==1)
  {
     // echo __LINE__;
      
 $paid=$status;
     
      if( $deposit_address->status  <> $status  and   $status ==="completed")
      
      {
          
          DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'status' =>  $status,
              'url_res' =>$response
            ]);
  
        $amount= $deposit_address->amount;
        
$user= User::findOrFail( $deposit_address->user_id);
 
 $des= 'PIX deposit    '.$transaction_id;
$pl=new Paymentcls();
     $r=   $pl->credit_user( $user  ,  $amount, "BRL"  ,$des  ,$transaction_id  );
             
             
     
             // now exchange the currency to dubai koin 
             
  //  $admin=DB::table('users')->where('email', "globocoins.trade@gmail.com")->first()  ;
    
     //  $pl->payment_transfer( $user,$admin,  $amount ,"BRL" ,$des   ,$transaction_id)  ;     
           
//$pl->payment_transfer(  $admin,  $user  ,  $amount * 0.18 ,"DBIK" ,$des  ,$transaction_id)  ;     
           

          
          
      }
      
      
      
      
  
     
 }
     // step 1
     // get transaction status
     
     // if completed but in table it was not completed then credit the payment otherwise no
     
     

 //echo __LINE__;
      
  
 
 $ar=array();
 
 $ar['id']=$transaction_id;//$res->invoice->token;
  
 
  
 
 $ar['status']= $paid;
 
 
 //$ar['grossAmount']=$res->grossAmount;
 
 
 //$ar['metadata']['payer_document']=$res->metadata->payer_document;
//$ar['metadata']=$res->metadata;

//
// $ar['metadata']['qrcode']=$res->metadata->qrcode;
// $ar['metadata']['brcode']=$res->metadata->payer_document;
// $ar['metadata']['payer_document']=$res->metadata->payer_document;
 
 
 
      if( $paid  ==  "COMPLETED")
      {
          
          
 $ar['metadata']['status']= $status; //"paid";
      }
      
 //$ar['metadata']['updated_at']=$res->metadata->updated_at;
  // $ar['metadata']['payer_name']=$res->metadata->payer_name;
  
  
  
  
// $this->forwarddata( 'https://api.inwista.ltd/receive.php',$ar);
 
 
 //$this->forwarddata($deposit_address->notification_url,$ar);
      
   return response()->json($ar);    
  }
 
 
 
      
  public function webhook2222222222222(Request $request)
  {
      

$file="../people.txt";
        
$transaction_id= $request['transaction_id'];

file_put_contents($file, $transaction_id.PHP_EOL, FILE_APPEND | LOCK_EX);
 
     // step 1
     // get transaction status
     
     // if completed but in table it was not completed then credit the payment otherwise no
     
     

 
 
 $ar=array();
 
 $ar['id']= "test";
 
 
      
      
       
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
 
 

$file="../../notification_url_05april.txt";
         

file_put_contents($file, date("Y-m-d H:i:s").$notification_url.$response.print_r($ar,true).PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

 
 

}



public function getMetaData( $token, $transaction_id)
{
  //  $transaction_id=$res->id;
         
  

$curl = curl_init();

curl_setopt_array($curl, array(
  
  CURLOPT_URL => 'https://api.onixsolucoes.com/api/v1/accounts/98ec9e5e-bff0-4074-bdde-fc3bdc7b085a/transactions/'.$transaction_id,
  
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
  CURLOPT_URL => 'http://api.onixsolucoes.com/api/v1/accounts/98ec9e5e-bff0-4074-bdde-fc3bdc7b085a/deposit',
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
      
      
      
  public function gettransaction(Request $request)
  {
 // return "";
//$user = Auth::user() ;
//$user_id= Auth::user()->id;
        
$transaction_id= $request['transaction_id'];
/*
 $token=$this->getToken();   

$curl = curl_init();

curl_setopt_array($curl, array(
  
  CURLOPT_URL => 'https://api.onixsolucoes.com/api/v1/accounts/98ec9e5e-bff0-4074-bdde-fc3bdc7b085a/transactions/'.$transaction_id,
  
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

$file="../transactions_490.txt";
         

file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);



curl_close($curl);
 
$res=json_decode($response, false, 512, JSON_BIGINT_AS_STRING );
 
 
$file="../transactions_490.txt";
         

file_put_contents($file, print_r($res,true).PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

*/
 

 DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'url_res' =>$response
            ]);
  
  


    
 $deposit_address = DB::table('deposit_address')->where('transactionId', $transaction_id)->orderBy('id', 'desc')->first();
     
     
      $amount= $deposit_address->amount;
      
      
 if( $res->status  ==  "COMPLETED" )
  {
      
      if( $deposit_address->status  <>  "COMPLETED")
      
      {
          
          DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'status' =>  "COMPLETED"
            ]);
  
        $amount= $deposit_address->amount;
        

$pl=new Paymentcls();

 $user =  User::find($deposit_address->user_id);
 
 

     $r=   $pl->credit_user( $user  ,  $amount, "BRL"  ,'PIX deposit '.$transaction_id  );
             
             
             
           
     
          
          
      }
      
      
      
      
  
     
 }
     // step 1
     // get transaction status
     
     // if completed but in table it was not completed then credit the payment otherwise no
     
     

 
 
 $ar=array();
 
 $ar['id']=$res->id;
 
 
 //$ar['authorizationCode']=$res->authorizationCode;
 
 
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
  
 
 
 DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update([
      
            'url_res' =>$response
            ]);
      
 
   return response()->json($ar);    
  }
      
      
  public function getnewtransaction(Request $request)
  {
      return "";
$user = Auth::user() ;
$user_id= Auth::user()->id;
        
$transaction_id= $request['transaction_id'];

 $token=$this->getToken();   

$curl = curl_init();

curl_setopt_array($curl, array(
  
  CURLOPT_URL => 'https://api.onixsolucoes.com/api/v1/accounts/98ec9e5e-bff0-4074-bdde-fc3bdc7b085a/transactions/'.$transaction_id,
  
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

$file="../transactions_662.txt";
         

file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);



curl_close($curl);
 
$res=json_decode($response, false, 512, JSON_BIGINT_AS_STRING );
 
 
$file="../transactions_674.txt";
         

file_put_contents($file, print_r($res,true).PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);


 

 DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'url_res' =>$response
            ]);
  
  


 $deposit_address = DB::table('deposit_address')->where('user_id',$user_id)->where('transactionId', $transaction_id)->orderBy('id', 'desc')->first();
   
 if( $res->status  ==  "COMPLETED" )
  {
      
      if( $deposit_address->status  <>  "COMPLETED")
      
      {
          
          DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'status' =>  "COMPLETED"
            ]);
  
        $amount= $deposit_address->amount;
        

$pl=new Paymentcls();
     $r=   $pl->credit_user( $user  ,  $amount, "BRL"  ,'PIX deposit '.$transaction_id  );
             
             
             
           
     
          
          
      }
      
      
      
      
  
     
 }
     // step 1
     // get transaction status
     
     // if completed but in table it was not completed then credit the payment otherwise no
     
     

 
 
 $ar=array();
 
 $ar['id']=$res->id;
 
 
 //$ar['authorizationCode']=$res->authorizationCode;
 
 
// $ar['amount']=$res->amount  ;
 
 
 //$ar['fee']=$res->fee;
 
 $ar['status']=$res->status;
 
 
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
  
 
 
 DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update([
      
            'url_res' =>$response
            ]);
      
 
   return response()->json($ar);    
  }
      
      
      
  public function gettransaction_old(Request $request)
  {
      
$user = Auth::user() ;
$user_id= Auth::user()->id;
        
$transaction_id= $request['transaction_id'];

 $token=$this->getToken();   

$curl = curl_init();

curl_setopt_array($curl, array(
  
  CURLOPT_URL => 'https://api.onixsolucoes.com/api/v1/accounts/98ec9e5e-bff0-4074-bdde-fc3bdc7b085a/transactions/'.$transaction_id,
  
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

$file="../transactions_490.txt";
         

file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);



curl_close($curl);
 
$res=json_decode($response, false, 512, JSON_BIGINT_AS_STRING );
 
 
$file="../transactions_490.txt";
         

file_put_contents($file, print_r($res,true).PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);


 

 DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'url_res' =>$response
            ]);
  
  


 $deposit_address = DB::table('deposit_address')->where('user_id',$user_id)->where('transactionId', $transaction_id)->orderBy('id', 'desc')->first();
   
 if( $res->status  ==  "COMPLETED" )
  {
      
      if( $deposit_address->status  <>  "COMPLETED")
      
      {
          
          DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'status' =>  "COMPLETED"
            ]);
  
        $amount= $deposit_address->amount;
        

$pl=new Paymentcls();
     $r=   $pl->credit_user( $user  ,  $amount, "BRL"  ,'PIX deposit '.$transaction_id  );
             
             
             
           
     
          
          
      }
      
      
      
      
  
     
 }
     // step 1
     // get transaction status
     
     // if completed but in table it was not completed then credit the payment otherwise no
     
     

 
 
 $ar=array();
 
 $ar['id']=$res->id;
 
 
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
  */
 
 
 
 $ar['metadata']['qrcode']=$res->metadata->qrcode;
 $ar['metadata']['brcode']=$res->metadata->payer_document;
 $ar['metadata']['payer_document']=$res->metadata->payer_document;
 
 $ar['metadata']['status']=$res->metadata->status;
 $ar['metadata']['updated_at']=$res->metadata->updated_at;
   $ar['metadata']['payer_name']=$res->metadata->payer_name;
  
 
   $ar['metadata']['brcode']=$res->metadata->brcode;
 DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update([
      
            'url_res' =>$response
            ]);
      
 
   return response()->json($ar);    
  }
  
  
  
 
 
   public function getTransactions(Request $request)
  {
      
       $user= Auth::user() ;
        
       $brl= $user->BRL;
         


$user_id=$user->id;

 $deposit_address = DB::table('deposit_address')->select("id","transactionId","amount","status","created_at")->where('user_id',$user_id)->where('coin',"BRL") ->orderBy('id', 'desc')->get();


 $ar=array();
 
 $ar['Error']=false;
 
 $ar['Balance']=$brl;
 
 $ar['Transactions']=$deposit_address;
 
 return response()->json($deposit_address);    


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
     // return "please contact support";
      
      
       $user= Auth::user() ;
        
       $user_id= $user->id;
        
$amount= $request['amount'];// floatval( $request['amount'] )*100;


 $amt= floatval( $request['amount'] )*100;



$pl=new Paymentcls();
 
if(!$pl->debit_user( $user ,  $request['amount']  , "BRL",'Withdraw'))
{
 
 
 
 
 
$cpf= $request['cpf'] ;

 $token=$this->getToken();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.onixsolucoes.com/api/v1/accounts/98ec9e5e-bff0-4074-bdde-fc3bdc7b085a/withdraw',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "dictKey": "'.$cpf.'",
  "dictType": "CPF",
  "amount": '.$amt.'
}
',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);


$file="../withdraw_702.txt";
         

file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);




curl_close($curl);
 
 
 $ar=array();
 
 $ar['Error']=false;
 
 $ar['Log']=json_decode($response);
 
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
      
       $user_id= Auth::user()->id;
        
$amount= $request['amount'] ;
$amt= floatval( $request['amount'])*100;

 $token=$this->getToken();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.onixsolucoes.com/api/v1/accounts/98ec9e5e-bff0-4074-bdde-fc3bdc7b085a/deposit',
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


$file="../deposit_806.txt";
         

file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);




curl_close($curl);
 

$res=json_decode($response);






 $ar=array();
 
 $ar['id']=$res->id;
 
 
 $ar['payment_link']= "https://link.pixpay.trade/".$ar['id']  ;
 
 //$ar['authorizationCode']=$res->authorizationCode;
 
 
// $ar['amount']=$res->amount;
 
 
 //$ar['fee']=$res->fee;
 
 $ar['status']=$res->status;
 
 
 //$ar['grossAmount']=$res->grossAmount;
 
 
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


 //$ar['case']=$user_id;



   return response()->json($ar);    





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
  CURLOPT_POSTFIELDS => 'client_id=c0999fb6-5db6-4c98-8f4d-b2c4cda7bcd3&client_secret=4dOa9CY3LG3UMkoQf8CgUb4RFOgY1YSf&username=44873962000120&password=abff8b7c-face-4da5-aa6b-1b41edbfed1e&grant_type=password',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl); 

$ar=json_decode($response);

var_dump($ar);

return $ar->access_token;

     }
     

}


