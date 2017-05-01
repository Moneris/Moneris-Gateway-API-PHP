<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************ Transaction Variables ******************************/

$orderid='ord-'.date("dmy-G:i:s");
$amount='1.00';
$enc_track2='02840085000000000416D705CCD4BAC5929D8D1EBF0644C234FBC65476C1D6C9E94B9BED3E4D1A791C3F4FC61C1800486A8A6B6CCAA00431353131FFFF3141594047A000960D5D03';
$device_type='idtech';

/************************ Transaction Array **********************************/

$txnArray=array(type=>'enc_purchase',  
         order_id=>$orderid,
         cust_id=>'cust',
         amount=>$amount,
         enc_track2=>$enc_track2,
         device_type=>$device_type,
         crypt_type=>'7', 
         commcard_invoice=>'Invoice 5757FRJ8',
         commcard_tax_amount=>'0.15',
         dynamic_descriptor=>'12345'
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

//Status check example
//$mpgHttpPost = new mpgHttpsPostStatus($store_id,$api_token,$status,$mpgRequest);

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
print("\nCardLevelResult = " . $mpgResponse->getCardLevelResult());
print("\nMaskedPan = " . $mpgResponse->getMaskedPan());

?>
