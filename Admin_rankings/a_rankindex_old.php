<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer,Administrator,Secretary";
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

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    //$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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

mysql_select_db($database_connvbsa, $connvbsa);
$query_rank_bill = "SELECT * FROM rank_info WHERE rank_exp_type='Billiards' ORDER BY rank_exp_type, rank_exp_order";
$rank_bill = mysql_query($query_rank_bill, $connvbsa) or die(mysql_error());
$row_rank_bill = mysql_fetch_assoc($rank_bill);
$totalRows_rank_bill = mysql_num_rows($rank_bill);

mysql_select_db($database_connvbsa, $connvbsa);
$query_snooker_update = "SELECT MAX( last_update) AS lastupdate FROM rank_aa_snooker_master";
$snooker_update = mysql_query($query_snooker_update, $connvbsa) or die(mysql_error());
$row_snooker_update = mysql_fetch_assoc($snooker_update);
$totalRows_snooker_update = mysql_num_rows($snooker_update);

mysql_select_db($database_connvbsa, $connvbsa);
$query_rank_snooker = "SELECT * FROM rank_info WHERE rank_exp_type='Snooker' ORDER BY rank_exp_type, rank_exp_order";
$rank_snooker = mysql_query($query_rank_snooker, $connvbsa) or die(mysql_error());
$row_rank_snooker = mysql_fetch_assoc($rank_snooker);
$totalRows_rank_snooker = mysql_num_rows($rank_snooker);

mysql_select_db($database_connvbsa, $connvbsa);
$query_billiards_update = "SELECT MAX( last_update) AS lastupdate FROM rank_a_billiards_master";
$billiards_update = mysql_query($query_billiards_update, $connvbsa) or die(mysql_error());
$row_billiards_update = mysql_fetch_assoc($billiards_update);
$totalRows_billiards_update = mysql_num_rows($billiards_update);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<center>
<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
    <td colspan=2/>&nbsp;</td>
  </tr>
  <tr>
    <td class="red_bold" align='right'>VICTORIAN RANKINGS</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
  <td class="red_bold">Victorian Snooker Rankings</td>
  </tr>
</table>
<table width="1000" align="center" class="greenbg_menu">
  <tr>
    <td width="300" align="left"><a href="rankings_vic_snooker.php"><?php echo date("Y"); ?> Snooker Rankings (Open)</a></td>
      <td width="10">&nbsp;</td>
      <td width="690">Week to week snooker rankings for all players, Tournament and Pennant.</td>
    </tr>
  <tr>
    <td align="left"><a href="rankings_vic_snooker_women.php"><?php echo date("Y"); ?> Snooker Rankings (Womens)</a></td>
    <td>&nbsp;</td>
    <td>Week to week snooker rankings for women players, Tournament and Pennant.</td>
  </tr>
  <tr>
    <td align="left"><a href="rankings_vic_snooker_junior.php"><?php echo date("Y"); ?> Snooker Rankings (Junior)</a></td>
    <td>&nbsp;</td>
    <td>Week to week snooker rankings for junior players, Tournament and Pennant.</td>
  </tr>
  <tr>
    <td width="300" align="left"><a href="rankings_snooker_weekly.php">Victorian Pennant, Willis and State Grade Snooker Rankings</a></td>
    <td>&nbsp;</td>
    <td>Week to week snooker rankings for all players, Victorian Pennant, Willis and State Grade Snooker.</td>
  </tr>
</table>
<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
    <td class="red_bold">Victorian Billiard Rankings</td>
  </tr>
</table>
<table width="1000" align="center" class="greenbg_menu">
  <tr>
    <td width="300" align="left"><a href="rankings_vic_billiards.php"><?php echo date("Y"); ?> Billiard Rankings (Open)</a></td>
      <td width="10">&nbsp;</td>
      <td width="690">Week to week billiard rankings for all players, Tournament and Pennant.</td>
    </tr>
  <tr>
    <td align="left"><a href="rankings_vic_billiards_womens.php"><?php echo date("Y"); ?> Billiard Rankings (Womens)</a></td>
    <td>&nbsp;</td>
    <td>Week to week billiard rankings for women players, Tournament and Pennant.</td>
  </tr>
  <tr>
    <td align="left"><a href="rankings_vic_billiards_junior.php"><?php echo date("Y"); ?> Billiard Rankings (Juniors)</a></td>
    <td>&nbsp;</td>
    <td>Week to week billiard rankings for junior players, Tournament and Pennant.</td>
  </tr>
</table>
</center>
</div>
</div>
</div>  <!-- close containing wrapper --> 
</body>
</html>
