<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require  'http://172.16.10.16/VBSA_Siteground/vendor/PHPMailer/src/Exception.php';
require  'http://172.16.10.16/VBSA_Siteground/vendor/PHPMailer/src/PHPMailer.php';
require  'http://172.16.10.16/VBSA_Siteground/vendor/PHPMailer/src/SMTP.php';

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
        $mail->addAddress('scores@vbsa.org.au');
        $mail->addBCC('web@vbsa.org.au');
        $mail->addBCC('vk3eka@gmail.com'); 
    }
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mailContent = $message;
    $mail->Body = $mailContent;
    if($mail->send())
    {
        echo "<script type='text/javascript'>";
        echo "alert('Mail Sent!')";
        echo "</script>";
    }
    else
    {
        echo "<script type='text/javascript'>";
        echo "alert('Mail not sent, Error: " . $mail->ErrorInfo . "')";
        echo "</script>";
    }
}

function SendBulkEmail($subject, $message, $email)
{
    // send email to captain
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
        $mail->addAddress('scores@vbsa.org.au');
        $mail->addBCC('web@vbsa.org.au');
        $mail->addBCC('vk3eka@gmail.com'); 
    }
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mailContent = $message;
    $mail->Body = $mailContent;
    $mail->send();
}

// get date in Aus format
function DisplayDate($mysql_date) 
{
    if ($mysql_date == "") {
        $display_date = "";
    }
    else
    {
      $mysql_date = strtotime($mysql_date);
      $display_date = getdate($mysql_date);
      $day = $display_date['mday'];
      $mon = $display_date['mon'];
      if ($day < 10){
        $day = '0' . $display_date['mday'];
      }
      if ($mon < 10) {
        $mon = '0' . $display_date['mon'];
      }
      $year = $display_date['year'];
      $display_date = $day . "-" . $mon . "-" . $year;
    }
    return $display_date;
}

// get date in mysql format
function MySqlDate($html_date) 
{
    if ($html_date == "") {
        $mysql_date = "";
    }
    else
    {
      $html_date = strtotime($html_date);
      $mysql_date = getdate($html_date);
      $day = $mysql_date['mday'];
      $mon = $mysql_date['mon'];
      if ($day < 10){
        $day = '0' . $mysql_date['mday'];
      }
      if ($mon < 10) {
        $mon = '0' . $mysql_date['mon'];
      }
      $year = $mysql_date['year'];
      $mysql_date = $year . "-" . $mon . "-" . $day;
    }
    return $mysql_date;
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

?>