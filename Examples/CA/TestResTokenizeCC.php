<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='monca03650';
$api_token='7Yw0MPTlhjBRcZiE6837';

/************************* Transactional Variables ****************************/

$type='res_tokenize_cc';
$order_id='Test230125-16:59:38';
$txn_number='27338-0_579';
$data_key_format = "0";
$return_issuer_id = "true";

$cust_id='customer1';
$phone = '4165555555';
$email = 'bob@smith.com';
$note = 'this is my note';

$avs_street_number = '123';
$avs_street_name = 'lakeshore blvd';
$avs_zipcode = '90210';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
				'order_id'=>$order_id,
				'txn_number'=>$txn_number,
				//'data_key_format'=>$data_key_format, //optional
				'return_issuer_id'=>$return_issuer_id,
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

/******************* Credential on File **********************************/

$cof = new CofInfo();
$cof->setIssuerId("168451306048014");

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);
$mpgTxn->setAvsInfo($mpgAvsInfo);
$mpgTxn->setCofInfo($cof);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
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
print("\nIssuer ID = " . $mpgResponse->getIssuerId());

?>

