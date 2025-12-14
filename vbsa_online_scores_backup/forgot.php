<?php
if (!isset($_SESSION)) 
{
  session_start();
}

include("header_vbsa.php"); 
include('connection.inc');
include('php_functions.php');

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

function SubmitButton() {
  if(document.getElementById('email').value != "")
  {
    document.forgot.buttonname.value = 'Submit';
    document.forgot.email.value = document.getElementById('email').value;
    document.forgot.forgotwhat.value = '<?php echo($_GET['forgot']); ?>';
    document.forgot.submit();
  }
  else
  {
    alert("You need to enter your registered email address!");
    return;
  }
}

function LogOutButton() {
  document.login.submit();
}

</script>
<center>
<?php 
if(!isset($_POST['buttonname']))
{
?>
  <form name="forgot" method="post" action="forgot.php">
    <input type="hidden" name="buttonname"/>
    <input type="hidden" name="forgotwhat"/>
    <table width="400" border="0" cellspacing="10" cellpadding="0" id="reply" name="reply" onKeyPress="EnterPressedAlert(event, this)">
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
        <td colspan="2" align="center">Enter your Registered Email Address.</td>
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
        <td align="center" colspan="2">&nbsp;</td>
      </tr>    
    </table>
        <div class="horizontal-centering">
          <div>
              <div>
                <ul id="nav" class="dropdown dropdown-horizontal">
                  <li><a class='btn btn-primary btn-xs' href="javascript:;" onClick="SubmitButton();">Submit Request</a></li>
                 </ul>
              </div>
          </div>
  	   </div>     
    </form>
<?php 
}
elseif((isset($_POST['buttonname'])) and ($_POST['buttonname'] = 'Submit'))
{
  $sql_authorise = "Select * from tbl_authorise Where Email = '" . $_POST['email'] . "'";
  $result_authorise = $dbcnx_client->query($sql_authorise);
  $num_authorise = $result_authorise->num_rows;
  if($num_authorise > 0)
  {
    $build_data_authorise = $result_authorise->fetch_assoc();
    $memberID = $build_data_authorise['PlayerNo'];
    $name = $build_data_authorise['Name'];
    if(($_GET['forgot'] == 'password') or ($_POST['forgotwhat'] == 'password'))
    {
      // update password
      $new_password = generatePassword(10);
      $sql = "Update tbl_authorise SET Password = '" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE PlayerNo = '" . $memberID . "'";
      $update = $dbcnx_client->query($sql);
      if(! $update )
      {
        die("Could not update password data: " . mysqli_error($dbcnx_client));
      }

      // send email with password reset info
      $subject = 'VBSA Login Details';  
      $message = '<html><body>';
      $message .= "<p>" . $name . "</p>";
      $message .= "<p>Here are your login details:</p>";
      $message .= "<p>Temporary Password  " . $new_password . "</p>";
      $message .= "<p>It is suggested you change your password in the 'Change Password' option after login.</p>";
      $message .= "<p>Click <a href='https://vbsa.org.au/'>here </a>to access the Scores Entry Webpage System (SEWS).</p>";
      $message .= "<p>Thanks.</p>";
      $message .= "<p>Database Administrator.</p>";
      $message .= "<p>&nbsp;</p>";
      $message .= "<p><i>NOTE: Please do not reply to this email as this email address is not monitored.</i></p>";
      $message .= "<p><i>Direct all emails to <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
      $message .= "<img src='" . $url . "/MarkDunn.jpg' width = '400px' height = '140px'>";
      $message .= "</body></html>";
    }
    Sendemail($subject, $message, $_POST['email']);

    $email_caption = "Your request has been sent to your registered email address. You will be redirected in 3 seconds.";
    // must be the same as the if statement below.
  }
  else
  {
    $email_caption = "The email address is not registered.<br><br>Please contact the Webmaster at <a href='mailto:web@vbsa.org.au'>web@vbsa.org.au</a>.";
  }
 ?>
  <form name="login" method="post" action="index.php">
  <table width="400" border="0" cellspacing="10" cellpadding="0">
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
      <td align="center" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" colspan="2">&nbsp;</td>
    </tr>    
    <tr>
      <td colspan="2" align="center"><?php echo($email_caption); ?></td>
    </tr>
    <tr>
      <td align="center" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" colspan="2">&nbsp;</td>
    </tr>    
  </table>
  <?php
    if($email_caption == 'Your request has been sent to your registered email address. You will be redirected in 3 seconds.')
    {
      echo("<script>");
      //echo("var timer = setTimeout(function() {");
      echo("window.location='index.php'");
      //echo("}, 3000);");
      echo("</script>");
    }
  ?>
  <div class="horizontal-centering">
    <div>
      <div>
        <ul id="nav" class="dropdown dropdown-horizontal">
          <li><a class='btn btn-primary btn-xs' href="javascript:;" onClick="LogOutButton();">Return to LogIn</a></li>
        </ul>
      </div>
    </div>
  </div>  
  </form>   
  <br>
</center>
</div>
</body>
</html>
<?php 
}
?>