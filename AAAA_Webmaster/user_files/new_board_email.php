<?php
include ("../../vbsa_online_scores/connection.inc");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vbsa_online_scores/vendor/PHPMailer/src/Exception.php';
require '../../vbsa_online_scores/vendor/PHPMailer/src/PHPMailer.php';
require '../../vbsa_online_scores/vendor/PHPMailer/src/SMTP.php';

$hashed_pwd = password_hash($_GET['Password'], PASSWORD_DEFAULT);

$sql_insert = "Insert INTO vbsaorga_users (
board_member_id, 
name, 
username, 
hashed_password, 
usertype, 
board_desc, 
display, 
register_year, 
order_display, 
assist, 
comment) 
VALUES ('" .
$_GET['ID'] . "', '" . 
$_GET['Name'] . "', '" . 
$_GET['Email'] . "', '" . 
$hashed_pwd . "', '" . 
$_GET['UserType'] . "', '" . 
$_GET['BoardDesc'] . "', '" . 
$_GET['Display'] . "', '" . 
date('Y-m-d H:i:s') . "', '" . 
$_GET['Order'] . "', '" . 
$_GET['Assist'] . "', '" . 
$_GET['Comment'] . "')";  

//echo("Insert " . $sql_insert . "<br>");
$result_insert = $dbcnx_client->query($sql_insert);

// get members name from rego number
$sql = "Select * from members where MemberID  = " . $_GET['ID'];
$result_select_memberid = $dbcnx_client->query($sql);
$row_data = $result_select_memberid->fetch_assoc();
$fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);
// send email
$subject = 'VBSA Board System Access'; 
$message = '<html><body>';
$message .= "<p>" . $row_data['FirstName'] . "</p>";
$message .= "<p>Here are your log-in details for the VBSA Board Portal. You have been granted " . $_GET['UserType'] . " access.</p>";
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
      echo("Mail Sent!");
    }
    else
    {
      echo("Mail Not Sent!");
    }
}

?>