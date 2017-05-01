<?php

/* eSELECTplus US Convenience Fee Account Required this transaction*/

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monusqa138';
$api_token='qatoken';

/************************ Transaction Variables ******************************/

$orderid='ord-'.date("dmy-G:i:s");
$amount='10.00';
$custid = 'my cust id';

/************************ Transaction Array **********************************/

$txnArray=array(type=>'ach_debit',  
         		order_id=>$orderid,
         		cust_id=>$custid,
         		amount=>$amount
          		);

/************************** ACH Info Variables *****************************/

$sec = 'ppd';
$cust_first_name = 'Bob';
$cust_last_name = 'Smith';
$cust_address1 = '101 Main St';
$cust_address2 = 'Apt 102';
$cust_city = 'Chicago';
$cust_state = 'IL';
$cust_zip = '123456';
$routing_num = '490000018';
$account_num = '23456';
$check_num = '100';
$account_type = 'savings';

/********************** ACH Info Associative Array *************************/

$achTemplate = array(
		     		 sec =>$sec,
                     cust_first_name => $cust_first_name,
                     cust_last_name => $cust_last_name,
                     cust_address1 => $cust_address1,
                     cust_address2 => $cust_address2,
                     cust_city => $cust_city,
                     cust_state => $cust_state,
                     cust_zip => $cust_zip,
                     routing_num => $routing_num,
                     account_num => $account_num,
                     check_num => $check_num,
                     account_type => $account_type
                    );

/************************** ACH Info Object ********************************/

$mpgAchInfo = new mpgAchInfo ($achTemplate);

/************************ CustInfo Object **********************************/

$mpgCustInfo = new mpgCustInfo();

/********************* Set E-mail and Instructions **************/

$email ='Joe@widgets.com';
$mpgCustInfo->setEmail($email);

$instructions ="Make it fast";
$mpgCustInfo->setInstructions($instructions);

/********************* Create Billing Array and set it **********/

$billing = array( first_name => 'Joe',
		last_name => 'Thompson',
		company_name => 'Widget Company Inc.',
		address => '111 Bolts Ave.',
		city => 'Toronto',
		province => 'Ontario',
		postal_code => 'M8T 1T8',
		country => 'Canada',
		phone_number => '416-555-5555',
		fax => '416-555-5555',
		tax1 => '123.45',
		tax2 => '12.34',
		tax3 => '15.45',
		shipping_cost => '456.23');

$mpgCustInfo->setBilling($billing);

/********************* Create Shipping Array and set it **********/

$shipping = array(first_name => 'Joe',
		last_name => 'Thompson',
		company_name => 'Widget Company Inc.',
		address => '111 Bolts Ave.',
		city => 'Toronto',
		province => 'Ontario',
		postal_code => 'M8T 1T8',
		country => 'Canada',
		phone_number => '416-555-5555',
		fax => '416-555-5555');

$mpgCustInfo->setShipping($shipping);

/********************* Create Item Arrays and set them **********/

$item1 = array (name=>'item 1 name',
		quantity=>'53',
		product_code=>'item 1 product code',
		extended_amount=>'1.00');

$mpgCustInfo->setItems($item1);

$item2 = array(name=>'item 2 name',
		quantity=>'53',
		product_code=>'item 2 product code',
		extended_amount=>'1.00');

$mpgCustInfo->setItems($item2);

/********************** ConvFee Associative Array *************************/

$convFeeTemplate = array(
						 convenience_fee=>'2.00'
						);

/************************** ConvFee Object ********************************/

$mpgConvFee = new mpgConvFeeInfo($convFeeTemplate);

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Set ACH, Cust and ConvFee Info *************************************/

$mpgTxn->setAchInfo($mpgAchInfo);
$mpgTxn->setCustInfo($mpgCustInfo);
$mpgTxn->setConvFeeInfo($mpgConvFee);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();


print("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nCfSuccess = " . $mpgResponse->getCfSuccess());
print("\nCfStatus = " . $mpgResponse->getCfStatus());
print("\nFeeAmount = " . $mpgResponse->getFeeAmount());
print("\nFeeRate = " . $mpgResponse->getFeeRate());
print("\nFeeType = " . $mpgResponse->getFeeType());

?>
