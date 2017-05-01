<?php
##
## This program takes 3 arguments from the command line:
## 1. Store id
## 2. api token
## 3. order id
##
## Example php -q TestPurchase-Efraud.php store1 45728773 45109
##

require "../../mpgClasses.php";


/************************ Request Variables ***************************/

$store_id='store5';
$api_token='yesguy';

/********************* Transactional Variables ************************/

$type='purchase';
$order_id='ord-'.date("dmy-G:i:s");
$cust_id='my cust id';
$amount='10.30';
$pan='4242424242424242';
$expiry_date='0812';		//December 2008
$crypt='7';

/************************** AVS Variables *****************************/

$avs_street_number = '201';
$avs_street_name = 'Michigan Ave';
$avs_zipcode = 'M1M1M1';
$avs_email = 'test@host.com';
$avs_hostname = 'www.testhost.com';
$avs_browser = 'Mozilla';
$avs_shiptocountry = 'Canada';
$avs_merchprodsku = '123456';
$avs_custip = '192.168.0.1';
$avs_custphone = '5556667777';

/************************** CVD Variables *****************************/

$cvd_indicator = '1';
$cvd_value = '198';

/********************** AVS Associative Array *************************/

$avsTemplate = array(
					 'avs_street_number'=>$avs_street_number,
                     'avs_street_name' =>$avs_street_name,
                     'avs_zipcode' => $avs_zipcode,
                     'avs_hostname'=>$avs_hostname,
					 'avs_browser' =>$avs_browser,
					 'avs_shiptocountry' => $avs_shiptocountry,
					 'avs_merchprodsku' => $avs_merchprodsku,
					 'avs_custip'=>$avs_custip,
					 'avs_custphone' => $avs_custphone
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

/***************** Transactional Associative Array ********************/

$txnArray=array(
				'type'=>$type,
       			'order_id'=>$order_id,
       			'cust_id'=>$cust_id,
       			'amount'=>$amount,
       			'pan'=>$pan,
       			'expdate'=>$expiry_date,
       			'crypt_type'=>$crypt
          		);

/********************** Transaction Object ****************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Set AVS and CVD *****************************/

$mpgTxn->setAvsInfo($mpgAvsInfo);
$mpgTxn->setCvdInfo($mpgCvdInfo);

/************************ Request Object ******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/*********************** HTTPS Post Object ****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/*************************** Response *********************************/

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
print("\nAVSResponse = " . $mpgResponse->getAvsResultCode());
print("\nCVDResponse = " . $mpgResponse->getCvdResultCode());
print("\nITDResponse = " . $mpgResponse->getITDResponse());

?>

