<?php

include('connection.inc');
include('header.php');
include('php_functions.php'); 

$month = date('m');
if($month < '08')
{
  $season = 'S1';
}
else
{
  $season = 'S2';
}

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
    if(document.getElementById('fixture').value.substring(2, 3) == 'S')
    {
        type = 'Snooker';
    }
    else if(document.getElementById('fixture').value.substring(2, 3) == 'B')
    {
        type = 'Billiards';
    }
    if(document.getElementById('fixture').value.substring(1, 1) == 'V')
    {
        team_grade = 'State';
    }
    else if(document.getElementById('fixture').value.substring(1, 1) == 'P')
    {
        team_grade = 'Premier';
    }
    document.fixture_select.Type.value = type;
    document.fixture_select.Grade.value = grade;
    document.fixture_select.TeamGrade.value = team_grade;
    document.fixture_select.Select.value = 'true';
    document.fixture_select.submit();
}

function SaveFixtures(team_grade) 
{
    var no_of_rounds = 17;
    var no_of_fixtures = 3;
    var scoredata = new Array;
    var scoredata_teams = new Array;
    var playing_date;
    var round;
    for(i = 0; i < no_of_rounds; i++)
    {
        playing_date = document.getElementById("round" + (i+1) + "_date").value;
        round = (i+1);
        for(j = 0; j < no_of_fixtures; j++) 
        {
         scoredata_teams[i+j] = document.getElementById("round" + (i+1) + "_fix" + (j+1) + "_home").value + ", " + document.getElementById("round" + (i+1) + "_fix" + (j+1) + "_away").value + ", " + playing_date + ", " + round; 
         scoredata.push(scoredata_teams[i+j]);
        }
    }
    var scoredata = JSON.stringify(scoredata);
    grade = team_grade.substring(0, 1);
    if(team_grade.substring(2, 3) == 'S')
    {
        type = 'Snooker';
    }
    else if(team_grade.substring(2, 3) == 'B')
    {
        type = 'Billiards';
    }
    document.fixture.ButtonName.value = 'SaveFixtures';
    document.fixture.Type.value = type;
    document.fixture.Grade.value = grade;
    document.fixture.TeamGrade.value = team_grade;
    document.fixture.ScoreData.value = scoredata;
    document.fixture.action = "edit_fixtures.php";
    document.fixture.submit();
}  

function PopulateCalendar() 
{
    var myCalendar;
    for(i = 0; i < 17; i++)
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

    // delete existing fixtures
    $sql_delete = "Delete FROM tbl_fixtures where year = " . $current_year . " and season = '" . $season . "' and team_grade = '". $team_grade . "'";
    $result_delete = $dbcnx_client->query($sql_delete);

    $scoredata = json_decode(stripslashes($_POST['ScoreData']), true);
    $no_of_fixtures = 2; // need to check for more fixtures
    $j = 0;
    $k = 0;
    for ($i = 0; $i < count($scoredata); $i++) 
    {
        $scoresheet = explode(", ", $scoredata[$i]);
        $k = (($j % $no_of_fixtures));
        if($k == 0)
        {
            // insert new fixtures
            $sql_insert = "Insert into tbl_fixtures (
            date, 
            type, 
            grade, 
            round, 
            fix" . ($k+1) . "home, 
            fix" . ($k+1) . "away, 
            year, 
            season, 
            team_grade, 
            dayplayed) 
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
            $dayplayed . "')";
            $update = $dbcnx_client->query($sql_insert);
        }
        elseif($k == 1)
        {
            $sql_update_1 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year
            . " and season = '" . $season . "'";
            $update = $dbcnx_client->query($sql_update_1);
        }
        elseif($k == 2)
        {
            $sql_update_2 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year
            . " and season = '" . $season . "'";
            $update = $dbcnx_client->query($sql_update_2);
        }
        elseif($k == 3)
        {
            $sql_update_3 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year
            . " and season = '" . $season . "'";
            $update = $dbcnx_client->query($sql_update_3);
        }
        elseif($k == 4)
        {
            $sql_update_4 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year
            . " and season = '" . $season . "'";
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

// get from settings table
$sql_settings = "Select * From tbl_settings Where year = " . $current_year . " AND season = '" . $current_season . "'";
$result_settings = $dbcnx_client->query($sql_settings) or die("Couldn't execute settings query. " . mysqli_error($dbcnx_client));
$build_data = $result_settings->fetch_assoc();
if($type == 'Billiards')
{
    switch($grade)
    {
        case 'A':
            $NoOfFixtures = $build_data['no_of_fixtures_AB'];
            $NoOfRounds = $build_data['no_of_rounds_AB'];
            break;
        case 'B':
            $NoOfFixtures = $build_data['no_of_fixtures_BB'];
            $NoOfRounds = $build_data['no_of_rounds_BB'];
            break;
        case 'C':
            $NoOfFixtures = $build_data['no_of_fixtures_CB'];
            $NoOfRounds = $build_data['no_of_rounds_CB'];
            break;
        case 'D':
            $NoOfFixtures = $build_data['no_of_fixtures_DB'];
            $NoOfRounds = $build_data['no_of_rounds_DB'];
            break;
    }
}
elseif($type == 'Snooker')
{
    switch($grade)
    {
        case 'A':
            $NoOfFixtures = $build_data['no_of_fixtures_AS'];
            $NoOfRounds = $build_data['no_of_rounds_AS'];
            break;
        case 'B':
            $NoOfFixtures = $build_data['no_of_fixtures_BS'];
            $NoOfRounds = $build_data['no_of_rounds_BS'];
            break;
        case 'C':
            $NoOfFixtures = $build_data['no_of_fixtures_CS'];
            $NoOfRounds = $build_data['no_of_rounds_CS'];
            break;
        case 'D':
            $NoOfFixtures = $build_data['no_of_fixtures_DS'];
            $NoOfRounds = $build_data['no_of_rounds_DS'];
            break;
    }
}
$modified_colspan = 3;
$NoOfRowsPerRound = 1;
$RoundsperPage = ceil($NoOfRounds/$NoOfRowsPerRound);

$final_start = 16; // start of finals in round 16

$sql = "Select * From tbl_fixtures Where grade = '" . $grade . "' AND type = '" . $type . "'
AND year = " . $current_year . " AND season = '" . $current_season . "' Order By round";
$result_fixture = $dbcnx_client->query($sql) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
$num_rows = $result_fixture->num_rows;
//echo("Rows " . $NoOfFixtures . "<br>");
if($num_rows > 0)
{
    echo("<script type='text/javascript'>");
    echo("function FillElementArray() {");
    $i = 0;
    while ($build_data = $result_fixture->fetch_assoc()) 
    {
      $team_grade = $build_data['team_grade'];
      $dayplayed = $build_data['dayplayed'];
      echo("document.getElementById('round" . ($i+1) . "_date').value = '" . DisplayDate($build_data['date']) . "';");
      for ($j = 0; $j < $NoOfFixtures; $j++) 
      {
        echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_home').value = '" . $build_data["fix" . ($j+1) . "home"] . "';");
        echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_away').value = '" . $build_data["fix" . ($j+1) . "away"] . "';");
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
<form name='fixture_select' method='post' action='edit_fixtures.php'>
<input type='hidden' name='Year' />
<input type='hidden' name='Season' />
<input type='hidden' name='HomeTeam' />
<input type='hidden' name='AwayTeam' />
<input type='hidden' name='RoundNo' />
<input type='hidden' name='Grade' />
<input type='hidden' name='Type' />
<input type='hidden' name='TeamGrade' value='" . $team_grade . "'/>
<input type='hidden' name='Select' />
<input type='hidden' name='FixtureDate' />

<table border='0' align='center' cellpadding='0' cellspacing='10' width='50%'>
    <tr>
        <td colspan='3'><h1 align='center'>Select fixture to edit</h1></td>
    </tr>
    <tr>
        <td colspan='3'>&nbsp;</td>
    </tr>
    <tr>
        <td align='center' valign='top'><b>Select Fixture:&nbsp;&nbsp; <select id='fixture' onchange='FillFixtureButton()'>
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
$sql = "Select distinct team_grade, grade, type, dayplayed FROM vbsa3364_vbsa2.tbl_fixtures order by type";
//echo($sql . "<br>");
$result_fixture = $dbcnx_client->query($sql) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
while($build_fixture = $result_fixture->fetch_assoc()) 
{
    if(substr($build_fixture['team_grade'], 1, 1) == 'V')
    {
        $text_grade = 'State';
    }
    elseif(substr($build_fixture['team_grade'], 1, 1) == 'P')
    {
        $text_grade = 'Premier';
    }

    //echo($build_fixture['team_grade'] . " " . $build_fixture['grade'] . " Grade" . " " . $team_grade . " " . $build_fixture['type'] . " (" . $build_fixture['dayplayed'] . ")<br>");
    echo("<option value='" . $build_fixture['team_grade'] . "'>" . $build_fixture['team_grade'] . " " . $build_fixture['grade'] . " Grade" . " " . $text_grade . " " . $build_fixture['type'] . " (" . $build_fixture['dayplayed'] . ")</option>");
}

//echo("<option value='APS'>A Grade Premier Snooker (Wed)</option>");
//echo("<option value='BPS'>B Grade Premier Snooker (Wed)</option>");
//echo("<option value='CPS'>C Grade Premier Snooker (Wed)</option>");
//echo("<option value='DPS'>D Grade Premier Snooker (Wed)</option>");
//echo("<option value=''>----------------</option>");
//echo("<option value='AVS'>A Grade State Snooker (Mon)</option>");
//echo("<option value='BVS'>B Grade State Snooker (Mon)</option>");
//echo("<option value='CVS'>C Grade State Snooker (Mon)</option>");
//echo("<option value='DVS'>D Grade State Snooker (Mon)</option>");
//echo("<option value=''>----------------</option>");
//echo("<option value='AVB'>A Grade State Billiards (Wed)</option>");
//echo("<option value='BVB'>B Grade State Billiards (Wed)</option>");
//echo("<option value='CVB'>C Grade State Billiards (Wed)</option>");
echo("</select></b>");
?>
        
        </td>
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
    echo("<input type='hidden' name='DayPlayed' />");
    echo("<input type='hidden' name='ButtonName' />");
    if($num_rows > 0)
    {
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>");
        $modified_colspan = 3;
        for ($j = 0; $j < $RoundsperPage; $j++) 
        { // number of rows per season
            $x = $NoOfRowsPerRound * $j;
            echo ("<tr>");
            for ($i = 0; $i < $NoOfRowsPerRound; $i++) { //no of rounds per row

                if((($i + 1) + $x) == $final_start)
                {
                    $final_title = "Semi Final";
                    $NoOfFixtures = 2;
                }
                elseif(($i + 1 + $x + 1) > $final_start)
                {
                    $final_title = "Grand Final";
                    $NoOfFixtures = 1;
                }
                else
                {
                    $final_title = "ROUND " . (($i + 1) + $x);
                }

                echo ("<td colspan=" . $modified_colspan . " align='center'>" . $final_title . "</td>");
                $round = (($i + 1) + $x);
            }
            echo ("</tr>");
            echo ("<tr>");
            for ($i = 0; $i < $NoOfRowsPerRound; $i++) 
            { //no of rounds per row (date entry)
                echo ("<td colspan=" . $modified_colspan . " align='center'><input type='text' id='round" . (($i + 1) + $x) . "_date'></td>");
            }
            echo ("</tr>");
            //echo($NoOfFixtures . "<br>");
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
                    echo("<option value='TBA'>TBA</option>");
                    echo("</select></td>");
                    // end dropbox fill
                }
                echo ("</tr>");
            }
            echo("<input type='hidden' name='Grade' value='" . $grade . "' />");
            echo("<input type='hidden' name='Type' value='" . $type . "' />");
            echo("<input type='hidden' name='TeamGrade' value = '" . $team_grade . "' />");
            echo("<input type='hidden' name='DayPlayed' value = '" . $dayplayed . "' />");
        }
        echo("</table>");
        echo("</form>");
        echo("<tr>");
        echo("<td class='text-center' colspan='3'><a class='btn btn-primary btn-xs' onclick=SaveFixtures('" . $team_grade . "') >Save Fixtures for " . $team_grade . "</a></td>");
        echo("</tr>");
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
