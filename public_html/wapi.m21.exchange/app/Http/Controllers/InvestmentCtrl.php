<?php

namespace App\Http\Controllers;

use App\ChargeCommision;
use App\Income;
use App\MemberExtra;
use App\Deposit;
use App\Gateway;
use App\Lib\GoogleAuthenticator;
use App\Transaction;
use App\User;
use DateTime;

use App\Exchange_bookings;
use Illuminate\Support\Facades\DB;

 
use App\Http\Controllers\Controller;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;


use App\P2PTrade;

use App\P2p_orders;
use Validator;

use App\P2P_orders_discussion;

use App\System_transactions;
use App\Exchange_investment;


use App\p2p_feedbacks;


//use App\mlm_orders_investment;

 use App\mlm_system_investments;

use App\mlm_investment_orders;

use App\mlm_investment_transactions;



class InvestmentCtrl extends Controller
{
    
    
    public function __construct(Request $request) {
        $this->request = $request;
   
      //  $this->middleware(['auth' ]);
    } 
 
   public function RobotIncome($user_id)
 {
     
     
$qr="select sum(hourly_income) hourly_income from  `exchange_investment`  where user_id=".$user_id."  and status <> 'pending'";


        $investments = DB::select($qr);
        
        if($investments)
        {
        return $investments[0] ->hourly_income ;
        }
        return  0;
        
        
    
 }
 
 
 public function Bonus_claim()
 {
     
     
       $user_q= "SELECT   * FROM `users` WHERE  referral_DBIK > 0 or referral_bonus_dbik > 0  limit 20";
    
  
    $user_s= DB::select($user_q);
    
    
    var_dump($user_s);
    foreach($user_s as $user )
    { 
         
 $pl = new Paymentcls();
 
   $pl->claim_bonus($user ); 
 
   $pl->claim_referral_bonus_dbik ($user ); 
	  
    }
 
 
 
  
     
 }
 
 
 
  public function SpecialRobotPay()
 {
   
   
  
       $investment_q= "SELECT   * FROM `exchange_investment` WHERE status = 'Active' and plan_name ='robot 11'    order by id desc limit 1";
    
  
    $investments = DB::select($investment_q);
    
    foreach($investments as $investment )
    {
   
    
    try {
        
        
           
//	DB::table('exchange_investment')->where('id', $investment->id)->update(['refer_income' =>  1 ]);
	
 print_r($investment);      

 $this->SpecialRobotPayLogic($investment );

        
    }
catch(Exception $e) {
	echo 'Message: ' . $e->getMessage();
}


}

}



  public function SpecialRobotPayLogic($investment )
 {
   
    $investment=Exchange_investment::where('id',$investment->id) ->first()  ;
 
 
   // get special robot
   
   // get days completed
   
   // if 30 days +
   
   $spc=new SystemplanCtrl($this->request);
   
 $days_completed  =  $spc-> getcount( $investment->days_completed);
  
if($days_completed==30)

{
    
    
    $investment->hourly_income= $investment->amount * 10 /100; 

    


$investment->hourly_income_string = $investment->hourly_income_string . '|' . $investment->hourly_income     ;



}
   if($days_completed==60)

{
    
    
    $investment->hourly_income=$investment->hourly_income+ $investment->amount * 10 /100; 

    


$investment->hourly_income_string = $investment->hourly_income_string . '|' . $investment->hourly_income     ;



} 
      if($days_completed==90)

{
    
    
    $investment->hourly_income=$investment->hourly_income+ $investment->amount * 10 /100; 

    


$investment->hourly_income_string = $investment->hourly_income_string . '|' . $investment->hourly_income     ;



} 

$investment->save();


 }
 
 
 public function RobotAutoPayv2()
 {
     
     $rand=rand(0,1);
     
     
     $amt=   floor(4+ $rand  *(4)) /160000;
     
     
     
$qr="UPDATE `exchange_investment` SET   hourly_income =hourly_income+ amount* ".  $amt ." , hourly_income_string = CONCAT (hourly_income_string ,'|' ,amount* ".  $amt ." )  where   status='Active' and plan_name <> 'robot 11' ";

echo $qr;


      
  
var_dump( DB::unprepared($qr));
    
      DB::disconnect();
 }
 
 
 public function RobotAutoPay()
 {
    return ""; 
     $hash= date('Y-m-d-h')  ;
     
     
     
 $investment_q= "SELECT * FROM exchange_investment 
 WHERE id not in (SELECT investment_id FROM `robot_income_daily` where hash  like '%__".$hash."')
 and status  ='Active' order by id desc  limit 50 ";
 
 
 
 
    
  echo   $investment_q;
  
    $investments = DB::select($investment_q);
    
    foreach($investments as $investment )
    {
   
    
    try {
        
  echo       $investment ->id;
echo $this->roboIncomeProcess($investment,$hash );


$this->processInvestment($investment);


}
catch(Exception $e) {
	echo 'Message: ' . $e->getMessage();
}


}
     
 }
 
 
 
 
 
 
    public function roboIncomeProcess($investment,$hash){

return "";
// step 1 fetch investment 
// step 2 get benefit value
// step 3 insert benefits to profit monitor table
// 4  make real credit


     
     // step 1 fetch investment 
     
     $investment=$investment;
     
     // step 2 get benefit value
     
    // $income=rand(200,300)/1000;
     
     $income=rand(400,800)/10000;
     
     
     
     $hourly_return =$income;
     
 
 $profit  =    $investment->amount * $hourly_return / 100;
 
 
 echo "<hr/>";echo "<hr/>";echo "<hr/>";echo "<hr/>";echo "<hr/>";echo "<hr/>";echo "<hr/>";echo "<hr/>";
 echo $profit; 
 
 echo $hourly_return;
 
 
     // step 3 insert benefits to profit monitor table
     
$user_id= $investment->user_id;
$coin= $investment->coin;
$planid=$investment->plan;
$id=$investment->id;
 
//$hash= md5( $investment->id ."__". date('Y-m-d'));


 $hash=  $investment->id ."__".  $hash ;

   $user = User::find($user_id);

 try {
   
          
      
 // 5 if matured then calculate profit earning 
 
 
   echo "-------------------------<hr/> ----".__LINE__;
     
 
 echo $profit;
  echo "-------------------------<hr/> ----".__LINE__;
     // 6  credit profit earning + principle
  
    $final_amount=      $investment->amount ;
    
 echo  $final_amount;
  
  
     echo "-------------------------<hr/> ----".__LINE__;
    


            $description = "Robot hourly income from the robot id  ".$investment->id;
        
        
     
   DB::table('robot_income_daily')->insertGetId(
    [ 'user_id' => $user_id ,'investment_id' =>  $id  ,'coin' =>  $coin  ,'plan' => $planid ,'amount' => $profit ,'income' =>$profit, 'hash'=> $hash]
) ;
         
         
 
          /*       
 $admin=DB::table('users')->where('id',207)->first()  ;
 
 
 $pl = new Paymentcls();

$description="Robot Income credit for the investment id ".$id;  
 $debit = $pl->credit_user($user, $profit,$coin, $description,$id);
 
 $description="Robot Income debit for the investment id ".$id;  
  $debit = $pl->debit_user($admin,$profit,$coin, $description,$id); 
      */
      return "complete--".     $investment ->id;
         
  } catch (\Exception $exception) {
          return "duplicate--".     $investment ->id;
        }


}
 
    public function roboIncomeProcess11($investment){


// step 1 fetch investment 
// step 2 get benefit value
// step 3 insert benefits to profit monitor table
// 4  make real credit


     
     // step 1 fetch investment 
     
     $investment=$investment;
     
     // step 2 get benefit value
     
     $income=rand(200,300)/10000;
     
     // step 3 insert benefits to profit monitor table
     
$user_id= $investment->user_id;
$coin= $investment->coin;
$planid=$investment->plan;
$id=$investment->id;
 
//$hash= md5( $investment->id ."__". date('Y-m-d'));
$hash=  $investment->id ."__". date('Y-m-d-h-i') ;

   $user = User::find($user_id);

 try {
   
      
        
        
     
   DB::table('robot_income_daily')->insertGetId(
    [ 'user_id' => $user_id ,'investment_id' =>  $id  ,'coin' =>  $coin  ,'plan' => $planid ,'amount' => $investment->amount ,'income' =>$income, 'hash'=> $hash]
) ;
         
         
 
                 
 $admin=DB::table('users')->where('id',207)->first()  ;
 
 
 $pl = new Paymentcls();

$description="Robot Income credit for the investment id ".$id;  
 $debit = $pl->credit_user($user, $final_amount,$coin, $description,$id);
 
 $description="Robot Income debit for the investment id ".$id;  
  $debit = $pl->debit_user($admin,$income,$coin, $description,$id); 
      
      return "complete--".     $investment ->id;
         
  } catch (\Exception $exception) {
          return "duplicate--".     $investment ->id;
        }


}

 public function processInvestments()
 {
     
     
       $investment_q= "SELECT   * FROM `exchange_investment` WHERE status <> 'Matured' and refer_income is null  order by id desc limit 1";
    
  
    $investments = DB::select($investment_q);
    
    foreach($investments as $investment )
    {
   
    
    try {
        
        
	 $this->processInvestment($investment );
}
catch(Exception $e) {
	echo 'Message: ' . $e->getMessage();
}


}
     
 }
 
 
    public function completeMarkInvestment(){


 
       $investment_q= "SELECT   * FROM `exchange_investment` WHERE (  status  = 'Matured' or status  = 'Active' ) and created_at <  now() - interval 30 DAY   limit 2 ";
    
  
    $investments = DB::select($investment_q);
    
    foreach($investments as $investment )
    {
   
    
    try {
        
        
	 
 //$ac= $this->_date_diff($investment->created_at);
 
 
 
   $days_completed=$this->getDaysCompleted($investment);
 
 if($days_completed >  30)
 {
     
  
          $this->stopInvestment(  $investment );
            
 }
 
            
}
catch(Exception $e) {
	echo 'Message: ' . $e->getMessage();
}


}



 
       
}
public function stopInvestment(  $investment )
  {
    
     
     
      if(!$investment) return "";
      
      
    
  if($investment->plan_name=="robot 11") return "";
       
    
       
    //   $investment->status="Pause";
       
     //  $investment->save();
       
 
	
 DB::table('exchange_investment')
           ->where('id', $investment->id)   
       ->update(['status' =>  "Completed"  ]);
   
   
  $id= $investment->id;

    $message="Return the amount after the pause of the robot id ". $investment->id;
       
       
    $pl=new Paymentcls();
     
 $user=DB::table('users')->where('id',$investment->user_id)->first()  ;
 
 
     if($user)
     {
    $res=  $pl->credit_user( $user ,  $investment->amount,$investment->coin ,	$message ,$id);
          
           $message="Earnings credit of robot ID ".$id  ;
           
           
           
 DB::table('exchange_investment')
           ->where('id', $investment->id)   
       ->update(['hourly_income' =>  "0"  , 
       'hourly_income_string' => "" ]);
       
       
    $res=  $pl->credit_user( $user ,  $investment->hourly_income,$investment->coin ,	$message ,$id);
          
     }
      
  }

  public function startInvestment(  $id )
  {
    
     
     
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
 
  $message="Debit of the robot for the investment id ".$investment->id;
       
       
       
    $pl=new Paymentcls();
     
 $user=DB::table('users')->where('id',$investment->user_id)->first()  ;
 
 
     if($user)
    $res=  $pl->debit_user( $user ,  $investment->amount,$investment->coin ,	$message ,$id);
          
          
          
         
      
  }

 
    public function processInvestment($investment){


// step 1 fetch investment 
// step 2 get plan details
// step 3 check date
// 4  if matured the debit charges -- removed
// 5 if matured then calculate profit earning -- removed

// get earning from robo pay and credit in his account
// 6  credit profit earning + principle
// 7  update investment to matured
     
     
     
     //  $investment_q= "SELECT   * FROM `exchange_investment` where id = ".$id;
    
  //  var_dump($investment);
    //$investment = DB::select($investment_q)[0] ;
     
$coin= $investment->coin;


 var_dump($investment);

  echo "-------------------------<hr/> ----".__LINE__;
     echo "-------------------------<hr/>plan";
      $id=$investment->id;
     
     
          
  $user = User::find( $investment->user_id);
  
 // if(!$user) return "ssssssssssssssssss" ;
  $pl = new Paymentcls();
  
  
  
  
 $investment_q= "SELECT   * FROM `system_plan` where id = ".$investment->plan;
    
  //  var_dump($investment);
    $plan = DB::select($investment_q)[0] ;
     

 var_dump($plan);


  echo "-------------------------<hr/> ----".__LINE__;
     
      
 
 
 $ac= $this->_date_diff($investment->created_at);
 
 
 
// echo $ac;
 
 
 // step 3
 if($ac < 30)
 
  return "";
  
      
      
      
 // 5 if matured then calculate profit earning 
 // get total earnings
 
 
 $robo_income_qr="SELECT sum(income) sum_income FROM `robot_income_daily` WHERE investment_id= ".$investment->id;
 
  $robo_income_res  = DB::select($robo_income_qr)  ;
 
  $robo_income   = $robo_income_res[0]->sum_income;
 
 
 
   echo "-------------------------<hr/> ----".__LINE__;
     
 $profit  =$robo_income;//    $investment->amount * $plan->total_return / 100;
 
 echo $profit;
  echo "-------------------------<hr/> ----".__LINE__;
     // 6  credit profit earning + principle
  
    $final_amount= $profit +    $investment->amount ;
    
 echo  $final_amount;
  
  
     echo "-------------------------<hr/> ----".__LINE__;
    


            $description = "Robot completed  ID ".$investment->id;
 

 $debit = $pl->credit_user($user, $final_amount,$coin, $description,$id);
 
 
      // 4  if matured the debit charges
      
    //  $description="Debit of activation fee for the robot id ".$id;
     
 //$debit = $pl->debit_user($user,$plan->activation_fee,$coin, $description,$id); 
      
      
      
     echo "-------------------------<hr/> ----".__LINE__;   
      
      
      
     //echo "-------------------------<hr/> ----";
              
      echo        $debit;
 
                
                
                
        $n=array();
	$n['message']=$description;
	
	$n['type']="success";
	$n['url']="";
	
	$n['user_id']=$user->id;
	
	$n['user_login']=$user->username;
	
	
	$noti=new SystemNotification();
	
	$noti->system_notification_new($n);
	
	
	  echo "-------------------------<hr/> ----".__LINE__;
	
    
 DB::table('exchange_investment')
            ->where('id', $id) 
         ->update(['status' =>  "Matured"  ]);
            
 $this->AwardProfit2Refer( $investment );
          
          
            echo "-------------------------<hr/> ----".__LINE__;  
}






public function refer_income()
{
     
      
	
	
       $investment_q= "SELECT * FROM `exchange_investment` WHERE STATUS <> 'pending' and id not in (SELECT DISTINCT REFERENCE FROM `referral_wallet_transactions` ) order by id desc limit 10 ";
    
  
    $investments = DB::select($investment_q);
    	
 //print_r($investments); 
    foreach($investments as $investment )
    {
   
    
    try {
        
        
           
//	DB::table('exchange_investment')->where('id', $investment->id)->update(['refer_income' =>  1 ]);
	//
// print_r($investment);      

 $this->AwardProfit2Refer($investment );

        
    }
catch(Exception $e) {
	echo 'Message: ' . $e->getMessage();
}


}
     
}



 public function processInvestmentsHourlyBonus()
 {
  $custom_hash=  date('Y-m-d');
     
       $investment_q= "SELECT   * FROM `exchange_investment` WHERE (  bonus_income <>  '".$custom_hash."' or  bonus_income is null   )    and status ='Active'  order by id desc limit 11";
     
     
    //return $investment_q;
     
     
    $investments = DB::select($investment_q);
    // var_dump($investments);
    foreach($investments as $investment )
    {
   
   
    try {
        
        var_dump($investment->id);
        
        echo "-------------------------------";
        
        
	 $this-> AwardProfit2ReferBonus($investment);
 
 //if($i++ > 2 )	 exit();
}
catch(Exception $e) {
	echo 'Message: ' . $e->getMessage();
}


}
     
 }


public function capture_robot_income_process()
{
 
       $investment_q= "SELECT   * FROM `exchange_investment` WHERE hourly_income > 5 and user_id=159 and limit 2";
     
     
    $investments = DB::select($investment_q);
    
    
       foreach($investments as $investment )
    {
      $description = "Robot Income Credit with  ID ROBOT ".$investment->id;
 
    $user = User::find( $investment->user_id);
    
 $debit = $pl->credit_user($user, $investment->hourly_income,$investment->coin, $description,$investment->id);
 
 
	DB::table('exchange_investment')->where('id', $investment->id)->update(['hourly_income' =>0  ]);
	
   
   
    }
    
     
}

 




public function capture_robot_income()
{
        
 $qr="insert into hourly_income_bonus_2 ( investment_id,user_id,hourly_income,date_ins) SELECT id,user_id,hourly_income ,now() from exchange_investment where status ='Active'   and hourly_income > 0"; 
      
 var_dump( DB::unprepared($qr));
    
}
public function getbonusin24hour($investment)
{
    
 //$investment=DB::table('exchange_investment')->where('id',$id)->first()  ;
 
 // var_dump($investment);
     // get earning before 24 hour
     
     
     
     // get earning now  // st 1
     
     // get subtract and get value call it earning in 24 hour
     
     
     // based on the bonus in 24 hour give profit to upline 
     
     
     
     
       $bonus_q= "SELECT   * FROM  hourly_income_bonus_2 WHERE  investment_id=".$investment->id." order by id desc ";
    
  
    $bonuss = DB::select($bonus_q);
 
    //st 1 get eanring now
    
    
    $earning_now=$investment->hourly_income;
    
   // echo $earning_now ;
    
    //echo "<hr />";
    
    //st 2 get eanring 24 hour before
    
    if($bonuss)
        $earning_24hour_before= $bonuss[0]->hourly_income;
    else
      $earning_24hour_before=0;
    //echo $earning_24hour_before;echo "<hr />";
    
    // st 3 earning in 24 hour
    
    $earning_now = $earning_now - $earning_24hour_before;
    
    return $earning_now;
    
    
    
     
     
     //capture data for next execuation
     
// $qr="insert into hourly_income_bonus_2 ( investment_id,user_id,hourly_income,date_ins) SELECT id,user_id,hourly_income ,now() from exchange_investment"; 
      
//var_dump( DB::unprepared($qr));
}

public function AwardProfit2ReferBonus($investment)
{
     
     	$id=$investment->id;
    
    
     
   $custom_hash=  date('Y-m-d');
     
     
	DB::table('exchange_investment')->where('id', $id)->update(['bonus_income' => $custom_hash  ]);
	
	
     
     // 1 get user id
     
     // 2 get parent id 
     
     // 3 check if parent have this type robot 
     
     // 4 if yes then award same 
     
     // 5 if no then award half
     
     
     // step 1 get user id
     
     
     $user_id=$investment->user_id;
     
     // step 2 get parent user id
     
     $user = User::find( $user_id);
	
	
	if(!$user)
	return "no user ";
	
	
   $parent_id =$user->refer_id;
   
   
   // step 3  check if parent have this type robot 
    
   $parents_robot = Exchange_investment::where('user_id',$parent_id)->where("plan_name",$investment->plan_name) ->first()  ;
   
   
   //print_r($parents_robot);
   
 if( $parents_robot)
    
     $this-> AwardProfit2ReferBonusSame($investment);
     
     else
     
     $this-> AwardProfit2ReferBonusLower($investment);
     
     
     
     
     
}



public function AwardProfit2ReferBonusSame($investment)
{
     
     

	
     
     if($investment->license_fee < 1) return "";
	
	
	
	
     
    $user_id=$investment->user_id;
      
    $pl = new Paymentcls();
    
	$user = User::find( $investment->user_id);
	
	
	
      if(!$user) return ;
  
  
 // print_r(	$user);
  
  
$coin= $investment->coin;
  
  
  
$id= $investment->id;
  
  
  
      $amount=$this->getbonusin24hour($investment) ;// $investment->hourly_income; // consider income in only 24 hour not all income
      
      echo "<hr /><hr /><hr /><hr /><hr />";
      echo $amount;
      echo "<hr /><hr /><hr /><hr /><hr />";
      
      
      // get all user id of 1st level
      
      $parent_1=$this-> getParent($user);
      if(!$parent_1) return ;
      
      $credit = $pl->referral_bonus_credit_user($parent_1,    $amount * 12 /100 , $coin,  "Referral Bonuse  income from the robot Id - " .$id,$id);
 
   
  
    
       
      
      // get all user id of 2nd level
      
      
      $parent_2=$this-> getParent($parent_1);
      if(!$parent_2) return ;
      $credit = $pl->referral_bonus_credit_user($parent_2,    $amount * 8 /100 , $coin,  "Referral Bonuse income from the robot Id - " .$id,$id );
 
 
 
 
      // get user id of 3rd level
      
      
      
      $parent_3=$this-> getParent($parent_2);
      if(!$parent_3) return ;
      $credit = $pl->referral_bonus_credit_user($parent_3,    $amount * 5 /100 , $coin,  "Referral Bonuse income from the robot Id - " .$id,$id);
 
 
 
      
      // get user id of 4th level
      
      
      
      
      $parent_4=$this-> getParent($parent_3);
       if(!$parent_4) return ;
      $credit = $pl->referral_bonus_credit_user($parent_4,    $amount * 3 /100 ,$coin,  "Referral Bonuse income from the robot Id - " .$id,$id);
      
      
      
      
      // get user id of 5th level
      
      
  
      
      
      $parent_5=$this-> getParent($parent_4);
        if(!$parent_5) return ;
      $credit = $pl->referral_bonus_credit_user($parent_5,    $amount * 1 /100 ,$coin,  "Referral Bonuse income from the robot Id - " .$id,$id);
      
  
    
  
         
         
         
    
}
public function AwardProfit2ReferBonusLower($investment)
{
     
     
	$id=$investment->id;
    
    
    
    
	 
     
     if($investment->license_fee < 1) return "";
	
	
	
	
     
    $user_id=$investment->user_id;
      
    $pl = new Paymentcls();
    
	$user = User::find( $investment->user_id);
	
	
	
      if(!$user) return ;
  
  
 // print_r(	$user);
  
  
$coin= $investment->coin;
  
  
  
     // $amount= $investment->hourly_income;
            $amount=$this->getbonusin24hour($investment) ;
      // get all user id of 1st level
      
      $parent_1=$this-> getParent($user);
      if(!$parent_1) return ;
      
      $credit = $pl->referral_bonus_credit_user($parent_1,    $amount * 6 /100 , $coin,  "Referral Bonuse  income from the robot Id - " .$id,$id);
 
   
 // print_r(	$parent_1);
  
    
       
      
      // get all user id of 2nd level
      
      
      $parent_2=$this-> getParent($parent_1);
      if(!$parent_2) return ;
      $credit = $pl->referral_bonus_credit_user($parent_2,    $amount * 4 /100 , $coin,  "Referral Bonuse income from the robot Id" .$id,$id );
 
 
 
 
      // get user id of 3rd level
      
      
      
      $parent_3=$this-> getParent($parent_2);
      if(!$parent_3) return ;
      $credit = $pl->referral_bonus_credit_user($parent_3,    $amount * 25 /1000 , $coin,  "Referral Bonuse income from the robot Id" .$id,$id);
 
 
 
      
      // get user id of 4th level
      
      
      
      
      $parent_4=$this-> getParent($parent_3);
       if(!$parent_4) return ;
      $credit = $pl->referral_bonus_credit_user($parent_4,    $amount * 15 /1000 ,$coin,  "Referral Bonuse income from the robot Id" .$id,$id);
      
      
      
      
      // get user id of 5th level
      
      
  
      
      
      $parent_5=$this-> getParent($parent_4);
        if(!$parent_5) return ;
      $credit = $pl->referral_bonus_credit_user($parent_5,    $amount * 5 /1000 ,$coin,  "Referral Bonuse income from the robot Id" .$id,$id);
      
  
      
      // get user id of 6th level
      
      return "";
      
      
    
     
         
         
         
    
}


 

public function AwardProfit2Refer($investment)
{
     
     
	$id=$investment->id;
    
	DB::table('exchange_investment')->where('id', $id)->update(['refer_income' =>  1  ]);
	
     
     if($investment->license_fee < 1) return "";
	
	
	
	
     
    $user_id=$investment->user_id;
      
    $pl = new Paymentcls();
    
	$user = User::find( $investment->user_id);
	
	
	
      if(!$user) return ;
  
  
//  print_r(	$user);
  
  
$coin= $investment->coin;
  
  
  
      $amount= $investment->license_fee;
      
      // get all user id of 1st level
      
      $parent_1=$this-> getParent($user);
      if(!$parent_1) return ;
      
      $credit = $pl->referral_credit_user($parent_1,    $amount * 10 /100 , $coin,  "Referral income from the robot Id - " .$id,$id);
 
   
//  print_r(	$parent_1);
  
    
       
      
      // get all user id of 2nd level
      
      
      $parent_2=$this-> getParent($parent_1);
      if(!$parent_2) return ;
      $credit = $pl->referral_credit_user($parent_2,    $amount * 8 /100 , $coin,  "Referral income from the robot Id" .$id,$id );
 
 
 
 
      // get user id of 3rd level
      
      
      
      $parent_3=$this-> getParent($parent_2);
      if(!$parent_3) return ;
      $credit = $pl->referral_credit_user($parent_3,    $amount * 5 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
 
 
 
      
      // get user id of 4th level
      
      
      
      
      $parent_4=$this-> getParent($parent_3);
       if(!$parent_4) return ;
      $credit = $pl->referral_credit_user($parent_4,    $amount * 3 /100 ,$coin,  "Referral income from the robot Id" .$id,$id);
      
      
      
      
      // get user id of 5th level
      
      
  
      
      
      $parent_5=$this-> getParent($parent_4);
        if(!$parent_5) return ;
      $credit = $pl->referral_credit_user($parent_5,    $amount * 3 /100 ,$coin,  "Referral income from the robot Id" .$id,$id);
      
  
      
      // get user id of 6th level
      
      
  
      
      
      $parent_6=$this-> getParent($parent_5);
        if(!$parent_6) return ;
      $credit = $pl->referral_credit_user($parent_6,    $amount * 2 /100 ,$coin,  "Referral income from the robot Id" .$id,$id);
      
      
      // get user id of 7th level
      
      
  
      
      
      $parent_7=$this-> getParent($parent_6);
        if(!$parent_7) return ;
      $credit = $pl->credit_user($parent_7,    $amount * 1 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
      
    
    
    
      
      $parent_8=$this-> getParent($parent_7);
        if(!$parent_8) return ;
      $credit = $pl->referral_credit_user($parent_8,    $amount * 1 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
    
      
      $parent_9=$this-> getParent($parent_8);
        if(!$parent_9) return ;
      $credit = $pl->referral_credit_user($parent_9,    $amount * 1 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
      
     
      
      $parent_10=$this-> getParent($parent_9);
        if(!$parent_10) return ;
      $credit = $pl->referral_credit_user($parent_10,    $amount * 1 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
      
    
    
     
         
         
         
    
}


 



public function AwardProfit2Refer_old($investment)
{
     
     
     
     
	
	
	$id=$investment->id;
    
	DB::table('exchange_investment')->where('id', $id)->update(['refer_income' =>  1 ]);
	
	
	
     
    $user_id=$investment->user_id;
      
    $pl = new Paymentcls();
    
	$user = User::find( $investment->user_id);
      if(!$user) return ;
  
$coin= $investment->coin;
  
  
  
      $amount= $investment->amount;
      
      // get all user id of 1st level
      
      $parent_1=$this-> getParent($user);
      if(!$parent_1) return ;
      
      $credit = $pl->credit_user($parent_1,    $amount * 10 /100 , $coin,  "Referral income from the robot Id - " .$id,$id);
 
   
      
      
      // get all user id of 2nd level
      
      
      $parent_2=$this-> getParent($parent_1);
      if(!$parent_2) return ;
      $credit = $pl->credit_user($parent_2,    $amount * 8 /100 , $coin,  "Referral income from the robot Id" .$id,$id );
 
 
 
 
      // get user id of 3rd level
      
      
      
      $parent_3=$this-> getParent($parent_2);
      if(!$parent_3) return ;
      $credit = $pl->credit_user($parent_3,    $amount * 5 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
 
 
 
      
      // get user id of 4th level
      
      
      
      
      $parent_4=$this-> getParent($parent_3);
       if(!$parent_4) return ;
      $credit = $pl->credit_user($parent_4,    $amount * 3 /100 ,$coin,  "Referral income from the robot Id" .$id,$id);
      
      
      
      
      // get user id of 5th level
      
      
  
      
      
      $parent_5=$this-> getParent($parent_4);
        if(!$parent_5) return ;
      $credit = $pl->credit_user($parent_5,    $amount * 3 /100 ,$coin,  "Referral income from the robot Id" .$id,$id);
      
  
      
      // get user id of 6th level
      
      
  
      
      
      $parent_6=$this-> getParent($parent_5);
        if(!$parent_6) return ;
      $credit = $pl->credit_user($parent_6,    $amount * 2 /100 ,$coin,  "Referral income from the robot Id" .$id,$id);
      
      
      // get user id of 7th level
      
      
  
      
      
      $parent_7=$this-> getParent($parent_6);
        if(!$parent_7) return ;
      $credit = $pl->credit_user($parent_7,    $amount * 1 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
      
    
    
    
      
      $parent_8=$this-> getParent($parent_7);
        if(!$parent_8) return ;
      $credit = $pl->credit_user($parent_8,    $amount * 1 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
    
      
      $parent_9=$this-> getParent($parent_8);
        if(!$parent_9) return ;
      $credit = $pl->credit_user($parent_9,    $amount * 1 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
      
     
      
      $parent_10=$this-> getParent($parent_9);
        if(!$parent_10) return ;
      $credit = $pl->credit_user($parent_10,    $amount * 1 /100 , $coin,  "Referral income from the robot Id" .$id,$id);
      
    
    
    
	
    
         
         
         
    
}


 




public function getParentbyUser_id($user_id)
{
    $u= User::find(  $user_id);
    
  $user = User::find(  $u->refer_id);
       
       
    return    $user;
    
    
}



public function getParent($user)
{
    
    
       $user = User::find(  $user->refer_id);
       
       
    return    $user;
    
    
}



 
 public function _date_diff($mydate) {
    $now = time();
    $mytime = strtotime(str_replace("/", "-", $mydate)); // replace '/' with '-'; to fit with 'strtotime'
    $diff = $now - $mytime;
    
    
    $ret_diff = [
        'days' => round($diff / (60 * 60 * 24)),
        'months' => round($diff / (60 * 60 * 24 * 30)),
        'years' => round($diff / (60 * 60 * 24 * 30 * 365))
    ];
    return $ret_diff['days'];
}



 
    public function referuser_list(){
     

    
      $ar=array();
    
       $user_id=Auth::user()->id;
        
        //level 1
        
    $q= "SELECT distinct id FROM `users` WHERE id =".$user_id;
    
    $q_details= "SELECT distinct * FROM `users` where id  in($q ) ";
    
  //  var_dump($q_details);
    $user = DB::select($q_details) ;
     
    $ar['level_0']=$user;
   
    
    
    
    
     //level 2
     
     
     $q0="SELECT distinct id FROM `users` WHERE id =".$user_id;
     
     
        $q1= "SELECT distinct id FROM `users` where id in($q0 ) ";
        
        
            $q1_details= "SELECT distinct * FROM `users` where id  in($q1 ) ";
            
            
     
      $user1 = DB::select($q1_details) ;
        $ar['level_1']=$user1;
    //  var_dump($q1_details);

      



 //level 3
      
      
      $q1= "SELECT distinct  id FROM `users` where id in($q0 ) ";
         $q2= "SELECT distinct  id FROM `users` where id in($q1 ) ";
         
         
          $q2_details= "SELECT distinct * FROM `users` where id  in($q2 ) ";
      
  //  echo  $q2;
      $user2 = DB::select($q2_details) ;
        $ar['level_2']=$user2;
     
 //level 4
     
  
      $q2= "SELECT distinct id FROM `users` where id in($q1 ) ";
      
      $q3= "SELECT  distinct  id FROM `users` where id in($q2 ) ";
      
      
      
      
       $q3_details= "SELECT distinct * FROM `users` where id  in($q3) ";

      $user3 = DB::select($q3_details) ;
          $ar['level_3']=$user3;
     
     
 //level 5
     
     
       
      $q3= "SELECT distinct id FROM `users` where id in($q2 ) ";
     $q4= "SELECT distinct id FROM `users` where id in($q3 ) ";
     $q4_details= "SELECT distinct * FROM `users` where id  in($q4) ";
      

      $user4 = DB::select($q4_details) ;
          $ar['level_4']=$user4;
           

      
return view('network.referuserslist'   ,['data' => $ar]);
}


public function getPackages()
{
    
     $packages = array( 
             array (
               "min_invest" => 100,
                 "max_invest" =>50000,
               "name" => "package_one" ,
                "label" => "Package One" 
            ),
            
              array (
              "min_invest" => 500,
               "max_invest" =>50000,
              "name" => "package_two" ,
                "label" => "Package Two"
            ),
            
          array (
                "min_invest" => 1400,
                "max_invest" =>50000,
                "name" => "package_three",
                "label" => "Package Three" 
            )
            ,
            
          array (
                 "min_invest" => 3000,
            "max_invest" =>50000,
                "name" => "package_four" ,
                "label" => "Package Four" 
            )
         );
         
      
          $package_name =Auth::user()->package_name;  
      
    Session::put('package_name', $package_name);
    
    
    
    return view('aff.packages'   ,['packages' => $packages]);
    
   
}



   public function UserBalance($coin,$user_id)
    {
         
  
         $data = DB::table('system_transactions')
                     ->select(DB::raw('sum(cr) - sum(dr) as balance '))
                     ->where('status', '=', "Success")
                        ->where('coin', $coin)
                         ->where('user_id', $user_id)
                        ->get();
     
                     
      
     
          return   $data[0]->balance  ;
            
    
    }
    
    
    
    
public function investnow( )

{
    
    
     $id=Auth::user()->id;
     
     
    $bal=$this->UserBalance("BTC",$id);
     
     
    if($bal<=$this->request['invest_amount'])
    {
        return back()->with('success','You do not have fund to complete this order Please deposit balance.');
        
    }
     
   ///////////////////////
    $st = new system_transactions;
   
    
        $st->user_id =$id;   
        
        
        $st->cr =  0; 
       
             $st->dr = $this->request['invest_amount']; 
       
             $st->coin =  "BTC"; 
             
             $st->invoice_id =  $id; 
       
           $st->status =  "Success";
        
       
             $st->description =  "Robot of ".$this->request['invest_amount']." for the package ".$this->request['package_name'] ;
         
       
        
        $save =    $st->save();
        /////////////////////////////////////
   $in=new    mlm_investment_orders;
   $in->amount=$this->request['invest_amount'];
     $in->package_name  =    $this->request['package_name'];
 
 
             $in->coin =  "BTC"; 
        $in->user_id =$id;   
        
        
 
        
        $save =    $in->save();
        
 
 ////////////////////////////////////////
    $si = new mlm_investment_transactions;
   
    
        $si ->user_id =$id;   
        
        
        $si->cr = $this->request['invest_amount'];
       
   
       
             $si->coin =  "BTC"; 
             
             $si->order_id =  $in->id; 
       
           $si->status =  "Success";
        
       
             $si->description = "Robot purchase of ".$this->request['invest_amount']." for the package ".$this->request['package_name'] ." Robot ID ". $in->id;
         
         
       
        
        $save =    $si->save();
        
        ////////////////////////////
 
 //$this->awardreferincome( $this->request['plan'],  $this->request['payment_method'] );
  
 
 
  $user = User::find((Auth::user()->id));
       
       
            $user->package_name  =    $this->request['package_name'];
               
            $user->save();
            
            
            
       return redirect( '/system/transactions');
       
        
}



private function creditearning($user_id,$amount,$r,$coin)

{
    
   // echo  $coin;
    
    $st = new system_transactions;
        $st->user_id =$user_id;
        
        
        $st->cr =$amount;
       
             $st->dr =0;
       
             $st->coin = $coin;    
             
             $st->invoice_id ="R".$r;
       
           $st->status =  "Success";
        
       
             $st->description =  "Referral income for the user registration with ID ".$r;
         
        
        
        $save =    $st->save();
        
        
        
        
}




public function test_awardreferincome()
{
 
 $amount=1000;
 $m=array(30,30,30);
 
 $this->awardreferincome($amount );
 
 
}


public function awardreferincome($amount,$coin )
{
 
  $id=  Auth::user()->id;   
  
  $m=array(30,30,30);
  
  $refer_id=  Auth::user()->refer_id;   
  
  
     $this->creditearning($refer_id,$amount*$m[0]/100, $id,$coin);
 
  
      //get direct referjj
    $user = DB::table('users')->where('id', $refer_id)->first();
//echo $user->refer_id;
 
 
   $this->creditearning($user->refer_id,$amount*$m[1]/100, $id,$coin);
 
 
    //get direct refer  level one
    $user1 = DB::table('users')->where('id',$user->refer_id)->first();
//echo $user1->refer_id;

 $this->creditearning($user1->refer_id,$amount*$m[2]/100, $id,$coin);
 
    //get direct refer  level two
   // $user2= DB::table('users')->where('id',$user1->refer_id)->first();
//echo $user2->refer_id;
 // $this->creditearning($user2->refer_id,$amount*$m[2]/100,  $id);

 
    //get direct refer  level three
  //  $user3= DB::table('users')->where('refer_id',$user2->refer_id)->first();

// $this->creditearning($user3->refer_id,$amount*$m[3]/100, $refer_id);  investment_order_profit
 
}



public function investment_order_profit()
{  

//investment_order_profit

 $user_id=  Auth::user()->id;
       


$mlm_investment_transactions = mlm_investment_transactions::where('user_id', $user_id)->get();

        return view('aff.mlm_investment_transactions')
            ->with('mlm_investment_transactions', $mlm_investment_transactions);
            
            
    
}
public function investment_order_history()
{ 
    
    

 $user_id=  Auth::user()->id;
       


$mlm_investment_orders = mlm_investment_orders::where('user_id', $user_id)->get();

       
       
  return view('aff.mlm_investment_orders'   ,['mlm_investment_orders' => $mlm_investment_orders]);
    
 
    
}



public function up_earning()
{  

 $refer_id=  Auth::user()->id;
       
   
 $wh ="  select * from system_transactions   where invoice_id= 'R".$refer_id. "'  "; 
 $trans = DB::select( $wh );
 
    
    Session::put('pagetitle',  "Income from your joining");
    
  return view('aff.refersincome'   ,['trans' => $trans]);
    
    
}


public function ref_earning()
{  

 $id=  Auth::user()->id;
      
// Session::put('refer_id', $refer_id);
   
 $wh ="  select * from system_transactions   where invoice_id like 'R%'  and user_id=". $id;
 
 //echo $wh;
 
 $trans = DB::select( $wh );
 
// var_dump( $trans);
    
    Session::put('pagetitle',  "Your income from your referral");
    
  return view('aff.refersincome'   ,['trans' => $trans]);
    
    
}



public function getrefer()
{  
      $refer_id=  Auth::user()->id;
      
         Session::put('refer_id', $refer_id);
   
 $wh ="  select * from users   where refer_id= '".$refer_id. "'  ";
 $users = DB::select( $wh );
    
 return view('aff.refersusers'   ,['users' => $users]);
    
 
    
}

public function getreferbylevelid($i)
{
   
    
    $wh="";
    
    
      $refer_id=  Auth::user()->id;
   
         Session::put('refer_id', $refer_id);
   
   if($i >= 1 )   
     
     
      $wh ="  select id from users   where refer_id= '".$refer_id. "'  ";
       
      
           if($i >= 2 )   
      
       
      $wh  =" refer_id in (".$wh. " )";
       
      
      
      
             if($i >=  3 )   
      $wh  =" refer_id in (".$wh. " )";
       
       
             if($i >=  4 )   
      $wh  =" refer_id in (".$wh. " )";
      
      
       
            if($i >=  5 )   
      $wh  =" refer_id in (".$wh. " )";
      
      
       
         if($i >=   6 )   
      $wh=" refer_id in (".$wh. " )";
      
      
      
              if($i >=  7 )   
      $wh   =" refer_id in (".$wh. " )";
      
      
      $q="select * from users 
      where id in   ( " .$wh.")";
       
      // echo $q;
       
   $users = DB::select($q );
      
      
      
      
     
 return view('aff.refersusers'   ,['users' => $users]);
    
    
    
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

}
