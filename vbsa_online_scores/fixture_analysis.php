<?php 

include('header.php'); 
include('connection.inc'); 

$sql = 'Select * from tbl_create_fixtures where round = 1 and season = "S1" and year = 2024';

$result_fixture = $dbcnx_client->query($sql) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
while($build_fixture = $result_fixture->fetch_assoc()) 
{
    echo($build_fixture['date'] . " " 
    . $build_fixture['fix1home_club'] . " " . $build_fixture['fix1home'] . "<br>"
    . $build_fixture['fix2home_club'] . " " . $build_fixture['fix2home'] . "<br>"
    . $build_fixture['fix3home_club'] . " " . $build_fixture['fix3home'] . "<br>"
    . $build_fixture['fix4home_club'] . " " . $build_fixture['fix4home'] . "<br>"
    . $build_fixture['fix5home_club'] . " " . $build_fixture['fix5home'] . "<br>"
    . $build_fixture['fix6home_club'] . " " . $build_fixture['fix6home'] . "<br>");
    //}

    $arrayClubID = [];
    $sql_round = 'Select * from tbl_create_fixtures where round = 1 and date = "' . $build_fixture['date'] . '" and season = "S1" and year = 2024';
    $result_round_fixture = $dbcnx_client->query($sql_round) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
    $arrayClubID = '';
    while($build_round_fixture = $result_round_fixture->fetch_assoc()) 
    {
        $arrayClubID .= $build_round_fixture['fix1home_club'] . ", " . $build_round_fixture['fix2home_club'] . ", " . $build_round_fixture['fix3home_club'] . ", " . $build_round_fixture['fix4home_club'] . ", " . $build_round_fixture['fix5home_club'] . ", " . $build_round_fixture['fix6home_club'];
    }

    $sql_club_count = "Select distinct team_club_id FROM Team_entries where team_cal_year = 2024 and team_season = 'S1' order by team_club_id desc Limit 1";
    $result_club_count = $dbcnx_client->query($sql_club_count) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
    $count = $result_club_count->fetch_assoc();
    $club_count = intval($count['team_club_id']);
    for($i = 0; $i < $club_count; $i++)
    {
        $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $i;
        $result_club_tables = $dbcnx_client->query($sql_club_tables) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
        $tables = $result_club_tables->fetch_assoc();
        $club_tables = $tables['ClubTables'];

        if((substr_count($arrayClubID, $i) != 0) && ($i != 0))
        {
            if((substr_count($arrayClubID, $i)) > ($club_tables/2))
            {
                echo ("Club ID = " . $i . " doesn't have enough tables for the match on " . $build_fixture['date'] . "<br>");
            }
            echo ("Club ID = " . $i . ", Tables = " . $club_tables . ", Count = " . substr_count($arrayClubID, $i) . "<br>");
        }
    }
}
?>