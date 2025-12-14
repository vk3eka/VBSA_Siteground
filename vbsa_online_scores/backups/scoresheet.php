<?php 
if (!isset($_SESSION)) {
  session_start();
}
include('header.php');
include('connection.inc'); 
include('php_functions.php'); 

// data from select fixture page
if($_POST['Season'] != '')
{
  $season = $_POST['Season'];
  $current_year  = $_POST['Year'];
  $round = $_POST['RoundNo'];
  $grade = $_POST['Grade'];
  $type = $_POST['Type'];
  $playing_date = $_POST['FixtureDate'];
  $scoring_team = $_POST['TeamScoring'];
  $team_grade = $_POST['TeamGrade'];
  $title = $_POST['RoundTitle'];
}
else // data from get opposition page
{
  $season = $_GET['Season'];
  $current_year  = $_GET['Year'];
  $round = $_GET['RoundNo'];
  $grade = $_GET['Grade'];
  $type = $_GET['Type'];
  $playing_date = $_GET['FixtureDate'];
  $scoring_team = $_GET['TeamScoring'];
  $team_grade = $_GET['TeamGrade'];
  $title = $_GET['RoundTitle'];
}

// data from select fixture page to set session teams
if($_POST['SessionHomeTeam'] != '')
{
  $_SESSION['home'] = $_POST['SessionHomeTeam'];
  $session_home = $_SESSION['home'];
  $_SESSION['away'] = $_POST['SessionAwayTeam'];
  $session_away = $_SESSION['away'];
}
else
{
  $session_home = $_SESSION['home'];
  $session_away = $_SESSION['away'];
}
$clubname = $_SESSION['clubname'];

// data from captain approval page
if((isset($_POST['TeamtoEdit']) && $_POST['TeamtoEdit'] != ''))
{
  $team = $_POST['TeamtoEdit'];
  $opposition = $_POST['OppositiontoEdit'];
}
else
{
  if($scoring_team == 'home')
  {
    $team = $session_home;
    $opposition = $session_away;
  }
  elseif($scoring_team == 'away')
  {
    $team = $session_away;
    $opposition = $session_home;
  }
}
/*
echo("Test " . $_POST['Test'] . "<br>");
echo("Team " . $team . "<br>");
echo("Opposition " . $opposition . "<br>");

echo("<pre>");
echo(var_dump($_POST) . "<br>");
echo("</pre>");
*/

// setup permissions for club captains approval checkbox
if(isset($_SESSION['clubname']))
{
  if(($clubname == $session_home) || ($clubname == $session_away))
  {
    //echo("I can enter all the scores.<br>");
    $readonly = '';
    $checkbox = '';
    $home = '';
    $away = 'disabled';
  }
  else
  {
    //echo("I can do nothing 1.<br>");
    $readonly = 'readonly';
    $checkbox = 'disabled';
    $home = 'disabled';
    $away = 'disabled';
  }
}
else
{
  //echo("I can do nothing 2.<br>");
  $readonly = 'readonly';
  $checkbox = 'disabled';
  $home = 'disabled';
  $away = 'disabled';
}

if($_SESSION['login_rights'] == 'Administrator')
{
    $readonly = '';
    $checkbox = '';
    $home = '';
    $away = '';
}

// get from grade settings table
$sql_grades = "Select * From tbl_team_grade Where grade = '" . $team_grade . "'";
$result_grades = $dbcnx_client->query($sql_grades) or die("Couldn't execute settings query. " . mysqli_error($dbcnx_client));
$build_grades = $result_grades->fetch_assoc();

$NoOfFixtures = $build_grades['no_of_matches'];
$NoOfRounds = $build_grades['no_of_rounds'];
$no_of_games = $build_grades['games_round'];
$no_of_players = $build_grades['no_of_players'];
$min_break = $build_grades['min_breaks'];

// get players and populate array
echo("<script type='text/javascript'>");
echo("var existingForm;");
echo("function fillelementarray() {");
$sql = "Select * from tbl_scoresheet where team = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year . " AND team_grade = '" . $team_grade . "'  Order By playing_position";
$result_scoresheet = $dbcnx_client->query($sql) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$j = 0;
while ($build_data = $result_scoresheet->fetch_assoc()) 
{
  echo("document.getElementById('year').innerHTML = '" . $build_data['year'] . "';");
  echo("document.getElementById('round').innerHTML = '" . $build_data['round'] . "';");
  echo("document.getElementById('season').innerHTML = '" . $build_data['season'] . "';");
  echo("document.getElementById('type').innerHTML = '" . $build_data['type'] . "';");
  echo("document.getElementById('grade').innerHTML = '" . $build_data['grade'] . "';");
  echo("document.getElementById('playing_date').innerHTML = '" . $build_data['playing_date'] . "';");
  echo("document.getElementById('id_" . $j . "').value = " . $build_data['id'] . ";");
  echo("document.getElementById('player_" . $j . "').value = '" . addslashes($build_data['players_name']) . "';");
  echo("document.getElementById('firstname_" . $j . "').value = '" . $build_data['firstname'] . "';");
  echo("document.getElementById('lastname_" . $j . "').value = '" . addslashes($build_data['lastname']) . "';");
  echo("document.getElementById('member_id_" . $j . "').value = '" . $build_data['memberID'] . "';");
  echo("document.getElementById('team_id_" . $j . "').value = '" . $build_data['team_id'] . "';");
  echo("document.getElementById('tier_" . $j . "').value = '" . number_format($build_data['tier'], 1) . "';");
  
  for ($i = 0; $i < $no_of_games; $i++) 
  {   
    if($build_data['score_' . ($i+1)] > 0)
    {
      echo("document.getElementById('score_" . $j . "_" . $i . "').value = '" . number_format($build_data['score_' . ($i+1)], 1) . "';");
    }
    else
    {
      echo("document.getElementById('score_" . $j . "_" . $i . "').value = '0.0';");
    }
    echo("document.getElementById('breaks_" . $j . "_" . $i . "').value = '" . $build_data['break_' . ($i+1)] . "';");
    $row_arr = explode(" ", trim($build_data['break_' . ($i+1)]));
    $row_size = sizeof($row_arr);
    if($row_size == 0)
    {
      $row_size = 1;
    }
    else
    {
      $row_size = sizeof($row_arr);
    }
    echo("document.getElementById('breaks_" . $j . "_" . $i . "').style='width:40px; height:" . ($row_size*25) . "px';");
  }
  if(($j == 0) && ($type == 'Snooker') && (($title == 'Semi Final') || ($title == 'Grand Final')))
  {   
    echo("document.getElementById('score_0_3').value = '" . number_format($build_data['score_4'], 1) . "';");
    echo("document.getElementById('breaks_0_3').value = '" . $build_data['break_4'] . "';");
    $row_arr = explode(" ", trim($build_data['break_4']));
    $row_size = sizeof($row_arr);
    if($row_size == 0)
    {
      $row_size = 1;
    }
    else
    {
      $row_size = sizeof($row_arr);
    }
    echo("document.getElementById('breaks_0_3').style='width:40px; height:" . ($row_size*25) . "px';");
  }
  $j++;
  // get club points/games won data
  $sql_club = "Select * from tbl_club_results where club = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND year = " . $current_year;
  //echo($sql_club . "<br>");
  $result_club = $dbcnx_client->query($sql_club) or die("Couldn't execute club query. " . mysqli_error($dbcnx_client));
  $build_data_club = $result_club->fetch_assoc();
  echo("document.getElementById('points').value = '" . $build_data_club['overall_points'] . "';");
  echo("document.getElementById('wins').value = '" . $build_data_club['games_won'] . "';");
  echo("document.getElementById('no_of_players').value = " . $j . ";");
}
$result_scoresheet->free_result();
//$result_club->free_result();
echo("$.fn.pagerefresh('" . $type . "');");
echo("existingForm = $.fn.getcurrentarray('" . $type . "');");
echo("}");
echo("</script>");

// data for add player dropdown
$players = array();
$sql = "Select MemberID, FirstName, LastName from members Order By LastName";
$result_players = $dbcnx_client->query($sql);
//echo($sql);
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
$result_players->free_result();

?>
<script type='text/javascript'>

$(document).ready(function()
{
  $.fn.getcurrentarray = function (type) {
    var new_array = new Array;
    var title = '<?= $title ?>';
    new_array = '';
    for (j = 0; j < 4; j++) 
    {
      if((type == 'Snooker') && (j == 0) && ((title == 'Semi Final') || (title == 'Grand Final')))
      {
        new_array += $('#player_' + j).val() + "";
        for (i = 0; i < 4; i++) 
        {
          if($('#score_' + j + '_' + i).val() == '')
          {
            $('#score_' + j + '_' + i).val(0);
          }
          new_array +=  $('#score_' + j + '_' + i).val() + "" +
                        $.trim($('#breaks_' + j + '_' + i).val()) + "";
        }
      }
      else if(type == 'Snooker')
      {
        new_array += $('#player_' + j).val() + "";
        for (i = 0; i < 3; i++) 
        {
          new_array +=  $('#score_' + j + '_' + i).val() + "" +
                        $.trim($('#breaks_' + j + '_' + i).val()) + "";
        }
      }
      else if(type == 'Billiards')
      {
        new_array +=  $('#player_' + j).val() + "" +
                      $('#tier_' + j).val() + "" +
                      $('#score_' + j + '_0').val() + "" +
                      $.trim($('#breaks_' + j + '_0').val()) + "";
      }
      new_array +=  ",";
    }
    if(type == 'Snooker')
    {
      new_array = new_array.substring(0,new_array.length - 1);
    }
    return new_array;
  }
});

window.onload = function() 
{
  fillelementarray();
}

function CheckInt()
{
  var score_input = document.getElementsByTagName("input");
  for (var i = 0; i < score_input.length; i++) 
  {
    //alert(score_input[i].id.substring(0,4));
    if((score_input[i].type == "text") && ((score_input[i].id.substring(0,5) == 'score') || (score_input[i].id.substring(0,4) == 'tier')))
    {
      if(isNaN(score_input[i].value))
      {
        alert("Score needs to be a number");
        score_input[i].value = '0.0';
        return;
      }
      else
      {
        current_val = parseFloat(score_input[i].value).toFixed(1);
        score_input[i].value = current_val;
      }
    }
  }
}

</script>
<!--Content--> 
<form name="authorise" id="authorise" method="post" action='scoresheet.php'>
<input type="hidden" name="Grade" id="grade" value="<?php echo($_POST['Grade']); ?>" />
<input type="hidden" name="Type" id="type" value="<?php echo($_POST['Type']); ?>" />
<input type="hidden" name="RoundNo" id="round" value="<?php echo($_POST['RoundNo']); ?>" />
<input type="hidden" name="RoundTitle" id="round_title" value="<?php echo($_POST['RoundTitle']); ?>" />
<input type="hidden" name="DatePlayed" id="playing_date" value="<?php echo($_POST['FixtureDate']); ?>" />
<input type="hidden" name="FixtureDate" id="played_date" value="<?php echo($_POST['FixtureDate']); ?>" />
<input type="hidden" name="TeamGrade" id="team_grade" value="<?php echo($_POST['TeamGrade']); ?>" />
<input type="hidden" name="TeamScoring" id="team_scoring" value="<?php echo($_POST['TeamScoring']); ?>" />
<input type="hidden" name="Year" id="year" value="<?php echo($_SESSION['year']); ?>" />
<input type="hidden" name="Season" id="season" value="<?php echo($_SESSION['season']); ?>" />
<input type="hidden" name="TeamtoEdit" id="team_to_edit" />
<input type="hidden" name="OppositiontoEdit" id="opposition_to_edit" />
<input type="hidden" name="PackedScoreData" id="PackedScoreData" />
<input type="hidden" name="PackedResultData" id="PackedResultData" />
<input type="hidden" name="ButtonName" />
  <div class="">
    <div class="page-title"></div>
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <div class="clearfix"></div>
        </div>
        <div class="x_content"> 
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
        <tr>
         <td align="center"><h1>Pennant Score Sheet - <?= $current_year ?></h1></td>
        </tr>
        <tr>
         <td align="center"><h1>Season - <?= $season ?></h1></td>
        </tr>
        </table>
        <input type='hidden' id='team_grade' name='team_grade' value='<?= $season ?>'>
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
          <tr> 
            <td class='text-center'><b>Grade:</b></td>
            <td class='text-center'><b>Round No.:</b></td> 
            <td class='text-center'><b>Game Type:</b></td>
            <td class='text-center'><b>Date:</b></td>  
            <td class='text-center'><b>Team Grade:</b></td>
          </tr>
          <tr>
            <td class='text-center'><b><?php echo($_POST['Grade']); ?></b></td> 
            <!--<td class='text-center'><b><?php echo($_POST['RoundNo']); ?></b></td>  -->
            <td class='text-center'><b><?php echo($title); ?></b></td>  
            <td class='text-center'><b><?php echo($_POST['Type']); ?></b></td>
            <td class='text-center'><b><?php echo($_POST['FixtureDate']); ?></b></td>
            <td class='text-center'><b><?php echo($_POST['TeamGrade']); ?></b></td>  
          </tr>
        </table>
        <?php 
        // added team grade 17/07/2023
        $sql_approve = "Select * from tbl_scoresheet where team = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year . " and team_grade = '" . $team_grade . "' Order By playing_position";
        //echo("Home sql " . $sql_approve . "<br>");
        $result_approve = $dbcnx_client->query($sql_approve) or die("Couldn't execute approve query. " . mysqli_error($dbcnx_client));
        $num_home = $result_approve->num_rows;
        while($build_data_approve = $result_approve->fetch_assoc())
        {
          $capt_home = $build_data_approve['capt_home'];
          //$capt_away = $build_data_approve['capt_away'];
        }
        //if($capt_home == 1)
        //if(($capt_home == 1) && ($capt_away == 1))
        //{
        //  $disabled = " disabled";
        //}

        $sql_approve_away = "Select * from tbl_scoresheet where team = '" . $opposition . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year . " and team_grade = '" . $team_grade . "' Order By playing_position";
        //echo("Away sql " . $sql_approve_away . "<br>");
        $result_approve_away = $dbcnx_client->query($sql_approve_away) or die("Couldn't execute approve query. " . mysqli_error($dbcnx_client));
        $num_away = $result_approve_away->num_rows;
        while($build_data_approve_away = $result_approve_away->fetch_assoc())
        {
          $capt_away = $build_data_approve_away['capt_home'];
          //$capt_away = $build_data_approve['capt_away'];
        }

        //if($capt_home == 1)
        if(($capt_home == 1) && ($capt_away == 1))
        {
          $disabled = " disabled";
        }

        //echo("Home " . $capt_home . ", Away " . $capt_away . "<br>");
        //echo("Disabled " . $disabled . "<br>");

        if(($num_home == $no_of_players) && ($num_away == $no_of_players))
        {
           $saved_already = " disabled";
        }
        if($_SESSION['login_rights'] == 'Administrator')
        {
           $disabled = " ";
           $saved_already = " ";
        }
        ?>
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
          <tr>
            <td class='text-center'><b>Home Team</b></td>
            <td class='text-center'><b>Away Team</b></td>
          </tr>
          <tr>
            <td align='center' valign='top'><?php echo($_SESSION['home']); ?></td>
            <td align='center' valign='top'><?php echo($_SESSION['away']); ?></td>  
          </tr>
        </table>
        <br>
        <table class='table table-striped table-bordered dt-responsive nowrap display'>
          <tr>
            <td class='text-left' colspan='11'><a class='btn btn-primary btn-xs' id='add_player' <?php echo($disabled); ?><?php echo($saved_already); ?> >Add Players to scoresheet</a></td>
          </tr>

          <tr> 
            <td colspan="4">Enter Scores for <?php echo($team); ?></td> <!-- change from 5 -->
          </tr>
          <tr> 
            <td align='center'>No.</td>
            <td align='center'>Players Name</td>
            <?php
            
            if($type == 'Billiards')
            {
              echo("<td align='center'>Tier</td>");
              echo("<td align='center'>Calculated Score</td>");
            }
            else
            {
              echo("<td align='center'>Score</td>");
            }
            ?>
            <td align='center'>Breaks</td>
        </tr>
        <?php
        for($i = 0; $i < $no_of_players; $i++) // no of players
        {
          echo("<tr>");
          if($i == 0) // add rows for 4th frame if required
          {
            echo("<td rowspan='" . ($no_of_games+1) . "' style='background-color: black; color: white; text-align: center;'>" . ($i+1) . "</td>");
          }
          else
          {
            echo("<td rowspan='" . $no_of_games . "' style='background-color: black; color: white; text-align: center;'>" . ($i+1) . "</td>");
          }
          echo("<input type='hidden' id='id_" . $i . "' name='id_" . $i . "'>");
          echo("<input type='hidden' id='member_id_" . $i . "' name='member_id_" . $i . "'>");
          echo("<input type='hidden' id='firstname_" . $i . "' name='firstname_" . $i . "'>");
          echo("<input type='hidden' id='lastname_" . $i . "' name='lastname_" . $i . "'>");
          echo("<input type='hidden' id='team_id_" . $i . "' name='team_id_" . $i . "'>");

          echo("<td rowspan='" . $no_of_games . "' align='center'><textarea rows='2' cols='1' wrap='soft' id='player_" . $i . "' name='player_" . $i . "' class='form-control input-sm' readonly></textarea>");

          if($type == 'Billiards')
          {
            echo("
                <td style='width: 30px; valign='center'><input type='text' name='tier_" . $i . "' id='tier_" . $i . "' style='width:50px' onFocus='this.setSelectionRange(0, this.value.length)' OnChange='CheckInt();' tabindex=" . $no_of_games . " value='0.0'></td>
              ");
          }
          else
          {
            echo("<input type='hidden' id='tier_" . $i . "' name='tier_" . $i . "' value='0.0'>");
          }
          
          for($j = 0; $j < $no_of_games; $j++) // no of games
          {
            if($type == "Snooker")
            {
              echo("<input type='hidden' id='win_" . $i . "' name='win_" . $i . "'>");
              echo("<input type='hidden' id='draw_" . $i . "' name='draw_" . $i . "'>");
            }
            elseif($type == 'Billiards')
            {
              echo("<input type='hidden' id='result_" . $i . "_" . $j . "' name='result_" . $i . "_" . $j . "'>");
            }

            //echo("<td align=center><input type='text' id='score_" . $i . "_" . $j . "' name='score_" . $i . "_" . $j . "' style='width:50px' " . $readonly . " tabindex=" . $no_of_games . "></td>");
            echo("<td align=center><input type='text' id='score_" . $i . "_" . $j . "' name='score_" . $i . "_" . $j . "' style='width:50px' " . $readonly . " onFocus='this.setSelectionRange(0, this.value.length)' OnChange='CheckInt();' tabindex=" . $no_of_games . "></td>");

            echo("<td align=center><textarea id='breaks_" . $i . "_" . $j . "' name='breaks_" . $i . "_" . $j . "' style='width:40px; height:25px' class='break' " . $readonly . " tabindex=" . ($no_of_games+3) . "></textarea></td>");
            echo("</tr>");
            echo("<tr>");

          }
          //if(($i == 0) and ($type == 'Snooker')) // add 4th frame for finals player 1
          if(($i == 0) && ($type == 'Snooker') && (($title == 'Semi Final') || ($title == 'Grand Final')))
          {
            echo("<tr>");
            echo("<td style='background-color: black; color: white; text-align: center;'></td>");
            echo("<td align=center style='background-color: black; color: white;'>4th Frame (if required)</td>");
            echo("<td align=center style='background-color: gray; color: white;'><input type='text' id='score_" . $i . "_3' name='score_" . $i . "_3' style='width:50px; height:25px;text-align:left;'  class='form-control' " . $readonly . " onFocus='this.setSelectionRange(0, this.value.length)'  OnChange='CheckInt();'  ></td>");
            //echo("<td align=center style='background-color: gray; color: white;'><input type='text' id='score_" . $i . "_3' name='score_" . $i . "_3' style='width:50px; height:25px;text-align:left;'  class='form-control' " . $readonly . " OnChange='CheckInt();'></td>");
            echo("<td align=center style='background-color: gray; color: white;'><input type='text' id='breaks_" . $i . "_3' name='breaks_" . $i . "_3' style='width:40px; height:25px'  class='form-control break' " . $readonly . "></td>");
            echo("</tr>");
          }
          else
          {
            if($type == "Snooker")
            {
              echo("<input type='hidden' id='win_" . $i . "_3'>");
              echo("<input type='hidden' id='score_" . $i . "_3'>");
              echo("<input type='hidden' id='breaks_" . $i . "_3'>");
            }
            elseif($type == "Billiards")
            {
              echo("<input type='hidden' id='result_" . $i . "_3'>");
              echo("<input type='hidden' id='score_" . $i . "_3'>");
              echo("<input type='hidden' id='breaks_" . $i . "_3'>");
            }

          }
        }
        echo("<input type='hidden' id='no_of_players' value=" . $i . ">");
        echo("<input type='hidden' id='wins'>");
        echo("<input type='hidden' id='draws'>");
        echo("<input type='hidden' id='points'>");
      ?>
      </table>
      </div>
    </div>
  </div>
</div>

<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
  <tr>
    <td class='text-center'>
      <a class='btn btn-primary btn-xs' href="javascript:;" id='savebutton' style='width:150px; height:100px; padding: 40px 0; border: 2px solid black;' <?php echo($disabled); ?>><b>Save Players/Scores</b></a>
    </td>
    <td class='text-center'>
      <a class='btn btn-primary btn-xs' id='go_to_opposition' style='width:150px; height:100px; padding: 35px 0; border: 2px solid black;' <?php echo($disabled); ?> ><b>Goto Opposition Scoresheet</b></a>
    </td>
  </tr>
<tr>
    <td class='text-center'>
      <a class='btn btn-primary btn-xs' id='matchups' style='width:150px; height:100px; padding: 35px 0; border: 2px solid black;' <?php echo($disabled); ?> ><b>View Player to Player Match Ups</b></a>
    </td>
</form>
<form name="approval" id="approval" method="post" action="captain_approval.php">
<input type="hidden" name="Grade" id="grade" value="<?php echo($_POST['Grade']); ?>" />
<input type="hidden" name="Type" id="type" value="<?php echo($_POST['Type']); ?>" />
<input type="hidden" name="Round" id="round" value="<?php echo($_POST['RoundNo']); ?>" />
<input type="hidden" name="RoundTitle" id="round_title" value="<?php echo($_POST['RoundTitle']); ?>" />
<input type="hidden" name="TeamtoEdit" id="edit_team" value="<?php echo($_POST['TeamtoEdit']); ?>" />
<input type="hidden" name="OppositiontoEdit" id="edit_team_op" value="<?php echo($_POST['OppositiontoEdit']); ?>" />
<input type="hidden" name="DatePlayed" id="playing_date" value="<?php echo($_POST['FixtureDate']); ?>" />
<input type="hidden" name="TeamScoring" id="team_scoring" value="<?php echo($_POST['TeamScoring']); ?>" />
<input type="hidden" name="Year" id="year" value="<?php echo($_SESSION['year']); ?>" />
<input type="hidden" name="Season" id="season" value="<?php echo($_SESSION['season']); ?>" />
<input type="hidden" name="TeamGrade" id="team_grade" value="<?php echo($_POST['TeamGrade']); ?>" />
<td class='text-center'>
      <a class='btn btn-primary btn-xs' id='submit_approval' style='width:150px; height:100px; padding: 35px 0; border: 2px solid black;' <?php echo($disabled); ?> ><b>Go to Approval Page</b></a>
    </td>
  </tr>
</table>
</form>
<br>
<br>
<script>
$(document).ready(function()
{
  $.fn.pagerefresh = function (type) {
    var year = <?php echo $_SESSION['year']; ?>;
    var season = '<?php echo $_SESSION['season']; ?>';
    var team = '<?= $team ?>';
    var opposition = '<?= $opposition ?>';
    var round = <?= $round ?>;
    var team_grade = '<?= $team_grade ?>';
    var type = $('#type').val();
    var title = '<?= $title ?>';
    $.ajax({
      url:"<?= $url ?>/get_scoresheet.php?home=" + team + "&away=" + opposition + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round + "&title=" + title,
      success : function(data){
        var obj = jQuery.parseJSON(data);
        console.log("Scoresheet Data " + obj);
        for (j = 0; j < 4; j++) 
        {
          obj_test = obj[j].split(",")
          if((type == 'Snooker') && ((title == 'Semi Final') || (title == 'Grand Final')))
          {
            $('#player_' + j).val(obj_test[0]);
            for (i = 0; i < 4; i++) 
            {
              row_arr = obj_test[i+4].split(" ");
              row_size = row_arr.length;
              if(row_size  == 0)
              {
                row_size = 1;
              }
              else
              {
                row_size = (row_arr.length-1);
              }
              $('#score_' + j + '_' + i).val($.trim(obj_test[(i+1)]));
              $('#breaks_' + j + '_' + i).attr('rows', row_size);
              $('#breaks_' + j + '_' + i).val($.trim(obj_test[(i+5)]));
            }
          }
          else if(type == 'Snooker')
          //if(type == 'Snooker')
          {
            $('#player_' + j).val(obj_test[0]);
            for (i = 0; i < 3; i++) 
            {
              row_arr = obj_test[i+4].split(" ");
              row_size = row_arr.length;
              if(row_size  == 0)
              {
                row_size = 1;
              }
              else
              {
                row_size = (row_arr.length-1);
              }
              $('#score_' + j + '_' + i).val($.trim(obj_test[i+1]));
              $('#breaks_' + j + '_' + i).attr('rows', row_size);
              $('#breaks_' + j + '_' + i).val($.trim(obj_test[i+4]));
            }
          }
          else if(type == 'Billiards')
          {
            console.log("Billiards Data " + obj);
            for (i = 0; i < 3; i++) 
            {
              row_arr = obj_test[i+4].split(" ");
              row_size = row_arr.length;
              if(row_size  == 0)
              {
                row_size = 1;
              }
              else
              {
                row_size = (row_arr.length-1);
              }
              $('#player_' + j).val(obj_test[0]);
              $('#score_' + j + '_' + i).val($.trim(obj_test[i+1]));
              $('#breaks_' + j + '_' + i).attr('rows', row_size);
              $('#breaks_' + j + '_' + i).val($.trim(obj_test[i+4]));
              $('#tier_' + j).val($.trim(obj_test[i+5]));
            }
          }
        }
      },
      error: function (data) {
        alert("Both Teams need to be entered!"); 
      }
    });
  }

  //declare function to display players in modal box
  $.fn.displayplayers = function () {
    $('#add').empty();
    var team_name = '<?= $team ?>';
    var PlayerCount = $('#no_of_players').val();
    PlayerCount = 4;
    var PlayingDate = $('#playing_date').val();
    var team_grade = $('#team_grade').val();
    var obj = "";
    var fullname = "";
    var memberID = "";
    var player_pos = '';
    $.ajax({
      url:"<?= $url ?>/get_players.php?clubname=" + team_name + "&year=" + <?= $_SESSION['year'] ?> + "&TeamGrade=" + team_grade,
      success : function(data){
        obj = jQuery.parseJSON(data);
        console.log(obj);
        var output = "";
        output += ("<table class='table table-striped table-bordered dt-responsive nowrap display fetched-data' width='100%'>");
        output += ("<thead>");
        output += ("<tr>");
        output += ("<th rowspan='2' style='text-align: center;'>Name</th>");
        output += ("<th colspan='" + PlayerCount + "' style='text-align: center;'>Player Position</th>");
        output += ("</tr>");
        output += ("<tr>");
        for(p = 0; p < PlayerCount; p++)
        {
           output += ("<th style='text-align: center;'>" + (p+1) + "</th>");
        }
        output += ("</tr>");
        output += ("<thead>");
        output += ("<tbody>");
        for(k = 0; k < obj.length; k++)
        {
          fullobj = obj[k].split(', ');
          fullname = fullobj[0] + ' ' + fullobj[1];
          //alert(fullobj[0] + ", " + fullobj[1]);
          memberID = fullobj[2];
          team_id = fullobj[3];
          output += ("<tr>");
          output += ("<td align='left' style='width:250px' id='fullname" + k + "' name='fullname" + k + "'>" + fullname + "</td>");
          output += ("<input type='hidden' id='memberID" + k + "' name='memberID" + k + "' value='" + memberID + "'>");
          for(i = 0; i < PlayerCount; i++)
          {
            output += ("<input type='hidden' id='firstname" + k + "' name='firstname" + k + "' value='" + fullobj[0] + "'>");
            output += ("<input type='hidden' id='lastname" + k + "' name='lastname" + k + "' value='" + fullobj[1] + "'>");
            output += ("<input type='hidden' id='team_id" + k + "' name='team_id" + k + "' value='" + fullobj[3] + "'>");

            output += ("<td align='center'><input type='radio' data-col='" + (i+1) + "' id='position_r" + k + "_c" + (i+1) + "' name='position" + k + "'></td>");
          }
          output += ("</tr>");
          var name = fullname;
          var index = k;
          $.ajax({
            url : "<?= $url ?>/get_player_position.php?name=" +  name + "&date=" + PlayingDate + "&team_grade=" + team_grade + "&team_id=" + team_id,
            ajaxI: index, // Capture the current value of 'i'.
            success: function(position_data){
              index = this.ajaxI; // Reinstate the correct value for 'i'.
              player_obj = jQuery.parseJSON(position_data);
              console.log(player_obj);
              var play_pos = player_obj[1];
              switch (play_pos) 
              { 
                case '1': 
                  $("input[id='position_r" + index + "_c1']").prop("checked", true);
                  break;
                case '2': 
                  $("input[id='position_r" + index + "_c2']").prop("checked", true);
                  break;
                case '3': 
                  $("input[id='position_r" + index + "_c3']").prop("checked", true);
                  break;
                case '4': 
                 $("input[id='position_r" + index + "_c4']").prop("checked", true);
                  break; 
                case '5': 
                  $("input[id='position_r" + index + "_c5']").prop("checked", true);
                  break; 
                case '6': 
                 $("input[id='position_r" + index + "_c6']").prop("checked", true);
                  break; 
              }
            }
          });
        }
        output += ("</tbody>");
        output += ("</table>");
        $($.parseHTML(output)).appendTo('#add');
        $("input[type='radio']").click(function(){
          var col, el;
          el = $(this);
          col = el.data("col");
          $("input[data-col='" + col + "']").prop("checked", false);
          el.prop("checked", true);
        });
      }
    });
  };

  // add emergency player to the database
  $.fn.add_emergency = function () {
    event.preventDefault();
    var total_players = parseInt($('#no_of_players').val());
    var no_of_players = (total_players+1);
    var team_name = '<?= $team ?>';
    var firstname = $('#em_first').val();
    var surname = $('#em_last').val();
    //alert(firstname + " " + surname);
    var email = $('#em_email').val();
    var mobile = $('#em_mobile').val();
    var previous = $('#em_previous').val();
    var user = '<?php echo($_SESSION['username']); ?>';
    var year = '<?php echo($_SESSION['year']); ?>';
    var season = '<?php echo($_SESSION['season']); ?>';
    var team_grade = $('#team_grade').val();
    var type = $('#type').val();
    var round = <?= $_POST['RoundNo'] ?>;
    if((firstname != '') && (surname != '') && (email != '') && (mobile != ''))
    {
      $.ajax({
        url:"<?= $url ?>/add_new_player.php?clubname=" + team_name + "&firstname=" + firstname + "&surname=" + surname + "&team_grade=" + team_grade + "&type=" + type + "&year=" + year + "&season=" + season + "&email=" + email + "&mobile=" + mobile + "&previous=" + previous + "&user=" + user + "&round=" + round,
        method: 'POST',
        success:function(response)
        {
          $('#tags').val(''); 
          $('#no_of_players').val(<?php echo($no_of_players); ?>);
          $('#add').empty();
          $.fn.displayplayers();
        },
      });
      return true;
    }
    else
    {
      alert("All boxes should be filled.");
      return false;
    }
  }

  $('#em_previous').click(function(event){
     event.preventDefault();
     var previous_caption = "Please repeat your search and make sure of spelling.  If still not found, continue with registering a new player";
     $($.parseHTML(previous_caption)).appendTo('#add_previous');
  })

  // add selected player to the database
  $('#newplayer').click(function(event){
    event.preventDefault();
    var total_players = parseInt($('#no_of_players').val());
    var no_of_players = (total_players+1);
    var team_name = '<?= $team ?>';
    var new_player = $('#tags').val();
    $.ajax({
      url:"<?= $url ?>/check_player.php?fullname=" + new_player,
      success : function(data)
      {
        player_obj = jQuery.parseJSON(data);
        //alert(player_obj[1]);
        //test_player = stripslashes(player_obj[1]);
        //alert(test_player);
        if(player_obj[2] == '')
        {
          $('#em_first').val(player_obj[0]);
          $('#em_last').val(player_obj[1]);
          $('#EmPlayer').modal('show');
          $('#em_player').click(function (event) {
            event.preventDefault();
            if($.fn.add_emergency() === true)
            {
              $('#EmPlayer').modal('hide');
              $.fn.displayplayers();
            }
          });
        }
        else
        {
          firstname = player_obj[0];
          surname = player_obj[1];
          //alert("Surname " + player_obj[1]);
          var year = '<?php echo($_SESSION['year']); ?>';
          var season = '<?php echo($_SESSION['season']); ?>';
          var team_grade = $('#team_grade').val();
          var type = $('#type').val();
          var round = <?= $_POST['RoundNo'] ?>;
          $.ajax({
            url:"<?= $url ?>/add_new_player.php?clubname=" + team_name + "&firstname=" + firstname + "&surname=" + surname + "&team_grade=" + team_grade + "&type=" + type + "&year=" + year + "&season=" + season + "&round=" + round,
            method: 'POST',
            success:function(response)
            {
              $('#tags').val(''); 
              $('#no_of_players').val(no_of_players);
              $('#add').empty();
              $.fn.displayplayers();
            },
          });
        }
      },
      error: function (request, error) {
        alert("Error");
      }
    });
  });

  $(".break").click(function()
  {
    $('#breakid').val($(this).attr('id'));
    var break_id = $('#breakid');
    var box_value = break_id.val();
    totalbreaks = $('#' + box_value).val();
    breaks = totalbreaks.split(' ');
    $('#brk1').val(breaks[0]);
    $('#brk2').val(breaks[1]);
    $('#brk3').val(breaks[2]);
    $('#brk4').val(breaks[3]);
    $('#brk5').val(breaks[4]);
    $('#brk6').val(breaks[5]);
    $('#all_breaks').val($(this).val());
    $('#BreaksModal').modal('show');
  });

  $("#add_break").click(function()
  {
    total_breaks = $('#temp_break').val() + " " + $('#all_breaks').val();
    $('#all_breaks').val(total_breaks);
    $('#temp_break').val('');
  });

  min_break = <?= $min_break ?>;

  $("#brk1").focusout(function()
  {
    if(($.isNumeric($("#brk1").val())) && ($("#brk1").val() >= min_break))
    {
      total_breaks = $('#brk1').val() + " " + $('#all_breaks').val();
      $('#all_breaks').val(total_breaks);
    }
    else
    {
      if($('#brk1').val() != "")
      {
        alert("Only numbers " + min_break + " or greater are accepted!");
        $("#brk1").val('')
      }
    }
  });
  $("#brk2").focusout(function()
  {
    if(($.isNumeric($("#brk2").val())) && ($("#brk2").val() >= min_break))
    {
      total_breaks = $('#brk2').val() + " " + $('#all_breaks').val();
      $('#all_breaks').val(total_breaks);
    }
    else
    {
      if($('#brk2').val() != "")
      {
        alert("Only numbers " + min_break + " or greater are accepted!");
        $("#brk2").val('')
      }
    }
  });
  $("#brk3").focusout(function()
  {
    if(($.isNumeric($("#brk3").val())) && ($("#brk3").val() >= min_break))
    {
      total_breaks = $('#brk3').val() + " " + $('#all_breaks').val();
      $('#all_breaks').val(total_breaks);
    }
    else
    {
      if($('#brk3').val() != "")
      {
        alert("Only numbers " + min_break + " or greater are accepted!");
        $("#brk3").val('')
      }
    }
  });
  $("#brk4").focusout(function()
  {
    if(($.isNumeric($("#brk4").val())) && ($("#brk4").val() >= min_break))
    {
      total_breaks = $('#brk4').val() + " " + $('#all_breaks').val();
      $('#all_breaks').val(total_breaks);
    }
    else
    {
      if($('#brk4').val() != "")
      {
       alert("Only numbers " + min_break + " or greater are accepted!");
        $("#brk4").val('')
      }
    }
  });
  $("#brk5").focusout(function()
  {
    if(($.isNumeric($("#brk5").val())) && ($("#brk5").val() >= min_break))
    {
      total_breaks = $('#brk5').val() + " " + $('#all_breaks').val();
      $('#all_breaks').val(total_breaks);
    }
    else
    {
      if($('#brk5').val() != "")
      {
        alert("Only numbers " + min_break + " or greater are accepted!");
        $("#brk5").val('')
      }
    }
  });
  $("#brk6").focusout(function()
  {
    if(($.isNumeric($("#brk6").val())) && ($("#brk6").val() >= min_break))
    {
      total_breaks = $('#brk6').val() + " " + $('#all_breaks').val();
      $('#all_breaks').val(total_breaks);
    }
    else
    {
      if($('#brk6').val() != "")
      {
        alert("Only numbers " + min_break + " or greater are accepted!");
        $("#brk6").val('')
      }
    }
  });

  // add breaks to selected player
  $('#new_break').click(function(event)
  {
    event.preventDefault();
    var breaks = $('#break_value');
    var break_id = $('#breakid');
    var box_value = breaks.val();
    var total_breaks = '';
    $('.break').each(function()
    {
      if(break_id.val() == $(this).attr('id'))
      {
        $(this).val(box_value);
        total_breaks = $('#brk1').val() + " " + $('#brk2').val() + " " + $('#brk3').val() + " " + $('#brk4').val() + " " + $('#brk5').val() + " " + $('#brk6').val();
        $(this).val(total_breaks);
        var row_arr  = $.trim(total_breaks).split(' ');
        var row_size = row_arr.length;
        if(row_size  = 0)
        {
          row_size = 1;
        }
        else
        {
          row_size = row_arr.length;
        }
        switch($(this).attr('id'))
        {
        case 'breaks_0_0':
          $('#breaks_0_0').css({'width': '40px', 'height': (row_size*25) + 'px'});
          break;
        case 'breaks_1_0':
          $('#breaks_1_0').css({'width': '40px', 'height': (row_size*25) + 'px'});
          break;
        case 'breaks_2_0':
          $('#breaks_2_0').css({'width': '40px', 'height': (row_size*25) + 'px'});
          break;
        case 'breaks_3_0':
          $('#breaks_3_0').css({'width': '40px', 'height': (row_size*25) + 'px'});
          break;

        } 
      }
    });
    $('#all_breaks').val(''); // clean all breaks input box on modal
    $("#break_value" ).val(''); // reset break input box to empty
    $('#BreaksModal').modal('hide');
  });

  $('#add_player').click(function(){
    $.fn.displayplayers();
    $('#myPlayers').modal('show');
  });

  //check if both teams entered before approval page is displayed
  $('#submit_approval').click(function(){
      var currForm = $.fn.getcurrentarray($('#type').val());
      var existingForm;
      var currForm;
      var home_team = '<?= $team ?>';
      var away_team = '<?= $opposition ?>';
      var round = $("#round" ).val();
      var year = $("#year" ).val();
      var season = $("#season" ).val();
      var team_grade = $('#team_grade').val();
      var title = '<?= $title ?>';
      $.ajax({
      url:"<?= $url ?>/check_two_teams.php?home=" + home_team + "&away=" + away_team + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round,
       success : function(data){
        if(data == 'true')
        {
          $.ajax({
            url:"<?= $url ?>/check_scoresheet_change.php?home=" + home_team + "&away=" + away_team + "&year=" + year + "&grade=" + $('#team_grade').val() + "&season=" + season + "&round=" + $('#round').val() + "&title=" + title,
            method: 'GET',
            success : function(data){
              var obj = jQuery.parseJSON(data);
              if($('#player_0').val() == 'Team Forfeit')
              {
                currForm = obj;
              }
              else
              {
                currForm = $.fn.getcurrentarray($('#type').val());
              }
              console.log("Existing Approval fn " + obj);
              console.log("Current Approval fn " + currForm);
              if($.trim(currForm) == $.trim(obj))
              {
                $("#approval").submit();
              }
              else
              {
                if(confirm("You have unsaved data, do you wish to continue to the Opposition Page?") == true)
                {
                  $("#approval").submit();
                }
              }
            },
          });
        }
        else
        {
          alert("Both Teams need to be entered!"); 
        }
      }
    });
  });

  //declare function to display player to player matchups in modal box
  $('#matchups').click(function(){
    $('#playertoplayer').empty();
    var PlayerCount = $('#no_of_players').val();
    var players = (PlayerCount/2);
    var home_player;
    var away_player;
    var home_team = '<?= $session_home ?>';
    var away_team = '<?= $session_away ?>';
    var round = $("#round" ).val();
    var year = $("#year" ).val();
    var season = $("#season" ).val();
    var team_grade = $('#team_grade').val();
    $.ajax({
      url:"<?= $url ?>/get_playertoplayer.php?home=" + home_team + "&away=" + away_team + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round,
      success : function(data){
        obj = jQuery.parseJSON(data);
        var players = (obj.length/2);
        output = '';
        output += ("<table class='table table-striped table-bordered dt-responsive nowrap display'>");
        output += ("<tr>");
        output += ("<td align=center>" + home_team + "</td>");
        output += ("<td align='center'>V</td>");
        output += ("<td align=center>" + away_team + "</td>");
        output += ("</tr>");
        output += ("<tr>");
        output += ("<td align=center>&nbsp;</td>");
        output += ("<td align='center'>Position</td>");
        output += ("<td align=center>&nbsp;</td>");
        output += ("</tr>");
        for(k = 0; k < players; k++)
        {
          output += ("<tr>");
          output += ("<td align='center' valign='top' style='width:250px' id='home_" + k + "'></td>");
          output += ("<td align='center' valign='top'>" + (k+1) + "</td>");
          output += ("<td align='center' valign='top' style='width:250px' id='away_" + (k+players) + "'></td>");
          output += ("</tr>");
        }
        output += ("<tr>"); 
        output += ("<td colspan=3 align=center>&nbsp;</td>"); 
        output += ("</tr>"); 
        output += ("</table>"); 
        $($.parseHTML(output)).appendTo('#playertoplayer');
        for(k = 0; k < players; k++)
        {
            $("#home_" + k).html(obj[k]);
        }
        for(k = players; k < obj.length; k++)
        {
            $("#away_" + k).html(obj[k]);
        }
      },
      error: function (request, error) {
        output = ("Both Teams need to be entered!"); 
        $($.parseHTML(output)).appendTo('#playertoplayer');
      }
    });
    $('#PlayerToPlayerModal').modal('show');
  });
 
  $('#clear_content').click(function(){ 
    $('#add').empty();
  });

  $('[data-dismiss=modal]').on('click', function (e){
    $('#add').empty();
  });

  var availableTags = <?php echo $player_data; ?>;
  $("#tags").autocomplete({
    source:  availableTags,
    appendTo: "#autocompleteAppendToMe"
  });

  $('#populate_positions').click(function(){
    $.fn.clearplayerscores();
    var team_grade = $('#team_grade').val();
    var team = '<?php echo $team; ?>';
    var name_array = [];
    $.ajax({
      url:"<?= $url ?>/get_player_count.php?clubname=" + team + "&year=" + <?php echo($_SESSION['year']); ?> + "&TeamGrade=" + $('#team_grade').val(),
      success : function(response){
        no_of_players = response;
        var k = 0;
        for(j = 0; j < no_of_players; j++)
        {
          for(i = 0; i < 20; i++) // expected max number of players to choose from
          {
            if($("input[id='position_r" + j + "_c" + i  + "']").is(":checked"))
            {
              var fullname = $.trim($('#fullname' + j).html());
              var memberID = $('#memberID' + j).val();
              name_array = fullname.split(" ");
              //alert(name_array.length);
              var firstname = name_array[0];
              var lastname = '';
              for (name = 1; name < name_array.length; name++)
              {
                lastname += name_array[name] + " ";
              }
              //alert("Array Value " + lastname);
              //var lastname = name_array[1];
              //firstname = $('#firstname' + j).val();
              //lastname = $('#lastname' + j).val();
              var teamID = $('#team_id' + j).val();
              $("textarea[name='player_" + (i-1) + "']").val(fullname);
              $("input[id='member_id_" + (i-1) + "']").val(memberID);
              $("input[id='firstname_" + (i-1) + "']").val(firstname);
              $("input[id='lastname_" + (i-1) + "']").val(lastname);
              //alert("Input Value " + $('#lastname_' + (i-1)).val());
              $("input[id='team_id_" + (i-1) + "']").val(teamID);
              /*
              if(fullname == "Player Forfeit")
              {
                $("input[id='score_" + (i-1) + "_0']").val(-1);
                $("input[id='score_" + (i-1) + "_1']").val(-1);
                $("input[id='score_" + (i-1) + "_2']").val(-1);
              }
              */
              k++;
            }
          }
          $('#no_of_players').val(k);
        }
        if(k == 0)
        {
          alert("No Players have been selected");
        }
        else
        {
          $('#add').empty();
          $('#myPlayers').modal('hide');
        }
      }
    });
  });

  $.fn.clearplayerscores = function () {
    event.preventDefault();
    var no_of_players = $('#no_of_players').val();
    var type = $('#type').val();
    for(j = 0; j < no_of_players; j++)
    {
      // remove existing players from tbl_scoresheet
      if(type == "Snooker")
      {
        for(i = 0; i < 7; i++)
        {
          $("textarea[name='player_" + i + "']").val('');
          $("input[id='win_" + j + "_" + i  + "']").prop("checked", false);
          $("input[id='score_" + j + "_" + i  + "']").val(0);
          $("input[id='breaks_" + j + "_" + i  + "']").val('');
        }
      }
      else if(type == 'Billiards')
      {
        $("textarea[name='player_" + i + "']").val('');
        $("input[id='win_" + j + "_0']").prop("checked", false);
        $("input[id='score_" + j + "_0']").val(0);
        $("input[id='breaks_" + j + "_0']").val('');
      }
    }
    $("#savebutton").attr('disabled', false);
    $("#getopposition").attr('disabled', false);
    $("#submit_approval").attr('disabled', false);
    $("input[id='points']").val(0);
    $("input[id='wins']").val(0);
  }

  $('#savebutton').click(function()
  {
    var grade = $('#grade').val();
    var type = $('#type').val();
    var resultdata = new Array;
    var scoredata = new Array;
    var scoredata_player = new Array;
    var year = <?php echo $_SESSION['year']; ?>;
    var season = '<?php echo $_SESSION['season']; ?>';
    if(type == 'Snooker')
    {
      var no_of_games = <?php echo($no_of_games+1); ?>;
    }
    else if(type == 'Billiards')
    {
      var no_of_games = <?php echo($no_of_games); ?>;
    }
    //var no_of_games = <?php echo($no_of_games); ?>;
    var no_of_players = $('#no_of_players').val();
    var draw = 0;
    var win = 0;
    var players_selected = 0;
    var no_of_zeros = 0;
    var team = '<?php echo($team); ?>';
    var opposition = '<?php echo($opposition); ?>';
    var title = '<?= $title ?>';
    // check if any players entered
    for(var x = 0; x < no_of_players; x++)
    {
      if($('#player_' + x).val() != '')
      {
        players_selected++;
      }
    }
    if(players_selected == 0)
    {
      alert('No players selected');
      return;
    }
    for(var j = 0; j < no_of_players; j++)
    {
      var play_pos = (j+1);
      for (var i = 0; i < (no_of_games); i++) 
      {
        if($('#tier_' + j).val() == '0.0')
        {
          no_of_zeros++;
        }
        scoredata_player[i] = 
        $('#player_' + j).val() + ", " + 
        grade + ", " + 
        type + ", " + 
        $('#round').val() + ", " + 
        team + ", " + 
        opposition + ", " + 
        $('#playing_date').val() + ", " + 
        win + ", " + 
        $('#score_' + j + '_' + i).val() + ", " + 
        $('#breaks_' + j + '_' + i).val() + ", " + 
        play_pos + ", " + 
        $('#id_' + j).val() + ", " + 
        $('#member_id_' + j).val() + ", " + 
        $('#team_grade').val()+ ", " + 
        $('#no_of_players').val() + ", " + 
        $('#firstname_' + j).val() + ", " + 
        $('#lastname_' + j).val() + ", " + 
        $('#team_id_' + j).val() + ", " + 
        $('#tier_' + j).val() + ", " + 
        draw;
        scoredata = [].concat(scoredata, scoredata_player[i]);
      }
    }

    if((no_of_zeros != 0) && (type == 'Billiards'))
    {
      alert("Warning: Some/All tier fields are zero's");
      return;
    }
    /*
    if(type == 'Billiards')
    {
      if(no_of_zeros != 0)
      {
        alert("Warning: Some/All tier fields are zero's");
        return;
      }
    }
    */
    
    resultdata = $('#points').val() + ", " + $('#wins').val() + ", " + $('#draws').val() + ", " + $('#grade').val();
    var resultdata = JSON.stringify(resultdata);
    var scoredata = JSON.stringify(scoredata);
    console.log("Score Data " + scoredata);
    $.ajax({
      url:"<?= $url ?>/check_scoresheet_change.php?home=" + team + "&away=" + opposition + "&year=" + year + "&grade=" + $('#team_grade').val() + "&season=" + season + "&round=" + $('#round').val() + "&title=" + title,
      method: 'GET',
      success : function(data){
        //alert(data);
        var obj = jQuery.parseJSON(data);
        var existingForm = obj;
        //$.fn.pagerefresh($('#type').val());
        var currForm = $.fn.getcurrentarray($('#type').val());
        console.log("Existing Save " + existingForm);
        console.log("Current Save " + currForm);
        
        if(existingForm == currForm)
        {
          alert("No changes have been made!");
          return;
        }     
        else
        {
          console.log('Select Query ' + "<?= $url ?>/save_scores.php?FixtureDate=" + $('#playing_date').val() + "&Grade=" + $('#grade').val() + "&Year=" + year + "&Season=" + season + "&PackedScoreData=" + scoredata + "&PackedResultData=" + resultdata + "&RoundNo=" + $("#round" ).val() + "&HomeTeam=" + team + "&AwayTeam=" + opposition + "&TeamGrade=" + $('#team_grade').val() + "&Type=" + $('#type').val());
          //alert("Ready to save");
          $.ajax({
            url:"<?= $url ?>/save_scores.php?FixtureDate=" + $('#playing_date').val() + "&Grade=" + $('#grade').val() + "&Year=" + year + "&Season=" + season + "&PackedScoreData=" + scoredata + "&PackedResultData=" + resultdata + "&RoundNo=" + $("#round" ).val() + "&HomeTeam=" + team + "&AwayTeam=" + opposition + "&TeamGrade=" + $('#team_grade').val() + "&Type=" + $('#type').val(),
            success : function(response)
            {
              //existingForm = $.fn.getcurrentarray($('#type').val());
              alert(response);
              //$.fn.pagerefresh($('#type').val());
              //location.reload();
            },
            error: function (request, error) 
            {
              alert("No data saved!");
            }
          });
        }
      }
    });
  });

  $('#go_to_opposition').click(function(){
    var currForm = $.fn.getcurrentarray($('#type').val());
    var existingForm;
    var team_scoring = $('#team_scoring').val();
    if(team_scoring == 'away')
    {
      $('#team_scoring').val('home');
    }
    else if(team_scoring == 'home')
    {
      $('#team_scoring').val('away');
    }
    var year = <?php echo $_SESSION['year']; ?>;
    var season = '<?php echo $_SESSION['season']; ?>';
    var team_to_edit = '<?= $team ?>';
    var opposition = '<?= $opposition ?>';
    var round = <?= $round ?>;
    var team_grade = '<?= $team_grade ?>';
    var type = $('#type').val();
    var dateplayed = '<?= $_POST['FixtureDate'] ?>';
    var title = '<?= $title ?>';
    $.ajax({
      url:"<?= $url ?>/check_two_teams.php?home=" + team_to_edit + "&away=" + opposition + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round,
      success : function(data){
        if((data == 'true') || ('<?= $_SESSION['login_rights'] ?>' == 'Administrator'))
        {
          $.ajax({
            url:"<?= $url ?>/check_scoresheet_change.php?home=" + team_to_edit + "&away=" + opposition + "&year=" + year + "&grade=" + $('#team_grade').val() + "&season=" + season + "&round=" + $('#round').val() + "&title=" + title,
            method: 'GET',
            success : function(data){
              //alert(data);
              var obj = jQuery.parseJSON(data);
              currForm = $.fn.getcurrentarray(type);
              if($('#player_0').val() == 'Team Forfeit')
              {
                currForm = obj;
              }
              else
              {
                currForm = $.fn.getcurrentarray(type);
              }
              console.log("Existing Opposition (Obj) fn " + obj);
              console.log("Current Opposition (currForm) fn " + currForm);
              if($.trim(currForm) != $.trim(obj))
              {
                if(confirm("You have unsaved data, do you wish to continue to the Opposition Page?") == true)
                {
                  $("#authorise").submit();
                }
                else
                {
                  return;
                }
              }
              else
              {
                $("#authorise").submit();
              }
            },
          });
        }
        else
        {
          alert("Both Teams need to be entered!"); 
        }
      }
    });
  });

});
</script>

<!-- Select Playing Positions Modal -->
<div class="modal fade" id="myPlayers" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ui-front">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Players (<?php echo($team); ?>)</h4>
            </div>
            <div class="modal-body">
              <div></div>
              <br>
              <div class='text-center ui-widget'>
              <label for='tags'>Enter name in this field to add players:&nbsp;&nbsp;</label>
              <input id='tags'>
              <br>
              <div id='autocompleteAppendToMe'></div>
              <br>
              <div>If player is listed in the drop down, select it, if not, you will be requested to provide player details.</div>
              <br>
              <div><a class='btn btn-primary btn-xs' id='newplayer'>Add to List</a>
              <br>
              <div></div>
              </div>
              <input type='hidden' id='temp_first'>
              <input type='hidden' id='temp_last'>
              <br>
              <div></div>
              </div>
              <br>
              <div id='add'></div>
              <br>
              <div class='text-center'><a class='btn btn-primary btn-xs' id='populate_positions'>Populate Playing Positions</a>
              </div>
              <br>
              <div class='text-center'><i>This action will clear any existing scores.</i></div>
              <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Input Breaks Modal -->
<div class="modal fade" id="BreaksModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Breaks</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id='breakid'></div>
        <br>
        <div class='text-center ui-widget'>
          <br>
          <div class='text-center ui-widget'>
            <div><b>Add Breaks for this player</b></div>
            <br>
            <br>
            <div>
              <center>
              <table class='table table-striped table-bordered'  style='width:350px;'>
                <tr>
                  <td>1</td>
                  <td>2</td>
                  <td>3</td>
                  <td>4</td>
                  <td>5</td>
                  <td>6</td>
                </tr>
                <tr>
                  <td><input type='text' id='brk1' style='width:40px; height:20px'></td>
                  <td><input type='text' id='brk2' style='width:40px; height:20px'></td>
                  <td><input type='text' id='brk3' style='width:40px; height:20px'></td>
                  <td><input type='text' id='brk4' style='width:40px; height:20px'></td>
                  <td><input type='text' id='brk5' style='width:40px; height:20px'></td>
                  <td><input type='text' id='brk6' style='width:40px; height:20px'></td>
                </tr>
              </table>
            </center>
            </div>
            <br>
            <div><a class='btn btn-primary btn-xs' id='new_break'>Add to Scoresheet</a></div>
            <input type='hidden' id='break_value'>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Player Matchups Modal -->
<div class="modal fade" id="PlayerToPlayerModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Player to Player Matchups</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <br>
        <div class='text-center ui-widget'>
          <div id="playertoplayer"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Emergency Player Modal -->
<div class="modal fade" id="EmPlayer" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Player Name</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
          <tr>
            <td>Firstname:</td><td><input type='text' id='em_first' style='width:200px; text-transform: capitalize;' placeholder="Firstname"></td>
          <tr>
          </tr>
            <td>Lastname:</td><td><input type='text' id='em_last' style='width:200px; text-transform: capitalize;'></td>
          <tr>
          </tr>
            <td>Email Address:</td><td><input type='text' id='em_email' style='width:200px;'></td>
          </tr>
          </tr>
            <td>Mobile No.:</td><td><input type='text' id='em_mobile' style='width:200px;'></td>
          </tr>
          </tr>
            <td>Have they ever played VBSA Pennant before?</td><td><input type='checkbox' id='em_previous' style='width:200px;'></td>
          </tr>
          <tr>
            <td colspan=2><div id='add_previous'></div></td>
          </tr>
          </tr>
        </table>
        <div class='text-center'>All boxes should be filled.</div>
        <br>
        <div class='text-center'>You must now lodge an online VBSA Membership form for this new player.  See <a href='https://www.vbsa.org.au/VBSA/vbsa_pol_proc.php' target="_blank">https://www.vbsa.org.au/VBSA/vbsa_pol_proc.php.</a></div>
        <br>
        <br>
        <div class='text-center'><a class='btn btn-primary btn-xs' id='em_player'>Add to List</a></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php 
include('footer.php'); 
?>
