<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$player_name = $_GET['player_name'];

if($player_name != "Bye")
{
	$sql = "Select *, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . $player_name . "%'";
	$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
	$build_member = $result_member->fetch_assoc();

	echo($build_member['MemberID']);
}
else
{
	echo(1);
}


?>