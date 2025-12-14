<?php

include ("connection.inc");
include ("header.php");

if ($_POST['ButtonName'] == "SelectSeason") {
	//echo($_POST['Season'] . "<br>");
	$year_season = explode(", ", $_POST['Season']);
	$_SESSION['season'] = $year_season[1];
	$_SESSION['year'] = $year_season[0];

	//echo("Season " . $_SESSION['season'] . "<br>");
	//echo("Year " . $_SESSION['year'] . "<br>");

	echo "<script type=\"text/javascript\">"; 
  echo "alert('The Season/Year has been changed.')"; 
  echo "</script>";
}

?>
<script type="text/JavaScript">

function ShowButton() {
	document.ladder.Season.value = document.getElementById('season').value;
	document.ladder.ButtonName.value = "SelectSeason";
	document.ladder.submit();
}

</script>
<center>
<form name="ladder" method="post" action="change_season.php">
<input type="hidden" name="Season">
<input type="hidden" name="ButtonName">
<table border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td align=center><h1>Change Current Season - <?php echo($_SESSION['year'] . ", " . $_SESSION['season']); ?></h1></td>
	</tr>
	<tr> 
		<td>&nbsp;</td>
	</tr>      
</table>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="10">
	 <td align='center' valign='top'><b>Select Season:&nbsp;&nbsp; <select id='season' onchange='ShowButton()'> 
<?php
if(isset($season)) 
{
     echo("<option value='' selected='selected'>" . $_SESSION['year'] . ", " . $_SESSION['season'] . "</option>"); 
}
else
{
     echo("<option value='' selected='selected'></option>");
}
echo("<option value=''>---------</option>");
echo("<option value='2023, S1'>2023, S1</option>");
echo("<option value='2023, S2'>2023, S2</option>");
echo("<option value='2024, S1'>2024, S1</option>");
echo("<option value='2024, S2'>2024, S2</option>");
echo("<option value='2025, S1'>2025, S1</option>");
echo("<option value='2025, S2'>2025, S2</option>");
?>
		</select></b> 
		</td> 
  </tr>
</table>

</form>
</center>
<?php
include("footer.php");
?>