<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');

$current_year = $_SESSION['year'];
$current_season = $_SESSION['season'];
$team = $_SESSION['clubname'];
$memberid = $_SESSION['playerID'];
$memberid = 171;

$sql_team_grade = "Select * FROM scrs where MemberID = " . $memberid . " and current_year_scrs = " . $current_year;
//echo($sql_team_grade . "<br>");
$result_team_grade = $dbcnx_client->query($sql_team_grade);
$row_team_grade = $result_team_grade->fetch_assoc();
$num_rows = $result_team_grade->num_rows;
if($num_rows > 0)
{
    $team_grade = $row_team_grade['team_grade'];
    $team_id = $row_team_grade['team_id'];
    //$team_id = 1397;
    
    $sql_club_id = "Select * FROM vbsa3364_vbsa2.Team_entries where team_id = " . $team_id;
    $result_club_id = $dbcnx_client->query($sql_club_id);
    //echo($sql_club_id . "<br>");
    $row_club_id = $result_club_id->fetch_assoc();
    $club_id = $row_club_id['team_club_id'];
    $team = $row_club_id['team_name'];

    // data for add player dropdown
    $players = array();
    $sql_players = "Select MemberID, FirstName, LastName From members Order By LastName";
    $result = $dbcnx_client->query($sql_players);
    $num_rows = $result->num_rows;
    if ($num_rows > 0) 
    {
    while($build_data = $result->fetch_assoc())
    {
      $firstname = $build_data['FirstName'];
      $lastname = $build_data['LastName'];
      $players[] = $build_data['FirstName'] . " " . $build_data['LastName']; 
    }
    $player_data = json_encode($players);
    }
    $result->free_result();

    ?>
    <!-- check jquery for autocomplete dropdown -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- end -->
    <script>

    $(document).ready(function()
    {
        var availableTags = <?php echo $player_data; ?>;
        $("#tags").autocomplete({
            source:  availableTags,
            appendTo: "#autocompleteAppendToMe"
        });

        $('#newplayer').click(function(event){
            event.preventDefault();
            if($('#rows').val() > 9)
            {
                alert("10 players have already been entered!");
                $('#tags').val('');
                return;
            }
            else
            {
                var teamgrade = $('#team_grade').val();
                var teamid = <?= $team_id ?>;
                var year = $('#year').val();
                var new_player = $('#tags').val();
                if(new_player == '')
                {
                    alert("No member to add!");
                    return;
                }
                $.ajax({
                    url:"<?= $url ?>/check_player.php?fullname=" + new_player,
                    success : function(data)
                    {
                        player_obj = jQuery.parseJSON(data);
                        player_id = player_obj[2];
                        $.ajax({
                            url:"<?= $url ?>/save_team_members.php?MemberID=" + player_id + "&TeamGrade=" + teamgrade + "&TeamID=" + teamid + "&Year=" + year,
                            success : function(data)
                            {
                                $('#tags').val('');
                                location.reload(true);
                            },
                            error: function (request, error) 
                            {
                                alert("Error");
                            }
                        });
                    }
                });
            }
        });

        $('#remove').click(function(event){
            event.preventDefault();
            for(i = 0; i < 10; i++)
            {
                if($('#delete_' + i).is(":checked"))
                {
                    $.ajax({
                        url:"<?= $url ?>/remove_player.php?scrs_id=" + $('#scrs_id_' + i).val(),
                        success : function(data)
                        {
                            location.reload(true);
                        }
                    });
                }
            }
        });

        $('#add_selected_players').click(function(event){
            event.preventDefault();
            var no_of_players = $('#no_of_players').val();
            var teamgrade = $('#team_grade').val();
            var teamid = <?= $team_id ?>;
            //alert(teamid);
            var year = $('#year').val();
            for(i = 0; i < no_of_players; i++)
            {
                if($('#select_' + i).is(":checked"))
                {
                    var player_id = $('#select_' + i).val();
                    //alert(player_id);
                    $.ajax({
                        url:"<?= $url ?>/save_team_members.php?MemberID=" + player_id + "&TeamGrade=" + teamgrade + "&TeamID=" + teamid + "&Year=" + year,
                        success : function(response)
                        {
                            //alert(response);
                            location.reload(true);
                        },
                        error: function (request, error) 
                        {
                            alert("Error");
                        }
                    });
                }
            }
            //alert(response);
        });
        
    });

    </script>
    <form id="form1" name="form1" method="post" action="select_team_players.php">
    <table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
        <tr>
            <td align='center'><h2>Team Selection for <?= $team ?></h2><h4>(<?= $team_grade ?>)</h4></td>
        </tr>
        <tr>
            <td><br>
                <?php 
                    if((basename($_SERVER["PHP_SELF"]) != 'index.php') && (basename($_SERVER["PHP_SELF"]) != 'main.php'))
                    {
                        $sql = ("Select * FROM members WHERE Email = '" . $_SESSION['username'] . "'");
                          $result_member = $dbcnx_client->query($sql);
                          $row_member = $result_member->fetch_assoc();
                          $firstname = $row_member["FirstName"];

                        echo("<div class='text-center'><i>" . $firstname . ", you are logged in as " . $_SESSION['login_rights'] . " for " . $_SESSION['clubname'] . ".</i></div>");
                        echo("<div class='text-center'><i>Current year  " . $_SESSION['year'] . ", season " . $_SESSION['season'] . ".</i></div>");
                    }
                ?>
                <br>
                <table class='table table-striped table-bordered dt-responsive display'>
                <?php
                $sql = "Select scrs.scrsID, scrs.scr_season, scrs.allocated_rp, scrs.game_type, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.captain_scrs, scrs.final_sub, scrs.maxpts, scrs.tier, members.MemberID, members.FirstName, members.LastName,
                members.Email, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade, count_played FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID AND Team_entries.team_id=scrs.team_id AND Team_entries.team_id=" . $team_id . " AND scrs.MemberID != 1 AND scrs.team_grade = '" . $team_grade . "' ORDER BY members.FirstName";
                //echo($sql . "<br>");
                $result_players = $dbcnx_client->query($sql);
                $num_rows = $result_players->num_rows;
                echo("<input type='hidden' id='rows' value='" . $num_rows . "' />");
                echo("<input type='hidden' id='season' value='" . $current_season . "' />");
                echo("<tr>"); 
                echo("<td colspan=2 align=center><b>Players currently in this team (9 max)</b></td>");
                echo("</tr>");
                echo("<tr>"); 
                echo("<td colspan=2 align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>"); 
                echo("<td align='center'>Name</td>");
                echo("<td align='center'>Remove</td>");
                echo("</tr>");
            	$x = 0;
                while ($build_data = $result_players->fetch_assoc()) 
                {
                    if($build_data['captain_scrs'] == 1)
                    {
                        $captain = " (Capt)";
                        $capt_checked = ' checked';
                        $disabled = ' disabled';
                    }
                    else
                    {
                        $captain = "";
                        $capt_checked = '';
                        $disabled = '';
                    }
                    // build array to check if existing players are already on the playing list
                    $arr_scrs_id[$x] = $build_data['FirstName'] . " " . $build_data['LastName'];

                    echo("<input type='hidden' id='scrs_id_". $x . "' value='" . $build_data['scrsID'] . "' />");
                    echo("<input type='hidden' id='player_id_". $x . "' value='" . $build_data['MemberID'] . "' />");
                    echo("<tr>"); 
                    echo("<td align='center'><input type='text' id='name_". $x . "' value='" . $build_data['FirstName'] . " " . $build_data['LastName'] . " " . $captain . "'></td>");
                    echo("<td align='center'>");
                    echo('<input type="checkbox" id="delete_' . $x . '" value=' . $x . ' ' . $disabled . '>');
                    echo("</td>");
                    echo("</tr>");
                    $x++;
                } 
                for($j = ($x+1); $j < 10; $j++)
                {
                    echo("<input type='hidden' id='scrs_id_". $j . "' value='" . $build_data['scrsID'] . "' />");
                    echo("<input type='hidden' id='player_id_". $j . "' value='" . $build_data['MemberID'] . "' />");
                    echo("<tr>");
                    echo("<td align='center'><input type='text' id='name_". $x . "'>&nbsp;</td>");
                    echo("<td align='center'>");
                    echo('<input type="checkbox" id="delete_' . $x . '" value="' . $build_data['MemberID'] . '">');
                    echo("</td>");
                    echo("</tr>");
                }
                ?>
                     <tr>
                        <td align='center' colspan=2><input type="button" value="Remove Selected Players" id='remove'/></td>
                    </tr>
                </table>
        	</td>
        </tr>
        <tr>
            <td>
                <table class='table table-striped table-bordered dt-responsive nowrap display'>
                <?php
                    $sql_team = "Select team_club_id, team_id, team_name, team_grade from Team_entries where team_name = '" . $team . "' Limit 1";
                    //echo($sql_team . "<br>");
                    $result_team = $dbcnx_client->query($sql_team);
                    $build_team = $result_team->fetch_assoc();
                    //$team_id = $build_team['team_id'];
                    $club_id = $build_team['team_club_id'];
                    $sql = "Select scrs.scrsID, scrs.MemberID, LastName, FirstName FROM Team_entries, scrs, members WHERE team_club_id = '$club_id' AND scrs.team_id = Team_entries.team_id AND scrs.MemberID = members.MemberID AND team_cal_year >= curdate() - interval 1 year AND (scrs.MemberID !=1 AND scrs.MemberID !=10 AND scrs.MemberID !=100 AND scrs.MemberID !=1000) GROUP BY members.MemberID ORDER BY LastName, FirstName";
                    //echo($sql . "<br>");
                    $result = $dbcnx_client->query($sql);
                    $previous_players = $result->num_rows;
                    echo("<tr>"); 
                    echo("<td colspan=2 align=center><b>Select players from previous players.</td>");
                    echo("</tr>");
                    echo("<tr>"); 
                    echo("<td colspan=2 align=center>&nbsp;</td>");
                    echo("</tr>");
                    echo("<tr>"); 
                    echo("<td align='left'>Name</td>");
                    echo("<td align='center'>Select</td>");
                    echo("</tr>");
                    echo("<input type='hidden' id='no_of_players' value='" . $previous_players . "' />");
                    $y = 0;
                    while ($build_list = $result->fetch_assoc()) 
                    {
                        if(!in_array(($build_list['FirstName'] . " " . $build_list['LastName']), $arr_scrs_id))
                        {
                            echo("<input type='hidden' id='player_id_". $y . "' value='" . $build_list['MemberID'] . "' />");
                            echo("<tr>"); 
                            echo("<td align='left'>" . $build_list['FirstName'] . " " . $build_list['LastName'] . "</td>");
                            echo('<td align="center"><input type="checkbox" id="select_' . $y . '" value="' . $build_list['MemberID'] . '"></td>');
                            echo("</tr>");
                        }
                        $y++;
                    } 
                ?>
                    <tr>
                        <td colspan='2' align='center'><input type="button" size=120 value="Add previous players from this club" id='add_selected_players'/></td>
                    </tr>
                </table>

                <table class='table table-striped table-bordered dt-responsive nowrap display'>
                    <input type="hidden" id="year" value="<?php echo $current_year; ?>" />
                    <input type="hidden" id="team_grade" value="<?php echo $team_grade; ?>" />
                    <input type="hidden" id="team_id" value="<?php echo $team_id; ?>" />
                    <tr>
                      <td colspan=2 class='text-center ui-widget'>
                        <label for='tags'>Select any players</label></td>
                    </tr>
                    <tr>
                      <td align='center' colspan=2><input id='tags'></td>
                    </tr>
                    <tr>
                        <td align='center' colspan=2><input type="button" size=120 value="Add any player" id='newplayer'/>
                            <div id='autocompleteAppendToMe'></div>
                        </td>
                    </tr>
                    <tr>
                        <td align='center' colspan=2>&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </form>
<?php
}
else
{
    echo("<table align=center>");
    echo("    <tr>");
    echo("      <td>&nbsp;</td>");
    echo("    </tr>");
    echo("    <tr>");
    echo("      <td>&nbsp;</td>");
    echo("    </tr>");
    echo("    <tr>");
    echo("      <td >&nbsp;</td>");
    echo("    </tr>");
    echo("    <tr>");
    echo("      <td>&nbsp;</td>");
    echo("    </tr>");
    echo("    <tr>");
    echo("      <td><b>No Captain Entries for that Membership ID</></td>");
    echo("    </tr>");
    echo("    <tr>");
    echo("      <td>&nbsp;</td>");
    echo("    </tr>");
    echo("    <tr>");
    echo("      <td>&nbsp;</td>");
    echo("    </tr>");
    echo("    <tr>");
    echo("      <td >&nbsp;</td>");
    echo("    </tr>");
    echo("</table>");
}

//include('footer.php'); 

?>