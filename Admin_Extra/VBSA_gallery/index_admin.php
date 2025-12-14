<?php require_once('../../Connections/connvbsa.php'); ?>
<?php //include('../php_function.php'); ?>
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
$MM_authorizedUsers = "Webmaster,VBSA";
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
    $arrUserGroups = Explode(",", $UserGroup); 
    for($i = 0; $i < count($arrGroups); $i++)
    {
      // Or, you may restrict access to only certain users based on their username. 
      if (in_array($arrUserGroups[$i], $arrGroups)) { 
        $isValid = true; 
      } 
    }
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}


$MM_restrictGoTo = "../Access_Denied.php";
if (!((isset($_SESSION['MM_Username'])) )) {   
//if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
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
$query_photo_year = "SELECT year_photo FROM gallery_team_photos WHERE gallery_team_photos.`current` ='Yes' GROUP BY year_photo ORDER BY gallery_team_photos.year_photo DESC";
$photo_year = mysql_query($query_photo_year, $connvbsa) or die(mysql_error());
$row_photo_year = mysql_fetch_assoc($photo_year);
$totalRows_photo_year = mysql_num_rows($photo_year);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Intro = "SELECT id, club_photo FROM gallery_team_photos WHERE  gallery_team_photos.id=1";
$Intro = mysql_query($query_Intro, $connvbsa) or die(mysql_error());
$row_Intro = mysql_fetch_assoc($Intro);
$totalRows_Intro = mysql_num_rows($Intro);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/gallery_team_photos.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<table width="1000" align="center" class="greenbg">
  <tr>
    <td align="center"><a href="../vbsa_extra.php">Extra Home Page</a></td>
    <td align="center"><a href="index_admin.php">Team Photo Gallery index</a></td>
    <td align="center"><a href="photo_insert.php">Insert / upload a new photo</a></td>
    <td align="center"><a href="index_admin_archives.php">Archives</a></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center"><a href="<?php echo $logoutAction ?>">Logout</a></td>
  </tr>
</table>
<div id="gallery_content">
  <table width="900" align="center" cellpadding="5">
    <tr>
      <td width="771" align="left"><span class="red_bold">THIS AREA is for managing the VBSA team photo Gallery </span></td>
      <td colspan="2" align="right" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><span class="page"><a href="../../galleries/VBSA_team_photo_index.php" target="_blank">Preview the web Page</a>, if a new entry is inserted or an item updated please refresh the page</span></td>
      <td width="156" align="right">Help File</td>
      <td width="33" align="right"><a href="Gallery_help/VBSA_Team_Photo_Gallery_help.pdf"><img src="../../images/Help.jpg" alt="Help" width="30" height="30" align="right" title="pdf help file" /></a></td>
    </tr>
  </table>
  
  <div id="gallery_intro">
  <?php do { ?>
    <div class="info"><span class="page"><?php echo $row_Intro['club_photo']; ?></span><div class="edit"><a href="index_admin_intro_edit.php?intro=<?php echo $row_Intro['id']; ?>"><img src="../../Admin_Images/edit_butt.fw.png" width="20" height="20" /></a></div></div>
    <?php } while ($row_Intro = mysql_fetch_assoc($Intro)); ?> 
  </div>
  <div id="gallery_container">
    
    <?php do { ?>
      <div class="gallery_index">Photos taken in: <?php echo $row_photo_year['year_photo']; ?> 
        <span class="greenbg"><a href="index_admin_detail.php?year=<?php echo $row_photo_year['year_photo']; ?>">View all</a></span>
      </div>
      <?php } while ($row_photo_year = mysql_fetch_assoc($photo_year)); ?>
  </table>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($photo_year);

mysql_free_result($Intro);
?>
