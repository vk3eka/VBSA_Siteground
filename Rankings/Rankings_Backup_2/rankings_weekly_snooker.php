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
$query_RP_weekly = "SELECT ranknum, memb_id, FirstName, LastName, total_weekly_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_weekly LEFT JOIN members ON MemberID = memb_id WHERE total_weekly_rp>0 ORDER BY ranknum";
//echo($query_RP_weekly . "<BR>");
$RP_weekly = mysql_query($query_RP_weekly, $connvbsa) or die(mysql_error());
$row_RP_weekly = mysql_fetch_assoc($RP_weekly);
$totalRows_RP_weekly = mysql_num_rows($RP_weekly);
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
  <script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
  </script>
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
 		<div class="page_title">Victorian Rankings - Pennant & Willis Snooker</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 <div class="table-condensed" style="padding-bottom:10px">
      <table align="center">
          <tr>
            <td style="padding-right:10px"><input type="button" class="btn-xs btn-default btn-responsive center-block" role="button" value="Return to previous page" onclick="window.history.go(-1); return false;"/></td>
            <td><input type="button" class="btn-xs btn-default btn-responsive center-block" onclick="MM_goToURL('parent','rankings_index.php');return document.MM_returnValue" value="Return to Rankings index page" role="button"/></td>
          </tr>
      </table>
 </div>


  <div class="row"> 
  		 <div class="text-center text_box">
        This list will update every time weekly competition is played, it lists all players. Last Updated: <?php echo $row_RP_weekly['last_update']; ?>
        <br/>(Current rankings will not appear until play starts) </div>
  </div>
  
  
  
  <!-- Search Bar -->
  <div class="table-responsive center-block" style="max-width:250px" > 
  <table class="table">
  <tr>
    <td><form id="RP_sname" name="RP_sname" method="get" action="rankings_weekly_name_srch_res.php">
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input type="text" name="RP_sname" id="RP_sname" placeholder="Search by Surname" />
        </form>
    </td>
  </table>
</div>

 <div class="table-responsive center-block" style="max-width:800px" > 
<table class="table">
        <tr>
          <td align="center">Rank</td>
          <td align="left">Name</td>
          <td align="center" nowrap="nowrap">Total RP</td>
          <td align="left">&nbsp;</td>
        </tr>
        <?php do { ?>
        <tr>
          <td align="center" nowrap="nowrap"><?php echo $row_RP_weekly['ranknum']; ?></td>
          <td align="left" nowrap="nowrap"><?php echo $row_RP_weekly['FirstName']; ?> <?php echo $row_RP_weekly['LastName']; ?></td>
          <td align="center" class="text-center"><?php echo $row_RP_weekly['total_weekly_rp']; ?></td>
          <td align="left" class="text-center text-nowrap"><a href="rankings_weekly_snooker_detail.php?rank=<?php echo $row_RP_weekly['memb_id']; ?>">How these points were awarded</a></td>
        </tr>
        <?php } while ($row_RP_weekly = mysql_fetch_assoc($RP_weekly)); ?>
    </table>
    </div>



</div>  <!-- close containing wrapper --> 
</body>
</html>
<?php

?>
