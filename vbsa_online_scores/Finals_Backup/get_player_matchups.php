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
/*$players_home_1 = array();
$players_home_2 = array();
$players_home_3 = array();
$players_home_4 = array();
$players_home_5 = array();
$players_home_6 = array();
*/
// get score matchups
$sql_score_home = "Select * from tbl_scoresheet where team = '" . $home . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $year . " AND team_grade = '" . $team_grade . "' Order By playing_position";
  $result_score_home = $dbcnx_client->query($sql_score_home) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $j = 0;
  //echo($sql_score_home . "<br>");

  while ($build_score_home = $result_score_home->fetch_assoc()) 
  {
    $scores_home_1[$j] = ($build_score_home['score_1']); 
    $scores_home_2[$j] = ($build_score_home['score_2']); 
    $scores_home_3[$j] = ($build_score_home['score_3']); 
    $home_approve = $build_score_home['capt_home'];
    $j++;
  }
  $sql_score_away = "Select * from tbl_scoresheet where team = '" . $away . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $year . " AND team_grade = '" . $team_grade . "' Order By playing_position";
  $result_score_away = $dbcnx_client->query($sql_score_away) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $j = 0;
  //echo($sql_score_away . "<br>");
  while ($build_score_away = $result_score_away->fetch_assoc()) 
  {
    $scores_away_1[$j] = ($build_score_away['score_1']); 
    $scores_away_2[$j] = ($build_score_away['score_2']); 
    $scores_away_3[$j] = ($build_score_away['score_3']); 
    $away_approve = $build_score_away['capt_home'];
    $j++;
  }
  $scores_all_1 = array_merge($scores_home_1, $scores_away_1);
  $scores_all_2 = array_merge($scores_home_2, $scores_away_2);
  $scores_all_3 = array_merge($scores_home_3, $scores_away_3);

  //echo("<pre>");
  //echo(var_dump($scores_all_2));
  //echo("</pre>");

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

// 0
    if($scores_all_1[0] > $scores_all_1[4])
    {
      $players_home_1[$j] = $players_home_1[$j] . "" .
                          'A_win_0, 1,' . " " .
                          'B_win_0, 0,' . " ";
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    if($scores_all_1[0] == $scores_all_1[4])
    {
      $players_home_1[$j] = $players_home_1[$j] . "" .
                          'A_draw_0, 0,' . " ";
      $players_home_1[$j] = $players_home_1[$j] . "" .
                          'B_draw_0, 0,' . " ";
    }
    if($scores_all_1[0] == $scores_all_1[4])
    {
      $players_home_1[$j] = $players_home_1[$j] . "" .
                          'A_draw_0, 1,' . " ";
      $home_draw = ($home_draw + 1);
      $check_home_draw = ($check_home_draw + 1);

      $players_home_1[$j] = $players_home_1[$j] . "" .
                          'B_draw_0, 1,' . " ";
      $away_draw = ($away_draw + 1);
      $check_away_draw = ($check_away_draw + 1);
    }
    if($scores_all_1[0] < $scores_all_1[4])
    {
      $players_home_1[$j] = $players_home_1[$j] . "" .
                          'A_win_0, 0,' . " " .
                          'B_win_0, 1,' . " ";
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

// 1
      $i = 1;
      if($scores_all_1[1] > $scores_all_1[5])
      {
       $players_home_2[$j] = $players_home_2[$j] . "" .
                            'A_win_' . $i . ', 1,' . " " .
                            'B_win_' . $i . ', 0,' . " ";
        $home_win = ($home_win + 1);
        $check_home_win = ($check_home_win + 1);
      }
      if($scores_all_1[1] == $scores_all_1[5])
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
      if($scores_all_1[1] < $scores_all_1[5])
      {
        $players_home_2[$j] = $players_home_2[$j] . "" .
                            'A_win_' . $i . ', 0,' . " " .
                            'B_win_' . $i . ', 1,' . " ";
        $away_win = ($away_win + 1);
        $check_away_win = ($check_away_win + 1);
      }

  // 2
      $i = 2;
      if($scores_all_1[2] > $scores_all_1[6])
      {
       $players_home_3[$j] = $players_home_3[$j] . "" .
                            'A_win_' . $i . ', 1,' . " " .
                            'B_win_' . $i . ', 0,' . " ";
        $home_win = ($home_win + 1);
        $check_home_win = ($check_home_win + 1);
      }
      if($scores_all_1[2] == $scores_all_1[6])
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
      if($scores_all_1[2] < $scores_all_1[6])
      {
        $players_home_3[$j] = $players_home_3[$j] . "" .
                            'A_win_' . $i . ', 0,' . " " .
                            'B_win_' . $i . ', 1,' . " ";
        $away_win = ($away_win + 1);
        $check_away_win = ($check_away_win + 1);
      }
   
  // 3
      $i = 3;
      if($scores_all_1[3] > $scores_all_1[7])
      {
       $players_home_4[$j] = $players_home_4[$j] . "" .
                            'A_win_' . $i . ', 1,' . " " .
                            'B_win_' . $i . ', 0,' . " ";
        $home_win = ($home_win + 1);
        $check_home_win = ($check_home_win + 1);
      }
      if($scores_all_1[3] == $scores_all_1[7])
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
      if($scores_all_1[3] < $scores_all_1[7])
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
      
      $players_all = array_merge($players_home_1, $players_home_2, $players_home_3, $players_home_4, $players_home_5, $players_home_6);

      //echo("<pre>");
      //echo(var_dump($players_all));
      //echo("</pre>");

      //echo(json_encode($players_all));
  }
  elseif($type == 'Snooker')
  {
/*
    echo("Snooker");
    echo("<pre>");
    echo(var_dump($scores_all_1)); //first game
    echo("</pre>");
    echo("<pre>");
    echo(var_dump($scores_all_2)); //second game
    echo("</pre>");
    echo("<pre>");
    echo(var_dump($scores_all_3)); //third game
    echo("</pre>");
*/
    $home_win = 0;
    $away_win = 0;

    $check_home_win = 0;
    $check_away_win = 0;

    //echo("A " . $scores_all_1[0] . ", B " . $scores_all_1[4] . ".<br>");
    //echo("A " . $scores_all_1[0] . ", B " . $scores_all_1[4] . ".<br>");
    //echo("A " . $scores_all_1[0] . ", B " . $scores_all_1[4] . ".<br>");
    //echo("A " . $scores_all_1[0] . ", B " . $scores_all_1[4] . ".<br>");
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
    elseif(($scores_all_1[0] == $scores_all_1[4]) && ($scores_all_1[0] == 0) && ($scores_all_1[4] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_0, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
    }

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
    elseif(($scores_all_2[0] == $scores_all_2[4]) && ($scores_all_2[0] == 0) && ($scores_all_2[4] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_1, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
;      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    elseif(($scores_all_3[0] == $scores_all_3[4]) && ($scores_all_3[0] == 0) && ($scores_all_3[4] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_0_2, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
    elseif(($scores_all_1[1] == $scores_all_1[5]) && ($scores_all_1[1] == 0) && ($scores_all_1[5] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_1_0, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
    elseif(($scores_all_2[1] == $scores_all_2[5]) && ($scores_all_2[1] == 0) && ($scores_all_2[5] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_1_2, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
    elseif(($scores_all_3[1] == $scores_all_3[5]) && ($scores_all_3[1] == 0) && ($scores_all_3[5] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_1_2, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
    elseif(($scores_all_1[2] == $scores_all_1[6]) && ($scores_all_1[2] == 0) && ($scores_all_1[6] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_2_0, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
    elseif(($scores_all_2[2] == $scores_all_2[6]) && ($scores_all_2[2] == 0) && ($scores_all_2[6] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_2_1, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
    elseif(($scores_all_3[2] == $scores_all_3[6]) && ($scores_all_3[2] == 0) && ($scores_all_3[6] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_2_2, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
    elseif(($scores_all_1[3] == $scores_all_1[7]) && ($scores_all_1[3] == 0) && ($scores_all_1[7] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_3_0, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
    elseif(($scores_all_2[3] == $scores_all_2[7]) && ($scores_all_2[3] == 0) && ($scores_all_2[7] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_3_1, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
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
    elseif(($scores_all_3[3] == $scores_all_3[7]) && ($scores_all_3[3] == 0) && ($scores_all_3[7] == 0)) // added to zero zero scores (finals)
    {
      $players_home_1[$j] = $players_home_1[$j] . " " . 'B_win_3_2, 0,';
      //$away_win = ($away_win - 1);
      //$check_away_win = ($check_away_win - 1);
    }

    //echo("<pre>");
    //echo(var_dump($players_home_4));
    //echo("</pre>");

    $j++;
    //echo("Away Win " . $away_win . "<br>");
    $players_home_5[0] = 'A_wins, ' . $home_win . ', B_wins, ' . $away_win . ',';
    $players_home_6[0] = 'home_ok, ' . $home_approve . ", away_ok, " . $away_approve . ',';

    $players_all = array_merge($players_home_1, $players_home_2, $players_home_3, $players_home_4, $players_home_5, $players_home_6);

    //echo("<pre>");
    //echo(var_dump($players_all));
    //echo("</pre>");

    //echo(json_encode($players_all));


  }

  //$players_home_6[0] = 'home_ok, ' . $home_approve . ", away_ok, " . $away_approve . ',';

//$players_all = array_merge($players_home_1, $players_home_2, $players_home_3, $players_home_4, $players_home_5, $players_home_6);

//echo("<pre>");
//echo(var_dump($players_all));
//echo("</pre>");

echo(json_encode($players_all));

?>
