<?php

##
## Example php -q TestPurchase.php store1
##

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='intuit_sped';
$api_token='spedguy';

/************************* Transactional Variables ****************************/

$order_id='ord-'.date("dmy-G:i:s");
$cust_id='nqa cust id';
$amount='1.00';
$network = "MASTERCARD";
$signature = "MEQCIHqTNrWj16DTwJUKi/AHbcp12n7hWgLYpcZeIL4YE6v2AiBabiGJla15MuHs0irVOXX2/jW/YxID1aUmaHSBRddy5Q==";
$protocol_version = "ECv1";
$signed_message =  "{\"encryptedMessage\":\"UC+YsDq+MDGAbMS+8liNngnEnHHsh/LueiqZlkCaVz3fYuWqL3i70Xk72yEg+Riu6erDA49nth7F/VkAH8Lun6ZvKC+r7AHLc7kScIAhRJf/muPYas9Zwr5sHV7WdmKLNoPi5Ni5YYGH8jXry7byJCXU0fbVYvFcR1zqrVL+IgdIRxu0hjpyP2NVhv9lNCaCP4Qk7bQf5jN0XhBYVzHwWc42knGkV8oHLBQ199IXl+ARjizax1zcZsa10+dPySBYQD2rcu6THp/aIRfHbW5feux/ifn4sbv4Xq3SNHJluo2L2MfKEiPLwFV6NyyMLRqXNIoLLB/5l8IjEqBgSkEXh4oBbyZcKsWw9udnzwf/K3Mat7lfu2xSPB9eLRJvwOtg3pgkYf8o+gZTW4UEbuBJwOtDDVtmZQeLVOFGTGZSX+tSn5Ua6unWEgwXkH9XTYXYtHlgGjOb\",\"ephemeralPublicKey\":\"BPoyXz2b9VdlFfcnsJ0pbu57wxNIrTxkVHDKstNmxTXKu0rE+5S6BcT9m5zPU8WR/CZ/H+lbXgAp9USPL3ZdRMY\\u003d\",\"tag\":\"dN9RJUDMztNgUkvPE/ys8ZLNpCUkpKi+ZLB6SoGx6Ww\\u003d\"}";
$dynamic_descriptor = "nqa-dd";


/*********************** Transactional Associative Array **********************/

$googlePayPurchase = new GooglePayPurchase();
$googlePayPurchase->setOrderId($order_id);
$googlePayPurchase->setCustId($cust_id);
$googlePayPurchase->setAmount($amount);
$googlePayPurchase->setNetwork($network);
$googlePayPurchase->setPaymentToken($signature, $protocol_version, $signed_message);
$googlePayPurchase->setDynamicDescriptor($dynamic_descriptor);

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($googlePayPurchase);


/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/***************************** HTTPS Post Object *****************************/

/* Status Check Example
$mpgHttpPost  =new mpgHttpsPostStatus($store_id,$api_token,$status_check,$mpgRequest);
*/

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

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
print("\nStatusCode = " . $mpgResponse->getStatusCode());
print("\nStatusMessage = " . $mpgResponse->getStatusMessage());
print("\nHostId = " . $mpgResponse->getHostId());
print("\nIssuerId = " . $mpgResponse->getIssuerId());

?>

