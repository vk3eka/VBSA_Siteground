<?php
include ('../vbsa_online_scores/connection.inc'); 
include ("../vbsa_online_scores/php_functions.php");

if(($_GET['forgot'] == 'username') or ($_POST['forgotwhat'] == 'username'))
{
  $caption = "Forgot your Username";
  $caption1 = "Enter your Registered Email Address.";
  $caption_title = "Email Address: ";
  $forgot = "username";

}
else
{
  if(($_GET['forgot'] == 'password') or ($_POST['forgotwhat'] == 'password'))
  {
    $caption = "Forgot your Password";
    $caption1 = "Enter your Registered Username.";
    $caption_title = "Username: ";
    $forgot = "password";
  }
}

?>
<script language="JavaScript" type="text/JavaScript">
/*
function EnterPressedAlert(e, textarea){
  var code = (e.keyCode ? e.keyCode : e.which);
  if(code == 13) { //'Enter' keycode
   document.forgot.submit();
  }
}

function LogOutButton() {
  document.login.submit();
}
*/
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="ROBOTS" CONTENT="NOINDEX,FOLLOW">
<title>VBSA Login</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<script type="text/javascript">

$(document).ready(function()
{
  $('#SendNewEmail').click(function(event){
    event.preventDefault();
    //var UserType = '';
    var Password = '<?php echo(generatePassword(10)); ?>';
    var Email = $("#email").val();
    //var MemberID = '';
    var ForgotWhat = '<?php echo($forgot); ?>';
    $.ajax({
      //url:"extra_forgot_email.php?Password=" + Password + "&UserType=" + UserType + "&Email=" + Email + "&MemberID=" + MemberID + "&ForgotWhat=" + ForgotWhat,
      url:"extra_forgot_email.php?Password=" + Password + "&Email=" + Email + "&ForgotWhat=" + ForgotWhat,
      success : function(response){
        alert(response);
        //window.location.href = 'VBSA_Extra_Login.php';
      }
    });
  });

});
</script>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<center>
<?php 
//if(!isset($_POST['buttonname']))
//{
?>
  <form name="forgot" method="post" action="Admin_forgot.php">
    <input type="hidden" name="buttonname"/>
    <input type="hidden" name="forgotwhat"/>
    <table border="0" align="center" cellpadding="2" class="greenbg" id="reply" name="reply">
      <tr>
        <td align="center" style='width: 30%'>&nbsp;</td>
        <td align="center" style='width: 70%'>&nbsp;</td>
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
        <td colspan="2" align="center"><?php echo($caption1); ?></td>
      </tr>
      <tr>
        <td align="center" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align=right><?php echo($caption_title); ?></td>
        <td align=center><input type="text" name="email" id="email" style="width:200px" autofocus/></td>
      </tr>
  	  <tr>
        <td align="center" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <!--<td align="center" colspan="2"><a class='greenbg' href="" onClick="SubmitButton();">Submit Request</a></td>-->
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
  </form>   
  <br>
</center>
</div>
</body>
</html>
<?php 
//}
?>