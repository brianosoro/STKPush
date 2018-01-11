<?php
include_once(realpath($_SERVER["DOCUMENT_ROOT"])."/stkpush/libs/all.php");
$STK = new STKPush();
header('Content-type: application/json');
echo $STK->callbackResponse();

?>