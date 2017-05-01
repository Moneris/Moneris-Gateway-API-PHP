<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='store5';
$api_token='yesguy';

/************************ Transaction Variables ******************************/

$orderid='ord-'.date("dmy-G:i:s");
$amount='1.00';
$enc_track2='02840085000000000416D705CCD4BAC5929D8D1EBF0644C234FBC65476C1D6C9E94B9BED3E4D1A791C3F4FC61C1800486A8A6B6CCAA00431353131FFFF3141594047A000960D5D03';
$device_type='idtech_bdk';


/************************ Transaction Array **********************************/

$txnArray=array('type'=>'enc_purchase',  
         'order_id'=>$orderid,
         'cust_id'=>'cust',
         'amount'=>$amount,
         'enc_track2'=>$enc_track2,
         'device_type'=>$device_type,
         'crypt_type'=>'7', 
         'dynamic_descriptor'=>'12345'
           );

/************************ CustInfo Object **********************************/

$mpgCustInfo = new mpgCustInfo();


/********************* Set E-mail and Instructions **************/

$email ='Joe@widgets.com';
$mpgCustInfo->setEmail($email);

$instructions ="Make it fast";
$mpgCustInfo->setInstructions($instructions);

/********************* Create Billing Array and set it **********/

$billing = array( 'first_name' => 'Joe',
		'last_name' => 'Thompson',
		'company_name' => 'Widget Company Inc.',
		'address' => '111 Bolts Ave.',
		'city' => 'Toronto',
		'province' => 'Ontario',
		'postal_code' => 'M8T 1T8',
		'country' => 'Canada',
		'phone_number' => '416-555-5555',
		'fax' => '416-555-5555',
		'tax1' => '123.45',
		'tax2' => '12.34',
		'tax3' => '15.45',
		'shipping_cost' => '456.23');


$mpgCustInfo->setBilling($billing);

/********************* Create Shipping Array and set it **********/

$shipping = array( 'first_name' => 'Joe',
		'last_name' => 'Thompson',
		'company_name' => 'Widget Company Inc.',
		'address' => '111 Bolts Ave.',
		'city' => 'Toronto',
		'province' => 'Ontario',
		'postal_code' => 'M8T 1T8',
		'country' => 'Canada',
		'phone_number' => '416-555-5555',
		'fax' => '416-555-5555',
		'tax1' => '123.45',
		'tax2' => '12.34',
		'tax3' => '15.45',
		'shipping_cost' => '456.23');

$mpgCustInfo->setShipping($shipping);


/********************* Create Item Arraya and set them **********/

$item1 = array ('name'=>'item 1 name',
		'quantity'=>'53',
		'product_code'=>'item 1 product code',
		'extended_amount'=>'1.00');

$mpgCustInfo->setItems($item1);


$item2 = array('name'=>'item 2 name',
		'quantity'=>'53',
		'product_code'=>'item 2 product code',
		'extended_amount'=>'1.00');

$mpgCustInfo->setItems($item2);


/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Set CustInfo Object *****************************/

$mpgTxn->setCustInfo($mpgCustInfo);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

//Status check example
//$mpgHttpPost = new mpgHttpsPostStatus($store_id,$api_token,$status,$mpgRequest);

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
print("\nCardLevelResult = " . $mpgResponse->getCardLevelResult());
print("\nMaskedPan = " . $mpgResponse->getMaskedPan());

?>
