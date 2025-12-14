<?php require_once('Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

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
?>
<?php
// *** Validate request to login to this site.
//if (!isset($_SESSION)) {
//  session_start();
//}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  //$loginUsername=$_POST['email_vbsa'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "usertype";
  $MM_redirectLoginSuccess = "Admin_DB_VBSA/vbsa_login_success.php";
  $MM_redirectLoginFailed = "page_error.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_connvbsa, $connvbsa);
  
  $LoginRS__query=sprintf("Select username, hashed_password, usertype FROM vbsaorga_users WHERE username=%s",
  //$LoginRS__query=sprintf("Select username, password, hashed_password, usertype FROM vbsaorga_users WHERE username=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
  //echo("SQL " . $LoginRS__query . "<br>");
  $LoginRS = mysql_query($LoginRS__query, $connvbsa) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  $row = mysql_fetch_assoc($LoginRS);

//echo("Entered PWD " . $password . "<br>");
//echo("DB PWD " . $row["hashed_password"] . "<br>");

  if(($loginFoundUser) && (password_verify($password, trim($row["hashed_password"])))) {
  //if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'usertype');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else 
  {
    header("Location: ". $MM_redirectLoginFailed );
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="ROBOTS" CONTENT="NOINDEX,FOLLOW">
<title>VBSA Login</title>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<link href="Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><img src="Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>

<form ACTION="<?php echo $loginFormAction; ?>" id="form1" name="form1" method="POST" >
  <table align="center" cellpadding="2" cellspacing="10">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Email Address (vbsa.org.au): </td>
      <td><input type="username" name="username" id="username" /></td>
    </tr>
    <tr>
      <td>Password: </td>
      <td><input type="password" name="password" id="password" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center"><input type="submit" name="submit" id="submit" value="Login" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center" class="greenbg"><a href="AAAA_Webmaster/user_files/Admin_forgot.php?forgot=password">Forgot Password?</a></td>
    </tr>    
  </table> 
  </form>
  
  <table width="1000" align="center" >
    <tr>
      <td height="23" align="center" class="red_text">Please read and understand your obligations as a user of this database </td>
    </tr>
    <tr>
      <td width="" height="23" align="left"><p>The Victorian Billiards &amp; Snooker Association Inc. PRIVACY STATEMENT<br />
      </p>
        <p>The VBSA has created this statement in order to demonstrate our firm commitment to privacy. The following discloses our information gathering and dissemination practices for this website: www.vbsa.org.au.<br />
        </p>
        <p>We use a customer IP address to administer the website for statistical purposes and to create the VBSA database. We may also use cookies to store the user's name and password on their computer for future visits to the website.</p>
        <p>Various on-line process requests require users to give us contact information (eg. name and e-mail address etc). We will only use member contact information for reasons such as contacting the visitor when necessary.</p>
        <p>This site may contain links to other sites. The VBSA is not responsible for the privacy practices or the content of such websites.</p>
        <p>The VBSA database is private and confidential. The database (including personal details) is for the sole use of the VBSA. Personal member data and information gathered for the creation of the database will not be passed to any third party by the VBSA without the express written permission of the member, unless it is necessary to pass on this information in order to provide a service that you have asked us to provide.</p></td>
    </tr>
  </table>

</body>
</html>