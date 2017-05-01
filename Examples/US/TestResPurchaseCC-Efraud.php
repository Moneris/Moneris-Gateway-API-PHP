<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************ Transaction Variables ******************************/

$data_key='FjhVlt4020HAVSaOmnaaPACpJ';
$orderid='ord-'.date("dmy-G:i:s");
$amount='10.30';
$custid='cust';
$crypt_type='1';
$commcard_invoice='Invoice 123';
$commcard_tax_amount='1.00';

/************************** CVD Variables *****************************/

$cvd_indicator = '1';
$cvd_value = '198';

/********************** CVD Associative Array *************************/

$cvdTemplate = array(
		     		 cvd_indicator => $cvd_indicator,
                     cvd_value => $cvd_value
                    );

$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);

/************************** AVS Variables *****************************/

//The AVS portion is optional if AVS details are already stored in this profile
//If AVS details are resent in Purchase transaction, they will replace stored details

$avs_street_number = '';
$avs_street_name = 'bloor st';
$avs_zipcode = '111111';

/********************** AVS Associative Array *************************/

$avsTemplate = array(
					'avs_street_number' => $avs_street_number,
					'avs_street_name' => $avs_street_name,
					'avs_zipcode' => $avs_zipcode
					);

$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);

/************************ Transaction Array **********************************/

$txnArray=array(type=>'res_purchase_cc',  
				data_key=>$data_key,
		        order_id=>$orderid,
		        cust_id=>$custid,
		        amount=>$amount,
		        crypt_type=>$crypt_type,
		        commcard_invoice=>$commcard_invoice,
		        commcard_tax_amount=>$commcard_tax_amount
		         );

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);
$mpgTxn->setCvdInfo($mpgCvdInfo);
$mpgTxn->setAvsInfo($mpgAvsInfo);

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
print("\nAVSResponse = " . $mpgResponse->getAvsResultCode());
print("\nCVDResponse = " . $mpgResponse->getCvdResultCode());
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
