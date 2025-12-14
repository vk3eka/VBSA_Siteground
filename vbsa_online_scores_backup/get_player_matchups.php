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

// function to calculate scores from stick score and tier
function TierCalc($player1_tier, $player2_tier, $player1_stick, $player2_stick)
{
  $tier_diff = abs($player1_tier-$player2_tier);
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

// get score matchups
$sql_score_home = "Select * from tbl_scoresheet where team = '" . $home . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $year . " AND team_grade = '" . $team_grade . "' Order By playing_position";
$result_score_home = $dbcnx_client->query($sql_score_home) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$j = 0;
//echo($sql_score_home . "<br>");

while ($build_score_home = $result_score_home->fetch_assoc()) 
{
  if($type == 'Billiards')
  {
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
  }
  $tier_home[$j] = ($build_score_home['tier']); 

  $home_approve = $build_score_home['capt_home'];
  $j++;
}

$sql_score_away = "Select * from tbl_scoresheet where team = '" . $away . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $year . " AND team_grade = '" . $team_grade . "' Order By playing_position";
$result_score_away = $dbcnx_client->query($sql_score_away) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$j = 0;
//echo($sql_score_away . "<br>");
while ($build_score_away = $result_score_away->fetch_assoc()) 
{
  if($type == 'Billiards')
  {
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
  }
/*
  $scores_away_1[$j] = ($build_score_away['score_1']); 
  $scores_away_2[$j] = ($build_score_away['score_2']); 
  $scores_away_3[$j] = ($build_score_away['score_3']); 
  $scores_away_4[$j] = ($build_score_away['score_4']); 
*/
  $tier_away[$j] = ($build_score_away['tier']); 

  $away_approve = $build_score_away['capt_home'];
  $j++;
}


$scores_all_1 = array_merge($scores_home_1, $scores_away_1);
$scores_all_2 = array_merge($scores_home_2, $scores_away_2);
$scores_all_3 = array_merge($scores_home_3, $scores_away_3);
$scores_all_4 = array_merge($scores_home_4, $scores_away_4);

$tier_all = array_merge($tier_home, $tier_away);

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


  // game 1
  $score_arr_0 = TierCalc($tier_all[0], $tier_all[4], $scores_all_1[0], $scores_all_1[4]);
  $scores_0 = explode(", ", $score_arr_0);
  $players_tier_1[0] = number_format($scores_0[0], 1);
  $players_tier_1[1] = number_format($scores_0[1], 1);

  // game 2
  $score_arr_1 = TierCalc($tier_all[1], $tier_all[5], $scores_all_1[1], $scores_all_1[5]);
  $scores_1 = explode(", ", $score_arr_1);
  $players_tier_2[0] = number_format($scores_1[0], 1);
  $players_tier_2[1] = number_format($scores_1[1], 1);

  // game 3
  $score_arr_2 = TierCalc($tier_all[2], $tier_all[6], $scores_all_1[2], $scores_all_1[6]);
  $scores_2 = explode(", ", $score_arr_2);
  $players_tier_3[0] = number_format($scores_2[0], 1);
  $players_tier_3[1] = number_format($scores_2[1], 1);

  // game 4
  $score_arr_3 = TierCalc($tier_all[3], $tier_all[7], $scores_all_1[3], $scores_all_1[7]);
  $scores_3 = explode(", ", $score_arr_3);
  $players_tier_4[0] = number_format($scores_3[0], 1);
  $players_tier_4[1] = number_format($scores_3[1], 1);

  $scores_all = array_merge($players_tier_1, $players_tier_2, $players_tier_3, $players_tier_4);
/*  
  echo("Tier 1<br>");
  echo("<pre>");
  echo(var_dump($players_tier_1) . "<br>");
  echo("</pre>");

  echo("Tier 2<br>");
  echo("<pre>");
  echo(var_dump($players_tier_2) . "<br>");
  echo("</pre>");

  echo("Calculated scores<br>");
  echo("<pre>");
  echo(var_dump($scores_all) . "<br>");
  echo("</pre>");

  echo("Stick scores<br>");
  echo("<pre>");
  echo(var_dump($scores_all_1) . "<br>");
  echo("</pre>");
  
  
  echo("1. " . $scores_all[0] . " = " . $scores_all[4] . "<br>");
  echo("2. " . $scores_all[1] . " = " . $scores_all[5] . "<br>");
  echo("3. " . $scores_all[2] . " = " . $scores_all[6] . "<br>");
  echo("4. " . $scores_all[3] . " = " . $scores_all[7] . "<br>");

  echo("1. " . $scores_all[0] . " = " . $scores_all[4] . "<br>");
  echo("2. " . $scores_all[1] . " = " . $scores_all[5] . "<br>");
  echo("3. " . $scores_all[2] . " = " . $scores_all[6] . "<br>");
  echo("4. " . $scores_all[3] . " = " . $scores_all[7] . "<br>");
 */
  
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
    
    // stick scores
    /*    
    echo("<pre>");
    echo(var_dump($scores_all_1) . "<br>");
    echo("</pre>");

    echo("<pre>");
    echo(var_dump($tier_all) . "<br>");
    echo("</pre>");
    */
    /*
    // game 1
    $score_arr = TierCalc($tier_all[0], $tier_all[4], $scores_all_1[0], $scores_all_1[4]);
    $scores = explode(", ", $score_arr);
    $players_tier_1[0] = number_format($scores[0], 1);
    $players_tier_1[1] = number_format($scores[1], 1);

    // game 2
    $score_arr = TierCalc($tier_all[1], $tier_all[5], $scores_all_1[1], $scores_all_1[5]);
    $scores = explode(", ", $score_arr);
    $players_tier_2[0] = number_format($scores[0], 1);
    $players_tier_2[1] = number_format($scores[1], 1);

    // game 3
    $score_arr = TierCalc($tier_all[2], $tier_all[6], $scores_all_1[2], $scores_all_1[6]);
    $scores = explode(", ", $score_arr);
    $players_tier_3[0] = number_format($scores[0], 1);
    $players_tier_3[1] = number_format($scores[1], 1);

    // game 4
    $score_arr = TierCalc($tier_all[3], $tier_all[7], $scores_all_1[3], $scores_all_1[7]);
    $scores = explode(", ", $score_arr);
    $players_tier_4[0] = number_format($scores[0], 1);
    $players_tier_4[1] = number_format($scores[1], 1);
    */
    $players_all = array_merge($players_home_1, $players_home_2, $players_home_3, $players_home_4, $players_home_5, $players_home_6, $players_tier_1, $players_tier_2, $players_tier_3, $players_tier_4, $scores_all_1);
    
    //$scores_all = array_merge($players_tier_1, $players_tier_2, $players_tier_3, $players_tier_4);

//echo("<pre>");
//echo(var_dump($scores_all_1) . "<br>");
//echo("</pre>");



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
//echo(var_dump($players_all) . "<br>");
//echo("</pre>");


echo(json_encode($players_all));


?>
