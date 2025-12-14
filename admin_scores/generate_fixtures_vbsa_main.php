<?php 
require_once('../Connections/connvbsa.php'); 
include('../vbsa_online_scores/server_name.php');
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

//$season = "-1";
if (isset($_GET['season'])) 
{
    $season = $_GET['season'];
}
elseif (isset($_POST['season']))
{
    $season = $_POST['season'];
}
$year = date('Y');

if (isset($_GET['DayPlayed'])) 
{
    $day_played = $_GET['DayPlayed'];
}
elseif (isset($_POST['DayPlayed']))
{
    $day_played = $_POST['DayPlayed'];
}

/*
if (isset($_GET['FormNo'])) 
{
    $form_no = $_GET['FormNo'];
}
elseif (isset($_POST['FormNo']))
{
    $form_no = $_POST['FormNo'];
}

if(!isset($form_no))
{
    $form_no = 1;
}
echo($form_no . "<br>");
//echo($season . "<br>");
//echo($year . "<br>");
*/

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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<style>
    .ui-sortable-helper {
  display: table;
}
</style>
<script>
$(document).ready(function()
{
    $('.savebutton').click(function()
    {
        var form_no = $(this).data("id"); 
        var team_grade = $('#team_grade_' + form_no).html();
        var no_of_teams = $('#no_of_teams_' + form_no).html();
        var no_of_fixtures = (no_of_teams/2);
        var no_of_rounds = ((no_of_teams*2)-2);
        var dayplayed = $("#dayplayed_" + form_no).html();
        var comptype = $("#comptype_" + form_no).html();
        var grade = team_grade.substring(0,1);
        var scoredata = new Array;
        var scoredata_teams = new Array;
        var sortdata = new Array;
        var sortdata_index = new Array;
        //var sortdata_fix = new Array;
        var season = '<?= $season ?>';
        var year = '<?= $year?>';
        var round;
        for(x = 0; x < no_of_teams; x++)
        {
            sortdata_index[x] = $("#club_" + form_no + "_" + (x+1)).html() + ", " + $("#sort_" + form_no + "_" + (x+1) + "_id").html(); 
            sortdata.push(sortdata_index[x]);
        }
        sortdata = JSON.stringify(sortdata);
        for(i = 0; i < no_of_rounds; i++)
        {
            playing_date = $("#" + form_no + "_date_" + i).val();
            round = (i+1);
            for(j = 0; j < (no_of_teams/2); j++) 
            {
                scoredata_teams[i+j] = $('#' + team_grade + "_home_" + (i+1) + "_" + (j+1)).val() + ", " + $('#' + team_grade + "_away_" + (i+1) + "_" + (j+1)).val() + ", " + playing_date + ", " + round; 
                scoredata.push(scoredata_teams[i+j]);
            }
        }
        scoredata = JSON.stringify(scoredata);
        console.log(sortdata);
        $.ajax({
            url:"../vbsa_online_scores/save_fixtures.php?Type=" + comptype + "&Grade=" + grade + "&TeamGrade=" + team_grade + "&FormNo=" + form_no + "&DayPlayed=" + dayplayed + "&Year=" + year + "&Season=" + season + "&SortData=" + sortdata+ "&ScoreData=" + scoredata + "&Fixtures=" + no_of_fixtures,
            method: 'GET',
            success : function(response)
            {
              alert(response);
            },
            error: function (request, error) 
            {
              alert("No data saved!");
            }
        });
    });
});
</script>
<?php 
if(isset($_POST['Sortby']) && (($_POST['Sortby']) != ''))
{
    $varSort = $_POST['Sortby'];
    switch ($varSort) {
        case 'team_id_dec':
            $sortby = " Order By team_id DESC";
            break;
        case 'team_name_dec':
            $sortby = " Order By team_name DESC";
            break;
        case 'team_id_asc':
            $sortby = " Order By team_id ASC";
            break;
        case 'team_name_asc':
            $sortby = " Order By team_name ASC";
            break;
        case 'rand':
            $sortby = " Order By RAND()";
            break;
        case 'fix_sort':
            $sortby = " Order By fix_sort ASC";
            break;
        default:
            $sortby = " Order By fix_sort ASC";
            break;
    }
}
else
{
    $sortby = " Order By fix_sort ASC";
}

function CreateDateArray($form_no, $teams, $team_grade, $year, $season) 
{
    for($y = 0; $y < (($teams*2)-2); $y++)
    {
        global $connvbsa;
        $sql_playing_dates = 'Select * from tbl_create_fixtures where year = ' . $year . ' and team_grade = "' . $team_grade . '" and season = "' . $season . '" and round = ' . ($y+1);
        //echo($sql_playing_dates . "<br>");
        $result_playing_dates = mysql_query($sql_playing_dates, $connvbsa) or die(mysql_error());
        echo("<script type='text/javascript'>");
        while($row = $result_playing_dates->fetch_assoc()) 
        {
            $row_date = substr($row['date'], 0, 10);
            echo("document.getElementById('" . $form_no . "_date_" . ($y) . "').value = '" . $row_date . "';");
        }
        echo("</script>");
    }
}

function NoCountByes($team1, $team2)
{
    $bye_count = 0;
    if($team1 == 'Bye')
    {
        $bye_count++;
    }
    if($team2 == 'Bye')
    {
        $bye_count++;
    }
    return $bye_count;
}

?>
<script type='text/javascript'>

function doOnLoad() {
  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate_1");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");

  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate_2");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");

  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate_3");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");
}

function FirstLoad() {
    document.getElementById("page1").style.display = "block";
    document.getElementById("page2").style.display = "none";
    document.getElementById("page3").style.display = "none";
    document.getElementById("page10").style.display = "none"; 
}

function Viewtab(sel){
    switch (sel) {
    case 1:
        document.getElementById("page1").style.display = "block";
        document.getElementById("page2").style.display = "none";
        document.getElementById("page3").style.display = "none";
        document.getElementById("page10").style.display = "none"; 
        break;
    case 2:
        document.getElementById("page1").style.display = "none";
        document.getElementById("page2").style.display = "block";
        document.getElementById("page3").style.display = "none";
        document.getElementById("page10").style.display = "none"; 
        break;
    case 3:
        document.getElementById("page1").style.display = "none";
        document.getElementById("page2").style.display = "none";
        document.getElementById("page3").style.display = "block";
        document.getElementById("page10").style.display = "none"; 
        break;
    case 10:
        document.getElementById("page1").style.display = "none";
        document.getElementById("page2").style.display = "none";
        document.getElementById("page3").style.display = "none";
        document.getElementById("page10").style.display = "block"; 
        break;
    }
}

window.onload = function() 
{
    doOnLoad();
    FirstLoad();
    GetAnalyisData();
}

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

function SetDate(form_no, teams)
{
    document.getElementById(form_no + '_date_0').value = document.getElementById('startdate_' + form_no).value;
    for(i = 0; i < teams; i++)
    {
        document.getElementById(form_no + '_date_' + (i+1)).value = addDays(document.getElementById(form_no + '_date_' + i).value, 7).toISOString().substring(0, 10);
    }
}

function SaveFixtures(form_no) 
{
    var team_grade = document.getElementById('team_grade_' + form_no).innerHTML;
    var no_of_teams = document.getElementById('no_of_teams_' + form_no).innerHTML;
    var no_of_fixtures = (no_of_teams/2);
    var no_of_rounds = ((no_of_teams*2)-2);
    var dayplayed = document.getElementById("dayplayed_" + form_no).innerHTML;
    var comptype = document.getElementById("comptype_" + form_no).innerHTML;
    var grade = team_grade.substring(0,1);
    var scoredata = new Array;
    var scoredata_teams = new Array;
    var round;
    for(i = 0; i < no_of_rounds; i++)
    {
        playing_date = document.getElementById(form_no + "_date_" + i).value;
        round = (i+1);
        for(j = 0; j < (no_of_teams/2); j++) 
        {
            scoredata_teams[i+j] = document.getElementById(team_grade + "_home_" + (i+1) + "_" + (j+1)).innerHTML + ", " + document.getElementById(team_grade + "_away_" + (i+1) + "_" + (j+1)).innerHTML + ", " + playing_date + ", " + round; 
            scoredata.push(scoredata_teams[i+j]);
        }
    }
    var scoredata = JSON.stringify(scoredata); 
    document.fixture.ButtonName.value = 'SaveFixtures'; 
    document.fixture.Fixtures.value = no_of_fixtures;
    document.fixture.Type.value = comptype;
    document.fixture.FormNo.value = form_no;
    document.fixture.Grade.value = grade;
    document.fixture.TeamGrade.value = team_grade;
    document.fixture.ScoreData.value = scoredata;
    document.fixture.DayPlayed.value = dayplayed;
    document.fixture.submit();
}  

function GetAnalyisData()
{
    // need to pass team grade programatically
    team_grade = 'CPS';
    no_of_cps_rounds = 18;
    no_of_cps_teams = 5;
    form_number = 3;
    for(i = 0; i < (no_of_cps_rounds); i++)
    {
        document.getElementById('date_' + i).innerHTML = document.getElementById(form_number + "_date_" + i).value;
        document.getElementById('table_date_' + i).innerHTML = document.getElementById(form_number + "_date_" + i).value;
        round = (i+1);
        for(j = 0; j < (no_of_cps_teams); j++)
        {
            document.getElementById(team_grade + '_round_' + (j+1)).innerHTML = team_grade;
            document.getElementById(team_grade + '_round_' + round + '_pos_' + (j+1)).innerHTML = document.getElementById(team_grade + "_home_" + round + "_" + (j+1)).value;
        }
    }

    team_grade = 'APS';
    no_of_aps_rounds = 14;
    no_of_aps_teams = 4;
    form_number = 1;
    for(i = 0; i < no_of_aps_rounds; i++)
    {
        document.getElementById('date_' + i).innerHTML = document.getElementById(form_number + "_date_" + i).value;
        round = (i+1);
        for(j = 0; j < (no_of_aps_teams); j++)  
        {
            document.getElementById(team_grade + '_round_' + (j+1)).innerHTML = team_grade;
            document.getElementById(team_grade + '_round_' + round + '_pos_' + (j+1)).innerHTML = document.getElementById(team_grade + "_home_" + round + "_" + (j+1)).value;
        }
    }

    team_grade = 'BPS';
    no_of_bps_rounds = 14;
    no_of_bps_teams = 4;
    form_number = 2;
    for(i = 0; i < no_of_bps_rounds; i++)
    {
        //console.log("Date " + document.getElementById(form_number + "_date_" + i).value);
        document.getElementById('date_' + i).innerHTML = document.getElementById(form_number + "_date_" + i).value;
        round = (i+1);
        for(j = 0; j < (no_of_bps_teams); j++) 
        {
            document.getElementById(team_grade + '_round_' + (j+1)).innerHTML = team_grade;
            document.getElementById(team_grade + '_round_' + round + '_pos_' + (j+1)).innerHTML = document.getElementById(team_grade + "_home_" + round + "_" + (j+1)).value;
        }
    }
}

function GetSort(sel, form_no, season, year, dayplayed) 
{
    var sort_order = sel.options[sel.selectedIndex].value;
    document.fixture.Sortby.value = sort_order;
    document.fixture.FormNo.value = form_no;
    document.fixture.year.value = year;
    document.fixture.season.value = season;
    document.fixture.DayPlayed.value = dayplayed;
    document.fixture.submit();
}
</script>
<?php

function GetHomeGames($round, $year, $season)
{
    global $connvbsa;
    $sql_club = "Select fix1home, fix2home, fix3home, fix4home, fix5home, fix6home, team_grade FROM tbl_create_fixtures where team_grade = 'APS' and year = " . $year . " and season = '" . $season . "' and round = '" . $round . "';";
    $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $build_club = $result_club->fetch_assoc();
    $fixArray_1 = [];
    $fixArray_1 = $build_club['fix1home'] . ', ' . $build_club['fix2home'] . ', ' . $build_club['fix3home'] . ', ' . $build_club['fix4home'] . ', ' . $build_club['fix5home'] . ', ' . $build_club['fix6home'];

    $sql_club = "Select fix1home, fix2home, fix3home, fix4home, fix5home, fix6home, team_grade FROM tbl_create_fixtures where team_grade = 'BPS' and year = " . $year . " and season = '" . $season . "' and round = '" . $round . "';";
    $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $build_club = $result_club->fetch_assoc();
    $fixArray_2 = [];
    $fixArray_2 = $build_club['fix1home'] . ', ' . $build_club['fix2home'] . ', ' . $build_club['fix3home'] . ', ' . $build_club['fix4home'] . ', ' . $build_club['fix5home'] . ', ' . $build_club['fix6home'];

    $sql_club = "Select fix1home, fix2home, fix3home, fix4home, fix5home, fix6home, team_grade FROM tbl_create_fixtures where team_grade = 'CPS' and year = " . $year . " and season = '" . $season . "' and round = '" . $round . "';";
    $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $build_club = $result_club->fetch_assoc();
    $fixArray_3 = [];
    $fixArray_3 = $build_club['fix1home'] . ', ' . $build_club['fix2home'] . ', ' . $build_club['fix3home'] . ', ' . $build_club['fix4home'] . ', ' . $build_club['fix5home'] . ', ' . $build_club['fix6home'];
    $fixArray = $fixArray_1 . ", " . $fixArray_2 . ", " . $fixArray_3;
    return $fixArray;
}


function GetAllGames($round, $year, $season)
{
    global $connvbsa;
    $sql_club = "Select team_grade, fix1home, fix1away, fix2home, fix2away, fix3home, fix3away, fix4home, fix4away, fix5home, fix5away, fix6home, fix6away FROM tbl_create_fixtures where team_grade = 'APS' and year = " . $year . " and season = '" . $season . "' and round = '" . $round . "';";
    $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $build_club = $result_club->fetch_assoc();
    $fixArray_1 = [];
    $fixArray_1 = $build_club['fix1home'] . ', ' . $build_club['fix1away'] . ', ' . $build_club['fix2home'] . ', ' . $build_club['fix2away'] . ', ' . $build_club['fix3home'] . ', ' . $build_club['fix3away'] . ", " . $build_club['fix4home'] . ', ' . $build_club['fix4away'] . ', ' . $build_club['fix5home'] . ', ' . $build_club['fix5away'] . ', ' . $build_club['fix6home'] . ', ' . $build_club['fix6away'];

    $sql_club = "Select team_grade, fix1home, fix1away, fix2home, fix2away, fix3home, fix3away, fix4home, fix4away, fix5home, fix5away, fix6home, fix6away FROM tbl_create_fixtures where team_grade = 'BPS' and year = " . $year . " and season = '" . $season . "' and round = '" . $round . "';";
    $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $build_club = $result_club->fetch_assoc();
    $fixArray_2 = [];
    $fixArray_2 = $build_club['fix1home'] . ', ' . $build_club['fix1away'] . ', ' . $build_club['fix2home'] . ', ' . $build_club['fix2away'] . ', ' . $build_club['fix3home'] . ', ' . $build_club['fix3away'] . ", " . $build_club['fix4home'] . ', ' . $build_club['fix4away'] . ', ' . $build_club['fix5home'] . ', ' . $build_club['fix5away'] . ', ' . $build_club['fix6home'] . ', ' . $build_club['fix6away'];

    $sql_club = "Select team_grade, fix1home, fix1away, fix2home, fix2away, fix3home, fix3away, fix4home, fix4away, fix5home, fix5away, fix6home, fix6away FROM tbl_create_fixtures where team_grade = 'CPS' and year = " . $year . " and season = '" . $season . "' and round = '" . $round . "';";
    $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $build_club = $result_club->fetch_assoc();
    $fixArray_3 = [];
    $fixArray_3 = $build_club['fix1home'] . ', ' . $build_club['fix1away'] . ', ' . $build_club['fix2home'] . ', ' . $build_club['fix2away'] . ', ' . $build_club['fix3home'] . ', ' . $build_club['fix3away'] . ", " . $build_club['fix4home'] . ', ' . $build_club['fix4away'] . ', ' . $build_club['fix5home'] . ', ' . $build_club['fix5away'] . ', ' . $build_club['fix6home'] . ', ' . $build_club['fix6away'];

    $fixArray = $fixArray_1 . ", " . $fixArray_2 . ", " . $fixArray_3;
    return $fixArray;
}

function HomeTeamsWithByes($team)
{   
    $new_array = [];
    $teamArray = [];
    $numOfTrue = 0;
    for($y = 0; $y < 18; $y++)
    {
        $new_array[$y] = GetAllGames(($y+1), 2024, 'S2');
    }
    for($x = 0; $x < sizeof($new_array); $x++)
    {
        $teamArray = explode(", ", $new_array[$x]);
        for($z = 0; $z < sizeof($new_array); $z+=2)
        {
            if($teamArray[$z] != '')
            {
                if(($teamArray[$z] == 'Bye')  || ($teamArray[$z+1] == 'Bye'))
                {
                    if(($teamArray[$z] == $team) && ($teamArray[$z] != 'Bye'))
                    {
                        $numOfTrue++;
                        echo($teamArray[$z] . ", " .  $teamArray[$z+1] . ", " . $numOfTrue . "<br>");
                    }
                }
            }
            
        }
    }
    //return ($numOfTrue);
}

$sql_club = 'Select * from Team_entries where team_cal_year = ' . $year . ' and team_grade = "APS" and day_played = "Wed" and team_season = "' . $season . '"';
//echo("Page " . $form_no . ", SQL " . $sql_club . "<br>");
$result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
$teams = 0;
$fixtures = '';
// create string for fixture generator
while($row = $result_club->fetch_assoc()) 
{
    HomeTeamsWithByes($row['team_name']);
}

function CountTeams($fixArray, $team)
{   
    $numOfTrue = 0;
    $teamArray = explode(", ", $fixArray);
    foreach($teamArray as $arr)
    {
        if(($arr == $team))
        //if(($arr == $team) && $team != HomeTeamsWithByes($team))
        {
            $numOfTrue++;
            echo("Team " . $team . ", Array " .  $arr . ", No. " . $numOfTrue . "<br>");
        }
    }
    return ($numOfTrue);
}

function CountClubs($fixArray, $year, $season, $club_name, $day_played)
{   
    global $connvbsa;
    $numOfTrue = 0;
    $sql_club_name = "Select team_name, team_club_id, team_club FROM Team_entries where team_club = '" . $club_name . "' and team_cal_year = " . $year . " and team_season = '" . $season . "' and day_played = '" . $day_played . "'";
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
</head>
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';

if($day_played == 'Mon')
{
    $title_caption = "(Monday Matches)";
}
elseif($day_played == 'Wed')
{
    $title_caption = "(Wednesday Matches)";
}
?>
<table width="800" align="center">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center"><span class="red_bold"><h3>Generate Fixtures <?= $title_caption ?> <?= $season ?></h3></span></td>
    <td align="right" nowrap="nowrap" class="greenbg"><a href="AA_scores_index_select_season.php">Return to opening page</a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<table class='table dt-responsive nowrap display' align='center' width='800px'>
  <tr>
<?php

// get team grades from Team entries table for menu
$sql_grades_menu = 'Select distinct team_grade from Team_entries where team_cal_year = ' . $year . ' and day_played = "' . $day_played . '" and team_season = "' . $season . '" order by team_grade';
//echo($sql_grades_menu . "<br>");
$result_grades_menu = mysql_query($sql_grades_menu, $connvbsa) or die(mysql_error());
$i = 1;
while($build_data_menu = $result_grades_menu->fetch_assoc())
{
    echo("<th class='text-center'><button type='button' class='btn btn-primary' onclick='Viewtab(" . $i . ");' style='width:120px'>" . $build_data_menu['team_grade'] . "</button></th>");
    $i++;
}
?>
</tr>
<tr>
    <th colspan=3>&nbsp;</th>
</tr>
<tr>
    <th colspan=3 class='text-center'><button type='button' class='btn btn-primary' onclick='Viewtab(10);' style='width:300px'>Analyse Data</button></th>
</tr>
<tr>
    <th colspan=3>&nbsp;</th>
</tr>
</table>
<center>
<?php
// get unavailable dates
$sql_dates = 'Select * from tbl_dates where year = ' . $year;
$result_dates = mysql_query($sql_dates, $connvbsa) or die(mysql_error());
$dates = '';
while($row = $result_dates->fetch_assoc()) 
{
    $dates = substr($row['new_year'], 0, 10) . "\n" . substr($row['aus_day'], 0, 10) . "\n" . substr($row['labour_day'], 0, 10) . "\n" . substr($row['good_friday'], 0, 10) . "\n" . substr($row['sat_before_easter'], 0, 10) . "\n" . substr($row['easter_sunday'], 0, 10) . "\n" . substr($row['easter_monday'], 0, 10) . "\n" . substr($row['anzac_day'], 0, 10) . "\n" . substr($row['kings_birthday'], 0, 10) . "\n" . substr($row['friday_afl'], 0, 10) . "\n" . substr($row['melbourne-cup'], 0, 10) . "\n" . substr($row['xmas_day'], 0, 10) . "\n" . substr($row['boxing_day'], 0, 10) . "\n" . substr($row['other_1'], 0, 10) . "\n" . substr($row['other_2'], 0, 10) . "\n" . substr($row['other_3'], 0, 10);
}
echo("<form name='fixture' method='post' action='generate_fixtures.php'>");
echo("<input type='hidden' name='FormNo' />");
echo("<input type='hidden' name='Sortby' />");
echo("<input type='hidden' name='season' />");
echo('<input type="hidden" name="year" />');
echo("<input type='hidden' name='ButtonName' />");
echo('<input type="hidden" name="TeamGrade" />');
echo("<input type='hidden' name='ScoreData' />");
echo('<input type="hidden" name="Fixtures">');
echo('<input type="hidden" name="Type">');
echo('<input type="hidden" name="Grade">');
echo('<input type="hidden" name="DayPlayed">');
$form_no = 1;
$team_grade = '';
$sql_grades = 'Select distinct team_grade from Team_entries where team_cal_year = ' . $year . ' and day_played = "' . $day_played . '" and team_season = "' . $season . '" order by team_grade';
//echo("Grades " . $sql_grades . "<br>");
$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
while($build_data_grades = $result_grades->fetch_assoc())
{
    $team_grade = $build_data_grades['team_grade'];
    $sql_club = 'Select * from Team_entries where team_cal_year = ' . $year . ' and team_grade = "' . $build_data_grades['team_grade'] . '" and day_played = "' . $day_played . '" and team_season = "' . $season . '" ' . $sortby;
    //echo("Page " . $form_no . ", SQL " . $sql_club . "<br>");
    $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $teams = 0;
    $fixtures = '';
    // create string for fixture generator
    while($row = $result_club->fetch_assoc()) 
    {
        $fixtures = $row['team_name'] . ", " . $fixtures;
        $teams++;
        $comptype = $row['comptype'];
    }
    $fixtures = substr($fixtures, 0, strlen($fixtures)-2);
    $fix_test = json_encode($fixtures);

    $result_club_display = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $num_club_display = $result_club_display->num_rows;
    echo("<div id='page" . $form_no . "'>");
    echo('<input type="hidden" name="form_no" id="form_no" value=' . $form_no . '>');
    echo("<table class='table table-striped table-bordered dt-responsive nowrap display' border='1'>
         <tr>
            <td align='center'>" . $build_data_grades['team_grade'] . "</td>
            <td align='center' id='comptype_" . $form_no . "''>" . $comptype . "</td>
        </tr>
        <tr>
            <td>");
    echo('<table class="table table-striped table-bordered dt-responsive display text-center" class="col-6">
        <thead>');
    echo('<th align="center">Position ID</th>');
    echo('<th align="center">Club ID</th>
          <th align="center">Tables</th>
          <th align="center">Club Name</th>
          <th align="center">Team Name</th>
          <th align="center">Home Games</th>
        </thead>
        <tbody class="row_position_' . $form_no . '">');
    $x = 1;
    while($build_data_club = $result_club_display->fetch_assoc())
    {
        $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $build_data_club["team_club_id"];
        $result_club_tables = mysql_query($sql_club_tables, $connvbsa) or die(mysql_error());
        $tables = $result_club_tables->fetch_assoc();
        $club_tables = $tables['ClubTables'];
        $day_played = $build_data_club["day_played"];
        echo('
          <tr draggable="true" ondragstart="start()" ondragover="dragover()" data-index="' . $index . '" id=' . $build_data_club["team_grade"] . ',' . $build_data_club["team_id"] . '> 
            <td align="center" id="sort_' . $form_no . '_' . $x . '_id">' . $x . '</td>
            <input type="hidden" id="sort_form" value=' . $form_no . '>');
        echo('<td align="center" id="club_' . $form_no . '_' . $x . '_id">' . $build_data_club["team_club_id"] . '</td>
            <td align="center">' . $club_tables . '</td>
            <td align="center">' . $build_data_club["team_club"] . '</td>
            <td align="center" id="club_' . $form_no . '_' . $x . '">' . $build_data_club["team_name"] . '</td>');
        echo('<td align="center" id="games_' . $form_no . '_' . $x . '">00</td>
          </tr>
        ');
        $x++;
    }
    echo("<tr><td>&nbsp;</td></tr>");
    echo("<tr>"); ?>
    <td colspan=6 align="center">Table Sort By:-&nbsp;
        <select name="sort_order" id="sort_order" onchange="GetSort(this, <?= $form_no ?>, '<?= $season ?>', <?= $year ?>, '<?= $day_played ?>')">
    <?php    
        if(!isset($_POST['Sortby']))
        {
            echo('<option value="fix_sort" selected>Table Sort</option>');
        }
        echo('<option value="fix_sort">Team Table Sort</option>
              <option value="team_id_dec">Team ID DESC</option>
              <option value="team_name_dec">Team Name DESC</option>
              <option value="team_id_asc">Team ID ASC</option>
              <option value="team_name_asc">Team Name ASC</option>
              <option value="rand">Shuffle</option>
            </select>
        </td>');
    echo("</tr>");
    echo("</tbody>");
    echo("</table>");
    echo("</td>");
    echo("<td>");
    echo('<table class="table table-striped table-bordered dt-responsive display text-center">
    <thead>
        <th align="center">Analysis Data</th>
        <th align="center" id="team_grade_' . $form_no . '" >' . $build_data_grades['team_grade'] . '</th>
    </thead>
    <tbody>
    <tr>
        <td align="left">No of Teams</td>
        <td align="center" id="no_of_teams_' . $form_no . '">' . $teams . '</td>
    </tr>
    <tr>
        <td align="left">Start Date</td>
        <td align="center"><input type="text" name="startdate" id="startdate_' . $form_no . '" onfocusout="SetDate(' . $form_no . ", " . (($teams*2)-2) . ')" ></td>
    </tr>
    <tr>
        <td align="left">Day Played</td>
        <td align="center" id="dayplayed_' . $form_no . '" >' . $day_played . '</td>
    </tr>
    <tr>
        <td align="left" valign="top">Non available dates</td>
        <td align="center" rowspan=' . ($num_club-3) .'><textarea cols=10 rows=6>' . $dates . '</textarea></td>
    </tr>'
    );
    for($y = 0; $y < ($x-$teams); $y++)
    {
      echo('
          <tr>
            <td colspan=2>&nbsp;</td>
          </tr>
        ');
    }
    echo("<tr>");
    echo("<td align='center' class='greenbg' colspan='2'><button type='button' class='btn btn-primary savebutton' data-id=" . $form_no . ">Save Fixtures for " . $team_grade . "</button></td>");
    echo("</tr>");
    echo('</tbody>');
    echo("</table>");
    echo("</td>");
    echo("</tr>");
    echo("</table>");
    main($fixtures, $team_grade, $form_no, $year, $season); 
    CreateDateArray($form_no, $teams, $team_grade, $year, $season);
    echo("</table>");
    echo('</div>');
    $form_no++;
}

echo("<div id='page10'>");
echo('<table class="table table-striped table-bordered dt-responsive display text-center" border="1">
<tr>
    <td colspan=22 align="center"><b>Analysis Data (' . $day_played . ')</td>
</tr>
<tr>
    <td rowspan=3  colspan=4 align="center">Grade</td>
    <td colspan=18 align="center">Round/Date</td>
</tr>
<tr>
    <td align="center">1</td>
    <td align="center">2</td>
    <td align="center">3</td>
    <td align="center">4</td>
    <td align="center">5</td>
    <td align="center">6</td>
    <td align="center">7</td>
    <td align="center">8</td>
    <td align="center">9</td>
    <td align="center">10</td>
    <td align="center">11</td>
    <td align="center">12</td>
    <td align="center">13</td>
    <td align="center">14</td>
    <td align="center">15</td>
    <td align="center">16</td>
    <td align="center">17</td>
    <td align="center">18</td>
</tr>
<tr>
    <td align="center" id="date_0"></td>
    <td align="center" id="date_1"></td>
    <td align="center" id="date_2"></td>
    <td align="center" id="date_3"></td>
    <td align="center" id="date_4"></td>
    <td align="center" id="date_5"></td>
    <td align="center" id="date_6"></td>
    <td align="center" id="date_7"></td>
    <td align="center" id="date_8"></td>
    <td align="center" id="date_9"></td>
    <td align="center" id="date_10"></td>
    <td align="center" id="date_11"></td>
    <td align="center" id="date_12"></td>
    <td align="center" id="date_13"></td>
    <td align="center" id="date_14"></td>
    <td align="center" id="date_15"></td>
    <td align="center" id="date_16"></td>
    <td align="center" id="date_17"></td>
</tr>');

// get team grades from Team entries table
$sql_grades_menu = 'Select team_grade, count(team_grade) as count from Team_entries Join clubs where clubs.ClubNumber = Team_entries.team_club_id and team_cal_year = ' . $year . ' and day_played = "' . $day_played . '" group by team_grade';
$result_grades_menu = mysql_query($sql_grades_menu, $connvbsa) or die(mysql_error());
$i = 0;
while($build_data_menu = $result_grades_menu->fetch_assoc())
{
    $no_of_home_teams = $build_data_menu['count'];
    if($no_of_home_teams % 2 == 1) $no_of_home_teams++;
    $no_of_rounds = ($no_of_home_teams*2)-2;
    for($y = 0; $y < ($no_of_home_teams/2); $y++) // home teams in team grade
    {   
        echo("<tr>");
        echo('<td colspan=4 align="center" id="' . $build_data_menu['team_grade'] . '_round_' . ($y+1) . '">' . $build_data_menu['team_grade'] . '_round_' . ($y+1) . '</td>');
        for($j = 0; $j < $no_of_rounds; $j++)
        {
            echo('<td align="center" class="round_' . $j . '" id="' . $build_data_menu['team_grade'] . '_round_' . ($j+1) . '_pos_' . ($y+1) . '">' . $build_data_menu['team_grade'] . '_round_' . ($j+1) . '_pos_' . ($y+1) . '</td>');
        }
        echo("</tr>");
        $i++;
    }
    echo("<tr>");
    echo("<td class='text-center' colspan='22'>&nbsp;</td>");
    echo("</tr>");
}
echo('
<tr>
    <td colspan=22 align="center"><b>Table Utilisation (' . $day_played . ')</td>
</tr>
<tr>
    <td rowspan=3 align="center">Grade</td>
    <td rowspan=3 align="center">Club/Team</td>
    <td rowspan=3 align="center">Team Name</td>
    <td rowspan=3 align="center">Tables</td>
    <td colspan=18 align="center">Round/Date</td>
</tr>
<tr>
    <td align="center">1</td>
    <td align="center">2</td>
    <td align="center">3</td>
    <td align="center">4</td>
    <td align="center">5</td>
    <td align="center">6</td>
    <td align="center">7</td>
    <td align="center">8</td>
    <td align="center">9</td>
    <td align="center">10</td>
    <td align="center">11</td>
    <td align="center">12</td>
    <td align="center">13</td>
    <td align="center">14</td>
    <td align="center">15</td>
    <td align="center">16</td>
    <td align="center">17</td>
    <td align="center">18</td>
</tr>
<tr>
    <td align="center" id="table_date_0"></td>
    <td align="center" id="table_date_1"></td>
    <td align="center" id="table_date_2"></td>
    <td align="center" id="table_date_3"></td>
    <td align="center" id="table_date_4"></td>
    <td align="center" id="table_date_5"></td>
    <td align="center" id="table_date_6"></td>
    <td align="center" id="table_date_7"></td>
    <td align="center" id="table_date_8"></td>
    <td align="center" id="table_date_9"></td>
    <td align="center" id="table_date_10"></td>
    <td align="center" id="table_date_11"></td>
    <td align="center" id="table_date_12"></td>
    <td align="center" id="table_date_13"></td>
    <td align="center" id="table_date_14"></td>
    <td align="center" id="table_date_15"></td>
    <td align="center" id="table_date_16"></td>
    <td align="center" id="table_date_17"></td>
</tr>');

$sql_club = 'Select distinct team_club_id, team_club from Team_entries where team_cal_year = ' . $year . ' and team_season = "' . $season . '" and day_played = "' . $day_played . '" order by team_club';
$result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
$z = 0;
while($row = $result_club->fetch_assoc()) 
{
    $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $row["team_club_id"];
    $result_club_tables = mysql_query($sql_club_tables, $connvbsa) or die(mysql_error());
    $tables = $result_club_tables->fetch_assoc();
    $club_tables = $tables['ClubTables'];

    $sql_team = 'Select distinct team_name, team_grade from Team_entries where team_cal_year = ' . $year . ' and team_club_id = "' . $row['team_club_id'] . '"  and team_season = "' . $season . '" and day_played = "' . $day_played . '"';
    $result_team = mysql_query($sql_team, $connvbsa) or die(mysql_error());
    $y = 0;
    while($row_team = $result_team->fetch_assoc()) 
    {
        echo("<tr>");
        echo('<td align="center">' . $row_team['team_grade'] . '</td>');
        echo('<td align="center">&nbsp;</td>');
        echo('<td align="center">' . $row_team["team_name"] . '</td>');
        echo('<td align="center">&nbsp;</td>');
        for($j = 0; $j < 18; $j++)
        {
            $fixArray = GetHomeGames(($j+1), $year, $season);
            //$fixArray = GetAllGames(($j+1), $year, $season);
            $count = CountTeams($fixArray, $row_team['team_name']);
            echo('<td align="center">');
            echo($count*2);
            echo('</td>');
        }
        echo("</tr>");
        $y++;
    }
    echo("<tr>");
    echo('<td align="center"></td>');
    echo('<td align="center"><b>' . $row['team_club'] . '</b></td>');
    echo('<td align="center"></td>');
    echo('<td align="center" id="club_tables_' . $z . '"><b>' . $club_tables . '</b></td>');
    for($j = 0; $j < 18; $j++)
    {   
        $fixArray = GetHomeGames(($j+1), $year, $season);
        $club_count = CountClubs($fixArray, $year, $season, $row['team_club'], $day_played);
        if($club_tables < ($club_count*2))
        {
            $colour = 'style=background-color:red; color:white';
        }
        else
        {
            $colour = '';
        }
        echo('<td align="center" id="round_tables_' . $z . '" ' . $colour . '><b>');
        echo($club_count*2);
        echo('</b></td>');
    }
    echo("</tr>");
    $z++;
}
echo("</table>");

//echo("Year " . $year . "<br>");
// fixture generation code
function main($fixtures, $team_grade, $form_no, $year, $season) 
{
    echo show_fixtures(isset($teams) ?  nums(intval($teams)) : explode(", ", ($fixtures)), $team_grade, $form_no, $year, $season);
}

function nums($n) {
    $ns = array();
    for ($i = 1; $i <= $n; $i++) {
        $ns[] = $i;
    }
    return $ns;
}

function show_fixtures($names, $team_grade, $form_no, $year, $season)
{
    //global $dbcnx_client;
    $teams = sizeof($names);
    // If odd number of teams add a "ghost".
    $ghost = false;
    if ($teams % 2 == 1) {
        $teams++;
        $ghost = true;
    }
    // Generate the fixtures using the cyclic algorithm.
    $totalRounds = $teams - 1;
    $matchesPerRound = $teams / 2;
    $rounds = array();
    for ($i = 0; $i < $totalRounds; $i++) {
        $rounds[$i] = array();
    }
    for ($round = 0; $round < $totalRounds; $round++) {
        for ($match = 0; $match < $matchesPerRound; $match++) {
            $home = ($round + $match) % ($teams - 1);
            $away = ($teams - 1 - $match + $round) % ($teams - 1);
            // Last team stays in the same place while the others
            // rotate around it.
            if ($match == 0) {
                $away = $teams - 1;
            }
            $rounds[$round][$match] = team_name($home + 1, $names)
                . " v " . team_name($away + 1, $names);
        }
    }
    // Interleave so that home and away games are fairly evenly dispersed.
    $interleaved = array();
    for ($i = 0; $i < $totalRounds; $i++) {
        $interleaved[$i] = array();
    }
    $evn = 0;
    $odd = ($teams / 2);
    for ($i = 0; $i < sizeof($rounds); $i++) {
        if ($i % 2 == 0) {
            $interleaved[$i] = $rounds[$evn++];
        } else {
            $interleaved[$i] = $rounds[$odd++];
        }
    }
    $rounds = $interleaved;
    // Last team can't be away for every game so flip them
    // to home on odd rounds.
    for ($round = 0; $round < sizeof($rounds); $round++) {
        if ($round % 2 == 1) {
            $rounds[$round][0] = flip($rounds[$round][0]);
        }
    }
    echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='1000px'>
  <tr>");
    for ($i = 0; $i < sizeof($rounds); $i++) {
      echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo ("<td align='center'><select id='" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "'>");
            echo("<option value='" . $round_data[0] . "'>" . $round_data[0] . "</option>");
            // start dropbox fill
            $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $year . " and team_season = '" . $season . "' and team_grade = '" . $team_grade . "'";
            echo($sql_home_team . "<br>");
            $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());
            while($build_home_team = $result_home_team->fetch_assoc()) 
            {
              echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
            }
            echo("</select></td>");
            // end dropbox fill
            echo("<td align='center'>v</td>");
            echo ("<td align='center'><select id='" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "'>");
            echo("<option value='" . $round_data[1] . "'>" . $round_data[1] . "</option>");
            // start dropbox fill
            $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $year . " and team_season = '" . $season . "' and team_grade = '" . $team_grade . "'";
            echo($sql_home_team . "<br>");
            $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());
            while($build_home_team = $result_home_team->fetch_assoc()) 
            {
              echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
            }
            echo("</select></td>");
            // end dropbox fill
            echo("</tr>");
            $x++;
        }
    }
    $round_counter = sizeof($rounds) + 1;
    for ($i = sizeof($rounds) - 1; $i >= 0; $i--) {
        echo("<td colspan=3 align='center'><b>Round " . $round_counter  . "</b></td>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px'></td></tr>");
        $round_counter += 1;
        $y = 0;    
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", flip($r));
            echo("<tr>");
            echo ("<td align='center'><select id='" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "'>");
            echo("<option value='" . $round_data[0] . "'>" . $round_data[0] . "</option>");
            // start dropbox fill
            $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $year . " and team_grade = '" . $team_grade . "'";
            echo($sql_home_team . "<br>");
            $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());
            while($build_home_team = $result_home_team->fetch_assoc()) 
            {
              echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
            }
            echo("</select></td>");
            // end dropbox fill
            echo("<td align='center'>v</td>");
            echo ("<td align='center'><select id='" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "'>");
            echo("<option value='" . $round_data[1] . "'>" . $round_data[1] . "</option>");
            // start dropbox fill
            $sql_home_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $year . " and team_grade = '" . $team_grade . "'";
            echo($sql_home_team . "<br>");
            $result_home_team = mysql_query($sql_home_team, $connvbsa) or die(mysql_error());
            while($build_home_team = $result_home_team->fetch_assoc()) 
            {
              echo("<option value='" . $build_home_team['team_name'] . "'>" . $build_home_team['team_name'] . "</option>");
            }
            echo("</select></td>");
            // end dropbox fill
            echo("</tr>");
            $y++;
        }
    }
    if ($ghost) {
        print "Matches against team " . $teams . " are byes.";
    }
}

function flip($match) {
    $components = explode(' v ', $match);
    return $components[1] . " v " . $components[0];
}

function team_name($num, $names) {
    $i = $num - 1;
    if (sizeof($names) > $i && strlen(trim($names[$i])) > 0) {
        return trim($names[$i]);
    } else {
        return $num;
    }
}

echo("</form>");
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
        updateOrder(selectedData);
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
        updateOrder(selectedData);
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
        updateOrder(selectedData);
    }
});

function updateOrder(aData) 
{
    $.ajax({
        url: '../vbsa_online_scores/save_sort_index.php?allData=' + aData,
        type: 'GET',
        success: function(response) {
            //alert("Your change successfully saved");
            //location.reload();
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
  //console.log("Child Start " + children.indexOf(row));
  if(children.indexOf(e.target.parentNode) > children.indexOf(row))
  {
    e.target.parentNode.after(row);
    //console.log("Child After " + children.indexOf(row));
  }
  else
  {
    e.target.parentNode.before(row);
    //console.log("Child Before " + children.indexOf(row));
  }
}

</script>





