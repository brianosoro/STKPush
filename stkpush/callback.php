<?php

$myfile = fopen("testfile.txt", "w");
fwrite($myfile, trim(file_get_contents('php://input')));
fclose($myfile);

?>