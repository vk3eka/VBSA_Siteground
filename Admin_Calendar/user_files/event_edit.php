<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once("../../webassist/ckeditor/ckeditor.php"); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../../Admin_DB_VBSA/vbsa_logout.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE calendar SET event=%s, venue=%s, `state`=%s, aust_rank=%s, ranking_type=%s, startdate=%s, finishdate=%s, entry_close=%s, about=%s, tourn=%s, visible=%s, footer1=%s, footer2=%s, footer3=%s, footer4=%s WHERE event_id=%s",
                       GetSQLValueString($_POST['event'], "text"),
                       GetSQLValueString($_POST['venue'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['aust_rank'], "text"),
                       GetSQLValueString($_POST['ranking_type'], "text"),
                       GetSQLValueString($_POST['startdate'], "date"),
                       GetSQLValueString($_POST['finishdate'], "date"),
                       GetSQLValueString($_POST['entry_close'], "date"),
                       GetSQLValueString($_POST['about'], "text"),
                       GetSQLValueString($_POST['tourn'], "text"),
                       GetSQLValueString($_POST['visible'], "text"),
                       GetSQLValueString(isset($_POST['footer1']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer2']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer3']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer4']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString($_POST['event_id'], "int"));
//echo("Update " . $updateSQL . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../calendar_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$eventID = "-1";
if (isset($_GET['eventID'])) {
  $eventID = $_GET['eventID'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_edit = "SELECT * FROM calendar WHERE event_id = '$eventID'";
$Cal_edit = mysql_query($query_Cal_edit, $connvbsa) or die(mysql_error());
$row_Cal_edit = mysql_fetch_assoc($Cal_edit);
$totalRows_Cal_edit = mysql_num_rows($Cal_edit);
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
  <table width="758" align="center" class="page">
      <tr valign="baseline">
        <td align="left" valign="baseline" nowrap="nowrap" class="red_bold">&nbsp;</td>
        <td align="right" valign="baseline" nowrap="nowrap" class="red_bold">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td align="left" nowrap="nowrap" class="red_bold">Edit event ID : <?php echo $row_Cal_edit['event_id']; ?></td>
        <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td width="303" align="right" nowrap="nowrap">Event Name:</td>
        <td width="439"><input type="text" name="event" value="<?php echo $row_Cal_edit['event']; ?>" size="50" />
          (Max 50 characters)</td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Venue:</td>
        <td><input type="text" name="venue" value="<?php echo $row_Cal_edit['venue']; ?>" size="80" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">State:</td>
        <td><select name="state">
          <option value="" <?php if (!(strcmp("", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No Entry</option>
          <option value="ACT" <?php if (!(strcmp("ACT", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>ACT</option>
          <option value="NSW" <?php if (!(strcmp("NSW", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NSW</option>
          <option value="NT" <?php if (!(strcmp("NT", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NT</option>
          <option value="Qld" <?php if (!(strcmp("Qld", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Qld</option>
          <option value="SA" <?php if (!(strcmp("SA", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>SA</option>
          <option value="Tas" <?php if (!(strcmp("Tas", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Tas</option>
          <option value="Vic" <?php if (!(strcmp("Vic", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Vic</option>
          <option value="WA" <?php if (!(strcmp("WA", htmlentities($row_Cal_edit['state'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>WA</option>
        </select>      </td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Australian Ranking Tournament ?</td>
        <td><select name="aust_rank">
          <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Cal_edit['aust_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_Cal_edit['aust_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        </select>      </td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Ranking Type:</td>
        <td><select name="ranking_type">
          <option value="None" <?php if (!(strcmp("None", htmlentities($row_Cal_edit['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>None</option>
          <option value="No Entry" <?php if (!(strcmp("No Entry", htmlentities($row_Cal_edit['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No Entry</option>
          <option value="National" <?php if (!(strcmp("National", htmlentities($row_Cal_edit['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>National</option>
          <option value="Victorian" <?php if (!(strcmp("Victorian", htmlentities($row_Cal_edit['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Victorian</option>
          <option value="Womens" <?php if (!(strcmp("Womens", htmlentities($row_Cal_edit['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Womens</option>
          <option value="Junior" <?php if (!(strcmp("Junior", htmlentities($row_Cal_edit['ranking_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Junior</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Start Date:</td>
        <td><input type="text" name="startdate" value="<?php echo $row_Cal_edit['startdate']; ?>" size="32" /> 
        <input type="button" value="Select Start Date" onclick="displayDatePicker('startdate', false, 'ymd', '.');" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Finish (last day of play:</td>
        <td><input type="text" name="finishdate" value="<?php echo $row_Cal_edit['finishdate']; ?>" size="32" />
        <input type="button" value="Select Finish Date" onclick="displayDatePicker('finishdate', false, 'ymd', '.');" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Entries Close:</td>
        <td><input type="text" name="entry_close" value="<?php echo $row_Cal_edit['entry_close']; ?>" size="32" />
        <input type="button" value="Entries Close On" onclick="displayDatePicker('entry_close', false, 'ymd', '.');" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap="nowrap">Description or info about the tournament or event</td>
        <td><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "".$row_Cal_edit['about']  ."";
$CKEditor = new CKEditor();
$CKEditor->basePath = "../../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "Normal";
$CKEditor_config["wa_preset_file"] = "Normal.xml";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "200px";
$CKEditor_config["dialog_startupFocusTab"] = false;
$CKEditor_config["fullPage"] = false;
$CKEditor_config["tabSpaces"] = 4;
$CKEditor_config["toolbar"] = array(
array( 'Bold','Italic','Underline'),
array( 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
array( 'NumberedList','BulletedList','-','Outdent','Indent'),
array( 'FontName','FontSize'),
array( 'TextColor'));
$CKEditor_config["contentsLangDirection"] = "ltr";
$CKEditor_config["entities"] = false;
$CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
$CKEditor_config["pasteFromWordRemoveStyles"] = false;
$CKEditor->editor("about", $CKEditor_initialValue, $CKEditor_config);
?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Tournament:</td>
        <td><select name="tourn">
          <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Cal_edit['tourn'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_Cal_edit['tourn'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Visible (if no, the event will not be visible on the site):</td>
        <td><select name="visible">
          <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Cal_edit['visible'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_Cal_edit['visible'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    
  <table width="1091" align="center" class="page">
    
    <tr>
      <td width="49" rowspan="8" valign="top" class="red_text"><span class="red_text">Check any of these to add to the Footer</span></td>
      <td>&nbsp;</td>
      <td>VBSA</td>
      <td><input type="checkbox" name="footer1" value="Y" id="footer1" <?php if (!(strcmp(htmlentities($row_Cal_edit['footer1'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
      <td>To enter this event, pay your membership or make a payment to the VBSA please go to the payments page. Enquiries -  <a href="mailto:treasurer@vbsa.org.au">VBSA treasurer</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="checkbox" name="footer2" value="Y" id="footer2" <?php if (!(strcmp(htmlentities($row_Cal_edit['footer2'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
      <td>To check the VBSA have received your entry please go to <a href="http://www.vbsa.org.au/Tournaments/tournindex.php">&quot;VBSA Tournament entries&quot;</a> . Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament.</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="10">&nbsp;</td>
      <td width="57">Non VBSA</td>
      <td width="20"><input type="checkbox" name="footer3" value="Y" id="footer3" <?php if (!(strcmp(htmlentities($row_Cal_edit['footer3'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
      <td width="931"><p>Please Note: The VBSA do not accept entries for this event, please refer the entry form for details on how to enter</p></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="checkbox" name="footer4" value="Y" id="footer4" <?php if (!(strcmp(htmlentities($row_Cal_edit['footer4'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
      <td>Please go to the <a href="http://absc.com.au/results.aspx">ABSC Site</a> for results</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" value="Update Event" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="event_id" value="<?php echo $row_Cal_edit['event_id']; ?>" />
</form>
</center>
</body>
</html>
<?php

?>
