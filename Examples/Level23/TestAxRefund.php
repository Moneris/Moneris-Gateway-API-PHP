<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='moneris';
$api_token='hurgle';
//$status = 'false';

/************************* Transactional Variables ****************************/

$type='axrefund';
$order_id='ord-210916-12:06:38';
$amount='62.37';
$txn_number = '18924-1_11';
$crypt = '7';

//Create AxLevel23 Object
$mpgAxLevel23 = new mpgAxLevel23();

//Create Table 1 with details

$n101 = "R6";	//Entity ID Code
$n102 = "Retailing Inc. International";	//Name
$n301 = "919 Oriole Rd.";		//Address Line 1
$n401 = "Toronto";		//City
$n402 = "On";			//State or Province
$n403 = "H1T6W3";			//Postal Code

$ref01 = array("4C", "CR");	//Reference ID Qualifier
$ref02 = array("M5T3A5", "16802309004"); //Reference ID


$big04 = "PO7758545";	//Purchase Order Number
$big05 = "RN0049858";	//Release Number
$big10 = "INV99870E";      //Invoice Number

$axRef1 = new axRef();
$axRef1->setRef($ref01[0], $ref02[0]);
$axRef1->setRef($ref01[1], $ref02[1]);

$axN1Loop = new axN1Loop();
$axN1Loop->setN1Loop($n101, $n102, $n301, $n401, $n402, $n403, $axRef1);

$mpgAxLevel23->setTable1($big04, $big05, $big10, $axN1Loop);

//Create Table 2 with details
//the sum of the extended amount field (pam05) must equal the level 1 amount field
	
$it102 = array("1", "1", "1", "1", "1");	//Line item quantity invoiced
$it103 = array("EA", "EA", "EA", "EA", "EA");  //Line item unit or basis of measurement code
$it104 = array("10.00", "25.00", "8.62", "10.00", "-10.00");   //Line item unit price
$it105 = array("", "", "", "", "");	//Line item basis of unit price code
	
$it10618 = array("MG", "MG", "MG", "MG", "MG");   //Product/Service ID qualifier
$it10719 = array("DJFR4", "JFJ49", "FEF33", "FEE43", "DISCOUNT");   //Product/Service ID (corresponds to it10618)
	
$txi01_GST = array("GS", "GS", "GS", "GS", "GS");	//Tax type code
$txi02_GST = array("0.70", "1.75", "1.00", "0.80","0.00");	//Monetary amount
$txi03_GST = array("", "", "", "","");		//Percent
$txi06_GST = array("", "", "", "","");		//Tax exempt code
	
$txi01_PST = array("PG", "PG", "PG","PG","PG");	//Tax type code
$txi02_PST = array("0.80", "2.00", "1.00", "0.80","0.00");	//Monetary amount
$txi03_PST = array("", "", "", "","");		//Percent
$txi06_PST = array("", "", "", "","");		//Tax exempt code

$pam05 = array("11.50", "28.75", "10.62", "11.50", "-10.00");	//Extended line-item amount
$pid05 = array("Stapler", "Lamp", "Bottled Water", "Fountain Pen", "DISCOUNT");	//Line item description

$it106s = array();
$it106s[0] = new axIt106s($it10618[0], $it10719[0]);
$it106s[1] = new axIt106s($it10618[1], $it10719[1]);
$it106s[2] = new axIt106s($it10618[2], $it10719[2]);
$it106s[3] = new axIt106s($it10618[3], $it10719[3]);
$it106s[4] = new axIt106s($it10618[4], $it10719[4]);

$txi = array(new axTxi(), new axTxi(), new axTxi(), new axTxi(), new axTxi());

$txi[0]->setTxi($txi01_GST[0], $txi02_GST[0], $txi03_GST[0], $txi06_GST[0]);
$txi[0]->setTxi($txi01_PST[0], $txi02_PST[0], $txi03_PST[0], $txi06_PST[0]);

$txi[1]->setTxi($txi01_GST[1], $txi02_GST[1], $txi03_GST[1], $txi06_GST[1]);
$txi[1]->setTxi($txi01_PST[1], $txi02_PST[1], $txi03_PST[1], $txi06_PST[1]);

$txi[2]->setTxi($txi01_GST[2], $txi02_GST[2], $txi03_GST[2], $txi06_GST[2]);
$txi[2]->setTxi($txi01_PST[2], $txi02_PST[2], $txi03_PST[2], $txi06_PST[2]);

$txi[3]->setTxi($txi01_GST[3], $txi02_GST[3], $txi03_GST[3], $txi06_GST[3]);
$txi[3]->setTxi($txi01_PST[3], $txi02_PST[3], $txi03_PST[3], $txi06_PST[3]);

$txi[4]->setTxi($txi01_GST[4], $txi02_GST[4], $txi03_GST[4], $txi06_GST[4]);
$txi[4]->setTxi($txi01_PST[4], $txi02_PST[4], $txi03_PST[4], $txi06_PST[4]);

$axItLoop = new axIt1Loop();
$axItLoop->setIt1Loop($it102[0], $it103[0], $it104[0], $it105[0], $it106s[0], $txi[0], $pam05[0], $pid05[0]);
$axItLoop->setIt1Loop($it102[1], $it103[1], $it104[1], $it105[1], $it106s[1], $txi[1], $pam05[1], $pid05[1]);
$axItLoop->setIt1Loop($it102[2], $it103[2], $it104[2], $it105[2], $it106s[2], $txi[2], $pam05[2], $pid05[2]);
$axItLoop->setIt1Loop($it102[3], $it103[3], $it104[3], $it105[3], $it106s[3], $txi[3], $pam05[3], $pid05[3]);
//$axItLoop->setIt1Loop($it102[4], $it103[4], $it104[4], $it105[4], $it106s[4], $txi[4], $pam05[4], $pid05[4]);

$mpgAxLevel23->setTable2($axItLoop);

//Create Table 3 with details

$taxTbl3 = new axTxi();
$taxTbl3->setTxi("GS", "4.25","","");	//sum of GST taxes
$taxTbl3->setTxi("PG", "4.60","","");	//sum of PST taxes
$taxTbl3->setTxi("TX", "8.85","","");	//sum of all taxes

$mpgAxLevel23->setTable3($taxTbl3);

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
     		    'order_id'=>$order_id,
     		    'amount'=>$amount,
				'txn_number'=> $txn_number,
				'crypt_type'=>$crypt
   		       );

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);
$mpgTxn->setLevel23Data($mpgAxLevel23);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

//Status check example
//$mpgHttpPost = new mpgHttpsPostStatus($store_id,$api_token,$status,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
//print("\nStatusCode = " . $mpgResponse->getStatusCode());
//print("\nStatusMessage = " . $mpgResponse->getStatusMessage());

?>

