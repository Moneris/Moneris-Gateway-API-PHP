<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************ Transaction Variables ******************************/

$data_key='ejJJON45q6M8maeptQyzJWc35';
$orderid='ord-'.date("dmy-G:i:s");
$amount='1.00';
$custid='cust';

/************************ Transaction Array **********************************/

//This transaction will be processed as us_ach_debit

$txnArray=array(type=>'res_purchase_ach',  
				data_key=>$data_key,
		        order_id=>$orderid,
		        cust_id=>$custid,
		        amount=>$amount
		         );

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

$shipping = array( first_name => 'Joe',
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

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);
$mpgTxn->setCustInfo($mpgCustInfo);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nCardType = " . $mpgResponse->getCardType());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());

//----------------- ResolveData ------------------------------

print("\n\nCust ID = " . $mpgResponse->getResDataCustId());
print("\nPhone = " . $mpgResponse->getResDataPhone());
print("\nEmail = " . $mpgResponse->getResDataEmail());
print("\nNote = " . $mpgResponse->getResDataNote());
print("\nSec = " . $mpgResponse->getResDataSec());
print("\nCust First Name = " . $mpgResponse->getResDataCustFirstName());
print("\nCust Last Name = " . $mpgResponse->getResDataCustLastName());
print("\nCust Address 1 = " . $mpgResponse->getResDataCustAddress1());
print("\nCust Address 2 = " . $mpgResponse->getResDataCustAddress2());
print("\nCust City = " . $mpgResponse->getResDataCustCity());
print("\nCust State = " . $mpgResponse->getResDataCustState());
print("\nCust Zip = " . $mpgResponse->getResDataCustZip());
print("\nRouting Num = " . $mpgResponse->getResDataRoutingNum());
print("\nMasked Account Num = " . $mpgResponse->getResDataMaskedAccountNum());
print("\nCheck Num = " . $mpgResponse->getResDataCheckNum());
print("\nAccount Type = " . $mpgResponse->getResDataAccountType());

?>
