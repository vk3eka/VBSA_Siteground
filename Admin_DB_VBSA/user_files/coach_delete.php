<?php require_once('../../Connections/connvbsa.php'); ?>
<?php include('../../security_header.php'); ?>
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

$colname_coaches = "-1";
if (isset($_GET['coach_del'])) {
  $colname_coaches = $_GET['coach_del'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_coaches = sprintf("SELECT MemberID, memb_id, FirstName, LastName, Email, MobilePhone, coach_id, class, comment, URL, coach_order FROM members, coaches_vbsa WHERE members.MemberID = coaches_vbsa.memb_id AND coaches_vbsa.memb_id=%s ORDER BY coach_order", GetSQLValueString($colname_coaches, "int"));
$coaches = mysql_query($query_coaches, $connvbsa) or die(mysql_error());
$row_coaches = mysql_fetch_assoc($coaches);
$totalRows_coaches = mysql_num_rows($coaches);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>

<body>

<div id="DBheader"></div>
<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td width="805" align="center" class="red_bold">DELETE A COACH FROM THE ACCREDITED COACHES LIST- WARNING When deleted cannot be recovered</td>
    <td width="158" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<div id="DBcontent">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">Member ID:</td>
      <td colspan="2"><?php echo $row_coaches['memb_id']; ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">Name</td>
      <td colspan="2"><?php echo $row_coaches['FirstName']; ?> <?php echo $row_coaches['LastName']; ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="4" align="center" nowrap="nowrap"><span class="red_bold">Delete this Coach</span></td>
    </tr>
    <tr valign="baseline">
      <td colspan="4" align="center" nowrap="nowrap" class="red_text">This person will be deleted from the Accredited Coaches page on the website. Personal details WILL NOT be deleted from the members table</td>
    </tr>
    <tr valign="baseline">
      <td width="338" align="right" nowrap="nowrap">Continue?</td>
      <td width="64" align="center" nowrap="nowrap" class="greenbg"><a href="coach_delete_confirm.php?ID=<?php echo $row_coaches['coach_id']; ?>">Yes</a></td>
      <td width="56" align="center" class="greenbg"><a href="../coaches.php">No</a></td>
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
mysql_free_result($coaches);
?>
