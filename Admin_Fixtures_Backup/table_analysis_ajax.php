<?php 
require_once('../Connections/connvbsa.php'); 
;
error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<?php

echo("<script type='text/javascript'>");
echo("function GetAnalyisData() {");
$team_grade = explode(", ", $team_grades);
$form = 0;
foreach($team_grade as $grade)
{
    if($grade != '')
    {
        $sql = "Select team_name FROM Team_entries WHERE team_season ='$season' AND team_cal_year = $year AND team_grade = '$grade' AND day_played = '$dayplayed' ORDER BY team_name";
        $result_home_games = mysql_query($sql, $connvbsa) or die(mysql_error());
        $teams = $result_home_games->num_rows;
        $rounds = (($teams*2)-2);
        $fixtures = ($teams/2);
        $form++;
        for($i = 0; $i < $rounds; $i++)
        {    
            $sql = "Select * FROM tbl_create_fixtures WHERE season ='$season' AND year = $year AND team_grade = '$grade' AND dayplayed = '$dayplayed' AND round = " . ($i+1);
            $result_home_games = mysql_query($sql, $connvbsa) or die(mysql_error());
            $teams = $result_home_games->fetch_assoc();
            echo("document.getElementById('date_" . $i . "').innerHTML = document.getElementById('A_" . $form . "_date_" . $i . "').value;");
            echo("document.getElementById('table_date_" . $i . "').innerHTML = document.getElementById('A_" . $form . "_date_" . $i . "').value;");
            $round = ($i+1);
            for($j = 0; $j < $fixtures; $j++)
            {
                $team_position = $teams['fix' . ($j+1) . 'home'];
                echo("document.getElementById('" . $grade . "_round_" . ($j+1) . "').innerHTML = '" . $grade . "';");
                echo("document.getElementById('" . $grade . "_round_" . ($i+1) . "_pos_" . ($j+1) . "').innerHTML = '" . $team_position . "';");
            }
        }
    }
}
echo("}");
echo(");");
echo("</script>");


function GetHomeGames($team_grades, $round, $year, $season, $dayplayed)
{
    global $connvbsa;
    
    $team_grade = explode(", ", $team_grades);
    $fixArray = '';
    $fixArray_home_games = '';
    foreach($team_grade as $grade)
    {
        if($grade != '')
        {
            $sql_home_games = "Select fix1home, fix2home, fix3home, fix4home, fix5home, fix6home, team_grade FROM tbl_create_fixtures where team_grade = '" . $grade . "' and year = " . $year . " and season = '" . $season . "' and round = " . $round . " and dayplayed = '" . $dayplayed . "'";
            $result_home_games = mysql_query($sql_home_games, $connvbsa) or die(mysql_error());
            $build_home_games = $result_home_games->fetch_assoc();
            $fixArray_home_games = $build_home_games['fix1home'] . ', ' . $build_home_games['fix2home'] . ', ' . $build_home_games['fix3home'] . ', ' . $build_home_games['fix4home'] . ', ' . $build_home_games['fix5home'] . ', ' . $build_home_games['fix6home'];
            $fixArray = $fixArray . ", " . $fixArray_home_games;
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
    foreach($grades as $grade)
    {
        if($grade != '')
        {
            $sql_away_games = "Select fix1away, fix2away, fix3away, fix4away, fix5away, fix6away, team_grade FROM tbl_create_fixtures where team_grade = '" . $grade . "' and year = " . $year . " and season = '" . $season . "' and round = '" . $round . "' and dayplayed = '" . $dayplayed . "'";
            $result_away_games = mysql_query($sql_away_games, $connvbsa) or die(mysql_error());
            $build_away_games = $result_away_games->fetch_assoc();
            $fixArray_away_games = $build_away_games['fix1away'] . ', ' . $build_away_games['fix2away'] . ', ' . $build_away_games['fix3away'] . ', ' . $build_away_games['fix4away'] . ', ' . $build_away_games['fix5away'] . ', ' . $build_away_games['fix6away'];
            $fixArray = $fixArray . ", " . $fixArray_away_games;
        }
    }
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
    $sql = "Select team_name, team_grade FROM Team_entries WHERE team_season ='$season' AND team_cal_year = $year and day_played = '" . $dayplayed . "' ORDER BY team_name";
    $result_home_games = mysql_query($sql, $connvbsa) or die(mysql_error());
    while($build_home_games = $result_home_games->fetch_assoc())
    {
        $team_name = $build_home_games['team_name'];
        $count = array_count_values($HomeTeamsArray);
        if($team_name == 'Bye')
        {
            $numOfTrue = 0;
        }
        else
        {
            $numOfTrue = $count[$team_name];
        }
        $sql_update = "Update Team_entries Set home_games = " . $numOfTrue . " where team_name = '" . $team_name . "'  and team_season ='" . $season . "' AND team_cal_year = " . $year; 
        $update = mysql_query($sql_update, $connvbsa);
    }
}

// count home games if create fixtures table for all grades have data
$grades = explode(", ", $team_grades);
$fixArray = '';
foreach($grades as $grade)
{
    if($grade != '')
    {
        $sql_count_home_games = "Select * FROM tbl_create_fixtures where year = " . $year . " and season = '" . $season . "' and team_grade = '" . $grade . "'";
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

//don't count home games that have a Bye as opposition
function CountTeams($home_array, $away_array, $team)
{   
    $HomeTeamArray = '';
    $AwayTeamArray = '';
    $numOfTrue = 0;
    $HomeTeamArray = explode(", ", $home_array);
    $AwayTeamArray = explode(", ", $away_array);
    $r = 0;
    foreach($HomeTeamArray as $arr)
    {
        if(($team == $arr) && ($team != ''))
        {
            if(($AwayTeamArray[$r] == 'Bye') || ($arr == 'Bye'))
            {
                $numOfTrue = 0;
            }
            else
            {
                $numOfTrue++;
            }
        }
        $r++;
    }
    return ($numOfTrue);
}

function CountClubs($fixArray, $year, $season, $club_name, $dayplayed)
{   
    global $connvbsa;
    $numOfTrue = 0;
    $sql_club_name = "Select team_name, team_club_id, team_club FROM Team_entries where team_club = '" . $club_name . "' and team_cal_year = " . $year . " and team_season = '" . $season . "' and day_played = '" . $dayplayed . "'";
    $result_club_name = mysql_query($sql_club_name, $connvbsa) or die(mysql_error());
    while($build_club_name = $result_club_name->fetch_assoc())
    {
        $teamArray = explode(", ", $fixArray);
        $i = 0;
        foreach($teamArray as $arr)
        {
            if($build_club_name['team_name'] == $arr)
            {
                $numOfTrue++;
            }
        }
    }
    return ($numOfTrue);
}

?>
<script type='text/javascript'>

$(document).ready(function()
{
    $.fn.displaytableanalysis = function () {
        $('#table_util').empty();
        var team_name = '<?= $team ?>';
        var PlayerCount = $('#no_of_players').val();
        PlayerCount = 4;
        var PlayingDate = $('#playing_date').val();
        var team_grade = $('#team_grade').val();
        var title = '<?= $title ?>';
        var obj = "";
        var fullname = "";
        var memberID = "";
        var player_pos = '';
        $.ajax({
            url:"<?= $url ?>/get_players.php?clubname=" + team_name + "&TeamGrade=" + team_grade + "&year=" + <?= $_SESSION['year'] ?>,
            success : function(data)
            {
                obj = jQuery.parseJSON(data);
                var output = "";
                output += ('table class="table table-striped table-bordered dt-responsive display text-center" border="1">');
                output += ('<tr>');
                output += ('    <td colspan=22 align="center"><b>Table Utilisation (' . $dayplayed . ')</td>');
                output += ('</tr>');
                output += ('<tr>');
                output += ('    <td rowspan=3 align="center">Grade</td>');
                output += ('    <td rowspan=3 align="center">Club</td>');
                output += ('    <td rowspan=3 align="center">Team</td>');
                output += ('    <td rowspan=3 align="center">Tables</td>');
                output += ('    <td colspan=18 align="center">Round/Date</td>');
                output += ('</tr>');
                output += ('<tr>');
                for($i = 0; $i < 18; $i++)
                {
                    output += ("<td align='center'>" . ($i+1) . "</td>");
                }
                output += ('</tr>');
                output += ('<tr>');
                for($i = 0; $i < 18; $i++)
                {
                    output += ("<td align='center' id='table_date_" . $i . "'></td>");
                }
                output += ('</tr>');

                $sql_club = 'Select distinct team_club_id, team_club from Team_entries where team_cal_year = ' . $year . ' and team_season = "' . $season . '" and day_played = "' . $dayplayed . '" order by team_club';
                $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
                $z = 0;
                while($row = $result_club->fetch_assoc()) 
                {
                    $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $row["team_club_id"];
                    $result_club_tables = mysql_query($sql_club_tables, $connvbsa) or die(mysql_error());
                    $tables = $result_club_tables->fetch_assoc();
                    $club_tables = $tables['ClubTables'];

                    $sql_team = 'Select distinct team_club_id, team_name, team_grade from Team_entries where team_cal_year = ' . $year . ' and team_club_id = ' . $row['team_club_id'] . ' and team_season = "' . $season . '" and day_played = "' . $dayplayed . '"';
                    $result_team = mysql_query($sql_team, $connvbsa) or die(mysql_error());
                    $y = 0;
                    while($row_team = $result_team->fetch_assoc()) 
                    {
                        output += ("<tr>");
                        output += ('<td align="center">' . $row_team['team_grade'] . '</td>');
                        output += ('<td align="center">&nbsp;</td>');
                        output += ('<td align="center">' . $row_team["team_name"] . '</td>');
                        output += ('<td align="center">&nbsp;</td>');
                        for($j = 0; $j < 18; $j++)
                        {
                            $HomeFixArray = GetHomeGames($team_grades, ($j+1), $year, $season, $dayplayed);
                            $AwayFixArray = GetAwayGames($team_grades, ($j+1), $year, $season, $dayplayed);
                            $count = CountTeams($HomeFixArray, $AwayFixArray, $row_team['team_name'], $dayplayed);
                            output += ('<td align="center">');
                            echo($count*2);
                            output += ('</td>');
                        }
                        output += ("</tr>");
                        $y++;
                    }
                    output += ("<tr>");
                    output += ('<td align="center"></td>');
                    output += ('<td align="center"><b>' . $row['team_club'] . '</b></td>');
                    output += ('<td align="center"></td>');
                    output += ('<td align="center" id="club_tables_' . $z . '"><b>' . $club_tables . '</b></td>');
                    for($j = 0; $j < 18; $j++)
                    {   
                        $fixArray = GetHomeGames($team_grades, ($j+1), $year, $season, $dayplayed);
                        $club_count = CountClubs($fixArray, $year, $season, $row['team_club'], $dayplayed);
                        if($club_tables < ($club_count*2))
                        {
                            $colour = 'style=background-color:red; color:white';
                        }
                        else
                        {
                            $colour = '';
                        }
                        output += ('<td align="center" id="round_tables_' . $z . '" ' . $colour . '><b>');
                        echo($club_count*2);
                        output += ('</b></td>');
                    }
                    output += ("</tr>");
                    $z++;
                }
                output += ("<tr>");
                output += ("<td colspan=22>&nbsp;</td>");
                output += ("</tr>");

                output += ("<tr>");
                output += ("<td>&nbsp;</td>");
                $grades = explode(", ", $team_grades);
                foreach($grades as $grade)
                {
                    if($grade != '')
                    {
                        output += ('<td colspan="7" align="center">' . $grade . ' Table Sort By:-&nbsp;'); 
                        output += ('<select name="sort_order_<?= $form_no ?>" id="sort_order_<?= $form_no ?>" onchange="GetSort(this, <?= $form_no ?>, '<?= $season ?>', '<?= $year ?>', '<?= $dayplayed ?>', '<?= $grade ?>')">');         
                        if(!isset($_POST['Sortby']))
                        {
                            output += ('<option value="fix_sort" selected>Table Sort</option>');
                        }
                            output += ('<option value="fix_sort">Team Table Sort</option>');
                            output += ('<option value="team_id_dec">Team ID DESC</option>');
                            output += ('<option value="team_name_dec">Team Name DESC</option>');
                            output += ('<option value="team_id_asc">Team ID ASC</option>');
                            output += ('<option value="team_name_asc">Team Name ASC</option>');
                            output += ('<option value="rand">Shuffle</option>');
                            output += ('</select>');
                            output += ('</td>');
                    }
                } 
                output += ("</tr>");
                output += ("</tbody>");
                output += ("</table>");
                $($.parseHTML(output)).appendTo('#add');
            } // success
        }); // ajax
    } // function
});
</script>


