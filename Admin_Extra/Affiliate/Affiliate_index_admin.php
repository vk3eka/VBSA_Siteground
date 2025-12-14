<?php require_once('../../Connections/connvbsa.php'); ?>
<?php include('../php_function.php'); ?>
<?php
error_reporting(0);

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
	
  $logoutGoTo = "../vbsa_extra_logout.php";
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
$MM_authorizedUsers = "Webmaster,BBSA";
$MM_donotCheckaccess = "false";
/*
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
*/
$MM_restrictGoTo = "../Access_Denied.php";
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


$affiliate = "-1";
if (isset($_GET['affiliate'])) {
  $affiliate = $_GET['affiliate'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_pageheader = "Select " . $affiliate . "_id, pagezone_header_desc, pagezone_header FROM " . $affiliate . " WHERE pagezone_header_desc is not null AND " . $affiliate . "_id < 7";
//echo($query_pageheader . "<br>");
$pageheader = mysql_query($query_pageheader, $connvbsa) or die(mysql_error());
$row_pageheader = mysql_fetch_assoc($pageheader);
$totalRows_pageheader = mysql_num_rows($pageheader);

mysql_select_db($database_connvbsa, $connvbsa);
$query_info = "Select date_format(uploaded_on,'%D %M %Y %H:%i') AS uploaded_on_date, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date," . $affiliate . "_id, " . $affiliate . "_type, item_title, pdf_name, " . $affiliate . ".`current`, uploaded_on, " . $affiliate . ".list_order FROM " . $affiliate . " WHERE " . $affiliate . "_type ='a_info' AND " . $affiliate . ".`current`=1  ORDER BY list_order, uploaded_on DESC";
$info = mysql_query($query_info, $connvbsa) or die(mysql_error());
$row_info = mysql_fetch_assoc($info);
$totalRows_info = mysql_num_rows($info);

mysql_select_db($database_connvbsa, $connvbsa);
$query_News = "Select " . $affiliate . "_id, " . $affiliate . "_type, item_title,  list_order, news_content FROM " . $affiliate . " WHERE " . $affiliate . "_type ='b_news' AND " . $affiliate . ".`current`=1 ORDER BY list_order";
$News = mysql_query($query_News, $connvbsa) or die(mysql_error());
$row_News = mysql_fetch_assoc($News);
$totalRows_News = mysql_num_rows($News);

mysql_select_db($database_connvbsa, $connvbsa);
$query_maxdate = "Select CASE WHEN MAX( uploaded_on ) > MAX( edited_on ) THEN MAX( uploaded_on ) ELSE MAX( edited_on ) END AS MAXDATE FROM " . $affiliate;
$maxdate = mysql_query($query_maxdate, $connvbsa) or die(mysql_error());
$row_maxdate = mysql_fetch_assoc($maxdate);
$totalRows_maxdate = mysql_num_rows($maxdate);

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone1 = "Select date_format(uploaded_on,'%D %M %Y %H:%i') AS uploaded_on_date, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date,  " . $affiliate . "_id, " . $affiliate . "_type, item_title, pdf_name, " . $affiliate . ".`current`, uploaded_on, " . $affiliate . ".list_order, edited_on, " . $affiliate . ".pagezone_header FROM " . $affiliate . " WHERE " . $affiliate . "." . $affiliate . "_type='c_zone1' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone1 = mysql_query($query_zone1, $connvbsa) or die(mysql_error());
$row_zone1 = mysql_fetch_assoc($zone1);
$totalRows_zone1 = mysql_num_rows($zone1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone2 = "Select date_format(uploaded_on,'%D %M %Y %H:%i') AS uploaded_on_date, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date,  " . $affiliate . "_id, " . $affiliate . "_type, item_title, pdf_name, " . $affiliate . ".`current`, uploaded_on, " . $affiliate . ".list_order, " . $affiliate . ".pagezone_header, " . $affiliate . ".list_order FROM " . $affiliate . " WHERE " . $affiliate . "." . $affiliate . "_type='d_zone2' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone2 = mysql_query($query_zone2, $connvbsa) or die(mysql_error());
$row_zone2 = mysql_fetch_assoc($zone2);
$totalRows_zone2 = mysql_num_rows($zone2);

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone3 = "Select date_format(uploaded_on,'%D %M %Y %H:%i') AS uploaded_on_date, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date,  " . $affiliate . "_id, " . $affiliate . "_type, item_title, pdf_name, " . $affiliate . ".`current`, uploaded_on, " . $affiliate . ".list_order, " . $affiliate . ".pagezone_header, " . $affiliate . ".list_order FROM " . $affiliate . " WHERE " . $affiliate . "." . $affiliate . "_type='e_zone3' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone3 = mysql_query($query_zone3, $connvbsa) or die(mysql_error());
$row_zone3 = mysql_fetch_assoc($zone3);
$totalRows_zone3 = mysql_num_rows($zone3);


mysql_select_db($database_connvbsa, $connvbsa);
$query_zone4 = "Select date_format(uploaded_on,'%D %M %Y %H:%i') AS uploaded_on_date, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date,  " . $affiliate . "_id, " . $affiliate . "_type, item_title, pdf_name, " . $affiliate . ".`current`, uploaded_on, " . $affiliate . ".list_order, " . $affiliate . ".pagezone_header  FROM " . $affiliate . " WHERE " . $affiliate . "." . $affiliate . "_type='f_zone4' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone4 = mysql_query($query_zone4, $connvbsa) or die(mysql_error());
$row_zone4 = mysql_fetch_assoc($zone4);
$totalRows_zone4 = mysql_num_rows($zone4);

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone5 = "Select date_format(uploaded_on,'%D %M %Y %H:%i') AS uploaded_on_date, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date,  " . $affiliate . "_id, " . $affiliate . "_type, item_title, pdf_name, " . $affiliate . ".`current`, uploaded_on, " . $affiliate . ".list_order, " . $affiliate . ".pagezone_header  FROM " . $affiliate . " WHERE " . $affiliate . "." . $affiliate . "_type='g_zone5' AND current=1 ORDER BY list_order, uploaded_on DESC";
$zone5 = mysql_query($query_zone5, $connvbsa) or die(mysql_error());
$row_zone5 = mysql_fetch_assoc($zone5);
$totalRows_zone5 = mysql_num_rows($zone5);

mysql_select_db($database_connvbsa, $connvbsa);
$query_hist = "Select date_format(uploaded_on,'%D %M %Y %H:%i') AS uploaded_on_date, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date,  " . $affiliate . "_id, " . $affiliate . "_type, item_title, pdf_name, " . $affiliate . ".`current`, uploaded_on, " . $affiliate . ".list_order FROM " . $affiliate . " WHERE " . $affiliate . "." . $affiliate . "_type='f_history' AND current=1 ORDER BY list_order, uploaded_on DESC";
$hist = mysql_query($query_hist, $connvbsa) or die(mysql_error());
$row_hist = mysql_fetch_assoc($hist);
$totalRows_hist = mysql_num_rows($hist);

mysql_select_db($database_connvbsa, $connvbsa);
$query_contact = "Select * FROM " . $affiliate . "_contact ORDER BY " . $affiliate . "_contact.contact_order";
$contact = mysql_query($query_contact, $connvbsa) or die(mysql_error());
$row_contact = mysql_fetch_assoc($contact);
$totalRows_contact = mysql_num_rows($contact);

mysql_select_db($database_connvbsa, $connvbsa);
$query_about = "Select item_title,  news_content, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date FROM " . $affiliate . " WHERE " . $affiliate . "." . $affiliate . "_id = 7";
$about = mysql_query($query_about, $connvbsa) or die(mysql_error());
$row_about = mysql_fetch_assoc($about);
$totalRows_about = mysql_num_rows($about);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Header = "Select pagezone_header_desc, pagezone_header, item_title, news_content FROM " . $affiliate . " WHERE " . $affiliate . "." . $affiliate . "_id=8";
$Header = mysql_query($query_Header, $connvbsa) or die(mysql_error());
$row_Header = mysql_fetch_assoc($Header);
$totalRows_Header = mysql_num_rows($Header);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
<link href="../../Admin_xx_CSS/Affiliate_db.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<table width="1000" align="center" class="greenbg">
  <tr>
    <td width="125" align="center"><a href="../vbsa_extra.php">Extra Home Page</a></td>
    <!--<td width="125" align="center"><a href="../Affiliate/Affiliate_index_admin.php?affiliate=<?= $affiliate ?>"><?= $affiliate ?> Index</a></td>-->
    <td width="125" align="center"><a href="Affiliate_xx_archives.php?affiliate=<?= $affiliate ?>">Archives</a></td>
    <td width="125" align="center">&nbsp;</td>
    <td width="125" align="center">&nbsp;</td>
    <td width="125" align="center">&nbsp;</td>
    <td width="125" align="center">&nbsp;</td>
    <td width="125" align="center"><a href="<?php echo $logoutAction ?>">Logout</a></td>
  </tr>
</table>
<table width="1108" align="center" cellpadding="5">
  <tr>
    <td width="887" align="left"><span class="red_bold">THIS AREA IS FOR <?= $affiliate ?> News, General Information, Scores and History administration&quot; </span></td>
    <td align="right" nowrap="nowrap">Last Insert / Update :    <?php $date = $row_maxdate['MAXDATE']; echo date("j M Y H:i", strtotime($date)); ?></td>
  </tr>
  <tr>
    <td align="center"><span class="page"><a href="../../<?= $affiliate ?>/<?= $affiliate ?>_index.php" target="_blank">Preview the web Page</a>, if a new entry is inserted or an item updated please refresh the page</span></td>
    <td align="right" class="greenbg"><a href="../affiliate_help/affiliate_help_2016.pdf">Help File</a></td>
  </tr>
</table>
<div class="affiliateDB_item">
  <table width="1100">
    <tr>
      <td width="250"><table width="250" align="left">
        <tr>
          <td width="258" class="red_bold">1. Edit the &quot;Zone&quot; headings</td>
        </tr>
        <tr>
          <td>Headings appear in the specified place on the web page. Leave blank and delete all items if a zone is not in use</td>
        </tr>
      </table></td>
      <td width="418"><table width="790">
        <?php do { ?>
        <tr>
          <td width="119"><?php echo $row_pageheader[$affiliate . '_id']; ?></td>
          <td width="216"><?php echo $row_pageheader['pagezone_header_desc']; ?></td>
          <td width="432"><?php echo $row_pageheader['pagezone_header']; ?></td>
          <td width="30"class="page"><a href="../Affiliate/Affiliate_pagehead_edit.php?affiliate=<?= $affiliate ?>&headedit=<?= $row_pageheader[$affiliate . '_id'] ?>" ><img src="../../Admin_Images/edit_butt.fw.png" width="20" height="20" title="Edit" /></a></td>
        </tr>
        <?php } while ($row_pageheader = mysql_fetch_assoc($pageheader)); ?>
      </table></td>
    </tr>
  </table>
</div>
<div class="affiliateDB_item">
  <table width="1100" align="center">
    <tr>
      <td width="526" align="left"><span class="red_bold">2. News Items currently displayed in the left hand column of the web page</span></td>
      <td width="526" align="left" class="greenbg"><a href="../Affiliate/Affiliate_insert_news.php?affiliate=<?= $affiliate ?>">insert a new item in top left NEWS column</a></td>
    </tr>
    <tr>
      <td colspan="2" align="left">If you want to keep an item but do not want it to appear on the site, edit and set &quot;Current&quot; to &quot;No&quot;. To discard an item completely please delete.</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
  <table width="1100">
    <tr>
      <td width="71">Type</td>
      <td width="198">News Heading</td>
      <td width="580">Content</td>
      <td width="126" align="center">Ordered ?</td>
      <td width="40">&nbsp;</td>
      <td width="40">&nbsp;</td>
    </tr>
    <?php do { ?>
    <tr>
      <td>News Item</td>
      <td><?php echo $row_News['item_title']; ?></td>
      <td><?php echo $row_News['news_content']; ?></td>
      <td align="center"><?php echo $row_News['list_order']; ?></td>
      <td width="40" align="center" class="page"><a href="../Affiliate/Affiliate_edit_news.php?affiliate=<?= $affiliate ?>&news=<?php echo $row_News[$affiliate . '_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" width="20" height="20"  title="Edit" /></a></td>
      <td width="40" align="center" class="page"><a href="../Affiliate/Affiliate_confirm_delete.php?affiliate=<?= $affiliate ?>&del=<?php echo $row_News[$affiliate . '_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" alt="" width="20" height="20" title="delete" /></a></td>
    </tr>
    <?php } while ($row_News = mysql_fetch_assoc($News)); ?>
  </table>
</div>

<table align="center" width="350">
  <tr>
    <td align="center" valign="middle"><img src="../../Admin_Images/glyphicon_view.jpg" height="18" /> = View</td>
    <td align="center" valign="middle"><img src="../../Admin_Images/glyphicon_download.jpg" height="20" /> = Download</td>
    <td align="center" valign="middle"><img src="../../Admin_Images/glyphicon_upload.jpg" height="20" /> = Upload</td>
  </tr>
</table>

<div class="affiliateDB_item">
  <table width="1103" align="center">
  <tr>
    <td align="left" class="red_bold">3. Items currently displayed in the &quot;
    <?php
		$query  = "Select pagezone_header FROM " . $affiliate . " WHERE " . $affiliate . "_id=1";
		$result = mysql_query($query);

		while($row = mysql_fetch_row($result))
			
    	$pagezone_header = $row[0];
			
		if ($pagezone_header<>'') echo "$pagezone_header"; elseif ($pagezone_header=='') echo "This zone not in use"; 
	?>
      &quot; section
    </td>
    <td align="right" class="greenbg"><a href="../Affiliate/Affiliate_insert_item.php?affiliate=<?= $affiliate ?>&item=<?php echo 'a_info'; ?>">insert a new information item</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left">If you want to keep an item but do not want it to appear on the site, edit and set &quot;Current&quot; to &quot;No&quot;. To discard an item completely please delete.</td>
    </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
<table width="1100" align="center">
<tr>
  	<td width="100" align="left">Type</td>
    <td width="280" align="left">Item Title</td>
    <td width="80" align="center">Ordered?</td>
    <td width="150" align="left">Inserted</td>
    <td width="150" align="left">Edited</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
</tr>
<?php do { ?>
    <tr>
      <td align="left"><?php if ($row_info[$affiliate . '_type'] =="a_info") echo "Information"; ?></td>
      <td align="left"><?php echo $row_info['item_title']; ?></td>
      <td align="center"><?php echo $row_info['list_order']; ?></td>
      <td align="left"><?php echo $row_info['uploaded_on_date']; ?></td>
      <td align="left"><?php echo $row_info['edited_on_date']; ?></td>
      
      <td align="center">
      		<?php // View pdf if it exists
			if(!empty($row_info['pdf_name'])) { ?>
      		<a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../../Affiliate/Affiliate_upload/<?php echo $row_info['pdf_name']; ?>" title="view"><img src="../../Admin_Images/glyphicon_view.jpg" height="18" /></a><?php } else echo '&nbsp;'; ?>
      </td>
      
      <td align="center" >
	  		<?php // Download pdf if it exists
			if(!empty($row_info['pdf_name'])) { ?>
      		<a href="../../Affiliate/Affiliate_upload/<?php echo $row_info['pdf_name']; ?>" target="_blank" title="download"><img src="../../Admin_Images/glyphicon_download.jpg" height="20" /></a>
      		<?php } else echo '&nbsp;'; ?>
      </td>
        
      <td align="center"><!-- Upload -->
      	<a href="../Affiliate/Affiliate_upload.php?affiliate=<?= $affiliate ?>&pdfup=<?php echo $row_info[$affiliate . '_id']; ?>" title="upload"><img src="../../Admin_Images/glyphicon_upload.jpg" height="20" /></a>
      </td>
      
      <td align="center"><!-- Edit -->
      	<a href="../Affiliate/Affiliate_edit.php?affiliate=<?= $affiliate ?>&zoned=<?php echo $row_info[$affiliate . '_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" height="20" title="Edit" /></a>
      </td>
      
      <td align="center"><!-- Delete -->
      <a href="../Affiliate/Affiliate_confirm_delete.php?affiliate=<?= $affiliate ?>&del=<?php echo $row_info[$affiliate . '_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" height="20" title="delete" /></a>
      </td>
    </tr>
    <?php } while ($row_info = mysql_fetch_assoc($info)); ?>
</table>
</div>

<!-- Zone Header -->
<div class="affiliateDB_item">
  <table width="1100" align="center">
  <tr>
    <td align="left" class="red_bold">4. Items currently displayed in  &quot;Zone 1, Zone 2, Zone 3, Zone 4, Zone 5 or History&quot; sections on the <?= $affiliate ?>  page  where &quot;Current =Yes&quot; </td>
  </tr>
</table>


<!-- Zone 1 -->
<table width="1100">
  <tr>
    <td align="left" class="red_bold">Zone 1</td>
    <td colspan="4" align="left">Current heading: 
      
      <?php $query  = "Select pagezone_header FROM " . $affiliate . " WHERE " . $affiliate . "_id=2";
			$result = mysql_query($query);

			while($row = mysql_fetch_row($result))
			
    		$pagezone_header = $row[0];
			
			if(!empty($pagezone_header)) echo "$pagezone_header"; else echo "This zone not in use"; ?>
    </td>
    <td colspan="5" align="right"><span class="greenbg"><a href="../Affiliate/Affiliate_insert_item.php?affiliate=<?= $affiliate ?>&item=<?php echo 'c_zone1'; ?>">insert a new item in Zone 1</a></span></td>
    </tr>
  <tr>
    <td width="100" align="left">Type</td>
    <td width="280" align="left">Item Title</td>
    <td width="80" align="center">Ordered?</td>
    <td width="150" align="left">Inserted</td>
    <td width="150" align="left">Edited</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="left"><?php if ($row_zone1[$affiliate . '_type'] =="c_zone1") echo "Zone 1";  ?></td>
      <td align="left"><?php echo $row_zone1['item_title']; ?></td>
      <td align="center"><?php echo $row_zone1['list_order']; ?></td>
      <td align="left"><?php echo $row_zone1['uploaded_on_date']; ?></td>
      <td align="left"><?php echo $row_zone1['edited_on_date']; ?></td>
      <td align="center">
      		<?php // View pdf if it exists
			if(!empty($row_zone1['pdf_name'])) { ?>
      		<a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../../Affiliate/Affiliate_upload/<?php echo $row_zone1['pdf_name']; ?>" title="view"><img src="../../Admin_Images/glyphicon_view.jpg" height="18" /></a><?php } else echo '&nbsp;'; ?>
      </td>
      
      <td align="center" >
	  		<?php // Download pdf if it exists
			if(!empty($row_zone1['pdf_name'])) { ?>
      		<a href="../../Affiliate/Affiliate_upload/<?php echo $row_zone1['pdf_name']; ?>" target="_blank" title="download"><img src="../../Admin_Images/glyphicon_download.jpg" height="20" /></a>
      		<?php } else echo '&nbsp;'; ?>
      </td>
        
      <td align="center"><!-- Upload -->
      	<a href="../Affiliate/Affiliate_upload.php?affiliate=<?= $affiliate ?>&pdfup=<?php echo $row_zone1[$affiliate . '_id']; ?>" title="upload"><img src="../../Admin_Images/glyphicon_upload.jpg" height="20" /></a>
      </td>
      
      <td align="center"><!-- Edit -->
      	<a href="../Affiliate/Affiliate_edit.php?affiliate=<?= $affiliate ?>&zoned=<?php echo $row_zone1[$affiliate . '_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" height="20" title="Edit" /></a>
      </td>
      
      <td align="center"><!-- Delete -->
      <a href="../Affiliate/Affiliate_confirm_delete.php?affiliate=<?= $affiliate ?>&del=<?php echo $row_zone1[$affiliate . '_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" height="20" title="delete" /></a>
      </td>
    </tr>
    <?php } while ($row_zone1 = mysql_fetch_assoc($zone1)); ?>
</table>
<hr>


<!-- Zone 2 -->
<table width="1100">
  <tr>
    <td align="left"><span class="red_bold">Zone 2</span></td>
 <td colspan="4" align="left">Current heading: 
      
      <?php $query  = "Select pagezone_header FROM " . $affiliate . " WHERE " . $affiliate . "_id=3";
			$result = mysql_query($query);

			while($row = mysql_fetch_row($result))
			
    		$pagezone_header = $row[0];
			
			if(!empty($pagezone_header)) echo "$pagezone_header"; else echo "This zone not in use"; ?>
    </td>
    <td colspan="5" align="right"><span class="greenbg"><a href="../Affiliate/Affiliate_insert_item.php?affiliate=<?= $affiliate ?>&item=<?php echo 'd_zone2'; ?>">insert a new item in Zone 2</a></span></td>
    </tr>
  <tr>
    <td width="100" align="left">Type</td>
    <td width="280" align="left">Item Title</td>
    <td width="80" align="center">Ordered?</td>
    <td width="150" align="left">Inserted</td>
    <td width="150" align="left">Edited</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td width="60" align="left"><?php if ($row_zone2[$affiliate . '_type'] =="d_zone2") echo "Zone 2"; ?></td>
      <td align="left"><?php echo $row_zone2['item_title']; ?></td>
      <td align="center"><?php echo $row_zone2['list_order']; ?></td>
      <td><?php echo $row_zone2['uploaded_on_date']; ?></td>
      <td><?php echo $row_zone2['edited_on_date']; ?></td>
      <td align="center">
      		<?php // View pdf if it exists
			if(!empty($row_zone2['pdf_name'])) { ?>
      		<a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../../Affiliate/Affiliate_upload/<?php echo $row_zone2['pdf_name']; ?>" title="view"><img src="../../Admin_Images/glyphicon_view.jpg" height="18" /></a><?php } else echo '&nbsp;'; ?>
      </td>
      
      <td align="center" >
	  		<?php // Download pdf if it exists
			if(!empty($row_zone2['pdf_name'])) { ?>
      		<a href="../../Affiliate/Affiliate_upload/<?php echo $row_zone2['pdf_name']; ?>" target="_blank" title="download"><img src="../../Admin_Images/glyphicon_download.jpg" height="20" /></a>
      		<?php } else echo '&nbsp;'; ?>
      </td>
        
      <td align="center"><!-- Upload -->
      	<a href="../Affiliate/Affiliate_upload.php?affiliate=<?= $affiliate ?>&pdfup=<?php echo $row_zone2[$affiliate . '_id']; ?>" title="upload"><img src="../../Admin_Images/glyphicon_upload.jpg" height="20" /></a>
      </td>
      
      <td align="center"><!-- Edit -->
      	<a href="../Affiliate/Affiliate_edit.php?affiliate=<?= $affiliate ?>&zoned=<?php echo $row_zone2[$affiliate . '_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" height="20" title="Edit" /></a>
      </td>
      
      <td align="center"><!-- Delete -->
      <a href="../Affiliate/Affiliate_confirm_delete.php?affiliate=<?= $affiliate ?>&del=<?php echo $row_zone2[$affiliate . '_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" height="20" title="delete" /></a>
      </td>
    </tr>
    <?php } while ($row_zone2 = mysql_fetch_assoc($zone2)); ?>
</table>
<hr>

<!-- Zone 3 -->
<table width="1100">
  <tr>
    <td align="left"><span class="red_bold">Zone 3</span></td>
<td colspan="4" align="left">Current heading: 
      
      <?php $query  = "Select pagezone_header FROM " . $affiliate . " WHERE " . $affiliate . "_id=4";
			$result = mysql_query($query);

			while($row = mysql_fetch_row($result))
			
    		$pagezone_header = $row[0];
			
			if(!empty($pagezone_header)) echo "$pagezone_header"; else echo "This zone not in use"; ?>
</td>
    <td colspan="5" align="right" class="greenbg"><span class="greenbg"><a href="../Affiliate/Affiliate_insert_item.php?affiliate=<?= $affiliate ?>&item=<?php echo 'e_zone3'; ?>">insert a new item in Zone 3</a></span></td>
    </tr>
  <tr>
    <td width="100" align="left">Type</td>
    <td width="280" align="left">Item Title</td>
    <td width="80" align="center">Ordered?</td>
    <td width="150" align="left">Inserted</td>
    <td width="150" align="left">Edited</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td width="60" align="left"><?php if ($row_zone3[$affiliate . '_type'] =="e_zone3") echo "Zone 3"; ?></td>
      <td align="left"><?php echo $row_zone3['item_title']; ?></td>
      <td align="center"><?php echo $row_zone3['list_order']; ?></td>
      <td align="left"><?php echo $row_zone3['uploaded_on_date']; ?></td>
      <td class="page"><?php echo $row_zone3['edited_on_date']; ?></td>
      <td align="center">
      		<?php // View pdf if it exists
			if(!empty($row_zone3['pdf_name'])) { ?>
      		<a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../../Affiliate/Affiliate_upload/<?php echo $row_zone3['pdf_name']; ?>" title="view"><img src="../../Admin_Images/glyphicon_view.jpg" height="18" /></a><?php } else echo '&nbsp;'; ?>
      </td>
      
      <td align="center" >
	  		<?php // Download pdf if it exists
			if(!empty($row_zone3['pdf_name'])) { ?>
      		<a href="../../Affiliate/Affiliate_upload/<?php echo $row_zone3['pdf_name']; ?>" target="_blank" title="download"><img src="../../Admin_Images/glyphicon_download.jpg" height="20" /></a>
      		<?php } else echo '&nbsp;'; ?>
      </td>
        
      <td align="center"><!-- Upload -->
      	<a href="../Affiliate/Affiliate_upload.php?affiliate=<?= $affiliate ?>&pdfup=<?php echo $row_zone3[$affiliate . '_id']; ?>" title="upload"><img src="../../Admin_Images/glyphicon_upload.jpg" height="20" /></a>
      </td>
      
      <td align="center"><!-- Edit -->
      	<a href="../Affiliate/Affiliate_edit.php?affiliate=<?= $affiliate ?>&zoned=<?php echo $row_zone3[$affiliate . '_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" height="20" title="Edit" /></a>
      </td>
      
      <td align="center"><!-- Delete -->
      <a href="../Affiliate/Affiliate_confirm_delete.php?affiliate=<?= $affiliate ?>&del=<?php echo $row_zone3[$affiliate . '_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" height="20" title="delete" /></a>
      </td>
    </tr>
    <?php } while ($row_zone3 = mysql_fetch_assoc($zone3)); ?>
</table>
<hr>

<!-- Zone 4 -->
<table width="1100">
  <tr>
    <td align="left"><span class="red_bold">Zone 4</span></td>
<td colspan="4" align="left">Current heading: 
      
      <?php $query  = "Select pagezone_header FROM " . $affiliate . " WHERE " . $affiliate . "_id=5";
			$result = mysql_query($query);

			while($row = mysql_fetch_row($result))
			
    		$pagezone_header = $row[0];
			
			if(!empty($pagezone_header)) echo "$pagezone_header"; else echo "This zone not in use"; ?>
</td>
    <td colspan="5" align="right"><span class="greenbg"><a href="../Affiliate/Affiliate_insert_item.php?affiliate=<?= $affiliate ?>&item=<?php echo 'f_zone4'; ?>">insert a new item in Zone 4</a></span></td>
    </tr>
  <tr>
    <td width="100" align="left">Type</td>
    <td width="280" align="left">Item Title</td>
    <td width="80" align="center">Ordered?</td>
    <td width="150" align="left">Inserted</td>
    <td width="150" align="left">Edited</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="left"><?php if ($row_zone4[$affiliate . '_type'] =="f_zone4") echo "Zone 4"; ?></td>
      <td align="left"><?php echo $row_zone4['item_title']; ?></td>
      <td align="center"></td>
      <td><?php echo $row_zone4['uploaded_on_date']; ?></td>
      <td><?php echo $row_zone4['edited_on_date']; ?></td>
      <td align="center">
      		<?php // View pdf if it exists
			if(!empty($row_zone4['pdf_name'])) { ?>
      		<a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../../Affiliate/Affiliate_upload/<?php echo $row_zone4['pdf_name']; ?>" title="view"><img src="../../Admin_Images/glyphicon_view.jpg" height="18" /></a><?php } else echo '&nbsp;'; ?>
      </td>
      
      <td align="center" >
	  		<?php // Download pdf if it exists
			if(!empty($row_zone4['pdf_name'])) { ?>
      		<a href="../../Affiliate/Affiliate_upload/<?php echo $row_zone4['pdf_name']; ?>" target="_blank" title="download"><img src="../../Admin_Images/glyphicon_download.jpg" height="20" /></a>
      		<?php } else echo '&nbsp;'; ?>
      </td>
        
      <td align="center"><!-- Upload -->
      	<a href="../Affiliate/Affiliate_upload.php?affiliate=<?= $affiliate ?>&pdfup=<?php echo $row_zone4[$affiliate . '_id']; ?>" title="upload"><img src="../../Admin_Images/glyphicon_upload.jpg" height="20" /></a>
      </td>
      
      <td align="center"><!-- Edit -->
      	<a href="../Affiliate/Affiliate_edit.php?affiliate=<?= $affiliate ?>&zoned=<?php echo $row_zone4[$affiliate . '_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" height="20" title="Edit" /></a>
      </td>
      
      <td align="center"><!-- Delete -->
      <a href="../Affiliate/Affiliate_confirm_delete.php?affiliate=<?= $affiliate ?>&del=<?php echo $row_zone4[$affiliate . '_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" height="20" title="delete" /></a>
      </td>
    </tr>
    <?php } while ($row_zone4 = mysql_fetch_assoc($zone4)); ?>
</table>
<hr>

<!-- Zone 5 -->
<table width="1100">
  <tr>
    <td align="left"><span class="red_bold">Zone 5</span></td>
<td colspan="4" align="left">Current heading: 
      
      <?php $query  = "Select pagezone_header FROM " . $affiliate . " WHERE " . $affiliate . "_id=6";
			$result = mysql_query($query);

			while($row = mysql_fetch_row($result))
			
    		$pagezone_header = $row[0];
			
			if(!empty($pagezone_header)) echo "$pagezone_header"; else echo "This zone not in use"; ?>
</td>
    <td colspan="5" align="right"><span class="greenbg"><a href="../Affiliate/Affiliate_insert_item.php?affiliate=<?= $affiliate ?>&item=<?php echo 'g_zone5'; ?>">insert a new item in Zone 5</a></span></td>
    </tr>
  <tr>
    <td width="100" align="left">Type</td>
    <td width="280" align="left">Item Title</td>
    <td width="80" align="center">Ordered?</td>
    <td width="150" align="left">Inserted</td>
    <td width="150" align="left">Edited</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="left"><?php if ($row_zone5[$affiliate . '_type'] =="g_zone5") echo "Zone 5";  ?></td>
      <td align="left"><?php echo $row_zone5['item_title']; ?></td>
      <td align="center"><?php echo $row_zone5['list_order']; ?></td>
      <td><?php echo $row_zone5['uploaded_on_date']; ?></td>
      <td><?php echo $row_zone5['edited_on_date']; ?></td>
      <td align="center">
      		<?php // View pdf if it exists
			if(!empty($row_zone5['pdf_name'])) { ?>
      		<a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../../Affiliate/Affiliate_upload/<?php echo $row_zone5['pdf_name']; ?>" title="view"><img src="../../Admin_Images/glyphicon_view.jpg" height="18" /></a><?php } else echo '&nbsp;'; ?>
      </td>
      
      <td align="center" >
	  		<?php // Download pdf if it exists
			if(!empty($row_zone5['pdf_name'])) { ?>
      		<a href="../../Affiliate/Affiliate_upload/<?php echo $row_zone5['pdf_name']; ?>" target="_blank" title="download"><img src="../../Admin_Images/glyphicon_download.jpg" height="20" /></a>
      		<?php } else echo '&nbsp;'; ?>
      </td>
        
      <td align="center"><!-- Upload -->
      	<a href="../Affiliate/Affiliate_upload.php?affiliate=<?= $affiliate ?>&pdfup=<?php echo $row_zone5[$affiliate . '_id']; ?>" title="upload"><img src="../../Admin_Images/glyphicon_upload.jpg" height="20" /></a>
      </td>
      
      <td align="center"><!-- Edit -->
      	<a href="../Affiliate/Affiliate_edit.php?affiliate=<?= $affiliate ?>&zoned=<?php echo $row_zone5[$affiliate . '_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" height="20" title="Edit" /></a>
      </td>
      
      <td align="center"><!-- Delete -->
      <a href="../Affiliate/Affiliate_confirm_delete.php?affiliate=<?= $affiliate ?>&del=<?php echo $row_zone5[$affiliate . '_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" height="20" title="delete" /></a>
      </td>
    </tr>
    <?php } while ($row_zone5 = mysql_fetch_assoc($zone5)); ?>
</table>
<hr>

<!-- History -->
<table width="1100">
  <tr>
    <td colspan="11" align="left">Please Note: History items will be sorted by the title so start your listing with the year and they will sort with the most recent first</td>
    </tr>
  <tr>
    <td align="left"><span class="red_bold">History</span></td>
    <td align="left">&nbsp;</td>
    <td colspan="2" align="left" class="greenbg">
    <td align="left">&nbsp;</td>
    <td colspan="5" align="right"><span class="greenbg"><a href="../Affiliate/Affiliate_insert_item.php?affiliate=<?= $affiliate ?>&item=<?php echo 'f_history'; ?>">insert a new item in History</a></span></td>
    </tr>
  <tr>
    <td width="100" align="left">Type</td>
    <td width="280" align="left">Item Title</td>
    <td width="80" align="center">Ordered?</td>
    <td width="150" align="left">Inserted</td>
    <td width="150" align="left">Edited</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="50" align="center">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="left"><?php if ($row_hist[$affiliate . '_type'] =="f_history") echo "History"; ?></td>
      <td align="left"><?php echo $row_hist['item_title']; ?></td>
      <td align="center"><?php echo $row_hist['list_order']; ?></td>
      <td><?php echo $row_hist['uploaded_on_date']; ?></td>
      <td><?php echo $row_hist['edited_on_date']; ?></td>
      <td align="center">
      		<?php // View pdf if it exists
			if(!empty($row_hist['pdf_name'])) { ?>
   		  <a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../../Affiliate/Affiliate_upload/<?php echo $row_hist['pdf_name']; ?>" title="view"><img src="../../Admin_Images/glyphicon_view.jpg" height="18" /></a><?php } else echo '&nbsp;'; ?>
      </td>
      
      <td align="center" >
	  		<?php // Download pdf if it exists
			if(!empty($row_hist['pdf_name'])) { ?>
   		  <a href="../../Affiliate/Affiliate_upload/<?php echo $row_hist['pdf_name']; ?>" target="_blank" title="download"><img src="../../Admin_Images/glyphicon_download.jpg" height="20" /></a>
      		<?php } else echo '&nbsp;'; ?>
      </td>
        
      <td align="center"><!-- Upload -->
      	<a href="../Affiliate/Affiliate_upload.php?affiliate=<?= $affiliate ?>&pdfup=<?php echo $row_hist[$affiliate . '_id']; ?>" title="upload"><img src="../../Admin_Images/glyphicon_upload.jpg" height="20" /></a>
      </td>
      
      <td align="center"><!-- Edit -->
      	<a href="../Affiliate/Affiliate_edit.php?affiliate=<?= $affiliate ?>&zoned=<?php echo $row_hist[$affiliate . '_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" height="20" title="Edit" /></a>
      </td>
      
      <td align="center"><!-- Delete -->
      <a href="../Affiliate/Affiliate_confirm_delete.php?affiliate=<?= $affiliate ?>&del=<?php echo $row_hist[$affiliate . '_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" height="20" title="delete" /></a>
      </td>
    </tr>
    <?php } while ($row_hist = mysql_fetch_assoc($hist)); ?>
</table>
</div>

<div class="affiliateDB_item">
  <table width="1100" align="center">
    <tr>
      <td align="left"><span class="red_bold">6. Board members and contact details on the <?= $affiliate ?>  contact us page</span></td>
      <td align="right" nowrap="nowrap" class="greenbg"><a href="../Affiliate/Affiliate_contact_insert.php?affiliate=<?= $affiliate ?>">Insert a new Contact</a></td>
    </tr>
    <tr>
      <td colspan="3" align="left">Board members are listed alphabetically by their first name. To appear in a different order - edit and set the &quot;Order&quot; to your preference</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
  </table>


  <table width="1100" align="center">
    <tr>
      <td>ID</td>
      <td>Name</td>
      <td>Position</td>
      <td>Order</td>
      <td>Phone</td>
      <td>Email</td>
      <td align="center">Visible?</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_contact['contact_id']; ?></td>
        <td><?php echo $row_contact['contact_name']; ?></td>
        <td class="page"><?php echo $row_contact['contact_position']; ?></td>
        <td><?php echo $row_contact['contact_order']; ?></td>
        <td class="page"><a href="tel:<?php echo $row_contact['contact_phone']; ?>"><?php echo $row_contact['contact_phone']; ?></a></td>
        <td><span class="page"><a href="mailto:<?php echo $row_contact['contact_email']; ?>"><?php echo $row_contact['contact_email']; ?></a></span></td>
        <td align="center"><?php if($row_contact['contact_current']==1) echo "Yes"; else echo "No"; ?></td>
        <td><a href="../Affiliate/Affiliate_contact_edit.php?affiliate=<?= $affiliate ?>&cont_edit=<?php echo $row_contact['contact_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" width="24" /></a></td>
        <td><a href="../Affiliate/Affiliate_confirm_contact_delete.php?affiliate=<?= $affiliate ?>&cont_del=<?php echo $row_contact['contact_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" width="24" title="Delete this contact" /></a></td>
      </tr>
      <?php } while ($row_contact = mysql_fetch_assoc($contact)); ?>
  </table>
</div>

<div class="affiliateDB_item">
  <table width="1100" align="center">
    <tr>
      <td colspan="2" align="left"><span class="red_bold">7. About <?= $affiliate ?> - appears below Board members on the Contact page</span> Last edited: <?php echo $row_about['edited_on_date']; ?> (leave both fiels blank if you do not want to appear)</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="99" align="right">Title:</td>
      <td width="935" align="left"><?php echo $row_about['item_title']; ?></td>
      <td width="50">&nbsp;</td>
    </tr>

      <tr>
        <td align="right">About:</td>
        <td align="left"><?php echo $row_about['news_content']; ?></td>
        <td align="center" class="page"><a href="../Affiliate/Affiliate_edit_about.php?affiliate=<?= $affiliate ?>"><img src="../../Admin_Images/edit_butt.fw.png" width="24" /></a></td>
      </tr>
  </table>
</div>

<div class="affiliateDB_item">
  <table width="1100" align="center">
    <tr>
      <td colspan="2" align="left" valign="middle"><span class="red_bold">8. <?= $affiliate ?> - Page Header (font sizes are actual size)</span></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="middle">Title (Large Page):</td>
      <td  align="left" valign="middle"><h1><?php echo $row_Header['item_title']; ?></h1></td>
      <td width="50"><span class="page"><a href="../Affiliate/Affiliate_edit_header.php?affiliate=<?= $affiliate ?>"><img src="../../Admin_Images/edit_butt.fw.png" width="24" /></a></span></td>
    </tr>

      <tr>
        <td align="right" valign="middle">Subtitle (Large Page):</td>
        <td align="left" valign="middle"><H4><em><?php echo $row_Header['news_content']; ?></em></H4></td>
        <td align="center" class="page">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" valign="middle">Title (Small Page):</td>
        <td align="left" valign="middle"><H1><?php echo $row_Header['pagezone_header']; ?></H1></td>
        <td align="center" class="page">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" valign="middle">Title Explanation (Small Page)</td>
        <td align="left" valign="middle"><H6><?php echo $row_Header['pagezone_header_desc']; ?></H6></td>
        <td align="center" class="page">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" valign="middle">Subtitle (Small Page):</td>
        <td align="left" valign="middle"><H5><em><?php echo $row_Header['news_content']; ?></em> (Same as Subtitle (Large Page))</H5></td>
        <td align="center" class="page">&nbsp;</td>
      </tr>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($pageheader);

mysql_free_result($info);

mysql_free_result($News);

mysql_free_result($maxdate);

mysql_free_result($zone1);

mysql_free_result($zone2);

mysql_free_result($zone3);

mysql_free_result($hist);

mysql_free_result($contact);

mysql_free_result($about);

mysql_free_result($Header);

mysql_free_result($zone4);

mysql_free_result($zone5);
?>