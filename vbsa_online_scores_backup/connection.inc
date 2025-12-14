<?php

$hostname_connvbsa = "localhost";
$database_connvbsa = "vbsa3364_vbsa2";
$username_connvbsa = "vbsa3364_AlPal";
$password_connvbsa = "Lurch147";

$dbcnx_client = new mysqli($hostname_connvbsa, $username_connvbsa, $password_connvbsa, $database_connvbsa);
if ($dbcnx_client->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbcnx_client->connect_error;
}

?>