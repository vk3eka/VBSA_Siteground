<?php 

require_once('../Connections/connvbsa.php'); 
error_reporting(0);

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;    
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

$season = "-1";
if (isset($_GET['season'])) 
{
  $season = $_GET['season'];
}
elseif (isset($_POST['SeasonSettings'])) {
  $season = $_POST['SeasonSettings'];
}
//$season = 'S2';

if (isset($_POST['Year'])) 
{
  $year = $_POST['Year'];
}
else
{
  $year = date("Y");
}

// get start/end dates for team rego visbilty
mysql_select_db($database_connvbsa, $connvbsa);
$query_rego = "Select * from tbl_team_select_visible where season = '" . $season ."' and year = " . $year;
$grades_rego = mysql_query($query_rego, $connvbsa) or die(mysql_error());
$row_grades_rego = mysql_fetch_assoc($grades_rego);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades_fix = "Select * from Team_grade where season = '" . $season ."' and fix_cal_year = " . $year . "  AND current='Yes' Order By grade";
//echo($query_grades_fix . "<br>");
$grades_fix = mysql_query($query_grades_fix, $connvbsa) or die(mysql_error());
$row_grades_fix = mysql_fetch_assoc($grades_fix);
$totalRows_grades_fix = mysql_num_rows($grades_fix);
mysql_select_db($database_connvbsa, $connvbsa);

//$query_grades_arch = "Select DISTINCT grade, grade_name From Team_grade where fix_cal_year = 0 order by grade";
$query_grades_arch = "Select DISTINCT grade, grade_name From Team_grade where current = 0 order by grade";
/*
/*

$query_grades_arch = "Select DISTINCT grade,
grade_name,
season,
type,
RP,
min_breaks,
fix_upload,
fix_cal_year,
current,
finals_teams,
dayplayed,
grade_start_date,
no_of_matches,
no_of_rounds,
no_of_players,
games_round,
tier1_rp,
tier2_rp,
tier3_rp,
tier4_rp,
tier5_rp,
tier6_rp
From Team_grade where fix_cal_year = 0 Group by grade order by grade";
*/
$grades_arch = mysql_query($query_grades_arch, $connvbsa) or die(mysql_error());
$row_grades_arch = mysql_fetch_assoc($grades_arch);
$totalRows_grades_arch = mysql_num_rows($grades_arch);
//echo($totalRows_grades_arch . "<br>");

if(isset($_POST['ButtonName']) && ($_POST['ButtonName'] == "SaveSettings")) 
{
    $settingdata = [];
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    $settingdata = explode(":", $packeddata[0]);
    
    for($i = 0; $i < $_POST['Grades']; $i++)
    {
        if($settingdata[$i][7] == '')
        {
            $year = date("Y");
        }
        else
        {
            $year = $settingdata[$i][7];
        }
        //$year = date("Y");
        if($settingdata[$i][4] == 'No') // delete if not current
        {
          $sql_delete = "Delete FROM Team_grade where year = " . $settingdata[$i][7] . " and season = '" . $settingdata[$i][2] . "' and team_grade = '". $settingdata[$i][0] . "'";;
          $result_delete = $dbcnx_client->query($sql_delete);
        }
        $settingdata[$i] = explode(", ", $packeddata[$i]);
        // added tiers 7 to 12.....
        // added tiers -2 to 0.....
        $sql = "Update Team_grade SET 
        grade = '" . $settingdata[$i][0] . "', " . "
        grade_name = '" . $settingdata[$i][1] . "', " . "
        season = '" . $settingdata[$i][2] . "', " . "
        type = '" . $settingdata[$i][3] . "', " . "
        RP = " . $settingdata[$i][4] . ", " . "
        min_breaks = " . $settingdata[$i][5] . ", " . "
        fix_upload = '" . $settingdata[$i][6] . "', " . "
        fix_cal_year = " . $settingdata[$i][7] . ", " . "
        current = '" . $settingdata[$i][8] . "', " . "
        finals_teams = " . $settingdata[$i][9] . ", " . "
        dayplayed = '" . $settingdata[$i][10] . "', " . "
        no_of_matches = " . $settingdata[$i][11] . ", " . "
        no_of_rounds = " . $settingdata[$i][12] . ", " . "
        no_of_players = " . $settingdata[$i][13] . ", " . "
        games_round = " . $settingdata[$i][14] . ", " . "
        tier_2_rp = " . $settingdata[$i][15] . ", " . "
        tier_1_rp = " . $settingdata[$i][16] . ", " . "
        tier0_rp = " . $settingdata[$i][17] . ", " . "
        tier1_rp = " . $settingdata[$i][18] . ", " . "
        tier2_rp = " . $settingdata[$i][19] . ", " . "
        tier3_rp = " . $settingdata[$i][20] . ", " . "
        tier4_rp = " . $settingdata[$i][21] . ", " . "
        tier5_rp = " . $settingdata[$i][22] . ", " . "
        tier6_rp = " . $settingdata[$i][23] . ", " . "
        tier7_rp = " . $settingdata[$i][24] . ", " . "
        tier8_rp = " . $settingdata[$i][25] . ", " . "
        tier9_rp = " . $settingdata[$i][26] . ", " . "
        tier10_rp = " . $settingdata[$i][27] . ", " . "
        tier11_rp = " . $settingdata[$i][28] . ", " . "
        tier12_rp = " . $settingdata[$i][29] . ", " . "
        grade_start_date = '" . $settingdata[$i][30] . "' " . "
        Where id = " . $settingdata[$i][31];
        mysql_select_db($database_connvbsa, $connvbsa);
        echo($sql . "<br>");
        $update = mysql_query($sql, $connvbsa) or die(mysql_error());
        if(!$update )
        {
          die("Could not update grade settings data: " . mysqli_error($dbcnx_client));
        }
    }
    echo("<script type='text/javascript'>");
    echo("alert('Settings have been saved.');");
    echo("</script>");

    $insertGoTo = "team_grades.php?season=" . $season . "&year=" . $year;
    if (isset($_SERVER['QUERY_STRING'])) {
      $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}
//echo("Here<br>");
if(isset($_POST['ButtonName']) && ($_POST['ButtonName'] == "SaveArchSettings")) 
{
    $settingdata = [];
    $packeddata = json_decode(stripslashes($_POST['PackedArchData']), true);
    $settingdata = explode(":", $packeddata[0]);
    //echo($settingdata . "<br>");
    for($i = 0; $i < $_POST['Grades']; $i++)
    {
        if($settingdata[$i][3] == '')
        {
            $year = date("Y");
        }
        else
        {
            $year = $settingdata[$i][3];
        }
        //$year = date("Y");
        //echo($settingdata[$i][3] . "<br>");
        $settingdata[$i] = explode(", ", $packeddata[$i]);
        // added tier7 to 12 ............
        if($settingdata[$i][4] != '')
        {
          /*
          $sql = "Insert Into Team_grade ( 
          grade,
          grade_name,
          season,
          type,
          RP,
          min_breaks,
          fix_upload,
          fix_cal_year,
          current,
          finals_teams,
          dayplayed,
          no_of_matches,
          no_of_rounds,
          no_of_players,
          games_round,
          tier1_rp,
          tier2_rp,
          tier3_rp,
          tier4_rp,
          tier5_rp,
          tier6_rp,
          tier7_rp,
          tier8_rp,
          tier9_rp,
          tier10_rp,
          tier11_rp,
          tier12_rp,
          grade_start_date
          )
          Values (
          " . $settingdata[$i][0] . ", " . 
          $settingdata[$i][1] . ", " . 
          $settingdata[$i][2] . ", " . 
          $settingdata[$i][3] . ", " . 
          $settingdata[$i][4] . ", " . 
          $settingdata[$i][5] . ", '" . 
          $settingdata[$i][6] . "', " . 
          $settingdata[$i][7] . ", " . 
          $settingdata[$i][8] . ", " . 
          $settingdata[$i][9] . ", " . 
          $settingdata[$i][10] . ", " . 
          $settingdata[$i][11] . ", " . 
          $settingdata[$i][12] . ", " . 
          $settingdata[$i][13] . ", " . 
          $settingdata[$i][14] . ", " . 
          $settingdata[$i][15] . ", " . 
          $settingdata[$i][16] . ", " . 
          $settingdata[$i][17] . ", " . 
          $settingdata[$i][18] . ", " . 
          $settingdata[$i][19] . ", " . 
          $settingdata[$i][20] . ", " . 
          $settingdata[$i][21] . ", " . 
          $settingdata[$i][22] . ", " . 
          $settingdata[$i][23] . ", " . 
          $settingdata[$i][24] . ", " . 
          $settingdata[$i][25] . ", " . 
          $settingdata[$i][26] . ", '" . 
          $settingdata[$i][27] . "')"; 
          //echo($sql . "<br>");
          */
          $sql = "Insert Into Team_grade ( 
          grade,
          grade_name,
          season,
          fix_cal_year,
          current
          )
          Values (
          " . $settingdata[$i][0] . ", " . 
          $settingdata[$i][1] . ", " . 
          $settingdata[$i][2] . ", " . 
          $settingdata[$i][3] . ", " . 
          $settingdata[$i][4] . "')"; 
          //echo($sql . "<br>");

          mysql_select_db($database_connvbsa, $connvbsa);
          $update = mysql_query($sql, $connvbsa) or die(mysql_error());
          if(!$update )
          {
            die("Could not update grade settings data: " . mysqli_error($dbcnx_client));
          }
        }
    }
    echo("<script type='text/javascript'>");
    echo("alert('Grade have been added to Current.');");
    echo("</script>");
    $insertGoTo = "team_grades.php?season=" . $season . "&year=" . $year;
    if (isset($_SERVER['QUERY_STRING'])) {
      $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}

?>
<script type="text/javaScript">

function SaveSettings(no_of_grades) 
{
    var transferdata = {};
    for (var i = 0; i < no_of_grades; i++) { // get number of grades
       if (document.getElementById("current_" + i).checked === true) {
            current_chk = 'Yes';
            year = document.getElementById("year_" + i ).value;
        }
        else
        {
            current_chk = 'No';
            year = 0;
        }
        // added rank_points 7 to 12 ..............................
        // added rank_points -2 to 0 ..............................
        season = document.getElementById("season_" + i ).value;
        transferdata[i] = 
        document.getElementById("grade_" + i ).value + ", " + 
        document.getElementById("grade_name_" + i ).value + ", " + 
        season + ", " + 
        document.getElementById("type_" + i ).value + ", " + 
        document.getElementById("rank_points_" + i ).value + ", " + 
        document.getElementById("min_break_points_" + i ).value + ", " + 
        document.getElementById("fix_upload_" + i ).value + ", " + 
        year + ", " + 
        current_chk + ", " + 
        document.getElementById("finals_" + i ).value + ", " + 
        document.getElementById("day_played_" + i ).value + ", " + 
        document.getElementById("matches_" + i ).value + ", " + 
        document.getElementById("rounds_" + i ).value + ", " + 
        document.getElementById("players_" + i ).value + ", " + 
        document.getElementById("games_round_" + i ).value + ", " + 
        document.getElementById("rank_points_-2_" + i ).value + ", " + 
        document.getElementById("rank_points_-1_" + i ).value + ", " + 
        document.getElementById("rank_points_0_" + i ).value + ", " + 
        document.getElementById("rank_points_1_" + i ).value + ", " + 
        document.getElementById("rank_points_2_" + i ).value + ", " + 
        document.getElementById("rank_points_3_" + i ).value + ", " + 
        document.getElementById("rank_points_4_" + i ).value + ", " + 
        document.getElementById("rank_points_5_" + i ).value + ", " + 
        document.getElementById("rank_points_6_" + i ).value + ", " + 
        document.getElementById("rank_points_7_" + i ).value + ", " + 
        document.getElementById("rank_points_8_" + i ).value + ", " + 
        document.getElementById("rank_points_9_" + i ).value + ", " + 
        document.getElementById("rank_points_10_" + i ).value + ", " + 
        document.getElementById("rank_points_11_" + i ).value + ", " + 
        document.getElementById("rank_points_12_" + i ).value + ", " + 
        document.getElementById("start_" + i ).value + ", " + 
        document.getElementById("id_" + i ).value;
    }
    var data = JSON.stringify(transferdata);
    document.settings.PackedData.value = data;
    document.settings.SeasonSettings.value = season;
    document.settings.Year.value = year;
    document.settings.Grades.value = no_of_grades;
    document.settings.ButtonName.value = "SaveSettings"; 
    document.settings.submit();
}

function SaveArchSettings(no_of_grades) 
{
    year = <?= $year ?>;
    season = '<?= $season ?>';
    var transferdata = {};
    for (var i = 0; i < no_of_grades; i++) // get number of grades
    {
      if (document.getElementById("arch_current_" + i).checked === true) 
      {
        current_chk = 'Yes';
        transferdata[i] = "'" + 
        document.getElementById("arch_grade_" + i ).innerHTML + "', '" + 
        document.getElementById("arch_grade_name_" + i ).innerHTML + "', '" + 
        season + "', " + 
        //document.getElementById("arch_type_" + i ).innerHTML + "', " + 
        /*document.getElementById("arch_rank_points_" + i ).innerHTML + ", " + 
        document.getElementById("arch_min_break_points_" + i ).innerHTML + ", " + 
        document.getElementById("arch_fix_upload_" + i ).innerHTML + ", " + */
        year + ", '" + 
        current_chk;
        /*document.getElementById("arch_finals_" + i ).innerHTML + ", '" + 
        document.getElementById("arch_day_played_" + i ).value + "', " + 
        document.getElementById("arch_matches_" + i ).innerHTML + ", " + 
        document.getElementById("arch_rounds_" + i ).innerHTML + ", " + 
        document.getElementById("arch_players_" + i ).innerHTML + ", " + 
        document.getElementById("arch_games_round_" + i ).innerHTML + ", " + 
        document.getElementById("arch_rank_points_1_" + i ).innerHTML + ", " + 
        document.getElementById("arch_rank_points_2_" + i ).innerHTML + ", " + 
        document.getElementById("arch_rank_points_3_" + i ).innerHTML + ", " + 
        document.getElementById("arch_rank_points_4_" + i ).innerHTML + ", " + 
        document.getElementById("arch_rank_points_5_" + i ).innerHTML + ", " + 
        document.getElementById("arch_rank_points_6_" + i ).innerHTML + ", " + 
        document.getElementById("arch_id_" + i ).value;*/
      }
    }
    var data = JSON.stringify(transferdata);
    //alert(data);
    document.settings.PackedArchData.value = data;
    document.settings.SeasonSettings.value = season;
    document.settings.Year.value = year;
    document.settings.Grades.value = no_of_grades;
    document.settings.ButtonName.value = "SaveArchSettings"; 
    document.settings.submit();
}

function SelectSeasonButton()
{
  season_year = document.getElementById("season").value.split(",");
  document.season_change.SeasonSettings.value = season_year[1].trim();
  document.season_change.Year.value = season_year[0].trim();
  document.season_change.submit();
}

</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="../Admin_Calendar/calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcommon.js"></script>
<script src="../Admin_Calendar/calendar/codebase/dhtmlxcalendar.js"></script>
<script>

$(document).ready(function()
{
    $('#set_team_rego_visible').click(function(event)
    {
        event.preventDefault();
        var start_date = $('#startdate').val();
        var finish_date = $('#enddate').val();
        var year = <?= $year ?>;
        var season = '<?= $season ?>';
        $.ajax({
            url:"save_rego_visible.php?start=" + start_date + "&end=" + finish_date +"&year=" + year + "&season=" + season,
            method: 'POST',
            success : function(response)
            {
              //alert(response);
              alert('Rego Visible data updated');
            },
        });
        return;
    });

});

</script>
<script>

window.onload = function() {
  doOnLoad();
}

function doOnLoad() {
  var mystartdate;  
  mystartdate = new dhtmlXCalendarObject("startdate");
  mystartdate.setSkin('dhx_skyblue');
  mystartdate.hideTime();
  mystartdate.hideWeekNumbers();
  mystartdate.setDateFormat("%Y-%m-%d");

  var myenddate;  
  myenddate = new dhtmlXCalendarObject("enddate");
  myenddate.setSkin('dhx_skyblue');
  myenddate.hideTime();
  myenddate.hideWeekNumbers();
  myenddate.setDateFormat("%Y-%m-%d");

for(i = 0; i < 10; i++)
{
  var myGradestartdate;  
  myGradestartdate = new dhtmlXCalendarObject("start_" + i);
  myGradestartdate.setSkin('dhx_skyblue');
  myGradestartdate.hideTime();
  myGradestartdate.hideWeekNumbers();
  myGradestartdate.setDateFormat("%Y-%m-%d");
}
  

}
</script>
</head>
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<form name="season_change" id="season_change" method="post" action="team_grades.php">
<input type='hidden' name='Year'/>
<input type="hidden" name="SeasonSettings">
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="11" align="center" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" align="center" class="red_bold">IMPORTANT - Allocated ranking points must be set or rankings will not calculate</td>
  </tr>
  <tr>
    <td colspan="11" align="center">Note: Snooker Ranking points are allocated on a per frame won basis, Billiards ranking points are allocated on a per game won eg. Win =2 points x allocated ranking points</td>
  </tr>
  <tr>
    <td colspan="11" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" align="center"><span class="red_bold">GRADES fixtured for <?php echo $season; ?> of <?php echo $year; ?></td>
  </tr>
  <tr>
    <td colspan="5" align="right"><input type="text" name="startdate" id="startdate" value="<?= $row_grades_rego['start_date'] ?>" size="15" placeholder='Start Date'/></td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td colspan="5" align="left"><input type="text" name="enddate" id="enddate" value="<?= $row_grades_rego['finish_date'] ?>" size="15" placeholder='End Date'/></td>
  </tr>
  <tr>
    <td colspan="11" align="center" class="greenbg"><a href="" rel="facebox" id='set_team_rego_visible'>Make Grades Visible to Users. Team Rego only “open” between these dates</a></td>
  </tr>
  <tr>
    <td colspan="11" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" align="center" class="greenbg"><a href="user_files/team_grade_insert.php?season=<?php echo $season; ?>" rel="facebox">Insert a new grade</a></td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td colspan="5" align="center" class="greenbg"><a href="AA_scores_index_grades.php?season=<?php echo $season ?>">Return to <?php echo $season ?> ladders</a></td>
  </tr>
</table>
<table class='table' width='100%'>
<tr> 
<td align='center' valign='top'><b>Select Season:&nbsp;&nbsp; <select id='season' onchange="SelectSeasonButton()">
<?php
if(isset($season)) 
{
     echo("<option value='" . $year . ", " . $season . "' selected='selected'>" . $year . ", " . $season . "</option>"); 
}
else
{
     echo("<option value='' selected='selected'></option>");
}
echo("<option value=''>---------</option>");
echo("<option value='2023, S1'>2023, S1</option>");
echo("<option value='2023, S2'>2023, S2</option>");
echo("<option value='2024, S1'>2024, S1</option>");
echo("<option value='2024, S2'>2024, S2</option>");
echo("<option value='2025, S1'>2025, S1</option>");
echo("<option value='2025, S2'>2025, S2</option>");
echo("<option value='2025, S1'>2026, S1</option>");
echo("<option value='2025, S2'>2026, S2</option>");
?>
</select></b> 
</td> 
</tr> 
</table>
</form>

<form name="settings" id="settings" method="post" action="team_grades.php">
<input type="hidden" name="PackedData">
<input type="hidden" name="PackedArchData">
<input type="hidden" name="Grades">
<input type="hidden" name="Year">
<input type="hidden" name="SeasonSettings">
<input type="hidden" name="ButtonName">
<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%' border=1>
<thead> 
  <tr> 
    <th rowspan='2' class='text-center' style='width : 50px;'>Grade Code</th>
    <th rowspan='2' class='text-center' style='width : 150px;'>Grade Name</th>
    <th rowspan='2' class='text-center' style='width : 60px;'>Type</th>
    <th rowspan='2' class='text-center' style='width : 50px;'>Finals Teams</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>Day Played</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>Start Date</th>
    <th rowspan='2' class='text-center' style='width : 50px;'>Allocated Ranking Points (Snooker)</th>
    <th colspan='15' class='text-center' style='width : 50px;'>Allocated Ranking Points (Billiards)</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>Min. Break Points</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>No. of Matches</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>No. of Rounds (inc Finals)</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>No. of Players</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>Games per Match</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>Current</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>&nbsp;</th>
    <!--<th rowspan='2' class='text-center' style='width : 30px;'>&nbsp;</th>-->
  </tr>
  <tr>
    <th class='text-center' style='width : 30px;'>Tier -2</th>
    <th class='text-center' style='width : 30px;'>Tier -1</th>
    <th class='text-center' style='width : 30px;'>Tier 0</th>
    <th class='text-center' style='width : 30px;'>Tier 1</th>
    <th class='text-center' style='width : 30px;'>Tier 2</th>
    <th class='text-center' style='width : 30px;'>Tier 3</th>
    <th class='text-center' style='width : 30px;'>Tier 4</th>
    <th class='text-center' style='width : 30px;'>Tier 5</th>
    <th class='text-center' style='width : 30px;'>Tier 6</th>
    <th class='text-center' style='width : 30px;'>Tier 7</th>
    <th class='text-center' style='width : 30px;'>Tier 8</th>
    <th class='text-center' style='width : 30px;'>Tier 9</th>
    <th class='text-center' style='width : 30px;'>Tier 10</th>
    <th class='text-center' style='width : 30px;'>Tier 11</th>
    <th class='text-center' style='width : 30px;'>Tier 12</th>
  </tr>
</thead>
<tbody>
<?php 
$k = 0;
// added tiers 7 to 12
do { ?>
  <input type='hidden' id='id_<?= $k ?>' value="<?= $row_grades_fix['id'] ?>">
  <tr> 
    <td align='center'><input type='text' id='grade_<?= $k ?>' style='width : 50px;' value='<?= $row_grades_fix['grade'] ?>'></td>
    <td align='center'><input type='text'  id='grade_name_<?= $k ?>' style='width : 150px;' value='<?= $row_grades_fix['grade_name'] ?>'></td>
    <input type='hidden' id='year_<?= $k ?>' value="<?= $row_grades_fix['fix_cal_year'] ?>">
    <input type='hidden' id='season_<?= $k ?>' value="<?= $row_grades_fix['season'] ?>">
    <td align='center'><select id='type_<?= $k ?>'>
    <option value='<?= $row_grades_fix['type'] ?>' selected='selected'><?= $row_grades_fix['type'] ?></option>
    <option value='Snooker'>Snooker</option>
    <option value='Billiards'>Billiards</option>
    </select>
    </td>
    <input type='hidden' id='fix_upload_<?= $k ?>' value='<?= $row_grades_fix['fix_upload'] ?>'>
    <td align='center'><input type='text'  id='finals_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['finals_teams'] ?>'></td>
    <td align='center'><select id='day_played_<?= $k ?>'>
    <option value='<?= $row_grades_fix['dayplayed'] ?>' selected='selected'><?= $row_grades_fix['dayplayed'] ?></option>
    <option value='Mon'>Mon</option>
    <option value='Wed'>Wed</option>
    </select>
    </td>
    <td align='center'><input type="text" name="start_<?= $k ?>" id="start_<?= $k ?>" value="<?= $row_grades_fix['grade_start_date'] ?>" size="15"/></td>
    <td align='center'><input type='text' id='rank_points_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['RP'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_-2_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier_2_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_-1_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier_1_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_0_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier0_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_1_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier1_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_2_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier2_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_3_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier3_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_4_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier4_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_5_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier5_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_6_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier6_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_7_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier7_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_8_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier8_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_9_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier9_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_10_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier10_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_11_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier11_rp'] ?>'></td>
    <td align='center'><input type='text' id='rank_points_12_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['tier12_rp'] ?>'></td>
    <td align='center'><input type='text' id='min_break_points_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['min_breaks'] ?>'></td>
    <td align='center'><input type='text' id='matches_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['no_of_matches'] ?>'></td>
    <td align='center'><input type='text' id='rounds_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['no_of_rounds'] ?>'></td>
    <td align='center'><input type='text' id='players_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['no_of_players'] ?>'></td>
    <td align='center'><input type='text' id='games_round_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['games_round'] ?>'></td>
    <?php if($row_grades_fix['current'] == 'Yes')
    {
      echo("<td align='center'><input type='checkbox' name='current' id='current_" . $k . "' checked></td>");
    }
    else
    {
      echo("<td align='center'><input type='checkbox' name='current' id='current_" . $k . "'></td>");
    }
    ?>
    <!--<td align='center'><a href="user_files/team_grade_edit.php?grade=<?php echo $row_grades_fix['grade']; ?>&amp;season=<?php echo $season ?>"><img src="../Admin_Images/edit_butt.png" title="Edit this grade" width="16" height="16" /></a></td>-->
    <td style='width : 100px;' align='center'><a href="team_grade_fix_upload.php?grade=<?php echo $row_grades_fix['grade']; ?> &amp; season=<?php echo $row_grades_fix['season']; ?>">upload a fixture</a></td>
  </tr>
  <?php 
    $k++; 
    } while ($row_grades_fix = mysql_fetch_assoc($grades_fix)); ?>
</tbody>  
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align=center><input type='button' id='submit_button' href='javascript:;' onclick='SaveSettings("<?= $k ?>");' style='width:200px' value='Save Grade Settings'>
    </td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="6" align="center">&nbsp;</td>
    <td colspan="5" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center"><span class="red_bold">ALL GRADES AVAILABLE</td>
    <td colspan="5" align="center">&nbsp;</td>
  </tr>
</table>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='30%' border=1 align="center">
<thead> 
  <tr> 
    <th rowspan='2' class='text-center' style='width : 50px;'>Grade Code</th>
    <th rowspan='2' class='text-center' style='width : 150px;'>Grade Name</th>
    <!--<th rowspan='2' class='text-center' style='width : 60px;'>Type</th>
    <th rowspan='2' class='text-center' style='width : 50px;'>Finals Teams</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>Day Played</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>Start Date</th>
    <th rowspan='2' class='text-center' style='width : 50px;'>Allocated Ranking Points (Snooker)</th>
    <th colspan='6' class='text-center' style='width : 50px;'>Allocated Ranking Points (Billiards)</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>Min. Break Points</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>No. of Matches</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>No. of Rounds (inc Finals)</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>No. of Players</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>Games per Match</th>-->
    <th rowspan='2' class='text-center' style='width : 30px;'>Select</th>
    <!--<th rowspan='2' class='text-center' style='width : 30px;'>&nbsp;</th>
    <th rowspan='2' class='text-center' style='width : 30px;'>&nbsp;</th>-->
  </tr>
  <!--<tr>
    <th class='text-center' style='width : 30px;'>Tier 1</th>
    <th class='text-center' style='width : 30px;'>Tier 2</th>
    <th class='text-center' style='width : 30px;'>Tier 3</th>
    <th class='text-center' style='width : 30px;'>Tier 4</th>
    <th class='text-center' style='width : 30px;'>Tier 5</th>
    <th class='text-center' style='width : 30px;'>Tier 6</th>
  </tr>-->
</thead>
<tbody>
<?php 
$k = 0;
do { ?>
  <input type='hidden' id='arch_id_<?= $k ?>' value="<?= $row_grades_arch['id'] ?>">
  <tr> 
    <td align='center' id='arch_grade_<?= $k ?>' style='width : 50px;' ><?= $row_grades_arch['grade'] ?></td>
    <td align='center' id='arch_grade_name_<?= $k ?>' style='width : 150px;' ><?= $row_grades_arch['grade_name'] ?></td>
    <!--<input type='hidden' id='arch_year_<?= $k ?>' value="<?= $row_grades_arch['fix_cal_year'] ?>">
    <input type='hidden' id='arch_season_<?= $k ?>' value="<?= $row_grades_arch['season'] ?>">
    <td align='center' id='arch_type_<?= $k ?>' style='width : 60px;' ><?= $row_grades_arch['type'] ?></td>
    <input type='hidden' id='arch_fix_upload_<?= $k ?>' value='<?= $row_grades_arch['fix_upload'] ?>'>
    <td align='center' id='arch_finals_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['finals_teams'] ?></td>
    <td align='center'><select id='arch_day_played_<?= $k ?>'>
    <option value='<?= $row_grades_arch['dayplayed'] ?>' selected='selected'><?= $row_grades_arch['dayplayed'] ?></option>
    <option value='Mon'>Mon</option>
    <option value='Wed'>Wed</option>
    </select>
    </td>
    <td align='center'><input type='text'  id='start_<?= $k ?>' style='width : 30px;' value='<?= $row_grades_fix['grade_start_date'] ?>'></td>
    <td align='center' id='arch_rank_points_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['RP'] ?></td>
    <td align='center' id='arch_rank_points_1_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['tier1_rp'] ?></td>
    <td align='center' id='arch_rank_points_2_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['tier2_rp'] ?></td>
    <td align='center' id='arch_rank_points_3_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['tier3_rp'] ?></td>
    <td align='center' id='arch_rank_points_4_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['tier4_rp'] ?></td>
    <td align='center' id='arch_rank_points_5_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['tier5_rp'] ?></td>
    <td align='center' id='arch_rank_points_6_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['tier6_rp'] ?></td>
    <td align='center' id='arch_min_break_points_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['min_breaks'] ?></td>
    <td align='center' id='arch_matches_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['no_of_matches'] ?></td>
    <td align='center' id='arch_rounds_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['no_of_rounds'] ?></td>
    <td align='center' id='arch_players_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['no_of_players'] ?></td>
    <td align='center' id='arch_games_round_<?= $k ?>' style='width : 30px;' ><?= $row_grades_arch['games_round'] ?></td>-->
    <td align='center'><input type='checkbox' name='current' id='arch_current_<?= $k ?>'></td>
    <!--<td align='center'><a href="user_files/team_grade_edit.php?grade=<?php echo $row_grades_arch['grade']; ?>&amp;season=<?php echo $season ?>"><img src="../Admin_Images/edit_butt.png" title="Edit this grade" width="16" height="16" /></a></td>
    <td style='width : 100px;' align='center'><a href="team_grade_fix_upload.php?grade=<?php echo $row_grades_arch['grade']; ?> &amp; season=<?php echo $row_grades_arch['season']; ?>">upload a fixture</a></td>-->
  </tr>
    <?php 
    $k++;
    } while ($row_grades_arch = mysql_fetch_assoc($grades_arch)); ?>
</tbody>  
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align=center><input type='button' id='submit_button' href='javascript:;' onclick='SaveArchSettings("<?= $k ?>");' style='width:300px' value="Add selected grades to current season">
    </td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>

