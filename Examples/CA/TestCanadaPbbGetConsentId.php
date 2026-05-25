<?php

require "../../mpgClasses.php";

$type = "pbb_get_consent_id";
$store_id = "monca03650";
$api_token = "7Yw0MPTlhjBRcZiE6837";
$processing_country_code = "CA";
$consent_id = '6470c230-3e44-4a29-a31c-78b6cc30d174';
$correlation_id = '37b77ce9-2ef6-4d08-a8d0-6748b87c4db4';
$ext_id = -1;
$is_phoenix = true;

$pbb_consent_id = new PbbGetConsentId();
$pbb_consent_id->setConsentId($consent_id);
$pbb_consent_id->setExtId($ext_id);
$pbb_consent_id->setIsPhoenix($is_phoenix);

$mpgTxn = new mpgTransaction($pbb_consent_id);

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode($processing_country_code);
$mpgRequest->setTestMode(true);

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nMessage = " . $mpgResponse->getMessage());
print("\nCreatedOn = " . $mpgResponse->getCreatedOn() );
print("\nConsentId = " . $mpgResponse->getConsentId() );
print("\naction = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "action"));


// ShippingInfo
print("\nShipping Apartment = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "Apartment"));
print("\nShipping RuralPostalAddress = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "RuralPostalAddress"));
print("\nShipping StreetNumber = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "StreetNumber"));
print("\nShipping StreetName = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "StreetName"));
print("\nShipping City = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "City"));
print("\nShipping Province = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "Province"));
print("\nShipping PostalCode = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "PostalCode"));
print("\nShipping Country = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "Country"));
print("\nShipping RecipientName = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "RecipientName"));
print("\nShipping ShippingAddressRef = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "ShippingAddressRef"));


//CustomerInfo
print("\nName = " . $mpgResponse->getName() );
print("\nEmail = " . $mpgResponse->getEmail() );
print("\nPhoneNumber = " . $mpgResponse->getPhoneNumber() );
print("\nAuthMethod = " . $mpgResponse->getAuthMethod() );
print("\nUserType = " . $mpgResponse->getUserType() );

//Status
print("\nStatus = " . $mpgResponse->getStatus() );

// Order
print("\nMerchantOrderRef = " . $mpgResponse->getMerchantOrderRef() );
print("\nMerchantCustomerRef = " . $mpgResponse->getMerchantCustomerRef() );
print("\nPlacementMode = " . $mpgResponse->getPlacementMode() );
print("\nShipmentType = " . $mpgResponse->getShipmentType() );

// Items
foreach ($mpgResponse->items as $item) {
	print("\n\nItem ItemRef = " . ifPresentReturnValue($item, "ItemRef") );
	print("\nItem ItemPaymentType = " . ifPresentReturnValue($item, "ItemPaymentType") );
	print("\nItem Amount = " . ifPresentReturnValue($item, "Amount") );
	print("\nItem Quantity = " . ifPresentReturnValue($item, "Quantity") );
	print("\nItem Description = " . ifPresentReturnValue($item, "Description") );
	print("\nItem Type = " . ifPresentReturnValue($item, "ItemType") );
	print("\nItem Name = " . ifPresentReturnValue($item, "itemName") );
	print("\nItem RecurringInfo:");
	foreach (ifPresentReturnValue($item, "RecurringInfo") as $key => $value) {
		print("\n  " . $key . " = " . $value );
	}
}

// Payment
print("\n\nSubTotal = " . $mpgResponse->getSubTotal() );
print("\nAmount = " . $mpgResponse->getAmount() );
print("\nTax = " . $mpgResponse->getTax() );
print("\nShippingCost = " . $mpgResponse->getShippingCost() );
print("\nCurrency = " . $mpgResponse->getCurrency() );

// Additional Fees
foreach($mpgResponse->additionalFees as $fee) {
	print("\n\nFee Name = " . ifPresentReturnValue($fee, "Name") );
	print("\nFee Amount = " . ifPresentReturnValue($fee, "Amount") );
}

// MerchantRequiredFields
foreach($mpgResponse->merchantRequiredContactFields as $field) {
	print("\nMerchant Required Contact Fields = " . $field );
}

// MerchantSupportedPaymentMethods
foreach($mpgResponse->merchantSupportedPaymentMethods as $method) {
	print("\nMerchant Supported Payment Methods = " . $method );
}
// TokenMetaData
print("\nTokenMetaData AccountType = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "AccountType"));
print("\nTokenMetaData TokenState = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "TokenState"));
print("\nTokenMetaData AccountLastDigits = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "AccountLastDigits"));
print("\nTokenMetaData TokenPanLastDigits = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "TokenPanLastDigits"));
//print("\nTokenMetaData CreatedOn = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "CreatedOn"));
print("\nTokenMetaData PaymentTokenRef = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "PaymentTokenRef"));

print("\nAuthOn = " . ifPresentReturnValue($mpgResponse->getMpgResponseData(), "AuthOn"));


function ifPresentReturnValue($array, $key) {
	if (array_key_exists($key, $array)) {
		return $array[$key];
	}
	return "";
}
?>

