<?php

//create mysql connection
$link = mysqli_connect("localhost", "root", "P0Peyethesailor12!!", "wallacetest");

//check connection
if($link === false){
	die("ERROR: could not connect. " . mysqli_connect_error());

}

//post from ajax call
$reason = $_POST['adjAmt']; //mysqli_escape_string($link, $_POST['reason']);

mysqli_query($link, "UPDATE stock_levels SET stocklevel  = stocklevel + " . $reason . " WHERE id = 1");
?>