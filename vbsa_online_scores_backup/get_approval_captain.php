<?php 

include('connection.inc');

$team = $_GET['Team'];
$home_away = $_GET['HomeAway'];

$sql = "Select * FROM tbl_authorise where (Team_1 = '" . $team . "' or Team_2 = '" . $team . "' or Team_3 = '" . $team . "') or Access = 'Administrator'";
$result = $dbcnx_client->query($sql);
$i = 0;
while($build_data = $result->fetch_assoc())
{
	$email_address[$i] = $build_data['Email'];
	$i++;
}
$email_address[($i)] = $home_away;
$emails = json_encode($email_address);
echo($emails);

?>