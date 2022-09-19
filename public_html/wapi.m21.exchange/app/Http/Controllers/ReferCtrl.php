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


//use App\mlm_orders_investment;

 use App\mlm_system_investments;

use App\mlm_investment_orders;

use App\mlm_investment_transactions;



class ReferCtrl extends Controller
{
    
    
    public function __construct(Request $request) {
        $this->request = $request;
   
        $this->middleware(['auth' ]);
    } 


public function investment_plan($user_id,$plan){
     $data = DB::table('exchange_investment')
                     ->select(DB::raw('sum(amount) as balance '))
                        ->where('plan', $plan)
                         ->where('user_id', $user_id)
                        ->get();
     
                     
      
     
          return   $data[0]->balance  ;
            
}



  function referral_income_list(){
      
       

   $user_id=Auth::user()->id;

  $ar=array();
  
  
$ar['referral_income_list'] =   DB::table('referral_wallet_transactions')->where('user_id',$user_id)->get();
      $ar['referral_wallet_bonus_transactions'] =   DB::table('referral_wallet_bonus_transactions')->where('user_id',$user_id)->get();

     return response()->json($ar);;     

}








  function user_investment(){
$user_id= $this->request['user_id'];
      $exchange_investment =   DB::table('exchange_investment')->where('user_id',$user_id)->get();

     return response()->json($exchange_investment);;     

}
    public function referuser_list(){
     


$col="id,username email,DBIK,created_at";

    
      $ar=array();
    
       $user_id=Auth::user()->id;
        
        //level 1
        
    $q= "SELECT distinct id FROM `users` WHERE refer_id =".$user_id;
    
    $q_details= "SELECT distinct  ".$col." FROM `users` where id  in($q )   ";
 
    $user = DB::select($q_details) ;
     
    $ar['level_0']=$user;
  
    
     //level 2
   
     
     $q0="SELECT distinct id FROM `users` WHERE refer_id =".$user_id;
     
     
        $q1= "SELECT distinct id FROM `users` where refer_id in($q0 ) ";
        
        
            $q1_details= "SELECT distinct ".$col." FROM `users` where id  in($q1 )   ";
            
            
     
      $user1 = DB::select($q1_details) ;
        $ar['level_1']=$user1;
        
        
        
         //   $ar['level_1_debug']=$q1_details;
            
            
            
 //    var_dump($q1_details);

       


 //level 3
      
      
      $q1= "SELECT distinct  id FROM `users` where refer_id in($q0 ) ";
         $q2= "SELECT distinct  id FROM `users` where refer_id in($q1 ) ";
         
         
          $q2_details= "SELECT distinct ".$col." FROM `users` where id  in($q2 )    ";
      
  //  echo  $q2;
      $user2 = DB::select($q2_details) ;
        $ar['level_2']=$user2;
     
        
         //   $ar['level_2_debug']=$q2_details;
            
 //level 4
     
  
      $q2= "SELECT distinct id FROM `users` where refer_id in($q1 ) ";
      
      $q3= "SELECT  distinct  id FROM `users` where refer_id in($q2 ) ";
      
      
      
      
       $q3_details= "SELECT distinct ".$col." FROM `users` where id  in($q3)   ";

      $user3 = DB::select($q3_details) ;
          $ar['level_3']=$user3;
     
     
          //  $ar['level_3_debug']=$q3_details;
 //level 5
     
     
       
      $q3= "SELECT distinct id FROM `users` where refer_id in($q2 ) ";
     $q4= "SELECT distinct id FROM `users` where refer_id in($q3 ) ";
     $q4_details= "SELECT distinct ".$col." FROM `users` where id  in($q4)     ";
      

      $user4 = DB::select($q4_details) ;
          $ar['level_4']=$user4;
           
           
           
           
           
           
       
      $q3= "SELECT distinct id FROM `users` where refer_id in($q2 ) ";
     $q4= "SELECT distinct id FROM `users` where refer_id in($q3 ) ";
     
     $q5= "SELECT distinct id FROM `users` where refer_id in($q4 ) ";
   
     $q5_details= "SELECT distinct ".$col." FROM `users` where id  in($q5)  ";
      

      $user5 = DB::select($q5_details) ;
          $ar['level_5']=$user5;
           
           
            
           
           
            
     $q6= "SELECT distinct id FROM `users` where refer_id in($q5) ";
   
     $q6_details= "SELECT distinct ".$col." FROM `users` where id  in($q6) ";
      

      $user6 = DB::select($q6_details) ;
          $ar['level_6']=$user6;
           
           
            
     $q7= "SELECT distinct id FROM `users` where refer_id in($q6) ";
   
     $q7_details= "SELECT distinct ".$col." FROM `users` where id  in($q7) ";
      

      $user7 = DB::select($q7_details) ;
          $ar['level_7']=$user7;
           
           
           
            
     $q8= "SELECT distinct id FROM `users` where refer_id in($q7) ";
   
     $q8_details= "SELECT distinct ".$col." FROM `users` where id  in($q8) ";
      

      $user8 = DB::select($q8_details) ;
          $ar['level_8']=$user8;
           
           
           
            
     $q9= "SELECT distinct id FROM `users` where refer_id in($q8) ";
   
     $q9_details= "SELECT distinct ".$col." FROM `users` where id  in($q9) ";
      

      $user9 = DB::select($q9_details) ;
          $ar['level_9']=$user9;
           
           
           
           
            
     $q10= "SELECT distinct id FROM `users` where refer_id in($q9) ";
   
     $q10_details= "SELECT distinct ".$col." FROM `users` where id  in($q10) ";
      

      $user10 = DB::select($q10_details) ;
          $ar['level_10']=$user10;
          
           
           
           // $ar['level_4_debug']=$q4_details;
          DB::disconnect();
            
return response()->json($ar);
      
// return view('network.referuserslist'   ,['data' => $ar]);
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
             
             $st->invoice_id = "INVESTMENT".$id; 
       
           $st->status =  "Success";
        
       
             $st->description =  "Investment of ".$this->request['invest_amount']." for the package ".$this->request['package_name'] ;
         
       
        
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
        
       
             $si->description = "Investment of ".$this->request['invest_amount']." for the package ".$this->request['package_name'] ." Investment ID ". $in->id;
         
         
       
        
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



}
