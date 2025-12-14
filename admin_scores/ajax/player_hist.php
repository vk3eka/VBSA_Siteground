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

$memb_id = "-1";
if (isset($_GET['memb_id'])) {
  $memb_id = $_GET['memb_id'];
}

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_teamdet = "SELECT MemberID, FirstName, LastName  FROM members WHERE MemberID= '$memb_id'";
$teamdet = mysql_query($query_teamdet, $connvbsa) or die(mysql_error());
$row_teamdet = mysql_fetch_assoc($teamdet);
$totalRows_teamdet = mysql_num_rows($teamdet);

mysql_select_db($database_connvbsa, $connvbsa);
$query_playhist = "SELECT scrs.Team_grade, team_club, team_name, scr_season, count_played, pts_won, percent_won, average_position, current_year_scrs, game_type FROM scrs LEFT JOIN Team_entries ON Team_entries.team_id = scrs.team_id WHERE MemberID = '$memb_id' AND game_type = '$comptype' ORDER BY current_year_scrs DESC ";
$playhist = mysql_query($query_playhist, $connvbsa) or die(mysql_error());
$row_playhist = mysql_fetch_assoc($playhist);
$totalRows_playhist = mysql_num_rows($playhist);

mysql_select_db($database_connvbsa, $connvbsa);
$query_brkmax = "SELECT brk,  grade, finals_brk, season, YEAR(recvd) AS year FROM breaks WHERE member_ID_brks = '$memb_id' AND brk_type = '$comptype' ORDER BY brk DESC, year DESC LIMIT 1";
$brkmax = mysql_query($query_brkmax, $connvbsa) or die(mysql_error());
$row_brkmax = mysql_fetch_assoc($brkmax);
$totalRows_brkmax = mysql_num_rows($brkmax);

mysql_select_db($database_connvbsa, $connvbsa);
$query_brkall = "SELECT brk, grade, finals_brk, season, YEAR(recvd) AS year FROM breaks WHERE member_ID_brks='$memb_id' AND brk_type = '$comptype' ORDER BY year DESC";
$brkall = mysql_query($query_brkall, $connvbsa) or die(mysql_error());
$row_brkall = mysql_fetch_assoc($brkall);
$totalRows_brkall = mysql_num_rows($brkall);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tournhist = "SELECT tournament_number as ID, tourn_name, rank_pts, entry_cal_year, tournaments.tourn_type FROM tourn_entry  LEFT JOIN tournaments ON tourn_id = tournament_number WHERE tourn_memb_id='$memb_id' AND rank_pts >0 AND tournaments.tourn_type = '$comptype' ORDER BY entry_cal_year DESC";
$tournhist = mysql_query($query_tournhist, $connvbsa) or die(mysql_error());
$row_tournhist = mysql_fetch_assoc($tournhist);
$totalRows_tournhist = mysql_num_rows($tournhist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_Scores_S1/facebox/facebox.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="2" align="center" nowrap="nowrap" class="red_bold"><?php echo $comptype; ?> history for: <?php echo $row_teamdet['FirstName']; ?> <?php echo $row_teamdet['LastName']; ?></td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">Member ID</td>
    <td align="left"><?php echo $row_teamdet['MemberID']; ?></td>
  </tr>

    <tr>
      <td align="center">&nbsp;</td>
      <td align="left" nowrap="nowrap">&nbsp;</td>
    </tr>

</table>

<!-- 2 column table to carry nested tables  -->
<table align="center">
  <tr>
    <td style="vertical-align:top"><!-- Nested table left  -->
    
<table align="center" cellpadding="3" cellspacing="3" frame="box">
  <tr>
    <td align="left">&nbsp;</td>
    <td colspan="9" align="center" class="red_bold">Team History</td>
    </tr>
  <tr>
    <th align="left">Grade</th>
    <th align="center">Season</th>
    <th align="left">Club</th>
    <th align="left" nowrap="nowrap">Team Name</th>
    <th align="center">Played</th>
    <th align="center">Won</th>
    <th align="center" nowrap="nowrap">% Won</th>
    <th align="center">Position</th>
    <th align="center">Year</th>
    <th align="left">Type</th>
  </tr>
  <?php do { ?>
    <tr>
      <td align="left"><?php echo $row_playhist['Team_grade']; ?></td>
      <td align="center"><?php echo $row_playhist['scr_season']; ?></td>
      <td align="left" nowrap="nowrap"><?php echo $row_playhist['team_club']; ?></td>
      <td align="left" nowrap="nowrap"><?php echo $row_playhist['team_name']; ?></td>
      <td align="center"><?php echo $row_playhist['count_played']; ?></td>
      <td align="center"><?php echo $row_playhist['pts_won']; ?></td>
      <td align="center"><?php echo $row_playhist['percent_won']; ?></td>
      <td align="center"><?php echo $row_playhist['average_position']; ?></td>
      <td align="center"><?php echo $row_playhist['current_year_scrs']; ?></td>
      <td align="left"><?php echo $row_playhist['game_type']; ?></td>
    </tr>
    <?php } while ($row_playhist = mysql_fetch_assoc($playhist)); ?>
</table>    
    
    </td><!-- END Nested table left  -->
    
    <td style="vertical-align:top"><!-- Nested table center  -->
<table cellpadding="3" cellspacing="3" frame="box" style="margin-left:10px">
  <tr>
    <td colspan="5" align="center"  class="red_bold">Best Break</td>
    </tr>
  <tr>
    <th align="center">Break</th>
    <th align="center">Grade</th>
    <th align="center">Finals?</th>
    <th align="center">Season</th>
    <th align="center">Year</th>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_brkmax['brk']; ?></td>
      <td align="center"><?php echo $row_brkmax['grade']; ?></td>
      <td align="center"><?php echo $row_brkmax['finals_brk']; ?></td>
      <td align="center"><?php echo $row_brkmax['season']; ?></td>
      <td align="center"><?php echo $row_brkmax['year']; ?></td>
    </tr>
    
    <?php } while ($row_brkmax = mysql_fetch_assoc($brkmax)); ?>
    
    <tr>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" align="center"  class="red_bold">All Breaks </td>
    </tr>
    <tr>
      <th align="center">Break</th>
        <th align="center">Grade</th>
        <th align="center">Finals?</th>
        <th align="center">Season</th>
        <th align="center">Year</th>
    </tr>
    <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_brkall['brk']; ?></td>
      <td align="center"><?php echo $row_brkall['grade']; ?></td>
      <td align="center"><?php echo $row_brkall['finals_brk']; ?></td>
      <td align="center"><?php echo $row_brkall['season']; ?></td>
      <td align="center"><?php echo $row_brkall['year']; ?></td>
    </tr>
    
    <?php } while ($row_brkall = mysql_fetch_assoc($brkall)); ?>
</table>   
    </td><!-- END Nested table center  -->
    
    <td style="vertical-align:top"><!-- Nested table right  -->
    <table cellpadding="3" cellspacing="3" frame="box" style="margin-left:10px">
  <tr>
    <td colspan="5" align="center" class="red_bold">Tournament History</td>
    </tr>
  <tr>
    <th align="center" nowrap="nowrap">Tournament ID</th>
    <th align="left" nowrap="nowrap">Tournament Name</th>
    <th align="center" nowrap="nowrap">Ranking Points</th>
    <th align="center">Year</th>
    <th align="center">Type</th>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_tournhist['ID']; ?></td>
      <td align="left" nowrap="nowrap"><?php echo $row_tournhist['tourn_name']; ?></td>
      <td align="center"><?php echo $row_tournhist['rank_pts']; ?></td>
      <td align="center"><?php echo $row_tournhist['entry_cal_year']; ?></td>
      <td align="center"><?php echo $row_tournhist['tourn_type']; ?></td>
    </tr>
    <?php } while ($row_tournhist = mysql_fetch_assoc($tournhist)); ?>
</table>
    </td><!-- END Nested table right  -->
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($teamdet);

mysql_free_result($playhist);

mysql_free_result($brkmax);

mysql_free_result($brkall);

mysql_free_result($tournhist);
?>
