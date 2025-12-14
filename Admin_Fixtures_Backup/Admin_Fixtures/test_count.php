<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$team_grades = ('BVS1, CVS1, PB(1)');

function GetHomeGames($team_grades, $round, $year, $season, $dayplayed)
{
    global $connvbsa;
    
    $team_grade = explode(", ", $team_grades);
    $fixArray = '';
    $fixArray_home_games = '';
    //$fixArray_home_games = [];
    //$i = 0;
    foreach($team_grade as $grade)
    { 
        //$grade = 'BVS2';
        if($grade != '')
        {
            $sql_home_games = "Select fix1home, fix2home, fix3home, fix4home, fix5home, fix6home, fix7home, team_grade FROM tbl_create_fixtures where team_grade = '" . $grade . "' and year = " . $year . " and season = '" . $season . "' and round = " . $round . " and dayplayed = '" . $dayplayed . "'";
            $result_home_games = mysql_query($sql_home_games, $connvbsa) or die(mysql_error());
            $build_home_games = $result_home_games->fetch_assoc();
            $fixArray_home_games = $build_home_games['fix1home'] . ', ' . $build_home_games['fix2home'] . ', ' . $build_home_games['fix3home'] . ', ' . $build_home_games['fix4home'] . ', ' . $build_home_games['fix5home'] . ', ' . $build_home_games['fix6home'] . ', ' . $build_home_games['fix7home'];
            $fixArray = $fixArray . ", " . $fixArray_home_games;
            //$i++;
        }
    }
    //echo("<pre>");
    //echo("Home " . var_dump($fixArray));
    //echo("</pre>");
    return $fixArray;
}

for($i = 0; $i < 18; $i++)
{
    $fixArray = GetHomeGames($team_grades, ($i+1), 2025, 'S1', 'Mon');
}



function CountClubs($fixArray, $year, $season, $club_name, $dayplayed)
{
    global $connvbsa;

    // Split and clean team list
    $teamArray = array_filter(array_map('trim', explode(", ", $fixArray)));

    // Escape inputs for SQL safety
    $club_name_safe = mysql_real_escape_string($club_name, $connvbsa);

    // Prepare SQL to get all teams for the given club
    $sql = "
        SELECT team_name 
        FROM Team_entries 
        WHERE team_club = '$club_name_safe'
          AND team_cal_year = $year
          AND team_season = '$season'
          AND day_played = '$dayplayed'
    ";

    $result = mysql_query($sql, $connvbsa) or die(mysql_error());

    $numOfTrue = 0;

    // Compare each team from database to input list
    while ($row = mysql_fetch_assoc($result)) {
        $team_name = trim($row['team_name']);
        if ($team_name !== 'Bye' && in_array($team_name, $teamArray)) {
            $numOfTrue++;
        }
    }

    return $numOfTrue;
}

//$fixArray = "Team A, Team B, Team C";
$count = CountClubs($fixArray, 2025, "S1", "Camberwell Cue Sports", "Mon");
echo "Camberwell Cue Sports has $count teams in this round.";

?>
