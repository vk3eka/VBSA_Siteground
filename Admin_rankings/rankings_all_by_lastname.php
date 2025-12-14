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
$query_RP_lastname = "SELECT MemberID, LastName, FirstName, rank_S_open_tourn.ranknum AS T_rank, rank_S_open_weekly.ranknum AS W_rank  FROM members LEFT JOIN rank_S_open_tourn ON rank_S_open_tourn.memb_id = MemberID LEFT JOIN rank_S_open_weekly ON rank_S_open_weekly.memb_id = MemberID  WHERE (rank_S_open_tourn.total_tourn_rp>0 OR rank_S_open_weekly.total_weekly_rp>0) ORDER BY LastName";
$RP_lastname = mysql_query($query_RP_lastname, $connvbsa) or die(mysql_error());
$row_RP_lastname = mysql_fetch_assoc($RP_lastname);
$totalRows_RP_lastname = mysql_num_rows($RP_lastname);
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
      All players that are ranked in either Tournament or Weekly rankings </td>
    <td align="right" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
  <tr>
    <td colspan="2" align="left">Ordered by last name</td>
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
      </tr>
      <tr>
        <td align="center">ID</td>
        <td align="left">&nbsp;</td>
        <td align="left">Last Name</td>
        <td align="left">First Name</td>
        <td align="center">Weekly rank</td>
        <td align="center">Tournament Rank</td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_RP_lastname['MemberID']; ?></td>
          <td align="left">&nbsp;</td>
          <td align="left"><?php echo $row_RP_lastname['LastName']; ?></td>
          <td align="left"><?php echo $row_RP_lastname['FirstName']; ?></td>
          <td align="center"><?php echo $row_RP_lastname['W_rank']; ?></td>
          <td align="center"><?php echo $row_RP_lastname['T_rank']; ?></td>
      </tr>
        <?php } while ($row_RP_lastname = mysql_fetch_assoc($RP_lastname)); ?>
</table>
</body>
</html>
<?php

?>