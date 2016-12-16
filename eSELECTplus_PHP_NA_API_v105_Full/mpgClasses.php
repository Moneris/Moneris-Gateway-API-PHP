<?php

#################### mpgGlobals #############################################


class mpgGlobals
{
	var $Globals=array(
        	        'MONERIS_PROTOCOL' => 'https',
					'MONERIS_HOST' => 'www3.moneris.com', //default
					'MONERIS_TEST_HOST' => 'esqa.moneris.com',
					'MONERIS_US_HOST' => 'esplus.moneris.com',
					'MONERIS_US_TEST_HOST' => 'esplusqa.moneris.com',
        	        'MONERIS_PORT' =>'443',
					'MONERIS_FILE' => '/gateway2/servlet/MpgRequest',
					'MONERIS_US_FILE' => '/gateway_us/servlet/MpgRequest',
					'MONERIS_MPI_FILE' => '/mpi/servlet/MpiServlet',
					'MONERIS_US_MPI_FILE' => '/mpi/servlet/MpiServlet',
                  	'API_VERSION'  =>'PHP NA - 1.0.5',
                  	'CLIENT_TIMEOUT' => '30'
                 	);

 	function mpgGlobals()
 	{
 		// default
 	}

 	function getGlobals()
 	{
  		return($this->Globals);
 	}

}//end class mpgGlobals

###################### curlPost #############################################
class httpsPost 
{
	var $url;
	var $dataToSend;
	var $clientTimeOut;
	var $apiVersion;
	var $response;
	var $debug = FALSE; //default is false for production release

	function httpsPost($url, $dataToSend)
	{
		$this->url=$url;
		$this->dataToSend=$dataToSend;

		if($this->debug == true)
		{
			echo "DataToSend= ".$this->dataToSend;
			echo "\n\nPostURL= " . $this->url;
		}
		
		$g=new mpgGlobals();
		$gArray=$g->getGlobals();
		$clientTimeOut = $gArray['CLIENT_TIMEOUT'];
		$apiVersion = $gArray['API_VERSION'];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$this->dataToSend);
		curl_setopt($ch,CURLOPT_TIMEOUT,$clientTimeOut);
		curl_setopt($ch,CURLOPT_USERAGENT,$apiVersion);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		//curl_setopt($ch, CURLOPT_CAINFO, "PATH_TO_CA_BUNDLE");
		
		$this->response=curl_exec ($ch);
		
		curl_close ($ch);
		
		if($this->debug == true)
		{
			echo "\n\nRESPONSE= $this->response\n";
		}
	}
	
	function getHttpsResponse()
	{
		return $this->response;
	}
}

###################### mpgHttpsPost #########################################

class mpgHttpsPost
{

 	var $api_token;
 	var $store_id;
 	var $app_version;
 	var $mpgRequest;
 	var $mpgResponse;
 	var $xmlString;
 	var $txnType;
 	var $isMPI;

 	function mpgHttpsPost($storeid,$apitoken,$mpgRequestOBJ)
 	{

  		$this->store_id=$storeid;
  		$this->api_token= $apitoken;
  		$this->app_version = null;
  		$this->mpgRequest=$mpgRequestOBJ;
  		$this->isMPI=$mpgRequestOBJ->getIsMPI();
  		$dataToSend=$this->toXML();
  		
		$url = $this->mpgRequest->getURL();
		
  		$httpsPost= new httpsPost($url, $dataToSend);	
  		$response = $httpsPost->getHttpsResponse();

  		if(!$response)
  		{

     			$response="<?xml version=\"1.0\"?><response><receipt>".
          			"<ReceiptId>Global Error Receipt</ReceiptId>".
          			"<ReferenceNum>null</ReferenceNum><ResponseCode>null</ResponseCode>".
          			"<AuthCode>null</AuthCode><TransTime>null</TransTime>".
          			"<TransDate>null</TransDate><TransType>null</TransType><Complete>false</Complete>".
          			"<Message>Global Error Receipt</Message><TransAmount>null</TransAmount>".
          			"<CardType>null</CardType>".
          			"<TransID>null</TransID><TimedOut>null</TimedOut>".
          			"<CorporateCard>false</CorporateCard><MessageId>null</MessageId>".
          			"</receipt></response>";
   		}

  		$this->mpgResponse=new mpgResponse($response);

 	}

	function setAppVersion($app_version)
	{
		$this->app_version = $app_version;
	}

 	function getMpgResponse()
 	{
  		return $this->mpgResponse;

 	}

 	function toXML()
 	{

  		$req=$this->mpgRequest;
  		$reqXMLString=$req->toXML();
  		
  		if($this->isMPI === true)
  		{
  			$this->xmlString .="<?xml version=\"1.0\"?>".
								"<MpiRequest>".
									"<store_id>$this->store_id</store_id>".
									"<api_token>$this->api_token</api_token>";
  			
  			if($this->app_version != null)
  			{
  				$this->xmlString .= "<app_version>$this->app_version</app_version>";
  			}
									
			$this->xmlString .= 	$reqXMLString.
								"</MpiRequest>";
  		}
  		else
  		{
  			$this->xmlString .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>".
               					"<request>".
               						"<store_id>$this->store_id</store_id>".
               						"<api_token>$this->api_token</api_token>";
  			
  			if($this->app_version != null)
  			{
  				$this->xmlString .= "<app_version>$this->app_version</app_version>";
  			}
  			
            $this->xmlString .=    	$reqXMLString.
                				"</request>";
  		}

  		return ($this->xmlString);

 	}

}//end class mpgHttpsPost


###################### mpgHttpsPostStatus ###################################

class mpgHttpsPostStatus
{

 	var $api_token;
 	var $store_id;
 	var $app_version;
 	var $status;
 	var $mpgRequest;
 	var $mpgResponse;
 	var $xmlString;

 	function mpgHttpsPostStatus($storeid,$apitoken,$status,$mpgRequestOBJ)
 	{

  		$this->store_id=$storeid;
  		$this->api_token= $apitoken;
  		$this->app_version = null;
  		$this->status=$status;
  		$this->mpgRequest=$mpgRequestOBJ;
  		$dataToSend=$this->toXML();

  		//$transactionType=$mpgRequestOBJ->getTransactionType();
 		
  		$url = $this->mpgRequest->getURL();

  		$httpsPost= new httpsPost($url, $dataToSend);	
  		$response = $httpsPost->getHttpsResponse();

  		if(!$response)
  		{

     			$response="<?xml version=\"1.0\"?><response><receipt>".
          			"<ReceiptId>Global Error Receipt</ReceiptId>".
          			"<ReferenceNum>null</ReferenceNum><ResponseCode>null</ResponseCode>".
          			"<AuthCode>null</AuthCode><TransTime>null</TransTime>".
          			"<TransDate>null</TransDate><TransType>null</TransType><Complete>false</Complete>".
          			"<Message>Global Error Receipt</Message><TransAmount>null</TransAmount>".
          			"<CardType>null</CardType>".
          			"<TransID>null</TransID><TimedOut>null</TimedOut>".
          			"<CorporateCard>false</CorporateCard><MessageId>null</MessageId>".
          			"</receipt></response>";
   		}

  		$this->mpgResponse=new mpgResponse($response);

 	}

 	function setAppVersion($app_version)
 	{
 		$this->app_version = $app_version;
 	}

 	function getMpgResponse()
 	{
  		return $this->mpgResponse;
 	}

 	function toXML( )
 	{

  		$req=$this->mpgRequest ;
  		$reqXMLString=$req->toXML();

  		$this->xmlString .= "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>".
	               			"<request>".
	               			"<store_id>$this->store_id</store_id>".
	               			"<api_token>$this->api_token</api_token>";
  		
  		if($this->app_version != null)
  		{
  			$this->xmlString .= "<app_version>$this->app_version</app_version>";
  		}
  		
	    $this->xmlString .= "<status_check>$this->status</status_check>".
	                		$reqXMLString.
	                		"</request>";

  		return ($this->xmlString);

 	}

}//end class mpgHttpsPostStatus

############# mpgResponse ###################################################


class mpgResponse
{

	var $responseData;

 	var $p; //parser

 	var $currentTag;
 	var $purchaseHash = array();
 	var $refundHash;
 	var $correctionHash = array();
 	var $isBatchTotals;
 	var $term_id;
 	var $receiptHash = array();
 	var $ecrHash = array();
 	var $CardType;
 	var $currentTxnType;
 	var $ecrs = array();
 	var $cards = array();
 	var $cardHash= array();

	//specifically for Resolver transactions
 	var $resolveData;
 	var $resolveDataHash;
 	var $data_key="";
 	var $DataKeys = array();
 	var $isResolveData;
 	
 	//specifically for VdotMe transactions
 	var $vDotMeInfo;
 	var $isVdotMeInfo;
 	
 	//specifically for MasterPass transactions
 	var $isPaypass;
 	var $isPaypassInfo;
 	var $masterPassData = array();

 	//specifically for MPI transactions
 	var $ACSUrl;
 	var $isMPI = false;
 	
 	//specifically for Risk transactions
 	var $isResults;
 	var $isRule;
 	var $ruleName;
 	var $results = array();
 	var $rules = array();

 	function mpgResponse($xmlString)
 	{

  		$this->p = xml_parser_create();
  		xml_parser_set_option($this->p,XML_OPTION_CASE_FOLDING,0);
  		xml_parser_set_option($this->p,XML_OPTION_TARGET_ENCODING,"UTF-8");
  		xml_set_object($this->p,$this);
  		xml_set_element_handler($this->p,"startHandler","endHandler");
  		xml_set_character_data_handler($this->p,"characterHandler");
  		xml_parse($this->p,$xmlString);
  		xml_parser_free($this->p);

 	}	//end of constructor


 	function getMpgResponseData()
	{
   		return($this->responseData);
 	}
 	
 	//To prevent Undefined Index Notices
 	private function getMpgResponseValue($responseData, $value)
 	{
 		return (isset($responseData[$value]) ? $responseData[$value] : '');
 	}

	function getRecurSuccess()
	{
 		return $this->getMpgResponseValue($this->responseData, 'RecurSuccess');
	}

	function getStatusCode()
	{
	 	return $this->getMpgResponseValue($this->responseData, 'status_code');
	}

	function getStatusMessage()
	{
	 	return $this->getMpgResponseValue($this->responseData, 'status_message');
	}

	function getAvsResultCode()
	{
		return $this->getMpgResponseValue($this->responseData,'AvsResultCode');
	}

	function getCvdResultCode()
	{
		return $this->getMpgResponseValue($this->responseData,'CvdResultCode');
	}

	function getCardType()
	{
 		return $this->getMpgResponseValue($this->responseData,'CardType');
	}

	function getTransAmount()
	{
 		return $this->getMpgResponseValue($this->responseData,'TransAmount');
	}

	function getTxnNumber()
	{
 		return $this->getMpgResponseValue($this->responseData,'TransID');
	}

	function getReceiptId()
	{
 		return $this->getMpgResponseValue($this->responseData, 'ReceiptId');
	}

	function getTransType()
	{
 		return $this->getMpgResponseValue($this->responseData,'TransType');
	}

	function getReferenceNum()
	{
 		return $this->getMpgResponseValue($this->responseData,'ReferenceNum');
	}

	function getResponseCode()
	{
 		return $this->getMpgResponseValue($this->responseData,'ResponseCode');
	}

	function getISO()
	{
 		return $this->getMpgResponseValue($this->responseData,'ISO');
	}

	function getBankTotals()
	{
 		return $this->getMpgResponseValue($this->responseData,'BankTotals');
	}

	function getMessage()
	{
 		return $this->getMpgResponseValue($this->responseData,'Message');
	}

	function getAuthCode()
	{
 		return $this->getMpgResponseValue($this->responseData,'AuthCode');
	}

	function getComplete()
	{
 		return $this->getMpgResponseValue($this->responseData,'Complete');
	}

	function getTransDate()
	{
 		return $this->getMpgResponseValue($this->responseData,'TransDate');
	}

	function getTransTime()
	{
 		return $this->getMpgResponseValue($this->responseData,'TransTime');
	}

	function getTicket()
	{
 		return $this->getMpgResponseValue($this->responseData,'Ticket');
	}

	function getTimedOut()
	{
 		return $this->getMpgResponseValue($this->responseData,'TimedOut');
	}

	function getCorporateCard()
	{
		return $this->getMpgResponseValue($this->responseData,'CorporateCard');
    }

    function getCavvResultCode()
    {
		return $this->getMpgResponseValue($this->responseData,'CavvResultCode');
	}

	function getCardLevelResult()
	{
		return $this->getMpgResponseValue($this->responseData,'CardLevelResult');
	}

	function getITDResponse()
	{
		return $this->getMpgResponseValue($this->responseData,'ITDResponse');
	}
	
	function getIsVisaDebit()
	{
		return $this->getMpgResponseValue($this->responseData,'IsVisaDebit');	
	}
	
	function getMaskedPan()
	{
		return $this->getMpgResponseValue($this->responseData,'MaskedPan');
	}
	
	function getCfSuccess()
	{
		return $this->getMpgResponseValue($this->responseData,'CfSuccess');
	}
	
	function getCfStatus()
	{
		return $this->getMpgResponseValue($this->responseData,'CfStatus');
	}
	
	function getFeeAmount()
	{
		return $this->getMpgResponseValue($this->responseData,'FeeAmount');
	}
	
	function getFeeRate()
	{
		return $this->getMpgResponseValue($this->responseData,'FeeRate');
	}
	
	function getFeeType()
	{
		return $this->getMpgResponseValue($this->responseData,'FeeType');
	}

	//--------------------------- RecurUpdate response fields ----------------------------//

	function getRecurUpdateSuccess()
	{
		return $this->getMpgResponseValue($this->responseData,'RecurUpdateSuccess');
	}

	function getNextRecurDate()
	{
		return $this->getMpgResponseValue($this->responseData,'NextRecurDate');
	}

	function getRecurEndDate()
	{
		return $this->getMpgResponseValue($this->responseData,'RecurEndDate');
	}
	
	//--------------------------- MCP response fields ----------------------------//
	
	function getMCPAmount()
	{
		return $this->getMpgResponseValue($this->responseData,'MCPAmount');
	}
	
	function getMCPCurrencyCode()
	{
		return $this->getMpgResponseValue($this->responseData,'MCPCurrencyCode');
	}

	//-------------------------- Resolver response fields --------------------------------//

	function getDataKey()
	{
		return $this->getMpgResponseValue($this->responseData,'DataKey');
	}

	function getResSuccess()
	{
		return $this->getMpgResponseValue($this->responseData,'ResSuccess');
	}

	function getPaymentType()
	{
		return $this->getMpgResponseValue($this->responseData,'PaymentType');
	}

	//------------------------------------------------------------------------------------//

	function getResolveData()
	{
		if($this->responseData['ResolveData']!='null'){
			return ($this->resolveData);
		}

		return $this->getMpgResponseValue($this->responseData,'ResolveData');
	}

	function setResolveData($data_key)
	{
		$this->resolveData=$this->resolveDataHash[$data_key];
	}

	function getResolveDataHash()
	{
		return ($this->resolveDataHash);
	}

	function getDataKeys()
	{
	 	return ($this->DataKeys);
 	}

 	function getResDataDataKey()
	{
		return $this->getMpgResponseValue($this->resolveData,'data_key');
	}

	function getResDataPaymentType()
	{
		return $this->getMpgResponseValue($this->resolveData,'payment_type');
	}

	function getResDataCustId()
	{
		return $this->getMpgResponseValue($this->resolveData,'cust_id');
	}

	function getResDataPhone()
	{
		return $this->getMpgResponseValue($this->resolveData,'phone');
	}

	function getResDataEmail()
	{
		return $this->getMpgResponseValue($this->resolveData,'email');
	}

	function getResDataNote()
	{
		return $this->getMpgResponseValue($this->resolveData,'note');
	}

	function getResDataPan()
	{
		return $this->getMpgResponseValue($this->resolveData,'pan');
	}

	function getResDataMaskedPan()
	{
		return $this->getMpgResponseValue($this->resolveData,'masked_pan');
	}

	function getResDataExpDate()
	{
		return $this->getMpgResponseValue($this->resolveData,'expdate');
	}

	function getResDataAvsStreetNumber()
	{
		return $this->getMpgResponseValue($this->resolveData,'avs_street_number');
	}

	function getResDataAvsStreetName()
	{
		return $this->getMpgResponseValue($this->resolveData,'avs_street_name');
	}

	function getResDataAvsZipcode()
	{
		return $this->getMpgResponseValue($this->resolveData,'avs_zipcode');
	}

	function getResDataCryptType()
	{
		return $this->getMpgResponseValue($this->resolveData,'crypt_type');
	}
	
	function getResDataSec()
	{
		return $this->getMpgResponseValue($this->resolveData,'sec');
	}
	
	function getResDataCustFirstName()
	{
		return $this->getMpgResponseValue($this->resolveData,'cust_first_name');
	}
	
	function getResDataCustLastName()
	{
		return $this->getMpgResponseValue($this->resolveData,'cust_last_name');
	}
	
	function getResDataCustAddress1()
	{
		return $this->getMpgResponseValue($this->resolveData,'cust_address1');
	}
	
	function getResDataCustAddress2()
	{
		return $this->getMpgResponseValue($this->resolveData,'cust_address2');
	}
	
	function getResDataCustCity()
	{
		return $this->getMpgResponseValue($this->resolveData,'cust_city');
	}
	
	function getResDataCustState()
	{
		return $this->getMpgResponseValue($this->resolveData,'cust_state');
	}
	
	function getResDataCustZip()
	{
		return $this->getMpgResponseValue($this->resolveData,'cust_zip');
	}
	
	function getResDataRoutingNum()
	{
		return $this->getMpgResponseValue($this->resolveData,'routing_num');
	}
	
	function getResDataAccountNum()
	{
		return $this->getMpgResponseValue($this->resolveData,'account_num');
	}
	
	function getResDataMaskedAccountNum()
	{
		return $this->getMpgResponseValue($this->resolveData,'masked_account_num');
	}
	
	function getResDataCheckNum()
	{
		return $this->getMpgResponseValue($this->resolveData,'check_num');
	}
	
	function getResDataAccountType()
	{
		return $this->getMpgResponseValue($this->resolveData,'account_type');
	}
	
	function getResDataPresentationType()
	{
		return $this->getMpgResponseValue($this->resolveData,'presentation_type');
	}
	
	function getResDataPAccountNumber()
	{
		return $this->getMpgResponseValue($this->resolveData,'p_account_number');
	}
	
	//-------------------------- VdotMe specific fields --------------------------------//
	function getVDotMeData()
	{
		return($this->vDotMeInfo);
	}
	
	function getCurrencyCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'currencyCode');
	}

	function getPaymentTotal()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'total');
	}

	function getUserFirstName()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'userFirstName');
	}

	function getUserLastName()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'userLastName');
	}

	function getUserName()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'userName');
	}

	function getUserEmail()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'userEmail');
	}

	function getEncUserId()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'encUserId');
	}

	function getCreationTimeStamp()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'creationTimeStamp');
	}

	function getNameOnCard()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'nameOnCard');
	}

	function getExpirationDateMonth()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['expirationDate'],'month');
	}

	function getExpirationDateYear()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['expirationDate'],'year');
	}

	function getBillingId()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'id');
	}
	
	function getLastFourDigits()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'lastFourDigits');
	}

	function getBinSixDigits()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'binSixDigits');
	}

	function getCardBrand()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'cardBrand');
	}

	function getVDotMeCardType()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'cardType');
	}
	
	function getBillingPersonName()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'personName');
	}

	function getBillingAddressLine1()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'line1');
	}

	function getBillingCity()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'city');
	}

	function getBillingStateProvinceCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'stateProvinceCode');
	}

	function getBillingPostalCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'postalCode');
	}

	function getBillingCountryCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'countryCode');
	}

	function getBillingPhone()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['billingAddress'],'phone');
	}

	function getBillingVerificationStatus()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'verificationStatus');
	}
	
	function getIsExpired()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'expired');
	}

	function getPartialShippingCountryCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['partialShippingAddress'],'countryCode');
	}

	function getPartialShippingPostalCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['partialShippingAddress'],'postalCode');
	}

	function getShippingPersonName()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'personName');
	}

	function getShippingCity()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'city');
	}

	function getShippingStateProvinceCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'stateProvinceCode');
	}

	function getShippingPostalCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'postalCode');
	}

	function getShippingCountryCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'countryCode');
	}

	function getShippingPhone()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'phone');
	}

	function getShippingDefault()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'default');
	}

	function getShippingId()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'id');
	}

	function getShippingVerificationStatus()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'verificationStatus');
	}

	function getBaseImageFileName()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'baseImageFileName');
	}

	function getHeight()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'height');
	}

	function getWidth()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'width');
	}

	function getIssuerBid()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo,'issuerBid');
	}

	function getRiskAdvice()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'advice');
	}

	function getRiskScore()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'score');
	}

	function getAvsResponseCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'avsResponseCode');
	}

	function getCvvResponseCode()
	{
		return $this->getMpgResponseValue($this->vDotMeInfo['riskData'],'cvvResponseCode');
	}
	
	//--------------------------- MasterPass response fields -----------------------------//
	
	function getCardBrandId()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBrandId');
	}
	
	
	function getCardBrandName()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBrandName');
	}
	
	
	function getCardBillingAddressCity()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressCity');
	}
	
	
	function getCardBillingAddressCountry()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressCountry');
	}
	
	
	function getCardBillingAddressCountrySubdivision()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressCountrySubdivision');
	}
	
	
	function getCardBillingAddressLine1()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressLine1');
	}
	
	
	function getCardBillingAddressLine2()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressLine2');
	}
	
	
	function getCardBillingAddressPostalCode()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressPostalCode');
	}
	
	
	function getCardBillingAddressRecipientPhoneNumber()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressRecipientPhoneNumber');
	}
	
	
	function getCardBillingAddressRecipientName()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardBillingAddressRecipientName');
	}
	
	
	function getCardCardHolderName()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardCardHolderName');
	}
	
	
	function getCardExpiryMonth()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardExpiryMonth');
	}
	
	
	function getCardExpiryYear()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardExpiryYear');
	}
	
	
	function getContactEmailAddress()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ContactEmailAddress');
	}
	
	
	function getContactFirstName()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ContactFirstName');
	}
	
	
	function getContactLastName()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ContactLastName');
	}
	
	
	function getContactPhoneNumber()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ContactPhoneNumber');
	}
	
	
	function getShippingAddressCity()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressCity');
	}
	
	
	function getShippingAddressCountry()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressCountry');
	}
	
	
	function getShippingAddressCountrySubdivision()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressCountrySubdivision');
	}
	
	function getShippingAddressLine2()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressLine2');
	}
	
	
	function getShippingAddressPostalCode()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressPostalCode');
	}
	
	
	function getShippingAddressRecipientName()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressRecipientName');
	}
	
	
	function getShippingAddressRecipientPhoneNumber()
	{
		return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressRecipientPhoneNumber');
	}
	
	
	function getPayPassWalletIndicator()
	{
		return $this->getMpgResponseValue($this->masterPassData,'PayPassWalletIndicator');
	}
	
	
	function getAuthenticationOptionsAuthenticateMethod()
	{
		return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsAuthenticateMethod');
	}
	
	
	function getAuthenticationOptionsCardEnrollmentMethod()
	{
		return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsCardEnrollmentMethod');
	}
	
	
	function getCardAccountNumber()
	{
		return $this->getMpgResponseValue($this->masterPassData,'CardAccountNumber');
	}
	
	
	function getAuthenticationOptionsEciFlag()
	{
		return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsEciFlag');
	}
	
	
	function getAuthenticationOptionsPaResStatus()
	{
		return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsPaResStatus');
	}
	
	
	function getAuthenticationOptionsSCEnrollmentStatus()
	{
		return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsSCEnrollmentStatus');
	}
	
	
	function getAuthenticationOptionsSignatureVerification()
	{
		return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsSignatureVerification');
	}
	
	
	function getAuthenticationOptionsXid()
	{
		return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsXid');
	}
	
	
	function getAuthenticationOptionsCAvv()
	{
		return $this->getMpgResponseValue($this->masterPassData,'AuthenticationOptionsCAvv');
	}
	
	
	function getTransactionId()
	{
		return $this->getMpgResponseValue($this->masterPassData,'TransactionId');
	}
	
	function getMPRequestToken()
	{
		return $this->getMpgResponseValue($this->responseData,'MPRequestToken');
	}
	
	function getMPRedirectUrl()
	{
		return $this->getMpgResponseValue($this->responseData,'MPRedirectUrl');
	}
	
	//------------------- VDotMe & MasterPass shared response fields ---------------------//
	
	function getShippingAddressLine1()
	{
		if ($this->isPaypass)
		{
			return $this->getMpgResponseValue($this->masterPassData,'ShippingAddressLine1');
		}
		else
		{
			return $this->getMpgResponseValue($this->vDotMeInfo['shippingAddress'],'line1');
		}
	}
//------------------- MPI response fields ---------------------//
	function getMpiType()
	{
		return $this->getMpgResponseValue($this->responseData,'MpiType');
	}

	function getMpiSuccess()
	{
		if ($this->isMPI === false)
		{
			return $this->getMpgResponseValue($this->responseData,'MpiSuccess');
		}
		else
		{
			return $this->getMpgResponseValue($this->responseData,'success');
		}
	}

	function getMpiMessage()
	{
		if ($this->isMPI === false)
		{
			return $this->getMpgResponseValue($this->responseData,'MpiMessage');
		}
		else
		{
			return $this->getMpgResponseValue($this->responseData,'message');
		}
	}
	
	function getMpiPaReq()
	{
		if ($this->isMPI === false)
		{
			return $this->getMpgResponseValue($this->responseData,'MpiPaReq');
		}
		else
		{
			return $this->getMpgResponseValue($this->responseData,'PaReq');
		}
	}

	function getMpiTermUrl()
	{
		if ($this->isMPI === false)
		{
			return $this->getMpgResponseValue($this->responseData,'MpiTermUrl');
		}
		else
		{
			return $this->getMpgResponseValue($this->responseData,'TermUrl');
		}
	}
	
	function getMpiMD()
	{
		if ($this->isMPI === false)
		{
			return $this->getMpgResponseValue($this->responseData,'MpiMD');
		}
		else
		{
			return $this->getMpgResponseValue($this->responseData,'MD');
		}
	}

	function getMpiACSUrl()
	{
		if ($this->isMPI === false)
		{
			return $this->getMpgResponseValue($this->responseData,'MpiACSUrl');
		}
		else
		{
			return $this->getMpgResponseValue($this->responseData,'ACSUrl');
		}
	}
	
	function getMpiCavv()
	{
		if ($this->isMPI === false)
		{
			return $this->getMpgResponseValue($this->responseData,'MpiCavv');
		}
		else
		{
			return $this->getMpgResponseValue($this->responseData,'cavv');
		}
	}

	function getMpiEci()
	{
		if ($this->isMPI === false)
		{
			return $this->getMpgResponseValue($this->responseData,'MpiEci');
		}
		else
		{
			return $this->getMpgResponseValue($this->responseData,'eci');
		}
	}


	function getMpiPAResVerified()
	{
		if ($this->isMPI === false)
		{
			return $this->getMpgResponseValue($this->responseData,'MpiPAResVerified');
		}
		else
		{
			return $this->getMpgResponseValue($this->responseData,'PAResVerified');
		}
	}
	
	function getMpiResponseData()
	{
		return($this->responseData);
	}
	
	function getMpiInLineForm()
	{
		
		$inLineForm ='<html><head><title>Title for Page</title></head><SCRIPT LANGUAGE="Javascript" >' . 
				"<!--
				function OnLoadEvent()
				{
					document.downloadForm.submit();
				}
				-->
				</SCRIPT>" .
				'<body onload="OnLoadEvent()">
					<form name="downloadForm" action="' . $this->getMpiACSUrl() . 
					'" method="POST">
					<noscript>
					<br>
					<br>
					<center>
					<h1>Processing your 3-D Secure Transaction</h1>
					<h2>
					JavaScript is currently disabled or is not supported
					by your browser.<br>
					<h3>Please click on the Submit button to continue
					the processing of your 3-D secure
					transaction.</h3>
					<input type="submit" value="Submit">
					</center>
					</noscript>
					<input type="hidden" name="PaReq" value="' . $this->getMpiPaReq() . '">
					<input type="hidden" name="MD" value="' . $this->getMpiMD() . '">
					<input type="hidden" name="TermUrl" value="' . $this->getMpiTermUrl() .'">
				</form>
				</body>
				</html>';
	
		return $inLineForm; 
	}
	
	function getMpiPopUpWindow()
	{
		$popUpForm ='<html><head><title>Title for Page</title></head><SCRIPT LANGUAGE="Javascript" >' .
				"<!--
					function OnLoadEvent()
					{
						window.name='mainwindow';
						//childwin = window.open('about:blank','popupName','height=400,width=390,status=yes,dependent=no,scrollbars=yes,resizable=no');
						//document.downloadForm.target = 'popupName';
						document.downloadForm.submit();
					}
					-->
					</SCRIPT>" .
						'<body onload="OnLoadEvent()">
						<form name="downloadForm" action="' . $this->getMpiAcsUrl() .
							'" method="POST">
						<noscript>
						<br>
						<br>
						<center>
						<h1>Processing your 3-D Secure Transaction</h1>
						<h2>
						JavaScript is currently disabled or is not supported
						by your browser.<br>
						<h3>Please click on the Submit button to continue
						the processing of your 3-D secure
						transaction.</h3>
						<input type="submit" value="Submit">
						</center>
						</noscript>
						<input type="hidden" name="PaReq" value="' . $this->getMpiPaReq() . '">
						<input type="hidden" name="MD" value="' . $this->getMpiMD() . '">
						<input type="hidden" name="TermUrl" value="' . $this->getMpiTermUrl() .'">
						</form>
					</body>
					</html>';
	
		return $popUpForm;
	}
	
	
	//-----------------  Risk response fields  ---------------------------------------------------------//
	
	function getRiskResponse()
	{
		return ($this->responseData);
	}
	
	function getResults()
	{
		return ($this->results);
	}
	
	function getRules()
	{
		return ($this->rules);
	}
	
	//--------------------------- BatchClose response fields -----------------------------//

	function getTerminalStatus($ecr_no)
	{
 		return $this->getMpgResponseValue($this->ecrHash,$ecr_no);
	}

	function getPurchaseAmount($ecr_no,$card_type)
	{
 		return ($this->purchaseHash[$ecr_no][$card_type]['Amount']=="" ? 0:$this->purchaseHash[$ecr_no][$card_type]['Amount']);
	}

	function getPurchaseCount($ecr_no,$card_type)
	{
 		return ($this->purchaseHash[$ecr_no][$card_type]['Count']=="" ? 0:$this->purchaseHash[$ecr_no][$card_type]['Count']);
	}

	function getRefundAmount($ecr_no,$card_type)
	{
 		return ($this->refundHash[$ecr_no][$card_type]['Amount']=="" ? 0:$this->refundHash[$ecr_no][$card_type]['Amount']);
	}

	function getRefundCount($ecr_no,$card_type)
	{
 		return ($this->refundHash[$ecr_no][$card_type]['Count']=="" ? 0:$this->refundHash[$ecr_no][$card_type]['Count']);
	}

	function getCorrectionAmount($ecr_no,$card_type)
	{
 		return ($this->correctionHash[$ecr_no][$card_type]['Amount']=="" ? 0:$this->correctionHash[$ecr_no][$card_type]['Amount']);
	}

	function getCorrectionCount($ecr_no,$card_type)
	{
 		return ($this->correctionHash[$ecr_no][$card_type]['Count']=="" ? 0:$this->correctionHash[$ecr_no][$card_type]['Count']);
	}

	function getTerminalIDs()
	{
 		return ($this->ecrs);
	}

	function getCreditCardsAll()
	{
 		return (array_keys($this->cards));
	}

	function getCreditCards($ecr)
	{
 		return $this->getMpgResponseValue($this->cardHash,$ecr);
	}



	function characterHandler($parser,$data)
	{
		if($this->isBatchTotals)
 		{
   			switch($this->currentTag)
    		{
     			case "term_id"    :
				{
					$this->term_id=$data;
					array_push($this->ecrs,$this->term_id);
					$this->cardHash[$data]=array();
					break;
				}

     			case "closed"     :
				{
					$ecrHash=$this->ecrHash;
					$ecrHash[$this->term_id]=$data;
					$this->ecrHash = $ecrHash;
					break;
				}

     			case "CardType"   :
				{
					$this->CardType=$data;
					$this->cards[$data]=$data;
					array_push($this->cardHash[$this->term_id],$data) ;
					break;
				}

     			case "Amount"     :
				{
					if($this->currentTxnType == "Purchase")
					{
						$this->purchaseHash[$this->term_id][$this->CardType]['Amount']=$data;
					}
					elseif( $this->currentTxnType == "Refund")
					{
						$this->refundHash[$this->term_id][$this->CardType]['Amount']=$data;
					}
					elseif( $this->currentTxnType == "Correction")
					{
						$this->correctionHash[$this->term_id][$this->CardType]['Amount']=$data;
					}
					break;
				 }

    			case "Count"     :
				{
					if($this->currentTxnType == "Purchase")
					{
						$this->purchaseHash[$this->term_id][$this->CardType]['Count']=$data;
					}
					elseif( $this->currentTxnType == "Refund")
					{
						$this->refundHash[$this->term_id][$this->CardType]['Count']=$data;
					}
					else if( $this->currentTxnType == "Correction")
					{
						$this->correctionHash[$this->term_id][$this->CardType]['Count']=$data;
					}
					break;
				}
	    	}

 		}
 		elseif($this->isResolveData && $this->currentTag != "ResolveData")
 		{
			if($this->currentTag == "data_key")
			{
				$this->data_key=$data;
				array_push($this->DataKeys,$this->data_key);
				$this->resolveData[$this->currentTag] = $data;
			}
   			else
   			{
   				$this->resolveData[$this->currentTag] = $data;
   			}
 		}
 		elseif($this->isVdotMeInfo)
 		{
 			if($this->ParentNode != "")
 				$this->vDotMeInfo[$this->ParentNode][$this->currentTag] = $data;
 			else
 				$this->vDotMeInfo[$this->currentTag] = $data;
 		}
 		else if ($this->isPaypassInfo)
 		{
 			$this->masterPassData[$this->currentTag] = $data;
 		}
 		elseif($this->isResults)
 		{
 			$this->results[$this->currentTag] = $data;
 			 
 		}
 		elseif($this->isRule)
 		{
 		
 			if ($this->currentTag == "RuleName")
 			{
 				$this->ruleName=$data;
 			}
 			$this->rules[$this->ruleName][$this->currentTag] = $data;
 		
 		}
 		else
 		{
 			$this->responseData[$this->currentTag] = $data;
 		}

	}//end characterHandler



	function startHandler($parser,$name,$attrs)
	{

		$this->currentTag=$name;

		if($this->currentTag == "ResolveData")
		{
			$this->isResolveData=1;
  	 	}
  	 	elseif($this->isResolveData)
  	 	{
  	 		$this->resolveData[$this->currentTag]="";
  	 	}
  	 	elseif($this->currentTag == "MpiResponse")
  	 	{
  	 		$this->isMPI=true;
  	 	}
  	 	elseif($this->currentTag == "VDotMeInfo")
  	 	{
  	 		$this->isVdotMeInfo=1;
  	 	}
  	 	elseif($this->isVdotMeInfo)
  	 	{
  	 		switch($name){
  	 			case "billingAddress":
  	 				{
  	 					$this->ParentNode=$name;
  	 					break;
  	 				}
  	 			case "partialShippingAddress":
  	 				{
  	 					$this->ParentNode=$name;
  	 					break;
  	 				}
  	 			case "shippingAddress":
  	 				{
  	 					$this->ParentNode=$name;
  	 					break;
  	 				}
  	 			case "riskData":
  	 				{
  	 					$this->ParentNode=$name;
  	 					break;
  	 				}
  	 			case "expirationDate":
  	 				{
  	 					$this->ParentNode=$name;
  	 					break;
  	 				}
  	 		}
  	 	}
  	 	else if($this->currentTag == "PayPassInfo")
  	 	{
  	 		$this->isPaypassInfo=1;
  	 		$this->isPaypass=1;
  	 	}
  		elseif($this->currentTag == "BankTotals")
  	 	{
  	  		$this->isBatchTotals=1;
  	 	}
  		elseif($this->currentTag == "Purchase")
   		{
   	 		$this->purchaseHash[$this->term_id][$this->CardType]=array();
   	 		$this->currentTxnType="Purchase";
   		}
  		elseif($this->currentTag == "Refund")
  	 	{
  	  		$this->refundHash[$this->term_id][$this->CardType]=array();
  	  		$this->currentTxnType="Refund";
  	 	}
  		elseif($this->currentTag == "Correction")
   		{
   	 		$this->correctionHash[$this->term_id][$this->CardType]=array();
   	 		$this->currentTxnType="Correction";
   		}
   		elseif($this->currentTag == "Result")
   		{
   			$this->isResults=1;
   		}
   		elseif($this->currentTag == "Rule")
   		{
   			$this->isRule=1;
   		}
	}

	function endHandler($parser,$name)
	{

	 	$this->currentTag=$name;
	 	if($this->currentTag == "ResolveData")
		{
			$this->isResolveData=0;
			if($this->data_key!="")
			{
				$this->resolveDataHash[$this->data_key]=$this->resolveData;
				$this->resolveData=array();
			}
	 	} 	
	 	elseif($this->currentTag == "VDotMeInfo")
	 	{
	 		$this->isVdotMeInfo=0;
	 	} 	
	 	elseif($this->isVdotMeInfo)
	 	{
	 		switch($this->currentTag){
	 			case "billingAddress":
	 				{
	 					$this->ParentNode="";
	 					break;
	 				}
	 			case "partialShippingAddress":
	 				{
	 					$this->ParentNode="";
	 					break;
	 				}
	 			case "shippingAddress":
	 				{
	 					$this->ParentNode="";
	 					break;
	 				}
	 			case "riskData":
	 				{
	 					$this->ParentNode="";
	 					break;
	 				}
	 			case "expirationDate":
	 				{
	 					$this->ParentNode="";
	 					break;
	 				}	
	 		}
	 	}
	 	elseif($name == "BankTotals")
	  	{
	    	$this->isBatchTotals=0;
	   	}
	   	else if($this->currentTag == "PayPassInfo")
	   	{
	   		$this->isPaypassInfo=0;
	   	}
	   	elseif($name == "Result")
	   	{
	   		$this->isResults=0;
	   	}
	   	elseif($this->currentTag == "Rule")
	   	{
	   		$this->isRule=0;
	   	}

 		$this->currentTag="/dev/null";
	}

}//end class mpgResponse


################## mpgRequest ###############################################

class mpgRequest
{

 	var $txnTypes =array(
 				//Basic
 				'batchclose' => array('ecr_number'),
 				'card_verification' =>array('order_id','cust_id','pan','expdate', 'crypt_type'),
 				'cavv_preauth' =>array('order_id','cust_id', 'amount', 'pan','expdate', 'cavv','crypt_type','dynamic_descriptor', 'wallet_indicator'),
 				'cavv_purchase' => array('order_id','cust_id', 'amount', 'pan','expdate', 'cavv','crypt_type','dynamic_descriptor', 'wallet_indicator'),
 				'completion' => array('order_id', 'comp_amount','txn_number', 'crypt_type', 'cust_id', 'dynamic_descriptor', 'mcp_amount', 'mcp_currency_code'),
 				'contactless_purchase' => array('order_id','cust_id','amount','track2','pan','expdate', 'pos_code','dynamic_descriptor'),
 				'contactless_purchasecorrection' => array('order_id','txn_number'),
 				'contactless_refund' => array('order_id','amount','txn_number'),
 				'forcepost'=> array('order_id','cust_id','amount','pan','expdate','auth_code','crypt_type','dynamic_descriptor'),
 				'ind_refund' => array('order_id','cust_id', 'amount','pan','expdate', 'crypt_type','dynamic_descriptor', 'mcp_amount', 'mcp_currency_code'),
	 			'opentotals' => array('ecr_number'),
	 			'preauth' =>array('order_id','cust_id', 'amount', 'pan', 'expdate', 'crypt_type','dynamic_descriptor', 'wallet_indicator', 'mcp_amount', 'mcp_currency_code'),
	 			'purchase'=> array('order_id','cust_id', 'amount', 'pan', 'expdate', 'crypt_type','dynamic_descriptor', 'wallet_indicator', 'mcp_amount', 'mcp_currency_code'),
	 			'purchasecorrection' => array('order_id', 'txn_number', 'crypt_type', 'cust_id', 'dynamic_descriptor', 'mcp_currency_code'),
	 			'reauth' =>array('order_id','cust_id', 'amount', 'orig_order_id', 'txn_number', 'crypt_type', 'dynamic_descriptor'),
	 			'recur_update' => array('order_id','cust_id','pan','expdate','recur_amount','add_num_recurs','total_num_recurs','hold','terminate'),
	 			'refund' => array('order_id', 'amount', 'txn_number', 'crypt_type', 'cust_id', 'dynamic_descriptor', 'mcp_amount', 'mcp_currency_code'),
 				
 				//Encrypted
 				'enc_card_verification' => array('order_id','cust_id','enc_track2','device_type', 'crypt_type'),
 				'enc_forcepost' => array('order_id','cust_id','amount','enc_track2','device_type','auth_code','crypt_type','dynamic_descriptor'),
 				'enc_ind_refund' => array('order_id','cust_id','amount','enc_track2','device_type','crypt_type','dynamic_descriptor'),
 				'enc_preauth' => array('order_id','cust_id','amount','enc_track2','device_type','crypt_type','dynamic_descriptor'),
 				'enc_purchase' => array('order_id','cust_id','amount','enc_track2','device_type','crypt_type','dynamic_descriptor'),
 				'enc_res_add_cc' => array('cust_id','phone','email','note','enc_track2','device_type','crypt_type'),
 				'enc_res_update_cc' => array('data_key','cust_id','phone','email','note','enc_track2','device_type','crypt_type'),
 				'enc_track2_forcepost' => array('order_id','cust_id','amount','enc_track2','pos_code','device_type','auth_code','dynamic_descriptor'),
 				'enc_track2_ind_refund' => array('order_id','cust_id','amount','enc_track2','pos_code','device_type','dynamic_descriptor'),
 				'enc_track2_preauth' => array('order_id','cust_id','amount','enc_track2','pos_code','device_type','dynamic_descriptor'),
 				'enc_track2_purchase' => array('order_id','cust_id','amount','enc_track2','pos_code','device_type','dynamic_descriptor'),
 				
 				//Interac Online
	 			'idebit_purchase' =>array('order_id', 'cust_id', 'amount','idebit_track2','dynamic_descriptor'),
	 			'idebit_refund' =>array('order_id','amount','txn_number'),
 			
 				//Vault
 				'res_add_cc' => array('cust_id','phone','email','note','pan','expdate','crypt_type'),
				'res_add_token' => array('data_key','cust_id','phone','email','note','expdate','crypt_type'),
 				'res_card_verification_cc' => array('data_key','order_id', 'crypt_type', 'expdate'),
 				'res_cavv_preauth_cc' => array('data_key','order_id','cust_id','amount','cavv','crypt_type','dynamic_descriptor','expdate'),
 				'res_cavv_purchase_cc' => array('data_key','order_id','cust_id','amount','cavv','crypt_type','dynamic_descriptor','expdate'),
 				'res_delete' => array('data_key'),
 				'res_get_expiring' => array(),
 				'res_ind_refund_cc' => array('data_key','order_id','cust_id','amount','crypt_type','dynamic_descriptor'),
				'res_iscorporatecard' => array('data_key'),
 				'res_lookup_full' => array('data_key'),
				'res_lookup_masked' => array('data_key'),
 				'res_mpitxn' => array('data_key','xid','amount','MD','merchantUrl','accept','userAgent','expdate'),
 				'res_preauth_cc' => array('data_key','order_id','cust_id','amount','crypt_type','dynamic_descriptor','expdate'),
				'res_purchase_cc' => array('data_key','order_id','cust_id','amount','crypt_type','dynamic_descriptor','expdate'),
 				'res_temp_add' => array('pan','expdate','crypt_type','duration'),
 				'res_temp_tokenize' => array('order_id', 'txn_number', 'duration', 'crypt_type'),
				'res_tokenize_cc' => array('order_id','txn_number','cust_id','phone','email','note'),
				'res_update_cc' => array('data_key','cust_id','phone','email','note','pan','expdate','crypt_type'),
 				
 				//Track2
 				'track2_completion' => array('order_id', 'comp_amount','txn_number','pos_code','dynamic_descriptor'),
 				'track2_forcepost'=>array('order_id','cust_id', 'amount', 'track2','pan','expdate','pos_code','auth_code','dynamic_descriptor'),
				'track2_ind_refund' => array('order_id','amount','track2','pan','expdate','cust_id','pos_code','dynamic_descriptor'),
	 			'track2_preauth' => array('order_id','cust_id','amount','track2','pan','expdate','pos_code','dynamic_descriptor'),
	 			'track2_purchase' =>array('order_id','cust_id','amount','track2','pan','expdate','pos_code','dynamic_descriptor'),
	 			'track2_purchasecorrection' => array('order_id', 'txn_number'),
	 			'track2_refund' => array('order_id', 'amount', 'txn_number','dynamic_descriptor'),
 				
 				//VDotMe
 				'vdotme_completion' => array('order_id','comp_amount','txn_number','crypt_type','cust_id','dynamic_descriptor'),
 				'vdotme_getpaymentinfo' => array('callid'),
 				'vdotme_preauth' => array('order_id','amount','callid','crypt_type','cust_id','dynamic_descriptor'),
 				'vdotme_purchase' => array('order_id','amount','callid','crypt_type','cust_id','dynamic_descriptor'),
 				'vdotme_purchasecorrection' => array('order_id','txn_number','crypt_type','cust_id','dynamic_descriptor'),
 				'vdotme_reauth' => array('order_id','orig_order_id','txn_number','amount','crypt_type','cust_id','dynamic_descriptor'),
 				'vdotme_refund' => array('order_id','txn_number','amount','crypt_type','cust_id','dynamic_descriptor'),
 				
 				//MasterPass
	 			'paypass_send_shopping_cart' => array('subtotal', 'suppress_shipping_address'),
	 			'paypass_retrieve_checkout_data' => array('oauth_token', 'oauth_verifier', 'checkout_resource_url'),
	 			'paypass_purchase' => array('order_id', 'cust_id', 'amount', 'mp_request_token', 'crypt_type', 'dynamic_descriptor'),
	 			'paypass_cavv_purchase' => array('order_id', 'cavv', 'cust_id', 'amount', 'mp_request_token', 'crypt_type', 'dynamic_descriptor'),
	 			'paypass_preauth' => array('order_id', 'cust_id', 'amount', 'mp_request_token', 'crypt_type', 'dynamic_descriptor'),
	 			'paypass_cavv_preauth' => array('order_id', 'cavv', 'cust_id', 'amount', 'mp_request_token', 'crypt_type', 'dynamic_descriptor'),
	 			'paypass_txn' => array('xid', 'amount', 'mp_request_token', 'MD', 'merchantUrl', 'accept', 'userAgent'),
 				
 				//US ACH
	 			'us_ach_credit' => array('order_id','cust_id','amount'),
 				'us_ach_debit' => array('order_id','cust_id','amount'),
	 			'us_ach_fi_enquiry' => array('routing_num'),
	 			'us_ach_reversal' => array('order_id','txn_number'),
	 			
 				//US Basic
 				'us_batchclose' => array('ecr_number'),
 				'us_card_verification' => array('order_id','cust_id','pan','expdate'),
 				'us_cavv_preauth' => array('order_id','cust_id', 'amount', 'pan','expdate', 'cavv','crypt_type','dynamic_descriptor', 'wallet_indicator'),
 				'us_cavv_purchase'=> array('order_id','cust_id','amount','pan','expdate', 'cavv', 'commcard_invoice','commcard_tax_amount','crypt_type', 'dynamic_descriptor', 'wallet_indicator'),
 				'us_completion' => array('order_id', 'comp_amount','txn_number', 'crypt_type', 'commcard_invoice','commcard_tax_amount'),
 				'us_contactless_purchase' => array('order_id','cust_id','amount','track2','pan','expdate','commcard_invoice','commcard_tax_amount','pos_code','dynamic_descriptor'),
 				'us_contactless_purchasecorrection' => array('order_id','txn_number'),
 				'us_contactless_refund' => array('order_id','amount','txn_number'),
	 			'us_forcepost'=> array('order_id','cust_id','amount','pan','expdate','auth_code','crypt_type','dynamic_descriptor'),
 				'us_ind_refund' => array('order_id','cust_id', 'amount','pan','expdate', 'crypt_type','dynamic_descriptor'),
	 			'us_opentotals' => array('ecr_number'),
	 			'us_pinless_debit_purchase' => array('order_id','amount','pan','expdate','cust_id','presentation_type','intended_use','p_account_number'),
	 			'us_pinless_debit_refund' => array('order_id', 'amount', 'txn_number'),
	 			'us_preauth' => array('order_id','cust_id', 'amount', 'pan', 'expdate', 'crypt_type', 'dynamic_descriptor'),
	 			'us_purchase'=> array('order_id','cust_id', 'amount', 'pan', 'expdate', 'crypt_type', 'commcard_invoice','commcard_tax_amount','dynamic_descriptor'),
	 			'us_purchasecorrection' => array('order_id', 'txn_number', 'crypt_type'),
	 			'us_reauth' => array('order_id','cust_id','orig_order_id','txn_number','amount','crypt_type'),
	 			'us_recur_update' => array('order_id', 'cust_id', 'pan', 'expdate', 'recur_amount','add_num_recurs', 'total_num_recurs', 'hold', 'terminate','avs_street_number', 'avs_street_name', 'avs_zipcode'),
	 			'us_refund' => array('order_id', 'amount', 'txn_number', 'crypt_type'),
 				
 				//US Encrypted
 				'us_enc_card_verification' => array('order_id','cust_id','enc_track2','device_type'),
 				'us_enc_forcepost' => array('order_id','cust_id','amount','enc_track2','device_type','auth_code','crypt_type','dynamic_descriptor'),
 				'us_enc_ind_refund' => array('order_id','cust_id','amount','enc_track2','device_type','crypt_type','dynamic_descriptor'),
 				'us_enc_preauth' => array('order_id','cust_id','amount','enc_track2','device_type','crypt_type','dynamic_descriptor'),
 				'us_enc_purchase' => array('order_id','cust_id','amount','enc_track2','device_type','crypt_type','commcard_invoice','commcard_tax_amount','dynamic_descriptor'),
	 			'us_enc_res_add_cc' => array('cust_id','phone','email','note','enc_track2','device_type','crypt_type'),
	 			'us_enc_res_update_cc' => array('data_key','cust_id','phone','email','note','enc_track2','device_type','crypt_type'),
 				'us_enc_track2_forcepost' => array('order_id','cust_id','amount','enc_track2','pos_code','device_type','auth_code','dynamic_descriptor'),
 				'us_enc_track2_ind_refund' => array('order_id','cust_id','amount','enc_track2','pos_code','device_type','dynamic_descriptor'),
	 			'us_enc_track2_preauth' => array('order_id','cust_id','amount','enc_track2','pos_code','device_type','dynamic_descriptor'),
	 			'us_enc_track2_purchase' => array('order_id','cust_id','amount','enc_track2','pos_code','device_type','commcard_invoice','commcard_tax_amount','dynamic_descriptor'),
 				
 				//US Vault
 				'us_res_add_cc' => array('cust_id','phone','email','note','pan','expdate','crypt_type'),
 				'us_res_add_ach' => array('cust_id','phone','email','note'),
 				'us_res_add_pinless' => array('cust_id','phone','email','note','pan','expdate','presentation_type','p_account_number'),
 				'us_res_add_token' => array('cust_id','phone','email','note','data_key','crypt_type','expdate'),
 				'us_res_delete' => array('data_key'),
 				'us_res_get_expiring' => array(),
 				'us_res_ind_refund_ach' => array('data_key','order_id','cust_id','amount'),
 				'us_res_ind_refund_cc' => array('data_key','order_id','cust_id','amount','crypt_type','dynamic_descriptor'),
 				'us_res_iscorporatecard' => array('data_key'),
 				'us_res_lookup_full' => array('data_key'),
 				'us_res_lookup_masked' => array('data_key'),
 				'us_res_preauth_cc' => array('data_key','order_id','cust_id','amount','crypt_type','dynamic_descriptor'),
 				'us_res_purchase_ach' => array('data_key','order_id','cust_id','amount'),
 				'us_res_purchase_cc' => array('data_key','order_id','cust_id','amount','crypt_type','commcard_invoice','commcard_tax_amount','dynamic_descriptor'),
 				'us_res_purchase_pinless' => array('data_key','order_id','cust_id','amount','intended_use','p_account_number'),
 				'us_res_temp_add' => array('pan','expdate','duration','crypt_type'),	
 				'us_res_tokenize_cc' => array('order_id','txn_number','cust_id','phone','email','note'),
 				'us_res_update_cc' => array('data_key','cust_id','phone','email','note','pan','expdate','crypt_type'),
 				'us_res_update_ach' => array('data_key','cust_id','phone','email','note'),
 				'us_res_update_pinless' => array('data_key','cust_id','phone','email','note','pan','expdate','presentation_type','p_account_number'),
 				
 				//US Track2
	 			'us_track2_completion' => array('order_id', 'comp_amount','txn_number','pos_code', 'commcard_invoice','commcard_tax_amount'),
	 			'us_track2_forcepost'=>array('order_id','cust_id', 'amount', 'track2','pan','expdate','pos_code','auth_code','dynamic_descriptor'),
 				'us_track2_ind_refund' => array('order_id','amount','track2','pan','expdate','cust_id','pos_code','dynamic_descriptor'),
 				'us_track2_preauth' => array('order_id','cust_id','amount','track2','pan','expdate','pos_code','dynamic_descriptor'),
 				'us_track2_purchase' =>array('order_id','cust_id','amount','track2','pan','expdate', 'commcard_invoice','commcard_tax_amount','pos_code','dynamic_descriptor'),
	 			'us_track2_purchasecorrection' => array('order_id', 'txn_number'),
	 			'us_track2_refund' => array('order_id', 'amount', 'txn_number'),
 				
 				//MPI - Common CA and US
	 			'txn' =>array('xid', 'amount', 'pan', 'expdate','MD', 'merchantUrl','accept','userAgent','currency','recurFreq', 'recurEnd','install'),
	 			'acs'=> array('PaRes','MD'),
 				
 				//Group Transaction - Common CA and US
 				'group'=> array('order_id', 'txn_number', 'group_ref_num', 'group_type'),
 			
 				//Risk - CA only
 				'session_query' => array('order_id','session_id','service_type','event_type'),
 				'attribute_query' => array('order_id','policy_id','service_type'),
 			
	 			//Level 23
	 			'iscorporatecard' => array('pan','expdate'),
	 				
	 			//Amex General level23
	 			'axcompletion' => array('order_id', 'comp_amount', 'txn_number'),
	 			'axrefund' => array('order_id', 'amount', 'txn_number'),
	 			'axind_refund' => array('order_id', 'cust_id', 'amount', 'pan', 'expdate'),
	 			'axpurchasecorrection' => array('order_id', 'txn_number', 'crypt_type'),
	 			'axforcepost' => array('order_id', 'cust_id', 'amount', 'pan', 'expdate', 'auth_code'),
	 			
	 			//Amex Air & Rail level23
	 			'axracompletion' => array('order_id', 'comp_amount', 'txn_number'),
	 			'axrarefund' => array('order_id', 'amount', 'txn_number'),
	 			'axraind_refund' => array('order_id', 'cust_id', 'amount', 'pan', 'expdate'),
	 			'axrapurchasecorrection' => array('order_id', 'txn_number'),
	 			'axraforcepost' => array('order_id', 'cust_id', 'amount', 'pan', 'expdate', 'auth_code'),
	 				
	 			//Visa General, Air & Rail Level23
	 			'vscompletion' => array('order_id', 'comp_amount', 'txn_number', 'crypt_type', 'national_tax', 'merchant_vat_no', 'local_tax', 'customer_vat_no', 'cri', 'customer_code', 'invoice_number', 'local_tax_no'),
	 			'vsrefund' => array('order_id', 'amount', 'txn_number', 'crypt_type', 'national_tax', 'merchant_vat_no', 'local_tax', 'customer_vat_no', 'cri','customer_code', 'invoice_number', 'local_tax_no'),
	 			'vsind_refund' => array('order_id', 'cust_id', 'amount', 'pan', 'expdate', 'crypt_type', 'national_tax', 'merchant_vat_no', 'local_tax', 'customer_vat_no', 'cri','customer_code', 'invoice_number', 'local_tax_no'),
	 			'vsforcepost' => array('order_id', 'cust_id', 'amount', 'pan', 'expdate', 'auth_code', 'crypt_type', 'national_tax', 'merchant_vat_no', 'local_tax', 'customer_vat_no', 'cri','customer_code', 'invoice_number', 'local_tax_no'),
	 			'vspurchasecorrection' => array('order_id', 'txn_number', 'crypt_type'),
	 			'vscorpais' => array('order_id', 'txn_number'),
	 				
	 			//MasterCard General, Air and Rail Level23
	 			'mccompletion' => array('order_id', 'comp_amount', 'txn_number', 'merchant_ref_no', 'crypt_type'),
	 			'mcrefund' => array('order_id', 'amount', 'txn_number', 'merchant_ref_no', 'crypt_type'),
	 			'mcind_refund' => array('order_id', 'cust_id', 'amount', 'pan', 'expdate', 'merchant_ref_no', 'crypt_type'),
	 			'mcpurchasecorrection' => array('order_id', 'txn_number', 'crypt_type'),
	 			'mcforcepost' => array('order_id', 'cust_id', 'amount', 'pan', 'expdate', 'auth_code', 'merchant_ref_no', 'crypt_type'),
	 			'mccorpais' => array('order_id', 'txn_number')
			);

	var $txnArray;
	var $procCountryCode = "";
	var $testMode = "";
	var $isMPI = "";
	
	function mpgRequest($txn)
	{

 		if(is_array($txn))
   		{
    			$this->txnArray = $txn;
   		}
 		else
   		{
    			$temp[0]=$txn;
    			$this->txnArray=$temp;
   		}
	}
	
	function setProcCountryCode($countryCode)
	{
		$this->procCountryCode = ((strcmp(strtolower($countryCode), "us") >= 0) ? "_US" : "");
	}
	
	function getIsMPI() 
	{
		$txnType = $this->getTransactionType();
		
		if((strcmp($txnType, "txn") === 0) || (strcmp($txnType, "acs") === 0))
  		{
  			//$this->setIsMPI(true);
  			return true;
  		}
  		else
  		{
  			return false;
  		}
	}
	
	function setTestMode($state)
	{
		if($state === true)
		{
			$this->testMode = "_TEST";
		}
		else
		{
			$this->testMode = "";
		}
	}

	function getTransactionType()
	{
  		$jtmp=$this->txnArray;
  		$jtmp1=$jtmp[0]->getTransaction();
  		$jtmp2=array_shift($jtmp1);
  		return $jtmp2;
	}
	
	function getURL()
	{
		$g=new mpgGlobals();
  		$gArray=$g->getGlobals();
  		
  		$txnType = $this->getTransactionType();
  		
  		if(strpos($txnType, "us_") !== false)
  		{
  			$this->setProcCountryCode("US");
  		}
  		
  		if((strcmp($txnType, "txn") === 0) || (strcmp($txnType, "acs") === 0))
  		{
  			$this->isMPI = "_MPI";
  		}
  		else
  		{
  			$this->isMPI = "";
  		}
  		
  		$hostId = "MONERIS".$this->procCountryCode.$this->testMode."_HOST";
  		$fileId = "MONERIS".$this->procCountryCode.$this->isMPI."_FILE";
  		
  		$url =  $gArray['MONERIS_PROTOCOL']."://".
  				$gArray[$hostId].":".
  				$gArray['MONERIS_PORT'].
  				$gArray[$fileId];
  		
  		return $url;
	}

	var $xmlString;
	function toXML()
	{
 		$tmpTxnArray=$this->txnArray;
 		$txnArrayLen=count($tmpTxnArray); //total number of transactions

 		for($x=0;$x < $txnArrayLen;$x++)
 		{
			$txnObj=$tmpTxnArray[$x];
			$txn=$txnObj->getTransaction();

			$txnType=array_shift($txn);
			if (($this->procCountryCode === "_US") && (strpos($txnType, "us_") !== 0))
			{
				if((strcmp($txnType, "txn") === 0) || (strcmp($txnType, "acs") === 0) || (strcmp($txnType, "group") === 0))
				{
					//do nothing
				}
				else
				{
					$txnType = "us_".$txnType;
				}
			}
			$tmpTxnTypes=$this->txnTypes;
			$txnTypeArray=$tmpTxnTypes[$txnType];
			$txnTypeArrayLen=count($txnTypeArray); //length of a specific txn type

			$txnXMLString="";
			
			//for risk transactions only
			if((strcmp($txnType, "attribute_query") === 0) || (strcmp($txnType, "session_query") === 0))
			{
				$txnXMLString .="<risk>";
			}
				
			$txnXMLString .="<$txnType>";

			for($i=0;$i < $txnTypeArrayLen ;$i++)
			{
				//Will only add to the XML if the tag was passed in by merchant
				if(array_key_exists($txnTypeArray[$i], $txn))
                {
				 	$txnXMLString  .="<$txnTypeArray[$i]>"   //begin tag
									.$txn[$txnTypeArray[$i]] // data
									. "</$txnTypeArray[$i]>"; //end tag
				}
			}
			
   			$recur  = $txnObj->getRecur();
  			if($recur != null)
   			{
         		$txnXMLString .= $recur->toXML();
   			}
   			
			$avs  = $txnObj->getAvsInfo();
			if($avs != null)
			{
				$txnXMLString .= $avs->toXML();
			}

			$cvd  = $txnObj->getCvdInfo();
			if($cvd != null)
			{
				$txnXMLString .= $cvd->toXML();
			}

   			$custInfo = $txnObj->getCustInfo();
   			if($custInfo != null)
   			{
        		$txnXMLString .= $custInfo->toXML();
   			}
   			
   			$ach = $txnObj->getAchInfo();
   			if($ach != null)
   			{
   				$txnXMLString .= $ach->toXML();
   			}
   			
   			$convFee  = $txnObj->getConvFeeInfo();
   			if($convFee != null)
   			{
   				$txnXMLString .= $convFee->toXML();
   			}
   			
   			$sessionQuery  = $txnObj->getSessionAccountInfo(); 			
   			if($sessionQuery != null)
   			{
   				$txnXMLString .= $sessionQuery->toXML();
   			}
   			
   			$attributeQuery  = $txnObj->getAttributeAccountInfo();   			
   			if($attributeQuery != null)
   			{
   				$txnXMLString .= $attributeQuery->toXML();
   			}
   			
   			$level23Data = $txnObj->getLevel23Data();
   			if($level23Data != null)
   			{
   				$txnXMLString .= $level23Data->toXML();
   			}

   			$txnXMLString .="</$txnType>";
   			
   			//for risk transactions only
   			if((strcmp($txnType, "attribute_query") === 0) || (strcmp($txnType, "session_query") === 0))
   			{
   				$txnXMLString .="</risk>";
   			}
   			
   			$this->xmlString .=$txnXMLString;

 		}
 		return $this->xmlString;

	}//end toXML



}//end class


##################### mpgCustInfo ###########################################

class mpgCustInfo
{


 	var $level3template = array(
 							'cust_info' => array('email','instructions',
					                 			'billing' => array('first_name', 'last_name', 'company_name', 'address',
					                                    			 'city', 'province', 'postal_code', 'country',
					                                    			 'phone_number', 'fax','tax1', 'tax2','tax3',
					                                    			 'shipping_cost'),
					                 			'shipping' => array('first_name', 'last_name', 'company_name', 'address',
					                                   			  'city', 'province', 'postal_code', 'country',
					                                   			  'phone_number', 'fax','tax1', 'tax2', 'tax3',
					                                   			  'shipping_cost'),
					                 			'item' => array ('name', 'quantity', 'product_code', 'extended_amount')
                		)
           		);

 	var $level3data;
 	var $email;
 	var $instructions;

 	function mpgCustInfo($custinfo=0,$billing=0,$shipping=0,$items=0)
 	{
 		if($custinfo)
   		{
    			$this->setCustInfo($custinfo);
   		}
 	}

 	function setCustInfo($custinfo)
 	{
 		$this->level3data['cust_info'] = array($custinfo);
 	}

 	function setEmail($email)
	{
   		$this->email=$email;
   		$this->setCustInfo(array('email'=>$email,'instructions'=>$this->instructions));
 	}

 	function setInstructions($instructions)
	{
 		$this->instructions=$instructions;
 		
   		$this->setCustinfo(array('email'=>$this->email,'instructions'=>$instructions));
 	}

 	function setShipping($shipping)
 	{
  		$this->level3data['shipping']=array($shipping);
 	}

 	function setBilling($billing)
 	{
  		$this->level3data['billing']=array($billing);
 	}

 	function setItems($items)
 	{
   		if(!isset($this->level3data['item']))
		{
			$this->level3data['item']=array($items);
   	 	}
   		else
		{
			$index=count($this->level3data['item']);
			$this->level3data['item'][$index]=$items;
		}
 	}

 	function toXML()
 	{
  		$xmlString=$this->toXML_low($this->level3template,"cust_info");
  		return $xmlString;
 	}

 	function toXML_low($template,$txnType)
 	{
	$xmlString = "";
  	for($x=0;$x<count($this->level3data[$txnType]);$x++)
   	{
     	if($x>0)
     	{
      		$xmlString .="</$txnType><$txnType>";
     	}
     	$keys=array_keys($template);
     	for($i=0; $i < count($keys);$i++)
     	{
        	$tag=$keys[$i];

        	if(is_array($template[$keys[$i]]))
        	{
          		$data=$template[$tag];

          		if(! count($this->level3data[$tag]))
           		{
            		continue;
           		}
          		$beginTag="<$tag>";
          		$endTag="</$tag>";

          		$xmlString .=$beginTag;

          		#if(is_array($data))
           		{
            		$returnString=$this->toXML_low($data,$tag);
            		$xmlString .= $returnString;
           		}
          		$xmlString .=$endTag;
        	}
        	else
        	{
         		$tag=$template[$keys[$i]];
         		$beginTag="<$tag>";
         		$endTag="</$tag>";
         		$data=$this->level3data[$txnType][$x][$tag];
         		$xmlString .=$beginTag.$data .$endTag;
        	}

     	}//end inner for

    }//end outer for

    return $xmlString;
	}//end toXML_low

}//end class

##################### mpgRecur ##############################################

class mpgRecur{

	var $params;
	var $recurTemplate = array('recur_unit','start_now','start_date','num_recurs','period','recur_amount');

	function mpgRecur($params)
	{
		$this->params = $params;
		if( (! $this->params['period']) )
		{
			$this->params['period'] = 1;
		}
	}

	function toXML()
	{
		$xmlString = "";

		foreach($this->recurTemplate as $tag)
		{
			$xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
		}

		return "<recur>$xmlString</recur>";
	}

}//end class

##################### mpgAvsInfo ############################################

class mpgAvsInfo
{

    var $params;
    var $avsTemplate = array('avs_street_number','avs_street_name','avs_zipcode','avs_email','avs_hostname','avs_browser','avs_shiptocountry','avs_shipmethod','avs_merchprodsku','avs_custip','avs_custphone');

    function mpgAvsInfo($params)
    {
        $this->params = $params;
    }

    function toXML()
    {
        $xmlString = "";

        foreach($this->avsTemplate as $tag)
        {
        	//will only add to the XML the tags from the template that were also passed in by the merchant
			if(array_key_exists($tag, $this->params))
			{
				$xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
			}
        }

        return "<avs_info>$xmlString</avs_info>";
    }

}//end class

##################### mpgCvdInfo ############################################

class mpgCvdInfo
{

    var $params;
    var $cvdTemplate = array('cvd_indicator','cvd_value');

    function mpgCvdInfo($params)
    {
        $this->params = $params;
    }

    function toXML()
    {
        $xmlString = "";

        foreach($this->cvdTemplate as $tag)
        {
            $xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
        }

        return "<cvd_info>$xmlString</cvd_info>";
    }

}//end class

##################### mpgAchInfo ############################################

class mpgAchInfo
{

	var $params;
	var $achTemplate = array('sec','cust_first_name','cust_last_name',
			'cust_address1','cust_address2','cust_city',
			'cust_state','cust_zip','routing_num','account_num',
			'check_num','account_type','micr');

	function mpgAchInfo($params)
	{
		$this->params = $params;
	}

	function toXML()
	{
		$xmlString = "";

		foreach($this->achTemplate as $tag)
		{
			$xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
		}

		return "<ach_info>$xmlString</ach_info>";
	}

}//end class

##################### mpgConvFeeInfo ########################################

class mpgConvFeeInfo
{

	var $params;
	var $convFeeTemplate = array('convenience_fee');

	function mpgConvFeeInfo($params)
	{
		$this->params = $params;
	}

	function toXML()
	{
		$xmlString = "";

		foreach($this->convFeeTemplate as $tag)
		{
			$xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
		}

		return "<convfee_info>$xmlString</convfee_info>";
	}

}//end class

##################### mpgTransaction ########################################

class mpgTransaction
{

	var $txn;
	var $custInfo = null;
	var $recur = null;
	var $cvd = null;
	var $avs = null;
	var $convFee = null;
	var $ach = null;
	var $sessionAccountInfo = null;
	var $attributeAccountInfo = null;
	var $level23Data = null;

	function mpgTransaction($txn)
	{
		$this->txn=$txn;
	}

	function getCustInfo()
	{
		return $this->custInfo;
	}

	function setCustInfo($custInfo)
	{
		$this->custInfo = $custInfo;
		array_push($this->txn,$custInfo);
	}

	function getRecur()
	{
		return $this->recur;
	}

	function setRecur($recur)
	{
		$this->recur = $recur;
	}

	function getTransaction()
	{
		return $this->txn;
	}

	function getCvdInfo()
	{
		return $this->cvd;
	}

	function setCvdInfo($cvd)
	{
		$this->cvd = $cvd;
	}

	function getAvsInfo()
	{
		return $this->avs;
	}

	function setAvsInfo($avs)
	{
		$this->avs = $avs;
	}
	
	function getAchInfo()
	{
		return $this->ach;
	}
	
	function setAchInfo($ach)
	{
		$this->ach = $ach;
	}
	
	function setConvFeeInfo($convFee)
	{
		$this->convFee = $convFee;
	}
	
	function getConvFeeInfo()
	{
		return $this->convFee;
	}
	
	function setExpiryDate($expdate)
	{
		$this->expdate = $expdate;
	}
	
	function getExpiryDate()
	{
		return $this->expdate;
	}
	
	function getAttributeAccountInfo()
	{
		return $this->attributeAccountInfo;
	}
	
	function setAttributeAccountInfo($attributeAccountInfo)
	{
		$this->attributeAccountInfo = $attributeAccountInfo;
	}
	
	function getSessionAccountInfo()
	{
		return $this->sessionAccountInfo;
	}
	
	function setSessionAccountInfo($sessionAccountInfo)
	{
		$this->sessionAccountInfo = $sessionAccountInfo;
	}
	
	function setLevel23Data($level23Object)
	{
		$this->level23Data = $level23Object;
	}
	
	function getLevel23Data()
	{
		return $this->level23Data;
	}

}//end class mpgTransaction

###################### MpiHttpsPost #########################################

class MpiHttpsPost
{

	var $api_token;
	var $store_id;
	var $mpiRequest;
	var $mpiResponse;

	function MpiHttpsPost($storeid,$apitoken,$mpiRequestOBJ)
	{

		$this->store_id=$storeid;
		$this->api_token= $apitoken;
		$this->mpiRequest=$mpiRequestOBJ;
		$dataToSend=$this->toXML();

		$url = $this->mpiRequest->getURL();

  		$httpsPost= new httpsPost($url, $dataToSend);	
  		$response = $httpsPost->getHttpsResponse();

		if(!$response)
		{

			$response="<?xml version=\"1.0\"?>".
					"<MpiResponse>".
					"<type>null</type>".
					"<success>false</success>".
					"<message>null</message>".
					"<PaReq>null</PaReq>".
					"<TermUrl>null</TermUrl>".
					"<MD>null</MD>".
					"<ACSUrl>null</ACSUrl>".
					"<cavv>null</cavv>".
					"<PAResVerified>null</PAResVerified>".
					"</MpiResponse>";
		}

		$this->mpiResponse=new MpiResponse($response);
			
	}



	function getMpiResponse()
	{
		return $this->mpiResponse;

	}

	function toXML( )
	{

		$req=$this->mpiRequest ;
		$reqXMLString=$req->toXML();

		$xmlString ="<?xml version=\"1.0\"?>".
					"<MpiRequest>".
					"<store_id>$this->store_id</store_id>".
					"<api_token>$this->api_token</api_token>".
					$reqXMLString.
					"</MpiRequest>";

		return ($xmlString);

	}

}//end class mpiHttpsPost

############# MpiResponse ###################################################


class MpiResponse{

	var $responseData;

	var $p; //parser

	var $currentTag;
	var $receiptHash = array();
	var $currentTxnType;

	var $ACSUrl;

	function MpiResponse($xmlString)
	{

		$this->p = xml_parser_create();
		xml_parser_set_option($this->p,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($this->p,XML_OPTION_TARGET_ENCODING,"UTF-8");
		xml_set_object($this->p, $this);
		xml_set_element_handler($this->p,"startHandler","endHandler");
		xml_set_character_data_handler($this->p,"characterHandler");
		xml_parse($this->p,$xmlString);
		xml_parser_free($this->p);

	}//end of constructor

	//vbv start
	
	//To prevent Undefined Index Notices
	private function getMpiResponseValue($responseData, $value)
	{
		return (isset($responseData[$value]) ? $responseData[$value] : '');
	}

	function getMpiMessage()
	{
		return $this->getMpiResponseValue($this->responseData,'message');
	}


	function getMpiSuccess()
	{
		return $this->getMpiResponseValue($this->responseData,'success');
	}

	function getMpiPAResVerified()
	{
		return $this->getMpiResponseValue($this->responseData,'PAResVerified');
	}

	function getMpiAcsUrl()
	{
		return $this->getMpiResponseValue($this->responseData,'ACSUrl');
	}

	function getMpiPaReq()
	{
		return $this->getMpiResponseValue($this->responseData,'PaReq');
	}
	
	function getMpiTermUrl()
	{
		return $this->getMpiResponseValue($this->responseData,'TermUrl');
	}

	function getMpiMD()
	{
		return $this->getMpiResponseValue($this->responseData,'MD');
	}

	function getMpiCavv()
	{
		return $this->getMpiResponseValue($this->responseData,'cavv');
	}

	function getMpiEci()
	{
		return $this->getMpiResponseValue($this->responseData,'eci');
	}

	function getMpiResponseData()
	{
		return($this->responseData);
	}

	function getMpiPopUpWindow()
	{
		$popUpForm ='<html><head><title>Title for Page</title></head><SCRIPT LANGUAGE="Javascript" >' .
					"<!--
					function OnLoadEvent()
					{
						window.name='mainwindow';
						//childwin = window.open('about:blank','popupName','height=400,width=390,status=yes,dependent=no,scrollbars=yes,resizable=no');
						//document.downloadForm.target = 'popupName';
						document.downloadForm.submit();
					}
					-->
					</SCRIPT>" .
					'<body onload="OnLoadEvent()">
						<form name="downloadForm" action="' . $this->getMpiAcsUrl() .
						'" method="POST">
						<noscript>
						<br>
						<br>
						<center>
						<h1>Processing your 3-D Secure Transaction</h1>
						<h2>
						JavaScript is currently disabled or is not supported
						by your browser.<br>
						<h3>Please click on the Submit button to continue
						the processing of your 3-D secure
						transaction.</h3>
						<input type="submit" value="Submit">
						</center>
						</noscript>
						<input type="hidden" name="PaReq" value="' . $this->getMpiPaReq() . '">
						<input type="hidden" name="MD" value="' . $this->getMpiMD() . '">
						<input type="hidden" name="TermUrl" value="' . $this->getMpiTermUrl() .'">
						</form>
					</body>
					</html>';

		return $popUpForm;
	}


	function getMpiInLineForm()
	{

		$inLineForm ='<html><head><title>Title for Page</title></head><SCRIPT LANGUAGE="Javascript" >' .
					"<!--
					function OnLoadEvent()
					{
						document.downloadForm.submit();
					}
					-->
					</SCRIPT>" .
					'<body onload="OnLoadEvent()">
						<form name="downloadForm" action="' . $this->getMpiAcsUrl() .
						'" method="POST">
						<noscript>
						<br>
						<br>
						<center>
						<h1>Processing your 3-D Secure Transaction</h1>
						<h2>
						JavaScript is currently disabled or is not supported
						by your browser.<br>
						<h3>Please click on the Submit button to continue
						the processing of your 3-D secure
						transaction.</h3>
						<input type="submit" value="Submit">
						</center>
						</noscript>
						<input type="hidden" name="PaReq" value="' . $this->getMpiPaReq() . '">
						<input type="hidden" name="MD" value="' . $this->getMpiMD() . '">
						<input type="hidden" name="TermUrl" value="' . $this->getMpiTermUrl() .'">
						</form>
					</body>
					</html>';

		return $inLineForm;
	}

	function characterHandler($parser,$data)
	{
		if(isset($this->responseData[$this->currentTag]))
		{
			$this->responseData[$this->currentTag] .= trim($data);
		}
		else
		{
			$this->responseData[$this->currentTag] = trim($data);
		}
	}//end characterHandler

	function startHandler($parser,$name,$attrs)
	{
		$this->currentTag=$name;
	}


	function endHandler($parser,$name)
	{

	}


}//end class MpiResponse

################## mpiRequest ###############################################

class MpiRequest
{

	var $txnTypes = array(
						'txn' =>array('xid', 'amount', 'pan', 'expdate','MD', 'merchantUrl','accept','userAgent','currency','recurFreq', 'recurEnd','install'),
						'acs'=> array('PaRes','MD')
					);
	
	var $txnArray;
	var $procCountryCode = "";
	var $testMode = "";

	function MpiRequest($txn)
	{

		if(is_array($txn))
		{
			$this->txnArray = $txn;
		}
		else
		{
			$temp[0]=$txn;
			$this->txnArray=$temp;
		}
	}
	function setProcCountryCode($countryCode)
	{
		$this->procCountryCode = ((strcmp(strtolower($countryCode), "us") >= 0) ? "_US" : "");
	}
	
	function setTestMode($state)
	{
		if($state === true)
		{
			$this->testMode = "_TEST";
		}
		else
		{
			$this->testMode = "";
		}
	}
	
	function getURL()
	{
		$g=new mpgGlobals();
		$gArray=$g->getGlobals();
	
		//$txnType = $this->getTransactionType();
	
		$hostId = "MONERIS".$this->procCountryCode.$this->testMode."_HOST";
		$fileId = "MONERIS".$this->procCountryCode."_MPI_FILE";
	
		$url =  $gArray['MONERIS_PROTOCOL']."://".
				$gArray[$hostId].":".
				$gArray['MONERIS_PORT'].
				$gArray[$fileId];
	
		//echo "PostURL: " . $url;
	
		return $url;
	}
	
	function toXML()
	{
		$xmlString = "";
		$tmpTxnArray=$this->txnArray;
		$txnArrayLen=count($tmpTxnArray); //total number of transactions

		for($x=0;$x < $txnArrayLen;$x++)
		{
			$txnObj=$tmpTxnArray[$x];
			$txn=$txnObj->getTransaction();
	
			$txnType=array_shift($txn);
			$tmpTxnTypes=$this->txnTypes;
			$txnTypeArray=$tmpTxnTypes[$txnType];
			$txnTypeArrayLen=count($txnTypeArray); //length of a specific txn type
	
			$txnXMLString="";
			
			for($i=0;$i < $txnTypeArrayLen ;$i++)
			{
				//Will only add to the XML if the tag was passed in by merchant
				if(array_key_exists($txnTypeArray[$i], $txn))
                {
				 	$txnXMLString  .="<$txnTypeArray[$i]>"   //begin tag
									.$txn[$txnTypeArray[$i]] // data
									. "</$txnTypeArray[$i]>"; //end tag
				}
			}
		 
			$txnXMLString = "<$txnType>$txnXMLString";

			$txnXMLString .="</$txnType>";
	
			$xmlString .=$txnXMLString;
		}

		return $xmlString;

	}//end toXML

}//end class MpiRequest

################# mpiTransaction ############################################

class MpiTransaction
{
	var $txn;

	function MpiTransaction($txn)
	{
		$this->txn=$txn;
	}

	function getTransaction()
	{
		return $this->txn;
	}
}//end class MpiTransaction


###################### riskHttpsPost ########################################

class riskHttpsPost{

	var $api_token;
	var $store_id;
	var $riskRequest;
	var $riskResponse;

	function riskHttpsPost($storeid,$apitoken,$riskRequestOBJ)
	{

		$this->store_id=$storeid;
		$this->api_token= $apitoken;
		$this->riskRequest=$riskRequestOBJ;
		$dataToSend=$this->toXML();
	
		$url = $this->riskRequest->getURL();

  		$httpsPost= new httpsPost($url, $dataToSend);	
  		$response = $httpsPost->getHttpsResponse();

		if(!$response)
		{

			$response="<?xml version=\"1.0\"?><response><receipt>".
					"<ReceiptId>Global Error Receipt</ReceiptId>".
					"<ResponseCode>null</ResponseCode>".
					"<AuthCode>null</AuthCode><TransTime>null</TransTime>".
					"<TransDate>null</TransDate><TransType>null</TransType><Complete>false</Complete>".
					"<Message>null</Message><TransAmount>null</TransAmount>".
					"<CardType>null</CardType>".
					"<TransID>null</TransID><TimedOut>null</TimedOut>".
					"</receipt></response>";
		}

		//print "Got a xml response of: \n$response\n";
		$this->riskResponse=new riskResponse($response);

	}



	function getRiskResponse()
	{
		return $this->riskResponse;

	}

	function toXML( )
	{

		$req=$this->riskRequest ;
		$reqXMLString=$req->toXML();

		$xmlString ="<?xml version=\"1.0\"?>".
					"<request>".
					"<store_id>$this->store_id</store_id>".
					"<api_token>$this->api_token</api_token>".
					"<risk>".
					$reqXMLString.
					"</risk>".
					"</request>";

		return ($xmlString);

	}

}//end class riskHttpsPost



############# riskResponse ##################################################


class riskResponse{

	var $responseData;

	var $p; //parser

	var $currentTag;
	var $isResults;
	var $isRule;
	var $ruleName;
	var $results = array();
	var $rules = array();

	function riskResponse($xmlString)
	{

		$this->p = xml_parser_create();
		xml_parser_set_option($this->p,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($this->p,XML_OPTION_TARGET_ENCODING,"UTF-8");
		xml_set_object($this->p,$this);
		xml_set_element_handler($this->p,"startHandler","endHandler");
		xml_set_character_data_handler($this->p,"characterHandler");
		xml_parse($this->p,$xmlString);
		xml_parser_free($this->p);

	}//end of constructor


	function getRiskResponse()
	{
		return($this->responseData);
	}
	
	//To prevent Undefined Index Notices
	private function getMpgResponseValue($responseData, $value)
	{
		return (isset($responseData[$value]) ? $responseData[$value] : '');
	}

	//-----------------  Receipt Variables  ---------------------------------------------------------//

	function getReceiptId()
	{
		return $this->getMpgResponseValue($this->responseData,'ReceiptId');
	}

	function getResponseCode()
	{
		return $this->getMpgResponseValue($this->responseData,'ResponseCode');
	}

	function getMessage()
	{
		return $this->getMpgResponseValue($this->responseData,'Message');
	}

	function getResults()
	{
		return ($this->results);
	}

	function getRules()
	{
		return ($this->rules);
	}

	//-----------------  Parser Handlers  ---------------------------------------------------------//

	function characterHandler($parser,$data)
	{
		@$this->responseData[$this->currentTag] .=$data;

		if($this->isResults)
		{
			//print("\n".$this->currentTag."=".$data);
			$this->results[$this->currentTag] = $data;
			 
		}

		if($this->isRule)
		{

			if ($this->currentTag == "RuleName")
			{
				$this->ruleName=$data;
			}
			$this->rules[$this->ruleName][$this->currentTag] = $data;

		}
	}//end characterHandler


	function startHandler($parser,$name,$attrs)
	{
		$this->currentTag=$name;

		if($this->currentTag == "Result")
		{
			$this->isResults=1;
		}

		if($this->currentTag == "Rule")
		{
			$this->isRule=1;
		}
	} //end startHandler

	function endHandler($parser,$name)
	{
		$this->currentTag=$name;

		if($name == "Result")
		{
			$this->isResults=0;
		}

		if($this->currentTag == "Rule")
		{
			$this->isRule=0;
		}

		$this->currentTag="/dev/null";
	} //end endHandler



}//end class riskResponse


################## riskRequest ##############################################

class riskRequest{

	var $txnTypes =array(
						'session_query' => array('order_id','session_id','service_type','event_type'),
						'attribute_query' => array('order_id','policy_id','service_type'),
						'assert' => array('orig_order_id','activities_description','impact_description','confidence_description')
	);

	var $txnArray;
	var $procCountryCode = "";
	var $testMode = "";

	function riskRequest($txn)
	{
		if(is_array($txn))
		{
			$this->txnArray = $txn;
		}
		else
		{
			$temp[0]=$txn;
			$this->txnArray=$temp;
		}
	}
	
	function setProcCountryCode($countryCode)
	{
		$this->procCountryCode = ((strcmp(strtolower($countryCode), "us") >= 0) ? "_US" : "");
	}
	
	function setTestMode($state)
	{
		if($state === true)
		{
			$this->testMode = "_TEST";
		}
		else
		{
			$this->testMode = "";
		}
	}
	
	function getURL()
	{
		$g=new mpgGlobals();
		$gArray=$g->getGlobals();
	
		//$txnType = $this->getTransactionType();
	
		$hostId = "MONERIS".$this->procCountryCode.$this->testMode."_HOST";
		$fileId = "MONERIS".$this->procCountryCode."_FILE";
	
		$url =  $gArray['MONERIS_PROTOCOL']."://".
				$gArray[$hostId].":".
				$gArray['MONERIS_PORT'].
				$gArray[$fileId];
	
		//echo "PostURL: " . $url;
	
		return $url;
	}

	function toXML()
	{
		$xmlString = "";

		$tmpTxnArray=$this->txnArray;

		$txnArrayLen=count($tmpTxnArray); //total number of transactions
		for($x=0;$x < $txnArrayLen;$x++)
		{
			$txnObj=$tmpTxnArray[$x];
			$txn=$txnObj->getTransaction();

			$txnType=array_shift($txn);
			$tmpTxnTypes=$this->txnTypes;
			$txnTypeArray=$tmpTxnTypes[$txnType];
			$txnTypeArrayLen=count($txnTypeArray); //length of a specific txn type

			$txnXMLString="";
			for($i=0;$i < $txnTypeArrayLen ;$i++)
			{
				//Will only add to the XML if the tag was passed in by merchant
				if(array_key_exists($txnTypeArray[$i], $txn))
                {
				 	$txnXMLString  .="<$txnTypeArray[$i]>"   //begin tag
									.$txn[$txnTypeArray[$i]] // data
									. "</$txnTypeArray[$i]>"; //end tag
				}
			}

			$txnXMLString = "<$txnType>$txnXMLString";

			$sessionQuery  = $txnObj->getSessionAccountInfo();
			 
			if($sessionQuery != null)
			{
				$txnXMLString .= $sessionQuery->toXML();
			}

			$attributeQuery  = $txnObj->getAttributeAccountInfo();
	
			if($attributeQuery != null)
			{
				$txnXMLString .= $attributeQuery->toXML();
			}
	
			$txnXMLString .="</$txnType>";
	
			$xmlString .=$txnXMLString;
	
			return $xmlString;
		}

		return $xmlString;

	}//end toXML
}//end class

##################### mpgSessionAccountInfo #################################

class mpgSessionAccountInfo
{

	var $params;
	var $sessionAccountInfoTemplate = array('policy','account_login','password_hash','account_number','account_name',
											'account_email','account_telephone','pan','account_address_street1','account_address_street2','account_address_city',
											'account_address_state','account_address_country','account_address_zip','shipping_address_street1','shipping_address_street2','shipping_address_city',
											'shipping_address_state','shipping_address_country','shipping_address_zip','local_attrib_1','local_attrib_2','local_attrib_3','local_attrib_4',
											'local_attrib_5','transaction_amount','transaction_currency');

	function mpgSessionAccountInfo($params)
	{
		$this->params = $params;
	}

	function toXML()
	{
		$xmlString = "";
		foreach($this->sessionAccountInfoTemplate as $tag)
		{
			if(isset($this->params[$tag]))
			{
				$xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
			}
		}
		return "<session_account_info>$xmlString</session_account_info>";
	}

}//end class mpgSessionAccountInfo

##################### mpgAttributeAccountInfo ###############################

class mpgAttributeAccountInfo
{

	var $params;
	var $attributeAccountInfoTemplate = array('device_id','account_login','password_hash','account_number','account_name',
											'account_email','account_telephone','cc_number_hash','ip_address','ip_forwarded','account_address_street1','account_address_street2','account_address_city',
											'account_address_state','account_address_country','account_address_zip','shipping_address_street1','shipping_address_street2','shipping_address_city',
											'shipping_address_state','shipping_address_country','shipping_address_zip');

	function mpgAttributeAccountInfo($params)
	{
		$this->params = $params;
	}

	function toXML()
	{
		$xmlString = "";
		foreach($this->attributeAccountInfoTemplate as $tag)
		{
			if(isset($this->params[$tag]))
			{
				$xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
			}
		}

		return "<attribute_account_info>$xmlString</attribute_account_info>";
	}

}//end class


##################### riskTransaction #######################################

class riskTransaction{

	var $txn;
	var $attributeAccountInfo = null;
	var $sessionAccountInfo = null;

	function riskTransaction($txn)
	{
		$this->txn=$txn;
	}

	function getTransaction()
	{
		return $this->txn;
	}

	function getAttributeAccountInfo()
	{
		return $this->attributeAccountInfo;
	}
	
	function setAttributeAccountInfo($attributeAccountInfo)
	{
		$this->attributeAccountInfo = $attributeAccountInfo;
	}

	function getSessionAccountInfo()
	{
		return $this->sessionAccountInfo;
	}
	
	function setSessionAccountInfo($sessionAccountInfo)
	{
		$this->sessionAccountInfo = $sessionAccountInfo;
	}
}//end class RiskTransaction

/******************* AMEX Level23 *******************/
class mpgAxLevel23
{

	private $template = array	(
			'axlevel23' => array ('table1' => null, 'table2' => null, 'table3' => null)
	);

	private $data;

	public function __construct()
	{
		$this->data = $this->template;
	}

	public function setTable1($big04, $big05, $big10, axN1Loop $axN1Loop)
	{
		$this->data['axlevel23']['table1']['big04'] = $big04;
		$this->data['axlevel23']['table1']['big05'] = $big05;
		$this->data['axlevel23']['table1']['big10'] = $big10;
		$this->data['axlevel23']['table1']['n1_loop'] = $axN1Loop->getData();
	}

	public function setTable2(axIt1Loop $axIt1Loop)
	{
		$this->data['axlevel23']['table2']['it1_loop'] = $axIt1Loop->getData();
	}

	public function setTable3(axTxi $axTxi)
	{
		$this->data['axlevel23']['table3']['txi'] = $axTxi->getData();
	}

	public function toXML()
	{
		$xmlString=$this->toXML_low($this->data, "axlevel23");

		return $xmlString;
	}

	private function toXML_low($dataArray, $root)
	{
		$xmlRoot = "";

		foreach ($dataArray as $key => $value)
		{
			if(!is_numeric($key) && $value != "" && $value != null)
			{
				$xmlRoot .= "<$key>";
			}
			else if(is_numeric($key) && $key != "0")
			{
				$xmlRoot .= "</$root><$root>";
			}
				
			if(is_array($value))
			{
				$xmlRoot .= $this->toXML_low($value, $key);
			}
			else
			{
				$xmlRoot .= $value;
			}
				
			if(!is_numeric($key) && $value != "" && $value != null)
			{
				$xmlRoot .= "</$key>";
			}
		}

		return $xmlRoot;
	}

	public function getData()
	{
		return $this->data;
	}
}

class axN1Loop
{
	private $template = array (
			'n101' => null , 'n102' => null , 'n301' => null , 'n401' => null , 'n402' => null , 'n403' => null , 'ref' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setN1Loop($n101, $n102, $n301, $n401, $n402, $n403, axRef $ref)
	{
		$this->template['n101'] = $n101;
		$this->template['n102'] = $n102;
		$this->template['n301'] = $n301;
		$this->template['n401'] = $n401;
		$this->template['n402'] = $n402;
		$this->template['n403'] = $n403;
		$this->template['ref'] = $ref->getData();

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}

class axRef
{
	private $template = array (
			'ref01' => null , 'ref02' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setRef($ref01, $ref02)
	{
		$this->template['ref01'] = $ref01;
		$this->template['ref02'] = $ref02;

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}

class axIt1Loop
{
	private $template = array(
			'it102' => null, 'it103'  => null, 'it104' => null, 'it105' => null, 'it106s' => null , 'txi' => null , 'pam05' => null, 'pid05' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setIt1Loop($it102, $it103, $it104, $it105, axIt106s $it106s, axTxi $txi, $pam05, $pid05)
	{
		$this->template['it102'] = $it102;
		$this->template['it103'] = $it103;
		$this->template['it104'] = $it104;
		$this->template['it105'] = $it105;
		$this->template['it106s'] = $it106s->getData();
		$this->template['txi'] = $txi->getData();
		$this->template['pam05'] = $pam05;
		$this->template['pid05'] = $pid05;

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}

class axIt106s
{
	private $template = array (
			'it10618' => null, 'it10719' => null
	);

	private $data;

	public function __construct($it10618, $it10719)
	{
		$this->data = $this->template;
		$this->data['it10618'] = $it10618;
		$this->data['it10719'] = $it10719;
	}

	public function getData()
	{
		return $this->data;
	}
}

class axTxi
{
	private $template = array (
			'txi01' => null, 'txi02' => null, 'txi03' => null, 'txi06' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setTxi($txi01, $txi02, $txi03, $txi06)
	{
		$this->template['txi01'] = $txi01;
		$this->template['txi02'] = $txi02;
		$this->template['txi03'] = $txi03;
		$this->template['txi06'] = $txi06;

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}

}

class mpgAxRaLevel23
{

	private $template = array(
			'axralevel23' => array(
					'airline_process_id' => null,
					'invoice_batch' => null,
					'establishment_name' => null,
					'carrier_name' => null,
					'ticket_id' => null,
					'issue_city' => null,
					'establishment_state' => null,
					'number_in_party' => null,
					'passenger_name' => null,
					'taa_routing' => null,
					'carrier_code' => null,
					'fare_basis' => null,
					'document_type' => null,
					'doc_number' => null,
					'departure_date' => null
			)
	);

	private $data;

	public function __construct()
	{
		$this->data = $this->template;
	}

	public function setAxRaLevel23($airline_process_id, $invoice_batch, $establishment_name, $carrier_name, $ticket_id, $issue_city, $establishment_state, $number_in_party, $passenger_name, $taa_routing, $carrier_code, $fare_basis, $document_type, $doc_number, $departure_date)
	{
		$this->data['axralevel23']['airline_process_id'] = $airline_process_id;
		$this->data['axralevel23']['invoice_batch'] = $invoice_batch;
		$this->data['axralevel23']['establishment_name'] = $establishment_name;
		$this->data['axralevel23']['carrier_name'] = $carrier_name;
		$this->data['axralevel23']['ticket_id'] = $ticket_id;
		$this->data['axralevel23']['issue_city'] = $issue_city;
		$this->data['axralevel23']['establishment_state'] = $establishment_state;
		$this->data['axralevel23']['number_in_party'] = $number_in_party;
		$this->data['axralevel23']['passenger_name'] = $passenger_name;
		$this->data['axralevel23']['taa_routing'] = $taa_routing;
		$this->data['axralevel23']['carrier_code'] = $carrier_code;
		$this->data['axralevel23']['fare_basis'] = $fare_basis;
		$this->data['axralevel23']['document_type'] = $document_type;
		$this->data['axralevel23']['doc_number'] = $doc_number;
		$this->data['axralevel23']['departure_date'] = $departure_date;
	}

	public function setAirlineProcessId($airline_process_id)
	{
		$this->data['axralevel23']['airline_process_id'] = $airline_process_id;
	}

	public function setInvoiceBatch($invoice_batch)
	{
		$this->data['axralevel23']['invoice_batch'] = $invoice_batch;
	}

	public function setEstablishmentName($establishment_name)
	{
		$this->data['axralevel23']['establishment_name'] = $establishment_name;
	}

	public function setCarrierName($carrier_name)
	{
		$this->data['axralevel23']['carrier_name'] = $carrier_name;
	}

	public function setTicketId($ticket_id)
	{
		$this->data['axralevel23']['ticket_id'] = $ticket_id;
	}

	public function setIssueCity($issue_city)
	{
		$this->data['axralevel23']['issue_city'] = $issue_city;
	}

	public function setEstablishmentState($establishment_state)
	{
		$this->data['axralevel23']['establishment_state'] = $establishment_state;
	}

	public function setNumberInParty($number_in_party)
	{
		$this->data['axralevel23']['number_in_party'] = $number_in_party;
	}

	public function setPassengerName($passenger_name)
	{
		$this->data['axralevel23']['passenger_name'] = $passenger_name;
	}

	public function setTaaRouting($taa_routing)
	{
		$this->data['axralevel23']['taa_routing'] = $taa_routing;
	}

	public function setCarrierCode($carrier_code)
	{
		$this->data['axralevel23']['carrier_code'] = $carrier_code;
	}

	public function setFareBasis($fare_basis)
	{
		$this->data['axralevel23']['fare_basis'] = $fare_basis;
	}

	public function setDocumentType($document_type)
	{
		$this->data['axralevel23']['document_type'] = $document_type;
	}

	public function setDocNumber($doc_number)
	{
		$this->data['axralevel23']['doc_number'] = $doc_number;
	}

	public function setDepartureDate($departure_date)
	{
		$this->data['axralevel23']['departure_date'] = $departure_date;
	}

	public function toXML()
	{
		$xmlString=$this->toXML_low($this->data, "axralevel23");

		return $xmlString;
	}

	private function toXML_low($dataArray, $root)
	{
		$xmlRoot = "";

		foreach ($dataArray as $key => $value)
		{
			if(!is_numeric($key) && $value != "" && $value != null)
			{
				$xmlRoot .= "<$key>";
			}
			else if(is_numeric($key) && $key != "0")
			{
				$xmlRoot .= "</$root><$root>";
			}

			if(is_array($value))
			{
				$xmlRoot .= $this->toXML_low($value, $key);
			}
			else
			{
				$xmlRoot .= $value;
			}

			if(!is_numeric($key) && $value != "" && $value != null)
			{
				$xmlRoot .= "</$key>";
			}
		}

		return $xmlRoot;
	}

	public function getData()
	{
		return $this->data;
	}
}//end class

/******************* Visa Level23 *******************/
class mpgVsLevel23
{

	private $template = array(
			'corpai' => null,
			'corpas' => null,
			'vspurcha' => null,
			'vspurchl' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = $this->template;
	}

	public function setVsCorpa(vsCorpai $vsCorpai, vsCorpas $vsCorpas)
	{
		$this->data['vspurcha'] = null;
		$this->data['vspurchal'] = null;

		$this->data['corpai'] = $vsCorpai->getData();
		$this->data['corpas'] = $vsCorpas->getData();
	}

	public function setVsPurch(vsPurcha $vsPurcha, vsPurchl $vsPurchl)
	{
		$this->data['corpai'] = null;
		$this->data['corpas'] = null;

		$this->data['vspurcha'] = $vsPurcha->getData();
		$this->data['vspurchl'] = $vsPurchl->getData();
	}

	public function toXML()
	{
		$xmlString=$this->toXML_low($this->data, "0");

		return $xmlString;
	}

	private function toXML_low($dataArray, $root)
	{
		$xmlRoot = "";

		foreach ($dataArray as $key => $value)
		{
			if(!is_numeric($key) && $value != "" && $value != null)
			{
				$xmlRoot .= "<$key>";
			}
			else if(is_numeric($key) && $key != "0")
			{
				$xmlRoot .= "</$root><$root>";
			}

			if(is_array($value))
			{
				$xmlRoot .= $this->toXML_low($value, $key);
			}
			else
			{
				$xmlRoot .= $value;
			}

			if(!is_numeric($key) && $value != "" && $value != null)
			{
				$xmlRoot .= "</$key>";
			}
		}

		return $xmlRoot;
	}

	public function getData()
	{
		return $this->data;
	}
}//end class

class vsPurcha
{

	private $template = array(
			'buyer_name' => null,
			'local_tax_rate' => null,
			'duty_amount' => null,
			'discount_treatment' => null,
			'discount_amt' => null,
			'freight_amount' => null,
			'ship_to_pos_code' => null,
			'ship_from_pos_code' => null,
			'des_cou_code' => null,
			'vat_ref_num' => null,
			'tax_treatment' => null,
			'gst_hst_freight_amount' => null,
			'gst_hst_freight_rate' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = $this->template;
	}

	public function setVsPurcha($buyer_name, $local_tax_rate, $duty_amount, $discount_treatment, $discount_amt, $freight_amount, $ship_to_pos_code, $ship_from_pos_code, $des_cou_code, $vat_ref_num, $tax_treatment, $gst_hst_freight_amount, $gst_hst_freight_rate)
	{
		$this->data['buyer_name'] = $buyer_name;
		$this->data['local_tax_rate'] = $local_tax_rate;
		$this->data['duty_amount'] = $duty_amount;
		$this->data['discount_treatment'] = $discount_treatment;
		$this->data['discount_amt'] = $discount_amt;
		$this->data['freight_amount'] = $freight_amount;
		$this->data['ship_to_pos_code'] = $ship_to_pos_code;
		$this->data['ship_from_pos_code'] = $ship_from_pos_code;
		$this->data['des_cou_code'] = $des_cou_code;
		$this->data['vat_ref_num'] = $vat_ref_num;
		$this->data['tax_treatment'] = $tax_treatment;
		$this->data['gst_hst_freight_amount'] = $gst_hst_freight_amount;
		$this->data['gst_hst_freight_rate'] = $gst_hst_freight_rate;
	}

	public function setBuyerName($buyer_name)
	{
		$this->data['buyer_name'] = $buyer_name;
	}

	public function setLocalTaxRate($local_tax_rate)
	{
		$this->data['local_tax_rate'] = $local_tax_rate;
	}

	public function setDutyAmount($duty_amount)
	{
		$this->data['duty_amount'] = $duty_amount;
	}

	public function setDiscountTreatment($discount_treatment)
	{
		$this->data['discount_treatment'] = $discount_treatment;
	}

	public function setDiscountAmt($discount_amt)
	{
		$this->data['discount_amt'] = $discount_amt;
	}

	public function setFreightAmount($freight_amount)
	{
		$this->data['freight_amount'] = $freight_amount;
	}

	public function setShipToPostalCode($ship_to_pos_code)
	{
		$this->data['ship_to_pos_code'] = $ship_to_pos_code;
	}

	public function setShipFromPostalCode($ship_from_pos_code)
	{
		$this->data['ship_from_pos_code'] = $ship_from_pos_code;
	}

	public function setDesCouCode($des_cou_code)
	{
		$this->data['des_cou_code'] = $des_cou_code;
	}

	public function setVatRefNum($vat_ref_num)
	{
		$this->data['vat_ref_num'] = $vat_ref_num;
	}

	public function setTaxTreatment($tax_treatment)
	{
		$this->data['tax_treatment'] = $tax_treatment;
	}

	public function setGstHstFreightAmount($gst_hst_freight_amount)
	{
		$this->data['gst_hst_freight_amount'] = $gst_hst_freight_amount;
	}

	public function setGstHstFreightRate($gst_hst_freight_rate)
	{
		$this->data['gst_hst_freight_rate'] = $gst_hst_freight_rate;
	}

	public function getData()
	{
		return $this->data;
	}
}//end class

class vsPurchl
{

	private $template = array(
			'item_com_code' => null,
			'product_code' => null,
			'item_description' => null,
			'item_quantity' => null,
			'item_uom' => null,
			'unit_cost' => null,
			'vat_tax_amt' => null,
			'vat_tax_rate' => null,
			'discount_treatment' => null,
			'discount_amt' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setVsPurchl($item_com_code, $product_code, $item_description, $item_quantity, $item_uom, $unit_cost, $vat_tax_amt, $vat_tax_rate, $discount_treatment, $discount_amt)
	{
		$this->template['item_com_code'] = $item_com_code;
		$this->template['product_code'] = $product_code;
		$this->template['item_description'] = $item_description;
		$this->template['item_quantity'] = $item_quantity;
		$this->template['item_uom'] = $item_uom;
		$this->template['unit_cost'] = $unit_cost;
		$this->template['vat_tax_amt'] = $vat_tax_amt;
		$this->template['vat_tax_rate'] = $vat_tax_rate;
		$this->template['discount_treatment'] = $discount_treatment;
		$this->template['discount_amt'] = $discount_amt;

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}//end class

class vsCorpai
{

	private $template = array(
			'ticket_number' => null,
			'passenger_name1' => null,
			'total_fee' => null,
			'exchange_ticket_number' => null,
			'exchange_ticket_amount' => null,
			'travel_agency_code' => null,
			'travel_agency_name' => null,
			'internet_indicator' => null,
			'electronic_ticket_indicator' => null,
			'vat_ref_num' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = $this->template;
	}

	public function setCorpai($ticket_number, $passenger_name1, $total_fee, $exchange_ticket_number, $exchange_ticket_amount, $travel_agency_code, $travel_agency_name, $internet_indicator, $electronic_ticket_indicator, $vat_ref_num)
	{
		$this->data['ticket_number'] = $ticket_number;
		$this->data['passenger_name1'] = $passenger_name1;
		$this->data['total_fee'] = $total_fee;
		$this->data['exchange_ticket_number'] = $exchange_ticket_number;
		$this->data['exchange_ticket_amount'] = $exchange_ticket_amount;
		$this->data['travel_agency_code'] = $travel_agency_code;
		$this->data['travel_agency_name'] = $travel_agency_name;
		$this->data['internet_indicator'] = $internet_indicator;
		$this->data['electronic_ticket_indicator'] = $electronic_ticket_indicator;
		$this->data['vat_ref_num'] = $vat_ref_num;
	}

	public function setTicketNumber($ticket_number)
	{
		$this->data['ticket_number'] = $ticket_number;
	}

	public function setPassengerName1($passenger_name1)
	{
		$this->data['passenger_name1'] = $passenger_name1;
	}

	public function setTotalFee($total_fee)
	{
		$this->data['total_fee'] = $total_fee;
	}

	public function setExchangeTicketNumber($exchange_ticket_number)
	{
		$this->data['exchange_ticket_number'] = $exchange_ticket_number;
	}

	public function setExchangeTicketAmount($exchange_ticket_amount)
	{
		$this->data['exchange_ticket_amount'] = $exchange_ticket_amount;
	}

	public function setTravelAgencyCode($travel_agency_code)
	{
		$this->data['travel_agency_code'] = $travel_agency_code;
	}

	public function setTravelAgencyName($travel_agency_name)
	{
		$this->data['travel_agency_name'] = $travel_agency_name;
	}

	public function setInternetIndicator($internet_indicator)
	{
		$this->data['internet_indicator'] = $internet_indicator;
	}

	public function setElectronicTicketIndicator($electronic_ticket_indicator)
	{
		$this->data['electronic_ticket_indicator'] = $electronic_ticket_indicator;
	}

	public function setVatRefNum($vat_ref_num)
	{
		$this->data['vat_ref_num'] = $vat_ref_num;
	}

	public function getData()
	{
		return $this->data;
	}
}//end class

class vsCorpas
{

	private $template = array(
			'conjunction_ticket_number' => null,
			'trip_leg_info' => null,
			'control_id' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setCorpas($conjunction_ticket_number, vsTripLegInfo $vsTripLegInfo, $control_id)
	{
		$this->template['conjunction_ticket_number'] = $conjunction_ticket_number;
		$this->template['trip_leg_info'] = $vsTripLegInfo->getData();
		$this->template['control_id'] = $control_id;

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}//end class

class vsTripLegInfo
{

	private $template = array(
			'coupon_number' => null,
			'carrier_code1' => null,
			'flight_number' => null,
			'service_class' => null,
			'orig_city_airport_code' => null,
			'stop_over_code' => null,
			'dest_city_airport_code' => null,
			'fare_basis_code' => null,
			'departure_date1' => null,
			'departure_time' => null,
			'arrival_time' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setTripLegInfo($coupon_number, $carrier_code1, $flight_number, $service_class, $orig_city_airport_code, $stop_over_code, $dest_city_airport_code, $fare_basis_code, $departure_date1, $departure_time, $arrival_time)
	{
		$this->template['coupon_number'] = $coupon_number;
		$this->template['carrier_code1'] = $carrier_code1;
		$this->template['flight_number'] = $flight_number;
		$this->template['service_class'] = $service_class;
		$this->template['orig_city_airport_code'] = $orig_city_airport_code;
		$this->template['stop_over_code'] = $stop_over_code;
		$this->template['dest_city_airport_code'] = $dest_city_airport_code;
		$this->template['fare_basis_code'] = $fare_basis_code;
		$this->template['departure_date1'] = $departure_date1;
		$this->template['departure_time'] = $departure_time;
		$this->template['arrival_time'] = $arrival_time;

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}//end class

/**************** MasterCard Level23 ****************/

class mpgMcLevel23
{

	private $template = array(
			'mccorpac' => null,
			'mccorpai' => null,
			'mccorpas' => null,
			'mccorpal' => null,
			'mccorpar' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = $this->template;
	}

	public function setMcCorpac(mcCorpac $mcCorpac)
	{
		$this->data['mccorpac'] = $mcCorpac->getData();
	}

	public function setMcCorpai(mcCorpai $mcCorpai)
	{
		$this->data['mccorpai'] = $mcCorpai->getData();
	}

	public function setMcCorpas(mcCorpas $mcCorpas)
	{
		$this->data['mccorpas'] = $mcCorpas->getData();
	}

	public function setMcCorpal(mcCorpal $mcCorpal)
	{
		$this->data['mccorpal'] = $mcCorpal->getData();
	}

	public function setMcCorpar(mcCorpar $mcCorpar)
	{
		$this->data['mccorpar'] = $mcCorpar->getData();
	}

	public function toXML()
	{
		$xmlString=$this->toXML_low($this->data, "0");

		return $xmlString;
	}

	private function toXML_low($dataArray, $root)
	{
		$xmlRoot = "";

		foreach ($dataArray as $key => $value)
		{
			if(!is_numeric($key) && $value != "" && $value != null)
			{
				$xmlRoot .= "<$key>";
			}
			else if(is_numeric($key) && $key != "0")
			{
				$xmlRoot .= "</$root><$root>";
			}

			if(is_array($value))
			{
				$xmlRoot .= $this->toXML_low($value, $key);
			}
			else
			{
				$xmlRoot .= $value;
			}

			if(!is_numeric($key) && $value != "" && $value != null)
			{
				$xmlRoot .= "</$key>";
			}
		}

		return $xmlRoot;
	}

	public function getData()
	{
		return $this->data;
	}
}//end class


class mcCorpac
{

	private $template = array(
			'customer_code1' => null,
			'additional_card_acceptor_data' => null,
			'austin_tetra_number' => null,
			'naics_code' => null,
			'card_acceptor_type' => null,
			'card_acceptor_tax_id' => null,
			'corporation_vat_number' => null,
			'card_acceptor_reference_number' => null,
			'freight_amount1' => null,
			'duty_amount1' => null,
			'ship_to_pos_code' => null,
			'destination_province_code' => null,
			'destination_country_code' => null,
			'ship_from_pos_code' => null,
			'order_date' => null,
			'card_acceptor_vat_number' => null,
			'customer_vat_number' => null,
			'unique_invoice_number' => null,
			'commodity_code' => null,
			'authorized_contact_name' => null,
			'authorized_contact_phone' => null,
			'tax' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = $this->template;
	}

	public function setMcCorpac($customer_code1, $additional_card_acceptor_data, $austin_tetra_number, $naics_code, $card_acceptor_type, $card_acceptor_tax_id, $corporation_vat_number, $card_acceptor_reference_number, $freight_amount1, $duty_amount1, $ship_to_pos_code, $destination_province_code, $destination_country_code, $ship_from_pos_code, $order_date, $card_acceptor_vat_number, $customer_vat_number, $unique_invoice_number, $commodity_code, $authorized_contact_name, $authorized_contact_phone, mcTax $mctax)
	{
		$this->data['customer_code1'] = $customer_code1;
		$this->data['additional_card_acceptor_data'] = $additional_card_acceptor_data;
		$this->data['austin_tetra_number'] = $austin_tetra_number;
		$this->data['naics_code'] = $naics_code;
		$this->data['card_acceptor_type'] = $card_acceptor_type;
		$this->data['card_acceptor_tax_id'] = $card_acceptor_tax_id;
		$this->data['corporation_vat_number'] = $corporation_vat_number;
		$this->data['card_acceptor_reference_number'] = $card_acceptor_reference_number;
		$this->data['freight_amount1'] = $freight_amount1;
		$this->data['duty_amount1'] = $duty_amount1;
		$this->data['ship_to_pos_code'] = $ship_to_pos_code;
		$this->data['destination_province_code'] = $destination_province_code;
		$this->data['destination_country_code'] = $destination_country_code;
		$this->data['ship_from_pos_code'] = $ship_from_pos_code;
		$this->data['order_date'] = $order_date;
		$this->data['card_acceptor_vat_number'] = $card_acceptor_vat_number;
		$this->data['customer_vat_number'] = $customer_vat_number;
		$this->data['unique_invoice_number'] = $unique_invoice_number;
		$this->data['commodity_code'] = $commodity_code;
		$this->data['authorized_contact_name'] = $authorized_contact_name;
		$this->data['authorized_contact_phone'] = $authorized_contact_phone;
		$this->data['tax'] = $mctax->getData();
	}

	public function setCustomerCode1($customer_code1)
	{
		$this->data['customer_code1'] = $customer_code1;
	}

	public function setAdditionalCardAcceptorData($additional_card_acceptor_data)
	{
		$this->data['additional_card_acceptor_data'] = $additional_card_acceptor_data;
	}

	public function setAustinTetraNumber($austin_tetra_number)
	{
		$this->data['austin_tetra_number'] = $austin_tetra_number;
	}

	public function setNaicsCode($naics_code)
	{
		$this->data['naics_code'] = $naics_code;
	}

	public function setCardAcceptorType($card_acceptor_type)
	{
		$this->data['card_acceptor_type'] = $card_acceptor_type;
	}

	public function setCardAcceptorTaxTd($card_acceptor_tax_id)
	{
		$this->data['card_acceptor_tax_id'] = $card_acceptor_tax_id;
	}

	public function setCorporationVatNumber($corporation_vat_number)
	{
		$this->data['corporation_vat_number'] = $corporation_vat_number;
	}

	public function setCardAcceptorReferenceNumber($card_acceptor_reference_number)
	{
		$this->data['card_acceptor_reference_number'] = $card_acceptor_reference_number;
	}

	public function setFreightAmount1($freight_amount1)
	{
		$this->data['freight_amount1'] = $freight_amount1;
	}

	public function setDutyAmount1($duty_amount1)
	{
		$this->data['duty_amount1'] = $duty_amount1;
	}

	public function setShipToPosCode($ship_to_pos_code)
	{
		$this->data['ship_to_pos_code'] = $ship_to_pos_code;
	}

	public function setDestinationProvinceCode($destination_province_code)
	{
		$this->data['destination_province_code'] = $destination_province_code;
	}

	public function setDestinationCountryCode($destination_country_code)
	{
		$this->data['destination_country_code'] = $destination_country_code;
	}

	public function setShipFromPosCode($ship_from_pos_code)
	{
		$this->data['ship_from_pos_code'] = $ship_from_pos_code;
	}

	public function setOrderDate($order_date)
	{
		$this->data['order_date'] = $order_date;
	}

	public function setCardAcceptorVatNumber($card_acceptor_vat_number)
	{
		$this->data['card_acceptor_vat_number'] = $card_acceptor_vat_number;
	}

	public function setCustomerVatNumber($customer_vat_number)
	{
		$this->data['customer_vat_number'] = $customer_vat_number;
	}

	public function setUniqueInvoiceNumber($unique_invoice_number)
	{
		$this->data['unique_invoice_number'] = $unique_invoice_number;
	}

	public function setCommodityCode($commodity_code)
	{
		$this->data['commodity_code'] = $commodity_code;
	}

	public function setAuthorizedContactName($authorized_contact_name)
	{
		$this->data['authorized_contact_name'] = $authorized_contact_name;
	}

	public function setAuthorizedContactPhone($authorized_contact_phone)
	{
		$this->data['authorized_contact_phone'] = $authorized_contact_phone;
	}

	public function setTax(mcTax $mcTax)
	{
		$this->data['tax'] = $mcTax->getData();
	}

	public function getData()
	{
		return $this->data;
	}
}//end class


class mcCorpai
{

	private $template = array(
			'passenger_name1' => null,
			'ticket_number1' => null,
			'issuing_carrier' => null,
			'customer_code1' => null,
			'issue_date' => null,
			'travel_agency_code' => null,
			'travel_agency_name' => null,
			'total_fare' => null,
			'total_fee' => null,
			'total_taxes' => null,
			'commodity_code' => null,
			'restricted_ticket_indicator' => null,
			'exchange_ticket_amount' => null,
			'exchange_fee_amount' => null,
			'travel_authorization_code' => null,
			'iata_client_code' => null,
			'tax' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = $this->template;
	}

	public function setMcCorpai($passenger_name1, $ticket_number1, $issuing_carrier, $customer_code1, $issue_date, $travel_agency_code, $travel_agency_name, $total_fare, $total_fee, $total_taxes, $commodity_code, $restricted_ticket_indicator, $exchange_ticket_amount, $exchange_fee_amount, $travel_authorization_code, $iata_client_code, mcTax $mctax)
	{
		$this->data['passenger_name1'] = $passenger_name1;
		$this->data['ticket_number1'] = $ticket_number1;
		$this->data['issuing_carrier'] = $issuing_carrier;
		$this->data['customer_code1'] = $customer_code1;
		$this->data['issue_date'] = $issue_date;
		$this->data['travel_agency_code'] = $travel_agency_code;
		$this->data['travel_agency_name'] = $travel_agency_name;
		$this->data['total_fare'] = $total_fare;
		$this->data['total_fee'] = $total_fee;
		$this->data['total_taxes'] = $total_taxes;
		$this->data['commodity_code'] = $commodity_code;
		$this->data['restricted_ticket_indicator'] = $restricted_ticket_indicator;
		$this->data['exchange_ticket_amount'] = $exchange_ticket_amount;
		$this->data['exchange_fee_amount'] = $exchange_fee_amount;
		$this->data['travel_authorization_code'] = $travel_authorization_code;
		$this->data['iata_client_code'] = $iata_client_code;
		$this->data['tax'] = $mctax->getData();
	}

	public function setPassengerName1($passenger_name1)
	{
		$this->data['passenger_name1'] = $passenger_name1;
	}

	public function setTicketNumber1($ticket_number1)
	{
		$this->data['ticket_number1'] = $ticket_number1;
	}

	public function setIssuingCarrier($issuing_carrier)
	{
		$this->data['issuing_carrier'] = $issuing_carrier;
	}

	public function setCustomerCode1($customer_code1)
	{
		$this->data['customer_code1'] = $customer_code1;
	}

	public function setIssueDate($issue_date)
	{
		$this->data['issue_date'] = $issue_date;
	}

	public function setTravelAgencyCode($travel_agency_code)
	{
		$this->data['travel_agency_code'] = $travel_agency_code;
	}

	public function setTravelAgencyName($travel_agency_name)
	{
		$this->data['travel_agency_name'] = $travel_agency_name;
	}

	public function setTotalFare($total_fare)
	{
		$this->data['total_fare'] = $total_fare;
	}

	public function setTotalFee($total_fee)
	{
		$this->data['total_fee'] = $total_fee;
	}

	public function setTotalTaxes($total_taxes)
	{
		$this->data['total_taxes'] = $total_taxes;
	}

	public function setCommodityCode($commodity_code)
	{
		$this->data['commodity_code'] = $commodity_code;
	}

	public function setRestrictedTicketIndicator($restricted_ticket_indicator)
	{
		$this->data['restricted_ticket_indicator'] = $restricted_ticket_indicator;
	}

	public function setExchangeTicketAmount($exchange_ticket_amount)
	{
		$this->data['exchange_ticket_amount'] = $exchange_ticket_amount;
	}

	public function setExchangeFeeAmount($exchange_fee_amount)
	{
		$this->data['exchange_fee_amount'] = $exchange_fee_amount;
	}

	public function setTravelAuthorizationCode($travel_authorization_code)
	{
		$this->data['travel_authorization_code'] = $travel_authorization_code;
	}

	public function setIataClientCode($iata_client_code)
	{
		$this->data['iata_client_code'] = $iata_client_code;
	}

	public function setTax(mcTax $mcTax)
	{
		$this->data['tax'] = $mcTax->getData();
	}


	public function getData()
	{
		return $this->data;
	}
}//end class


class mcCorpas
{

	private $template = array(
			'travel_date' => null,
			'carrier_code1' => null,
			'service_class' => null,
			'orig_city_airport_code' => null,
			'dest_city_airport_code' => null,
			'stop_over_code' => null,
			'conjunction_ticket_number1' => null,
			'exchange_ticket_number' => null,
			'coupon_number1' => null,
			'fare_basis_code1' => null,
			'flight_number' => null,
			'departure_time' => null,
			'arrival_time' => null,
			'fare' => null,
			'fee' => null,
			'taxes' => null,
			'endorsement_restrictions' => null,
			'tax' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setMcCorpas($travel_date, $carrier_code1, $service_class, $orig_city_airport_code, $dest_city_airport_code, $stop_over_code, $conjunction_ticket_number1, $exchange_ticket_number1, $coupon_number1, $fare_basis_code1, $flight_number, $departure_time, $arrival_time, $fare, $fee, $taxes, $endorsement_restrictions, mcTax $mcTax)
	{
		$this->template['travel_date'] = $travel_date;
		$this->template['carrier_code1'] = $carrier_code1;
		$this->template['service_class'] = $service_class;
		$this->template['orig_city_airport_code'] = $orig_city_airport_code;
		$this->template['dest_city_airport_code'] = $dest_city_airport_code;
		$this->template['stop_over_code'] = $stop_over_code;
		$this->template['conjunction_ticket_number1'] = $conjunction_ticket_number1;
		$this->template['exchange_ticket_number'] = $exchange_ticket_number1;
		$this->template['coupon_number1'] = $coupon_number1;
		$this->template['fare_basis_code1'] = $fare_basis_code1;
		$this->template['flight_number'] = $flight_number;
		$this->template['departure_time'] = $departure_time;
		$this->template['arrival_time'] = $arrival_time;
		$this->template['fare'] = $fare;
		$this->template['fee'] = $fee;
		$this->template['taxes'] = $taxes;
		$this->template['endorsement_restrictions'] = $endorsement_restrictions;
		$this->template['tax'] = $mcTax->getData();

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}//end class



class mcCorpal
{

	private $template = array(
			'customer_code1' => null,
			'line_item_date' => null,
			'ship_date' => null,
			'order_date' => null,
			'medical_services_ship_to_health_industry_number' => null,
			'contract_number' => null,
			'medical_services_adjustment' => null,
			'medical_services_product_number_qualifier' => null,
			'product_code1' => null,
			'item_description' => null,
			'item_quantity' => null,
			'unit_cost' => null,
			'item_unit_measure' => null,
			'ext_item_amount' => null,
			'discount_amount' => null,
			'commodity_code' => null,
			'type_of_supply' => null,
			'vat_ref_num' => null,
			'tax' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setMcCorpal($customer_code1, $line_item_date, $ship_date, $order_date, $medical_services_ship_to_health_industry_number, $contract_number, $medical_services_adjustment, $medical_services_product_number_qualifier, $product_code1, $item_description, $item_quantity, $unit_cost, $item_unit_measure, $ext_item_amount, $discount_amount, $commodity_code, $type_of_supply, $vat_ref_num, mcTax $mcTax)
	{
		$this->template['customer_code1'] = $customer_code1;
		$this->template['line_item_date'] = $line_item_date;
		$this->template['ship_date'] = $ship_date;
		$this->template['order_date'] = $order_date;
		$this->template['medical_services_ship_to_health_industry_number'] = $medical_services_ship_to_health_industry_number;
		$this->template['contract_number'] = $contract_number;
		$this->template['medical_services_adjustment'] = $medical_services_adjustment;
		$this->template['medical_services_product_number_qualifier'] = $medical_services_product_number_qualifier;
		$this->template['product_code1'] = $product_code1;
		$this->template['item_description'] = $item_description;
		$this->template['item_quantity'] = $item_quantity;
		$this->template['unit_cost'] = $unit_cost;
		$this->template['item_unit_measure'] = $item_unit_measure;
		$this->template['ext_item_amount'] = $ext_item_amount;
		$this->template['discount_amount'] = $discount_amount;
		$this->template['commodity_code'] = $commodity_code;
		$this->template['type_of_supply'] = $type_of_supply;
		$this->template['vat_ref_num'] = $vat_ref_num;
		$this->template['tax'] = $mcTax->getData();

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}//end class



class mcCorpar
{

	private $template = array(
			'passenger_name1' => null,
			'ticket_number1' => null,
			'travel_agency_code' => null,
			'travel_agency_name' => null,
			'travel_date' => null,
			'sequence_number' => null,
			'procedure_id' => null,
			'service_type' => null,
			'service_nature' => null,
			'service_amount' => null,
			'full_vat_gross_amount' => null,
			'full_vat_tax_amount' => null,
			'half_vat_gross_amount' => null,
			'half_vat_tax_amount' => null,
			'traffic_code' => null,
			'sample_number' => null,
			'start_station' => null,
			'destination_station' => null,
			'generic_code' => null,
			'generic_number' => null,
			'generic_other_code' => null,
			'generic_other_number' => null,
			'reduction_code' => null,
			'reduction_number' => null,
			'reduction_other_code' => null,
			'reduction_other_number' => null,
			'transportation_other_code' => null,
			'number_of_adults' => null,
			'number_of_children' => null,
			'class_of_ticket' => null,
			'transportation_service_provider' => null,
			'transportation_service_offered' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setMcCorpar($passenger_name1, $ticket_number1, $travel_agency_code, $travel_agency_name, $travel_date, $sequence_number, $procedure_id, $service_type, $service_nature, $service_amount, $full_vat_gross_amount, $full_vat_tax_amount, $half_vat_gross_amount, $half_vat_tax_amount, $traffic_code, $sample_number, $start_station, $destination_station, $generic_code, $generic_number, $generic_other_code, $generic_other_number, $reduction_code, $reduction_number, $reduction_other_code, $reduction_other_number, $transportation_other_code, $number_of_adults, $number_of_children, $class_of_ticket, $transportation_service_provider, $transportation_service_offered)
	{
		$this->template['passenger_name1'] = $passenger_name1;
		$this->template['ticket_number1'] = $ticket_number1;
		$this->template['travel_agency_code'] = $travel_agency_code;
		$this->template['travel_agency_name'] = $travel_agency_name;
		$this->template['travel_date'] = $travel_date;
		$this->template['sequence_number'] = $sequence_number;
		$this->template['procedure_id'] = $procedure_id;
		$this->template['service_type'] = $service_type;
		$this->template['service_nature'] = $service_nature;
		$this->template['service_amount'] = $service_amount;
		$this->template['full_vat_gross_amount'] = $full_vat_gross_amount;
		$this->template['full_vat_tax_amount'] = $full_vat_tax_amount;
		$this->template['half_vat_gross_amount'] = $half_vat_gross_amount;
		$this->template['half_vat_tax_amount'] = $half_vat_tax_amount;
		$this->template['traffic_code'] = $traffic_code;
		$this->template['sample_number'] = $sample_number;
		$this->template['start_station'] = $start_station;
		$this->template['destination_station'] = $destination_station;
		$this->template['generic_code'] = $generic_code;
		$this->template['generic_number'] = $generic_number;
		$this->template['generic_other_code'] = $generic_other_code;
		$this->template['generic_other_number'] = $generic_other_number;
		$this->template['reduction_code'] = $reduction_code;
		$this->template['reduction_number'] = $reduction_number;
		$this->template['reduction_other_code'] = $reduction_other_code;
		$this->template['reduction_other_number'] = $reduction_other_number;
		$this->template['transportation_other_code'] = $transportation_other_code;
		$this->template['number_of_adults'] = $number_of_adults;
		$this->template['number_of_children'] = $number_of_children;
		$this->template['class_of_ticket'] = $class_of_ticket;
		$this->template['transportation_service_provider'] = $transportation_service_provider;
		$this->template['transportation_service_offered'] = $transportation_service_offered;

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}//end class


class mcTax
{
	private $template = array (
			'tax_amount' => null,
			'tax_rate' => null,
			'tax_type' => null,
			'tax_id' => null,
			'tax_included_in_sales' => null
	);

	private $data;

	public function __construct()
	{
		$this->data = array();
	}

	public function setTax($tax_amount, $tax_rate, $tax_type, $tax_id, $tax_included_in_sales)
	{
		$this->template['tax_amount'] = $tax_amount;
		$this->template['tax_rate'] = $tax_rate;
		$this->template['tax_type'] = $tax_type;
		$this->template['tax_id'] = $tax_id;
		$this->template['tax_included_in_sales'] = $tax_included_in_sales;

		array_push($this->data, $this->template);
	}

	public function getData()
	{
		return $this->data;
	}
}
?>
