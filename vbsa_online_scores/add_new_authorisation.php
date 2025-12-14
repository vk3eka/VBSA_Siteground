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
    $subject = 'VBSA Scoring System Access'; 
    $message = '<html><body>';
    $message .= "<p>" . $firstname . "</p>";
    $message .= "<p>Here are your log-in details for the VBSA scoring system, the Scores Entry Webpage System (SEWS). You have been granted " . $access . " access for " . $team . ".</p>";
    $message .= "<p>Username " . $email . "</p>";
    $message .= "<p>Password " . $password . "</p>";
    $message .= "<p>Click <a href='https://vbsa.org.au/vbsa_online_scores'>here </a>to access the Scores Entry Webpage System (SEWS).</p>";
    $message .= "<p>Select 'Scoresheet Log In' from the menu item at far right of menu bar.</p>";
    $message .= "<p>Once logged in to SEWS, you can change your password, if you wish to.</p>";
    $message .= "<p>Instructions on how to use SEWS are located on the VBSA website Scores <a href='https://www.vbsa.org.au/VBSA_scores/scores_index.php'> page.</a></p>";
    $message .= "<p>Please note that a condition of being a SEWS Captain or Authoriser is that you agree to receive important or time sensitive messages regarding weekday Pennant.</p>";
    $message .= "<p>This does not override any other communication preferences you may have made with us.</p>";
    $message .= "<p>Your co-operation in this regard is appreciated.</p>";
    $message .= "<p>Thanks.</p>";
    $message .= "<p>VBSA Scores Registrar.</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p>" . date('d/m/Y') . "</p>";
    $message .= "<p>&nbsp;</p>";
    $message .= "<p><i>NOTE: Please do not reply to this email as this email address is not monitored.</i></p>";
    $message .= "<p><i>Direct all emails to <a href='mailto:scores@vbsa.org.au'>scores@vbsa.org.au</a></i></p>";
    $message .= "<img src='" . $url . "/MarkDunn.jpg' width = '400px' height = '140px'>";
    $message .= "</body></html>";
    Sendemail($subject, $message, $email);
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
   $message .= "<p>" . date('d/m/Y') . "</p>";
   $message .= "</body></html>";
   SendBulkEmail($subject, $message, $email);
}


if (($_POST['MemberID'] != "") and ($_POST['ButtonName'] == "Add User")) {
    // get members name from rego number
    $sql = "Select * from members where MemberID  = " . $_POST['MemberID'];
    $result_select_rego = $dbcnx_client->query($sql);
    $row_data = $result_select_rego->fetch_assoc();
    $fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);
}

if (($_POST['ButtonName'] == "Save") || ($_POST['ButtonName'] == "SaveEmail")) 
{
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    $sql = "Select PlayerNo from tbl_authorise where PlayerNo  = " . $_POST['MemberID'];
    $result_select_rego = $dbcnx_client->query($sql);
    $num_rows = $result_select_rego->num_rows;
    if ($num_rows == 0)
    {
        // get members name from rego number
        $sql = "Select * from members where MemberID  = " . $_POST['MemberID'];
        $result_select_rego = $dbcnx_client->query($sql);
        $row_data = $result_select_rego->fetch_assoc();
        $fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);

        $captain_scrs = 0;
        $authoriser_scrs = 0;
        $season = $packeddata[6];

        if($packeddata[1] == 'Team Captain')
        {   
            $captain_scrs = 1;
        }
        else if($packeddata[1] == 'Team Authoriser')
        {
            $authoriser_scrs = 1;
        }

        if($packeddata[2] !== 'Temp Login')
        {
            // add data to scrs table

            // get team details from team name
            $sql_get_team_id = "Select * FROM vbsa3364_vbsa2.Team_entries where team_season = '$season' and team_cal_year = $year and team_name = '$packeddata[2]'";
            //echo($sql_get_team_id . "<br>");     
            $result_get_team_id = $dbcnx_client->query($sql_get_team_id);
            $row_get_team_id = $result_get_team_id->fetch_assoc();
            $team_name = $row_get_team_id['team_name'];
            $team_id = $row_get_team_id['team_id'];
            $team_grade = $row_get_team_id['team_grade'];
            $team_type = $row_get_team_id['comptype'];
            $sql_get_scrs_id = "Select * FROM scrs where scr_season = '$season' and current_year_scrs = $year and team_id = $team_id and MemberID = " . $_POST['MemberID'];
            $result_get_scrs_id = $dbcnx_client->query($sql_get_scrs_id);
            $row_get_scrs_id = $result_get_scrs_id->fetch_assoc();
            $num_rows = $result_get_scrs_id->num_rows;
            if($num_rows > 0)
            {
                $scrs_id = $row_get_scrs_id['scrsID'];
                $sql = "Update scrs SET MemberID='" . $_POST['MemberID'] . "', team_grade='$team_grade', game_type='$team_type', scr_season='$season', team_id=$team_id, captain_scrs=$captain_scrs, authoriser_scrs=$authoriser_scrs WHERE scrsID=$scrs_id";
            }
            else
            {
                $sql = "Insert into scrs (MemberID, team_grade, game_type, scr_season, current_year_scrs, team_id, captain_scrs, authoriser_scrs) VALUES (" . $_POST['MemberID'] . ", '" . $team_grade . "', '" . $team_type . "', '" . $season . "', " . $year . ", " . $team_id . ", " . $captain_scrs . ", " . $authoriser_scrs . ")";   
                //echo($sql . "<br>");                 
            } 
            $update = $dbcnx_client->query($sql);
        }

        // insert data into authorise tabe

        $sql = "Insert into tbl_authorise (Name, Password, Access, Team_1, Team_2, Team_3, PlayerNo, Email, Active, Season) VALUES ('" . $fullname . "', '" . password_hash($packeddata[0], PASSWORD_DEFAULT) . "', '" . $packeddata[1] . "', '" . $packeddata[2] . "', '" . $packeddata[3] . "', '" . $packeddata[4] . "', " . $_POST['MemberID'] . ", '" . $packeddata[5] . "', 1, '" . $packeddata[6] . "')";  
        //echo($sql . "<br>");               
        $update = $dbcnx_client->query($sql);
        if(!$update )
        {
            die("Could not update data: " . mysqli_error($dbcnx_client));
        }   
        else
        {
            if($_POST['ButtonName'] == "SaveEmail")
            {
                // send email
                if($packeddata[5] != '')
                {
                    AuthoriseEmail($packeddata[1], $row_data['FirstName'], $row_data['MemberID'], $packeddata[0], $packeddata[5], $packeddata[2], $packeddata[3], $packeddata[4]);
                }
                SREmail("scores@vbsa.org.au", $fullname, $packeddata[5], $team_name, $team_grade);
            }
            //echo "<script type=\"text/javascript\">"; 
            //echo "alert('Record Updated!')"; 
            //echo "</script>";
        }
        echo "<script type=\"text/javascript\">"; 
        echo "alert('Record Updated!')"; 
        echo "</script>";
    }
    else
    {
        echo "<script type=\"text/javascript\">"; 
        echo "alert('User already exists!')"; 
        echo "</script>";    
    }   
}

?>
<script>

function GetPW()
{
    var generatedPW = '<?php echo(generatePassword(10)); ?>';
    document.getElementById("member_password").value = generatedPW;
}

function AddNewButton() {
    document.authorise.ButtonName.value = "Add User";
    document.authorise.submit();
}  

function GetRegoData(sel) {
    var member_number = sel.options[sel.selectedIndex].value;
    // Creating Our XMLHttpRequest object 
    let xhr = new XMLHttpRequest();
    // Making our connection  
    let url = 'get_player_id.php?MemberID=' + member_number;
    xhr.open("GET", url, true);
    // function execute after request is successful 
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText);
            if(this.responseText == 'Yes')
            {
                //alert(member_number + ", Existing");
                document.authorise.MemberID.value = member_number;
                document.authorise.ButtonName.value = "Edit User"; 
                document.authorise.action = 'authorise.php';
                document.authorise.submit();
            }
            else
            {
                //alert(member_number + ", Rego");
                document.authorise.MemberID.value = member_number;
                document.authorise.ButtonName.value = "Add User"; 
                document.authorise.submit();
            }
        }
    }
    // Sending our request 
    xhr.send();   
}


function GetSeasonData(sel) 
{
    var select_season = sel.options[sel.selectedIndex].value;
    document.authorise.SelectSeason.value = select_season;
    document.authorise.submit();
}

function GetNameData(sel) {
    var member_number = sel.options[sel.selectedIndex].value;
    // Creating Our XMLHttpRequest object 
    let xhr = new XMLHttpRequest();
    // Making our connection  
    let url = 'get_player_id.php?MemberID=' + member_number;
    xhr.open("GET", url, true);
    // function execute after request is successful 
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText);
            if(this.responseText == 'Yes')
            {
                //alert(member_number + ", Existing");
                document.authorise.MemberID.value = member_number;
                document.authorise.ButtonName.value = "Edit User"; 
                document.authorise.action = 'authorise.php';
                document.authorise.submit();
            }
            else
            {
                //alert(member_number + ", Rego");
                document.authorise.MemberID.value = member_number;
                document.authorise.ButtonName.value = "Add User"; 
                document.authorise.submit();
            }
        }
    }
    // Sending our request 
    xhr.send();   
}

/*
function GetNameData(sel) {
    var member_number = sel.options[sel.selectedIndex].value;
    alert(member_number + ", Name");
    document.authorise.MemberID.value = member_number;
    document.authorise.ButtonName.value = "Add User"; 
    document.authorise.submit();
}
*/

function SaveButton() {
    var transferdata = new Array();
    transferdata[0] = document.authorise.member_password.value;
    transferdata[1] = document.authorise.rego_type.value;
    transferdata[2] = document.authorise.club_name_1.value;
    transferdata[3] = document.authorise.club_name_2.value;
    transferdata[4] = document.authorise.club_name_3.value;
    transferdata[5] = document.authorise.member_email.value;
    transferdata[6] = document.authorise.select_season.value;
    var data = JSON.stringify(transferdata);
    document.authorise.PackedData.value = data; 
    document.authorise.ButtonName.value = "Save"; 
    document.authorise.MemberID.value = document.authorise.member_rego.value;
    document.authorise.submit();
}

function SaveEmailButton() {
    var transferdata = new Array();
    transferdata[0] = document.authorise.member_password.value;
    transferdata[1] = document.authorise.rego_type.value;
    transferdata[2] = document.authorise.club_name_1.value;
    transferdata[3] = document.authorise.club_name_2.value;
    transferdata[4] = document.authorise.club_name_3.value;
    transferdata[5] = document.authorise.member_email.value;
    transferdata[6] = document.authorise.select_season.value;
    var data = JSON.stringify(transferdata);
    document.authorise.PackedData.value = data; 
    document.authorise.ButtonName.value = "SaveEmail"; 
    document.authorise.MemberID.value = document.authorise.member_rego.value;
    document.authorise.submit();
}

</script>
<style>
    .table {
    width: 80%;
    max-width: 100%;
    margin-bottom: 20px;
}
</style>
<form name="authorise" method="post" action="add_new_authorisation.php">
<input type="hidden" name="MemberID" value="" />
<input type="hidden" name="ButtonName" />
<input type="hidden" name="PackedData" />
<input type="hidden" name="Access" />
<center>
<table class='table table-striped table-bordered text-center'>
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
    </select></td>
    <!--<td align=right>Select a season&nbsp;</td>
    <td align='left' valign='top'><select id="select_season" onchange="GetSeasonData(this);">
        <option value='<?= $season ?>' selected='selected'><?= $season ?></option>
        <option value='S1'>Season 1</option>
        <option value='S2'>Season 2</option>
        </select>
    </td> --> 
  </tr>
</table>
<br/>
<input name="member_rego" value="<?php echo $row_data["MemberID"]; ?>" type='hidden'>
<!--<table class='table table-striped table-bordered dt-responsive nowrap display text-center'>
    <tr>
        <td>-->
            <center>
            <table class='table table-striped table-bordered dt-responsive nowrap display text-center'>
                <tr>
                    <td colspan=2 align=center><b>Authorisation</b></td>
                </tr>
                <tr>
                    <td align=right>Full Name:</td>
                    <td align='left' valign='top'><input name="member_name" value="<?php echo $row_data["FirstName"] . " " . $row_data["LastName"]; ?>" type='text' readonly></td>
                </tr>
                <tr>
                    <td align=right>Password:</td>
                    <td align='left' valign='top'><input name="member_password" id="member_password" type='password' value='<?php echo $row_data["Password"]; ?>' readonly></td>
                </tr>
                <tr>
                    <td align=right>Teams Administered (1)</td>
                    <td align='left' valign='top'><select name="club_name_1" id="club_name_1">
                    <?php 
                    $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Where team_season = '" . $_SESSION['season'] . "' AND team_cal_year = " . $_SESSION['year'] . " Order By team_name");
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
                    <td align=right>Email</td>     
                    <td align='left' valign='top'><input name="member_email" value="<?php echo $row_data["Email"]; ?>" type='text' readonly></td>  
                </tr>
                <tr>             
                    <td align=right>Access Type (Team 1)</td>            
                    <td align='left' valign='top'><select name='rego_type'>
                        <option value='<?php echo $row_data["Access"]; ?>'><?php echo $row_data["Access"]; ?></option>
                        <option value='Administrator'>Administrator</option>
                        <option value='Team Administrator'>Team Administrator</option>
                        <option value='Team Captain'>Team Captain</option>
                        <option value='Team Authoriser'>Team Authoriser</option>
                        </select>
                    </td>                   
                </tr>  
                <?php
                //echo ($_SESSION['season'] . "<br>");
                if($_SESSION['season'] == 'S1') 
                { 
                    $S1_selected = ' selected';
                }
                else if($_SESSION['season'] == 'S2')
                {
                    $S2_selected = ' selected';
                }
                ?>
                <tr>
                    <td align='right'>Select a season&nbsp;</td>
                    <td align='left' valign='top'><select id="select_season">
                        <option value='S1' <?= $S1_selected ?>>S1</option>
                        <option value='S2' <?= $S2_selected ?>>S2</option>
                        </select>
                    </td>  
                </tr>   
            </table>
            </center>
                
<!--
            <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
                <thead>
                <tr>
                    <td colspan=7 align=center><b>Authorisation</b></td>
                </tr>
                <tr>
                    <th rowspan=2 class="text-center">Full Name:</th>
                    <th rowspan=2 class="text-center">Password:</th>
                    <th colspan=3 class="text-center">Teams Administered</th>
                    <th rowspan=2 class="text-center">Email</th>                  
                    <th rowspan=2 class="text-center">Access Type<br>(Team 1)</th>  
                </tr>
                <tr>
                    <th class="text-center">Team 1</th>
                    <th class="text-center">Team 2</th>
                    <th class="text-center">Team 3</th>  
                </tr>                
                </thead>
                <tbody>
                <tr>
                    <input name="member_rego" value="<?php echo $row_data["MemberID"]; ?>" type='hidden'>
                    <td align='center'><input name="member_name" value="<?php echo $row_data["FirstName"] . " " . $row_data["LastName"]; ?>" type='text' readonly></td>
                    <td align='center'><input name="member_password" id="member_password" type='password' value='<?php echo $row_data["Password"]; ?>' readonly></td>
                    <td align='center'><select name="club_name_1" id="club_name_1">
                    <?php 
                    $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Where team_season = '" . $_SESSION['season'] . "' AND team_cal_year = " . $_SESSION['year'] . " Order By team_name");
                        echo"<option value=''>&nbsp</option>";
                        echo("<option value='Temp Login'>Temp Login</option>");
                        echo"<option value=''>----------------</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                        ?>
                        </select>
                    </td> 
                    <td align='center'><select name="club_name_2" id="club_name_2">
                    <?php 
                        $club_result = $dbcnx_client->query("Select Distinct team_name from Team_entries Where team_season = '" . $_SESSION['season'] . "' AND team_cal_year = " . $_SESSION['year'] . " Order By team_name");
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
                        echo"<option value=''>&nbsp</option>";
                        //echo("<option value='Not Allocated'>Not Allocated</option>");
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['team_name'] ."'>" . $club_build_data['team_name'] ."</option>";
                        }         
                        ?>
                        </select>
                    </td> 
                    <td align='center'><input name="member_email" value="<?php echo $row_data["Email"]; ?>" type='text' readonly></td>                 
                    <td align='center'><select name='rego_type'>
                        <option value='<?php echo $row_data["Access"]; ?>'><?php echo $row_data["Access"]; ?></option>
                        <option value='Administrator'>Administrator</option>
                        <option value='Team Administrator'>Team Administrator</option>
                        <option value='Team Captain'>Team Captain</option>
                        <option value='Team Authoriser'>Team Authoriser</option>
                        </select>
                    </td>                   
                </tr>
                </tbody>
            </table>

-->
            <br />
            <div class='text-center'>
                <a class='btn btn-primary btn-xs' href="javascript:;" onclick="GetPW();">Generate Password</a>
                <a class='btn btn-primary btn-xs' href="javascript:;" onclick="SaveButton();">Save Record Only</a>
                <a class='btn btn-primary btn-xs' href="javascript:;" onclick="SaveEmailButton();">Save Record & Email User</a>
            </div>
        <!--</td>                  
    </tr>
</table>-->

</center>
</form>
            
<?php

include("footer.php"); 

?>