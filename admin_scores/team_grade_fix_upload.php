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

$year = "-1";
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}

$targetDir = "../fix_upload/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
}
$target_id = $_GET['id'];
//echo($target_id . "<br>");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

  $uploadFile = $_FILES['fix_upload'];
  $fileName = basename($uploadFile['name']);
  $targetFilePath = $targetDir . $fileName;

  // Validate file type
  $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
  $allowedTypes = array('pdf');

  // Move uploaded file to target directory
  if((in_array($fileType, $allowedTypes)))
  {
    move_uploaded_file($uploadFile['tmp_name'], $targetFilePath);
  }
  $current = 'Yes';
  $updateSQL = sprintf("Update Team_grade SET fix_upload=IFNULL(%s, fix_upload), fix_cal_year=%s WHERE id=%s",
            GetSQLValueString($fileName, "text"),
            GetSQLValueString($_POST['fix_cal_year'], "date"),
            GetSQLValueString($target_id, "int"));
  mysql_select_db($database_connvbsa, $connvbsa);
  //echo($updateSQL . "<br>");
  $Result = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
  $updateGoTo = "team_grades.php?season=".$season."&year=".$_POST['fix_cal_year'];
  header("Location: " . $updateGoTo);
}

$colname_teamgrades_fix = "-1";
if (isset($_GET['grade'])) {
  $colname_teamgrades_fix = $_GET['grade'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_teamgrades_fix = sprintf("SELECT grade, fix_upload, grade_name, season, fix_cal_year FROM Team_grade WHERE grade = %s", GetSQLValueString($colname_teamgrades_fix, "text"));
$teamgrades_fix = mysql_query($query_teamgrades_fix, $connvbsa) or die(mysql_error());
$row_teamgrades_fix = mysql_fetch_assoc($teamgrades_fix);
$totalRows_teamgrades_fix = mysql_num_rows($teamgrades_fix);
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

<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center" class="red_bold"> TEAM GRADES Season <?php echo $season ?>  - Upload a fixture (pdf only)</td>
    <td width="262" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'',true,'','','','','','','');return document.MM_returnValue">
  <table align="center">
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap">You are about to upload a file for <?php echo $row_teamgrades_fix['grade']; ?> in season <?php echo $season; ?> of <?php echo date('Y') ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current file:</td>
      <td><?php echo $row_teamgrades_fix['fix_upload']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Select file to upload</td>
      <td><input name="fix_upload" type="file" id="fix_upload" onchange="checkOneFileUpload(this,'',true,'','','','','','','')" value="<?php echo $row_teamgrades_fix['fix_upload']; ?>" />
        Upload again to overwrite or replace</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Upload file" /></td>
    </tr>
  </table>
  <input type="hidden" name="grade" value="<?php echo $row_teamgrades_fix['grade']; ?>" />
  <input type="hidden" name="fix_cal_year" value="<?php echo date('Y') ?>" />
  <input type="hidden" name="MM_update" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>

