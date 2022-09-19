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
      
       
    
    
    
    
    
     
    
    
    
    
    
    
      
      
      
  public function getStatus(Request $request)
  {
 
        
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

$file="../admintransactions_v4_".__LINE__.".txt";
file_put_contents($file, $response.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);



curl_close($curl);
 
$res=json_decode($response, false, 512, JSON_BIGINT_AS_STRING );


 
 

  
 
 
 
 
   return response()->json($res);    
  }
      
      
      
      
      
      
 
   public function Deposits(Request $request)
  {
     
  if(Auth::user()->roll < 8 )
    {
       
       
      
 $deposit_address = DB::table('deposit_address') ->where('coin',"BRL") ->orderBy('id', 'desc')->get();

 
 return response()->json($deposit_address);    
}

}
  
 
 
   public function getBalance(Request $request)
  {
       

}
  
 
  public function getWithdraw(Request $request)
  {
    
    
       
  }
  
  
  
  
  
  
  public function getDeposit(Request $request)
  {
       


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

return $ar->access_token;

     }
     

}


