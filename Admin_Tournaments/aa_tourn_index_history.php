<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

$tourn_page = "http://www.vbsa.org.au/Admin_Tournaments/aa_tourn_index_history.php";
$_SESSION['tourn_page'] = $tourn_page;

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

if (isset($_GET['tourn_year'])) {
  $tourn_year = $_GET['tourn_year'];
}
$calendarArr = [];

mysql_select_db($database_connvbsa, $connvbsa);
//$query_tourn1 = "Select * FROM tournaments Left Join calendar on  calendar.event_id = tournaments.event_id WHERE tourn_year = '$tourn_year' AND tournaments.tourn_type='Snooker' ORDER BY startdate";
$query_tourn1 = "Select *, tournaments.tourn_id, calendar.event_id, tournaments.tourn_class as tourn_class, tournaments.tourn_type as comp_type FROM tournaments Left Join calendar on tournaments.tourn_id = calendar.tourn_id WHERE tourn_year = " . $tourn_year . " AND tournaments.tourn_type='Snooker' ORDER BY startdate";
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
//echo($query_tourn1 . "<br>");
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

//mysql_select_db($database_connvbsa, $connvbsa);
//$query_tourn2 = "Select * FROM tournaments Left Join calendar on  calendar.event_id = tournaments.event_id WHERE tourn_year = '$tourn_year' AND tournaments.tourn_type='Billiards' ORDER BY startdate";
$query_tourn2 = "Select *, tournaments.tourn_id, calendar.event_id, tournaments.tourn_class as tourn_class, tournaments.tourn_type as comp_type FROM tournaments Left Join calendar on tournaments.tourn_id = calendar.tourn_id WHERE tourn_year = " . $tourn_year . " AND tournaments.tourn_type='Billiards' ORDER BY startdate";
$tourn2 = mysql_query($query_tourn2, $connvbsa) or die(mysql_error());
$row_tourn2 = mysql_fetch_assoc($tourn2);
$totalRows_tourn2 = mysql_num_rows($tourn2);

$query_tourn3 = "Select *, tournaments.tourn_id, calendar.event_id, tournaments.tourn_class as tourn_class, tournaments.tourn_type as comp_type FROM tournaments Left Join calendar on tournaments.tourn_id = calendar.tourn_id WHERE tourn_year = " . $tourn_year . " AND tournaments.tourn_type='Both' ORDER BY startdate";
$tourn3 = mysql_query($query_tourn3, $connvbsa) or die(mysql_error());
$row_tourn3 = mysql_fetch_assoc($tourn3);
$totalRows_tourn3 = mysql_num_rows($tourn3);


$query_calendar = "Select * FROM vbsa3364_vbsa2.calendar where tourn_id != '' and startdate > '" . $tourn_year . "-01-01' and startdate < '" . $tourn_year . "-12-31' order by tourn_id";
//echo($query_calendar . "<br>");
$calendar = mysql_query($query_calendar, $connvbsa) or die(mysql_error());
//create array of tourn_id's
$i = 0;
while($row_calendar = mysql_fetch_assoc($calendar))
{
  $calendarArr[$i] = $row_calendar['tourn_id'];
  //echo($calendarArr[$i] . ", " .  $row_calendar['tourn_id'] . "<br>");
  $i++;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch_treas.php';?>
<table width="600" align="center">
  <tr>
    <td colspan="2" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td class="red_bold">  <?php echo $tourn_year; ?>
    Tournament History</td>
    <td align="right" class="greenbg"><a href="aa_tourn_index.php">Return to Tournaments</a></td>
  </tr>
</table>
    <table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td colspan="13" align="center" class="red_bold" ><?php echo $row_tourn1['tourn_year']; ?> SNOOKER TOURNAMENTS</td>
      </tr>
      <tr>
        <td align="center">Tourn ID</td>
        <td align="left">Tournament Name</td>
        <td align="left">Year</td>
        <td align="left">Class</td>
        <!--<td>Draw Type</td>-->
        <td align="center">View on site</td>
        <td>Type</td>
        <td align="center" nowrap="nowrap">Vic rank type</td>
        <td>Entries</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr>
        <?php
        if(in_array($row_tourn1['tourn_id'], $calendarArr))
        {
          echo("<td align='center' style='color: red'>" . $row_tourn1['tourn_id'] . "</td>");
        }
        else
        {
          echo("<td align='center'>" . $row_tourn1['tourn_id'] . "</td>");
        }
        ?>
          <!--<td align="center"><?php echo $row_tourn1['tourn_id']; ?></td>-->
          <td align="left"><?php echo $row_tourn1['tourn_name']; ?></td>
          <td align="left"><?php echo $row_tourn1['tourn_year'];?></td>
          <td align="left"><?php echo $row_tourn1['tourn_class']; ?></td>
          <!--<td><?php echo $row_tourn1['tourn_draw']; ?></td>-->
          <td align="center"><?php echo $row_tourn1['site_visible']; ?></td>
          <td><?php echo $row_tourn1['comp_type']; ?></td>
          <td align="center"><?php echo $row_tourn1['ranking_type']; ?></td>
          <td><?php echo $row_tourn1['status']; ?></td>
          <td><a href="tournament_detail.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="View all entries" /></a></td>
          <!--<td><a href="user_files/tournament_edit.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>-->
          <td><a href="edit_tournament.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>&page=tournament"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>
          <td class="page"><a href="user_files/player_edit_all.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>">Edit All</a></td>
          <td><a href="x_fin_rep.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>"><img src="../Admin_Images/fin_butt.png" width="20" height="20" title="View tournament financials" /></a></td>
        </tr>
        <?php } while ($row_tourn1 = mysql_fetch_assoc($tourn1)); ?>
    </table>
    <table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td colspan="13" align="center" class="red_bold" ><?php echo $row_tourn2['tourn_year']; ?> BILLIARDS TOURNAMENTS</td>
      </tr>
      <tr>
        <td align="center">Tourn ID</td>
        <td align="left">Tournament Name</td>
        <td align="left">Year</td>
        <td align="left">Class</td>
        <!--<td>Draw Type</td>-->
        <td align="center">View on site</td>
        <td>Type</td>
        <td align="center" nowrap="nowrap">Vic rank type</td>
        <td>Entries</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php do { ?>
      <tr>
        <?php
        if(in_array($row_tourn2['tourn_id'], $calendarArr))
        {
          echo("<td align='center' style='color: red'>" . $row_tourn2['tourn_id'] . "</td>");
        }
        else
        {
          echo("<td align='center'>" . $row_tourn2['tourn_id'] . "</td>");
        }
        ?>
          <!--<td align="center"><?php echo $row_tourn2['tourn_id']; ?></td>-->
        <td align="left"><?php echo $row_tourn2['tourn_name']; ?></td>
        <td align="left"><?php echo $row_tourn2['tourn_year']; ?></td>
        <td align="left"><?php echo $row_tourn2['tourn_class']; ?></td>
        <!--<td><?php echo $row_tourn2['tourn_draw']; ?></td>-->
        <td align="center"><?php echo $row_tourn2['site_visible']; ?></td>
        <td><?php echo $row_tourn2['comp_type']; ?></td>
        <td align="center"><?php echo $row_tourn2['ranking_type']; ?></td>
        <td><?php echo $row_tourn2['status']; ?></td>
        <td><a href="tournament_detail.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="View all entries" /></a></td>
        <td><a href="user_files/tournament_edit.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>
        <td class="page"><a href="user_files/player_edit_all.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>">Edit All</a></td>
        <td><a href="x_fin_rep.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>"><img src="../Admin_Images/fin_butt.png" width="20" height="20" title="View tournament financials" /></a></td>
      </tr>
      <?php } while ($row_tourn2 = mysql_fetch_assoc($tourn2)); ?> 
</table>

<table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td colspan="13" align="center" class="red_bold" ><?php echo $tourn_year; ?> SNOOKER & BILLIARDS TOURNAMENTS</td>
      </tr>
      <tr>
        <td align="center">Tourn ID</td>
        <td align="left">Tournament Name</td>
        <td align="left">Year</td>
        <td align="left">Class</td>
        <!--<td>Draw Type</td>-->
        <td align="center">View on site</td>
        <td>Type</td>
        <td align="center" nowrap="nowrap">Vic rank type</td>
        <td>Entries</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php do { ?>
      <tr>
        <?php
        if(in_array($row_tourn3['tourn_id'], $calendarArr))
        {
          echo("<td align='center' style='color: red'>" . $row_tourn3['tourn_id'] . "</td>");
        }
        else
        {
          echo("<td align='center'>" . $row_tourn3['tourn_id'] . "</td>");
        }
        ?>
          <!--<td align="center"><?php echo $row_tourn2['tourn_id']; ?></td>-->
        <td align="left"><?php echo $row_tourn3['tourn_name']; ?></td>
        <td align="left"><?php echo $row_tourn3['tourn_year']; ?></td>
        <td align="left"><?php echo $row_tourn3['tourn_class']; ?></td>
        <!--<td><?php echo $row_tourn2['tourn_draw']; ?></td>-->
        <td align="center"><?php echo $row_tourn3['site_visible']; ?></td>
        <td><?php echo $row_tourn3['comp_type']; ?></td>
        <td align="center"><?php echo $row_tourn3['ranking_type']; ?></td>
        <td><?php echo $row_tourn3['status']; ?></td>
        <td><a href="tournament_detail.php?tourn_id=<?php echo $row_tourn3['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="View all entries" /></a></td>
        <td><a href="user_files/tournament_edit.php?tourn_id=<?php echo $row_tourn3['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>
        <td class="page"><a href="user_files/player_edit_all.php?tourn_id=<?php echo $row_tourn3['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>">Edit All</a></td>
        <td><a href="x_fin_rep.php?tourn_id=<?php echo $row_tourn3['tourn_id']; ?>&tourn_year=<?php echo $tourn_year; ?>"><img src="../Admin_Images/fin_butt.png" width="20" height="20" title="View tournament financials" /></a></td>
      </tr>
      <?php } while ($row_tourn2 = mysql_fetch_assoc($tourn2)); ?> 
</table>

<p>&nbsp;</p>
</body>
</html>
<?php

?>
