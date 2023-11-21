<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monca00597';
$api_token='O27AbCbxQorPggMQe6hU';

/************************ Transaction Variables ******************************/

$data_key='4HIme0ZGURXE3NRBXHUj6nSc4';
$orderid='res-purch-'.date("dmy-G:i:s");
$amount='18.00';
$custid='customer1';
$crypt_type='7';

$expdate='2301'; //For Temp Tokens only

//NT Response Option
$get_nt_response = 'false';//Optional - set it true only if you want to get network tokenization response.

/************************ Transaction Array **********************************/

$txnArray=array('type'=>'res_purchase_cc',
				'data_key'=>$data_key,
		        'order_id'=>$orderid,
		        'cust_id'=>$custid,
		        'amount'=>$amount,
		        'crypt_type'=>$crypt_type,
				'expdate'=>$expdate,
				'dynamic_descriptor'=>'12484',
				'get_nt_response'=>$get_nt_response
		        );


/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/******************* Credential on File **********************************/

$cof = new CofInfo();
$cof->setPaymentIndicator("U");
$cof->setPaymentInformation("2");
$cof->setIssuerId("139X3130ASCXAS9");

$mpgTxn->setCofInfo($cof);

/******************* Installment Info *OPTIONAL* **********************************/

$installmentInfo = new InstallmentInfo();
$installmentInfo->setPlanId("ae859ef1-eb91-b708-8b80-1dd481746401");
$installmentInfo->setPlanIdRef("0000000065");
$installmentInfo->setTacVersion("2");

//$mpgTxn->setInstallmentInfo($installmentInfo);

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
print("\nAVSResponse = " . $mpgResponse->getAvsResultCode());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());
print("\nIssuerId = " . $mpgResponse->getIssuerId());
print("\n\nSourcePanLast4 = " . $mpgResponse->getSourcePanLast4());

// $installmentResults = $mpgResponse->getInstallmentResults();

// print("\nPlanId = " . $installmentResults->getPlanId());
// print("\nPlanIDRef = " . $installmentResults->getPlanIDRef());
// print("\nTacVersion = " . $installmentResults->getTacVersion());
// print("\nPlanAcceptanceId = " . $installmentResults->getPlanAcceptanceId());
// print("\nPlanStatus = " . $installmentResults->getPlanStatus()); 
// print("\nPlanResponse = " . $installmentResults->getPlanResponse());

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
