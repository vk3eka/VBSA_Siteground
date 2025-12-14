<?php
require_once('../Connections/connvbsa.php'); 
include('../vbsa_online_scores/server_name.php');

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

if (!isset($_SESSION)) {
    session_start();
    if(!isset($_SESSION['refresh_count']))
    {
        $_SESSION['refresh_count'] = 0;
    }
    $_SESSION['refresh_count']++;
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

// get team grades for fixture list
$team_grades = '';
$sql = "Select distinct team_grade, grade_start_date FROM Team_entries Left Join Team_grade on Team_grade.grade = Team_entries.team_grade and Team_grade.fix_cal_year = Team_entries.team_cal_year and Team_grade.season = Team_entries.team_season Where team_season = '" . $season . "' AND team_name <> 'Bye' AND team_cal_year = " . $year . " and dayplayed = '" . $dayplayed . "' ORDER BY team_grade ASC";
//echo($sql . "<br>");
$result_team_grades = mysql_query($sql, $connvbsa) or die(mysql_error());
$total_team_grades = $result_team_grades->num_rows;
while($build_team_grades = $result_team_grades->fetch_assoc())
{
    $grade_start = $build_team_grades['grade_start_date'];
    $grade = $build_team_grades['team_grade'];
    $team_grades .= $grade . ", ";
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

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.14.1/themes/smoothness/jquery-ui.css">

<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcommon.js"></script>
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script src="fixture_gen_functions_js.js"></script>

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

// get team grades from Team entries table for menu
$sql_total_grades = 'Select distinct team_grade, no_of_rounds, type from Team_entries Left Join Team_grade on Team_grade.grade = Team_entries.team_grade and Team_grade.fix_cal_year = Team_entries.team_cal_year and Team_grade.season = Team_entries.team_season where team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" and team_season = "' . $season . '" order by no_of_rounds ASC';
//echo($sql_total_grades . "<br>");
$result_total_grades = mysql_query($sql_total_grades, $connvbsa) or die(mysql_error());
$total_team_grades = $result_total_grades->num_rows;
while($build_total_grades = $result_total_grades->fetch_assoc())
{
    if($build_total_grades['type'] == 'Snooker')
    {
        $max_no_of_rounds  = ($build_total_grades['no_of_rounds']-2);
    }
    else if($build_total_grades['type'] == 'Billiards')
    {
        $max_no_of_rounds  = ($build_total_grades['no_of_rounds']-3);
    }
    //echo($max_no_of_rounds . "<br>");
    $team_grade_with_max_rounds = ($build_total_grades['team_grade']);
}

?>
<script type='text/javascript'>

function FirstLoad() 
{
    document.getElementById("page1").style.display = "block";
    document.getElementById("page2").style.display = "none";
    document.getElementById("page3").style.display = "none";
    <?php 
    if($total_team_grades == 4) 
    {
        echo('document.getElementById("page4").style.display = "none";');
    }
    ?>
    document.getElementById("page10").style.display = "block"; 
}

function Viewtab(sel)
{
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
        document.getElementById("page10").style.display = "block"; 
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
        document.getElementById("page10").style.display = "block"; 
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
        ?>;
        document.getElementById("page10").style.display = "block"; 
        break;

    <?php 
    if($total_team_grades == 4) 
    {
        echo('case 4:');
        echo('document.getElementById("page1").style.display = "none";');
        echo('document.getElementById("page2").style.display = "none";');
        echo('document.getElementById("page3").style.display = "none";');
        echo('document.getElementById("page4").style.display = "block";');
        echo('document.getElementById("page10").style.display = "block";');
        echo('break;');
    }
    ?>
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
        document.getElementById("page10").style.display = "block"; 
        break;
    }
}

window.onload = function() 
{
    FirstLoad();
}
</script>
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
    <td colspan="3" align="center" nowrap="nowrap" class="greenbg"><a href="../admin_scores/AA_scores_index_grades.php?season=<?= $season ?>">Return to <?= $season ?> page</a></td>
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
$sql_grades_menu = 'Select count(team_grade) as count, team_grade from Team_entries where team_cal_year = ' . $year . ' and day_played = "' . $dayplayed . '" and team_season = "' . $season . '" group by team_grade order by Team_grade';
//echo($sql_grades_menu . "<br>");
$result_grades_menu = mysql_query($sql_grades_menu, $connvbsa) or die(mysql_error());
$i = 1;
while($build_data_menu = $result_grades_menu->fetch_assoc())
{
    echo("<th class='text-center'><button type='button' class='btn btn-primary' onclick='Viewtab(" . $i . ");' style='width:120px'>" . $build_data_menu['team_grade'] . "</button></th>");
    $no_of_home_teams = $build_data_menu['count'];
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

require_once('Models/Fixture.php');

$fixture = new Fixture();
//echo($year . "<br>");
$fixture->LoadFixture($year, $season, $dayplayed);

$jsonData = json_encode($fixture);

$data = json_decode($jsonData, true);

//echo($jsonData . "<br>");

function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}
$pages = unique_multidim_array($data['grades'],'name');

foreach($pages as $grade)
{
    $team_grade = $grade['name'];
    $fixtures = '';
    $filtered = array_filter($data['teams'], fn($r) => $r['grade'] === $team_grade);
    foreach($filtered as $fix)
    {
        $comptype = $fix['type'];
    }
    echo("<div id='page" . $form_no . "'>");
    echo("<table class='table table-striped table-bordered dt-responsive nowrap display' border='1'>
             <tr>
                <td align='center' id='" . $team_grade . "_" . $form_no . "'>" . $team_grade . "</td>
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
    
    foreach($filtered as $fix)
    {
        $fixtures .= $fix['name'] . ", ";
        $team_name = $fix['name'];
        $team_id = $fix['id'];
        $club_name = $fix['club'];
        $club_tables = array_filter($data['clubs'], fn($d) => $d['name'] === $club_name);
        foreach($club_tables as $tables)
        {
            $club_tables = $tables['tables'];
            $club_id = $tables['id'];
        }
        echo('
          <tr draggable="true" ondragstart="start()" ondragover="dragover()" data-index=' . $x . ' id=' . $x . '> 
            <td align="center" id="sort_' . $form_no . '_' . $x . '_id">' . $x . '</td>
            <td align="center" id="team_' . $form_no . '_' . $x . '_id">' . $team_id . '</td>
            <input type="hidden" id="sort_form" value=' . $form_no . '>');
        echo('<td align="center" id="club_' . $form_no . '_' . $x . '_id">' . $club_id . '</td>
            <td align="center" id="club_' . $form_no . '_' . $x . '_tables">' . $club_tables . '</td>
            <td align="left" id="club_name_' . $form_no . '_' . $x . '">' . $club_name . '</td>
            <td align="left" id="team_name_' . $form_no . '_' . $x . '">' . $team_name . '</td>');
        echo('<td align="center" id="home_games_' . $form_no . '_' . $x . '">TBA</td>
          </tr>
        ');
        $x++;
    }

    $no_of_teams = ($x-1);
    echo("<tr><td>&nbsp;</td></tr>");
    echo("<tr>"); 
    $start_date = '';
    $no_of_rounds = array_filter($data['rounds'], fn($p) => $p['grade'] === $team_grade);

    //echo("<pre>");
    //echo(var_dump($data['rounds']));
    //echo("</pre>");

    foreach($no_of_rounds as $count)
    {
        $round_count = ($count['number']);
        if($count['number'] === 1)
        {
            $start_date = $count['date'];
        }
    }
    if($comptype == 'Billiards')
    {
        $total_rounds = ($round_count+3);
    }
    else if($comptype == 'Snooker')
    {
        $total_rounds = ($round_count+2);
    }

    $dates = [];
    $dates = '';
    foreach($data['non_dates'] as $date)
    {
        $dates .= $date['date'] . "\n";
    }
    echo("<tr><td colspan=2>&nbsp;</td></tr>");
    echo("<tr><td colspan=2>&nbsp;</td></tr>");
    echo("<tr><td colspan=2>&nbsp;</td></tr>");
    echo("</tbody>");
    echo("</table>");
    echo("</td>");
    echo("<td>");
    echo('<table class="table table-striped table-bordered dt-responsive display text-center">
    <thead>
        <th align="center">Analysis Data</th>
        <th align="center" id="team_grade_' . $form_no . '" >' . $team_grade . '</th>
    </thead>
    <tbody>
    <tr>
        <td align="left">No of Teams</td>
        <td align="center" id="no_of_teams_' . $form_no . '">' . $no_of_teams . '</td>
    </tr>
    <tr>
        <td align="left">No of Rounds</td>
        <td align="center" id="no_of_rounds_' . $form_no . '">' . $round_count . '</td>
    </tr>
    <tr>
        <td align="left">Rounds (inc Finals)</td>
        <td align="center" id="finals_rounds_' . $form_no . '">' . $total_rounds . '</td>
    </tr>
    <tr>
        <td align="left">Start Date</td>
        <td align="center"><input type="text" name="startdate" id="startdate_' . $form_no . '" value=' . $start_date . ' style="width:100px"></td>
    </tr>
    <tr>
        <td align="left">Day Played</td>
        <td align="center" id="dayplayed_' . $form_no . '" >' . $dayplayed . '</td>
    </tr>
    <tr>
        <td align="left" valign="top">Non available dates</td>
        <td align="center" rowspan=' . ($num_club) .'><textarea cols=10 rows=10 id="non_dates">' . $dates . '</textarea></td>
    </tr>'
    );
    echo('</tbody>');
    echo("</table>");
    echo("</td>");
    echo("</tr>");
    echo("</table>");
    echo("<div id='output_" . $form_no . "'></div>");
    echo("</div>");
    $form_no++;
}

?>
<script>

function shuffleArray(array)
{
    const $array = $(array);

    for(let i = array.length-1; i>0; i--)
    {
        const j = Math.floor(Math.random() * (i+1));
        [$array[i], $array[j]] = [$array[j], $array[i]];
    }
    return $array.get();
}

var data = <?php echo json_encode($data['teams']); ?>;
const shuffledArray = shuffleArray(data);

$(document).ready(function()
{

    $.fn.drawAnalysisData = function(grades, teams, rounds, max_no_of_rounds)
    {
        console.log("Algorithym");
        console.log("Teams " + JSON.stringify(teams, null, 2));
        // anaylsis data page
        $('#algorithym').empty();
        //var grades = <?php echo json_encode($data['grades']); ?>;
        //var teams = <?php echo json_encode($data['teams']); ?>;
        var rounds = <?php echo json_encode($data['rounds']); ?>;
        var home_team_count = [];
        var team_count;
        $.each(grades, function(_, r) {
            name = r.name;
            //name = grades;
            let teamData = teams.filter(d => d.grade === name);
            team_count = 0;
            $.each(teamData, function(_, d) {
                    team_count++;
            });
            home_team_count.push({'Count': team_count, 'name': name});
        });
        //console.log("Home Team Count " + JSON.stringify(home_team_count, null, 2));
        //var max_no_of_rounds = <?= $max_no_of_rounds ?>;
        //console.log("Max No. " + max_no_of_rounds);
        var no_of_home_teams;
        var output = '';
        output += ("<div id='page10'>");
        //output += ('<table class="table table-striped table-bordered dt-responsive display text-center" border="1" style="display: none">');
        output += ('<table class="table table-striped table-bordered dt-responsive display text-center" border="1">');
        output += ('<tr>');
        output += ('<td colspan=' + (max_no_of_rounds+4) + ' align="center"><b>Analysis Data (<?= $dayplayed ?>)</td>');
        output += ('</tr>');
        output += ('<tr>');
        output += ('<td rowspan=3  colspan=4 align="center">Grade</td>');
        output += ('<td colspan=' + max_no_of_rounds + ' align="center">Round/Date</td>');
        output += ('</tr>');
        output += ('<tr>');
        for(i = 0; i < max_no_of_rounds; i++)
        {
            output += ("<td align='center' id='round'>" + (i+1) + "</td>");
        }
        output += ('</tr>');
        output += ('<tr>');
        // get max rounds
        let maxPerGrade = {};
        $.each(grades, function(_, r) {
            grade = r.name;
            let gradeRounds = rounds.filter(r => r.grade === grade);
            let maxRound = Math.max(...gradeRounds.map(r => r.number));
            maxPerGrade[grade] = maxRound;
        });
        let overallMax = Math.max(...Object.values(maxPerGrade));
        //console.log("Max (Anal) " + overallMax);
        i = 0;
        let max_grade = rounds.filter(d => d.grade === '<?= $team_grade_with_max_rounds ?>');
        //console.log("Rounds " + JSON.stringify(rounds, null, 2));
        //console.log("Max Grade " + JSON.stringify(max_grade, null, 2));
        $.each(max_grade, function(_, r) {
            date = r.date;
            if(i <= (overallMax-1))
            {
                output += ("<td align='center' id='date_" + i + "'>" + date + "</td>");
                //console.log("Max " + i);
                i++;
            }
        });
        output += ('</tr>');
        i = 0;
        $.each(grades, function(_, r) {
            grade = r.name;
            //console.log("Grade from grades " + JSON.stringify(grades, null, 2));
            let home_count = home_team_count.filter(d => d.grade === grade);
            //console.log("Home Count " + JSON.stringify(home_team_count, null, 2));
            count = r.count;
            no_of_home_teams = (count/2);
            //var no_of_rounds = overallMax;
            var no_of_rounds = max_no_of_rounds;
            for(y = 0; y < no_of_home_teams; y++) // home teams in team grade
            {   
                output += ("<tr>");
                output += ('<td colspan=4 align="center" id="' + grade + '_fix_' + (y+1) + '"></td>');
                for(j = 0; j < no_of_rounds; j++)
                {
                    if((grade + '_fix_' + (j+1) + '_pos_' + (y+1)) != '')
                    {
                        output += ('<td align="center" class="round_' + j + '" id="' + grade + '_fix_' + (j+1) + '_pos_' + (y+1) + '">&nbsp;</td>');
                    }
                    else
                    {
                        output += ('<td align="center" class="round_' + j + '" id="' + grade + '_fix_' + (j+1) + '_pos_' + (y+1) + '">' + grade + '_fix_' + (j+1) + '_pos_' + (y+1) + '</td>');
                    }
                }
                output += ("</tr>");
                i++;
            }
            output += ("<tr>");
            output += ("<td class='text-center' colspan=" + (max_no_of_rounds+4) + ">&nbsp;</td>");
            output += ("</tr>");
        });
        output += ('</table>');
        $('#algorithym').append(output);
    }
/*
    var grades = <?php echo json_encode($data['grades']); ?>;
    var clubs = <?php echo json_encode($data['clubs']); ?>;
    var teams = <?php echo json_encode($data['teams']); ?>;
    var rounds = <?php echo json_encode($data['rounds']); ?>;
    var max_no_of_rounds = <?= $max_no_of_rounds ?>;
    $.fn.drawAnalysisData(grades, teams, rounds, max_no_of_rounds); // initial display
*/


    $.fn.drawTableUtilisation = function(all_home_teams, clubs, teams, rounds)
    {
        console.log("Table Util");
        // table utilisation data page
        $('#table_util').empty();
        var grades = <?php echo json_encode($data['grades']); ?>;
        var clubs = <?php echo json_encode($data['clubs']); ?>;
        var teams = <?php echo json_encode($data['teams']); ?>;
        var rounds = <?php echo json_encode($data['rounds']); ?>;
        var holidays = <?php echo json_encode($data['holidays']); ?>;

        let data = JSON.stringify(all_home_teams, null, 2);
        let parsedData = JSON.parse(data);
        let teamList = parsedData.map(d => ({
            home: d.home_team.team,
            away: d.away_team.team,
            club: d.home_team.club,
            round: d.home_team.round,
        }));
        //console.log("Rounds  " + JSON.stringify(rounds, null, 2));
        style = 'style=background-color:red; color:white;'; // temp
        var util = '';
        util += ('<table class="table table-striped table-bordered dt-responsive display text-center" border="1">');
        util += ('<tr>');
        util += ('<td colspan=' + (max_no_of_rounds+4) + ' align="center"><b>Table Utilisation (<?= $dayplayed ?>)</td>');
        util += ('</tr>');
        util += ('<tr>');
        util += ('    <td rowspan=3 align="center">Grade</td>');
        util += ('    <td rowspan=3 align="center">Club</td>');
        util += ('    <td rowspan=3 align="center">Team</td>');
        util += ('    <td rowspan=3 align="center">Tables</td>');
        util += ('    <td colspan=' + max_no_of_rounds + ' align="center">Round/Date</td>');
        util += ('</tr>');
        util += ('<tr>');
        for(i = 0; i < max_no_of_rounds; i++)
        {
            util += ("<td align='center'>" + (i+1) + "</td>");
        }
        util += ('</tr>');
        util += ('<tr>');
        i = 0;

        // get max rounds
        let maxPerGrade = {};
        $.each(grades, function(_, r) {
            grade = r.name;
            let gradeRounds = rounds.filter(r => r.grade === grade);
            let maxRound = Math.max(...gradeRounds.map(r => r.number));
            maxPerGrade[grade] = maxRound;
        });
        let overallMax = Math.max(...Object.values(maxPerGrade));


        let max_grade = rounds.filter(d => d.grade === '<?= $team_grade_with_max_rounds ?>');
        $.each(max_grade, function(_, r) {
            date = r.date;
            if(i <= overallMax)
            {
                util += ("<td align='center' id='table_date_" + (i) + "'>" + date + "</td>");
                i++;
            }
        });
        util += ('</tr>');
        //console.log("Clubs " + JSON.stringify(clubs, null, 2));
        let clubs_sorted = clubs.sort((a, b) => a.name.localeCompare(b.name));
        $.each(clubs_sorted, function(_, r) {
            name = r.name;
            id = r.id;
            tables = r.tables;
            let style = '';
            let club_count = 0;
            let teamData2 = teams
                .filter(d => d.club === r.name)
                .filter(d => d.name !== 'Bye') // added
                .sort((a, b) => a.name.localeCompare(b.name));  // sort by name
            $.each(teamData2, function(_, g) {
                //console.log("Team Data " + JSON.stringify(teamData2, null, 2));
                grade = g.grade;
                name = g.name;
                club = g.club;
                util += ("<tr>");
                util += ('<td align="center">' + grade + '</td>');
                util += ('<td align="center">&nbsp;</td>');
                util += ('<td align="center">' + name + '</td>');
                util += ('<td align="center">&nbsp;</td>');
                for(var i = 0; i < max_no_of_rounds; i++)
                {
                    let style = '';
                    let count = 0;
                    let roundList = teamList
                        .filter(d => d.round === (i+1) && d.home === name)
                        .sort((a, b) => a.home.localeCompare(b.home))  // sort by name;
                    $.each(roundList, function(_, h) {
                        if((h.home == 'Bye') || (h.away == 'Bye'))
                        {
                             count = 'Bye';  // added
                        }
                        else
                        {
                            count = 1;
                        }
                        
                        team_match = g.name;
                    });
                    if(count !== 'Bye')  // added
                    {
                        count = (count*2);
                    }
                    util += ('<td align="center" >' + (count) + '</td>');
                }
                util += ("</tr>");
            });
            let clubData = teams
                .sort((a, b) => a.name.localeCompare(b.name));  // sort by name
            let teamData1 = teams.filter(d => d.club === r.name && d.club != 'Bye').slice(0, 1) // get distinct club
            //console.log("Team List " + JSON.stringify(teamList, null, 2));
            $.each(teamData1, function(_, g) {
                util += ("<tr>");
                util += ('<td align="center">&nbsp;</td>');
                util += ('<td align="center"><b>' + club + '</b></td>');
                util += ('<td align="center">&nbsp;</td>');
                util += ('<td align="center"><b>' + tables + '</b></td>');
                for(var i = 0; i < max_no_of_rounds; i++)
                {
                    let style = '';
                    let roundList = teamList.filter(d => 
                        d.round === (i+1) &&
                        d.away != 'Bye' &&
                        d.club === club);
                    let club_count = roundList.length;
                    if(tables < (club_count*2))
                    {
                        style = 'style=background-color:red; color:white;';
                        clashes++;
                    }
                    else
                    {
                        style = '';
                    }
                    util += ('<td align="center" ' + style + '><b>' + (club_count*2) + '</b></td>'); // total of club column

                }
                util += ("</tr>");
            });
        });
        util += ("<td colspan=" + (max_no_of_rounds+4) + ">&nbsp;</td>");
        util += ("</tr>");
        util += ("</table>"); 
        util += ("<br>");
        document.getElementById('no_of_clashes').value = clashes;
        $('#table_util').append(util);
    }

/**
  * Calculates the number of venue clashes for a given set of fixtures.
  * (This function is the same as before but is included for completeness).
  * @param {Array} fixtures - The array of all games.
  * @param {Array} clubs - The array of club data.
  * @returns {Number} The total number of clashes.
  */
 function calculateClashes(fixtures, clubs) {
     let totalClashes = 0;
     const rounds = [...new Set(fixtures.map(f => f.home_team.round))];

     for (const round of rounds) {
         const gamesInRound = fixtures.filter(f => f.home_team.round === round && f.away_team.team !== 'Bye');
         let clubHomeCounts = {};
         for (const game of gamesInRound) {
             if (!clubHomeCounts[game.home_team.club]) {
                 clubHomeCounts[game.home_team.club] = 0;
             }
             clubHomeCounts[game.home_team.club]++;
         }

         for (const clubName in clubHomeCounts) {
             const clubInfo = clubs.find(c => c.name === clubName);
             const homeGames = clubHomeCounts[clubName];
             const tablesNeeded = homeGames * 2;

             if (clubInfo && tablesNeeded > clubInfo.tables) {
                 totalClashes++;
             }
         }
     }
     return totalClashes;
 }

 /**
  * Calculates the penalty score for home/away game imbalance.
  * A perfect balance (e.g., 7 home, 7 away) has a score of 0.
  * A near balance (e.g., 7 home, 6 away) also has a score of 0.
  * An imbalance (e.g., 8 home, 5 away) will have a penalty.
  * @param {Array} fixtures - The array of all games.
  * @param {Array} teams - The array of all team data.
  * @returns {Number} The total imbalance penalty.
  */
 function calculateHomeGameImbalance(fixtures, teams) {
     let totalImbalance = 0;
     const teamStats = {};

     // Initialize stats for all non-bye teams
     for (const team of teams) {
         if (team.name !== 'Bye') {
             teamStats[team.name] = { home: 0, away: 0 };
         }
     }

     // Count home and away games
     for (const game of fixtures) {
         if (game.home_team.team && teamStats[game.home_team.team]) {
             teamStats[game.home_team.team].home++;
         }
         if (game.away_team.team && teamStats[game.away_team.team]) {
             teamStats[game.away_team.team].away++;
         }
     }

     // Calculate the penalty for each team
     for (const teamName in teamStats) {
         const stats = teamStats[teamName];
         const imbalance = Math.abs(stats.home - stats.away);
         // A difference of 0 or 1 is acceptable. Anything more is a penalty.
         if (imbalance > 1) {
             totalImbalance += (imbalance - 1);
         }
     }
     return totalImbalance;
 }

 /**
  * Calculates a total penalty score for a fixture list.
  * Prioritizes fixing clashes, then home/away balance.
  * @param {Array} fixtures - The array of all games.
  * @param {Array} clubs - The array of club data.
  * @param {Array} teams - The array of team data.
  * @returns {Number} The total penalty score.
  */
 function calculatePenaltyScore(fixtures, clubs, teams) {
     const clashPenaltyWeight = 100;   // High cost for a venue clash
     const imbalancePenaltyWeight = 1; // Lower cost for home/away imbalance

     const clashCount = calculateClashes(fixtures, clubs);
     const imbalanceCount = calculateHomeGameImbalance(fixtures, teams);

     return (clashCount * clashPenaltyWeight) + (imbalanceCount * imbalancePenaltyWeight);
 }

/**
  * Attempts to resolve fixture clashes and home/away imbalance using an iterative algorithm.
  * @param {Array} initialFixtures - The initial array of all games.
  * @param {Array} teams - The array of all team data.
  * @param {Array} clubs - The array of all club data.
  * @param {Number} maxAttempts - The maximum number of swaps to try.
  * @returns {Array} The optimized fixtures.
*/
function resolveFixtures(initialFixtures, teams, clubs, maxAttempts = 2000) {
     let fixtures = JSON.parse(JSON.stringify(initialFixtures));
     let attempts = 0;

     let bestFixtures = JSON.parse(JSON.stringify(fixtures));
     let bestPenalty = calculatePenaltyScore(fixtures, clubs, teams);

     while (attempts < maxAttempts) {
         let currentPenalty = calculatePenaltyScore(fixtures, clubs, teams);

         if (currentPenalty < bestPenalty) {
             bestPenalty = currentPenalty;
             bestFixtures = JSON.parse(JSON.stringify(fixtures));
         }

         if (currentPenalty === 0) {
             console.log(`Optimal solution found after ${attempts} attempts.`);
             return fixtures;
         }

         let improvementFound = false;

         // Iterate through every game and try to flip its home/away status
         for (let i = 0; i < fixtures.length; i++) {
             let testFixtures = JSON.parse(JSON.stringify(fixtures));
             let gameToFlip = testFixtures[i];

             if (gameToFlip.away_team.team === 'Bye') continue;

             // --- Perform Swap ---
             const newHomeTeamInfo = teams.find(t => t.name === gameToFlip.away_team.team);
             if (!newHomeTeamInfo) continue;

             [gameToFlip.home_team, gameToFlip.away_team] = [gameToFlip.away_team, gameToFlip.home_team];
             gameToFlip.home_team.club = newHomeTeamInfo.club;

             // --- Evaluate Swap ---
             const newPenalty = calculatePenaltyScore(testFixtures, clubs, teams);

             if (newPenalty < currentPenalty) {
                 fixtures = testFixtures; // Keep the improvement
                 improvementFound = true;
                 break;
             }
         }

         attempts++;

         if (!improvementFound) {
             console.log(`No further improvement found. Stopping with best score: ${bestPenalty}.`);
             return bestFixtures;
         }
     }

     console.log(`Max attempts reached. Returning best result with score: ${bestPenalty}.`);
     return bestFixtures;
 }


/**
  * Attempts to resolve fixture clashes using an iterative improvement algorithm.
  * @param {Array} initialFixtures - The initial array of all games (all_home_teams).
  * @param {Array} teams - The array of all team data.
  * @param {Array} clubs - The array of all club data.
  * @param {Number} maxAttempts - The maximum number of swaps to try before giving up.
  * @returns {Array} The resolved fixtures, hopefully with zero clashes.
  */
 /*
 function resolveClashes(initialFixtures, teams, clubs, maxAttempts = 500) {
     let fixtures = JSON.parse(JSON.stringify(initialFixtures)); // Deep copy to avoid modifying the original
     let attempts = 0;

     while (attempts < maxAttempts) {
         let currentClashes = calculateClashes(fixtures, clubs);
         if (currentClashes === 0) {
             console.log(`Solution found after ${attempts} attempts.`);
             return fixtures; // Success!
         }

         let improvementFound = false;

         // Find a game involved in a clash
         for (let i = 0; i < fixtures.length; i++) {
             const gameToMove = fixtures[i];

             // Skip byes or games that aren't part of a clash
             const homeClub = clubs.find(c => c.name === gameToMove.home_team.club);
             if (gameToMove.away_team.team === 'Bye' || !homeClub) continue;

             const gamesInRoundAtClub = fixtures.filter(f =>
                 f.home_team.round === gameToMove.home_team.round &&
                 f.home_team.club === gameToMove.home_team.club
             ).length;

             if (gamesInRoundAtClub * 2 <= homeClub.tables) {
                 continue; // This specific game is not in a clashing club for this round
             }

             // Now, try to find a game to swap with
             for (let j = 0; j < fixtures.length; j++) {
                 if (i === j) continue;

                 const potentialSwap = fixtures[j];

                 // We are looking for a simple home/away flip.
                 // The swap is valid if:
                 // 1. It's in the same round and grade.
                 // 2. The other team ('potentialSwap') is playing at home.
                 // 3. The other team's opponent has a club that can host the game.
                 if (gameToMove.home_team.round === potentialSwap.home_team.round &&
                     teams.find(t => t.name === gameToMove.home_team.team)?.grade === teams.find(t => t.name === potentialSwap.home_team.team)?.grade) {

                     // Let's try swapping home/away status for 'gameToMove'
                     let testFixtures = JSON.parse(JSON.stringify(fixtures));

                     // The game to modify in our test copy
                     let gameToModify = testFixtures[i];

                     // Get the club of the away team, which will become the new home team
                     const newHomeTeamName = gameToModify.away_team.team;
                     const newHomeTeamInfo = teams.find(t => t.name === newHomeTeamName);
                     if (!newHomeTeamInfo) continue; // Skip if away team is a 'Bye' or not found

                     // Perform the swap
                     let originalHomeTeam = gameToModify.home_team;
                     gameToModify.home_team = gameToModify.away_team;
                     gameToModify.away_team = originalHomeTeam;

                     // Update the club for the new home team
                     gameToModify.home_team.club = newHomeTeamInfo.club;

                     // Check if this swap made things better
                     let newClashes = calculateClashes(testFixtures, clubs);

                     if (newClashes < currentClashes) {
                         fixtures = testFixtures; // Keep the improvement
                         improvementFound = true;
                         break; // Exit the inner loop and restart the clash search
                     }
                 }
             } // End of inner loop (j)

             if (improvementFound) {
                 break; // Exit the outer loop (i) to restart the whole process
             }
         } // End of outer loop (i)

         attempts++;

         if (!improvementFound) {
             // If a full pass resulted in no improvements, we are stuck
             console.log(`No improvement found. Stopping with ${currentClashes} clashes.`);
             return fixtures;
         }
     }

     console.log(`Max attempts reached. Returning best result with ${calculateClashes(fixtures, clubs)} clashes.`);
     return fixtures;
 }
 */
    let shouldStop = false;
    let clashes = 0;
    let recursions = 0;

    $('.stopbutton').on('click', function() {
        shouldStop = true;
    });

    $('.shufflebutton').click(function()
    {
        /*
        shouldStop = false; // reset stop flag
        if($('#target_clashes').val() < 1)
        {
            alert("No Target Entered!");
            return;
        }
    */
        $.fn.shuffle_teams();
    });

    $.fn.shuffle_teams = function(recursions = 0)
    {
        $('#recursions').val(recursions);
        if (shouldStop) {
            return;
        }
        var index = 1; // no of grades
        var grades = <?php echo json_encode($data['grades']); ?>;
        var teams = <?php echo json_encode($data['teams']); ?>;
        var clubs = <?php echo json_encode($data['clubs']); ?>;
        var rounds = <?php echo json_encode($data['rounds']); ?>;
        var non_dates = <?php echo json_encode($data['non_dates']); ?>;
        var dayPlayed;
        var clubTables = 0;
        var clubID = 0;
        var form_no = 1;
        var home_teams = [];
        var all_home_teams = [];
        var startdate = '';
        //console.log("Clubs " + JSON.stringify(clubs, null, 2));
        clashes = 0; // reset clashes to zero

        $.each(grades, function(_, r) {
            name = r.name;
            teamGrade = name;
            let teamData = teams.filter(d => d.grade === name);
            //console.log("Team Data " + JSON.stringify(teamData, null, 2));
            var team_no = 1;
            var shuffledArray = shuffleArray(teamData);
            //var shuffledArray = teamData;
            //console.log("Shuffled " + JSON.stringify(shuffledArray, null, 2));
            var team_count = 1;
            var teamArray = '';
            $.each(shuffledArray, function(_, d) {
                dayPlayed = d.dayplayed.trim();
                teamName = d.name.trim();
                teamClub = d.club.trim();
                teamID = d.id;
                type = d.type.trim();
                //console.log("Shuffle Array " + JSON.stringify(shuffledArray, null, 2));
                $('#club_name_' + form_no + '_' + team_no).html(teamClub);
                $('#team_name_' + form_no + '_' + team_no).html(teamName);
                $('#team_' + form_no + '_' + team_no + '_id').html(teamID);
                teamArray += teamName + ", ";
                clubData = clubs.filter(g => g.name === teamClub);
                $.each(clubData, function(_, g) {
                    clubTables = g.tables;
                    clubID = g.id;
                    team_count++;
                });
                roundData = rounds.filter(g => g.grade === teamGrade);
                $.each(roundData, function(_, g) {
                    //console.log("Date " + g.date + ", number " + g.number);
                    if(g.number == 1)
                    {
                        startdate = g.date;
                    }
                    no_of_rounds = (g.number);
                });
                //console.log("Finals " + finals_rounds + ", grade " + teamGrade);
                $('#club_' + form_no + '_' + team_no + '_tables').html(clubTables);
                $('#club_' + form_no + '_' + team_no + '_id').html(clubID);
                if(type == 'Snooker')
                {
                    finals_rounds = (no_of_rounds+2);
                }
                else if(type == 'Billiards')
                {
                    finals_rounds = (no_of_rounds+3);
                }
                $('#finals_rounds_' + form_no).html(finals_rounds);
                $('#no_of_rounds_' + form_no).html(no_of_rounds);
                $('#startdate_' + form_no).val(startdate);
                $('#dayplayed_' + form_no).html(dayPlayed);
                team_no++;
            });
            const dateList = non_dates.map(item => item.date).join('\n');
            $('#non_dates').val(dateList);
            $('#output_' + form_no).empty();
            home_teams = [];
            console.log("Team Array " + JSON.stringify(teamArray, null, 2));
            home_teams[teamGrade] = main(teamArray, teamGrade, form_no, <?= $year ?>, '<?= $season ?>', startdate); 

            // add club name to home_teams object
            $.each(home_teams[teamGrade], function(_, match) {
                let homeTeamName = match.home_team.team;
                let awayTeamName = match.away_team.team;
                // Find matching team in Club List
                let homeclubMatch = teams.find(t => t.name === homeTeamName);
                if (homeclubMatch) {
                    // Add the 'club' field to the home_team object
                    match.home_team.club = homeclubMatch.club;
                }
                let awayclubMatch = teams.find(t => t.name === awayTeamName);
                if (awayclubMatch) {
                    // Add the 'club' field to the away_team object
                    match.away_team.club = awayclubMatch.club;
                }
            });
            
            if (home_teams[teamGrade]) {
                all_home_teams = all_home_teams.concat(home_teams[teamGrade]);
            }



            // 1. Calculate the initial penalty score
            let initialPenalty = calculatePenaltyScore(all_home_teams, clubs, teams);
            console.log(`Initial generation has a penalty score of: ${initialPenalty}`);

            // 2. If the score isn't zero, try to resolve the issues
            if (initialPenalty > 0) {
             console.log("Attempting to optimize fixtures...");
             all_home_teams = resolveFixtures(all_home_teams, teams, clubs);
            }

            // 3. Update UI with the final, optimized fixture list
            console.log("Redrawing tables with final fixture data.");

            // (Call your table-drawing functions here using the final 'all_home_teams')
            $.fn.drawTableUtilisation(all_home_teams, clubs, teams, rounds);
            //var teams = <?php echo json_encode($data['teams']); ?>;
            //var rounds = <?php echo json_encode($data['rounds']); ?>;
            //var max_no_of_rounds = <?= $max_no_of_rounds ?>;
            //$.fn.drawAnalysisData(grade, teams, rounds, max_no_of_rounds);

            // drawTableUtilisation(all_home_teams, ...);
            // drawAnalysisData(all_home_teams, ...);

            // Update the final clash and penalty counts on the UI
            let finalClashes = calculateClashes(all_home_teams, clubs);
            let finalImbalance = calculateHomeGameImbalance(all_home_teams, teams);
            let finalPenalty = calculatePenaltyScore(all_home_teams, clubs, teams);

            $('#no_of_clashes').val(finalClashes);
            // You may want to add a new field to display the imbalance score
            // $('#home_away_imbalance').val(finalImbalance);

            console.log(`Final result -> Clashes: ${finalClashes}, Imbalance: ${finalImbalance}, Total Score: ${finalPenalty}`);

            if (finalPenalty === 0) {
             $('#save_button').prop('disabled', false);
             console.log("Successfully generated an optimal fixture list.");
            } else {
             console.log("Could not find a perfect solution, but this is the best result.");
            }


        //});
            //console.log("Home Teams Array  " + JSON.stringify(all_home_teams, null, 2));
            // get home team count
            let teamCounts = {};
            $.each(all_home_teams, function(_, game) {
                let home_game = game.home_team.team;
                let away_game = game.away_team.team;
                if(away_game != 'Bye')
                {
                    if (!teamCounts[home_game]) {
                        teamCounts[home_game] = 0;
                    }
                    teamCounts[home_game]++;
                }
                else if(away_game == 'Bye')
                {
                    teamCounts[home_game] = 0;
                }
            });
            team_no = 1;
            for (let home_game in teamCounts) {
                $('#home_games_' + form_no + '_' + team_no).html(teamCounts[home_game]);
                team_no++;
            }
            index++;
            form_no++;
        });
    } // end of shufflle button action

    var grades = <?php echo json_encode($data['grades']); ?>;
    var clubs = <?php echo json_encode($data['clubs']); ?>;
    var teams = <?php echo json_encode($data['teams']); ?>;
    var rounds = <?php echo json_encode($data['rounds']); ?>;
    var max_no_of_rounds = <?= $max_no_of_rounds ?>;
    $.fn.drawAnalysisData(grades, teams, rounds, max_no_of_rounds); // same as initial display
    

    $.fn.save_fixtures = function (form_no, action) {
        var team_grade_before = $('#team_grade_' + form_no).html();
        var team_grade = $.escapeSelector(team_grade_before);
        var no_of_teams = $('#no_of_teams_' + form_no).html();
        var no_of_fixtures = (no_of_teams/2);
        var no_of_rounds = $('#no_of_rounds_' + form_no).html(); // rounds, no finals
        var dayplayed = $("#dayplayed_" + form_no).html();
        var comptype = $("#comptype_" + form_no).html();
        var grade = team_grade.substring(0,1);
        var scoredata = new Array;
        var scoredata_teams = new Array;
        var sortdata = new Array;
        var sortdata_index = new Array;
        var season = '<?= $season ?>';
        var year = '<?= $year?>';
        var round;
        //alert(no_of_rounds + ", " + team_grade);
        for(x = 0; x < no_of_teams; x++)
        {
            sortdata_index[x] = $("#club_" + form_no + "_" + (x+1) + "_id").html() + ", " + $("#sort_" + form_no + "_" + (x+1) + "_id").html(); 
            //console.log("Index " + sortdata_index[x]);
            sortdata.push(sortdata_index[x]);
        }
        sortdata = JSON.stringify(sortdata);
        for(i = 0; i < no_of_rounds; i++)
        {
            playing_date = $("#A_" + form_no + "_date_" + i).val();
            round = (i+1);
            //console.log(no_of_teams/2);
            for(j = 0; j < (no_of_teams/2); j++) 
            {
                scoredata_teams[i+j] = $("#A_" + team_grade + "_home_" + round + "_" + (j+1)).val() + ", " + $("#A_" + team_grade + "_away_" + round + "_" + (j+1)).val() + ", " + playing_date + ", " + round; 
                scoredata.push(scoredata_teams[i+j]);
            }
        }
        scoredata = JSON.stringify(scoredata);
        //console.log("Scoredata " + scoredata);
        $.ajax({
            url:"save_calculated_fixtures.php?Type=" + comptype + "&Grade=" + grade + "&TeamGrade=" + team_grade + "&FormNo=" + form_no + "&DayPlayed=" + dayplayed + "&Year=" + year + "&Season=" + season + "&SortData=" + sortdata + "&ScoreData=" + scoredata + "&Fixtures=" + no_of_fixtures + "&Rounds=" + no_of_rounds,
            method: 'GET',
            success : function(response)
            {
                if(action == 'Response')
                {
                    //alert(response);
                }
            },
            error: function (request, error) 
            {
              //caption = "No data saved!";
            }
        });  
    }

    $('.savebutton').click(function()
    {
        event.preventDefault();
        var grades = <?php echo json_encode($data['grades']); ?>;
        var form_no = 0;
        var season = '<?= $season ?>';
        var year = '<?= $year?>';
        var dayplayed = '<?= $dayplayed?>';
        var response_alert;
        // need to loop thgrough all grades/forms
        $.each(grades, function(_, r) {
            grade = r.name;
            form_no++;
            response = $.fn.save_fixtures(form_no, 'Response');
            if(response == 'Saved')
            {
                response_alert = response;
            }
        });
    });

    $("#edit_button").on('click', function(event) {
        event.preventDefault();
        window.location = "../Admin_Fixtures/generate_fixtures.php?DayPlayed=<?= $dayplayed?>&season=<?php echo $season; ?>";
    });
});

</script>
<br>
<div id='algorithym'></div>
<br>
<div id='table_util'></div>
<table class='table table-striped table-bordered dt-responsive display text-center'>
<tr>
    <td colspan=40 align='center'>Current Clashes&nbsp;&nbsp;<input type='text' id='no_of_clashes' value='<?= $clashes ?>' style='width:50px'></td>
</tr>
<tr>
    <td colspan=40 align='left'><button type='button' class='btn btn-primary shufflebutton' style='width:400px'>Analyse Data</button></td>
<tr>
    <td colspan=40 align='center'>&nbsp;</td>
</tr>
<tr>
    <td colspan=40 align='center'><button type='button' id='save_button' class='btn btn-primary savebutton'  style='width:400px'>Save Current Fixtures</button></td>
</tr>
<tr>
    <td colspan=40 align='center'>&nbsp;</td>
</tr>
<tr>
    <td colspan=40 align='center'><button type='button' id='edit_button' class='btn btn-primary editbutton' style='width:400px'>Edit/Review Fixtures for <?php echo $season; ?></button></td>
</tr>
</table>
<br><br> 
</body>
</html>


