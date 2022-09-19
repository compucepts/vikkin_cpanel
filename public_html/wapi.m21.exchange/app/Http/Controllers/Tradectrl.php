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


class Tradectrl extends Controller
{
      public function __construct(Request $request)
    {
        $this->request = $request;
    }
 
 
 
 public function posttrade(){
       $user_id= Auth::user()->id;
       $email= Auth::user()->email;
       
       
    $id = DB::table('trade')->insertGetId(
    ['trade_type' =>  $this->request['trade_type'],
    'location' =>  $this->request['location'],
      'currency' =>$this->request['currency'],
    'bank_name' =>  $this->request['bank_name'],
      'margin' => $this->request['margin'],
       'amount' =>$this->request['amount'],
    'min_transaction_limit' =>  $this->request['min_transaction_limit'],
     'max_transaction_limit' =>  $this->request['max_transaction_limit'],
          'term_tarde' =>  $this->request['term_tarde'],
          'user_id' => $user_id ]
);
 
  $ar=array();
    $ar['Error']=false; 
    $ar['message']=  "Successfully";
    
    return response()->json($ar);
    
}
 
}
 