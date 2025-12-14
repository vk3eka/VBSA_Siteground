<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once('../../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "../../Front_page_upload";
$ppu->extensions = "pdf,doc,docx";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "";
$ppu->nameConflict = "over";
$ppu->nameToLower = false;
$ppu->requireUpload = false;
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO webpage_attach (up_id, up_desc, up_on, up_pdf_name, up_event, item_id, up_type) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['up_id'], "int"),
                       GetSQLValueString($_POST['up_desc'], "date"),
					   GetSQLValueString($_POST['up_on'], "text"),
                       GetSQLValueString($_POST['up_pdf_name'], "text"),
                       GetSQLValueString($_POST['up_event'], "int"),
                       GetSQLValueString($_POST['item_id'], "int"),
                       GetSQLValueString($_POST['up_type'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  
    $insertGoTo = "../item_detail.php?item_id=" . $_REQUEST['item_id'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  
}

if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_attach = "SELECT * FROM webpage_attach WHERE item_id = '$item_id'";
$attach = mysql_query($query_attach, $connvbsa) or die(mysql_error());
$row_attach = mysql_fetch_assoc($attach);
$totalRows_attach = mysql_num_rows($attach);

mysql_select_db($database_connvbsa, $connvbsa);
$query_item = "SELECT * FROM webpage_items, webpage_attach WHERE ID=item_id AND item_id='$item_id' GROUP BY item_id";
$item = mysql_query($query_item, $connvbsa) or die(mysql_error());
$row_item = mysql_fetch_assoc($item);
$totalRows_item = mysql_num_rows($item);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'pdf,doc,docx',false,'','','','','','','');return document.MM_returnValue">
  <table align="center" width="800">
    <tr>
      <td class="red_bold" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td class="red_bold" nowrap="nowrap">From this page you may Upload an attachment. PDF files only</td>
    </tr>
    <tr>
      <td class="red_text">After uploading a link to the attachment using the &quot;Attachment title&quot; will appear at the bottom of the page</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Item Name: <span class="red_text"><?php echo $row_item['Header']; ?></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table align="center" width="800">
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap" class="red_bold">Create an attachment for this item</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap" class="red_text">Please create a brief title for your attachment (Max 35 characters) and select the file to upload from your filing system</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Attachment title:</td>
      <td><input type="text" name="up_desc" value="" size="32" />
        eg entry form, reslts, draw etc</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Select your attachment</td>
      <td><input name="up_pdf_name" type="file" id="up_pdf_name" onchange="checkOneFileUpload(this,'pdf,doc,docx',false,'','','','','','','')" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Upload attachment" /></td>
    </tr>
  </table>
  <p>
    <input type="hidden" name="up_id" value="" />
    <input type="hidden" name="item_id" value="<?php echo $colname_attach = $_GET['item_id']; ?>" />
    <input type="hidden" name="up_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?>" />
    <input type="hidden" name="up_type" value="Attachment" />
    <input type="hidden" name="MM_insert" value="form1" />
  </p>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($attach);

mysql_free_result($item);
?>
