<?php require_once('Connections/connvbsa.php'); 
mysql_select_db($database_connvbsa, $connvbsa);
?>
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

$maxRows_page_items = 10;
$pageNum_page_items = 0;
if (isset($_GET['pageNum_page_items'])) {
  $pageNum_page_items = $_GET['pageNum_page_items'];
}
$startRow_page_items = $pageNum_page_items * $maxRows_page_items;

//mysql_select_db($database_connvbsa, $connvbsa);
$query_page_items = "SELECT webpage_items.ID, webpage_items.Header, webpage_items.`Comment`, webpage_items.`By`, webpage_items.created_on, webpage_items.blocked, webpage_items.img_orientation, webpage_items.item_image,  webpage_items.event_id,  webpage_items.page_help, webpage_items.OrderFP, webpage_items.OrderRef, webpage_items.OrderWomens, webpage_items.OrderHelp, webpage_items.OrderWomens, webpage_items.img_size FROM webpage_items WHERE webpage_items.blocked='No' AND webpage_items.page_front='Y' ORDER BY OrderFP, created_on DESC";
$query_limit_page_items = sprintf("%s LIMIT %d, %d", $query_page_items, $startRow_page_items, $maxRows_page_items);
$page_items = mysql_query($query_limit_page_items, $connvbsa) or die(mysql_error());
$row_page_items = mysql_fetch_assoc($page_items);

if (isset($_GET['totalRows_page_items'])) {
  $totalRows_page_items = $_GET['totalRows_page_items'];
} else {
  $all_page_items = mysql_query($query_page_items, $connvbsa);
  $totalRows_page_items = mysql_num_rows($all_page_items);
}
$totalPages_page_items = ceil($totalRows_page_items/$maxRows_page_items)-1;

//mysql_select_db($database_connvbsa, $connvbsa);
$query_BBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM BBSA";
$BBSA = mysql_query($query_BBSA, $connvbsa) or die(mysql_error());
$row_BBSA = mysql_fetch_assoc($BBSA);
$totalRows_BBSA = mysql_num_rows($BBSA);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_BendBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM BendBSA";
$BendBSA = mysql_query($query_BendBSA, $connvbsa) or die(mysql_error());
$row_BendBSA = mysql_fetch_assoc($BendBSA);
$totalRows_BendBSA = mysql_num_rows($BendBSA);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_CC = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM CC";
$CC = mysql_query($query_CC, $connvbsa) or die(mysql_error());
$row_CC = mysql_fetch_assoc($CC);
$totalRows_CC = mysql_num_rows($CC);

//mysql_select_db($database_connvbsa, $connvbsa);
//$query_Church = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM Church";
//$Church = mysql_query($query_Church, $connvbsa) or die(mysql_error());
//$row_Church = mysql_fetch_assoc($Church);
//$totalRows_Church = mysql_num_rows($Church);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_DVSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM DVSA";
$DVSA = mysql_query($query_DVSA, $connvbsa) or die(mysql_error());
$row_DVSA = mysql_fetch_assoc($DVSA);
$totalRows_DVSA = mysql_num_rows($DVSA);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_MSBA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM MSBA";
$MSBA = mysql_query($query_MSBA, $connvbsa) or die(mysql_error());
$row_MSBA = mysql_fetch_assoc($MSBA);
$totalRows_MSBA = mysql_num_rows($MSBA);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_O55 = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM O55";
$O55 = mysql_query($query_O55, $connvbsa) or die(mysql_error());
$row_O55 = mysql_fetch_assoc($O55);
$totalRows_O55 = mysql_num_rows($O55);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_RSL = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM RSL";
$RSL = mysql_query($query_RSL, $connvbsa) or die(mysql_error());
$row_RSL = mysql_fetch_assoc($RSL);
$totalRows_RSL = mysql_num_rows($RSL);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_SBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM SBSA";
$SBSA = mysql_query($query_SBSA, $connvbsa) or die(mysql_error());
$row_SBSA = mysql_fetch_assoc($SBSA);
$totalRows_SBSA = mysql_num_rows($SBSA);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_WSBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM WSBSA";
$WSBSA = mysql_query($query_WSBSA, $connvbsa) or die(mysql_error());
$row_WSBSA = mysql_fetch_assoc($WSBSA);
$totalRows_WSBSA = mysql_num_rows($WSBSA);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_VBSAmax = "SELECT  Updated  AS MAXDATE FROM Team_entries WHERE Updated=(SELECT MAX(Updated) FROM Team_entries)";
$VBSAmax = mysql_query($query_VBSAmax, $connvbsa) or die(mysql_error());
$row_VBSAmax = mysql_fetch_assoc($VBSAmax);
$totalRows_VBSAmax = mysql_num_rows($VBSAmax);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal = "SELECT * FROM calendar WHERE calendar.visible ='Yes' AND calendar.startdate IS NOT NULL AND calendar.startdate >= curdate() ORDER BY calendar.startdate LIMIT 8";
//echo($query_Cal . "<br>");
$Cal = mysql_query($query_Cal, $connvbsa) or die(mysql_error());
$row_Cal = mysql_fetch_assoc($Cal);
$totalRows_Cal = mysql_num_rows($Cal);

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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Home Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  
  <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>-->
  <link href="CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <link rel="icon" type="image/x-icon" href="images/image001.png">
</head>
<body id="home">

  
<div class="new_header" style="width: 100%; background-color: black; margin-bottom: 13px;">
<img src="https://vbsa.org.au/ui_assets/Logo-full-lockup_horizontal_invert.svg" style="margin: auto; display: block; padding: 25px; max-width: 600px; width: calc(100% - 50px);">
</div>    


   <!-- Include Google Tracking -->
<?php include_once("includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php //include 'includes/header.php';?>
    
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

  		<div>
  			<img src="images_2016/aramith.png" class="img-responsive  center-block" style="margin-bottom:10px"/>
  			<img src="images_2016/strachan.png" class="img-responsive  center-block" style="margin-bottom:10px"/>
  			<img src="images_2016/mitchell.png" class="img-responsive  center-block" style="margin-bottom:10px"/> 
  		</div>
        
  		<div class="right_container visible-xs"> 
    		<div class="right_title">Last Updates</div>
            <div class="right_update">BBSA - <?php $newDate = date("M d, g:ia", strtotime($row_BBSA['MAXDATE'])); echo $newDate; ?></div>
            <div class="right_update">City Clubs - <?php $newDate = date("M d, g:ia", strtotime($row_CC['MAXDATE'])); echo $newDate; ?></div>
            <div class="right_update">DVSA - <?php $newDate = date("M d, g:ia", strtotime($row_DVSA['MAXDATE'])); echo $newDate; ?></div>
            <div class="right_update">MSBA - <?php $newDate = date("M d, g:ia", strtotime($row_MSBA['MAXDATE'])); echo $newDate; ?></div>
            <div class="right_update">Over 55's - <?php $newDate = date("M d, g:ia", strtotime($row_O55['MAXDATE'])); echo $newDate; ?></div>
            <div class="right_update">RSL - <?php $newDate = date("M d, g:ia", strtotime($row_RSL['MAXDATE'])); echo $newDate; ?></div>
            <div class="right_update">SBSA - <?php $newDate = date("M d, g:ia", strtotime($row_SBSA['MAXDATE'])); echo $newDate; ?></div>
            <div class="right_update">VBSA - <?php $newDate = date("M d, g:ia", strtotime($row_VBSAmax['MAXDATE'])); echo $newDate; ?></div>
		</div>
    <div class="right_title center-block" style="width:75%">Coming events</div>
    <div class="center-block" style="width:75%">
<?php do { ?>
      <div class="right_header"><?php echo $row_Cal['event']; ?></div>
      
      <div class="right_content">
        Starts: <?php if ($row_Cal['startdate'] != ''): ?>
        <?php $newDate = date("Y-m-d", strtotime($row_Cal['startdate'])); echo $newDate; ?>
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
 		<div class="index_title"><span class="h4">VBSA News</span></div>
   
 	  
   <div class="search_container">
   		<form id="form1" name="form1" method="get" action="index_srch_res.php">
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input type="text" name="page_content" id="page_content" placeholder="Search items .." />
        </form> 
   </div> 
   </div>
   
   <div class="update_container hidden-xs">
    		<div class="update_title">Last Updates: </div>
            <div class="update">BBSA - <?php $newDate = date("M d, g:ia", strtotime($row_BBSA['MAXDATE'])); echo $newDate; ?> ,</div>
            <div class="update">BendBSA - <?php $newDate = date("M d, g:ia", strtotime($row_BendBSA['MAXDATE'])); echo $newDate; ?> ,</div>
            <div class="update">City Clubs - <?php $newDate = date("M d, g:ia", strtotime($row_CC['MAXDATE'])); echo $newDate; ?> ,</div>
            <div class="update">DVSA - <?php $newDate = date("M d, g:ia", strtotime($row_DVSA['MAXDATE'])); echo $newDate; ?></div>
            <div class="update">MSBA - <?php $newDate = date("M d, g:ia", strtotime($row_MSBA['MAXDATE'])); echo $newDate; ?></div>
            <div class="update">Over 55's - <?php $newDate = date("M d, g:ia", strtotime($row_O55['MAXDATE'])); echo $newDate; ?></div>
            <div class="update">RSL - <?php $newDate = date("M d, g:ia", strtotime($row_RSL['MAXDATE'])); echo $newDate; ?></div>
            <div class="update">SBSA - <?php $newDate = date("M d, g:ia", strtotime($row_SBSA['MAXDATE'])); echo $newDate; ?></div>
            <div class="update">VBSA - <?php $newDate = date("M d, g:ia", strtotime($row_VBSAmax['MAXDATE'])); echo $newDate; ?></div>
            <div class="update">WSBSA - <?php $newDate = date("M d, g:ia", strtotime($row_WSBSA['MAXDATE'])); echo $newDate; ?></div>
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

        
        <?php echo mb_convert_encoding($row_page_items['Comment'], "HTML-ENTITIES", "UTF-8"); ?>
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

//mysql_free_result($Church);

?>