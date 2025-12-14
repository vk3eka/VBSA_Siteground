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

if (isset($_GET['RP_sname'])) {
  $RP_sname = $_GET['RP_sname'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_weeklyRP = "SELECT ranknum, memb_id, FirstName, LastName, total_weekly_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_weekly  LEFT JOIN members ON MemberID = memb_id WHERE LastName LIKE '$RP_sname%' ORDER BY LastName, FirstName";
$weeklyRP = mysql_query($query_weeklyRP, $connvbsa) or die(mysql_error());
$row_weeklyRP = mysql_fetch_assoc($weeklyRP);
$totalRows_weeklyRP = mysql_num_rows($weeklyRP);
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
 		<div class="page_title">Victorian Rankings. Name search results</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
<div class="table-condensed" style="padding-bottom:10px">
      <table align="center">
          <tr>
            <td style="padding-right:10px"><input type="button" class="btn-xs btn-default btn-responsive center-block" role="button" value="Return to previous page" onclick="window.history.go(-1); return false;"/></td>
          </tr>
      </table>
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

<div class="table-responsive center-block" style="max-width:700px"> <!-- class table-responsive -->
  <table class="table">
        <tr>
          <td colspan="5" class="italic">You searched: <?php echo $RP_sname; ?></td>
        </tr>
        <?php if($totalRows_weeklyRP>0) { ?>
        <tr>
          <th align="center">Rank</th>
          <th align="left">Name</th>
          <th class="text-center">Total RP</th>
          <th class="text-center">Ranked</th>
          <th >&nbsp;</th>
        </tr>
        <?php do { ?>
        <tr>
          <td align="center" nowrap="nowrap" class="text-left"><?php echo $row_weeklyRP['ranknum']; ?></td>
          <td align="left" nowrap="nowrap" class="text-left"><?php echo $row_weeklyRP['FirstName']; ?> <?php echo $row_weeklyRP['LastName']; ?></td>
          <td class="text-center"><?php echo $row_weeklyRP['total_weekly_rp']; ?></td>
          <td class="text-center"><?php echo $row_weeklyRP['total_weekly_rp']; ?></td>
          <td nowrap="nowrap"><a href="rankings_weekly_snooker_detail.php?rank=<?php echo $row_weeklyRP['memb_id']; ?>">how these points were awarded</a></td>
        </tr>
        <?php } while ($row_weeklyRP = mysql_fetch_assoc($weeklyRP)); ?>
        <?php } else echo '<tr><td colspan=5 class=italic>'."No results".'</td></td>' ?>
      </table>

</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php

?>
