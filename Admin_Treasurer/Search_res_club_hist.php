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

$colname_memb = "-1";
if (isset($_GET['clubhist'])) {
  $colname_memb = $_GET['clubhist'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_memb = sprintf("SELECT MemberID, LastName, FirstName, MobilePhone, members.Email, Club, LifeMember, paid_memb FROM members WHERE Club LIKE %s ORDER BY FirstName", GetSQLValueString($colname_memb . "%", "text"));
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);
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

<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

<!--------- Facebox Starts-------------------------->

<script src="facebox/jquery.js" type="text/javascript"></script>
<link href="facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="facebox/facebox.js" type="text/javascript"></script> 

  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loadingImage : 'facebox/loading.gif',
        closeImage   : 'facebox/closelabel.png'
      })
    })
  </script>
  
<!--------- Facebox Ends-------------------------->



</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch_treas.php';?>
<table width="1000" border="0" align="center">
    <tr>
      <td colspan="3" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="left">Total:<?php echo $totalRows_memb ?></td>
      <td align="center" class="red_bold">You searched Club History for: <?php echo $colname_memb ?></td>
      <td colspan="2" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  </table>

  <table border="1" align="center" cellpadding="1" class="page">
    <tr>
      <td>ID Number</a></td>
      <td>First Name</td>
      <td>Last Name</a></td>
      <td>Mobile Phone</td>
      <td>Email</td>
      <td>Club</td>
      <td align="center">Life Member</td>
      <td align="center">Paid Member?</td>
      <td align="center">&nbsp;</td>
    </tr>
    <?php do { ?>
    <tr>
      <td><?php echo $row_memb['MemberID']; ?></td>
      <td><?php echo $row_memb['FirstName']; ?></td>
      <td><?php echo $row_memb['LastName']; ?></td>
      <td><a href="tel:<?php echo $row_memb['MobilePhone']; ?>"><?php echo $row_memb['MobilePhone']; ?></a></td>
      <td><a href="mailto:<?php echo $row_memb['Email']; ?>" target="_blank"><?php echo $row_memb['Email']; ?></a></td>
      <td><?php echo $row_memb['Club']; ?></td>
      <td align="center"><?php if($row_memb['LifeMember']==1)echo "Yes"; else echo "No";?></td>
      <td align="center"><?php echo $row_memb['paid_memb']; ?></td>
      <td align="center"><a href="ajax/Treas_member_edit.php?membedit=<?php echo $row_memb['MemberID']; ?>" rel="facebox"><img src="../Admin_Images/edit_butt.fw.png" width="18" title="Edit" /></a></td>
      </tr>
    <?php } while ($row_memb = mysql_fetch_assoc($memb)); ?>
  </table>

</body>
</html>
<?php
mysql_free_result($memb);
?>
