<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../VBSA_Admin_Login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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

if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_item_attach = "SELECT up_id, up_desc, up_pdf_name, up_type, item_id FROM webpage_attach WHERE item_id  = '$item_id'";
$item_attach = mysql_query($query_item_attach, $connvbsa) or die(mysql_error());
$row_item_attach = mysql_fetch_assoc($item_attach);
$totalRows_item_attach = mysql_num_rows($item_attach);

mysql_select_db($database_connvbsa, $connvbsa);
$query_att = "SELECT up_id, up_desc, up_on, up_pdf_name, up_event, item_id, up_type FROM webpage_attach, webpage_items WHERE item_id=ID AND ID='$item_id'";
$att = mysql_query($query_att, $connvbsa) or die(mysql_error());
$row_att = mysql_fetch_assoc($att);
$totalRows_att = mysql_num_rows($att);

mysql_select_db($database_connvbsa, $connvbsa);
$query_page_items = "SELECT * FROM webpage_items WHERE webpage_items.ID='$item_id'";
$page_items = mysql_query($query_page_items, $connvbsa) or die(mysql_error());
$row_page_items = mysql_fetch_assoc($page_items);
$totalRows_page_items = mysql_num_rows($page_items);

mysql_select_db($database_connvbsa, $connvbsa);
$query_info_cal_update = "SELECT upload_on FROM calendar_attach, webpage_items WHERE event_number=event_id AND webpage_items.ID = '$item_id' ORDER BY upload_on DESC LIMIT 1";
$info_cal_update = mysql_query($query_info_cal_update, $connvbsa) or die(mysql_error());
$row_info_cal_update = mysql_fetch_assoc($info_cal_update);
$totalRows_info_cal_update = mysql_num_rows($info_cal_update);

mysql_select_db($database_connvbsa, $connvbsa);
$query_attach_update = "SELECT up_on FROM webpage_attach, webpage_items WHERE item_id = ID AND webpage_items.ID = '$item_id' ORDER BY up_on DESC LIMIT 1";
$attach_update = mysql_query($query_attach_update, $connvbsa) or die(mysql_error());
$row_attach_update = mysql_fetch_assoc($attach_update);
$totalRows_attach_update = mysql_num_rows($attach_update);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Front Page Administation Area</title>
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

<table align="center" cellpadding="5" cellspacing="3">
  <tr>
    <td align="center" class="red_bold">Item Detail for:  
      
      
      <?php echo $item_id . " ";
    if($row_page_items['blocked']=='Yes')
		{
			echo '&nbsp;&nbsp;&nbsp;';
		echo '<span class="red_text">';
		echo " Item is currently blocked and does not appear on the web site";
		echo '</span>';
		}
		?>
    </td>
  </tr>
</table>
<!--Open News Item -->
<div class="webpage_content">

    <div ID="News_container"> 
      
    <div class="News_item">
        <div class="News_item_header">
          <div class="News_item_header_left_corner"></div>
          <div class="News_item_header_title_BG"><?php echo $row_page_items['Header']; ?></div>
          <div class="News_item_header_right_title"></div>
          <div class="News_item_header_right_corner"></div>
          <div class="News_item_header_by">By: <?php echo $row_page_items['By']; ?>&nbsp;&nbsp;<?php $newDate = date("D jS M \- g:iA", strtotime($row_page_items['created_on'])) ; echo $newDate; ?></div>
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
        
<?PHP        



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
?>
        
  	</div><!--Close Container for news item-->
        
</div><!--Close News Item  -->

<div class="News_item_detail_container">
<div class="detail_500">  
  <table align="center">
  <tr>
    <td align="center" class="red_bold">Item Properties</td>
    <td align="left"><a href="user_files/item_edit.php?item_id=<?php echo $row_page_items['ID']; ?>" ><img src="../Admin_Images/edit_butt.fw.png" width="24" height="24" title="Edit" /></a></td>
    </tr>
  <tr>
    <td colspan="2" align="left">
      <?php
	  // establish if item is ordered on any of the web pages
	if($row_page_items['OrderFP']=='not ordered' && $row_page_items['OrderRef']=='not ordered' && $row_page_items['OrderJunior']=='not ordered' && $row_page_items['OrderHelp']=='not ordered' && $row_page_items['OrderWomens']=='not ordered' && $row_page_items['OrderRefProfile']=='not ordered' && $row_page_items['OrderRefPoser']=='not ordered' && $row_page_items['OrderPlayerProfile']=='not ordered' && $row_page_items['OrderScores']=='not ordered' && $row_page_items['OrderPolProc']=='not ordered' && $row_page_items['OrderAbout']=='not ordered')
	{
		// if not ordered
	 	echo "Item is not ordered"; 
	}
	else
	{
		// if it is ordered
		echo " Ordered: ";
	}
	
    if($row_page_items['OrderFP']<>'not ordered')
		{
		echo "Front = ";
		echo $row_page_items['OrderFP'];
		echo ". ";
		}
		else echo "";

    if($row_page_items['OrderRef']<>'not ordered')
		{
		echo "Referees = ";
		echo $row_page_items['OrderRef'];
		echo ". ";
		}
		else echo "";

    if($row_page_items['OrderJunior']<>'not ordered')
		{
		echo "Junior = ";
		echo $row_page_items['OrderJunior'];
		echo ". ";
		}
		else echo "";

	if($row_page_items['OrderHelp']<>'not ordered')
		{
		echo "Help = ";
		echo $row_page_items['OrderHelp'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderWomens']<>'not ordered')	
		{
		echo "Womens = ";
		echo $row_page_items['OrderWomens'];
		echo ". ";
		}
		else echo "";
		
			
	if($row_page_items['OrderRefProfile']<>'not ordered')	
		{
		echo "Ref Profiles = ";
		echo $row_page_items['OrderRefProfile'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderRefPoser']<>'not ordered')	
		{
		echo "Ref Posers = ";
		echo $row_page_items['OrderRefPoser'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderScores']<>'not ordered')	
		{
		echo "Scores = ";
		echo $row_page_items['OrderScores'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderPlayerProfile']<>'not ordered')	
		{
		echo "Player Profile = ";
		echo $row_page_items['OrderPlayerProfile'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderPolProc']<>'not ordered')	
		{
		echo "Policies & Procedures = ";
		echo $row_page_items['OrderPolProc'];
		echo ". ";
		}
		else echo "";	
		
		// previously info page
	if($row_page_items['OrderAbout']<>'not ordered')	
		{
		echo "About = ";
		echo $row_page_items['OrderAbout'];
		echo ". ";
		}
		else echo "";
	?>
    </td>
    </tr>
  <tr>
    <td colspan="2" align="left">
    <?php
    if($row_page_items['page_front']=='Y' || $row_page_items['page_referee']=='Y' || $row_page_items['page_junior']=='Y' || $row_page_items['page_help']=='Y' || $row_page_items['page_womens']=='Y' || $row_page_items['page_refprofile']=='Y' || $row_page_items['page_refposer']=='Y' || $row_page_items['page_playerprofile']=='Y' || $row_page_items['page_about']=='Y' || $row_page_items['page_scores']=='Y' || $row_page_items['page_polproc']=='Y')
		{
		echo " Item is set to appear on page/s - ";
		}

    if($row_page_items['page_front']=='Y')
		{
		echo " Front. ";
		}
		else echo "";

    if($row_page_items['page_referee']=='Y')
		{
		echo " Referees. ";
		}
		else echo "";

    if($row_page_items['page_junior']=='Y')
		{
		echo " Juniors. ";
		}
		else echo "";

    if($row_page_items['page_help']=='Y')
		{
		echo " Help. ";
		}
		else echo "";
	
	if($row_page_items['page_womens']=='Y')
		{
		echo " Womens. ";
		}
		else echo "";
		
	if($row_page_items['page_refprofile']=='Y')
		{
		echo " Ref Profile. ";
		}
		
	if($row_page_items['page_refposer']=='Y')
		{
		echo " Ref Posers. ";
		}
		else echo "";
		
	if($row_page_items['page_playerprofile']=='Y')
		{
		echo " Player Profile. ";
		}
		else echo "";
		
	if($row_page_items['page_scores']=='Y')
		{
		echo " Scores. ";
		}
		else echo "";
		
	if($row_page_items['page_polproc']=='Y')
		{
		echo " Policies & Procedures. ";
		}
		else echo "";
	
	// previously info page now about	
	if($row_page_items['page_about']=='Y')
		{
		echo " About. ";
		}
		else echo "";
		
	
		
	
	?>
    </td>
    </tr>
  <tr>
    <td colspan="2" align="left"><?php if(isset($row_page_items['event_id'])) 
	{
		echo "This item was created from Event ID:";
	}
	else
	{
		echo "";
	}
	
	if(isset($row_page_items['event_id'])) 
	{
		echo $row_page_items['event_id'];
	}
	else
	{
		echo "";
	}
	?>
    </td>
    </tr>
  <tr>
    <td colspan="2" align="left">&nbsp;</td>
    </tr>
  </table>
</div>
<div class="detail_500"> 
  <table align="center">
    <tr>
      <td>&nbsp;</td>
      <td align="center"><span class="red_bold">Image Properties</span></td>      <td colspan="2" align="left"><span class="greenbg"><a href="user_files/item_upload_image.php?item_id=<?php echo $row_page_items['ID']; ?>">Upload new or edit existing image</a></span></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td align="right">Image Path:</td>
      <td align="left">
      <?php if (isset($row_page_items['item_image'])) { echo $row_page_items['item_image']; } else { echo "No Image"; } ?>
      </td>
      <td align="left">
        <?php if (isset($row_page_items['item_image'])) { ?>
        <a href="user_files/item_image_delete.php?item_id=<?php echo $row_page_items['ID']; ?>"><img src="../Admin_Images/Trash.fw.png" width="20" height="18" title="Delete the image" /></a>
        <?php } else { echo ""; } ?>
      </td>
      
      </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td align="right"><?php if (isset($row_page_items['item_image'])) echo "Image current size:"; else echo ""; ?></td>
      <td colspan="2">&nbsp;<?php if (isset($row_page_items['item_image'])) echo $row_page_items['img_size']; else echo ""; ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3" class="page"><a href="webpage_admin_images/preparing_images_use_website.pdf" target="_blank">How to prepare an image for web use and why it is important</a> </td>
      </tr>
  </table>
</div>


<?php if (!isset($row_page_items['event_id'])) { ?>
<div class="detail_1000">
  <table width="850" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td class="red_bold">Web page Attachments</td>
      <td align="right" class="greenbg"><a href="user_files/attach_upload.php?item_id=<?php echo $row_page_items['ID']; ?>">Insert/upload a new attachment </a></td>
      <td align="right" class="greenbg"><a href="user_files/attach_URL_insert.php?item_id=<?php echo $row_page_items['ID']; ?>">Insert a URL Link</a></td>
      <td align="right" class="greenbg"><a href="user_files/attach_email_insert.php?item_id=<?php echo $row_page_items['ID']; ?>"> Insert an email link</a></td>
    </tr>
</table>
  <table style="min-width:650" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <td>Type</td>
      <td>Description</td>
      <td>Path</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_item_attach['up_type']; ?></td>
        <td><?php echo $row_item_attach['up_desc']; ?></td>
        <td><?php echo $row_item_attach['up_pdf_name']; ?></td>
        <td>
        <?php if ($row_item_attach['up_type'] == 'Attachment'): ?>
        <a href="user_files/attach_upload_edit.php?up_id=<?php echo $row_item_attach['up_id']; ?>&item_id=<?php echo $row_item_attach['item_id']; ?>"><img src="../Admin_Images/edit_butt.png" height="22" title="Edit Attachment" /></a>    
		<?php endif; ?>
        
        <?php if ($row_item_attach['up_type'] == 'Email'): ?>
        <a href="user_files/attach_email_edit.php?up_id=<?php echo $row_item_attach['up_id']; ?>&item_id=<?php echo $row_item_attach['item_id']; ?>"><img src="../Admin_Images/edit_butt.png" height="22" title="Edit Email" /></a>    
		<?php endif; ?>
        
        <?php if ($row_item_attach['up_type'] == 'URL'): ?>
        <a href="user_files/attach_URL_edit.php?up_id=<?php echo $row_item_attach['up_id']; ?>&item_id=<?php echo $row_item_attach['item_id']; ?>"><img src="../Admin_Images/edit_butt.png" height="22" title="Edit URL" /></a>    
		<?php endif; ?>
        </td>
        <td>
        <?php if(isset($row_item_attach['up_id'])) { ?>
        <a href="user_files/attach_delete_confirm.php?up_id=<?php echo $row_item_attach['up_id']; ?>&item_id=<?php echo $row_item_attach['item_id']; ?>"><img src="../Admin_Images/Trash.fw.png" height="22" /></a>
        <?php } else echo '<td>&nbsp;</td>' ?>
        </td>
      </tr>
      <?php } while ($row_item_attach = mysql_fetch_assoc($item_attach)); ?>
  </table>
</div>
<?php } else echo ""; ?>




<?php if (isset($row_page_items['event_id'])) { ?>

<div class="News_item_detail_container">
<div class="detail_1000">
  <table width="850" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td class="red_bold">Calendar Attachments (Can only be edited from the calendar)</td>
      <td align="right" class="greenbg"><a href="../Admin_Calendar/calendar_detail.php?eventID=<?php echo $row_page_items['event_id']; ?>" >Go to this event in the calendar </a></td>
    </tr>
</table>

<?php if($totalRows_item_attach >0 ) 
	{ 
	?>

	<table width="600" align="center" cellpadding="3" cellspacing="3">
  		<tr>
    		<td>Type</td>
    		<td>Description</td>
    		<td>Path</td>
   		  </tr>
  		<?php do { ?>
    	<tr>
      		<td><?php echo $row_item_attach['type']; ?></td>
      		<td><?php echo $row_item_attach['attach_name']; ?></td>
      		<td><?php echo $row_item_attach['Attachment']; ?></td>
      		</tr>
    	<?php } while ($row_item_attach = mysql_fetch_assoc($item_attach)); ?>
	</table>
<?php 
	}
	else
	{
		echo '<div class="query_result_empty_message">';
		echo "There are no attachments to this event in the calendar. To add attachments to this item please go to the calendar";
		echo '</div>';
	}
	?>
</div>
</div>
<?php } else echo ""; ?>


</div>









</div>
</body>
</html>
<?php
mysql_free_result($item_attach);

mysql_free_result($att);

mysql_free_result($page_items);

mysql_free_result($info_cal_update);

mysql_free_result($attach_update);
?>
