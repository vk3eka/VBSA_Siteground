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

$tid = "-1";
if (isset($_GET['tid'])) {
  $tid = $_GET['tid'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_arch_tourn = "SELECT * FROM tourn_archives WHERE tournament_ID = '$tid'";
$arch_tourn = mysql_query($query_arch_tourn, $connvbsa) or die(mysql_error());
$row_arch_tourn = mysql_fetch_assoc($arch_tourn);
$totalRows_arch_tourn = mysql_num_rows($arch_tourn);

mysql_select_db($database_connvbsa, $connvbsa);
$query_t_arch = "SELECT ID, tourn_year,  
CONCAT(t1.FirstName, ' ', t1.LastName) AS winner,  
CONCAT(t2.FirstName, ' ', t2.LastName) AS runnerup,  
CONCAT(t3.FirstName, ' ', t3.LastName) AS brkby, 
CONCAT(t4.FirstName, ' ', t4.LastName) AS shared1,
CONCAT(' / ',t5.FirstName, ' ', t5.LastName) AS shared2,
break, brk_comment, venue, why_not_run 
FROM tourn_archives_results 
LEFT JOIN members t1 ON t1.MemberID = win 
LEFT JOIN members t2 ON t2.MemberID = rup 
LEFT JOIN members t3 ON t3.MemberID = brk 
LEFT JOIN members t4 ON t4.MemberID = brk_shared1
LEFT JOIN members t5 ON t5.MemberID = brk_shared2
WHERE tourn_id = '$tid' 
ORDER BY tourn_year DESC";
$t_arch = mysql_query($query_t_arch, $connvbsa) or die(mysql_error());
$row_t_arch = mysql_fetch_assoc($t_arch);
$totalRows_t_arch = mysql_num_rows($t_arch);
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
    
  </div>
</nav>  

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <!--Left--> 
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">Archives for <?php echo $row_arch_tourn['tourn_name']; ?></div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
    <div class="table-condensed" style="margin-left:5%; margin-right:5%">
    <table width="100%" >
      <tr>
        <td style="padding-bottom:10px"><input type="button" class="btn-xs btn-default btn-responsive center-block" role="button" value="Return to previous page" onclick="window.history.go(-1); return false;"/></td>
      </tr>
      <tr>
        <td><?php echo $row_arch_tourn['about']; ?></td>
      </tr>
    </table>
    </div>
    
  <div class="table-responsive center-block"> <!-- class table-responsive -->
    <table cellpadding="3" cellspacing="3" class="table">
      <tr >
            <td class="table_header" align="center">Year</td>
            <td class="table_header" align="left">Winner</td>
            <td class="table_header" align="left">Runner up</td>
            <td class="table_header" align="left">High Break By</td>
            <td class="table_header" align="center">Break</td>
            <td class="table_header" align="left">Venue</td>
    		<td class="table_header" align="left">If not run in this year, reason why</td>
      </tr>
          <?php do { ?>
            <tr>
              <td align="center"><?php echo $row_t_arch['tourn_year']; ?></td>
              <td align="left"><?php echo $row_t_arch['winner']; ?></td>
              <td align="left"><?php echo $row_t_arch['runnerup']; ?></td>
              <td align="left">
				  <?php 	
                    if(!empty($row_t_arch['brkby'])) 
                    echo $row_t_arch['brkby'];
                    else echo $row_t_arch['shared1'].$row_t_arch['shared2'];
                    
                    if (!empty($row_t_arch['brkby']) && !empty($row_t_arch['shared1']) && !empty($row_t_arch['shared2']));
                    echo $row_t_arch['brk_comment'] 
                  ?>
              </td>
              <td align="center"><?php echo $row_t_arch['break']; ?></td>
      <td align="left"><?php echo $row_t_arch['venue']; ?></td>
      <td align="left"><?php echo $row_t_arch['why_not_run']; ?></td>
          </tr>
            <?php } while ($row_t_arch = mysql_fetch_assoc($t_arch)); ?>
      </table>
    </div>
  <div class="table-condensed center-block" style="max-width:800px"> <!-- class table-responsive -->
        <table class="table">
          <tr>
            <td><?php echo $row_arch_tourn['footer']; ?></td>
          </tr>
        </table>
    </div>
  
  
</div><!-- close conraineing wrapper -->   
 
</body>
</html>
<?php

?>
