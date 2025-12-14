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
$query_exp_rank = "SELECT * FROM rank_info ORDER BY rank_exp_type, rank_exp_order DESC";
$exp_rank = mysql_query($query_exp_rank, $connvbsa) or die(mysql_error());
$row_exp_rank = mysql_fetch_assoc($exp_rank);
$totalRows_exp_rank = mysql_num_rows($exp_rank);
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
        <td colspan="7" align="right">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="center" class="red_bold">Rankings Information</td>
        <td colspan="4" align="center" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
      </tr>
      <tr>
        <td colspan="3" class="page">This information appears on<a href="http://www.vbsa.org.au/Rankings/rankings_index.php" target="_blank"> this page</a> please check after update or insert</td>
        <td colspan="4" align="right" class="greenbg"><a href="rank_exp_insert.php">Insert a new item</a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Ranking Title</td>
        <td>File Name (pdf)</td>
        <td>File Type</td>
        <td>Last Update</td>
        <td align="center">Order</td>
        <td align="center">Current? (1=Yes, 0=No)</td>
        <td>&nbsp;</td>
      </tr>
	  <?php do { ?>
      <tr>
        <td class="page" nowrap="nowrap"><a href="../Rankings/files/<?php echo $row_exp_rank['rank_exp_pdf']; ?>"><?php echo $row_exp_rank['rank_exp_title']; ?></a></td>
        
        <td nowrap="nowrap"><?php echo $row_exp_rank['rank_exp_pdf']; ?></td>
        <td nowrap="nowrap"><?php echo $row_exp_rank['rank_exp_type']; ?></td>
        <td nowrap="nowrap"><?php $newDate = date("l jS F Y g:ia", strtotime($row_exp_rank['rank_exp_last_update'])); echo $newDate; ?></td>
        <td align="center" nowrap="nowrap"><?php echo $row_exp_rank['rank_exp_order']; ?></td>
        <td align="center" nowrap="nowrap"><?php echo $row_exp_rank['current']; ?></td>
        <td><a href="rank_exp_update.php?rank_id=<?php echo $row_exp_rank['rank_exp_id']; ?>&type=<?php echo $row_exp_rank['rank_exp_type']; ?>"><img src="../Admin_Images/edit_butt.fw.png" width="25" height="25" title="Upload a new pdf" /></a></td>
          
      </tr>
	  <?php } while ($row_exp_rank = mysql_fetch_assoc($exp_rank)); ?>
    </table>
</body>
</html>
<?php
mysql_free_result($exp_rank);
?>
