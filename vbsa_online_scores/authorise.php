<?php
if (!isset($_SESSION)) 
{
  session_start();
}

$season = $_SESSION['season'];
$year = $_SESSION['year'];

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
    $message .= "<p>Click <a href='https://vbsa.org.au/vbsa_online_scores/index.php'>here </a>to access the Scoring APP portal.</p>";
    $message .= "<p>Select from the menu item at far right of menu bar.</p>";
    $message .= "<p>Instructions on how to use the APP are located on the VBSA website Scores <a href='https://www.vbsa.org.au/VBSA_scores/scores_index.php'> page.</a></p>";
    $message .= "<p>Thanks.</p>";
    $message .= "<p>VBSA Scores Registrar.</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<img src='http://vbsa.org.au/vbsa_online_scores/MarkDunn.jpg' width = '400px' height = '140px'>";
    $message .= "</body></html>";
    SendBulkEmail($subject, $message, $email);
}


function SREmail($email, $captain_name, $captain_email, $team_name, $team_grade)
{
   $current_year = $_SESSION['year'];
   $current_season = $_SESSION['season'];
   
   $subject = 'VBSA Add Authoriser'; 
   $message = '<html><body>';
   $message .= "<p>The team " . $team_name . " has been entered in the " . $team_grade . " Grade for " . $current_season . " of " . $current_year . ".</p>";
   $message .= "<p>" . $captain_name . " at <a href=mailto:" . $captain_email . ">" . $captain_email . "</a> has been added as a Team Authoriser</p>";
   $message .= "<p>Team Registration SEWS.</p>";
   $message .= "</body></html>";
   SendBulkEmail($subject, $message, $email);
}


function Get_Authorisation($team, $season, $year, $memberid)
{
    global $dbcnx_client;

    $sql_multiple = 'Select captain_scrs, authoriser_scrs, Team_entries.team_id, team_name from scrs left join Team_entries on Team_entries.team_id = scrs.team_id where scr_season = "' . $season . '" and current_year_scrs = ' . $year . ' and MemberID = ' . $memberid . ' and team_name = "' . $team . '"';
    $result_multiple = $dbcnx_client->query($sql_multiple);
    $build_multiple = $result_multiple->fetch_assoc();

    if($build_multiple['captain_scrs'] == 1)
    {
        $authorisation = ' (Capt)';
    }
    else if($build_multiple['authoriser_scrs'] == 1)
    {
        $authorisation = ' (Auth)';
    }
    else
    {
        $authorisation = '';
    }
    return $authorisation;
}


if ($_POST['ButtonName'] == "Delete") {
    
    //echo("Delete ID " . $_POST['ID'] . "<br>");

    if($_POST['Team'] == 'Not Allocated')
    {
        $sql_delete = "Delete From tbl_authorise where ID  = " . $_POST['ID'];   
        echo($sql_delete . "<br>");     
        $update = $dbcnx_client->query($sql_delete);
    }
    else
    {
        // get team details from team name
        $sql_get_team_id = "Select * FROM vbsa3364_vbsa2.Team_entries where team_season = '" . $season . "' and team_cal_year = $year and team_name = '" . $_POST['Team'] . "'";
        //echo($sql_get_team_id . "<br>"); 
        $result_get_team_id = $dbcnx_client->query($sql_get_team_id);
        $row_get_team_id = $result_get_team_id->fetch_assoc();
        $team_id = $row_get_team_id['team_id'];

        $sql_get_scrs_id = "Select * FROM scrs where scr_season = '$season' and current_year_scrs = $year and team_id = $team_id and MemberID = " . $_POST['MemberID'];
        //echo($sql_get_scrs_id . "<br>"); 
        $result_get_scrs_id = $dbcnx_client->query($sql_get_scrs_id);
        $row_get_scrs_id = $result_get_scrs_id->fetch_assoc();
        $scrs_id = $row_get_scrs_id['scrsID'];

        $sql_delete = "Delete From tbl_authorise where ID  = " . $_POST['ID'];   
        //echo($sql_delete . "<br>");     
        $update = $dbcnx_client->query($sql_delete);

        $sql_scrs = "Update scrs SET captain_scrs = 0, authoriser_scrs = 0 where scrsID  = " . $scrs_id; 
        //echo($sql_scrs . "<br>");               
        $update = $dbcnx_client->query($sql_scrs);
    }
}

if ($_POST['ButtonName'] == "SaveEdited") {
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    $sql_auth = "Update tbl_authorise Set Access = '" . $packeddata[1] . "', Team_1 = '" . $packeddata[2] . "', Team_2 = '" . $packeddata[3] . "', Team_3 = '" . $packeddata[4] . "', Active = " . $packeddata[5] . ", Season = '" . $packeddata[6] . "'  where PlayerNo  = " . $packeddata[0];
    //echo("Save " . $sql_auth . "<br>");
    $update = $dbcnx_client->query($sql_auth);
    //echo("Save " . $sql_auth . "<br>");

    if($packeddata[1] != 'Administrator')
    {
        // update scrs table

        // get team details from team name
        $sql_get_team_id = "Select * FROM vbsa3364_vbsa2.Team_entries where team_season = '" . $season . "' and team_cal_year = $year and team_name = '" . $packeddata[2] . "'";
        //echo($sql_get_team_id . "<br>");
        $result_get_team_id = $dbcnx_client->query($sql_get_team_id);
        $row_get_team_id = $result_get_team_id->fetch_assoc();
        $team_id = $row_get_team_id['team_id'];
        $team_name = $row_get_team_id['team_name'];
        $team_grade = $row_get_team_id['team_grade'];
        $team_type = $row_get_team_id['comptype'];
        

        // get existing teams for player
        $sql_teams = "Select team_name FROM scrs Left Join Team_entries on Team_entries.team_id = scrs.team_id where scr_season = '" . $season . "' and current_year_scrs = $year and MemberID = " . $packeddata[0];
        $result_get_teams = $dbcnx_client->query($sql_teams);
        $i = 0;
        while($build_teams = $result_get_teams->fetch_assoc())
        {
            if($build_teams['team_name'] != '')
            {
                $existing_team_arr[$i] = $build_teams['team_name'];
                $i++;
            }
        }
        $team_arr = $existing_team_arr[0] . ", " . $existing_team_arr[1] . ", " . $existing_team_arr[2];
        $array1 = explode(", ", $team_arr);

        //echo(var_dump($array1) . "<br>");

        //echo(var_dump($packeddata) . "<br>");
        $y = 0;
        for($x = 2; $x < 5; $x++)
        {
            $new_team_arr[$y] = $packeddata[$x];
            $y++;
        }
        $new_team_arr = $new_team_arr[0] . ", " . $new_team_arr[1] . ", " . $new_team_arr[2];
        $array2 = explode(", ", $new_team_arr);

        //echo(var_dump($array2) . "<br>");

        $result = array_diff($array2, $array1);
        for($z = 0; $z < 3; $z++)
        {
            if($result[$z] != "")
            {
                $new_team = $result[$z];
            }
        }

        //echo("MemberID = " . $packeddata[0] . "<br>");
        //echo($new_team . "<br>"); 

        if($new_team != '')
        {
            // get team details from team name
            $sql_get_team_id = "Select * FROM vbsa3364_vbsa2.Team_entries where team_season = '" . $season . "' and team_cal_year = $year and team_name = '" . $new_team . "'";
            //echo($sql_get_team_id . "<br>");
            $result_get_team_id = $dbcnx_client->query($sql_get_team_id);
            $row_get_team_id = $result_get_team_id->fetch_assoc();
            $new_team_id = $row_get_team_id['team_id'];
            $new_team_grade = $row_get_team_id['team_grade'];
            $new_team_type = $row_get_team_id['comptype'];

            $authoriser_scrs = 1;
            $captain_scrs = 0;
            
            $sql = "Insert into scrs (MemberID, team_grade, game_type, scr_season, current_year_scrs, team_id, captain_scrs, authoriser_scrs) VALUES (" . $_POST['MemberID'] . ", '" . $new_team_grade . "', '" . $new_team_type . "', '" . $season . "', " . $year . ", " . $new_team_id . ", " . $captain_scrs . ", " . $authoriser_scrs . ")";               
            //echo($sql . "<br>"); 
            $update = $dbcnx_client->query($sql);
        }

        // Gets the incorrect team id if new team is in position 2 or 3.

        $sql_get_scrs_id = "Select * FROM scrs Left Join Team_entries on Team_entries.team_id = scrs.team_id where scr_season = '" . $season . "' and current_year_scrs = $year and scrs.team_id = $team_id and MemberID = " . $packeddata[0];
        //echo($sql_get_scrs_id . "<br>");
        $result_get_scrs_id = $dbcnx_client->query($sql_get_scrs_id);
        $row_get_scrs_id = $result_get_scrs_id->fetch_assoc();
        $num_rows = $result_get_scrs_id->num_rows;
        if($num_rows > 0)
        {
            $captain_scrs = 0;
            $authoriser_scrs = 0;
            if($packeddata[1] == 'Team Captain')
            {   
                $captain_scrs = 1;
            }
            else if($packeddata[1] == 'Team Authoriser')
            {
                $authoriser_scrs = 1;
            }
            $scrs_id = $row_get_scrs_id['scrsID'];

            $sql_scrs = "Update scrs SET MemberID = " . $packeddata[0] . ", team_grade='" . $team_grade . "', game_type='" . $team_type . "', scr_season='" . $season . "', team_id=$team_id, captain_scrs=$captain_scrs, authoriser_scrs=$authoriser_scrs WHERE scrsID=$scrs_id";
            //echo($sql_scrs . "<br>");
            $update = $dbcnx_client->query($sql_scrs);

            $sql = "Select * from tbl_authorise Where PlayerNo = " . $packeddata[0];
            $result = $dbcnx_client->query($sql);
            $build_data = $result->fetch_assoc();
            SREmail("scores@vbsa.org.au", $build_data['Name'], $build_data['Email'], $team_name, $team_grade);
        }  
    } 
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
    document.getElementById("function_type").value = function_type;
    document.authorise.Access.value = function_type;
    document.authorise.SelectSeason.value = document.getElementById("select_season").value
    document.getElementById("select_season").selected = document.getElementById("select_season").value
    document.authorise.submit();
}

function GetSeasonData(sel) 
{
    var select_season = sel.options[sel.selectedIndex].value;
    document.getElementById("select_season").value = select_season;
    document.authorise.SelectSeason.value = select_season;
    document.authorise.Access.value = document.getElementById("function_type").value
    document.getElementById("function_type").selected = document.getElementById("function_type").value
    document.authorise.submit();
}

function DeleteButton(MemberID, ID, access_id) 
{
    //alert(document.getElementById("ID_" + access_id).value);
    var access = document.getElementById("access_0").innerHTML;
    if(access != 'Administrator')
    {
        document.authorise.MemberID.value = MemberID;
        document.authorise.ID.value = document.getElementById("ID_" + access_id).value;
        document.authorise.Team.value = document.getElementById("club_1_" + access_id).innerHTML;
        document.authorise.ButtonName.value = "Delete"; 
        document.authorise.submit();
    }
    else
    {
        alert('You cannot delete the Administrator!');
    }
}

function EditButton(MemberID) 
{
    document.authorise.MemberID.value = MemberID;
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
    transferdata[6] = document.authorise.select_season.value;
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
//echo("Post Access " . $_POST['Access'] . "<br>");
if(isset($_POST['Access']) && ($_POST['Access'] != ''))
{
    $access = $_POST['Access'];
}
else
{
    $access = "Team Captain";
}

//echo("Post Season " . $_POST['SelectSeason'] . "<br>");
if(isset($_POST['SelectSeason']) && ($_POST['SelectSeason'] != ''))
{
    $select_season = $_POST['SelectSeason'];
}
else
{
    $select_season = $_SESSION['season'];
}

//echo("Season " . $select_season . "<br>");
//echo("Access " . $access . "<br>");


?>
<center>
<form name="authorise" method="post" action="authorise.php">
<input type="hidden" name="MemberID" />
<input type="hidden" name="ID" />
<input type="hidden" name="ButtonName" />
<input type="hidden" name="PackedData" />
<input type="hidden" name="Access" />
<input type="hidden" name="SelectSeason" />
<input type="hidden" name="Team" />
<?php
if ($_POST['ButtonName'] != "Edit User")
{
?>
<table class="table table-sm table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tr>
        <td align=right style="width:100px"><b>Select By Function&nbsp;</b></td>
        <td align='left' valign='top' style="width:100px"><select id="function_type" onchange="GetFunctionData(this);">
            <?php 
            if((isset($_POST['Access'])) && ($_POST['Access'] != ''))
            {
                echo("<option value='" . $_POST['Access'] . "'>" . $_POST['Access'] . "</option>");
            }
            else
            {
                echo("<option value='Team Captain'>Team Captain</option>");
            }
            ?>
            <option value=''>&nbsp;</option>
            <option value='Administrator'>Administrator</option>
            <option value='Team Administrator'>Team Administrator</option>
            <option value='Team Captain'>Team Captain</option>
            <option value='Team Authoriser'>Team Authoriser</option>
            </select>
        </td>
        <td align=right style="width:100px"><b>Select By Season&nbsp;</b></td>
        <td align='left' valign='top' style="width:100px"><select id="select_season" onchange="GetSeasonData(this);">
            <?php 
            if((isset($_POST['SelectSeason'])) && ($_POST['SelectSeason'] != ''))
            {
              echo("<option value='" . $_POST['SelectSeason'] . "'>" . $_POST['SelectSeason'] . "</option>");
            }
            else
            {
                echo("<option value='" . $_SESSION['season'] . "'>" . $_SESSION['season'] . "</option>");
            }
            ?>
            <option value=''>&nbsp;</option>
            <option value='S1'>S1</option>
            <option value='S2'>S2</option>
            </select>
        </td>  
    </tr>
</table>
<br>
<table class="table table-sm table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tr>
        <td>
            <form name='edit' method='post' action='authorise.php'>
            <table id="datatable-responsive" class="table table-sm table-striped table-bordered dt-responsive nowrap" cellspacing="10" width="100%">
             <thead>
                <tr>
                    <th class="text-center" rowspan=2>Players Name</th>
                    <th class="text-center" rowspan=2>Active Season</th>
                    <th class="text-center" rowspan=2>Access Type<br>(Team 1)</th>
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
                //echo("Access " . $access . "<br>");
                if($access == 'Administrator')
                {
                    $sql = "Select * from tbl_authorise, members Where (tbl_authorise.PlayerNo=members.MemberID AND Access = 'Administrator') Order By Team_1";
                }
                else
                {
                    $sql = "Select * from tbl_authorise, members Where (tbl_authorise.PlayerNo=members.MemberID AND Access = '" . $access . "' AND Season = '" . $select_season . "') Order By Team_1";
                }
                
                //echo($sql . "<br>");
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
                        echo("<input type='hidden' id='ID_" . $i . "' value=" . $build_data['ID'] . " />");
                        echo("<td id='player_name_" . $i . "' style='text-transform:capitalize'>" . $build_data['Name'] . "</td>");
                        echo("<td id='season_" . $i . "'>" . $build_data['Season'] . "</td>");
                        echo("<td id='access_" . $i . "'>" . $build_data['Access'] . "</td>");
                        $sql = "Select * from tbl_authorise where PlayerNo  = " . $build_data['PlayerNo'];
                        $result_select_club = $dbcnx_client->query($sql);
                        $build_club_data = $result_select_club->fetch_assoc();
                        $team1 = $build_club_data['Team_1'];
                        echo("<td id='club_1_" . $i . "'>" . $team1 . "</td>");
                        $team2 = $build_club_data['Team_2'];
                        $authorisation = Get_Authorisation($team2, $season, $year, $build_data['PlayerNo']);
                        echo("<td id='club_2_" . $i . "'>" . $team2 . $authorisation . "</td>");
                        $team3 = $build_club_data['Team_3'];
                        $authorisation = Get_Authorisation($team3, $season, $year, $build_data['PlayerNo']);
                        echo("<td id='club_3_" . $i . "'>" . $team3 . $authorisation . "</td>");
                        echo("<td id='email_" . $i . "'>" . $build_club_data['Email'] . "</td>");
                        echo("<td align=center><input type='checkbox' id='active_" . $i . "' " . $checked . "></td>");
                        echo("<td align=center><input type='checkbox' id='bulk_email_" . $i . "' " . $bulk_checked . "></td>");
                        echo("<td align='center' ><a class='btn btn-primary btn-xs' onclick='EditButton(" . $build_data['PlayerNo'] . ")'>Edit Record</a></td>");   
                        echo("<td><a class='btn btn-primary btn-xs' onclick='DeleteButton(" . $build_data['PlayerNo'] . ", " . $build_data['ID'] . ", " . $i . ")'>Delete Record</a></td>");   
                        echo("</td>");
                        echo("</tr>"); 
                        $i++;
                    } 
                    echo("<tr>"); 
                    echo("<td id='player_name_" . $i . "' style='text-transform:capitalize'>" . $build_data['Name'] . "</td>");
                    echo("<td colspan=5>&nbsp;</td>");
                    echo("<td align='center'>");
                    echo("<a class='btn btn-primary btn-xs' id='check_all_active' onclick='ToggleActiveButton(" . $i . ")'>Check All</a></td>"); 
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
                    <a class='btn btn-primary btn-xs' id='bulkemail' onclick='SaveBulkEmailButton(<?php echo($i); ?>)' style='width:250px'>Reset Password & Send E-mail to Selection</a>
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
    //echo($sql . "<br>");
    $edit_result = $dbcnx_client->query($sql);
    while ($edit_build_data = $edit_result->fetch_assoc())
    {
        $regoID = $_POST['MemberID'];
        $fullname = $edit_build_data['Name'];
        $email = $edit_build_data['Email'];
        $team1 = $edit_build_data['Team_1'];
        $team2 = $edit_build_data['Team_2'];
        $team3 = $edit_build_data['Team_3'];
        $access = $edit_build_data['Access'];
        $select_season = $edit_build_data['Season'];
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
<!--
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
                    <td rowspan=2 align='center'>Access Type<br>(Team 1)</td>                  
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
                        $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Where team_season = '" . $_SESSION['season'] . "' AND team_cal_year = " . $_SESSION['year'] . " Order By team_name");
                        if(isset($team1))
                        {
                            echo("<option value='" . $team1 . "' selected>" . $team1 . "</option>");
                        }
                        echo"<option value=''>&nbsp</option>";
                        //echo("<option value='Not Allocated'>Not Allocated</option>");
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                    ?>  
                        </select>
                    </td> 
                    <td align='center'><select name="club_name_2" id="club_name_2">
                    <?php    
                        $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Where team_season = '" . $_SESSION['season'] . "' AND team_cal_year = " . $_SESSION['year'] . " Order By team_name");
                        if(isset($team2))
                        {
                            echo("<option value='" . $team2 . "' selected>" . $team2 . "</option>");
                        }
                        echo"<option value=''>&nbsp</option>";
                        //echo("<option value='Not Allocated'>Not Allocated</option>");
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                    ?>  
                        </select>
                    </td> 
                    <td align='center'><select name="club_name_3" id="club_name_3">
                    <?php    
                        $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Where team_season = '" . $_SESSION['season'] . "' AND team_cal_year = " . $_SESSION['year'] . " Order By team_name");
                        if(isset($team3))
                        {
                            echo("<option value='" . $team3 . "' selected>" . $team3. "</option>");
                        }
                        echo"<option value=''>&nbsp</option>";
                        //echo("<option value='Not Allocated'>Not Allocated</option>");
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
                        <option value='Team Administrator'>Team Administrator</option>
                        <option value='Team Captain'>Team Captain</option>
                        <option value='Team Authoriser'>Team Authoriser</option>
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
</table>-->


<!--<table class='table table-striped table-bordered text-center' width='800px'>
  <tr>
    <td align='center'>Select by Player No.</td>
    <td align='center'><select id="rego_no" onchange="GetRegoData(this)">
        <option value="" selected="selected"></option>
        <?php    
           $sql = "Select MemberID from members Order By MemberID";
            $result1 = $dbcnx_client->query($sql);
            while ($build_data1 = $result1->fetch_assoc()) {
                echo"<option value=" . $build_data1['MemberID'] .">" . $build_data1['MemberID'] ."</option>";
            }  
        ?>                
        </select>
    </td>
    <td align='center'>Select by Name.</td>
    <td  align='center'><select id="fullname" onchange="GetNameData(this)">
         <option value="" selected="selected"></option>
        <?php    
            $sql = "Select * from members Order by LastName, FirstName";
            $result2 = $dbcnx_client->query($sql);
            while ($build_data2 = $result2->fetch_assoc()) {
                echo"<option value=" . $build_data2['MemberID'] . ">" . $build_data2['FirstName'] . " " . $build_data2['LastName'] . "</option>";
            }  
        ?>                
    </select></td>-->
    <!--<td align=right>Select a season&nbsp;</td>
    <td align='left' valign='top'><select id="select_season" onchange="GetSeasonData(this);">
        <option value='<?= $season ?>' selected='selected'><?= $season ?></option>
        <option value='S1'>Season 1</option>
        <option value='S2'>Season 2</option>
        </select>
    </td> --> 
<!--  </tr>
</table>-->
<br />
<style>
    .table {
    width: 50%;
    max-width: 100%;
    margin-bottom: 20px;
}
</style>
<!--<table class='table table-striped table-bordered dt-responsive nowrap display' width='800px'>
    <tr>
        <td>-->
            <table class='table table-striped table-bordered dt-responsive nowrap display'>
                <input name="member_rego" value="<?php echo $regoID; ?>" type='hidden'>
                <tr>
                    <td colspan=2 align=center><b>Edit User Details</b></td>
                </tr>
                <tr>
                    <td align=right>Full Name:</td>
                    <td align='left' valign='top'><input name="member_name" value="<?php echo $fullname; ?>" type='text' readonly></td>
                </tr>
                <tr>
                    <td align=right>Teams Administered (1)</td>
                    <td align='left' valign='top'><select name="club_name_1" id="club_name_1">
                    <?php 
                    $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Where team_season = '" . $_SESSION['season'] . "' AND team_cal_year = " . $_SESSION['year'] . " Order By team_name");
                        if(isset($team1))
                        {
                            echo("<option value='" . $team1 . "' selected>" . $team1. "</option>");
                        }
                        echo"<option value=''>&nbsp</option>";
                        echo("<option value='Temp Login'>Temp Login</option>");
                        echo"<option value=''>----------------</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                        ?>
                        </select>
                    </td> 
                </tr>
                <tr>
                    <td align=right>Teams Administered (2)</td>
                    <td align='left' valign='top'><select name="club_name_2" id="club_name_2">
                    <?php 
                    $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Where team_season = '" . $_SESSION['season'] . "' AND team_cal_year = " . $_SESSION['year'] . " Order By team_name");
                        if(isset($team2))
                        {
                            echo("<option value='" . $team2 . "' selected>" . $team2. "</option>");
                        }
                        echo"<option value=''>&nbsp</option>";
                        echo("<option value='Temp Login'>Temp Login</option>");
                        echo"<option value=''>----------------</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                        ?>
                        </select>
                    </td> 
                </tr>
                <tr>
                    <td align=right>Teams Administered (3)</td>
                    <td align='left' valign='top'><select name="club_name_3" id="club_name_3">
                    <?php 
                    $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Where team_season = '" . $_SESSION['season'] . "' AND team_cal_year = " . $_SESSION['year'] . " Order By team_name");
                        if(isset($team3))
                        {
                            echo("<option value='" . $team3 . "' selected>" . $team3. "</option>");
                        }
                        echo"<option value=''>&nbsp</option>";
                        echo("<option value='Temp Login'>Temp Login</option>");
                        echo"<option value=''>----------------</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                        ?>
                        </select>
                    </td> 
                </tr>
                    <td align=right>Active</td>
                    <td align=left><input type='checkbox' id='active' <?php echo $checked; ?>></td>
                <tr>
                    <td align=right>Email</td>     
                    <td align='left' valign='top'><input name="member_email" value="<?php echo $email; ?>" type='text' readonly></td>  
                </tr>
                <tr>             
                    <td align=right>Access Type (Team 1)</td>            
                    <td align='left' valign='top'><select name='rego_type'>
                        <option value='<?php echo $access; ?>'><?php echo $access; ?></option>
                        <option value='Administrator'>Administrator</option>
                        <option value='Team Administrator'>Team Administrator</option>
                        <option value='Team Captain'>Team Captain</option>
                        <option value='Team Authoriser'>Team Authoriser</option>
                        </select>
                    </td>                   
                </tr>  
                <tr>
                    <td align='right'>Select a season&nbsp;</td>
                    <td align='left' valign='top'><select id="select_season">
                        <option value='<?= $select_season ?>'><?= $select_season ?></option>
                        <option value='S1'>S1</option>
                        <option value='S2'>S2</option>
                        </select>
                    </td>  
                </tr>   
                <tr> 
                    <td colspan=2 align=center>&nbsp;</td>
                </tr>
                <tr> 
                    <td colspan=2 align=center>Only the User can change their password.</td>
                </tr>
                <tr> 
                    <td colspan=2 align=center><a class='btn btn-primary btn-xs' href="javascript:;" onclick="SaveEditedButton();">Save Edited Data</a></td>
                </tr>
            </table>
            </form>
        <!--</td>
    </tr>
</table>-->

<br /> 
</center>
</form>
<?php
}

include("footer.php"); 

?>