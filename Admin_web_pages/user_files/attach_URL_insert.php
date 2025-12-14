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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO webpage_attach (up_id, up_desc, up_pdf_name, up_event, item_id, up_type) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['up_id'], "int"),
                       GetSQLValueString($_POST['up_desc'], "text"),
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

if (isset($_GET['up_id'])) {
  $up_id = $_GET['up_id'];
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
$query_item = "SELECT ID, Header FROM webpage_items WHERE ID = '$item_id' ";
$item = mysql_query($query_item, $connvbsa) or die(mysql_error());
$row_item = mysql_fetch_assoc($item);
$totalRows_item = mysql_num_rows($item);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
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


  <table align="center" width="800">
    <tr>
      <td align="right">&nbsp;</td>
    </tr>
    <tr>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td class="red_bold" nowrap="nowrap">From this page you may create a link to another URL (an existing web page)</td>
    </tr>
    <tr>
      <td class="red_bold"><span class="red_text">After creating the link the &quot;URL title&quot; will appear at the bottom of the page as a link to another webpage</span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
  
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'',false,'','','','','','','');return document.MM_returnValue">
  <table align="center" width="800">
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap" class="red_text">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap" class="red_bold">Create a URL link for: <?php echo $row_item['Header']; ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap" class="red_text">Please create a brief title for your URL link (Max 35 characters)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">URL title:</td>
      <td><input type="text" name="up_desc" value="" size="32" />
        eg Visit the ABSC Site for results</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">URL:</td>
      <td><input type="text" name="up_pdf_name" value="" size="32" />
      Copy and Paste the URL from your browser eg.http://www.absc.com.au/</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Create URL Link" /></td>
    </tr>
  </table>
      <input type="hidden" name="up_id" value="" />
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
    <input type="hidden" name="up_type" value="URL" />
    <input type="hidden" name="MM_insert" value="form1" />
</form>
<table align="center">
  <tr>
      <td>&nbsp;</td>
  </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td> To copy a URL from your browser - the link MUST include the prefix &quot;http://www.&quot; or it will not work</td>
    </tr>
    <tr>
      <td><img src="../../Admin_Images/URL.fw.png" width="879" height="276" /></td>
    </tr>
</table>


<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($attach);

mysql_free_result($item);
?>
