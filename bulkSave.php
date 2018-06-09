<?php

//create mysql connection
$link = new mysqli("localhost", "root", "P0Peyethesailor12!!", "wallacepos");
//check connection
if($link->connect_error){
	die("ERROR: could not connect. " . $link->connect_error);
}
if(isset($_POST['invAdj'])) {
	$invAdj = json_decode($_POST['invAdj']);
	$scannedId = $invAdj->itemId; //barcode sent in from html
	//$amountAdj = $invAdj->amountAdj;
}
$invLogFile = "bulk.log"; //set log file
$fh = fopen($invLogFile, "a");  //open log file
if(is_writable("bulk.log")){
	//get the number of the last line of log file
	$readInvLog = file($invLogFile);
	$countInvLog = count($readInvLog);
	//retrieve total number of unique items from bulk box barcode
	$sqlTotalItems = "SELECT totalItems FROM bulk_stock WHERE bulkBoxBc = '".$scannedId."'";
	$totalItems = mysqli_fetch_row($link->query($sqlTotalItems));

	//for($i = 0; $i < $totalItems[0]; i++){  //functionality for more than one item in a package
	//	switch ($i) {
	//		case 0:
				$sqlOneBC = "SELECT itemOne FROM bulk_stock WHERE bulkBoxBc = '".$scannedId."'";
				$sqlOneCount = "SELECT itemOneCount FROM bulk_stock WHERE bulkBoxBc = '".$scannedId."'";
				$itemOneBC = mysqli_fetch_row($link->query($sqlOneBC));
				$itemOneCount = mysqli_fetch_row($link->query($sqlOneCount));
				//convert barcode to item id recognizable by levels table
				$sqlIDone = "SELECT id FROM stored_items WHERE code = '".$itemOneBC[0]."'";
				$res = mysqli_fetch_row($link->query($sqlIDone));
				$updLevel = "UPDATE stock_levels SET stocklevel = stocklevel + " . $itemOneCount[0] . " WHERE storeditemid = '" . $res[0] . "'";
				//$updateLevel = ($link->query($updLevel));
		//		break;
	//	}
	//variable to set if select found a real item
	$countRes = count($res);
    //select item name using barcode scanned and converted to item id
	//$itemName = mysqli_fetch_row($link->query($sqlItemName));
	//$displayName = $itemName[0];
	//updates level based on id converted from barcode select, then log it
	//update stock level and log it in the log file.  if no item was found under scanned code, log the error
	if ($link->query($updLevel) === TRUE) {
		if($countRes == 1) {
			$tStamp = date('Y-m-d h:i:sa');
			$logTxt = ($countInvLog+1).". Bulk BC:".$scannedId.", Item 1:".$itemOneBC[0] .", #". $itemOneCount[0].", dt:". $tStamp. ",  stockID:".$res[0]."\n";
			fwrite($fh, $logTxt);
		} elseif ($countRes == 0){
			$tStamp = date('Y-m-d h:i:sa');
			$logTxt = ($countInvLog+1).". Error - Does not exist: ".$scannedId.", ". $tStamp. ",  ".$res[0]."\n";
			fwrite($fh, $logTxt);
		} //add code for duplicate numbers
	} else {
		echo "error";
	}
}
?>