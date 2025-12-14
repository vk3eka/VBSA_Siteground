<?php

include('connection.inc');
include('php_functions.php'); 

$season = $_GET['Season'];
$current_year  = $_GET['Year'];
$team = $_GET['HomeTeam'];
$opposition = $_GET['AwayTeam'];
$round = $_GET['RoundNo'];
$team_grade = $_GET['TeamGrade'];
$grade = $_GET['Grade'];
$type = $_GET['Type'];
$date_played = $_GET['FixtureDate'];

// save sql for data to check tbl_club_results inserts
date_default_timezone_set('Australia/Melbourne');
$date = date('Y-m-d H:i:s');
$page = basename($_SERVER['PHP_SELF']);
$username = "PeterJ";
$ip = $_SERVER['REMOTE_ADDR'];

// save player results
// delete if data already entered
$sql = "Delete FROM tbl_scoresheet where season = '" . $season . "' and round = " . $round . " and team = '". $team . "' and team_grade = '".$team_grade . "' AND year = " . $current_year;
//echo("Delete - " . $sql . "<br>");
$result = $dbcnx_client->query($sql);

// delete opposition approvals
$sql_approval = "Select * from tbl_scoresheet where team = '" . $opposition . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND year = " . $current_year . " Order By playing_position";
//echo($sql_approval . "<br>");
$result_count_approval = $dbcnx_client->query($sql_approval); 
$num_rows_approval_count = $result_count_approval->num_rows;
//echo($num_rows_approval_count . "<br>");
if($num_rows_approval_count > 0)
{
  $sql = "Update tbl_scoresheet set capt_home = 0, capt_away = 0 where season = '" . $season . "' and round = " . $round . " and team = '". $opposition . "' and team_grade = '".$team_grade . "' AND year = " . $current_year;
  $update = $dbcnx_client->query($sql);
}

// check if data already entered
$sql_players = "Select * from tbl_scoresheet where team = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND year = " . $current_year . " Order By playing_position";
$result_count_players = $dbcnx_client->query($sql_players) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$playerarr = [];
$i = 0;
while($build_data = $result_count_players->fetch_assoc())
{
  $playerarr[$i] = $build_data['firstname'] . " " . $build_data['lastname'];
  $i++;
}

//$result_count_players->free_result();
$packedscoredata = json_decode(stripslashes($_GET['PackedScoreData']), true);
// get first name in list
// check for team forfeit.
$score1 = explode(", ", $packedscoredata[0]);

if((sizeof($packedscoredata) < 4) && ($score1[0] != 'Team Forfeit'))
{
  echo "Not all players have been entered!"; 
  return;
}

if($_GET['Type'] == 'Snooker')
{
  for($i = 0; $i < sizeof($packedscoredata); $i+=4)
  {
    $score1 = explode(", ", $packedscoredata[$i]);
    $score2 = explode(", ", $packedscoredata[$i+1]);
    $score3 = explode(", ", $packedscoredata[$i+2]);
    $score4 = explode(", ", $packedscoredata[$i+3]);
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
    if($score4[8] == '')
    {
      $score4[8] = 0;
    }
    
    if(!in_array($score1[0], $playerarr, true))
    {
      
      if(strpos($score1[0], "'") !== false) // str_contains php 8.0 onwards
      {
        //echo("True");
        $player = str_replace("'", "\'", $score1[0]);
        $lastname = str_replace("'", "\'", $score1[16]);
      }
      else
      {
        //echo("False");
        $player = $score1[0];
        $lastname = $score1[16];
      }
      
      //$player = $score1[0];
      //$lastname = $score1[16];
    }

    //if(!in_array($score2[0], $playerarr, true)) 
    //{
      /*if (str_contains($score2[0], "'")) 
      {
        $player = str_replace("'", "''", $score2[0]);
        $lastname = str_replace("'", "''", $score2[16]);
      }
      else
      {
        $player = $score2[0];
        $lastname = $score2[16];
      }
      */
      //$player = $score2[0];
    //}
    //if(!in_array($score3[0], $playerarr, true))
    //{
      /*
      if (str_contains($score3[0], "'")) 
      {
        $player = str_replace("'", "''", $score3[0]);
        $lastname = str_replace("'", "''", $score3[16]);
      }
      else
      {
        $player = $score3[0];
        $lastname = $score3[16];
      }
      */
      //$player = $score2[0];
    //}
    //if(!in_array($score4[0], $playerarr, true))
    //{
      /*
      if (str_contains($score4[0], "'")) 
      {
        $player = str_replace("'", "''", $score4[0]);
        $lastname = str_replace("'", "''", $score4[16]);
      }
      else
      {
        $player = $score4[0];
        $lastname = $score4[16];
      }
      */
      //$player = $score2[0];
    //}
    
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
      team_id,
      capt_home
      ) 
      VALUES ('" . 
      trim($player) . "', " . 
      $score1[10] . ", '" . 
      $score1[4] . "', '" . 
      $score1[5] . "', " . 
      $current_year . ", '" . 
      $season . "', '" . 
      $score1[2] . "', '" . 
      $score1[1] . "', " . 
      $score1[3] . ", '" . 
      MySqlDate($score1[6]) . "', " . 
      $score1[8] . ", " . 
      $score2[8] . ", " . 
      $score3[8] . ", " . 
      $score4[8] . ", '" . 
      $score1[9] . "', '" . 
      $score2[9] . "', '" . 
      $score3[9] . "', '" . 
      $score4[9] . "', " . 
      $score1[12] . ", '" .
      $score1[13] . "', '" .
      $score1[15] . "', '" .
      $lastname . "', " .
      $score1[17] . ", 0)";  
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
      round = " . $score1[3] . ",
      date_played = '" . MySqlDate($score1[6]) . "',
      score_1 = " . $score1[8] . ",
      score_2 = " . $score2[8] . ",
      score_3 = " . $score3[8] . ", 
      score_4 = " . $score4[8] . ",  
      break_1 = '" . trim($score1[9]) . "', 
      break_2 = '" . trim($score2[9]) . "', 
      break_3 = '" . trim($score3[9]) . "', 
      break_4 = '" . trim($score4[9]) . "', 
      memberID = " . $score1[12] . ",
      team_grade = '" . $score1[13] . "',
      firstname = '" . $score1[15] . "',
      lastname = '" . $score1[16] . "',
      team_id = " .  $score1[17] . ",
      capt_home = 0
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
    //echo("Billiiards, Data " . $packedscoredata[$i] . "<br>");
    $score1 = explode(", ", $packedscoredata[$i]);
    if($score1[8] == '')
    {
      $score1[8] = 0;
    }
    if(!in_array($score1[0], $playerarr, true))
    {
      if(strpos($score1[0], "'") !== false) // str_contains php 8.0 onwards
      {
        //echo("True");
        $player = str_replace("'", "\'", $score1[0]);
        $lastname = str_replace("'", "\'", $score1[16]);
      }
      else
      {
        //echo("False");
        $player = $score1[0];
        $lastname = $score1[16];
      }
      //$player = $score1[0];
    }
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
      score_1, 
      break_1, 
      memberID,
      team_grade,
      firstname,
      lastname,
      team_id,
      capt_home
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
      MySqlDate($score1[6]) . "', " . 
      $score1[8] . ", '" . 
      trim($score1[9]) . "', " . 
      $score1[12] . ", '" .
      $score1[13] . "', '" .
      $score1[15] . "', '" .
      $lastname . "', " .
      $score1[17] . ", 0)";  
    }
    else
    {
      $sql_players = "Update tbl_scoresheet Set 
      players_name =  '" . $player . "',
      playing_position = " . $score1[10] . ",
      team = '" . $score1[4] . "',
      opposition = '" . $score1[5] . "',
      year = " . $current_year . ",
      season = '" . $season . "', 
      type = '" . $score1[2] . "',
      grade = '" . $score1[1] . "',
      round = '" . $score1[3] . "',
      date_played = '" . MySqlDate($score1[6]) . "',
      score_1 = " . $score1[8] . ",
      break_1 = '" . trim($score1[9]) . "', 
      memberID = " . $score1[12] . ",
      team_grade = '" . $score1[13] . "',
      firstname = '" .$score1[15] . "',
      lastname = '".$lastname . "',
      team_id = " .  $score1[17] . ",
      capt_home = 0
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

/*

// save club results
// check if data already entered
$sql_club_check = "Select * from tbl_club_results where club = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year . " AND team_grade = '" . $team_grade . "'";
echo("Club Select - " . $sql_club_check . "<br>");
$result_count_club = $dbcnx_client->query($sql_club_check) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$num_rows_club_count = $result_count_club->num_rows;
echo("Rows " . $num_rows_club_count . "<br>");

//while ($build_data = $result_count_club->fetch_assoc()) 
//{
//  echo("Row ID " . $build_data['id'] . "<br>");
//}

//$build_data = $result_count_club->fetch_assoc();

//$result_count_club->free_result();

if($num_rows_club_count == 0) //  changed from < 1
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
  MySqlDate($date_played) . "', 0, 0, 0)"; 
  echo("Clubs Save Insert - " . $sql_club . "<br>");
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
  overall_points = 0,
  games_won = 0,
  games_drawn = 0
  where id = " . $build_data['id'];  
  
  $sql_club = "Update tbl_club_results Set 
  club = '" . $team . "', 
  team_grade = '" . $team_grade . "', 
  season = '" . $season . "', 
  year = " . $current_year . ",  
  round = " . $round . ",  
  date_played = '" . MySqlDate($date_played) . "',
  overall_points = 0,
  games_won = 0,
  games_drawn = 0
  where club = '" . $team . "' AND date_played = '" . MySqlDate($date_played) . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year . " AND team_grade = '" . $team_grade . "'";

  echo("Clubs Save Update - " . $sql_club . "<br>");
}  
//echo("Clubs Save - " . $sql_club . "<br>");
$update = $dbcnx_client->query($sql_club);
if(!$update)
{
    die("Could not update club data: " . mysqli_error($dbcnx_client));
} 
/*else
{
  $sql_club = addslashes($sql_club);
  $sql_club_check = addslashes($sql_club_check);
  //echo($date . ", " . $page . ", " . $username . ", " . $ip . ", " . $sql_club . "<br>");
  $sql_error1 = "Insert into tbl_alertlog (
  username, 
  login_date_time, 
  login_ip, 
  login_comments, 
  error_generated
  ) VALUES ('" . 
  $username . "', '" . 
  $date . "', '" . 
  $ip . "', '" . 
  $page . "', '" . 
  $sql_club . "')";
  //echo("SQL " . $sql_error . "<br>");
  $update = $dbcnx_client->query($sql_error1);
  if(!$update)
  {
    die("Could not update alert log: " . mysqli_error($dbcnx_client));
  }   
  $sql_error2 = "Insert into tbl_alertlog (
  username, 
  login_date_time, 
  login_ip, 
  login_comments, 
  error_generated
  ) VALUES ('" . 
  $username . "', '" . 
  $date . "', '" . 
  $ip . "', '" . 
  $page . "', ' Check - " . 
  $sql_club_check . "')";
  //echo("SQL " . $sql_error . "<br>");
  $update = $dbcnx_client->query($sql_error2);
  if(!$update)
  {
    die("Could not update alert log: " . mysqli_error($dbcnx_client));
  }   
}


// check if away data already entered, if not update scores etc
$sql_club_select = "Select * from tbl_club_results where club = '" . $opposition. "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year . " AND team_grade = '" . $team_grade . "'";
echo("Club Select - " . $sql_club_select . "<br>");
$result_count_club = $dbcnx_client->query($sql_club_select) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$num_rows_club_count = $result_count_club->num_rows;
//$build_data_opposition = $result_count_club->fetch_assoc();

$//result_count_club->free_result();

if($num_rows_club_count > 0)
{
  $sql_club_update = "Update tbl_club_results Set 
  overall_points = 0,
  games_won = 0,
  games_drawn = 0
  where club = '" . $opposition . "' AND date_played = '" . MySqlDate($date_played) . "'";
  /*$sql_club_update = "Update tbl_club_results Set 
  overall_points = 0,
  games_won = 0,
  games_drawn = 0
  where id = " . $build_data_opposition['id'];  */
  echo("Clubs Save - " . $sql_club_update . "<br>");
  $update = $dbcnx_client->query($sql_club_update);
  if(!$update)
  {
      die("Could not update opposition club data: " . mysqli_error($dbcnx_client));
  } 
//}
*/
echo("Scoresheet has been saved.");

?>


