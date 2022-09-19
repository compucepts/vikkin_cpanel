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

class TestBotCtrl extends Controller
{
    
    public function __construct()
{
   
}
/*


  
   public function checkCompletedRobot()
 {
     
      
	
     
       $investment_q= "SELECT   *  FROM `exchange_investment` WHERE   status ='Pause'  order by id desc  ";
    
  
    $investments = DB::select($investment_q);
 
    foreach($investments as $investment )
    {
   echo "--------------------";
 
 //   $this->checkwallettransactionOddEvent( $investment );
    
    
  
   $days_completed=$this->getDaysCompleted($investment);
   
   echo "----days_completed---".$days_completed;
      if($days_completed< 29 )
    {
         var_dump($investment->id); 
        
   
   
   
    }
   echo "--------------------";
 

} 
 
 
       
 }
 
 
 
/*
  
   public function checkCompletedRobot()
 {
     
      
	
     
       $investment_q= "SELECT   *  FROM `exchange_investment` WHERE   status ='Completed' and created_at <  now() - interval 10 DAY   order by id desc limit 2000";
    
  
    $investments = DB::select($investment_q);
 
    foreach($investments as $investment )
    {
   echo "--------------------";
 
 //   $this->checkwallettransactionOddEvent( $investment );
    
    
  
   $days_completed=$this->getDaysCompleted($investment);
   
   echo "----days_completed---".$days_completed;
      if($days_completed< 29 )
    {
         var_dump($investment->id); 
        
        
        
DB::table('exchange_investment')
           ->where('id', $investment->id)   
       ->update(['status' =>  "Pause"  ]);
   
   
    }
   echo "--------------------";
 

} 
 
 
       
 }
 */
  
  
  
  
    public function checkGiftingRobots000000000000000()
 {
     
      
	
     
       $investment_q= "SELECT   id,status,user_id FROM `exchange_investment` WHERE   status ='Active'   order by id desc limit 2000";
    
  
    $investments = DB::select($investment_q);
 
    foreach($investments as $investment )
    {
   echo "--------------------";
   // var_dump($investment);
    $this->checkwallettransactionOddEvent( $investment );
    
    
   echo "--------------------";
 

} 
 
 
       
 }
 
 
 
  
  
  
  
  
  
  
  
  
  //  203104  he get extra 30 
  // 203104
  public function checkGiftingRobots()
 {
     
      
	
     
       $investment_q= "SELECT   id,status,user_id FROM `exchange_investment` WHERE   status ='Active'   order by id desc limit 2000";
    
  
    $investments = DB::select($investment_q);
 
    foreach($investments as $investment )
    {
   echo "--------------------";
   // var_dump($investment);
    $this->checkwallettransactionOddEvent( $investment );
    
    
   echo "--------------------";
 

} 
 
 
       
 }
 
     public function checkwallettransactionReturn($investment)
 {
     
      
	
   $str="Debit of the investment amount after purchase of lincese fee for the investment id ".$investment->id;
     
   $q= "SELECT   id,description,user_id,reference  FROM `wallet_transactions` WHERE   description like '%".$str."%'  and user_id > 200  order by id desc    ";
    
  
      // $investment_q= "SELECT   id,user_id,amount, type,description , created_at  ,REFERENCE   FROM `wallet_transactions` WHERE user_id =$investment->user_id and type='debit' and amount = $investment->amount    ";
    
  
    $transactions = DB::select($q);
  
 
   $size=sizeof($transactions);
   
   
     //  echo "size-------------".$size;
       
        if($size % 2 == 0){
        echo "Even"; 
    }
    else{
        echo "Odd";
    }
 }
 
 
 
 
     public function checkwallettransactionOddEvent($investment)
 {
     
      
	 
     
   $q= "SELECT   id,description,user_id,reference,amount  FROM `wallet_transactions` WHERE  reference= $investment->id and user_id > 200 and description not like 'Referral income from the%' order by id desc    ";
    
  
    
  
    $transactions = DB::select($q);
  
 if(isset($transactions))
 {
  // var_dump($transactions[0]);
         
       //  var_dump($investment);
 }
 else
 {
       echo ":::::::::::::::::::";
       var_dump($investment);
       echo ":::::::::::::::::::";
     
 }

} 
 
 
 
 
   
   
 
 
 
 
     public function checkwallettransaction($investment)
 {
     
      
	
     
   $investment_q= "SELECT   id,user_id,amount, type,description , created_at  ,REFERENCE   FROM `wallet_transactions` WHERE reference like '%".$investment->id."%'     ";
    
  
      // $investment_q= "SELECT   id,user_id,amount, type,description , created_at  ,REFERENCE   FROM `wallet_transactions` WHERE user_id =$investment->user_id and type='debit' and amount = $investment->amount    ";
    
  
    $investments = DB::select($investment_q);
 
    foreach($investments as $investment )
    {
   
   var_dump($investment);
    // $this->testGiftingRobotid( $investment);
    
    
 

} 
 
 
       
 }
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 /*
 
 
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
  
  */
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
  
  
  /*
  
  
  
  
 
 ///////////////////////////////////////////////////////////////////////
  
     public function testGiftingRobot22()
 {
     
      
	
     
       $investment_q= "SELECT   * FROM `exchange_investment` WHERE description like '%gift%' and status ='Completed'  and days_completed='0' order by id desc limit 10";
    
  
    $investments = DB::select($investment_q);
 
    foreach($investments as $investment )
    {
   
   var_dump($investment);
     $this->testGiftingRobotid( $investment);
    
    
 

} 
 
 
       
 }
   
public function testGiftingRobotid( $robot){
   
    
      
       
  $robot=DB::table('exchange_investment')->where('id' ,  $robot->id) ->first()  ;
 //var_dump($robot  );
 
 
 $created = new Carbon($robot->created_at);
 
 
$now = Carbon::now();


$difference = $created->diff($now)->days ;
 
 $str="0";
    
    for($i=0;$i< $difference ; $i++)
    
    
    {
        
             
$future  = $created->addDay();

 
    
    $str= $str.',' . $future->format('Y-m-d') ;
    }
 
 
// echo $str;
 
 
 
  $qr="update  exchange_investment  set Status_updated_at=created_at,  days_completed = '".$str."' where id = ".$robot->id; 
       
      echo   $qr;
      
       
 
  DB::statement($qr);
	
	
	
	
	 
 
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