<?php

##
## Example php -q TestPurchase.php store1
##

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='store5';
$api_token='yesguy';

$type = 'mcp_get_rate';
$mcp_version = '1.0';
$rate_txn_type = 'P';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
				'mcp_version'=>$mcp_version,
				'rate_txn_type'=>$rate_txn_type
   		       );

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

/******************* Credential on File **********************************/

$mcpRate = new MCPRate();
$mcpRate->setCardholderAmount('100', '840');
$mcpRate->setMerchantSettlementAmount('200', '826');

$mpgTxn->setMCPRateInfo($mcpRate);

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

print("\nRateTxnType = " . $mpgResponse->getRateTxnType());
print("\nMCPRateToken = " . $mpgResponse->getMCPRateToken());

print("\nRateInqStartTime = " . $mpgResponse->getRateInqStartTime());  //The time (unix UTC) of when the rate is requested
print("\nRateInqEndTime = " . $mpgResponse->getRateInqEndTime());  //The time (unix UTC) of when the rate is returned
print("\nRateValidityStartTime = " . $mpgResponse->getRateValidityStartTime());    //The time (unix UTC) of when the rate is valid from
print("\nRateValidityEndTime = " . $mpgResponse->getRateValidityEndTime());    //The time (unix UTC) of when the rate is valid until
print("\nRateValidityPeriod = " . $mpgResponse->getRateValidityPeriod());  //The time in minutes this rate is valid for

print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTimedOut = " . $mpgResponse->getTimedOut());

//RateData
for ($index = 0; $index < $mpgResponse->getRatesCount(); $index++)
{
	print("\nMCPRate = " . $mpgResponse->getMCPRate($index));
	print("\nMerchantSettlementCurrency = " . $mpgResponse->getMerchantSettlementCurrency($index));
	print("\nMerchantSettlementAmount = " . $mpgResponse->getMerchantSettlementAmount($index));    //Domestic(CAD) amount
	print("\nCardholderCurrencyCode = " . $mpgResponse->getCardholderCurrencyCode($index));
	print("\nCardholderAmount = " . $mpgResponse->getCardholderAmount($index));    //Foreign amount
	
	print("\nMCPErrorStatusCode = " . $mpgResponse->getMCPErrorStatusCode($index));
	print("\nMCPErrorMessage = " . $mpgResponse->getMCPErrorMessage($index));
}

?>

