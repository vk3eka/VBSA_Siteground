<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('../vbsa_online_scores/php_functions.php'); 

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "scores";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;    
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcommon.js"></script>
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

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
    document.fixture.ButtonName.value = 'SaveFixtures';
    document.fixture.Type.value = type;
    document.fixture.Grade.value = grade;
    document.fixture.TeamGrade.value = team_grade;
    document.fixture.ScoreData.value = scoredata;
    document.fixture.DayPlayed.value = dayplayed;
    document.fixture.action = "edit_fixtures.php";
    document.fixture.submit();
}  

function PopulateCalendar() 
{
    var myCalendar;
    var no_of_rounds = document.getElementsByName('Rounds')[0].value;
    for(i = 0; i < no_of_rounds; i++)
    {
        myCalendar = new dhtmlXCalendarObject("round" + i + "_date");
        myCalendar.setDateFormat("%Y-%m-%d");
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
    $sql_delete = "Delete FROM tbl_fixtures where year = " . $current_year . " and season = '" . $season . "' and team_grade = '". $team_grade . "'";
    $result_delete = mysql_query($sql_delete, $connvbsa) or die(mysql_error());

    $scoredata = json_decode(stripslashes($_POST['ScoreData']), true);
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
            $scoresheet[2] . "', '" . 
            $type . "', '" . 
            $grade . "', " . 
            $scoresheet[3] . ", '" . 
            $scoresheet[0]. "', '" . 
            $scoresheet[1] . "', " . 
            $current_year . ", '" . 
            $season.  "', '" . 
            $team_grade . "', '" . 
            $dayplayed . "')";
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
        }
        elseif($k == 1)
        {
            $sql_update_1 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 1 " . $sql_update_1 . "<br>");
            $update = mysql_query($sql_update_1, $connvbsa) or die(mysql_error());
        }
        elseif($k == 2)
        {
            $sql_update_2 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 2 " . $sql_update_2 . "<br>");
            $update = mysql_query($sql_update_2, $connvbsa) or die(mysql_error());
        }
        elseif($k == 3)
        {
            $sql_update_3 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 3 " . $sql_update_3 . "<br>");
            $update = mysql_query($sql_update_3, $connvbsa) or die(mysql_error());
        }
        elseif($k == 4)
        {
            $sql_update_4 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 4 " . $sql_update_4 . "<br>");
            $update = mysql_query($sql_update_4, $connvbsa) or die(mysql_error());
        }
        elseif($k == 5)
        {
            $sql_update_5 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 5 " . $sql_update_5 . "<br>");
            $update = mysql_query($sql_update_5, $connvbsa) or die(mysql_error());
        }
        elseif($k == 6)
        {
            $sql_update_6 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Fixture 6 " . $sql_update_6 . "<br>");
            $update = mysql_query($sql_update_6, $connvbsa) or die(mysql_error());
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

// for testing
$type = 'Snooker';
$grade = 'BVS1';
$current_year = 2024;
$current_season = 'S1';
/*
echo("Type " . $type . "<br>");
echo("Grade " . $grade . "<br>");
echo("Year " . $current_year . "<br>");
echo("Season " . $current_season . "<br>");
*/
// get from grade settings table
$sql_grades = "Select * From tbl_team_grade Where grade = '" . $grade . "' and season = '" . $current_season . "' and fix_cal_year = " . $current_year;
$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
$build_grades = $result_grades->fetch_assoc();

$NoOfFixtures = $build_grades['no_of_matches'];
$NoOfRounds = $build_grades['no_of_rounds'];
$no_of_games = $build_grades['games_round'];

$modified_colspan = 4;
$NoOfRowsPerRound = 1;
$RoundsperPage = ceil($NoOfRounds/$NoOfRowsPerRound);

$final_start = ($NoOfRounds-1); // start of finals is last but one rounds

$sql = "Select * From tbl_fixtures Where team_grade = '" . $grade . "' AND year = " . $current_year . " AND season = '" . $current_season . "' Order By round";
$result_fixture = mysql_query($sql, $connvbsa) or die(mysql_error());
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
      echo("document.getElementById('round" . ($i+1) . "_date').value = '" . $build_fixture_data['date']) . "';";
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
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<center>
<form name='fixture_select' method='post' action='edit_fixtures.php'>
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
        <td align='center' valign='top' colspan='3'><span class="red_bold"><h2>Select fixture to edit</h2></span></td>
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
$sql = "Select distinct team_grade, grade, type, dayplayed FROM tbl_fixtures where season = '". $current_season . "' and year = '". $current_year . "' order by team_grade";
//echo($sql . "<br>");
$result_fixture = mysql_query($sql, $connvbsa) or die(mysql_error());
while($build_fixture = $result_fixture->fetch_assoc()) 
{
    echo("<option value='" . $build_fixture['team_grade'] . "'>" . $build_fixture['team_grade'] . " " . $build_fixture['grade'] . " Grade" . " " . $build_fixture['type'] . " (" . $build_fixture['dayplayed'] . ")</option>");
}
echo("</select></b>");
?>
        </td>
    </tr>
    <tr>
        <td colspan='3'><h3 align='center'><?php echo($_POST['TeamGrade']); ?></h3></td>
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
    echo("<input type='hidden' name='Type' id='ftype' value='" . $type . "' />");
    echo("<input type='hidden' name='ButtonName' />");
    if($num_rows > 0)
    {
/*        echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>");
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
                echo ("<td colspan=" . $modified_colspan . " align='center'><input type='text' id='round" . (($i + 1) + $x) . "_date' size='10px'></td>");
            }
            echo ("</tr>");
            for ($l = 0; $l < $NoOfFixtures; $l++) 
            { // no of fixtures per row
                echo ("<tr>");
                for ($k = 0; $k < $NoOfRowsPerRound; $k++) 
                { // no of rounds per row
                    echo ("<td align='center'><select id='round" . (($k + $x) + 1) . "_fix" . ($l + 1) . "_home'>");
                    // start dropbox fill
                    $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $current_year . " and team_grade = '" . $team_grade . "'";
                    $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());
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
                    $sql_away_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $current_year . " and team_grade = '" . $team_grade . "'";
                    $result_away_team = mysql_query($sql_away_team, $connvbsa) or die(mysql_error());
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

        echo("<td align='center' colspan='3'><button type='button' btn-xs' style='width:200px' onclick='SaveFixtures()'; >Save Fixtures for " . $team_grade . "</button></td>");
        echo("</tr>");
        echo("</table>");
        //echo("</form>");
*/


        // get data for dataset 
        $sql = "Select team_name FROM Team_entries WHERE team_season ='$current_season' AND team_cal_year = '$current_year' AND team_grade = '$team_grade' AND day_played = '$dayplayed' ORDER BY team_name";
        //echo($sql . "<br>");
        $result_teams = mysql_query($sql, $connvbsa) or die(mysql_error());
        $no_of_teams = $result_teams->num_rows;
        $no_of_fixtures = ($no_of_teams/2);
        $no_of_rounds = (($no_of_teams*2)-2);
        $form_no = 1;
        //echo("<table style='background-color: grey; display: none' class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
        echo("<tbody class='row_position_" . ($form_no+10) . "'>");
        //echo("<tr><td colspan=3 align='center'>(Database)</td></tr>");
        for($r = 0; $r < $no_of_rounds; $r++)
        {
            // get date
            $sql_dates = 'Select date from tbl_fixtures where year = ' . $current_year . ' and dayplayed = "' . $dayplayed . '" and season = "' . $current_season . '" and team_grade = "' . $team_grade . '" and round = ' . ($r+1);
            //echo($sql_dates . "<br>");
            $result_dates = mysql_query($sql_dates, $connvbsa) or die(mysql_error());
            $build_dates = $result_dates->fetch_assoc();
            $date = date_create($build_dates['date']);
            $fixture_date = date_format($date, 'Y-m-d');

            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>Round " . ($r+1)  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . $r . "'  value='" . $fixture_date . "' style='width:100px'></td></tr>");

            $sql_fixtures = 'Select * from tbl_fixtures where year = ' . $current_year . ' and dayplayed = "' . $dayplayed . '" and season = "' . $current_season . '" and team_grade = "' . $team_grade . '" and round = ' . ($r+1);
            //echo($sql_fixtures . "<br>");
            //echo($no_of_fixtures . "<br>");
            $result_fixtures = mysql_query($sql_fixtures, $connvbsa) or die(mysql_error());
            $build_fixtures = $result_fixtures->fetch_assoc();
            //echo($build_fixtures["fix1home"] . "<br>");
            for($y = 0; $y < $no_of_fixtures; $y++)
            {
                //echo($build_fixtures['fix' . ($y+1) . "home"] . "<br>");
                echo("<tr data-index='" . $y . "'>");
                echo("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_home_" . ($r+1) . "_" . ($y+1) . "' value='" . $build_fixtures['fix' . ($y+1) . "home"] . "' style='width:200px'></td>");
                echo("<td align='center'>v</td>");
                echo("<td align='center'><input class='float-child' type='text' id='" . $team_grade . "_away_" . ($r+1) . "_" . ($y+1) . "' value='" . $build_fixtures['fix' . ($y+1) . "away"] . "' style='width:200px'></td>");
                echo("</tr>");
            }
        }
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
<script type="text/javascript">

$(".row_position_1").sortable(
{
    delay: 150,
    stop: function() {
        var selectedData = new Array();
        $(".row_position_1>tr").each(function() {
            selectedData.push($(this).attr("id"));
        });
        console.log("Data 1 " + selectedData);
        //updateOrder(selectedData);
    }
});

$(".row_position_2").sortable(
{
    delay: 150,
    stop: function() {
        var selectedData = new Array();
        $(".row_position_2>tr").each(function() {
            selectedData.push($(this).attr("id"));
        });
        console.log("Data 2 " + selectedData);
        //updateOrder(selectedData);
    }
});

$(".row_position_3").sortable(
{
    delay: 150,
    stop: function() {
        var selectedData = new Array();
        $(".row_position_3>tr").each(function() {
            selectedData.push($(this).attr("id"));
        });
        console.log("Data 3 " + selectedData);
        //updateOrder(selectedData);
    }
});

function updateOrder(aData) 
{
    var dayplayed = '<?= $dayplayed ?>';
    var season = '<?= $season ?>';
    var year = '<?= $year?>';
    var form_no = '<?= $form_no ?>';
    //alert(aData);
    $.ajax({
        url: '../vbsa_online_scores/save_sort_index.php?allData=' + aData + '&season=' + season + '&year=' + year + '&formno=' + form_no,
        type: 'POST',
        success: function(response) {
            alert("Your change successfully saved");
            //window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
        }
    });
}

var row;

function start(){  
  row = event.target; 
}

function dragover(){
  var e = event;
  e.preventDefault(); 
  let children = Array.from(e.target.parentNode.parentNode.children);
  if(children.indexOf(e.target.parentNode) > children.indexOf(row))
  {
    e.target.parentNode.after(row);
  }
  else
  {
    e.target.parentNode.before(row);
  }
}

</script> <!-- dragand drop tables -->

<script>

$(document).ready(function()
{
    const ELS = (sel, par) => (par || document).querySelectorAll(sel);

    // TASK:
    const ELS_child = ELS(".float-child");
    let EL_drag; // Used to remember the dragged element

    const addEvents = (EL_ev) => {
      EL_ev.setAttribute("draggable", "true");
      EL_ev.addEventListener("dragstart", onstart);
      EL_ev.addEventListener("dragover", (ev) => ev.preventDefault());
      EL_ev.addEventListener("drop", ondrop);
    };

    const onstart = (ev) => EL_drag = ev.currentTarget;

    const ondrop = (ev) => {
        if (!EL_drag) return;

        ev.preventDefault();

        const EL_targ = ev.currentTarget;
        const EL_targClone = EL_targ.cloneNode(true);
        const EL_dragClone = EL_drag.cloneNode(true);

        EL_targ.replaceWith(EL_dragClone);
        EL_drag.replaceWith(EL_targClone);

        addEvents(EL_targClone); // Reassign events to cloned element
        addEvents(EL_dragClone); // Reassign events to cloned element

        year = <?= $current_year ?>;
        season = '<?= $current_season ?>';
        dayplayed = '<?= $dayplayed ?>';
        team_grade = '<?= $team_grade ?>';
        form_no = '<?= $form_no ?>';

        from_id = EL_drag.id;
        to_id = EL_targ.id;
        from_team = EL_drag.value;
        to_team = EL_targ.value;
        
        $.ajax({
            url:'<?= $url ?>vbsa_online_scores/save_drag_drop_changes.php?year=' + year + '&season=' + season + '&dayplayed=' + dayplayed + '&from_id=' + from_id + '&to_id=' + to_id + '&from_team=' + from_team + '&to_team=' + to_team + '&form_no=' + form_no,

            method: 'POST',
            success:function(response)
            {
              alert(response);
            },
        });

        EL_drag = undefined;
    };

    ELS_child.forEach((EL_child) => addEvents(EL_child));

});
</script> <!-- dragand drop fixtures -->
</center>
</body>
</html>

