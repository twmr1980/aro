<?php

//create mysql connection
$link = new mysqli("localhost", "root", "P0Peyethesailor12!!", "wallacetest");

//check connection
if($link->connect_error){
	die("ERROR: could not connect. " . $link->connect_error);

}

if(isset($_POST['invAdj'])) {
	$invAdj = json_decode($_POST['invAdj']);
	$itemId = $invAdj->itemId;
	$amountAdj = $invAdj->amountAdj;

}

$sqlID = "SELECT id FROM stored_items WHERE code = '".$itemId."'";

$resultID = $link->query($sqlID);

$res = mysqli_fetch_row($resultID);
$barToId = $res[0];
$updLevel = "UPDATE stock_levels SET stocklevel = stocklevel + " . $amountAdj . " WHERE storeditemid = '" . $barToId . "'";
if ($link->query($updLevel) === TRUE) {
	echo "Update successfull";
} else {
	echo "error";
}

?>