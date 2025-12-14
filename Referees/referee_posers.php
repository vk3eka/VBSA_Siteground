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
$query_page_items = "SELECT webpage_items.ID, webpage_items.Header, webpage_items.`Comment`, webpage_items.`By`, webpage_items.created_on, webpage_items.blocked, webpage_items.img_orientation, webpage_items.item_image,  webpage_items.event_id,  webpage_items.OrderRefPoser, webpage_items.img_size FROM webpage_items WHERE webpage_items.blocked='No' AND webpage_items.page_refposer='Y' ORDER BY OrderRefPoser, created_on DESC";
$page_items = mysql_query($query_page_items, $connvbsa) or die(mysql_error());
$row_page_items = mysql_fetch_assoc($page_items);
$totalRows_page_items = mysql_num_rows($page_items);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal = "SELECT event, date_format(closedate,'%b %e, %Y') AS ClsDate,  date_format(startdate,'%b %e, %Y') AS StartDate, event_id, startdate, closedate, about_short FROM calendar WHERE calendar.visible='Yes' AND calendar.startdate is not null AND calendar.startdate >= NOW() ORDER BY calendar.startdate LIMIT 8";
$Cal = mysql_query($query_Cal, $connvbsa) or die(mysql_error());
$row_Cal = mysql_fetch_assoc($Cal);
$totalRows_Cal = mysql_num_rows($Cal);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Referees</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="info">

   <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container-->

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px-->

    <!-- Include reventon advertising -->

<!--Content--> 
  
  <!--Right--> 
  
  <div class="pull-left" style="width:25%; margin-left:15px"> 
  
<div class="italic">
  	  <p class="text-center">&nbsp;</p>
      <p class="text-center right_title">Referee Q &amp; A</p>
      <p class="text-center">Need Clarification or advice?</p>
      <p class="text-center">Contact: Larry Eforgan</p>
      <p class="text-center">Victorian Director of Referees</p>
      <p class="text-center">Ring: <a href="tel:0432798777">0432 798 777</a>      </p>
      <p class="text-center">&nbsp;</p>
  		</div>

        
        
    <!-- Include buttons - make a payment, find a club and calendar -->
<?php include '../includes/buttons_left.php';?> 
     
        
        <div class="right_title center-block" style="width:75%">Coming events</div>
        <div class="center-block" style="width:75%">
            <?php do { ?>
        <div class="right_header"><?php echo $row_Cal['event']; ?></div>
        
        <div class="right_content">
          Starts: <?php if ($row_Cal['startdate'] != ''): ?>
          <?php $newDate = date("M d, Y", strtotime($row_Cal['startdate'])); echo $newDate; ?>
          <?php endif; ?>
          </div>
        <div class="right_content pull-right text-nowrap">
          <a href="../calendar/cal_index_detail.php?event_id=<?php echo $row_Cal['event_id']; ?>"class="btn-xs btn-primary btn-responsive" role="button">Read More</a>
          </div>
          
          <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div>
		<?php } while ($row_Cal = mysql_fetch_assoc($Cal)); ?>
        </div>
  </div>
  <!--End Right--> 
  
  <!--Left--> 
  <div ID="News_container">
    
    <div class="Page_heading_container">
 		<div class="index_title"><span class="h4">Referee Q &amp; A </span></div>
   
 	  
   <div class="search_container">
   		<form id="form1" name="form1" method="get" action="../index_srch_res.php">
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input type="text" name="page_content" id="page_content" placeholder="Search items .." />
        </form> 
   </div> 
   </div>
    <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div>   
  
  
  <?php do { ?><!--Open News Item Repeat-->    
		<!--Container for news item-->
    <div class="News_item">
    	<div class="News_item_header">
			<div class="news_title"><?php echo $row_page_items['Header']; ?></div>
        </div>
        <div class="News_item_content">


        
        <?php
		if(empty ($row_page_items['item_image']))
				{
				echo " ";
				}
				elseif(isset ($row_page_items['item_image'])) { ?>
          
          		
                <img class="img-responsive pull-right" style="padding-left:10px; padding-bottom:10px" src="http://vbsa.org.au/../images_frontpage/<?php echo $row_page_items['item_image']; ?>" width="<?php echo $row_page_items['img_size']; ?>" />
               
                
          
<?php } ?>

        
        <?php echo $row_page_items['Comment']; ?>
        </div>
<!--By -->         
        <div class="news_by">By: <?php echo $row_page_items['By']; ?>&nbsp;&nbsp;<?php $newDate = date("D jS M Y \- g:iA", strtotime($row_page_items['created_on'])) ; echo $newDate; ?>
        </div>
        
<!--Begin "information" footer - contains links to attachments, email, url --> 
<div class="news_item_footer_links" >     
<?php        
// display "Information" and list the web page attachments (from table webpage_attach) if they exist 

$query_att = "SELECT up_id, up_desc, up_on, up_pdf_name, up_event, item_id, up_type FROM webpage_attach, webpage_items WHERE item_id=ID AND ID= ".$row_page_items['ID'].""; 
$result_att = mysql_query($query_att) or die(mysql_error());		

		if(mysql_num_rows(mysql_query($query_att)) >0 ) {
		echo '<table class="table" style="max-width:600px">';
		echo '<tr>'.'<td colspan="3" class="italic">'."Information".'</td>'.'</tr>'; 
		
		{
		
			while($row_att= mysql_fetch_array($result_att)):
				   
			// find file extension (Front Page attachments)
			$path_info_att = pathinfo($row_att['up_pdf_name']); 
			 
			   	if ($row_att['up_type'] == 'Attachment'):
								
					echo '<tr>';
					
					// if extension is a .pdf file display pdf after the attachment title and the pdf viewer link. Else display the attachment title only and a blank td
					if($path_info_att['extension']=="pdf")
					{
					echo '<td nowrap="nowrap" style="width:60%">'.$row_att['up_desc']." (pdf)".'</td>';
					// pdf viewer link
					echo '<td style="width:20%">'."<a href=http://www.vbsa.org.au/../ViewerJS/?zoom=page-width#..//Front_page_upload/".$row_att['up_pdf_name']." title=View >".'<span class="glyphicon glyphicon-eye-open">'."</a>",'</td>';
					}
					else echo '<td nowrap="nowrap" style="width:60%">'.$row_att['up_desc'].'</td>'.'<td style="width:20%">&nbsp;</td>' ;
					
					// download link
					echo '<td style="width:20%">'."<a href=../Front_page_upload/".$row_att['up_pdf_name']." target=_blank title=Download>".'<span class="glyphicon glyphicon-download">'."</a>",'</td>';
					echo '</tr>';
				endif;
								
				if ($row_att['up_type'] == 'URL'):
								
					echo '<tr>';
					echo '<td nowrap="nowrap" colspan="2">'.$row_att['up_desc'].'</td>';
					echo '<td nowrap="nowrap">'."<a href=".$row_att['up_pdf_name']." target=_blank>".'Visit this page'."</a>".'</td>';
					echo '</tr>';
				endif;
								
				if ($row_att['up_type'] == 'Email'):
								
					echo '<tr>';
					echo '<td colspan="3" nowrap="nowrap">'."<a href=mailto:".$row_att['up_pdf_name']." target=_blank>".$row_att['up_desc']."</a>". " (Email)".'</td>';
					echo '</tr>';
					endif;
			endwhile;
							
		} 
		echo '</table>'; }
		else echo "";

// display "Information" and list the calendar attachments if they exist 

$query_event = "SELECT attach_name, Attachment, type FROM calendar_attach, webpage_items WHERE event_number=event_id AND webpage_items.ID = ".$row_page_items['ID'].""; 
$result_event = mysql_query($query_event) or die(mysql_error());		

		if(mysql_num_rows(mysql_query($query_event)) >0 ) {
		echo '<table class="table" style="max-width:600px">';
		echo '<tr>'.'<td colspan="3" class="italic">'."Information".'</td>'.'</tr>'; 
		
		{
		
			while($row_event = mysql_fetch_array($result_event)):	   
			 
			   	if ($row_event['type'] == 'Uploaded Attachment'):
				
					// find file extension (Calendar attachments)
					$path_info_cal = pathinfo($row_event['Attachment']); 
								
					echo '<tr>';
					
					// if extension is a .pdf file display pdf after the attachment title and the pdf viewer link. Else display the attachment title only and a blank td
					if($path_info_cal['extension']=="pdf")
					{
					echo '<td nowrap="nowrap" style="width:60%">'.$row_event['attach_name']." (pdf)".'</td>';
					// pdf viewer link
					echo '<td style="width:20%">'."<a href=http://www.vbsa.org.au//ViewerJS/?zoom=page-width#..//calendar/cal_upload/".$row_event['Attachment']." title=View >".'<span class="glyphicon glyphicon-eye-open">'."</a>",'</td>';
					}
					else echo '<td nowrap="nowrap" style="width:60%">'.$row_event['attach_name'].'</td>'.'<td style="width:20%">&nbsp;</td>' ;
					
					// download link
					echo '<td style="width:20%">'."<a href=../calendar/cal_upload/".$row_event['Attachment']." target=_blank>".'<span class="glyphicon glyphicon-download">'."</a>",'</td>';
					echo '</tr>';
				endif;
								
				if ($row_event['type'] == 'URL'):
								
					echo '<tr>';
					echo '<td nowrap="nowrap" colspan="2">'.$row_event['attach_name'].'</td>';
					echo '<td nowrap="nowrap">'."<a href=".$row_event['Attachment']." >".'  Visit this page'."</a>".'</td>';
					echo '</tr>';	
				endif;
								
				if ($row_event['type'] == 'Email'):
								
					echo '<tr>';
					echo '<td colspan="3" nowrap="nowrap">'."<a href=mailto:".$row_event['Attachment']." target=_blank>".$row_event['attach_name']."</a>". " (Email)".'</td>';
					echo '</tr>';
					endif;
			endwhile;
							
		} 
		echo '</table>'; }
		else echo "";

 ?>

 </div><!--Close footer links container--> <!--Close footer links container--> 

</div><!--Close news item--> 

<div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div>

<?php } while ($row_page_items = mysql_fetch_assoc($page_items)); ?>
  </div>
 <!--Close News Item repeat -->
<!--Close Container for news item-->



    
    
<!--Open Right sidebar-->
   
  <!--Close Right sidebar-->
  

</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php
mysql_free_result($page_items);

mysql_free_result($Cal);
?>
