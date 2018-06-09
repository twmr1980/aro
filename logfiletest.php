<?php

$myfile = "invupd.log";
$read = file($myfile);
$count = count($read);

$fh = fopen($myfile,"a");
if(is_writable("invupd.log")){ echo "good to go";}else{echo "no go first";}
$x = "8675309";
$y = 100;
$d = date('Y-m-d h:i:sa');

$txt = ($count+1) . ". ".$x . ", " . $y . ", " . $d ."\n" ;

if(fwrite($fh, $txt) == FALSE){echo "NO GO";}else{echo "good to go two";}

fclose($fh);
?>
