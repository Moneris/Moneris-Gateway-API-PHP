<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='store5';
$api_token='yesguy';

/************************* Transactional Variables ****************************/

$type='enc_res_update_cc';
$data_key='F91LyeEJjv8OvpOdmXYWKh7dV';
$cust_id='cust2';
$phone = '4169996999';
$email = 'bob@email.com';
$note = 'note4';
$enc_track2 = '028400850000000004168FD1D5CC11C4D40338907BB070F3D219318B242B9719CE5CDBF44C412304E045971CC6E36F7842DAF11907210431383131FFFF3141594047A00094739F03';
$device_type='idtech_bdk';
$crypt_type='7';

$avs_street_number = '3300';
$avs_street_name = 'bloor street west';
$avs_zipcode = 'm8x2x3';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
		'data_key'=>$data_key,
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

?>

