<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="600" align="center">
  <tr>
    <td align="center" class="red_bold">Webmaster Index</td>
  </tr>
  <tr>
    <td align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  </table>
	<table align="center" cellpadding="5" cellspacing="5">
	  <tr>
	    <td colspan="2" class="greenbg_menu">&nbsp;</td>
      </tr>
	  <tr>
	    <td class="greenbg_menu"><a href="forum_visitors.php">Admin  visitors</a></td>
	    <td>Check visitors</td>
      </tr>
	  
	  <tr>
	    <td class="greenbg_menu"><a href="create_members_SMS.php">Create new SMS tables</a></td>
	    <td>Tables for bulk sms - create new</td>
      </tr>
	  <tr>
	    <td nowrap="nowrap" class="greenbg_menu"><a href="board_members.php">Board Members</a></td>
	    <td nowrap="nowrap">Add / Edit / insert Board Memmbers</td>
    </tr>
	  <tr>
	  	<tr>
	    <td nowrap="nowrap" class="greenbg_menu"><a href="affiliate_members.php">Affiliate Members</a></td>
	    <td nowrap="nowrap">Add / Edit / Affilate members / edit / upload help files</td>
      </tr>
	  <tr>
	    <td nowrap="nowrap" class="greenbg_menu"><a href="../Admin_links/links_index.php">Links</a></td>
	    <td nowrap="nowrap">Inser / Edit / delete links</td>
      </tr>
	  <tr>
	    <td class="greenbg_menu"><a href="create_members_current_csv.php">Download Members .csv</a></td>
	    <td>Members Current in a .csv file</td>
      </tr>
	  <tr>
	    <td nowrap="nowrap" class="greenbg_menu">&nbsp;</td>
	    <td nowrap="nowrap">&nbsp;</td>
      </tr>
	  <tr>
	    <td colspan="2" nowrap="nowrap" class="greenbg_menu">Webmaster - experimental</td>
      </tr>
	  <tr>
	    <td nowrap="nowrap" class="greenbg_menu"><a href="tournament_index.php">Tournament testing</a></td>
	    <td nowrap="nowrap">&nbsp;</td>
      </tr>
      <tr>
	    <td class="greenbg_menu"><a href="Webmaster_tables.php">Database tables</a></td>
	    <td>View all tables in the database</td>
      </tr>
</table>
	<p>&nbsp;</p>
</body>
</html>
