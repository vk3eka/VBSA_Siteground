<?php require_once('../Connections/connvbsa.php'); 
error_reporting(0);
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

mysql_select_db($database_connvbsa, $connvbsa);
//echo("RP All " . $query_RPall . "<br>");
$RPall = mysql_query($query_RPall, $connvbsa) or die(mysql_error());
$row_RPall = mysql_fetch_assoc($RPall);
$totalRows_RPall = mysql_num_rows($RPall);

mysql_select_db($database_connvbsa, $connvbsa);
//echo("RP Current " . $query_rp_curr . "<br>");
$rp_curr = mysql_query($query_rp_curr, $connvbsa) or die(mysql_error());
$row_rp_curr = mysql_fetch_assoc($rp_curr);
$totalRows_rp_curr = mysql_num_rows($rp_curr);

mysql_select_db($database_connvbsa, $connvbsa);
//echo("RP 1 year " . $query_rp_1yr . "<br>");
$rp_1yr = mysql_query($query_rp_1yr, $connvbsa) or die(mysql_error());
$row_rp_1yr = mysql_fetch_assoc($rp_1yr);
$totalRows_rp_1yr = mysql_num_rows($rp_1yr);

mysql_select_db($database_connvbsa, $connvbsa);
//echo("RP 2 year " . $query_rp_2yr . "<br>");
$rp_2yr = mysql_query($query_rp_2yr, $connvbsa) or die(mysql_error());
$row_rp_2yr = mysql_fetch_assoc($rp_2yr);
$totalRows_rp_2yr = mysql_num_rows($rp_2yr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1curr = "SELECT total_RP  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( )) AND game_type='Snooker' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//echo("Season 1 Current " . $query_S1curr . "<br>");
$S1curr = mysql_query($query_S1curr, $connvbsa) or die(mysql_error());
$row_S1curr = mysql_fetch_assoc($S1curr);
$totalRows_S1curr = mysql_num_rows($S1curr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2curr = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))  AND game_type='Snooker' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S2curr = "Select SUM(total_RP) as TotalRP FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( )) AND game_type='Snooker' AND scr_season='S2' GROUP BY memberID";
//echo("Season 2 Current " . $query_S2curr . "<br>");
$S2curr = mysql_query($query_S2curr, $connvbsa) or die(mysql_error());
$row_S2curr = mysql_fetch_assoc($S2curr);
$totalRows_S2curr = mysql_num_rows($S2curr);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1_1year = "SELECT total_RP  FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S1_1year = "Select SUM(total_RP) as TotalRP FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S1' GROUP BY memberID";
//echo("Season 1, 1 year " . $query_S1_1year . "<br>");
$S1_1year = mysql_query($query_S1_1year, $connvbsa) or die(mysql_error());
$row_S1_1year = mysql_fetch_assoc($S1_1year);
$totalRows_S1_1year = mysql_num_rows($S1_1year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2_1year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S2_1year = "Select SUM(total_RP) as TotalRP FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-1 AND game_type='Snooker' AND scr_season='S2' GROUP BY memberID";
//echo("Season 2, 1 year " . $query_S2_1year . "<br>");
$S2_1year = mysql_query($query_S2_1year, $connvbsa) or die(mysql_error());
$row_S2_1year = mysql_fetch_assoc($S2_1year);
$totalRows_S2_1year = mysql_num_rows($S2_1year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S1_2year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S1' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S1_2year = "Select SUM(total_RP) as TotalRP FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S1' GROUP BY memberID";
//echo("Season 1, 2 year " . $query_S1_2year . "<br>");
$S1_2year = mysql_query($query_S1_2year, $connvbsa) or die(mysql_error());
$row_S1_2year = mysql_fetch_assoc($S1_2year);
$totalRows_S1_2year = mysql_num_rows($S1_2year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2_2year = "SELECT total_RP FROM scrs WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S2' GROUP BY scrsID ORDER BY total_rp DESC LIMIT 1";
//$query_S2_2year = "Select SUM(total_RP) as TotalRP FROM scrs  WHERE MemberID='$rank' AND current_year_scrs = YEAR(CURDATE( ))-2 AND game_type='Snooker' AND scr_season='S2' GROUP BY memberID";
//echo("Season 2, 2 year " . $query_S2_2year . "<br>");
$S2_2year = mysql_query($query_S2_2year, $connvbsa) or die(mysql_error());
$row_S2_2year = mysql_fetch_assoc($S2_2year);
$totalRows_S2_2year = mysql_num_rows($S2_2year);

$total_points = Round((ROUND($row_S1curr['total_RP']))+(ROUND($row_S2curr['total_RP']))+(ROUND($row_S1_1year['total_RP']*65/100))+(ROUND($row_S2_1year['total_RP']*65/100))+(ROUND($row_S1_2year['total_RP']*35/100))+(ROUND($row_S2_2year['total_RP']*35/100)));

$total_15_pc = Round($total_points*0.15);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Victorian Rankings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
  </script>
</head>
<body id="vbsa">
    
    <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">Victorian Rankings - Snooker <?= $title ?>, Player detail</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
 <div class="table-condensed" style="padding-bottom:10px">
      <table align="center">
          <tr>
            <td style="padding-right:10px"><input type="button" class="btn-xs btn-default btn-responsive center-block" role="button" value="Return to previous page" onclick="window.history.go(-1); return false;"/></td>
            <td><input type="button" class="btn-xs btn-default btn-responsive center-block" onclick="MM_goToURL('parent','rankings_index.php');return document.MM_returnValue" value="Return to Rankings index page" role="button"/></td>
          </tr>
      </table>
 </div>

  <div class="row"> 
  		 <div class="text_box text-center">
         This list will update every time a tournament or weekly competition is played, <br/> 
         it lists all players that have 20 ranking points or more - Last Update: <?php echo $row_RPall['last_update']; ?></div>
  </div>
  
  <!-- Search Bar -->
  <!--<div class="table-responsive center-block" style="max-width:250px" > 
  <table class="table">
  <tr>
    <td><form id="RP_sname" name="RP_sname" method="get" action="rankings_vic_snooker_name_srch_res.php">
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input name="RP_sname" type="text" id="RP_sname"  placeholder="Search by Surname" />
        </form>
    </td>
  </tr>
  </table>
  </div>-->
<div class="table-responsive center-block" style="max-width:600px" > 
	<table class="table">
        <tr>
          <td class="text-center italic">
            <?php echo date("Y"); ?> Victorian Snooker Ranking for: <?php echo $row_RPall['FirstName']; ?> <?php echo $row_RPall['LastName']; ?></td>
        </tr>
        <tr>
          <td class="text-center"> Tournament Points =  <strong><?php echo ($row_RPall['total_tourn']+$total_15_pc); ?></strong> which includes 15% of Weekly points = <strong><?php echo $total_15_pc; ?></strong></td>
        </tr>
    </table>
</div>
<div class="table-responsive center-block" style="max-width:600px" > 

<table class="table">
  <tr>
  	<td colspan="3" class="italic"><?php echo date("Y"); ?> Tournament Ranking points - current (100%)</td>
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

</div>

<div class="table-responsive center-block" style="max-width:600px" > 
<table class="table">
  <tr>
    <td colspan="3" class="italic"><?php echo date("Y")-1; ?> Tournament Ranking points - 1 Year (65%)</td>
  </tr>
  <?php 
  //echo("Points " . $totalRows_rp_1yr . "<br>");
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

</div>

<div class="table-responsive center-block" style="max-width:600px" > 

<table class="table">
  <tr>
    <td colspan="3" class="italic"><?php echo date("Y")-2; ?> Tournament Ranking points - 2 Year (35%)</td>
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
</table>

</div>

<div class="table-responsive center-block" style="max-width:600px" > 
<table class="table">
    <tr>
    	<td class="text-right"><span class="italic">Total Tournament Ranking Points</span> &nbsp;&nbsp;&nbsp; <strong><?php echo ($row_RPall['total_tourn']); ?></strong></td>
    </tr>
</table>
</div>

<div class="table-responsive center-block" style="max-width:600px" > 
	<table class="table">
        <tr>
          <td colspan="6" class="text-center italic">Calculation of Weekly Ranking Points (to the nearest whole number)</td>
      </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d"))); ?></span> - S1 Best total Ranking Points</td>
          <td class="text-center"><?php if(isset($row_S1curr['total_RP'])) echo round($row_S1curr['total_RP']); else echo "0"; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">100%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S1curr['total_RP'])) echo round($row_S1curr['total_RP']); else echo "0"; ?></td>
        </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d"))); ?></span> - S2 Best total Ranking Points</td>
          <td class="text-center"><?php if(isset($row_S2curr['total_RP'])) echo round($row_S2curr['total_RP']); else echo "0"; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">100%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S2curr['total_RP'])) echo round($row_S2curr['total_RP']); else echo "0"; ?></td>
          </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?></span> - S1 Best total Ranking Points </td>
          <td class="text-center"><?php if(isset($row_S1_1year['total_RP'])) echo round($row_S1_1year['total_RP']); else echo "0"; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">65%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S1_1year['total_RP'])) echo round($row_S1_1year['total_RP']*65/100); else echo "0"; ?></td>
          </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -1 year")); ?></span> - S2 Best total Ranking Points </td>
          <td class="text-center"><?php if(isset($row_S2_1year['total_RP'])) echo round($row_S2_1year['total_RP']); else echo "0"; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">65%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S2_1year['total_RP'])) echo round($row_S2_1year['total_RP']*65/100); else echo "0"; ?></td>
          </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?></span> - S1 Best total Ranking Points </td>
          <td class="text-center"><?php if(isset($row_S1_2year['total_RP'])) echo round($row_S1_2year['total_RP']); else echo "0"; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">35%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S1_2year['total_RP'])) echo round($row_S1_2year['total_RP']*35/100); else echo "0"; ?></td>
          </tr>
        <tr>
          <td><span class="red_bold"><?php echo date("Y", strtotime(date("Y-m-d")." -2 year")); ?></span> -  S2 Best total Ranking Points</td>
          <td class="text-center"><?php if(isset($row_S2_2year['total_RP'])) echo round($row_S2_2year['total_RP']); else echo "0"; ?></td>
          <td class="text-center">x</td>
          <td class="text-center">35%</td>
          <td class="text-center">=</td>
          <td class="text-center"><?php if(isset($row_S2_2year['total_RP'])) echo round($row_S2_2year['total_RP']*35/100); else echo "0"; ?></td>
          </tr>
      </table>
</div>

<div class="table-responsive center-block" style="max-width:600px" > 
<table class="table">
    <tr>
    	<!--<td class="text-right"><span class="italic">Total Weekly Ranking Points <?php echo $row_RPall['scr_total']; ?> X 15% = </span> &nbsp; <strong><?php echo $row_RPall['weekly_percent']; ?></strong></td>-->
      <td class="text-right"><span class="italic">Total Weekly Ranking Points <?php echo $total_points; ?> X 15% = </span> &nbsp; <strong><?php echo $total_15_pc; ?></strong></td>
    </tr>
</table>
</div>

</div>  <!-- close containing wrapper --> 
</body>
</html>

