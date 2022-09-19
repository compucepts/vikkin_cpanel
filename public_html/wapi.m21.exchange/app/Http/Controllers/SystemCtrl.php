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


class SystemCtrl extends Controller
{
    
    private $ip;
    private $request;
private $private_blockchain_host;
    public function __construct(Request $request)
    {
        $this->request = $request;
        
    
            
            // $this->private_blockchain_host="http://167.99.255.217/btcrpc/";
            
        //       $this->private_blockchain_host="http://64.227.28.52/btcrpc/";
        // $this->ip="http://64.227.28.52";  http://162.255.116.244/btcrpc/?q=createwallet&w=
        //       http://  private_blockchain_host
               
    $this->private_ethereum_host="http://64.227.28.52:8080/";
                
    $this->private_blockchain_host="http://37.120.222.251/btcrpc/";
    $this->ip="http://159.65.220.60";
    
    
    
        $this->private_blockchain_host="http://162.255.116.244/btcrpc_0507/";
       
      
    $this->private_blockchain_host="http://149.28.241.163/btcrpc/";  
       
    }
    
    
    
    
    
      public function add_ticket()
{
 $user=Auth::user();
 
 
$user_id= $user->id;
   $username=$user->username;
   
   
    $id = DB::table('ticket')->insertGetId(
    ['category' =>  $this->request['category'],
    'title' =>  $this->request['title'],
    
       'user_id' =>$user_id,
       'username' =>$username,
       
    'details' =>  $this->request['details']]
);
 
 
  
 
   
  $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  " successfully";
    
    return response()->json($ar);
    
}
  
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
  public function transactionin5minutes(){
$sql='select transactionid from  deposit_address WHERE cttimestamp <= NOW() - INTERVAL 5 MINUTE ORDER BY id DESC limit 5';

$data= DB::select($sql);
$data1= json_encode($data);

$ar=array();
foreach($data as $d){

$data1 =$this->transactionbyid($d->transactionid);

array_push($ar,$data1);
}
 return response()->json($ar);

  }
  
 function transactionbyid($transaction_id){
     return $transaction_id;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.pixpay.trade/api/v4/Status/?transaction_id'.$transaction_id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNWNiNDgwYWFlNGMxNTQ1MTRkMWE4ZjI3MjJiYmE3ODJhOGY4YjNjNjAwYmZiODMxZTUwM2RkNDVmZDY3NzZhOGFhY2FlYTJlNmQyNDdjZWUiLCJpYXQiOjE2NDQ3NzU5NzcsIm5iZiI6MTY0NDc3NTk3NywiZXhwIjoxNjc2MzExOTc3LCJzdWIiOiIyMDAiLCJzY29wZXMiOltdfQ.lSzvUFJ58HX9GxSYDp86jGUyLUMYKBwc_yhx6xcdmbnmPgH_Y3TWO-CE1bi3ZX2ikGP6JhEzSaL2x2Lfd8v8RwInGqW6IWndC0eLysw3ISc_Id-rz0gOWrlsO39AdNtI2WmsUiFX_KCvncFime4dN5Xf4MEP19roZhZDWaezL0RKyNpgJDt8jy6znLLsN_fTz86hdhWbjFZz1DfDYdsfvgCqaf6YIfFIfR1-4tUB7kRq92ZTeWNdgLsZ3X3JNhItLfYuIxxjge6ZT6hkRyTazJVwE0VneReh9dXaJFG-9VzvI-LZGCM9Zfok-pm-sdiyxoTuAfPQ1h9arYsckK6S-kpYWZjAa6tVGR6Hc8_TEiGUsRCWQgIsKV9bvq_CmrHVmNALVANuTl6tuXY50JBfIkwYQvgrspXytNsj422pYeu099k4e_iEMtWNoiGCzkCbTZ6Axgr1PAaGdEJLbElRaoqgCEz6jXdnaec5zx64cQtLLQmz6oj0OrLKwHV-k2VvkGumcoRH8blGzkONj7jfT4WRKBsc0a3IqAwT_MwZ3grVejyvIgsbfChKfmw9ZBKIpsz6IvslHU0VEAGMptKIINK8imARZUYF2eSerleeB_V2rfRp4I2olLYJIrc_YSb32pm2ACWPCskoFJiwiW12hLX_d08A9ureUWTi8753InM'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
 } 
  
  
  
  
  
  
 /* new v2 calls start  */
  public function datefetch1(){
      $ar =array();
            $date = [date("Y-m-d h-i-s")];
for($i = 1; $i < 5; $i++) {
        $ar1 =array();
    $date[] = date("Y-m-d",strtotime("-$i day"));
  $ar1['date']=$date; 
//For cli output you'll need:
// $v= implode("\n", $date) . "\n";


}
array_push($ar,$ar1);
 return ($ar);
 
//For web output you'll need:
// echo implode("<br />", $date) . "<br />";

    }
    
    
 public function LedgetUsersEmails()
{ 
     if(Auth::user()->roll  <8   )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
    
 $data=DB::table('users')->orderBy('id', 'desc')->get()  ;
 return response()->json($data );
    
}
    
    public function alldatetransaction111(){
        if(Auth::user()->roll < 9 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
         $date= $this->request['getdate'];
         
         if($data=="all")
 $data=DB::table('system_transactions') ->orderBy('id', 'desc')->get()  ;
 else
 
 $data=DB::table('system_transactions')->where('created_at', $date)->orderBy('id', 'desc')->get()  ;
 
 
 $data=DB::table('system_transactions') ->orderBy('id', 'desc')->get()  ;
 
 
  return response()->json($data );
    }
    
    
    
    
    
    
 function exchangeform(){

     $user_id= Auth::user()->id;
     //  $email= Auth::user()->email;
       
       
    $id = DB::table('exchange_bookings')->insertGetId(
    ['c1' =>  $this->request['coin1'],
    'c2' =>  $this->request['coin2'],
      'c1_value' =>$this->request['amount1'],
    'c2_value' =>  $this->request['amount2'],
         'quantity' =>1,
    'amount' =>  $this->request['amount1'],
    'rate' => 1,
    'pair' =>  $this->request['coin1'].$this->request['coin2'],
     'type' =>  $this->request['type'],
     'order_type' =>  $this->request['order_type'],
    //  'email' => $email ,
          'user_id' => $user_id ]
);
 
  $ar=array();
    $ar['Error']=false; 
    
      $ar['swap']=  $id;

    $ar['Message']=  "Successfully";
    
    return response()->json($ar);
 }
 
 public function getorderlistbycoin($c1,$c2)
{ 
     $user_id= Auth::user()->id;
         $user_roll= Auth::user()->roll;

 $data=DB::table('exchange_bookings')->where('user_id',$user_id)->where('c1',$c1)->where('c2',$c2)->orderBy('id', 'desc')->get()  ;

    
  return response()->json($data );
    
}

  function currencylist(){
        $data=DB::table('currency')->where('status','active')->orderBy('id', 'desc')->get()  ;

    
  return response()->json($data );
    }
    
  
  
 
  function Notification_test(  )
  
  {
 
	$n=array();
	$n['message']="test notification msg";
	
	$n['type']="success";
	$n['url']="localhost";
	
	$n['user_login']=  "email";
	
	$noti=new Notification();
    $noti->aistore_notification_new($n  );
     
     
}  


public function dubaikoindepositaddress(){
     $ar=  array();
      $user_id= Auth::user()->id;
    // $address=$this->getdubaikoinAddress( );
     $bn1= $this->generateAddressCustomCoin($user_id);
    $ar['address']= $bn1  ;
return response()->json($ar );
}



public  function getexchangeRateFiatJSON($coin ){
    
    $ar=$this->getexchangeRateFiat( $coin);
    
 return  response()->json($ar);
    
}
public  function getexchangeRateFiat($coin ){
    
    
    $rateUSD='    {
    "USDT":1,
    "USD":1,
    "DBKN":1,
    "BTC":0.00003333333,
      "BRL": 5.059804
  }';
  
  
    $rateBTC='    {
    "USDT":30000,
    "USD":30000,
    "DBKN":30000,
    "BTC":1,
      "BRL": 146500
  }';
  
  
    $rateBRL='    {
    "USDT":0.20,
    "USD":0.20,
    "DBKN":0.20,
    "BTC":0.0000068,
      "BRL": 1
  }';
  
  
  if($coin=="BTC")
     $ar=json_decode($rateBTC);
    
 else if($coin=="BRL")
     $ar=json_decode($rateBRL);
    
    else
    
     $ar=json_decode($rateUSD);
    
    
 return $ar;
    
}



public  function newRate(){
    
    
    
   
   $amount1= $this->request['amount1'];
 
  $coin1=  $this->request['coin1'];
 $coin2=   $this->request['coin2'];
    
    
  
 
 
    
    
         $ar=$this->getexchangeRateFiat($coin1 );
         $rate=$ar->$coin2;
         
         $amount2=$amount1*$rate;
         
         
       
    
    	$n=array(); 	
	$n['amount1']=   $amount1;
	
		$n['amount2']=   $amount2;
		
		
			$n['coin1']=   $coin1;
				$n['coin2']=   $coin2;
	
	$n['rate']=$rate;
	
     return $n;	
}


public  function currencyexchange(){
    
    	$n=array();
     $user = Auth::user() ;
    $user_id= $user ->id;
    
   $amount1= $this->request['amount1'];
  $amount2=  $this->request['amount2'];
  $coin1=  $this->request['coin1'];
 $coin2=   $this->request['coin2'];
 
 
   if(   (double) $amount1 < 0)
    {
      
     
	$n['message']="Incorrect request";
	
	 
	
	
     return $n;	
      
    }
       
       
       
 
 if($coin1=="DBKN") $coin1="DBIK";
 if($coin2=="DBKN") $coin2="DBIK";
  
     $old_balance= $user->$coin1;
     
    $new_balance=   (double)$old_balance- (double)$amount1;
    
     
    if(   (double) $new_balance < 0)
    {
        
        	$n=array();
	$n['message']="No balance for the Currency Exchange request";
	
	$n['amount1']=   $amount1;
	
	 
	$n['balance']=   $old_balance;
	
	$n['new_balance']=   $new_balance;
	
		
			$n['coin1']=   $coin1;
				$n['coin2']=   $coin2;
	
	
     return $n;	
    }
    
    
    
     
         $pl=new Paymentcls();
         
         // get amount2 
         $ar=$this->getexchangeRateFiat($coin1 );
         $rate=$ar->$coin2;
         
         $amount2=$amount1*$rate;
         
        
    $id = DB::table('exchange_bookings')->insertGetId(
    ['c1' =>  $coin1,
    'c2' => $coin2,
      'c1_value' =>$amount1,
    'c2_value' => $amount2,
     'pair' =>$coin1.$coin2, 
     
     'amount' =>$amount1, 
     'quantity' =>$amount1, 
     
     
     'rate' =>$rate, 
     'order_type' =>"market", 
     
     
          'user_id' => $user_id ]
);
 
 
      
         
 $user=DB::table('users')->where('id',$user_id)->first()  ;
    $admin=DB::table('users')->where('email', "globocoins.trade@gmail.com")->first()  ;
    
    
  $step1=  $pl->payment_transfer( $user , $admin , $amount1 , $coin1 , 'Exchange Order '.$id ." of ".$amount1 ." ". $coin1  ." @ ".$rate  ,$id   );
    
    
  $step2=     $pl->payment_transfer( $admin , $user , $amount2 , $coin2 , 'Exchange Order '.$id ." of ".$amount1 ." ". $coin1  ." @ ".$rate  ,$id   );
    
    
    if(!$step1 and !$step2)
{
DB::table('exchange_bookings')
    ->where('id', $id)
    ->update(["status"  => "Completed"]);
 
}   
    	$n=array();
	$n['message']="Currency Exchange Succesfully";
	
	$n['amount1']=   $amount1;
	
		$n['amount2']=   $amount2;
		
		
			$n['coin1']=   $coin1;
				$n['coin2']=   $coin2;
	
	
     return $n;	
}


 function getdubaikoinAddress()
{
    
    
    
 $user_id= Auth::user()->id;
    
      // $user_id=1358;
$root_url = 'http://162.255.116.244/ethapi/?q=createUSTTronWallet&w='.$user_id ;
$response = $this->file_get_contents_curl( $root_url);
$object = json_decode($response);
//$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
//  $privateKey=  $object->privateKey;
//   $address=$object->address->base58;
   $publicKeyy= "";//$object->publicKey;
  $Mnemonic=  "";//$object->Mnemonic;
  $privateKey= "";
DB::table('deposit_address')->insert(['coin'=>"Duabikoin", 'user_id' => $user_id, 'address' =>$address,
'url_hit' =>  $root_url ,'url_res' =>$response ,'Mnemonic'=>$Mnemonic,'publicKey'=>$publicKeyy,'privateKey'=>$privateKey]);
 
  return $address;	
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
  

public function depositapprove(){
    $depositid = $this->request['depositid'];
     $accountNumber = $this->request['accountNumber'];
      $accountBranch = $this->request['accountBranch'];
     $bankName = $this->request['bankName'];
      $bankNumber = $this->request['bankNumber'];
     $document = $this->request['document'];
      $name = $this->request['name'];
 
     
          $user= Auth::user();  
 $data=DB::table('deposit_address')->where('id',$depositid)->first()  ;
    $pl=new Paymentcls();
      
   $coin=  $data->coin;
    $amount=   $data->amount;
      $user_id=  $data->user_id;
              
 $user1=DB::table('users')->where('id',$user_id)->first()  ;
        $res=  $pl->debit_user( $user ,  $amount,$coin ,'deposit' );
         $res=   $pl->credit_user( $user1 ,  $amount,$coin  ,'deposit'  );
         
      $id =  DB::table('deposit_address')
            ->where('id',$depositid)
            ->update(['accountNumber' =>$accountNumber,'accountBranch' =>$accountBranch,'bankName' =>$bankName,'bankNumber' =>$bankNumber, 'document' =>$document
            , 'name' =>$name,'status' =>  "Approved" ]);
 
 
  $ar=array();
    $ar['Error']=false; 
    $ar['Message']=  "Approved Successfully";
    
    return response()->json($ar);
}

public function user_details(){
    
 $data=DB::table('users')->where('id',$this->request['id'])->orwhere('email',$this->request['id'])->first()  ;
 
return response()->json($data );


}

    public function datefetch(){
      $ar =array();
            $date = [date("Y-m-d ")];
for($i = 1; $i < 5; $i++) {
        $ar1 =array();
    $date[] = date("Y-m-d",strtotime("-$i day"));
  $ar1['date']=$date; 
//For cli output you'll need:
// $v= implode("\n", $date) . "\n";


}
array_push($ar,$ar1);
 return ($ar);
 
//For web output you'll need:
// echo implode("<br />", $date) . "<br />";

    }
    


public function users_notificationbyid(){
$user_id = $this->request['id'];
 $data = DB::table('notification')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}


function send_fund_email_otp2(){
    
       $user= Auth::user();
       
        $user_email= Auth::user()->email;
        $otp= mt_rand(1000,9999);
      $user->otp=   $otp; 
    
    $user->save();
       
                $data = array( 'content'=>$otp.' is the otp for the send fund in the wallet','subject'=> $otp.' is the otp for the send fund  in the wallet'  );
         
        Mail::send('mail', $data, function($m) {
          
          
               $user_email= Auth::user()->email;
            $m->to($user_email, $user_email )->subject
            ('The otp for the send fund  in the wallet');
            
            
          
           $m->getHeaders()->addTextHeader('isTransactional', true);
      
         

      });
       $user->save();
       
      $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Email Send Successfully";
    
    return response()->json($ar);
}


public function login_history1(){
$user_id = Auth::user()->id;
 $data = DB::table('login_history')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}

public function users_loginhistorybyid(){
$user_id = $this->request['id'];$user_id = $this->request['id'];
 $data = DB::table('login_history')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}

public function users_transactionbyid(){
$user_id = $this->request['id'];
 $data = DB::table('wallet_transactions')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}

public function users_withdrawbyid(){
$user_id = $this->request['id'];
 $data = DB::table('wallet_transactions')->where('type', 'debit')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}

public function users_depositbyid(){
$user_id = $this->request['id'];
 $data = DB::table('wallet_transactions')->where('type', 'credit')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}


    public function alldatetransaction(Request $request){
        if(Auth::user()->roll < 9 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
         $date= $this->request['getdate'];
 $data=DB::table('system_transactions') ->orderBy('id', 'desc')->get()  ;
  return response()->json($data );
    }
    
    
    
function send_fund_email_otp(){
    
       $user= Auth::user();
       
        $user_email= Auth::user()->email;
        $otp= mt_rand(1000,9999);
      $user->otp=   $otp; 
    
    
       
                $data = array( 'content'=>$otp.' is the otp for the send fund in the wallet','subject'=> $otp.' is the otp for the send fund  in the wallet'  );
         
        Mail::send('mail', $data, function($m) {
          
          
               $user_email= Auth::user()->email;
            $m->to($user_email, $user_email )->subject
            ('The otp for the send fund  in the wallet');
            
            
          
           $m->getHeaders()->addTextHeader('isTransactional', true);
      
         

      });
       $user->save();
       
      $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Email Send Successfully";
    
    return response()->json($ar);
}

function Balance_v2(  )
    {
        
        
        
        
        $user= Auth::user();
        
     $user_id=     $user->id;
        
        /*
          $bn = Bnext_Wallet::where('user_id',$user_id)->get();
           
         
              $bal=0;
          foreach ($bn as $f) {
  
    
  $b=$this->BalancebygivenID( $f->wallet_name  );
  
   
    
 $bal=$bal+$b;
 
 
}

*/
$ar=array();


 
    
           
//$e=  number_format(  $bal,8);

$ar['custom_coin_balance']=$user->BRL;

  
$ar['brl_balance']=  $user->BRL;
 
 
$ar['btc_balance']=  $user->BTC;
 
$ar['balance']=  $user->BRL;
 
 
  
    
    
   return json_encode($ar);       
   
          
        
 
    }
    
    
 
 
      public function getExchangeRate(Request $r)
{ 
    $rt=file_get_contents("https://api-pub.bitfinex.com/v2/ticker/tBTCUSD");

$r1=json_decode( $rt) ;

 
 $rate=$r1[0];


 $ar=array();
    $ar['Error']=false; 
    
      $ar['btc_rate']=  $rate;
      $ar['brl_rate']=  1;

    $ar['Message']=  "Successfully";
    
    return response()->json($ar);
    
}    

 /* new v2 calls  end */
 
 
       public function getuserinfo(Request $r)
{ 
    
   $id =$r->input('id');
    $user =  User::find($id);
  
 
      
  return response()->json($user );
    
}    




 
public function swapcoin()
{
 
     $user_id= Auth::user()->id;
       $email= Auth::user()->email;
       
       
    $id = DB::table('swap_coin')->insertGetId(
    ['coin1' =>  $this->request['coin1'],
    'coin2' =>  $this->request['coin2'],
      'amount1' =>$this->request['amount1'],
    'amount2' =>  $this->request['amount2'],
      'email' => $email ,
          'user_id' => $user_id ]
);
 
  $ar=array();
    $ar['Error']=false; 
    
      $ar['swap']=  $id;

    $ar['Message']=  "Successfully";
    
    return response()->json($ar);
    
}


 public function ticket_list(Request $request)
{ 
    $roll=  Auth::user()->roll;
     
    $user_id= Auth::user()->id;
    
    if($roll>8 ){
        

 $data=DB::table('ticket')->orderBy('id', 'desc')->get()  ;
    }
    else
    {
        
 $data=DB::table('ticket')->where('user_id',$user_id)->orderBy('id', 'desc')->get()  ;
        
    }
  return response()->json($data );
    
}
public function searchaddress(){
   $address= $this->request['address'];
         $ar=array();
  
    $data=DB::table('private_bnext_wallet')->where('address',$address)->orderBy('id', 'desc')->first()  ;
   // print_r($data);
     $wallet_name= $data->wallet_name;
    

  $balance= $this->walletbalance( $wallet_name );
  
 $ar=array();
  $ar['address']=$address; 
    $ar['wallet_name']=$wallet_name; 
        $ar['user_id']=$data->user_id; 
    $ar['balance']=  $balance;
    
    return response()->json($ar);
    
}


public function login_history(Request $request)
{ 
     $user_id= Auth::user()->id;
 
 
 $data=DB::table('login_history') ->where('user_id',$user_id)->orderBy('id', 'desc')->take(3)->get()  ;


  return response()->json($data );
    
}
  
  
  public function system_notification(Request $request)
{  
    
    $user_id= Auth::user()->id;
   
 $data=DB::table('system_notification')->where('user_id',$user_id) ->orderBy('id', 'desc')->get()  ;
 
  
     
  return response()->json($data );
    
}
  
public function addticket_message()
{
  
    $user_id= Auth::user()->id;
   $user_email= Auth::user()->email;
    $user_name= Auth::user()->username;
   
    $id = DB::table('ticket_discuss')->insertGetId(
        [ 'ticket_id' => $this->request['ticket_id'] ,
     'message' => $this->request['message'] , 
     'username' =>    $user_name,
     'user_id' =>$user_id ]
);


 /*
  $data = array( 'content'=>'Ticket created successfully with Ticket ID  '.$id,'subject'=>  ' Ticket created successfully'  );
           
          
      Mail::send('mail', $data, function($m) {
          
          
         
            
           
            
         $m->to($this->request->email, $this->request->email )->subject
            ('Ticket created successfully');
            
            
         $m->from('support@btvcash.com','btvcash.com');
         
         
      });
      

*/

 $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  " successfully";
    
    return response()->json($ar);
    
}


 public function getticket_message($ticket_id)
{ 
    
    
    //$ticket_id=$this->request->ticket_id;
    
    
    
    $user_id= Auth::user()->id;
    
    
    
 $data=DB::table('ticket_discuss') 
 ->where('ticket_id',$ticket_id)->orderBy('id', 'desc')->get()  ;
 
 
      
  return response()->json($data );
    
}

public function alldepositaddress(){
        
  
    
    
    $user_id= Auth::user()->id;
     
    
// $data=DB::table('wallet_transactions')->select("*", DB::raw("CONCAT(  'https://link.vikkin.ltd/',transactionId) as qrcode_url"))->where('user_id',$user_id)->orderBy('id', 'desc')->take(30)->get()  ;
   
  $data=DB::table('wallet_transactions')->where('user_id',$user_id)->orderBy('id', 'desc')->take(30)->get()  ;
 
 
      
  return response()->json($data );
}

 public function ticket_by_id($ticket_id)
{ 
    
    
    //$ticket_id=$this->request->ticket_id;
    //$user_id= Auth::user()->id;
 $data=DB::table('ticket')->where('id',$ticket_id)->orderBy('id', 'asc')->first()  ;
      
  return response()->json($data );
    
}





 
 
public function getlogo()
{  

      $ss  =System_Settings::first();
      
  return response()->json($ss );
    
} 


public function dashboard()
{  

      $ss  =System_Settings::select('exchange_rate','coin_name1','coin_name2','coin_name3','coin2_deposit_address')->first();
      
  return response()->json($ss );
    
} 




public function getRateHistory()
{
     
    
 $ar = DB::table('erate')->orderBy('time')->get();
 
 
                
     return $ar ;
    
   
} 
public function getRateHistoryWithTime()
{
      $ar=array();
     $t=time();
      for($i=0;$i<120;$i++)
 {
     $dt=array();
      $t=$t+ 3600;
   $y=date('Y',$t );
   
     $y=date('Y',$t );
       $m=date('m',$t );
         $d=date('d',$t );
         
           
   $a =array();
   
   $a['time'] =$t;// date('Y-m-d H:i:s',  $t) ;
 
   
   
 $a['value']= rand(4,9);
   
   
 
 
  array_push($ar,$a);
 }
                
     return $ar ;
    
   
} 

public function getCoinDT()
{
    
  $ar=array();
    $ar['Error']=false; 
    
    $ar['name'] =  "BTC4"; 
    
      $ar['rate'] =  "6.27"; 
    
    return $ar ;
    
   
} 
public function getCoin()
{
    
 // $ar= $this->getCoinDT(); 
        $ar  = System_Settings::first();
    
    
    // return response()->json($ss);
    return response()->json($ar);
    
   
} 

public function getCoin22()
{
    
  $ar=array();
    $ar['Error']=false; 
    
    $ar['result']['networkfee_regular']=  "10";
    $ar['result']['networkfee_priority']=  "100";
    
    return response()->json($ar);
    
   
} 


public function networkfee()
{
    
  $ar=array();
    $ar['Error']=false; 
    
    $ar['result']['networkfee_regular']=  "10";
    $ar['result']['networkfee_priority']=  "100";
    
    return response()->json($ar);
    
   
} 

  public function getrates( )
{
    
    $ss  = System_Settings::first();
    
    $ar=  array();
    
    $ar['custom_coin']=$ss->exchange_rate;
    
    
    
        $f=file_get_contents("https://api.bitfinex.com/v1/pubticker/btcusd");
          $s=json_decode($f);
         
          $rt=$s->bid;
         
        
          
  
   $ar['btc_rate']=sprintf('%0.2f',$rt) ; 
   
  $ar['btc_custom_coin']=$rt  /   $ss->exchange_rate;
    
 
    
     return response()->json($ar);
    
   
    
}

  public function getsettings( )
{
        $ar=  array();
    $ss  = System_Settings::first();
 
      $ar['res']=$ss;
      
     return response()->json($ar);
    
   
    
}
  public function setsettings(Request $request)
{
     if(Auth::user()->roll < 9 )
        {
  
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        $user = Auth::user();
  
  
    
       $ar=array();
   
   
    
    
    
        $ar['Error']=true;
         
    $ss  = System_Settings::first();
    
    
     
 $ss->exchange_rate=$request->exchange_rate;
    
     
//$ss->coin_short_name=$request->coin_short_name;
     $ss->coin_name1=$request->coin_name1;
    $ss->coin_name2=$request->coin_name2;  
 $ss->coin_name3=$request->coin_name3;
 $ss->coin2_deposit_address=$request->coin2_deposit_address;
 $ss->withdraw_charge=$request->withdraw_charge;
    $ss->save();
    
  //  DB::table('erate')->where('time',  100)->delete();
    
    DB::table('erate')
    ->updateOrInsert(
        ['time' =>  date("Y-m-d") , 'pair' =>$request->coin_short_name. 'USD'],
        ['value' =>$request->exchange_rate]
    );
    
    
       $ar['message']="Successfully updated  ";
      
         $ar['Error']=false;
             
 return response()->json($ar);
    
 

}

  public function postlogo(Request $request)
{ 
    $user = Auth::user();
       $ar=array();
        $user1=$user->id;
    if($user1 < 9 )
    {
       
        $ar['Error']=false;
       $ar['message']="Unable to upload";   
        
 return response()->json($ar);
    exit();
    }
    
    
    
        $ar['Error']=true;
         
    $ss  = System_Settings::first();
    
    
    
 $ss->logo=$request->document;
 
    $ss->save();
    
    $ar['Settings']=$ss;
       $ar['message']="Successfully updated  ";
      
         $ar['Error']=false;
             
 return response()->json($ar);
    
 

}
     function UnconfirmedTransactions(  )
    {
        
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://167.99.255.217:8080/blockchain/transactions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);



return $result ;

 
    }
    
    
   //
   function transactions(  )
    {
        
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://167.99.255.217:8080/blockchain/transactions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);



return $result ;

 
    }
   function latest_blocks(  )
    {
        
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://167.99.255.217:8080/blockchain/blocks/latest');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);



return $result ;

 
    }
    
   function blocks(  )
    {
    
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://167.99.255.217:8080/blockchain/blocks",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);
 
      

return $response ;

 
    }
    

     function transaction_details($txid)
    {
        

$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=transaction_details&txid='.$txid);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 

$res=json_decode($result);
 
 if ($res) {
    return $result  ;
} else {
   return 0;
}

 
 
    }
    
//   function blocks(  )
//     {
    
// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => "http://167.99.255.217:8080/blockchain/blocks",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET",
// ));

// $response = curl_exec($curl);

// curl_close($curl);
 
      

// return $response ;

 
//     }
    
    
    function NewBalance(Request $request){
        
  $ar=array();      
             
 $address=$request->address;
 
 $bn = Bnext_Wallet::where('address',$address)->first();
 
   $b=$this->BalancebygivenID($bn->wallet_name  );
   $e=  number_format($b,8);

$ar['balance']=$e;
 return json_encode($ar);       
 
    }
     
     
    
      function pixdepositaddress_static_qrocde()
      {
          
          


  
 $url="https://api.vikkin.ltd/pix.png";
  
 $ar=array();
    
 
$user = Auth::user() ;
                                 
 $ar['amount']=$this->request['amount'];

 $ar['pix_address']= $url;
 
  $ar['transactionId']="st".  rand(3000000,9000000);
  

  $id = DB::table('deposit_address')->insertGetId(
       
    [ 'user_id' =>$user->id,'coin'=>'BRL','amount' =>$this->request['amount'] ,'qrcode_image' =>$url,'transactionId' => $ar['transactionId']   ,'dump'=>  "static"]
);

 $ar['id']=  $id ;
 return response()->json($ar);
 
}
 
 
 
 
    
    
    
    
    
    
      function pixdepositaddress (){
       
        /*
 $pix=new PixCtrlV3();
 
  
    
 $res=$pix->FetchQRcode($this->request );
 
     

 $ar=array();
 
 $ar['id']=$res['id'];

 
 //$ar['authorizationCode']=$res->authorizationCode;
 
 
 
 
 
 
 //$ar['status']=$res->status;
 
  
 
 
 
  
 $ar['pix_address']= $res['metadata']['qrcode'];
 
  $ar['transactionId']=  $ar['id'];
 
 
 
 

 return response()->json($ar);
 
 
 */
 
 
$user = Auth::user() ;
        
       
 $amount=$this->request['amount']; 
        
 
 //  $ar['amount']=$res['amount'];

 
 $data='{
    "api_key": "'. $user->api_key.'",
    "email": "'. $user->email.'",
    "amount": '.  $amount.',
    "notification_url": "localhost3",
    "document_number": "05418082736",
    "document_type": "cpf",
    "name": "string",
    "description": "string"
}';

  
 
  $logid = DB::table('deposit_address_log')->insertGetId(
       
    [ 'user_id' =>$user->id,'coin'=>'BRL','amount' =>2 ,'qrcode_image' => "" ,'transactionId' => ""  ,'dump'=>  print_r($data,true)]
);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://3.237.12.27/qrcode',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$data,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

//echo $response;
 
//var_dump($response);
 

$res  =json_decode($response,true);
//var_dump($res);


curl_close($curl);


//return   $res->id;

//return $res['id'];

// return response()->json($res);
 
 
 

 
/*

 $ar=array();
 
 $ar['id']=$res['id'];

 
 //$ar['authorizationCode']=$res->authorizationCode;
 
 
 
 
 
 
 //$ar['status']=$res->status;
 
  
 
 
 
  
 $ar['pix_address']= $res['metadata']['qrcode'];
 
  $ar['transactionId']=  $ar['id'];
 
 
 
 

 return response()->json($ar);
 

return $res;
 
 DB::table('deposit_address_log')
            ->where('id', $logid)
            ->update( [ 
            'url_res' =>$response
            ]);
      
     
*/

 $ar=array();

if( isset( $res['id']))
{
    
 $ar['id']=$res['id'];
 $ar['amount']=$res['amount'];

$url="https://chart.googleapis.com/chart?cht=qr&chl=".$res['metadata']['brcode']."&choe=UTF-8&chs=300x300";


 $ar['pix_address']= $url;// $res['metadata']['brcode'];
 
  $ar['transactionId']=  $ar['id'];
  $ar['qrcode_value']=  $res['metadata']['brcode'];
  

 // $id = DB::table('deposit_address')->insertGetId(
       
  //  [ 'user_id' =>$user->id,'coin'=>'BRL','amount' =>$res['amount'] ,'qrcode_image' => $ar['pix_address'] ,'qrcode_value' => $ar['qrcode_value'] ,'transactionId' => $ar['transactionId']   ,'dump'=> $response]
//);


 return response()->json($ar);

}
else 
if( isset( $res['data']["last_invoice_created"]["token"] )  )

{

 //return response()->json($res);
     $transactionId=$res['data']["last_invoice_created"]["token"];
    
 //$bn = Bnext_Wallet::where('transactionId',$transactionId)->first();
 
 
 $bn=DB::table('deposit_address')->where('transactionId',$transactionId)->first()  ;
 
  //  $ar['debug_data']=$res;

     $ar['id']=$bn->id;
 $ar['amount']=$bn->amount;

 
 
 $ar['qrcode_value']= $bn->qrcode_value ;
   
   
   $ar['brcode']= $bn->brcode;
   
   
 $ar['dump']= $bn ;
 
  $ar['transactionId']=  $transactionId;
  




 return response()->json($ar);
 
 

}

else



{
    
 return "";//  $this->pixdepositaddress_static_qrocde();
    
    
}
 
  //
  
 
 




      }
            function widthdraw_coin_history(){
          $user_id= Auth::user()->id;
           $data=DB::table('exchange_widthdraw')->where('user_id',$user_id)->get()  ;
 return response()->json($data );
    
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
     
    function pixdepositaddress1111(){
         $pc =  new PixCtrl();
 
 $qr="";//$pc->getQRCode( $r->amount);
 
       $user_id= Auth::user()->id;
  $deposit= $qr;
$coin = 'PIX';


     
$amount = $this->request['amount'];



$id = DB::table('deposit_address')->insertGetId(
    [ 'user_id' => $user_id ,'amount' => $amount ,'coin' => $coin ,'address' => $deposit   ]
);




 $ar1=$pc->getQRCode( $amount, $id);
 
// $transactionId=$ar->transactionId ;


$str=urlencode("00020126360014br.gov.bcb.pix0114448739620001205204000053039865802BR5921DATA AI ROBOTICS LTD A6009Sao Paulo61080141000062070503***63043127");


 $qrcode=file_get_contents("http://qrcode.m21.exchange/?data=".$str);


  
  $deposit_address=  $qrcode;//$this->getqrcode();
  
  
     
 
    
    $invoice= $id;// rand(200,900);
     
 
 
  $id =  DB::table('deposit_address')
            ->where('id', $id)
            ->update(['address' => $deposit_address,'transactionId' =>$invoice ,'status' =>  "Created"     ,'url_res' =>"local"  ]);
      
      
      

 
  $ar=array();
    $ar['Error']=false; 
    
      $ar['pix_address']=  $deposit_address;

      $ar['pix_data']=  $str;

      $ar['InvoiceID']=  $invoice;

      $ar['amount']=  $amount;

    $ar['Message']=  "Invoice ID ".$invoice." In transaction descriptions when you make payment.";
    
    return response()->json($ar);

    }
    
    
    
    function getqrcode()
    { 
  
    }
    function Balance(  )
    {
        
        
        
        
        $user= Auth::user();
        
     $user_id=     $user->id;
        
        
          $bn = Bnext_Wallet::where('user_id',$user_id)->get();
           
         
              $bal=0;
          foreach ($bn as $f) {
  
    
  $b=$this->BalancebygivenID( $f->wallet_name  );
  
   
    
 $bal=$bal+$b;
 
 
}
$ar=array();


$bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
      
      
          if(is_null( $bn1)  )
   {
 
 
   $bn1= $this->generateAddressPrivateBlockChain($user_id);
   
   }
   
   
   
        $address=$bn1->address;
           
           
$e=  number_format(  $bal,8);



 $pc =  new PixCtrl();
 
 $qr="";//$pc->getQRCode( $r->amount);
 
       $user_id= Auth::user()->id;
  $deposit= $qr;
$coin = 'PIX';
$id = DB::table('deposit_address')->insertGetId(
    [ 'user_id' => $user_id ,'coin' => $coin ,'address' => $deposit   ]
);


 
 $ar1=$pc->getQRCode( 1000, $id);
 
// $transactionId=$ar->transactionId ;
 
  $deposit_address="";//$ar1->instantPayment->generateImage->imageContent;
 
 $ar['pixaddress'] = $deposit_address;

$ar['balance']= $user->BRL;
$ar['address']=$address;


$ar['btc_address']=   //$this->getBtcAddress( );
$ar['btc_balance']=  $user->BTC;
 
 
 
     
     
        $f=file_get_contents("https://api.bitfinex.com/v1/pubticker/btcusd");
          $s=json_decode($f);
         
          $rt=$s->bid;
         
        
          
  
   $ar['btc_rate']=sprintf('%0.2f',$rt)   ;  //  round($no,2);
    
    
    
    
   return json_encode($ar);       
   
          
        
 
    }
    
    
    
//     function getExchangeRate()

// {

//         return 13;

// }
    
    
    
    
     function Wallet( )
    {
        
        
        
        
        $user= Auth::user();
        
     $user_id=     $user->id;
        
        
          $bn = Bnext_Wallet::where('user_id',$user_id)->get();
           
         
              $bal=0;
          foreach ($bn as $f) {
  
    
  $b=$this->BalancebygivenID( $f->wallet_name  );
  
   
    
 $bal=$bal+$b;
 
 
}
$ar=array();


$bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
      
      
          if(is_null( $bn1)  )
   {
 
 
   $bn1= $this->generateAddressPrivateBlockChain($user_id);
   
   }
   
   
   
        $address=$bn1->address;
           
           
$e=  number_format(  $bal,8);

$ar['balance']=$e;
$ar['address']=$address;


$ar['btc_address']=  $this->getBtcAddress( );
$ar['btc_balance']=  $user->BTC;
 
 
        $f=file_get_contents("https://api.bitfinex.com/v1/pubticker/btcusd");
          $s=json_decode($f);
         
          $rt=$s->bid;
         
        
          
  
   $ar['btc_rate']=sprintf('%0.2f',$rt)   ;  //  round($no,2);
    
    
    
    
   return json_encode($ar);       
   
          
        
 
    }
     function rate(Request $request)
    {
     
     
          
            $ss  =System_Settings::first();
    
     
     
          
    $ar=array();
    $ar['rate']=$ss->exchange_rate    ;  //  round($no,2);

   return json_encode($ar);    
          
}
    
    function BalancebygivenID( $wallet_name )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=balance&w='.$wallet_name);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 

$res=json_decode($result);
 
 if ($res) {
    return $res->balance  ;
} else {
   return 0;
}

 
 
    }
       function getBalancebyaddress( $address )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, 'http://159.65.220.60:3001/address/'.$address);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 

$res=json_decode($result);
 
 if ($res) {
    return $result  ;
} else {
   return 0;
}

 
 
    }
    
    function ipaddress($ipaddress  )
    {
       
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, "https://ipapi.co/$ipaddress/json/");


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 

$res=json_decode($result);
 
 if ($res) {
    return $res  ;
} else {
   return 0;
}

 
 
    }
    
    
    function peerinfo(  )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=peerinfo');


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 
 
 
 $ar=array();
   $r1= json_decode($result) ;
 
 
// var_dump($r1);
foreach($r1->peerinfo->result as $r){
    
    
    $r2=array();
    $r2['peers']=$r->addr;
     $r2['id']=$r->id;
    
  //  $r2['ip']=$this->ipaddress($r->addr);
    
    array_push($ar,$r2);
}
 
    $r2=array();
    $r2['peers']=$this->ip;
    // $r2['ip']=$this->ipaddress($this->ip);
     $r2['id']=0;
    
  
    
    array_push($ar,$r2);
    
    
    
 return json_encode($ar);

  //  return $result ;
 

 
 
    }
    
    
    function listunspent(  )
    {
        
 $user= Auth::user();
        
     $user_id=$user->id;
        
        
          $bn = Bnext_Wallet::where('user_id',$user_id)->first();
           
 $b=$this->listunspentbygivenID( $bn->wallet_name  );

   $s=json_decode($b);
   $ar=array();
   
   $ar['transactions']=$s->transactions;
   
   
 return json_encode($ar);
 
   
          
        
 
    }


    function listunspentbywallet( $wallet_name)
    {
        
 $user= Auth::user();
        
     $user_id=$user->id;
        
        
 $bn = Bnext_Wallet::where('user_id',$user_id)->where('wallet_name',$wallet_name)->first();
 
 
           
 $b=$this->listunspentbygivenID( $bn->wallet_name  );
   
   $s=json_decode($b);
 
 
   return json_encode($s->transactions);       
   
          
        
 
    }

    function listunspentbygivenID( $wallet_name )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?a=23&q=listunspent&w='.$wallet_name);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 

    return $result ;
 

 
 
    }
    
    
    function walletbalance( $wallet_name )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?a=23&q=balance&w='.$wallet_name);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 

    return $result ;
 

 
 
    }
    
        
    function listwallets(  )
    {
       
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=listwallets');


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

//var_dump($result);
 
      
$ar=array();
   $r1= json_decode($result) ;
 
foreach($r1->allwallet->result as $r){
    
    
    $r2=array();
    $r2['wallet']=$r;
    
  $b=json_decode($this->walletbalance($r));
    
    
    
   $r2['balance']=$b->balance;
    
    array_push($ar,$r2);
}
 
 return json_encode($ar);
 
 
// echo  $this->private_blockchain_host.'?q=listwallets';
    }


    
    function blockcount(  )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=blockcount');


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 

    return $result ;
 

 
 
    }
    
    function sendFund()
    {
                         $user_email= Auth::user()->email;
        $send_fund_email_otp=$this->request['email_otp'];
        
        
        $user_email_otp= Auth::user()->otp;
        
        
        if($user_email_otp==$send_fund_email_otp){
            
            
         $auth_code=$this->request['auth_code'];
     $secret=DB::table('loginguards')->where('email',$user_email)->first()  ;
     
     	$googleauth=new GoogleAuthCtrl($request);
   
    
         $checkResult = $googleauth->verifyCode( $secret, $this->request['auth_code']  ,   2);    
 
 if ( $checkResult) {
     
 
        
   $ar=array();
                 $user_id= Auth::user()->id;
                 
                 
                 
      $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
      
       
   $toAddress=$this->request['toAddress'];
   
       $amount=(float)$this->request['amount']   ; 
       
$ch = curl_init();

$ar=array();


$ar['wallet_name']=  $bn1['wallet_name']; 

$ar['toAddress']=$toAddress;
  
  
$ar["amount"]=$amount;

 

$json=json_encode($ar);
  


curl_setopt($ch, CURLOPT_URL, $this->private_blockchain_host.'?q=sendfund&w='.$ar['wallet_name'].'&a='.$ar['toAddress'].'&amount='.$ar["amount"]);




curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, $json );



$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
$headers[] = 'Password: '.$bn1['password'];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 
$r=array();
   $r['status']=200;
   $r['result']=json_decode($result);
   
   $r['rawresult']= $result ; 
           
  $r['message']="Successfully...";
    return response()->json($r);
//return $result ;

 }
 
 else{
     $r=array();
   $r['Error']=true;
   
  $r['message']="Incorrect Auth Code";
    return response()->json($r);
 }
 
 
        }
        
        else{
              $r=array();
   $r['Error']=true;
   
  $r['message']="Incorrect OTP";
    return response()->json($r);
        }
    }
     
     
     public function confirm_payment(Request $request)
{ 
    
    
        
    
    //$user_id= Auth::user()->id;
 $data=DB::table('exchange_widthdraw')->where('id',$request->id)->orderBy('id', 'desc')->first()  ;
      
  return response()->json($data );
    
}  
     
    
    
    public function NewWallet(Request $r)
    {
        
        $ar=array();
 
 
   $deposit=   "";
       $user_id= Auth::user()->id;
       
       
      $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
      
     
      
      
          if(is_null( $bn1)  )
   {
 
 
   $bn1= $this->generateAddressPrivateBlockChain($user_id);
   
   }
   
   
   
   
   // $deposit=  $this->getBtcAddress( );
   
   
   
   
    $ar['address']= $bn1['address'];
    $ar['wallet_id']= $bn1['wallet_id'];
    $ar['coin']="private_blockchain";
 
 
  
   
   
   
   $r=array();
   $r['status']=200;
   $r['result']=$ar;
  
    return response()->json($r);
  
        
    }
   //
   
         public function allWallet()
{ 
    
  
    
    $wal=array();
    
    
    $q="SELECT b.*, u.first_name,u.email FROM `bnext_wallet` b , users u WHERE b.user_id=u.id ";
    
    
 $data=DB::table('bnext_wallet')->orderBy('id', 'desc')->get()  ;
 
 
 $data = DB::select($q);
 
 
 
 foreach($data as $dr)
 {
     $w=array();
     $w['user_id']=$dr->user_id;
     $w['address']=$dr->address;
        $w['name']=$dr->first_name;
      //     $w['email']=$dr->email;
     $w['balance']= $this->BalancebygivenID($dr->address );
     
     array_push($wal,$w);
 }
      
  return response()->json($wal );
    
}    


public function generatenewaddress(){
     $bn=new Bnext_Wallet();
      $user_id= Auth::user()->id;
       //   $wallet_name= "dubaikoin_".rand()."wallet_".$user_id;
         $wallet_name= "wallet_".rand()."".$user_id;
        $ar=array();  
$ch = curl_init();

$notification_url=$_SERVER['HTTP_HOST']."/notifications/".rand();


$url=$this->private_blockchain_host.'?q=createwallet&w='.$wallet_name.'&notification_url='.$notification_url ;


curl_setopt($ch, CURLOPT_URL,$url );


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($ar));

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
$res=json_decode($result);
curl_close($ch);

//$balance=walletbalance( $wallet_name );

$bn->user_id=$user_id;
 


  $bn->address=  $res->address;

  $bn->wallet_id=  $res->address;
   $bn->wallet_name=  $wallet_name; 
          
 
curl_close($ch);

$bn->save();

return   $res->address;

}

    public function generateAddressCustomCoin($user_id)
    {
         
     
    $bn1 = Bnext_Wallet::where('user_id',$user_id)  ->first();
               
 
   if(  $bn1 )
   {
 
 
        
        return   $bn1  ;
   
   }
  
   
        $ar=array();
        
        
        
        
        
        
        $bn=new Bnext_Wallet();
        
          
          
        $wallet_name= "VN3_".rand(7990,9990)."__".$user_id;
        
        
$ch = curl_init();


$notification_url="https://".$_SERVER['HTTP_HOST']."/api/notifications";


$url=$this->private_blockchain_host.'?q=createwallet&w='.$wallet_name.'&notification_url='.$notification_url ;
 


//$url=$this->private_blockchain_host.'?q=createwallet&w='.$wallet_name;


curl_setopt($ch, CURLOPT_URL,$url );

DB::insert('insert into logpb (  msg) values  (?)', [$url]);



curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($ar));

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
 

 
DB::insert('insert into logpb (  msg) values  (?)', [$result]);


$res=json_decode($result);

$bn->user_id=$user_id;
 


  $bn->address=  $res->address;

  $bn->wallet_id=  $res->address;
   $bn->wallet_name=  $wallet_name; 
          
 
curl_close($ch);

$bn->save();

return   $bn;
     
    }

    public function generateAddressPrivateBlockChain($user_id)
    {
         
     
    $bn1 = Bnext_Wallet::where('user_id',$user_id)  ->get();
               
 
   if(count( $bn1) > 0)
   {
 
 
        
        return   $bn1[0] ;
   
   }
  
   return "";
        $ar=array();
        
        
        
        
        
        
        $bn=new Bnext_Wallet();
        
          
          
        $wallet_name= "W".rand(7990,9990)."__".$user_id;
        
        
$ch = curl_init();


$notification_url="https://".$_SERVER['HTTP_HOST']."/api/notifications";


$url=$this->private_blockchain_host.'?q=createwallet&w='.$wallet_name.'&notification_url='.$notification_url ;
 


//$url=$this->private_blockchain_host.'?q=createwallet&w='.$wallet_name;


curl_setopt($ch, CURLOPT_URL,$url );

DB::insert('insert into logpb (  msg) values  (?)', [$url]);



curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($ar));

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
 

 
DB::insert('insert into logpb (  msg) values  (?)', [$result]);


$res=json_decode($result);

$bn->user_id=$user_id;
 


  $bn->address=  $res->address;

  $bn->wallet_id=  $res->address;
   $bn->wallet_name=  $wallet_name; 
          
 
curl_close($ch);

$bn->save();

return   $bn;
     
    }
    
    public function getNewwallet(){
           $user_id= Auth::user()->id;
        //  $bn1 = Bnext_Wallet::where('user_id',$user_id)->get();

//   if(count($bn1) > 0)
//   {
//       return   $bn1[0] ;
   
//   }
        $ar=array();
 
     $bn=new Bnext_Wallet();
   
          $wallet_name= "wallet_".rand()."".$user_id;
        

$ch = curl_init();
$url=$this->private_blockchain_host.'?q=createwallet&w='.$wallet_name;


curl_setopt($ch, CURLOPT_URL,$url );


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($ar));

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
$res=json_decode($result);
curl_close($ch);

//$balance=walletbalance( $wallet_name );

$bn->user_id=$user_id;
 


  $bn->address=  $res->address;

 // $bn->wallet_id=  $res->address;
  $bn->wallet_name=  $wallet_name; 
          
 
$bn->save();
// echo curl_errno($ch);
// echo curl_error($ch);
// curl_close($ch);

return $bn;

}

    
    public function getNewAdrress1()
    {
        
        //generateAddressPrivateBlockChain($user_id)
        
        
    // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://64.227.28.52:8080/operator/wallets/'.  $bn->wallet_id.'/addresses');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
$headers[] = 'Password:   '.  $bn->password;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);



$res=json_decode($result);


//$bn->address=$res->address;

//$bn->save();
return  $res->address;


//echo json_encode($bn);

}
    
function password_generate( ) 
{
  
   
      
      
  
  
  return  "" ;
  
  
  
  
}


 



function getBitcoinAddress( )
{
    
    
       
    $rt=file_get_contents("https://api-pub.bitfinex.com/v2/ticker/tBTCUSD");

$r1=json_decode( $rt) ;

 
 $rate=$r1[0];



    
   $r=array();
   $r['status']=200; 
   
   
   $r['rate']=1/$rate ;  
   
   
 $user= Auth::user();
 
 
   $r['btc_balance']=$user->BTC;
   
   
   $r['Address']=$this->getBtcAddress( );
  
    return response()->json($r);
  
  
}




function getInvoiceFiat( )
{
    
    
       
    
   // $rt=file_get_contents("https://api-pub.bitfinex.com/v2/ticker/tBTCUSD");

//$r1=json_decode( $rt) ;

 
 $rate=1;//$r1[0];



    
   $r=array();
   $r['status']=200; 
   
   
   $r['rate']=1/$rate ;  
   
   
    $ss  =System_Settings::first();
    
      $r['newcoin']=$ss->exchange_rate ;    
  // $this->request['amount']
   
 $user= Auth::user();
 
 
   $r['btc_balance']=$user->BTC;
   
   
   
   $r['FiatName']= "Brazilian real";
   
   
   $r['amountFiat'] =   $r['newcoin'] * $this->request['amount'];
   
   
   
   $r['amountUSD'] =   $r['newcoin'] * $this->request['amount'];
   
   
   $r['amountBTC'] =  $r['rate'] * $r['amountFiat'];
   
   if($r['btc_balance']  >=  $r['amountBTC']  )
      $r['buy_from_balance'] = "Complete it from the bitcoin balance";
      
      else
       $r['buy_from_balance'] = "I have paid";
      
   $r['Address']=  $ss->coin2_deposit_address ;
   
  
    return response()->json($r);
  
}

function getInvoiceBtc( )
{
    
    
       
    $rt=file_get_contents("https://api-pub.bitfinex.com/v2/ticker/tBTCUSD");

$r1=json_decode( $rt) ;

 
 $rate=$r1[0];



    
   $r=array();
   $r['status']=200; 
   
   
   $r['rate']=1/$rate ;  
   
   
    $ss  =System_Settings::first();
    
      $r['newcoin']=$ss->exchange_rate ;
   
  // $this->request['amount']
   
 $user= Auth::user();
 
 
   $r['btc_balance']=$user->BTC;
   
   
   $r['amountUSD'] =   $r['newcoin'] * $this->request['amount'];
   
   
   $r['amountBTC'] =  $r['rate'] * $r['amountUSD'];
   
   if($r['btc_balance']  >=  $r['amountBTC']  )
      $r['buy_from_balance'] = "Complete it from the bitcoin balance";
      
      else
       $r['buy_from_balance'] = "I have paid";
      
   $r['Address']=$this->getBtcAddress( );
  
    return response()->json($r);
  
  
}



function getBtcAddress( )
{
    
 
 $user_id= Auth::user()->id;
    
     
    $deposit_address = DB::table('deposit_address')->where('user_id', $user_id)->count();
    
    
  // echo $deposit_address;
   if( $deposit_address==0)
   {
 
 
        $this->setBtcAddress($user_id );
   
   }
  
   $data=   DB::table('deposit_address')->where('user_id', $user_id)->where('coin', "BTC")->first(); 
  
  return $data->address ;	
}

function setBtcAddress($user_id )
{
   
 
 $secret = 'ZzsMLGZzsMLGKe162CfA5EcG6jKe162CfA5EcG6j';

$my_xpub = $this->getXpub( );
 
 
$my_api_key = '36276ce3-16c5-471d-bcfd-ac143bfeccd2';


$invoice=date("MdYhisA").$user_id;

$hit_url=$_SERVER['HTTP_HOST'] ."/". $_SERVER['REQUEST_URI'];




$my_callback_url ="https://". $_SERVER['HTTP_HOST'] ."/system/btcdeposit?invoice_id=".$invoice."&user_id=".$user_id."&secret=".$secret;
 

$root_url = 'https://api.blockchain.info/v2/receive';

$parameters = 'xpub=' .$my_xpub. '&callback=' .urlencode($my_callback_url). '&key=' .$my_api_key;

 
 $call=$root_url . '?' . $parameters;
 
 
$response = file_get_contents( $call);
 
$object = json_decode($response);

// echo "object";
// print_r($object);

DB::table('deposit_address')->insert(['coin'=>"Bitcoin", 'user_id' => $user_id, 'address' => $object->address,'url_hit' => $call ,'url_res' =>$response ]);

 
 DB::table('exchange_btcaddress')->insert(['xpub'=>$my_xpub , 'user_id' => $user_id, 'address' => $object->address ]);

 
 
     
  return $object->address ;	
  
  
 // return "NA";


}

public function getwithdrawal_update(Request $r){
    
   $id=  $r->id;
     
    $updateDetails = [
    'status' =>  "Approved"
];

DB::table('swap_coin')
    ->where('id',   $id)
    ->update($updateDetails);
}


public function getwithdrawal_details($id){
    
    
      // $id=$r->id;
      
 
    if(Auth::user()->roll >  5)
        {
 
 
 
       
      $data=   DB::table('exchange_widthdraw')->where('id',$id)->first() ; 
      
      
        }
        else
        {
            
 
       
      $data=   DB::table('exchange_widthdraw')->where('user_id', $user_id)->where('id',$id)->first() ; 
      
 
 
        }
 
    return response()->json($data);
    
}




public function getallwithdrawalusers(){
     $data=   DB::table('swap_coin')->get() ; 
    return response()->json($data);
}


public function getallwithdrawal( )
{
 
 
           
 $user= Auth::user();
        
     $user_id=$user->id;
        
        
          $bn = Bnext_Wallet::where('user_id',$user_id)->first();
           
 $b=$this->listunspentbygivenID( $bn->wallet_name  );

   $s=json_decode($b);
   $ar=array();
   
   $ar['transactions']=$s->transactions;
 return json_encode($ar);
 
//       $user = Auth::user() ;
//                  $user_id= $user->id;
    
    
 
//     if(Auth::user()->roll >  5)
//         {
 
 
 
//   $data=   DB::table('exchange_widthdraw')  ->latest()->get() ; 
   
   
//         }
//         else
//         {
            
 
 
 
//   $data=   DB::table('exchange_widthdraw')->where('user_id', $user_id) ->latest()->get() ; 
   
            
//         }
//     return response()->json($data);
    
   
} 

   

 function sendTelegramMsg($msg){
ob_start();

        $msg=urlencode ($msg  );


 
$ch = curl_init();

 $url="http://159.65.220.60:81/send/".  $msg;
 
 echo file_get_contents($url);
curl_setopt($ch, CURLOPT_URL, $url);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    //echo 'Error:' . curl_error($ch);
}
curl_close($ch);
  


return  ob_get_clean();

} 

   

public function deposit_slip(Request $r){
      $user = Auth::user() ;
         $user_id= $user->id;
    
    
    try{
        
        
        
        
        
    $id = DB::table('deposit')->insertGetId(
    [ 'user_id' => $user_id ,'coin' => $r->coin ,'deposit_transaction_id' => $user->email." ".$r->deposit_transaction_id ,'amount' => $r->amount  ,'deposit_slip' => $r->deposit_slip ]
);
        
        
        
        $msg="Received a deposit request of amount ".$r->amount . " From " . $user->email;
        
        
        
      /////////////////////////////////
      
   $st=   $this-> sendTelegramMsg($msg);
      
      
         $data = array( 'content'=> 'Deposit request has been submitted','subject'=>  'Deposit request has been submitted'  );
         
        Mail::send('mail', $data, function($m) {
          
           $u = Auth::user() ;
         $email= $u->email;
   $m->to($email,$email )->subject
            ('Deposit request has been submitted');
       });
      
      
      
          Mail::send('mail', $data, function($m) {
          
           $u = Auth::user() ;
         $email=  "irsantana@msn.com";
   $m->to($email,$email )->subject
            ('Deposit request has been submitted');
       });
      
      
      
          Mail::send('mail', $data, function($m) {
          
           $u = Auth::user() ;
         $email=  "susheel3010@gmail.com";
   $m->to($email,$email )->subject
            ('Deposit request has been submitted');
       });
      
      
      /////////////////////////////////
        
        $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Submitted successfully";
    
    return response()->json($ar);
    
    }

catch(\Exception $e) {

       
            
              $ar=array();
    $ar['Error']=true; 
    
    $ar['Message']=  "Plese provide correct transaction ID";
    
    return response()->json($ar);
        }
        
        
           
                 
}



public function deposit_details($id){
      $user = Auth::user() ;
         $user_id= $user->id;
   if(Auth::user()->roll >  5)
        {
 
 // $data=   DB::table('deposit') ->where('id',  $id) ->first() ; 
  $data = DB::table('users') -> select("deposit_slip",  'deposit_transaction_id', "email","bank_account","amount","deposit.id","deposit.created_at","deposit.status","deposit.transaction_id","deposit.approve_by","deposit.remark_message","deposit.user_id","users.created_at as uca")
            ->rightJoin('deposit', 'users.id', '=', 'deposit.user_id') ->where('deposit.id',  $id)
            ->first();
      }
        else
        { 
   $data=   DB::table('deposit')->where('user_id', $user_id)->where('id',  $id) ->first() ; 
   
       }
    return response()->json($data);
           
                 
}

public function getalldeposits( )
{
           
 $user= Auth::user();
        
     $user_id=$user->id;
        
        
          $bn = Bnext_Wallet::where('user_id',$user_id)->first();
           
 $b=$this->listunspentbygivenID( $bn->wallet_name  );

   $s=json_decode($b);
   $ar=array();
   
   $ar['transactions']=$s->transactions;
 return json_encode($ar);
 
   
          
        
 
    }

  
//       $user = Auth::user() ;
     
 

//                  $user_id= $user->id;
     
  
//          if(Auth::user()->roll >  5)
//         {
 
//   $data=   DB::table('deposit')->select("amount","id","created_at",'deposit_transaction_id', "status","transaction_id","approve_by","remark_message") ->latest() ->get() ; 
   
//         }
//         else
//         { 
//   $data=   DB::table('deposit')->select("amount","id","created_at",'deposit_transaction_id', "status","transaction_id","approve_by","remark_message")->where('user_id', $user_id) ->latest() ->get() ; 
//         }
    
   
  
     
    
//     return response()->json($data);
    
  


public function getallsawp( )
{
 
 
  
       $user = Auth::user() ;
     
 

                 $user_id= $user->id;
     
  
 
 
    
   $data=   DB::table('swap_coin')->where('user_id', $user_id) ->latest() ->get() ; 
   
    
   
  
     
    
    return response()->json($data);
    
   
} 



public function swapcoinprocess(Request $r)
{
 
 
  
       $user = Auth::user() ;
     
    
     $balance=$user->BRL;
     
     $amount=$r->amount;
     


                 $user_id= $user->id;
     
 
 $rate=1 ;
 
   
  $amountinBTC=  $r->amount  *  (1/$rate);
  
           $ar=array();
 
$pl=new Paymentcls();
 

 
 
    
 
 
    $id = DB::table('swap_coin')->insertGetId(
    [ 'user_id' => $user_id ,'coin1' => "BRL",'coin2' =>"Casinokoin" ,'deposit_address' => "Bank Account" ,'amount1' => $r->amount1  ,'amount2' => $r->amount2 ]
);
 
 
      
if(!$pl->debit_user( $user ,  $amountinBTC , "BRL",'Swapping'))
{
  
           
$updateDetails = [
    'status' =>  "Completed"
];

DB::table('swap_coin')
    ->where('id',   $id)
    ->update($updateDetails);
        
        

} 

else
{
    
    
    
}
 
 
    
    $ar['Error']=false; 
    
    $ar['Message']=  "Request submitted successfully";
    
    return response()->json($ar);
    
   
} 





 
public function buycoinprocessFiat(Request $r)
{
 
 
  
       $user = Auth::user() ;
     
    
     $balance=$user->BRL;
     
     $amount=$r->amount;
     


                 $user_id= $user->id;
     
 
 $rate=1 ;
 
   
  $amountinBTC=  $r->amount  *  (1/$rate);
  
           $ar=array();
 
$pl=new Paymentcls();
 

 
 
    
    $id = DB::table('swap_coin')->insertGetId(
    [ 'user_id' => $user_id ,'coin1' => "BRL",'coin2' =>"Casinokoin" ,'deposit_address' => "Bank Account" ,'amount1' => $r->amount  ,'amount2' => $r->amount ]
);
 
 
 
 
      
if($pl->debit_user( $user ,  $amountinBTC , "BRL",'Swapping'))
{
  
           
$updateDetails = [
    'status' =>  "Completed"
];

DB::table('swap_coin')
    ->where('id',   $id)
    ->update($updateDetails);
        
        

} 

else
{
    
    
    
}
 
 
    
    $ar['Error']=false; 
    
    $ar['Message']=  "Request submitted successfully";
    
    return response()->json($ar);
    
   
} 


public function buycoinprocess(Request $r)
{
 
 
  
       $user = Auth::user() ;
     
    
     $balance=$user->BTC;
     
     $amount=$r->amount;
     
     
    
    $rt=file_get_contents("https://api-pub.bitfinex.com/v2/ticker/tBTCUSD");

$r1=json_decode( $rt) ;

 
 $rate=$r1[0];
 
   
  $amountinBTC=  $r->amount  *  (1/$rate);
  
           $ar=array();
 
$pl=new Paymentcls();
 

 
      
if($pl->debit_user( $user ,  $amountinBTC , "BTC",'Purchase by BTC'))
{
  
  

                 $user_id= $user->id;
                 
                 
                 
      $bn1 = Bnext_Wallet::where('user_id',  $user_id)->first();
      
      
  
  
   // var_dump($wallet);
   $toAddress=$this->request['ReceivingAddress']  ;
   
       $amount=(float)$this->request['amount'] * 100000000 ; 
       
$ch = curl_init();

$ar=array();


$ar['fromAddress']=  $bn1['address']; 

$ar['toAddress']=$toAddress;
  
  
$ar["amount"]=$amount;


$ar["changeAddress"]=$ar['fromAddress']; 



$json=json_encode($ar);

    
    
    // $this->private_blockchain_host="http://64.227.28.52/btcrpc/";
     
     
     //   $this->ip="http://64.227.28.52";
        
        
curl_setopt($ch, CURLOPT_URL, 'http://167.99.255.217:8080/operator/wallets/'.$bn1['wallet_id'].'/transactions');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, $json );



$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
$headers[] = 'Password: '.$bn1['password'];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);



} 
 
 
    
    $ar['Error']=false; 
    
    $ar['Message']=  "Request submitted successfully";
    
    return response()->json($ar);
    
   
} 

//Balance ETH


        public function  BalanceETH(  )
    {
    
        $user= Auth::user();
        
     $user_id=     $user->id;
          $bn = ETH_Wallet::where('user_id',$user_id)->get();
        $bal=0;
          foreach ($bn as $f) {
  
    
  $b=$this->ETHBalancebygivenID( $f->address  );
 
 $bal=$bal+$b;
 
 
}
$ar=array();
$bn1 = ETH_Wallet::where('user_id',$user_id)->first();
      
      
          if(is_null( $bn1)  )
   {
 
 
   $bn1= $this->ETHgenerateAddressPrivateBlockChain($user_id);
   
   }
          $address=$bn1->address;
    
    
    $ar=array();
    
    $ar['balance']=$bal;
      $ar['address']=$address;
     // var_dump( $bal);
    
  return json_encode($ar);      
    }
    
    
  function ETHBalancebygivenID( $address )
    {
      
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, 'http://64.227.28.52:8080/Balane/'.$address);

//var_dump($address);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 
//var_dump($result);
$res=json_decode($result);
 
 if ($res) {
    return $res->result  ;
} else {
  return 0;
}
}



 
     public function ETHgenerateAddressPrivateBlockChain()
    {
         $user= Auth::user();
        
     $user_id=     $user->id;
        
 $bn1=DB::table('private_eth_wallet')->where('user_id',$user_id)->get()  ;
               
   if(count( $bn1) > 0)
   {
   return   $bn1[0] ;
   
   }
     $ar=array();
        $bn=new ETH_Wallet();
 $wallet_name= "wallet_".rand()."_".$user_id;
       
$ch = curl_init();

$url='http://64.227.28.52:8080/NewWallet/:'.$wallet_name;
//var_dump($url);

curl_setopt($ch, CURLOPT_URL,$url );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($ar));

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}


$res=json_decode($result);
//var_dump($result);
$bn->user_id=$user_id;
  $bn->address=  $res->Address;

  $bn->wallet_id=  $res->Address;
  $bn->wallet_name=  $wallet_name; 
  $bn->PrivateKey=$res->PrivateKey;
  $bn->PublicKey=$res->PublicKey;
  $bn->Mnemonic=$res->Mnemonic;
  $bn->coin='Ethereum';
 
curl_close($ch);

$bn->save();

return $bn;
     
    }
    
    
    
     
    function ETHlistunspentbygivenID( $address )
    {
       
        
        
$ch = curl_init();

 
curl_setopt($ch, CURLOPT_URL, 'http://64.227.28.52:8080/txlist/'.$address);


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 

    return $result ;
 
    }
    

public function ETHTransactions(){
    
    $user= Auth::user();
        
     $user_id=$user->id;
        
        
          $bn = ETH_Wallet::where('user_id',$user_id)->first();
           
 $b=$this->ETHlistunspentbygivenID( $bn->address  );
   
   $s=json_decode($b);
 
 
 $data=$s->result;   
//  var_dump($data);
  
   return view('system.ETHTransactions'   ,['data' => $data]);
    
}


public function deleteuserotp(){
    
    $user= Auth::user();
       $email= Auth::user()->email;
  
        $otp= mt_rand(1000,9999);
      $user->delete_user_otp=   $otp; 
      $user->save();
       
            $content='Delete User OTP is'.$otp;
     $data = array('email'=>$email, 'content'=> $content,'subject'=> "One Time otp for the delete otp in the alphakoin "  );
              
           
   //   Mail::send('mail', $data, function($m) {
        
          Mail::send(['text'=>'mail'], $data, function($m ) {
              
     $email= Auth::user()->email;
            $m->to($email, $email)->subject
            ('One Time password for the login in the wallet ');
            
          
            $m->getHeaders()->addTextHeader('isTransactional', true);
      });
      
        $ar=array();
    $ar['Error']=false; 
    
    $ar['message']=  "Otp send Successfully" ;
  
    return response()->json($ar);
    
      
    
}
public function send_payment_user(){

        $user= Auth::user();
          $ar=array();
         $amount=(float)$this->request['amount'];
          $coin=$this->request['coin'];
           $type=$this->request['type'];
              $user_id=$this->request['user_id'];
              
               $user_data=DB::table('users')->where('id',$user_id)->first()  ;
               
    $pl=new Paymentcls();
      
      if($type=='credit'){
      
        $res=  $pl->debit_user( $user ,  $amount,$coin ,'deposit' );
         $res=   $pl->credit_user( $user_data ,  $amount,$coin  ,'deposit'  );
           

    $ar['Error']=$res; 
    
    $ar['Message']= "Send Successfully" ;
      }
      
      if($type=='debit'){
   $res1= $pl->debit_user( $user_data ,  $amount,$coin ,'deposit' );
   $res1= $pl->credit_user( $user , $amount,$coin  ,'deposit'  );
    
  $ar=array();
    $ar['Error']=$res1; 
    
    $ar['Message']=  "Send Successfully" ;
}

 
    
    return response()->json($ar);
    

}

          
    
          public function LedgetTransactionsdeposits()
{ 
    
    $user_id= Auth::user()->id;
 $data=DB::table('system_transactions')->where('user_id',$user_id)->where('roll','<>',12)->where('type', 'credit')->orderBy('id', 'desc')->take(50000)->get()  ;
      
  return response()->json($data );
    
}    




       public function LedgetTransactionswidthdraws()
{ 
    
    $user_id= Auth::user()->id;


 $data=DB::table('system_transactions')->where('user_id',$user_id)->where('type', 'debit')->orderBy('id', 'desc')->take(50000)->get()  ;
      
      ;
      
  return response()->json($data );
    
}    
   
   
 function getXpub( )
  {


 
 
  $qr="select xpub ,n from (SELECT count(address) n, xpub FROM `exchange_btcaddress` GROUP by xpub ) t WHERE n < 12 order by n desc limit 1 ";
 
 
   $xp = DB::select($qr) ;
  return  $xp[0]->xpub;


   }
           
         public function ExchangeOrders()
{ 
    
     
    
    
    $user_id= Auth::user()->id;
    
   
 $data=DB::table('exchange_bookings')->where('user_id',$user_id) ->orderBy('id', 'desc')->take(500)->get()  ;
      
  return response()->json($data );
    
}

     public function LedgetTransactions()
{ 
    
    
     
    //@file_get_contents("https://api.inwista.ltd/api/checkDeposits");
    
    
    $user_id= Auth::user()->id;
    
   // return $user_id;
 //$data=DB::table('system_transactions')->where('user_id',$user_id)   ->where( 'created_at', '>', Carbon::now()->subDays(10))->orderBy('id', 'desc')->take(500)->get()  ;
 
 
 $data=DB::table('wallet_transactions')->where('user_id',$user_id) ->orderBy('id', 'desc')->take(500)->get()  ;
      
  return response()->json($data );
    
}
 


         public function LedgetUsers()
{ /*return Auth::user()->roll;
     if(Auth::user()->roll  <  9)
        {
 //  $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        */
        
    //$user_id= Auth::user()->id;
 $data=DB::table('users')->orderBy('id', 'desc') ->get()  ;
 
 
 //$data=DB::select("SELECT u.* , w.wallet_name as wallet_name , w.address as address , '' as first_name , '' as last_name  FROM `users` u , private_bnext_wallet w WHERE u.id=w.user_id ");
      
  return response()->json($data );
    
}

    public function KYCUsers()
{ 
     if(Auth::user()->roll   <   8  )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
 //$user_id= Auth::user()->id;
 $data=DB::table('users')->where('status',1)->orderBy('id', 'desc')->get()  ;
  return response()->json($data );
    
}



    public function NonKYCUsers()
{ 
     if(Auth::user()->roll <  8)
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
 //$user_id= Auth::user()->id;
 $data=DB::table('users')->where('status',0)->orderBy('id', 'desc')->get()  ;
  return response()->json($data );
    
}







    public function DeleteUsersAccountRequest()
{ 
     if(Auth::user()->roll <  8  )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
 //$user_id= Auth::user()->id;
 $data=DB::table('users')->where('account_status',1)->orderBy('id', 'desc')->get()  ;
  return response()->json($data );
    
}

         public function Ledgetmembership()
{ 
    
    //$user_id= Auth::user()->id;
 $data=DB::table('membership')->orderBy('id', 'asc')->get()  ;
      
  return response()->json($data );
    
}


public function postdeposits(Request $r)
{
 
   $deposit=   "";
       $user_id= Auth::user()->id;
     
    
    
     $deposit=  $this->getBtcAddress( );
   
    $id = DB::table('exchange_deposit')->insertGetId(
    [ 'user_id' => $user_id ,'coin' => $r->coin ,'deposit_address' => $deposit ,'amount' => $r->amount ]
);
 
  
 
   
  $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Request submitted successfully";
    
    return response()->json($ar);
    
   
} 

public function receive_payment(Request $r)
{
 $ar=array();
 
 
   $deposit=   "";
       $user_id= Auth::user()->id;
     
     
     
 
    if($r->coin =="PIX"){
        // echo $r->coin;
          $pc =  new PixCtrl();
 
 $qr="";//$pc->getQRCode( $r->amount);
 
       $user_id= Auth::user()->id;
  $deposit= $qr;
$coin = $r->coin;


     
$amount =1000;// $this->request['amount'];



$id = DB::table('deposit_address')->insertGetId(
    [ 'user_id' => $user_id ,'amount' => $amount ,'coin' => $coin ,'address' => $deposit   ]
);




 $ar1=$pc->getQRCode( $amount, $id);
 
// $transactionId=$ar->transactionId ;
 
  $deposit_address=$ar1->instantPayment->generateImage->imageContent;
  
  
 
 
  $id =  DB::table('deposit_address')
            ->where('id', $id)
            ->update(['address' => $deposit_address,'transactionId' => $ar1->transactionId  ,'status' => $ar1->financialStatement->status     ,'url_res' =>json_encode( $ar1)  ]);
      
      $ar['address'] = $deposit_address;
          $ar['coin']=$coin;
          
          
    //   echo $deposit_address;
    }
    
       else if($r->coin=='AUSDT'  )
 {
     
      $user_id= Auth::user()->id;
$deposit_address = DB::table('deposit_address')->where('user_id', $user_id)->where('coin', $r->coin)->count();
 
   if( $deposit_address==0)
   {
 
 $deposit=   $this->getAUSDTAddress();
   $ar['address']=  $deposit;
      $ar['coin']=$r->coin;
   }
   
     $data=   DB::table('deposit_address')->where('user_id', $user_id)->where('coin', $r->coin)->first(); 
   $ar['address']=  $data->address;
    $ar['id']=  $data->id;
    $ar['coin']=$r->coin;
 }
      
      
          else if($r->coin=='Tron'  )
 {
     
      $user_id= Auth::user()->id;
    
     
$deposit_address = DB::table('deposit_address')->where('user_id', $user_id)->where('coin', $r->coin)->count();
    
    
   
   if( $deposit_address==0)
   {
 
 $deposit=   $this->getTronAddress();
   $ar['address']=  $deposit;
      $ar['coin']=$r->coin;
   }
   
     $data=   DB::table('deposit_address')->where('user_id', $user_id)->where('coin', $r->coin)->first(); 
   $ar['address']=  $data->address;
    $ar['id']=  $data->id;
    $ar['coin']=$r->coin;
 }
      
      
         else if($r->coin=="BTC")
          {
                 $ar['address']= $this->getBtcAddress( );
                 
                 
           
    $ar['coin']="BTC";
          }
          else
          {
              
    //   $bn=  $this->getNewAdrress1();
      
      $c=$this->getCoinDT();
                   
           $ar['address']= $this->getBtcAddress( );
    $ar['coin']=$c['name'];
          }
 
  
   
   
   
   $r=array();
   $r['status']=200;
   $r['result']=$ar;
  
    return response()->json($r);
  
   
} 

 

 
   function getAUSDTAddress(  )
{
     
  
 
 $user_id= Auth::user()->id;
    
  


$root_url = 'http://162.255.116.244/ethapi/?q=createUSTTronWallet&w='.$user_id ;

 
 
$response = file_get_contents( $root_url);

 $object = json_decode($response);
 
$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
 $privateKey="";//$object->privateKey;
//   $address=$object->address->base58;
   $publicKeyy="";//$object->publicKey;
  $Mnemonic="";//$object->Mnemonic;
  
DB::table('deposit_address')->insert(['coin'=>"AUSDT", 'user_id' => $user_id, 'address' =>$address,'url_hit' =>  $root_url ,'url_res' =>$response ,'Mnemonic'=>$Mnemonic,'publicKey'=>$publicKeyy,'privateKey'=>$privateKey]);
 
  return $address;	
}




   function getTronAddress(  )
{
     
  
 
 $user_id= Auth::user()->id;
    
  


$root_url = 'http://162.255.116.244/ethapi/?q=createUSTTronWallet&w='.$user_id ;

 
 
$response = file_get_contents( $root_url);

 $object = json_decode($response);
 
$address = 'TJYeasTPa6gpEEfYK6gDSpsBq6J593k9as';
 $privateKey= //$object->privateKey;
  $address=$object->address->base58;
   $publicKeyy= //$object->publicKey;
  $Mnemonic= //$object->Mnemonic;
  
DB::table('deposit_address')->insert(['coin'=>"Tron", 'user_id' => $user_id, 'address' =>$address,'url_hit' =>  $root_url ,'url_res' =>$response ,'Mnemonic'=>$Mnemonic,'publicKey'=>$publicKeyy,'privateKey'=>$privateKey]);
 
  return $address;	
}





public function send_payment()
{
    
    
    $this->sendFund();/*
       $user = Auth::user();
    
    $user_id=$user->id;
  
    
      $trid = DB::table('system_transactions')->insertGetId(
    [ 'user_id' => $user_id ,'coin' => $this->request['coin']  ,'cr' => 0 ,'dr' =>  $this->request['amount_coin']  ,'status' =>"Success" ,'description' =>"Widthdraw Request ". $this->request['toAddress']   ]
);

     
            
     $id = DB::table('exchange_widthdraw')->insertGetId(
    [ 'user_id' => $user_id ,'coin' => $this->request['coin'] ,'amount' => $this->request['amount_coin'],'widthdraw_address' =>  $this->request['toAddress'] ]
);

 



    $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Request submitted successfully";
    $ar['paymentid']=$id;
    return response()->json($ar);
    
    */
    
}



public function add_plan()
{  if(Auth::user()->roll < 8 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
       $user = Auth::user();
    
    $user_id=$user->id;
  
    
      $trid = DB::table('membership')->insertGetId(
    [ 'user_id' => $user_id ,'plan' => $this->request['name'] ,
    'row1' =>  $this->request['row1'] ,
    'row2' =>  $this->request['row2'] ,
    'row3' =>  $this->request['row3'] ,
    'row4' =>  $this->request['row4'],
        'row5' =>  $this->request['row5'],
            'row6' =>  $this->request['row6'],
                'row7' =>  $this->request['row7'],
    'price' =>  $this->request['price']]
);

  


    $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Plan submitted successfully";
    
    return response()->json($ar);
    
    
    
    
}


 
        
    public function add_shop()
{ 
       $user = Auth::user();
    
    $user_id=$user->id;
  
    
      $trid = DB::table('shop')->insertGetId(
    [ 
        
        
    'shop_name' => $this->request['shop_name'],
    'number'=> $this->request['number'],
      'privacy' => $this->request['privacy'],
      'refund_policy'=> $this->request['refund_policy'],
	  'about_shop'=> $this->request['about_shop'],
      'terms_policy'=> $this->request['terms_policy'],
      'status'=>0
    
   ]
);

  


    $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Add shop successfully";
    
    return response()->json($ar);
    
    
    
    
}
       public function shop_list()
{ 
    
    //$user_id= Auth::user()->id;
 $data=DB::table('shop')->orderBy('id', 'desc')->get()  ;
      
  return response()->json($data );
    
}


        
    public function add_product()
{ 
       $user = Auth::user();
    
    $user_id=$user->id;
  
    
      $trid = DB::table('items')->insertGetId(
    [ 
    'item_name' => $this->request['product_name'],
    'shop_id' => $this->request['shop_id'],
    'shop_name' => $this->request['shop_name'],
    'description' => $this->request['description'],
     'item_price'=> $this->request['amount'],
        'call_to_action'=> $this->request['call_to_action'],
         'summery' => $this->request['summery'],
         'license'=> $this->request['license'],
         'settings'=> $this->request['settings'],
         'product_sale'=> $this->request['product_sale'],
          'quantity'=> $this->request['quantity'],
          'myfile'=> $this->request['myfile']
         
    
   ]
);
$targetDir = "/uploadfile/uploads/";
if(is_array($_FILES)) {
if(is_uploaded_file($_FILES['myfile']['tmp_name'])) {
if(move_uploaded_file($_FILES['myfile']['tmp_name'],"$targetDir/".$_FILES['myfile']['name'])) {
//echo "File uploaded successfully";
}
}
}



    $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Add product successfully";
    
    return response()->json($ar);
    
    
    
    
}

public function getdepositreport1(){

 $sql=   'select * , concat( "https://link.pixpay.trade/", transactionid ) as paymentlink from deposit_address INNER  JOIN users
ON deposit_address.user_id = users.id ORDER BY deposit_address.id DESC limit 100';
//  echo $sql;

$data= DB::select($sql);
    //  $data=      DB::table('users')
    //     ->leftJoin('deposit_address', 'users.id', '=', 'deposit_address.user_id')->orderBy('deposit_address.id', 'desc')->take(500)->get();
        
    // $data=   DB::table('deposit_address')->orderBy('id', 'desc')->take(500)->get(); 
     
      return response()->json($data );
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

public function getdepositreportbydate(){


 
         $date= $this->request['getdate'];
//  $data=DB::table('system_transactions')->where('created_at', $date)->orderBy('id', 'desc')->get()  ;

    
 $sql=   'select deposit_address.*, users.email, concat( "https://link.pixpay.trade/", transactionid ) as paymentlink from deposit_address JOIN users ON deposit_address.user_id = users.id Where deposit_address.created_at like "'.$date.'%" ORDER BY deposit_address.id DESC limit 100';
//  echo $sql;

$data= DB::select($sql);
    //  $data=      DB::table('users')
    //     ->leftJoin('deposit_address', 'users.id', '=', 'deposit_address.user_id')->select('select * ,concat("https://link.pixpay.trade/", deposit_address.transactionid ) as paymentlink from deposit_address')->orderBy('deposit_address.id', 'desc')->take(50)->get();
        
    // $data=   DB::table('deposit_address')->orderBy('id', 'desc')->take(500)->get(); 
     
      return response()->json($data );
}

public function SearchAmountForm(Request $request){
 $amount=   $request->amount;

 $sql=   'select deposit_address.*, users.email , concat( "https://link.pixpay.trade/", transactionid ) as paymentlink from deposit_address INNER  JOIN users
ON deposit_address.user_id = users.id Where deposit_address.amount='.$amount.' ORDER BY deposit_address.id DESC limit 100';
//  echo $sql;

$data= DB::select($sql);

    // $data=   DB::table('deposit_address')->where('amount',$amount)->orderBy('id', 'desc')->take(50)->get(); 
     
      return response()->json($data );
}

public function SearchTransactionForm(Request $request){
    
     $transaction_id=   $request->transaction_id;
      
 $sql=   'select deposit_address.*, users.email , concat( "https://link.pixpay.trade/", transactionid ) as paymentlink from deposit_address INNER  JOIN users
ON deposit_address.user_id = users.id Where deposit_address.transactionId="'.$transaction_id.'" ORDER BY deposit_address.id DESC limit 100';
//  echo $sql;

$data= DB::select($sql);      
             
    // $data=   DB::table('deposit_address')->where('transactionId',$transaction_id)->orderBy('id', 'desc')->take(50)->get(); 
     
      return response()->json($data );
}


       public function product_list(Request $request)
{ 
    
    //$user_id= Auth::user()->id;
 $data=DB::table('items')->where('shop_id',$request->id)->orderBy('Item_ID', 'desc')->get()  ;
      
  return response()->json($data );
    
}

public function shop_by_id(Request $request)
{ 
    
    
        
    
    //$user_id= Auth::user()->id;
 $data=DB::table('shop')->where('id',$request->id)->orderBy('id', 'desc')->first()  ;
      
  return response()->json($data );
    
}


public function product_by_id(Request $request)
{ 
    
    
        
    
    //$user_id= Auth::user()->id;
 $data=DB::table('items')->where('item_ID',$request->item_ID)->orderBy('item_ID', 'desc')->first()  ;
      
  return response()->json($data );
    
}
public function shop_update(Request $request)
    {
                   
        $ar=array();
  
        $ar['Error']=true;
        
        
$updateDetails = [
    'shop_name' => $request->get('shop_name'),
    'number' => $request->get('number'),
     'privacy' => $request->get('privacy'),
    'refund_policy' => $request->get('refund_policy'),
     'about_shop' => $request->get('about_shop'),
    'terms_policy' => $request->get('terms_policy')
];

DB::table('shop')
    ->where('id', $request->get('id'))
    ->update($updateDetails);
        



   
 
       $ar['message']="Successfully updated the shop.";
              
              
             $ar['Error']=false;
             
 return response()->json($ar);
    }
    public function product_update(Request $request)
    {
                   
        $ar=array();
  
        $ar['Error']=true;
       
$updateDetails = [
    
    'item_name' => $request->get('item_name'),
    'description' => $request->get('description'),
     'call_to_action' => $request->get('call_to_action'),
    'summery' => $request->get('summery'),
     'item_price' => $request->get('item_price'),
     'shop_id' => $request->get('shop_id'),
    'shop_name' => $request->get('shop_name'),
     'license' => $request->get('license'),
    'settings' => $request->get('settings'),
     'product_sale' => $request->get('product_sale'),
      'quantity' => $request->get('quantity'),
    
];

DB::table('items')
    ->where('item_ID', $request->get('item_ID'))
    ->update($updateDetails);
        
       

   
 
       $ar['message']="Successfully updated the Product.";
              
              
             $ar['Error']=false;
             
 return response()->json($ar);
    }
    
        public function deleteshop(Request $request)

    {
         $ar=array();
        $task = Shop::find($this->request['id']);

       $task->delete();


        $ar['message']=" Deleted successfully.";
              
              
        $ar['Error']=false;

        return response()->json($ar);

    }
    
     
        public function deleteproduct(Request $request)

    {
         $ar=array();
        $task = Items::find($this->request['item_ID']);

       $task->delete();


        $ar['message']=" Deleted successfully.";
              
              
        $ar['Error']=false;

        return response()->json($ar);

    }
    
        public function deleteevents(Request $request)

    {
         $ar=array();
        $task = Events::find($this->request['id']);

       $task->delete();


        $ar['message']=" Deleted successfully.";
              
              
        $ar['Error']=false;

        return response()->json($ar);

    }
    
       public function productupload(Request $request)
{
       
    $ar=array();
        $ar['Error']=true;
          // $user = 2;
       $updateDetails = [
    
    'img' => $request->get('document'),
    
    
];

DB::table('items')
    ->where('item_ID', $request->get('id'))
    ->update($updateDetails);
        

//  $a=$request->document_type;
//  $user->$a=$request->document;
 
    // $user->save();
    
       $ar['message']="Successfully updated the product.";
      
         $ar['Error']=false;
             
 return response()->json($ar);
    

}


 
 
 public function post_notification_url(Request $request)
{  

            
                $user = Auth::user();
                
              $user->notification_url=$request->notification_url;
                  $user->save();
              
       
        return response()->json($user->only(['notification_url' ]));
        
    }
 
 
 
 
 
 
 
 
 
public function get_notification_url()
{  

            
                $user = Auth::user();
                
              
       
        return response()->json($user->only(['notification_url' ]));
        
    }
 
   public function FiatBalance()
{  

            
                $user = Auth::user();
                
              
       if(is_null($user->brl))
       $user->brl=0;
       
       $user->save();
       
        return response()->json($user->only(['brl' ]));
        
    }
        
}