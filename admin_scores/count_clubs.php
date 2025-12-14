<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$fixArray = $_GET['TeamArray'];
$year = $_GET['Year']; 
$season = $_GET['Season'];
$club_name = $_GET['ClubName']; 
$dayplayed = $_GET['DayPlayed'];
$team_grade = $_GET['TeamGrade'];
$round = $_GET['Round'];

//echo("<pre>");
//echo(var_dump($fixArray));
//echo("</pre>");

$numOfTrue = 0;
$sql_club_name = "Select team_name, team_club_id, team_club FROM Team_entries where team_club = '" . $club_name . "' and team_cal_year = " . $year . " and team_season = '" . $season . "' and day_played = '" . $dayplayed . "'";
//echo($sql_club_name . "<br>");
$result_club_name = mysql_query($sql_club_name, $connvbsa) or die(mysql_error());
while($build_club_name = $result_club_name->fetch_assoc())
{

    $exploded = explode("Round ", $fixArray);
    //var_dump($exploded);

    for($i = 0; $i < count($exploded); $i++)
    {
        $exploded2 = explode(", ", $exploded[$i]);
        //echo(var_dump($exploded2) . "<br>");
    }
    for($j = 0; $j < count($exploded2); $j++)
    {
        $exploded3 = explode(",", $exploded2[$j]);
        //echo(var_dump($exploded3) . "<br>");
    }
    $numOfTrue = 0;
    $sql_club_name = "Select team_name, team_club_id, team_club FROM Team_entries where team_club = '" . $club_name . "' and team_cal_year = " . $year . " and team_season = '" . $season . "' and day_played = '" . $dayplayed . "'";
    //echo($sql_club_name . "<br>");
    $result_club_name = mysql_query($sql_club_name, $connvbsa) or die(mysql_error());
    foreach($exploded3 as $arr)
    {
        if($arr != '')
        {
            echo("Array " . $arr . "<br>");
            if($build_club_name['team_name'] == $arr)
            {
                echo("Team Name " . $build_club_name['team_name'] . "<br>");
                $numOfTrue++;
            }
        }
    }
    echo ($numOfTrue);
    }
//}
//}


   // }
//}
/*
foreach($fixArray as $fixture)
{
    $exploded = explode(", ", $fixture);
    $substr = trim(substr($exploded[1], 0, strpos($exploded[1], ".")));
    var_dump($substr);
}
*/
/*
//for($i = 0; $i < 18; $i++)
//{
    $numOfTrue = 0;
    $sql_club_name = "Select team_name, team_club_id, team_club FROM Team_entries where team_club = '" . $club_name . "' and team_cal_year = " . $year . " and team_season = '" . $season . "' and day_played = '" . $dayplayed . "'";
    //echo($sql_club_name . "<br>");
    $result_club_name = mysql_query($sql_club_name, $connvbsa) or die(mysql_error());
    while($build_club_name = $result_club_name->fetch_assoc())
    {
        $teamArray = explode(", ", $exploded[$i]);
        $i = 0;
        foreach($teamArray as $arr)
        {
            if($arr != '')
            {
                echo("Array " . $arr . "<br>");
                if($build_club_name['team_name'] == $arr)
                {
                    echo("Team Name " . $build_club_name['team_name'] . "<br>");
                    $numOfTrue++;
                }
            }
        }
    }
    echo ($numOfTrue);
//}
*/
?>