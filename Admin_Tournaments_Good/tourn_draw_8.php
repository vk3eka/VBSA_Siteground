<?php
require_once('../Connections/connvbsa.php'); 
//include '../vbsa_online_scores/header_admin.php';
include '../vbsa_online_scores/header_vbsa.php';

mysql_select_db($database_connvbsa, $connvbsa);

error_reporting(0);
/*
'202272', '8'
'202274', '15'
'202281', '24'
'202251', '48'
'202269', '98'
*/

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link href="../vbsa_online_scores/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<style>

@import url("https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700");
html {
  height: 100%;
  width: 100%;
}

body {
  font-family: "source sans pro", sans-serif;
  height: 100%;
  width: 100%;
}

.theme {
  /*height: 100%;
  width: 100%;
  position: absolute;*/
}

.bracket {
  padding: 40px;
  margin: 5px;
}

.bracket {
  display: flex;
  flex-direction: row;
  position: relative;
}

.column {
  display: flex;
  flex-direction: column;
  min-height: 100%;
  justify-content: space-around;
  align-content: center;
}

.match {
  position: relative;
  display: flex;
  flex-direction: column;
  min-width: 240px;
  max-width: 240px;
  height: 62px;
  margin: 12px 24px 12px 0;
}
.match .match-top {
  border-radius: 2px 2px 0 0;
}
.match .match-bottom {
  border-radius: 0 0 2px 2px;
}
.match .team {
  display: flex;
  align-items: center;
  width: 100%;
  height: 100%;
  border: 1px solid black;
  position: relative;
}
.match .team span {
  padding-left: 8px;
}
.match .team span:last-child {
  padding-right: 8px;
}
.match .team .score {
  margin-left: auto;
}
.match .team:first-child {
  margin-bottom: -1px;
}

.match-lines {
  display: block;
  position: absolute;
  top: 50%;
  bottom: 0;
  margin-top: 0px;
  right: -1px;
}
.match-lines .line {
  background: red;
  position: absolute;
}
.match-lines .line.one {
  height: 1px;
  width: 12px;
}
.match-lines .line.two {
  height: 44px;
  width: 1px;
  left: 11px;
}
.match-lines .line.three {
  height: 344px;
  width: 1px;
  left: 11px;
}
.match-lines.alt {
  left: -12px;
}

.match:nth-child(even) .match-lines .line.two {
  transform: translate(0, -100%);
}

.match:nth-child(even) .match-lines .line.three {
  transform: translate(0, -100%);
}

.column:first-child .match-lines.alt {
  display: none;
}

.column:last-child .match-lines {
  display: none;
}
.column:last-child .match-lines.alt {
  display: block;
}

.column:nth-child(2) .match-lines .line.two {
  height: 88px;
}

.column:nth-child(3) .match-lines .line.two {
  height: 175px;
}

.column:nth-child(4) .match-lines .line.two {
  height: 262px;
}

.column:nth-child(5) .match-lines .line.two {
  height: 349px;
}

.theme-light {
  background: #f9fafd;
  border-color: #e6eaf7;
}
.theme-light .match-lines .line {
  background: #dadfe3;
}
.theme-light .team {
  background: #fff;
  border-color: #dadfe3;
  color: #708392;
}
.theme-light .winner-top .match-top,
.theme-light .winner-bottom .match-bottom {
  background: #fff;
  color: #272f36;
  border-color: #dadfe3;
  z-index: 1;
}
.theme-light .match .seed {
  color: #9fafbf;
}
.theme-light .match .score {
  color: #9fafbf;
}
.theme-light .match .seed {
  font-size: 12px;
  min-width: 10px;
}
.theme-light .match .score {
  font-size: 14px;
}

.theme-dark {
  background: #0e1217;
  border-color: #040607;
}
.theme-dark .match-lines .line {
  background: #36404e;
}
.theme-dark .team {
  background: #182026;
  border-color: #232c36;
  color: #6b798c;
}
.theme-dark .winner-top .match-top,
.theme-dark .winner-bottom .match-bottom {
  background: #232c36;
  color: #e3e8ef;
  border-color: #36404e;
  z-index: 1;
}
.theme-dark .winner-top .match-top .score,
.theme-dark .winner-bottom .match-bottom .score {
  color: #03d9ce;
}
.theme-dark .match .seed {
  font-size: 12px;
  min-width: 10px;
}
.theme-dark .match .score {
  font-size: 14px;
}

.theme-dark-trendy {
  background: #2b5876;
  background: -webkit-linear-gradient(to right, #171721, #122b29);
  background: linear-gradient(to right, #171721, #122b29);
  border-color: #040607;
}
.theme-dark-trendy .match-lines .line {
  background: #36404e;
}
.theme-dark-trendy .team {
  background: rgba(50, 54, 65, 0.4);
  color: #6b798c;
  border: 2px solid transparent;
}
.theme-dark-trendy .team:first-child {
  margin-bottom: 2px;
}
.theme-dark-trendy .team:last-child {
  margin-top: 2px;
}
.theme-dark-trendy .winner-top .match-top,
.theme-dark-trendy .winner-bottom .match-bottom {
  background: #323641;
  color: #e3e8ef;
  z-index: 1;
}
.theme-dark-trendy .winner-top .match-top .score,
.theme-dark-trendy .winner-bottom .match-bottom .score {
  color: #03d9ce;
}
.theme-dark-trendy .match {
  margin-right: 48px;
}
.theme-dark-trendy .match .team .name {
  text-transform: uppercase;
  font-size: 14px;
  letter-spacing: 0.5px;
}
.theme-dark-trendy .match .seed {
  display: none;
}
.theme-dark-trendy .match .match-top {
  border-radius: 0;
}
.theme-dark-trendy .match .match-bottom {
  border-radius: 0;
}
.theme-dark-trendy .match-lines {
  opacity: 0.75;
  right: -12px;
}
.theme-dark-trendy .match-lines .line {
  background: #03d9ce;
}
.theme-dark-trendy .match-lines.alt {
  left: -24px;
}
.theme-dark-trendy .team {
  overflow: hidden;
}
.theme-dark-trendy .score:before {
  opacity: 0.25;
  position: absolute;
  z-index: 1;
  content: "";
  display: block;
  background: black;
  min-height: 50px;
  min-width: 70px;
  transform: translate(-12px, 0) rotate(25deg);
}

.disable-image .image,
.disable-seed .seed,
.disable-name .name,
.disable-score .score {
  display: none !important;
}

.disable-borders {
  border-width: 0px !important;
}
.disable-borders .team {
  border-width: 0px !important;
}

.disable-seperator .match-top {
  border-bottom: 0px !important;
}
.disable-seperator .match-bottom {
  border-top: 0px !important;
}
.disable-seperator .team:first-child {
  margin-bottom: 0px;
}

.theme-switcher {
  position: absolute;
  top: 20px;
  right: 20px;
  padding: 24px;
  border: 1px solid #c0cfdd;
  background: #6b798c;
}
.theme-switcher h2 {
  color: #e1e8ef;
  text-transform: uppercase;
  font-size: 12px;
  margin: 0 0 12px 0;
}
.theme-switcher button {
  line-height: 1em;
  font-size: 14px;
  font-weight: 500;
  padding: 10px 16px;
  border-radius: 2px;
  border: 1px solid #ccc;
  cursor: pointer;
  background: #6b798c;
  color: #ccc;
  border: 1px solid #eee;
}

.theme-light .theme-switcher #theme-light,
.theme-dark .theme-switcher #theme-dark,
.theme-dark-trendy .theme-switcher #theme-dark-trendy,
.theme-none .theme-switcher #theme-none {
  background: #505b69;
  border-color: #4a5461;
}
</style>
  <!--<div id="Wrapper">Wrapper contains content to a max width of 1200px--> 
  <div class="row"> 
    <div class="Page_heading_container">
      <div class="page_title"><?php echo date("Y"); ?> Tournaments</div>
    </div>    
    <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
<?php include '../includes/prev_page.php';?>
</div>  <!-- close containing wrapper --> 
<?php

$tournamentID = 202269;
$tourn_caption = "(Tournament ID " . $tournamentID . ")";

// get tournament name
$query_tourn_name = 'Select * FROM vbsa3364_vbsa2.tournaments LEFT JOIN calendar ON tournaments.tourn_id =  calendar.tourn_id where tournaments.tourn_id = ' . $tournamentID;
$result_tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
$build_tourn_name = $result_tourn_name->fetch_assoc();
$tourn_type = $build_tourn_name['tourn_type'];

$query_scores = 'Select * FROM vbsa3364_vbsa2.tournament_scores where tourn_id = ' . $tournamentID;
$result_scores = mysql_query($query_scores, $connvbsa) or die(mysql_error());
$total_tourn = $result_scores->num_rows;

function GetPlayerNumber($total_tourn)
{
  switch ($total_tourn) {
    case ($total_tourn <= 8):
      $total_players = 8;
      break;
    case ($total_tourn <= 16) && ($total_tourn > 8):
      $total_players = 16;
      break;
    case ($total_tourn <= 32) && ($total_tourn > 16):
      $total_players = 32;
      break;
    case ($total_tourn <= 64) && ($total_tourn > 32):
      $total_players = 64;
      break;
    case ($total_tourn <= 128) && ($total_tourn > 64):
      $total_players = 128;
      break;
  }
  return $total_players;
}

?>
<div align='center'><h3><?= $build_tourn_name['tourn_name'] ?></h3></div>
<div align='center'><?= $tourn_caption ?></div>
<div hidden align='center' id='tourn_id'><?= $tournamentID ?></div>
<br>
<div align='center'>Start Date <?= $build_tourn_name['startdate'] ?> - Finish Date <?= $build_tourn_name['finishdate'] ?></div>
<br>
<br>
<center>
<div class="theme theme-light">
  <table width="300" border="0" align="center">
  <tr>
    <td align='left'><input type="button" id="backBtn" value="<- Back"/></td>
    <td align='right'><input type="button" id="nextBtn" value="Next ->"/></td>
  </tr>
</table>
<br>
  <div class="bracket  disable-image">
    <div id='R32' class="content-div active">
      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">1</span>
          <span class="name">Orlando Jetsetters (32)</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">8</span>
          <span class="name">D.C. Senators</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-bottom">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">4</span>
          <span class="name">New Orleans Rockstars</span>
          <span class="score">1</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">5</span>
          <span class="name">West Virginia Runners</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">2</span>
          <span class="name">Denver Demon Horses</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">7</span>
          <span class="name">Chicago Pistons</span>
          <span class="score">0</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">3</span>
          <span class="name">San Francisco Porters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">6</span>
          <span class="name">Seattle Climbers</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">1</span>
          <span class="name">Orlando Jetsetters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">8</span>
          <span class="name">D.C. Senators</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-bottom">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">4</span>
          <span class="name">New Orleans Rockstars</span>
          <span class="score">1</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">5</span>
          <span class="name">West Virginia Runners</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">2</span>
          <span class="name">Denver Demon Horses</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">7</span>
          <span class="name">Chicago Pistons</span>
          <span class="score">0</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">3</span>
          <span class="name">San Francisco Porters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">6</span>
          <span class="name">Seattle Climbers</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">1</span>
          <span class="name">Orlando Jetsetters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">8</span>
          <span class="name">D.C. Senators</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-bottom">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">4</span>
          <span class="name">New Orleans Rockstars</span>
          <span class="score">1</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">5</span>
          <span class="name">West Virginia Runners</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">2</span>
          <span class="name">Denver Demon Horses</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">7</span>
          <span class="name">Chicago Pistons</span>
          <span class="score">0</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">3</span>
          <span class="name">San Francisco Porters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">6</span>
          <span class="name">Seattle Climbers</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">1</span>
          <span class="name">Orlando Jetsetters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">8</span>
          <span class="name">D.C. Senators</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-bottom">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">4</span>
          <span class="name">New Orleans Rockstars</span>
          <span class="score">1</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">5</span>
          <span class="name">West Virginia Runners</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">2</span>
          <span class="name">Denver Demon Horses</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">7</span>
          <span class="name">Chicago Pistons</span>
          <span class="score">0</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">3</span>
          <span class="name">San Francisco Porters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">6</span>
          <span class="name">Seattle Climbers</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>
    </div>
    <div id='R16' class="column 16 content-div">
      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">1</span>
          <span class="name">Orlando Jetsetters (16)</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">8</span>
          <span class="name">D.C. Senators</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-bottom">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">4</span>
          <span class="name">New Orleans Rockstars</span>
          <span class="score">1</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">5</span>
          <span class="name">West Virginia Runners</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">2</span>
          <span class="name">Denver Demon Horses</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">7</span>
          <span class="name">Chicago Pistons</span>
          <span class="score">0</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">3</span>
          <span class="name">San Francisco Porters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">6</span>
          <span class="name">Seattle Climbers</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">1</span>
          <span class="name">Orlando Jetsetters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">8</span>
          <span class="name">D.C. Senators</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-bottom">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">4</span>
          <span class="name">New Orleans Rockstars</span>
          <span class="score">1</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">5</span>
          <span class="name">West Virginia Runners</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">2</span>
          <span class="name">Denver Demon Horses</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">7</span>
          <span class="name">Chicago Pistons</span>
          <span class="score">0</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">3</span>
          <span class="name">San Francisco Porters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">6</span>
          <span class="name">Seattle Climbers</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>
    </div>
    <div id='R8'  class="column 8 content-div">
      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">1</span>
          <span class="name">Orlando Jetsetters (8)</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">8</span>
          <span class="name">D.C. Senators</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-bottom">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">4</span>
          <span class="name">New Orleans Rockstars</span>
          <span class="score">1</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">5</span>
          <span class="name">West Virginia Runners</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">2</span>
          <span class="name">Denver Demon Horses</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">7</span>
          <span class="name">Chicago Pistons</span>
          <span class="score">0</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">3</span>
          <span class="name">San Francisco Porters</span>
          <span class="score">2</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">6</span>
          <span class="name">Seattle Climbers</span>
          <span class="score">1</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line two"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>
    </div>

    <div id='R4' class="column 4 content-div">
      <div class="match winner-bottom">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">1</span>
          <span class="name">Orlando Jetsetters (4)</span>
          <span class="score">1</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">5</span>
          <span class="name">West Virginia Runners</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line three"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>

      <div class="match winner-bottom">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">2</span>
          <span class="name">Denver Demon Horses</span>
          <span class="score">1</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">3</span>
          <span class="name">San Francisco Porters</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
          <div class="line three"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>
    </div>

    <div id='R2' class="column 2 content-div">
      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="seed">5</span>
          <span class="name">West Virginia Runners (2)</span>
          <span class="score">3</span>
        </div>
        <div class="match-bottom team">
          <span class="image"></span>
          <span class="seed">3</span>
          <span class="name">San Francisco Porters</span>
          <span class="score">2</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>        
    </div>

    <div id='R1' class="column 1 content-div">
      <div class="match winner-top">
        <div class="match-top team">
          <span class="image"></span>
          <span class="name">West Virginia Runners (1)</span>
        </div>
        <div class="match-lines">
          <div class="line one"></div>
        </div>
        <div class="match-lines alt">
          <div class="line one"></div>
        </div>
      </div>        
    </div>

  </div>
</div>
</center>
<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>

<script>

$(document).ready(function(){

  if(/iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent) || screen.availWidth > 480)
  {
    mobile = true;
    $("#R32").show();
    $("#R16").show();
    $("#R8").hide();
    $("#R4").hide();
    $("#R2").hide();
    $("#R1").hide();
    $("#backBtn").show();
    $("#nextBtn").show();
  }
  else
  {
    mobile = false;
    $("#R32").show();
    $("#R16").show();
    $("#R8").show();
    $("#R4").show();
    $("#R2").show();
    $("#R1").show();
    $("#backBtn").hide();
    $("#nextBtn").hide();
  }
  total_matches = '<?= GetPlayerNumber($total_tourn) ?>';
  console.log("Number of matches in tournament " + total_matches);
/*  if(total_matches <= 32)
  {
    
    $("#R32").show();
    $("#R16").show();
    $("#R8").hide();
    $("#R4").hide();
    $("#R2").hide();
    $("#R1").hide();
*/

  $("#backBtn").click(function()
  {
      if(($("#R16").is(':visible')) && ($("#R8").is(':visible')))
      {
        $("#R32").show();
        $("#R16").show();
        $("#R8").hide();
        $("#R4").hide();
        $("#R2").hide();
        $("#R1").hide();
      }
      else if(($("#R8").is(':visible')) && ($("#R4").is(':visible')))
      {
        $("#R32").hide();
        $("#R16").show();
        $("#R8").show();
        $("#R4").hide();
        $("#R2").hide();
        $("#R1").hide();
      }
      else if(($("#R4").is(':visible')) && ($("#R2").is(':visible')))
      {
        $("#R32").hide();
        $("#R16").hide();
        $("#R8").show();
        $("#R4").show();
        $("#R2").hide();
        $("#R1").hide();
      }
      else if(($("#R2").is(':visible')) && ($("#R1").is(':visible')))
      {
        $("#R32").hide();
        $("#R16").hide();
        $("#R8").hide();
        $("#R4").show();
        $("#R2").show();
        $("#R1").hide();
      }
  });

  $("#nextBtn").click(function()
  {
      if(($("#R32").is(':visible')) && ($("#R16").is(':visible')))
      {
        $("#R32").hide();
        $("#R16").show();
        $("#R8").show();
        $("#R4").hide();
        $("#R2").hide();
        $("#R1").hide();
      }
     else if(($("#R16").is(':visible')) && ($("#R8").is(':visible')))
      {
        $("#R32").hide();
        $("#R16").hide();
        $("#R8").show();
        $("#R4").show();
        $("#R2").hide();
        $("#R1").hide();
      }
      else if(($("#R8").is(':visible')) && ($("#R4").is(':visible')))
      {
        $("#R32").hide();
        $("#R16").hide();
        $("#R8").hide();
        $("#R4").show();
        $("#R2").show();
        $("#R1").hide();
      }
      else if(($("#R4").is(':visible')) && ($("#R2").is(':visible')))
      {
        $("#R32").hide();
        $("#R16").hide();
        $("#R8").hide();
        $("#R4").hide();
        $("#R2").show();
        $("#R1").show();
      }
  });

});
</script>
</body>
</html>
