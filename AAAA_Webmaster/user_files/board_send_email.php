<?php
include ("../../vbsa_online_scores/connection.inc");
include ("../../vbsa_online_scores/php_functions.php");
/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vbsa_online_scores/vendor/PHPMailer/src/Exception.php';
require '../../vbsa_online_scores/vendor/PHPMailer/src/PHPMailer.php';
require '../../vbsa_online_scores/vendor/PHPMailer/src/SMTP.php';
*/
//$sql = "Select board_member_id from vbsaorga_users where board_member_id  = " . $_GET['MemberID'];
//echo($sql . "<br>");
//$result_select_rego = $dbcnx_client->query($sql);
//$row_email = $result_select_rego->fetch_assoc();
//echo($row_email['board_member_id'] . "<br>");

//echo("Email " . $_GET['Email'] . "<br>");
// update new password
$sql = "Update vbsaorga_users SET hashed_password = '" . password_hash($_GET['Password'], PASSWORD_DEFAULT) . "' WHERE board_member_id = '" . $_GET['MemberID'] . "'";
$update = $dbcnx_client->query($sql);
//echo($sql . "<br>");
// get members name from rego number
$sql = "Select * from members where MemberID  = " . $_GET['MemberID'];
$result_select_memberid = $dbcnx_client->query($sql);
$row_data = $result_select_memberid->fetch_assoc();
$fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);
// send email
$subject = 'VBSA Admin System Access'; 
$message = '<html><body>';
$message .= "<p>" . $row_data['FirstName'] . "</p>";
$message .= "<p>Here are your log-in details for the VBSA Admin Portal. You have been granted " . $_GET['UserType'] . " access.</p>";
$message .= "<p>Username " . $_GET['UserName'] . "</p>";
$message .= "<p>Password " . $_GET['Password'] . "</p>";
$message .= "<p>Please access the system and change your password, if you wish to.</p>";
$message .= "<p>Click <a href='https://vbsa.org.au/VBSA_Admin_Login.php'>here </a>to access the site.</p>";
$message .= "<p>Thanks.</p>";
$message .= "<p>VBSA Webmaster.</p>";
$message .= "<p>&nbsp;</p>";
$message .= "<p><i>Direct all emails to <a href='mailto:web@vbsa.org.au'>web@vbsa.org.au</a></i></p>";
$message .= "<img src='https://vbsa.org.au/images/image001.png' width = '220px' height = '140px'>";
$message .= "</body></html>";
//echo($message . "<br>");
Sendemail($subject, $message, $_GET['Email']);
//SendEmailMS($subject, $message, $email)

/*
function Sendemail($subject, $message, $email)
{
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
    $mail->Username = 'vbsamailer@gmail.com'; 
    $mail->Password = 'cnpw pdao abct blnb';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('vbsamailer@gmail.com', 'VBSA Scores Registrar');
    $mail->addReplyTo('scores@vbsa.org.au', 'Scores Registrar');
    if(($_SERVER['HTTP_HOST'] == 'localhost') OR ($_SERVER['HTTP_HOST'] == '172.16.10.32'))
    {
        $mail->addAddress('vk3eka@yahoo.com');
        $mail->addBCC('vk3eka@gmail.com'); 
    }
    else
    {
        $mail->addAddress($email);
        $mail->addBCC('vk3eka@gmail.com'); 
    }
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mailContent = $message;
    $mail->Body = $mailContent;
    if($mail->send())
    {
        echo "Mail Sent!";
    }
    else
    {
        echo "Mail not sent, Error: " . $mail->ErrorInfo . "'";
    }
}
*/
?>