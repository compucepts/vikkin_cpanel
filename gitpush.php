 <?php
// outputs the username that owns the running php/httpd process
// (on a system with the "whoami" executable in the path)
$output=null;
$retval=null;



$file = "delete/".rand().'__people.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "John Smith\n";
// Write the contents back to the file
file_put_contents($file, $current);



 

exec('git add * ', $output,$retval);

print_r($output); 





exec('git commit -m "test "     ', $output,$retval);

print_r($output);


//exec('git push -u origin main ', $output, $retval);


exec('git push https://compucepts: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@github.com/compucepts/vikkin_cpanel.git --all   ', $output, $retval);


print_r($output);
 