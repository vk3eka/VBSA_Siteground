<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$page = "../brks_byseason.php?comptype=$comptype&season=$season";
$_SESSION['page'] = $page;


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
$query_brks_all = "SELECT breaks.member_ID_brks, members.FirstName, members.LastName, breaks.grade, breaks.Break_ID, breaks.brk, members.MemberID, breaks.recvd, season, breaks.brk_type, breaks.finals_brk, brk_team_id FROM breaks, members WHERE breaks.member_ID_brks=members.MemberID AND `brk_type`='$comptype' AND season='$season' AND YEAR( recvd ) = YEAR( CURDATE( ) ) ORDER BY brk DESC";
$brks_all = mysql_query($query_brks_all, $connvbsa) or die(mysql_error());
$row_brks_all = mysql_fetch_assoc($brks_all);
$totalRows_brks_all = mysql_num_rows($brks_all);
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

  <table width="800" border="0" align="center">
    <tr>
      <td align="center" class="red_bold">&nbsp;</td>
      <td align="center" class="red_bold">&nbsp;</td>
    </tr>
    <tr>
    <td align="left" class="red_bold">All <?php echo $comptype; ?> Breaks recorded <?php echo $season. " " . date("Y"); ?></td>
    <td align="center" class="red_bold"><span class="greenbg"><a href="../admin_scores/AA_scores_index_grades.php? season=<?php echo $season; ?>">Return to <?php echo $season; ?> scores</a></span></td>
    </tr>
    <tr>
      <td align="center" class="greenbg">&nbsp;</td>
      <td align="center" class="greenbg">&nbsp;</td>
    </tr>
</table>
<table width="775" border="1" align="center" class="page">
  <tr>
    <td align="center">member ID</td>
    <td align="left">Last Name</td>
    <td align="left">First Name</td>
    <td align="center">Break ID</td>
    <td align="center">Break</td>
    <td align="center">Grade</td>
    <td align="center">Finals Break?</td>
    <td>inserted on</td>
    <td>Type</td>
    <td align="left">Team ID</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php  if($totalRows_brks_all>0) do { ?>
  <tr>
    <td align="center"><?php echo $row_brks_all['member_ID_brks']; ?></td>
    <td align="left"><?php echo $row_brks_all['FirstName']; ?></td>
    <td align="left"><?php echo $row_brks_all['LastName']; ?></td>
    <td align="center"><?php echo $row_brks_all['Break_ID']; ?></td>
    <td align="center"><?php echo $row_brks_all['brk']; ?></td>
    <td align="center"><?php echo $row_brks_all['grade']; ?></td>
    <td align="center"><?php echo $row_brks_all['finals_brk']; ?></td>
    <td><?php echo $row_brks_all['recvd']; ?></td>
    <td nowrap="nowrap"><?php echo $row_brks_all['brk_type']; ?></td>
    <td align="left" nowrap="nowrap"><?php echo $row_brks_all['brk_team_id']; ?></td>
    <td nowrap="nowrap"><a href="user_files/break_edit.php?brk_id=<?php echo $row_brks_all['Break_ID']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="20" title="Edit Break" /></a></td>
        <td align="center"><a href="user_files/break_delete_confirm.php?brk_id=<?php echo $row_brks_all['Break_ID']; ?>"><img src="../Admin_Images/Trash.fw.png" width="18" title="Delete Permanently" /></a></td>
  </tr>
  <?php } while ($row_brks_all = mysql_fetch_assoc($brks_all));  else echo '<tr><td colspan="12" align="center" class="Italic">'."No Breaks Recorded".'</td></tr>'; ?>
</table>
<input name="" type="hidden" value="" />
<input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_brks_all['Break_ID']; ?>" />

</body>
</html>
<?php

?>