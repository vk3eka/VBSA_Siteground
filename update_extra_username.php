<?php

$dbcnx_client = new mysqli("172.16.10.16", "peterj", "abj059XZ@!", "vbsa3364_vbsa2");
if ($dbcnx_client->connect_errno) {
    echo "Failed to connect to MySQL: " . $dbcnx_client->connect_error;
}

$sql = "Select Email, MemberID from members left join vbsaorga_users2 On vbsaorga_users2.vbsa_id=members.MemberID where vbsa_id != '' order by vbsa_id";
$result = $dbcnx_client->query($sql);
while($row_data = $result->fetch_assoc())
{
    $sql_insert = "Update vbsaorga_users2 
         set email_address = '" . $row_data['Email'] . "' where vbsa_id = " . $row_data['MemberID'];  
        //echo("Update " . $sql_insert . "<br>");
        $update = $dbcnx_client->query($sql_insert);

}
?>
<center>
<br>
<br>
<br>
<br>
<table width="425" border="0" cellspacing="0" cellpadding="0" align="center">  
<?php

$sql = "Select * from vbsaorga_users2 Order By vbsa_id";
$result = $dbcnx_client->query($sql);
echo("<tr>"); 
echo("<td colspan=3 align=center><b>Existing User Table</b></td>");
echo("</tr>");
echo("<tr>"); 
echo("<td colspan=3 align=center>&nbsp;</td>");
echo("</tr>");
echo("</table>");
echo("<table width='425' border='1' cellspacing='0' cellpadding='0' align='center'>");  
echo("<tr>"); 
echo("<td align='center'><b>VBSA No.</b></td>");
echo("<td align='center'><b>Username</b></td>");
echo("<td align='center'><b>Email Address</b></td>");
echo("</tr>");
$i = 0;
while ($build_data = $result->fetch_assoc()) {
    echo("<tr>"); 
    echo("<td align='center' id='rego_no'>" . $build_data['vbsa_id'] . "</td>");
    echo("<td align='center' id='uname'>" . $build_data['username'] . "</td>");
    echo("<td align='center' id='email'>" . $build_data['email_address'] . "</td>");
    echo("</tr>"); 
    $i++;
} 
    
?>
</table>

