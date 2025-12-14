<?php require_once('../Connections/connvbsa.php'); 

error_reporting(0);

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

$year = "-1";
if (isset($_GET['year'])) {
$year = $_GET['year'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_curr_tourn = "Select distinct tourn_name, tournament_results.tourn_id FROM tournaments Left Join tournament_results on tournaments.tourn_id = tournament_results.tourn_id where tournament_results.tourn_id != '' and (ranking_type = 'None' or ranking_type = 'No Entry') ORDER BY tournament_results.tourn_id";
$curr_tourn = mysql_query($query_curr_tourn, $connvbsa) or die(mysql_error());
$row_curr_tourn = mysql_fetch_assoc($curr_tourn);
//echo($query_curr_tourn . "<br>");

$query_rank_tourn = "Select distinct tourn_name, tournament_results.tourn_id FROM tournaments Left Join tournament_results on tournaments.tourn_id = tournament_results.tourn_id where tournament_results.tourn_id != '' and (ranking_type = 'Victorian' or ranking_type = 'Womens' or ranking_type = 'Junior') ORDER BY tournament_results.tourn_id";
$rank_tourn = mysql_query($query_rank_tourn, $connvbsa) or die(mysql_error());
$row_rank_tourn = mysql_fetch_assoc($rank_tourn);
//echo($query_rank_tourn . "<br>");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Tournament Results</title>
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
  </div><!--End Bootstrap Container--> 

  <div id="Wrapper">
    <div class="row"> 
      <div class="Page_heading_container">
     		<div class="page_title">Tournament results for <?php echo $year; ?></div>
      </div>  	
      <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
    </div>
  <!-- Include link to previous page -->
  <?php include '../includes/prev_page.php';?>
  <br>
  <br>    
  <div class="table-responsive center-block" style="max-width:300px; clear:both"> <!-- class table-responsive -->
    <table class='table table-striped table-bordered table-responsive' >
        <tr>
          <td style='text-align: center; background-color: #e60000; color: white' >Ranking Events</td>
        </tr>
        <?php do { ?>
        <tr>
          <td align="center"><a href='../Admin_Tournaments/tournament_draw_public.php?tourn_id=<?= $row_rank_tourn['tourn_id'] ?>'><?php echo $row_rank_tourn['tourn_name']; ?></a></td>
        </tr>
        <?php } while ($row_rank_tourn = mysql_fetch_assoc($rank_tourn)); ?>
    </table>
  </div>

  <div class="table-responsive center-block" style="max-width:300px; clear:both"> <!-- class table-responsive -->
    <table class='table table-striped table-bordered table-responsive' >
      <tr>
        <td style='text-align: center; background-color: #e60000; color: white' >Non Ranking Events</td>
      </tr>
      <?php do { ?>
      <tr>
        <td align="center"><a href='../Admin_Tournaments/tournament_draw_public.php?tourn_id=<?= $row_curr_tourn['tourn_id'] ?>'><?php echo $row_curr_tourn['tourn_name']; ?></a></td>
      </tr>
      <?php } while ($row_curr_tourn = mysql_fetch_assoc($curr_tourn)); ?>
    </table>
  </div>
  <!-- Footer -->  
  <div class="table-condensed center-block" style="max-width:600px"> 
    <table class="table">
      <tr>
        <td align="center">If you have information that is not currently available please contact <a href="mailto:mediatech@vbsa.org.au">mediatech@vbsa.org.au</a></td>
      </tr>
    </table>
  </div> 
</div>
<!-- close conraineing wrapper -->   
</body>
</html>

