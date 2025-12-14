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

$colname_tournRP = "-1";
if (isset($_GET['RP_sname'])) {
  $colname_tournRP = $_GET['RP_sname'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_tournRP = sprintf("SELECT t_rank, member_tid, FirstName, LastName, pts_tourn, tourn_total, scr_total, scr_percent FROM rankings_tourn_ordered LEFT JOIN members ON MemberID = member_tid LEFT JOIN rankings_snooker ON memb_id = member_tid WHERE LastName LIKE %s ORDER BY FirstName, LastName", GetSQLValueString($colname_tournRP . "%", "text"));
$tournRP = mysql_query($query_tournRP, $connvbsa) or die(mysql_error());
$row_tournRP = mysql_fetch_assoc($tournRP);
$totalRows_tournRP = mysql_num_rows($tournRP);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

<body>
    


<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table align="center" cellpadding="5" cellspacing="5"> 
  	<tr>
    <td class="red_bold">Victorian Tournament Rankings - Search results</td>
    <td align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>  	
 

  <!-- Search Bar -->
  <table align="center">
  <tr>
    <td><form action="rankings_vic_snooker_name_srch_res.php" method="get" enctype="<?php echo $ranknum; ?>" name="RP_sname" id="RP_sname" >
        <label class="glyphicon glyphicon-search" style="margin-right:3px"></label>
        <input name="RP_sname" type="text" id="RP_sname" placeholder="Search by Surname" />
        </form>
    </td>
  </tr>
  </table>



  <table width="600" align="center" cellpadding="5" cellspacing="5">
        <tr>
          <th>Name</th>
          <th align="center">Ranked</th>
          <th align="center">Total RP</th>
          <th >&nbsp;</th>
        </tr>
        <?php do { ?>
        <tr>
          <td nowrap="nowrap"><?php echo $row_tournRP['FirstName']; ?> <?php echo $row_tournRP['LastName']; ?></td>
          <td align="center"><?php echo $row_tournRP['t_rank']; ?></td>
          <td align="center"><?php echo $row_tournRP['pts_tourn']; ?></td>
          <td nowrap="nowrap" class="greenbg"><a href="rankings_Vic_snooker_detail.php?rank=<?php echo $row_tournRP['member_tid']; ?>">how these points were awarded</a></td>
        </tr>
        <?php } while ($row_tournRP = mysql_fetch_assoc($tournRP)); ?>
      </table>

</div>  <!-- close conraineing wrapper --> 
</body>
</html>
<?php
mysql_free_result($tournRP);
?>
