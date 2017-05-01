<?php

$storeid ="monusqa002";
$apitoken="qatoken";
$merchUrl="https://yourURL/mpistore.php";

include("../../mpgClasses.php");

if( isset($_POST['purchase_amount'])) 
{
    $xid =sprintf("%'920d", rand());
	 
	$HTTP_ACCEPT = getenv("HTTP_ACCEPT"); 
	$HTTP_USER_AGENT = getenv("HTTP_USER_AGENT");
	
	$purchase_amount = $_POST['purchase_amount'];
	$pan = $_POST['pan'];
	$expiry = $_POST['expiry'];
    
    //these are form variable gotten after cardholder hits buy button on merchant site
    //(purchase_amount,pan,expiry)

	$txnArray=array(type=>'txn',
         xid=>$xid,
         amount=>$purchase_amount,
         pan=>$pan,
         expdate=>$expiry,

         MD=>   "xid=" . $xid           //MD is merchant data that can be passed along
               ."&amp;pan=" . $pan      
               ."&amp;expiry=".$expiry
               ."&amp;amount=" .$purchase_amount,
 
         merchantUrl=>$merchUrl,
	     accept =>$HTTP_ACCEPT,
	     userAgent =>$HTTP_USER_AGENT
           );

	$mpgTxn = new mpgTransaction($txnArray);

	$mpgRequest = new mpgRequest($mpgTxn);
	$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
	$mpgRequest->setTestMode(true); //false or comment out this line for production transactions
    
	$mpgHttpPost  = new mpgHttpsPost($storeid,$apitoken,$mpgRequest);

	$mpgResponse = $mpgHttpPost->getMpgResponse();

    
     	 if($mpgResponse->getMpiMessage() == 'Y')
        {
                $vbvInLineForm = $mpgResponse->getMpiInLineForm();
                print "$vbvInLineForm\n";
        }
        else {
                if ($mpgResponse->getMpiMessage() == 'U')       {
		// merchant assumes liability for charge back (usu. corporate cards)
                        $crypt_type='7';
                }
                else {
		// merchant is not liable for chargeback (attempt was made)
                        $crypt_type='6';
                }
                // Send regular transaction with appropriate ECI
                $txnArray=array(type=>'purchase',
                        order_id=>$xid,
                        cust_id=>$cust_id,
                        amount=>$purchase_amount,
                        pan=>$pan,
                        expdate=>$expiry,
                        crypt_type=>$crypt_type
                        );

                $mpgTxn = new mpgTransaction($txnArray);
				
                $mpgRequest = new mpgRequest($mpgTxn);
				$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
				$mpgRequest->setTestMode(true); //false or comment out this line for production transactions
				
                $mpgHttpPost  =new mpgHttpsPost($storeid,$apitoken,$mpgRequest);

                $mpgResponse=$mpgHttpPost->getMpgResponse();
                print "<br>Response = ".$mpgResponse->getMessage();
                print "<br>VBV Resp = ".$mpgResponse->getMpiMessage();
                print "<br>Crypt Type = ".$crypt_type;
        }
	
}
//$PaRes    This variable is gotten from ACS 

else if(isset($_POST['PaRes']))
{
	$PaRes = $_POST['PaRes'];
	$MD = $_POST['MD'];
    $txnArray=array( type=>'acs',
                     PaRes=>$PaRes,
                     MD=>$MD
                    );

    $mpgTxn = new mpgTransaction($txnArray);

    $mpgRequest = new mpgRequest($mpgTxn);
	$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
    $mpgRequest->setTestMode(true); //false or comment out this line for production transactions

    $mpgHttpPost  = new mpgHttpsPost($storeid,$apitoken,$mpgRequest);

    $mpgResponse=$mpgHttpPost->getMpgResponse();

    parse_str($MD); //this function will parse MD field as if it were a query string
                    //and bring the resultant variables into this scope 

    if( strcmp($mpgResponse->getMpiSuccess(),"true") == 0 )
    {
           
        $orderid =sprintf("%'920d", rand());
        
        $cavv = $mpgResponse->getMpiCavv();

        $txnArray=array(
            type=>'cavv_purchase',
            order_id=> $orderid,
            amount=>$amount,
            pan=>$pan,
            expdate=>$expiry,
            cavv=>$cavv,
           );

        $mpgTxn = new mpgTransaction($txnArray);

        $mpgRequest = new mpgRequest($mpgTxn);
		$mpgRequest->setProcCountryCode("US"); //"CA" for sending transaction to Canadian environment
		$mpgRequest->setTestMode(true); //false or comment out this line for production transactions
       
        $mpgHttpPost  = new mpgHttpsPost($storeid,$apitoken,$mpgRequest);

        $mpgResponse =$mpgHttpPost->getMpgResponse();
        
        print "<br>The message is " .$mpgResponse->getMessage();
    }
    else
    {

        //At this point the merchant should deny this transaction

        print "<br>Success = ".$mpgResponse->getMpiSuccess();
        print "<br>Message = ".$mpgResponse->getMpiMessage();
    }
}
else
{
?> 
    <html>
    <form method=post action="https://localhost.com/php/mpistore.php">
    <table> 
      <tr>
        <td>Credit Card Number:</td>
        <td colspan><input type=text name=pan size=16 value="4242424242424242"></td>
      </tr>
      <tr>
        <td>Expiry Date:</td>
        <td colspan><input type=text  name=expiry size=4 value="1511"></td>
      </tr>
      <tr>
        <td>Amount:</td>
        <td colspan><input type=text  name=purchase_amount size=4 value="1.00"></td>
      </tr>
      <tr>
      <td colspan=2 align=center><input type=submit  name=submit value='Buy'></td>
      </tr>
     </table>
    </form>
    </html>    
   
<?php
}
?>
