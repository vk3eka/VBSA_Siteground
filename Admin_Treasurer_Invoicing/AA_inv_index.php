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
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?><?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once('../Connections/connvbsa.php'); ?><?php
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch_treas.php';?>
<table width="1000" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table border="1" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td>Search all invoices by business title (Invoice To):</td>
    <td><form id="form" name="form2" method="get" action="xx_search_res.php">
      <input name="INVsearch" type="text" id="INVsearch" size="24" />
      <input type="submit" name="checkinv" id="checkinv" value="Search Invoices" />
    </form></td>
    <td class="greenbg"><a href="../Admin_update_tables/UpdateInvoiceTables.php">Recalculate the Invoice tables</a> </td>
  </tr>
</table>
<table width="1000" border="0" align="center">
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>    
    <td align="center">Treasurers work area - access by the treasurer and webmaster only. View, print, issue, edit or create invoices.</td>
  </tr>
  <tr>
    <td align="left" class="red_bold">&nbsp;</td>
  </tr>
</table>

  <table width="1000" align="center" class="greenbg_menu">
    <tr>
      <td width="288" align="left">Current &amp; Previous Year Invoices</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left"><a href="inv_vbsa_curyear.php?inv_type=S1"><?php echo date("Y")?> VBSA S1 Invoices</a></td>
      <td>All invoices (S1 <?php echo date("Y")?>) - view, edit, create new, delete or print any invoice.</td>
    </tr>
    <tr>
      <td align="left"><a href="inv_vbsa_curyear.php?inv_type=S2"><?php echo date("Y")?> VBSA S2 Invoices</a></td>
      <td>All invoices (S2 <?php echo date("Y")?>) - view, edit, create new, delete or print any invoice.</td>
    </tr>
    <tr>
      <td align="left"><a href="inv_2year_cityclub.php?inv_type=CityClub"><?php echo date("Y")?> or <?php echo date("Y")-1 ?>  City Clubs</a></td>
      <td>All <?php echo date("Y"); ?> invoices - City Clubs - view, edit, create new, delete or print any invoice.</td>
    </tr>
    <tr>
      <td align="left"><a href="inv_2year_association.php?inv_type=Association"> Associations</a></td>
      <td>All Association invoices - view, edit, create new, delete or print any invoice.</td>
    </tr>
    <tr>
      <td align="left"><a href="inv_2year_other.php?inv_type=Other">&quot;Other&quot;</a></td>
      <td>All invoices - &quot;Other&quot;, Not S1,S2,City Club or Association - view, edit, create new, delete or print any invoice.</td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left">Invoices by status</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left"><a href="inv_x_outstanding.php">Outstanding</a></td>
      <td> Unpaid or part paid invoices- view, edit, create, delete or print any invoice. Ordered by Invoice ID (newest first) </td>
    </tr>
    <tr>
      <td align="left"><a href="inv_x_unsent.php">Unsent</a></td>
      <td> Unsent invoices- view, edit, create, delete or print any invoice. Ordered by Invoice ID (newest first) </td>
    </tr>
    <tr>
      <td align="left"><a href="inv_x_bad_debt.php">Bad Debt</a></td>
      <td> Bad Debt - Written off invoices- view, edit, create, delete or print any invoice. Ordered by Invoice ID (newest first) </td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left">Invoice History</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left"><a href="inv_y_all_prev_year.php">All Invoices - Previous year - Ordered Alphabetically</a></td>
      <td>All invoices, previous year - view, edit, delete or print any invoice.</td>
    </tr>
    <tr>
      <td align="left"><a href="inv_y_all_sortbytitle.php">All Invoices - Ordered Alphabetically</a></td>
      <td>All invoices - view, edit, create, delete or print any invoice.</td>
    </tr>
    <tr>
      <td align="left"><a href="inv_y_all_sortbyid.php">All Invoices - Ordered by Number (newest first)</a></td>
      <td>All invoices - view, edit, create, delete or print any invoice.</td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
</table>
<table width="1000" align="center" class="page">
  <tr>
    <td align="center">If there is a view that is not listed that would suit your purpose please let me know <a href="mailto:web@vbsa.org.au">web@vbsa.org.au</a></td>
  </tr>
</table>
</body>
</html>
<?php
?>