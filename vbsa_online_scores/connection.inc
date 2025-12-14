<?php

//echo("Here<br>");
$dbcnx_client = new mysqli("127.0.0.1", "peterj", "abj059XZ@!", "vbsa3364_vbsa2");
if ($dbcnx_client->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbcnx_client->connect_error;
}
error_reporting(0);
?>