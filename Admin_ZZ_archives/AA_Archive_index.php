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

mysql_select_db($database_connvbsa, $connvbsa);
$query_memb_archive = "SELECT IDfin, Fin_ID, memb_cal_year FROM XXmemb_fin_2013_15 WHERE memb_cal_year BETWEEN 2012 AND 2015 GROUP BY memb_cal_year ORDER BY XXmemb_fin_2013_15.memb_cal_year DESC";
//echo($query_memb_archive);
$memb_archive = mysql_query($query_memb_archive, $connvbsa) or die(mysql_error());
$row_memb_archive = mysql_fetch_assoc($memb_archive);
$totalRows_memb_archive = mysql_num_rows($memb_archive);

/*$query_memb_archive = "SELECT IDfin, Fin_ID, memb_cal_year FROM XXmemb_fin_2013_15 WHERE memb_cal_year BETWEEN 2012 AND 2015 GROUP BY memb_cal_year ORDER BY XXmemb_fin_2013_15.memb_cal_year DESC";
$memb_archive = mysql_query($query_memb_archive, $connvbsa) or die(mysql_error());
$row_memb_archive = mysql_fetch_assoc($memb_archive);
$totalRows_memb_archive = mysql_num_rows($memb_archive);
*/

mysql_select_db($database_connvbsa, $connvbsa);
$query_scr_archive = "SELECT team_cal_year FROM Team_entries GROUP BY team_cal_year ORDER BY Team_entries.team_cal_year DESC";
//echo($query_scr_archive);
$scr_archive = mysql_query($query_scr_archive, $connvbsa) or die(mysql_error());
$row_scr_archive = mysql_fetch_assoc($scr_archive);
$totalRows_scr_archive = mysql_num_rows($scr_archive);

mysql_select_db($database_connvbsa, $connvbsa);
$query_after2015 = "SELECT `current_year_scrs`  FROM `scrs`  WHERE `current_year_scrs`>2015 GROUP BY `current_year_scrs`";
//echo($query_after2015);

$after2015 = mysql_query($query_after2015, $connvbsa) or die(mysql_error());
$row_after2015 = mysql_fetch_assoc($after2015);
$totalRows_after2015 = mysql_num_rows($after2015);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="1000" border="0" align="center">
  <tr>
	    <td class="red_bold">Members, Tournament and Yearly competition Archives</td>
	    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>

<table width="1000" align="center" class="greenbg_menu">

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
  </tr>
    <?php do { ?>
      <tr>
        <td width="288"><a href="players2016on.php?year=<?php echo $row_after2015['current_year_scrs']; ?>">2016</a></td>
        <td width="10">&nbsp;</td>
        <td width="742">List of players that played in this year - membership pay as you play system</td>
    </tr>
      <?php } while ($row_after2015 = mysql_fetch_assoc($after2015)); ?>

</table>

<table width="1000" align="center" class="greenbg_menu">
  <?php do { ?>
    <tr>
      <td width="288"><a href="Members_ZZ_archive.php?finmemb=<?php echo $row_memb_archive['memb_cal_year']; ?>"><?php echo $row_memb_archive['memb_cal_year']; ?></a></a>
      <td width="10">&nbsp;</td>
      <td width="742">All members - <?php echo $row_memb_archive['memb_cal_year']; ?></td>
    </tr>
    <?php } while ($row_memb_archive = mysql_fetch_assoc($memb_archive)); ?>
</table>
<table width="1000" align="center" class="greenbg_menu">
	  <tr>
	    <td align="left"><a href="../Admin_ZZ_archives/Members_ZZ_2012.php">2012</a></td>
	    <td width="10">&nbsp;</td>
	    <td width="742">2012 all members - membership list from XXarchive tables</td>
      </tr>
	  <tr>
	    <td align="left"><a href="../Admin_ZZ_archives/Members_ZZ_2011.php">2011</a></td>
	    <td>&nbsp;</td>
	    <td>2011 all members - membership list from XXarchive tables</td>
      </tr>
	  <tr>
	    <td width="232" align="left"><a href="../Admin_ZZ_archives/Members_ZZ_2010.php">2010</a></td>
	    <td>&nbsp;</td>
	    <td>2010 all members - membership list from XXarchive tables</td>
      </tr>
	  <tr>
	    <td width="232" align="left"><a href="../Admin_ZZ_archives/Members_ZZ_2009.php">2009</a></td>
	    <td>&nbsp;</td>
	    <td>2009 all members - membership list from XXarchive tables</td>
      </tr>
</table>
	<table width="1000" align="center" class="greenbg_menu">
	  <tr>
	    <td width="232" align="left" class="red_bold">Tournament Archives</td>
	    <td width="10">&nbsp;</td>
	    <td width="742">&nbsp;</td>
      </tr>
	  <tr>
	    <td align="left"><a href="../Admin_ZZ_archives_tourn/index.php">Tournament Archives</a></td>
	    <td>&nbsp;</td>
	    <td>Maintain records of past winners for all tournaments</td>
      </tr>
</table>
	<table width="1000" align="center">
      <tr>
        <td width="288"><span class="red_bold">Pennant, Billiards &amp; Willis Archives</span></td>
        <td width="10">&nbsp;</td>
        <td width="686">&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr>
          <td class="greenbg_menu"><a href="Premiers.php?arch=<?php echo $row_scr_archive['team_cal_year']; ?>"><?php echo $row_scr_archive['team_cal_year']; ?></a></td>
          <td>&nbsp;</td>
          <td>Premiers, High Breaks, Final Ladders, Team &amp; Player stats</td>
        </tr>
        <?php } while ($row_scr_archive = mysql_fetch_assoc($scr_archive)); ?>
    </table>
    <table width="1000" align="center" class="page">
	  <tr>
	    <td align="center">If there is a view that is not listed that would suit your purpose please let me know <a href="mailto:scores@vbsa.org.au">scores@vbsa.org.au</a></td>
      </tr>
</table>
<p>&nbsp;</p>
	<p>&nbsp;</p>
</body>
</html>
<?php

?>
