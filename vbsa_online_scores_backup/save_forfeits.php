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

// loop all rounds player to determine forfeits

for($x = 0; $x < $round; $x++)
{
    $adjustment_score_total = 0;
    $sql_fix = "Select * from tbl_fixtures where round = " . ($x+1) . " AND team_grade = '" . $teamgrade . "' AND year = " . $current_year;
    $result_fix = $dbcnx_client->query($sql_fix) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
    $build_fix = $result_fix->fetch_assoc();
    $date_played = $build_fix['date'];
    //$result_fix->free_result();
    $y = 0; // result position
    foreach($fixture_array as $fixture_no)
    {
        //echo("Round No. " . $x . "<br>");
        if($build_fix[$fixture_no] != '')
        {
            $away_field = substr($fixture_no, 0, 4) . 'away';
            $home_field = substr($fixture_no, 0, 4) . 'home';
            $home_team = $build_fix[$home_field];
            $away_team = $build_fix[$away_field];
            $no_of_players = 4;
            //echo("Home " . $home_team . ", Away " . $away_team . "<br>");
            
            // start team forfeit



            // update home team of team forfeit

            // added to update team forfeit scores
            $sql_forfeit = "Select * from tbl_scoresheet where (team = '" . $home_team . "' or team = '" . $away_team . "') AND round = " . $x . " AND season = '" . $season . "' AND year = " . $current_year . " AND FirstName = 'Team' AND LastName = 'Forfeit'";
            //echo("Get Scoresheet Home Data " . $sql_forfeit . "<br>");
            $result_forfeit = $dbcnx_client->query($sql_forfeit) or die("Couldn't execute players query. " . mysqli_error($dbcnx_client));

            while($build_forfeit = $result_forfeit->fetch_assoc())
            {
                $firstname = 'Team';
                $lastname = 'Forfeit';
                $team_id = $build_forfeit['team_id'];
                //echo("Team ID " . $team_id . "<br>");
                $sql_scrs = "Select members.MemberID, current_year_scrs, scr_season, scrsID from scrs, members where members.MemberID=scrs.MemberID AND (members.FirstName = '" . $firstname . "' AND members.LastName = '" . $lastname . "') AND current_year_scrs = " . $current_year. "  AND scr_season = '" . $season . "' AND team_grade = '" . $teamgrade . "' and team_id = " . $team_id;
                //echo($sql_scrs . "<br>");
                $result_scrs = $dbcnx_client->query($sql_scrs) or die("Couldn't execute scores query. " . mysqli_error($dbcnx_client));
                $build_scrs = $result_scrs->fetch_assoc();
                //echo($build_scrs['scrsID'] . "<br>");
                // format round number
                if($i > 8)
                {
                    $rnd_no = ($x+1);
                }
                else
                {
                    $rnd_no = '0' . ($x+1);
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
            //} // end home team

            //echo("Players Name " . $build_forfeit['players_name'] . "<br>");
            //echo("Players Name " . $firstname . " "  . $lastname . "<br>");
            //if(trim($build_forfeit['players_name']) == 'Team Forfeit')
            //$adjustment_score_total = 0;
            //if(trim($firstname == 'Team') && trim($lastname == 'Forfeit'))
            //{
                // get any previous adjustments for this team grade
                //$sql_grade_forfeit = "Select * from Team_entries where team_cal_year = " . $current_year . " and team_grade='" . $teamgrade . "' and team_id=" . $team_id;
                //echo("Check previous adjustments " . $sql_grade_forfeit . "<br>");
                //$result_grade_forfeit = $dbcnx_client->query($sql_grade_forfeit) or die("Couldn't execute adjust query. " . mysqli_error($dbcnx_client));
                //$adjustment_score_total = 0;
                //while($build_grade_forfeit = $result_grade_forfeit->fetch_assoc())
                //{
                    //$adjustment_score_total = $build_grade_forfeit['scr_adjust'];
                //}
                    //echo("Previous adjustment totals " . $adjustment_score_total . "<br>");

                    //echo("Home " . $home_team . ", Away " . $away_team . ", Player " . $build_forfeit['players_name'] . "<br>");
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
                    //echo("Calculated Adjustment " . $adjustment_score . ", Previous Adjustment " . $adjustment_score_total . "<br>");

                    $adjustment_score_total = $adjustment_score+$adjustment_score_total;
                    //$team_id = $build_forfeit['team_id'];
                    $sql_team_forfeit = "Update Team_entries Set
                    day_played='" . $build_fix['dayplayed'] . "', 
                    players='" . $no_of_players . "', 
                    scr_adjust=" . ($adjustment_score_total) . ", 
                    scr_adj_rd=" . ($x+1) . ", 
                    Final5=4, 
                    Result_score=" . $away_points_available . ", 
                    HB=NULL, 
                    adj_comment='Team Forfeit' 
                    WHERE team_id=" . $team_id . " and team_grade='" . $teamgrade . "'";  
                    echo("Team Entries Home Forfeit Update " . $sql_team_forfeit ."<br>");
                    $update = $dbcnx_client->query($sql_team_forfeit);
                    if(!$update )
                    {
                        die("Could not update forfeit data: " . mysqli_error($dbcnx_client));
                    }
                //}
                //$adjustment_score_total = $adjustment_score+$adjustment_score_total;
            }
        }

        // update away team of team forfeit
        $sql_forfeit = "Select * from tbl_scoresheet where team = '" . $away_team . "' AND round = " . ($x+1) . " AND season = '" . $season . "' AND year = " . $current_year;
        //echo("Get Scoresheet Away Data " . $sql_forfeit . "<br>");
        $result_forfeit = $dbcnx_client->query($sql_forfeit) or die("Couldn't execute players query. " . mysqli_error($dbcnx_client));
        while($build_forfeit = $result_forfeit->fetch_assoc())
        {
            //echo("Players Name " . $build_forfeit['players_name'] . "<br>");
            if(trim($firstname == 'Team') && trim($lastname == 'Forfeit'))
            //if(trim($build_forfeit['players_name']) == 'Team Forfeit')
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
                $adjustment_score_total = $adjustment_score+$adjustment_score_total;
                //$team_id = $build_forfeit['team_id'];
                $sql_team_forfeit = "Update Team_entries Set
                day_played='" . $build_fix['dayplayed'] . "', 
                players='" . $no_of_players . "', 
                scr_adjust=" . ($adjustment_score_total) . ", 
                scr_adj_rd=" . ($x+1) . ", 
                Final5=4, 
                Result_score=" . $away_points_available . ", 
                HB=NULL, 
                adj_comment='Team Forfeit' 
                WHERE team_id=" . $team_id . " and team_grade='" . $teamgrade . "'";  
                echo("Team Entries Away Forfeit Update " . $sql_team_forfeit ."<br>");
                $update = $dbcnx_client->query($sql_team_forfeit);
                if(!$update )
                {
                    die("Could not update forfeit data: " . mysqli_error($dbcnx_client));
                }
            }
            //}
            // end team forfeit
        }
        $y++;
    }
}

?>
