<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  

    
    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-2c7831bb44f98c1391d6a4ffda0e1fd302503391ca806e7fcc7b9b87197aec26.js"></script>


  <title>CodePen - Tournament Bracket Generator</title>

    <link rel="canonical" href="https://codepen.io/trima/pen/zByMJw">
  
  
  
  
<style>
html, body, .brackets {
        width: 100%;
        min-height: 100%;
        font-family: "Arial", sans-serif;
      }
      .metroBtn {
        background-color: #2E7BCC;
        color: #fff;
        font-size: 1.1em;
        padding: 10px;
        display: inline-block;
        margin-bottom: 30px;
        cursor: pointer;
      }
      .brackets > div {
        vertical-align: top;
        clear: both;
      }
      .brackets > div > div {
        float: left;
        height: 100%;
      }
      .brackets > div > div > div {
        margin: 50px 0;
      }
      .brackets div.bracketbox {
        position: relative;
        width: 100%; height: 100%;
        border-top: 1px solid #555;
        border-right: 1px solid #555;
        border-bottom: 1px solid #555;
      }

      .brackets div.bracketbox > span {
        position: absolute;
        left: 40px;
        font-size: 0.85em;

      }
      .brackets div.bracketbox > span.info1 {
        position: absolute;
        top: -20px;
        left: 280px;
        font-size: 0.8em;
        color: #BBB;
      }
      .brackets div.bracketbox > span.info2 {
        position: absolute;
        bottom: -20px;
        left: 280px;
        font-size: 0.8em;
        color: #BBB;
      }
      .brackets div.bracketbox > span.teama {
        top: -13.5px;
        background-color: white;
        padding : 5px 20px 5px 20px;
        border: 1px solid black;
      }
      .brackets div.bracketbox > span.teamb {
        bottom: -13.5px;
        background-color: white;
        padding : 5px 20px 5px 20px;
        border: 1px solid black;
      }
      .brackets div.bracketbox > span.teamc {
        bottom: -13.5px;
        background-color: white;
        padding : 5px 20px 5px 20px;
        border: 1px solid black;
      }
      .brackets > .group2 {
        height: 260px;
        width: 25%;
      }
      .brackets > .group4 {
        height: 260px;
        width: 75%;
      }
      .brackets > .group5 {
        height: 260px;
        width: 75%;
      }
      .brackets > .group2 > div {
        width: 49%;
      }
      .brackets > .group3 {
        height: 320px;
        width: 50%;
      }
      .brackets > .group3 > div {
        width: 32.7%;
      }
      .brackets > .group4 > div {
        width: 24.5%;
      }
      .brackets > .group5 > div {
        width: 19.6%;
      }
      .brackets > .group6 {
        height: 2000px;
      }
      .brackets > .group6 > div {
        width: 16.3%;
      }
      .brackets > .group7 > div {
        width: 13.3%;
      }
      .brackets > div > .r1 > div {
        height: 60px;
      }
      .brackets > div > .r2 > div {
        margin: 80px 0 110px 0;
        height: 110px;
      }
      .brackets > div > .r3 > div {
        margin: 135px 0 220px 0;
        height: 220px;
      }
      .brackets > div > .r4 > div {
        margin: 250px 0 445px 0;
        height: 445px;
      }
      .brackets > div > .r5 > div {
        margin: 460px 0 900px 0;
        height: 900px;
      }
      .brackets > div > .r6 > div {
        margin: 900px 0 0 0;
        height : 1800px;
      }
      .brackets > div > .r7 > div {
        margin: 1800px 0 0 0;
      }
      .brackets div.final > div.bracketbox {
        border-top: 0px;
        border-right: 0px;
        height: 0px;
      }
      .brackets > div > .r4 > div.drop {
        height: 180px;
        margin-bottom: 0px;
      }
      .brackets > div > .r5 > div.final.drop {
        margin-top: 345px;
        margin-bottom: 0px;
        height: 1px;
      }
      .brackets > div > div > div:last-of-type {
        margin-bottom: 0px;
      }
</style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
</head>

<body translate="no">
  <div id="add" class="metroBtn">Add Bracket</div>
<div id="clear" class="metroBtn">Clear</div>
<div class="brackets" id="brackets"></div>
  <script src='https://code.jquery.com/jquery-2.2.4.min.js'></script>
<script src='https://underscorejs.org/underscore-min.js'></script>
      <script id="rendered-js" >
$(document).on('ready', function () {

  var knownBrackets = [2, 4, 8, 16, 32, 64], // brackets with "perfect" proportions (full fields, no byes)

  exampleTeams = _.shuffle(["New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "New Jersey Devils", "New York Islanders", "New York Rangers", "Philadelphia Flyers", "Pittsburgh Penguins", "Boston Bruins", "Buffalo Sabres", "Montreal Canadiens", "Ottawa Senators", "Toronto Maple Leafs", "Carolina Hurricanes", "Florida Panthers", "Tampa Bay Lightning", "Washington Capitals", "Winnipeg Jets", "Chicago Blackhawks", "Columbus Blue Jackets", "Detroit Red Wings", "Nashville Predators", "St. Louis Blues", "Calgary Flames", "Colorado Avalanche", "Edmonton Oilers", "Minnesota Wild", "Vancouver Canucks", "Anaheim Ducks", "Dallas Stars", "Los Angeles Kings", "Phoenix Coyotes", "San Jose Sharks", "Montreal Wanderers", "Quebec Nordiques", "Hartford Whalers"]), // because a bracket needs some teams!
  bracketCount = 0;console.log($(exampleTeams).length);

  /*
   * Build our bracket "model"
   */
  function getBracket(base) {

    var closest = _.find(knownBrackets, function (k) {return k >= base;}),
    byes = closest - base;

    if (byes > 0) base = closest;

    var brackets = [],
    round = 1,
    baseT = base / 2,
    baseC = base / 2,
    teamMark = 0,
    nextInc = base / 2;

    for (i = 1; i <= base - 1; i++) {if (window.CP.shouldStopExecution(0)) break;
      var baseR = i / baseT,
      isBye = false;

      if (byes > 0 && (i % 2 != 0 || byes >= baseT - i)) {
        isBye = true;
        byes--;
      }

      var last = _.map(_.filter(brackets, function (b) {return b.nextGame == i;}), function (b) {return { game: b.bracketNo, teams: b.teamnames };});

      brackets.push({
        lastGames: round == 1 ? null : [last[0].game, last[1].game],
        nextGame: nextInc + i > base - 1 ? null : nextInc + i,
        teamnames: round == 1 ? [exampleTeams[teamMark], exampleTeams[teamMark + 1]] : [last[0].teams[_.random(1)], last[1].teams[_.random(1)]],
        bracketNo: i,
        roundNo: round,
        bye: isBye });

      teamMark += 2;
      if (i % 2 != 0) nextInc--;
      while (baseR >= 1) {if (window.CP.shouldStopExecution(1)) break;
        round++;
        baseC /= 2;
        baseT = baseT + baseC;
        baseR = i / baseT;
      }window.CP.exitedLoop(1);
    }window.CP.exitedLoop(0);

    renderBrackets(brackets);
  }

  /*
   * Inject our brackets
   */
  function renderBrackets(struct) {
    var groupCount = _.uniq(_.map(struct, function (s) {return s.roundNo;})).length;

    var group = $('<div class="group' + (groupCount + 1) + '" id="b' + bracketCount + '"></div>'),
    grouped = _.groupBy(struct, function (s) {return s.roundNo;});
    for (g = 1; g <= groupCount; g++) {if (window.CP.shouldStopExecution(2)) break;
      var round = $('<div class="r' + g + '"></div>');
      _.each(grouped[g], function (gg) {
        if (gg.bye)
        round.append('<div></div>');else

        round.append('<div><div class="bracketbox"><span class="info1">' + gg.bracketNo + '</span><span class="info2">' + gg.bracketNo + '</span><span class="teama">' + gg.teamnames[0] + '</span><span class="teamb">' + gg.teamnames[1] + '</span></div></div>');
      });
      group.append(round);
    }window.CP.exitedLoop(2);
    group.append('<div class="r' + (groupCount + 1) + '"><div class="final"><div class="bracketbox"><span class="teamc">' + _.last(struct).teamnames[_.random(1)] + '</span></div></div></div>');
    $('#brackets').append(group);

    bracketCount++;
    $('html,body').animate({
      scrollTop: $("#b" + (bracketCount - 1)).offset().top });

  }

  $('#add').on('click', function () {
    var opts = parseInt(prompt('Bracket size (number of teams):', 32));

    if (!_.isNaN(opts) && opts <= _.last(knownBrackets))
    getBracket(opts);else

    alert('The bracket size you specified is not currently supported.');
    $('#clear').off('click');
    $('#clear').on('click', function () {
      $('#brackets').html("");
    });
  });


});
//# sourceURL=pen.js
    </script>

  
</body>

</html>