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


use App\Notifications\Login;


class AdminCtrl extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        
    
            
     $this->private_blockchain_host="http://159.65.220.60/btcrpc/";
       $this->ip="http://159.65.220.60";          
    }
 
 
 
 
       public function getuserinfo()
{ 
    
    $user = Auth::user() ;


 
      
  return response()->json($user );
    
}    



 
public function getlogo()
{  

      $ss  =System_Settings::first();
      
  return response()->json($ss );
    
}   




  public function postlogo(Request $request)
{
    $user = Auth::user();
       $ar=array();
        $user1=$user->id;
    if($user1< 9 )
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
    
    
    
    
function getBtcAddress( )
{
    
 
 $user_id= Auth::user()->id;
    
     
    $deposit_address = DB::table('deposit_address')->where('user_id', $user_id)->count();
    
    
   
   if( $deposit_address==0)
   {
 
 
        $this->setBtcAddress($user_id );
   
   }
  
   $data=   DB::table('deposit_address')->where('user_id', $user_id)->where('coin', "Bitcoin")->get()->first(); 
  
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
 //var_dump( $call);
$object = json_decode($response);


DB::table('deposit_address')->insert(['coin'=>"Bitcoin", 'user_id' => $user_id, 'address' => $object->address,'url_hit' => $call ,'url_res' =>$response ]);

 
 DB::table('exchange_btcaddress')->insert(['xpub'=>$my_xpub , 'user_id' => $user_id, 'address' => $object->address ]);

 
 
     
  return $object->address ;		    


}

          
    
          public function LedgetTransactionsdeposits()
{ 
    
    $user_id= Auth::user()->id;
 $data=DB::table('system_transactions')->where('user_id',$user_id)->where('type', 'credit')->orderBy('id', 'desc')->take(50)->get()  ;
      
  return response()->json($data );
    
}    
    
    
          public function getorders()
{ 
    
    $user_id= Auth::user()->id;
 $data=DB::table('swap_coin')->get()  ;
      
  return response()->json($data );
    
}    




       public function LedgetTransactionswidthdraws()
{ 
    
    $user_id= Auth::user()->id;


 $data=DB::table('system_transactions')->where('user_id',$user_id)->where('type', 'debit')->orderBy('id', 'desc')->take(50)->get()  ;
      
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
 $data=DB::table('system_transactions')->where('user_id',$user_id)->orderBy('id', 'desc')->take(50)->get()  ;
      
  return response()->json($data );
    
}


 


         public function LedgetUsers()
{ 
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
    //$user_id= Auth::user()->id;
 $data=DB::table('users')->orderBy('id', 'desc')->take(50)->get()  ;
      
  return response()->json($data );
    
}

    public function KYCUsers()
{ 
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
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
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
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
    
    
      if(Auth::user()->roll < 9 )
        {
   $this->request->user()->token()->revoke();
   
   
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
     
     
    
    $deposit=  $this->getBtcAddress( );
   
    $ar['address']= $deposit;
    $ar['coin']="Bitcoin";
 
 
  
   
   
   
   $r=array();
   $r['status']=200;
   $r['result']=$ar;
  
    return response()->json($r);
  
   
} 

 

 
 



public function send_payment()
{ 
       $user = Auth::user();
    
    $user_id=$user->id;
  
    
      $trid = DB::table('system_transactions')->insertGetId(
    [ 'user_id' => $user_id ,'coin' => $this->request['coin']  ,'cr' => 0 ,'dr' =>  $this->request['amount_coin']  ,'status' =>"Success" ,'description' =>"Widthdraw Request ". $this->request['to']   ]
);

     
            
     $id = DB::table('exchange_widthdraw')->insertGetId(
    [ 'user_id' => $user_id ,'coin' => $this->request['coin'] ,'amount' => $this->request['amount_coin'],'widthdraw_address' =>  $this->request['to'] ]
);

 



    $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Request submitted successfully";
    
    return response()->json($ar);
    
    
    
    
}



public function add_plan()
{ 
    
    
    
       $user = Auth::user();
       
       
         if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
        
    
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


public function complete_widthdraw_coin_process()
{ 
    
    
      if(Auth::user()->roll < 8 )
        {
  
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
        
       $user = Auth::user();
    
    $user_id=$user->id;
  
    
     // $trid = DB::table('exchange_btcaddress')->insertGetId( [  'xpub' => $this->request['xpub']] );

  
  
  
    
        if($this->request['status']=="Complete")
        {
            
            /////////////////////////////////////////////
            
                    
   $ar=array();
            
         
         
         $widthdraw=DB::table('exchange_widthdraw')->where('status','Pending')->where('id',  $this->request['widid'])->first()  ;
         
         
    $bn   = Bnext_Wallet::where('user_id', $widthdraw->user_id)->first();
  
  
 
  $user_id= Auth::user()->id;
  $bn_admin = Bnext_Wallet::where('user_id',$user_id)->first();
  
  
  
    

 $toAddress=  $bn_admin->address;
   
    
    
       $amount=$widthdraw->amount;
       
$ch = curl_init();

$ar=array();


$ar['wallet_name']=  $bn->wallet_name;//  $this->request['wallet_name']; 

$ar['toAddress']=$toAddress;
$ar["amount"]=$amount;
$json=json_encode($ar);
  
$u=$this->private_blockchain_host.'?q=sendfund&w='.$ar['wallet_name'].'&a='.$ar['toAddress'].'&amount='.$ar["amount"];
 



curl_setopt($ch, CURLOPT_URL, $u);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, $json );



$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Accept: application/json';
$headers[] = 'Password: '.$bn['password'];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result =  curl_exec($ch );
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

 
            
            
            /////////////////////////////
            
            
 


        $updateDetails = [
    'status' =>  "Complete",
     'widthdraw_transaction_id' =>  "",
     'transaction_dump' => $result ,
      'approve_by' =>$user_id,
        'remark_message' => $result 
        ];

DB::table('exchange_widthdraw')
    ->where('id', $this->request['widid'])
    ->update($updateDetails);
    
    
    $r=json_decode($result);
    
    
    
      
        $updateDetails = [
    'status' =>  "Complete",
     'widthdraw_transaction_id' => $r->txid ,
     'transaction_dump' => $result ,
      'approve_by' =>$user_id,
        'remark_message' => $result
     
];

DB::table('exchange_widthdraw')
    ->where('id', $this->request['widid'])
    ->update($updateDetails);
    
    
   


}
           

        
        
        
        

    $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Successfully".$this->request['id'];
    
    return response()->json($ar);
    
    
    
    
}
 
public function complete_deposit_process(Request $request)
{ 
    
    
    
    
      $data = array( 'content'=> ' Success ','subject'=>  ' Success '  );
         
        Mail::send('mail', $data, function($m) {
          
 
        
         $deposit=DB::table('deposit')->where('id',  $this->request['did'])->first()  ;
 
      
$duser_id= $deposit->user_id;

               $u  = User::where('id',$duser_id) ->first();
      
 
$email= $u->email;

            $m->to($email,$email )->subject
            (' Success ');
            
           
           
         

      });
      
  
      
      
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
   $r=Auth::user()->roll;
   
 //  $r=11;
   
      if($r >  8 )
        {
          
          $deposit=DB::table('deposit')->whereNull( 'request'   )->where('id',  $this->request['did'])->first()  ;
     
     
        }
        
          if($r > 10 )
        
        {
           
         $deposit=DB::table('deposit')->where('id',  $this->request['did'])->first()  ;
       
            
        }
        
        
      if(!isset(   $deposit))
      {
    
        

    $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Request was already completed" ;
    
    return response()->json($ar);
    
    
      }
      
      
      
      
      
      
      
      
      
        if($this->request['status']=="Complete")
        {


     
        
       $user = Auth::user();
    
    $user_id=$user->id;
  
    
 
 
        
    
$duser_id= $deposit->user_id;

    
  $updateDetails = [
    'status' =>  $this->request['status'],
       'request' =>  "Complete",
     'transaction_id' => $this->request['transaction_id'],
        'remark_message' => $this->request['remark_message'] 
     
];

$s=DB::table('deposit')
    ->where('id', $this->request['did'])
    ->update($updateDetails);
        
  
  
  
  
        $bv2=new BTVCv2Ctrl(  $request);
         
        
         
    $bn1 = Bnext_Wallet::where('user_id',$duser_id)->first();
  
  
    
            
   $ar=array();
   
   
 
  $user_id= Auth::user()->id;
                 
                 
  $bn_admin = Bnext_Wallet::where('user_id',$user_id)->first();
  
 $toAddress=  $bn1->address;
  
   
     //  $amount=(float)$this->request['amount']   ; 
     
     
       $amount=$deposit->amount;
       
       
       
$ch = curl_init();

$ar=array();


$ar['wallet_name']=  $bn_admin->wallet_name;//  $this->request['wallet_name']; 

$ar['toAddress']=$toAddress;
$ar["amount"]=$amount;

$json=json_encode($ar);
  
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

$result =  curl_exec($ch );
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

 
      
      
        $updateDetails = [
    'status' =>  "Complete",
     'transaction_id' =>  "",
        'request' =>  "Complete",
     'transaction_dump' => $result ,
      'approve_by' =>$user_id,
        'remark_message' => $result
     
];

DB::table('deposit')
    ->where('id', $this->request['did'])
    ->update($updateDetails);
    
    
    $r=json_decode($result);
    
    
    
      
        $updateDetails = [
    'status' =>  "Complete",
     'transaction_id' => $r->txid ,
     'transaction_dump' => $result ,
        'request' =>  "Complete",
      'approve_by' =>$user_id,
        'remark_message' => $result
     
];

DB::table('deposit')
    ->where('id', $this->request['did'])
    ->update($updateDetails);
    
    
    //send approve email 
     
         // 
     
      $data = array( 'content'=> 'Deposit request has been Accepted','subject'=>  'Deposit request has been Accepted'  );
         
        Mail::send('mail', $data, function($m) {
          
          
        
        
         $deposit=DB::table('deposit')->where('id',  $this->request['did'])->first()  ;
      
      
      
$duser_id= $deposit->user_id;

               $u  = User::where('id',$duser_id) ->first();
      
      
$email= $u->email;

            $m->to($email,$email )->subject
            ('Deposit request has been Accepted');
            
            
           
         

      });
      
         
        Mail::send('mail', $data, function($m) {
          
          
        
        
         $deposit=DB::table('deposit')->where('id',  $this->request['did'])->first()  ;
      
      
      
$duser_id= $deposit->user_id;

               $u  = User::where('id',$duser_id) ->first();
      
      
$email= "irsantana@msn.com";

            $m->to($email,$email )->subject
            ('Deposit request has been Accepted');
            
            
           
         

      });
      
      
      
}

else
{
    
      $updateDetails = [
    'status' =>  $this->request['status'],
       'request' =>  "Complete",
     'transaction_id' => $this->request['transaction_id'],
        'remark_message' => $this->request['remark_message'] 
     
];

$s=DB::table('deposit')
    ->where('id', $this->request['did'])
    ->update($updateDetails);
        
        
         
    //send dis approve email 
    
    
      $data = array( 'content'=> 'Deposit request has been Disapproved.','subject'=>  'Deposit request has been Disapproved.'  );
         
        Mail::send('mail', $data, function($m) {
          
          
         
         $deposit=DB::table('deposit')->where('status','Pending')->where('id',  $this->request['did'])->first()  ;
      
      
$duser_id= $deposit->user_id;

              $u  = User::where('id',$duser_id) ->first();
      
$email= $u->email;
            $m->to($email,$email )->subject
            ('Deposit request has been Disapproved.');
                   
        
            
           
         

      });
    
}
           

        
        
        
        

    $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "Successfully";
    
    return response()->json($ar);
    
    
    
    
}

function sendemail()
{
     
 
      
}



     
     function sendFundtoaddress($toAddress, $amount)
    {
         
   $ar=array();
                 $user_id= Auth::user()->id;
  $bn1 = Bnext_Wallet::where('user_id',$user_id)->first();
  
  //$toAddress=$this->request['toAddress'];
   
     //  $amount=(float)$this->request['amount']   ; 
       
$ch = curl_init();

$ar=array();


$ar['wallet_name']=  $bn1->wallet_name;//  $this->request['wallet_name']; 

$ar['toAddress']=$toAddress;
$ar["amount"]=$amount;
$json=json_encode($ar);
  
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

$result = json_decode(curl_exec($ch));
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 
 return $result;
 
   
  
   // return $toAddress. "---". $amount; 
 
    }
     
     
     
     
     
     
public function add_xpub()
{ 
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
        
       $user = Auth::user();
    
    $user_id=$user->id;
  
    
      $trid = DB::table('exchange_btcaddress')->insertGetId( [  'xpub' => $this->request['xpub']] );

  


    $ar=array();
    $ar['Error']=false; 
    
    $ar['Message']=  "XPUB  submitted successfully";
    
    return response()->json($ar);
    
    
    
    
}
     public function getxpubs()
{ 
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
    
     $data= DB::table('exchange_btcaddress')->distinct()->get();
// $data=DB::table('exchange_btcaddress')->orderBy('id', 'asc')->get()  ;
      
  return response()->json($data );
    
}




     public function deletexpub()
{ 
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
        
    $ar=array();
    $ar['Error']=false; 
    
    $c = DB::table('exchange_btcaddress')->where('xpub',   $this->request['xpub'])->count();
    
if($c==1)
{
    DB::table('exchange_btcaddress')->where('xpub',   $this->request['xpub'])->delete();
     
      $ar['message']=    "Delete successfully";
}
   
 else
 {
      $ar['message']=    "Delete Failure because it may be used";
 }
 
    
    return response()->json($ar);
    
    
}




     public function deleteuser()
{ 
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        $id=$this->request['user_id'];
          $otp=Auth::user()->delete_user_otp;
          
                if($otp!=$this->request['otp'])
         {  
           $ar=array();
      $ar['Error']=true;
      $ar['message']="Please Correct OTP";
       return response()->json($ar);
         }
         
         else if($otp==$this->request['otp'])
         {
               
        
    $ar=array();
    $ar['Error']=false; 
    
    $c = DB::table('users')->where('id',   $id)->count();
    
if($c==1)
{
    DB::table('users')->where('id',   $this->request['user_id'])->delete();
     
      $ar['message']=    "Delete successfully";
}
   
 else
 {
      $ar['message']=    "Delete Failure because it may be used";
 }
 
    
    return response()->json($ar);
    
    
}

}

          
}