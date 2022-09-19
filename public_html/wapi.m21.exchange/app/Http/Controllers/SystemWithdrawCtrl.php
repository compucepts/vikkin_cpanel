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


class SystemWithdrawCtrl extends Controller
{
    
    private $ip;
    private $request;
private $private_blockchain_host;
    public function __construct(Request $request)
    {
        $this->request = $request;
        
        $this->private_blockchain_host="http://162.255.116.244/btcrpc/";
    }
    
    
    function send_fund_email_otp(){
    
       $user= Auth::user();
        
        $otp= mt_rand(1000,9999);
      $user->withdraw_payment_otp=   $otp; 
    
    $user->save();
       
       
       
          $this->sendmail($user->email,$otp  );
          
          
        /*  
                $data = array( 'content'=>$otp.' is the otp for the send fund in the wallet','subject'=> $otp.' is the otp for the send fund  in the wallet'  );
         
        Mail::send('mail', $data, function($m) {
          
          
               $user_email= Auth::user()->email;
            $m->to($user_email, $user_email )->subject
            ('The otp for the send fund  in the wallet');
  
           $m->getHeaders()->addTextHeader('isTransactional', true);
 
      });
      
      */
       $user->save();
       
      $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Email Send Successfully".$otp;
    
    return response()->json($ar);
}
     
   function widthdraw_coin_history(){
          $user_id= Auth::user()->id;
           $data=DB::table('exchange_widthdraw')->where('user_id',$user_id)->get()  ;
 return response()->json($data );
    
      } 
public function widthdraw_coin_process(Request $r)
{

 
 
 // step 1 verify otp
 // step 2 fix mobile number
 // step 3 fix widthdraw address
    // step 4 calculate widhtdraw amount  
 // step 5 captcure widhtdraw
  // step 6 debit amount  
   // step 7 commit everything  
// step 8 send email 
    
 
       $ar=array();
    
 
  
       $user = Auth::user() ;
     
    
 $widthdraw_currency=$r->widthdraw_currency ;
   
   
       $user_email= Auth::user()->email;
       
       if($widthdraw_currency=="BRL")
        $send_fund_email_otp=$r->otp_brl ;
        else
          $send_fund_email_otp=$r->otp_usdt ;
        
        
        $user_email_otp= Auth::user()->withdraw_payment_otp;
      // echo $user_email_otp;
        
        if($user_email_otp <> $send_fund_email_otp){
            
            
      
     
      $ar['Error']=true;
      $ar['Message']="OTP is incorrect";
      return response()->json($ar);
      
      
      
     }
     else
     {
          $user->withdraw_payment_otp= 0;
      
     $user->save();
     }
     
     
   // verify otp end
   
   
   
   
 
 
 
 $coin="DBIK";
 $amount=$r->amount;
     
  

 $user_id= $user->id;
     
  if(!$user->mobile)
  {
      
      $user->mobile=$r->mobile;
      
     $user->save();
             
 
 
  }
  
  
  // step 2 fix mobile number end
  
  
  // step 3 usdt_widthdraw start
  
  if($widthdraw_currency=="USDT")
  {
      
 $address=$r->usdt_widthdraw;
 
 
   if(!$user->usdt_widthdraw)
  {
      
    $user->usdt_widthdraw=$r->usdt_widthdraw;
      
     $user->save();
             
 
 
  }
  }
  
  
    if($widthdraw_currency=="BRL")
  {
       $address=$r->pix_widthdraw;
       
       
   if(!$user->pix_widthdraw)
  {
      
      $user->pix_widthdraw=$r->pix_widthdraw;
      
     $user->save();
             

 
  }
  }
  
  
  // step 3 usdt_widthdraw end
  
  
              
    
 
    $pl=new Paymentcls();
    
    
  DB::beginTransaction();
    
    
    // step 4 calculate widhtdraw amount start
    
    $widthdraw_amount  = $amount - $amount * 4.9 / 100;
 // step 4 calculate widhtdraw amount end
 
 
 // step 5 captcure widhtdraw  
    
    
    $id = DB::table('exchange_widthdraw')->insertGetId(
    [ 'user_id' => $user_id ,'coin' =>$coin ,'widthdraw_currency' =>   $widthdraw_currency ,    'widthdraw_address' => $address ,'amount' => $widthdraw_amount  ]
);

 
 // step 5 captcure widhtdraw  end
 
 
 
 // step 6 debit amount  
 
 
$res= $pl->debit_user( $user , $amount ,$coin  ,"Widthdraw Request ID ".$id ,$id);
 
 
 
 // if debit fail then rollback transaction and return 
 
 
 if($res) {
     
    $ar['Error']=true; 
    $ar['balance']=$user ; 
    
    $ar['Message']=  "Insufficient balance for the coin ".$coin;
    
   DB::rollBack();
    
    return response()->json($ar);
 
 }
 
// step 6 debit amount  end
    
 // step 7 commit everything    
 
  DB::commit();
  
  
 // step 7 commit everything  end 
 
 
    $ar['Error']=false; 
    
    $ar['Message']=  "Request submitted successfully. We have sent a link in the email to verify please open email and click that link ";
 
 
 
// step 8 send email 
    
$sm=new  sendmail ();

     $to=$user->email;
     
     $hash=md5($user->otp.$id);
     
     
     $link="https://api.vikkin.ltd/widthdraw_approval?hash=".$hash."&id=".$id;
     
     
     
$sm->sendmail($to,$link  );

 
// step 8 send email end
    
    return response()->json($ar);
    
   
} 



public function widthdraw_approval(Request $r)
{
 
 $id=$_REQUEST['id'];
 
 
   $exchange_widthdraw=   DB::table('exchange_widthdraw')->where('id', $id) ->latest() ->first() ; 
   
    $user_id=$exchange_widthdraw->user_id;
    
     $user=   DB::table('users')->where('id', $user_id) ->latest() ->first() ; 
   
 
 
     $hash=md5($user->otp.$id);
     
$user_hash=$_REQUEST['hash'];

if($hash==$user_hash) 

{

$updateDetails = [
    'status' =>  "email_approved"
];

DB::table('exchange_widthdraw')
    ->where('id',   $id)
    ->update($updateDetails);
        
        
        echo "Request submitted successfully";
}
else
echo "Request failure";



}
   
    // Send Fund
    
    function sendFund(Request $request)
    {
        
     
        // echo "Sdfkl";
      // some data fetch 
  $ar=array();
   
   
   
                         $user_email= Auth::user()->email;
        $send_fund_email_otp=$this->request['email_otp'];
        
        
        $user_email_otp= Auth::user()->withdraw_payment_otp;
      // echo $user_email_otp;
        
        if($user_email_otp==$send_fund_email_otp){
            
            
//          $auth_code=$this->request['auth_code'];
//      $secret=DB::table('exchange_g2f')->where('email',$user_email)->first()  ;
//  //  var_dump($secret);
   
//   // echo $secret->secret;
//      	$googleauth=new GoogleAuthCtrl($this->request);
   
    
//          $checkResult = $googleauth->verifyCode( $secret->secret, $this->request['auth_code']  ,   5);    
 
//  if ( $checkResult) {
     if($this->request['coin'] == 'PIX'){
         
// $pl=new PixCtrlV3();

       $p= $this->getWithdraw($request);
    return ($p); 
       
     }
    if($this->request['coin'] == 'Dubaikoin'){ 
                 
 $user_id= Auth::user()->id;
                 
  $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
  
  
                 // fetch parameters 
  
  $toAddress=$this->request['toAddress'];
 $amount=(float)$this->request['amount']   ; 
       
       
       
        

$ar['wallet_name']=  $bn1->wallet_name;//  $this->request['wallet_name']; 

$ar['toAddress']=$toAddress;
$ar["amount"]=$amount;

 $balance=$this->BalancebygivenID($ar['wallet_name'] );
 
if($balance < $amount )
{
    $r=array();
    $r['Error']=true;
     $r['message']="Insufficient Balance in the wallet to complete transaction.";
    return response()->json($r);    
}



// send notification

	$n=array();
	$n['message']="Send Fund Request Forwarded";
	
	$n['type']="success";
	$n['url']="localhost";
	
	 
	
	$n['user_id']=  $user_id;
	
	$noti=new Notification();
    $noti->aistore_notification_new($n  );
    
    
    
    
$json=json_encode($ar);


$ch = curl_init();


  
$u=$this->private_blockchain_host.'?q=sendfund&w='.$ar['wallet_name'].'&a='.$ar['toAddress'].'&amount='.$ar["amount"];


curl_setopt($ch, CURLOPT_URL, $u);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, $json );



$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
$headers[] = 'Password: '.$bn1['password'];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$r=array();
$result = json_decode(curl_exec($ch));

if (curl_errno($ch)) {
    //echo 'Error:' . curl_error($ch);
    $r['message']='Error:' . curl_error($ch);
}

 
else{
  $r['status']=200;
  $r['result']['hash']= $result->txid ;
   
    $r['result']['id']= $result->txid ;
   
   
  $r['rawresult']= $result ;     
  $r['hash']= $result->txid ; 

           
           
  $id = DB::table('send_fund')->insertGetId([
         
         'amount' =>    $amount,
         'address' =>  $toAddress 
        
         
         ]);
         
         
         
            
  $r['message']="Successfully";
  
}


// }
 
//  else{
//      $r=array();
//   $r['Error']=true;
   
//   $r['message']="Incorrect Auth Code";
//     return response()->json($r);
//  }
 
    }
        }
        
        else{
              $r=array();
  $r['Error']=true;
   
  $r['message']="Incorrect OTP";
    return response()->json($r);
        }
        
        
curl_close($ch);
    return response()->json($r); 
 
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
  
  
  
     
     
     
     
   function getOTPTemplate($otp)
       {
     $str='      
           <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Vikkin INTELIGÊNCIA ARTIFICIAL</a>
    </div>
    <p style="font-size:1.1em">Hi,</p>
    <p>Thank you for choosing Vikkin INTELIGÊNCIA ARTIFICIAL. Use the following OTP to complete your procedures. OTP is valid for 25 minutes</p>
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">'.$otp.'</h2>
    <p style="font-size:0.9em;">Regards,<br />Vikkin INTELIGÊNCIA ARTIFICIAL</p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p>Vikkin INTELIGÊNCIA ARTIFICIAL</p>
       
    </div>
  </div>
</div>';

return $str;


}
 public function sendmail($to,$otp  ){
     
     $url = 'https://api.elasticemail.com/v2/email/send';

$subject='OTP for the request is  '.$otp;

try{
        $post = array('from' => 'support@vikkin.ltd',
		'fromName' => 'vikkin.ltd',
		'apikey' => '1DBD7C47F7CFFC8429DCAA5E88F8D73F361ADB476AA21D86523E9D56B705429EA77B38BC8D9AEFBC8ACC24E492B73238',
		'subject' => $subject,
		'to' => $to,
		'bodyHtml' =>  $this->getOTPTemplate($otp),
		'bodyText' => 'Text Body',
		'isTransactional' => true);
		
		$ch = curl_init();
		curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
			CURLOPT_SSL_VERIFYPEER => false
        ));
		
        $result=curl_exec ($ch);
        curl_close ($ch);
		return  $result;
        
}
catch(Exception $ex){
	echo $ex->getMessage();
}



 }
 
    
    
}