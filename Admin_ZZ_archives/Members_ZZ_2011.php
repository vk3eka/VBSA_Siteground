<?php 
function tfm_cleanOrderBy($theValue, $defaultSort) {
	if (preg_match("/^[\w,]{1,50}\s+(asc|desc)\s*$/i",$theValue, $matches)) {
		return $matches[0];
	}
	return $defaultSort;
}
?>
<?php require_once('../Connections/connvbsa.php'); ?><?php
$tfm_orderby =(!isset($_GET["tfm_orderby"]))?"LastName":$_GET["tfm_orderby"];
$tfm_order =(!isset($_GET["tfm_order"]))?"ASC":$_GET["tfm_order"];
$sql_orderby = $tfm_orderby." ".$tfm_order;
$sql_orderby = tfm_cleanOrderBy($sql_orderby, "LastName");
?>
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
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$currentPage = $_SERVER["PHP_SELF"];

//TOMLR Special List Recordset
// Defining List Recordset variable
$sqlorderby_memb = "LastName";
if (isset($sql_orderby)) {
  $sqlorderby_memb = $sql_orderby;
}
mysql_select_db($database_connvbsa, $connvbsa);

$query_memb = "SELECT MemberID, LastName, FirstName, MobilePhone, Email, Club, Paid, Captain, LastUpdated, UpdateBy, Playing, Pennant, Willis, Billiards FROM XXArchive_members_2011 WHERE XXArchive_members_2011.Paid is not Null ORDER BY {$sqlorderby_memb}";
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);
//End TOMLR Special List Recordset

mysql_select_db($database_connvbsa, $connvbsa);
$query_CountMemb = "SELECT COUNT(Paid) FROM XXArchive_members_2011 WHERE XXArchive_members_2011.Paid is not Null  ";
$CountMemb = mysql_query($query_CountMemb, $connvbsa) or die(mysql_error());
$row_CountMemb = mysql_fetch_assoc($CountMemb);
$totalRows_CountMemb = mysql_num_rows($CountMemb);

$queryString_memb = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_memb") == false && 
        stristr($param, "totalRows_memb") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_memb = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_memb = sprintf("&totalRows_memb=%d%s", $totalRows_memb, $queryString_memb);

?>
<?php
//sort column headers for memb
$tfm_saveParams = explode(",","");
$tfm_keepParams = "";
if($tfm_order == "ASC") {
	$tfm_order = "DESC";
}else{
	$tfm_order = "ASC";
};
while (list($key,$val) = each($tfm_saveParams)) {
	if(isset($_GET[$val]))$tfm_keepParams .= ($val)."=".urlencode($_GET[$val])."&";	
	if(isset($_POST[$val]))$tfm_keepParams .= ($val)."=".urlencode($_POST[$val])."&";
}
$tfm_orderbyURL = $_SERVER["PHP_SELF"]."?".$tfm_keepParams."tfm_order=".$tfm_order."&tfm_orderby=";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="715" align="center">
  <tr>
    <td width="264">Total 2011 Members</td>
      <td width="146"><?php echo $totalRows_memb ?></td>
      <td width="157">Total Financial Members</td>
      <td width="128"><?php echo $row_CountMemb['COUNT(Paid)']; ?></td>
    </tr>
</table>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
</table>
<tr>
    <td width="2500"><table border="1" align="center" class="page">
      <tr>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>MemberID">MemberID</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>FirstName">First Name</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>LastName">Last Name</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>MobilePhone">Mobile Phone</a></td>
        <td>Email</td>
        <td>Club</td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>Paid">Paid</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>Captain">Captain</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>LastUpdated">Last Updated</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>UpdateBy">Update By</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>Playing">Playing</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>Pennant">Pennant</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>Willis">Willis</a></td>
        <td><a href="<?Php echo ($tfm_orderbyURL); ?>Billiards">Billiards</a></td>
      </tr>
      <?php do { ?>
      <tr>
        <td><?php echo $row_memb['MemberID']; ?></td>
        <td><?php echo $row_memb['FirstName']; ?></td>
        <td><?php echo $row_memb['LastName']; ?></td>
        <td><a href="tel:<?php echo $row_memb['EMobilePhone']; ?>"><?php echo $row_memb['MobilePhone']; ?></a></td>
        <td><a href="mailto:<?php echo $row_memb['Email']; ?>"><?php echo $row_memb['Email']; ?></a></td>
        <td><?php echo $row_memb['Club']; ?></td>
        <td><?php echo $row_memb['Paid']; ?></td>
        <td><?php echo $row_memb['Captain']; ?></td>
        <td><?php echo $row_memb['LastUpdated']; ?></td>
        <td><?php echo $row_memb['UpdateBy']; ?></td>
        <td><?php echo $row_memb['Playing']; ?></td>
        <td><?php echo $row_memb['Pennant']; ?></td>
        <td><?php echo $row_memb['Willis']; ?></td>
        <td><?php echo $row_memb['Billiards']; ?></td>
      </tr>
      <?php } while ($row_memb = mysql_fetch_assoc($memb)); ?>
    </table>
</body>
</html>
<?php

?>
