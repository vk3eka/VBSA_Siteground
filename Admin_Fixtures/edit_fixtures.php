<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('../vbsa_online_scores/php_functions.php'); 

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

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

//$current_year = $_SESSION['year'];
$current_year = date("Y");

if (isset($_GET['season'])) 
{
    $current_season = $_GET['season'];
    $_SESSION['session_season'] = $current_season;
}
elseif (isset($_POST['season']))
{
    $current_season = $_POST['season'];
    $_SESSION['session_season'] = $current_season;
}

if (isset($_GET['DayPlayed'])) 
{
    $dayplayed = $_GET['DayPlayed'];
    $_SESSION['session_dayplayed'] = $dayplayed;
}
elseif (isset($_POST['DayPlayed']))
{
    $dayplayed = $_POST['DayPlayed'];
    $_SESSION['session_dayplayed'] = $dayplayed;
}

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
            $rounds = 15;
            break;
        case 14:
            $rounds = 13;
            break;
        default:
            $rounds = 18;
    }
    return $rounds;
}

require_once('Models/Fixture.php');

$fixture = new Fixture();
$fixture->LoadFixture($current_year, $_SESSION['session_season'], $_SESSION['session_dayplayed']);
$jsonData = json_encode($fixture);
$data = json_decode($jsonData, true);
//echo($current_year . "<br>");
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

function GeneratePDF(current_year, current_season, team_grade, dayplayed, comp_type)
{
    window.location.href = '../Admin_Fixtures/create_pdf_content.php?Year=' + current_year + '&Season=' + current_season + '&Team_Grade=' + team_grade + '&DayPlayed=' + dayplayed + '&CompType=' + comp_type + '&Table=tbl_fixtures';
}

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
    else
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
    //alert("Here");

    var team_grade = document.getElementsByName('TeamGrade')[0].value;
    var no_of_rounds = document.getElementsByName('Rounds')[0].value;
    var no_of_fixtures = document.getElementsByName('Fixtures')[0].value;
    var dayplayed = document.getElementById("dayplayed").value
    var scoredata = new Array;
    var scoredata_teams = new Array;
    var playing_date;
    var round;
    var type; 
    //alert(no_of_rounds);
    x = 0;
    for(i = 0; i < no_of_rounds; i++)
    {
        playing_date = document.getElementById("round" + i + "_date").value;
        //alert(playing_date);
        round = (i+1);
        for(j = 0; j < no_of_fixtures; j++) 
        {
            if(document.getElementById("round" + round + "_fix" + (j+1) + "_home") != null)
            {
                scoredata_teams[x] = document.getElementById("round" + round + "_fix" + (j+1) + "_home").value + ", " + document.getElementById("round" + round + "_fix" + (j+1) + "_away").value + ", " + playing_date + ", " + round; 
                //alert(scoredata_teams[x]);
                scoredata.push(scoredata_teams[x]);
                x++;
            }
        }
    }
    //alert("Here");
    var scoredata = JSON.stringify(scoredata);  
    //console.log("Score Data " + scoredata);
    var grade = team_grade.substring(0, 1);
    if(team_grade.substring(2, 3) == 'S')
    {
        type = 'Snooker';
    }
    else// if(team_grade.substring(2, 3) == 'B')
    {
        type = 'Billiards';
    }   
    document.fixture.ButtonName.value = 'SaveFixtures';
    document.fixture.Type.value = type;
    document.fixture.Rounds.value = no_of_rounds;
    document.fixture.Grade.value = grade;
    document.fixture.TeamGrade.value = team_grade;
    document.fixture.ScoreData.value = scoredata;
    document.fixture.DayPlayed.value = dayplayed;
    //document.fixture.action = "edit_fixtures.php";
    //FillFixtureButton();
    document.fixture.submit();

}  

function PopulateCalendar() 
{
    //alert("Here");
    var myCalendar;
    var no_of_rounds = document.getElementsByName('Rounds')[0].value;
    //console.log("Rounds " + no_of_rounds);
    for(i = 0; i < no_of_rounds; i++)
    {
        myCalendar = new dhtmlXCalendarObject("round" + (i) + "_date");
        myCalendar.setDateFormat("%Y-%m-%d");
        myCalendar.setSkin('dhx_skyblue');
        myCalendar.hideTime();
        myCalendar.hideWeekNumbers();
        myCalendar.attachEvent("onClick",function(date)
        {
            DateChange(i);
        });
    }
}

function addDays(date) 
{
    result = date.setDate(date.getDate() + 7);
    return result;
}

function displayDate(current_date)
{
    var new_date = new Date(current_date);
    var added_days_date = addDays(new_date);
    var new_date_object = new Date(added_days_date);
    var day = ("0" + new_date_object.getDate()).slice(-2);
    var month = ("0" + (new_date_object.getMonth() + 1)).slice(-2);
    var year = new_date_object.getFullYear();
    var formatted_date = year + "-" + month + "-" + day;
    return formatted_date;
}

function DateChange(r, no_of_rounds)
{
    var non_dates = <?php echo json_encode($data['non_dates']); ?>;
    //console.log("Non Dates " + JSON.stringify(non_dates, null, 2));
    for(i = r; i < (no_of_rounds-1); i++)
    {
        current_date = document.getElementById("round" + i + "_date").value;
        formatted_date = displayDate(current_date);
        //console.log(formatted_date);
        document.getElementById("round" + (i+1) + "_date").value = formatted_date;

        let phArray = non_dates.map(item => item.date);
        if (phArray.includes(formatted_date)) 
        {
            //console.log(formatted_date + " is here");
            document.getElementById("round" + (i+1) + "_date").value = displayDate(formatted_date);
        } 
        else 
        {
            document.getElementById("round" + (i+1) + "_date").value = formatted_date;
            //console.log(formatted_date + " is NOT here");
        }
    }
}

</script>
<?php

if ($_POST['ButtonName'] == "SaveFixtures") 
{
    //echo("Here<br>");
    $type = $_POST['Type'];
    $grade = $_POST['Grade'];
    $team_grade = $_POST['TeamGrade'];
    $dayplayed = $_POST['DayPlayed'];
    $current_year = date("Y");
    $current_season = $_SESSION['session_season'];
    $no_of_fixtures  = $_POST['Fixtures'];
    //echo(date("Y") . "<br>");
    // delete existing fixtures
    $sql_delete = "Delete FROM tbl_fixtures where year = " . $current_year . " and season = '" . $current_season . "' and team_grade = '". $team_grade . "'";
    $result_delete = mysql_query($sql_delete, $connvbsa) or die(mysql_error());
    //echo($sql_delete . "<br>");
    $scoredata = json_decode(stripslashes($_POST['ScoreData']), true);
    $j = 0;
    $k = 0;

    //echo("<pre>");
    //echo(var_dump($scoredata));
    //echo("</pre>");

    //echo(count($scoredata) . "<br>");
    //for ($i = 0; $i < $no_of_rounds; $i++) 
    for ($i = 0; $i < count($scoredata); $i++) 
    {
        $scoresheet = explode(", ", $scoredata[$i]);
        $k = (($j % $no_of_fixtures));
        //echo($scoresheet[3] . ", " . $i . ", " . $k . "<br>");
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
            $current_season .  "', '" . 
            $team_grade . "', '" . 
            $dayplayed . "')";
            //echo($sql_insert . ", Round " . $scoresheet[3] . "<br>");
            $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
        }
        elseif($k == 1)
        {
            $sql_update_1 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $current_season . "'";
            //echo("Fixture 1 " . $sql_update_1 . "<br>");
            $update = mysql_query($sql_update_1, $connvbsa) or die(mysql_error());
        }
        elseif($k == 2)
        {
            $sql_update_2 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $current_season . "'";
            //echo("Fixture 2 " . $sql_update_2 . "<br>");
            $update = mysql_query($sql_update_2, $connvbsa) or die(mysql_error());
        }
        elseif($k == 3)
        {
            $sql_update_3 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $current_season . "'";
            //echo("Fixture 3 " . $sql_update_3 . "<br>");
            $update = mysql_query($sql_update_3, $connvbsa) or die(mysql_error());
        }
        elseif($k == 4)
        {
            $sql_update_4 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $current_season . "'";
            //echo("Fixture 4 " . $sql_update_4 . "<br>");
            $update = mysql_query($sql_update_4, $connvbsa) or die(mysql_error());
        }
        elseif($k == 5)
        {
            $sql_update_5 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $current_season . "'";
            //echo("Fixture 5 " . $sql_update_5 . "<br>");
            $update = mysql_query($sql_update_5, $connvbsa) or die(mysql_error());
        }
        elseif($k == 6)
        {
            $sql_update_6 = "Update tbl_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $current_season . "'";
            //echo("Fixture 6 " . $sql_update_6 . "<br>");
            $update = mysql_query($sql_update_6, $connvbsa) or die(mysql_error());
        }
        $j++;
    }
    echo "<script type=\"text/javascript\">"; 
    echo "alert('Fixtures Updated')"; 
    echo "</script>";    
    $_POST['TeamGrade'] = '';
}

if ((isset($_POST['Grade']) and $_POST['Grade'] <> '') and (isset($_POST['Type']) and $_POST['Type'] <> '')) 
{
    $type = $_POST['Type'];
    $grade = $_POST['Grade'];
    $team_grade = $_POST['TeamGrade'];
    //$current_season = $_SESSION['session_season'];
}
else
{
    $type = "";
    $grade = "";
    $team_grade = " ";
}

$current_year = date('Y');
$current_season = $_SESSION['session_season'];
/*
echo("Type " . $type . "<br>");
echo("Team Grade " . $team_grade . "<br>");
echo("Year " . $current_year . "<br>");
echo("Season " . $current_season . "<br>");
*/

// get from grade settings table
$sql_grades = "Select * From Team_grade Where grade = '" . $team_grade . "' and season = '" . $current_season . "' and fix_cal_year = " . $current_year . " AND current = 'Yes'";
//echo($sql_grades . "<br>");
$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
$build_grades = $result_grades->fetch_assoc();

$NoOfFixtures = $build_grades['no_of_matches'];
$NoOfRounds = ($build_grades['no_of_rounds']); // from Team_grades, includes finals...
$final_players = $build_grades['finals_teams'];
//echo("NoOfFixtures " . $NoOfFixtures . "<br>");
//echo("NoOfRounds " . $NoOfRounds . "<br>");
$no_of_games = $build_grades['games_round'];
$final_start = ($NoOfRounds); // start of finals

$add_rounds_for_finals = ($final_players/2);
//$no_of_rounds = (($NoOfRounds)-$add_rounds_for_finals); // inc finals
$no_of_rounds = $NoOfRounds; // inc finals



$modified_colspan = 4;
$NoOfRowsPerRound = 1;
$RoundsperPage = ceil($NoOfRounds/$NoOfRowsPerRound);

$sql = "Select * From tbl_fixtures Where team_grade = '" . $team_grade . "' AND year = " . $current_year . " AND season = '" . $current_season . "' Order By round";
//echo($sql . "<br>");
$result_fixture = mysql_query($sql, $connvbsa) or die(mysql_error());
$num_rows = $result_fixture->num_rows;
//echo("Rows " . $num_rows . "<br>");
if($num_rows > 0)
{
    echo("<script type='text/javascript'>");
    echo("function FillElementArray() {");
    $i = 0;
    while ($build_fixture_data = $result_fixture->fetch_assoc()) 
    {
      $team_grade = $build_fixture_data['team_grade'];
      $dayplayed = $build_fixture_data['dayplayed'];
      echo("document.getElementById('round" . ($i) . "_date').value = '" . $build_fixture_data['date']) . "';";
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
    echo("  FillElementArray();");
    echo("  PopulateCalendar();");
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
$sql = "Select grade_name, grade, type, dayplayed FROM Team_grade where fix_cal_year = $current_year and season = '$current_season' and current = 'Yes' Order By dayplayed, grade_name";
$result_fixture = mysql_query($sql, $connvbsa) or die(mysql_error());
while($build_fixture = $result_fixture->fetch_assoc()) 
{
    if($build_fixture['dayplayed'] == 'Mon')
    {
        echo("<option value='" . $build_fixture['grade'] . "'>" . $build_fixture['grade_name'] . " (" . $build_fixture['dayplayed'] . ")</option>");  
    }
}
        echo("<option value=''></option>");
$result_fixture = mysql_query($sql, $connvbsa) or die(mysql_error());
while($build_fixture = $result_fixture->fetch_assoc()) 
{
    if($build_fixture['dayplayed'] == 'Wed')
    {
        echo("<option value='" . $build_fixture['grade'] . "'>" . $build_fixture['grade_name'] . " (" . $build_fixture['dayplayed'] . ")</option>");
    }
}
echo("</select>");
?>
        </td>
    </tr>
    <tr>
        <td colspan='3'><h3 align='center'><?php echo($team_grade); ?></h3></td>
    </tr>
</table>
</form>
<?php
/*
function addDays($date, $days, $round) 
{
    $result = date('Y-m-d', strtotime($date . ' + ' . ($days*$round) . ' days'));
    return $result;
}
*/
if($_POST['Select'] == 'true')
{
    //echo("Here<br>");
    echo("<form name='fixture' method='post'>");
    echo("<input type='hidden' name='HomeTeam' />");
    echo("<input type='hidden' name='AwayTeam' />");
    echo("<input type='hidden' name='RoundNo' />");
    echo("<input type='hidden' name='Select' />");
    echo("<input type='hidden' name='FixtureDate' />");
    echo("<input type='hidden' name='RoundSelected' value=''/>");
    echo("<input type='hidden' name='TeamScoring' />");
    echo("<input type='hidden' name='Year' id='year' value=" . $current_year . " />");
    echo("<input type='hidden' name='Season' id='season' value='" . $current_season . "' />");
    echo("<input type='hidden' name='FinalData' />");
    echo("<input type='hidden' name='ScoreData' />");
    echo("<input type='hidden' name='DayPlayed' id='dayplayed' value='" . $dayplayed . "' />");
    echo("<input type='hidden' name='Type' id='type' value='" . $type . "' />");
    echo("<input type='hidden' name='ButtonName' />");
    //echo($num_rows . "<br>");

    //$num_rows = 0;
    if($num_rows > 0)
    {
        //echo($num_rows . "<br>");
        // get data for dataset 
        $sql = "Select team_name FROM Team_entries WHERE team_season ='$current_season' AND team_cal_year = $current_year AND team_grade = '$team_grade' AND day_played = '$dayplayed' AND include_draw = 'Yes'";
        //echo($sql . "<br>");
        $result_teams = mysql_query($sql, $connvbsa) or die(mysql_error());
        $no_of_teams = $result_teams->num_rows;
        //$no_of_fixtures = ($no_of_teams/2);
        //$add_rounds_for_finals = ($final_players/2);
        //$no_of_rounds = (GetRounds($no_of_teams)+$add_rounds_for_finals); // inc finals
        //echo("Rounds " . (GetRounds($no_of_teams)) . "<br>");
        //echo("Teams " . ($no_of_teams) . "<br>");

        // get last date before finals
        $sql_last = 'Select date from tbl_fixtures where year = ' . $current_year . ' and dayplayed = "' . $dayplayed . '" and season = "' . $current_season . '" and team_grade = "' . $team_grade . '" and round = ' . $NoOfRounds; // inc finals
        //echo($sql_last . "<br>");
        $result_last = mysql_query($sql_last, $connvbsa) or die(mysql_error());
        $build_last = $result_last->fetch_assoc();
        $last_date = date_create($build_last['date']);
        $last_date = date_format($last_date, 'Y-m-d');
        //echo("Last Date " . $last_date . "<br>");

        echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='1000px'>");

        //echo("Final Players " . $final_players . "<br>");
        //echo("Rounds " . $no_of_rounds . "<br>");

        echo("<tbody>");
        for($r = 0; $r < ($no_of_rounds); $r++)
        {
            // get fixture date
            $sql_dates = 'Select date from tbl_fixtures where year = ' . $current_year . ' and dayplayed = "' . $dayplayed . '" and season = "' . $current_season . '" and team_grade = "' . $team_grade . '" and round = ' . ($r+1);
            //echo($sql_dates . "<br>");
            $result_dates = mysql_query($sql_dates, $connvbsa) or die(mysql_error());
            $build_dates = $result_dates->fetch_assoc();
            $date = date_create($build_dates['date']);
            $fixture_date = date_format($date, 'Y-m-d');
            
            $no_of_fixtures = ($no_of_teams/2);
            if($final_players == 4)
            {
                if($r == $no_of_rounds-2)
                {
                    $final_title = "Semi Final";
                }
                elseif($r == $no_of_rounds-1)
                {
                    $final_title = "Grand Final";
                }
                else
                {
                    $final_title = "Round " . ($r+1);
                }
            }
            elseif($final_players == 6)
            {
                if($r == $no_of_rounds-2)
                {
                    $final_title = "Semi Final";
                }
                elseif($r == $no_of_rounds-3)
                {
                    $final_title = "Elimination Final";
                }
                elseif($r == $no_of_rounds-1)
                {
                    $final_title = "Grand Final";
                }
                else
                {
                    $final_title = "Round " . ($r+1);
                }
            }

            //echo("<pre>");
            //echo(var_dump($public_holiday));
            //echo("</pre>");


            echo("<tr><td>&nbsp;</td></tr>");
            echo("<td colspan=3 align='center'><b>" . $final_title  . "</b></td></tr>");
            echo("<tr><td align='right'><b>Date</b></td>");
            echo("<td colspan=2 class='text-left'><input type='text' id='round" . $r . "_date'  value='" . $fixture_date . "' style='width:100px'  onchange='DateChange(" . $r . ", " . $no_of_rounds . ")'></td></tr>");

            $sql_fixtures = 'Select * from tbl_fixtures where year = ' . $current_year . ' and dayplayed = "' . $dayplayed . '" and season = "' . $current_season . '" and team_grade = "' . $team_grade . '" and round = ' . ($r+1);
            $result_fixtures = mysql_query($sql_fixtures, $connvbsa) or die(mysql_error());
            $build_fixtures = $result_fixtures->fetch_assoc();
            for($y = 0; $y < $no_of_fixtures; $y++)
            {
                echo("<tr>");
                echo ("<td align='center'><select id='round" . ($r+1) . "_fix" . ($y+1) . "_home'>");
                // start dropbox fill
                echo("<option value='TBA'>TBA</option>");
                $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $current_year . " and team_grade = '" . $team_grade . "'";
                $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());

                if($build_fixtures["fix" . ($y+1) . "home"] == '')
                {
                    echo("<option value='TBA'>TBA</option>");
                }
                else
                {
                    echo("<option value='" . $build_fixtures["fix" . ($y+1) . "home"] . "'>" . $build_fixtures["fix" . ($y+1) . "home"] . "</option>");
                }
                

                while($build_home_team = $result_home_team->fetch_assoc()) 
                {
                  echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
                }
                echo("</select></td>");
                // end dropbox fill
                echo("<td align='center'>v</td>");
                echo ("<td align='center'><select id='round" . ($r+1) . "_fix" . ($y+1) . "_away'>");
                // start dropbox fill
                echo("<option value='TBA'>TBA</option>");

                $sql_away_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $current_year . " and team_grade = '" . $team_grade . "'";
                $result_away_team = mysql_query($sql_away_team, $connvbsa) or die(mysql_error());
                //echo("<option value='round" . ($r+1) . "_fix" . ($y+1) . "_home'>" . $build_fixtures["fix" . ($y+1) . "away"] . "</option>");
                if($build_fixtures["fix" . ($y+1) . "away"] == '')
                {
                    echo("<option value='TBA'>TBA</option>");
                }
                else
                {
                    echo("<option value='" . $build_fixtures["fix" . ($y+1) . "away"] . "'>" . $build_fixtures["fix" . ($y+1) . "away"] . "</option>");
                }
                while($build_away_team = $result_away_team->fetch_assoc()) 
                {
                    echo("<option value='" . $build_away_team['team_name'] . "'>" . $build_away_team['team_name'] . "</option>");
                }
                echo("</select></td>");
                // end dropbox fill
                echo("</tr>");
            }
        }
        echo("</tr>");
        echo("<tr>");
        echo("<td align='center' colspan='3'>&nbsp;</td>");
        echo("</tr>");
        echo("<input type='hidden' name='Grade' value='" . $grade . "' />");
        echo("<input type='hidden' name='TeamGrade' value='" . $team_grade . "' />");
        echo("<input type='hidden' name='Type' value='" . $type . "' />");
        echo("<input type='hidden' name='Fixtures' value = '" . $NoOfFixtures . "' />");
        echo("<input type='hidden' name='Rounds' value = '" . $NoOfRounds . "' />");
        echo("<tr>");
        echo("<td align='center' colspan='3'><button type='button' class='btn btn-primary'  onclick='SaveFixtures()' style='width: 250px;'>Save Fixtures for " . $team_grade . "</button></td>");
        echo("</tr>");
        echo("<tr>");
        echo("<td align='center' colspan='3'>&nbsp;</td>");
        echo("</tr>");
        echo("<tr>");
        $args = $current_year . ", '" . $current_season . "', '" . $team_grade . "', '" . $dayplayed . "', '" . $type . "'";
        //echo($args . "<br>");
        echo("<td align='center' colspan='3' style='height: 20px'><button type='button' class='btn btn-primary' onclick=\"GeneratePDF(" . $args . ")\" style='width: 250px;'>Generate Fixtures PDF for " . $team_grade . "</button></td>");
        echo("</tr>");
        echo("<tr>");
        echo("<td align='center' colspan='3'>&nbsp;</td>");
        echo("</tr>");
        //echo("<tr>");
        //echo("<td align='center' colspan='3'><button type='button' id='upload' class='btn btn-primary'  onclick='UploadPDF()'>Upload PDF via the Grades page</button></td>");
        //echo("</tr>");
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
</body>
</html>

