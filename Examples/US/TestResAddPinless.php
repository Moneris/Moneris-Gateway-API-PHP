<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************ Transaction Variables ******************************/

$type='res_add_pinless';  
$cust_id='my cust id';
$phone = '416-555-5555';
$email = 'bob@smith.com';
$note = 'this is my note';
$pan='4242424242424242';
$expiry_date='1509';
$presentation_type = 'W';
$p_account_number = '1234567890123456789012345';

/************************ Transaction Array **********************************/

$txnArray = array ( 'type'=>$type,
				 	'cust_id'=>$cust_id,
					'phone'=>$phone,
					'email'=>$email,
					'note'=>$note,
					'pan'=>$pan,
				 	'expdate'=>$expiry_date,
				 	'presentation_type'=>$presentation_type,
				 	'p_account_number'=>$p_account_number
           		  );


/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

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
print("\nPresentation Type = " . $mpgResponse->getResDataPresentationType());
print("\nP Account Number = " . $mpgResponse->getResDataPAccountNumber());

?>
