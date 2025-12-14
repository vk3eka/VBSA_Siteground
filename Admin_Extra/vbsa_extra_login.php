<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    #removed By Alec 22.3.22 Deprecated Function Error $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
?>
<?php
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
  	
  $LoginRS__query=sprintf("Select email_address, hashed_password, usertype FROM vbsaorga_users2 WHERE email_address=%s",
  GetSQLValueString($loginUsername, "text")); 
  
  $LoginRS = mysql_query($LoginRS__query, $connvbsa) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  $row = mysql_fetch_assoc($LoginRS);
  
  if(($loginFoundUser) && (password_verify($password, trim($row["hashed_password"])))) {
  //if ($loginFoundUser) {
    $loginStrGroup  = mysql_result($LoginRS,0,'usertype');

    //$loginStrGroup  = '';
    for($i = 0; $i < $loginFoundUser; $i++)
    {
      $loginStrGroup  = mysql_result($LoginRS,$i,'usertype') . ',' . $loginStrGroup;
    }
    //echo($loginFoundUser . ", " . $loginStrGroup . "<br>");

    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administration Area</title>
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
<form ACTION="<?php echo $loginFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="400" align="center" class="extra_text" border=0>
    <tr>
      <td colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td align="center" width=50%>Email Address</td>
      <td align="center" width=50%><input type="text" name="UserExtra" id="UserExtra" /></td>
    </tr>
    <tr>
      <td align="center" width=50%>Password</td>
      <td align="center" width=50%><input type="password" name="UserPass" id="UserPass" /></td>
    </tr>
    <tr>
      <td colspan=2 align="center"><input type="submit" name="login" id="login" value="Login" /></td>
    </tr>
    <tr>
      <td colspan=2>&nbsp;</td>
    </tr>
    <!--<tr>
      <td colspan=2 align="center" class="greenbg" style='width:800px'><a href="Extra_forgot.php?forgot=username">Forgot Username?</a></td>
    </tr>  -->
    <tr>
      <td colspan=2>&nbsp;</td>
    </tr>
    <tr>
      <td colspan=2 align="center" class="greenbg" style='width:400px'><a href="Extra_forgot.php?forgot=password">Forgot Password?&nbsp;</a></td>
    </tr>    
  </table>
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
</form>
</body>
</html>
