<?php require_once('../Connections/connvbsa.php'); 
?>
<?php require_once("../webassist/ckeditor/ckeditor.php"); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO forum_posts (post_ID, post_order, post_category, post_topic, post_content, post_by, post_date, post_edit_on, post_edit_by, post_current, Blocked, last_post, admin_comment) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, now(), %s)",
                       GetSQLValueString($_POST['post_ID'], "int"),
					   GetSQLValueString($_POST['post_order'], "int"),
                       GetSQLValueString($_POST['post_category'], "text"),
                       GetSQLValueString($_POST['post_topic'], "text"),
                       GetSQLValueString($_POST['post_content'], "text"),
                       GetSQLValueString($_POST['post_by'], "text"),
                       GetSQLValueString($_POST['post_date'], "date"),
                       GetSQLValueString($_POST['post_edit_on'], "date"),
                       GetSQLValueString($_POST['post_edit_by'], "text"),
                       GetSQLValueString($_POST['post_current'], "text"),
					   GetSQLValueString($_POST['Blocked'], "text"),
					   GetSQLValueString($_POST['last_post'], "date"),
					   GetSQLValueString($_POST['admin_comment'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  
  /*Sending Email starts here */ 
  include("mailer.php");  
  function sendEmail($to,$message){

		$mailer = new mailer($to,"NOTIFICATION ONLY - AUTOMATED EMAIL",$message);
  }
  
  $message = "A new topic has been introduced in the forum\r\n
			PLEASE VISIT THE FORUM - DO NOT REPLY TO THIS EMAIL!\r\n
			New topic title:" .$_POST['post_topic']."\r\n;
			Topic created by:" .$_POST['post_by']."\r\n";
  
  $q = "SELECT * FROM vbsaorga_users";
  $r = mysql_query($q) or die("Error 1.1. Contact admin".mysql_error());
  
  
  $final_message="An auto email has been sent to all Board Members advising them of your new topic<BR>";
  while($row=mysql_fetch_array($r)){
    $to = $row['email_vbsa'];
	if(empty($to))continue;
	sendEmail($to,$message);
  }
  
  /*Sending email part ends here*/  

}

mysql_select_db($database_connvbsa, $connvbsa);
$query_forum_users = "SELECT * FROM vbsaorga_users WHERE email_vbsa is not null AND password is not null ORDER BY vbsaorga_users.name";
$forum_users = mysql_query($query_forum_users, $connvbsa) or die(mysql_error());
$row_forum_users = mysql_fetch_assoc($forum_users);
$totalRows_forum_users = mysql_num_rows($forum_users);

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
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
    <td colspan="2" align="left" class="red_bold">Insert a new topic to the VBSA Forum</td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

<div id="ContentDB">
<form method="post" name="form1" id="form1">
  

  
    
    <table width="1000" align="center" class="table_text">
      <tr valign="baseline">
        <td width="185" align="right" valign="middle" nowrap="nowrap">Type a &quot;Title&quot; for your topic: </td>
        <td width="803" valign="middle"><input type="text" name="post_topic" value="" size="70" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">General topic or Meeting?: </td>
        <td valign="middle"><select name="post_category">
          <option value="General" selected="selected" <?php if (!(strcmp("General", ""))) {echo "SELECTED";} ?>>General</option>
          <option value="Meeting" <?php if (!(strcmp("Meeting", ""))) {echo "SELECTED";} ?>>Meeting</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap="nowrap">Topic Content: </td>
        <td><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "";
$CKEditor = new CKEditor();
$CKEditor->basePath = "../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "forum1";
$CKEditor_config["wa_preset_file"] = "forum1.xml";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "200px";
$CKEditor_config["skin"] = "kama";
$CKEditor_config["docType"] = "<" ."!" ."DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
$CKEditor_config["dialog_startupFocusTab"] = false;
$CKEditor_config["fullPage"] = false;
$CKEditor_config["tabSpaces"] = 4;
$CKEditor_config["toolbar"] = array(
array( 'Source'),
array( 'Bold','Italic'),
array( 'TextColor'),
array( 'NumberedList','BulletedList','-','Outdent','Indent'),
array( 'PasteText','PasteFromWord','SpellChecker'),
array( 'Link','Unlink'),
array( 'Undo','Redo','Find','Replace'));
$CKEditor_config["contentsLangDirection"] = "ltr";
$CKEditor_config["entities"] = false;
$CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
$CKEditor_config["pasteFromWordRemoveStyles"] = false;
$CKEditor->editor("post_content", $CKEditor_initialValue, $CKEditor_config);
?></td>
      </tr>
      <tr valign="baseline">
        <td width="185" align="right" nowrap="nowrap">Post by: </td>
        <td><?php echo $row_getusername['name']; ?> (auto inserted)</td>
      </tr>

      <tr valign="baseline">
        <td width="185" align="center">&nbsp;</td>
        <td align="left"><input type="submit" value="Insert topic" /></td>
      </tr>
      <tr valign="baseline">
        <td align="center">&nbsp;</td>
        <td align="left" class="red_bold">
<?php
echo $final_message;
?>
        </td>
      </tr>
    </table>
    <input type="hidden" name="post_ID" value="" />
    <input type="hidden" name="post_order" value="0" />
    <input type="hidden" name="post_date" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?>" />
    <input type="hidden" name="post_edit_on" value="" />
    <input type="hidden" name="post_current" value="Current" />
    <input type="hidden" name="post_by" value="<?php echo $row_getusername['name']; ?>" />
    <input type="hidden" name="Blocked" value="No" />
    <input type="hidden" name="admin_current" value="" />
    <input type="hidden" name="post_edit_by" value="" />
    <input type="hidden" name="MM_insert" value="form1" />


</form>
</div>

</body>
</html>
<?php
mysql_free_result($forum_users);

mysql_free_result($getusername);
?>