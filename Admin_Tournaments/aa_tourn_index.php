<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}

// export player date from tourn entry:-
//SELECT ranknum, MemberID, concat(FirstName, " ", LastName) as fullname FROM vbsa3364_vbsa2.tourn_entry left join rank_S_open_tourn on tourn_entry.tourn_memb_id = rank_S_open_tourn.memb_id left Join members on members.MemberID = tourn_entry.tourn_memb_id where tournament_number = 202462 order by tournament_number;


$tourn_page = "../Admin_Tournaments/aa_tourn_index.php";

$_SESSION['tourn_page'] = $tourn_page;

$MM_authorizedUsers = "Webmaster,Treasurer";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

// temp addition to show tournaments with calendar entry

//mysql_select_db($database_connvbsa, $connvbsa);
$query_calendar = "Select * FROM vbsa3364_vbsa2.calendar where tourn_id != '' and startdate > '" . (date("Y")) . "-01-01' and startdate < '" . (date("Y")) . "-12-31' order by tourn_id";
//echo($query_calendar . "<br>");
$calendar = mysql_query($query_calendar, $connvbsa) or die(mysql_error());
//create array of tourn_id's
$i = 0;
while($row_calendar = mysql_fetch_assoc($calendar))
{
  $calendarArr[$i] = $row_calendar['tourn_id'];
  $i++;
}

//echo("<pre>");
//echo(var_dump($calendarArr));
//echo("</pre>");


//mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = "Select *, tournaments.tourn_id, calendar.event_id, tournaments.tourn_class as tourn_class, tournaments.tourn_type as comp_type FROM tournaments Left Join calendar on tournaments.tourn_id = calendar.tourn_id WHERE tourn_year = YEAR(CURDATE()) AND tournaments.tourn_type='Snooker' AND status='Open' ORDER BY startdate";
//echo($query_tourn1 . "<br>");
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

//echo("<pre>");
//echo(var_dump($row_tourn1));
//echo("</pre>");

//mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn2 = "Select *, tournaments.tourn_id, calendar.event_id, tournaments.tourn_class as tourn_class, tournaments.tourn_type as comp_type FROM tournaments Left Join calendar on tournaments.tourn_id = calendar.tourn_id WHERE tourn_year = YEAR(CURDATE()) AND tournaments.tourn_type='Billiards' AND status='Open' ORDER BY startdate";
//$query_tourn2 = "SELECT * FROM tournaments WHERE tourn_year = YEAR(CURDATE()) AND tourn_type='Billiards' AND status='Open' ORDER BY tournaments.tourn_name";
$tourn2 = mysql_query($query_tourn2, $connvbsa) or die(mysql_error());
$row_tourn2 = mysql_fetch_assoc($tourn2);
$totalRows_tourn2 = mysql_num_rows($tourn2);

//mysql_select_db($database_connvbsa, $connvbsa);
//$query_tyear = "SELECT YEAR (tourn_year) AS Tyear FROM tournaments WHERE YEAR(tourn_year) <> YEAR( CURDATE( ) ) GROUP BY Tyear ORDER BY Tyear DESC";
$query_tyear = "SELECT tourn_year FROM tournaments WHERE tourn_year <> YEAR( CURDATE( ) ) GROUP BY tourn_year ORDER BY tourn_year DESC";
$tyear = mysql_query($query_tyear, $connvbsa) or die(mysql_error());
$row_tyear = mysql_fetch_assoc($tyear);
$totalRows_tyear = mysql_num_rows($tyear);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn_closed = "Select *, tournaments.tourn_id, calendar.event_id, tournaments.tourn_class as tourn_class, tournaments.tourn_type as comp_type FROM tournaments Left Join calendar on tournaments.tourn_id = calendar.tourn_id WHERE tourn_year = YEAR(CURDATE()) AND status=('Closed') ORDER BY startdate";
//$query_tourn_closed = "SELECT * FROM tournaments WHERE tourn_year = YEAR(CURDATE()) AND status=('Closed') ORDER BY tournaments.tourn_type DESC, tournaments.tourn_name ";
$tourn_closed = mysql_query($query_tourn_closed, $connvbsa) or die(mysql_error());
$row_tourn_closed = mysql_fetch_assoc($tourn_closed);
$totalRows_tourn_closed = mysql_num_rows($tourn_closed);

$cal_year = date("Y");
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
<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="4" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" class="red_bold"><?php echo $cal_year; ?> Tournaments, Administrators have access to all views, cannot edit or insert financials. Treasurer has access/ edit/ insert from all views</td>
  </tr>
  <tr>
    <!--<td align="center" nowrap class="greenbg"><a href="user_files/tournament_insert.php">Insert a new tournament</a></td>-->
    <!--<td align="center" nowrap class="greenbg"><a href="insert_tournament.php?page=tournament">Insert a new tournament</a></td>-->
    <td align="center" nowrap class="greenbg"><a href="../Admin_Calendar/calendar_event_previous.php?page=tournament">Insert a new event/tournament</a></td>
    <td align="center" nowrap class="greenbg"><a href="../Admin_rankings/rankings_Vic_snooker.php">Tournament Rankings</a></td>
    <!--<td align="center" class="greenbg"><a href="Tournament_last_50_entries.php">Last 50 entries</a></td>-->
    <td align="center" nowrap class="greenbg"><a href="../Admin_update_tables/Update_Scores_Rank.php">Update the Victorian Tournament Rankings</a></td>
    <!--<td align="center" class="greenbg"><a href="../Admin_Treasurer/A_fin_index.php">Return to Treasurer index</a></td>-->
    <!--<td align="center" nowrap class="greenbg"><a href="tournament_draw.php">Tournament Draw (test)</a></td>-->
    <td align="center" nowrap class="greenbg" colspan='5'><a href="../Admin_DB_VBSA/export_tournament_csv.php">Export Calendar/Tournament List</a></td>
  </tr>
  <!--<tr>
    <td align="center" nowrap class="greenbg" colspan='4'><a href="tournament_draw_template.php">Tournament Draw (test)</a></td>
  </tr>-->  
</table>
    <table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td colspan="13" align="center" class="greenbg" >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="9" align="center" class="red_bold" >SNOOKER TOURNAMENTS OPEN</td>
        <td align="center" class="red_bold" >&nbsp;</td>
        <td align="center" class="red_bold" >&nbsp;</td>
        <td align="center" class="red_bold" >&nbsp;</td>
        <td align="center" class="red_bold" >&nbsp;</td>
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
          <!--<td align="center"><?php echo $row_tourn1['tourn_id'];?></td>-->
          <td align="left"><?php echo $row_tourn1['tourn_name']; ?></td>
          <td align="left"><?php $newDate = date("Y", strtotime($row_tourn1['tourn_year'])); echo $newDate; ?></td>
          <td align="left"><?php echo $row_tourn1['tourn_class']; ?></td>
          <!--<td><?php echo $row_tourn1['tourn_draw']; ?></td>-->
          <td align="center"><?php echo $row_tourn1['site_visible']; ?></td>
          <td><?php echo $row_tourn1['comp_type']; ?></td>
          <td align="center"><?php echo $row_tourn1['ranking_type']; ?></td>
          <td><?php echo $row_tourn1['status']; ?></td>
          <td><a href="tournament_detail.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>&tourn_type=<?php echo $row_tourn1['comp_type']; ?>&tourn_year=<?php echo date("Y"); ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="View all entries" /></a></td>
          <!--<td><a href="user_files/tournament_edit.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>-->
          <td><a href="edit_tournament.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>&page=tournament"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>
          
          <td class="page"><a href="tournament_draw_template.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>"><img src="../images/draw.jpeg" width="20" height="20" title="Create/Edit Draw" /></a></td>
          <!--<<td class="page"><a href="tournament_draw.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>"><img src="../images/draw.jpeg" width="20" height="20" title="Create/Edit Draw" /></a></td>-->

          <!--<td class="page"><a href="create_draw_order.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>"><img src="../images/draw.jpeg" width="20" height="20" title="Create/Edit Draw" /></a></td>-->

          <td class="page"><a href="user_files/player_edit_all.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>&tourn_year=<?php echo date("Y"); ?>">Edit All</a></td>
          <td align="center"><a href="user_files/tournament_delete_confirm.php?tournID=<?php echo $row_tourn1['tourn_id']; ?>&cal_year=<?php echo date("Y"); ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
          <td><a href="x_fin_rep.php?tourn_id=<?php echo $row_tourn1['tourn_id']; ?>"><img src="../Admin_Images/fin_butt.png" width="20" height="20" title="Tournament financials" /></a></td>
          
        </tr>
        <?php } while ($row_tourn1 = mysql_fetch_assoc($tourn1)); ?>
    </table>
    <table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <td colspan="9" align="center" class="red_bold" >BILLIARDS TOURNAMENTS OPEN</td>
        <td align="center" class="red_bold" >&nbsp;</td>
        <td align="center" class="red_bold" >&nbsp;</td>
        <td align="center" class="red_bold" >&nbsp;</td>
        <td align="center" class="red_bold" >&nbsp;</td>
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
        <!--<td align="center"><?php echo $row_tourn2['tourn_id'];?></td>-->
        <td align="left"><?php echo $row_tourn2['tourn_name']; ?></td>
        <td align="left"><?php $newDate = date("Y", strtotime($row_tourn2['tourn_year'])); echo $newDate; ?></td>
        <td align="left"><?php echo $row_tourn2['tourn_class']; ?></td>
        
        <!--<td><?php echo $row_tourn2['tourn_draw']; ?></td>-->

        <td align="center"><?php echo $row_tourn2['site_visible']; ?></td>
        <td><?php echo $row_tourn2['comp_type']; ?></td>
        <td align="center"><?php echo $row_tourn2['ranking_type']; ?></td>
        <td><?php echo $row_tourn2['status']; ?></td>
        <td><a href="tournament_detail.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>&tourn_type=<?php echo $row_tourn2['comp_type']; ?>&tourn_year=<?php echo date("Y"); ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="View all entries" /></a></td>
        <!--<td><a href="user_files/tournament_edit.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>-->
        <td><a href="edit_tournament.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>&page=tournament"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>
          
        <!--<td class="page"><a href="create_draw_order.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>"><img src="../images/draw.jpeg" width="20" height="20" title="Create/Edit Draw" /></a></td>-->

        <!--<td class="page"><a href="tournament_draw.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>"><img src="../images/draw.jpeg" width="20" height="20" title="Create/Edit Draw" /></a></td>-->
        <td class="page"><a href="tournament_draw_template.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>"><img src="../images/draw.jpeg" width="20" height="20" title="Create/Edit Draw" /></a></td>

        <td class="page"><a href="user_files/player_edit_all.php?tourn_id=<?php echo $row_tourn2['tourn_id']; ?>&tourn_year=<?php echo date("Y"); ?>">Edit All</a></td>
        <td align="center"><a href="user_files/tournament_delete_confirm.php?tournID=<?php echo $row_tourn2['tourn_id']; ?>&cal_year=<?php echo date("Y"); ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
        <td><a href="x_fin_rep.php?fin_det=<?php echo $row_tourn2['tourn_id']; ?>"><img src="../Admin_Images/fin_butt.png" width="20" height="20" title="Tournament financials" /></a></td>
        
      </tr>
      <?php } while ($row_tourn2 = mysql_fetch_assoc($tourn2)); ?>
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="9" align="center" class="red_bold" >TOURNAMENTS CLOSED</td> 
    <td align="center" class="red_bold" >&nbsp;</td>
    <td align="center" class="red_bold" >&nbsp;</td>
    <td align="center" class="red_bold" >&nbsp;</td>
    <td align="center" class="red_bold" >&nbsp;</td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr>
    <?php

        if(in_array($row_tourn_closed['tourn_id'], $calendarArr))
        {
          echo("<td align='center' style='color: red'>" . $row_tourn_closed['tourn_id'] . "</td>");
        }
        else
        {
          echo("<td align='center'>" . $row_tourn_closed['tourn_id'] . "</td>");
        }

        ?>
        <!--<td align="center"><?php echo $row_tourn2['tourn_id'];?></td>-->
    <td align="left"><?php echo $row_tourn_closed['tourn_name']; ?></td>
    <td align="left"><?php $newDate = date("Y", strtotime($row_tourn_closed['tourn_year'])); echo $newDate; ?></td>
    <td align="left"><?php echo $row_tourn_closed['tourn_class']; ?></td>
    <!--<td><?php echo $row_tourn_closed['tourn_draw']; ?></td>-->
    <td align="center"><?php echo $row_tourn_closed['site_visible']; ?></td>
    <td><?php echo $row_tourn_closed['comp_type']; ?></td>
    <td align="center"><?php echo $row_tourn_closed['ranking_type']; ?></td>
    <td><?php echo $row_tourn_closed['status']; ?></td>
    <td><a href="tournament_detail.php?tourn_id=<?php echo $row_tourn_closed['tourn_id']; ?>&tourn_type=<?php echo $row_tourn_closed['comp_type']; ?>&tourn_year=<?php echo date("Y"); ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="View all entries" /></a></td>
    <!--<td><a href="user_files/tournament_edit.php?tourn_id=<?php echo $row_tourn_closed['tourn_id']; ?>"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>-->
    <td><a href="edit_tournament.php?tourn_id=<?php echo $row_tourn_closed['tourn_id']; ?>&page=tournament"><img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit tournament details" /></a></td>
          
    <!--<td class="page"><a href="create_draw_order.php?tourn_id=<?php echo $row_tourn_closed['tourn_id']; ?>"><img src="../images/draw.jpeg" width="20" height="20" title="Create/Edit Draw" /></a></td>-->
    <!--<td class="page"><a href="tournament_draw.php?tourn_id=<?php echo $row_tourn_closed['tourn_id']; ?>"><img src="../images/draw.jpeg" width="20" height="20" title="Create/Edit Draw" /></a></td>-->
    <td class="page"><a href="tournament_draw_template.php?tourn_id=<?php echo $row_tourn_closed['tourn_id']; ?>"><img src="../images/draw.jpeg" width="20" height="20" title="Create/Edit Draw" /></a></td>

     <td class="page"><a href="user_files/player_edit_all.php?tourn_id=<?php echo $row_tourn_closed['tourn_id']; ?>&tourn_year=<?php echo date("Y"); ?>">Edit All</a></td>
    <td align="center"><a href="user_files/tournament_delete_confirm.php?tournID=<?php echo $row_tourn_closed['tourn_id']; ?>&cal_year=<?php echo date("Y"); ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    <td><a href="x_fin_rep.php?tourn_id=<?php echo $row_tourn_closed['tourn_id']; ?>"><img src="../Admin_Images/fin_butt.png" width="20" height="20" title="Tournament financials" /></a></td>
  </tr>
  <?php } while ($row_tourn_closed = mysql_fetch_assoc($tourn_closed)); ?> 
</table>
<table align="center" cellpadding="5" cellspacing="5"> 
  <tr>
    <td>View tournaments from previous years</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center" class="greenbg" ><a href="aa_tourn_index_history.php?tourn_year=<?php echo $row_tyear['tourn_year']; ?>"><?php echo $row_tyear['tourn_year']; ?></a></td>
    </tr>
    <?php } while ($row_tyear = mysql_fetch_assoc($tyear)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
