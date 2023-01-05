<?php

require "../../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='monca03650';
$api_token='7Yw0MPTlhjBRcZiE6837';

/************************ Transaction Variables ******************************/

$type='res_installment_lookup';
$order_id='Test'.date("dmy-G:i:s");
$amount='6000.00';
$data_key='K6DgYJOh3de7e6m9TvuCkNu26';
$expdate='2212'; //For Temp Token

/************************ Transaction Array **********************************/

$txnArray=array('type'=>$type,  
         'order_id'=>$order_id,
         'amount'=>$amount,
         'data_key'=>$data_key,
         'expdate'=>$expdate
        );

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

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

print("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nBankTotals = " . $mpgResponse->getBankTotals());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nIsVisaDebit = " . $mpgResponse->getIsVisaDebit());

$eligibleInstallmentPlans = $mpgResponse->getEligibleInstallmentPlans();
           
$planCount = $eligibleInstallmentPlans->getPlanCount();
$installmentPlans = $eligibleInstallmentPlans->getInstallmentPlans();
           
for ($i = 0; $i < $planCount; $i++)
{
        print("\nPlanId = " . $installmentPlans[$i]->getPlanId());
        print("\nPlanIdRef = " . $installmentPlans[$i]->getPlanIdRef());
        print("\nName = " . $installmentPlans[$i]->getName());
        print("\nType = " . $installmentPlans[$i]->getType());
        print("\nNumInstallments = " . $installmentPlans[$i]->getNumInstallments());
        print("\nInstallmentFrequency = " . $installmentPlans[$i]->getInstallmentFrequency());


        $tac = $installmentPlans[$i]->getTac();

        $tacDetailsList = $tac->getTacDetailsList();
        $tacCount = $tac->getTacCount();

        print("\ntacCount = " . $tacCount);

        for ($j = 0; $j < $tacCount; $j++)
        {
                $tacDetails = $tacDetailsList[$j];
                
                print("\nText = " . $tacDetails->getText());
                print("\nUrl = " . $tacDetails->getUrl());
                print("\nVersion = " . $tacDetails->getVersion());
                print("\nLanguageCode = " . $tacDetails->getLanguageCode());
        }

        $promotionInfo = $installmentPlans[$i]->getPromotionInfo();

        print("\nPromotionCode = " . $promotionInfo->getPromotionCode());
        print("\nPromotionId = " . $promotionInfo->getPromotionId());


        $firstInstallment = $installmentPlans[$i]->getFirstInstallment();

        print("\nUpfrontFee = " . $firstInstallment->getUpfrontFee());
        print("\nInstallmentFee = " . $firstInstallment->getInstallmentFee());
        print("\nAmount = " . $firstInstallment->getAmount());

        $lastInstallment = $installmentPlans[$i]->getLastInstallment();

        print("\nInstallmentFee = " . $lastInstallment->getInstallmentFee());
        print("\nAmount = " . $lastInstallment->getAmount());

        print("\nAPR = " . $installmentPlans[$i]->getAPR());
        print("\nTotalFees = " . $installmentPlans[$i]->getTotalFees());
}

//Resolve Data
print("\ncust_id = " . $mpgResponse->getResDataCustId());
print("\nphone = " . $mpgResponse->getResDataPhone());
print("\nemail = " . $mpgResponse->getResDataEmail());
print("\nnote = " . $mpgResponse->getResDataNote());
print("\nexpdate = " . $mpgResponse->getResDataExpdate());
print("\nmasked_pan = " . $mpgResponse->getResDataMaskedPan());

?>

