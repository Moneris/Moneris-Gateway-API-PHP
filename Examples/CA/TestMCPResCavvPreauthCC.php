<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='store1';
$api_token='yesguy1';

/************************ Transaction Variables ******************************/

$data_key='4INQR1A8ocxD0oafSz50LADXy';
$orderid='res-preauth-'.date("dmy-G:i:s");
$amount='1.00';
$cavv='AAABBJg0VhI0VniQEjRWAAAAAAA';
$custid='customer1';	//if sent will be submitted, otherwise cust_id from profile will be used
$expdate = '1901'; //YYMM - used only for temp token
$crypt_type = '7'; //value obtained from MpiACS transaction

$mcp_version = '1.0';
$cardholder_amount = '100';
$cardholder_currency_code = '840';
$mcp_rate_token = 'P1623438275728112';

/************************ Transaction Array **********************************/

$txnArray =array('type'=>'mcp_res_cavv_preauth_cc',
				 'data_key'=>$data_key,
				 'order_id'=>$orderid,
				 'cust_id'=>$custid,
				 'amount'=>$amount,
				 'cavv'=>$cavv,
				 'expdate'=>$expdate,  //mandatory for temp tokens only
				 'crypt_type'=>$crypt_type, //set for AMEX SafeKey only
				 //'dynamic_descriptor'=>'12346',
				 'threeds_version' => '2', //Mandatory for 3DS Version 2.0+
				 'threeds_server_trans_id' => 'e11d4985-8d25-40ed-99d6-c3803fe5e68f', //Mandatory for 3DS Version 2.0+ - obtained from MpiCavvLookup or MpiThreeDSAuthentication 
                 'ds_trans_id' => '12345', //Optional - to be used only if you are using 3rd party 3ds 2.0 service
                 'mcp_version'=> $mcp_version,
                 'cardholder_amount' => $cardholder_amount,
                 'cardholder_currency_code' => $cardholder_currency_code,
                 'mcp_rate_token' => $mcp_rate_token            
                );

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/******************* Credential on File **********************************/

$cof = new CofInfo();
$cof->setPaymentIndicator("U");
$cof->setPaymentInformation("2");
$cof->setIssuerId("139X3130ASCXAS9");

$mpgTxn->setCofInfo($cof);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

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
print("\nCavvResultCode = " . $mpgResponse->getCavvResultCode());
print("\nIssuerId = " . $mpgResponse->getIssuerId());
print("\nThreeDSVersion = " . $mpgResponse->getThreeDSVersion());

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
