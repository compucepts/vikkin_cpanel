 <html>
<body>

Welcome <?php echo $_POST["amount"]; ?><br>


</body>
</html> 

<?php


ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://auth.globo4x.com/laravel/public/api/auth/login",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => array('email' => 'susheel30102@gmail.com','password' => 'susheel3010@gmail.com'),
));

$response = curl_exec($curl);

curl_close($curl);




//echo $response;
//ar=$response();
$arr=json_decode($response);
//print_r( $arr);
//echo $arr->access_token;
 
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://auth.globo4x.com/laravel/public/api/auth/system/receive_payment",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>json_encode( array('coin' => 'BTC','id' => 3)),
));


$authorization = "Authorization: Bearer ".$arr->access_token;  

 curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));  
      
   
$response = curl_exec($curl);
   var_dump($curl);
curl_close($curl);
echo $response;
