<?php require_once('../vendor/autoload.php');

$apiKey = 'mlsn.debfa225c919d40e1ae2cfe6dd6029b79b84c3bb381cb8e904b1d7d0b7dd087d';

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

echo("API " . $apiKey . "<br>");

$mailersend = new MailerSend(['api_key' => $apiKey]);

$recipients = [
    new Recipient('vk3eka@gmail.com', 'Peter Johnson'),
];

echo("<pre>");
echo(var_dump($recipients));
echo("</pre>");

$emailParams = (new EmailParams())
    ->setFrom('scores@vbsa.org.au')
    ->setFromName('PJ')
    ->setRecipients($recipients)
    ->setSubject('Subject')
    ->setHtml('This is the HTML content')
    ->setText('This is the text content')
    ->setReplyTo('scores@vbsa.org.au')
    ->setReplyToName('Peter Johnson');

$mailersend->email->send($emailParams);

?>