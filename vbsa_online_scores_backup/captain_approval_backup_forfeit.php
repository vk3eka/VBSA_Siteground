<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include ("header.php");
include ("connection.inc");
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
$title = $_POST['RoundTitle'];

// get from grade settings table
$sql_grades = "Select * From tbl_team_grade Where grade = '" . $team_grade . "'";
$result_grades = $dbcnx_client->query($sql_grades) or die("Couldn't execute settings query. " . mysqli_error($dbcnx_client));
$build_grades = $result_grades->fetch_assoc();
$no_of_games = $build_grades['games_round'];
$no_of_players = $build_grades['no_of_players'];

?>
<script type='text/javascript'>

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

  $.fn.refresh_forfeit = function (type) {
    var home_team = '<?= $session_home ?>';
    var away_team = '<?= $session_away ?>';
    var round = <?= $round ?>;
    var year = <?= $current_season ?>;
    var season = '<?= $season ?>';
    var team_grade = '<?= $team_grade ?>';

    if($('#A_player_0').html().substr(0,12) == 'Team Forfeit')
    {
      $("input[id='A_win_0_0']").prop("checked", false); 
      $("input[id='B_win_0_0']").prop("checked", true); 
      $("input[id='A_win_0_1']").prop("checked", false); 
      $("input[id='B_win_0_1']").prop("checked", true); 
      $("input[id='A_win_0_2']").prop("checked", false); 
      $("input[id='B_win_0_2']").prop("checked", false); 
      
      $("input[id='A_win_1_0']").prop("checked", false); 
      $("input[id='B_win_1_0']").prop("checked", true); 
      $("input[id='A_win_1_1']").prop("checked", false); 
      $("input[id='B_win_1_1']").prop("checked", true); 
      $("input[id='A_win_1_2']").prop("checked", false); 
      $("input[id='B_win_1_2']").prop("checked", false);

      $("input[id='A_win_2_0']").prop("checked", false); 
      $("input[id='B_win_2_0']").prop("checked", true); 
      $("input[id='A_win_2_1']").prop("checked", false); 
      $("input[id='B_win_2_1']").prop("checked", true); 
      $("input[id='A_win_2_2']").prop("checked", false); 
      $("input[id='B_win_2_2']").prop("checked", false); 

      $("input[id='A_win_3_0']").prop("checked", false); 
      $("input[id='B_win_3_0']").prop("checked", true); 
      $("input[id='A_win_3_1']").prop("checked", false); 
      $("input[id='B_win_3_1']").prop("checked", true); 
      $("input[id='A_win_3_2']").prop("checked", false); 
      $("input[id='B_win_3_2']").prop("checked", false); 
      if($('#type').val() == "Snooker")
      {
        $("input[id='A_wins']").val(-4); 
        $("input[id='B_wins']").val(8); 
      }
      else if($('#type').val() == "Billiards")
      {
        $("input[id='A_wins']").val(-4); 
        $("input[id='B_wins']").val(6); 
      }
    }

    if($('#B_player_0').html().substr(0,12) == 'Team Forfeit')
    {
      $("input[id='A_win_0_0']").prop("checked", true); 
      $("input[id='B_win_0_0']").prop("checked", false); 
      $("input[id='A_win_0_1']").prop("checked", true); 
      $("input[id='B_win_0_1']").prop("checked", false); 
      $("input[id='A_win_0_2']").prop("checked", false); 
      $("input[id='B_win_0_2']").prop("checked", false); 
      
      $("input[id='A_win_1_0']").prop("checked", true); 
      $("input[id='B_win_1_0']").prop("checked", false); 
      $("input[id='A_win_1_1']").prop("checked", true); 
      $("input[id='B_win_1_1']").prop("checked", false); 
      $("input[id='A_win_1_2']").prop("checked", false); 
      $("input[id='B_win_1_2']").prop("checked", false);

      $("input[id='A_win_2_0']").prop("checked", true); 
      $("input[id='B_win_2_0']").prop("checked", false); 
      $("input[id='A_win_2_1']").prop("checked", true); 
      $("input[id='B_win_2_1']").prop("checked", false); 
      $("input[id='A_win_2_2']").prop("checked", false); 
      $("input[id='B_win_2_2']").prop("checked", false); 

      $("input[id='A_win_3_0']").prop("checked", true); 
      $("input[id='B_win_3_0']").prop("checked", false); 
      $("input[id='A_win_3_1']").prop("checked", true); 
      $("input[id='B_win_3_1']").prop("checked", false); 
      $("input[id='A_win_3_2']").prop("checked", false); 
      $("input[id='B_win_3_2']").prop("checked", false); 
      if($('#type').val() == "Snooker")
      {
        $("input[id='A_wins']").val(8); 
        $("input[id='B_wins']").val(-4); 
      }
      else if($('#type').val() == "Billiards")
      {
        $("input[id='A_wins']").val(6); 
        $("input[id='B_wins']").val(-4); 
      }
    }
  }

  $.fn.getexistingarray = function (type, team, home_away) {
    var home_team = '<?= $session_home ?>';
    var away_team = '<?= $session_away ?>';
    var round = <?= $round ?>;
    var year = <?= $current_season ?>;
    var season = '<?= $season ?>';
    var team_grade = '<?= $team_grade ?>';
    var title = '<?= $title ?>';
    //alert('Get Existing');
    $.ajax({
      url:"<?= $url ?>/check_approval_changes.php?home=" + home_team + "&away=" + away_team + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round + "&title=" + title,
      method: 'GET',
      success : function(data){
        var obj = jQuery.parseJSON(data);
        //alert('Check Approval');
        currForm = $.fn.getcurrentarray(type);
        if(($('#A_player_0').html() == 'Team Forfeit') || ($('#B_player_0').html() == 'Team Forfeit'))
        {
          currForm = obj;
        }
        else
        {
          currForm = $.fn.getcurrentarray(type);
        }
        console.log("Existing (Obj) fn " + obj);
        console.log("Current (currForm) fn " + currForm);
        if($.trim(currForm) == $.trim(obj))
        {
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
          alert("This page has changed since it was last displayed.\nThe page will be refreshed.");
          $.fn.pagerefresh($('#type').val());
          $.fn.getscorematchups($('#type').val());
          return;
        }
      },
      error: function (request, error) {
          alert("Error, cannot get data"); 
      }
    });
  }

  $.fn.getcurrentarray = function (type) {
    var new_array = new Array;
    var title = '<?= $title ?>';
    new_array = '';
    // check if any teams have been approved
    Round = <?= $_POST['Round'] ?>; 
    RoundTitle = '<?= $_POST['RoundTitle'] ?>'; 
    Year = <?php echo($current_season); ?>; 
    Season = '<?= $_POST['Season'] ?>'; 
    DatePlayed = '<?= $_POST['DatePlayed'] ?>'; 
    TeamGrade = '<?= $team_grade ?>'; 
    Type = '<?= $type ?>'; 
    team = '<?= $session_home ?>';
    opposition = '<?= $session_away ?>';
    $.ajax({
      url:"<?= $url ?>/get_approval_status.php?Round=" + Round + "&Year=" + Year + "&Season=" + Season + "&DatePlayed=" + DatePlayed + "&TeamGrade=" + TeamGrade + "&Type=" + Type + "&Home=" + team + "&Away=" + opposition + "&RoundTitle=" + RoundTitle,
      method: 'POST',
      success:function(approval)
      {
        approval_obj = jQuery.parseJSON(approval);
        console.log("Approval Status " + approval_obj);
        if(approval_obj[0] == 1)
        {
          //home_ok = 1;
          $("input[id='home_ok']").prop("checked", true); 
        }
        if(approval_obj[2] == 1)
        {
          //away_ok = 1;
          $("input[id='away_ok']").prop("checked", true); 
        }
      }
    });
     
    if($("input[id='home_ok']").is(':checked'))
    {
      home_ok = 1;
      $("input[id='home_ok']").prop("checked", true);
    }
    else
    {
      home_ok = 0;
      $("input[id='home_ok']").prop("checked", false);
    }
    if($("input[id='away_ok']").is(':checked'))
    {
      away_ok = 1;
      $("input[id='away_ok']").prop("checked", true);
    }
    else
    {
      away_ok = 0;
      $("input[id='away_ok']").prop("checked", false);
    }
    
    // home team
    for (j = 0; j < 4; j++) 
    {
    //j = 0;
      if((j == 0) && (type == 'Snooker') && ((title == 'Semi Final') || (title == 'Grand Final')))
      {
        //new_array +=  $('#A_player_' + j).html() + "" + home_ok + "";
        new_array +=  $('#A_player_' + j).html() + "";
        for (i = 0; i < 4; i++) 
        {
          new_array +=  $.trim($('#A_score_' + j + '_' + i).val()) + "" +
                        $.trim($('#A_breaks_' + j + '_' + i).val());
        }
        new_array += ",";
        //console.log("Array 0 " + new_array);
      }
      else if(type == 'Snooker')
      //if(type == 'Snooker')
      {
        //new_array +=  $('#A_player_' + j).html() + "" + home_ok + "";
        new_array +=  $('#A_player_' + j).html() + "";
        for (i = 0; i < 3; i++) 
        {
          new_array +=  $('#A_score_' + j + '_' + i).val() + "" +
                        $.trim($('#A_breaks_' + j + '_' + i).val());
        }
        new_array += ",";
        //console.log("Array " + new_array);
      }
      else if(type == 'Billiards')
      {
        if(($('#A_player_' + j).html().substr(0,12) == 'Team Forfeit') && (j == 0))
        {
          new_array +=  $('#A_player_' + j).html() + "" +
                      $('#A_tier_' + j).val() + "" +
                      $('#A_stick_' + j).val() + "" +
                      $('#A_breaks_' + j).val();
          new_array += ",";
        }
        else if(($('#A_player_0').html().substr(0,12) != 'Team Forfeit'))
        {
        //new_array +=  $('#A_player_' + j).html() + "" + home_ok + "" +
        new_array +=  $('#A_player_' + j).html() + "" +
                      $('#A_tier_' + j).val() + "" +
                      $('#A_stick_' + j).val() + "" +
                      $('#A_breaks_' + j).val() + ",";
        }
      }
      if(type == 'Billiards')
      {
        //new_array += ",";
      }
    }
    
    // away team
    for (j = 0; j < 4; j++) 
    {
      if((j == 0) && (type == 'Snooker') && ((title == 'Semi Final') || (title == 'Grand Final')))
      {
        //new_array +=  $('#B_player_' + j).html() + "" + away_ok + "";
        new_array +=  $('#B_player_' + j).html() + "";
        for (i = 0; i < 4; i++) 
        {
          new_array +=  $('#B_score_' + j + '_' + i).val() + "" +
                        $.trim($('#B_breaks_' + j + '_' + i).val());
        }
        new_array += ",";
      }
      else if(type == 'Snooker')
      {
        //new_array +=  $('#B_player_' + j).html() + "" + away_ok + "";
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
        if(($('#B_player_' + j).html().substr(0,12) == 'Team Forfeit') && (j == 0))
        {
          new_array +=  $('#B_player_' + j).html() + "" +
                      $('#B_tier_' + j).val() + "" +
                      $('#B_stick_' + j).val() + "" +
                      $('#B_breaks_' + j).val();
          new_array += ",";
          //console.log("Away array " + new_array);
        }
        else if(($('#B_player_0').html().substr(0,12) != 'Team Forfeit'))
        {
          new_array +=  $('#B_player_' + j).html() + "" +
                        $('#B_tier_' + j).val() + "" +
                        $('#B_stick_' + j).val() + "" +
                        $('#B_breaks_' + j).val() + ",";
        }
      }
    }
    new_array = new_array.substring(0, new_array.length-1);
    return new_array;
  }

  $.fn.getscorematchups = function (type) {
    //alert("Matchups");
    var home = '<?= $session_home ?>';
    var away = '<?= $session_away ?>';
    var round = <?= $round ?>;
    var year = <?= $current_season ?>;
    var season = '<?= $season ?>';
    var team_grade = '<?= $team_grade ?>';
    var title = '<?= $title ?>';
    $.ajax({
      url:"<?= $url ?>/get_player_matchups.php?home=" + home + "&away=" + away + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round + "&type=" + type,
      method: 'GET',
      success : function(data){
        //console.log("Score Matchups (data) " + data);
        var obj = jQuery.parseJSON(data);
        obj_approval = obj[5].split(",")
        for(y = 0; y < obj_approval.length; y++)
        {
          //console.log("Score Approval " + obj_approval[y]);
          //console.log("Score Approval boxes " + obj_approval[1] + ", " + obj_approval[3]);
          if((obj_approval[1] == 1) && (obj_approval[3] == 1))
          {
            $('#remove_home_approve').hide();
            $('#remove_away_approve').hide();
          }
          else
          {
            $('#remove_home_approve').show();
            $('#remove_away_approve').show();
          }
        }
        
        if(type == 'Snooker')
        {
          $("input[id='A_win_0_0']").prop("checked", false); 
          $("input[id='A_win_0_1']").prop("checked", false); 
          $("input[id='A_win_0_2']").prop("checked", false); 
          $("input[id='A_win_1_0']").prop("checked", false); 
          $("input[id='A_win_1_1']").prop("checked", false); 
          $("input[id='A_win_1_2']").prop("checked", false); 
          $("input[id='A_win_2_0']").prop("checked", false); 
          $("input[id='A_win_2_1']").prop("checked", false); 
          $("input[id='A_win_2_2']").prop("checked", false); 
          $("input[id='A_win_3_0']").prop("checked", false); 
          $("input[id='A_win_3_1']").prop("checked", false); 
          $("input[id='A_win_3_2']").prop("checked", false); 
          $("input[id='B_win_0_0']").prop("checked", false); 
          $("input[id='B_win_0_1']").prop("checked", false); 
          $("input[id='B_win_0_2']").prop("checked", false); 
          $("input[id='B_win_1_0']").prop("checked", false); 
          $("input[id='B_win_1_1']").prop("checked", false); 
          $("input[id='B_win_1_2']").prop("checked", false); 
          $("input[id='B_win_2_0']").prop("checked", false); 
          $("input[id='B_win_2_1']").prop("checked", false); 
          $("input[id='B_win_2_2']").prop("checked", false); 
          $("input[id='B_win_3_0']").prop("checked", false); 
          $("input[id='B_win_3_1']").prop("checked", false); 
          $("input[id='B_win_3_2']").prop("checked", false); 
          if((title == 'Semi Final') || (title == 'Grand Final'))
          {  
            $("input[id='A_win_0_3']").prop("checked", false); 
            $("input[id='B_win_0_3']").prop("checked", false); 
          }
          for(x = 0; x < 5; x++) // 4 matches and 1 results = 5
          {
            obj_test = obj[x].split(",");
            //console.log("Matches " + x + ", " + obj_test);
            for(i = 0; i < obj_test.length; i++)
            {
              // 0 
              if($.trim(obj_test[0]) == 'A_win_0_0')
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_win_0_0']").prop("checked", true); 
                  $("input[id='B_win_0_0']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[2]) == 'A_win_0_1')
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='A_win_0_1']").prop("checked", true); 
                  $("input[id='B_win_0_1']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[4]) == 'A_win_0_2')
              {
                if(obj_test[5] == 1)
                {
                  $("input[id='A_win_0_2']").prop("checked", true); 
                  $("input[id='B_win_0_2']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[0]) == 'B_win_0_0')
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_win_0_0']").prop("checked", false); 
                  $("input[id='B_win_0_0']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[2]) == 'B_win_0_1')
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='A_win_0_1']").prop("checked", false); 
                  $("input[id='B_win_0_1']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[4]) == 'B_win_0_2')
              {
                if(obj_test[5] == 1)
                {
                  $("input[id='A_win_0_2']").prop("checked", false); 
                  $("input[id='B_win_0_2']").prop("checked", true); 
                }
              }
              
              // 1
              if($.trim(obj_test[0]) == 'A_win_1_0')
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_win_1_0']").prop("checked", true); 
                  $("input[id='B_win_1_0']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[2]) == 'A_win_1_1')
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='A_win_1_1']").prop("checked", true); 
                  $("input[id='B_win_1_1']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[4]) == 'A_win_1_2')
              {
                if(obj_test[5] == 1)
                {
                  $("input[id='A_win_1_2']").prop("checked", true); 
                  $("input[id='B_win_1_2']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[0]) == 'B_win_1_0')
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_win_1_0']").prop("checked", false); 
                  $("input[id='B_win_1_0']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[2]) == 'B_win_1_1')
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='A_win_1_1']").prop("checked", false); 
                  $("input[id='B_win_1_1']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[4]) == 'B_win_1_2')
              {
                if(obj_test[5] == 1)
                {
                  $("input[id='A_win_1_2']").prop("checked", false); 
                  $("input[id='B_win_1_2']").prop("checked", true); 
                }
              }

              // 2
              if($.trim(obj_test[0]) == 'A_win_2_0')
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_win_2_0']").prop("checked", true); 
                  $("input[id='B_win_2_0']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[2]) == 'A_win_2_1')
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='A_win_2_1']").prop("checked", true); 
                  $("input[id='B_win_2_1']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[4]) == 'A_win_2_2')
              {
                if(obj_test[5] == 1)
                {
                  $("input[id='A_win_2_2']").prop("checked", true); 
                  $("input[id='B_win_2_2']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[0]) == 'B_win_2_0')
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_win_2_0']").prop("checked", false); 
                  $("input[id='B_win_2_0']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[2]) == 'B_win_2_1')
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='A_win_2_1']").prop("checked", false); 
                  $("input[id='B_win_2_1']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[4]) == 'B_win_2_2')
              {
                if(obj_test[5] == 1)
                {
                  $("input[id='A_win_2_2']").prop("checked", false); 
                  $("input[id='B_win_2_2']").prop("checked", true); 
                }
              }

              // 3
              if($.trim(obj_test[0]) == 'A_win_3_0')
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_win_3_0']").prop("checked", true); 
                  $("input[id='B_win_3_0']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[2]) == 'A_win_3_1')
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='A_win_3_1']").prop("checked", true); 
                  $("input[id='B_win_3_1']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[4]) == 'A_win_3_2')
              {
                if(obj_test[5] == 1)
                {
                  $("input[id='A_win_3_2']").prop("checked", true); 
                  $("input[id='B_win_3_2']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[0]) == 'B_win_3_0')
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_win_3_0']").prop("checked", false); 
                  $("input[id='B_win_3_0']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[2]) == 'B_win_3_1')
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='A_win_3_1']").prop("checked", false); 
                  $("input[id='B_win_3_1']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[4]) == 'B_win_3_2')
              {
                if(obj_test[5] == 1)
                {
                  $("input[id='A_win_3_2']").prop("checked", false); 
                  $("input[id='B_win_3_2']").prop("checked", true); 
                }
              }

              // finals
              if($.trim(obj_test[6]) == 'A_win_0_3')
              {
                if(obj_test[7] == 1)
                {
                  $("input[id='A_win_0_3']").prop("checked", true); 
                  $("input[id='B_win_0_3']").prop("checked", false); 
                }
              }
              if($.trim(obj_test[6]) == 'B_win_0_3')
              {
                if(obj_test[7] == 1)
                {
                  $("input[id='A_win_0_3']").prop("checked", false); 
                  $("input[id='B_win_0_3']").prop("checked", true); 
                }
              }
            }
          }
        }
        else if(type == 'Billiards')
        {
          $("input[id='A_loss_0']").prop("checked", true); 
          $("input[id='A_loss_1']").prop("checked", true); 
          $("input[id='A_loss_2']").prop("checked", true); 
          $("input[id='A_loss_3']").prop("checked", true); 

          $("input[id='B_loss_0']").prop("checked", true); 
          $("input[id='B_loss_1']").prop("checked", true); 
          $("input[id='B_loss_2']").prop("checked", true); 
          $("input[id='B_loss_3']").prop("checked", true); 
        }
        for(x = 0; x < 15; x++) // 4 matches and 1 results = 5
        {
          obj_test = obj[x].split(",");
          console.log("Matches " + x + ", " + obj_test);

          if($('#A_player_0').html().substr(0,12) == 'Team Forfeit')
          {
            $("input[id='A_loss_0']").prop("checked", true); 
            $("input[id='A_loss_1']").prop("checked", true); 
            $("input[id='A_loss_2']").prop("checked", true); 
            $("input[id='A_loss_3']").prop("checked", true); 

            $("input[id='B_win_0']").prop("checked", true); 
            $("input[id='B_win_1']").prop("checked", true); 
            $("input[id='B_win_2']").prop("checked", true); 
            $("input[id='B_win_3']").prop("checked", true); 
          }
          else if($('#B_player_0').html().substr(0,12) == 'Team Forfeit')
          {
            $("input[id='B_loss_0']").prop("checked", true); 
            $("input[id='B_loss_1']").prop("checked", true); 
            $("input[id='B_loss_2']").prop("checked", true); 
            $("input[id='B_loss_3']").prop("checked", true); 

            $("input[id='A_win_0']").prop("checked", true); 
            $("input[id='A_win_1']").prop("checked", true); 
            $("input[id='A_win_2']").prop("checked", true); 
            $("input[id='A_win_3']").prop("checked", true); 
          }
          else
          {
            console.log("Obj test " + obj_test);
            for(i = 0; i < obj_test.length; i++)
            {
              if($.trim(obj_test[0]) == 'A_win_' + i)
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_win_" + i + "']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[0]) == 'A_draw_' + i)
              {
                if(obj_test[1] == 1)
                {
                  $("input[id='A_draw_" + i + "']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[2]) == 'B_win_' + i)
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='B_win_" + i + "']").prop("checked", true); 
                }
              }
              if($.trim(obj_test[2]) == 'B_draw_' + i)
              {
                if(obj_test[3] == 1)
                {
                  $("input[id='B_draw_" + i + "']").prop("checked", true); 
                }
              }

              /*
              alert("Matchups 1 " + obj_test[5] +  " , " + obj_test[5]);
              if((obj_test[5] == 1) && (obj_test[6] == 1))
              {
                //alert("Matchups 2");
                $('#remove_home_approve').hide();
                $('#remove_away_approve').hide();
              }
              */
              
  

            }
            // get calculated score
            $("#A_score_0").val(obj[6]);
            $("#B_score_0").val(obj[7]);
            $("#A_score_1").val(obj[8]);
            $("#B_score_1").val(obj[9]);
            $("#A_score_2").val(obj[10]);
            $("#B_score_2").val(obj[11]);
            $("#A_score_3").val(obj[12]);
            $("#B_score_3").val(obj[13]);

            // get stick score
            $("#A_stick_0").val(obj[14]);
            $("#A_stick_1").val(obj[15]);
            $("#A_stick_2").val(obj[16]);
            $("#A_stick_3").val(obj[17]);
            $("#B_stick_0").val(obj[18]);
            $("#B_stick_1").val(obj[19]);
            $("#B_stick_2").val(obj[20]);
            $("#B_stick_3").val(obj[21]);
          }
          if(x == 4)
          {
            if(type == 'Snooker')
            {
                $('#A_wins').val($.trim(obj_test[1]));
                $('#B_wins').val($.trim(obj_test[3]));
            }
            else if(type == 'Billiards')
            {
                $('#A_wins').val($.trim(obj_test[1]));
                $('#B_wins').val($.trim(obj_test[5]));
                $('#A_draws').val($.trim(obj_test[3]));
                $('#B_draws').val($.trim(obj_test[7]));
            }
          }
        }
        
        // added for the first to 7 game wins
        if((title == 'Semi Final') || (title == 'Grand Final'))
        {  
          obj_test = obj[3].split(",");
        }

        obj_approve = obj[5].split(",")
        console.log("Approve Home/Away (obj) " + obj);
        console.log("Approve Home/Away " + obj_approve);
        console.log("Home/Away " + obj_approve[1] + ", " + obj_approve[3]);
        if(obj_approve[1] == 1)
        {
        $("input[id='home_ok']").prop("checked", true); 
        }
        else
        {
          $("input[id='home_ok']").prop("checked", false); 
        }
        if(obj_approve[3] == 1)
        {
          $("input[id='away_ok']").prop("checked", true); 
        }
        else
        {
          $("input[id='away_ok']").prop("checked", false); 
        }
        
        if(($('#A_player_0').html().substr(0,12) == 'Team Forfeit') || $('#B_player_0').html().substr(0,12) == 'Team Forfeit')
        {
          $.fn.refresh_forfeit($('#type').val());
        }

        if((obj_approve[1] == 1) && (obj_approve[3] == 1))
        {
          $('#remove_home_approve').hide();
          $('#remove_away_approve').hide();
        }
      },
      error: function (request, error) {
          alert("Error, cannot get data"); 
      }
    });
  }

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
    // add tier
    if($type == 'Billiards')
    {
      echo("document.getElementById('A_player_" . $j . "').innerHTML = '" . addslashes($build_data['players_name']) . "<br>(" .  addslashes($build_data['tier']) . ")';");
    }
    else
    {
      echo("document.getElementById('A_player_" . $j . "').innerHTML = '" . addslashes($build_data['players_name']) . "';");
    }
    if($build_data['capt_home'] == 1)
    {
      echo("document.getElementById('home_ok').checked = true;");
    }
    else
    {
      echo("document.getElementById('home_ok').checked = false;");
    }

    if(($type == 'Snooker') && ($j == 0) && (($title == 'Semi Final') || ($title == 'Grand Final')))
    {   
      for($i = 0; $i < ($no_of_games+1); $i++)
      {
        echo("document.getElementById('A_score_" . $j . "_" . $i . "').value = '" . $build_data['score_' . ($i+1)] . "';");
        echo("document.getElementById('A_breaks_" . $j . "_" . $i . "').value = '" . $build_data['break_' . ($i+1)] . "';");
        $row_arr = explode(" ", trim($build_data['break_' . ($i+1)]));
        $row_size = sizeof($row_arr);
        echo("document.getElementById('A_breaks_" . $j . "_" . $i . "').style='width:" . ($row_size*30) . "px; height:20px';");
      }
    }
    elseif($type == 'Snooker') 
    {   
      for($i = 0; $i < $no_of_games; $i++)
      {
        echo("document.getElementById('A_score_" . $j . "_" . $i . "').value = '" . intval($build_data['score_' . ($i+1)]) . "';");
        echo("document.getElementById('A_breaks_" . $j . "_" . $i . "').value = '" . $build_data['break_' . ($i+1)] . "';");
        $row_arr = explode(" ", trim($build_data['break_' . ($i+1)]));
        $row_size = sizeof($row_arr);
        echo("document.getElementById('A_breaks_" . $j . "_" . $i . "').style='width:" . ($row_size*30) . "px; height:20px';");
      }
    }
    elseif($type == 'Billiards')
    {
      echo("document.getElementById('A_stick_" . $j . "').value = '" . $build_data['billiard_stick'] . "';");
      echo("document.getElementById('A_breaks_" . $j . "').value = '" . $build_data['break_1'] . "';");
      $row_arr = explode(" ", $build_data['break_1']);
      $row_size = sizeof($row_arr);
      echo("document.getElementById('A_breaks_" . $j . "').rows = " . $row_size . ";");
    }
    if($build_data['capt_home'] == 1)
    {
      $disable_home_cb = 'checked';
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
    if($build_data_op['capt_home'] == 1)
    {
      $checkbox6 = ' checked';
    }
    else
    {
      $checkbox6 = '';
    }
    // add tier
    if($type == 'Billiards')
    {
      echo("document.getElementById('B_player_" . $j . "').innerHTML = '" . addslashes($build_data_op['players_name']) . "<br>(" .  addslashes($build_data_op['tier']) . ")';");
    }
    else
    {
      echo("document.getElementById('B_player_" . $j . "').innerHTML = '" . addslashes($build_data_op['players_name']) . "';");
    }
    if($build_data_op['capt_home'] == 1)
    {
      echo("document.getElementById('away_ok').checked = true;");
    }
    else
    {
      echo("document.getElementById('away_ok').checked = false;");
    }
    if(($type == 'Snooker') && ($j == 0) && (($title == 'Semi Final') || ($title == 'Grand Final')))
    {   
      for($i = 0; $i < ($no_of_games+1); $i++)
      {
        echo("document.getElementById('B_score_" . $j . "_" . $i . "').value = '" . $build_data_op['score_' . ($i+1)] . "';");
        echo("document.getElementById('B_breaks_" . $j . "_" . $i . "').value = '" . $build_data_op['break_' . ($i+1)] . "';");
        $row_arr = explode(" ", trim($build_data_op['break_' . ($i+1)]));
        $row_size = sizeof($row_arr);
        echo("document.getElementById('B_breaks_" . $j . "_" . $i . "').style='width:" . ($row_size*30) . "px; height:20px';");
      }
    }
    elseif($type == 'Snooker')
    {   
      for($i = 0; $i < $no_of_games; $i++)
      {
        echo("document.getElementById('B_score_" . $j . "_" . $i . "').value = '" . intval($build_data_op['score_' . ($i+1)]) . "';");
        echo("document.getElementById('B_breaks_" . $j . "_" . $i . "').value = '" . $build_data_op['break_' . ($i+1)] . "';");
        $row_arr = explode(" ", trim($build_data_op['break_' . ($i+1)]));
        $row_size = sizeof($row_arr);
        echo("document.getElementById('B_breaks_" . $j . "_" . $i . "').style='width:" . ($row_size*30) . "px; height:20px';");
      }
    }
    elseif($type == 'Billiards')
    {
      echo("document.getElementById('B_stick_" . $j . "').value = '" . $build_data_op['billiard_stick'] . "';");
      echo("document.getElementById('B_breaks_" . $j . "').value = '" . $build_data_op['break_1'] . "';");
      $row_arr = explode(" ", $build_data_op['break_1']);
      $row_size = sizeof($row_arr);
      echo("document.getElementById('B_breaks_" . $j . "').rows = " . $row_size . ";");
    }
    if($build_data_op['capt_home'] == 1)
    {
      $disable_away_cb = 'checked';
    }
    $j++;
  }

// disable approval buttons when already saved.
if($login != 'Administrator')
{
  if(($disable_home_cb == 'checked') && ($disable_away_cb == 'checked'))
  {
    echo("document.getElementById('home_ok').disabled = true;");
    echo("document.getElementById('away_ok').disabled = true;");
  }
}
echo("}");
//echo("console.log('fillelementarray');");
echo("</script>");
// end score matchups

?>
<script>
window.onload = function() 
{
  fillelementarray();
  $.fn.getscorematchups($('#type').val());
  
  /*
  if(($("input[id='home_ok']").prop("checked", true)) && ($("input[id='away_ok']").prop("checked", true)))
  {
    //alert("Here");
    //$("input[id='home_ok']").attr("disabled", true);
    //$("input[id='away_ok']").attr("disabled", true);
    $('#remove_home_approve').hide();
    $('#remove_away_approve').hide();
  }
  */
  if(($('#A_player_0').html().substr(0,12) == 'Team Forfeit') || $('#B_player_0').html().substr(0,12) == 'Team Forfeit')
  {
    $.fn.refresh_forfeit($('#type').val());
  }
}

</script>
<script>

$(document).ready(function()
{
  $.fn.pagerefresh = function (type) {
    var year = <?php echo $_SESSION['year']; ?>;
    var season = '<?php echo $_SESSION['season']; ?>';
    var team = '<?= $session_home ?>';
    var opposition = '<?= $session_away ?>';
    var round = <?= $round ?>;
    var team_grade = '<?= $team_grade ?>';
    var type = $('#type').val();
    var title = '<?= $title ?>';
    var new_array = new Array;
    new_array = '';
    $.ajax({
      url:"<?= $url ?>/get_approval_scores.php?home=" + team + "&away=" + opposition + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round,
      success : function(data){
        var obj = jQuery.parseJSON(data);
        console.log("Obj " + obj);

        // home team
        for (j = 0; j < 4; j++) 
        {
          obj_test = obj[j].split(",");
          //console.log("Obj_test " + obj_test);
          if(obj_test[1] == 1)
          {
            $("input[id='home_ok']").prop("checked", true); 
          }
          else
          {
            $("input[id='home_ok']").prop("checked", false); 
          }
          if((type == 'Snooker') && ((title == 'Semi Final') || (title == 'Grand Final')))
          {
            new_array +=  $('#A_player_' + j).val(obj_test[0]) + "";
            for (i = 0; i < 4; i++) 
            {
              new_array +=  $('#A_score_' + j + '_' + i).val($.trim(obj_test[i+2])) + "" +
                            $('#A_breaks_' + j + '_' + i).val($.trim(obj_test[i+6]));
            }
            new_array += ",";
          }
          else if(type == 'Snooker')
          {
            new_array +=  $('#A_player_' + j).val(obj_test[0]) + "";
            for (i = 0; i < 3; i++) 
            {
              new_array +=  $('#A_score_' + j + '_' + i).val($.trim(obj_test[i+1])) + "" +
                            $('#A_breaks_' + j + '_' + i).val($.trim(obj_test[i+4]));
            }
            new_array += ",";
          }
          else if(type == 'Billiards')
          {
            new_array +=  $('#A_player_' + j).val(obj_test[0]) + "" +
                          $('#A_score_' + j).val($.trim(obj_test[2])) + "" +
                          $('#A_breaks_' + j).val($.trim(obj_test[6])) + "";
            //alert(new_array);
          }
        }
        if(type == "Billiards")
        {
          new_array += ",";
        }

        // away team
        for (j = 0; j < 4; j++) 
        {
          obj_test = obj[j+4].split(",");
          if(obj_test[1] == 1)
          {
            $("input[id='away_ok']").prop("checked", true); 
          }
          else
          {
            $("input[id='away_ok']").prop("checked", false); 
          }

          if((type == 'Snooker') && ((title == 'Semi Final') || (title == 'Grand Final')))
          {
            new_array +=  $('#B_player_' + j).val(obj_test[0]) + "";
            for (i = 0; i < 4; i++) 
            {
              new_array +=  $('#B_score_' + j + '_' + i).val($.trim(obj_test[i+2])) + "" +
                            $('#B_breaks_' + j + '_' + i).val($.trim(obj_test[i+6]));
            }
            new_array += ",";
          }
          else if(type == 'Snooker')
          //if(type == 'Snooker')
          {
            new_array +=  $('#B_player_' + j).val(obj_test[0]) + "";
            for (x = 0; x < 3; x++) 
            {
              new_array +=  $('#B_score_' + j + '_' + x).val($.trim(obj_test[x+1])) + "" +
                            $('#B_breaks_' + j + '_' + x).val($.trim(obj_test[x+4]));
            }
            new_array += ",";
          }
          else if(type == 'Billiards')
          {
              new_array +=  $('#B_player_' + j).val(obj_test[0]) + "" +
                            $('#B_score_' + j).val($.trim(obj_test[2])) + "" +
                            $('#B_breaks_' + j).val($.trim(obj_test[6])) + ""; // change from 3 to prevent data already there error
          }
        }
        if(type == 'Snooker')
        {
           new_array = new_array.substring(0, new_array.length-1);
        }
        if(type == "Billiards")
        {
          new_array += ",";
        }
        return new_array;
      },
      error: function (data) {
        alert("Both Teams need to be entered!"); 
      }
    });
  }

  $('#test_reload').click(function(event){
    event.preventDefault();
    $.fn.pagerefresh('<?= $type ?>');
    $.fn.getscorematchups('<?= $type ?>');
    $.fn.refresh_forfeit($('#type').val());
  });

  // get away team captain to enter email and password to activate checkbox
  $('#home_ok').click(function(event){
    event.preventDefault();
    var team = '<?= $session_home ?>';
    var home_away = 'home';
    //alert($('#A_player_0').html());
    $.fn.getexistingarray('<?= $type ?>', team, home_away);
  });

  // get away team captain to enter email and password to activate checkbox
  $('#away_ok').click(function(event){
    event.preventDefault();
    var team = '<?= $session_away ?>';
    var home_away = 'away';
    $.fn.getexistingarray('<?= $type ?>', team, home_away);
  });

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

  $.fn.save_approval = function (team, opposition, venue, event) 
  {
    //event.preventDefault();
    var title = '<?= $title ?>';
    var home_forfeit = false;
    var away_forfeit = false;
    GamesWonHome = 0;
    GamesWonAway = 0;
    GamesDrawHome = 0;
    GamesDrawAway = 0;
    type = '<?= $type ?>'; 
    if($('#A_player_0').html().substr(0,12) == 'Team Forfeit')
    {
      var home_forfeit = true;
      //var away_forfeit = false;
    }
    else if($('#B_player_0').html().substr(0,12) == 'Team Forfeit')
    {
      //var home_forfeit = false;
      var away_forfeit = true;
    }
    //console.log("Home " + home_forfeit + ", Away " + away_forfeit);
    if($("input[id='home_ok']").is(':checked'))
    {
      home_ok = 1;
    }
    else
    {
      home_ok = 0;
    }
    if($("input[id='away_ok']").is(':checked'))
    {
      away_ok = 1;
    }
    else
    {
      away_ok = 0;
    }
    
    GamesWonHome = $('#A_wins').val();
    GamesDrawHome = $('#A_draws').val();
    GamesWonAway = $('#B_wins').val();
    GamesDrawAway = $('#B_draws').val();

    no_of_players = <?= $no_of_players ?>;
    no_of_games = <?= $no_of_games ?>;
    var scoredata = new Array;
    var scoredata_player = new Array;
    var scoredata_tier_calc = new Array;
    var scoredata_calc = new Array;
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

          scoredata_tier_calc[i] = $('#A_score_' + j).val() + "," + $('#B_score_' + j).val();
          scoredata_calc = [].concat(scoredata_calc, scoredata_tier_calc[i]);


          if(home_forfeit === true)
          {
            Awin = 0;
            Bwin = 0.75;
            Aloss = 1;
            Bloss = 0;
            Adraw = 0;
            Bdraw = 0;
          }
          else if(away_forfeit === true)
          {
            Awin = 0.75;
            Bwin = 0;
            Aloss = 0;
            Bloss = 1;
            Adraw = 0;
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

    if((type == 'Snooker') && ((title == 'Semi Final') || (title == 'Grand Final')))
    {
      if($('#A_win_0_3').is(":checked"))
      {
        Awin = 1;
      }
      else
      {
        Awin = 0;
      }
      if($('#B_win_0_3').is(":checked"))
      {
        Bwin = 1;
      }
      else
      {
        Bwin = 0;
      }
    }
    finalsdata = 
    Awin + ", " + 
    Bwin;

    forfeitdata = 
    home_forfeit + ", " + 
    away_forfeit;

    //console.log("Calc Data " + scoredata_calc);
    //console.log("Finals Data " + finalsdata);
    //console.log("Score Data " + scoredata);
    //console.log("Forfeit Data " + forfeitdata);

    var scoredata_calc = JSON.stringify(scoredata_calc);
    PackedDataCalc = scoredata_calc;

    var scoredata = JSON.stringify(scoredata);
    PackedData = scoredata;

    var finalsdata = JSON.stringify(finalsdata);
    Finals = finalsdata;

    var forfeitdata = JSON.stringify(forfeitdata);
    Forfeit = forfeitdata;

    Round = <?= $_POST['Round'] ?>; 
    RoundTitle = '<?= $_POST['RoundTitle'] ?>'; 
    Year = <?php echo($current_season); ?>; 
    Season = '<?= $_POST['Season'] ?>'; 
    DatePlayed = '<?= $_POST['DatePlayed'] ?>'; 
    TeamGrade = '<?= $team_grade ?>'; 
    Type = '<?= $type ?>'; 
    team = '<?= $session_home ?>';
    opposition = '<?= $session_away ?>';
    $.ajax({
      url:"<?= $url ?>/save_approvals.php?Home_Approve=" + home_ok + "&Away_Approve=" + away_ok + "&Round=" + Round + "&Year=" + Year + "&Season=" + Season + "&DatePlayed=" + DatePlayed + "&TeamGrade=" + TeamGrade + "&Type=" + Type + "&Home=" + team + "&Away=" + opposition + "&GamesWonHome=" + GamesWonHome + "&GamesWonAway=" + GamesWonAway + "&GamesDrawHome=" + GamesDrawHome + "&GamesDrawAway=" + GamesDrawAway + "&Venue=" + venue + "&PackedData=" + PackedData + "&PackedDataCalc=" + PackedDataCalc + "&RoundTitle=" + RoundTitle + "&Finals=" + Finals + "&Forfeit=" + Forfeit,
      method: 'POST',
      success:function(response)
      {
        $.ajax({
          url:"<?= $url ?>/get_approval_status.php?Round=" + Round + "&Year=" + Year + "&Season=" + Season + "&DatePlayed=" + DatePlayed + "&TeamGrade=" + TeamGrade + "&Type=" + Type + "&Home=" + team + "&Away=" + opposition + "&RoundTitle=" + RoundTitle,
          method: 'POST',
          success:function(approval)
          {
            approval_obj = jQuery.parseJSON(approval);
            console.log("Approval Data " + approval_obj);
            console.log("Approval Data " + approval_obj[0] + ", " + approval_obj[2]);
            alert(response);

            if(approval_obj[0] == 1)
            {
              $("input[id='home_ok']").prop("checked", true);
            }
            if(approval_obj[2] == 1)
            {
              $("input[id='away_ok']").prop("checked", true);
            }

            if((approval_obj[0] == 1) && (approval_obj[2] == 1))
            //if((approval_obj[0] == 1) && (approval_obj[3] == 1))
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
    <td class='text-center'><?= $title ?></td> 
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
    if(($i == 0) && (($title == 'Semi Final') || ($title == 'Grand Final')))
    {
      $finals_games = ($no_of_games+1);
    }
    else
    {
      $finals_games = $no_of_games;
    }
    echo("<tr>");
    echo("<td class='A_Team' align=center>");
    echo("<div id='A_player_" . $i . "'></div>");
    echo("</td>");
    echo("<td id='A_Team_" . $i . "' align=center>");
    for($j = 0; $j < $finals_games; $j++) // no of games
    { 
      echo("<input type='checkbox' id='A_win_" . $i . "_" . $j . "' OnChange='disableCB(" . $i . ", " . $j . ");'><br>");
    }
    echo("</td>");
    echo("<td class='A_Team' align=center>");
    for($j = 0; $j < $finals_games; $j++) // no of games
    { 
      echo("<input type='text' id='A_score_" . $i . "_" . $j . "' style='width:40px; height:20px; font-size:12px;' readonly>");
      echo("<input type='text' id='A_breaks_" . $i . "_" . $j . "' style='width:40px; height:20px; font-size:12px;' readonly><br>");
    }
    echo("</td>");
    echo("<td align=center>");
    echo("<div id='B_player_" . $i . "'></div>");
    echo("</td>");
    echo("<td id='B_Team_" . $i . "' align=center>");
    for($j = 0; $j < $finals_games; $j++) // no of games
    { 
      echo("<input type='checkbox' id='B_win_" . $i . "_" . $j . "' OnChange='disableCB(" . $i . ", " . $j . ");'><br>");
    }
    echo("</td>");
    echo("<td align=center>");
    for($j = 0; $j < $finals_games; $j++) // no of games
    { 
      echo("<input type='text' id='B_score_" . $i . "_" . $j . "' style='width:40px; height:20px; font-size:12px;' readonly>");
      echo("<input type='text' id='B_breaks_" . $i . "_" . $j . "' style='width:30px; height:20px; font-size:12px;' readonly><br>");
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
  echo("<td align='center'>Player<br>(Tier)</td>"); 
  echo("<td align='center'>Result</td>"); 
  echo("<td align='center'>Final Scr<br>(Raw Scr)<br>Breaks</td>"); 
  echo("<td align='center'>Player<br>(Tier)</td>"); 
  echo("<td align='center'>Result</td>"); 
  echo("<td align='center'>Final Scr<br>(Raw Scr)<br>Breaks</td>"); 
  echo("</tr>"); 
  for($i = 0; $i < $no_of_players; $i++)
  {
    echo("<tr>");
    echo("<td class='A_Team_" . $i . "' align=center><div id='A_player_" . $i . "'></div><div id='A_tier_" . $i . "'></div></td>");
    echo("<td id='A_Team_" . $i . "' align=center>Win&nbsp;&nbsp;<input type='radio' id='A_win_" . $i . "' name='A_Result_" . $i . "' readonly disabled><br>");
    echo("Loss&nbsp;<input type='radio' id='A_loss_" . $i . "' name='A_Result_" . $i . "' checked readonly disabled><br>");
    echo("Draw&nbsp;<input type='radio' id='A_draw_" . $i . "' name='A_Result_" . $i . "' readonly disabled></td>");
    echo("<td align=center><input type='text' id='A_score_" . $i . "' style='width:50px; height:20px; font-size:12px;' readonly><br><br>");
    echo("<input type='text' id='A_stick_" . $i . "' style='width:50px; height:20px; font-size:12px; font-style: italic;' readonly disabled><br><br>");
    echo("<textarea id='A_breaks_" . $i . "' name='breaks_" . $i . "' style='width:60px; height:25px: overflow: auto;' class='break' readonly tabindex=" . ($no_of_games+3) . "></textarea></td>");
   
    echo("<td class='B_Team_" . $i . "' align=center><div id='B_player_" . $i . "'></div><div id='B_tier_" . $i . "'></div></td>");
    echo("<td id='B_Team_" . $i . "' align=center>Win&nbsp;&nbsp;<input type='radio' id='B_win_" . $i . "' name='B_Result_" . $i . "' readonly disabled><br>");
    echo("Loss&nbsp;<input type='radio' id='B_loss_" . $i . "' name='B_Result_" . $i . "' checked readonly disabled><br>");
    echo("Draw&nbsp;<input type='radio' id='B_draw_" . $i . "' name='B_Result_" . $i . "' readonly disabled></td>");
    echo("<td class='B_Team_" . $i . "' align=center><input type='text' id='B_score_" . $i . "' style='width:50px; height:20px; font-size:12px;' readonly><br><br>");
    echo("<input type='text' id='B_stick_" . $i . "' style='width:50px; height:20px; font-size:12px;font-style: italic;' readonly disabled><br><br>");
    echo("<textarea id='B_breaks_" . $i . "' name='breaks_" . $i . "' style='width:60px; height:25px: overflow: auto;' class='break' readonly tabindex=" . ($no_of_games+3) . "></textarea></td>");

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
  $style = '';
  $results_span = 2;
  echo("<tr id='good_bad'>");
  echo("<input type='hidden' id='A_draws' value=0>");
  echo("<td " . $style . " class='text-right'><b>Games Won:</b>");
  echo("</td>");
  echo("<td " . $style . " class='text-center'><input type='text' id='A_wins' style='width:30px; height:20px; font-size:12px;' readonly></td>");
  echo("<td " . $style . ">&nbsp;</td>");
  echo("<input type='hidden' id='B_draws' value=0>");
  echo("<td " . $style . " class='text-right'><b>Games Won:</b>");
  echo("</td>");
  echo("<td " . $style . " class='text-center'><input type='text' id='B_wins' style='width:30px; height:20px; font-size:12px;' readonly></td>");
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
  $style = '';
  $results_span = 2;
  echo("<tr id='good_bad'>");
  echo("<td " . $style . " class='text-center'>Games Drawn:&nbsp;&nbsp;");
  echo("<input type='text' id='A_draws' style='width:30px; height:20px; font-size:12px;' readonly></td>");
  echo("<td " . $style . " class='text-center'>Games Won:&nbsp;&nbsp;");
  echo("<input type='text' id='A_wins' style='width:30px; height:20px; font-size:12px;' readonly></td>");
  echo("<td " . $style . ">&nbsp;</td>");
  echo("<td " . $style . " class='text-center'>Games Drawn:&nbsp;&nbsp;");
  echo("<input type='text' id='B_draws' style='width:30px; height:20px; font-size:12px;' readonly></td>");
  echo("<td " . $style . " class='text-center'>Games Won:&nbsp;&nbsp;");
  echo("<input type='text' id='B_wins' style='width:30px; height:20px; font-size:12px;' readonly></td>");
  echo("<td " . $style . ">&nbsp;</td>");
  echo("</tr>");
}
?>
<tr> 
<td colspan=8 align=center>&nbsp;</td> 
</tr> 
  <tr> 
    <td colspan=<?= $results_span ?> class='text-center'><b>Home Captain Approve:</b></td>
    <td class='text-center'><input type='checkbox' id='home_ok' <?=  $checkbox5  ?> ></td>
    <td colspan=<?= $results_span ?> class='text-center'><b>Away Captain Approve:</b></td>
    <td class='text-center'><input type='checkbox' id='away_ok' <?=  $checkbox6  ?> ></td>
  </tr>
</table>
<!--
<br>
<div> 
  <div class='text-center'>
    <td colspan=8 align='center'><a class='btn btn-primary btn-xs' id='test_reload' style='width:300px'>Test Reload</a>
  </div>
</div> 
-->
</form>
<br>
<form name="edit_scoresheet" id="edit_scoresheet" method="post" action="scoresheet.php">
  <input type="hidden" name="Grade" id="grade" value="<?php echo($_POST['Grade']); ?>" />
  <input type="hidden" name="Type" id="type" value="<?php echo($_POST['Type']); ?>" />
  <input type="hidden" name="RoundNo" id="round" value=<?= $_POST['Round'] ?> />
  <input type="hidden" name="RoundTitle" id="round_title" value="<?php echo($_POST['RoundTitle']); ?>" />
  <input type="hidden" name="DatePlayed" id="playing_date" value="<?php echo($_POST['DatePlayed']); ?>" />
  <input type="hidden" name="FixtureDate" id="played_date" value="<?php echo($_POST['DatePlayed']); ?>" />
  <input type="hidden" name="TeamGrade" id="team_grade" value="<?php echo($_POST['TeamGrade']); ?>" />
  <input type="hidden" name="TeamScoring" id="team_scoring" value="<?php echo($_POST['TeamScoring']); ?>" />
  <input type="hidden" name="TeamtoEdit" id="team_to_edit" value='<?php echo($_SESSION['home']); ?>'/>
  <input type="hidden" name="OppositiontoEdit" id="opp_to_edit" value='<?php echo($_SESSION['away']); ?>' />
  <!--<input type="hidden" name="TeamtoEdit" id="team_to_edit"/>
  <input type="hidden" name="OppositiontoEdit" id="opp_to_edit"/>-->
  <input type="hidden" name="Test" id="test"/>
  <input type="hidden" name="Year" id="year" value="<?php echo($_SESSION['year']); ?>" />
  <input type="hidden" name="Season" id="season" value="<?php echo($_SESSION['season']); ?>" />
<!--
  <div class='text-center' id='remove_home_approve'>
    <a class='btn btn-primary btn-xs' href="javascript:;" id='edit_home' onclick="$.fn.edit_team('<?php echo($_SESSION['home']); ?>')" style='width:300px'>Edit the <?php echo($_SESSION['home']); ?> Scoresheet</a>
  </div>
  <br>
  <div class='text-center' id='remove_away_approve'>
    <a class='btn btn-primary btn-xs' href="javascript:;" id='edit_away' onclick="$.fn.edit_team('<?php echo($_SESSION['away']); ?>')" style='width:300px'>Edit the <?php echo($_SESSION['away']); ?> Scoresheet</a>
  </div>
-->
  <div class='text-center' id='remove_home_approve'>
    <a class='btn btn-primary btn-xs' href="javascript:;" id='edit_home' onclick="EditTeam('<?php echo($_SESSION['home']); ?>')" style='width:300px'>Edit the <?php echo($_SESSION['home']); ?> Scoresheet</a>
  </div>
  <br>
  <div class='text-center' id='remove_away_approve'>
    <a class='btn btn-primary btn-xs' href="javascript:;" id='edit_away' onclick="EditTeam('<?php echo($_SESSION['away']); ?>')" style='width:300px'>Edit the <?php echo($_SESSION['away']); ?> Scoresheet</a>
  </div>
</form>
<br /> 
</center>
<form name="activate_cb" id="activate_cb" method="post" action="scoresheet.php" onsubmit="return activate_cb(event)">
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

