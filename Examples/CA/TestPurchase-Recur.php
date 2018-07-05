<?php

##
## Example php -q TestPurchase-Recur.php store3 yesguy unique_order_id
##

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id = 'store5';
$api_token = 'yesguy';

/********************************* Recur Variables ****************************/
$recurUnit = 'eom';
$startDate = '2018/11/30';
$numRecurs = '4';
$recurInterval = '10';
$recurAmount = '31.00';
$startNow = 'true';

/************************* Transactional Variables ****************************/

$orderId = 'ord-'.date("dmy-G:i:s");
$custId = 'student_number';
$creditCard = '5454545454545454';
$nowAmount = '10.00';
$expiryDate = '0912';
$cryptType = '7';

/*********************** Recur Associative Array **********************/

$recurArray = array('recur_unit'=>$recurUnit, // (day | week | month)
					'start_date'=>$startDate, //yyyy/mm/dd
					'num_recurs'=>$numRecurs,
					'start_now'=>$startNow,
					'period' => $recurInterval,
					'recur_amount'=> $recurAmount
					);

$mpgRecur = new mpgRecur($recurArray);

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>'purchase',
				'order_id'=>$orderId,
				'cust_id'=>$custId,
				'amount'=>$nowAmount,
				'pan'=>$creditCard,
				'expdate'=>$expiryDate,
				'crypt_type'=>$cryptType
				);

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

/****************************** Recur Object *********************************/

$mpgTxn->setRecur($mpgRecur);

/******************* Credential on File **********************************/

$cof = new CofInfo();
$cof->setPaymentIndicator("R");
$cof->setPaymentInformation("2");
$cof->setIssuerId("168451306048014");

$mpgTxn->setCofInfo($cof);


/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print ("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nIsVisaDebit = " . $mpgResponse->getIsVisaDebit());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nRecurSuccess = " . $mpgResponse->getRecurSuccess());
print("\nIssuerId = " . $mpgResponse->getIssuerId());
?>
