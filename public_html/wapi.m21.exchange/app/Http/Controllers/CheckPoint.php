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

use DateTime;
use App\Notifications\Login;

use App\Http\Controllers\Robot\RobotInvestmentCtrl;

class CheckPoint extends Controller
{
    
    public function __construct()
{
   
}



  public function startInvestment(  $id )
  {
    
     return "";
  $investment=DB::table('exchange_investment')->where('id' ,  $id) ->first()  ;
  
  
  var_dump($investment);
     
      if(!$investment) return "";
      
      
    
  if($investment->plan_name=="robot 11") return "";
  
  if($investment->status=="Active") return "";
       
   $days_completed=$this->getDaysCompleted($investment);
   
   
    if($days_completed > 30) return "";
    
    
       
    //   $investment->status="Pause";
       
     //  $investment->save();
       
 
	
 DB::table('exchange_investment')
           ->where('id', $investment->id)   
       ->update(['status' =>  "Active"  ]);
   
   
  $id= $investment->id;
 
 // $message="Debit of the robot for the investment id ".$investment->id;
       
       
       
   // $pl=new Paymentcls();
     
 //$user=DB::table('users')->where('id',$investment->user_id)->first()  ;
 
 
  //   if($user)
  //  $res=  $pl->debit_user( $user ,  $investment->amount,$investment->coin ,	$message ,$id);
          
          
          
     $investment=DB::table('exchange_investment')->where('id' ,  $id) ->first()  ;
  
  
  var_dump($investment);      
      
  }
  
  
  function getDaysCompleted($robot)
  {
      
        
      
      $days_completed=$this->getcount( $robot->days_completed ) ;
 
 
return     $days_completed;
 
 
 
 
  }
  
  function getcount($str)
    {
      $ar= explode(",",$str);
      
      $ar=array_unique($ar);
      
      $size=sizeof($ar);
      
      return $size;
        
    }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
     public function checkActiveRobot()
 {
     
      
	
     
       $investment_q= "SELECT   * FROM `exchange_investment` WHERE  status <> 'pending' ";
    
  
    $investments = DB::select($investment_q);
    
 
    foreach($investments as $investment )
    {
   
   //var_dump($investment );
 
 
       $transactions_q= "SELECT   * FROM `wallet_transactions` WHERE  user_id ='".$investment->user_id."' and  type='debit' and  reference  like '%".$investment->id."%'";
    echo $transactions_q;
  
    $transactions = DB::select($transactions_q);
    
    
    if( !$transactions )  
    
    {
     
   var_dump($investment );
    }

} 
 
 }
   
public function testGiftingRobotid( $robot){
   
    
      
       
  $robot=DB::table('exchange_investment')->where('id' ,  $robot->id) ->first()  ;
 var_dump($robot  );
 
 
 $created = new Carbon($robot->created_at);
 
 
$now = Carbon::now();


$difference = $created->diff($now)->days ;
 
 $str="0";
    
    for($i=0;$i< $difference ; $i++)
    
    
    {
        
             
$future  = $created->addDay();


  //  var_dump( $future->format('d.m.Y'));
    
    $str= $str.',' . $future->format('Y-m-d') ;
    }
 //0,2022-08-09,2022-08-10,2022-08-11,2022-08-12,2022...
 
 echo $str;
 
 
 
  $qr="update  exchange_investment  set Status_updated_at=created_at,  days_completed = '".$str."' where id = ".$robot->id; 
       
       
       
       
        DB::statement($qr);
	
	
	
	
	
  $robot=DB::table('exchange_investment')->where('id' ,  $robot->id) ->first()  ;
 var_dump($robot  );
 
}


    /*
public function activate_investment(Request $r){
   
    
          $user= Auth::user();
          
 
      
      
 $robo=DB::table('exchange_investment')->where('status' ,  "pending")->where('user_id',$user->id)->first()  ;
 
 
 $ric=new RobotInvestmentCtrl($r);
 
 return $ric->ActivateInvestment($robo->id );
 
 
   
}





public function credit(){
   
    
          $user= Auth::user();
          
    $pl=new Paymentcls();
      
 
 
    
    $res=  $pl->credit_user( $user ,  rand(5000,9000) , "DBIK"  ,'Deposit by test bot'  );
         
  
  $ar=array();
    $ar['Error']=false; 
    $ar['refer_id']=   $user->refer_id  ;
     $ar['DBIK']=   $user->DBIK  ;
    
    return response()->json($ar);
}


public function user_ids(){
   
     
 $users=DB::table('users')->select("id") ->orderBy("id", "desc")->take(5)->get()  ;
    
    return response()->json($users);
}

    */
}