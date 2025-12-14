<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once('../../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "../../ClubImages";
$ppu->extensions = "GIF,JPG,JPEG,BMP,PNG";
$ppu->formName = "form2";
$ppu->storeType = "file";
$ppu->sizeLimit = "300";
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
  //if (PHP_VERSION < 6) {
  //  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  //}

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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE clubs SET ClubLogo=IFNULL(%s,ClubLogo), ClubTitle=%s, LastUpdated=%s, UpdatedBy=%s WHERE ClubNumber=%s",
                       GetSQLValueString($_POST['ClubLogo'], "text"),
                       GetSQLValueString($_POST['ClubTitle'], "text"),
                       GetSQLValueString($_POST['LastUpdated'], "date"),
                       GetSQLValueString($_POST['UpdatedBy'], "text"),
                       GetSQLValueString($_POST['ClubNumber'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../all_clubs_public.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_clublogo = "SELECT ClubNumber, ClubLogo, ClubTitle, LastUpdated, UpdatedBy FROM clubs WHERE ClubNumber = '$club_id'";
$clublogo = mysql_query($query_clublogo, $connvbsa) or die(mysql_error());
$row_clublogo = mysql_fetch_assoc($clublogo);
$totalRows_clublogo = mysql_num_rows($clublogo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 


</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>


<table width="800" align="center" cellpadding="2">
  <tr>
    <td class="red_bold">&nbsp;</td>
    <td class="red_bold">&nbsp;</td>
    <td class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td class="red_bold">EDIT / INSERT A CLUB LOGO</td>
    <td class="red_bold">&nbsp;</td>
    <td align="right" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="3" class="red_bold">Acceptable image formats: GIF,JPG,JPEG,BMP,PNG maximum image size: 300kb</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form2" id="form2" onsubmit="checkFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',false,300,'','','','','','');return document.MM_returnValue">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">ClubNumber:</td>
      <td><?php echo $club_id; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current Logo</td>
      <td><?php echo $row_clublogo['ClubLogo']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Club Logo:</td>
      <td><input name="ClubLogo" type="file" id="ClubLogo" onchange="checkOneFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',false,300,'','','','','','')" value="<?php echo $row_clublogo['ClubLogo']; ?>" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">ClubTitle:</td>
      <td><input type="text" name="ClubTitle" value="<?php echo htmlentities($row_clublogo['ClubTitle'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update image" /></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" class="red_text">Maximum image size is 300kb</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap">Current image</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap"><img src="http://www.vbsa.org.au/ClubImages/<?php echo $row_clublogo['ClubLogo']; ?>" height="120px" /></td>
    </tr>
  </table>
  <input type="hidden" name="LastUpdated" value="<?php echo date("Y-m-d")?>" />
  <input type="hidden" name="UpdatedBy" value="<?php echo $row_getusername['name']; ?>" />
  <input type="hidden" name="MM_update" value="form2" />
  <input type="hidden" name="ClubNumber" value="<?php echo $row_clublogo['ClubNumber']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($getusername);

mysql_free_result($played);

mysql_free_result($fin);

mysql_free_result($getusername);

mysql_free_result($clublogo);

?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

<script type="text/javascript">

function doit(){     
	
	var tx = jQuery.noConflict();
        tx.ajax({
            url     : '<?PHP echo $editFormAction ?>',
            type    : tx('#form1').attr('method'),
            data    : tx('#form1').serialize(),
            success : function( data ) {
                        alert('Updated Succesfully!');
						location.reload();
                      },
            error   : function( xhr, err ) {
                        alert('Error');     
                      }
        }); 
        return false;
}

</script>