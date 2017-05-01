<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************ Transaction Variables ******************************/

$orderid='ord-'.date("dmy-G:i:s");
$amount='1.00';
$pan='4242424242424242';
$expiry_date='1511';
$presentation_type = 'X';
$intended_use = '0';
$p_account_number = '1234567890';

/************************ Transaction Array **********************************/

$txnArray=array(type=>'pinless_debit_purchase',  
				 order_id=>$orderid,
				 cust_id=>'cust',				//This field is optional
				 amount=>$amount,
				 pan=>$pan,
				 expdate=>$expiry_date,			//This field is optional
				 presentation_type=>$presentation_type,
				 intended_use=>$intended_use,
				 p_account_number=>$p_account_number
           		);

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

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/****************************** Set Recur Object **********************/

$mpgTxn->setRecur($mpgRecur);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();


print("\nCardType = " . $mpgResponse->getCardType());
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


?>
