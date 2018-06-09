<?php

//create mysql connection
$link = new mysqli("localhost", "root", "P0Peyethesailor12!!", "wallacepos");

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
	$res = mysqli_fetch_row($link->query($sqlID));
	//variable to set if select found a real item
	$countRes = count($res);
    //select item name using barcode scanned and converted to item id
	$sqlItemName = "SELECT name FROM stored_items WHERE id = '".$res[0]."'";
	$itemName = mysqli_fetch_row($link->query($sqlItemName));
	$displayName = $itemName[0];
	//updates level based on id converted from barcode select, then log it
	$updLevel = "UPDATE stock_levels SET stocklevel = stocklevel + " . $amountAdj . " WHERE storeditemid = '" . $res[0] . "'";
	//update stock level and log it in the log file.  if no item was found under scanned code, log the error
	if ($link->query($updLevel) === TRUE) {
		if($countRes == 1) {
			$tStamp = date('Y-m-d h:i:sa');
			$logTxt = ($countInvLog+1).". ". $itemName[0] .", ".$itemId.", ". $amountAdj.", ". $tStamp. ",  ".$res[0].$countRes."\n";
			fwrite($fh, $logTxt);
		} elseif ($countRes == 0){
			$tStamp = date('Y-m-d h:i:sa');
			$logTxt = ($countInvLog+1).". Error - Does not exist: ".$itemId.", ". $amountAdj.", ". $tStamp. ",  ".$res[0]."\n";
			fwrite($fh, $logTxt);
		} //add code for duplicate numbers
	} else {
		echo "error";
	}
}
?>