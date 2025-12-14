<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once('../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "forum_upload_attachments";
$ppu->extensions = "";
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE forum_attach SET Attachment=IFNULL(%s,Attachment) WHERE ID=%s",
                       GetSQLValueString($_POST['attachment'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "forum_index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_att_edit = "-1";
if (isset($_GET['ID'])) {
  $colname_att_edit = $_GET['ID'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_att_edit = sprintf("SELECT * FROM forum_attach WHERE ID = %s", GetSQLValueString($colname_att_edit, "int"));
$att_edit = mysql_query($query_att_edit, $connvbsa) or die(mysql_error());
$row_att_edit = mysql_fetch_assoc($att_edit);
$totalRows_att_edit = mysql_num_rows($att_edit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<link href="../Admin_xx_CSS/forum_db.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/forum_db_links.css" rel="stylesheet" type="text/css" />
<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
</head>

<body>
<div id="forum_header">
  <div id="logo"><img src="../images/VBSA1.jpg" alt="" width="90" height="87" /></div>

<table width="870" align="right">
  <tr>

    <td class="red_bold">VBSA Administrators Forum</td>
    <td align="right" class="bluebg"><a href="How%20to%20use%20the%20VBSA%20Board%20Forum.pdf" target="_blank">How to use the Forum</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="red_bold"><span class="red_bold">Edit an attachment</span></td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

<div id="ContentDB">
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'',true,'','','','','','','');return document.MM_returnValue">
    <table align="center">
      <tr>
        <td align="right">Attachment Title</td>
        <td><?php echo $row_att_edit['attach_name']; ?></td>
      </tr>
      <tr>
        <td align="right">Current Attachment</td>
        <td><?php echo $row_att_edit['Attachment']; ?></td>
      </tr>
      <tr>
        <td align="right">Will overwrite existing (even if file has same name)</td>
        <td><input name="attachment" type="file" id="attachment" onchange="checkOneFileUpload(this,'',true,'','','','','','','')" value="<?php echo $row_att_edit['Attachment']; ?>" size="60" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="button" id="button" value="Upload" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1" />
    <input name="ID" type="hidden" id="ID" value="<?php echo $row_att_edit['ID']; ?>" />
  </form>
</div>

</body>
</html>
<?php
mysql_free_result($att_edit);
?>
