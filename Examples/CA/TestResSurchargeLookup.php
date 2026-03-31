<?php

require "../../mpgClasses.php";

$store_id='moneris';
$api_token='hurgle';

$amount = '50.00';
$data_key='yOhbwgj8TI1aLNsy4jznrpbI2';

$txnArray=array('type'=>'res_surcharge_lookup',
         'amount'=>$amount,
         'data_key'=>$data_key
           );

$mpgTxn = new mpgTransaction($txnArray);

$mpgRequest = new mpgRequest($mpgTxn);
//print the mpgrequest
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

$mpgResponse=$mpgHttpPost->getMpgResponse();
print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nCardType = " . $mpgResponse->getCardType());
print("\nIsSurchargeEligible = " . $mpgResponse->getIsSurchargeEligible());
print("\nMaxSurchargeRate = " . $mpgResponse->getMaxSurchargeRate());
print("\nMaxSurchargeAmount = " . $mpgResponse->getMaxSurchargeAmount());
print("\nServiceType = " . $mpgResponse->getServiceType());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());

print("\n\nCust ID = " . $mpgResponse->getResDataCustId());
print("\nPhone = " . $mpgResponse->getResDataPhone());
print("\nEmail = " . $mpgResponse->getResDataEmail());
print("\nNote = " . $mpgResponse->getResDataNote());
print("\nMasked Pan = " . $mpgResponse->getResDataMaskedPan());
print("\nExp Date = " . $mpgResponse->getResDataExpDate());
print("\nCrypt Type = " . $mpgResponse->getResDataCryptType());
print("\nAvs Street Number = " . $mpgResponse->getResDataAvsStreetNumber());
print("\nAvs Street Name = " . $mpgResponse->getResDataAvsStreetName());
print("\nAvs Zipcode = " . $mpgResponse->getResDataAvsZipcode());
?>

