<?php
require_once('../vendor/autoload.php');

//local
$apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiN2JlMmM1NDA0ODljMjUwMjUyODI2ODQ5MTQyNmJjYTVkYzBjZjAwOTJmZGQzNDFhYTc3NjkxMmU3YzNmNTQ1NDkxNTMxMzFiOTk2ZWQxOTEiLCJpYXQiOjE3MzI5MzMwNDkuOTkzMTc0LCJuYmYiOjE3MzI5MzMwNDkuOTkzMTc3LCJleHAiOjQ4ODg2MDY2NDkuOTg5MzgzLCJzdWIiOiIxMjI0MzAzIiwic2NvcGVzIjpbXX0.XU2PE1WSL7HYhPy12EzrHT_BR0uxBDi15Z81Ujopd9oco8Kc1ACu3_xFSrsktV9pK1yQfcao0AqAii4xdrKhisvhKzVbMn5eMvQPOJGW62vwRAp-KMvak5Ckr4hvxEGu8i2k5NpeFqLXMwbiAxsI59eMJD6XWhmAi9qOYgRh0V9W4VWbkba5HavyH-we1edlE4JTcZeyDVXR0NGDnPmn-1VxC0RHIwlZNVkwsz8FNlvnr4tefNBBxfRuuEsOY9vtDqEJ0E_8z8rFv0j_ldvQsdJr_qaOXvJYMdlU7oUiMPaj86ypItY6kmtsENdy66f8Iounce-lkTVfbkEzRN4dPlhVd396Nhcm-QW7qT7XeG-f0NGQDUUJuhVAVj1ZnbccQUph0lvFQEDwhA4oXvXvpZGhIuknSEpgA9t7cbmotrgjhrBYUl67ML9AVcWCXFGK7-CgIED2rYFa160Ws9ZEqYf6jl7P50Cia13Kqy8ebMuasQXShslkke5gbFvVMq6jgLrWniRONNSBng4-ZfNzwtRMe-iTVEP-Lc8exPSpwaX12-OaknqxLD7Lde_vQSmHNkJfAUdQWt8D5W-6m567lqBDHHy90X6mPoNRrLI-QwGXdnp37WY9zhzKBL1k6NDc0ybeLKgSpGdRemg6q0klE7PFLYpEcFJKfHGYIqGuRJg';

// vbsa
//$apiKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiNDkyOTE0OGM3NmE2YmNjYmM3MTdlNzczNTNhZGFlMDk2ZjhkYmI1NDE3MzM5OTJmYjFhOGU1ZWIwOWRlMjczMWNjYTBmOTUyYjUxNDY1YzUiLCJpYXQiOjE3MzMzNjQ1MTAuMDQyNjQsIm5iZiI6MTczMzM2NDUxMC4wNDI2NDMsImV4cCI6NDg4OTAzODExMC4wMzkxMTksInN1YiI6IjEyMjk5NzciLCJzY29wZXMiOltdfQ.jGQWUkbdZ-shyGyvzxS0gqN9kgLWS0Xl5YSV4qfQwuajdv9NaS_9e_Ax2sYLXl-TWjDAhDhell2Y348uUgZJDTI-4Q6dK6_6oPVYfO2kd74MCWrq-vq6SvYfICuMv07z61QegbuaEG5e7-spaLgXVIykjiuqwPPGLQMN6lDkHonRWFrxOi7xcxcsapkYayVeYHwq7w8A7Ntnhsljn2zO31NcGyKpp7I5wLtHGqCtz1UC3s0HZizQ6R70PHa3EUuX-2g6CtyFoWyf517_XoApYotcJFcWYF5piQT2AIAj1tKxAa8QRX4yBmV0dRcnrJzTQCEQYY5QszHkZgaQqXngYytK_0V8xddBpyPEUnRJz4fTaYNkF36cB_7bAbTjKYF5luFPJz1qIjltSeSpBMNJq_5vnJgBpq93EkAQzzD_Wcgg-wwOTCaCikfKJ9R4g-Jz6c67R-AhJCB81HIBInlNAYoorujPEfKE6YLbb3rh4kaGLurlo_yi-RbB-vLVTgb0smScf6CBsXF470hxchjO4LrUZXxVMvw2dDL44-Lzwcb49PtOhdR1GiDfaCyetlugz6LXSBQD0cBkPuwyoZxQmenNAW-KEFPP7bm1ev6Qh8VbYDeDjXqg85R8N28wlSlqoFtXLLZK-ZEtkaSCNylh3tTcnC8bG0jWczlvst3DS0I";

use MailerLite\MailerLite;
$mailerLite = new MailerLite(['api_key' => $apiKey]);

// list a member groups
function ListMemberGroups($email, $new_array, $existing_email)
{
	global $mailerLite;
	
  	$temp_array = implode(",", $new_array);
  	$temp_new_array = explode(",", $temp_array);
  	
  	//echo("<pre>");
	//echo(var_dump($temp_new_array));
	//echo("</pre>");
/*
  	$response = $mailerLite->subscribers->find($email);
  	$subscriberID = $response['body']['data']['id'];
  	
  	// find groups already subscribed
	$data = [
	    'email' => $email,
	];
	$response = $mailerLite->subscribers->create($data);
*/
	
/*
	// list all groups available
	$response = $mailerLite->groups->get();
	if (is_array($response['body']['data']) || $response['body']['data'] instanceof Traversable) 
	{
	    foreach($response['body']['data'] as $group)
	    {
	        echo("Name " . $group['name'] . ", ID " . $group['id'] . "<br>"); //  groups 
	    }
	} 
	else 
	{
	    echo 'No groups found or response format is unexpected.';
	}
*/

	//echo("<pre>");
	//echo(var_dump($response));
	//echo("</pre>");
	$existing_array = array();
	$existing_array = '';

	// list all groups available
	$response = $mailerLite->groups->get();
	if (is_array($response['body']['data']['groups']) || $response['body']['data']['groups'] instanceof Traversable) 
	{
	    foreach($response['body']['data']['groups'] as $group)
	    {
	    	//echo("Groups " . $group['name'] . "<br>");
	        $existing_array = $group['name'];
	        if(!in_array($existing_array, $temp_new_array))
	        {
				$response = $mailerLite->groups->get();
				if (is_array($response['body']['data']) || $response['body']['data'] instanceof Traversable) 
				{
				    foreach($response['body']['data'] as $group)
				    {
				    	// remove email from group
				        if (strtolower($group['name']) === strtolower($existing_array)) 
				        {
				            // If the group name matches, output the Group ID
						    $groupName = $group['name'];
							$response = $mailerLite->groups->get();
							$groupId = null;
							foreach ($response['body']['data'] as $group) {
							    if ($group['name'] === $groupName) 
							    {
							        $groupId = $group['id'];
							        break;
							    }
							}
        					$response = $mailerLite->groups->unassignSubscriber($groupId, $email);
        					//echo("<pre>");
							//echo(var_dump($response));
							//echo("</pre>");
				            break;
        				}
				    }
				} 
	        }
	    }

	    // get existing groups again
	    //$data = [
		//    'email' => $email,
		//];
	    //$response = $mailerLite->subscribers->create($data);
	    $response = $mailerLite->groups->get();
		if (is_array($response['body']['data']['groups']) || $response['body']['data']['groups'] instanceof Traversable) 
		{
		    foreach($response['body']['data']['groups'] as $group)
		    {
		        $existing_group_array[] = $group['name'];
		    }
		    foreach($temp_new_array as $temp_new)
		    {
		    	if($temp_new != '')
		    	{
		    		//add new email to group
		    		if(!in_array($temp_new, $existing_group_array))
			        {
			        	$groupName = $temp_new;
						$response = $mailerLite->groups->get();
						$groupId = null;
						foreach ($response['body']['data'] as $group) 
						{
						    if ($group['name'] === $groupName) 
						    {
						        $groupId = $group['id'];
						        break;
						    }
						}
					    $response = $mailerLite->groups->assignSubscriber($groupId, $email);
					    //echo("<pre>");
						//echo(var_dump($response));
						//echo("</pre>");
			        }
		    	}
		    } 
		}
	} 
	else 
	{
	    echo 'No groups found or response format is unexpected.';
	}
}

function addNewMember($email, $first_name, $last_name, $member_id)
{
	$data = [
	    'email' => $email,
        'status' => 'active', // or 'unsubscribed'
        'fields' => [
            'memberid' => $member_id,
            'name' => $first_name,
            'last_name' => $last_name
        ],
	];
	$response = $mailerLite->subscribers->create($data);
}

function GetGroupID($groupName)
{
	global $mailerLite;
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
	return $groupId;
}

function DeleteGroup($groupId)
{
	global $mailerLite;
	$response = $mailerLite->groups->delete($groupId);
	return true;
}

function CreateGroup($groupName)
{
	global $mailerLite;
	$data = [
    "name" => $groupName,
	];
	$response = $mailerLite->groups->create($data);
	return true;
}

function GetPlayerID($email)
{
	global $mailerLite;
	$response = $mailerLite->subscribers->find($email);
  	$subscriberID = $response['body']['data']['id'];
  	return $subscriberID;
}

function AddPlayerToGroup($email, $groupID)
{
	global $mailerLite;
	$response = $mailerLite->groups->assignSubscriber($groupID, $email);
	return true;
}

function UpdateEmail($newEmail, $existing_email)
{
    global $mailerLite;
    // Find the player
    $subscriber = $mailerLite->subscribers->find($existing_email);
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

        //delete the old email address
        $response = $mailerLite->subscribers->delete($existing_mail);
    }
}

