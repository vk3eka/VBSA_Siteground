<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer,Administrator,Boardmember,Secretary,Scores";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_page_items = "SELECT webpage_items.ID, Header, Comment,`By`, created_on, updated, MAX(upload_on) AS caldate, MAX(up_on) AS FPdate, item_image, event_id, page_front, img_size, OrderFP, OrderRef, OrderJunior, OrderHelp, OrderWomens, OrderRefProfile, OrderRefPoser, OrderPlayerProfile, OrderScores, OrderPolProc, OrderAbout FROM webpage_items LEFT JOIN calendar_attach ON event_number=event_id LEFT JOIN webpage_attach ON item_id = webpage_items.ID WHERE webpage_items.blocked='No' AND webpage_items.page_about='Y' GROUP BY webpage_items.ID ORDER BY OrderAbout, created_on DESC";
$page_items = mysql_query($query_page_items, $connvbsa) or die(mysql_error());
$row_page_items = mysql_fetch_assoc($page_items);
$totalRows_page_items = mysql_num_rows($page_items);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administation Area</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<link href="../Admin_xx_CSS/VBSA_db_webpages.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/news_item_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php include '../admin_xx_includes/webpage_nav.php';?>

<table width="1000" align="center">
  <tr>
    <td colspan="3" align="center" class="red_bold">About Page</td>
  </tr>
  <tr>
    <td width="630" align="right">To update an item, add an image, create links etc please click the detail button </td>
    <td width="5" align="center">&nbsp;</td>
    <td width="349" align="left"><img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" /></td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="red_bold">When you have finished please check the website, you may have to refresh the site to see your changes </td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
</table>
<!--Open News Item -->
<div class="webpage_content">

      
  <?php do { ?>
  <!--Open News Item Repeat  - includes table at right of page-->
    <div ID="News_container"> 
      
		<!--Container for news item-->
      <!--Open News Item -->
    <div class="News_item">
        <div class="News_item_header">
          <div class="News_item_header_left_corner"></div>
          <div class="News_item_header_title_BG"><?php echo $row_page_items['Header']; ?></div>
          <div class="News_item_header_right_title"></div>
          <div class="News_item_header_right_corner"></div>
          <div class="News_item_header_by">By: <?php echo $row_page_items['By']; ?>&nbsp;&nbsp;<?php $newDate = date("jS M Y", strtotime($row_page_items['created_on'])) ; echo $newDate; ?>&nbsp;&nbsp; <?php $update = date("D jS M \- g:iA", strtotime($row_page_items['updated'])) ; if(isset($row_page_items['updated'])) echo "Updated: " . $update; ?></div>
        </div>
        <div class="News_item_content">
        <?php
		if(empty ($row_page_items['item_image'])) echo " ";
		else { ?>
        
        <img src="../images_frontpage/<?php echo $row_page_items['item_image']; ?>" width="<?php echo $row_page_items['img_size']; ?>" style="float:right; padding:10px" />
        <?php  }  ?>
        
        <div class="page"><?php echo $row_page_items['Comment']; ?></div>
        </div>
        
        <!--Begin "information" footer -->
        
<?php       

// display "Information" and list the web page attachments (from table webpage_attach) if they exist 

$query_FP = "SELECT up_id, up_desc, up_on, up_pdf_name, up_event, item_id, up_type FROM webpage_attach, webpage_items WHERE item_id=ID AND ID= ".$row_page_items['ID'].""; 
$result_FP = mysql_query($query_FP) or die(mysql_error());		

		if(mysql_num_rows(mysql_query($query_FP)) >0 )

		{
			echo '<div class="News_item_close">'; // changes bg image from white to grey
			echo '</div>';
				
			echo '<div class="News_item_info_bg">';	// grey bg
			echo '<div class="News_item_content_info">';  // "News_item_content_info" is the containing div	for all info items
        
        	echo '<div class="item_info_title">';
			echo "Information: ";
			echo '</div>';

		
			while($row_FP = mysql_fetch_array($result_FP)):	   
			 
 					if ($row_FP['up_type'] == 'Attachment'):
				
					echo '<div class="item_info">';
					echo '<img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" />';
					echo '<span class="page">';
					echo "<a href=../Front_page_upload/".$row_FP['up_pdf_name']." target=_blank>".$row_FP['up_desc']."</a>";
					echo '</span>';
					echo '</div>';
				endif;
				
				if ($row_FP['up_type'] == 'URL'):
				
					echo '<div class="item_info">';
					echo '<img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" />';
					echo '<span class="page">';
					echo "<a href=".$row_FP['up_pdf_name']." target=_blank>".$row_FP['up_desc']."</a>";
					echo '</span>';
					echo '</div>';
				endif;
				
				if ($row_FP['up_type'] == 'Email'):
				
					echo '<div class="item_info">';
					echo '<img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" />';
					echo '<span class="page">';
					echo "<a href=mailto:".$row_FP['up_pdf_name']." target=_blank>".$row_FP['up_desc']."</a>";
					echo '</span>';
					echo '</div>';
				endif;
			endwhile;
			
			//if FP attachments have been updated
			$updateFP = date("D jS M \- g:iA", strtotime($row_page_items['FPdate'])) ; 
			if(isset($row_page_items['FPdate']))
			echo '<div class="News_item_info_update">' . "Updated: " . $updateFP . '</div>';
			else
			echo "";
				
			echo "</div>"; // END "News_item_content_info" containing div
			echo '</div>'; // closes grey bg
				
			echo '<div class="News_item_info_close">'; //bg rounded corners and bottom of container image
			echo '</div>';
			
							
		}


// display "Information" and list the calendar attachments if they exist 

$query = "SELECT attach_name, Attachment, type FROM calendar_attach, webpage_items WHERE event_number=event_id AND webpage_items.ID = ".$row_page_items['ID'].""; 
$result = mysql_query($query) or die(mysql_error());		

		if(mysql_num_rows(mysql_query($query)) >0 )

		{
			echo '<div class="News_item_close">'; // changes bg image from white to grey
			echo '</div>';
				
			echo '<div class="News_item_info_bg">';	// grey bg
			echo '<div class="News_item_content_info">';  // "News_item_content_info" is the containing div	for all info items
        
        	echo '<div class="item_info_title">';
			echo "Information: ";
			echo '</div>';

		
			while($row = mysql_fetch_array($result)):	   
			 
 					if ($row['type'] == 'Uploaded Attachment'):
				
					echo '<div class="item_info">';
					echo '<img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" />';
					echo '<span class="page">';
					echo "<a href=../calendar/cal_upload/".$row['Attachment']." target=_blank>".$row['attach_name']."</a>";
					echo '</span>';
					echo '</div>';
				endif;
				
				if ($row['type'] == 'URL'):
				
					echo '<div class="item_info">';
					echo '<img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" />';
					echo '<span class="page">';
					echo "<a href=".$row['Attachment']." target=_blank>".$row['attach_name']."</a>";
					echo '</span>';
					echo '</div>';
				endif;
				
				if ($row['type'] == 'Email'):
				
					echo '<div class="item_info">';
					echo '<img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" />';
					echo '<span class="page">';
					echo "<a href=mailto:".$row['Attachment']." target=_blank>".$row['attach_name']."</a>";
					echo '</span>';
					echo '</div>';
				endif;
			endwhile;
			
			//if calendar attachments have been updated
			$update = date("D jS M \- g:iA", strtotime($row_page_items['caldate'])) ; 
			if(isset($row_page_items['caldate']))
			echo '<div class="News_item_info_update">' . "Updated: " . $update . '</div>';
			else
			echo "";

				
			echo "</div>"; // END "News_item_content_info" containing div
			echo '</div>'; // closes grey bg
				
			echo '<div class="News_item_info_close">'; //bg rounded corners and bottom of container image
			echo '</div>';				
		} 
		
		//Display the footer if the number of rows in info (query_FP) or event attach (query_event) is 0. 		
		elseif(mysql_num_rows(mysql_query($query_FP)) ==0 && mysql_num_rows(mysql_query($query)) ==0) 
			{
				echo '<div class="News_item_footer">';
				echo '</div>';
			}
?><!--End Information footer-->
        
  	</div><!--Close Container for news item-->   
		   
    <div class="News_item_links">
      		<?php include '../admin_xx_includes/db_webpage_right_table.php';?>
    </div>
</div><!--Close News Item repeat - includes table at right of page -->
    <?php } while ($row_page_items = mysql_fetch_assoc($page_items)); ?>


</div>
</body>
</html>
<?php

?>
