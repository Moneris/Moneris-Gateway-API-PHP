<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monusqa002';
$api_token='qatoken';

/************************ Transaction Variables ******************************/

$type='res_add_ach';  
$cust_id='my cust id';
$phone = '416-555-5555';
$email = 'bob@smith.com';
$note = 'this is my note';

/************************ Transaction Array **********************************/

$txnArray=array('type'=>$type,
				'cust_id'=>$cust_id,
				'phone'=>$phone,
				'email'=>$email,
				'note'=>$note
          		);

/************************** ACH Info Variables *****************************/

$sec = 'web';			//only ppd|ccd|web are supported
$cust_first_name = 'Bob';
$cust_last_name = 'Smith';
$cust_address1 = '101 Main St';
$cust_address2 = '';
$cust_city = 'Washington';
$cust_state = 'WA';
$cust_zip = '62615';
$routing_num = '543211234';
$account_num = '23456';
$check_num = '100';
$account_type = 'savings';

/********************** ACH Info Associative Array *************************/

$achTemplate = array(
		     		 sec =>$sec,
		     		 cust_first_name => $cust_first_name,
                     cust_last_name => $cust_last_name,
                     cust_address1 => $cust_address1,
                     cust_address2 => $cust_address2,
                     cust_city => $cust_city,
                     cust_state => $cust_state,
                     cust_zip => $cust_zip,
                     routing_num => $routing_num,
                     account_num => $account_num,
                     check_num => $check_num,
                     account_type => $account_type
                    );

/************************** ACH Info Object ********************************/

$mpgAchInfo = new mpgAchInfo ($achTemplate);

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Set ACH Info *************************************/

$mpgTxn->setAchInfo($mpgAchInfo);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions
$mpgRequest->setTestMode(true);

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();


print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());

//----------------- ResolveData ------------------------------

print("\n\nCust ID = " . $mpgResponse->getResDataCustId());
print("\nPhone = " . $mpgResponse->getResDataPhone());
print("\nEmail = " . $mpgResponse->getResDataEmail());
print("\nNote = " . $mpgResponse->getResDataNote());
print("\nSec = " . $mpgResponse->getResDataSec());
print("\nCust First Name = " . $mpgResponse->getResDataCustFirstName());
print("\nCust Last Name = " . $mpgResponse->getResDataCustLastName());
print("\nCust Address 1 = " . $mpgResponse->getResDataCustAddress1());
print("\nCust Address 2 = " . $mpgResponse->getResDataCustAddress2());
print("\nCust City = " . $mpgResponse->getResDataCustCity());
print("\nCust State = " . $mpgResponse->getResDataCustState());
print("\nCust Zip = " . $mpgResponse->getResDataCustZip());
print("\nRouting Num = " . $mpgResponse->getResDataRoutingNum());
print("\nMasked Account Num = " . $mpgResponse->getResDataMaskedAccountNum());
print("\nCheck Num = " . $mpgResponse->getResDataCheckNum());
print("\nAccount Type = " . $mpgResponse->getResDataAccountType());

?>
