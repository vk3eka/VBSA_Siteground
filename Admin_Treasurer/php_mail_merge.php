<?php 
function mkpath($path){
    if(@mkdir($path) || file_exists($path)){return true;}else{return false;}
}
if(isset($_POST["template_name"]) && $_POST["template_name"]!=""){
	$allowed_extensions=array("txt","htm","html","php");
	$file = "templates/".$_POST['template_name']; 
	//check for extension and add htm if it's invalid or no extension
	$pathinfo = pathinfo($file);
	if(!$pathinfo['extension']){
		$file=$file.".htm";
		$pathinfo['extension']="htm";
	}
		//Refuse if the extension is not allowed
		if (!in_array(strtolower($pathinfo['extension']),$allowed_extensions)){
				echo "The selected file extension is not allowed for security reasons.";
		} else{
			$openfile = @fopen($file, 'w');
			if (!$openfile) { 
			//Error - cannot write to the file
				if(file_exists($file)){
					echo "The file is read-only.\nEither manually set \"write\" permission for this file\non the server or save it under different name.";
				}else{
					echo "Could not save the file: ".$file;
				}
			} else {
				//All good- can write to the file
				$content = stripslashes($_POST['content']);
				fwrite($openfile, $content);
				fclose($openfile); 
				echo "The file has been saved"; 
			}
		}
	die();
}
if(isset($_GET["template_open"]) && $_GET["template_open"]!=""){
	header("Location:templates/".$_GET["template_open"]);
}
if(isset($_GET["mode"]) && ($_GET["mode"]=="openTemplate" || $_GET["mode"]=="saveTemplate")){
	$module="templates";
	$button_label="Open";
	$page_title="Open template from the server";
	$readonly='readonly="true"';
	if($_GET["mode"]=="saveTemplate"){
		$button_label="Save";
		$page_title="Save template to the server";
		$readonly="";
	}
	echo "<html><head><title>$page_title</title>";
?>
<script>
var last;
function selectRow(what, fn){
	if(last!=undefined){
		last.style.backgroundColor="Window";
		last.style.color="WindowText";
	}
      document.getElementById("File_Name").value=fn;
	  document.getElementById("preview").src="<?php echo $module; ?>/"+fn;
	  document.getElementById("preview").style.display="";
	  document.getElementById("before_preview").style.display="none";
      what.style.backgroundColor="Highlight";
      what.style.color="ButtonHighlight";
      last=what;
}

function passValue(v){
	if(v!=""){
		if(window.opener!=null){
			with(window.opener){
		<?php if(isset($_GET['mode']) && $_GET['mode']=="saveTemplate"){?>
				saveTemplate(v);
		<?php } else{ ?>
				openTemplate(v);
		<?php } ?>
			}
		}
		window.close();
	}
}
<?php $target = $module."/"; 
	//If trying to save but not able to
	if((!mkpath($target) || !is_writable($target)) && $_GET['mode']=="saveTemplate"){?>
		alert("The <?php echo $module; ?> directory doesn't exist or is read-only.\nYou will not be able to save the file.");
<?php }?>
</script>
<style>
th{
border-top: 1px window solid;
border-left: 1px window solid;
border-right: 1px buttonshadow solid;
border-bottom: 2px buttonshadow solid;
font-weight:normal;
}
table{
 font-family: Tahoma;
 font-size:11px;
}
</style>
<?php echo "</head><body leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" style=\"background-color: ButtonFace; font-family: Tahoma; font-size: 11px\">";?>
<form id="template_manager" name="template_manager" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="480"><div id="file_list" style="background-color:window; width: 480px; height: 320px; overflow:auto; border: 1px #7F9DB9 solid">
        <table id="file_grid" width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr style="background-color: buttonface;">
            <th align="left" nowrap="nowrap" style="">Name</th>
            <th align="right" nowrap="nowrap">Size</th>
            <th align="left" nowrap="nowrap">Date Modified </th>
          </tr>
          <?php
$allowed_extensions=array("txt","htm","html","php");
$templatelist = @opendir($module);

while($template = @readdir($templatelist)){ 
	$pathinfo = @pathinfo($template);
	if(in_array(strtolower($pathinfo['extension']),$allowed_extensions)){
?>
          <tr onclick="selectRow(this,'<?php echo basename($template); ?>')" ondblclick="passValue('<?php echo basename($template); ?>')" style="cursor:default">
            <td><img src="<?php echo "php_mail_merge/".strtolower($pathinfo['extension']) ?>.png"  width="16" height="16" align="absmiddle" /> <?php echo basename($template); ?></td>
            <td><?php echo number_format(filesize($module."/".basename($template))/1024)." KB"; ?></td>
            <td><?php echo date("m/d/y H:i:s", filemtime($module."/".basename($template))) ?></td>
          </tr>
          <?php		
	}
}
@closedir($templatelist);
?>
        </table>
      </div>
          <table width="480" border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td width="80" height="27" nowrap="nowrap">File name: </td>
            <td width="100%">
                <input name="File_Name" type="text" id="File_Name" style="width: 100%" <?php echo $readonly; ?> />
            </td>
            <td nowrap="nowrap"><input name="Open_btn" type="button" id="Open_btn" value="<?php echo $button_label; ?>" onclick="passValue(document.getElementById('File_Name').value)"  style="width: 90px" />
              <input name="Cancel_btn" type="button" id="Cancel_btn" onclick="window.close()" value="Cancel" style="width: 90px" /></td>
            </tr>
        </table></td>
      <td align="center"><iframe src="about:blank" width="100%" height="344" scrolling="auto" id="preview" style="display: none;background-color:#FFFFFF"></iframe>
          <span id="before_preview"><b>Preview</b></span></td>
    </tr>
  </table>
</form>
<?php echo "</body></html>";
die();
}
?>
<?php

if(isset($_GET["mode"]) && $_GET["mode"]=="uploadImage"){
	$allowed_extensions=array("jpeg","gif","png","jpg");
	$module="images";
	$target = $module."/"; 
	$status = "";
	if(!mkpath($target) || !is_writable($target)){
		$status="<script>alert('The \"".$module."\" directory does not exist or is not writable: ".$module." cannot be uploaded');window.close()</script>";
	}
	if(isset($_POST['img_put']) && $_POST['img_put']=="Upload"){
		$target = $target.basename( $_FILES['File']['name']);
		$pathinfo = pathinfo($_FILES['File']['name']);
		//verify extension
		if(in_array(strtolower($pathinfo['extension']),$allowed_extensions)){ 
			if(move_uploaded_file($_FILES['File']['tmp_name'], $target)){
				@chmod($target, 0777);
				$status = basename($_FILES['File']['name']). " has been uploaded.<br>You can upload another image or <a href=\"javascript:insertImage('/".$module."/".basename( $_FILES['File']['name'])."')\">insert</a> the image you have just uploaded.";
			}else{
				$status = "There was a problem uploading ". basename( $_FILES['File']['name']).".";
			}
		}else{
			$status = "The selected file was not an image.";
		}
	}
?>
<?php echo "<html>";?>
<script language="javascript">
function insertImage(image_path){
	with(window.opener){
		insert_image(location.href.substring(0,location.href.lastIndexOf("/"))+image_path);
	}
	window.close();
}
</script>
<?php echo "<head><title>Upload $module</title></head><body style=\"background-color: ButtonFace; font-family: Tahoma; font-size: 11px\">";?>

<?php echo $status; ?>
<br /><form id="up_form" name="up_form" enctype="multipart/form-data" method="post" action="">
  <fieldset>
  <legend>Upload Images</legend>
  <input type="file" name="File" id="File" />
  <br />
  <input type="submit" name="img_put" id="img_put" value="Upload" />
  <input type="button" name="Cancel" id="Cancel" value="Cancel" onclick="window.close()" />
  </fieldset>
  </form>
<?php echo "</body></html>";
die();
} ?>
<?php if(isset($_GET["mode"]) && $_GET["mode"]=="insertImage"){
$module="images";
echo "<html>";?>
<script language="javascript">
var last;
function selectRow(what, fn){
	document.getElementById("OK").disabled=false;
	if(last!=undefined){
		last.style.backgroundColor="Window";
		last.style.color="WindowText";
	}
      document.getElementById("File_Name").value=fn;
      what.style.backgroundColor="Highlight";
      what.style.color="ButtonHighlight";
      last=what;
	  document.getElementById("preview").src="<?php echo $module; ?>/"+fn;
	  document.getElementById("preview").style.display="";
	  document.getElementById("before_preview").style.display="none";
}
function insertImage(image_path){
	with(window.opener){
		insert_image(location.href.substring(0,location.href.lastIndexOf("/"))+image_path);
	}
	window.close();
}
</script>
<style>
th{
border-top: 1px window solid;
border-left: 1px window solid;
border-right: 1px buttonshadow solid;
border-bottom: 2px buttonshadow solid;
font-weight:normal;
}
table{
 font-family: Tahoma;
 font-size:11px;
}
</style>
<?php echo "<head><title>Insert image from the server</title></head><body leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" style=\"background-color: ButtonFace;font-family: Tahoma; font-size: 11px\">";?>
<form id="pick_image" name="pick_image" method="post" action="">
  <table width="740" border="0" cellspacing="0" cellpadding="4" style="font-family: Tahoma; font-size: 11px">
    <tr>
      <td width="300"><div style="background-color:Window; border:1px inset ButtonFace; height: 400px; width: 300px; overflow:auto">
        <table width="100%" border="0" cellspacing="0" cellpadding="3" style="font-family: Tahoma; font-size: 11px">
          <tr style="background-color: buttonface;">
            <th align="left" nowrap="nowrap" style="">Name</th>
            <th align="right" nowrap="nowrap">Size</th>
            <th align="left" nowrap="nowrap">Date Modified </th>
          </tr>

  <?php
$allowed_extensions=array("jpeg","gif","png","jpg");
$images_folder = $module;
$imagelist = @opendir($images_folder);

while($image = @readdir($imagelist)){ 
	$pathinfo = @pathinfo($image);
	if(in_array(strtolower($pathinfo['extension']),$allowed_extensions)){
?>
          <tr onclick="selectRow(this,'<?php echo basename($image); ?>')" ondblclick="insertImage('/<?php echo $module ."/". basename($image); ?>')" style="cursor:default">
            <td><nobr><img src="<?php echo "php_mail_merge/".strtolower($pathinfo['extension']) ?>.png"  width="16" height="16" align="absmiddle" /> <?php echo htmlentities(basename($image)); ?></nobr></td>
            <td><nobr><?php echo number_format(filesize($module."/".basename($image))/1024)." KB"; ?></nobr></td>
            <td><nobr><?php echo date("m/d/y H:i:s", filemtime($module."/".basename($image))) ?></nobr></td>
          </tr>
          <?php		
	}
}
@closedir($imagelist);
?>
        </table>
      </div></td>
      <td align="center"><iframe src="" width="100%" height="400" scrolling="auto" id="preview" style="display: none"></iframe>
      <span id="before_preview"><b>Preview</b></span></td>
    </tr>
    <tr>
      <td><input type="hidden" name="File_Name" id="File_Name" /></td>
      <td align="right" valign="top"><input type="button" name="OK" id="OK" value="OK" disabled="disabled" onclick="insertImage('/<?php echo $module ?>/'+document.getElementById('File_Name').value)" />
        <input type="button" name="Cancel" id="Cancel" value="Cancel" onclick="window.close()" /></td>
    </tr>
  </table>
</form>
<?php echo "</body></html>";
die();
}?>
<?php if(isset($_GET["mode"]) && $_GET["mode"]=="addFile"){
	$module="files";
	$button_label="Add";
	$page_title="Add attachment";
	$readonly='readonly="true"';
	$target = $module."/";
	echo("<html><head><title>" . $page_title . "</title>");
?><script>
var last;
function selectRow(what, fn){
	if(last!=undefined){
		last.style.backgroundColor="Window";
		last.style.color="WindowText";
	}
	
      fn="<?php echo $module;?>/" + fn;
      document.getElementById("File_Name").value=fn;
      what.style.backgroundColor="Highlight";
      what.style.color="ButtonHighlight";
      last=what;
}

function addAttachment(v){
	if(v!=""){
		if(window.opener!=null){
			with(window.opener){
				document.getElementById('FileAttachment').value=v;
			}
		}
		window.close();
	}
}
<?php
	//If trying to save but not able to
if(!mkpath($target) || !is_writable($target)){?>	
		alert("The <?php echo $module;?> directory doesn't exist.");
<?php die();} ?>
</script>
<style>
th{
border-top: 1px window solid;
border-left: 1px window solid;
border-right: 1px buttonshadow solid;
border-bottom: 2px buttonshadow solid;
font-weight:normal;
}
table{
 font-family: Tahoma;
 font-size:11px;
}
</style>
<?php echo "</head><body leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" style=\"background-color: ButtonFace; font-family: Tahoma; font-size: 11px\">";?>
<?php echo("<form id='file_manager' name='file_manager' method='post'>");?>  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="480"><div id="file_list" style="background-color:window; width: 480px; height: 320px; overflow:auto; border: 1px #7F9DB9 solid">
        <table id="file_grid" width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr style="background-color: buttonface;">
            <th align="left" nowrap="nowrap" style="">Name</th>
            <th align="right" nowrap="nowrap">Size</th>
            <th align="left" nowrap="nowrap">Date Modified </th>
          </tr>
           <?php
$filelist = @opendir($module);
while($file = @readdir($filelist)){ 
	$pathinfo = @pathinfo($file);
		if($pathinfo['extension']!=""){
			if(file_exists("php_mail_merge/".strtolower($pathinfo['extension']).".png")){
				$icon_file="php_mail_merge/".strtolower($pathinfo['extension']).".png";
			}else{
				$icon_file="php_mail_merge/file.png";
			}
?>
          <tr onclick="selectRow(this,'<?php echo basename($file); ?>')" ondblclick="passValue('<?php echo basename($file); ?>')" style="cursor:default">
            <td><img src="<?php echo $icon_file; ?>"  width="16" height="16" align="absmiddle" /> <?php echo basename($file); ?></td>
            <td><?php echo number_format(filesize($module."/".basename($file))/1024)." KB"; ?></td>
            <td><?php echo date("m/d/y H:i:s", filemtime($module."/".basename($file))) ?></td>
          </tr>
          <?php	
		}
}
@closedir($filelist);
?>         
        </table>
      </div>
<table width="480" border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td width="80" height="27" nowrap="nowrap">File name: </td>
    <td width="100%">
        <input name="File_Name" type="text" id="File_Name" style="width: 100%" <?php echo $readonly;?> />
    </td>
    <td nowrap="nowrap"><input name="Open_btn" type="button" id="Open_btn" value="<?php echo $button_label;?>" onclick="addAttachment(document.getElementById('File_Name').value)"  style="width: 90px" />
      <input name="Cancel_btn" type="button" id="Cancel_btn" onclick="window.close()" value="Cancel" style="width: 90px" /></td>
    </tr>
</table></td>
    </tr>
  </table>
<?php echo ("</form></body></html>");?>
<?php
die();
//End add attachment
}?> 
<!--[if IE]>
<style>
html, body{
      height: 100%;
}
</style>
<![endif]-->
<script language="javascript">
var screenHeight = document.body.offsetHeight;
window.onerror=function(){return true;}
</script>
<div id="status_viewer">
  <table width="100" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" id="table_holder"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="7" background="php_mail_merge/panel_cleft.png"><img src="php_mail_merge/panel_cleft.png" width="7" height="28"  /></td>
          <td width="484" background="php_mail_merge/panel_back.png"><span id="in_progress"> Operation in progress, please wait ...</span></td>
          <td width="32" background="php_mail_merge/panel_back.png"><img src="php_mail_merge/collapse.png" title="Hide dialog" width="16" height="16" onclick="minimizePopup()" /><img src="php_mail_merge/close.png" title="Close" width="16" height="16" onclick="closePopup()" /></td>
          <td width="7" background="panel_cright.png"><img src="php_mail_merge/panel_cright.png" width="7" height="28"  /></td>
        </tr>
      </table>
    <table width="520"  align="center" cellpadding="3" cellspacing="0" id="dialogbox"  onmousedown='document.getElementById("palette_div").style.visibility="hidden";'>
            <tr>
              <td align="left"><div align="center" style="padding-bottom: 2px">
                  <button id="StartOver" type="button" onclick="if(confirm('This will stop current operation. Continue?')){location.replace(location.href)}" class="interface_button" style="width: 86px" onmousedown="flipClass(this, 'interface_button down')" onmouseup="flipClass(this, 'interface_button on')" onmouseover="flipClass(this, 'interface_button on')" onmouseout="flipClass(this, 'interface_button')" title="Stop and Start over"><img src="php_mail_merge/first_email.png" width="17" height="16" align="absmiddle" /> Start over</button>
                  <button type="button" id="Pause" class="interface_button" style="width: 86px;" disabled="disabled" onmousedown="flipClass(this, 'interface_button down')" onmouseup="flipClass(this, 'interface_button on')" onmouseover="flipClass(this, 'interface_button on')" onmouseout="flipClass(this, 'interface_button')" title="Pause Cycle"  onclick="sendOnOff(this)"><img src="php_mail_merge/pause_email.png" width="17" height="16" align="absmiddle" /> Delay</button>
                  <button type="button" id="Stop" class="interface_button" style="width: 86px;" onmousedown="flipClass(this, 'interface_button down')" onmouseup="flipClass(this, 'interface_button on')" onmouseover="flipClass(this, 'interface_button on')" onmouseout="flipClass(this, 'interface_button')" title="Stop"  onclick="stopSend()"><img src="php_mail_merge/stop_email.png" width="17" height="16" align="absmiddle" /> Stop</button><button id="Skip" type="button" class="interface_button" style="width: 86px" onmousedown="flipClass(this, 'interface_button down')" onmouseup="flipClass(this, 'interface_button on')" onmouseover="flipClass(this, 'interface_button on')" onmouseout="flipClass(this, 'interface_button')" onclick="skipNext(1)" title="Skip recepient, go to next one"><img src="php_mail_merge/next_email.png" width="17" height="16" align="absmiddle" /> Skip/ Next</button>
                </div>
                  <div style="border: 1px inset ThreedShadow; padding: 1px; height: 14px">
                    <div id="progress_bar" style="width: 1px; background-color:#0000CC; height:13px"></div>
                  </div>
                <div style="height: 22px; margin-top: 8px"><span id="send_status" style="visibility:hidden">&nbsp;</span><span id="num_recepients"></span><span id="start_num"></span></div>
                <table width="520" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF" id="summary">
                    <tr>
                      <td valign="top"><fieldset style="height:100%">
                        <legend><b>Summary</b></legend>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                          <tr>
                            <td width="50%" valign="top"><div id="summary_content"> <br />
                                    <img src="php_mail_merge/important.gif" width="10" height="10" align="absmiddle" /> Total <span id="was_sent"></span>&nbsp; out of <span id="was_to_send"></span>&nbsp; messages has been sent - <b><span id="progress_percent"></span></b>&nbsp; done.
                              <div id="errors_summary" style="visibility: hidden"> <img src="php_mail_merge/important.gif" width="10" height="10" align="absmiddle" /> Errors occurred while sending messages to the following recipients:
                                <textarea name="errors" id="errors" style="width: 98%;" rows="6"></textarea>
                                        <br />
                                    </div>
                            </div></td>
                          </tr>
                        </table>
                      </fieldset></td>
                    </tr>
                </table></td>
            </tr>
        </table></td>
    </tr>
  </table>
</div>
<div id="shield"><!--[if lte IE 7]><iframe src="" style="height: 100%; width: 100%;"></iframe><![endif]--></div>
  <div id="palette_div" style="position:absolute; left:0px; top:0px;padding: 3px; z-index:1">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:3px">
                <tr>
                  <td id="swatch" width="72" style="border:2px solid black">&nbsp;</td>
                  <td width="90" align="center" id="colorValue">&nbsp;</td>
                  <td width="11" align="center"><img unselectable="on" src="php_mail_merge/nocolor.gif" width="11" height="11" onClick="clearColor()"></td>
                </tr>
  </table>
<table border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000" id="palette">
	<tr><td bgcolor="#000000"></td><td bgcolor="#000000"></td><td bgcolor="#003300"></td><td bgcolor="#006600"></td><td bgcolor="#009900"></td><td bgcolor="#00CC00"></td><td bgcolor="#00FF00"></td><td bgcolor="#330000"></td><td bgcolor="#333300"></td><td bgcolor="#336600"></td><td bgcolor="#339900"></td><td bgcolor="#33CC00"></td><td bgcolor="#33FF00"></td><td bgcolor="#660000"></td><td bgcolor="#663300"></td><td bgcolor="#666600"></td><td bgcolor="#669900"></td><td bgcolor="#66CC00"></td><td bgcolor="#66FF00"></td></tr><tr><td bgcolor="#333333"></td><td bgcolor="#000033"></td><td bgcolor="#003333"></td><td bgcolor="#006633"></td><td bgcolor="#009933"></td><td bgcolor="#00CC66"></td><td bgcolor="#00FF33"></td><td bgcolor="#330033"></td><td bgcolor="#333333"></td><td bgcolor="#336633"></td><td bgcolor="#339933"></td><td bgcolor="#33CC33"></td><td bgcolor="#33FF33"></td><td bgcolor="#660066"></td><td bgcolor="#663333"></td><td bgcolor="#666633"></td><td bgcolor="#669933"></td><td bgcolor="#66CC33"></td><td bgcolor="#66FF33"></td></tr><tr><td bgcolor="#666666"></td><td bgcolor="#000066"></td><td bgcolor="#003366"></td><td bgcolor="#006666"></td><td bgcolor="#009966"></td><td bgcolor="#00CC66"></td><td bgcolor="#00FF66"></td><td bgcolor="#330066"></td><td bgcolor="#333366"></td><td bgcolor="#336666"></td><td bgcolor="#339966"></td><td bgcolor="#33CC66"></td><td bgcolor="#33FF66"></td><td bgcolor="#660066"></td><td bgcolor="#663366"></td><td bgcolor="#666666"></td><td bgcolor="#669966"></td><td bgcolor="#66CC66"></td><td bgcolor="#66FF66"></td></tr><tr><td bgcolor="#999999"></td><td bgcolor="#000099"></td><td bgcolor="#003399"></td><td bgcolor="#006699"></td><td bgcolor="#009999"></td><td bgcolor="#00CC99"></td><td bgcolor="#00FF99"></td><td bgcolor="#330099"></td><td bgcolor="#333399"></td><td bgcolor="#336699"></td><td bgcolor="#339999"></td><td bgcolor="#33CC99"></td><td bgcolor="#33FF99"></td><td bgcolor="#660099"></td><td bgcolor="#663399"></td><td bgcolor="#666699"></td><td bgcolor="#669999"></td><td bgcolor="#66CC66"></td><td bgcolor="#66FF99"></td></tr><tr><td bgcolor="#CCCCCC"></td><td bgcolor="#0000CC"></td><td bgcolor="#0033CC"></td><td bgcolor="#0066CC"></td><td bgcolor="#0099CC"></td><td bgcolor="#00CCCC"></td><td bgcolor="#00FFCC"></td><td bgcolor="#3300CC"></td><td bgcolor="#3333CC"></td><td bgcolor="#3366CC"></td><td bgcolor="#3399CC"></td><td bgcolor="#33CCCC"></td><td bgcolor="#33FFCC"></td><td bgcolor="#6600CC"></td><td bgcolor="#6633CC"></td><td bgcolor="#6666CC"></td><td bgcolor="#6699CC"></td><td bgcolor="#66CCCC"></td><td bgcolor="#66FFCC"></td></tr><tr><td bgcolor="#FFFFFF"></td><td bgcolor="#0000FF"></td><td bgcolor="#0033FF"></td><td bgcolor="#0066FF"></td><td bgcolor="#0099FF"></td><td bgcolor="#00CCFF"></td><td bgcolor="#00FFFF"></td><td bgcolor="#3300FF"></td><td bgcolor="#3333FF"></td><td bgcolor="#3366FF"></td><td bgcolor="#3399FF"></td><td bgcolor="#33CCFF"></td><td bgcolor="#33FFFF"></td><td bgcolor="#6600FF"></td><td bgcolor="#6633FF"></td><td bgcolor="#6666FF"></td><td bgcolor="#6699FF"></td><td bgcolor="#66CCFF"></td><td bgcolor="#66FFFF"></td></tr><tr><td bgcolor="#FF0000"></td><td bgcolor="#990000"></td><td bgcolor="#993300"></td><td bgcolor="#996600"></td><td bgcolor="#999900"></td><td bgcolor="#99CC00"></td><td bgcolor="#99FF00"></td><td bgcolor="#CC0000"></td><td bgcolor="#CC3300"></td><td bgcolor="#CC6600"></td><td bgcolor="#CC9900"></td><td bgcolor="#CCCC00"></td><td bgcolor="#CCFF00"></td><td bgcolor="#FF0000"></td><td bgcolor="#FF3300"></td><td bgcolor="#FF6600"></td><td bgcolor="#FF9900"></td><td bgcolor="#FFCC00"></td><td bgcolor="#FFFF00"></td></tr><tr><td bgcolor="#00FF00"></td><td bgcolor="#990033"></td><td bgcolor="#993333"></td><td bgcolor="#996633"></td><td bgcolor="#999933"></td><td bgcolor="#99CC66"></td><td bgcolor="#99FF33"></td><td bgcolor="#CC0033"></td><td bgcolor="#CC3333"></td><td bgcolor="#CC6633"></td><td bgcolor="#CC9933"></td><td bgcolor="#CCCC33"></td><td bgcolor="#CCFF33"></td><td bgcolor="#FF0033"></td><td bgcolor="#FF3333"></td><td bgcolor="#FF6633"></td><td bgcolor="#FF9933"></td><td bgcolor="#FFCC33"></td><td bgcolor="#FFFF33"></td></tr><tr><td bgcolor="#0000FF"></td><td bgcolor="#990066"></td><td bgcolor="#993366"></td><td bgcolor="#996666"></td><td bgcolor="#999966"></td><td bgcolor="#99CC66"></td><td bgcolor="#99FF66"></td><td bgcolor="#CC0066"></td><td bgcolor="#CC3366"></td><td bgcolor="#CC6666"></td><td bgcolor="#CC9966"></td><td bgcolor="#CCCC66"></td><td bgcolor="#CCFF66"></td><td bgcolor="#FF0066"></td><td bgcolor="#FF3366"></td><td bgcolor="#FF6666"></td><td bgcolor="#FF9966"></td><td bgcolor="#FFCC66"></td><td bgcolor="#FFFF66"></td></tr><tr><td bgcolor="#FFFF00"></td><td bgcolor="#990099"></td><td bgcolor="#993399"></td><td bgcolor="#996699"></td><td bgcolor="#999999"></td><td bgcolor="#99CC99"></td><td bgcolor="#99FF99"></td><td bgcolor="#CC0099"></td><td bgcolor="#CC3399"></td><td bgcolor="#CC6699"></td><td bgcolor="#CC9999"></td><td bgcolor="#CCCC99"></td><td bgcolor="#CCFF99"></td><td bgcolor="#FF0099"></td><td bgcolor="#FF3399"></td><td bgcolor="#FF6699"></td><td bgcolor="#FF9999"></td><td bgcolor="#FFCC99"></td><td bgcolor="#FFFF99"></td></tr><tr><td bgcolor="#00FFFF"></td><td bgcolor="#9900CC"></td><td bgcolor="#9933CC"></td><td bgcolor="#9966CC"></td><td bgcolor="#9999CC"></td><td bgcolor="#99CCCC"></td><td bgcolor="#99FFCC"></td><td bgcolor="#CC00CC"></td><td bgcolor="#CC33CC"></td><td bgcolor="#CC66CC"></td><td bgcolor="#CC99CC"></td><td bgcolor="#CCCCCC"></td><td bgcolor="#CCFFCC"></td><td bgcolor="#FF00CC"></td><td bgcolor="#FF33CC"></td><td bgcolor="#FF66CC"></td><td bgcolor="#FF99CC"></td><td bgcolor="#FFCCCC"></td><td bgcolor="#FFFFCC"></td></tr><tr><td bgcolor="#FF00FF"></td><td bgcolor="#9900FF"></td><td bgcolor="#9933FF"></td><td bgcolor="#9966FF"></td><td bgcolor="#9999FF"></td><td bgcolor="#99CCFF"></td><td bgcolor="#99FFFF"></td><td bgcolor="#CC00FF"></td><td bgcolor="#CC33FF"></td><td bgcolor="#CC66FF"></td><td bgcolor="#CC99FF"></td><td bgcolor="#CCCCFF"></td><td bgcolor="#CCFFFF"></td><td bgcolor="#FF00FF"></td><td bgcolor="#FF33FF"></td><td bgcolor="#FF66FF"></td><td bgcolor="#FF99FF"></td><td bgcolor="#FFCCFF"></td><td bgcolor="#FFFFFF"></td>
	</tr>
</table>
</div>
  <table width="960" border="0" align="center" cellpadding="3" cellspacing="0" id="dialogbox">
    <tr>
      <td> Start sending from record
        <input name="StartNumber" type="text" id="StartNumber" value="1" size="4" maxlength="10" />
        &nbsp;&nbsp;&nbsp;
        Pause  for
        <input name="delay" type="text" id="delay" value="" size="4" maxlength="6" />
        minute(s) after every
        <input name="NumOfRecords" type="text" id="NumOfRecords" size="4" maxlength="8" />
        recipients
        <div id="total_messages"><br />
          <img src="php_mail_merge/important.gif" alt="" width="10" height="10" align="absmiddle" /> Total of <span id="total_recipients"><?php echo $myTotalRecords ?></span> messages pending. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          <label><b>Show filters:</b>
            <input name="show_filters" type="checkbox" id="show_filters" onclick="showFilters(this)" value="1" />
          </label>
        </div></td>
    </tr>
  </table>
<table width="960" style="height:594px"  align="center" cellpadding="1" cellspacing="0" id="dialogbox"  onMouseDown='document.getElementById("palette_div").style.visibility="hidden";'>
  <tr>
    <td colspan="4" style="border-bottom: 1px solid ThreedShadow;border-top: 1px solid ThreedHighlight"><table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="1" align="center" valign="middle" style="padding:0px"><div class="barhandle"></div></td>
          <td width="60" align="right"><b>From: &nbsp;</b></td>
          <td nowrap><input name="From" type="text" id="From" style="width: 250px" title="Enter your email address as you want it in the &quot;From&quot; field" value="">
&nbsp;<b>&nbsp;Name:</b>
            <input name="FromName" type="text" id="FromName" style="width: 250px" title="Enter your name as you want it in the &quot;From&quot; field">
            <b>&nbsp;</b></td>
          <td align="right" nowrap><b>Reply to:</b>
          <input name="ReplyTo" type="text" id="ReplyTo" style="width: 250px" title="Enter the return email address if it is not the same as the From address" /></td>
        </tr>
    </table></td>
  </tr>
  <tr >
    <td colspan="4" style="border-bottom: 1px solid ThreedShadow;border-top: 1px solid ThreedHighlight"><table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="1" align="center" valign="middle" style="padding:0px"><div class="barhandle"></div></td>
          <td width="60" align="right"><b>To: &nbsp;</b></td>
          <td><input name="To" type="text" id="To" style="width: 250px"  title="Enter single static email address (Test mode) or insert dynamic value using Recordset fields toolbar." <?php if((!isset($_POST["SendMode"])) || ($_POST["SendMode"]!="Test")){ ?>readonly="readonly"<?php } ?>>
          <label for="Validate"> <b>Validate:</b></label>
          <input name="Validate" type="checkbox" id="Validate" value="1"  title="Validate each email before sending." /></td>
          <td align="right"><b>Return path:</b>
          <input name="ReturnPath" type="text" id="ReturnPath" style="width: 250px" title="Enter the bounce-back email address if required by your server" /></td>
        </tr>
    </table></td>
  </tr>
  <tr >
    <td colspan="4" style="border-bottom: 1px solid ThreedShadow;border-top: 1px solid ThreedHighlight"><table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="1" align="center" valign="middle" style="padding:0px"><div class="barhandle"></div></td>
          <td width="60" align="right"><b>Subject: &nbsp;</b></td>
          <td><input name="Subject" type="text" id="Subject" style="width: 482px" title="Message Subject">
          &nbsp;&nbsp;</td>
          <td align="right"><b>Encoding:</b>
            <select name="encoding" id="encoding"  style="width: 250px" title="Select character encoding corresponding to the message language">
              <option value="utf-8" selected="selected">Unicode (UTF-8)</option>
              <option value="utf-16">Unicode (UTF-16)</option>
              <option value="iso-8859-1">ISO 8859-1</option>
              <option value="iso-8829-2">ISO 8829-2</option>
          </select></td>
        </tr>
        
    </table></td>
  </tr>
  <?php if($pear_enabled){?><tr >
    <td colspan="4" style="border-bottom: 1px solid ThreedShadow;border-top: 1px solid ThreedHighlight"><table width="100%" cellpadding="0" cellspacing="0">
      <tr id="attachment_tr">
        <td width="1" align="center" valign="middle" style="padding:0px"><div class="barhandle"></div></td>
        <th width="60" align="right"><b>File:</b> &nbsp;</th>
        <th align="left"><input type="text" name="FileAttachment" id="FileAttachment" style="width:60%" title="Type the absolute path to the file on the server" />
          <button type="button" onclick="openAddFile()">Browse...</button>   &nbsp;&nbsp;&nbsp;</th>
        <th align="right"><label> <img src="php_mail_merge/smtp.gif" width="16" height="16" align="absmiddle" /> <b>Show SMTP info:</b> <input name="show_smtp" type="checkbox" id="show_smtp" onclick="showSMTP(this)" value="1" /></label></th>
      </tr>
    </table>
      <table width="100%" cellpadding="0" cellspacing="0" id="smtp_table" style="display:none">
        <tr>
          <td width="1" align="center" valign="middle" style="padding:0px"><div class="barhandle"></div></td>
          <th width="60" align="right"><b>Host:</b> &nbsp;</th>
          <th align="left"><input type="text" name="Host" id="Host" title="Enter SMTP Host" /> 
            &nbsp;<b>User name:</b>
            <input type="text" name="UserName" id="UserName" title="Enter SMTP User Name" />
            &nbsp;<b>Password:</b>            <input type="text" name="Password" id="Password" title="Enter SMTP password" /></th>
        </tr>
    </table></td>
  </tr><?php } ?>
  <tr >
    <td colspan="4" style="border-bottom: 1px solid ThreedShadow;border-top: 1px solid ThreedHighlight;"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="1" align="center" valign="middle" style="padding:0px"><div class="barhandle"></div></td>
          <td nowrap style="padding: 3px" title="Select dynamic field"><b> &nbsp;Recordset fields:</b>
              <select name="RecordsetFields" title="Select a dynamic field">
                <?php 	
	  $totalColumns=mysql_num_fields($myRecordset);
	  for ($x=0; $x<$totalColumns; $x++) {?>
                <option value="##<?php echo mysql_field_name($myRecordset, $x); ?>##"><?php echo mysql_field_name($myRecordset, $x); ?></option>
                <?php } ?>
              </select>
&nbsp;
            <button type="button" valign="middle" class="interface_button" onClick="with(document.editor_form){if(SendMode.checked){SendMode.checked=false;To.readOnly=true;}To.value=RecordsetFields.value}" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/dynamic_e.gif" width="17" height="17" align="absbottom" title="Insert in the &quot;To&quot; field"></button>
            <button type="button" valign="middle" class="interface_button" onClick="with(document.editor_form){Subject.value+=RecordsetFields.value}" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/subject.gif" width="17" height="17" align="absbottom" title="Insert in the &quot;Subject&quot; field"></button>
            <button type="button" valign="middle" class="interface_button" onClick="insert_link(document.editor_form.RecordsetFields.value,false);" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/dynamic_link.gif" width="17" height="17" align="absbottom" title="Insert Dynamic Link"></button>
            <button type="button" onMouseOver="flipClass(this, 'interface_button on')" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')" class="interface_button" onClick="insertObject(document.editor_form.RecordsetFields.value);"><img unselectable="on" src="php_mail_merge/dynamic_t.gif" width="17" height="17" align="absbottom" title="Insert in the message box "> </button>
          <td width="4" align="center" valign="middle" class="separator_r">&nbsp;</td>
          <td width="4" align="center" valign="middle" class="separator_l">&nbsp;</td>
          <th align="right"><label for="SendAs">Send as</label>: </th>
          <td style="padding-left:0px "><label>
            <input type="checkbox" name="SendAs" id="SendAs" value="Plain Text" onClick="flipMessageFormat(this)" title="Send as Plain Text (not HTML)">
            Plain Text</label>
&nbsp;&nbsp;&nbsp;&nbsp;
            <label>
            <input id="SendMode" type="checkbox" name="SendMode" value="Test" onClick="testMode(this)" title="Send a test to a single (specified) email address">
            Test</label></td>
          <td align="right" style="padding-left:0px "><b>Base URL:</b>  <input style="width: 250px" name="BaseURL" type="text" id="BaseURL" title="The Base URL an email application should use to download HTML message files (images, style sheets, etc.)"></td>
        </tr>
    </table></td>
  </tr>
  <tr valign="top">
    <td colspan="4" style="border-bottom: 1px solid ThreedShadow;border-top: 1px solid ThreedHighlight"><table id="format_bar"  border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td width="1" align="center" valign="middle" style="padding:0px"><div class="barhandle"></div></td>
          <td width="5">&nbsp;</td>
          <td width="60" valign="middle"><button type="button" id="Send" class="interface_button" style="width: 60px !important; visibility:visible !important; margin:"  onClick="sendMessage();" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')" title="Send the message"><img src="php_mail_merge/send.gif" width="17" height="12" align="absbottom"> Send</button></td>
          <td width="4" class="separator_r">&nbsp;</td>
          <td width="4" class="separator_l">&nbsp;</td>
          <td nowrap="nowrap"><button type="button" class="interface_button" onClick="makeBold();buttonState(this.id)" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="sp_buttonState(this)" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="buttonState(this.id)"  id="bold"><img unselectable="on" src="php_mail_merge/bold.gif" title="Bold" width="16" height="16" align="absbottom"></button><button type="button" class="interface_button" onClick="makeItalic();buttonState(this.id)" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="sp_buttonState(this)" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="buttonState(this.id)" id="italic"><img unselectable="on" src="php_mail_merge/italics.gif" title="Italic" width="16" height="16" align="absbottom"></button><button type="button" class="interface_button" onClick="htmlCommand('JustifyLeft', null);reflectSelection()"  onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="sp_buttonState(this)" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="buttonState(this.id)" id="JustifyLeft"><img unselectable="on" src="php_mail_merge/left.gif" title="Align Left" width="16" height="16" align="absbottom"></button>            <button type="button"  class="interface_button" onClick="htmlCommand('JustifyCenter', null);reflectSelection()" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="sp_buttonState(this)" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="buttonState(this.id)" id="JustifyCenter"><img unselectable="on" src="php_mail_merge/centre.gif" title="Align Center" width="16" height="16" align="absbottom"></button>            <button type="button"  class="interface_button" onClick="htmlCommand('JustifyRight', null);reflectSelection()" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="sp_buttonState(this)" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="buttonState(this.id)"  id="JustifyRight"><img unselectable="on" src="php_mail_merge/right.gif" title="Align Right" width="16" height="16" align="absbottom"></button>            <button type="button"  class="interface_button" onClick="htmlCommand('InsertUnorderedList', null);reflectSelection()" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="sp_buttonState(this)" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="buttonState(this.id)"  id="InsertUnorderedList"><img unselectable="on" src="php_mail_merge/bullist.gif" title="Bullet List" width="16" height="16" border="0" align="absbottom"></button>            <button type="button"  class="interface_button" onClick="htmlCommand('InsertOrderedList', null);reflectSelection()" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="sp_buttonState(this)" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="buttonState(this.id)" id="InsertOrderedList"><img unselectable="on" src="php_mail_merge/numlist.gif" title="Numbered List" width="16" height="16" border="0" align="absbottom"></button>            <button type="button"  class="interface_button" onClick="indent();" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')" id="Indent"><img unselectable="on" src="php_mail_merge/indent.gif" title="Indent" width="20" height="16" align="absbottom"></button>            <button type="button"  class="interface_button" onClick="htmlCommand('Outdent', null);" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')" id="Outdent"><img unselectable="on" src="php_mail_merge/outdent.gif" title="Outdent" width="16" height="16" align="absbottom"></button></td>
          <td width="4" class="separator_r">&nbsp;</td>
          <td width="4" class="separator_l">&nbsp;</td>
          <td>&nbsp;</td>
          <td width="4" align="right" valign="middle">&nbsp;</td>
          <td align="right" valign="middle" nowrap> Font:
              <select name="fonts" class="" id="fonts" onChange="htmlCommand('fontname',this[this.selectedIndex].value);this.selectedIndex=0;">
                <option selected="selected">Type face</option>
                <option value="Arial" style="font-family:Arial">Arial</option>
                <option value="Arial Black" style="font-family:Arial Black">Arial Black</option>
                <option value="Comic Sans MS" style="font-family:Comic Sans MS">Comic Sans MS</option>
                <option value="Courier New" style="font-family:Courier New">Courier New</option>
                <option value="Georgia" style="font-family:Georgia">Georgia</option>
                <option value="Impact" style="font-family:Impact">Impact</option>
                <option value="Lucida Console" style="font-family:Lucida Console">Lucida Console</option>
                <option value="Tahoma" style="font-family:Tahoma">Tahoma</option>
                <option value="Times New Roman" style="font-family:Times New Roman">Times New Roman</option>
                <option value="Trebuchet MS" style="font-family:Trebuchet MS">Trebuchet MS</option>
                <option value="Verdana" style="font-family:Verdana" >Verdana</option>
            </select>
              <select name="size" class="" id="size" onChange="htmlCommand('fontsize',this[this.selectedIndex].value);this.selectedIndex=0;">
                <option selected="selected">Size</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>          </td>
          <td>&nbsp;<img src="php_mail_merge/swatch.gif" width="21" height="20" border="0" id="fontColor" title="Pick the font color" onClick="showPalette(this,'fontColor')" unselectable="on" ></td>
          <td nowrap>&nbsp;          </td>
          <td id="b_font" width="28"><button type="button"  class="interface_button"  onClick="customColor('ForeColor','fontColor')" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img src="php_mail_merge/fontcolor.gif" width="16" height="16" align="absmiddle" title="Apply font color" unselectable="on"></button></td>
          <td width="4" align="center" valign="middle" class="separator_r">&nbsp;</td>
          <td width="4" align="center" valign="middle" class="separator_l">&nbsp;</td>
          <td align="right">Background: </td>
          <td width="21"><img src="php_mail_merge/swatch.gif" width="21" height="20" border="0" id="backgrColor" title="Pick the background color" onClick="showPalette(this,'backgrColor')" unselectable="on"></td>
          <td>&nbsp;          </td>
          <td id="b_hilite" width="28"><button type="button"  class="interface_button" onClick="customColor('BackColor','backgrColor')" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img src="php_mail_merge/highlight.gif" width="16" height="16" align="absmiddle" title="Apply background color" unselectable="on"></button></td>
        </tr>
    </table></td>
  </tr>
  <tr valign="top">
    <td colspan="4" style="border-bottom: 1px solid ThreedShadow;border-top: 1px solid ThreedHighlight"><table id="objects_bar"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="1" align="center" valign="middle" style="padding:0px"><div class="barhandle"></div></td>
          <td width="5">&nbsp;</td>
          <td><button type="button"  class="interface_button" onClick="newPage();" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')" style="visibility:visible !important; "><img unselectable="on" src="php_mail_merge/new.gif" title="Start over" width="16" height="16" align="absbottom"></button></td>		  <td><button type="button"  class="interface_button" onClick="openFile();" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')" style="visibility:visible !important; "><img unselectable="on" src="php_mail_merge/open.gif" title="Open Template" width="16" height="16" align="absbottom"></button></td>
<td><button type="button" class="interface_button" id="b_save" title="Save to a file on your computer" onClick="saveLocal(this)" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')" ><img unselectable="on" src="php_mail_merge/save.gif" width="16" height="16" align="absbottom"></button></td>
          <td><button type="button" class="interface_button" id="b_save2server" title="Save as a template to the server" onClick="saveFile()" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')" ><img unselectable="on" src="php_mail_merge/save2server.gif" width="16" height="16" align="absbottom"></button></td><td id="file_bar"><table  border="0" cellpadding="0" cellspacing="0">
              <tr>
                
                <td><button id="b_cut" type="button"  class="interface_button" onClick="htmlCommand('Cut',null)" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/cut.gif" title="Cut" width="16" height="16" align="absbottom"></button></td>
                <td><button id="b_copy" type="button"  class="interface_button" onClick="htmlCommand('Copy',null)" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/copy.gif" title="Copy" width="16" height="16" align="absbottom"></button></td>
                <td><button id="b_paste" type="button"  class="interface_button" onClick="html_box.focus();htmlCommand('Paste',null)" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/paste.gif" title="Paste" width="16" height="16" align="absbottom"></button></td>
              </tr>
          </table></td>
          <td><button id="b_print" type="button"  class="interface_button" onClick="printIframe()" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/print.gif" title="Print the message box" width="16" height="16" align="absbottom"></button></td>
          <td><button id="b_undo" type="button"  class="interface_button" onClick="htmlCommand('undo',null);" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/undo.gif" title="Undo last action  (in Design View)" width="16" height="16" align="absbottom"></button></td>
          <td><button id="b_redo" type="button"  class="interface_button" onClick="htmlCommand('redo',null);" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/redo.gif" title="Redo last action (in Design View)" width="16" height="16" align="absbottom"></button></td>
                    <td><button type="button"  class="interface_button" onClick="previewPage();" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/preview.gif" title="Preview in a separate window" width="16" height="16" align="absbottom"></button></td>
                    <td><button type="button"  class="interface_button" title="Upload image" onclick="open_upload_image();" onmousedown="flipClass(this, 'interface_button down')" onmouseup="flipClass(this, 'interface_button on')" onmouseover="flipClass(this, 'interface_button on')" onmouseout="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/image_upload.gif" width="16" height="16" align="absbottom" /></button></td>
<td width="4" class="separator_r">&nbsp;</td>
          <td width="4" class="separator_l">&nbsp;</td>
          <td valign="middle">&nbsp;&nbsp;Text Style:
              <select name="formatblock" class="" id="formatblock" onchange="setFormat(this[this.selectedIndex].value);this.selectedIndex=0;">
                <option selected="selected">Paragraph</option>
                <option value="<body>">Normal</option>
                <option value="<h1>"> Heading 1</option>
                <option value="<h2>">Heading 2</option>
                <option value="<h3>">Heading 3</option>
                <option value="<h4>">Heading 4</option>
                <option value="<h5>">Heading 5</option>
                <option value="<h6>">Heading 6</option>
                <option value="<pre>">Formatted</option>
            </select>          </td>
          <td width="4" class="separator_r">&nbsp;</td>
          <td width="4" class="separator_l">&nbsp;</td>
          <td><button type="button"  class="interface_button" onClick="insert_link('http://www.',true);" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')" id="createlink"><img unselectable="on" src="php_mail_merge/link.gif" title="Create web page link" width="17" height="17" align="absbottom"></button></td>
          <td><button type="button"  class="interface_button" onClick="insert_link('mailto:',false);" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/email_link.gif" title="Create email link" width="16" height="16" align="absbottom"></button></td>
          <td id="b_unlink"><button type="button"  class="interface_button" onClick="htmlCommand('unlink',null);" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/unlink.gif" title="Remove link" width="17" height="17" align="absbottom"></button></td>
          <td><button type="button"  class="interface_button" onClick="insert_image(null);" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/image.gif" title="Insert image" width="16" height="16" align="absbottom"></button></td>
          
          <td><button type="button"  class="interface_button" onclick="open_insert_image();"  onmousedown="flipClass(this, 'interface_button down')" onmouseup="flipClass(this, 'interface_button on')" onmouseover="flipClass(this, 'interface_button on')" onmouseout="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/image_insert.gif" title="Insert image from the server" width="16" height="16" align="absbottom" /></button></td>
          <td><button type="button"  class="interface_button" onClick="insertElement('InsertHorizontalRule','','<hr>','');" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/rule.gif" title="Insert horizontal rule" width="16" height="16" align="absbottom"></button></td>
          <td><button type="button"  class="interface_button" onClick="insertDate()" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/date.gif" title="Insert today's date" width="16" height="16" align="absbottom"></button></td>
          <td><button type="button"  class="interface_button" onClick="tableDialog()" onMouseDown="flipClass(this, 'interface_button down')" onMouseUp="flipClass(this, 'interface_button on')" onMouseOver="flipClass(this, 'interface_button on')" onMouseOut="flipClass(this, 'interface_button')"><img unselectable="on" src="php_mail_merge/table.gif" title="Insert table" width="16" height="16" align="absbottom"></button></td></tr>
    </table></td>
  </tr>
  <tr >
    <td height="2" colspan="4" style="border-top: 1px solid ThreedHighlight; padding:0px">&nbsp;</td>
  </tr>
  <tr>
    <th width="30%" height="20" class="activetab" id="sourcetab" title="Switch to Plain Text/ Code View" onClick="showSource()">Plain Text/ Code View </th>
    <th width="2" height="30" class="separator"></th>
    <th width="30%" height="20" class="tab" id="htmltab" title="Switch to Design View" onClick="showHTML()">Design View</th>
    <td width="54%" height="20" align="right" class="notab"><div id="progress_minimized"><img src="php_mail_merge/progress.gif" name="small_progress" width="101" height="6" id="small_progress" /><br />
  <span id="small_status">Sending...</span> <a href="javascript:;" onclick="maximizePopup()">Show details</a></div>  
    </tr>
  <tr align="center">
    <td colspan="4"><textarea name="m_source" wrap="VIRTUAL" id="m_source" style="width:932px; height:326px;"></textarea>        
<div id="sourceholder" style="background-color:#FFFFFF; display:none; width: 932px; height: 334px">
<iframe src="" id="html_box" name="html_box" width="930" height="334"></iframe>
</div></td></tr>
</table>
<input name="keep_source" type="hidden" id="keep_source">
<input name="Do_Send" type="hidden" id="Do_Send">
<script language="javascript">
function restoreValues(elm,val){
	var the_elm=eval("document.editor_form."+elm);
	if(the_elm){
		if(the_elm.length>0 && !the_elm.options){
			var the_elm_len=the_elm.length;
			for (i=0; i<=the_elm_len;i++){
				if(the_elm[i].value==val){
					the_elm[i].checked=true;
					break;
				}
			}
		}else if(the_elm.type.toLowerCase()=="text" || the_elm.type.toLowerCase()=="hidden" || the_elm.tagName.toLowerCase()=="textarea" ){
			the_elm.value=val.replace(/#_#_#/g,"\n");
		}else if(the_elm.type.toLowerCase()=="checkbox" && the_elm.value!=""){
			the_elm.checked=true;
		}else if(the_elm.tagName.toLowerCase()=="select"){
			var the_elm_len=the_elm.options.length;
			for (i=0; i<=the_elm_len;i++){
				if(!the_elm.options[i].value && the_elm.options[i].text){the_elm.options[i].value=the_elm.options[i].text}
				if(the_elm.options[i].value==val){
					the_elm.selectedIndex=i;
					break;
				}
			}
		}
	}
}
<?php 
if(isset($_POST['Do_Send'])){
	$line_breaks=array("\r\n", "\n", "\r");
	if($_POST['reset_editor']=="1"){
		$mailer_fields=array("StartNumber","NumOfRecords","delay","From","FromName","To","Validate","Subject","RecordsetFields","BaseURL","formatblock","m_source","keep_source","Do_Send","errors","FileAttachment","Host","UserName","Password");
	}else{
		$mailer_fields=array("RecordsetFields");
	}
	foreach ($_POST as $key => $value){
	  if (isset($key) && !in_array($key,$mailer_fields)){
?>
restoreValues('<?php echo $key; ?>','<?php echo str_replace($line_breaks, "#_#_#",$value);?>');
<?php }}} ?>
</script>