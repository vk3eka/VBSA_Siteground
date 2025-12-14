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
$query_T96RR6 = "SELECT * FROM draw_96RR6";
$T96RR6 = mysql_query($query_T96RR6, $connvbsa) or die(mysql_error());
$row_T96RR6 = mysql_fetch_assoc($T96RR6);
$totalRows_T96RR6 = mysql_num_rows($T96RR6);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<link href="../Admin_xx_CSS/fixtures.css" rel="stylesheet" type="text/css" />
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
    <td align="center" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">Tournament Draw base table 96RR6 - 96 entries, 16 groups round robin</td>
  </tr>
  <tr>
    <td align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  </table>
<table align="center" cellpadding="2" cellspacing="2"  class="fix_altcolor">
  <tr>
    <th align="center">Group</th>
    <th align="center">Seed 1</th>
    <th align="center">Seed 2</th>
    <th align="center">Seed 3</th>
    <th align="center">Seed 4</th>
    <th align="center">Seed 5</th>
    <th align="center">Seed 6</th>
  </tr>
  <?php do { ?>
    <tr>
      <th align="center"><?php echo $row_T96RR6['group_id']; ?></th>
      <td align="center"><?php echo $row_T96RR6['seed1']; ?></td>
      <td align="center"><?php echo $row_T96RR6['seed2']; ?></td>
      <td align="center"><?php echo $row_T96RR6['seed3']; ?></td>
      <td align="center"><?php echo $row_T96RR6['seed4']; ?></td>
      <td align="center"><?php echo $row_T96RR6['seed5']; ?></td>
      <td align="center"><?php echo $row_T96RR6['seed6']; ?></td>
    </tr>
    <?php } while ($row_T96RR6 = mysql_fetch_assoc($T96RR6)); ?>
</table>


<table align="center">
<tr>
	
<?php
$sql_result = mysql_query ("SELECT seed_draw FROM draw_96RR6_2");
$record_count = 0;  //Keeps count of the records echoed.
while ($row=mysql_fetch_row($sql_result))
{
    //Check to see if it is time to start a new row
    //Note: the first time through when
    //$record_count==0, don't start a new row
    if ($record_count % 6==0 && $record_count != 0)
    {
        echo '</tr><tr>';
    }
    
    //Echo out the entire record in one table cell:
    for ($i=0; $i< count($row); $i++)
    {
        echo '<td align="center" width="30">'; echo $row[$i]; echo '</td>';
    }
    
    //Indicate another record has been echoed:
    $record_count++;
}
?>
1
2
	
</tr>
</table>
</body>
</html>
<?php
mysql_free_result($T96RR6);
?>
