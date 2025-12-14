<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once('../../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "../../calendar/cal_upload";
$ppu->extensions = "pdf,doc,docx";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "";
$ppu->nameConflict = "over";
$ppu->nameToLower = false;
$ppu->requireUpload = true;
$ppu->minWidth = "";
$ppu->minHeight = "";
$ppu->maxWidth = "";
$ppu->maxHeight = "";
$ppu->saveWidth = "";
$ppu->saveHeight = "";
$ppu->timeout = "600";
$ppu->progressBar = "";
$ppu->progressWidth = "300";
$ppu->progressHeight = "100";
$ppu->redirectURL = "";
$ppu->checkVersion("2.1.12");
$ppu->doUpload();
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

if (isset($editFormAction)) {
  if (isset($_SERVER['QUERY_STRING'])) {
	  if (!eregi("GP_upload=true", $_SERVER['QUERY_STRING'])) {
  	  $editFormAction .= "&GP_upload=true";
		}
  } else {
    $editFormAction .= "?GP_upload=true";
  }
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO calendar_attach (ID, event_number, attach_name, Attachment, upload_on, type) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['event_number'], "int"),
                       GetSQLValueString($_POST['attach_name'], "text"),
                       GetSQLValueString($_POST['Attachment'], "text"),
					   GetSQLValueString($_POST['upload_on'], "date"),
                       GetSQLValueString($_POST['type'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../calendar_detail.php?eventID=" . $_REQUEST['event_number'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO calendar_attach (ID, event_number, attach_name, Attachment, upload_on, type) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['event_number'], "int"),
                       GetSQLValueString($_POST['attach_name'], "text"),
                       GetSQLValueString($_POST['Attachment'], "text"),
					   GetSQLValueString($_POST['upload_on'], "date"),
                       GetSQLValueString($_POST['type'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../calendar_detail.php?eventID=" . $_REQUEST['event_number'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO calendar_attach (ID, event_number, attach_name, Attachment, upload_on, type) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['event_number'], "int"),
                       GetSQLValueString($_POST['attach_name'], "text"),
                       GetSQLValueString($_POST['Attachment'], "text"),
					   GetSQLValueString($_POST['upload_on'], "date"),
                       GetSQLValueString($_POST['type'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());


  $insertGoTo = "../calendar_detail.php?eventID=" . $_REQUEST['event_number'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if (isset($_GET['eventID'])) {
  $eventID = $_GET['eventID'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_attach_id = "SELECT ID, event_number, attach_name, Attachment FROM calendar_attach WHERE event_number = '$eventID'";
$attach_id = mysql_query($query_attach_id, $connvbsa) or die(mysql_error());
$row_attach_id = mysql_fetch_assoc($attach_id);
$totalRows_attach_id = mysql_num_rows($attach_id);

mysql_select_db($database_connvbsa, $connvbsa);
$query_event_id = "SELECT event_id, event FROM calendar WHERE event_id = '$eventID'";
$event_id = mysql_query($query_event_id, $connvbsa) or die(mysql_error());
$row_event_id = mysql_fetch_assoc($event_id);
$totalRows_event_id = mysql_num_rows($event_id);
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
<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>

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
      
      <table align="center" width="800">
  <tr>
    <td colspan="2" nowrap="nowrap" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" nowrap="nowrap" class="red_bold">From this page you may Upload an attachment, create an Email link or Create a URL link (a link to another web page)</td>
  </tr>
  <tr>
    <td colspan="2" class="red_bold">These will appear in the information section of the event detail page</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>Event Name: <?php echo $row_event_id['event']; ?></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>

      
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'pdf,doc,docx',true,'','','','','','','');return document.MM_returnValue">
      <table align="center" width="800">
        <tr valign="baseline">
          <td colspan="2" align="left" nowrap="nowrap" class="red_text">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="left" nowrap="nowrap" class="red_bold">Create an attachment for this event - pdf, doc or docx only</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="left" nowrap="nowrap" class="red_text">Please create a brief title for your attachment (Max 25 characters) and select the file to upload from your filing system</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Attachment title:</td>
          <td><input type="text" name="attach_name" value="" size="32" /> 
            eg entry form, reslts, draw etc. (pdf files only)</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Select your attachment</td>
          <td><input name="Attachment" type="file" id="Attachment" onchange="checkOneFileUpload(this,'pdf,doc,docx',true,'','','','','','','')" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Upload attachment" /></td>
        </tr>
      </table>
      <input type="hidden" name="ID" value="" />
      <input type="hidden" name="event_number" value="<?php echo $row_event_id['event_id']; ?>" />
      <input type="hidden" name="upload_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?>" />
      <input type="hidden" name="type" value="Uploaded Attachment" />
      <input type="hidden" name="MM_insert" value="form1" />
</form>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
      <table align="center" width="800">
        <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="left" nowrap="nowrap" class="red_bold">Create an Email Link for this event</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="left" nowrap="nowrap"><span class="red_text">Please create a brief title for your Email address: </span></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Title:</td>
          <td><input type="text" name="attach_name" value="" size="32" /> eg. Email the TD</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Email Address:</td>
          <td><input type="text" name="Attachment" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Create Email Link" /></td>
        </tr>
      </table>
      <input type="hidden" name="ID" value="" />
      <input type="hidden" name="event_number" value="<?php echo $row_event_id['event_id']; ?>" />
      <input type="hidden" name="upload_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?>" />
      <input type="hidden" name="type" value="Email" />
      <input type="hidden" name="MM_insert" value="form2" />
</form>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3">
      <table align="center" width="800">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="left" nowrap="nowrap" class="red_bold">Create a Link to another web page (URL) for this event </td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="left" nowrap="nowrap" class="red_text">Please create a brief title for your URL: eg. Visit the VBSA</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">URL title:</td>
          <td><input type="text" name="attach_name" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Paste URL:</td>
          <td nowrap="nowrap"><input type="text" name="Attachment" value="" size="50" /> 
            Copy and Paste the URL from your browser eg. http://www.vbsa.org.au/</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Create URL link" /></td>
        </tr>
      </table>
      <input type="hidden" name="ID" value="" />
      <input type="hidden" name="event_number" value="<?php echo $row_event_id['event_id']; ?>" />
      <input type="hidden" name="upload_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?>" />
      <input type="hidden" name="type" value="URL" />
      <input type="hidden" name="MM_insert" value="form3" />
</form>
    <p>&nbsp;</p>
</body>
</html>
<?php

?>
