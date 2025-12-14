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

if(isset($_GET['cohort']) && ($_GET['cohort'] != ''))
{
  $cohort = $_GET['cohort'];
  switch ($cohort)
  {
      case 'all':
        $title = '(Open)';
        $query_RPall = "Select rank_aa_snooker_master.memb_id, FirstName, LastName, 
        (Round(tourn_1 + 
        tourn_2 + 
        tourn_curr)) as total_tourn, 
        (Round(ROUND(scr_curr_S2)) + 
        (ROUND(scr_curr_S1)) + 
        (ROUND(scr_1yr_S1)) + 
        (ROUND(scr_1yr_S2)) + 
        (ROUND(scr_2yr_S1)) + 
        (ROUND(scr_2yr_S2))) as total_rp, date_format(rank_aa_snooker_master.last_update,'%D %b %Y') AS last_update FROM rank_aa_snooker_master LEFT JOIN members ON MemberID = rank_aa_snooker_master.memb_id WHERE rank_aa_snooker_master.memb_id = '$rank'";

        $query_rp_curr = "Select tourn_memb_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.tourn_type='Snooker' AND (tournaments.ranking_type='Victorian') AND entry_cal_year = YEAR(CURDATE( )) GROUP BY tourn_id order by rank_pts DESC";

        $query_rp_1yr = "Select tourn_memb_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.ranking_type='Victorian' AND tournaments.tourn_type='Snooker' AND entry_cal_year = YEAR(CURDATE( ))-1 GROUP BY tourn_id order by rank_pts DESC";

        $query_rp_2yr = "Select tourn_memb_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.ranking_type='Victorian' AND tournaments.tourn_type='Snooker' AND entry_cal_year = YEAR(CURDATE( ))-2 GROUP BY tourn_id order by rank_pts DESC";
      break;
      case 'woman':
        $title = "(Womens)";
        $query_RPall = "Select rank_aa_snooker_master.memb_id, FirstName, LastName, 
        (Round(tourn_1_w + 
        tourn_2_w + 
        tourn_curr_w)) as total_tourn, 
        (Round(ROUND(scr_curr_S2)) + 
        (ROUND(scr_curr_S1)) + 
        (ROUND(scr_1yr_S1)) + 
        (ROUND(scr_1yr_S2)) + 
        (ROUND(scr_2yr_S1)) + 
        (ROUND(scr_2yr_S2))) as total_rp, date_format(rank_aa_snooker_master.last_update,'%D %b %Y') AS last_update FROM rank_aa_snooker_master LEFT JOIN members ON MemberID = rank_aa_snooker_master.memb_id WHERE rank_aa_snooker_master.memb_id = '$rank'";

        $query_rp_curr = "Select tourn_memb_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.tourn_type='Snooker' AND (tournaments.ranking_type='Womens') AND entry_cal_year = YEAR(CURDATE( )) GROUP BY tourn_id order by rank_pts DESC";

        $query_rp_1yr = "Select tourn_memb_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.ranking_type='Womens' AND tournaments.tourn_type='Snooker' AND entry_cal_year = YEAR(CURDATE( ))-1 GROUP BY tourn_id order by rank_pts DESC";

        $query_rp_2yr = "Select tourn_memb_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.ranking_type='Womens' AND tournaments.tourn_type='Snooker' AND entry_cal_year = YEAR(CURDATE( ))-2 GROUP BY tourn_id order by rank_pts DESC";
        break;
      case 'junior': 
        $title = '(Junior)';
        $query_RPall = "Select rank_aa_snooker_master.memb_id, FirstName, LastName, 
          (Round(tourn_1_j + 
          tourn_2_j + 
          tourn_curr_j)) as total_tourn, 
          (Round(ROUND(scr_curr_S2)) + 
          (ROUND(scr_curr_S1)) + 
          (ROUND(scr_1yr_S1)) + 
          (ROUND(scr_1yr_S2)) + 
          (ROUND(scr_2yr_S1)) + 
          (ROUND(scr_2yr_S2))) as total_rp, date_format(rank_aa_snooker_master.last_update,'%D %b %Y') AS last_update FROM rank_aa_snooker_master LEFT JOIN members ON MemberID = rank_aa_snooker_master.memb_id WHERE rank_aa_snooker_master.memb_id = '$rank'";

        $query_rp_curr = "Select tourn_memb_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.tourn_type='Snooker' AND (tournaments.ranking_type='Junior') AND entry_cal_year = YEAR(CURDATE( )) GROUP BY tourn_id order by rank_pts DESC";

        $query_rp_1yr = "Select tourn_memb_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.ranking_type='Junior' AND tournaments.tourn_type='Snooker' AND entry_cal_year = YEAR(CURDATE( ))-1 GROUP BY tourn_id order by rank_pts DESC";

        $query_rp_2yr = "Select tourn_memb_id, rank_pts, tourn_name FROM tourn_entry  LEFT JOIN tournaments ON tourn_id=tournament_number WHERE tourn_memb_id='$rank' AND tournaments.ranking_type='Junior' AND tournaments.tourn_type='Snooker' AND entry_cal_year = YEAR(CURDATE( ))-2 GROUP BY tourn_id order by rank_pts DESC";
        break;
  }
}

echo($query_RPall . "<br>");
mysql_select_db($database_connvbsa, $connvbsa);
$RPall = mysql_query($query_RPall, $connvbsa) or die(mysql_error());
$row_RPall = mysql_fetch_assoc($RPall);
$totalRows_RPall = mysql_num_rows($RPall);

mysql_select_db($database_connvbsa, $connvbsa);
//echo($query_rp_curr . "<br>");
$rp_curr = mysql_query($query_rp_curr, $connvbsa) or die(mysql_error());
$row_rp_curr = mysql_fetch_assoc($rp_curr);
$totalRows_rp_curr = mysql_num_rows($rp_curr);

mysql_select_db($database_connvbsa, $connvbsa);
$rp_1yr = mysql_query($query_rp_1yr, $connvbsa) or die(mysql_error());
$row_rp_1yr = mysql_fetch_assoc($rp_1yr);
$totalRows_rp_1yr = mysql_num_rows($rp_1yr);

mysql_select_db($database_connvbsa, $connvbsa);
$rp_2yr = mysql_query($query_rp_2yr, $connvbsa) or die(mysql_error());
$row_rp_2yr = mysql_fetch_assoc($rp_2yr);
$totalRows_rp_2yr = mysql_num_rows($rp_2yr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1curr = "SELECT total_RP  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( )) AND game_type='Snooker' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S1curr = mysql_query($query_S1curr, $connvbsa) or die(mysql_error());
$row_S1curr = mysql_fetch_assoc($S1curr);
$totalRows_S1curr = mysql_num_rows($S1curr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2curr = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))  AND game_type='Snooker' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S2curr = mysql_query($query_S2curr, $connvbsa) or die(mysql_error());
$row_S2curr = mysql_fetch_assoc($S2curr);
$totalRows_S2curr = mysql_num_rows($S2curr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1_1year = "SELECT total_RP  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S1_1year = mysql_query($query_S1_1year, $connvbsa) or die(mysql_error());
$row_S1_1year = mysql_fetch_assoc($S1_1year);
$totalRows_S1_1year = mysql_num_rows($S1_1year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2_1year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S2_1year = mysql_query($query_S2_1year, $connvbsa) or die(mysql_error());
$row_S2_1year = mysql_fetch_assoc($S2_1year);
$totalRows_S2_1year = mysql_num_rows($S2_1year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1_2year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S1_2year = mysql_query($query_S1_2year, $connvbsa) or die(mysql_error());
$row_S1_2year = mysql_fetch_assoc($S1_2year);
$totalRows_S1_2year = mysql_num_rows($S1_2year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2_2year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
$S2_2year = mysql_query($query_S2_2year, $connvbsa) or die(mysql_error());
$row_S2_2year = mysql_fetch_assoc($S2_2year);
$totalRows_S2_2year = mysql_num_rows($S2_2year);

$total_points = Round((ROUND($row_S1curr['total_RP']))+(ROUND($row_S2curr['total_RP']))+(ROUND($row_S1_1year['total_RP']*65/100))+(ROUND($row_S2_1year['total_RP']*65/100))+(ROUND($row_S1_2year['total_RP']*35/100))+(ROUND($row_S2_2year['total_RP']*35/100)));

$total_15_pc = Round($total_points*0.15);

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
    <td align="left"><span class=red_bold>
      <strong><?php echo date("Y"); ?> Victorian Snooker Ranking for: <?php echo $row_RPall['FirstName']; ?> <?php echo $row_RPall['LastName']; ?></strong></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left">Tournament Points =  <strong><?php echo ($row_RPall['total_tourn']+$total_15_pc); ?></strong> which includes 15% of Weekly points = <strong><?php echo $total_15_pc; ?></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="500" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="3" class="italic"><b><?php echo date("Y"); ?> Tournament Ranking points - current (100%)</b></td>
  </tr>
  <?php 
  if(($totalRows_rp_curr == 0) || ($row_rp_curr['rank_pts'] == 0))
  { 
    echo("<tr>");
    echo("<td colspan='3' class='text-left'>Did not compete</td>");
    echo("</tr>");
  } 
  else
  {
    echo("<tr>");
    echo("<td class='text-left'>Tournament Name</td>");
    echo("<td class='text-center'>Points Won</td>");
    echo("<td class='text-center'>100%</td>");
    echo("</tr>");
    do
    { 
      if($row_rp_curr['rank_pts']>0) 
      { 
        echo("<tr>");
        echo("<td class='text-left' width=60%>" . $row_rp_curr['tourn_name'] . "</td>");
        echo("<td class='text-center' width=20%>" .  $row_rp_curr['rank_pts'] . "</td>");
        echo("<td class='text-center' width=20%>" . $row_rp_curr['rank_pts'] . "</td>");
        echo("</tr>");
      }
    } 
    while ($row_rp_curr = mysql_fetch_assoc($rp_curr));
  } 
?>
</table>
<table width="500" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="3" class="italic"><b><?php echo date("Y")-1; ?> Tournament Ranking points - 1 Year (65%)</b></td>
  </tr>
  <?php 
  if(($totalRows_rp_1yr == 0) || ($row_rp_1yr['rank_pts'] == 0))
  { 
    echo("<tr>");
    echo("<td colspan='3' class='text-left'>Did not compete</td>");
    echo("</tr>");
  } 
  else
  {
    echo("<tr>");
    echo("<td class='text-left'>Tournament Name</td>");
    echo("<td class='text-center'>Points Won</td>");
    echo("<td class='text-center'>65%</td>");
    echo("</tr>");
    do
    { 
      if($row_rp_1yr['rank_pts']>0) 
      { 
        echo("<tr>");
        echo("<td class='text-left' width=60%>" . $row_rp_1yr['tourn_name'] . "</td>");
        echo("<td class='text-center' width=20%>" .  $row_rp_1yr['rank_pts'] . "</td>");
        echo("<td class='text-center' width=20%>" . (ROUND($row_rp_1yr['rank_pts']*65/100)) . "</td>");
        echo("</tr>");
      }
    } 
    while ($row_rp_1yr = mysql_fetch_assoc($rp_1yr));
  } 
?>
</table>
<table width="500" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="3" class="italic"><b><?php echo date("Y")-2; ?> Tournament Ranking points - 2 Year (35%)</b></td>
  </tr>
  <?php 
  if(($totalRows_rp_2yr == 0)  || ($row_rp_2yr['rank_pts'] == 0))
  { 
    echo("<tr>");
    echo("<td colspan='3' class='text-left'>Did not compete</td>");
    echo("</tr>");
  } 
  else
  {
    echo("<tr>");
    echo("<td class='text-left'>Tournament Name</td>");
    echo("<td class='text-center'>Points Won</td>");
    echo("<td class='text-center'>35%</td>");
    echo("</tr>");
    do
    { 
      if($row_rp_2yr['rank_pts']>0) 
      { 
        echo("<tr>");
        echo("<td class='text-left' width=60%>" . $row_rp_2yr['tourn_name'] . "</td>");
        echo("<td class='text-center' width=20%>" .  $row_rp_2yr['rank_pts'] . "</td>");
        echo("<td class='text-center' width=20%>" . (ROUND($row_rp_2yr['rank_pts']*35/100)) . "</td>");
        echo("</tr>");
      }
    } 
    while ($row_rp_2yr = mysql_fetch_assoc($rp_2yr));
  } 
?>
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
  <tr>
    <td width=60% class="text-left"><span class="italic">Total Tournament Ranking Points</span></td>
    <td class='text-center' width=20%>&nbsp;</td>
    <td class='text-center' width=20%><strong><?php echo ($row_RPall['total_tourn']); ?></strong></td>
  </tr>
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
</table>
<table width="500" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="6" class="text-center italic">Calculation of Weekly Ranking Points (to the nearest whole number)</td>
  </tr>
  <tr>
    <td><?php echo date("Y", strtotime(date("Y-m-d"))); ?> - S1 Best total Ranking Points</td>
    <td class="text-center"><?php if(isset($row_S1curr['total_RP'])) echo round($row_S1curr['total_RP']); else echo "0"; ?></td>
    <td class="text-center">x</td>
    <td class="text-center">100%</td>
    <td class="text-center">=</td>
    <td class="text-center"><?php if(isset($row_S1curr['total_RP'])) echo round($row_S1curr['total_RP']); else echo "0"; ?></td>
  </tr>
  <tr>
    <td><?php echo date("Y", strtotime(date("Y-m-d"))); ?> - S2 Best total Ranking Points</td>
    <td class="text-center"><?php if(isset($row_S2curr['total_RP'])) echo round($row_S2curr['total_RP']); else echo "0"; ?></td>
    <td class="text-center">x</td>
    <td class="text-center">100%</td>
    <td class="text-center">=</td>
    <td class="text-center"><?php if(isset($row_S2curr['total_RP'])) echo round($row_S2curr['total_RP']); else echo "0"; ?></td>
  </tr>
  <tr>
    <td><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?> - S1 Best total Ranking Points </td>
    <td class="text-center"><?php if(isset($row_S1_1year['total_RP'])) echo round($row_S1_1year['total_RP']); else echo "0"; ?></td>
    <td class="text-center">x</td>
    <td class="text-center">65%</td>
    <td class="text-center">=</td>
    <td class="text-center"><?php if(isset($row_S1_1year['total_RP'])) echo round($row_S1_1year['total_RP']*65/100); else echo "0"; ?></td>
  </tr>
  <tr>
    <td><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?>< - S2 Best total Ranking Points </td>
    <td class="text-center"><?php if(isset($row_S2_1year['total_RP'])) echo round($row_S2_1year['total_RP']); else echo "0"; ?></td>
    <td class="text-center">x</td>
    <td class="text-center">65%</td>
    <td class="text-center">=</td>
    <td class="text-center"><?php if(isset($row_S2_1year['total_RP'])) echo round($row_S2_1year['total_RP']*65/100); else echo "0"; ?></td>
  </tr>
  <tr>
    <td><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?>< - S1 Best total Ranking Points </td>
    <td class="text-center"><?php if(isset($row_S1_2year['total_RP'])) echo round($row_S1_2year['total_RP']); else echo "0"; ?></td>
    <td class="text-center">x</td>
    <td class="text-center">35%</td>
    <td class="text-center">=</td>
    <td class="text-center"><?php if(isset($row_S1_2year['total_RP'])) echo round($row_S1_2year['total_RP']*35/100); else echo "0"; ?></td>
  </tr>
  <tr>
    <td><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?> -  S2 Best total Ranking Points</td>
    <td class="text-center"><?php if(isset($row_S2_2year['total_RP'])) echo round($row_S2_2year['total_RP']); else echo "0"; ?></td>
    <td class="text-center">x</td>
    <td class="text-center">35%</td>
    <td class="text-center">=</td>
    <td class="text-center"><?php if(isset($row_S2_2year['total_RP'])) echo round($row_S2_2year['total_RP']*35/100); else echo "0"; ?></td>
  </tr>
  <tr>
    <td colspan=6>&nbsp;</td>
  </tr>
  <tr>
    <td>Total Weekly Ranking Points</td>
    <td class="text-center"><?php echo $total_points; ?></td>
    <td class="text-center">x</td>
    <td class="text-center">15%</td>
    <td class="text-center">=</td>
    <td class="text-center"><strong><?php echo $total_15_pc; ?></strong></td>
  </tr>
</table>
</body>
</html>
