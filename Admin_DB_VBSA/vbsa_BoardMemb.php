<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); ?>
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
$query_Board = "SELECT id, board_member_id, name, username, vbsaorga_users.email, vbsaorga_users.username, usertype, display, register_year, order_display, `comment`, MemberID, BoardMemb, board_desc, assist FROM vbsaorga_users, members WHERE board_member_id=MemberID AND assist=0 ORDER BY order_display ASC";
$Board = mysql_query($query_Board, $connvbsa) or die(mysql_error());
$row_Board = mysql_fetch_assoc($Board);
$totalRows_Board = mysql_num_rows($Board);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Access = "SELECT id, board_member_id, name, username, vbsaorga_users.email, vbsaorga_users.username, usertype, display, register_year, order_display, `comment`, MemberID, BoardMemb, board_desc, assist FROM vbsaorga_users, members WHERE board_member_id=MemberID AND assist=1 ORDER BY order_display ASC";
$Access = mysql_query($query_Access, $connvbsa) or die(mysql_error());
$row_Access = mysql_fetch_assoc($Access);
$totalRows_Access = mysql_num_rows($Access);

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
<link href="php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

    <table width="1000" align="center" cellpadding="3" cellspacing="3">
      <tr>
        <th scope="col">&nbsp;</th>
        <th scope="col">&nbsp;</th>
      </tr>
      <tr>
        <td><span class="red_text">NOTE: Board Members may only be added, edited or deleted by the Webmaster</span></td>
        <td align="right"> <input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
      </tr>
    </table>
    <table width="1000" align="center" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="5" align="center">&nbsp;</td>
        <td align="right" class="red_bold">&nbsp;</td>
        <td align="right" class="red_bold">&nbsp;</td>
        <td align="right" class="red_bold">&nbsp;</td>
        <td align="right" class="red_bold">&nbsp;</td>
        <td align="right" class="red_bold">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" align="left" valign="middle"><span class="red_bold">Current Board Members </span></td>
        <td align="right" class="red_bold">&nbsp;</td>
        <td align="right" class="red_bold">&nbsp;</td>
        <td align="right" class="red_bold">&nbsp;</td>
        <td align="right" class="red_bold">&nbsp;</td>
        <td align="right" class="red_bold">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">Member ID</td>
        <td>Name</td>
        <td>Position</td>
        <td>Display Email?</td>
        <td>User Type</td>
        <td align="center">Display?</td>
        <td align="center">Assist</td>
        <td align="center">Joined</td>
        <td align="center">Display Order</td>
        <td>&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_Board['board_member_id']; ?></td>
          <td><?php echo $row_Board['name']; ?></td>
          <td><?php echo $row_Board['board_desc']; ?></td>
          <td class="page"><a href="mailto:<?php echo $row_Board['username']; ?>"><?php echo $row_Board['username']; ?></a></td>
          <td><?php echo $row_Board['usertype']; ?></td>
          <td align="center"><?php echo $row_Board['display']; ?></td>
          <td align="center"><?php echo $row_Board['assist']; ?></td>
          <td align="center"><?php echo $row_Board['register_year']; ?></td>
          <td align="center"><?php echo $row_Board['order_display']; ?></td>
          <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_Board['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
        </tr>
        <?php } while ($row_Board = mysql_fetch_assoc($Board)); ?>
</table>
<p>&nbsp;</p>
<table width="1000" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="5" align="left"><span class="red_bold">Assist - Access to database but not a Board Member</span>&nbsp;</td>
    <td align="right" class="red_bold">&nbsp;</td>
    <td align="right" class="red_bold">&nbsp;</td>
    <td align="right" class="red_bold">&nbsp;</td>
    <td align="right" class="red_bold">&nbsp;</td>
    <td align="right" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">Member ID</td>
    <td>Name</td>
    <td>Position</td>
    <td>Display Email?</td>
    <td>User Type</td>
    <td align="center">Display?</td>
    <td align="center">Assist</td>
    <td align="center">Joined</td>
    <td align="center">Display Order</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr>
    <td align="center"><?php echo $row_Access['board_member_id']; ?></td>
    <td><?php echo $row_Access['name']; ?></td>
    <td><?php echo $row_Access['board_desc']; ?></td>
    <td class="page"><a href="mailto:<?php echo $row_Access['username']; ?>"><?php echo $row_Access['username']; ?></a></td>
    <td><?php echo $row_Access['usertype']; ?></td>
    <td align="center"><?php echo $row_Access['display']; ?></td>
    <td align="center"><?php echo $row_Access['assist']; ?></td>
    <td align="center"><?php echo $row_Access['register_year']; ?></td>
    <td align="center"><?php echo $row_Access['order_display']; ?></td>
    <td><a href="../A_common/vbsa_personal_detail.php?memb_id=<?php echo $row_Access['MemberID']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="detail" /></a></td>
    </tr>
  <?php } while ($row_Access = mysql_fetch_assoc($Access)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
