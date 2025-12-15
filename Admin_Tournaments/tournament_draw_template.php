<?php 
require_once '../Connections/connvbsa.php'; 
error_reporting(0);

mysql_select_db($database_connvbsa,$connvbsa);

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

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
<style>
.ui-timepicker-container {
  z-index: 99999 !important;   /* added to display timepicker dropdown in front of modal */
  position: absolute !important;
}
</style>

</head>
<body>

<?php //include('../vbsa_online_scores/header_vbsa.php') ;?>

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

function formatTime($time)
{
  // Format a specific timestamp
  $timestamp = strtotime($time_1);
  $new_time = date("h:i", $timestamp); // Output: 03:30 PM
  return $new_time;
}

$sql_session = "set session query_cache_type = OFF;";

echo("<script type='text/javascript'>");
echo("$(document).ready(function() { ");
echo("$.fn.fillelementarray = function () {");

//get player results from tournament_results table
$query_scores = 'Select * FROM vbsa3364_vbsa2.tournament_results where tourn_id = ' . $tourn_id;
$result_scores = mysql_query($query_scores, $connvbsa) or die(mysql_error());

while($build_scores = $result_scores->fetch_assoc())
{
  $id_scores = ($build_scores['row_no'] . '_' . ($build_scores['col_no']+1) . '_' . ($build_scores['col_no']+1));
  $id_name = $build_scores['row_no'] . '_' . ($build_scores['col_no']) . '_' . ($build_scores['col_no']);
  if(($build_scores['forfeit_1'] == 1) || ($build_scores['forfeit_2'] == 1))
  {
    $game_score = 'FF';
  }
  else if(($build_scores['walkover_1'] == 1) || ($build_scores['walkover_2'] == 1))
  {
    $game_score = 'WO';
  }
  else
  {
    $game_score = $build_scores['game_1'];
  }
  echo("$('#" . $id_scores . "').val('" . $game_score . "');\n");
  echo("$('#" . $id_name . "').val('" . GetMemberName($build_scores['memb_id']) . "');\n");

  //echo('console.log("' . $id_name . ' has a bye");');
}

//get results from tournament_day_time table
$query_times = 'Select * FROM vbsa3364_vbsa2.tournament_day_time where tourn_id = ' . $tourn_id;
$result_times = mysql_query($query_times, $connvbsa) or die(mysql_error());

while($build_times = $result_times->fetch_assoc())
{
  $id_day = ("D_" . $build_times['row_no'] . '_' . $build_times['col_no']);
  $id_time = ("T_" . $build_times['row_no'] . '_' . $build_times['col_no']);
  echo("$('#$id_day').val('{$build_times['day']}');\n");

  //$new_time = strtotime($build_times['time']);
  $new_time = date("h:i", strtotime($build_times['time']));


  echo("$('#$id_time').val('{$new_time}');\n");
  $key = ("D_" . $build_times['row_no'] . '_' . $build_times['col_no']);
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

// bet best of values from tournaments table
$sql_best_of = 'Select * from tournaments where tourn_id = ' . $tourn_id;
$result_best_of = mysql_query($sql_best_of, $connvbsa) or die(mysql_error());
$build_best_of = $result_best_of->fetch_assoc(); 

// select rank or seed for sort order
$sort_order = '';
if($build_tourn_name['sort'] == 'rank')
{
  $sort_order = 'rank';
  $sql_players = "Select ROW_NUMBER() OVER (ORDER BY 
  CASE WHEN seed = 1 
  THEN 0 
  ELSE 1 
  END,
  CASE WHEN ranknum IS NULL OR ranknum = 0 
  THEN 1 
  ELSE 0 
  END, 
  CASE WHEN ranknum IS NULL OR ranknum = 0 THEN LastName 
  END ASC,
  CASE WHEN ranknum IS NULL OR ranknum = 0 THEN FirstName 
  END ASC,
  ranknum ASC) AS row_num, tourn_memb_id, FirstName, LastName, ranked, rank_pts, seed, ranknum, tourn_type FROM tourn_entry Left join members on members.memberID = tourn_entry.tourn_memb_id Left Join rank_S_open_tourn on members.MemberID = rank_S_open_tourn.memb_id where tournament_number = '$tourn_id' Order By row_num";
}
else
{
  $sort_order = 'seed';
  $sql_players = "Select ROW_NUMBER() OVER (ORDER BY 
  CASE WHEN seed IS NULL OR seed = 0 
  THEN 1 
  ELSE 0 
  END, 
  seed ASC) AS row_num, tourn_memb_id, FirstName, LastName, ranked, rank_pts, seed, tourn_type FROM tourn_entry Left join members on members.memberID = tourn_entry.tourn_memb_id where tournament_number = '$tourn_id' Order By row_num";
}

//echo($sql_players . "<br>");
$result_players = mysql_query($sql_players, $connvbsa) or die(mysql_error());
$build_tourn = $result_players->fetch_assoc();
$tourn_type = $build_tourn['tourn_type'];
$no_of_players = $result_players->num_rows;

/*
128
112
96
80
64
48
32
16
*/

echo("Players " . $no_of_players . "<br>");
/*
if($no_of_players <= 8)
{
  $tourn_size = 8;
  $no_of_rounds = 3;
}
if(($no_of_players > 8) && ($no_of_players <= 16))
{
  $tourn_size = 16;
  $no_of_rounds = 4;
}
if(($no_of_players > 16) && ($no_of_players <= 32))
{
  $tourn_size = 32;
  $no_of_rounds = 5;
}
if(($no_of_players > 32) && ($no_of_players <= 64))
{
  $tourn_size = 64;
  $no_of_rounds = 7;
}
*/

if($no_of_players <= 64)
{
  $tourn_size = 64;
  $no_of_rounds = 7;
}
if(($no_of_players > 64) && ($no_of_players <= 96))
{
  $tourn_size = 96;
  $no_of_rounds = 10;
}

echo("Size " . $tourn_size . "<br>");
echo("Rounds " . $no_of_rounds . "<br>");

//$tourn_size = 128;

echo("<div hidden align='center' id='tourn_name'>" . $build_tourn_name['tourn_name'] . "</div>");
echo("<div align='center'><h3>" . $build_tourn_name['tourn_name'] . " (" . $tourn_size . " players)</h3></div>");
//echo("<div align='center'>" . $tourn_caption . "</div>");
echo("<div hidden align='center' id='tourn_id'>" . $tourn_id . "</div>");
echo("<br>");
//echo("<div align='center'>Start Date " . $build_tourn_name['startdate'] . " - Finish Date " . $build_tourn_name['finishdate'] . "</div>");
echo("<br>");


function isMobileDevice() 
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    // Common mobile device keywords
    $mobileKeywords = '/(android|iphone|ipod|blackberry|iemobile|opera mobile|palmos|webos|mobile|tablet)/i';
    if (preg_match($mobileKeywords, $userAgent)) 
    {
        return true;
    } 
    else 
    {
        return false;
    }
}

if(isMobileDevice()) 
{

?>
<table>
  <tr>
    <td align="center" class="greenbg"><button id="show_players" style="width: 300px;">Show all players for this tournament</button></td>
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
  </tr>
  <tr>
    <td align="center" class="greenbg" style="width: 50px;">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="greenbg" style="width: 50px;"><button id="save_players" <?= $added_player_caption ?> style="width: 300px;">Save Initial Player Allocation</button></td>
  </tr>
  <tr>
    <td align="center" class="greenbg" style="width: 50px;">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="greenbg" style="width: 50px;"><button id="reset_players" style="width: 300px;">Reset Initial Player Allocation</button></td>
  </tr>
  <tr>
    <td align="center" class="greenbg" style="width: 50px;">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="greenbg" style="width: 50px;"><button id="settings" style="width: 300px;">Settings</button></td>
  </tr>
  <tr>
    <td align="center" class="greenbg" style="width: 50px;">&nbsp;</td>
  </tr>
</table>
<table width="300" border="0" align="center">
  <tr>
    <td align="left"><button type="button" class="btn btn-primary" id="prevColumn"><< Back</button></td>
    <td align="right"><button type="button" class="btn btn-primary" id="nextColumn">Next >></button></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<?php
}
else
{
?>
<center>
<table>
  <tr>
    <td align="center" class="greenbg"><button id="show_players" style="width: 300px;">Show all players for this tournament</button></td>
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
    <td align="center" class="greenbg" style="width: 50px;">&nbsp;</td>
    <td align="center" class="greenbg" style="width: 50px;"><button id="save_players" <?= $added_player_caption ?> style="width: 300px;">Save Initial Player Allocation</button></td>
    <td align="center" class="greenbg" style="width: 50px;">&nbsp;</td>
    <td align="center" class="greenbg" style="width: 50px;"><button id="reset_players" style="width: 300px;">Reset Initial Player Allocation</button></td>
    <td align="center" class="greenbg" style="width: 50px;">&nbsp;</td>
    <td align="center" class="greenbg" style="width: 50px;"><button id="settings" style="width: 300px;">Settings</button></td>
    <td align="center" class="greenbg" style="width: 50px;">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</center>
<?php
}
?>

<div id="fixtureTable"></div>

<?php
echo('<table class="table table-striped table-bordered" style="width: 50%;">');
echo('<tr>');
echo('<td colspan="3" align="center"><h4>Breaks</h4></td>');
echo('</tr>');
echo('<tr>');
echo('<td align="center" style="width: 200px;"><b>Name</b></td>');
echo('<td align="center" style="width: 200px;"><b>Breaks</b></td>');
echo('</tr>');

$sql_select = "
  Select DISTINCT memb_id
  FROM tournament_results
  WHERE (breaks_1 > 0 OR breaks_2 > 0 OR breaks_3 > 0 OR breaks_4 > 0 OR breaks_5 > 0 OR breaks_6 > 0 OR breaks_7 > 0)
  AND tourn_id = $tourn_id
";
$result_players_breaks = mysql_query($sql_select, $connvbsa) or die(mysql_error());

$all_players = [];

while ($build_players = $result_players_breaks->fetch_assoc()) {
    $memb_id = $build_players['memb_id'];
    $player_name = GetMemberName($memb_id);

    $sql_players = "Select * FROM tournament_results WHERE memb_id = $memb_id AND tourn_id = $tourn_id";
    $result_breaks = mysql_query($sql_players, $connvbsa) or die(mysql_error());

    $breaks = [];
    while ($build_breaks = $result_breaks->fetch_assoc()) {
        for ($i = 1; $i <= 7; $i++) {
            $b = $build_breaks["breaks_$i"];
            if (is_numeric($b) && $b > 0) {
                $breaks[] = (int)$b;
            }
        }
    }

    if (!empty($breaks)) {
        rsort($breaks); // sort each player's breaks
        $all_players[] = [
            'Name' => $player_name,
            'Breaks' => $breaks
        ];
    }
}

// Sort all players by their highest break (descending)
usort($all_players, function ($a, $b) {
    return (max($b['Breaks']) <=> max($a['Breaks']));
});

// Display table
foreach ($all_players as $player) {
    $name = htmlspecialchars($player['Name']);
    $breaks_list = implode(' ', $player['Breaks']);
    echo "<tr><td>{$name}</td><td>{$breaks_list}</td></tr>";
}

echo("</table>");

?>
</table>
<script>
  tourn_size = <?= $tourn_size ?>;

  function hideEmptyRows(activeGroups) 
  {
    $("#fixtureTable tbody tr").each(function () 
    {
        let row = $(this);
        let hasData = false;
        // Check only the column groups that are currently visible
        activeGroups.forEach(function (group) {
            row.find('td.' + group).each(function () {
                let input = $(this).find('input');

                if (input.length) {
                    if (input.val().trim() !== "") {
                        hasData = true;
                    }
                } else {
                    let text = $(this).html().replace(/&nbsp;/g, '').trim();
                    if (text !== "") {
                        hasData = true;
                    }
                }
            });
        });
        if (hasData) row.show();
        else row.hide();
    });
  }

  function setupColumnNavigation() 
  {
    // --- REMOVE old click handlers (prevents stacking if table reloads)
    $('#nextColumn').off('click');
    $('#prevColumn').off('click');
    // --- Collect unique column class groups (column_5 → column_12)
    let columnGroups = [...new Set(
        $('#fixtureTable [class^="column_"]').map(function () {
            return $(this).attr('class');
        }).get()
    )];
    let showCount = 2;        // we always show 2 groups
    let currentIndex = 0;     // left-most visible group

    // remove day/time row
    let columnDTGroups = [...new Set(
        $('#fixtureTable [class^="DT_column_"]').map(function () {
            return $(this).attr('class');
        }).get()
    )];
    columnDTGroups.forEach(g => $('.' + g).hide());

    // --- Initial update
    // --- Function to show correct groups
    function updateColumns() 
    {
      columnGroups.forEach(g => $('.' + g).hide());

      let g1 = columnGroups[currentIndex];
      let g2 = columnGroups[currentIndex + 1];

      $('.' + g1).show();
      $('.' + g2).show();

      hideEmptyRows([g1, g2]);
    }
    updateColumns();
    // --- NEXT button
    $('#nextColumn').on('click', function () 
    {
        if (currentIndex < columnGroups.length - showCount) 
        {
            currentIndex++;
            updateColumns();
        }
    });
    // --- PREV button
    $('#prevColumn').on('click', function () 
    {
        if (currentIndex > 0) 
        {
            currentIndex--;
            updateColumns();
        }
    });
  }

  function DateSelect(play_date, index)
  {
    let parts = index.split('_');
    let row = parts[1] || '';
    let col = parts[2] || '';
    let play_date_text = play_date[index];
    if((play_date_text === undefined) || (play_date_text === null))
    {
      play_date_text = '';
    }
    let days = [
        "",
        "Sun",
        "Mon",
        "Tue",
        "Wed",
        "Thur",
        "Fri",
        "Sat"
    ];
    let html_Date = "<select id='" + index + "' class='date_select' data-row=" + row + " data-col=" + col + " style='width: 40px;'>";
    days.forEach(day => {
        let selected = (day === play_date_text) ? " selected" : "";
        html_Date += `<option value="${day}"${selected}>${day}</option>`;
    });
    html_Date += "</select>";
    return html_Date;
  }

  var match_no = 0;

  function R7(index, tourn_size) 
  {
    html_R7 = "";
    if(tourn_size == 128)
    {
      html_R7 += "<td nowrap align='center' class='column_1'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R7 += "<div id='match_" + index + "_1'></div></td>";
      html_R7 += "<td id='" + index + "_2' align='center' class='column_1'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value=''></td>";
      html_R7 += "<td nowrap align='center' class='column_2'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R7 += "<div id='match_" + index + "_3'></div></td>";
      html_R7 += "<td id='" + index + "_4' align='center' class='column_2'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value=''></td>";
      html_R7 += "<td nowrap align='center' class='column_3'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R7 += "<div id='match_" + index + "_5'></div></td>";
      html_R7 += "<td id='" + index + "_6' align='center' class='column_3'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value=''></td>";
      html_R7 += "<td nowrap align='center' class='column_4'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R7 += "<div id='match_" + index + "_7'></div></td>";
      html_R7 += "<td id='" + index + "_8' align='center' class='column_4'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value=''></td>";
    }
    else if(tourn_size == 96)
    {
      html_R7 += "<td nowrap align='center' class='column_3'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R7 += "<div id='match_" + index + "_5'></div></td>";
      html_R7 += "<td id='" + index + "_6' align='center' class='column_3'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value=''></td>";
      html_R7 += "<td nowrap align='center' class='column_4'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R7 += "<div id='match_" + index + "_7'></div></td>";
      html_R7 += "<td id='" + index + "_8' align='center' class='column_4'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value=''></td>";
    }
    html_R7 += "<td nowrap align='center' class='column_5'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R7 += "<div id='match_" + index + "_9'></div></td>";
    html_R7 += "<td id='" + index + "_10' align='center' class='column_5'><input type='text' style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R7 += "<td nowrap align='center' class='column_6'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R7 += "<div id='match_" + index + "_11'></div></td>";
    html_R7 += "<td id='" + index + "_12' align='center' class='column_6'><input type='text' style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R7 += "<td nowrap align='center' class='column_7'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R7 += "<div id='match_" + index + "_13'></td>";
    html_R7 += "<td id='" + index + "_14' align='center' class='column_7'><input type='text' style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R7 += "<td align='center' class='column_8'>";
    html_R7 += DateSelect(play_date, ('D_' + index + '_8'));
    html_R7 += "&nbsp;";
    html_R7 += "<input type='text' class='timepicker' id='T_" + index + "_8' data-row='" + index + "' data-col='8' value='' readonly size='6' /></td>";
    html_R7 += "<td class='column_8'>&nbsp;</td>";
    html_R7 += "<td class='column_9'>&nbsp;</td>";
    html_R7 += "<td class='column_9'>&nbsp;</td>";
    html_R7 += "<td class='column_10'>&nbsp;</td>";
    html_R7 += "<td class='column_10'>&nbsp;</td>";
    html_R7 += "<td class='column_11'>&nbsp;</td>";
    html_R7 += "<td class='column_11'>&nbsp;</td>";
    html_R7 += "<td class='column_12'>&nbsp;</td>";
    html_R7 += "<td class='test_column'>R7</td>";
    return html_R7;
  }

  function R8(index, tourn_size) 
  {
    html_R8 = "";
    if(tourn_size == 128)
    {
      html_R8 += "<td nowrap class='column_1' align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R8 += "<div id='match_" + index + "_1'></div></td>";
      html_R8 += "<td id='" + index + "_2' class='column_1' align='center'><input type='text' style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R8 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R8 += "<div id='match_" + index + "_3'></div></td>";
      html_R8 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text' style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R8 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R8 += "<div id='match_" + index + "_5'></div></td>";
      html_R8 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text' style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R8 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R8 += "<div id='match_" + index + "_7'></div></td>";
      html_R8 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    else if(tourn_size == 96)
    {
      html_R8 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R8 += "<div id='match_" + index + "_5'></div></td>";
      html_R8 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text' style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R8 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R8 += "<div id='match_" + index + "_7'></div></td>";
      html_R8 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R8 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value=''></td>";
    html_R8 += "<div id='match_" + index + "_9'></td>";
    html_R8 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R8 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R8 += "<div id='match_" + index + "_11'></td>";
    html_R8 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R8 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R8 += "<div id='match_" + index + "_13''></td>";
    html_R8 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R8 += "<td class='column_8'  nowrap align='center'><input type='text' class='player' id='" + index + "_15_15' value='' readonly></td>";
    html_R8 += "<div id='match_" + index + "_15'></td>";
    html_R8 += "<td class='column_8'  id='" + index + "_16' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_16_16' value='' readonly></td>";
    html_R8 += "<td class='column_9' >&nbsp;</td>";
    html_R8 += "<td class='column_9' >&nbsp;</td>";
    html_R8 += "<td class='column_10' >&nbsp;</td>";
    html_R8 += "<td class='column_10' >&nbsp;</td>";
    html_R8 += "<td class='column_11' >&nbsp;</td>";
    html_R8 += "<td class='column_11' >&nbsp;</td>";
    html_R8 += "<td class='column_12' >&nbsp;</td>";
    html_R8 += "<td class='test_column'>R8</td>";
    return html_R8;
  }

  function R9(index, tourn_size) 
  {
    html_R9 = "";
    if(tourn_size == 128)
    {
      html_R9 += "<td class='column_1' >&nbsp;</td>";
      html_R9 += "<td class='column_1' >&nbsp;</td>";
      html_R9 += "<td class='column_2' >&nbsp;</td>";
      html_R9 += "<td class='column_2' >&nbsp;</td>";
      html_R9 += "<td class='column_3' >&nbsp;</td>";
      html_R9 += "<td class='column_3' >&nbsp;</td>";
      html_R9 += "<td class='column_4' >&nbsp;</td>";
      html_R9 += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_R9 += "<td class='column_3' >&nbsp;</td>";
      html_R9 += "<td class='column_3' >&nbsp;</td>";
      html_R9 += "<td class='column_4' >&nbsp;</td>";
      html_R9 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R9 += "<td class='column_5' >&nbsp;</td>";
    html_R9 += "<td class='column_5' >&nbsp;</td>";
    html_R9 += "<td class='column_6' >&nbsp;</td>";
    html_R9 += "<td class='column_6' >&nbsp;</td>";
    html_R9 += "<td class='column_7' >&nbsp;</td>";
    html_R9 += "<td class='column_7' >&nbsp;</td>";
    html_R9 += "<td class='column_8' >&nbsp;</td>";
    html_R9 += "<td class='column_8' >&nbsp;</td>";
    html_R9 += "<td class='column_9'  nowrap align='center'><input type='text' class='player' id='" + index + "_17_17' value='' readonly></td>";
    html_R9 += "<div id='match_" + index + "_17'></div></td>";
    html_R9 += "<td class='column_9'  id='" + index + "_18' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_18_18' value='' readonly></td>";
    html_R9 += "<td class='column_10' >&nbsp;</td>";
    html_R9 += "<td class='column_10' >&nbsp;</td>";
    html_R9 += "<td class='column_11' >&nbsp;</td>";
    html_R9 += "<td class='column_11' >&nbsp;</td>";
    html_R9 += "<td class='column_12' >&nbsp;</td>";
    html_R9 += "<td class='test_column'>R9</td>";
    return html_R9;
  }

  function R10(index, tourn_size) 
  {
    html_R10 = "";
    if(tourn_size == 128)
    {
      html_R10 += "<td class='column_1' >&nbsp;</td>";
      html_R10 += "<td class='column_1' >&nbsp;</td>";
      html_R10 += "<td class='column_2' >&nbsp;</td>";
      html_R10 += "<td class='column_2' >&nbsp;</td>";
      html_R10 += "<td class='column_3' >&nbsp;</td>";
      html_R10 += "<td class='column_3' >&nbsp;</td>";
      html_R10 += "<td class='column_4' >&nbsp;</td>";
      html_R10 += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_R10 += "<td class='column_3' >&nbsp;</td>";
      html_R10 += "<td class='column_3' >&nbsp;</td>";
      html_R10 += "<td class='column_4' >&nbsp;</td>";
      html_R10 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R10 += "<td class='column_5' >&nbsp;</td>";
    html_R10 += "<td class='column_5' >&nbsp;</td>";
    html_R10 += "<td class='column_6' >&nbsp;</td>";
    html_R10 += "<td class='column_6' >&nbsp;</td>";
    html_R10 += "<td class='column_7' >&nbsp;</td>";
    html_R10 += "<td class='column_7' >&nbsp;</td>";
    html_R10 += "<td class='column_8' >&nbsp;</td>";
    html_R10 += "<td class='column_8' >&nbsp;</td>";
    html_R10 += "<td class='column_9' >&nbsp;</td>";
    html_R10 += "<td class='column_9' >&nbsp;</td>";
    html_R10 += "<td class='column_10'  nowrap align='center'><input type='text' class='player' id='" + index + "_19_19' value='' readonly></td>";
    html_R10 += "<div id='match_" + index + "_19'></div></td>";
    html_R10 += "<td class='column_10'  id='" + index + "_20' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_20_20' value='' readonly></td>";
    html_R10 += "<td class='column_11' >&nbsp;</td>";
    html_R10 += "<td class='column_11' >&nbsp;</td>";
    html_R10 += "<td class='column_12' >&nbsp;</td>";
    html_R10 += "<td class='test_column'>R10</td>";
    return html_R10;
  }

  function R11(index, tourn_size) 
  {
    html_R11 = "";
    if(tourn_size == 128)
    {
      html_R11 += "<td class='column_1' >&nbsp;</td>";
      html_R11 += "<td class='column_1' >&nbsp;</td>";
      html_R11 += "<td class='column_2' >&nbsp;</td>";
      html_R11 += "<td class='column_2' >&nbsp;</td>";
      html_R11 += "<td class='column_3' >&nbsp;</td>";
      html_R11 += "<td class='column_3' >&nbsp;</td>";
      html_R11 += "<td class='column_4' >&nbsp;</td>";
      html_R11 += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_R11 += "<td class='column_3' >&nbsp;</td>";
      html_R11 += "<td class='column_3' >&nbsp;</td>";
      html_R11 += "<td class='column_4' >&nbsp;</td>";
      html_R11 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R11 += "<td class='column_5' >&nbsp;</td>";
    html_R11 += "<td class='column_5' >&nbsp;</td>";
    html_R11 += "<td class='column_6' >&nbsp;</td>";
    html_R11 += "<td class='column_6' >&nbsp;</td>";
    html_R11 += "<td class='column_7' >&nbsp;</td>";
    html_R11 += "<td class='column_7' >&nbsp;</td>";
    html_R11 += "<td class='column_8' >&nbsp;</td>";
    html_R11 += "<td class='column_8' >&nbsp;</td>";
    html_R11 += "<td class='column_9' >&nbsp;</td>";
    html_R11 += "<td class='column_9' >&nbsp;</td>";
    html_R11 += "<td class='column_10' >&nbsp;</td>";
    html_R11 += "<td class='column_10' >&nbsp;</td>";
    html_R11 += "<td class='column_11'  nowrap align='center'><input type='text' class='player' id='" + index + "_21_21' value='' readonly></td>";
    html_R11 += "<div id='match_" + index + "_21'></div></td>";
    html_R11 += "<td class='column_11'  id='" + index + "_22' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_22_22' value='' readonly></td>";
    html_R11 += "<td class='column_12' >&nbsp;</td>";
    html_R11 += "<td class='test_column'>R11</td>";
    return html_R11;
  }

  function R12(index, tourn_size) 
  {
    html_R12 = "";
    if(tourn_size == 128)
    {
      html_R12 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R12 += "<div id='match_" + index + "_1'></div></td>";
      html_R12 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R12 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R12 += "<div id='match_" + index + "_3'></div></td>";
      html_R12 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R12 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R12 += "<div id='match_" + index + "_5'></div></td>";
      html_R12 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R12 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R12 += "<div id='match_" + index + "_7'></div></td>";
      html_R12 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    else if(tourn_size == 96)
    {
      html_R12 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R12 += "<div id='match_" + index + "_5'></div></td>";
      html_R12 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R12 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R12 += "<div id='match_" + index + "_7'></div></td>";
      html_R12 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R12 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R12 += "<div id='match_" + index + "_9'></div></td>";
    html_R12 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R12 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R12 += "<div id='match_" + index + "_11'></div></td>";
    html_R12 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R12 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R12 += "<div id='match_" + index + "_13'></div></td>";
    html_R12 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R12 += "<td class='column_8'  nowrap align='center'><input type='text' class='player' id='" + index + "_15_15' value='' readonly></td>";
    html_R12 += "<div id='match_" + index + "_15'></div></td>";
    html_R12 += "<td class='column_8'  id='" + index + "_16' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_16_16' value='' readonly></td>";
    html_R12 += "<td class='column_9' >&nbsp;</td>";
    html_R12 += "<td class='column_9' >&nbsp;</td>";
    html_R12 += "<td class='column_10' >&nbsp;</td>";
    html_R12 += "<td class='column_10' >&nbsp;</td>";
    html_R12 += "<td class='column_11' >&nbsp;</td>";
    html_R12 += "<td class='column_11' >&nbsp;</td>";
    html_R12 += "<td class='column_12' >&nbsp;</td>";
    html_R12 += "<td class='test_column'>R12</td>";
    return html_R12;
  }

  function R13(index, tourn_size) 
  {
    html_R13 = "";
    if(tourn_size == 128)
    {
      html_R13 += "<td class='column_1' >&nbsp;</td>";
      html_R13 += "<td class='column_1' >&nbsp;</td>";
      html_R13 += "<td class='column_2' >&nbsp;</td>";
      html_R13 += "<td class='column_2' >&nbsp;</td>";
      html_R13 += "<td class='column_3' >&nbsp;</td>";
      html_R13 += "<td class='column_3' >&nbsp;</td>";
      html_R13 += "<td class='column_4' >&nbsp;</td>";
      html_R13 += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_R13 += "<td class='column_3' >&nbsp;</td>";
      html_R13 += "<td class='column_3' >&nbsp;</td>";
      html_R13 += "<td class='column_4' >&nbsp;</td>";
      html_R13 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R13 += "<td class='column_5' >&nbsp;</td>";
    html_R13 += "<td class='column_5' >&nbsp;</td>";
    html_R13 += "<td class='column_6' >&nbsp;</td>";
    html_R13 += "<td class='column_6' >&nbsp;</td>";
    html_R13 += "<td class='column_7' >&nbsp;</td>";
    html_R13 += "<td class='column_7' >&nbsp;</td>";
    html_R13 += "<td class='column_8' >&nbsp;</td>";
    html_R13 += "<td class='column_8' >&nbsp;</td>";
    html_R13 += "<td class='column_9'  align='center'>";
    html_R13 += DateSelect(play_date, ("D_" + index + '_9'));
    html_R13 += "&nbsp;";
    html_R13 += "<input type='text' class='timepicker' id='T_" + index + "_9' data-row='" + index + "' data-col='9' value='' readonly size='6' /></td>";
    html_R13 += "<td class='column_9' >&nbsp;</td>";
    html_R13 += "<td class='column_10' >&nbsp;</td>";
    html_R13 += "<td class='column_10' >&nbsp;</td>";
    html_R13 += "<td class='column_11' >&nbsp;</td>";
    html_R13 += "<td class='column_11' >&nbsp;</td>";
    html_R13 += "<td class='column_12' >&nbsp;</td>";
    html_R13 += "<td class='test_column'>R13</td>";
    return html_R13;
  }

  function R14(index, tourn_size) 
  {
    html_R14 = "";
    if(tourn_size == 128)
    {
      html_R14 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R14 += "<div id='match_" + index + "_1'></div></td>";
      html_R14 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R14 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R14 += "<div id='match_" + index + "_3'></div></td>";
      html_R14 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R14 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R14 += "<div id='match_" + index + "_5'></div></td>";
      html_R14 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R14 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R14 += "<div id='match_" + index + "_7'></div></td>";
      html_R14 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    else if(tourn_size == 96)
    {
      html_R14 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R14 += "<div id='match_" + index + "_5'></div></td>";
      html_R14 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R14 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R14 += "<div id='match_" + index + "_7'></div></td>";
      html_R14 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R14 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R14 += "<div id='match_" + index + "_9'></div></td>";
    html_R14 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R14 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R14 += "<div id='match_" + index + "_11'></div></td>";
    html_R14 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R14 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R14 += "<div id='match_" + index + "_13'></div></td>";
    html_R14 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R14 += "<td class='column_8'  nowrap align='center'><input type='text' class='player' id='" + index + "_15_15' value='' readonly></td>";
    html_R14 += "<div id='match_" + index + "_15'></div></td>";
    html_R14 += "<td class='column_8'  id='" + index + "_16' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_16_16' value='' readonly></td>";
    html_R14 += "<td class='column_9'  align='center'>";
    html_R14 += DateSelect(play_date, ("D_" + index + '_9'));
    html_R14 += "&nbsp;";
    html_R14 += "<input type='text' class='timepicker' id='T_" + index + "_9' data-row='" + index + "' data-col='9' value='' readonly size='6' /></td>";
    html_R14 += "<td class='column_9' >&nbsp;</td>";
    html_R14 += "<td class='column_10' >&nbsp;</td>";
    html_R14 += "<td class='column_10' >&nbsp;</td>";
    html_R14 += "<td class='column_11' >&nbsp;</td>";
    html_R14 += "<td class='column_11' >&nbsp;</td>";
    html_R14 += "<td class='column_12' >&nbsp;</td>";
    html_R14 += "<td class='test_column'>R14</td>";
    return html_R14;
  }

  function R15(index, tourn_size) 
  {
    html_R15 = "";
    if(tourn_size == 128)
    {
      html_R15 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R15 += "<div id='match_" + index + "_1'></div></td>";
      html_R15 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R15 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R15 += "<div id='match_" + index + "_3'></div></td>";
      html_R15 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R15 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R15 += "<div id='match_" + index + "_5'></div></td>";
      html_R15 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R15 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R15 += "<div id='match_" + index + "_7'></div></td>";
      html_R15 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    else if(tourn_size == 96)
    {
      html_R15 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R15 += "<div id='match_" + index + "_5'></div></td>";
      html_R15 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R15 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R15 += "<div id='match_" + index + "_7'></div></td>";
      html_R15 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R15 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R15 += "<div id='match_" + index + "_9'></div></td>";
    html_R15 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R15 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R15 += "<div id='match_" + index + "_11'></div></td>";
    html_R15 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R15 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R15 += "<div id='match_" + index + "_13'></div></td>";
    html_R15 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R15 += "<td class='column_8'  nowrap align='center'><input type='text' class='player' id='" + index + "_15_15' value='' readonly></td>";
    html_R15 += "<div id='match_" + index + "_15'></div></td>";
    html_R15 += "<td class='column_8'  id='" + index + "_16' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_16_16' value='' readonly></td>";
    html_R15 += "<td class='column_9' >&nbsp;</td>";
    html_R15 += "<td class='column_9' >&nbsp;</td>";
    html_R15 += "<td class='column_10' >&nbsp;</td>";
    html_R15 += "<td class='column_10' >&nbsp;</td>";
    html_R15 += "<td class='column_11' >&nbsp;</td>";
    html_R15 += "<td class='column_11' >&nbsp;</td>";
    html_R15 += "<td class='column_12' >&nbsp;</td>";
    html_R15 += "<td class='test_column'>R15</td>";
    return html_R15;
  }

  function R16(index, tourn_size) 
  {
    html_R16 = "";
    if(tourn_size == 128)
    {
      html_R16 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R16 += "<div id='match_" + index + "_1'></div></td>";
      html_R16 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R16 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R16 += "<div id='match_" + index + "_3'></div></td>";
      html_R16 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R16 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R16 += "<div id='match_" + index + "_5'></div></td>";
      html_R16 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R16 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R16 += "<div id='match_" + index + "_7'></div></td>";
      html_R16 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    else if(tourn_size == 96)
    {
      html_R16 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R16 += "<div id='match_" + index + "_5'></div></td>";
      html_R16 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R16 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R16 += "<div id='match_" + index + "_7'></div></td>";
      html_R16 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R16 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R16 += "<div id='match_" + index + "_9'></div></td>";
    html_R16 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R16 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R16 += "<div id='match_" + index + "_11'></div></td>";
    html_R16 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R16 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R16 += "<div id='match_" + index + "_13'></div></td>";
    html_R16 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R16 += "<td class='column_8' >&nbsp;</td>";
    html_R16 += "<td class='column_8' >&nbsp;</td>";
    html_R16 += "<td class='column_9' >&nbsp;</td>";
    html_R16 += "<td class='column_9' >&nbsp;</td>";
    html_R16 += "<td class='column_10'  align='center'>";
    html_R16 += DateSelect(play_date, ("D_" + index + '_10'));
    html_R16 += "&nbsp;";
    html_R16 += "<input type='text' class='timepicker' id='T_" + index + "_10' data-row='" + index + "' data-col='10' value='' readonly size='6' /></td>";
    html_R16 += "<td class='column_10' >&nbsp;</td>";
    html_R16 += "<td class='column_11' >&nbsp;</td>";
    html_R16 += "<td class='column_11' >&nbsp;</td>";
    html_R16 += "<td class='column_12' >&nbsp;</td>";
    html_R16 += "<td class='test_column'>R16</td>";
    return html_R16;
  }

  function R17(index, tourn_size) 
  {
    html_R17 = "";
    if(tourn_size == 128)
    {
      html_R17 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R17 += "<div id='match_" + index + "_1'></div></td>";
      html_R17 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R17 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R17 += "<div id='match_" + index + "_3'></div></td>";
      html_R17 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R17 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R17 += "<div id='match_" + index + "_5'></div></td>";
      html_R17 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R17 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R17 += "<div id='match_" + index + "_7'></div></td>";
      html_R17 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    else if(tourn_size == 96)
    {
      html_R17 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R17 += "<div id='match_" + index + "_5'></div></td>";
      html_R17 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R17 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R17 += "<div id='match_" + index + "_7'></div></td>";
      html_R17 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R17 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R17 += "<div id='match_" + index + "_9'></div></td>";
    html_R17 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R17 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R17 += "<div id='match_" + index + "_11'></div></td>";
    html_R17 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R17 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R17 += "<div id='match_" + index + "_13'></div></td>";
    html_R17 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R17 += "<td class='column_8' >&nbsp;</td>";
    html_R17 += "<td class='column_8' >&nbsp;</td>";
    html_R17 += "<td class='column_9' >&nbsp;</td>";
    html_R17 += "<td class='column_9' >&nbsp;</td>";
    html_R17 += "<td class='column_10' >&nbsp;</td>";
    html_R17 += "<td class='column_10' >&nbsp;</td>";
    html_R17 += "<td class='column_11' >&nbsp;</td>";
    html_R17 += "<td class='column_11' >&nbsp;</td>";
    html_R17 += "<td class='column_12' >&nbsp;</td>";
    html_R17 += "<td class='test_column'>R17</td>";
    return html_R17;
  }

  function R18(index, tourn_size) 
  {
    html_R18 = "";
    if(tourn_size == 128)
    {
      html_R18 += "<td class='column_1' >&nbsp;</td>";
      html_R18 += "<td class='column_1' >&nbsp;</td>";
      html_R18 += "<td class='column_2' >&nbsp;</td>";
      html_R18 += "<td class='column_2' >&nbsp;</td>";
      html_R18 += "<td class='column_3' >&nbsp;</td>";
      html_R18 += "<td class='column_3' >&nbsp;</td>";
      html_R18 += "<td class='column_4' >&nbsp;</td>";
      html_R18 += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_R18 += "<td class='column_3' >&nbsp;</td>";
      html_R18 += "<td class='column_3' >&nbsp;</td>";
      html_R18 += "<td class='column_4' >&nbsp;</td>";
      html_R18 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R18 += "<td class='column_5' >&nbsp;</td>";
    html_R18 += "<td class='column_5' >&nbsp;</td>";
    html_R18 += "<td class='column_6' >&nbsp;</td>";
    html_R18 += "<td class='column_6' >&nbsp;</td>";
    html_R18 += "<td class='column_7' >&nbsp;</td>";
    html_R18 += "<td class='column_7' >&nbsp;</td>";
    html_R18 += "<td class='column_8' >&nbsp;</td>";
    html_R18 += "<td class='column_8' >&nbsp;</td>";
    html_R18 += "<td class='column_9' >&nbsp;</td>";
    html_R18 += "<td class='column_9' >&nbsp;</td>";
    html_R18 += "<td class='column_10' >&nbsp;</td>";
    html_R18 += "<td class='column_10' >&nbsp;</td>";
    html_R18 += "<td class='column_11'  align='center'>";
    html_R18 += DateSelect(play_date, ("D_" + index + '_11'));
    html_R18 += "&nbsp;";
    html_R18 += "<input type='text' class='timepicker' id='T_" + index + "_11' data-row='" + index + "' data-col='11' value='' readonly size='6' /></td>";
    html_R18 += "<td class='column_11' >&nbsp;</td>";
    html_R18 += "<td class='column_12' >&nbsp;</td>";
    html_R18 += "<td class='test_column'>R18</td>";
    return html_R18;
  }

  function Finals(index, tourn_size) 
  {
    html_Finals = "";
    if(tourn_size == 128)
    {
      html_Finals += "<td class='column_1' >&nbsp;</td>";
      html_Finals += "<td class='column_1' >&nbsp;</td>";
      html_Finals += "<td class='column_2' >&nbsp;</td>";
      html_Finals += "<td class='column_2' >&nbsp;</td>";
      html_Finals += "<td class='column_3' >&nbsp;</td>";
      html_Finals += "<td class='column_3' >&nbsp;</td>";
      html_Finals += "<td class='column_4' >&nbsp;</td>";
      html_Finals += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_Finals += "<td class='column_3' >&nbsp;</td>";
      html_Finals += "<td class='column_3' >&nbsp;</td>";
      html_Finals += "<td class='column_4' >&nbsp;</td>";
      html_Finals += "<td class='column_4' >&nbsp;</td>";
    }
    html_Finals += "<td class='column_5' >&nbsp;</td>";
    html_Finals += "<td class='column_5' >&nbsp;</td>";
    html_Finals += "<td class='column_6' >&nbsp;</td>";
    html_Finals += "<td class='column_6' >&nbsp;</td>";
    html_Finals += "<td class='column_7' >&nbsp;</td>";
    html_Finals += "<td class='column_7' >&nbsp;</td>";
    html_Finals += "<td class='column_8' >&nbsp;</td>";
    html_Finals += "<td class='column_8' >&nbsp;</td>";
    html_Finals += "<td class='column_9' >&nbsp;</td>";
    html_Finals += "<td class='column_9' >&nbsp;</td>";
    html_Finals += "<td class='column_10' >&nbsp;</td>";
    html_Finals += "<td class='column_10' >&nbsp;</td>";
    html_Finals += "<td class='column_11' >&nbsp;</td>";
    html_Finals += "<td class='column_11' >&nbsp;</td>";
    html_Finals += "<td class='column_12'  nowrap align='center'><b><input type='text' class='player' id='" + index + "_23_23' value=''  readonly style='width:164px; height:32x'></b></td>";
    html_Finals += "<div id='match_" + index + "_23'></div></td>";
    html_Finals += "<td class='test_column'>R_Finals;</td>";
    return html_Finals;
  }

  function Blank(index, tourn_size) 
  {
    html_Blank = "";
    if(tourn_size == 128)
    {
      html_Blank += "<td class='column_1' >&nbsp;</td>";
      html_Blank += "<td class='column_1' >&nbsp;</td>";
      html_Blank += "<td class='column_2' >&nbsp;</td>";
      html_Blank += "<td class='column_2' >&nbsp;</td>";
      html_Blank += "<td class='column_3' >&nbsp;</td>";
      html_Blank += "<td class='column_3' >&nbsp;</td>";
      html_Blank += "<td class='column_4' >&nbsp;</td>";
      html_Blank += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_Blank += "<td class='column_3' >&nbsp;</td>";
      html_Blank += "<td class='column_3' >&nbsp;</td>";
      html_Blank += "<td class='column_4' >&nbsp;</td>";
      html_Blank += "<td class='column_4' >&nbsp;</td>";
    }
    html_Blank += "<td class='column_5' >&nbsp;</td>";
    html_Blank += "<td class='column_5' >&nbsp;</td>";
    html_Blank += "<td class='column_6' >&nbsp;</td>";
    html_Blank += "<td class='column_6' >&nbsp;</td>";
    html_Blank += "<td class='column_7' >&nbsp;</td>";
    html_Blank += "<td class='column_7' >&nbsp;</td>";
    html_Blank += "<td class='column_8' >&nbsp;</td>";
    html_Blank += "<td class='column_8' >&nbsp;</td>";
    html_Blank += "<td class='column_9' >&nbsp;</td>";
    html_Blank += "<td class='column_9' >&nbsp;</td>";
    html_Blank += "<td class='column_10' >&nbsp;</td>";
    html_Blank += "<td class='column_10' >&nbsp;</td>";
    html_Blank += "<td class='column_11' >&nbsp;</td>";
    html_Blank += "<td class='column_11' >&nbsp;</td>";
    html_Blank += "<td class='column_12' >&nbsp;</td>";
    html_Blank += "<td class='test_column'>R_Blank</td>";
    return html_Blank;
  }

  function TimeDay1(index, tourn_size) 
  {
    html_TimeDay = "";
    if(tourn_size == 128)
    {
      html_TimeDay += "<td class='column_1'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_1'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_1' data-row='" + index + "' data-col='1' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_2'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_2' data-row='" + index + "' data-col='2' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_3'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_3' data-row='" + index + "' data-col='3' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_4'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_4' data-row='" + index + "' data-col='4' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_TimeDay += "<td class='column_3'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_3'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_3' data-row='" + index + "' data-col='3' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_4'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_4' data-row='" + index + "' data-col='4' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
    }
    html_TimeDay += "<td class='column_5'  align='center' class='column_5'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_5'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_5' data-row='" + index + "' data-col='5' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6'  align='center'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_6'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_6' data-row='" + index + "' data-col='6' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7'  align='center'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_7'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_7' data-row='" + index + "' data-col='7' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_12' >&nbsp;</td>";
    html_TimeDay += "<td class='test_column'>TimeDay1</td>";
    return html_TimeDay;
  }

  function TimeDay2(index, tourn_size) 
  {
    html_TimeDay = "";
    if(tourn_size == 128)
    {
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
    }
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10'  align='center'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_10'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_10' data-row='" + index + "' data-col='10' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_12' >&nbsp;</td>";
    html_TimeDay += "<td class='test_column'>TimeDay2</td>";
    return html_TimeDay;
  }

  function TimeDay3(index, tourn_size) 
  {
    html_TimeDay = "";
    if(tourn_size == 128)
    {
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
    }
    else if(tourn_size == 96)
    {
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
    }
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11'  align='center'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_11'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' class='timepicker' id='T_" + index + "_11' data-row='" + index + "' data-col='11' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_12' >&nbsp;</td>";
    html_TimeDay += "<td class='test_column'>TimeDay3</td>";
    return html_TimeDay;
  }

  function Header(index, tourn_size) 
  {
    html_Header = "";
    if(tourn_size == 128)
    {
      html_Header += "<td class='DT_column_1'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_1'  align='center'><b>Score</b></td>";
      html_Header += "<td class='DT_column_2'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_2'  align='center'><b>Score</b></td>";
      html_Header += "<td class='DT_column_3'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_3'  align='center'><b>Score</b></td>";
      html_Header += "<td class='DT_column_4'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_4'  align='center'><b>Score</b></td>";
    }
    else if(tourn_size == 96)
    {
      html_Header += "<td class='DT_column_3'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_3'  align='center'><b>Score</b></td>";
      html_Header += "<td class='DT_column_4'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_4'  align='center'><b>Score</b></td>";
    }
    html_Header += "<td class='DT_column_5' align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_5' align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_6' align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_6' align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_7' align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_7' align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_8' align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_8' align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_9' align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_9' align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_10' align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_10' align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_11' align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_11' align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_12' align='center'>&nbsp;</td>";
    html_Header += "<td class='test_column'>Header</td>";
    return html_Header;
  }

const rowPatterns = [
// header id first three rows
  "Header",
  "Blank",
  "TimeDay1",
  "R7",
  "R8",
  "R13",
  "R9",
  "TimeDay1",
  "R8",
  "R16",
  "R10",
  "Blank",
  "TimeDay1",
  "R7",
  "R12",
  "Blank",
  "R9",
  "TimeDay1",
  "R8",
  "R17",
  "Blank",
  "R18",
  "R11",
  "TimeDay1",
  "R7",
  "R12",
  "R13",
  "R9",
  "TimeDay1",
  "R15",
  "R17",
  "R10",
  "Blank",
  "TimeDay1",
  "R7",
  "R12",
  "Blank",
  "R9",
  "TimeDay1",
  "R8",
  "R17",
  "Blank",
  "Finals",
  "Blank",
];

let rounds = 0;
if(tourn_size === 128)
{
    rounds = 12; // number of rounds (inc finals)
}
else if(tourn_size === 96)
{
    rounds = 10; // number of rounds (inc finals)
}
else
{
    rounds = 8; // number of rounds (inc finals)
}
let html = "<table align='center' border='0' cellpadding='0' cellspacing='0' width='50%'>";

// ---- Top Header ----
index = 1;
rnd = 3;// for 128 player tournament
html += "<tr>";

for (let r = 1; r <= rounds; r++) 
{
  if((tourn_size === 128) && (r <= 4))
  {
      html += "<th colspan='2'  style='text-align: center' class='column_" + (r+1) + "'><b>Round 1 (Best of " + <?= $build_best_of['best_of_1'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r > 4) && (r <= 6))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_" + (r+1) + "'><b>Round 2 (Best of " + <?= $build_best_of['best_of_2'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 7))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_6'><b>Round " + (r-4) + " (Best of " + <?= $build_best_of['best_of_3'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 8))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_7'><b>Round " + (r-4) + " (Best of " + <?= $build_best_of['best_of_4'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 9))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_9'><b>Quarter Finals <b>(Best of " + <?= $build_best_of['best_of_6'] ?> + ")</b></th>";
  }
  else if(((tourn_size === 128) && r === 10))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_10'><b>Semi Finals (Best of " + <?= $build_best_of['best_of_7'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 11))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_11'><b>Finals (Best of " + <?= $build_best_of['best_of_8'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 12))
  {
    html += "<th  style='text-align: center' class='column_12'><h5><b>Winner</b></h5></th>";
  }

  if((tourn_size === 96) && (r <= 2))
  {
      html += "<th colspan='2'  style='text-align: center' class='column_" + (r+1) + "'><b>Round 1 (Best of " + <?= $build_best_of['best_of_1'] ?> + ")</b></th>";
  }
  else if((tourn_size === 96) && (r > 2) && (r <= 4))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_" + (r+1) + "'><b>Round 2 (Best of " + <?= $build_best_of['best_of_2'] ?> + ")</b></th>";
  }
  else if((tourn_size === 96) && (r === 5))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_6'><b>Round " + (r-2) + " (Best of " + <?= $build_best_of['best_of_3'] ?> + ")</b></th>";
  }
  else if((tourn_size === 96) && (r === 6))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_7'><b>Round " + (r-2) + " (Best of " + <?= $build_best_of['best_of_4'] ?> + ")</b></th>";
  }
  else if((tourn_size === 96) && (r === 7))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_9'><b>Quarter Finals <b>(Best of " + <?= $build_best_of['best_of_6'] ?> + ")</b></th>";
  }
  else if(((tourn_size === 96) && r === 8))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_10'><b>Semi Finals (Best of " + <?= $build_best_of['best_of_7'] ?> + ")</b></th>";
  }
  else if((tourn_size === 96) && (r === 9))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_11'><b>Finals (Best of " + <?= $build_best_of['best_of_8'] ?> + ")</b></th>";
  }
  else if((tourn_size === 96) && (r === 10))
  {
    html += "<th  style='text-align: center' class='column_12'><h5><b>Winner</b></h5></th>";
  }

  if(tourn_size === 64)
  {
    if(r === 1)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_5' value=" + r + "><b>Round " + r + " (Best of " + <?= $build_best_of['best_of_1'] ?> + ")</b></th>";
    }
    else if(r === 2)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_6' value=" + r + "><b>Round " + r + " (Best of " + <?= $build_best_of['best_of_2'] ?> + ")</b></th>";
    }
    else if(r === 3)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_7' value=" + r + "><b>Round " + r + " (Best of " + <?= $build_best_of['best_of_3'] ?> + ")</b></th>";
    }
    else if(r === 4)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_8' value=" + r + "><b>Round " + r + " (Best of " + <?= $build_best_of['best_of_4'] ?> + ")</b></th>";
    }
    else if(r === 5)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_9' value=" + r + "><b>Quarter Finals (Best of " + <?= $build_best_of['best_of_5'] ?> + ")</b></th>";
    }
    else if(r === 6)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_10' value=" + r + "><b>Semi Finals (Best of " + <?= $build_best_of['best_of_6'] ?> + ")</b></th>";
    }
    else if(r === 7)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_11' value=" + r + "><b>Finals (Best of " + <?= $build_best_of['best_of_7'] ?> + ")</b></th>";
    }
    else if(r === 8)
    {
      html += "<th style='text-align: center;' class='column_12'><b>Winner</b></th>";
    }
  }
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
    else if (pattern === "R11") 
    {
      html += R11(index, tourn_size);
    }
    else if (pattern === "R12") 
    {
      html += R12(index, tourn_size);
    }
    else if (pattern === "R13") 
    {
      html += R13(index, tourn_size);
    }
    else if (pattern === "R14") 
    {
      html += R14(index, tourn_size);
    }
    else if (pattern === "R15") 
    {
      html += R15(index, tourn_size);
    }
    else if (pattern === "R16") 
    {
      html += R16(index, tourn_size);
    }
    else if (pattern === "R17") 
    {
      html += R17(index, tourn_size);
    }
    else if (pattern === "R18") 
    {
      html += R18(index, tourn_size);
    }
    else if (pattern === "Blank") 
    {
      html += Blank(index, tourn_size);
    }
    else if (pattern === "TimeDay1") 
    {
      html += TimeDay1(index, tourn_size);
    }
    else if (pattern === "TimeDay2") 
    {
      html += TimeDay2(index, tourn_size);
    }
    else if (pattern === "TimeDay3") 
    {
      html += TimeDay3(index, tourn_size);
    }
    else if (pattern === "Finals") 
    {
      html += Finals(index, tourn_size);
    }
    else if (pattern === "Header") 
    {
      html += Header(index, tourn_size);
    }
  html += "</tr>";
  index++;
});

// ---- bottom of table ----
html += "<tr>";

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
    else if (pattern === "R11") 
    {
      html += R11(index, tourn_size);
    }
    else if (pattern === "R12") 
    {
      html += R12(index, tourn_size);
    }
    else if (pattern === "R13") 
    {
      html += R13(index, tourn_size);
    }
    else if (pattern === "R14") 
    {
      html += R14(index, tourn_size);
    }
    else if (pattern === "R15") 
    {
      html += R15(index, tourn_size);
    }
    else if (pattern === "R16") 
    {
      html += R16(index, tourn_size);
    }
    else if (pattern === "R17") 
    {
      html += R17(index, tourn_size);
    }
    else if (pattern === "R18") 
    {
      html += R18(index, tourn_size);
    }
    else if (pattern === "Blank") 
    {
      html += Blank(index, tourn_size);
    }
    else if (pattern === "TimeDay1") 
    {
      html += TimeDay1(index, tourn_size);
    }
    else if (pattern === "TimeDay2") 
    {
      html += TimeDay2(index, tourn_size);
    }
    else if (pattern === "TimeDay3") 
    {
      html += TimeDay3(index, tourn_size);
    }
    else if (pattern === "Header") 
    {
      html += Header(index, tourn_size);
    }
  html += "</tr>";
  index++;
});
html += "</table>";
html += "<br><br>";

document.getElementById("fixtureTable").innerHTML = html;
</script>
<?php
// map seeds/ranks to table position
if($tourn_size === 64)
{
  $idMap = [
    // rows 1 to 4 are headers
    1 => "5_13_13",
    2 => "85_13_13",
    3 => "49_13_13",
    4 => "41_13_13",
    5 => "26_13_13",
    6 => "64_13_13",
    7 => "70_13_13",
    8 => "20_13_13",
    9 => "15_13_13",
    10 => "75_13_13",
    11 => "59_13_13",
    12 => "31_13_13",
    13 => "36_13_13",
    14 => "54_13_13",
    15 => "80_13_13",
    16 => "10_13_13",

    17 => "10_11_11",
    18 => "80_11_11",
    19 => "54_11_11",
    20 => "36_11_11",
    21 => "31_11_11",
    22 => "59_11_11",
    23 => "75_11_11",
    24 => "15_11_11",
    25 => "20_11_11",
    26 => "70_11_11",
    27 => "64_11_11",
    28 => "26_11_11",
    29 => "41_11_11",
    30 => "49_11_11",
    31 => "85_11_11",
    32 => "5_11_11",

    33 => "5_9_9",
    34 => "86_9_9",
    35 => "49_9_9",
    36 => "42_9_9",
    37 => "27_9_9",
    38 => "64_9_9",
    39 => "71_9_9",
    40 => "20_9_9",
    41 => "15_9_9",
    42 => "76_9_9",
    43 => "59_9_9",
    44 => "32_9_9",
    45 => "37_9_9",
    46 => "54_9_9",
    47 => "81_9_9",
    48 => "10_9_9",

    49 => "11_9_9",
    50 => "80_9_9",
    51 => "55_9_9",
    52 => "36_9_9",
    53 => "31_9_9",
    54 => "60_9_9",
    55 => "75_9_9",
    56 => "16_9_9",
    57 => "21_9_9",
    58 => "70_9_9",
    59 => "65_9_9",
    60 => "26_9_9",
    61 => "41_9_9",
    62 => "50_9_9",
    63 => "85_9_9",
    64 => "6_9_9"
  ];
}
else if($tourn_size === 96)
{
  $idMap = [
    // rows 1 to 4 are headers
    1 => "5_13_13",
    2 => "85_13_13",
    3 => "49_13_13",
    4 => "41_13_13",
    5 => "26_13_13",
    6 => "64_13_13",
    7 => "70_13_13",
    8 => "20_13_13",
    9 => "15_13_13",
    10 => "75_13_13",
    11 => "59_13_13",
    12 => "31_13_13",
    13 => "36_13_13",
    14 => "54_13_13",
    15 => "80_13_13",
    16 => "10_13_13",

    17 => "10_9_9",
    18 => "80_9_9",
    19 => "54_9_9",
    20 => "36_9_9",
    21 => "31_9_9",
    22 => "59_9_9",
    23 => "75_9_9",
    24 => "15_9_9",
    25 => "20_9_9",
    26 => "70_9_9",
    27 => "64_9_9",
    28 => "26_9_9",
    29 => "41_9_9",
    30 => "49_9_9",
    31 => "85_9_9",
    32 => "5_9_9",

    33 => "5_11_11",
    34 => "85_11_11",
    35 => "49_11_11",
    36 => "41_11_11",
    37 => "26_11_11",
    38 => "64_11_11",
    39 => "70_11_11",
    40 => "20_11_11",
    41 => "15_11_11",
    42 => "75_11_11",
    43 => "59_11_11",
    44 => "31_11_11",
    45 => "36_11_11",
    46 => "54_11_11",
    47 => "80_11_11",
    48 => "10_11_11",

    49 => "10_7_7",
    50 => "80_7_7",
    51 => "54_7_7",
    52 => "36_7_7",
    53 => "31_7_7",
    54 => "59_7_7",
    55 => "75_7_7",
    56 => "15_7_7",
    57 => "20_7_7",
    58 => "70_7_7",
    59 => "64_7_7",
    60 => "26_7_7",
    61 => "41_7_7",
    62 => "49_7_7",
    63 => "85_7_7",
    64 => "5_7_7",

    65 => "5_5_5",
    66 => "85_5_5",
    67 => "49_5_5",
    68 => "41_5_5",
    69 => "26_5_5",
    70 => "64_5_5",
    71 => "70_5_5",
    72 => "20_5_5",
    73 => "15_5_5",
    74 => "75_5_5",
    75 => "59_5_5",
    76 => "31_5_5",
    77 => "36_5_5",
    78 => "54_5_5",
    79 => "80_5_5",
    80 => "10_5_5",

    81 => "11_5_5",
    82 => "81_5_5",
    83 => "55_5_5",
    84 => "37_5_5",
    85 => "32_5_5",
    86 => "60_5_5",
    87 => "76_5_5",
    88 => "16_5_5",
    89 => "21_5_5",
    90 => "71_5_5",
    91 => "65_5_5",
    92 => "27_5_5",
    93 => "42_5_5",
    94 => "50_5_5",
    95 => "86_5_5",
    96 => "6_5_5"
  ];
}
else if($tourn_size == 128)
{
  $idMap = [
    // rows 1 to 4 are headers
    1 => "5_13_13",
    2 => "85_13_13",
    3 => "49_13_13",
    4 => "41_13_13",
    5 => "26_13_13",
    6 => "64_13_13",
    7 => "70_13_13",
    8 => "20_13_13",
    9 => "15_13_13",
    10 => "75_13_13",
    11 => "59_13_13",
    12 => "31_13_13",
    13 => "36_13_13",
    14 => "54_13_13",
    15 => "80_13_13",
    16 => "10_13_13",

    17 => "10_9_9",
    18 => "80_9_9",
    19 => "54_9_9",
    20 => "36_9_9",
    21 => "31_9_9",
    22 => "59_9_9",
    23 => "75_9_9",
    24 => "15_9_9",
    25 => "20_9_9",
    26 => "70_9_9",
    27 => "64_9_9",
    28 => "26_9_9",
    29 => "41_9_9",
    30 => "49_9_9",
    31 => "85_9_9",
    32 => "5_9_9",

    33 => "5_11_11",
    34 => "85_11_11",
    35 => "49_11_11",
    36 => "41_11_11",
    37 => "26_11_11",
    38 => "64_11_11",
    39 => "70_11_11",
    40 => "20_11_11",
    41 => "15_11_11",
    42 => "75_11_11",
    43 => "59_11_11",
    44 => "31_11_11",
    45 => "36_11_11",
    46 => "54_11_11",
    47 => "80_11_11",
    48 => "10_11_11",

    49 => "10_5_5",
    50 => "80_5_5",
    51 => "54_5_5",
    52 => "36_5_5",
    53 => "31_5_5",
    54 => "59_5_5",
    55 => "75_5_5",
    56 => "15_5_5",
    57 => "20_5_5",
    58 => "70_5_5",
    59 => "64_5_5",
    60 => "26_5_5",
    61 => "41_5_5",
    62 => "49_5_5",
    63 => "85_5_5",
    64 => "5_5_5",

    65 => "5_7_7",
    66 => "85_7_7",
    67 => "49_7_7",
    68 => "41_7_7",
    69 => "26_7_7",
    70 => "64_7_7",
    71 => "70_7_7",
    72 => "20_7_7",
    73 => "15_7_7",
    74 => "75_7_7",
    75 => "59_7_7",
    76 => "31_7_7",
    77 => "36_7_7",
    78 => "54_7_7",
    79 => "80_7_7",
    80 => "10_7_7",

    81 => "10_1_1",
    82 => "81_1_1",
    83 => "54_1_1",
    84 => "37_1_1",
    85 => "32_1_1",
    86 => "59_1_1",
    87 => "76_1_1",
    88 => "15_1_1",
    89 => "20_1_1",
    90 => "71_1_1",
    91 => "64_1_1",
    92 => "27_1_1",
    93 => "42_1_1",
    94 => "49_1_1",
    95 => "86_1_1",
    96 => "5_1_1",

    97 => "5_3_3",
    98 => "85_3_3",
    99 => "49_3_3",
    100 => "41_3_3",
    101 => "26_3_3",
    102 => "64_3_3",
    103 => "70_3_3",
    104 => "20_3_3",
    105 => "15_3_3",
    106 => "75_3_3",
    107 => "59_3_3",
    108 => "31_3_3",
    109 => "36_3_3",
    110 => "54_3_3",
    111 => "80_3_3",
    112 => "10_3_3",

    113 => "11_1_1",
    114 => "80_1_1",
    115 => "55_1_1",
    116 => "36_1_1",
    117 => "31_1_1",
    118 => "60_1_1",
    119 => "75_1_1",
    120 => "16_1_1",
    121 => "21_1_1",
    122 => "70_1_1",
    123 => "65_1_1",
    124 => "26_1_1",
    125 => "41_1_1",
    126 => "50_1_1",
    127 => "85_1_1",
    128 => "6_1_1"
  ];
}
echo("<script>");
$player_index = 0;
mysqli_data_seek($result_players, 0);
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
    //echo("console.log('Player Name " . $name . "');");
}
//echo("console.log('Player Name " . $name . "');");
$byeArr = [];
for($i = ($player_index); $i < $tourn_size; $i++)
{
  $name = 'Bye';
  $elementId = $idMap[$i+1];

  $byeArr[] = [
    'Element' => $elementId
  ];

  //$byeArr[$i] = $elementId;
  echo "document.getElementById('$elementId').value = '$name';";
  //echo("console.log('Player Name " . $name . ", Element ID " . $elementId . "');");
  //echo("console.log('Player Index " . $player_index . "');");
  //echo("console.log('Tourn Size " . $tourn_size . "');");
  //echo("console.log('Array " . $byeArr . "');");
  //echo("$.fn.move_bye('" . $elementId . "');");
}
echo "document.getElementById('byeArray').value = '" . json_encode($byeArr) . "';";

// add player as rounds progress
$sql_added_player = 'Select * from tournament_players where tourn_id = ' . $tourn_id . ' and added_player = 1';
$result_added_player = mysql_query($sql_added_player, $connvbsa) or die(mysql_error());
while($build_added = $result_added_player->fetch_assoc())
{
  echo("document.getElementById('" . $build_added['row_no'] . "_" . $build_added['col_no'] . "_" . $build_added['col_no'] . "').value = '" . $build_added['fullname'] . "';");
}
echo("</script>");

echo("<pre>");
echo(var_dump($byeArr));
echo("</pre>");


?>
</center>

<script>
$(document).ready(function() 
{
  $('#scores_modal').off('focusout', '.score_input_1').on('focusout', '.score_input_1', function(e) 
  {
    e.stopImmediatePropagation();
    $.fn.check_int("SS", this.id);
  });
  $('#scores_modal').off('focusout', '.score_input_2').on('focusout', '.score_input_2', function(e) 
  {
    e.stopImmediatePropagation();
    $.fn.check_int("SS", this.id);
  });

/*
  let dayTimeData = [];
  $('input[id^="T_"]').each(function () {
    let id = $(this).attr('id'); // e.g. D_5_6
    let parts = id.split("_");
    let type = parts[0];
    let row = parts[1];
    let col = parts[2];
    dayTimeData.push({ type, row, col });
  });
  // Send to PHP
  var tourn_id = <?= $tourn_id ?>;
  var no_of_rounds = <?= $no_of_rounds ?>;
  $.ajax({
    url: "save_day_time.php?tourn_id=" + tourn_id + "&data=" + JSON.stringify(dayTimeData) + "&no_of_rounds=" + no_of_rounds,
    method: 'POST',
    success: function (response) {
      console.log(response);
    }
  });
*/
  let matchData = [];
  match_no = 0;
  $('div[id^="match_"]').each(function () {
    let id = $(this).attr('id'); // e.g. match_5_9
    let parts = id.split("_");
    let type = parts[0];
    let row = parts[1];
    let col = parts[2];
    matchData.push({ type, row, col });
  });
  matchData.sort(function(a, b) {
    return a.col - b.col;
  });
  $.each(matchData, function(index, value)
  {
    match_no++;
    var newID = value['type'] + '_' + value['row'] + '_' + value['col'];
    $('#' + newID).val(Math.ceil(match_no/2));
  });


  //$('.test_column').hide();

  //console.log("tourn_size " + tourn_size);
  $.fn.get_round_no = function (column, tourn_size) {
    if(tourn_size == 64)
    {
      switch (column) {
        case 9:
            round_no = 1;
            break;
        case 11:
            round_no = 2;
            break;
        case 13:
            round_no = 3;
            break;
        case 15:
            round_no = 4;
            break;
        case 17:
            round_no = 5;
            break;
        case 19:
            round_no = 6;
            break;
        case 21:
            round_no = 7;
            break; 
        case 23:
            round_no = 8;
            break; 
        default:
            round_no = 0;
            break; 
        }
    }
    else if(tourn_size == 96)
    {
      switch (column) {
        case 5:
            round_no = 1;
            break;
        case 7:
            round_no = 1;
            break;
        case 9:
            round_no = 2;
            break;
        case 11:
            round_no = 2;
            break;
        case 13:
            round_no = 3;
            break;
        case 15:
            round_no = 4;
            break;
        case 17:
            round_no = 5;
            break;
        case 19:
            round_no = 6;
            break;
        case 21:
            round_no = 7;
            break; 
        case 23:
            round_no = 8;
            break; 
        default:
            round_no = 0;
            break; 
        }
    }
    else if(tourn_size == 128)
    {
      switch (column) {
        case 1:
            round_no = 1;
            break;
        case 3:
            round_no = 1;
            break;
        case 5:
            round_no = 1;
            break;
        case 7:
            round_no = 2;
            break;
        case 9:
            round_no = 2;
            break;
        case 11:
            round_no = 3;
            break;
        case 13:
            round_no = 4;
            break;
        case 15:
            round_no = 5;
            break;
        case 17:
            round_no = 6;
            break;
        case 19:
            round_no = 7;
            break;
        case 21:
            round_no = 8;
            break; 
        case 23:
            round_no = 9;
            break; 
        default:
            round_no = 0;
            break; 
        }
    }
    return round_no;
  }

  $.fn.get_player_1 = function (str) {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = str.substring(0, first);
    var second = (str.split(subStr, 2).join(subStr).length);
    var column = parseInt(str.substring(second+1));
    return $('#' + row + '_' + (column-1) + '_' + (column-1)).val();
  }

  $.fn.get_player_2 = function (str, i) 
  {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var second = (str.split(subStr, 2).join(subStr).length);
    var column = parseInt(str.substring(second+1));

    var player_name_1 = $('#' + (row) + "_" + (column-1) + "_" + (column-1)).val();
    var player_name_2 = $('#' + (row+i) + "_" + (column-1) + "_" + (column-1)).val();
    var player_name_3 = $('#' + (row-i) + "_" + (column-1) + "_" + (column-1)).val();
  
    if(player_name_1 == undefined)
    {
      var row_no = (row);
      var player_name = $('#' + (row_no) + "_" + (column-1) + "_" + (column-1)).val();
    }
    if(player_name_2 == undefined)
    {
      var row_no = (row-i);
      var player_name = $('#' + (row_no) + "_" + (column-1) + "_" + (column-1)).val();
    }
    else if(player_name_3 == undefined)
    {
      var row_no = (row+i);
      var player_name = $('#' + (row_no) + "_" + (column-1) + "_" + (column-1)).val();
    }
    return player_name;
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
    score_detail['row'] = row;
    score_detail['col1'] = col_1;
    score_detail['col2'] = col_2;
    return score_detail;
  }

  $.fn.get_score_2 = function (str, i) 
  {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var column = str.substring(first+1);
    var col_split = column.split('_');
    var col_1 = col_split[0];
    var col_2 = col_split[1];
    var score_detail= [];
    var score_value_1 = $('#' + (row+i) + '_' + column).val();
    var score_value_2 = $('#' + (row) + '_' + column).val();
    var score_value_3 = $('#' + (row-i) + '_' + column).val();
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
    score_detail['col1'] = col_1;
    score_detail['col2'] = col_2;
    return score_detail;
  }

  $.fn.get_next_id = function (str, i, call) 
  {
    if(call == 'SS') // call from scoresheet
    {
      column = column;
    }
    else if(call == "ES") // call from enter_score
    {
      column = (column-1);
    }
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var second = (str.split(subStr, 2).join(subStr).length);
    var column = parseInt(str.substring(second+1));

    var id_detail= [];
    var id_value_1 = $('#' + (parseInt(row)+i) + '_' + (parseInt(column)) + '_' + (parseInt(column))).val();
    var id_value_2 = $('#' + parseInt(row) + '_' + (parseInt(column)) + '_' + (parseInt(column))).val();
    var id_value_3 = $('#' + (parseInt(row)-i) + '_' + (parseInt(column)) + '_' + (parseInt(column))).val();

    if((id_value_1 != undefined) || (id_value_1 == ''))
    {
      var name_next = $('#' + (row+i) + '_' + (column) + '_' + (column)).val();
      var id_next = (row+i) + '_' + column + '_' + column;
      var row_next = (row+i);
    }
    else if((id_value_3 != undefined) || (id_value_3 == ''))
    {
      var name_next = $('#' + (row-i) + '_' + (column) + '_' + (column)).val();
      var id_next = (row-i) + '_' + column + '_' + column;
      var row_next = (row-i);
    }
    var name_current = id_value_2;
    var id_current = row + '_' + column + '_' + column;
    id_detail['column'] = column;
    id_detail['current_id'] = id_current;
    id_detail['next_id'] = id_next;
    id_detail['current_row'] = row;
    id_detail['next_row'] = row_next;
    id_detail['current_name'] = name_current;
    id_detail['next_name'] = name_next;
    return id_detail;
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

  // shift player if initial opposition is a bye................................
  if(<?= json_encode($byeArr) ?> != '')
  {
    //var jsArray = <?php echo json_encode($byeArr); ?>;
    var jsArray = <?php echo json_encode($idMap); ?>;
    var player_data = [];
    $.each(jsArray, function(index, elementId) {
      console.log(elementId);
      //id = elementId['Element'];
      id = elementId;
      nextId = $.fn.get_next_id(id, 1, "ES"); 
      var curr_name = nextId['current_name'];
      var next_name = nextId['next_name'];
      var curr_row = nextId['current_row'];
      var next_row = nextId['next_row'];
      var col = nextId['column'];
      if(curr_row > next_row)
      {
        move_row = curr_row;
      }
      else
      {
        move_row = next_row;
      }
      if((curr_name === "Bye") || (curr_name === ""))
      {
        name = next_name;
      }
      else
      {
        name = curr_name;
      }
      $('#' + (move_row) + "_" + (col+2) + "_" + (col+2)).val(name);
      player_data += ($('#' + move_row + '_' + (col+2) + '_' + (col+2)).val()) + ', ' + move_row + ', ' + (col+2) + ':';
      
    });
    var tourn_id = <?= $tourn_id ?>;
    player_data = player_data.slice(0, -1);
    player_data = JSON.stringify(player_data);
    /*
    $.ajax({
      url:"save_bye_data.php?tourn_id=" + tourn_id + "&player_data=" + player_data,
      method: 'GET',
      success:function(response)
      {
        console.log(response);
      },
    });
    */
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
    for(col = 1; col < 23; col++)
    {
      for(row = 5; row <= 86; row++)
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
        location.reload(true);172
      },
    });
  });

  $('#settings').click(function(e)
  {
    e.preventDefault();
    var tourn_id = <?= $tourn_id ?>;
    var no_of_rounds = <?= $no_of_rounds ?>;
    var best; 
    $.ajax({
      url:"get_settings_data.php?tourn_id=" + tourn_id + "&no_of_rounds=" + no_of_rounds,
      method: 'GET',
      success:function(data)
      {
        console.log("Get Settings " + data);
        if(data != 'No Data')
        {
          var newData = data.split(', ');
          for(b = 0; b < no_of_rounds; b++)
          {
            switch (parseInt(newData[b]))
            {
              case 3:
                best = 0;
                break;
              case 5:
                best = 1;
                break;
              case 7:
                best = 2;
                break;
              case 9:
                best = 3;
                break;
              case 11:
                best = 4;
                break;
            }
            $("#best_of_r" + best + "_c" + b).prop("checked", true); 
          }
          for(d = 0; d < no_of_rounds; d++)
          {
            $("#start_day_r" + newData[no_of_rounds+d] + "_c" + d).prop("checked", true); 
          }

          for(t = 0; t < no_of_rounds; t++)
          {
            $('#start_time_' + t).val(newData[(no_of_rounds*2)+t]);
          }
          if(newData[(no_of_rounds*2)+t] == 'seed')
          {
            $("#seed").prop("checked", true); 
          }
          else if(newData[(no_of_rounds*2)+t] == 'rank')
          {
            $("#rank").prop("checked", true); 
          }
        }
      },
    });
    $('#settings_modal').modal('show');
  });

  $('#scores_modal').on('shown.bs.modal', function () 
  {
    $('#newplayer_1').click(function(e)
    {
      e.preventDefault();
      var tourn_id = <?= $tourn_id ?>;
      var player_name = $('#playername_1').html();
      var memb_id = $('#member_id_1').html();
      var new_player = $('#tags_1').val();
      var element_id = $('#scores_element_id_1').html();
      var tourn_type = '<?= $tourn_type ?>';
      //console.log(new_player);
      if(new_player != '')
      {
        if(player_name != new_player)
        {
          $.ajax({
            url:"update_player.php?tourn_id=" + tourn_id + "&player_id=" + memb_id + "&element_id=" + element_id + "&tourn_type=" + tourn_type + "&existing=" + player_name + "&new_player=" + new_player,
            method: 'GET',
            success:function(response)
            {
              console.log(response);
              alert("Players Name has been Changed");
              $('#scores_modal').modal('hide');
              location.reload(true);
            }
          });
        }
      }
    });

    $('#newplayer_2').click(function(e)
    {
      e.preventDefault();
      var tourn_id = <?= $tourn_id ?>;
      var player_name = $('#playername_2').html();
      var memb_id = $('#member_id_2').html();
      var new_player = $('#tags_2').val();
      var element_id = $('#scores_element_id_2').html();
      var tourn_type = '<?= $tourn_type ?>';
      if(new_player != '')
      {
        if(player_name != new_player)
        {
          $.ajax({
            url:"update_player.php?tourn_id=" + tourn_id + "&player_id=" + memb_id + "&element_id=" + element_id + "&tourn_type=" + tourn_type + "&existing=" + player_name + "&new_player=" + new_player,
            method: 'GET',
            success:function(response)
            {
              console.log(response);
              alert("Players Name has been Changed");
              $('#scores_modal').modal('hide');
              location.reload(true);
            }
          });
        }
      }
    });
  });

  $('#save_settings').click(function(e)
  {
    e.preventDefault();
    var tourn_id = <?= $tourn_id ?>;
    var no_of_rounds = <?= $no_of_rounds ?>;
    var settings_time = [];
    var settings_days = [];
    var settings_best_of = [];
    var default_start;
    var best;
    var best_of;
    var day;
    var sort;
    if($(`input[name='sort']:checked`).val() == undefined)
    {
      sort = '';
    }
    else
    {
      sort = $(`input[name='sort']:checked`).val();
    }
  
    for(i = 0; i < no_of_rounds; i++) // no of rounds
    {
      if($(`input[name='days_${i}']:checked`).val() == undefined)
      {
        day = '';
      }
      else
      {
        day = $(`input[name='days_${i}']:checked`).val();
      }
      settings_days += day + ", ";
      
      best_of = $(`input[name='best_of_${i}']:checked`).val();
      var best = (parseInt(best_of)+1);
      switch (best)
      {
        case 1:
          best = 3;
          break;
        case 2:
          best = 5;
          break;
        case 3:
          best = 7;
          break;
        case 4:
          best = 9;
          break;
        case 5:
          best = 11;
          break;
      }
      settings_best_of += best + ", ";
      if($(`input[id='start_time_${i}`).val() == undefined)
      {
        start_time = '00:00';
      }
      else
      {
        start_time = $(`input[id='start_time_${i}`).val();
      }
      settings_time += start_time + ", ";
    }
    let dayTimeData = [];
    $('input[id^="T_"]').each(function () {
      let id = $(this).attr('id'); // e.g. D_5_6
      let parts = id.split("_");
      let type = parts[0];
      let row = parts[1];
      let col = parts[2];
      dayTimeData.push({row, col });
    });
    console.log(settings_time);
    $.ajax({
      url: "save_settings.php",
      method: "GET",
      data: {
        tourn_id: tourn_id,
        sort: sort,
        settings_best_of: settings_best_of,
        settings_days: settings_days,
        settings_time: settings_time,
        rounds: no_of_rounds,
        table_data: JSON.stringify(dayTimeData)
      },
      success: function (response) 
      {
        alert(response);
      },
      error: function (xhr, status, error) 
      {
        console.error("AJAX Error:", error);
      }
    });
    $('#settings_modal').modal('hide');
    //window.location.reload(true);
  });

  $('#interim_save_modal_button').click(function(e)
  {
    e.preventDefault();
    ///alert("Here");
    $.fn.save_modal('interim');
  });

  $('#finished_save_modal_button').click(function(e)
  {
    e.preventDefault();
    //alert("Here");
    $.fn.save_modal('finished');
  });


  $.fn.save_modal = function(mode)
  {
    //console.log($('.save_modal_button').val());
    // get data from scoresheet
    var score_data_1 = [];
    var forfeit_data_1 = [];
    var break_data_1 = [];
    var score_data_2 = [];
    var forfeit_data_2 = [];
    var break_data_2 = [];
    var to_break_data_1 = [];
    var to_break_data_2 = [];
    var scoredata_player_1 = new Array;
    var scoredata_player_2 = new Array;
    var game_score_1 = 0;
    var game_score_2 = 0;
    var tourn_id = $('#tourn_id').html();
    var element_id_1 = $('#scores_element_id_1').html();
    var element_id_2 = $('#scores_element_id_2').html();
    var referee = $('#referee').find('option:selected').text();
    var roving = $('#roving').val();
    var self = $('#self').val();
    var marker = $('#marker').find('option:selected').text();
    var table_no = $('#table_no').val();
    var round = $('#round').val();
    var start = $('#start').val();
    var finish = $('#finish').val();
    var match_no = $('#match_no').html();
    var best_of = $('#best').html();
    var winner_row = '';
    var winner_name = '';
    var loser_name = '';

    if(mode == 'finished')
    {
      //console.log("Roving " + $('#roving').prop("checked") + ", Ref" + referee + ":");
      var dt = new Date();
      var hours = dt.getHours();
      var minutes = dt.getMinutes();
      minutes = (minutes < 10 ? "0" : "") + minutes;
      var time = hours + ":" + minutes;
      $('#finish').val(time);
      finish = time;

      if(($('#roving').prop("checked") === false) && ((referee === '') || (referee === ' ') || (referee === null)))
      {
        alert('A "Referee" or checking "Roving Referee" is required!');
        return;
      }
      else if(($('#roving').prop("checked") === true) || (referee != ''))
      {
        if($('#start').val() === '')
        {
          alert('Both Start and Finish times are required!');
          return;
        }
      }
    }

    // clear all score entry before saving
    for(i = 0; i < 7; i++) // max number of 'Best Of' in database
    {
      score_data_1[i] = 0;
      score_data_2[i] = 0;
      break_data_1[i] = '';
      break_data_2[i] = '';
      to_break_data_1[i] = 0;
      to_break_data_2[i] = 0;
    }
    // create array with scores data from scoresheet
    for(i = 0; i < best_of; i++) // max number of 'Best Of'
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
      score_data_1[i] = parseInt($('#score' + (i+1) + '_1').val());
      break_data_1[i] = $('#brk' + (i+1) + '_1').val();
      score_data_2[i] = parseInt($('#score' + (i+1) + '_2').val());
      break_data_2[i] = $('#brk' + (i+1) + '_2').val();
      if(score_data_1[i] > score_data_2[i])
      {
        game_score_1++;
        winner_row_1 = element_id_1;
        winner_name_1 = $('#playername_1').html();
        $('#' + row + '_' + (col+1) + '_' + (col+1)).val(game_score_1);
      }
      else if(score_data_2[i] > score_data_1[i])
      {
        game_score_2++;
        winner_row_2 = element_id_2;
        winner_name_2 = $('#playername_2').html();
        $('#' + (row+1) + '_' + (col+1) + '_' + (col+1)).val(game_score_2);
      }
    }
    // check of forfeit or walkover
    if($('#forfeit_1').is(":checked"))
    {
      forfeit_1 = 1;
      score_data_1[0] = 0;
      score_data_1[1] = 0;
      score_data_1[2] = 0;
      score_data_1[3] = 0;
      score_data_1[4] = 0;
      score_data_1[5] = 0;
      score_data_1[6] = 0;
      game_score_1 = 0;

      score_data_2[0] = 1;
      score_data_2[1] = 1;
      score_data_2[2] = 1;
      score_data_2[3] = 0;
      score_data_2[4] = 0;
      score_data_2[5] = 0;
      score_data_2[6] = 0;
      game_score_2 = 3;
      winner_name_2 = $('#playername_2').html();
    }
    else
    {
      forfeit_1 = 0;
    }
    if($('#forfeit_2').is(":checked"))
    {
      forfeit_2 = 1;
      score_data_1[0] = 1;
      score_data_1[1] = 1;
      score_data_1[2] = 1;
      score_data_1[3] = 0;
      score_data_1[4] = 0;
      score_data_1[5] = 0;
      score_data_1[6] = 0;
      game_score_1 = 3;
      winner_name_1 = $('#playername_1').html();

      score_data_2[0] = 0;
      score_data_2[1] = 0;
      score_data_2[2] = 0;
      score_data_2[3] = 0;
      score_data_2[4] = 0;
      score_data_2[5] = 0;
      score_data_2[6] = 0;
      game_score_2 = 0;
    }
    else
    {
      forfeit_2 = 0;
    }

    if($('#walkover_1').is(":checked"))
    {
      walkover_1 = 1;
      score_data_1[0] = 1;
      score_data_1[1] = 1;
      score_data_1[2] = 1;
      score_data_1[3] = 0;
      score_data_1[4] = 0;
      score_data_1[5] = 0;
      score_data_1[6] = 0;
      game_score_1 = 3;
      winner_name_1 = $('#playername_1').html();

      score_data_2[0] = 0;
      score_data_2[1] = 0;
      score_data_2[2] = 0;
      score_data_2[3] = 0;
      score_data_2[4] = 0;
      score_data_2[5] = 0;
      score_data_2[6] = 0;
      game_score_2 = 0;
    }
    else
    {
      walkover_1 = 0;
    }
    if($('#walkover_2').is(":checked"))
    {
      walkover_2 = 1;
      score_data_1[0] = 0;
      score_data_1[1] = 0;
      score_data_1[2] = 0;
      score_data_1[3] = 0;
      score_data_1[4] = 0;
      score_data_1[5] = 0;
      score_data_1[6] = 0;
      game_score_1 = 0;

      score_data_2[0] = 1;
      score_data_2[1] = 1;
      score_data_2[2] = 1;
      score_data_2[3] = 0;
      score_data_2[4] = 0;
      score_data_2[5] = 0;
      score_data_2[6] = 0;
      game_score_2 = 3;
      winner_name_2 = $('#playername_2').html();
    }
    else
    {
      walkover_2 = 0;
    }
    forfeit_data_1[0] = parseInt(forfeit_1);
    forfeit_data_2[0] = parseInt(forfeit_2);
    forfeit_data_1[1] = parseInt(walkover_1);
    forfeit_data_2[1] = parseInt(walkover_2);
/*    
    console.log("F 1 " + forfeit_1);
    console.log("F 2 " + forfeit_2);
    console.log("W 1 " + walkover_1);
    console.log("W 2 " + walkover_2);
*/
    var player_name_1 = $('#playername_1').html();
    var player_name_2 = $('#playername_2').html();
    var element_id = $('#scores_element_id_1').html();
    var element_other = $('#scores_element_id_2').html();
    var row_1 = parseInt($('#row_1').html());
    var row_2 = parseInt($('#row_2').html());
    var col_1 = parseInt($('#column_1').html());
    var col_2 = parseInt($('#column_2').html());
    var row = parseInt($('#row_1').html());
    var col = parseInt($('#column_1').html());

    // get top and botton rows
    var move_to_row;
    var move_to_col;

    if(row_1 > row_2)
    {
      var top_row = row_2;
      var bottom_row = row_1;
    }
    else
    {
      var top_row = row_1;
      var bottom_row = row_2;
    }
    var move_to_col = (col_1+2);

    if(col < 15)
    {
      var player_10 = $('#' + row + '_' + (col_1+2) + '_' + (col_1+2)).val();
      var player_20 = $('#' + (row+1) + '_' + (col_1+2) + '_' + (col_1+2)).val();
      var player_30 = $('#' + (row-1) + '_' + (col_1+2) + '_' + (col_1+2)).val();

      // get winners name and move to row
      if (game_score_1 > game_score_2) 
      {
          var winner_player_row = top_row;
          var loser_player_row = bottom_row;
          var winner_score = game_score_1;
          var loser_score = game_score_2;

          winner_name = winner_name_1;
          winner_row = winner_player_row;
      } 
      else if (game_score_2 > game_score_1) 
      {
          var winner_player_row = bottom_row;
          var loser_player_row = top_row;
          var winner_score = game_score_2;
          var loser_score = game_score_1;

          winner_name = winner_name_2;
          winner_row = winner_player_row;
      }
      move_to_row = bottom_row;

      if(col == 13)
      {
        if((top_row == 10) || (top_row == 20) || (top_row == 31) || (top_row == 41) || (top_row == 54) || (top_row == 64) || (top_row == 75) || (top_row == 85))
        {
          move_to_row = top_row;
        }
      }
    }
    else if(col == 15)
    {
      var player_10 = $('#' + row + '_' + (col_1+2) + '_' + (col_1+2)).val();
      var player_20 = $('#' + (row+4) + '_' + (col_1+2) + '_' + (col_1+2)).val();
      var player_30 = $('#' + (row-4) + '_' + (col_1+2) + '_' + (col_1+2)).val();

      // get winners name and move to row
      if (game_score_1 > game_score_2) 
      {
          var winner_player_row = top_row;
          var loser_player_row = bottom_row;
          var winner_score = game_score_1;
          var loser_score = game_score_2;

          winner_name = winner_name_1;
          winner_row = winner_player_row;
      } 
      else if (game_score_2 > game_score_1) 
      {
          var winner_player_row = bottom_row;
          var loser_player_row = top_row;
          var winner_score = game_score_2;
          var loser_score = game_score_1;

          winner_name = winner_name_2;
          winner_row = winner_player_row;
      }  
      move_to_row = (top_row + 2);
    }
    else if(col == 17)
    {
      // get winners name and move to row
      if (game_score_1 > game_score_2) 
      {
          var winner_player_row = top_row;
          var loser_player_row = bottom_row;
          var winner_score = game_score_1;
          var loser_score = game_score_2;

          winner_name = winner_name_1;
          winner_row = winner_player_row;
      } 
      else if (game_score_2 > game_score_1) 
      {
          var winner_player_row = bottom_row;
          var loser_player_row = top_row;
          var winner_score = game_score_2;
          var loser_score = game_score_1;

          winner_name = winner_name_2;
          winner_row = winner_player_row;
      }
      var player_10 = $('#' + (row+4) + '_' + (col_1+2) + '_' + (col_1+2)).val();
      var player_30 = $('#' + (row-6) + '_' + (col_1+2) + '_' + (col_1+2)).val();

      var move_to_col = (col_1+2);
      if(player_10 != undefined)
      {
        move_to_row = (row+1);
      }
      if(player_30 != undefined)
      {
        move_to_row = (row-1);
      }
      move_to_row = (top_row + 4);
      
      //$('#' + move_to_row + '_' + move_to_col + '_' + move_to_col).val(winner_name);
    }
    else if(col == 19)
    {
      // get winners name and move to row
      if (game_score_1 > game_score_2) 
      {
          var winner_player_row = top_row;
          var loser_player_row = bottom_row;
          var winner_score = game_score_1;
          var loser_score = game_score_2;

          winner_name = winner_name_1;
          winner_row = winner_player_row;
      } 
      else if (game_score_2 > game_score_1) 
      {
          var winner_player_row = bottom_row;
          var loser_player_row = top_row;
          var winner_score = game_score_2;
          var loser_score = game_score_1;

          winner_name = winner_name_2;
          winner_row = winner_player_row;
      }
      var player_10 = $('#' + (row+12) + '_' + (col_1+2) + '_' + (col_1+2)).val();
      var player_20 = $('#' + (row) + '_' + (col_1+2) + '_' + (col_1+2)).val();
      var player_30 = $('#' + (row-9) + '_' + (col_1+2) + '_' + (col_1+2)).val();

      var move_to_col = (col_1+2);
      if(player_10 != undefined)
      {
        move_to_row = (row+3);
      }
      if(player_30 != undefined)
      {
        move_to_row = (row-3);
      }
      move_to_row = (top_row + 12);

      //$('#' + move_to_row + '_' + move_to_col + '_' + move_to_col).val(winner_name);
    }
    else if(col == 21)
    {
      // get winners name and move to row
      if (game_score_1 > game_score_2) 
      {
          var winner_player_row = top_row;
          var loser_player_row = bottom_row;
          var winner_score = game_score_1;
          var loser_score = game_score_2;

          winner_name = winner_name_1;
          winner_row = winner_player_row;
      } 
      else if (game_score_2 > game_score_1) 
      {
          var winner_player_row = bottom_row;
          var loser_player_row = top_row;
          var winner_score = game_score_2;
          var loser_score = game_score_1;

          winner_name = winner_name_2;
          winner_row = winner_player_row;
      }
      var player_10 = $('#' + (row+20) + '_' + (col_1+2) + '_' + (col_1+2)).val();
      var player_20 = $('#' + (row) + '_' + (col_1+2) + '_' + (col_1+2)).val();
      var player_30 = $('#' + (row-19) + '_' + (col_1+2) + '_' + (col_1+2)).val();
 
      var move_to_col = 23;
      var move_to_row = 44;

      //$('#' + move_to_row + '_' + move_to_col + '_' + move_to_col).val(winner_name);
    }

    // add match scores to tourn page
    $('#' + row_1 + '_' + (col_1+1) + '_' + (col_1+1)).val(game_score_1);
    $('#' + row_2 + '_' + (col_1+1) + '_' + (col_1+1)).val(game_score_2);

    if(mode == 'interim')
    {
      console.log('Do Nothing');
    }
    else if(mode == 'finished')
    {
      console.log("Moving Winner");
      // add winner name to tourn page next round
      $('#' + (move_to_row) + '_' + (move_to_col) + '_' + (move_to_col)).val(winner_name);
    }
    

    //console.log(player_name_1);
    //console.log(player_name_2);

    // create arrays to save with scoresheet data
    scoredata_player_1 = 
          row_1 + ", " + 
          col_1 + ", " + 
          player_name_1 + ", " + 
          game_score_1 + ", " + 
          score_data_1 + ", " + 
          forfeit_data_1 + ", " + 
          break_data_1 + ", " + 
          to_break_data_1 + ", " + 
          move_to_row + ", " + 
          move_to_col; 
    scoredata_player_2 = 
          row_2 + ", " + 
          col_2 + ", " + 
          player_name_2 + ", " + 
          game_score_2 + ", " + 
          score_data_2 + ", " + 
          forfeit_data_2 + ", " + 
          break_data_2 + ", " + 
          to_break_data_2 + ", " + 
          move_to_row + ", " + 
          move_to_col; 
    if($('#roving').is(":checked"))
    {
      roving = 1;
    }
    else
    {
      roving = 0;
    }
    if($('#self').is(":checked"))
    {
      self = 1;
    }
    else
    {
      self = 0;
    }

    scoredata_player_3 = 
          referee + ", " + 
          roving + ", " + 
          self + ", " + 
          marker + ", " + 
          table_no + ", " + 
          round + ", " + 
          start + ", " + 
          finish + ", " + 
          match_no;
    scoredata_player_1 = JSON.stringify(scoredata_player_1);
    scoredata_player_2 = JSON.stringify(scoredata_player_2);
    scoredata_player_3 = JSON.stringify(scoredata_player_3);
    $.ajax({
      url:"save_result_data.php?tourn_id=" + tourn_id + "&score_data_1=" + scoredata_player_1 + "&score_data_2=" + scoredata_player_2 + "&score_data_3=" + scoredata_player_3,
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
        forfeit_data_1[0] = $('#forfeit_1').prop("checked", false); 
        forfeit_data_2[0] = $('#forfeit_2').prop("checked", false); 
        forfeit_data_1[1] = $('#walkover_1').prop("checked", false); 
        forfeit_data_2[1] = $('#walkover_2').prop("checked", false); 

        $('#roving').prop("checked", false); 
        $('#self').prop("checked", false); 

        $('#scores_modal').modal('hide');
        $('#scorestable_1').empty();
        $('#scorestable_2').empty();
        //location.reload(true);
      },
    });
  };

  var availableTags = <?php echo $player_data; ?>;
  // change player name if changed  
  $('#scores_modal').on('shown.bs.modal', function () 
  {
    $("#tags_1").autocomplete({
      source:  availableTags,
      appendTo: "#autocompleteAppendToMe_1"
    });
  
    $("#tags_2").autocomplete({
      source:  availableTags,
      appendTo: "#autocompleteAppendToMe_2"
    });
  });

  $.fn.get_member_id = function (fullname) 
  {
    var response;
    $.ajax({
      url:"get_member_id.php?player_name=" + fullname,
      method: 'GET',
      async: false,
      success:function(response)
      {
        result = response;
      }
    });
    return result;
  }

  $('#scores_modal').on('change', '#to_brk1_1', function(e) 
  {
    $('#to_brk3_1').prop('checked', true);
    $('#to_brk5_1').prop('checked', true);
    $('#to_brk7_1').prop('checked', true);
    $('#to_brk2_2').prop('checked', true);
    $('#to_brk4_2').prop('checked', true);
    $('#to_brk6_2').prop('checked', true);

    $('#to_brk1_2').prop('checked', false);
    $('#to_brk3_2').prop('checked', false);
    $('#to_brk5_2').prop('checked', false);
    $('#to_brk7_2').prop('checked', false);
    $('#to_brk2_1').prop('checked', false);
    $('#to_brk4_1').prop('checked', false);
    $('#to_brk6_1').prop('checked', false);
  });

  $('#scores_modal').on('change', '#to_brk1_2', function(e) 
  {
    $('#to_brk3_2').prop('checked', true);
    $('#to_brk5_2').prop('checked', true);
    $('#to_brk7_2').prop('checked', true);
    $('#to_brk2_1').prop('checked', true);
    $('#to_brk4_1').prop('checked', true);
    $('#to_brk6_1').prop('checked', true);

    $('#to_brk1_1').prop('checked', false);
    $('#to_brk3_1').prop('checked', false);
    $('#to_brk5_1').prop('checked', false);
    $('#to_brk7_1').prop('checked', false);
    $('#to_brk2_2').prop('checked', false);
    $('#to_brk4_2').prop('checked', false);
    $('#to_brk6_2').prop('checked', false);
  });

  $('#scores_modal').on('change', '#forfeit_1', function(e) 
  {
    $('#forfeit_1').prop('checked', true);
    $('#forfeit_2').prop('checked', false);
    $('#walkover_1').prop('checked', false);
    $('#walkover_2').prop('checked', false);
  });

  $('#scores_modal').on('change', '#forfeit_2', function(e) 
  {
    $('#forfeit_2').prop('checked', true);
    $('#forfeit_1').prop('checked', false);
    $('#walkover_1').prop('checked', false);
    $('#walkover_2').prop('checked', false);
  });

  $('#scores_modal').on('change', '#walkover_1', function(e) 
  {
    $('#forfeit_1').prop('checked', false);
    $('#forfeit_2').prop('checked', false);
    $('#walkover_1').prop('checked', true);
    $('#walkover_2').prop('checked', false);
  });

  $('#scores_modal').on('change', '#walkover_2', function(e) 
  {
    $('#forfeit_1').prop('checked', false);
    $('#forfeit_2').prop('checked', false);
    $('#walkover_1').prop('checked', false);
    $('#walkover_2').prop('checked', true);
  });

  $('.player').on('click', function(e) 
  {
    e.preventDefault();
    $('#scorestable_1').empty();
    $('#scorestable_2').empty();
    var id = $(this).attr('id');
    var tourn_id = <?= $tourn_id ?>;
    var tourn_size = <?= $tourn_size ?>;
    var subStr = '_';
    var first_1 = (id.split(subStr, 1).join(subStr).length);
    var row_1 = parseInt(id.substring(0, first_1));
    var col_1 = parseInt(id.substring(first_1+1));
    var round_no = $.fn.get_round_no(col_1, tourn_size);
    console.log("Round " + round_no);
    $('#round').html(round_no);
    $('#match_no').html($('#match_' + row_1 + '_' + col_1).val());
    $('#scores_modal').modal('show'); 

    if(col_1 < 15)
    {
      score_array = $.fn.get_next_id(id, 1, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 15)
    {
      score_array = $.fn.get_next_id(id, 4, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 17)
    {
      score_array = $.fn.get_next_id(id, 10, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 19)
    {
      score_array = $.fn.get_next_id(id, 21, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 21)
    {
      score_array = $.fn.get_next_id(id, 44, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    // ajax to get best of tables
    $.ajax({
      url:"get_best_of_data.php?tourn_id=" + tourn_id + "&round=" + round_no,
      method: 'GET',
      success:function(data)
      {
        var best_of = data;
        var colspan = (best_of+1);
        var disabled = '';
      
        if(best_of > 7)
        {
          $('#modal_size').addClass('modal-lg');
        }
        else
        {
          $('#modal_size').removeClass('modal-lg');
        }
        
        output_1 = '';
        if(data === 'No Data')
        {
          output_1 += ("<div>No Best of data available!</div>");
        }
        else
        {
          obj = jQuery.parseJSON(data);
          output_1 += ("<table class='table table-striped table-bordered dt-responsive nowrap display'>");
          output_1 += ("<tr>");
          output_1 += ("<tr>");
          output_1 += ("<td colspan='" + colspan + "' align='center'>");
          output_1 += ("<b><div id='playername_1'>" + player_name_1 + "</div></b>");
          output_1 += ("<div hidden id='member_id_1'>" + $.fn.get_member_id(player_name_1) + "</div>");
          output_1 += ("<div id='scores_element_id_1'>" + score_array['current_id'] + "</div>");
          output_1 += ("<div hidden id='row_1'>" + row_1 + "</div>");
          output_1 += ("<div hidden id='column_1'>" + col + "</div>");
          output_1 += ("<div hidden id='best'>" + best_of + "</div>");
          output_1 += ("</td>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          output_1 += ("<td colspan='" + colspan + "' align='center'>Change Player:&nbsp;<input id='tags_1' style='width:200px; height:25px'>");
          output_1 += ("<div id='autocompleteAppendToMe_1'></div></td>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          output_1 += ("<td colspan='" + colspan + "' align='center'><a class='btn btn-default btn-xs' id='newplayer_1'>Save New Player</a></td>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'>Frame " + (i+1) + "</td>");
          }
          output_1 += ("<td align='center'>Best Of " + best_of + "</td>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'>Points</td>");
          }
          output_1 += ("<td rowspan='5' valign='center' align='center'><br><br>Frames:<br><input type='text' id='game_score_1' style='text-align: center; width:20px; height:20px'><br><br>");
          output_1 += ("&nbsp;Forfeit<br><input type='checkbox' id='forfeit_1'><br><br>");
          output_1 += ("&nbsp;Walkover<br><input type='checkbox' id='walkover_1'></td>");
          output_1 += ("</tr>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'><input type='text' class='score_input_1' id='score" + (i+1) + "_1' style='text-align: center; width:30px; height:20px' tabindex=" + (i+1) + "></td>");
          }
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'>Breaks 40+</td>");
          }
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'><input type='text' id='brk" + (i+1) + "_1' style='width:50px; height:20px' tabindex=" + (i+8) + " style='text-align: left';></td>");
          }
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            if(i > 0)
            {
              disabled = 'disabled';
            }
            output_1 += ("<td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk" + (i+1) + "_1' " + disabled + "></td>");
          }
          output_1 += ("</tr>"); 
          output_1 += ("</table>"); 
          $($.parseHTML(output_1)).appendTo('#scorestable_1');

          output_2 = '';
          output_2 += ("<table class='table table-striped table-bordered dt-responsive nowrap display'>");
          output_2 += ("<tr>");
          output_2 += ("<tr>");
          output_2 += ("<td colspan='" + colspan + "' align='center'>");
          output_2 += ("<b><div id='playername_2'>" + player_name_2 + "</div></b>");
          output_2 += ("<div hidden id='member_id_2'>" + $.fn.get_member_id(player_name_2) + "</div>");
          output_2 += ("<div hidden id='scores_element_id_2'>" + score_array['current_id'] + "</div>");
          output_2 += ("<div hidden id='row_2'>" + row_2 + "</div>");
          output_2 += ("<div hidden id='column_2'>" + col + "</div>");
          output_2 += ("</td>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          output_2 += ("<td colspan='" + colspan + "' align='center'>Change Player:&nbsp;<input id='tags_2' style='width:200px; height:25px'>");
          output_2 += ("<div id='autocompleteAppendToMe_2'></div></td>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          output_2 += ("<td colspan='" + colspan + "' align='center'><a class='btn btn-default btn-xs' id='newplayer_2'>Save New Player</a></td>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'>Frame " + (i+1) + "</td>");
          }
          output_2 += ("<td align='center'>Best Of " + best_of + "</td>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'>Points</td>");
          }
          output_2 += ("<td rowspan='5' valign='center' align='center'><br><br>Frames:<br><input type='text' id='game_score_2' style='text-align: center; width:20px; height:20px'><br><br>");
          output_2 += ("&nbsp;Forfeit<br><input type='checkbox' id='forfeit_2'><br><br>");
          output_2 += ("&nbsp;Walkover<br><input type='checkbox' id='walkover_2'></td>");
          output_2 += ("</tr>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'><input type='text' class='score_input_2' id='score" + (i+1) + "_2' style='text-align: center; width:30px; height:20px' tabindex=" + (i+15) + "></td>");
          }
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'>Breaks 40+</td>");
          }
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'><input type='text' id='brk" + (i+1) + "_2' style='width:50px; height:20px' tabindex=" + (i+23) + " style='text-align: left';></td>");
          }
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            if(i > 0)
            {
              disabled = 'disabled';
            }
            output_2 += ("<td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk" + (i+1) + "_2' " + disabled + "></td>");
          }
          output_2 += ("</tr>"); 
          output_2 += ("</table>"); 
          $($.parseHTML(output_2)).appendTo('#scorestable_2');
        }
      },
    });
    $.ajax({
      url:"get_result_data.php?tourn_id=" + tourn_id + "&player_name_1=" + player_name_1 + "&row_1=" + row_1 + "&col=" + col_1 + "&player_name_2=" + player_name_2 + "&row_2=" + row_2 + "&col=" + col_1,
      method: 'GET',
      success:function(data)
      {
        console.log(data);
        if(data === 'No Data')
        {
          for (var i = 0; i < 23; i++) 
          {
            $('#member_id_1').val('');
            if((i > 0) && (i <= 7))
            {
              $('#score' + (i) + '_1').val(0);
            }
            if((i > 7) && (i <= 14))
            {
              $('#brk' + (i-7) + '_1').val('');
            }
            if((i > 14) && (i <= 21))
            {
              $('#to_brk' + (i-7) + '_1').val(0);
            }
            $('#game_score_1').val('0');
            $('#referee').val('');
            $('#roving').val('');
            $('#self').val('');
            $('#marker').val('');
            $('#table_no').val('0');
            $('#round').val('0');
            $('#start').val('');
            $('#finish').val('');
            $('#match_no').val('0');
          }
          for (var i = 0; i < 26; i++) 
          {
            $('#member_id_2').val('');
            if((i > 0) && (i <= 7))
            {
              $('#score' + (i) + '_2').val(0);
            }
            if((i > 7) && (i <= 14))
            {
              $('#brk' + (i-7) + '_2').val('');
            }
             if((i > 14) && (i <= 21))
            {
              $('#to_brk' + (i-7) + '_2').val(0);
            }
            $('#game_score_2').val('');
          }
        }
        else
        {
          var newData = data.split(';');
          var member_1_data = newData[0].split(", ");
          var member_2_data = newData[1].split(", ");
          if((member_1_data.length > 1) || (member_2_data.length > 1))
          {
            var cb_index = 1;
            for (var i = 0; i < member_1_data.length; i++) 
            {
              $('#member_id_1').val(member_1_data[0]);
              if((i > 0) && (i <= 7))
              {
                $('#score' + i + '_1').val(member_1_data[i]);
              }

              if(member_1_data[8] == 1)
              {
                $('#forfeit_1').prop('checked', true);
              }
              else if(member_1_data[8] == 0)
              {
                $('#forfeit_1').prop("checked", false);
              }
              if(member_1_data[9] == 1)
              {
                $('#walkover_1').prop('checked', true);
              }
              else if(member_1_data[9] == 0)
              {
                $('#walkover_1').prop("checked", false);
              }

              if((i > 9) && (i <= 16))
              {
                $('#brk' + (i-9) + '_1').val(jQuery.trim(member_1_data[i]));
              }
              if((i > 16) && (i <= 23))
              {
                if(member_1_data[i] == 1)
                {
                  $('#to_brk' + cb_index + '_1').prop('checked', true);
                }
                else if(member_1_data[i] == 0)
                {
                  $('#to_brk' + cb_index + '_1').prop("checked", false);
                }
                cb_index++;
              }
              $('#game_score_1').val(member_1_data[(24)]);

              $('#referee').val(member_1_data[(25)]);
              if(member_1_data[(26)] == 1)
              {
                $('#roving').prop('checked', true);
              }
              else if(member_1_data[26] == 0)
              {
                $('#roving').prop("checked", false);
              }
              if(member_1_data[27] == 1)
              {
                $('#self').prop('checked', true);
              }
              else if(member_1_data[27] == 0)
              {
                $('#self').prop("checked", false);
              }
              $('#marker').val($.trim(member_1_data[(28)]));
              $('#table_no').val(member_1_data[(29)]);
              $('#round').val(member_1_data[(30)]);
              $('#start').val(jQuery.trim(member_1_data[(31)]));
              $('#finish').val(jQuery.trim(member_1_data[(32)]));
              $('#match_no').val(member_1_data[(33)]);
            }
            cb_index = 1;
            for (var i = 0; i < member_2_data.length; i++) 
            {
              $('#member_id_2').val(member_2_data[0]);
              if((i > 0) && (i <= 7))
              {
                $('#score' + (i) + '_2').val(member_2_data[(i)]);
              }

              if(member_2_data[8] == 1)
              {
                $('#forfeit_2').prop('checked', true);
              }
              else if(member_2_data[8] == 0)
              {
                $('#forfeit_2').prop("checked", false);
              }
              if(member_2_data[9] == 1)
              {
                $('#walkover_2').prop('checked', true);
              }
              else if(member_2_data[9] == 0)
              {
                $('#walkover_2').prop("checked", false);
              }
          
              if((i > 9) && (i <= 16))
              {
                $('#brk' + (i-9) + '_2').val(jQuery.trim(member_2_data[i]));
              }
              if((i > 16) && (i <= 23))
              {
                if(member_2_data[i] == 1)
                {
                  $('#to_brk' + cb_index + '_2').prop('checked', true);
                }
                else if(member_2_data[i] == 0)
                {
                  $('#to_brk' + cb_index + '_2').prop("checked", false);
                }
                cb_index++;
              }
              $('#game_score_2').val(member_2_data[(24)]);
            }
          }
        }
        $('#scores_modal').modal('show');
      },
      error: function() 
      {
        alert("There was an error. Try again please!");
      }
    });
  });

  $('.timepicker').timepicker({
    template: false,
    timeFormat: 'H:mm',
    interval: 30,
    minTime: '8',
    dynamic: false,
    dropdown: true,
    scrollbar: true,
    font_size: 12,
    appendTo: function() 
    { /* added to display timepicker dropdown in front of modal */
        // find nearest modal (if any)
        var $modal = $(this).closest('.modal');
        return $modal.length ? $modal : 'body';
    },
    change: function(time) 
    {
      let id = $(this).attr('id');
      if((id != 'start') && (id != 'finish'))
      {
        var tourn_id = <?= $tourn_id ?>;
        var element = $(this);
        id = id.slice(2);
        var timepicker = element.timepicker();
        time = timepicker.format(time);
        var subStr = '_';
        var first = (id.split(subStr, 1).join(subStr).length);
        var row = parseInt(id.substring(0, first));
        var col = id.substring(first+1);
        $.ajax({
          url:"save_time.php?tourn_id=" + tourn_id + "&row=" + row + "&col=" + col + "&time=" + time,
          method: 'GET',
          success:function(response) {
            console.log(response);
          }
        });
      }
    }
  });

  $('.time_settings').timepicker({
    template: false,
    timeFormat: 'H:mm',
    interval: 30,
    minTime: '8',
    dynamic: false,
    dropdown: true,
    scrollbar: true,
    appendTo: function() 
    { /* added to display timepicker dropdown in front of modal */
        // find nearest modal (if any)
        var $modal = $(this).closest('.modal');
        return $modal.length ? $modal : 'body';
    },
  });
  
  $('.date_select').on('change', function() 
  {
    var id1 = $(this).attr('id');
    var id = id1.slice(2);
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

  $.fn.check_int = function (page, id) 
  {
    if(page ==='ES')
    {
      var subStr = '_';
      var first = (id.split(subStr, 1).join(subStr).length);
      var row = parseInt(id.substring(0, first));
      var col = parseInt(id.substring(first+1));
      var score = $.fn.get_score_1(id)['value'];
      if($.isNumeric(score) === false)
      {
        alert("Score needs to be an integer");
        $('#' + id).val('');
        return;
      }
    }
    else if(page === 'SS')
    {
      let val = $('#' + id).val();
      if (!$.isNumeric(val) || val.indexOf('.') !== -1) 
      {
        if (val !== '') 
        {
          alert("Score needs to be an integer");
        }
        $('#' + id).val('');
      }
    }
  }

  $('.enter_score').focusout(function(e) 
  {
    e.preventDefault();
    var id = $(this).attr('id');
    var subStr = '_';
    var first = (id.split(subStr, 1).join(subStr).length);
    var row = parseInt(id.substring(0, first));
    var col = parseInt(id.substring(first+1));
    var tourn_id = <?= $tourn_id ?>;
    var move_to_row;
    var move_to_col;
    var winner_name;
    var loser_name;
    var winner_player_row;
    var loser_player_row;
    var winner_score;
    var loser_score;
    var new_winner_row;
    var new_winner_col;

    $.fn.check_int('ES', id);

    if(col < 16)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);

      var next_score = $.fn.get_score_2(id, 1);
      var next_value = parseInt(next_score['value']);

      var next_id = $.fn.get_next_id(id, 1, 'ES');

      var current_name = $.fn.get_player_1(id);
      var next_name = $.fn.get_player_2(id, 1);

      var current_row = parseInt(current_score['row']);
      var current_col = parseInt(current_score['col1']);

      var next_row = next_id['next_row'];
      var next_col = next_id['column'];

      if(!isNaN(next_value))
      {
        if(current_row < next_row)
        {
          var top_row = current_row;
          var bottom_row = next_row;
          var top_name = current_name;
          var bottom_name = next_name;
          var top_score = current_value;
          var bottom_score = next_value;
        }
        else
        {
          var top_row = next_row;
          var bottom_row = current_row;
          var top_name = next_name;
          var bottom_name = current_name;
          var top_score = next_value;
          var bottom_score = current_value;
        }

        if(top_score > bottom_score)
        {
          winner_name = top_name;
          loser_name = bottom_name;
        }
        else
        {
          winner_name = bottom_name;
          loser_name = top_name;
        }

        move_to_row = bottom_row;
        if(col == 14)
        {
          if((top_row == 10) || (top_row == 20) || (top_row == 31) || (top_row == 41) || (top_row == 54) || (top_row == 64) || (top_row == 75) || (top_row == 85))
          {
            move_to_row = top_row;
          }
        }

        move_to_col = parseInt(current_col+1);
        new_winner_row = move_to_row;
        new_winner_col = move_to_col;
        col = current_col;

        var player_1 = top_name;
        var player_2 = bottom_name;
        winner_player_row = top_row;
        loser_player_row = bottom_row;
        winner_score = top_score;
        loser_score = bottom_score;

        $('#' + (move_to_row) + '_' + (parseInt(current_score['col1'])+1) + '_' + (parseInt(current_score['col1'])+1)).val(winner_name);
      }
    }

    if(col == 16)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);

      var next_score = $.fn.get_score_2(id, 4);
      var next_value = parseInt(next_score['value']);

      var next_id = $.fn.get_next_id(id, 4, 'ES');

      var current_name = $.fn.get_player_1(id);
      var next_name = $.fn.get_player_2(id, 4);

      var current_row = parseInt(current_score['row']);
      var current_col = parseInt(current_score['col1']);

      var next_row = next_id['next_row'];
      var next_col = next_id['column'];

      if(!isNaN(next_value))
      {
        if(current_row < next_row)
        {
          var top_row = current_row;
          var bottom_row = next_row;
          var top_name = current_name;
          var bottom_name = next_name;
          var top_score = current_value;
          var bottom_score = next_value;
        }
        else
        {
          var top_row = next_row;
          var bottom_row = current_row;
          var top_name = next_name;
          var bottom_name = current_name;
          var top_score = next_value;
          var bottom_score = current_value;
        }

        if(top_score > bottom_score)
        {
          winner_name = top_name;
          loser_name = bottom_name;
        }
        else
        {
          winner_name = bottom_name;
          loser_name = top_name;
        }

        move_to_row = (top_row + 2);

        move_to_col = parseInt(current_col+1);
        new_winner_row = move_to_row;
        new_winner_col = move_to_col;
        col = current_col;

        var player_1 = top_name;
        var player_2 = bottom_name;
        winner_player_row = top_row;
        loser_player_row = bottom_row;
        winner_score = top_score;
        loser_score = bottom_score;

        $('#' + (move_to_row) + '_' + (parseInt(current_score['col1'])+1) + '_' + (parseInt(current_score['col1'])+1)).val(winner_name);
      }
    }

    if(col == 18)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);

      var next_score = $.fn.get_score_2(id, 10);
      var next_value = parseInt(next_score['value']);

      var next_id = $.fn.get_next_id(id, 10, 'ES');

      var current_name = $.fn.get_player_1(id);
      var next_name = $.fn.get_player_2(id, 10);

      var current_row = parseInt(current_score['row']);
      var current_col = parseInt(current_score['col1']);

      var next_row = next_id['next_row'];
      var next_col = next_id['column'];

      if(!isNaN(next_value))
      {
        if(current_row < next_row)
        {
          var top_row = current_row;
          var bottom_row = next_row;
          var top_name = current_name;
          var bottom_name = next_name;
          var top_score = current_value;
          var bottom_score = next_value;
        }
        else
        {
          var top_row = next_row;
          var bottom_row = current_row;
          var top_name = next_name;
          var bottom_name = current_name;
          var top_score = next_value;
          var bottom_score = current_value;
        }

        if(top_score > bottom_score)
        {
          winner_name = top_name;
          loser_name = bottom_name;
        }
        else
        {
          winner_name = bottom_name;
          loser_name = top_name;
        }

        if((top_row == 8) || (top_row == 18))
        {
          move_to_row = 12;
        }
        if((top_row == 29) || (top_row == 39))
        {
          move_to_row = 33;
        }
        if((top_row == 52) || (top_row == 62))
        {
          move_to_row = 56;
        }
        if((top_row == 73) || (top_row == 83))
        {
          move_to_row = 77;
        }

        move_to_col = parseInt(current_col+1);
        new_winner_row = move_to_row;
        new_winner_col = move_to_col;
        col = current_col;

        var player_1 = top_name;
        var player_2 = bottom_name;
        winner_player_row = top_row;
        loser_player_row = bottom_row;
        winner_score = top_score;
        loser_score = bottom_score;

        $('#' + (move_to_row) + '_' + (parseInt(current_score['col1'])+1) + '_' + (parseInt(current_score['col1'])+1)).val(winner_name);
      }
    }

    if(col == 20)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);

      var next_score = $.fn.get_score_2(id, 21);
      var next_value = parseInt(next_score['value']);

      var next_id = $.fn.get_next_id(id, 21, 'ES');

      var current_name = $.fn.get_player_1(id);
      var next_name = $.fn.get_player_2(id, 21);

      var current_row = parseInt(current_score['row']);
      var current_col = parseInt(current_score['col1']);

      var next_row = next_id['next_row'];
      var next_col = next_id['column'];

      if(!isNaN(next_value))
      {
        if(current_row < next_row)
        {
          var top_row = current_row;
          var bottom_row = next_row;
          var top_name = current_name;
          var bottom_name = next_name;
          var top_score = current_value;
          var bottom_score = next_value;
        }
        else
        {
          var top_row = next_row;
          var bottom_row = current_row;
          var top_name = next_name;
          var bottom_name = current_name;
          var top_score = next_value;
          var bottom_score = current_value;
        }

        if(top_score > bottom_score)
        {
          winner_name = top_name;
          loser_name = bottom_name;
        }
        else
        {
          winner_name = bottom_name;
          loser_name = top_name;
        }

        if((top_row == 12) || (top_row == 33))
        {
          move_to_row = 24;
        }
        if((top_row == 56) || (top_row == 77))
        {
          move_to_row = 68;
        }

        move_to_col = parseInt(current_col+1);
        new_winner_row = move_to_row;
        new_winner_col = move_to_col;
        col = current_col;

        var player_1 = top_name;
        var player_2 = bottom_name;
        winner_player_row = top_row;
        loser_player_row = bottom_row;
        winner_score = top_score;
        loser_score = bottom_score;

        $('#' + (move_to_row) + '_' + (parseInt(current_score['col1'])+1) + '_' + (parseInt(current_score['col1'])+1)).val(winner_name);
      }
    }

    if(col == 22)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);

      var next_score = $.fn.get_score_2(id, 44);
      var next_value = parseInt(next_score['value']);

      var next_id = $.fn.get_next_id(id, 44, 'ES');

      var current_name = $.fn.get_player_1(id);
      var next_name = $.fn.get_player_2(id, 44);

      var current_row = parseInt(current_score['row']);
      var current_col = parseInt(current_score['col1']);

      var next_row = next_id['next_row'];
      var next_col = next_id['column'];

      if(!isNaN(next_value))
      {
        if(current_row < next_row)
        {
          var top_row = current_row;
          var bottom_row = next_row;
          var top_name = current_name;
          var bottom_name = next_name;
          var top_score = current_value;
          var bottom_score = next_value;
        }
        else
        {
          var top_row = next_row;
          var bottom_row = current_row;
          var top_name = next_name;
          var bottom_name = current_name;
          var top_score = next_value;
          var bottom_score = current_value;
        }

        if(top_score > bottom_score)
        {
          winner_name = top_name;
          loser_name = bottom_name;
        }
        else
        {
          winner_name = bottom_name;
          loser_name = top_name;
        }

        if((top_row == 24) || (top_row == 68))
        {
          move_to_row = 44;
        }
      
        move_to_col = parseInt(current_col+1);
        new_winner_row = move_to_row;
        new_winner_col = move_to_col;
        col = current_col;

        var player_1 = top_name;
        var player_2 = bottom_name;
        winner_player_row = top_row;
        loser_player_row = bottom_row;
        winner_score = top_score;
        loser_score = bottom_score;

        $('#' + (move_to_row) + '_' + (parseInt(current_score['col1'])+1) + '_' + (parseInt(current_score['col1'])+1)).val(winner_name);
      }
    }
    if(!isNaN(current_value) && !isNaN(next_value)) 
    {
      $.ajax({
        url:"save_match_score.php?tourn_id=" + tourn_id + "&player_1=" + player_1 + "&player_2=" + player_2 + "&winner_row_no=" + winner_player_row + "&opp_row_no=" + loser_player_row + "&winner_score=" + winner_score + "&opp_score=" + loser_score + "&common_col=" + (col) + "&winner_name=" + winner_name + "&move_to_row=" + new_winner_row + "&move_to_col=" + new_winner_col,
        method: 'GET',
        success:function(response)
        {
          console.log(response);
          location.reload(true);
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

if(/iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent) || screen.availWidth < 480)
{
    if(tourn_size == 64)
    {
      //setupColumnNavigation();
      // re-run the column update once more shortly after population likely finishes
      setTimeout(function(){
        // safe re-run: re-collect groups and re-apply update
        if (typeof setupColumnNavigation === 'function') {
          setupColumnNavigation(); // re-init (it .off() handlers)
        }
      }, 300); // adjust 200-600ms based on how long populate usually takes
    }
}
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
        if($sort_order === 'seed')
        {
          echo("<td align='center'><h4>Seed</h4></td>");
        }
        else if($sort_order === 'rank')
        {
          echo("<td align='center'><h4>Ranking</h4></td>");
        }
        echo("<td align='center'><h4>Score</h4></td>");
        echo("</tr>");
        $i = 1;
        $ranked = 0;
        mysqli_data_seek($result_players, 0);
        while($build_players = $result_players->fetch_assoc())
        {
          echo('<input type="hidden" id="member_id" value=' . $build_players['tourn_memb_id'] . '>');
          echo("<tr>");
          echo("<td align='center'>" . $i . "</td>");
          echo("<td nowrap>" . $build_players['FirstName'] . " " . $build_players['LastName'] . "</td>");
          if($sort_order == 'seed')
          {
            if($build_players['seed'] === '')
            {
              $ranked = 0;
            }
            else
            {
              $ranked = $build_players['seed'];
            }
          }
          else if($sort_order == 'rank')
          {
            if($build_players['ranknum'] === '')
            {
              $ranked = 0;
            }
            else
            {
              $ranked = $build_players['ranknum'];
            }
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
          <table style='width:300px;' border=0>
          <tr>
            <td colspan='6'>&nbsp;</td>
          </tr>
          <tr>
            <td align='center' colspan='6'><h4>Select 'Seeding' or 'Ranking' Order</h4></td>
          </tr>
          <tr>
            <td colspan='2'>&nbsp;</td>
            <td align='center'>Seed</td>
            <td align='center'>Rank</td>
            <td colspan='2'>&nbsp;</td>
          </tr>
          <tr>
            <td colspan='2'>&nbsp;</td>
            <td align='center'><input type='radio' id='seed' name='sort' value='seed'></td>
            <td align='center'><input type='radio' id='rank' name='sort' value='rank'></td>
            <td colspan='2'>&nbsp;</td>
          </tr>
          <tr>
            <td colspan='6'>&nbsp;</td>
          </tr>
        </table>
        <table style='width:300px;' border=0>
          <tr>
            <td colspan='6'>&nbsp;</td>
          </tr>
          <tr>
            <td align='center' colspan='6'><h4>Select number of 'Best of' matches</h4></td>
          </tr>
          <tr>
            <td align='center'>&nbsp;</td>
            <td colspan='5' align='center'>Best of <?= $no_of_rounds ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align='center'>3</td>
            <td align='center'>5</td>
            <td align='center'>7</td>
            <td align='center'>9</td>
            <td align='center'>11</td>
          </tr>
          <tr>
          <?php
          for($i = 0; $i < $no_of_rounds; $i++)
          {
            if($no_of_rounds == 7)
            {
              //echo("Round " . ($i+1) . "<br>");
              if($i < 4)
              {
                echo("<td align='center'>Round " . ($i+1) . "</td>");
              }
              else if($i === 6)
              {
                echo("<td align='center'>Finals</td>");
              }
              if($i === 5)
              {
                echo("<td align='center'>Semi Finals</td>");
              }
              if($i === 4)
              {
                echo("<td align='center'>Quarter Finals</td>");
              }
            }
            else if($no_of_rounds == 10)
            {
              //echo("Round " . ($i+1) . "<br>");
              if($i < 7)
              {
                echo("<td align='center'>Round " . ($i+1) . "</td>");
              }
              else if($i === 9)
              {
                echo("<td align='center'>Finals</td>");
              }
              if($i === 8)
              {
                echo("<td align='center'>Semi Finals</td>");
              }
              if($i === 7)
              {
                echo("<td align='center'>Quarter Finals</td>");
              }
            }
            for($x = 0; $x < 5; $x++)
            {
              echo("<td align='center'><input type='radio' id='best_of_r" . $x . "_c" . $i . "' name='best_of_" . $i . "' value='" . $x . "'></td>");
            }
            echo("</tr>
            <tr><td colspan='8'>&nbsp;</td></tr>");
          }
          ?>
        </table>
        <table style='width:300px;'>
          <tr>
            <td colspan='8'>&nbsp;</td>
          </tr>
          <tr>
            <td align='center' colspan='8'><h4>Select default start day</h4></td>
          </tr>
          <tr>
            <td colspan='8'>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align='center'>M</td>
            <td align='center'>T</td>
            <td align='center'>W</td>
            <td align='center'>T</td>
            <td align='center'>F</td>
            <td align='center'>S</td>
            <td align='center'>S</td>
          </tr>
          <tr>
          <?php
          for($i = 0; $i < $no_of_rounds; $i++)
          {
            if($no_of_rounds == 7)
            {
              //echo("Round " . ($i+1) . "<br>");
              if($i < 4)
              {
                echo("<td align='center'>Round " . ($i+1) . "</td>");
              }
              else if($i === 6)
              {
                echo("<td align='center'>Finals</td>");
              }
              if($i === 5)
              {
                echo("<td align='center'>Semi Finals</td>");
              }
              if($i === 4)
              {
                echo("<td align='center'>Quarter Finals</td>");
              }
            }
            else if($no_of_rounds == 10)
            {
              //echo("Round " . ($i+1) . "<br>");
              if($i < 7)
              {
                echo("<td align='center'>Round " . ($i+1) . "</td>");
              }
              else if($i === 9)
              {
                echo("<td align='center'>Finals</td>");
              }
              if($i === 8)
              {
                echo("<td align='center'>Semi Finals</td>");
              }
              if($i === 7)
              {
                echo("<td align='center'>Quarter Finals</td>");
              }
            }

            for($x = 0; $x < 7; $x++) // number of days
            {
              echo("<td align='center'><input type='radio' id='start_day_r" . $x . "_c" . $i . "' name='days_" . $i . "' value='" . $x . "'></td>");
            }
            echo("</tr>
            <tr><td colspan='8'>&nbsp;</td></tr>");
          }
          ?>
        </table>
        <table style='width:300px;'>
          <tr>
            <td colspan='8'>&nbsp;</td>
          </tr>
          <tr>
            <td align='center' colspan='8'><h4>Select default start time</h4></td>
          </tr>
          <tr>
            <td colspan='8'>&nbsp;</td>
          </tr>
          <tr>
          <?php
          for($i = 0; $i < $no_of_rounds; $i++)
          {
            if($no_of_rounds == 7)
            {
              if($i < 4)
              {
                echo("<td align='center'>Round " . ($i+1) . "</td>");
              }
              else if($i === 6)
              {
                echo("<td align='center'>Finals</td>");
              }
              if($i === 5)
              {
                echo("<td align='center'>Semi Finals</td>");
              }
              if($i === 4)
              {
                echo("<td align='center'>Quarter Finals</td>");
              }
            }
            else if($no_of_rounds == 10)
            {
              //echo("Round " . ($i+1) . "<br>");
              if($i < 7)
              {
                echo("<td align='center'>Round " . ($i+1) . "</td>");
              }
              else if($i === 9)
              {
                echo("<td align='center'>Finals</td>");
              }
              if($i === 8)
              {
                echo("<td align='center'>Semi Finals</td>");
              }
              if($i === 7)
              {
                echo("<td align='center'>Quarter Finals</td>");
              }
            }
            echo("<td align='center'><input type='text' class='time_settings' id='start_time_" . $i . "' value='' size='5' /></td>");
            echo("</tr>");
            echo("<tr><td colspan='8'>&nbsp;</td></tr>");
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
  <div id='modal_size' class="modal-dialog" role="document">
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
            <td align='center'><font color="red"><h5>Breaks should be separated by a space.</h5></font></td>
          </tr>
          <tr>
            <td align='center'>
              <div id= 'scorestable_1'></div>
              <table>
                <tr>
                  <th align='center'>&nbsp;</th>
                </tr>
                <tr>
                  <th align='center'>&nbsp;</th>
                </tr>
              </table>
              <div id= 'scorestable_2'></div>
              </td>
            </tr>
          </td>
          </table>
          <table class='table table-striped table-bordered'>
            <tr>
                <td colspan='8' align='center'><b>To be entered by the Referee.</b></td>
            </tr>
            <tr>
              <td align='center'>Table</td>
              <td align='center'>Round</td>
              <td colspan='2' align='center'>Match No.</td>
              <td colspan='2' align='center'>Start</td>
              <td colspan='2' align='center'>Finish</td>
            </tr>
            <tr>
              <td align='center'><input type='text' id='table_no' value=0 style='text-align: center; width:30px; height:20px'></td>
              <td align='center' id='round'></td> 
              <td colspan='2' align='center' id='match_no'></td>
              <td colspan='2' align='center'><input type='text' class='timepicker' id='start' value='' style='width:50px; height:20px;'></td>
              <td colspan='2' align='center'><input type='text' class='timepicker' id='finish' value='' style='width:50px; height:20px'></td>
            </tr>
            <tr>
              <td colspan='4' align='center' style='width:100px; height:20px'><font color="red">*</font> Referee - select this OR Roving Referee</td>
              <td colspan='4' align='center'><select id="referee" style='width:160px; height:20px'>
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
              <td colspan='4' align='center'><select id="marker" style='width:160px; height:20px'>
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
          </table>
        <div><a class='btn btn-primary btn-xs' id='interim_save_modal_button'>Interim Save</a>
        <a class='btn btn-primary btn-xs' id='finished_save_modal_button'>Match Complete</a></div>
        </center>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close (without saving)</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() 
{
  $('#referee').on('change', function()
  {
    if($(this).val() == '')
    {
      $('#roving').prop('disabled', false);
    }
    else
    {
      $('#roving').prop('disabled', true);
    }
  });

  $('#roving').change(function()
  {
    if($("#roving").is(":checked"))
    {
      $('#referee').prop('disabled', true);
    }
    else
    {
      $('#referee').prop('disabled', false);
    }
  });
});
</script>  
</center>
</form>
</body>
</html>



