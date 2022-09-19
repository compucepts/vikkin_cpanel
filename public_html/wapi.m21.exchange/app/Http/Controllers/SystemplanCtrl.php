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


use App\Http\Controllers\Robot\RobotInvestmentCtrl;

use Illuminate\Support\Facades\Cache;
class SystemplanCtrl extends Controller
{
    
    private $ip;
    private $request;
private $private_blockchain_host;
    public function __construct(Request $request)
    {
        $this->request = $request;
        
    
    }
    
  
 function   investments_info($id){
     
$exchange_investment = DB::table('exchange_investment')->where('id',$id)->first();
         
          
          
          
          
 return response()->json($exchange_investment );
 
 
 
    }
  
 function   investments_details(){
     
$exchange_investment = DB::table('exchange_investment')->where('id',$this->request['id'])->first();
         
          
 return response()->json($exchange_investment );
    }
    
    function investmentbyuser($user_id){
                 
 $data=DB::table('exchange_investment')->where('user_id',$user_id)->get();
 return response()->json($data );
    }
    
    
     
    
    
    
     function investment($id){
        //echo $id;
        
           $pl=new Paymentcls();
      $n=array();
      
    $data=DB::table('system_plan')->where('id',$id)->first()  ;
    
    /*
    
    $data = Cache::remember('system_plan',  60*60*24*10, function () {
        
        
    return DB::table('system_plan')->where('id',$id)->first()  ;
});
*/


        if(!$data) return "";
        
        
   $coin=  $data->coin;// 'DBIK';
    $amount=  $data->activation;
    $plan_name= $data->name;
    
    
     $user = Auth::user();
   $user_id= $user->id;
   
   
   
	//	$n['message']= " Investment was created with robot ID # ".$id . " and amount ". $amount." ". $coin;
		$n['message']= "License fee for the purchase of robot ID # ".$id  ;
    
    $username=  $user->name;
                 
     $reference="Robot_Purchase_".$id;
     
    $pl->convert_currency($user,$reference , "BRL");
     
     
    $pl->convert_currency($user,$reference ,"USDT");
    
    
     $user = Auth::user();
               
               
               
 $n['amount_USD']  =  $data->license_fee - $user->USDT;
 $paymentcls=new Paymentcls();
 $rate=$paymentcls->getExchangeUSDBRL( );
  $n['amount']  = 	$n['amount_USD']  *  $rate;
 	$n['amount']  =    number_format($n['amount'], 2, '.', '');   
              
              
              
               	
   if(  $data->license_fee >= $user->$coin  )
   {
       	$n['message']='Plz deposit fund to complete the investment. Your current balance is only '.$user->$coin ." ".$coin;
       		$n['type']="failed";
   return response()->json($n );
  
   }  
                 
 
         
        $res=  $pl->debit_user( $user ,  $data->license_fee,$coin ,'License fee debit for the roboot license purchase' ,$reference);
        
        if( $res) 
        {
             	$n['message']='Plz deposit fund to complete the investment. Your current balance is only '.$user->$coin ." ".$coin;
       		$n['type']="failed";
   return response()->json($n );
        }
        
        else
        {
            
            
            	$n['message']= "Purchase of robot ID # ".$id  ;
            	
            	
          $id = DB::table('exchange_investment')->insertGetId(
    [ 'user_id' => $user_id ,'coin' =>  $coin,'license_fee' =>   $data->license_fee  ,'plan' => $id ,'amount' => $amount ,'description' => 	$n['message'], 'plan_name' => $plan_name, 'Status_updated_at' =>  Carbon::now() ]
);
         
        }
        
   
		
		
	$n['type']="success";
	$n['url']="/investment/".$id;
	
	$n['user_id']=$user_id;
	
	$n['user_login']=$username;
	
	$n['investment_id']=$id;
	
	
	
	
$exchange_investment = DB::table('exchange_investment')->where('id',$id)->first();
         
         
         
	$n['investment']=$exchange_investment;
	
	
	$noti=new Notification();
	
	$noti->aistore_notification_new($n);
	
	
// 	$ic=new InvestmentCtrl($r);
// 	 $ic->AwardProfit2Refer( $id );
 
 
 
 
  
 $ric=new RobotInvestmentCtrl($this->request);
 
 $ric->parents_referred_robot_v1($user );
 
 return response()->json($n );
 
         
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function delete_plan(){
    
 $system_plan = DB::table('system_plan')->where('id',$this->request['id']);
 
       $system_plan->delete();


        $ar['message']=" Deleted successfully.";
              
              
        $ar['Error']=false;

        return response()->json($ar);
    }
    
    
    
    public function addinvestmentplan(){
       
       
   $id = DB::table('system_plan')->insertGetId(
       
    [ 'name' => $this->request['name'] ,'duration' => $this->request['duration']  ,'total_return' => $this->request['total_return'] ,'minimum' => $this->request['minimum'] ,'maximum' =>$this->request['maximum']  ,'activation_fee' =>$this->request['activation_fee'],
    'investment_amount'  =>$this->request['minimum'] ]
);
	$n['message']= "Add Plan Successfully";
	
	$n['type']="success";
	
 return response()->json($n );
 
    }
    
    
    public function edit_plan(){
        
      $plan_id=   $this->request['id'];
       $data=DB::table('system_plan')->where('id',$plan_id)->first()  ;
 return response()->json($data );
    
      
    }
      public function robot_by_id($robot_id){
        
    
       $data=DB::table('system_plan')->where('id',$robot_id)->first()  ;
 return response()->json($data );
    
      
    }
    
      public function   investplanfetch(){
         
 /*/
 
    $data = Cache::remember('system_plan',  6, function () {
        
        
    return DB::table('system_plan')->where('status', 'active') ->get()  ;
});
*/
 $data=DB::table('system_plan')->where('status', 'active')->get()   ;
 return response()->json($data );
    
  }
  
  
//       function plan_details(){
//          $user = Auth::user();
//   $user_id= $user->id;
//          $data=DB::table('system_plan')->where('id',$plan)->get()  ;
//  return response()->json($data );
//     }
    
    
  function updateinvestmentplan(){
          
      $plan_id=   $this->request['id'];
      
      
      
         $updateDetails = [
    
 'name' => $this->request['name'] ,'duration' => $this->request['duration']  ,'total_return' => $this->request['total_return'] ,'minimum' => $this->request['minimum'] ,'maximum' =>$this->request['maximum']  ,'activation_fee' =>$this->request['activation_fee'],
    'investment_amount'  =>$this->request['minimum'] 
    
    
];

DB::table('system_plan')
    ->where('id', $plan_id)
    ->update($updateDetails);
    
    $n['message']= "Update Plan Successfully";
	
	$n['type']="success";
	
 return response()->json($n );
  }
    
    
    function allbalance(){
        $ar=array();
         $currency=DB::table('currency') ->where('status','active')->get();
         
     //  var_dump($currency);
         
         
         foreach($currency as $c){
             
             
             
           $balance=  $this->fetchbalancebycoin($c->symbol);
             $user = Auth::user();
           
           
           if($balance> 0)
        array_push($ar,["coin"=>$c->currency ,"symbol"=>$c->symbol , "currency"=>$c->currency ,"balance"=>number_format( $balance, 2, '.', ''),"user"=> $user]);
        
        
             
         }
         
         

$ic=new InvestmentCtrl(   $this->request);

$balance=$ic->RobotIncome($user->id);
 



         
          array_push($ar,["coin"=>   "DBIK",     "symbol"=>  "DBIK"   , "currency"=> "Bonus" ,"balance"=>   $balance ,"user"=> $user]);

if($user->referral_DBIK > 0)
         
          array_push($ar,["coin"=>   "DBIK",     "symbol"=>  "DBIK"   , "currency"=> "Referal Income" ,"balance"=> number_format( $user->referral_DBIK, 2, '.', ''),"user"=> $user]);
        
        
        
    return $ar;
         
    }
    
    
    function fetchbalancebycoin($coin){
        
           $user = Auth::user();
          $balance=  $user->$coin;
       /*   $income=0;
          if($coin=="DBIK")
          {
          
          $ric= new RobotInvestmentCtrl(  $this->request );
          
          
          $income= $ric->robot_total_gain(  );
          
          }*/
          
          return $balance  ;
    }
    
    
    function investmentsreport(){
         $user = Auth::user();
   $user_id= $user->id;
        $data=DB::table('exchange_investment')->where('user_id',$user_id)->get()  ;
         
         
         
         $result=array();
         
         //  SELECT v.sum_amount +e.amount as sum , e.* from robo_income_view v , exchange_investment e WHERE e.id=v.investment_id ORDER BY `e`.`id` ASC;  
         //$qr="SELECT    v.sum_amount +e.amount as total_earning , e.*   from robo_income_view v , exchange_investment e WHERE e.id=v.investment_id and e.user_id =".$user_id;
         
        // $data=DB::SELECT($qr);
         foreach($data as $d)
         {
             $s=array();
             
             $s=$d;
             $s->total_earning= $s->amount+ $s->hourly_income;
           $s->days_completed=$this-> getcount(  $d->days_completed);
           
           array_push($result,$s);
         }
         
 return response()->json($result );
    }
    
    function getcount($str)
    {
      $ar= explode(",",$str);
      
      $ar=array_unique($ar);
      
      $size=sizeof($ar);
      
      return $size;
        
    }
    
      function pixdepositaddress(){
          
          
 $pix=new PixCtrlV3();
 
  
    
 $res=$pix->FetchQRcode($this->request );
 
     

 $ar=array();
 
 $ar['id']=$res['id'];

 
 //$ar['authorizationCode']=$res->authorizationCode;
 
 
 $ar['amount']=$res['amount'];
 
 
 
 
 //$ar['status']=$res->status;
 
  
 
 
 
  
 $ar['pix_address']= $res['metadata']['qrcode'];
 
  $ar['transactionId']=  $ar['id'];
 
 
  $id = DB::table('deposit_address')->insertGetId(
       
    [ 'amount' =>$res['amount'] ,'qrcode_image' => $ar['pix_address'] ,'transactionId' => $ar['transactionId']  ]
);

return   $id;

 

 return response()->json($ar);

      }
      
         function gettransaction_removed($transaction_id)
  {
      
$user_id= Auth::user()->id;
        
// $transaction_id= $request['transaction_id'];

 $token=$this->getToken();   
 
 $pix=new PixCtrlV3();
 
 
 $res=$pix->getMetaData( $token, $transaction_id);
 
 
 return $res;
// return $res->metadata->qrcode;
 
 /*
$response =json_encode($res);
 
 DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update( [ 
            'url_res' =>$response
            ]);
      
     


 
 $ar=array();
 
 $ar['id']=$res->id;
 
 
 $ar['authorizationCode']=$res->authorizationCode;
 
 
 $ar['amount']=$res->amount;
 
 
 $ar['fee']=$res->fee;
 
 $ar['status']=$res->status;
 
 
 $ar['grossAmount']=$res->grossAmount;
 
//   print_r($res);
  
 $ar['metadata']['payer_document']=$res->metadata->payer_document;
$ar['metadata']['amount']=$res->amount;
 $ar['metadata']['qrcode']=$res->metadata->qrcode;
 $ar['metadata']['brcode']=$res->metadata->payer_document;
 $ar['metadata']['payer_document']=$res->metadata->payer_document;
 
 $ar['metadata']['status']=$res->metadata->status;
  

 
 DB::table('deposit_address')
            ->where('transactionId', $transaction_id)
            ->update(['address' => $ar['metadata']['qrcode']    ,
            'status' => $res->metadata->status     ,
            'url_res' =>$response
            ]);
            
      return  $ar['metadata']['qrcode'];
     
//   return response()->json($ar);    

*/ 
  }
  
  public function getdepositreport(){
   $user = Auth::user();
   $user_id= $user->id;
   
   
 $sql=   'select deposit_address.*, users.email, concat( "https://link.pixpay.trade/", transactionid ) as paymentlink from deposit_address JOIN users ON deposit_address.user_id = users.id WHERE deposit_address.user_id='.$user_id .' ORDER BY deposit_address.id DESC limit 100';
 
 
 $sql=   'select *   from deposit_address   WHERE  user_id='.$user_id .' ORDER BY  id DESC limit 100';
 
    
  $data=DB::table('wallet_transactions')->where('user_id',$user_id)->orderBy('id', 'desc')->take(30)->get()  ;
 
 
 
  //echo $sql;

//$data= DB::select($sql);
    //  $data=      DB::table('users')
    //     ->leftJoin('deposit_address', 'users.id', '=', 'deposit_address.user_id')->select('select * ,concat("https://link.pixpay.trade/", deposit_address.transactionid ) as paymentlink from deposit_address')->orderBy('deposit_address.id', 'desc')->take(50)->get();
        
    // $data=   DB::table('deposit_address')->orderBy('id', 'desc')->take(500)->get(); 
     
      return response()->json($data );
}

  
}

 