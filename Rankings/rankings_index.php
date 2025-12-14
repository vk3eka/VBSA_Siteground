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
$query_rank_bill = "SELECT * FROM rank_info WHERE rank_exp_type='Billiards' ORDER BY rank_exp_type, rank_exp_order";
$rank_bill = mysql_query($query_rank_bill, $connvbsa) or die(mysql_error());
$row_rank_bill = mysql_fetch_assoc($rank_bill);
$totalRows_rank_bill = mysql_num_rows($rank_bill);

mysql_select_db($database_connvbsa, $connvbsa);
$query_snooker_update = "SELECT MAX( last_update) AS lastupdate FROM rank_aa_snooker_master";
//$query_snooker_update = "SELECT MAX( last_update) AS lastupdate FROM tourn_RP_co_curr";
$snooker_update = mysql_query($query_snooker_update, $connvbsa) or die(mysql_error());
$row_snooker_update = mysql_fetch_assoc($snooker_update);
$totalRows_snooker_update = mysql_num_rows($snooker_update);

mysql_select_db($database_connvbsa, $connvbsa);
$query_rank_snooker = "SELECT * FROM rank_info WHERE rank_exp_type='Snooker' ORDER BY rank_exp_type, rank_exp_order";
$rank_snooker = mysql_query($query_rank_snooker, $connvbsa) or die(mysql_error());
$row_rank_snooker = mysql_fetch_assoc($rank_snooker);
$totalRows_rank_snooker = mysql_num_rows($rank_snooker);

mysql_select_db($database_connvbsa, $connvbsa);
$query_billiards_update = "SELECT MAX( last_update) AS lastupdate FROM rank_a_billiards_master";
//$query_billiards_update = "SELECT MAX( last_update) AS lastupdate FROM tourn_RP_co_curr";
$billiards_update = mysql_query($query_billiards_update, $connvbsa) or die(mysql_error());
$row_billiards_update = mysql_fetch_assoc($billiards_update);
$totalRows_billiards_update = mysql_num_rows($billiards_update);

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
 		<div class="page_title">Victorian Rankings</div>
  </div>  	
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  <div class="table-responsive center-block" style="max-width:800px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
    <tr>
      <td colspan="4">
       	<div class="text-center">
   		   <a href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
    	   <span class="italic"> = View &nbsp;&nbsp;&nbsp;</span>
    	   <a href="#"><span class="glyphicon glyphicon-download"></span></a>
    		<span class="italic"> = Download</span>
  		</div>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th align="center">VICTORIAN SNOOKER RANKINGS</th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rank_snooker['rank_exp_title']; ?></td>
        <td align="left" class="italic">Last Update: <?php $newDate = date("l jS F Y", strtotime($row_rank_snooker['rank_exp_last_update'])); echo $newDate; ?></td>
        <td><a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#..//Rankings/files/<?php echo $row_rank_snooker['rank_exp_pdf']; ?>" title="View"><span class="glyphicon glyphicon-eye-open"></a></td>
        <td><a href="../Rankings/files/<?php echo $row_rank_snooker['rank_exp_pdf']; ?>" title="Download"><span class="glyphicon glyphicon-download"></a></td>
      </tr>
      <?php } while ($row_rank_snooker = mysql_fetch_assoc($rank_snooker)); ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><a href="rankings_vic_snooker.php"><?php echo date("Y"); ?> Snooker Rankings (Open)</a></td>
      <td colspan="3"><span class="italic">Last Update:
          <?php $newDate = date("l jS F Y", strtotime($row_snooker_update['lastupdate'])); echo $newDate; ?>
      </span></td>
    </tr>
    <tr>
      <td><a href="rankings_vic_snooker_women.php"><?php echo date("Y"); ?> Snooker Rankings (Womens)</a></td>
      <td colspan="3"><span class="italic">Last Update:
          <?php $newDate = date("l jS F Y", strtotime($row_snooker_update['lastupdate'])); echo $newDate; ?>
      </span></td>
    </tr>
    <tr>
      <td><a href="rankings_vic_snooker_junior.php"><?php echo date("Y"); ?> Snooker Rankings (Junior)</a></td>
      <td colspan="3"><span class="italic">Last Update:
          <?php $newDate = date("l jS F Y", strtotime($row_snooker_update['lastupdate'])); echo $newDate; ?>
      </span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td><a href="rankings_weekly_snooker.php">Victorian Pennant, Willis and State Grade Snooker Rankings</a></td>
      <td colspan="3"><span class="italic">Updated weekly</span></td>
    </tr>
    <tr>
      <td class="italic">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th>&nbsp;</th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><b>VICTORIAN BILLIARD RANKINGS</b></td>
      </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rank_bill['rank_exp_title']; ?></td>
        <td align="left" class="italic">Last Update: <?php $newDate = date("l jS F Y", strtotime($row_rank_bill['rank_exp_last_update'])); echo $newDate; ?></td>
        <td><a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#..//Rankings/files/<?php echo $row_rank_bill['rank_exp_pdf']; ?>" title="View"><span class="glyphicon glyphicon-eye-open"></a></td>
        <td><a href="../Rankings/files/<?php echo $row_rank_bill['rank_exp_pdf']; ?>" title="Download"><span class="glyphicon glyphicon-download"></a></td>
    </tr>
    <?php } while ($row_rank_bill = mysql_fetch_assoc($rank_bill)); ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><a href="rankings_vic_billiards.php"><?php echo date("Y"); ?> Billiard Rankings (Open)</a></td>
      <td colspan="3"><span class="italic">Last Update:
          <?php $newDate = date("l jS F Y", strtotime($row_billiards_update['lastupdate'])); echo $newDate; ?>
      </span></td>
    </tr>
    <tr>
      <td><a href="rankings_vic_billiards_womens.php"><?php echo date("Y"); ?> Billiard Rankings (Womens)</a></td>
      <td colspan="3"><span class="italic">Last Update:
          <?php $newDate = date("l jS F Y", strtotime($row_billiards_update['lastupdate'])); echo $newDate; ?>
      </span></td>
    </tr>
    <tr>
      <td><a href="rankings_vic_billiards_junior.php"><?php echo date("Y"); ?> Billiard Rankings (Juniors)</a></td>
      <td colspan="3"><span class="italic">Last Update:
          <?php $newDate = date("l jS F Y", strtotime($row_billiards_update['lastupdate'])); echo $newDate; ?>
      </span></td>
    </tr>
    <!--<tr>
      <td><a href="rankings_vic_billiards_test.php"><?php echo date("Y"); ?> Billiard Tournament Rankings (Test)</a></td>
      <td colspan="3"><span class="italic">Last Update:
          <?php $newDate = date("l jS F Y", strtotime($row_billiards_update['lastupdate'])); echo $newDate; ?>
      </span></td>
    </tr>-->
  </table>
</div>
</div>  <!-- close containing wrapper --> 
</body>
</html>

