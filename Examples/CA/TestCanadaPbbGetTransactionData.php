<?php

require "../../mpgClasses.php";

$store_id = "monca03650";
$api_token = "7Yw0MPTlhjBRcZiE6837";
$processing_country_code = "CA";
$consent_id = '52fc438c-373d-40ea-abb7-c9809af56498';
$amount = "21.00";
$currency = "CAD";
$transaction_type = "PRE_AUTH";
$payment_type = "SINGLE";

$pbb_get_transaction_data = new PbbGetTransactionData();
$pbb_get_transaction_data->setConsentId($consent_id);
$pbb_get_transaction_data->setAmount($amount);
$pbb_get_transaction_data->setCurrency($currency);
$pbb_get_transaction_data->setPaymentType($payment_type);
$pbb_get_transaction_data->setTransactionType($transaction_type);


$mpgTxn = new mpgTransaction($pbb_get_transaction_data);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode($processing_country_code);
$mpgRequest->setTestMode(true);

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();
print("\nTransactionRef = " . $mpgResponse->getTransactionRef());

print("\n\n\tTransactionData:");
print("\n\tPaymentMethod = " . $mpgResponse->getPaymentMethod());
print("\n\tConsentId= " . $mpgResponse->getConsentId2());
print("\n\tPaymentType = " . $mpgResponse->getPBBPaymentType());
print("\n\tCurrency = " . $mpgResponse->getCurrency());
print("\n\tAmount = " . $mpgResponse->getAmount());
print("\n\tTransactionRef = " . $mpgResponse->getTransactionRef2());
print("\n\tTokenExpiry = " . $mpgResponse->getTokenExpiry());
print("\n\tPaymentToken = " . $mpgResponse->getPaymentToken());
print("\n\tCryptogram = " . $mpgResponse->getCryptogram());
print("\n\tCryptogramExpiry = " . $mpgResponse->getCryptogramExpiry());
print("\n\nPaymentMethod = " . $mpgResponse->getPaymentMethod2());
print("\nTokenPanLastDigits = " . $mpgResponse->getTokenPanLastDigits());
print("\n\nEci = " . $mpgResponse->getECI());
print("\nPar = " . $mpgResponse->getPar());

function ifPresentReturnValue($array, $key) {
	if (array_key_exists($key, $array)) {
		return $array[$key];
	}
	return "";
}
?>

