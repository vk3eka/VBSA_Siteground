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

$colname_teamdet = "-1";
if (isset($_GET['team_id'])) {
  $colname_teamdet = $_GET['team_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_teamdet = sprintf("SELECT scrs.MemberID, FirstName, LastName, team_grade, team_id, captain_scrs, current_year_scrs FROM scrs, members WHERE scrs.MemberID=members.MemberID AND  team_id = %s", GetSQLValueString($colname_teamdet, "int"));
$teamdet = mysql_query($query_teamdet, $connvbsa) or die(mysql_error());
$row_teamdet = mysql_fetch_assoc($teamdet);
$totalRows_teamdet = mysql_num_rows($teamdet);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_Scores_S1/facebox/facebox.css" rel="stylesheet" type="text/css" />
</head>

<body>
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
mysql_free_result($teamdet);
?>
