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

$colname_confirmed = "-1";
if (isset($_GET['tournID'])) {
  $colname_confirmed = $_GET['tournID'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_confirmed = sprintf("SELECT members.MemberID, members.LastName, members.FirstName, tourn_entry.tourn_memb_id, tourn_entry.tournament_number, tourn_entry.amount_entry, tourn_entry.entered_by, tourn_entry.how_paid, tourn_entry.seed, tourn_entry.junior_cat, tourn_entry.tourn_date_ent, members.Email, members.MobilePhone FROM tourn_entry, members WHERE tournament_number = %s AND members.MemberID=tourn_memb_id AND entry_confirmed=1 ORDER BY members.FirstName, tourn_entry.junior_cat", GetSQLValueString($colname_confirmed, "int"));
$confirmed = mysql_query($query_confirmed, $connvbsa) or die(mysql_error());
$row_confirmed = mysql_fetch_assoc($confirmed);
$totalRows_confirmed = mysql_num_rows($confirmed);

$colname_tourn1 = "-1";
if (isset($_GET['tournID'])) {
  $colname_tourn1 = $_GET['tournID'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = sprintf("SELECT * FROM tournaments WHERE tourn_id = %s", GetSQLValueString($colname_tourn1, "int"));
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

$colname_unconfirmed = "-1";
if (isset($_GET['tournID'])) {
  $colname_unconfirmed = $_GET['tournID'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_unconfirmed = sprintf("SELECT members.MemberID, members.LastName, members.FirstName, tourn_entry.tourn_memb_id, tourn_entry.tournament_number, tourn_entry.amount_entry, tourn_entry.entered_by, tourn_entry.how_paid, tourn_entry.seed, tourn_entry.junior_cat, tourn_entry.tourn_date_ent, members.Email, members.MobilePhone FROM tourn_entry, members WHERE tournament_number = %s AND members.MemberID=tourn_memb_id AND entry_confirmed=0 ORDER BY members.FirstName, tourn_entry.junior_cat", GetSQLValueString($colname_unconfirmed, "int"));
$unconfirmed = mysql_query($query_unconfirmed, $connvbsa) or die(mysql_error());
$row_unconfirmed = mysql_fetch_assoc($unconfirmed);
$totalRows_unconfirmed = mysql_num_rows($unconfirmed);
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
 		<div class="page_title"><?php echo $row_tourn1['tourn_name']; ?> Entrants</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

<div class="table-responsive center-block" style="max-width:400px"> <!-- scoring system explained -->
  <table class="table">
  <tr>
  	<td class="italic" nowrap="nowrap">Tournament entry conditions:</td>
  	<td><a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../Tournaments/Tournament_Entry_Conditions.pdf">View </a></td>
  	<td><a href="../Tournament_Entry_Conditions.pdf">Download</a></td>
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
  
<div class="table-responsive center-block" style="max-width:700px"> <!-- confirmed entries -->
  <table class="table">
	  <tr>
	    <td colspan="3" class="italic" align="center"><?php if($totalRows_confirmed>0) echo "Total Confirmed Entries: ".$totalRows_confirmed; else echo "No Entries"; ?></td>
      </tr>
      <?php if($totalRows_confirmed>0) { ?>
	  <tr>
        <th>Name</th>
        <th class="text-center">Seed</th>
        <th class="text-center">Entered on</th>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_confirmed['FirstName'];  echo " ", $row_confirmed['LastName']; ?></td>
          <td class="text-center"><?php echo $row_confirmed['seed']; ?></td>
          <td class="text-center"><?php $newDate = date("d . m", strtotime($row_confirmed['tourn_date_ent'])); echo $newDate; ?></td>
        </tr>
        <?php } while ($row_confirmed = mysql_fetch_assoc($confirmed)); ?>
		<?php } else echo "";?>
    </table>
</div>

<div class="table-responsive center-block" style="max-width:700px"> <!-- unconfirmed entries -->
  <table class="table">
	  <tr>
	    <td colspan="3" class="italic" align="center"><?php if($totalRows_unconfirmed>0) echo "Total Unconfirmed Entries (Not Paid): ".$totalRows_unconfirmed; else echo "No Unconfirmed Entries"; ?></td>
      </tr>
      <?php if($totalRows_unconfirmed>0) { ?>
	  <tr>
        <th>Name</th>
        <th class="text-center">Seed</th>
        <th class="text-center">Entered on</th>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_unconfirmed['FirstName'];  echo " ", $row_unconfirmed['LastName']; ?></td>
          <td class="text-center"><?php echo $row_unconfirmed['seed']; ?></td>
          <td class="text-center"><?php $newDate = date("d . m", strtotime($row_unconfirmed['tourn_date_ent'])); echo $newDate; ?></td>
        </tr>
        <?php } while ($row_unconfirmed = mysql_fetch_assoc($unconfirmed)); ?>
		<?php } else echo "";?>
    </table>
</div>

</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php
mysql_free_result($confirmed);

mysql_free_result($tourn1);

mysql_free_result($unconfirmed);
?>
