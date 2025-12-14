<?php 

include('server_name.php');

// send email with memberid info
      $subject = 'VBSA Login Details';  
      $message = '<html><body>';
      $message .= "<p>" . $name . "</p>";
      $message .= "<p>Here is your login Player Number:</p>";
      $message .= "<p>Player No.  " . $memberID . "</p>";
      $message .= "<p>It is also suggested you change the password in the 'Change Password' option.</p>";
      $message .= "<p><a href='https://www.vbsa.org.au/'>Click here to access the scoring site.</a></p>";
      $message .= "<p>Thanks.</p>";
      $message .= "<p>Database Administrator.</p>";
      $message .= "<p>&nbsp;</p>";
      $message .= "<p><i>NOTE: Please do not reply to this email as this email address is not monitored.</i></p>";
      $message .= "<p><i>Direct all emails to <a href='mailto:web@vbsa.org.au'>web@vbsa.org.au</a></i></p>";
      $message .= "<img src='" . $url . "/MarkDunn.jpg' width = '400px' height = '140px'>";
      $message .= "</body></html>";
      echo($message);

      echo(date("Y-m-d H:m:s"));

?>