<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 header('Content-Type: application/json');
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
try{
    $pdo = new PDO("mysql:host=pix.cduuflmnmg2e.sa-east-1.rds.amazonaws.com;dbname=PIXADMIN", "pixdeposit", "pix1234poiiupix0");
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
 
// Attempt select query execution
try{
    $ar=array();
    $ar['Error']=true;
     
     $ar['status']="Pending";
     
    $ar['message']="No Payment received";
    
    
    $sql = "SELECT * FROM pixdeposits where description='".$_REQUEST['InvoiceID']."'";   
   
    $result = $pdo->query($sql);
    
 
    
    
    if($result->rowCount() > 0){
         
       
            $rs=  $result->fetchObject();
    
    $ar['status']="Completed";
    
     
    $ar['data']=$rs;
    
     
    $ar['amount']=$rs->amount ;
    
    $ar['Error']=false;
    
    
      $ar['message']="Payment received";
        
        unset($result);
    }  
    

    
    echo  json_encode( $ar);
    
    
} catch(PDOException $e){
    
}
 
// Close connection
unset($pdo);
 