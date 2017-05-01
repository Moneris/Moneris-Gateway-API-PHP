<?php

require "../../mpgClasses.php";


/************************ Request Variables ***************************/

$store_id='moneris';
$api_token='hurgle';

/********************* Transactional Variables ************************/

$type='attribute_query';
$order_id='risktest-'.date("dmy-G:i:s");
$service_type='session';


/************************** SessionAccountInfo Variables *****************************/

$device_id = '4EC40DE5-0770-4fa0-BE53-981C067C598D';
$account_login = '13195417-8CA0-46cd-960D-14C158E4DBB2';
$password_hash = '489c830f10f7c601d30599a0deaf66e64d2aa50a';
$account_number = '3E17A905-AC8A-4c8d-A417-3DADA2A55220';
$account_name = '4590FCC0-DF4A-44d9-A57B-AF9DE98B84DD';
$account_email = '3CAE72EF-6B69-4a25-93FE-2674735E78E8@test.threatmetrix.com';
$account_telephone = '5556667777';
//$cc_number_hash = '';
$ip_address = '192.168.0.1';
$ip_forwarded = '192.168.0.1';
$account_address_street1 = '3300 Bloor St W';
$account_address_street2 = '4th Flr West Tower';
$account_address_city = 'Toronto';
$account_address_state ='Ontario';
$account_address_country = 'Canada';
$account_address_zip = 'M8X2X2';
$shipping_address_street1 = '3300 Bloor St W';
$shipping_address_street2 = '4th Flr West Tower';
$shipping_address_city = 'Toronto';
$shipping_address_state = 'Ontario';
$shipping_address_country = 'Canada';
$shipping_address_zip = 'M8X2X2';


/********************** SessionAccountInfo Associative Array *************************/

$attributeAccountInfoTemplate = array(
						'account_login'=>$account_login,
                     	'password_hash' =>$password_hash,
	                	'account_number' => $account_number,
                     	'account_name' => $account_name,
                     	'account_email'=>$account_email
                );


/************************** SessionAccountInfo Object ********************************/

$mpgAttributeAccountInfo = new mpgAttributeAccountInfo ($attributeAccountInfoTemplate);


/***************** Transactional Associative Array ********************/

$txnArray=array(
			'type'=>$type,
       			'order_id'=>$order_id,
       			'service_type'=>$service_type
          		);

/********************** Transaction Object ****************************/

$riskTxn = new mpgTransaction($txnArray);

/************************ Set SessionAccountInfo *****************************/

$riskTxn->setAttributeAccountInfo($mpgAttributeAccountInfo);

/************************ Request Object ******************************/

$riskRequest = new mpgRequest($riskTxn);
$riskRequest->setProcCountryCode("CA");
$riskRequest->setTestMode(true);

/*********************** HTTPS Post Object ****************************/

$riskHttpsPost  =new mpgHttpsPost($store_id,$api_token,$riskRequest);

/***************************** Response ******************************/

$riskResponse=$riskHttpsPost->getmpgResponse();

//print("\nResponse = " . $riskResponse); 

print("\nResponseCode = " . $riskResponse->getResponseCode());
print("\nMessage = " . $riskResponse->getMessage());

$results = $riskResponse->getResults();

foreach($results as $key => $value)
{
	print("\n".$key ." = ". $value);
}

$rules = $riskResponse->getRules();

//print_r($rules);

foreach ($rules as $i) 
{
    	foreach ($i as $key => $value) 
    	{
    		echo "\n$key = $value";
    	}
}

?>

