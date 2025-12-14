<?php
include ('../../vbsa_online_scores/connection.inc'); 
include ("../../vbsa_online_scores/php_functions.php");

if(($_GET['forgot'] == 'username') or ($_POST['forgotwhat'] == 'username'))
{
  $caption = "Forgot your Email Address";
}
else
{
  if(($_GET['forgot'] == 'password') or ($_POST['forgotwhat'] == 'password'))
  {
    $caption = "Forgot your password";
  }
}

?>
<script language="JavaScript" type="text/JavaScript">

function EnterPressedAlert(e, textarea){
  var code = (e.keyCode ? e.keyCode : e.which);
  if(code == 13) { //'Enter' keycode
   document.forgot.submit();
  }
}

function LogOutButton() {
  document.login.submit();
}

</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="ROBOTS" CONTENT="NOINDEX,FOLLOW">
<title>VBSA Login</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/javascript">

$(document).ready(function()
{
  $('#SendNewEmail').click(function(event){
    event.preventDefault();
    var Password = '<?php echo(generatePassword(10)); ?>';
    var UserType = '';
    var Email = $("#email").val();
    var MemberID = '';
    $.ajax({
      url:"forgot_send_email.php?Password=" + Password + "&UserType=" + UserType + "&Email=" + Email + "&MemberID=" + MemberID,
      success : function(response){
        alert(response);
        window.location.href = '../../VBSA_Admin_Login.php';
      }
    });
  });

});
</script>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<center>
  <form name="forgot" method="post" action="Admin_forgot.php">
    <input type="hidden" name="buttonname"/>
    <input type="hidden" name="forgotwhat"/>
    <table border="0" align="center" cellpadding="2" class="greenbg" id="reply" name="reply" onKeyPress="EnterPressedAlert(event, this)">
      <tr>
        <td align="center" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" colspan="2">&nbsp;</td>
      </tr>    
      <tr>
        <td colspan="2" align="center"><font size="+2"><u><?php echo($caption); ?></u></font></td>
      </tr>
      <tr>
        <td align="center" colspan="2">&nbsp;</td>
      </tr>    
      <tr>
        <td colspan="2" align="center">Enter your vbsa,org,au email address.</td>
      </tr>
      <tr>
        <td align="center" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align=center>Email Address: </td>
        <td align=center><input type="text" name="email" id="email" style="width:200px" autofocus/></td>
      </tr>
  	  <tr>
        <td align="center" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" colspan="2"><a class='greenbg' href="" id="SendNewEmail">Submit Request</a></td>
      </tr>    
      <tr>
        <td align="center" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" colspan="2" class="red_text">Only click the button once, you will recieve a message when the request is complete.</td>
      </tr>
    </table>
    </form>
  <br>
</center>
</div>
</body>
</html>
