<?php
require_once('../vendor/autoload.php');

error_reporting(0);

$mailchimp = new \MailchimpMarketing\ApiClient();

$api_key = '47ee1caeef220fd3a248ebdb9e098a15-us21';
$server_prefix = 'us21';
$list_id = 'f8d521b97d';

$mailchimp->setConfig([
	'apiKey' => $api_key,
	'server' => $server_prefix
]);


$email = "vk3eka@yahoo.com";
$subscriber_hash = md5(strtolower($email));

try 
{
  $mailchimp->lists->updateListMemberTags($list_id, $subscriber_hash, [
    "tags" => [
      [
        "name" => "Test Tag",
        "status" => "inactive"      
      ]
    ]
  ]);
  echo "The return type for this endpoint is null";
} 
catch (MailchimpMarketing\ApiException $e) 
{
    echo $e->getMessage();
}

?>
