<?php
$currentPage = $_SERVER["PHP_SELF"];

$queryString_FPitems = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_FPitems") == false && 
        stristr($param, "totalRows_FPitems") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_FPitems = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_FPitems = sprintf("&totalRows_FPitems=%d%s", $totalRows_FPitems, $queryString_FPitems);

;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Archives</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="../CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>
<body id="vbsa">

   <!-- Include Google Tracking -->
<?php include_once("includes/analyticstracking.php") ?>

<div class="container"> 

    <!-- Include header -->
<?php include '../includes/header.php';?>
    
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>
    
  </div>  

</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  <!--Content--> 
  
  
  <!--Left--> 
  
  <div class="row"> 
  <div class="Page_heading_container">
 		<div class="page_title">Archives for 2006</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
<!-- Include link to previous page -->
<?php include '../includes/prev_page.php';?>
    
  <div class="table-responsive center-block" style="max-width:700px; font-style:italic"> <!-- class table-responsive -->
    <table class="table" style=" text-align:left">
        <tr>
            <td colspan="3" align="center" class="table_header">Snooker Season</td>
        </tr>
        <tr>
          <td colspan="3" align="center"><a href="arch_reports_pdf/06PennantFinalResult.pdf" target="_blank">Finals Results </a></td>
        </tr>
        <tr>
            <th>Grade</th>
            <th>Winners</th>
            <th>Runners-up</th>
        </tr>
            <tr>
            <td>A Premier</td>
            <td>Cheltenham</td>
            <td>Brunswick</td>
      </tr>
            <tr>
            <td>B Premier</td>
            <td>Fast Eddies</td>
            <td>Fast Eddies</td>
      </tr>
            <tr>
            <td>B Reserve</td>
            <td>Kings Crown</td>
            <td>Princes Knights</td>
      </tr>
            <tr>
              <td>C Premier</td>
              <td>Fast Eddies Gold</td>
              <td>Fast Eddies Green</td>
      </tr>
            <tr>
              <td>C Reserve</td>
              <td>Princes Dragons</td>
              <td>Dandenong Club</td>
      </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
</table>
</div>

  <div class="table-responsive center-block" style="max-width:700px; font-style:italic"> <!-- class table-responsive -->
    <table class="table" style=" text-align:left">
        <tr>
            <td colspan="3" align="center" class="table_header">Biliards &amp; Willis Season</td>
        </tr>
        <tr>
          <td colspan="3" align="center"><a href="arch_reports_pdf/06BWFinalsfixRes.pdf" target="_blank">Finals Results </a></td>
        </tr>
        <tr>
            <th>Grade</th>
            <th>Winners</th>
            <th>Runners-up</th>
        </tr>
            <tr>
            <td>A Billiards</td>
            <td>Yarraville</td>
            <td>Cheltenham</td>
      </tr>
            <tr>
            <td>B Billiards</td>
            <td>Geelong</td>
            <td>Cheltenham</td>
      </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
              <td>A Willis</td>
            <td>Princes Blue</td>
            <td>Dandenong RSL</td>
      </tr>
            <tr>
              <td>B Willis</td>
            <td>Princes Dragons</td>
            <td>Dandenong Club</td>
      </tr>
            <tr>
              <td>B Reserve</td>
              <td>Princes Red</td>
              <td>Brunswick Black</td>
            </tr>
            <tr>
              <td>C Willis</td>
              <td>Maccabi</td>
              <td>Princes Knights</td>
            </tr>
            <tr>
              <td>C Reserve</td>
              <td>Yarraville Gold</td>
              <td>Princes Sharks</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
</table>
</div>
<div class="table-responsive center-block" style="max-width:700px; font-style:italic"> <!-- class table-responsive -->
    <table class="table" style=" text-align:left">
      <tr>
        <td colspan="3"  class="table_header" align="center">Tournament Results</td>
      </tr>
      <tr>
        <td><strong>Tournament</strong></td>
        <td><strong>Winner</strong></td>
        <td><strong>Runner Up</strong></td>
      </tr>
      <tr>
        <td>Robby Foldvari Handicap Snooker</td>
        <td>Marco Chan</td>
        <td>Paul Thomerson</td>
      </tr>
      <tr>
        <td>Victorian Snooker Championship</td>
        <td>Aaron Mahoney</td>
        <td>Rudy Sulaiman</td>
      </tr>
      <tr>
        <td>Victorian Ladies Snooker Championship</td>
        <td>Kathy Howden</td>
        <td>Samantha Ridley</td>
      </tr>
      <tr>
        <td>Victorian Under 21 Snooker Championship</td>
        <td>Eing Hou Lee</td>
        <td>James O'Reilly</td>
      </tr>
      <tr>
        <td>Victorian Under 18 Snooker Championship</td>
        <td>Daniel Faddoul</td>
        <td>Patrick Hood</td>
      </tr>
      <tr>
        <td>Victorian Snooker Under 15 Championship</td>
        <td>Daniel Faddoul</td>
        <td>Ryan Thomerson</td>
      </tr>
      <tr>
        <td>Victorian Snooker Under 12 Championship</td>
        <td>Ryan Thomerson</td>
        <td>Adam Bluemink</td>
      </tr>
      <tr>
        <td>Kings Coca Cola Cup</td>
        <td>Neil Robertson</td>
        <td>Steve Mifsud</td>
      </tr>
      <tr>
        <td>Gary Cullen Handicap Snooker</td>
        <td>Marco Chan</td>
        <td>George Spiteri</td>
      </tr>
      <tr>
        <td>Lance Pannell Snooker Classic</td>
        <td>Steve Mifsud</td>
        <td>James Mifsud</td>
      </tr>
      <tr>
        <td>Harry Andrews A Grade Cup</td>
        <td>Brunswick</td>
        <td>Princes</td>
      </tr>
      <tr>
        <td>Fred Osbourne Memorial Snooker</td>
        <td>James Mifsud</td>
        <td>Glen Wilkinson</td>
      </tr>
      <tr>
        <td>Victorian Womens Billiard Championship&gt;</td>
        <td>Anna Lynch</td>
        <td>Tessa Russell</td>
      </tr>
      <tr>
        <td>Princes Coca Cola Cup Snooker</td>
        <td>Aaron Mahoney</td>
        <td>Steve Mifsud</td>
      </tr>
      <tr>
        <td>Victorian Under 21 Billiard Championship</td>
        <td>Not Run</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>George Ganim Handicap Snooker</td>
        <td>Charles Lowe</td>
        <td>Eing Hou Lee</td>
      </tr>
      <tr>
        <td>City of Melbourne Snooker Championship</td>
        <td>Adrian Ridley</td>
        <td>Rob Foldvari</td>
      </tr>
      <tr>
        <td>Victorian Billiard Championship</td>
        <td>David Collins</td>
        <td>Steve Mifsud</td>
      </tr>
      <tr>
        <td>Victorian Minor Billiard Championship</td>
        <td>Ian Gilbee</td>
        <td>Terry Rushford</td>
      </tr>
      <tr>
        <td>Handicap Series Invitational Playoff</td>
        <td>Paul Dixon</td>
        <td>Charlie Lowe</td>
      </tr>
      <tr>
        <td>John Mc Kay Sheild - Willis Super League</td>
        <td>Steve Ebejer</td>
        <td>Aaron Mahoney</td>
      </tr>
    </table>
  </div>

  
  <!-- Footer -->  
  <div class="table-condensed center-block" style="max-width:800px"> 
        <table class="table">
          <tr>
            <td align="center">If you have information that is not currently available please contact <a href="mailto:scores@vbsa.org.au">scores@vbsa.org.au</a></td>
          </tr>
        </table>
  </div>
  
  
</div><!-- close conraineing wrapper -->   
 
</body>
</html>