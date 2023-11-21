<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='store5';
$api_token='yesguy';

/************************ Transaction Variables ******************************/

$orderid="ord_".date("dmy-G:i:s");
$amount="1.00";
$enc_track2="02D901801F4F2800039B%*4924********3428^TESTCARD/MONERIS^*****************************************?*;4924********3428=********************?*105D7E8A2A9DE6DA04767BE4A7A489DAEE810982BEFF874BBF940211DFD85083922E37D4D90AB06819BD99BD1C96B1D93EE50FA63A2971C8734F84B6AB3A41CC4A334E2D16CB584C00308C47397221FBD4C1EB3719B68A095421426F7DD6B1B8A4CE9F7737B662CC961AEB82371E6F096C1962CD290BCC4C3CD06F7A188D84EA0260832F743E485C0D369929D4840FFAFA12BC3938C4A4DE4FA3FA837D1C2190FFFF3141594047A0009532D603";
$pos_code="00";
$device_type='idtech_bdk';

/************************ Transaction Array **********************************/

$txnArray=array('type'=>'enc_track2_preauth',
         'order_id'=>$orderid,
         'cust_id'=>'cust',
         'amount'=>$amount,
         'enc_track2'=>$enc_track2,
         'pos_code'=>$pos_code,
         'device_type'=>$device_type,
		 'dynamic_descriptor'=>'12345'
           );

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();


print("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nMaskedPan = " . $mpgResponse->getMaskedPan());

?>
