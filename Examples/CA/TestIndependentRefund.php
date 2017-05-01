<?php

##
## This program takes 3 arguments from the command line:
## 1. Store id
## 2. api token
## 3. order id
##
## Example php -q TestIndependentRefund.php store1 yesguy unique_order_id
##

require "../../mpgClasses.php";

$store_id='store5';
$api_token='yesguy';

$orderid='ord-'.date("dmy-G:i:s");
$amount = '1.00';
$pan='4242424242424242';
$expiry_date='2011';
$crypt='7';
$dynamic_descriptor='123456';

//Optional - Set for Multi-Currency only
//$amount must be 0.00 when using multi-currency
$mcp_amount = '100'; //penny value amount 1.25 = 125
$mcp_currency_code = '840'; //ISO-4217 country currency number

## step 1) create transaction array ###
$txnArray=array('type'=>'ind_refund',
         'order_id'=>$orderid,
         'cust_id'=>'my cust id',
         'amount'=>$amount,
         'pan'=>$pan,
         'expdate'=>$expiry_date,
         'crypt_type'=>$crypt,
         'dynamic_descriptor'=>$dynamic_descriptor
         //,'mcp_amount' => $mcp_amount,
         //'mcp_currency_code' => $mcp_currency_code
           );

## step 2) create a transaction  object passing the array created in
## step 1.

$mpgTxn = new mpgTransaction($txnArray);

## step 3) create a mpgRequest object passing the transaction object created
## in step 2
$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

## step 4) create mpgHttpsPost object which does an https post ##
$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

## step 5) get an mpgResponse object ##
$mpgResponse=$mpgHttpPost->getMpgResponse();

## step 6) retrieve data using get methods

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
print("\nMCPAmount = " . $mpgResponse->getMCPAmount());
print("\nMCPCurrenyCode = " . $mpgResponse->getMCPCurrencyCode());

?>

