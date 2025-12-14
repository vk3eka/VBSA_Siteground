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

<style>
    .ui-sortable-helper {
  display: table;
}
</style>
<?php
if (isset($_GET['season'])) 
{
    $season = $_GET['season'];
    $_SESSION['session_season'] = $season;
}
elseif (isset($_POST['season']))
{
    $season = $_POST['season'];
    $_SESSION['session_season'] = $season;
}
$year = date('Y');

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

$season = $_SESSION['session_season'];
$dayplayed = $_SESSION['session_dayplayed'];

//echo('Season ' . $season . "<br>");
//echo('Day Played ' . $dayplayed . "<br>");

// get team grades for fixture list
$team_grades = '';
$sql = "Select grade, dayplayed, grade_start_date, current FROM Team_grade WHERE season ='$season' AND fix_cal_year = $year and dayplayed = '$dayplayed' and current = 'Yes' ORDER BY grade ASC";
$result_team_grades = mysql_query($sql, $connvbsa) or die(mysql_error());
while($build_team_grades = $result_team_grades->fetch_assoc())
{
    $grade_start = $build_team_grades['grade_start_date'];
    $grade = $build_team_grades['grade'];
    $team_grades .= $grade . ", ";
}

echo("<script type='text/javascript'>");
echo("function SaveAllData() {");
$team_grade = explode(", ", $team_grades);
$form = 0;
foreach($team_grade as $grade)
{
    if($grade != '')
    {
        $form++;
        $sql = "Select fix1home, team_grade, season, year, dayplayed FROM tbl_create_fixtures WHERE season = '" . $season . "' AND year = " . $year . " AND team_grade = '" . $grade . "' AND dayplayed = '" . $dayplayed . "' ORDER BY team_grade";
        //echo($sql . "<br>");
        $result_fixtures = mysql_query($sql, $connvbsa) or die(mysql_error());
        $fixture_count = $result_fixtures->num_rows;
        if($fixture_count == 0)
        {
            echo("$.fn.save_fixtures(" . $form . ", 'NoResponse');");
        }
    }
}

echo("}");
echo("</script>");

echo("<script type='text/javascript'>");
echo("function GetAnalyisData() {");
$team_grade = explode(", ", $team_grades);
$form = 0;
foreach($team_grade as $grade)
{
    if($grade != '')
    {
        $form++;
        $sql = "Select team_name FROM Team_entries WHERE team_season ='$season' AND team_cal_year = $year AND team_grade = '$grade' AND day_played = '$dayplayed' ORDER BY team_name";
        $result_home_games = mysql_query($sql, $connvbsa) or die(mysql_error());
        $teams = $result_home_games->num_rows;
        $rounds = (($teams*2)-2);
        $fixtures = ($teams/2);
        for($i = 0; $i < $rounds; $i++)
        {
            echo("document.getElementById('date_" . $i . "').innerHTML = document.getElementById('A_" . $form . "_date_" . $i . "').value;");
            echo("document.getElementById('table_date_" . $i . "').innerHTML = document.getElementById('A_" . $form . "_date_" . $i . "').value;");
            $round = ($i+1);
            for($j = 0; $j < $fixtures; $j++)
            {
                echo("document.getElementById('" . $grade . "_round_" . ($j+1) . "').innerHTML = '" . $grade . "';");
                echo("document.getElementById('" . $grade . "_round_" . $round . "_pos_" . ($j+1) . "').innerHTML = document.getElementById('A_" . $grade . "_home_" . $round . "_" . ($j+1) . "').value;");
            }
        }
    }
}
echo("}");
echo("</script>");

?>

<?php
include "save_fixtures_include.php";
?>
<script>

$(document).ready(function()
{
    $('.savebutton').click(function()
    {
        event.preventDefault();
        var form_no = $(this).data("id"); 
        $.fn.save_fixtures(form_no, 'Response');
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

if(isset($_POST['ButtonName']) && (($_POST['ButtonName']) == 'ResetFixtures'))
{   
    // set existing fix sort and game count to 0
    $sql_update = "Update Team_entries set home_games = 0, fix_sort = 0 WHERE team_season ='" . $season . "' AND team_cal_year = " . $year;
    $result_update  = mysql_query($sql_update, $connvbsa) or die(mysql_error());
    
    // delete existing fixtures
    $sql_delete = "Delete FROM tbl_create_fixtures where year = " . $year . " and season = '" . $season . "'";
    $result_delete  = mysql_query($sql_delete, $connvbsa) or die(mysql_error());
    header("Location:'" . $_SERVER['PHP_SELF'] . "?DayPlayed=" . $dayplayed ."&season=" .  $season . "'");
}

if(isset($_POST['ButtonName']) && (($_POST['ButtonName']) == 'PublishFixtures'))
{   
    // delete existing fixtures
    $sql_delete = "Delete FROM tbl_fixtures where year = " . $year . " and season = '" . $season . "'";
    $result_delete  = mysql_query($sql_delete, $connvbsa) or die(mysql_error());

    // get existing create fixtures data and insert into main fixtures table
    $sql_create = 'Select * from tbl_create_fixtures Order By team_grade';
    $result_create = mysql_query($sql_create, $connvbsa) or die(mysql_error());
    while($row_create = $result_create->fetch_assoc()) 
    {
        $sql = "Insert into tbl_fixtures (
        date,
        type,
        grade,
        round,
        fix1home,
        fix1away,
        fix2home,
        fix2away,
        fix3home,
        fix3away,
        fix4home,
        fix4away,
        fix5home,
        fix5away,
        fix6home,
        fix6away,
        year,
        season,
        team_grade,
        dayplayed
        )
        VALUES
        ('" . 
        $row_create['date'] . "', '" . 
        $row_create['type'] . "', '" . 
        $row_create['grade'] . "', " . 
        $row_create['round'] . ", '" . 
        $row_create['fix1home'] . "', '" . 
        $row_create['fix1away'] . "', '" . 
        $row_create['fix2home'] . "', '" . 
        $row_create['fix2away'] . "', '" . 
        $row_create['fix3home'] . "', '" . 
        $row_create['fix3away'] . "', '" . 
        $row_create['fix4home'] . "', '" . 
        $row_create['fix4away'] . "', '" . 
        $row_create['fix5home'] . "', '" . 
        $row_create['fix5away'] . "', '" . 
        $row_create['fix6home'] . "', '" . 
        $row_create['fix6away'] . "', " . 
        $year . ", '" . 
        $row_create['season'] . "', '" . 
        $row_create['team_grade'] . "', '" . 
        $row_create['dayplayed'] . "')";
        $update = mysql_query($sql, $connvbsa) or die(mysql_error());
    }
    header('Location:' . $_SERVER['PHP_SELF'] . '?DayPlayed=' . $dayplayed . '&season=' . $season);
}

if(isset($_POST['ButtonName']) && (($_POST['ButtonName']) == 'SaveSort'))
{   
    echo("<script>");
    echo("$(document).ready(function(){");
    echo("$.fn.save_fixtures(" . $form_no . ", 'Response');");
    echo("});");
    echo("</script>");

    $grade = $_POST['TeamGrade'];
    $form_no = $_POST['FormNo'];
    $year = $_POST['year'];
    $season = $_POST['season'];
    $dayplayed = $_POST['DayPlayed'];
    $sort = $_POST['Sortby'];
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
    $sql_sort_order = 'Select * from Team_entries where team_cal_year = ' . $year . ' and team_grade = "' . $grade . '" and team_season = "' . $season . '" ' . $sortby;
    $result_sort_order = mysql_query($sql_sort_order, $connvbsa) or die(mysql_error());
    $row_count = $result_sort_order->num_rows;
    $i = 1;
    while($row = $result_sort_order->fetch_assoc()) 
    {
        $sql_update = 'Update Team_entries set fix_sort = ' . $i . ' where team_cal_year = ' . $year . ' and team_grade = "' . $grade . '" and team_season = "' . $season . '" and team_id = ' . $row['team_id'];
        //echo($sql_update . "<br>");
        $result_update  = mysql_query($sql_update, $connvbsa) or die(mysql_error());
        $i++;
    }
    header("Location:'" . $_SERVER['PHP_SELF'] . "?DayPlayed=" . $dayplayed ."&season=" .  $season . "'");
}

function CreateDateArray($form_no, $teams, $team_grade, $year, $season) 
{
    for($y = 0; $y < (($teams*2)-2); $y++)
    {
        global $connvbsa;
        $sql_playing_dates = 'Select * from tbl_create_fixtures where year = ' . $year . ' and team_grade = "' . $team_grade . '" and season = "' . $season . '" and round = ' . ($y+1);
        $result_playing_dates = mysql_query($sql_playing_dates, $connvbsa) or die(mysql_error());
        echo("<script type='text/javascript'>");
        while($row = $result_playing_dates->fetch_assoc()) 
        {
            $row_date = $row['date'];
            echo("document.getElementById('A_" . $form_no . "_date_" . $y . "').value = '" . $row_date . "';");
            // used to color non avaiable dates red
            $sql_dates = 'Select * from tbl_ics_dates where Year(DTSTART) >= YEAR(CURDATE() - 1) order by DTSTART';
            $result_dates = mysql_query($sql_dates, $connvbsa) or die(mysql_error());
            while($row_dates = $result_dates->fetch_assoc()) 
            {
                if($row_date == $row_dates['DTSTART'])
                {
                    echo("document.getElementById('A_" . $form_no . "_date_" . $y . "').style.color = 'red';");
                }
            }
        }
        echo("</script>");
    }
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
    SaveAllData();
}

function GetSort(sel, form_no, season, year, dayplayed, team_grade) 
{
    var sort_order = sel.options[sel.selectedIndex].value;
    document.fixture.ButtonName.value = 'SaveSort'; 
    document.fixture.Sortby.value = sort_order;
    document.fixture.TeamGrade.value = team_grade;
    document.fixture.FormNo.value = form_no;
    document.fixture.year.value = year;
    document.fixture.season.value = season;
    document.fixture.DayPlayed.value = dayplayed;
    document.fixture.submit();
}

function ResetFixtures() 
{
    document.reset_fixture.ButtonName.value = 'ResetFixtures'; 
    document.reset_fixture.year.value = <?= $year ?>;
    document.reset_fixture.season.value = '<?= $season ?>';
    document.reset_fixture.DayPlayed.value = '<?= $dayplayed ?>';
    document.reset_fixture.submit();
}

function PublishFixtures() 
{
    document.reset_fixture.ButtonName.value = 'PublishFixtures'; 
    document.reset_fixture.submit();
}

</script>
<?php

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
    //echo("<pre>");
    //echo(var_dump($fixArray));
    //echo("</pre>");

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
    //echo("<pre>");
    //echo(var_dump($fixArray));
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
    //$numOfTrue = 0;
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
</head>
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';

if($dayplayed == 'Mon')
{
    $title_caption = "(Monday Matches)";
}
elseif($dayplayed == 'Wed')
{
    $title_caption = "(Wednesday Matches)";
}
?>
<form name='reset_fixture' method='post' action='generate_fixtures.php?DayPlayed=<?= $dayplayed ?>&season=<?= $season ?>'>
<input type='hidden' name='season' />
<input type="hidden" name="year" />
<input type='hidden' name='ButtonName'/>
<input type='hidden' name='DayPlayed'/>

<table class='table dt-responsive nowrap display' align='center' width='800px'>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center"><span class="red_bold"><h3>Generate Fixtures <?= $title_caption ?> <?= $season ?></h3></span></td>
  </tr>
  <tr>
    <td align="center" nowrap="nowrap" class="greenbg"><a href="#" onclick='ResetFixtures()'>Reset to defaults</a></td>
    <td align="center" nowrap="nowrap" class="greenbg"><a href="#" onclick='PublishFixtures()'>Publish Fixtures</a></td>
    <td align="center" nowrap="nowrap" class="greenbg"><a href="create_fixtures_pdf.php?Year=2024&Season=S2&Team_Grade=BVS2&DayPlayed=Wed";>Generate Fixtures PDF</a></td>
    <td align="center" nowrap="nowrap" class="greenbg"><a href="AA_scores_index_grades.php?season=<?= $season ?>">Return to <?= $season ?> page</a></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>
</form>
<table class='table dt-responsive nowrap display' align='center' width='800px'>
  <tr>
<?php

// get team grades from Team entries table for menu
$sql_grades_menu = 'Select distinct team_grade from Team_entries where team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" and team_season = "' . $season . '" order by team_grade';
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
    <th colspan=<?= $i ?>>&nbsp;</th>
</tr>
<tr>
    <th colspan=<?= $i ?> class='text-center'><button type='button' class='btn btn-primary' onclick='Viewtab(10);' style='width:300px'>Analyse Data</button></th>
</tr>
<tr>
    <th colspan=<?= $i ?>>&nbsp;</th>
</tr>
<tr>
    <th colspan=<?= $i ?>>&nbsp;</th>
</tr>
</table>
<center>
<?php

// get unavailable dates
$sql_dates = 'Select * from tbl_ics_dates where Year(DTSTART) >= YEAR(CURDATE() - 1) order by DTSTART';
$result_dates = mysql_query($sql_dates, $connvbsa) or die(mysql_error());
$dates = '';
while($row = $result_dates->fetch_assoc()) 
{
    $dates .= $row['DTSTART'] . "\n";
}

echo("<form name='fixture' method='post' action='generate_fixtures.php?DayPlayed=" . $dayplayed . "&season=" . $season . "'>");
echo("<input type='hidden' name='FormNo' id='FormNo' value='" . $form_no . "' />");
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

$team_grade = '';
$sql_grades = 'Select distinct team_grade from Team_entries where team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" and team_season = "' . $season . '" order by team_grade';
$result_grades = mysql_query($sql_grades, $connvbsa) or die(mysql_error());
while($build_data_grades = $result_grades->fetch_assoc())
{
    $team_grade = $build_data_grades['team_grade'];
    $sql_club = 'Select * from Team_entries where team_cal_year = ' . $year . ' and team_grade = "' . $build_data_grades['team_grade'] . '" and day_played = "' . $dayplayed . '" and team_season = "' . $season . '" ' . $sortby;
    $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $teams = 0;
    $fixtures = '';
    // create string for fixture generator
    while($row = $result_club->fetch_assoc()) 
    {
        $fixtures = $row['team_name'] . ", " . $fixtures;
        $teams++;
        $team_name = $row['team_name'];
        $comptype = $row['comptype'];
    }

    // add a bye if uneven number of teams.
    if(($teams % 2) == 1)
    {
        $sql_insert_bye = "Insert INTO Team_entries (team_club_id, team_name, team_grade, team_season, day_played, players, Final5, include_draw, audited, team_cal_year, comptype) VALUES (0, 'Bye', '" . $build_data_grades['team_grade'] . "', '" . $season . "', '" . $dayplayed . "', 4, 4, 'Yes', 'No', " . $year . ", '" . $comptype . "')";
        $update = mysql_query($sql_insert_bye, $connvbsa) or die(mysql_error());
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
    echo('<th align="center">Team ID</th>');
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
        $day_layed = $build_data_club["day_played"];
        echo('
          <tr draggable="true" ondragstart="start()" ondragover="dragover()" data-index="' . $index . '" id=' . $build_data_club["team_grade"] . ',' . $build_data_club["team_id"] . '> 
            <td align="center" id="sort_' . $form_no . '_' . $x . '_id">' . $build_data_club["fix_sort"] . '</td>
            <td align="center" id="' . $form_no . '_' . $x . '_id">' . $build_data_club["team_id"] . '</td>
            <input type="hidden" id="sort_form" value=' . $form_no . '>');
        echo('<td align="center" id="club_' . $form_no . '_' . $x . '_id">' . $build_data_club["team_club_id"] . '</td>
            <td align="center">' . $club_tables . '</td>
            <td align="left">' . $build_data_club["team_club"] . '</td>
            <td align="left" id="club_' . $form_no . '_' . $x . '">' . $build_data_club["team_name"] . '</td>');
        echo('<td align="center" id="games_' . $form_no . '_' . $x . '">' . $build_data_club["home_games"] . '</td>
          </tr>
        ');
        $x++;
    }
    echo("<tr><td>&nbsp;</td></tr>");
    echo("<tr>"); ?>
    <td colspan="7" align="center">Table Sort By:-&nbsp;
        <select name="sort_order" id="sort_order" onchange="GetSort(this, <?= $form_no ?>, '<?= $season ?>', <?= $year ?>, '<?= $dayplayed ?>', '<?= $team_grade ?>')">
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
        <td align="center"><input type="text" name="startdate" id="startdate_' . $form_no . '" value=' . $grade_start . ' style="width:100px"></td>
    </tr>
    <tr>
        <td align="left">Day Played</td>
        <td align="center" id="dayplayed_' . $form_no . '" >' . $dayplayed . '</td>
    </tr>
    <tr>
        <td align="left" valign="top">Non available dates</td>
        <td align="center" rowspan=' . ($num_club-3) .'><textarea cols=10 rows=6>' . $dates . '</textarea></td>
    </tr>'
    );
    // onfocusout="SetFixtureDate(' . $form_no . ", " . (($teams*2)-2) . ')""
    for($y = 0; $y < ($x-$teams); $y++)
    {
      echo('
          <tr>
            <td colspan=2>&nbsp;</td>
          </tr>
        ');
    }
    
    // get create fixtures table data to see if saved already
    $sql_create = 'Select team_grade, year, season, dayplayed from tbl_create_fixtures where year = ' . $year . ' and dayplayed = "' . $dayplayed . '" and team_grade = "' . $team_grade . '" and season = "' . $season . '"';
    $result_create = mysql_query($sql_create, $connvbsa) or die(mysql_error());
    $create_data_exists = $result_create->num_rows;
    if($create_data_exists > 0)
    {
        echo("<tr>");
        echo("<td align='center' class='greenbg' colspan='2'><button type='button' style='color:black;' class='btn btn-primary savebutton' data-id=" . $form_no . ">Save Fixtures for " . $team_grade . "</button></td>");
        echo("</tr>");
    }
    else
    {
        echo("<tr>");
        echo("<td align='center' class='greenbg' colspan='2'><button type='button' style='color:red;' class='btn btn-primary savebutton' data-id=" . $form_no . ">Save Fixtures for " . $team_grade . "</button></td>");
        echo("</tr>");
    }
    echo('</tbody>');
    echo("</table>");
    echo("</td>");
    echo("</tr>");
    echo("</table>");

    $sql_fixtures = "Select fix1home FROM tbl_create_fixtures WHERE season ='$season' AND year = '$year' AND team_grade = '$team_grade' AND dayplayed = '$dayplayed'";
    $result_fixtures = mysql_query($sql_fixtures, $connvbsa) or die(mysql_error());
    $no_of_fixtures = $result_fixtures->num_rows;
    
    PopulateFromDatabase($team_grade, $form_no, $year, $season, $dayplayed);
    echo('<br>');
    main($fixtures, $team_grade, $form_no, $year, $season); 

    CreateDateArray($form_no, $teams, $team_grade, $year, $season);
    echo('</div>');
    $form_no++;
}

echo("<div id='page10'>");
//echo('<table class="table table-striped table-bordered dt-responsive display text-center" border="1">');
echo('<table class="table table-striped table-bordered dt-responsive display text-center" style="display: none" border="1">');
echo('<tr>
    <td colspan=22 align="center"><b>Analysis Data (' . $dayplayed . ')</td>
</tr>
<tr>
    <td rowspan=3  colspan=4 align="center">Grade</td>
    <td colspan=18 align="center">Round/Date</td>
</tr>');

echo('<tr>');
for($i = 0; $i < 18; $i++)
{
    echo("<td align='center'>" . ($i+1) . "</td>");
}
echo('</tr>');
echo('<tr>');
for($i = 0; $i < 18; $i++)
{
    echo("<td align='center' id='date_" . $i . "'></td>");
}
echo('</tr>');

// get team grades from Team entries table
$sql_grades_menu = 'Select team_grade, count(team_grade) as count from Team_entries Join clubs where clubs.ClubNumber = Team_entries.team_club_id and team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" AND team_season = "' . $season . '"  group by team_grade';
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
</table>
<table class="table table-striped table-bordered dt-responsive display text-center" border="1">
<tr>
    <td colspan=22 align="center"><b>Table Utilisation (' . $dayplayed . ')</td>
</tr>
<tr>
    <td rowspan=3 align="center">Grade</td>
    <td rowspan=3 align="center">Club</td>
    <td rowspan=3 align="center">Team</td>
    <td rowspan=3 align="center">Tables</td>
    <td colspan=18 align="center">Round/Date</td>
</tr>');
echo('<tr>');
for($i = 0; $i < 18; $i++)
{
    echo("<td align='center'>" . ($i+1) . "</td>");
}
echo('</tr>');
echo('<tr>');
for($i = 0; $i < 18; $i++)
{
    echo("<td align='center' id='table_date_" . $i . "'></td>");
}
echo('</tr>');

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
        echo("<tr>");
        echo('<td align="center">' . $row_team['team_grade'] . '</td>');
        echo('<td align="center">&nbsp;</td>');
        echo('<td align="center">' . $row_team["team_name"] . '</td>');
        echo('<td align="center">&nbsp;</td>');
        for($j = 0; $j < 18; $j++)
        {
            $HomeFixArray = GetHomeGames($team_grades, ($j+1), $year, $season, $dayplayed);
            $AwayFixArray = GetAwayGames($team_grades, ($j+1), $year, $season, $dayplayed);
            $count = CountTeams($HomeFixArray, $AwayFixArray, $row_team['team_name'], $dayplayed);
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
        echo('<td align="center" id="round_tables_' . $z . '" ' . $colour . '><b>');
        echo($club_count*2);
        echo('</b></td>');
    }
    echo("</tr>");
    $z++;
}
echo("</table>");        

// fixture generation code (draggable)
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
    
    echo("<table style='background-color: grey; display: none' class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
    //echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
    echo("<tbody class='row_position_10'>");
    echo("<tr><td colspan=3 align='center'>(Algorithm)</td></tr>");
    for ($i = 0; $i < sizeof($rounds); $i++) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($i+1)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("</tr>");
            $x++;
        }
    }
    // Second half is mirror of first half
    $round_counter = sizeof($rounds) + 1;
    for ($b = (sizeof($rounds) - 1); $b >= 0; $b--) {
        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . $round_counter  . "</b></td>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='A_" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px'></td></tr>");
        $round_counter += 1;
        $y = 0;    
        foreach ($rounds[$b] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[1] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='A_" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[0] . "' style='width:200px'></td>");
            echo("</tr>");
            $y++;
        }
    }
    echo("</tbody>");
    echo("</table>");

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

function PopulateFromDatabase($team_grade, $form_no, $year, $season, $dayplayed)
{
    global $connvbsa;
    // get data for dataset 
    $sql = "Select team_name FROM Team_entries WHERE team_season ='$season' AND team_cal_year = '$year' AND team_grade = '$team_grade' AND day_played = '$dayplayed' ORDER BY team_name";
    $result_teams = mysql_query($sql, $connvbsa) or die(mysql_error());
    $no_of_teams = $result_teams->num_rows;
    $no_of_fixtures = ($no_of_teams/2);
    $no_of_rounds = (($no_of_teams*2)-2);
    echo("<table class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
    echo("<tbody class='row_position_" . ($form_no+10) . "'>");
    echo("<tr><td colspan=3 align='center'>(Database)</td></tr>");
    for($r = 0; $r < $no_of_rounds; $r++)
    {
        // get date
        $sql_dates = 'Select date from tbl_create_fixtures where year = ' . $year . ' and dayplayed = "' . $dayplayed . '" and season = "' . $season . '" and team_grade = "' . $team_grade . '" and round = ' . ($r+1);
        $result_dates = mysql_query($sql_dates, $connvbsa) or die(mysql_error());
        $build_dates = $result_dates->fetch_assoc();
        $date = date_create($build_dates['date']);
        $fixture_date = date_format($date, 'Y-m-d');

        echo("<tr><td>&nbsp;</td></tr>");
        echo("<td colspan=3 align='center'><b>Round " . ($r+1)  . "</b></td></tr>");
        echo("<tr><td align='right'><b>Date</b></td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='D_" . $form_no . "_date_" . $r . "'  value='" . $fixture_date . "' style='width:100px'></td></tr>");

        $sql_fixtures = 'Select * from tbl_create_fixtures where year = ' . $year . ' and dayplayed = "' . $dayplayed . '" and season = "' . $season . '" and team_grade = "' . $team_grade . '" and round = ' . ($r+1);
        $result_fixtures = mysql_query($sql_fixtures, $connvbsa) or die(mysql_error());
        $build_fixtures = $result_fixtures->fetch_assoc();
        for($y = 0; $y < $no_of_fixtures; $y++)
        {
            echo("<tr data-index='" . $y . "'>");
            echo("<td align='center'><input class='float-child' type='text' id='D_" . $team_grade . "_home_" . ($r+1) . "_" . ($y+1) . "' value='" . $build_fixtures['fix' . ($y+1) . "home"] . "' style='width:200px'></td>");
            echo("<td align='center'>v</td>");
            echo("<td align='center'><input class='float-child' type='text' id='D_" . $team_grade . "_away_" . ($r+1) . "_" . ($y+1) . "' value='" . $build_fixtures['fix' . ($y+1) . "away"] . "' style='width:200px'></td>");
            echo("</tr>");
        }
    }
    echo("</table>");
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
            window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
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

        year = <?= $year ?>;
        season = '<?= $season ?>';
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
                //window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
            },
        });

        EL_drag = undefined;
    };

    ELS_child.forEach((EL_child) => addEvents(EL_child));

});
</script> <!-- dragand drop fixtures -->

</body>
</html>
