<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************* Transactional Variables ****************************/

$type='res_tokenize_cc';  

/************************ Transaction Variables ******************************/

$order_id='ord-140515-12:09:18';
$txn_number='123288-0_25';

$cust_id='custom1';
$phone = '9999999999';
$email = 'bob@smith.com';
$note = 'this is my note';

$avs_street_number = '444';
$avs_street_name = 'lakeshore blvd';
$avs_zipcode = '47204';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
		'order_id'=>$order_id,
		'txn_number'=>$txn_number,
		'cust_id'=>$cust_id,
		'phone'=>$phone,
		'email'=>$email,
		'note'=>$note
   		);

/********************** AVS Associative Array *********************************/

$avsTemplate = array(
		'avs_street_number' => $avs_street_number,
		'avs_street_name' => $avs_street_name,
		'avs_zipcode' => $avs_zipcode
		);

/************************** AVS Object ***************************************/

$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);
$mpgTxn->setAvsInfo($mpgAvsInfo);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());

//----------------- ResolveData ------------------------------

print("\n\nCust ID = " . $mpgResponse->getResDataCustId());
print("\nPhone = " . $mpgResponse->getResDataPhone());
print("\nEmail = " . $mpgResponse->getResDataEmail());
print("\nNote = " . $mpgResponse->getResDataNote());
print("\nMasked Pan = " . $mpgResponse->getResDataMaskedPan());
print("\nExp Date = " . $mpgResponse->getResDataExpDate());
print("\nCrypt Type = " . $mpgResponse->getResDataCryptType());
print("\nAvs Street Number = " . $mpgResponse->getResDataAvsStreetNumber());
print("\nAvs Street Name = " . $mpgResponse->getResDataAvsStreetName());
print("\nAvs Zipcode = " . $mpgResponse->getResDataAvsZipcode());

?>

