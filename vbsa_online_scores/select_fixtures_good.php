<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');
include('php_functions.php'); 

$season = $_SESSION['season'];
$year = $_SESSION['year'];

//echo("ID " . $_POST['Select'] . "<br>");
//echo("Season " . $_SESSION['season'] . "<br>");
?>
<script>

function FillFixtureButton() 
{
    //alert(document.getElementById('fixture').value.substring(0, 1));
    var grade;
    var type;
    var team_grade = document.getElementById('fixture').value;
    //alert(team_grade);
    grade = document.getElementById('fixture').value.substring(0, 1);
    if(document.getElementById('fixture').value.substring(2, 3) == 'S')
    {
        type = 'Snooker';
    }
    else if((document.getElementById('fixture').value.substring(2, 3) == 'B') || (document.getElementById('fixture').value.substring(1, 2) == 'B')) // added for PB(T)
    {
        type = 'Billiards';
    }
    if((document.getElementById('fixture').value.substring(1, 2) == 'V') || (document.getElementById('fixture').value.substring(1, 2) == 'V') || document.getElementById('fixture').value.substring(0, 1) == 'S') // added for SBT
    {
        team_state = 'State';
    }
    else if((document.getElementById('fixture').value.substring(1, 2) == 'P') || (document.getElementById('fixture').value.substring(0, 1) == 'P'))// added for PB(T)
    {
        team_state = 'Premier';
    }
    else if(document.getElementById('fixture').value.substring(1, 2) == 'W')
    {
        team_state = 'Willis';
    }
    //alert(team_state);
    document.fixture_select.Type.value = type
    document.fixture_select.Grade.value = grade;
    document.fixture_select.TeamGrade.value = team_grade;
    document.fixture_select.State.value = team_state;
    document.fixture_select.Select.value = 'true';
    document.fixture_select.submit();
}

function SaveRound(id) 
{
    var grade;
    var type;
    var team_grade;
    var title = document.getElementById('finalist_' + id).value;
    team_grade = '<?= $_POST['TeamGrade'] ?>';
    grade = team_grade.substring(0, 1);
    if(team_grade.substring(2, 3) == 'S')
    {
        type = 'Snooker';
    }
    else if(team_grade.substring(2, 3) == 'B')
    {
        type = 'Billiards';
    }
    document.fixture.Type.value = type;
    document.fixture.Grade.value = grade;
    document.fixture.TeamGrade.value = team_grade;
    document.fixture.RoundSelected.value = id;
    document.fixture.RoundTitle.value = title;
    document.fixture.action = "save_results.php";
    document.fixture.submit();
}  

function ClearRound(id) 
{
    if (confirm("Are you sure you want to DELETE these scores?") == true) 
    {
        var grade;
        var type;
        var team_grade;
        team_grade = '<?= $_POST['TeamGrade'] ?>';
        grade = team_grade.substring(0, 1);
        if(team_grade.substring(2, 3) == 'S')
        {
            type = 'Snooker';
        }
        else if(team_grade.substring(2, 3) == 'B')
        {
            type = 'Billiards';
        }
        var date = document.getElementById('round' + id + '_date').innerHTML;
        document.fixture.Type.value = type;
        document.fixture.Date.value = date;
        document.fixture.Grade.value = grade;
        document.fixture.TeamGrade.value = team_grade;
        document.fixture.RoundSelected.value = id;
        document.fixture.action = "select_fixtures.php";
        document.fixture.ButtonName.value = "clear_results";
        document.fixture.submit();
    }
}  

function MagicCalcRound(id) 
{
    var grade;
    var type;
    var team_grade;
    var fixture;
    fixture = '<?= $_POST['TeamGrade'] ?>';
    grade = fixture.substring(0, 1);
    if(fixture.substring(2, 3) == 'S')
    {
        type = 'Snooker';
    }
    else if(fixture.substring(2, 3) == 'B')
    {
        type = 'Billiards';
    }
    if(fixture.substring(1, 2) == 'V')
    {
        team_grade = 'State';
    }
    else if(fixture.substring(1, 2) == 'P')
    {
        team_grade = 'Premier';
    }
    document.fixture.Type.value = type;
    document.fixture.Grade.value = grade;
    document.fixture.TeamGrade.value = team_grade;
    document.fixture.RoundSelected.value = id;
    document.fixture.action = "magic_calc.php";
    document.fixture.submit();
}  

</script>
<?php

if (isset($_POST['ButtonName']) && ($_POST['ButtonName'] == 'clear_results'))
{
    $current_year = $_SESSION['year'];
    $current_season = $_SESSION['season'];
    $type = $_POST['Type'];
    $grade = $_POST['Grade'];
    $team_grade = $_POST['TeamGrade'];
    $round = $_POST['RoundSelected'];
    $date = $_POST['Date'];
    // format round number
    if($round > 8)
    {
        $rnd_no = $round;
    }
    else
    {
        $rnd_no = '0' . $round;
    }

    $sql_scrsheet = "Delete From tbl_scoresheet where round  = " . $round . " AND season = '" . $current_season . "' and year = " . $current_year . " and team_grade = '" . $team_grade . "'";  
    $update = $dbcnx_client->query($sql_scrsheet);
    //echo("Clean scoresheet Entries " . $sql_scrsheet . "<br>");

    $sql_club= "Delete From tbl_club_results where round  = " . $round . " AND season = '" . $current_season . "' and year = " . $current_year . " and team_grade = '" . $team_grade . "'";             
    $update = $dbcnx_client->query($sql_club);
    //echo("Clean club results Entries " . $sql_club . "<br>");

    $sql_breaks = "Delete From breaks where recvd  = '" . MySqlDate($date) . "' AND season = '" . $current_season . "' and grade = '" . $team_grade . "'";             
    $update = $dbcnx_client->query($sql_breaks);
    //echo("Clean breaks Entries " . $sql_breaks . "<br>");

    $sql_scrs_clean = "Update scrs SET r" . $rnd_no  . "s = NULL, r" . $rnd_no  . "pos = NULL where team_grade = '" . $team_grade . "' and scr_season = '" . $current_season . "' and current_year_scrs = " . $current_year;
    $update = $dbcnx_client->query($sql_scrs_clean);
    //echo("Clean scrs Entries " . $sql_scrs_clean . "<br>");
    
    // added clear result score and position 29/08/2023
    $sql_team_entries_clean = "Update Team_entries SET T" . $rnd_no . " = NULL, P" . $rnd_no . " = NULL, Result_pos = NULL, Result_score = NULL where team_grade = '" . $team_grade . "' and team_season = '" . $current_season . "' and team_cal_year = " . $current_year;
    $update = $dbcnx_client->query($sql_team_entries_clean);
    echo("Clean Team Entries " . $sql_team_entries_clean . "<br>");

    // added recalc total scores 29/08/2023
    // select all teams in grade so as to recalc total score
    $sql_select_teams = "Select * FROM vbsa3364_vbsa2.Team_entries where team_season = '" . $current_season . "' and team_grade = '" . $team_grade . "' and team_cal_year = " . $current_year;
    //echo("Clean Team Entries " . $sql_select_teams . "<br>");

    $result_select_teams = $dbcnx_client->query($sql_select_teams) or die("Couldn't execute players query. " . mysqli_error($dbcnx_client));
    while($build_data = $result_select_teams->fetch_assoc())
    {
        //echo("Team ID " . $build_data['team_id'] . "<br>");
       // Update total scores
        $sql_total_score = "Select SUM(IFNULL(scrs.r01s,0)) + SUM(IFNULL(scrs.r02s,0)) + SUM(IFNULL(scrs.r03s,0)) + SUM(IFNULL(scrs.r04s,0)) + SUM(IFNULL(scrs.r05s,0)) + SUM(IFNULL(scrs.r06s,0)) + SUM(IFNULL(scrs.r07s,0)) + SUM(IFNULL(scrs.r08s,0)) + SUM(IFNULL(scrs.r09s,0)) + SUM(IFNULL(scrs.r10s,0)) + SUM(IFNULL(scrs.r11s,0)) + SUM(IFNULL(scrs.r12s,0)) + SUM(IFNULL(scrs.r13s,0)) + SUM(IFNULL(scrs.r14s,0)) + SUM(IFNULL(scrs.r15s,0)) + SUM(IFNULL(scrs.r16s,0)) + SUM(IFNULL(scrs.r17s,0)) + SUM(IFNULL(scrs.r18s,0)) AS team_total FROM scrs WHERE team_id = " . $build_data['team_id'];
        //echo("Get Total scores " . $sql_total_score . "<br>");
         $result_total_score = $dbcnx_client->query($sql_total_score) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
        $build_total_score = $result_total_score->fetch_assoc();
        //echo("Total " . $build_total_score['team_total'] . "<br>");

        $sql_team_scores = "Update Team_entries SET total_score = " . $build_total_score['team_total'] . " where team_id = " . $build_data['team_id'] . " and team_season = '" . $current_season . "' and team_cal_year = " . $current_year . " and team_grade = '" . $team_grade . "'";
        $update = $dbcnx_client->query($sql_team_scores);
        //echo("Update Total Scores " . $sql_team_scores . "<br>");
    }

}

if ((isset($_POST['Grade']) and $_POST['Grade'] <> '') and (isset($_POST['Type']) and $_POST['Type'] <> '')) 
{
    $type = $_POST['Type'];
    $grade = $_POST['Grade'];
    $team_grade = $_POST['TeamGrade'];
}
else
{
    $type = '';
    $grade = '';
    $team_grade = '';
}
$current_year = $_SESSION['year'];
$current_season = $_SESSION['season'];
$clubname = $_SESSION['clubname'];
$login_rights = $_SESSION['login_rights'];

// get from grade settings table
$sql_grades = "Select * From Team_grade Where grade = '" . $team_grade . "' AND fix_cal_year = " . $current_year . " AND season = '" . $current_season . "'";
//echo($sql_grades . "<br>");
$result_grades = $dbcnx_client->query($sql_grades) or die("Couldn't execute settings query. " . mysqli_error($dbcnx_client));
$build_grades = $result_grades->fetch_assoc();

$NoOfFixtures = $build_grades['no_of_matches'];
$NoOfRounds = $build_grades['no_of_rounds'];

$modified_colspan = 5;
$NoOfRowsPerRound = 1;
$RoundsperPage = ceil($NoOfRounds/$NoOfRowsPerRound);

$final_start = ($NoOfRounds-1); // start of finals in round 16

$sql = "Select * From tbl_fixtures Where type = '" . $type . "' AND team_grade = '" . $team_grade . "'
AND year = " . $current_year . " AND season = '" . $current_season . "' Order By round";
//echo($sql . "<br>");
$result_fixture = $dbcnx_client->query($sql) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
$num_rows = $result_fixture->num_rows;
if($num_rows > 0)
{
    echo("<script type='text/javascript'>");
    echo("function fillelementarray() {");
    $i = 0;
    while ($build_data = $result_fixture->fetch_assoc()) 
    {
      $team_grade = $build_data['team_grade'];
      echo("document.getElementById('round" . ($i+1) . "_date').innerHTML = '" . DisplayDate($build_data['date']) . "';");
      for ($j = 0; $j < $NoOfFixtures; $j++) 
      {
        $sql_home = "Select * from tbl_club_results where club = '" . $build_data["fix" . ($j+1) . "home"] . "' AND date_played = '" . $build_data['date'] . "' AND team_grade = '" . $team_grade . "'";
        $result_home = $dbcnx_client->query($sql_home) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
        $build_data_home = $result_home->fetch_assoc();
        $num_rows_home = $result_home->num_rows;
        if($num_rows_home > 0)
        {
            $home_points = $build_data_home['overall_points'];
            $home_games = $build_data_home['games_won'];
        }
        else
        {
            $home_points = 0;
            $home_games = 0;
        }

        $sql_away = "Select * from tbl_club_results where club = '" . $build_data["fix" . ($j+1) . "away"] . "' AND date_played = '" . $build_data['date'] . "' AND team_grade = '" . $team_grade . "'";
        $result_away = $dbcnx_client->query($sql_away) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
        $build_data_away = $result_away->fetch_assoc();
        $num_rows_away = $result_away->num_rows;
        if($num_rows_away > 0)
        {
            $away_points = $build_data_away['overall_points'];
            $away_games = $build_data_away['games_won'];
        }
        else
        {
            $away_points = 0;
            $away_games = 0;
        }
        $sql_check_home = "Select team, opposition, capt_home, capt_away from tbl_club_results join tbl_scoresheet where tbl_club_results.date_played = tbl_scoresheet.date_played and team = '" . $build_data["fix" . ($j+1) . "home"] . "' AND tbl_club_results.date_played = '" . $build_data['date'] . "' and tbl_scoresheet.team_grade = '" . $team_grade . "' LIMIT 1";
        $result_check_home = $dbcnx_client->query($sql_check_home) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
        $build_check_home = $result_check_home->fetch_assoc();
        if($build_check_home['capt_home'] == 1)
        {
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').style.backgroundColor = 'green';");
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').style.color = 'white';");
        }
        elseif($build_check_home['capt_home'] == 0)
        {
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').style.backgroundColor = 'red';");
        }
        $sql_check_away = "Select team, opposition, capt_home, capt_away from tbl_club_results join tbl_scoresheet where tbl_club_results.date_played = tbl_scoresheet.date_played and team = '" . $build_data["fix" . ($j+1) . "away"] . "' AND tbl_club_results.date_played = '" . $build_data['date'] . "' and tbl_scoresheet.team_grade = '" . $team_grade . "' LIMIT 1";
        $result_check_away = $dbcnx_client->query($sql_check_away) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
        $build_check_away = $result_check_away->fetch_assoc();

        if($build_check_away['capt_home'] == 1)
        {
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').style.backgroundColor = 'green';");
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').style.color = 'white';");
        }
        elseif($build_check_away['capt_home'] == 0)
        {
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').style.backgroundColor = 'red';");
        }

        echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_home').innerHTML = '" . $build_data["fix" . ($j+1) . "home"] . "';");
        echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_away').innerHTML = '" . $build_data["fix" . ($j+1) . "away"] . "';");
        echo("document.getElementById('round" . ($i+1) . "_home_fix" . ($j+1) . "').value = '" . $build_data["fix" . ($j+1) . "home"] . "," . $build_data["fix" . ($j+1) . "away"] . "';");
        echo("document.getElementById('round" . ($i+1) . "_away_fix" . ($j+1) . "').value = '" . $build_data["fix" . ($j+1) . "home"] . "," . $build_data["fix" . ($j+1) . "away"] . "';");
        echo("if((document.getElementById('finalist_" . ($i+1) . "').value == 'Semi Final') || (document.getElementById('finalist_" . ($i+1) . "').value == 'Grand Final'))");
        echo("{");
        echo("  document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').innerHTML = '" . $home_games . "';");
        echo("  document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').innerHTML = '" . $away_games . "';");
        echo("}");
        echo("else");
        echo("{");
        
        if($type == 'Billiards')
        {
            echo("  document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').innerHTML = '" . $home_games . "';");
            echo("  document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').innerHTML = '" . $away_games . "';");
        }
        else
        {
            echo("  document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').innerHTML = '" . $home_games . " (" . $home_points . ")';");
            echo("  document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').innerHTML = '" . $away_games . " (" . $away_points . ")';");
        }
        echo("}");

        // mark green/red if bye fixture
        if($type == 'Snooker')
        {
            if((($home_points == 2) && ($home_games == 6) && ($build_data["fix" . ($j+1) . "home"] == 'Bye')) || (($away_points == 2) && ($home_games == 6) && ($build_data["fix" . ($j+1) . "away"] == 'Bye')))
            {
                echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').style.backgroundColor = 'green';");
                echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').style.color = 'white';");
                echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').style.backgroundColor = 'green';");
                echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').style.color = 'white';");
            }
        }
        elseif($type == 'Billiards')
        {
            if((($home_points == 2) && ($home_games == 4) && ($build_data["fix" . ($j+1) . "home"] == 'Bye')) || (($away_points == 2) && ($home_games == 4) && ($build_data["fix" . ($j+1) . "away"] == 'Bye')))
            {
                echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').style.backgroundColor = 'green';");
                echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').style.color = 'white';");
                echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').style.backgroundColor = 'green';");
                echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').style.color = 'white';");
            }
        }
        else
        {
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_home').style.backgroundColor = 'red';");
            echo("document.getElementById('round" . ($i+1) . "_fix" . ($j+1) . "_modified_away').style.backgroundColor = 'red';");
        }
      }
      $i++;
    }
    echo("}");

    echo("function GetRadioData() {");
    for ($j = 0; $j < $NoOfRounds; $j++) 
    {
        echo("  var round" . ($j+1) . " = document.getElementsByName('round" . ($j+1) . "');");
        echo("  for(var i = 0; i < round" . ($j+1) . ".length; i++) {");
        echo("     if(round" . ($j+1) . "[i].checked === true) {");
        echo("        var round_title = document.getElementById('finalist_" . ($j+1) . "').value;");
        echo("        var teams = round" . ($j+1) . "[i].value.split(',');");
        echo("        var scoring_team = round" . ($j+1) . "[i].id.split('_');");
        echo("        document.fixture.Year.value = " . $current_year . ";");
        echo("        document.fixture.Season.value = '". $current_season . "';");
        echo("        document.fixture.TeamScoring.value = scoring_team[1];");
        echo("        document.fixture.SessionHomeTeam.value = teams[0];");
        echo("        document.fixture.SessionAwayTeam.value = teams[1];");
        echo("        document.fixture.HomeTeam.value = teams[0];");
        echo("        document.fixture.AwayTeam.value = teams[1];");
        echo("        document.fixture.RoundNo.value = " . ($j+1) . ";");
        echo("        document.fixture.RoundTitle.value = round_title;");
        echo("        document.fixture.Grade.value = '" . $grade . "';");
        echo("        document.fixture.Type.value = '" . $type . "';");
        echo("        document.fixture.FixtureDate.value = document.getElementById('round" . ($j+1) . "_date').innerHTML;");
        echo("     }");
        echo("  }");
    }
    echo("if((document.fixture.SessionHomeTeam.value == 'Bye') || (document.fixture.SessionAwayTeam.value == 'Bye')) {");
    echo("    alert('You have a Bye this week!');");
    //clear all radio boxes after selection
    for ($j = 0; $j < $NoOfRounds; $j++) 
    {
      echo("for (var i = 0; i < round" . ($j+1) . ".length; i++) {");
      echo("     round" . ($j+1) . "[i].checked = false;");
      echo("}");
    }
    echo("    return false;");
    echo("} ");

    echo("if((scoring_team[1] == 'home' && document.fixture.SessionHomeTeam.value == '" . $clubname . "') || (scoring_team[1] == 'away' && document.fixture.SessionAwayTeam.value == '" . $clubname . "')|| ('" . $login_rights . "' == 'Administrator')) {");
    echo("document.fixture.action = 'scoresheet.php';");
    echo("document.fixture.submit();");
    echo("}");
    echo("else");
    echo("{");
    //clear all radio boxes 
    for ($g = 0; $g < $NoOfRounds; $g++) 
    {
    echo("for (var i = 0; i < round" . ($g+1) . ".length; i++) {");
    echo("     round" . ($g+1) . "[i].checked = false;");
    echo("}");
    }
    echo("      alert('You can only enter or view scores for your own team!');");
    echo("      return;");
    echo("}");

    echo("}");
    echo("window.onload = fillelementarray;");
}
echo("</script>");

echo("<center>");
echo("<form name='fixture_select' method='post' action='select_fixtures.php'>");
echo("<input type='hidden' name='Year' />");
echo("<input type='hidden' name='Season' />");
echo("<input type='hidden' name='HomeTeam' />");
echo("<input type='hidden' name='AwayTeam' />");
echo("<input type='hidden' name='RoundNo' />");
echo("<input type='hidden' name='Grade' />");
echo("<input type='hidden' name='State' />");
echo("<input type='hidden' name='Type' />");
echo("<input type='hidden' name='TeamGrade' value='" . $team_grade . "'/>");
echo("<input type='hidden' name='Select' />");
echo("<input type='hidden' name='FixtureDate' />");
echo("<table border='0' align='center' cellpadding='0' cellspacing='10' width='100%'>");
//echo("<tr>");
//echo("<td colspan='3'><h1 align='center'>Fixture Selection</h1></td>");
//echo("</tr>");
echo("<tr>");
echo("<td colspan='3'>&nbsp;</td>");
echo("</tr>");
echo("<tr>");
echo("<td align='center' valign='top'><h2>Select Fixture</h2><select id='fixture' onchange='FillFixtureButton()'>");

if(isset($_POST['Fixture'])) 
{
    echo("<option value='' selected='selected'>" . $_POST['Fixture'] . "</option>");
}
else
{
    echo("<option value='' selected='selected'></option>");
}
// fill fixture dropdown
$sql = "Select distinct team_grade, grade, type, dayplayed FROM tbl_fixtures  where season = '". $current_season . "' and year = '". $current_year . "' order by team_grade";
$result_fixture = $dbcnx_client->query($sql) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
echo($sql . "<br>");
while($build_fixture = $result_fixture->fetch_assoc()) 
{
    echo("<option value='" . $build_fixture['team_grade'] . "'>" . $build_fixture['team_grade'] . " " . $build_fixture['grade'] . " Grade" . " " . $build_fixture['type'] . " (" . $build_fixture['dayplayed'] . ")</option>");
}
echo("</select></b>");
echo("</td>");
echo("</tr>");
echo("<tr>");
echo("<td colspan='3'><h4 align='center'>" . $team_grade . "</h4></td>");
echo("</tr>");
echo("<tr>");
echo("<td colspan='3'>&nbsp;</td>");
echo("</tr>");
echo("</table>");
echo("</form>");
if($_POST['Select'] == 'true')
{
    echo("<form name='fixture' method='post'>");
    echo("<input type='hidden' name='SessionHomeTeam' />");
    echo("<input type='hidden' name='SessionAwayTeam' />");
    echo("<input type='hidden' name='HomeTeam' />");
    echo("<input type='hidden' name='AwayTeam' />");
    echo("<input type='hidden' name='RoundNo' />");
    echo("<input type='hidden' name='Date' />");
    echo("<input type='hidden' name='Select' />");
    echo("<input type='hidden' name='FixtureDate' />");
    echo("<input type='hidden' name='RoundSelected' value=''/>");
    echo("<input type='hidden' name='RoundTitle' value='" . $final_title . "'/>");
    echo("<input type='hidden' name='TeamScoring' />");
    echo("<input type='hidden' name='TeamGrade' value='" . $team_grade . "'/>");
    echo("<input type='hidden' name='ButtonName' />");
    echo("<input type='hidden' name='Year' id='year' value=" . $_SESSION['year'] . " />");
    echo("<input type='hidden' name='Season' id='season' value='" . $_SESSION['season'] . "' />");
    if($num_rows > 0)
    {
        echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>");
        $modified_colspan = 7;
        for ($j = 0; $j < $RoundsperPage; $j++) 
        { // number of rows per season
            $x = $NoOfRowsPerRound * $j;
            echo ("<tr>");
            for ($i = 0; $i < $NoOfRowsPerRound; $i++) { //no of rounds per row
                $round = (($i + 1) + $x);
                if((($i + 1) + $x) == $final_start)
                {
                    $final_title = "Semi Final";
                    $save_button_text = "Save results for Semi Finals";
                    $clear_button_text = "Clear results for Semi Finals";
                    //$NoOfFixtures = 2;
                }
                elseif((($i + 1) + $x+1) > $final_start)
                {
                    $final_title = "Grand Final";
                    $save_button_text = "Save results for Grand Final";
                    $clear_button_text = "Clear results for Grand Final";
                    //$NoOfFixtures = 1;
                }
                else
                {
                    $final_title = "Round " . (($i + 1) + $x);
                    $save_button_text = "Save all results round " . $round;
                    $clear_button_text = "Clear all results round " . $round;
                }
                echo ("<td colspan=" . $modified_colspan . " align='center'><b>" . $final_title . "</b></td>");
                echo("<input type='hidden' id='finalist_" . (($i + 1) + $x) . "' value='" . $final_title . "' />");
                $round = (($i + 1) + $x);
            }
            echo ("</tr>");
            echo ("<tr>");
            for ($i = 0; $i < $NoOfRowsPerRound; $i++) 
            { //no of rounds per row (date entry)
                echo ("<td colspan=" . $modified_colspan . " id='round" . (($i + 1) + $x) . "_date' align='center' ></td>");
            }
            echo ("</tr>");
            for ($l = 0; $l < $NoOfFixtures; $l++) 
            { // no of fixtures per row
                echo ("<tr>");
                for ($k = 0; $k < $NoOfRowsPerRound; $k++) 
                { // no of rounds per row
                    echo ("<td><input type='radio' name='round" . (($k + $x) + 1) . "' id='round" . (($k + $x) + 1) . "_home_fix" . ($l + 1) . "' class='fix' OnClick='GetRadioData();'></td>");
                    echo ("<td id='round" . (($k + $x) + 1) . "_fix" . ($l + 1) . "_home' align='center'></td>");
                    echo ("<td id='round" . (($k + $x) + 1) . "_fix" . ($l + 1) . "_modified_home' width=1 align='center'></td>");
                    echo ("<td align='center'>v</td>");
                    echo ("<td id='round" . (($k + $x) + 1) . "_fix" . ($l + 1) . "_modified_away' width=1 align='center'></td>");
                    echo ("<td id='round" . (($k + $x) + 1) . "_fix" . ($l + 1) . "_away' align='center'></td>");
                    echo ("<td><input type='radio' name='round" . (($k + $x) + 1) . "' id='round" . (($k + $x) + 1) . "_away_fix" . ($l + 1) . "'  class='fix' OnClick='GetRadioData()';></td>");
                }
                echo ("</tr>");
            }
            echo("<input type='hidden' name='Grade' value='" . $grade . "' />");
            echo("<input type='hidden' name='Type' value='" . $type . "' />");
            echo("<input type='hidden' id='fixture' name='TeamGrade' value = '" . $team_grade . "' />");
            
            if($_SESSION['login_rights'] == "Administrator")
            {
                echo("<tr>");
                echo("<td class='text-center' colspan=7><a class='btn btn-primary btn-xs' onclick='SaveRound(" . $round . ")'>" . $save_button_text . "</a></td>");
                echo("</tr>");

                echo("<tr>");
                echo("<td class='text-center' colspan=7><a class='btn btn-danger btn-xs' onclick='ClearRound(" . $round . ")'>" . $clear_button_text . "</a></td>");
                echo("</tr>");
            }
            echo("<tr>");
            echo("<td class='text-center' colspan=7>&nbsp;</td>");
            echo("</tr>");
        }
        echo("</table>");
        echo("</form>");
    }
    else
    {   ?>
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
echo("</center>");

include("footer.php"); 
?>
