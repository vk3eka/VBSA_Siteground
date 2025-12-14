<?php

include('connection.inc');

$home = $_GET['home'];
$away = $_GET['away'];
$round = $_GET['round'];
$year = $_GET['year'];
$season = $_GET['season'];
$team_grade = $_GET['grade'];
$type = $_GET['type'];

$players_home = array();
$PlayerForfeitHome = '';
$PlayerForfeitAway = '';

// function to calculate scores from stick score and tier
function TierCalc($player1_tier, $player2_tier, $player1_stick, $player2_stick)
{
  $tier_diff = abs($player1_tier-$player2_tier);
  /*
  switch($tier_diff)
  {
  case 0:
    $multipler = 1;
    break;
  case 1:
    $multipler = 1.5;
    break;
  case 2:
    $multipler = 2;
    break;
  case 3:
    $multipler = 2.5;
    break;
  case 4:
    $multipler = 3;
    break;
  case 5:
    $multipler = 3.5;
    break;
  default:
    $multipler = 1;
    break;
  }
  */
  // added for 12 tiers
  switch($tier_diff)
  {
  //case -1:
  //  $multipler = 1.00;
  //  break;
  case 0:
    $multipler = 1.00;
    break;
  case 1:
    $multipler = 1.25;
    break;
  case 2:
    $multipler = 1.50;
    break;
  case 3:
    $multipler = 1.75;
    break;
  case 4:
    $multipler = 2.00;
    break;
  case 5:
    $multipler = 2.25;
    break;
  case 6:
    $multipler = 2.50;
    break;
  case 7:
    $multipler = 2.75;
    break;
  case 8:
    $multipler = 3.00;
    break;
  case 9:
    $multipler = 3.25;
    break;
  case 10:
    $multipler = 3.50;
    break;
  case 11:
    $multipler = 3.75;
    break;
  case 12:
    $multipler = 4.00;
    break;
  case 13:
    $multipler = 4.25;
    break;
  case 14:
    $multipler = 4.50;
    break;
  default:
    $multipler = 1.00;
    break;
  }
  //................

  if($player1_tier > $player2_tier)
  {
    $score1 = ($player1_stick*$multipler);
    $score2 = $player2_stick;
  }
  if($player1_tier < $player2_tier)
  {
    $score1 = $player1_stick;
    $score2 = ($player2_stick*$multipler);
  }
  if($player1_tier == $player2_tier)
  {
    $score1 = $player1_stick;
    $score2 = $player2_stick;
  }
  return $score1 . ", " . $score2;
}

$scores_home_1 = [];
$scores_away_1 = [];
$scores_home_2 = [];
$scores_away_2 = [];
$scores_home_3 = [];
$scores_away_3 = [];
$scores_home_4 = [];
$scores_away_4 = [];

// get score matchups
$sql_score_home = "Select * from tbl_scoresheet where team = '" . $home . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $year . " AND team_grade = '" . $team_grade . "' Order By playing_position";
$result_score_home = $dbcnx_client->query($sql_score_home) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$j = 0;
//echo($sql_score_home . "<br>");

while ($build_score_home = $result_score_home->fetch_assoc()) 
{
  if($type == 'Billiards')
  {
    if($build_score_home['players_name'] == 'Team Forfeit')
    {
      $TeamForfeitHome = true;
      //echo("This is a Team Forfeit (Home)<br>");
      break;
    }
    else
    {
      $TeamForfeitHome = false;
      //echo("This is not a Team Forfeit (Home)<br>");
    }
    // added 29/10/2024
    if($build_score_home['players_name'] == 'Player Forfeit')
    {
      $PlayerForfeitHome = true;
      $play_pos = $build_score_home['playing_position'];
      $play_pos_home = $build_score_home['playing_position'];
      //echo("This is a Player Forfeit (Home)<br>");
    }
    else
    {
      $PlayerForfeitHome = false;
      //echo("This is not a Player Forfeit (Home)<br>");
    }
    $scores_home_1[$j] = ($build_score_home['billiard_stick']); 
    $scores_home_2[$j] = ''; 
    $scores_home_3[$j] = ''; 
    $scores_home_4[$j] = ''; 
  }
  else if($type == 'Snooker')
  {
    $scores_home_1[$j] = ($build_score_home['score_1']); 
    $scores_home_2[$j] = ($build_score_home['score_2']); 
    $scores_home_3[$j] = ($build_score_home['score_3']); 
    $scores_home_4[$j] = ($build_score_home['score_4']); 
    if($build_score_home['players_name'] == 'Player Forfeit')
    {
      $PlayerForfeitHome = true;
      $play_pos = $build_score_home['playing_position'];
      $play_pos_home = $build_score_home['playing_position'];
      //echo("This is a Player Forfeit (Home), Position " . $play_pos . "<br>");
    }
    else
    {
      $PlayerForfeitHome = false;
      //echo("This is not a Player Forfeit (Home)<br>");
    }
  }
  if($TeamForfeitHome === false)
  {
    $tier_home[$j] = ($build_score_home['tier']); 
  }
 
  $home_approve = $build_score_home['capt_home'];
  $j++;
}

$sql_score_away = "Select * from tbl_scoresheet where team = '" . $away . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $year . " AND team_grade = '" . $team_grade . "' Order By playing_position";
//echo($sql_score_away . "<br>");
$result_score_away = $dbcnx_client->query($sql_score_away) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$j = 0;
while ($build_score_away = $result_score_away->fetch_assoc()) 
{
  if($type == 'Billiards')
  {
    if($build_score_away['players_name'] == 'Team Forfeit')
    {
      $TeamForfeitAway = true;
      //echo("This is a Team Forfeit (Away)<br>");
    }
    else
    {
      $TeamForfeitAway = false;
      //echo("This is not a Team Forfeit (Away)<br>");
    }
    // added 29/10/2024
    if($build_score_away['players_name'] == 'Player Forfeit')
    {
      $PlayerForfeitAway = true;
      $play_pos = $build_score_away['playing_position'];
      $play_pos_home = $build_score_home['playing_position'];
      //echo("This is a Player Forfeit (Away), Position " . $play_pos . "<br>");
    }
    else
    {
      $PlayerForfeitAway = false;
      //echo("This is not a Player Forfeit (Away)<br>");
    }
    $scores_away_1[$j] = ($build_score_away['billiard_stick']);  
    $scores_away_2[$j] = ''; 
    $scores_away_3[$j] = ''; 
    $scores_away_4[$j] = ''; 
  }
  else if($type == 'Snooker')
  {
    $scores_away_1[$j] = ($build_score_away['score_1']); 
    $scores_away_2[$j] = ($build_score_away['score_2']); 
    $scores_away_3[$j] = ($build_score_away['score_3']); 
    $scores_away_4[$j] = ($build_score_away['score_4']); 

    // added 08/07/2025
    if($build_score_away['players_name'] == 'Player Forfeit')
    {
      $PlayerForfeitAway = true;
      $play_pos = $build_score_away['playing_position'];
      $play_pos_away = $build_score_away['playing_position'];
      //echo("This is a Player Forfeit (Away), Position " . $play_pos . "<br>");
    }
    else
    {
      $PlayerForfeitAway = false;
      //echo("This is not a Player Forfeit (Away)<br>");
    }
  }
  if(($TeamForfeitHome === false) && ($TeamForfeitAway === false) && ($type == 'Billiards'))
  {
    $tier_away[$j] = ($build_score_away['tier']); 
    $tier_all = array_merge($tier_home, $tier_away);
  }
  $away_approve = $build_score_away['capt_home'];
  $j++;
}
$scores_all_1 = array_merge($scores_home_1, $scores_away_1);
$scores_all_2 = array_merge($scores_home_2, $scores_away_2);
$scores_all_3 = array_merge($scores_home_3, $scores_away_3);
$scores_all_4 = array_merge($scores_home_4, $scores_away_4);

$j = 0;
if($type == 'Billiards')
{
  //echo("Billiards");
  $home_win = 0;
  $away_win = 0;

  $home_draw = 0;
  $away_draw = 0;

  $check_home_win = 0;
  $check_away_win = 0;
  $check_home_draw = 0;
  $check_away_draw = 0;

  if(($TeamForfeitHome === false) && ($TeamForfeitAway === false))
  {
    /*
        echo("Team Forfeit Inside<br>");
        ///////////// team forfeit  add scores to each non forfeit player  /////////////
        ///////////// move to $TeamForfeitHome === true ////////////////////////////////
        // game 1
        $score_arr_0 = TierCalc($tier_all[0], $tier_all[4], $scores_all_1[0], $scores_all_1[4]);
        echo("<pre>");
        echo(var_dump($score_arr_0));
        echo("</pre>");
        $scores_0 = explode(", ", $score_arr_0);
        $players_tier_1[0] = number_format($scores_0[0], 2);
        $players_tier_1[1] = number_format($scores_0[1], 2);

        echo("<pre>");
        echo(var_dump($players_tier_1[0]));
        echo("</pre>");
        // game 2
        $score_arr_1 = TierCalc($tier_all[1], $tier_all[5], $scores_all_1[1], $scores_all_1[5]);
        $scores_1 = explode(", ", $score_arr_1);
        $players_tier_2[0] = number_format($scores_1[0], 2);
        $players_tier_2[1] = number_format($scores_1[1], 2);

        // game 3
        $score_arr_2 = TierCalc($tier_all[2], $tier_all[6], $scores_all_1[2], $scores_all_1[6]);
        $scores_2 = explode(", ", $score_arr_2);
        $players_tier_3[0] = number_format($scores_2[0], 2);
        $players_tier_3[1] = number_format($scores_2[1], 2);

        // game 4
        $score_arr_3 = TierCalc($tier_all[3], $tier_all[7], $scores_all_1[3], $scores_all_1[7]);
        $scores_3 = explode(", ", $score_arr_3);
        $players_tier_4[0] = number_format($scores_3[0], 2);
        $players_tier_4[1] = number_format($scores_3[1], 2);

        echo("<pre>");
        echo(var_dump($score_arr_0));
        echo("</pre>");
        /////
    */    

    // game 1
    $score_arr_0 = TierCalc($tier_all[0], $tier_all[4], $scores_all_1[0], $scores_all_1[4]);
    $scores_0 = explode(", ", $score_arr_0);
    $players_tier_1[0] = number_format($scores_0[0], 2);
    $players_tier_1[1] = number_format($scores_0[1], 2);

    // game 2
    $score_arr_1 = TierCalc($tier_all[1], $tier_all[5], $scores_all_1[1], $scores_all_1[5]);
    $scores_1 = explode(", ", $score_arr_1);
    $players_tier_2[0] = number_format($scores_1[0], 2);
    $players_tier_2[1] = number_format($scores_1[1], 2);

    // game 3
    $score_arr_2 = TierCalc($tier_all[2], $tier_all[6], $scores_all_1[2], $scores_all_1[6]);
    $scores_2 = explode(", ", $score_arr_2);
    $players_tier_3[0] = number_format($scores_2[0], 2);
    $players_tier_3[1] = number_format($scores_2[1], 2);

    // game 4
    $score_arr_3 = TierCalc($tier_all[3], $tier_all[7], $scores_all_1[3], $scores_all_1[7]);
    $scores_3 = explode(", ", $score_arr_3);
    $players_tier_4[0] = number_format($scores_3[0], 2);
    $players_tier_4[1] = number_format($scores_3[1], 2);

    // make non forfeit player score 100 points and a win. added 29/10/2024
    // make non forfeit player score 80 points and a win. added 30/07/2025
    if($PlayerForfeitHome === true)
    {
      switch ($play_pos)
      {
        case 1:
          $players_tier_1[0] = number_format(0, 2);
          $players_tier_1[1] = number_format(80, 2);
          break;
        case 2:
          $players_tier_2[0] = number_format(0, 2);
          $players_tier_2[1] = number_format(80, 2);
          break;
        case 3:
          $players_tier_3[0] = number_format(1, 2);
          $players_tier_3[1] = number_format(80, 2);
          break;
        case 4:
          $players_tier_4[0] = number_format(1, 2);
          $players_tier_4[1] = number_format(80, 2);
          break;
      }
    }
    if($PlayerForfeitAway === true)
    {
      switch($play_pos)
      {
        case 1:
          $players_tier_1[0] = number_format(80, 2);
          $players_tier_1[1] = number_format(0, 2);
          break;
        case 2:
          $players_tier_2[0] = number_format(80, 2);
          $players_tier_2[1] = number_format(0, 2);
          break;
        case 3:
          $players_tier_3[0] = number_format(80, 2);
          $players_tier_3[1] = number_format(0, 2);
          break;
        case 4:
          $players_tier_4[0] = number_format(80, 2);
          $players_tier_4[1] = number_format(0, 2);
          break;
      }
    }
    //.......................................................................

    $scores_all = array_merge($players_tier_1, $players_tier_2, $players_tier_3, $players_tier_4);

// 0
  $i = 0;
  if($scores_all[0] > $scores_all[1])
  {
    $players_home_1[$j] = $players_home_1[$j] . "" .
                        'A_win_' . $i . ', 1,' . " " .
                        'B_win_' . $i . ', 0,' . " ";
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  if($scores_all[0] == $scores_all[1])
  {
    $players_home_1[$j] = $players_home_1[$j] . "" .
                        'A_draw_' . $i . ', 1,' . " ";
    $home_draw = ($home_draw + 1);
    $check_home_draw = ($check_home_draw + 1);

    $players_home_1[$j] = $players_home_1[$j] . "" .
                         'B_draw_' . $i . ', 1,' . " ";
    $away_draw = ($away_draw + 1);
    $check_away_draw = ($check_away_draw + 1);
  }
  if($scores_all[0] < $scores_all[1])
  {
    $players_home_1[$j] = $players_home_1[$j] . "" .
                        'A_win_' . $i . ', 0,' . " " .
                        'B_win_' . $i . ', 1,' . " ";
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }

  // 1
    $i = 1;
    if($scores_all[2] > $scores_all[3])
    {
     $players_home_2[$j] = $players_home_2[$j] . "" .
                          'A_win_' . $i . ', 1,' . " " .
                          'B_win_' . $i . ', 0,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    if($scores_all[2] == $scores_all[3])
    {
      $players_home_2[$j] = $players_home_2[$j] . "" .
                          'A_draw_' . $i . ', 1,' . " ";
      $home_draw = ($home_draw + 1);
      $check_home_draw = ($check_home_draw + 1);

      $players_home_2[$j] = $players_home_2[$j] . "" .
                          'B_draw_' . $i . ', 1,' . " ";
      $away_draw = ($away_draw + 1);
      $check_away_draw = ($check_away_draw + 1);
    }
    if($scores_all[2] < $scores_all[3])
    {
      $players_home_2[$j] = $players_home_2[$j] . "" .
                          'A_win_' . $i . ', 0,' . " " .
                          'B_win_' . $i . ', 1,' . " ";
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

// 2
    $i = 2;
    if($scores_all[4] > $scores_all[5])
    {
     $players_home_3[$j] = $players_home_3[$j] . "" .
                          'A_win_' . $i . ', 1,' . " " .
                          'B_win_' . $i . ', 0,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    if($scores_all[4] == $scores_all[5])
    {
      $players_home_3[$j] = $players_home_3[$j] . "" .
                          'A_draw_' . $i . ', 1,' . " ";
      $home_draw = ($home_draw + 1);
      $check_home_draw = ($check_home_draw + 1);

      $players_home_3[$j] = $players_home_3[$j] . "" .
                          'B_draw_' . $i . ', 1,' . " ";
      $away_draw = ($away_draw + 1);
      $check_away_draw = ($check_away_draw + 1);
    }
    if($scores_all[4] < $scores_all[5])
    {
      $players_home_3[$j] = $players_home_3[$j] . "" .
                          'A_win_' . $i . ', 0,' . " " .
                          'B_win_' . $i . ', 1,' . " ";
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
 
// 3
    $i = 3;
    if($scores_all[6] > $scores_all[7])
    {
     $players_home_4[$j] = $players_home_4[$j] . "" .
                          'A_win_' . $i . ', 1,' . " " .
                          'B_win_' . $i . ', 0,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    if($scores_all[6] == $scores_all[7])
    {
      $players_home_4[$j] = $players_home_4[$j] . " " .
                          'A_draw_' . $i . ', 1,' . " ";
      $home_draw = ($home_draw + 1);
      $check_home_draw = ($check_home_draw + 1);

      $players_home_4[$j] = $players_home_4[$j] . "" .
                          'B_draw_' . $i . ', 1,' . " ";
      $away_draw = ($away_draw + 1);
      $check_away_draw = ($check_away_draw + 1);
    }
    if($scores_all[6] < $scores_all[7])
    {
      $players_home_4[$j] = $players_home_4[$j] . "" .
                          'A_win_' . $i . ', 0,' . " " .
                          'B_win_' . $i . ', 1,' . " ";
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    $players_home_5[0] = 'A_wins, ' . $home_win . ', A_draws, ' . $home_draw . ', B_wins, ' . $away_win . ', B_draws, ' . $away_draw . ',';
    $players_home_6[0] = 'home_ok, ' . $home_approve . ", away_ok, " . $away_approve . ',';
    $j++;
    
    $players_all = array_merge($players_home_1, $players_home_2, $players_home_3, $players_home_4, $players_home_5, $players_home_6, $players_tier_1, $players_tier_2, $players_tier_3, $players_tier_4, $scores_all_1);
    //echo("Not Team Forfeit<br>");
  }
  else
  {
    //echo("No Tier<br>");
    if($TeamForfeitHome === true)
    {
        /*
        echo("Team Forfeit Inside<br>");
        ///////////// team forfeit
        // game 1
        $score_arr_0 = TierCalc($tier_all[0], $tier_all[4], $scores_all_1[0], $scores_all_1[4]);
        echo("<pre>");
        echo(var_dump($score_arr_0));
        echo("</pre>");
        $scores_0 = explode(", ", $score_arr_0);
        $players_tier_1[0] = number_format($scores_0[0], 2);
        $players_tier_1[1] = number_format($scores_0[1], 2);

        // game 2
        $score_arr_1 = TierCalc($tier_all[1], $tier_all[5], $scores_all_1[1], $scores_all_1[5]);
        $scores_1 = explode(", ", $score_arr_1);
        $players_tier_2[0] = number_format($scores_1[0], 2);
        $players_tier_2[1] = number_format($scores_1[1], 2);

        // game 3
        $score_arr_2 = TierCalc($tier_all[2], $tier_all[6], $scores_all_1[2], $scores_all_1[6]);
        $scores_2 = explode(", ", $score_arr_2);
        $players_tier_3[0] = number_format($scores_2[0], 2);
        $players_tier_3[1] = number_format($scores_2[1], 2);

        // game 4
        $score_arr_3 = TierCalc($tier_all[3], $tier_all[7], $scores_all_1[3], $scores_all_1[7]);
        $scores_3 = explode(", ", $score_arr_3);
        $players_tier_4[0] = number_format($scores_3[0], 2);
        $players_tier_4[1] = number_format($scores_3[1], 2);

        echo("<pre>");
        echo(var_dump($score_arr_0));
        echo("</pre>");
        /////
        */

      $i = 0;
      $players_home_1[$j] = $players_home_1[$j] . "" .
                          'A_win_' . $i . ', 1,' . " " .
                          'B_win_' . $i . ', 0,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);

      $i = 1;
      $players_home_2[$j] = $players_home_2[$j] . "" .
                          'A_win_' . $i . ', 1,' . " " .
                          'B_win_' . $i . ', 0,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);

      $i = 2;
      $players_home_3[$j] = $players_home_3[$j] . "" .
                          'A_win_' . $i . ', 1,' . " " .
                          'B_win_' . $i . ', 0,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);

      $i = 3;
      $players_home_4[$j] = $players_home_4[$j] . "" .
                          'A_win_' . $i . ', 1,' . " " .
                          'B_win_' . $i . ', 0,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);

      $players_home_5[0] = 'A_wins, -4, A_draws, 0, B_wins, 6, B_draws, 0,';
      $players_home_6[0] = 'home_ok, ' . $home_approve . ", away_ok, " . $away_approve . ',';
      $j++;

      $players_all = array_merge($players_home_1, $players_home_2, $players_home_3, $players_home_4, $players_home_5, $players_home_6);
    }
    else if($TeamForfeitAway === true)
    {
      $i = 0;
      $players_home_1[$j] = $players_home_1[$j] . "" .
                          'A_win_' . $i . ', 0,' . " " .
                          'B_win_' . $i . ', 1,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);

      $i = 1;
      $players_home_2[$j] = $players_home_2[$j] . "" .
                          'A_win_' . $i . ', 0,' . " " .
                          'B_win_' . $i . ', 1,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);

      $i = 2;
      $players_home_3[$j] = $players_home_3[$j] . "" .
                          'A_win_' . $i . ', 0,' . " " .
                          'B_win_' . $i . ', 1,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);

      $i = 3;
      $players_home_4[$j] = $players_home_4[$j] . "" .
                          'A_win_' . $i . ', 0,' . " " .
                          'B_win_' . $i . ', 1,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);

      $players_home_5[0] = 'A_wins, 6, A_draws, 0, B_wins, -4, B_draws, 0,';
      $players_home_6[0] = 'home_ok, ' . $home_approve . ", away_ok, " . $away_approve . ',';
      $j++;

      $players_all = array_merge($players_home_1, $players_home_2, $players_home_3, $players_home_4, $players_home_5, $players_home_6);
    }

  }
}
elseif($type == 'Snooker')
{
  $home_win = 0;
  $away_win = 0;

  $check_home_win = 0;
  $check_away_win = 0;

// Game 1    
  if($scores_all_1[0] > $scores_all_1[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'A_win_0_0, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_1[0] < $scores_all_1[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_0, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif(($scores_all_1[0] == $scores_all_1[4]))
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_0, 0,';
  }
  //echo($players_home_1[$j] . "<br>");

  if($scores_all_2[0] > $scores_all_2[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'A_win_0_1, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_2[0] < $scores_all_2[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_1, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_2[0] == $scores_all_2[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_1, 0,';
  }

  if($scores_all_3[0] > $scores_all_3[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'A_win_0_2, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_3[0] < $scores_all_3[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_2, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_3[0] == $scores_all_3[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_2, 0,';
  }


  // Finals (player 1)
  if($scores_all_4[0] > $scores_all_4[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'A_win_0_3, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_4[0] < $scores_all_4[4])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_3, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif(($scores_all_4[0] == $scores_all_4[4]))
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_3, 0,';
  }

//2
  if($scores_all_1[1] > $scores_all_1[5])
  {
    $players_home_2[$j] = $players_home_2[$j] . " " . 'A_win_1_0, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_1[1] < $scores_all_1[5])
  {
    $players_home_2[$j] = $players_home_2[$j] . " " . 'B_win_1_0, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_1[1] == $scores_all_1[5])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_1_0, 0,';
  }

  if($scores_all_2[1] > $scores_all_2[5])
  {
    $players_home_2[$j] = $players_home_2[$j] . " " . 'A_win_1_1, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_2[1] < $scores_all_2[5])
  {
    $players_home_2[$j] = $players_home_2[$j] . " " . 'B_win_1_1, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_2[1] == $scores_all_2[5])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_1_2, 0,';
  }

  if($scores_all_3[1] > $scores_all_3[5])
  {
    $players_home_2[$j] = $players_home_2[$j] . " " . 'A_win_1_2, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_3[1] < $scores_all_3[5])
  {
    $players_home_2[$j] = $players_home_2[$j] . " " . 'B_win_1_2, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_3[1] == $scores_all_3[5])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_1_2, 0,';
  }

//3
  if($scores_all_1[2] > $scores_all_1[6])
  {
    $players_home_3[$j] = $players_home_3[$j] . " " . 'A_win_2_0, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  else if($scores_all_1[2] < $scores_all_1[6])
  {
    $players_home_3[$j] = $players_home_3[$j] . " " . 'B_win_2_0, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_1[2] == $scores_all_1[6])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_2_0, 0,';
  }

  if($scores_all_2[2] > $scores_all_2[6])
  {
    $players_home_3[$j] = $players_home_3[$j] . " " . 'A_win_2_1, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_2[2] < $scores_all_2[6])
  {
    $players_home_3[$j] = $players_home_3[$j] . " " . 'B_win_2_1, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_2[2] == $scores_all_2[6])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_2_1, 0,';
  }

  if($scores_all_3[2] > $scores_all_3[6])
  {
    $players_home_3[$j] = $players_home_3[$j] . " " . 'A_win_2_2, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_3[2] < $scores_all_3[6])
  {
    $players_home_3[$j] = $players_home_3[$j] . " " . 'B_win_2_2, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_3[2] == $scores_all_3[6])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_2_2, 0,';
  }

// 4
  if($scores_all_1[3] > $scores_all_1[7])
  {
    $players_home_4[$j] = $players_home_4[$j] . " " . 'A_win_3_0, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_1[3] < $scores_all_1[7])
  {
    $players_home_4[$j] = $players_home_4[$j] . " " . 'B_win_3_0, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_1[3] == $scores_all_1[7])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_3_0, 0,';
  }

  if($scores_all_2[3] > $scores_all_2[7])
  {
    $players_home_4[$j] = $players_home_4[$j] . " " . 'A_win_3_1, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_2[3] < $scores_all_2[7])
  {
    $players_home_4[$j] = $players_home_4[$j] . " " . 'B_win_3_1, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_2[3] == $scores_all_2[7])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_3_1, 0,';
  }

  if($scores_all_3[3] > $scores_all_3[7])
  {
    $players_home_4[$j] = $players_home_4[$j] . " " . 'A_win_3_2, 1,';
    $home_win = ($home_win + 1);
    $check_home_win = ($check_home_win + 1);
  }
  elseif($scores_all_3[3] < $scores_all_3[7])
  {
    $players_home_4[$j] = $players_home_4[$j] . " " . 'B_win_3_2, 1,';
    $away_win = ($away_win + 1);
    $check_away_win = ($check_away_win + 1);
  }
  elseif($scores_all_3[3] == $scores_all_3[7])
  {
    $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_3_2, 0,';
  }

  // added 08/06/2025 Player forfeit for snooker
  if($play_pos_home != '')
  {
    //echo("Home true " . $play_pos . " (" . $j . ")<br>");
    switch($play_pos)
    {
      case 1:
        $players_home_1[$j] = 'B_win_0_0, 1,';
        $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_1, 1,';
        $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_2, 1,';
        //echo("Case " . $players_home_1[$j] . "<br>");
        $away_win = ($away_win + 3);
        break;
      case 2:
        $players_home_2[$j] = 'B_win_1_0, 1,';
        $players_home_2[$j] = $players_home_2[$j] . " " . 'B_win_1_1, 1,';
        $players_home_2[$j] = $players_home_2[$j] . " " . 'B_win_1_2, 1,';
        $away_win = ($away_win + 3);
        break;
      case 3:
        $players_home_3[$j] = 'B_win_2_0, 1,';
        $players_home_3[$j] = $players_home_3[$j] . " " . 'B_win_2_1, 1,';
        $players_home_3[$j] = $players_home_3[$j] . " " . 'B_win_2_2, 1,';
        $away_win = ($away_win + 3);
        break;
      case 4:
        $players_home_4[$j] = 'B_win_3_0, 1,';
        $players_home_4[$j] = $players_home_4[$j] . " " . 'B_win_3_1, 1,';
        $players_home_4[$j] = $players_home_4[$j] . " " . 'B_win_3_2, 1,';
        $away_win = ($away_win + 3);
        break;
    }
  }
  if($play_pos_away != '')
  {
    //echo("Away true " . $play_pos . "<br>");
    switch($play_pos)
    {
      case 1:
        $players_home_1[$j] = 'A_win_0_0, 1,';
        $players_home_1[$j] = $players_home_1[$j] . " " . 'A_win_0_1, 1,';
        $players_home_1[$j] = $players_home_1[$j] . " " . 'A_win_0_2, 1,';
        $home_win = ($home_win + 3);
        break;
      case 2:
        $players_home_2[$j] = 'A_win_1_0, 1,';
        $players_home_2[$j] = $players_home_2[$j] . " " . 'A_win_1_1, 1,';
        $players_home_2[$j] = $players_home_2[$j] . " " . 'A_win_1_2, 1,';
        $home_win = ($home_win + 3);
        break;
      case 3:
        $players_home_3[$j] = 'A_win_2_0, 1,';
        $players_home_3[$j] = $players_home_3[$j] . " " . 'A_win_2_1, 1,';
        $players_home_3[$j] = $players_home_3[$j] . " " . 'A_win_2_2, 1,';
        $home_win = ($home_win + 3);
        break;
      case 4:
        $players_home_4[$j] = 'A_win_3_0, 1,';
        $players_home_4[$j] = $players_home_4[$j] . " " . 'A_win_3_1, 1,';
        $players_home_4[$j] = $players_home_4[$j] . " " . 'A_win_3_2, 1,';
        $home_win = ($home_win + 3);
        break;
    }
  }

  $j++;
  $players_home_5[0] = 'A_wins, ' . $home_win . ', B_wins, ' . $away_win . ',';
  $players_home_6[0] = 'home_ok, ' . $home_approve . ", away_ok, " . $away_approve . ',';

  // for finals if any array's are empty.
  if(empty($players_home_1))
  {
    $players_home_1 = [];
  }
  if(empty($players_home_2))
  {
    $players_home_2 = [];
  }
  if(empty($players_home_3))
  {
    $players_home_3 = [];
  }
  if(empty($players_home_4))
  {
    $players_home_4 = [];
  }

  $players_all = array_merge($players_home_1, $players_home_2, $players_home_3, $players_home_4, $players_home_5, $players_home_6);
}

//echo("<pre>");
//echo(var_dump($players_home_4) . "<br>");
//echo("</pre>");


echo(json_encode($players_all));


?>
