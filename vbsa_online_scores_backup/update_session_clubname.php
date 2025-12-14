<?php 
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');

?>
<center>
<form name="change_clubname" method="post" action="main.php">
<table width="425" border="0" cellspacing="0" cellpadding="0" align="center">  
  <tr> 
    <td class=normal>&nbsp;</td>
    <td class=normal>&nbsp;</td>
  </tr>
  <tr> 
    <td align=center colspan=2 class=normal><h2>Team Name Changed.</h2></td>
  </tr> 
  <tr> 
    <td class=normal>&nbsp;</td>
    <td class=normal>&nbsp;</td>
  </tr>
  <tr> 
    <td class=normal>&nbsp;</td>
    <td class=normal>&nbsp;</td>
  </tr>
  <tr> 
    <td align=center colspan=2 class=normal>You are now administering <?= $_SESSION['clubname'] ?>.</td>
  </tr>  
  <tr> 
    <td class=normal>&nbsp;</td>
    <td class=normal>&nbsp;</td>
  </tr>
  <tr> 
    <td align=center colspan=2 class=normal>Please make a selection from the top menu.</td>
  </tr>
  <tr> 
    <td class=normal>&nbsp;</td>
    <td class=normal>&nbsp;</td>
  </tr>
</table>
</form>
</center>
</body>
</html>