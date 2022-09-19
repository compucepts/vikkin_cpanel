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


class SystemplanCtrl extends Controller
{
    
    private $ip;
    private $request;
private $private_blockchain_host;
    public function __construct(Request $request)
    {
        $this->request = $request;
        
    
    }
    
    
     function investment($id){
        //echo $id;
        
           $pl=new Paymentcls();
      
   $coin=  'APHK';
    $amount=   1;//$data->amount;
     $user = Auth::user();
   $user_id= $user->id;
                 $username=  $user->name;
                 
               
   if(  $amount > $user->$coin  )
   {
       	$n['message']='Plz deposit fund to complete the investment';
   return response()->json($n );
  
   }  
                 
 $user1=DB::table('users')->where('id',137)->first()  ;
 
        $res=  $pl->debit_user( $user ,  $amount,$coin ,'investment' );
         $res=   $pl->credit_user( $user1 ,  $amount,$coin  ,'investment'  );
         
          $id = DB::table('exchange_investment')->insertGetId(
    [ 'user_id' => $user_id ,'coin' =>  $coin  ,'plan' => $id ,'amount' => $amount ]
);
         	$n['message']= " Investment was created with  ID # ".$id . " and amount ". $amount." ". $coin;
	
	$n['type']="success";
	$n['url']="";
	
	$n['user_id']=$user_id;
	
	$n['user_login']=$username;
	
	
	$noti=new Notification();
	
	$noti->aistore_notification_new($n);
	
	
// 	$ic=new InvestmentCtrl($r);
// 	 $ic->AwardProfit2Refer( $id );
 
 return response()->json($n );
 
         
    }
    
      public function   investplanfetch(){
         
 $data=DB::table('system_plan')->get()  ;
 return response()->json($data );
    
  }
    
    
    function allbalance(){
        $ar=array();
         $currency=DB::table('currency')->where('status','active')->get();
         foreach($currency as $c){
            $c= $c->symbol;
             
             
           $balance=  $this->fetchbalancebycoin($c);
        array_push($ar,["coin"=>$c ,"balance"=> $balance]);
             
         }
    return $ar;
         
    }
    
    
    function fetchbalancebycoin($coin){
        
           $user = Auth::user();
          $balance=  $user->$coin;
          
          return $balance;
    }
    
    
    function investmentsreport(){
         $user = Auth::user();
   $user_id= $user->id;
         $data=DB::table('exchange_investment')->where('user_id',$user_id)->get()  ;
 return response()->json($data );
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

 $sql=   'select deposit_address.*, users.email, concat( "https://link.pixpay.trade/", transactionid ) as paymentlink from deposit_address JOIN users ON deposit_address.user_id = users.id ORDER BY deposit_address.id DESC limit 100';
//  echo $sql;

$data= DB::select($sql);
    //  $data=      DB::table('users')
    //     ->leftJoin('deposit_address', 'users.id', '=', 'deposit_address.user_id')->select('select * ,concat("https://link.pixpay.trade/", deposit_address.transactionid ) as paymentlink from deposit_address')->orderBy('deposit_address.id', 'desc')->take(50)->get();
        
    // $data=   DB::table('deposit_address')->orderBy('id', 'desc')->take(500)->get(); 
     
      return response()->json($data );
}

  
}

 