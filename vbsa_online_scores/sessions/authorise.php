<?php
if (!isset($_SESSION)) {
  session_start();
}
include ('server_name.php');
include ("header.php");
include ("connection.inc");
include ("php_functions.php");

function AuthoriseEmail($access, $firstname, $rego_id, $password, $email, $team1, $team2, $team3)
{
    $team = $team1 . " team.";
    if($team2 != '')
    {
        $team = $team1 . " and " . $team2 . " teams.";
    }
    if($team3 != '')
    {
        $team = $team1 . ", " . $team2 . " and " . $team3 . " teams.";
    }
    $subject = 'VBSA Scoring APP System Access'; 
    $message = '<html><body>';
    $message .= "<p>" . $firstname . "</p>";
    $message .= "<p>Here are your log-in details for the VBSA Scoring APP. You have been granted " . $access . " access to the system for the " . $team . "</p>";
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
    //echo($message . "<br>");
    SendBulkEmail($subject, $message, $email);
}

if ($_POST['ButtonName'] == "Delete") {
    $sql = "Delete From tbl_authorise where PlayerNo  = " . $_POST['MemberID'];                
    $update = $dbcnx_client->query($sql);
}

if ($_POST['ButtonName'] == "SaveEdited") {
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    $sql = "Update tbl_authorise Set Access = '" . $packeddata[1] . "', Team_1 = '" . $packeddata[2] . "', Team_2 = '" . $packeddata[3] . "', Team_3 = '" . $packeddata[4] . "', Active = " . $packeddata[5] . "  where PlayerNo  = " . $packeddata[0];
    $update = $dbcnx_client->query($sql);
}

if ($_POST['ButtonName'] == "SaveChanges") {
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    for ($i = 0; $i < count($packeddata); $i++) {
        $player = explode(", ", $packeddata[$i]);
        $sql = "Update tbl_authorise Set Active =  " . $player[1] . " where PlayerNo  = " . $player[0];
        $update = $dbcnx_client->query($sql);
    }
}

if ($_POST['ButtonName'] == "BulkEmail") {
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    for ($i = 0; $i < count($packeddata); $i++) {
        $player = explode(", ", $packeddata[$i]);
        if($player[1] == 1)
        {
            $sql = "Select * from tbl_authorise Where PlayerNo = " . $player[0];
            $result = $dbcnx_client->query($sql);
            $build_data = $result->fetch_assoc();
            $fullname = explode(" ", $build_data['Name']);
            $firstname = $fullname[0];
            $access = $build_data['Access'];
            $rego_id = $build_data['PlayerNo'];
            $password = generatePassword(10);
            $email = $build_data['Email'];
            $team1 = $build_data['Team_1'];
            $team2 = $build_data['Team_2'];
            $team3 = $build_data['Team_3'];
            // update password
            $sql = "Update tbl_authorise Set Password =  '" . password_hash($password, PASSWORD_DEFAULT) . "', Active = 1, BulkEmail = 0 where PlayerNo  = " . $player[0];
            $update = $dbcnx_client->query($sql);
            // send email
            AuthoriseEmail($access, $firstname, $rego_id, $password, $email, $team1, $team2, $team3);
        }
    }
    echo "<script type='text/javascript'>";
    echo "alert('Mail Sent!')";
    echo "</script>";
}

?>
<script language="JavaScript" type="text/JavaScript">

function GetFunctionData(sel) 
{
    var function_type = sel.options[sel.selectedIndex].value;
    document.authorise.Access.value = function_type;
    document.authorise.submit();
}

function DeleteButton(ID, access_id) 
{
    var access = document.getElementById("access_0").innerHTML;
    if(access != 'Administrator')
    {
        document.authorise.MemberID.value = ID;
        document.authorise.ButtonName.value = "Delete"; 
        document.authorise.submit();
    }
    else
    {
        alert('You cannot delete the Administrator!');
    }
}

function EditButton(ID) 
{
    document.authorise.MemberID.value = ID;
    document.authorise.ButtonName.value = "Edit User"; 
    document.authorise.submit();
}  

function SaveSelectedChangesButton(no_of_players) {
    var transferdata = {};
    var enable_chk = 0;
    for (var i = 0; i < no_of_players; i++) { // get number of players
        if (document.getElementById("active_" + i).checked == true) {
            enable_chk = 1;
        }
        else
        {
            enable_chk = 0;
        }
        transferdata[i] = document.getElementById("member_id_" + i ).value + ", " + enable_chk;
    }
    var data = JSON.stringify(transferdata);
    document.authorise.PackedData.value = data;  
    document.authorise.ButtonName.value = "SaveChanges"; 
    document.authorise.submit();
}

function SaveBulkEmailButton(no_of_players) {
    document.getElementById('bulkemail').classList.add('disabled');
    var transferdata = {};
    var enable_chk = 0;
    for (var i = 0; i < no_of_players; i++) { // get number of players
        if (document.getElementById("bulk_email_" + i).checked == true) {
            enable_chk = 1;
        }
        else
        {
            enable_chk = 0;
        }
        transferdata[i] = document.getElementById("member_id_" + i ).value + ", " + enable_chk;
    }
    var data = JSON.stringify(transferdata);
    document.authorise.PackedData.value = data;  
    document.authorise.ButtonName.value = "BulkEmail"; 
    document.authorise.submit();
}

function SaveEditedButton() 
{
    if(document.getElementById('active').checked)
    {
        active = 1;
    }
    else
    {
        active = 0;
    }
    var transferdata = new Array();
    transferdata[0] = document.authorise.member_rego.value;
    transferdata[1] = document.authorise.rego_type.value;
    transferdata[2] = document.authorise.club_name_1.value;
    transferdata[3] = document.authorise.club_name_2.value;
    transferdata[4] = document.authorise.club_name_3.value;
    transferdata[5] = active;
    var data = JSON.stringify(transferdata);
    document.authorise.PackedData.value = data; 
    document.authorise.ButtonName.value = "SaveEdited"; 
    document.authorise.MemberID.value = document.authorise.member_rego.value;
    document.authorise.submit();
}

function ToggleActiveButton(no_of_players) {
    if (document.getElementById("check_all_active").innerHTML == "Check All")
    {
        document.getElementById("check_all_active").innerHTML = "UnCheck All"
        for (var i = 0; i < no_of_players; i++) { // get number of players
            document.getElementById("active_" + i).checked = true;
        }
    }
    else
    {
        document.getElementById("check_all_active").innerHTML = "Check All"
        for (var i = 0; i < no_of_players; i++) { // get number of players
            document.getElementById("active_" + i).checked = false;
        }
    }
}

function ToggleBulkButton(no_of_players) {
    if (document.getElementById("check_all_bulk_email").innerHTML == "Check All")
    {
        document.getElementById("check_all_bulk_email").innerHTML = "UnCheck All"
        for (var i = 0; i < no_of_players; i++) { // get number of players
            document.getElementById("bulk_email_" + i).checked = true;
        }
    }
    else
    {
        document.getElementById("check_all_bulk_email").innerHTML = "Check All"
        for (var i = 0; i < no_of_players; i++) { // get number of players
            document.getElementById("bulk_email_" + i).checked = false;
        }
    }
}

</script>
<?php
if(isset($_POST['Access']) && ($_POST['Access'] != ''))
{
    $access = $_POST['Access'];
}
else
{
    $access = "Team Captain";
}
?>
<center>
<form name="authorise" method="post" action="authorise.php">
<input type="hidden" name="MemberID" value="" />
<input type="hidden" name="ButtonName" />
<input type="hidden" name="PackedData" />
<input type="hidden" name="Access" />
<?php
if ($_POST['ButtonName'] != "Edit User")
{
?>
<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tr>
        <td align=right><b>Select By Function&nbsp;</b></td>
        <td align='left' valign='top'><select id="function_type" onchange="GetFunctionData(this)">
            <option value='' selected='selected'>&nbsp;</option>
            <option value='Administrator'>Administrator</option>
            <option value='Team Captain'>Team Captain</option>
            </select>
        </td>  
    </tr>
</table>
<br>
<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tr>
        <td>
            <form name='edit' method='post' action='authorise.php'>
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="10" width="100%">
             <thead>
                <tr>
                    <th class="text-center" rowspan=2>Players Name</th>
                    <th class="text-center" rowspan=2>Access Type</th>
                    <th colspan=3 class="text-center">Teams Administered</th>
                    <th class="text-center" rowspan=2>Email Address</th>
                    <th class="text-center" rowspan=2>Active</th>
                    <th class="text-center" rowspan=2>Bulk Email</th>
                    <th colspan=2 class="text-center">Action</th>
                </tr>
                <tr>
                    <th class="text-center" >Team 1</th>
                    <th class="text-center" >Team 2</th>
                    <th class="text-center" >Team 3</th>
                    <th class="text-center" >Edit</th>
                    <th class="text-center" >Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "Select * from tbl_authorise, members Where (tbl_authorise.PlayerNo=members.MemberID AND Access = '" . $access . "') Order By Team_1";
                $result = $dbcnx_client->query($sql);
                $i = 0;
                $num_rows = $result->num_rows;
                if($num_rows > 0)
                {
                    while ($build_data = $result->fetch_assoc())
                    {
                        if($build_data['Active'] == 1)
                        {
                            $checked = ' checked';
                        }
                        else
                        {
                            $checked = '';
                        }
                        if($build_data['BulkEmail'] == 1)
                        {
                            $bulk_checked = ' checked';
                        }
                        else
                        {
                            $bulk_checked = '';
                        }
                        echo("<tr>"); 
                        echo("<input type='hidden' id='member_id_" . $i . "' value=" . $build_data['PlayerNo'] . " />");
                        echo("<td id='player_name_" . $i . "' style='text-transform:capitalize'>" . $build_data['Name'] . "</td>");
                        echo("<td id='access_" . $i . "'>" . $build_data['Access'] . "</td>");
                        $sql = "Select * from tbl_authorise where PlayerNo  = " . $build_data['PlayerNo'];
                        $result_select_club = $dbcnx_client->query($sql);
                        $build_club_data = $result_select_club->fetch_assoc();
                        $team1 = $build_club_data['Team_1'];
                        echo("<td id='club_1_" . $i . "'>" . $team1 . "</td>");
                        $team2 = $build_club_data['Team_2'];
                        echo("<td id='club_2_" . $i . "'>" . $team2 . "</td>");
                        $team3 = $build_club_data['Team_3'];
                        echo("<td id='club_3_" . $i . "'>" . $team3 . "</td>");
                        echo("<td id='email_" . $i . "'>" . $build_club_data['Email'] . "</td>");
                        echo("<td align=center><input type='checkbox' id='active_" . $i . "' " . $checked . "></td>");
                        echo("<td align=center><input type='checkbox' id='bulk_email_" . $i . "' " . $bulk_checked . "></td>");
                        echo("<td align='center' ><a class='btn btn-primary btn-xs' onclick='EditButton(" . $build_data['PlayerNo'] . ")'>Edit Record</a></td>");   
                        echo("<td><a class='btn btn-primary btn-xs' onclick='DeleteButton(" . $build_data['PlayerNo'] . ", " . $i . ")'>Delete Record</a></td>");   
                        echo("</td>");
                        echo("</tr>"); 
                        $i++;
                    } 
                    echo("<tr>"); 
                    echo("<td id='player_name_" . $i . "' style='text-transform:capitalize'>" . $build_data['Name'] . "</td>");
                    echo("<td colspan=5>&nbsp;</td>");
                    echo("<td align='center'>");
                    echo("<a class='btn btn-primary btn-xs' id='check_all_active' onclick='ToggleActiveButton(" . $i . ")'>Check All</a></td>;"); 
                    echo("<td align='center'>");  
                    echo("<a class='btn btn-primary btn-xs' id='check_all_bulk_email' onclick='ToggleBulkButton(" . $i . ")'>Check All</a></td>");  
                    echo("<td>&nbsp;</td>"); 
                    echo("</tr>"); 
                }
                else
                {
                    echo("<tr>");
                    echo("<td align='center' colspan = '7'>No records to Display</td>");
                    echo("</tr>");
                }
                ?>
                <tr>   
                    <td colspan=10 align='center'><a class='btn btn-primary btn-xs' href='<?= $url ?>/add_new_authorisation.php' style='width:250px'>Add New Record</a>
                    <a class='btn btn-primary btn-xs' onclick='SaveSelectedChangesButton(<?php echo($i); ?>)' style='width:250px'>Update 'Active' Selection</a>
                    </td>
                </tr>
                <tr>   
                    <td colspan=10 align='center'><a class='btn btn-primary btn-xs' href='<?= $url ?>/upload_bulk_list.php' onclick='UploadBulkList()' style='width:250px'>Upload Bulk List</a>
                    &nbsp;<a class='btn btn-primary btn-xs' id='bulkemail' onclick='SaveBulkEmailButton(<?php echo($i); ?>)' style='width:250px'>Send Selected Bulk Emails</a>
                    </td>
                </tr>
                </tbody>
            </table>
            </form>
        </td>
    </tr>
</table>
<?php
}
elseif ($_POST['ButtonName'] == "Edit User")
{
    $sql = "Select * from tbl_authorise Where PlayerNo = " . $_POST['MemberID'];
    $edit_result = $dbcnx_client->query($sql);
    while ($edit_build_data = $edit_result->fetch_assoc())
    {
        $regoID = $edit_build_data['PlayerNo'];
        $fullname = $edit_build_data['Name'];
        $email = $edit_build_data['Email'];
        $team1 = $edit_build_data['Team_1'];
        $team2 = $edit_build_data['Team_2'];
        $team3 = $edit_build_data['Team_3'];
        $access = $edit_build_data['Access'];
        if($edit_build_data['Active'] == 1)
        {
            $checked = ' checked';
        }
        else
        {
            $checked = '';
        }
    }
?>
<table class='table dt-responsive nowrap display' style="width:1000px">
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display' style="width:1000px">
                <tr>
                    <td colspan=7 align=center><b>Edit User Details</b></td>
                </tr>
                <tr> 
                    <td colspan=7 align=center>&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan=2 align='center'>Full Name:</td>
                    <td rowspan=2 align='center'>Email Address:</td>
                    <td rowspan=2 align='center'>Active:</td>
                    <td colspan=3 align='center'>Team</td>                
                    <td rowspan=2 align='center'>Access Type</td>                  
                </tr>
                <tr>
                    <td align='center'>Team 1</td>
                    <td align='center'>Team 2</td>
                    <td align='center'>Team 3</td>                                
                </tr>
                <tr>
                    <input name="member_rego" value="<?php echo $regoID; ?>" type='hidden'>
                    <td align='center' valign='top'><input name="member_name" value="<?php echo $fullname; ?>" type='text' readonly></td>
                    <td align='center' valign='top'><input name="member_email" value="<?php echo $email; ?>" type='text' readonly></td>
                    <td align=center><input type='checkbox' id='active' <?php echo $checked; ?>></td>
                    <td align='center'><select name="club_name_1" id="club_name_1">
                    <?php    
                        $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Order By team_name");
                        echo("<option value='" . $team1 . "'>" . $team1 . "</option>");
                        echo"<option value=''>&nbsp</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                    ?>  
                        </select>
                    </td> 
                    <td align='center'><select name="club_name_2" id="club_name_2">
                    <?php    
                        $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Order By team_name");
                        echo("<option value='" . $team2 . "'>" . $team2 . "</option>");
                        echo"<option value=''>&nbsp</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                    ?>  
                        </select>
                    </td> 
                    <td align='center'><select name="club_name_3" id="club_name_3">
                    <?php    
                        $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Order By team_name");
                        echo("<option value='" . $team3 . "'>" . $team3 . "</option>");
                        echo"<option value=''>&nbsp</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                    ?>  
                        </select>
                    </td> 
                    <td align='center' valign='top'><select name="rego_type">
                        <option value="<?php echo $access; ?>" selected="selected"><?php echo $access; ?></option>
                        <option value="">--------------</option>
                        <option value='Administrator'>Administrator</option>
                        <option value='Team Captain'>Team Captain</option>
                        </select></td>                  
                </tr>
                <tr> 
                    <td colspan=7 align=center>&nbsp;</td>
                </tr>
                <tr> 
                    <td colspan=7 align=center>Only the User can change their password.</td>
                </tr>
                 <tr> 
                    <td colspan=7 align=center><a class='btn btn-primary btn-xs' href="javascript:;" onclick="SaveEditedButton();">Save Edited Data</a></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br /> 
</center>
</form>
<?php
}

include("footer.php"); 

?>