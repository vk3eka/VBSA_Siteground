<?php require_once('../../ScriptLibrary/incPureUpload.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['attach_redirect'])) {
  $attach_redirect = $_GET['attach_redirect'];
}


// Pure PHP Upload 2.1.12
$ppu = new pureFileUpload();
$ppu->path = "../files";
$ppu->extensions = "pdf";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "600";
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
$ppu->redirectURL = "upload_attach_redirect.php?";
$ppu->checkVersion("2.1.12");
$ppu->doUpload();

if (isset($editFormAction)) {
  if (isset($_SERVER['QUERY_STRING'])) {
	  if (!eregi("GP_upload=true", $_SERVER['QUERY_STRING'])) {
  	  $editFormAction .= "&GP_upload=true";
		}
  } else {
    $editFormAction .= "?GP_upload=true";
  }
}
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

<?php include '../../admin_xx_includes/db_nav.php';?>


	      <table width="789" align="center" class="tst_page_txt">
        <tr>
          <td class="red_bold">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="630" class="red_bold">Upload an attachment to be sent with a bulk email</td>
          <td width="147"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
        </tr>
        <tr>
          <td colspan="2" align="center">Click the &quot;Browse&quot; button and select the attachment from your filing system.</td>
        </tr>
        <tr>
          <td colspan="2" align="center" class="red_text">IMPORTANT - Selected file must be a pdf file and less than 600kb or file will not upload</td>
        </tr>
        <tr>
          <td colspan="2" align="center">After upload is successful you will be returned to the &quot;Clubs Index&quot; page</td>
        </tr>
      </table>
      <form action="<?php echo $GP_uploadAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'pdf',true,600,'','','','','','');return document.MM_returnValue">
      <table align="center" class="tst_page_txt">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle">Please select file: </td>
          <td>
            <input name="file_up" type="file" id="file_up" onchange="checkOneFileUpload(this,'pdf',true,600,'','','','','','')" size="50" />
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" name="submit" id="submit" value="Upload your file" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      </form>
</body>
</html>
