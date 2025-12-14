<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster";
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

$MM_restrictGoTo = "forum_access_denied.php";
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
$query_Current = "SELECT date_format(post_date,'%b %e, %Y, %r') AS PostOn, post_ID, post_category, post_topic, post_date, post_current, Blocked, admin_comment FROM forum_posts WHERE forum_posts.post_current='Current' ORDER BY post_date DESC";
$Current = mysql_query($query_Current, $connvbsa) or die(mysql_error());
$row_Current = mysql_fetch_assoc($Current);
$totalRows_Current = mysql_num_rows($Current);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Archived = "SELECT date_format(post_date,'%b %e, %Y, %r') AS PostOn, post_ID, post_category, post_topic, post_date, post_current, Blocked, admin_comment FROM forum_posts WHERE forum_posts.post_current='Archived' AND Blocked='No' ORDER BY post_date DESC";
$Archived = mysql_query($query_Archived, $connvbsa) or die(mysql_error());
$row_Archived = mysql_fetch_assoc($Archived);
$totalRows_Archived = mysql_num_rows($Archived);

mysql_select_db($database_connvbsa, $connvbsa);
$query_blocked = "SELECT date_format(post_date,'%b %e, %Y, %r') AS PostOn, post_ID, post_category, post_topic, post_date, post_current, Blocked, admin_comment FROM forum_posts WHERE forum_posts.Blocked='Yes' ORDER BY post_date DESC";
$blocked = mysql_query($query_blocked, $connvbsa) or die(mysql_error());
$row_blocked = mysql_fetch_assoc($blocked);
$totalRows_blocked = mysql_num_rows($blocked);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/forum_db.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/forum_db_links.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="forum_header">
<div id="logo"><img src="../images/VBSA1.jpg" alt="" width="90" height="87" /></div>

<table width="870" align="right">
  <tr>

    <td class="red_bold">VBSA Administrators Forum</td>
    <td align="right" class="bluebg"><a href="How%20to%20use%20the%20VBSA%20Board%20Forum.pdf" target="_blank">How to use the Forum</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="red_bold">Administrator Home Page - Lists Current, Archived and Blocked posts</td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

  <div id="ContentDB">
    
    <table width="1000" align="center" cellpadding="3">
      <tr>
        <td colspan="10" class="red_bold">Current Topics</td>
        </tr>
      <tr>
        <th align="center"><strong>ID</strong></th>
        <th align="left">Topic</th>
        <th align="left">Post On</th>
        <th align="center">Status</th>
        <th align="center">Blocked</th>
        <th align="center">Topic Type</th>
        <th align="center">&nbsp;</th>
        <th align="center">&nbsp;</th>
        <th align="center">&nbsp;</th>
        </tr>
      <?php do { ?>
        <tr>
          <td align="center"><strong><?php echo $row_Current['post_ID']; ?></strong></td>
          <td align="left"><?php echo $row_Current['post_topic']; ?></td>
          <td align="left"><?php echo $row_Current['PostOn']; ?></td>
          <td align="center"><?php echo $row_Current['post_current']; ?></td>
          <td align="center"><?php echo $row_Current['Blocked']; ?></td>
          <td align="center" class="page"><?php echo $row_Current['post_category']; ?></td>
          <td align="center" class="page"><a href="forum_administrator_detail.php?admindet=<?php echo $row_Current['post_ID']; ?>"> <img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" title="Detail" /></a></td>
          <td align="center" class="page"><a href="forum_administrator_edit.php?postID=<?php echo $row_Current['post_ID']; ?>"> <img src="../Admin_Images/edit_butt.png" alt="" width="20" height="20" title="Administrator Edit" /></a></td>
          <td align="center" class="page">
            <?php 
        if($row_Current['admin_comment']=='')
		{
		echo '';
		}
        elseif($row_Current['admin_comment']<>'')
		{
		?>
            <span>
              <img src="../Admin_Images/red_flag.fw.png" width="24" height="24" title="Administrators comment" />
              </span> 
            <?php
		}
        ?>
            </td>
          </tr>
          <tr>
        <td>&nbsp;</td>
        <td colspan="8" align="left" class="text_italic">Admin Comment: <?php echo $row_Current['admin_comment']; ?></td>
        </tr>
        <?php } while ($row_Current = mysql_fetch_assoc($Current)); ?>
      </table>
    <p>&nbsp;</p>
    <table width="1000" align="center" cellpadding="3">
      <tr>
        <td colspan="9" class="red_bold">Archived Topics</td>
        </tr>
      <tr>
        <th align="center">ID</th>
        <th align="left">Topic</th>
        <th align="left">Post On</th>
        <th align="center">Status</th>
        <th align="center">Blocked</th>
        <th align="center">Topic Type</th>
        <th align="center">&nbsp;</th>
        <th align="center">&nbsp;</th>
        </tr>
      <?php do { ?>
        <tr>
          <td align="center"><strong><?php echo $row_Archived['post_ID']; ?></strong></td>
          <td align="left"><?php echo $row_Archived['post_topic']; ?></td>
          <td align="left"><?php echo $row_Archived['PostOn']; ?></td>
          <td align="center"><?php echo $row_Archived['post_current']; ?></td>
          <td align="center"><?php echo $row_Archived['Blocked']; ?></td>
          <td align="center" class="page"><?php echo $row_Archived['post_category']; ?></td>
          <td align="center" class="page"><a href="forum_administrator_detail.php?admindet=<?php echo $row_Archived['post_ID']; ?>">
            <img src="../Admin_Images/detail.fw.png" width="20" height="20" title="Detail" /></a></td>
          <td align="center" class="page"><a href="forum_administrator_edit.php?postID=<?php echo $row_Archived['post_ID']; ?>">
            <img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Administrator Edit" /></a></td>
      </tr>
          <tr>
        <td>&nbsp;</td>
        <td colspan="7" align="left" class="text_italic">Admin Comment: <?php echo $row_Archived['admin_comment']; ?></td>
        </tr>
        <?php } while ($row_Archived = mysql_fetch_assoc($Archived)); ?>
      </table>
    <p>&nbsp;</p>
    <table width="1000" align="center">
      <tr>
        <td colspan="8" class="red_bold">Blocked Topics</td>
        </tr>
      <tr>
        <th align="center">ID</th>
        <th align="left">Topic</th>
        <th align="left">Post On</th>
        <th align="center">Status</th>
        <th align="center">Blocked</th>
        <th>Topic Type</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        </tr>
  <?php do { ?>
      
      <tr>
        <td align="center"><strong><?php echo $row_blocked['post_ID']; ?></strong></td>
        <td align="left"><?php echo $row_blocked['post_topic']; ?></td>
        <td align="left"><?php echo $row_blocked['PostOn']; ?></td>
        <td align="center"><?php echo $row_blocked['post_current']; ?></td>
        <td align="center"><?php echo $row_blocked['Blocked']; ?></td>
        <td align="center" class="page"><?php echo $row_blocked['post_category']; ?></td>
        <td align="center" class="page"><a href="forum_administrator_detail.php?admindet=<?php echo $row_blocked['post_ID']; ?>"> <img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" title="Detail" /></a></td>
        <td align="center" class="page"><a href="forum_administrator_edit.php?postID=<?php echo $row_blocked['post_ID']; ?>"> <img src="../Admin_Images/edit_butt.png" alt="" width="20" height="20" title="Administrator Edit" /></a></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="7" align="left" class="text_italic">Admin Comment: <?php echo $row_blocked['admin_comment']; ?></td>
        </tr>      
    <?php } while ($row_blocked = mysql_fetch_assoc($blocked)); ?>
</table>
</div>

</body>
</html>
<?php
mysql_free_result($Current);

mysql_free_result($Archived);

mysql_free_result($blocked);
?>
