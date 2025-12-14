<?php require_once('../Connections/connvbsa.php'); 
include ("../vbsa_online_scores/php_functions.php");

error_reporting(0);

mysql_select_db($database_connvbsa, $connvbsa);

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

for($month = 1; $month <= 12; $month++)
{
  $query_Cal_01 = "Select *  FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE YEAR( startdate )='$cal_year' and month(startdate) = '$month' GROUP BY calendar.event_id ORDER BY calendar.startdate";
  $Cal_01 = mysql_query($query_Cal_01, $connvbsa) or die(mysql_error());
  $row_Cal_01 = mysql_fetch_assoc($Cal_01);
  $totalRows[$month] = mysql_num_rows($Cal_01);
}

if ($_POST['ButtonName'] == "SaveChanges") {

  //echo("Save Changes<br>");
  // get next tourn_id
  $query_tourn_id = 'Select * FROM vbsa3364_vbsa2.tournaments order by tourn_id DESC Limit 1';
  $result_tourn_id = mysql_query($query_tourn_id, $connvbsa) or die(mysql_error());
  $build_tourn_id = $result_tourn_id->fetch_assoc();
  $last_id = $build_tourn_id['tourn_id'];
  $next_id = ($last_id+1);

  $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
  for ($i = 0; $i < count($packeddata); $i++) 
  {
    $events = explode(", ", $packeddata[$i]);

    //echo("<pre>");
    //echo(var_dump($packeddata));
    //echo("</pre>");

    //echo($events[18] . "<br>");
    $updateSQL = sprintf("Update calendar SET tourn_id=%s, event=%s, venue=%s, startdate=%s, finishdate=%s, closedate=%s, `state`=%s, visible=%s, aust_rank=%s, ranking_type=%s, tourn=%s, footer1=%s, footer2=%s, footer3=%s, footer4=%s, tourn_type=%s, tourn_class=%s, special_dates=%s WHERE event_id=%s",
      GetSQLValueString($events[18], "int"),
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
      GetSQLValueString($events[15], "text"),
      GetSQLValueString($events[16], "text"),
      GetSQLValueString($events[20], "text"),
      GetSQLValueString($events[0], "int"));
    //echo($updateSQL . "<br>");
    $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

    $status = 'Closed';
    $insert_tourn_SQL = sprintf("Update tournaments set tourn_name=%s, site_visible=%s, tourn_year=%s, tourn_type=%s, tourn_class=%s, ranking_type=%s, how_seed=%s, status=%s WHERE tourn_id=%s",
      GetSQLValueString(stripslashes($events[1]), "text"),
      GetSQLValueString($events[7], "text"),
      $cal_year,
      GetSQLValueString($events[15], "text"),
      GetSQLValueString($events[16], "text"),
      GetSQLValueString($events[9], "text"),
      GetSQLValueString($events[17], "text"),
      GetSQLValueString($status, "text"),
      GetSQLValueString($events[18], "int"));
    //echo($insert_tourn_SQL . "<br>");
    $result = mysql_query($insert_tourn_SQL, $connvbsa) or die(mysql_error());
  
    // need to add to non playing dates.
    $start = $events[3];
    $end = $events[4];
    $summary = $events[1];
    $description = 'NA';
    $type = 'vbsa';

    // check if event is already listed
    $query_event = 'Select SUMMARY FROM vbsa3364_vbsa2.tbl_ics_dates Where SUMMARY = "' . $summary . '"';
    $result_event = mysql_query($query_event, $connvbsa) or die(mysql_error());
    $date_rows = $result_event->num_rows;
    if($events[20] === 'Y')
    {
      if($date_rows == 0)
      {
        $days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
        for($i = 0; $i <= $days; $i++)
        {
          $new_start = date('Y-m-d', strtotime($start . ' + ' . $i . ' days'));
          $sql = "Insert into tbl_ics_dates (DTSTART, SUMMARY, DESCRIPTION, Year, TYPE) VALUES
          ('" . $new_start . "', '" . addslashes($summary) . "', '" . addslashes($description) . "', " . $cal_year . ", '" . $type . "')";
          $update = mysql_query($sql, $connvbsa) or die(mysql_error());
        }
      }
    }
    else if($events[20] === 'N')
    {
      if($date_rows > 0)
      {
        $days = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);
        for($i = 0; $i <= $days; $i++)
        {
          $new_start = date('Y-m-d', strtotime($start . ' + ' . $i . ' days'));
          $sql = "Delete from tbl_ics_dates Where DTSTART = '" . $new_start . "' AND SUMMARY = '" . addslashes($summary) . "' AND DESCRIPTION = '" . addslashes($description) . "' AND Year = " . $cal_year . " AND TYPE = '" . $type . "'";
          $update = mysql_query($sql, $connvbsa) or die(mysql_error());
        }
      }

    }
  }
  header("Location: calendar_list_edit.php?cal_year=" . $cal_year); 
}

if ($_POST['ButtonName'] == "Delete")
{
  $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
  
  foreach($packeddata as $item => $event_id) {
    $updateSQL = "Delete From calendar where event_id  = " . $event_id;   
    $result = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  }
  header("Location: calendar_list_edit.php?cal_year=" . $cal_year);
}

?>
<script>

function SaveSelectedChangesButton(month, no_of_events) 
{
  //alert("Events " + no_of_events + ", Month " + month);
    var transferdata = {};
    for (var i = 0; i < no_of_events; i++) // get number of events
    {
        if(document.getElementById("vbsa_event_" + month + "_" + i).checked == true)
        {
          vbsa_event = 'Y';
        }
        else
        {
          vbsa_event = 'N';
        }
        if(document.getElementById("vbsa_entries_" + month + "_" + i).checked == true)
        {
          vbsa_entries = 'Y';
        }
        else
        {
          vbsa_entries = 'N';
        }
        if(document.getElementById("non_vbsa_event_" + month + "_" + i).checked == true)
        {
          non_vbsa_event = 'Y';
        }
        else
        {
          non_vbsa_event = 'N';
        }
        if(document.getElementById("non_vbsa_entries_" + month + "_" + i).checked == true)
        {
          non_vbsa_entries = 'Y';
        }
        else
        {
          non_vbsa_entries = 'N';
        }
        if(document.getElementById("copy_to_non_dates_" + month + "_" + i).checked == true)
        {
          copy_to_non_dates = 'Y';
        }
        else
        {
          copy_to_non_dates = 'N';
        }
        //alert(document.getElementById("event_id_" + month + "_" + i).value);
        transferdata[i] = document.getElementById("event_id_" + month + "_" + i).value + ", " +
                          document.getElementById("event_" + month + "_" + i).value + ", " +
                          document.getElementById("venue_" + month + "_" + i).value + ", " +
                          document.getElementById("startdate_" + month + "_" + i).value + ", " +
                          document.getElementById("finishdate_" + month + "_" + i).value + ", " +
                          document.getElementById("closedate_" + month + "_" + i).value + ", " +
                          document.getElementById("state_" + month + "_" + i).value + ", " +
                          document.getElementById("visible_" + month + "_" + i).value + ", " +
                          document.getElementById("aust_rank_" + month + "_" + i).value + ", " +
                          document.getElementById("ranking_type_" + month + "_" + i).value + ", " +
                          document.getElementById("tourn_" + month + "_" + i).value + ", " +
                          vbsa_event + ", " +
                          vbsa_entries + ", " +
                          non_vbsa_event + ", " +
                          non_vbsa_entries + ", " +
                          document.getElementById("tourn_type_" + month + "_" + i).value + ", " +
                          document.getElementById("tourn_class_" + month + "_" + i).value + ", " +
                          document.getElementById("how_seed_" + month + "_" + i).value + ", " +
                          document.getElementById("tourn_id_" + month + "_" + i).value + ", Closed" + ", " +
                          copy_to_non_dates;
    }
    var data = JSON.stringify(transferdata);
    //console.log(data);
    document.save_edits.Events.value = no_of_events;
    document.save_edits.PackedData.value = data;  
    document.save_edits.ButtonName.value = "SaveChanges"; 
    document.save_edits.submit();
}

function DeleteSelectedButton(month, no_of_events)  
{
  var transferdata = {};
  for(var i = 0; i < no_of_events; i++) // get number of events
  {
    if(document.getElementById("delete_" + month + "_" + i).checked === true)
    {
      transferdata[i] = document.getElementById("event_id_" + month + "_" + i).value;
    }
  }
  var data = JSON.stringify(transferdata);
  document.save_edits.Events.value = no_of_events;
  document.save_edits.PackedData.value = data;  
  document.save_edits.ButtonName.value = "Delete"; 
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
  <?php
  for($month = 1; $month <= 12; $month++)
  {
  ?>
    for(i = 0; i < <?= $totalRows[$month] ?>; i++)
    {
      month = <?= $month ?>;
      var mystart<?= $month ?>date;  
      mystart<?= $month ?>date = new dhtmlXCalendarObject("startdate_" + month + "_" + i);
      mystart<?= $month ?>date.setSkin('dhx_skyblue');
      mystart<?= $month ?>date.hideTime();
      mystart<?= $month ?>date.hideWeekNumbers();
      mystart<?= $month ?>date.setDateFormat("%Y-%m-%d");

      var myfinish<?= $month ?>date;  
      myfinish<?= $month ?>date = new dhtmlXCalendarObject("finishdate_" + month + "_" + i);
      myfinish<?= $month ?>date.setSkin('dhx_skyblue');
      myfinish<?= $month ?>date.hideTime();
      myfinish<?= $month ?>date.hideWeekNumbers();
      myfinish<?= $month ?>date.setDateFormat("%Y-%m-%d");

      var myentry<?= $month ?>date;  
      myentry<?= $month ?>date = new dhtmlXCalendarObject("closedate_" + month + "_" + i);
      myentry<?= $month ?>date.setSkin('dhx_skyblue');
      myentry<?= $month ?>date.hideTime();
      myentry<?= $month ?>date.hideWeekNumbers();
      myentry<?= $month ?>date.setDateFormat("%Y-%m-%d");
    }
  <?php
  }
  ?>
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
    <td align="right" class="greenbg"><a href="calendar_event_previous.php?page=calendar">Insert a new event</a></td>
    <td><a href="../Admin_web_pages/aa_webpage_index.php">Webpage Menu</a></td>
    <td><a href="../Admin_DB_VBSA/vbsa_login_success.php">Admin Menu</a></td>
  </tr>
</table>
<p>&nbsp;</p>
<form action="calendar_list_edit.php?cal_year=<?php echo $cal_year ?>" method="post" name="save_edits" id="save_edits">
<input type='hidden' id='Events' name="Events">
<input type='hidden' id='PackedData' name="PackedData">
<input type='hidden' id='ButtonName' name="ButtonName">
<table align="center" width="80%">
  <tr>
    <td align="center" class="red_bold"><h2>Calendar List for <?= $cal_year ?></h2></td>
  </tr>
  <tr>
    <td align="center"><h2>Legend for Column Headings</h2></td>
  </tr>
  <tr>
    <td align="left"><b>Show on website</b> - will be seen on the public website</td>
  </tr>
  <tr>
     <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><b>Footers:- </b></td>
  </tr>
  <tr>
    <td align="left">&nbsp;&nbsp;<b>VBSA Event</b> - To enter this event, pay your membership or make a payment to   the VBSA please go to the payments page. Enquiries - <a href="mailto:treasurer@vbsa.org.au">VBSA treasurer.</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;&nbsp;<b>VBSA Entries</b> - To check the VBSA have received your entry please go to <a href="http://www.vbsa.org.au/Tournaments/tournindex.php">VBSA Tournament entries</a> . Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament.</td>
  </tr>
  <tr>
    <td align="left">&nbsp;&nbsp;<b>Non VBSA Event</b> - Please Note: The VBSA do not accept entries for this event, please refer the entry form fo details on how to enter.</td>
  </tr>
  <tr>
    <td align="left">&nbsp;&nbsp;<b>Non VBSA Entries</b> - Please go to the <a href="http://absc.com.au/results.aspx">ABSC Site</a> for results.</td>
    </td>
  </tr>
  <tr>
     <td>&nbsp;</td>
  </tr>
  <tr>
     <td align="left"><b>Attract Vic Ranking</b></td>
  </tr>
  <tr>
    <td align="left">&nbsp;&nbsp;<b>No Entry</b> for tournaments that do not attract Victorian Ranking points. <br />
          &nbsp;&nbsp;<b>Victorian</b> for tournaments that DO attract Victorian Ranking points.<br />
          &nbsp;&nbsp;<b>Womens</b> for tournaments that DO attract Victorian WOMENS Ranking points.<br />
          &nbsp;&nbsp;<b>Junior</b> for tournaments that attract Victorian JUNIOR Ranking points.<br /></td>
  </tr>
</table>
<br>
<?php
$total_records = 0;
mysql_select_db($database_connvbsa, $connvbsa);
for($month = 1; $month <= 12; $month++)
{
  switch($month)
  {
    case 1:
      $month = 1;
      $title = 'January';
      break;
    case 2:
      $month = 2;
      $title = 'February';
      break;
    case 3:
      $month = 3;
      $title = 'March';
      break;
    case 4:
      $month = 4;
      $title = 'April';
      break;
    case 5:
      $month = 5;
      $title = 'May';
      break;
    case 6:
      $month = 6;
      $title = 'June';
      break;
    case 7:
      $month = 7;
      $title = 'July';
      break;
    case 8:
      $month = 8;
      $title = 'August';
      break;
    case 9:
      $month = 9;
      $title = 'September';
      break;
    case 10:
      $month = 10;
      $title = 'October';
      break;
    case 11:
      $month = 11;
      $title = 'November';
      break;
    case 12:
      $month = 12;
      $title = 'December';
      break;
  }
  $query_Cal_01 = "Select calendar.event_id, event, venue, state, aust_rank, tournaments.ranking_type, startdate, finishdate, closedate, visible, event_number, attach_name, tournaments.tourn_id, tournaments.tourn_type, tournaments.tourn_class,  calendar.tourn, footer1, footer2, footer3, footer4, tourn_year, special_dates FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id LEFT JOIN tournaments ON tournaments.tourn_id = calendar.tourn_id WHERE ((YEAR(startdate) = $cal_year AND MONTH(startdate) = " . $month . "))  GROUP BY calendar.event_id ORDER BY calendar.startdate";
  $Cal_01 = mysql_query($query_Cal_01, $connvbsa) or die(mysql_error());
  $row_Cal_01 = mysql_fetch_assoc($Cal_01);

  $totalRows_Cal_01 = mysql_num_rows($Cal_01);
  $total_records = ($total_records + $totalRows_Cal_01);
  include "monthly_calendar.php";
}

?>
</form>
</body>
</html>
