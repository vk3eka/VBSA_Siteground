<?php 

include('connection.inc');

$team_name = $_GET['team_name'];
$team_grade = $_GET['team_grade'];
$entered_by = addslashes($_GET['entered_by']);
$club_name = htmlspecialchars($_GET['club_name']);

$team_data = array();
$include_draw = 'Yes';
$audited = 'No';

$team_cal_year = $_GET['year'];
$team_season = $_GET['season'];

$sql = "Select team_id, comptype FROM Team_entries WHERE team_name = '" . $team_name . "' and team_grade = '" . $team_grade . "' and team_cal_year = " . $team_cal_year . " and team_season = '" . $team_season . "'";
//echo($sql . "<br>");
$result = $dbcnx_client->query($sql);
$num_ids = $result->num_rows;
$row = $result->fetch_assoc();
$team_id = $row['team_id'];
$comptype = $row['comptype'];
//echo($num_ids . "<br>");
if($num_ids == 0)
{
	// add team details to team entries table

	// get club data for club id
	$sql_club = "Select ClubNumber FROM clubs WHERE ClubTitle = '" . html_entity_decode($club_name) . "'";
	$result_club = $dbcnx_client->query($sql_club);
	$row_club = $result_club->fetch_assoc();
	$team_club_id = $row_club['ClubNumber'];

	// get data from team grades table
	$sql_grades = "Select * FROM Team_grade WHERE grade = '" . $team_grade . "' and season = '" . $team_season . "' and fix_cal_year = " . $team_cal_year;
	$result_grades = $dbcnx_client->query($sql_grades);
	$row_grades = $result_grades->fetch_assoc();

	$comptype = $row_grades['type'];
	$final5 = $row_grades['finals_teams'];
	$day_played = $row_grades['dayplayed'];
	$players = $row_grades['no_of_players'];
	//hard coded
	$include_draw = 'Yes';
	$audited = 'No';

	// insert data to get new team id
	$sql = 'Insert INTO Team_entries (team_club, team_club_id, team_name, team_grade, team_season, day_played, players, Final5, include_draw, audited, team_cal_year, comptype, entered_by) VALUES ("' . $club_name . '", ' . $team_club_id . ', "' . $team_name . '", "' . $team_grade . '", "' . $team_season . '", "' . $day_played . '", ' . $players . ', ' . $final5 . ', "' . $include_draw . '", "' . $audited . '", ' . $team_cal_year . ', "' . $comptype . '", "' . stripslashes($entered_by) . '")';
	//echo($sql . "<br>");
	$result = $dbcnx_client->query($sql);
	
	$sql_id = "Select team_id FROM Team_entries WHERE team_name = '" . $team_name . "' and team_grade = '" . $team_grade . "' and team_cal_year = " . $team_cal_year . " and team_season = '" . $team_season . "'";
	$result_id = $dbcnx_client->query($sql_id);
	$row_id = $result_id->fetch_assoc();
	$team_id = $row_id['team_id'];
}
$team_data = $team_id . ", " . $comptype;
echo($team_data);

?>