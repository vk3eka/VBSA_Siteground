<?php 
include('connection.inc');
include('php_functions.php'); 

$home_approve = $_GET['Home_Approve'];
$away_approve = $_GET['Away_Approve'];
$home = $_GET['Home'];
$away = $_GET['Away'];
$round = $_GET['Round'];
$season = $_GET['Season'];
$date = MySqlDate($_GET['DatePlayed']);
$year = $_GET['Year'];

$sql_players = "Update tbl_scoresheet Set 
    capt_home = " . $home_approve . ", 
    capt_away = " . $away_approve . " 
    where (team = '" . $home . "' OR team = '" . $away . "')
    AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year;
$update = $dbcnx_client->query($sql_players);
if(!$update )
{
    die("Could not player update data: " . mysqli_error($dbcnx_client));
} 
echo("Approval Saved");

?>
