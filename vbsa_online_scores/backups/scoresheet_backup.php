<?php 

include('connection.inc');
include('header.php'); 
include('php_functions.php'); 

session_start();

// data from select fixture page
if($_POST['Season'] != '')
{
  $season = $_POST['Season'];
  $current_year  = $_POST['Year'];
  $team = $_POST['HomeTeam'];
  $opposition = $_POST['AwayTeam'];
  $round = $_POST['RoundNo'];
  $grade = $_POST['Grade'];
  $type = $_POST['Type'];
  $playing_date = $_POST['FixtureDate'];
  $scoring_team = $_POST['TeamScoring'];
  $team_grade = $_POST['TeamGrade'];
}

else // data from get opposition page
{
  $season = $_GET['Season'];
  $current_year  = $_GET['Year'];
  $team = $_GET['HomeTeam'];
  $opposition = $_GET['AwayTeam'];
  $round = $_GET['RoundNo'];
  $grade = $_GET['Grade'];
  $type = $_GET['Type'];
  $playing_date = $_GET['FixtureDate'];
  $scoring_team = $_GET['TeamScoring'];
  $team_grade = $_GET['TeamGrade'];
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
//$season = $_SESSION[['season'];
//$current_year  = $_SESSION[['year'];
$clubname = $_SESSION['clubname'];

// data from captain approval page
if(isset($_POST['TeamtoEdit']))
{
  $team = $_POST['TeamtoEdit'];
  $opposition = $_POST['OppositiontoEdit'];
}
else
{
  $team = $_POST['HomeTeam'];
  $opposition = $_POST['AwayTeam'];
}

echo("Scoring Team (from select fixtures) " . $scoring_team . "<br>");

echo("Home Team (from session) " . $_SESSION['home'] . "<br>");
echo("Team Edit (from captains approval) " . $_POST['TeamtoEdit'] . "<br>");

echo("Away Team (from session) " . $_SESSION['away'] . "<br>");
echo("Opposition Edit (from captains approval) " . $_POST['OppositiontoEdit'] . "<br>");

echo("Team " . $team . "<br>");
echo("Opposition " . $opposition . "<br>");

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

echo("function fillelementarray() {");
  $sql = "Select * from tbl_scoresheet where team = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year . " Order By playing_position";
  $result_scoresheet = $dbcnx_client->query($sql) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
  $j = 0;
  while ($build_data = $result_scoresheet->fetch_assoc()) 
  {
    echo("document.getElementById('year').innerHTML = '" . $build_data['year'] . "';");
    echo("document.getElementById('round').innerHTML = '" . $build_data['round'] . "';");
    echo("document.getElementById('season').innerHTML = '" . $build_data['season'] . "';");
    echo("document.getElementById('team').innerHTML = '" . $build_data['team'] . "';");
    echo("document.getElementById('opposition').innerHTML = '" . $build_data['opposition'] . "';");
    echo("document.getElementById('type').innerHTML = '" . $build_data['type'] . "';");
    echo("document.getElementById('grade').innerHTML = '" . $build_data['grade'] . "';");
    echo("document.getElementById('playing_date').innerHTML = '" . $build_data['playing_date'] . "';");
    echo("document.getElementById('id_" . $j . "').value = " . $build_data['id'] . ";");
    echo("document.getElementById('player_" . $j . "').value = '" . $build_data['players_name'] . "';");
    echo("document.getElementById('firstname_" . $j . "').value = '" . $build_data['firstname'] . "';");
    echo("document.getElementById('lastname_" . $j . "').value = '" . $build_data['lastname'] . "';");
    echo("document.getElementById('member_id_" . $j . "').value = '" . $build_data['memberID'] . "';");
    echo("document.getElementById('team_id_" . $j . "').value = '" . $build_data['team_id'] . "';");

    for ($i = 0; $i < $no_of_games; $i++) 
    {   
      if($type == 'Snooker')
      {
        if($build_data['win_' . ($i+1)] == 1)
        {
          echo("document.getElementById('win_" . $j . "_" . $i . "').checked = true;");
        }
        else
        {
          echo("document.getElementById('win_" . $j . "_" . $i . "').checked = false;");
        }
        echo("document.getElementById('draw_" . $j . "').value = '0';");
      }
      elseif($type == 'Billiards')
      {

        if($build_data['win_' . ($i+1)] == 1)
        {
          echo("if(document.getElementById('result_" . $j . "_" . $i . "').value == 'win')");
          echo("{");
          echo("  document.getElementById('result_" . $j . "_" . $i . "').checked = true;");
          echo("};");
        }
      
        if($build_data['draw_' . $j] == 1)
        {
          echo("if(document.getElementById('result_" . $j . "_" . $i . "').value == 'draw')");
          echo("{");
          echo("  document.getElementById('result_" . $j . "_" . $i . "').checked = true;)");
          echo("};");
        }
      }
      echo("document.getElementById('score_" . $j . "_" . $i . "').value = '" . $build_data['score_' . ($i+1)] . "';");
      echo("document.getElementById('breaks_" . $j . "_" . $i . "').value = '" . $build_data['break_' . ($i+1)] . "';");
    }
    $j++;
  // get club points/games won data
  $sql_club = "Select * from tbl_club_results where club = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year;
  $result_club = $dbcnx_client->query($sql_club) or die("Couldn't execute club query. " . mysqli_error($dbcnx_client));
  $build_data_club = $result_club->fetch_assoc();
  echo("document.getElementById('points').value = '" . $build_data_club['overall_points'] . "';");
  echo("document.getElementById('wins').value = '" . $build_data_club['games_won'] . "';");
  echo("document.getElementById('no_of_players').value = " . $j . ";");
}
$result_scoresheet->free_result();
//$result_club->free_result();
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
    $players[] = $build_data['FirstName'] . ", " . $build_data['LastName']; 
  }
  $player_data = json_encode($players);
}
$result_players->free_result();

?>
<script type='text/javascript'>
window.onload = function() 
{
  fillelementarray();
  UpdateGamesWonTotals();
}

function GetOpposition() 
{
  if(document.authorise.TeamScoring.value == 'away')
  {
    document.authorise.TeamScoring.value = 'home';
  }
  else if(document.authorise.TeamScoring.value == 'home')
  {
    document.authorise.TeamScoring.value = 'away';
  }
  /*
  if(document.getElementById('player_0').value == '')
  {
    alert("No Players/Scores have been saved!");
    return;
  }
  */
  document.authorise.Year.value = <?php echo($current_year); ?>; 
  document.authorise.Season.value = '<?= $season ?>'; 
  document.authorise.HomeTeam.value = '<?= $opposition ?>'; 
  document.authorise.AwayTeam.value = '<?= $team ?>';

  document.authorise.TeamtoEdit.value = '<?= $opposition ?>'; 
  document.authorise.OppositiontoEdit.value = '<?= $team ?>';

  document.authorise.RoundNo.value = <?= $round ?>; 
  document.authorise.TeamGrade.value = '<?= $team_grade ?>'; 
  document.authorise.Type.value = '<?= $type ?>'; 
  document.authorise.DatePlayed.value = '<?= $_POST['FixtureDate'] ?>'; 
  document.authorise.action = 'scoresheet.php';
  document.authorise.submit();
}

/*
function ApprovalButton()
{
  if(document.approval.TeamScoring.value == 'away')
  {
    document.approval.TeamScoring.value = 'home';
  }
  else if(document.approval.TeamScoring.value == 'home')
  {
    document.approval.TeamScoring.value = 'away';
  }

  document.approval.Round.value = <?= $round ?>; 
  document.approval.Year.value = <?= $current_year ?>; 
  document.approval.Grade.value = '<?= $grade ?>'; 
  document.approval.TeamGrade.value = '<?= $_POST['TeamGrade'] ?>'; 
  document.approval.Type.value = '<?= $type ?>'; 
  document.approval.Season.value = '<?= $season ?>'; 
  document.approval.HomeTeam.value = '<?= $team ?>'; 
  document.approval.AwayTeam.value = '<?= $opposition ?>'; 
  document.approval.DatePlayed.value = '<?= $_POST['FixtureDate'] ?>'; 
  document.approval.submit();
}
*/

function CheckInt()
{
  var score_input = document.getElementsByTagName("input");
  for (var i = 0; i < score_input.length; i++) 
  {
    if (score_input[i].type == "text") 
    {
      if((score_input[i].id.substring(0,5) == 'score') && (isNaN(score_input[i].value)))
      {
        alert("Score needs to be a number");
        score_input[i].value = 0;
        return;
      }
    }
  }
}

function UpdateGamesWonTotals() 
{
  var wins = 0;
  var draw = 0;
  var overall_points = 0;
  var checkboxes = document.getElementsByTagName("input");
  for (var i = 0; i < checkboxes.length; i++) 
  {
      if (checkboxes[i].type == "checkbox") 
      {
          var isChecked = checkboxes[i].checked;
          if(isChecked)
          {
            if(checkboxes[i].id.substring(0,3) == 'win')
            {
              wins++;
            }
          }
      }
      if (checkboxes[i].type == "radio") 
      {
          var isChecked = checkboxes[i].checked;
          if((isChecked) && (checkboxes[i].value == 'win'))
          {
            if(checkboxes[i].id.substring(0,6) == 'result')
            {
              wins++;
            }
          }
          if((isChecked) && (checkboxes[i].value == 'draw'))
          {
            if(checkboxes[i].id.substring(0,6) == 'result')
            {
              draw++;
            }
          }
      }
  }
  document.getElementById("wins").value = wins;
  var no_of_games = <?php echo($no_of_games); ?>;
  var no_of_players = document.getElementById("no_of_players").value;
  if(document.getElementById("type").value == 'Snooker')
  {
    if(wins > (no_of_players*no_of_games)/2)
    {
      overall_points = 4;
    }
    else if(wins < (no_of_players*no_of_games)/2)
    {
      overall_points = 0;
    }
    else if(wins = (no_of_players*no_of_games)/2)
    {
      overall_points = 2;
    }
  }
  else if(document.getElementById("type").value == 'Billiards')
  {
    document.getElementById("draws").value = draw;
    total_wins = parseInt(wins*2);
    overall_points = parseInt(total_wins+draw);
  }
  document.getElementById("points").value = overall_points;
}

</script>
<!--Content--> 
<form name="authorise" method="post" action='scoresheet.php'>
<input type="hidden" name="Grade" id="grade" value="<?php echo($_POST['Grade']); ?>" />
<input type="hidden" name="Type" id="type" value="<?php echo($_POST['Type']); ?>" />
<input type="hidden" name="RoundNo" id="round" value="<?php echo($_POST['RoundNo']); ?>" />
<input type="hidden" name="HomeTeam" id="team" value="<?php echo($_POST['HomeTeam']); ?>" />
<input type="hidden" name="AwayTeam" id="opposition" value="<?php echo($_POST['AwayTeam']); ?>" />
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
            <td class='text-center'><b><?php echo($_POST['RoundNo']); ?></b></td>  
            <td class='text-center'><b><?php echo($_POST['Type']); ?></b></td>
            <td class='text-center'><b><?php echo($_POST['FixtureDate']); ?></b></td>
            <td class='text-center'><b><?php echo($_POST['TeamGrade']); ?></b></td>  
          </tr>
        </table>
        <?php

        if($scoring_team == 'home')
        {
          $display_team = $_POST['HomeTeam'];
          $display_opposition = $_POST['AwayTeam'];
        }
        elseif($scoring_team == 'away')
        {
          $display_team = $_POST['AwayTeam'];
          $display_opposition = $_POST['HomeTeam'];
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
            <td class='text-left' colspan='10'><a class='btn btn-primary btn-xs' id='modal_ok'>Add Players to scoresheet</a></td>
            <!--<input type='text' id='arr_populate'>-->
          </tr>

          <tr> 
            <td colspan="5">Enter Scores for <?php echo($team); ?></td>
          </tr>
          <tr> 
            <td align='center'>No.</td>
            <td align='center'>Players Name</td>
            <td align='center'>Win</td>
            <td align='center'>Score</td>
            <td align='center'>Breaks</td>
        </tr>
        <?php
        for($i = 0; $i < $no_of_players; $i++) // no of players
        {
          //$j = 0; // no of games
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


          echo("<td rowspan='" . $no_of_games . "'><textarea rows='2' cols='1' wrap='soft' id='player_" . $i . "' name='player_" . $i . "' class='form-control input-sm' " . $readonly . "></textarea>");
          for($j = 0; $j < $no_of_games; $j++) // no of games
          {
            if($type == "Snooker")
            {
              echo("<td align=center><input type='checkbox' id='win_" . $i . "_" . $j . "' OnChange='UpdateGamesWonTotals();' " . $checkbox . "></td>");
              echo("<input type='hidden' id='draw_" . $i . "' name='draw_" . $i . "'>");
            }
            
            elseif($type == 'Billiards')
            {
              echo("<td align=center>Win&nbsp;&nbsp;<input type='radio' name='result_" . $i . "_" . $j . "' id='result_" . $i . "_" . $j . "' value='win' OnChange='UpdateGamesWonTotals();' " . $checkbox . "><br>");
              echo("Lose&nbsp;<input type='radio' name='result_" . $i . "_" . $j . "' id='result_" . $i . "_" . $j . "' value='lose' OnChange='UpdateGamesWonTotals();' checked><br>");
              echo("Draw&nbsp;<input type='radio' name='result_" . $i . "_" . $j . "' id='result_" . $i . "_" . $j . "' value='draw' OnChange='UpdateGamesWonTotals();' ></td>");
            }
            
            echo("<td align=center><input type='text' id='score_" . $i . "_" . $j . "' style='width:50px' class='form-control input-sm' " . $readonly . " OnChange='CheckInt();' tabindex=" . $no_of_games . "></td>");
            echo("<td align=center><input type='text' id='breaks_" . $i . "_" . $j . "' style='width:50px' class='form-control input-sm break' " . $readonly . " tabindex=" . ($no_of_games+3) . "></td>");
            echo("</tr>");
            echo("<tr>");
          }
          if(($i == 0) and ($type == 'Snooker')) // add 4th frame for finals if required
          {
            echo("<tr>");
            echo("<td style='background-color: black; color: white; text-align: center;'></td>");
            echo("<td align=center style='background-color: black; color: white;'>4th Frame (Finals Only)</td>");
            echo("<td align=center style='background-color: gray; color: white;'><input type='checkbox' id='win_" . $i . "_3' OnChange='UpdateGamesWonTotals();' " . $checkbox . "></td>");
            echo("<td align=center style='background-color: gray; color: white;'><input type='text' id='score_" . $i . "_3' style='width:50px' class='form-control input-sm' " . $readonly . " OnChange='CheckInt();'></td>");
            echo("<td align=center style='background-color: gray; color: white;'><input type='text' id='breaks_" . $i . "_3' style='width:50px' class='form-control input-sm break' " . $readonly . "></td>");
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
        ?>
      </table>
      <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
        <tr> 
          <td class='text-left'><b>Overall Points:</b></td>
          <td class='text-center'><input type='text' id='points' style='width:50px' class='form-control input-sm' readonly></td>
        
        <?php
        if($type == 'Snooker')
          {
            echo("<input type='hidden' id='draws' value=0>");
            echo("<td class='text-left'><b>Games Won:</b></td>");
            echo("<td class='text-center'><input type='text' id='wins' style='width:50px' class='form-control input-sm' readonly></td>");
            echo("</tr>");
            echo("<tr>"); 
            echo("<td class='text-left'colspan='4'>* (For each Win 4: Draw 2: Loss 0)</td>");
            echo("</tr>");
          }
          elseif($type == 'Billiards')
          {
            echo("<td class='text-left'><b>Games Won:</b></td>");
            echo("<td class='text-center'><input type='text' id='wins' style='width:50px' class='form-control input-sm' readonly></td>");
            echo("<td class='text-left'><b>Games Drawn:</b></td>");
            echo("<td class='text-center'><input type='text' id='draws' style='width:50px' class='form-control input-sm' readonly></td>");
            echo("</tr>");
            echo("<tr>");
            echo("<td class='text-left'colspan='4'>* (For each Win 2: Draw 1: Loss 0)</td>");
            echo("</tr>");
          }
        ?>
      </table>
      </div>
    </div>
  </div>
</div>

<br>
<br> 
<?php 

$sql_approve = "Select * from tbl_scoresheet where team = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_year . " Order By playing_position";
//echo($sql_approve . "<br>");
$result_approve = $dbcnx_client->query($sql_approve) or die("Couldn't execute approve query. " . mysqli_error($dbcnx_client));
while($build_data_approve = $result_approve->fetch_assoc())
{
  $capt_home = $build_data_approve['capt_home'];
  $capt_away = $build_data_approve['capt_away'];
}
if(($capt_home == 1) && ($capt_away == 1))
{
  $disabled = " disabled";
}
/*
if(isset($_POST['TeamtoEdit']))
{
   //echo("Disable buttons<br>");
   $disable_edit = " disabled";
}
*/

if($_SESSION['login_rights'] == 'Administrator')
{
   $disabled = " ";
   //$disable_edit = " ";
}

?>
<br>
<div> 
  <div class='text-center'>
    <a class='btn btn-primary btn-xs' href="javascript:;" id='savebutton' style='width:250px' <?php echo($disabled); ?>>Save Players/Scores</a>
  </div>
</div> 
<br>
<div> 
  <div class='text-center'>
    <a class='btn btn-primary btn-xs' href="javascript:;" onclick="GetOpposition();" id='getopposition'  style='width:250px' <?php echo($disabled); ?> >Goto Opposition Scoresheet</a>
  </div>
</div> 
<br>
<div> 
  <div class='text-center'>
    <a class='btn btn-primary btn-xs' id='matchups'  style='width:250px'>View Player to Player Match Ups</a>
  </div>
</div>
</form>
<form name="approval" id="approval" method="post" action="captain_approval.php">
<input type="hidden" name="Grade" id="grade" value="<?php echo($_POST['Grade']); ?>" />
<input type="hidden" name="Type" id="type" value="<?php echo($_POST['Type']); ?>" />
<input type="hidden" name="Round" id="round" value="<?php echo($_POST['RoundNo']); ?>" />
<!--<input type="hidden" name="HomeTeam" id="team" value="<?php echo($_POST['HomeTeam']); ?>" />
<input type="hidden" name="AwayTeam" id="opposition" value="<?php echo($_POST['AwayTeam']); ?>" />-->

<input type="hidden" name="TeamtoEdit" id="edit_team" value="<?php echo($_POST['TeamtoEdit']); ?>" />
<input type="hidden" name="OppositiontoEdit" id="edit_team_op" value="<?php echo($_POST['OppositiontoEdit']); ?>" />

<input type="hidden" name="HomeTeam" id="team" value="<?php echo($display_team); ?>" />
<input type="hidden" name="AwayTeam" id="opposition" value="<?php echo($display_opposition); ?>" />

<input type="hidden" name="DatePlayed" id="playing_date" value="<?php echo($_POST['FixtureDate']); ?>" />
<input type="hidden" name="TeamScoring" id="team_scoring" value="<?php echo($_POST['TeamScoring']); ?>" />
<input type="hidden" name="Year" id="year" value="<?php echo($_SESSION['year']); ?>" />
<input type="hidden" name="Season" id="season" value="<?php echo($_SESSION['season']); ?>" />
<input type="hidden" name="TeamGrade" id="team_grade" value="<?php echo($_POST['TeamGrade']); ?>" />
<br>
<div> 
  <div class='text-center'>
    <td colspan=8 align='center'><a class='btn btn-primary btn-xs' id='submit_approval' style='width:250px' <?php echo($disabled); ?> >Go to Approval Page</a>
    <!--<td colspan=8 align='center'><a class='btn btn-primary btn-xs' id='submit_approval' onclick="ApprovalButton();" style='width:250px' <?php echo($disabled); ?> >Go to Approval Page</a>-->
  </div>
</div> 
</form>
<br>
<br>
<script>
$(document).ready(function()
{
  //declare function to display players in modal box
  $.fn.displayplayers = function () {
    $('#add').empty();
    var team_name = $('#team_name').val();
    var PlayerCount = $('#no_of_players').val();
    //alert(PlayerCount);
    PlayerCount = 4;
    var PlayingDate = $('#playing_date').val();
    var team_grade = $('#team_grade').val();
    var obj = "";
    var fullname = "";
    var memberID = "";
    $.ajax({
      url:"<?= $url ?>/get_players.php?clubname=" + $('#team_name').val() + "&year=" + <?= $_SESSION['year'] ?> + "&TeamGrade=" + team_grade,
      success : function(data){
        obj = jQuery.parseJSON(data);
        //alert*obj[0];
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
    var team_name = $('#team_name').val();
    var firstname = $('#em_first').val();
    var surname = $('#em_last').val();
    var email = $('#em_email').val();
    var mobile = $('#em_mobile').val();
    var previous = $('#em_previous').val();
    var year = '<?php echo($_SESSION['year']); ?>';
    var season = '<?php echo($_SESSION['season']); ?>';
    var team_grade = $('#team_grade').val();
    var type = $('#type').val();
    $.ajax({
      url:"<?= $url ?>/add_new_player.php?clubname=" + $('#team_name').val() + "&firstname=" + firstname + "&surname=" + surname + "&team_grade=" + team_grade + "&type=" + type + "&year=" + year + "&season=" + season + "&email=" + email + "&mobile=" + mobile + "&previous=" + previous,
      method: 'POST',
      success:function(response)
      {
        $('#tags').val(''); 
        $('#no_of_players').val(<?php echo($no_of_players); ?>);
        $('#add').empty();
        $.fn.displayplayers();
      },
    });
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
    var team_name = $('#team_name').val();
    var new_player = $('#tags').val();
    var newarray = new_player.split(", ");
    var firstname = newarray[0];
    var surname = newarray[1];
    if((firstname == 'Emergency') && (surname == 'Player'))
    {
      $('#EmPlayer').modal('show');
      $('#em_player').click(function (event) {
        event.preventDefault();
        $.fn.add_emergency();
        $('#EmPlayer').modal('hide');
        $.fn.displayplayers();
      });
    }
    else
    {
      var year = '<?php echo($_SESSION['year']); ?>';
      var season = '<?php echo($_SESSION['season']); ?>';
      var team_grade = $('#team_grade').val();
      var type = $('#type').val();
      $.ajax({
        url:"<?= $url ?>/add_new_player.php?clubname=" + $('#team_name').val() + "&firstname=" + firstname + "&surname=" + surname + "&team_grade=" + team_grade + "&type=" + type + "&year=" + year + "&season=" + season,
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

  //min_break = 30;
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
      }
    });
    $('#all_breaks').val(''); // clean all breaks input box on modal
    $("#break_value" ).val(''); // reset break input box to empty
    $('#BreaksModal').modal('hide');
  });

  $('#modal_ok').click(function(){
    $.fn.displayplayers();
    $('#myModal').modal('show');
  });

  //check if both teams entered before approval page is displayed
  $('#submit_approval').click(function(){
      var home_team = '<?= $team ?>';
      var away_team = '<?= $opposition ?>';
      var round = $("#round" ).val();
      var year = $("#year" ).val();
      var season = $("#season" ).val();
      var team_grade = $('#team_grade').val();
      $.ajax({
      url:"<?= $url ?>/get_playertoplayer.php?home=" + home_team + "&away=" + away_team + "&year=" + year + "&grade=" + team_grade + "&season=" + season + "&round=" + round,
      success : function(data){
        obj = jQuery.parseJSON(data);
        $("#approval").submit();
      },
      error: function (request, error) {
        alert("Both Teams need to be entered!"); 
      }
    });
  });

  //declare function to display player to player matchups in modal box
  $('#matchups').click(function(){
    /*
    if($('#player_0').val(''));
    {
      alert("No Players/Scores have been saved!");
      return;
    }
    */
    $('#playertoplayer').empty();
    var PlayerCount = $('#no_of_players').val();
    var players = (PlayerCount/2);
    var home_player;
    var away_player;
    var home_team = '<?= $team ?>';
    var away_team = '<?= $opposition ?>';
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
    //alert(team_grade);
    $.ajax({
      url:"<?= $url ?>/get_player_count.php?clubname=" + $('#team_name').val() + "&year=" + <?php echo($_SESSION['year']); ?> + "&TeamGrade=" + $('#team_grade').val(),
      success : function(response){
        //alert(response);
        no_of_players = response;
        var k = 0;
        //alert(no_of_players);
        for(j = 0; j < no_of_players; j++)
        {
          for(i = 0; i < 20; i++) // expected max number of players to choose from
          {
            if($("input[id='position_r" + j + "_c" + i  + "']").is(":checked"))
            {
              fullname = $('#fullname' + j).html();
              memberID = $('#memberID' + j).val();
              firstname = $('#firstname' + j).val();
              lastname = $('#lastname' + j).val();
              teamID = $('#team_id' + j).val();
              $("textarea[name='player_" + (i-1) + "']").val(fullname);
              $("input[id='member_id_" + (i-1) + "']").val(memberID);
              $("input[id='firstname_" + (i-1) + "']").val(firstname);
              $("input[id='lastname_" + (i-1) + "']").val(lastname);
              $("input[id='team_id_" + (i-1) + "']").val(teamID);
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
          $('#myModal').modal('hide');
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

  $('#savebutton').click(function(){
    var grade = $('#grade').val();
    var type = $('#type').val();
    var resultdata = new Array;
    var scoredata = new Array;
    var scoredata_player = new Array;
    var year = <?php echo $_SESSION['year']; ?>;
    var season = '<?php echo $_SESSION['season']; ?>';
    var no_of_games = <?php echo($no_of_games); ?>;
    var no_of_players = $('#no_of_players').val();
    var draw = 0;
    var win = 0;
    var players_selected = 0;
    //team_split = $('#edit_team').val().split(",");
    //alert(team_split);
    //alert(team_split[0]);
    //alert(team_split[1]);
    //alert($('#edit_team').val());
    //alert($('#edit_team_op').val());
    if($('#edit_team').val() == '')
    {
      team = $('#team').val();
      opposition = $('#opposition').val();
    }
    else
    {
      team = $('#edit_team').val();
      opposition = $('#edit_team_op').val();
    }
    //alert(team + ", " + opposition);
    //alert($('#edit_team').val());
    //alert('<?php echo $_POST['TeamtoEdit']; ?>');
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
      for (var i = 0; i < no_of_games; i++) {
        if(type == 'Snooker')
        {
          if($('#win_' + j + '_' + i).is(":checked"))
          {
            win = 1;
          }
          else
          {
            win = 0;
          }
          draw = 0;
        }
        else if(type == 'Billiards')
        {
          if(($('#result_' + j + '_' + i).val() == 'win') && ($('#result_' + j + '_' + i).is(":checked")))
          {
            win = 1;
          }
          else
          {
            win = 0;
          }
          if(($('#result_' + j + '_' + i).val() == 'draw') && ($('#result_' + j + '_' + i).is(":checked")))
          {
            draw = 1;
          }
          else
          {
            draw = 0;
          }
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
        draw;
        scoredata = [].concat(scoredata, scoredata_player[i]);
      }
    }
    resultdata = $('#points').val() + ", " + $('#wins').val() + ", " + $('#draws').val();
    var resultdata = JSON.stringify(resultdata);
    var scoredata = JSON.stringify(scoredata);
    //alert(scoredata);
    //alert("save_scores.php?FixtureDate=" + $('#playing_date').val() + "&Grade=" + $('#grade').val() + "&Year=" + year + "&Season=" + season + "&PackedScoreData=" + scoredata + "&PackedResultData=" + resultdata + "&RoundNo=" + $("#round" ).val() + "&HomeTeam=" + $('#team').val() + "&TeamGrade=" + $('#team_grade').val() + "&Type=" + $('#type').val());
    $.ajax({
        url:"<?= $url ?>/save_scores.php?FixtureDate=" + $('#playing_date').val() + "&Grade=" + $('#grade').val() + "&Year=" + year + "&Season=" + season + "&PackedScoreData=" + scoredata + "&PackedResultData=" + resultdata + "&RoundNo=" + $("#round" ).val() + "&HomeTeam=" + $('#team').val() + "&TeamGrade=" + $('#team_grade').val() + "&Type=" + $('#type').val(),
        success : function(response){
          alert(response);
        },
          error: function (request, error) {
          alert("No data saved!");
        }
      });
  });
    
});
</script>
<?php
if(isset($_POST['HomeTeam']))
{
  $team_name = "(" . trim($_POST['HomeTeam']) . ")";
}
else
{
  $team_name = "";
}
echo("<input type='hidden' name='team_name' id='team_name' value='" . $_POST['HomeTeam'] . "'>");
?>
<!-- Select Playing Positions Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ui-front">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Players <?php echo($team_name); ?></h4>
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
            <td>Firstname:</td><td><input type='text' id='em_first' style='width:200px;'></td>
          <tr>
          </tr>
            <td>Lastname:</td><td><input type='text' id='em_last' style='width:200px;'></td>
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
