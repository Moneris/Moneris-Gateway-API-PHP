<?php

$store_id ="monusqa002";
$api_token="qatoken";
$merchUrl="https://YOUR_MPI_RESPONSE_URL";

include("../../mpgClasses.php");

$xid =sprintf("%'920d", rand());
$pan = "4242424242424242";
$expiry = "1511";
$purchase_amount = "1.00";
	 
$HTTP_ACCEPT = getenv("HTTP_ACCEPT"); 
$HTTP_USER_AGENT = getenv("HTTP_USER_AGENT");

//these are form variable gotten after cardholder hits buy button on merchant site
//(purchase_amount,pan,expiry)

$txnArray=array(type=>'txn',
 				xid=>$xid,
 				amount=>$purchase_amount,
				 pan=>$pan,
				 expdate=>$expiry,
				 MD=>   "xid=" . $xid   //MD is merchant data that can be passed along
				   ."&amp;pan=" . $pan  
				   ."&amp;expiry=".$expiry
				   ."&amp;amount=" .$purchase_amount,
				 merchantUrl=>$merchUrl,
				 accept =>$HTTP_ACCEPT,
				 userAgent =>$HTTP_USER_AGENT
   );

$mpgTxn = new mpgTransaction($txnArray);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

$mpgResponse=$mpgHttpPost->getMpgResponse();


if($mpgResponse->getMpiMessage() == 'Y')
{
	$vbvInLineForm = $mpgResponse->getMpiInLineForm();
	print "$vbvInLineForm\n";
}
else {
	if ($mpgResponse->getMpiMessage() == 'U')   {
		// merchant assumes liability for charge back (usu. corporate cards)
		$crypt_type='7';
	}
	else {
		// merchant is not liable for chargeback (attempt was made)
		$crypt_type='6';
	}
	//Perform regular transaction with $crypt_type='7'
}
?>
