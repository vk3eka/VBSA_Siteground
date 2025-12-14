<?php 
require_once('../Connections/connvbsa.php'); 
include('../vbsa_online_scores/php_functions.php'); 

function SREmail($email)
{
   $subject = 'VBSA Add Captain'; 
   $message = '<html><body>';
   $message .= "<p>The team Yarraville has been entered in the PB(2) Grade for S2 of 2024.</p>";
   $message .= "<p>The Team Captain is Fred Bloggs at <a href=mailto:vk3eka@yahoo.com>vk3eka@yahoo.com</a></p>";
   $message .= "<p>Team Registration SEWS.</p>";
   $message .= "<p>" . date('d/m/Y') . "</p>";
   $message .= "</body></html>";
   SendEmailMS($subject, $message, $email);
}

SREmail('vk3eka@yahoo.com');


