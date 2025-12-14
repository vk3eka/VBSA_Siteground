<?php
include('header.php'); 

include('connection.inc'); 

?>
<script>
function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
  ev.preventDefault();
  var data = ev.dataTransfer.getData("text");
  ev.target.appendChild(document.getElementById(data));
}
</script>
<?php

$team_grade = $build_data_grades['team_grade'];
$sql_club = 'Select team_name, team_club_id, team_club, team_grade, day_played, comptype from Team_entries where team_cal_year = 2024 and team_grade = "APS" and day_played = "Wed"';
//echo($sql_club . "<br>");
$result_club = $dbcnx_client->query($sql_club);
$fixtures = '';
// create string for fixture generator
while($row = $result_club->fetch_assoc()) 
{
    $fixtures = $row['team_name'] . ", " . $fixtures;
}
$fixtures = substr($fixtures, 0, strlen($fixtures)-2);

main($fixtures, 'APS', 1); 


// fixture generation code

function main($fixtures, $team_grade, $form_no) 
{
    echo show_fixtures(isset($teams) ?  nums(intval($teams)) : explode(", ", ($fixtures)), $team_grade, $form_no);
}

function nums($n) {
    $ns = array();
    for ($i = 1; $i <= $n; $i++) {
        $ns[] = $i;
    }
    return $ns;
}

function show_fixtures($names, $team_grade, $form_no)
{
    $teams = sizeof($names);
    // If odd number of teams add a "ghost".
    $ghost = false;
    if ($teams % 2 == 1) {
        $teams++;
        $ghost = true;
    }

    // Generate the fixtures using the cyclic algorithm.
    $totalRounds = $teams - 1;
    $matchesPerRound = $teams / 2;
    $rounds = array();
    for ($i = 0; $i < $totalRounds; $i++) {
        $rounds[$i] = array();
    }

    for ($round = 0; $round < $totalRounds; $round++) {
        for ($match = 0; $match < $matchesPerRound; $match++) {
            $home = ($round + $match) % ($teams - 1);
            $away = ($teams - 1 - $match + $round) % ($teams - 1);
            // Last team stays in the same place while the others
            // rotate around it.
            if ($match == 0) {
                $away = $teams - 1;
            }
            $rounds[$round][$match] = team_name($home + 1, $names)
                . " v " . team_name($away + 1, $names);
        }
    }

    // Interleave so that home and away games are fairly evenly dispersed.
    $interleaved = array();
    for ($i = 0; $i < $totalRounds; $i++) {
        $interleaved[$i] = array();
    }

    $evn = 0;
    $odd = ($teams / 2);
    for ($i = 0; $i < sizeof($rounds); $i++) {
        if ($i % 2 == 0) {
            $interleaved[$i] = $rounds[$evn++];
        } else {
            $interleaved[$i] = $rounds[$odd++];
        }
    }

    $rounds = $interleaved;

    // Last team can't be away for every game so flip them
    // to home on odd rounds.
    for ($round = 0; $round < sizeof($rounds); $round++) {
        if ($round % 2 == 1) {
            $rounds[$round][0] = flip($rounds[$round][0]);
        }
    }

    echo("<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
  <tr>");
    for ($i = 0; $i < sizeof($rounds); $i++) {
        echo("<td colspan=3 class='text-center'>Round " . ($i+1)  . "</td></tr>");
        //echo("<tr><td class='text-right'>Date</td>");
        //echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . $i . "' style='width:100px'></td></tr>");
        $x = 0;
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", $r);
            echo("<tr>");
            echo("<td class='text-center'><input type='text' ondrop='drop(event)' ondragover='allowDrop(event)' draggable='true' ondragstart='drag(event)' class='text-center' id='" . $team_grade . "_home_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[0] . "'></td>");
            echo("<td align='center'>v</td>");
            echo("<td class='text-center'><input ondrop='drop(event)' ondragover='allowDrop(event)'draggable='true' ondragstart='drag(event)' type='text' class='text-center' id='" . $team_grade . "_away_" . ($i+1) . "_" . ($x+1) . "' value='" . $round_data[1] . "'></td>");
            echo("</tr>");
            $x++;
        }
    }
    $round_counter = sizeof($rounds) + 1;
    for ($i = sizeof($rounds) - 1; $i >= 0; $i--) {
        echo("<td colspan=3 class='text-center'>Round " . $round_counter  . "</td>");
        echo("<tr><td class='text-right'>Date</td>");
        echo("<td colspan=2 class='text-left'><input type='text' id='" . $form_no . "_date_" . ($round_counter-1) . "' style='width:100px'></td></tr>");
        $round_counter += 1;
        $y = 0;    
        foreach ($rounds[$i] as $r) {
            $round_data = explode(" v ", flip($r));
            echo("<tr>");
            echo("<td class='text-center'><input type='text' class='text-center' id='" . $team_grade . "_home_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[0] . "'></td>");
            echo("<td align='center'>v</td>");
            echo("<td class='text-center'><input type='text' class='text-center' id='" . $team_grade . "_away_" . ($round_counter-1) . "_" . ($y+1) . "' value='" . $round_data[1] . "'></td>");
            echo("</tr>");
            $y++;
        }
    }

    if ($ghost) {
        print "Matches against team " . $teams . " are byes.";
    }
}

function flip($match) {
    $components = explode(' v ', $match);
    return $components[1] . " v " . $components[0];
}

function team_name($num, $names) {
    $i = $num - 1;
    if (sizeof($names) > $i && strlen(trim($names[$i])) > 0) {
        return trim($names[$i]);
    } else {
        return $num;
    }
}

echo("</form>");

?>
<script>
$(document).ready(function() {
  $(".draggable").draggable({
    cursor: 'move',
    revert: true
  });

  $(".droppable").droppable({
    drop: function(event, ui) {
      var str1 = ui.draggable.text(); //returns draggable value
      ui.draggable.text($('$row1').val())
      $(this).text(str1); //trying to set droppable target with draggable value
    }
  });
});

</script>
<table class='table table-striped table-bordered dt-responsive nowrap display text-center' width='100%'>
  <tr>
    <td>
      <div id='row1' class="draggable">1234</div>
    </td>
    <td>
      <div id='row2' class="droppable">2345</div>
    </td>
  </tr>

  <tr>
    <td>
      <div id='row3' class="draggable">1111</div>
    </td>
    <td>
      <div id='row4' class="droppable">2222</div>
    </td>
  </tr>
</table>





