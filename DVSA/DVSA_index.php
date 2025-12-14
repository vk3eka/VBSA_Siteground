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
$query_news = "SELECT item_title, news_content, current, list_order FROM DVSA WHERE current=1 AND `DVSA_type`='b_news' ORDER BY DVSA.list_order";
$news = mysql_query($query_news, $connvbsa) or die(mysql_error());
$row_news = mysql_fetch_assoc($news);
$totalRows_news = mysql_num_rows($news);

mysql_select_db($database_connvbsa, $connvbsa);
$query_maxdate = "SELECT CASE WHEN MAX( uploaded_on ) > MAX( edited_on ) THEN MAX( uploaded_on ) ELSE MAX( edited_on ) END AS MAXDATE FROM DVSA";
$maxdate = mysql_query($query_maxdate, $connvbsa) or die(mysql_error());
$row_maxdate = mysql_fetch_assoc($maxdate);
$totalRows_maxdate = mysql_num_rows($maxdate);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Info = "SELECT DVSA_id, DVSA_type, item_title, pdf_name, `current`, uploaded_on, DVSA.list_order FROM DVSA WHERE DVSA_type='a_info' AND current=1 ORDER BY list_order";
$Info = mysql_query($query_Info, $connvbsa) or die(mysql_error());
$row_Info = mysql_fetch_assoc($Info);
$totalRows_Info = mysql_num_rows($Info);

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone1 = "SELECT DVSA_id, DVSA_type, item_title, pdf_name, list_order FROM DVSA WHERE DVSA.DVSA_type='c_zone1' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone1 = mysql_query($query_zone1, $connvbsa) or die(mysql_error());
$row_zone1 = mysql_fetch_assoc($zone1);
$totalRows_zone1 = mysql_num_rows($zone1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone2 = "SELECT DVSA_id, DVSA_type, item_title, pdf_name, list_order FROM DVSA WHERE DVSA.DVSA_type='d_zone2' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone2 = mysql_query($query_zone2, $connvbsa) or die(mysql_error());
$row_zone2 = mysql_fetch_assoc($zone2);
$totalRows_zone2 = mysql_num_rows($zone2);

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone3 = "SELECT DVSA_id, DVSA_type, item_title, pdf_name, list_order FROM DVSA WHERE DVSA_type='e_zone3' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone3 = mysql_query($query_zone3, $connvbsa) or die(mysql_error());
$row_zone3 = mysql_fetch_assoc($zone3);
$totalRows_zone3 = mysql_num_rows($zone3);

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone4 = "SELECT DVSA_id, DVSA_type, item_title, pdf_name, list_order FROM DVSA WHERE DVSA_type='f_zone4' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone4 = mysql_query($query_zone4, $connvbsa) or die(mysql_error());
$row_zone4 = mysql_fetch_assoc($zone4);
$totalRows_zone4 = mysql_num_rows($zone4);

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone5 = "SELECT DVSA_id, DVSA_type,  item_title, pdf_name, list_order FROM DVSA WHERE DVSA_type='g_zone5' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone5 = mysql_query($query_zone5, $connvbsa) or die(mysql_error());
$row_zone5 = mysql_fetch_assoc($zone5);
$totalRows_zone5 = mysql_num_rows($zone5);

mysql_select_db($database_connvbsa, $connvbsa);
$query_hist = "SELECT DVSA_id, DVSA_type, item_title, pdf_name, `current`, uploaded_on, DVSA.list_order FROM DVSA WHERE DVSA_type='f_history' AND current=1 ORDER BY list_order, item_title DESC";
$hist = mysql_query($query_hist, $connvbsa) or die(mysql_error());
$row_hist = mysql_fetch_assoc($hist);
$totalRows_hist = mysql_num_rows($hist);

mysql_select_db($database_connvbsa, $connvbsa);
$query_contact = "SELECT * FROM DVSA_contact WHERE contact_current=1 ORDER BY contact_order";
$contact = mysql_query($query_contact, $connvbsa) or die(mysql_error());
$row_contact = mysql_fetch_assoc($contact);
$totalRows_contact = mysql_num_rows($contact);

mysql_select_db($database_connvbsa, $connvbsa);
$query_DVSA_about = "SELECT  item_title, news_content FROM DVSA WHERE DVSA_id =7";
$DVSA_about = mysql_query($query_DVSA_about, $connvbsa) or die(mysql_error());
$row_DVSA_about = mysql_fetch_assoc($DVSA_about);
$totalRows_DVSA_about = mysql_num_rows($DVSA_about);

mysql_select_db($database_connvbsa, $connvbsa);
$query_header = "SELECT item_title, news_content, DVSA.pagezone_header_desc, DVSA.pagezone_header FROM DVSA WHERE DVSA_id=8";
$header = mysql_query($query_header, $connvbsa) or die(mysql_error());
$row_header = mysql_fetch_assoc($header);
$totalRows_header = mysql_num_rows($header);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DVSA Home Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="affiliates">

   <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

<!-- Affiliate header from appropriate database table -->
<!-- Large Screen header -->
<div class="row visible-xs">
  <h1 style="text-align: center; padding-bottom: 10px"><?php echo $row_header['pagezone_header']; ?></h1> 
  <h6 style="text-align: center; padding-bottom: 10px"><?php echo $row_header['pagezone_header_desc']; ?></h6>   
  <h5 style="color:#900; text-align:center; padding-bottom:10px" class="italic"><?php echo $row_header['news_content']; ?></h5> 
</div>
  
<!-- Small Screen header -->
<div class="row hidden-xs">
  <h1><?php echo $row_header['item_title']; ?></h1>   
  <h4 style="color:#666; text-align:center; padding-bottom:10px" class="italic"><?php echo $row_header['news_content']; ?></h4> 
</div> 
<!-- End header -->
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container-->

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 
   
   
<!--Left News--> 
  <div class="pull-left" style="width:30%; padding:1% ;font-size:12px">
  
  <div style="width:95%; padding-bottom:10px"><a href="../index.php"><img src="../DVSA/DVSA_logo.jpg" class="img-responsive center-block" width="100%" /></a></div> 
  
	<div class="italic">
          <p class="text-center">Last update: <?php $newDate = date("M d, g:ia", strtotime($row_maxdate['MAXDATE'])); echo $newDate; ?></p>
          
          
          <div style="margin-bottom:10px"> 
  			<a href="../Club_dir/clubz_DVSA.php" class="btn btn-primary btn-responsive center-block" role="button">DVSA Clubs</a>
	  </div>
      <?php // Display link to Contact / About page depending on if content exists
	if(!empty($row_contact['contact_id']) && !empty($row_DVSA_about['item_title'])) 
	echo '<p class="italic text-center"><a href="DVSA_contact.php">'. "Contact, About DVSA". '</a></p>'; 
	elseif(empty($row_contact['contact_id']) && !empty($row_DVSA_about['item_title']))
	echo '<p class="italic text-center"><a href="DVSA_contact.php">'. "About DVSA". '</a></p>';  
	elseif(!empty($row_contact['contact_id']) && empty($row_DVSA_about['item_title']))
	echo '<p class="italic text-center"><a href="DVSA_contact.php">'. "Contact DVSA". '</a></p>'; 
	else echo "";
	?>
      
      
      
      
      <p class="right_title">News</p>
      <?php do { ?> 
        <div class="affiliate_item">
		
          <div class="right_title"><?php echo $row_news['item_title']; ?></div>
          <div class="right_content"><?php echo $row_news['news_content']; ?></div>
        </div>
        <?php } while ($row_news = mysql_fetch_assoc($news)); ?>
  		</div>
        
</div>
<!--End left--> 

<!--Right-->

<!--Container for affiliate content - Information--> 
<div ID="affiliate_container"> 
		
	<div class="table-responsive center-block" style="max-width:600px">
  	<table class="table">
  		<tr>
  		  <td colspan="3">Email scores to the Score Registrar at <a href="mailto:dvsascores@gmail.com">dvsascores@gmail.com</a></td>
	    </tr>
  		<tr>
  		  <td colspan="3">
          <div class="text-center">
          <a href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
          <span class="italic"> = View &nbsp;&nbsp;&nbsp;</span>
          <a href="#"><span class="glyphicon glyphicon-download"></span></a>
          <span class="italic"> = Download</span>
          </div>
          </td>
	    </tr>
  		<tr>
    		<th colspan="3">Information</th>
    	</tr>
		<?php do { // information section ?>
  		<tr>
      		<td nowrap="nowrap"><?php echo $row_Info['item_title']; ?></td>
      		<td class="text-center"><a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../DVSA/DVSA_upload/<?php echo $row_Info['pdf_name']; ?>" title="View"><span class="glyphicon glyphicon-eye-open"></span></a></td>
      		<td class="text-center"><a href="../DVSA/DVSA_upload/<?php echo $row_Info['pdf_name']; ?>" target="_blank"  title="Download"><span class="glyphicon glyphicon-download"></span></a></td>
  		</tr>
		<?php } while ($row_Info = mysql_fetch_assoc($Info)); ?>

        <!-- Zone 1 -->
          	<?php  // Show Zone 1 header if it is not null
		  	$query  = "SELECT pagezone_header AS header1 FROM DVSA WHERE DVSA_id=2"; $result = mysql_query($query);
			while($row = mysql_fetch_row($result)) $header1 = $row[0];
			
			if(isset($header1)) // Show blank row and the header
			echo '<tr>'.'<td colspan="3">'.'&nbsp;'.'</td>'.'</tr>'.'<tr>'.'<th colspan="3">'.$header1,'</th>'.'</tr>'; else echo ""; ?>

        	<?php  // Show zone 1 items if they exist, else "Not yet available"
			if(isset($row_zone1['DVSA_id'])) do { ?>
            
  			<tr>
       		<td nowrap="nowrap"><?php echo $row_zone1['item_title']; ?></td>
      		<td class="text-center">
			<?php if(isset($row_zone1['pdf_name'])) { ?>
            <a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../DVSA/DVSA_upload/<?php echo $row_zone1['pdf_name']; ?>" title="View"><span class="glyphicon glyphicon-eye-open"></span></a>
            <?php } else echo "";?>
            </td>
            <td class="text-center">
			<?php if(isset($row_zone1['pdf_name'])) { ?>
            <a href="../DVSA/DVSA_upload/<?php echo $row_zone1['pdf_name']; ?>" target="_blank" title="Download"><span class="glyphicon glyphicon-download"></a>
            <?php } else echo "";?>
            </td>
  			</tr>
			<?php } while ($row_zone1 = mysql_fetch_assoc($zone1));  
			elseif(isset($header1)) echo '<tr>'.'<td colspan="3" class="italic">'."Not available".'</td>'.'</tr>';
			else echo"";
			
			?>
         <!-- End Zone 1 -->
         
         <!-- Zone 2 -->
          	<?php  // Show Zone 2 header if it is not null
		  	$query  = "SELECT pagezone_header AS header2 FROM DVSA WHERE DVSA_id=3"; $result = mysql_query($query);
			while($row = mysql_fetch_row($result)) $header2 = $row[0];
			
			if(isset($header2)) // Show blank row and the header
			echo '<tr>'.'<td colspan="3">'.'&nbsp;'.'</td>'.'</tr>'.'<tr>'.'<th colspan="3">'.$header2,'</th>'.'</tr>'; else echo ""; ?>

        	<?php  // Show zone 2 items if they exist, else "Not yet available"
			if(isset($row_zone2['DVSA_id'])) do { ?>
            
  			<tr>
       			<td nowrap="nowrap"><?php echo $row_zone2['item_title']; ?></td>
      			<td class="text-center">
				<?php if(isset($row_zone2['pdf_name'])) { ?>
                <a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../DVSA/DVSA_upload/<?php echo $row_zone2['pdf_name']; ?>" title="View"><span class="glyphicon glyphicon-eye-open"></span></a>
                <?php } else echo "";?>
                </td>
                <td class="text-center">
				<?php if(isset($row_zone2['pdf_name'])) { ?>
                <a href="../DVSA/DVSA_upload/<?php echo $row_zone2['pdf_name']; ?>" target="_blank" title="Download"><span class="glyphicon glyphicon-download"></a>
                <?php } else echo "";?>
                </td>
  			</tr>
			<?php } while ($row_zone2 = mysql_fetch_assoc($zone2));  
			elseif(isset($header2)) echo '<tr>'.'<td colspan="3" class="italic">'."Not available".'</td>'.'</tr>';
			else echo"";
			
			?>
         <!-- End Zone 2 -->
         
         <!-- Zone 3 -->
          	<?php  // Show Zone 3 header if it is not null
		  	$query  = "SELECT pagezone_header AS header3 FROM DVSA WHERE DVSA_id=4"; $result = mysql_query($query);
			while($row = mysql_fetch_row($result)) $header3 = $row[0];
			
			if(isset($header3)) // Show blank row and the header
			echo '<tr>'.'<td colspan="3">'.'&nbsp;'.'</td>'.'</tr>'.'<tr>'.'<th colspan="3">'.$header3,'</th>'.'</tr>'; else echo ""; ?>

        	<?php  // Show zone 3 items if they exist, if they do not exist and the header is not set show nothing, else "Not yet available"
			if(isset($row_zone3['DVSA_id'])) do { ?>
            
  			<tr>
       			<td nowrap="nowrap"><?php echo $row_zone3['item_title']; ?></td>
      			<td class="text-center">
				<?php if(isset($row_zone3['pdf_name'])) { ?>
                <a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../DVSA/DVSA_upload/<?php echo $row_zone3['pdf_name']; ?>" title="View"><span class="glyphicon glyphicon-eye-open"></span></a>
                <?php } else echo "";?>
                </td>
                <td class="text-center">
				<?php if(isset($row_zone3['pdf_name'])) { ?>
                <a href="../DVSA/DVSA_upload/<?php echo $row_zone3['pdf_name']; ?>" target="_blank" title="Download"><span class="glyphicon glyphicon-download"></a>
                <?php } else echo "";?>
                </td>
  			</tr>
			<?php } while ($row_zone3 = mysql_fetch_assoc($zone3));  
			elseif(isset($header3)) echo '<tr>'.'<td colspan="3" class="italic">'."Not available".'</td>'.'</tr>';
			else echo"";
			
			?> 
         <!-- End Zone 3 -->
         
         <!-- Zone 4 -->
          	<?php  // Show Zone 4 header if it is not null
		  	$query  = "SELECT pagezone_header AS header4 FROM DVSA WHERE DVSA_id=5"; $result = mysql_query($query);
			while($row = mysql_fetch_row($result)) $header4 = $row[0];
			
			if(isset($header4)) // Show blank row and the header
			echo '<tr>'.'<td colspan="3">'.'&nbsp;'.'</td>'.'</tr>'.'<tr>'.'<th colspan="3">'.$header4,'</th>'.'</tr>'; else echo ""; ?>

        	<?php  // Show zone 4 items if they exist, if they do not exist and the header is not set show nothing, else "Not yet available"
			if(isset($row_zone4['DVSA_id'])) do { ?>
            
  			<tr>
       			<td nowrap="nowrap"><?php echo $row_zone4['item_title']; ?></td>
      			<td class="text-center">
				<?php if(isset($row_zone4['pdf_name'])) { ?>
                <a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../DVSA/DVSA_upload/<?php echo $row_zone4['pdf_name']; ?>" title="View"><span class="glyphicon glyphicon-eye-open"></span></a>
                <?php } else echo "";?>
                </td>
                <td class="text-center">
				<?php if(isset($row_zone4['pdf_name'])) { ?>
                <a href="../DVSA/DVSA_upload/<?php echo $row_zone4['pdf_name']; ?>" target="_blank" title="Download"><span class="glyphicon glyphicon-download"></a>
                <?php } else echo "";?>
                </td>
  			</tr>
			<?php } while ($row_zone4 = mysql_fetch_assoc($zone4));  
			elseif(isset($header4)) echo '<tr>'.'<td colspan="3" class="italic">'."Not available".'</td>'.'</tr>';
			else echo"";
			
			?> 
         <!-- End Zone 4 -->
         
         <!-- Zone 5 -->
          	<?php  // Show Zone 5 header if it is not null
		  	$query  = "SELECT pagezone_header AS header5 FROM DVSA WHERE DVSA_id=6"; $result = mysql_query($query);
			while($row = mysql_fetch_row($result)) $header5 = $row[0];
			
			if(isset($header5)) // Show blank row and the header
			echo '<tr>'.'<td colspan="3">'.'&nbsp;'.'</td>'.'</tr>'.'<tr>'.'<th colspan="3">'.$header5,'</th>'.'</tr>'; else echo ""; ?>

        	<?php  // Show zone 5 items if they exist, if they do not exist and the header is not set show nothing, else "Not yet available"
			if(isset($row_zone5['DVSA_id'])) do { ?>
            
  			<tr>
       			<td nowrap="nowrap"><?php echo $row_zone5['item_title']; ?></td>
      			<td class="text-center">
				<?php if(isset($row_zone5['pdf_name'])) { ?>
                <a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../DVSA/DVSA_upload/<?php echo $row_zone5['pdf_name']; ?>" title="View"><span class="glyphicon glyphicon-eye-open"></span></a>
                <?php } else echo "";?>
                </td>
                <td class="text-center">
				<?php if(isset($row_zone5['pdf_name'])) { ?>
                <a href="../DVSA/DVSA_upload/<?php echo $row_zone5['pdf_name']; ?>" target="_blank" title="Download"><span class="glyphicon glyphicon-download"></a>
                <?php } else echo "";?>
                </td>
  			</tr>
			<?php } while ($row_zone5 = mysql_fetch_assoc($zone5));  
			elseif(isset($header5)) echo '<tr>'.'<td colspan="3" class="italic">'."Not available".'</td>'.'</tr>';
			else echo"";
			
			?> 
         <!-- End Zone 5 -->
         
         <!-- History -->
         <tr>
            <th colspan="3">&nbsp;</th>
         </tr>
         <tr>
            <th colspan="3">History</th>
         </tr>

        <?php  do { ?>    
  		<tr>
       		<td nowrap="nowrap"><?php echo $row_hist['item_title']; ?></td>
      		<td class="text-center">
				<?php if(isset($row_hist['pdf_name'])) { ?>
                <a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../DVSA/DVSA_upload/<?php echo $row_hist['pdf_name']; ?>" title="View"><span class="glyphicon glyphicon-eye-open"></span></a>
                <?php } else echo "";?>
            </td>
            <td class="text-center">
				<?php if(isset($row_hist['pdf_name'])) { ?>
                <a href="../DVSA/DVSA_upload/<?php echo $row_hist['pdf_name']; ?>" target="_blank" title="Download"><span class="glyphicon glyphicon-download"></a>
                <?php } else echo "";?>
            </td>
  		</tr>
		<?php } while ($row_hist = mysql_fetch_assoc($hist)); ?> 
         <!-- End History -->
	</table>
	</div>
</div><!--Close Information--> 



  

</div>  <!-- close containing wrapper --> 
</body>
</html>
<?php

?>
