<?php 

include('server_name.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Score Sheet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link href="https://vbsa.org.au/CSS/VBSA_base.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>-->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  
<script type="text/javascript">
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' should be 5.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
</script>
  
</head>
<body id="vbsa">
<!-- Include Google Tracking -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-7874393-1', 'auto');
  ga('send', 'pageview');
</script>
<div class="container"> 
    <!-- Include header -->
<div class="visible-md visible-lg" style="margin-left:5%; margin-right:5%">
  <h1 style="text-align: center; padding-bottom: 10px">Victorian Billiards & Snooker Association Inc.</h1> 
  <h4 style="color:#900; text-align: center; padding-bottom: 10px;">Representing & Developing Billiards &amp; Snooker as a sport in Victoria</h4> 
  <!--<h6 style="text-align: center; padding-bottom: 10px">VBSA Patron: The Honourable Linda Dessau AC, Governor of Victoria.  </h6>-->   
</div>  
<div class="visible-sm visible-xs"style="margin-left:5%; margin-right:5%">
  <h1 style="text-align: center; padding-bottom: 10px">VBSA</h1> 
  <h6 style="text-align: center; padding-bottom: 10px">(Victorian Billiards & Snooker Association Inc.)</h6>
  <!--<h6 style="text-align: center; padding-bottom: 10px">VBSA Patron: The Honourable Linda Dessau AC, Governor of Victoria.  </h6>-->    
  <h5 style="color:#900; text-align: center; padding-bottom: 10px">Representing & Developing Billiards &amp; Snooker as a sport in Victoria</h5> 
</div> 
<!-- Include navigation -->
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
    <ul class="nav navbar-nav">
    <!-- Home -->
      <li class="home"><a href="http://www.vbsa.org.au/index.php">Home </a> </li>
        
      <!-- VBSA --> 
      <li class="info"><a class="dropdown-toggle" data-toggle="dropdown" href="#">VBSA<span class="caret"></span></a>
          <ul class="dropdown-menu">
              <li><a href="http://www.vbsa.org.au/VBSA_scores/scores_index.php">VBSA Scores</a></li>
                <li><a href="http://www.vbsa.org.au/Club_dir/club_index.php">Victorian Clubs</a></li>
              <li><a href="http://www.vbsa.org.au/calendar/cal_index.php">Victorian Calendar</a></li>
                <li><a href="http://www.vbsa.org.au/Rankings/rankings_index.php">Victorian Rankings</a></li>
                <li><a href="http://www.vbsa.org.au/Tournaments/tourn_index.php">Tournament Entries &amp; Conditions</a></li>
                <li><a href="http://www.vbsa.org.au/CityClubs/CC_index.php">VBSA City Clubs</a></li>
                <li><a href="http://www.vbsa.org.au/VBSA/vbsa_contact.php">VBSA Administration / Contact</a></li>
                <li><a href="http://www.vbsa.org.au/VBSA/vbsa_about.php">VBSA About</a></li>
                <li><a href="http://www.vbsa.org.au/VBSA/vbsa_pol_proc.php">VBSA Policies & Procedures</a></li>
                <li><a href="http://www.vbsa.org.au/Archives/ArchiveIndex.php">VBSA History (Archives)</a></li>
                <li><a href="http://www.vbsa.org.au/vbsa_shop/shop_cart.php">VBSA Shop (payments, enter t'ments)</a></li>
          </ul>
        </li>
        <!-- Affiliates -->
        <li class="affiliates"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Affiliate<span class="caret"></span></a>
          <ul class="dropdown-menu">
              <li><a href="http://www.vbsa.org.au/BBSA/BBSA_index.php">Ballarat BSA</a></li>
                <li><a href="http://www.vbsa.org.au/BendBSA/BendBSA_index.php">Bendigo BSA</a></li>
                <li><a href="http://www.vbsa.org.au/DVSA/DVSA_index.php">DVSA</a></li>
                <li><a href="http://www.vbsa.org.au/MSBA/MSBA_index.php">MSBA</a></li>
                <li><a href="http://www.vbsa.org.au/O55/O55_index.php">Over 55's</a></li>
                <li><a href="http://www.vbsa.org.au/RSL/RSL_index.php">RSL</a></li>
                <li><a href="http://www.vbsa.org.au/Southern/SBSA_index.php">Southern</a></li>
                <li><a href="http://www.vbsa.org.au/WSBSA/WSBSA_index.php">Western Suburbs BSA</a></li>
          </ul>
        </li>
        <!-- Juniors -->
        <li class="junior"><a href="http://www.vbsa.org.au/Juniors/Junior_Index.php">Junior</a></li>
        <!-- Womens -->
        <li class="women"><a href="http://www.vbsa.org.au/Womens/Womens_Index.php">Women</a></li>
        <!-- Coaching -->
        <li class="coaching"><a href="http://www.vbsa.org.au/VBSA/accredited_coaches.php">Coaching</a></li>
        <!-- Referees -->
        <li referees class="referees"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Referees<span class="caret"></span></a>
          <ul class="dropdown-menu">
              <li><a href="http://www.vbsa.org.au/Referees/referee_index.php">Referee Information</a></li>
              <li><a href="http://www.vbsa.org.au/Referees/referee_posers.php">Referee Q & A</a></li>
              <li><a href="http://www.vbsa.org.au/Referees/referee_profile.php">Referee Profiles</a></li>
              <li><a href="http://www.vbsa.org.au/Referees/referee_contact.php">Referees Contact / list of qualified</a></li>
          </ul>
        </li>
        <!-- Tournaments -->
        <li class="info"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Info</a>
          <ul class="dropdown-menu">
              <li><a href="http://www.vbsa.org.au/VBSA_Help/VBSA_Help.php">Help</a></li>
              <li><a href="http://www.vbsa.org.au/Links.php">Links</a></li>
              <li><a href="http://www.vbsa.org.au/VBSA/vbsa_contact.php">VBSA Administration / Contact</a></li>
            </ul>
        </li> 
      </ul>
      <div class="pull-right hidden-xs" style="margin-top:5px">
        <a href="http://www.facebook.com/pages/Victorian-Billiards-Snooker-Association-VBSA/170438026331018?ref=tn_tnmn" target="_blank"><img src="http://www.vbsa.org.au/images_2016/facebook.fw.png" class="img-responsive" title="Visit the VBSA facebook page"/></a>
      </div>
      
      <div class="pull-left visible-xs" style="margin-bottom:5px; margin-left:5px">
        <a href="http://www.facebook.com/pages/Victorian-Billiards-Snooker-Association-VBSA/170438026331018?ref=tn_tnmn" target="_blank"><img src="http://www.vbsa.org.au/images_2016/facebook_lg.fw.png" class="img-responsive" title="Visit the VBSA facebook page"/></a>
      </div>
    </div>
  </div>
</nav>
</div><!--End Bootstrap Container--> 

<div id="Wrapper"><!-- Wrapper contains content to a max width of 1200px--> 

  
