<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer,Secretary,Administrator";
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
?><?php
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
  $insertSQL = sprintf("INSERT INTO links (link_id, url, link_title, link_order, visible) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['link_id'], "int"),
                       GetSQLValueString($_POST['url'], "text"),
                       GetSQLValueString($_POST['link_title'], "text"),
                       GetSQLValueString($_POST['link_order'], "text"),
                       GetSQLValueString(isset($_POST['visible']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../links_index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>

<table align="center" cellpadding="2">
  <tr>
    <td class="red_bold">INSERT A NEW LINK</td>
    <td align="right" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2">
      <p>If you set visble to &quot;No&quot; link will not appear on the site</p>
      <p>By deleting a link you remove it completely - it cannot be restored</p>
      <p>The first 10 links can be ordered.</p>
      <p>Links will be sorted by order then by title alphabetically</p>
    </td>
  </tr>
  </table>
 
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" >
    <table align="center">
      <tr>
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap="nowrap" align="right">Create a title for the link</td>
        <td><input type="text" name="link_title" value="" size="70" /></td>
      </tr>
      <tr>
        <td nowrap="nowrap" align="right">Copy URL from browser</td>
        <td><input type="text" name="url" value="" size="70" /></td>
      </tr>
      <tr>
        <td nowrap="nowrap" align="right">Set the link order of appearance in list</td>
        <td><select name="link_order">
          <option value="11" <?php if (!(strcmp(11, ""))) {echo "SELECTED";} ?>>Not Set</option>
          <option value="01" <?php if (!(strcmp(01, ""))) {echo "SELECTED";} ?>>01</option>
          <option value="02" <?php if (!(strcmp(02, ""))) {echo "SELECTED";} ?>>02</option>
          <option value="03" <?php if (!(strcmp(03, ""))) {echo "SELECTED";} ?>>03</option>
          <option value="04" <?php if (!(strcmp(04, ""))) {echo "SELECTED";} ?>>04</option>
          <option value="05" <?php if (!(strcmp(05, ""))) {echo "SELECTED";} ?>>05</option>
          <option value="06" <?php if (!(strcmp(06, ""))) {echo "SELECTED";} ?>>06</option>
          <option value="07" <?php if (!(strcmp(07, ""))) {echo "SELECTED";} ?>>07</option>
          <option value="08" <?php if (!(strcmp(08, ""))) {echo "SELECTED";} ?>>08</option>
          <option value="09" <?php if (!(strcmp(09, ""))) {echo "SELECTED";} ?>>09</option>
          <option value="10" <?php if (!(strcmp(10, ""))) {echo "SELECTED";} ?>>10</option>
        </select></td>
      </tr>
      <tr>
        <td nowrap="nowrap" align="right">Checked = Visible on the website:</td>
        <td><input type="checkbox" name="visible" value="" checked="checked" /></td>
      </tr>
      <tr>
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Insert Link" /></td>
      </tr>
    </table>
    <input type="hidden" name="link_id" value="" />
    <input type="hidden" name="MM_insert" value="form1" />
  </form>



</body>
</html>
<?php
mysql_free_result($nextmember);
?>
