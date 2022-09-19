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

 
use App\Http\Controllers\Controller;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;


use App\Notifications\Login;
 
class Notification extends Controller
{ 

    public function __construct( )
    {
         
    
            
            
    }
 
   
 

 public function aistore_echo_all_notification()
{
	 
ob_start();
 if ( !is_user_logged_in() ) {
    return "Please login to see your notifications" ;
}
$user_email = get_the_author_meta('user_email', get_current_user_id());
  

    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}escrow_notification WHERE user_email='$user_email'   order by id desc limit 10";
	
	 

  $v1= $wpdb->get_results($sql );
	 
	foreach ($v1 as $row):
            
?> 
  
  <div class="discussionmsg">
   
  <p><a href="<?php echo $row->url; ?>"> <?php echo html_entity_decode($row->message); ?> </a> </p>
  
  
  <h6 > <?php echo $row->created_at; ?></h6>
</div>
 
<hr>
    
    <?php
        endforeach;  
	
 return ob_get_clean();	

}


 function aistore_echo_notification( ){
	 if ( !is_user_logged_in() ) {
    return "Please login to see your notifications" ;
}
$user_email = get_the_author_meta('user_email', get_current_user_id());
    global $wpdb;
	
	
$notification = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}escrow_notification WHERE   user_email = '".   $user_email."' and status =0 order by id   desc  limit 1"   );
 
 
 
 

	
	
	 if(isset($notification->type))
	 {
		  $qr=$wpdb->prepare("UPDATE {$wpdb->prefix}escrow_notification
    SET  status =  status+1   WHERE id =  %d ", $notification->id);
	
    $wpdb->query($qr);
	
	
return ' <div class="alert alert-'.$notification->type .'" role="alert"> '. $notification->message.'</div>';
	 }

else
return "";

 			
}
 
 public  function aistore_notification($notification,$type="success",$user_login="" ){
     
     
	 if ( !is_user_logged_in() ) {
    return "Please login to see your notifications" ;
}
	 if($user_login=="")
	 {
 	$user_login = get_the_author_meta('user_login', get_current_user_id());
	 }
	
   global $wpdb;
 
 
  

   $q1=$wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_notification (  message,type, user_email ) VALUES ( %s, %s, %s ) ", array(  $notification,$type, $user_login));
     $wpdb->query($q1);
   
   
}


 public function aistore_notification_new($n  ){

 
	   
  $trid = DB::table('system_notification')->insertGetId(
    [ 'message' =>$n['message'],'type' => $n['type']  ,'url' =>  $n['url'] ,'user_id' =>$n['user_id'],'ip' =>\Request::ip() ]
);



 
	 
    
   
}


 

public function  insert_notification( $user,$amount ,$coin,$description)
{ 
    
    
    $error=true;
    $old_balance= $user->$coin  ;
    $new_balance=  (double)$old_balance+(double)$amount;
    
    
     
    
  $trid = DB::table('system_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin  ,'type' =>  'credit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' =>$description   ]
);


// $user->$coin =$new_balance;

// $user->save();

$updateDetails = [
    $coin => $new_balance,
    
];

DB::table('users')
    ->where('id', $user->id)
    ->update($updateDetails);
  
$error=false;
 
 return $error;
    
    
}


public function debit_user( $user,$amount,$coin,$description )
{ 
    
    $error=true;
     $old_balance= $user->$coin;
     
    $new_balance=   (double)$old_balance- (double)$amount;
    
     
    if(   (double) $new_balance > 0)
    {
 $trid = DB::table('system_transactions')->insertGetId(
    [ 'user_id' => $user->id ,'coin' => $coin  ,'type' =>  'debit' ,'amount' => $amount  ,'balance' => $new_balance  ,'status' =>"Success" ,'description' => $description  ]
);


$updateDetails = [
    $coin => $new_balance,
    
];

DB::table('users')
    ->where('id', $user->id)
    ->update($updateDetails);
$error=false;
}
else
{
       $error=true;
}
 

 
 return $error;
    
    
}
            
}