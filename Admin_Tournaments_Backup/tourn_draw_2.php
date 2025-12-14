<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  

    <link rel="apple-touch-icon" type="image/png" href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />

    <meta name="apple-mobile-web-app-title" content="CodePen">

    <link rel="shortcut icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

    <link rel="mask-icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111" />



  
    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-2c7831bb44f98c1391d6a4ffda0e1fd302503391ca806e7fcc7b9b87197aec26.js"></script>


  <title>CodePen - Tournament Bracket</title>

    <link rel="canonical" href="https://codepen.io/rzencoder/pen/JaQreL">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  
  
<style>
.app {
  text-align: center;
  font-family: "Montserrat", arial, sans-serif;
}

.title {
  background-color: #222;
  text-transform: uppercase;
  font-size: 40px;
  font-weight: 700;
  padding: 15px;
  color: white;
}

.category-selection {
  width: 280px;
  margin: 15px auto 5px;
  display: flex;
}
.category-selection > div {
  padding: 0 10px;
}

.champions-container {
  background: #eee;
  padding: 10px;
  width: 85%;
  margin-left: 10px;
  min-height: 150px;
  box-shadow: 0 0 8px 4px #999;
  position: absolute;
}
@media (max-width: 1000px) {
  .champions-container {
    position: initial;
    margin: 30px auto 15px;
    width: 90%;
    min-height: 150px;
  }
}

.champions-data {
  font-size: 26px;
  text-align: center;
}
.champions-data div {
  margin: 10px;
  padding: 5px 0;
}
.champions-data i {
  color: gold;
  font-size: 50px;
}
.champions-data .champions-team {
  padding: 10px 0;
  font-weight: 700;
}
@media (max-width: 1000px) {
  .champions-data .champions-team {
    font-size: 30px;
  }
}

.knockout-stage {
  text-align: center;
  width: 25%;
  background: #ececec;
  padding-bottom: 50px;
  border-left: 0.5px dashed #888;
}
@media (max-width: 1000px) {
  .knockout-stage {
    width: 100%;
    background: initial;
    padding-bottom: 10px;
  }
}
.knockout-stage:nth-of-type(1) {
  border-left: none;
}
.knockout-stage:nth-of-type(4) {
  border-left: none;
}
@media (max-width: 1000px) {
  .knockout-stage:nth-of-type(4) {
    padding-bottom: 50px;
  }
}
.knockout-stage:nth-of-type(2n) {
  background: #f3f3f3;
}
@media (max-width: 1000px) {
  .knockout-stage:nth-of-type(2n) {
    background: initial;
  }
}
.knockout-stage h2 {
  font-size: 26px;
  font-weight: 700;
  padding: 20px 0 10px;
}
@media (max-width: 1000px) {
  .knockout-stage h2 {
    font-size: 32px;
  }
}

.knockout-container {
  display: flex;
  flex-direction: row;
  padding: 20px;
  justify-content: space-around;
}
@media (max-width: 1000px) {
  .knockout-container {
    flex-direction: column;
    width: 70%;
    margin: 0 auto;
  }
}
@media (max-width: 600px) {
  .knockout-container {
    width: 90%;
  }
}

.knockout-round-container {
  display: flex;
  position: relative;
  flex-direction: column;
  justify-content: space-around;
  height: 100%;
  width: 90%;
  margin: 0 auto;
}
.knockout-round-container:nth-of-type(4) {
  justify-content: center;
}
@media (max-width: 1000px) {
  .knockout-round-container {
    height: auto;
    width: auto;
    margin: initial;
  }
}

.knockout-match {
  font-family: "Montserrat", arial, sans-serif;
  font-size: 20px;
  position: relative;
  display: flex;
  justify-content: space-between;
  background: #eee;
  padding: 5px 10px;
  margin: 10px 5px 0px;
  text-align: center;
  min-height: 30px;
  z-index: 20;
  box-shadow: 0px 0px 10px 3px #aaa;
}

.knockout-match:nth-child(2n) {
  margin: 0 5px 10px;
}

.team-name {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.team-radio {
  display: flex;
  justify-content: center;
  align-items: center;
}

.bracket-team::before {
  content: "";
  position: absolute;
  height: 1px;
  width: 11%;
  left: -11%;
  top: 50%;
  border-top: 3px solid #444;
}
@media (max-width: 1000px) {
  .bracket-team::before {
    display: none;
  }
}
.bracket-team::after {
  content: "";
  position: absolute;
  display: block;
  width: 10px;
  right: -12.5px;
  border-color: #444;
  border-width: 3px;
}
@media (max-width: 1000px) {
  .bracket-team::after {
    display: none;
  }
}

.bracket-team:nth-of-type(odd):after {
  height: 100%;
  top: 50%;
  border-right-style: solid;
  border-top-style: solid;
}

.bracket-team:nth-of-type(even):after {
  height: 100%;
  top: -50%;
  border-right-style: solid;
  border-bottom-style: solid;
}

.bracket-1 .bracket-team:before {
  display: none;
}

.bracket-2 .bracket-team:nth-of-type(odd):after {
  height: 200%;
  top: 50%;
}
.bracket-2 .bracket-team:nth-of-type(even):after {
  height: 200%;
  top: -150%;
}

.bracket-3 .bracket-team:nth-of-type(odd):after {
  height: 350%;
  top: 50%;
}
.bracket-3 .bracket-team:nth-of-type(even):after {
  height: 350%;
  top: -300%;
}

.bracket-4 .bracket-team:after {
  display: none;
}
</style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
</head>

<body translate="no">
  <div id="root"></div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/react/16.4.2/umd/react.production.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/react-dom/16.4.2/umd/react-dom.production.min.js'></script>
      <script id="rendered-js" >
const categories = [
{
  name: "Football Teams",
  items: ["Arsenal", "Chelsea", "Liverpool", "Man City", "Man United", "Tottenham", "Real Madrid", "Barcelona", "Atletico Madrid", "Bayern Munich", "Borussia Dortmund", "Juventus", "Paris Saint Germain", "AC Milan", "Inter Milan", "Ajax"] },

{
  name: "British Bands",
  items: ["The Beatles", "The Rolling Stones", "The Kinks", "The Who", "Queen", "Led Zeppelin", "The Clash", "The Jam", "New Order", "Oasis", "Blur", "Muse", "Arctic Monkeys", "The Cure", "Black Sabbath", "Radiohead"] },

{
  name: "US Presidents",
  items: ["Washington", "Jefferson", "Lincoln", "T. Roosevelt", "F.D. Roosevelt", "Kennedy", "Nixon", "Clinton", "Reagan", "G.W. Bush", "Obama", "Wilson", "Trump", "Truman", "Carter", "Eisenhower"] }];



const blankRound = [
['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
['', '', '', '', '', '', '', ''],
['', '', '', ''],
['', '']];


function shuffle(a) {
  for (let i = a.length - 1; i > 0; i--) {if (window.CP.shouldStopExecution(0)) break;
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }window.CP.exitedLoop(0);
  return a;
}

class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      categories: categories,
      round: blankRound.map(arr => arr.slice()),
      champion: '' };

    this.onSelect = this.onSelect.bind(this);
    this.changeCategory = this.changeCategory.bind(this);
  }
  componentWillMount() {
    let round = [...this.state.round];
    round[0] = shuffle(this.state.categories[0].items);
    this.setState({
      round: round });

  }

  changeCategory(event) {
    let round = [...blankRound];
    round[0] = shuffle(this.state.categories[event.target.value].items);
    this.setState({
      champion: '',
      round: round });

  }

  onSelect(item, index, roundIndex) {
    let champion = this.state.champion;
    let round = [...this.state.round];
    if (roundIndex === 3) {
      champion = item;
    } else {
      round[roundIndex + 1][index] = item;
      if (roundIndex === 0) {
        round[2][Math.floor(index / 2)] = "";
        round[3][Math.floor(index / 4)] = "";
        champion = "";
      }
      if (roundIndex === 1) {
        round[3][Math.floor(index / 2)] = "";
        champion = "";
      }
      if (roundIndex === 2) {
        champion = "";
      }
    }
    this.setState({
      round: round,
      champion: champion });

  }

  render() {
    const list = this.state.round.map((round, i) => {
      return round.map((el, j) => {
        let checked = i !== 3 ? this.state.round[i + 1][Math.floor(j / 2)] : this.state.champion;
        return /*#__PURE__*/React.createElement(Match, { roundIndex: i, data: el, checked: checked, index: Math.floor(j / 2), onSelect: this.onSelect });
      });
    });
    const rounds = list.map((el, i) => {
      const key = 'round' + i;
      return /*#__PURE__*/React.createElement(Round, { key: key, data: el, round: i, champion: this.state.champion });
    });

    const options = this.state.categories.map((el, i) => {
      return /*#__PURE__*/React.createElement("option", { value: i }, el.name);
    });

    return /*#__PURE__*/(
      React.createElement("div", { className: "app" }, /*#__PURE__*/
      React.createElement("header", null, /*#__PURE__*/
      React.createElement("h1", { className: "title" }, "Championship of Anything")), /*#__PURE__*/

      React.createElement("div", { className: "category-selection" }, /*#__PURE__*/
      React.createElement("div", null, "Choose Category: "), /*#__PURE__*/
      React.createElement("select", { onChange: this.changeCategory },
      options)), /*#__PURE__*/


      React.createElement("div", { className: "knockout-container" },
      rounds)));



  }}


class Match extends React.Component {
  render() {
    let checked = this.props.checked === this.props.data && this.props.data !== "";
    return /*#__PURE__*/(
      React.createElement("div", { className: "knockout-match bracket-team" }, /*#__PURE__*/
      React.createElement("div", { className: "team-name" }, /*#__PURE__*/
      React.createElement("div", null, this.props.data)), /*#__PURE__*/

      React.createElement("div", { className: "team-radio" },
      this.props.data ? /*#__PURE__*/React.createElement("input", { type: "radio", checked: checked,
        onChange: () => this.props.onSelect(this.props.data, this.props.index, this.props.roundIndex) }) :
      '')));



  }}


class Round extends React.Component {
  render() {
    const champions = this.props.champion && this.props.round === 3 ? /*#__PURE__*/React.createElement("div", { className: "champions-container" }, /*#__PURE__*/
    React.createElement("div", { className: "champions-data" }, /*#__PURE__*/
    React.createElement("div", null, /*#__PURE__*/React.createElement("i", { className: "fas fa-trophy" })), /*#__PURE__*/
    React.createElement("div", { className: "champions-team" }, this.props.champion))) :

    '';
    return /*#__PURE__*/(
      React.createElement("div", { className: "knockout-stage" }, /*#__PURE__*/
      React.createElement("h2", null), /*#__PURE__*/
      React.createElement("div", { className: 'knockout-round-container bracket-' + (this.props.round + 1) },
      champions,
      this.props.data)));



  }}


ReactDOM.render( /*#__PURE__*/React.createElement(App, null), document.getElementById('root'));
//# sourceURL=pen.js
    </script>

  
</body>

</html>
