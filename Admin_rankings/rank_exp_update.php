<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once('../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "../Rankings/files";
$ppu->extensions = "pdf";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "";
$ppu->nameConflict = "over";
$ppu->nameToLower = false;
$ppu->requireUpload = false;
$ppu->minWidth = "";
$ppu->minHeight = "";
$ppu->maxWidth = "";
$ppu->maxHeight = "";
$ppu->saveWidth = "";
$ppu->saveHeight = "";
$ppu->timeout = "600";
$ppu->progressBar = "";
$ppu->progressWidth = "300";
$ppu->progressHeight = "100";
$ppu->redirectURL = "";
$ppu->checkVersion("2.1.12");
$ppu->doUpload();

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

if (isset($editFormAction)) {
  if (isset($_SERVER['QUERY_STRING'])) {
	  if (!eregi("GP_upload=true", $_SERVER['QUERY_STRING'])) {
  	  $editFormAction .= "&GP_upload=true";
		}
  } else {
    $editFormAction .= "?GP_upload=true";
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE rank_info SET rank_exp_title=%s, rank_exp_type=%s, rank_exp_pdf=IFNULL(%s,rank_exp_pdf), rank_exp_last_update=%s, `current`=%s WHERE rank_exp_id=%s",
                       GetSQLValueString($_POST['rank_exp_title'], "text"),
                       GetSQLValueString($_POST['rank_exp_type'], "text"),
                       GetSQLValueString($_POST['rank_exp_pdf'], "text"),
                       GetSQLValueString($_POST['rank_exp_last_update'], "date"),
                       GetSQLValueString(isset($_POST['current']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['rank_exp_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "rank_exp.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['rank_id'])) {
  $rank_id = $_GET['rank_id'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_exp_rank = "SELECT * FROM rank_info WHERE rank_exp_id = '$rank_id' ORDER BY rank_info.rank_exp_type";
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
<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
	<p>&nbsp;</p>
	<table align="center" cellpadding="5" cellspacing="5">
	  <tr>
	    <td align="center" class="red_bold">Billiards Rankings</td>
      </tr>
	  <tr>
	    <td align="center" class="red_bold">Upload a new pdf to replace existing</td>
      </tr>
</table>
	<p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'pdf',false,'','','','','','','');return document.MM_returnValue">
      <table align="center" cellpadding="3" cellspacing="3">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Current pdf: </td>
          <td><?php echo $row_exp_rank['rank_exp_pdf']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Title:</td>
          <td><input name="rank_exp_title" type="text" id="rank_exp_title" value="<?php echo $row_exp_rank['rank_exp_title']; ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Select the .pdf from your files: </td>
          <td><input name="rank_exp_pdf" type="file" onchange="checkOneFileUpload(this,'pdf',false,'','','','','','','')" value="<?php echo $row_exp_rank['rank_exp_pdf']; ?>" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">File Type:</td>
          <td><select name="rank_exp_type" id="rank_exp_type">
          	<option value="Billiards" <?php if (!(strcmp("Billiards", htmlentities($row_exp_rank['rank_exp_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Billiards</option>
            <option value="Snooker" <?php if (!(strcmp("Snooker", htmlentities($row_exp_rank['rank_exp_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Snooker</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Set the order you want the item to appear in:</td>
          <td><select name="rank_exp_order" id="rank_exp_order">
            <option value="01" <?php if (!(strcmp("01", htmlentities($row_exp_rank['rank_exp_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>01</option>
            <option value="02" <?php if (!(strcmp("02", htmlentities($row_exp_rank['rank_exp_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>02</option>
            <option value="03" <?php if (!(strcmp("03", htmlentities($row_exp_rank['rank_exp_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>03</option>
            <option value="04" <?php if (!(strcmp("04", htmlentities($row_exp_rank['rank_exp_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>04</option>
            <option value="05" <?php if (!(strcmp("05", htmlentities($row_exp_rank['rank_exp_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>05</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Appear on Website (checked=&quot;Yes&quot;</td>
          <td><input type="checkbox" name="current" id="current"  <?php if (!(strcmp(htmlentities($row_exp_rank['current'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Last Update:</td>
          <td><?php $newDate = date("l jS F Y g:ia", strtotime($row_exp_rank['rank_exp_last_update'])); echo $newDate; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Updated on (auto inserted):</td>
          <td><?php date_default_timezone_set('Australia/Melbourne'); echo date("l, F jS, Y \: g ia",time()); ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Update item" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="rank_exp_id" value="<?php echo $row_exp_rank['rank_exp_id']; ?>" />
    </form>
    <p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($exp_rank);
?>
