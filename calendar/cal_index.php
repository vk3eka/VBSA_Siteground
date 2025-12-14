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

$cal_year = date("Y");

if (isset($_GET['cal_year'])) {
  $cal_year = $_GET['cal_year'];
}
elseif(isset($_POST['CalYear']))
{
  $cal_year = $_POST['CalYear'];
}

$filter = '';
$whereby = '';

if(isset($_POST['state']) && ($_POST['state'] != ""))
{
  $varstate = $_POST['state'];
  if($varstate == 'ALL')
  {
   $filter = ""; 
  }
  else
  {
    $filter = " AND lower(state) = '" . strtolower($varstate) . "'";
  }
}
else
{
  $filter = '';
}

if(isset($_POST['vic_rank']) && ($_POST['vic_rank'] != ""))
{
  $varRank = $_POST['vic_rank'];
  switch ($varRank) {
    case 'None':
      $whereby = " and ranking_type = 'None'";
      break;
    //case 'National':
    //  $whereby = " and ranking_type = 'National'";
    //  break;
    case 'National':
      $whereby = " and ((aust_rank = 'Yes') OR (aust_rank = 'Yes' AND ranking_type = 'Victorian') OR (aust_rank = 'Yes' AND ranking_type = 'Womens') OR (aust_rank = 'Yes' AND ranking_type = 'Junior'))";
      break;
    case 'Victorian':
      $whereby = " and ranking_type = 'Victorian'";
      break;
    case 'Womens':
      $whereby = " and ranking_type = 'Womens'";
      break;
    case 'Junior':
      $whereby = " and ranking_type = 'Junior'";
      break;
    default:
      $whereby = "";
      break;
  }

}
else
{
  $filter = '';
}

$filter = $filter . " " . $whereby;

// month variable
$month = date('m');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Calendar</title>
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

<script type='text/javascript'>

function GetState(sel) {
  var state = sel.options[sel.selectedIndex].value;
  document.getElementById("state").value = state;
  var year = <?= $cal_year ?>;
  document.cal_state.vic_rank.value = document.getElementById("vic_rank").value
  document.getElementById("vic_rank").selected = document.getElementById("vic_rank").value
  document.cal_state.CalYear.value = year;
  document.cal_state.submit();
}

function GetVicRank(sel) {
  var rank = sel.options[sel.selectedIndex].value;
  document.getElementById("vic_rank").value = rank;
  var year = <?= $cal_year ?>;
  document.cal_state.state.value = document.getElementById("state").value
  document.getElementById("state").selected = document.getElementById("state").value
  document.cal_state.CalYear.value = year;
  document.cal_state.submit();
}

</script>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

<div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">Calendar of events - All Events for <?php echo date("Y"); ?></div>
  </div>
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div>   
</div>
    
<div class="row"> 
  		 <div class="text_box text-center">
         For More information, Entry Form etc, select "More". <br /><br />
         <a href="cal_next_yr.php" class="btn btn-primary btn-xs " role="button">Proposed calendar for  <?php echo date("Y")+1; ?></a>
  		</div>
  </div>  
<form name='cal_state'  method="post" action='cal_index.php'>  
<table class="table">
	<input type='hidden' name='CalYear'name='CalYear' value='<?php echo date("Y"); ?>'>
  <tr>
    <td colspan="2" align="center">
      <label for="state">Filter by State:</label>
      <select name="state" id="state" onchange="GetState(this)">
        <?php 
        if((isset($_POST['state'])) && ($_POST['state'] != ''))
        {
          echo("<option value='" . $_POST['state'] . "'>" . $_POST['state'] . "</option>");
        }
        ?>
        <option value="ALL">All</option>
        <option value="ACT">ACT</option>
        <option value="NSW">NSW</option>
        <option value="NT">NT</option>
        <option value="QLD">QLD</option>
        <option value="SA">SA</option>
        <option value="TAS">TAS</option>
        <option value="VIC">VIC</option>
        <option value="WA">WA</option>
      </select>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <label for="vic_rank">Filter by Ranking Type:</label>
      <select name="vic_rank" id="vic_rank" onchange="GetVicRank(this)">
      <?php 
      if((isset($_POST['vic_rank'])) && ($_POST['vic_rank'] != ''))
      {
        echo("<option value='" . $_POST['vic_rank'] . "'>" . $_POST['vic_rank'] . "</option>");
      }
      ?>
        <option value="ALL">All</option>
        <option value="None">None</option>
        <option value="National">National</option>
        <option value="Victorian">Victorian</option>
        <option value="Womens">Womens</option>
        <option value="Junior">Junior</option>
      </select>
    </td>
  </tr>
</table>
</form>
<?php
$total_records = 0;
mysql_select_db($database_connvbsa, $connvbsa);
for($month = 1; $month <= 12; $month++)
{
  switch($month)
  {
    case 1:
      $month = 1;
      $title = 'January';
      break;
    case 2:
      $month = 2;
      $title = 'February';
      break;
    case 3:
      $month = 3;
      $title = 'March';
      break;
    case 4:
      $month = 4;
      $title = 'April';
      break;
    case 5:
      $month = 5;
      $title = 'May';
      break;
    case 6:
      $month = 6;
      $title = 'June';
      break;
    case 7:
      $month = 7;
      $title = 'July';
      break;
    case 8:
      $month = 8;
      $title = 'August';
      break;
    case 9:
      $month = 9;
      $title = 'September';
      break;
    case 10:
      $month = 10;
      $title = 'October';
      break;
    case 11:
      $month = 11;
      $title = 'November';
      break;
    case 12:
      $month = 12;
      $title = 'December';
      break;
  }
  $query_Cal_01 = "Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate, visible, event_number, attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE ((YEAR(startdate) = $cal_year AND MONTH(startdate) = " . $month . "))  " . $filter . " and calendar.visible = 'Yes'  GROUP BY calendar.event_id ORDER BY calendar.startdate";

  //$query_Cal_01 = "Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) = " . $month . " " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
  //echo($query_Cal_01 . "<br>");
  $Cal_01 = mysql_query($query_Cal_01, $connvbsa) or die(mysql_error());
  $row_Cal_01 = mysql_fetch_assoc($Cal_01);
  $totalRows_Cal_01 = mysql_num_rows($Cal_01);mysql_select_db($database_connvbsa, $connvbsa);
  $total_records = ($total_records + $totalRows_Cal_01);
  include "cal_monthly.php";
}
?>

</div>  <!-- close containing wrapper --> 
</body>
</html>

