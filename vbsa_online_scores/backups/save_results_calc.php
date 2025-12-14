<?php

include('connection.inc');
include('header.php'); 

$grade = trim($_POST['Grade']);
$type = $_POST['Type'];
$teamgrade = $_POST['TeamGrade'];
$current_year = $_SESSION['year'];
$season = $_SESSION['season'];
$round = $_POST['RoundSelected'];
$current_date = date("Y-m-d H:m:s");

$dt = new DateTime($current_date);
$tz = new DateTimeZone('Australia/Melbourne');
$dt->setTimezone($tz);
$current_time = $dt->format('Y-m-d H:m:s');

//echo("Date " . $current_date . "<br>");
//echo("Current " . $current_time . "<br>");
//$current_time = date('Y-m-d H:i:s');

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
    $sql_update_clean = "Update `Team_entries` SET `Result_pos` = NULL, `Result_score` = NULL, `HB` = '', `Countback` = 0, `audited` = 'No' WHERE `team_name`='" . $build_fix[$fixture_no] . "'";
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
            $sql_home_team = "Select team_id, team_club_id, team_club from Team_entries where team_name = '" . $home_team . "' and team_grade = '" . $teamgrade . "' AND '" . $date_played . "' < '" . $current_date . "' Order By team_id DESC LIMIT 1";
            //echo("Get Home Bye Team data " . $sql_home_team . "<br>");
            $result_home_team = $dbcnx_client->query($sql_home_team) or die("Couldn't execute bye round no query. " . mysqli_error($dbcnx_client));
            $build_home_team = $result_home_team->fetch_assoc();

            //$result_team->free_result();
            $home_team_id = $build_home_team['team_id'];
            //echo("Bye Home Team ID " . $home_team_id . "<br>"); //OK

            // get away team data
            $sql_away_team = "Select team_id, team_club_id, team_club from Team_entries where team_name = '" . $away_team . "' and team_grade = '" . $teamgrade . "' AND '" . $date_played . "' < '" . $current_date . "' Order By team_id DESC LIMIT 1";
            //echo("Get Away Bye Team data " . $sql_away_team . "<br>");
            $result_away_team = $dbcnx_client->query($sql_away_team) or die("Couldn't execute bye round no query. " . mysqli_error($dbcnx_client));
            $build_away_team = $result_away_team->fetch_assoc();

            //$result_team->free_result();
            $away_team_id = $build_away_team['team_id'];
            //echo("Bye Away Team ID " . $away_team_id . "<br>"); //OK


            $sql_check_home = "Select * from scrs where memberID = 1 and team_grade = '" . $teamgrade . "' and team_id = " . $home_team_id;
            //echo("Check Home " . $sql_check_home . "<br>"); //OK
            $result_check_home_team = $dbcnx_client->query($sql_check_home) or die("Couldn't execute bye round no query. " . mysqli_error($dbcnx_client));
            $num_rows_home = $result_check_home_team->num_rows;
            //echo("Rows Home = " . $num_rows_home . "<br>");

            // insert player Bye into home team // check if already entered....
            if($num_rows_home == 0)
            {
                $sql_home_scrs = "Insert INTO scrs (MemberID, team_grade, allocated_rp, game_type, scr_season, team_id, maxpts, final_sub, fin_year_scrs, current_year_scrs) VALUES (1, '" . $teamgrade . "', 80, '" . $type . "', '" . $season . "', " . $home_team_id . ", " . ($i+1) . ", 'No', '" . $current_year . "', '" . $current_year . "')";
                //echo("Sql Insert SCRS " . $sql_home_scrs . "<br>");
                $update = $dbcnx_client->query($sql_home_scrs);
            }
            
            $sql_check_away = "Select * from scrs where memberID = 1 and team_grade = '" . $teamgrade . "' and team_id = " . $away_team_id;
            //echo("Check Away " . $sql_check_away . "<br>"); //OK
            $result_check_away_team = $dbcnx_client->query($sql_check_away) or die("Couldn't execute bye round no query. " . mysqli_error($dbcnx_client));
            $num_rows_away = $result_check_away_team->num_rows;
            //echo("Rows Away = " . $num_rows_away . "<br>");

            // insert player Bye into home team // check if already entered....
            if($num_rows_away == 0)
            {
                // insert player Bye into away team
                $sql_away_scrs = "Insert INTO scrs (MemberID, team_grade, allocated_rp, game_type, scr_season, team_id, maxpts, final_sub, fin_year_scrs, current_year_scrs) VALUES (1, '" . $teamgrade . "', 80, '" . $type . "', '" . $season . "', " . $away_team_id . ", " . ($i+1) . ", 'No', '" . $current_year . "', '" . $current_year . "')";
                //echo("Sql Insert SCRS " . $sql_away_scrs . "<br>");
                $update = $dbcnx_client->query($sql_away_scrs);
            }

            $date_played = $build_fix['date'];

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
                $overall_points = 6;
            }
            elseif($type == 'Billiards')
            {
                $overall_points = 4;
            }
            $games_won = 0;

            // Update team data for home team

            // check if total score is sum all overall points...................
            $sql_home_total = "Select SUM(IFNULL(scrs.r01s,0)) + SUM(IFNULL(scrs.r02s,0)) + SUM(IFNULL(scrs.r03s,0)) + SUM(IFNULL(scrs.r04s,0)) + SUM(IFNULL(scrs.r05s,0)) + SUM(IFNULL(scrs.r06s,0)) + SUM(IFNULL(scrs.r07s,0)) + SUM(IFNULL(scrs.r08s,0)) + SUM(IFNULL(scrs.r09s,0)) + SUM(IFNULL(scrs.r10s,0)) + SUM(IFNULL(scrs.r11s,0)) + SUM(IFNULL(scrs.r12s,0)) + SUM(IFNULL(scrs.r13s,0)) + SUM(IFNULL(scrs.r14s,0)) + SUM(IFNULL(scrs.r15s,0)) + SUM(IFNULL(scrs.r16s,0)) + SUM(IFNULL(scrs.r17s,0)) + SUM(IFNULL(scrs.r18s,0)) AS team_total, scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.r01s, scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s, scrs.r07s, scrs.r08s, scrs.r09s, scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s, scrs.r17s, scrs.r18s,Team_entries.team_id, Team_entries.team_club, Team_entries.team_name, Team_entries.team_grade, Team_entries.day_played, Team_entries.players, Team_entries.total_score, Team_entries.Final5, Team_entries.Updated, Team_entries.Result_pos, Team_entries.Result_score, Team_entries.HB, Team_entries.audited FROM Team_entries,scrs WHERE Team_entries.team_id=scrs.team_id AND Team_entries.team_id = " . $home_team_id;
            //echo("Home Total " . $sql_home_total . "<br>");
            $result_home_total = $dbcnx_client->query($sql_home_total) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
            $build_home_total = $result_home_total->fetch_assoc();
            //echo("Home Totals " . $build_home_total['total_score'] . "<br>");

            $sql_home_team_entry = "Update Team_entries Set
            team_name = '" . $home_team . "',
            total_score = " . $build_home_total['total_score'] . ",
            Result_pos = " . $y . ", 
            Result_score = " . $overall_points . ",
            Updated = '" . $current_time . "',
            HB = '',
            audited = 'No'
            where team_id = " . $home_team_id;  
            //echo("Team Entries Update Home (bye) " . $sql_home_team_entry ."<br>");
            $update = $dbcnx_client->query($sql_home_team_entry);
            if(!$update )
            {
                die("Could not update home team data: " . mysqli_error($dbcnx_client));
            } 

            // Update team data for away (Bye) team
            $sql_away_total = "Select SUM(IFNULL(scrs.r01s,0)) + SUM(IFNULL(scrs.r02s,0)) + SUM(IFNULL(scrs.r03s,0)) + SUM(IFNULL(scrs.r04s,0)) + SUM(IFNULL(scrs.r05s,0)) + SUM(IFNULL(scrs.r06s,0)) + SUM(IFNULL(scrs.r07s,0)) + SUM(IFNULL(scrs.r08s,0)) + SUM(IFNULL(scrs.r09s,0)) + SUM(IFNULL(scrs.r10s,0)) + SUM(IFNULL(scrs.r11s,0)) + SUM(IFNULL(scrs.r12s,0)) + SUM(IFNULL(scrs.r13s,0)) + SUM(IFNULL(scrs.r14s,0)) + SUM(IFNULL(scrs.r15s,0)) + SUM(IFNULL(scrs.r16s,0)) + SUM(IFNULL(scrs.r17s,0)) + SUM(IFNULL(scrs.r18s,0)) AS team_total, scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.r01s, scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s, scrs.r07s, scrs.r08s, scrs.r09s, scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s, scrs.r17s, scrs.r18s,Team_entries.team_id, Team_entries.team_club, Team_entries.team_name, Team_entries.team_grade, Team_entries.day_played, Team_entries.players, Team_entries.total_score, Team_entries.Final5, Team_entries.Updated, Team_entries.Result_pos, Team_entries.Result_score, Team_entries.HB, Team_entries.audited FROM Team_entries,scrs WHERE Team_entries.team_id=scrs.team_id AND Team_entries.team_id = " . $away_team_id;
            //echo("Away Total " . $sql_away_total . "<br>");
            $result_away_total = $dbcnx_client->query($sql_away_total) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
            $build_away_total = $result_away_total->fetch_assoc();
            //echo("Away Totals " . $build_away_total['total_score'] . "<br>");

            $sql_away_team_entry = "Update Team_entries Set
            team_name = '" . $away_team . "',
            total_score = " . $build_away_total['total_score'] . ",
            Result_pos = " . ($y+1) . ", 
            Result_score = " . $overall_points . ",
            Updated = '" . $current_time . "',
            HB = '',
            audited = 'No'
            where team_id = " . $away_team_id;  
            //echo("Team Entries Update Away (bye) " . $sql_away_team_entry ."<br>");
            $update = $dbcnx_client->query($sql_away_team_entry);
            if(!$update )
            {
                die("Could not update team data: " . mysqli_error($dbcnx_client));
            } 
            
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

            $sql_scrs = "Select * FROM vbsa3364_vbsa2.scrs where current_year_scrs = 2023 and team_grade = 'AVB' and memberid = 1 and team_id = " . $away_team_id;

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
                0 . ")"; 
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
                0 . ")"; 
                $update = $dbcnx_client->query($sql_away_club);
                //echo("Club Away (Bye) " . $sql_away_club . "<br>");
                if(!$update)
                {
                    die("Could not insert away club data: " . mysqli_error($dbcnx_client));
                } 
                /*
                // Insert club results data for away (Bye) team
                $sql_scrs = "Select members.MemberID, current_year_scrs, scr_season, scrsID from scrs, members where members.MemberID=scrs.MemberID AND (members.FirstName = '" . $firstname . "' AND members.LastName = '" . $lastname . "') AND current_year_scrs = " . $current_year. "  AND scr_season = '" . $season . "' AND team_grade = '" . $teamgrade . "' and team_id = " . $team_id;
                echo("Get Bye Scores data " . $sql_scrs . "<br>");
                $result_scrs = $dbcnx_client->query($sql_scrs) or die("Couldn't execute bye scores query. " . mysqli_error($dbcnx_client));
                $num_rows_scrs = $result_scrs->num_rows;
                $build_scrs = $result_scrs->fetch_assoc();

                if($type == 'Snooker')
                {
                    $sql_scores = "Update scrs Set 
                    r" . $rnd_no . "s = 6, 
                    r" . $rnd_no . "pos = 6 
                    where scrsID = " . $build_scrs['scrsID'];
                }
                elseif($type == 'Billiards')
                {
                    $sql_scores = "Update scrs Set 
                    r" . $rnd_no . "s = 4, 
                    r" . $rnd_no . "pos = 4 
                    where scrsID = " . $build_scrs['scrsID'];
                }
                echo("SCRS Bye Update " . $sql_scores . "<br>");
                $update = $dbcnx_client->query($sql_scores);
                if(!$update)
                {
                    die("Could not update bye scores data: " . mysqli_error($dbcnx_client));
                }
                */
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

                //echo("Miss " . $team_to_miss . "<br>");
                if($teamID > 0)
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
                    //echo("SCRS Update " . $sql_scores . "<br>");
                    $update = $dbcnx_client->query($sql_scores);
                    if(!$update)
                    {
                        die("Could not update scores data: " . mysqli_error($dbcnx_client));
                    }
                }
            }

            //}
            // save breaks
            $sql_breaks = "Select * from tbl_scoresheet where team = '" . $build_fix[$fixture_no] . "' AND season = '" . $season . "' AND year = " . $current_year . " Order By playing_position";
            //$sql_breaks = "Select * from tbl_scoresheet where team = '" . $build_fix[$fixture_no] . "' AND round = " . ($i+1) . " AND season = '" . $season . "' AND year = " . $current_year . " Order By playing_position";
            //echo("Get Scoresheet data " . $sql_breaks . "<br>");
            $result_breaks = $dbcnx_client->query($sql_breaks) or die("Couldn't execute breaks query. " . mysqli_error($dbcnx_client));
            while($build_breaks_data = $result_breaks->fetch_assoc())
            {
                if($teamID > 0)
                {
                    $sql_breaks_exist = "Select * from breaks where brk_team_id = " . $build_breaks_data['team_id'] . " AND recvd = '" . $build_breaks_data['date_played'] . "' AND season = '" . $season . "' AND member_ID_brks = " . $build_breaks_data['memberID'];
                    //echo("Select from Breaks - " . $sql_breaks_exist . "<br>");
                    $result_breaks_exist = $dbcnx_client->query($sql_breaks_exist) or die("Couldn't execute breaks query. " . mysqli_error($dbcnx_client));
                    $num_rows_breaks = $result_breaks_exist->num_rows;
                    if($type == 'Snooker')
                    {
                        $no_of_frames = 3;
                        for($k = 0; $k < $no_of_frames; $k++) // number of frames in snooker/billiards
                        {
                            //echo("Break Value " . $build_breaks_data['break_' . ($k+1)] . "<br>");
                            if(($num_rows_breaks == 0) && ($build_breaks_data['break_' . ($k+1)] > 0))
                            {
                                //$date = date_create($build_breaks_data['date_played']);
                                //$date = date_format($date, "Y-m-d");

                                //$arr_break = explode(" ", $build_breaks_data['break_' . ($k+1)]);
                                //echo("Count " . count($arr_break) . "<br>");

                                //for($b = 0; $b < count($arr_break); $b++)
                                //{
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
                                    $build_breaks_data['memberID'] . ", " . 
                                    $build_breaks_data['break_' . ($k+1)] . ", '" . 
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
                                //}
                                
                            }
                        }
                    }
                    elseif($type == 'Billiards')
                    {
                        $no_of_frames = 1;
                    //}
                        for($k = 0; $k < $no_of_frames; $k++) // number of frames in snooker/billiards
                        {
                            //echo("Break Value " . $build_breaks_data['break_' . ($k+1)] . "<br>");
                            if(($num_rows_breaks == 0) && ($build_breaks_data['break_' . ($k+1)] > 0))
                            {
                                //$date = date_create($build_breaks_data['date_played']);
                                //$date = date_format($date, "Y-m-d");

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
                                    $build_breaks_data['memberID'] . ", " . 
                                    $arr_break[$b] . ", '" . 
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
                //echo("<pre>");
                //echo(var_dump($highest_break));
                //echo("</pre>");
                //echo("Round " . ($i+1) . "<br>");
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
                    //echo("HB Name " . $high_break_name . ", " . $max . "<br>");
                    // check if data already entered

                    /*
                    if(($teamID != '') && ($high_break_id != ''))
                    {
                        $sql_breaks_exist = "Select * from breaks where brk_team_id = " . $teamID . " AND recvd > '2022-12-31' AND season = '" . $season . "' AND member_ID_brks = " . $high_break_id;
                        //echo("Select from Breaks - " . $sql_breaks_exist . "<br>");
                        $result_breaks = $dbcnx_client->query($sql_breaks_exist) or die("Couldn't execute breaks query. " . mysqli_error($dbcnx_client));
                        $num_rows_breaks = $result_breaks->num_rows;
                        if($high_break != 0)
                        {
                            if($num_rows_breaks == 0)
                            {
                                $sql_breaks = "Insert into breaks (
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
                                $high_break_id . ", " . 
                                $high_break . ", '" . 
                                $teamgrade . "', " . 
                                $teamID . ", '" . 
                                $type . "', '" . 
                                "No" . "', '" . 
                                $current_time . "', '" .
                                $season . "')"; 
                            }
                            else
                            {
                                if($max_date != '')
                                {
                                    $sql_breaks = "Update breaks Set 
                                    member_ID_brks = " . $high_break_id . ", 
                                    brk = " . $high_break . ", 
                                    grade = '" . $teamgrade . "',  
                                    brk_team_id = " . $teamID . ",  
                                    brk_type = '" . $type . "',  
                                    finals_brk = 'No',  
                                    recvd = '" . $max_date . "',
                                    season = '" . $season . "' 
                                    where brk_team_id = " . $teamID . " AND member_ID_brks = " . $high_break_id . " AND season = '" . $season . "'";  
                                }  
                            }
                            //echo("Breaks Update/Insert - " . $sql_breaks ."<br>");
                            //$update = $dbcnx_client->query($sql_breaks);
                            //if(!$update)
                            //{
                            //    die("Could not update breaks data: " . mysqli_error($dbcnx_client));
                            //} 
                        }

                    }*/
                }
            } // end of breaks/players
            

            // update team_entries
            $sql_team_data = "Select * from tbl_club_results where club = '" . $build_fix[$fixture_no] . "' AND round = " . ($i+1) . " AND season = '" . $season . "' AND team_grade = '" . $teamgrade . "' AND year = " . $current_year;
            //echo("Club Results Select " . $sql_team_data ."<br>");
            $result_team_data = $dbcnx_client->query($sql_team_data) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
            $build_team_data = $result_team_data->fetch_assoc();
            $num_rows_team_data = $result_team_data->num_rows;
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
                $sql_update_total = "Select SUM(IFNULL(scrs.r01s,0)) + SUM(IFNULL(scrs.r02s,0)) + SUM(IFNULL(scrs.r03s,0)) + SUM(IFNULL(scrs.r04s,0)) + SUM(IFNULL(scrs.r05s,0)) + SUM(IFNULL(scrs.r06s,0)) + SUM(IFNULL(scrs.r07s,0)) + SUM(IFNULL(scrs.r08s,0)) + SUM(IFNULL(scrs.r09s,0)) + SUM(IFNULL(scrs.r10s,0)) + SUM(IFNULL(scrs.r11s,0)) + SUM(IFNULL(scrs.r12s,0)) + SUM(IFNULL(scrs.r13s,0)) + SUM(IFNULL(scrs.r14s,0)) + SUM(IFNULL(scrs.r15s,0)) + SUM(IFNULL(scrs.r16s,0)) + SUM(IFNULL(scrs.r17s,0)) + SUM(IFNULL(scrs.r18s,0)) AS team_total, scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.r01s, scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s, scrs.r07s, scrs.r08s, scrs.r09s, scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s, scrs.r17s, scrs.r18s,Team_entries.team_id, Team_entries.team_club, Team_entries.team_name, Team_entries.team_grade, Team_entries.day_played, Team_entries.players, Team_entries.total_score, Team_entries.Final5, Team_entries.Updated, Team_entries.Result_pos, Team_entries.Result_score, Team_entries.HB, Team_entries.audited FROM Team_entries,scrs WHERE Team_entries.team_id=scrs.team_id AND Team_entries.team_id = " . $team_id;
                //echo("Null or 0 " . $sql_update_total . "<br>");
                $result_total = $dbcnx_client->query($sql_update_total) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
                $build_total = $result_total->fetch_assoc();

                if($type == "Snooker")
                {
                    $result_score = ($build_team_data['games_won']);
                }
                elseif($type == 'Billiards')
                {
                    $result_score = $build_team_data['overall_points'];
                }
                $sql_team_entry = "Update Team_entries Set
                team_name = '" . $build_fix[$fixture_no] . "',
                total_score = " . $build_total['team_total'] . ",
                Result_pos = " . $result_position . ", 
                Result_score = " . $result_score . ",
                Updated = '" . $current_time . "',
                HB = '" . $HB . "',
                audited = 'No'
                where team_id = " . $team_id;  
                //echo("Team Entries Update (no forfeit or bye) " . $sql_team_entry ."<br>");
                $update = $dbcnx_client->query($sql_team_entry);
                if(!$update )
                {
                    die("Could not update team data: " . mysqli_error($dbcnx_client));
                } 
            } 
        }
    }
    $y++;  // result position
}

$sql_fix = "Select * from tbl_fixtures where round = " . ($i+1) . " AND team_grade = '" . $teamgrade . "' AND year = " . $current_year;
$result_fix = $dbcnx_client->query($sql_fix) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
$build_fix = $result_fix->fetch_assoc();
$date_played = $build_fix['date'];
//$result_fix->free_result();

$y = 0; // result position
foreach($fixture_array as $fixture_no)
{
    if($build_fix[$fixture_no] != '')
    {
        $away_field = substr($fixture_no, 0, 4) . 'away';
        $home_field = substr($fixture_no, 0, 4) . 'home';
        $home_team = $build_fix[$home_field];
        $away_team = $build_fix[$away_field];
        
        // start team forfeit
        $sql_forfeit = "Select * from tbl_scoresheet where team = '" . $home_team . "' AND round = " . ($i+1) . " AND season = '" . $season . "' AND year = " . $current_year;
        //echo("Get Scoresheet data " . $sql_forfeit . "<br>");
        $result_forfeit = $dbcnx_client->query($sql_forfeit) or die("Couldn't execute players query. " . mysqli_error($dbcnx_client));
        while($build_forfeit = $result_forfeit->fetch_assoc())
        {
            if(trim($build_forfeit['players_name']) == 'Team Forfeit')
            {
                if($type == "Snooker")
                {
                    $max_points_available = ($no_of_players*$no_of_games);
                    $home_points_available = ($no_of_players*2);
                    $away_points_available = -4;
                }
                elseif($type == "Billiards")
                {
                    $max_points_available = ($no_of_players*$no_of_games);
                    $home_points_available = (($no_of_players-1)*2);
                    $away_points_available = -4;
                }
                $adjustment_score = ($home_points_available+$away_points_available);
                $team_id = $build_forfeit['team_id'];
                $sql_team_forfeit = "Update Team_entries Set
                day_played='" . $build_fix['dayplayed'] . "', 
                players='" . $no_of_players . "', 
                scr_adjust=" . $adjustment_score . ", 
                scr_adj_rd=" . ($i+1) . ", 
                Final5=4, 
                Result_score=" . $away_points_available . ", 
                HB=NULL, 
                adj_comment='Team Forfeit' 
                WHERE team_id=" . $team_id;
                //echo("Team Entries Forfeit Update " . $sql_team_forfeit ."<br>");
                $update = $dbcnx_client->query($sql_team_forfeit);
                if(!$update )
                {
                    die("Could not update forfeit data: " . mysqli_error($dbcnx_client));
                }
            }
        }
        // end team forfeit
    }

    $y++;
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