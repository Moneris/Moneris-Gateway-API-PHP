<?php

##
## Example php -q TestPurchase.php store1
##

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='store5';
$api_token='yesguy';

/************************* Transactional Variables ****************************/

$type='mcp_purchase';
$cust_id='cust id';
$order_id='ord-'.date("dmy-G:i:s");
$pan='4242424242424242';
$expiry_date='2011';
$crypt='7';
$dynamic_descriptor='123';
$status_check = 'false';

$mcp_version = '1.0';
$cardholder_amount = '100';
$cardholder_currency_code = '840';
$mcp_rate_token = 'P1536163325404090';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
     		    'order_id'=>$order_id,
     		    'cust_id'=>$cust_id,
   			    'pan'=>$pan,
   			    'expdate'=>$expiry_date,
   			    'crypt_type'=>$crypt,
   			    'dynamic_descriptor'=>$dynamic_descriptor,
				'mcp_version'=> $mcp_version,
				'cardholder_amount' => $cardholder_amount, 
				'cardholder_currency_code' => $cardholder_currency_code, 
				'mcp_rate_token' => $mcp_rate_token
				//,'wallet_indicator' => '' //Refer to documentation for details
   		       );

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

/******************* Credential on File **********************************/

$cof = new CofInfo();
$cof->setPaymentIndicator("U");
$cof->setPaymentInformation("2");
$cof->setIssuerId("168451306048014");

$mpgTxn->setCofInfo($cof);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/***************************** HTTPS Post Object *****************************/

/* Status Check Example
$mpgHttpPost  =new mpgHttpsPostStatus($store_id,$api_token,$status_check,$mpgRequest);
*/

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nIsVisaDebit = " . $mpgResponse->getIsVisaDebit());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nStatusCode = " . $mpgResponse->getStatusCode());
print("\nStatusMessage = " . $mpgResponse->getStatusMessage());
print("\nHostId = " . $mpgResponse->getHostId());
print("\nIssuerId = " . $mpgResponse->getIssuerId());

print("\nMerchantSettlementAmount = " . $mpgResponse->getMerchantSettlementAmount());
print("\nCardholderAmount = " . $mpgResponse->getCardholderAmount());
print("\nCardholderCurrencyCode = " . $mpgResponse->getCardholderCurrencyCode());
print("\nMCPRate = " . $mpgResponse->getMCPRate());
print("\nMCPErrorStatusCode = " . $mpgResponse->getMCPErrorStatusCode());
print("\nMCPErrorMessage = " . $mpgResponse->getMCPErrorMessage());
print("\nHostId = " . $mpgResponse->getHostId());

?>

