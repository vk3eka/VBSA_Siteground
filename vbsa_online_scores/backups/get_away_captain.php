<?php 

include('connection.inc');

$away_team = $_GET['AwayTeam'];

$sql = "Select * FROM tbl_authorise where Team = '" . $away_team . "' or Access = 'Administrator'";
$result = $dbcnx_client->query($sql);
$i = 0;
while($build_data = $result->fetch_assoc())
{
	$email_address[$i] = $build_data['Email'];
	$i++;
}
$emails = json_encode($email_address);
echo($emails);
?>