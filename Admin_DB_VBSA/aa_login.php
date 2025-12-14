<?php
session_start();
?>
<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once( "../webassist/security_assist/helper_php.php" ); ?>
<?php
if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_SERVER["HTTP_REFERER"]) && strpos(urldecode($_SERVER["HTTP_REFERER"]), urldecode($_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"])) > 0) && isset($_POST)){
	$WA_Auth_Parameter = array(
	"connection" => $connvbsa,
	"database" => $database_connvbsa,
	"tableName" => "vbsaorga_users",
	"columns" => explode($WA_Auth_Separator,"username".$WA_Auth_Separator."password"),
	"columnValues" => explode($WA_Auth_Separator,"".((isset($_POST["username"]))?$_POST["username"]:"")  ."".$WA_Auth_Separator."".((isset($_POST["password"]))?$_POST["password"]:"")  .""),
	"columnTypes" => explode($WA_Auth_Separator,"text".$WA_Auth_Separator."text"),
	"sessionColumns" => explode($WA_Auth_Separator,"id"),
	"sessionNames" => explode($WA_Auth_Separator,"id"),
	"successRedirect" => "vbsa_login_success.php",
	"failRedirect" => "../page_error.php",
	"gotoPreviousURL" => FALSE,
	"keepQueryString" => TRUE
	);
	
	WA_AuthenticateUser($WA_Auth_Parameter);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="ROBOTS" CONTENT="NOINDEX,FOLLOW">
<title>VBSA Login</title>
<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>


<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>

<form id="form1" name="form1" method="POST" >
  <table align="center">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Username: </td>
      <td><input type="username" name="username" id="username" /></td>
    </tr>
    <tr>
      <td>Password: </td>
      <td><input type="password" name="password" id="password" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" id="submit" value="Login" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  </form>
</body>
</html>