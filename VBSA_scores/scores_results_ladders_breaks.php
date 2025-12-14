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

$colname_brks = "-1";
if (isset($_GET['season'])) {
  $colname_brks = $_GET['season'];
}
$year = "-1";
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}
$col2_brks = "-1";
if (isset($_GET['comptype'])) {
  $col2_brks = $_GET['comptype'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_brks = sprintf("Select member_ID_brks, FirstName, LastName, brk, grade, finals_brk, date_format(  recvd, '%%b %%e, %%Y') AS recvd FROM breaks, members WHERE breaks.member_ID_brks=members.MemberID AND season = %s AND brk_type=%s AND YEAR( recvd ) = '$year' AND brk > 19 ORDER BY brk DESC", GetSQLValueString($colname_brks, "text"),GetSQLValueString($col2_brks, "text"));
$brks = mysql_query($query_brks, $connvbsa) or die(mysql_error());
$row_brks = mysql_fetch_assoc($brks);
$totalRows_brks = mysql_num_rows($brks);
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
		echo "All ". $colname_brks . " " . $col2_brks . " Breaks in " . $year ;
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
      <th class="text-center">Player ID</th>
      <th>Name</th>
      <th class="text-center">Break</th>
      <th class="text-center">Grade</th>
      <th class="text-center">Finals?</th>
      <th class="text-center">Recorded</th>
    </tr>
    <?php do { ?>
    <tr>
      <td class="text-center"><?php echo $row_brks['member_ID_brks']; ?></td>
      <td><?php echo $row_brks['FirstName']; ?> <?php echo $row_brks['LastName']; ?></td>
      <td class="text-center"><?php echo $row_brks['brk']; ?></td>
      <td class="text-center"><?php echo $row_brks['grade']; ?></td>
      <td class="text-center"><?php echo $row_brks['finals_brk']; ?></td>
      <td class="text-center"><?php echo $row_brks['recvd']; ?></td>
    </tr>
    <?php } while ($row_brks = mysql_fetch_assoc($brks)); ?>
</table>
  </div>


</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php

?>
