<?php require_once('../Connections/connvbsa.php'); ?>
<?php
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
?>
<?php require_once('../Connections/connvbsa.php'); ?><?php
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_list = "SELECT * FROM calendar WHERE visible='No' ORDER BY startdate ASC";
$Cal_list = mysql_query($query_Cal_list, $connvbsa) or die(mysql_error());
$row_Cal_list = mysql_fetch_assoc($Cal_list);
$totalRows_Cal_list = mysql_num_rows($Cal_list);

mysql_select_db($database_connvbsa, $connvbsa);
$query_nostart = "SELECT * FROM calendar WHERE startdate is null AND visible='Yes' ORDER BY startdate ASC";
$nostart = mysql_query($query_nostart, $connvbsa) or die(mysql_error());
$row_nostart = mysql_fetch_assoc($nostart);
$totalRows_nostart = mysql_num_rows($nostart);

mysql_select_db($database_connvbsa, $connvbsa);
$query_outofdate = "SELECT * FROM calendar WHERE  YEAR( startdate )< YEAR( CURDATE( ) ) AND visible='Yes' ORDER BY startdate ASC";
$outofdate = mysql_query($query_outofdate, $connvbsa) or die(mysql_error());
$row_outofdate = mysql_fetch_assoc($outofdate);
$totalRows_outofdate = mysql_num_rows($outofdate);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" />

</head>

<body>

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
  <table align="center">
    <tr>
      <td colspan="2" align="center" class="red_bold" >&nbsp;</td>
    </tr>
    <tr>
      <td width="624" align="center" class="red_bold" >Calendar Archives</td>
      <td align="right" class="greenbg"><a href="A_calendar_index.php">Calendar Menu</a></td>
    </tr>
    <tr>
      <td colspan="2">To Edit an event, Create Email link, URL, upload  attachment/s or create a web page item please click the detail button &nbsp;&nbsp;&nbsp;&nbsp;<img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" />&nbsp; &nbsp;&nbsp;&nbsp;adjacent to the event</td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_text">Note: Events will only show in the calendar if: A Start Date has been set in the current year and &quot;Visible&quot; is set to Yes</td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_text">&nbsp;</td>
    </tr>
  </table>
  <table border="1" align="center">
    <tr>
      <td colspan="13" align="left"><span class="red_bold">Archived events - shows all events where visible = &quot;No&quot;</span></td>
    </tr>
    <tr>
      <td align="center">Event ID</td>
      <td align="center">Visible?</td>
      <td>Event Name</td>
      <td>Venue</td>
      <td align="center">State</td>
      <td align="center">Aust Rank?</td>
      <td align="center">Ranking Type</td>
      <td align="center">Visible?</td>
      <td align="center">Start Date</td>
      <td align="center">Finish Date</td>
      <td align="center">Entries Close </td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_Cal_list['event_id']; ?></td>
        <td align="center"><?php echo $row_Cal_list['visible']; ?></td>
        <td><?php echo $row_Cal_list['event']; ?></td>
        <td><?php echo $row_Cal_list['venue']; ?></td>
        <td align="center"><?php echo $row_Cal_list['state']; ?></td>
        <td align="center"><?php echo $row_Cal_list['aust_rank']; ?></td>
        <td align="center"><?php echo $row_Cal_list['ranking_type']; ?></td>
        <td align="center"><?php echo $row_Cal_list['visible']; ?></td>
        <td align="center">
          <?php if ($row_Cal_list['startdate'] != ''): ?>
          <?php $newDate = date("M d, Y", strtotime($row_Cal_list['startdate'])); echo $newDate; ?>
          <?php endif; ?>        </td>
        <td align="center">
          <?php if ($row_Cal_list['finishdate'] != ''): ?>
          <?php $newDate = date("M d, Y", strtotime($row_Cal_list['finishdate'])); echo $newDate; ?>
          <?php endif; ?>        </td>
        <td align="center">
          <?php if ($row_Cal_list['closedate'] != ''): ?>
          <?php $newDate = date("M d, Y", strtotime($row_Cal_list['closedate'])); echo $newDate; ?>
          <?php endif; ?>        </td>
        <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_list['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
        <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_list['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" /></a></td>-->
        <td align="center"><a href="user_files/event_delete_confirm_archives.php?eventID=<?php echo $row_Cal_list['event_id']; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
      </tr>
      <?php } while ($row_Cal_list = mysql_fetch_assoc($Cal_list)); ?>
</table>
  <p>&nbsp;</p>
  <table border="1" align="center">
    <tr>
      <td colspan="13" align="left"><span class="red_bold">Archived events - shows all events where &quot;Start Date&quot; does not exist but &quot;Visible&quot; = Yes</span></td>
    </tr>
    <tr>
      <td align="center">Event ID</td>
      <td align="center">Visible?</td>
      <td>Event Name</td>
      <td>Venue</td>
      <td align="center">State</td>
      <td align="center">Aust Rank?</td>
      <td align="center">Ranking Type</td>
      <td align="center">Visible?</td>
      <td align="center">Start Date</td>
      <td align="center">Finish Date</td>
      <td align="center">Entries Close </td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_nostart['event_id']; ?></td>
        <td align="center"><?php echo $row_nostart['visible']; ?></td>
        <td><?php echo $row_nostart['event']; ?></td>
        <td><?php echo $row_nostart['venue']; ?></td>
        <td align="center"><?php echo $row_nostart['state']; ?></td>
        <td align="center"><?php echo $row_nostart['aust_rank']; ?></td>
        <td align="center"><?php echo $row_nostart['ranking_type']; ?></td>
        <td align="center"><?php echo $row_nostart['visible']; ?></td>
        <td align="center"><?php if ($row_nostart['startdate'] != ''): ?>
          <?php $newDate = date("M d, Y", strtotime($row_nostart['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
        <td align="center"><?php if ($row_nostart['finishdate'] != ''): ?>
          <?php $newDate = date("M d, Y", strtotime($row_nostart['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
        <td align="center"><?php if ($row_nostart['closedate'] != ''): ?>
          <?php $newDate = date("M d, Y", strtotime($row_nostart['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
        <td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_nostart['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" /></a></td>
        <td align="center"><a href="user_files/event_delete_confirm_archives.php?eventID=<?php echo $row_nostart['event_id']; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
      </tr>
      <?php } while ($row_nostart = mysql_fetch_assoc($nostart)); ?>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>

</body>
</html>
<?php

?>