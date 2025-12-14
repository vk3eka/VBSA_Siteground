<!DOCTYPE html>
<!-- saved from url=(0031)https://www.aropupu.fi/bracket/ -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!--[if ie]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><![endif]-->

<title>jQuery Bracket</title>
<meta name="description" content="jQuery plugin for visualizing and editing single and double elimination tournament brackets">
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

<script type="text/javascript" src="./jQuery Bracket_files/jquery.bracket.min.js"></script>

<script type="text/javascript">
$(function() {
    var demos = ['save', 'minimal', 'resize', 'matches', 'customHandlers', 'autoComplete', 'doubleElimination', 'noSecondaryFinal', 'noConsolationRound', 'noGrandFinalComeback', 'reverseBracket', 'big', 'connectorStyles']
    $.each(demos, function(i, d){
      var demo = $('div#'+d)
      $('<div class="demo"></div>').appendTo(demo)
      var pre = $('<pre name="code" class="js"></pre>').appendTo(demo)
      var script = demo.find('script')
      demo.find("h3").append($('<a href="#' + d + '">¶</a>'))
      pre.text(script.html())
    })
  })
</script>

</head>
<body>
<div id="main">
<h1>jQuery Bracket library [<a href="http://aropupu.fi/bracket-server/">server</a>]</h1>
<p>jQuery bracket is a <a href="http://www.jquery.com/">jQuery plugin</a> that lets users create and display <a href="http://en.wikipedia.org/wiki/Bracket_(tournament)">single and double elimination brackets</a> for tournament play.</p>

<div id="minimal">
  <h3>Minimal<a href="https://www.aropupu.fi/bracket/#minimal">¶</a></h3>
  <script type="text/javascript">
  var minimalData = {
      teams : [
        ["Team 1", "Team 2"], /* first matchup */
        ["Team 3", "Team 4"]  /* second matchup */
      ],
      results : [
        [[1,2], [3,4]],       /* first round */
        [[4,6], [2,1]]        /* second round */
      ]
    }

  $(function() {
      $('#minimal .demo').bracket({
        init: minimalData /* data to initialize the bracket with */ })
    });
  </script>
	<!--<div class="demo">
		<div class="jQBracket lr" style="width: 290px; height: 170px;">
			<div class="bracket" style="height: 130px;">
				<div class="round" style="width: 100px; margin-right: 40px">
					<div class="match" style="height: 65px;">
						<div class="teamContainer" style="top: 10px;">
							<div class="team lose" style="width: 100px;" data-teamid="0">
							<div class="label" style="width: 70px;">Team 1</div>
							<div class="score" style="width: 30px;" data-resultid="result-1">1</div>
						</div>
						<div class="team win highlightLoser" style="width: 100px;" data-teamid="1">
							<div class="label" style="width: 70px;">Team 2</div>
							<div class="score" style="width: 30px;" data-resultid="result-2">2</div>
						</div>
						<div class="connector highlightLoser" style="height: 10px; width: 20px; right: -22px; top: 32.75px; border-bottom: none;">
							<div class="connector highlightLoser" style="width: 20px; right: -20px; bottom: 0px;"></div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 10px;">
						<div class="team lose" style="width: 100px;" data-teamid="2">
							<div class="label" style="width: 70px;">Team 3</div>
							<div class="score" style="width: 30px;" data-resultid="result-3">3</div>
						</div>
						<div class="team win highlightWinner" style="width: 100px;" data-teamid="3">
							<div class="label" style="width: 70px;">Team 4</div>
							<div class="score" style="width: 30px;" data-resultid="result-4">4</div>
						</div>
						<div class="connector highlightWinner" style="height: 32.5px; width: 20px; right: -22px; bottom: 10.25px; border-top: none;">
							<div class="connector highlightWinner" style="width: 20px; right: -20px; top: 0px;"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="round" style="width: 100px; margin-right: 40px">
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="position: absolute; bottom: -22.5px;">
						<div class="team lose highlightLoser" style="width: 100px;" data-teamid="1">
							<div class="label" style="width: 70px;">Team 2</div>
							<div class="score" style="width: 30px;" data-resultid="result-5">4</div>
							<div class="bubble">2nd</div>
						</div>
						<div class="team win highlightWinner" style="width: 100px;" data-teamid="3">
							<div class="label" style="width: 70px;">Team 4</div>
							<div class="score" style="width: 30px;" data-resultid="result-6">6</div>
							<div class="bubble">1st</div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 42.5px;">
						<div class="team win" style="width: 100px;" data-teamid="0">
							<div class="label" style="width: 70px;">Team 1</div>
							<div class="score" style="width: 30px;" data-resultid="result-7">2</div>
							<div class="bubble third">3rd</div>
						</div>
						<div class="team lose" style="width: 100px;" data-teamid="2">
							<div class="label" style="width: 70px;">Team 3</div>
							<div class="score" style="width: 30px;" data-resultid="result-8">1</div>
							<div class="bubble fourth">4th</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>-->

<script type="text/javascript">
  function resize(target, propName) {
    resizeParameters[propName] = parseInt(target.value);
    target.previousElementSibling.textContent = target.value;
    updateResizeDemo();
  }
</script>
<div id="resize">
  <h3>Resizing<a href="https://www.aropupu.fi/bracket/#resize">¶</a></h3>
  <p>You can adjust the sizes and margins of the bracket elements with initialization parameters. Other styles can be overridden by CSS.</p>
  <label class="rangePicker">teamWidth: <span>60</span>; <input oninput="resize(this, 'teamWidth')" type="range" min="30" max="100" step="1" value="60"></label>
  <label class="rangePicker">scoreWidth: <span>40</span>; <input oninput="resize(this, 'scoreWidth')" type="range" min="20" max="100" step="1" value="40"></label>
  <label class="rangePicker">matchMargin: <span>40</span>; <input oninput="resize(this, 'matchMargin')" type="range" min="0" max="100" step="1" value="40"></label>
  <label class="rangePicker">roundMargin: <span>20</span>; <input oninput="resize(this, 'roundMargin')" type="range" min="3" max="100" step="1" value="20"></label>
  <script type="text/javascript">
    // These are modified by the sliders
    var resizeParameters = {
      teamWidth: 60,
      scoreWidth: 20,
      matchMargin: 10,
      roundMargin: 50,
      init: minimalData
    };

    function updateResizeDemo() {
      $('#resize .demo').bracket(resizeParameters);
    }

    $(updateResizeDemo)
  </script>

<!--<div class="demo">
	<div class="jQBracket lr" style="width: 270px; height: 150px;">
		<div class="bracket" style="height: 110px;">
			<div class="round" style="width: 80px; margin-right: 50px">
				<div class="match" style="height: 55px;">
					<div class="teamContainer" style="top: 5px;">
						<div class="team lose" style="width: 80px;" data-teamid="0">
							<div class="label" style="width: 60px;">Team 1</div>
							<div class="score" style="width: 20px;" data-resultid="result-1">1</div>
						</div>
						<div class="team win highlightLoser" style="width: 80px;" data-teamid="1">
							<div class="label" style="width: 60px;">Team 2</div>
							<div class="score" style="width: 20px;" data-resultid="result-2">2</div>
						</div>
						<div class="connector highlightLoser" style="height: 5px; width: 25px; right: -27px; top: 32.75px; border-bottom: none;">
							<div class="connector highlightLoser" style="width: 25px; right: -25px; bottom: 0px;"></div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 55px;">
					<div class="teamContainer" style="top: 5px;">
						<div class="team lose" style="width: 80px;" data-teamid="2">
							<div class="label" style="width: 60px;">Team 3</div>
							<div class="score" style="width: 20px;" data-resultid="result-3">3</div>
						</div>
						<div class="team win highlightWinner" style="width: 80px;" data-teamid="3">
							<div class="label" style="width: 60px;">Team 4</div>
							<div class="score" style="width: 20px;" data-resultid="result-4">4</div>
						</div>
						<div class="connector highlightWinner" style="height: 27.5px; width: 25px; right: -27px; bottom: 10.25px; border-top: none;">
							<div class="connector highlightWinner" style="width: 25px; right: -25px; top: 0px;"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="round" style="width: 80px; margin-right: 50px">
				<div class="match" style="height: 55px;">
					<div class="teamContainer" style="position: absolute; bottom: -22.5px;">
						<div class="team lose highlightLoser" style="width: 80px;" data-teamid="1">
							<div class="label" style="width: 60px;">Team 2</div>
							<div class="score" style="width: 20px;" data-resultid="result-5">4</div>
							<div class="bubble">2nd</div>
						</div>
						<div class="team win highlightWinner" style="width: 80px;" data-teamid="3">
							<div class="label" style="width: 60px;">Team 4</div>
							<div class="score" style="width: 20px;" data-resultid="result-6">6</div>
							<div class="bubble">1st</div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 55px;">
					<div class="teamContainer" style="top: 32.5px;">
						<div class="team win" style="width: 80px;" data-teamid="0">
							<div class="label" style="width: 60px;">Team 1</div>
							<div class="score" style="width: 20px;" data-resultid="result-7">2</div>
							<div class="bubble third">3rd</div>
						</div>
						<div class="team lose" style="width: 80px;" data-teamid="2">
							<div class="label" style="width: 60px;">Team 3</div>
							<div class="score" style="width: 20px;" data-resultid="result-8">1</div>
							<div class="bubble fourth">4th</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</div>-->

<div id="save">
  <h3>Save functionality and BYEs<a href="https://www.aropupu.fi/bracket/#save">¶</a></h3>
  <ul>
    <li>Click team and score labels to edit</li>
    <li>Empty team name will remove the team, resulting into <strong>BYE</strong></li>
    <li>Use <code>null</code> when initializing team data to mark that branch as <strong>BYE</strong></li>
    <li>All teams playing against <strong>BYE</strong> will get a default win with no score</li>
    <li>Spot that will eventually get a team are shown as <strong>TBD</strong></li>
    <li>You can press return when entering scores to proceed to next field</li>
    <li style="color: #C00; font-weight: bold;">NOTE: <a href="https://www.aropupu.fi/bracket/#balancing">See explanation on balancing</a> a tournament with <strong>BYE</strong>s</li>
    <li>Additional parameters. Requires <code>save</code> callback to be given.
      <ul>
        <li><code>disableToolbar: boolean</code> hides the toolbar that
          allows resizing the bracket and changing its type</li>
        <li><code>disableTeamEdit: boolean</code> disallows editing teams,
          allows still editing scores. You must ALSO disable the toolbar (as
          incresing bracket size would add BYE teams, thus "editing
          teams")</li>
      </ul>
    </li>
  </ul>
  <script type="text/javascript">
  var saveData = {
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
    /* You probably want to do something like this */
    jQuery.ajax("rest/"+userData, {contentType: 'application/json',
                                  dataType: 'json',
                                  type: 'post',
                                  data: json})

  }

  $(function() {
      var container = $('div#save .demo')
      container.bracket({
        init: saveData,
        save: saveFn,
        userData: "http://myapi"})

      /* You can also inquiry the current data */
      var data = container.bracket('data')
      $('#dataOutput').text(jQuery.toJSON(data))
    })
  </script>
<!--<div class="demo">
	<div class="jQBracket lr" style="width: 460px;">
		<div class="tools"><span class="increment">+</span><span class="decrement">-</span><span class="doubleElimination">de</span></div>
		<div class="bracket" style="height: 260px;">
			<div class="round" style="width: 100px; margin-right: 40px">
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 10px;">
						<div class="team win" style="width: 100px;" data-teamid="0">
							<div class="label editable" style="width: 70px;">Seed</div>
							<div class="label editable" style="width: 70px;">Team 1</div>
							<div class="score editable" style="width: 30px;" data-resultid="result-1">1</div>
						</div>
						<div class="team lose" style="width: 100px;" data-teamid="1">
							<div class="label editable" style="width: 70px;">Team 2</div>
							<div class="score editable" style="width: 30px;" data-resultid="result-2">0</div>
						</div>
						<div class="connector" style="height: 32.5px; width: 20px; right: -22px; top: 10.25px; border-bottom: none;">
							<div class="connector" style="width: 20px; right: -20px; bottom: 0px;"></div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 10px;">
						<div class="team win" style="width: 100px;" data-teamid="2">
							<div class="label editable" style="width: 70px;">Team 3</div>
							<div class="score" style="width: 30px;">--</div>
						</div>
						<div class="team na" style="width: 100px;" data-teamid="3">
							<div class="label editable" style="width: 70px;">BYE</div>
							<div class="score" style="width: 30px;">--</div>
						</div>
						<div class="connector" style="height: 10px; width: 20px; right: -22px; bottom: 32.75px; border-top: none;">
							<div class="connector" style="width: 20px; right: -20px; top: 0px;"></div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 10px;">
						<div class="team win" style="width: 100px;" data-teamid="4">
							<div class="label editable" style="width: 70px;">Team 4</div>
							<div class="score" style="width: 30px;">--</div>
						</div>
						<div class="team na" style="width: 100px;" data-teamid="5">
							<div class="label editable" style="width: 70px;">BYE</div>
							<div class="score" style="width: 30px;">--</div>
						</div>
						<div class="connector" style="height: 32.5px; width: 20px; right: -22px; top: 10.25px; border-bottom: none;">
							<div class="connector" style="width: 20px; right: -20px; bottom: 0px;"></div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 10px;">
						<div class="team win" style="width: 100px;" data-teamid="6">
							<div class="label editable" style="width: 70px;">Team 5</div>
							<div class="score" style="width: 30px;">--</div>
						</div>
						<div class="team na" style="width: 100px;" data-teamid="7">
							<div class="label editable" style="width: 70px;">BYE</div>
							<div class="score" style="width: 30px;">--</div>
						</div>
						<div class="connector" style="height: 10px; width: 20px; right: -22px; bottom: 32.75px; border-top: none;">
							<div class="connector" style="width: 20px; right: -20px; top: 0px;"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="round" style="width: 100px; margin-right: 40px">
				<div class="match" style="height: 130px;">
					<div class="teamContainer" style="top: 42.5px;">
						<div class="team" style="width: 100px;" data-teamid="0">
							<div class="label editable" style="width: 70px;">Team 1</div>
							<div class="score editable" style="width: 30px;" data-resultid="result-3">--</div>
						</div>
						<div class="team" style="width: 100px;" data-teamid="2">
							<div class="label editable" style="width: 70px;">Team 3</div>
							<div class="score editable" style="width: 30px;" data-resultid="result-4">--</div>
						</div>
						<div class="connector" style="height: 53.75px; width: 20px; right: -22px; top: 21.5px; border-bottom: none;">
							<div class="connector" style="width: 20px; right: -20px; bottom: 0px;"></div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 130px;">
					<div class="teamContainer" style="top: 42.5px;">
						<div class="team lose" style="width: 100px;" data-teamid="4"><div class="label editable" style="width: 70px;">Team 4</div>
						<div class="score editable" style="width: 30px;" data-resultid="result-5">1</div>
					</div>
					<div class="team win" style="width: 100px;" data-teamid="6">
						<div class="label editable" style="width: 70px;">Team 5</div>
						<div class="score editable" style="width: 30px;" data-resultid="result-6">4</div>
					</div>
					<div class="connector" style="height: 65px; width: 20px; right: -22px; bottom: 10.25px; border-top: none;">
						<div class="connector" style="width: 20px; right: -20px; top: 0px;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="round" style="width: 100px; margin-right: 40px">
			<div class="match" style="height: 130px;">
				<div class="teamContainer" style="position: absolute; bottom: -22.5px;">
					<div class="team na" style="width: 100px;">
						<div class="label" style="width: 70px;">TBD</div>
						<div class="score" style="width: 30px;">--</div>
					</div>
					<div class="team" style="width: 100px;" data-teamid="6">
						<div class="label editable" style="width: 70px;">Team 5</div>
						<div class="score" style="width: 30px;">--</div>
					</div>
				</div>
			</div>
			<div class="match" style="height: 130px;">
				<div class="teamContainer" style="top: 42.5px;">
					<div class="team na" style="width: 100px;">
						<div class="label" style="width: 70px;">TBD</div>
						<div class="score" style="width: 30px;">--</div>
					</div>
					<div class="team" style="width: 100px;" data-teamid="4">
						<div class="label editable" style="width: 70px;">Team 4</div>
						<div class="score" style="width: 30px;">--</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>-->
</div>
<div>
</div></div>
<h4>Save output</h4>
<pre id="saveOutput">Try to first modify some scores or teams</pre>
<h4>Data inquired at startup</h4>
<pre id="dataOutput">{"teams":[["Team 1","Team 2"],["Team 3",null],["Team 4",null],["Team 5",null]],"results":[[[[1,0],[null,null],[null,null],[null,null]],[[null,null],[1,4]],[[null,null],[null,null]]]]}</pre>

<div id="matches">
  <h3>Match information<a href="https://www.aropupu.fi/bracket/#matches">¶</a></h3>
  <p>If you wish to make the bracket more interatctive and display match specific information, you can use the match
    callbacks. You can bind callbacks that are triggered when user clicks or hovers on a match. Custom data
    regarding which match was triggered will be passed as argument. The data can be input as the third value of
    each match, first two being the result of the match. The type of the
    value is not restricted. Hover gets a boolean as second argument indicating if mouse entered or left the match.
    Callbacks cannot be used in conjunction with the edit feature.</p>
  <div style="margin-bottom: 5px; font-size: 16px;"><span id="matchCallback">onhover(data: 'Consolation final', hover: false)</span></div>
  <script type="text/javascript">
    var matchData = {
      teams : [
        ["Team 1", "Team 2"],
        ["Team 3", "Team 4"]
      ],
      results : [
        [[4,3,'Match 1'], [3,4,'Match 2']],
        [[8,6,'Final'], [2,3,'Consolation final']]
      ]
    }

    function onclick(data) {
      $('#matchCallback').text("onclick(data: '" + data + "')")
    }

    function onhover(data, hover) {
      $('#matchCallback').text("onhover(data: '" + data + "', hover: " + hover + ")")
    }

    $(function() {
      $('#matches .demo').bracket({
        init: matchData,
        onMatchClick: onclick,
        onMatchHover: onhover
      })
    })
  </script>
<!--<div class="demo">
	<div class="jQBracket lr" style="width: 290px; height: 170px;">
		<div class="bracket" style="height: 130px;">
			<div class="round" style="width: 100px; margin-right: 40px">
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 10px;">
						<div class="team win highlightWinner" style="width: 100px;" data-teamid="0">
							<div class="label" style="width: 70px;">Team 1</div>
							<div class="score" style="width: 30px;" data-resultid="result-1">4</div>
						</div>
						<div class="team lose" style="width: 100px;" data-teamid="1">
							<div class="label" style="width: 70px;">Team 2</div>
							<div class="score" style="width: 30px;" data-resultid="result-2">3</div>
						</div>
						<div class="connector highlightWinner" style="height: 32.5px; width: 20px; right: -22px; top: 10.25px; border-bottom: none;">
							<div class="connector highlightWinner" style="width: 20px; right: -20px; bottom: 0px;"></div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 10px;">
						<div class="team lose" style="width: 100px;" data-teamid="2">
							<div class="label" style="width: 70px;">Team 3</div>
							<div class="score" style="width: 30px;" data-resultid="result-3">3</div>
						</div>
						<div class="team win highlightLoser" style="width: 100px;" data-teamid="3">
							<div class="label" style="width: 70px;">Team 4</div>
							<div class="score" style="width: 30px;" data-resultid="result-4">4</div>
						</div><div class="connector highlightLoser" style="height: 32.5px; width: 20px; right: -22px; bottom: 10.25px; border-top: none;">
							<div class="connector highlightLoser" style="width: 20px; right: -20px; top: 0px;"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="round" style="width: 100px; margin-right: 40px">
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="position: absolute; bottom: -22.5px;">
						<div class="team win highlightWinner" style="width: 100px;" data-teamid="0">
							<div class="label" style="width: 70px;">Team 1</div>
							<div class="score" style="width: 30px;" data-resultid="result-5">8</div>
							<div class="bubble">1st</div>
						</div>
						<div class="team lose highlightLoser" style="width: 100px;" data-teamid="3">
							<div class="label" style="width: 70px;">Team 4</div>
							<div class="score" style="width: 30px;" data-resultid="result-6">6</div>
							<div class="bubble">2nd</div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 42.5px;">
						<div class="team lose" style="width: 100px;" data-teamid="1">
							<div class="label" style="width: 70px;">Team 2</div>
							<div class="score" style="width: 30px;" data-resultid="result-7">2</div>
							<div class="bubble fourth">4th</div>
						</div>
						<div class="team win" style="width: 100px;" data-teamid="2">
							<div class="label" style="width: 70px;">Team 3</div>
							<div class="score" style="width: 30px;" data-resultid="result-8">3</div>
							<div class="bubble third">3rd</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div>
</div></div>-->

<div id="customHandlers">
  <h3>Data customization<a href="https://www.aropupu.fi/bracket/#customHandlers">¶</a></h3>
  <p>In this demo we customize the rendering and editing of a team. You can
    give the team data as <code>country:name</code>, where <code>country</code>
    is a two character country code. <em>There is no proper input validation</em>
    as it's only for demo purposes.</p>
  <script type="text/javascript">
  /* Custom data objects passed as teams */
  var customData = {
      teams : [
        [{name: "Team 1", flag: 'fi'}, null],
        [{name: "Team 3", flag: 'se'}, {name: "Team 4", flag: 'us'}]
      ],
      results : []
    }

  /* Edit function is called when team label is clicked */
  function edit_fn(container, data, doneCb) {
    var input = $('<input type="text">')
    input.val(data ? data.flag + ':' + data.name : '')
    container.html(input)
    input.focus()
    input.blur(function() {
      var inputValue = input.val()
      if (inputValue.length === 0) {
        doneCb(null); // Drop the team and replace with BYE
      } else {
        var flagAndName = inputValue.split(':') // Expects correct input
        doneCb({flag: flagAndName[0], name: flagAndName[1]})
      }
    })
  }

  /* Render function is called for each team label when data is changed, data
   * contains the data object given in init and belonging to this slot.
   *
   * 'state' is one of the following strings:
   * - empty-bye: No data or score and there won't team advancing to this place
   * - empty-tbd: No data or score yet. A team will advance here later
   * - entry-no-score: Data available, but no score given yet
   * - entry-default-win: Data available, score will never be given as opponent is BYE
   * - entry-complete: Data and score available
   */
  function render_fn(container, data, score, state) {
    switch(state) {
      case "empty-bye":
        container.append("No team")
        return;
      case "empty-tbd":
        container.append("Upcoming")
        return;

      case "entry-no-score":
      case "entry-default-win":
      case "entry-complete":
        container.append('<img src="jQuery Bracket_files/'+data.flag+'.png" /> ').append(data.name)
        return;
    }
  }

  $(function() {
    $('div#customHandlers .demo').bracket({
      init: customData,
      save: function(){}, /* without save() labels are disabled */
      decorator: {edit: edit_fn,
                  render: render_fn}})
    })
  </script>
<!--<div class="demo">
	<div class="jQBracket lr" style="width: 320px; height: 170px;">
		<div class="tools"><span class="increment">+</span><span class="decrement">-</span><span class="doubleElimination">de</span></div>
		<div class="bracket" style="height: 130px;">
			<div class="round" style="width: 100px; margin-right: 40px">
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 10px;">
						<div class="team win" style="width: 100px;" data-teamid="0">
							<div class="label editable" style="width: 70px;"><img src="./jQuery Bracket_files/fi.png"> Team 1</div>
							<div class="score" style="width: 30px;">--</div>
						</div>
						<div class="team na" style="width: 100px;" data-teamid="1">
							<div class="label editable" style="width: 70px;">No team</div>
							<div class="score" style="width: 30px;">--</div>
						</div>
						<div class="connector" style="height: 32.5px; width: 20px; right: -22px; top: 10.25px; border-bottom: none;">
							<div class="connector" style="width: 20px; right: -20px; bottom: 0px;"></div>
						</div>
					</div>
				</div>
				<div class="match" style="height: 65px;">
					<div class="teamContainer" style="top: 10px;">
						<div class="team" style="width: 100px;" data-teamid="2">
							<div class="label editable" style="width: 70px;"><img src="./jQuery Bracket_files/se.png"> Team 3</div>
							<div class="score editable" style="width: 30px;" data-resultid="result-1">--
							</div></div>
							<div class="team" style="width: 100px;" data-teamid="3">
								<div class="label editable" style="width: 70px;"><img src="./jQuery Bracket_files/us.png"> Team 4</div>
								<div class="score editable" style="width: 30px;" data-resultid="result-2">--</div>
							</div>
							<div class="connector" style="height: 21.25px; width: 20px; right: -22px; bottom: 21.5px; border-top: none;">
								<div class="connector" style="width: 20px; right: -20px; top: 0px;"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="round" style="width: 100px; margin-right: 40px">
					<div class="match" style="height: 65px;">
						<div class="teamContainer" style="position: absolute; bottom: -22.5px;">
							<div class="team" style="width: 100px;" data-teamid="0">
								<div class="label editable" style="width: 70px;"><img src="./jQuery Bracket_files/fi.png"> Team 1</div>
								<div class="score" style="width: 30px;">--</div>
							</div>
							<div class="team na" style="width: 100px;">
								<div class="label" style="width: 70px;">Upcoming</div>
								<div class="score" style="width: 30px;">--</div>
							</div>
						</div>
					</div>
					<div class="match" style="height: 65px;">
						<div class="teamContainer np" style="top: 42.5px;">
							<div class="team na" style="width: 100px;" data-teamid="1">
								<div class="label" style="width: 70px;">No team</div>
								<div class="score" style="width: 30px;">--</div>
							</div>
							<div class="team na" style="width: 100px;">
								<div class="label" style="width: 70px;">Upcoming</div>
								<div class="score" style="width: 30px;">--</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div>
		<div id="highlighter_122617" class="syntaxhighlighter nogutter  js"><
			div class="toolbar"><span><a href="https://www.aropupu.fi/bracket/#" class="toolbar_item command_help help">?</a></span></div>

</div>
</div>
</div>-->

<!--<script async="" defer="" id="github-bjs" src="./jQuery Bracket_files/buttons.js"></script>-->
</body></html>
  































</body>
</html>  