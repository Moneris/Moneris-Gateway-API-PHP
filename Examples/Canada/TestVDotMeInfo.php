<?php

##
## Example php -q TestPurchase.php store1
##

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='store2';
$api_token='yesguy';

/************************* Transactional Variables ****************************/

$callid='8620484083629792701';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>'vdotme_getpaymentinfo',
				'callid'=>$callid
   		       );

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/***************************** HTTPS Post Object *****************************/

/* Status Check Example
$mpgHttpPost  =new mpgHttpsPostStatus($store_id,$api_token,$status_check,$mpgRequest);
*/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$vdotmeinfo=$mpgHttpPost->getMpgResponse();

print("\nResponse Code: " . $vdotmeinfo->getResponseCode());
print("\nResponse Message: " . $vdotmeinfo->getMessage());
print("\nCurrency Code: " . $vdotmeinfo->getCurrencyCode());
print("\nPayment Totals: " . $vdotmeinfo->getPaymentTotal());
print("\nUser First Name: "  . $vdotmeinfo->getUserFirstName());
print("\nUser Last Name: "  . $vdotmeinfo->getUserLastName());
print("\nUsername: "  . $vdotmeinfo->getUserName());
print("\nUser Email: "  . $vdotmeinfo->getUserEmail());
print("\nEncrypted User ID: "  . $vdotmeinfo->getEncUserId());
print("\nCreation Time Stamp: "  . $vdotmeinfo->getCreationTimeStamp());
print("\nName on Card: "  . $vdotmeinfo->getNameOnCard());
print("\nExpiration Month: "  . $vdotmeinfo->getExpirationDateMonth());
print("\nExpiration Year: "  . $vdotmeinfo->getExpirationDateYear());
print("\nLast 4 Digits: "  . $vdotmeinfo->getLastFourDigits());
print("\nBin Number (6 Digits): "  . $vdotmeinfo->getBinSixDigits());        	
print("\nCard Brand: "  . $vdotmeinfo->getCardBrand());
print("\nCard Type: "  . $vdotmeinfo->getVDotMeCardType());
print("\nBilling Person Name: "  . $vdotmeinfo->getBillingPersonName());
print("\nBilling Address Line 1: "  . $vdotmeinfo->getBillingAddressLine1());
print("\nBilling City: "  . $vdotmeinfo->getBillingCity());
print("\nBilling State/Province Code: "  . $vdotmeinfo->getBillingStateProvinceCode());
print("\nBilling Postal Code: "  . $vdotmeinfo->getBillingPostalCode());
print("\nBilling Country Code: "  . $vdotmeinfo->getBillingCountryCode());
print("\nBilling Phone: "  . $vdotmeinfo->getBillingPhone());
print("\nBilling ID: "  . $vdotmeinfo->getBillingId());
print("\nBilling Verification Status: "  . $vdotmeinfo->getBillingVerificationStatus());
print("\nPartial Shipping Country Code: "  . $vdotmeinfo->getPartialShippingCountryCode());
print("\nPartial Shipping Postal Code: "  . $vdotmeinfo->getPartialShippingPostalCode());        	
print("\nShipping Person Name: "  . $vdotmeinfo->getShippingPersonName());
print("\nShipping Address Line 1: "  . $vdotmeinfo->getShippingAddressLine1());
print("\nShipping City: "  . $vdotmeinfo->getShippingCity());
print("\nShipping State/Province Code: "  . $vdotmeinfo->getShippingStateProvinceCode());
print("\nShipping Postal Code: "  . $vdotmeinfo->getShippingPostalCode());
print("\nShipping Country Code: "  . $vdotmeinfo->getShippingCountryCode());
print("\nShipping Phone: "  . $vdotmeinfo->getShippingPhone());
print("\nShipping Default: "  . $vdotmeinfo->getShippingDefault());
print("\nShipping ID: "  . $vdotmeinfo->getShippingId());
print("\nShipping Verification Status: "  . $vdotmeinfo->getShippingVerificationStatus());       	
print("\nisExpired: "  . $vdotmeinfo->getIsExpired());
print("\nBase Image File Name: "  . $vdotmeinfo->getBaseImageFileName());
print("\nHeight: "  . $vdotmeinfo->getHeight());
print("\nWidth: "  . $vdotmeinfo->getWidth());
print("\nIssuer Bid: "  . $vdotmeinfo->getIssuerBid());
print("\nRisk Advice: "  . $vdotmeinfo->getRiskAdvice());
print("\nRisk Score: "  . $vdotmeinfo->getRiskScore());
print("\nAVS Response Code: "  . $vdotmeinfo->getAvsResponseCode());
print("\nCVV Response Code: "  . $vdotmeinfo->getCvvResponseCode());
?>

