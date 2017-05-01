<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************ Transaction Variables ******************************/

$type='res_update_pinless';  
$data_key='y5SJ5IWTrRdAH8UT5UFWfm3DO';
$cust_id='';
$phone = '';
$email = '';
$note = '';
$pan='5454545454545454';
$expiry_date='0909';
$presentation_type = 'W';
$p_account_number = '23456789';

/************************ Transaction Array **********************************/

$txnArray = array ( 'type'=>$type,
					'data_key'=>$data_key,
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
