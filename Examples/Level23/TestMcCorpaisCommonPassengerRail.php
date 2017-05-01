<?php

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='moneris';
$api_token='hurgle';
//$status = 'false';

/************************* Transactional Variables ****************************/

$type='mccorpais';
$cust_id='CUST13343';
$order_id='ord-200916-17:03:22';
$txn_number='66011731632016264170326620-0_11';

//Common Data
$customer_code1_c ="CustomerCode123";
$additional_card_acceptor_data_c ="acad1";
$austin_tetra_number_c ="atn1";
$naics_code_c ="nc1";
$card_acceptor_type_c ="0000nnnn";
$card_acceptor_tax_id_c ="Moneristaxid1";
$corporation_vat_number_c ="cvn123";
$card_acceptor_reference_number_c ="carn1";
$freight_amount1_c ="1.23";
$duty_amount1_c ="2.34";
$destination_province_code_c ="ONT";
$destination_country_code_c ="CAN";
$ship_from_pos_code_c ="M8X 2X2";
$ship_to_pos_code_c ="_M1R 1R5";
$order_date_c ="141211";
$card_acceptor_vat_number_c ="cavn1";
$customer_vat_number_c ="customervn231";
$unique_invoice_number_c ="uin567";
$commodity_code_c ="paCCC1";
$authorized_contact_name_c ="John Walker";
$authorized_contact_phone_c ="416-734-1000";

//Common Tax Details
$tax_amount_c = array("1.19", "1.29");
$tax_rate_c = array("6.0", "7.0");
$tax_type_c = array("GST", "PST");
$tax_id_c = array("gst1298", "pst1298");
$tax_included_in_sales_c = array("Y", "N");

//General Passenger Ticket Information
$passenger_name1_i ="MCC Tester";
$ticket_number1_i ="1234567890001";
$travel_agency_name_i ="Moneris Travel";
$travel_agency_code_i ="MC322";
$issuing_carrier_i ="2R";
$customer_code1_i ="passengerabc";
$issue_date_i ="141210";
$total_fare_i ="129.45";
$travel_authorization_code_i ="sde-erdsz-452112";
$total_fee_i ="10.34";
$total_taxes_i ="11.56";
$restricted_ticket_indicator_i ="1";
$exchange_ticket_amount_i ="13.98";
$exchange_fee_amount_i ="1.78";
$iata_client_code_i ="icc2";

//Tax Details for passenger
$tax_amount_i = array("3.28");
$tax_rate_i = array("13.00");
$tax_type_i = array("HST");
$tax_id_i = array("hst1298");
$tax_included_in_sales_i = array("Y");

//Passenger Air Travel Details
$travel_date_s = array("150101", "150102");
$carrier_code1_s = array("3R", "4R");
$service_class_s = array("E", "B");
$orig_city_airport_code_s = array("Toron", "Montr");
$dest_city_airport_code_s = array("Montr", "Halif");
$stop_over_code_s = array("", "X");
$coupon_number_s = array("1", "2");
$fare_basis_code_s = array("FClass", "SClass");
$flight_number_s = array("56786", "54386");
$departure_time_s = array("1920", "1120");
$arrival_time_s = array("0620", "1620");
$conjunction_ticket_number1_s = array("123456789054367", null);
$exchange_ticket_number1_s = array("123456789067892", null);
$coupon_number1_s = array(null, null);
$fare_basis_code1_s = array(null, null);
$fare_s = array("1.69", null);
$fee_s = array("1.48", null);
$taxes_s = array("3.91", null);
$endorsement_restrictions_s = array("er6", null);

//Tax Details for Air Travel
$tax_amount_s = array("4.67", "7.43");
$tax_rate_s = array("5.0", "9.975");
$tax_type_s = array("GST", "QST");
$tax_id_s = array("gst1298", "qst1298");
$tax_included_in_sales_s = array("Y", "Y");

//Passenger Rail Details
$passenger_name1_r = array("Passenger Namer", "Passenger Namer1");
$ticket_number1_r = array("1234567890002", "1234567890003");
$travel_agency_code_r = array("TAC1", "TAC2");
$travel_agency_name_r = array("Daily Travel", "Daily Travel");
$travel_date_r = array("141223", "141222");
$sequence_number_r = array("001", "002");
$service_type_r = array("01", "02");
$service_nature_r = array("01", "02");
$service_amount_r = array("788.34", "56.34");
$full_vat_gross_amount_r = array("68.12", null);
$start_station_r = array("Vanco", "Calgr");
$destination_station_r = array("Calgr", "Winpg");
$number_of_adults_r = array("2", "4");
$number_of_children_r = array("3", "6");
$class_of_ticket_r = array("E", "B");
$procedure_id_r = array("RS-23IVTY", null);
$full_vat_tax_amount_r = array("4.49", null);
$half_vat_gross_amount_r = array("1.08", null);
$half_vat_tax_amount_r = array("0.87", null);
$traffic_code_r = array("665", null);
$sample_number_r = array("125", null);
$generic_code_r = array("66", null);
$generic_number_r = array("gn2", null);
$generic_other_code_r = array("13", null);
$generic_other_number_r = array("gon2", null);
$reduction_code_r = array("14", null);
$reduction_number_r = array("rn2", null);
$reduction_other_code_r = array("17", null);
$reduction_other_number_r = array("ron2", null);
$transportation_other_code_r = array("115", null);
$transportation_service_provider_r = array("tsp2", null);
$transportation_service_offered_r = array("tso2", null);

//Create and set Tax for McCorpac
$mcTax_c = new mcTax();
$mcTax_c->setTax($tax_amount_c[0], $tax_rate_c[0], $tax_type_c[0], $tax_id_c[0], $tax_included_in_sales_c[0]);
$mcTax_c->setTax($tax_amount_c[1], $tax_rate_c[1], $tax_type_c[1], $tax_id_c[1], $tax_included_in_sales_c[1]);

//Create and set McCorpac for common data - only set values that you know
$mcCorpac = new mcCorpac();
$mcCorpac->setCustomerCode1($customer_code1_c);
$mcCorpac->setAdditionalCardAcceptorData($additional_card_acceptor_data_c);
$mcCorpac->setAustinTetraNumber($austin_tetra_number_c);
$mcCorpac->setNaicsCode($naics_code_c);
$mcCorpac->setCardAcceptorType($card_acceptor_type_c);
$mcCorpac->setCardAcceptorTaxTd($card_acceptor_tax_id_c);
$mcCorpac->setCorporationVatNumber($corporation_vat_number_c);
$mcCorpac->setCardAcceptorReferenceNumber($card_acceptor_reference_number_c);
$mcCorpac->setFreightAmount1($freight_amount1_c);
$mcCorpac->setDutyAmount1($duty_amount1_c);
$mcCorpac->setDestinationProvinceCode($destination_province_code_c);
$mcCorpac->setDestinationCountryCode($destination_country_code_c);
$mcCorpac->setShipFromPosCode($ship_from_pos_code_c);
$mcCorpac->setShipToPosCode($ship_to_pos_code_c);
$mcCorpac->setOrderDate($order_date_c);
$mcCorpac->setCardAcceptorVatNumber($card_acceptor_vat_number_c);
$mcCorpac->setCustomerVatNumber($customer_vat_number_c);
$mcCorpac->setUniqueInvoiceNumber($unique_invoice_number_c);
$mcCorpac->setCommodityCode($commodity_code_c);
$mcCorpac->setAuthorizedContactName($authorized_contact_name_c);
$mcCorpac->setAuthorizedContactPhone($authorized_contact_phone_c);
$mcCorpac->setTax($mcTax_c);

//Create and set Tax for McCorpai
$mcTax_i = new mcTax();
$mcTax_i->setTax($tax_amount_i[0], $tax_rate_i[0], $tax_type_i[0], $tax_id_i[0], $tax_included_in_sales_i[0]);

//Create and set McCorpai
$mcCorpai = new mcCorpai();
$mcCorpai->setPassengerName1($passenger_name1_i);
$mcCorpai->setTicketNumber1($ticket_number1_i);
$mcCorpai->setTravelAgencyName($travel_agency_name_i);
$mcCorpai->setTravelAgencyCode($travel_agency_code_i);
$mcCorpai->setIssuingCarrier($issuing_carrier_i);
$mcCorpai->setCustomerCode1($customer_code1_i);
$mcCorpai->setIssueDate($issue_date_i);
$mcCorpai->setTotalFare($total_fare_i);
$mcCorpai->setTravelAuthorizationCode($travel_authorization_code_i);
$mcCorpai->setTotalFee($total_fee_i);
$mcCorpai->setTotalTaxes($total_taxes_i);
$mcCorpai->setRestrictedTicketIndicator($restricted_ticket_indicator_i);
$mcCorpai->setExchangeTicketAmount($exchange_ticket_amount_i);
$mcCorpai->setExchangeFeeAmount($exchange_fee_amount_i);
$mcCorpai->setIataClientCode($iata_client_code_i);
$mcCorpai->setTax($mcTax_i);

//Create and set Tax for McCorpas
$mcTax_s = array(new mcTax(), new mcTax());
$mcTax_s[0]->setTax($tax_amount_s[0], $tax_rate_s[0], $tax_type_s[0], $tax_id_s[0], $tax_included_in_sales_s[0]);
$mcTax_s[1]->setTax($tax_amount_s[1], $tax_rate_s[1], $tax_type_s[1], $tax_id_s[1], $tax_included_in_sales_s[1]);

//Create and set McCorpas for Air Travel Details only
$mcCorpas = new mcCorpas();
$mcCorpas->setMcCorpas($travel_date_s[0], $carrier_code1_s[0], $service_class_s[0], $orig_city_airport_code_s[0], $dest_city_airport_code_s[0], $stop_over_code_s[0],
					$conjunction_ticket_number1_s[0],$exchange_ticket_number1_s[0], $coupon_number1_s[0], $fare_basis_code1_s[0], $flight_number_s[0], $departure_time_s[0],
					$arrival_time_s[0], $fare_s[0], $fee_s[0], $taxes_s[0], $endorsement_restrictions_s[0], $mcTax_s[0]);
$mcCorpas->setMcCorpas($travel_date_s[1], $carrier_code1_s[1], $service_class_s[1], $orig_city_airport_code_s[1], $dest_city_airport_code_s[1], $stop_over_code_s[1],
					$conjunction_ticket_number1_s[1],$exchange_ticket_number1_s[1], $coupon_number1_s[1], $fare_basis_code1_s[1], $flight_number_s[1], $departure_time_s[1],
					$arrival_time_s[1], $fare_s[1], $fee_s[1], $taxes_s[1], $endorsement_restrictions_s[1], $mcTax_s[1]);

//Create and set McCorpar for Rail Travel Details only
$mcCorpar = new mcCorpar();
$mcCorpar->setMcCorpar($passenger_name1_r[0], $ticket_number1_r[0], $travel_agency_code_r[0], $travel_agency_name_r[0], $travel_date_r[0], $sequence_number_r[0], $procedure_id_r[0], $service_type_r[0],
					$service_nature_r[0], $service_amount_r[0], $full_vat_gross_amount_r[0], $full_vat_tax_amount_r[0], $half_vat_gross_amount_r[0], $half_vat_tax_amount_r[0], $traffic_code_r[0],
		 			$sample_number_r[0], $start_station_r[0], $destination_station_r[0], $generic_code_r[0], $generic_number_r[0], $generic_other_code_r[0], $generic_other_number_r[0], $reduction_code_r[0],
					$reduction_number_r[0], $reduction_other_code_r[0], $reduction_other_number_r[0], $transportation_other_code_r[0], $number_of_adults_r[0], $number_of_children_r[0],
					$class_of_ticket_r[0], $transportation_service_provider_r[0], $transportation_service_offered_r[0]);
$mcCorpar->setMcCorpar($passenger_name1_r[1], $ticket_number1_r[1], $travel_agency_code_r[1], $travel_agency_name_r[1], $travel_date_r[1], $sequence_number_r[1], $procedure_id_r[1], $service_type_r[1],
					$service_nature_r[1], $service_amount_r[1], $full_vat_gross_amount_r[1], $full_vat_tax_amount_r[1], $half_vat_gross_amount_r[1], $half_vat_tax_amount_r[1], $traffic_code_r[1],
					$sample_number_r[1], $start_station_r[1], $destination_station_r[1], $generic_code_r[1], $generic_number_r[1], $generic_other_code_r[1], $generic_other_number_r[1], $reduction_code_r[1],
					$reduction_number_r[1], $reduction_other_code_r[1], $reduction_other_number_r[1], $transportation_other_code_r[1], $number_of_adults_r[1], $number_of_children_r[1],
					$class_of_ticket_r[1], $transportation_service_provider_r[1], $transportation_service_offered_r[1]);

//Create and set McLevel23
$mpgMcLevel23 = new mpgMcLevel23();
$mpgMcLevel23->setMcCorpac($mcCorpac);
$mpgMcLevel23->setMcCorpai($mcCorpai);
$mpgMcLevel23->setMcCorpas($mcCorpas);
$mpgMcLevel23->setMcCorpar($mcCorpar);

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
     		    'order_id'=>$order_id,
     		    'txn_number'=>$txn_number,
   		       );

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);
$mpgTxn->setLevel23Data($mpgMcLevel23);

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

