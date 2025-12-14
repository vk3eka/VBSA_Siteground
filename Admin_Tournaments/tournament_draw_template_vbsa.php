<?php 
require_once('../Connections/connvbsa.php'); 
//error_reporting(0);
mysql_select_db($database_connvbsa, $connvbsa);

if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 
  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
  {
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

if(isset($_GET['tourn_id']))
{
  $tourn_id = $_GET['tourn_id'];
  $tourn_caption = "(Tournament ID " . $tourn_id . ")";
}

/*
$sql_players = "Select ROW_NUMBER() OVER (ORDER BY 
CASE WHEN ranknum IS NULL OR ranknum = 0 
THEN 1 
ELSE 0 
END, 
ranknum ASC) AS row_num, tourn_memb_id, FirstName, LastName, ranked, rank_pts, seed, ranknum FROM tourn_entry Left join members on members.memberID = tourn_entry.tourn_memb_id Left Join rank_S_open_tourn on members.MemberID = rank_S_open_tourn.memb_id where tournament_number = '$tourn_id' Order By row_num";
*/

$sql_players = "Select ROW_NUMBER() OVER (ORDER BY 
CASE WHEN seed IS NULL OR seed = 0 
THEN 1 
ELSE 0 
END, 
seed ASC) AS row_num, tourn_memb_id, FirstName, LastName, ranked, rank_pts, seed FROM tourn_entry Left join members on members.memberID = tourn_entry.tourn_memb_id where tournament_number = '$tourn_id' Order By row_num";

$result_players = mysql_query($sql_players, $connvbsa) or die(mysql_error());
$no_of_players = $result_players->num_rows;

if($no_of_players <= 64)
{
  $tourn_size = 64;
  $no_of_rounds = 6;
}
if(($no_of_players > 64) && ($no_of_players <= 128))
{
  $tourn_size = 128;
  $no_of_rounds = 10;
}

// data array for change player dropdown
$players = array();
$player_data = array();

$sql = "Select MemberID, FirstName, LastName From members where FirstName != '' Order By LastName";
$result_player_dropdown = mysql_query($sql, $connvbsa) or die(mysql_error());
$num_rows = $result_player_dropdown->num_rows;
if ($num_rows > 0) 
{
  while($build_data = $result_player_dropdown->fetch_assoc()) 
  {
    $firstname = $build_data['FirstName'];
    $lastname = $build_data['LastName'];
    $players[] = $build_data['FirstName'] . " " . $build_data['LastName']; 
  }
  $player_data = json_encode($players);
}
$result_player_dropdown->free_result();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="tournament_draw.css">
<body>
<?php 
include '../admin_xx_includes/db_nav.php';
include '../admin_xx_includes/db_srch_treas.php';

$playDatesArr = [];

function GetMemberName($memberID)
{
  global $connvbsa;
  $sql = "Select MemberID, FirstName, LastName FROM members WHERE MemberID = " . $memberID;
  $result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
  $build_member = $result_member->fetch_assoc();
  $fullname = ($build_member['FirstName'] . " " . $build_member['LastName']);
  return $fullname;
}

echo("<script type='text/javascript'>");
echo("$(document).ready(function() { ");
echo("$.fn.fillelementarray = function () {");

//get plater results from tournament_results table
$query_scores = 'Select * FROM vbsa3364_vbsa2.tournament_results where tourn_id = ' . $tourn_id;
$result_scores = mysql_query($query_scores, $connvbsa) or die(mysql_error());

while($build_scores = $result_scores->fetch_assoc())
{
  $id_scores = ($build_scores['row_no'] . '_' . ($build_scores['col_no']+1) . '_' . ($build_scores['col_no']+1));
  $id_name = $build_scores['row_no'] . '_' . ($build_scores['col_no']) . '_' . ($build_scores['col_no']);

  echo("$('#" . $id_scores . "').val('" . $build_scores['game_1'] . "');\n");
  echo("$('#" . $id_name . "').val('" . GetMemberName($build_scores['memb_id']) . "');\n");
}

//get results from tournament_day_time table
$query_times = 'Select * FROM vbsa3364_vbsa2.tournament_day_time where tourn_id = ' . $tourn_id;
$result_times = mysql_query($query_times, $connvbsa) or die(mysql_error());
while($build_times = $result_times->fetch_assoc())
{
  $id_day = ($build_times['row_no'] . '_' . $build_times['col_no']);
  $id_time = $build_times['row_no'] . '_' . ($build_times['col_no']+1);
  echo("$('#$id_day').html('{$build_times['day']}');\n");
  echo("$('#$id_time').val('{$build_times['time']}');\n");
  $key = ($build_times['row_no'] . '_' . $build_times['col_no']);
  $playDatesArr[$key] = $build_times['day'];
}

echo("}");
echo("});");

echo("let play_date_arr = '" . json_encode($playDatesArr, JSON_UNESCAPED_UNICODE) . "';");
echo("let play_date = JSON.parse(play_date_arr);");

echo("window.onload = function()"); 
echo("{");
echo("$.fn.fillelementarray();");
echo("}");
echo("</script>");
?>

<form name='tournament_draw_template' id='tournament_draw_template' method="post" action='tournament_draw_template.php?tourn_id=<?= $tourn_id ?>'>
<center>
<br>
<table align="center" cellpadding="5" cellspacing="5" width='50%'>
  <tr>
    <td align="center"><span class="red_bold"><h3>Tournament Draw Creation</h3></span></td>
  </tr>
  <tr>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<?php

// get tournament name
$query_tourn_name = 'Select *, tournaments.tourn_type as type FROM vbsa3364_vbsa2.tournaments LEFT JOIN calendar ON tournaments.tourn_id = calendar.tourn_id where tournaments.tourn_id = ' . $tourn_id;

$result_tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
$build_tourn_name = $result_tourn_name->fetch_assoc();
$tourn_type = $build_tourn_name['type'];

echo("<div hidden align='center' id='tourn_name'>" . $build_tourn_name['tourn_name'] . "</div>");
echo("<div align='center'><h3>" . $build_tourn_name['tourn_name'] . "</h3></div>");
echo("<div align='center'>" . $tourn_caption . "</div>");
echo("<div hidden align='center' id='tourn_id'>" . $tourn_id . "</div>");
echo("<br>");
echo("<div align='center'>Start Date " . $build_tourn_name['startdate'] . " - Finish Date " . $build_tourn_name['finishdate'] . "</div>");
echo("<br>");

$default_user_image = '../images/change_player_1.png';
?>

<div align="center" class="greenbg"><button id="show_players" style="width: 300px;">Show all players for this tournament</button></div>
<br>
<?php
//get any changes from tournament_players
$query_added_player = 'Select * FROM vbsa3364_vbsa2.tournament_players where tourn_id = ' . $tourn_id . ' and added_player = 1';
$result_added_player = mysql_query($query_added_player, $connvbsa) or die(mysql_error());
$added_player_rows = $result_added_player->num_rows;
if($added_player_rows > 0)
{
  $added_player_caption = 'disabled';
}
else
{
  $added_player_caption = '';
}
?>
<div align="center" class="greenbg"><button id="save_players" <?= $added_player_caption ?> style="width: 300px;">Save Initial Player Allocation</button></div>
<div align="center"  style="color: red">Saving the initial player allocation will reset the players list to that of the Tournament Entries Table</div>
<div align="center"  style="color: red">This button will be disabled when scoring data has been added</div>
<br>
<!--<div align="center" class="greenbg"><button id="reset_players" style="width: 300px;">Reset Initial Player Allocation</button></div>
<div align="center"  style="color: red">Resetting the initial player allocation will delete all scoring data</div>
<br>-->
<div align="center" class="greenbg"><button id="settings" style="width: 300px;">Settings</button></div>
<br>
<div align="center" class="greenbg">Tournament Size <?= $tourn_size ?></div>
<br>
<br><br>
<div id="fixtureTable"></div>
<script>
  tourn_size = <?= $tourn_size ?>;

  function DateSelect(play_date, index)
  {
    let play_date_text = play_date[index];
    if((play_date_text === undefined) || (play_date_text === null))
    {
      play_date_text = '';
    }
    let days = [
        "",
        "Friday",
        "Saturday",
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday"
    ];
    let html_Date = "<select id='R" + index + "' class='date_select'>";
    days.forEach(day => {
        let selected = (day === play_date_text) ? " selected" : "";
        html_Date += `<option value="${day}"${selected}>${day}</option>`;
    });
    html_Date += "</select>";
    return html_Date;
  }

  function R7(index, tourn_size) 
  {
    html_R7 = "";
    if(tourn_size == 128)
    {
        html_R7 += "<td>";
        html_R7 += DateSelect(play_date, (index + '_1'));
        html_R7 += "</td>";
        html_R7 += "<td><input type='text' class='timepicker' id='" + index + "_2' value='' size='6' /></td>";
        html_R7 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_3' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
        html_R7 += "<td id='" + index + "_4' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_4_4' value=''></td>";
        html_R7 += "<td>";
        html_R7 += DateSelect(play_date, (index + '_5'));
        html_R7 += "</td>";
        html_R7 += "<td><input type='text' class='timepicker' id='" + index + "_6' value='' size='6' /></td>";
        html_R7 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_7' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
        html_R7 += "<td id='" + index + "_8' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_8_8' value=''></td>";
        html_R7 += "<td>";
        html_R7 += DateSelect(play_date, (index + '_9'));
        html_R7 += "</td>";
        html_R7 += "<td><input type='text' class='timepicker' id='" + index + "_10' value='' size='6' /></td>";
        html_R7 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_11' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_11_11' value=''></td>";
        html_R7 += "<td id='" + index + "_12' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_12_12' value=''></td>";
        html_R7 += "<td>";
        html_R7 += DateSelect(play_date, (index + '_13'));
        html_R7 += "</td>";
        html_R7 += "<td><input type='text' class='timepicker' id='" + index + "_14' value='' size='6' /></td>";
        html_R7 += "<td nowrap><img src='<?= $default_user_image ?>'  class='score' id='" + index + "_15' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_15_15' value=''></td>";
        html_R7 += "<td id='" + index + "_16' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_16_16' value=''></td>";
    }
    html_R7 += "<td>";
    html_R7 += "</td>";
    html_R7 += "<td id='" + index + "_18'></td>";
    html_R7 += "<td nowrap><img src='<?= $default_user_image ?>'  class='score' id='" + index + "_19' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_19_19' value=''></td>";
    html_R7 += "<td id='" + index + "_20' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_20_20' value=''></td>";
    html_R7 += "<td>";
    html_R7 += "</td>";
    html_R7 += "<td id='" + index + "_22'></td>";
    html_R7 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_23' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_23_23' value=''></td>";
    html_R7 += "<td id='" + index + "_24' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_24_24' value=''></td>";
    html_R7 += "<td>";
    html_R7 += "</td>";
    html_R7 += "<td id='" + index + "_26'></td>";
    html_R7 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_27' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_27_27' value=''></td>";
    html_R7 += "<td id='" + index + "_28' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_28_28' value=''></td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td>&nbsp;</td>";
    html_R7 += "<td id='" + index + "_39'></td>";
    html_R7 += "<td>&nbsp;</td>";
    return html_R7;
  }

  function R8(index, tourn_size) 
  {
    html_R8 = "";
    if(tourn_size == 128)
    {
        html_R8 += "<td>";
        html_R8 += DateSelect(play_date, (index + '_1'));
        html_R8 += "</td>";
        html_R8 += "<td><input type='text' class='timepicker' id='" + index + "_2' value='' size='6' /></td>";
        html_R8 += "<td nowrap><img src='<?= $default_user_image ?>'  class='score' id='" + index + "_3' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
        html_R8 += "<td id='" + index + "_4' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_4_4' value=''></td>";
        html_R8 += "<td>";
        html_R8 += DateSelect(play_date, (index + '_5'));
        html_R8 += "</td>";
        html_R8 += "<td><input type='text' class='timepicker' id='" + index + "_6' value='' size='6' /></td>";
        html_R8 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_7' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
        html_R8 += "<td id='" + index + "_8' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_8_8' value=''></td>";
        html_R8 += "<td>";
        html_R8 += DateSelect(play_date, (index + '_9'));
        html_R8 += "</td>";
        html_R8 += "<td><input type='text' class='timepicker' id='" + index + "_10' value='' size='6' /></td>";
        html_R8 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_11' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_11_11' value=''></td>";
        html_R8 += "<td id='" + index + "_12' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_12_12' value=''></td>";
        html_R8 += "<td>";
        html_R8 += DateSelect(play_date, (index + '_13'));
        html_R8 += "</td>";
        html_R8 += "<td><input type='text' class='timepicker' id='" + index + "_14' value='' size='6' /></td>";
        html_R8 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_15' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_15_15' value=''></td>";
        html_R8 += "<td id='" + index + "_16' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_16_16' value=''></td>";
    }
    html_R8 += "<td>";
    html_R8 += DateSelect(play_date, (index + '_17'));;
    html_R8 += "</td>";
    html_R8 += "<td><input type='text' class='timepicker' id='" + index + "_18' value='' size='6' /></td>";
    html_R8 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_19' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_19_19' value=''></td>";
    html_R8 += "<td id='" + index + "_20' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_20_20' value=''></td>";
    html_R8 += "<td>";
    html_R8 += DateSelect(play_date, (index + '_21'));
    html_R8 += "</td>";
    html_R8 += "<td><input type='text' class='timepicker' id='" + index + "_22' value='' size='6' /></td>";
    html_R8 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_23' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_23_23' value=''></td>";
    html_R8 += "<td id='" + index + "_24' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_24_24' value=''></td>";
    html_R8 += "<td>";
    html_R8 += DateSelect(play_date, (index + '_25'));
    html_R8 += "</td>";
    html_R8 += "<td><input type='text' class='timepicker' id='" + index + "_26' value='' size='6' /></td>";
    html_R8 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_27' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_27_27' value=''></td>";
    html_R8 += "<td id='" + index + "_28' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_28_28' value=''></td>";
    html_R8 += "<td>";
    html_R8 += DateSelect(play_date, (index + '_29'));
    html_R8 += "</td>";
    html_R8 += "<td><input type='text' class='timepicker' id='" + index + "_30' value='' size='6' /></td>";
    html_R8 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_31' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_31_31' value=''></td>";
    html_R8 += "<td id='" + index + "_32' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_32_32' value=''></td>";
    html_R8 += "<td>&nbsp;</td>";
    html_R8 += "<td>&nbsp;</td>";
    html_R8 += "<td>&nbsp;</td>";
    html_R8 += "<td>&nbsp;</td>";
    html_R8 += "<td>&nbsp;</td>";
    html_R8 += "<td>&nbsp;</td>";
    html_R8 += "<td id='" + index + "_39'</td>";
    html_R8 += "<td>&nbsp;</td>";
    return html_R8;
  }

  function R9(index, tourn_size) 
  {
    html_R9 = "";
    if(tourn_size == 128)
    {
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
        html_R9 += "<td>&nbsp;</td>";
    }
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>";
    html_R9 += DateSelect(play_date, (index + '_33'));
    html_R9 += "</td>";
    html_R9 += "<td><input type='text' class='timepicker' id='" + index + "_34' value='' size='6' /></td>";
    html_R9 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_35' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_35_35' value=''></td>";
    html_R9 += "<td id='" + index + "_36' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_36_36' value=''></td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    html_R9 += "<td>&nbsp;</td>";
    return html_R9;
  }

  function R10(index, tourn_size) 
  {
    html_R10 = "";
    if(tourn_size == 128)
    {
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
        html_R10 += "<td>&nbsp;</td>";
    }
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>&nbsp;</td>";
    html_R10 += "<td>";
    html_R10 += DateSelect(play_date, (index + '_37'));
    html_R10 += "</td>";
    html_R10 += "<td><input type='text' class='timepicker' id='" + index + "_38' value='' size='6' /></td>";
    html_R10 += "<td nowrap><img src='<?= $default_user_image ?>' class='score' id='" + index + "_39' style='width:32px; height:32x'><input type='text' class='player' id='" + index + "_39_39' value=''></td>";
    html_R10 += "<td id='" + index + "_40' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_40_40' value=''></td>";
    return html_R10;
  }

  function Finals(index, tourn_size) 
  {
    html_Finals = "";
    if(tourn_size == 128)
    {
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
        html_Finals += "<td>&nbsp;</td>";
    }
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;</td>";
    html_Finals += "<td>&nbsp;";
    html_Finals += "</td>";
    html_Finals += "<td id='" + index + "_40'></td>";
    if(index == 29) // final winner
    {
      html_Finals += "<td nowrap><b><input type='text' class='player' id='" + index + "_41_41' value='' style='width:164px; height:32x'></b></td>";
      html_Finals += "<td id='" + index + "_42'>&nbsp;</td>";
    }
    else
    {
      html_Finals += "<td nowrap><input type='text' class='player' id='" + index + "_41_41' value='' style='width:164px; height:32x'></td>";
      html_Finals += "<td id='" + index + "_42' align='center'><input type='text' class='enter_score' size='3px' id='" + index + "_42_42' value=''></td>";
    }
    return html_Finals;
  }

  function Blank(index, tourn_size) 
  {
    html_Blank = "";
    if(tourn_size == 128)
    {
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
        html_Blank += "<td>&nbsp;</td>";
    }
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    html_Blank += "<td>&nbsp;</td>";
    return html_Blank;
  }

const rowPatterns = [
// header id first three rows
  "R7",
  "R8",
  "R9",
  "R8",
  "R7",
  "R10",
  "R7",
  "R8",
  "R9",
  "R8",
  "R7",
  "Blank",
  "R7",
  "R8",
  "R9",
  "R8",
  "R7",
  "R10",
  "R7",
  "R8",
  "R9",
  "R8",
  "R7",
  "Finals",
  "Finals",
  "Finals",
];

let rounds = 0;
if(tourn_size === 128)
{
    rounds = 10; // number of rounds
}
else
{
    rounds = 6; // number of rounds
}
let html = "<table align='center' border='1' cellpadding='0' cellspacing='0' width='1200'>";

// ---- Top Header ----
index = 1;
rnd = 3;// for 128 player tournament
html += "<tr>";
html += "<td rowspan='26' height='391' style='writing-mode: vertical-lr;text-orientation: sideways;text-align: center;'>Top Half<span>&nbsp;</span></td>";

for (let r = 1; r <= rounds; r++) 
{
  if((tourn_size === 128) && (r <= 4))
  {
      html += "<td colspan='4' align='center'><b>Round 1</b></td>";
  }
  else if((tourn_size === 128) && (r > 4) && (r <= 6))
  {
    html += "<td colspan='4' align='center'><b>Round 2</b></td>";
  }
  else if((tourn_size === 128) && (r > 6) && (r <= rounds))
  {
    html += "<td colspan='4' align='center'><b>Round " + rnd + "</b></td>";
    rnd++;
  }
  if(tourn_size === 64)
  {
    html += "<td colspan='4' align='center'><b>Round " + r + "</b></td>";
  }
}
html += "</tr>";
index++;
for (let r = 1; r <= rounds; r++) 
{
    html += "<td colspan='4' align='center'><b>Best of 5</b></td>";
}
html += "</tr>";
index++;
html += "<tr>";
for (let r = 1; r <= rounds; r++) 
{
    html += "<td align='center'><b>Day</b></td>";
    html += "<td align='center'><b>Time</b></td>";
    html += "<td>&nbsp;</td>";
    html += "<td align='center'><b>Score</b></td>";
}
html += "</tr>";
index++;
html += "<tr>";

// ---- Body ----
rowPatterns.forEach((pattern, rowIndex) => {
    if (pattern === "R7") 
    {
      html += R7(index, tourn_size);
    } 
    else if (pattern === "R8") 
    {
      html += R8(index, tourn_size);
    } 
    else if (pattern === "R9") 
    {
      html += R9(index, tourn_size);
    }
    else if (pattern === "R10") 
    {
      html += R10(index, tourn_size);
    }
    else if (pattern === "Blank") 
    {
      html += Blank(index, tourn_size);
    }
    else if (pattern === "Finals") 
    {
      html += Finals(index, tourn_size);
    }
  html += "</tr>";
  index++;
});

// ---- bottom of table ----
html += "<tr>";
html += "<td rowspan='26' height='391' style='writing-mode: vertical-lr;text-orientation: sideways;text-align: center;'>Bottom Half<span>&nbsp;</span></td>";

// ---- Bottom Body ----
rowPatterns.forEach((pattern, rowIndex) => {
    if (pattern === "R7") 
    {
      html += R7(index, tourn_size);
    } 
    else if (pattern === "R8") 
    {
      html += R8(index, tourn_size);
    } 
    else if (pattern === "R9") 
    {
      html += R9(index, tourn_size);
    }
    else if (pattern === "R10") 
    {
      html += R10(index, tourn_size);
    }
    else if (pattern === "Blank") 
    {
      html += Blank(index, tourn_size);
    }
  html += "</tr>";
  index++;
});
html += "</table>";
html += "<br><br>";

document.getElementById("fixtureTable").innerHTML = html;
</script>
<?php
// map seeds to table position
if($tourn_size === 64)
{
  $idMap = [
    // rows 1 to 3 are headers
    1 => "4_27_27",
    2 => "52_27_27",
    3 => "30_27_27",
    4 => "26_27_27",
    5 => "17_27_27",
    6 => "39_27_27",
    7 => "43_27_27",
    8 => "13_27_27",
    9 => "10_27_27",
    10 => "46_27_27",
    11 => "36_27_27",
    12 => "20_27_27",
    13 => "23_27_27",
    14 => "33_27_27",
    15 => "49_27_27",
    16 => "7_27_27",
    17 => "7_23_23",
    18 => "49_23_23",
    19 => "33_23_23",
    20 => "23_23_23",
    21 => "20_23_23",
    22 => "36_23_23",
    23 => "46_23_23",
    24 => "10_23_23",
    25 => "13_23_23",
    26 => "43_23_23",
    27 => "39_23_23",
    28 => "17_23_23",
    29 => "26_23_23",
    30 => "30_23_23",
    31 => "52_23_23",
    32 => "4_23_23",
    33 => "4_19_19",
    34 => "52_19_19",
    35 => "30_19_19",
    36 => "26_19_19",
    37 => "17_19_19",
    38 => "39_19_19",
    39 => "43_19_19",
    40 => "13_19_19",
    41 => "10_19_19",
    42 => "46_19_19",
    43 => "36_19_19",
    44 => "20_19_19",
    45 => "23_19_19",
    46 => "33_19_19",
    47 => "49_19_19",
    48 => "7_19_19",
    49 => "8_19_19",
    50 => "48_19_19",
    51 => "34_19_19",
    52 => "22_19_19",
    53 => "19_19_19",
    54 => "37_19_19",
    55 => "45_19_19",
    56 => "11_19_19",
    57 => "14_19_19",
    58 => "42_19_19",
    59 => "40_19_19",
    60 => "16_19_19",
    61 => "25_19_19",
    62 => "31_19_19",
    63 => "51_19_19",
    64 => "5_19_19"
  ];
}
else if($tourn_size == 128)
{
  $idMap = [
    // rows 1 to 3 are headers
    1 => "4_27_27",
    2 => "52_27_27",
    3 => "30_27_27",
    4 => "26_27_27",
    5 => "17_27_27",
    6 => "39_27_27",
    7 => "43_27_27",
    8 => "13_27_27",
    9 => "10_27_27",
    10 => "46_27_27",
    11 => "36_27_27",
    12 => "20_27_27",
    13 => "23_27_27",
    14 => "33_27_27",
    15 => "49_27_27",
    16 => "7_27_27",
    17 => "7_23_23",
    18 => "49_23_23",
    19 => "33_23_23",
    20 => "23_23_23",
    21 => "20_23_23",
    22 => "36_23_23",
    23 => "46_23_23",
    24 => "10_23_23",
    25 => "13_23_23",
    26 => "43_23_23",
    27 => "39_23_23",
    28 => "17_23_23",
    29 => "26_23_23",
    30 => "30_23_23",
    31 => "52_23_23",
    32 => "4_23_23",
    33 => "4_19_19",
    34 => "52_19_19",
    35 => "30_19_19",
    36 => "26_19_19",
    37 => "17_19_19",
    38 => "39_19_19",
    39 => "43_19_19",
    40 => "13_19_19",
    41 => "10_19_19",
    42 => "46_19_19",
    43 => "36_19_19",
    44 => "20_19_19",
    45 => "23_19_19",
    46 => "33_19_19",
    47 => "49_19_19",
    48 => "7_19_19",
    49 => "7_15_15",
    50 => "48_19_19",
    51 => "49_15_15",
    52 => "23_15_15",
    53 => "20_15_15",
    54 => "36_15_15",
    55 => "5_15_15",
    56 => "10_15_15",
    57 => "13_15_15",
    58 => "43_15_15",
    59 => "39_15_15",
    60 => "17_15_15",
    61 => "26_15_15",
    62 => "30_15_15",
    63 => "46_15_15",
    64 => "52_15_15",
    65 => "4_11_11",
    66 => "52_11_11",
    67 => "30_11_11",
    68 => "26_11_11",
    69 => "17_11_11",
    70 => "39_11_11",
    71 => "43_11_11",
    72 => "13_11_11",
    73 => "10_11_11",
    74 => "46_11_11",
    75 => "36_11_11",
    76 => "20_11_11",
    77 => "23_11_11",
    78 => "33_11_11",
    79 => "49_11_11",
    80 => "7_11_11",
    81 => "7_7_7",
    82 => "49_7_7",
    83 => "33_7_7",
    84 => "23_7_7",
    85 => "20_7_7",
    86 => "36_7_7",
    87 => "46_7_7",
    88 => "10_7_7",
    89 => "13_7_7",
    90 => "43_7_7",
    91 => "39_7_7",
    92 => "17_7_7",
    93 => "26_7_7",
    94 => "30_7_7",
    95 => "52_7_7",
    96 => "4_7_7",
    97 => "4_3_3",
    98 => "52_3_3",
    99 => "30_3_3",
    100 => "26_3_3",
    101 => "17_3_3",
    102 => "39_3_3",
    103 => "43_3_3",
    104 => "13_3_3",
    105 => "10_3_3",
    106 => "46_3_3",
    107 => "36_3_3",
    108 => "20_3_3",
    109 => "23_3_3",
    110 => "33_3_3",
    111 => "49_3_3",
    112 => "7_3_3",
    113 => "8_3_3",
    114 => "48_3_3",
    115 => "25_3_3",
    116 => "22_3_3",
    117 => "19_3_3",
    118 => "37_3_3",
    119 => "45_3_3",
    120 => "11_3_3",
    121 => "14_3_3",
    122 => "42_3_3",
    123 => "40_3_3",
    124 => "16_3_3",
    125 => "34_3_3",
    126 => "31_3_3",
    127 => "51_3_3",
    128 => "5_3_3"
  ];
}
echo("<script>");
$player_index = 0;
while($build_table = $result_players->fetch_assoc())
{
    $rowNum = $build_table['row_num'];
    if(isset($idMap[$rowNum]))
    {
      $name = addslashes($build_table['FirstName'] . " " . $build_table['LastName']);
      $elementId = $idMap[$rowNum];
      echo "document.getElementById('$elementId').value = '$name';";
    }
    $player_index++;
}
for($i = ($player_index); $i < $tourn_size; $i++)
{
  $name = 'Bye';
  $elementId = $idMap[$i+1];
  echo "document.getElementById('$elementId').value = '$name';";
}

// add player as rounds progress
$sql_added_player = 'Select * from tournament_players where tourn_id = ' . $tourn_id . ' and added_player = 1';
$result_added_player = mysql_query($sql_added_player, $connvbsa) or die(mysql_error());
while($build_added = $result_added_player->fetch_assoc())
{
  echo("document.getElementById('" . $build_added['row_no'] . "_" . $build_added['col_no'] . "_" . $build_added['col_no'] . "').value = '" . $build_added['fullname'] . "';");
}

// add finals text
echo("document.getElementById('25_39').style.fontWeight = 'bold';");
echo("document.getElementById('25_39').style.textAlign = 'center';");
echo("document.getElementById('25_39').innerHTML = 'Finals';");

echo("document.getElementById('26_39').style.fontWeight = 'bold';");
echo("document.getElementById('26_39').style.textAlign = 'center';");
echo("document.getElementById('26_39').innerHTML = 'Best of 7';");

/*
// added for testing.
echo("document.getElementById('5_27_27').value = 'Adrian Hung';");
echo("document.getElementById('5_28_28').value = '6';");
echo("document.getElementById('4_28_28').value = '4';");
echo("document.getElementById('8_23_23').value = 'Scott Preston';");
echo("document.getElementById('8_24_24').value = '6';");
echo("document.getElementById('7_24_24').value = '4';");
echo("document.getElementById('8_27_27').value = 'Adrian Hung';");
echo("document.getElementById('8_28_28').value = '6';");
echo("document.getElementById('7_28_28').value = '4';");
echo("document.getElementById('5_31_31').value = 'Adrian Hung';");
echo("document.getElementById('7_31_31').value = 'Scott Preston';");
//
*/
//echo("document.getElementById('5_18_18').value = '9:00';");

echo("</script>");
?>
</center>

<script>
$(document).ready(function() 
{
  // array of rows with a match
  var row_match = [4, 7, 10, 13, 16, 19, 22, 25, 30, 33, 36, 39, 42, 45, 48, 51];
  tourn_size == <?= $tourn_size ?>;
  // array of cols with a match (player name)
  if(tourn_size == 128)
  {
    var col_match = [3, 11, 19, 27, 35, 41]; //128 player tournament
  }
  else if(tourn_size == 64)
  {
    var col_match = [19, 27, 35, 41]; //64 player tournament
  }

  // fill table with players/seeds (position data from George Hoy's spreadsheet)
  $.each(col_match, function(col_index, col_num)
  {
    $.each(row_match, function(row_index, row_num)
    {
      if(tourn_size == 64)
      {
        // Instance 1
        if(($('#' + (row_num+1) + '_' + col_num + '_' + col_num).val() === "Bye")
          && 
        ($('#' + (row_num+1) + '_' + (col_num+4) + '_' + (col_num+4)).val() === '')
          && 
        ($('#' + (row_num) + '_' + (col_num) + '_' + (col_num)).val() != ""))
        {
          $('#' + (row_num+1) + '_' + (col_num+4) + '_' + (col_num+4)).val( $('#' + (row_num) + '_' + col_num + '_' + col_num).val() );
        }

        // Instance 3
        if((($('#' + row_num + '_' + col_num + '_' + col_num).val() === "Bye")
          && 
        ($('#' + (row_num+1) + '_' + (col_num+4) + '_' + (col_num+4)).val() != ""))
          && 
        ($('#' + (row_num+1) + '_' + (col_num+4) + '_' + (col_num+4)).val() != "Bye")
          && 
        ($('#' + (row_num+1) + '_' + (col_num+4) + '_' + (col_num+4)).val() != ""))
        {
          $('#' + (row_num) + '_' + (col_num+4) + '_' + (col_num+4)).val($('#' + (row_num+1) + '_' + col_num + '_' + col_num).val())
        }
      }

      if(tourn_size == 128)
      {
        // Instance 2
        if(($('#' + (row_num+1) + '_' + col_num + '_' + col_num).val() === "Bye")
          && 
        ($('#' + (row_num) + '_' + (col_num+4) + '_' + (col_num+4)).val() === "Bye")
          && 
        ($('#' + (row_num) + '_' + (col_num) + '_' + (col_num)).val() === "Bye"))
        {
          $('#' + (row_num+1) + '_' + (col_num+4) + '_' + (col_num+4)).val( $('#' + (row_num) + '_' + col_num + '_' + col_num).val() );
        }

        // Instance 4
        if(($('#' + (row_num+1) + '_' + col_num + '_' + col_num).val() === "Bye")
          && 
        ($('#' + (row_num) + '_' + (col_num+4) + '_' + (col_num+4)).val() === "Bye")
          && 
        ($('#' + (row_num+1) + '_' + (col_num) + '_' + (col_num)).val() === "Bye"))
        {
          $('#' + (row_num+1) + '_' + (col_num+4) + '_' + (col_num+4)).val( $('#' + (row_num) + '_' + col_num + '_' + col_num).val() );
        }

        // Instance 5
        if(($('#' + (row_num) + '_' + col_num + '_' + col_num).val() != "")
          && 
        ($('#' + (row_num+1) + '_' + (col_num) + '_' + (col_num)).val() === ''))
        {
          $('#' + (row_num+1) + '_' + (col_num) + '_' + (col_num)).val( $('#' + (row_num) + '_' + (col_num-4) + '_' + (col_num-4)).val() );
        }

        // Instance 6
        if(($('#' + (row_num) + '_' + col_num + '_' + col_num).val() != "")
          && 
        ($('#' + (row_num+1) + '_' + (col_num) + '_' + (col_num)).val() === 'Bye')
          && 
        ($('#' + (row_num+1) + '_' + (col_num+4) + '_' + (col_num+4)).val() === ""))
        {
          $('#' + (row_num+1) + '_' + (col_num+4) + '_' + (col_num+4)).val( $('#' + (row_num) + '_' + (col_num) + '_' + (col_num)).val() );
        }

        // Instance 7
        if(($('#' + (row_num+1) + '_' + col_num + '_' + col_num).val() != "")
          && 
        ($('#' + (row_num) + '_' + (col_num) + '_' + (col_num)).val() === 'Bye')
          && 
        ($('#' + (row_num) + '_' + (col_num+4) + '_' + (col_num+4)).val() === ""))
        {
          $('#' + (row_num) + '_' + (col_num+4) + '_' + (col_num+4)).val( $('#' + (row_num+1) + '_' + (col_num) + '_' + (col_num)).val() );
        }
      } 
    });
  });

  $.fn.get_player_1 = function (str) 
  {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var column = str.substring(first+1);
    var player_name = $('#' + row + "_" + column + "_" + column).val();
    return player_name;
  }

  $.fn.get_player_2 = function (str, i) 
  {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var column = str.substring(first+1);
    var player_name_1 = $('#' + (row+i) + "_" + column + "_" + column).val();
    var player_name_3 = $('#' + (row-i) + "_" + column + "_" + column).val();
    if(player_name_1 == undefined)
    {
      var row_no = (row-i);
      var player_name = $('#' + (row_no) + "_" + column + "_" + column).val();
    }
    else if(player_name_3 == undefined)
    {
      var row_no = (row+i);
      var player_name = $('#' + (row_no) + "_" + column + "_" + column).val();
    }
    playerArr = player_name + ", " + row_no;
    return playerArr;
  }

  $.fn.get_score_1 = function (str) 
  {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var column = str.substring(first+1);
    var col_split = column.split('_');
    var col_1 = col_split[0];
    var col_2 = col_split[1];
    var score_value = $('#' + row + "_" + column).val();
    var score_detail= [];
    score_detail['value'] = score_value;
    console.log("1 Current Score " + score_detail['value']);
    console.log("1 Current Row " + row);
    score_detail['row'] = row;
    score_detail['col1'] = col_1;
    score_detail['col2'] = col_2;
    return score_detail;
  }

  $.fn.get_score_2 = function (str, i) 
  {
    //i = 6;
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var column = str.substring(first+1);
    var col_split = column.split('_');
    var col_1 = col_split[0];
    var col_2 = col_split[1];
    /*
    console.log(str);
    console.log(col_1);
    console.log(row);
    console.log(row+i);
    console.log(row-i);
    */
    var score_detail= [];
    var score_value_1 = $('#' + (row+i) + '_' + column).val();
    //var score_value_2 = $('#' + (row) + '_' + column).val();
    var score_value_3 = $('#' + (row-i) + '_' + column).val();
    //console.log(score_value_1);
    //console.log(score_value_2);
    //console.log(score_value_3);
    if((score_value_1 == undefined) || (score_value_1 == ''))
    {
      var score_value = $('#' + (row-i) + '_' + column).val();
      score_detail['row'] = (row-i);
    }
    else if((score_value_3 == undefined) || (score_value_3 == ''))
    {
      var score_value = $('#' + (row+i) + '_' + column).val();
      score_detail['row'] = (row+i);
    }
    score_detail['value'] = score_value;
    //var score_value = $('#' + i + "_" + column).val();
    /*
    console.log("2 Current Score " + score_detail['value']);
    console.log("2 Current Row " + row);
    console.log("2 Current Col " + column);
    console.log("2 Value " + $('#12_36_36').val());
    //score_value = 3;
    */
    score_detail['value'] = score_value;
    score_detail['col1'] = col_1;
    score_detail['col2'] = col_2;
    return score_detail;
  }

  $.fn.get_draw = function (element_id) 
  {
    var subStr = '_';
    var first = (element_id.split(subStr, 1).join(subStr).length);
    var row = parseInt(element_id.substring(0, first));
    var col = element_id.substring(first+1);
    var score_1 = $('#' + row + '_' + col + '_' + col).val();
    var score_2 = $('#' + (row+1) + '_' + col + '_' + col).val();
    if(score_2 < score_1)
    {
      var player_name = $('#' + (row) + "_" + col + "_" + col).val();
    }
    else if(score_1 < score_2)
    {
      var player_name = $('#' + (row+1) + "_" + col + "_" + col).val();
    }
    var next_player = (player_name + ", " + row + ", " + col);
    return next_player;
  }

  $('#show_players').click(function(e)
  {
    e.preventDefault();
    $('#list_players_modal').modal('show');
  });

  $('#save_players').click(function(e)
  {
    e.preventDefault();
    var tourn_id = <?= $tourn_id ?>;
    var player_data = [];
    for(col = 19; col < 42; col++)
    {
      for(row = 3; row < 53; row++)
      {
        if($('#' + row + '_' + col + '_' + col).val() != undefined)
        {
          player_data += ($('#' + row + '_' + col + '_' + col).val()) + ": " + (row + '_' + col + '_' + col) + ", ";
        }
      }
    }
    player_data = JSON.stringify(player_data);
    $.ajax({
      url:"save_player_data.php?tourn_id=" + tourn_id + "&player_data=" + player_data,
      method: 'GET',
      success:function(response)
      {
        alert(response);
        location.reload(true);
      },
    });
  });

  $('#reset_players').click(function(e)
  {
    e.preventDefault();
    var tourn_id = <?= $tourn_id ?>;
    $.ajax({
      url:"reset_player_data.php?tourn_id=" + tourn_id,
      method: 'GET',
      success:function(response)
      {
        alert(response);
      },
    });
  });

  $('#settings').click(function(e)
  {
    e.preventDefault();
    $('#settings_modal').modal('show');
  });

  $('#save_settings').click(function(e)
  {
    e.preventDefault();
    var tourn_id = <?= $tourn_id ?>;
    var settings_data = [];
    for(i = 0; i < '<?= $no_of_rounds ?>'; i++)
    {
      $('#best_of_' + i).val();
      settings_data += $('#best_of_' + (i+1)).val() + ", ";
    }
    $.ajax({
        url:"save_settings.php?tourn_id=" + tourn_id + "&settings_data=" + settings_data,
        method: 'GET',
        success:function(response)
        {
          alert(response);
        },
      });
  });

  $('#save_modal_button').click(function()
  {
    var score_data_1 = [];
    var break_data_1 = [];
    var score_data_2 = [];
    var break_data_2 = [];
    var to_break_data_1 = [];
    var to_break_data_2 = [];
    var scoredata_player_1 = new Array;
    var scoredata_player_2 = new Array;
    var game_score_1 = 0;
    var game_score_2 = 0;
    var tourn_id = $('#tourn_id').html();
    var element_id = $('#scores_element_id').html();
    var subStr = '_';
    var first = (element_id.split(subStr, 1).join(subStr).length);
    var row = parseInt(element_id.substring(0, first));
    var col = element_id.substring(first+1);
    var referee = $('#referee').find('option:selected').text();
    var marker = $('#marker').find('option:selected').text();
    var table_no = $('#table_no').val();
    var round = $('#round').val();
    var grade = $('#grade').val();
    var start = $('#start').val();
    var finish = $('#finish').val();
    var match_no = $('#match_no').val();
    for(i = 0; i < 7; i++) // max number of 'Best Of'
    {
      if($('#to_brk' + (i+1) + '_1').prop("checked"))
      {
        to_break_data_1[i] = 1;
      }
      else
      {
        to_break_data_1[i] = 0;
      }
      if($('#to_brk' + (i+1) + '_2').is(":checked"))
      {
        to_break_data_2[i] = 1;
      }
      else
      {
        to_break_data_2[i] = 0;
      }
      score_data_1[i] = $('#score' + (i+1) + '_1').val();
      break_data_1[i] = $('#brk' + (i+1) + '_1').val();
      score_data_2[i] = $('#score' + (i+1) + '_2').val();
      break_data_2[i] = $('#brk' + (i+1) + '_2').val();

      if(score_data_1[i] > score_data_2[i])
      {
        game_score_1++;
        $('#' + row + '_' + (col+1) + '_' + (col+1)).val(game_score_1);
      }
      else if(score_data_1[i] < score_data_2[i])
      {
        game_score_2++;
        $('#' + (row+1) + '_' + (col+1) + '_' + (col+1)).val(game_score_2);
      }
    }
    var subStr = '_';
    var first = (element_id.split(subStr, 1).join(subStr).length);
    var row = parseInt(element_id.substring(0, first));
    var col = parseInt(element_id.substring(first+1));
    
    var player_name_1 = $('#' + (row+1) + "_" + col + "_" + col).val();
    var player_name_2 = $('#' + (row) + "_" + col + "_" + col).val();
    var element_other = $('#' + (row-1) + "_" + col + "_" + col);
    if(game_score_1 > game_score_2)
    {
      if($('#' + row + '_' + (col+4) + '_' + (col+4)).val() === '')
      {
        $('#' + row + '_' + (col+4) + '_' + (col+4)).val($('#' + row + '_' + (col) + '_' + (col)).val());
      }
      else if($('#' + (row+1) + '_' + (col+4) + '_' + (col+4)).val() === '')
      {
        $('#' + (row+1) + '_' + (col+4) + '_' + (col+4)).val($('#' + row + '_' + (col) + '_' + (col)).val());
      }
    }
    if(game_score_1 < game_score_2)
    {
      if($('#' + (row+1) + '_' + (col+4) + '_' + (col+4)).val() === '')
      {
        $('#' + (row+1) + '_' + (col+4) + '_' + (col+4)).val($('#' + (row+1) + '_' + (col) + '_' + (col)).val());
      }
      else if($('#' + (row) + '_' + (col+4) + '_' + (col+4)).val() === '')
      {
        $('#' + (row) + '_' + (col+4) + '_' + (col+4)).val($('#' + (row) + '_' + (col) + '_' + (col)).val());
      }
    }
    $('#' + row + '_' + (col+1) + '_' + (col+1)).val(game_score_1);
    $('#' + (row+1) + '_' + (col+1) + '_' + (col+1)).val(game_score_2);
    scoredata_player_1 = 
          row + ", " + 
          col + ", " + 
          player_name_1 + ", " + 
          game_score_1 + ", " + 
          score_data_1 + ", " + 
          break_data_1 + ", " + 
          to_break_data_1 + ", " + 
          parseInt(element_id);
    scoredata_player_2 = 
          (row+1) + ", " + 
          col + ", " + 
          player_name_2 + ", " + 
          game_score_2 + ", " + 
          score_data_2 + ", " + 
          break_data_2 + ", " + 
          to_break_data_2 + ", " + 
          parseInt(element_other);
    scoredata_player_3 = $.fn.get_draw(element_id);
    scoredata_player_4 = 
          referee + ", " + 
          marker + ", " + 
          table_no + ", " + 
          round + ", " + 
          grade + ", " + 
          start + ", " + 
          finish + ", " + 
          match_no;
    scoredata_player_1 = JSON.stringify(scoredata_player_1);
    scoredata_player_2 = JSON.stringify(scoredata_player_2);
    scoredata_player_3 = JSON.stringify(scoredata_player_3);
    scoredata_player_4 = JSON.stringify(scoredata_player_4);
    $.ajax({
      url:"save_result_data.php?tourn_id=" + tourn_id + "&score_data_1=" + scoredata_player_1 + "&score_data_2=" + scoredata_player_2 + "&score_data_3=" + scoredata_player_3 + "&score_data_4=" + scoredata_player_4,
      method: 'GET',
      success:function(response)
      {
        for(i = 0; i < 7; i++)
        {
          score_data_1[i] = $('#score' + (i+1) + '_1').val('');
          break_data_1[i] = $('#brk' + (i+1) + '_1').val('');
          to_break_data_1[i] = $('#to_brk' + (i+1) + '_1').val('');

          score_data_2[i] = $('#score' + (i+1) + '_2').val('');
          break_data_2[i] = $('#brk' + (i+1) + '_2').val('');
          to_break_data_2[i] = $('#to_brk' + (i+1) + '_2').val('');
        }
        $('#scores_modal').modal('hide');
      },
    });
  });

  var availableTags = <?php echo $player_data; ?>;
  $("#tags").autocomplete({
    source:  availableTags,
    appendTo: "#autocompleteAppendToMe"
  });

  $('.player').on('click', function(e) 
  {
    e.preventDefault();
    $('#change_players_modal').modal('show');
    let id = $(this).attr('id');
    $('#element_id').html(id);
    player_name = $('#' + id).val();
    $('#existing_player').val(player_name);
  });

  $('.score').on('click', function(e) 
  {
    alert("Presently not activated");
/*     
    e.preventDefault();
    $('#scores_modal').modal('show');
    var id = $(this).attr('id');
    $('#scores_element_id').html(id);
    player_name_1 = $.fn.get_player_1(id);
    $('#playername_1').html(player_name_1);
    player_name_2 = $.fn.get_player_2(id, 1);
    player_name = player_name_2.split(", ");
    row_2 = (player_name[1]);
    player_name_2 = (player_name[0]);
    $('#playername_2').html(player_name_2);
    var tourn_id = $('#tourn_id').html();

    var subStr = '_';
    var first = (id.split(subStr, 1).join(subStr).length);
    var row_1 = parseInt(id.substring(0, first));
    var col = parseInt(id.substring(first+1));

    $.ajax({
      url:"get_result_data.php?tourn_id=" + tourn_id + "&player_name_1=" + player_name_1 + "&row_1=" + row_1 + "&col=" + col + "&player_name_2=" + player_name_2 + "&row_2=" + row_2 + "&col=" + col,
      method: 'GET',
      success:function(data)
      {
        if(data === 'No Data')
        {
          for (var i = 0; i < 26; i++) 
          {
            $('#member_id_1').val('');
            if((i > 0) && (i <= 7))
            {
              $('#score' + (i) + '_1').val('');
            }
            if((i > 7) && (i <= 14))
            {
              $('#brk' + (i-7) + '_1').val('');
            }
            if((i > 14) && (i <= 21))
            {
              $('#to_brk' + (i-7) + '_1').val('');
            }
            $('#game_score_1').val('');
            $('#referee').val('');
            $('#roving').val('');
            $('#self').val('');
            $('#marker').val('');
            $('#table_no').val('');
            $('#round').val('');
            $('#grade').val('');
            $('#start').val('');
            $('#finish').val('');
            $('#match_no').val('');
          }
          for (var i = 0; i < 26; i++) 
          {
            $('#member_id_2').val('');
            if((i > 0) && (i <= 7))
            {
              $('#score' + (i) + '_2').val('');
            }
            if((i > 7) && (i <= 14))
            {
              $('#brk' + (i-7) + '_2').val('');
            }
             if((i > 14) && (i <= 21))
            {
              $('#to_brk' + (i-7) + '_2').val('');
            }
            $('#game_score_2').val('');
          }
        }
        else
        {
          var newData = data.split(':');
          var member_1_data = newData[0].split(", ");
          var member_2_data = newData[1].split(", ");
          if((member_1_data.length > 1) || (member_2_data.length > 1))
          {
            for (var i = 0; i < member_1_data.length; i++) 
            {
              $('#member_id_1').val(member_1_data[0]);
              if((i > 0) && (i <= 7))
              {
                $('#score' + (i) + '_1').val(member_1_data[(i)]);
              }
              if((i > 7) && (i <= 14))
              {
                $('#brk' + (i-7) + '_1').val(member_1_data[(i)]);
              }
              if((i > 14) && (i <= 21))
              {
                $('#to_brk' + (i-7) + '_1').val(member_1_data[(i)]);
              }
              $('#game_score_1').val(member_1_data[(22)]);
              $('#referee').val(member_1_data[(23)]);
              $('#roving').val(member_1_data[(24)]);
              $('#self').val(member_1_data[(25)]);
              $('#marker').val($.trim(member_1_data[(26)]));
              $('#table_no').val(member_1_data[(27)]);
              $('#round').val(member_1_data[(28)]);
              $('#grade').val(member_1_data[(29)]);
              $('#start').val(member_1_data[(30)]);
              $('#finish').val(member_1_data[(31)]);
              $('#match_no').val(member_1_data[(32)]);
            }
            for (var i = 0; i < member_2_data.length; i++) 
            {
              $('#member_id_2').val(member_2_data[0]);
              if((i > 0) && (i <= 7))
              {
                $('#score' + (i) + '_2').val(member_2_data[(i)]);
              }
              if((i > 7) && (i <= 14))
              {
                $('#brk' + (i-7) + '_2').val(member_2_data[(i)]);
              }
              if((i > 14) && (i <= 21))
              {
                $('#to_brk' + (i-7) + '_2').val('');
              }
              $('#game_score_2').val(member_2_data[(22)]);
            }
          }
        }
          
      },
      error: function() 
      {
        alert("There was an error. Try again please!");
      }
    });
*/  
  });

  $('.timepicker').timepicker({
    timeFormat: 'H:mm',
    interval: 15,
    minTime: '8',
    dynamic: false,
    dropdown: true,
    scrollbar: true,
    change: function(time) {
      id = $(this).attr('id');
      var tourn_id = <?= $tourn_id ?>;
      var element = $(this), text;
      var timepicker = element.timepicker();
      time = timepicker.format(time);
      var subStr = '_';
      var first = (id.split(subStr, 1).join(subStr).length);
      var row = parseInt(id.substring(0, first));
      var col = id.substring(first+1);
      // add to day entry
      $.ajax({
        url:"save_time.php?tourn_id=" + tourn_id + "&row=" + row + "&col=" + (col-1) + "&time=" + time,
        method: 'GET',
        success:function(response)
        {
          console.log(response);
        }
      });
    }
  });

  $('.date_select').on('change', function() 
  {
    var id1 = $(this).attr('id');
    var id = id1.substring(1);
    var day = $(this).val();
    var tourn_id = <?= $tourn_id ?>;
    var subStr = '_';
    var first = (id.split(subStr, 1).join(subStr).length);
    var row = parseInt(id.substring(0, first));
    var col = id.substring(first+1);
    // add to time entry
    $.ajax({
      url:"save_day.php?tourn_id=" + tourn_id + "&row=" + row + "&col=" + col+ "&day=" + day,
      method: 'GET',
      success:function(response)
      {
        console.log(response);
      }
    });
  });

  $('.to_break').on('change', function(e) 
  {
    e.preventDefault();
    $('#to_brk1_1').prop('checked', true);
    $('#to_brk3_1').prop('checked', true);
    $('#to_brk5_1').prop('checked', true);
    $('#to_brk7_1').prop('checked', true);
    $('#to_brk2_2').prop('checked', true);
    $('#to_brk4_2').prop('checked', true);
    $('#to_brk6_2').prop('checked', true);
  });

  $('.enter_score').focusout(function(e) 
  {
    e.preventDefault();
    var id = $(this).attr('id');
    var subStr = '_';
    var first = (id.split(subStr, 1).join(subStr).length);
    var row = parseInt(id.substring(0, first));
    var col = parseInt(id.substring(first+1));
    var tourn_id = <?= $tourn_id ?>;
    console.log("Column " + col);

    /*
    if(tourn_size === 128)
    {
      // round 1
      next_round_col = 0;
      if(col == 4)
      {
        shift_col = 15;
      }
      if(col == 8)
      {
        shift_col = 11;
      }
      if(col == 12)
      {
        shift_col = 7;
      }
      if(col == 16)
      {
        shift_col = 3;
      }
      // round 2
      if(col == 20)
      {
        shift_col = 7;
      }
      if(col == 24)
      {
        shift_col = 3;
      }
    }
    else if(tourn_size === 64)
    {
      shift_col = 0;
    }
    */

    if(col < 28)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);
      var next_score = $.fn.get_score_2(id, 1);
      var next_value = parseInt(next_score['value']);

      if(next_value != NaN)
      {
        if(!isNaN(current_value) && !isNaN(next_value)) 
        {
          var winner_name = '';
          var winner_player_row = 0;
          var loser_player_row = 0;
          if (current_value > next_value) 
          {
              winner_player_row = current_score['row'];
              loser_player_row = next_score['row'];
              winner_score = current_value;
              loser_score = next_value;
          } 
          else if (next_value > current_value) 
          {
              winner_player_row = next_score['row'];
              loser_player_row = current_score['row'];
              winner_score = next_value;
              loser_score = current_value;
          }
          var opposition_player;
          var opposition_row;
          var player_10 = $('#' + (current_score['row']) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val();
          var player_20 = $('#' + (row+1) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val();
          var player_30 = $('#' + (row-1) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val();
          if((player_10 != undefined) && (player_10 != ''))
          {
            opposition_player = player_10;
            opposition_row = (current_score['row']);
          }
          if((player_20 != undefined) && (player_20 != ''))
          {
            opposition_player = player_20;
            opposition_row = (row+1);
          }
          if((player_30 != undefined) && (player_30 != ''))
          {
            opposition_player = player_30;
            opposition_row = (row-1);
          }
          var new_winner_row;
          if (winner_player_row > 0) 
          {
            winner_name = $('#' + winner_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            loser_name = $('#' + loser_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            if(winner_player_row === opposition_row)
            {
              if($('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])+3)).length > 0)
              {
                $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
                var new_winner_row = loser_player_row;
              }
              else if($('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])+3)).length > 0)
              {
                $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
                var new_winner_row = winner_player_row;
              }
            }
            else if(loser_player_row === opposition_row)
            {
              if($('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])+3)).length > 0)
              {
                $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
                var new_winner_row = winner_player_row;
              }
              else if($('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])+3)).length > 0)
              {
                $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
                var new_winner_row = loser_player_row;
              }
            }
          }
        }
        var new_winner_col = (col+3);
        var player_1 = $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
        var player_2 = $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
      }
    }

    if(col == 28)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);
      var next_score = $.fn.get_score_2(id, 1);
      var next_value = parseInt(next_score['value']);
      if(next_value != NaN)
      {
        if(!isNaN(current_value) && !isNaN(next_value)) 
        {
          var winner_name = '';
          var winner_player_row = 0;
          var loser_player_row = 0;
          if (current_value > next_value) 
          {
              winner_player_row = current_score['row'];
              loser_player_row = next_score['row'];
              winner_score = current_value;
              loser_score = next_value;
          } 
          else if (next_value > current_value) 
          {
              winner_player_row = next_score['row'];
              loser_player_row = current_score['row'];
              winner_score = next_value;
              loser_score = current_value;
          }
          var new_winner_row;
          if (winner_player_row > 0) 
          {
            winner_name = $('#' + winner_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            loser_name = $('#' + loser_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            if($('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])+3)).length > 0)
            {
              $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
              var new_winner_row = winner_player_row;
            }
            else if($('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])+3)).length > 0)
            {
              $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
              var new_winner_row = loser_player_row;
            }
          }
        }
        var new_winner_col = (col+3);
        var player_1 = $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
        var player_2 = $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
      }
    }

    if(col == 32)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);
      var next_score = $.fn.get_score_2(id, 2);
      var next_value = parseInt(next_score['value']);
      if(next_value != NaN)
      {
        if(!isNaN(current_value) && !isNaN(next_value)) 
        {
          var winner_name = '';
          var winner_player_row = 0;
          var loser_player_row = 0;
          if (current_value > next_value) 
          {
              winner_player_row = current_score['row'];
              loser_player_row = next_score['row'];
              winner_score = current_value;
              loser_score = next_value;
          } 
          else if (next_value > current_value) 
          {
              winner_player_row = next_score['row'];
              loser_player_row = current_score['row'];
              winner_score = next_value;
              loser_score = current_value;
          }
          var new_winner_row;
          if (winner_player_row > 0) 
          {
            winner_name = $('#' + winner_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            loser_name = $('#' + loser_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            if(winner_player_row > loser_player_row)
            {
              $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
              new_winner_row = (winner_player_row-1);
            }
            else if(winner_player_row < loser_player_row)
            {
              $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
              new_winner_row = (loser_player_row-1);
            }
          }
        }
        var new_winner_col = (parseInt(current_score['col1'])+3);
        var player_1 = $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
        var player_2 = $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
      }
    }

    if(col == 36)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);
      var next_score = $.fn.get_score_2(id, 6);
      var next_value = parseInt(next_score['value']);
      //console.log("Current Value " + current_value);
      //console.log("Next Value " + next_value);
      if(next_value != NaN)
      {
        if(!isNaN(current_value) && !isNaN(next_value)) 
        {
          var winner_name = '';
          var winner_player_row = 0;
          var loser_player_row = 0;
          if (current_value > next_value) 
          {
              winner_player_row = current_score['row'];
              loser_player_row = next_score['row'];
              winner_score = current_value;
              loser_score = next_value;
          } 
          else if (next_value > current_value) 
          {
              winner_player_row = next_score['row'];
              loser_player_row = current_score['row'];
              winner_score = next_value;
              loser_score = current_value;
          }
          var new_winner_row;
          if (winner_player_row > 0) 
          {
            winner_name = $('#' + winner_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            loser_name = $('#' + loser_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            //console.log("Winner Row " + winner_player_row);
            //console.log("Loser Row " + loser_player_row);
            if(winner_player_row > loser_player_row)
            {
              new_winner_row = (winner_player_row-3);
              //console.log("Winner Row 1 " + new_winner_row);
              //console.log("Winner Col 1 " + (parseInt(current_score['col1'])+3));
              $('#' + (new_winner_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
            }
            else if(winner_player_row < loser_player_row)
            {
              new_winner_row = (loser_player_row-3);
              //console.log("Winner Row 2 " + new_winner_row);
              //console.log("Winner Col 2 " + (parseInt(current_score['col1'])+3));
              $('#' + (new_winner_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
            }
          }
        }
        //console.log("New Winner Row " + new_winner_row);
        var new_winner_col = (parseInt(current_score['col1'])+3);
        var player_1 = $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
        var player_2 = $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
      }
    }

    if(col == 40)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);
      var next_score = $.fn.get_score_2(id, 12);
      var next_value = parseInt(next_score['value']);
      //console.log("Current Value " + current_value);
      //console.log("Next Value " + next_value);
      if(next_value != NaN)
      {
        if(!isNaN(current_value) && !isNaN(next_value)) 
        {
          var winner_name = '';
          var winner_player_row = 0;
          var loser_player_row = 0;
          if (current_value > next_value) 
          {
              winner_player_row = current_score['row'];
              loser_player_row = next_score['row'];
              winner_score = current_value;
              loser_score = next_value;
          } 
          else if (next_value > current_value) 
          {
              winner_player_row = next_score['row'];
              loser_player_row = current_score['row'];
              winner_score = next_value;
              loser_score = current_value;
          }
          var new_winner_row;
          if (winner_player_row > 0) 
          {
            winner_name = $('#' + winner_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            loser_name = $('#' + loser_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            //console.log("Winner Row " + winner_player_row);
            //console.log("Loser Row " + loser_player_row);
            if(winner_player_row > loser_player_row)
            {
              new_winner_row = (winner_player_row-3);
              //console.log("Winner Row 1 " + new_winner_row);
              //console.log("Winner Col 1 " + (parseInt(current_score['col1'])+3));
              $('#' + (new_winner_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
            }
            else if(winner_player_row < loser_player_row)
            {
              new_winner_row = (loser_player_row-3);
              //console.log("Winner Row 2 " + new_winner_row);
              //console.log("Winner Col 2 " + (parseInt(current_score['col1'])+3));
              $('#' + (new_winner_row) + '_' + (parseInt(current_score['col1'])+3) + '_' + (parseInt(current_score['col1'])+3)).val(winner_name);
            }
            if(winner_player_row < 25)
            {
              new_winner_row = 27; // first final row
            }
            else if(winner_player_row > 35)
            {
              new_winner_row = 28; // second final row
            }
            //console.log("Winner Name" + winner_name);
            //console.log("New Winner Row " + new_winner_row);
            var new_winner_col = (parseInt(current_score['col1'])+1);
            $('#' + (new_winner_row) + '_' + new_winner_col + '_' + new_winner_col).val(winner_name);
          }
        }
        //console.log("New Winner Row " + new_winner_row);
        var player_1 = $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
        var player_2 = $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
      }

    }

    if(col == 42)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);
      var next_score = $.fn.get_score_2(id, 1);
      var next_value = parseInt(next_score['value']);
      //console.log("Current Value " + current_value);
      //console.log("Next Value " + next_value);
      if(next_value != NaN)
      {
        if(!isNaN(current_value) && !isNaN(next_value)) 
        {
          var winner_name = '';
          var winner_player_row = 0;
          var loser_player_row = 0;
          if (current_value > next_value) 
          {
              winner_player_row = current_score['row'];
              loser_player_row = next_score['row'];
              winner_score = current_value;
              loser_score = next_value;
          } 
          else if (next_value > current_value) 
          {
              winner_player_row = next_score['row'];
              loser_player_row = current_score['row'];
              winner_score = next_value;
              loser_score = current_value;
          }
          var new_winner_row;
          if (winner_player_row > 0) 
          {
            winner_name = $('#' + winner_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            loser_name = $('#' + loser_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();
            //console.log("Winner Row " + winner_player_row);
            //console.log("Loser Row " + loser_player_row);
            new_winner_row = 29; // grand final row
            //console.log("Winner Name" + winner_name);
            //console.log("New Winner Row " + new_winner_row);
            var new_winner_col = 41;
            //console.log("New Winner Col " + new_winner_col);
            $('#' + (new_winner_row) + '_' + new_winner_col + '_' + new_winner_col).val(winner_name);
          }
        }
        var player_1 = $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
        var player_2 = $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
      }
    }

    if(!isNaN(current_value) && !isNaN(next_value)) 
    {
      /*
      console.log("Tourn ID " + tourn_id);
      console.log("Player 1 " + player_1);
      console.log("Player 2 " + player_2);
      console.log("Winner Row " + winner_player_row);
      console.log("Loser Row " + loser_player_row);
      console.log("Winner Score " + winner_score);
      console.log("Opp Score " + next_value);
      console.log("Common Column " + col);
      console.log("Winner Name " + winner_name);
      console.log("New Winner Row " + new_winner_row);
      console.log("New Winner Col " + new_winner_col);
      */
      $.ajax({
        url:"save_match_score.php?tourn_id=" + tourn_id + "&player_1=" + player_1 + "&player_2=" + player_2 + "&winner_row_no=" + winner_player_row + "&opp_row_no=" + loser_player_row + "&winner_score=" + winner_score + "&opp_score=" + loser_score + "&common_col=" + (col) + "&winner_name=" + winner_name + "&move_to_row=" + new_winner_row + "&move_to_col=" + new_winner_col,
        method: 'GET',
        success:function(response)
        {
          console.log(response);
        },
      });
    }

  });

  // pagination of tournament players
  let rowsPerPage = 20; // show 20 at a time
  let rows = $("#players_table tr"); // includes header row
  let totalRows = rows.length - 1;   // exclude header
  let totalPages = Math.ceil(totalRows / rowsPerPage);
  let currentPage = 1;

  function showPage(page) 
  {
    // bounds check
    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;
    currentPage = page;
    // hide all except header
    rows.hide();
    rows.eq(0).show(); // keep header
    let start = (page - 1) * rowsPerPage + 1; // +1 to skip header
    let end = start + rowsPerPage;
    for (let i = start; i < end && i < rows.length; i++) {
      rows.eq(i).show();
    }
    // update button states
    $("#prevPage").prop("disabled", currentPage === 1);
    $("#nextPage").prop("disabled", currentPage === totalPages);
    $("#pageIndicator").text("Page " + currentPage + " of " + totalPages);
  }

  // build pagination controls
  let controls = '<button type="button" class="btn btn-sm btn-secondary m-1" id="prevPage">Prev</button><span id="pageIndicator" class="mx-2"></span><button type="button" class="btn btn-sm btn-secondary m-1" id="nextPage">Next</button>';
  $("#pagination_controls").html(controls);
  // attach handlers
  $(document).on("click", "#prevPage", function(e) 
  {
    e.preventDefault();
    showPage(currentPage - 1);
  });
  $(document).on("click", "#nextPage", function(e) 
  {
    e.preventDefault();
    showPage(currentPage + 1);
  });
  // show first page initially
  showPage(1);

});
</script>

<!-- List Player Modal -->
<div class="modal fade" id="list_players_modal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header ui-front">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title">List of Players</h3>
      </div>
      <div class="modal-body">
        <center>
        <table id="players_table" style='width:300px;' border='1'>
        <?php
        echo("<tr>");
        echo("<td align='center'><h4>Index</h4></td>");
        echo("<td align='center'><h4>Players Name</h4></td>");
        echo("<td align='center'><h4>Seed</h4></td>");
        //echo("<td align='center'><h4>Ranking</h4></td>");
        echo("<td align='center'><h4>Score</h4></td>");
        echo("</tr>");
        $i = 1;
        mysqli_data_seek($result_players, 0);
        while($build_players = $result_players->fetch_assoc())
        {
          echo('<input type="hidden" id="member_id" value=' . $build_players['tourn_memb_id'] . '>');
          echo("<tr>");
          echo("<td align='center'>" . $i . "</td>");
          echo("<td nowrap>" . $build_players['FirstName'] . " " . $build_players['LastName'] . "</td>");
          if($build_players['seed'] == '')
          //if($build_players['ranknum'] == '')
          {
            $ranked = 0;
          }
          else
          {
            //$ranked = $build_players['ranknum'];
            $ranked = $build_players['seed'];
          }
          echo("<td align='center'>" . $ranked . "</td>");
          echo("<td align='center'>0</td>");
          echo("</tr>");
          $i++;
        }
        for($x = $i; $x <= $tourn_size; $x++)
        {
          echo("<tr>");
          echo("<td align='center'>" . $x . "</td>");
          echo("<td>Bye</td>");
          echo("<td align='center'>0</td>");
          echo("<td align='center'>0</td>");
          echo("</tr>");
        }
        ?>
        </table>
        <div>&nbsp;</div>
        <div id="pagination_controls" class="text-center"></div>
        <br>
        <br>
      </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

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
              <div id="element_id"></div>
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

<!-- Settings Modal -->
<div class="modal fade" id="settings_modal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header ui-front">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tournament Settings</h4>
      </div>
      <div class="modal-body">
        <center>
        <table style='width:500px;'>
          <tr>
            <td colspan='2'>&nbsp;</td>
          </tr>
          <tr>
            <td align='center' colspan='2'><b>Select number of 'Best of' matches</b></td>
          </tr>
          <tr>
            <td colspan='2'>&nbsp;</td>
          </tr>
          <?php
          for($i = 0; $i < $no_of_rounds; $i++)
          {
            echo("<tr>
              <td colspan='2' align='center'>Round " . ($i+1) . ":&nbsp;
                <select name='best_of_" . ($i+1) . "' id='best_of_" . ($i+1) . "'>
                  <option value=''>&nbsp;</option>
                  <option value='3'>Best of 3</option>
                  <option value='5'>Best of 5</option>
                  <option value='7'>Best of 7</option>
                </select>
              </td>
            </tr>
            <tr><td>&nbsp;</td></tr>");
          }
          ?>
        </table>
        <br>
        <br>
        <div class='text-center ui-widget'><a class='btn btn-primary btn-xs' id='save_settings'>Save Settings</a>
        <br>
        <div>&nbsp;</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
            <td align='center'><font color="red"><h4>Breaks should be seperated by a space.</h4></font></td>
          </tr>
          <tr>
            <td align='center'>
                <table class='table table-striped table-bordered' style='width:250px;'>
                  <tr>
                    <td colspan='8' align='center'>Enter Scores/Breaks for <div id="playername_1"></div><div hidden id="member_id_1"></div><div hidden id="scores_element_id"></div></td>
                  </tr>
                  <tr>
                    <td align='center'>Frame 1</td>
                    <td align='center'>Frame 2</td>
                    <td align='center'>Frame 3</td>
                    <td align='center'>Frame 4</td>
                    <td align='center'>Frame 5</td>
                    <td align='center'>Frame 6</td>
                    <td align='center'>Frame 7</td>
                    <td align='center'>Best Of 7</td>
                  </tr>
                  <tr>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td rowspan='5' align='center'><br><br><br>Frames:<br><input type='text' id='game_score_1' style='width:20px; height:20px'></td>
                  </tr>
                  <tr>
                    <td align='center'><input type='text' id='score1_1' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score2_1' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score3_1' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score4_1' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score5_1' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score6_1' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score7_1' style='width:40px; height:20px'></td>
                  </tr>
                  <tr>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                  </tr>
                  <tr>
                    <td align='center'><input type='text' id='brk1_1' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk2_1' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk3_1' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk4_1' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk5_1' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk6_1' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk7_1' style='width:50px; height:20px'></td>
                  </tr>
                  <tr>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk1_1'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk2_1'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk3_1'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk4_1'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk5_1'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk6_1'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk7_1'></td>
                  </tr>
                </table>
                <table>
                    <tr>
                        <th align='center'><h3>Versus</h3></th>
                    </tr>
                    <tr>
                        <th align='center'>&nbsp;</th>
                    </tr>
                </table>
                <table class='table table-striped table-bordered' style='width:250px;'>
                  <tr>
                    <td colspan='8' align='center'>Enter Scores/Breaks for <div id="playername_2"></div><div hidden id="member_id_2"></div></td>
                  </tr>
                  <tr>
                    <td align='center'>Frame 1</td>
                    <td align='center'>Frame 2</td>
                    <td align='center'>Frame 3</td>
                    <td align='center'>Frame 4</td>
                    <td align='center'>Frame 5</td>
                    <td align='center'>Frame 6</td>
                    <td align='center'>Frame 7</td>
                    <td align='center'>Best Of 7</td>
                  </tr>
                  <tr>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td align='center'>Points</td>
                    <td rowspan='5' align='center'><br><br><br>Frames:<br><input type='text' id='game_score_2' style='width:20px; height:20px'></td>
                  </tr>
                  <tr>
                    <td align='center'><input type='text' id='score1_2' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score2_2' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score3_2' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score4_2' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score5_2' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score6_2' style='width:40px; height:20px'></td>
                    <td align='center'><input type='text' id='score7_2' style='width:40px; height:20px'></td>
                  </tr>
                  <tr>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                    <td align='center'>Breaks 40+</td>
                  </tr>
                  <tr>
                    <td align='center'><input type='text' id='brk1_2' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk2_2' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk3_2' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk4_2' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk5_2' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk6_2' style='width:50px; height:20px'></td>
                    <td align='center'><input type='text' id='brk7_2' style='width:50px; height:20px'></td>
                  </tr>
                  <tr>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk1_2'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk2_2'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk3_2'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk4_2'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk5_2'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk6_2'></td>
                    <td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk7_2'></td>
                  </tr>
                </table>
              </td>
            </tr>
          </td>
          </table>
          <table class='table table-striped table-bordered' style='width:300px;'>
            <tr>
                <td colspan='8' align='center'><b>To be entered by the Referee.</b></td>
            </tr>
            <tr>
              <td colspan='8' align='center'><font color="red">* required</font></td>
            </tr>
            <tr>
              <td colspan='4' align='center' style='width:100px; height:20px'><font color="red">*</font> Referee</td>
              <td colspan='4' ><select id="referee" style='width:160px; height:20px'>
              <?php
              // get list of referees
              $query_referees = 'Select Concat(FirstName, " ",  LastName) as fullname FROM vbsa3364_vbsa2.members Where referee = 1 order by LastName';
              $result_referees = mysql_query($query_referees, $connvbsa) or die(mysql_error());
              echo("<option value=''>&nbsp;</option>");
              while($build_referees = $result_referees->fetch_assoc())
              {
                 echo("<option value='" . $build_referees['fullname'] . "'>" . $build_referees['fullname'] . "</option>");
              }
              ?>
              </select></td>
            </tr>
            <tr>
              <td colspan='4' align='center'><font color="red">*</font> Roving Referee <input type='checkbox' id='roving'></td>
              <td colspan='4' align='center'>Self Referee <input type='checkbox' id='self'></td>
            </tr>
            <tr>
              <td colspan='4' align='center'>Marker</td>
              <td colspan='4' ><select id="marker" style='width:160px; height:20px'>
              <?php
              // get list of markers
              mysqli_data_seek($result_players, 0);
              echo("<option value=''>&nbsp;</option>");
              while($build_markers = $result_players->fetch_assoc())
              {
                 echo("<option value='" . $build_markers['FirstName'] . " " . $build_markers['LastName'] . "'>" . $build_markers['FirstName'] . " " . $build_markers['LastName'] . "</option>");
              }
              ?>
              </select></td>
            </tr>
            <tr>
              <td colspan='2' align='center'>Table</td>
              <td align='center'>Round</td>
              <td align='center'>Grade</td>
              <td align='center'>Start</td>
              <td align='center'>Finish</td>
              <td colspan='2' align='center'>Match No.</td>
            </tr>
            <tr>
              <td colspan='2' align='center'><input type='text' id='table_no' value=0 style='width:50px; height:20px'></td>
              <td align='center'><input type='text' id='round' value=0 style='width:50px; height:20px'></td>
              <td align='center'><input type='text' id='grade' value='' style='width:50px; height:20px'></td>
              <td align='center'><input type='text' id='start' value='' style='width:50px; height:20px'></td>
              <td align='center'><input type='text' id='finish' value='' style='width:50px; height:20px'></td>
              <td colspan='2' align='center'><input type='text' id='match_no' value=0 style='width:50px; height:20px'></td>
            </tr>
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

</center>
</form>
</body>
</html>



