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
<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="1000" align="center" class="greenbg">
  <tr>
    <td colspan="3" align="left" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" class="red_bold">Calendar of events - all views are ordered by &quot;Start Date&quot; (if no start date is listed then event will not appear)</td>
    </tr>
  <tr>
    <td align="left"><a href="../Admin_web_pages/aa_webpage_index.php">Go to the Webpage Menu</a></td>
    <td><a href="../Admin_DB_VBSA/vbsa_login_success.php">Return to the Admin Menu</a></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<table width="1000" align="center" class="greenbg_menu">
  <tr>
    <td width="350" nowrap="nowrap">&nbsp;</td>
    <td width="650" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td nowrap="nowrap"><a href="calendar_event_previous.php?page=calendar" style='width:300px;'>Insert a new event/tournament</a></td>
    <td align="left">Insert an event </td>
  </tr>
  <tr>
    <td nowrap="nowrap"><a href="populate_calendar_list.php" style='width:300px;'>Populate all <?php echo (date("Y")+1) ?> calendar events from <?php echo date("Y") ?> calendar</a></td>
    <td align="left">Populate calendar for <?php echo (date("Y")+1) ?> from <?php echo date("Y") ?> entries.</td>
  </tr>
  <tr>
    <td nowrap="nowrap"><a href="calendar_list_edit.php?cal_year=<?php echo date("Y") ?>" style='width:300px;'>Edit calendar & events lists for <?php echo date("Y") ?></a></td>
    <td align="left">Edit and populate any month in the calendar list into the calendar and tournaments list”.</td>
  </tr>
  <tr>
    <td nowrap="nowrap"><a href="calendar_list_edit.php?cal_year=<?php echo (date("Y")+1) ?>" style='width:300px;'>Edit calendar & events lists for <?php echo (date("Y")+1) ?></a></td>
    <td align="left">Edit and populate any month in the calendar list into the calendar and tournaments list”.</td>
  </tr>
  <tr>
    <td nowrap="nowrap">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td nowrap="nowrap"><a href="planner_calendar.php" style='width:300px;'>Planning Calendar</a></td>
    <td align="left">Plan events</td>
  </tr>
  <tr>
    <td nowrap="nowrap">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><a href="calendar_event_xx_archive.php" style='width:300px;' title="No Start Date, Start Date is out of date or View is set">Archives</a></td>
    <td>Events that do not have a start date OR visible is set to &quot;No&quot; - will not appear on the web site</td>
  </tr>
  <tr>
    <td align="left" class="page">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" nowrap class="greenbg" colspan='5'><a href="../Admin_DB_VBSA/export_tournament_csv.php" style='width:300px;'>Export Calendar/Tournament List (test)</a></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td align="center" class="page">If there is a view that is not listed that would suit your purpose please let me know <a href="mailto:web@vbsa.org.au">web@vbsa.org.au</a></td>
  </tr>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
</center>
</body>
</html>
<?php
?>