<?php

function ProcessByes($home_team, $teamgrade, $season, $year, $round)
{
    global $dbcnx_client;
    
//echo("Start of Bye process (Round " . $round . ")<br>");
        
            // get home team data
            $sql_home_team = "Select team_id, team_club_id, team_club from Team_entries where team_name = '" . $home_team . "' and team_grade = '" . $teamgrade . "' Order By team_id DESC LIMIT 1";
            //echo("Get Home Bye Team data " . $sql_home_team . "<br>");
            $result_home_team = $dbcnx_client->query($sql_home_team) or die("Couldn't execute bye round no query. " . mysqli_error($dbcnx_client));
            $build_home_team = $result_home_team->fetch_assoc();

            $home_team_id = $build_home_team['team_id'];
            //echo("Bye Home Team ID " . $home_team_id . ", Name " . $home_team . "<br>"); //OK

            // get away team data
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
            $sql_scrs = "Select scrsID from scrs where MemberID = 1 AND current_year_scrs = " . $current_year. " AND scr_season = '" . $season . "' AND team_grade = '" . $teamgrade . "' and team_id = " . $home_team_id;
            //echo("Get Home Bye Scores data " . $sql_scrs . "<br>");
            $result_scrs = $dbcnx_client->query($sql_scrs) or die("Couldn't execute bye scores query. " . mysqli_error($dbcnx_client));
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
            $sql_scrs = "Select * FROM scrs where current_year_scrs = " . $current_year. " and team_grade = '" . $teamgrade . "' and MemberID = 1 and team_id = " . $away_team_id;

            //echo("Get Away Bye Scores data " . $sql_scrs . "<br>");
           
            $result_scrs = $dbcnx_client->query($sql_scrs) or die("Couldn't execute bye scores query. " . mysqli_error($dbcnx_client));
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
            //$sql_select_club = "Select * from tbl_club_results where club = '" . $home_team . "' AND round = " . ($i+1) . " AND team_grade = '" . $teamgrade . "'  AND season = '" . $season . "' AND year = " . $current_year;

            $sql_select_club = "Select * from tbl_club_results where club = '" . $build_fix[$fixture_no] . "' AND round = " . ($i+1) . " AND team_grade = '" . $teamgrade . "'  AND season = '" . $season . "' AND year = " . $current_year;


            $result_select_club = $dbcnx_client->query($sql_select_club) or die("Couldn't execute club results query. " . mysqli_error($dbcnx_client));
            //echo("Club Existing Byes " . $sql_select_club . "<br>");
            $num_rows_select_club = $result_select_club->num_rows;
            //echo("Club Rows " . $num_rows_select_club . "<br>");
            $build_club_data = $result_select_club->fetch_assoc();

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
            $result_home_total = $dbcnx_client->query($sql_home_total) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
            $build_home_total = $result_home_total->fetch_assoc();
            //echo("Home Totals " . $build_home_total['total_score'] . ", Team " . $home_team . "<br>");

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
            
            // Update club results data
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
                //echo("Club Insert Home (Bye) " . $sql_home_club . "<br>");
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
                //echo("Club Insert Away (Bye) " . $sql_away_club . "<br>");
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
                where club = '" . $home_team . "' AND date_played = '" . $date_played . "' and round = " . ($i+1);  
                //echo("Club Home Update (Bye) " . $sql_clubs_home . "<br>");
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
                where club = '" . $away_team . "' AND date_played = '" . $date_played  . "' and round = " . ($i+1);  
                //echo("Club Away Update (Bye) " . $sql_clubs_away . "<br>");
                $update = $dbcnx_client->query($sql_clubs_away);
                if(!$update )
                {
                    die("Could not club update data: " . mysqli_error($dbcnx_client));
                } 
                
            } // end of club results rows


            //echo("End of Bye process (Round " . $round . ")<br>");
}

?>