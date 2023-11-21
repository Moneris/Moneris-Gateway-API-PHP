<?php

require "../../mpgClasses.php";

$store_id='store5';
$api_token='yesguy';

$orderid='ord-'.date("dmy-G:i:s");
$amount = '1.00';
$pan='4761260000000134';
$expiry_date='2212';
$crypt='7';
$dynamic_descriptor='123456';

$txnArray=array('type'=>'oct_payment',
         'order_id'=>$orderid,
         'cust_id'=>'my cust id',
         'amount'=>$amount,
         'pan'=>$pan,
         'expdate'=>$expiry_date,
         'crypt_type'=>$crypt,
         'dynamic_descriptor'=>$dynamic_descriptor
           );

$mpgTxn = new mpgTransaction($txnArray);

/******************* Optional - Credential on File **********************************/

$cof = new CofInfo();
$cof->setPaymentIndicator("U");
$cof->setPaymentInformation("2");
$cof->setIssuerId("168451306048014");

//$mpgTxn->setCofInfo($cof);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);


$mpgResponse=$mpgHttpPost->getMpgResponse();


print("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nIsVisaDebit = " . $mpgResponse->getIsVisaDebit());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nFastFundsIndicator = " . $mpgResponse->getFastFundsIndicator());

?>

