<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once('../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "forum_upload_attachments";
$ppu->extensions = "";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "";
$ppu->nameConflict = "over";
$ppu->nameToLower = false;
$ppu->requireUpload = true;
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE forum_attach SET Attachment=IFNULL(%s,Attachment) WHERE ID=%s",
                       GetSQLValueString($_POST['upload'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "forum_index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Upload = "SELECT ID, attach_id, attach_name, Attachment FROM forum_attach WHERE ID = (select Max( ID ) from  forum_attach)";
$Upload = mysql_query($query_Upload, $connvbsa) or die(mysql_error());
$row_Upload = mysql_fetch_assoc($Upload);
$totalRows_Upload = mysql_num_rows($Upload);
$query_Upload = "SELECT ID, attach_id, attach_name, Attachment FROM forum_attach WHERE ID = (select Max( ID ) from  forum_attach)";
$Upload = mysql_query($query_Upload, $connvbsa) or die(mysql_error());
$row_Upload = mysql_fetch_assoc($Upload);
$totalRows_Upload = mysql_num_rows($Upload);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>

<link href="../Admin_xx_CSS/forum_db.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/forum_db_links.css" rel="stylesheet" type="text/css" />
<script language='javascript' src='../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
</head>

<body>
<div id="forum_header">
  <div id="logo"><img src="../images/VBSA1.jpg" alt="" width="90" height="87" /></div>

<table width="870" align="right">
  <tr>

    <td class="red_bold">VBSA Administrators Forum</td>
    <td align="right" class="bluebg"><a href="How%20to%20use%20the%20VBSA%20Board%20Forum.pdf" target="_blank">How to use the Forum</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="red_bold"><span class="red_bld_txt">Upload an attachment</span></td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

<div id="ContentDB">
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'',true,'','','','','','','');return document.MM_returnValue">
    <table width="850" align="center" class="table_text">
      
      <tr>
        <td width="300" align="right">Attachment will be uploaded to this Topic:</td>
        <td width="542" class="red_txt"><?php echo $row_Upload['attach_id']; ?></td>
      </tr>
      <tr>
        <td align="right">Upload attachment to</td>
        <td class="red_txt"><?php echo $row_Upload['attach_name']; ?></td>
      </tr>
      <tr>
        <td width="300" align="right">Select the file to upload from your filing system</td>
        <td width="542"><label for="upload"></label>
          <input name="upload" type="file" id="upload" onchange="checkOneFileUpload(this,'',true,'','','','','','','')" value="<?php echo $row_Upload['Attachment']; ?>" size="50" /></td>
      </tr><input name="" type="hidden" value="" />
      <tr>
        <td width="300" align="right">&nbsp;</td>
        <td>
          <input type="submit" name="Upload" id="Upload" value="Upload" />
          Depending on file size this may take some time</td>
      </tr>
    </table>
    <input name="ID" type="hidden" value="<?php echo $row_Upload['ID']; ?>" />
    <input type="hidden" name="MM_update" value="form1" />
  </form>
</div>
</div>

</body>
</html>
<?php
mysql_free_result($Upload);
?>
