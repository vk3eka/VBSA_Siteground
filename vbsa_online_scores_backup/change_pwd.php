<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include ("header.php");
include ("connection.inc");

//echo('Check ' . $_POST['checkpass'] . '<br>');
//echo('Old PWD ' . $_POST['old_password'] . '<br>');
//echo('PWD ' . $_SESSION['password'] . '<br>');

function CheckExistingPW($old_password, $password)
{
	// check old password is valid
	if(! password_verify($old_password, $password))
	{
		//echo("Password Checked but not OK.<br>");
		echo "<script type=\"text/javascript\">"; 
    echo "alert('Your Existing Password is incorrect.')"; 
    echo "</script>";
		return false;
	}
	else
	{
		//echo("Password Checked OK.<br>");
		return true;
	}
}

if (($_POST['checkpass'] == "Yes") and (CheckExistingPW($_POST['old_password'], $_SESSION['password']))) 
{
	$sql = "Update tbl_authorise SET Password = '" . password_hash($_POST['new_password1'], PASSWORD_DEFAULT) . "' WHERE Email = '" . $_POST['memberno'] . "'";
	//echo($sql);
	$update = $dbcnx_client->query($sql);
	if(! $update )
	{
		die("Could not update data: " . mysqli_error($dbcnx_client));
	}
	else
	{
		echo "<script type='text/javascript'>window.location = '" . $url . "/password_updated.php'</script>";
	}
}

?>
<script language="JavaScript" type="text/JavaScript">

function UpdatePWButton() 
{
	var memberno = document.getElementById('memberNo').value;
	if (validatePwd()) 
	{
		document.password.memberno.value = memberno;
		document.password.checkpass.value = "Yes";
		document.password.submit();
	}
	else
	{
		return;
	}
}

function validatePwd() 
{
	var invalid = " "; // Invalid character is a space
	var minLength = 6; // Minimum length
	var maxLength = 15; // Maximum length
	var pw1 = document.password.new_password1.value;
	var pw2 = document.password.new_password2.value;
	// check for a value in both fields.
	if (pw1 == '' || pw2 == '') 
	{
		alert('Please enter your password twice.');
		return false;
	}
	// check for minimum length
	if (document.password.new_password1.value.length < minLength) 
	{
		alert('Your password must be at least ' + minLength + ' characters long. Try again.');
		return false;
	}
	// check for maximum length
	if (document.password.new_password1.value.length >= maxLength) 
	{
		alert('Your password must be ' + maxLength + ' or less characters long. Try again.');
		return false;
	}
	// check for lowercase
	var regex = /^(?=.*[a-z]).+$/;
	if (!regex.test(document.password.new_password1.value)) 
	{
		alert('Your password must contain a lowercase character. Try again.');
		return false;
	}
	// check for uppercase
	var regex = /^(?=.*[A-Z]).+$/;
	if (!regex.test(document.password.new_password1.value)) 
	{
		alert('Your password must contain an uppercase character. Try again.');
		return false;
	}
	// check for spaces
	if (document.password.new_password1.value.indexOf(invalid) > -1) 
	{
		alert("Sorry, spaces are not allowed.");
		return false;
	}
	else 
	{
		if (pw1 != pw2) 
		{
			alert ("You did not enter the same new password twice. Please re-enter your password.");
			return false;
		}
		else 
		{
			return true;
	  }
	}
}

</script>
<center>
<?php
$change_password_caption = "You may change your password by typing your new password (twice) and clicking on the 'Update Password?' button.";
?>
<form name="password" method="post" action="change_pwd.php">
<input type="hidden" name="memberno">
<input type="hidden" name="checkpass">
<center>
<table class='table table-striped table-bordered dt-responsive nowrap display'>
    <tr>
      <td colspan=2 align=center><?php echo($change_password_caption); ?></td>
    </tr>
    <tr>
      <td colspan=2 align=center>Your new password should contain a minimum of 6 and maximum of 10 characters with at least one uppercase and one lowercase character with no spaces.</td>
    </tr>
    <tr>
      <td colspan=2 align=center>&nbsp;</td>
    </tr>
    <tr>
      <td>        
				<table class='table table-striped table-bordered dt-responsive nowrap display'>
				  <tr>
						<td colspan=2>&nbsp;</td>
				  </tr>
				  <tr>
						<td align=left>Email Address</td>
					  <td align=center><input type="text" name="memberNo" id="memberNo" value=""></td>
				  </tr>
				  <tr>
						<td colspan=2>&nbsp;</td>
				  </tr>
				  <tr>
						<td align=left>Enter Existing password.</td>
						<td align=center><input type="password" name="old_password" id="old_password" value=""></td>
				  </tr>
				  <tr>
						<td align=left>Enter New password.</td>
						<td align=center><input type="password" name="new_password1" id="new_password1" value=""></td>
				  </tr>
				  <tr>
						<td align=left>Re-enter New password</td>
						<td align=center><input type="password" name="new_password2" id="new_password2" value=""></td>
				  </tr>
				  <tr>
						<td colspan=2>&nbsp;</td>
				  </tr>
				  <tr>
				    <td colspan='2' align="center"><a class='btn btn-primary btn-xs' href="javascript:;" onClick="UpdatePWButton();">Update Password</a></td>
				  </tr>  
		  	</table>    
    	</td>
    </tr>
  </table>
</form>
</center>    
<?php

include("footer.php"); 

?>