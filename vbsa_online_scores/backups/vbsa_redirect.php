<?php

include('header_vbsa.php');

?>
<script language="JavaScript" type="text/JavaScript">

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
        <form name="login" method="post" action="https://vbsa.org.au">
          <table border="0" cellspacing="0" cellpadding="0" id="reply" name="reply">
            <tr>
              <td align="center"><h3>Online Scores Entry Changes</h3></td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">The scoresheet entry site has moved to the VBSA Live website.</td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">Click on the 'Redirect' button below to go to the live site home page.</td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">The scoresheet login link is at the top right of the main menu.</td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">Don't forget to update any bookmarks from the test site to the live site.</td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td align="center">
                <button type="submit" class="btn btn-primary">Redirect</button>
              </td>
            </tr>
            <tr>
              <td align="center">&nbsp;</td>
            </tr>
        	  <tr>
              <td align="center">&nbsp;</td>
            </tr>
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