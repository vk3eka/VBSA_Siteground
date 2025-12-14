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
$query_memb = "SELECT members.MemberID, FirstName, LastName, Club, MobilePhone, Email, paid_memb, LifeMember,  totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, totplayed_prev+totplaybill_prev AS Prev, totplayed_prev AS PSnooker, totplaybill_prev AS PBilliards FROM members WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW( ) )) OR LifeMember=1 OR totplayed_curr+totplaybill_curr>3 OR totplayed_prev+totplaybill_prev>3) AND (MemberID != 1 AND MemberID != 1000) ORDER BY paid_memb DESC, LifeMember DESC, FirstName, LastName";
$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Count20 = "SELECT COUNT(paid_memb) FROM members WHERE paid_memb>0 AND YEAR(paid_date)=YEAR(NOW( ) )";
$Count20 = mysql_query($query_Count20, $connvbsa) or die(mysql_error());
$row_Count20 = mysql_fetch_assoc($Count20);
$totalRows_Count20 = mysql_num_rows($Count20);

mysql_select_db($database_connvbsa, $connvbsa);
$query_lifemembers = "SELECT MemberID, LifeMember FROM members WHERE LifeMember>0";
$lifemembers = mysql_query($query_lifemembers, $connvbsa) or die(mysql_error());
$row_lifemembers = mysql_fetch_assoc($lifemembers);
$totalRows_lifemembers = mysql_num_rows($lifemembers);

mysql_select_db($database_connvbsa, $connvbsa);
$query_paimemb = "SELECT MemberID, paid_memb FROM members WHERE paid_memb=20 AND YEAR(paid_date) = YEAR( CURDATE( ) )";
$paimemb = mysql_query($query_paimemb, $connvbsa) or die(mysql_error());
$row_paimemb = mysql_fetch_assoc($paimemb);
$totalRows_paimemb = mysql_num_rows($paimemb);
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
    <td colspan="2" align="left" nowrap="nowrap">&nbsp;</td>
    <td colspan="2" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left" nowrap="nowrap"><span class="red_bold" >Players that satisfy Membership requirements</span><span class="greenbg">&nbsp;&nbsp;&nbsp; <a href="../Admin_DB_VBSA/user_files/member.php">When is a person considered a member?</a></span></td>
    <td colspan="2" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="4" class="red_text"><em>Note: Matches in Previous year will reset to 0 when the current year month is &gt;6 as per membership requirements</em></td>
  </tr>
  <tr>
    <td>Total Members: <?php echo $totalRows_memb ?></td>
    <td>Total Life Members: <?php echo $totalRows_lifemembers ?></td>
    <td colspan="2">Total Paid Members: <?php echo $totalRows_paimemb ?></td>
  </tr>
</table>

<p>&nbsp;</p>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left" nowrap="nowrap">&nbsp;</td>
    <td align="left" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td colspan="3" align="center">Matches in Current year</td>
    <td colspan="3" align="center" bgcolor="#CCCCCC">Matches in Previous Year</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">ID</td>
    <td align="left">First Name</td>
    <td align="left">Last Name</td>
    <td align="left">Club</td>
    <td align="left" nowrap="nowrap">Mobile Phone</td>
    <td align="left" nowrap="nowrap">Email</td>
    <td align="center" nowrap="nowrap">Life Member</td>
    <td align="center">Paid</td>
    <td align="center">Total</td>
    <td align="center">Snooker</td>
    <td align="center">Billiards</td>
    <td align="center" bgcolor="#CCCCCC">Total</td>
    <td align="center" bgcolor="#CCCCCC">Snooker</td>
    <td align="center" bgcolor="#CCCCCC">Billiards</td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr class="page">
      <td align="center"><?php echo $row_memb['MemberID']; ?></td>
      <td align="left"><?php echo $row_memb['FirstName']; ?></td>
      <td align="left"><?php echo $row_memb['LastName']; ?></td>
      <td align="left"><?php echo $row_memb['Club']; ?></td>
      <td><a href="tel:<?php echo $row_memb['MobilePhone']; ?>"><?php echo $row_memb['MobilePhone']; ?></a></td>
      <td><a href="mailto:<?php echo $row_memb['Email']; ?>" target="_blank"><?php echo $row_memb['Email']; ?></a></td>
      <td align="center"><?php if($row_memb['LifeMember']==1)
	  echo "Yes";
	  else
	  echo ""; 
	  ?></td>
      <td align="center"><?php echo $row_memb['paid_memb']; ?></td>
      <td align="center"><?php echo $row_memb['Current']; ?></td>
      <td align="center"><?php echo $row_memb['CSnooker']; ?></td>
      <td align="center"><?php echo $row_memb['CBilliards']; ?></td>
      <td align="center" bgcolor="#CCCCCC"><?php echo $row_memb['Prev']; ?></td>
      <td align="center" bgcolor="#CCCCCC"><?php echo $row_memb['PSnooker']; ?></td>
      <td align="center" bgcolor="#CCCCCC"><?php echo $row_memb['PBilliards']; ?></td>
      <td align="center"><a href="ajax/Treas_member_edit.php?membedit=<?php echo $row_memb['MemberID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" /> </a></td>
    </tr>
    <?php } while ($row_memb = mysql_fetch_assoc($memb)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($memb);

mysql_free_result($Count20);

mysql_free_result($TotInc);

mysql_free_result($lifemembers);

mysql_free_result($paimemb);

mysql_free_result($memb);
?>
