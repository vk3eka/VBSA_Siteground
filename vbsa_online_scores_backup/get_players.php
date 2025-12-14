<?php 

include('connection.inc');

$players = array();
$sql_team = "Select team_id, team_name, team_grade from Team_entries where team_name = '" . $_GET['clubname'] . "' and team_cal_year = " . $_GET['year'] . " and team_grade = '" . $_GET['TeamGrade'] . "'";
//echo($sql_team . "<br>");
$result_team = $dbcnx_client->query($sql_team);
$build_team = $result_team->fetch_assoc();
$team_id = $build_team['team_id'];

$sql = "Select scrs.scrsID, scrs.scr_season, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.final_sub, scrs.tier, members.MemberID, members.FirstName, members.LastName, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade, count_played FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID AND Team_entries.team_id=scrs.team_id AND Team_entries.team_id=" . $team_id . " ORDER BY members.FirstName";

$result_players = $dbcnx_client->query($sql);
$num_rows = $result_players->num_rows;
if ($num_rows != 0) 
{
  $i = 0;
  while($build_data = $result_players->fetch_assoc()) 
  {
    // get last tier from scoresheet
    $sql_tier = "Select * FROM tbl_scoresheet where season = '" . $build_data['scr_season'] . "' and year = " . $_GET['year'] . " and team_grade = '" . $_GET['TeamGrade'] . "' and MemberID = " . $build_data['MemberID'] . " order by date_played DESC Limit 1";
    //echo("Tier " . $sql_tier . "<br>");
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
    }
    //echo("Tier " . $tier . "<br>");

    $players[$i] = ((trim($build_data['FirstName'])) . ", " . (trim($build_data['LastName'])) . ", " . $build_data['MemberID'] . ", " . $build_data['team_id'] . ", " . $build_data['count_played'] . ", " . $build_data['final_sub'] . ", " . $tier);

    //echo("Tier " . $build_tier['tier'] . "<br>");

    $players[$i] = ((trim($build_data['FirstName'])) . ", " . (trim($build_data['LastName'])) . ", " . $build_data['MemberID'] . ", " . $build_data['team_id'] . ", " . $build_data['count_played'] . ", " . $build_data['final_sub'] . ", " . $tier); 
    $i++;
  }
  $player_data = json_encode($players);
  echo($player_data);
}

?>