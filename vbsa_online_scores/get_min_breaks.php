<?php 

include('connection.inc');

$tier = (int)$_GET['tier'];
$grade = $_GET['grade'];

$sql = "Select tier" . $tier . "_break FROM tbl_team_grade WHERE grade = '" . $grade . "'";
$result = $dbcnx_client->query($sql);
$row = $result->fetch_assoc();

$min_breaks = $row['tier' . $tier . '_break'];
echo($min_breaks);

?>