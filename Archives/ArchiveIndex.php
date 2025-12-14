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
$query_curr_tourn = "Select distinct tourn_name, tournament_results.tourn_id FROM tournaments Left Join tournament_results on tournaments.tourn_id = tournament_results.tourn_id where tournament_results.tourn_id != '' ORDER BY tournament_results.tourn_id";
$curr_tourn = mysql_query($query_curr_tourn, $connvbsa) or die(mysql_error());
$row_curr_tourn = mysql_fetch_assoc($curr_tourn);
$totalRows_curr_tourn = mysql_num_rows($curr_tourn);
//echo($query_curr_tourn . "<br>");

//mysql_select_db($database_connvbsa, $connvbsa);
$query_arch_tourn = "SELECT * FROM tourn_archives ORDER BY arch_order, tourn_name";
$arch_tourn = mysql_query($query_arch_tourn, $connvbsa) or die(mysql_error());
$row_arch_tourn = mysql_fetch_assoc($arch_tourn);
$totalRows_arch_tourn = mysql_num_rows($arch_tourn);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_scr_archive = "SELECT current_year_scrs FROM scrs GROUP BY current_year_scrs ORDER BY current_year_scrs DESC";
$scr_archive = mysql_query($query_scr_archive, $connvbsa) or die(mysql_error());
$row_scr_archive = mysql_fetch_assoc($scr_archive);
$totalRows_scr_archive = mysql_num_rows($scr_archive);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_results_year = "SELECT current_year_scrs FROM scrs GROUP BY current_year_scrs ORDER BY current_year_scrs DESC";
$results_year = mysql_query($query_results_year, $connvbsa) or die(mysql_error());
$row_results_year = mysql_fetch_assoc($results_year);
$totalRows_results_year = mysql_num_rows($results_year);

$query_tournaments_year = "Select distinct tourn_year FROM tournament_results Left Join tournaments on tournaments.tourn_id = tournament_results.tourn_id";
$tournaments_year = mysql_query($query_tournaments_year, $connvbsa) or die(mysql_error());
//$row_tournaments_year = mysql_fetch_assoc($tournaments_year);
//$totalRows_tournaments_year = mysql_num_rows($tournaments_year);
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
<?php //include_once("includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?> 
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>
    
  </div>
</nav> 
</div><!--End Bootstrap Container--> 
<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 
<div class="row"> 
<div class="Page_heading_container">
		<div class="page_title"><span class="h4">Competition Results</span></div>
</div>  	
  
<div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
</div>



<div class="center-block" style="max-width:600px"> 
<div class="text-center table_header">Tournament Results from 2025</div>
<br>
<?php do { ?>
  <div class="text-center" style="float:left; width:25%; margin-top:6px; margin-bottom:6px">    
      <a href="tourn_results_by_year.php?year=<?php echo $row_tournaments_year['tourn_year']; ?>"><?php echo $row_tournaments_year['tourn_year']; ?></a>
  </div>    
<?php } while ($row_tournaments_year = mysql_fetch_assoc($tournaments_year)); ?>        
</div>

<br>
<br>
<br>
  <div class="table-responsive center-block" style="max-width:600px; clear:both"> <!-- class table-responsive -->
  <table class="table">
      <tr>
        <td colspan="3" align="center" class="table_header">Major's Tournament Winners and Finalists</td>
      </tr>
      <tr>
        <th>Tournament Title</th>
        <th>Type</th>
        <td>&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_arch_tourn['tourn_name']; ?></td>
          <td><?php echo $row_arch_tourn['ranked']; ?></td>
          <td><a href="tourn_detail.php?tid=<?php echo $row_arch_tourn['tournament_ID']; ?>">History</a></td>
        </tr>
        <?php } while ($row_arch_tourn = mysql_fetch_assoc($arch_tourn)); ?>
  </table>
  </div>

<!-- spacer -->
<div class="center-block" style="width:100%; height:10px; clear:both"></div>
<!-- end results by year repeat region header -->

<!-- test results by year repeat region header -->
<div class="center-block" style="max-width:600px"> 
<div class="text-center table_header">Pennant Results after 2012 (Database developed in 2013)</div>


          
       <?php do { ?>
            <div class="text-center" style="float:left; width:25%; margin-top:6px; margin-bottom:6px">    
                <a href="results_by_year.php?year=<?php echo $row_results_year['current_year_scrs']; ?>"><?php echo $row_results_year['current_year_scrs']; ?></a>
            </div>    
       <?php } while ($row_results_year = mysql_fetch_assoc($results_year)); ?>
            
</div>

<div class="table-responsive center-block" style="max-width:600px"> <!-- class table-responsive -->
<table class="table">
      <tr>
        <td colspan="4" align="center" class="table_header">Pennant Archive Results to 2012 (before results database)</td>
        </tr>
      <tr>
        <td align="center"><a href="1995.php">1995</a></td>
        <td align="center"><a href="1996.php">1996</a></td>
        <td align="center"><a href="1997.php">1997</a></td>
        <td align="center"><a href="1998.php">1998</a></td>
        </tr>
      <tr>
        <td align="center"><a href="1999.php">1999</a></td>
        <td align="center"><a href="2000.php">2000</a></td>
        <td align="center"><a href="2001.php">2001</a></td>
        <td align="center"><a href="2002.php">2002</a></td>
        </tr>
      <tr>
        <td align="center"><a href="2003.php">2003</a></td>
        <td align="center"><a href="2004.php">2004</a></td>
        <td align="center"><a href="2005.php">2005</a></td>
        <td align="center"><a href="2006.php">2006</a></td>
        </tr>
      <tr>
        <td align="center"><a href="2007.php">2007</a></td>
        <td align="center"><a href="2008.php">2008</a></td>
        <td align="center"><a href="2009.php">2009</a></td>
        <td align="center"><a href="2010.php">2010</a></td>
        </tr>
      <tr>
        <td align="center"><a href="2011.php">2011</a></td>
        <td align="center"><a href="2012.php">2012</a></td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        </tr>
    </table>
</div>

<!-- spacer -->
<div class="center-block" style="width:100%; height:10px; clear:both"></div>
<!-- end test results by year repeat region header -->

  </div><!-- close containeing wrapper -->   
 
</body>
</html>
<?php

?>
