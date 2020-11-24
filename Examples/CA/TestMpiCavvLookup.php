<?php

require "../../mpgClasses.php";

/******************************* Request Variables ********************************/
$store_id = "moneris";
$api_token = "hurgle";

//BASE64 Encoded CRes value returned from response at completion of challenge flow.
$cres = "eyJhY3NUcmFuc0lEIjoiNzQ0ZDI2NjUtNjU2Yy00ZGNiLTg3MWUtYTBkYmMwODA0OTYzIiwibWVzc2FnZVR5cGUiOiJDUmVzIiwiY2hhbGxlbmdlQ29tcGxldGlvbkluZCI6IlkiLCJtZXNzYWdlVmVyc2lvbiI6IjIuMS4wIiwidHJhbnNTdGF0dXMiOiJZIiwidGhyZWVEU1NlcnZlclRyYW5zSUQiOiJlMTFkNDk4NS04ZDI1LTQwZWQtOTlkNi1jMzgwM2ZlNWU2OGYifQ==";
		
$mpiCavvLookup = new MpiCavvLookup();
$mpiCavvLookup->setCRes($cres);

/****************************** Transaction Object *******************************/

$mpgTxn = new mpgTransaction($mpiCavvLookup);

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

print("\nThreeDSServerTransId = " .  $mpgResponse->getMpiThreeDSServerTransId());
print("\nTransStatus = " .  $mpgResponse->getMpiTransStatus());
print("\nChallengeCompletionIndicator = " .  $mpgResponse->getMpiChallengeCompletionIndicator());
print("\nCavv = " .  $mpgResponse->getMpiCavv());
print("\nECI = " .  $mpgResponse->getMpiEci());

?>
