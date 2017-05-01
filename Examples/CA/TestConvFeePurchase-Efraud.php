<?php

/* eSELECTplus US Convenience Fee Account Required this transaction*/

require "../../mpgClasses.php";

/************************ Request Variables ***************************/

$store_id='monca00392';
$api_token='qYdISUhHiOdfTr1CLNpN';

/********************* Transactional Variables ************************/

$type='purchase';  
$order_id='ord-'.date("dmy-G:i:s");
$cust_id="customer id";
$amount='10.31';
$pan='4242424242424242';
$expiry_date="1511";
$crypt='7';

/************************** AVS Variables *****************************/

$avs_street_number = '201';
$avs_street_name = 'Michigan Ave';
$avs_zipcode = 'M1M1M1';

/************************** CVD Variables *****************************/

$cvd_indicator = '1';
$cvd_value = '198';

/********************** AVS Associative Array *************************/

$avsTemplate = array(
		     		 'avs_street_number'=>$avs_street_number,
                     'avs_street_name' =>$avs_street_name,
                     'avs_zipcode' => $avs_zipcode
                    );

/********************** CVD Associative Array *************************/

$cvdTemplate = array(
		     		 'cvd_indicator' => $cvd_indicator,
                     'cvd_value' => $cvd_value
                    );

/********************** ConvFee Associative Array *************************/

$convFeeTemplate = array(
					     'convenience_fee'=>'1.00'
					    );

/************************** AVS Object ********************************/

$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);

/************************** CVD Object ********************************/

$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);

/************************** ConvFee Object ********************************/

$mpgConvFee = new mpgConvFeeInfo($convFeeTemplate);

/***************** Transactional Associative Array ********************/

$txnArray=array(
		'type'=>$type,
		'order_id'=>$order_id,
		'cust_id'=>$cust_id,
		'amount'=>$amount,
		'pan'=>$pan,
		'expdate'=>$expiry_date,
		'crypt_type'=>$crypt,
          	);

/********************** Transaction Object ****************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Set AVS, CVD and ConvFee *****************************/

$mpgTxn->setAvsInfo($mpgAvsInfo);
$mpgTxn->setCvdInfo($mpgCvdInfo);
$mpgTxn->setConvFeeInfo($mpgConvFee);

/************************ Request Object ******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/*********************** HTTPS Post Object ****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/*************************** Response *********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print ("\nCardType = " . $mpgResponse->getCardType());
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
print("\nAVSResponse = " . $mpgResponse->getAvsResultCode());
print("\nCVDResponse = " . $mpgResponse->getCvdResultCode());
print("\nCardLevelResult = " . $mpgResponse->getCardLevelResult());
print("\nCfSuccess = " . $mpgResponse->getCfSuccess());
print("\nCfStatus = " . $mpgResponse->getCfStatus());
print("\nFeeAmount = " . $mpgResponse->getFeeAmount());
print("\nFeeRate = " . $mpgResponse->getFeeRate());
print("\nFeeType = " . $mpgResponse->getFeeType());

?>
