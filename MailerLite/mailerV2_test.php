<?php
require_once('../vendor/autoload.php');
require_once('../Connections/connvbsa.php'); 

// local
//$apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiN2JlMmM1NDA0ODljMjUwMjUyODI2ODQ5MTQyNmJjYTVkYzBjZjAwOTJmZGQzNDFhYTc3NjkxMmU3YzNmNTQ1NDkxNTMxMzFiOTk2ZWQxOTEiLCJpYXQiOjE3MzI5MzMwNDkuOTkzMTc0LCJuYmYiOjE3MzI5MzMwNDkuOTkzMTc3LCJleHAiOjQ4ODg2MDY2NDkuOTg5MzgzLCJzdWIiOiIxMjI0MzAzIiwic2NvcGVzIjpbXX0.XU2PE1WSL7HYhPy12EzrHT_BR0uxBDi15Z81Ujopd9oco8Kc1ACu3_xFSrsktV9pK1yQfcao0AqAii4xdrKhisvhKzVbMn5eMvQPOJGW62vwRAp-KMvak5Ckr4hvxEGu8i2k5NpeFqLXMwbiAxsI59eMJD6XWhmAi9qOYgRh0V9W4VWbkba5HavyH-we1edlE4JTcZeyDVXR0NGDnPmn-1VxC0RHIwlZNVkwsz8FNlvnr4tefNBBxfRuuEsOY9vtDqEJ0E_8z8rFv0j_ldvQsdJr_qaOXvJYMdlU7oUiMPaj86ypItY6kmtsENdy66f8Iounce-lkTVfbkEzRN4dPlhVd396Nhcm-QW7qT7XeG-f0NGQDUUJuhVAVj1ZnbccQUph0lvFQEDwhA4oXvXvpZGhIuknSEpgA9t7cbmotrgjhrBYUl67ML9AVcWCXFGK7-CgIED2rYFa160Ws9ZEqYf6jl7P50Cia13Kqy8ebMuasQXShslkke5gbFvVMq6jgLrWniRONNSBng4-ZfNzwtRMe-iTVEP-Lc8exPSpwaX12-OaknqxLD7Lde_vQSmHNkJfAUdQWt8D5W-6m567lqBDHHy90X6mPoNRrLI-QwGXdnp37WY9zhzKBL1k6NDc0ybeLKgSpGdRemg6q0klE7PFLYpEcFJKfHGYIqGuRJg';

// vbsa
$apiKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiNDkyOTE0OGM3NmE2YmNjYmM3MTdlNzczNTNhZGFlMDk2ZjhkYmI1NDE3MzM5OTJmYjFhOGU1ZWIwOWRlMjczMWNjYTBmOTUyYjUxNDY1YzUiLCJpYXQiOjE3MzMzNjQ1MTAuMDQyNjQsIm5iZiI6MTczMzM2NDUxMC4wNDI2NDMsImV4cCI6NDg4OTAzODExMC4wMzkxMTksInN1YiI6IjEyMjk5NzciLCJzY29wZXMiOltdfQ.jGQWUkbdZ-shyGyvzxS0gqN9kgLWS0Xl5YSV4qfQwuajdv9NaS_9e_Ax2sYLXl-TWjDAhDhell2Y348uUgZJDTI-4Q6dK6_6oPVYfO2kd74MCWrq-vq6SvYfICuMv07z61QegbuaEG5e7-spaLgXVIykjiuqwPPGLQMN6lDkHonRWFrxOi7xcxcsapkYayVeYHwq7w8A7Ntnhsljn2zO31NcGyKpp7I5wLtHGqCtz1UC3s0HZizQ6R70PHa3EUuX-2g6CtyFoWyf517_XoApYotcJFcWYF5piQT2AIAj1tKxAa8QRX4yBmV0dRcnrJzTQCEQYY5QszHkZgaQqXngYytK_0V8xddBpyPEUnRJz4fTaYNkF36cB_7bAbTjKYF5luFPJz1qIjltSeSpBMNJq_5vnJgBpq93EkAQzzD_Wcgg-wwOTCaCikfKJ9R4g-Jz6c67R-AhJCB81HIBInlNAYoorujPEfKE6YLbb3rh4kaGLurlo_yi-RbB-vLVTgb0smScf6CBsXF470hxchjO4LrUZXxVMvw2dDL44-Lzwcb49PtOhdR1GiDfaCyetlugz6LXSBQD0cBkPuwyoZxQmenNAW-KEFPP7bm1ev6Qh8VbYDeDjXqg85R8N28wlSlqoFtXLLZK-ZEtkaSCNylh3tTcnC8bG0jWczlvst3DS0I";

use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => $apiKey]);
/*
// batch update group members
mysql_select_db($database_connvbsa, $connvbsa);
// test members
$query_audience = "Select MemberID, LastName, FirstName, Email FROM test_members WHERE ReceiveEmail = 1 AND Email != ''";
$audience = mysql_query($query_audience, $connvbsa) or die(mysql_error());
$groupId = '140462839340991556';

$data = [
    'requests' => []
];

while ($row_audience = mysql_fetch_assoc($audience))
{
    $email = $row_audience['Email'];
    $first_name = $row_audience['FirstName'];
    $last_name = $row_audience['LastName'];
    $member_id = $row_audience['MemberID'];
    
    $data['requests'][] = [
            "method" => "POST",
            "path" => "api/subscribers",
            'body' => [
                'email' => $email,
                'status' => 'active', // or 'unsubscribed'
                'fields' => [
                    'memberid' => $member_id,
                    'name' => $first_name,
                    'last_name' => $last_name
                ],
                'groups' => [$groupId],
            ],
        ];
}

//echo("<pre>");
//echo(var_dump($data));
//echo("</pre>");

$response = $mailerLite->batches->send($data);

echo("<pre>");
echo(var_dump($response));
echo("</pre>");
*/

/*
try 
{
    $data = [
        'requests' => [
            [
                "method" => "POST",
                "path" => "api/subscribers",
                'body' => [
                    'email' => 'user1@example.com',
                    'fields' => [
                        'name' => 'User',
                        'last_name' => 'One'],
                    'groups' => [$groupId],
                ],
            ],
            [
                "method" => "POST",
                "path" => "api/subscribers",
                'body' => [
                    'email' => 'user2@example.com',
                    'fields' => [
                        'name' => 'User',
                        'last_name' => 'Two'],
                    'groups' => [$groupId],
                ],
            ],
        ],
    ];
    $response = $mailerLite->batches->send($data);

    echo("<pre>");
    echo(var_dump($response));
    echo("</pre>");
} 
catch (Exception $e) 
{
    echo "Error: " . $e->getMessage();
}
*/



// add a subscriber
/*
$data = [
    'body' => [
        'email' => 'user2@example.com',
        'fields' => [
            'name' => 'User',
            'last_name' => 'Two'],
        'groups' => [$groupId],
    ],
];
$response = $mailerLite->subscribers->create($data);

//echo("<pre>");
//echo(var_dump($response));
//echo("</pre>");
*/


/*
// create and send campaign
$groupID = '139843024618259498';
// send email
$data = [
    'type' => 'regular',
    'name' => 'My new campaign 2',
    'emails' => [
        [
            'subject' => 'My new email',
            'from_name' => 'Peter Johnson',
            'from' => 'president@vbsa.org.au',
            'content' => 'Hello World!',
        ]
    ],
    'list_ids' => $groupID,
    'template' => [
        'id' => 7855567
    ]
];
*/
//$response = $mailerLite->campaigns->create($data);
//echo "Campaign Created:<br>";
/*
$data = [
    'filter' => ['status' => 'sent'],
];

$response = $mailerLite->campaigns->get($data);
*/
//echo("<pre>");
//echo(var_dump($response));
//echo("</pre>");
/*oreach($response['body']['data'] as $group)
{
    echo("ID " . $group['id'] . "<br>"); //  groups 
}*/
/*
$data = [
    'body' => [
        'email' => 'user2@example.com',
        'fields' => [
            'name' => 'User',
            'last_name' => 'Two'],
        'groups' => [$groupId],
    ],
];
*/
/*
$data = [
    'body' => [
        'data' => [
            'name' => 'Test VBSA Version 2',
        ],
    ]      
];
$campaignId = $mailerLite->campaigns->get($data);
foreach($response['body']['data'] as $group)
{
    //echo("Name " . $group['fields']['name'] . " " . $group['fields']['last_name'] . ", email " . $group['email'] . ", ID " . $group['id'] . "<br>"); // members 
    echo("Name " . $group['name'] . ", ID " . $group['id'] . "<br>"); //  groups 
}
*/
//if ($campaignId) {
//    echo "Campaign ID ': {$campaignId}\n";
//} else {
    //echo "Campaign '{$campaignName}' not found.\n";
//}

/*
echo("<pre>");
echo(var_dump($response));
echo("</pre>");


$data = [
    "delivery" => "instant",
];
$response = $mailerLite->campaigns->get($data);
echo("<pre>");
echo(var_dump($response));
echo("</pre>");
//$campaignID = '142297598881957412';
//$campaignID = '142294421836138060';
//$sendResponse = $mailerLite->campaigns->schedule($campaignID, $data);



// list all subscribers
/*
$data = [
        'email' => 'vk3eka@yahoo.com',
    ];
$response = $mailerLite->subscribers->find('vk3eka@yahoo.com');
echo("<pre>");
echo(var_dump($response));
echo("</pre>");
*/


// delete a group
//$groupId = '139674940507948858';
//$response = $mailerLite->groups->delete($groupId);


/*
// list all groups
$response = $mailerLite->groups->get();
if (is_array($response['body']['data']) || $response['body']['data'] instanceof Traversable) 
{
    foreach($response['body']['data'] as $group)
    {
        //echo("Name " . $group['fields']['name'] . " " . $group['fields']['last_name'] . ", email " . $group['email'] . ", ID " . $group['id'] . "<br>"); // members 
        echo("Name " . $group['name'] . ", ID " . $group['id'] . "<br>"); //  groups 
    }
} 
else 
{
    echo 'No groups found or response format is unexpected.';
}
//echo("<pre>");
//echo(var_dump($response));
//echo("</pre>");
*/

/*
// get a group
//$groupId = '139391020632638839'; // members
$groupName = 'Life';
$response = $mailerLite->groups->get();

// Find the group by name
$groupId = null;
foreach ($response['body']['data'] as $group) {
    if ($group['name'] === $groupName) 
    {
        $groupId = $group['id'];
        break;
    }
}
echo("Group Name " . $group['name'] . "<br>");
echo("Group ID " . $groupId . "<br>");
*/
/*
$data = [
    'email' => 'vk3eka@yahoo.com',
];
echo("<pre>");
echo(var_dump($data));
echo("</pre>");
echo('Add group ' . $groupName . " to subscriber<br>");
$response = $mailerLite->groups->assignSubscriber($groupId, $data);
//echo("<pre>");
//echo(var_dump($response));
//echo("</pre>");
*/

/*
// Email address of the subscriber you want to fetch
//$subscriberEmail = 'vk3eka@yahoo.com'; // Replace with the email of the subscriber
$subscriberEmail = '139844421481924511'; // Replace with the email of the subscriber
try {
    // Find the subscriber by email
    $subscriber = $mailerLite->subscribers->find($subscriberEmail);

    // Output the subscriber details
    //echo("<pre>");
    //echo(var_dump($subscriber));
    //echo("</pre>");

    echo "Subscriber ID: " . $subscriber['body']['data']['id'] . "\n"; // Get and print the subscriber ID
    echo "Subscriber Email: " . $subscriber['body']['data']['email'] . "\n"; // Subscriber email
    echo "Subscriber Status: " . $subscriber['body']['data']['status'] . "\n"; // Subscriber status
    // You can access other subscriber data as needed, e.g., custom fields, groups, etc.

} catch (Exception $e) {
    // Handle error if subscriber is not found or any API error occurs
    echo "Error: " . $e->getMessage();
}
*/


// list all campaigns
$data = [
    'filter' => ['status' => 'unsubscribed'],
];

$response = $mailerLite->subscribers->get($data);

//echo("<pre>");
//echo(var_dump($response));
//echo("</pre>");

foreach($response['body']['data'] as $group)
{
    echo($group['fields']['memberid'] . "<br>");
}

foreach($response['body']['data'] as $group)
{
    echo($group['email'] . "<br>");
} 
//echo($response['body']['data']['email'] . "<br>");
//echo(($response['memberid'] . "<br>"));

/*
$groupName = "Captains S1";
$data = [
    "name" => $groupName,
    ];
$response = $mailerLite->groups->create($data);
echo("<pre>");
echo(var_dump($response));
echo("</pre>");

*/
/*
$new_array = ["Life","Female/NB/NS","Honorary"];

$newEmail = 'vk3eka@cpc-world.com';
$existing_email = 'vk3eka@yahoo.com';

function UpdateEmail($newEmail, $new_array, $existing_email)
{
    global $mailerLite;

    //echo($newEmail . ", " . $existing_email . "<br>");
    $temp_array = implode(",", $new_array);
    $temp_new_array = explode(",", $temp_array);

    // get existing groups
    $data = [
        'email' => $existing_email,
    ];
    $response = $mailerLite->subscribers->create($data);
    foreach($response['body']['data']['groups'] as $group)
    {
        $existing_group_array[] = $group['name'];
    }

    try 
    {
        // Step 1: Find the subscriber
        $subscriber = $mailerLite->subscribers->find($existing_email);
        /*
        echo("<pre>");
        echo(var_dump($existing_group_array));
        echo("</pre>");

        echo("<pre>");
        echo(var_dump($temp_new_array));
        echo("</pre>");
        */
        /*
        //echo($subscriber['body']['data']['fields']['memberid'] . "<br>");

        if($subscriber) 
        {
            $data = [
                'email' => $newEmail,
                'status' => 'active', // or 'unsubscribed'
                'fields' => [
                    'memberid' => $subscriber['body']['data']['fields']['memberid'],
                    'name' => $subscriber['body']['data']['fields']['name'],
                    'last_name' => $subscriber['body']['data']['fields']['last_name']
                ],
         
            ];
            $newSubscriber = $mailerLite->subscribers->create($data);

            // add existing groups to new email address
            foreach($existing_group_array as $temp_new)
            {
                //echo($temp_new . "<br>");
                //if($temp_new != '')
                //{
                    //if(!in_array($temp_new, $existing_group_array))
                    //{
                        $groupName = $temp_new;
                        $response = $mailerLite->groups->get();
                        $groupId = null;
                        //echo($groupName . "<br>");
                        foreach ($response['body']['data'] as $group) 
                        {
                            if ($group['name'] === $groupName) 
                            {
                                $groupId = $group['id'];
                                ///echo($groupId . "<br>");
                                break;
                                //$response = $mailerLite->groups->assignSubscriber($groupId, $newEmail);
                            }
                        }
                        $response = $mailerLite->groups->assignSubscriber($groupId, $newEmail);
                    //}
                //}
            } 

            echo "Subscriber email updated successfully.";
        } else {
            echo "Subscriber not found.";
            }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

}

UpdateEmail($newEmail, $new_array, $existing_email);
*/

