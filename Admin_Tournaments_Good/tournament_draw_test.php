<?php
require_once('../Connections/connvbsa.php'); 
include '../vbsa_online_scores/header_admin.php';
//include '../vbsa_online_scores/header_vbsa.php';


mysql_select_db($database_connvbsa, $connvbsa);

error_reporting(0);
/*
'202272', '6'
'202274', '15'
'202281', '24'
'202251', '48'
'202269', '98'
*/

/*
if(isset($_POST['ButtonName']) && ($_POST['ButtonName'] == 'SaveDraw'))
{
  $total_players_score = $_POST['TotalPlayers'];
  $packedscoredata_1 = json_decode(stripslashes($_POST['ScoreData1']), true);
  //echo("<pre>");
  //echo(var_dump($packedscoredata_1));
  //echo("</pre>");
  for($i = 0; $i < sizeof($packedscoredata_1); $i++)
  {
    $score = explode(",", $packedscoredata_1[$i]);
  
    $sql_players = "Insert into tournament_scores (
    tourn_id, 
    member_id, 
    position, 
    seed, 
    r_" . $total_players_score . "_score_1, 
    r_" . $total_players_score . "_time
    )
    VALUES (" . $score[0] . ", $score[1], " . $i . ", 125, '" . trim($score[2]) . "', '20:00')";  
    echo($sql_players . "<br>");
    $update = mysql_query($sql_players, $connvbsa) or die(mysql_error());
  }

  echo "<script type='text/javascript'>";
  echo "alert('Score Data Updated')";
  //echo "alert('Not Yet Implemented')";
  echo "</script>";
  header("Location:'tournament_draw.php'");
}
*/
?>
<script type='text/javascript'>

  function GetTournament(sel) {
    var tournament_id = sel.options[sel.selectedIndex].value;
    document.getElementById("tournament").value = tournament_id;
    document.tournament_draw.submit();
  }

/*
  function SaveAll(total_players, tourn_id) {
    var max_players = total_players; // max number of players
    var scoredata_player_1 = [];
    for(i = 0; i < total_players; i++)
    {
      console.log(tourn_id);
      if((document.getElementById("R" + total_players + "_name_" + i).value) != 'Bye')
      {
        scoredata_player_1[i] = tourn_id + ", " + document.getElementById("R" + total_players + "_id_" + i).value + ", " + document.getElementById("R" + total_players + "_name_" + i).value + ", " + document.getElementById("R" + total_players + "_score_" + i).value;
      }
    }
    scoredata_player_1 = JSON.stringify(scoredata_player_1);
    document.tournament_draw.TournamentID.value = tourn_id;
    document.tournament_draw.TotalPlayers.value = max_players;
    document.tournament_draw.ScoreData1.value = scoredata_player_1;
    document.tournament_draw.ButtonName.value = 'SaveDraw';
    document.tournament_draw.submit();
  }
*/
</script>

<link rel="stylesheet" type="text/css" href="tournament_draw.css">
<form name='tournament_draw' method="post" action='tournament_draw.php'>
<input type='hidden' name='TournamentID' id='TournamentID'>
<input type='hidden' name='TotalPlayers'>
<input type='hidden' name='ScoreData1'>
<input type='hidden' name='ButtonName'>
<div class="container">
<br>
<div align='center'><h2>Tournament Draw Creation</h2></div>
<br>
<div align='center'>Please select a tournament
  <select name="tournament" id="tournament" onchange="GetTournament(this)">
    <option value=""></option>
    <option value="202272">202272 (max 8 player)</option>
    <option value="202274">202274 (max 16 player)</option>
    <option value="202281">202281 (max 32 player)</option>
    <option value="202251">202251 (max 64 player)</option>
    <option value="202269">202269 (max 128 player)</option>
  </select>
</div>
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

if(isset($_POST['tournament']))
{
  $tournamentID = $_POST['tournament'];
  $tourn_caption = "(Tournament ID " . $tournamentID . ")";

  // get tournament name
  $query_tourn_name = 'Select * FROM vbsa3364_vbsa2.tournaments LEFT JOIN calendar ON tournaments.tourn_id =  calendar.tourn_id where tournaments.tourn_id = ' . $tournamentID;
  $result_tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
  $build_tourn_name = $result_tourn_name->fetch_assoc();

  //get tournament players from tourn entries table
  $query_tourn = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournamentID . '  and LastName != ""';
  $result_tourn = mysql_query($query_tourn, $connvbsa) or die(mysql_error());
  $total_tourn = $result_tourn->num_rows;

  $max_players = GetPlayerNumber($total_tourn);
  //echo($max_players . "<br>");
  // check if players already saved in the scoring table
  $query_players = 'Select * FROM vbsa3364_vbsa2.tournament_scores where tourn_id = ' . $tournamentID;
  $result_players = mysql_query($query_players, $connvbsa) or die(mysql_error());
  $total_players = $result_players->num_rows;
  //echo($total_players . "<br>");
  $i = 0;
  if($total_players == 0)
  {
    while($build_players = $result_tourn->fetch_assoc())
    {
      $sql_insert = "Insert into tournament_scores (
          tourn_id, 
          member_id, 
          r_" . $max_players . "_position, 
          seed)
          Values (" . 
          $tournamentID . ", " . 
          $build_players['MemberID'] . ", " . 
          ($i+1) . ", " . 
          $build_players['rank_pts'] . ")"; 
          echo($sql_insert . "<br>");
          $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());;
          $i++;
    }
    for($x = $i; $x < $max_players; $x++)
    {
      $sql_insert = "Insert into tournament_scores (
          tourn_id, 
          member_id, 
          r_" . $max_players . "_position, 
          seed)
          Values (" . 
          $tournamentID . ", 1, " . 
          ($x+1) . ", 0)"; 
          echo($sql_insert . "<br>");
          $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());;
    }
  }
  else
  {
    //echo("Get player scores from scores table<br>");
    // get scores/breaks from the scoring table

    echo("<script type='text/javascript'>");
    echo("function fillelementarray() {");

      $query_scores = 'Select * FROM vbsa3364_vbsa2.tournament_scores where tourn_id = ' . $tournamentID;
      $result_scores = mysql_query($query_scores, $connvbsa) or die(mysql_error());
      $total_scores = $result_scores->num_rows;
      $i = 0;
      while($build_scores = $result_scores->fetch_assoc())
      {
        echo("document.getElementById('R8_score_" . $i . "').value = " . $build_scores['r_8_game_1'] . ";");
        //echo("document.getElementById('R4_score_" . $i . "').value = " . $build_scores['r_4_game_1'] . ";");
        /*if($build_scores['member_id'] == 6277)
        {
            echo("document.getElementById('game_score_1').value = " . $build_scores['r_8_game_1'] . ";");
        }*/
        $i++;
      }

    echo("}");
    echo("window.onload = function()"); 
    echo("{");
    echo("fillelementarray()");
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
  //echo("<div align='center' class='greenbg' ><input type='button' value='Save All Results' onclick='SaveAll(" . $total_players . ", " . $tournamentID . ")'/>");
  //echo("</div>");
  echo("<hr>");

  //$tournamentID = $_POST['tournament'];
  //$tourn_caption = "(Tournament ID " . $tournamentID . ")";

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
  $query_tourn_players = 'Select * FROM tournament_scores LEFT JOIN members on member_id=MemberID LEFT JOIN tournaments on tournament_scores.tourn_id=tournaments.tourn_id where tournament_scores.tourn_id = ' . $tournamentID . ' and LastName != "" ';
  $result_tourn_players = mysql_query($query_tourn_players, $connvbsa) or die(mysql_error());
  if($result_tourn_players->num_rows == 0)
  {
    $query_tourn_players = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tournamentID . '  and LastName != "" ';
  }
  $result_tourn_players = mysql_query($query_tourn_players, $connvbsa) or die(mysql_error());

//echo($total_players . "<br>");
  $x = 0;  
  echo("<div class='bracket bracket-1'>");
  while($build_tourn = $result_tourn_players->fetch_assoc())
  {
    //echo("<div class='team-item' style='width:200px'><input type='text' value='" . $x . "' style='width:25px'><input type='text' value='" . $build_tourn['FirstName'] . " " . $build_tourn['LastName'] . "' id='R" . $total_players . "_name_" . $x . "' style='width:120px'><input type='text' id='R" . $total_players . "_score_" . $x . "' value=0 style='width:20px'><br><time id='R" . $total_players . "_time_" . $x . "'>20:00</time></div>");
    echo("<div class='team-item' style='width:200px'><input type='text' value='" . $build_tourn['FirstName'] . " " . $build_tourn['LastName'] . "' id='R" . $total_players . "_name_" . $x . "' style='width:120px'><input type='text' id='R" . $total_players . "_score_" . $x . "' style='width:20px' readonly value=''></div>");
    echo("<input type='hidden' value=" . $build_tourn['MemberID'] . " name='R" . $total_players . "_id_" . $x . "' id='R" . $total_players . "_id_" . $x . "'>");
    $x++;
  }
  for($i = ($total_tourn); $i < $total_players; $i++) // name = 'Bye'
  {
    //echo("<div class='team-item' style='width:200px'><input type='text' value='" . $x . "' style='width:25px'>&nbsp;<input type='text' value='Bye' id='R" . $total_players . "_name_" . $x . "'  style='width:120px'><input type='text' value=0 id='R" . $total_players . "_score_" . $x . "' style='width:20px'><br><time id=R" . $total_players . "_time_" . $x . "></time></div>");
    echo("<div class='team-item' style='width:200px'><input type='text' value='Bye' id='R" . $total_players . "_name_" . $x . "'  style='width:120px'><input type='text' id='R" . $total_players . "_score_" . $x . "' style='width:20px' readonly></div>");
    $x++;
  }
  echo("</div>");

  if($total_players > 64)
  {
    echo("<div class='bracket bracket-2'>");
    for($i = 0; $i < 64; $i++)
    {
      //echo("<div class='team-item' style='width:200px'><input type='text' id='R64_name_" . $i . "'  value='' style='width:120px'><input type='text' id='R64_score_" . $i . "' value='' style='width:20px'><br><time id='R64_time_" . $i . "'>21:00</time></div>");
      echo("<div class='team-item' style='width:200px'><input type='text' id='R64_name_" . $i . "'  value='' style='width:120px'><input type='text' id='R64_score_" . $i . "' value='' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 32)
  {
    echo("<div class='bracket bracket-2'>");
    for($i = 0; $i < 32; $i++)
    {
      //echo("<div class='team-item' style='width:200px'><input type='text' id='R32_name_" . $i . "' value='' style='width:120px'><input type='text' id='R32_score_" . $i . "' value='' style='width:20px'><br><time id='R32_time_" . $i . "'>21:00</time></div>");
      echo("<div class='team-item' style='width:200px'><input type='text' id='R32_name_" . $i . "' value='' style='width:120px'><input type='text' id='R32_score_" . $i . "' value='' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 16)
  {
    echo("<div class='bracket bracket-2'>");
    for($i = 0; $i < 16; $i++)
    {
      //echo("<div class='team-item' style='width:200px'><input type='text' id='R16_name_" . $i . "' style='width:120px' value=''><input type='text' id='R16_score_" . $i . "'  value='' style='width:20px'><br><time id='R16_time_" . $i . "'>21:00</time></div>");
      echo("<div class='team-item' style='width:200px'><input type='text' id='R16_name_" . $i . "' style='width:120px' value=''><input type='text' id='R16_score_" . $i . "'  value='' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 8)
  {
    echo("<div class='bracket bracket-3'>");
    for($i = 0; $i < 8; $i++)
    {
      //echo("<div class='team-item' style='width:200px'><input type='text' id='R8_name_" . $i . "' style='width:120px' value=''><input type='text' id='R8_score_" . $i . "' value='' style='width:20px'><br><time id='R8_time_" . $i . "'>21:00</time></div>");
      echo("<div class='team-item' style='width:200px'><input type='text' id='R8_name_" . $i . "' style='width:120px' value=''><input type='text' id='R8_score_" . $i . "' value='' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 4)
  {
    echo("<div class='bracket bracket-4'>");
    for($i = 0; $i < 4; $i++)
    {
      //echo("<div class='team-item' style='width:200px'><input type='text' id='R4_name_" . $i . "' style='width:120px' value=''><input type='text' id='R4_score_" . $i . "' value='' style='width:20px'><br><time id='R4_time_" . $i . "'>21:00</time></div>");
      echo("<div class='team-item' style='width:200px'><input type='text' id='R4_name_" . $i . "' style='width:120px' value=''><input type='text' id='R4_score_" . $i . "' value='' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 2)
  {
    echo("<div class='bracket bracket-5'>");
    for($i = 0; $i < 2; $i++)
    {
      //echo("<div class='team-item' style='width:200px'><input type='text' id='R2_name_" . $i . "' style='width:120px' value=''><input type='text' id='R2_score_" . $i . "' value='' style='width:20px'><br><time id='R2_time_" . $i . "'>22:00</time></div>");
      echo("<div class='team-item' style='width:200px'><input type='text' id='R2_name_" . $i . "' style='width:120px' value=''><input type='text' id='R2_score_" . $i . "' value='' style='width:20px'></div>");
    }
    echo("</div>");
  }

  if($total_players > 1)
  {
    echo("<div class='bracket bracket-6'>");
    echo("<div class='team-item' style='width:200px'><input type='text' id='R1_name_0' style='width:120px' value=''></div>");
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
      element_other = ((element_no)+1);
    }
    else
    {
      element_other = ((element_no)-1);
    }
    //console.log("Now " + element_no + ", Other " + element_other);
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
    //var element_after = '';
    if(element_no % 2 == 0)
    {
      element_next = Math.round(element_no/2);
      element_before = ((element_no)+1);
    }
    else
    {
      element_next = Math.round((element_no-1)/2);
      element_before = ((element_no)-1);
    }
    var score_1 = $('#R' + col_no + '_score_' + element_no).val();
    var score_2 = $('#R' + col_no + '_score_' + element_before).val();
    if(score_2 < score_1)
    {
      player_name = $('#R' + col_no + '_name_' + element_no).val();
    }
    else if(score_1 < score_2)
    {
      player_name = $('#R' + col_no + '_name_' + element_before).val();
    }
    $('#R' + next_index + '_name_' + element_next).val(player_name);
  }
  
  $.fn.getScoreData = function (round, member_1, member_2)
  {
    $.ajax({
      url:"get_score_data.php?total_players=" + round + "&member_1=" + member_1 + "&member_2=" + member_2,
      method: 'POST',
      success:function(data)
      {
        var newData = data.split(':');
        console.log("Player 1 " + (newData[0]));
        console.log("Player 2 " + (newData[1]));
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
    /*$('#R128_score_' + i).change(function () { 
      var str = $(this).attr('id');
      $.fn.get_draw(str);
    });*/

    $('#R128_name_' + i).click(function() {
      $('#scores_modal').modal('show');
      var id = $(this).attr('id');
      $('#playername_1').html($('#' + id).val());
      $('#playername_2').html($('#R128_name_' + $.fn.get_index_id(id)).val());
      $('#member_id_1').html($.fn.get_id_element(id));
      $('#member_id_2').html($('#R128_id_' + $.fn.get_index_id(id)).val());
      $('#element_id').html(id);
      $.fn.get_index_id(id);
    });
  }

  for(i = 0; i < 64; i++)
  {
    /*$('#R64_score_' + i).change(function () { 
      var str = $(this).attr('id');
      $.fn.get_draw(str);
   });*/

    $('#R64_name_' + i).click(function() {
      $('#scores_modal').modal('show');
      var id = $(this).attr('id');
      $('#playername_1').html($('#' + id).val());
      $('#playername_2').html($('#R64_name_' + $.fn.get_index_id(id)).val());
      $('#member_id_1').html($.fn.get_id_element(id));
      $('#member_id_2').html($('#R64_id_' + $.fn.get_index_id(id)).val());
      $('#element_id').html(id);
    });
  }

  for(i = 0; i < 32; i++)
  {
    /*$('#R32_score_' + i).change(function () { 
      var str = $(this).attr('id');
      $.fn.get_draw(str);
    });*/

    $('#R32_name_' + i).click(function() {
      $('#scores_modal').modal('show');
      var id = $(this).attr('id');
      $('#playername_1').html($('#' + id).val());
      $('#playername_2').html($('#R32_name_' + $.fn.get_index_id(id)).val());
      $('#member_id_1').html($.fn.get_id_element(id));
      $('#member_id_2').html($('#R32_id_' + $.fn.get_index_id(id)).val());
      $('#element_id').html(id);
    });
  }

  for(i = 0; i < 16; i++)
  {
    /*$('#R16_score_' + i).change(function () { 
      var str = $(this).attr('id');
      $.fn.get_draw(str);
    });*/

    $('#R16_name_' + i).click(function() {
      $('#scores_modal').modal('show');
      var id = $(this).tr('id');
      $('#playername_1').html($('#' + id).val());
      $('#playername_2').html($('#R16_name_' + $.fn.get_index_id(id)).val());
      $('#member_id_1').html($.fn.get_id_element(id));
      $('#member_id_2').html($('#R16_id_' + $.fn.get_index_id(id)).val());
      $('#element_id').html(id);
    });
  }

  for(i = 0; i < 8; i++)
  {
    /*$('#R8_score_' + i).change(function () { 
      var str = $(1his).attr('id');
      $.fn.get_draw(str);
    });*/

    $('#R8_name_' + i).click(function() {
      $('#score1_modal').modal('show');
      var id = $(this).attr('id');
      $('#playername_1').html($('#' + id).val());
      $('#pyernam1_2').html($('#R8_name_' + $.fn.get_index_id(id)).val());
      $('#me1ber_id_1').htm1($.fn.get_id_element(id));
      $('#member_id_2').html($('#R8_id_' + $.fn.get_index_id(id)).val());
      $.ajax({
        url:"get_score_data.php?total_players=8&member_1=" + $.fn.get_id_element(id) + "&member_2=" + $('#R8_id_' + $.fn.get_index_id(id)).val(),
        method: 'POST',
        success:function(data)
        {
          var newData = data.split(':');
          console.log("Player 1 " + (newData[0]));
          console.log("Player 2 " + (newData[1]));
          var member_1_data = newData[0].split(", ");
          for (var i = 0; i < member_1_data.length; i++) {
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
          for (var i = 0; i < member_2_data.length; i++) {
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
      $('#element_id').html(id);
    });
  }

  for(i = 0; i < 4; i++)
  {
    /*$('#R4_score_' + i).change(function () { 
      var str = $(this).attr('id');
      $.fn.get_draw(str);
    });*/

    $('#R4_name_' + i).click(function() {
      $('#scores_modal').modal('show');
      var id = $(this).attr('id');
      var Memb1 = $.fn.get_id_element(id);
      var Memb2 = $('#R8_id_' + $.fn.get_index_id(id)).val();
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
    /*$('#R2_score_' + i).change(function () { 
      var str = $(this).attr('id');
      $.fn.get_draw(str);
    });*/

    $('#R2_name_' + i).click(function() {
      $('#scores_modal').modal('show');
      var id = $(this).attr('id');
      $('#playername_1').html($('#' + id).val());
      $('#playername_2').html($('#R2_name_' + $.fn.get_index_id(id)).val());
      $('#member_id_1').html($.fn.get_id_element(id));
      $('#member_id_2').html($('#R2_id_' + $.fn.get_index_id(id)).val());
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
    var scoredata_1 = [];
    var scoredata_2 = [];
    var game_score_1 = 0;
    var game_score_2 = 0;
    var player_name = '';
    var tourn_id = $('#tourn_id').html();
    var member_id_1 = $('#member_id_1').html();
    var member_id_2 = $('#member_id_2').html();
    var element_id = $('#element_id').html();
    for(i = 0; i < 7; i++)
    {
      console.log("Game Number " + (i+1));
      score_data_1[i] = $('#score' + (i+1) + '_1').val();
      break_data_1[i] = $('#brk' + (i+1) + '_1').val();
      score_data_2[i] = $('#score' + (i+1) + '_2').val();
      break_data_2[i] = $('#brk' + (i+1) + '_2').val();
      //$('#score' + (i+1) + '_1').change(function () { 
      if(score_data_1[i] > score_data_2[i])
      {
        game_score_1++;
        console.log("Game score 1 val " + game_score_1);
        $('#game_score_1').val(game_score_1);
      }
      else if(score_data_1[i] < score_data_2[i])
      {
        game_score_2++;
        console.log("Game score 2 val " + game_score_2);
        $('#game_score_2').val(game_score_2);
      }
      //});
    }
    //$.fn.get_draw(element_id);
    
    var score_1 = 0;
    var score_2 = 0;
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
      element_other = ((element_no)+1);
    }
    else
    {
      element_next = Math.round((element_no-1)/2);
      element_other = ((element_no)-1);
    }

    var second = (element_id.split(subStr, 2).join(subStr).length);
    var element_no = parseInt(element_id.substring(second+1));

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

    $.fn.get_draw(element_id);
/*
    if(game_score_2 < game_score_1)
    {
      player_name = $('#R' + col_no + '_name_' + element_no).val();
      $('#R' + (col_no/2) + '_name_' + element_other).val(player_name);
    }
    else if(game_score_1 < game_score_2)
    {
      player_name = $('#R' + col_no + '_name_' + element_other).val();
      $('#R' + (col_no/2) + '_name_' + element_next).val(player_name);
    }
    else if(game_score_1 == game_score_2)
    {
      alert('Cannot have a draw!');
    }
    //$('#R' + (col_no/2) + '_name_' + element_no).val(player_name);
*/
    scoredata_player_1 = 
          col_no + ", " + 
          member_id_1 + ", " + 
          game_score_1 + ", " + 
          score_data_1 + ", " + 
          break_data_1 + ", " + 
          parseInt(element_no);
    //scoredata_1 = [].concat(scoredata_1, scoredata_player_1);

    scoredata_player_2 = 
          col_no + ", " + 
          member_id_2 + ", " + 
          game_score_2 + ", " + 
          score_data_2 + ", " + 
          break_data_2 + ", " + 
          parseInt(element_other);
    //scoredata_2 = [].concat(scoredata_2, scoredata_player_2);

    //scoredata_player_1 = scoredata_player_1;
    scoredata_player_1 = JSON.stringify(scoredata_player_1);
    //scoredata_player_2 = scoredata_player_2;
    scoredata_player_2 = JSON.stringify(scoredata_player_2);

    console.log('Player 1 ' + scoredata_player_1);
    console.log('Player 2 ' + scoredata_player_2);

    $.ajax({
      url:"save_score_data.php?tourn_id=" + tourn_id + "&score_data_1=" + scoredata_player_1 + "&score_data_2=" + scoredata_player_2,
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
        alert(response);
      },
    });

  });

});
</script>

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
            <td colspan='2'  align='center'>Match Score</td>
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
            <td colspan='2'  align='center'>Match Score</td>
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
            <td align='center'><input type='text' id='brk7v' style='width:100px; height:20px'></td>
          </tr>
          <tr>
            <td colspan='2' align='center'>Game Score</td>
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