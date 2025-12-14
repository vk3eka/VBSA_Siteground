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
  $updateSQL = sprintf("UPDATE calendar_attach SET attach_name=%s, Attachment=IFNULL(%s,Attachment), upload_on=%s WHERE ID=%s",
                       GetSQLValueString($_POST['attach_name'], "text"),
                       GetSQLValueString($_POST['Attachment'], "text"),
					   GetSQLValueString($_POST['upload_on'], "date"),
                       GetSQLValueString($_POST['ID'], "int"));

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

$id = "-1";
if (isset($_GET['id'])) {
  $id = $_GET['id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_upload = "SELECT ID, event_number, attach_name, Attachment, upload_on, event FROM calendar_attach, calendar WHERE ID='$id' AND event_number='$eventID'";
$upload = mysql_query($query_upload, $connvbsa) or die(mysql_error());
$row_upload = mysql_fetch_assoc($upload);
$totalRows_upload = mysql_num_rows($upload);
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

<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr>
        <td align="right"><span class="red_bold">Update a URL Link</span></td>
        <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td><p>The simplest method of creating a URL link - Go to the web page in your browser, </p>
          <p>copy the complete URL from the address bar,</p>
        <p> link must include the full URL http://www.siteaddress.com.au)</p>
        <p>paste URL into the &quot;URL&quot; box</p></td>
      </tr>
      <tr>
        <td colspan="2" align="left"><span class="red_bold">Event ID number - <?php echo $row_upload['event_number']; ?></span></td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">Event name</td>
        <td><?php echo $row_upload['event']; ?></td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">URL Title:</td>
        <td nowrap="nowrap"><input name="attach_name" type="text" id="attach_name" value="<?php echo $row_upload['attach_name']; ?>" size="25" /> 
          eg Check your tournament entry (Max 35 characters)</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap">URL:</td>
        <td nowrap="nowrap"><input name="Attachment" type="text" id="Attachment" value="<?php echo $row_upload['Attachment']; ?>" size="50" />
        eg http://www.vbsa.org.au/Tournaments/tournindex.php</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="button" id="button" value="Update URL" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1" />
    <input name="ID" type="hidden" id="ID" value="<?php echo $row_upload['ID']; ?>" />
    <input name="upload_on" type="hidden" id="upload_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?>" />
</form>
</body>
</html>
<?php

?>

