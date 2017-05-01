<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************ Transaction Variable ******************************/

$ecr_number='64000003';

/************************ Transaction Array **********************************/

$txnArray=array(type=>'opentotals',  
         	ecr_number=>$ecr_number
           	);

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Request Object **********************************/

$mpgReq= new mpgRequest($mpgTxn);
$mpgReq->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgReq->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost=new mpgHttpsPost($store_id,$api_token,$mpgReq);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

/************************ Array of Cedit Cards  ****************************/

$creditCards = $mpgResponse->getCreditCards($ecr_number);

/************************ Loop through Array and Display ********************/

for($i=0; $i < count($creditCards); $i++)
 {
  print "\nCard Type = $creditCards[$i]";

  print "\nPurchase Count = "
        . $mpgResponse->getPurchaseCount($ecr_number,$creditCards[$i]);

  print "\nPurchase Amount = "
        . $mpgResponse->getPurchaseAmount($ecr_number,$creditCards[$i]);


  print "\nRefund Count = "
        . $mpgResponse->getRefundCount($ecr_number,$creditCards[$i]);


  print "\nRefund Amount = "
        . $mpgResponse->getRefundAmount($ecr_number,$creditCards[$i]);



  print "\nCorrection Count = "
        . $mpgResponse->getCorrectionCount($ecr_number,$creditCards[$i]);

  print "\nCorrection Amount = "
        . $mpgResponse->getCorrectionAmount($ecr_number,$creditCards[$i]);
 }

?>
