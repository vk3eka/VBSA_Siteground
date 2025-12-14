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
$query_RP_weekly = "SELECT ranknum, memb_id, FirstName, LastName, total_weekly_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_weekly LEFT JOIN members ON MemberID = memb_id WHERE total_weekly_rp>0 ORDER BY ranknum";

$RP_weekly = mysql_query($query_RP_weekly, $connvbsa) or die(mysql_error());
$row_RP_weekly = mysql_fetch_assoc($RP_weekly);
$totalRows_RP_weekly = mysql_num_rows($RP_weekly);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

<table align="center">
  <tr>
    <td align="left" class="red_bold">&nbsp;</td>
    <td align="right" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="red_bold">
      Rankings for Pennant, Willis &amp; State Grade Snooker      </td>
    <td align="right" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
  <tr>
    <td colspan="2" align="left">This list will update every time weekly competition is played, it lists all players that have ranking points &gt;0. </td>
    </tr>
  </table>
  <center>
    <table align="center" cellpadding="3" cellspacing="3">
      <tr>
        <td align="center">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">ID</td>
        <td align="left">&nbsp;</td>
        <td align="left">First Name</td>
        <td align="left">Last Name</td>
        <td align="center">Total RP</td>
        <td align="center">Ranked</td>
        <td align="center">&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_RP_weekly['memb_id']; ?></td>
          <td align="left">&nbsp;</td>
          <td align="left"><?php echo $row_RP_weekly['FirstName']; ?></td>
          <td align="left"><?php echo $row_RP_weekly['LastName']; ?></td>
          <td align="center"><?php echo $row_RP_weekly['total_weekly_rp']; ?></td>
          <td align="center"><?php echo $row_RP_weekly['ranknum']; ?></td>
          <td align="center" class="page"><a href="rankings_snooker_weekly_detail.php?rank=<?php echo $row_RP_weekly['memb_id']; ?>">how awarded</a></td>
      </tr>
        <?php } while ($row_RP_weekly = mysql_fetch_assoc($RP_weekly)); ?>
</table>
</body>
</html>
<?php

?>