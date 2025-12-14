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
  
  <link rel='stylesheet' href='https://www.aropupu.fi/bracket/jquery-bracket/dist/jquery.bracket.min.css'>
  <script>
    window.console = window.console || function(t) {};
  </script>
</head>

<body translate="no">
  <span id="matchCallback"></span>
<div id="matches">
  <div class="demo">
  </div>
</div>

<!--
<div id="matchesblank">
  <div class="demo">
  </div>
</div>

1 - e.g if 16 player tournament status[open]

2 - user should be able to put name forward for tournament (in beginning maybe first 16 should be granted entry?)

3 - should seeding be implement?(maybe future)

3 - players(16) should now be selected from pool of players that have put name forward
status [progress] depepnding on (2)

4 - tournament schedule and matches created

5 - user enters scores after each match

tournament complete, display winner tournament status[closed]


DATABASE

Tournament TABLE
-ID (primary key)
-DATE
-Status
-Article ID?
-No Players

Players TABLE
-playertblID (primary key)
-playerid(id_user)
-ID(Tournament TABLE)

Matches TABLE
-ID(Tournament TABLE)
-tournament date/time
-round no
-round name
-scoreh
-scorea

-->
<script type="text/javascript" async="" src="./jQuery Bracket_files/ga.js"></script>
<script type="text/javascript" src="./jQuery Bracket_files/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="./jQuery Bracket_files/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="./jQuery Bracket_files/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="./jQuery Bracket_files/shCore.js"></script>
<script type="text/javascript" src="./jQuery Bracket_files/shBrushJScript.js"></script>
<script type="text/javascript" src="./jQuery Bracket_files/shBrushXml.js"></script>
<link rel="stylesheet" type="text/css" href="./jQuery Bracket_files/shCoreDefault.css">
<link rel="stylesheet" type="text/css" href="./jQuery Bracket_files/jquery-ui-1.8.16.custom.css">
<link rel="stylesheet" type="text/css" href="./jQuery Bracket_files/jquery.bracket-site.css">
<link rel="stylesheet" type="text/css" href="./jQuery Bracket_files/jquery.bracket.min.css">

<!--<script type="text/javascript" src="./jQuery Bracket_files/jquery.bracket.min.js"></script>-->

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://www.aropupu.fi/bracket/jquery-bracket/dist/jquery.bracket.min.js'></script>
<script id="rendered-js" >
var matchData = {
  teams: [
  ["Team 1", "Team 2"],
  ["Team 3", "Team 4"],
  ["Team 5", "Team 6"],
  ["Team 7", "Team 8"],
  ["Team 9", "Team 10"],
  ["Team 11", "Team 12"],
  ["Team 13", "Team 14"],
  ["Team 15", "Team 16"]],

  results: [
  //first round - last 16
  [
  [4, 3, 'Match 1'],
  [1, 4, 'Match 2'],
  [1, 4, 'Match 3'],
  [1, 4, 'Match 4'],
  [6, 4, 'Match 5'],
  [1, 4, 'Match 6'],
  [1, 4, 'Match 7'],
  [1, 4, 'Match 8']],

  //second round - Quarter Final
  [
  [4, 3, 'Match 9'],
  [1, 4, 'Match 10'],
  [1, 4, 'Match 11'],
  [1, 4, 'Match 12']],

  //third round - Semi Final
  [
  [4, 3, 'Match 13'],
  [1, 4, 'Match 14']],

  //fourth round - Final
  [
  [], //winners
  [1, 4, 'Match 16'] //third place
  ]] };

  /* Called whenever bracket is modified
   *
   * data:     changed bracket object in format given to init
   * userData: optional data given when bracket is created.
   */
  function saveFn(data, userData) {
    alert("Here");
    var json = jQuery.toJSON(data)
    $('#saveOutput').text('POST '+userData+' '+json)
    // You probably want to do something like this
    /*jQuery.ajax("rest/"+userData, {contentType: 'application/json',
                                  dataType: 'json',
                                  type: 'post',
                                  data: json})
    */
  }

/*
var matchBlankData = {
  teams: [
  ["Open Slot", "Open Slot"],
  ["Open Slot", "Team 4"],
  ["Team 5", "Team 6"],
  ["Team 7", "Team 8"],
  ["Team 9", "Team 10"],
  ["Team 11", "Team 12"],
  ["Team 13", "Team 14"],
  ["Team 15", "Team 16"]],

  results: [
  //first round - last 16
  [
  [4, 3, 'Match 1'],
  [1, 4, 'Match 2'],
  [1, 4, 'Match 3'],
  [1, 4, 'Match 4'],
  [6, 4, 'Match 5'],
  [1, 4, 'Match 6'],
  [1, 4, 'Match 7'],
  [1, 4, 'Match 8']],

  //second round - Quarter Final
  [
  [4, 3, 'Match 9'],
  [1, 4, 'Match 10'],
  [1, 4, 'Match 11'],
  [1, 4, 'Match 12']],

  //third round - Semi Final
  [
  [4, 3, 'Match 13'],
  [1, 4, 'Match 14']],

  //fourth round - Final
  [
  [], //winners
  [1, 4, 'Match 16'] //third place
  ]] };
*/


function onclick(data) {
  $('#matchCallback').text("onclick(data: '" + data + "')");
}

function onhover(data, hover) {
  $('#matchCallback').text("onhover(data: '" + data + "', hover: " + hover + ")");
}

$(function () {
  $('#matches .demo').bracket({
    init: matchData,
    onMatchClick: onclick,
    onMatchHover: onhover });

/*
  $('#matchesblank .demo').bracket({
    init: matchBlankData,
    onMatchClick: onclick,
   onMatchHover: onhover });
 */
});

</script>

</body>
</html>