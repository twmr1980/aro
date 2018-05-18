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

$invLogFile = "invupd.log"; //set log file
$fh = fopen($invLogFile, "a");  //open log file
if(is_writeable("invupd.log")){ 
	//get the number of the last line of log file
	$readInvLog = file($invLogFile);
	$countInvLog = count($readInvLog);
	//convert barcode to item id reconizable by levels table
	$sqlID = "SELECT id FROM stored_items WHERE code = '".$itemId."'"; 
	$resultID = $link->query($sqlID);
	$res = mysqli_fetch_row($resultID);
	$barToId = $res[0];
	$countRes = count($res);
	//updates level based on id converted from barcode select, then log it
	$updLevel = "UPDATE stock_levels SET stocklevel = stocklevel + " . $amountAdj . " WHERE storeditemid = '" . $barToId . "'";
//*******************need to add function to select item name to add to log file to make log more easily read.
	
	if ($link->query($updLevel) === TRUE) {
		if($countRes == 1) {
			$tStamp = date('Y-m-d h:i:sa');
			$logTxt = ($countInvLog+1).". ".$itemId.", ". $amountAdj.", ". $tStamp. ",  ".$countRes. $res[0]."\n";
			fwrite($fh, $logTxt);
		} elseif ($countRes == 0){
			$tStamp = date('Y-m-d h:i:sa');
			$logTxt = ($countInvLog+1).". Error - Does not exist: ".$itemId.", ". $amountAdj.", ". $tStamp. ",  ".$countRes. $res[0]."\n";
			fwrite($fh, $logTxt);
		}
	} else {
		echo "error";
	}
}
?>