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

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}


$colname_players1 = "-1";
if (isset($_GET['del'])) {
  $colname_players1 = $_GET['del'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_players1 = sprintf("SELECT tourn_entry.ID, members.MemberID, members.LastName, members.FirstName, tourn_entry.tourn_memb_id, tourn_entry.tournament_number, tourn_entry.amount_entry, tourn_entry.entered_by, tourn_entry.how_paid, tourn_entry.seed, tourn_entry.junior_cat, tourn_entry.tourn_date_ent, members.Email, members.MobilePhone, tourn_entry.wcard, tourn_entry.ranked FROM tourn_entry, members WHERE ID = %s AND tourn_entry.tourn_memb_id=members.MemberID", GetSQLValueString($colname_players1, "int"));
$players1 = mysql_query($query_players1, $connvbsa) or die(mysql_error());
$row_players1 = mysql_fetch_assoc($players1);
$totalRows_players1 = mysql_num_rows($players1);

$colname_tourn1 = "-1";
if (isset($_GET['del'])) {
  $colname_tourn1 = $_GET['del'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = sprintf("SELECT tourn_name FROM tourn_entry, tournaments WHERE `ID` =%s AND tourn_entry.tournament_number = tournaments.tourn_id ", GetSQLValueString($colname_tourn1, "int"));
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
<script src="../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td width="805" align="center" class="red_bold">DELETE A PLAYER FROM A TOURNAMENT(Treasurer ONLY) - WARNING When deleted cannot be recovered</td>
    <td width="158" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<div id="DBcontent">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">Member ID:</td>
      <td colspan="2"><?php echo $row_players1['MemberID']; ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">Name</td>
      <td colspan="2"><?php echo $row_players1['FirstName']; ?> <?php echo $row_players1['LastName']; ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">Amount Paid:</td>
      <td colspan="2"><?php echo $row_players1['amount_entry']; ?></td>
</tr>
    <tr valign="baseline">
      <td colspan="4" align="center" nowrap="nowrap"><span class="red_bold">Delete this player from </span><?php echo $row_tourn1['tourn_name']; ?></td>
    </tr>
    <tr valign="baseline">
      <td width="338" align="right" nowrap="nowrap">Continue?</td>
      <td width="64" align="center" nowrap="nowrap" class="greenbg"><a href="player_delete.php?ID=<?php echo $row_players1['ID']; ?>&tourn_id=<?php echo $tourn_id; ?>">Yes</a></td>
      <td width="56" align="center" class="greenbg"><a href="../tournament_detail.php?tourn_id=<?php echo $tourn_id; ?>">No</a></td>
      <td width="293">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="4" align="right" nowrap="nowrap" class="page">&nbsp;</td>
    </tr>
  </table>
</div>

</body>
</html>
<?php
mysql_free_result($players1);

mysql_free_result($tourn1);
?>
