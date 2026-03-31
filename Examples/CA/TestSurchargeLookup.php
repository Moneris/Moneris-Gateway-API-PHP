<?php

require "../../mpgClasses.php";

$store_id='monca03650';
$api_token='7Yw0MPTlhjBRcZiE6837';

$amount = '50.00';
$pan='4242424242424242';

$txnArray=array('type'=>'surcharge_lookup',
         'amount'=>$amount,
         'pan'=>$pan
           );

$mpgTxn = new mpgTransaction($txnArray);

$mpgRequest = new mpgRequest($mpgTxn);
//print the mpgrequest
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);


$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nMessage = " . $mpgResponse->getMessage());
print("\nCardType = " . $mpgResponse->getCardType());

print("\nIsSurchargeEligible = " . $mpgResponse->getIsSurchargeEligible());
print("\nMaxSurchargeRate = " . $mpgResponse->getMaxSurchargeRate());
print("\nMaxSurchargeAmount = " . $mpgResponse->getMaxSurchargeAmount());
print("\nServiceType = " . $mpgResponse->getServiceType());
?>

