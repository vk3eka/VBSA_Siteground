<?php
require_once('../Connections/connvbsa.php'); 
include '../vbsa_online_scores/header_admin.php';
//include '../vbsa_online_scores/header_vbsa.php';

mysql_select_db($database_connvbsa, $connvbsa);

error_reporting(0);
/*
'202272', '8'
'202274', '15'
'202281', '24'
'202251', '48'
'202269', '98'
*/

?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script type='text/javascript'>

  function GetTournament(sel) {
    var tournament_id = sel.options[sel.selectedIndex].value;
    document.getElementById("tournament").value = tournament_id;
    document.tournament_draw.submit();
  }

</script>
<script type='text/javascript'>

$(document).ready(function()
{
  $('.timepicker').timepicker({
    timeFormat: 'HH:mm:ss',
    interval: 15,
    minTime: '08',
    dynamic: false,
    dropdown: true,
    scrollbar: true
  });
});
</script>
<link rel="stylesheet" type="text/css" href="tournament_draw.css">
<form name='tournament_draw' method="post" action='tournament_draw.php'>
<input type='hidden' name='TournamentID' id='TournamentID'>
<input type='hidden' name='TotalPlayers'>
<input type='hidden' name='ScoreData1'>
<input type='hidden' name='ButtonName'>
<div class="container">
<br>
<table align="center" cellpadding="5" cellspacing="5" width='50%'>
    <tr>
      <td align="left"><span class="red_bold"><h3>Tournament Draw Creation</h3></span></td>
      <td align="center">&nbsp;</td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
  </table>
<!--<div align='center'><h2>Tournament Draw Creation</h2></div>-->
<br>
<!--<div align='center'>Please select a tournament
  <select name="tournament" id="tournament" onchange="GetTournament(this)">
    <option value=""></option>
    <option value="202272">202272 (max 8 player)</option>
    <option value="202274">202274 (max 16 player)</option>
    <option value="202281">202281 (max 32 player)</option>
    <option value="202251">202251 (max 64 player)</option>
    <option value="202269">202269 (max 128 player)</option>
  </select>
</div>-->
<br>
<?php

// data array for change player dropdown
  $players = array();

  $sql = "Select MemberID, FirstName, LastName From members Order By LastName";
  $result_players = mysql_query($sql, $connvbsa) or die(mysql_error());
  $num_rows = $result_players->num_rows;
  if ($num_rows > 0) 
  {
    while($build_data = $result_players->fetch_assoc()) 
    {
      $firstname = $build_data['FirstName'];
      $lastname = $build_data['LastName'];
      $players[] = $build_data['FirstName'] . " " . $build_data['LastName']; 
    }
    $player_data = json_encode($players);
  }
  //$result_players->free_result();


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

$tourn_type = '';

if(isset($_GET['tourn_id']))
{
  $tournamentID = $_GET['tourn_id'];
  //echo($tournamentID . "<br>");
  //$tournamentID = $_POST['tournament'];
  $tourn_caption = "(Tournament ID " . $tournamentID . ")";

  // get tournament name
  $query_tourn_name = 'Select *, tournaments.tourn_type as type FROM vbsa3364_vbsa2.tournaments LEFT JOIN calendar ON tournaments.tourn_id = calendar.tourn_id where tournaments.tourn_id = ' . $tournamentID;
  //echo($query_tourn_name . "<br>");
  $result_tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
  $build_tourn_name = $result_tourn_name->fetch_assoc();
  $tourn_type = $build_tourn_name['type'];
  /*
  //default times
  $R128_time = $build_tourn_name['time_128'];
  $R64_time = $build_tourn_name['time_64'];
  $R32_time = $build_tourn_name['time_32'];
  $R16_time = $build_tourn_name['time_16'];
  $R8_time = $build_tourn_name['time_8'];
  $R4_time = $build_tourn_name['time_4'];
  $R2_time = $build_tourn_name['time_2'];
  */
  
  //echo($tourn_type . "<br>");

  /*

  //get number of tournament players from tourn entries table
  if($tourn_type == 'Billiards')
  {
    $query_tourn = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_Billiards on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournamentID . '  and LastName != ""';
  }
  else if($tourn_type == 'Snooker')
  {
    $query_tourn = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_S_open_tourn on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournamentID . '  and LastName != ""';
  }
  
  $result_tourn = mysql_query($query_tourn, $connvbsa) or die(mysql_error());
  //$build_tourn = $result_tourn->fetch_assoc();
  $total_tourn = $result_tourn->num_rows;
  //$tourn_type = $build_tourn['tourn_type'];
  //echo($query_tourn . "<br>");
  */
  //$max_players = GetPlayerNumber($total_tourn);
  //echo($max_players . "<br>");
  // check if players already saved in the scoring table
  $query_players = 'Select * FROM vbsa3364_vbsa2.tournament_scores where tourn_id = ' . $tournamentID;
  $result_players = mysql_query($query_players, $connvbsa) or die(mysql_error());
  //echo($query_players . "<br>");
  $total_players = $result_players->num_rows;
  //echo("Total Players " . $total_players . "<br>");
  
  $max_players = GetPlayerNumber($total_players);


  if($total_players == 0)
  {
    /*
    $i = 0;
    //while($build_players = $result_players->fetch_assoc())
    while($build_players = $result_tourn->fetch_assoc())
    {
      $sql_insert = "Insert into tournament_scores (
          tourn_id, 
          member_id, 
          r_" . $max_players . "_position, 
          ranknum)
          Values (" . 
          $tournamentID . ", " . 
          $build_players['MemberID'] . ", " . 
          ($i+1) . ", '" . 
          $build_players['ranknum'] . "')"; 
          //echo($sql_insert . "<br>");
          $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());;
          $i++;
    }
    for($x = $i; $x < $max_players; $x++) // for bye's
    {
      $sql_insert = "Insert into tournament_scores (
          tourn_id, 
          member_id, 
          r_" . $max_players . "_position, 
          ranknum)
          Values (" . 
          $tournamentID . ", 1, " . 
          ($x+1) . ", 0)"; 
          //echo($sql_insert . "<br>");
          $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());;
    }
    */
  }
  else
  {
    //echo("Get player scores from scores table<br>");
    // get scores/breaks from the scoring table

    $query_scores = 'Select * FROM vbsa3364_vbsa2.tournament_scores Left Join tournaments on tournaments.tourn_id = tournament_scores.tourn_id where tournament_scores.tourn_id = ' . $tournamentID;
    $result_scores = mysql_query($query_scores, $connvbsa) or die(mysql_error());
    $total_scores = $result_scores->num_rows;
    $total_players = $result_scores->num_rows;
    //echo($query_scores . "<br>");
    //echo($total_players . "<br>");
    echo("<script type='text/javascript'>");
    echo("function fillelementarray() {");

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
      
      //if(($total_players > 64) && ($total_players <= 128))
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
        echo("document.getElementById('R128_time_" . $R128_Pos . "').value = '" . $build_scores['time_128'] . "';");
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
      //if(($total_players > 32) && ($total_players <= 64))
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
        echo("document.getElementById('R64_time_" . $R64_Pos . "').value = '" . $build_scores['time_64'] . "';");
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
      //if(($total_players > 16) && ($total_players <= 32))
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
        echo("document.getElementById('R32_time_" . $R32_Pos . "').value = '" . $build_scores['r_32_time'] . "';");
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
      //if(($total_players > 8) && ($total_players <= 16))
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
        echo("document.getElementById('R16_time_" . $R16_Pos . "').value = '" . $build_scores['time_16'] . "';");
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
        echo("document.getElementById('R8_time_" . $R8_Pos . "').value = '" . $build_scores['r_8_time'] . "';");
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
        echo("document.getElementById('R4_time_" . $R4_Pos . "').value = '" . $build_scores['time_4'] . "';");
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
        echo("document.getElementById('R2_time_" . $R2_Pos . "').value = '" . $build_scores['time_2'] . "';");
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
  }
  echo("<hr>");
  echo("<div align='center'><h3>" . $build_tourn_name['tourn_name'] . "</h3></div>");
  echo("<div align='center'>" . $tourn_caption . "</div>");
  echo("<div hidden align='center' id='tourn_id'>" . $tournamentID . "</div>");
  echo("<br>");
  echo("<div align='center'>Start Date " . $build_tourn_name['startdate'] . " - Finish Date " . $build_tourn_name['finishdate'] . "</div>");
  echo("<br>");
  echo("<hr>");

 //echo("Total Tourn " . $total_scores . "<br>");
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
  //echo("Total Players " . $total_players . "<br>");
  
  echo("<div class='tournament-headers' align='center' style='width:" . ($total_rounds*$div_width) . "px'>");
  if($total_players > 64)
  {
    echo("<h4>Round of 128<br>Best of " . $build_tourn_name['best_of_128'] . "</h4>");
  }
  if($total_players > 32)
  {
    echo("<h4>Round of 64<br>Best of " . $build_tourn_name['best_of_64'] . "</h4>");
  }
  if($total_players > 16)
  {
    echo("<h4>Round of 32<br>Best of " . $build_tourn_name['best_of_32'] . "</h4>");
  }
  if($total_players > 8)
  {
    echo("<h4>Round of 16<br>Best of " . $build_tourn_name['best_of_16'] . "</h4>");
  }
  if($total_players > 4)
  {
    echo("<h4>Quarter Finals<br>Best of " . $build_tourn_name['best_of_8'] . "</h4>");
  }
  if($total_players > 2)
  {
    echo("<h4>Semi Finals<br>Best of " . $build_tourn_name['best_of_4'] . "</h4>");
  }
  if($total_players > 1)
  {
    echo("<h4>Grand Final<br>Best of " . $build_tourn_name['best_of_2'] . "</h4>");
    echo("<h4>Winner</h4>");
  }
  echo("</div>");

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
  $query_tourn_players_count = 'Select * FROM tournament_scores where tourn_id = ' . $tournamentID ;
  $result_tourn_players_count = mysql_query($query_tourn_players_count, $connvbsa) or die(mysql_error());
  //echo("Rows " . $result_tourn_players_count->num_rows . "<br>");
  if($result_tourn_players_count->num_rows == 0)
  {
    if($tourn_type == 'Billiards')
    {
       //get tournament players from tourn entries table
      $query_tourn_players = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_Billiards on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournamentID . '  and LastName != "" Order by ranknum DESC';

    }
    else if($tourn_type == 'Snooker')
    {
       //get tournament players from tourn entries table
      $query_tourn_players = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN rank_S_open_tourn on memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournamentID . '  and LastName != "" Order by total_tourn_rp DESC';
    }
  }
  else
  {
    $query_tourn_players = 'Select * FROM tournament_scores LEFT JOIN members on member_id=MemberID LEFT JOIN tournaments on tournament_scores.tourn_id=tournaments.tourn_id where tournament_scores.tourn_id = ' . $tournamentID;

    //$query_tourn_players = 'Select * FROM tournament_scores LEFT JOIN members on member_id=MemberID LEFT JOIN tournaments on tournament_scores.tourn_id=tournaments.tourn_id where tournament_scores.tourn_id = ' . $tournamentID . ' and LastName != "" Order by ranknum = 0 ASC, ranknum ASC';

   }
  //echo("SQL " . $query_tourn_players . "<br>");
  $result_tourn_players = mysql_query($query_tourn_players, $connvbsa) or die(mysql_error());

  //echo($total_tourn . "<br>");
  //echo($total_players . "<br>");

  $x = 0; 
  $bye_index = 10000; 
  $default_user_image = '../images/change_player_1.png';
  echo("<div class='bracket bracket-1'>");
  
  while($build_tourn = $result_tourn_players->fetch_assoc())
  {
    //echo("Index " . ($x+1) . "<br>");
    //if($build_tourn['r_' . $total_players . '_position'] > 0)
    //{
    //  echo("Position " . $build_tourn['r_' . $total_players . '_position'] . "<br>");
    //}
    if($build_tourn['member_id'] >= 10000)
    {
      $fullname = 'Bye';
    }
    else
    {
      $fullname = $build_tourn['FirstName'] . " " . $build_tourn['LastName'];
    }
    
    echo("<div class='team-item' style='width:200px'><input type='text' id='R" . $total_players . "_seed_" . ($x+1) . "' value='" . $build_tourn['ranknum'] . "' style='width:25px'><input type='text' value='" . $fullname . "' id='R" . $total_players . "_name_" . ($x+1) . "' style='width:110px'><input type='text' id='R" . $total_players . "_score_" . ($x+1) . "' style='width:15px' readonly value=''><img src='$default_user_image' id='R" . $total_players . "_change_" . ($x+1) . "' style='width:32px; height:32x'>");
    echo("<input type='hidden' value=" . $build_tourn['member_id'] . " id='R" . $total_players . "_id_" . ($x+1) . "' style='width:20px'><br><time><input type='text' id='R" . $total_players . "_time_" . ($x+1) . "' value='' style='width:50px'></time></div>");
    $x++;
    //}
  }
  /*for($i = ($total_tourn); $i < $total_players; $i++) // name = 'Bye'
  {
    echo("<div class='team-item' style='width:200px'><input type='text' id='R" . $total_players . "_seed_" . ($x+1) . "' value='0' style='width:35px'><input type='text' value='Bye' id='R" . $total_players . "_name_" . ($x+1) . "'  style='width:120px'><input type='text' id='R" . $total_players . "_score_" . ($x+1) . "' style='width:20px' readonly value=''>");
    echo("<input type='hidden' value=" . ($build_tourn['MemberID']+$bye_index) . " id='R" . $total_players . "_id_" . ($x+1) . "' style='width:50px'><br><time><input type='text' id='R" . $total_players . "_time_" . ($x+1) . "' value='' style='width:30px'></time></div>");
    $x++;
    $bye_index++;
  }*/
  echo("</div>");
  
  if($total_players > 64)
  {
    echo("<div class='bracket bracket-2'>");
    for($i = 0; $i < 64; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R64_name_" . ($i+1) . "'  value='' style='width:120px'><input type='text' id='R64_score_" . ($i+1) . "' value='' style='width:20px'>
        <img src='$default_user_image' id='R64_change_" . ($i+1) . "' style='width:32px; height:32x'>");
      echo("<input type='hidden' value='' id='R64_id_" . ($i+1) . "' style='width:20px'><br><time><input type='text' id='R64_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 32)
  {
    echo("<div class='bracket bracket-2'>");
    for($i = 0; $i < 32; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R32_name_" . ($i+1) . "' value='' style='width:120px'><input type='text' id='R32_score_" . ($i+1) . "' value='' style='width:20px'><img src='$default_user_image' id='R32_change_" . ($i+1) . "' style='width:32px; height:32x'>");
      echo("<input type='hidden' value='' id='R32_id_" . ($i+1) . "' style='width:20px'><br><time><input type='text' id='R32_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 16)
  {
    echo("<div class='bracket bracket-2'>");
    for($i = 0; $i < 16; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R16_name_" . ($i+1) . "' style='width:120px' value=''><input type='text' id='R16_score_" . ($i+1) . "'  value='' style='width:20px'><img src='$default_user_image' id='R16_change_" . ($i+1) . "' style='width:32px; height:32x'>");
      echo("<input type='hidden' value='' id='R16_id_" . ($i+1) . "' style='width:20px'><br><time><input type='text' class='time' id='R16_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 8)
  {
    echo("<div class='bracket bracket-3'>");
    for($i = 0; $i < 8; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R8_name_" . ($i+1) . "' style='width:120px' value=''><input type='text' id='R8_score_" . ($i+1) . "' value='' style='width:20px'><img src='$default_user_image' id='R8_change_" . ($i+1) . "' style='width:32px; height:32x'><input type='hidden' value='' id='R8_id_" . ($i+1) . "' style='width:20px'><br><time><input type='text' id='R8_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 4)
  {
    echo("<div class='bracket bracket-4'>");
    for($i = 0; $i < 4; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R4_name_" . ($i+1) . "' style='width:120px' value=''><input type='text' id='R4_score_" . ($i+1) . "' value='' style='width:20px'><img src='$default_user_image' id='R4_change_" . ($i+1) . "' style='width:32px; height:32x'><input type='hidden' value='' id='R4_id_" . ($i+1) . "' style='width:20px'><br><time><input type='text' id='R4_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 2)
  {
    echo("<div class='bracket bracket-5'>");
    for($i = 0; $i < 2; $i++)
    {
      echo("<div class='team-item' style='width:200px'><input type='text' id='R2_name_" . ($i+1) . "' style='width:120px' value=''><input type='text' id='R2_score_" . ($i+1) . "' value='' style='width:20px'><img src='$default_user_image' id='R2_change_" . ($i+1) . "' style='width:32px; height:32x'><input type='hidden' value='' id='R2_id_" . ($i+1) . "' style='width:20px'><br><time><input type='text' id='R2_time_" . ($i+1) . "' value='' style='width:50px'></time></div>");
    }
    echo("</div>");
  }

  if($total_players > 1)
  {
    echo("<div class='bracket bracket-6'>");
    echo("<div class='team-item' style='width:200px'><input type='text' id='R1_name_1' style='width:120px' value=''><input type='hidden' value='' id='R1_id_1' style='width:20px'></div>");
    echo("</div>");
  }

}
?>
</div>
</form>
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
      url:"get_score_data.php?total_players=" + round + "&member_1=" + member_1 + "&member_2=" + member_2,
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
    $('#R128_time_' + (i+1)).click(function() {
      $('#edit_time_modal').modal('show');
      var id = $(this).attr('id');
      $('#member_id').html($.fn.get_id_element(id));
      $('#existing_time').val($('#' + id).val());
      $('#element_id').html(id);
    });

    /*
    $(document).on("contextmenu", '#R128_name_' + (i+1), function(e) {
      // right click
      var id = $(this).attr('id');
      $('#existing_player').val($('#' + id).val());
      $('#member_id').html($.fn.get_id_element(id));
      $('#element_id').html(id);

      $('#change_players_modal').modal('show');
      return false;
    });
    */

    $('#R128_change_' + (i+1)).click(function() {
      var id = $(this).attr('id');
      //alert(id);
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
    $('#R64_time_' + (i+1)).click(function() {
      $('#edit_time_modal').modal('show');
      var id = $(this).attr('id');
      $('#member_id').html($.fn.get_id_element(id));
      $('#existing_time').val($('#' + id).val());
      $('#element_id').html(id);
    });

    $('#R64_change_' + (i+1)).click(function() {
      var id = $(this).attr('id');
      //alert(id);
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
    $('#R32_time_' + (i+1)).click(function() {
      $('#edit_time_modal').modal('show');
      var id = $(this).attr('id');
      $('#member_id').html($.fn.get_id_element(id));
      $('#existing_time').val($('#' + id).val());
      $('#element_id').html(id);
    });

    $('#R32_change_' + (i+1)).click(function() {
      var id = $(this).attr('id');
      //alert(id);
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
    $('#R16_time_' + (i+1)).click(function() {
      $('#edit_time_modal').modal('show');
      var id = $(this).attr('id');
      $('#member_id').html($.fn.get_id_element(id));
      $('#existing_time').val($('#' + id).val());
      $('#element_id').html(id);
    });

    $('#R16_change_' + (i+1)).click(function() {
      var id = $(this).attr('id');
      //alert(id);
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
    $('#R8_time_' + (i+1)).click(function() {
      $('#edit_time_modal').modal('show');
      var id = $(this).attr('id');
      $('#member_id').html($.fn.get_id_element(id));
      $('#existing_time').val($('#' + id).val());
      $('#element_id').html(id);
    });

    $('#R8_change_' + (i+1)).click(function() {
      var id = $(this).attr('id');
      //alert(id);
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
    $('#R4_time_' + (i+1)).click(function() {
      $('#edit_time_modal').modal('show');
      var id = $(this).attr('id');
      $('#member_id').html($.fn.get_id_element(id));
      $('#existing_time').val($('#' + id).val());
      $('#element_id').html(id);
    });

    $('#R4_change_' + (i+1)).click(function() {
      var id = $(this).attr('id');
      //alert(id);
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
    $('#R2_time_' + (i+1)).click(function() {
      $('#edit_time_modal').modal('show');
      var id = $(this).attr('id');
      $('#member_id').html($.fn.get_id_element(id));
      $('#existing_time').val($('#' + id).val());
      $('#element_id').html(id);
    });

    $('#R4_change_' + (i+1)).click(function() {
      var id = $(this).attr('id');
      //alert(id);
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

  $('#save_modal_button').click(function()
  {
    var score_data_1 = [];
    var break_data_1 = [];
    var score_data_2 = [];
    var break_data_2 = [];
    var scoredata_player_1 = new Array;
    var scoredata_player_2 = new Array;
    var game_score_1 = 0;
    var game_score_2 = 0;
    var tourn_id = $('#tourn_id').html();
    var member_id_1 = $('#member_id_1').html();
    var member_id_2 = $('#member_id_2').html();
    var element_id = $('#element_id').html();
    for(i = 0; i < 7; i++) // max number of 'Best Of'
    {
      score_data_1[i] = $('#score' + (i+1) + '_1').val();
      break_data_1[i] = $('#brk' + (i+1) + '_1').val();
      score_data_2[i] = $('#score' + (i+1) + '_2').val();
      break_data_2[i] = $('#brk' + (i+1) + '_2').val();
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
    //console.log("No 3 " + $.fn.get_draw(element_id));
    scoredata_player_1 = 
          col_no + ", " + 
          member_id_1 + ", " + 
          game_score_1 + ", " + 
          score_data_1 + ", " + 
          break_data_1 + ", " + 
          parseInt(element_no);

    scoredata_player_2 = 
          col_no + ", " + 
          member_id_2 + ", " + 
          game_score_2 + ", " + 
          score_data_2 + ", " + 
          break_data_2 + ", " + 
          parseInt(element_other);;

    scoredata_player_3 = $.fn.get_draw(element_id);


    scoredata_player_1 = JSON.stringify(scoredata_player_1);
    scoredata_player_2 = JSON.stringify(scoredata_player_2);
    scoredata_player_3 = JSON.stringify(scoredata_player_3);

    console.log('Player 1 ' + scoredata_player_1);
    console.log('Player 2 ' + scoredata_player_2);
    console.log('Player 3 ' + scoredata_player_3);

    $.ajax({
      url:"save_score_data.php?tourn_id=" + tourn_id + "&score_data_1=" + scoredata_player_1 + "&score_data_2=" + scoredata_player_2 + "&score_data_3=" + scoredata_player_3,
      method: 'GET',
      success:function(response)
      {
        for(i = 0; i < 7; i++)
        {
          score_data_1[i] = $('#score' + (i+1) + '_1').val('');
          break_data_1[i] = $('#brk' + (i+1) + '_1').val('');
          score_data_2[i] = $('#score' + (i+1) + '_2').val('');
          break_data_2[i] = $('#brk' + (i+1) + '_2').val('');
        }
        $('#scores_modal').modal('hide');
      },
    });
  });


  $('#save_time_button').click(function()
  {
    var tourn_id = $('#tourn_id').html();
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
      url:"save_time_data.php?tourn_id=" + tourn_id + "&player_id=" + player + "&new_time=" + new_time
       + "&round_no=" + col_no,
      method: 'GET',
      success:function(data)
      {
        $('#R' + col_no + '_time_' + element_no).val(data);
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
      //alert(id);
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


      alert(tourn_id + ", " + memb_id + ", " + element_id + ", " + new_player + ", " + existing);
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

});
</script>

<!-- Change Player Modal -->
<div class="modal fade" id="change_players_modal" role="dialog">
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
              <!--<div>If player is listed in the drop down, select them. If not, you will be requested to provide player details.</div>-->
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
</div>

<!-- Edit time Modal -->
<div class="modal fade" id="edit_time_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Game Time</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style='position: relative; z-index: 5000; overflow-y:visible;'>
        <br>
        <center>
        <table class='table table-striped table-bordered' style='width:250px;'>
          <tr>
            <td align='center'>Current Match Time</td>
            <td align='center'>New Match Time</td>
          </tr>
          <tr>
            <div id="player_name"></div>
            <div hidden id="member_id"></div>
            <div hidden id="element_id"></div>
            <td align='center'><input type='text' id='existing_time' style='width:100px; height:20px'></td>
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
</div>

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
            <td colspan='3' align='center'>Enter Scores/Breaks for <div id="playername_1"></div><div hidden id="member_id_1"></div><div hidden id="element_id"></div></td>
          </tr>
          <tr>
            <td colspan='2' align='center'>Game Score</td>
            <td align='center'>Breaks</td>
          </tr>
          <tr>
            <td align='center'>1</td>
            <td align='center'><input type='text' id='score1_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk1_1' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>2</td>
            <td align='center'><input type='text' id='score2_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk2_1' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>3</td>
            <td align='center'><input type='text' id='score3_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk3_1' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>4</td>
            <td align='center'><input type='text' id='score4_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk4_1' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>5</td>
            <td align='center'><input type='text' id='score5_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk5_1' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>6</td>
            <td align='center'><input type='text' id='score6_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk6_1' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>7</td>
            <td align='center'><input type='text' id='score7_1' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk7_1' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td colspan='2' align='center'>Game Score</td>
            <td align='center'><input type='text' id='game_score_1' style='width:40px; height:20px'></td>
          </tr>
        </table>
      </td>
      <td align='center'>
        <table class='table table-striped table-bordered'  style='width:250px;'>
          <tr>
            <td colspan='3' align='center'>Enter Scores/Breaks for <div id="playername_2"></div><div hidden id="member_id_2"></div></td>
          </tr>
          <tr>
            <td colspan='2'  align='center'>Game Score</td>
            <td align='center'>Breaks</td>
          </tr>
          <tr>
            <td align='center'>1</td>
            <td align='center'><input type='text' id='score1_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk1_2' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>2</td>
            <td align='center'><input type='text' id='score2_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk2_2' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>3</td>
            <td align='center'><input type='text' id='score3_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk3_2' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>4</td>
            <td align='center'><input type='text' id='score4_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk4_2' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>5</td>
            <td align='center'><input type='text' id='score5_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk5_2' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>6</td>
            <td align='center'><input type='text' id='score6_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk6_2' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td align='center'>7</td>
            <td align='center'><input type='text' id='score7_2' style='width:40px; height:20px'></td>
            <td align='center'><input type='text' id='brk7_2' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td colspan='2' align='center'>Match Score</td>
            <td align='center'><input type='text' id='game_score_2' style='width:40px; height:20px'></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan='6' align='center'><font color="red">A Maximum of 8 breaks can be entered and should be seperated by a space.</font></td>
    </tr>
  </td>
  </table>
      <div><a class='btn btn-primary btn-xs' id='save_modal_button'>Save Scores</a></div>
        </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>