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

use App\Http\Controllers\Paymentcls;

use App\LoginGuards;

use App\Exchange_g2f;

use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
     public function __construct(Request $request) {
        
  //  $this->middleware('auth');
        $this->request = $request;
    }  
    
     public function mailtest() {
     
     
     
      //////////////////////////////////////////////

      $otp=4343;
      $email="sakshk2019@gmail.com";
      
      
         $content="Please click the link to start login. 
       https://wapiv3.btvcash.net/api/auth/loginguard?otp=".$otp."&email=".$email ;
       
       
       
       $content  = "One Time password for the login in the wallet is  ".$otp;
       
       echo $email;
       
     
       
            $data = array('email'=>$email, 'content'=> $content,'subject'=> "One Time password for the login in the wallet "  );
              
           
   //   Mail::send('mail', $data, function($m) {
        
          Mail::send(['text'=>'mail'], $data, function($m ) {
              
              
              $email="sakshk2019@gmail.com";
         
         
            $m->to($email, "sss" )->subject
            ('777One Time password for the login in the wallet ');
            
          
           $m->getHeaders()->addTextHeader('isTransactional', true);
            
         $m->from('noreply@casinokoin.com','');     
         
      });
      
      
     
      //////////////////////////////////////////////
         $data1 = array('name'=>"Virat Gandhi",'subject'=> "susheel",'content'=> "susheel");
   
      Mail::send(['text'=>'mail'], $data, function($message) {
          
          
           $message->getHeaders()->addTextHeader('isTransactional', true);
           
           
       //  $message->to('sakshk2019@gmail.com', '2222Tutorials Point')->subject
           // ('uuuuuuuuuuuuuuuuuuing Mail');
            
            
               $message->to('sakshk2019@gmail.com', "sss" )->subject
            ('33One Time password for the login in the wallet ');
            
            
            
                 $message->from('noreply@casinokoin.com','');
         
      });
      
      
      echo "43435435 Basic Email Sent. Check your inbox.";
      /*
      
  
            
            $data = array('name'=>"Virat Gandhi",'subject'=> "susheel",'content'=> "susheel");
   
           
      Mail::send('mail', $data, function($m) {
        
        $otp="465464";  $email="susheel2339@gmail.com";
        
        
    
            
            
            
         $m->to('susheel2339@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
            
            
            
         $m->from('nsb85050@cuoly.com','Virat Gandhi33333');
         
         
      });
      
      
      
         $data = array('name'=> "susheel",'subject'=> "susheel",'content'=> "susheel");
         
      Mail::send('mail', $data, function($message) {
          $otp = mt_rand(1000,9999);
         $message->to("nsb85050@cuoly.com", $otp)->subject
            ($otp.' is the otp for the login in the exchange software');
         $message->from('support@milexcoin.io','milexcoin.io');
      });
      
      
      
      */
      $ar=array();
      $ar['Error']=false;
      $ar['message']="OTP Sent. Check your inbox.";
       return response()->json($ar);
       
       
     
      
      
      
      
     
     
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
      
      
   
         $otp = mt_rand(1000,9999);
         
            
          $user = User::where('email',$this->request->email) ->first();
          
        //  echo "dasf";
   
       
       
            if(isset($user))
            
            { 
       
              
    $user->code =    $otp ;
  $user->save();
             
              
            }
          
           $data = array( 'content'=>$otp.' is the otp for the login in the wallet','subject'=> $otp.' is the otp for the login in the wallet'  );
           
           
      Mail::send('mail', $data, function($m) {
          
          
         
            $m->to($this->request->email, $this->request->email )->subject
            ('The otp for the login in the wallet');
            
            
          
           $m->getHeaders()->addTextHeader('isTransactional', true);
        
        
         
      });
      
      
       
      
      
      
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
 
 
 
         
           $data = array( 'content'=>'Your password was reset succesfully.','subject'=>  ' Your password was reset succesfully.'  );
           
         
      Mail::send('mail', $data, function($message) {
       
       
          
       
          
         $message->to($this->request->email,  $this->request->code)->subject
            ('Your password was reset succesfully.');
            
          
           $message->getHeaders()->addTextHeader('isTransactional', true);
          
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
                'message' => 'Login failure!!!',
                "Error" =>   true
            ], 401);
         }
            
            
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
            
        $user = $request->user();
        
        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user'=> $user,
            'message' => 'Login Success!!!',
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
    
    
    
 $ss->username=$request->username;
 $ss->email=$request->email;
  
 
       $ss->password = bcrypt($request->password);
       
       
 
 try {
    $ss->save();
    
 return   $this->AuthToken(  $request);
  
  
        
    
}
 catch(\Illuminate\Database\QueryException $ex){
 
      

   return response()->json([
                'message' => "Duplicate Entry. Email Already existing in our system",
         'ErrorCode' => 101,
                "Error" =>   true
            ], 401);
            
            
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
        
          $data = array( 'content'=>'Successfully login in the wallet...','subject'=>  'Successfully login in the wallet'  );
           
         
      Mail::send('mail', $data, function($message) {
       
           $user = Auth::user();
          
       
          
         $message->to($user->email,  $user->email)->subject
            ('Successfully login in the wallet.');
            
          
           $message->getHeaders()->addTextHeader('isTransactional', true);
       
      });
     
     
     // 
      $id = DB::table('login_history')->insertGetId(
    ['username' => $user->email ,
          'user_id' => $user->id,
          'ip'=>$ip]
);
        
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
               'gauth' => false,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user'=> $user
            
        ]);
        
        
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

        
    


 public function register_otp(Request $request){
     
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
         $user->register_otp = $otp;
        $user->email = $request->email;
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
        return response()->json($user->only(['id',  'BTC','BTCV', 'email',  'first_name', 'last_name', 'address1', 'address2','state', 'country', 'mobile',
        'district', 'street', 'apartment_number', 'zip_code','house_no', 'city',  'dob', 'gender','profile_pic' ]));
        
    }
    
      public function user(Request $request)
    {
              
                $user = Auth::user();
       
        return response()->json($user->only(['id',  'email',  'first_name', 'last_name', 'address1', 'address2','state', 'country', 'mobile',
        'district', 'street', 'apartment_number', 'zip_code','house_no', 'city',  'dob', 'gender','BRL'   ,'bank_account' ,'BTC' ,'USDTt' ]));
        
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
    
    
 public function postprofile()
    {
        
        $ar=array();
  
        $ar['Error']=true;
 
         $user = Auth::user();
        $user->email_verified_at = $this->request['email_verified_at'];
       $user->first_name =  $this->request['first_name'];
           $user->last_name =  $this->request['last_name'];
             
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
                       
                          $user->bank_account  = $this->request['bank_account'];
                          
                          
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
 
    $user->save();
    
       $ar['message']="Successfully updated the profile.";
      
         $ar['Error']=false;
             
 return response()->json($ar);
    
 

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
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user'=> $user,
            'message' => 'Login Success!!!',
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


       
    
}