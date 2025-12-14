<?php

if(($_SERVER['HTTP_HOST'] == 'localhost') OR ($_SERVER['HTTP_HOST'] == '172.16.10.32'))
{
  $url = "http://172.16.10.32/VBSA_Siteground";
  $test_menu = '
       <li class="info"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin Testing<span class="caret"></span></a>
       <ul class="dropdown-menu">
              <li><a href="' . $url . '/vbsa_online_scores/index.php">Scoresheet Login</a></li>
              <li><a href="' . $url . '/VBSA_Admin_Login.php">Admin Portal</a></li>
              <li><a href="' . $url . '/Admin_Tournaments/tournament_draw_template.php?tourn_id=202473">Torunament Test</a></li>
          </ul>
        </li>';
}
else
{
  $url = "https://vbsa.org.au";
  //$url = "http://vbsa.cpc-world.com";
  $test_menu = '<li class="info"><a href="' . $url . '/vbsa_online_scores/index.php">Scoresheet Log In</a> </li>'; 
}

?>
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
    	<li class="home"><a href="<?= $url ?>/index.php">Home </a> </li>
        
	<!-- VBSA -->	
    	<li class="vbsa"><a class="dropdown-toggle" data-toggle="dropdown" href="#">VBSA<span class="caret"></span></a>
        	<ul class="dropdown-menu">
            	<li><a href="<?= $url ?>/VBSA_scores/scores_index.php">Pennant Scores</a></li>
              <li><a href="<?= $url ?>/galleries/VBSA_team_photo_index.php">Pennant Grand Finals Photos</a></li>
              <li><a href="<?= $url ?>/Club_dir/club_index.php">Clubs</a></li>
          	  <li><a href="<?= $url ?>/calendar/cal_index.php">Calendar</a></li>
              <li><a href="<?= $url ?>/PreviousRank/rankings_index.php">Rankings</a></li>
              <li><a href="<?= $url ?>/Tournaments/tourn_index.php">Tournament Entries &amp; Conditions</a></li>
              <li><a href="<?= $url ?>/Admin_Tournaments/tournament_draw_public.php?tourn_id=202473">Tournament Draw Test</a></li>
              <li><a href="<?= $url ?>/CityClubs/CC_index.php">City Clubs</a></li>
              <li><a href="<?= $url ?>/VBSA/vbsa_contact.php">Contact Us</a></li>
              <li><a href="<?= $url ?>/VBSA/vbsa_about.php">About</a></li>
              <li><a href="<?= $url ?>/VBSA/vbsa_pol_proc.php">Policies & Procedures</a></li>
              <li><a href="<?= $url ?>/Archives/ArchiveIndex.php">Competition Results</a></li>
              <li><a href="<?= $url ?>/VBSA_Help/VBSA_Help.php">Help</a></li>
            	<li><a href="<?= $url ?>/Links.php">Links</a></li>
              <li><a href="<?= $url ?>/vbsa_shop/shop_cart.php">Shop (payments, enter t'ments)</a></li>
        	</ul>
        </li>
        <li class="membership"><a href="<?= $url ?>/Admin_DB_VBSA/membership_application_online.php">Join the VBSA </a> </li>

  <!-- Events -->
        <li class="Calendar"><a href="<?= $url ?>/calendar/cal_index.php">Calendar</a></li>

     <!-- Rankings -->
       <li><a href="<?= $url ?>/PreviousRank/rankings_index.php">Rankings</a></li>

     <!-- Affiliates -->
        <li class="affiliates"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Affiliate<span class="caret"></span></a>
        	<ul class="dropdown-menu">
            	<li><a href="<?= $url ?>/BBSA/BBSA_index.php">Ballarat BSA</a></li>
              <li><a href="<?= $url ?>/BendBSA/BendBSA_index.php">Bendigo BSA</a></li>
            	<li><a href="<?= $url ?>/DVSA/DVSA_index.php">DVSA</a></li>
              <li><a href="<?= $url ?>/MSBA/MSBA_index.php">MSBA</a></li>
              <li><a href="<?= $url ?>/O55/O55_index.php">Over 55's</a></li>
              <li><a href="<?= $url ?>/RSL/RSL_index.php">RSL</a></li>
              <li><a href="<?= $url ?>/Southern/SBSA_index.php">Southern</a></li>
              <li><a href="<?= $url ?>/WSBSA/WSBSA_index.php">Western Suburbs BSA</a></li>
        	</ul>
        </li>
     <!-- Juniors -->
        <li class="junior"><a href="<?= $url ?>/Juniors/Junior_Index.php">Junior</a></li>
     <!-- Womens -->
        <li class="women"><a href="<?= $url ?>/Womens/Womens_Index.php">Women</a></li>
        <!-- Coaching -->
        <li class="coaching"><a href="<?= $url ?>/VBSA/accredited_coaches.php">Coaching</a></li>
     <!-- Referees -->
        <li class="referees"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Referees<span class="caret"></span></a>
        	<ul class="dropdown-menu">
            	<li><a href="<?= $url ?>/Referees/referee_index.php">Referee Information</a></li>
            	<li><a href="<?= $url ?>/Referees/referee_posers.php">Referee Q & A</a></li>
              <li><a href="<?= $url ?>/Referees/referee_contact.php">Qualified Referees List</a></li>
        	</ul>
        </li>
    <!-- Test Menus -->
      <?= $test_menu ?>
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