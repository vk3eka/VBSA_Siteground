<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once('../../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "../../images_frontpage";
$ppu->extensions = "GIF,JPG,JPEG,BMP,PNG";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "500";
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
  $updateSQL = sprintf("UPDATE webpage_items SET img_size=%s, item_image=IFNULL(%s,item_image) WHERE ID=%s",
                       GetSQLValueString($_POST['img_size'], "text"),
                       GetSQLValueString($_POST['item_image'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  echo($updateSQL . "<br>");
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../item_detail.php?item_id=" . $_REQUEST['item_id'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  //header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}
echo($item_id . "<br>");

mysql_select_db($database_connvbsa, $connvbsa);
$query_image_up = "SELECT ID, Header, item_image, img_size FROM webpage_items WHERE ID = '$item_id'";
$image_up = mysql_query($query_image_up, $connvbsa) or die(mysql_error());
$row_image_up = mysql_fetch_assoc($image_up);
$totalRows_image_up = mysql_num_rows($image_up);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Front Page Administation Area</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>
<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>

<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
</head>
<body>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td align="center" class="red_bold"><p>Upload an image to a Web Page item<span class="webmaster_table"></span> - MAXIMUM IMAGE SIZE TO UPLOAD IS 500KB, IMAGE MUST BE .jpg or .jpeg</p>      </td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',false,500,'','','','','','');return document.MM_returnValue">
  <table width="1006" align="center" class="table_text">
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Item ID : </td>
      <td><?php echo $row_image_up['ID']; ?></td>
    </tr>
    <tr>
      <td align="right">Item Title : </td>
      <td><?php echo $row_image_up['Header']; ?></td>
    </tr>
    <tr>
      <td align="right">Select a size for your image (default 30%) : </td>
      <td><select name="img_size">
      <option value="20%" <?php if (!(strcmp("20%", htmlentities($row_image_up['img_size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>20%</option>
      <option value="30%" selected="selected" <?php if (!(strcmp("30%", htmlentities($row_image_up['img_size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>30%</option>
      <option value="40%" <?php if (!(strcmp("40%", htmlentities($row_image_up['img_size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>40%</option>
      <option value="50%" <?php if (!(strcmp("50%", htmlentities($row_image_up['img_size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>50%</option>
      <option value="60%" <?php if (!(strcmp("60%", htmlentities($row_image_up['img_size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>60%</option>
      <option value="70%" <?php if (!(strcmp("70%", htmlentities($row_image_up['img_size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>70%</option>
      <option value="100%" <?php if (!(strcmp("100%", htmlentities($row_image_up['img_size'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>100%</option>
      </select></td>
    </tr>
    <tr>
      <td align="right">Current image (upload a new image to replace)</td>
      <td>
		<?php
				if(empty ($row_image_up['item_image']))
				{
				echo "No image uploaded";
				}
				elseif(isset ($row_image_up['item_image']))
				{
				echo $row_image_up['item_image'];
				}
				?>
      </td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">Please select the file to upload from your filing system</td>
      <td nowrap="nowrap"><input name="item_image" type="file" id="item_image" onchange="checkOneFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',false,500,'','','','','','')" value="<?php echo $row_image_up['item_image']; ?>" size="50" />  
      Maximum image size 500kb, must be .jpg or .jpeg</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><input type="submit" name="Upload" id="Upload" value="Upload or Update" /></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
  </table>

  <input type="hidden" name="MM_update" value="form1" />
  <input name="ID" type="hidden" id="ID" value="<?php echo $row_image_up['ID']; ?>" />
</form>
</body>
</html>
<?php
mysql_free_result($image_up);
?>