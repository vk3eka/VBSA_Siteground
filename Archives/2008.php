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
 		<div class="page_title">Archives for 2008</div>
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
          <td align="center"><a href="arch_reports_pdf/08 Pennant Finals info.pdf" target="_blank">Finals Results </a><a href="arch_reports_pdf/07 Finals Pennant.pdf" target="_blank"></a><a href="arch_reports_pdf/06PennantFinalResult.pdf" target="_blank"></a></td>
          <td colspan="2" align="center"><a href="arch_reports_pdf/08 VBSA res 18.pdf" target="_blank">Final Ladders &amp; Ind. Results</a><a href="arch_reports_pdf/07 Pennant Scores.pdf" target="_blank"></a></td>
        </tr>
        <tr>
            <th>Grade</th>
            <th>Winners</th>
            <th>Runners-up</th>
        </tr>
            <tr>
            <td>A Premier</td>
            <td>Brunswick</td>
            <td>Princes Blue</td>
      </tr>
            <tr>
            <td>B Premier</td>
            <td>Brunswick White</td>
            <td>Fast Eddies</td>
      </tr>
            <tr>
            <td>B Reserve</td>
            <td>Fast Eddies</td>
            <td>Cheltenham</td>
      </tr>
            <tr>
              <td>C Premier</td>
              <td>Princes Tigers</td>
              <td>Dandenong Club</td>
      </tr>
            <tr>
              <td>C Reserve</td>
              <td>Princes Blue</td>
              <td>Dandenong Club</td>
      </tr>
            <tr>
              <td>D Premier</td>
              <td>Maccabi</td>
              <td>Kings Legends</td>
            </tr>
            <tr>
              <td>D Reserve</td>
              <td>Kings Knights</td>
              <td>Bentleigh</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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
          <td align="center"><a href="arch_reports_pdf/08 B&amp;W Finals.pdf" target="_blank">Finals Results </a><a href="arch_reports_pdf/06BWFinalsfixRes.pdf" target="_blank"></a></td>
          <td colspan="2" align="center"><a href="arch_reports_pdf/08 BW Scores 15.pdf" target="_blank">Final Ladders &amp; Ind. Results</a><a href="2007/07 B &amp; W Scores.pdf" target="_blank"></a></td>
        </tr>
        <tr>
            <th>Grade</th>
            <th>Winners</th>
            <th>Runners-up</th>
        </tr>
            <tr>
            <td>A Billiards</td>
            <td>Cheltenham</td>
            <td>Brunswick</td>
      </tr>
            <tr>
            <td>B Billiards</td>
            <td>Kings</td>
            <td>Geelong</td>
      </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
              <td>A Willis</td>
            <td>Brunswick White</td>
            <td>Princes Blue</td>
      </tr>
            <tr>
              <td>B Willis</td>
            <td>Fast Eddies</td>
            <td>Princes Cats</td>
      </tr>
            <tr>
              <td>B Reserve</td>
              <td>Brunswick</td>
              <td>Kings Dolphins</td>
            </tr>
            <tr>
              <td>C Willis</td>
              <td>Maccabi</td>
              <td>Bentleigh</td>
            </tr>
            <tr>
              <td>C Reserve</td>
              <td>Yarraville Gold</td>
              <td>Fast Eddies</td>
            </tr>
            <tr>
              <td>D Willis</td>
              <td>Maccabi Unites</td>
              <td>Kings Rockets</td>
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
        <th>Tournament</th>
        <th>Winner</th>
        <th>Runner Up</th>
      </tr>
      <tr>
        <td>Robby Foldvari Handicap Snooker</td>
        <td>Michael D'Silva</td>
        <td>Roger Dalitz</td>
      </tr>
      <tr>
        <td>Victorian Snooker Championship </td>
        <td>James Mifsud</td>
        <td>Steve Mifsud</td>
      </tr>
      <tr>
        <td>Victorian Ladies Snooker Championship </td>
        <td>Margaret Gorski</td>
        <td>Samantha Ridley</td>
      </tr>
      <tr>
        <td>Victorian Under 21 Snooker Championship </td>
        <td>Daniel Faddoul</td>
        <td>Charlie Chafe</td>
      </tr>
      <tr>
        <td>Victorian Under 18 Snooker Championship </td>
        <td>Daniel Faddoul</td>
        <td>Kurt Dunham</td>
      </tr>
      <tr>
        <td>Victorian Snooker Under 15 Championship </td>
        <td>Charlie Chafe</td>
        <td>Jake Boyle</td>
      </tr>
      <tr>
        <td>Victorian Snooker Under 12 Championship </td>
        <td>Andrew Lang</td>
        <td></td>
      </tr>
      <tr>
        <td>Victorian Pot Black Snooker</td>
        <td>Aaron Mahoney</td>
        <td>James Mifsud</td>
      </tr>
      <tr>
        <td>Kings Coca Cola Cup </td>
        <td>Neil Robertson</td>
        <td>Aaron Mahoney</td>
      </tr>
      <tr>
        <td>Gary Cullen Handicap Snooker</td>
        <td>Greame Wright</td>
        <td>Michael D'Silva</td>
      </tr>
      <tr>
        <td> Lance Pannell Snooker Classic</td>
        <td>James Mifsud</td>
        <td>Aaron Mahoney</td>
      </tr>
      <tr>
        <td>John Mc Kay Handicap Snooker</td>
        <td>David Deng</td>
        <td>Kurt Dunham</td>
      </tr>
      <tr>
        <td>SS&amp;A Minor Classic Snooker</td>
        <td>Michael Lupton</td>
        <td>Brad Harris</td>
      </tr>
      <tr>
        <td> Fred Osbourne Memorial Snooker</td>
        <td>Shawn Budd</td>
        <td>Steve Mifsud</td>
      </tr>
      <tr>
        <td>Victorian Womens Billiard Championship</td>
        <td>Anna Lynch</td>
        <td>Tessa Russell</td>
      </tr>
      <tr>
        <td>Princes Coca Cola Cup Snooker</td>
        <td>Rob Foldvari</td>
        <td>Daniel Thorp</td>
      </tr>
      <tr>
        <td>George Ganim Handicap Snooker</td>
        <td>Shaun Dalitz</td>
        <td>Charlie Chafe</td>
      </tr>
      <tr>
        <td>City of Melbourne Snooker Championship</td>
        <td>Neil Robertson</td>
        <td>Rudy Sulaeman</td>
      </tr>
      <tr>
        <td>Victorian Billiard Championship</td>
        <td>Steve Mifsud</td>
        <td>John Schenck</td>
      </tr>
      <tr>
        <td>Victorian Minor Billiard Championship</td>
        <td>Jason Colebrook</td>
        <td>Brian Moulday</td>
      </tr>
      <tr>
        <td>Handicap Series Invitational Playoff </td>
        <td>Charlie Chafe</td>
        <td>David Deng</td>
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