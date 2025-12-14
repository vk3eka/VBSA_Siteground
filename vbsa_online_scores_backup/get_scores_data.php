<?php 

include('connection.inc');

$season = $_GET['season'];
$team_grade = $_GET['grade'];
$member_id = $_GET['player'];  // memberid
$sort = $_GET['sort'];
$sort_order = $_GET['sort_order'];
$season_data = explode(",", $season);
$current_year = $season_data[0];
$current_season = $season_data[1];
$team_id = $_GET['team'];

$sql_part = " and scrs.team_grade = '" . trim($team_grade) . "'  and scrs.team_id = '" . $team_id . "' and members.MemberID = " . $member_id;

if(($team_grade == 'all') || ($team_grade == ''))
{
	$sql_part = " ";
}
elseif(($member_id == 'all') || ($member_id == ''))
{
	$sql_part = " and scrs.team_grade = '" . stripslashes($team_grade) . "' ";
}
if($sort != '')
{
	$sql_sort = " order by " . $sort . " " . $sort_order;
}
else
{
	$sql_sort = "";
}
$sql = "Select * FROM scrs LEFT JOIN members on members.MemberID = scrs.MemberID LEFT JOIN Team_entries on Team_entries.team_id = scrs.team_id where scr_season = '". trim($current_season) . "' and current_year_scrs = " . $current_year . " and members.MemberID != 1 " . " " . $sql_part . $sql_sort;
//echo($sql . "<br>");
$result_player = $dbcnx_client->query($sql) or die("Couldn't execute grade query. " . mysqli_error($dbcnx_client));
$i = 0;
$player = [];
while($build_player = $result_player->fetch_assoc()) 
{
    $player[$i] = $build_player['MemberID'] . ", " . $build_player['FirstName'] . " " . $build_player['LastName'] . ", " . $build_player['team_name'] . ", " . $build_player['team_grade'] . ", " . $build_player['current_year_scrs'] . ", " . $build_player['scr_season'] . ", " . $build_player['allocated_rp'] . ", " . $build_player['game_type'] . ", " . $build_player['count_played'] . ", " . $build_player['avail_pts'] . ", " . $build_player['pts_won'] . ", " . $build_player['percent_won'] . ", " . $build_player['average_position'];
	$i++;
}
$player_data = json_encode($player);
echo($player_data);

?>