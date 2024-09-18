<?php

require "../../mpgClasses.php";

$store_id='monca02932';
$api_token="CG8kYzGgzVU5z23irgMx";

// TrId and TokenCryptogram are optional, refer documentation for more details.
$tr_id = '50189815682';
$token_cryptogram = 'APmbM/411e0uAAH+s6xMAAADFA==';

$txnArray=array('type'=>'card_verification',
         'order_id'=>'ord-'.date("dmy-G:i:s"),
         'cust_id'=>'my cust id',
         'pan'=>'4761349999000039',
         'expdate'=>'1512',
         'crypt_type'=>'7'
        //,'tr_id' => $tr_id
		//,'token_cryptogram' => $token_cryptogram   
        );

$mpgTxn = new mpgTransaction($txnArray);

/************************** AVS Variables *****************************/

$avs_street_number = '201';
$avs_street_name = 'Michigan Ave';
$avs_zipcode = 'M1M1M1';

/************************** CVD Variables *****************************/

$cvd_indicator = '1';
$cvd_value = '198';

/************************** Account Name Variables *****************************/

$first_name = 'FIRST';
$middle_name = 'MIDDLE';
$last_name = 'LAST';

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
/********************** AccountName Array *************************/

$accountNameTemplate = array(
    'first_name'=>$first_name,
    'middle_name' =>$middle_name,
    'last_name' => $last_name
);

/************************** AVS Object ********************************/

$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);

/************************** CVD Object ********************************/

$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);

/************************** AccountNameObject ********************************/

$mpgAccountNameInfo = new mpgAccountNameInfo ($accountNameTemplate);

/*********************** Credential on File ************************/
$cof = new CofInfo();
$cof->setPaymentIndicator("U");
$cof->setPaymentInformation("2");
$cof->setIssuerId("168451306048014");

/*********************** Account Name Verification  ************************/


$mpgTxn->setAvsInfo($mpgAvsInfo);
$mpgTxn->setCvdInfo($mpgCvdInfo);
$mpgTxn->setCofInfo($cof);
$mpgTxn->setAccountNameVerification($mpgAccountNameInfo);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

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
print("\nIssuerId = " . $mpgResponse->getIssuerId());
print("\nSourcePanLast4 = " . $mpgResponse->getSourcePanLast4());
print("\nAccountNameVerificationResult = " . $mpgResponse->getAccountNameResult());

?>

