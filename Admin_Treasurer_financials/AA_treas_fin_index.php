<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer";
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
$query_fin1 = "SELECT financials.ID, financials.fin_year, fin_year+1 AS year2 FROM financials GROUP BY financials.fin_year";
$fin1 = mysql_query($query_fin1, $connvbsa) or die(mysql_error());
$row_fin1 = mysql_fetch_assoc($fin1);
$totalRows_fin1 = mysql_num_rows($fin1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_fin_cal_year = "SELECT financials.ID, financials.cal_year FROM financials GROUP BY financials.cal_year";
$fin_cal_year = mysql_query($query_fin_cal_year, $connvbsa) or die(mysql_error());
$row_fin_cal_year = mysql_fetch_assoc($fin_cal_year);
$totalRows_fin_cal_year = mysql_num_rows($fin_cal_year);
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

<link href="../Admin_xx_CSS/Financials.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table align="center">
  <tr>
    <td class="red_bold">All Financials - This page is under construction</td>
  </tr>
  <tr>
    <td  class="greenbg"><a href="fin_exp_insert.php">Insert a Miscellaneous item</a></td>
  </tr>
</table>

<table width="600" align="center">
  <tr>
    <td>
    

<!--Nested Table Fin Year -->
	<table align="center" cellpadding="5">
      <tr>
      		<td>Financial Year</td>
            <td>&nbsp;</td>
      </tr>
	  <?php do { ?>
      <tr>
          	<td>
		  	<?php echo $row_fin1['fin_year']; ?> - <?php echo $row_fin1['year2']; ?>
          	</td>
            <td>
            <img src="../Admin_Images/detail.fw.png" width="20" height="20" title="View all financials for this Financial Year" />
            </td>   
      </tr>
      <?php } while ($row_fin1 = mysql_fetch_assoc($fin1)); ?>
</table>
<!--End Nested Table Fin Year -->
</td>
    <td>
<!--Nested Table Cal Year -->    
    <table align="center" cellpadding="5">
      <tr>
        <td colspan="2">Calendar Year</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_fin_cal_year['cal_year']; ?></td>
          <td><a href="detail_cal_yr.php?cal_year=<?php echo $row_fin_cal_year['cal_year']; ?>">
          
          <img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" title="View all financials for this Calendar Year" /></a></td>
        </tr>
        <?php } while ($row_fin_cal_year = mysql_fetch_assoc($fin_cal_year)); ?>
</table>
<!--End Nested Table Cal Year -->
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($fin1);

mysql_free_result($fin_cal_year);
?>
