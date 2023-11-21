<?php
##
## This program takes 3 arguments from the command line:
## 1. Store id
## 2. api token
## 3. order id
##
## Example php -q TestResPurchaseCC-CustInfo.php store3 yesguy unique_order_id 1.00 1
##

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='store5';
$api_token='yesguy';

/************************ Transaction Variables ******************************/

$data_key='t8RCndWBNFNt4Dx32CCnl2tlz';
$orderid='res-purch-'.date("dmy-G:i:s");
$amount='1.00';
$custid='cust';
$crypt_type='1';

/************************ Transaction Array **********************************/

$txnArray=array('type'=>'res_purchase_cc',
				'data_key'=>$data_key,
		        'order_id'=>$orderid,
		        'cust_id'=>$custid,
		        'amount'=>$amount,
		        'crypt_type'=>$crypt_type
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

/********************* Create Item Arrays and set them **********/

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
$mpgTxn->setCustInfo($mpgCustInfo);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
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
print("\nAVSResponse = " . $mpgResponse->getAvsResultCode());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());

//----------------- ResolveData ------------------------------

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
