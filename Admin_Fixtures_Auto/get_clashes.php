<?php
require_once('../Connections/connvbsa.php'); 

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

$year = 2024;
$season = 'S2';
$dayplayed = 'Mon';
$team_grades = ("BWS, CWS, PB(2)");
$max_no_of_teams = 14;
//echo($team_grades . "<br>");
function GetHomeGames($team_grades, $round, $year, $season, $dayplayed)
{
    global $connvbsa;
    //echo("Home Games<br>");
    //echo($team_grades[1] . "<br>");
    $team_grade = explode(", ", $team_grades);
    $fixArray = '';
    $fixArray_home_games = '';
    foreach($team_grade as $grade)
    { 
        //echo($grade . "<br>");
        if($grade != '')
        {
            $sql_home_games = "Select fix1home, fix2home, fix3home, fix4home, fix5home, fix6home, fix7home, team_grade FROM tbl_create_fixtures where team_grade = '" . $grade . "' and year = " . $year . " and season = '" . $season . "' and round = " . $round . " and dayplayed = '" . $dayplayed . "'";
            //echo($sql_home_games . "<br>");
            $result_home_games = mysql_query($sql_home_games, $connvbsa) or die(mysql_error());
            $build_home_games = $result_home_games->fetch_assoc();
            $fixArray_home_games = $build_home_games['fix1home'] . ', ' . $build_home_games['fix2home'] . ', ' . $build_home_games['fix3home'] . ', ' . $build_home_games['fix4home'] . ', ' . $build_home_games['fix5home'] . ', ' . $build_home_games['fix6home'] . ', ' . $build_home_games['fix7home'];
            $fixArray = $fixArray . ", " . $fixArray_home_games;
            //$i++;
        }
    }
    return $fixArray;
}

function GetAwayGames($team_grades, $round, $year, $season, $dayplayed)
{
    global $connvbsa;
    
    $grades = explode(", ", $team_grades);
    $fixArray = '';
    $fixArray_away_games = '';
    //$fixArray_away_games = [];
    foreach($grades as $grade)
    {
        if($grade != '')
        {
            $sql_away_games = "Select fix1away, fix2away, fix3away, fix4away, fix5away, fix6away, fix7away, team_grade FROM tbl_create_fixtures where team_grade = '" . $grade . "' and year = " . $year . " and season = '" . $season . "' and round = '" . $round . "' and dayplayed = '" . $dayplayed . "'";
            $result_away_games = mysql_query($sql_away_games, $connvbsa) or die(mysql_error());
            $build_away_games = $result_away_games->fetch_assoc();
            $fixArray_away_games = $build_away_games['fix1away'] . ', ' . $build_away_games['fix2away'] . ', ' . $build_away_games['fix3away'] . ', ' . $build_away_games['fix4away'] . ', ' . $build_away_games['fix5away'] . ', ' . $build_away_games['fix6away'] . ', ' . $build_away_games['fix7away'];
            $fixArray = $fixArray . ", " . $fixArray_away_games;
        }
    }
    //echo("<pre>");
    //echo("Away " . var_dump($fixArray));
    //echo("</pre>");

    return $fixArray;
}

function CountHomeGames($team_grades, $year, $season, $dayplayed)
{
    global $connvbsa;
    $HomeTeamsArray = [];
    $HomeCountArray = '';
    $numOfTrue = 0;
    for($i = 0; $i < 18; $i++)
    {
        $HomeCountArray .= GetHomeGames($team_grades, ($i+1), $year, $season, $dayplayed);
    }
    $HomeTeamsArray = explode(", ", $HomeCountArray);

    //echo("<pre>");
    //echo(var_dump($HomeTeamsArray));
    //echo("</pre>");
    $numOfTrue = 0;

    $sql = "Select team_name, team_grade FROM Team_entries WHERE team_season ='$season' AND team_cal_year = $year and day_played = '" . $dayplayed . "' ORDER BY team_name";
    //echo($sql . "<br>");
    $result_home_games = mysql_query($sql, $connvbsa) or die(mysql_error());
    while($build_home_games = $result_home_games->fetch_assoc())
    {
        $team_name = trim($build_home_games['team_name']);
        //echo($team_name . "<br>");
        $count = array_count_values($HomeTeamsArray);
        //echo($count[$team_name] . "<br>");
        if(($team_name == 'Bye') || ($count[$team_name] == ''))
        {
            $numOfTrue = 0;
        }
        else
        {
            $numOfTrue = $count[$team_name];
        }
        $sql_update = "Update Team_entries Set home_games = " . $numOfTrue . " where team_name = '" . $team_name . "'  and team_season ='" . $season . "' AND team_cal_year = " . $year; 
        //echo($sql_update . "<br>");
        $update = mysql_query($sql_update, $connvbsa);
    }
}

function CountTeams($home_array, $away_array, $team, $dayplayed, $round)
{   
    $HomeTeamArray = '';
    $AwayTeamArray = '';
    $numOfTrue = 0;
    $HomeTeamArray = explode(", ", $home_array);
    $AwayTeamArray = explode(", ", $away_array);
    $HomeTeamArray = array_filter($HomeTeamArray);
    $AwayTeamArray = array_filter($AwayTeamArray);
    //echo("<pre>");
    //echo(var_dump($HomeTeamArray));
    //echo("</pre>");
    $r = 0; // changed from 1 12/7/24
    $numOfTrue = 0;
    foreach($HomeTeamArray as $arr)
    {
        if((trim($team) == trim($arr)))
        //if(($team == $arr) && ($team != ''))
        {
            if(($AwayTeamArray[$r] == 'Bye') || ($arr == 'Bye'))
            {
                $numOfTrue = 0;
            }
            else
            {
                $numOfTrue++;
                //echo("Team Name " . $team . ", Arr " . $arr . ", Count " . $numOfTrue . ", Round " . $round . "<br>");
            }
            $r++; // moved 12/07/24
        }
        //$r++; // moved 12/07/24
    }
    //echo("Returned Count " . $numOfTrue . "<br>");
    return ($numOfTrue);
}

function CountClubs($fixArray, $year, $season, $club_name, $dayplayed, $round)
{   
    global $connvbsa;
    $teamArray = explode(", ", $fixArray);
    $teamArray = array_filter($teamArray);
    //echo("<pre>");
    //echo(var_dump($teamArray));
    //echo("</pre>");

    $sql_club_name = "Select team_name, team_club_id, team_club FROM Team_entries where team_club = '" . $club_name . "' and team_cal_year = " . $year . " and team_season = '" . $season . "' and day_played = '" . $dayplayed . "'";
    $result_club_name = mysql_query($sql_club_name, $connvbsa) or die(mysql_error());
    $numOfTrue = 0;
    while($build_club_name = $result_club_name->fetch_assoc())
    {
        foreach($teamArray as $arr)
        {
            if(trim($build_club_name['team_name']) == trim($arr))
            {
                if($build_club_name['team_name'] != 'Bye')
                {
                    $numOfTrue++;
                }
                
                //echo("Club " . $club_name . ", Team Name " . $build_club_name['team_name'] . ", Arr " . $arr . ", Count " . $numOfTrue . ", Round " . $round . "<br>");
                //echo("Looking for  " . $build_club_name['team_name']. " in Array " . $arr . ", Count " . $numOfTrue . ", Round " . $round . "<br>");
                
            }
        }
    }
    //echo("Count Returned " . $numOfTrue  . "<br>");
    return ($numOfTrue);
}

$sql_club = 'Select distinct team_club_id, team_club from Team_entries where team_cal_year = ' . $year . ' and team_season = "' . $season . '" and day_played = "' . $dayplayed . '" order by team_club';
//echo($sql_club . "<br>");
$result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
//$z = 0;
$clashes = 0;
while($row = $result_club->fetch_assoc()) 
{
    if($row['team_club_id'] > 0)
    {
        $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $row["team_club_id"];
        //echo($sql_club_tables . "<br>");
        $result_club_tables = mysql_query($sql_club_tables, $connvbsa) or die(mysql_error());
        $tables = $result_club_tables->fetch_assoc();
        $club_tables = $tables['ClubTables'];

        $sql_team = 'Select distinct team_club_id, team_name, team_grade from Team_entries where team_cal_year = ' . $year . ' and team_club_id = ' . $row['team_club_id'] . ' and team_season = "' . $season . '" and day_played = "' . $dayplayed . '"';
        //echo($sql_team . "<br>");
        //echo($max_no_of_teams . "<br>");
        $result_team = mysql_query($sql_team, $connvbsa) or die(mysql_error());
        $y = 0;
        while($row_team = $result_team->fetch_assoc()) 
        {
            for($j = 0; $j < $max_no_of_teams; $j++)
            {
                //echo($j . "<br>");
                $HomeFixArray = GetHomeGames($team_grades, ($j+1), $year, $season, $dayplayed);

                //echo("<pre>");
                //echo("Home " . var_dump($HomeFixArray));
                //echo("</pre>");

                $AwayFixArray = GetAwayGames($team_grades, ($j+1), $year, $season, $dayplayed);
                $count = CountTeams($HomeFixArray, $AwayFixArray, $row_team['team_name'], $dayplayed, ($j+1));
                //echo($row_team['team_grade'] . " " . $count . " - " . $row_team['team_name'] . "<br>");
            }
            $y++;
        }

        for($j = 0; $j < $max_no_of_teams; $j++)
        {   
            $fixArray = GetHomeGames($team_grades, ($j+1), $year, $season, $dayplayed);
            $club_count = CountClubs($fixArray, $year, $season, ($row['team_club']), $dayplayed, ($j+1));
            if($club_tables < ($club_count*2))
            {
                $clashes++;
            }
        }
        //$z++;
    }
}

echo($clashes);

?>