<?php

require "../../mpgClasses.php";

$type = "pbb_get_access_token";
$store_id = "monca03650";
$api_token = "7Yw0MPTlhjBRcZiE6837";
$processing_country_code = "CA";

$txnArray=array('type'=>$type,
	'store_id'=>$store_id,
	'api_token'=>$api_token,
	'processing_country_code'=>$processing_country_code);

$mpgTxn = new mpgTransaction($txnArray);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode($processing_country_code);
$mpgRequest->setTestMode(true);

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nTokenType = " . $mpgResponse->getMpgResponseData()["TokenType"] );
print("\nExpiresIn = " . $mpgResponse->getMpgResponseData()["ExpiresIn"] );
print("\nExtExpiresIn = " . $mpgResponse->getMpgResponseData()["ExtExpiresIn"] );
print("\nAccessToken = " . $mpgResponse->getMpgResponseData()["AccessToken"] );
?>

