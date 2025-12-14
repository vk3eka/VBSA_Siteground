<?php
date_default_timezone_set("Australia/Melbourne");

?>
<?php //$url = "http://vbsa.org.au/"; ?>
<?php //$url = "http://vbsa.cpc-world.com/"; ?>
<?php $url = "http://172.16.10.32/VBSA_Siteground/"; ?>
<?php //$url = "http://localhost/VBSA_Siteground/";

?><div id="DBheader"></div>

<table width="1200" border="0" align="center" class="greenbg">
  <tr>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td>
    <td align="center" >&nbsp;</td> 
  </tr>
  <tr>
  	<td align="center" ><a href="<?= $url ?>Admin_DB_VBSA/vbsa_logout.php">Logout</a></td>
    <td align="center" ><a href="<?= $url ?>vbsa_online_scores/Admin_change_pwd.php">Change Password</a></td>
    <td align="center" ><a href="<?= $url ?>Admin_DB_VBSA/A_memb_index.php">Members</a></td>
    <td align="center" ><a href="<?= $url ?>Admin_ZZ_archives/AA_Archive_index.php">Archives</a></td>
    <td align="center" ><a href="<?= $url ?>Admin_Calendar/A_calendar_index.php">Calendar</a></td>
<!-- Commented out by Alec Spyrou 10/11/24 as it is an unused function and available in the TREASURER item below which will now be renamed Tournaments    <td align="center" ><a href="<?= $url ?>admin_tourn_view-download/AA_general_tourn_index.php">Tournaments</a></td> -->
    <td align="center" ><a href="<?= $url ?>Admin_rankings/a_rankindex.php">Rankings</a></td>
    <td align="center" ><a href="<?= $url ?>admin_scores/AA_scores_index_select_season.php">Scoring </a></td>
    <td align="center" ><a href="<?= $url ?>Admin_Clubs_Affiliates/A_Club_index.php">Clubs</a></td>
    <td align="center" ><a href="<?= $url ?>Admin_Tournaments/aa_tourn_index.php">Tournaments</a></td>
    <td align="center" ><a href="<?= $url ?>AAAA_Webmaster/AA_webmaster_index.php">Webmaster</a></td>
    <td align="center" ><a href="<?= $url ?>Admin_DB_VBSA/vbsa_login_success.php">Admin Menu</a></td>
  </tr>
</table>
