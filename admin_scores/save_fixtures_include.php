<script>
$(document).ready(function()
{
    $.fn.save_fixtures = function (form_no, action) {
        var team_grade_before = $('#team_grade_' + form_no).html();
        team_grade = $.escapeSelector(team_grade_before);
        var no_of_teams = $('#no_of_teams_' + form_no).html();
        var no_of_fixtures = (no_of_teams/2);
        var no_of_rounds = ((no_of_teams*2)-2);
        var dayplayed = $("#dayplayed_" + form_no).html();
        var comptype = $("#comptype_" + form_no).html();
        var grade = team_grade.substring(0,1);
        var scoredata = new Array;
        var scoredata_teams = new Array;
        var sortdata = new Array;
        var sortdata_index = new Array;
        var season = '<?= $season ?>';
        var year = '<?= $year?>';
        var round;
        for(x = 0; x < no_of_teams; x++)
        {
            sortdata_index[x] = $("#club_" + form_no + "_" + (x+1)).html() + ", " + $("#sort_" + form_no + "_" + (x+1) + "_id").html(); 
            sortdata.push(sortdata_index[x]);
        }
        sortdata = JSON.stringify(sortdata);
        for(i = 0; i < no_of_rounds; i++)
        {
            playing_date = $("#A_" + form_no + "_date_" + i).val();
            round = (i+1);
            for(j = 0; j < (no_of_teams/2); j++) 
            {
                scoredata_teams[i+j] = $("#A_" + team_grade + "_home_" + (i+1) + "_" + (j+1)).val() + ", " + $("#A_" + team_grade + "_away_" + (i+1) + "_" + (j+1)).val() + ", " + playing_date + ", " + round; 
                scoredata.push(scoredata_teams[i+j]);
            }
        }
        scoredata = JSON.stringify(scoredata);
        $.ajax({
            url:"../vbsa_online_scores/save_fixtures.php?Type=" + comptype + "&Grade=" + grade + "&TeamGrade=" + team_grade + "&FormNo=" + form_no + "&DayPlayed=" + dayplayed + "&Year=" + year + "&Season=" + season + "&SortData=" + sortdata + "&ScoreData=" + scoredata + "&Fixtures=" + no_of_fixtures + "&Rounds=" + no_of_rounds,
            method: 'GET',
            success : function(response)
            {
                if(action == 'Response')
                {
                    alert(response);
                }
                window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?DayPlayed=' + dayplayed + '&season=' + season;
            },
            error: function (request, error) 
            {
              alert("No data saved!");
            }
        });
    }
});
</script>