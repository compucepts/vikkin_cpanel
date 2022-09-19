<?php


namespace App\Http\Controllers;

 
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

use App\System_Settings;

use App\Exchange_widthdraw;
use App\Exchange_bookings;
use Illuminate\Support\Facades\DB;

 use App\Bnext_Wallet;
use App\Http\Controllers\Controller;
use Mail;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

use App\Http\Controllers\Robot\RobotInvestmentCtrl;


use App\Notifications\Login;


class DashboardCtrl extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('auth');
              
    }
 
    
    function topboxes(){
        $ar=array();
         $currency=DB::table('currency') ->where('status','active')->get();
            $user = Auth::user();
     //  var_dump($currency);
         
         
         foreach($currency as $c){
             
             
             $coin=$c->symbol;
             
          // $balance=  $this->fetchbalancebycoin($c->symbol);
           $user = Auth::user();
           $balance=$user->$coin;
           
        if($balance> 0)
        array_push($ar,["title"=>$c->currency ,"coin"=>$c->currency ,"symbol"=>$c->symbol , "currency"=>$c->currency ,"balance"=>number_format( $balance, 2, '.', ''),"user"=> $user]);
        
        
             
         }
         
         

$ic=new InvestmentCtrl(   $this->request);

$balance=$ic->RobotIncome($user->id);
 



         
      array_push($ar,["title"=>   "Bonus DBIK","coin"=>   "Bonus DBIK",     "symbol"=>  "DBIK"   , "currency"=> "Bonus" ,"balance"=>  number_format( $balance, 2, '.', '')      ,"user"=> $user]);



         
          array_push($ar,["title"=>   "Referal Income DBIK", "coin"=>   "Referal Income DBIK",     "symbol"=>  "DBIK"   , "currency"=> "Referal Income" ,"balance"=> number_format( $user->referral_DBIK, 2, '.', ''),"user"=> $user]);
        
           array_push($ar,["title"=>   "Network Bonus DBIK",  "coin"=>   "Network Bonus DBIK",     "symbol"=>  "DBIK"   , "currency"=> "Network Bonus" ,"balance"=> number_format( $user->referral_bonus_dbik, 2, '.', ''),"user"=> $user]);
        
        
        $paymentcls=new Paymentcls();
        
        


 $rate=$paymentcls->getExchangeUSDBRL( );
     array_push($ar,["coin"=>   "BRL/USD",  "title"=>   "Exchange Rate",     "symbol"=>  "BRL/USD"   , "currency"=> "Exchange Rate" ,"balance"=> number_format( $rate, 2, '.', '')   ,"user"=> $user]);
        
         //  $user = $request->user();
           //     $user = Auth::user();
           
       // $ric=new RobotInvestmentCtrl(  $this->request );
        
       // $ric->parents_referred_robot($user   );
        
    return $ar;
         
    }
      
          
}