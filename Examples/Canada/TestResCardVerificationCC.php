<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monca00597';
$api_token='O27AbCbxQorPggMQe6hU';

/************************ Transaction Variables ******************************/

$data_key='4HIme0ZGURXE3NRBXHUj6nSc4';
$orderid='res-purch-'.date("dmy-G:i:s");
$crypt_type='1';

$expdate='2301'; //for temp token

//NT Response Option
$get_nt_response = 'false';//Optional - set it true only if you want to get network tokenization response.

/************************ Transaction Array **********************************/

$txnArray=array('type'=>'res_card_verification_cc',
				'data_key'=>$data_key,
		        'order_id'=>$orderid,
				'crypt_type'=>$crypt_type,
		        'expdate'=>$expdate,
				'get_nt_response'=>$get_nt_response
		        );

/************************** CVD Variables *****************************/

$cvd_indicator = '1';
$cvd_value = '198';

/********************** CVD Associative Array *************************/

$cvdTemplate = array(
		'cvd_indicator' => $cvd_indicator,
		'cvd_value' => $cvd_value
);

$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);

/************************** AVS Variables *****************************/

//The AVS portion is optional if AVS details are already stored in this profile
//If AVS details are resent in Purchase transaction, they will replace stored details

$avs_street_number = '';
$avs_street_name = 'bloor st';
$avs_zipcode = '111111';

/********************** AVS Associative Array *************************/

$avsTemplate = array(
		'avs_street_number' => $avs_street_number,
		'avs_street_name' => $avs_street_name,
		'avs_zipcode' => $avs_zipcode
);

$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);
$mpgTxn->setCvdInfo($mpgCvdInfo);
$mpgTxn->setAvsInfo($mpgAvsInfo);

/******************* Credential on File **********************************/

$cof = new CofInfo();
$cof->setPaymentIndicator("U");
$cof->setPaymentInformation("2");
$cof->setIssuerId("168451306048014");

$mpgTxn->setCofInfo($cof);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();


print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nCardType = " . $mpgResponse->getCardType());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nCVDResponse = " . $mpgResponse->getCvdResultCode());
print("\nAVSResponse = " . $mpgResponse->getAvsResultCode());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());
print("\nIssuerId = " . $mpgResponse->getIssuerId());
print("\n\nSourcePanLast4 = " . $mpgResponse->getSourcePanLast4());

if($get_nt_response == 'true') 
{
	print("\n\nNTResponseCode = " . $mpgResponse->getNTResponseCode());
	print("\nNTMessage = " . $mpgResponse->getNTMessage());
	print("\nNTUsed = " . $mpgResponse->getNTUsed());
	print("\nNTTokenBin = " . $mpgResponse->getNTTokenBin());
	print("\nNTTokenLast4 = " . $mpgResponse->getNTTokenLast4());
	print("\nNTTokenExpDate = " . $mpgResponse->getNTTokenExpDate());
}

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
