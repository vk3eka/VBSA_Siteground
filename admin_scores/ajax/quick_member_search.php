<?php
require_once('../../Connections/connvbsa.php');
$conn = mysql_select_db($database_connvbsa, $connvbsa) or die("Error 101: " . mysql_error());
// get details of users with this lastname
if(!isset($_REQUEST['last_name'])) {
    exit;
}
$last_name = $_REQUEST['last_name'];
$last_name = trim($last_name);
if(strlen($last_name) < 2) {
    exit;
}
$sql = "SELECT LastName, FirstName, MemberID
        FROM members
        WHERE LastName LIKE '%$last_name%'
		ORDER BY LastName";
$run = mysql_query($sql, $connvbsa) or die("Error : fetching users with this last name :" . mysql_error());
while($row = mysql_fetch_assoc($run)) {
    $data[] = $row;
}
$r = json_encode($data);
echo $r;