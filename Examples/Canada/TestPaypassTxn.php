<?php
require "../../mpgClasses.php";

$store_id="moneris";
$api_token="hurgle";

$txnArray=array(
	'type'=>'paypass_txn',
	'xid'=>'13090510182901645',
	'amount'=>'1.00',
	'mp_request_token'=>'6034e4d0c451b323e50531ffa64f177795b38fc3',
	'MD'=>'merchant_data',
	'merchantUrl'=>'https://www.google.com',
	'accept'=>'*/*',
	'userAgent'=>'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3)'
);

$mpgTxn = new mpgTransaction($txnArray);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

$mpgResponse=$mpgHttpPost->getMpgResponse();

// Response Information
//
print("\nCardType = " . $mpgResponse->getCardType()."<br>");
print("\nTransAmount = " . $mpgResponse->getTransAmount()."<br>");
print("\nTxnNumber = " . $mpgResponse->getTxnNumber()."<br>");
print("\nReceiptId = " . $mpgResponse->getReceiptId()."<br>");
print("\nTransType = " . $mpgResponse->getTransType()."<br>");
print("\nReferenceNum = " . $mpgResponse->getReferenceNum()."<br>");
print("\nResponseCode = " . $mpgResponse->getResponseCode()."<br>");
print("\nISO = " . $mpgResponse->getISO()."<br>");
print("\nMessage = " . $mpgResponse->getMessage()."<br>");
print("\nIsVisaDebit = " . $mpgResponse->getIsVisaDebit()."<br>");
print("\nAuthCode = " . $mpgResponse->getAuthCode()."<br>");
print("\nComplete = " . $mpgResponse->getComplete()."<br>");
print("\nTransDate = " . $mpgResponse->getTransDate()."<br>");
print("\nTransTime = " . $mpgResponse->getTransTime()."<br>");
print("\nTicket = " . $mpgResponse->getTicket()."<br>");
print("\nTimedOut = " . $mpgResponse->getTimedOut()."<br>");
print("\nMpiMessage = " . $mpgResponse->getMpiMessage()."<br>");
print("\nMpiSuccess = " . $mpgResponse->getMpiSuccess()."<br>");
print("\nMpiParesVerified = " . $mpgResponse->getMpiParesVerified()."<br>");
print("\nMpiAcsUrl = " . $mpgResponse->getMpiAcsUrl()."<br>");
print("\nMpiPaReq = " . $mpgResponse->getMpiPaReq()."<br>");
print("\nMpiTermUrl = " . $mpgResponse->getMpiTermUrl()."<br>");
print("\nMpiMD = " . $mpgResponse->getMpiMD()."<br>");
print("\nMpiType = " . $mpgResponse->getMpiType()."<br>");

?>

