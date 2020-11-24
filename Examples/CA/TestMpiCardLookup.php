<?php

require "../../mpgClasses.php";

/******************************* Request Variables ********************************/
$store_id = "moneris";
$api_token = "hurgle";

$order_id = 'ord-'.date("dmy-G:i:s");
$pan = "4740611374762707";
		
$mpiCardLookup = new MpiCardLookup();
$mpiCardLookup->setOrderId($order_id);
$mpiCardLookup->setPan($pan);
//$mpiCardLookup->setDataKey("8OOXGiwxgvfbZngigVFeld9d2"); //Optional - For Moneris Vault and Hosted Tokenization tokens in place of setPan
$mpiCardLookup->setNotificationUrl("https://yournotificationurl.com"); // (Website URL that will receive 3DS Method Completion response from ACS)

/****************************** Transaction Object *******************************/

$mpgTxn = new mpgTransaction($mpiCardLookup);

/******************************* Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/****************************** HTTPS Post Object *******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************************* Response *********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nMessageType = " . $mpgResponse->getMpiMessageType());
print("\nThreeDSMethodURL = " . $mpgResponse->getMpiThreeDSMethodURL());
print("\nThreeDSMethodData = " . $mpgResponse->getMpiThreeDSMethodData());
print("\nThreeDSServerTransId = " . $mpgResponse->getMpiThreeDSServerTransId());

?>
