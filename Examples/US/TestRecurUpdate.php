<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************* Transactional Variables ****************************/

$type='recur_update';  
$order_id='ord-140515-10:57:40';

//The following fields can be updated for a CC, ACH or Pinless Debit transaction
$cust_id='MY CUST ID';
$recur_amount='1.00';
$add_num='20';
$total_num='999';
$hold = 'false';
$terminate = 'false';

//The pan & expdate can be updated for a Credit Card or Pinless Debit transaction
$pan='5454545454545454';
$expiry_date='1511';

//The AVS details can only be updated for a Credit Card transaction
$avs_street_number = '112';
$avs_street_name = 'lakeshore blvd';
$avs_zipcode = '123123';

//The p_account_number & presentation_type can only be updated for a Pinless Debit transaction
$p_account_number="Account a12345678 9876543";
$presentation_type = "X";

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
     		    'order_id'=>$order_id,
     		    'cust_id'=>$cust_id,
    		    'recur_amount'=>$recur_amount,
   			    'pan'=>$pan,
   			    'expdate'=>$expiry_date,
   			    'p_account_number'=>$p_account_number,
   			    'presentation_type'=>$presentation_type,
   			    'add_num_recurs' => $add_num,
   			    'total_num_recurs' => $total_num,
   			    'hold' => $hold,
   			    'terminate' => $terminate,
   			    'avs_street_number' => $avs_street_number,
   			    'avs_street_name' => $avs_street_name,
   			    'avs_zipcode' => $avs_zipcode
   		       );

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nRecurUpdateSuccess = " . $mpgResponse->getRecurUpdateSuccess());
print("\nNextRecurDate = " . $mpgResponse->getNextRecurDate());
print("\nRecurEndDate = " . $mpgResponse->getRecurEndDate());

?>

