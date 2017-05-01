<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************* Transactional Variables ****************************/

$type='enc_res_add_cc';  
$cust_id='customer3';
$phone = '4169996578';
$email = 'bob@smith.com';
$note = 'this is my note';
$enc_track2 = '02840085000000000416D705CCD4BAC5929D8D1EBF0644C234FBC65476C1D6C9E94B9BED3E4D1A791C3F4FC61C1800486A8A6B6CCAA00431353131FFFF3141594047A000960D5D03';
$device_type = 'idtech';
$crypt_type='7';
$avs_street_number = '112';
$avs_street_name = 'lakeshore blvd';
$avs_zipcode = '15645';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
		'cust_id'=>$cust_id,
		'phone'=>$phone,
		'email'=>$email,
		'note'=>$note,
		'enc_track2'=>$enc_track2,
		'device_type'=>$device_type,
		'crypt_type'=>$crypt_type
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

