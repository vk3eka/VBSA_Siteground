<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer,Administrator,Secretary";
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

$MM_restrictGoTo = "../../page_error.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO members_bulkmail (mail_id, mail_by, mail_date, mail_body) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['mail_id'], "int"),
                       GetSQLValueString($_POST['mail_by'], "text"),
                       GetSQLValueString($_POST['mail_date'], "date"),
                       GetSQLValueString($_POST['mail_body'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
  
    /*Sending Email starts here */
  include("mailer.php");  
  function sendEmail($to,$message){

		$mailer = new mailer($to,"VBSA News",$message);
  }
  
     $message = "VBSA News\r\n"
		.$_POST['mail_body']. " \n ". "THIS IS A BULK EMAIL SENT FROM THE VBSA DATABASE\r\n";
  
  $q = "SELECT CONCAT( FirstName,' ' ,  LastName) As name, Email AS email FROM members WHERE members.MemberID=202";
  $r = mysql_query($q) or die("Error 1.1. Contact admin".mysql_error());
  
  
  $final_message="An email has been sent to all Financial members.<BR>";
  while($row=mysql_fetch_array($r)){
    $to = $row['email'];
	if(empty($to))continue;
	sendEmail($to,$message);
  }
  
  /*Sending email part ends here*/  
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO members_bulkmail (mail_id, mail_by, mail_date, mail_body) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['mail_id'], "int"),
                       GetSQLValueString($_POST['mail_by'], "text"),
                       GetSQLValueString($_POST['mail_date'], "date"),
                       GetSQLValueString($_POST['mail_body'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_membmail = "SELECT CONCAT( FirstName,' ' ,  LastName) As name, Email AS email FROM members WHERE members.MemberID=202";
$membmail = mysql_query($query_membmail, $connvbsa) or die(mysql_error());
$row_membmail = mysql_fetch_assoc($membmail);
$totalRows_membmail = mysql_num_rows($membmail);

$colname_user = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_user = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_user = sprintf("SELECT name, vbsaorga_users.email FROM vbsaorga_users WHERE vbsaorga_users.username=%s", GetSQLValueString($colname_user, "text"));
$user = mysql_query($query_user, $connvbsa) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Members Bulk Email</title>
<link href="../CSS/forum_db.css" rel="stylesheet" type="text/css" />
<link href="../CSS/forum_db_links.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="ContentDB">
  <table align="center" cellpadding="5">
    <tr>
      <td>Name</td>
      <td>Email</td>
    </tr>
    <?php do { ?><tr>
      <td><?php echo $row_membmail['name']; ?></td>
      <td class="page"><a href="mailto:<?php echo $row_membmail['email']; ?>"><?php echo $row_membmail['email']; ?></a></td>
    </tr><?php } while ($row_membmail = mysql_fetch_assoc($membmail)); ?>
  </table>
  
<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td class="red_bold">&nbsp;</td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td class="red_bold">Send an email to all financial members in the current year and the previous year</td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">From: </td>
      <td><?php echo $row_user['name']; ?> (Auto inserted into the email)</td>
      </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Email Message: </td>
      <td><textarea name="mail_body" cols="120" rows="10"></textarea></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Send Email" /></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><a href="members.php">return to members</a></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td align="left" class="red_bold">
  <?php
echo $final_message;
?>
        </td>
      </tr>
    </table>
  <p>
    <input type="hidden" name="mail_id" value="" />
    <input type="hidden" name="mail_date" value="" />
    <input type="hidden" name="mail_by" value="<?php echo $row_user['name']; ?>" />
    <input type="hidden" name="MM_insert" value="form1" />
    </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>

</div>
</body>
</html>
<?php
mysql_free_result($membmail);

mysql_free_result($user);
?>
