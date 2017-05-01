<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='moneris';
$api_token='hurgle';
//$status = 'false';

/************************* Transactional Variables ****************************/

$type='vscorpais';
$cust_id='CUST13343';
$order_id='ord-190916-11:18:28';
$txn_number='18519-0_11';

$ticket_number = "X9831083193";
$passenger_name = "John Williams";
$total_fee = "0.23";
$exchange_ticket_number = "1234567890001";
$exchange_ticket_amount = "0.24";
$travel_agency_code = "XH1";
$travel_agency_name="AIR FLY";
$internet_indicator = "Y";
$electronic_ticket_indicator = "Y";
$vat_ref_num="XH13983189";

$conjunction_ticket_number = array("1234567890100", "1234567890101");

$coupon_number = array("1", "3", "2");
$carrier_code1 = array("2R", "2R", "2R");
$flight_number = array("1234", "5678", "3456");
$service_class = array("A", "B", "C");
$orig_city_airport_code = array("YVR", "BOS", "NYC");
$stop_over_code = array("O", "O", "X");
$dest_city_airport_code = array("BOS", "NYC", "EWR");
$fare_basis_code = array("FClass", "Business", "Business");
$departure_date1 = array("030113", "030213", "030313");
$departure_time = array("1110", "1120", "1130");
$arrival_time = array("1210", "1220", "1230");

$control_id = array("1234567890300", "1234567890301");

//Create and set VsCorpai
$vsCorpai = new vsCorpai();
$vsCorpai->setTicketNumber($ticket_number);
$vsCorpai->setPassengerName1($passenger_name);
$vsCorpai->setTotalFee($total_fee);
$vsCorpai->setExchangeTicketNumber($exchange_ticket_number);
$vsCorpai->setExchangeTicketAmount($exchange_ticket_amount);
$vsCorpai->setTravelAgencyCode($travel_agency_code);
$vsCorpai->setTravelAgencyName($travel_agency_name);
$vsCorpai->setInternetIndicator($internet_indicator);
$vsCorpai->setElectronicTicketIndicator($electronic_ticket_indicator);
$vsCorpai->setVatRefNum($vat_ref_num);

//Create and set VsCorpais
//Every Corpas can only have up to 2 TripLegInfo
$vsTripLegInfo = array(new vsTripLegInfo(), new vsTripLegInfo());
$vsTripLegInfo[0]->setTripLegInfo($coupon_number[0], $carrier_code1[0], $flight_number[0], $service_class[0], $orig_city_airport_code[0], $stop_over_code[0], $dest_city_airport_code[0], $fare_basis_code[0], $departure_date1[0], $departure_time[0], $arrival_time[0]);
$vsTripLegInfo[0]->setTripLegInfo($coupon_number[1], $carrier_code1[1], $flight_number[1], $service_class[1], $orig_city_airport_code[1], $stop_over_code[1], $dest_city_airport_code[1], $fare_basis_code[1], $departure_date1[1], $departure_time[1], $arrival_time[1]);

$vsTripLegInfo[1]->setTripLegInfo($coupon_number[2], $carrier_code1[2], $flight_number[2], $service_class[2], $orig_city_airport_code[2], $stop_over_code[2], $dest_city_airport_code[2], $fare_basis_code[2], $departure_date1[2], $departure_time[2], $arrival_time[2]);

$vsCorpas = new vsCorpas();
$vsCorpas->setCorpas($conjunction_ticket_number[0], $vsTripLegInfo[0], $control_id[0]);
$vsCorpas->setCorpas($conjunction_ticket_number[1], $vsTripLegInfo[1], $control_id[1]);

//Create and set VsLevel23
$mpgVsLevel23 = new mpgVsLevel23();
$mpgVsLevel23->setVsCorpa($vsCorpai, $vsCorpas);

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
     		    'order_id'=>$order_id,
     		    'txn_number'=>$txn_number,
   		       );

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);
$mpgTxn->setLevel23Data($mpgVsLevel23);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

//Status check example
//$mpgHttpPost = new mpgHttpsPostStatus($store_id,$api_token,$status,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
//print("\nStatusCode = " . $mpgResponse->getStatusCode());
//print("\nStatusMessage = " . $mpgResponse->getStatusMessage());

?>

