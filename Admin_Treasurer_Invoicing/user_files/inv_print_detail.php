<?php require_once('../../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}


if (isset($_GET['inv_type'])) {
  $inv_type = $_GET['inv_type'];
}

if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

$inv_page = "inv_print_detail.php";
$_SESSION['inv_page'] = $inv_page;

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
$query_Item = "SELECT inv_item_id, inv_no, club_id,  item_name, item_discount, item_amount, inv_to.inv_id, inv_items.discount_total, inv_items.apply_GST, inv_items.item_total, inv_items.GST, inv_items.item_total_all FROM inv_items, inv_to WHERE inv_no =  '$inv_id' GROUP BY inv_item_id ORDER BY inv_item_id";
$Item = mysql_query($query_Item, $connvbsa) or die(mysql_error());
$row_Item = mysql_fetch_assoc($Item);
$totalRows_Item = mysql_num_rows($Item);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv_no = "SELECT inv_busname, inv_to, inv_email, inv_street, inv_suburb, inv_city, inv_postcode, date_format(inv_date,'%b %e, %Y') AS date, inv_id, inv_date FROM inv_to WHERE inv_id =  '$inv_id'";
$Inv_no = mysql_query($query_Inv_no, $connvbsa) or die(mysql_error());
$row_Inv_no = mysql_fetch_assoc($Inv_no);
$totalRows_Inv_no = mysql_num_rows($Inv_no);

mysql_select_db($database_connvbsa, $connvbsa);
$query_invtot = "SELECT * FROM inv_to WHERE inv_id =  '$inv_id'";
$invtot = mysql_query($query_invtot, $connvbsa) or die(mysql_error());
$row_invtot = mysql_fetch_assoc($invtot);
$totalRows_invtot = mysql_num_rows($invtot);

mysql_select_db($database_connvbsa, $connvbsa);
$query_teams = "SELECT Team_entries.team_id , team_club , team_club_id, team_name , FirstName, LastName, Team_entries.team_grade , players, count_byes,  rounds_played, rounds_played*players*7 AS subs, team_season, scrs.MemberID FROM Team_entries LEFT JOIN scrs ON scrs.team_id = Team_entries.team_id AND captain_scrs =1 LEFT JOIN members ON members.MemberID = scrs.MemberID WHERE team_club_id = '$club_id'  AND team_season = '$inv_type'  AND team_cal_year = YEAR( CURDATE( ) )  AND rounds_played>0 GROUP BY Team_entries.team_id ORDER BY team_grade";
$teams = mysql_query($query_teams, $connvbsa) or die(mysql_error());
$row_teams = mysql_fetch_assoc($teams);
$totalRows_teams = mysql_num_rows($teams);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>

<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/Invoice_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />


</head>

<Body>
<div id="inv_wrapper">

	<div id="inv_header" ><img src="../../Admin_Images/Inv_Header.jpg" width="850" height="145" /></div>

<div id="Invoice_number">

		<div class="inv_no">INVOICE: <?php echo $row_Inv_no['inv_id']; ?></div>
		<div class="inv_date">Issue Date:</div>
        <div class="inv_date"><?php echo $row_Inv_no['date']; ?></div>

</div>

<div id="Cust_detail">

<div class="Bus_name"><div class="name"><strong>Invoice to:</strong></div>
  <?php echo $row_Inv_no['inv_busname']; ?></div>
  
  <div class="Address"><div class="name"><strong>Attention:</strong></div>
<?php echo $row_Inv_no['inv_to']; ?></div>

<div class="Address"><div class="name"><strong>Email to:</strong></div>
<?php echo $row_Inv_no['inv_email']; ?></div>

 <div class="Address"><div class="name"><strong>Address:</strong></div>
<?php echo $row_Inv_no['inv_street']; ?>, <?php echo $row_Inv_no['inv_suburb']; ?>, <?php echo $row_Inv_no['inv_city']; ?>, <?php echo $row_Inv_no['inv_postcode']; ?></div>


<div class="inv_edit"><a href="inv_edit.php?inv_id=<?php echo $inv_id; ?>&inv_type=<?php echo $inv_type; ?>&club_id=<?php echo $row_Inv_det['club_id']; ?>"><img src="../../Admin_Images/edit_butt.png" title="Edit" /></a></div>

</div>
<div id="Inv_items">
<table width="825" align="left">
  <tr>
    <td><div class="item_desc"><strong>Item Description</strong></div></td> 
    <td><strong>Cost</strong></td>
    <td><strong>Disc Rate</strong></td>
    <td><strong>Discount</strong></td>
    <td><strong>Total</strong></td>
    <td><strong>GST?</strong></td>
    <td><strong>GST</strong></td>
    <td><strong>Inc GST</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_Item['item_name']; ?></td>
    <td>$<?php echo $row_Item['item_amount']; ?></td>
    <td><?php echo $row_Item['item_discount']; ?>%</td>
    <td><?php echo $row_Item['discount_total']; ?></td> 
    <td><?php echo $row_Item['item_total']; ?></td>
    <td><?php echo $row_Item['apply_GST']; ?></td> 
    <td><?php echo $row_Item['GST']; ?></td>
    <td><?php echo $row_Item['item_total_all']; ?></td>
    <td><a href="inv_item_edit.php?item_id=<?php echo $row_Item['inv_item_id']; ?>&inv_id=<?php echo $inv_id; ?>&inv_type=<?php echo $inv_type; ?>&club_id=<?php echo $club_id; ?>"><img src="../../Admin_Images/edit_butt.png" title="Edit" /></a></td>
    <td><a href="inv_item_delete_confirm.php?item_id=<?php echo $row_Item['inv_item_id']; ?>&inv_id=<?php echo $inv_id; ?>&inv_type=<?php echo $inv_type; ?>&club_id=<?php echo $club_id; ?>"><img src="../../Admin_Images/trash_butt.png" title="Delete" /></a></td>
  </tr>
  <?php } while ($row_Item = mysql_fetch_assoc($Item)); ?>
  <?php do { ?>
  <tr>
    <td><div class="item_desc"><strong>Totals</strong></div></td>
    <td>$<strong><?php echo $row_invtot['inv_amount_total']; ?></strong></td>
    <td>&nbsp;</td>
    <td>$<strong><?php echo $row_invtot['inv_discount_total']; ?></strong></td>
    <td>$<strong><?php echo $row_invtot['total_less_disc']; ?></strong></td>
    <td>&nbsp;</td>
    <td>$<strong><?php echo $row_invtot['inv_GST_total']; ?></strong></td>
    <td>$<strong><?php echo $row_invtot['inv_total_all']; ?></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="9">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Comment:</td>
    <td colspan="8"><?php echo $row_invtot['inv_comment']; ?></td>
    <td><a href="inv_edit.php?inv_id=<?php echo $inv_id; ?>&inv_type=<?php echo $inv_type; ?>&club_id=<?php echo $row_invtot['club_id']; ?>"><img src="../../Admin_Images/edit_butt.png" title="Edit" /></a></td>
    <td>&nbsp;</td>
  </tr>
  <?php } while ($row_invtot = mysql_fetch_assoc($invtot)); ?>
</table>
		
</div>


<div id="Inv_items">
  	
</div>

<div class="admin">
  <table width="795" align="center">
        <tr>
          <th colspan="2" align="center">Invoice ID: <?php echo $inv_id; ?></th>
          <td>&nbsp;Invoice type: <?php echo $inv_type; ?></td>
        </tr>
        <tr>
          <th colspan="2" align="right">&nbsp;</th>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <th colspan="2" align="right">Add an item to this invoice: </th>
          <td> <a href="inv_item_insert.php?inv_id=<?php echo $inv_id; ?>&amp;club_id=<?php echo $club_id; ?>&amp;inv_type=<?php echo $inv_type; ?>"><img src="../../Admin_Images/add_butt.png" width="20" height="20" title="Add an item" /></a></td>
        </tr>
        <tr>
          <td colspan="2" align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" class="greenbg"><a href="../<?php echo $_SESSION['page']; ?>">Return to previous page</a></td>
          <td align="left" class="greenbg"><a href="../AA_inv_index.php">Return to Invoice Menu</a> </td>
          <td align="right" class="greenbg"><a href="../../Admin_update_tables/UpdateInvoiceTables.php">Recalculate the Invoice tables</a> </td>
        </tr>
    </table>
    </div>  
  <?php if($row_Inv_det['inv_cal_year'] = date("Y") && ($inv_type=='S1' || $inv_type=='S2')) { ?>
  <table border="1" align="center" cellpadding="3" cellspacing="3" style="margin-top:10px">
    <tr>
      <td colspan="10" align="center"><span class="page_heading">All participating teams for this club in <?php echo $row_Inv_det['inv_type']; ?>, <?php echo date("Y") ?> - Premier &amp; State Grade</span></td>
    </tr>
    <tr>
      <td colspan="10" align="center"><span class="page_heading">Treasurer -  PLEASE NOTE: WILL NOT BE ACCURATE UNTIL THE FINAL ROUND HAS BEEN PLAYED</span></td>
    </tr>
    <tr>
      <td>Team ID</td>
      <td>Club</td>
      <td>Team Name</td>
      <td>Captain</td>
      <td>Grade</td>
      <td align="center">Players</td>
      <td align="center">Byes</td>
      <td align="center">Rounds Played </td>
      <td align="center">Value $ </td>
      <td align="center">&nbsp;
        <center>
        </center></td>
    </tr>
    <?php do { ?>
    <tr>
      <td><?php echo $row_teams['team_id']; ?></td>
      <td><?php echo $row_teams['team_club']; ?></td>
      <td><?php echo $row_teams['team_name']; ?></td>
      <td><?php echo $row_teams['FirstName']; ?> <?php echo $row_teams['LastName']; ?></td>
      <td nowrap="nowrap" ><?php echo $row_teams['team_grade']; ?></td>
      <td align="center"><?php echo $row_teams['players']; ?> players</td>
      <td align="center"><center>
        <?php echo $row_teams['count_byes']; ?>
      </center></td>
      <td align="center"><?php echo $row_teams['rounds_played']; ?></td>
      <td align="center"><?php echo $row_teams['subs']; ?></td>
      <td align="center" nowrap="nowrap" class="greenbg"><a href="inv_item_insert_vbsa.php?inv_id=<?php echo $inv_id; ?>&team_id=<?php echo $row_teams['team_id']; ?>&inv_type=<?php echo $inv_type; ?>&club_id=<?php echo $club_id; ?>">Ins team to Inv</a></td>
    </tr>
    <?php } while ($row_teams = mysql_fetch_assoc($teams)); ?>
  </table>
  <?php } else echo ""; ?>
  
<!--Close wrapper --></div>
</body>
</html>
<?php
mysql_free_result($Item);

mysql_free_result($Inv_det);

mysql_free_result($Inv_no);

mysql_free_result($invtot);

mysql_free_result($teams);
?>
