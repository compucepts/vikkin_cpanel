<?php

namespace App\Http\Controllers\Robot;

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


use App\p2p_feedbacks;



use App\Exchange_investment;



//use App\mlm_orders_investment;

 use App\mlm_system_investments;

use App\mlm_investment_orders;

use App\mlm_investment_transactions;


use App\Http\Controllers\Paymentcls;
use App\Http\Controllers\Notification;


use App\Http\Controllers\SystemNotification;
use App\Http\Controllers\InvestmentCtrl;


class RobotInvestmentCtrl extends Controller
{
    
    
    public function __construct(Request $request) {
        $this->request = $request;
   
      //  $this->middleware(['auth' ]);
    } 
  
  
 public function updatedays( )
  {
    
  DB::unprepared( "UPDATE `exchange_investment` set days_completed =0 WHERE days_completed is null; ");
          
          
          $ids="SELECT  id, days_completed  FROM `exchange_investment` where status ='Active'   and     days_completed not like CONCAT('%', CURRENT_DATE(), '%')   ";
          
            $investments=DB::select($ids)   ;
     if(!$investments)
     return "No data";
     
     
      
          
          $ids=array();
          
          foreach($investments as $investment )
    {
   
    
    array_push($ids,$investment->id);
    
    
    
    }
    
    
   
            $List = implode(', ', $ids);
  
 


     $qr=" update  exchange_investment  set   days_completed =CONCAT( days_completed,',', CURRENT_DATE()) where status ='Active'  and id in (".$List .")"; 
     
  
         DB::statement($qr);
         
         
         
          
            exit();
      /*      
            
 //   B::statement( "update  exchange_investment  set   days_completed =CONCAT( days_completed,',', CURRENT_DATE()) where 
 //   id in (SELECT  * FROM `exchange_investment` where status ='Active' and  days_completed not like CONCAT('%', CURRENT_DATE(), '%')  )   " ); ;
          
          
          // CREATE TABLE delete___updatedays__exchange_investment__  AS  SELECT  id FROM `exchange_investment` where status ='Active' and  days_completed not like CONCAT('%', CURRENT_DATE(), '%')  ) 
          
          
          
 //  update  exchange_investment  set   days_completed =CONCAT( days_completed,',', CURRENT_DATE()) where 
    //id in select id from      delete___updatedays__exchange_investment__ 
          
       //   SELECT  id  FROM `exchange_investment` where status ='Active'   and ( days_completed is null or    days_completed not like CONCAT('%', CURRENT_DATE(), '%')  );
          
   $qr="  SELECT  * FROM `exchange_investment` where status ='Active' and  days_completed not like CONCAT('%', CURRENT_DATE(), '%')      " ;
        
    $invs=DB::select($qr)   ;
    
    foreach($invs as $inv)
    {
        
        
    $days_different=$this-> getDaysCompleted($inv);
    
    echo "------" .$days_different;
    
    echo "------" .$inv->created_at;
    
    
  if($days_different < 30 )
    
       {
           
  
       
           
     $qr=" update  exchange_investment  set   days_completed =CONCAT( days_completed,',', CURRENT_DATE()) where status ='Active'  and id = ".$inv->id; 
     
     echo $qr;
         DB::statement($qr);
       }
       
       
       
    
	
    }
  */
  }
  public function updateInvestment($id,$status)
  {
      
    	$ar=array();
    $ar['Error']=false;  
    
    
       $user = Auth::user() ;
     
                 $user_id= $user->id;
                 
                 
                 
	if($status=="Pause" or $status=="Cancel" )
	{
	   	$this->stopInvestment($id,$status); 
	   	
	   	
    $ar['message']=  "Successfully updated  the status to ".$status;
	}

	
	if($status=="Active")
	{
	$this->startInvestment($id,$status);
	
$ar['message']=  "Successfully updated  the status to ".$status;
}
    
    
    
 $data=DB::table('exchange_investment')->where('id',$id)->where('user_id',$user_id)->first()  ;
 
 
     $ar['investment']=   $data;
    
    return response()->json($ar);
	
  }
  
  
  
  
  
  
   public function donate_robot( )
  {
      
      
      
    
       $user = Auth::user() ;
     
     $user_id= $user->id;
     
     
     
 	$ar=array();
    $ar['Error']=false; 
    
    
         $email= $this->request['email'];
         
           $robot_id= $this->request['robot_id'];
           
 $u = DB::table('users') ->where('email',$email)->first()  ;
 
 
 
 if($u)
 {
 
 $new_user_id=$u->id;

          
 DB::table('exchange_investment')->where('id', $robot_id)->where('user_id',$user_id)->update(['user_id' =>$new_user_id]);
    
  $ar['message']=  "Successfully transferred";
    
 

 }
 else
 
 {
    $ar['message']=  "It look like email id ".$email." is not registered with us so we can not process the request";
     
     
 }
    

        
 $data=DB::table('exchange_investment')->where('id',$robot_id) ->first()  ;
 
 
     $ar['robot_id']=   $robot_id;
     $ar['investment']=   $data;
    return response()->json($ar);
	
  }
  
  
  
  
 //  public function parents_referred_robot($user )
  //{
      
      
   public function parents_referred_robot_v1($user )
  {
     
       $user_id= $user->id;
       
       $robot=Exchange_investment::where('license_fee', 0) ->where('user_id',$user_id)->first()  ;
 if( $robot) return "";
 
 
 
 
 
      // get user id
      // get parents user id 
      // get parents robots only first
      // duplicate robot with logged in user id as owner
      
      
   
      
      // step 1
    
    //   $user = Auth::user() ;
     
    
     
     // step 2
     
   
 //  refer_id
 
 // step 3
 
 
//$flight = Flight::where('number', 'FR 900')->first();
 $robot=Exchange_investment::where('user_id',$user->refer_id) ->first()  ;
 
 
 
 
 
 if(!$robot) return "";
 
 
  // step 4

$newRobot = $robot->replicate();

$newRobot->created_at = Carbon::now();

$newRobot->license_fee=0;// as free



$newRobot->refer_income=1;
$newRobot->description="Gift Rebot from the referal id ".$user->refer_id ;
$newRobot->days_completed=0;
$newRobot->hourly_income=0;
$newRobot->hourly_income_string=0;

$newRobot->Status_updated_at= Carbon::now(); 
$newRobot->save();
 
 
  return "success";
	
  }
  
  
  
  
   public function sell_robot( )
  {
      
      
      
    
       $user = Auth::user() ;
     
     $user_id= $user->id;
     
     
     
 	$ar=array();
    $ar['Error']=false; 
     
         
$robot_id= $this->request['robot_id'];
    
DB::table('exchange_investment')->where('id', $robot_id)->where('user_id',$user_id)->update(['listed' =>'listed']);
    
 $ar['message']=  "Successfully listed";
     
 $data=DB::table('exchange_investment')->where('id',$robot_id) ->first()  ;
 
 
     $ar['robot_id']=   $robot_id;
     $ar['investment']=   $data;
    return response()->json($ar);
	
  }
  
  
  
  
  public function stopInvestment($id,$status)
  {
    return "";
    
      $user = Auth::user() ;
     
      $user_id= $user->id;
      
      
          
      $qr="  SELECT *,DATEDIFF(now(), Status_updated_at ) days_different FROM `exchange_investment` where status  = 'Active' and id= ".$id ;
        
      $inv=DB::select($qr)   ;
     
      if($inv)
      
    
      {
       
       $investment=$inv[0];
       
 //      $days_completed=$investment->days_different+ $investment->days_completed;
       
       if($investment->plan_name=="robot 11") return "";
       
       
//	DB::table('exchange_investment')->where('id', $id)->where('user_id',$user_id)->update(['status' =>  "Pause",
//	"Status_updated_at" => Carbon::now() ,"days_completed"=> $days_completed ]);
       
	DB::table('exchange_investment')->where('id', $id)->where('user_id',$user_id)->update(['status' =>  "Pause",
	"Status_updated_at" => Carbon::now()   ]);
   
   
   // $user1=DB::table('users')->where('email', "susheel3010@gmail.com")->first()  ;

    $message="Return the amount after the pause of the robot id ".$id;
       
       
    $pl=new Paymentcls();
     
    //$res=   $pl->debit_user( $user1 , $investment->amount,$investment->coin ,	$message ,$id  );
    $res=  $pl->credit_user( $user ,  $investment->amount,$investment->coin ,	$message ,$id);
          
          
          
          
      }
      
  }
  
  
  
  
    public function startInvestment($id,$status)
  {
     
      
      
       
       $user = Auth::user() ;
     
                 $user_id= $user->id;
       
       
 $inv=DB::table('exchange_investment')->where('status','<>', "Active")->where('id',$id) ->first()  ;
 
 if(!$inv) return "";
 
//	DB::table('exchange_investment')->where('id', $id)->where('user_id',$user_id)->update(['status' => $status,  "Status_updated_at" => Carbon::now()   ]);

 
           
           
 $user1=DB::table('users')->where('email', "susheel3010@gmail.com")->first()  ;

 $message="Debit of the robot amount after purchase of lincese fee for the investment id ".$id;
       
       
       
       
           $pl=new Paymentcls();
     
     
    $pl->convert_currency($user,$reference , "BRL");
     
     
    $pl->convert_currency($user,$reference ,"USDT");
    
    
    
        $res=   $pl->debit_user( $user1 , $inv->amount,$inv->coin ,	$message ,$id );
        
        
 if($res) return "";
        
        
       $res=  $pl->credit_user( $user ,  $inv->amount,$inv->coin ,	$message,$id );
      
         
         
             
	DB::table('exchange_investment')->where('id', $id)->where('user_id',$user_id) ->update(['status' =>  "Active",  "Status_updated_at" => Carbon::now()   ]);
        
        
        
       /*    $n=array();
	$n['message']= "Robot activated successfully and payment was debited form the account";
	
	$n['type']="success";
	$n['url']="";
	
	$n['user_id']=$user->id;
	
	$n['user_login']=$user->username;
	
	
	$noti=new SystemNotification();
	
	$noti->system_notification_new($n);
	
	*/
	 
       
       
       
    
  }
  
  
  
  
  
  
  
  
      public function buy_this_robot($id )
  {
      
      
       $user = Auth::user() ;
     
                 $user_id= $user->id;
                 
      //1 get the owner user id
      //2 get the purchase cost
      // 2--a check if user have enough fund or  not if not fund force him to deposit then process
      
      //3 transfer fund to the seller from buyer
      
      //4 transfer admin fee for seller
      //5 transfer admin fee for the buyer
      
      
      //6 update ownership of the robot
      //7 update robot status to sold
      //8 update listed status  listed , sold ---- purchased 
      
      //9 robot ownership log
      
      
        $ar=array();
    $ar['Error']=false; 
    
      // step 0 
 $robot=DB::table('exchange_investment')->where("listed","listed")->where('id',$id) ->first()  ;
 
 if(!$robot)
 {
             
    $ar['Error']=true; 
    
    $ar['message']=  "Robot was not found or already sold.";
    
    return response()->json($ar);
     
 }
 
 // step   1 get the owner user id
 
 $robot_owner_id=$robot->user_id;
 
  // step 2 get the purchase cost
      
      
      $robot_purchase_cost= $robot->license_fee; 
      
      
        $coin=$robot->coin;
        
        $balance= $user->$coin;
        
          // 2--a check if user have enough fund or  not if not fund force him to deposit then process
   
       if($robot_purchase_cost  >=  $balance)
      {
          
           
    $ar['Error']=true; 
    
    $ar['message']=  "Do not have sufficient balance kindly deposit fund";
    
    return response()->json($ar);
    
      }
      
      
      // step 3  transfer fund to the seller from buyer
     
     $buyer=$user;
     
     
 $seller=DB::table('users')->where('id',$robot_owner_id) ->first()  ;
 
   if(!$seller) return "";
   
   
       $pl=new Paymentcls();
       
       $sender=$buyer;
       $receiver=$seller;
       $amount=$robot_purchase_cost;
     
       $description="Trade for the robot ".$id;
       $reference=$id;
       
     $pl->payment_transfer( $sender,$receiver,  $amount ,$coin,$description,$reference );
     
     // step 4 take fee from the seller
     
        $admin_fee=$robot_purchase_cost * 0.02;
        
        
     
     $admin =DB::table('users')->where('email', "susheel3010@gmail.com")->first()  ;
     
     if(!$admin)  return "";
     
     
        $receiver=$admin;
         $sender=$seller;
         
         $seller_admin_fee=$admin_fee;
         
     $description="Admin fee for the Trade of the robot ".$id;
      $pl->payment_transfer( $sender,$receiver,  $seller_admin_fee ,$coin,$description,$reference );
     
         // step 5 take fee from the buyer
     
         $sender=$buyer;
         $buyer_admin_fee=$admin_fee;
     $description="Admin fee for the Trade of the robot ".$id;
      $pl->payment_transfer( $sender,$receiver,  $buyer_admin_fee ,$coin,$description,$reference );
     
     
     
     
      //6 update ownership of the robot
       
      //8 update listed status  listed , sold ---- purchased 
      
     
	DB::table('exchange_investment')->where('id', $id) ->update(['listed' =>  "sold" ,"user_id" =>$buyer->id   ]);
        
     
   
      //9 robot ownership log
      $created_by=$user_id;
      $event= "Robot sold from ".$pl->getUserName($seller->email)."  to ".$pl->getUserName($buyer->email) ." robot Id ".$id  ;
      
      $clientIP = \Request::ip();
      
      
 $id = DB::table('robot_ownership_log')->insertGetId(
    [ 'user_id' => $user_id,
    'buyer_user_id' => $buyer->id ,
    'seller_user_id' => $seller->id ,
    'event' =>  $event ,  
    'amount' =>  $amount ,
    'buyer_admin_fee' =>  $buyer_admin_fee ,
    'seller_admin_fee' =>  $seller_admin_fee , 
     "robot_id" =>$id ,
       "ip" => $clientIP ]
);
         
     
     $n=array();
     
    $n['message']=$event;
    $n['type']= "success";
    $n['url']= "/investment/".$id;
   
    
     $noti=new Notification();
	
	
	// buyer
	 $n['user_id']=$buyer->id; 
	$noti->aistore_notification_new($n);
	
	
  	//seller
  	  $n['user_id']=$seller->id; 
	$noti->aistore_notification_new($n);
	


        $ar=array();
    $ar['Error']=false; 
    
    
        
    
 $data=DB::table('exchange_investment')->where('id',$id)->where('user_id',$user_id)->first()  ;
 
 
     $ar['investment']=   $data;
     
     
    $ar['message']=  "Purchased order for the robot ".$id." was Successfully";
    
    return response()->json($ar);
      
      }
      
      
      
      
      
      
      
      
      
      
      public function ActivateInvestment($id )
  {
     
      
      
       
       $user = Auth::user() ;
     
                 $user_id= $user->id;
       
       
       
  $qr="Select * from  exchange_investment where id=".$id." and user_id= ".$user_id." and ( status='pending' or status='Pause') ";
       
  $in=DB::select($qr)   ;
  
  
  
     
 if(!$in)
 
 {
     
      
      $ar=array();
    $ar['Error']=false;  
    
 $data=DB::table('exchange_investment')->where('id',$id)->where('user_id',$user_id)->first()  ;
 
 
     $ar['investment']=   $data;
     
     
    $ar['message']=  "Unable to complete request ";
    
    return response()->json($ar);
     }
     
  $inv=$in[0];
 
           


 
     
     $balance= $user->DBIK;
     
      if($balance <  $inv->amount)
 
 {
    $ar=array();
    $ar['Error']=false  ; 
    
    $ar['inv']=$in;     $ar['user']=$user;    $ar['balance']=$balance;
    
    
    $ar['message']=  "Low balance to activate the robot".$inv->amount;
    
    return response()->json($ar);    
    
    
 }
     
     
     
     
     
     
     
     
     
     
     
     
     
 $message="Debit of the investment amount after purchase of lincese fee for the investment id ".$id;
                  
 
           $pl=new Paymentcls();
     
       $res=  $pl->debit_user( $user ,  $inv->amount,$inv->coin ,	$message ,$id);
       
       
      if(!$res)
      {
          	DB::table('exchange_investment')->where('id', $id)->where('user_id',$user_id) ->update(['status' =>  "Active",  "Status_updated_at" => Carbon::now()   ]);
          
      
      
 $user1=DB::table('users')->where('email', "susheel3010@gmail.com")->first()  ;
 
 
        $res=   $pl->credit_user( $user1 , $inv->amount,$inv->coin ,	$message ,$id );
         
            
    
    $ar['Error']=false; 
    
    $ar['message']=  "Activate Investment Successfully";
    return response()->json($ar);
      }

        

 
    $ar['Error']=true; 
 $data=DB::table('exchange_investment')->where('id',$id)->where('user_id',$user_id)->first()  ;
 
 $ar['investment']=   $data;
     
     
    $ar['message']=  "Activate Investment   failure";
    
    return response()->json($ar);
  }
  
  
  
   public function robot_total_gain(  )
  {
     
      
      
       
       $user = Auth::user() ;
     
                 $user_id= $user->id;
       
  
  
  $qr="select sum(amount) as total_amount from robot_income_daily where user_id=".$user_id;
  
  
 $data=DB::select($qr)  ;
 


  
 if($data)
return $data[0]->total_amount;

else
return 0;

    
  }
  
  
  
  
  
  
  
      public function robot_income_daily($id )
  {
     
     $user = Auth::user() ;
     
    $user_id= $user->id;
       
  
    $data=DB::table('robot_income_daily')->where('investment_id',$id)->where('user_id',$user_id)->get()  ;
  
 
    return response()->json($data );


    
  }
  
  
  
  
  
  
   
   
   
   
   
    public function investmentinfobyid($id )
  {
     
       $user = Auth::user() ;
     
      $user_id= $user->id;
      
 $data=DB::table('exchange_investment')->where('id',$id)->where('user_id',$user_id)->first()  ;
 
  if($data)
  {
      
 // $robot_income_daily=DB::table('robot_income_daily')->where('investment_id',$id)->where('user_id',$user_id)->get()  ;
 
//  $data->robot_income_daily=$robot_income_daily;
// 
//$data->days_completed=$this-> getcount(  $data->days_completed);   
 
// $robo_income_qr="SELECT sum(income) sum_income FROM `robot_income_daily` WHERE investment_id= ".$data->id;
 
 // $robo_income_res  = DB::select($robo_income_qr)  ;
 
 // $robo_income   = $robo_income_res[0]->sum_income;
  
  
    //  $data->sum_income= $data->amount + $robo_income;
  
  }
 
     $data->sum_income=    $data->hourly_income  +$data->amount  ;
     
     $days_completed= $data->days_completed;
     
      $days_completed=count( explode(",", $days_completed))-1;
 
 
 $data->days_completed=      $days_completed;
 
 
 
return response()->json($data );


    
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
  
      function buy_sell_robots( )
  {
     
      
      // listed 
      // sold
      // purchased
      // 
       
       $user = Auth::user() ;
     
                 $user_id= $user->id;
       $q="select * from exchange_investment where listed='listed' and user_id <>  ".$user_id." ";
      // $q="select * from exchange_investment  ";
       
   $q="select exchange_investment.*,users.username  from exchange_investment left join users on exchange_investment.user_id = users.id  where listed='listed'   and user_id  <> ".$user_id." ";
       
     $data_buy=DB::SELECT($q);
      
  
// $data_buy=DB::table('exchange_investment')->where('listed','listed') ->whereNotIn('user_id',[$user_id])->get()  ;
  
  
   $q="select  exchange_investment.*,users.username from exchange_investment left join users on exchange_investment.user_id = users.id  where listed='listed'   and user_id = ".$user_id." ";
       
     $your_listing=DB::SELECT($q);
     
     
// $your_listing=DB::table('exchange_investment')->where('listed','listed')->where('user_id',$user_id)->get()  ;
  
// $purchase_history=DB::table('exchange_investment')->where('listed','sold')->where('user_id',$user_id)->get()  ;
 
    $q="select * from exchange_investment where listed='sold'   and user_id = ".$user_id." ";
       
     $purchase_history=DB::SELECT($q);
     
     
  
  $ar=array();
  
  $ar['buy']=$data_buy;
  
  $ar['your_listing']=$your_listing;
  
  $ar['purchase_history']=$purchase_history;
  
 
return response()->json($ar);


    
  }
  
  
}
