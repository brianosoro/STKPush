<?php
include_once(realpath($_SERVER["DOCUMENT_ROOT"])."/stkpush/libs/all.php");
$transactions = new Transactions();
echo $transactions->verify("254".substr($_POST['phone'] , 1));

?>