<?php

//create mysql connection
$link = mysqli_connect("localhost", "root", "P0Peyethesailor12!!", "wallacetest");

//check connection
if($link === false){
	die("ERROR: could not connect. " . mysqli_connect_error());

}

/*if(isset($_POST['invAdj'])) {
	$invAdj = json_decode($_POST['invAdj']);
	$itemId = $invAdj->itemId;
	$amountAdj = $invAdj->amountAdj;

}*/
$itemId = "038000311314";
$findIdbyBar = mysqli_query($link, "SELECT id FROM stored_items WHERE code = 'test'");
echo $findIdbyBar;
echo "test";
//mysqli_query($link, "UPDATE stock_levels SET stocklevel  = stocklevel + " . $amountAdj . " WHERE storeditemid = " .$findIdbyBar);

?>