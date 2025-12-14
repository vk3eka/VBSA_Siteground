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

$year = "-1";
if (isset($_GET['year'])) {
$year = $_GET['year'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_part_clubs = "SELECT team_club, team_cal_year FROM Team_entries WHERE team_cal_year = '$year' AND team_club is not null GROUP BY team_club";
$part_clubs = mysql_query($query_part_clubs, $connvbsa) or die(mysql_error());
$row_part_clubs = mysql_fetch_assoc($part_clubs);
$totalRows_part_clubs = mysql_num_rows($part_clubs);


mysql_select_db($database_connvbsa, $connvbsa);
$query_S1P = "SELECT scrs.team_grade, current_year_scrs, game_type,
(
    SELECT team_name
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1 
    ) AS winner,
    (
    SELECT GFtot
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1 
    ) AS winner_scr,
	(
    SELECT GF_pts
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1 
    ) AS ptswin,
    (
    SELECT team_name
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1,1 
    ) AS RUP,
    (
    SELECT GFtot
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1,1
    ) AS RUP_scr
FROM scrs
WHERE current_year_scrs='$year' AND scr_season = 'S1'
GROUP BY team_grade
ORDER BY game_type ASC, team_grade ASC";
$S1P = mysql_query($query_S1P, $connvbsa) or die(mysql_error());
$row_S1P = mysql_fetch_assoc($S1P);
$totalRows_S1P = mysql_num_rows($S1P);


mysql_select_db($database_connvbsa, $connvbsa);
$query_S2P = "SELECT scrs.team_grade, current_year_scrs, game_type,
(
    SELECT team_name
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1 
    ) AS winner,
    (
    SELECT GFtot
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1 
    ) AS winner_scr,
	(
    SELECT GF_pts
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1 
    ) AS ptswin,
    (
    SELECT team_name
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1,1 
    ) AS RUP,
    (
    SELECT GFtot
	FROM Team_entries 
	WHERE `team_cal_year`='$year' AND Team_entries.team_grade = scrs.team_grade 
	ORDER BY GFtot DESC, GF_pts DESC
	LIMIT 1,1
    ) AS RUP_scr
FROM scrs
WHERE current_year_scrs='$year' AND scr_season = 'S2'
GROUP BY team_grade
ORDER BY game_type ASC, team_grade ASC" ;
$S2P = mysql_query($query_S2P, $connvbsa) or die(mysql_error());
$row_S2P = mysql_fetch_assoc($S2P);
$totalRows_S2P = mysql_num_rows($S2P);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Archives</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="vbsa">

   <!-- Include Google Tracking -->
<?php include_once("includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>
    
</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <!--Left--> 
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">Archived results for <?php echo $row_S1P['current_year_scrs']; ?></div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>
    
  <div class="table-responsive center-block" style="max-width:800px"> <!-- class table-responsive -->
    <table class="table" style=" text-align:left">
        <tr>
            <td align="center" colspan="8" class="table_header"><?php echo $colname_S1P; ?> Season (S1) - * denotes Billiards win on points, or a Snooker draw</td>
        </tr>
       <?php do { ?>
        <tr>
          <?php if($row_S1P['winner_scr']>0) { ?>
          <td><?php echo $row_S1P['team_grade']; ?></td>
          <td><?php echo $row_S1P['game_type']; ?></td>
          <td><?php echo $row_S1P['winner']; ?></td>
          <td><?php echo $row_S1P['winner_scr']; if ($row_S1P['ptswin']==1) echo " *"; ?></td>
          <td class="text-center">v</td>
          <td><?php echo $row_S1P['RUP']; ?></td>
          <td><?php echo $row_S1P['RUP_scr']; ?></td>
          <td align="center"><a href="Premiers_ladder.php?ladder=<?php echo $row_S1P['team_grade']; ?>& year=<?php echo $year; ?>"class="btn-xs btn-primary btn-responsive" role="button">Final Ladder</a></td>
          <?php } else echo '<td>'.$row_S2P['team_grade'].'</td>'.'<td colspan=7 align=center>'."Not played / No Results".'</td>'; ?>
          
        </tr>
		<?php } while ($row_S1P = mysql_fetch_assoc($S1P)); ?>
       
    </table>
  </div>
  <div class="table-responsive center-block" style="max-width:800px">
    <!-- class table-responsive -->
    <table class="table" style=" text-align:left">
      <tr>
        <td align="center" colspan="8" class="table_header"><?php echo $colname_S1P; ?> Season (S2) - * denotes Billiards win on points, or a Snooker draw</td>
      </tr>
      <?php do {  ?>
      <tr>
      <?php if($row_S2P['winner_scr']>0) { ?>
        <td><?php echo $row_S2P['team_grade']; ?></td>
        <td><?php if($row_S2P['team_grade']!='BPBS') echo $row_S2P['game_type']; else echo "2 x 2"; ?></td>
        <td><?php echo $row_S2P['winner']; ?></td>
        <td><?php echo $row_S2P['winner_scr']; if ($row_S2P['ptswin']==1) echo " *"; ?></td>
        <td class="text-center">v</td>
        <td><?php echo $row_S2P['RUP']; ?></td>
        <td><?php echo $row_S2P['RUP_scr']; ?></td>
        <td align="center"><a href="Premiers_ladder.php?ladder=<?php echo $row_S2P['team_grade']; ?>&amp; year=<?php echo $year; ?>"class="btn-xs btn-primary btn-responsive" role="button">Final Ladder</a></td>
        <?php } else echo '<td>'.$row_S2P['team_grade'].'</td>'.'<td colspan=7 align=center>'."Not played / No Results".'</td>'; ?>
      </tr>
      <?php  } while ($row_S2P = mysql_fetch_assoc($S2P));  ?>
    </table>
  </div>
  <div class="table-responsive center-block" style="max-width:300px"> <!-- class table-responsive -->
    <table class="table" style=" text-align:left">
    <tr>
      <td colspan="2" align="center"  class="table_header"><?php echo $row_part_clubs['team_cal_year']; ?> Participating Clubs</td>
      </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_part_clubs['team_club']; ?></td>
        <td align="center"><a href="player_stats.php?stats=<?php echo $row_part_clubs['team_club']; ?>&amp;year=<?php echo $row_part_clubs['team_cal_year']; ?> " class="btn-xs btn-primary btn-responsive" role="button">Player stats</a></td>
        </tr>
      <?php } while ($row_part_clubs = mysql_fetch_assoc($part_clubs)); ?>
  </table>
</div>
  
  <!-- Footer -->  
  <div class="table-condensed center-block" style="max-width:800px"> 
        <table class="table">
          <tr>
            <td align="center">If you have information that is not currently available please contact <a href="mailto:web@vbsa.org.au">web@vbsa.org.au</a></td>
          </tr>
        </table>
  </div> 
  
  
</div><!-- close conraineing wrapper -->   
 
</body>
</html>
<?php

?>
