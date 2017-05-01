<?php

##
## Example php -q TestResGetExpiring.php store3 yesguy
##

//There is a max number of attempts set for this transaction per calendar day
//Can not surpass or will receive Invalid Transaction error

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='store5';
$api_token='yesguy';

/************************* Transactional Variables ****************************/

$type='res_get_expiring';

/*********************** Transactional Associative Array **********************/

$txnArray = array( 'type'=>$type );


/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

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

$DataKeys = $mpgResponse->getDataKeys();

for($i=0; $i < count($DataKeys); $i++)
{
	$mpgResponse->setResolveData($DataKeys[$i]);

	print("\n\nData Key = " . $DataKeys[$i]);
	print("\nCust ID = " . $mpgResponse->getResDataCustId());
	print("\nPhone = " . $mpgResponse->getResDataPhone());
	print("\nEmail = " . $mpgResponse->getResDataEmail());
	print("\nNote = " . $mpgResponse->getResDataNote());
	print("\nMasked Pan = " . $mpgResponse->getResDataMaskedPan());
	print("\nExp Date = " . $mpgResponse->getResDataExpDate());
	print("\nCrypt Type = " . $mpgResponse->getResDataCryptType());
	print("\nAvs Street Number = " . $mpgResponse->getResDataAvsStreetNumber());
	print("\nAvs Street Name = " . $mpgResponse->getResDataAvsStreetName());
	print("\nAvs Zipcode = " . $mpgResponse->getResDataAvsZipcode());

}


?>

