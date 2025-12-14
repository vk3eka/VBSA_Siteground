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

if(isset($_GET['cohort']) && ($_GET['cohort'] != ''))
{
  $cohort = $_GET['cohort'];
  switch ($cohort)
  {
      case 'all':
      $query_RPall = "Select ranknum, memb_id, FirstName, LastName, Female, total_tourn_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_tourn  LEFT JOIN members ON MemberID = memb_id ORDER BY total_tourn_rp DESC";
      break;
      case 'woman':
        $query_RPall = "Select ranknum, memb_id, FirstName, LastName, Female, total_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_womens  LEFT JOIN members ON MemberID = memb_id where Female = 1 ORDER BY total_rp DESC";
        break;
      case 'junior': 
        $query_RPall = "Select ranknum, memb_id, FirstName, LastName, Female, total_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_junior  LEFT JOIN members ON MemberID = memb_id where Junior != 'na' ORDER BY total_rp DESC";
        break;
  }
}
else
{
  $query_RPall = "Select ranknum, memb_id, FirstName, LastName, Female, total_tourn_rp, date_format(last_update,'%D %b %Y') AS last_update FROM rank_S_open_tourn  LEFT JOIN members ON MemberID = memb_id ORDER BY total_tourn_rp DESC";
}

mysql_select_db($database_connvbsa, $connvbsa);
$RPall = mysql_query($query_RPall, $connvbsa) or die(mysql_error());
$row_RPall = mysql_fetch_assoc($RPall);
$totalRows_RPall = mysql_num_rows($RPall);
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
 		<div class="page_title">Victorian Rankings - Snooker</div>
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
    
  <!-- Search Bar -->
  <div class="table-responsive center-block" style="max-width:250px" > 
  <table class="table">
  <tr>
    <td>
      <form id="RP_sname" name="RP_sname" method="get" action="rankings_vic_snooker_name_srch_res.php">
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input type='hidden' name='cohort'name='cohort' value='<?php echo($_GET['cohort']); ?>'>
        <input name="RP_sname" type="text" id="RP_sname"  placeholder="Search by Surname" />
      </form>
    </td>
  </tr>
  </table>
  </div>


<div class="table-responsive center-block" style="max-width:500px; padding-left:3px"> <!-- class table-responsive -->
 <!--<form name='cohort_selection' method="post" action='rankings_vic_snooker.php'>
  <table class="table">
    <input type='hidden' name='cohort'name='cohort' value='all'>
  <tr>
    <td align='center' width="200" ><a class="btn btn-sm btn-primary" style='width:150px;' href="rankings_vic_snooker.php?cohort=all">All</a></td>
    <td align='center' width="200" ><a class="btn btn-sm btn-primary" style='width:150px;' href="rankings_vic_snooker.php?cohort=woman">Womens</a></td>
    <td align='center' width="200" ><a class="btn btn-sm btn-primary" style='width:150px;' href="rankings_vic_snooker.php?cohort=junior">Juniors</a></td>
  </tr>
</table>
</form>-->
   </div> 
   <div class="table-responsive center-block" style="max-width:750px; padding-left:3px">
   <table class="table">
    <?php 
    // total ranking points need to include 15% of pennant points
    if(!isset($_GET['cohort']))
    { ?>
      <tr>
        <th class="text-center">Currently ranked</th>
        <th class="text-center">Member ID </th>
        <th class="text-left">Name</th>
        <th class="text-center">Total Points</th>
        <th>&nbsp;</th>
      </tr>
      <?php 
        $rank_number = 0;
        do { 
          $rank_number++;
          ?>
        <tr>
          <td class="text-center"><?php echo ($rank_number); ?></td>
          <td class="text-center"><?php echo $row_RPall['memb_id']; ?></td>
          <td class="text-left" nowrap="nowrap"><?php echo $row_RPall['FirstName']. " ". $row_RPall['LastName']; ?> </td>
          <td class="text-center"><?php echo $row_RPall['total_tourn_rp']; ?></td>
          <td class="text-center" nowrap="nowrap"><a href="rankings_vic_snooker_detail.php?rank=<?php echo $row_RPall['memb_id']; ?>">How awarded</a></td>
        </tr>
        <?php } while ($row_RPall = mysql_fetch_assoc($RPall)); ?>
    <?php
    } 
    if($_GET['cohort'] == "all")
    { ?>
      <tr>
        <th class="text-center">Currently ranked</th>
        <th class="text-center">Member ID </th>
        <th class="text-left">Name</th>
        <th class="text-center">Total Points</th>
        <th>&nbsp;</th>
      </tr>
      <?php 
        $rank_number = 0;
        do { 
          $rank_number++;
          ?>
        <tr>
          <td class="text-center"><?php echo ($rank_number); ?></td>
          <td class="text-center"><?php echo $row_RPall['memb_id']; ?></td>
          <td class="text-left" nowrap="nowrap"><?php echo $row_RPall['FirstName']. " ". $row_RPall['LastName']; ?> </td>
          <td class="text-center"><?php echo $row_RPall['total_tourn_rp']; ?></td>
          <td class="text-center" nowrap="nowrap"><a href="rankings_vic_snooker_detail.php?rank=<?php echo $row_RPall['memb_id']; ?>">How awarded</a></td>
        </tr>
        <?php } while ($row_RPall = mysql_fetch_assoc($RPall)); ?>
    <?php
    }
    if($_GET['cohort'] == "woman")
    { ?>
      <tr>
        <!--<th class="text-center">Currently ranked</th>-->
        <th class="text-center">Womens Rank</th>
        <th class="text-center">Member ID </th>
        <th class="text-left">Name</th>
        <th class="text-center">Total Points</th>
        <th>&nbsp;</th>
      </tr>
      <?php 
        $rank_number = 0;
        do { 
          $rank_number++;
          ?>
        <tr>
          <!--<td class="text-center"><?php echo $row_RPall['ranknum']; ?></td>-->
          <td class="text-center"><?php echo ($rank_number); ?></td>
          <td class="text-center"><?php echo $row_RPall['memb_id']; ?></td>
          <td class="text-left" nowrap="nowrap"><?php echo $row_RPall['FirstName']. " ". $row_RPall['LastName']; ?> </td>
          <td class="text-center"><?php echo $row_RPall['total_rp']; ?></td>
          <td class="text-center" nowrap="nowrap"><a href="rankings_vic_snooker_detail.php?rank=<?php echo $row_RPall['memb_id']; ?>">How awarded</a></td>
        </tr>
        <?php } while ($row_RPall = mysql_fetch_assoc($RPall)); ?>
    <?php
    }

    if($_GET['cohort'] == "junior")
    { ?>
      <tr>
        <!--<th class="text-center">Currently ranked</th>-->
        <th class="text-center">Junior Rank</th>
        <th class="text-center">Member ID </th>
        <th class="text-left">Name</th>
        <th class="text-center">Total Points</th>
        <th>&nbsp;</th>
      </tr>
      <?php 
        $rank_number = 0;
        do { 
          $rank_number++;
          ?>
        <tr>
          <!--<td class="text-center"><?php echo $row_RPall['ranknum']; ?></td>-->
          <td class="text-center"><?php echo ($rank_number); ?></td>
          <td class="text-center"><?php echo $row_RPall['memb_id']; ?></td>
          <td class="text-left" nowrap="nowrap"><?php echo $row_RPall['FirstName']. " ". $row_RPall['LastName']; ?> </td>
          <td class="text-center"><?php echo $row_RPall['total_rp']; ?></td>
          <td class="text-center" nowrap="nowrap"><a href="rankings_vic_snooker_detail.php?rank=<?php echo $row_RPall['memb_id']; ?>">How awarded</a></td>
        </tr>
        <?php } while ($row_RPall = mysql_fetch_assoc($RPall)); ?>
    <?php
    }


    ?>
  </table>
  </div>

</div>  <!-- close containing wrapper --> 
</body>
</html>
<?php

?>
