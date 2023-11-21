<?php

require "../../mpgClasses.php";

/******************************* Request Variables ********************************/

$store_id='monca00392';
$api_token='qYdISUhHiOdfTr1CLNpN';
//$status = 'false';

/****************************** Transactional Variables ***************************/

$type='cavv_purchase';  
$order_id="ord-".date("dmy-G:i:s");
$cust_id='customer1';
$amount='1.00';
$pan='4242424242424242';
$expiry_date='0912';
$cavv='AAABBJg0VhI0VniQEjRWAAAAAAA';
//$cavv='AAABBJg0VhI0VniQEjRWAAAAAAA=';
$commcard_invoice='Invoice 5757FRJ8';
$commcard_tax_amount='1.00';
$crypt_type = '7';

/*************************** Transaction Associative Array ************************/

$txnArray=array(
				'type'=>$type,
	    		'order_id'=>$order_id,
				'cust_id'=>$cust_id,
	    		'amount'=>$amount,
	    		'pan'=>$pan,
	    		'expdate'=>$expiry_date,
				'cavv'=>$cavv,
				//commcard_invoice=>$commcard_invoice,
				//commcard_tax_amount=>$commcard_tax_amount,
				'crypt_type'=>$crypt_type, //mandatory for AMEX only
				'dynamic_descriptor'=>'test'
	       		);


/********************** ConvFee Associative Array *************************/

$convFeeTemplate = array(
						 'convenience_fee'=>'1.00'
						);

/************************** ConvFee Object ********************************/

$mpgConvFee = new mpgConvFeeInfo($convFeeTemplate);

/****************************** Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Set ConvFee *****************************/

$mpgTxn->setConvFeeInfo($mpgConvFee);

/******************************* Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/****************************** HTTPS Post Object *******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

//Status check example
//$mpgHttpPost = new mpgHttpsPostStatus($store_id,$api_token,$status,$mpgRequest);

/************************************* Response *********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nCardLevelResult = " . $mpgResponse->getCardLevelResult());
print("\nCavvResultCode = " . $mpgResponse->getCavvResultCode());
print("\nCfSuccess = " . $mpgResponse->getCfSuccess());
print("\nCfStatus = " . $mpgResponse->getCfStatus());
print("\nFeeAmount = " . $mpgResponse->getFeeAmount());
print("\nFeeRate = " . $mpgResponse->getFeeRate());
print("\nFeeType = " . $mpgResponse->getFeeType());
//print("\nStatusCode = " . $mpgResponse->getStatusCode());
//print("\nStatusMessage = " . $mpgResponse->getStatusMessage());


?>
