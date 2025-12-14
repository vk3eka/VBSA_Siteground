<?php
require_once('../Connections/connvbsa.php'); 
include '../vbsa_online_scores/header_admin.php';
//include '../vbsa_online_scores/header_vbsa.php';

error_reporting(0);

?>
<!DOCTYPE html>
<html lang="en" >

<head>
<meta charset="UTF-8">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.css'>
<script>
  window.console = window.console || function(t) {};
</script>
</head>
<body translate="no">
  <div id="noGrandFinalComeback">
  <div class="demo">
  </div>
</div>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.js'></script>
<script id="rendered-js" >

$(document).ready(function()
{
    var eightTeams = {
      teams: [],
      results: [] 
    };

    var team_count = 16;
    //add teams
    for (var i = 1; i <= team_count; i += 2) {
      eightTeams.teams.push(["Team " + i, "Team " + (i + 1)]);
    }

    $.fn.get_teams = function (type) 
    {
      //alert("GetTeams");
      var tourn_id = 202281;
      $.ajax({
        url:"get_player_draw.php?tourn_id=" + tourn_id + "&team_count=" + team_count,
        success : function(data){
          var obj = jQuery.parseJSON(data);
          console.log(obj);
          for (var i = 0; i < team_count; i += 2) 
          {
            console.log("First " + obj[i]);
            console.log("Second " + obj[i+1]);
            eightTeams.teams.push("Team " + i, "Team " + (i + 1));
            //eightTeams.teams.push([obj[i], obj[i+1]]);
          }
        },
      });
    }

    $.fn.get_teams();

    //add results
    for (var i = team_count / 2; i > 1; i /= 2) {
      var result = [];
      for (var j = 0; j < i; j++) {
        result.push(generate_rundom_result());
      };
      eightTeams.results.push(result);
    }
    //add the final result
    eightTeams.results.push([generate_rundom_result(), generate_rundom_result()]);

    // only used to generate a demo score
    function generate_rundom_result() {
      var first_team = Math.floor(Math.random() * 10);
      var second_team = Math.floor(Math.random() * 10);
      if (first_team === second_team) {
        if (second_team > 0) {
          second_team--;
        } else {
          second_team++;
        }
      }
      return [first_team, second_team];
    }

    $(function () {
      $('div#noGrandFinalComeback .demo').bracket({
        skipGrandFinalComeback: true,
        matchMargin: 5,
        init: eightTeams });
    });

});

</script>
</body>
</html>