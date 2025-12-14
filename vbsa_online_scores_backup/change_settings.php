<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include("header.php");
include("connection.inc");
include("php_functions.php"); 

// on page load.
if ((isset($_POST['Year']) and $_POST['Year'] <> '') and (isset($_POST['Season']) and $_POST['Season'] <> '')) 
{
  $season = $_POST['Season'];
  $year = $_POST['Year'];
}
else
{
  $season = $_SESSION['season'];
  $year = $_SESSION['year'];
}

if ($_POST['ButtonName'] == "SaveSettings") {

  $settingdata = [];
  $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
  $settingdata = explode(", ", $packeddata);

  $sql = "Select * FROM tbl_settings where season = '" . $_POST['Season'] . "' and year = " . $_POST['Year'];
  $result_rows = $dbcnx_client->query($sql) or die("Couldn't execute settings query. " . mysqli_error($dbcnx_client));
  $num_of_rows = $result_rows->num_rows;
  if ($num_of_rows > 0){
    $sql = "Update tbl_settings SET 
    no_of_fixtures_AB = " . $settingdata[0] . ", " . "
    no_of_fixtures_BB = " . $settingdata[1] . ", " . "
    no_of_fixtures_CB = " . $settingdata[2] . ", " . "
    no_of_fixtures_DB = " . $settingdata[3] . ", " . "
    no_of_fixtures_AS = " . $settingdata[4] . ", " . "
    no_of_fixtures_BS = " . $settingdata[5] . ", " . "
    no_of_fixtures_CS = " . $settingdata[6] . ", " . "
    no_of_fixtures_DS = " . $settingdata[7] . ", " . "
    no_of_rounds_AB = " . $settingdata[8] . ", " . "
    no_of_rounds_BB = " . $settingdata[9] . ", " . "
    no_of_rounds_CB = " . $settingdata[10] . ", " . "
    no_of_rounds_DB = " . $settingdata[11] . ", " . "
    no_of_rounds_AS = " . $settingdata[12] . ", " . "
    no_of_rounds_BS = " . $settingdata[13] . ", " . "
    no_of_rounds_CS = " . $settingdata[14] . ", " . "
    no_of_rounds_DS = " . $settingdata[15] . ", " . "
    no_of_players_AB = " . $settingdata[16] . ", " . "
    no_of_players_BB = " . $settingdata[17] . ", " . "
    no_of_players_CB = " . $settingdata[18] . ", " . "
    no_of_players_DB = " . $settingdata[19] . ", " . "
    no_of_players_AS = " . $settingdata[20] . ", " . "
    no_of_players_BS = " . $settingdata[21] . ", " . "
    no_of_players_CS = " . $settingdata[22] . ", " . "
    no_of_players_DS = " . $settingdata[23] . ", " . "
    no_of_games_AB = " . $settingdata[24] . ", " . "
    no_of_games_BB = " . $settingdata[25] . ", " . "
    no_of_games_CB = " . $settingdata[26] . ", " . "
    no_of_games_DB = " . $settingdata[27] . ", " . "
    no_of_games_AS = " . $settingdata[28] . ", " . "
    no_of_games_BS = " . $settingdata[29] . ", " . "
    no_of_games_CS = " . $settingdata[30] . ", " . "
    no_of_games_DS = " . $settingdata[31] . "
    Where season = '" . $_POST['Season'] . "' and year = " . $_POST['Year'];
    $update = $dbcnx_client->query($sql);
    if(!$update )
    {
      die("Could not update settings data: " . mysqli_error($dbcnx_client));
    } 
  }
  else
  {
      $sql = "Insert into tbl_settings (
      no_of_fixtures_AB,
      no_of_fixtures_BB,
      no_of_fixtures_CB,
      no_of_fixtures_DB,
      no_of_fixtures_AS,
      no_of_fixtures_BS,
      no_of_fixtures_CS,
      no_of_fixtures_DS,
      no_of_rounds_AB,
      no_of_rounds_BB,
      no_of_rounds_CB,
      no_of_rounds_DB,
      no_of_rounds_AS,
      no_of_rounds_BS,
      no_of_rounds_CS,
      no_of_rounds_DS,
      no_of_players_AB,
      no_of_players_BB,
      no_of_players_CB,
      no_of_players_DB,
      no_of_players_AS,
      no_of_players_BS,
      no_of_players_CS,
      no_of_players_DS,
      no_of_games_AB,
      no_of_games_BB,
      no_of_games_CB,
      no_of_games_DB,
      no_of_games_AS,
      no_of_games_BS,
      no_of_games_CS,
      no_of_games_DS,
      year,
      season
    ) VALUES (
    " . $settingdata[0] . ", 
    " . $settingdata[1] . ", 
    " . $settingdata[2] . ", 
    " . $settingdata[3] . ", 
    " . $settingdata[4] . ", 
    " . $settingdata[5] . ", 
    " . $settingdata[6] . ", 
    " . $settingdata[7] . ", 
    " . $settingdata[8] . ", 
    " . $settingdata[9] . ", 
    " . $settingdata[10] . ", 
    " . $settingdata[11] . ", 
    " . $settingdata[12] . ", 
    " . $settingdata[13] . ", 
    " . $settingdata[14] . ", 
    " . $settingdata[15] . ", 
    " . $settingdata[16] . ", 
    " . $settingdata[17] . ", 
    " . $settingdata[18] . ", 
    " . $settingdata[19] . ", 
    " . $settingdata[20] . ", 
    " . $settingdata[21] . ", 
    " . $settingdata[22] . ", 
    " . $settingdata[23] . ", 
    " . $settingdata[24] . ", 
    " . $settingdata[25] . ", 
    " . $settingdata[26] . ", 
    " . $settingdata[27] . ", 
    " . $settingdata[28] . ", 
    " . $settingdata[29] . ", 
    " . $settingdata[30] . ", 
    " . $settingdata[31] . ", 
    " . $_POST['Year'] . ", 
    '" . $_POST['Season'] . "'
    )";
    $update = $dbcnx_client->query($sql);
    if(!$update )
    {
      die("Could not insert data into settings: " . mysqli_error($dbcnx_client));
    }
  }
}
?>
<script type="text/javaScript">

function GetYearData(year) {
  var current_year = year.options[year.selectedIndex].value;
  document.settings.Year.value = current_year;
  document.settings.Season.value = document.getElementById("season").value;
  document.settings.submit();
}

function GetSeasonData(season) {
  var current_season = season.options[season.selectedIndex].value;
  document.settings.Season.value = current_season;
  document.settings.Year.value = document.getElementById("year").value;
  document.settings.submit();
}

function SaveSettings() {
  document.getElementById('submit_button').focus();
  var transferdata = [];
  transferdata = 
    document.getElementById("gradeAB_fix").value + ", " + 
    document.getElementById("gradeBB_fix").value + ", " + 
    document.getElementById("gradeCB_fix").value + ", " + 
    document.getElementById("gradeDB_fix").value + ", " + 
    document.getElementById("gradeAS_fix").value + ", " + 
    document.getElementById("gradeBS_fix").value + ", " + 
    document.getElementById("gradeCS_fix").value + ", " + 
    document.getElementById("gradeDS_fix").value + ", " + 
    document.getElementById("gradeAB_rounds").value + ", " + 
    document.getElementById("gradeBB_rounds").value + ", " + 
    document.getElementById("gradeCB_rounds").value + ", " + 
    document.getElementById("gradeDB_rounds").value + ", " + 
    document.getElementById("gradeAS_rounds").value + ", " + 
    document.getElementById("gradeBS_rounds").value + ", " + 
    document.getElementById("gradeCS_rounds").value + ", " + 
    document.getElementById("gradeDS_rounds").value + ", " + 
    document.getElementById("gradeAB_players").value + ", " + 
    document.getElementById("gradeBB_players").value + ", " + 
    document.getElementById("gradeCB_players").value + ", " + 
    document.getElementById("gradeDB_players").value + ", " + 
    document.getElementById("gradeAS_players").value + ", " + 
    document.getElementById("gradeBS_players").value + ", " + 
    document.getElementById("gradeCS_players").value + ", " + 
    document.getElementById("gradeDS_players").value + ", " + 
    document.getElementById("gradeAB_games").value + ", " + 
    document.getElementById("gradeBB_games").value + ", " + 
    document.getElementById("gradeCB_games").value + ", " + 
    document.getElementById("gradeDB_games").value + ", " + 
    document.getElementById("gradeAS_games").value + ", " + 
    document.getElementById("gradeBS_games").value + ", " + 
    document.getElementById("gradeCS_games").value + ", " + 
    document.getElementById("gradeDS_games").value;

  var data = JSON.stringify(transferdata);
  document.settings.PackedData.value = data;
  document.settings.Season.value = document.getElementById("season").value;
  document.settings.Year.value = document.getElementById("year").value;
  document.settings.ButtonName.value = "SaveSettings"; 
  document.settings.submit();
} 

</script>
<?php
// load data from database on load.
$sql = "Select * from tbl_settings where season = '" . $season . "' and year = " . $year . " Order By season";
$result = $dbcnx_client->query($sql) or die("Couldn't execute query (1). " . mysqli_error($dbcnx_client));
$build_data = $result->fetch_assoc();

?>
<center>
<form name="settings" method="post" action="change_settings.php">
<input type="hidden" name="Season">
<input type="hidden" name="Year">
<input type="hidden" name="ButtonName">
<input type="hidden" name="PackedData">
<center>

<table class='table table-striped table-bordered dt-responsive nowrap display' width='90%'>
  <tr>
      <td colspan=4 align=left><h1>Common settings.</h1></td>
  </tr>
  <tr>
    <td align='left'>Year</td>
    <td align='center'>
    <select id="year" onchange="GetYearData(this)">
            <option value='<?php echo($year); ?>' selected='selected'><?php echo($year); ?></option>
            <option value='2022'>2022</option>
            <option value='2023'>2023</option>
            <option value='2024'>2024</option>
            <option value='2025'>2025</option>
            <option value='2026'>2026</option>
    </select>
    </td>
    <td align='left'>Season</td>
    <td align='center'>
    <select id="season" onchange="GetSeasonData(this)">
            <option value='<?php echo($season); ?>' selected='selected'><?php echo($season); ?></option>
            <option value='S1'>S1</option>
            <option value='S2'>S2</option>
    </select>
    </td>
  </tr>
  <table class='table table-striped table-bordered dt-responsive nowrap display' width='90%'>
  	<tr>
      	<td colspan=9 align=left><h1>Grade settings.</h1></td>
  	</tr>
    <tr> 
        <td>&nbsp;</td>
        <td colspan=4 align="center"><b>Billiards</b></td>
        <td colspan=4 align="center"><b>Snooker</b></td>
    </tr> 
    <?php
      echo("<tr> ");
      echo("<td align='center'>&nbsp;</td>");
      echo("<td align='center'>Grade A</td>");
      echo("<td align='center'>Grade B</td>");
      echo("<td align='center'>Grade C</td>");
      echo("<td align='center'>Grade D</td>");
      echo("<td align='center'>Grade A</td>");
      echo("<td align='center'>Grade B</td>");
      echo("<td align='center'>Grade C</td>");
      echo("<td align='center'>Grade D</td>");
      echo("</tr> ");
      echo("<tr> ");
      echo("<td align='left'>No of Matches</td>");
      echo("<td align='center'><input id='gradeAB_fix' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeBB_fix' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeCB_fix' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeDB_fix' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeAS_fix' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeBS_fix' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeCS_fix' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeDS_fix' type='text' style='width : 30px;'></td>");
      echo("</tr> ");
      echo("<tr> ");
      echo("<td align='left'>No of Rounds</td>");
      echo("<td align='center'><input id='gradeAB_rounds' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeBB_rounds' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeCB_rounds' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeDB_rounds' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeAS_rounds' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeBS_rounds' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeCS_rounds' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeDS_rounds' type='text' style='width : 30px;'></td>");
      echo("</tr>");
      echo("<tr> ");
      echo("<td align='left'>No of Players</td>");
      echo("<td align='center'><input id='gradeAB_players' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeBB_players' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeCB_players' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeDB_players' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeAS_players' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeBS_players' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeCS_players' type='text' style='width : 30px;'></td>");
      echo("<td align='center'><input id='gradeDS_players' type='text' style='width : 30px;'></td>");
      echo("</tr>");
      echo("<tr> ");
      echo("<td align='left'>Games per round</td>");
      echo("<td align='center'><input id='gradeAB_games' type='text' style='width : 30px;' disabled></td>");
      echo("<td align='center'><input id='gradeBB_games' type='text' style='width : 30px;' disabled></td>");
      echo("<td align='center'><input id='gradeCB_games' type='text' style='width : 30px;' disabled></td>");
      echo("<td align='center'><input id='gradeDB_games' type='text' style='width : 30px;' disabled></td>");
      echo("<td align='center'><input id='gradeAS_games' type='text' style='width : 30px;' disabled></td>");
      echo("<td align='center'><input id='gradeBS_games' type='text' style='width : 30px;' disabled></td>");
      echo("<td align='center'><input id='gradeCS_games' type='text' style='width : 30px;' disabled></td>");
      echo("<td align='center'><input id='gradeDS_games' type='text' style='width : 30px;' disabled></td>");
      echo("</tr>");
      echo("</table>");
      echo("</tr>");

      if (!isset($build_data['no_of_fixtures_AB'])) { // setup default as 0
        echo("<script type='text/javaScript'>");

        echo("document.getElementById('gradeAB_fix').defaultValue = 0;");
        echo("document.getElementById('gradeBB_fix').defaultValue = 0;");
        echo("document.getElementById('gradeCB_fix').defaultValue = 0;");
        echo("document.getElementById('gradeDB_fix').defaultValue = 0;");
        echo("document.getElementById('gradeAS_fix').defaultValue = 0;");
        echo("document.getElementById('gradeBS_fix').defaultValue = 0;");
        echo("document.getElementById('gradeCS_fix').defaultValue = 0;");
        echo("document.getElementById('gradeDS_fix').defaultValue = 0;");

        echo("document.getElementById('gradeAB_rounds').defaultValue = 0;");
        echo("document.getElementById('gradeBB_rounds').defaultValue = 0;");
        echo("document.getElementById('gradeCB_rounds').defaultValue = 0;");
        echo("document.getElementById('gradeDB_rounds').defaultValue = 0;");
        echo("document.getElementById('gradeAS_rounds').defaultValue = 0;");
        echo("document.getElementById('gradeBS_rounds').defaultValue = 0;");
        echo("document.getElementById('gradeCS_rounds').defaultValue = 0;");
        echo("document.getElementById('gradeDS_rounds').defaultValue = 0;");

        echo("document.getElementById('gradeAB_players').defaultValue = 0;");
        echo("document.getElementById('gradeBB_players').defaultValue = 0;");
        echo("document.getElementById('gradeCB_players').defaultValue = 0;");
        echo("document.getElementById('gradeDB_players').defaultValue = 0;");
        echo("document.getElementById('gradeAS_players').defaultValue = 0;");
        echo("document.getElementById('gradeBS_players').defaultValue = 0;");
        echo("document.getElementById('gradeCS_players').defaultValue = 0;");
        echo("document.getElementById('gradeDS_players').defaultValue = 0;");

        echo("document.getElementById('gradeAB_games').defaultValue = 0;");
        echo("document.getElementById('gradeBB_games').defaultValue = 0;");
        echo("document.getElementById('gradeCB_games').defaultValue = 0;");
        echo("document.getElementById('gradeDB_games').defaultValue = 0;");
        echo("document.getElementById('gradeAS_games').defaultValue = 0;");
        echo("document.getElementById('gradeBS_games').defaultValue = 0;");
        echo("document.getElementById('gradeCS_games').defaultValue = 0;");
        echo("document.getElementById('gradeDS_games').defaultValue = 0;");

        echo("</script>");
      }
      else
      {
        echo("<script type='text/javaScript'>");

        echo("document.getElementById('gradeAB_fix').value = '" . $build_data['no_of_fixtures_AB'] . "';");
        echo("document.getElementById('gradeBB_fix').value = '" . $build_data['no_of_fixtures_BB'] . "';");
        echo("document.getElementById('gradeCB_fix').value = '" . $build_data['no_of_fixtures_CB'] . "';");
        echo("document.getElementById('gradeDB_fix').value = '" . $build_data['no_of_fixtures_DB'] . "';");
        echo("document.getElementById('gradeAS_fix').value = '" . $build_data['no_of_fixtures_AS'] . "';");
        echo("document.getElementById('gradeBS_fix').value = '" . $build_data['no_of_fixtures_BS'] . "';");
        echo("document.getElementById('gradeCS_fix').value = '" . $build_data['no_of_fixtures_CS'] . "';");
        echo("document.getElementById('gradeDS_fix').value = '" . $build_data['no_of_fixtures_DS'] . "';");

        echo("document.getElementById('gradeAB_rounds').value = '" . $build_data['no_of_rounds_AB'] . "';");
        echo("document.getElementById('gradeBB_rounds').value = '" . $build_data['no_of_rounds_BB'] . "';");
        echo("document.getElementById('gradeCB_rounds').value = '" . $build_data['no_of_rounds_CB'] . "';");
        echo("document.getElementById('gradeDB_rounds').value = '" . $build_data['no_of_rounds_DB'] . "';");
        echo("document.getElementById('gradeAS_rounds').value = '" . $build_data['no_of_rounds_AS'] . "';");
        echo("document.getElementById('gradeBS_rounds').value = '" . $build_data['no_of_rounds_BS'] . "';");
        echo("document.getElementById('gradeCS_rounds').value = '" . $build_data['no_of_rounds_CS'] . "';");
        echo("document.getElementById('gradeDS_rounds').value = '" . $build_data['no_of_rounds_DS'] . "';");

        echo("document.getElementById('gradeAB_players').value = '" . $build_data['no_of_players_AB'] . "';");
        echo("document.getElementById('gradeBB_players').value = '" . $build_data['no_of_players_BB'] . "';");
        echo("document.getElementById('gradeCB_players').value = '" . $build_data['no_of_players_CB'] . "';");
        echo("document.getElementById('gradeDB_players').value = '" . $build_data['no_of_players_DB'] . "';");
        echo("document.getElementById('gradeAS_players').value = '" . $build_data['no_of_players_AS'] . "';");
        echo("document.getElementById('gradeBS_players').value = '" . $build_data['no_of_players_BS'] . "';");
        echo("document.getElementById('gradeCS_players').value = '" . $build_data['no_of_players_CS'] . "';");
        echo("document.getElementById('gradeDS_players').value = '" . $build_data['no_of_players_DS'] . "';");

        echo("document.getElementById('gradeAB_games').value = '" . $build_data['no_of_games_AB'] . "';");
        echo("document.getElementById('gradeBB_games').value = '" . $build_data['no_of_games_BB'] . "';");
        echo("document.getElementById('gradeCB_games').value = '" . $build_data['no_of_games_CB'] . "';");
        echo("document.getElementById('gradeDB_games').value = '" . $build_data['no_of_games_DB'] . "';");
        echo("document.getElementById('gradeAS_games').value = '" . $build_data['no_of_games_AS'] . "';");
        echo("document.getElementById('gradeBS_games').value = '" . $build_data['no_of_games_BS'] . "';");
        echo("document.getElementById('gradeCS_games').value = '" . $build_data['no_of_games_CS'] . "';");
        echo("document.getElementById('gradeDS_games').value = '" . $build_data['no_of_games_DS'] . "';");

        echo("</script>");
      } 
  ?>
  </table>
</table>   
<div class='text-center'>
    <a class='btn btn-primary btn-xs' id='submit_button' href="javascript:;" onclick="SaveSettings();">Save Settings</a>
</div>
</form>
</center>  
<?php
include("footer.php"); 
?>