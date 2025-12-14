<?php

include ("connection.inc");
include ("header.php");
include ("php_functions.php");

$season = $_SESSION['season'];
$current_season = $_SESSION['year'];
$team = $_POST['HomeTeam'];
$opposition = $_POST['AwayTeam'];

if($_POST['ButtonName'] == "SaveData") 
{
  $sql_players = "Update tbl_scoresheet Set 
      capt_home = " . $_POST['Home_Approve'] . ", 
      capt_away = " . $_POST['Away_Approve'] . " 
      where team = '" . $_POST['HomeTeam'] . "' or team = '" . $_POST['AwayTeam'] . "'
      AND round = " . $_POST['Round'] . " AND season = '" . $_POST['Season'] . "' AND date_played = '" . MySqlDate($_POST['DatePlayed']) . "' AND year = " . $_POST['Year'];
    //echo("Save Approval - " . $sql_players . "<br>");
    $update = $dbcnx_client->query($sql_players);
    if(! $update )
    {
        die("Could not player update data: " . mysqli_error($dbcnx_client));
    } 
    echo "<script type=\"text/javascript\">"; 
    echo "alert('Records Updated!')"; 
    echo "</script>";
    if(($_POST['Home_Approve'] == 1) && ($_POST['Away_Approve'] == 1))
    {
      echo "<script type='text/javascript'>window.location = '" . $url . "/index.php'</script>";
    }
    //echo "<script type='text/javascript'>window.location = '" . $url . "/records_update.php'</script>";
}

if($_SESSION['login_rights'] == 'Administrator')
{
  //$home = "";
  //$away = "";
}

$type = $_POST['Type'];
$grade = $_POST['Grade'];
$date_played = $_POST['DatePlayed'];
$round = $_POST['Round'];
?>
<script>

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
  document.capt_auth.HomeTeam.value = '<?= $team ?>'; 
  document.capt_auth.AwayTeam.value = '<?= $opposition ?>'; 
  document.capt_auth.DatePlayed.value = '<?= $_POST['DatePlayed'] ?>'; 
  document.capt_auth.Home_Approve.value = home_ok; 
  document.capt_auth.Away_Approve.value = away_ok; 
  document.capt_auth.submit();
}

</script>

<script>
$(document).ready(function()
{
// get away team captain to enter email and password to activate checkbox
  $('#away_ok').click(function(event){
    event.preventDefault();
    var away_team_captain = '<?= $_SESSION['clubname'] ?>';
    var away_email = '<?= $_SESSION['username'] ?>';
    $('#email').html("Enter your password for " + away_email);
    $('#LoginModal').modal('show');
  });

  $('#approve').click(function(event){
    event.preventDefault();
    var password = $('#password').val();
    var away_email = '<?= $_SESSION['username'] ?>';
    $.ajax({
      url:"<?= $url ?>/get_login.php",
      method: 'POST',
      data:{
            username: away_email,
            password: password,
      },
      success:function(response)
      {
        $("input[id='away_ok']").prop("checked", true);
        $('#LoginModal').modal('hide');
      },
    });
  });

  $('#cancel').click(function(event){
    event.preventDefault();
    $("input[id='away_ok']").prop("checked", false);
    $('#LoginModal').modal('hide');
  });

});
</script>
<center>
<form name="capt_auth" method="post" action="captain_approval.php">
<input type="hidden" name="Round" value="" />
<input type="hidden" name="Year" value="" />
<input type="hidden" name="Season" value="" />
<input type="hidden" name="HomeTeam" value="" />
<input type="hidden" name="AwayTeam" value="" />
<input type="hidden" name="DatePlayed" />
<input type="hidden" name="Home_Approve" value="" />
<input type="hidden" name="Away_Approve" value="" />
<input type="hidden" name="PWD_Check" value="" />
<input type="hidden" name="PlayerID" value="" />
<input type="hidden" name="ButtonName" />
<input type="hidden" name="Team" />
<table class='table table-striped table-bordered dt-responsive nowrap display'>
  <tr>
   <td colspan=5 align="center"><b>Pennant Score Sheet Summary - <?= $current_season ?>. Season - <?= $season ?></b></td>
  </tr>
  <tr> 
    <td class='text-center'>Grade:&nbsp;<?= $grade ?></td>  
    <td class='text-center'>Game type:&nbsp;<?= $type ?></td>  
    <td></td>
    <td class='text-center'>Round No.&nbsp;<?= $round ?></td>  
    <td class='text-center'>Date&nbsp;<?= $date_played ?></td>
  </tr>
  
</table>



<table class='table table-striped table-bordered dt-responsive nowrap display'>
<tr>
<td colspan=11 align=center><?php echo($team); ?></td>
<td colspan=2 align='center'>V</td>
<td colspan=11 align=center><?php echo($opposition); ?></td>
</tr>
<tr> 
  <td colspan=4 align='center'>Player</td>
  <td colspan=4 align='center'>Win</td>
  <td colspan=4 align='center'>Loss</td>
  <td colspan=4 align='center'>Draw</td>
</tr>


<?php
$sql_match = "Select * from tbl_scoresheet where team = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season . " Order By playing_position";
//echo("SQL - " . $sql_match . "<br>");
$result_match = $dbcnx_client->query($sql_match) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$k = 0;
$i = 0;
$checkbox1 =' checked';
while ($build_match = $result_match->fetch_assoc())
{
    echo("<tr>");
    echo("<td colspan=12 id='player_" . $k . "' name='player_" . $k . "' >Player 1</td>");
     echo("<td align=center><div id='scores_" . $i . "_1'>" . $build_data['score_1'] . "</div>12</td>");
      echo("<td align=center><div id='scores_" . $i . "_2'>" . $build_data['score_2'] . "</div>23</td>");
      echo("<td align=center><div id='scores_" . $i . "_3'>" . $build_data['score_3'] . "</div>34</td>");
      echo("<td align=center><div id='scores_" . $i . "_4'>" . $build_data['score_4'] . "</div>45</td>");
      echo("<td align=center><div id='breaks_" . $i . "_1'>" . $build_data['break_1'] . "</div>21 34</td>");
      echo("<td align=center><div id='breaks_" . $i . "_2'>" . $build_data['break_2'] . "</div>45 76</td>");
      /*
    echo("<td colspan=12 id='player_" . $k . "' name='player_" . $k . "' >Player 2</td>");
    echo("</tr>");
    echo("<tr>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_1' " . $checkbox1 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_2' " . $checkbox2 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_3' " . $checkbox3 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_4' " . $checkbox4 . "></td>");
      echo("<td align=center><div id='scores_" . $i . "_1'>" . $build_data['score_1'] . "</div>12</td>");
      echo("<td align=center><div id='scores_" . $i . "_2'>" . $build_data['score_2'] . "</div>23</td>");
      echo("<td align=center><div id='scores_" . $i . "_3'>" . $build_data['score_3'] . "</div>34</td>");
      echo("<td align=center><div id='scores_" . $i . "_4'>" . $build_data['score_4'] . "</div>45</td>");
      echo("<td align=center><div id='breaks_" . $i . "_1'>" . $build_data['break_1'] . "</div>21 34</td>");
      echo("<td align=center><div id='breaks_" . $i . "_2'>" . $build_data['break_2'] . "</div>45 76</td>");
      echo("<td align=center><div id='breaks_" . $i . "_3'>" . $build_data['break_3'] . "</div>0</td>");
      echo("<td align=center><div id='breaks_" . $i . "_4'>" . $build_data['break_4'] . "</div>0</td>");
    
       echo("<td align=center><input type='checkbox' id='win_" . $i . "_1' " . $checkbox1 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_2' " . $checkbox2 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_3' " . $checkbox3 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_4' " . $checkbox4 . "></td>");
      echo("<td align=center><div id='scores_" . $i . "_1'>" . $build_data['score_1'] . "</div>12</td>");
      echo("<td align=center><div id='scores_" . $i . "_2'>" . $build_data['score_2'] . "</div>23</td>");
      echo("<td align=center><div id='scores_" . $i . "_3'>" . $build_data['score_3'] . "</div>34</td>");
      echo("<td align=center><div id='scores_" . $i . "_4'>" . $build_data['score_4'] . "</div>45</td>");
      echo("<td align=center><div id='breaks_" . $i . "_1'>" . $build_data['break_1'] . "</div>21 34</td>");
      echo("<td align=center><div id='breaks_" . $i . "_2'>" . $build_data['break_2'] . "</div>45 76</td>");
      echo("<td align=center><div id='breaks_" . $i . "_3'>" . $build_data['break_3'] . "</div>0</td>");
      echo("<td align=center><div id='breaks_" . $i . "_4'>" . $build_data['break_4'] . "</div>0</td>");
    */
      echo("</tr>");
    $k++;
    $i++;
}
?>
<tr> 
<td colspan=3 align=center>&nbsp;</td> 
</tr> 
</table>

<!--

<table class='table table-striped table-bordered dt-responsive nowrap display'>
    <tr> 
      <td colspan="13">Scores for <?php echo($team); ?>.</td>
    </tr>
    <tr> 
      <td rowspan=2 align='center'>No.</td>
      <td colspan=4 align='center'>Wins</td>
      <td colspan=4 align='center'>Scores</td>
      <td colspan=4 align='center'>Breaks</td>
    </tr>
    <tr> 
      <td align='center'>1</td>
      <td align='center'>2</td>
      <td align='center'>3</td>
      <td align='center'>4</td>
      <td align='center'>1</td>
      <td align='center'>2</td>
      <td align='center'>3</td>
      <td align='center'>4</td>
      <td align='center'>1</td>
      <td align='center'>2</td>
      <td align='center'>3</td>
      <td align='center'>4</td>
    </tr>
    <?php
    $sql = "Select * from tbl_scoresheet where team = '" . $team . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season . " Order By playing_position";
    $result_scoresheet = $dbcnx_client->query($sql) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
    $i = 0;
    while ($build_data = $result_scoresheet->fetch_assoc())
    {
      if($build_data['win_1'] == 1)
      {
        $checkbox1 = ' checked';
      }
      else
      {
        $checkbox1 = '';
      }
      if($build_data['win_2'] == 1)
      {
        $checkbox2 = ' checked';
      }
      else
      {
        $checkbox2 = '';
      }
      if($build_data['win_3'] == 1)
      {
        $checkbox3 = ' checked';
      }
      else
      {
        $checkbox3 = '';
      }
      if($build_data['win_4'] == 1)
      {
        $checkbox4 = ' checked';
      }
      else
      {
        $checkbox4 = '';
      }
      if($build_data['capt_home'] == 1)
      {
        $checkbox5 = ' checked';
      }
      else
      {
        $checkbox5 = '';
      }
      echo("<tr>");
      echo("<td rowspan=2 style='text-align: center;'>" . ($i+1) . "</td>");
      echo("<td colspan=12 id='player_" . $i . "' name='player_" . $i . "' >" . $build_data['players_name'] . "</td>");
      echo("</tr><tr>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_1' " . $checkbox1 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_2' " . $checkbox2 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_3' " . $checkbox3 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_4' " . $checkbox4 . "></td>");
      echo("<td align=center><div id='scores_" . $i . "_1'>" . $build_data['score_1'] . "</div></td>");
      echo("<td align=center><div id='scores_" . $i . "_2'>" . $build_data['score_2'] . "</div></td>");
      echo("<td align=center><div id='scores_" . $i . "_3'>" . $build_data['score_3'] . "</div></td>");
      echo("<td align=center><div id='scores_" . $i . "_4'>" . $build_data['score_4'] . "</div></td>");
      echo("<td align=center><div id='breaks_" . $i . "_1'>" . $build_data['break_1'] . "</div></td>");
      echo("<td align=center><div id='breaks_" . $i . "_2'>" . $build_data['break_2'] . "</div></td>");
      echo("<td align=center><div id='breaks_" . $i . "_3'>" . $build_data['break_3'] . "</div></td>");
      echo("<td align=center><div id='breaks_" . $i . "_4'>" . $build_data['break_4'] . "</div></td>");
      echo("</tr>");
      $i++;
    }
    //echo("<input type='hidden' id='no_of_players'>");
    ?>
</table>
<br>
<table class='table table-striped table-bordered dt-responsive nowrap display'>
    <tr> 
      <td colspan="13">Scores for <?php echo($opposition); ?>.</td>
    </tr>
    <tr> 
      <td rowspan=2 align='center'>No.</td>
      <td colspan=4 align='center'>Wins</td>
      <td colspan=4 align='center'>Scores</td>
      <td colspan=4 align='center'>Breaks</td>
    </tr>
    <tr> 
      <td align='center'>1</td>
      <td align='center'>2</td>
      <td align='center'>3</td>
      <td align='center'>4</td>
      <td align='center'>1</td>
      <td align='center'>2</td>
      <td align='center'>3</td>
      <td align='center'>4</td>
      <td align='center'>1</td>
      <td align='center'>2</td>
      <td align='center'>3</td>
      <td align='center'>4</td>
    </tr>
    <?php
    $sql = "Select * from tbl_scoresheet where team = '" . $opposition . "' AND round = " . $round . " AND season = '" . $season . "' AND year = " . $current_season . " Order By playing_position";
    $result_scoresheet = $dbcnx_client->query($sql) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
    $i = 0;
    while ($build_data = $result_scoresheet->fetch_assoc())
    {
      if($build_data['win_1'] == 1)
      {
        $checkbox1 = ' checked';
      }
      else
      {
        $checkbox1 = '';
      }
      if($build_data['win_2'] == 1)
      {
        $checkbox2 = ' checked';
      }
      else
      {
        $checkbox2 = '';
      }
      if($build_data['win_3'] == 1)
      {
        $checkbox3 = ' checked';
      }
      else
      {
        $checkbox3 = '';
      }
      if($build_data['win_4'] == 1)
      {
        $checkbox4 = ' checked';
      }
      else
      {
        $checkbox4 = '';
      }
      if($build_data['capt_away'] == 1)
      {
        $checkbox6 = ' checked';
      }
      else
      {
        $checkbox6 = '';
      }
      //echo("Box 5 - " . $checkbox5 . "<br>");
      //echo("Box 6 - " . $checkbox6 . "<br>");

      echo("<tr>");
      echo("<td rowspan=2 style='text-align: center;'>" . ($i+1) . "</td>");
      echo("<td colspan=12 id='player_" . $i . "' name='player_" . $i . "' >" . $build_data['players_name'] . "</td>");
      echo("</tr><tr>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_1' " . $checkbox1 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_2' " . $checkbox2 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_3' " . $checkbox3 . "></td>");
      echo("<td align=center><input type='checkbox' id='win_" . $i . "_4' " . $checkbox4 . "></td>");
      echo("<td align=center><div id='scores_" . $i . "_1'>" . $build_data['score_1'] . "</div></td>");
      echo("<td align=center><div id='scores_" . $i . "_2'>" . $build_data['score_2'] . "</div></td>");
      echo("<td align=center><div id='scores_" . $i . "_3'>" . $build_data['score_3'] . "</div></td>");
      echo("<td align=center><div id='scores_" . $i . "_4'>" . $build_data['score_4'] . "</div></td>");
      echo("<td align=center><div id='breaks_" . $i . "_1'>" . $build_data['break_1'] . "</div></td>");
      echo("<td align=center><div id='breaks_" . $i . "_2'>" . $build_data['break_2'] . "</div></td>");
      echo("<td align=center><div id='breaks_" . $i . "_3'>" . $build_data['break_3'] . "</div></td>");
      echo("<td align=center><div id='breaks_" . $i . "_4'>" . $build_data['break_4'] . "</div></td>");
      echo("</tr>");
      $i++;
    }
    echo("<input type='hidden' id='no_of_players'>");
    ?>
</table>
<br>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
  <tr> 
    <td class='text-center'><b>Home Captain Approve:</b></td>
    <td class='text-center'><input type='checkbox' id='home_ok' <?=  $checkbox5  ?> <?php echo($home_ok); ?> ></td>
    <td class='text-center'><b>Away Captain Approve:</b></td>
    <td class='text-center'><input type='checkbox' id='away_ok'  <?=  $checkbox6  ?> <?php echo($away_ok); ?> ></td>
  </tr>
</table>
-->
<?php 
if(!$checkbox5 || !$checkbox6)
{
?>
<div> 
  <div class='text-center'>
    <a class='btn btn-primary btn-xs' href="javascript:;" onclick="SaveApproval();">Save Approvals</a>
  </div>
</div> 
<?php
}
?>
</form>
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
                <a class='btn btn-default btn-xs'  href='<?= $url ?>/forgot.php?forgot=password'>Forgot Password?</a>
              </div>
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

