<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

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

$inv_type = "-1";
if (isset($_GET['inv_type'])) {
  $inv_type = $_GET['inv_type'];
}

$club_id = "-1";
if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_multi_cont = "SELECT cont_id, club_id, cont_memb_id, FirstName, LastName, Email, MobilePhone, cont_type, cont_title, ClubTitle FROM clubs_contact  LEFT JOIN members ON MemberID = cont_memb_id LEFT JOIN clubs ON ClubNumber = club_id WHERE club_id='$club_id'";
$multi_cont = mysql_query($query_multi_cont, $connvbsa) or die(mysql_error());
$row_multi_cont = mysql_fetch_assoc($multi_cont);
$totalRows_multi_cont = mysql_num_rows($multi_cont);
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
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />


</head>

<body>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table border="1" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<table align="center">
  <tr>
    <td><?php echo $_SESSION['inv_page']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="red_text"><strong><?php echo $row_multi_cont['ClubTitle']; ?> has more than 1 "Invoice to" contacts, please choose</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td>Member ID</td>
    <td>Type</td>
    <td>Name</td>
    <td>Email</td>
    <td>Phone</td>
    <td>Title</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_multi_cont['cont_memb_id']; ?></td>
      <td><?php echo $row_multi_cont['cont_type']; ?></td>
      <td nowrap="nowrap"><?php echo $row_multi_cont['FirstName']; ?> <?php echo $row_multi_cont['LastName']; ?></td>
      <td class="page"><a href="mailto:<?php echo $row_multi_cont['Email']; ?>"><?php echo $row_multi_cont['Email']; ?></a></td>
      <td class="page"><a href="tel:<?php echo $row_multi_cont['MobilePhone']; ?>"><?php echo $row_multi_cont['MobilePhone']; ?></a></td>
      <td><?php echo $row_multi_cont['cont_title']; ?></td>
      <td><a href="user_files/inv_insert_from_inv_to_id.php?club_id=<?php echo $club_id; ?>&inv_type=<?php echo $inv_type; ?>&cont_id=<?php echo $row_multi_cont['cont_id']; ?>"><img src="../Admin_Images/new_doc.fw.png"  height="20" title="Create a new invoice for this Club" /></a></td>
    </tr>
    <?php } while ($row_multi_cont = mysql_fetch_assoc($multi_cont)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($multi_cont);
?>
