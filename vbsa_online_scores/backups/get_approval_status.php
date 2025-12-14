<?php 

include('connection.inc');
include('php_functions.php'); 

$Round = $_GET['Round'];
$Title = $_GET['RoundTitle'];
$Year = $_GET['Year'];
$Season = $_GET['Season'];
$DatePlayed = $_GET['DatePlayed'];
$TeamGrade = $_GET['TeamGrade'];
$Team = $_GET['Home'];
$Opposition = $_GET['Away'];

$home_approval = array();

$sql_approvals_home = "Select Sum(score_1) as total, capt_home from tbl_scoresheet where year = " . $Year . " and team_grade = '" . $TeamGrade . "' and season = '"  . $Season . "' and date_played = '" . MySqlDate($DatePlayed) . "' and team = '" . $Team . "'";
//echo("Home " . $sql_approvals_home . "<br>");

$result_approvals_home = $dbcnx_client->query($sql_approvals_home);

while($build_approvals_home = $result_approvals_home->fetch_assoc()) 
{
  $home_approval[0] = $build_approvals_home['capt_home']; 
  //echo("Home " . $home_approval[0] . "<br>");
  $home_approval[1] = $build_approvals_home['total']; 
}

$away_approval = array();

$sql_approvals_away = "Select Sum(score_1) as total, capt_home from tbl_scoresheet where year = " . $Year . " and team_grade = '" . $TeamGrade . "' and season = '"  . $Season . "' and date_played = '" . MySqlDate($DatePlayed) . "' and team = '" . $Opposition . "'";
//echo("Away " . $sql_approvals_away . "<br>");
$result_approvals_away = $dbcnx_client->query($sql_approvals_away);

while($build_approvals_away = $result_approvals_away->fetch_assoc()) 
{
  $away_approval[0] = $build_approvals_away['capt_home']; 
  $away_approval[1] = $build_approvals_away['total']; 
}

$home_win = 0;
$away_win = 0;
if(($build_approvals_away['type'] == 'Billiards') && (($Title == 'Semi Final') || ($Title == 'Grand Final')))
{
  if($home_approval[1] > $away_approval[1])
  {
    $home_win = 1;
    $away_win = 0;
  }
  else if($away_approval[1] > $home_approval[1])
  {
    $away_win = 1;
    $home_win = 0;
  }
}

//echo("Home " . $homm_win . "<br>");
//echo("Away " . $away_win . "<br>");

$sql_approve_home = "Update tbl_club_results Set score_total = " . $home_approval[1] . ", finals_win = " . $home_win  . " where club = '" . $Team . "' AND date_played = '" . MySqlDate($DatePlayed) . "'";  
//echo("Home " . $sql_approve_home . "<br>");
$update = $dbcnx_client->query($sql_approve_home);
if(!$update )
{
    die("Could not update home club data: " . mysqli_error($dbcnx_client));
} 

$sql_approve_away = "Update tbl_club_results Set score_total = " . $away_approval[1] . ", finals_win = " . $away_win  . " where club = '" . $Opposition . "' AND date_played = '" . MySqlDate($DatePlayed) . "'"; 
//echo("Away " . $sql_approve_away . "<br>"); 
$update = $dbcnx_client->query($sql_approve_away);
if(!$update )
{
    die("Could not update away club data: " . mysqli_error($dbcnx_client));
} 


$approval_all = array_merge($home_approval, $away_approval);
echo(json_encode($approval_all));

//echo json_encode($approval);

?>