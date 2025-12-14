<?php
include('connection.inc');
include ("header.php");
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
    Sendemail($subject, $message, $email);
}

if (($_POST['MemberID'] != "") and ($_POST['ButtonName'] == "Add User")) {
    // get members name from rego number
    $sql = "Select * from members where MemberID  = " . $_POST['MemberID'];
    $result_select_rego = $dbcnx_client->query($sql);
    $row_data = $result_select_rego->fetch_assoc();
    $fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);
}

if ($_POST['ButtonName'] == "Save") {
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    $sql = "Select PlayerNo from tbl_authorise where PlayerNo  = " . $_POST['MemberID'];
    $result_select_rego = $dbcnx_client->query($sql);
    $num_rows = $result_select_rego->num_rows;
    if ($num_rows == 0){
        // get members name from rego number
        $sql = "Select * from members where MemberID  = " . $_POST['MemberID'];
        $result_select_rego = $dbcnx_client->query($sql);
        $row_data = $result_select_rego->fetch_assoc();
        $fullname = ($row_data['FirstName'] . " " . $row_data['LastName']);

        $sql = "Insert into tbl_authorise (Name, Password, Access, Team1, Team2, Team3, PlayerNo, Email, Active) VALUES ('" . $fullname . "', '" . password_hash($packeddata[0], PASSWORD_DEFAULT) . "', '" . $packeddata[1] . "', '" . $packeddata[2] . "', '" . $packeddata[3] . "', '" . $packeddata[4] . "', '" . $_POST['MemberID'] . "', '" . $packeddata[5] . "', 1)";   
        //echo("Save No Email " . $sql . "<br>");             
        $update = $dbcnx_client->query($sql);
        if(! $update )
        {
            die("Could not update data: " . mysqli_error($dbcnx_client));
        }   
        else
        {
            echo "<script type=\"text/javascript\">"; 
            echo "alert('Record Updated!')"; 
            echo "</script>";
        }
    }
    else
    {
        echo "<script type=\"text/javascript\">"; 
        echo "alert('User already exists!')"; 
        echo "</script>";    
    }   
}

if ($_POST['ButtonName'] == "SaveEmail") {
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
        // send email
        AuthoriseEmail($packeddata[1], $row_data['FirstName'], $row_data['MemberID'], $packeddata[0], $packeddata[5], $packeddata[2], $packeddata[3], $packeddata[4]);
        
        $sql = "Insert into tbl_authorise (Name, Password, Access, Team_1, Team_2, Team_3, PlayerNo, Email, Active) VALUES ('" . $fullname . "', '" . password_hash($packeddata[0], PASSWORD_DEFAULT) . "', '" . $packeddata[1] . "', '" . $packeddata[2] . "', '" . $packeddata[3] . "', '" . $packeddata[4] . "', '" . $_POST['MemberID'] . "', '" . $packeddata[5] . "', 1)"; 
        //echo("Save Email " . $sql . "<br>");                          
        $update = $dbcnx_client->query($sql);
        if(!$update )
        {
            die("Could not update data: " . mysqli_error($dbcnx_client));
        }  
        else
        {
            echo "<script type=\"text/javascript\">"; 
            echo "alert('Record Updated!')"; 
            echo "</script>";
        } 
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
    document.authorise.MemberID.value = member_number;
    document.authorise.ButtonName.value = "Add User"; 
    document.authorise.submit();
}

function GetNameData(sel) {
    var member_number = sel.options[sel.selectedIndex].value;
    document.authorise.MemberID.value = member_number;
    document.authorise.ButtonName.value = "Add User"; 
    document.authorise.submit();
}

function SaveButton() {
    var transferdata = new Array();
    transferdata[0] = document.authorise.member_password.value;
    transferdata[1] = document.authorise.rego_type.value;
    transferdata[2] = document.authorise.club_name_1.value;
    transferdata[3] = document.authorise.club_name_2.value;
    transferdata[4] = document.authorise.club_name_3.value;
    transferdata[5] = document.authorise.member_email.value;
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
    var data = JSON.stringify(transferdata);
    document.authorise.PackedData.value = data; 
    document.authorise.ButtonName.value = "SaveEmail"; 
    document.authorise.MemberID.value = document.authorise.member_rego.value;
    document.authorise.submit();
}

</script>

<form name="authorise" method="post" action="add_new_authorisation.php">
<input type="hidden" name="MemberID" value="" />
<input type="hidden" name="ButtonName" />
<input type="hidden" name="PackedData" />
<input type="hidden" name="Access" />
<table class='table table-striped table-bordered dt-responsive nowrap display text-center' width='100%'>
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
  </tr>
</table>
<br />
 <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
    <tr>
        <td>
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
                    <th rowspan=2 class="text-center">Access Type</th>  
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
                    // change slect from team_entries to tb;_fixtures. Field is fix1home not Team
                        $club_result = $dbcnx_client->query("Select Distinct fix1home from tbl_fixtures Order By fix1home");
                        echo("<option value='" . $row_data["Team"] . "'>" . $row_data["Team"] . "</option>");
                        echo"<option value=''>&nbsp</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['fix1home'] ."'>" . $club_build_data['fix1home'] ."</option>";
                        }         
                        ?>
                        </select>
                    </td> 

                    <td align='center'><select name="club_name_2" id="club_name_2">
                    <?php 
                    // change slect from team_entries to tb;_fixtures. Field is fix1home not Team
                        $club_result = $dbcnx_client->query("Select Distinct fix1home from tbl_fixtures Order By fix1home");
                        echo("<option value='" . $row_data["Team"] . "'>" . $row_data["Team"] . "</option>");
                        echo"<option value=''>&nbsp</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['fix1home'] ."'>" . $club_build_data['fix1home'] ."</option>";
                        }         
                        ?>
                        </select>
                    </td> 

                    <td align='center'><select name="club_name_3" id="club_name_3">
                    <?php 
                    // change slect from team_entries to tb;_fixtures. Field is fix1home not Team
                        $club_result = $dbcnx_client->query("Select Distinct fix1home from tbl_fixtures Order By fix1home");
                        echo("<option value='" . $row_data["Team"] . "'>" . $row_data["Team"] . "</option>");
                        echo"<option value=''>&nbsp</option>";
                        while ($club_build_data = $club_result->fetch_assoc()) {
                            echo"<option value='" . $club_build_data['fix1home'] ."'>" . $club_build_data['fix1home'] ."</option>";
                        }         
                        ?>
                        </select>
                    </td> 


                    <td align='center'><input name="member_email" value="<?php echo $row_data["Email"]; ?>" type='text' readonly></td>                 
                    <td align='center'><select name='rego_type'>
                        <option value='<?php echo $row_data["Access"]; ?>'><?php echo $row_data["Access"]; ?></option>
                        <option value='Administrator'>Administrator</option>
                        <option value='Team Captain'>Team Captain</option>
                        </select>
                    </td>					
                </tr>
                </tbody>
            </table>
            <br />
            <div class='text-center'>
                <a class='btn btn-primary btn-xs' href="javascript:;" onclick="GetPW();">Generate Password</a>
                <a class='btn btn-primary btn-xs' href="javascript:;" onclick="SaveButton();">Save Record Only</a>
                <a class='btn btn-primary btn-xs' href="javascript:;" onclick="SaveEmailButton();">Save Record & Email User</a>
            </div>
        </td>                  
    </tr>
</table>
</center>
</form>
            
<?php

include("footer.php"); 

?>