<?php

include('connection.inc');
include('php_functions.php'); 

$season = $_GET['Season'];
$current_year  = $_GET['Year'];
$team = $_GET['HomeTeam'];
//$opposition = $_GET['AwayTeam'];
$round = $_GET['RoundNo'];
$team_grade = $_GET['TeamGrade'];
$grade = $_GET['Grade'];
$type = $_GET['Type'];
$date_played = $_GET['FixtureDate'];

// save player results
// delete if data already entered
$sql = "Delete FROM tbl_scoresheet where season = '" . $season . "' and round = " . $round . " and team = '". $team . "' and team_grade = '".$team_grade . "' AND year = " . $current_year;
//echo("Delete - " . $sql . "<br>");
$result = $dbcnx_client->query($sql);

// check if data already entered
$sql_players = "Select * from tbl_scoresheet where team = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND year = " . $current_year . " Order By playing_position";
$result_count_players = $dbcnx_client->query($sql_players) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$playerarr = [];
$i = 0;
while($build_data = $result_count_players->fetch_assoc())
{
  $playerarr[$i] = $build_data['firstname'] . " " . $build_data['lastname'];
  //$playerarr[$i] = $build_data['players_name'];
  $i++;
}

//$result_count_players->free_result();
$packedscoredata = json_decode(stripslashes($_GET['PackedScoreData']), true);

//echo("Size = " . sizeof($packedscoredata) . "<br>");
if(sizeof($packedscoredata) < 4)
{
  echo "Not all players have been entered!"; 
  return;
}
if($_GET['Type'] == 'Snooker')
{
  for($i = 0; $i < sizeof($packedscoredata); $i+=3)
  {
    $score1 = explode(", ", $packedscoredata[$i]);
    //echo("<pre>");
    //echo(var_dump($score1));
    //echo("</pre>");
    $score2 = explode(", ", $packedscoredata[$i+1]);
    //echo("<pre>");
    //echo(var_dump($score2));
    //echo("</pre>");
    $score3 = explode(", ", $packedscoredata[$i+2]);
    //echo("<pre>");
    //echo(var_dump($score3));
    //echo("</pre>");

    if($score1[8] == '')
    {
      $score1[8] = 0;
    }
    if($score2[8] == '')
    {
      $score2[8] = 0;
    }
    if($score3[8] == '')
    {
      $score3[8] = 0;
    }
    if(!in_array($score1[0], $playerarr, true))
    {
      $player = $score1[0];
    }
    if(!in_array($score2[0], $playerarr, true)) 
    {
      $player = $score2[0];
    }
    if(!in_array($score3[0], $playerarr, true))
    {
      $player = $score2[0];
    }
    //echo("Player = " . $score1[0] . "<br>");
    //echo("ID = " . $score1[11] . "<br>");
    //echo("Lastname = " . $score1[16] . "<br>");
    if($player != '')
    {
      $sql_players = "Insert into tbl_scoresheet (
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
      win_1, 
      win_2, 
      win_3, 
      win_4, 
      score_1, 
      score_2, 
      score_3, 
      score_4, 
      break_1, 
      break_2, 
      break_3, 
      break_4,
      memberID,
      team_grade,
      firstname,
      lastname,
      team_id
      ) 
      VALUES ('" . 
      trim($player) . "', " . 
      $score1[10] . ", '" . 
      $score1[4] . "', '" . 
      $score1[5] . "', " . 
      $current_year . ", '" . 
      $season . "', '" . 
      $score1[2] . "', '" . 
      $score1[1] . "', '" . 
      $score1[3] . "', '" . 
      MySqlDate($score1[6]) . "', '" . 
      $score1[7] . "', '" . 
      $score2[7] . "', '" . 
      $score3[7] . "', 
      '0', " . 
      $score1[8] . ", " . 
      $score2[8] . ", " . 
      $score3[8] . ", 
      0, '" . 
      $score1[9] . "', '" . 
      $score2[9] . "', '" . 
      $score3[9] . "',
      '0', " . 
      $score1[12] . ", '" .
      $score1[13] . "', '" .
      $score1[15] . "', '" .
      $score1[16] . "', " .
      $score1[17] . ")";  
    }
    else
    {
      $sql_players = "Update tbl_scoresheet Set 
      players_name =  '" . trim($score1[0]) . "',
      playing_position = " . $score1[10] . ",
      team = '" . $score1[4] . "',
      opposition = '" . $score1[5] . "',
      year = " . $current_year . ",
      season = '" . $season . "', 
      type = '" . $score1[2] . "',
      grade = '" . $score1[1] . "',
      round = '" . $score1[3] . "',
      date_played = '" . MySqlDate($score1[6]) . "',
      win_1 = " . $score1[7] . ",
      win_2 = " . $score2[7] . ", 
      win_3 = " . $score3[7] . ", 
      win_4 = 0, 
      score_1 = " . $score1[8] . ",
      score_2 = " . $score2[8] . ",
      score_3 = " . $score3[8] . ", 
      score_4 = 0, 
      break_1 = '" . trim($score1[9]) . "', 
      break_2 = '" . trim($score2[9]) . "', 
      break_3 = '" . trim($score3[9]) . "', 
      break_4 = '0',
      memberID = " . $score1[12] . ",
      team_grade = '" . $score1[13] . "',
      firstname = '" . $score1[15] . "',
      lastname = '" . $score1[16] . "',
      team_id = " .  $score1[17] . "
      where id = " . $score1[11];  
    }   
    //echo("Snooker Save - " . $sql_players . "<br>");
    $update = $dbcnx_client->query($sql_players);
    if(!$update)
    {
        die("Could not update snooker player data: " . mysqli_error($dbcnx_client));
    } 
  }
}
elseif($_GET['Type'] == "Billiards")
{
  for($i = 0; $i < sizeof($packedscoredata); $i++)
  {
    $score1 = explode(", ", $packedscoredata[$i]);
    //echo("<pre>");
    //echo(var_dump($score1));
    //echo("</pre>");
    if($score1[8] == '')
    {
      $score1[8] = 0;
    }
    if(!in_array($score1[0], $playerarr, true))
    {
      $player = $score1[0];
    }
    //echo("Player = " . $score1[0] . "<br>");
    //echo("ID = " . $score1[11] . "<br>");
    if($player != '')
    {
      $sql_players = "Insert into tbl_scoresheet (
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
      win_1, 
      score_1, 
      break_1, 
      memberID,
      team_grade,
      firstname,
      lastname,
      team_id,
      draw_1
      ) 
      VALUES ('" . 
      $player . "', " . 
      $score1[10] . ", '" . 
      $score1[4] . "', '" . 
      $score1[5] . "', " . 
      $current_year . ", '" . 
      $season . "', '" . 
      $score1[2] . "', '" . 
      $score1[1] . "', " . 
      $score1[3] . ", '" . 
      MySqlDate($score1[6]) . "', '" . 
      $score1[7] . "', " . 
      $score1[8] . ", '" . 
      trim($score1[9]) . "', " . 
      $score1[12] . ", '" .
      $score1[13] . "', '" .
      $score1[15] . "', '" .
      $score1[16] . "', " .
      $score1[17] . ", '" .
      $score1[18] . "')";  
    }
    else
    {
      $sql_players = "Update tbl_scoresheet Set 
      players_name =  '" . $score1[0] . "',
      playing_position = " . $score1[10] . ",
      team = '" . $score1[4] . "',
      opposition = '" . $score1[5] . "',
      year = " . $current_year . ",
      season = '" . $season . "', 
      type = '" . $score1[2] . "',
      grade = '" . $score1[1] . "',
      round = '" . $score1[3] . "',
      date_played = '" . MySqlDate($score1[6]) . "',
      win_1 = " . $score1[7] . ",
      score_1 = " . $score1[8] . ",
      break_1 = '" . trim($score1[9]) . "', 
      memberID = " . $score1[12] . ",
      team_grade = '" . $score1[13] . "',
      firstname = '" .$score1[15] . "',
      lastname = '".$score1[16] . "',
      team_id = ".$score1[17] . ",
      draw_1 = '".$score1[18] . "'
      where id = " . $score1[11];  
    }   
    //echo("Billiards Save - " . $sql_players . "<br>");
    $update = $dbcnx_client->query($sql_players);
    if(!$update)
    {
        die("Could not update billiard player data: " . mysqli_error($dbcnx_client));
    } 
  }
}

// save club results
// check if data already entered
$sql_club = "Select * from tbl_club_results where club = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year . " AND team_grade = '" . $team_grade . "'";
//echo("Club Select - " . $sql_club . "<br>");
$result_count_club = $dbcnx_client->query($sql_club) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$num_rows_club_count = $result_count_club->num_rows;
$result_count_club->free_result();
$packedresultdata = json_decode(stripslashes($_GET['PackedResultData']), true);
$result = explode(", ", $packedresultdata);
//echo("<pre>");
//echo(var_dump($result));
//echo("</pre>");
$overall_points = $result[0];
$games_won = $result[1];
$games_drawn = $result[2];

//echo("Drawn " . $packedresultdata . "<br>");
if($num_rows_club_count < 1)
{
  $sql_club = "Insert into tbl_club_results (
  club, 
  team_grade,
  season, 
  year, 
  round, 
  date_played,
  overall_points,
  games_won,
  games_drawn
  ) 
  VALUES ('" . 
  $team . "', '" . 
  $team_grade . "', '" . 
  $season . "', '" . 
  $current_year . "', '" . 
  $round . "', '" . 
  MySqlDate($date_played) . "', " .
  $overall_points . ", " .
  $games_won . ", " .
  $games_drawn . ")"; 
}
else
{
  $sql_club = "Update tbl_club_results Set 
  club = '" . $team . "', 
  team_grade = '" . $team_grade . "', 
  season = '" . $season . "', 
  year = " . $current_year . ",  
  round = " . $round . ",  
  date_played = '" . MySqlDate($date_played) . "',
  overall_points = " . $overall_points . ", 
  games_won = " . $games_won . ",  
  games_drawn = " . $games_drawn . "  
  where club = '" . $team . "' AND date_played = '" . MySqlDate($date_played) . "'";  
}  
//echo("Clubs Save - " . $sql_club . "<br>");
$update = $dbcnx_client->query($sql_club);
if(!$update)
{
    die("Could not update club data: " . mysqli_error($dbcnx_client));
} 
echo("Scoresheet has been saved.");

?>


