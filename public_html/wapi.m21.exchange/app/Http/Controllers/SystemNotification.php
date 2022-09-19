<?php 


namespace App\Http\Controllers;

 
 
use Illuminate\Support\Facades\DB;

class SystemNotification
{  
    
    
  public    function System_echo_all_notification()
{
	    $user_id= Auth::user()->id;
        $notifications = DB::table('notification')->get();

  
     $view = View::make('system.notifications',  compact(['notifications' ])) ;
 
return $view;

}


   public function System_echo_notification( ){
       
	    $user_id= Auth::user()->id;
        $notifications = DB::table('notification')->where('user_id', $user_id)->get();

     $view = View::make('system.notifications',  compact(['notifications' ])) ;
 
return $view;
	 
}
 
  public function System_notification($notification,$type="success",$user_login="" ){
	
	  
   
}


  public function system_notification_new($n  ){
	/*
	$n=array();
	$n['message']="test notification msg";
	
	$n['type']="success";
	$n['url']="localhost";
	
	$n['user_login']=$login_email;
	*/
	
	 
	//$n['user_email']=$n['user_login'];
	
  
 
  
  DB::table('notification')->insert(['message'=>$n['message'], 'user_id' => $n['user_id']   ,  'url' => $n['url']    ,  'type' => $n['type']  ]);

   
}


}
 