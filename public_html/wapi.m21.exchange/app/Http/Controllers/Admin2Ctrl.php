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


class Admin2Ctrl extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware('auth');
              
    }
 
 
 public function getOTP($user_id)
 {
     
     return 22335;
 }
  
  
     
     
     
      public function getuserinfo($user_id)
{ 
    
   
     $user=   DB::table('users')->select("email","username","USDT","DBIK","BRL"    ,'pix_widthdraw' ,'dbik_widthdraw')->where('id', $user_id) ->first(); 
     
     

 
      
  return response()->json($user );
    
}    












  
      public function speical_robot_submit( )
{ 
     
  
     $otp=$this->request['otp'];
        
           $pl=new Paymentcls();
    
        
if( $otp  <> 22335)

{
          
          
          
            $n=array();
          $n['message']=' Incorrect OTP'; 
   return response()->json($n );
}
        
        
        
        
        
   $coin=  'DBIK';
    $amount=$this->request['amount'];
    
    $plan_name= "Special Robot";
    
     
   $user_id= $this->request['user_id'];
   
   
    
		$n['message']= "The purchase of special robot ID "  ;
    
$user = DB::table('users')->where('id',$user_id)->first();
    $username=  $user->username;
                 
     $reference="special_robot_Purchase";
     
  
 
 
 $paymentcls=new Paymentcls();
 
 
 
         
               	
   if(  $amount   >  $user->$coin  )
   {
       	$n['message']='Plz deposit fund to complete the investment. Your current balance is only '.$user->$coin ." ".$coin;
       		$n['type']="failed";
   return response()->json($n );
  
   }  
                  
         
        $res=  $pl->debit_user( $user ,  $amount ,$coin ,'Debit for the purchase of special robot' ,$reference);
        
        if( $res) 
        {
             	$n['message']='Plz deposit fund to complete the investment. Your current balance is only '.$user->$coin ." ".$coin;
       		$n['type']="failed";
   return response()->json($n );
        }
        
        else
        {
            
            
            	$n['message']= "Purchase of    ".$reference  ;
            	
            	
          $id = DB::table('exchange_investment')->insertGetId(
    [ 'user_id' => $user_id ,'coin' =>  $coin,'license_fee' =>   0  ,'plan' =>  22,'amount' => $amount ,'description' => 	$n['message'], 'plan_name' =>  "Special robot",
    'Status_updated_at' =>  Carbon::now() ]
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
 
 return response()->json($n );
 
     
    
    
    
    
        
    
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
 //$data=DB::table('users')->orderBy('id', 'desc')->get()  ;
 
  
      
 // return response()->json($data );
  
  
  
     $ar=array();
      
        
      
      $ar['users_BRL']=DB::table('users')->where("BRL",'>',0)->orderBy('id', 'desc')->get()  ;;
     
      
      $ar['users_USDT']=DB::table('users')->where("USDT",'>',0)->orderBy('id', 'desc')->get()  ;;
     
      
      $ar['users_DBIK']=DB::table('users')->where("DBIK",'>',0)->orderBy('id', 'desc')->get()  ;;
      
      $ar['users_referral_DBIK']=DB::table('users')->where("referral_DBIK",'>',0)->orderBy('id', 'desc')->get()  ;;
      $ar['users_referral_bonus_dbik']=DB::table('users')->where("referral_bonus_dbik",'>',0)->orderBy('id', 'desc')->get()  ;;
     
       return response()->json($ar);
       
       
       
       
    
}
    
    
      public function setpassword(Request $request) {
     
     
     $user = Auth::user() ;
     
      if( $user->roll <  9 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
        }
        
        $user_id= $user->id;
        
        $otp=$request->otp;
        
        
        
       $ar=array();
      
        
        if($otp <> $this->getOTP($user_id))  
        {
            
    $ar['message']="Password did not set successfully.";
       
 
  
      $ar['Error']=true;
     
       return response()->json($ar);
        }
        
        
        if($request->new_password <> $request->confirm_password )
        {
            
            $ar['message']="Password did not matched with confirm password.";
       
 
  
      $ar['Error']=true;
     
     
       return response()->json($ar);
        }
        
        
        
        
        
      $user = User::find($this->request->user_id);
     
     
       if(isset($user))
      {
          
          
    $user->password = bcrypt($request->new_password);
  $user->save();
             
             $ar['message']="Password set successfully.";
                
                
            }
            else
            {
                
                
             
             $ar['message']="Password did not set successfully.";
            }
 
 
  
      $ar['Error']=false;
     
       return response()->json($ar);
     
   }
      
    
    
    
          public function LedgetTransactionsdepositsWithCoin()
{ 
    
   



  $ar=array();
    $ar['error']=false; 
 
    
     
    
    
$ar['transaction_usdt']= DB::table('wallet_transactions')->where("description" ,"like","%Credit against the transaction id%")  ->where('type', 'credit')->where('user_id','<>', 159)
->where('coin', 'USDT')->orderBy('created_at', 'desc')  ->get()  ;
      
    
     
    
$ar['transaction_brl']= DB::table('wallet_transactions') ->where('type', 'credit')->where('coin', 'BRL')->orderBy('created_at', 'desc') ->get()  ;
      
    
$ar['transaction_dbik']= DB::table('wallet_transactions') ->where('type', 'credit')->where('coin', 'dbik')->orderBy('created_at', 'desc')  ->get()  ;
      
  
    return response()->json($ar);
    
}  



          public function fetchrobot_bonusAdmin()
{ 
    
   



  $ar=array();
    $ar['error']=false; 
 
    
     
    
    
//$ar['robot_bonus_income']= DB::table('wallet_transactions')->where("description" ,"like","%Claim of bonus income%")->where("type","credit") ->where("amount",">",0)->orderBy('created_at', 'desc')  ->get()  ;
      
    
      
  $qr="select    w.*,  u.email  FROM wallet_transactions  w ,users u where w.user_id=u.id and description like '%Claim of bonus income%' and type='credit' and date(w.created_at) =CURDATE()  and amount > 0   order by w.id desc  ";
 
 
$ar['robot_bonus_income']= DB::select($qr) ;
   
      
  
    return response()->json($ar);
    
}  
    
    
    
          public function fetchreferral_bonusAdmin()
{ 
    
   



  $ar=array();
    $ar['error']=false; 
 
    
     
    
    
//$ar['referral_bonus_income']= DB::table('wallet_transactions')->where("description" ,"like","%Claim of referral bonus income%")->where("type","credit") ->where("amount",">",0)->orderBy('created_at', 'desc')  ->get()  ;
      
    
           
  $qr="select    w.*,  u.email  FROM wallet_transactions  w ,users u where w.user_id=u.id and description like '%Claim of referral bonus income%' and date(w.created_at) =CURDATE() and type='credit' and amount > 0   order by w.id desc  ";
 
 
$ar['referral_bonus_income']= DB::select($qr) ;
   
      
  
    return response()->json($ar);
    
}  





          public function fetch_bonus_claims()
{ 
    
   



  $ar=array();
    $ar['error']=false; 
 
    
     
     
      
    
$ar['transactions']= DB::table('wallet_transactions') ->where('type', 'credit') ->where('description','like' , '%Claim of bonus income%')->orderBy('id', 'desc') ->get()  ;
      
  
    return response()->json($ar);
    
}   

    
    
    
         public function fetch_recovery_robot()
{
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        



 
  $qr="select  * FROM exchange_investment WHERE   plan_name ='robot 11' order by id desc   ";
 
 
   $data = DB::select($qr) ;
   
   
      
  return response()->json($data );
    
}
     
     
     
     
     
     
     
         public function fetch_widthdraw_requests( )
{
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        



 
  $qr="select    ew.*,  u.email  FROM exchange_widthdraw  ew ,users u where ew.user_id=u.id order by ew.id desc  ";
 
 
   $data = DB::select($qr) ;
   
   
      
  return response()->json($data );
    
}
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
         public function fetch_investment ($robot_id)
{
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        


$robot = DB::table('exchange_investment')->where('id',$robot_id) ->first();

 
 $ar=array();
 
 $ar['robot']=$robot;
 
 


      //  this.referral_wallet_transactions=data.referral_wallet_transactions;

        //this.referral_wallet_bonus_transactions=data.referral_wallet_bonus_transactions;

       // this.wallet_transactions=data.wallet_transactions;
        
        
$referral_wallet_transactions = DB::table('referral_wallet_transactions')->where('reference',$robot_id) ->get();

 $ar['referral_wallet_transactions']=$referral_wallet_transactions;
 
$referral_wallet_bonus_transactions= DB::table('referral_wallet_bonus_transactions')->where('reference',$robot_id) ->get();

 $ar['referral_wallet_bonus_transactions']=$referral_wallet_bonus_transactions;
 
 
$wallet_transactions= DB::table('wallet_transactions')->where("user_id",$robot->user_id)->where('reference','like' , '%'.$robot_id . '%') ->get();

 $ar['wallet_transactions']=$wallet_transactions;
 
   
      
  return response()->json($ar );
    
}







     
         public function fetch_investments($user_id)
{
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        



 
  $qr="select  * FROM exchange_investment WHERE user_id=".$user_id."   ";
 
 
   $data = DB::select($qr) ;
   
   
      
  return response()->json($data );
    
}
     




 public function widthdraw_completion_form_submit()
{
    
    
$id= $this->request['id'];



$widthdraw = DB::table('exchange_widthdraw')->where('status','NEW')->where('id',$id)->first();
     
     if(!$widthdraw)    
    {
        $ar=array();
    $ar['Error']=false; 
    
    $ar['message']=  "Some Error or duplicate request";
    
    return response()->json($ar);  
        
    }
    
    
    
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        

 
 
  $user_id= Auth::user()->id;
 
 $status=  $this->request['status'];
 
 
   $updateDetails = [
    'status' =>  $status,
     'widthdraw_transaction_id' => $this->request['txn'],
    
      'approved_by' =>$user_id,
        'remark_message' =>$this->request['description'] 
        ];

DB::table('exchange_widthdraw')
    ->where('id', $this->request['id'])
    ->update($updateDetails);
    
    
    
 if( $status=="Cancel")

{
    
  

      
	
//$widthdraw = DB::table('exchange_widthdraw')->where('status','Cancel')->where('id',$id)->first();
         
         
	
$user = DB::table('users')->where('id',  $widthdraw->user_id)->first();
         
         
         
     $paymentcls=new Paymentcls();
       
        $res=  $paymentcls->credit_user( $user ,      $widthdraw->amount ,$widthdraw->coin ,'Payment against cancellation of Widthdraw ' ,$id);
        
    
    
}

    $ar=array();
    $ar['Error']=false; 
    
    $ar['message']=  "Successfully Submitted";
    
    return response()->json($ar);
    
    
}


         public function fetch_widthdraw_requests_for_user($user_id)
{
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        


 
 
 
  $qr="select    ew.*,  u.email  FROM exchange_widthdraw  ew ,users u where ew.user_id=u.id and ew.user_id=".$user_id."   order by ew.id desc  ";
  
  
  
   $data = DB::select($qr) ;
   
   
      
  return response()->json($data );
    
}




     public function fetch_transactions($user_id)
{
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        



 
  $qr="select  * FROM wallet_transactions WHERE user_id=".$user_id." and status='Success' order by id desc  ";
 
 
   $data = DB::select($qr) ;
   
   
      
  return response()->json($data );
    
}
     
     
     
     
     
     
     
     
     
    
    
    
         public function getUsersByQuery($query)
{
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        

$query=trim($query);

 
  $qr="select  * FROM users WHERE EMAIL LIKE  '%".$query."%' or id like  '%".$query."%'    ";
 
 
   $data = DB::select($qr) ;
   
   
      
  return response()->json($data );
    
}
     
     
        public function investment_delete(Request $request )
{
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
 

     $otp=$request->otp;
        
        
        $user=Auth::user();
        
            $id=$request->robot_id;
        $user_id=$user->id;
        
       $ar=array();
      
        
        if($otp <> $this->getOTP($user_id))  
        {
            
    $ar['message']="OTP  did not matched.";
       
 
  
      $ar['Error']=true;
     
       return response()->json($ar);
        }
        
        
        
        
  $qr="delete FROM exchange_investment where id= ".$id;
 
 
   
  DB::unprepared($qr) ;
     
 
      if($otp <> $this->getOTP($user_id))  
        {
            
    $ar['message']="OTP  did not matched.";
       
 
  
      $ar['Error']=true;
     
       return response()->json($ar);
        }
        
        
        
          $robot =DB::table('exchange_investment')->where('id',$id)->first(); 
 
 
 if(!$robot)

{
    $ar['message']="Deleted successfully";
       
 
  
      $ar['Error']=true;
     
       return response()->json($ar);
} 
else
{  $ar['message']="Deleted failure";
       
 
  
      $ar['Error']=true;
     
       return response()->json($ar);
    
}
        
}



         public function fetch_all_investments( )
{
    
    
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
 

 
  $qr="select  * FROM exchange_investment  order by id desc  limit 200   ";
 
 
   $data = DB::select($qr) ;
   
  // echo $data;
      
  return response()->json($data );
    
}
     
     
     
     
     
         
         public function admindashboard()
{
  
    /*
    
      if(Auth::user()->roll < 8 )
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
*/



   
   $ar=array();
   
   
 $qr="select count(*) total_users FROM users    ";
  $data = DB::select($qr) ;
  $ar['total_users']=$data[0]->total_users;
   
   $qr="SELECT DATE(created_at) registeration_date , COUNT(id)  AS total_users FROM users GROUP BY DATE(created_at) order by registeration_date desc ";
    $data = DB::select($qr) ;
    
   $ar['user_counts']=$data ;
   
   
   
   
   
   
 $qr="select count(*) total_logins FROM login_history    ";
  $data = DB::select($qr) ;
  $ar['total_logins']=$data[0]->total_logins;
  
  
   
   
   $qr="SELECT DATE(created_at) login_date , COUNT(user_id)  AS total_login FROM login_history GROUP BY DATE(created_at) order by login_date desc ";
    $data = DB::select($qr) ;
    
   $ar['login_count']=$data ;
    
      
  return response()->json($ar );
 
 
}



      
         public function debit_credit_user()
{
  
  
  
     
     $user_id=$this->request['user_id'];
     
     
     $amount=$this->request['amount'];
     
     $currency=$this->request['currency'];
     $description=$this->request['description'];
     $reference_number=$this->request['reference_number'];
     
     $type=$this->request['type'];
     
      
       $admin=DB::table('users')->where('email', "globocoins.trade@gmail.com")->first()  ;
       
       
     
 $pl=new Paymentcls();
 
  $user =DB::table('users')->where('id',$user_id)->first(); 
 
    
    if($type=="credit")
  
    
    $status= $pl->payment_transfer($admin , $user   , $amount,$currency , $description  ,$reference_number   );
else

    
$status=  $pl->payment_transfer( $user , $admin , $amount, $currency, $description  ,$reference_number   );

    
  
      $ar=  array();
     
    $ar['status']= $status;
    $ar['message']=  "Request was successfully submitted"  ;
return response()->json($ar );
   
  
}

      
          
}