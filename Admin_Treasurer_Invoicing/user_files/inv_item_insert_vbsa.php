<?php require_once('../../Connections/connvbsa.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO inv_items (inv_item_id, inv_no, item_name, apply_GST, item_amount, GST) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['inv_item_id'], "int"),
                       GetSQLValueString($_POST['inv_no'], "int"),
                       GetSQLValueString($_POST['item_name'], "text"),
					   GetSQLValueString($_POST['apply_GST'], "text"),
                       GetSQLValueString($_POST['item_amount'], "double"),
					   GetSQLValueString($_POST['GST'], "double"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "inv_print_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$inv_id = "-1";
if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv = "SELECT * FROM inv_to WHERE inv_id = '$inv_id'";
$Inv = mysql_query($query_Inv, $connvbsa) or die(mysql_error());
$row_Inv = mysql_fetch_assoc($Inv);
$totalRows_Inv = mysql_num_rows($Inv);

mysql_select_db($database_connvbsa, $connvbsa);
$query_teams = "SELECT team_id , team_club , team_club_id, team_name , team_grade , players, count_byes, rounds_played, rounds_played*players*7 AS subs, team_season FROM `Team_entries` WHERE team_id = '$team_id' ";
$teams = mysql_query($query_teams, $connvbsa) or die(mysql_error());
$row_teams = mysql_fetch_assoc($teams);
$totalRows_teams = mysql_num_rows($teams);

mysql_select_db($database_connvbsa, $connvbsa);
$query_inv_items = "SELECT * FROM inv_items WHERE inv_no = '$inv_id'";
$inv_items = mysql_query($query_inv_items, $connvbsa) or die(mysql_error());
$row_inv_items = mysql_fetch_assoc($inv_items);
$totalRows_inv_items = mysql_num_rows($inv_items);
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

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<table width="800" align="center" cellpadding="5" cellspacing="5">
  <tr>
          <td colspan="2" align="center" class="page_heading">&nbsp;</td>
          <td colspan="2" align="right" class="page_heading">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="left" class="red_text">Administration Treasurer - Insert team details and value to Invoice </td>
          <td colspan="2" align="right" class="red_text"><span class="page_heading">
            <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
          </span></td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">&nbsp;</td>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">You are about to insert this team (ID: <?php echo $team_id; ?>) detail to  invoice number:</td>
          <td><?php echo $row_Inv['inv_id']; ?></td>
          <td align="right">Addressed to:</td>
          <td align="left"><?php echo $row_Inv['inv_busname']; ?></td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">&nbsp;</td>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="center" nowrap="nowrap" class="red_text">Treasurer -  PLEASE NOTE: WILL NOT BE ACCURATE UNTIL THE FINAL ROUND HAS BEEN PLAYED</td>
        </tr>
      </table>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table width="600" border="1" align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td align="center">These details will be entered as &quot;Item Description&quot;</td>
        <td align="center">Value $</td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" nowrap="nowrap">Team name: <?php echo $row_teams['team_name']; ?>,&nbsp;&nbsp; Grade:<?php echo $row_teams['team_grade']; ?>, &nbsp;&nbsp;<?php echo $row_teams['players']; ?> players</td>
        <td align="center"><?php echo $row_teams['subs']; ?></td>
        <td align="center" nowrap="nowrap"><input type="submit" value="Insert item" /></td>
      </tr>
    </table>
    <input type="hidden" name="inv_item_id" value="" />
    <input type="hidden" name="inv_no" value="<?php echo $inv_id; ?>" />
    <input type="hidden" name="item_name" value="<?php echo $row_teams['team_season']; ?>. Team name: <?php echo $row_teams['team_name']; ?>,&nbsp;&nbsp; Grade:  <?php echo $row_teams['team_grade']; ?>, &nbsp;&nbsp;<?php echo $row_teams['players']; ?> players" />
    <input type="hidden" name="item_amount" value="<?php echo $row_teams['subs']; ?>" />
    <input type="hidden" name="apply_GST" value="No" />
    <input type="hidden" name="GST" value="0" />
    <input type="hidden" name="MM_insert" value="form1" />
</form>
  <table width="400" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="2" class="page_heading">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="page_heading">Items currently listed on this invoice</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_inv_items['item_name']; ?></td>
    <td><?php echo $row_inv_items['item_amount']; ?></td>
  </tr>
  <?php } while ($row_inv_items = mysql_fetch_assoc($inv_items)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Inv);

mysql_free_result($Inv);

mysql_free_result($teams);

mysql_free_result($inv_items);
?>
