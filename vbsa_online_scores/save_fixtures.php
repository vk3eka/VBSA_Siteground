<?php

include('connection.inc'); 
include('php_functions.php'); 

$type = $_GET['Type'];
$grade = $_GET['Grade'];
$team_grade = stripslashes($_GET['TeamGrade']);
$form_no = $_GET['FormNo'];
$dayplayed = $_GET['DayPlayed'];
$current_year = $_GET['Year'];
$season = $_GET['Season'];
$no_of_fixtures  = $_GET['Fixtures'];
$no_of_rounds  = $_GET['Rounds'];

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

// get start date for grade
$sql_grade = "Select grade_start_date FROM Team_grade where grade = '" . $team_grade . "' and fix_cal_year = " . $current_year . " and season = '" . $season . "';";
$result_grade = $dbcnx_client->query($sql_grade) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
$build_grade = $result_grade->fetch_assoc();
$grade_start = $build_grade['grade_start_date'];


function addDays($date, $days, $round) 
{
    $result = date('Y-m-d', strtotime($date . ' + ' . ($days*$round) . ' days'));
    return $result;
}

// delete existing fixtures
$sql_delete = "Delete FROM tbl_create_fixtures where year = " . $current_year . " and season = '" . $season . "' and team_grade = '". $team_grade . "'";
$result_delete = $dbcnx_client->query($sql_delete);
$scoredata = json_decode(stripslashes($_GET['ScoreData']), true);
$j = 0;
$k = 0;
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
        $update = $dbcnx_client->query($sql_update_4);
    }
    $j++;
}

function CheckUnuseableDays($date, $days, $round, $team_grade, $current_year, $season) 
{
    global $dbcnx_client;
    // get current date used
    if($round > 0)
    {
        $sql_last_date = "Select date from tbl_create_fixtures where year = " . $current_year . " and season = '" . $season . "' and team_grade = '" . $team_grade . "' and round = " . ($round) . " Order By date";
        $result_last_date = $dbcnx_client->query($sql_last_date) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
        $build_last_date = $result_last_date->fetch_assoc();
        $last_date = $build_last_date['date'];
    }
    else
    {
        $last_date = $grade_start;
    }

    if($last_date != '')
    {
        //}
        $sql_playing_dates = 'Select * from tbl_ics_dates where DTSTART = "' . $last_date . '" and ok_to_use = 0 Order by DTSTART';
        $result_playing_dates = $dbcnx_client->query($sql_playing_dates) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
        $row_count = $result_playing_dates->num_rows;
        //echo('Existing Date ' . $last_date . "<br>");
        //echo('Existing Date ' . date('Y-m-d', strtotime($date . ' + ' . ($days*$round) . ' days')) . "<br>");
        //echo('Days ' . $days . "<br>");
        //echo('Count ' . $row_count . "<br>");
        //echo('Round ' . $round . "<br>");
        //echo("Add Days " . ($days*$round) . "<br>");
        if($row_count > 0)
        {
            echo("Day is not available<br><br>");
        }
        else
        {
            //...........................................
            //echo("Day is available<br><br>");
            $sql_update_fixtures = "Update tbl_create_fixtures Set fix_sort = " . $sort_data[1] . " where team_cal_year = " . $current_year . " and team_grade = '" . $team_grade . "' and team_name = '" . $sort_data[0] . "' and day_played = '" . $dayplayed . "' and team_season = '" . $season . "'";
            //echo($sql_update_sort . "<br>");
            $update = $dbcnx_client->query($sql_update_sort);
            //...........................................

            //$result = date('Y-m-d', strtotime($date . ' + ' . ($days*$round) . ' days'));
            //echo("Result " . $result . "<br>");
        }
        $result = date('Y-m-d', strtotime($date . ' + ' . ($days*$round) . ' days'));
        //echo("Next Date " . $result . "<br><br>");
    }
    //else
    //{
    //    echo('Existing Date ' . $last_date . "<br>");;
    //}
    //$result = date('Y-m-d', strtotime($date . ' + ' . ($days*$round) . ' days'));
    //echo("Result " . $result . "<br>");
    return $result;
}

// update dates after tbl_create_fixtures save.
for($i = 0; $i < $no_of_rounds; $i++)
{
    //$new_date = CheckUnuseableDays($grade_start, 7, $i, $team_grade, $current_year, $season);
    //echo("New Date " . $new_date . " in Round " . ($i+1) . "<br>");
    //addDays($grade_start, 7, ($scoresheet[3]-1)); 
}

// save sort order to team entries
$sortdata = json_decode(stripslashes($_GET['SortData']), true);
for ($i = 0; $i < sizeof($sortdata); $i++) 
{
    $sort_data = explode(", ", $sortdata[$i]);
    $sql_update_sort = "Update Team_entries Set 
    fix_sort = " . $sort_data[1] . " where team_cal_year = " . $current_year . " and team_grade = '" . $team_grade . "' and team_name = '" . $sort_data[0] . "' and day_played = '" . $dayplayed . "' and team_season = '" . $season . "'";
    //echo($sql_update_sort . "<br>");
    $update = $dbcnx_client->query($sql_update_sort);

}

echo("Fixtures have been saved.");

?>