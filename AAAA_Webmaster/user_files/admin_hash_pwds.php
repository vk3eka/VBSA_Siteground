<?php 
include ("../../vbsa_online_scores/connection.inc");

if(isset($_POST['Button']) && ($_POST['Button'] == "SaveScores")) 
{
  $sql_select = "Select * from vbsaorga_users";
  $result = $dbcnx_client->query($sql_select);
  while ($row_data = $result->fetch_assoc()) 
  {
    if((substr($row_data['hashed_password'], 0, 6) != '$2y$10') && ($row_data['password'] != ''))
    {
      $sql_authorise = "Update vbsaorga_users Set hashed_password = '" . password_hash($row_data['password'], PASSWORD_DEFAULT) . "' Where board_member_id = " . $row_data['board_member_id'];
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

<center>
<form name="hash" method="post" action="hash_pwds.php">
<input type="hidden" name="Button" id="Button" value='SaveScores'>
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
    <td align="center"><button type="submit">Convert Plain to Hash</button></a></td>
  </tr>
</table>
</form>
</center>
</body>
</html>