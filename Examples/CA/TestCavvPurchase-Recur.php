<?php

## Example php -q TestPurchase-VBV.php "moneris" store

require "../../mpgClasses.php";

/******************************* Request Variables ********************************/

$store_id='store5';
$api_token='yesguy';

/****************************** Transactional Variables ***************************/

$type='cavv_purchase';
$order_id='ord-'.date("dmy-G:i:s");
$cust_id='CUST887763';
$amount='10.00';
$pan="4242424242424242";
$expiry_date="1511";
$cavv='AAABBJg0VhI0VniQEjRWAAAAAAA=';
$crypt_type = '7';
$wallet_indicator = "APP";
$dynamic_descriptor='123456';

/********************************* Recur Variables ****************************/
$recurUnit = 'month'; //eom - end of month
$startDate = '2018/02/06';
$numRecurs = '4';
$recurInterval = '10';
$recurAmount = '31.00';
$startNow = 'true';

/*************************** Transaction Associative Array ************************/

$txnArray=array(
			'type'=>$type,
	        'order_id'=>$order_id,
			'cust_id'=>$cust_id,
	        'amount'=>$amount,
	        'pan'=>$pan,
	        'expdate'=>$expiry_date,
			'cavv'=>$cavv,
			'crypt_type'=>$crypt_type, //mandatory for AMEX only
			//'wallet_indicator'=>$wallet_indicator, //set only for wallet transactions. e.g. APPLE PAY
			//'network'=> "Interac", //set only for Interac e-commerce
			//'data_type'=> "3DSecure", //set only for Interac e-commerce
			'dynamic_descriptor'=>$dynamic_descriptor
	           );

/*********************** Recur Associative Array **********************/

$recurArray = array('recur_unit'=>$recurUnit, // (day | week | month)
		'start_date'=>$startDate, //yyyy/mm/dd
		'num_recurs'=>$numRecurs,
		'start_now'=>$startNow,
		'period' => $recurInterval,
		'recur_amount'=> $recurAmount
);

$mpgRecur = new mpgRecur($recurArray);

/****************************** Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/****************************** Recur Object *********************************/

$mpgTxn->setRecur($mpgRecur);

/******************* Credential on File **********************************/

$cof = new CofInfo();
$cof->setPaymentIndicator("R");
$cof->setPaymentInformation("2");
$cof->setIssuerId("168451306048014");

$mpgTxn->setCofInfo($cof);

/******************************* Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/****************************** HTTPS Post Object *******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************************* Response *********************************/

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
print("\nCavvResultCode = " . $mpgResponse->getCavvResultCode());
print("\nIssuerId = " . $mpgResponse->getIssuerId());


?>

