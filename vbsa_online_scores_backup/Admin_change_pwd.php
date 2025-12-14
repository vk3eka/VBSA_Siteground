<?php
include ("connection.inc"); 
include ("header_admin.php");

function CheckExistingPW($old_password, $email)
{
	global $dbcnx_client;
	$sql = "Select hashed_password, username FROM vbsaorga_users WHERE username = '" . $email . "'";
	$result = $dbcnx_client->query($sql);
	$row = $result->fetch_assoc();
	if(password_verify($old_password, $row["hashed_password"]))
	{
		//echo("Password Checked OK.<br>");
		return true;
	}
	else
	{
		//echo("Password Checked but not OK.<br>");
		echo "<script type=\"text/javascript\">"; 
    echo "alert('Your Existing Password is incorrect.')"; 
    echo "</script>";
		return false;
	}
}

if((isset($_POST['checkpass']) && ($_POST['checkpass'] != '')))
{
	if (($_POST['checkpass'] == "Yes") && (CheckExistingPW($_POST['old_password'], $_POST['memberNo']) == 1))
	{
		$sql = "Update vbsaorga_users SET hashed_password = '" . password_hash($_POST['new_password1'], PASSWORD_DEFAULT) . "' WHERE username = '" . $_POST['memberNo'] . "'";
		$update = $dbcnx_client->query($sql);
		if(! $update )
		{
			die("Could not update data: " . mysqli_error($dbcnx_client));
		}
		else
		{
			echo "<script type=\"text/javascript\">"; 
	    echo "alert('Your Password has been updated.')"; 
	    echo "</script>";
			echo "<script type='text/javascript'>window.location = '../VBSA_Admin_Login.php'</script>";
		}
	}
}

?>
<script language="JavaScript" type="text/JavaScript">

$(document).ready(function()
{
	$('#update_record').click(function(event){
		event.preventDefault();
		if ($.fn.validatePwd()) 
		{
			var memberNo = $('#memberNo').val(); 
			var checkpass = $('#checkpass').val('Yes');
			$('#changePWD').submit();
		}
		else
		{
			return;
		}
	});

	$.fn.validatePwd = function () {
		event.preventDefault();
		var invalid = " "; // Invalid character is a space
		var minLength = 6; // Minimum length
		var maxLength = 15; // Maximum length
		var pw1 = $('#new_password1').val();
		var pw2 = $('#new_password2').val();
		// check for a value in both fields.
		if (pw1 == '' || pw2 == '') 
		{
			alert('Please enter your password twice.');
			return false;
		}
		// check for minimum length
		if (pw1.length < minLength) 
		{
			alert('Your password must be at least ' + minLength + ' characters long. Try again.');
			return false;
		}
		// check for maximum length
		if (pw1.length >= maxLength) 
		{
			alert('Your password must be ' + maxLength + ' or less characters long. Try again.');
			return false;
		}
		// check for lowercase
		var regex = /^(?=.*[a-z]).+$/;
		if (!regex.test(pw1)) 
		{
			alert('Your password must contain a lowercase character. Try again.');
			return false;
		}
		// check for uppercase
		var regex = /^(?=.*[A-Z]).+$/;
		if (!regex.test(pw1)) 
		{
			alert('Your password must contain an uppercase character. Try again.');
			return false;
		}
		// check for spaces
		if (pw1.indexOf(invalid) > -1) 
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
});


</script>
<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
  <tr>
  <td align="center" class="red_bold">Change Password Area.</td>
  <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>

<br>
<center>
<?php
$change_password_caption = "You may change your password by typing your new password (twice) and clicking on the 'Update Password?' button.";
?>
<form name="changePWD" id="changePWD" method="post" action="Admin_change_pwd.php">
<!--<input type="hidden" name="memberno">-->
<input type="hidden" name="checkpass" value='Yes'>
<center>
<table border="0" align="center" cellpadding="2">
	  <tr>
      <td colspan=2 align=center>&nbsp;</td>
    </tr>
    <tr>
      <td colspan=2 align=center><?php echo($change_password_caption); ?></td>
    </tr>
    <tr>
      <td colspan=2 align=center>Your new password should contain a minimum of 6 and maximum of 16 characters with at least one uppercase and one lowercase character with no spaces.</td>
    </tr>
    <tr>
      <td colspan=2 align=center>&nbsp;</td>
    </tr>
    <tr>
      <td>        
				<table border="0" align="center" cellpadding="2" class='greenbg'>
				  <tr>
						<td colspan=2>&nbsp;</td>
				  </tr>
				  <tr>
						<td align=left>Email Address (vbsa.org.au)</td>
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
				    <td colspan='2' align="center"><a class='greenbg' href="" id='update_record'>Update Password</a></td>
				  </tr>  
		  	</table>    
    	</td>
    </tr>
  </table>
</form>
</center>    
</body>
</html>
