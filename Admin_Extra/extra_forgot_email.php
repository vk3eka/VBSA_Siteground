<?php
include ("../vbsa_online_scores/connection.inc");

if (!isset($_SESSION)) {
  session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vbsa_online_scores/vendor/PHPMailer/src/Exception.php';
require '../vbsa_online_scores/vendor/PHPMailer/src/PHPMailer.php';
require '../vbsa_online_scores/vendor/PHPMailer/src/SMTP.php';


//echo($_GET['UserType'] . "<br>");
//echo($_GET['Password'] . "<br>");
//echo($_GET['ForgotWhat'] . "<br>");
//echo($_GET['Email'] . "<br>");


// get members name from rego number
//$sql = "Select * from members where MemberID  = " . $_GET['MemberID'];
//$result_select_memberid = $dbcnx_client->query($sql);
//$row_data = $result_select_memberid->fetch_assoc();
//$email = ($row_data['Email']);

if($_GET['ForgotWhat'] == 'password')
{
    //echo($_GET['Email'] . "<br>");
    // forgotten password
    $sql = "Select vbsa_id, usertype from vbsaorga_users2 where email_address  = '" . $_GET['Email'] . "'";
    //echo($sql . "<br>");
    $result_select_rego = $dbcnx_client->query($sql);
    $row_email = $result_select_rego->fetch_assoc();
    $vbsaID = $row_email['vbsa_id'];
    //echo("ID " . $vbsaID . "<br>");;
    if($vbsaID == '')
    {
        echo("Your Username is not registered!");
        return;
    }

    $_SESSION['MM_UserGroup'] = $row_email['usertype'];

    // get new password
    //$password = generatePassword(10);

    // update with new password
    $sql = "Update vbsaorga_users2 SET hashed_password = '" . password_hash($_GET['Password'], PASSWORD_DEFAULT) . "' WHERE vbsa_id = '" . $vbsaID . "'";
    $update = $dbcnx_client->query($sql);

    // get members name from rego number
    $sql = "Select * from members where MemberID  = " . $vbsaID;
    $result_select_memberid = $dbcnx_client->query($sql);
    $row_data = $result_select_memberid->fetch_assoc();
    //$email = ($row_data['Email']);
    // send email
    $subject = 'VBSA Affiliate System Access'; 
    $message = '<html><body>';
    $message .= "<p>" . $row_data['FirstName'] . "</p>";
    $message .= "<p>Here are your log-in details for the VBSA Affiliate Portal.</p>";
    $message .= "<p>Username " . $_GET['Email'] . "</p>";
    $message .= "<p>Password " . $password . "</p>";
    $message .= "<p>Please access the system and change your password, if you wish to.</p>";
    $message .= "<p>Click <a href='https://vbsa.org.au/VBSA_Extra_Login.php'>here </a>to access the site.</p>";
    $message .= "<p>Thanks.</p>";
    $message .= "<p>VBSA Webmaster.</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p><i>Direct all emails to <a href='mailto:web@vbsa.org.au'>web@vbsa.org.au</a></i></p>";
    $message .= "<img src='https://vbsa.org.au/images/image001.png' width = '220px' height = '140px'>";
    $message .= "</body></html>";
    Sendemail($subject, $message, $_GET['Email']);
}
else if($_GET['ForgotWhat'] == 'username')
{
    // forgotten username
    // get members id from email address
    $sql = "Select MemberID, FirstName from members where Email  = '" . $_GET['Email'] . "'";
    $result_select_memberid = $dbcnx_client->query($sql);
    $row_data = $result_select_memberid->fetch_assoc();
    $memberid = $row_data['MemberID'];
    $firstname = $row_data['FirstName'];
    // get new password
    $password = generatePassword(10);
    // get username
    $sql = "Select email_address from vbsaorga_users2 where vbsa_id  = " . $memberid;
    $result_select_rego = $dbcnx_client->query($sql);
    $row_email = $result_select_rego->fetch_assoc();
    $username = $row_email['email_address'];

    $sql = "Update vbsaorga_users2 set hashed_password = '" . password_hash($password, PASSWORD_DEFAULT) . "', password = '" . $password . "' where vbsa_id = " . $memberid; 
    //echo($sql . "<br>");           
    $update = $dbcnx_client->query($sql);

    // send email
    $subject = 'VBSA Affiliate System Access'; 
    $message = '<html><body>';
    $message .= "<p>" . $firstName . "</p>";
    $message .= "<p>Here are your log-in details for the VBSA Affiliate Portal.</p>";
    $message .= "<p>Email Address/Username " . $username . "</p>";
    $message .= "<p>New Password " . $password . "</p>";
    $message .= "<p>Please access the system and change your password, if you wish to.</p>";
    $message .= "<p>Click <a href='https://vbsa.org.au/VBSA_Extra_Login.php'>here </a>to access the site.</p>";
    $message .= "<p>Thanks.</p>";
    $message .= "<p>VBSA Webmaster.</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p><i>Direct all emails to <a href='mailto:web@vbsa.org.au'>web@vbsa.org.au</a></i></p>";
    $message .= "<img src='https://vbsa.org.au/images/image001.png' width = '220px' height = '140px'>";
    $message .= "</body></html>";
    Sendemail($subject, $message, $_GET['Email']);
}

function generatePassword($_len) 
{
  $_alphaSmall = 'abcdefghjkmnpqrstuvwxyz';               // small letters
  $_alphaCaps  = strtoupper($_alphaSmall);                // CAPITAL LETTERS
  $_numerics   = '123456789';                             // numerics
  //$_specialChars = '`~!@#$%^&*()-_=+]}[{;:,<.>/?\'"\|'; // Special Characters
  $_container = $_alphaSmall.$_alphaCaps.$_numerics;      // Contains all characters
  $password = '';                                         // will contain the desired pass
  for($i = 0; $i < $_len; $i++) {                         // Loop till the length mentioned
      $_rand = rand(0, strlen($_container) - 1);          // Get Randomized Length
      $password .= substr($_container, $_rand, 1);        // returns part of the string [ high tensile strength ;) ] 
  }
  return $password;                                       // Returns the generated Pass
}

function Sendemail($subject, $message, $email)
{
    // send email to admin
    $today = getdate();
    $month = $today['mon'];
    $mday = $today['mday'];
    $year = $today['year'];
    $hours = $today['hours'];
    $mins = $today['minutes'];
    $secs = $today['seconds'];

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'vk3eka@gmail.com'; 
    $mail->Password = 'hthotpvchnbppkqx';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('vk3eka@gmail.com', 'Database Admin');
    $mail->addReplyTo('scores@vbsa.org.au', 'Scores');
    if(($_SERVER['HTTP_HOST'] == 'localhost') OR ($_SERVER['HTTP_HOST'] == '172.16.10.16'))
    {
        $mail->addAddress('vk3eka@yahoo.com');
        $mail->addBCC('vk3eka@gmail.com'); 
    }
    else
    {
        $mail->addAddress($email);
        //$mail->addAddress('scores@vbsa.org.au');
        //$mail->addBCC('web@vbsa.org.au');
        //$mail->addBCC('vk3eka@gmail.com'); 
    }
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mailContent = $message;
    $mail->Body = $mailContent;
    if($mail->send())
    {
      echo("Your request has been sent to your registered email address. You will be redirected. to the login page.");
    }
    else
    {
      echo("Mail Not Sent!");
    }
}

?>