<?php 

include ("../vbsa_online_scores/header_admin.php");
include ("../vbsa_online_scores/connection.inc");

echo("Button 1 " . $_GET['Button'] . "<br>");
echo("Button 2 " . $_POST['Button'] . "<br>");

if(isset($_POST['Button']) && ($_POST['Button'] == "SaveScores")) 
{
  $sql_select = "Select * from vbsaorga_users";
  $result = $dbcnx_client->query($sql_select);
  while ($row_data = $result->fetch_assoc()) 
  {
    echo(substr($row_data['password'], 0, 6) . "<br>");
    if((substr($row_data['hashed_password'], 0, 6) != '$2y$10') && ($row_data['password'] != ''))
    {
      echo($row_data['password'] . "<br>");
      $sql_authorise = "Update vbsaorga_users Set hashed_password = '" . password_hash($row_data['password'], PASSWORD_DEFAULT) . "' Where board_member_id = " . $row_data['board_member_id'];
      echo($sql_authorise . "<br>");
      $update2 = $dbcnx_client->query($sql_authorise);
      if(! $update2 )
      {
         die("Could not update password data:  " . mysqli_error($dbcnx_client));
      }
      else
      {
        echo($row_data['board_member_id'] . " password updated.<br>");
      }
    }
  }
}
else
{
  echo("Not here");
}

?>
<script language="JavaScript" type="text/JavaScript">
function SaveButton() 
{
  //alert(document.getElementById("rego").value);
  //document.hash.Rego.value = document.getElementById("rego").value;
  //document.hash.PlainPWD.value = document.getElementById("plain").value;
  //alert("Here");
  document.hash.Button.value = 'SaveScores'; 
  document.hash.submit();
}   
</script>

<center>
<form name="hash" method="post" action="hash_pwds.php">
<input type="hidden" name="Button" id="Button">
<!--<input type="hidden" name="Rego">
<input type="hidden" name="PlainPWD">-->
<br>
<br>
<br>
<br>
<table width="425" border="0" cellspacing="0" cellpadding="0" align="center">  
<?php

$sql = "Select * from vbsaorga_users Order By board_member_id";
$result = $dbcnx_client->query($sql);
echo("<tr>"); 
echo("<td colspan=3 align=center><b>Existing User Access</b></td>");
echo("</tr>");
echo("<tr>"); 
echo("<td colspan=3 align=center>&nbsp;</td>");
echo("</tr>");
echo("</table>");
echo("<table width='425' border='1' cellspacing='0' cellpadding='0' align='center'>");  
echo("<tr>"); 
echo("<td align='center'><b>Registration No.</b></td>");
echo("<td align='center'><b>Password</b></td>");
echo("<td align='center'><b>Hashed Password</b></td>");
echo("</tr>");
$i = 0;
while ($build_data = $result->fetch_assoc()) {
    echo("<tr>"); 
    echo("<td align='center' id='rego_no'>" . $build_data['board_member_id'] . "</td>");
    echo("<td align='center' id='pwd'>" . $build_data['password'] . "</td>");
    echo("<td align='center' id='hash_pwd'>" . $build_data['hashed_password'] . "</td>");
    echo("</tr>"); 
    $i++;
} 
    
?>
</table>
<table width="425" border="0" cellspacing="0" cellpadding="0" align="center">  
   <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><a class='greenbg' href='' onclick='SaveButton()'>Convert Plain to Hash</a></td>
  </tr>
</table>
</form>
</center>
</body>
</html>