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


if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

if (isset($_GET['year'])) {
  $year = $_GET['year'];
}


if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_pts_all = "SELECT scrs.MemberID, team_grade, team_id, FirstName, LastName, pts_won FROM scrs, members WHERE scrs.MemberID = members.MemberID AND scrs.MemberID <>1 AND current_year_scrs = '$year'  AND pts_won >0 AND game_type='$comptype' AND scr_season='$season' ORDER BY pts_won DESC";
$pts_all = mysql_query($query_pts_all, $connvbsa) or die(mysql_error());
$row_pts_all = mysql_fetch_assoc($pts_all);
$totalRows_pts_all = mysql_num_rows($pts_all);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Scores</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />

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
  
  
  <!--Left--> 
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">
        <?php
		// display "Snooker or Billiards"
		if($comptype=='Snooker') echo $comptype . " Frames won in " . $season . " - " . $year ; 
		else echo $comptype . " Points won in " . $season . " - " . $year ;
		?>
        </div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>


  <div class="table-responsive center-block" style="max-width:700px"> <!-- class table-responsive -->
  
    <table class="table">
      <tr>
        <th class="text-center">&nbsp;<?php echo $season.' '.$comptype ?></th>
        <th>&nbsp;</th>
        <th class="text-center">&nbsp;</th>
        <th class="text-center">&nbsp;</th>
      </tr>
      <tr>
      <th class="text-center">Member ID</th>
      <th>Name</th>
      <th class="text-center"><?php if($comptype=='Snooker') echo "Frames"; else echo "Points"; ?></th>
      <th class="text-center">Grade</th>
      </tr>
      
	  <?php  do { ?> 
    <tr>
      <td class="text-center"><?php echo $row_pts_all['MemberID']; ?></td>
      <td nowrap="nowrap"><?php echo $row_pts_all['FirstName']; ?> <?php echo $row_pts_all['LastName']; ?></td>
      <td class="text-center"><?php echo $row_pts_all['pts_won']; ?></td>
      <td class="text-center"><?php echo $row_pts_all['team_grade']; ?></td>
      </tr>
	  <?php } while ($row_pts_all = mysql_fetch_assoc($pts_all));?>
      
     
</table>
  </div>


</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php

?>
