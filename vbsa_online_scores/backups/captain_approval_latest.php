<?php

include ("connection.inc");
include ("header.php");
include ("php_functions.php");

$season = $_SESSION['season'];
$login = $_SESSION['login_rights'];
$current_season = $_SESSION['year'];
$session_home = $_SESSION['home'];
$session_away = $_SESSION['away'];
$type = $_POST['Type'];
$grade = $_POST['Grade'];
$team_grade = $_POST['TeamGrade'];
$date_played = $_POST['DatePlayed'];
$round = $_POST['Round'];

// need to get from settings.......
$no_of_players = 4;
$no_of_games = 3;

?>
<script type='text/javascript'>

  $.fn.getexistingarray = function (type, team, home_away) {
  var home_team = '<?= $session_home ?>';
  var away_team = '<?= $session_away ?>';
  var round = <?= $round ?>;
  var year = <?= $current_season ?>;
  var season = '<?= $season ?>';
  var team_grade = '<?= $team_grade ?>';
  $.ajax({
    url:"<?= $url ?>/check_approval_changes.php?home=" + home_team + "&away=" + away_team + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round,
    method: 'GET',
    success : function(data){
      var obj = jQuery.parseJSON(data);
      //console.log("Existing fn " + obj);
      //console.log("Current fn " + $.fn.getcurrentarray(type));
      if($.fn.getcurrentarray(type) == obj)
      {
        //alert("The same");
        $.ajax({
          url:"<?= $url ?>/get_approval_captain.php?Team=" + team + "&HomeAway=" + home_away,
          success : function(data){
            var obj = jQuery.parseJSON(data);
            $('#email').html("Enter Team Captain password for " + team);
            $('#email_address').val(obj);
            $('#venue').val(obj[(obj.length-1)]);
            $('#LoginModal').modal('show');
          },
          error: function (request, error) {
            alert("Error, cannot get data"); 
          }
        });
      }
      else
      {
        //alert($.fn.getexistingarray('<?= $type ?>'));
        alert("This page has changed since it was last displayed.\nThe page will be refreshed.");
        location.reload();
        return;
      }
    },
    error: function (request, error) {
        alert("Error, cannot get data"); 
    }
  });
}
//var existingForm = $.fn.getexistingarray('<?= $type ?>');

</script>

<?php
echo("<script type='text/javascript'>");

echo("function fillelementarray() {");
// get hometeam

  $sql_wins = "Select * from tbl_club_results where club = '" . $session_home . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND year = " . $current_season;

  $result_wins = $dbcnx_client->query($sql_wins) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $build_data_wins = $result_wins->fetch_assoc();

  $sql = "Select * from tbl_scoresheet where team = '" . $session_home . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season . "  AND team_grade = '" . $team_grade . "' Order By playing_position";
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
        echo("document.getElementById('A_score_" . $j . "_" . $i . "').value = '" . $build_data['score_' . ($i+1)] . "';");
        echo("document.getElementById('A_breaks_" . $j . "_" . $i . "').value = '" . $build_data['break_' . ($i+1)] . "';");
        echo("document.getElementById('A_wins').value = " . $build_data_wins['games_won'] . ";");
      }
    }
    if($type == 'Billiards')
    {
      echo("document.getElementById('A_score_" . $j . "').value = '" . $build_data['score_1'] . "';");
      echo("document.getElementById('A_breaks_" . $j . "').value = '" . $build_data['break_1'] . "';");
      echo("document.getElementById('A_wins').value = " . $build_data_wins['games_won'] . ";");
      echo("document.getElementById('A_draws').value = " . $build_data_wins['games_drawn'] . ";");
    }
    $j++;
  }

  // get awayteam
  $sql_wins_op = "Select * from tbl_club_results where club = '" . $session_away . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND year = " . $current_season;

  $result_wins_op = $dbcnx_client->query($sql_wins_op) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $build_data_wins_op = $result_wins_op->fetch_assoc();

  $sql_op = "Select * from tbl_scoresheet where team = '" . $session_away . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season . " AND team_grade = '" . $team_grade . "' Order By playing_position";
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
        echo("document.getElementById('B_score_" . $j . "_" . $i . "').value = '" . $build_data_op['score_' . ($i+1)] . "';");
        echo("document.getElementById('B_breaks_" . $j . "_" . $i . "').value = '" . $build_data_op['break_' . ($i+1)] . "';");

        echo("document.getElementById('B_wins').value = " . $build_data_wins_op['games_won'] . ";");
      }
    }
    if($type == 'Billiards')
    {
      echo("document.getElementById('B_score_" . $j . "').value = '" . $build_data_op['score_1'] . "';");
      echo("document.getElementById('B_breaks_" . $j . "').value = '" . $build_data_op['break_1'] . "';");
      echo("document.getElementById('B_wins').value = " . $build_data_wins_op['games_won'] . ";");
      echo("document.getElementById('B_draws').value = " . $build_data_wins_op['games_drawn'] . ";");
    }
    $j++;
  }
//echo("var existingForm = $.fn.getexistingarray('" . $type . "');");
echo("}");
//echo("</script>");

//echo("<script type='text/javascript'>");
echo("function score_matchups() {");
// get score matchups
$sql_score_home = "Select * from tbl_scoresheet where team = '" . $session_home . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season . " AND team_grade = '" . $team_grade . "' Order By playing_position";
  $result_score_home = $dbcnx_client->query($sql_score_home) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $j = 0;
  while ($build_score_home = $result_score_home->fetch_assoc()) 
  {
    $scores_home_1[$j] = ($build_score_home['score_1']); 
    $scores_home_2[$j] = ($build_score_home['score_2']); 
    $scores_home_3[$j] = ($build_score_home['score_3']); 
    $j++;
  }
  $sql_score_away = "Select * from tbl_scoresheet where team = '" . $session_away . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season . " AND team_grade = '" . $team_grade . "' Order By playing_position";
  $result_score_away = $dbcnx_client->query($sql_score_away) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $j = 0;
  while ($build_score_away = $result_score_away->fetch_assoc()) 
  {
    $scores_away_1[$j] = ($build_score_away['score_1']); 
    $scores_away_2[$j] = ($build_score_away['score_2']); 
    $scores_away_3[$j] = ($build_score_away['score_3']); 
    $j++;
  }
  $scores_all_1 = array_merge($scores_home_1, $scores_away_1);
  $scores_all_2 = array_merge($scores_home_2, $scores_away_2);
  $scores_all_3 = array_merge($scores_home_3, $scores_away_3);

  if($type == 'Billiards')
  {
    $home_win = 0;
    $away_win = 0;

    $home_draw = 0;
    $away_draw = 0;

    $check_home_win = 0;
    $check_away_win = 0;
    $check_home_draw = 0;
    $check_away_draw = 0;

    if($scores_all_1[0] > $scores_all_1[4])
    {
      echo("document.getElementById('A_win_0').checked = true;");
      echo("document.getElementById('B_win_0').checked = false;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    if($scores_all_1[0] == $scores_all_1[4])
    {
      echo("document.getElementById('A_draw_0').checked = true;");
      $home_draw = ($home_draw + 1);
      $check_home_draw = ($check_home_draw + 1);

      echo("document.getElementById('B_draw_0').checked = true;");
      $away_draw = ($away_draw + 1);
      $check_away_draw = ($check_away_draw + 1);
    }
    if($scores_all_1[0] < $scores_all_1[4])
    {
      echo("document.getElementById('A_win_0').checked = false;");
      echo("document.getElementById('B_win_0').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

    if($scores_all_1[1] > $scores_all_1[5])
    {
      echo("document.getElementById('A_win_1').checked = true;");
      echo("document.getElementById('B_win_1').checked = false;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    if($scores_all_1[1] == $scores_all_1[5])
    {
      echo("document.getElementById('A_draw_1').checked = true;");
      $home_draw = ($home_draw + 1);
      $check_home_draw = ($check_home_draw + 1);

      echo("document.getElementById('B_draw_1').checked = true;");
      $away_draw = ($away_draw + 1);
      $check_away_draw = ($check_away_draw + 1);
    }
    if($scores_all_1[1] < $scores_all_1[5])
    {
      echo("document.getElementById('A_win_1').checked = false;");
      echo("document.getElementById('B_win_1').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

    if($scores_all_1[2] > $scores_all_1[6])
    {
      echo("document.getElementById('A_win_2').checked = true;");
      echo("document.getElementById('B_win_2').checked = false;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    if($scores_all_1[2] == $scores_all_1[6])
    {
      echo("document.getElementById('A_draw_2').checked = true;");
      $home_draw = ($home_draw + 1);
      $check_home_draw = ($check_home_draw + 1);

      echo("document.getElementById('B_draw_2').checked = true;");
      $away_draw = ($away_draw + 1);
      $check_away_draw = ($check_away_draw + 1);

    }
    if($scores_all_1[2] < $scores_all_1[6])
    {
      echo("document.getElementById('A_win_2').checked = false;");
      echo("document.getElementById('B_win_2').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

    if($scores_all_1[3] > $scores_all_1[7])
    {
      echo("document.getElementById('A_win_3').checked = true;");
      echo("document.getElementById('B_win_3').checked = false;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    if($scores_all_1[3] == $scores_all_1[7])
    {
      echo("document.getElementById('A_draw_3').checked = true;");
      $home_draw = ($home_draw + 1);
      $check_home_draw = ($check_home_draw + 1);

      echo("document.getElementById('B_draw_3').checked = true;");
      $away_draw = ($away_draw + 1);
      $check_away_draw = ($check_away_draw + 1);

    }
    if($scores_all_1[3] < $scores_all_1[7])
    {
      echo("document.getElementById('A_win_3').checked = false;");
      echo("document.getElementById('B_win_3').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    echo("document.getElementById('A_wins').value = " . $home_win . ";");
    echo("document.getElementById('A_draws').value = " . $home_draw . ";");
    echo("document.getElementById('B_wins').value = " . $away_win . ";");
    echo("document.getElementById('B_draws').value = " . $away_draw . ";");
  }
  elseif($type == 'Snooker')
  {
    $home_win = 0;
    $away_win = 0;

    $check_home_win = 0;
    $check_away_win = 0;

    if($scores_all_1[0] > $scores_all_1[4])
    {
      echo("document.getElementById('A_win_0_0').checked = true;"); 
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_0_0').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    if($scores_all_2[0] > $scores_all_2[4])
    {
      echo("document.getElementById('A_win_0_1').checked = true;"); 
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_0_1').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    if($scores_all_3[0] > $scores_all_3[4])
    {
      echo("document.getElementById('A_win_0_2').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_0_2').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

    if($scores_all_1[1] > $scores_all_1[5])
    {
      echo("document.getElementById('A_win_1_0').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_1_0').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    if($scores_all_2[1] > $scores_all_2[5])
    {
      echo("document.getElementById('A_win_1_1').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_1_1').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    if($scores_all_3[1] > $scores_all_3[5])
    {
      echo("document.getElementById('A_win_1_2').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_1_2').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

    if($scores_all_1[2] > $scores_all_1[6])
    {
      echo("document.getElementById('A_win_2_0').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_2_0').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    if($scores_all_2[2] > $scores_all_2[6])
    {
      echo("document.getElementById('A_win_2_1').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_2_1').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

    if($scores_all_3[2] > $scores_all_3[6])
    {
      echo("document.getElementById('A_win_2_2').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_2_2').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

    if($scores_all_1[3] > $scores_all_1[7])
    {
      echo("document.getElementById('A_win_3_0').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_3_0').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    if($scores_all_2[3] > $scores_all_2[7])
    {
      echo("document.getElementById('A_win_3_1').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_3_1').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }
    if($scores_all_3[3] > $scores_all_3[7])
    {
      echo("document.getElementById('A_win_3_2').checked = true;");
      $home_win = ($home_win + 1);
      $check_home_win = ($check_home_win + 1);
    }
    else
    {
      echo("document.getElementById('B_win_3_2').checked = true;");
      $away_win = ($away_win + 1);
      $check_away_win = ($check_away_win + 1);
    }

  }
  echo("document.getElementById('A_wins').value = " . $home_win . ";");
  echo("document.getElementById('B_wins').value = " . $away_win . ";");

echo("}");
echo("</script>");
// end score matchups

?>
<script>
window.onload = function() 
{
  fillelementarray();
  score_matchups();
}
/*
function SaveApproval() {

  currForm = $.fn.getcurrentarray($('#type').val());
  console.log("Existing " + existingForm);
  console.log("Current " + currForm);

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
  document.capt_auth.DatePlayed.value = '<?= $_POST['DatePlayed'] ?>'; 
  document.capt_auth.TeamGrade.value = '<?= $team_grade ?>'; 
  document.capt_auth.Type.value = '<?= $type ?>'; 
  document.capt_auth.Home_Approve.value = home_ok; 
  document.capt_auth.Away_Approve.value = away_ok; 
  document.capt_auth.submit();
}
*/
function EditTeam($edit_team) 
{
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
  document.edit_scoresheet.Year.value = <?php echo($current_season); ?>; 
  document.edit_scoresheet.Grade.value = '<?php echo($_POST['Grade']); ?>'; 
  document.edit_scoresheet.FixtureDate.value = '<?php echo($_POST['DatePlayed']); ?>'; 
  document.edit_scoresheet.Season.value = '<?= $season ?>'; 
  document.edit_scoresheet.RoundNo.value = <?= $round ?>; 
  document.edit_scoresheet.TeamGrade.value = '<?= $team_grade ?>'; 
  document.edit_scoresheet.TeamScoring.value = '<?php echo($_POST['TeamScoring']); ?>'; 
  document.edit_scoresheet.Type.value = '<?= $type ?>'; 
  document.edit_scoresheet.DatePlayed.value = '<?= $_POST['DatePlayed'] ?>'; 
  document.edit_scoresheet.TeamtoEdit.value = $team; 
  document.edit_scoresheet.OppositiontoEdit.value = $opposition; 
  document.edit_scoresheet.action = 'scoresheet.php';
  document.edit_scoresheet.submit();
}

</script>
<script>

$(document).ready(function()
{
  /*
  $.fn.getexistingarray = function (type) {
    var home_team = '<?= $session_home ?>';
    var away_team = '<?= $session_away ?>';
    var round = <?= $round ?>;
    var year = <?= $current_season ?>;
    var season = '<?= $season ?>';
    var team_grade = '<?= $team_grade ?>';
    $.ajax({
      url:"<?= $url ?>/check_approval_changes.php?home=" + home_team + "&away=" + away_team + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round,
      method: 'GET',
      success : function(data){
          var obj = jQuery.parseJSON(data);
          //alert(obj);
          console.log("Existing fn " + obj);
          console.log("Current fn " + $.fn.getcurrentarray(type));
          if($.fn.getcurrentarray(type) == obj)
          {
            return true;
          }
          //return obj;
      },
      error: function (request, error) {
          alert("Error, cannot get data"); 
      }
    });
  }
*/
  $.fn.getcurrentarray = function (type) {
    var new_array = new Array;
    new_array = '';
    // home team
    for (j = 0; j < 4; j++) 
    {
      if(type == 'Snooker')
      {
        new_array +=  $('#A_player_' + j).html() + "";
        for (i = 0; i < 3; i++) 
        {
          new_array +=  $('#A_score_' + j + '_' + i).val() + "" +
                        $.trim($('#A_breaks_' + j + '_' + i).val());
        }
        new_array += ",";
      }
      else if(type == 'Billiards')
      {
        new_array +=  $('#A_player_' + j).html() + "" +
                      $('#A_score_' + j).val() + "" +
                      $('#A_breaks_' + j).val() + "";
      }
    }
    if(type == "Billiards")
    {
      new_array += ",";
    }

    // away team
    for (j = 0; j < 4; j++) 
    {
      if(type == 'Snooker')
      {
        new_array +=  $('#B_player_' + j).html() + "";
        for (i = 0; i < 3; i++) 
        {
          new_array +=  $('#B_score_' + j + '_' + i).val() + "" +
                        $.trim($('#B_breaks_' + j + '_' + i).val());
        }
        new_array += ",";
      }
      else if(type == 'Billiards')
      {
        new_array +=  $('#B_player_' + j).html() + "" +
                      $('#B_score_' + j).val() + "" +
                      $('#B_breaks_' + j).val() + "";
      }
    }
    if(type == 'Snooker')
    {
       new_array = new_array.substring(0, new_array.length-1);
    }
    return new_array;
  }

  // get away team captain to enter email and password to activate checkbox
  $('#home_ok').click(function(event){
    event.preventDefault();
    var team = '<?= $session_home ?>';
    var home_away = 'home';
    //$.fn.get_approval(team, home_away);
    $.fn.getexistingarray('<?= $type ?>', team, home_away);
  });

  // get away team captain to enter email and password to activate checkbox
  $('#away_ok').click(function(event){
    event.preventDefault();
    var team = '<?= $session_away ?>';
    var home_away = 'away';
    //$.fn.get_approval(team, home_away);
    $.fn.getexistingarray('<?= $type ?>', team, home_away);
  });

/*
  $.fn.get_approval = function (team, home_away) {
    event.preventDefault();
    //alert($.fn.getexistingarray('Snooker'));
    //var existingForm = $.fn.getexistingarray('<?= $type ?>');
    //var currForm = $.fn.getcurrentarray('<?= $type ?>');
    //console.log("Existing " + existingForm);
    //console.log("Current " + currForm);
    if($.fn.getexistingarray('<?= $type ?>') == 'true')
    {
      alert("The same");
      $.ajax({
        url:"<?= $url ?>/get_approval_captain.php?Team=" + team + "&HomeAway=" + home_away,
        success : function(data){
          var obj = jQuery.parseJSON(data);
          $('#email').html("Enter Team Captain password for " + team);
          $('#email_address').val(obj);
          $('#venue').val(obj[(obj.length-1)]);
          $('#LoginModal').modal('show');
        },
        error: function (request, error) {
          alert("Error, cannot get data"); 
        }
      });
    }
    else
    {
      //alert($.fn.getexistingarray('<?= $type ?>'));
      alert("This page has changed since it was last displayed.\nThe page will be refreshed.");
      location.reload();
      return;
    }
    $.ajax({
      url:"<?= $url ?>/get_approval_captain.php?Team=" + team + "&HomeAway=" + home_away,
      success : function(data){
        var obj = jQuery.parseJSON(data);
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
*/
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
          team = '<?= $_SESSION['home'] ?>'; 
          opposition = '<?= $_SESSION['away'] ?>'; 
          $.fn.save_approval(team, opposition, response);
          $('#password').val('');
          $('#LoginModal').modal('hide');
        }
        else
        {
          alert("Incorrect Password or you are Not authorised!");
        }
      }
    });
  });

  $.fn.save_approval = function (team, opposition, venue) 
  {
    event.preventDefault();
    GamesWonHome = 0;
    GamesWonAway = 0;
    GamesDrawHome = 0;
    GamesDrawAway = 0;
    type = '<?= $type ?>'; 
    if($("input[id='home_ok']").is(':checked'))
    {
      home_ok = 1;
      GamesWonHome = $('#A_wins').val();
      GamesDrawHome = $('#A_draws').val();
    }
    else
    {
      home_ok = 0;
    }
    if($("input[id='away_ok']").is(':checked'))
    {
      away_ok = 1;
      GamesWonAway = $('#B_wins').val();
      GamesDrawAway = $('#B_draws').val();
    }
    else
    {
      away_ok = 0;
    }
    no_of_players = 4;
    no_of_games = 3;
    var scoredata = new Array;
    var scoredata_player = new Array;
    var x = 0;
    // add wins etc to scoresheet and club results
    for(var j = 0; j < no_of_players; j++)
    {
      var play_pos = (j+1);
      Awin = 0; 
      Bwin = 0; 
      Aloss = 0; 
      Bloss = 0; 
      Adraw = 0; 
      Bdraw  = 0;
      for (var i = 0; i < no_of_games; i++) 
      {
        if(type == 'Snooker')
        {
          if($('#A_win_' + j + '_' + i).is(":checked"))
          {
            Awin = 1;
          }
          else
          {
            Awin = 0;
          }
          Adraw = 0;
          Aloss = 0;
          Adraw = 0;

          if($('#B_win_' + j + '_' + i).is(":checked"))
          {
            Bwin = 1;
          }
          else
          {
            Bwin = 0;
          }
          Bdraw = 0;
          Bloss = 0;
          Bdraw = 0;
        }
        else if(type == 'Billiards')
        {
          if($('#A_win_' + j).is(":checked"))
          {
            Awin = 1;
          }
          else
          {
            Awin = 0;
          }
          if($('#A_loss_' + j).is(":checked"))
          {
            Aloss = 1;
          }
          else
          {
            Aloss = 0;
          }
          if($('#A_draw_' + j).is(":checked"))
          {
            Adraw = 1;
          }
          else
          {
            Adraw = 0;
          }
          if($('#B_win_' + j).is(":checked"))
          {
            Bwin = 1;
          }
          else
          {
            Bwin = 0;
          }
          if($('#B_loss_' + j).is(":checked"))
          {
            Bloss = 1;
          }
          else
          {
            Bloss = 0;
          }
          if($('#B_draw_' + j).is(":checked"))
          {
            Bdraw = 1;
          }
          else
          {
            Bdraw = 0;
          }
        }
        scoredata_player[i] = 
        Awin + ", " + 
        Bwin + ", " + 
        Aloss + ", " + 
        Bloss + ", " + 
        Adraw + ", " + 
        Bdraw + ", " + 
        play_pos;
        scoredata = [].concat(scoredata, scoredata_player[i]);
      }
    }
    var scoredata = JSON.stringify(scoredata);
    PackedData = scoredata;
    Round = <?= $_POST['Round'] ?>; 
    Year = <?php echo($current_season); ?>; 
    Season = '<?= $_POST['Season'] ?>'; 
    DatePlayed = '<?= $_POST['DatePlayed'] ?>'; 
    TeamGrade = '<?= $team_grade ?>'; 
    Type = '<?= $type ?>'; 
    team = '<?= $session_home ?>';
    opposition = '<?= $session_away ?>';
    $.ajax({
      url:"<?= $url ?>/save_approvals.php?Home_Approve=" + home_ok + "&Away_Approve=" + away_ok + "&Round=" + Round + "&Year=" + Year + "&Season=" + Season + "&DatePlayed=" + DatePlayed + "&TeamGrade=" + TeamGrade + "&Type=" + Type + "&Home=" + team + "&Away=" + opposition + "&GamesWonHome=" + GamesWonHome + "&GamesWonAway=" + GamesWonAway + "&GamesDrawHome=" + GamesDrawHome + "&GamesDrawAway=" + GamesDrawAway + "&Venue=" + venue+ "&PackedData=" + PackedData,
      method: 'POST',
      success:function(response)
      {
        $.ajax({
          url:"<?= $url ?>/get_approval_status.php?Round=" + Round + "&Year=" + Year + "&Season=" + Season + "&DatePlayed=" + DatePlayed + "&TeamGrade=" + TeamGrade + "&Type=" + Type + "&Home=" + team + "&Away=" + opposition,
          method: 'POST',
          success:function(approval)
          {
            approval_obj = jQuery.parseJSON(approval);
            alert(response);
            if((approval_obj[0] == 1) && (approval_obj[1] == 1))
            {
              $('#remove_home_approve').hide();
              $('#remove_away_approve').hide();
            }
          }
        });
      }
    });
  }

  $('#cancel').click(function(event){
    event.preventDefault();
    $("input[id='home_ok']").prop("checked", false);
    $("input[id='away_ok']").prop("checked", false);
    $('#password').val('');
    $('#LoginModal').modal('hide');
  });

});
</script>
<center>
<form name="capt_auth" method="post" action="captain_approval.php">
<input type="hidden" name="Round" value="" />
<input type="hidden" name="Year" value="" />
<input type="hidden" name="Season" value="" />
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
    document.getElementById("A_win_" + i + "_" + j).checked = false;
  }
  else if(document.getElementById("A_win_" + i + "_" + j).checked === false) 
  {
    document.getElementById("A_win_" + i + "_" + j).checked = true;
  }
  if (document.getElementById("B_win_" + i + "_" + j).checked === true) 
  {
    document.getElementById("B_win_" + i + "_" + j).checked = false;
  }
  else if(document.getElementById("B_win_" + i + "_" + j).checked === false) 
  {
    document.getElementById("B_win_" + i + "_" + j).checked = true;
  }
}

</script>

<table class='table table-striped table-bordered dt-responsive nowrap display'>
<?php
if($type == 'Snooker')
{
  echo("<tr>");
  echo("<td colspan=3 align=center>" . $session_home . "</td>");
  echo("<td colspan=3 align=center>" . $session_away . "</td>");
  echo("</tr>");
  echo("<tr>");
  echo("<td align='center'>Player</td>"); 
  echo("<td align='center'>Win</td>"); 
  echo("<td align='center'>Scrs/Brks</td>"); 
  echo("<td align='center'>Player</td>"); 
  echo("<td align='center'>Win</td>");
  echo("<td align='center'>Scrs/Brks</td>"); 
  echo("</tr>"); 
  for($i = 0; $i < $no_of_players; $i++)
  {
    echo("<tr>");
    echo("<td class='A_Team' align=center>");
    echo("<div id='A_player_" . $i . "'></div>");
    echo("</td>");
    echo("<td id='A_Team_" . $i . "' align=center>");
    for($j = 0; $j < $no_of_games; $j++) // no of games
    { 
      echo("<input type='checkbox' id='A_win_" . $i . "_" . $j . "' OnChange='disableCB(" . $i . ", " . $j . ");'><br>");
    }
    echo("</td>");
    echo("<td class='A_Team' align=center>");
    for($j = 0; $j < $no_of_games; $j++) // no of games
    { 
      echo("<input type='text' id='A_score_" . $i . "_" . $j . "' style='width:30px; height:20px' readonly>");
      echo("<input type='text' id='A_breaks_" . $i . "_" . $j . "' style='width:30px; height:20px' readonly><br>");
    }
    echo("</td>");
    echo("<td align=center>");
    echo("<div id='B_player_" . $i . "'></div>");
    echo("</td>");
    echo("<td id='B_Team_" . $i . "' align=center>");
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
  }
}
elseif($type == 'Billiards')
{
  echo("<tr>");
  echo("<td colspan=3 align='center'>" . $session_home . "</td>");
  echo("<td colspan=3 align='center'>" . $session_away . "</td>");
  echo("</tr>");
  echo("<tr>");
  echo("<td align='center'>Player</td>"); 
  echo("<td align='center'>Result</td>"); 
  echo("<td align='center'>Score<br>Breaks</td>"); 
  echo("<td align='center'>Player</td>"); 
  echo("<td align='center'>Result</td>"); 
  echo("<td align='center'>Score<br>Breaks</td>"); 
  echo("</tr>"); 
  for($i = 0; $i < $no_of_players; $i++)
  {
    echo("<tr>");
    echo("<td class='A_Team_" . $i . "' align=center><div id='A_player_" . $i . "'></div></td>");
    echo("<td id='A_Team_" . $i . "' align=center>Win&nbsp;&nbsp;<input type='radio' id='A_win_" . $i . "' name='A_Result_" . $i . "' disabled><br>");
    echo("Loss&nbsp;<input type='radio' id='A_loss_" . $i . "' name='A_Result_" . $i . "' checked disabled><br>");
    echo("Draw&nbsp;<input type='radio' id='A_draw_" . $i . "' name='A_Result_" . $i . "' disabled></td>");
    echo("<td class='A_Team_" . $i . "' align=center><input type='text' id='A_score_" . $i . "' style='width:40px; 
      height:20px' readonly><br><br>");
    echo("<textarea id='A_breaks_" . $i . "' rows=6 style='width:40px' readonly></textarea></td>");
    echo("<td class='B_Team_" . $i . "' align=center><div id='B_player_" . $i . "'></div></td>");
    echo("<td id='B_Team_" . $i . "' align=center>Win&nbsp;&nbsp;<input type='radio' id='B_win_" . $i . "' name='B_Result_" . $i . "' disabled><br>");
    echo("Loss&nbsp;<input type='radio' id='B_loss_" . $i . "' name='B_Result_" . $i . "' checked disabled><br>");
    echo("Draw&nbsp;<input type='radio' id='B_draw_" . $i . "' name='B_Result_" . $i . "' disabled></td>");
    echo("<td class='B_Team_" . $i . "' align=center><input type='text' id='B_score_" . $i . "' style='width:40px; height:20px' readonly><br><br>");
    echo("<textarea id='B_breaks_" . $i . "' rows=6 style='width:40px' readonly></textarea></td>");
    echo("</tr>");
  }
}

?>
<?php
// check games won ratio
$sql_home = "Select * from tbl_club_results where club = '" . $session_home . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND year = " . $current_season;
$result_home = $dbcnx_client->query($sql_home) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$build_data_home = $result_home->fetch_assoc();

$sql_away = "Select * from tbl_club_results where club = '" . $session_away . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND year = " . $current_season;
$result_away = $dbcnx_client->query($sql_away) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$build_data_away = $result_away->fetch_assoc();

// add games won/drawn etc
if($type == 'Snooker')
{
  $max_wins = ($no_of_games*$no_of_players);
  $home_wins = $build_data_home['games_won'];
  $away_wins = $build_data_away['games_won'];
  $max_played = ($build_data_home['games_won']+$build_data_away['games_won']);
  /*if($max_wins == $max_played)
  {
    //echo("Good!<br>");
    //$style = "style='background-color:yellowgreen;color:black;'";
  }
  else
  {
    //echo("Somebody is lying!<br>");
    //$style = "style='background-color:red;color:black;'";
  }
  */
  $style = '';
  $results_span = 2;
  echo("<tr id='good_bad'>");
  echo("<input type='hidden' id='A_draws' value=0>");
  echo("<td " . $style . " class='text-right'><b>Games Won:</b>");
  echo("</td>");
  echo("<td " . $style . " class='text-center'><input type='text' id='A_wins' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . ">&nbsp;</td>");
  echo("<input type='hidden' id='B_draws' value=0>");
  echo("<td " . $style . " class='text-right'><b>Games Won:</b>");
  echo("</td>");
  echo("<td " . $style . " class='text-center'><input type='text' id='B_wins' style='width:30px; height:20px' readonly></td>");
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
  /*if(($max_played+$max_draws) == 8)
  {
    //echo("Good!<br>");
    //$style = "style='background-color:yellowgreen;color:black;'";
  }
  else
  {
    //echo("Somebody is lying!<br>");
    //$style = "style='background-color:red;color:black;'";
  }*/
  $style = '';
  $results_span = 2;
  echo("<tr id='good_bad'>");
  echo("<td " . $style . " class='text-center'>Games Drawn:&nbsp;&nbsp;");
  echo("<input type='text' id='A_draws' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . " class='text-center'>Games Won:&nbsp;&nbsp;");
  echo("<input type='text' id='A_wins' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . ">&nbsp;</td>");
  echo("<td " . $style . " class='text-center'>Games Drawn:&nbsp;&nbsp;");
  echo("<input type='text' id='B_draws' style='width:30px; height:20px' readonly></td>");
  echo("<td " . $style . " class='text-center'>Games Won:&nbsp;&nbsp;");
  echo("<input type='text' id='B_wins' style='width:30px; height:20px' readonly></td>");
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
    <td colspan=<?= $results_span ?> class='text-center'><b>Away Captain Approve:</b></td>
    <td class='text-center'><input type='checkbox' id='away_ok'></td>
  </tr>
</table>
</form>
<br>
<?php 
if(!$checkbox5 || !$checkbox6)
{
?>
<form name="edit_scoresheet" method="post" action="scoresheet.php">
<!--
  <div class='text-center' id='remove_home_approve'>
  <a class='btn btn-primary btn-xs' href="javascript:;" id='edit_home' onclick='$.fn.getexistingarray("Snooker")'style='width:300px'>Test Existing</a>
</div>
<br>
-->
<div class='text-center' id='remove_home_approve'>
  <a class='btn btn-primary btn-xs' href="javascript:;" id='edit_home' onclick="EditTeam('<?php echo($_SESSION['home']); ?>')" style='width:300px'>Edit the <?php echo($_SESSION['home']); ?> Scoresheet</a>
</div>
<br>
<div class='text-center' id='remove_away_approve'>
  <a class='btn btn-primary btn-xs' href="javascript:;" id='edit_away' onclick="EditTeam('<?php echo($_SESSION['away']); ?>')" style='width:300px'>Edit the <?php echo($_SESSION['away']); ?> Scoresheet</a>
</div>
<input type="hidden" name="Grade" id="grade" value="<?php echo($_POST['Grade']); ?>" />
<input type="hidden" name="Type" id="type" value="<?php echo($_POST['Type']); ?>" />
<input type="hidden" name="RoundNo" id="round" value="<?php echo($_POST['RoundNo']); ?>" />
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

