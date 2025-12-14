<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
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

$MM_restrictGoTo = "../../page_error.php";
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



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO calendar (event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate, about, tourn, visible, footer1, footer2, footer3, footer4) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['event'], "text"),
                       GetSQLValueString($_POST['venue'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['aust_rank'], "text"),
                       GetSQLValueString($_POST['ranking_type'], "text"),
                       GetSQLValueString($_POST['startdate'], "date"),
                       GetSQLValueString($_POST['finishdate'], "date"),
                       GetSQLValueString($_POST['closedate'], "date"),
                       GetSQLValueString($_POST['about'], "text"),
                       GetSQLValueString($_POST['tourn'], "text"),
                       GetSQLValueString($_POST['visible'], "text"),
                       GetSQLValueString(isset($_POST['footer1']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer2']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer3']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer4']) ? "true" : "", "defined","'Y'","'N'"));
//echo($insertSQL . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  $year = date('Y', strtotime($_POST['startdate']));
  $insertGoTo = "../calendar_list.php?cal_year=" . $year;
  //echo($insertGoTo . "<br>");
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
  
  <table align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td align="center"><span class="red_bold">Insert a new event into the calendar</span></td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><span class="red_bold">If a &quot;Start Date&quot; is not set, or, &quot;Visible is set to &quot;No&quot; then event will not appear in the calendar</span></td>
    </tr>
    <tr>
      <td colspan="2" align="center">for events that do not have a start date, start date is not set to the current year or &quot;visible&quot; is set to &quot;No&quot; please go to the Archives</td>
    </tr>
    <tr>
      <td colspan="2" align="center">for events that have the start date set for next year go to next years events</td>
    </tr>
  </table>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top">Name of event or Tournament</td>
        <td>&nbsp;</td>
        <td><input type="text" name="event" value="" size="50" /> 50 Characters Max</td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap="nowrap">About the event:</td>
        <td>&nbsp;</td>
        <td><textarea name="about" cols="85" rows="4"></textarea></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Venue:</td>
        <td>&nbsp;</td>
        <td><input type="text" name="venue" value="" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Startdate:</td>
        <td>&nbsp;</td>
        <td><input type="text" name="startdate" value="" size="24" />
        <input type="button" value="Select Date" onclick="displayDatePicker('startdate', false, 'ymd', '-');" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Finishdate:</td>
        <td>&nbsp;</td>
        <td><input type="text" name="finishdate" value="" size="24" />
        <input type="button" value="Select Date" onclick="displayDatePicker('finishdate', false, 'ymd', '-');" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Closedate:</td>
        <td>&nbsp;</td>
        <td><input type="text" name="closedate" value="" size="24" />
        <input type="button" value="Select Date" onclick="displayDatePicker('closedate', false, 'ymd', '-');" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">State:</td>
        <td>&nbsp;</td>
        <td><select name="state">
          <option value="" <?php if (!(strcmp("No Entry", ""))) {echo "SELECTED";} ?>>No Entry</option>
          <option value="ACT" <?php if (!(strcmp("ACT", ""))) {echo "SELECTED";} ?>>ACT</option>
          <option value="NSW" <?php if (!(strcmp("NSW", ""))) {echo "SELECTED";} ?>>NSW</option>
          <option value="NT" <?php if (!(strcmp("NT", ""))) {echo "SELECTED";} ?>>NT</option>
          <option value="Qld" <?php if (!(strcmp("Qld", ""))) {echo "SELECTED";} ?>>Qld</option>
          <option value="SA" <?php if (!(strcmp("SA", ""))) {echo "SELECTED";} ?>>SA</option>
          <option value="Tas" <?php if (!(strcmp("Tas", ""))) {echo "SELECTED";} ?>>Tas</option>
          <option value="Vic" <?php if (!(strcmp("Vic", ""))) {echo "SELECTED";} ?>>Vic</option>
          <option value="WA" <?php if (!(strcmp("WA", ""))) {echo "SELECTED";} ?>>WA</option>
        </select></td>
      <tr>  
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Austalian Ranking event</td>
        <td>&nbsp;</td>
        <td><select name="aust_rank">
          <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
          <option selected="selected" value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Ranking Type:</td>
        <td>&nbsp;</td>
        <td><select name="ranking_type">
          <option value="National" <?php if (!(strcmp("National", ""))) {echo "SELECTED";} ?>>National</option>
          <option value="Victorian" <?php if (!(strcmp("Victorian", ""))) {echo "SELECTED";} ?>>Victorian</option>
          <option value="Womens" <?php if (!(strcmp("Womens", ""))) {echo "SELECTED";} ?>>Womens</option>
          <option value="Junior" <?php if (!(strcmp("Junior", ""))) {echo "SELECTED";} ?>>Junior</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="left" class="red_text">"National" for tournaments that DO attract National Ranking points. <br />
            "Victorian" for tournaments that DO attract Victorian Ranking points. <br />
            "Womens" for tournaments that DO attract Victorian WOMENS Ranking points. <br />
            "Junior" for tournaments that attract Victorian JUNIOR Ranking points. <br />
           </td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Is this event a tournament?</td>
        <td>&nbsp;</td>
        <td><select name="tourn">
          <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Visible - will be seen on the website:</td>
        <td>&nbsp;</td>
        <td><select name="visible">
          <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <table align="center" class="page">
      <tr>
        <td colspan="3" class="red_text">Check any of these to add to the Footer </td>
      </tr>
      <tr>
        <td>VBSA</td>
        <td><input type="checkbox" name="footer1" value="" /></td>
        <td>To enter this event, pay your membership or make a payment to   the VBSA please go to the payments page. Enquiries - <a href="mailto:treasurer@vbsa.org.au">VBSA treasurer </a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="footer2" value="" /></td>
        <td>To check the VBSA have received your entry please go to <a href="http://www.vbsa.org.au/Tournaments/tournindex.php">&quot;VBSA Tournament entries&quot;</a> . Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament.</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="57">Non VBSA</td>
        <td width="20"><input type="checkbox" name="footer3" value="" /></td>
        <td width="931"><p>Please Note: The VBSA do not accept entries for this event, please refer the entry form fo details on how to enter</p></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="footer4" value="" /></td>
        <td>Please go to the <a href="http://absc.com.au/results.aspx">ABSC Site</a> for results</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="submit" value="Insert Event" /></td>
      </tr>
    </table>
  <input type="hidden" name="event_id" value="" />
    <input type="hidden" name="MM_insert" value="form1" />
</form>
</center>
</body>
</html>
<?php
?>