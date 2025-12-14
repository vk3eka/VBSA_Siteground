<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

$searchthis = "-1";
if (isset($_GET['searchthis'])) {
  $searchthis = $_GET['searchthis'];
}

$page = "../Admin_DB_VBSA/search_by_LastName.php?searchthis=$searchthis"  ;
$_SESSION['page'] = $page;


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
 
mysql_select_db($database_connvbsa, $connvbsa);
$searchthis = mysql_real_escape_string($searchthis);
$query_History = "SELECT MemberID, LastName, FirstName, HomePhone, WorkPhone, MobilePhone, Email, ReceiveEmail, Club, LastUpdated, UpdateBy, entered_on, memb_by FROM members WHERE LastName LIKE CONCAT('$searchthis','%') ORDER BY LastName ASC";
$History = mysql_query($query_History, $connvbsa) or die(mysql_error());
$row_History = mysql_fetch_assoc($History);
$totalRows_History = mysql_num_rows($History);
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

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

  
  <table width="1000" border="0" align="center">
    <tr>
      <td width="268" align="center">&nbsp;</td>
      <td width="722" align="center">&nbsp;</td>
    </tr>
  </table>
  <table width="1000" border="0" align="center">
    <tr>
      <td colspan="3" align="left" class="red_bold">Search Results by Last Name beginning with "<?php echo $searchthis ?>" </td>
      <td align="right">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" class="greenbg"><a href="../A_common/vbsa_member_insert.php">Insert a new person to the members table</a></td>
      <td align="left" class="greenbg"><a href="user_files/member.php">When is a person considered a member?</a></td>
      <td align="center">Total Entries: <?php echo $totalRows_History ?></td>
      <td align="right" class="greenbg"><a href="A_memb_index.php">Return to Members Index</a></td>
    </tr>
    <tr>
      <td align="left" class="greenbg">&nbsp;</td>
      <td align="left" class="greenbg">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="right">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  </table>
  <script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  </table>
  <table border="1" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td>ID Number</a></td>
      <td>Last Name</a></td>
      <td>First Name</td>
      <td>Mobile Phone</td>
      <td>Email</td>
      <td>Last Updated</td>
      <td>Update By</td>
      <td>Entered on</td>
      <!--<td align="center">Financials</td>-->
      <td align="center">Detail</td>
      <td align="center">Edit</td>
      <td align="center">M'ship data</td>
      <td align="center">Modify Form</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_History['MemberID']; ?></td>
        <td><?php echo $row_History['LastName']; ?></td>
        <td><?php echo $row_History['FirstName']; ?></td>
        <td class="page"><a href="tel:<?php echo $row_History['MobilePhone']; ?>"><?php echo $row_History['MobilePhone']; ?></a></td>
        <td class="page"><a href="mailto:<?php echo $row_History['Email']; ?>" target="_blank"><?php echo $row_History['Email']; ?></a></td>
        <td><?php echo $row_History['LastUpdated']; ?></td>
        <td><?php echo $row_History['UpdateBy']; ?></td>
        <td><?php echo $row_History['entered_on']; ?></td>
        <!--<td nowrap="nowrap"><a href="../Admin_Tournaments/user_files/player_financial_edit.php?memb_id=<?php echo $row_memb['MemberID']; ?>">edit member financials</a></td>-->
        <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_History['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
        <td><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_History['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" title="Edit Personal & Financial"  /></a></td>
        <td align="center" nowrap="nowrap"><?php if(isset($row_History['memb_by'])) echo "<font color=green>Yes</font>"; else echo "<font color=red>No</font>"; ?></td>
        <td align="center" nowrap="nowrap" class="greenbg"><a href="../A_common/vbsa_member_edit_form.php?memb_id=<?php echo $row_History['MemberID']; ?>" title="Insert / update membership form details">Memb</a></td>
      </tr>
      <?php } while ($row_History = mysql_fetch_assoc($History)); ?>
  </table>

</body>
</html>
<?php

?>
