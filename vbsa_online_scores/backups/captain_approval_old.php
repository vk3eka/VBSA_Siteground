<?php

include ("connection.inc");
include ("header.php");
include ("php_functions.php");

$season = $_SESSION['season'];
$login = $_SESSION['login_rights'];
$current_season = $_SESSION['year'];
//$team = $_POST['HomeTeam'];
//$opposition = $_POST['AwayTeam'];

//echo("Home Team " . $_POST['HomeTeam'] . "<br>");
//echo("Scoring Team " . $_POST['TeamScoring'] . "<br>");
/*
if($_POST['TeamScoring'] == 'away')
{
  $team = $_POST['HomeTeam'];
  $opposition = $_POST['AwayTeam'];
}
elseif($_POST['TeamScoring'] == 'home')
{
  $opposition = $_POST['AwayTeam'];
  $team = $_POST['HomeTeam'];
}
*/
$session_home = $_SESSION['home'];
//echo("Session Home " . $session_home . "<br>");
$session_away = $_SESSION['away'];
//echo("Session Away " . $session_away . "<br>");

//echo("Home " . $_SESSION['team'] . "<br>");
//echo("Away " . $_SESSION['opposition'] . "<br>");

//echo("Session Home " . $session_home . "<br>");
//echo("Session Away " . $session_away . "<br>");
//echo("Post to Edit " . $_POST['TeamtoEdit'] . "<br>");

if($_POST['ButtonName'] == "SaveData") 
{
  $sql_players = "Update tbl_scoresheet Set 
      capt_home = " . $_POST['Home_Approve'] . ", 
      capt_away = " . $_POST['Away_Approve'] . " 
      where (team = '" . $session_home . "' OR team = '" . $session_away . "')
      AND round = " . $_POST['Round'] . " AND season = '" . $_POST['Season'] . "' AND date_played = '" . MySqlDate($_POST['DatePlayed']) . "' AND year = " . $_POST['Year'];
  $update = $dbcnx_client->query($sql_players);
  if(! $update )
  {
      die("Could not player update data: " . mysqli_error($dbcnx_client));
  } 
  if(($_POST['Home_Approve'] == 1) && ($_POST['Away_Approve'] == 1))
  {
    echo "<script type=\"text/javascript\">"; 
    echo "alert('Approvals Saved!')"; 
    echo "</script>";
    echo "<script type='text/javascript'>window.location = '" . $url . "/index.php'</script>";
  }
}

$season = $_POST['Season'];
$type = $_POST['Type'];
$grade = $_POST['Grade'];
$team_grade = $_POST['TeamGrade'];
$date_played = $_POST['DatePlayed'];
$round = $_POST['Round'];

$no_of_players = 4;
$no_of_games = 3;

?>
<?php
echo("<script type='text/javascript'>");

echo("function fillelementarray() {");
// get hometeam

  $sql_wins = "Select * from tbl_club_results where club = '" . $session_home . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season;

  $result_wins = $dbcnx_client->query($sql_wins) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $build_data_wins = $result_wins->fetch_assoc();

  $sql = "Select * from tbl_scoresheet where team = '" . $session_home . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season . " Order By playing_position";
  $result = $dbcnx_client->query($sql) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $j = 0;
  while($build_data = $result->fetch_assoc()) 
  {
    if($build_data['capt_home'] == 1)
    {
      $checkbox5 = ' checked';
    }
    else
    {
      $checkbox5 = '';
    }
    echo("document.getElementById('A_player_" . $j . "').innerHTML = '" . $build_data['players_name'] . "';");

    if($build_data['capt_home'] == 1)
    {
      echo("document.getElementById('home_ok').checked = true;");
    }
    else
    {
      echo("document.getElementById('home_ok').checked = false;");
    }

    if($type == 'Snooker') 
    {   
      for($i = 0; $i < $no_of_games; $i++)
      {
        if($build_data['win_' . ($i+1)] == 1)
        {
          echo("document.getElementById('A_win_" . $j . "_" . $i . "').checked = true;");
        }
        else
        {
          echo("document.getElementById('A_win_" . $j . "_" . $i . "').checked = false;");
        }
        echo("document.getElementById('A_score_" . $j . "_" . $i . "').value = '" . $build_data['score_' . ($i+1)] . "';");
        echo("document.getElementById('A_breaks_" . $j . "_" . $i . "').value = '" . $build_data['break_' . ($i+1)] . "';");
        
        echo("document.getElementById('A_wins').value = " . $build_data_wins['games_won'] . ";");
      }
    }
    
    if($type == 'Billiards')
    {
      if($build_data['win_1'] == 1)
      {
        echo("document.getElementById('A_win_" . $j . "').checked = true;");
      }
      else
      {
        echo("document.getElementById('A_win_" . $j . "').checked = false;");
      }
      if($build_data['draw_1'] == 1)
      {
        echo("document.getElementById('A_draw_" . $j . "').checked = true;");
      }
      else
      {
        echo("document.getElementById('A_draw_" . $j . "').checked = false;");
      }
      echo("document.getElementById('A_score_" . $j . "').value = '" . $build_data['score_1'] . "';");
      echo("document.getElementById('A_breaks_" . $j . "').value = '" . $build_data['break_1'] . "';");

      echo("document.getElementById('A_wins').value = " . $build_data_wins['games_won'] . ";");
      echo("document.getElementById('A_draws').value = " . $build_data_wins['games_drawn'] . ";");
    }
    
    $j++;
  }

  // get awayteam
  $sql_wins_op = "Select * from tbl_club_results where club = '" . $session_away . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season;

  $result_wins_op = $dbcnx_client->query($sql_wins_op) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $build_data_wins_op = $result_wins_op->fetch_assoc();

  $sql_op = "Select * from tbl_scoresheet where team = '" . $session_away . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season . " Order By playing_position";
  $result_op = $dbcnx_client->query($sql_op) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $j = 0;
  while ($build_data_op = $result_op->fetch_assoc()) 
  {
     if($build_data_op['capt_away'] == 1)
    {
      $checkbox6 = ' checked';
    }
    else
    {
      $checkbox6 = '';
    }
    echo("document.getElementById('B_player_" . $j . "').innerHTML = '" . $build_data_op['players_name'] . "';");
    if($build_data_op['capt_away'] == 1)
    {
      echo("document.getElementById('away_ok').checked = true;");
    }
    else
    {
      echo("document.getElementById('away_ok').checked = false;");
    }
    
    for ($i = 0; $i < $no_of_games; $i++) 
    {   
      if($type == 'Snooker')
      {
        if($build_data_op['win_' . ($i+1)] == 1)
        {
          echo("document.getElementById('B_win_" . $j . "_" . $i . "').checked = true;");
        }
        else
        {
          echo("document.getElementById('B_win_" . $j . "_" . $i . "').checked = false;");
        }
        echo("document.getElementById('B_score_" . $j . "_" . $i . "').value = '" . $build_data_op['score_' . ($i+1)] . "';");
        echo("document.getElementById('B_breaks_" . $j . "_" . $i . "').value = '" . $build_data_op['break_' . ($i+1)] . "';");

        echo("document.getElementById('B_wins').value = " . $build_data_wins_op['games_won'] . ";");
      }
    }
    if($type == 'Billiards')
    {
      if($build_data_op['win_1'] == 1)
      {
        echo("document.getElementById('B_win_" . $j . "').checked = true;");
      }
      else
      {
        echo("document.getElementById('B_win_" . $j . "').checked = false;");
      }
      if($build_data_op['draw_1'] == 1)
      {
        echo("document.getElementById('B_draw_" . $j . "').checked = true;");
      }
      else
      {
        echo("document.getElementById('B_draw_" . $j . "').checked = false;");
      }
      echo("document.getElementById('B_score_" . $j . "').value = '" . $build_data_op['score_1'] . "';");
      echo("document.getElementById('B_breaks_" . $j . "').value = '" . $build_data_op['break_1'] . "';");
      echo("document.getElementById('B_wins').value = " . $build_data_wins_op['games_won'] . ";");
      echo("document.getElementById('B_draws').value = " . $build_data_wins_op['games_drawn'] . ";");
    }
    $j++;
  }
echo("}");

echo("</script>");
?>
<script>
window.onload = function() 
{
  fillelementarray();
}

function SaveApproval() {
  if(document.getElementById('home_ok').checked == true)
  {
    home_ok = 1;
  }
  else
  {
    home_ok = 0;
  }
  if(document.getElementById('away_ok').checked == true)
  {
    away_ok = 1;
  }
  else
  {
    away_ok = 0;
  }
  document.capt_auth.ButtonName.value = 'SaveData'; 
  document.capt_auth.Round.value = <?= $_POST['Round'] ?>; 
  document.capt_auth.Year.value = <?php echo($current_season); ?>; 
  document.capt_auth.Season.value = '<?= $_POST['Season'] ?>'; 
  //document.capt_auth.HomeTeam.value = '<?= $session_home ?>'; 
  //document.capt_auth.AwayTeam.value = '<?= $session_away ?>'; 
  document.capt_auth.DatePlayed.value = '<?= $_POST['DatePlayed'] ?>'; 
  document.capt_auth.TeamGrade.value = '<?= $team_grade ?>'; 
  document.capt_auth.Type.value = '<?= $type ?>'; 
  document.capt_auth.Home_Approve.value = home_ok; 
  document.capt_auth.Away_Approve.value = away_ok; 
  document.capt_auth.submit();
}

function EditTeam($edit_team) 
{
  //alert("Home " + "<?php echo($_SESSION['home']); ?>" + ", Away " + "<?php echo($_SESSION['away']); ?>" + ", Edit " + $edit_team + ", Club " + "<?php echo($_SESSION['clubname']); ?>");
  //$teams = $edit_team.split(",");
  if($edit_team == '<?php echo($_SESSION['home']); ?>')
  {
    $team = '<?php echo($_SESSION['home']); ?>';
    $opposition = '<?php echo($_SESSION['away']); ?>';
  }
  else
  {
    $team = '<?php echo($_SESSION['away']); ?>';
    $opposition = '<?php echo($_SESSION['home']); ?>';
  }
  //alert($team + ", " + $opposition);
  /*
  if($teams[0] == "<?php echo($_SESSION['clubname']); ?>")
  {
    //alert("Same as Club");
    document.edit_scoresheet.TeamtoEdit.value = $teams[0]; 
    document.edit_scoresheet.OppositiontoEdit.value = $teams[1]; 
  }
  else
  {
    //alert("Not Same as Club");
    document.edit_scoresheet.TeamtoEdit.value = $teams[1]; 
    document.edit_scoresheet.OppositiontoEdit.value = $teams[0]; 
  }
  */
  document.edit_scoresheet.Year.value = <?php echo($current_season); ?>; 
  document.edit_scoresheet.Grade.value = '<?php echo($_POST['Grade']); ?>'; 
  document.edit_scoresheet.FixtureDate.value = '<?php echo($_POST['DatePlayed']); ?>'; 
  document.edit_scoresheet.Season.value = '<?= $season ?>'; 
  //document.edit_scoresheet.HomeTeam.value = '<?php echo($_SESSION['home']); ?>'; 
  //document.edit_scoresheet.AwayTeam.value = '<?php echo($_SESSION['away']); ?>';
  document.edit_scoresheet.RoundNo.value = <?= $round ?>; 
  document.edit_scoresheet.TeamGrade.value = '<?= $team_grade ?>'; 
  document.edit_scoresheet.TeamScoring.value = '<?php echo($_POST['TeamScoring']); ?>'; 
  document.edit_scoresheet.Type.value = '<?= $type ?>'; 
  document.edit_scoresheet.DatePlayed.value = '<?= $_POST['DatePlayed'] ?>'; 
  document.edit_scoresheet.TeamtoEdit.value = $team; 
  document.edit_scoresheet.OppositiontoEdit.value = $opposition; 
  
  //document.edit_scoresheet.TeamtoEdit.value = $teams[0]; 
  //document.edit_scoresheet.OppositiontoEdit.value = $teams[1]; 
  document.edit_scoresheet.action = 'scoresheet.php';
  document.edit_scoresheet.submit();
}

</script>
<script>

$(document).ready(function()
{
  // get away team captain to enter email and password to activate checkbox
  $('#home_ok').click(function(event){
    event.preventDefault();
    var team = '<?= $session_home ?>';
    alert(team);
    var home_away = 'home';
     $.fn.get_approval(team, home_away);
  });

  // get away team captain to enter email and password to activate checkbox
  $('#away_ok').click(function(event){
    event.preventDefault();
    var team = '<?= $session_away ?>';
    var home_away = 'away';
    $.fn.get_approval(team, home_away);
  });

  $.fn.get_approval = function (team, home_away) {
    event.preventDefault();
    $.ajax({
      url:"<?= $url ?>/get_approval_captain.php?Team=" + team + "&HomeAway=" + home_away,
      success : function(data){
        var obj = jQuery.parseJSON(data);
        alert(obj);
        $('#email').html("Enter Team Captain password for " + team);
        $('#email_address').val(obj);
        $('#venue').val(obj[(obj.length-1)]);
        $('#LoginModal').modal('show');
      },
      error: function (request, error) {
        alert("Error, cannot get data"); 
      }
    });
  };

  $('#approve').click(function(event){
    event.preventDefault();
    var password = $('#password').val();
    var email = $('#email_address').val();
    var venue = $('#venue').val();
    $.ajax({
      url:"<?= $url ?>/get_login.php",
      method: 'GET',
      data:{
            username: email,
            password: password,
            venue: venue,
      },
      success:function(response)
      {
        if((response == 'home') || (response == 'away'))
        {
          $("input[id='" + response + "_ok']").prop("checked", true);
          $('#password').val('');
          $('#LoginModal').modal('hide');
          $.fn.save_approval = function (team, home_away) {
        }
        else
        {
          alert("Incorrect Password or you are Not authorised!");
        }
      }
    });
  });

  $('#cancel').click(function(event){
    event.preventDefault();
    $("input[id='home_ok']").prop("checked", false);
    $("input[id='away_ok']").prop("checked", false);
    $('#password').val('');
    $('#LoginModal').modal('hide');
  });

  $.fn.save_approval = function (team, home_away) {
    event.preventDefault();

    if($('#home_ok').prop("checked", true))
    {
      home_ok = 1;
    }
    else
    {
      home_ok = 0;
    }
    if($('#away_ok').prop("checked", true))
    {
      away_ok = 1;
    }
    else
    {
      away_ok = 0;
    }
    Round = <?= $_POST['Round'] ?>; 
    Year = <?php echo($current_season); ?>; 
    Season = '<?= $_POST['Season'] ?>'; 
    DatePlayed = '<?= $_POST['DatePlayed'] ?>'; 
    TeamGrade = '<?= $team_grade ?>'; 
    Type = '<?= $type ?>'; 
    Home_Approve = home_ok; 
    Away_Approve = away_ok; 

    $.ajax({
      url:"<?= $url ?>/save_approvals.php",
      method: 'POST',
      data:{
        /*
            $home_approve: $_POST['Home_Approve'],
            $home_approve: $_POST['Away_Approve'],
            $session_home,
            $session_away,
            $round = $_POST['Round'],
            $season = $_POST['Season'],
            $date = MySqlDate($_POST['DatePlayed']),
            $year = $_POST['Year'],
        */
      },
      success:function(response)
      {
        if((response == 'home') || (response == 'away'))
        {
          $("input[id='" + response + "_ok']").prop("checked", true);
          $('#password').val('');
          $('#LoginModal').modal('hide');
        }
        else
        {
          alert("Incorrect Password or you are Not authorised!");
        }
      }
    });

  }


});
</script>
<center>
<form name="capt_auth" method="post" action="captain_approval.php">
<input type="hidden" name="Round" value="" />
<input type="hidden" name="Year" value="" />
<input type="hidden" name="Season" value="" />
<!--<input type="hidden" name="HomeTeam" value="" />
<input type="hidden" name="AwayTeam" value="" />-->
<input type="hidden" name="DatePlayed" />
<input type="hidden" name="Home_Approve" value="" />
<input type="hidden" name="Away_Approve" value="" />
<input type="hidden" name="PWD_Check" value="" />
<input type="hidden" name="TeamGrade" value="" />
<input type="hidden" name="Type" value="" />
<input type="hidden" name="PlayerID" value="" />
<input type="hidden" name="ButtonName" />
<input type="hidden" name="Team" />
<table class='table table-striped table-bordered dt-responsive nowrap display'>
  <tr>
   <td colspan=4 align="center"><b>Pennant Score Sheet Summary - <?= $current_season ?>. Season - <?= $season ?></b></td>
  </tr>
  <tr> 
    <td class='text-center'>Grade:&nbsp;<?= $team_grade ?></td>  
    <td class='text-center'>Game type:&nbsp;<?= $type ?></td>  
    <td class='text-center'>Round No.&nbsp;<?= $round ?></td>  
    <td class='text-center'>Date&nbsp;<?= $date_played ?></td>
  </tr>
</table>
<script>

function disableCB(i, j)
{
  if (document.getElementById("A_win_" + i + "_" + j).checked === true) 
  {
    //alert("Checked");
    document.getElementById("A_win_" + i + "_" + j).checked = false;
  }
  else if(document.getElementById("A_win_" + i + "_" + j).checked === false) 
  {
    //alert("Not Checked");
    document.getElementById("A_win_" + i + "_" + j).checked = true;
  }
  if (document.getElementById("B_win_" + i + "_" + j).checked === true) 
  {
    //alert("Checked");
    document.getElementById("B_win_" + i + "_" + j).checked = false;
  }
  else if(document.getElementById("B_win_" + i + "_" + j).checked === false) 
  {
    //alert("Not Checked");
    document.getElementById("B_win_" + i + "_" + j).checked = true;
  }
}
</script>

<table class='table table-striped table-bordered dt-responsive nowrap display'>
<?php
if($type == 'Snooker')
{
  echo("<tr>");
  //echo("<td colspan=3 align=center>" . $team . "</td>");

  //echo("<td colspan=3 align=center>" . $opposition . "</td>");
  echo("<td colspan=3 align=center>" . $session_home . "</td>");

  echo("<td colspan=3 align=center>" . $session_away . "</td>");
  echo("</tr>");
  echo("<tr>");
  echo("<td align='center'>Player</td>"); 
  echo("<td align='center'>Win</td>"); 
  echo("<td align='center'>Scrs/Brks</td>"); 
  //echo("<td align='center'>Breaks</td>"); 
  //echo("<td align='center'>&nbsp;</td>");
  echo("<td align='center'>Player</td>"); 
  echo("<td align='center'>Win</td>");
  echo("<td align='center'>Scrs/Brks</td>"); 
  //echo("<td align='center'>Breaks</td>"); 
  echo("</tr>"); 
  for($i = 0; $i < $no_of_players; $i++)
  {
    echo("<tr>");
    echo("<td align=center>");
    echo("<div id='A_player_" . $i . "'></div>");
    echo("</td>");
    echo("<td align=center>");
    for($j = 0; $j < $no_of_games; $j++) // no of games
    { 
      echo("<input type='checkbox' id='A_win_" . $i . "_" . $j . "' OnChange='disableCB(" . $i . ", " . $j . ");'><br>");
    }
    echo("</td>");
    echo("<td align=center>");
    for($j = 0; $j < $no_of_games; $j++) // no of games
    { 
      echo("<input type='text' id='A_score_" . $i . "_" . $j . "' style='width:30px; height:20px' readonly>");
      echo("<input type='textn' id='A_breaks_" . $i . "_" . $j . "' style='width:30px; height:20px' readonly><br>");
    }
    echo("</td>");
    /*echo("<td align=center>");
    for($j = 0; $j < $no_of_games; $j++) // no of games
    { 
      //echo("<input type='text' id='A_score_" . $i . "_" . $j . "' style='width:40px; height:20px'>");
      echo("<input type='text' id='A_breaks_" . $i . "_" . $j . "' style='width:40px; height:20px'><br>");
    }
    echo("</td>");*/
    //echo("<td>&nbsp;</td>");
    echo("<td align=center>");
    echo("<div id='B_player_" . $i . "'></div>");
    echo("</td>");
    echo("<td align=center>");
    for($j = 0; $j < $no_of_games; $j++) // no of games
    { 
      echo("<input type='checkbox' id='B_win_" . $i . "_" . $j . "' OnChange='disableCB(" . $i . ", " . $j . ");'><br>");
    }
    echo("</td>");
    echo("<td align=center>");
    for($j = 0; $j < $no_of_games; $j++) // no of games
    { 
      echo("<input type='text' id='B_score_" . $i . "_" . $j . "' style='width:30px; height:20px' readonly>");
      echo("<input type='text' id='B_breaks_" . $i . "_" . $j . "' style='width:30px; height:20px' readonly><br>");
    }
    echo("</td>");
    /*echo("<td align=center>");
    for($j = 0; $j < $no_of_games; $j++) // no of games
    { 
      //echo("<input type='text' id='B_score_" . $i . "_" . $j . "' style='width:40px; height:20px'>");
      echo("<input type='text' id='B_breaks_" . $i . "_" . $j . "' style='width:40px; height:20px'><br>");
    }
    echo("</td>");
    */
  }
}
elseif($type == 'Billiards')
{
  echo("<tr>");
  echo("<td colspan=3 align='center'>" . $session_home . "</td>");
  //echo("<td colspan=1 align='center'>V</td>");
  echo("<td colspan=3 align='center'>" . $session_away . "</td>");
  echo("</tr>");
  echo("<tr>");
  echo("<td align='center'>Player</td>"); 
  echo("<td align='center'>Result</td>"); 
  echo("<td align='center'>Score<br>Breaks</td>"); 
  //echo("<td align='center'>Breaks</td>"); 
  //echo("<td align='center'>&nbsp;</td>");
  echo("<td align='center'>Player</td>"); 
  echo("<td align='center'>Result</td>"); 
  echo("<td align='center'>Score<br>Breaks</td>"); 
  //echo("<td align='center'>Breaks</td>"); 
  echo("</tr>"); 
  for($i = 0; $i < $no_of_players; $i++)
  {
    echo("<tr>");
    echo("<td align=center><div id='A_player_" . $i . "'></div></td>");
    echo("<td align=center>Win&nbsp;&nbsp;<input type='radio' id='A_win_" . $i . "' name='A_Result_" . $i . "' disabled><br>");
    echo("Loss&nbsp;<input type='radio' id='A_loss_" . $i . "' name='A_Result_" . $i . "' checked disabled><br>");
    echo("Draw&nbsp;<input type='radio' id='A_draw_" . $i . "' name='A_Result_" . $i . "' disabled></td>");
    echo("<td align=center><input type='text' id='A_score_" . $i . "' style='width:40px; 
      height:20px' readonly><br><br>");
    //echo("<textarea id='A_breaks_" . $i . "'></textarea></td>");
    echo("<textarea id='A_breaks_" . $i . "' rows=6 style='width:40px' readonly></textarea></td>");
    //echo("<input type='text' id='A_breaks_" . $i . "' style='width:60px; height:20px'></td>");
    //echo("<td>&nbsp;</td>");
    echo("<td align=center><div id='B_player_" . $i . "'></div></td>");
    echo("<td align=center>Win&nbsp;&nbsp;<input type='radio' id='B_win_" . $i . "' name='B_Result_" . $i . "' disabled><br>");
    echo("Loss&nbsp;<input type='radio' id='B_loss_" . $i . "' name='B_Result_" . $i . "' checked disabled><br>");
    echo("Draw&nbsp;<input type='radio' id='B_draw_" . $i . "' name='B_Result_" . $i . "' disabled></td>");
    echo("<td align=center><input type='text' id='B_score_" . $i . "' style='width:40px; height:20px' readonly><br><br>");
    //echo("<input type='text' id='B_breaks_" . $i . "' style='width:60px; height:20px'></td>");
    echo("<textarea id='B_breaks_" . $i . "' rows=6 style='width:40px' readonly></textarea></td>");
    echo("</tr>");
  }
}

?>
<?php
// check games won ratio
$sql_home = "Select * from tbl_club_results where club = '" . $session_home . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season;
$result_home = $dbcnx_client->query($sql_home) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$build_data_home = $result_home->fetch_assoc();

$sql_away = "Select * from tbl_club_results where club = '" . $session_away . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season;
$result_away = $dbcnx_client->query($sql_away) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$build_data_away = $result_away->fetch_assoc();

// add games won/drawn etc
if($type == 'Snooker')
{
  $max_wins = ($no_of_games*$no_of_players);
  $home_wins = $build_data_home['games_won'];
  $away_wins = $build_data_away['games_won'];
  $max_played = ($build_data_home['games_won']+$build_data_away['games_won']);
  if($max_wins == $max_played)
  {
    //echo("Good!<br>");
    $style = "style='background-color:yellowgreen;color:black;'";
  }
  else
  {
    //echo("Somebody is lying!<br>");
    $style = "style='background-color:red;color:black;'";
  }
  $results_span = 2;
  echo("<tr>");
  echo("<input type='hidden' id='A_draws' value=0>");
  echo("<td " . $style . " class='text-right'><b>Games Won:</b>");
  echo("</td>");
  echo("<td " . $style . " class='text-center'><input type='text' id='A_wins' style='width:30px; height:20px' readonly></td>");
  //echo("<td>&nbsp;</td>");
  //echo("<td>&nbsp;</td>");
  echo("<td " . $style . ">&nbsp;</td>");
  echo("<input type='hidden' id='B_draws' value=0>");
  echo("<td " . $style . " class='text-right'><b>Games Won:</b>");
  echo("</td>");
  echo("<td " . $style . " class='text-center'><input type='text' id='B_wins' style='width:30px; height:20px' readonly></td>");
  //echo("<td>&nbsp;</td>");
  echo("<td " . $style . ">&nbsp;</td>");
  echo("</tr>");
}
elseif($type == 'Billiards')
{
  $home_draws = $build_data_home['games_drawn'];
  $away_draws = $build_data_away['games_drawn'];

  $home_wins = $build_data_home['games_won'];
  $away_wins = $build_data_away['games_won'];
  $max_played = (($build_data_home['games_won']+$build_data_away['games_won'])*2);
  $max_draws = ($build_data_home['games_drawn']+$build_data_away['games_drawn']);
  if(($max_played+$max_draws) == 8)
  {
    //echo("Good!<br>");
    $style = "style='background-color:yellowgreen;color:black;'";
  }
  else
  {
    //echo("Somebody is lying!<br>");
    $style = "style='background-color:red;color:black;'";
  }
  $results_span = 2;
  echo("<tr>");
  //echo("<td>&nbsp;</td>");
  //echo("<td colspan=2 class='text-center'>Overall Points:&nbsp;&nbsp;");
  //echo("<input type='text' id='A_points' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . " class='text-center'>Games Drawn:&nbsp;&nbsp;");
  echo("<input type='text' id='A_draws' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . " class='text-center'>Games Won:&nbsp;&nbsp;");
  echo("<input type='text' id='A_wins' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . ">&nbsp;</td>");
  //echo("<td>&nbsp;</td>");
  //echo("<td colspan=2 class='text-center'>Overall Points:&nbsp;&nbsp;");
  //echo("<input type='text' id='B_points' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . " class='text-center'>Games Won:&nbsp;&nbsp;");
  echo("<input type='text' id='B_wins' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . " class='text-center'>Games Drawn:&nbsp;&nbsp;");
  echo("<input type='text' id='B_draws' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . ">&nbsp;</td>");
  echo("</tr>");
}
?>
<tr> 
<td colspan=8 align=center>&nbsp;</td> 
</tr> 
  <tr> 
    <td colspan=<?= $results_span ?> class='text-center'><b>Home Captain Approve:</b></td>
    <td class='text-center'><input type='checkbox' id='home_ok' <?=  $checkbox5  ?> <?php echo($home_ok); ?> ></td>
    <!--<td>&nbsp;</td>-->
    <td colspan=<?= $results_span ?> class='text-center'><b>Away Captain Approve:</b></td>
    <td class='text-center'><input type='checkbox' id='away_ok'></td>
    <!--<td class='text-center'><input type='checkbox' id='away_ok'  <?=  $checkbox6  ?> <?php echo($away_ok); ?> ></td>-->
  </tr>
</table>
<?php 
if(!$checkbox5 || !$checkbox6)
{
?>
<div> 
  <div class='text-center'>
    <a class='btn btn-primary btn-xs' href="javascript:;" onclick="SaveApproval();" style='width:300px'>Save Approvals</a>
  </div>
</div> 
<?php
}
?>
</form>
<br>
<?php 
if(!$checkbox5 || !$checkbox6)
{
?>
<form name="edit_scoresheet" method="post" action="scoresheet.php">
<!--<div class='text-center'>
  <a class='btn btn-primary btn-xs' href="javascript:;" onclick="EditTeam('<?php echo($_SESSION['home'] . "," . $_SESSION['away']); ?>')" style='width:200px'>Edit the Scoresheet</a>
</div>-->
<div class='text-center'>
  <a class='btn btn-primary btn-xs' href="javascript:;" onclick="EditTeam('<?php echo($_SESSION['home']); ?>')" style='width:300px'>Edit the <?php echo($_SESSION['home']); ?> Scoresheet</a>
</div>
<br>
<div>
  <a class='btn btn-primary btn-xs' href="javascript:;" onclick="EditTeam('<?php echo($_SESSION['away']); ?>')" style='width:300px'>Edit the <?php echo($_SESSION['away']); ?> Scoresheet</a>
</div>
<input type="hidden" name="Grade" id="grade" value="<?php echo($_POST['Grade']); ?>" />
<input type="hidden" name="Type" id="type" value="<?php echo($_POST['Type']); ?>" />
<input type="hidden" name="RoundNo" id="round" value="<?php echo($_POST['RoundNo']); ?>" />
<!--<input type="hidden" name="HomeTeam" id="team" value="<?php echo($_SESSION['home']); ?>" />
<input type="hidden" name="AwayTeam" id="opposition" value="<?php echo($_SESSION['away']); ?>" />-->
<input type="hidden" name="DatePlayed" id="playing_date" value="<?php echo($_POST['DatePlayed']); ?>" />
<input type="hidden" name="FixtureDate" id="played_date" value="<?php echo($_POST['DatePlayed']); ?>" />
<input type="hidden" name="TeamGrade" id="team_grade" value="<?php echo($_POST['TeamGrade']); ?>" />
<input type="hidden" name="TeamScoring" id="team_scoring" value="<?php echo($_POST['TeamScoring']); ?>" />
<input type="hidden" name="TeamtoEdit" id="team_to_edit" />
<input type="hidden" name="OppositiontoEdit" id="opp_to_edit" />
<input type="hidden" name="Year" id="year" value="<?php echo($_SESSION['year']); ?>" />
<input type="hidden" name="Season" id="season" value="<?php echo($_SESSION['season']); ?>" />
</form>
<?php
}
?>
<br /> 
</center>
<div class="modal fade" id="LoginModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ui-front">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Log in to activate checkbox</h4>
            </div>
            <div class="modal-body">
              <div id='approval'></div>
              <div class='text-center' id='email'></div>
              <br>
              <div class='text-center'><input type='password' id='password' style='width:250px'></div>
              <br>
              <div class='text-center'>
                If you have forgotten your password<br>Please login to <a href="http://vbsa.cpc-world.com">VBSA</a> on your own device<br>Choose 'Forgotten Password' from the log in screen.
              </div>
              <div class='text-center' id='venue'></div>
              <input type='hidden' id='email_address'>
              <div class='text-center'>&nbsp;</div>
              <div class='text-center'>
                <a class='btn btn-primary btn-xs' id='approve'>Approve</a>
                <a class='btn btn-primary btn-xs' id='cancel'>Cancel</a>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</form>
<?php

include("footer.php"); 

?>

