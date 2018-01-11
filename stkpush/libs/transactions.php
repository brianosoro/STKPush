<?php
include_once ("all.php");
include_once("AfricasTalkingGateway.php");

class Transactions
{

    public $utilities;
    public $returnQueryObject;

    function __construct()
    {

        $this->utilities = new Utilities();

    }

    public function insert($amount, $refNo, $date ,  $msisdn)
    {
        $this
            ->utilities
            ->Query("INSERT INTO transactions ( amount , refNo  , msisdn , transactiondate ) VALUE ( '" . $this
            ->utilities
            ->sanitize($amount) . "' , '" . $this
            ->utilities
            ->sanitize($refNo) . "' , '" . $this
            ->utilities
            ->sanitize($msisdn) . "' , '" . $this
            ->utilities
            ->sanitize($date) . "') ");

       $this->sendSMS($msisdn , "We have Received your Payment.\r\n".$refNo."\r\nLipaEasy for Nairobi County");   
    }

    public function verify( $msisdn )
    {
        $count = $this
            ->utilities
            ->countRows("SELECT id FROM  transactions WHERE msisdn = '" . $this
            ->utilities
            ->sanitize($msisdn) . "' AND status = '0'");
        return ($count > 0 ? $this->update($msisdn) : "TRANSACTION_NOT_FOUND");

    }

    public function update($msisdn)
    {
        $this
            ->utilities
            ->countRows("UPDATE transactions SET status = '1' WHERE msisdn = '" . $this
            ->utilities
            ->sanitize($msisdn) . "'");
         
        return "OK";
    }


    public function sendSMS($msisdn , $message){



        $gateway    = new AfricasTalkingGateway(SMS_USERNAME, SMS_APIKEY);


        try 
        { 
          // Thats it, hit send and we'll take care of the rest. 
          $results = $gateway->sendMessage($msisdn, $message);
                    
          foreach($results as $result) {
            // status is either "Success" or "error message"
            //echo " Number: " .$result->number;
            //echo " Status: " .$result->status;
            //echo " MessageId: " .$result->messageId;
            //echo " Cost: "   .$result->cost."\n";
          }
        }
        catch ( AfricasTalkingGatewayException $e )
        {
          //echo "Encountered an error while sending: ".$e->getMessage();
        }        

    }



}
?>
