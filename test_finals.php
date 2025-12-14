<?php 

require_once('Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$season = 'S1';
$team_grade = 'CVS1';
$count_played = 8;

function GetFinalsQualification($season, $team_grade, $count_played)
{
    global $connvbsa;
// get data for finals qualification
// get number of byes
  $sql_byes = "Select 
    SUM((fix1home = 'Bye') +
    (fix2home = 'Bye') +
    (fix3home = 'Bye') +
    (fix4home = 'Bye') +
    (fix5home = 'Bye') +
    (fix6home = 'Bye') +
    (fix7home = 'Bye') +
    (fix1away = 'Bye') +
    (fix2away = 'Bye') +
    (fix3away = 'Bye') +
    (fix4away = 'Bye') +
    (fix5away = 'Bye') +
    (fix6away = 'Bye') +
    (fix7away = 'Bye'))
    as byes FROM vbsa3364_vbsa2.tbl_fixtures Where season = '$season' and year = " . date('Y') . " and team_grade = '" . $team_grade . "'";
  $byes = mysql_query($sql_byes, $connvbsa) or die(mysql_error());
  $row_byes = mysql_fetch_assoc($byes);
  if($row_byes['byes'] != '')
  {
    $total_byes = $row_byes['byes'];
  }
  else
  {
    $total_byes = 0;
  }
  
  $sql_teams = "Select * FROM vbsa3364_vbsa2.Team_entries Where team_season = '$season' and team_cal_year = " . date('Y') . " and team_grade = '" . $team_grade . "' and team_name != 'Bye'";
  $teams = mysql_query($sql_teams, $connvbsa) or die(mysql_error());
  $no_of_teams = mysql_num_rows($teams);
  if($total_byes == 0)
  {
    $no_of_byes = 0;
  }
  else
  {
    $no_of_byes = ($total_byes/$no_of_teams);
  }
  
  // get last round played
  $query_total_rounds = "Select no_of_rounds, grade, season, fix_cal_year, type, grade FROM Team_grade WHERE season = '$season' and fix_cal_year = " . date('Y') . " and current = 'Yes' and grade = '" . $team_grade . "'";
  $total_rounds = mysql_query($query_total_rounds, $connvbsa) or die(mysql_error());
  $row_total_rounds = mysql_fetch_assoc($total_rounds);
  $type = $row_total_rounds['type'];
  $total_rounds_available = ($row_total_rounds['no_of_rounds']);
  if($type == 'Snooker')
  {
    $total_rounds_available = ($total_rounds_available-2); // two finals
  }
  else if($type == 'Billiards')
  {
    $total_rounds_available = ($total_rounds_available-3); // three finals
  }
  if((($count_played >= ceil(($total_rounds_available-$no_of_byes)*0.5)) && ($count_played > 0)))
  {
    return true;
  }
  else
  {
    return false;
  }
// end finals qualification
}
if(GetFinalsQualification($season, $team_grade, $count_played) == 1)
{
    echo("True<br>");
}
else
{
    echo("False<br>");
}
