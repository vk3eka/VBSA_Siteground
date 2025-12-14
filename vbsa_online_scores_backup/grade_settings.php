<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include("header.php"); 
include("connection.inc");
include("php_functions.php"); 

if ((isset($_POST['Year']) and $_POST['Year'] <> '') and (isset($_POST['Season']) and $_POST['Season'] <> '')) 
{
  $season = $_POST['Season'];
  $year = $_POST['Year'];
}
elseif ((isset($_GET['Year']) and $_GET['Year'] <> '') and (isset($_GET['Season']) and $_GET['Season'] <> '')) 
{
  $season = $_GET['Season'];
  $year = $_GET['Year'];
}
else
{
  $season = $_SESSION['season'];
  $year = $_SESSION['year'];
}

if(isset($_POST['ButtonName']) && ($_POST['ButtonName'] == "SaveSettings")) 
{
    $settingdata = [];
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    $settingdata = explode(":", $packeddata[0]);

    for($i = 0; $i < $_POST['Grades']; $i++)
    {
        if($settingdata[$i][7] == '')
        {
            $year = date("Y");
        }
        else
        {
            $year = $settingdata[$i][7];
        }
        $year = date("Y");
        $settingdata[$i] = explode(", ", $packeddata[$i]);
        $sql = "Update tbl_team_grade SET 
        grade = '" . $settingdata[$i][0] . "', " . "
        grade_name = '" . $settingdata[$i][1] . "', " . "
        season = '" . $settingdata[$i][2] . "', " . "
        type = '" . $settingdata[$i][3] . "', " . "
        RP = " . $settingdata[$i][4] . ", " . "
        min_breaks = " . $settingdata[$i][5] . ", " . "
        fix_upload = '" . $settingdata[$i][6] . "', " . "
        fix_cal_year = " . $settingdata[$i][7] . ", " . "
        current = '" . $settingdata[$i][8] . "', " . "
        finals_teams = " . $settingdata[$i][9] . ", " . "
        dayplayed = '" . $settingdata[$i][10] . "', " . "
        no_of_matches = " . $settingdata[$i][11] . ", " . "
        no_of_rounds = " . $settingdata[$i][12] . ", " . "
        no_of_players = " . $settingdata[$i][13] . ", " . "
        games_round = " . $settingdata[$i][14] . ", " . "
        tier1_rp = " . $settingdata[$i][15] . ", " . "
        tier2_rp = " . $settingdata[$i][16] . ", " . "
        tier3_rp = " . $settingdata[$i][17] . ", " . "
        tier4_rp = " . $settingdata[$i][18] . ", " . "
        tier5_rp = " . $settingdata[$i][19] . ", " . "
        tier6_rp = " . $settingdata[$i][20] . " " . "
        Where id = " . $settingdata[$i][21];
        //echo($sql . "<br>");
        $update = $dbcnx_client->query($sql);
        if(!$update )
        {
          die("Could not update grade settings data: " . mysqli_error($dbcnx_client));
        }
    }
    echo("<script type='text/javascript'>");
    echo("alert('Settings have been saved.');");
    echo("</script>");
}

if(isset($_POST['ButtonName']) && ($_POST['ButtonName'] == "SaveChanges")) {
    $packeddata = json_decode(stripslashes($_POST['PackedData']), true);
    for ($i = 0; $i < count($packeddata); $i++) {
        $grade = explode(", ", $packeddata[$i]);
        $sql = "Update tbl_grade_settings Set changePW =  " . $player[1] . ", enable = " . $player[2] . " where RegistrationNo  = " . $player[0];
        $update = $dbcnx_client->query($sql);
    }
}

?>
<script type="text/javaScript">

function SaveSettings(no_of_grades) {
    var transferdata = {};
    for (var i = 0; i < no_of_grades; i++) { // get number of grades
       if (document.getElementById("current_" + i).checked === true) {
            current_chk = 'Yes';
        }
        else
        {
            current_chk = 'No';
        }
        transferdata[i] = 
        document.getElementById("grade_" + i ).value + ", " + 
        document.getElementById("grade_name_" + i ).value + ", " + 
        document.getElementById("season_" + i ).value + ", " + 
        document.getElementById("type_" + i ).value + ", " + 
        document.getElementById("rank_points_" + i ).value + ", " + 
        document.getElementById("min_break_points_" + i ).value + ", " + 
        document.getElementById("fix_upload_" + i ).value + ", " + 
        document.getElementById("year_" + i ).value + ", " + 
        current_chk + ", " + 
        document.getElementById("finals_" + i ).value + ", " + 
        document.getElementById("day_played_" + i ).value + ", " + 
        document.getElementById("matches_" + i ).value + ", " + 
        document.getElementById("rounds_" + i ).value + ", " + 
        document.getElementById("players_" + i ).value + ", " + 
        document.getElementById("games_round_" + i ).value + ", " + 
        document.getElementById("rank_points_1_" + i ).value + ", " + 
        document.getElementById("rank_points_2_" + i ).value + ", " + 
        document.getElementById("rank_points_3_" + i ).value + ", " + 
        document.getElementById("rank_points_4_" + i ).value + ", " + 
        document.getElementById("rank_points_5_" + i ).value + ", " + 
        document.getElementById("rank_points_6_" + i ).value + ", " + 
        document.getElementById("id_" + i ).value;
    }
    var data = JSON.stringify(transferdata);
    //alert(data);
    document.settings.PackedData.value = data;
    document.settings.Grades.value = no_of_grades;
    document.settings.ButtonName.value = "SaveSettings"; 
    document.settings.submit();
}
</script>
<script>
window.onload = function() 
{
  $.fn.get_grades();
}

$(document).ready(function()
{
    $.fn.get_grades = function () {
        season_year = $('#season').val().split(",");
        //alert(season_year);
        $('#add').empty();
        $.ajax({
            url:"<?= $url ?>/get_grades.php?year=" + season_year[0] + "&season=" + season_year[1],
            success : function(data){
                obj = jQuery.parseJSON(data);
                console.log(obj);
                var output = "";
                output += ("<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>");
                output += ("<thead>"); 
                output += ("<tr>"); 
                output += ("<th rowspan='2' class='text-center' style='width : 50px;'>Grade Code</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 150px;'>Grade Name</th>");
                //output += ("<th rowspan='2' class='text-center' style='width : 40px;'>Season</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 60px;'>Type</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 50px;'>Finals Teams</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 30px;'>Day Played</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 50px;'>Allocated Ranking Points (Snooker)</th>");
                output += ("<th colspan='6' class='text-center' style='width : 50px;'>Allocated Ranking Points (Billiards)</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 30px;'>Min. Break Points</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 30px;'>No. of Matches</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 30px;'>No. of Rounds (inc Finals)</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 30px;'>No. of Players</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 30px;'>Games per Match</th>");
                output += ("<th rowspan='2' class='text-center' style='width : 30px;'>Current</th>");
                output += ("</tr>");
                output += ("<tr>");
                output += ("<th class='text-center' style='width : 30px;'>Tier 1</th>");
                output += ("<th class='text-center' style='width : 30px;'>Tier 2</th>");
                output += ("<th class='text-center' style='width : 30px;'>Tier 3</th>");
                output += ("<th class='text-center' style='width : 30px;'>Tier 4</th>");
                output += ("<th class='text-center' style='width : 30px;'>Tier 5</th>");
                output += ("<th class='text-center' style='width : 30px;'>Tier 6</th>");
                output += ("</tr>");
                output += ("</thead>");
                for(k = 0; k < obj.length; k++)
                {
                    fullobj = obj[k].split(', ');
                    //alert(fullobj[16]);
                    output += ("<input type='hidden' id='id_" + k + "' value=" + fullobj[22] + ">");
                    output += ("<tr>"); 
                    output += ("<td align='center'><input type='text' id='grade_" + k + "' value='" + fullobj[0] + "' style='width : 50px;'></td>");
                    output += ("<td align='center'><input type='text' id='grade_name_" + k + "' value='" + fullobj[1] + "'style='width : 150px;'></td>");
                    output += ("<input type='hidden' id='year_" + k + "' value=" + fullobj[7] + ">");
                    output += ("<input type='hidden' id='season_" + k + "' value=" + fullobj[2] + ">");
                    //output += ("<td align='center'><input type='text' id='season_" + k + "' value='" + fullobj[2] + "' style='width : 40px;'></td>");
                    output += ("<td align='center'><input type='text' id='type_" + k + "' value='" + fullobj[3] + "' style='width : 60px;'></td>");
                    output += ("<input type='hidden' id='fix_upload_" + k + "' value='" + fullobj[6] + "'>");
                    output += ("<td align='center'><input type='text' id='finals_" + k + "' value='"  + fullobj[9] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><select id='day_played_" + k + "'>");
                    output += ("<option value='"  + fullobj[10] +  "' selected='selected'>"  + fullobj[10] +  "</option>");
                    output += ("<option value='Mon'>Mon</option>");
                    output += ("<option value='Wed'>Wed</option>");
                    output += ("</select>");
                    output += ("</td>");
                    output += ("<td align='center'><input type='text' id='rank_points_" + k + "' value='"  + fullobj[4] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='rank_points_1_" + k + "' value='"  + fullobj[16] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='rank_points_2_" + k + "' value='"  + fullobj[17] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='rank_points_3_" + k + "' value='"  + fullobj[18] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='rank_points_4_" + k + "' value='"  + fullobj[19] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='rank_points_5_" + k + "' value='"  + fullobj[20] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='rank_points_6_" + k + "' value='"  + fullobj[21] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='min_break_points_" + k + "' value='"  + fullobj[5] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='matches_" + k + "' value='"  + fullobj[11] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='rounds_" + k + "' value='"  + fullobj[12] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='players_" + k + "' value='"  + fullobj[13] +  "' style='width : 30px;'></td>");
                    output += ("<td align='center'><input type='text' id='games_round_" + k + "' value='"  + fullobj[14] +  "' style='width : 30px;'></td>");
                    if (fullobj[8] == "Yes") {                  
                        output += ("<td align='center'><input type='checkbox' name='current' id='current_" + k + "' checked></td>");
                    }
                    else
                    {
                        output += ("<td align='center'><input type='checkbox' name='current' id='current_" + k + "'></td>");
                    }
                    output += ("</tr>");
                }     
                output += ("</table>");

                output += ("<table class='table table-striped table-bordered dt-responsive nowrap display' width='90%'>");
                output += ("<tr>");
                output += ("<td colspan=18 align=center>");
                output += ("<a class='btn btn-primary btn-xs' id='submit_button' href='javascript:;' onclick='SaveSettings(" + k + ");' style='width:200px'>Save Grade Settings</a>");
                output += ("</td></tr>");
                output += ("</table>");
                $($.parseHTML(output)).appendTo('#add');
            },
        });
    };

    $('#season').change(function(event){
        event.preventDefault(); 
        $.fn.get_grades();
    });

    // add grade to the database
    $('#add_grade').click(function(event){
        event.preventDefault();
        //alert("Here");
        $('#addGrade').modal('show');
    });

    $('#new_grade').click(function(event){
        event.preventDefault();
        var response;
        var no_of_grades = $('#mod_grades').val();
        for(i = 0; i < no_of_grades; i++)
        {
            if($("input[id='mod_add_" + i  + "']").is(":checked"))
            {
                var team_grade = $('#mod_grade_' + i).html();
                var grade_name = $('#mod_grade_name_' + i).html();
                var grade_type = $('#mod_type_' + i).html();
                var day_played = $('#mod_day_' + i).html();
                var season = '<?= $season ?>';
                var year = <?= $year ?>;
                $.ajax({
                    url:"<?= $url ?>/add_grade.php?grade=" + team_grade + "&name=" + grade_name + "&type=" + grade_type + "&dayplayed=" + day_played + "&season=" + season + "&year=" + year,
                    method: 'POST',
                    success:function(response)
                    {
                        if(response == "Added")
                        {
                            success = "True";
                        }
                    },
                });
            }
        }
        $('#addGrade').modal('hide');
        alert("Grade/s Added.");
        window.location.href = 'grade_settings.php';
    });

});

</script>
<center>
<form name="season" id="seasonID" method="post" action="grade_settings.php">
<input type='hidden' name='Year' />
<input type='hidden' name='Season' />
<input type='hidden' name='Select' />
<table class='table table-striped table-bordered dt-responsive nowrap display text-center' width='100%'>
    <tr>
        <td colspan=4 align=center><h1>Grade Settings</h1></td>
    </tr>
 <tr> 
 <td colspan='4'>&nbsp;</td> 
 </tr> 
 <tr> 
 <td align='center' valign='top'><b>Select Season:&nbsp;&nbsp; <select id='season' >
 <!--   <td align='center' valign='top'><b>Select Season:&nbsp;&nbsp; <select id='season' onchange="SelectSeasonButton()">-->
<?php
if(isset($season)) 
{
     echo("<option value='" . $year . ", " . $season . "' selected='selected'>" . $year . ", " . $season . "</option>"); 
}
else
{
     echo("<option value='' selected='selected'></option>");
}
echo("<option value=''>---------</option>");
echo("<option value='2023, S1'>2023, S1</option>");
echo("<option value='2023, S2'>2023, S2</option>");
echo("<option value='2024, S1'>2024, S1</option>");
echo("<option value='2024, S2'>2024, S2</option>");
echo("<option value='2025, S1'>2025, S1</option>");
echo("<option value='2025, S2'>2025, S2</option>");
?>
</select></b> 
</td> 
</tr> 
<tr> 
<td colspan='4'>&nbsp;</td> 
</tr> 
</table> 
</form>

<form name="settings" id="settings" method="post" action="grade_settings.php">
<input type="hidden" name="PackedData">
<input type="hidden" name="Grades">
<input type="hidden" name="ButtonName">
<table class='table table-striped table-bordered dt-responsive nowrap display text-center' width='100%'>
    <div id='add'></div>
    <tr><td colspan=18 align=center>
    <a class='btn btn-primary btn-xs' id='add_grade' style='width:200px'>Add New Grade</a>
    </td></tr>
</table>
</form>
</center>  

<!-- Add Grade Modal -->
<div class="modal fade" id="addGrade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Grade</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form name="add_grade" method="post" action="grade_settings.php">
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
            <?php
            error_reporting(0);
            $sql = "Select * FROM Team_grade Order by season, grade";
            $result = $dbcnx_client->query($sql);
            echo("<tr>"); 
            echo("<td colspan=8 align=center><b>Existing Grades</b></td>");
            echo("</tr>");
            echo("<tr>"); 
            echo("<td colspan=8 align=center>&nbsp;</td>");
            echo("</tr>");
            echo("<tr>"); 
            echo("<td align='center'>Grade</td>");
            echo("<td align='center'>Grade Name</td>");
            echo("<td align='center'>Season</td>");
            echo("<td align='center'>Type</td>");
            echo("<td align='center'>Day Played</td>");
            echo("<td align='center'>Add Grade</td>");
            echo("</tr>");
            $j = 0;
            while ($build_data = $result->fetch_assoc()) {
                echo("<input type='hidden' id='mod_year_" . $j . "' value=" . $_POST['Year'] . " />");
                echo("<tr>"); 
                echo("<td align='left' id='mod_grade_" . $j . "'>" . $build_data['grade'] . "</td>");
                echo("<td align='left' id='mod_grade_name_" . $j . "'>" . $build_data['grade_name'] . "</td>");
                echo("<td align='center' id='mod_season_" . $j . "'>" . $build_data['season'] . "</td>");
                echo("<td align='left' id='mod_type_" . $j . "'>" . $build_data['type'] . "</td>");
                
                echo("<td align='center' id='mod_day_" . $j . "'>" . $build_data['dayplayed'] . "</td>");
                echo("<td align='center'><input type='checkbox' name='mod_add_". $j . "' id='mod_add_" . $j . "'></td>");
                echo("</tr>");
                $j++;
            } 
            echo("<input type='hidden' name='mod_grades' id='mod_grades' value=" . ($j) . " />");
            ?>
        </table>
            <div class='text-center'><a class='btn btn-primary btn-xs' id='new_grade'>Add Selected Grade to List</a></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php

include("footer.php"); 

?>
