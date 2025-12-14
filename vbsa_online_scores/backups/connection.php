<?php

$dbcnx_client = new mysqli("172.16.10.16", "peterj", "abj059XZ@!", "vbsa3364_vbsa2");
if ($dbcnx_client->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbcnx_client->connect_error;
}

?>