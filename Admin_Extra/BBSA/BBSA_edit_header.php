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

$MM_restrictGoTo = "../../page_error_extra.php";
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
  $updateSQL = sprintf("UPDATE BBSA SET pagezone_header_desc=%s, pagezone_header=%s, item_title=%s, news_content=%s WHERE BBSA_id=%s",
                       GetSQLValueString($_POST['pagezone_header_desc'], "text"),
                       GetSQLValueString($_POST['pagezone_header'], "text"),
					   GetSQLValueString($_POST['item_title'], "text"),
					   GetSQLValueString($_POST['news_content'], "text"),
                       GetSQLValueString($_POST['BBSA_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "BBSA_index_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_BBSAedit = "SELECT BBSA_id,  item_title, news_content, pagezone_header_desc, pagezone_header FROM BBSA WHERE BBSA_id = 8";
$BBSAedit = mysql_query($query_BBSAedit, $connvbsa) or die(mysql_error());
$row_BBSAedit = mysql_fetch_assoc($BBSAedit);
$totalRows_BBSAedit = mysql_num_rows($BBSAedit);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td align="left" nowrap="nowrap" class="red_bold">&nbsp;</td>
      <td align="right" nowrap="nowrap" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td align="left" nowrap="nowrap" class="red_bold">Edit &quot;Page headings&quot;</td>
      <td align="left" nowrap="nowrap" class="red_bold">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="left" nowrap="nowrap">Note: please ensure your page reads correctly both on your desktop and your phone or other devices</td>
    </tr>
    <tr>
      <td align="right">Title (Large Page):</td>
      <td><input type="text" name="item_title" value="<?php echo htmlentities($row_BBSAedit['item_title'], ENT_COMPAT, 'utf-8'); ?>" size="60" /></td>
    </tr>
    <tr>
      <td align="right">Subtitle (Large Page):</td>
      <td><input name="news_content" type="text" value="<?php echo htmlentities($row_BBSAedit['news_content'], ENT_COMPAT, 'utf-8'); ?>" size="80" /></td>
    </tr>
    <tr>
      <td align="right">Title (Small Page):</td>
      <td><input type="text" name="pagezone_header" value="<?php echo htmlentities($row_BBSAedit['pagezone_header'], ENT_COMPAT, 'utf-8'); ?>" size="60" /></td>
    </tr>
    <tr>
      <td align="right">Title Explanation (Small Page)</td>
      <td><input type="text" name="pagezone_header_desc" value="<?php echo htmlentities($row_BBSAedit['pagezone_header_desc'], ENT_COMPAT, 'utf-8'); ?>" size="60" /></td>
    </tr>
    <tr>
      <td align="right">Subtitle (Small Page):</td>
      <td><?php echo $row_BBSAedit['news_content']; ?> Same as Subtitle (Large Page)</td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update Header" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="BBSA_id" value="<?php echo $row_BBSAedit['BBSA_id']; ?>" />
  </form>
<p>&nbsp;</p>

<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($BBSAedit);
?>
