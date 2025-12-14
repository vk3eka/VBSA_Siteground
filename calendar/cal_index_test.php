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
$query_Cal_01 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name, calendar.pp_butt FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =1 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_01 = mysql_query($query_Cal_01, $connvbsa) or die(mysql_error());
$row_Cal_01 = mysql_fetch_assoc($Cal_01);
$totalRows_Cal_01 = mysql_num_rows($Cal_01);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_02 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =2 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_02 = mysql_query($query_Cal_02, $connvbsa) or die(mysql_error());
$row_Cal_02 = mysql_fetch_assoc($Cal_02);
$totalRows_Cal_02 = mysql_num_rows($Cal_02);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_03 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =3 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_03 = mysql_query($query_Cal_03, $connvbsa) or die(mysql_error());
$row_Cal_03 = mysql_fetch_assoc($Cal_03);
$totalRows_Cal_03 = mysql_num_rows($Cal_03);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_04 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =4 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_04 = mysql_query($query_Cal_04, $connvbsa) or die(mysql_error());
$row_Cal_04 = mysql_fetch_assoc($Cal_04);
$totalRows_Cal_04 = mysql_num_rows($Cal_04);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_05 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =5 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_05 = mysql_query($query_Cal_05, $connvbsa) or die(mysql_error());
$row_Cal_05 = mysql_fetch_assoc($Cal_05);
$totalRows_Cal_05 = mysql_num_rows($Cal_05);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_06 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =6 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_06 = mysql_query($query_Cal_06, $connvbsa) or die(mysql_error());
$row_Cal_06 = mysql_fetch_assoc($Cal_06);
$totalRows_Cal_06 = mysql_num_rows($Cal_06);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_07 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =7 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_07 = mysql_query($query_Cal_07, $connvbsa) or die(mysql_error());
$row_Cal_07 = mysql_fetch_assoc($Cal_07);
$totalRows_Cal_07 = mysql_num_rows($Cal_07);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_08 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =8 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_08 = mysql_query($query_Cal_08, $connvbsa) or die(mysql_error());
$row_Cal_08 = mysql_fetch_assoc($Cal_08);
$totalRows_Cal_08 = mysql_num_rows($Cal_08);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_09 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =9 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_09 = mysql_query($query_Cal_09, $connvbsa) or die(mysql_error());
$row_Cal_09 = mysql_fetch_assoc($Cal_09);
$totalRows_Cal_09 = mysql_num_rows($Cal_09);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_10 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =10 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_10 = mysql_query($query_Cal_10, $connvbsa) or die(mysql_error());
$row_Cal_10 = mysql_fetch_assoc($Cal_10);
$totalRows_Cal_10 = mysql_num_rows($Cal_10);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_11 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =11 GROUP BY calendar.event_id ORDER BY calendar.startdate";
$Cal_11 = mysql_query($query_Cal_11, $connvbsa) or die(mysql_error());
$row_Cal_11 = mysql_fetch_assoc($Cal_11);
$totalRows_Cal_11 = mysql_num_rows($Cal_11);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_12 = "SELECT calendar.event_id, calendar.event, calendar.venue, calendar.state, calendar.startdate, calendar.finishdate, calendar.closedate,  calendar.visible, calendar_attach.event_number, calendar_attach.attach_name FROM calendar LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id WHERE calendar.visible = 'Yes' AND YEAR( startdate ) = YEAR( CURDATE( ) ) AND MONTH( startdate ) =12 GROUP BY calendar.event_id ORDER BY calendar.startdate";
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
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

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
        <div class="text_box text-center">
        <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" >
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_viewcart_SM.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
</form>
		</div>

        
  </div>  
  
  

<!--Open January-->
  <div class="table-responsive center-block" style="max-width:800px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="8" nowrap="nowrap" style="background-color: #CCC">January</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_01['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['event']; ?></td>
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
    	<td><?php echo $row_Cal_01['pp_butt']; ?></td>
  		</tr>
  		<?php } while ($row_Cal_01 = mysql_fetch_assoc($Cal_01)); ?>
        </tbody>
	</table>
  </div>
  <!--Close January-->
  
<!--Open Feb-->
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">February</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_02['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_02['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">March</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_03['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_03['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">April</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_04['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_04['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">May</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_05['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_05['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">June</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_06['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_06['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">July</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_07['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_07['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">August</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_08['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_08['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">September</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_09['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_09['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">October</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_10['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_10['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">November</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_11['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_11['event']; ?></td>
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
<div class="table-responsive center-block" style="max-width:700px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<th colspan="7" nowrap="nowrap" style="background-color: #CCC">December</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_12['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_12['event']; ?></td>
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
