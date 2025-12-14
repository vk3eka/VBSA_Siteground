<?php require_once('../Connections/connvbsa.php'); ?>
<?php

// VBSA
$api_key = '47ee1caeef220fd3a248ebdb9e098a15-us21';
$server_prefix = 'us21'; // e.g., us1, us2, etc.
$audience_id = 'f8d521b97d'; // Replace with your Mailchimp audience ID
/*
// cpc-world
$api_key = '2251a363941e5c1ed53c807ba05ccc8d-us22';
$server_prefix = 'us22'; // e.g., us1, us2, etc.
$audience_id = 'c7f901582f'; // Replace with your Mailchimp audience ID
*/
function update_mailchimp_audience($api_key, $server_prefix, $email, $first_name, $last_name, $audience_id) 
{
    $url = 'https://' . $server_prefix . '.api.mailchimp.com/3.0/lists/' . $audience_id . '/members/' . md5(strtolower($email));

    $data = [
        'email_address' => $email,
        'status_if_new' => 'subscribed',  // or 'unsubscribed', 'pending'
        'tags' => array('Members', 'Junior', 'Referee'),
        'merge_fields' => [
            'FNAME' => $first_name,
            'LNAME' => $last_name
        ]
    ];

    $json_data = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return [
        'status_code' => $http_code,
        'response' => json_decode($result, true)
    ];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_audience = "Select LastName, FirstName, Email FROM test_members WHERE ReceiveEmail = 1 AND Email != '' AND MemberID = 6206";
$audience = mysql_query($query_audience, $connvbsa) or die(mysql_error());

while ($row_audience = mysql_fetch_assoc($audience))
{
    $email = $row_audience['Email'];
    $first_name = $row_audience['FirstName'];
    $last_name = $row_audience['LastName'];
    
    $response = update_mailchimp_audience($api_key, $server_prefix, $email, $first_name, $last_name, $audience_id);
    //echo('Code ' . $response['status_code'] . "<br>");
    if ($response['status_code'] == 200) 
    {
        $update = true;
    } 
    else 
    {
        $update = false;
    }
    
}


if ($update === true) 
{
    echo 'Successfully updated audience!';
    //echo '<script>window.open("https://login.mailchimp.com")</script>';
} 
else 
{
    echo 'Failed to update audience.';
}
