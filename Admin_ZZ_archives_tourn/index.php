<?php require_once('../Connections/connvbsa.php'); ?>
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

$MM_restrictGoTo = "../page_error.php";
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_arch_tourn = "SELECT * FROM tourn_archives ORDER BY tourn_archives.arch_order, tournament_ID";
$arch_tourn = mysql_query($query_arch_tourn, $connvbsa) or die(mysql_error());
$row_arch_tourn = mysql_fetch_assoc($arch_tourn);
$totalRows_arch_tourn = mysql_num_rows($arch_tourn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/Archives.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="1000" align="center">
  <tr>
    <td class="red_bold">&nbsp;</td>
    <td colspan="2" class="page">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="red_bold">All tournaments currently listed in the Archives</td>
    <td align="right" class="greenbg"><a href="user_files/tourn_insert.php">Insert another tournament to the archives</a></td>
    <td align="right" class="greenbg"><a href="../Admin_ZZ_archives/AA_Archive_index.php">Return to Archive index</a></td>
  </tr>
  <tr>
    <td class="red_bold">&nbsp;</td>
    <td colspan="2" class="page">&nbsp;</td>
  </tr>
</table>
	<table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <th align="left">Tournament Name</th>
        <th align="left">Ranked?</th>
        <th align="left">Status?</th>
        <th>Ordered?</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <?php do { ?>
        <tr>
          <td align="left"><?php echo $row_arch_tourn['tourn_name']; ?></td>
          <td align="left"><?php echo $row_arch_tourn['ranked']; ?></td>
          <td align="left"><?php echo $row_arch_tourn['status']; ?></td>
          <td><?php echo $row_arch_tourn['arch_order']; ?></td>
          <td><a href="tourn_detail.php?tid=<?php echo $row_arch_tourn['tournament_ID']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" title="View all / Edit / insert" /></a></td>
          <td><a href="user_files/tourn_edit.php?tid=<?php echo $row_arch_tourn['tournament_ID']; ?>"><img src="../Admin_Images/edit_butt.png" height="20" title="Edit about this tournament" /></a></td>
        </tr>
        <?php } while ($row_arch_tourn = mysql_fetch_assoc($arch_tourn)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
