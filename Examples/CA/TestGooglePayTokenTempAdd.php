<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='store5';
$api_token='yesguy';

/************************* Transactional Variables ****************************/

$order_id='ord-'.date("dmy-G:i:s");
$cust_id='nqa cust id';
$amount='1.00';
$network = "MASTERCARD";
$signature = "MEUCIQCrYF49SSZ65mZhxRMd8t75gLRuBmiXgaRkab1ebufy9QIgOBEVnsRIEYOaBQUBrfbWG1yaCsvYRnpu+jzSB3YvSrg=";
$protocol_version = "ECv1";
$signed_message = '{&quot;encryptedMessage&quot;:&quot;Nc10DcPstj0pDveawLVmYq0A69lyR3GrdoMzCDRP8GKoUmApXHJ+a4hr9z5VFfHBPC42nkdL84Ktq72XsQrRlcGuX+7gHJMWqoTIjn3n4dGkNoRR/y7wNiFIPuGrnkpJqXCI2y908/QzBYkptlZA2KORjmHIDW2WdmJL7tVYYZIaftuJMszlNS5s02x9420xxYnpqPL9jetgOrcfe5ePfrGbZFj8uNiZKku8smkcnahJpvGX+WQY5LDbsD/jB1Xb2Cq0NPJ23Vz+uJR4lCQa6fnSRWQnjF5H7OrOO2kduHqxdOrybEGAFuv4lgln6e2a1fsa8y7xtMrtKbf1SIna644SrIRt9IFnrdQtOSijzcuOB0nV9ZPtDQCgVJOYbljmbwdmXoK5rs6Fd986piBU3ja5X9BwHjjCklXI6ipWwOJcbxcyn4SVKzXXCPs3jpLiT1B0XeF+&quot;,&quot;ephemeralPublicKey&quot;:&quot;BI98Y9Wpw3s/dy7Q+tJ1UHU3eYcavmbrzlEtlxtPCAWaoK/Tw+4uGY+87dh3YqEOhfh06fFIo0itpQMTOK9dUmM\u003d&quot;,&quot;tag&quot;:&quot;2++nMKEeXseTX80XSEkZUHCD/FV6qaZa2w+5xmGWhOY\u003d&quot;}';
$dynamic_descriptor = "nqa-dd";


/*********************** Transactional Associative Array **********************/

$googlePayTokenTempAdd = new GooglePayTokenTempAdd();
$googlePayTokenTempAdd->setPaymentToken($signature, $protocol_version, $signed_message);

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($googlePayTokenTempAdd);

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

print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nIsVisaDebit = " . $mpgResponse->getIsVisaDebit());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nMasked Pan = " . $mpgResponse->getResDataMaskedPan());
print("\nExp Date = " . $mpgResponse->getResDataExpDate());
print("\nPayment Type = " . $mpgResponse->getPaymentType());
print("\nGooglepayPaymentMethod = " . $mpgResponse->getGooglepayPaymentMethod());

?>

