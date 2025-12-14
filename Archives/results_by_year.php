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
$query_S1_grades = "SELECT team_grade, COUNT( Team_entries.team_grade ) AS teams, day_played, comptype FROM Team_entries WHERE team_name <> 'Bye' AND team_season ='S1'  AND `include_draw` = 'Yes' AND team_cal_year = $year GROUP BY team_grade ORDER BY comptype, day_played, team_grade ";
$S1_grades = mysql_query($query_S1_grades, $connvbsa) or die(mysql_error());
$row_S1_grades = mysql_fetch_assoc($S1_grades);
$totalRows_S1_grades = mysql_num_rows($S1_grades);

mysql_select_db($database_connvbsa, $connvbsa);
$query_S2_grades = "SELECT team_grade, COUNT( Team_entries.team_grade ) AS teams, day_played, comptype FROM Team_entries WHERE team_name <> 'Bye' AND team_season ='S2'  AND `include_draw` = 'Yes' AND team_cal_year = $year GROUP BY team_grade ORDER BY comptype, day_played, team_grade ";
$S2_grades = mysql_query($query_S2_grades, $connvbsa) or die(mysql_error());
$row_S2_grades = mysql_fetch_assoc($S2_grades);
$totalRows_S2_grades = mysql_num_rows($S2_grades);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Archives</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

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
 		<div class="page_title">Archived results for <?php echo $year; ?></div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>
    
  <div class="center-block"  style="max-width:600px">
    <table class="table" style=" text-align:left">
    	<tr>
        	<td colspan="5" lign="center"><span class="table_header"> Season 1 (S1) </span></td>
    	</tr>
    	<tr>
    	  <td>Grade</td>
    	  <td>Teams</td>
    	  <td>Day</td>
    	  <td>Game</td>
    	  <td>&nbsp;</td>
      </tr>
    	<?php do { ?>
    	  <tr>
    	    <td><?php echo $row_S1_grades['team_grade']; ?></td>
    	    <td><?php echo $row_S1_grades['teams']; ?></td>
    	    <td><?php echo $row_S1_grades['day_played']; ?></td>
    	    <td><?php echo $row_S1_grades['comptype']; ?></td>
    	    <td align="center" nowrap="nowrap"><a href="Premiers_ladder.php?grade=<?php echo $row_S1_grades['team_grade']; ?>&year=<?php echo $year; ?>"class="btn-sm btn-primary btn-responsive" role="button">More</a></td>
  	    </tr>
   	    <?php } while ($row_S1_grades = mysql_fetch_assoc($S1_grades)); ?>
    </table>
  </div>
  

 <div class="center-block"  style="max-width:600px"> <!-- class table-responsive -->
    <table class="table" style=" text-align:left">
    	<tr>
        	<td colspan="5" lign="center"><span class="table_header"> Season 2 (S2) </span></td>
    	</tr>
    	<tr>
    	  <td>Grade</td>
    	  <td>Teams</td>
    	  <td>Day</td>
    	  <td>Game</td>
    	  <td>&nbsp;</td>
      </tr>
    	<?php do { ?>
    	  <tr>
    	    <td><?php echo $row_S2_grades['team_grade']; ?></td>
    	    <td><?php echo $row_S2_grades['teams']; ?></td>
    	    <td><?php echo $row_S2_grades['day_played']; ?></td>
    	    <td><?php echo $row_S2_grades['comptype']; ?></td>
    	    <td align="center" nowrap="nowrap"><a href="Premiers_ladder.php?grade=<?php echo $row_S2_grades['team_grade']; ?>&year=<?php echo $year; ?>"class="btn-sm btn-primary btn-responsive" role="button">More</a></td>
  	    </tr>
   	    <?php } while ($row_S2_grades = mysql_fetch_assoc($S2_grades)); ?>
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
        <td align="center"><a href="player_stats.php?stats=<?php echo $row_part_clubs['team_club']; ?>&amp;year=<?php echo $row_part_clubs['team_cal_year']; ?> " class="btn-sm btn-primary btn-responsive" role="button">Player stats</a></td>
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
  
  
</div>
<!-- close conraineing wrapper -->   
 
</body>
</html>
<?php

?>
