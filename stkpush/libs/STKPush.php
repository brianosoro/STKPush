<?php
include_once ("constants.php");

//======================================================================
// SAFARICOM STK PUSH
//======================================================================
//-----------------------------------------------------
// Author   : Brian Osoro
// Company  : Symatech Labs Ltd
// Website  : www.symatechlabs.com
// Blog     : www.brianosoro.com
// Twitter  : @brayanosoro
// Email    : info@brianosoro.com / brianosoroinc@gmail.com
//-----------------------------------------------------


class STKPush
{

    public $timestamp;

    function __construct()
    {
        $this->timestamp = date('YmdHis');
    }

    public function generateToken()
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, TOKEN_URL);
        $credentials = base64_encode(CONSUMER_KEY . ':' . CONSUMER_SECRET);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . $credentials
        )); //setting a custom header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);

        return json_decode($curl_response, true) ['access_token'];

    }

    public function proccessRequest($amount, $phoneNumber)
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, URL);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Authorization:Bearer ' . TOKEN
        )); //setting custom header
        $password = base64_encode(SHORT_CODE . PASS_KEY . $this->timestamp);

        $curl_post_data = array(
            //Fill in the request parameters with valid values
            "BusinessShortCode" => SHORT_CODE,
            "Password" => $password,
            "Timestamp" => $this->timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => $amount,
            "PartyA" => $phoneNumber,
            "PartyB" => SHORT_CODE,
            "PhoneNumber" => $phoneNumber,
            "CallBackURL" => CALLBACK_URL,
            "AccountReference" => "Order 43",
            "TransactionDesc" => "Paybill Online"
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));

        return curl_exec($curl);

    }

    public function callbackResponse()
    {

        $jsonArray = json_decode(trim(file_get_contents('php://input')) , true);

        $jsonObject = $jsonArray['Body']['stkCallback']['CallbackMetadata']['Item'];

        $transactions = new Transactions();

        foreach ($jsonObject as $key => $value)
        {

            if ($value[Name] == "Amount")
            {
                $amount = $value[Value];
            }
            else if ($value[Name] == "MpesaReceiptNumber")
            {
                $MpesaReceiptNumber = $value[Value];

            }
            else if ($value[Name] == "TransactionDate")
            {
                $TransactionDate = $value[Value];

            }
            else if ($value[Name] == "PhoneNumber")
            {

                $PhoneNumber = $value[Value];
            }


        }

        $transactions->insert($amount, $MpesaReceiptNumber , $TransactionDate , $PhoneNumber);

    }

}

?>
