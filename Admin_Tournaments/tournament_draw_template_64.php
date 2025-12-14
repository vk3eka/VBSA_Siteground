<!-- jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- html2canvas library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js"></script>
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

$tourn_id = 202473;

// get list of players from tournaments table
$sql_players = "Select * FROM tourn_entry Left join members on members.memberID = tourn_entry.tourn_memb_id where tournament_number = '$tourn_id'";
//echo($sql_players . "<br>");
$result_players = mysql_query($sql_players, $connvbsa) or die(mysql_error());
$no_of_players = $result_players->num_rows;

if($no_of_players < 64)
{
  $tourn_size = 64;
}
if(($no_of_players > 64) && ($no_of_players < 128))
{
  $tourn_size = 128;
}

$tourn_size = 64;

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
?>

<form name='tournament_draw_template' id='tournament_draw_template' method="post" action='tournament_draw_template.php'>
<!--<div class="container">-->
<center>
<br>


<div align='center'>
  <input type="button" value="PDF of the draw." onclick="generatePDF()">
</div>

<!--<div align="center" class="greenbg"><button id="show_players" style="width: 300px;">Show all players for this tournament</button></div>-->
<br>

<script>
$(document).ready(function()
{
  $('#show_players').click(function(e)
  {
    e.preventDefault();
    $('#list_players_modal').modal('show');
  });

});

</script>
<style>
td {
  height: 10px; /* Set the desired height for table rows */
  font-size: 8px;
}

</style>
<div id='FixtureTable'>
  <table align="center" cellpadding="0" cellspacing="0" width='1000'>
  <tr>
    <td align="center"><h4>Tournament Draw Creation Template</h4></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<table border="1" cellpadding="0" cellspacing="0" width="1000" valign='top'>
 <tr>
  <td>&nbsp;</td>
  <?php
  if($tourn_size > 64)
  {
    ?>
    <td rowspan="2">Day</td>
    <td rowspan="2">Time</td>
    <td id='title_1'>Round of 128</td>
    <td>&nbsp;</td>
    <td rowspan="2">Day</td>
    <td rowspan="2">Time</td>
    <td id='title_2'>Round of 128</td>
    <td>&nbsp;</td>
    <td rowspan="2">Day</td>
    <td rowspan="2">Time</td>
    <td id='title_3'>Round of 128</td>
    <td>&nbsp;</td>
    <td rowspan="2">Day</td>
    <td rowspan="2">Time</td>
    <td id='title_4'>Round of 64</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td rowspan="2">Day</td>
  <td rowspan="2">Time</td>
  <td id='title_5'>Round of 64</td>
  <td>&nbsp;</td>
  <td rowspan="2">Day</td>
  <td rowspan="2">Time</td>
  <td id='title_6'>Round of 32</td>
  <td>&nbsp;</td>
  <td rowspan="2">Day</td>
  <td rowspan="2">Time</td>
  <td id='title_7'>Round of 16</td>
  <td>&nbsp;</td>
  <td id='title_8'>Round of 8</td>
  <td>&nbsp;</td>
  <td id='title_9'>Qtr Final</td>
  <td>&nbsp;</td>
  <td id='title_10'>Semi Final</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Score</td>
    <td>&nbsp;</td>
    <td>Score</td>
    <td>&nbsp;</td>
    <td>Score</td>
    <td>&nbsp;</td>
    <td>Score</td>
    <td>&nbsp;</td>
    <td>Score</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>Score</td>
  <td id='best_1'>Best of 5 frames</td>
  <td>Score</td>
  <td id='best_2'>Best of 5 frames</td>
  <td>Score</td>
  <td id='best_3'>Best of 5 frames</td>
  <td>Score</td>
  <td id='best_4'>Best of 5 frames</td>
  
 </tr>
 <tr>
  <td rowspan="23" height="391" style="writing-mode: vertical-lr;text-orientation: sideways;text-align: center;">Top Half<span>&nbsp;</span></td>
  <?php
  if($tourn_size > 64)
  {
  ?>
    <td id='day_1_1'>&nbsp;</td>
    <td id='time_1_1'>&nbsp;</td>
    <td id='player_1_1'><input type='text' value='B97'></td>
    <td id='score_1_1'>&nbsp;</td>
    <td id='day_1_2'>&nbsp;</td>
    <td id='time_1_2'>&nbsp;</td>
    <td><input type='text' value='B96'></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value='B65'></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value='B64'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>33</td>
  <td><input type='text' value='B33'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>32</td>
  <td><input type='text' value='B32'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>1</td>
  <td><input type='text' value='B1'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value='B128'></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value=''></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value=''></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>64</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value='B112'></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value='B81'></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value='B80'></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type='text' value='B49'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>6.00</td>
  <td>48</td>
  <td><input type='text' value='B48'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>17</td>
  <td><input type='text' value='B17'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>16</td>
  <td><input type='text' value='B16'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B113'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>49</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B105'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B88'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B73'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B56'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>41</td>
  <td><input type='text' value='B41'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>24</td>
  <td><input type='text' value='B24'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>9</td>
  <td><input type='text' value='B9'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B120'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>56</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B104'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B89'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B72'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B57'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>40</td>
  <td><input type='text' value='B40'></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>25</td>
  <td><input type='text' value='B25'></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>8</td>
  <td><input type='text' value='B9'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B121'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>57</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B124'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>60</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B101'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B92'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B69'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B60'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>37</td>
  <td><input type='text' value='B37'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>28</td>
  <td><input type='text' value='B28'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>5</td>
  <td><input type='text' value='B5'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B117'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>6.00</td>
  <td>53</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B108'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B85'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B76'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B53'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>44</td>
  <td><input type='text' value='B44'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>21</td>
  <td><input type='text' value='B21'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>12</td>
  <td><input type='text' value='B12'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B116'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>6.00</td>
  <td>52</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B109'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B84'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B77'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B52'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>45</td>
  <td><input type='text' value='B45'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>20</td>
  <td><input type='text' value='B20'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>13</td>
  <td><input type='text' value='B13'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>FINAL</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B115'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>61</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>9AM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>11AM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>Best of 7 frames</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B100'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B93'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B68'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B61'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>36</td>
  <td><input type='text' value='B36'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>29</td>
  <td><input type='text' value='B29'></td>
  <td>&nbsp;</td>
  <td>SAT</td>
  <td>4</td>
  <td><input type='text' value='B4'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
 </tr>
 <tr>
  <td rowspan="23" height="391" style="writing-mode: vertical-lr;text-orientation: sideways;text-align: center;" >Bottom Half</td>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B99'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B94'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B67'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B62'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>35</td>
  <td><input type='text' value='B35'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td>30</td>
  <td><input type='text' value='B30'></td>
  <td>&nbsp;</td>
  <td>1.30PM</td>
  <td>3</td>
  <td><input type='text' value='B3'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B126'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>62</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B110'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B83'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B78'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B51'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>6.00</td>
  <td>46</td>
  <td><input type='text' value='B46'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td>19</td>
  <td><input type='text' value='B19'></td>
  <td>&nbsp;</td>
  <td>1.30PM</td>
  <td>14</td>
  <td><input type='text' value='B14'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B115'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>51</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B107'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B86'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B75'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B54'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>6.00</td>
  <td>43</td>
  <td><input type='text' value='B43'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td>22</td>
  <td><input type='text' value='B22'></td>
  <td>&nbsp;</td>
  <td>1.30PM</td>
  <td>11</td>
  <td><input type='text' value='B11'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B118'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>54</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Round 10</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B102'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B91'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B70'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B59'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>38</td>
  <td><input type='text' value='B38'></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td>27</td>
  <td><input type='text' value='B27'></td>
  <td>&nbsp;</td>
  <td>1.30PM</td>
  <td>6</td>
  <td><input type='text' value='B6'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>Best of 5 frames</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B123'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>59</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B122'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>58</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>6.00</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>3.00PM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B103'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B90'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B71'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B58'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>39</td>
  <td><input type='text' value='B39'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>26</td>
  <td><input type='text' value='B26'></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td>7</td>
  <td><input type='text' value='B7'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B119'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>6.00</td>
  <td>55</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>3.00PM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B106'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B87'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B74'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B55'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>42</td>
  <td><input type='text' value='B42'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>23</td>
  <td><input type='text' value='B23'></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td>10</td>
  <td><input type='text' value='B10'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B114'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>6.00</td>
  <td>50</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>3.00PM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B111'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B82'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B79'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B50'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>47</td>
  <td><input type='text' value='B47'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>18</td>
  <td><input type='text' value='B18'></td>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td>15</td>
  <td><input type='text' value='B15'></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B127'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>63</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>8PM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>3.00PM</td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td><input type='text' value=''></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B98'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B95'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>34</td>
  <td><input type='text' value='B66'></td>
  <td>&nbsp;</td>
  <td>Fri</td>
  <td>31</td>
  <td><input type='text' value='B63'></td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>Sat</td>
  <td>2</td>
  <td><input type='text' value='B34'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B31'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><input type='text' value='B2'></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <?php
  if($tourn_size > 64)
  {
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <?php
  }
  ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
 </tr>
</tbody>
</table>
</center>
</div> <!-- end of fixturetable -->
<script>
$(document).ready(function() {

  let rowsPerPage = 20; // show 20 at a time
  let rows = $("#players_table tr"); // includes header row
  let totalRows = rows.length - 1;   // exclude header
  let totalPages = Math.ceil(totalRows / rowsPerPage);
  let currentPage = 1;

  function showPage(page) {
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
  $(document).on("click", "#prevPage", function(e) {
    e.preventDefault();
    showPage(currentPage - 1);
  });

  $(document).on("click", "#nextPage", function(e) {
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
        <input type="hidden" id="member_id" value="<?= $build_players['tourn_memb_id'] ?>">
        <center>
        <table id="players_table" style='width:300px;' border='1'>
        <?php
        echo("<tr>");
        echo("<td align='center'><h4>Index</h4></td>");
        echo("<td align='center'><h4>Players Name</h4></td>");
        echo("<td align='center'><h4>Ranking</h4></td>");
        echo("<td align='center'><h4>Score</h4></td>");
        echo("</tr>");
        $i = 1;
        while($build_players = $result_players->fetch_assoc())
        {
          echo("<tr>");
          echo("<td align='center'>" . $i . "</td>");
          echo("<td nowrap>" . $build_players['FirstName'] . " " . $build_players['LastName'] . "</td>");
          if($build_players['ranked'] == '')
          {
            $ranked = 0;
          }
          else
          {
            $ranked = $build_players['ranked'];
          }
          echo("<td align='center'>" . $ranked . "</td>");
          echo("<td align='center'>" . $build_players['rank_pts'] . "</td>");
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


</form>
</center>
<script type="text/javascript">

window.jsPDF = window.jspdf.jsPDF;

function generatePDF() {
  //alert("Not yet implemented!");
  
    //const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
      orientation: 'landscape'
      //orientation: 'portrait'
    });
    var elementHTML = document.querySelector("#FixtureTable");

    doc.html(elementHTML, {
        callback: function(doc) {
            // Save the PDF
            doc.save('Tournament_<?= $tourn_id ?>.pdf');
        },
        x: 15,
        y: 15,
        width: 170, //target width in the PDF document
        windowWidth: 650 //window width in CSS pixels
    });         
         
}            
</script>   

<!--</div>--><!-- end container -->

</body>
</html>