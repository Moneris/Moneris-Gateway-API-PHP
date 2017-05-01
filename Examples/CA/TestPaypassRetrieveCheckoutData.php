<?php
require "../../mpgClasses.php";

$store_id="moneris";
$api_token="hurgle";

$txnArray=array(
	'type'=>'paypass_retrieve_checkout_data',
	'oauth_token'=>'78a5cbdd1e102f14fe7ca9357f34220824b372fc',
	'oauth_verifier'=>'fb5d463a2dcd4620e8bf67c97446b210bfbe6768',
	'checkout_resource_url'=>'https://sandbox.api.mastercard.com/online/v3/checkout/267933261'
);

$mpgTxn = new mpgTransaction($txnArray);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

$mpgResponse=$mpgHttpPost->getMpgResponse();

// Response Information
//
print("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nISO = " . $mpgResponse->getISO());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nIsVisaDebit = " . $mpgResponse->getIsVisaDebit());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nMPRequestToken = " . $mpgResponse->getMPRequestToken());
print("\nMPRedirectUrl = " . $mpgResponse->getMPRedirectUrl());

print("\n\nMasterpass Info");
print("\nCardBrandId = " . $mpgResponse->getCardBrandId());
print("\nCardBrandName = " . $mpgResponse->getCardBrandName());
print("\nCardBillingAddressCity = " . $mpgResponse->getCardBillingAddressCity());
print("\nCardBillingAddressCountry = " . $mpgResponse->getCardBillingAddressCountry());
print("\nCardBillingAddressCountrySubdivision = " . $mpgResponse->getCardBillingAddressCountrySubdivision());
print("\nCardBillingAddressLine1 = " . $mpgResponse->getCardBillingAddressLine1());
print("\nCardBillingAddressLine2 = " . $mpgResponse->getCardBillingAddressLine2());
print("\nCardBillingAddressPostalCode = " . $mpgResponse->getCardBillingAddressPostalCode());
print("\nCardBillingAddressRecipientPhoneNumber = " . $mpgResponse->getCardBillingAddressRecipientPhoneNumber());
print("\nCardBillingAddressRecipientName = " . $mpgResponse->getCardBillingAddressRecipientName());
print("\nCardCardHolderName = " . $mpgResponse->getCardCardHolderName());
print("\nCardExpiryMonth = " . $mpgResponse->getCardExpiryMonth());
print("\nCardExpiryYear = " . $mpgResponse->getCardExpiryYear());
print("\nContactEmailAddress = " . $mpgResponse->getContactEmailAddress());
print("\nContactFirstName = " . $mpgResponse->getContactFirstName());
print("\nContactLastName = " . $mpgResponse->getContactLastName());
print("\nContactPhoneNumber = " . $mpgResponse->getContactPhoneNumber());
print("\nShippingAddressCity = " . $mpgResponse->getShippingAddressCity());
print("\nShippingAddressCountry = " . $mpgResponse->getShippingAddressCountry());
print("\nShippingAddressCountrySubdivision = " . $mpgResponse->getShippingAddressCountrySubdivision());
print("\nShippingAddressLine1 = " . $mpgResponse->getShippingAddressLine1());
print("\nShippingAddressLine2 = " . $mpgResponse->getShippingAddressLine2());
print("\nShippingAddressPostalCode = " . $mpgResponse->getShippingAddressPostalCode());
print("\nShippingAddressRecipientName = " . $mpgResponse->getShippingAddressRecipientName());
print("\nShippingAddressRecipientPhoneNumber = " . $mpgResponse->getShippingAddressRecipientPhoneNumber());
print("\nPayPassWalletIndicator = " . $mpgResponse->getPayPassWalletIndicator());
print("\nAuthenticationOptionsAuthenticateMethod = " . $mpgResponse->getAuthenticationOptionsAuthenticateMethod());
print("\nAuthenticationOptionsCardEnrollmentMethod = " . $mpgResponse->getAuthenticationOptionsCardEnrollmentMethod());
print("\nCardAccountNumber = " . $mpgResponse->getCardAccountNumber());
print("\nAuthenticationOptionsEciFlag = " . $mpgResponse->getAuthenticationOptionsEciFlag());
print("\nAuthenticationOptionsPaResStatus = " . $mpgResponse->getAuthenticationOptionsPaResStatus());
print("\nAuthenticationOptionsSCEnrollmentStatus = " . $mpgResponse->getAuthenticationOptionsSCEnrollmentStatus());
print("\nAuthenticationOptionsSignatureVerification = " . $mpgResponse->getAuthenticationOptionsSignatureVerification());
print("\nAuthenticationOptionsXid = " . $mpgResponse->getAuthenticationOptionsXid());
print("\nAuthenticationOptionsCAvv = " . $mpgResponse->getAuthenticationOptionsCAvv());
print("\nTransactionId = " . $mpgResponse->getTransactionId());
?>

