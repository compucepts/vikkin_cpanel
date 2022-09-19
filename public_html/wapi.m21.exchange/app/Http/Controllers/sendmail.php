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

class sendmail extends Controller
{ 

    public function __construct( )
    {
         
    
            
            
    }
    
    
    
 public function sendmail($to,$link  ){
     
     $url = 'https://api.elasticemail.com/v2/email/send';

$subject='Link for the processing Widthdraw ';

try{
        $post = array('from' => 'support@vikkin.ltd',
		'fromName' => 'vikkin.ltd',
		'apikey' => '1DBD7C47F7CFFC8429DCAA5E88F8D73F361ADB476AA21D86523E9D56B705429EA77B38BC8D9AEFBC8ACC24E492B73238',
		'subject' => $subject,
		'to' => $to,
		'bodyHtml' =>  $this->getLinkTemplate($link),
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

 function getLinkTemplate($link)
       {
     $str='      
           <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Vikkin INTELIGÊNCIA ARTIFICIAL</a>
    </div>
    <p style="font-size:1.1em">Hi,</p>
    <p>Thank you for choosing Vikkin INTELIGÊNCIA ARTIFICIAL. please open the following link  to complete your procedures. Link is valid for 25 minutes</p>
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;"><a href="'.$link.'">'.$link.'</a> </h2>
    <p style="font-size:0.9em;">Regards,<br />Vikkin INTELIGÊNCIA ARTIFICIAL</p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p>Vikkin INTELIGÊNCIA ARTIFICIAL</p>
       
    </div>
  </div>
</div>';

return $str;


}

 
    
}


