<?php
//mysql_select_db($database_connvbsa, $connvbsa);
error_reporting(0);
//echo("Here<br>");
?>
<!-- jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- html2canvas library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js"></script>

<script type='text/javascript'>
function GetTournament(sel) {
  var tournament_id = sel.options[sel.selectedIndex].value;
  document.getElementById("tournament").value = tournament_id;
  document.tournament_draw.submit();
}
</script>
<link rel="stylesheet" type="text/css" href="../Admin_Tournaments/tournament_draw.css">
<br>
<?php
function GetPlayerNumber($total_tourn)
{
  switch ($total_tourn) {
    case ($total_tourn <= 8):
      $total_players = 8;
      break;
    case ($total_tourn <= 16) && ($total_tourn > 8):
      $total_players = 16;
      break;
    case ($total_tourn <= 32) && ($total_tourn > 16):
      $total_players = 32;
      break;
    case ($total_tourn <= 64) && ($total_tourn > 32):
      $total_players = 64;
      break;
    case ($total_tourn <= 128) && ($total_tourn > 64):
      $total_players = 128;
      break;
  }
  return $total_players;
}

function GetMemberName($memberid)
{
  global $connvbsa;
  global $database_connvbsa;
  $query_member_name = 'Select FirstName, LastName FROM vbsa3364_vbsa2.members where MemberID = ' . $memberid;
  $result_member_name = mysql_query($query_member_name, $connvbsa) or die(mysql_error());
  $build_member_name = $result_member_name->fetch_assoc();
  $member_name = $build_member_name['FirstName'] . " " . $build_member_name['LastName'];
  return $member_name;
}

if(isset($_GET['tourn_id']))
{
  $tournament_id = $_GET['tourn_id'];
  $tourn_caption = "(Tournament ID " . $tournament_id . ")";

  // get tournament name
  $query_tourn_name = 'Select *, tournaments.tourn_type as type FROM vbsa3364_vbsa2.tournaments LEFT JOIN calendar ON tournaments.tourn_id = calendar.tourn_id where tournaments.tourn_id = ' . $tournament_id;

  $result_tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
  $build_tourn_name = $result_tourn_name->fetch_assoc();
  $tourn_type = $build_tourn_name['type'];
  
  // check if players already saved in the scoring table
  $query_players = 'Select * FROM vbsa3364_vbsa2.tournament_scores where tourn_id = ' . $tournament_id;
  $result_players = mysql_query($query_players, $connvbsa) or die(mysql_error());
  $total_players = $result_players->num_rows;
  $max_players = GetPlayerNumber($total_players);

  // get date/times from draw dates table

  $query_scores = 'Select * FROM vbsa3364_vbsa2.tournament_scores Left Join tournaments on tournaments.tourn_id = tournament_scores.tourn_id where tournament_scores.tourn_id = ' . $tournament_id;
  $result_scores = mysql_query($query_scores, $connvbsa) or die(mysql_error());
  $total_scores = $result_scores->num_rows;
  $total_players = $result_scores->num_rows;
  /*
  while($build_scores = $result_scores->fetch_assoc())
  {
    $matches_day1 = ($build_scores['matches_day_1']*2);
    $matches_day2 = ($build_scores['matches_day_2']*2);
    $matches_day3 = ($build_scores['matches_day_3']*2);
    $matches_day4 = ($build_scores['matches_day_4']*2);
  }
  */
  echo("<script type='text/javascript'>");
  echo("function fillelementarray() {");
  
  // fill day and time from tourn dates table
  $query_score_table = 'Select * FROM tournament_draw_dates where tourn_id = ' . $tournament_id;
  $result_score_table = mysql_query($query_score_table, $connvbsa) or die(mysql_error());
  $rounds = $total_players;
  echo("console.log('Records " . $result_score_table->num_rows . "');");
  echo("console.log('Rounds " . $rounds . "');");

  $i = 0;
  $j = 0;
  $k = 0;
  $l = 0;
  $m = 0;
  $n = 0;
  $p = 0;
  
  while($build_scores_table = $result_score_table->fetch_assoc())
  {
    if($total_players == 128)
    {
      if(($build_scores_table['match_index'] <= 128))
      {
        $rounds = 128;
        echo("document.getElementById('R" . $rounds . "_time_" . ($i+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($i+1) . "').value = '" . $build_scores_table['day'] . "';");
        $i++;
      }
      if(($build_scores_table['match_index'] > 128) && ($build_scores_table['match_index'] < (128+64+1)))
      {
        $rounds = 64;
        echo("document.getElementById('R" . $rounds . "_time_" . ($j+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($j+1) . "').value = '" . $build_scores_table['day'] . "';");
        $j++;
      }
      if(($build_scores_table['match_index'] > (128+64)) && ($build_scores_table['match_index'] < (128+64+32+1)))
      {
        $rounds = 32;
        echo("document.getElementById('R" . $rounds . "_time_" . ($k+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($k+1) . "').value = '" . $build_scores_table['day'] . "';");
        $k++;
      }
      if(($build_scores_table['match_index'] > (128+64+32)) && ($build_scores_table['match_index'] < (128+64+32+16+1)))
      {
        $rounds = 16;
        echo("document.getElementById('R" . $rounds . "_time_" . ($l+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($l+1) . "').value = '" . $build_scores_table['day'] . "';");
        $l++;
      }
      if(($build_scores_table['match_index'] > (128+64+32+16)) && ($build_scores_table['match_index'] < (128+64+32+16+8+1)))
      {
        $rounds = 8;
        echo("document.getElementById('R" . $rounds . "_time_" . ($m+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($m+1) . "').value = '" . $build_scores_table['day'] . "';");
        $m++;
      }
      if(($build_scores_table['match_index'] > (128+64+32+16+8)) && ($build_scores_table['match_index'] < (128+64+32+16+8+4+1)))
      {
        $rounds = 4;
        echo("document.getElementById('R" . $rounds . "_time_" . ($n+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($n+1) . "').value = '" . $build_scores_table['day'] . "';");
        $n++;
      }
      if(($build_scores_table['match_index'] > (128+64+32+16+8+4)) && ($build_scores_table['match_index'] < (128+64+32+16+8+4+2+1)))
      {
        $rounds = 2;
        echo("document.getElementById('R" . $rounds . "_time_" . ($o+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($o+1) . "').value = '" . $build_scores_table['day'] . "';");
        $o++;
      }
    }
    
    if($total_players == 64)
    {
      if(($build_scores_table['match_index'] <= 64))
      {
        $rounds = 64;
        echo("document.getElementById('R" . $rounds . "_time_" . ($j+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($j+1) . "').value = '" . $build_scores_table['day'] . "';");
        $j++;
      }
      if(($build_scores_table['match_index'] > 64) && ($build_scores_table['match_index'] < (64+32+1)))
      {
        $rounds = 32;
        echo("document.getElementById('R" . $rounds . "_time_" . ($k+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($k+1) . "').value = '" . $build_scores_table['day'] . "';");
        $k++;
      }
      if(($build_scores_table['match_index'] > (64+32)) && ($build_scores_table['match_index'] < (64+32+16+1)))
      {
        $rounds = 16;
        echo("document.getElementById('R" . $rounds . "_time_" . ($l+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($l+1) . "').value = '" . $build_scores_table['day'] . "';");
        $l++;
      }
      if(($build_scores_table['match_index'] > (64+32+16)) && ($build_scores_table['match_index'] < (64+32+16+8+1)))
      {
        $rounds = 8;
        echo("document.getElementById('R" . $rounds . "_time_" . ($m+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($m+1) . "').value = '" . $build_scores_table['day'] . "';");
        $m++;
      }
      if(($build_scores_table['match_index'] > (64+32+16+8)) && ($build_scores_table['match_index'] < (64+32+16+8+4+1)))
      {
        $rounds = 4;
        echo("document.getElementById('R" . $rounds . "_time_" . ($n+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($n+1) . "').value = '" . $build_scores_table['day'] . "';");
        $n++;
      }
      if(($build_scores_table['match_index'] > (64+32+16+8+4)) && ($build_scores_table['match_index'] < (64+32+16+8+4+2+1)))
      {
        $rounds = 2;
        echo("document.getElementById('R" . $rounds . "_time_" . ($o+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($o+1) . "').value = '" . $build_scores_table['day'] . "';");
        $o++;
      }
    }

    if($total_players == 32)
    {
      if(($build_scores_table['match_index'] <= 32))
      {
        $rounds = 32;
        echo("document.getElementById('R" . $rounds . "_time_" . ($j+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($j+1) . "').value = '" . $build_scores_table['day'] . "';");
        $j++;
      }
      if(($build_scores_table['match_index'] > 32) && ($build_scores_table['match_index'] < (32+16+1)))
      {
        $rounds = 16;
        echo("document.getElementById('R" . $rounds . "_time_" . ($k+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($k+1) . "').value = '" . $build_scores_table['day'] . "';");
        $k++;
      }
      if(($build_scores_table['match_index'] > (32+16)) && ($build_scores_table['match_index'] < (32+16+8+1)))
      {
        $rounds = 8;
        echo("document.getElementById('R" . $rounds . "_time_" . ($l+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($l+1) . "').value = '" . $build_scores_table['day'] . "';");
        $l++;
      }
      if(($build_scores_table['match_index'] > (32+16+8)) && ($build_scores_table['match_index'] < (32+16+8+4+1)))
      {
        $rounds = 4;
        echo("document.getElementById('R" . $rounds . "_time_" . ($m+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($m+1) . "').value = '" . $build_scores_table['day'] . "';");
        $m++;
      }
      if(($build_scores_table['match_index'] > (32+16+8+4)) && ($build_scores_table['match_index'] < (32+16+8+4+2+1)))
      {
        $rounds = 2;
        echo("document.getElementById('R" . $rounds . "_time_" . ($n+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($n+1) . "').value = '" . $build_scores_table['day'] . "';");
        $n++;
      }
    }

    if($total_players == 16)
    {
      if(($build_scores_table['match_index'] <= 16))
      {
        $rounds = 16;
        echo("document.getElementById('R" . $rounds . "_time_" . ($k+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($k+1) . "').value = '" . $build_scores_table['day'] . "';");
        $k++;
      }
      if(($build_scores_table['match_index'] > 16) && ($build_scores_table['match_index'] < (16+8+1)))
      {
        $rounds = 8;
        echo("document.getElementById('R" . $rounds . "_time_" . ($l+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($l+1) . "').value = '" . $build_scores_table['day'] . "';");
        $l++;
      }
      if(($build_scores_table['match_index'] > (16+8)) && ($build_scores_table['match_index'] < (16+8+4+1)))
      {
        $rounds = 4;
        echo("document.getElementById('R" . $rounds . "_time_" . ($m+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($m+1) . "').value = '" . $build_scores_table['day'] . "';");
        $m++;
      }
      if(($build_scores_table['match_index'] > (16+8+4)) && ($build_scores_table['match_index'] < (16+8+4+2+1)))
      {
        $rounds = 2;
        echo("document.getElementById('R" . $rounds . "_time_" . ($n+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($n+1) . "').value = '" . $build_scores_table['day'] . "';");
        $n++;
      }
    }

    if($total_players == 8)
    {
      if(($build_scores_table['match_index'] <= 8))
      {
        $rounds = 8;
        echo("document.getElementById('R" . $rounds . "_time_" . ($l+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($l+1) . "').value = '" . $build_scores_table['day'] . "';");
        $l++;
      }
      if(($build_scores_table['match_index'] > 8) && ($build_scores_table['match_index'] < (8+4+1)))
      {
        $rounds = 4;
        echo("document.getElementById('R" . $rounds . "_time_" . ($m+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($m+1) . "').value = '" . $build_scores_table['day'] . "';");
        $m++;
      }
      if(($build_scores_table['match_index'] > (8+4)) && ($build_scores_table['match_index'] < (8+4+2+1)))
      {
        $rounds = 2;
        echo("document.getElementById('R" . $rounds . "_time_" . ($n+1) . "').value = '" . $build_scores_table['time'] . "';");
        echo("document.getElementById('R" . $rounds . "_day_" . ($n+1) . "').value = '" . $build_scores_table['day'] . "';");
        $n++;
      }
    }
  }

  while($build_scores = $result_scores->fetch_assoc())
  {
    if($build_scores['member_id'] >= 10000)
    {
        $member_name = "Bye";
    }
    else
    {
        $member_name = GetMemberName($build_scores['member_id']);
    }
    if($build_scores['r_128_position'] != 0)
    {
      $R128_Pos = $build_scores['r_128_position'];
      echo("document.getElementById('R128_name_" . $R128_Pos . "').value = '" . $member_name . "';");
      echo("document.getElementById('R128_id_" . $R128_Pos . "').value = " . $build_scores['member_id'] . ";");
      if($build_scores['r_128_game_1'] == 0)
      {
        echo("document.getElementById('R128_score_" . $R128_Pos . "').value = " . $build_scores['r_128_game_2'] . ";");
      }
      else
      {
        echo("document.getElementById('R128_score_" . $R128_Pos . "').value = " . $build_scores['r_128_game_1'] . ";");
      }
      // modal R128
      echo("document.getElementById('score1_1').value = '" . $build_scores['r_128_score_1'] . "';");
      echo("document.getElementById('score2_1').value = '" . $build_scores['r_128_score_2'] . "';");
      echo("document.getElementById('score3_1').value = '" . $build_scores['r_128_score_3'] . "';");
      echo("document.getElementById('score4_1').value = '" . $build_scores['r_128_score_4'] . "';");
      echo("document.getElementById('score5_1').value = '" . $build_scores['r_128_score_5'] . "';");
      echo("document.getElementById('score6_1').value = '" . $build_scores['r_128_score_6'] . "';");
      echo("document.getElementById('score7_1').value = '" . $build_scores['r_128_score_7'] . "';");

      echo("document.getElementById('brk1_1').value = '" . $build_scores['r_128_breaks_1'] . "';");
      echo("document.getElementById('brk2_1').value = '" . $build_scores['r_128_breaks_2'] . "';");
      echo("document.getElementById('brk3_1').value = '" . $build_scores['r_128_breaks_3'] . "';");
      echo("document.getElementById('brk4_1').value = '" . $build_scores['r_128_breaks_4'] . "';");
      echo("document.getElementById('brk5_1').value = '" . $build_scores['r_128_breaks_5'] . "';");
      echo("document.getElementById('brk6_1').value = '" . $build_scores['r_128_breaks_6'] . "';");
      echo("document.getElementById('brk7_1').value = '" . $build_scores['r_128_breaks_7'] . "';");

      echo("document.getElementById('score1_2').value = '" . $build_scores['r_128_score_1'] . "';");
      echo("document.getElementById('score2_2').value = '" . $build_scores['r_128_score_2'] . "';");
      echo("document.getElementById('score3_2').value = '" . $build_scores['r_128_score_3'] . "';");
      echo("document.getElementById('score4_2').value = '" . $build_scores['r_128_score_4'] . "';");
      echo("document.getElementById('score5_2').value = '" . $build_scores['r_128_score_5'] . "';");
      echo("document.getElementById('score6_2').value = '" . $build_scores['r_128_score_6'] . "';");
      echo("document.getElementById('score7_2').value = '" . $build_scores['r_128_score_7'] . "';");

      echo("document.getElementById('brk1_2').value = '" . $build_scores['r_128_breaks_1'] . "';");
      echo("document.getElementById('brk2_2').value = '" . $build_scores['r_128_breaks_2'] . "';");
      echo("document.getElementById('brk3_2').value = '" . $build_scores['r_128_breaks_3'] . "';");
      echo("document.getElementById('brk4_2').value = '" . $build_scores['r_128_breaks_4'] . "';");
      echo("document.getElementById('brk5_2').value = '" . $build_scores['r_128_breaks_5'] . "';");
      echo("document.getElementById('brk6_2').value = '" . $build_scores['r_128_breaks_6'] . "';");
      echo("document.getElementById('brk7_2').value = '" . $build_scores['r_128_breaks_7'] . "';");
    }
    if($build_scores['r_64_position'] != 0)
    {
      $R64_Pos = $build_scores['r_64_position'];
      echo("document.getElementById('R64_name_" . $R64_Pos . "').value = '" . $member_name . "';");
      echo("document.getElementById('R64_id_" . $R64_Pos . "').value = " . $build_scores['member_id'] . ";");
      if($build_scores['r_64_game_1'] == 0)
      {
        echo("document.getElementById('R64_score_" . $R64_Pos . "').value = " . $build_scores['r_64_game_2'] . ";");
      }
      else
      {
        echo("document.getElementById('R64_score_" . $R64_Pos . "').value = " . $build_scores['r_64_game_1'] . ";");
      }
      // modal R64
      echo("document.getElementById('score1_1').value = '" . $build_scores['r_64_score_1'] . "';");
      echo("document.getElementById('score2_1').value = '" . $build_scores['r_64_score_2'] . "';");
      echo("document.getElementById('score3_1').value = '" . $build_scores['r_64_score_3'] . "';");
      echo("document.getElementById('score4_1').value = '" . $build_scores['r_64_score_4'] . "';");
      echo("document.getElementById('score5_1').value = '" . $build_scores['r_64_score_5'] . "';");
      echo("document.getElementById('score6_1').value = '" . $build_scores['r_64_score_6'] . "';");
      echo("document.getElementById('score7_1').value = '" . $build_scores['r_64_score_7'] . "';");

      echo("document.getElementById('brk1_1').value = '" . $build_scores['r_64_breaks_1'] . "';");
      echo("document.getElementById('brk2_1').value = '" . $build_scores['r_64_breaks_2'] . "';");
      echo("document.getElementById('brk3_1').value = '" . $build_scores['r_64_breaks_3'] . "';");
      echo("document.getElementById('brk4_1').value = '" . $build_scores['r_64_breaks_4'] . "';");
      echo("document.getElementById('brk5_1').value = '" . $build_scores['r_64_breaks_5'] . "';");
      echo("document.getElementById('brk6_1').value = '" . $build_scores['r_64_breaks_6'] . "';");
      echo("document.getElementById('brk7_1').value = '" . $build_scores['r_64_breaks_7'] . "';");

      echo("document.getElementById('score1_2').value = '" . $build_scores['r_64_score_1'] . "';");
      echo("document.getElementById('score2_2').value = '" . $build_scores['r_64_score_2'] . "';");
      echo("document.getElementById('score3_2').value = '" . $build_scores['r_64_score_3'] . "';");
      echo("document.getElementById('score4_2').value = '" . $build_scores['r_64_score_4'] . "';");
      echo("document.getElementById('score5_2').value = '" . $build_scores['r_64_score_5'] . "';");
      echo("document.getElementById('score6_2').value = '" . $build_scores['r_64_score_6'] . "';");
      echo("document.getElementById('score7_2').value = '" . $build_scores['r_64_score_7'] . "';");

      echo("document.getElementById('brk1_2').value = '" . $build_scores['r_64_breaks_1'] . "';");
      echo("document.getElementById('brk2_2').value = '" . $build_scores['r_64_breaks_2'] . "';");
      echo("document.getElementById('brk3_2').value = '" . $build_scores['r_64_breaks_3'] . "';");
      echo("document.getElementById('brk4_2').value = '" . $build_scores['r_64_breaks_4'] . "';");
      echo("document.getElementById('brk5_2').value = '" . $build_scores['r_64_breaks_5'] . "';");
      echo("document.getElementById('brk6_2').value = '" . $build_scores['r_64_breaks_6'] . "';");
      echo("document.getElementById('brk7_2').value = '" . $build_scores['r_64_breaks_7'] . "';");
    }
    if($build_scores['r_32_position'] != 0)
    {
      $R32_Pos = $build_scores['r_32_position'];
      echo("document.getElementById('R32_name_" . $R32_Pos . "').value = '" . $member_name . "';");
      echo("document.getElementById('R32_id_" . $R32_Pos . "').value = " . $build_scores['member_id'] . ";");
      if($build_scores['r_32_game_1'] == 0)
      {
        echo("document.getElementById('R32_score_" . $R32_Pos . "').value = " . $build_scores['r_32_game_2'] . ";");
      }
      else
      {
        echo("document.getElementById('R32_score_" . $R32_Pos . "').value = " . $build_scores['r_32_game_1'] . ";");
      }
      // modal R32
      echo("document.getElementById('score1_1').value = '" . $build_scores['r_32_score_1'] . "';");
      echo("document.getElementById('score2_1').value = '" . $build_scores['r_32_score_2'] . "';");
      echo("document.getElementById('score3_1').value = '" . $build_scores['r_32_score_3'] . "';");
      echo("document.getElementById('score4_1').value = '" . $build_scores['r_32_score_4'] . "';");
      echo("document.getElementById('score5_1').value = '" . $build_scores['r_32_score_5'] . "';");
      echo("document.getElementById('score6_1').value = '" . $build_scores['r_32_score_6'] . "';");
      echo("document.getElementById('score7_1').value = '" . $build_scores['r_32_score_7'] . "';");

      echo("document.getElementById('brk1_1').value = '" . $build_scores['r_32_breaks_1'] . "';");
      echo("document.getElementById('brk2_1').value = '" . $build_scores['r_32_breaks_2'] . "';");
      echo("document.getElementById('brk3_1').value = '" . $build_scores['r_32_breaks_3'] . "';");
      echo("document.getElementById('brk4_1').value = '" . $build_scores['r_32_breaks_4'] . "';");
      echo("document.getElementById('brk5_1').value = '" . $build_scores['r_32_breaks_5'] . "';");
      echo("document.getElementById('brk6_1').value = '" . $build_scores['r_32_breaks_6'] . "';");
      echo("document.getElementById('brk7_1').value = '" . $build_scores['r_32_breaks_7'] . "';");

      echo("document.getElementById('score1_2').value = '" . $build_scores['r_32_score_1'] . "';");
      echo("document.getElementById('score2_2').value = '" . $build_scores['r_32_score_2'] . "';");
      echo("document.getElementById('score3_2').value = '" . $build_scores['r_32_score_3'] . "';");
      echo("document.getElementById('score4_2').value = '" . $build_scores['r_32_score_4'] . "';");
      echo("document.getElementById('score5_2').value = '" . $build_scores['r_32_score_5'] . "';");
      echo("document.getElementById('score6_2').value = '" . $build_scores['r_32_score_6'] . "';");
      echo("document.getElementById('score7_2').value = '" . $build_scores['r_32score_7'] . "';");

      echo("document.getElementById('brk1_2').value = '" . $build_scores['r_32_breaks_1'] . "';");
      echo("document.getElementById('brk2_2').value = '" . $build_scores['r_32_breaks_2'] . "';");
      echo("document.getElementById('brk3_2').value = '" . $build_scores['r_32_breaks_3'] . "';");
      echo("document.getElementById('brk4_2').value = '" . $build_scores['r_32_breaks_4'] . "';");
      echo("document.getElementById('brk5_2').value = '" . $build_scores['r_32_breaks_5'] . "';");
      echo("document.getElementById('brk6_2').value = '" . $build_scores['r_32_breaks_6'] . "';");
      echo("document.getElementById('brk7_2').value = '" . $build_scores['r_32_breaks_7'] . "';");
    }
    if($build_scores['r_16_position'] != 0)
    {
      $R16_Pos = $build_scores['r_16_position'];
      echo("document.getElementById('R16_name_" . $R16_Pos . "').value = '" . $member_name . "';");
      echo("document.getElementById('R16_id_" . $R16_Pos . "').value = " . $build_scores['member_id'] . ";");
      if($build_scores['r_16_game_1'] == 0)
      {
        echo("document.getElementById('R16_score_" . $R16_Pos . "').value = " . $build_scores['r_16_game_2'] . ";");
      }
      else
      {
        echo("document.getElementById('R16_score_" . $R16_Pos . "').value = " . $build_scores['r_16_game_1'] . ";");
      }
      // modal R16
      echo("document.getElementById('score1_1').value = '" . $build_scores['r_16_score_1'] . "';");
      echo("document.getElementById('score2_1').value = '" . $build_scores['r_16_score_2'] . "';");
      echo("document.getElementById('score3_1').value = '" . $build_scores['r_16_score_3'] . "';");
      echo("document.getElementById('score4_1').value = '" . $build_scores['r_16_score_4'] . "';");
      echo("document.getElementById('score5_1').value = '" . $build_scores['r_16_score_5'] . "';");
      echo("document.getElementById('score6_1').value = '" . $build_scores['r_16_score_6'] . "';");
      echo("document.getElementById('score7_1').value = '" . $build_scores['r_16_score_7'] . "';");

      echo("document.getElementById('brk1_1').value = '" . $build_scores['r_16_breaks_1'] . "';");
      echo("document.getElementById('brk2_1').value = '" . $build_scores['r_16_breaks_2'] . "';");
      echo("document.getElementById('brk3_1').value = '" . $build_scores['r_16_breaks_3'] . "';");
      echo("document.getElementById('brk4_1').value = '" . $build_scores['r_16_breaks_4'] . "';");
      echo("document.getElementById('brk5_1').value = '" . $build_scores['r_16_breaks_5'] . "';");
      echo("document.getElementById('brk6_1').value = '" . $build_scores['r_16_breaks_6'] . "';");
      echo("document.getElementById('brk7_1').value = '" . $build_scores['r_16_breaks_7'] . "';");

      echo("document.getElementById('score1_2').value = '" . $build_scores['r_16_score_1'] . "';");
      echo("document.getElementById('score2_2').value = '" . $build_scores['r_16_score_2'] . "';");
      echo("document.getElementById('score3_2').value = '" . $build_scores['r_16_score_3'] . "';");
      echo("document.getElementById('score4_2').value = '" . $build_scores['r_16_score_4'] . "';");
      echo("document.getElementById('score5_2').value = '" . $build_scores['r_16_score_5'] . "';");
      echo("document.getElementById('score6_2').value = '" . $build_scores['r_16_score_6'] . "';");
      echo("document.getElementById('score7_2').value = '" . $build_scores['r_16score_7'] . "';");

      echo("document.getElementById('brk1_2').value = '" . $build_scores['r_16_breaks_1'] . "';");
      echo("document.getElementById('brk2_2').value = '" . $build_scores['r_16_breaks_2'] . "';");
      echo("document.getElementById('brk3_2').value = '" . $build_scores['r_16_breaks_3'] . "';");
      echo("document.getElementById('brk4_2').value = '" . $build_scores['r_16_breaks_4'] . "';");
      echo("document.getElementById('brk5_2').value = '" . $build_scores['r_16_breaks_5'] . "';");
      echo("document.getElementById('brk6_2').value = '" . $build_scores['r_16_breaks_6'] . "';");
      echo("document.getElementById('brk7_2').value = '" . $build_scores['r_16_breaks_7'] . "';");
    }
    
    // R8 - present in all draw's
    $R8_Pos = $build_scores['r_8_position'];
    if($R8_Pos != 0)
    {
      echo("document.getElementById('R8_name_" . $R8_Pos . "').value = '" . $member_name . "';");
      echo("document.getElementById('R8_id_" . $R8_Pos . "').value = " . $build_scores['member_id'] . ";");
      if($build_scores['r_8_game_1'] == 0)
      {
        echo("document.getElementById('R8_score_" . $R8_Pos . "').value = " . $build_scores['r_8_game_2'] . ";");
      }
      else
      {
        echo("document.getElementById('R8_score_" . $R8_Pos . "').value = " . $build_scores['r_8_game_1'] . ";");
      }
      // modal R8
      echo("document.getElementById('score1_1').value = '" . $build_scores['r_8_score_1'] . "';");
      echo("document.getElementById('score2_1').value = '" . $build_scores['r_8_score_2'] . "';");
      echo("document.getElementById('score3_1').value = '" . $build_scores['r_8_score_3'] . "';");
      echo("document.getElementById('score4_1').value = '" . $build_scores['r_8_score_4'] . "';");
      echo("document.getElementById('score5_1').value = '" . $build_scores['r_8_score_5'] . "';");
      echo("document.getElementById('score6_1').value = '" . $build_scores['r_8_score_6'] . "';");
      echo("document.getElementById('score7_1').value = '" . $build_scores['r_8_score_7'] . "';");

      echo("document.getElementById('brk1_1').value = '" . $build_scores['r_8_breaks_1'] . "';");
      echo("document.getElementById('brk2_1').value = '" . $build_scores['r_8_breaks_2'] . "';");
      echo("document.getElementById('brk3_1').value = '" . $build_scores['r_8_breaks_3'] . "';");
      echo("document.getElementById('brk4_1').value = '" . $build_scores['r_8_breaks_4'] . "';");
      echo("document.getElementById('brk5_1').value = '" . $build_scores['r_8_breaks_5'] . "';");
      echo("document.getElementById('brk6_1').value = '" . $build_scores['r_8_breaks_6'] . "';");
      echo("document.getElementById('brk7_1').value = '" . $build_scores['r_8_breaks_7'] . "';");

      echo("document.getElementById('score1_2').value = '" . $build_scores['r_8_score_1'] . "';");
      echo("document.getElementById('score2_2').value = '" . $build_scores['r_8_score_2'] . "';");
      echo("document.getElementById('score3_2').value = '" . $build_scores['r_8_score_3'] . "';");
      echo("document.getElementById('score4_2').value = '" . $build_scores['r_8_score_4'] . "';");
      echo("document.getElementById('score5_2').value = '" . $build_scores['r_8_score_5'] . "';");
      echo("document.getElementById('score6_2').value = '" . $build_scores['r_8_score_6'] . "';");
      echo("document.getElementById('score7_2').value = '" . $build_scores['r_8_score_7'] . "';");

      echo("document.getElementById('brk1_2').value = '" . $build_scores['r_8_breaks_1'] . "';");
      echo("document.getElementById('brk2_2').value = '" . $build_scores['r_8_breaks_2'] . "';");
      echo("document.getElementById('brk3_2').value = '" . $build_scores['r_8_breaks_3'] . "';");
      echo("document.getElementById('brk4_2').value = '" . $build_scores['r_8_breaks_4'] . "';");
      echo("document.getElementById('brk5_2').value = '" . $build_scores['r_8_breaks_5'] . "';");
      echo("document.getElementById('brk6_2').value = '" . $build_scores['r_8_breaks_6'] . "';");
      echo("document.getElementById('brk7_2').value = '" . $build_scores['r_8_breaks_7'] . "';");
    }

    // R4 - present in all draw's
    $R4_Pos = $build_scores['r_4_position'];
    if($R4_Pos != 0)
    {
      echo("document.getElementById('R4_name_" . $R4_Pos . "').value = '" . $member_name . "';");
      echo("document.getElementById('R4_id_" . $R4_Pos . "').value = '" . $build_scores['member_id'] . "';");
      if($build_scores['r_4_game_1'] == 0)
      {
        echo("document.getElementById('R4_score_" . $R4_Pos . "').value = " . $build_scores['r_4_game_2'] . ";");
      }
      else
      {
        echo("document.getElementById('R4_score_" . $R4_Pos . "').value = " . $build_scores['r_4_game_1'] . ";");
      }
      // modal R4
      echo("document.getElementById('score1_1').value = '" . $build_scores['r_4_score_1'] . "';");
      echo("document.getElementById('score2_1').value = '" . $build_scores['r_4_score_2'] . "';");
      echo("document.getElementById('score3_1').value = '" . $build_scores['r_4_score_3'] . "';");
      echo("document.getElementById('score4_1').value = '" . $build_scores['r_4_score_4'] . "';");
      echo("document.getElementById('score5_1').value = '" . $build_scores['r_4_score_5'] . "';");
      echo("document.getElementById('score6_1').value = '" . $build_scores['r_4_score_6'] . "';");
      echo("document.getElementById('score7_1').value = '" . $build_scores['r_4_score_7'] . "';");

      echo("document.getElementById('brk1_1').value = '" . $build_scores['r_4_breaks_1'] . "';");
      echo("document.getElementById('brk2_1').value = '" . $build_scores['r_4_breaks_2'] . "';");
      echo("document.getElementById('brk3_1').value = '" . $build_scores['r_4_breaks_3'] . "';");
      echo("document.getElementById('brk4_1').value = '" . $build_scores['r_4_breaks_4'] . "';");
      echo("document.getElementById('brk5_1').value = '" . $build_scores['r_4_breaks_5'] . "';");
      echo("document.getElementById('brk6_1').value = '" . $build_scores['r_4_breaks_6'] . "';");
      echo("document.getElementById('brk7_1').value = '" . $build_scores['r_4_breaks_7'] . "';");

      echo("document.getElementById('score1_2').value = '" . $build_scores['r_4_score_1'] . "';");
      echo("document.getElementById('score2_2').value = '" . $build_scores['r_4_score_2'] . "';");
      echo("document.getElementById('score3_2').value = '" . $build_scores['r_4_score_3'] . "';");
      echo("document.getElementById('score4_2').value = '" . $build_scores['r_4_score_4'] . "';");
      echo("document.getElementById('score5_2').value = '" . $build_scores['r_4_score_5'] . "';");
      echo("document.getElementById('score6_2').value = '" . $build_scores['r_4_score_6'] . "';");
      echo("document.getElementById('score7_2').value = '" . $build_scores['r_4_score_7'] . "';");

      echo("document.getElementById('brk1_2').value = '" . $build_scores['r_4_breaks_1'] . "';");
      echo("document.getElementById('brk2_2').value = '" . $build_scores['r_4_breaks_2'] . "';");
      echo("document.getElementById('brk3_2').value = '" . $build_scores['r_4_breaks_3'] . "';");
      echo("document.getElementById('brk4_2').value = '" . $build_scores['r_4_breaks_4'] . "';");
      echo("document.getElementById('brk5_2').value = '" . $build_scores['r_4_breaks_5'] . "';");
      echo("document.getElementById('brk6_2').value = '" . $build_scores['r_4_breaks_6'] . "';");
      echo("document.getElementById('brk7_2').value = '" . $build_scores['r_4_breaks_7'] . "';");

    }

    // R2 - present in all draw's
    $R2_Pos = $build_scores['r_2_position'];
    if($R2_Pos != 0)
    {
      echo("document.getElementById('R2_name_" . $R2_Pos . "').value = '" . $member_name . "';");
      echo("document.getElementById('R2_id_" . $R2_Pos . "').value = " . $build_scores['member_id'] . ";");
      if($build_scores['r_2_game_1'] == 0)
      {
        echo("document.getElementById('R2_score_" . $R2_Pos . "').value = " . $build_scores['r_2_game_2'] . ";");
      }
      else
      {
        echo("document.getElementById('R2_score_" . $R2_Pos . "').value = " . $build_scores['r_2_game_1'] . ";");
      }
      // modal R2
      echo("document.getElementById('score1_1').value = '" . $build_scores['r_2_score_1'] . "';");
      echo("document.getElementById('score2_1').value = '" . $build_scores['r_2_score_2'] . "';");
      echo("document.getElementById('score3_1').value = '" . $build_scores['r_2_score_3'] . "';");
      echo("document.getElementById('score4_1').value = '" . $build_scores['r_2_score_4'] . "';");
      echo("document.getElementById('score5_1').value = '" . $build_scores['r_2_score_5'] . "';");
      echo("document.getElementById('score6_1').value = '" . $build_scores['r_2_score_6'] . "';");
      echo("document.getElementById('score7_1').value = '" . $build_scores['r_2_score_7'] . "';");

      echo("document.getElementById('brk1_1').value = '" . $build_scores['r_2_breaks_1'] . "';");
      echo("document.getElementById('brk2_1').value = '" . $build_scores['r_2_breaks_2'] . "';");
      echo("document.getElementById('brk3_1').value = '" . $build_scores['r_2_breaks_3'] . "';");
      echo("document.getElementById('brk4_1').value = '" . $build_scores['r_2_breaks_4'] . "';");
      echo("document.getElementById('brk5_1').value = '" . $build_scores['r_2_breaks_5'] . "';");
      echo("document.getElementById('brk6_1').value = '" . $build_scores['r_2_breaks_6'] . "';");
      echo("document.getElementById('brk7_1').value = '" . $build_scores['r_2_breaks_7'] . "';");

      echo("document.getElementById('score1_2').value = '" . $build_scores['r_2_score_1'] . "';");
      echo("document.getElementById('score2_2').value = '" . $build_scores['r_2_score_2'] . "';");
      echo("document.getElementById('score3_2').value = '" . $build_scores['r_2_score_3'] . "';");
      echo("document.getElementById('score4_2').value = '" . $build_scores['r_2_score_4'] . "';");
      echo("document.getElementById('score5_2').value = '" . $build_scores['r_2_score_5'] . "';");
      echo("document.getElementById('score6_2').value = '" . $build_scores['r_2_score_6'] . "';");
      echo("document.getElementById('score7_2').value = '" . $build_scores['r_2_score_7'] . "';");

      echo("document.getElementById('brk1_2').value = '" . $build_scores['r_2_breaks_1'] . "';");
      echo("document.getElementById('brk2_2').value = '" . $build_scores['r_2_breaks_2'] . "';");
      echo("document.getElementById('brk3_2').value = '" . $build_scores['r_2_breaks_3'] . "';");
      echo("document.getElementById('brk4_2').value = '" . $build_scores['r_2_breaks_4'] . "';");
      echo("document.getElementById('brk5_2').value = '" . $build_scores['r_2_breaks_5'] . "';");
      echo("document.getElementById('brk6_2').value = '" . $build_scores['r_2_breaks_6'] . "';");
      echo("document.getElementById('brk7_2').value = '" . $build_scores['r_2_breaks_7'] . "';");
    }

    //R1
    $R1_Pos = $build_scores['r_1_position'];
    if($R1_Pos != 0)
    {
      echo("document.getElementById('R1_name_1').value = '" . trim(GetMemberName($build_scores['member_id'])) . "';");
      echo("document.getElementById('R1_id_1').value = " . $build_scores['member_id'] . ";");
    }
  }
  echo("}");
  echo("window.onload = function()"); 
  echo("{");
  echo("fillelementarray();");
  echo("}");
  echo("</script>");

  echo("<hr>");
  echo("<div align='center'><h3>" . $build_tourn_name['tourn_name'] . "</h3></div>");
  echo("<div align='center'>" . $tourn_caption . "</div>");
  echo("<div hidden align='center' id='tourn_id'>" . $tournament_id . "</div>");
  echo("<br>");
  echo("<div align='center'>Start Date " . $build_tourn_name['startdate'] . " - Finish Date " . $build_tourn_name['finishdate'] . "</div>");
  echo("<br>");
  //echo('<div align="center" class="greenbg"><button id="delete_draw">Reset all Data for This Tournament Draw</button></div>');
  echo("<hr>");

 $total_tourn = $total_scores;
  switch ($total_tourn) {
    case ($total_tourn <= 8):
      $total_players = 8;
      break;
    case ($total_tourn <= 16) && ($total_tourn > 8):
      $total_players = 16;
      break;
    case ($total_tourn <= 32) && ($total_tourn > 16):
      $total_players = 32;
      break;
    case ($total_tourn <= 64) && ($total_tourn > 32):
      $total_players = 64;
      break;
    case ($total_tourn <= 128) && ($total_tourn > 64):
      $total_players = 128;
      break;
  }

  switch ($total_players) {
    case 128:
      $total_rounds = 8;
      $div_width = 200;
      break;
    case 64:
      $total_rounds = 7;
      $div_width = 200;
      break;
    case 32:
      $total_rounds = 6;
      $div_width = 200;
      break;
    case 16:
      $total_rounds = 5;
      $div_width = 228;
      break;
    case 8:
      $total_rounds = 4;
      $div_width = 285;
      break;
  }
?>
<div id='pdf_test'> <!-- start of pdf creattion -->
<table width="300" border="0" align="center">
  <tr>
    <td align='left'><button id="backBtn">Back</button></td>
    <td align='right'><button id="nextBtn">Next</button></td>
  </tr>
</table>

<?php
function isMobile() {
  return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

if(!isMobile())
{
  //$total_rounds = 2;
//}
  echo("<div class='tournament-headers' id='best_of_header' align='center' style='width:" . ($total_rounds*$div_width) . "px'>");

  if($total_players > 64)
  {
    echo("<h4 id='header128'>Round of 128<br>Best of " . $build_tourn_name['best_of_128'] . "</h4>");
  }
  if($total_players > 32)
  {
    echo("<h4 id='header64'>Round of 64<br>Best of " . $build_tourn_name['best_of_64'] . "</h4>");
  }
  if($total_players > 16)
  {
    echo("<h4 id='header32'>Round of 32<br>Best of " . $build_tourn_name['best_of_32'] . "</h4>");
  }
  if($total_players > 8)
  {
    echo("<h4 id='header16'>Round of 16<br>Best of " . $build_tourn_name['best_of_16'] . "</h4>");
  }
  if($total_players > 4)
  {
    echo("<h4 id='header8'>Quarter Finals<br>Best of " . $build_tourn_name['best_of_8'] . "</h4>");
  }
  if($total_players > 2)
  {
    echo("<h4 id='header4'>Semi Finals<br>Best of " . $build_tourn_name['best_of_4'] . "</h4>");
  }
  if($total_players > 1)
  {
    echo("<h4 id='header2'>Grand Final<br>Best of " . $build_tourn_name['best_of_2'] . "</h4>");
    echo("<h4 id='header1'>Winner</h4>");
  }
  echo("</div>");
}
else
{
  $total_rounds = 2;
  //echo("Rounds Visible " . $total_rounds . "<br>");
  //echo("Players " . $total_players . "<br>");
  
  echo("<div class='tournament-headers' align='center' style='width:" . ($total_rounds*$div_width) . "px'>");

  if($total_players > 64)
  {
    echo("<h4 id='header128'>Round of 128<br>Best of " . $build_tourn_name['best_of_128'] . "</h4>");
  }
  if($total_players > 32)
  {
    echo("<h4 id='header64'>Round of 64<br>Best of " . $build_tourn_name['best_of_64'] . "</h4>");
  }
  if($total_players > 16)
  {
    echo("<h4 id='header32'>Round of 32<br>Best of " . $build_tourn_name['best_of_32'] . "</h4>");
  }
  if($total_players > 8)
  {
    echo("<h4 id='header16'>Round of 16<br>Best of " . $build_tourn_name['best_of_16'] . "</h4>");
  }
  if($total_players > 4)
  {
    echo("<h4 id='header8'>Quarter Finals<br>Best of " . $build_tourn_name['best_of_8'] . "</h4>");
  }
  if($total_players > 2)
  {
    echo("<h4 id='header4'>Semi Finals<br>Best of " . $build_tourn_name['best_of_4'] . "</h4>");
  }
  if($total_players > 1)
  {
    echo("<h4 id='header2'>Grand Final<br>Best of " . $build_tourn_name['best_of_2'] . "</h4>");
    echo("<h4 id='header1'>Winner</h4>");
  }
  echo("</div>");
}

// 8 rounds use 1, 2, 4, 8
// 16 rounds use 1, 2, 4, 8, 16
// 32 rounds use 1, 2, 4, 8, 16, 32
// 64 rounds use 1, 2, 4, 8, 16, 32, 64
// 128 rounds use 1, 2, 4, 8, 16, 32, 64, 128

?>
  <div class="tournament-brackets">
  <?php  
  //get tournament players
  // check if players alraedy saved.
  $query_tourn_players_count = 'Select * FROM tournament_scores where tourn_id = ' . $tournament_id ;
  $result_tourn_players_count = mysql_query($query_tourn_players_count, $connvbsa) or die(mysql_error());
  if($result_tourn_players_count->num_rows == 0)
  {
    if($tourn_type == 'Billiards')
    {
       //get tournament players from tourn entries table
      $query_tourn_players = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_Billiards on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . '  and LastName != "" Order by ranknum DESC';

    }
    else if($tourn_type == 'Snooker')
    {
       //get tournament players from tourn entries table
      $query_tourn_players = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_S_open_tourn on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournament_id . '  and LastName != "" Order by total_tourn_rp DESC';
    }
  }
  else
  {
    $query_tourn_players = 'Select * FROM tournament_scores LEFT JOIN members on member_id=MemberID LEFT JOIN tournaments on tournament_scores.tourn_id=tournaments.tourn_id where tournament_scores.tourn_id = ' . $tournament_id;
   }
  $result_tourn_players = mysql_query($query_tourn_players, $connvbsa) or die(mysql_error());

  $x = 0; 
  $default_user_image = '';
  //$default_user_image = '../images/change_player_1.png';
  $bye_index = 10000; 
  echo("<div class='bracket bracket-1' content-div' id='content1'>");
  
  while($build_tourn = $result_tourn_players->fetch_assoc())
  {
    if($build_tourn['member_id'] >= 10000)
    {
      $fullname = 'Bye';
    }
    else
    {
      $fullname = $build_tourn['FirstName'] . " " . $build_tourn['LastName'];
    }
    
    echo("<div class='team-item' style='width:200px'><input type='text' value='" . $fullname . "' id='R" . $total_players . "_name_" . ($x+1) . "' style='width:120px'><input type='text' id='R" . $total_players . "_score_" . ($x+1) . "' style='width:20px' readonly value=''>");
    echo("<input type='hidden' value=" . $build_tourn['member_id'] . " id='R" . $total_players . "_id_" . ($x+1) . "' style='width:20px'><br><img src='$default_user_image' id='R" . $total_players . "_change_" . ($i+1) . "' style='width:32px; height:32x'><time><input type='text' id='R" . $total_players . "_day_" . ($x+1) . "' value='" . $build_tourn['r_128_day'] . "' style='width:50px'><input type='text' id='R" . $total_players . "_time_" . ($x+1) . "' value='' style='width:50px'></time></div>");
    $x++;
  }
  echo("</div>");
  
  if($total_players > 64)
  {
    echo("<div class='bracket bracket-2' content-div' id='content2'>");
    for($i = 0; $i < 64; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R64_name_" . ($i+1) . "'  value='' style='width:120px'><input type='text' id='R64_score_" . ($i+1) . "' value='' style='width:20px'>");
      echo("<input type='hidden' value='' id='R64_id_" . ($i+1) . "' style='width:20px'><br><img src='$default_user_image' id='R64_change_" . ($i+1) . "' style='width:32px; height:32x'><time><input type='text' id='R64_day_" . ($i+1) . "' value='' style='width:50px'><input type='text' id='R64_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 32)
  {
    echo("<div class='bracket bracket-2' content-div' id='content3'>");
    for($i = 0; $i < 32; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R32_name_" . ($i+1) . "' value='' style='width:120px'><input type='text' id='R32_score_" . ($i+1) . "' value='' style='width:20px'>");
      echo("<input type='hidden' value='' id='R32_id_" . ($i+1) . "' style='width:20px'><br><img src='$default_user_image' id='R32_change_" . ($i+1) . "' style='width:32px; height:32x'><time><input type='text' id='R32_day_" . ($i+1) . "' value='' style='width:50px'><input type='text' id='R32_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 16)
  {
    echo("<div class='bracket bracket-2' content-div' id='content4'>");
    for($i = 0; $i < 16; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R16_name_" . ($i+1) . "' style='width:120px' value=''><input type='text' id='R16_score_" . ($i+1) . "'  value='' style='width:20px'>");
      echo("<input type='hidden' value='' id='R16_id_" . ($i+1) . "' style='width:20px'><br><img src='$default_user_image' id='R16_change_" . ($i+1) . "' style='width:32px; height:32x'><time><input type='text' id='R16_day_" . ($i+1) . "' value='' style='width:50px'><input type='text' class='time' id='R16_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 8)
  {
    echo("<div class='bracket bracket-3' content-div' id='content5'>");
    for($i = 0; $i < 8; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R8_name_" . ($i+1) . "' style='width:120px' value=''><input type='text' id='R8_score_" . ($i+1) . "' value='' style='width:20px'><input type='hidden' value='' id='R8_id_" . ($i+1) . "' style='width:20px'><br><img src='$default_user_image' id='R8_change_" . ($i+1) . "' style='width:32px; height:32x'><time><input type='text' id='R8_day_" . ($i+1) . "' value='' style='width:50px'><input type='text' id='R8_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 4)
  {
    echo("<div class='bracket bracket-4' content-div' id='content6'>");
    for($i = 0; $i < 4; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R4_name_" . ($i+1) . "' style='width:120px' value=''><input type='text' id='R4_score_" . ($i+1) . "' value='' style='width:20px'><input type='hidden' value='' id='R4_id_" . ($i+1) . "' style='width:20px'><br><img src='$default_user_image' id='R4_change_" . ($i+1) . "' style='width:32px; height:32x'><time><input type='text' id='R4_day_" . ($i+1) . "' value='' style='width:50px'><input type='text' id='R4_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 2)
  {
    echo("<div class='bracket bracket-5' content-div' id='content7'>");
    for($i = 0; $i < 2; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R2_name_" . ($i+1) . "' style='width:120px' value=''><input type='text' id='R2_score_" . ($i+1) . "' value='' style='width:20px'><input type='hidden' value='' id='R2_id_" . ($i+1) . "' style='width:20px'><br><img src='$default_user_image' id='R2_change_" . ($i+1) . "' style='width:32px; height:32x'><time><input type='text' id='R2_day_" . ($i+1) . "' value='' style='width:50px'><input type='text' id='R2_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 1)
  {
    echo("<div class='bracket bracket-6' content-div' id='content8'>");
    echo("<div class='team-item' style='width:200px'><input type='text' id='R1_name_1' style='width:120px' value=''><input type='hidden' value='' id='R1_id_1' style='width:20px'><br></div>");
    echo("</div>");
  }

}
?>
</div> <!-- end of pdf creattion -->
<div align='center'>
<input type="button" value="Download a printable PDF of the draw." onclick="generatePDF()">
</div>
</div>
<script>

$(document).ready(function()
{
  $.fn.get_id_element = function (str) {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var col = str.substring(0, first);
    var col_no = col.substring(1, col.length);
    var second = (str.split(subStr, 2).join(subStr).length);
    var element_no = parseInt(str.substring(second+1));
    return $('#R' + col_no + '_id_' + element_no).val();
  }

  $.fn.get_player = function (str) {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var col = str.substring(0, first);
    var col_no = col.substring(1, col.length);
    var second = (str.split(subStr, 2).join(subStr).length);
    var element_no = parseInt(str.substring(second+1));
    return $('#R' + col_no + '_name_' + element_no).val();
  }

  $.fn.get_day = function (str) {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var col = str.substring(0, first);
    var col_no = col.substring(1, col.length);
    var second = (str.split(subStr, 2).join(subStr).length);
    var element_no = parseInt(str.substring(second+1));
    return $('#R' + col_no + '_day_' + element_no).val();
  }

  $.fn.get_time = function (str) {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var col = str.substring(0, first);
    var col_no = col.substring(1, col.length);
    var second = (str.split(subStr, 2).join(subStr).length);
    var element_no = parseInt(str.substring(second+1));
    return $('#R' + col_no + '_time_' + element_no).val();
  }

  $.fn.get_index_id = function (str) {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var col = str.substring(0, first);
    var col_no = col.substring(1, col.length);
    var second = (str.split(subStr, 2).join(subStr).length);
    var element_no = parseInt(str.substring(second+1));
    var element_other = '';
    if(element_no % 2 == 0)
    {
      // even
      element_other = (element_no-1);
    }
    else
    {
      // odd
      element_other = (element_no+1);
    }
    console.log("Now " + element_no + ", Other " + element_other);
    return element_other;
  }

  $.fn.get_draw = function (str) {
    var subStr = '_';
    var player_name = '';
    var first = (str.split(subStr, 1).join(subStr).length);
    var col = str.substring(0, first);
    var col_no = col.substring(1, col.length);
    var next_index = (col_no/2);
    var second = (str.split(subStr, 2).join(subStr).length);
    var element_no = parseInt(str.substring(second+1));
    var element_before = '';
    if(element_no % 2 == 0)
    {
      element_next = Math.round(element_no/2);
      element_before = ((element_no)-1);
    }
    else
    {
      element_next = Math.round((element_no+1)/2);
      element_before = ((element_no)+1);
    }
    var score_1 = $('#R' + col_no + '_score_' + element_no).val();
    var score_2 = $('#R' + col_no + '_score_' + element_before).val();
    if(score_2 < score_1)
    {
      player_name = $('#R' + col_no + '_name_' + element_no).val();
      player_id = $('#R' + col_no + '_id_' + element_no).val();
    }
    else if(score_1 < score_2)
    {
      player_name = $('#R' + col_no + '_name_' + element_before).val();
      player_id = $('#R' + col_no + '_id_' + element_before).val();
    }
    console.log("Player name from 'get draw' " + player_name + ", column " + (next_index) + ", position " + element_next);
    $('#R' + next_index + '_name_' + element_next).val(player_name);
    $('#R' + next_index + '_id_' + element_next).val(player_id);
    next_player = (player_id + ", " + player_name + ", " + next_index + ", " + element_next);
    return next_player;
  }
  
  $.fn.getScoreData = function (round, member_1, member_2)
  {
    $.ajax({
      url:"../Admin_Tournaments/get_score_data.php?total_players=" + round + "&member_1=" + member_1 + "&member_2=" + member_2,
      method: 'POST',
      success:function(data)
      {
        var newData = data.split(':');
        console.log("Player 1 get " + (newData[0]));
        console.log("Player 2 get " + (newData[1]));
        var member_1_data = newData[0].split(", ");
        for (var i = 0; i < member_1_data.length; i++) 
        {
          console.log(i + " " + member_1_data[i]);
          $('#member_id_1').val(member_1_data[0]);
          if((i > 0) && (i <= 7))
          {
            $('#score' + (i) + '_1').val(member_1_data[(i)]);
          }
          if((i > 7) && (i <= 14))
          {
            $('#brk' + (i-7) + '_1').val(member_1_data[(i)]);
          }
          $('#game_score_1').val(member_1_data[(15)]);
        }

        var member_2_data = newData[1].split(", ");
        for (var i = 0; i < member_2_data.length; i++) 
        {
          console.log(i + " " + member_2_data[i]);
          $('#member_id_2').val(member_2_data[0]);
          if((i > 0) && (i <= 7))
          {
            $('#score' + (i) + '_2').val(member_2_data[(i)]);
          }
          if((i > 7) && (i <= 14))
          {
            $('#brk' + (i-7) + '_2').val(member_2_data[(i)]);
          }
          $('#game_score_2').val(member_2_data[(15)]);
        }
      },
      error: function() 
      {
        alert("There was an error. Try again please!");
      }
    });
  }

  for(i = 0; i < 128; i++)
  {
    $('#R128_day_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R128_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R128_change_' + (i+1)).click(function() {
        var id = $(this).attr('id');
        $('#existing_player').val($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#element_id').html(id);
        $('#change_players_modal').modal('show');
    });

    $('#R128_name_' + (i+1)).click(function() {
        $('#scores_modal').modal('show');
        var id = $(this).attr('id');
        var Memb1 = $.fn.get_id_element(id);
        var Memb2 = $('#R128_id_' + $.fn.get_index_id(id)).val();
        $('#playername_1').html($('#' + id).val());
        $('#playername_2').html($('#R128_name_' + $.fn.get_index_id(id)).val());
        $('#member_id_1').html($.fn.get_id_element(id));
        $('#member_id_2').html($('#R128_id_' + $.fn.get_index_id(id)).val());
        $.fn.getScoreData(128, Memb1, Memb2);
        $('#element_id').html(id);
        $.fn.get_index_id(id);
    });
  }

  for(i = 0; i < 64; i++)
  {
    $('#R64_day_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R64_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R64_change_' + (i+1)).click(function() {
        var id = $(this).attr('id');
        $('#existing_player').val($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#element_id').html(id);
        $('#change_players_modal').modal('show');
    });

    $('#R64_name_' + (i+1)).click(function() {
        $('#scores_modal').modal('show');
        var id = $(this).attr('id');
        var Memb1 = $.fn.get_id_element(id);
        var Memb2 = $('#R64_id_' + $.fn.get_index_id(id)).val();
        $('#playername_1').html($('#' + id).val());
        $('#playername_2').html($('#R64_name_' + $.fn.get_index_id(id)).val());
        $('#member_id_1').html($.fn.get_id_element(id));
        $('#member_id_2').html($('#R64_id_' + $.fn.get_index_id(id)).val());
        $.fn.getScoreData(64, Memb1, Memb2);
        $('#element_id').html(id);
    });
  }

  for(i = 0; i < 32; i++)
  {
    $('#R32_day_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R32_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R32_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_time').val($('#' + id).val());
        $('#element_id').html(id);
    });

    $('#R32_change_' + (i+1)).click(function() {
        var id = $(this).attr('id');
        $('#existing_player').val($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#element_id').html(id);
        $('#change_players_modal').modal('show');
    });

    $('#R32_name_' + (i+1)).click(function() {
        $('#scores_modal').modal('show');
        var id = $(this).attr('id');
        var Memb1 = $.fn.get_id_element(id);
        var Memb2 = $('#R32_id_' + $.fn.get_index_id(id)).val();
        $('#playername_1').html($('#' + id).val());
        $('#playername_2').html($('#R32_name_' + $.fn.get_index_id(id)).val());
        $('#member_id_1').html($.fn.get_id_element(id));
        $('#member_id_2').html($('#R32_id_' + $.fn.get_index_id(id)).val());
        $.fn.getScoreData(32, Memb1, Memb2);
        $('#element_id').html(id);
    });
  }

  for(i = 0; i < 16; i++)
  {
    $('#R16_day_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R16_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R16_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_time').val($('#' + id).val());
        $('#element_id').html(id);
    });

    $('#R16_change_' + (i+1)).click(function() {
        var id = $(this).attr('id');
        $('#existing_player').val($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#element_id').html(id);
        $('#change_players_modal').modal('show');
    });

    $('#R16_name_' + (i+1)).click(function() {
        $('#scores_modal').modal('show');
        var id = $(this).attr('id');
        var Memb1 = $.fn.get_id_element(id);
        var Memb2 = $('#R16_id_' + $.fn.get_index_id(id)).val();
        $('#playername_1').html($('#' + id).val());
        $('#playername_2').html($('#R16_name_' + $.fn.get_index_id(id)).val());
        $('#member_id_1').html($.fn.get_id_element(id));
        $('#member_id_2').html($('#R16_id_' + $.fn.get_index_id(id)).val());
        $.fn.getScoreData(16, Memb1, Memb2);
        $('#element_id').html(id);
    });
  }

  for(i = 0; i < 8; i++)
  {
    $('#R8_day_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R8_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R8_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_time').val($('#' + id).val());
        $('#element_id').html(id);
    });

    $('#R8_change_' + (i+1)).click(function() {
        var id = $(this).attr('id');
        $('#existing_player').val($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#element_id').html(id);
        $('#change_players_modal').modal('show');
    });

    $('#R8_name_' + (i+1)).click(function() {
        $('#scores_modal').modal('show');
        var id = $(this).attr('id');
        var Memb1 = $.fn.get_id_element(id);
        var Memb2 = $('#R8_id_' + $.fn.get_index_id(id)).val();
        $('#playername_1').html($('#' + id).val());
        $('#playername_2').html($('#R8_name_' + $.fn.get_index_id(id)).val());
        $('#member_id_1').html($.fn.get_id_element(id));
        $('#member_id_2').html($('#R8_id_' + $.fn.get_index_id(id)).val());
        $.fn.getScoreData(8, Memb1, Memb2);
        $('#element_id').html(id);
    });
  }

  for(i = 0; i < 4; i++)
  {
    $('#R4_day_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R4_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R4_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_time').val($('#' + id).val());
        $('#element_id').html(id);
    });

    $('#R4_change_' + (i+1)).click(function() {
        var id = $(this).attr('id');
        $('#existing_player').val($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#element_id').html(id);
        $('#change_players_modal').modal('show');
    });

    $('#R4_name_' + (i+1)).click(function() {
        $('#scores_modal').modal('show');
        var id = $(this).attr('id');
        var Memb1 = $.fn.get_id_element(id);
        var Memb2 = $('#R4_id_' + $.fn.get_index_id(id)).val();
        $('#playername_1').html($('#' + id).val());
        $('#playername_2').html($('#R4_name_' + $.fn.get_index_id(id)).val());
        $('#member_id_1').html($.fn.get_id_element(id));
        $('#member_id_2').html($('#R4_id_' + $.fn.get_index_id(id)).val());
        $.fn.getScoreData(4, Memb1, Memb2);
        $('#element_id').html(id);
    });
  }

  for(i = 0; i < 2; i++)
  {
    $('#R2_day_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R2_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_day').val($.fn.get_day(id));
        $('#existing_time').val($.fn.get_time(id));
        $('#element_id').html(id);
    });

    $('#R2_time_' + (i+1)).click(function() {
        $('#edit_time_modal').modal('show');
        var id = $(this).attr('id');
        $('#player_name').html($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#existing_time').val($('#' + id).val());
        $('#element_id').html(id);
    });

    $('#R2_change_' + (i+1)).click(function() {
        var id = $(this).attr('id');
        $('#existing_player').val($.fn.get_player(id));
        $('#member_id').html($.fn.get_id_element(id));
        $('#element_id').html(id);
        $('#change_players_modal').modal('show');
    });

    $('#R2_name_' + (i+1)).click(function() {
        $('#scores_modal').modal('show');
        var id = $(this).attr('id');
        var Memb1 = $.fn.get_id_element(id);
        var Memb2 = $('#R2_id_' + $.fn.get_index_id(id)).val();
        $('#playername_1').html($('#' + id).val());
        $('#playername_2').html($('#R2_name_' + $.fn.get_index_id(id)).val());
        $('#member_id_1').html($.fn.get_id_element(id));
        $('#member_id_2').html($('#R2_id_' + $.fn.get_index_id(id)).val());
        $.fn.getScoreData(2, Memb1, Memb2);
        $('#element_id').html(id);
    });
  }
/*
  $('#save_modal_button').click(function()
  {
    var score_data_1 = [];
    var break_data_1 = [];
    var score_data_2 = [];
    var break_data_2 = [];
    var to_break_data_1 = [];
    var to_break_data_2 = [];
    var scoredata_player_1 = new Array;
    var scoredata_player_2 = new Array;
    var game_score_1 = 0;
    var game_score_2 = 0;
    var tourn_id = $('#tourn_id').html();
    var member_id_1 = $('#member_id_1').html();
    var member_id_2 = $('#member_id_2').html();
    var element_id = $('#element_id').html();

    var referee = $('#referee').html();
    var marker = $('#marker').html();
    var referee = $('#referee').html();
    var table_no = $('#table_no').html();
    var round = $('#round').html();
    var grade = $('#grade').html();
    var start = $('#start').html();
    var finish = $('#finish').html();
    var match_no = $('#match_no').val();

    for(i = 0; i < 7; i++) // max number of 'Best Of'
    {
      score_data_1[i] = $('#score' + (i+1) + '_1').val();
      break_data_1[i] = $('#brk' + (i+1) + '_1').val();
      score_data_2[i] = $('#score' + (i+1) + '_2').val();
      break_data_2[i] = $('#brk' + (i+1) + '_2').val();

      to_break_data_1[i] = $('#to_brk_' + (i+1) + '_1').val();
      to_break_data_2[i] = $('#to_brk' + (i+1) + '_2').val();

      if(score_data_1[i] > score_data_2[i])
      {
        game_score_1++;
        $('#game_score_1').val(game_score_1);
      }
      else if(score_data_1[i] < score_data_2[i])
      {
        game_score_2++;
        $('#game_score_2').val(game_score_2);
      }
    }
    var subStr = '_';
    var first = (element_id.split(subStr, 1).join(subStr).length);
    var col = element_id.substring(0, first);
    var col_no = col.substring(1, col.length);
    var second = (element_id.split(subStr, 2).join(subStr).length);
    var element_no = parseInt(element_id.substring(second+1));
    var element_other = null;
    if(element_no % 2 == 0)
    {
      element_next = Math.round(element_no/2);
      element_other = ((element_no)-1);
    }
    else
    {
      element_next = Math.round(element_no/2);
      element_other = ((element_no)+1);
    }

    console.log("Element ID " + col_no + ", Score Data 1 " + score_data_1);
    console.log("Element ID " + col_no + ", Break Data 1 " + break_data_1);
    console.log("Element ID " + col_no + ", Score Data 2 " + score_data_2);
    console.log("Element ID " + col_no + ", Break Data 2 " + break_data_2);
    console.log("Element Position " + element_no);
    console.log("Element Position (Next) " + element_next);
    console.log("Element Position (Other) " + element_other);
    console.log("Element ID (inc Position) " + element_id);
    console.log("Game Score 1 " + game_score_1 + ", Game Score 2 " + game_score_2);
    
    $('#R' + col_no + '_score_' + element_no).val(game_score_1);
    $('#R' + col_no + '_score_' + element_other).val(game_score_2);
    scoredata_player_1 = 
          col_no + ", " + 
          member_id_1 + ", " + 
          game_score_1 + ", " + 
          score_data_1 + ", " + 
          break_data_1 + ", " + 
          to_break_data_1 + ", " + 
          parseInt(element_no);

    scoredata_player_2 = 
          col_no + ", " + 
          member_id_2 + ", " + 
          game_score_2 + ", " + 
          score_data_2 + ", " + 
          break_data_2 + ", " + 
          to_break_data_2 + ", " + 
          parseInt(element_other);

    scoredata_player_3 = $.fn.get_draw(element_id);

    scoredata_player_4 = 
          referee + ", " + 
          marker + ", " + 
          table_no + ", " + 
          round + ", " + 
          grade + ", " + 
          start + ", " + 
          finish + ", " + 
          match_no;

    scoredata_player_1 = JSON.stringify(scoredata_player_1);
    scoredata_player_2 = JSON.stringify(scoredata_player_2);
    scoredata_player_3 = JSON.stringify(scoredata_player_3);
    scoredata_player_4 = JSON.stringify(scoredata_player_4);

    console.log('Player 1 ' + scoredata_player_1);
    console.log('Player 2 ' + scoredata_player_2);
    console.log('Player 3 ' + scoredata_player_3);
    console.log('Player 4 ' + scoredata_player_4);

    $.ajax({
      url:"save_score_data.php?tourn_id=" + tourn_id + "&score_data_1=" + scoredata_player_1 + "&score_data_2=" + scoredata_player_2 + "&score_data_3=" + scoredata_player_3 + "&score_data_4=" + scoredata_player_4,
      method: 'GET',
      success:function(response)
      {
        for(i = 0; i < 7; i++)
        {
          score_data_1[i] = $('#score' + (i+1) + '_1').val('');
          break_data_1[i] = $('#brk' + (i+1) + '_1').val('');
          to_break_data_1[i] = $('#to_brk' + (i+1) + '_1').val('');

          score_data_2[i] = $('#score' + (i+1) + '_2').val('');
          break_data_2[i] = $('#brk' + (i+1) + '_2').val('');
          to_break_data_2[i] = $('#to_brk' + (i+1) + '_2').val('');
        }
        $('#scores_modal').modal('hide');
      },
    });
  });
*/
/*
  $('#save_time_button').click(function()
  {
    var tourn_id = $('#tourn_id').html();
    var new_day = $('#new_day').val();
    var new_time = $('#new_time').val();
    var player = $('#member_id').html();
    var element_id = $('#element_id').html();

    var subStr = '_';
    var first = (element_id.split(subStr, 1).join(subStr).length);
    var col = element_id.substring(0, first);
    var col_no = col.substring(1, col.length);
    var second = (element_id.split(subStr, 2).join(subStr).length);
    var element_no = parseInt(element_id.substring(second+1));
    var element_other = null;
    if(element_no % 2 == 0)
    {
      element_next = Math.round(element_no/2);
      element_other = ((element_no)-1);
    }
    else
    {
      element_next = Math.round(element_no/2);
      element_other = ((element_no)+1);
    }
    $.ajax({
      url:"save_time_data.php?tourn_id=" + tourn_id + "&player_id=" + player + "&new_day=" + new_day + "&new_time=" + new_time + "&round_no=" + col_no,
      method: 'GET',
      success:function(data)
      {
        var day_time = data.split(', ');
        $('#R' + col_no + '_day_' + element_no).val(day_time[0]);
        $('#R' + col_no + '_time_' + element_no).val(day_time[1]);
        $('#edit_time_modal').modal('hide');
      },
    });
  });

  var availableTags = <?php echo $player_data; ?>;
  $("#tags").autocomplete({
    source:  availableTags,
    appendTo: "#autocompleteAppendToMe"
  });

  $('#newplayer').click(function() {
      var id = $(this).attr('id');
      var tourn_type = '<?= $tourn_type ?>';
      var tourn_id = $('#tourn_id').html();
      var existing = $('#existing_player').val();
      var memb_id = $('#member_id').html();
      var element_id = $('#element_id').html();
      var new_player = $('#tags').val();

      var subStr = '_';
      var first = (element_id.split(subStr, 1).join(subStr).length);
      var col = element_id.substring(0, first);
      var col_no = col.substring(1, col.length);
      var second = (element_id.split(subStr, 2).join(subStr).length);
      var element_no = parseInt(element_id.substring(second+1));
      $.ajax({
      url:"update_player.php?tourn_id=" + tourn_id + "&player_id=" + memb_id + "&element_id=" + element_id + "&tourn_type=" + tourn_type + "&existing=" + existing + "&new_player=" + new_player,
      method: 'GET',
      success:function(data)
      {
        $('#R' + col_no + '_name_' + element_no).val(data);
        $('#change_players_modal').modal('hide');
      }
    });
  });
*/
});
</script>

<!-- Edit time Modal -->
<!--<div class="modal fade" id="edit_time_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Game Day/Time</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style='position: relative; z-index: 5000; overflow-y:visible;'>
        <br>
        <center>
        <table class='table table-striped table-bordered' style='width:250px;'>
          <tr>
            <td colspan='2' align='center'>Current Playing Day/Time</td>
          </tr>
          <tr>
            <div id="player_name"></div>
            <div hidden id="member_id"></div>
            <div hidden id="element_id"></div>
            <td align='center'><input type='text' id='existing_day' style='width:100px; height:20px'></td>
            <td align="center"><input type="text" class="timepicker" id="existing_time" style='width:100px; height:20px' /></td>
          </tr>
          <tr>
            <td colspan='2' align='center'>New Playing Day/Time</td>
          </tr>
          <tr>
            <?php
            $query_dates = 'Select * FROM tournaments left join calendar on calendar.tourn_id=tournaments.tourn_id where tournaments.tourn_id = ' . $tournament_id;
            $result_dates = mysql_query($query_dates, $connvbsa) or die(mysql_error());
            $build_dates = $result_dates->fetch_assoc();
            $start_date  = new DateTime($build_dates['startdate']);
            $finish_date  = new DateTime($build_dates['finishdate']);
            $interval = new DateInterval('P1D');
            $period = new DatePeriod($start_date, $interval, $finish_date->modify('+1 day'));
            ?>
            <td align="center"><select name="new_day" id="new_day" style='width:100px; height:20px'>
            <option value="">Select a Day</option>
            <?php
            foreach($period as $date)
            {
                echo("<option value='" . $date->format("l") . "'>" . $date->format("l") . "</option>");
            }
            ?>
            </select></td>
            <td align="center"><input type="text" class="timepicker" id="new_time" style='width:100px; height:20px' /></td>
          </tr>
        </table>
        <div><a class='btn btn-primary btn-xs' id='save_time_button'>Save Edit</a></div>
        </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>-->

<!-- Change Player Modal -->
<!--<div class="modal fade" id="change_players_modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ui-front">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select from the List of Players</h4>
            </div>
            <div class="modal-body">
              <div hidden id="member_id"></div>
              <div hidden id="element_id"></div>
              <center>
              <table style='width:500px;'>
                <tr>
                  <td colspan='2'>&nbsp;</td>
                </tr>
                <tr>
                  <td align='center'>Existing Players Name:</td>
                  <td align='center'><input id='existing_player' style='width:200px; height:25px' readonly></td>
                </tr>
                <tr>
                  <td colspan='2'>&nbsp;</td>
                </tr>
                <tr>
                  <td align='center'>New Players Name:</td>
                  <td align='center'><input id='tags' style='width:200px; height:25px'>
                  <br>
                  <div id='autocompleteAppendToMe'></div>
                  <br></td>
                </tr>
              </table>
              <br>
              <br>
              <div class='text-center ui-widget'><a class='btn btn-primary btn-xs' id='newplayer'>Change Existing Player</a>
              <br>
              <div></div>
              </div>
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>-->

<!-- Add scores Modal -->
<div class="modal fade" id="scores_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Player Score Entry</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <br>
        <center>
        <table class='table table-striped table-bordered'>
          <tr>
            <td align='center'>
        <table class='table table-striped table-bordered' style='width:250px;'>
          <tr>
            <td colspan='8' align='center'>Enter Scores/Breaks for <div id="playername_1"></div><div hidden id="member_id_1"></div><div hidden id="element_id"></div></td>
          </tr>
          <tr>
            <td align='center'>Frame 1</td>
            <td align='center'>Frame 2</td>
            <td align='center'>Frame 3</td>
            <td align='center'>Frame 4</td>
            <td align='center'>Frame 5</td>
            <td align='center'>Frame 6</td>
            <td align='center'>Frame 7</td>
            <td align='center'>Best Of 7</td>
          </tr>
          <tr>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td rowspan='5' align='center'><br><br><br>Frames:<br><input type='text' id='game_score_1' style='width:20px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'><input type='text' id='score1_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score2_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score3_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score4_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score5_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score6_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score7_1' style='width:40px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
          </tr>
          <tr>
            <td align='center'><input type='text' id='brk1_1' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk2_1' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk3_1' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk4_1' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk5_1' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk6_1' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk7_1' style='width:50px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>To Break <input type='checkbox' id='to_brk1_1'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk2_1'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk3_1'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk4_1'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk5_1'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk6_1'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk7_1'></td>
          </tr>
        </table>
        <table>
            <tr>
                <th align='center'><h3>Versus</h3></th>
            </tr>
            <tr>
                <th align='center'>&nbsp;</th>
            </tr>
        </table>
        <table class='table table-striped table-bordered' style='width:250px;'>
          <tr>
            <td colspan='8' align='center'>Enter Scores/Breaks for <div id="playername_2"></div><div hidden id="member_id_2"></div></td>
          </tr>
          <tr>
            <td align='center'>Frame 1</td>
            <td align='center'>Frame 2</td>
            <td align='center'>Frame 3</td>
            <td align='center'>Frame 4</td>
            <td align='center'>Frame 5</td>
            <td align='center'>Frame 6</td>
            <td align='center'>Frame 7</td>
            <td align='center'>Best Of 7</td>
          </tr>
          <tr>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td align='center'>Points</td>
            <td rowspan='5' align='center'><br><br><br>Frames:<br><input type='text' id='game_score_2' style='width:20px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'><input type='text' id='score1_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score2_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score3_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score4_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score5_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score6_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='score7_2' style='width:40px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
            <td align='center'>Breaks</td>
          </tr>
          <tr>
            <td align='center'><input type='text' id='brk1_2' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk2_2' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk3_2' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk4_2' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk5_2' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk6_2' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='brk7_2' style='width:50px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>To Break <input type='checkbox' id='to_brk1_2'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk2_2'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk3_2'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk4_2'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk5_2'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk6_2'></td>
            <td align='center'>To Break <input type='checkbox' id='to_brk7_2'></td>
          </tr>
        </table>
      </td>
    </tr>
    <!--<tr>
      <td colspan='6' align='center'><font color="red">Breaks should be seperated by a space.</font></td>
    </tr>
  </td>
  </table>
    <table class='table table-striped table-bordered' style='width:300px;'>
        <tr>
            <td colspan='8' align='center'><b>To be entered by the Referee.</b></td>
        </tr>
        <tr>
            <td colspan='3' align='center' style='width:100px; height:20px'>Referee</td>
            <td colspan='3' ><select name="referee">
            <?php
            // get list of referees
            $query_referees = 'Select Concat(FirstName, " ",  LastName) as fullname FROM vbsa3364_vbsa2.members Where referee = 1 order by LastName';
            $result_referees = mysql_query($query_referees, $connvbsa) or die(mysql_error());
            echo("<option value=''>&nbsp;</option>");
            while($build_referees = $result_referees->fetch_assoc())
            {
               echo("<option value='" . $build_referees['fullname'] . "'>" . $build_referees['fullname'] . "</option>");
            }
            ?>
            </select></td>
        </tr>
        <tr>
            <td colspan='3' align='center' style='width:100px; height:20px'>Marker</td>
            <td colspan='3' ><select name="marker">
            <?php
            // get list of markers
            $query_markers = 'Select Concat(FirstName, " ", LastName) as Fullname From members WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0';
            $result_markers = mysql_query($query_markers, $connvbsa) or die(mysql_error());
            echo("<option value=''>&nbsp;</option>");
            while($build_markers = $result_markers->fetch_assoc())
            {
               echo("<option value='" . $build_markers['Fullname'] . "'>" . $build_markers['Fullname'] . "</option>");
            }
            ?>
            </select></td>
        </tr>
        <tr>
            <td align='center'>Table</td>
            <td align='center'>Round</td>
            <td align='center'>Grade</td>
            <td align='center'>Start</td>
            <td align='center'>Finish</td>
            <td align='center'>Match No.</td>
        </tr>
        <tr>
            <td align='center'><input type='text' id='table_no' value=0 style='width:20px; height:20px'></td>
            <td align='center'><input type='text' id='round' value=0 style='width:20px; height:20px'></td>
            <td align='center'><input type='text' id='grade' value='' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='start' value='' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='finish' value='' style='width:50px; height:20px'></td>
            <td align='center'><input type='text' id='match_no' value=0 style='width:30px; height:20px'></td>
        </tr>-->
    </table>
            <!--<div><a class='btn btn-primary btn-xs' id='save_modal_button'>Save Scores</a></div>
            </center>
            </div>-->
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</form>
</body>
</html>
<script>
$(document).ready(function()
{
// change 480 to 760
  var total_players = <?= $total_players ?>;
  console.log("Total Players " + total_players);

  if(/iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent) || screen.availWidth < 480)
  {
    mobile = true;
    //alert("Total Player " + total_players);
    //$("#best_of_header").css({display: "block"});
    if(total_players == 128)
    {
        $("#header128").css({display: "block"});
        $("#content1").show();

        $("#header64").css({display: "block"});
        $("#content2").show();

        $("#header32").css({display: "none"});
        $("#content3").hide();

        $("#header16").css({display: "none"});
        $("#content4").hide();

        $("#header8").css({display: "none"});
        $("#content5").hide();

        $("#header4").css({display: "none"});
        $("#content6").hide();

        $("#header2").css({display: "none"});
        $("#content7").hide();

        $("#header1").css({display: "none"});
        $("#content8").hide();

        $("#backBtn").show();
        $("#nextBtn").show();
    }
    if(total_players == 64)
    {
        $("#header64").css({display: "block"});
        $("#content1").show();

        $("#header32").css({display: "block"});
        $("#content3").show();

        $("#header16").css({display: "none"});
        $("#content4").hide();

        $("#header8").css({display: "none"});
        $("#content5").hide();

        $("#header4").css({display: "none"});
        $("#content6").hide();

        $("#header2").css({display: "none"});
        $("#content7").hide();

        $("#header1").css({display: "none"});
        $("#content8").hide();

        $("#backBtn").show();
        $("#nextBtn").show();
    }
    if(total_players == 32)
    {
        $("#header32").css({display: "block"});
        $("#content1").show();

        $("#header16").css({display: "block"});
        $("#content4").show();

        $("#header8").css({display: "none"});
        $("#content5").hide();

        $("#header4").css({display: "none"});
        $("#content6").hide();

        $("#header2").css({display: "none"});
        $("#content7").hide();

        $("#header1").css({display: "none"});
        $("#content8").hide();

        $("#backBtn").show();
        $("#nextBtn").show();
    }
    if(total_players == 16)
    {
        $("#header16").css({display: "block"});
        $("#content1").show();

        $("#header8").css({display: "block"});
        $("#content5").show();

        $("#header4").css({display: "none"});
        $("#content6").hide();

        $("#header2").css({display: "none"});
        $("#content7").hide();

        $("#header1").css({display: "none"});
        $("#content8").hide();

        $("#backBtn").show();
        $("#nextBtn").show();
    }
    if(total_players == 8)
    {
        $("#header8").css({display: "block"});
        $("#content1").show();

        $("#header4").css({display: "block"});
        $("#content6").show();

        $("#header2").css({display: "none"});
        $("#content7").hide();

        $("#header1").css({display: "none"});
        $("#content8").hide();

        $("#backBtn").show();
        $("#nextBtn").show();
    }
  }
  else // all visible on desktop
  {
    mobile = false;
    $("#header32").css({display: "block"});
    $("#content1").show();

    $("#header16").css({display: "block"});
    $("#content4").show();

    $("#header8").css({display: "block"});
    $("#content5").show();

    $("#header4").css({display: "block"});
    $("#content6").show();

    $("#header2").css({display: "block"});
    $("#content7").show();

    $("#header1").css({display: "block"});
    $("#content8").show();

    $("#backBtn").hide();
    $("#nextBtn").hide();
  }

  total_matches = '<?= GetPlayerNumber($total_tourn) ?>';
  console.log("Number of matches in tournament " + total_matches);
  console.log("Number of players in tournament " + total_players);

  /*$("#backBtn").click(function()
  {
    alert("Back Button Pressed");
  });*/

  if(total_players == 128)
  {
    $("#nextBtn").click(function()
    {
      if(($("#content1").is(':visible')) && ($("#content2").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "block"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 1 and 2 are visible");
        $("#content1").hide();
        $("#content2").show();
        $("#content3").show();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content2").is(':visible')) && ($("#content3").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 2 and 3 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").show();
        $("#content4").show();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content3").is(':visible')) && ($("#content4").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 3 and 4 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").show();
        $("#content5").show();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content4").is(':visible')) && ($("#content5").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 4 and 5 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").show();
        $("#content6").show();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "none"});
    
        //alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").show();
        $("#content7").show();
        $("#content8").hide();
      }
      else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "block"});
    
        //alert("Content 6 and 7 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").show();
        $("#content8").show();
      }
    });
    $("#backBtn").click(function()
    {
      if(($("#content1").is(':visible')) && ($("#content2").is(':visible')))
      {
        $("#header128").css({display: "block"});
        $("#header64").css({display: "block"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 1 and 2 are visible");
        $("#content1").show();
        $("#content2").show();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content2").is(':visible')) && ($("#content3").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "block"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        //alert("Content 5 and 6 are visible");
        $("#content1").show();
        $("#content2").show();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content3").is(':visible')) && ($("#content4").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        //alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").show();
        $("#content3").show();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content4").is(':visible')) && ($("#content5").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        //alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").show();
        $("#content4").show();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        //alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").show();
        $("#content5").show();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        //alert("Content 6 and 7 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").show();
        $("#content6").show();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content7").is(':visible')) && ($("#content8").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "none"});

        //alert("Content 7 and 8 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").show();
        $("#content7").show();
        $("#content8").hide();
      }
    });
  }

  if(total_players == 64)
  {
    $("#nextBtn").click(function()
    {
        if(($("#content1").is(':visible')) && ($("#content3").is(':visible')))
        {
          $("#header64").css({display: "none"});
          $("#header32").css({display: "block"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          //alert("Content 1 and 4 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").show();
          $("#content4").show();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content2").is(':visible')) && ($("#content3").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          //alert("Content 4 and 5 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").show();
          $("#content5").show();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content3").is(':visible')) && ($("#content4").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          //alert("Content 4 and 5 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").show();
          $("#content6").show();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content4").is(':visible')) && ($("#content5").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          //alert("Content 4 and 5 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").show();
          $("#content6").show();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "block"});
          $("#header1").css({display: "none"});
      
          //alert("Content 5 and 6 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").show();
          $("#content7").show();
          $("#content8").hide();
        }
        else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
        {
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "block"});
          $("#header1").css({display: "block"});
      
          //alert("Content 6 and 7 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").show();
          $("#content8").show();
        }

    });
    $("#backBtn").click(function()
    {
      if(($("#content1").is(':visible') || $("#content3").is(':visible')) && ($("#content4").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "block"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 1 and 4 are visible");
        $("#content1").show();
        $("#content2").hide();
        $("#content3").show();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content3").is(':visible')) && ($("#content4").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "block"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        //alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").show();
        $("#content3").show();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content4").is(':visible')) && ($("#content5").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "block"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        //alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").show();
        $("#content4").show();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        //alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").show();
        $("#content5").show();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});

        //alert("Content 6 and 7 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").show();
        $("#content6").show();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content7").is(':visible')) && ($("#content8").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "none"});

        //alert("Content 7 and 8 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").show();
        $("#content7").show();
        $("#content8").hide();
      }
    });
  }

  if(total_players == 32)
  {
    $("#nextBtn").click(function()
    {
      if(($("#content1").is(':visible')) && ($("#content4").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "block"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 1 and 4 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").show();
        $("#content5").show();
        $("#content6").hide();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content4").is(':visible')) && ($("#content5").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 4 and 5 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").show();
        $("#content6").show();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "none"});
    
        //alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").show();
        $("#content7").show();
        $("#content8").hide();
      }
      else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "block"});
    
        //alert("Content 6 and 7 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").show();
        $("#content8").show();
      }
    });
    $("#backBtn").click(function()
    {
      // good for 32 players
        if(($("#content1").is(':visible') || $("#content4").is(':visible')) && ($("#content5").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "none"});
          $("#header32").css({display: "block"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          //alert("Content 1 and 4 are visible");
          $("#content1").show();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").show();
          $("#content5").hide();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});

          //alert("Content 5 and 6 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").show();
          $("#content5").show();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});

          //alert("Content 6 and 7 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").show();
          $("#content6").show();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content7").is(':visible')) && ($("#content8").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "block"});
          $("#header1").css({display: "none"});

          //alert("Content 7 and 8 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").show();
          $("#content7").show();
          $("#content8").hide();
        }
    });
  }

  if(total_players == 16)
  {
    $("#nextBtn").click(function()
    {
      if(($("#content1").is(':visible')) && ($("#content5").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 1 and 4 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").show();
        $("#content6").show();
        $("#content7").hide();
        $("#content8").hide();
      }
      else if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "none"});
    
        //alert("Content 5 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").show();
        $("#content7").show();
        $("#content8").hide();
      }
      else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "block"});
    
        //alert("Content 6 and 7 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").show();
        $("#content8").show();
      }
    });
    $("#backBtn").click(function()
    {
      //alert("Back Button");
        if(($("#content5").is(':visible')) && ($("#content6").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "block"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "none"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});
      
          //alert("Content 1 and 5 are visible");
          $("#content1").show();
          $("#content2").hide();
          $("#content3").hide(); 
          $("#content4").hide();
          $("#content5").show();
          $("#content6").hide();
          $("#content7").hide();
          $("#content8").hide();
        }
        
        else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});

          //alert("Content 6 and 7 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").show();
          $("#content6").show();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content7").is(':visible')) && ($("#content8").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "block"});
          $("#header1").css({display: "none"});

          //alert("Content 7 and 8 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").show();
          $("#content7").show();
          $("#content8").hide();
        }
    });
  }

  if(total_players == 8)
  {
    $("#nextBtn").click(function()
    {
      if(($("#content1").is(':visible')) && ($("#content6").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "block"});
        $("#header4").css({display: "block"});
        $("#header2").css({display: "none"});
        $("#header1").css({display: "none"});
    
        //alert("Content 1 and 6 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").show();
        $("#content7").show();
        $("#content8").hide();
      }
      else if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
      {
        $("#header128").css({display: "none"});
        $("#header64").css({display: "none"});
        $("#header32").css({display: "none"});
        $("#header16").css({display: "none"});
        $("#header8").css({display: "none"});
        $("#header4").css({display: "none"});
        $("#header2").css({display: "block"});
        $("#header1").css({display: "block"});
    
        //alert("Content 6 and 7 are visible");
        $("#content1").hide();
        $("#content2").hide();
        $("#content3").hide();
        $("#content4").hide();
        $("#content5").hide();
        $("#content6").hide();
        $("#content7").show();
        $("#content8").show();
      }
    });
    $("#backBtn").click(function()
    {
        if(($("#content6").is(':visible')) && ($("#content7").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "block"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "none"});
          $("#header1").css({display: "none"});

          //alert("Content 6 and 7 are visible");
          $("#content1").show();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").show();
          $("#content7").hide();
          $("#content8").hide();
        }
        else if(($("#content7").is(':visible')) && ($("#content8").is(':visible')))
        {
          $("#header128").css({display: "none"});
          $("#header64").css({display: "none"});
          $("#header32").css({display: "none"});
          $("#header16").css({display: "none"});
          $("#header8").css({display: "none"});
          $("#header4").css({display: "block"});
          $("#header2").css({display: "block"});
          $("#header1").css({display: "none"});

          //alert("Content 7 and 8 are visible");
          $("#content1").hide();
          $("#content2").hide();
          $("#content3").hide();
          $("#content4").hide();
          $("#content5").hide();
          $("#content6").show();
          $("#content7").show();
          $("#content8").hide();
        }
    });
  }

});
</script>
<script type="text/javascript">

window.jsPDF = window.jspdf.jsPDF;

function generatePDF() {
    //const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
      orientation: 'landscape'
    });
    var elementHTML = document.querySelector("#pdf_test");

    doc.html(elementHTML, {
        callback: function(doc) {
            // Save the PDF
            doc.save('Tournament_<?= $tournament_id ?>.pdf');
        },
        x: 15,
        y: 15,
        width: 170, //target width in the PDF document
        windowWidth: 650 //window width in CSS pixels
    });                
}            
</script>        

</body>
</html>