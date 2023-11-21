<?php

##
## Example php -q TestPurchase.php store1
##

require "../../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='store5';
$api_token='yesguy';

$kountInquiry = new KountInquiry();

$kountInquiry->setKountMerchantId("760000"); //6 digit - This is a UNIQUE local identifier used by the merchant to identify the kount inquiry request
$kountInquiry->setKountApiKey("myapi"); //214 character max - This is a UNIQUE local identifier used by the merchant to identify the kount inquiry request
$kountInquiry->setOrderId("nqa-orderidkount-1"); //64 characters max - This is a UNIQUE local identifier used by the merchant to identify the transaction e.g. purchase order number.
$kountInquiry->setCallCenterInd("N"); //Y or N - Risk Inquiry originating from call center environment
$kountInquiry->setCurrency("CAD"); //country of currency submitted on order
$kountInquiry->setDataKey("3B1C19fgfRObNHaQh5qVCpRW2"); //token from moneris vault service to represent pan if previously tokenized
$kountInquiry->setEmail("test@gmail.com"); //email address submitted by the customer
$kountInquiry->setCustomerId("NQA"); //Merchant assigned account number for consumer
$kountInquiry->setAutoNumberId("NQA-X1"); //Automatic Number Identification (ANI) submitted with order
$kountInquiry->setFinancialOrderId("nqa-fin-orderid-1"); //64 characters max - This is a local identifier used by the merchant to identify the transaction e.g. purchase order number.
$kountInquiry->setPaymentToken("4242424242424242"); //payment token submitted by merchant (ie: credit card, payer ID)
   /*	Payment Type Must be one of the following values:
	   APAY - Apple Pay
	   CARD - Credit Card
	   PYPL - Paypal
	   NONE - None
	   GOOG - Google Checkout
	   GIFT - Gift Card
	   INTERAC - Interac
	   CHEK - Check
	   GDMP - Green Dot Money Pack
	   BLML - Bill Me Later
	   BPAY - BPAY
	   NETELLER - Neteller
	   GIROPAY - GiroPay
	   ELV - ELV
	   MERCADE_PAGO - Mercade Pago
	   SEPA - Single Euro Payments Area
	   CARTE_BLEUE - Carte Bleue
	   POLI - POLi
	   Skrill/Moneybookers - SKRILL
	   SOFORT - Sofort
   */

$kountInquiry->setPaymentType("CARD"); //payment type submitted by merchant
$kountInquiry->setIpAddress("192.168.2.1"); //Dotted Decimal IPv4 address that the merchant sees coming from the customer
$kountInquiry->setSessionId("xjudq804i1049jkjakdad"); //unique session id.  Must be unique over a 30-day span
$kountInquiry->setWebsiteId("DEFAULT");
$kountInquiry->setAmount("100"); //Transaction amount This must contain at least 3 digits, two of which are penny values
$kountInquiry->setPaymentResponse("A"); //A - Authorized, D - Declined - payment transaction response
$kountInquiry->setAvsResponse("M"); //M - Match, N - No Match - avs verification response returned from payment request. This can be provided should $kountInquiry be performed after the transaction is complete
$kountInquiry->setCvdResponse("M"); //M - Match, N - No Match, X - Unsupported/Unavailable - cvd response returned to merchant from processor. This can be provided should $kountInquiry be performed after the transaction is complete
$kountInquiry->setBillStreet1("3300 Bloor Street"); //billing street address line 1
$kountInquiry->setBillStreet2("West Tower"); //billing street address line 2
$kountInquiry->setBillCountry("CA"); //2 character - billing country code
$kountInquiry->setBillCity("Toronto"); //billing address city
$kountInquiry->setBillPostalCode("M8X2X2"); //billing address postal code
$kountInquiry->setBillPhone("4167341000"); //billing phone number
$kountInquiry->setBillProvince("ON"); //billing address province
$kountInquiry->setDob("1950-11-12"); //YYYY-MM-DD
$kountInquiry->setEpoc("1491783223"); //timestamp expressed as seconds from epoch
$kountInquiry->setGender("M"); //M - Male or F - Female
$kountInquiry->setLast4("4242"); //last 4 digits of credit card value
$kountInquiry->setCustomerName("Moneris Test"); //customer name submitted with the order
$kountInquiry->setShipStreet1("3200 Bloor Street"); //shipping street address line 1
$kountInquiry->setShipStreet2("East Tower"); //shipping street address line 2
$kountInquiry->setShipCountry("CA"); //2 digit - shipping country code
$kountInquiry->setShipCity("Toronto"); //shipping address city
$kountInquiry->setShipEmail("test@gmail.com"); //email of recipient
$kountInquiry->setShipName("Moneris Test"); //name of recipient
$kountInquiry->setShipPostalCode("M8X2X3"); //shipping address postal code
$kountInquiry->setShipPhone("4167341001"); //ship-to phone number
$kountInquiry->setShipProvince("ON"); //shipping address province
$kountInquiry->setShipType("ST"); //Same Day = SD, Next Day = ND, Second Day = 2D, Standard = ST

//Product Details - item number, product_type, product_item (SKU), product_description, product quatity, product_price
//1-25 products can be added - must be in sequence starting with 1
$kountInquiry->setProduct(1, "Phone", "XM9731S", "iPhone 7", "1", "100");
$kountInquiry->setProduct(2, "Phone", "YM9731R", "iPhone 6", "1", "100");

//Local Attributes - 255 character max each,These attributes can be used to pass custom attribute data. These are used if you wish to correlate some data with the returned response via kount
//1-25 of these can be submitted in one request - must be in sequence starting with 1
$kountInquiry->setUdfField("LOCAL_ATTRIBUTE_1", "iPhone 7");
$kountInquiry->setUdf();

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($kountInquiry);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);
$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
$mpgRequest->setTestMode(true); //false or comment out this line for production transactions

/***************************** HTTPS Post Object *****************************/

/* Status Check Example
 $mpgHttpPost  =new mpgHttpsPostStatus($store_id,$api_token,$status_check,$mpgRequest);
 */

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nKountResult = " . $mpgResponse->getKountResult());
print("\nKountTransactionId = " . $mpgResponse->getKountTransactionId());
print("\nKountScore = " . $mpgResponse->getKountScore());
print("\nKountInfo = " . $mpgResponse->getKountInfo());


?>
