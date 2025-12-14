<?php require_once('../../Connections/connvbsa.php'); ?>
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

$MM_restrictGoTo = "../../page_error_extra.php";
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
$query_BBSAarchive = "SELECT date_format(uploaded_on,'%D %M %Y %H:%i') AS uploaded_on_date, date_format(edited_on,'%D %M %Y %H:%i') AS edited_on_date,  BBSA_id, BBSA_type, item_title, pdf_name, BBSA.`current`, uploaded_on, BBSA.list_order, edited_on, BBSA.pagezone_header FROM BBSA WHERE current=0 AND BBSA_id>8";
$BBSAarchive = mysql_query($query_BBSAarchive, $connvbsa) or die(mysql_error());
$row_BBSAarchive = mysql_fetch_assoc($BBSAarchive);
$totalRows_BBSAarchive = mysql_num_rows($BBSAarchive);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
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
<p>&nbsp;</p>


<table width="902" align="center">
    <tr valign="baseline">
      <td align="right" class="page">&nbsp;</td>
      <td align="right" nowrap="nowrap"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><span class="page"><span class="red_bold">Archives</span></span></td>
      <td>All items that have the &quot;Visible&quot; checkbox not checked - these items will not appear on the site</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">:</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="1000" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <th align="left">Type</th>
      <th align="center" nowrap="nowrap">Item ID</th>
      <th align="left">Item Title</th>
      <th align="center">Ordered?</th>
      <th align="left">Uploaded on</th>
      <th align="left">Edited on</th>
      <th align="center">&nbsp;</th>
      <th align="center">&nbsp;</th>
      <th align="center">&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
    <?php do { ?>
    <tr>
      <td align="left"><?php if($row_BBSAarchive['BBSA_type']=='b_news') echo "News Item"; elseif($row_BBSAarchive['BBSA_type']=='a_info') echo "Info Item"; else echo "Zone item";?></td>
      <td align="center"><?php echo $row_BBSAarchive['BBSA_id']; ?></td>
      <td align="left"><?php echo $row_BBSAarchive['item_title']; ?></td>
      <td align="center"><?php echo $row_BBSAarchive['list_order']; ?></td>
      <td align="left"><?php echo $row_BBSAarchive['uploaded_on_date']; ?></td>
      <td align="left"><?php echo $row_BBSAarchive['edited_on_date']; ?></td>
      <td align="center"><?php // View pdf if it exists
			if(!empty($row_BBSAarchive['pdf_name'])) { ?>
        <a href="http://www.vbsa.org.au//ViewerJS/?zoom=page-width#../../BBSA/BBSA_upload/<?php echo $row_BBSAarchive['pdf_name']; ?>" title="view"><img src="../../Admin_Images/glyphicon_view.jpg" alt="4" height="18" /></a>
        <?php } else echo '&nbsp;'; ?></td>
      <td align="center" ><?php // Download pdf if it exists
			if(!empty($row_BBSAarchive['pdf_name'])) { ?>
        <a href="../../BBSA/BBSA_upload/<?php echo $row_BBSAarchive['pdf_name']; ?>" target="_blank" title="download"><img src="../../Admin_Images/glyphicon_download.jpg" alt="2" height="20" /></a>
        <?php } else echo '&nbsp;'; ?></td>
      <td align="center"><!-- Upload -->
        <a href="../BBSA/BBSA_upload.php?pdfup=<?php echo $row_BBSAarchive['BBSA_id']; ?>" title="upload"><img src="../../Admin_Images/glyphicon_upload.jpg" alt="3" height="20" /></a></td>
      <td align="center"><!-- Edit -->
      
        <?php // select the edit page that suits the item
		 if($row_BBSAarchive['BBSA_type']=='b_news') {?>
        <a href="../BBSA/BBSA_edit_news.php?news=<?php echo $row_BBSAarchive['BBSA_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" width="20" height="20"  title="Edit" /></a>
        <?php }
		else {
		?>
        <a href="../BBSA/BBSA_edit.php?zoned=<?php echo $row_BBSAarchive['BBSA_id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" width="20" height="20"  title="Edit" /></a>
      <?php } ?></td>
      <td align="center"><!-- Delete -->
        <a href="../BBSA/BBSA_confirm_delete.php?del=<?php echo $row_BBSAarchive['BBSA_id']; ?>"><img src="../../Admin_Images/Trash.fw.png" alt="5" height="20" title="delete" /></a></td>
    </tr>
    <?php } while ($row_BBSAarchive = mysql_fetch_assoc($BBSAarchive)); ?>
  </table>
</body>
</html>
<?php
mysql_free_result($BBSAarchive);
?>
