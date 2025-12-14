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
 		<div class="page_title">Archives for 1995</div>
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
            <th>Grade</th>
            <th>Winners</th>
            <th>Runners-up</th>
        </tr>
            <tr>
            <td>A Premier</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
            <td>A Reserve</td>
            <td>Brunswick</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
            <td>B Premier</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
            <td>B Reserve</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
            <td>C East</td>
            <td>Cheltenham</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
            <td>C West</td>
            <td>Melton</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
            <td>D East</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
            <td>D West</td>
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
            <th>Grade</th>
            <th>Winners</th>
            <th>Runners-up</th>
        </tr>
            <tr>
            <td>A Billiards</td>
            <td>Yarraville</td>
            <td>Kooyong</td>
      </tr>
            <tr>
            <td>B Billiards</td>
            <td>Bentleigh </td>
            <td>Princes</td>
      </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
            <tr>
            <td>Willis A</td>
            <td>Brunswick </td>
            <td>Melton</td>
      </tr>
            <tr>
            <td>Wilis A Reserve</td>
            <td>RACV</td>
            <td>Melton</td>
      </tr>
            <tr>
            <td>Willis B</td>
            <td>Bentleigh</td>
            <td>Princes</td>
      </tr>
            <tr>
            <td>Wilis B Reserve</td>
            <td>Cheltenham</td>
            <td>Dandenong WSC</td>
      </tr>
            <tr>
            <td>Willis C</td>
            <td>Bentleigh</td>
            <td>Dandenong Club</td>
      </tr>
            <tr>
            <td>Wilis C Reserve</td>
            <td>Princes </td>
            <td>The Cue</td>
      </tr>
            <tr>
            <td>Willis D East</td>
            <td>Classic Cue</td>
            <td>Knox</td>
      </tr>
            <tr>
            <td>Willis D West</td>
            <td>Princes</td>
            <td>Footscray RSL</td>
      </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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