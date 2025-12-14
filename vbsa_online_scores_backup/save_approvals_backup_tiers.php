<?php
if (!isset($_SESSION)) 
{
  session_start();
}

include('connection.inc');
include("php_functions.php");

$home_approve = $_GET['Home_Approve']; // home_ok
$away_approve = $_GET['Away_Approve']; // away_ok
$home = $_GET['Home'];
$away = $_GET['Away'];
$round = $_GET['Round'];
$title = $_GET['RoundTitle'];
$season = $_GET['Season'];
$date = MySqlDate($_GET['DatePlayed']);
$year = $_GET['Year'];
$team_grade = $_GET['TeamGrade'];
$games_won_home = $_GET['GamesWonHome'];
$games_won_away = $_GET['GamesWonAway'];
$games_drawn_home = $_GET['GamesDrawHome'];
$games_drawn_away = $_GET['GamesDrawAway'];
$venue = $_GET['Venue'];
$type = $_GET['Type'];

$team = $home;
$opposition = $away;

// unpack PackedData and update scoresheet table
$packedscoredata = json_decode(stripslashes($_GET['PackedData']), true);
$packedfinalsdata = json_decode(stripslashes($_GET['Finals']), true);
$packedforfeitdata = json_decode(stripslashes($_GET['Forfeit']), true);

if($type == 'Snooker')
{
    for($i = 0; $i < sizeof($packedscoredata); $i+=3)
    {
        $score1 = explode(", ", $packedscoredata[$i]);
        $score2 = explode(", ", $packedscoredata[$i+1]);
        $score3 = explode(", ", $packedscoredata[$i+2]);

        $sql_players_home = "Update tbl_scoresheet Set 
        win_1 = " . $score1[0] . ",
        win_2 = " . $score2[0] . ", 
        win_3 = " . $score3[0] . ", 
        win_4 = 0, 
        capt_home = " . $home_approve . "
        where team = '" . $team . "'
        AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . " and team_grade = '" . $team_grade . "' and playing_position = " . $score1[6];
        //echo('Home scoresheet ' . $sql_players_home . "<br>");
        $update = $dbcnx_client->query($sql_players_home);
        if(!$update )
        {
            die("Could not update player home data: " . mysqli_error($dbcnx_client));
        }

        // changed home away approve
        $sql_players_away = "Update tbl_scoresheet Set 
        win_1 = " . $score1[1] . ",
        win_2 = " . $score2[1] . ", 
        win_3 = " . $score3[1] . ", 
        win_4 = 0, 
        capt_home = " . $away_approve . " 
        where team = '" . $opposition . "'
        AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . "  and team_grade = '" . $team_grade . "' and playing_position = " . $score1[6];
        //echo('Away scoresheet ' . $sql_players_away . "<br>");
        $update = $dbcnx_client->query($sql_players_away);
        if(!$update )
        {
            die("Could not update away player data: " . mysqli_error($dbcnx_client));
        }
    }

    $FinalsData = explode(", ", $packedfinalsdata);
    // home team
    $sql_finals_home = "Update tbl_scoresheet Set 
    win_4 = " . $FinalsData[0] . "
    where team = '" . $team . "'
    AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . " and team_grade = '" . $team_grade . "' and playing_position = 1";
    //echo('Home scoresheet ' . $sql_finals_home . "<br>");
    $update = $dbcnx_client->query($sql_finals_home);
    if(!$update )
    {
        die("Could not update player home data: " . mysqli_error($dbcnx_client));
    }
    // away team
    $sql_finals_away = "Update tbl_scoresheet Set 
    win_4 = " . $FinalsData[1] . "
    where team = '" . $opposition . "'
    AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . " and team_grade = '" . $team_grade . "' and playing_position = 1";
    //echo('Away scoresheet ' . $sql_finals_away . "<br>");
    $update = $dbcnx_client->query($sql_finals_away);
    if(!$update )
    {
        die("Could not update player home data: " . mysqli_error($dbcnx_client));
    }
}
else if($type == "Billiards")
{
    $ForfeitData = explode(", ", $packedforfeitdata);

    for($i = 0; $i < sizeof($packedscoredata); $i++)
    {
        $score1 = explode(", ", $packedscoredata[$i]);
        $sql_players_home = "Update tbl_scoresheet Set 
        win_1 = " . $score1[0] . ",
        draw_1 = ".$score1[4] . ",
        capt_home = " . $home_approve . "
        where team = '" . $team . "'
        AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . "  and team_grade = '" . $team_grade . "' and playing_position = " . $score1[6];
        //echo('Home scoresheet (billiards) ' . $sql_players_home . "<br>");
        $update = $dbcnx_client->query($sql_players_home);
        if(!$update )
        {
            die("Could not player update data: " . mysqli_error($dbcnx_client));
        }
        $sql_players_away = "Update tbl_scoresheet Set 
        win_1 = " . $score1[1] . ",
        draw_1 = ".$score1[5] . ",
        capt_home = " . $away_approve . " 
        where team = '" . $opposition . "'
        AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . "  and team_grade = '" . $team_grade . "' and playing_position = " . $score1[6];
        //echo('Away scoresheet (billiards) ' . $sql_players_away . "<br>");
        $update = $dbcnx_client->query($sql_players_away);
        if(!$update )
        {
            die("Could not player update data: " . mysqli_error($dbcnx_client));
        }
        if($ForfeitData[0] == 'true')
        {
            //echo("1 " . $ForfeitData[0] . ", " . $ForfeitData[1] . "<br>");
            // home team
            $sql_players_home = "Update tbl_scoresheet Set 
            win_1 = 0,
            score_1 = 0,
            draw_1 = 0, 
            capt_home = " . $home_approve . " 
            where team = '" . $team . "'
            AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . "  and team_grade = '" . $team_grade . "' and playing_position = 1";
            //echo('Home scoresheet ' . $sql_players_home . "<br>");
            $update = $dbcnx_client->query($sql_players_home);
            if(!$update )
            {
                die("Could not update player home data: " . mysqli_error($dbcnx_client));
            }
            // away team
            $sql_players_away = "Update tbl_scoresheet Set 
            win_1 = 0.75,
            score_1 = 250,
            draw_1 = 0, 
            capt_home = " . $away_approve . " 
            where team = '" . $opposition . "'
            AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . "  and team_grade = '" . $team_grade . "' and playing_position = " . $score1[6];
            //echo('Home scoresheet ' . $sql_players_away . "<br>");
            $update = $dbcnx_client->query($sql_players_away);
            if(!$update )
            {
                die("Could not update player home data: " . mysqli_error($dbcnx_client));
            }
        }
        if($ForfeitData[1] == 'true')
        {
            // home team
            $sql_players_home = "Update tbl_scoresheet Set 
            win_1 = 0,
            score_1 = 0,
            draw_1 = 0, 
            capt_home = " . $home_approve . " 
            where team = '" . $opposition . "'
            AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . "  and team_grade = '" . $team_grade . "' and playing_position = 1";
            //echo('Home scoresheet ' . $sql_players_home . "<br>");
            $update = $dbcnx_client->query($sql_players_home);
            if(!$update )
            {
                die("Could not update player home data: " . mysqli_error($dbcnx_client));
            }
            // away team
            $sql_players_away = "Update tbl_scoresheet Set 
            win_1 = 0.75,
            score_1 = 250,
            draw_1 = 0, 
            capt_home = " . $away_approve . " 
            where team = '" . $home . "'
            AND round = " . $round . " AND season = '" . $season . "' AND date_played = '" . $date . "' AND year = " . $year . "  and team_grade = '" . $team_grade . "' and playing_position = " . $score1[6];
            //echo('Home scoresheet ' . $sql_players_away . "<br>");
            $update = $dbcnx_client->query($sql_players_away);
            if(!$update )
            {
                die("Could not update player home data: " . mysqli_error($dbcnx_client));
            }
        }
    }
}

$home_games_won = 0;
$away_games_won = 0;

if($type == 'Snooker')
{
    // get overall points.
    if($games_won_home > $games_won_away)
    {
        $points_home = 4;
        $points_away = 0;
    }
    if($games_won_away > $games_won_home)
    {
        $points_home = 0;
        $points_away = 4;
    }
    if($games_won_home == $games_won_away)
    {
        $points_home = 2;
        $points_away = 2;
    }
    $home_games_won = $games_won_home;
    $away_games_won = $games_won_away;
}
elseif($type == 'Billiards')
{
    // get overall points.
    if($games_won_home > $games_won_away)
    {
        $points_home = 4;
        $points_away = 0;
    }
    if($games_won_away > $games_won_home)
    {
        $points_home = 0;
        $points_away = 4;
    }
    if($games_won_home == $games_won_away)
    {
        $points_home = 2;
        $points_away = 2;
    }
    // change if team forfeit (games won = -4) //
    if(($games_won_home == -4) || ($games_won_away == -4))
    {
        $home_games_won = ($games_won_home+$games_drawn_home);
        $away_games_won = ($games_won_away+$games_drawn_away);
    }
    else
    {
        $home_games_won = (($games_won_home*2)+$games_drawn_home);
        $away_games_won = (($games_won_away*2)+$games_drawn_away);
    }
}
$sql_clubs_home = "Update tbl_club_results Set 
overall_points = " . $points_home . ", 
games_won = " . $home_games_won . ", 
games_drawn = " . $games_drawn_home . " 
where club = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "'  AND date_played = '" . $date . "'";  
//echo("Home Club Results " .$sql_clubs_home . "<br>");
$update = $dbcnx_client->query($sql_clubs_home);
if(!$update )
{
    die("Could not player update data: " . mysqli_error($dbcnx_client));
} 

// added to update rather than the scoresheet updating
$sql_clubs_away = "Update tbl_club_results Set 
overall_points = " . $points_away . ", 
games_won = " . $away_games_won . ", 
games_drawn = " . $games_drawn_away . "
where club = '" . $opposition . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND date_played = '" . $date . "'";

//echo("Away Club Results " .$sql_clubs_away . "<br>");
$update = $dbcnx_client->query($sql_clubs_away);
if(!$update )
{
    die("Could not club update data: " . mysqli_error($dbcnx_client));
} 

echo("Approval Saved and refreshed.");

?>
