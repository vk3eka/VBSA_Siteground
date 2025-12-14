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

if (isset($_GET['cont_number'])) {
  $cont_number = $_GET['cont_number'];
}

if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

if (isset($_GET['redirect'])) {
  $redirect = $_GET['redirect'];
}




mysql_select_db($database_connvbsa, $connvbsa);
$query_contact = "SELECT * FROM clubs_contact WHERE cont_id ='$cont_number'";
$contact = mysql_query($query_contact, $connvbsa) or die(mysql_error());
$row_contact = mysql_fetch_assoc($contact);
$totalRows_contact = mysql_num_rows($contact);
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
    <td align="center" class="red_bold">DELETE A CLUB CONTACT  (contact will be deleted from the Club Contacts)</td>
  </tr>
</table>
<div id="DBcontent">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">&nbsp;</td>
      <td colspan="2"></td>
    </tr>
    <tr valign="baseline">
      <td colspan="4" align="center" nowrap="nowrap">Delete contact ID number; <?php echo $cont_number ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="4" align="center" nowrap="nowrap" class="black_bld_txt"><span class="red_bold">If you continue this Contact will be permanently deleted from the clubs_contact table (Member details will not be affected)</span></td>
    </tr>
    <tr valign="baseline">
      <td width="338" align="right" nowrap="nowrap">&nbsp;</td>
      <td width="64" align="center" nowrap="nowrap" class="greenbg"><a href="contact_delete.php?cont_number=<?php echo $row_contact['cont_id']; ?>&club_id=<?php echo $row_contact['club_id']; ?>&redirect=<?php echo $redirect; ?>">Yes</a></td>
      <td width="56" align="center" class="greenbg">&nbsp;</td>
      <td width="293"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr valign="baseline">
      <td colspan="4" align="right" nowrap="nowrap" class="page">&nbsp;</td>
    </tr>
  </table>
</div>

</body>
</html>
<?php
mysql_free_result($contact);
?>
