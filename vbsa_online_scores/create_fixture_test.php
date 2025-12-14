<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include("header.php"); 
include('connection.inc');
include('php_functions.php'); 

?>
<link rel="stylesheet" type="text/css" href="<?= $url ?>/calendar/codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="<?= $url ?>/calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">
<script src="<?= $url ?>/calendar/codebase/dhtmlxcommon.js"></script>
<script src="<?= $url ?>/calendar/codebase/dhtmlxcalendar.js"></script>

<script>

function FillFixtureButton() 
{
    var grade;
    var type;
    var team_grade;
    grade = document.getElementById('fixture').value.substring(0, 1);
    team_grade = document.getElementById('fixture').value;
    if(document.getElementById('fixture').value.substring(2, 3) == 'S')
    {
        type = 'Snooker';
    }
    else if(document.getElementById('fixture').value.substring(2, 3) == 'B')
    {
        type = 'Billiards';
    }
    document.fixture_select.Type.value = type;
    document.fixture_select.Grade.value = grade;
    document.fixture_select.TeamGrade.value = team_grade;
    document.fixture_select.Select.value = 'true';
    document.fixture_select.submit();
}

function SaveFixtures() 
{
    //var play_finals = document.getElementsByName('Finals')[0].value;
    //alert(play_finals);
    var team_grade = document.getElementsByName('TeamGrade')[0].value;
    var no_of_rounds = document.getElementsByName('Rounds')[0].value;
    var no_of_fixtures = document.getElementsByName('Fixtures')[0].value;
    var dayplayed = document.getElementById("dayplayed").value
    var scoredata = new Array;
    var scoredata_teams = new Array;
    var playing_date;
    var round;
    var type;
    for(i = 0; i < no_of_rounds; i++)
    {
        var play_finals = document.getElementById("finals_" + (i+1)).value;
        //alert(play_finals);
        playing_date = document.getElementById("round" + (i+1) + "_date").value;
        round = (i+1);
        for(j = 0; j < no_of_fixtures; j++) 
        {
            scoredata_teams[i+j] = document.getElementById("round" + (i+1) + "_fix" + (j+1) + "_home").value + ", " + document.getElementById("round" + (i+1) + "_fix" + (j+1) + "_away").value + ", " + playing_date + ", " + round + ", " + play_finals; 
            scoredata.push(scoredata_teams[i+j]);
        }
    }
    var scoredata = JSON.stringify(scoredata);  
    var grade = team_grade.substring(0, 1);
    if(team_grade.substring(2, 3) == 'S')
    {
        type = 'Snooker';
    }
    else if(team_grade.substring(2, 3) == 'B')
    {
        type = 'Billiards';
    }   
    //alert(type);
    document.fixture.ButtonName.value = 'SaveFixtures';
    document.fixture.Type.value = type;
    document.fixture.Grade.value = grade;
    document.fixture.TeamGrade.value = team_grade;
    document.fixture.ScoreData.value = scoredata;
    document.fixture.DayPlayed.value = dayplayed;
    document.fixture.action = "create_fixture_test.php";
    document.fixture.submit();
}  

function PopulateCalendar() 
{
    var myCalendar;
    var no_of_rounds = document.getElementsByName('Rounds')[0].value;
    for(i = 0; i < no_of_rounds; i++)
    {
        myCalendar = new dhtmlXCalendarObject("round" + i + "_date");
        myCalendar.setDateFormat("%d-%m-%Y");
        myCalendar.setSkin('dhx_skyblue');
        myCalendar.hideTime();
        myCalendar.hideWeekNumbers();
    }
}
</script>
<?php

if ($_POST['ButtonName'] == "SaveFixtures") 
{
    $type = $_POST['Type'];
    $grade = $_POST['Grade'];
    $team_grade = $_POST['TeamGrade'];
    $dayplayed = $_POST['DayPlayed'];
    $current_year = $_SESSION['year'];
    $season = $_SESSION['season'];
    $no_of_fixtures  = $_POST['Fixtures'];

    // delete existing fixtures
    $sql_delete = "Delete FROM tbl_create_fixtures where year = " . $current_year . " and season = '" . $season . "' and team_grade = '". $team_grade . "'";
    $result_delete = $dbcnx_client->query($sql_delete);

    $scoredata = json_decode(stripslashes($_POST['ScoreData']), true);
    $j = 0;
    $k = 0;
    for ($i = 0; $i < count($scoredata); $i++) 
    {
        $scoresheet = explode(", ", $scoredata[$i]);
        $k = (($j % $no_of_fixtures));
        if($k == 0)
        {
            // get club id for home and away team names
            $sql_home_club = "Select team_club_id, team_name FROM Team_entries where team_grade = '" . $team_grade . "' and team_cal_year = " . $current_year . " and team_season = '" . $season . "' and team_name = '" . $scoresheet[0] . "';";
            $result_home_club = $dbcnx_client->query($sql_home_club) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
            $build_home_club = $result_home_club->fetch_assoc();
            $home_club_id = $build_home_club['team_club_id'];

            $sql_away_club = "Select team_club_id, team_name FROM Team_entries where team_grade = '" . $team_grade . "' and team_cal_year = " . $current_year . " and team_season = '" . $season . "' and team_name = '" . $scoresheet[1] . "';";
            $result_away_club = $dbcnx_client->query($sql_away_club) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
            $build_away_club = $result_away_club->fetch_assoc();
            $away_club_id = $build_away_club['team_club_id'];

            //echo("Home " . $home_club_id . ", away " . $away_club_id . "<br>");

            // insert new fixtures
            $sql_insert = "Insert into tbl_create_fixtures (
            date, 
            type, 
            grade, 
            round, 
            fix" . ($k+1) . "home, 
            fix" . ($k+1) . "away, 
            year, 
            season, 
            team_grade, 
            dayplayed,
            fix" . ($k+1) . "home_club, 
            fix" . ($k+1) . "away_club) 
            Values ('" . 
            MysqlDate($scoresheet[2]) . "', '" . 
            $type . "', '" . 
            $grade . "', " . 
            $scoresheet[3] . ", '" . 
            $scoresheet[0]. "', '" . 
            $scoresheet[1] . "', " . 
            $current_year . ", '" . 
            $season.  "', '" . 
            $team_grade . "', '" . 
            $dayplayed . "', '" . 
            $home_club_id . "', '" . 
            $away_club_id . "')"; 
            $update = $dbcnx_client->query($sql_insert);
        }
        elseif($k == 1)
        {
            $sql_update_1 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 1 " . $sql_update_1 . "<br>");
            $update = $dbcnx_client->query($sql_update_1);
        }
        elseif($k == 2)
        {
            $sql_update_2 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 2 " . $sql_update_2 . "<br>");
            $update = $dbcnx_client->query($sql_update_2);
        }
        elseif($k == 3)
        {
            $sql_update_3 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 3 " . $sql_update_3 . "<br>");
            $update = $dbcnx_client->query($sql_update_3);
        }
        elseif($k == 4)
        {
            $sql_update_4 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 4 " . $sql_update_4 . "<br>");
            $update = $dbcnx_client->query($sql_update_4);
        }
        elseif($k == 5)
        {
            $sql_update_5 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 5 " . $sql_update_5 . "<br>");
            $update = $dbcnx_client->query($sql_update_4);
        }
        elseif($k == 6)
        {
            $sql_update_6 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 6 " . $sql_update_6 . "<br>");
            $update = $dbcnx_client->query($sql_update_4);
        }
        $j++;
    }
}

if ((isset($_POST['Grade']) and $_POST['Grade'] <> '') and (isset($_POST['Type']) and $_POST['Type'] <> '')) 
{
    $type = $_POST['Type'];
    $grade = $_POST['Grade'];
}
else
{
    $type = '';
    $grade = '';
}
$current_year = $_SESSION['year'];
$current_season = $_SESSION['season'];

// get from grade settings table
$sql_grades = "Select * From tbl_team_grade Where grade = '" . $_POST['TeamGrade'] . "' and season = '" . $current_season . "' and fix_cal_year = " . $current_year;
$result_grades = $dbcnx_client->query($sql_grades) or die("Couldn't execute settings query. " . mysqli_error($dbcnx_client));
$build_grades = $result_grades->fetch_assoc();

$NoOfFixtures = $build_grades['no_of_matches'];
$NoOfRounds = $build_grades['no_of_rounds'];
$no_of_games = $build_grades['games_round'];

$modified_colspan = 4;
$NoOfRowsPerRound = 1;
$RoundsperPage = ceil($NoOfRounds/$NoOfRowsPerRound);

$final_start = ($NoOfRounds-1); // start of finals is last but one rounds

$sql = "Select * From tbl_create_fixtures Where team_grade = '" . $_POST['TeamGrade'] . "' AND year = " . $current_year . " AND season = '" . $current_season . "' Order By round";
//echo($sql . "<br>");
$result_fixture = $dbcnx_client->query($sql) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
$num_rows = $result_fixture->num_rows;

if($num_rows > 0)
{
    echo("<script type='text/javascript'>");
    echo("function FillElementArray() {");
    $i = 0;
    while ($build_fixture_data = $result_fixture->fetch_assoc()) 
    {
      $team_grade = $build_fixture_data['team_grade'];
      $dayplayed = $build_fixture_data['dayplayed'];
      echo("document.getElementById('round" . ($i+1) . "_date').value = '" . DisplayDate($build_fixture_data['date']) . "';");
      for ($j = 0; $j < $NoOfFixtures; $j++) 
      {
        echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_home').value = '" . $build_fixture_data["fix" . ($j+1) . "home"] . "';");
        echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_away').value = '" . $build_fixture_data["fix" . ($j+1) . "away"] . "';");
      }
      $i++;
    }
    echo("}");
    echo("window.onload = function()");
    echo("{");
    echo("FillElementArray();");
    echo("PopulateCalendar();");
    echo("}");
}
echo("</script>");
?>
<center>
<form name='fixture_select' method='post' action='create_fixture_test.php'>
<input type='hidden' name='Year' />
<input type='hidden' name='Season' />
<input type='hidden' name='HomeTeam' />
<input type='hidden' name='AwayTeam' />
<input type='hidden' name='RoundNo' />
<input type='hidden' name='Grade' />
<input type='hidden' name='Type' />
<input type='hidden' name='TeamGrade' value='<?php echo($team_grade); ?>'/>
<input type='hidden' name='Select' />
<input type='hidden' name='FixtureDate' />

<table border='0' align='center' cellpadding='0' cellspacing='10' width='50%'>
    <tr>
        <td colspan='3'><h1 align='center'>Create fixture for:-</h1></td>
    </tr>
    <tr>
        <td colspan='3'>&nbsp;</td>
    </tr>
    <tr>
        <td align='center' valign='top'><b>Select Team Grade:&nbsp;&nbsp; <select id='fixture' onchange='FillFixtureButton()'>
<?php

if(isset($_POST['Fixture'])) 
{
    echo("<option value='' selected='selected'>" . $_POST['Fixture'] . "</option>");
}
else
{
    echo("<option value='' selected='selected'></option>");
}

// fill fixture dropdown
$sql = "Select distinct team_grade, grade, type, dayplayed FROM tbl_create_fixtures  where season = '". $current_season . "' and year = '". $current_year . "' order by team_grade";
//echo($sql . "<br>");
$result_fixture = $dbcnx_client->query($sql) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
while($build_fixture = $result_fixture->fetch_assoc()) 
{
    echo("<option value='" . $build_fixture['team_grade'] . "'>" . $build_fixture['team_grade'] . " " . $build_fixture['grade'] . " Grade" . " " . $build_fixture['type'] . " (" . $build_fixture['dayplayed'] . ")</option>");
}
echo("</select></b>");
?>
        
        </td>
    </tr>
    <tr>
        <td colspan='3'><h4 align='center'><?php echo($_POST['TeamGrade']); ?></h4></td>
    </tr>
    <tr>
        <td colspan='3'>&nbsp;</td>
    </tr>
</table>
</form>
<?php
if($_POST['Select'] == 'true')
{
    echo("<form name='fixture' method='post'>");
    echo("<input type='hidden' name='HomeTeam' />");
    echo("<input type='hidden' name='AwayTeam' />");
    echo("<input type='hidden' name='RoundNo' />");
    echo("<input type='hidden' name='Select' />");
    echo("<input type='hidden' name='FixtureDate' />");
    echo("<input type='hidden' name='RoundSelected' value=''/>");
    echo("<input type='hidden' name='TeamScoring' />");
    echo("<input type='hidden' name='Year' id='year' value=" . $_SESSION['year'] . " />");
    echo("<input type='hidden' name='Season' id='season' value='" . $_SESSION['season'] . "' />");
    echo("<input type='hidden' name='ScoreData' />");
    echo("<input type='hidden' name='DayPlayed' id='dayplayed' value='" . $dayplayed . "' />");
    echo("<input type='hidden' name='Type' id='type' value='" . $type . "' />");
    echo("<input type='hidden' name='ButtonName' />");
    if($num_rows > 0)
    {
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>");
        $modified_colspan = 3;
        $finalist = 'No';
        for ($j = 0; $j < $RoundsperPage; $j++) 
        { // number of rows per season
            $x = $NoOfRowsPerRound * $j;
            echo ("<tr>");
            for ($i = 0; $i < $NoOfRowsPerRound; $i++) { //no of rounds per row

                if((($i + 1) + $x) == $final_start)
                {
                    $final_title = "Semi Final";
                    $finalist = 'Yes';
                    //$NoOfFixtures = 2;
                }
                elseif(($i + 1 + $x + 1) > $final_start)
                {
                    $final_title = "Grand Final";
                    $finalist = 'Yes';
                    //$NoOfFixtures = 1;
                }
                else
                {
                    $final_title = "ROUND " . (($i + 1) + $x);
                    $finalist = 'No';
                }

                echo ("<td colspan=" . $modified_colspan . " align='center'>" . $final_title . "</td>");
                echo("<input type='hidden' id='finals_" . (($i + 1) + $x) . "' value='" . $finalist . "' />");
                $round = (($i + 1) + $x);
            }
            echo ("</tr>");
            echo ("<tr>");
            for ($i = 0; $i < $NoOfRowsPerRound; $i++) 
            { //no of rounds per row (date entry)
                echo ("<td colspan=" . $modified_colspan . " align='center'><input type='text' id='round" . (($i + 1) + $x) . "_date'></td>");
            }
            echo ("</tr>");
            for ($l = 0; $l < $NoOfFixtures; $l++) 
            { // no of fixtures per row
                echo ("<tr>");
                for ($k = 0; $k < $NoOfRowsPerRound; $k++) 
                { // no of rounds per row
                    echo ("<td align='center'><select id='round" . (($k + $x) + 1) . "_fix" . ($l + 1) . "_home'>");
                    // start dropbox fill
                    $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $_SESSION['year'] . " and team_grade = '" . $team_grade . "'";
                    $result_home_team = $dbcnx_client->query($sql_home_team);
                    while($build_home_team = $result_home_team->fetch_assoc()) 
                    {
                      echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
                    }
                    echo("<option value='Bye'>Bye</option>");
                    echo("<option value='TBA'>TBA</option>");
                    echo("</select></td>");
                    // end dropbox fill
                    echo ("<td align='center'>v</td>");
                    echo ("<td align='center'><select id='round" . (($k + $x) + 1) . "_fix" . ($l + 1) . "_away'>");
                    // start dropbox fill
                    $sql_away_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $_SESSION['year'] . " and team_grade = '" . $team_grade . "'";
                    $result_away_team = $dbcnx_client->query($sql_away_team);
                    while($build_away_team = $result_away_team->fetch_assoc()) 
                    {
                      echo("<option value='" . $build_away_team['team_name'] . "'>" . $build_away_team['team_name'] . "</option>");
                    }
                    echo("<option value='Bye'>Bye</option>");
                    echo("<option value='TBA'>TBA</option>");
                    echo("</select></td>");
                    // end dropbox fill
                }
                echo ("</tr>");
            }
            echo("<tr>");
            echo("<td class='text-center' colspan=7>&nbsp;</td>");
            echo("</tr>");
        }
        echo("<input type='hidden' name='Grade' value='" . $grade . "' />");
        echo("<input type='hidden' name='TeamGrade' value='" . $team_grade . "' />");
        echo("<input type='hidden' name='Type' value='" . $type . "' />");
        echo("<input type='hidden' name='Fixtures' value = '" . $NoOfFixtures . "' />");
        echo("<input type='hidden' name='Rounds' value = '" . $NoOfRounds . "' />");
        echo("<tr>");
        echo("<td class='text-center' colspan='3'><a class='btn btn-primary btn-xs' onclick='SaveFixtures()'; >Save Fixtures for " . $team_grade . "</a></td>");
        echo("</tr>");
        echo("</table>");
        echo("</form>");
    }
    else
    {   
?>
        <table class='table table-striped dt-responsive nowrap display' width='100%'>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>  
          <tr>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="center">No fixtures to display.</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>  
          <tr>
            <td>&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>  
        </table>
<?php
    }
}
?>
</center>
<?php

include("footer.php"); 

?>
