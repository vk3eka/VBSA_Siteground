<html>
<body>
<link rel="stylesheet" type="text/css" href="draw4.css">
<h1>Tournament Bracket</h1>
<br>
<br>
<table width="300" border="0" align="center">
  <tr>
    <td align='left'><button id="backBtn">Back</button></td>
    <td align='right'><button id="nextBtn">Next</button></td>
  </tr>
</table>
<br>

<div class="bracket">
  <section aria-labelledby="round-1" class="content-div active">
    <h2 id="round-1">Round 1</h2>
    <ol>
      <li>
        <div>
          <span>Player 1</span>
          <span>Player 2</span>
          <span>Date: 01.01. 1pm</span>
        </div>
      </li>
      <li>
        <div>
          <span>Player 3</span>
          <span>Player 4</span>
          <span>Date: 01.01. 1.30pm</span>
        </div>
      </li>
      <li>
        <div>
          <span>Player 5</span>
          <span>Player 6</span>
          <span>Date: 01.01. 2pm</span>
        </div>
      </li>
      <li>
        <div>
          <span>Player 7</span>
          <span>Player 8</span>
          <span>Date: 01.01. 2.30pm</span>
        </div>
      </li>
      <li>
        <div>
          <span>Player 9</span>
          <span>Player 10</span>
          <span>Date: 01.01. 3pm</span>
        </div>
      </li>
      <li>
        <div>
          <span>Player 11</span>
          <span>Player 12</span>
          <span>Date: 01.01. 3.30pm</span>
        </div>
      </li>
      <li>
        <div>
          <span>Player 13</span>
          <span>Player 14</span>
          <span>Date: 01.01. 4pm</span>
        </div>
      </li>
      <li>
        <div>
          <span>Player 15</span>
          <span>Player 16</span>
          <span>Date: 01.01. 4.30pm</span>
        </div>
      </li>
    </ol>
  </section>
  <section aria-labelledby="round-2" class="content-div">
    <h2 id="round-2">Round 2</h2>
      <ol>
        <li>
          <div>
            <span>Player 1</span>
            <span>Player 3</span>
            <span>Date: 05.01. 1pm</span>
          </div>
        </li>
        <li>
          <div>
            <span>Player 5</span>
            <span>Player 7</span>
            <span>Date: 05.01. 1.30pm</span>
          </div>
        </li>
        <li>
          <div>
            <span>Player 9</span>
            <span>Player 11</span>
            <span>Date: 05.01. 2pm</span>
          </div>
        </li>
        <li>
          <div>
            <span>Player 13</span>
            <span>Player 15</span>
            <span>Date: 05.01. 2.30pm</span>
          </div>
        </li>
      </ol>
  </section>
  <section aria-labelledby="round-3" class="content-div">
    <h2 id="round-3">Round 3</h2>
    <ol>
      <li>
        <div>
          <span>Player 1</span>
          <span>Player 5</span>
          <span>Date: 07.01. 1pm</span>
        </div>
      </li>
      <li>
        <div>
          <span>Player 9</span>
          <span>Player 13</span>
          <span>Date: 07.01. 1.30pm</span>
        </div>
      </li>
    </ol>
  </section>
  <section aria-labelledby="round-4" class="content-div">
    <h2 id="round-4">Round 4</h2>
    <ol>
      <li>
        <div>
          <span>Player 1</span>
          <span>Player 9</span>
          <span>Date: 10.01. 1pm</span>
        </div>
      </li>
    </ol>
  </section>
  <section aria-labelledby="winner" class="content-div">
    <h2 id="winner">Winner</h2>
    <ol>
      <li>
        <div>
          <span>Player 1</span>
        </div>
      </li>
    </ol>
  </section>
</div>
<script>

if(/iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent) || screen.availWidth < 480)
{
  alert("Its a mobile");
  echo("<style>");
  echo("  .content-div {");
  echo("      display: none;");
  echo("  }");
  echo("  .content-div.active {");
  echo("      display: block;");
  echo("  }");
  echo("  .content-div.non-mobile {");
  echo("      display: block;");
  echo("  }");
  echo("</style>");

  //document.getElementById('backBtn').classList.add('content-div.non-mobile');
  /*document.getElementById('backBtn').style.display = 'block';
  document.getElementById('nextBtn').style.display = 'none';

  document.getElementById('content1').classList.remove('active');
  document.getElementById('content1').classList.add('content-div non-mobile');
  //element2.classList.remove('content-div');
  document.getElementById('content2').classList.add('non-mobile');
  //element3.classList.remove('content-div');
  document.getElementById('content3').classList.add('non-mobile');
  */
}
else
{
  alert("Its not a mobile");
  /*
  var element1 = document.getElementById('content1');
  element1.classList.remove('non-mobile');
  var element2 = document.getElementById('content2');
  element2.classList.remove('non-mobile');
  var element3 = document.getElementById('content3');
  element3.classList.remove('non-mobile');
  */
}

</script>

<style>
    .content-div {
        display: none;
    }
    .content-div.active {
        display: block;
    }
    .content-div.non-mobile {
        display: block;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        let currentDiv = 0;
        const divs = $(".content-div");

        function updateDivs() {
            divs.removeClass("active");
            $(divs[currentDiv]).addClass("active");
        }

        $("#nextBtn").click(function(){
            if (currentDiv < divs.length - 1) {
                currentDiv++;
                updateDivs();
            }
        });

        $("#backBtn").click(function(){
            if (currentDiv > 0) {
                currentDiv--;
                updateDivs();
            }
        });
    });
</script>

</body>
</html>