<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once("../../webassist/ckeditor/ckeditor.php"); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE webpage_items SET Header=%s, `Comment`=%s, updated=%s, blocked=%s, OrderFP=%s, OrderRef=%s, OrderJunior=%s, OrderHelp=%s, OrderWomens=%s, OrderRefProfile=%s, OrderRefPoser=%s, OrderPlayerProfile=%s, OrderScores=%s, OrderPolProc=%s, OrderAbout=%s, page_front=%s, page_referee=%s, page_junior=%s, page_help=%s, page_womens=%s, page_refprofile=%s, page_refposer=%s, page_playerprofile=%s, page_scores=%s, page_polproc=%s, page_about=%s WHERE ID=%s",
                       GetSQLValueString($_POST['Header'], "text"),
                       GetSQLValueString($_POST['Comment'], "text"),
					   GetSQLValueString($_POST['updated'], "date"),
                       GetSQLValueString($_POST['blocked'], "text"),
                       GetSQLValueString($_POST['OrderFP'], "text"),
                       GetSQLValueString($_POST['OrderRef'], "text"),
                       GetSQLValueString($_POST['OrderJunior'], "text"),
					   GetSQLValueString($_POST['OrderHelp'], "text"),
					   GetSQLValueString($_POST['OrderWomens'], "text"),
					   GetSQLValueString($_POST['OrderRefProfile'], "text"),
					   GetSQLValueString($_POST['OrderRefPoser'], "text"),
					   GetSQLValueString($_POST['OrderPlayerProfile'], "text"),
					   GetSQLValueString($_POST['OrderScores'], "text"),
					   GetSQLValueString($_POST['OrderPolProc'], "text"),
					   GetSQLValueString($_POST['OrderAbout'], "text"),
                       GetSQLValueString(isset($_POST['page_front']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_referee']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_junior']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_help']) ? "true" : "", "defined","'Y'","'N'"),
					   GetSQLValueString(isset($_POST['page_womens']) ? "true" : "", "defined","'Y'","'N'"),
					   GetSQLValueString(isset($_POST['page_refprofile']) ? "true" : "", "defined","'Y'","'N'"),
					   GetSQLValueString(isset($_POST['page_refposer']) ? "true" : "", "defined","'Y'","'N'"),
					   GetSQLValueString(isset($_POST['page_playerprofile']) ? "true" : "", "defined","'Y'","'N'"),
					   GetSQLValueString(isset($_POST['page_scores']) ? "true" : "", "defined","'Y'","'N'"),
					   GetSQLValueString(isset($_POST['page_polproc']) ? "true" : "", "defined","'Y'","'N'"),
					   GetSQLValueString(isset($_POST['page_about']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

$updateGoTo = "../item_detail.php?item_id=" . $_REQUEST['item_id'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_FPedit = "SELECT * FROM webpage_items WHERE ID = '$item_id'";
$FPedit = mysql_query($query_FPedit, $connvbsa) or die(mysql_error());
$row_FPedit = mysql_fetch_assoc($FPedit);
$totalRows_FPedit = mysql_num_rows($FPedit);
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

<script type="text/javascript" >
function validateForm() {
    var allchecked=0;
if(document.getElementById('page_front').checked)allchecked=1;
if(document.getElementById('page_referee').checked)allchecked=1;
if(document.getElementById('page_junior').checked)allchecked=1;
if(document.getElementById('page_help').checked)allchecked=1;
if(document.getElementById('page_womens').checked)allchecked=1;
if(document.getElementById('page_refprofile').checked)allchecked=1;
if(document.getElementById('page_refposer').checked)allchecked=1;
if(document.getElementById('page_playerprofile').checked)allchecked=1;
if(document.getElementById('page_scores').checked)allchecked=1;
if(document.getElementById('page_poproc').checked)allchecked=1;
if(document.getElementById('page_about').checked)allchecked=1;
    if(allchecked==0) {
        alert("NO PAGE SELECTED\n\nPlease select at least one");
        return false;
    }
	else {return true;}
}
</script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td align="center" class="red_bold">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">VBSA Front page - Edit an item</td>
    <td align="center" class="page"><a href="javascript:history.go(-1)"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></a></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return validateForm()">
  <table width="1000" align="center" class="page">
    <tr>
          <td>&nbsp;</td>
          <td colspan="10">&nbsp;</td>
    </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap" class="page">ID:</td>
          <td colspan="10"><?php echo $row_FPedit['ID']; ?></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap" class="page">Header:</td>
          <td colspan="10"><input type="text" name="Header" value="<?php echo htmlentities($row_FPedit['Header'], ENT_COMPAT, 'utf-8'); ?>" size="60" /></td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap" class="page">Comment:</td>
          <td colspan="10"><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "".$row_FPedit['Comment']  ."";
$CKEditor = new CKEditor();
$CKEditor->basePath = "../../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "forum1 (custom)";
$CKEditor_config["wa_preset_file"] = "(custom)";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "200px";
$CKEditor_config["uiColor"] = "#CCFFFF";
$CKEditor_config["docType"] = "<" ."!" ."DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
$CKEditor_config["contentsLanguage"] = "";
$CKEditor_config["dialog_startupFocusTab"] = false;
$CKEditor_config["fullPage"] = false;
$CKEditor_config["tabSpaces"] = 4;
$CKEditor_config["toolbar"] = array(
array( 'Source'),
array( 'Bold','Italic'),
array( 'TextColor'),
array( 'NumberedList','BulletedList','-','Outdent','Indent'),
array( 'PasteText','PasteFromWord','SpellChecker'),
array( 'Link','Unlink'),
array( 'Undo','Redo','-','Find','Replace'));
$CKEditor_config["contentsLangDirection"] = "ltr";
$CKEditor_config["entities"] = false;
$CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
$CKEditor_config["pasteFromWordRemoveStyles"] = false;
$CKEditor->editor("Comment", $CKEditor_initialValue, $CKEditor_config);
?></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap" class="page">Item submitted by:</td>
          <td colspan="10"> <?php echo $row_FPedit['By']; ?> (Cannot be edited)</td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td colspan="10">&nbsp;</td>
        </tr>
                <tr>
          <td colspan="11" align="left" valign="middle" nowrap="nowrap" class="red_text">Please select the page or pages that you want this item to appear on (item may be set to appear on multiple pages). Item will not insert unless at least one page is selected.</td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="Italic">Web Pages</td>
          <td align="right" valign="middle" nowrap="nowrap">Front Page</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_front" value="Y" id="page_front"  <?php if (!(strcmp(htmlentities($row_FPedit['page_front'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td align="right" valign="middle" nowrap="nowrap">Junior Page</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_junior" value="Y" id="page_junior"  <?php if (!(strcmp(htmlentities($row_FPedit['page_junior'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td align="right" valign="middle" nowrap="nowrap">Help Page</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_help" value="Y" id="page_help"  <?php if (!(strcmp(htmlentities($row_FPedit['page_help'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td align="right" valign="middle" nowrap="nowrap">Womens Page</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_womens" value="Y" id="page_womens"  <?php if (!(strcmp(htmlentities($row_FPedit['page_womens'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td align="right" valign="middle" nowrap="nowrap">Player Profiles</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_playerprofile" value="Y" id="page_playerprofile"  <?php if (!(strcmp(htmlentities($row_FPedit['page_playerprofile'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="Italic">Administrative pages</td>
          <td align="right" valign="middle" nowrap="nowrap">About</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_about" value="Y" id="page_about"  <?php if (!(strcmp(htmlentities($row_FPedit['page_about'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td align="right" valign="middle" nowrap="nowrap">Scores</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_scores" value="Y" id="page_scores"  <?php if (!(strcmp(htmlentities($row_FPedit['page_scores'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td align="right" valign="middle" nowrap="nowrap">Policies & Procedures</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_polproc" value="Y" id="page_polproc"  <?php if (!(strcmp(htmlentities($row_FPedit['page_polproc'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
          <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
          <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
          <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="Italic">Referees pages</td>
          <td align="right" valign="middle" nowrap="nowrap">Referees Page</td>
          <td nowrap="nowrap"><input type="checkbox" name="page_referee" value="Y" id="page_referee"  <?php if (!(strcmp(htmlentities($row_FPedit['page_referee'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td align="right" valign="middle" nowrap="nowrap">Referee Profiles</td>
          <td nowrap="nowrap"><input type="checkbox" name="page_refprofile" value="Y" id="page_refprofile"  <?php if (!(strcmp(htmlentities($row_FPedit['page_refprofile'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td align="right" valign="middle" nowrap="nowrap"><label> Referee Posers&nbsp;&nbsp;&nbsp;</label></td>
          <td nowrap="nowrap"><input type="checkbox" name="page_refposer" value="Y" id="page_refposer"  <?php if (!(strcmp(htmlentities($row_FPedit['page_refposer'], ENT_COMPAT, 'utf-8'),"Y"))) {echo "checked=\"checked\"";} ?> /></td>
          <td nowrap="nowrap">&nbsp;</td>
          <td nowrap="nowrap">&nbsp;</td>
          <td nowrap="nowrap">&nbsp;</td>
          <td nowrap="nowrap">&nbsp;</td>
        </tr>
        </table>
        <table width="1000" align="center">
          <td colspan="4" align="center" nowrap="nowrap" class="red_text">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" nowrap="nowrap" class="red_text"> Will determine the display order of an item and make the item remain in the selected position on the  page.</td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="page">Ordered Front Page?:</td>
          <td width="271"><select name="OrderFP">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderFP'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
          <td width="313" align="right" valign="middle">Ordered Referees Page?</td>
          <td width="196"><select name="OrderRef">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderRef'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="page">Ordered Juniors Page?</td>
          <td><select name="OrderJunior">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderJunior'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
          <td align="right" valign="middle">Ordered Referees Profiles Page?</td>
          <td><select name="OrderRefProfile">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderRefProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="page">Ordered Womens Page?</td>
          <td><select name="OrderWomens">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderWomens'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
          <td align="right" valign="middle">Ordered Referees Posers Page?</td>
          <td><select name="OrderRefPoser">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderRefPoser'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="page">Ordered Help Page?</td>
          <td><select name="OrderHelp">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderHelp'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
          <td align="right" valign="middle">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="page">&nbsp;</td>
          <td>&nbsp;</td>
          <td align="right" valign="middle">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="page">Ordered Scores Page?</td>
          <td><select name="OrderScores">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderScores'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
          <td align="right" valign="middle" nowrap="nowrap">Ordered Policies &amp; Procedures Page?</td>
          <td><select name="OrderPolProc">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderPolProc'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="page">Ordered Player Profiles Page?</td>
          <td><select name="OrderPlayerProfile">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderPlayerProfile'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
          <td align="right" valign="middle">Ordered About?</td>
          <td><select name="OrderAbout">
            <option value="not ordered" <?php if (!(strcmp("not ordered", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>not ordered</option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
            <option value="6" <?php if (!(strcmp("6", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
            <option value="7" <?php if (!(strcmp("7", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
            <option value="8" <?php if (!(strcmp("8", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
            <option value="9" <?php if (!(strcmp("9", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
            <option value="10" <?php if (!(strcmp("10", htmlentities($row_FPedit['OrderAbout'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
          </select></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap" class="page">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap" class="page">Image size:</td>
          <td colspan="3"><?php echo $row_FPedit['img_size']; ?></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap" class="page">Uploaded image: </td>
          <td colspan="3"><?php echo $row_FPedit['item_image']; ?></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap" class="page">Blocked: </td>
          <td colspan="3"><select name="blocked">
            <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_FPedit['blocked'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
            <option value="No" <?php if (!(strcmp("No", htmlentities($row_FPedit['blocked'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
          </select>
If set to &quot;Yes&quot; item will not appear on the site</td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap" class="page">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap" class="page">&nbsp;</td>
          <td colspan="3"><input type="submit" value="Update Item" /></td>
        </tr>
      </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="ID" value="<?php echo $row_FPedit['ID']; ?>" />
  <input type="hidden" name="updated" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?>" />
  </form>
</body>
</html>
<?php
mysql_free_result($FPedit);
?>
