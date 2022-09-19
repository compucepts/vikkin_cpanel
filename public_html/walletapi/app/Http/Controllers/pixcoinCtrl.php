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

 

class pixcoinCtrl extends Controller
{
    private $request;

   
   
   
   
   
   


    public function __construct( )
    {
        
    }
  
  
   public   function getdeposit($amount,$coin)
   {
       
//   echo $amount;
//   echo $coin;
   $user= Auth::user();
  $user_id=1; //$user->id;
        
  $pc =  new PixCtrl();
  $ar1=$pc->getQRCode( $amount, $coin);
  
//   $deposit_address=$ar1->instantPayment->generateImage->imageContent;
  
   $deposit= "bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh";


$id = DB::table('deposit_address')->insertGetId(
    [ 'user_id' => $user_id ,'amount' => $amount ,'coin' => $coin ,'address' => $deposit  ,'status' => 'Complete'   ]
);

 
 
//   $id =  DB::table('deposit_address')
//             ->where('id', $id)
//             ->update(['address' => $deposit_address,'transactionId' => $ar1->transactionId  ,'status' => $ar1->financialStatement->status     ,'url_res' =>json_encode( $ar1)  ]);
      
      
//     }
    $ar=array();
    
    $ar['QR Code']= "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".$deposit;
     $ar['Deposit ID']= $id;
    
    return $ar;
    
     
}


function getdepositbyid($id){
    // echo $id;
     $data=DB::table('deposit_address')->where('id',$id)->orderBy('id', 'desc')->first()  ;
          $ar=array();
    $ar['Deposit ID']= $id;
    $ar['Status']= $data->status;
    $ar['Amount']= $data->amount;
    
    return $ar;
      
 // return response()->json($data );
}

}