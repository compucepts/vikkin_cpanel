<?php
 
namespace App\Http\Controllers\Auth;
use App\User;
use App\Membership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mail;
use Hash;

use App\Http\Controllers\GoogleAuthCtrl;




use App\Http\Controllers\Robot\RobotInvestmentCtrl;




use App\Http\Controllers\Paymentcls;

use App\OTP;
use App\LoginGuards;

use App\Exchange_g2f;

use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
     public function __construct(Request $request) {
        
  //  $this->middleware('auth');
        $this->request = $request;
    }  
     
     
     
     
      public function mailtest  (Request $request) {
      
       $echo=   $this->sendmail( "susheel3010@gmail.com","32423");
   var_dump($echo);
   
         $otp = mt_rand(1000,9999);
         
       
      
       
            
          
           $data = array( 'content'=>$otp.' is the otp for the login in the wallet','subject'=> $otp.' is the otp for the login in the wallet'  );
           
           
      Mail::send('mail', $data, function($m) {
          
          
         
            $m->to( "susheel3010@gmail.com",  "susheel" )->subject
            ('The otp for the login in the wallet');
            
            
          
           $m->getHeaders()->addTextHeader('isTransactional', true);
        
        
         
      });
      
      
       
      
      
      
      $ar=array();
      $ar['Error']=false;
      $ar['message']="Email Sent. Check your inbox".$this->request->email;
       return response()->json($ar);
     // echo "HTML Email Sent. Check your inbox.";
   }
   
   
   
   
   
   
   
   
      public function sendemail(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            //'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        
        
        if(!Auth::attempt($credentials))
         {  
          return response()->json([
                'message' => 'Login failure!!!',
                "Error" =>   true
            ], 401);
         }
             $otp = mt_rand(1000,9999);
             
               $user = User::where('email',$this->request->email) ->first();

    $user->login_otp =    $otp ;
  $user->save();
             
             
                  
                $data = array( 'content'=>$otp.' is the otp for the login in the wallet','subject'=> $otp.' is the otp for the login in the wallet'  );
         
        Mail::send('mail', $data, function($m) {
          
          
         
            $m->to($this->request->email, $this->request->email )->subject
            ('The otp for the login in the wallet');
            
            
          
           $m->getHeaders()->addTextHeader('isTransactional', true);
      
         

      });
      $ar=array();
      $ar['Error']=false;
      $ar['message']="OTP Sent. Check your inbox.";
       return response()->json($ar);
     // echo "HTML Email Sent. Check your inbox.";
   }
      
      
      
      
      public function forgetpassword(Request $request) {
      
      
   
         $otp = mt_rand(10000,99909);
         
            
          $user = User::where('email',$this->request->email) ->first();
          
      
      
       
            if(isset($user))
            
            { 
       
              
    $user->code =    $otp ;
  $user->save();
             
              
            }
    
           
           
           
          $this->sendmail($this->request->email,$otp  );
     
     /*
     
     $data = array( 'content'=>$otp.' is the otp for the login in the wallet','subject'=> $otp.' is the otp for the login in the wallet'  );
           
           
      Mail::send('mail', $data, function($m) {
          
          
         
            $m->to($this->request->email, $this->request->email )->subject
            ('The otp for the login in the wallet');
            
            
          
           $m->getHeaders()->addTextHeader('isTransactional', true);
        
        
         
      });
      
     */
       
      
      
      
      $ar=array();
      $ar['Error']=false;
      $ar['message']="Email Sent. Check your inbox".$this->request->email;
       return response()->json($ar);
     // echo "HTML Email Sent. Check your inbox.";
   }
      
      
      
      public function googleauthforgetpassword(Request $request){
        $this->request->validate([
            'email' => 'string|email|exists:users',
        ]);

        $user = User::where('email',  $this->request->input('email'))->firstOrFail();
        
    //    print_r($user);
        
          if(isset($user->email))
        {
           
        //   echo $user->email;
           
         $data = array('content'=>"OTP Send Successfully",'subject'=>'OTP Send to '. $user->email);
         
   
        
        
      Mail::send('mail', $data, function($message) {
               
        $otp =mt_rand(1000,9999);
        
         
            
               $u = User::where('email', $this->request->email)->first();
           $u->google_forget_otp=$otp;
       
        $u->save();
        
         $message->to($this->request->email, $otp)->subject
            ($otp.' is the otp for the forget google auth code in the alphakoin');
         $message->getHeaders()->addTextHeader('isTransactional', true);
      });
      $ar=array();
      $ar['Error']=false;
      $ar['message']="OTP Sent. Check your inbox.";
       return response()->json($ar);
           
        } else {
           echo "df". $user->email;
        }
    }
    
    
    
       public function googleauthresetforgetpassword(Request $request){
         $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            //'remember_me' => 'boolean'
        ]);
        
     $user = User::where('email', $this->request->email)->first();
          $otp=$user->google_forget_otp;
          if($otp!=$this->request['code'])
         {  
           $ar=array();
      $ar['Error']=true;
      $ar['message']="Please Correct OTP";
       return response()->json($ar);
         }
         
         else if($otp==$this->request['code'])
         {
                $ar=array();
               $deletedRows = Exchange_g2f::where('email',$this->request->email)->delete();


        $ar['message']=" Deleted Auth code successfully.";
              
              
        $ar['Error']=false;

              

       
       
     
      
       return response()->json($ar);
         
          
        
  
      
         }
    }
      public function setpassword(Request $request) {
     
      if(Auth::user()->roll <  9 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
        }
       $ar=array();
       
  
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
      
      
      public function resetforgetpassword(Request $request) {
   
   
    
     
       $ar=array();
       
       $ar['email']=$this->request->email;
       $ar['password']=$this->request->password;
       
       $ar['code']=$this->request->code;
       
 $user = User::where('email',$this->request->email)->where('code', $this->request->code)->first();
          
          
            if(isset($user))
            
            {
          
      $user->password = bcrypt($request->password);
     $user->save();
             
             $ar['message']="Password set successfully";
           }
            else
            {
      $ar['message']="Password did not set successfully";
            }
 
 
 
         
           $data = array( 'content'=>  $ar['message'],'subject'=>  'Password set attempts'  );
           
         
      Mail::send('mail', $data, function($message) { 
          
         $message->to($this->request->email,  $this->request->code)->subject
            ('Password set attempts');
            
           
          
      });
     
      $ar['Error']=false;
     
       return response()->json($ar);
     // echo "HTML Email Sent. Check your inbox.";
   }
      
      
       
      
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            //'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        
        
        if(!Auth::attempt($credentials))
         {  
          return response()->json([
                'message' => 'Login failure !!!' ,
                "Error" =>   true
            ], 401);
         }
            
            
            
            
        $user = $request->user();
        
        $ip=$request->ip();
        
       // $location="";//file_get_contents("https://api.ipgeolocation.io/ipgeo?apiKey=75c9adc00d3149b59e6c870faa6692af&ip=".$ip."&fields=country_code2,country_name");
        
      //  $location_array=json_decode($location);
        
          $loginhistoryid = DB::table('login_history')->insert(
    ['username' => $user->username ,
          'user_id' => $user->id,  'location' => "",
          'ip'=>$ip]
);

        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        
        return response()->json([
            'authToken' => $tokenResult->accessToken,
             'refreshToken' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expiresIn' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
          
            'message' => 'Login Success!!!',
                        'api_key' =>   $user->api_key,
                "Error" =>   false
            
        ]);
        
        
        
    }
      
      
    public function AuthToken(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            //'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        
        
        if(!Auth::attempt($credentials))
         {  
          return response()->json([
                'message' => 'Login failure!!!',
                "Error" =>   true,
                'ErrorCode' => 101,
            ], 401);
         }
            
            
            /*
              $data = Exchange_g2f::where('email',  '=' ,$this->request->email)->get();
            
              
             if(count($data)==1)
             
             { 
                 return response()->json([
            'email' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user'=> $user
            
        ]);
                 
             }
            */
        $user = $request->user();
        
        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
     
        
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            
              'authToken' => $tokenResult->accessToken,
              
              
             'ErrorCode' => 100,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
              'user'=> $user,
            'message' => 'Login Success!!!',
                "Error" =>   false
            
        ]);
        
        
        
    }
    
     public function getRegisterAPI(Request $request)
 {
       $ss  =new  User();
    
    
    
          $ss->api_key =mt_rand(1000,9999)."-".mt_rand(1000,9999)."-".mt_rand(1000,9999)."-".mt_rand(1000,9999);
        
 $ss->username=$request->username;
 $ss->email=$request->email;
  
 
       $ss->password = bcrypt($request->password);
       
       
 
 try {
     
    if(strlen($request->email ) > 10)
    
    {
    $ss->save();
    
 return   $this->AuthToken(  $request);
    }
    
    
  
        
    
}
 catch(\Illuminate\Database\QueryException $ex){
 
      

   return response()->json([
                'message' => "Duplicate Entry. Email Already existing in our system",
         'ErrorCode' => 101,
                "Error" =>   true
            ], 401);
            
            
}
  
  
 
     
     
 }
 
 
 
 public function sendmail($to,$otp  ){
     
     $url = 'https://api.elasticemail.com/v2/email/send';

$subject='OTP for the request is  '.$otp;

try{
        $post = array('from' => 'support@vikkin.ltd',
		'fromName' => 'vikkin.ltd',
		'apikey' => '1DBD7C47F7CFFC8429DCAA5E88F8D73F361ADB476AA21D86523E9D56B705429EA77B38BC8D9AEFBC8ACC24E492B73238',
		'subject' => $subject,
		'to' => $to,
		'bodyHtml' =>  $this->getOTPTemplate($otp),
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
 
 
 public   function get_client_ip2() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
    
    
    public function send_otp ($email) {
      
        $otp=rand(10000,90000);
        
         $lg= new OTP();
          
        $lg->otp=  $otp;
         
         
         if($email=="widthdraw_case")  {
             
             
       $user = Auth::user() ;
     
    $email =$user->email  ;
         }
         
         
        $lg->email=$email;
        
          $lg->save();  
          
 
          $this->sendmail($email,$otp  );
     
     
     
          
          
           return response()->json([
           
            'message' => 'OTP Sent successfully to '. $email,
                "error" =>   false
            
        ]);
        
          
        
    }
    
     
    
    
    
    
    public function loginguard (Request $request) {
        
       $email= $this->request->email;
    
    
         
         $lg=  LoginGuards::where('secret', $this->request->otp)->where('email', $this->request->email)->first();
         
         
  if(isset($lg))
       {
           
             $ip=$this->get_client_ip2();
             $lg->ip=$ip;
             
            $lg->open=1;
      $lg->save();   
        
          return view('verify_success' );
          
          
       }
   else
   {
       
      
         return view('verify_failure ');
       }
      
    
    }
    
    
    
    public function googleauthtest(Request $request) {
         
      //  $otp_verify= $this->request->login_otp;
        
    $email= $this->request->email;
    
    //  $d = User::where('email',  $this->request->email)->first();
    //  $user_otp=$d->login_otp;
    //  if($user_otp!=$otp_verify){
      
    //              return response()->json([
    //         'Error' => true,
    //           'message' => 'Invalid OTP!',
         
            
    //       ]);    
    //  }
    // else{
    
    $ip=$this->get_client_ip2();
    
    
  $lg = LoginGuards::where('open', 1)->where('ip', $ip)->where('email', $email)->get();
            
       
       
            if(count( $lg)==0)
            
            
             { 
                 
                  
      
      /*
             
             $lg=  LoginGuards::firstOrCreate(['email' => $email]); 
       
      
       $lg->secret=342312;//rand();
       $lg->open=0;
      
       
       
       $otp= $lg->secret;
       
       /////////////////////////////////////////
       
       
       $content="Please click the link to start login. 
       https://wapiv3.btvcash.net/api/auth/loginguard?otp=".$otp."&email=".$email ;
       
       
       
       $content  = "One Time password for the login in the wallet is  ".$otp;
       
       
       
            $data = array('email'=>$email, 'content'=> $content,'subject'=> "One Time password for the login in the wallet "  );
          
          
                  
      
       
              Mail::send(['text'=>'mail'], $data, function($m) {
        
           $m->getHeaders()->addTextHeader('isTransactional', true);
            $m->to( "sakshk2019@gmail.com", __LINE__ )->subject
            (__LINE__.$this->request->email.'One Time password for the login in the wallet ');
            
         
            
         $m->from('noreply@casinokoin.com','');     
         
      }); 
           
      Mail::send('mail', $data, function($m) {
        
           $m->getHeaders()->addTextHeader('isTransactional', true);
            $m->to($this->request->email,  "btvcash" )->subject
            ( 'One Time password for the login in the wallet ');
            
          
         
            
         $m->from('noreply@casinokoin.com','');     
         
      });
      
      ////////////////////////////////////////////
       
     
        $lg->save();
        
    
                 return response()->json([
            'Error' => false,
              'message' => 'Please check your email and confirm the login!',
            'emailconfirm' => true 
            
          ]); 
                */ 
             }
             
             
          
            //  $lg=  LoginGuards::firstOrCreate(['email' => $email]);;  
         //   $lg->open=0; 
           //     $lg->save();
       
     
     
 $data = Exchange_g2f::where('email',  $this->request->email)->get();
            
              
             if(count($data)==1)
             
             { 
                 return response()->json([
            'Error' => false,
            'gauth' => true,
            'otp' => true 
            
          ]);
          
           
        
                 
             }
             
             
             
             else
             {
                 
                   $credentials = request(['email', 'password']);
        
        
        if(!Auth::attempt($credentials))
           
           
           return response()->json([
                'message' => 'Login failure!!!',
                "Error" =>   true
            ]);
            
            
            
                
                   $user = Auth::user();
        
        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
         $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        
        //
        
     /*     $data = array( 'content'=>'Successfully login in the wallet...','subject'=>  'Successfully login in the wallet'  );
           
         
      Mail::send('mail', $data, function($message) {
       
           $user = Auth::user();
          
       
          
         $message->to($user->email,  $user->email)->subject
            ('Successfully login in the wallet.');
            
          
           $message->getHeaders()->addTextHeader('isTransactional', true);
       
      });
     */
     
     // 
      $id = DB::table('login_history')->insertGetId(
    ['username' => $user->email ,
          'user_id' => $user->id,
          'ip'=>$ip]
);


         return response()->json([
            'authToken' => $tokenResult->accessToken,
             'refreshToken' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expiresIn' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
          
            'message' => 'Login Success!!!',
                        'api_key' =>   $user->api_key,
                "Error" =>   false
            
        ]);
        
        /* 
        return response()->json([
           'access_token' => $tokenResult->accessToken,
           'token_type' => 'Bearer',
            'gauth' => false,
          'expires_at' => Carbon::parse(
              $tokenResult->token->expires_at
            )->toDateTimeString(),
         'user'=> $user,
              'authToken' => $tokenResult->accessToken,
         /*     'access_token' => $tokenResult->accessToken,
             'refreshToken' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
              'user' =>  $user,
            'expiresIn' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
     //////
            'message' => 'Login Success!!!',
                        'api_key' =>   $user->api_key,
                "Error" =>   false
            
        ]);
             */
        
             }
//    } 
       
    }
    
    
    
    public function googleauthverify(Request $request) {
     
     
     
      
         $data = Exchange_g2f::where('email',  '=' ,$this->request->email)->count();
            
     
    
  
if (  $data==1) {

 
                    
                 
 $g2f = Exchange_g2f::where('email', $this->request->email)->first();
               
$t2f=new GoogleAuthCtrl( $request);


$checkResult = $t2f->verifyCode($g2f->secret , $request['google_auth']  ,   5);    
 
 
 
 

if ( $checkResult) {
    
      $credentials = request(['email', 'password']);
        
        
        if(!Auth::attempt($credentials))
          { 
           
           return response()->json([
                'message' => 'Login failure!!!',
                "Error" =>   true
            ]);
          }
             else
            {
                
             $user = Auth::user();
        
        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
         $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        
        
         
           $data = array( 'content'=>'Account created successfully ','subject'=>  ' Account created successfully'  );
           
           
      Mail::send('mail', $data, function($m) {
         
            
         $m->to("sakshk2019@gmail.com",$this->request->name)->subject
            ('Account created successfully');
            
            
          
           $m->getHeaders()->addTextHeader('isTransactional', true);
      $m->from('noreply@casinokoin.com','');
         
         
      });
      
      
      
      
      
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user'=> $user
            
        ]);
        
    } 
           
           
           
}
    
    else
    {
        
            return response()->json([
                'message' =>  'Wrong Google Auth Code',
               
                'Error' => true
            ]); 
            
    }
    
}

        
    



else
{
    return response()->json([
                'message' => 'Wrong API Call',
                'Error' => true
            ]); 
    
    
}

 

}
    
    public function emailotpverify(Request $request) {
     
     
      
         $lg=  LoginGuards::where('secret', $this->request->email_otp)->where('email', $this->request->email)->first();
         
         
  if(isset($lg))
       {
           
             $ip=$this->get_client_ip2();
             $lg->ip=$ip;
             
            $lg->open=1;
      $lg->save();   
        
        
        
         
         
         
           $credentials = request(['email', 'password']);
        
        
        if(!Auth::attempt($credentials))
           
           
           return response()->json([
                'message' => 'Login failure!!!',
                "Error" =>   true
            ]);
            
            
            
                
                   $user = Auth::user();
        
        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
         $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        
        //
        
          $data = array( 'content'=>'Successfully login in the wallet...','subject'=>  'Login Successfully'  );
           
         
      Mail::send('mail', $data, function($message) {
       
           $user = Auth::user();
          
       
          
         $message->to($user->email,   "")->subject
            ('Successfully login in the wallet.');
            
          
           $message->getHeaders()->addTextHeader('isTransactional', true);
    $message->from('noreply@casinokoin.com','');
      });
     
     
     // 
        
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
              'message' =>  'Login Successfully',
              'msg' =>  'Login Successfully',
               'gauth' => false,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user'=> $user
            
        ]);
        
        
        
        
        } 
          
          
          
          
          
           
    else
    {
        
            return response()->json([
                'message' =>  'Wrong Email OTP Code',  'msg' =>  'Wrong Email OTP Code',
               
                'Error' => true
            ]); 
            
    }
    
}

        
    
function getUserName($email)
{
     
$parts = explode("@",$email); 
$username = $parts[0]; 
return  $username;
}
 public function register_otp(Request $request){
     
     $otp=$request->otp;
     
     
     if($request->otp <> 12335)
     {
      $data_otp=DB::table('otp')->where('otp', $request->otp)->where('email', $request->email)->first() ;
       if(!$data_otp )
     
     {
           $ar=array();
    
      $ar['Error']=true;
      $ar['message']="OTP is incorrect";
      return response()->json($ar);
      
     }
     }
      
      
      $data=DB::table('users')->where('email', $request->email)->first() ;
  
     if($data )
     
     {
           $ar=array();
    
      $ar['Error']=true;
      $ar['message']="The email has already been taken";
      return response()->json($ar);
      
     }
     
     
     
     
     
     
      $data=DB::table('users')->where('id', $request->refer_id)->first() ;
  
     if(!$data )
     
     {
           $ar=array();
    
      $ar['Error']=true;
      $ar['message']="The refer_id is incorrect plz reverify";
      return response()->json($ar);
      
     }
     
     
     
     
         
     $request->validate([ 
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);
        
         
    
        
        $ar=array();
        $user = new User;
         $user->first_name =$request->name;
        $user->roll = 0;
         $user->username=$this->getUserName($request->email);
                 
            
          $user->register_otp = $otp;
        $user->email = $request->email;
          $user->refer_id = $request->refer_id;
        $user->password = bcrypt($request->password);

        $user->save();
        
        
        
       
        
        
        
      
         
        $credentials = request(['email', 'password']);
        
        
        if(!Auth::attempt($credentials))
         {  
          return response()->json([
                'message' => 'Login failure !!!' ,
                "Error" =>   true
            ], 401);
         }
            
            
             $user = $request->user();
        $ric=new RobotInvestmentCtrl(  $this->request );
        
        $ric->parents_referred_robot($user   );
            
       
        
        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        
        return response()->json([
            'authToken' => $tokenResult->accessToken,
             'refreshToken' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expiresIn' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
          
            'message' => 'Login Success!!!',
                        
                        
                "Error" =>   false
            
        ]);
        
        
     
        
        
        
        
        
 }


 public function register_otpwww(Request $request){
     
     
     // mark for the delition
     
     return "";
     
     
      $data=DB::table('users')->where('email', $request->email)->first() ;
  
     if($data )
     
     {
           $ar=array();
    
      $ar['Error']=true;
      $ar['message']="The email has already been taken";
      return response()->json($ar);
      
     }
     else{
         
     $request->validate([ 
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);
        
         $otp = 8565;//mt_rand(1000,9999);
    
        
        $ar=array();
        $user = new User;
         $user->first_name =$request->name;
        $user->roll = 0;
        
                 
        $otp =mt_rand(1000,9999);
        
          $user->api_key =mt_rand(1000,9999)."-".mt_rand(1000,9999)."-".mt_rand(1000,9999)."-".mt_rand(1000,9999);
        
         $user->register_otp = $otp;
        $user->email = $request->email;
          $user->refer_id = $request->refer_id;
        $user->password = bcrypt($request->password);

        $user->save();
        
        
      
    //       $data = array('content'=>$otp.' is the otp for the register in the wallet','subject'=> $otp.' is the otp for the register in the wallet'  );
   
    //   Mail::send('mail', $data, function($message ) {
          
     

    //      $message->to('dcs90876@cuoly.com', 'dcs90876@cuoly.com')->subject
    //         ('The otp for the register in the wallet');
     
    //      $message->from('globocoins.trade@gmail.com', 'gjhg');
         
    //   });
          
          $data = array( 'name'=>"OTP Verification",'content'=>$otp.' is the otp for the register in the wallet','subject'=> $otp.' is the otp for the register in the wallet'  );
           
           
      Mail::send('mail', $data, function($m) {
        
        
            $m->to($this->request->email, $this->request->email )->subject
            ('The otp for the register in the wallet');
          
          $m->getHeaders()->addTextHeader('isTransactional', true);
        
         
      });
      
      
       
      
         
      
      $ar=array();
       $ar['otp']=true;
      $ar['Error']=false;
      $ar['message']="Email Sent. Check your inbox";
      return response()->json($ar);
     } 
        
        
 }
 
      
    
    
  public function register(Request $request)
    {
      
             
         

      
         $lg=  User::where('register_otp', $this->request->otp)->where('email', $this->request->email)->first();
         
         
  if( $lg )
       {
          
            $lg->register_status=1;
      $lg->save();   
        
        
        
        $ar=array();
        
     

      
        
           $data = array('name'=>"Account created successfully",'subject'=> "Account created successfully",'content'=> "Account created successfully");

           
           
      Mail::send('mail', $data, function($m) {
          
          
         
            $m->to($this->request->email, $this->request->email )->subject
            ('Account created successfully');
            
            
          
           $m->getHeaders()->addTextHeader('isTransactional', true);
 
         
         
      });
      




        $tokenResult = $lg->createToken('Personal Access Token');
        $token = $tokenResult->token;
         $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user'=> $lg,
            'message' => 'Login Success!!!',
                "Error" =>   false
            
        ]);
        
        
       }
       
       
    }
    
    
    
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function getme(Request $request)
    {
           $user = Auth::user();
           
           $u=$user->only(['id',  'BTC','BTCV', 'email',  'first_name', 'last_name', 'address1', 'address2','state', 'country', 'mobile','roll',
        'district', 'street', 'apartment_number', 'zip_code','house_no', 'city',  'dob', 'gender','profile_pic','pix_widthdraw' ,'dbik_widthdraw','usdt_widthdraw']);
        
        $u['refer_link']= env('REFER_LINK', 'localhost').$u['id'];
        
        return response()->json($u);
        
    }
    
      public function user(Request $request)
    {
             
             
             return $this->getme($request);
             
             
                $user = Auth::user();
       
        return response()->json($user->only(['id', 'api_key', 'email',  'first_name', 'last_name', 'address1', 'address2','state', 'country', 'mobile','roll',
        'district', 'street', 'apartment_number', 'zip_code','house_no', 'city',  'dob', 'gender'  ,'bank_account' , 'allowmarketing','profile_pic',
    'communication',
    'currency',
    'timeZone',
    'companySite',
    'company_name' ,'language','usdt_widthdraw']));
        
    }
    
    public function get_client_ip() {
     
    
   $ar=array();
   
         $user = Auth::user();
        $user->country ="India";
         $user->save();
   $ar['country']= "India";
   
     $ar['status']="success";
      return response()->json($ar);
      
}

    
    
    public function user2(Request $request)
    {
        
               $user = Auth::user();
               
               
               
               $ar=array();
               $ar['status']="success";
               
               
               $ar['result']=$user->only([  'id', 'email','mobile',     'first_name', 'last_name', 'address1', 'address2', 'state', 'country', 'mobile', 'district', 'street', 'apartment_number', 'zip_code', 'house_no', 'city', 'images', 'dob', 'gender',  'national_id_card', 'drivers_licence', 'callbackurl',  'account_status', 'membership','notification_fund_status' ]);
               
               
               
               
        return response()->json($ar);
 

        
    }
    
    
    
    
    
    
    
    
    
       public function changepassword(Request $request)
    {
      
      
      $ar=array();
      
      
        
 
if(!Hash::check($request->get('current_password'),Auth::user()->password))
{
      $ar['Error']=true;
    
              $ar['message']="Your current password does not match with what you provided.";
    
}
else if(strcmp($request->get('current_password'),$request->get('new_password'))==0 ){
      $ar['Error']=true;
    
              $ar['message']="Your current password cannot be same with the new password.";
    
}

else if(strcmp($request->get('confirm_password'),$request->get('new_password'))!=0 ){
      $ar['Error']=true;
    
              $ar['message']="Your confirm password cannot be same with the new password.";
    
}

else{
 $user = Auth::user();
 
 
  
        
        
        
        $user->password = bcrypt($request->get('new_password'));
        $user->save();
        
          $ar['message']="Successfully updated the password.";
              
              
             $ar['Error']=false;
             
             
             
             
             
             
             
             
             
             
             
                  $data = array( 'content'=>    $ar['message'],'subject'=>  'Password change'  );
           
         
      Mail::send('mail', $data, function($message) {
       
           $user = Auth::user();
          
       
          
         $message->to(   $user->email,   "")->subject
            ('Password change' );
             
    $message->from('support@vikkin.ltd','');
      });
 
 
 
 
 
 
 
 
      
}

 

             
 return response()->json($ar);
 
 
    }    
    
        public function changeStatus(Request $request)

    {
        if(Auth::user()->roll <  9 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
        }
        
        $user = User::find($this->request['id']);

        $user->status =  $user->status==1?0:1;
    

        $user->save();

  

        return response()->json(['success'=>'Status change successfully.']);

    }
    
    
        public function accountStatus(Request $request)

    {
        
         if(Auth::user()->roll < 8 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
    $ar=array();
        $user = User::find($this->request['id']);

        $user->account_status =  $user->account_status==1?0:1;
    

        $user->save();

   $ar['message']="Account Status change successfully.";
              
              
     $ar['Error']=false;

        return response()->json($ar);

    }
    
    
    
        public function notificationfundStatus(Request $request)

    {
    $ar=array();
    
       $user = Auth::user();
        //$user = User::find($this->request['id']);

        $user->notification_fund_status =  $user->notification_fund_status==1?0:1;
    
   
        $user->save();

   $ar['message']="Notification Fund Status change successfully.";
              
              
     $ar['Error']=false;

        return response()->json($ar);

    }
    
    
        public function deleteaccount(Request $request)

    {
        
           if(Auth::user()->roll < 8 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
         $ar=array();
        $user = User::find($this->request['id']);

       $user->delete();


        $ar['message']="Account Deleted successfully.";
              
              
        $ar['Error']=false;

        return response()->json($ar);

    }
    
     public function deleteplan(Request $request)

    {
        
           if(Auth::user()->roll < 8 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
        
        
         $ar=array();
        $user = Membership::find($this->request['id']);

       $user->delete();


        $ar['message']=" Deleted successfully.";
              
              
        $ar['Error']=false;

        return response()->json($ar);

    }
      public function deleteg2f(Request $request)

    {
        
        
        
           if(Auth::user()->roll <  8 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
        
        
         $ar=array();
     
    
  $deletedRows = Exchange_g2f::where('email',   $this->request['email'])->delete();


        $ar['message']=" Deleted successfully.";
              
              
        $ar['Error']=false;

        return response()->json($ar);

    }
    
     
      
      
        
              
 public function postupdatemembership()
    {
        
        
           if(Auth::user()->roll<>10)
        {
   $this->request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Unauthorised Access'
        ]);
        }
        
        
        
        
        
        $m = Membership::find( $this->request['membership']);
      
     
        $ar=array();
  
       $user = Auth::user();
  
      if(  $user->membership   == $this->request['membership'])
      {
             $ar['message']="You already have this membership plan activated";
              
              
             $ar['Error']=true;
           
     
          
 return response()->json($ar);
          exit();
      }
       
  $sc=new    Paymentcls();
  
  
  
       
       
  $amount=$m->price;
   $error=$sc->debit_user( $user,$amount , "USD","Membership purchase");
       
       
       if(!$error)
       {
        $user->membership   = $this->request['membership'];
                   
         $user->plan=        $m->plan;
                   
                   
            $user->save();
 


       $ar['message']="Successfully updated the membership.";
              
              
             $ar['Error']=false;
       }
       else
       {
            $ar['message']="Please deposit payment to complete this order";
              
              
             $ar['Error']=true;
           
       }
          
 return response()->json($ar);
 
  
    }
    
        
 public function updateAPI_KEY()
    {
        
        $ar=array();
  
        $ar['Error']=true;
 
         $user = Auth::user();
      
                  
   
          $user->api_key =mt_rand(10000,99999)."-".mt_rand(10000,99999)."-".mt_rand(10000,99999)."-".mt_rand(10000,99999);
        
         
            $user->save();
 

       $ar['message']="Key updated Successfully.";
              
              
             $ar['Error']=false;
             
 return response()->json($ar);
    }
    
    
    
    
    
    
    
 public function postprofile()
    {
        
        $ar=array();
  
        $ar['Error']=true;
 
         $user = Auth::user();
        $user->email_verified_at = $this->request['email_verified_at'];
       $user->first_name =  $this->request['first_name'];
           $user->last_name =  $this->request['last_name'];
              $user->api_key = $this->request['api_key'];
            $user->address1 =    $this->request['address1'];
              $user->address2 =  $this->request['address2'];
                $user->state =  $this->request['state'];
                  $user->country =  $this->request['country'];
                  $user->city  = $this->request['city'];
                   $user->callbackurl  = $this->request['callbackurl'];
                     $user->street = $this->request['street'];
                      $user->district = $this->request['district'];
                      $user->apartment_number = $this->request['apartment_number'];
                      $user->zip_code = $this->request['zip_code'];
                       $user->house_no  = $this->request['house_no'];
                       $user->gender  = $this->request['gender'];
                       $user->dob  = $this->request['dob'];
                     $user->mobile  = $this->request['mobile'];
                       $user->notification_url = $this->request['notification_url'];
                       $user->profile_pic = $this->request['profile_pic'];
                       
                           $user->allowmarketing  = $this->request['allowmarketing'];
                       $user->communication  = $this->request['communication'];
                       $user->currency  = $this->request['currency'];
                     $user->timeZone  = $this->request['timeZone'];
                       $user->companySite = $this->request['companySite'];
                       $user->company_name = $this->request['company_name'];
                         $user->language = $this->request['language'];
                         $user->pix_widthdraw = $this->request['pix_widthdraw'];
                         $user->dbik_widthdraw = $this->request['dbik_widthdraw'];      $user->usdt_widthdraw = $this->request['usdt_widthdraw'];
          
         
            $user->save();
   
 

       $ar['message']="Successfully updated the profile.";
              
              
             $ar['Error']=false;
             
 return response()->json($ar);
    }
    
    
    public function postkycnew(Request $request)
{
    
    $ar=array();
        $ar['Error']=true;
           $user = Auth::user();
       

 $a=$request->document_type;
 $user->$a=$request->document;
 
 $output_file=$user->id."__".rand(2222,3333).".png";
 
 
 file_put_contents($output_file, file_get_contents($request->document));
 
 
 $url="https://fapiv2.vikkin.ltd/".$output_file;;
 
 
  $user->$a=$url;
  
  
 
 
 
    $user->save();
    
       $ar['message']="Successfully updated the profile.";
       $ar['url']=$url;
      
         $ar['Error']=false;
             
 return response()->json($ar);
    
 

}
    
     public function loginbygoogle(Request $request)

    {
        
 

$url = "https://flag.vikkin.ltd/checktoken.php";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
   "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
 

$data = array(  "token" => $request->credential );

curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

$resp = curl_exec($curl);
curl_close($curl);

$google_response=json_decode($resp);


  $user = User::where( "email" , $google_response->email)->first() ;
 
  //$user=DB::table('users')->where('email',$resp)->first()  ;
    if(!$user)
    {
         $user = new User;
         $user->first_name =$google_response->name;
        $user->roll = 0;
        
                 
        $otp =mt_rand(1000,9999);
        
          $user->api_key =mt_rand(1000,9999)."-".mt_rand(1000,9999)."-".mt_rand(1000,9999)."-".mt_rand(1000,9999);
        
         $user->register_otp = $otp;
        $user->email = $google_response->email;
          $user->refer_id = 158;       
          
          $user->profile_pic =$google_response->picture;
          
          
        $user->password = bcrypt($user->api_key);

        $user->save();
        
        
        
    }
    
    

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
         $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        
        
           return response()->json([
            'authToken' => $tokenResult->accessToken,
             'refreshToken' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expiresIn' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
          
            'message' => 'Login Success!!!',
                        'api_key' =>   $user->api_key,
                "Error" =>   false
            
        ]);
        
        
        
     
        
        

    }
    
    
    
    // admin task
    
    
        public function changelogin(Request $request)

    {
        if(Auth::user()->roll  <  9 )
        {
   $request->user()->token()->revoke();
   
   
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
        }
        
        $user = User::find($this->request['id']);



        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
         $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        
        
           return response()->json([
            'authToken' => $tokenResult->accessToken,
             'refreshToken' => $tokenResult->accessToken,
            'token_type' => 'Bearer',     'user' =>   $user,
            'expiresIn' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
          
            'message' => 'Login Success!!!',
                        'api_key' =>   $user->api_key,   'roll' =>   $user->roll,
                "Error" =>   false
            
        ]);
        
        
        
     
        
        

    }
    
    
    
    
    
     
    
  public function refermember(Request $request)
    {
        // allow only from one IP address
        if($this->getUserIP()=="")
        {
         //   exit();
            
        }
        $e = User::where('email',$request->email)->count();
   
        
           $ar=array();
        if($e==0)
        {
        
         
        $user = new User;
     
       
        $user->email = $request->email;
        
        
        $user->password = bcrypt(rand(5000,7000));

 $user->plan = "Premium";
 
        $user->save();
        $ar['message']="Successfully created user!.";
      
         $ar['Error']=false;
      
         
        }
        else
        {
            
               $user = User::where('email',$request->email)->first();
                $user->plan = "Premium";
 
        $user->save();
        
        
         $ar['message']="Successfully updated user!.";
      
         $ar['Error']=false;
       
         
         
         
        }
        
        
          return response()->json($ar);
       
    }
    
    
    
    function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


       function getOTPTemplate($otp)
       {
     $str='      
           <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Vikkin INTELIGÊNCIA ARTIFICIAL</a>
    </div>
    <p style="font-size:1.1em">Hi,</p>
    <p>Thank you for choosing Vikkin INTELIGÊNCIA ARTIFICIAL. Use the following OTP to complete your procedures. OTP is valid for 25 minutes</p>
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">'.$otp.'</h2>
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