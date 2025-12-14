<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');
include('php_functions.php'); 
include('server_name.php'); 

//$sql_select = "Select * from tbl_team_select_visible where year=" . $_SESSION['year'] . " and season = '" . $_SESSION['season'] . "'";
$sql_select = "Select * from tbl_team_select_visible Order By year DESC Limit 1";
//$sql_select = "Select * from tbl_team_select_visible where year=2026 and season = 'S1'"; //.............................. Hard Coded
$result_select = $dbcnx_client->query($sql_select);
$build_select = $result_select->fetch_assoc();
if(($build_select['start_date'] == '0000-00-00'))
{
    $page_open = false;
}
elseif((date("Y-m-d") >= $build_select['start_date']) and (date("Y-m-d") <= $build_select['finish_date']))
{
    $page_open = true;
}
else
{
    $page_open = false;
}

//$current_year = $_SESSION['year'];
//$current_season = $_SESSION['season'];

$current_season = $build_select['season'];
$current_year = $build_select['year'];

$team = $_SESSION['clubname'];
$memberid = $_SESSION['playerID'];

$sql_captain = "Select MemberID, FirstName, LastName From members Where MemberID = " . $memberid . " Order By LastName";
$result = $dbcnx_client->query($sql_captain);
$build_data = $result->fetch_assoc();
$captain_name = $build_data['FirstName'] . " " . $build_data['LastName'];

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

?>
<style>
/* set to allow modal to scroll when the list is long.*/

.modal-body {
position: relative;
padding: 20px;
height: 600px;
overflow-y: scroll;
}

</style>
<!-- check jquery for autocomplete dropdown -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!-- end -->
<script>

$(document).ready(function()
{
    $(window).unload(function() {  
        event.preventDefault();
        alert("Window closing");
        var team_grade = $('input[name="grade"]:checked').val();
        var team_name = $('#team_tags').val();
        $.ajax({
            url:"<?= $url ?>/delete_rego_data.php?team_grade=" + team_grade + "&team_name=" + team_name,
            method: 'POST',
            success : function(response)
            {
                console.log('Window Closed');
            },
        });
        return;
    });

    var availableTags = <?php echo $player_data; ?>;
    $("#tags").autocomplete(
    {
        source:  availableTags,
        appendTo: "#autocompleteAppendToMe"
    });

    $.fn.get_team_id = function () {
        event.preventDefault();
        var team_grade = $('input[name="grade"]:checked').val();
        var club_name = $('#new_club').val();
        var team_name = $('#team_tags').val();
        var entered_by = "<?= $captain_name ?>";
        var year = <?= $current_year ?>;
        var season = '<?= $current_season ?>';
        var team_data;
        var team_id;
        var type;
        //console.log('team_name: ' + team_name + ' team_grade: ' + team_grade + ' club_name: ' + club_name + ' entered_by: ' + entered_by + ' year: ' + year + ' season: ' + season);
        $.ajax({
            url:"<?= $url ?>/get_team_id.php",
            data: {team_name: team_name, team_grade: team_grade, club_name: club_name, entered_by: entered_by, year: year, season: season},
            async: false,
            success : function(data)
            {
                //console.log('Team ID Data (func 1) ' + data);
                team_data = data.split(", ");
                $('#team_id').val(team_data[0]);
                $('#temp_team_id').val(team_data[0]);
                $('#temp_type').val(team_data[1]);
                //console.log('Team ID Data (func 2) ' + team_data[0] + ", " + team_data[1]);
                team_data = data;
                //return team_data;
            }
        });
        //console.log('Team ID Data (func 2) ' + team_data);
        return team_data;
    };

    // add emergency player to the database
    $.fn.add_emergency = function () {
        event.preventDefault();
        var club_name = $('#new_club').val();
        var team_name = $('#team_tags').val();
        var firstname = $('#em_first').val();
        var surname = $('#em_last').val();
        var email = $('#em_email').val();
        var mobile = $('#em_mobile').val();
        if($('#em_previous').prop('checked'))
        {
            var previous = 1;
        }
        else
        {
            var previous = 0;
        }
        var gender = $('#gender').val();
        var user = '<?php echo($_SESSION['username']); ?>';
        var year = '<?= $current_year ?>';
        var season = '<?= $current_season ?>';
        var team_grade = $('input[name="grade"]:checked').val();
        if($('#temp_team_id').val() == '')
        {
            var team_id = 0;
        }
        else
        {
            var team_id = $('#temp_team_id').val();
        }
        var type = $('#temp_type').val();
        if((firstname != '') && (surname != '') && (email != '') && (mobile != ''))
        {
            $.ajax({
                url:"<?= $url ?>/add_new_team_player.php?teamname=" + team_name + "&clubname=" + club_name + "&firstname=" + firstname + "&surname=" + surname + "&team_grade=" + team_grade + "&type=" + type + "&year=" + year + "&season=" + season + "&email=" + email + "&mobile=" + mobile + "&previous=" + previous + "&teamID=" + team_id + "&user=" + user + "&gender=" + gender,
                method: 'POST',
                success:function(response)
                {
                    $('#newplayer').attr('disabled', false);
                    $('#tags').val(''); 
                    $.fn.display_club_players();
                },
            });
            return true;
        }
        else
        {
          alert("All boxes should be filled.");
          return false;
        }
    }

    $('#newplayer').click(function(event)
    {
        event.preventDefault();
        var club_name = $('#new_club').val();
        var team_name = $('#team_tags').val();
        var team_grade = $('input[name="grade"]:checked').val();
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
                if(player_obj[2] == '') // brand new player
                {
                    $('#em_first').val(player_obj[0]);
                    $('#em_last').val(player_obj[1]);
                    $('#EmPlayer').modal('show');
                    $('#em_player').click(function (event) 
                    {
                        $('#newplayer').attr('disabled', true);
                        if($.fn.add_emergency() === true)
                        {
                            $('#EmPlayer').modal('hide');
                            $.fn.display_club_players();
                        }
                    });
                }
                else
                {
                    firstname = player_obj[0];
                    surname = player_obj[1];
                    var team_id = $('#temp_team_id').val();
                    $.ajax({
                        url:"<?= $url ?>/add_new_club_player.php?team_name=" + team_name + "&firstname=" + firstname + "&surname=" + surname + "&team_grade=" + team_grade + "&team_id=" + team_id + "&club_name=" + club_name,
                        method: 'POST',
                        success:function(response)
                        {
                            $('#tags').val(''); 
                            $('#add').empty();
                            $.fn.display_club_players();
                        },
                        error: function (request, error) 
                        {
                            alert("Error");
                        }
                    });
                }
            }
        });
    });

    $('#remove').click(function()
    {
        event.preventDefault();
        for(i = 0; i < 9; i++)
        {
            if($('#delete_' + i).is(":checked"))
            {
                //alert(i + " is checked");
                $('#captain_' + i).prop('checked',false);
                $('#authoriser_' + i).prop('checked',false);
                $.ajax({
                    url:"<?= $url ?>/remove_player.php?id=" + $('#scrs_id_' + i).val(),
                    success : function(data)
                    {
                        $.fn.display_team_players();
                    }
                });
            }
        }
    });

    $('#add_modal').click(function()
    {
        $('#waitModal').modal('show');
    });

    $('#add_to_team').click(function(event)
    {
        event.preventDefault();
        $("#confirmDialog").modal("show");
        $("#confirm").click(function(event)
        {
            event.preventDefault();
            $("#confirmDialog").modal("hide");
            //console.log("Confirmed");
            $.fn.save_team_data();
        });
        $("#cancel").click(function(event)
        {
            event.preventDefault();
            $("#confirmDialog").modal("hide");
            //console.log("Cancelled");
            return;
        });
    });

    // add player data to the database
    $.fn.save_team_data = function () {
        //console.log("Save data");
        //$('#add_to_team').prop('disabled', true);
        var team_grade = $('input[name="grade"]:checked').val();
        var club_name = $('#new_club').val();
        var team_name = $('#team_tags').val();
        var manager_id = <?= $_SESSION['playerID'] ?>;
        var year = <?= $current_year ?>;
        var season = '<?= $current_season ?>';
        var data = $.fn.get_team_id();
        var team_data = data.split(", ");
        var team_id = team_data[0];
        var type = team_data[1];
        var team_member_array = new Array;
        z = 0;
        // get players from list
        var player_count = 0;
        var check_count = 0;
        for(i = 0; i < 9; i++)
        {
            if($('#name_' + i).val() != '')
            {
                if($('#check_' + i).is(":checked"))
                {
                    check_count++;
                }
                //else
                //{
                //    check_count = 0;
                //}

                selected = 1;
                if($('#captain_' + i).is(":checked"))
                {
                    captain = 1;
                }
                else
                {
                    captain = 0;
                }
                if($('#authoriser_' + i).is(":checked"))
                {
                    authoriser = 1;
                }
                else
                {
                    authoriser = 0;
                }
                if($('#need_players').is(":checked"))
                {
                    need_players = 1;
                }
                else
                {
                     need_players = 0;
                }
                var player_id = $('#member_ID_' + i).val();
                team_member_array[z] = player_id + ", " + team_id + ", " + team_grade + ", " + captain + ", " + selected + ", " + type + ", " + team_name + ", " + manager_id + ", " + need_players + ", " + authoriser + ";";
                z++;
                player_count++;
                //console.log("Player " + player_count);
                //console.log("Check " + check_count);
            }
        }
        if(player_count > check_count)
        {
            alert('You need to check the details of all players.');
            return;
        }

        if(player_count >= 4)
        {
            //$('#waitModal').modal('show');
            var captain_check = 0;
            for(i = 0; i < 9; i++)
            {
                if($('#captain_' + i).is(":checked"))
                {
                    captain_check++;
                }
            }
            if(captain_check != 1)
            {
                alert("No Captain Selected!");
                return;
            }
            $('#waitModal').modal('show');
            $.ajax({
                url:"<?= $url ?>/save_team_members.php?members_array=" + team_member_array + "&team_grade=" + team_grade + "&team_name=" + team_name + "&year=" + year + "&season=" + season + "&team_id=" + team_id + "&club_name=" + club_name,
                method: 'POST',
                //async: false,
                success : function(response)
                {
                    $('#waitModal').modal('hide');
                    //window.location.href = "http://vbsa.cpc-world.com/VBSA_scores/scores_index_detail.php?season=" + season;
                    window.open('https://vbsa.org.au/VBSA_scores/scores_index_detail.php?season=' + season + '&year=' + year);
                    //window.open('<?= $url ?>/VBSA_scores/scores_index_detail.php?season=' + season + '&year=' + year);
                    window.location.target = '_blank';
                },
                error: function (request, error) 
                {
                    alert("Error " + error);
                }
            });
        }
        else
        {
            alert('You need 4 or more players entered.');
            return;
        }

    }

    //declare function to display players in modal box
    $.fn.display_club_players = function () 
    {
        $('#add').empty();
        var team_name = $('#team_tags').val();
        //var team_id = $('#team_id').val();
        var team_id = 9999;
        //alert(team_id);
        var club_name = $('#new_club').val();
        $('#modal_title').html(team_name);
        var team_grade = $('input[name="grade"]:checked').val();
        var obj = "";
        var fullname = "";
        $.ajax({
            url:"<?= $url ?>/get_club_players.php",
            data: {year: <?= $current_year ?>, team_grade: team_grade, clubname: club_name, team_name: team_name, team_id: team_id},
            success : function(data)
            {
                obj = jQuery.parseJSON(data);
                //console.log("Get Club Players " + obj);
                var output = "";
                output += ("<table class='table table-striped table-bordered dt-responsive nowrap display fetched-data' width='100%'>");
                output += ("<thead>");
                output += ("<tr>");
                output += ("<th style='text-align: center;'>Name</th>");
                output += ("<th style='text-align: center;'>Select Player</th>");
                output += ("</tr>");
                output += ("<thead>");
                output += ("<tbody>");
                for(k = 0; k < obj.length; k++)
                {
                    fullobj = obj[k].split(', ');
                    //console.log("Obj " + fullobj);
                    fullname = fullobj[0] + ' ' + fullobj[1];
                    if(fullobj[2] != 1) // remove Bye
                    {
                        output += ("<input type='hidden' id='member_id_" + k + "' value='" + fullobj[2] + "' />");
                        output += ("<input type='hidden' id='id_" + k + "' value='" + fullobj[7] + "' />");
                        output += ("<tr>");
                        output += ("<td align='left' style='width:250px' id='fullname_" + k + "' name='fullname_" + k + "'>" + fullname + "</td>");
                        if(fullobj[8] == 1)
                        {
                            output += ("<td align='center'><input type='checkbox' data-row='" + k + "' id='select_" + k + "' checked></td>");
                        }
                        else if(fullobj[8] == 0)
                        {
                            output += ("<td align='center'><input type='checkbox' data-row='" + k + "' id='select_" + k + "'></td>");
                        }
                        output += ("</tr>");
                    }
                }
                output += ("<input type='hidden' id='no_of_players' value=" + k + " />");
                output += ("</tbody>");
                output += ("</table>");
                $($.parseHTML(output)).appendTo('#add');
            }
        });
    };

    $('#add_players').click(function()
    {
        var error_caption = "";
        if(!$('input:radio[name="grade"]').is(':checked'))
        {
            error_caption += "Grade ";
        }
        if($('#new_club').val() == '')
        {
            error_caption += "Club ";
        }
        if($('#team_tags').val() == '')
        {
            error_caption += "Team ";
        }
        if(error_caption != "")
        {
            alert('The following data is missing, ' + error_caption);
            return;
        }
        //alert("Here");
        //$.fn.get_team_id();
        $.fn.display_club_players();
        $('#myPlayers').modal('show');
    });

    $.fn.display_team_players = function()
    {
        var team_grade = $('input[name="grade"]:checked').val();
        var team = $('#team_tags').val();
        //alert("Display");
        $.ajax({
            url:"<?= $url ?>/get_team_players.php",
            data: {year: <?= $current_year ?>, team_grade: team_grade, team_name: team},
            success : function(data)
            {
                obj = jQuery.parseJSON(data);
                //console.log(data);
                var x = 0;
                // clear existing list
                for(k = 0; k < 9; k++)
                {
                    $("input[id='name_" + x + "']").val('');
                    $("input[id='scrs_id_" + x + "']").val('');
                    $('#captain_' + x).prop('checked',false);
                    $('#authoriser_' + x).prop('checked',false);
                    $('#delete_' + x).prop('checked',false);
                    x++;
                }
                y = 0;
                for(k = 0; k < obj.length; k++)
                {
                    fullobj = obj[k].split(', ');
                    if(fullobj[8] == 1)
                    {
                        fullname = fullobj[0] + ' ' + fullobj[1];
                        $("input[id='name_" + y + "']").val(fullname);
                        $("input[id='scrs_id_" + y + "']").val(fullobj[7]);

                        if(fullobj[9] == 4)
                        //if(fullobj[9] == 1)
                        {
                            $('#captain_' + y).prop('checked',true);
                        }
                        else
                        {
                            $('#captain_' + y).prop('checked',false);
                        }
                    }
                    y++;
                }
            }
        });
    };

    $('#populate_positions').click(function(event)
    {
        event.preventDefault();
        var club_player_selected = 0;
        var no_of_players = $('#no_of_players').val();
        for(x = 0; x < no_of_players; x++)
        {
            if($('#select_' + x).is(":checked"))
            {
                club_player_selected++;
            }
        }
        if(club_player_selected > 9)
        {
            alert("9 players have already been entered!");
            $('#tags').val('');
            return;
        }
        //alert(club_player_selected);
        if(club_player_selected < 4)
        {
            alert("Minimum 4 players required!");
            $('#tags').val('');
            return;
        }
        var no_of_checked = 0;
        var year = '<?= $current_year ?>';
        var season = '<?= $current_season ?>';
        var club_name = $('#new_club').val();
        var team_name = $('#team_tags').val();
        var team_grade = $('input[name="grade"]:checked').val();
        //var team_id;
        var x = 0;
        // clear existing list
        for(k = 0; k < 9; k++)
        {
            $("input[id='name_" + x + "']").val('');
            $("input[id='scrs_id_" + x + "']").val('');
            $('#captain_' + x).prop('checked',false);
            $('#authoriser_' + x).prop('checked',false);
            $('#delete_' + x).prop('checked',false);
            x++;
        }
        var selected = 0;
        var captain = 0;
        var authoriser = 0;
        for(i = 0; i < no_of_players; i++)
        {
            if($('#select_' + i).is(":checked"))
            {
                selected = 1;
                if($('#captain_' + i).is(":checked"))
                {
                    captain = 1;
                }
                if($('#authoriser_' + i).is(":checked"))
                {
                    authoriser = 1;
                }
                $('#member_ID_' + no_of_checked).val($('#member_id_' + i).val());
                var memberid = $('#member_ID_' + no_of_checked).val();
                $('#name_' + no_of_checked).val($('#fullname_' + i).html());
                $('#scrs_id_' + no_of_checked).val($('#id_' + i).val());
                $.ajax({
                    url:"<?= $url ?>/add_team_players.php",
                    data: {MemberID: memberid, TeamName: team_name, TeamGrade: team_grade, Captain: captain, Authoriser: authoriser, Selected: selected, ClubName: club_name},
                    success : function(response)
                    {
                        //alert(response);
                    },
                    error: function (request, error) 
                    {
                        alert("Error, " + error + ".");
                    }
                });
                no_of_checked++;
            }
        }

        $('#myPlayers').modal('hide');
    });

    $('#team_tags').on('focusout', function()
    {
        var error_caption = "";
        if(!$('input:radio[name="grade"]').is(':checked'))
        {
            error_caption += "Grade ";
        }
        if($('#new_club').val() == '')
        {
            error_caption += "Club ";
        }
        if($('#team_tags').val() == '')
        {
            error_caption += "Team ";
        }
        if(error_caption != "")
        {
            alert('The following data is missing, ' + error_caption);
            return;
        }
        /* // disabled at the request of Mark.
        
        var year = '<?= $current_year ?>';
        var season = '<?= $current_season ?>'
        var team_name = $('#team_tags').val();
        team_name = $.trim(team_name);
        $.ajax({
            url:"<?= $url ?>/check_team_exists.php?team_name=" + team_name + "&year=" + year + "&season=" + season,
            success : function(response)
            {
                if(response === 'true')
                {
                     alert(team_name + " is already in use!");
                     $('#team_tags').val('')
                     return;
                }
            }
        });
        */
        $.fn.display_team_players();
    });

    $(".captain").change(function() 
    {
        $(".captain").prop('checked', false);
        $(this).prop('checked', true);
    });

    $("#un_select").click(function() 
    {
        x = 0;
        for(k = 0; k < 9; k++)
        {
            $('#captain_' + x).prop('checked',false);
            x++;
        }
    });

    $('.check_email').click(function() {
        let id = $(this).attr('id');
        let index = id.split('_')[1];
        if ($('#member_ID_' + index).val() == '')
        {
            alert("No player to check.");
            $('#check_' + index).prop('checked',false);
            return;
        }
        else
        {
            $('#check_details').modal('show');
            let email_id = $('#member_ID_' + index).val();
            $.ajax({
                url: "<?= $url ?>/get_player_details.php?memberID=" + email_id,
                success: function(data) {
                    let obj = jQuery.parseJSON(data);
                    $('#email_member_id').val(obj[0]);
                    $("#email_first").val(obj[1]);
                    $("#email_last").val(obj[2]);
                    $("#email_email").val(obj[3]);
                    $("#email_mobile").val(obj[4]);
                }
            });
        }
        
    });

    $("#save_edits").click(function() 
    {
        member_id = $('#email_member_id').val();
        firstname = $("#email_first").val();
        lastname = $("#email_last").val();
        email = $("#email_email").val();
        mobile = $("#email_mobile").val();
        $.ajax({
            url:"<?= $url ?>/save_player_details.php?memberID=" + member_id + "&Firstname=" + firstname + "&LastName=" + lastname + "&Email=" + email + "&MobilePhone=" + mobile,
            success : function(response)
            {
                alert("Edits Saved");
            }
        });
    });

    $("#no_change").click(function() 
    {
        //alert("No Changes Made");
        return;
    });

});

</script>
<?php 
if($page_open === true) // display page only if open
{
?>
<form id="form1" name="form1" method="post" action="team_registration.php">
<table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
    <tr>
        <td align='center'><h2>VBSA Team Registration</h2><br>IMPORTANT.  Click <a href='https://www.vbsa.org.au/fix_upload/Team_Registration_Preamble.pdf' target='_blank'>HERE</a> to see Pennant Conditions, including Fees, for this Season.<br><font color="red">* required</font></td>
    </tr>
    <tr>
        <td align='center'>(For <?= $current_season ?>, <?= $current_year ?>)</h2></td>
    </tr>
    <tr>
        <td>
        <?php
            echo('<table class="table table-striped table-bordered dt-responsive display text-center">');
            echo('<tr>');
            echo("<td align='left'>Who is completing this Registration?</td>");
            echo('<td align="center">' . $captain_name . '</td>');
            echo('</r>');
            echo('<tr>');
            echo('<td align="left"><font style="border-style: solid; border-color: black; background-color: red; color: white;">STEP 1</font> - Select Grade<font color="red">&nbsp;*</font></td>');
            echo('<td align="left">');
            $sql_grade = "Select grade, grade_name, dayplayed from Team_grade where current = 'Yes' and fix_cal_year = " . $current_year . " and season = '" . $current_season . "' order by grade";
            $result_grade = $dbcnx_client->query($sql_grade);
            $rows = $result_grade->num_rows;
            //echo($rows . "<br>");
            if($rows > 0)
            {
                while($build_grade = $result_grade->fetch_assoc()) 
                {
                    echo('<input type="radio" id="' . $build_grade['grade'] . '" name="grade" value="' . $build_grade['grade'] . '">');
                    echo('<label for="' . $build_grade['grade'] . '">&nbsp;' . $build_grade['grade'] . '&nbsp;' . $build_grade['grade_name'] . '&nbsp;' . $build_grade['dayplayed'] . '</label><br>');
                }
            }
            else
            {
                echo("No Grades available.");
            }
            
            echo('</td>');
            echo('</tr>');
            echo('<tr>');
            echo('<tr>');
            echo('<td align="left"><font style="border-style: solid; border-color: black; background-color: red; color: white;">STEP 2</font> - Select or Enter Club Name<font color="red">&nbsp;*</font></td>');
            echo('<td align="center"><select id="new_club" style="width:300px; height:25px;">');
            echo("<option value=''>&nbsp;</option>");
            $sql_club = "Select ClubTitle from clubs where inactive = 1 Order By ClubTitle";
            $result_club = $dbcnx_client->query($sql_club);
            while($build_club = $result_club->fetch_assoc()) 
            {
            echo("<option value='" . $build_club['ClubTitle'] . "'>" . $build_club['ClubTitle'] . "</option>");
            }
            echo("</select></td>");
            echo('</tr>');
            echo('<tr>');
            echo('<td align="left" class="ui-widget" id="new_team"><font style="border-style: solid; border-color: black; background-color: red; color: white;">STEP 3</font> - Enter Team Name<font color="red">&nbsp;*</font></td>');
            echo('<td align="center"><input id="team_tags"  style="width:300px; height:25px;">');
            echo('</td>');
            echo('</tr>');
            echo('<tr>');
            echo('<td colspan="2"><font style="color: red;">PLEASE PROVIDE A UNIQUE TEAM NAME, INCLUDING YOUR CLUB NAME BUT NOT JUST YOUR CLUB NAME E.G. FRANKSTON RSL 147</font></td>');
            echo('</tr>');
            echo("<input type='hidden' id='team_id' name='team_id'>");
            echo("</table>");
        ?>
        </td>
    </tr>
</table>
<input type='hidden' id='team_id'>
<table class='table table-striped table-bordered dt-responsive display'>
    <tr>
        <td colspan=5 align=center><b>Players currently in this team (9 max)</b></td>
    </tr>
    <tr>
        <td align=right><font style="border-style: solid; border-color: black; background-color: red; color: white;">STEP 4</font></td>
   
        <td class="text-left" colspan=2><a class="btn btn-primary btn-xs" id="add_players" style='width:250px'>Select Players from here</a></td>
    </tr>
    <tr>
        <td colspan=5 align=center>&nbsp;</td>
    </tr>
    <tr>
        <td align='center'>Name</td>
        <td align='center'>Captain<br><font style="color: red;">Able to enter results.<br>Will have/be issued with a SEWS User Login.</font></td>
        <td align='center'>Authoriser<br><font style="color: red;">Not a Captain but able to enter results.<br>Will have/be issued with a SEWS User Login.</font></td>
        <td align='center'>Remove<br><font style="color: red;">Remove the player<br>from the list.</font></td>
        <td align='center'>Check Details<font color="red">&nbsp;*</font><br><font style="color: red;">Check all player details.<br>Make changes as required.</font></td>
    </tr>
<?php
for($j = 0; $j < 9; $j++)
{
    echo("<tr>");
    echo("<input type='hidden' id='member_ID_". $j . "'>");
    echo("<input type='hidden' id='scrs_id_". $j . "'>");
    echo("<td align='center'><input type='text' id='name_". $j . "' readonly>&nbsp;</td>");
    echo("<td align='center'>");
    echo('<input type="checkbox" id="captain_' . $j . '" name="captain_' . $j . '[]" class="captain">');
    echo("</td>");
    echo("<td align='center'>");
    echo('<input type="checkbox" id="authoriser_' . $j . '" name="captain_' . $j . '[]">');
    echo("</td>");
    echo("<td align='center'>");
    echo('<input type="checkbox" id="delete_' . $j . '">');
    echo("</td>");
    echo("<td align='center'>");
    echo('<input type="checkbox" id="check_' . $j . '" class="check_email">');
    //echo("<a class='btn btn-primary btn-xs check_email' id=check_" . $j . " style='width:20px'>X</a>");
    echo("</td>");
    echo("</tr>");
}
?>
    <tr>
        <td class="text-center" colspan=5><a class="btn btn-default btn-xs" id="remove" style='width:250px'>Remove Selected Players</a></td>
    </tr>
    <tr>
        <td class="text-center" colspan=5><font style="border-style: solid; border-color: black; background-color: red; color: white;">STEP 6</font>&nbsp;&nbsp;&nbsp;<input type='checkbox' name='need_players' id='need_players' >&nbsp;&nbsp;If you are looking for more players to be part of your team tick this box and it will be advertised on our web page for prospective players to contact you.</font></td>
    </tr>
    <tr>
        <td align=right><font style="border-style: solid; border-color: black; background-color: red; color: white;">STEP 6A</font></td>
        <td class="text-left" colspan=4><a class="btn btn-primary btn-xs" id="add_to_team" style='width:250px'>Submit Team</a></td>
    </tr>
    <tr>
        <td colspan=5 align=center><font color="red"><i><b>(Please stay on this page until confirmation of team submission is displayed before continuing)</b></i></font></td>
    </tr>
</table>


<!-- Please Wait Modal -->
<div class="modal fade" id="waitModal" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><center>Registration in Progress</center></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style='max-height: 20vh; overflow-y: auto;'>
        <div class='text-center'><b>Please wait until registration process is complete.</b></div>
        <center>
            <div class="text-center loader"></div>
        </center>
        <div class='text-center'><b>You will be redirected to the VBSA Web Site on completion.</b></div>
        <style>
            .loader {
              width: 50px;
              --b: 8px; 
              aspect-ratio: 1;
              border-radius: 50%;
              padding: 1px;
              background: conic-gradient(#0000 10%,#f03355) content-box;
              -webkit-mask:
                repeating-conic-gradient(#0000 0deg,#000 1deg 20deg,#0000 21deg 36deg),
                radial-gradient(farthest-side,#0000 calc(100% - var(--b) - 1px),#000 calc(100% - var(--b)));
              -webkit-mask-composite: destination-in;
                      mask-composite: intersect;
              animation:l4 1s infinite steps(10);
            }
            @keyframes l4 {to{transform: rotate(1turn)}}
        </style>

      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
      </div>
    </div>
  </div>
</div>

<!-- Select Players Modal -->
<div class="modal fade" id="myPlayers" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ui-front">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id='modal_title' class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div><input type='hidden' id='temp_team_id'><input type='hidden' id='temp_type'></div>
                <div class='text-center'><b>Players that previously played for this club.</b></div>
                <br>
                <div id='add'></div>
                <table class='table table-striped table-bordered dt-responsive display'>
                    <tr>
                        <td align=left><font style="border-style: solid; border-color: black; background-color: red; color: white;">STEP 5B</font></td>
                        <td class="text-left" colspan=2><a class='btn btn-primary btn-xs' style='width:250px' id='populate_positions'>Add Selected Players to Team</a></td>
                    </tr>
                    <tr>
                        <td colspan=3  class='text-center'><i>This action will clear any existing players.</i></td>
                    </tr>
                </table>
                <br>
                <table class='table table-striped table-bordered dt-responsive display'>
                    <tr>
                        <td colspan=3  class='text-center ui-widget'>
                        <label for='tags'>Enter Name in this field to add players from other Clubs or New players</label>
                        <input id='tags'></td>
                    </tr>
                    <tr>
                        <td colspan=3  class='text-center'>If player is listed in the drop down, select it, if not, you will be requested to provide player details.</td>
                    </tr>
                    <div id='autocompleteAppendToMe'></div>
                    <tr>
                        <td><font style="border-style: solid; border-color: black; background-color: red; color: white;">STEP 5A</font></td>
                        <td colspan=2  class='text-left'><a class='btn btn-primary btn-xs' style='width:250px' id='newplayer'>Add to List</a></td>
                    </tr>
                    <tr>
                        <td colspan=3 align=center><font color="red"><i><b>(Please wait until player is added to above list before continuing)</b></i></font></td>
                    </tr>
                    <input type='hidden' id='temp_first'>
                    <input type='hidden' id='temp_last'>
                </table>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            </div>
        </div>
    </div>
</div>

<!-- Emergency Player Modal -->
<div class="modal fade" id="EmPlayer" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Player Name</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
          <tr>
            <td>Firstname:</td><td><input type='text' id='em_first' style='width:200px; text-transform: capitalize;' placeholder="Firstname"></td>
          <tr>
          </tr>
            <td>Lastname:</td><td><input type='text' id='em_last' style='width:200px; text-transform: capitalize;'></td>
          <tr>
          </tr>
            <td>Email Address:</td><td><input type='text' id='em_email' style='width:200px;'></td>
          </tr>
          </tr>
            <td>Mobile No.:</td><td><input type='text' id='em_mobile' style='width:200px;'></td>
          </tr>
          <tr>
            <td>Gender</td>
            <td align="left">
              <select id="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="nonbinary">Non-Binary</option>
                <option value="notsay">Prefer not to say</option>
              </select>
            </td>
          </tr>
          </tr>
            <td>Have they ever played VBSA Pennant before?</td><td><input type='checkbox' id='em_previous' style='width:200px;'></td>
          </tr>
          <tr>
            <td colspan=2><div id='add_previous'></div></td>
          </tr>
          </tr>
        </table>
        <div class='text-center'>All boxes should be filled.</div>
        <br>
        <div class='text-center'>You must now lodge an online VBSA Membership form for this new player.  See <a href='https://vbsa.org.au/Admin_DB_VBSA/membership_application_online.php' target="_blank">https://vbsa.org.au/Admin_DB_VBSA/membership_application_online.php.</a></div>
        <br>
        <br>
        <div class='text-center'><a class='btn btn-primary btn-xs' id='em_player'>Add to List</a></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Check Details Modal -->
<div class="modal fade" id="check_details" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Check Player Details</h4>
        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
      </div>
      <div class="modal-body" style='height: 50%;'>
        <input type='hidden' id='email_member_id'>
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
          <tr>
            <td>Firstname:</td><td><input type='text' id='email_first' style='width:200px; text-transform: capitalize;'></td>
          <tr>
          </tr>
            <td>Lastname:</td><td><input type='text' id='email_last' style='width:200px; text-transform: capitalize;'></td>
          <tr>
          </tr>
            <td>Email Address:</td><td><input type='text' id='email_email' style='width:200px;'></td>
          </tr>
          </tr>
            <td>Mobile No.:</td><td><input type='text' id='email_mobile' style='width:200px;'></td>
          </tr>
        </table>
        <table class='table table-striped table-bordered dt-responsive display'>
            <tr>
                <td class="text-left" colspan=2><a class='btn btn-primary btn-xs' style='width:250px' data-dismiss="modal" id='no_change'>No Changes</a></td>
                <td class="text-left" colspan=2><a class='btn btn-primary btn-xs' style='width:250px' data-dismiss="modal" id='save_edits'>Save Edits</a></td>
            </tr>
        </table>
      </div>    
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Check Details Modal -->
<div class="modal fade" id="confirmDialog" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body" style='height: 50%;'>
        <p>Please note that to ensure that all SEWS Captains & Authorisers receive highly important or time sensitive messages regarding weekday Pennant, it has been assumed that ALL Captains & Authorisers are agreeable to receiving such messages by SMS.</p>
        <p>If you have previously advised you do not wish to receive SMS messages from the VBSA, this will be disregarded WHERE IT PERTAINS TO WEEKLY PENNANT ONLY.</p>
        <p>Your co-operation in this regard is appreciated.</p>
        <p>If you wish to modify your selections for Captains & Authorisers, please click the CANCEL button to return to the main page.</p>
      </div>    
      <table class='table table-striped table-bordered dt-responsive display'>
            <tr>
                <td class="text-center" colspan=2><a class='btn btn-success btn-xs' style='width:50px' data-dismiss="modal" id='confirm' value='Yes'>Confirm</a></td>
                <td class="text-center" colspan=2><a class='btn btn-danger btn-xs' style='width:50px' data-dismiss="modal" id='cancel' value='No'>Cancel</a></td>
            </tr>
        </table>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</form>
<?php 
}
else
{
    echo("<div align='center'>&nbsp;</div>");
    echo("<div align='center'>&nbsp;</div>");
    echo("<div align='center'>&nbsp;</div>");
    echo("<div align='center'>&nbsp;</div>");
    echo("<div align='center'>&nbsp;</div>");
    echo("<div align='center'><b>This page is not open for new team registrations yet!</b></div>");
}
include('footer.php'); 
?>


