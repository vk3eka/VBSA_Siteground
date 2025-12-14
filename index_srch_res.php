<?php require_once('Connections/connvbsa.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal = "SELECT event, date_format(closedate,'%b %e, %Y') AS ClsDate,  date_format(startdate,'%b %e, %Y') AS StartDate, event_id, startdate, closedate, about_short FROM calendar WHERE calendar.visible='Yes' AND calendar.startdate is not null AND calendar.startdate >= NOW() ORDER BY calendar.startdate LIMIT 8";
$Cal = mysql_query($query_Cal, $connvbsa) or die(mysql_error());
$row_Cal = mysql_fetch_assoc($Cal);
$totalRows_Cal = mysql_num_rows($Cal);

$colname_page_items = "-1";
if (isset($_GET['page_content'])) {
  $colname_page_items = $_GET['page_content'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_page_items = sprintf("SELECT date_format(created_on,'%%b %%e, %%Y, %%r') AS PostOn, date_format(created_on,'%%Y') AS year, date_format(created_on,'%%m') AS month, date_format(created_on,'%%e') AS day,  date_format(created_on,'%%H %%i') AS time,  webpage_items.ID, webpage_items.Header, webpage_items.`Comment`, webpage_items.`By`, webpage_items.created_on, webpage_items.blocked, webpage_items.`OrderFP`, webpage_items.img_orientation, webpage_items.item_image, webpage_items.img_size  FROM webpage_items WHERE webpage_items.blocked='No' AND webpage_items.Comment LIKE %s ORDER BY `OrderFP` ASC, year DESC, month DESC, day DESC, time DESC", GetSQLValueString("%" . $colname_page_items . "%", "text"));
$page_items = mysql_query($query_page_items, $connvbsa) or die(mysql_error());
$row_page_items = mysql_fetch_assoc($page_items);
$totalRows_page_items = mysql_num_rows($page_items);

$queryString_page_items = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_page_items") == false && 
        stristr($param, "totalRows_page_items") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_page_items = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_page_items = sprintf("&totalRows_page_items=%d%s", $totalRows_page_items, $queryString_page_items);

;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Search results</title>
  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="CSS/VBSA_base.css" rel="stylesheet" type="text/css" />

</head>
<body id="home">

   <!-- Include Google Tracking -->
<?php include_once("includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include 'includes/header.php';?>
    
    <!-- Include navigation -->
<?php include 'includes/nav_vbsa.php';?>

</div><!--End Bootstrap Container-->

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px-->

    <!-- Include reventon advertising -->
<?php include 'includes/reventon_image_swap.php';?>  

<!--Content--> 
  
  <!--Right--> 
  
  <div class="pull-left" style="width:25%; margin-left:15px"> 
  

  			 

        
         <!-- Include buttons - make a payment, find a club and calendar -->
<?php include 'includes/buttons_left.php';?> 
     
        
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
          <a href="calendar/cal_index_detail.php?event_id=<?php echo $row_Cal['event_id']; ?>"class="btn-xs btn-primary btn-responsive" role="button">Read More</a>
          </div>
          
          <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div>
		<?php } while ($row_Cal = mysql_fetch_assoc($Cal)); ?>
        </div>
  </div>
  <!--End Right--> 
  
  <!--Left--> 
  <div ID="News_container">
    
    <div class="Page_heading_container">
 		<div class="index_title"><span class="h4">There were <?php echo $totalRows_page_items ?> results for &quot;<?php echo $_GET['page_content'] ?>&quot;</span></div>
   
 	  
   <div class="search_container">
   		<form id="form1" name="form1" method="get" action="index_srch_res.php">
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

$query_FP = "SELECT up_id, up_desc, up_on, up_pdf_name, up_event, item_id, up_type FROM webpage_attach, webpage_items WHERE item_id=ID AND ID= ".$row_page_items['ID'].""; 
$result_FP = mysql_query($query_FP) or die(mysql_error());		

		if(mysql_num_rows(mysql_query($query_FP)) >0 ) {
		echo '<table class="table" style="max-width:600px">';
		echo '<tr>'.'<td colspan="3" class="italic">'."Information".'</td>'.'</tr>'; 
		
		{
		
			while($row_FP= mysql_fetch_array($result_FP)):	   
			 
			   	if ($row_FP['up_type'] == 'Attachment'):
								
					echo '<tr>';
					echo '<td nowrap="nowrap" style="width:60%">'.$row_FP['up_desc']." (pdf)".'</td>';
					echo '<td style="width:20%">'."<a href=http://www.vbsa.org.au/../ViewerJS/?zoom=page-width#..//Front_page_upload/".$row_FP['up_pdf_name']." title=View >".'<span class="glyphicon glyphicon-eye-open">'."</a>",'</td>';
					echo '<td style="width:20%">'."<a href=../Front_page_upload/".$row_FP['up_pdf_name']." target=_blank title=Download>".'<span class="glyphicon glyphicon-download">'."</a>",'</td>';
					echo '</tr>';
				endif;
								
				if ($row_FP['up_type'] == 'URL'):
								
					echo '<tr>';
					echo '<td nowrap="nowrap" colspan="2">'.$row_FP['up_desc'].'</td>';
					echo '<td nowrap="nowrap">'."<a href=".$row_FP['up_pdf_name']." target=_blank>".'Visit this page'."</a>".'</td>';
					echo '</tr>';
				endif;
								
				if ($row_FP['up_type'] == 'Email'):
								
					echo '<tr>';
					echo '<td colspan="3" nowrap="nowrap">'."<a href=mailto:".$row_FP['up_pdf_name']." target=_blank>".$row_FP['up_desc']."</a>". " (Email)".'</td>';
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
								
					echo '<tr>';
					echo '<td nowrap="nowrap" style="width:60%">'.$row_event['attach_name']." (pdf)".'</td>';
					echo '<td style="width:20%">'."<a href=http://www.vbsa.org.au//ViewerJS/?zoom=page-width#..//ComingEvents/cal_upload/".$row_event['Attachment']." >".'<span class="glyphicon glyphicon-eye-open">'."</a>",'</td>';
					echo '<td style="width:20%">'."<a href=../ComingEvents/cal_upload/".$row_event['Attachment']." target=_blank>".'<span class="glyphicon glyphicon-download">'."</a>",'</td>';
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
<table border="0" style="font-size:12px">
  <tr>
    <td width="20">&nbsp;</td>
    <td colspan="7" align="center" nowrap="nowrap" style="color:#666">Records <?php echo ($startRow_page_items + 1) ?> to <?php echo min($startRow_page_items + $maxRows_page_items, $totalRows_page_items) ?> of <?php echo $totalRows_page_items ?></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><?php if ($pageNum_page_items > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_page_items=%d%s", $currentPage, 0, $queryString_page_items); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td width="15" align="center">&nbsp;</td>
    <td align="center"><?php if ($pageNum_page_items > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_page_items=%d%s", $currentPage, max(0, $pageNum_page_items - 1), $queryString_page_items); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td width="15" align="center">&nbsp;</td>
    <td align="center"><?php if ($pageNum_page_items < $totalPages_page_items) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_page_items=%d%s", $currentPage, min($totalPages_page_items, $pageNum_page_items + 1), $queryString_page_items); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td width="15" align="center">&nbsp;</td>
    <td align="center"><?php if ($pageNum_page_items < $totalPages_page_items) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_page_items=%d%s", $currentPage, $totalPages_page_items, $queryString_page_items); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
 </div>
 <!--Close News Item repeat -->
<!--Close Container for news item-->



    
    
<!--Open Right sidebar-->
   
  <!--Close Right sidebar-->
  

</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php
mysql_free_result($Cal);

mysql_free_result($page_items);
?>
