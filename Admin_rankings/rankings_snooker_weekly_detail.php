<?php require_once('../Connections/connvbsa.php'); ?>
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

if (isset($_GET['rank'])) {
  $rank = $_GET['rank'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_RPALL = "SELECT ranknum, memb_id, FirstName, LastName, total_weekly_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_weekly LEFT JOIN members ON MemberID = memb_id  WHERE memb_id = '$rank'";
$RPALL = mysql_query($query_RPALL, $connvbsa) or die(mysql_error());
$row_RPALL = mysql_fetch_assoc($RPALL);
$totalRows_RPALL = mysql_num_rows($RPALL);

mysql_select_db($database_connvbsa, $connvbsa);
$query_personal_brks = "SELECT member_ID_brks, FirstName, LastName, grade, brk, brk_type, finals_brk, season, date_format( recvd, '%b %e, %Y') AS 'Reported on' FROM breaks, members WHERE member_ID_brks=MemberID AND (YEAR(breaks.recvd) > YEAR( CURDATE( ))-3) AND member_ID_brks='$rank' ORDER BY recvd DESC";
$personal_brks = mysql_query($query_personal_brks, $connvbsa) or die(mysql_error());
$row_personal_brks = mysql_fetch_assoc($personal_brks);
$totalRows_personal_brks = mysql_num_rows($personal_brks);

mysql_select_db($database_connvbsa, $connvbsa);
$query_high_brk = "SELECT member_ID_brks, MAX(brk) FROM breaks WHERE (YEAR(breaks.recvd) >  YEAR( CURDATE( ))-3) AND member_ID_brks='$rank'";
$high_brk = mysql_query($query_high_brk, $connvbsa) or die(mysql_error());
$row_high_brk = mysql_fetch_assoc($high_brk);
$totalRows_high_brk = mysql_num_rows($high_brk);

mysql_select_db($database_connvbsa, $connvbsa);
$query_RP_by_year = "SELECT scr_curr_S1, scr_curr_S2, scr_1yr_S1, scr_1yr_S2, scr_2yr_S1, scr_2yr_S2, weekly_total FROM rank_aa_snooker_master WHERE memb_id='$rank'";
$RP_by_year = mysql_query($query_RP_by_year, $connvbsa) or die(mysql_error());
$row_RP_by_year = mysql_fetch_assoc($RP_by_year);
$totalRows_RP_by_year = mysql_num_rows($RP_by_year);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>
<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="799" align="center">
  <tr>
    <td align="left" class="red_bold">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="red_bold">
      Rankings - Detail of individual Ranking Points and how they were earned
    </td>
    <td align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2" align="left">Details  will update every time weekly competition is played. (Current rankings will not appear until play starts)</td>
  </tr>
</table>
    <p>&nbsp;</p>
    <table align="center" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2" align="center">Rankings detail for: <span class="red_bold"><?php echo $row_RPALL['FirstName']; ?> <?php echo $row_RPALL['LastName']; ?></span> (ID Number: <span class="red_bold"><?php echo $row_RPALL['memb_id']; ?></span>)</td>
      </tr>
      <tr>
        <td align="center">Currently Ranked: <span class="red_bold"><?php echo $row_RPALL['ranknum']; ?></span></td>
        <td align="center">Total Ranking Points: <span class="red_bold"><?php echo $row_RPALL['total_weekly_rp']; ?></span></td>
      </tr>
</table>
    <p>&nbsp;</p>
    <table width="550" align="center">
      <tr>
        <td colspan="4" align="left">Calculation of Ranking Points (to the nearest whole number)</td>
      </tr>
      <tr>
        <td align="left"><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d"))); ?></span> - S1 Best total Ranking Points</td>
        <td align="center">@</td>
        <td align="center">100%</td>
        <td align="left"><?php echo $row_RP_by_year['scr_curr_S1']; ?></td>
      </tr>
      <tr>
        <td align="left"><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d"))); ?></span> - S2 Best total Ranking Points</td>
        <td align="center">@</td>
        <td align="center">100%</td>
        <td align="left"><?php echo $row_RP_by_year['scr_curr_S2']; ?></td>
      </tr>
      <tr>
        <td align="left"><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?></span> - S1 Best total Ranking Points </td>
        <td align="center">@</td>
        <td align="center">65%</td>
        <td align="left"><?php echo $row_RP_by_year['scr_1yr_S1']; ?></td>
      </tr>
      <tr>
        <td align="left"><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?></span> - S2 Best total Ranking Points </td>
        <td align="center">@</td>
        <td align="center">65%</td>
        <td align="left"><?php echo $row_RP_by_year['scr_1yr_S2']; ?></td>
      </tr>
      <tr>
        <td align="left"><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?></span> - S1 Best total Ranking Points </td>
        <td align="center">@</td>
        <td align="center">35%</td>
        <td align="left"><?php echo $row_RP_by_year['scr_2yr_S1']; ?></td>
      </tr>
      <tr>
        <td align="left"><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?></span> -  S2 Best total Ranking Points</td>
        <td align="center">@</td>
        <td align="center">35%</td>
        <td align="left"><?php echo $row_RP_by_year['scr_2yr_S2']; ?></td>
      </tr>
      <tr>
        <td colspan="3" align="right"><strong>Total &nbsp;&nbsp;&nbsp;</strong></td>
        <td align="left"><strong><?php echo $row_RP_by_year['weekly_total']; ?></strong></td>
      </tr>
    </table>
<p>&nbsp;</p>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
        <td colspan="6" align="center"><span class="red_bold">Breaks made in <?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?>, <?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?> & <?php echo date("Y", strtotime(date("Y-m-d"))); ?>  </span> - High Break <?php echo $row_high_brk['MAX(brk)']; ?></td>
      </tr>
      <tr>
        <td align="left">Grade</td>
        <td align="center">&nbsp;</td>
        <td align="center">Break</td>
        <td align="center">Finals?</td>
        <td align="center">Season</td>
        <td align="center">Reported on</td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="left"><?php echo $row_personal_brks['grade']; ?></td>
          <td align="center">&nbsp;</td>
          <td align="center"><?php echo $row_personal_brks['brk']; ?></td>
          <td align="center"><?php echo $row_personal_brks['finals_brk']; ?></td>
          <td align="center"><?php echo $row_personal_brks['season']; ?></td>
          <td align="center"><?php echo $row_personal_brks['Reported on']; ?></td>
      </tr>
        <?php } while ($row_personal_brks = mysql_fetch_assoc($personal_brks)); ?>
</table>
</body>
</html>
<?php

?>
