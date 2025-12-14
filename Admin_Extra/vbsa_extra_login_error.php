<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  #Removed By Alec 22.3.22 Deprecated Function Error $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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
?><?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['UserExtra'])) {
  $loginUsername=$_POST['UserExtra'];
  $password=$_POST['UserPass'];
  $MM_fldUserAuthorization = "usertype";
  $MM_redirectLoginSuccess = "vbsa_extra.php";
  $MM_redirectLoginFailed = "vbsa_extra_login_error.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_connvbsa, $connvbsa);
  	
  //$LoginRS__query=sprintf("SELECT username, password, usertype FROM vbsaorga_users2 WHERE username=%s AND password=%s",
  //GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS__query=sprintf("Select email_address, hashed_password, usertype FROM vbsaorga_users2 WHERE email_address=%s",
  GetSQLValueString($loginUsername, "text")); 
  //echo($LoginRS__query . "<br>");
  $LoginRS = mysql_query($LoginRS__query, $connvbsa) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  $row = mysql_fetch_assoc($LoginRS);
  
  //echo("User " . $loginFoundUser . "<br>");
  //echo("PWD " . $password . "<br>");
  //echo("PWD Verfiy " . password_verify($password, $row["hashed_password"]) . "<br>");

  if(($loginFoundUser) && (password_verify($password, trim($row["hashed_password"])))) {
  //if ($loginFoundUser) {
    
    //$loginStrGroup  = mysql_result($LoginRS,0,'usertype');
    $loginStrGroup  = '';
    for($i = 0; $i < $loginFoundUser; $i++)
    {
      $loginStrGroup  = mysql_result($LoginRS,$i,'usertype') . ',' . $loginStrGroup;
    }
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    //echo("Success " . $loginFoundUser . "<br>");
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    //echo("Fail " . $loginFoundUser . "<br>");
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<div class="extra_text">
  
  
  <form ACTION="<?php echo $loginFormAction; ?>" id="form1" name="form1" method="POST">
    <table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="extra_text">
      <tr>
        <td colspan="3" align="center" class="red_bold">Sorry! You may not have access to this page or your details were incorrect please type them in again</td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="419" align="right">Username</td>
        <td width="371"><label>
            <input type="text" name="UserExtra" id="UserExtra" />
          </label></td>
        <td width="190"><label></label></td>
      </tr>
      <tr>
        <td align="right">Password</td>
        <td><label>
            <input type="password" name="UserPass" id="UserPass" />
          </label></td>
        <td><label></label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><label>
            <input type="submit" name="login" id="login" value="Login" />
          </label></td>
        <td><label></label></td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="page">If you have encountered a problem logging in: please contact the <a href="mailto:scores@vbsa.org.au">webmaster</a></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      </table>
  </form>
  </td>
</div>

</body>
</html>
