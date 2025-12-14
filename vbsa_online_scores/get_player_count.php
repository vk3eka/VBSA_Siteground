<?php 

include('connection.inc');

$players = array();
$sql_team = "Select team_id, team_name, team_grade from Team_entries where team_name = '" . $_GET['clubname'] . "' and team_cal_year = " . $_GET['year'] . " and team_grade = '" . $_GET['TeamGrade'] . "'";
//echo($sql_team . "<br>");
$result_team = $dbcnx_client->query($sql_team);
$build_team = $result_team->fetch_assoc();
$team_id = $build_team['team_id'];

$sql = "Select scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, r01s, r02s, r03s, r04s, r05s, r06s, r07s, r08s, r09s, r10s, r11s, r12s, r13s, r14s, r15s, r16s, r17s, r18s, r01pos, r02pos, r03pos, r04pos, r05pos, r06pos, r07pos, r08pos, r09pos, r10pos, r11pos, r12pos, r13pos, r14pos, r15pos, r16pos, r17pos, r18pos, members.MemberID, members.FirstName, members.LastName, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID AND Team_entries.team_id=scrs.team_id AND Team_entries.team_id=" . $team_id . " ORDER BY members.FirstName";
//echo($sql . "<br>");
$result_players = $dbcnx_client->query($sql);
$num_rows = $result_players->num_rows;
$player_count = json_encode($num_rows);
echo($player_count);

?>