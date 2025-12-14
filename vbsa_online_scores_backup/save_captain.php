<?php 

include ("connection.inc");
include ("php_functions.php");

function AuthoriseEmail($access, $firstname, $rego_id, $password, $email, $team)
{
    $subject = 'VBSA Scoring APP System Access'; 
    $message = '<html><body>';
    $message .= "<p>" . $firstname . "</p>";
    $message .= "<p>Here are your log-in details for the VBSA Scoring APP. You have been granted " . $access . " access for " . $team . ".</p>";
    $message .= "<p>Username " . $email . "</p>";
    $message .= "<p>Password " . $password . "</p>";
    $message .= "<p>Please access the system and change your password, if you wish to.</p>";
    $message .= "<p>Click <a href='http://vbsa.org.au/'>here </a>to access the Scoring APP portal.</p>";
    $message .= "<p>Select from the menu item at far right of menu bar.</p>";
    $message .= "<p>Instructions on how to use the APP are located on the VBSA website Scores <a href='https://www.vbsa.org.au/VBSA_scores/scores_index.php'> page.</a></p>";
    $message .= "<p>Thanks.</p>";
    $message .= "<p>VBSA Scores Registrar.</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p><i>NOTE: Please do not reply to this email as this email address is not monitored.</i></p>";
    $message .= "<p><i>Direct all emails to <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
    $message .= "<img src='http://vbsa.org.au/vbsa_online_scores/MarkDunn.jpg' width = '400px' height = '140px'>";
    $message .= "</body></html>";
    SendBulkEmail($subject, $message, $email);
}
    $player = $_GET['ID'];
    $team = $_GET['Team'];
    $action = $_GET['Action'];

    if($action == 'NewListing') // not listed
    {
        // get members name from rego number
        $sql = "Select * from members where MemberID  = " . $player;
        $result_select_rego = $dbcnx_client->query($sql);
        $row_data = $result_select_rego->fetch_assoc();
        $fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);
        $password = generatePassword(10);
        // send email
        AuthoriseEmail("Team Captain", $row_data['FirstName'], $row_data['MemberID'], $password, $row_data['Email'], $team);
        $sql = "Insert into tbl_authorise (Name, Password, Access, Team_1, PlayerNo, Email, Active) VALUES ('" . $fullname . "', '" . password_hash($password, PASSWORD_DEFAULT) . "', 'Team Captain', '" . $team . "', " . $player . ", '" . $row_data['Email'] . "', 1)";
        $update = $dbcnx_client->query($sql);
        if(!$update )
        {
            die("Could not update data: " . mysqli_error($dbcnx_client));
        }   
        else
        {
            $caption = 'New captain added and email sent.';
            echo($caption);
        }
    }
    else if($action == 'NewPassword') // no password
    {
        $password = generatePassword(10);
        $sql_players = "Update tbl_authorise Set Team_1 = '" . $team . "', Password = '" . password_hash($password, PASSWORD_DEFAULT) . "' where PlayerNo = " . $player; 
        $update = $dbcnx_client->query($sql_players);
        if(! $update )
        {
            die("Could not player update data: " . mysqli_error($dbcnx_client));
        } 
        else
        {
            // send email
            $sql = "Select * from members where MemberID  = " . $player;
            $result_select_rego = $dbcnx_client->query($sql);
            $row_data = $result_select_rego->fetch_assoc();
            $fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);
            AuthoriseEmail("Team Captain", $row_data['FirstName'], $row_data['MemberID'], $password, $row_data['Email'], $team);
            $caption = 'New captain added and email sent.';
            echo($caption);
        }
    }
    else if($action == 'AddTeam') // already is a team captain for other teams
    {
        $sql_authorise = "Select * from tbl_authorise where PlayerNo = " . $player;
        $result_authorise = $dbcnx_client->query($sql_authorise);
        $row = $result_authorise->fetch_assoc();

        if(($row['Team_1'] == $team) || ($row['Team_2'] == $team) || ($row['Team_3'] == $team))
        {
            $caption = 'This player is already a Team Captain for this team!';
            echo($caption);
        }
        else if(($row['Team_2'] == '') && ($row['Team_3'] == ''))
        {
            $sql_players = "Update tbl_authorise Set Team_2 = '" . $team . "' where PlayerNo = " . $player; 
            $update = $dbcnx_client->query($sql_players);
            if(!$update )
            {
                die("Could not update data: " . mysqli_error($dbcnx_client));
            }   
            $caption = 'New captain added to team 2.';
            echo($caption);
        }
        else if(($row['Team_2'] != '') && ($row['Team_3'] == ''))
        {
            $sql_players = "Update tbl_authorise Set team_3 = '" . $team . "' where PlayerNo = " . $player; 
            $update = $dbcnx_client->query($sql_players);
            if(!$update )
            {
                die("Could not update data: " . mysqli_error($dbcnx_client));
            }   
            $caption = 'New captain added to team 3.';
            echo($caption);
            return;
        }
    }

?>