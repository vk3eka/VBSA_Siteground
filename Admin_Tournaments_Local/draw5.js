(function() {
  
  // * uncomment 32 object and 64 object to see it,
  // it works in a pattern of 2,4,8,16,32,64,128,256.....
  const rounds = [
    // {
    //   title: "Round One",
    //   matches: [...new Array(64)].fill({
    //     date: new Date(),
    //     teams: [
    //       { name: "Team A", score: 5 },
    //       { name: "Team B", score: 2 }
    //     ]
    //   })
    // },
    // {
    //   title: "Round One",
    //   matches: [...new Array(32)].fill({
    //     date: new Date(),
    //     teams: [
    //       { name: "Team A", score: 5 },
    //       { name: "Team B", score: 2 }
    //     ]
    //   })
    // },
    {
      title: "Round One",
      matches: [...new Array(16)].fill({
        date: new Date(),
        teams: [
          { name: "Team A", score: 5 },
          { name: "Team B", score: 2 }
        ]
      })
    },
    {
      title: "Round One",
      matches: [...new Array(8)].fill({
        date: new Date(),
        teams: [
          { name: "Team A", score: 5 },
          { name: "Team B", score: 2 }
        ]
      })
    },
    {
      title: "Round Three",
      matches: [...new Array(4)].fill({
        date: new Date(),
        teams: [
          { name: "Team A", score: 5 },
          { name: "Team B", score: 2 }
        ]
      })
    },
    {
      title: "Round Three",
      matches: [...new Array(2)].fill({
        date: new Date(),
        teams: [
          { name: "Team A", score: 5 },
          { name: "Team B", score: 2 }
        ]
      })
    },
    {
      title: "Round Three",
      matches: [...new Array(1)].fill({
        date: new Date(),
        teams: [
          { name: "Team A", score: 5 },
          { name: "Team B", score: 2 }
        ]
      })
    }
  ];

  const brackets = document.querySelector(".tournament-brackets");
  rounds.forEach(round => {
    const matches = round.matches.map(match => {
      const date = `${match.date.getFullYear()}-${
        match.date.getMonth() > 9
          ? match.date.getMonth()
          : `0${match.date.getMonth()}`
      }-${
        match.date.getDate() > 9
          ? match.date.getDate()
          : `0${match.date.getDate()}`
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
  console.log(brackets.innerHTML)
})();
