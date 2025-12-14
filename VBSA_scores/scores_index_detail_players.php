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

$colname_players = "-1";
if (isset($_GET['team_id'])) {
  $colname_players = $_GET['team_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_players = sprintf("SELECT scrs.MemberID, members.FirstName, members.LastName, scrs.team_grade, Team_entries.team_name, scrs.team_id FROM scrs  LEFT JOIN members ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries ON scrs.team_id=Team_entries.team_id WHERE scrs.team_id = %s AND scrs.MemberID>1 AND scrs.MemberID<>1000 ORDER BY FirstName", GetSQLValueString($colname_players, "int"));
$players = mysql_query($query_players, $connvbsa) or die(mysql_error());
$row_players = mysql_fetch_assoc($players);
$totalRows_players = mysql_num_rows($players);
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
 		<div class="page_title"><?php echo date("Y"); ?> VBSA Scores - Billiards & Willis (S2) - Listed players </div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>


  <div class="table-responsive center-block" style="max-width:700px"> <!-- class table-responsive -->
  <table class="table">
    <tr>
      <td class="page_title"><?php echo $row_players['team_name']; ?> (Team ID <?php echo $row_players['team_id']; ?>) </td>
      </tr>
    <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_players['FirstName']; ?> <?php echo $row_players['LastName']; ?></td>
      </tr>
    <?php } while ($row_players = mysql_fetch_assoc($players)); ?>
  </table> 
</div>


</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php

?>
