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

$colname_S1P = "-1";
if (isset($_GET['arch'])) {
  $colname_S1P = $_GET['arch'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_S1P = sprintf("SELECT win.team_cal_year, a.grade, a.type, win.team_name AS Winner, win.GFtot AS win_total, win.GF_pts AS win_pts, rup.team_name AS RunnerUp, rup.GFtot AS rup_total, win.GFsnook_draw AS S1draw FROM Team_grade AS a LEFT JOIN (SELECT team_grade, team_name, GFtot, GF_pts, team_cal_year, GFsnook_draw FROM Team_entries WHERE team_cal_year = %s AND S1=1 ORDER BY GFtot DESC, GF_pts DESC, GFsnook_draw ASC ) AS win ON win.team_grade =a.grade LEFT JOIN (SELECT team_grade, team_name, GFtot, GF_pts FROM Team_entries WHERE team_cal_year = %s AND GFtot is not null AND S1=1 ORDER BY GFtot ASC, GF_pts ASC, GFsnook_draw DESC ) AS rup ON rup.team_grade =a.grade WHERE a.season='S1' AND win.GFtot is not null GROUP BY a.grade ORDER BY a.type, a.grade", GetSQLValueString($colname_S1P, "date"),GetSQLValueString($colname_S1P, "date"));
$S1P = mysql_query($query_S1P, $connvbsa) or die(mysql_error());
$row_S1P = mysql_fetch_assoc($S1P);
$totalRows_S1P = mysql_num_rows($S1P);

$colname_part_clubs = "-1";
if (isset($_GET['arch'])) {
  $colname_part_clubs = $_GET['arch'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_part_clubs = sprintf("SELECT team_club, team_cal_year FROM Team_entries WHERE team_cal_year = %s AND team_club is not null GROUP BY team_club", GetSQLValueString($colname_part_clubs, "date"));
$part_clubs = mysql_query($query_part_clubs, $connvbsa) or die(mysql_error());
$row_part_clubs = mysql_fetch_assoc($part_clubs);
$totalRows_part_clubs = mysql_num_rows($part_clubs);

$colname_S2P = "-1";
if (isset($_GET['arch'])) {
  $colname_S2P = $_GET['arch'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_S2P = sprintf("SELECT win.team_cal_year, a.grade, a.type, win.team_name AS Winner, win.GFtot AS win_total, win.GF_pts AS win_pts, rup.team_name AS RunnerUp, rup.GFtot AS rup_total, win.GFsnook_draw AS S2draw FROM Team_grade AS a  LEFT JOIN (SELECT team_grade, team_name, GFtot, GF_pts, team_cal_year, GFsnook_draw            FROM Team_entries 		   WHERE team_cal_year = %s AND S2=1            ORDER BY GFtot DESC, GF_pts DESC, GFsnook_draw ASC ) AS win ON win.team_grade =a.grade LEFT JOIN (SELECT team_grade, team_name, GFtot, GF_pts            FROM Team_entries 		   WHERE team_cal_year = %s AND GFtot is not null AND S2=1            ORDER BY GFtot ASC, GF_pts ASC, GFsnook_draw DESC ) AS rup ON rup.team_grade =a.grade WHERE a.season='S2' AND win.GFtot is not null GROUP BY a.grade ORDER BY a.type, a.grade", GetSQLValueString($colname_S2P, "date"),GetSQLValueString($colname_S2P, "date"));
$S2P = mysql_query($query_S2P, $connvbsa) or die(mysql_error());
$row_S2P = mysql_fetch_assoc($S2P);
$totalRows_S2P = mysql_num_rows($S2P);
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
<script type="text/javascript" src="../Scripts/datepicker.js"></script><script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

<link href="../Admin_xx_CSS/Archives.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="850" border="0" align="center">
  <tr>
    <td align="right"><span class="page"><a href><input type="button" value="Return to previous page" onclick="window.history.go(-1); return false;"/></a></span></td>
  </tr>
</table>
<div id="Archive_content">    
    <div class="archive_teams">
      <table align="center" cellpadding="5" cellspacing="5" class="red_bold">
        <tr>
          <td> <?php echo $row_part_clubs['team_cal_year']; ?> Season 1 - Grand final results and end of season ladders</td>
        </tr>
      </table>
        
      <table align="center" cellpadding="5" cellspacing="5">

            <?php do { ?>
            <tr>
              <td><?php echo $row_S1P['grade']; ?></td>
              <td><?php echo $row_S1P['type']; ?></td>
              <td><?php echo $row_S1P['Winner']; ?></td>
              <td><?php echo $row_S1P['win_total']; if ($row_S1P['win_pts']==1) echo " *"; elseif($row_S1P['S1draw']==1) echo " #"; else echo ""; ?></td>
              <td class="text-center"><?php if ($row_S1P['win_pts']==1) echo "Points"; elseif($row_S1P['S1draw']==1) echo "Draw"; else echo "Defeated"; ?></td>
              <td><?php echo $row_S1P['RunnerUp']; ?></td>
              <td><?php echo $row_S1P['rup_total']; if($row_S1P['S1draw']==1) echo " #"; else echo ""; ?></td>
              <td align="center" class="greenbg"><a href="Premiers_ladder_detail.php?ladder=<?php echo $row_S1P['grade']; ?>&amp;year=<?php echo $row_S1P['team_cal_year']; ?>">Final Ladder</a></td>
            </tr>
            <?php } while ($row_S1P = mysql_fetch_assoc($S1P)); ?>
          </table>
  </div>
  
        
    <div class="archive_teams">
      <table align="center" cellpadding="5" cellspacing="5" class="red_bold">
        <tr>
          <td> <?php echo $row_part_clubs['team_cal_year']; ?> Season 2 - Grand final results and end of season ladders </td>
        </tr>
      </table>
      
      <table align="center" cellpadding="5" cellspacing="5">
        <?php do { ?>
        <tr>
          <td><?php echo $row_S2P['grade'];?></td>
          <td><?php if($row_S2P['grade']!='BPBS') echo $row_S2P['type']; else echo "2x2"; ?></td>
          <td><?php echo $row_S2P['Winner']; ?></td>
          <td><?php echo $row_S2P['win_total']; if ($row_S2P['win_pts']==1) echo " *"; elseif($row_S2P['S2draw']==1) echo " #"; else echo ""; ?></td>
          <td class="text-center"><?php if ($row_S2P['win_pts']==1) echo "Points"; elseif($row_S2P['S2draw']==1) echo "Draw"; else echo "Defeated"; ?></td>
          <td><?php echo $row_S2P['RunnerUp']; ?></td>
          <td><?php echo $row_S2P['rup_total']; if($row_S2P['S2draw']==1) echo " #"; else echo ""; ?></td>
          <td align="center" class="greenbg"><a href="Premiers_ladder_detail.php?ladder=<?php echo $row_S2P['grade']; ?>&amp;year=<?php echo $row_S2P['team_cal_year']; ?>">Final Ladder</a></td>
        </tr>
        <?php } while ($row_S2P = mysql_fetch_assoc($S2P)); ?>
      </table>  
      
      
        
  </div>
    <div class="archive_clubs">
      <table align="center" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2" class="red_bold"><?php echo $row_part_clubs['team_cal_year']; ?> Participating Clubs</td>
          </tr>
        <?php do { ?>
          <tr>
            <td align="left"><?php echo $row_part_clubs['team_club']; ?></td>
            <td align="center" nowrap="nowrap" class="page"><a href="Premiers_player_detail.php?playdet=<?php echo $row_part_clubs['team_club']; ?>&amp;team_cur_year=<?php echo $row_part_clubs['team_cal_year']; ?>">Player stats</a></td>
            </tr>
          <?php } while ($row_part_clubs = mysql_fetch_assoc($part_clubs)); ?>
        </table>
  </div>
  </div>
</div> <!--Close Archive content -->
</body>
</html>
<?php

?>
