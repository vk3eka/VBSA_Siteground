<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include ("header.php");
include ("connection.inc");
include ("php_functions.php");

?>    
<script>

$(document).ready(function()
{
    $.fn.SaveButton = function (ID, index) {
        $('#savebutton_' + index).attr('disabled', 'disabled');
        var clubname = '<?= $_SESSION['clubname'] ?>';
        var player = ID;
        $.ajax({
            url:"<?= $url ?>/check_authorisation.php?ID=" + player + "&Team=" + clubname,
            success : function(data){
                alert(data);
                if(data == 'No Password')
                {
                    //add pwd and team to list
                    action = 'NewPassword';
                }
                else if(data == 'Not Listed')
                {
                    //add new entry to list
                    action = 'NewListing';
                }
                else if (data.indexOf("will be added") >= 0)
                {
                    //add new team to existing login
                    action = 'AddTeam';
                }
                $.ajax({
                    url:"<?= $url ?>/save_captain.php?ID=" + player + "&Team=" + clubname + "&Action=" + action,
                    success : function(data){
                        alert(data);
                        window.location.href = 'captain_authorise.php';
                    }
                });
            }
        });
    }
});

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
                        <th class='text-center'>Active Login</th>
                        <th>Email Address</th>
                        <th class='text-center'>Add Authoriser</th>
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
                    //echo($sql_player . "<br>");
                    $result_players = $dbcnx_client->query($sql_player);
                    $num_rows_player = $result_players->num_rows;
                    if($num_rows > 0)
                    {
                        while ($build_data_player = $result_players->fetch_assoc())
                        {
                            $sql_password = "Select Password from tbl_authorise where PlayerNo = " . $build_data_player['MemberID'];
                            //echo($sql_password . "<br>");
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
                            echo("<td align='center'><a id='savebutton_" . $i . "' class='btn btn-primary btn-xs'  href='javascript:;' onclick='$.fn.SaveButton(" . $build_data_player['MemberID'] . ", " . $i . "); 'style='width:200px'>Add as New Authoriser</a></td>");
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