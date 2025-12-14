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
/*
mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_01 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =1 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_01 = mysql_query($query_Cal_01, $connvbsa) or die(mysql_error());
$row_Cal_01 = mysql_fetch_assoc($Cal_01);
$totalRows_Cal_01 = mysql_num_rows($Cal_01);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_02 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =2 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_02 = mysql_query($query_Cal_02, $connvbsa) or die(mysql_error());
$row_Cal_02 = mysql_fetch_assoc($Cal_02);
$totalRows_Cal_02 = mysql_num_rows($Cal_02);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_03 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =3 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_03 = mysql_query($query_Cal_03, $connvbsa) or die(mysql_error());
$row_Cal_03 = mysql_fetch_assoc($Cal_03);
$totalRows_Cal_03 = mysql_num_rows($Cal_03);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_04 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =4 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_04 = mysql_query($query_Cal_04, $connvbsa) or die(mysql_error());
$row_Cal_04 = mysql_fetch_assoc($Cal_04);
$totalRows_Cal_04 = mysql_num_rows($Cal_04);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_05 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =5 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_05 = mysql_query($query_Cal_05, $connvbsa) or die(mysql_error());
$row_Cal_05 = mysql_fetch_assoc($Cal_05);
$totalRows_Cal_05 = mysql_num_rows($Cal_05);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_06 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =6 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_06 = mysql_query($query_Cal_06, $connvbsa) or die(mysql_error());
$row_Cal_06 = mysql_fetch_assoc($Cal_06);
$totalRows_Cal_06 = mysql_num_rows($Cal_06);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_07 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =7 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_07 = mysql_query($query_Cal_07, $connvbsa) or die(mysql_error());
$row_Cal_07 = mysql_fetch_assoc($Cal_07);
$totalRows_Cal_07 = mysql_num_rows($Cal_07);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_08 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =8 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_08 = mysql_query($query_Cal_08, $connvbsa) or die(mysql_error());
$row_Cal_08 = mysql_fetch_assoc($Cal_08);
$totalRows_Cal_08 = mysql_num_rows($Cal_08);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_09 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =9 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_09 = mysql_query($query_Cal_09, $connvbsa) or die(mysql_error());
$row_Cal_09 = mysql_fetch_assoc($Cal_09);
$totalRows_Cal_09 = mysql_num_rows($Cal_09);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_10 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =10 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_10 = mysql_query($query_Cal_10, $connvbsa) or die(mysql_error());
$row_Cal_10 = mysql_fetch_assoc($Cal_10);
$totalRows_Cal_10 = mysql_num_rows($Cal_10);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_11 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =11 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_11 = mysql_query($query_Cal_11, $connvbsa) or die(mysql_error());
$row_Cal_11 = mysql_fetch_assoc($Cal_11);
$totalRows_Cal_11 = mysql_num_rows($Cal_11);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_12 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) +1 AND MONTH( startdate ) =12 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_12 = mysql_query($query_Cal_12, $connvbsa) or die(mysql_error());
$row_Cal_12 = mysql_fetch_assoc($Cal_12);
$totalRows_Cal_12 = mysql_num_rows($Cal_12);

// month variable
$month = date('m');
*/
$cal_year = (date("Y")+1);

if (isset($_GET['cal_year'])) {
  $cal_year = $_GET['cal_year'];
}
elseif(isset($_POST['CalYear']))
{
  $cal_year = $_POST['CalYear'];
}
//echo("Year " . $cal_year . "<br>");

//$filter = "";
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
    case 'National':
      $whereby = " and ranking_type = 'National'";
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
  //$filter = $whereby;
}
else
{
  $filter = '';
}

//echo("WhereBy " . $whereby . "<br>");

$filter = $filter . " " . $whereby;
//echo("Filter " . $filter . "<br>");


mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_01 = "Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, closedate,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =1  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_01 = mysql_query($query_Cal_01, $connvbsa) or die(mysql_error());
$row_Cal_01 = mysql_fetch_assoc($Cal_01);
$totalRows_Cal_01 = mysql_num_rows($Cal_01);mysql_select_db($database_connvbsa, $connvbsa);

//echo("SQL " . $query_Cal_01 . "<br>");

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_02 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =2  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_02 = mysql_query($query_Cal_02, $connvbsa) or die(mysql_error());
$row_Cal_02 = mysql_fetch_assoc($Cal_02);
$totalRows_Cal_02 = mysql_num_rows($Cal_02);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_03 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =3  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_03 = mysql_query($query_Cal_03, $connvbsa) or die(mysql_error());
$row_Cal_03 = mysql_fetch_assoc($Cal_03);
$totalRows_Cal_03 = mysql_num_rows($Cal_03);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_04 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =4  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_04 = mysql_query($query_Cal_04, $connvbsa) or die(mysql_error());
$row_Cal_04 = mysql_fetch_assoc($Cal_04);
$totalRows_Cal_04 = mysql_num_rows($Cal_04);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_05 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =5  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_05 = mysql_query($query_Cal_05, $connvbsa) or die(mysql_error());
$row_Cal_05 = mysql_fetch_assoc($Cal_05);
$totalRows_Cal_05 = mysql_num_rows($Cal_05);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_06 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =6  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_06 = mysql_query($query_Cal_06, $connvbsa) or die(mysql_error());
$row_Cal_06 = mysql_fetch_assoc($Cal_06);
$totalRows_Cal_06 = mysql_num_rows($Cal_06);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_07 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =7  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_07 = mysql_query($query_Cal_07, $connvbsa) or die(mysql_error());
$row_Cal_07 = mysql_fetch_assoc($Cal_07);
$totalRows_Cal_07 = mysql_num_rows($Cal_07);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_08 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =8  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_08 = mysql_query($query_Cal_08, $connvbsa) or die(mysql_error());
$row_Cal_08 = mysql_fetch_assoc($Cal_08);
$totalRows_Cal_08 = mysql_num_rows($Cal_08);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_09 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =9  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_09 = mysql_query($query_Cal_09, $connvbsa) or die(mysql_error());
$row_Cal_09 = mysql_fetch_assoc($Cal_09);
$totalRows_Cal_09 = mysql_num_rows($Cal_09);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_10 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =10  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_10 = mysql_query($query_Cal_10, $connvbsa) or die(mysql_error());
$row_Cal_10 = mysql_fetch_assoc($Cal_10);
$totalRows_Cal_10 = mysql_num_rows($Cal_10);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_11 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =11  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_11 = mysql_query($query_Cal_11, $connvbsa) or die(mysql_error());
$row_Cal_11 = mysql_fetch_assoc($Cal_11);
$totalRows_Cal_11 = mysql_num_rows($Cal_11);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_12 = "Select event_id, event, venue, state, aust_rank, startdate, ranking_type, finishdate, closedate,  visible, event_number, attach_name FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate )='$cal_year' AND MONTH( startdate ) =12  " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_12 = mysql_query($query_Cal_12, $connvbsa) or die(mysql_error());
$row_Cal_12 = mysql_fetch_assoc($Cal_12);
$totalRows_Cal_12 = mysql_num_rows($Cal_12);

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
 		<div class="page_title">Calendar of events - All Events for <?php echo date("Y") +1; ?></div>
  </div>
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div>   
</div>
    
<div class="row"> 
  		 <div class="text_box text-center">
         For More information, Entry Form etc, select "More". <br />
	    </div>
  </div>  
  
  <!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>  

<br>
<form name='cal_state'  method="post" action='cal_next_yr.php'>  
<table class="table">
	 <input type='hidden' name='CalYear'name='CalYear' value='<?php echo $cal_year; ?>'>
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

<!--Open January-->
  <div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">January</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_01['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['ranking_type']; ?></td>
      <td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_01['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_01['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_01['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_01['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_01['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_01['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_01['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_01 = mysql_fetch_assoc($Cal_01)); ?>
        </tbody>
	</table>
  </div>
  <!--Close January-->
  
<!--Open Feb-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">February</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_02['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_02['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_02['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_02['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_02['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_02['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_02['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_02['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_02['closedate'])) 
    	echo '<span class="italic">'. "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_02['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_02['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_02['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_02 = mysql_fetch_assoc($Cal_02)); ?>
        </tbody>
	</table>
</div>
  <!--Close Feb-->

<!--Open Mar-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">March</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_03['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_03['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_03['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_03['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_03['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_03['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_03['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_03['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_03['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_03['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_03['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_03['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_03 = mysql_fetch_assoc($Cal_03)); ?>
        </tbody>
	</table>
</div>
<!--Close Mar-->

<!--Open Apr-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">April</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_04['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_04['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_04['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_04['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_04['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_04['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_04['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_04['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_04['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_04['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_04['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_04['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_04 = mysql_fetch_assoc($Cal_04)); ?>
        </tbody>
	</table>
</div>
<!--Close April-->

<!--Open May-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">May</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_05['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_05['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_05['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_05['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_05['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_05['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_05['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_05['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_05['closedate'])) 
    	echo '<span class="italic">'. "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_05['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_05['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_05['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_05 = mysql_fetch_assoc($Cal_05)); ?>
        </tbody>
	</table>
</div>
<!--Close May-->


<!--Open June-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">June</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_06['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_06['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_06['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_06['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_06['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_06['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_06['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_06['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_06['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_06['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_06['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_06['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_06 = mysql_fetch_assoc($Cal_06)); ?>
        </tbody>
	</table>
</div>
<!--Close June-->

<!--Open July-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">July</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_07['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_07['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_07['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_07['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_07['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_07['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_07['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_07['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_07['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_07['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_07['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_07['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_07 = mysql_fetch_assoc($Cal_07)); ?>
        </tbody>
	</table>
</div>
<!--Close July-->

<!--Open Aug-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">August</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_08['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_08['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_08['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_08['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_08['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_08['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_08['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_08['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_08['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_08['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_08['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_08['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_08 = mysql_fetch_assoc($Cal_08)); ?>
        </tbody>
	</table>
</div>
<!--Close Aug-->

<!--Open Sept-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">September</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_09['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_09['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_09['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_09['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_09['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_09['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_09['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_09['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_09['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_09['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_09['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_09['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_09 = mysql_fetch_assoc($Cal_09)); ?>
        </tbody>
	</table>
</div>
<!--Close Sept-->

<!--Open Oct-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">October</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_10['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_10['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_10['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_10['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_10['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_10['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_10['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_10['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_10['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_10['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_10['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_10['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_10 = mysql_fetch_assoc($Cal_10)); ?>
        </tbody>
	</table>
</div>
<!--Close Oct-->

<!--Open Nov-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">November</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_11['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_11['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_11['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_11['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_11['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_11['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_11['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_11['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_11['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_11['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_11['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_11['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_11 = mysql_fetch_assoc($Cal_11)); ?>
        </tbody>
	</table>
</div>
<!--Close Nov-->

<!--Open Dec--> 
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">December</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_12['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_12['event']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_12['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_12['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_12['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_12['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_12['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_12['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_12['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_12['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_12['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_12['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_12 = mysql_fetch_assoc($Cal_12)); ?>
        </tbody>
	</table>
</div>
<!--Close Dec-->

</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php

?>
