<?php
error_reporting(0);

$secret = $_POST['secret'];
$response = $_POST['response'];
$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $response;
$verify = json_decode(file_get_contents($url));
if($verify->success == 1)
{
	echo('true');
}
else
{
	echo('false');
}

//echo($verify);
?>
