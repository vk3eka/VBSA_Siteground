<?php require_once('../Connections/connvbsa.php'); 
include ("../vbsa_online_scores/php_functions.php");
error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer,Administrator,Boardmember,Secretary,Scores";
$MM_donotCheckaccess = "false";

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
    if (($strUsers == "") && false) { 
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
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

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

if (isset($_GET['cal_year'])) {
  $cal_year = $_GET['cal_year'];
}
elseif(isset($_POST['CalYear']))
{
  $cal_year = $_POST['CalYear'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_01 = "Select *  FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )='$cal_year' GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_01 = mysql_query($query_Cal_01, $connvbsa) or die(mysql_error());
$row_Cal_01 = mysql_fetch_assoc($Cal_01);
$totalRows_Cal_01 = mysql_num_rows($Cal_01);
/*
mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_01 = "Select *  FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )='$cal_year' and month(startdate) = 1 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_01 = mysql_query($query_Cal_01, $connvbsa) or die(mysql_error());
$row_Cal_01 = mysql_fetch_assoc($Cal_01);
$totalRows_Cal_01 = mysql_num_rows($Cal_01);

$query_Cal_02 = "Select *  FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )='$cal_year' and month(startdate) = 2 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_02 = mysql_query($query_Cal_02, $connvbsa) or die(mysql_error());
$row_Cal_02 = mysql_fetch_assoc($Cal_02);
$totalRows_Cal_02 = mysql_num_rows($Cal_02);

$query_Cal_03 = "Select *  FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )='$cal_year' and month(startdate) = 3 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_03 = mysql_query($query_Cal_03, $connvbsa) or die(mysql_error());
$row_Cal_03 = mysql_fetch_assoc($Cal_03);
$totalRows_Cal_03 = mysql_num_rows($Cal_03);
*/


if ($_POST['ButtonName'] == "SaveChanges") {
  $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
  for ($i = 0; $i < count($packeddata); $i++) 
  {
      $events = explode(", ", $packeddata[$i]);
      $updateSQL = sprintf("Update calendar SET event=%s, venue=%s, startdate=%s, finishdate=%s, entry_close=%s, `state`=%s, visible=%s, aust_rank=%s, ranking_type=%s, tourn=%s, footer1=%s, footer2=%s, footer3=%s, footer4=%s WHERE event_id=%s",
                     GetSQLValueString($events[1], "text"),
                     GetSQLValueString($events[2], "text"),
                     GetSQLValueString($events[3], "text"),
                     GetSQLValueString($events[4], "text"),
                     GetSQLValueString($events[5], "text"),
                     GetSQLValueString($events[6], "date"),
                     GetSQLValueString($events[7], "date"),
                     GetSQLValueString($events[8], "date"),
                     GetSQLValueString($events[9], "text"),
                     GetSQLValueString($events[10], "text"),
                     GetSQLValueString($events[11], "text"),
                     GetSQLValueString($events[12], "text"),
                     GetSQLValueString($events[13], "text"),
                     GetSQLValueString($events[14], "text"),
                     GetSQLValueString($events[0], "int"));
    mysql_select_db($database_connvbsa, $connvbsa);
    $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  }
  header("Location: calendar_list_edit.php?cal_year=" . date("Y")); 
}


if ($_POST['ButtonName'] == "Delete")
{
  $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
  
  foreach($packeddata as $item => $event_id) {
    $updateSQL = "Delete From calendar where event_id  = " . $event_id;   
    mysql_select_db($database_connvbsa, $connvbsa);
    $result = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  }
  header("Location: calendar_list_edit.php?cal_year=" . date("Y"));
}

if ($_POST['ButtonName'] == "CopySelected")
{
  $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
  $keys = array_keys($packeddata);
 
  for ($i = 0; $i < count($keys); $i++) 
  {
    $events = explode(", ", $packeddata[$keys[$i]]);
    $insert_tourn_SQL = sprintf("Insert INTO tournaments (tourn_name, site_visible, tourn_year, tourn_type, tourn_class, ranking_type, how_seed, status) 
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
       GetSQLValueString($events[1], "text"),
       GetSQLValueString($events[2], "text"),
       date("Y"),
       GetSQLValueString($events[3], "text"),
       GetSQLValueString($events[4], "text"),
       GetSQLValueString($events[5], "text"),
       GetSQLValueString($events[6], "text"),
       GetSQLValueString($events[7], "text"));
    mysql_select_db($database_connvbsa, $connvbsa);
    $result = mysql_query($insert_tourn_SQL, $connvbsa) or die(mysql_error());
  }
  header("Location: calendar_list_edit.php?cal_year=" . date("Y"));
}

?>

<script>

function SaveSelectedChangesButton(no_of_events) 
{
    var transferdata = {};
    for (var i = 0; i < no_of_events; i++) // get number of events
    {
        if(document.getElementById("vbsa_event_" + i).checked == true)
        {
          vbsa_event = 'Y';
        }
        else
        {
          vbsa_event = 'N';
        }
        if(document.getElementById("vbsa_entries_" + i).checked == true)
        {
          vbsa_entries = 'Y';
        }
        else
        {
          vbsa_entries = 'N';
        }
        if(document.getElementById("non_vbsa_event_" + i).checked == true)
        {
          non_vbsa_event = 'Y';
        }
        else
        {
          non_vbsa_event = 'N';
        }
        if(document.getElementById("non_vbsa_entries_" + i).checked == true)
        {
          non_vbsa_entries = 'Y';
        }
        else
        {
          non_vbsa_entries = 'N';
        }
        transferdata[i] = document.getElementById("event_id_" + i).value + ", " +
                          document.getElementById("event_" + i).value + ", " +
                          document.getElementById("venue_" + i).value + ", " +
                          document.getElementById("startdate_" + i).value + ", " +
                          document.getElementById("finishdate_" + i).value + ", " +
                          document.getElementById("entry_close_" + i).value + ", " +
                          document.getElementById("state_" + i).value + ", " +
                          document.getElementById("visible_" + i).value + ", " +
                          document.getElementById("aust_rank_" + i).value + ", " +
                          document.getElementById("ranking_type_" + i).value + ", " +
                          document.getElementById("tourn_" + i).value + ", " +
                          vbsa_event + ", " +
                          vbsa_entries + ", " +
                          non_vbsa_event + ", " +
                          non_vbsa_entries;
    }
    var data = JSON.stringify(transferdata);
    document.save_edits.Events.value = no_of_events;
    document.save_edits.PackedData.value = data;  
    document.save_edits.ButtonName.value = "SaveChanges"; 
    document.save_edits.submit();
}


function DeleteSelectedButton(no_of_events)  
{
  var transferdata = {};
  for(var i = 0; i < no_of_events; i++) // get number of events
  {
    if(document.getElementById("delete_" + i).checked === true)
    {
      transferdata[i] = document.getElementById("event_id_" + i).value;
    }
  }
  var data = JSON.stringify(transferdata);
  document.save_edits.Events.value = no_of_events;
  document.save_edits.PackedData.value = data;  
  document.save_edits.ButtonName.value = "Delete"; 
  document.save_edits.submit();
}

function CopySelectedButton(no_of_events)  
{
  var transferdata = {};
  for(var i = 0; i < no_of_events; i++) // get number of events
  {
    if(document.getElementById("copy_to_events_" + i).checked === true)
    {    
      transferdata[i] = document.getElementById("event_id_" + i).value + ", " +
                        document.getElementById("event_" + i).value + ", " +
                        document.getElementById("visible_" + i).value + ", " +
                        document.getElementById("tourn_type_" + i).value + ", " +
                        document.getElementById("tourn_class_" + i).value + ", " +
                        document.getElementById("ranking_class_" + i).value + ", " +
                        document.getElementById("how_seed_" + i).value + ", Open";
    }
  }
  var data = JSON.stringify(transferdata);
  document.save_edits.Events.value = no_of_events;
  document.save_edits.PackedData.value = data;  
  document.save_edits.ButtonName.value = "CopySelected"; 
  document.save_edits.submit();
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="calendar/codebase/dhtmlxcalendar.css">
<link rel="stylesheet" type="text/css" href="calendar/codebase/skins/dhtmlxcalendar_dhx_skyblue.css">

<script src="calendar/codebase/dhtmlxcommon.js"></script>
<script src="calendar/codebase/dhtmlxcalendar.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type='text/javascript'>

window.onload = function() {
  doOnLoad();
}

function doOnLoad() {
  for(i = 0; i < <?= $totalRows_Cal_01 ?>; i++)
  {
    var mystartdate;  
    mystartdate = new dhtmlXCalendarObject("startdate_" + i);
    mystartdate.setSkin('dhx_skyblue');
    mystartdate.hideTime();
    mystartdate.hideWeekNumbers();
    mystartdate.setDateFormat("%Y-%m-%d");

    var myfinishdate;  
    myfinishdate = new dhtmlXCalendarObject("finishdate_" + i);
    myfinishdate.setSkin('dhx_skyblue');
    myfinishdate.hideTime();
    myfinishdate.hideWeekNumbers();
    myfinishdate.setDateFormat("%Y-%m-%d");

    var myentrydate;  
    myentrydate = new dhtmlXCalendarObject("entry_close_" + i);
    myentrydate.setSkin('dhx_skyblue');
    myentrydate.hideTime();
    myentrydate.hideWeekNumbers();
    myentrydate.setDateFormat("%Y-%m-%d");
  }
}

</script>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5" class="greenbg">
  <tr>
    <td><a href="calendar_list.php?cal_year=<?php echo date("Y") ?>" title="View, Insert and edit the current calendar">Calendar for the current year</a></td>
    <td><a href="calendar_list.php?cal_year=<?php echo date("Y")+1 ?>" title="View, Insert and edit calendar for next year">Calendar for next year</a></td>
    <td><a href="calendar_event_xx_archive.php" title="No Start Date, Start Date is out of date or Visible is set to No">Archives</a></a></td>
    <td align="right" class="greenbg"><a href="calendar_event_previous.php">Insert a new event</a></td>
    <td><a href="../Admin_web_pages/aa_webpage_index.php">Webpage Menu</a></td>
    <td><a href="../Admin_DB_VBSA/vbsa_login_success.php">Admin Menu</a></td>
  </tr>
</table>
<p>&nbsp;</p>
<form action="calendar_list_edit.php?cal_year=<?php echo date("Y") ?>" method="post" name="save_edits" id="save_edits">
<input type='hidden' id='Events' name="Events">
<input type='hidden' id='PackedData' name="PackedData">
<input type='hidden' id='ButtonName' name="ButtonName">
<table align="center" class="red_text"  width="80%">
  <tr>
    <td align="center"><h2>Legend for Column Headings</h2></td>
  </tr>
  <tr>
    <td align="left"><b>Show on website</b> - will be seen on the public website</td>
  </tr>
  <tr>
    <td align="left"><b>Footers:- </b></td>
  </tr>
  <tr>
    <td align="left">&nbsp;&nbsp;<b>VBSA Event</b> - To enter this event, pay your membership or make a payment to   the VBSA please go to the payments page. Enquiries - <a href="mailto:treasurer@vbsa.org.au">VBSA treasurer </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;&nbsp;<b>VBSA Entries</b> - To enter this event, pay your membership or make a payment to   the VBSA please go to the payments page. Enquiries - <a href="mailto:treasurer@vbsa.org.au">VBSA treasurer </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;&nbsp;<b>Non VBSA Event</b> - To check the VBSA have received your entry please go to <a href="http://www.vbsa.org.au/Tournaments/tournindex.php">&quot;VBSA Tournament entries&quot;</a> . Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament. Please Note: The VBSA do not accept entries for this event, please refer the entry form fo details on how to enter</td>
  </tr>
  <tr>
    <td align="left">&nbsp;&nbsp;<b>Non VBSA Entries</b> - Please go to the <a href="http://absc.com.au/results.aspx">ABSC Site</a> for results</td>
    </td>
  </tr>
  <tr>
     <td align="left"><b>Tournament Ranking Class</b></td>
   </tr>
   <tr>
    <td align="left">&nbsp;&nbsp;<b>National</b> for tournaments that DO attract National Ranking points.<br />
          &nbsp;&nbsp;<b>Victorian</b> for tournaments that DO attract Victorian Ranking points.<br />
          &nbsp;&nbsp;<b>Womens</b> for tournaments that DO attract Victorian WOMENS Ranking points.<br />
          &nbsp;&nbsp;<b>Junior</b> for tournaments that attract Victorian JUNIOR Ranking points.<br /></td>
  </tr>
  <tr>
     <td align="left"><b>Attract Vic Ranking</b></td>
   </tr>
   <tr>
    <td align="left">&nbsp;&nbsp;<b>No Entry</b> for tournaments that do not attract Victorian Ranking points. <br />
            &nbsp;&nbsp;<b>Vic Rank</b> for tournaments that DO attract Victorian Ranking points. <br />
            &nbsp;&nbsp;<b>Junior Rank</b> for tournaments that attract Victorian JUNIOR Ranking points. <br />
            &nbsp;&nbsp;<b>Womens Rank</b> for tournaments that DO attract Victorian WOMENS Ranking points. </td>
  </tr>
</table>
<br>
<!--

Check any of these to add to the Footer

To enter this event, pay your membership or make a payment to   the VBSA please go to the payments page. Enquiries - <a href="mailto:treasurer@vbsa.org.au">VBSA treasurer 

To check the VBSA have received your entry please go to <a href="http://www.vbsa.org.au/Tournaments/tournindex.php">&quot;VBSA Tournament entries&quot;</a> . Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament.

Please Note: The VBSA do not accept entries for this event, please refer the entry form fo details on how to enter

Please go to the <a href="http://absc.com.au/results.aspx">ABSC Site</a> for results


"National" for tournaments that DO attract National Ranking points. <br />
"Victorian" for tournaments that DO attract Victorian Ranking points. <br />
"Womens" for tournaments that DO attract Victorian WOMENS Ranking points. <br />
"Junior" for tournaments that attract Victorian JUNIOR Ranking points. <br />

Is this event a tournament?

Copy this calendar entry to the list of tournaments for this year?

Does this tournament attract ranking points in Victoria?
"No Entry" for tournaments that do not attract Victorian Ranking points. <br />
            "Vic Rank" for tournaments that DO attract Victorian Ranking points. <br />
            "Junior Rank" for tournaments that attract Victorian JUNIOR Ranking points. <br />
            "Womens Rank" for tournaments that DO attract Victorian WOMENS Ranking points. 

Please Select a Class for this tournament

How is this tournament seeded?

Visible - will be seen on the website
If a "Start Date" is not set, or, "Visible is set to "No" then event will not appear in the calendar

-->
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan=20 align="center"><button style='width:250px' onclick="SaveSelectedChangesButton(<?= $no_of_events ?>)">Save All Edits</button></td>
  
    <td colspan=20 align="center"><button style='width:250px' onclick="CopySelectedButton(<?= $no_of_events ?>)">Copy Selected to Events</button></td>
  
    <td colspan=20 align="center"><button style='width:250px' onclick="DeleteSelectedButton(<?= $no_of_events ?>)">Delete Selected from List</button></td>
  </tr>
  <tr>
    <td colspan=20>&nbsp;</td>
  </tr>
</table>

<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
  <?php if(isset($row_Cal_01['event_id'])) { ?>
  <tr>
    <th colspan=14  style="background-color: #CCC">Editable Calendar Entries</th>
    <th colspan=4  style="background-color: #CCC">Used only if copying to events</th>
    <th colspan=2  style="background-color: #CCC">&nbsp;</th>
  </tr>
  <tr>
    <input type='hidden' id='event_id' value='<?php echo $row_Cal_01['event_id']; ?>'>
    <th rowspan=2  style="background-color: #CCC">Event</th>
    <th rowspan=2  style="background-color: #CCC">Venue</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Start Date</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Finish Date</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Entries Close </th>
    <th rowspan=2  style="background-color: #CCC" align="center">State</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Show on Website</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Aust Ranking Event</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Attract Vic Ranking</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Tournament</th>
    <th colspan=4  style="background-color: #CCC" align="center">Footers</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Tournament Type</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Tournament Class</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Ranking Class</th>
    <th rowspan=2  style="background-color: #CCC" align="center">How Seeded</th>
    <th colspan=2  style="background-color: #CCC" align="center">Action</th>
  </tr>
  <tr>
    <th align="center" style="background-color: #CCC">VBSA Event</th>
    <th align="center" style="background-color: #CCC">VBSA Entries</th>
    <th align="center" style="background-color: #CCC">Non VBSA Event</th>
    <th align="center" style="background-color: #CCC">Non VBSA Entries</th>
    <th align="center" style="background-color: #CCC">Copy to Events</th>
    <th align="center" style="background-color: #CCC">Delete from List</th>
  </tr>
  <?php 
  $i = 0;
  do { 
    if(date("m", strtotime($row_Cal_01['startdate'])) % 2)
    {
      $month_colour = 'light grey';
    }
    else
    {
      $month_colour = 'grey';
    }
  ?>
  <tr>
    <input type='hidden' id='event_id_<?= $i ?>' value='<?php echo $row_Cal_01['event_id']; ?>'>
    <td align="center" style='background-color: <?= $month_colour ?>'><input type='text' id='event_<?= $i ?>' value='<?php echo $row_Cal_01['event']; ?>'></td>
    <td align="center" style='background-color: <?= $month_colour ?>'><input type='text' id='venue_<?= $i ?>' value='<?php echo $row_Cal_01['venue']; ?>'></td>
    <td align="center" style='background-color: <?= $month_colour ?>'><input name="startdate_<?= $i ?>" type="text" id="startdate_<?= $i ?>" value="<?php echo $row_Cal_01['startdate']; ?>" style="width : 100px;">
    </td>
    <td align="center" style='background-color: <?= $month_colour ?>'><input name="finishdate_<?= $i ?>" type="text" id="finishdate_<?= $i ?>" value="<?php echo ($row_Cal_01['finishdate']); ?>" style="width : 100px;">
    </td>
    <td align="center" style='background-color: <?= $month_colour ?>'><input name="entry_close_<?= $i ?>" type="text" id="entry_close_<?= $i ?>" value="<?php echo ($row_Cal_01['entry_close']); ?>" style="width : 100px;">
    </td>
    <td align="center" style='background-color: <?= $month_colour ?>'><select name="state_<?= $i ?>" id="state_<?= $i ?>">
      <?php
      if(isset($row_Cal_01['state']))
      {
        echo("<option value='" . $row_Cal_01['state'] . "' selected>" . $row_Cal_01['state'] . "</option>");
      }
      ?>
        <option value="ACT">ACT</option>
        <option value="NSW">NSW</option>
        <option value="NT">NT</option>
        <option value="Qld">Qld</option>
        <option value="SA">SA</option>
        <option value="Tas">Tas</option>
        <option value="Vic">Vic</option>
        <option value="WA">WA</option>
      </select></td>
      <td align="center" style='background-color: <?= $month_colour ?>'><select name="visible_<?= $i ?>" id="visible_<?= $i ?>">
      <?php
      if(isset($row_Cal_01['visible']))
      {
        echo("<option value='" . $row_Cal_01['visible'] . "' selected>" . $row_Cal_01['visible'] . "</option>");
      }
      ?>
        <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
        <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
      </select></td>
    <td align="center" style='background-color: <?= $month_colour ?>'><select name="aust_rank_<?= $i ?>" id="aust_rank_<?= $i ?>">
      <?php
      if(isset($row_Cal_01['aust_rank']))
      {
        echo("<option value='" . $row_Cal_01['aust_rank'] . "' selected>" . $row_Cal_01['aust_rank'] . "</option>");
      }
      ?>
        <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
        <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
      </select></td>
      <td style='background-color: <?= $month_colour ?>'><select name="ranking_type_<?= $i ?>" id="ranking_type_<?= $i ?>">
        <?php
      if(isset($row_Cal_01['ranking_type']))
      {
        echo("<option value='" . $row_Cal_01['ranking_type'] . "' selected>" . $row_Cal_01['ranking_type'] . "</option>");
      }
      ?>
      <option value="None" <?php if (!(strcmp("None", ""))) {echo "SELECTED";} ?>>None</option>
      <option value="No Entry" <?php if (!(strcmp("No Entry", ""))) {echo "SELECTED";} ?>>No Entry</option>
      <option value="Vic Rank" <?php if (!(strcmp("Vic Rank", ""))) {echo "SELECTED";} ?>>Vic Rank</option>
      <option value="Womens Rank" <?php if (!(strcmp("Womens Rank", ""))) {echo "SELECTED";} ?>>Womens Rank</option>
      <option value="Junior Rank" <?php if (!(strcmp("Junior Rank", ""))) {echo "SELECTED";} ?>>Junior Rank</option>
    </select> 
    </td>
    <td align="center" style='background-color: <?= $month_colour ?>'><select name="tourn_<?= $i ?>" id="tourn_<?= $i ?>">
      <?php
      if(isset($row_Cal_01['tourn']))
      {
        echo("<option value='" . $row_Cal_01['tourn'] . "' selected>" . $row_Cal_01['tourn'] . "</option>");
      }
      ?>
        <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
        <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
      </select></td>
    <td align="center" style='background-color: <?= $month_colour ?>'><input type="checkbox" name="vbsa_event_<?= $i ?>"  id="vbsa_event_<?= $i ?>"  <?php if ($row_Cal_01['footer1'] == "Y"){echo "checked=\"checked\"";} ?> /></td>
    <td align="center" style='background-color: <?= $month_colour ?>'><input type="checkbox" name="vbsa_entries_<?= $i ?>"  id="vbsa_entries_<?= $i ?>"  <?php if ($row_Cal_01['footer2'] == "Y") {echo "checked=\"checked\"";} ?> /></td>
    <td align="center" style='background-color: <?= $month_colour ?>'><input type="checkbox" name="non_vbsa_event_<?= $i ?>"  id="non_vbsa_event_<?= $i ?>"  <?php if ($row_Cal_01['footer3'] == "Y") {echo "checked=\"checked\"";} ?> /></td>
    <td align="center" style='background-color: <?= $month_colour ?>'><input type="checkbox" name="non_vbsa_entries_<?= $i ?>"  id="non_vbsa_entries_<?= $i ?>"  <?php if ($row_Cal_01['footer4'] == "Y") {echo "checked=\"checked\"";} ?> /></td>

    <td align="center" style='background-color: <?= $month_colour ?>'><select name='tourn_type_<?= $i ?>' id='tourn_type_<?= $i ?>'>
        <option value="Snooker">Snooker</option>
        <option value="Billiards">Billiards</option>
      </select></td>
      <td align="center" style='background-color: <?= $month_colour ?>'><select name='tourn_class_<?= $i ?>' id='tourn_class_<?= $i ?>'>>
        <option value="Victorian">Victorian</option>
        <option value="Aust Rank">Aust Rank</option>
        <option value="Junior">Junior</option>
      </select></td>
    <td align="center" style='background-color: <?= $month_colour ?>'><select name="ranking_class_<?= $i ?>" id="ranking_class_<?= $i ?>">
      <?php
      if(isset($row_Cal_01['ranking_type']))
      {
        echo("<option value='" . $row_Cal_01['ranking_type'] . "' selected>" . $row_Cal_01['ranking_type'] . "</option>");
      }
      ?>
          <option value="None" <?php if (!(strcmp("None", ""))) {echo "SELECTED";} ?>>None</option>
          <option value="National" <?php if (!(strcmp("National", ""))) {echo "SELECTED";} ?>>National</option>
          <option value="Victorian" <?php if (!(strcmp("Victorian", ""))) {echo "SELECTED";} ?>>Victorian</option>
          <option value="Womens" <?php if (!(strcmp("Womens", ""))) {echo "SELECTED";} ?>>Womens</option>
          <option value="Junior" <?php if (!(strcmp("Junior", ""))) {echo "SELECTED";} ?>>Junior</option>
        </select></td>
    <td style='background-color: <?= $month_colour ?>'><select name="how_seed_<?= $i ?>" id="how_seed_<?= $i ?>">
       <?php
      if(isset($row_Cal_01['how_seed']))
      {
        echo("<option value='" . $row_Cal_01['how_seed'] . "' selected>" . $row_Cal_01['how_seed'] . "</option>");
      }
      ?>
      <option value="NA" <?php if (!(strcmp("NA", ""))) {echo "SELECTED";} ?>>Not Applicable</option>
        <option value="Aust Rankings" <?php if (!(strcmp("Aust Rankings", ""))) {echo "SELECTED";} ?>>Aust Rankings</option>
        <option value="Vic Rankings" <?php if (!(strcmp("Vic Rankings", ""))) {echo "SELECTED";} ?>>Victorian Rankings</option>
        <option value="Aust Womens Rankings" <?php if (!(strcmp("Aust Womens Rankings", ""))) {echo "SELECTED";} ?>>Aust Womens Rankings</option>
        <option value="Vic Womens Rankings" <?php if (!(strcmp("Vic WomensRankings", ""))) {echo "SELECTED";} ?>>Victorian Womens Rankings</option>
        <option value="Junior Rankings" <?php if (!(strcmp("Junior Rankings", ""))) {echo "SELECTED";} ?>>Junior Rankings</option>
      </select></td>
    
    <td align="center" style='background-color: <?= $month_colour ?>'><input type="checkbox" name="copy_to_events_<?= $i ?>"  id="copy_to_events_<?= $i ?>" /></td>
    <td align="center" style='background-color: <?= $month_colour ?>'><input type="checkbox" name="delete_<?= $i ?>"  id="delete_<?= $i ?>" /></td>
  </tr>
  <?php 
    $i++;
  } while ($row_Cal_01 = mysql_fetch_assoc($Cal_01)); 
  $no_of_events = $i;
  ?>
  <input type='hidden' id='no_of_events' value='<?= $no_of_events ?>'>
  <?php } else { ?>
  <tr>
    <td colspan="20" style='background-color: <?= $month_colour ?>' align="left">No events listed</td>
  </tr>
  <?php } ?>
</table>
</form>
</body>
</html>
