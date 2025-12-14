<?php
require_once('../Connections/connvbsa.php'); 

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

$type = $_GET['Type'];
$grade = $_GET['Grade'];
$team_grade = stripslashes($_GET['TeamGrade']);
$form_no = $_GET['FormNo'];
$dayplayed = $_GET['DayPlayed'];
$current_year = $_GET['Year'];
$season = $_GET['Season'];
$no_of_fixtures  = $_GET['Fixtures'];
$no_of_rounds  = $_GET['Rounds'];  // number of rounds NOT including finals

echo("Type " .  $_GET['Type'] . "<br>");
echo("Grade " .  stripslashes($_GET['TeamGrade']) . "<br>");
echo("Form No  " .  $_GET['FormNo'] . "<br>");
echo("Day " .  $_GET['DayPlayed'] . "<br>");
echo("Year " .  $_GET['Year'] . "<br>");
echo("Season " .  $_GET['Season'] . "<br>");
echo("Fixtures " .  $_GET['Fixture'] . "<br>");
echo("Rounds " .  $_GET['Rounds'] . "<br>");


//echo("Saving Fixtures<br>");
function GetClubID($team_name, $team_grade, $year, $season)
{
    global $connvbsa;
    // get club id for home and away team names
    $sql_club = "Select team_club_id, team_name FROM Team_entries where team_grade = '" . $team_grade . "' and team_cal_year = " . $year . " and team_season = '" . $season . "' and team_name = '" . $team_name . "';";
    //echo($sql_club . "<br>");
    $result_club = mysql_query($sql_club, $connvbsa) or die(mysql_error());
    $build_club = $result_club->fetch_assoc();
    $club_id = $build_club['team_club_id'];
    return $club_id;
}

// get start date for grade
$sql_grade = "Select grade_start_date, finals_teams FROM Team_grade where grade = '" . $team_grade . "' and fix_cal_year = " . $current_year . " and season = '" . $season . "' and current = 'Yes';";
//echo($sql_grade . "<br>");
$result_grade = mysql_query($sql_grade, $connvbsa) or die(mysql_error());
$build_grade = $result_grade->fetch_assoc();
$grade_start = $build_grade['grade_start_date'];
$no_of_finals = ($build_grade['finals_teams']/2);
$normal_rounds = ($no_of_rounds); // number of rounds excluding finals
$total_rounds = ($no_of_rounds+$no_of_finals); // number of rounds including finals

//echo("Team grade " . $team_grade . ", Normal rounds " . $no_of_rounds . ", No of Finals rounds " . ($no_of_finals) . ", Finals inc Rounds " . $total_rounds . ", Grade Start " . $grade_start . "<br>");

//$no_of_rounds = ($no_of_rounds+($no_of_finals/2)); // number of rounds including finals

function addDays($date, $days, $round) 
{
    $result = date('Y-m-d', strtotime($date . ' + ' . ($days*$round) . ' days'));
    return $result;
}

// delete existing fixtures
$sql_delete = "Delete FROM tbl_create_fixtures where year = " . $current_year . " and season = '" . $season . "' and team_grade = '". $team_grade . "'";
$result_delete = mysql_query($sql_delete, $connvbsa) or die(mysql_error());
$scoredata = json_decode(stripslashes($_GET['ScoreData']), true);
$j = 0;
$k = 0;
//echo(sizeof($scoredata) . "<br>");
for ($i = 0; $i < sizeof($scoredata); $i++) 
{
    $scoresheet = explode(", ", $scoredata[$i]);
    $k = (($j % $no_of_fixtures));
    if($k == 0)
    {
        // get club id for home and away team names
        $home_club_id = GetClubID($scoresheet[0], $team_grade, $current_year, $season);
        $away_club_id = GetClubID($scoresheet[1], $team_grade, $current_year, $season);
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
        addDays($grade_start, 7, ($scoresheet[3]-1)) . "', '" . 
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
        //echo($sql_insert . "<br>");
        $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
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
        $update = mysql_query($sql_update_1, $connvbsa) or die(mysql_error());
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
        $update = mysql_query($sql_update_2, $connvbsa) or die(mysql_error());
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
        $update = mysql_query($sql_update_3, $connvbsa) or die(mysql_error());
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
        $update = mysql_query($sql_update_4, $connvbsa) or die(mysql_error());
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
        $update = mysql_query($sql_update_5, $connvbsa) or die(mysql_error());
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
        $update = mysql_query($sql_update_6, $connvbsa) or die(mysql_error());
    }
    $j++;
}

// add finals fixture positions to fixtures table.
for($x = $no_of_rounds; $x < $total_rounds; $x++)
{
    //echo($x . "<br>");
    $sql_insert_finals = "Insert into tbl_create_fixtures (
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
    fix7home,
    fix7away,
    year, 
    season, 
    team_grade, 
    dayplayed,
    fix1home_club, 
    fix1away_club,
    fix2home_club, 
    fix2away_club) 
    Values ('" . 
    addDays($grade_start, 7, ($x+1)) . "', '" . 
    $type . "', '" . 
    $grade . "', " . 
    ($x+1) . ", 'TBA', 'TBA', 'TBA', 'TBA', 'TBA', 'TBA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', " . 
    $current_year . ", '" . 
    $season.  "', '" . 
    $team_grade . "', '" . 
    $dayplayed . "', '', '', '', '')"; 
    //echo($sql_insert_finals . "<br>");
    $update = mysql_query($sql_insert_finals, $connvbsa) or die(mysql_error());
}

// get array of non available dates.
$date_array = array();
$sql_playing_dates = 'Select * from tbl_ics_dates where Year(DTSTART) = Year(CURDATE()) and ok_to_use = 0 Order by DTSTART';
$result_playing_dates = mysql_query($sql_playing_dates, $connvbsa) or die(mysql_error());
$i = 0;
while($build_date = $result_playing_dates->fetch_assoc())
{
    $date_array[$i] = $build_date['DTSTART'];
    $i++;
}

$current_date = $grade_start;
$days = 7;
for($i = 0; $i < $no_of_rounds; $i++)
{
    if($i == 0)
    {
        $sql_update_fixtures_r1 = "Update tbl_create_fixtures Set date = '" . $grade_start . "' where year = " . $current_year . " and team_grade = '" . $team_grade . "' and season = '" . $season . "' and round = " . ($i+1);
        $update = mysql_query($sql_update_fixtures_r1, $connvbsa) or die(mysql_error());
    }
    else
    {
        $round = ($i+1);
        $current_date = date('Y-m-d', strtotime($current_date . ' + ' . $days . ' days'));
        if(in_array($current_date, $date_array))
        {
            $current_date = date('Y-m-d', strtotime($current_date . ' + ' . $days . ' days'));
        }
        $sql_update_fixtures = "Update tbl_create_fixtures Set date = '" . $current_date . "' where year = " . $current_year . " and team_grade = '" . $team_grade . "' and season = '" . $season . "' and round = " . $round;
        $update = mysql_query($sql_update_fixtures, $connvbsa) or die(mysql_error());
    }
}

// save sort order to team entries
//echo($_GET['SortData'] . "<br>");
$sortdata = json_decode(stripslashes($_GET['SortData']), true);
//echo("<pre>");
//echo('Sort Before ' . var_dump($sortdate));
//echo("</pre>");
//$sortdata = shuffle($_GET['SortData']);
//echo("<pre>");
//echo(shuffle($sortdata));
//echo("</pre>");
for ($i = 0; $i < sizeof($sortdata); $i++) 
{
    $sort_data = explode(", ", $sortdata[$i]);
    //echo(($sort_data[1]));
    $sql_update_sort = "Update Team_entries Set 
    fix_sort = " . $sort_data[1] . " where team_cal_year = " . $current_year . " and team_grade = '" . $team_grade . "' and team_name = '" . $sort_data[0] . "' and day_played = '" . $dayplayed . "' and team_season = '" . $season . "'";
    $update = mysql_query($sql_update_sort, $connvbsa) or die(mysql_error());
}

// added to automate clashes
/*
function PopulateFromDatabase($team_grade, $form_no, $year, $season, $dayplayed, $no_of_rounds)
{
    global $connvbsa;
    // get data for dataset 
    $sql = "Select team_name FROM Team_entries WHERE team_season ='$season' AND team_cal_year = '$year' AND team_grade = '$team_grade' AND day_played = '$dayplayed' ORDER BY team_name";
    $result_teams = mysql_query($sql, $connvbsa) or die(mysql_error());
    $no_of_teams = $result_teams->num_rows;
    $no_of_fixtures = ($no_of_teams/2);
    //$no_of_rounds = (($no_of_teams*2)-2);
    //echo("<table style='background-color: grey; display: none' class='table table-striped table-bordered dt-responsive nowrap display float-container' width='1000px'>");
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
        //echo($sql_fixtures . "<br>");
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

PopulateFromDatabase($team_grade, $form_no, $year, $season, $dayplayed, $no_of_rounds);
*/
echo("Save - Fixtures have been saved.");


?>