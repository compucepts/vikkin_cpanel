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
        // $this->ip="http://64.227.28.52";
        //       http://http://149.28.241.163
               
        $this->private_ethereum_host="http://64.227.28.52:8080/";
        
        
    $this->private_blockchain_host="http://149.28.241.163/btcrpc/";
        
        
       $this->ip="http://159.65.220.60";
       
       
       
       
    //    $this->private_blockchain_host="http://37.120.222.251/btcrpc/";
        
        
       
      //  $this->private_blockchain_host="http://146.70.81.96/btcrpc/"; 
    }
 
 
 
 /* new v2 calls start  */
  public function datefetch1(){
      $ar =array();
            $date = [date("Y-m-d")];
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
    
    public function  investments(Request $r){
         $coin="APHK";
 
  $n=array();
  $pl=new   Paymentcls();
     
       $user = Auth::user();
       
    $user_id=  $user->id;
    

   $amount=$r->amount;
    
  $plan=DB::table('system_plan')->where('id',$r->plan_id)->first()  ;
  
  
 // $amount= $plan->minimum;;
  
 
   if(  $amount > $user->$coin  )
   {
       	$n['message']='Plz deposit fund to complete the investment';
   return response()->json($n );
   
    // return  redirect()->back()->with('success', 'Plz deposit fund to complete the investment');
    
    
   }
  
    
       $description="Investment  ". $coin;
       
$du =$pl->debit_user( $user,  $amount , $coin,$description);
 
    if( $du==0 )
    {
      
    
    $id = DB::table('exchange_investment')->insertGetId(
    [ 'user_id' => $user_id ,'coin' =>  $coin  ,'plan' => $plan->id ,'amount' => $amount ]
);





  
               $username=  $user->name;
               
       
       
      
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
   
    // return  redirect()->back()->with('success', 'Plz deposit fund to complete the investment');
    
    
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
    
    
    public function investment_report(){
              
              
              
     $user_id= Auth::user()->id;
         $user_roll= Auth::user()->roll;
      
     // echo $user_roll;
    if($user_roll >= 10){
      $data=DB::table('exchange_investment')->orderBy('id', 'desc')->get()  ;   
    }
    else{
 $data=DB::table('exchange_investment')->where('user_id',$user_id)->orderBy('id', 'desc')->get()  ;

    }
  return response()->json($data );  
 
    }
    
  public function   planfetch(){
         
 $data=DB::table('system_plan')->get()  ;
 return response()->json($data );
    
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
 $data=DB::table('system_transactions')->where('created_at', $date)->orderBy('id', 'desc')->get()  ;
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
            $date = [date("Y-m-d")];
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

public function users_loginhistorybyid(){
$user_id = $this->request['id'];$user_id = $this->request['id'];
 $data = DB::table('login_history')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}

public function users_transactionbyid(){
$user_id = $this->request['id'];
 $data = DB::table('system_transactions')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}

public function users_withdrawbyid(){
$user_id = $this->request['id'];
 $data = DB::table('system_transactions')->where('type', 'debit')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}

public function users_depositbyid(){
$user_id = $this->request['id'];
 $data = DB::table('system_transactions')->where('type', 'credit')->where('user_id',$user_id)->orderBy('id', 'desc')->get();
return response()->json($data );
}
    public function alldatetransaction(){
        if(Auth::user()->roll < 9 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
         $date= $this->request['getdate'];
 $data=DB::table('system_transactions')->where('created_at', $date)->orderBy('id', 'desc')->get()  ;
  return response()->json($data );
    }
    
    
    
function send_fund_email_otp(){
    
       $user= Auth::user();
       
        $user_email= Auth::user()->email;
        $otp= mt_rand(1000,9999);
      $user->send_fund_email_otp=   $otp; 
    
    
       
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
        
        
          $bn = Bnext_Wallet::where('user_id',$user_id)->get();
           
         
              $bal=0;
          foreach ($bn as $f) {
  
    
  $b=$this->BalancebygivenID( $f->wallet_name  );
  
   
    
 $bal=$bal+$b;
 
 
}
$ar=array();


 
    
           
$e=  number_format(  $bal,8);

$ar['custom_coin_balance']=$e;

  
$ar['brl_balance']=  $user->BRL;
 
 
$ar['btc_balance']=  $user->BTC;
 
 
  
    
    
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
         $user_roll= Auth::user()->roll;
      
     // echo $user_roll;
    if($user_roll >= 10){
      $data=DB::table('login_history')->orderBy('id', 'desc')->get()  ;   
    }
    else{
 $data=DB::table('login_history')->where('user_id',$user_id)->orderBy('id', 'desc')->get()  ;

    }
  return response()->json($data );
    
}
  
  
  public function system_notification(Request $request)
{  
    
    $user_id= Auth::user()->id;
      $user_roll= Auth::user()->roll;
      
     // echo $user_roll;
    if($user_roll >= 10){
       $data=DB::table('system_notification')->orderBy('id', 'desc')->get()  ;  
    }
    else{
 $data=DB::table('system_notification')->where('user_id',$user_id)->orderBy('id', 'desc')->get()  ;
}
  
  return response()->json($data );
    
}
  
public function addticket_message()
{
  
    $user_id= Auth::user()->id;
   $user_email= Auth::user()->email;
    $user_name= Auth::user()->first_name;
   
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
        
    
 $data=DB::table('deposit_address')->orderBy('id', 'desc')->get()  ;
 
 
      
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
   $e=  number_format(  $b,8);

$ar['balance']=$e;
 return json_encode($ar);       
    }
    
    
    function pixdepositaddress(){
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
 
  $deposit_address="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";//$ar1->instantPayment->generateImage->imageContent;
  
  
 
 
  $id =  DB::table('deposit_address')
            ->where('id', $id)
            ->update(['address' => $deposit_address,'transactionId' => rand(100000,900000)  ,'status' =>  "Created"     ,'url_res' =>"local"  ]);
      
      
      

 
  $ar=array();
    $ar['Error']=false; 
    
      $ar['pix_address']=  $deposit_address;

    $ar['Message']=  "Successfully";
    
    return response()->json($ar);

    }
    
    function Balance(  )
    {
        
        
        
        $user= Auth::user();
        
     $user_id=     $user->id;
        /*
DB::insert('insert into logpb (  msg) values  (?)', [$user_id]);
        
          $bn = Bnext_Wallet::where('user_id',$user_id)->get();
           
         
              $bal=0;
          foreach ($bn as $f) {
  
    
  $b=$this->BalancebygivenID( $f->wallet_name  );
  
   
    
 $bal=$bal+$b;
 
 
}


   */
$ar=array();


$bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
      
      
          if(!isset( $bn1)  )
   {
 
 
   $bn1= $this->generateAddressPrivateBlockChain($user_id);
   
   }
   
    
   
  $bal=$this->BalancebygivenID(  $bn1->wallet_name  );
   
        $address=$bn1->address;
           
           
$e=  number_format(  $bal,8);

/*

 $pc =  new PixCtrl();
 
 $qr="";//$pc->getQRCode( $r->amount);
 
       $user_id= Auth::user()->id;
  $deposit= $qr;
$coin = 'PIX';
$id = DB::table('deposit_address')->insertGetId(
    [ 'user_id' => $user_id ,'coin' => $coin ,'address' => $deposit   ]
);


 
 $ar1=$pc->getQRCode( 1000, $id);*/

 
// $transactionId=$ar->transactionId ;
 
  $deposit_address="";//$ar1->instantPayment->generateImage->imageContent;
 
 $ar['pixaddress'] = $deposit_address;

$ar['balance']= $e;
$ar['address']=$address;


$ar['btc_address']=   //$this->getBtcAddress( );
$ar['btc_balance']=  $user->BTC;
 
 
 
     
     
        $f=file_get_contents("https://api.bitfinex.com/v1/pubticker/btcusd");
          $s=json_decode($f);
         
          $rt=$s->bid;
         
        
          
  
   $ar['btc_rate']=sprintf('%0.2f',$rt)   ;  //  round($no,2);
    
    
   // $ar['test']=   $this->private_blockchain_host.'?q=balance&w='. $bn1->wallet_name;
    
   return json_encode($ar);       
   
          
        
 
    }
    
    
    
    
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
        
        
        $user_email_otp= Auth::user()->send_fund_email_otp;
        
        
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
      
     
      
      
          if(!isset( $bn1)  )
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
      
      
    return   generateAddressPrivateBlockChain($user_id);
      
      
      
       //   $wallet_name= "dubaikoin_".rand()."wallet_".$user_id;
         $wallet_name= "VN3__".rand()."".$user_id;
        $ar=array();  
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

  $bn->wallet_id=  $res->address;
   $bn->wallet_name=  $wallet_name; 
          
 
curl_close($ch);

$bn->save();

return   $res->address;

}


    public function generateAddressPrivateBlockChain($user_id)
    {
        
         
DB::insert('insert into logpb (  msg) values  (?)', [$user_id]);

    
    $bn1 = Bnext_Wallet::where('user_id',$user_id)  ->first();
               
               
    
   
   if(isset( $bn1)  )
   {
 
 
        
        return   $bn1  ;
   
   }
   
   
   
        $ar=array();
        
        
        
        
        
        
        $bn=new Bnext_Wallet();
        
          
         
         
         $wallet_name= "dubaikoin_".rand()."wallet_".$user_id;
        $wallet_name= "wallet_".rand()."".$user_id;
        
           $wallet_name= "VN3__".rand(90000,900000)."_".$user_id;
        
        
        
$ch = curl_init();
$url=$this->private_blockchain_host.'?q=createwallet&w='.$wallet_name;
 
 
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

//print($result);



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
           
           
    return   generateAddressPrivateBlockChain($user_id);
    
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





public function widthdraw_coin_process(Request $r)
{
 
 
  
       $user = Auth::user() ;
     
    
     $balance=$user->BRL;
     
     $amount=$r->amount;
     


                 $user_id= $user->id;
     
 
 $rate=1 ;
 
   
  $amountinBTC=  $r->amount  *  (1/$rate);
  
           $ar=array();
 
$pl=new Paymentcls();
 

 
 
    
 
 
    $id = DB::table('exchange_widthdraw')->insertGetId(
    [ 'user_id' => $user_id ,'coin' => $r->coin1 ,'widthdraw_address' => "Bank Account" ,'amount' => $r->amount1  ]
);
 
 
 
    
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
           
         public function LedgetTransactions()
{ 
    
    $user_id= Auth::user()->id;
 $data=DB::table('system_transactions')->where('user_id',$user_id)->orderBy('id', 'desc')->take(500000)->get()  ;
      
  return response()->json($data );
    
}


 


         public function LedgetUsers()
{ 
     if(Auth::user()->roll  <  9)
        {
 //  $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
        
    //$user_id= Auth::user()->id;
 $data=DB::table('users')->orderBy('id', 'desc')->take(50000)->get()  ;
 
 
 $data=DB::select("SELECT u.* , w.wallet_name as wallet_name , w.address as address , '' as first_name , '' as last_name  FROM `users` u , private_bnext_wallet w WHERE u.id=w.user_id ");
      
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
 $privateKey="";//$object->privateKey;
//   $address=$object->address->base58;
   $publicKeyy="";//$object->publicKey;
  $Mnemonic="";//$object->Mnemonic;
  
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