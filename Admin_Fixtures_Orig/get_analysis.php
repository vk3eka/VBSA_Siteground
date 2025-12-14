<?php
require_once('../Connections/connvbsa.php'); 

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

function GetRounds($teams) 
{
    // get number of rounds per number of teams
    switch ($teams) {
        case 4:
            $rounds = 15;
            break;
        case 6:
            $rounds = 15;
            break;
        case 8:
            $rounds = 14;
            break;
        case 10:
            $rounds = 18;
            break;
        case 12:
            $rounds = 16;
            break;
        case 14:
            $rounds = 13;
            break;
        default:
            $rounds = 18;
    }
    return $rounds;
}

function GetHomeGames($team_grades, $round, $year, $season, $dayplayed)
{
    global $connvbsa;
    
    //echo(var_dump($team_grades) . "<br>");
    $team_grade = explode(", ", $team_grades);
    //echo('Grade ' . $team_grade . "<br>");
    $fixArray = '';
    $fixArray_home_games = '';
    //$fixArray_home_games = [];
    //$i = 0;
    foreach($team_grade as $grade)
    { 
        //echo('Grade ' . $grade . "<br>");
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
/*
// count home games if create fixtures table for all grades have data
$grades = explode(", ", $team_grades);
$fixArray = '';
foreach($grades as $grade)
{
    if($grade != '')
    {
        $sql_count_home_games = "Select * FROM tbl_create_fixtures where year = " . $year . " and season = '" . $season . "' and team_grade = '" . $grade . "'";
        //$sql_count_home_games = "Select * FROM tbl_create_fixtures where year = " . $year . " and season = '" . $season . "'";
        $result_count_home_games = mysql_query($sql_count_home_games, $connvbsa) or die(mysql_error());
        $count_home_games_rows = $result_count_home_games->num_rows;
        if($count_home_games_rows == 0)
        {
            $data_exists = false;
            break;
        }
        else
        {
            $data_exists = true;
        }
    }
}
if($data_exists)
{
    CountHomeGames($team_grades, $year, $season, $dayplayed);
}
*/
//don't count home games that have a Bye as opposition
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

function GetAnalyisData($team_grades, $year, $season, $dayplayed, $max_no_of_teams) {

    //$max_no_of_teams = 18;
    $analysis_output = '';
    $analysis_output .= ("<div id='page10'>");
    $analysis_output .= ('<table class="table table-striped table-bordered dt-responsive display text-center" border="1">');
    $analysis_output .= ('<tr>
        <td colspan=22 align="center"><b>Analysis Data (' . $dayplayed . ')</td>
    </tr>
    <tr>
        <td rowspan=3  colspan=4 align="center">Grade</td>
        <td colspan=' . $max_no_of_teams . ' align="center">Round/Date</td>
    </tr>');

    $analysis_output .= ('<tr>');
    for($i = 0; $i < $max_no_of_teams; $i++)
    {
        $analysis_output .= ("<td align='center'>" . ($i+1) . "</td>");
    }
    $analysis_output .= ('</tr>');

    $sql_playing_dates = 'Select * from tbl_create_fixtures where year = ' . $year . ' and team_grade = "' . $team_grade . '" and season = "' . $season . '" and round = ' . ($i+1);
    $result_playing_dates = mysql_query($sql_playing_dates, $connvbsa) or die(mysql_error());
    //echo("<script type='text/javascript'>");
     //$analysis_output .= ('<tr>');
    //while($row = $result_playing_dates->fetch_assoc()) 
    //{
        //$row_date = $row['date'];

        //$analysis_output .= ("<td align='center' id='date_" . $i . "'>'" . $row_date . "'</td>");

        //$analysis_output .= ("document.getElementById('A_" . $form_no . "_date_" . $i . "').value = '" . $row_date . "';");
        //$analysis_output .= ("document.getElementById('B_" . $form_no . "_date_" . $i . "').value = '" . $row_date . "';");
    //}
    // $analysis_output .= ('</tr>');

    $analysis_output .= ('<tr>');
    for($i = 0; $i < $max_no_of_teams; $i++)
    {
        $analysis_output .= ("<td align='center' id='date_" . $i . "'>Here</td>");
    }
    $analysis_output .= ('</tr>');

    // get team grades from Team entries table
    $sql_grades_menu = 'Select team_grade, count(team_grade) as count from Team_entries Join clubs where clubs.ClubNumber = Team_entries.team_club_id and team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" AND team_season = "' . $season . '"  group by team_grade';

    //$analysis_output .= ($sql_grades_menu . "<br>");

    $result_grades_menu = mysql_query($sql_grades_menu, $connvbsa) or die(mysql_error());
    $i = 0;
    while($build_data_menu = $result_grades_menu->fetch_assoc())
    {
        $no_of_home_teams = $build_data_menu['count'];
        $no_of_rounds = $max_no_of_teams;
        for($y = 0; $y < ceil($no_of_home_teams/2); $y++) // home teams in team grade
        {   
            $analysis_output .= ("<tr>");
            $analysis_output .= ('<td colspan=4 align="center" id="' . $build_data_menu['team_grade'] . '_round_' . ($y+1) . '">' . $build_data_menu['team_grade'] . '_round_' . ($y+1) . '</td>');
            for($j = 0; $j < $no_of_rounds; $j++)
            {
                if(($build_data_menu['team_grade'] . '_round_' . ($j+1) . '_pos_' . ($y+1)) != '')
                {
                    $analysis_output .= ('<td align="center" class="round_' . $j . '" id="' . $build_data_menu['team_grade'] . '_round_' . ($j+1) . '_pos_' . ($y+1) . '">&nbsp;</td>');
                }
                else
                {
                    $analysis_output .= ('<td align="center" class="round_' . $j . '" id="' . $build_data_menu['team_grade'] . '_round_' . ($j+1) . '_pos_' . ($y+1) . '">' . $build_data_menu['team_grade'] . '_round_' . ($j+1) . '_pos_' . ($y+1) . '</td>');
                }
            }
            $analysis_output .= ("</tr>");
            $i++;
        }
        $analysis_output .= ("<tr>");
        $analysis_output .= ("<td class='text-center' colspan='22'>&nbsp;</td>");
        $analysis_output .= ("</tr>");
    }
    echo('</table>');



    $team_grade = explode(", ", $team_grades);
    //$analysis_output = '';
    $form = 0;
    $analysis_output .= ("<script>");
    foreach($team_grade as $grade)
    {
        //echo("Grade " . $grade . "<br>");
        if($grade != '')
        {
            $sql = "Select team_name FROM Team_entries WHERE team_season ='$season' AND team_cal_year = $year AND team_grade = '$grade' AND day_played = '$dayplayed' ORDER BY team_name";
            //echo("SQL " . $sql . "<br>");
            $result_home_games = mysql_query($sql, $connvbsa) or die(mysql_error());
            $teams = $result_home_games->num_rows;
            $rounds = GetRounds($teams); // based on number of teams
            $fixtures = ($teams/2);
            $form++;
            for($i = 0; $i < $rounds; $i++)
            {    
                /*
                $sql_playing_dates = 'Select * from tbl_create_fixtures where year = ' . $year . ' and team_grade = "' . $team_grade . '" and season = "' . $season . '" and round = ' . ($i+1);
                $result_playing_dates = mysql_query($sql_playing_dates, $connvbsa) or die(mysql_error());
                echo("<script type='text/javascript'>");
                while($row = $result_playing_dates->fetch_assoc()) 
                {
                    $row_date = $row['date'];
                    $analysis_output .= ("document.getElementById('A_" . $form_no . "_date_" . $i . "').value = '" . $row_date . "';");
                    $analysis_output .= ("document.getElementById('B_" . $form_no . "_date_" . $i . "').value = '" . $row_date . "';");
                }
                echo("</script>");
                */
                
                $sql = "Select * FROM tbl_create_fixtures WHERE season ='$season' AND year = $year AND team_grade = '$grade' AND dayplayed = '$dayplayed' AND round = " . ($i+1);
                //echo($sql . "<br>");
                $result_home_games = mysql_query($sql, $connvbsa) or die(mysql_error());
                $teams = $result_home_games->fetch_assoc();
                $row_date = $teams['date'];
                //echo($row_date . "<br>");
                $analysis_output .= ("document.getElementById('A_" . $form_no . "_date_" . $i . "').value = '" . $row_date . "';");
                $analysis_output .= ("document.getElementById('B_" . $form_no . "_date_" . $i . "').value = '" . $row_date . "';");

                $analysis_output .= ("document.getElementById('date_" . $i . "').innerHTML = document.getElementById('A_" . $form . "_date_" . $i . "').value;");
                $analysis_output .= ("document.getElementById('table_date_" . $i . "').innerHTML = document.getElementById('A_" . $form . "_date_" . $i . "').value;");
                $round = ($i+1);
                for($j = 0; $j < $fixtures; $j++)
                {
                    $team_position = $teams['fix' . ($j+1) . 'home'];
                    $analysis_output .= ("document.getElementById('" . $grade . "_round_" . ($j+1) . "').innerHTML = '" . $grade . "';");
                    $analysis_output .= ("document.getElementById('" . $grade . "_round_" . ($i+1) . "_pos_" . ($j+1) . "').innerHTML = '" . $team_position . "';");
                }
            }
        }
    }
    $analysis_output .= ("</script>");
    //echo("Output " . $analysis_output . "<br>");
    return $analysis_output;
}

$dayplayed =  $_GET['dayplayed'];
//echo($dayplayed . "<br>");
$max_no_of_teams =  $_GET['max_teams'];
$year =  $_GET['year'];
$season =  $_GET['season'];
$team_grades =  $_GET['team_grades'];
//echo($max_no_of_teams . "<br>");
//$team_grades = array('BVS1', 'CVS1', 'PB(1)');

$analysis_output = GetAnalyisData($team_grades, $year, $season, $dayplayed, $max_no_of_teams);
//echo($analysis_output . "<br>");

/*
$output = '';
$output .= ('<table class="table table-striped table-bordered dt-responsive display text-center" border="1">
<tr>
    <td colspan=22 align="center"><b>Table Utilisation (' . $dayplayed . ')</td>
</tr>
<tr>
    <td rowspan=3 align="center">Grade</td>
    <td rowspan=3 align="center">Club</td>
    <td rowspan=3 align="center">Team</td>
    <td rowspan=3 align="center">Tables</td>
    <td colspan=' . $max_no_of_teams . ' align="center">Round/Date</td>
</tr>');
$output .= ('<tr>');
for($i = 0; $i < $max_no_of_teams; $i++)
{
    $output .=  ("<td align='center'>" . ($i+1) . "</td>");
}
$output .=  ('</tr>');
$output .=  ('<tr>');
for($i = 0; $i < $max_no_of_teams; $i++)
{
    $output .=  ("<td align='center' id='table_date_" . $i . "'></td>");
}
$output .=  ('</tr>');

$sql_club = 'Select distinct team_club_id, team_club from Team_entries where team_cal_year = ' . $year . ' and team_season = "' . $season . '" and day_played = "' . $dayplayed . '" order by team_club';

$result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
$z = 0;
$clashes = 0;
while($row = $result_club->fetch_assoc()) 
{
    $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $row["team_club_id"];
    $result_club_tables = mysql_query($sql_club_tables, $connvbsa) or die(mysql_error());
    $tables = $result_club_tables->fetch_assoc();
    $club_tables = $tables['ClubTables'];

    $sql_team = 'Select distinct team_club_id, team_name, team_grade from Team_entries where team_cal_year = ' . $year . ' and team_club_id = ' . $row['team_club_id'] . ' and team_season = "' . $season . '" and day_played = "' . $dayplayed . '"';
    //echo($team_grades[0] . "<br>");
    $result_team = mysql_query($sql_team, $connvbsa) or die(mysql_error());
    $y = 0;
    while($row_team = $result_team->fetch_assoc()) 
    {
        $output .=  ("<tr>");
        $output .=  ('<td align="center">' . $row_team['team_grade'] . '</td>');
        $output .=  ('<td align="center">&nbsp;</td>');
        $output .=  ('<td align="center">' . $row_team["team_name"] . '</td>');
        $output .=  ('<td align="center">&nbsp;</td>');
        for($j = 0; $j < $max_no_of_teams; $j++)
        {
            $HomeFixArray = GetHomeGames($team_grades, ($j+1), $year, $season, $dayplayed);
            $AwayFixArray = GetAwayGames($team_grades, ($j+1), $year, $season, $dayplayed);
            $count = CountTeams($HomeFixArray, $AwayFixArray, $row_team['team_name'], $dayplayed, ($j+1));
            //echo("Team Count " . $count . " Y " . $y . "<br>");
            $output .=  ('<td align="center">');
            $output .=  ($count*2);
            $output .=  ('</td>');
        }
        $output .=  ("</tr>");
        $y++;
    }
    $output .=  ("<tr>");
    $output .=  ('<td align="center"></td>');
    $output .=  ('<td align="center"><b>' . $row['team_club'] . '</b></td>');
    $output .=  ('<td align="center"></td>');
    $output .=  ('<td align="center" id="club_tables_' . $z . '"><b>' . $club_tables . '</b></td>');

    for($j = 0; $j < $max_no_of_teams; $j++)
    {   
        $fixArray = GetHomeGames($team_grades, ($j+1), $year, $season, $dayplayed);
        $club_count = CountClubs($fixArray, $year, $season, ($row['team_club']), $dayplayed, ($j+1));
        if($club_tables < ($club_count*2))
        {
            $colour = 'style=background-color:red; color:white';
            $clashes++;
        }
        else
        {
            $colour = '';
        }
        $output .=  ('<td align="center" id="round_tables_' . $z . '" ' . $colour . '><b>');
        $output .=  ($club_count*2);
        $output .=  ('</b></td>');
    }
    $output .=  ("</tr>");
    $z++;
}
$output .=  ("<tr>");
$output .=  ("<td colspan=22>&nbsp;</td>");
$output .=  ("</tr>");
$output .=  ("</table>"); 

echo($analysis_output . "<br>");
echo($output);
*/
/*
$arr_output = array();
$arr_output['analysis'] = $analysis_output;
$arr_output['tables'] = $output;

//header('Content-type: application/json');
echo ($arr_output);
*/
//echo($analysis_output . ", " . $output);
echo($analysis_output);
?>
