<?php

include('connection.inc');
include('header.php'); 

$grade = trim($_POST['Grade']);
$type = $_POST['Type'];
$teamgrade = $_POST['TeamGrade'];
$current_year = $_SESSION['year'];
$season = $_SESSION['season'];
$round = $_POST['RoundSelected'];
$title = $_POST['RoundTitle'];
$current_date = date("Y-m-d H:m:s");

//echo("Round Selected " . $round . "<br>");
//echo("Round Title " . $title . "<br>");

$dt = new DateTime($current_date);
$tz = new DateTimeZone('Australia/Melbourne');
$dt->setTimezone($tz);
$current_time = $dt->format('Y-m-d H:m:s');

// get settings from grade settings table
$sql_grades = "Select * From tbl_team_grade Where grade = '" . $teamgrade . "'";
$result_grades = $dbcnx_client->query($sql_grades) or die("Couldn't execute settings query. " . mysqli_error($dbcnx_client));
$build_grades = $result_grades->fetch_assoc();

$NoOfFixtures = $build_grades['no_of_matches'];
$NoOfRounds = $build_grades['no_of_rounds'];
$no_of_games = $build_grades['games_round'];

// get club names from fixtures
$sql = "Show columns FROM tbl_fixtures";
$result_fields = $dbcnx_client->query($sql) or die("Couldn't execute tables query. " . mysqli_error($dbcnx_client));
$fixture_array = [];
$x = 0;
while($build_columns = $result_fields->fetch_assoc())
{
    if(substr($build_columns['Field'],0, 3) == 'fix')
    {
        $fixture_array[$x] = $build_columns['Field'];
    }
    $x++;
}
//$result_fields->free_result();

$i = ($round-1);
$sql_fix = "Select * from tbl_fixtures where round = " . ($i+1) . " AND team_grade = '" . $teamgrade . "' AND year = " . $current_year;
//echo("Fixtures " .$sql_fix . "<br>");
$result_fix = $dbcnx_client->query($sql_fix) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
$build_fix = $result_fix->fetch_assoc();
$date_played = $build_fix['date'];
//$result_fix->free_result();

foreach($fixture_array as $fixture_no)
{
    $sql_update_clean = "Update `Team_entries` SET `Result_pos` = NULL, `Result_score` = NULL, `HB` = '', `Countback` = 0, `audited` = 'No' WHERE `team_name`='" . $build_fix[$fixture_no] . "' and team_grade='" . $teamgrade . "'";
    //$sql_update_clean = "Update `Team_entries` SET `Result_pos` = NULL, `Result_score` = NULL, `HB` = '', `Countback` = NULL, `audited` = 'No' WHERE `team_id`=" . $teamID;
    // countback cannot be NULL
    //echo("Clean Team Entries " . $sql_update_clean . "<br>");
    $update = $dbcnx_client->query($sql_update_clean);
    if(!$update )
    {
        die("Could not update clean team data: " . mysqli_error($dbcnx_client));
    } 
}

$y = 0; // result position
foreach($fixture_array as $fixture_no)
{
    if($build_fix[$fixture_no] != '')
    {
        //echo($fixture_no . "<br>");
        $away_field = substr($fixture_no, 0, 4) . 'away';
        $home_field = substr($fixture_no, 0, 4) . 'home';
        $home_team = $build_fix[$home_field];
        $away_team = $build_fix[$away_field];
        //echo("Team Name " . $build_fix[$fixture_no] . "<br>");
        //echo("Home Name " . $home_team . "<br>");
        //echo("Away Name " . $away_team . "<br>");
        //if($away_team == 'Bye')

        if($build_fix[$fixture_no] == 'Bye')
        //if($build_fix[$fixture_no] == strtoupper('Bye'))
        {
            //echo("Start of Bye process (Round " . $round . ")<br>");

            // get home team data
            //$sql_home_team = "Select team_id, team_club_id, team_club from Team_entries where team_name = '" . $home_team . "' and team_grade = '" . $teamgrade . "' AND '" . $date_played . "' < '" . $current_date . "' Order By team_id DESC LIMIT 1";
            $sql_home_team = "Select team_id, team_club_id, team_club from Team_entries where team_name = '" . $home_team . "' and team_grade = '" . $teamgrade . "' Order By team_id DESC LIMIT 1";
            //echo("Get Home Bye Team data " . $sql_home_team . "<br>");
            $result_home_team = $dbcnx_client->query($sql_home_team) or die("Couldn't execute bye round no query. " . mysqli_error($dbcnx_client));
            $build_home_team = $result_home_team->fetch_assoc();

            //$result_team->free_result();
            $home_team_id = $build_home_team['team_id'];
            //echo("Bye Home Team ID " . $home_team_id . ", Name " . $home_team . "<br>"); //OK

            // get away team data
            //$sql_away_team = "Select team_id, team_club_id, team_club from Team_entries where team_name = '" . $away_team . "' and team_grade = '" . $teamgrade . "' AND '" . $date_played . "' < '" . $current_date . "' Order By team_id DESC LIMIT 1";
            $sql_away_team = "Select team_id, team_club_id, team_club from Team_entries where team_name = '" . $away_team . "' and team_grade = '" . $teamgrade . "' Order By team_id DESC LIMIT 1";
            //echo("Get Away Bye Team data " . $sql_away_team . "<br>");
            $result_away_team = $dbcnx_client->query($sql_away_team) or die("Couldn't execute bye round no query. " . mysqli_error($dbcnx_client));
            $build_away_team = $result_away_team->fetch_assoc();

            //$result_team->free_result();
            $away_team_id = $build_away_team['team_id'];
            //echo("Bye Away Team ID " . $away_team_id . ", Name " . $away_team . "<br>"); //OK

            // check if home team already entered....
            $sql_check_home = "Select * from scrs where memberID = 1 and team_grade = '" . $teamgrade . "' and team_id = " . $home_team_id;
            //echo("Check Home " . $sql_check_home . "<br>"); //OK
            $result_check_home_team = $dbcnx_client->query($sql_check_home) or die("Couldn't execute bye round no query. " . mysqli_error($dbcnx_client));
            $num_rows_home = $result_check_home_team->num_rows;
            //echo("Rows Home = " . $num_rows_home . "<br>");

            // insert player Bye into home team
            if($num_rows_home == 0)
            {
                $sql_home_scrs = "Insert INTO scrs (MemberID, team_grade, allocated_rp, game_type, scr_season, team_id, maxpts, final_sub, fin_year_scrs, current_year_scrs) VALUES (1, '" . $teamgrade . "', 80, '" . $type . "', '" . $season . "', " . $home_team_id . ", " . ($i+1) . ", 'No', '" . $current_year . "', '" . $current_year . "')";
                //echo("Sql Insert SCRS " . $sql_home_scrs . "<br>");
                $update = $dbcnx_client->query($sql_home_scrs);
            }
            
            // check if away team already entered....
            $sql_check_away = "Select * from scrs where memberID = 1 and team_grade = '" . $teamgrade . "' and team_id = " . $away_team_id;
            //echo("Check Away " . $sql_check_away . "<br>"); //OK
            $result_check_away_team = $dbcnx_client->query($sql_check_away) or die("Couldn't execute bye round no query. " . mysqli_error($dbcnx_client));
            $num_rows_away = $result_check_away_team->num_rows;
            //echo("Rows Away = " . $num_rows_away . "<br>");

            // insert player Bye into away team
            if($num_rows_away == 0)
            {
                // insert player Bye into away team
                $sql_away_scrs = "Insert INTO scrs (MemberID, team_grade, allocated_rp, game_type, scr_season, team_id, maxpts, final_sub, fin_year_scrs, current_year_scrs) VALUES (1, '" . $teamgrade . "', 80, '" . $type . "', '" . $season . "', " . $away_team_id . ", " . ($i+1) . ", 'No', '" . $current_year . "', '" . $current_year . "')";
                //echo("Sql Insert SCRS " . $sql_away_scrs . "<br>");
                $update = $dbcnx_client->query($sql_away_scrs);
            }
            $date_played = $build_fix['date'];

            // check if player bye already entered in scoresheet
            $sql_bye = "Select * from tbl_scoresheet where (team = '" . $home_team . "' AND opposition = '" . $away_team . "') AND round = " . ($i+1) . " AND season = '" . $season . "' AND year = " . $current_year . " Order By playing_position";
            //echo("Sql Select Team " . $sql_bye . "<br>");
            $result_count_byes = $dbcnx_client->query($sql_bye) or die("Couldn't execute scoresheet query. " . mysqli_error($dbcnx_client));
            $num_rows_byes = $result_count_byes->num_rows;
            //echo("Rows " . $num_rows_byes . "<br>");
            $build_byes = $result_count_byes->fetch_assoc();
            if(($num_rows_byes == 0))
            { 
                // insert player Bye into hometeam
                $sql_home_scoresheet = "Insert into tbl_scoresheet (
                memberID,
                players_name, 
                playing_position, 
                team, 
                opposition, 
                year, 
                season, 
                type, 
                grade, 
                round, 
                date_played, 
                team_grade,
                firstname,
                team_id
                ) 
                VALUES (1, '" . 
                'Bye' . "', " . 
                1 . ", '" . 
                $home_team . "', '" . 
                $away_team . "', " . 
                $current_year . ", '" . 
                $season . "', '" . 
                $type . "', '" . 
                $grade . "', " . 
                ($i+1) . ", '" . 
                $date_played . "', '" . 
                $teamgrade . "', '" . 
                'Bye' . "', " . 
                $home_team_id . ")";  
                //echo("Bye Home " . $sql_home_scoresheet . "<br>");
                $update = $dbcnx_client->query($sql_home_scoresheet);
                if(!$update)
                {
                    die("Could not insert home team data: " . mysqli_error($dbcnx_client));
                } 
                // insert player Bye into away (bye) team
                $sql_away_scoresheet = "Insert into tbl_scoresheet (
                memberID,
                players_name, 
                playing_position, 
                team, 
                opposition, 
                year, 
                season, 
                type, 
                grade, 
                round, 
                date_played, 
                team_grade,
                firstname,
                team_id
                ) 
                VALUES (1, '" . 
                'Bye' . "', " . 
                1 . ", '" . 
                $away_team . "', '" . 
                $home_team . "', " . 
                $current_year . ", '" . 
                $season . "', '" . 
                $type . "', '" . 
                $grade . "', " . 
                ($i+1) . ", '" . 
                $date_played . "', '" . 
                $teamgrade . "', '" . 
                'Bye' . "', " . 
                $away_team_id . ")";
                //echo("Bye Away " . $sql_away_scoresheet . "<br>");
                $update = $dbcnx_client->query($sql_away_scoresheet);
                if(!$update)
                {
                    die("Could not insert bye team data: " . mysqli_error($dbcnx_client));
                } 
            } // end if scoresheet rows = 0


            // Update scrs data for home team
            //$sql_scrs = "Select members.MemberID, current_year_scrs, scr_season, scrsID from scrs, members where members.MemberID=scrs.MemberID AND (members.FirstName = 'Bye' AND members.LastName = '" . $lastname . "') AND current_year_scrs = " . $current_year. "  AND scr_season = '" . $season . "' AND team_grade = '" . $teamgrade . "' and team_id = " . $home_team_id;

            $sql_scrs = "Select scrsID from scrs where MemberID = 1 AND current_year_scrs = " . $current_year. " AND scr_season = '" . $season . "' AND team_grade = '" . $teamgrade . "' and team_id = " . $home_team_id;
            //echo("Get Home Bye Scores data " . $sql_scrs . "<br>");
            //echo($home_team . " ID " . $home_team_id . "<br>");
            
            $result_scrs = $dbcnx_client->query($sql_scrs) or die("Couldn't execute bye scores query. " . mysqli_error($dbcnx_client));
            //$num_rows_scrs = $result_scrs->num_rows;
            $build_scrs = $result_scrs->fetch_assoc();

            // format round number
            if($i > 8)
            {
                $rnd_no = ($i+1);
            }
            else
            {
                $rnd_no = '0' . ($i+1);
            }

            if($type == 'Snooker')
            {
                $sql_scores = "Update scrs Set 
                r" . $rnd_no . "s = 6, 
                r" . $rnd_no . "pos = 0 
                where scrsID = " . $build_scrs['scrsID'];
            }
            elseif($type == 'Billiards')
            {
                $sql_scores = "Update scrs Set 
                r" . $rnd_no . "s = 4, 
                r" . $rnd_no . "pos = 0 
                where scrsID = " . $build_scrs['scrsID'];
            }
            //echo("SCRS Home Bye Update " . $sql_scores . "<br>");
            $update = $dbcnx_client->query($sql_scores);
            if(!$update)
            {
                die("Could not update bye scores data: " . mysqli_error($dbcnx_client));
            }
            
            // Update scrs data for away (Bye) team
            //$sql_scrs = "Select members.MemberID, current_year_scrs, scr_season, scrsID from scrs, members where members.MemberID=scrs.MemberID AND (members.FirstName = '" . $firstname . "' AND members.LastName = '" . $lastname . "') AND current_year_scrs = " . $current_year. "  AND scr_season = '" . $season . "' AND team_grade = '" . $teamgrade . "' and team_id = " . $home_team_id;

            $sql_scrs = "Select * FROM scrs where current_year_scrs = " . $current_year. " and team_grade = '" . $teamgrade . "' and MemberID = 1 and team_id = " . $away_team_id;

            //echo("Get Away Bye Scores data " . $sql_scrs . "<br>");
            //echo($away_team . " ID " . $away_team_id . "<br>");

            
            $result_scrs = $dbcnx_client->query($sql_scrs) or die("Couldn't execute bye scores query. " . mysqli_error($dbcnx_client));
            //$num_rows_scrs = $result_scrs->num_rows;
            $build_scrs = $result_scrs->fetch_assoc();
            //echo("Scores ID " . $build_scrs['scrsID'] . "<br>");

            if($type == 'Snooker')
            {
                $sql_scores = "Update scrs Set 
                r" . $rnd_no . "s = 6, 
                r" . $rnd_no . "pos = 0 
                where scrsID = " . $build_scrs['scrsID'];
            }
            elseif($type == 'Billiards')
            {
                $sql_scores = "Update scrs Set 
                r" . $rnd_no . "s = 4, 
                r" . $rnd_no . "pos = 0 
                where scrsID = " . $build_scrs['scrsID'];
            }
            //echo("SCRS Away Bye Update " . $sql_scores . "<br>");
            $update = $dbcnx_client->query($sql_scores);
            if(!$update)
            {
                die("Could not update bye scores data: " . mysqli_error($dbcnx_client));
            }


            // save club results
            // check if data already entered
            $sql_select_club = "Select * from tbl_club_results where club = '" . $home_team . "' AND round = " . ($i+1) . " AND team_grade = '" . $teamgrade . "'  AND season = '" . $season . "' AND year = " . $current_year;
            $result_select_club = $dbcnx_client->query($sql_select_club) or die("Couldn't execute club results query. " . mysqli_error($dbcnx_client));
            //echo("Club Byes " . $sql_select_club . "<br>");
            $num_rows_select_club = $result_select_club->num_rows;
            $build_club_data = $result_select_club->fetch_assoc();
            //$result_select_club->free_result();

            if($type == 'Snooker')
            {
                $games_won = 6;
                $overall_points = 2;
            }
            elseif($type == 'Billiards')
            {
                $games_won = 4;
                $overall_points = 2;
            }
            if(($title == 'Semi Final') || ($title == 'Grand Final'))
            {
                $audited = 'Yes';
            }
            else
            {
                $audited = 'No';
            }
            
            // Update team data for home team
            $sql_home_total = "Select SUM(IFNULL(scrs.r01s,0)) + SUM(IFNULL(scrs.r02s,0)) + SUM(IFNULL(scrs.r03s,0)) + SUM(IFNULL(scrs.r04s,0)) + SUM(IFNULL(scrs.r05s,0)) + SUM(IFNULL(scrs.r06s,0)) + SUM(IFNULL(scrs.r07s,0)) + SUM(IFNULL(scrs.r08s,0)) + SUM(IFNULL(scrs.r09s,0)) + SUM(IFNULL(scrs.r10s,0)) + SUM(IFNULL(scrs.r11s,0)) + SUM(IFNULL(scrs.r12s,0)) + SUM(IFNULL(scrs.r13s,0)) + SUM(IFNULL(scrs.r14s,0)) + SUM(IFNULL(scrs.r15s,0)) + SUM(IFNULL(scrs.r16s,0)) + SUM(IFNULL(scrs.r17s,0)) + SUM(IFNULL(scrs.r18s,0)) AS team_total FROM scrs WHERE team_id = " . $home_team_id;
            //echo("Home Team ID " . $home_team_id . "<br>");
            //echo("Home Team sql " . $sql_home_total . "<br>");
            $result_home_total = $dbcnx_client->query($sql_home_total) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
            $build_home_total = $result_home_total->fetch_assoc();
            //echo("Home Totals " . $build_home_total['total_score'] . "<br>");

            $sql_home_team_entry = "Update Team_entries Set
            team_name = '" . $home_team . "',
            total_score = " . $build_home_total['team_total'] . ",
            Result_pos = " . $y . ", 
            Result_score = " . $games_won . ",
            Updated = '" . $current_time . "',
            HB = '',
            audited = '" . $audited . "'
            where team_id = " . $home_team_id . " and team_grade='" . $teamgrade . "'";  
            //echo("Team Entries Update Home (bye) " . $sql_home_team_entry ."<br>");
            $update = $dbcnx_client->query($sql_home_team_entry);
            if(!$update )
            {
                die("Could not update home team data: " . mysqli_error($dbcnx_client));
            } 

            // Update team data for away (Bye) team
            $sql_away_total = "Select SUM(IFNULL(scrs.r01s,0)) + SUM(IFNULL(scrs.r02s,0)) + SUM(IFNULL(scrs.r03s,0)) + SUM(IFNULL(scrs.r04s,0)) + SUM(IFNULL(scrs.r05s,0)) + SUM(IFNULL(scrs.r06s,0)) + SUM(IFNULL(scrs.r07s,0)) + SUM(IFNULL(scrs.r08s,0)) + SUM(IFNULL(scrs.r09s,0)) + SUM(IFNULL(scrs.r10s,0)) + SUM(IFNULL(scrs.r11s,0)) + SUM(IFNULL(scrs.r12s,0)) + SUM(IFNULL(scrs.r13s,0)) + SUM(IFNULL(scrs.r14s,0)) + SUM(IFNULL(scrs.r15s,0)) + SUM(IFNULL(scrs.r16s,0)) + SUM(IFNULL(scrs.r17s,0)) + SUM(IFNULL(scrs.r18s,0)) AS team_total FROM scrs WHERE team_id = " . $away_team_id;
            //echo("Away Team sql " . $sql_away_total . "<br>");
            //echo("Away Team ID " . $away_team_id . "<br>");
            $result_away_total = $dbcnx_client->query($sql_away_total) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
            $build_away_total = $result_away_total->fetch_assoc();
            //echo("Away Totals " . $build_away_total['team_total'] . "<br>");

            $sql_away_team_entry = "Update Team_entries Set
            team_name = '" . $away_team . "',
            total_score = " . $build_away_total['team_total'] . ",
            Result_pos = " . ($y+1) . ", 
            Result_score = " . $games_won . ",
            Updated = '" . $current_time . "',
            HB = '',
            audited = '" . $audited . "'
            where team_id = " . $away_team_id . " and team_grade='" . $teamgrade . "'";   
            //echo("Team Entries Update Away (bye) " . $sql_away_team_entry ."<br>");
            $update = $dbcnx_client->query($sql_away_team_entry);
            if(!$update )
            {
                die("Could not update team data: " . mysqli_error($dbcnx_client));
            } 
            
            // Update scrs data for home team
            if(($num_rows_select_club == 0))
            {
                // Insert club results data for home team
                $sql_home_club = "Insert into tbl_club_results (
                club, 
                team_grade,
                season, 
                year, 
                round, 
                date_played,
                overall_points,
                games_won
                ) 
                VALUES ('" . 
                $home_team . "', '" . 
                $teamgrade . "', '" . 
                $season . "', '" . 
                $current_year . "', '" . 
                ($i+1) . "', '" . 
                $date_played . "', " .
                $overall_points . ", " .
                $games_won . ")"; 
                //echo("Club Home (Bye) " . $sql_home_club . "<br>");
                $update = $dbcnx_client->query($sql_home_club);
                if(!$update)
                {
                    die("Could not insert home club data: " . mysqli_error($dbcnx_client));
                } 
                $sql_away_club = "Insert into tbl_club_results (
                club, 
                team_grade,
                season, 
                year, 
                round, 
                date_played,
                overall_points,
                games_won
                ) 
                VALUES ('" . 
                $away_team . "', '" . 
                $teamgrade . "', '" . 
                $season . "', '" . 
                $current_year . "', '" . 
                ($i+1) . "', '" . 
                $date_played . "', " .
                $overall_points . ", " .
                $games_won . ")"; 
                $update = $dbcnx_client->query($sql_away_club);
                //echo("Club Away (Bye) " . $sql_away_club . "<br>");
                if(!$update)
                {
                    die("Could not insert away club data: " . mysqli_error($dbcnx_client));
                } 
            }
            else
            {
                $sql_clubs_home = "Update tbl_club_results Set 
                club = '" . $home_team . "', 
                overall_points = " . $overall_points . ", 
                games_won = " . $games_won . ", 
                team_grade = '" . $teamgrade . "', 
                season = '" . $season . "', 
                year = " . $current_year . ",  
                round = " . ($i+1) . "
                where club = '" . $home_team . "' AND date_played = '" . $date_played . "'";  
                //echo($sql_clubs_home . "<br>");
                $update = $dbcnx_client->query($sql_clubs_home);
                if(!$update )
                {
                    die("Could not player update data: " . mysqli_error($dbcnx_client));
                } 

                // added to update rather than the scoresheet updating
                $sql_clubs_away = "Update tbl_club_results Set 
                club = '" . $away_team . "', 
                overall_points = " . $overall_points . ", 
                games_won = " . $games_won . ", 
                team_grade = '" . $teamgrade . "', 
                season = '" . $season . "', 
                year = " . $current_year . ",  
                round = " . ($i+1) . "
                where club = '" . $away_team . "' AND date_played = '" . $date_played . "'";  
                //echo($sql_clubs_away . "<br>");
                $update = $dbcnx_client->query($sql_clubs_away);
                if(!$update )
                {
                    die("Could not club update data: " . mysqli_error($dbcnx_client));
                } 
            } // end of club results rows

            //echo("End of Bye process (Round " . $round . ")<br>");
        } // end of bye



        // do not update Bye Home Team (already done)
        if($away_team == 'Bye')
        {
            //echo("Home Name " . $home_team . "<br>");
            //echo("Away Name " . $away_team . "<br>");
            //echo("Fixture Name " . $home_team . " has already been updated<br>");
            $team_to_miss = $home_team;
        }
        if($home_team != $team_to_miss)
        {
            // get team data
            $sql_team = "Select team_id, team_club_id, team_club from Team_entries where team_name = '" . $build_fix[$fixture_no] . "' and team_grade = '" . $teamgrade . "' AND '" . $date_played . "' < '" . $current_date . "' Order By team_id DESC LIMIT 1";
            //$sql_team = "Select team_id, team_club_id, team_club from Team_entries where team_name = '" . $build_fix[$fixture_no] . "' and team_grade = '" . $teamgrade . "' Order By team_id DESC LIMIT 1";
            //echo("Get Team data " . $sql_team . "<br>");
            $result_team = $dbcnx_client->query($sql_team) or die("Couldn't execute round no query. " . mysqli_error($dbcnx_client));
            $build_team = $result_team->fetch_assoc();

            //$result_team->free_result();
            $teamID = $build_team['team_id'];
            //echo("Team ID " . $teamID . "<br>");
            if($teamID == '')
            {
                $teamID = 0;
            }
            $team_club = $build_team['team_club'];

            // format round number
            if($i > 8)
            {
                $rnd_no = ($i+1);
            }
            else
            {
                $rnd_no = '0' . ($i+1);
            }

            // get result position from fixture list
            $result_position = ($y+1);

            $sql_players = "Select * from tbl_scoresheet where team = '" . $build_fix[$fixture_no] . "' AND round = " . ($i+1) . " AND season = '" . $season . "' AND year = " . $current_year . " AND team_grade = '" . $teamgrade . "'  Order By playing_position";

            //echo("Get Scoresheet data " . $sql_players . "<br>");
            $result_count_players = $dbcnx_client->query($sql_players) or die("Couldn't execute players query. " . mysqli_error($dbcnx_client));
            $num_count_players = $result_count_players->num_rows;
            while($build_data = $result_count_players->fetch_assoc())
            {
                $firstname = $build_data['firstname'];
                $lastname = $build_data['lastname'];
                // use teamID
                $team_id = $build_data['team_id'];
                $sql_scrs = "Select members.MemberID, current_year_scrs, scr_season, scrsID from scrs, members where members.MemberID=scrs.MemberID AND (members.FirstName = '" . $firstname . "' AND members.LastName = '" . $lastname . "') AND current_year_scrs = " . $current_year. "  AND scr_season = '" . $season . "' AND team_grade = '" . $teamgrade . "' and team_id = " . $team_id;
                //echo("Get Scores data " . $sql_scrs . "<br>");
                $result_scrs = $dbcnx_client->query($sql_scrs) or die("Couldn't execute scores query. " . mysqli_error($dbcnx_client));
                $num_rows_scrs = $result_scrs->num_rows;
                $build_scrs = $result_scrs->fetch_assoc();

                if($result_position <= 2)
                {
                    $semi_no = 1;
                }
                else
                {
                    $semi_no = 2;
                }
                if(($teamID > 0) && ($build_scrs['scrsID'] != ''))
                {
                    if($title == 'Semi Final')
                    {
                        if($type == 'Snooker')
                        {
                            // semi final
                            $sql_finals_scrs_update = "Update scrs Set
                            SF" . $semi_no . " = " . ($build_data['win_1']+$build_data['win_2']+$build_data['win_3']+$build_data['win_4']) . ", 
                            SF" . $semi_no . "_pos = " . $build_data['playing_position'] . " 
                            WHERE scrsID = " . $build_scrs['scrsID'];
                            //echo("Finals Scrs (SF Snooker) Update - " . $sql_finals_scrs_update . "<br>");
                            $update = $dbcnx_client->query($sql_finals_scrs_update);
                            if(!$update )
                            {
                                die("Could not update finals data: " . mysqli_error($dbcnx_client));
                            } 
                        }
                        if($type == 'Billiards')
                        {
                            // semi final
                            $sql_finals_scrs_update_bill = "Update scrs Set SF" . $semi_no . " = " . (($build_data['win_1']*2)+($build_data['draw_1']*2)) . ", SF" . $semi_no . "_pos = " . $build_data['playing_position'] . " WHERE scrsID = " . $build_scrs['scrsID'];
                            //echo("Finals Scrs (SF Billiards) Update - " . $sql_finals_scrs_update_bill . "<br>");
                            $update = $dbcnx_client->query($sql_finals_scrs_update_bill);
                            if(!$update )
                            {
                                die("Could not update finals data: " . mysqli_error($dbcnx_client));
                            } 
                        }
                        
                    }
                    elseif($title == 'Grand Final')
                    {
                        // grand final
                        $sql_finals_scrs_update = "Update scrs Set
                        GF = " . $games_won . ", 
                        GF_pos = " . $games_won . "
                        where team_grade = '" . $teamgrade . "' and team_id = '" . $team_id . "'
                        and scr_season = '" . $season . "' and current_year_scrs = " . $current_year;
                        //echo("Finals Scrs (GF) Update - " . $sql_finals_scrs_update . "<br>");
                        $update = $dbcnx_client->query($sql_finals_scrs_update);
                        if(!$update )
                        {
                            die("Could not update finals data: " . mysqli_error($dbcnx_client));
                        } 
                    }  
                    elseif(($title != 'Semi Final') && ($title != 'Grand Final'))
                    {              
                        if($type == 'Snooker')
                        {
                            $sql_scores = "Update scrs Set 
                            r" . $rnd_no . "s = " . ($build_data['win_1']+$build_data['win_2']+$build_data['win_3']) . ", 
                            r" . $rnd_no . "pos = " . $build_data['playing_position'] . " 
                            where scrsID = " . $build_scrs['scrsID'];
                        }
                        elseif($type == 'Billiards')
                        {
                            $sql_scores = "Update scrs Set 
                            r" . $rnd_no . "s = " . (($build_data['win_1']*2)+($build_data['draw_1']*2)) . ", 
                            r" . $rnd_no . "pos = " . $build_data['playing_position'] . " 
                            where scrsID = " . $build_scrs['scrsID'];
                        }
                        echo("SCRS Update (Finals)" . $sql_scores . "<br>");
                        $update = $dbcnx_client->query($sql_scores);
                        if(!$update)
                        {
                            die("Could not update scores data: " . mysqli_error($dbcnx_client));
                        }
                    }
                }
            }

            //}
            // save breaks
            $sql_breaks = "Select * from tbl_scoresheet where team = '" . $build_fix[$fixture_no] . "' AND season = '" . $season . "' AND team_grade = '" . $teamgrade . "' AND year = " . $current_year . " Order By playing_position";
            //$sql_breaks = "Select * from tbl_scoresheet where team = '" . $build_fix[$fixture_no] . "' AND round = " . ($i+1) . " AND season = '" . $season . "' AND year = " . $current_year . " Order By playing_position";
            //echo("Get Scoresheet data " . $sql_breaks . "<br>");
            $result_breaks = $dbcnx_client->query($sql_breaks) or die("Couldn't execute breaks query. " . mysqli_error($dbcnx_client));
            while($build_breaks_data = $result_breaks->fetch_assoc())
            {
                if($teamID > 0)
                {
                    $sql_breaks_exist = "Select * from breaks where brk_team_id = " . $build_breaks_data['team_id'] . " AND grade = '" . $teamgrade . "'  AND recvd = '" . $build_breaks_data['date_played'] . "' AND season = '" . $season . "' AND member_ID_brks = " . $build_breaks_data['memberID'];
                    //echo("Select from Breaks - " . $sql_breaks_exist . "<br>");
                    $result_breaks_exist = $dbcnx_client->query($sql_breaks_exist) or die("Couldn't execute breaks query. " . mysqli_error($dbcnx_client));
                    $num_rows_breaks = $result_breaks_exist->num_rows;
                    if($build_breaks_data['type'] == 'Snooker')
                    {
                        $no_of_frames = 3;
                        for($k = 0; $k < $no_of_frames; $k++) // number of frames in snooker/billiards
                        {
                            //echo("Break Value " . $build_breaks_data['break_' . ($k+1)] . "<br>");
                            if(($num_rows_breaks == 0) && ($build_breaks_data['break_' . ($k+1)] > 0))
                            {
                                $sql_breaks_insert = "Insert into breaks (
                                member_ID_brks, 
                                brk, 
                                grade, 
                                brk_team_id, 
                                brk_type,
                                finals_brk,
                                recvd,
                                season
                                ) 
                                VALUES (" . 
                                $build_breaks_data['memberID'] . ", '" . 
                                $build_breaks_data['break_' . ($k+1)] . "', '" . 
                                $build_breaks_data['team_grade'] . "', " . 
                                $build_breaks_data['team_id'] . ", '" . 
                                $build_breaks_data['type'] . "', '" . 
                                "No', '" . 
                                $build_breaks_data['date_played'] . "', '" .
                                $season . "')"; 
                                //echo("Breaks Insert (Snooker) - " . $sql_breaks_insert .", round " . ($i+1) . "<br>");
                                $update = $dbcnx_client->query($sql_breaks_insert);
                                if(!$update)
                                {
                                    die("Could not update breaks data: " . mysqli_error($dbcnx_client));
                                } 
                            }
                        }
                    }
                    elseif($build_breaks_data['type'] == 'Billiards')
                    {
                        $no_of_frames = 1;
                    //}
                        for($k = 0; $k < $no_of_frames; $k++) // number of frames in snooker/billiards
                        {
                            //echo("Break Value " . $build_breaks_data['break_' . ($k+1)] . "<br>");
                            if(($num_rows_breaks == 0) && ($build_breaks_data['break_' . ($k+1)] > 0))
                            {
                                $arr_break = explode(" ", $build_breaks_data['break_' . ($k+1)]);
                                //echo("Count " . count($arr_break) . "<br>");

                                for($b = 0; $b < count($arr_break); $b++)
                                {
                                    //echo($arr_break[$b] . "<br>");
                                    $sql_breaks_insert = "Insert into breaks (
                                    member_ID_brks, 
                                    brk, 
                                    grade, 
                                    brk_team_id, 
                                    brk_type,
                                    finals_brk,
                                    recvd,
                                    season
                                    ) 
                                    VALUES (" . 
                                    $build_breaks_data['memberID'] . ", '" . 
                                    $arr_break[$b] . "', '" . 
                                    $build_breaks_data['team_grade'] . "', " . 
                                    $build_breaks_data['team_id'] . ", '" . 
                                    $build_breaks_data['type'] . "', '" . 
                                    "No', '" . 
                                    $build_breaks_data['date_played'] . "', '" .
                                    $season . "')"; 
                                    //echo("Breaks Insert (Billiards) - " . $sql_breaks_insert .", round " . ($i+1) . "<br>");
                                    $update = $dbcnx_client->query($sql_breaks_insert);
                                    if(!$update)
                                    {
                                        die("Could not update breaks data: " . mysqli_error($dbcnx_client));
                                    } 
                                }
                            }
                        }
                    }
                }
                //echo("End of save Breaks<br>");
            }   // end save breaks

            // calculate breaks
            $high_break = 0;
            if($away_team != "Bye")
            {
                $sql_players = "Select * from tbl_scoresheet where team = '" . $build_fix[$fixture_no] . "' AND round = " . ($i+1) . " AND season = '" . $season . "' AND year = " . $current_year . " Order By playing_position";
                //echo("Select from Scoresheet - " . $sql_players . "<br>");
                $result_count_players = $dbcnx_client->query($sql_players) or die("Couldn't execute player query. " . mysqli_error($dbcnx_client));
                // save breaks
                $count_players = $result_count_players->num_rows;
                while($build_data = $result_count_players->fetch_assoc())
                {
                    $array_breaks_1 = str_replace(" ", ", ", trim($build_data['break_1']));
                    $array_breaks_2 = str_replace(" ", ", ", trim($build_data['break_2']));
                    $array_breaks_3 = str_replace(" ", ", ", trim($build_data['break_3']));
                    $breaks1 = explode(", ", $array_breaks_1);
                    $breaks2 = explode(", ", $array_breaks_2);
                    $breaks3 = explode(", ", $array_breaks_3);
                    $highest_break[$build_data['playing_position']] = array('ID' => $build_data['memberID'], "Max" => max(array($breaks1[0], $breaks1[1], $breaks1[2], $breaks1[3], $breaks1[4], $breaks1[5], $breaks2[0], $breaks2[1], $breaks2[2], $breaks2[3], $breaks2[4], $breaks2[5], $breaks3[0], $breaks3[1], $breaks3[2], $breaks3[3], $breaks3[4], $breaks3[5])), "Date" => $build_data['date_played'], "Name" => $build_data['players_name']);
                    //$r++;
                }
                // add any highest breaks
                $max = 0;
                $high_break = 0;
                $high_break_id = '';
                $max_date = date("Y-m-d");
                $max_name = '';
                for($k = 0; $k < $count_players; $k++)
                //for($k = 0; $k < $no_of_players; $k++)
                {
                    $id_max = $highest_break[$k+1]["ID"];
                    $max_value = $highest_break[$k+1]["Max"];
                    $max_date = $highest_break[$k+1]["Date"];
                    $max_name = $highest_break[$k+1]["Name"];
                    //echo("Name " . $max_name . ", " . $max_value . "<br>");
                    if($max < $max_value)
                    {
                        $high_break_id = $id_max;
                        $high_break = $max_value;
                        $max = $max_value;
                        $high_break_name = $max_name;
                    }
                }
            } // end of breaks/players
        
            // update team_entries with Highest Breaks
            $sql_team_data = "Select * from tbl_club_results where club = '" . $build_fix[$fixture_no] . "' AND round = " . ($i+1) . " AND season = '" . $season . "' AND team_grade = '" . $teamgrade . "' AND year = " . $current_year;
            //echo("Club Results Select " . $sql_team_data ."<br>");
            $result_team_data = $dbcnx_client->query($sql_team_data) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
            $build_team_data = $result_team_data->fetch_assoc();
            $num_rows_team_data = $result_team_data->num_rows;
            if(($title == 'Semi Final') || ($title == 'Grand Final'))
            {
                $audited = 'Yes';
            }
            else
            {
                $audited = 'No';
            }
            // format round number
            if($i > 8)
            {
                $rnd_no = ($i+1);
            }
            else
            {
                $rnd_no = '0' . ($i+1);
            }
            if(($num_rows_team_data == 1) && ($build_team_data['round'] == $round))
            {
                if($high_break > 0)
                {
                    $HB = $high_break . " " . $high_break_name;
                }
                else
                {
                    $HB = '';
                }
                $sql_update_total = "Select SUM(IFNULL(scrs.r01s,0)) + SUM(IFNULL(scrs.r02s,0)) + SUM(IFNULL(scrs.r03s,0)) + SUM(IFNULL(scrs.r04s,0)) + SUM(IFNULL(scrs.r05s,0)) + SUM(IFNULL(scrs.r06s,0)) + SUM(IFNULL(scrs.r07s,0)) + SUM(IFNULL(scrs.r08s,0)) + SUM(IFNULL(scrs.r09s,0)) + SUM(IFNULL(scrs.r10s,0)) + SUM(IFNULL(scrs.r11s,0)) + SUM(IFNULL(scrs.r12s,0)) + SUM(IFNULL(scrs.r13s,0)) + SUM(IFNULL(scrs.r14s,0)) + SUM(IFNULL(scrs.r15s,0)) + SUM(IFNULL(scrs.r16s,0)) + SUM(IFNULL(scrs.r17s,0)) + SUM(IFNULL(scrs.r18s,0)) AS team_total FROM scrs WHERE team_id = " . $team_id;
                //echo("Team Total Select " . $sql_update_total . "<br>");
                $result_total = $dbcnx_client->query($sql_update_total) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
                $build_total = $result_total->fetch_assoc();
                $result_score = ($build_team_data['games_won']);

                //$result_ladder_position = $build_finals['Result_pos'];
                if($result_position <= 2)
                {
                    $semi_no = 1;
                }
                else
                {
                    $semi_no = 2;
                }

                if($title == 'Semi Final')
                {
                    // semi final
                    $sql_finals_update = "Update Team_entries Set
                    T" . $rnd_no . " = " . $build_team_data['overall_points'] . ", 
                    P" . $rnd_no . " = " . $build_team_data['games_won'] . ", 
                    SF" . $semi_no . "_pts = " . $build_team_data['overall_points'] . ", 
                    SF" . $semi_no . "tot = " . $build_team_data['games_won'] . ",
                    audited = '" . $audited . "'
                    where team_grade = '" . $teamgrade . "' and team_name = '" . $build_fix[$fixture_no] . "' and team_season = '" . $season . "' and team_cal_year = " . $current_year;
                    //echo("Finals Update (SF) - " . $sql_finals_update . "<br>");
                    $update = $dbcnx_client->query($sql_finals_update);
                    if(!$update )
                    {
                        die("Could not update finals data: " . mysqli_error($dbcnx_client));
                    } 
                }
                elseif($title == 'Grand Final')
                {
                    // grand final
                    $sql_grand_finals_update = "Update Team_entries Set
                    T" . $rnd_no . " = " . $build_team_data['overall_points'] . ", 
                    P" . $rnd_no . " = " . $build_team_data['games_won'] . ", 
                    GF_pts = " . $build_team_data['overall_points'] . ", 
                    GFtot = " . $build_team_data['games_won'] . ",
                    audited = '" . $audited . "'
                    where team_grade = '" . $teamgrade . "' and team_name = '" . $build_fix[$fixture_no] . "' and team_season = '" . $season . "' and team_cal_year = " . $current_year;
                    //echo("Finals Update (GF) - " . $sql_grand_finals_update . "<br>");
                    $update = $dbcnx_client->query($sql_grand_finals_update);
                    if(!$update )
                    {
                        die("Could not update finals data: " . mysqli_error($dbcnx_client));
                    } 
                }   
                else
                {
                    $sql_team_entry = "Update Team_entries Set
                    team_name = '" . $build_fix[$fixture_no] . "',
                    total_score = " . $build_total['team_total'] . ",
                    Result_pos = " . $result_position . ", 
                    Result_score = " . $result_score . ",
                    Updated = '" . $current_time . "',
                    HB = '" . $HB . "',
                    audited = '" . $audited . "'
                    where team_id = " . $team_id . " and team_grade='" . $teamgrade . "'";   
                    //echo("Team Entries Update (no forfeit or bye) " . $sql_team_entry ."<br>");
                    $update = $dbcnx_client->query($sql_team_entry);
                    if(!$update )
                    {
                        die("Could not update team data: " . mysqli_error($dbcnx_client));
                    } 
                }
            } 
        }

        // add finals scores
/*
        if(($title == 'Semi Final') || ($title == 'Grand Final'))
        {
            // format round number
            if($i > 8)
            {
                $rnd_no = ($i+1);
            }
            else
            {
                $rnd_no = '0' . ($i+1);
            }
            //echo("Start of Finals to save<br>");
            $sql_check_existing = "Select * FROM Team_entries where team_grade = '" . $teamgrade . "' and team_name = '" . $build_fix[$fixture_no] . "' and team_season = '" . $season . "' and team_cal_year = " . $current_year;
            //echo("Check Select " . $sql_check_existing . "<br>");
            $result_count_finals = $dbcnx_client->query($sql_check_existing) or die("Couldn't execute player query. " . mysqli_error($dbcnx_client));
            // save finals
            while($build_finals = $result_count_finals->fetch_assoc())
            {
                $result_ladder_position = $build_finals['Result_pos'];
                if($result_ladder_position <= 2)
                {
                    $semi_no = 1;
                }
                else
                {
                    $semi_no = 2;
                }
                $sql_scores = "Select * from tbl_club_results where club = '" . $build_finals['team_name'] . "' AND round = " . ($i+1) . " AND season = '" . $season . "' AND year = " . $current_year;
                //echo("Select Scores " . $sql_scores . "<br>");
                $result_scores = $dbcnx_client->query($sql_scores) or die("Couldn't execute scores query. " . mysqli_error($dbcnx_client));
                $count_finals = $result_scores->num_rows;
                //echo("Count Select " . $count_finals . "<br>");
                if($count_finals > 0)
                {
                    $build_scores = $result_scores->fetch_assoc();
                    $overall_points = $build_scores['overall_points'];
                    $games_won = $build_scores['games_won'];
                    $games_drawn = $build_scores['games_drawn'];

                    if($title == 'Semi Final')
                    {
                        // semi final
                        $sql_finals_update = "Update Team_entries Set
                        T" . $rnd_no . " = " . $overall_points . ", 
                        P" . $rnd_no . " = " . $games_won . ", 
                        SF" . $semi_no . "_pts = " . $overall_points . ", 
                        SF" . $semi_no . "tot = " . $games_won . "
                        where team_grade = '" . $teamgrade . "' and team_name = '" . $build_fix[$fixture_no] . "'
                        and team_season = '" . $season . "' and team_cal_year = " . $current_year;
                        //echo("Finals Update (SF) - " . $sql_finals_update . "<br>");
                        $update = $dbcnx_client->query($sql_finals_update);
                        if(!$update )
                        {
                            die("Could not update finals data: " . mysqli_error($dbcnx_client));
                        } 
                    }
                    elseif($title == 'Grand Final')
                    {
                        // grand final
                        $sql_finals_update = "Update Team_entries Set
                        T" . $rnd_no . " = '" . $overall_points . "', 
                        P" . $rnd_no . " = '" . $games_won . "', 
                        GF_pts = " . $overall_points . ", 
                        GFtot = " . $games_won . "
                        where team_grade = '" . $teamgrade . "' and team_name = '" . $build_fix[$fixture_no] . "'
                        and team_season = '" . $season . "' and team_cal_year = " . $current_year;
                        //echo("Finals Update (GF) - " . $sql_finals_update . "<br>");
                        $update = $dbcnx_client->query($sql_finals_update);
                        if(!$update )
                        {
                            die("Could not update finals data: " . mysqli_error($dbcnx_client));
                        } 
                    }   
                }             
            }
           
            $sql_players = "Select * from tbl_scoresheet where team = '" . $build_fix[$fixture_no] . "' AND round = " . ($i+1) . " AND season = '" . $season . "' AND year = " . $current_year . " AND team_grade = '" . $teamgrade . "'  Order By playing_position";

            //echo("Get Scoresheet finals data " . $sql_players . "<br>");
            $result_count_players = $dbcnx_client->query($sql_players) or die("Couldn't execute players query. " . mysqli_error($dbcnx_client));
            $num_count_players = $result_count_players->num_rows;
            while($build_data = $result_count_players->fetch_assoc())
            {
                $firstname = $build_data['firstname'];
                $lastname = $build_data['lastname'];
                // use teamID
                $team_id = $build_data['team_id'];
                $sql_scrs = "Select members.MemberID, current_year_scrs, scr_season, scrsID from scrs, members where members.MemberID=scrs.MemberID AND (members.FirstName = '" . $firstname . "' AND members.LastName = '" . $lastname . "') AND current_year_scrs = " . $current_year. "  AND scr_season = '" . $season . "' AND team_grade = '" . $teamgrade . "' and team_id = " . $team_id;
                //echo("Get Scores data " . $sql_scrs . "<br>");
                $result_scrs = $dbcnx_client->query($sql_scrs) or die("Couldn't execute scores query. " . mysqli_error($dbcnx_client));
                $num_rows_scrs = $result_scrs->num_rows;
                $build_scrs = $result_scrs->fetch_assoc();

                //echo("Scrs ID " . $build_scrs['scrsID']  . "<br>");
                //echo("Member ID " . $build_scrs['MemberID']  . "<br>");
                if(($teamID > 0) && ($build_scrs['scrsID'] != ''))
                {
                    if($title == 'Semi Final')
                    {
                        // semi final
                        $sql_finals_scrs_update = "Update scrs Set
                        SF" . $semi_no . " = " . ($build_data['win_1']+$build_data['win_2']+$build_data['win_3']+$build_data['win_4']) . ", 
                        SF" . $semi_no . "_pos = " . $build_data['playing_position'] . " 
                        WHERE scrsID = " . $build_scrs['scrsID'];
                        //echo("Finals Scrs (SF) Update - " . $sql_finals_scrs_update . "<br>");
                        $update = $dbcnx_client->query($sql_finals_scrs_update);
                        if(!$update )
                        {
                            die("Could not update finals data: " . mysqli_error($dbcnx_client));
                        } 
                    }
                    elseif($title == 'Grand Final')
                    {
                        // grand final
                        $sql_finals_scrs_update = "Update scrs Set
                        GF = " . $games_won . ", 
                        GF_pos = " . $games_won . "
                        where team_grade = '" . $teamgrade . "' and team_id = '" . $team_id . "'
                        and scr_season = '" . $season . "' and current_year_scrs = " . $current_year;
                        //echo("Finals Scrs (GF) Update - " . $sql_finals_scrs_update . "<br>");
                        $update = $dbcnx_client->query($sql_finals_scrs_update);
                        if(!$update )
                        {
                            die("Could not update finals data: " . mysqli_error($dbcnx_client));
                        } 
                    }                
                }
            }

            /*
            // TEAM ENTRIES FINALS Magic Calc.

            //tested ok calculates Team_entries SF1tot by (SELECT SUM(scrs.SF1)

            $querytoexecute = "Update `vbsa3364_vbsa2`.`Team_entries` T1 
            JOIN
            (   SELECT team_id, SUM(SF1) AS semi1tot 
              FROM scrs
              GROUP BY team_id
            ) T2
            SET T1.SF1tot = T2.semi1tot 
            WHERE T2.team_id = T1.team_id";

            $result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

            if (isset($result)) echo "<br><br><font face='arial' color='green'>1. Table was successfully updated - calculated Semi Final 1 totals OK</font>";

            //tested ok calculates Team_entries SF2tot by (SELECT SUM(scrs.SF2)

            $querytoexecute = "Update `vbsa3364_vbsa2`.`Team_entries` T1 
            JOIN
            (   SELECT team_id, SUM(SF2) AS semi2tot 
              FROM scrs
              GROUP BY team_id
            ) T2
            SET T1.SF2tot = T2.semi2tot 
            WHERE T2.team_id = T1.team_id";

            $result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

            if (isset($result)) echo "<br><br><font face='arial' color='green'>2. Table was successfully updated - calculated Semi Final 2 totals OK</font>";

            //tested ok calculates Team_entries PFtot by (SELECT SUM(scrs.GF)

            $querytoexecute = "Update `vbsa3364_vbsa2`.`Team_entries` T1 
            JOIN
            (   SELECT team_id, SUM(GF) AS Grandtot 
              FROM scrs
              GROUP BY team_id
            ) T2
            SET T1.GFtot = T2.Grandtot 
            WHERE T2.team_id = T1.team_id
            ";

            $result=mysql_query($querytoexecute, $connvbsa) or die("Table was not updated");

            if (isset($result)) echo "<br><br><font face='arial' color='green'>3. Table was successfully updated - calculated Grand Final totals OK</font>";

            // end TEAM ENTRIES FINALS
            */
            //echo("End of Finals to save<br>"); 
/*        }   */
    }
    $y++;  // result position
}

// start team forfeit
$sql_fix = "Select * from tbl_fixtures where round = " . ($i+1) . " AND team_grade = '" . $teamgrade . "' AND year = " . $current_year;
$result_fix = $dbcnx_client->query($sql_fix) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
$build_fix = $result_fix->fetch_assoc();
$date_played = $build_fix['date'];
//$result_fix->free_result();

$y = 0; // result position
foreach($fixture_array as $fixture_no)
{
    //echo("Round No. " . ($i+1) . "<br>");
    if($build_fix[$fixture_no] != '')
    {
        $away_field = substr($fixture_no, 0, 4) . 'away';
        $home_field = substr($fixture_no, 0, 4) . 'home';
        $home_team = $build_fix[$home_field];
        $away_team = $build_fix[$away_field];
        $no_of_players = 4;
        //echo("Home " . $home_team . ", Away " . $away_team . "<br>");
        // update home team of team forfeit

        // added to update team forfeit scores
        $sql_forfeit = "Select * from tbl_scoresheet where (team = '" . $home_team . "' or team = '" . $away_team . "') AND round = " . ($i+1) . " AND season = '" . $season . "' AND year = " . $current_year . " AND FirstName = 'Team' AND LastName = 'Forfeit'";
        //echo("Get Scoresheet Home Data " . $sql_forfeit . "<br>");
        $result_forfeit = $dbcnx_client->query($sql_forfeit) or die("Couldn't execute players query. " . mysqli_error($dbcnx_client));

        while($build_forfeit = $result_forfeit->fetch_assoc())
        {
            $firstname = 'Team';
            $lastname = 'Forfeit';
            $team_id = $build_forfeit['team_id'];
            //echo("Team ID " . $team_id . "<br>");
            $sql_scrs = "Select members.MemberID, current_year_scrs, scr_season, scrsID from scrs, members where members.MemberID=scrs.MemberID AND (members.FirstName = '" . $firstname . "' AND members.LastName = '" . $lastname . "') AND current_year_scrs = " . $current_year. "  AND scr_season = '" . $season . "' AND team_grade = '" . $teamgrade . "' and team_id = " . $team_id;
            //echo("Get scrs ID " . $sql_scrs . "<br>");
            $result_scrs = $dbcnx_client->query($sql_scrs) or die("Couldn't execute scores query. " . mysqli_error($dbcnx_client));
            $build_scrs = $result_scrs->fetch_assoc();
            //echo("scrs ID " . $build_scrs['scrsID'] . "<br>");
            // format round number
            if($i > 8)
            {
                $rnd_no = ($i+1);
            }
            else
            {
                $rnd_no = '0' . ($i+1);
            }

            if(($team_id > 0) && ($build_scrs['scrsID'] != ''))
            {
                if($type == 'Snooker')
                {
                    $sql_scores = "Update scrs Set 
                    r" . $rnd_no . "s = -4, 
                    r" . $rnd_no . "pos = 0 
                    where scrsID = " . $build_scrs['scrsID'];
                }
                elseif($type == 'Billiards')
                {
                    $sql_scores = "Update scrs Set 
                    r" . $rnd_no . "s = -4, 
                    r" . $rnd_no . "pos = 0 
                    where scrsID = " . $build_scrs['scrsID'];
                }
                //echo("SCRS Forfeit Update " . $sql_scores . "<br>");
                $update = $dbcnx_client->query($sql_scores);
                if(!$update)
                {
                    die("Could not update scores data: " . mysqli_error($dbcnx_client));
                }
            }
       // } // end 
        }
    }
}
     
// calculate total adjustement score
foreach($fixture_array as $fixture_no)
{
    // get total score for forfeit team 
    $sql_update_total = "Select SUM(IFNULL(scrs.r01s,0)) + SUM(IFNULL(scrs.r02s,0)) + SUM(IFNULL(scrs.r03s,0)) + SUM(IFNULL(scrs.r04s,0)) + SUM(IFNULL(scrs.r05s,0)) + SUM(IFNULL(scrs.r06s,0)) + SUM(IFNULL(scrs.r07s,0)) + SUM(IFNULL(scrs.r08s,0)) + SUM(IFNULL(scrs.r09s,0)) + SUM(IFNULL(scrs.r10s,0)) + SUM(IFNULL(scrs.r11s,0)) + SUM(IFNULL(scrs.r12s,0)) + SUM(IFNULL(scrs.r13s,0)) + SUM(IFNULL(scrs.r14s,0)) + SUM(IFNULL(scrs.r15s,0)) + SUM(IFNULL(scrs.r16s,0)) + SUM(IFNULL(scrs.r17s,0)) + SUM(IFNULL(scrs.r18s,0)) AS team_total FROM scrs WHERE team_id = " . $team_id;
    //echo("Team Total Select " . $sql_update_total . "<br>");
    $result_total = $dbcnx_client->query($sql_update_total) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
    $build_total = $result_total->fetch_assoc();
    $total_score = $build_total['team_total'];

    $adjustment_total = 0;
    for($x = 0; $x < $round; $x++)
    {
        $sql_forfeit = "Select * from tbl_scoresheet where team = '" . $build_fix[$fixture_no] . "' AND round = " . ($x+1) . " AND season = '" . $season . "' AND year = " . $current_year . " AND FirstName = 'Team' AND LastName = 'Forfeit'";
        $result_forfeit = $dbcnx_client->query($sql_forfeit) or die("Couldn't execute players query. " . mysqli_error($dbcnx_client));
        while($build_forfeit = $result_forfeit->fetch_assoc())
        {
            if($type == "Snooker")
            {
                $max_points_available = ($no_of_players*$no_of_games); //12
                $home_points_available = ($no_of_players*2); //8
                $away_points_available = -4;
                $adjustment_score = ($max_points_available+$away_points_available); // 8
            }
            elseif($type == "Billiards")
            {
                $max_points_available = (($no_of_players*$no_of_games)*2); //8
                $home_points_available = (($no_of_players-1)*2); //6
                $away_points_available = -4;
                //6 - 4 = 2 add 6 to get to 8
                $adjustment_score = ($max_points_available - ($home_points_available+$away_points_available)); // 6
            }
            $adjustment_total = ($adjustment_score+$adjustment_total);
            //echo("Calculated Adjustment " . $adjustment_score . ", Previous Adjustment " . $adjustment_total . "<br>");
            $sql_team_forfeit = "Update Team_entries Set
            day_played='" . $build_fix['dayplayed'] . "', 
            players='" . $no_of_players . "', 
            total_score=" . $total_score . ", 
            scr_adjust=" . $adjustment_total . ", 
            scr_adj_rd=" . ($x+1) . ", 
            Final5=4, 
            Result_score=" . $away_points_available . ", 
            HB=NULL, 
            adj_comment='Team Forfeit' 
            WHERE team_id=" . $team_id . " and team_grade='" . $teamgrade . "'";  
            //echo("Team Entries Home Forfeit Update " . $sql_team_forfeit ."<br>");
            $update = $dbcnx_client->query($sql_team_forfeit);
            if(!$update )
            {
                die("Could not update forfeit data: " . mysqli_error($dbcnx_client));
            }
        }
    }
}

?>
<center>
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
    <td align="center">The Records have been updated.</td>
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
    <td align="center">Please make a selection from the top menu.</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
</table>
</center>
<?php
include("footer.php"); 
?>