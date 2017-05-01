<?php
##
## This program takes 3 arguments from the command line:
## 1. Store id
## 2. api token
## 3. ecr number
##
## Example php -q TestOpenTotals.php store1 yesguy 66002163
##

require "../../mpgClasses.php";


$store_id='store5';
$api_token='yesguy';
$ecr_number='66014842';

## step 1) create transaction array ###
$txnArray=array('type'=>'opentotals',
         		'ecr_number'=>$ecr_number
           	   );

$mpgTxn = new mpgTransaction($txnArray);

## step 2) create mpgRequest object ###
$mpgReq= new mpgRequest($mpgTxn);
$mpgReq->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgReq->setTestMode(true); //false or comment out this line for production transactions

## step 3) create mpgHttpsPost object which does an https post ##
$mpgHttpPost=new mpgHttpsPost($store_id,$api_token,$mpgReq);

## step 4) get an mpgResponse object ##
$mpgResponse=$mpgHttpPost->getMpgResponse();


##step 5) get array of all credit cards
$creditCards = $mpgResponse->getCreditCards($ecr_number);

## step 6) loop through the array of credit cards and get information

if(is_array($creditCards) && !empty($creditCards))
{
	for($i=0; $i < count($creditCards); $i++)
	{
		print "\nCard Type = $creditCards[$i]";
		
		print "\nPurchase Count = " . $mpgResponse->getPurchaseCount($ecr_number,$creditCards[$i]);
		
		print "\nPurchase Amount = " . $mpgResponse->getPurchaseAmount($ecr_number,$creditCards[$i]);
		
		print "\nRefund Count = " . $mpgResponse->getRefundCount($ecr_number,$creditCards[$i]);
			
		print "\nRefund Amount = " . $mpgResponse->getRefundAmount($ecr_number,$creditCards[$i]);
		
		print "\nCorrection Count = " . $mpgResponse->getCorrectionCount($ecr_number,$creditCards[$i]);
		
		print "\nCorrection Amount = " . $mpgResponse->getCorrectionAmount($ecr_number,$creditCards[$i]);
	}
}
else 
{
	print("\nReceiptId = " . $mpgResponse->getReceiptId());
	print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
	print("\nResponseCode = " . $mpgResponse->getResponseCode());
	print("\nMessage = " . $mpgResponse->getMessage());
	print("\nComplete = " . $mpgResponse->getComplete());
	print("\nTransDate = " . $mpgResponse->getTransDate());
	print("\nTransTime = " . $mpgResponse->getTransTime());
	print("\nTicket = " . $mpgResponse->getTicket());
	print("\nTimedOut = " . $mpgResponse->getTimedOut());
}



?>

