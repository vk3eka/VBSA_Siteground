</div>  <!-- close containing wrapper --> 
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
/*
//echo($url . "/select_fixtures.php<br>");
echo '<pre>', htmlspecialchars(print_r($_COOKIE, true)), '</pre>';
//session_start();
echo '<pre>Session = ', session_id(), '</pre>';
echo("<pre>");
echo(var_dump($_SESSION));
echo("</pre>");
*/

?>

<br>
<br>
<?php

if((basename($_SERVER["PHP_SELF"]) != 'index.php') && (basename($_SERVER["PHP_SELF"]) != 'main.php'))
{
	$sql = ("Select * FROM members WHERE Email = '" . $_SESSION['username'] . "'");
      $result_member = $dbcnx_client->query($sql);
      $row_member = $result_member->fetch_assoc();
      $firstname = $row_member["FirstName"];

	echo("<div class='text-center'><i>" . $firstname . ", you are logged in as " . $_SESSION['login_rights'] . " for " . $_SESSION['clubname'] . ".</i></div>");
	echo("<div class='text-center'><i>Current year  " . $_SESSION['year'] . ", season " . $_SESSION['season'] . ".</i></div>");
}

?>

</body>
</html>