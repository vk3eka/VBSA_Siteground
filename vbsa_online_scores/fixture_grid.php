<?php 

include('header.php'); 
include('connection.inc'); 
include('php_functions.php'); 

?>
<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcommon.js"></script>
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.js"></script>

<?php 

function CreateDateArray($form_no, $teams, $team_grade) 
{
    for($y = 0; $y < (($teams*2)-2); $y++)
    {
        global $dbcnx_client;
        $sql_playing_dates = 'Select * from tbl_create_fixtures where year = 2024 and team_grade = "' . $team_grade . '" and season = "S1" and round = ' . ($y+1);
        $result_playing_dates = $dbcnx_client->query($sql_playing_dates);
        echo("<script type='text/javascript'>");
        while($row = $result_playing_dates->fetch_assoc()) 
        {
            $row_date = substr($row['date'], 0, 10);
            echo("document.getElementById('" . $form_no . "_date_" . ($y) . "').value = '" . $row_date . "';");
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

  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate_4");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");

  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate_5");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");

  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate_6");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");
}

function FirstLoad() {
  document.getElementById("form1").style.display = "block";
  document.getElementById("form2").style.display = "none";
  document.getElementById("form3").style.display = "none";
  document.getElementById("form4").style.display = "none";
  document.getElementById("form5").style.display = "none";
  document.getElementById("form6").style.display = "none"; 
  document.getElementById("form10").style.display = "none"; 
  document.getElementById("form11").style.display = "none"; 
}

function Viewtab(sel){
  switch (sel) {
    case 1:
        document.getElementById("form1").style.display = "block";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        document.getElementById("form10").style.display = "none"; 
        document.getElementById("form11").style.display = "none"; 
        break;
    case 2:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "block";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        document.getElementById("form10").style.display = "none"; 
        document.getElementById("form11").style.display = "none"; 
        break;
    case 3:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "block";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        document.getElementById("form10").style.display = "none"; 
        document.getElementById("form11").style.display = "none"; 
        break;
    case 4:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "block";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        document.getElementById("form10").style.display = "none";
        document.getElementById("form11").style.display = "none";  
        break;
    case 5:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "block";
        document.getElementById("form6").style.display = "none";
        document.getElementById("form10").style.display = "none";
        document.getElementById("form11").style.display = "none";  
        break;
    case 6:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "block";
        document.getElementById("form10").style.display = "none";
        document.getElementById("form11").style.display = "none";  
        break;
    case 10:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        document.getElementById("form10").style.display = "block"; 
        document.getElementById("form11").style.display = "none"; 
        break;
    case 11:
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "none";
        document.getElementById("form3").style.display = "none";
        document.getElementById("form4").style.display = "none";
        document.getElementById("form5").style.display = "none";
        document.getElementById("form6").style.display = "none";
        document.getElementById("form10").style.display = "none"; 
        document.getElementById("form11").style.display = "block"; 
        break;
    }
}

window.onload = function() 
{
    doOnLoad();
    FirstLoad();
    GetAnalyisData();
    /*
    for(j = 0; j < 18; j++)
    {
        GetMatchesCount(j);
    }
    */
}

function TestRound()
{
    var test = "Test";
    //console.log(test);
    return test;
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
            document.getElementById(team_grade + '_round_' + round + '_pos_' + (j+1)).innerHTML = document.getElementById(team_grade + "_home_" + round + "_" + (j+1)).innerHTML;
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
            document.getElementById(team_grade + '_round_' + round + '_pos_' + (j+1)).innerHTML = document.getElementById(team_grade + "_home_" + round + "_" + (j+1)).innerHTML;
        }
    }

    team_grade = 'BPS';
    no_of_bps_rounds = 14;
    no_of_bps_teams = 4;
    form_number = 2;
    for(i = 0; i < no_of_bps_rounds; i++)
    {
        document.getElementById('date_' + i).innerHTML = document.getElementById(form_number + "_date_" + i).value;
        round = (i+1);

        for(j = 0; j < (no_of_bps_teams); j++) 
        {
            document.getElementById(team_grade + '_round_' + (j+1)).innerHTML = team_grade;
            document.getElementById(team_grade + '_round_' + round + '_pos_' + (j+1)).innerHTML = document.getElementById(team_grade + "_home_" + round + "_" + (j+1)).innerHTML;
        }
    }
}

function GetMatchesCount(round)
{
    var arrCount = {};
    var numOfTrue = 0;
    var x = document.getElementsByClassName('round_' + round);
    for (var i = 0; i < x.length; i++)
    {
        arrCount[i] = x[i].innerHTML;
    }
    console.log((arrCount));
    
    for (var y = 0; y < arrCount.length; y++) {
        if (arrCount[y] === "Yarraville Shufflers") 
        { //increment if true
          numOfTrue++; 
        }
    }
    
    //document.getElementById('round_count_' + j + '_' + y).innerHTML = arrCount
    console.log(arrCount.length);
}

</script>
<?php

function GetClubID($team_name, $team_grade, $year, $season)
{
    global $dbcnx_client;
    // get club id for home and away team names
    $sql_club = "Select team_club_id, team_name FROM Team_entries where team_grade = '" . $team_grade . "' and team_cal_year = " . $year . " and team_season = '" . $season . "' and team_name = '" . $team_name . "';";
    $result_club = $dbcnx_client->query($sql_club) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
    $build_club = $result_club->fetch_assoc();
    $club_id = $build_club['team_club_id'];
    return $club_id;
}

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
            $home_club_id = GetClubID($scoresheet[0], $team_grade, $current_year, $season);
            $away_club_id = GetClubID($scoresheet[1], $team_grade, $current_year, $season);
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
            //echo("Insert " . $sql_insert . "<br>");
            $update = $dbcnx_client->query($sql_insert);
        }
        elseif($k == 1)
        {
            $home_club_id = GetClubID($scoresheet[0], $team_grade, $current_year, $season);
            $away_club_id = GetClubID($scoresheet[1], $team_grade, $current_year, $season);
            $sql_update_1 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Update 1 " . $sql_update_1 . "<br>");
            $update = $dbcnx_client->query($sql_update_1);
        }
        elseif($k == 2)
        {
            $home_club_id = GetClubID($scoresheet[0], $team_grade, $current_year, $season);
            $away_club_id = GetClubID($scoresheet[1], $team_grade, $current_year, $season);
            $sql_update_2 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Update 2 " . $sql_update_2 . "<br>");
            $update = $dbcnx_client->query($sql_update_2);
        }
        elseif($k == 3)
        {
            $home_club_id = GetClubID($scoresheet[0], $team_grade, $current_year, $season);
            $away_club_id = GetClubID($scoresheet[1], $team_grade, $current_year, $season);
            $sql_update_3 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Update 3 " . $sql_update_3 . "<br>");
            $update = $dbcnx_client->query($sql_update_3);
        }
        elseif($k == 4)
        {
            $home_club_id = GetClubID($scoresheet[0], $team_grade, $current_year, $season);
            $away_club_id = GetClubID($scoresheet[1], $team_grade, $current_year, $season);
            $sql_update_4 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Update 4 " . $sql_update_4 . "<br>");
            $update = $dbcnx_client->query($sql_update_4);
        }
        elseif($k == 5)
        {
            $home_club_id = GetClubID($scoresheet[0], $team_grade, $current_year, $season);
            $away_club_id = GetClubID($scoresheet[1], $team_grade, $current_year, $season);
            $sql_update_5 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Update 5 " . $sql_update_5 . "<br>");
            $update = $dbcnx_client->query($sql_update_4);
        }
        elseif($k == 6)
        {
            $home_club_id = GetClubID($scoresheet[0], $team_grade, $current_year, $season);
            $away_club_id = GetClubID($scoresheet[1], $team_grade, $current_year, $season);
            $sql_update_6 = "Update tbl_create_fixtures Set 
            fix" . ($k+1) . "home = '" . $scoresheet[0] . "', " . "
            fix" . ($k+1) . "away = '" . $scoresheet[1] . "', " . "
            fix" . ($k+1) . "home_club = '" . $home_club_id . "', " . "
            fix" . ($k+1) . "away_club = '" . $away_club_id . "'" . "
            where round = " . $scoresheet[3] . " and year = " . $current_year . " and team_grade = '" . $team_grade . "' and dayplayed = '" . $dayplayed . "' and season = '" . $season . "'";
            //echo("Update 6 " . $sql_update_6 . "<br>");
            $update = $dbcnx_client->query($sql_update_4);
        }
        $j++;
    }
}
?>
<table class='table dt-responsive nowrap display' width='100%'>
  <tr>
    <td colspan=6 class="text-center"><h1>2024 VBSA Fixture Generation</h1></td>
  </tr>
  <tr>
<?php
// get team grades from Team entries table for menu
$sql_grades_menu = 'Select distinct team_grade from Team_entries where team_cal_year = 2024 order by team_grade';
$result_grades_menu = $dbcnx_client->query($sql_grades_menu);
$i = 1;
while($build_data_menu = $result_grades_menu->fetch_assoc())
{
    echo("<th class='text-center'><button type='button' class='btn btn-primary' onclick='Viewtab(" . $i . ");' style='width:120px'>" . $build_data_menu['team_grade'] . "</button></th>");
    $i++;
}
?>
</tr>
<tr>
    <th colspan=3 class='text-right'><button type='button' class='btn btn-primary' onclick='Viewtab(10);' style='width:300px'>Analyse Data (Mon)</button></th>
    <th colspan=3 class='text-left'><button type='button' class='btn btn-primary' onclick='Viewtab(11);' style='width:300px'>Analyse Data (Wed)</button></th>
</tr>
</table>

<?php
// get unavailable dates
$sql_dates = 'Select * from tbl_dates where year = 2024';
$result_dates = $dbcnx_client->query($sql_dates);
$dates = '';
// create string for fixture generator
while($row = $result_dates->fetch_assoc()) 
{
    $dates = substr($row['new_year'], 0, 10) . "\n" . substr($row['aus_day'], 0, 10) . "\n" . substr($row['labour_day'], 0, 10) . "\n" . substr($row['good_friday'], 0, 10) . "\n" . substr($row['sat_before_easter'], 0, 10) . "\n" . substr($row['easter_sunday'], 0, 10) . "\n" . substr($row['easter_monday'], 0, 10) . "\n" . substr($row['anzac_day'], 0, 10) . "\n" . substr($row['kings_birthday'], 0, 10) . "\n" . substr($row['friday_afl'], 0, 10) . "\n" . substr($row['melbourne-cup'], 0, 10) . "\n" . substr($row['xmas_day'], 0, 10) . "\n" . substr($row['boxing_day'], 0, 10) . "\n" . substr($row['other_1'], 0, 10) . "\n" . substr($row['other_2'], 0, 10) . "\n" . substr($row['other_3'], 0, 10);
}

echo("<form name='fixture' method='post' action='fixture_grid.php'>");
echo("<input type='hidden' name='ButtonName' />");
echo('<input type="hidden" name="TeamGrade" />');
echo("<input type='hidden' name='ScoreData' />");
echo('<input type="hidden" name="Fixtures">');
echo('<input type="hidden" name="Type">');
echo('<input type="hidden" name="Grade">');
echo('<input type="hidden" name="DayPlayed">');
$form_no = 1;
$team_grade = '';
$sql_grades = 'Select distinct team_grade from Team_entries where team_cal_year = 2024 order by team_grade';
$result_grades = $dbcnx_client->query($sql_grades);
while($build_data_grades = $result_grades->fetch_assoc())
{
    $team_grade = $build_data_grades['team_grade'];
    $sql_club = 'Select team_name, team_club_id, team_club, team_grade, day_played, comptype from Team_entries where team_cal_year = 2024 and team_grade = "' . $build_data_grades['team_grade'] . '" order by team_grade, team_club';
    //echo($sql_club . "<br>");
    $result_club = $dbcnx_client->query($sql_club);
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

    $result_club_display = $dbcnx_client->query($sql_club);
    $num_club = $result_club_display->num_rows;

    echo("<div id='form" . $form_no . "'>");
    echo("<table class='table table-striped table-bordered dt-responsive nowrap display' class='col-6'>
         <tr>
          <td>");
    echo('<table class="table table-striped table-bordered dt-responsive display text-center" class="col-6">
        <tr>
          <td colspan=2 align="center"><b>' . $build_data_grades['team_grade'] . '</b></td>
          <td colspan=3 align="center" id="comptype_' . $form_no . '">' . $comptype . '</td>
        </tr>
        <tr>
          <td align="center">Position ID</td>
          <td align="center">Club ID</td>
          <td align="center">Tables</td>
          <td align="center">Club Name</td>
          <td align="center">Team Name</td>
        </tr>');
    $x = 1;
    while($build_data_club = $result_club_display->fetch_assoc())
    {
        $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $build_data_club["team_club_id"];
        $result_club_tables = $dbcnx_client->query($sql_club_tables) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
        $tables = $result_club_tables->fetch_assoc();
        $club_tables = $tables['ClubTables'];
        $day_played = $build_data_club["day_played"];
        echo('
          <tr>
            <td scope="row" align="center">' . $x . '</td>
            <td align="center" id-"club_' . $i . '_id>' . $build_data_club["team_club_id"] . '</td>
            <td align="center">' . $club_tables . '</td>
            <td align="center">' . $build_data_club["team_club"] . '</td>
            <td align="center" id-"club_' . $i . '>' . $build_data_club["team_name"] . '</td>
          </tr>
        ');
        $x++;
    }
    echo("</table>");
    echo("</td>");
    echo("<td>");
    echo('<table class="table table-striped table-bordered dt-responsive display text-center" class="col-6">
    <tr>
        <td align="center"><b>Analysis Data</b></td>
        <td align="center" id="team_grade_' . $form_no . '" >' . $build_data_grades['team_grade'] . '</td>
    </tr>
    <tr>
        <td align="left">No of Teams</td>
        <td align="center" id="no_of_teams_' . $form_no . '">' . $teams . '</td>
    </tr>
    <tr>
        <td align="left">Start Date</td>
        <td align="center"><input type="text" name="startdate" id="startdate_' . $form_no . '" onfocusout="SetDate(' . $form_no . ", " . (($teams*2)-2) . ')" style="width:100px"></td>
    </tr>
    <tr>
        <td align="left">Day Played</td>
        <td align="center" id="dayplayed_' . $form_no . '" >' . $day_played . '</td>
    </tr>
    <tr>
        <td align="left" rowspan=' . ($num_club-2) .'>Non available dates</td>
        <td align="center" rowspan=' . ($num_club-2) .'><textarea cols=10 rows=' . ($num_club) .'>' . $dates . '</textarea></td>
    </tr>'
    );
    for($y = 0; $y < ($num_club-3); $y++)
    {
      echo('
          <tr>
            <td colspan=2>&nbsp;</td>
          </tr>
        ');
    }
    echo("<tr>");
    echo("<td class='text-center' colspan='3'><a class='btn btn-primary btn-xs' onclick='SaveFixtures(" . $form_no . ")'; >Save Fixtures for " . $team_grade . "</a></td>");
    echo("</tr>");
    echo("</table>");

    echo("</td>");
    echo("</tr>");
    echo("</table>");
    
    main($fixtures, $team_grade, $form_no); 
    CreateDateArray($form_no, $teams, $team_grade);

    echo("</table>");
    echo('</div>');
    $form_no++;
}


echo("<div id='form10'>");
echo("<input type = 'hidden' id='team_grade_10' value='" . $build_data_grades['team_grade'] . "'>");
echo('<table class="table table-striped table-bordered dt-responsive display text-center" class="col-6">
<tr>
    <td colspan=22 align="center"><b>Analysis Data (Mon)</td>
</tr>
<tr>
    <td rowspan=3 align="center">Grade</td>
    <td rowspan=3 colspan=3 >&nbsp;</td>
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
$sql_grades_menu = 'Select team_grade, count(team_grade) as count from Team_entries Join clubs where clubs.ClubNumber = Team_entries.team_club_id and team_cal_year = 2024 and day_played = "Wed" group by team_grade';
$result_grades_menu = $dbcnx_client->query($sql_grades_menu);
$i = 0;
while($build_data_menu = $result_grades_menu->fetch_assoc())
{
    $no_of_home_teams = $build_data_menu['count'];
    if($no_of_home_teams % 2 == 1) $no_of_home_teams++;
    $no_of_rounds = ($no_of_home_teams*2)-2;
    for($y = 0; $y < ($no_of_home_teams/2); $y++) // home teams in team grade
    {   
        echo("<tr>");
        echo('<td align="center" id="' . $build_data_menu['team_grade'] . '_round_' . ($y+1) . '">' . $build_data_menu['team_grade'] . '_round_' . ($y+1) . '</td>');
        echo('<td colspan=3">&nbsp;</td>');
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
    <td colspan=22 align="center"><b>Table Utilisation (Mon)</td>
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
    <td align="center" id="table_date_0">1</td>
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

$sql_club = 'Select distinct team_club_id, team_club, team_grade from Team_entries where team_cal_year = 2024 and team_season = "S1" and day_played = "Wed" and team_name != "Bye" order by team_grade, team_club';
//echo($sql_club . "<br>");
$result_club = $dbcnx_client->query($sql_club);

while($row = $result_club->fetch_assoc()) 
{
    $sql_club_tables = "Select ClubNumber, ClubTables FROM clubs where ClubNumber = " . $row["team_club_id"];
    $result_club_tables = $dbcnx_client->query($sql_club_tables) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
    $tables = $result_club_tables->fetch_assoc();
    $club_tables = $tables['ClubTables'];
    echo("<tr>");
    echo('<td align="center"></td>');
    echo('<td align="center">' . $row['team_club'] . '</td>');
    echo('<td align="center"></td>');
    echo('<td align="center">' . $club_tables . '</td>');
    for($j = 0; $j < 18; $j++)
    {
        echo('<td align="center">&nbsp;</td>');
    }
    echo("</tr>");

    $sql_team = 'Select distinct team_name, team_grade from Team_entries where team_cal_year = 2024 and team_club_id = "' . $row['team_club_id'] . '"  and team_season = "S1" and day_played = "Wed" ';
    //echo($sql_team . "<br>");
    $result_team = $dbcnx_client->query($sql_team);
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
            //$arrRound = GetMatchesCount(0);
            $count = $j;
            echo('<td align="center" onfocus="TestRound()">');
            echo($count);
            //echo('<script>TestRound();</script>');
            echo('</td>');
        }
        echo("</tr>");
        $y++;
    }
}

echo("</table>");
echo("</div>");

// GetMatchesCount(1);

echo("<div id='form11'>");
echo('<table class="table table-striped table-bordered dt-responsive display text-center" class="col-6">
<tr>
    <td colspan=20 align="center"><b>Analysis Data (Wed)</td>
</tr>
<tr>
    <td rowspan=2 align="center">Grade</td>
    <td rowspan=2 align="center">&nbsp;</td>
    <td colspan=18 align="center">Round</td>
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
</tr>');
$x = 0;
//while($build_data_club = $result_club_display->fetch_assoc())
//{
echo('
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">GPC Geelong</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Yarraville Breath Hackers</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Brunswick Mafia</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Yarrville Thunder</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">North Brighton Whirlwinds</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">CVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Dandenong RSL Green</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">CVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Frankston RSL Sea Side</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">CVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Camberwell Cobras</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">CVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Cheltenham Legends</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
');
$x++;
//}


echo("</table>");
echo("</div>");





// fixture generation code

function main($fixtures, $team_grade, $form_no) 
{
    echo show_fixtures(isset($teams) ?  nums(intval($teams)) : explode(", ", ($fixtures)), $team_grade, $form_no);
}

function nums($n) {
    $ns = array();
    for ($i = 1; $i <= $n; $i++) {
        $ns[] = $i;
    }
    return $ns;
}

function show_fixtures($names, $team_grade, $form_no)
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

    echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
  <tr>");
    for ($i = 0; $i < sizeof($rounds); $i++) {
        echo("<td colspan=3 class='text-center'>Round " . ($i+1)  . "</td></tr>");
        echo("<tr><td class='text-right'>Date</td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo("<td id='" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "'>" . $round_data[0] . "</td>");
            echo("<td align='center'>v</td>");
            echo("<td id='" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "'>" . $round_data[1] . "</td>");
            echo("</tr>");
            $x++;
        }
    }
    $round_counter = sizeof($rounds) + 1;
    for ($i = sizeof($rounds) - 1; $i >= 0; $i--) {
        echo("<td colspan=3 class='text-center'>Round " . $round_counter  . "</td>");
        echo("<tr><td class='text-right'>Date</td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px'></td></tr>");
        $round_counter += 1;
        $y = 0;    
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", flip($r));
            echo("<tr>");
            echo("<td id='" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "'>" . $round_data[0] . "</td>");
            echo("<td align='center'>v</td>");
            echo("<td id='" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "'>" . $round_data[1] . "</td>");
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
/*
function get_form() {
    $s = '';
    $s = '<p>Enter number of teams OR team names</p>' . "\n";
    $s .= '<form action="' . $_SERVER['SCRIPT_NAME'] . '">' . "\n";
    $s .= '<label for="teams">Number of Teams</label><input type="text" name="teams" />' . "\n";
    $s .= '<input type="submit" value="Generate Fixtures" />' . "\n";
    $s .= '</form>' . "\n";

    $s .= '<form action="' . $_SERVER['SCRIPT_NAME'] . '">' . "\n";
    $s .= '<div><strong>OR</strong></div>' . "\n";
    $s .= '<label for="names">Names of Teams (one per line)</label>'
        . '<textarea name="names" rows="8" cols="40"></textarea>' . "\n";
    $s .= '<input type="submit" value="Generate Fixtures" />' . "\n";
    $s .= "</form>\n";
    return $s;
}
*/
echo("</form>");
$testTeam = "Brunswick Titans";
echo("<div class='text-center' colspan='3'><a class='btn btn-primary btn-xs' onclick='TestRound()'>Get Round</a></div>");
?>

<script>
/*
function GetMatchesCount(round)
{
    var arrCount = {};
    var x = document.getElementsByClassName('round_' + round);
    for (var i = 0; i < x.length; i++)
    {
        arrCount[i] = x[i].innerHTML;
    }
    console.log(arrCount);
}
*/
</script>
<?php


