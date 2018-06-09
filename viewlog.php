<?php

$read = file("invupd.log");
$count =  count($read);
$i = 1;
foreach ($read as $line){
	echo $line;
	if($i < $count){
		echo '<br>';
	}
	$i++;
}
?>