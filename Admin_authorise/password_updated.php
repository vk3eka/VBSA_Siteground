<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');

?>
<script type="text/javascript">

function LogOutButton() {
  document.pwd_update.submit();
}
</script>
<center>
  <form name="pwd_update" method="post" action="index.php">
<table width="425" border="0" cellspacing="0" cellpadding="0" align="center">  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">Your Password has been updated.</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td align="center"><a class='btn btn-primary btn-xs' href="javascript:;" onClick="LogOutButton();">Return to Login</a></td>
    <td>&nbsp;</td>
  </tr>  
</table>
</center>
</form>
<?php
include("footer.php"); 
?>