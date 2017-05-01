<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='store5';
$api_token='yesguy';

/************************ Transaction Variables ******************************/

$data_key='ot-DYm9m3m00lCgN2b1Kk6mEb7np';
$amount='1.00';
$xid = sprintf("%'920d", rand());
$MD = $xid."mycardinfo".$amount;
$merchantUrl = "www.mystoreurl.com";
$accept = "true";
$userAgent = "Mozilla";
$expdate = "1712"; //For Temp Tokens only

/************************ Transaction Array **********************************/

$txnArray =array('type'=>'res_mpitxn',
				 'data_key'=>$data_key,
				 //'expdate'=>$expdate,
				 'amount'=>$amount,
				 'xid'=>$xid,
				 'MD'=>$MD,
				 'merchantUrl'=>$merchantUrl,
				 'accept'=>$accept,
				 'userAgent'=>$userAgent
				 );

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nMpiSuccess = " . $mpgResponse->getMpiSuccess());

if($mpgResponse->getMpiSuccess() == "true")
{
	print($mpgResponse->getMpiInLineForm());
}
else
{
	print("\nMpiMessage = " . $mpgResponse->getMpiMessage());
}

?>
