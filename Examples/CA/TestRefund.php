<?php

##
## This program takes 4 arguments from the command line:
## 1. Store id
## 2. api token
## 3. order id
## 4. trans number
##
## Example php -q TestRefund.php store1 yesguy my_order_id 45109-89-0
##

require "../../mpgClasses.php";

$store_id='store5';
$api_token='yesguy';
$orderid='ord-150816-11:56:58';
$txnnumber='117743-0_10';
$amount = '1.00';
$crypt_type = '7';

$dynamic_descriptor='123';

//Optional - Set for Multi-Currency only
//$amount must be 0.00 when using multi-currency
$mcp_amount = '100'; //penny value amount 1.25 = 125
$mcp_currency_code = '840'; //ISO-4217 country currency number

## step 1) create transaction array ###
$txnArray=array('type'=>'refund',
         'txn_number'=>$txnnumber,
         'order_id'=>$orderid,
         'amount'=>$amount,
         'crypt_type'=>$crypt_type,
         'cust_id'=> 'Customer ID',
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

print ("\nCardType = " . $mpgResponse->getCardType());
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

