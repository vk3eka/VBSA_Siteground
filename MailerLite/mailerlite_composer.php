<?php
require_once('../vendor/autoload.php');

$apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiN2JlMmM1NDA0ODljMjUwMjUyODI2ODQ5MTQyNmJjYTVkYzBjZjAwOTJmZGQzNDFhYTc3NjkxMmU3YzNmNTQ1NDkxNTMxMzFiOTk2ZWQxOTEiLCJpYXQiOjE3MzI5MzMwNDkuOTkzMTc0LCJuYmYiOjE3MzI5MzMwNDkuOTkzMTc3LCJleHAiOjQ4ODg2MDY2NDkuOTg5MzgzLCJzdWIiOiIxMjI0MzAzIiwic2NvcGVzIjpbXX0.XU2PE1WSL7HYhPy12EzrHT_BR0uxBDi15Z81Ujopd9oco8Kc1ACu3_xFSrsktV9pK1yQfcao0AqAii4xdrKhisvhKzVbMn5eMvQPOJGW62vwRAp-KMvak5Ckr4hvxEGu8i2k5NpeFqLXMwbiAxsI59eMJD6XWhmAi9qOYgRh0V9W4VWbkba5HavyH-we1edlE4JTcZeyDVXR0NGDnPmn-1VxC0RHIwlZNVkwsz8FNlvnr4tefNBBxfRuuEsOY9vtDqEJ0E_8z8rFv0j_ldvQsdJr_qaOXvJYMdlU7oUiMPaj86ypItY6kmtsENdy66f8Iounce-lkTVfbkEzRN4dPlhVd396Nhcm-QW7qT7XeG-f0NGQDUUJuhVAVj1ZnbccQUph0lvFQEDwhA4oXvXvpZGhIuknSEpgA9t7cbmotrgjhrBYUl67ML9AVcWCXFGK7-CgIED2rYFa160Ws9ZEqYf6jl7P50Cia13Kqy8ebMuasQXShslkke5gbFvVMq6jgLrWniRONNSBng4-ZfNzwtRMe-iTVEP-Lc8exPSpwaX12-OaknqxLD7Lde_vQSmHNkJfAUdQWt8D5W-6m567lqBDHHy90X6mPoNRrLI-QwGXdnp37WY9zhzKBL1k6NDc0ybeLKgSpGdRemg6q0klE7PFLYpEcFJKfHGYIqGuRJg';

// Replace with your group ID
$groupId = '139391020632638839';

// Set the API endpoint
//$url = "https://connect.mailerlite.com/api/v2/groups/" .$groupId . "/subscribers";
//$url = 'https://connect.mailerlite.com/api/groups/' .$groupId . '/subscribers';
$url = 'https://api.mailerlite.com/api/v2/groups';

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $apiKey
]);

// Execute the request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Decode the JSON response
    $subscribers = json_decode($response, true);

    echo("<pre>");
    echo(var_dump($subscribers));
    echo("</pre>");

    // Display subscribers
    if (is_array($subscribers)) {
        foreach ($subscribers as $subscriber) {
            echo "Name: " . $subscriber['name'] . ", Email: " . $subscriber['email'] . "<br>";
        }
    } else {
        echo "No subscribers found.";
    }
}

// Close cURL session
curl_close($ch);
/*


// Set your MailerLite API key here
//$apiKey = 'your-mailerlite-api-key';

// MailerLite API URL for subscribers
$url = 'https://api.mailerlite.com/api/v2/subscribers';

// Set up headers for the API request
$headers = [
    'Content-Type: application/json',
    'X-MailerLite-ApiKey: ' . $apiKey
];

// Use cURL to send the GET request to MailerLite API
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if needed

$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

curl_close($ch);

// Decode JSON response
$subscribers = json_decode($response, true);

echo("<pre>");
echo(var_dump($subscribers));
echo("</pre>");


// Check if the API call was successful
if (isset($subscribers['error'])) {
    echo 'Error: ' . $subscribers['error'];
} else {
    // Iterate through the subscribers and display their data
    foreach ($subscribers as $subscriber) {
        echo 'Email: ' . $subscriber['email'] . "\n";
        echo 'Name: ' . (isset($subscriber['name']) ? $subscriber['name'] : 'N/A') . "\n";
        echo 'Created At: ' . $subscriber['date_created'] . "\n";
        echo '------------------------------------' . "\n";
    }
}
*/
?>