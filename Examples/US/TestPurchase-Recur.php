<?php

require "../../mpgClasses.php";

/************************ Request Variables ***************************/

$store_id='monusqa002';
$api_token='qatoken';

/********************* Transactional Variables ************************/

$type='purchase';  
$order_id='ord-'.date("dmy-G:i:s");
$cust_id="customer id";
$amount='1.00';
$pan='4242424242424242';
$expiry_date="1511";
$crypt='7';
$commcard_invoice='Invoice 5757FRJ8';
$commcard_tax_amount='0.15';


/************************** Recur Variables *****************************/

$recurUnit = 'day';
$startDate = '2015/11/30';
$numRecurs = '4';
$recurInterval = '10';
$recurAmount = '31.00';
$startNow = 'true';

/****************************** Recur Array **************************/

$recurArray = array(recur_unit=>$recurUnit,  // (day | week | month)
	start_date=>$startDate, //yyyy/mm/dd
	num_recurs=>$numRecurs,
	start_now=>$startNow,
	period => $recurInterval,
	recur_amount=> $recurAmount
	);

/****************************** Recur Object **************************/

$mpgRecur = new mpgRecur($recurArray);

/***************** Transactional Associative Array ********************/

$txnArray=array(
		type=>$type,
		order_id=>$order_id,
		cust_id=>$cust_id,
		amount=>$amount,
		pan=>$pan,
		expdate=>$expiry_date,
		crypt_type=>$crypt,
		commcard_invoice=>$commcard_invoice,
		commcard_tax_amount=>$commcard_tax_amount
          	);

/****************************** Transaction Object ********************/

$mpgTxn = new mpgTransaction($txnArray);


/****************************** Set Recur Object **********************/

$mpgTxn->setRecur($mpgRecur);


/****************************** Request Object **************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions


/****************************** mpgHttpsPost Object *********************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);


/****************************** Response ********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();


/****************************** Receipt ********************************/

print ("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nRecurSuccess = " . $mpgResponse->getRecurSuccess());
print("\nCardLevelResult = " . $mpgResponse->getCardLevelResult());

?>
