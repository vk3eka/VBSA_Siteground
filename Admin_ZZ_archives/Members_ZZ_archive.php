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

$colname_members = "-1";
if (isset($_GET['finmemb'])) {
  $colname_members = $_GET['finmemb'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_members = sprintf("SELECT members.MemberID, LastName, FirstName, MobilePhone, Email, Club, Junior, Female, Fin_ID, Paid, members.LifeMember, DatePaid, HowMembPaid, memb_cal_year FROM XXmemb_fin_2013_15  LEFT OUTER JOIN members ON members.MemberID = XXmemb_fin_2013_15.Fin_ID WHERE memb_cal_year = %s OR memb_cal_year IS NULL GROUP BY Fin_ID", GetSQLValueString($colname_members, "date"));
$members = mysql_query($query_members, $connvbsa) or die(mysql_error());
$row_members = mysql_fetch_assoc($members);
$totalRows_members = mysql_num_rows($members);$colname_members = "-1";
if (isset($_GET['finmemb'])) {
  $colname_members = $_GET['finmemb'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_members = sprintf("SELECT members.MemberID, LastName, FirstName, MobilePhone, Email, Club, Junior, Female, Fin_ID, Paid, members.LifeMember, DatePaid, HowMembPaid, memb_cal_year FROM XXmemb_fin_2013_15  LEFT OUTER JOIN members ON members.MemberID = XXmemb_fin_2013_15.Fin_ID WHERE memb_cal_year = %s OR memb_cal_year IS NULL GROUP BY Fin_ID ORDER BY members.Club, FirstName", GetSQLValueString($colname_members, "date"));
$members = mysql_query($query_members, $connvbsa) or die(mysql_error());
$row_members = mysql_fetch_assoc($members);
$totalRows_members = mysql_num_rows($members);

$colname_TotInc = "-1";
if (isset($_GET['finmemb'])) {
  $colname_TotInc = $_GET['finmemb'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_TotInc = sprintf("SELECT SUM(Paid) AS TotalIncome FROM XXmemb_fin_2013_15 WHERE memb_cal_year = %s", GetSQLValueString($colname_TotInc, "date"));
$TotInc = mysql_query($query_TotInc, $connvbsa) or die(mysql_error());
$row_TotInc = mysql_fetch_assoc($TotInc);
$totalRows_TotInc = mysql_num_rows($TotInc);

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

<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
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
    <td colspan="2" align="center" class="red_bold" nowrap="nowrap">All Financial Members for the  <?php echo $row_members['memb_cal_year']; ?> Calendar Year</td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td>Total Members: <?php echo $totalRows_members ?></td>
    <td>&nbsp;</td>
    <td>Total Income from Memberships: $<?php echo $row_TotInc['TotalIncome']; ?></td>
  </tr>
</table>
<table border="1" align="center" cellpadding="2" cellspacing="2" class="page">
  <tr>
    <td align="center">ID</td>
    <td>First Name</td>
    <td>Last Name</td>
    <td>Mobile Phone</td>
    <td>Email</td>
    <td>Club</td>
    <td align="center">Junior</td>
    <td align="center">Female</td>
    <td align="center" nowrap="nowrap">Life Member</td>
    <td align="center">Paid</td>
    <td>Date Paid</td>
    <td>How Paid</td>
    <td>Year</td>
  </tr>
  <?php do { ?>
  <tr>
    <td align="center"><?php echo $row_members['MemberID']; ?></td>
    <td><?php echo $row_members['FirstName']; ?></td>
    <td><?php echo $row_members['LastName']; ?></td>
    <td><a href="tel:<?php echo $row_members['MobilePhone']; ?>"><?php echo $row_members['MobilePhone']; ?></a></td>
    <td><a href="mailto:<?php echo $row_members['Email']; ?>"><?php echo $row_members['Email']; ?></a></td>
    <td><?php echo $row_members['Club']; ?></td>
    <td align="center"><?php echo $row_members['Junior']; ?></td>
    <td align="center"><?php echo $row_members['Female']; ?></td>
    <td align="center">
		  <?php
          if ($row_members['LifeMember']==1)
          {
		  echo "Yes";
		  }
		  else
		  echo "";
          ?>
    </td>
    <td align="center"><?php echo $row_members['Paid']; ?></td>
    <td><?php echo $row_members['DatePaid']; ?>
          <?php
          if ($row_members['LifeMember']<>"")
          {
		  echo "";
		  }
		  else
		  echo "";
          ?>
    </td>
    <td><?php echo $row_members['HowMembPaid']; ?></td>
    <td><?php echo $row_members['memb_cal_year']; ?></td>
  </tr>
  <?php } while ($row_members = mysql_fetch_assoc($members)); ?>
</table>
</body>
</html>
<?php

?>
