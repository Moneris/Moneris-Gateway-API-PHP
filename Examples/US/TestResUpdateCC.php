<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************* Transactional Variables ****************************/

$type='res_update_cc';  
$data_key='FjhVlt4020HAVSaOmnaaPACpJ';
$cust_id='';
$phone = '';
$email = '';
$note = '';
$pan='4242424242424242';
$expiry_date='1811';
$crypt_type='7';

$avs_street_number = '';
$avs_street_name = '';
$avs_zipcode = '';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
		'data_key'=>$data_key,
     		'cust_id'=>$cust_id,
     		'phone'=>$phone,
		'email'=>$email,
		'note'=>$note,
    		'pan'=>$pan,
   		'expdate'=>$expiry_date,
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
print("\nPresentation Type = " . $mpgResponse->getResDataPresentationType());
print("\nP Account Number = " . $mpgResponse->getResDataPAccountNumber());
print("\nSec = " . $mpgResponse->getResDataSec());
print("\nCust First Name = " . $mpgResponse->getResDataCustFirstName());
print("\nCust Last Name = " . $mpgResponse->getResDataCustLastName());
print("\nCust Address 1 = " . $mpgResponse->getResDataCustAddress1());
print("\nCust Address 2 = " . $mpgResponse->getResDataCustAddress2());
print("\nCust City = " . $mpgResponse->getResDataCustCity());
print("\nCust State = " . $mpgResponse->getResDataCustState());
print("\nCust Zip = " . $mpgResponse->getResDataCustZip());
print("\nRouting Num = " . $mpgResponse->getResDataRoutingNum());
print("\nMasked Account Num = " . $mpgResponse->getResDataMaskedAccountNum());
print("\nCheck Num = " . $mpgResponse->getResDataCheckNum());
print("\nAccount Type = " . $mpgResponse->getResDataAccountType());

?>

