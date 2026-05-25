<?php

require "../../mpgClasses.php";

$store_id = "monca03650";
$api_token = "7Yw0MPTlhjBRcZiE6837";
$processing_country_code = "CA";
$consent_id = 'ea534ba8-8d92-4510-a728-2b40e1bde735';
$cancel_ref = 'e32f9a6b-843c-4fbb-a8ac-2b11bbca7fd3';
$cancel_reason = 'this is the optional cancel';

$pbb_cancel_consent = new PbbCancelConsent();
$pbb_cancel_consent->setConsentId($consent_id);
$pbb_cancel_consent->setCancelReference($cancel_ref);
$pbb_cancel_consent->setCancelReason($cancel_reason);

$mpgTxn = new mpgTransaction($pbb_cancel_consent);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode($processing_country_code);
$mpgRequest->setTestMode(true);

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();
$correlation_id = ifPresentReturnValue($mpgResponse->getMpgResponseData(), "CorrelationId");
if ($correlation_id != null && $correlation_id != "") {
	print("\nRequestId = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "RequestId"));
	print("\nCorrelationId = " . $correlation_id);
} else {
	print("\nCode = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "Code"));
	print("\nType = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "Type"));
	print("\nMessage = " . $mpgResponse->getMessage());
}

function ifPresentReturnValue($array, $key) {
	if (array_key_exists($key, $array)) {
		return $array[$key];
	}
	return "";
}
?>

