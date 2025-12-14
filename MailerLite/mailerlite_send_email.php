<?php require_once('../vendor/autoload.php');

//$apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiN2JlMmM1NDA0ODljMjUwMjUyODI2ODQ5MTQyNmJjYTVkYzBjZjAwOTJmZGQzNDFhYTc3NjkxMmU3YzNmNTQ1NDkxNTMxMzFiOTk2ZWQxOTEiLCJpYXQiOjE3MzI5MzMwNDkuOTkzMTc0LCJuYmYiOjE3MzI5MzMwNDkuOTkzMTc3LCJleHAiOjQ4ODg2MDY2NDkuOTg5MzgzLCJzdWIiOiIxMjI0MzAzIiwic2NvcGVzIjpbXX0.XU2PE1WSL7HYhPy12EzrHT_BR0uxBDi15Z81Ujopd9oco8Kc1ACu3_xFSrsktV9pK1yQfcao0AqAii4xdrKhisvhKzVbMn5eMvQPOJGW62vwRAp-KMvak5Ckr4hvxEGu8i2k5NpeFqLXMwbiAxsI59eMJD6XWhmAi9qOYgRh0V9W4VWbkba5HavyH-we1edlE4JTcZeyDVXR0NGDnPmn-1VxC0RHIwlZNVkwsz8FNlvnr4tefNBBxfRuuEsOY9vtDqEJ0E_8z8rFv0j_ldvQsdJr_qaOXvJYMdlU7oUiMPaj86ypItY6kmtsENdy66f8Iounce-lkTVfbkEzRN4dPlhVd396Nhcm-QW7qT7XeG-f0NGQDUUJuhVAVj1ZnbccQUph0lvFQEDwhA4oXvXvpZGhIuknSEpgA9t7cbmotrgjhrBYUl67ML9AVcWCXFGK7-CgIED2rYFa160Ws9ZEqYf6jl7P50Cia13Kqy8ebMuasQXShslkke5gbFvVMq6jgLrWniRONNSBng4-ZfNzwtRMe-iTVEP-Lc8exPSpwaX12-OaknqxLD7Lde_vQSmHNkJfAUdQWt8D5W-6m567lqBDHHy90X6mPoNRrLI-QwGXdnp37WY9zhzKBL1k6NDc0ybeLKgSpGdRemg6q0klE7PFLYpEcFJKfHGYIqGuRJg';

$apiKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiNDkyOTE0OGM3NmE2YmNjYmM3MTdlNzczNTNhZGFlMDk2ZjhkYmI1NDE3MzM5OTJmYjFhOGU1ZWIwOWRlMjczMWNjYTBmOTUyYjUxNDY1YzUiLCJpYXQiOjE3MzMzNjQ1MTAuMDQyNjQsIm5iZiI6MTczMzM2NDUxMC4wNDI2NDMsImV4cCI6NDg4OTAzODExMC4wMzkxMTksInN1YiI6IjEyMjk5NzciLCJzY29wZXMiOltdfQ.jGQWUkbdZ-shyGyvzxS0gqN9kgLWS0Xl5YSV4qfQwuajdv9NaS_9e_Ax2sYLXl-TWjDAhDhell2Y348uUgZJDTI-4Q6dK6_6oPVYfO2kd74MCWrq-vq6SvYfICuMv07z61QegbuaEG5e7-spaLgXVIykjiuqwPPGLQMN6lDkHonRWFrxOi7xcxcsapkYayVeYHwq7w8A7Ntnhsljn2zO31NcGyKpp7I5wLtHGqCtz1UC3s0HZizQ6R70PHa3EUuX-2g6CtyFoWyf517_XoApYotcJFcWYF5piQT2AIAj1tKxAa8QRX4yBmV0dRcnrJzTQCEQYY5QszHkZgaQqXngYytK_0V8xddBpyPEUnRJz4fTaYNkF36cB_7bAbTjKYF5luFPJz1qIjltSeSpBMNJq_5vnJgBpq93EkAQzzD_Wcgg-wwOTCaCikfKJ9R4g-Jz6c67R-AhJCB81HIBInlNAYoorujPEfKE6YLbb3rh4kaGLurlo_yi-RbB-vLVTgb0smScf6CBsXF470hxchjO4LrUZXxVMvw2dDL44-Lzwcb49PtOhdR1GiDfaCyetlugz6LXSBQD0cBkPuwyoZxQmenNAW-KEFPP7bm1ev6Qh8VbYDeDjXqg85R8N28wlSlqoFtXLLZK-ZEtkaSCNylh3tTcnC8bG0jWczlvst3DS0I";


use GuzzleHttp\Client;

// Create the Guzzle client
$client = new Client([
    'base_uri' => 'https://api.mailerlite.com/api/v2/',
    'headers' => [
        'X-MailerLite-ApiKey' => $apiKey,
        'Content-Type' => 'application/json'
    ]
]);

// API v2 endpoint for sending an email
//$apiUrl = 'https://api.mailerlite.com/api/v2/campaign/schedule';

// Email payload
$emailData = [
    'subject' => 'Welcome Email',
    'from' => [
        'email' => 'president@vbsa.org.au',
        'name' => 'Peter Johnson'
    ],
    'to' => [
        [
            'email' => 'vk3eka@bigpond.net.au',
            'name' => 'Peter W Johnson'
        ]
    ],
    'content' => [
        'html' => '<h1>Hello!</h1><p>Welcome to our service.</p>',
        'plain' => 'Hello! Welcome to our service.'
    ],
    'delivery' => 'instant',
];


try {
    // Make the POST request to the MailerLite API endpoint for sending emails
    $response = $client->post('send', [
        'json' => $emailData
    ]);

    // Get the response body
    $responseBody = json_decode($response->getBody(), true);

    if ($response->getStatusCode() == 200) {
        echo 'Email sent successfully!';
    } else {
        echo 'Error sending email. Response: ' . $responseBody['message'];
    }
} catch (\GuzzleHttp\Exception\RequestException $e) {
    // Handle error in the request
    echo 'Error: ' . $e->getMessage();
}

/*
// Initialize cURL
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request and get the response
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

// Close cURL
curl_close($ch);
*/
?>
