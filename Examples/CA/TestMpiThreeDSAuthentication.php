<?php

require "../../mpgClasses.php";

/******************************* Request Variables ********************************/
$store_id = "moneris";
$api_token = "hurgle";

$mpiThreeDSAuthentication = new MpiThreeDSAuthentication();
$mpiThreeDSAuthentication->setOrderId("ord-110920-10:36:43");	//must be the same one that was used in MpiCardLookup call
$mpiThreeDSAuthentication->setCardholderName("Moneris Test");
$mpiThreeDSAuthentication->setPan("340087427838525");
//$mpiThreeDSAuthentication->setDataKey("8OOXGiwxgvfbZngigVFeld9d2"); //Optional - For Moneris Vault and Hosted Tokenization tokens in place of setPan
$mpiThreeDSAuthentication->setExpdate("2310");
$mpiThreeDSAuthentication->setAmount("1.00");
$mpiThreeDSAuthentication->setThreeDSCompletionInd("Y"); //(Y|N|U) indicates whether 3ds method MpiCardLookup was successfully completed
$mpiThreeDSAuthentication->setRequestType("01"); //(01=payment|02=recur)
$mpiThreeDSAuthentication->setPurchaseDate("20200911035249"); //(YYYYMMDDHHMMSS)
$mpiThreeDSAuthentication->setNotificationURL("https://yournotificationurl.com"); //(Website where response from RRes or CRes after challenge will go)
$mpiThreeDSAuthentication->setChallengeWindowSize("03"); //(01 =     250 x 400, 02 = 390 x 400, 03 = 500 x 600, 04 = 600 x 400, 05 = Full screen)

$mpiThreeDSAuthentication->setBrowserUserAgent("Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36\\");
$mpiThreeDSAuthentication->setBrowserJavaEnabled("true"); //(true|false)
$mpiThreeDSAuthentication->setBrowserScreenHeight("1000"); //(pixel height of cardholder screen)
$mpiThreeDSAuthentication->setBrowserScreenWidth("1920"); //(pixel width of cardholder screen)
$mpiThreeDSAuthentication->setBrowserLanguage("en-GB"); //(defined by IETF BCP47)
$mpiThreeDSAuthentication->setBrowserIP("127.0.0.1"); //(defined by IETF BCP47)

//Optional Methods
$mpiThreeDSAuthentication->setBillAddress1("3300 Bloor St W");
$mpiThreeDSAuthentication->setBillProvince("ON");
$mpiThreeDSAuthentication->setBillCity("Toronto");
$mpiThreeDSAuthentication->setBillPostalCode("M8X 2X2");
$mpiThreeDSAuthentication->setBillCountry("124");

$mpiThreeDSAuthentication->setShipAddress1("3300 Bloor St W");
$mpiThreeDSAuthentication->setShipProvince("ON");
$mpiThreeDSAuthentication->setShipCity("Toronto");
$mpiThreeDSAuthentication->setShipPostalCode("M8X 2X2");
$mpiThreeDSAuthentication->setShipCountry("124");

$mpiThreeDSAuthentication->setEmail("test@email.com");
$mpiThreeDSAuthentication->setRequestChallenge("Y"); //(Y|N Requesting challenge regardless of outcome)

$mpiThreeDSAuthentication->setMessageCategory("01");
$mpiThreeDSAuthentication->setDeviceChannel("02");
$mpiThreeDSAuthentication->setDecoupledRequestIndicator("Y");
$mpiThreeDSAuthentication->setDecoupledRequestMaxTime("00010");
$mpiThreeDSAuthentication->setDecoupledRequestAsyncUrl("https://yourasyncnotificationurl.com");
$mpiThreeDSAuthentication->setRiIndicator("03");
// $mpiThreeDSAuthentication->setRecurringFrequency("031");
// $mpiThreeDSAuthentication->setRecurringExpiry("20251230");

// $paiTemplate = array(
//     'prior_request_auth_data'=>"d7c1ee99-9478-44a6-b1f2-391e29c6b340",
//     'prior_request_ref'=>"d7c1ee99-9478-44a6-b1f2-391e29c6b340",
//     'prior_request_auth_method'=>"01",
//     'prior_request_auth_timestamp'=>"201710282113"
// );
// $mpiThreeDSAuthentication->setPriorAuthenticationInfo( $paiTemplate );
//
// $workPhoneTemplate = array(
//     'cc'=>'1',
//     'subscriber'=>'1111111111'
// );
// $mpiThreeDSAuthentication->setWorkPhone( $workPhoneTemplate );
//
// $mobilePhoneTemplate = array(
//     'cc'=>'2',
//     'subscriber'=>'2222222222'
// );
// $mpiThreeDSAuthentication->setMobilePhone( $mobilePhoneTemplate );
//
// $homePhoneTemplate = array(
//     'cc'=>'3',
//     'subscriber'=>'3333333333'
// );
// $mpiThreeDSAuthentication->setHomePhone( $homePhoneTemplate );


/****************************** Transaction Object *******************************/
$mpgTxn = new mpgTransaction($mpiThreeDSAuthentication);

/******************************* Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

print_r($mpgRequest);
/****************************** HTTPS Post Object *******************************/
$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************************* Response *********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nMessage = " . $mpgResponse->getMessage());

print("\nMessageType = " . $mpgResponse->getMpiMessageType());
print("\nTransStatus = " . $mpgResponse->getMpiTransStatus());
print("\nTransStatusReason = " . $mpgResponse->getMpiTransStatusReason());
print("\nChallengeURL = " . $mpgResponse->getMpiChallengeURL());
print("\nChallengeData = " . $mpgResponse->getMpiChallengeData());
print("\nThreeDSServerTransId = " . $mpgResponse->getMpiThreeDSServerTransId());
print("\nThreeDSVersion = " . $mpgResponse->getThreeDSVersion());
print("\nThreeDSDSTransID = " . $mpgResponse->getMpiDSTransId());
print("\nThreeDSAcsTransID = " . $mpgResponse->getMpiThreeDSAcsTransID());
print("\nThreeDSAuthTimeStamp = " . $mpgResponse->getMpiThreeDSAuthTimeStamp());
print("\nAuthenticationType = " . $mpgResponse->getMpiAuthenticationType());
print("\nCardholderInfo = " . $mpgResponse->getMpiCardholderInfo());

//In Frictionless flow, you may receive TransStatus as "Y", 
//in which case you can then proceed directly to Cavv Purchase/Preauth with values below'
if($mpgResponse->getMpiTransStatus() == "Y")
{
 print("\nCavv = " . $mpgResponse->getMpiCavv());
 print("\nECI = " . $mpgResponse->getMpiEci());
}
?>
