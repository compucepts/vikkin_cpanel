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


class EthereumCtrl extends Controller
{
    
    private $ip;
    private $request;
private $private_blockchain_host;



    public function __construct(Request $request)
    {
        $this->request = $request;
        
     
     
    }
    
     

   function EthereumDeposit(  )
{
     
  
 
 $user_id= Auth::user()->id;
    
      // $user_id=1358;
$index=rand(1,20000);

$root_url = 'http://162.255.116.244:81/wallet/'. $index;

 
 
$response = $this->file_get_contents_curl( $root_url);

 


 $object = json_decode($response);
 
//$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
 $privateKey=   $object->PrivateKey;
  $address=$object->Address;
   $publicKeyy= $object->PublicKey;
  $Mnemonic=  $object->mnemonic; 
  
  $index=  $object->index;
  
DB::table('deposit_address')->insert(['coin'=>"ETH", 'user_id' => $user_id, 'address' =>$address,'url_hit' =>  $root_url ,'url_res' =>$response 
,'Mnemonic'=>$Mnemonic,'publicKey'=>$publicKeyy,'privateKey'=>$privateKey,'transactionId'=>$index]);

 
  
  
  
   $ar=array(); 
   $ar['address']= $address;
   
    $ar['coin']= "ETH";
   
   
  
return response()->json($ar );
   
   
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