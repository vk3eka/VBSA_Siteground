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

$col1_player_det = "-1";
if (isset($_GET['stats'])) {
  $col1_player_det = $_GET['stats'];
}
$col2_player_det = "-1";
if (isset($_GET['year'])) {
  $col2_player_det = $_GET['year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_player_det = sprintf("SELECT scrs.scrsID, Team_entries.team_cal_year, Team_entries.team_club, game_type, scr_season, Team_entries.team_name, scrs.team_grade, FirstName, LastName, count_played, pts_won, average_position, percent_won FROM scrs LEFT JOIN Team_entries ON scrs.team_id = Team_entries.team_id LEFT JOIN members ON scrs.MemberID = members.MemberID WHERE Team_entries.team_club = %s AND team_cal_year = %s AND pts_won >0 AND scrs.MemberID != 1 GROUP BY scrs.scrsID ORDER BY scr_season, scrs.team_grade, FirstName", GetSQLValueString($col1_player_det, "text"),GetSQLValueString($col2_player_det, "int"));
$player_det = mysql_query($query_player_det, $connvbsa) or die(mysql_error());
$row_player_det = mysql_fetch_assoc($player_det);
$totalRows_player_det = mysql_num_rows($player_det);

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
    
  </div>
</nav>  

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <!--Left--> 
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title"><?php echo $row_player_det['team_cal_year']; ?> - Player stats for : <?php echo $row_player_det['team_club']; ?></div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
    <!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

    
  <div class="table-responsive center-block" style="max-width:900px"> <!-- class table-responsive -->
  <table class="table">
  <tr>

    <th nowrap="nowrap">Name</th>
    <th nowrap="nowrap">Team Name</th>
    <th nowrap="nowrap" class="text-center">Season</th>
    <th nowrap="nowrap" class="text-center">Grade</th>
    <th nowrap="nowrap" class="text-center">Type</th>
    <th nowrap="nowrap" class="text-center">Matches played</th>
    <th nowrap="nowrap" class="text-center">Frames/points won</th>
    <th nowrap="nowrap" class="text-center">Average Position</th>
    <th nowrap="nowrap" class="text-center">% Won</th>
    
  </tr>
  <?php do { ?>
  <tr>

  <td align="left" nowrap="nowrap"><?php echo $row_player_det['FirstName']; ?> <?php echo $row_player_det['LastName']; ?></td>
  <td align="left"><?php echo $row_player_det['team_name']; ?></td>
  <td align="center"><?php echo $row_player_det['scr_season']; ?></td>
  <td align="center"><?php echo $row_player_det['team_grade']; ?></td>
  <td align="center"><?php echo $row_player_det['game_type']; ?></td>
  <td align="center">
    <?php echo $row_player_det['count_played']; ?></td>
  <td align="center">
    <?php echo $row_player_det['pts_won']; ?></td>
  <td align="center"><?php echo $row_player_det['average_position']; ?></td>
  <td align="center"><?php echo $row_player_det['percent_won']; ?></td>
  </tr>
    
  <?php } while ($row_player_det = mysql_fetch_assoc($player_det)); ?>
  <tr>
  <td colspan="11" align="center"><a href="player_stats_export_to_pdf.php?stats=<?php echo $col1_player_det ?>&amp;year=<?php echo $col2_player_det?>" class="btn-xs btn-primary btn-responsive" role="button">Download PDF</a> </td>
  
  
  </tr>
</table>
  
  </div>
</div><!-- close conraineing wrapper -->   
 
</body>
</html>
<?php

?>
