<?php

require "../../mpgClasses.php";

$store_id = "monca03650";
$api_token = "7Yw0MPTlhjBRcZiE6837";
$processing_country_code = "CA";

$pbb_merchant_id = "CAM0000001";
$required_contact_fields = array("NAME", "PHONE", "EMAIL", "SHIPPING_ADDRESS");
$supported_payment_methods = array("MASTERCARD_DEBIT", "VISA_DEBIT", "AMEX_CREDIT", "VISA_CREDIT", "MASTERCARD_CREDIT");

//payment
$currency = "CAD";
$amount = "72.00";
$sub_total = "2.00";
$tax = "0.00";
$shipping_cost = "0.00";
$additional_fee1 = array("name" => "java", "amount" => 50.00);
$additional_fee2 = array("name" => "Dotnet", "amount" => 20.00);
$additional_fees = array();
array_push($additional_fees, $additional_fee1, $additional_fee2);

//order
$shipment_type = "NONE";
$merchant_order_ref = "1695401825L1CLwAJ98p7JwAc";
$placement_mode = "STANDARD";

$recurringInfo1 = new RecurringInfo();
$recurringInfo1->setAmountCapped("100");
$recurringInfo1->setAmountVariance("2.5");
$recurringInfo1->setDayOfMonth("1");
$recurringInfo1->setDayOfWeek("MON");
$recurringInfo1->setStartDate("2023-10-01T00:00:00.000Z");
$recurringInfo1->setEndDate("2025-12-31T23:59:59.000Z");
$recurringInfo1->setFrequencyRate("50");
$recurringInfo1->setFrequencyType("DAY");
$recurringInfo1->setWeekOfMonth("FIRST");

$recurringInfo2 = new RecurringInfo();
$recurringInfo2->setAmountCapped("200");
$recurringInfo2->setAmountVariance("5.0");
$recurringInfo2->setDayOfMonth("15");
$recurringInfo2->setDayOfWeek("FRI");



$item1 = array(
    "item_payment_type" => "SINGLE",
    "amount" => 50.00,
    "qty" => 1,
    "description" => "Item1",
    "item_ref" => "Testing",
    "item_name" => "ItemName1",
    "recurring_info" => $recurringInfo1
);


$item2 = array(
    "item_payment_type" => "SINGLE",
    "amount" => 20.00,
    "qty" => 1,
    "description" => "Item2",
    "item_ref" => "Testing2",
    "item_name" => "ItemName2",
    "recurring_info" => $recurringInfo2
);
$items = array();
array_push($items, $item1, $item2);

$pbbCreateConsent = new PbbCreateConsent();
$pbbCreateConsent->setPbbMerchantId($pbb_merchant_id);
$pbbCreateConsent->setRequiredContactFields($required_contact_fields);
$pbbCreateConsent->setSupportedPaymentMethods($supported_payment_methods);
$pbbCreateConsent->setPbbPayment($currency, $amount, $sub_total, $tax, $shipping_cost, $additional_fees);
$pbbCreateConsent->setPbbOrder($shipment_type, $merchant_order_ref, $placement_mode, $items);

$mpgTxn = new mpgTransaction($pbbCreateConsent);
//$mpgTxn->setPbbPayment($payment);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode($processing_country_code);
$mpgRequest->setTestMode(true);

/***************************** HTTPS Post Object *****************************/
try {
    $mpgHttpPost = new mpgHttpsPost($store_id, $api_token, $mpgRequest);


    $mpgResponse = $mpgHttpPost->getMpgResponse();
    if(isset( $mpgResponse->getMpgResponseData()["ConsentId"])) {
        print("\nConsentId = " . $mpgResponse->getMpgResponseData()["ConsentId"]);
        print("\nRequestId = " . $mpgResponse->getMpgResponseData()["RequestId"]);
        print("\nCorrelationId = " . $mpgResponse->getMpgResponseData()["CorrelationId"]);
        print("\nDeviceProfileSessionId = " . $mpgResponse->getMpgResponseData()["DeviceProfileSessionId"]);
    }
    else{
        print("\nMessage = " . $mpgResponse->getMessage());
        print("\nType = " . $mpgResponse->getMpgResponseData()["Type"]);
        print("\nCODE= " . $mpgResponse->getMpgResponseData()["Code"]);
    }

} catch (Exception $e) {
    print("\nException caught: " . $e->getMessage() . "\n");
}

?>