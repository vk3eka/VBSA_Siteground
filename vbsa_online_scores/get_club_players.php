<?php 

include('connection.inc');

$team_grade = $_GET['team_grade'];
$team_name = $_GET['team_name'];
$team_id = $_GET['team_id'];
$club_name = htmlspecialchars($_GET['clubname']);
$year = date("Y");
$players = array();
$sql_club = "Select ClubNumber from clubs where ClubTitle = '" . html_entity_decode($club_name) . "'";
//echo($sql_club . "<br>");
$result_club = $dbcnx_client->query($sql_club);
$build_club = $result_club->fetch_assoc();
$club_id = $build_club['ClubNumber'];
//$team_id = 0;
//echo("Club ID " . $club_id . "<br>");
$sql_count = "Select * from tbl_team_rego where team_grade = '" . $team_grade . "' AND club_name = '" . html_entity_decode($club_name) . "'";
//echo($sql_count . "<br>");
$result_count = $dbcnx_client->query($sql_count);
$row_count = $result_count->num_rows;
//echo("Rows " . $row_count . "<br>");
if($row_count == 0)
{
  $sql = "Select scrs.scrsID, scrs.MemberID, scrs.selected, scrs.captain_scrs, LastName, FirstName, scrs.team_id FROM Team_entries, scrs, members WHERE team_club_id = " . $club_id . " AND scrs.team_id = Team_entries.team_id AND scrs.MemberID = members.MemberID AND team_cal_year >= (curdate() - interval 2 year) AND (scrs.MemberID !=1 AND scrs.MemberID !=10 AND scrs.MemberID !=100 AND scrs.MemberID !=1000) GROUP BY members.MemberID ORDER BY LastName, FirstName";
  //echo($sql . "<br>");
  $result_players = $dbcnx_client->query($sql);
  $i = 0;
  while($build_data = $result_players->fetch_assoc()) 
  {
     //$players[$i] = ((trim($build_data['FirstName'])) . ", " . (trim($build_data['LastName'])) . ", " . $build_data['MemberID'] . ", " . $team_id . ", " . $build_data['captain_scrs'] . ", No, 0, " . $build_data['scrsID'] . ", " . $build_data['selected']);
     //$i++;
  //}
    //echo("<pre>");
    //echo(var_dump($players));
    //echo("</pre>");

    $sql_club_players = "Insert INTO tbl_team_rego 
    ( 
    member_id, 
    firstname,
    lastname,
    selected,
    captain_scrs,
    club_name,
    team_grade,
    team_name
    )
    values 
    (
    " . $build_data['MemberID'] . ", 
    '" . $build_data['FirstName'] . "',
    '" . addslashes($build_data['LastName']) . "',
    0,
    0,
    '" . $club_name . "',
    '" . $team_grade . "',
    '" . $team_name . "')";

/*
    $sql_club_players = "Insert INTO tbl_team_rego 
    ( 
    member_id, 
    firstname,
    lastname,
    selected,
    captain_scrs,
    team_id,
    team_grade,
    team_name
    )
    values 
    (
    " . $build_data['MemberID'] . ", 
    '" . $build_data['FirstName'] . "',
    '" . addslashes($build_data['LastName']) . "',
    0,
    0,
    " . $team_id . ",
    '" . $team_grade . "',
    '" . $team_name . "')";
*/

    //echo($sql_club_players . "<br>");
    $update = $dbcnx_client->query($sql_club_players);
    if(!$update)
    {
        die("Could not insert data: " . mysqli_error($dbcnx_client));
    }
  }
}

$sql_club = "Select * from tbl_team_rego where team_grade = '" . $team_grade . "' AND team_name = '" . $team_name . "' AND club_name = '" . $club_name . "' Order by lastname";
//echo($sql_club . "<br>");
$result_club_players = $dbcnx_client->query($sql_club);
$i = 0;
while($build_club_data = $result_club_players->fetch_assoc()) 
{
  $tier = 0;
  $players[$i] = ((trim($build_club_data['firstname'])) . ", " . (trim($build_club_data['lastname'])) . ", " . $build_club_data['member_id'] . ", " . $team_id . ", " . $build_club_data['captain_scrs'] . ", No, " . $tier . ", " . $build_club_data['id'] . ", " . $build_club_data['selected']);
  $i++;
}

$player_data = json_encode($players);
echo($player_data);

?>