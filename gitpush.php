 <?php
// outputs the username that owns the running php/httpd process
// (on a system with the "whoami" executable in the path)
$output=null;
$retval=null;


 


 

exec('git add * ', $output,$retval);

print_r($output); 






exec('git commit -m "test "     ', $output,$retval);

print_r($output);

//
//exec('git push -u origin main ', $output, $retval);

$k="ghp_KiTYAwdWK8aQ0";

$m="OyfTfwxzzVJhAxhiO4E4enI";





exec('git push https://compucepts:'.$k.$m.'@github.com/compucepts/vikkin_cpanel.git --all   ', $output, $retval);


print_r($output);
 