<?php 

include('connection.inc');

$memberID = $_GET['memberID'];
$firstname = $_GET['Firstname'];
$lastname = $_GET['LastName'];
$email = $_GET['Email'];
$mobilephone = $_GET['MobilePhone'];

$sql_update = "Update members Set 
        FirstName = '" . $firstname . "', " . "
        LastName = '" . $lastname . "', " . "
        Email = '" . $email . "', " . "
        MobilePhone = '" . $mobilephone . "'" . "
        where MemberID= " . $memberID;
$update = $dbcnx_client->query($sql_update);
echo("Updated");
?>