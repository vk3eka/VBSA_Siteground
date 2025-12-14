<?php require_once('../Connections/connvbsa.php'); ?>
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
$query_memb = "SELECT MemberID, FirstName, LastName, Club, MobilePhone, Email, paid_memb, LifeMember, totplayed_curr, totplayed_prev FROM members WHERE  LastName is not null ORDER BY LastName";
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
<?php include '../admin_xx_includes/db_srch_treas.php';?>
<table width="1000" align="center">
  <tr>
    <td colspan="2" align="center" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" nowrap="nowrap"><span class="red_bold">All persons entered in the members table that have  a Last Name - Ordered by Last Name</span></td>
  </tr>
  <tr>
    <td align="center" nowrap="nowrap"><span class="greenbg">&nbsp;&nbsp;&nbsp;</span>Total Entries: <?php echo $totalRows_memb ?></td>
    <td align="right" nowrap="nowrap"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td align="center" class="greenbg"><a href="../Admin_DB_VBSA/user_files/member.php">When is a person considered a member?</a> </td>
    <td align="center" class="greenbg"><a href="ajax/Treas_member_insert.php">Insert a New Person into the members table</a> </td>
  </tr>
</table>

<p>&nbsp;</p>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="center"><strong>ID</strong></td>
    <td align="left"><strong>Last Name</strong></td>
    <td align="left"><strong>First Name</strong></td>
    <td align="left"><strong>Club</strong></td>
    <td align="left" nowrap="nowrap"><strong>Mobile Phone</strong></td>
    <td align="left" nowrap="nowrap"><strong>Email</strong></td>
    <td align="center" nowrap="nowrap"><strong>Life Member</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr class="page">
      <td align="center"><?php echo $row_memb['MemberID']; ?></td>
      <td align="left"><strong><?php echo $row_memb['LastName']; ?></strong></td>
      <td align="left"><?php echo $row_memb['FirstName']; ?></td>
      <td align="left"><?php echo $row_memb['Club']; ?></td>
      <td><a href="tel:<?php echo $row_memb['MobilePhone']; ?>"><?php echo $row_memb['MobilePhone']; ?></a></td>
      <td><a href="mailto:<?php echo $row_memb['Email']; ?>" target="_blank"><?php echo $row_memb['Email']; ?></a></td>
      <td align="center"><?php if($row_memb['LifeMember']==1)
	  echo "Yes";
	  else
	  echo ""; 
	  ?></td>
      <td align="center"><a href="ajax/Treas_member_edit.php?membedit=<?php echo $row_memb['MemberID']; ?>" ><img src="../Admin_Images/edit_butt.fw.png" width="24" /></a></td>
    </tr>
    <?php } while ($row_memb = mysql_fetch_assoc($memb)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($Count20);

mysql_free_result($TotInc);

mysql_free_result($lifemembers);

mysql_free_result($paimemb);

mysql_free_result($memb);
?>
