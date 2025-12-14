<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$memberid = $_GET['memberid'];
//$result = [];
//$result[0] = 'false';

$sql = "Select * FROM members WHERE MemberID = " . $memberid;
$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
$build_member = $result_member->fetch_assoc();

$dob_year = $build_member['dob_year'];
if(($dob_year >= (date("Y")-21)) AND ($dob_year <= (date("Y")-18)))
{
$junior = 'U21';
}
else if(($dob_year >= (date("Y")-18)) AND ($dob_year <= (date("Y")-16)))
{
$junior = 'U18';
}
else if(($dob_year >= (date("Y")-15)) AND ($dob_year <= (date("Y")-13)))
{
$junior = 'U15';
}
else if(($dob_year >= (date("Y")-12)) AND ($dob_year <= (date("Y"))))
{
$junior = 'U12';
}
else
{
$junior = 'na';
}

if(isset($build_member['memb_by']))
{
	$result = 'true' . ", " . $junior;
}
else
{
	$result = 'false' . ", " . $junior;
}

echo($result);

?>