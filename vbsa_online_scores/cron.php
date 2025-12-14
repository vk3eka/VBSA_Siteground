<?php
error_reporting(0); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require  __DIR__ . '/vendor/PHPMailer/src/Exception.php';
require  __DIR__ . '/vendor/PHPMailer/src/PHPMailer.php';
require  __DIR__ . '/vendor/PHPMailer/src/SMTP.php';

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
    if(($_SERVER['HTTP_HOST'] == 'localhost') OR ($_SERVER['HTTP_HOST'] == '172.16.10.16'))
    {
        $mail->addAddress('vk3eka@yahoo.com');
        $mail->addBCC('vk3eka@gmail.com'); 
    }
    else
    {
        $mail->addAddress($email);
    }
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mailContent = $message;
    $mail->Body = $mailContent;
    $mail->send();
}

date_default_timezone_set('Australia/Melbourne');
//$date = date('d-m-y h:i:s');

function AuthoriseEmail()
{
    $date = date('Y-m-d h:i:s');
    $subject = 'VBSA CRON Job Test'; 
    $message = '<html><body>';
    $message .= "<p>Peter</p>";
    $message .= "<p>This is a test of the cron job function in Siteground</p>";
    $message .= "<p>It was sent on " . $date . "</p>";
    $message .= "<p>Regards. Siteground Cron.</p>";
    $message .= "</body></html>";
    Sendemail($subject, $message, 'vk3eka@yahoo.com');
}

AuthoriseEmail();

