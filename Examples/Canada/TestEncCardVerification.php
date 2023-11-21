<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='store5';
$api_token='yesguy';

/************************ Transaction Variables ******************************/

$orderid='ord-'.date("dmy-G:i:s");
$enc_track2='02840085000000000416570F44857F2F7867342C66F7CDB57128A48F6E8DD8AD30AC1A6C727B5C400DC3AC8169BF2398B6C664FD3BE40431383131FFFF3141594047A00093031D03';
$device_type='idtech_bdk';

/************************** AVS Variables *****************************/

$avs_street_number = '201';
$avs_street_name = 'Michigan Ave';
$avs_zipcode = 'M1M1M1';

/************************** CVD Variables *****************************/

$cvd_indicator = '1';
$cvd_value = '198';

/********************** AVS Associative Array *************************/

$avsTemplate = array(
		     		 'avs_street_number'=>$avs_street_number,
                     'avs_street_name' =>$avs_street_name,
                     'avs_zipcode' => $avs_zipcode
                    );

/********************** CVD Associative Array *************************/

$cvdTemplate = array(
		     		 'cvd_indicator' => $cvd_indicator,
                     'cvd_value' => $cvd_value
                    );

/************************** AVS Object ********************************/

$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);

/************************** CVD Object ********************************/

$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);

/************************ Transaction Array **********************************/

$txnArray=array('type'=>'enc_card_verification',
         	'order_id'=>$orderid,
         	'cust_id'=>'cust',
         	'enc_track2'=>$enc_track2,
         	'device_type'=>$device_type,
			'crypt_type'=>'7'
           );


/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Set AVS and CVD *****************************/

$mpgTxn->setAvsInfo($mpgAvsInfo);
$mpgTxn->setCvdInfo($mpgCvdInfo);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);



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
