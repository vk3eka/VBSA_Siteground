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

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = "SELECT * FROM tournaments WHERE tourn_year = YEAR(CURDATE()) AND tourn_type='Snooker' AND site_visible='Yes' AND tournaments.status ='Open' ORDER BY tournaments.tourn_name";
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn2 = "SELECT * FROM tournaments WHERE tourn_year = YEAR(CURDATE()) AND tourn_type='Billiards' AND site_visible='Yes' AND tournaments.status ='Open' ORDER BY tournaments.tourn_name";
$tourn2 = mysql_query($query_tourn2, $connvbsa) or die(mysql_error());
$row_tourn2 = mysql_fetch_assoc($tourn2);
$totalRows_tourn2 = mysql_num_rows($tourn2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tournaments</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!--
<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@5.4.54/wasm/openjpeg_nowasm_fallback.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/pdfjs-dist@5.4.54/web/pdf_viewer.min.css" rel="stylesheet">-->

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
 		<div class="page_title"><?php echo date("Y"); ?> Tournaments</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

<div class="table-responsive center-block" style="max-width:400px"> <!-- scoring system explained -->
  <table class="table">
  <tr>
  	<td class="italic" nowrap="nowrap">Tournament entry conditions:</td>

<td style="width:20%">
    <a href="../pdfjs/web/viewer.html?file=../../Tournaments/<?php echo rawurlencode('Tournament_Entry_Conditions_10-05-2021.pdf'); ?>" title="View">
        <span class="glyphicon glyphicon-eye-open"></span>
    </a>
</td>

<td>
  <a href="../Tournaments/<?php echo rawurlencode('Tournament_Entry_Conditions_10-05-2021.pdf'); ?>" title="Download"><span class="glyphicon glyphicon-download"></a></td>


    <!--<a href="../pdfjs/web/viewer.html?file=../../Tournaments/<?php echo rawurlencode('Tournament_Entry_Conditions_10-05-2021.pdf'); ?>" title="View">
        <span class="glyphicon glyphicon-download"></span>
    </a>
</td>-->

    </tr>
    </table>
  </div>

<div class="table-responsive center-block" style="max-width:700px"> <!-- scoring system explained -->
  <table class="table">
	  <tr>
	    <td class="text-center">For VBSA run tournament results  go to the <a href="../calendar/cal_index.php">Calendar</a></td>
	    </tr>
	  <tr>
	    <td class="text-center">For all Australian tournament results go to - <a href="http://www.absc.com.au" target="_blank">www.absc.com.au</a></td>
	    </tr>
	  <tr>
	    <td class="text-center">For Previous tournament results go to the <a href="../Archives/ArchiveIndex.php">Archives</a></td>
	    </tr>
</table>
</div>


<div class="table-responsive center-block" style="max-width:700px">
  <table class="table">
  <tr>
    <td colspan="5" class="italic">Snooker Tournaments</td>
  </tr>
  <tr>
    <th class="text-center">Tourn ID</th>
    <th nowrap="nowrap">Tournament Title</th>
    <th>Year</th>
    <th nowrap="nowrap">Tournament Class</th>
    <th></th>
  </tr>
  <?php if(mysql_num_rows(mysql_query($query_tourn1)) >0 ) { do { ?>
    <tr>
      <td class="text-center"><?php echo $row_tourn1['tourn_id']; ?></td>
      <td nowrap="nowrap"><?php echo $row_tourn1['tourn_name']; ?></td>
      <td><?php $newDate = date("Y", strtotime($row_tourn1['tourn_year'])); echo $newDate; ?></td>
      <td nowrap="nowrap"><?php echo $row_tourn1['tourn_class']; ?></td>
      <td class="page"><a href="tourn_detail.php?tournID=<?php echo $row_tourn1['tourn_id']; ?>">Entries</a></td>
      </tr>
    <?php } while ($row_tourn1 = mysql_fetch_assoc($tourn1));  } 
			else echo '<tr>'.'<td colspan="5" nowrap="nowrap" class="italic">'."No scheduled tournaments.".'</td>'.'</tr>';?>
</table>
</div>

<div class="table-responsive center-block" style="max-width:700px">
  <table class="table">
  <tr>
    <td colspan="5" class="italic">Billiard Tournaments</td>
  </tr>
  </tr>
  <tr>
    <th class="text-center">Tourn ID</th>
    <th>Tournament Title</th>
    <th>Year</th>
    <th>Tournament Class</th>
    <th>&nbsp;</th>
  </tr>
  <?php if(mysql_num_rows(mysql_query($query_tourn2)) >0 ) { do { ?>
  <tr>
    <td align="center"><?php echo $row_tourn2['tourn_id']; ?></td>
    <td><?php echo $row_tourn2['tourn_name']; ?></td>
    <td><?php $newDate = date("Y", strtotime($row_tourn2['tourn_year'])); echo $newDate; ?></td>
    <td><?php echo $row_tourn2['tourn_class']; ?></td>
    <td class="page"><a href="tourn_detail.php?tournID=<?php echo $row_tourn2['tourn_id']; ?>">Entries</a></td>
  </tr>
  <?php } while ($row_tourn2 = mysql_fetch_assoc($tourn2));  } 
			else echo '<tr>'.'<td colspan="5" nowrap="nowrap" class="italic">'."No scheduled tournaments.".'</td>'.'</tr>';?>
</table>
</div>

</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php

?>
