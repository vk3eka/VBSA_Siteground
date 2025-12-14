<?php
include ("connection.inc");
include ("php_functions.php");

if($_POST['ButtonName'] == "SendEmail") 
{
  $sql = "Select board_member_id from vbsaorga_users where board_member_id  = " . $_POST['MemberID'];
  $result_select_rego = $dbcnx_client->query($sql);
  $num_rows = $result_select_rego->num_rows;
  //if ($num_rows == 0)
  //{
      // get members name from rego number
      $sql = "Select * from members where MemberID  = " . $_POST['MemberID'];
      $result_select_rego = $dbcnx_client->query($sql);
      $row_data = $result_select_rego->fetch_assoc();
      $fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);
      // send email
      $subject = 'VBSA Admin System Access'; 
      $message = '<html><body>';
      $message .= "<p>" . $row_data['FirstName'] . "</p>";
      $message .= "<p>Here are your log-in details for the VBSA Admin Portal. You have been granted " . $_POST['usertype'] . " access.</p>";
      $message .= "<p>Username " . $_POST['Email'] . "</p>";
      $message .= "<p>Password " . $_POST['Password'] . "</p>";
      $message .= "<p>Please access the system and change your password, if you wish to.</p>";
      $message .= "<p>Click <a href='https://vbsa.org.au/VBSA_Admin_Login.php'>here </a>to access the site.</p>";
      $message .= "<p>Thanks.</p>";
      $message .= "<p>VBSA Webmaster.</p>";
      $message .= "<p>&nbsp;</p>";
      $message .= "<p><i>Direct all emails to <a href='mailto:web@vbsa.org.au'>web@vbsa.org.au</a></i></p>";
      $message .= "<img src='../images/image001.png' width = '400px' height = '140px'>";
      $message .= "</body></html>";
      echo($message);
      //Sendemail($subject, $message, $_POST['Email']);
  //}
}
?>