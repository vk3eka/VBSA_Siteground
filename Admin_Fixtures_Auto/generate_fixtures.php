<?php
require_once('../Connections/connvbsa.php'); 

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


// get team grades for fixture list
$team_grades = '';
$sql = "Select grade, dayplayed, grade_start_date, current FROM Team_grade WHERE season ='$season' AND fix_cal_year = $year and dayplayed = '$dayplayed' and current = 'Yes' ORDER BY grade ASC";
$result_team_grades = mysql_query($sql, $connvbsa) or die(mysql_error());
$total_team_grades = $result_team_grades->num_rows;
//echo($total_team_grades . "<br>");
while($build_team_grades = $result_team_grades->fetch_assoc())
{
    $grade_start = $build_team_grades['grade_start_date'];
    $grade = $build_team_grades['grade'];
    $team_grades .= $grade . ", ";
}

require_once('Models/Fixture.php');

$fixture = new Fixture();

$fixture->LoadFixture($year, $season, $dayplayed);

$jsonData = json_encode($fixture);
//$jsonData = json_encode($fixture->rounds);

echo("Fixture Data " . $jsonData . "<br>");

/*
echo("<pre>");
echo(var_dump(json_decode($jsonData)));
echo("</pre>");
*/

//funciton RunReg (){

    // Clear content

    // Call function

    // on return reset var

    // Run Display function
//}

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

  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate_4");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");
  
}

function FirstLoad() {
    document.getElementById("page1").style.display = "block";
    document.getElementById("page2").style.display = "none";
    document.getElementById("page3").style.display = "none";
    <?php 
    if($total_team_grades == 4) 
    {
        echo('document.getElementById("page4").style.display = "none";');
    }
    ?>
}

function Viewtab(sel){
    switch (sel) {
    case 1:
        document.getElementById("page1").style.display = "block";
        document.getElementById("page2").style.display = "none";
        document.getElementById("page3").style.display = "none";
        <?php 
        if($total_team_grades == 4) 
        {
            echo('document.getElementById("page4").style.display = "none";');
        }
        ?>
        //document.getElementById("page4").style.display = "none";
        //document.getElementById("page10").style.display = "none"; 
        break;
    case 2:
        document.getElementById("page1").style.display = "none";
        document.getElementById("page2").style.display = "block";
        document.getElementById("page3").style.display = "none";
        <?php 
        if($total_team_grades == 4) 
        {
            echo('document.getElementById("page4").style.display = "none";');
        }
        ?>
        //document.getElementById("page4").style.display = "none";
        //document.getElementById("page10").style.display = "none"; 
        break;
    case 3:
        document.getElementById("page1").style.display = "none";
        document.getElementById("page2").style.display = "none";
        document.getElementById("page3").style.display = "block";
        <?php 
        if($total_team_grades == 4) 
        {
            echo('document.getElementById("page4").style.display = "none";');
        }
        ?>
        //document.getElementById("page4").style.display = "none";
        document.getElementById("page10").style.display = "none"; 
        break;

    if($total_team_grades == 4) 
    {
        echo('case 4:');
        echo('document.getElementById("page1").style.display = "none";');
        echo('document.getElementById("page2").style.display = "none";');
        echo('document.getElementById("page3").style.display = "none";');
        echo('document.getElementById("page4").style.display = "block";');
        //echo('document.getElementById("page10").style.display = "none";');
        echo('break;');
    }
    //document.getElementById("page4").style.display = "none";

    /*
    case 4:
        document.getElementById("page1").style.display = "none";
        document.getElementById("page2").style.display = "none";
        document.getElementById("page3").style.display = "none";
        document.getElementById("page4").style.display = "block";
        document.getElementById("page10").style.display = "none"; 
        break;
    */
    case 10:
        document.getElementById("page1").style.display = "none";
        document.getElementById("page2").style.display = "none";
        document.getElementById("page3").style.display = "none";
        <?php 
        if($total_team_grades == 4) 
        {
            echo('document.getElementById("page4").style.display = "none";');
        }
        ?>
        //document.getElementById("page4").style.display = "none";
        //document.getElementById("page10").style.display = "block"; 
        break;
    }
}

window.onload = function() 
{
    doOnLoad();
    FirstLoad();

}

function GetSort(sel, form_no, season, year, dayplayed, team_grade) 
{
    //console.log("GetSort");
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


</script>
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

// added to display current sort order
switch ($_POST['sort_order'])
{
    case 'team_id_dec':
        $sortOrder = 'Team Id (Descending)';
        break;
    case 'team_id_asc':
        $sortOrder = 'Team Id (Descending)';
        break;
    case 'team_name_dec':
        $sortOrder = 'Team Name (Descending)';
        break;
    case 'team_name_asc':
        $sortOrder = 'Team Name (Ascending)';
        break;
    case 'rand':
        $sortOrder = 'Shuffle';
        break;
    case 'fix_sort':
        $sortOrder = 'Position ID (Descending';
        break;
    default:
        $sortOrder = '';
        break;
}

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

if(isset($_POST['ButtonName']) && (($_POST['ButtonName']) == 'SaveSort'))
{   
    //echo("<script>");
    //echo("$(document).ready(function(){");
    //echo("$.fn.save_fixtures(" . $form_no . ", 'NoResponse');");
    //echo("});");
    //echo("</script>");

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
            $sortby = "";
            break;
    }
    $sql_sort_order = 'Select * from Team_entries where team_cal_year = ' . $year . ' and team_grade = "' . $grade . '" and team_season = "' . $season . '" ' . $sortby;
    $result_sort_order = mysql_query($sql_sort_order, $connvbsa) or die(mysql_error());
    $row_count = $result_sort_order->num_rows;
    $i = 1;
    while($row = $result_sort_order->fetch_assoc()) 
    {
        $sql_update = 'Update Team_entries set fix_sort = ' . $i . ' where team_cal_year = ' . $year . ' and team_grade = "' . $grade . '" and team_season = "' . $season . '" and team_id = ' . $row['team_id'];
        $result_update  = mysql_query($sql_update, $connvbsa) or die(mysql_error());
        $i++;
    }
    header("Location:'" . $_SERVER['PHP_SELF'] . "?DayPlayed=" . $dayplayed ."&season=" .  $season . "'");
}
/*
function CreateDateArray($form_no, $rounds, $team_grade, $year, $season) 
{
    //$no_of_finals = 3;
    //for($y = 0; $y < ($rounds+$no_of_finals); $y++)
    for($y = 0; $y < $rounds; $y++)

    {
        global $connvbsa;
        $sql_playing_dates = 'Select * from tbl_create_fixtures where year = ' . $year . ' and team_grade = "' . $team_grade . '" and season = "' . $season . '" and round = ' . ($y+1);
        $result_playing_dates = mysql_query($sql_playing_dates, $connvbsa) or die(mysql_error());
        echo("<script type='text/javascript'>");
        while($row = $result_playing_dates->fetch_assoc()) 
        {
            $row_date = $row['date'];
            echo("document.getElementById('A_" . $form_no . "_date_" . $y . "').value = '" . $row_date . "';");
            echo("document.getElementById('B_" . $form_no . "_date_" . $y . "').value = '" . $row_date . "';");
        }
        echo("</script>");
    }
}
*/
?>
<form name='reset_fixture' method='post' action='generate_fixtures.php?DayPlayed=<?= $dayplayed ?>&season=<?= $season ?>'>
<input type='hidden' name='season' />
<input type="hidden" name="year" />
<input type='hidden' name='ButtonName'/>
<input type='hidden' name='DayPlayed'/>

<table class='table dt-responsive nowrap display' align='center' width='800px'>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><span class="red_bold"><h3>Generate Fixtures <?= $title_caption ?> <?= $season ?></h3></span></td>
  </tr>
  <tr>
    <td align="center" nowrap="nowrap" class="greenbg"><a href="#" onclick='ResetFixtures()'>Reset to defaults</a></td>
    <td align="center" nowrap="nowrap" class="greenbg"><a href="#" onclick='PublishFixtures()'>Publish Fixtures for all <?= $season ?> grades.</a></td>
    <td align="center" nowrap="nowrap" class="greenbg"><a href="../admin_scores/AA_scores_index_grades.php?season=<?= $season ?>">Return to <?= $season ?> page</a></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
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
    <th colspan=<?= ($i) ?> class='text-center'><button type='button' class='btn btn-primary' onclick='Viewtab(10);' style='width:300px'>Analyse Data</button></th>
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
$max_teams = '';
$form_no = 1;
while($build_data_grades = $result_grades->fetch_assoc())
{
    $team_grade = $build_data_grades['team_grade'];
    $sql_club = 'Select * from Team_entries where team_cal_year = ' . $year . ' and team_grade = "' . $build_data_grades['team_grade'] . '" and day_played = "' . $dayplayed . '" and team_season = "' . $season . '" ' . $sortby;
    //echo($sql_club . "<br>");
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
    // get maximum rounds for number of teams in each grade
    $max_teams .= GetRounds($teams) . ", ";

    // add a bye if uneven number of teams.
    if(($teams % 2) == 1)
    {
        $sql_insert_bye = "Insert INTO Team_entries (team_club_id, team_name, team_grade, team_season, day_played, players, Final5, include_draw, audited, team_cal_year, comptype) VALUES (0, 'Bye', '" . $build_data_grades['team_grade'] . "', '" . $season . "', '" . $dayplayed . "', 4, 4, 'Yes', 'No', " . $year . ", '" . $comptype . "')";
        echo($sql_insert_bye . "<br>");
        $update = mysql_query($sql_insert_bye, $connvbsa) or die(mysql_error());
    }

    $fixtures = substr($fixtures, 0, strlen($fixtures)-2);
    $fix_test = json_encode($fixtures);

    $result_club_display = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $num_club_display = $result_club_display->num_rows;
    //echo("Page " . $form_no . "<br>");
    echo("<div id='page" . $form_no . "'>");
    echo('<input type="hidden" name="form_no" id="form_no" value=' . $form_no . '>');
    echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='900px'border='1'>
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
    echo("<tr>"); 
 ?>
    <td colspan="7" align="center">
        <select name="sort_order" id="sort_order" onchange="GetSort(this, <?= $form_no ?>, '<?= $season ?>', <?= $year ?>, '<?= $dayplayed ?>', '<?= $team_grade ?>')">
    <?php    
        if(!isset($_POST['Sortby']))
        {
            echo('<option value="fix_sort" selected>Sort Table By:</option>');
        }
        echo('<option value="fix_sort">Position ID (Descending)</option>
              <option value="team_id_dec">Team ID (Descending)</option>
              <option value="team_id_asc">Team ID (Ascending)</option>
              <option value="team_name_dec">Team Name (Descending)</option>
              <option value="team_name_asc">Team Name (Ascending)</option>
              <option value="rand">Shuffle</option>
            </select>
        </td>');
    echo("</tr>");
    echo("</tbody>");
    echo("</table>");
    $sql_finals = 'Select finals_teams from Team_grade where fix_cal_year = ' . $year . ' and season = "' . $season . '" and grade = "' . $team_grade . '"';
    $result_finals = mysql_query($sql_finals, $connvbsa) or die(mysql_error());
    $build_finals = $result_finals->fetch_assoc();
    $no_of_finals = $build_finals['finals_teams'];
    $total_rounds = (GetRounds($teams)+($no_of_finals/2)); // number of rounds including finals
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
        <td align="left">No of Rounds</td>
        <td align="center" id="no_of_rounds_' . $form_no . '">' . GetRounds($teams) . '</td>
    </tr>
    <tr>
        <td align="left">Rounds (inc Finals)</td>
        <td align="center" id="finals_rounds_' . $form_no . '">' . $total_rounds . '</td>
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
        <td align="center" rowspan=' . ($num_club) .'><textarea cols=10 rows=3>' . $dates . '</textarea></td>
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
    
    echo('</tbody>');
    echo("</table>");
    echo("</td>");
    echo("</tr>");
    echo("</table>");

    $sql_fixtures = "Select fix1home FROM tbl_create_fixtures WHERE season ='$season' AND year = '$year' AND team_grade = '$team_grade' AND dayplayed = '$dayplayed'";
    $result_fixtures = mysql_query($sql_fixtures, $connvbsa) or die(mysql_error());
    $no_of_fixtures = $result_fixtures->num_rows;
    
    $rounds = GetRounds($teams);

    //main($fixtures, $team_grade, $form_no, $year, $season); 
    echo('<br>');
    //PopulateFromDatabase($team_grade, $form_no, $year, $season, $dayplayed, $rounds);
    //CreateDateArray($form_no, $rounds, $team_grade, $year, $season);
    echo('</div>');
    $form_no++;

}
?>
<script type="text/javascript">

$(document).ready(function()
{
    $('.load_teams').click(function()
    {
        var dayplayed =  '<?= $dayplayed?>';
        //var max_no_of_teams =  $('#no_of_teams_' + form_no).html();
        var max_no_of_teams =  18;
        //alert(max_no_of_teams);
        var current_year =  <?= $year ?>;
        var current_season =  '<?= $season ?>';
        var team_grades_before =  '<?= trim($team_grades)  ?>';
        var team_grades = team_grades_before.slice(0, -1);
        //alert(team_grades);
        $.ajax({
          url:"get_team_data.php?dayplayed=" + dayplayed+ "&team_grades=" + team_grades + "&max_teams=" + max_no_of_teams + "&year=" + current_year + "&season=" + current_season,
              success : function(data){
                //obj = jQuery.parseJSON(data);
                console.log(data);
                $($.parseHTML(data)).appendTo('#table_util');
            }
        });
    });
});

</script>

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
        url: 'save_sort_index.php?allData=' + aData + '&season=' + season + '&year=' + year + '&formno=' + form_no,
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

</body>
</html>