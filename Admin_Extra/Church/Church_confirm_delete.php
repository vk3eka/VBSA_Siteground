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

$colname_Churchdel = "-1";
if (isset($_GET['del'])) {
  $colname_Churchdel = $_GET['del'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Churchdel = sprintf("SELECT * FROM Church WHERE Church_id = %s", GetSQLValueString($colname_Churchdel, "int"));
$Churchdel = mysql_query($query_Churchdel, $connvbsa) or die(mysql_error());
$row_Churchdel = mysql_fetch_assoc($Churchdel);
$totalRows_Churchdel = mysql_num_rows($Churchdel);
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
<form id="form1" name="form1" method="post" action="">
  <table width="982" align="center">
    <tr>
      <td width="169" class="page">&nbsp;</td>
      <td colspan="3" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td>ID</td>
      <td colspan="3"><?php echo $row_Churchdel['Church_id']; ?></td>
    </tr>
    <tr>
      <td>Type</td>
      <td colspan="3">
      <?php 
	  if ($row_Churchdel['Church_type'] =="a_info")
	  {echo "Information";}
	  elseif ($row_Churchdel['Church_type'] =="b_news") 
      {echo "News Item";}
	  elseif ($row_Churchdel['Church_type'] =="c_zone1") 
      {echo "Zone 1";}
	  elseif ($row_Churchdel['Church_type'] =="d_zone2") 
      {echo "Zone 2";}
	  elseif ($row_Churchdel['Church_type'] =="e_zone3") 
      {echo "Zone 3";}
	  elseif ($row_Churchdel['Church_type'] =="f_history") 
      {echo "History";}	  
	  ?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="4"><p class="page"><span class="red_bold">IF YOU PROCEED THIS RECORD WILL BE PERMANENTLY DELETED FROM THE DATABASE, </span><span class="red_bold">YOU CANNOT UNDO THIS ACTION</span></p>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3" class="page">&nbsp;</td>
    </tr>
    <tr>
      <td>Do you wish to proceed?</td>
      <td width="69"><a href="../Church/Church_delete.php?del=<?php echo $row_Churchdel['Church_id']; ?>"><img src="../../Admin_Images/Yes.jpg" class="greenbg" /></a></td>
      <td width="61"><a href="../Church/Church_index_admin.php"><img src="../../Admin_Images/No.jpg" width="50" height="15" class="greenbg" /></a></td>
      <td width="663">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Churchdel);
?>
