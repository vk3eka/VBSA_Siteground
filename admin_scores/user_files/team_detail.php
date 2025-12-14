<?php require_once('../../Connections/connvbsa.php'); ?>
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

if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_teamdet = "SELECT scrs.MemberID, FirstName, LastName, team_grade, team_id, captain_scrs, current_year_scrs FROM scrs, members WHERE scrs.MemberID=members.MemberID AND  team_id = '$team_id'";
$teamdet = mysql_query($query_teamdet, $connvbsa) or die(mysql_error());
$row_teamdet = mysql_fetch_assoc($teamdet);
$totalRows_teamdet = mysql_num_rows($teamdet);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table align="center">
  <tr>
    <td width="184" class="red_bold">&nbsp;</td>
    <td width="128" class="red_bold">&nbsp;</td>
    <td width="129" class="red_bold">&nbsp;</td>
    <td width="237" class="greenbg">&nbsp;</td>
    <td width="100" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" nowrap="nowrap" class="red_bold">Team Detail for: <?php echo $row_team_entries['team_club']; ?> in season <?php echo $season; ?></td>
    <td class="greenbg" nowrap="nowrap"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center" nowrap="nowrap">Member ID</td>
    <td align="left">Name</td>
    <td align="center">Grade</td>
    <td align="center" nowrap="nowrap">Team ID</td>
    <td align="center" nowrap="nowrap">Captain ?</td>
    <td align="center">Year</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_teamdet['MemberID']; ?></td>
      <td align="left" nowrap="nowrap"><?php echo $row_teamdet['FirstName']; ?> <?php echo $row_teamdet['LastName']; ?></td>
      <td align="center"><?php echo $row_teamdet['team_grade']; ?></td>
      <td align="center"><?php echo $row_teamdet['team_id']; ?></td>
      <td align="center"><?php echo $row_teamdet['captain_scrs']; ?></td>
      <td align="center"><?php echo $row_teamdet['current_year_scrs']; ?></td>
    </tr>
    <?php } while ($row_teamdet = mysql_fetch_assoc($teamdet)); ?>
</table>
</body>
</html>
<?php

?>
