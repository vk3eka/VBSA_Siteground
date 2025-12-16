<?php 

//include('/Users/peterj/Sites/VBSA_Siteground/vendor/autoload.php');
//include('/public_html/vendor/autoload.php');
include('../vendor/autoload.php');

error_reporting(0);

$apiKey = 'mlsn.84fc4e7e7c09644af7862638f47a5b8bab3de59623e1467660aa8c37f5e9d46e';

//echo("PHP_Functions Loaded<br>");

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

function SendEmailMS($subject, $message, $email)
{
    global $apiKey;
    $mailersend = new MailerSend(['api_key' => $apiKey]);

    $recipients = [
            new Recipient('vk3eka@gmail.com', 'Peter Johnson'),
    ];

    $BCC = [
            new Recipient('vk3eka@bigpond.net.au', 'Peter W Johnson'),
    ];

    if(($_SERVER['HTTP_HOST'] == 'localhost') OR ($_SERVER['HTTP_HOST'] == '172.16.10.32'))
    {
        $emailParams = (new EmailParams())
            ->setFrom('scores@vbsa.org.au')
            ->setFromName('VBSA Scores Registrar')
            ->setSubject($subject)
            ->setHtml($message)
            ->setReplyTo('scores@vbsa.org.au')
            ->setReplyToName('VBSA Scores Registrar')
            ->setBcc($BCC)
            ->setRecipients($recipients);
    }
    else
    {
        $emailParams = (new EmailParams())
            ->setFrom('scores@vbsa.org.au')
            ->setFromName('VBSA Scores Registrar')
            ->setSubject($subject . " (Test)")
            ->setHtml($message)
            ->setReplyTo('scores@vbsa.org.au')
            ->setReplyToName('VBSA Scores Registrar')
            ->setBcc($BCC)
            ->setRecipients($recipients);
    }
    
    try
    {
        $mailersend->email->send($emailParams);
        echo "<script type='text/javascript'>";
        echo "alert('Mail Sent!')";
        echo "</script>";
    } 
    catch (Exception $e) {
        echo "<script type='text/javascript'>";
        echo "alert('Mail not sent, Error: " . $e->getMessage() . "')";
        echo "</script>";
    }
}
 
function Sendemail($subject, $message, $email)
{
    global $apiKey;
    $mailersend = new MailerSend(['api_key' => $apiKey]);

    if(($_SERVER['HTTP_HOST'] == 'localhost') OR ($_SERVER['HTTP_HOST'] == '172.16.10.32'))
    {
        $recipients = [
            new Recipient('vk3eka@yahoo.com', 'Peter Johnson'),
        ];
        $emailParams = (new EmailParams())
            ->setFrom('scores@vbsa.org.au')
            ->setFromName('VBSA Scores Registrar')
            ->setSubject($subject)
            ->setHtml($message)
            ->setReplyTo('scores@vbsa.org.au')
            ->setReplyToName('VBSA Scores Registrar')
            ->setRecipients($recipients);
    }
    else
    {
        $recipients = [
            new Recipient($email, ''),
        ];
        $BCC = [
            new Recipient('vk3eka@yahoo.com', 'Peter W Johnson'),
        ];
        $emailParams = (new EmailParams())
            ->setFrom('scores@vbsa.org.au')
            ->setFromName('VBSA Scores Registrar')
            ->setSubject($subject)
            ->setHtml($message)
            ->setReplyTo('scores@vbsa.org.au')
            ->setReplyToName('VBSA Scores Registrar')
            ->setRecipients($recipients);
    }
    
    try
    {
        $mailersend->email->send($emailParams);
        echo("Mail Sent");
    } 
    catch (Exception $e) {
        echo "Mail not sent, Error: " . $e->getMessage();
    }
}

function SendemailWithResponse($subject, $message, $email)
{
    global $apiKey;
    $mailersend = new MailerSend(['api_key' => $apiKey]);

    if(($_SERVER['HTTP_HOST'] == 'localhost') OR ($_SERVER['HTTP_HOST'] == '172.16.10.32'))
    {
        $recipients = [
            new Recipient('vk3eka@yahoo.com', 'Peter Johnson'),
        ];
        $emailParams = (new EmailParams())
            ->setFrom('scores@vbsa.org.au')
            ->setFromName('VBSA Scores Registrar')
            ->setSubject($subject)
            ->setHtml($message)
            ->setReplyTo('scores@vbsa.org.au')
            ->setReplyToName('VBSA Scores Registrar')
            ->setRecipients($recipients);
    }
    else
    {
        $recipients = [
            new Recipient($email, ''),
        ];
        $BCC = [
            new Recipient('vk3eka@yahoo.com', 'Peter W Johnson'),
        ];
        $emailParams = (new EmailParams())
            ->setFrom('scores@vbsa.org.au')
            ->setFromName('VBSA Scores Registrar')
            ->setSubject($subject)
            ->setHtml($message)
            ->setReplyTo('scores@vbsa.org.au')
            ->setReplyToName('VBSA Scores Registrar')
            ->setBcc($BCC)
            ->setRecipients($recipients);
    }
    $mailersend->email->send($emailParams);
}

function SendemailWithResponse_Members($subject, $message, $email)
{
    global $apiKey;
    $mailersend = new MailerSend(['api_key' => $apiKey]);

    if(($_SERVER['HTTP_HOST'] == 'localhost') OR ($_SERVER['HTTP_HOST'] == '172.16.10.32'))
    {
        $recipients = [
            new Recipient('vk3eka@yahoo.com', 'Peter Johnson'),
        ];
        $emailParams = (new EmailParams())
            ->setFrom('members@vbsa.org.au')
            ->setFromName('VBSA Membership Co-ordinator')
            ->setSubject($subject)
            ->setHtml($message)
            ->setReplyTo('members@vbsa.org.au')
            ->setReplyToName('VBSA Membership Co-ordinator')
            ->setRecipients($recipients);
    }
    else
    {
        $recipients = [
            new Recipient($email, ''),
        ];
        $BCC = [
            new Recipient('vk3eka@yahoo.com', 'Peter W Johnson'),
        ];
        $emailParams = (new EmailParams())
            ->setFrom('members@vbsa.org.au')
            ->setFromName('VBSA Membership Co-ordinator')
            ->setSubject($subject)
            ->setHtml($message)
            ->setReplyTo('members@vbsa.org.au')
            ->setReplyToName('VBSA Membership Co-ordinator')
            ->setBcc($BCC)
            ->setRecipients($recipients);
    }
    $mailersend->email->send($emailParams);
}


function SendBulkEmail($subject, $message, $email)
{
    global $apiKey;
    $mailersend = new MailerSend(['api_key' => $apiKey]);

    if(($_SERVER['HTTP_HOST'] == 'localhost') OR ($_SERVER['HTTP_HOST'] == '172.16.10.32'))
    {
        $recipients = [
            new Recipient('vk3eka@yahoo.com', 'Peter Johnson'),
        ];
        $emailParams = (new EmailParams())
            ->setFrom('scores@vbsa.org.au')
            ->setFromName('VBSA Scores Registrar')
            ->setSubject($subject)
            ->setHtml($message)
            ->setReplyTo('scores@vbsa.org.au')
            ->setReplyToName('VBSA Scores Registrar')
            ->setRecipients($recipients);
    }
    else
    {
        $recipients = [
            new Recipient($email, ''),
        ];
        $BCC = [
            new Recipient('vk3eka@yahoo.com', 'Peter W Johnson'),
        ];
        $emailParams = (new EmailParams())
            ->setFrom('scores@vbsa.org.au')
            ->setFromName('VBSA Scores Registrar')
            ->setSubject($subject)
            ->setHtml($message)
            ->setReplyTo('scores@vbsa.org.au')
            ->setReplyToName('VBSA Scores Registrar')
            ->setBcc($BCC)
            ->setRecipients($recipients);
    }
    try
    {
        $mailersend->email->send($emailParams);
        //echo "<script type='text/javascript'>";
        //echo "alert('Mail Sent!')";
        //echo "</script>";
    } 
    catch (Exception $e) {
        echo "<script type='text/javascript'>";
        echo "alert('Mail not sent, Error: " . $e->getMessage() . "')";
        echo "</script>";
    }
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