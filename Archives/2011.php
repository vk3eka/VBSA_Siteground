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
 		<div class="page_title">Archives for 2011</div>
  </div>  	
    
  <div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div> 
  </div>
  
 
    <div class="table-condensed">
        <table align="center">
          <tr>
            <td style="padding-bottom:10px"><input type="button" class="btn-xs btn-default btn-responsive center-block" role="button" value="Return to previous page" onclick="window.history.go(-1); return false;"/></td>
          </tr>
        </table>
    </div>
    
  <div class="table-responsive center-block" style="max-width:700px; font-style:italic"> <!-- class table-responsive -->
    <table class="table" style=" text-align:left">
        <tr>
            <td colspan="3" align="center" class="table_header">Snooker Season</td>
        </tr>
        <tr>
          <td align="center"><a href="arch_reports_pdf/2011_S1_finals.pdf" target="_blank">Final Results</a></td>
          <td colspan="2" align="center"><a href="arch_reports_pdf/2011_S1_ladders.pdf" target="_blank">Final Ladders &amp; Ind. Results</a></td>
        </tr>
        <tr>
            <th>Grade</th>
            <th>Winners</th>
            <th>Runners-up</th>
        </tr>
            <tr>
            <td>A Premier</td>
            <td>Masters</td>
            <td>Yarraville</td>
      </tr>
            <tr>
              <td>A Reserve</td>
              <td>Princes</td>
              <td>AK8 United</td>
            </tr>
            <tr>
            <td>B Premier</td>
            <td>Geelong</td>
            <td>Princes Athletico</td>
      </tr>
            <tr>
            <td>B Reserve</td>
            <td>Cheltenham</td>
            <td>Princes White</td>
      </tr>
            <tr>
              <td>C Premier</td>
              <td>Kings &amp; Queens</td>
              <td>Dandenong Club</td>
      </tr>
            <tr>
              <td>C Reserve</td>
              <td>Yarraville Gold</td>
              <td>RACV Blue Balls</td>
      </tr>
            <tr>
              <td>D Premier</td>
              <td>Kings Devils</td>
              <td>Maccabi Gold</td>
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
          <td align="center"><a href="arch_reports_pdf/2011_S2_finals.pdf" target="_blank">Final Results</a></td>
          <td colspan="2" align="center"><a href="arch_reports_pdf/2011_S2_ladders.pdf" target="_blank">Final Ladders &amp; Ind. Results</a></td>
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
              <td>B Billiards Singles</td>
              <td>Not Run</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
            <td>A Willis</td>
            <td>Princes Black</td>
            <td>Princes</td>
      </tr>
            <tr>
              <td>B Willis</td>
            <td>Princes Black</td>
            <td>AK8</td>
      </tr>
            <tr>
              <td>B Reserve</td>
            <td>Princes Blue</td>
            <td>Yarraville Gold</td>
      </tr>
            <tr>
              <td>C Willis</td>
              <td>Kings Kong</td>
              <td>Yarraville Gold</td>
            </tr>
            <tr>
              <td>C Reserve</td>
              <td>Box Hill Goats</td>
              <td>Bentleigh</td>
            </tr>
            <tr>
              <td>D Reserve</td>
              <td>Kings Feds</td>
              <td>Kings Devils</td>
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
        <td>Robby Foldvari Handicap  Snooker</td>
        <td>Ryan Thomerson</td>
        <td>Joe Sacco</td>
      </tr>
      <tr>
        <td>Victorian Snooker Championship </td>
        <td>Aaron Mahoney</td>
        <td>Adrian Ridley</td>
      </tr>
      <tr>
        <td>Victorian Under 21 Snooker Championship </td>
        <td>No Results</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Victorian Under 18 Snooker Championship </td>
        <td>No Results</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Victorian Snooker Under 15 Championship </td>
        <td>No Results</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Victorian Snooker Under 12 Championship </td>
        <td>No Results</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Kings Coca Cola Cup </td>
        <td>Not Run</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Gary Cullen Handicap Snooker</td>
        <td>Ron Calender</td>
        <td>Adam Bleumink</td>
      </tr>
      <tr>
        <td>Lance Pannell Snooker Classic</td>
        <td>Aaron Mahoney</td>
        <td>James Mifsud</td>
      </tr>
      <tr>
        <td>John Mc Kay Handicap Snooker</td>
        <td>Tony Masci</td>
        <td>Sompop Koschasan</td>
      </tr>
      <tr>
        <td>Fred Osbourne Memorial Snooker</td>
        <td>James Mifsud</td>
        <td>Paul Balzer</td>
      </tr>
      <tr>
        <td>Victorian Womens Billiard Championship</td>
        <td>Anna Lynch</td>
        <td>Kathy Howden</td>
      </tr>
      <tr>
        <td>Victorian Womens 147 Snooker Championship</td>
        <td>Marg Gorski</td>
        <td>Denise Hu</td>
      </tr>
      <tr>
        <td>Victorian Womens Snooker Championship</td>
        <td>Marg Gorski</td>
        <td>Lyndal Ellement</td>
      </tr>
      <tr>
        <td>Princes Coca Cola Cup Snooker</td>
        <td>Vinnie Calabrese</td>
        <td>James Mifsud</td>
      </tr>
      <tr>
        <td>George Ganim Handicap Snooker</td>
        <td>Ekky Lakthong</td>
        <td>Richard Ball</td>
      </tr>
      <tr>
        <td>City of Melbourne Snooker Championship</td>
        <td>James Mifsud</td>
        <td>Shaun Budd</td>
      </tr>
      <tr>
        <td>Victorian Billiard Championship</td>
        <td>Steve Mifsud</td>
        <td>John Schenck</td>
      </tr>
      <tr>
        <td>Victorian Minor Billiard Championship</td>
        <td>Ian Gilbee</td>
        <td>Ron Cole</td>
      </tr>
      <tr>
        <td>Victorian 150 Up Billiard Championship</td>
        <td>Steve Mifsud</td>
        <td>David Collins</td>
      </tr>
      <tr>
        <td>Handicap Series Invitational Playoff </td>
        <td>Ekky Lakthong</td>
        <td>Ron Calender</td>
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