<?php
require_once('../Connections/connvbsa.php'); 
include '../vbsa_online_scores/header_admin.php';
//include '../vbsa_online_scores/header_vbsa.php';

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = 202281';
$result_tourn = mysql_query($query_tourn, $connvbsa) or die(mysql_error());
$total_tourn = $result_tourn->num_rows;

?>
<!DOCTYPE html>
<html lang="en" >

<head>
<meta charset="UTF-8">
<title>CodePen - Responsive Tournament Brackets</title>
<style>
*,
::after,
::before {
  box-sizing: border-box;
}

body {
  margin: 0;
  padding: 0;
  min-height: 100vh;
  background-color: #f0f0f0;
  font-family: sans-serif;
}

.container {
  padding: 0 1rem;
}
@media (min-width: 1400px) {
  .container {
    /*max-width: 1300px;*/
    margin-left: auto;
    margin-right: auto;
  }
}

.tournament-brackets {
  display: flex;
  flex-direction: row;
}
@media (max-width: 992px) {
  .tournament-brackets {
    flex-direction: column;
  }
}
.tournament-brackets .bracket-round {
  flex: 1;
}
.tournament-brackets .bracket-round .round-title {
  color: #8f8f8f;
  font-weight: 400;
  text-align: center;
}
.tournament-brackets .bracket-round .matches-list {
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  flex-flow: row wrap;
  justify-content: center;
  height: 100%;
  list-style: none;
}
.tournament-brackets .bracket-round .matches-list .round-item {
  padding: 0.5em 1.5em;
  min-width: 250px;
  width: 100%;
  position: relative;
  display: flex;
  align-items: center;
  flex: 0 1 auto;
  justify-content: center;
}
.tournament-brackets .bracket-round .matches-list .round-item .match {
  width: 100%;
  background-color: #fff;
  padding: 1em;
  border: 1px solid transparent;
  border-radius: 0.1em;
  box-shadow: 0 2px 0 0 #e5e5e5;
  text-align: center;
}
.tournament-brackets .bracket-round .matches-list .round-item .match > div:not(:last-child) {
  margin-bottom: 0.8rem;
}
.tournament-brackets .bracket-round .matches-list .round-item .match time {
  color: #8f8f8f;
}
.tournament-brackets .bracket-round .matches-list .round-item .match .scores span {
  display: inline-block;
  padding: 5px 7px;
  background-color: #f3c74c;
  border-radius: 5px;
}
.tournament-brackets .bracket-round .matches-list .round-item .match .teams span {
  display: inline-block;
  color: #3333bd;
  border-radius: 5px;
  font-weight: bold;
}
@media (min-width: 992px) {
  .tournament-brackets .bracket-round .matches-list .round-item::after {
    content: "";
    position: absolute;
    height: 50%;
    right: 0;
    width: 1.5em;
  }
  .tournament-brackets .bracket-round .matches-list .round-item:nth-child(even)::after {
    border-bottom-right-radius: 12px;
    border-bottom: 2px solid #707070;
    border-right: 2px solid #707070;
    top: 0;
  }
  .tournament-brackets .bracket-round .matches-list .round-item:nth-child(odd)::after {
    border-top-right-radius: 12px;
    border-top: 2px solid #707070;
    border-right: 2px solid #707070;
    top: 50%;
  }
}
@media (min-width: 992px) {
  .tournament-brackets .bracket-round:not(:nth-child(1)) .round-item::before {
    content: "";
    position: absolute;
    width: 1.5em;
    left: 0;
    top: 0;
    height: 50%;
    border-bottom: 2px solid #707070;
  }
  .tournament-brackets .bracket-round:last-child .round-item::after {
    content: unset;
  }
}
</style>

<script>
  window.console = window.console || function(t) {};
</script>

</head>

<body translate="no">
  <div class="container">
    <div class="tournament-brackets"></div>
</div>
  
<script id="rendered-js" >
(function () {

  // * uncomment 32 object and 64 object to see it,
  // it works in a pattern of 2,4,8,16,32,64,128,256.....
  var round = 1;
  const rounds = [
   {
/*   title: "Round " + (round++),
     matches: [...new Array(128)].fill({
       date: new Date(),
       teams: [
         { name: "Team A", score: 5 },
         { name: "Team B", score: 2 }] }) },
   {
     title: "Round " + (round++),
     matches: [...new Array(64)].fill({
       date: new Date(),
       teams: [
         { name: "Team A", score: 5 },
         { name: "Team B", score: 2 }] }) },
   {
*/      
    title: "Round " + (round++),
     matches: [...new Array(32)].fill({
       date: new Date(),
       teams: [
         { name: "Team A", score: 5 },
         { name: "Team B", score: 2 }] }) },
  {
    title: "Round " + (round++),
    matches: [...new Array(16)].fill({
      date: new Date(),
      teams: [
      { name: "Team A", score: 5 },
      { name: "Team B", score: 2 }] }) },
  {
    title: "Round " + (round++),
    matches: [...new Array(8)].fill({
      date: new Date(),
      teams: [
      { name: "Team A", score: 5 },
      { name: "Team B", score: 2 }] }) },
  {
    title: "Quarter Finals",
    matches: [...new Array(4)].fill({
      date: new Date(),
      teams: [
      { name: "Team A", score: 5 },
      { name: "Team B", score: 2 }] }) },
  {
    title: "Semi Finals",
    matches: [...new Array(2)].fill({
      date: new Date(),
      teams: [
      { name: "Team A", score: 5 },
      { name: "Team B", score: 2 }] }) },
  {
    title: "Finals",
    matches: [...new Array(1)].fill({
      date: new Date(),
      teams: [
      { name: "Team A", score: 5 },
      { name: "Team B", score: 2 }] }) }];

  const brackets = document.querySelector(".tournament-brackets");
  rounds.forEach(round => {
    const matches = round.matches.map(match => {
      const date = `${match.date.getFullYear()}-${
      match.date.getMonth() > 9 ?
      match.date.getMonth() :
      `0${match.date.getMonth()}`
      }-${
      match.date.getDate() > 9 ?
      match.date.getDate() :
      `0${match.date.getDate()}`
      }`;

      return `<li class="round-item">
          <div class="match">
            <div><time datetime="${date}"> ${date} </time></div>
            <div class="scores"><span>${match.teams[0].score}</span> : <span>${match.teams[1].score}</span></div>
            <div class="teams">
              <span>${match.teams[0].name}</span> vs <span>${match.teams[1].name}</span>
            </div>
          </div>
        </li>`;
    });

    const elem = `<div class="bracket-round">
            <h3 class="round-title">
                ${round.title}
            </h3>
            <ul class="matches-list">
            ${matches.join("")}
            </ul>
      </div>`;
    brackets.innerHTML += elem;
  });
  // console.log(brackets.innerHTML)
})();

</script>
<?php
/*
while($build_tourn = $result_tourn->fetch_assoc())
{
    echo('<li class="round-item">
          <div class="match">
            <div><time datetime="' . date() . '"> " . date() . "</time></div>
            <div class="scores"><span>5</span> : <span>4</span></div>
            <div class="teams">
              <span>' . $build_tourn['FirstName'] . ' ' . $build_tourn['LastName'] . '</span> vs <span>' . $build_tourn['FirstName'] . ' ' . $build_tourn['LastName'] . '</span>
            </div>
          </div>
        </li>');
}
*/
?> 
</body>

</html>
