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

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades_fix = "SELECT * FROM Team_grade WHERE season ='$season' AND current='Yes' ORDER BY  type, dayplayed, grade_name";
$grades_fix = mysql_query($query_grades_fix, $connvbsa) or die(mysql_error());
$row_grades_fix = mysql_fetch_assoc($grades_fix);
$totalRows_grades_fix = mysql_num_rows($grades_fix);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades_arch = "SELECT * FROM Team_grade WHERE season ='$season' AND current='No' ORDER BY type, dayplayed, grade_name";
$grades_arch = mysql_query($query_grades_arch, $connvbsa) or die(mysql_error());
$row_grades_arch = mysql_fetch_assoc($grades_arch);
$totalRows_grades_arch = mysql_num_rows($grades_arch);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 


</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="11" align="center" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" align="center" class="red_bold">IMPORTANT - Allocated ranking points must be set or rankings will not calculate</td>
  </tr>
  <tr>
    <td colspan="11" align="center">Note: Snooker Ranking points are allocated on a per frame won basis, Billiards ranking points are allocated on a per game won eg. Win =2 points x allocated ranking points</td>
  </tr>
  <tr>
    <td colspan="6" align="center">&nbsp;</td>
    <td colspan="5" align="center">&nbsp;</td>
  </tr>
  <tr>
        <td colspan="4" align="center"><span class="red_bold">TEAM GRADES to be fixtured in <?php echo $season; ?></td>
        <td colspan="2" align="center" class="greenbg"><a href="user_files/team_grade_insert.php?season=<?php echo $season; ?>" rel="facebox">Insert a new grade</a></td>
        <td align="center" class="greenbg">&nbsp;</td>
        <td align="center" class="greenbg">&nbsp;</td>
        <td colspan="3" align="right" class="greenbg"><a href="AA_scores_index_grades.php?season=<?php echo $season ?>">Return to <?php echo $season ?> ladders</a></td>
      </tr>
      <tr>
        <td align="center">Grade Code</td>
        <td>Grade Name</td>
        <td>Season</td>
        <td>Type</td>
        <td>Fixture</td>
        <td align="center">Current</td>
        <td align="center" nowrap="nowrap">Allocated Rank points</td>
        <td align="center">Finals Teams</td>
        <td align="center">Day Played</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_grades_fix['grade']; ?></td>
          <td><?php echo $row_grades_fix['grade_name']; ?></td>
          <td><?php echo $row_grades_fix['season']; ?></td>
          <td><?php echo $row_grades_fix['type']; ?></td>
          <td class="page">
		  <?php
		  
if ($row_grades_fix['fix_cal_year']== date('Y')  && !empty($row_grades_fix['fix_upload'])) 
{
?>
<a href="http://www.vbsa.org.au/fix_upload/<?php echo $row_grades_fix['fix_upload']; ?>" target="_blank"><?php echo $row_grades_fix['fix_upload']; ?></a>
<?php    
} 

elseif ($row_grades_fix['fix_cal_year']<> date('Y') OR !isset($grades_fix->fix_upload)) 
{
echo "Not Available";
} 
?>

</td>
          <td align="center"><?php echo $row_grades_fix['current']; ?></td>
           <td align="center"><?php if($row_grades_fix['RP']!=0) echo $row_grades_fix['RP']; else echo "NOT SET" ?></td>
          <td align="center"><?php echo $row_grades_fix['finals_teams']; ?></td>
          <td align="center"><?php echo $row_grades_fix['dayplayed']; ?></td>
          <td><a href="user_files/team_grade_edit.php?grade=<?php echo $row_grades_fix['grade']; ?>&amp;season=<?php echo $season ?>"><img src="../Admin_Images/edit_butt.png" title="Edit this grade" width="16" height="16" /></a></td>
          <td class="page"><a href="team_grade_fix_upload.php?grade=<?php echo $row_grades_fix['grade']; ?> &amp; season=<?php echo $row_grades_fix['season']; ?>">upload a fixture</a></td>
        </tr>
        <?php } while ($row_grades_fix = mysql_fetch_assoc($grades_fix)); ?>
    </table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan="6" align="center">&nbsp;</td>
    <td colspan="5" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center"><span class="red_bold"><?php echo $colname_grades_arch; ?> ARCHIVED TEAM GRADES - Where &quot;Current = No&quot;</span></td>
    <td colspan="5" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">Grade Code</td>
    <td>Grade Name</td>
    <td>Season</td>
    <td>Type</td>
    <td>Fixture</td>
    <td align="center">Current</td>
    <td align="center" nowrap="nowrap">Allocated Rank points</td>
    <td align="center">Finals Teams</td>
    <td align="center">Day Played</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr>
    <td align="center"><?php echo $row_grades_arch['grade']; ?></td>
    <td><?php echo $row_grades_arch['grade_name']; ?></td>
    <td><?php echo $row_grades_arch['season']; ?></td>
    <td><?php echo $row_grades_arch['type']; ?></td>
    <td class="page"><?php
if ($row_grades_arch['fix_cal_year']== date('Y') && !empty($row_grades_arch['fix_upload'])) 
{ echo "Yes";
?>
      <a href="http://www.vbsa.org.au/fix_upload/<?php echo $row_grades_arch['fix_upload']; ?>"  target="_blank"><?php echo $row_grades_arch['fix_upload']; ?></a>
<?php    
} 
elseif ($row_grades_arch['fix_cal_year']<> date('Y') OR !isset($row_grades_arch['fix_upload'])) 
{
echo "Not Available";
} 
?></td>
    <td align="center"><?php echo $row_grades_arch['current']; ?></td>
    <td align="center"><?php if($row_grades_arch['RP']!=0) echo $row_grades_arch['RP']; else echo "NOT SET" ?></td>
    <td align="center"><?php echo $row_grades_arch['finals_teams']; ?></td>
    <td align="center"><?php echo $row_grades_arch['dayplayed']; ?></td>
    <td><a href="user_files/team_grade_edit.php?grade=<?php echo $row_grades_arch['grade']; ?>&amp;season=<?php echo $season ?>"><img src="../Admin_Images/edit_butt.png" alt="" width="16" height="16" title="Edit this grade" /></a></td>
    <td class="page">&nbsp;</td>
  </tr>
  <?php } while ($row_grades_arch = mysql_fetch_assoc($grades_arch)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
