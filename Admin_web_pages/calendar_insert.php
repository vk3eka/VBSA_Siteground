<?php require_once('../Connections/connvbsa.php'); ?>
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
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
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
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO webpage_items (ID, Header, `Comment`, `By`, created_on, blocked, img_orientation, event_id, page_front, page_referee, page_junior, page_help, page_womens, page_refprofile, page_refposer, page_playerprofile, page_about) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['Header'], "text"),
                       GetSQLValueString($_POST['Comment'], "text"),
                       GetSQLValueString($_POST['By'], "text"),
                       GetSQLValueString($_POST['created_on'], "date"),
                       GetSQLValueString($_POST['blocked'], "text"),
                       GetSQLValueString($_POST['img_orientation'], "text"),
                       GetSQLValueString($_POST['event_id'], "text"),
                       GetSQLValueString(isset($_POST['page_front']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_referee']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_junior']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_help']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_womens']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_refprofile']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_refposer']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_playerprofile']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_about']) ? "true" : "", "defined","'Y'","'N'"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "item_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if (isset($_GET['eventID'])) {
  $eventID = $_GET['eventID'];
}

if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Cal_list = "SELECT * FROM calendar WHERE event_id ='$eventID'";
$Cal_list = mysql_query($query_Cal_list, $connvbsa) or die(mysql_error());
$row_Cal_list = mysql_fetch_assoc($Cal_list);
$totalRows_Cal_list = mysql_num_rows($Cal_list);

mysql_select_db($database_connvbsa, $connvbsa);
$query_info = "SELECT attach_name, Attachment, type FROM calendar_attach WHERE event_number = '$eventID' ORDER BY type";
$info = mysql_query($query_info, $connvbsa) or die(mysql_error());
$row_info = mysql_fetch_assoc($info);
$totalRows_info = mysql_num_rows($info);

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

<script type="text/javascript" >
function validateForm() {
    var allchecked=0;
if(document.getElementById('page_front').checked)allchecked=1;
if(document.getElementById('page_referee').checked)allchecked=1;
if(document.getElementById('page_junior').checked)allchecked=1;
if(document.getElementById('page_help').checked)allchecked=1;
if(document.getElementById('page_womens').checked)allchecked=1;
if(document.getElementById('page_refprofile').checked)allchecked=1;
if(document.getElementById('page_refposer').checked)allchecked=1;
if(document.getElementById('page_playerprofile').checked)allchecked=1;
if(document.getElementById('page_about').checked)allchecked=1;
    if(allchecked==0) {
        alert("NO PAGE SELECTED\n\nPlease select at least one");
        return false;
    }
	else {return true;}
}
</script>

<link href="../Admin_xx_CSS/VBSA_db_webpages.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/news_item_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<table align="center" class="page">
  <tr>
    <td class="red_bold">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td class="red_bold">Event Detail for Event ID: <?php echo $eventID; ?> - <?php echo $row_Cal_list['event']; ?></td>
    <td align="center"><a href="javascript:history.go(-1)"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></a></td>
  </tr>
  <tr>
    <td colspan="2"><span class="red_bold">All editing of this event or attachments may only be done from the &quot;Calendar&quot;. If you do not have access to the calendar please contact the <a href="mailto:scores@vbsa.org.au" target="_blank">webmaster</a></span></td>
  </tr> 
</table>
  <p>&nbsp;</p>
  <table align="center" class="red_bold">
    
    <tr>
      <td>Your Website item will look like this, you may edit after you have inserted.</td>
    </tr>
  </table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<hr color="#CCCCCC" width="850px" align="center"/>

<table align="center" cellpadding="3" cellspacing="3" width="1000px" style="border-style: solid 1px #666666">
  <tr>
    <td align="left" class="red_text"><?php echo $row_Cal_list['event']; ?></td>
  </tr>
  <tr>
  <td align="left">
  <?php	  
		// insert calendar content for about and venue if it is set 
	  	echo $row_Cal_list['about'] . ". ";

      	if (isset($row_Cal_list['venue']))
		{
			echo " Venue - " . $row_Cal_list['venue'] . ". ";
		}
		else
		{
			echo "";
		}
		
		// insert start date
		$StartDate = date("D jS M", strtotime($row_Cal_list['startdate'])); 
        echo "Starts - ";
		echo $StartDate. " ";
		
		// insert entries close date if it is set
		if (isset($row_Cal_list['closedate']))
		{
			echo "Entries close - "; 
			$newDate = date("D jS M", strtotime($row_Cal_list['closedate'])); 
			echo $newDate . ". ";
		}
		else
		{
			echo "";
		}
		
		
		?>
  		</div>
    </td>
  </tr>
  <tr>
    <td>   
    <?php
    //footer
		if ($row_Cal_list['footer1']=='Y' OR $row_Cal_list['footer2']=='Y'OR $row_Cal_list['footer3']=='Y'OR $row_Cal_list['footer4']=='Y')
		{
			echo '</br>';
		}
		?>

        <?php
		if ($row_Cal_list['footer1']=='Y')
		{
			echo '<span class="page">';
			echo "To enter this event, pay your membership or make a payment to the VBSA please go to the ";
			echo "<a href=../vbsa_store/frontend/index.php target='_blank'>payments page </a>";
			echo "Enquiries - ";
			echo '<a href=mailto:treasurer@vbsa.org.au> VBSA treasurer </a>';
			echo '</span>';
		}
		else
		{
			echo "";
		}
		if ($row_Cal_list['footer2']=='Y')
		{
			echo '<span class="page">';
        	echo "To check the VBSA have received your entry please go to ";
			echo '<a href=http://www.vbsa.org.au/Tournaments/tournindex.php>&quot;VBSA Tournament entries&quot;</a>';
			echo ". Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament.";
		}
		else
		{
			echo "";
		}
		if ($row_Cal_list['footer3']=='Y')
		{
			echo "Please Note: The VBSA do not accept entries for this event, please refer the entry form for details on how to enter. ";
		}
		else
		{
			echo "";
		}
		if ($row_Cal_list['footer4']=='Y')
		{
			echo"For results please go to the ";
			echo '<span class="page">';
			echo "<a href=http://absc.com.au/results.aspx target='_blank'>ABSC Site</a>"; 
			echo '</span>';
		}
		else
		{
			echo "";
		}
		/*
		if ($row_Cal_list['footer5']=='Y')
		{
			echo '<span class="page">';
			echo $row_Cal_list['footer5']; 
			echo '</span>';
		}
		else
		{
			echo "";
		}
		*/
		// close footer
        ?>
    </td>
  </tr>
  <tr>
    <td>
    <!--Information section start If the result of the query is >0 -->
    <?php
		$query = "SELECT * FROM calendar_attach WHERE event_number =".$row_Cal_list['event_id'].""; 
	 
		$result = mysql_query($query) or die(mysql_error());
		
		if(mysql_num_rows(mysql_query($query)) >0)
			{
				
				echo '<div class="item_info_title">';
				echo "Information: ";
				echo "</div>";
				
				while($row = mysql_fetch_array($result))
							{
								if ($row['type'] == 'Uploaded Attachment')
								{
									echo '<div class="item_info">';
									echo '<img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" />';
									echo '<span class="page">';
									echo "<a href=../ComingEvents/cal_upload/".$row['Attachment']." target=_blank>".$row['attach_name']."</a>";
									echo '</span>';
									echo '</div>';
								}
								
								elseif ($row['type'] == 'URL')
								{
									echo '<div class="item_info">';
									echo '<img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" />';
									echo '<span class="page">';
									echo "<a href=".$row['Attachment']." target=_blank>".$row['attach_name']."</a>";
									echo '</span>';
									echo '</div>';
								}
								
								elseif ($row['type'] == 'Email')
								{
									echo '<div class="item_info">';
									echo '<img src="../images/attach_entry.gif" width="25" height="16" title="Attachment" />';
									echo '<span class="page">';
									echo "<a href=mailto:".$row['Attachment']." target=_blank>".$row['attach_name']."</a>";
									echo '</span>';
									echo '</div>';
								}

						}
			}
		  ?>
   </td>
 </tr>
</table>

<hr color="#CCCCCC" width="850px" align="center"/>
     
    
  
      
        
        


<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return validateForm()">

<table width="1000" align="center" class="page">
        <tr valign="baseline">
          <td align="right" nowrap="nowrap">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td width="157" align="right" nowrap="nowrap">Blocked:</td>
        <td width="831"><select name="blocked">            
              <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
              <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
            </select>
        If &quot;Yes&quot; the item will not appear on the website          </td>
        </tr>
        
        <tr valign="baseline">
          <td align="right" valign="middle" nowrap="nowrap">Page Selection:</td>
          <td>
          <fieldset>
          <legend> Please select the page or pages that you want this item to appear on !!!</legend>
          <label> Front Page<input type="checkbox" name="page_front" id="page_front" />&nbsp;&nbsp;&nbsp;</label>
          <label> Junior Page <input type="checkbox" name="page_junior" id="page_junior" />&nbsp;&nbsp;&nbsp;</label>
          <label> Help Page<input type="checkbox" name="page_help" id="page_help" />&nbsp;&nbsp;&nbsp;</label>
          <label> Womens Page<input type="checkbox" name="page_womens" id="page_womens" />&nbsp;&nbsp;&nbsp;</label>
          <label> Player Profiles<input type="checkbox" name="page_playerprofile" id="page_playerprofile" />&nbsp;&nbsp;&nbsp;</label>
          <label> Featured<input type="checkbox" name="page_about" id="page_about" />&nbsp;&nbsp;&nbsp;</label>
          </fieldset>
          <fieldset>
          <label> Referees Page<input type="checkbox" name="page_referee" id="page_referee" />&nbsp;&nbsp;&nbsp;</label>
          <label> Referee Profiles <input type="checkbox" name="page_refprofile" id="page_refprofile" />&nbsp;&nbsp;&nbsp;</label>
          <label> Referee Posers<input type="checkbox" name="page_refposer" id="page_refposer" />&nbsp;&nbsp;&nbsp;</label>
          </fieldset>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><em class="red_text">Pages are currently being built, have had to make modifications to a large amount of code, please let me know if you see any problems. The check boxes for Player Profiles and Featured are part of a complete redesign of the site and will not be available until that is completed. </em></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right"> Ordered ?:</td>
          <td> Item inserted as &quot;not ordered&quot; please edit if you want this item to remain in a selected position on any of the selected pages</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insert item" /></td>
        </tr>
  </table>
  	<input type="hidden" name="Header" value="<?php echo $row_Cal_list['event']; ?>"/>
  	<input type="hidden" name="Comment" value="
    	<?php	  
		// insert calendar content for about and venue if it is set 
	  	echo $row_Cal_list['about'] . ". ";

      	if (isset($row_Cal_list['venue']))
		{
			echo " Venue - " . $row_Cal_list['venue'] . ". ";
		}
		else
		{
			echo "";
		}
		
		// insert start date
		$StartDate = date("D jS M", strtotime($row_Cal_list['startdate'])); 
        echo "Starts - ";
		echo $StartDate. " ";
		
		// insert entries close date if it is set
		if (isset($row_Cal_list['closedate']))
		{
			echo "Entries close - "; 
			$newDate = date("D jS M", strtotime($row_Cal_list['closedate'])); 
			echo $newDate . ". ";
		}
		else
		{
			echo "";
		}
		
		
		?>
    " />
	<input type="hidden" name="img_orientation" value="No Image" />
	<input type="hidden" name="By" value="<?php echo $row_getusername['name']; ?>" />
  	<input type="hidden" name="event_id" value="<?php echo $row_Cal_list['event_id']; ?>"/>
	<input type="hidden" name="ID" value="<?php echo $item_id; ?>" />
    <input type="hidden" name="created_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?> " />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>
<?php

mysql_free_result($getusername);

mysql_free_result($Cal_list);

mysql_free_result($info);
?>
