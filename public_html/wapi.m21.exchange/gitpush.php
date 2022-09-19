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




exec('cd public_html/wapi.m21.exchange ', $output,$retval);

exec('git add *  -C  public_html/wapi.m21.exchange  ', $output,$retval);

print_r($output); 





exec('git commit -m "test "   -C  public_html/wapi.m21.exchange  ', $output,$retval);

print_r($output);


//exec('git push -u origin main ', $output, $retval);


exec('git push https://susheel2335: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@github.com/susheel2335/vikkin_laravel.git --all  -C  public_html/wapi.m21.exchange', $output, $retval);


print_r($output);

 
?> 