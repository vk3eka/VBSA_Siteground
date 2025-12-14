<?php 

include('connection.inc');

error_reporting(0);

$tier = 0;
$players = array();
$sql_team = "Select team_id, team_name, team_grade, comptype from Team_entries where team_name = '" . $_GET['clubname'] . "' and team_cal_year = " . $_GET['year'] . " and team_grade = '" . $_GET['TeamGrade'] . "'";

$result_team = $dbcnx_client->query($sql_team);
$build_team = $result_team->fetch_assoc();
$team_id = $build_team['team_id'];
$comptype = $build_team['comptype'];

$sql = "Select scrs.scrsID, scrs.scr_season, scrs.MemberID, scrs.team_grade, scrs.game_type, scrs.team_id, scrs.final_sub, scrs.byes_to_date, scrs.tier, members.MemberID, members.FirstName, members.LastName, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade, count_played FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID AND Team_entries.team_id=scrs.team_id AND Team_entries.team_id=" . $team_id . " ORDER BY members.FirstName";
//echo($sql . "<br>");
$result_players = $dbcnx_client->query($sql);
$num_rows = $result_players->num_rows;


$query_total_rounds = "Select no_of_rounds, grade, season, fix_cal_year FROM Team_grade WHERE grade = '" . $_GET['TeamGrade'] . "' and season = 'S1' and fix_cal_year = " . $_GET['year'] . " and current = 'Yes'";
//echo($query_total_rounds . "<br>");
$total_rounds = $dbcnx_client->query($query_total_rounds);
$row_total_rounds = $total_rounds->fetch_assoc();
$total_rounds_available = ($row_total_rounds['no_of_rounds']);
if($comptype == 'Snooker')
{
  $total_rounds_available = ($total_rounds_available-2); // two finals
}
else if($comptype == 'Billiards')
{
  $total_rounds_available = ($total_rounds_available-3); // three finals
}
//echo($total_rounds_available . "<br>");


if ($num_rows != 0) 
{
  $i = 0;
  if($build_team['comptype'] == 'Billiards')
  { // if billiards
    while($build_data = $result_players->fetch_assoc()) 
    {
      // get last tier from scoresheet
      $sql_tier = "Select * FROM tbl_scoresheet where season = '" . $build_data['scr_season'] . "' and year = " . $_GET['year'] . " and team_grade = '" . $_GET['TeamGrade'] . "' and MemberID = " . $build_data['MemberID'] . " order by date_played DESC Limit 1";
      $result_tier = $dbcnx_client->query($sql_tier);
      $tier_rows = $result_tier->num_rows;
      if($tier_rows > 0)
      {
        $build_tier = $result_tier->fetch_assoc();
        $tier = $build_tier['tier'];
      }
      else
      {
        $tier = 0;
        if($build_data['scr_season'] == "S1")
        {
          $previous_season = 'S2';
          $previous_year = ($_GET['year']-1);
        }
        if($build_data['scr_season'] == "S2")
        {
          $previous_season = 'S1';
          $previous_year = ($_GET['year']);
        }
        $sql_tier_previous = "Select * FROM tbl_scoresheet where season = '" . $previous_season . "' and year = " . $previous_year . " and type = 'Billiards' and MemberID = " . $build_data['MemberID'] . " order by date_played DESC Limit 1";
        $result_tier_previous = $dbcnx_client->query($sql_tier_previous);
        $tier_rows_previous = $result_tier_previous->num_rows;
        $build_tier_previous = $result_tier_previous->fetch_assoc();
        if($tier_rows_previous > 0)
        {
          $tier = $build_tier_previous['tier'];
        }
        else
        {
          $tier = 0;
        }
      }
       $players[$i] = ((trim($build_data['FirstName'])) . ", " . (trim($build_data['LastName'])) . ", " . $build_data['MemberID'] . ", " . $build_data['team_id'] . ", " . $build_data['count_played'] . ", " . $build_data['final_sub'] . ", " . $tier . ", " . $build_data['byes_to_date'] . ", " . $total_rounds_available); 
      $i++;
    }  
  }
  else
  { // not billiards
    mysqli_data_seek($result_players,0);
    while($build_data = $result_players->fetch_assoc()) 
    {
      $tier = 0;
      $players[$i] = ((trim($build_data['FirstName'])) . ", " . (trim($build_data['LastName'])) . ", " . $build_data['MemberID'] . ", " . $build_data['team_id'] . ", " . $build_data['count_played'] . ", " . $build_data['final_sub'] . ", " . $tier . ", " . $build_data['byes_to_date'] . ", " . $total_rounds_available); 
      $i++;
    }
  }
  $player_data = json_encode($players);
  echo($player_data);
}

?>