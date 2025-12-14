<?php

include ("connection.inc");
include ("header.php");
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
    $message .= "<p>Click <a href='http://vbsa.cpc-world.com/'>here </a>to access the Scoring APP portal.</p>";
    $message .= "<p>Select from the menu item at far right of menu bar.</p>";
    $message .= "<p>Instructions on how to use the APP are located on the VBSA website Scores <a href='https://www.vbsa.org.au/VBSA_scores/scores_index.php'> page.</a></p>";
    $message .= "<p>Thanks.</p>";
    $message .= "<p>VBSA Scores Registrar.</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p><i>NOTE: Please do not reply to this email as this email address is not monitored.</i></p>";
    $message .= "<p><i>Direct all emails to <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
    $message .= "<img src='http://vbsa.cpc-world.com/vbsa_online_scores/MarkDunn.jpg' width = '400px' height = '140px'>";
    $message .= "</body></html>";
    SendBulkEmail($subject, $message, $email);
}

if ($_POST['ButtonName'] == "SaveData") 
{
    $player = $_POST['PlayerID'];
    $team = $_POST['Team'];
    if($_POST['PWD_Check'] == 0)
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
    }
    else
    {
        // check if already a captain.
        $sql_captain_check = "Select * from tbl_authorise where PlayerNo  = " . $player;
        $result_captain_check = $dbcnx_client->query($sql_captain_check);
        $captain_check = $result_captain_check->fetch_assoc();
        if($captain_check['Team_1'] != '')
        {
            if($captain_check['Team_2'] != '')
            {
                if($captain_check['Team_3'] != '')
                {
                    echo "<script type='text/javascript'>";
                    echo "if(confirm('" . $captain_check['Name'] . " is already a Captain of three teams!'))";
                    echo "{";
                    echo "location.href =  'captain_authorise.php'";
                    echo "}";
                    echo "</script>";
                }
                else
                {
                    $add_team = "Team_3";
                }
            }
            else
            {
                $add_team = "Team_2";
            }
        }
        else
        {
            $add_team = "Team_1";
        }
        $sql_players = "Update tbl_authorise Set " . $add_team . " = '" . $team . "' where PlayerNo = " . $player; 
        $update = $dbcnx_client->query($sql_players);
        if(! $update )
        {
            die("Could not player update data: " . mysqli_error($dbcnx_client));
        } 
    }
    header("Location: records_update.php");
}

?>    
<script>

function SaveButton(ID, index) 
{
    var no_of_players = document.getElementById("no_of_players").value;
    var password_check = 0;
    if(document.getElementById("password_ok_" + index).checked)
    {
        password_check = 1;
    }
    else
    {
        password_check = 0;
    }
    document.capt_auth.PlayerID.value = ID;
    document.capt_auth.PWD_Check.value = password_check;
    document.capt_auth.Team.value = '<?php echo($_SESSION['clubname']); ?>';
    document.capt_auth.ButtonName.value = "SaveData"; 
    document.capt_auth.submit();
}

</script>
<center>
<form name="capt_auth" method="post" action="captain_authorise.php">
<input type="hidden" name="PWD_Check" value="" />
<input type="hidden" name="PlayerID" value="" />
<input type="hidden" name="ButtonName" />
<input type="hidden" name="Team" />
<table class="table table-striped table-bordered dt-responsive nowrap">
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display'>
                <thead>
                    <tr>
                        <th>Players Name</th>
                        <th class='text-center'>Password</th>
                        <th>Email Address</th>
                        <th class='text-center'>Add Captain</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $players = array();
                $sql_team = "Select distinct team_id, team_name, team_grade from Team_entries where team_name = '" . $_SESSION['clubname'] . "' AND team_cal_year = " . $_SESSION['year'];
                $result_team = $dbcnx_client->query($sql_team);
                $i = 0;
                $checked = '';
                $num_rows = $result_team->num_rows;
                $teamID_Array = array();
                while ($build_data = $result_team->fetch_assoc())
                {
                    $teamID_Array =  "Team_entries.team_id=" . $build_data['team_id'] . " OR " . $teamID_Array;
                }
                $team_text = stristr($teamID_Array, "or array", true);
                if($num_rows > 0)
                {
                    $players = array();
                    $sql_player = "Select scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, members.Email, members.MemberID, members.FirstName, members.LastName, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID AND Team_entries.team_id=scrs.team_id AND (" . $team_text . ") AND FirstName <> 'Bye' Group By members.MemberID ORDER BY members.MemberID";
                    $result_players = $dbcnx_client->query($sql_player);
                    $num_rows_player = $result_players->num_rows;
                    if($num_rows > 0)
                    {
                        while ($build_data_player = $result_players->fetch_assoc())
                        {
                            $sql_password = "Select Password from tbl_authorise where PlayerNo = " . $build_data_player['MemberID'];
                            $result_password = $dbcnx_client->query($sql_password);
                            $build_password = $result_password->fetch_assoc();
                            if($build_password['Password'] != '')
                            {
                                $checked = ' checked';
                            }
                            else
                            {
                                $checked = '';
                            }
                            echo("<tr>"); 
                            echo("<input type='hidden' id='player_id_" . $i . "' value='" . $build_data_player['MemberID'] . "' readonly>");
                            echo("<td id='player_name_" . $i . "' style='text-transform:capitalize'>" . $build_data_player['FirstName'] . " " . $build_data_player['LastName'] . "</td>");
                            echo("<td class='text-center'><input type='checkbox' id='password_ok_" . $i . "' " . $checked . "  disabled></td>");
                            echo("<td id='email'>" . $build_data_player['Email'] . "</td>");
                            echo("</td>");
                            echo("<td align='center'><a class='btn btn-primary btn-xs'  href='javascript:;' onclick='SaveButton(" . $build_data_player['MemberID'] . ", " . $i . ");'>Add as New Captain</a></td>");
                            echo("</tr>"); 
                            $i++;
                        } 
                    }
                    else
                    {
                        echo("<tr>");
                        echo("<td align='center' colspan = '7'>No records to Display</td>");
                        echo("</tr>");
                    }  
                    echo("<input type='hidden' id='no_of_players' value=" . $i . ">"); 
                }        
                ?>
                </tbody>
            </table>
        </td>
    </tr>
</table>
</form>
<br /> 
</center>
<?php

include("footer.php"); 

?>