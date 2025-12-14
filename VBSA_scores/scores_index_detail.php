<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;    
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}
$year = "-1";
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}

if(isset($_POST['ButtonName']) && ($_POST['ButtonName']) == "SelectSeason") 
{
  $year_season = explode(", ", $_POST['Season']);
  $season = $year_season[1];
  $year = $year_season[0];
}

mysql_select_db($database_connvbsa, $connvbsa);

$query_team_total = "Select COUNT(team_id) AS teams FROM Team_entries Where team_season ='$season' AND team_name <> 'Bye' AND team_cal_year = $year AND include_draw = 'Yes'";

$team_total = mysql_query($query_team_total, $connvbsa) or die(mysql_error());
$row_team_total = mysql_fetch_assoc($team_total);

$query_bill = "Select distinct team_grade as new_grade, COUNT( Team_entries.team_grade ) AS teams, day_played, comptype, (Select fix_upload from Team_grade where fix_cal_year = $year and season = '$season' and grade = new_grade LIMIT 1) as fix_upload FROM vbsa3364_vbsa2.Team_entries where team_cal_year = $year and team_season = '$season' and team_name <> 'Bye' and comptype = 'Billiards' group by team_grade order by day_played, new_grade";

//echo($query_bill . "<br>");

$bill = mysql_query($query_bill, $connvbsa) or die(mysql_error());
$row_bill = mysql_fetch_assoc($bill);
$totalRows_bill = mysql_num_rows($bill);

$query_snook = "Select distinct team_grade as new_grade, COUNT( Team_entries.team_grade ) AS teams, day_played, comptype, (Select fix_upload from Team_grade where fix_cal_year = $year and season = '$season' and grade = new_grade LIMIT 1) as fix_upload FROM vbsa3364_vbsa2.Team_entries where team_cal_year = $year and team_season = '$season' and team_name <> 'Bye' and comptype = 'Snooker' group by team_grade order by day_played, new_grade";

//echo($query_snook . "<br>");
$snook = mysql_query($query_snook, $connvbsa) or die(mysql_error());
$row_snook = mysql_fetch_assoc($snook);
$totalRows_snook = mysql_num_rows($snook);

$query_Teams = "Select team_club, team_id, team_name, team_grade, day_played, players FROM Team_entries Where team_season ='$season' AND team_name <> 'Bye' AND team_cal_year = $year AND include_draw = 'Yes' Order By team_name";

//echo($query_Teams . "<br>");
$Teams = mysql_query($query_Teams, $connvbsa) or die(mysql_error());
$row_Teams = mysql_fetch_assoc($Teams);
$totalRows_Teams = mysql_num_rows($Teams);

$query_need_players = "Select * FROM vbsa3364_vbsa2.Team_entries where need_players = 1 AND team_cal_year = '$year' AND team_season='$season'";
$Need_Players = mysql_query($query_need_players, $connvbsa) or die(mysql_error());
$totalRows_Need_Players = mysql_num_rows($Need_Players);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Scores</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />


<script src="https://unpkg.com/pdf-lib@1.4.0/dist/pdf-lib.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdf-lib@1.4.0/dist/pdf-lib.min.js"></script>


</head>
<body id="vbsa">
    
    <!-- Include Google Tracking -->
<?php include_once("../includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';

//echo($url . "<br>"); 
?>

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 
<!--Content--> 
<!--Left--> 
<div class="row"> 
  <div class="Page_heading_container">
  	<div class="page_title"><?php echo $year; ?> VBSA Scores – <?php if($season=='S1') echo "Premier / State (S1) Snooker & State Billiards Season"; else echo "Premier Billiards & Willis / State (S2) Snooker Season" ?></div>
  </div>  
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
</div>

<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>

<table class="table">
  <tr>
    <td colspan="7">
      <div class="text-center">
        <a href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
        <span class="italic"> = View &nbsp;&nbsp;&nbsp;</span>
        <a href="#"><span class="glyphicon glyphicon-download"></span></a>
        <span class="italic"> = Download</span>
      </div>
    </td>
  </tr>
</table>

<script type="text/JavaScript">

function ShowButton() 
{
  //alert(document.getElementById('season').value);
  document.change_year.Season.value = document.getElementById('season').value;
  document.change_year.ButtonName.value = "SelectSeason";
  document.change_year.submit();
}
</script>
<form name="change_year" id="change_year" method="post" action='scores_index_detail.php'>
<input type="hidden" name="ButtonName"/>
<input type="hidden" name="Season"/>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td align='center' valign='top'>Select Year/Season:&nbsp;&nbsp; <select id='season' onchange='ShowButton()'> 
<?php
if(isset($season)) 
{
     echo("<option value='' selected='selected'>" . $year . ", " . $season . "</option>"); 
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
echo("<option value='2026, S1'>2026, S1</option>");
echo("<option value='2026, S2'>2026, S2</option>");
?>
    </select> 
    </td> 
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>    
  <tr> 
    <td align='center' valign='top'><b>Total Teams for this Season/Year <?= $row_team_total['teams'];?>.</b></td>
  </tr>    
  <tr> 
    <td>&nbsp;</td>
  </tr>    
</table>
</form>

  <div class="table-responsive center-block" style="max-width:600px"> <!-- class table-responsive -->
<?php
$query_select = "Select * from tbl_team_select_visible where year='$year' and season = '$season'";
//echo($query_select . "<br>");
$result_select = mysql_query($query_select, $connvbsa) or die(mysql_error());
$build_select = mysql_fetch_assoc($result_select);

if(($totalRows_Need_Players > 0) && (date("Y-m-d") < $build_select['finish_date']) && (date("Y-m-d") > $build_select['start_date']))
{
?>
  <table class="table">
    <tr>
      <td colspan="3"  class="page_title">The following Teams are in need of additional players.</td>
    </tr>
    <tr>
      <th class="text-center">Grade</th>
      <th class="text-center">Day</th>
      <th class="text-center">Teams</th>       
    </tr>
    <?php 
    while($row_Need_Players = mysql_fetch_assoc($Need_Players))
    { 
    ?>
    <tr>
      <td nowrap="nowrap" class="text-center"><?php echo $row_Need_Players['team_grade']; ?></td>
      <td nowrap="nowrap" class="text-center"><?php echo $row_Need_Players['day_played']; ?></td>
      <td nowrap="nowrap" class="text-center"><?php echo $row_Need_Players['team_name']; ?></td>
    </tr>
    <?php 
    }
    ?>
    
    <tr>
      <td colspan="3"  class="page_title">Please contact the <a href='mailto:scores@vbsa.org.au'>Scores Registrar</a> if you are interested in playing.</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
  </table>  
<?php
}
?> 

<table class="table">
  <tr>
    <td colspan="7"  class="page_title">Snooker (Teams)</td>
  </tr>
  <tr>
    <th>Grade</th>
    <th class="text-center">Day</th>
    <th class="text-center">Teams</th>
    <th class="text-center">&nbsp;</th>                      
    <th class="text-center">Fixture</th>              
  </tr>
  <?php 
  if($totalRows_snook > 0)
  {
    do { ?>
    <tr>
      <td><?php echo $row_snook['new_grade']; ?></td>
      <td nowrap="nowrap" class="text-center"><?php echo $row_snook['day_played']; ?> </td>
      <td nowrap="nowrap" class="text-center"><?php echo $row_snook['teams']; ?></td>
      <td align="left" nowrap="nowrap"><a href="scores_results_ladders.php?grade=<?php echo $row_snook['new_grade']; ?>&amp; year=<?php echo $year ?>&amp; season=<?php echo $season ?>&amp;comptype=Snooker" class=" btn-sm btn-primary btn-responsive" role="button">Ladder</a></td>
      
    <?php // Show view or download links if fixture exists in the current year 
  	if ((isset($row_snook['fix_upload'])) && ($row_snook['fix_upload'] != ''))
  	{
  	   echo '<td nowrap=nowrap class="pdf_filename">' . $row_snook['fix_upload'] . '</td>';  
       $encoded_fix_upload = rawurlencode($row_snook['fix_upload']);
  	?>
      <!--<td class="text-center"><a href="../pdfjs/web/viewer.html?file=<?= $url ?>/fix_upload/<?= $encoded_fix_upload ?>" title="View"><span class="glyphicon glyphicon-eye-open"></span></a></td>-->
      <td class="text-center"><a href="" class="merge_view" title="Download"><span class="glyphicon glyphicon-eye-open"></a></td>
      <!--<td class="text-center"><button id="merge">Download</button><span class="glyphicon glyphicon-download"></a></td>-->
      <!--<div class='pdf_filename' value='<?= $encoded_fix_upload ?>'></div>
      <td class="text-center"><a href="#" id="merge" title="Download"><span id="merge" class="glyphicon glyphicon-download"></a></td>-->
      <td class="text-center"><a href="" class="merge" title="Download"><span class="glyphicon glyphicon-download"></a></td>
  <?php    
  	} 
    elseif ($row_snook['fix_cal_year'] <> date('Y') OR !isset($row_snook['fix_upload'])) 
    {
      echo '<td>'."Not Available".'</td>','<td>&nbsp;</td>'.'<td>&nbsp;</td>';
    } 
  ?>
    </tr>
    <?php } while ($row_snook = mysql_fetch_assoc($snook)); 
  }
  else
  {
     echo("<tr align=center><td colspan=5>No Data to display</td></tr>");
  }
  ?>
</table>  

<?php
// get all team grades
mysql_select_db($database_connvbsa, $connvbsa);
$sql = "Select distinct team_grade, type, dayplayed FROM tbl_fixtures where type = 'Snooker' and season = '". $season . "' and year = '$year' order by dayplayed, team_grade";
$result_fixture = mysql_query($sql, $connvbsa);
$row_no_of_players = $result_fixture->num_rows;
?>
<!--<table class="table">
  <tr>
    <td colspan="5"  class="page_title">Snooker (Singles)</td>
  </tr>
  <tr>
    <th>Grade</th>
    <th class="text-center">Day</th>
    <th class="text-center">Players</th>  
    <th>&nbsp;</th> 
    <th>&nbsp;</th>                   
  </tr>
  <?php 
  if($row_no_of_players > 0)
  {
    while($build_fixture = $result_fixture->fetch_assoc())
    { 
    ?>
    <tr>
      <td><?php echo $build_fixture['team_grade']; ?></td>
      <td nowrap="nowrap" class="text-center"><?php echo $build_fixture['dayplayed']; ?> </td>
      <td nowrap="nowrap" class="text-center">6</td>
      <td align="left" nowrap="nowrap"><a href="scores_pennant_singles_detail.php?grade=<?php echo $build_fixture['team_grade']; ?>&amp; year=<?php echo $year ?>&amp; season=<?php echo $season ?>&amp;comptype=Snooker&display=top" class=" btn-sm btn-primary btn-responsive" role="button">Top 6</a></td>
      <td align="left" nowrap="nowrap"><a href="scores_pennant_singles_detail.php?grade=<?php echo $build_fixture['team_grade']; ?>&amp; year=<?php echo $year ?>&amp; season=<?php echo $season ?>&amp;comptype=Snooker&display=all" class=" btn-sm btn-primary btn-responsive" role="button">Show All</a></td>
    </tr>
    <?php 
    }
  }
  else
  {
    echo("<tr><td align=center colspan=4>No Data to display</td></tr>");
  }
  ?>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>-->

<div class="table-responsive center-block" style="max-width:600px"> <!-- class table-responsive -->
  <table class="table">
    <tr>
      <td colspan="7"  class="page_title">Billiards (Teams)</td>
    </tr>
    <tr>
      <th>Grade</th>
      <th class="text-center">Day</th>
      <th class="text-center">Teams</th>
      <th>
      <th>Fixture              
      <th>              
      <th>
    </tr>
    <?php 
    if($totalRows_bill > 0)
    {
      do { 
      ?>
      <tr>
        <td><?php echo $row_bill['new_grade']; ?></td>
        <td nowrap="nowrap" class="text-center"><?php echo $row_bill['day_played']; ?> </td>
        <td nowrap="nowrap" class="text-center"><?php echo $row_bill['teams']; ?></td>
        <td align="left" nowrap="nowrap"><a href="scores_results_ladders.php?grade=<?php echo $row_bill['new_grade']; ?>&amp; year=<?php echo $year ?>&amp; season=<?php echo $season ?>&amp;comptype=Billiards" class=" btn-sm btn-primary btn-responsive" role="button">Ladder</a></td>
        
<?php // Show view or download links if fixture exists in the current year 
    if((isset($row_bill['fix_upload'])) && ($row_bill['fix_upload'] != ''))
    {
       echo '<td nowrap=nowrap class="pdf_filename">'.$row_bill['fix_upload'].'</td>';  
       $encoded_fix_upload = rawurlencode($row_bill['fix_upload']);
?>
      <!--<td class="text-center"><a href="../pdfjs/web/viewer.html?file=<?= $url ?>/fix_upload/<?= $encoded_fix_upload ?>" title="View"><span class="glyphicon glyphicon-eye-open"></span></a></td>-->
      <td class="text-center"><a href="" class="merge_view" title="Download"><span class="glyphicon glyphicon-eye-open"></a></td>
      <td class="text-center"><a href="" class="merge" title="Download"><span class="glyphicon glyphicon-download"></a></td>
      <!--<td class="text-center"><a href="../fix_upload/<?= $encoded_fix_upload ?>" title="Download"><span class="glyphicon glyphicon-download"></a></td>-->
<?php    
    } 
    elseif ($row_bill['fix_cal_year'] <> date('Y') OR !isset($row_bill['fix_upload'])) 
    {
      echo '<td>'."Not Available".'</td>','<td>&nbsp;</td>'.'<td>&nbsp;</td>';
    } 
?>
    </tr>
<?php } while ($row_bill = mysql_fetch_assoc($bill)); 
  }
  else
  {
     echo("<tr align=center><td colspan=5>No Data to display</td></tr>");
  }
?>
 </table>  
</div>

<?php

$tier_array = ['Tiers 5 and below', 'Tiers 6 and 7', 'Tiers 8 and 9', 'Tiers 10 and above'];
//$tier_array = ['Tiers 0, 1, 2, 3, 4 and 5', 'Tiers 6 and 7', 'Tiers 8 and 9', 'Tiers 10, 11 and 12'];

?>
<table class="table">
  <tr>
    <td colspan="2"  class="page_title">Billiard (Singles)</td>
  </tr>
  <tr>
    <td align='center'><b>Tier</b></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>                 
  </tr>
  <?php 
  for($i = 0; $i < count($tier_array); $i++)
  { 
  ?>
  <tr>
    <td align='center'><?php echo $tier_array[$i]; ?></td>
    <td align="center" nowrap="nowrap"><a href="scores_pennant_singles_detail.php?grade=<?php echo $tier_array[$i]; ?>&amp; year=<?php echo $year ?>&amp; season=<?php echo $season ?>&amp;comptype=Billiards&display=top" class=" btn-sm btn-primary btn-responsive" role="button">Top 8</a></td>
    <td align="center" nowrap="nowrap"><a href="scores_pennant_singles_detail.php?grade=<?php echo $tier_array[$i]; ?>&amp; year=<?php echo $year ?>&amp; season=<?php echo $season ?>&amp;comptype=Billiards&display=all" class=" btn-sm btn-primary btn-responsive" role="button">Show All</a></td>
  </tr>
  <?php 
  }
  ?> 
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>

<div class="table-responsive center-block" style="max-width:650px">
  <table class="table">
    <tr>
      <td align="center" class="page_title">Finals</td>
    </tr>
      <tr>
      <td>
          <p> Go to the Pennant Billiards post on the <a href="https://vbsa.org.au/VBSA_scores/scores_index.php">Scores Page</a> to view the Finals Schedule</p>
        </td>
    </tr>
  </table>
</div>

<div class="table-responsive center-block" style="max-width:650px"> <!-- class table-responsive -->
  <table class="table">
    <tr>
      <td align="center"><span  class="page_title"><?php echo $year; ?> <?php if($colname_bill=='S1') echo "Pennant"; else echo "Billiards & Willis" ?> Season  <?php echo $colname_bill; ?> Team Entries &nbsp;&nbsp; </span>Total Team entries <?php echo $totalRows_Teams ?></td>
    </tr>
    <tr>
      <td align="center"><p class="greybg">If your team is not listed here, IT WILL NOT BE INCLUDED IN THE DRAW. </p>
        <p class="greybg">If  any details, grade, name, day played are not correct please<br /> 
          <a href="mailto:scores@vbsa.org.au?subject=Scores%20Enquiry" >contact the score registrar</a></p></td>
    </tr>
  </table>
</div>

<div class="table-responsive center-block" style="max-width:650px"> <!-- class table-responsive -->
  <table class="table">
      <th>Club</th>
      <th>Team Name</th>
      <th class="text-center">Grade</th>
      <th class="text-center">Day Played</th>
      <th class="text-center">Players</th>
      <th class="text-center">&nbsp;</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_Teams['team_club']; ?></td>
        <td><?php echo $row_Teams['team_name']; ?></td>
        <td class="text-center"><?php echo $row_Teams['team_grade']; ?></td>
        <td class="text-center"><?php echo $row_Teams['day_played']; ?></td>
        <td class="text-center"><?php echo $row_Teams['players']; ?></td>
        <td nowrap="nowrap"><a href="scores_index_detail_players.php?team_id=<?php echo $row_Teams['team_id']; ?>" class=" btn-sm btn-primary btn-responsive" role="button">Listed Players</a></td>
      </tr>
      <?php } while ($row_Teams = mysql_fetch_assoc($Teams)); ?>
  </table>
</div>

</div>  <!-- close containing wrapper --> 

<script>

$(document).ready(function()
{
  // download and display pdf.
  $(".merge").on("click", async function (e) {
     e.preventDefault();
    // find the row that was clicked
    let row = $(this).closest("tr");
    // get the filename from the pdf_filename cell
    let filename = row.find(".pdf_filename").text();
    const { PDFDocument } = PDFLib;
    const pdfUrls = [
        "<?= $url ?>/fix_upload/Pennant_Dress_Code.pdf",
        "<?= $url ?>/fix_upload/Pennant_Rules.pdf",
        "<?= $url ?>/fix_upload/" + filename
    ];
    const mergedPdf = await PDFDocument.create();
    for (const url of pdfUrls) {
        // Fetch each PDF as ArrayBuffer
        const response = await fetch(url);
        if (!response.ok) {
            console.error("Failed to fetch", url, response.status);
            continue; // skip this file
        }
        const bytes = await response.arrayBuffer();
        const pdf = await PDFDocument.load(bytes);

        const pages = await mergedPdf.copyPages(pdf, pdf.getPageIndices());
        pages.forEach(p => mergedPdf.addPage(p));
    }
    const pdfBytes = await mergedPdf.save();
    const blob = new Blob([pdfBytes], { type: "application/pdf" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
  });

   $(".merge_view").on("click", async function (e) {
     e.preventDefault();
    // find the row that was clicked
    let row = $(this).closest("tr");
    // get the filename from the pdf_filename cell
    let filename = row.find(".pdf_filename").text();
    const { PDFDocument } = PDFLib;
    const pdfUrls = [
        "<?= $url ?>/fix_upload/Pennant_Dress_Code.pdf",
        "<?= $url ?>/fix_upload/Pennant_Rules.pdf",
        "<?= $url ?>/fix_upload/" + filename
    ];
    const mergedPdf = await PDFDocument.create();
    for (const url of pdfUrls) {
        // Fetch each PDF as ArrayBuffer
        const response = await fetch(url);
        if (!response.ok) {
            console.error("Failed to fetch", url, response.status);
            continue; // skip this file
        }
        const bytes = await response.arrayBuffer();
        const pdf = await PDFDocument.load(bytes);

        const pages = await mergedPdf.copyPages(pdf, pdf.getPageIndices());
        pages.forEach(p => mergedPdf.addPage(p));
    }
    const pdfBytes = await mergedPdf.save();
    const blob = new Blob([pdfBytes], { type: "application/pdf" });
    const blobUrl = URL.createObjectURL(blob);
    window.open('../pdfjs/web/viewer.html?file=' + blobUrl, '_blank');
  });
});

</script>

</div>

</body>
</html>

