<?php
if (!isset($_SESSION)) {
  session_start();
}
include('server_name.php');
include('session_vbsa_header.php');
/*
if(strtolower($_GET['logout']) == 'yes') {
  // Unset all of the session variables when Log Out button pressed.
  //session_start();
  session_unset();
  $_SESSION = [];
  session_destroy();
  session_write_close();
  setcookie(session_name(),'',0,'/');
  session_regenerate_id(true);
}
*/
?>
<script language="JavaScript" type="text/JavaScript">

function EnterPressedAlert(e, textarea){
  var code = (e.keyCode ? e.keyCode : e.which);
  if(code == 13) { //'Enter' keycode
   document.login.submit();
  }
}

function LoginButton() {
	document.login.submit();
}

</script>
<center>
<div class="">
  <div class="page-title"></div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <div class="clearfix"></div>
      </div>
      <div class="x_content"> 
      <div onKeyPress="EnterPressedAlert(event, this)">
        <form name="login" method="post" action="session_main.php">
          <table border="0" cellspacing="0" cellpadding="0" id="reply" name="reply" onKeyPress="EnterPressedAlert(event, this)">
            <tr>
              <td align="center"><h3>Administrator/Team Captain Login Form</h3></td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center"><input type="text" class="form-control input-sm" name="username" style="width:250px" autofocus placeholder="Enter your Username" required=""/></td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center"><input type="password" name="password" class="form-control input-sm" style="width:250px" placeholder="Enter your Password" required="" /></td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">
                <button type="submit" class="btn btn-primary">Login</button>
              </td>
            </tr>
            <!--<tr>
              <td align="center">
                <button type="submit" class="btn btn-primary">Clear Session</button>
              </td>
            </tr>-->
          </table>
        </form>
        </div>
    </div>
    </div>
  </div>
</div>
</center>
</body>
</html>