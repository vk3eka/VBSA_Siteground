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

$rank = "-1";
if (isset($_GET['rank'])) {
  $rank = $_GET['rank'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_RPall = "SELECT ranknum, rank_B_open_tourn.memb_id, FirstName, LastName, total_tourn_rp, date_format(rank_B_open_tourn.last_update,'%D %b %Y') AS last_update, total_tourn_rp FROM rank_B_open_tourn  LEFT JOIN members ON MemberID = rank_B_open_tourn.memb_id  LEFT JOIN rank_a_billiards_master ON rank_a_billiards_master.memb_id = rank_B_open_tourn.memb_id WHERE rank_B_open_tourn.memb_id='$rank'";
$RPall = mysql_query($query_RPall, $connvbsa) or die(mysql_error());
$row_RPall = mysql_fetch_assoc($RPall);
$totalRows_RPall = mysql_num_rows($RPall);

mysql_select_db($database_connvbsa, $connvbsa);
$query_rp_curr = "SELECT tourn_memb_id, tourn_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.ranking_type='Vic rank' AND tournaments.tourn_type='Billiards' AND entry_cal_year = YEAR(CURDATE( )) GROUP BY tourn_id";
$rp_curr = mysql_query($query_rp_curr, $connvbsa) or die(mysql_error());
$row_rp_curr = mysql_fetch_assoc($rp_curr);
$totalRows_rp_curr = mysql_num_rows($rp_curr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_rp_1yr = "SELECT tourn_memb_id, tourn_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.ranking_type='Vic rank' AND tournaments.tourn_type='Billiards' AND entry_cal_year = YEAR(CURDATE( ))-1 GROUP BY tourn_id";
$rp_1yr = mysql_query($query_rp_1yr, $connvbsa) or die(mysql_error());
$row_rp_1yr = mysql_fetch_assoc($rp_1yr);
$totalRows_rp_1yr = mysql_num_rows($rp_1yr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_rp_2yr = "SELECT tourn_memb_id, tourn_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.ranking_type='Vic rank' AND tournaments.tourn_type='Billiards' AND entry_cal_year = YEAR(CURDATE( ))-2 GROUP BY tourn_id";
$rp_2yr = mysql_query($query_rp_2yr, $connvbsa) or die(mysql_error());
$row_rp_2yr = mysql_fetch_assoc($rp_2yr);
$totalRows_rp_2yr = mysql_num_rows($rp_2yr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1curr = "SELECT total_RP  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( )) AND game_type='Billiards' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S1curr = mysql_query($query_S1curr, $connvbsa) or die(mysql_error());
$row_S1curr = mysql_fetch_assoc($S1curr);
$totalRows_S1curr = mysql_num_rows($S1curr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2curr = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))  AND game_type='Billiards' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S2curr = mysql_query($query_S2curr, $connvbsa) or die(mysql_error());
$row_S2curr = mysql_fetch_assoc($S2curr);
$totalRows_S2curr = mysql_num_rows($S2curr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1_1year = "SELECT total_RP  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Billiards' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S1_1year = mysql_query($query_S1_1year, $connvbsa) or die(mysql_error());
$row_S1_1year = mysql_fetch_assoc($S1_1year);
$totalRows_S1_1year = mysql_num_rows($S1_1year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2_1year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Billiards' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S2_1year = mysql_query($query_S2_1year, $connvbsa) or die(mysql_error());
$row_S2_1year = mysql_fetch_assoc($S2_1year);
$totalRows_S2_1year = mysql_num_rows($S2_1year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1_2year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Billiards' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S1_2year = mysql_query($query_S1_2year, $connvbsa) or die(mysql_error());
$row_S1_2year = mysql_fetch_assoc($S1_2year);
$totalRows_S1_2year = mysql_num_rows($S1_2year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2_2year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Billiards' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S2_2year = mysql_query($query_S2_2year, $connvbsa) or die(mysql_error());
$row_S2_2year = mysql_fetch_assoc($S2_2year);
$totalRows_S2_2year = mysql_num_rows($S2_2year);
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
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>


<body>
    
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>


<table align="center">
          <tr>
            <td style="padding-right:10px"><input type="button" role="button" value="Return to previous page" onclick="window.history.go(-1); return false;"/></td>
          </tr>
</table>
<table align="center">
    <tr>
      <td>&nbsp;</td>
  </tr>
        <tr>
          <td align="left">
            <strong><?php echo date("Y"); ?> Victorian Billiards Ranking for: <?php echo $row_RPall['FirstName']; ?> <?php echo $row_RPall['LastName']; ?></strong></td>
  </tr>
        <tr>
          <td align="left">Currently Ranked: <span><strong><?php echo $row_RPall['ranknum']; ?></strong></span></td>
  </tr>
        <tr>
          <td align="left">Tournament Points =  <strong><?php echo $row_RPall['tourn_total']; ?></strong> which includes 15% of Weekly points = <strong><?php echo $row_RPall['weekly_percent']; ?></strong></td>
  </tr>
</table>

<table width="500" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3"><strong><?php echo date("Y"); ?> Tournament Ranking points - current (100%)</strong></td>
  </tr>
    <?php if($row_rp_curr['rank_pts']>0 ) { ?>
    <tr class="italicise">
      	<td align="left">Tournament Name</td>
      	<td width="80" align="center">Points Won</td>
      	<td width="80" align="center">100%</td>
  </tr>
	<?php do { ?>
    <tr>
      
        <td align="left"><?php echo $row_rp_curr['tourn_name']; ?></td>
        <td width="80" align="center"><?php echo $row_rp_curr['rank_pts']; ?></td>
        <td width="80" align="center"><?php echo $row_rp_curr['rank_pts']; ?></td>
        
    </tr>
	<?php } while ($row_rp_curr = mysql_fetch_assoc($rp_curr)); ?>
    <?php } else { ?>
    <tr>
    <td colspan="3" align="left">Did not compete</td>
    </tr>
    <?php } ?>
</table>

<table width="500" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="3"><strong><?php echo date("Y")-1; ?> Tournament Ranking points - 1 Year (65%)</strong></td>
  	</tr>
    <?php if($row_rp_1yr['rank_pts']>0 ) { ?>
    <tr class="italicise">
      	<td align="left">Tournament Name</td>
      	<td width="80" align="center">Points Won</td>
      	<td width="80" align="center">65%</td>
    </tr>
	<?php do { ?>
    <tr>
      
        <td align="left"><?php echo $row_rp_1yr['tourn_name']; ?></td>
        <td width="80" align="center"><?php echo $row_rp_1yr['rank_pts']; ?></td>
        <td width="80" align="center"><?php echo (ROUND($row_rp_1yr['rank_pts']*65/100)); ?></td>
        
    </tr>
	<?php } while ($row_rp_1yr = mysql_fetch_assoc($rp_1yr)); ?>
    <?php } else { ?>
    <tr>
    <td colspan="3" align="left">Did not compete</td>
    </tr>
    <?php } ?>
</table>

<table width="500" align="center" cellpadding="3" cellspacing="3">
    <tr>
    	<td colspan="3"><strong><?php echo date("Y")-2; ?> Tournament Ranking points - 2 Years (35%)</strong></td>
  	</tr>
    <?php if($row_rp_2yr['rank_pts']>0 ) { ?>
    <tr>
      	<td>Tournament Name</td>
      	<td width="80" align="center">Points Won</td>
      	<td width="80" align="center">35%</td>
    </tr>
	<?php do { ?>
    <tr class="italicise">
      
        <td><?php echo $row_rp_2yr['tourn_name']; ?></td>
        <td width="80" align="center"><?php echo $row_rp_2yr['rank_pts']; ?></td>
        <td width="80" align="center"><?php echo (ROUND($row_rp_2yr['rank_pts']*35/100)); ?></td>
        
    </tr>
	<?php } while ($row_rp_2yr = mysql_fetch_assoc($rp_2yr)); ?>
    <?php } else { ?>
    <tr>
    <td colspan="3">Did not compete</td>
    </tr>
    <?php } ?>
</table>

<table width="500" align="center" cellpadding="3" cellspacing="3">
    <tr>
    	<td class="text-right"><span class="italic">Total Tournament Ranking Points</span> &nbsp;&nbsp;&nbsp; <strong><?php echo $row_RPall['tourn_total']; ?></strong></td>
    </tr>
</table>

<table width="500" align="center" cellpadding="3" cellspacing="3">
        <tr>
          <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6"><strong>Calculation of Weekly Ranking Points (to the nearest whole number)</strong></td>
      </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d"))); ?></span> - S1 Best total Ranking Points</td>
          <td width="40" align="left"><?php echo $row_S1curr['total_RP']; ?></td>
          <td width="5" align="center">x</td>
          <td width="30" align="center">100%</td>
          <td width="5" align="center">=</td>
          <td width="40" align="center"><?php if(isset($row_S1curr['total_RP'])) echo round($row_S1curr['total_RP']); else echo "0"; ?></td>
        </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d"))); ?></span> - S2 Best total Ranking Points</td>
          <td width="40" align="left"><?php echo $row_S2curr['total_RP']; ?></td>
          <td width="5" align="center">x</td>
          <td width="30" align="center">100%</td>
          <td width="5" align="center">=</td>
          <td width="40" align="center"><?php if(isset($row_S2curr['total_RP'])) echo round($row_S2curr['total_RP']); else echo "0"; ?></td>
      </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?></span> - S1 Best total Ranking Points </td>
          <td width="40" align="left"><?php echo $row_S1_1year['total_RP']; ?></td>
          <td width="5" align="center">x</td>
          <td width="30" align="center">65%</td>
          <td width="5" align="center">=</td>
          <td width="40" align="center"><?php if(isset($row_S1_1year['total_RP'])) echo round($row_S1_1year['total_RP']*65/100); else echo "0"; ?></td>
      </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?></span> - S2 Best total Ranking Points </td>
          <td width="40" align="left"><?php echo $row_S2_1year['total_RP']; ?></td>
          <td width="5" align="center">x</td>
          <td width="30" align="center">65%</td>
          <td width="5" align="center">=</td>
          <td width="40" align="center"><?php if(isset($row_S2_1year['total_RP'])) echo round($row_S2_1year['total_RP']*65/100); else echo "0"; ?></td>
      </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?></span> - S1 Best total Ranking Points </td>
          <td width="40" align="left"><?php echo $row_S1_2year['total_RP']; ?></td>
          <td width="5" align="center">x</td>
          <td width="30" align="center">35%</td>
          <td width="5" align="center">=</td>
          <td width="40" align="center"><?php if(isset($row_S1_2year['total_RP'])) echo round($row_S1_2year['total_RP']*35/100); else echo "0"; ?></td>
      </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?></span> -  S2 Best total Ranking Points</td>
          <td width="40" align="left"><?php echo $row_S2_2year['total_RP']; ?></td>
          <td width="5" align="center">x</td>
          <td width="30" align="center">35%</td>
          <td width="5" align="center">=</td>
          <td width="40" align="center"><?php if(isset($row_S2_2year['total_RP'])) echo round($row_S2_2year['total_RP']*35/100); else echo "0"; ?></td>
      </tr>
  </table>

<table width="500" align="center" cellpadding="3" cellspacing="3">
    <tr>
    	<td>Total Weekly Ranking Points <?php echo $row_RPall['scr_total']; ?> X 15% = &nbsp; <strong><?php echo $row_RPall['weekly_percent']; ?></strong></td>
    </tr>
</table>
 
</body>
</html>

