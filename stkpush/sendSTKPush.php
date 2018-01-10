<?php
include_once(realpath($_SERVER["DOCUMENT_ROOT"])."/stkpush/libs/all.php");
$STK = new STKPush();
echo $STK->proccessRequest("10" , "254713946234");

?>