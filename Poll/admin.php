<?php
/*
#3.0.0 2009 02 19
# 
#################################################################################################
## Copright (c) 2007 - PHPjabbers.com - webmasters tools and help http://www.phpjabbers.com/   ##
## Not for resale                   					                                                 ##
## info@phpjabbers.com                                                                	  	   ##
#################################################################################################
##        Custom Web Development - Dynamic Content - Website scripts                           ##
##                          www.phpjabbers.com                                                 ##
#################################################################################################
## This code is protected by international copyright.                                          ##
## DO NOT POST or distribute portions of code on any site / forum etc.                         ##
#################################################################################################
#
# */
error_reporting(0);
session_start();
include("options.php");

if(isset($_REQUEST["ac"])) {
if ($_REQUEST["ac"] == 'logout') {
		if( $SETTINGS["useCookie"] == false ){
			$_SESSION["webPollAdmin"] = "";
			unset($_SESSION["webPollAdmin"]);
		}
		else{
			setCookie("webPollAdmin", "", 0);
			$_COOKIE["webPollAdmin"] = "";
		}
} elseif ($_REQUEST["ac"] == 'login') {
  	if ($_REQUEST["user"] == $SETTINGS["admin_username"] and $_REQUEST["pass"] == $SETTINGS["admin_password"]) {
  	$md_sum=md5($SETTINGS["admin_username"].$SETTINGS["admin_password"]);
		$sess_id=$md_sum.strtotime("+1 hour");
		if( $SETTINGS["useCookie"] == false )
			$_SESSION["webPollAdmin"] = $sess_id;
		else{
			setCookie("webPollAdmin", $sess_id, time()+3600);
			$_COOKIE["webPollAdmin"] = $sess_id;
		}
		
 		$_REQUEST["ac"]='current_polls';
	} else {
		$message = '<strong style="color:#FF0000">Incorrect login details.</strong>';
	};
	};
};
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PHP Poll - Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<script language="javascript" >
function highlightrow(id,columns) {
	for (i=1; i<=columns; i++) {
		document.getElementById("c"+i+"-"+id).style.backgroundColor = '#ececec';
	};
};

function outhighlightrow(id,columns) {
	for (i=1; i<=columns; i++) {
		document.getElementById("c"+i+"-"+id).style.backgroundColor = '';
	};
};


function LoadStep(val){
	document.getElementById("step1").style.display = 'none';
	document.getElementById("step2").style.display = 'none';
	document.getElementById("step3").style.display = 'none';
	  if (val=='1') {
  	  document.getElementById("step1").style.display = 'block';
    }
    if (val=='2') {
  	  document.getElementById("step2").style.display = 'block';
    } 
    if (val=='3') {
	    document.getElementById("step3").style.display = 'block';
    }
	}
	
	function useTime(val){
	   val = !val;
     document.getElementById("sMonth").disabled = val;
     document.getElementById("sDay").disabled = val;
     document.getElementById("sYear").disabled = val;
     document.getElementById("sHour").disabled = val;
     document.getElementById("sMin").disabled = val;
     document.getElementById("eMonth").disabled = val;
     document.getElementById("eDay").disabled = val;
     document.getElementById("eYear").disabled = val;
     document.getElementById("eHour").disabled = val;
     document.getElementById("eMin").disabled = val;
  }

function FillAnswerArea(el){
    var txt = "";
    var aVal = "";
    var sVal = "";
    var aField = "";
    var sField = "";
    for(i=0;i<el;i++){
      aField = "answer" + i;
      sField = "start" + i;
      var answerTxt = document.frm.elements[aField];
      var startTxt  = document.frm.elements[sField];
      if ( answerTxt != null ) aVal = answerTxt.value;
      else aVal = '';
      if ( startTxt != null ) sVal = startTxt.value;
      else sVal = '0';
      
	  aVal = aVal.replace(/"/g,"&quot;");
	  aVal = aVal.replace(/'/g,"&quot;");
	  txt = txt + '<input name="answer'+i+'" type="text" id="answer'+i+'" size="30" maxlength="80" style="margin-top:4px" value="' + aVal + '"> votes count <input name="start'+i+'" type="text"  id="start'+i+'" size="2" maxlength="4" value="' + sVal + '"><br>';
    }
    var qEl = document.getElementById("answerArea");
    qEl.innerHTML = txt;
  }

function getAnchorPosition(anchorname){var useWindow=false;var coordinates=new Object();var x=0,y=0;var use_gebi=false, use_css=false, use_layers=false;if(document.getElementById){use_gebi=true;}else if(document.all){use_css=true;}else if(document.layers){use_layers=true;}if(use_gebi && document.all){x=AnchorPosition_getPageOffsetLeft(document.all[anchorname]);y=AnchorPosition_getPageOffsetTop(document.all[anchorname]);}else if(use_gebi){var o=document.getElementById(anchorname);x=AnchorPosition_getPageOffsetLeft(o);y=AnchorPosition_getPageOffsetTop(o);}else if(use_css){x=AnchorPosition_getPageOffsetLeft(document.all[anchorname]);y=AnchorPosition_getPageOffsetTop(document.all[anchorname]);}else if(use_layers){var found=0;for(var i=0;i<document.anchors.length;i++){if(document.anchors[i].name==anchorname){found=1;break;}}if(found==0){coordinates.x=0;coordinates.y=0;return coordinates;}x=document.anchors[i].x;y=document.anchors[i].y;}else{coordinates.x=0;coordinates.y=0;return coordinates;}coordinates.x=x;coordinates.y=y;return coordinates;}
function getAnchorWindowPosition(anchorname){var coordinates=getAnchorPosition(anchorname);var x=0;var y=0;if(document.getElementById){if(isNaN(window.screenX)){x=coordinates.x-document.body.scrollLeft+window.screenLeft;y=coordinates.y-document.body.scrollTop+window.screenTop;}else{x=coordinates.x+window.screenX+(window.outerWidth-window.innerWidth)-window.pageXOffset;y=coordinates.y+window.screenY+(window.outerHeight-24-window.innerHeight)-window.pageYOffset;}}else if(document.all){x=coordinates.x-document.body.scrollLeft+window.screenLeft;y=coordinates.y-document.body.scrollTop+window.screenTop;}else if(document.layers){x=coordinates.x+window.screenX+(window.outerWidth-window.innerWidth)-window.pageXOffset;y=coordinates.y+window.screenY+(window.outerHeight-24-window.innerHeight)-window.pageYOffset;}coordinates.x=x;coordinates.y=y;return coordinates;}
function AnchorPosition_getPageOffsetLeft(el){var ol=el.offsetLeft;while((el=el.offsetParent) != null){ol += el.offsetLeft;}return ol;}
function AnchorPosition_getWindowOffsetLeft(el){return AnchorPosition_getPageOffsetLeft(el)-document.body.scrollLeft;}
function AnchorPosition_getPageOffsetTop(el){var ot=el.offsetTop;while((el=el.offsetParent) != null){ot += el.offsetTop;}return ot;}
function AnchorPosition_getWindowOffsetTop(el){return AnchorPosition_getPageOffsetTop(el)-document.body.scrollTop;}
function PopupWindow_getXYPosition(anchorname){var coordinates;if(this.type == "WINDOW"){coordinates = getAnchorWindowPosition(anchorname);}else{coordinates = getAnchorPosition(anchorname);}this.x = coordinates.x;this.y = coordinates.y;}
function PopupWindow_setSize(width,height){this.width = width;this.height = height;}
function PopupWindow_populate(contents){this.contents = contents;this.populated = false;}
function PopupWindow_setUrl(url){this.url = url;}
function PopupWindow_setWindowProperties(props){this.windowProperties = props;}
function PopupWindow_refresh(){if(this.divName != null){if(this.use_gebi){document.getElementById(this.divName).innerHTML = this.contents;}else if(this.use_css){document.all[this.divName].innerHTML = this.contents;}else if(this.use_layers){var d = document.layers[this.divName];d.document.open();d.document.writeln(this.contents);d.document.close();}}else{if(this.popupWindow != null && !this.popupWindow.closed){if(this.url!=""){this.popupWindow.location.href=this.url;}else{this.popupWindow.document.open();this.popupWindow.document.writeln(this.contents);this.popupWindow.document.close();}this.popupWindow.focus();}}}
function PopupWindow_showPopup(anchorname){this.getXYPosition(anchorname);this.x += this.offsetX;this.y += this.offsetY;if(!this.populated &&(this.contents != "")){this.populated = true;this.refresh();}if(this.divName != null){if(this.use_gebi){document.getElementById(this.divName).style.left = this.x + "px";document.getElementById(this.divName).style.top = this.y;document.getElementById(this.divName).style.visibility = "visible";}else if(this.use_css){document.all[this.divName].style.left = this.x;document.all[this.divName].style.top = this.y;document.all[this.divName].style.visibility = "visible";}else if(this.use_layers){document.layers[this.divName].left = this.x;document.layers[this.divName].top = this.y;document.layers[this.divName].visibility = "visible";}}else{if(this.popupWindow == null || this.popupWindow.closed){if(this.x<0){this.x=0;}if(this.y<0){this.y=0;}if(screen && screen.availHeight){if((this.y + this.height) > screen.availHeight){this.y = screen.availHeight - this.height;}}if(screen && screen.availWidth){if((this.x + this.width) > screen.availWidth){this.x = screen.availWidth - this.width;}}var avoidAboutBlank = window.opera ||( document.layers && !navigator.mimeTypes['*']) || navigator.vendor == 'KDE' ||( document.childNodes && !document.all && !navigator.taintEnabled);this.popupWindow = window.open(avoidAboutBlank?"":"about:blank","window_"+anchorname,this.windowProperties+",width="+this.width+",height="+this.height+",screenX="+this.x+",left="+this.x+",screenY="+this.y+",top="+this.y+"");}this.refresh();}}
function PopupWindow_hidePopup(){if(this.divName != null){if(this.use_gebi){document.getElementById(this.divName).style.visibility = "hidden";}else if(this.use_css){document.all[this.divName].style.visibility = "hidden";}else if(this.use_layers){document.layers[this.divName].visibility = "hidden";}}else{if(this.popupWindow && !this.popupWindow.closed){this.popupWindow.close();this.popupWindow = null;}}}
function PopupWindow_isClicked(e){if(this.divName != null){if(this.use_layers){var clickX = e.pageX;var clickY = e.pageY;var t = document.layers[this.divName];if((clickX > t.left) &&(clickX < t.left+t.clip.width) &&(clickY > t.top) &&(clickY < t.top+t.clip.height)){return true;}else{return false;}}else if(document.all){var t = window.event.srcElement;while(t.parentElement != null){if(t.id==this.divName){return true;}t = t.parentElement;}return false;}else if(this.use_gebi && e){var t = e.originalTarget;while(t.parentNode != null){if(t.id==this.divName){return true;}t = t.parentNode;}return false;}return false;}return false;}
function PopupWindow_hideIfNotClicked(e){if(this.autoHideEnabled && !this.isClicked(e)){this.hidePopup();}}
function PopupWindow_autoHide(){this.autoHideEnabled = true;}
function PopupWindow_hidePopupWindows(e){for(var i=0;i<popupWindowObjects.length;i++){if(popupWindowObjects[i] != null){var p = popupWindowObjects[i];p.hideIfNotClicked(e);}}}
function PopupWindow_attachListener(){if(document.layers){document.captureEvents(Event.MOUSEUP);}window.popupWindowOldEventListener = document.onmouseup;if(window.popupWindowOldEventListener != null){document.onmouseup = new Function("window.popupWindowOldEventListener();PopupWindow_hidePopupWindows();");}else{document.onmouseup = PopupWindow_hidePopupWindows;}}
function PopupWindow(){if(!window.popupWindowIndex){window.popupWindowIndex = 0;}if(!window.popupWindowObjects){window.popupWindowObjects = new Array();}if(!window.listenerAttached){window.listenerAttached = true;PopupWindow_attachListener();}this.index = popupWindowIndex++;popupWindowObjects[this.index] = this;this.divName = null;this.popupWindow = null;this.width=0;this.height=0;this.populated = false;this.visible = false;this.autoHideEnabled = false;this.contents = "";this.url="";this.windowProperties="toolbar=no,location=no,status=no,menubar=no,scrollbars=auto,resizable,alwaysRaised,dependent,titlebar=no";if(arguments.length>0){this.type="DIV";this.divName = arguments[0];}else{this.type="WINDOW";}this.use_gebi = false;this.use_css = false;this.use_layers = false;if(document.getElementById){this.use_gebi = true;}else if(document.all){this.use_css = true;}else if(document.layers){this.use_layers = true;}else{this.type = "WINDOW";}this.offsetX = 0;this.offsetY = 0;this.getXYPosition = PopupWindow_getXYPosition;this.populate = PopupWindow_populate;this.setUrl = PopupWindow_setUrl;this.setWindowProperties = PopupWindow_setWindowProperties;this.refresh = PopupWindow_refresh;this.showPopup = PopupWindow_showPopup;this.hidePopup = PopupWindow_hidePopup;this.setSize = PopupWindow_setSize;this.isClicked = PopupWindow_isClicked;this.autoHide = PopupWindow_autoHide;this.hideIfNotClicked = PopupWindow_hideIfNotClicked;}
ColorPicker_targetInput = null;
function ColorPicker_writeDiv(){document.writeln("<DIV ID=\"colorPickerDiv\" STYLE=\"position:absolute;visibility:hidden;\"> </DIV>");}
function ColorPicker_show(anchorname){this.showPopup(anchorname);}
function ColorPicker_pickColor(color,obj){obj.hidePopup();pickColor(color);}
function pickColor(color){if(ColorPicker_targetInput==null){alert("Target Input is null, which means you either didn't use the 'select' function or you have no defined your own 'pickColor' function to handle the picked color!");return;}ColorPicker_targetInput.value = color;}
function ColorPicker_select(inputobj,linkname){if(inputobj.type!="text" && inputobj.type!="hidden" && inputobj.type!="textarea"){alert("colorpicker.select: Input object passed is not a valid form input object");window.ColorPicker_targetInput=null;return;}window.ColorPicker_targetInput = inputobj;this.show(linkname);}
function ColorPicker_highlightColor(c){var thedoc =(arguments.length>1)?arguments[1]:window.document;var d = thedoc.getElementById("colorPickerSelectedColor");d.style.backgroundColor = c;d = thedoc.getElementById("colorPickerSelectedColorValue");d.innerHTML = c;}
function ColorPicker(){var windowMode = false;if(arguments.length==0){var divname = "colorPickerDiv";}else if(arguments[0] == "window"){var divname = '';windowMode = true;}else{var divname = arguments[0];}if(divname != ""){var cp = new PopupWindow(divname);}else{var cp = new PopupWindow();cp.setSize(225,250);}cp.currentValue = "#FFFFFF";cp.writeDiv = ColorPicker_writeDiv;cp.highlightColor = ColorPicker_highlightColor;cp.show = ColorPicker_show;cp.select = ColorPicker_select;var colors = new Array("#000000","#000033","#000066","#000099","#0000CC","#0000FF","#330000","#330033","#330066","#330099","#3300CC",
"#3300FF","#660000","#660033","#660066","#660099","#6600CC","#6600FF","#990000","#990033","#990066","#990099",
"#9900CC","#9900FF","#CC0000","#CC0033","#CC0066","#CC0099","#CC00CC","#CC00FF","#FF0000","#FF0033","#FF0066",
"#FF0099","#FF00CC","#FF00FF","#003300","#003333","#003366","#003399","#0033CC","#0033FF","#333300","#333333",
"#333366","#333399","#3333CC","#3333FF","#663300","#663333","#663366","#663399","#6633CC","#6633FF","#993300",
"#993333","#993366","#993399","#9933CC","#9933FF","#CC3300","#CC3333","#CC3366","#CC3399","#CC33CC","#CC33FF",
"#FF3300","#FF3333","#FF3366","#FF3399","#FF33CC","#FF33FF","#006600","#006633","#006666","#006699","#0066CC",
"#0066FF","#336600","#336633","#336666","#336699","#3366CC","#3366FF","#666600","#666633","#666666","#666699",
"#6666CC","#6666FF","#996600","#996633","#996666","#996699","#9966CC","#9966FF","#CC6600","#CC6633","#CC6666",
"#CC6699","#CC66CC","#CC66FF","#FF6600","#FF6633","#FF6666","#FF6699","#FF66CC","#FF66FF","#009900","#009933",
"#009966","#009999","#0099CC","#0099FF","#339900","#339933","#339966","#339999","#3399CC","#3399FF","#669900",
"#669933","#669966","#669999","#6699CC","#6699FF","#999900","#999933","#999966","#999999","#9999CC","#9999FF",
"#CC9900","#CC9933","#CC9966","#CC9999","#CC99CC","#CC99FF","#FF9900","#FF9933","#FF9966","#FF9999","#FF99CC",
"#FF99FF","#00CC00","#00CC33","#00CC66","#00CC99","#00CCCC","#00CCFF","#33CC00","#33CC33","#33CC66","#33CC99",
"#33CCCC","#33CCFF","#66CC00","#66CC33","#66CC66","#66CC99","#66CCCC","#66CCFF","#99CC00","#99CC33","#99CC66",
"#99CC99","#99CCCC","#99CCFF","#CCCC00","#CCCC33","#CCCC66","#CCCC99","#CCCCCC","#CCCCFF","#FFCC00","#FFCC33",
"#FFCC66","#FFCC99","#FFCCCC","#FFCCFF","#00FF00","#00FF33","#00FF66","#00FF99","#00FFCC","#00FFFF","#33FF00",
"#33FF33","#33FF66","#33FF99","#33FFCC","#33FFFF","#66FF00","#66FF33","#66FF66","#66FF99","#66FFCC","#66FFFF",
"#99FF00","#99FF33","#99FF66","#99FF99","#99FFCC","#99FFFF","#CCFF00","#CCFF33","#CCFF66","#CCFF99","#CCFFCC",
"#CCFFFF","#FFFF00","#FFFF33","#FFFF66","#FFFF99","#FFFFCC","#FFFFFF");var total = colors.length;var width = 18;var cp_contents = "";var windowRef =(windowMode)?"window.opener.":"";if(windowMode){cp_contents += "<HTML><HEAD><TITLE>Select Color</TITLE></HEAD>";cp_contents += "<BODY MARGINWIDTH=0 MARGINHEIGHT=0 LEFTMARGIN=0 TOPMARGIN=0><CENTER>";}cp_contents += "<TABLE BORDER=1 CELLSPACING=1 CELLPADDING=0>";var use_highlight =(document.getElementById || document.all)?true:false;for(var i=0;i<total;i++){if((i % width) == 0){cp_contents += "<TR>";}if(use_highlight){var mo = 'onMouseOver="'+windowRef+'ColorPicker_highlightColor(\''+colors[i]+'\',window.document)"';}else{mo = "";}cp_contents += '<TD BGCOLOR="'+colors[i]+'"><FONT SIZE="-3"><A HREF="#" onClick="'+windowRef+'ColorPicker_pickColor(\''+colors[i]+'\','+windowRef+'window.popupWindowObjects['+cp.index+']);return false;" '+mo+' STYLE="text-decoration:none;">&nbsp;&nbsp;&nbsp;</A></FONT></TD>';if( ((i+1)>=total) ||(((i+1) % width) == 0)){cp_contents += "</TR>";}}if(document.getElementById){var width1 = Math.floor(width/2);var width2 = width = width1;cp_contents += "<TR><TD COLSPAN='"+width1+"' BGCOLOR='#ffffff' ID='colorPickerSelectedColor'>&nbsp;</TD><TD COLSPAN='"+width2+"' ALIGN='CENTER' ID='colorPickerSelectedColorValue'>#FFFFFF</TD></TR>";}cp_contents += "</TABLE>";if(windowMode){cp_contents += "</CENTER></BODY></HTML>";}cp.populate(cp_contents+"\n");cp.offsetY = 25;cp.autoHide();return cp;}
var cp = new ColorPicker('window'); 

</script>
<STYLE type="text/css">
 BODY {
	margin:0px;	
}
TD {
	font-family:Verdana, Geneva, sans-serif;
	font-size:11px;
}
a.topMenu:link {
	font-family:Verdana, Geneva, sans-serif;
	font-size:18px;
	color:#FFFFFF;
	font-weight:bold;
	text-decoration:none;
}     /* unvisited link */
a.topMenu:visited {
	font-family:Verdana, Geneva, sans-serif;
	font-size:18px;
	color:#FFFFFF;
	font-weight:bold;
	text-decoration:none;
}  /* visited link */
a.topMenu:hover {
	font-family:Verdana, Geneva, sans-serif;
	font-size:18px;
	color:#ffcc03;
	font-weight:bold;
	text-decoration:underline;
} 
a.topMenu:active {
	font-family:Verdana, Geneva, sans-serif;
	font-size:18px;
	color:#ffcc03;
	font-weight:bold;
}



a.subMenu:link {
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	color:#616161;
	font-weight:bold;
	text-decoration:none;
}     /* unvisited link */
a.subMenu:visited {
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	color:#616161;
	font-weight:bold;
	text-decoration:none;
}  /* visited link */
a.subMenu:hover {
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	color:#616161;
	font-weight:bold;
	text-decoration:underline;
} 
a.subMenu:active {
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	color:#616161;
	font-weight:bold;
}


a:link {
	font-family:Verdana, Geneva, sans-serif;
	font-size:11px;
	color:#3a30ff;
	font-weight:bold;
	text-decoration:underline;
}     /* unvisited link */
a:visited {
	font-family:Verdana, Geneva, sans-serif;
	font-size:11px;
	color:#3a30ff;
	font-weight:bold;
	text-decoration:underline;
}  /* visited link */
a:hover {
	font-family:Verdana, Geneva, sans-serif;
	font-size:11px;
	color:#ffcc03;
	font-weight:bold;
	text-decoration:underline;
} 
a:active {
	font-family:Verdana, Geneva, sans-serif;
	font-size:11px;
	color:#616161;
	font-weight:bold;
}
</STYLE>
</head>

<body>
<center>
<div style="width:981px"><img src="images/header.jpg" width="981" height="61" /></div>
<div style="width:100%; background-color:#242424; height:8px; font-size:1px">&nbsp;</div>
<?php 
$isLogged = false;
if ( $SETTINGS["useCookie"] == false ){
	if (isset($_SESSION["webPollAdmin"])) $temp_sid=$_SESSION["webPollAdmin"];
}
else{
	if (isset($_COOKIE["webPollAdmin"])) $temp_sid=$_COOKIE["webPollAdmin"];
}

$md_sum=md5($SETTINGS["admin_username"].$SETTINGS["admin_password"]);
	$md_res=substr($temp_sid,0,strlen($md_sum));
	if (strcmp($md_sum,$md_res)==0) {
		$ts=substr($temp_sid,strlen($md_sum));
		if ($ts>time()) {
      $isLogged = true;
      $md_sum=md5($SETTINGS["admin_username"].$SETTINGS["admin_password"]);
  		$sess_id=$md_sum.strtotime("+1 hour");
  		if( $SETTINGS["useCookie"] == false )
  			$_SESSION["webPollAdmin"] = $sess_id;
  		else{
  			setCookie("webPollAdmin", $sess_id, time()+3600);
  			$_COOKIE["webPollAdmin"] = $sess_id;
  		}
    }
	}
	
if ( $isLogged ){
?>
<table width="981" border="0" cellspacing="0" cellpadding="0">



  <tr>
    <td width="100%" height="49" style="background-image:url(images/menu-back.jpg); background-repeat:no-repeat"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="16%" align="center"><a href="admin.php?ac=current_polls" class="topMenu" <?php if (($_REQUEST["ac"]=='current_polls')||(($_REQUEST["ac"]=='poll')&&(isset($_REQUEST["qid"])))||($_REQUEST["ac"]=='stats')||($_REQUEST["ac"]=='html')) echo 'style="color:#ffcc03"'; ?>>My Polls</a></td>
        <td width="18%" align="center"><a href="admin.php?ac=poll" class="topMenu" <?php if (($_REQUEST["ac"]=='poll')&&(!isset($_REQUEST["qid"]))) echo 'style="color:#ffcc03"'; ?>> Create a Poll </a></td>
        <td width="20%" align="center"><a href="admin.php?ac=color_sets" class="topMenu" <?php if ($_REQUEST["ac"]=='color_sets') echo 'style="color:#ffcc03"'; ?> > Color Sets</a></td>
        <td width="36%" align="right" style="padding-right:10px"><a href="admin.php?ac=update" class="topMenu" style="font-size:12px<?php if ($_REQUEST["ac"]=='update') echo '; color:#ffcc03'; ?>"> UPDATE</a></td>
        <td width="10%" align="right" style="padding-right:10px"><a href="admin.php?ac=logout" class="topMenu" style="font-size:12px">LOGOUT</a></td>
      </tr>
    </table></td>
    </tr>
    


<?php
if ($_REQUEST["ac"] == 'save_poll') {

  	if ($_REQUEST["width"] < 100 ) $_REQUEST["width"] = 100;
	if ( $_REQUEST["useTimeInterval"] == "" ) {
		$_REQUEST["useTimeInterval"] = "false";		
		$sDate = '0000-00-00 00:00:00';
		$eDate = '0000-00-00 00:00:00';
	}
	else{
		$sDate = date("Y-m-d-G-i", mktime($_REQUEST["sHour"],$_REQUEST["sMin"],0,$_REQUEST["sMonth"],$_REQUEST["sDay"],$_REQUEST["sYear"]));
		$eDate = date("Y-m-d-G-i", mktime($_REQUEST["eHour"],$_REQUEST["eMin"],0,$_REQUEST["eMonth"],$_REQUEST["eDay"],$_REQUEST["eYear"]));
	}
	if ($_REQUEST["pollDisable"] == "") $_REQUEST["pollDisable"] = "true";
	if (!isset($_REQUEST["multiple_votes"])) $_REQUEST["multiple_votes"] = "false";

  	if ( $_REQUEST["qid"] > 0 ) {
  		$sql = "UPDATE ".$TABLES["QUESTIONS"]." 
				  SET `COLOR_SET_ID` = '".$_REQUEST["color_set_id"]."',                    
					  `DAYS` = '".$_REQUEST["days"]."',                       
					  `WIDTH` = '".$_REQUEST["width"]."',                      
					  `QUESTION` = '".mysql_escape_string(utf8_encode($_REQUEST["question"]))."',                  
					  `FONT` = '".$_REQUEST["font"]."',               
					  `SHOW_RESULT` = '".$_REQUEST["results"]."',               
					  `ON_VOTE` = '".$_REQUEST["onvote"]."',                   
					  `CUSTOM_MSG` = '".mysql_escape_string(utf8_encode($_REQUEST["msg"]))."',                   
					  `BTN_MSG` = '".mysql_escape_string(utf8_encode($_REQUEST["voteMsg"]))."',                  
					  `TOTAL_MSG` = '".mysql_escape_string(utf8_encode($_REQUEST["totalMsg"]))."',                 
					  `POLL_START` = '".$sDate."',
					  `POLL_END` = '".$eDate."',                      
					  `ALLOW_VOTE` = '".$_REQUEST["pollDisable"]."',                  
					  `USE_TIME_INTERVAL` = '".$_REQUEST["useTimeInterval"]."',
					  `MULTIPLE_VOTES` = '".$_REQUEST["multiple_votes"]."' 
				  WHERE ID ='".$_REQUEST["qid"]."'";
      	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);

		  for($i=0;$i<$_REQUEST["aNum"];$i++){
			$answer = "answer" . $i;
			$count  = "start" . $i;
			if ($_REQUEST[$answer] == "") {
				$sql = "SELECT * FROM ".$TABLES["ANSWERS"]."
						WHERE QUESTION_ID = '".$_REQUEST["qid"]."'
						AND ORDER_ID = '".($i+1)."'";
				$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);        
				$temp = mysql_fetch_assoc($sql_result);
				$sql = "DELETE FROM ".$TABLES["ANSWERS"]."
						WHERE QUESTION_ID = '".$_REQUEST["qid"]."'
						AND ORDER_ID = '".($i+1)."'";
				$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);        
				$sql = "DELETE FROM ".$TABLES["VOTES"]."
						WHERE QUESTION_ID = '".$_REQUEST["qid"]."'
						AND ANSWER_ID = '".$temp["ID"]."'";
				$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);        
				break;
			};
		
			$sql = "SELECT * FROM ".$TABLES["ANSWERS"]."
					WHERE QUESTION_ID = '".$_REQUEST["qid"]."'
					AND ORDER_ID = '".($i+1)."'";
			$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);        
			
			if ( mysql_num_rows($sql_result) > 0 ) $isUpdate = "true";
			else $isUpdate = "false";
				
			if ( $isUpdate == "true" ){
				$sql = "UPDATE ".$TABLES["ANSWERS"]."
						SET `ANSWER` = '".mysql_escape_string(utf8_encode($_REQUEST[$answer]))."',
							`COUNT`  = '".$_REQUEST[$count]."'
						WHERE QUESTION_ID = '".$_REQUEST["qid"]."'
						AND ORDER_ID = '".($i+1)."'";
				$sql_result = mysql_query ($sql, $connection );        
			}
			else{
				$sql = "INSERT INTO ".$TABLES["ANSWERS"]."
						SET `ID` = null,
							`QUESTION_ID` = '".$_REQUEST["qid"]."',
							`ORDER_ID` = '".($i+1)."',
							`ANSWER` = '".mysql_escape_string(utf8_encode($_REQUEST[$answer]))."',
							`COUNT`  = '".$_REQUEST[$count]."'";
				$sql_result = mysql_query ($sql, $connection );        
			}	
		  }


		$k = 1;
		$sql = "SELECT * FROM ".$TABLES["ANSWERS"]."
				WHERE QUESTION_ID = '".$_REQUEST["qid"]."' ORDER BY ORDER_ID ASC";
		$sql_result = mysql_query ($sql, $connection );        
		while ($temp = mysql_fetch_assoc($sql_result)) {
				$sql = "UPDATE ".$TABLES["ANSWERS"]."
						SET ORDER_ID = '".$k."'
						WHERE ID = '".$temp["ID"]."'";
				$sql_resultT = mysql_query ($sql, $connection );        
				$k++;
		};
		  
  	} else {
    		$sql = "INSERT INTO ".$TABLES["QUESTIONS"]." 
               			SET `COLOR_SET_ID` = '".$_REQUEST["color_set_id"]."',                    
							`DAYS` = '".$_REQUEST["days"]."',                       
							`WIDTH` = '".$_REQUEST["width"]."',                      
							`QUESTION` = '".mysql_escape_string(utf8_encode($_REQUEST["question"]))."',                  
			                `FONT` = '".$_REQUEST["font"]."',               
							`SHOW_RESULT` = '".$_REQUEST["results"]."',               
							`ON_VOTE` = '".$_REQUEST["onvote"]."',                   
							`CUSTOM_MSG` = '".mysql_escape_string(utf8_encode($_REQUEST["msg"]))."',                   
							`BTN_MSG` = '".mysql_escape_string(utf8_encode($_REQUEST["voteMsg"]))."',                  
							`TOTAL_MSG` = '".mysql_escape_string(utf8_encode($_REQUEST["totalMsg"]))."',                 
							`POLL_START` = '".$sDate."',
							`POLL_END` = '".$eDate."',
							`ALLOW_VOTE` = '".$_REQUEST["pollDisable"]."',                  
							`USE_TIME_INTERVAL` = '".$_REQUEST["useTimeInterval"]."',
							`MULTIPLE_VOTES` = '".$_REQUEST["multiple_votes"]."'";
      $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);              
      $_REQUEST["qid"] = mysql_insert_id();
                    
      for($i=0;$i<$_REQUEST["aNum"];$i++){
        $answer = "answer" . $i;
        $count  = "start" . $i;
        if ($_REQUEST[$answer] == "") break;
    		$sql = "INSERT INTO ".$TABLES["ANSWERS"]."
    		        SET `ID` = null,
    		            `QUESTION_ID` = '".$_REQUEST["qid"]."',
    		            `ORDER_ID` = '".($i+1)."',
                    	`ANSWER` = '".mysql_escape_string(utf8_encode($_REQUEST[$answer]))."',
	                    `COUNT`  = '".$_REQUEST[$count]."'";
        $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);                                 
      }
  	}
  	$_REQUEST["ac"] = 'poll'; $message = 'Poll saved.';

} elseif ($_REQUEST["ac"] == 'del_poll') {
  	$sql = "DELETE FROM ".$TABLES["QUESTIONS"]." WHERE ID = '".$_REQUEST["qid"]."'";
  	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
  	$sql = "DELETE FROM ".$TABLES["ANSWERS"]." WHERE QUESTION_ID ='".$_REQUEST["qid"]."'";
  	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
  	$sql = "DELETE FROM ".$TABLES["VOTES"]." WHERE QUESTION_ID ='".$_REQUEST["qid"]."'";
  	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
  	$_REQUEST["ac"] = 'current_polls'; $message = 'Poll deleted.';
} elseif ($_REQUEST["ac"] == 'save_color_set') {
	if ($_REQUEST["color_set_id"] > 0) { 
		$sql = "UPDATE ".$TABLES["COLORS"]."
    				SET `SET_NAME`     ='".mysql_escape_string(utf8_encode($_REQUEST["set_name"]))."', 
    				 	  `POLL_BG`	     ='".str_replace("#","",$_REQUEST["poll_bg"])."', 
    					  `QUESTION_BG`  ='".str_replace("#","",$_REQUEST["question_bg"])."', 
    					  `QUESTION_TXT` ='".str_replace("#","",$_REQUEST["question_txt"])."', 
    					  `ANSWER_TXT`   ='".str_replace("#","",$_REQUEST["answer_txt"])."', 
    					  `MOUSE_OVER`   ='".str_replace("#","",$_REQUEST["mouse_over"])."', 
    					  `VOTE_BTN_BG`  ='".str_replace("#","",$_REQUEST["vote_btn_txt"])."', 
    					  `VOTE_BTN_TXT` ='".str_replace("#","",$_REQUEST["vote_btn"])."', 
    					  `TOTAL_VOTES`  ='".str_replace("#","",$_REQUEST["total_votes"])."', 
    					  `VOTES_BAR`    ='".str_replace("#","",$_REQUEST["votes_bar"])."' 
    				WHERE ID='".$_REQUEST["color_set_id"]."'";
		$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	} else {
		$sql = "INSERT INTO ".$TABLES["COLORS"]."
    				SET `ID`		       = null,	
      					`SET_NAME`     ='".mysql_escape_string(utf8_encode($_REQUEST["set_name"]))."', 
      					`POLL_BG` 	   ='".str_replace("#","",$_REQUEST["poll_bg"])."', 
      					`QUESTION_BG`  ='".str_replace("#","",$_REQUEST["question_bg"])."', 
      					`QUESTION_TXT` ='".str_replace("#","",$_REQUEST["question_txt"])."', 
      					`ANSWER_TXT`   ='".str_replace("#","",$_REQUEST["answer_txt"])."', 
      					`MOUSE_OVER`   ='".str_replace("#","",$_REQUEST["mouse_over"])."', 
      					`VOTE_BTN_BG`  ='".str_replace("#","",$_REQUEST["vote_btn_txt"])."', 
      					`VOTE_BTN_TXT` ='".str_replace("#","",$_REQUEST["vote_btn"])."', 
      					`TOTAL_VOTES`  ='".str_replace("#","",$_REQUEST["total_votes"])."', 
      					`VOTES_BAR`    ='".str_replace("#","",$_REQUEST["votes_bar"])."'";
	
		$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
		$_REQUEST["color_set_id"] = mysql_insert_id();
	};
	$_REQUEST["ac"] = 'color_sets'; $message = 'Color set saved.';
} elseif ($_REQUEST["ac"] == 'del_color_set') {
  if($_REQUEST["color_set_id"]==1){
    $message = 'You are not allowed to delete the Default Color Set.';
  }
  else{
  	$sql = "DELETE FROM ".$TABLES["COLORS"]."
  			    WHERE ID='".$_REQUEST["color_set_id"]."'";
  	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
  	$_REQUEST["color_set_id"] = 1;
	  $message = 'Color set deleted.';
	}
	$_REQUEST["ac"] = 'color_sets';

} elseif ($_REQUEST["ac"] == 'resetstats') {
	$sql = "DELETE FROM ".$TABLES["VOTES"]." WHERE QUESTION_ID='".$_REQUEST["qid"]."'";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	$message = 'Poll votes deleted.';
	$_REQUEST["ac"] = 'stats';
};


if (($_REQUEST["ac"]=='html' OR $_REQUEST["ac"]=='stats' OR $_REQUEST["ac"]=='poll' OR $_REQUEST["ac"]=='view_votes' OR $_REQUEST["ac"]=='save_poll') AND $_REQUEST["qid"]>0 ) {
	$sql = "SELECT * FROM ".$TABLES["QUESTIONS"]." WHERE id='".$_REQUEST["qid"]."'";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	$QUESTION = mysql_fetch_assoc($sql_result);
?>

<tr>
    <td height="32" align="left" style="background-image:url(images/sub-menu-back.jpg); background-repeat:repeat-y; font-size:12px">
      
      <table border="0" cellspacing="0" cellpadding="0" width="100%">



  
  <tr>
    <td width="15%" height="30" align="center" ><a href="admin.php?ac=poll&qid=<?php  echo $_REQUEST["qid"]; ?>" class="subMenu">
    <?php if ($_REQUEST["ac"]=='poll') echo '<ins>'; ?>Edit Poll
    <?php if ($_REQUEST["ac"]=='poll') echo '</ins>'; ?></a></td>
    <td width="15%" align="center" ><a href="admin.php?ac=stats&qid=<?php  echo $_REQUEST["qid"]; ?>" class="subMenu">
    <?php if ($_REQUEST["ac"]=='stats') echo '<ins>'; ?>Vote Statistic
    <?php if ($_REQUEST["ac"]=='stats') echo '</ins>'; ?>
    </a></td>
    <td width="15%" align="center"><a href="admin.php?ac=html&qid=<?php  echo $_REQUEST["qid"]; ?>" class="subMenu">
    <?php if ($_REQUEST["ac"]=='html') echo '<ins>'; ?>HTML code
    <?php if ($_REQUEST["ac"]=='html') echo '</ins>'; ?>
    </a></td>
    <td>&nbsp;</td>
    
  </tr>
  
</table>


 </td>
    </tr>
      <?php }; ?>
  <tr>
    <td align="left" valign="top" style="background-image:url(images/main-middle.jpg); background-repeat:repeat-y; padding:20px">
    
    

<?php
if(isset($message)) echo "<strong>".$message."</strong><br /><br />";
//////////////////////
//////////////////////
///   VIEW POLLS /////
//////////////////////
//////////////////////
if ( $_REQUEST["ac"] == 'current_polls') {
?>
Below you can see all the polls that you've created. You can create UNLIMITED amount of polls.<br />
&bull; To create a new poll, click on the Create a Poll link at the top.<br />
&bull; To edit a poll click on the Edit Poll link next to the poll question.<br />
&bull; To put a poll on your web page click on the HTML code link next to the poll question and follow the instructions.<br />
&bull; To view Vote statistic for each poll click on the Vote Statistic link next to the poll question.<br />
&bull; To delete a poll click on the 'DELETE' link next to the poll question.<br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #CCCCCC" bgcolor="#FFFFFF">
  <tr>
    <td width="344" valign="top" bgcolor="#CCCCCC"><strong>Polls</strong></td>
    <td width="85" valign="top" bgcolor="#CCCCCC"><strong>Total Votes </strong></td>
    <td bgcolor="#CCCCCC" colspan="4">&nbsp;</td>
  </tr>

<?php
	$sql = "SELECT * FROM ".$TABLES["QUESTIONS"]." 
    			WHERE ID > 1	 
    			ORDER BY QUESTION ASC";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
    $rowCount = 1;
	while ($POLL = mysql_fetch_assoc($sql_result)) {

		$sql = "SELECT COUNT(*) as cnt FROM ".$TABLES["VOTES"]." WHERE QUESTION_ID = '".$POLL["ID"]."'";
		$sql_result_total = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
		$total_votes = mysql_fetch_assoc($sql_result_total);
?>
  <tr>
    <td style="border-bottom:1px solid #CCCCCC; cursor:pointer" id="c1-<?php echo $rowCount; ?>" onMouseOver="highlightrow(<?php echo $rowCount; ?>,6)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,6)" onclick="window.location.href='admin.php?ac=poll&qid=<?php echo $POLL["ID"]; ?>'"><?php echo stripslashes(utf8_decode($POLL["QUESTION"])); ?></td>
    <td style="border-bottom:1px solid #CCCCCC" id="c2-<?php echo $rowCount; ?>" onMouseOver="highlightrow(<?php echo $rowCount; ?>,6)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,6)"><?php echo $total_votes["cnt"]; ?></td>
    <td width="54" align="left" id="c3-<?php echo $rowCount; ?>" style="border-bottom:1px solid #CCCCCC" onMouseOver="highlightrow(<?php echo $rowCount; ?>,6)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,6)"><a href='admin.php?ac=poll&qid=<?php echo $POLL["ID"]; ?>'><strong>Edit Poll </strong></a></td>
	<td width="74" align="left" id="c4-<?php echo $rowCount; ?>" style="border-bottom:1px solid #CCCCCC" onMouseOver="highlightrow(<?php echo $rowCount; ?>,6)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,6)"><a href='admin.php?ac=html&qid=<?php echo $POLL["ID"]; ?>'><strong>HTML code</strong></a></td>
    <td width="87" align="left" id="c5-<?php echo $rowCount; ?>" style="border-bottom:1px solid #CCCCCC" onMouseOver="highlightrow(<?php echo $rowCount; ?>,6)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,6)"><a href='admin.php?ac=stats&qid=<?php echo $POLL["ID"]; ?>'><strong>Vote Statistic</strong></a></td>
    <td width="58" align="left" id="c6-<?php echo $rowCount; ?>" style="border-bottom:1px solid #CCCCCC" onMouseOver="highlightrow(<?php echo $rowCount; ?>,6)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,6)"><a href='#' onClick='pass=confirm("Are you sure you want to delete it?",""); if (pass) window.location="admin.php?ac=del_poll&qid=<?php echo $POLL["ID"]; ?>";'><strong style='color:red'>DELETE</strong></a></td>
  </tr>
  <?php
  		$rowCount++;
	};
?>
</table>
<?php
//////////////////////
//////////////////////
/// VOTES STATS  ////
//////////////////////
//////////////////////
} elseif ($_REQUEST["ac"] == 'stats') {
	$sql = "SELECT * FROM ".$TABLES["QUESTIONS"]." 
		    	WHERE ID = '".$_REQUEST["qid"]."'";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	$POLL = mysql_fetch_assoc($sql_result);
?>
&nbsp;&nbsp;&nbsp;&nbsp;Below you can see voting statistic. Please, note that this is the actual voting statistic and does not include the starting vote's amount for your answers. To view detailed voting statistic including IP address, date &amp; time and selected answers <a href="admin.php?ac=view_votes&amp;qid=<?php echo $_REQUEST["qid"]; ?>">click here</a>. To delete all votes <a href='#' onClick='pass=confirm("Are you sure you want to delete all poll votes?",""); if (pass) window.location="admin.php?ac=resetstats&qid=<?php echo $_REQUEST["qid"]; ?>";'>click here</a><br /><br />
<table width="600" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #CCCCCC" bgcolor="#FFFFFF">
  <tr>
    <td width="205" bgcolor="#CCCCCC">Total votes: </td>
    <td colspan="2" style="border-bottom:1px solid #CCCCCC"><strong>
      <?php
      	$sql = "SELECT ID FROM ".$TABLES["VOTES"]." WHERE QUESTION_ID = '".$POLL["ID"]."'";
      	$sql_result_votes = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
      	echo $total = mysql_num_rows($sql_result_votes);
      ?>
    </strong></td>
    </tr>
  <tr>
    <td bgcolor="#CCCCCC">Todays votes: </td>
    <td colspan="2" style="border-bottom:1px solid #CCCCCC"><strong>
      <?php
	$sql = "SELECT * FROM ".$TABLES["VOTES"]." WHERE QUESTION_ID = '".$POLL["ID"]."' AND DATE_FORMAT(DT, '%Y-%m-%d') = CURDATE()";
	$sql_result_votes = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	echo mysql_num_rows($sql_result_votes);
?>
    </strong></td>
    </tr>
  <tr>
    <td bgcolor="#CCCCCC"><strong>Answers votes </strong></td>
    <td width="109" bgcolor="#CCCCCC" style="border-bottom:1px solid #CCCCCC"><strong>Percent</strong></td>
    <td width="266" bgcolor="#CCCCCC" style="border-bottom:1px solid #CCCCCC"><strong>Amount</strong></td>
  </tr>
  
  <?php
    	$sql = "SELECT * FROM ".$TABLES["ANSWERS"]." WHERE QUESTION_ID = '".$POLL["ID"]."' ORDER BY ORDER_ID";
    	$sql_result_answers = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	    $rowCount = 1;
		while ($ANSWER = mysql_fetch_assoc($sql_result_answers)) {
		  $sql = "SELECT * FROM ".$TABLES["VOTES"]." WHERE QUESTION_ID ='".$POLL["ID"]."' AND ANSWER_ID ='".$ANSWER["ORDER_ID"]."'";
		  $sql_resultC = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
		  $count = mysql_num_rows($sql_resultC);
    		
   		  $percent = round(($count / $total) * 100,2);
  ?>
  <tr>
    <td bgcolor="#CCCCCC"><?php echo stripslashes(utf8_decode($ANSWER["ANSWER"])); ?></td>
    <td style="border-bottom:1px solid #CCCCCC" id="c1-<?php echo $rowCount; ?>" onMouseOver="highlightrow(<?php echo $rowCount; ?>,2)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,2)"><?php echo $percent.'%'; ?></td>
    <td style="border-bottom:1px solid #CCCCCC" id="c2-<?php echo $rowCount; ?>" onMouseOver="highlightrow(<?php echo $rowCount; ?>,2)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,2)"><?php echo $count; ?></td>
  </tr>
<?php
	    $rowCount++;
	};
?>
</table>

<?php
//////////////////////
//////////////////////
// DETAILED STATS  ///
//////////////////////
//////////////////////
} elseif ($_REQUEST["ac"] == 'view_votes') {
	$sql = "SELECT * FROM ".$TABLES["QUESTIONS"]." 
          WHERE ID = '".$_REQUEST["qid"]."'";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	$QUESTION = mysql_fetch_assoc($sql_result);
?>
<a href="admin.php?ac=stats&qid=<?php echo $_REQUEST["qid"]; ?>">back to statistic summary </a><br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #CCCCCC" bgcolor="#FFFFFF">
  <tr>
    <td width="112" valign="top" bgcolor="#CCCCCC"><strong>Date &amp; time </strong></td>
    <td width="124" valign="top" bgcolor="#CCCCCC"><strong>IP address </strong></td>
    <td width="444" valign="top" bgcolor="#CCCCCC"><strong>Answer</strong></td>
  </tr>
<?php
	if (!isset($_REQUEST["st"])) { $st=0; } else { $st=$_REQUEST["st"]; };
	$sql = "SELECT * FROM ".$TABLES["VOTES"]." 
          WHERE QUESTION_ID = '".$_REQUEST["qid"]."' 
          ORDER BY dt DESC LIMIT $st,25";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
    $rowCount = 1;
	while ($VOTES = mysql_fetch_assoc($sql_result)) {
		$sql = "SELECT * FROM ".$TABLES["ANSWERS"]." WHERE ORDER_ID ='".$VOTES["ANSWER_ID"]."' AND QUESTION_ID='".$_REQUEST["qid"]."'";
		$sql_result_answer = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
		$ANAME = mysql_fetch_assoc($sql_result_answer);
?>
  <tr>
    <td style="border-bottom:1px solid #CCCCCC" id="c1-<?php echo $rowCount; ?>" onMouseOver="highlightrow(<?php echo $rowCount; ?>,3)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,3)"><?php echo $VOTES["DT"]; ?></td>
    <td style="border-bottom:1px solid #CCCCCC" id="c2-<?php echo $rowCount; ?>" onMouseOver="highlightrow(<?php echo $rowCount; ?>,3)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,3)"><?php echo $VOTES["IP"]; ?></td>
    <td style="border-bottom:1px solid #CCCCCC" id="c3-<?php echo $rowCount; ?>" onMouseOver="highlightrow(<?php echo $rowCount; ?>,3)" onMouseOut="outhighlightrow(<?php echo $rowCount; ?>,3)"><?php echo stripslashes(utf8_decode($ANAME["ANSWER"])); ?></td>
  </tr>
<?php
		$rowCount++;
	};
?>
  <tr>
    <td bgcolor="#CCCCCC" colspan="3">
<?php
	if ($st>0) { 
		$prev=$st-25; 
		echo "<a href='admin.php?ac=stats&qid=".$_REQUEST["qid"]."&st=0'><<</a>&nbsp;&nbsp;"; 
		echo "<a href='admin.php?ac=stats&qid=".$_REQUEST["qid"]."&st=$prev'><</a>&nbsp;&nbsp;"; 
	};

	$sql = "SELECT * FROM ".$TABLES["VOTES"]." 
          WHERE QUESTION_ID ='".$_REQUEST["qid"]."' 
          ORDER BY DT DESC";
	$sql_result_count = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	$count_rows = mysql_num_rows($sql_result_count);
	$total_pages = ceil($count_rows/25);
	$current_page = $st / 25 + 1;
	echo " <strong>page $current_page of $total_pages</strong> ";
	if ($st<$count_rows-25) {
		$next=$st+25;
		echo "<a href='admin.php?ac=view_votes&qid=".$_REQUEST["qid"]."&st=$next'>></a>&nbsp;&nbsp;";
		$lastid=0;
		while ($lastid<$count_rows-25) {
			$lastid=$lastid+25;
		};
		echo "<a href='admin.php?ac=view_votes&qid=".$_REQUEST["qid"]."&st=$lastid'>>></a>&nbsp;&nbsp;"; 
	};
?></td>
  </tr>
</table>
<?php
//////////////////////
//////////////////////
///   ADD POLL   /////
//////////////////////
//////////////////////
} elseif ( $_REQUEST["ac"] == 'poll') {
if ($_REQUEST["qid"]>0) {
	$sql = "SELECT * FROM ".$TABLES["QUESTIONS"]." 
          WHERE ID = '".$_REQUEST["qid"]."'";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	$QUESTION = mysql_fetch_assoc($sql_result);
} else {
	$QUESTION["COLOR_SET_ID"] = 1; 
	$QUESTION["DAYS"] = 0;
	$QUESTION["WIDTH"] = 160;
	$QUESTION["BTN_MSG"] = 'Vote';
	$QUESTION["TOTAL_MSG"] = 'Total: ';
	$QUESTION["ON_VOTE"] = 'total';
	$QUESTION["VIEW_RESULTS"] = "true";
	$QUESTION["VIEW_RESULTS_TITLE"] = "vote results";
};
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td width="50%" valign="top">
<form action="admin.php" method="post" name="frm">
  <input type="hidden" name="ac" value="save_poll">
  <input type="hidden" name="qid" value="<?php echo $_REQUEST["qid"]; ?>">
  <input type="hidden" name="openme" value="<?php echo $_REQUEST["openme"]; ?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="49%" valign="top">
    	  <input type="button" name="btnStep1" id="btnStep1" style='width:130px' value="Question & answers" onclick="LoadStep('1'); document.frm.openme.value='1';" />
    	  <input type="button" name="btnStep2" id="btnStep2" style='width:60px' value="Options" onclick="LoadStep('2'); document.frm.openme.value='2';" />
    	  <input type="button" name="btnStep3" id="btnStep3" style='width:90px' value="Start / Stop" onclick="LoadStep('3'); document.frm.openme.value='3';" />
    	  <input type="submit" name="btnStep4" id="btnStep4" style='width:80px' value="Save poll" />
	   </td>
	   </tr>
	</table>  
		  <div id="step1" style='display:block'>	
  	  <table width="100%" border="0" cellspacing="4" cellpadding="5">
        <tr>
          <td bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC"><p>Below you can enter poll question, select number of answers and enter them. You can set starting vote amount for each of your answers in case you do not want to have an answer with 0 votes. To delete an answer just delete it from its text box and click on Save poll button.</p>
            You can use HTML code in the question and answers to change font style or to insert images. <a href="examples.php" target="_blank">Examples here</a>.</td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC"><strong>Poll question:</strong><br />
            <input name="question" type="text"  id="question" size="50" style="margin-top:4px" value="<?php echo str_replace('"','&quot;',stripslashes(utf8_decode($QUESTION["QUESTION"]))); ?>"> 
          <br>
<?php
  $txt = "";
  if ($_REQUEST["qid"]>0) {
  	$sql = "SELECT * FROM ".$TABLES["ANSWERS"]."
            WHERE QUESTION_ID = '".$_REQUEST["qid"]."'  
            ORDER BY ORDER_ID";
  	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
  	while ($ANSWER = mysql_fetch_assoc($sql_result)) {
  	  $answerNum = $ANSWER["ORDER_ID"] - 1;   
      $txt = $txt . '<input name="answer'.$answerNum.'" type="text"  id="answer'.$answerNum.'" size="30" style="margin-top:4px" value="'.str_replace('"','&quot;',stripslashes(utf8_decode($ANSWER["ANSWER"]))).
                    '"> votes count <input name="start'.$answerNum.'" type="text"  id="start'.$answerNum.'" size="2" maxlength="4" value="'.$ANSWER["COUNT"].'"><br>';
  	}
	$_REQUEST["aNum"] = mysql_num_rows($sql_result) - 2;
  }
  else{
    if (!isset($_REQUEST["aNum"])) $_REQUEST["aNum"] = 10 - 2;
    for($i=0;$i<$_REQUEST["aNum"]+2;$i++){
      $txt = $txt . '<input name="answer'.$i.'" type="text"  id="answer'.$i.'" size="30" value="" style="margin-top:4px"> votes count <input name="start'.$i.'" type="text"  id="start'.$i.'" size="2" maxlength="4" value="0"><br>';
    }
  }	
?>
           <br /> How many answers do you want: 
             <select name="aNum" onchange='FillAnswerArea(this.value)'>
													
					<?php
						for ($i=0;$i<($SETTINGS["maxAnswers"]-1);$i++){
							echo '<option value="'.($i+2).'" '; 
							if($_REQUEST["aNum"] == $i) echo "selected"; 
							echo '>'.($i+2).'</option>';
						}
					?>
                        </select><br />
            <div id="answerArea">
              <?php echo $txt; ?>
           </div>   
		   </td>
        </tr>
        </table>
        
	  	  <table width="100%" border="0" cellspacing="4" cellpadding="5">
        <tr>
          <td width="50%" bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC"><input type="button" name="btnDivOption" id="btnDivOption" value="Options" onclick="LoadStep('2'); document.frm.openme.value='2'; " style="font-size:16px; cursor:pointer; font-weight:bold">          </td>
          <td width="50%" align="right" bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC"><input type="submit" name="btnStep42" id="btnStep42" style="font-size:16px; cursor:pointer; font-weight:bold" value="Save poll" /></td>
        </tr>
       </table>
        
  		  </div>	
        <div id="step2" style='display:none'>
	  <table width="100%" border="0" cellspacing="4" cellpadding="5">
         <tr>
				  <td bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC" colspan="2">Below you can set various poll options. You can either select a color set from the available ones. To create a new color set click on the 'Color Sets' link on the top but be sure to save your poll before this because all changes you've done will be lost. Poll height depends on the length of your answers, question and width chosen so you may need to adjust it so all  result bars are visible when you vote. 'Limit votes' is used to prevent multiple votes using the same computer. You can set number of days between each vote. You can set it to 0 and this will allow unlimited votes using the same computer. At the end, you can configure poll messages and the way your poll will display them. </td>
				  </tr>
         <tr>
          <td bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td>Font name: </td>
                <td><select name="font" id="font" style="width:120px">
					<?php
						foreach($fonts_arr as $value){
							if($QUESTION["FONT"]==$value)
								echo "<option value='".$value."'  style='font-family:".$value."' selected>".$value."</option>";
							else
								echo "<option value='".$value."' style='font-family:".$value."'>".$value."</option>";		
						}
					?>
					</select></td>
              </tr>
                  
            <tr>
              <td >Color Set:</td>
                    <td ><select name="color_set_id" >
                  <?php
	$sql = "SELECT * FROM ".$TABLES["COLORS"]." ORDER BY SET_NAME ASC";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	while ($COLOR = mysql_fetch_assoc($sql_result)) {
		if ($QUESTION["COLOR_SET_ID"] == $COLOR["ID"]) { $selected=' selected'; } else { $selected=''; };
		echo "<option value='".$COLOR["ID"]."'$selected>".utf8_decode($COLOR["SET_NAME"])."</option>";
	};
?>
              </select></td>
            </tr>
            <tr>
              <td width="43%" >Poll Width:</td>
              <td width="57%" ><input name="width" type="text" id="width" value="<?php echo $QUESTION["WIDTH"]; ?>" size="3" maxlength="3" />
                pixels </td>
            </tr>
            <tr>
              <td >Limit votes: </td>
              <td ><input name="days" type="text" size="3" maxlength="3" value="<?php echo $QUESTION["DAYS"]; ?>" />
                days</td>
            </tr>
            <tr>
              <td >Allow multiple votes: </td>
              <td ><input type="checkbox" name="multiple_votes" value="true" <?php if($QUESTION["MULTIPLE_VOTES"]=="true") echo 'checked="checked"'; ?> /></td>
            </tr>            
  <tr>
    <td colspan="2"  style="border-top:1px solid #5D5D5D">When people vote show results as:<br />
        <?php if ($QUESTION["SHOW_RESULT"]=='') $QUESTION["SHOW_RESULT"]='amount_percent'; ?>
        <input type="radio" name="results" value="amount"<?php if ($QUESTION["SHOW_RESULT"]=='amount') { echo ' checked="checked"'; }; ?> />
      number<br />
      <input type="radio" name="results" value="percent"<?php if ($QUESTION["SHOW_RESULT"]=='percent') { echo ' checked="checked"'; }; ?> />
      percent<br />
      <input type="radio" name="results" value="amount_percent"<?php if ($QUESTION["SHOW_RESULT"]=='amount_percent') { echo ' checked="checked"'; }; ?> />
      number and percent<br />
      <input type="radio" name="results" value="nothing"<?php if ($QUESTION["SHOW_RESULT"]=='nothing') { echo ' checked="checked"'; }; ?> />
      do	not	show	results </td>
  </tr>
  <tr>
    <td colspan="2"  style="border-top:1px solid #5D5D5D">When people vote replace Vote button with:<br />
        <?php if ($QUESTION["ON_VOTE"]=='') $QUESTION["ON_VOTE"]='total'; ?>
        <input type="radio" name="onvote" value="message"<?php if ($QUESTION["ON_VOTE"]=='message') { echo ' checked="checked"'; }; ?> />
      with custom message
      <input name="msg" type="text" size="20" maxlength="30" value="<?php echo str_replace('"','&quot;',stripslashes(utf8_decode($QUESTION["CUSTOM_MSG"]))); ?>" />
      <br />
      <input type="radio" name="onvote" value="total"<?php if ($QUESTION["ON_VOTE"]=='total') { echo ' checked="checked"'; }; ?> />
      total votes and custom message
      <input name="totalMsg" type="text"  id="totalMsg" value="<?php echo str_replace('"','&quot;',stripslashes(utf8_decode($QUESTION["TOTAL_MSG"]))); ?>" size="20" maxlength="30" />
      <br />
      <input type="radio" name="onvote" value="nothing"<?php if ($QUESTION["ON_VOTE"]=='nothing') { echo ' checked="checked"'; }; ?> />
      nothing </td>
  </tr>
  <tr>
    <td style="border-top:1px solid #5D5D5D">Vote button title: </td>
    <td style="border-top:1px solid #5D5D5D"><input name="voteMsg" type="text"  id="voteMsg" value="<?php echo str_replace('"','&quot;',stripslashes(utf8_decode($QUESTION["BTN_MSG"]))); ?>" size="30" maxlength="30" /></td>
  </tr>
              </table>
			  </td>
			  </tr>
        </table>	

	  	  <table width="100%" border="0" cellspacing="4" cellpadding="5">
        <tr>
          <td width="50%" bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC">
           <input type="button" name="btnDivSS" id="btnDivSS" value="Start / Stop" onclick="LoadStep('3'); document.frm.openme.value='3';" style="font-size:16px; cursor:pointer; font-weight:bold">          </td>
          <td width="50%" align="right" bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC"><input type="submit" name="btnStep42" id="btnStep42"  style="font-size:16px; cursor:pointer; font-weight:bold" value="Save poll" /></td>
        </tr>
       </table>
        
	     </div>
             <div id="step3" style='display:none'>
				  <?php
				  	$sYear  = $QUESTION["POLL_START"][0].$QUESTION["POLL_START"][1].$QUESTION["POLL_START"][2].$QUESTION["POLL_START"][3];
						$sMonth = $QUESTION["POLL_START"][5].$QUESTION["POLL_START"][6];
						$sDay   = $QUESTION["POLL_START"][8].$QUESTION["POLL_START"][9];
						$sHour  = $QUESTION["POLL_START"][11].$QUESTION["POLL_START"][12];
						$sMin   = $QUESTION["POLL_START"][14].$QUESTION["POLL_START"][15];
						
				  	$eYear  = $QUESTION["POLL_END"][0].$QUESTION["POLL_END"][1].$QUESTION["POLL_END"][2].$QUESTION["POLL_END"][3];
						$eMonth = $QUESTION["POLL_END"][5].$QUESTION["POLL_END"][6];
						$eDay   = $QUESTION["POLL_END"][8].$QUESTION["POLL_END"][9];
						$eHour  = $QUESTION["POLL_END"][11].$QUESTION["POLL_END"][12];
						$eMin   = $QUESTION["POLL_END"][14].$QUESTION["POLL_END"][15];
				  ?>
             	  <table width="100%" border="0" cellspacing="4" cellpadding="5">
                  <tr>
                    <td bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC">Here you can specify start and stop time for your poll. To do this, click on 'Enable Start / Stop' and select start and stop dates and times. 'Current server time' is the one used to calculate start and stop times. You can also stop the poll and only let people see poll results by clicking on 'Stop poll' checkbox. </td>
                  </tr>
                  <tr>
		            <td bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC">
	                <table width="100%" border="0" cellspacing="0" cellpadding="2">
  		  	      <tr>
  		  	        <td width="42%">Stop poll:</td>
  		  	        <td width="58%"><input name="pollDisable" type="checkbox" id="pollDisable" value="false" <?php if ( $QUESTION["ALLOW_VOTE"] == "false" ) echo "checked"; ?> />
  		  	          </td>
		  	        </tr>
  		  	      <tr>
  		  	        <td>Enable Start / Stop:</td>
  		  	        <td><input name="useTimeInterval" type="checkbox" id="useTimeInterval" value="true" <?php if ( $QUESTION["USE_TIME_INTERVAL"] == "true" ) echo "checked"; ?> onclick="useTime(this.checked)" /></td>
		  	        </tr>
  		  	      <tr>
					<td>Current server time: </td>
					<td><?php echo date("dS \of F Y G:i", time()); ?></td>
				</tr>

					<?php if( $QUESTION["USE_TIME_INTERVAL"] == "true" ) $isDisabled = "";
						    else $isDisabled = "disabled=\"true\"";
						    
						    if(($sYear==0) and ($sMonth==0) and ($sDay==0) and ($eYear==0) and ($eMonth==0) and ($eDay==0)){
    						    $cDate = date("Y-m-d", time());
    						    list($sYear, $sMonth, $sDay) = split("-",$cDate);
    						    $eYear = $sYear; 
                    $eMonth = $sMonth;
    						    $eDay = $sDay + 1;
						    }
					?>
	                <tr>
                    <td>Start date:</td>
					<td>
						<table width="100%">
							<tr>
								<td>
								  <select name="sMonth" id="sMonth" <?php echo $isDisabled; ?>>
									<?php
										  for ($i=1; $i<13; $i++) {
											if ($sMonth==$i) { $selected=' selected="selected"'; } else { $selected=''; };
											echo '<option value="'.$i.'"'.$selected.'>'.$monthnames_arr[$i].'</option>';
										  };
									?>
								  </select>
								  <select name="sDay" id="sDay" <?php echo $isDisabled; ?>>
								  <?php
								  for ($i=1; $i<32; $i++) {
									if ($sDay==$i) { $selected=' selected="selected"'; } else { $selected=''; };
									echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
								  };
								  ?>
								  </select>
								  <select name="sYear" id="sYear" <?php echo $isDisabled; ?>>
								  <?php
								  for ($i=2007; $i<2012; $i++) {
									if ($sYear==$i) { $selected=' selected="selected"'; } else { $selected=''; };
									echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
								  };
								  ?>
								  </select>								</td>
							 </tr>
						 </table>					</td>	
                  </tr>
                  <tr>
                    <td>Start time:</td>
					<td>
						<table width="100%">
							<tr>
								<td>
								  <select name="sHour" id="sHour" <?php echo $isDisabled; ?>>
									<?php
										  for ($i=0; $i<24; $i++) {
											if ($sHour==$i) { $selected=' selected="selected"'; } else { $selected=''; };
											echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
										  };
									?>
								  </select>
								  <select name="sMin" id="sMin" <?php echo $isDisabled; ?>>
								  <?php
									  for ($i=0; $i<60; $i = ($i+5)) {
										if ($sMin==$i) { $selected=' selected="selected"'; } else { $selected=''; };
										echo '<option value="'.$i.'"'.$selected.'>';
										if($i == 0) echo "00";
										else echo $i;
										echo '</option>';
									  };
								  ?>
								  </select>								</td>
							</tr>
						</table>					</td>	
                  </tr>
                  <tr>
                    <td >Stop date: </td>
					<td>
						<table width="100%">
							<tr>
								<td>
								  <select name="eMonth" id="eMonth" <?php echo $isDisabled; ?>>
									<?php
										  for ($i=1; $i<13; $i++) {
											if ($eMonth==$i) { $selected=' selected="selected"'; } else { $selected=''; };
											echo '<option value="'.$i.'"'.$selected.'>'.$monthnames_arr[$i].'</option>';
										  };
									?>
								  </select>
								  <select name="eDay" id="eDay" <?php echo $isDisabled; ?>>
								  <?php
								  for ($i=1; $i<32; $i++) {
									if ($eDay==$i) { $selected=' selected="selected"'; } else { $selected=''; };
									echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
								  };
								  ?>
								  </select>
								  <select name="eYear" id="eYear" <?php echo $isDisabled; ?>>
								  <?php
								  for ($i=2007; $i<2012; $i++) {
									if ($eYear==$i) { $selected=' selected="selected"'; } else { $selected=''; };
									echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
								  };
								  ?>
								  </select>								</td>
							</tr>
						</table>					</td>	
                  </tr>
                  <tr>
                    <td>Stop time:</td>
					<td>
						<table width="100%">
							<tr>
								<td>
								  <select name="eHour" id="eHour" <?php echo $isDisabled; ?>>
									<?php
										  for ($i=0; $i<24; $i++) {
											if ($eHour==$i) { $selected=' selected="selected"'; } else { $selected=''; };
											echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
										  };
									?>
								  </select>
								  <select name="eMin" id="eMin" <?php echo $isDisabled; ?>>
								  <?php
									  for ($i=0; $i<60; $i = ($i+5)) {
										if ($eMin==$i) { $selected=' selected="selected"'; } else { $selected=''; };
										echo '<option value="'.$i.'"'.$selected.'>';
										if($i == 0) echo "00";
										else echo $i;
										echo '</option>';
									  };
								  ?>
								  </select>								</td>
							</tr>
						</table>					</td>	
                  </tr>
              </table>
			  </td>
        </tr>
        </table>

	  	  <table width="100%" border="0" cellspacing="4" cellpadding="5">
        <tr>
          <td width="50%" bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC">&nbsp;</td>
          <td width="50%" align="right" bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC"><input type="submit" name="btnStep42" id="btnStep42"  style="font-size:16px; cursor:pointer; font-weight:bold" value="Save poll" /></td>
        </tr>
       </table>
	  </div>

<?php if ($_REQUEST["openme"]>0) { ?>
<script language="javascript">
LoadStep(<?php echo $_REQUEST["openme"]; ?>);
</script>  
<?php }; ?>
	
</form>    		  
</td><td width="50%" valign="top" align="center">
<?php if ($_REQUEST["qid"]>0) { ?>
	<script language="javascript" src="<?php echo $SETTINGS["installFolder"]; ?>load.php?id=<?php echo $_REQUEST["qid"]; ?>"></script>
<?php }; ?>       
</td></tr></table>

       
<?php
//////////////////////
//////////////////////
//// HTML CODE ///////
//////////////////////
//////////////////////
} elseif ($_REQUEST["ac"] == 'html') {
?>        
 &nbsp;&nbsp;&nbsp;&nbsp; Select the HTML code below and copy it. Open your web page using some web page editing software and paste the code where you want the poll to appear on the page. Once you put this code on your web page, you can make changes to your poll using the administration page but there is no need to update the HTML code. Please, note that you should NOT change the code below. In case you do so, it is quite possible that your poll will not work properly.</p>
<textarea name="textarea" rows="16" style="width:720px" >
	<script language="javascript" src="<?php echo $SETTINGS["installFolder"]; ?>load.php?&id=<?php echo $_REQUEST["qid"]; ?>"></script>
</textarea>
<?php
//////////////////////
//////////////////////
//// COLOR SETS  /////
//////////////////////
//////////////////////
} elseif ($_REQUEST["ac"] == 'color_sets') {

	if ($_REQUEST["color_set_id"] > 0) {
  		$sql = "UPDATE ".$TABLES["QUESTIONS"]." 
	            SET `COLOR_SET_ID` = '".$_REQUEST["color_set_id"]."'
                WHERE ID ='1'";
        $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	}
?>You can create unlimited amount of color sets and use them when creating a poll. You can change color sets at any time and changes will apply to all polls using the changed color set.
	      <li>To create new Color Set enter its' name and the HTML color codes for all poll parts.</li>
	      <li>To edit a Color Set select it from the drop down menu below and click on 'Edit' button, edit colors and save it. </li>
	      <li>To delete a Color Set select it from the drop down menu below and click on 'Delete' button </li>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="50%" valign="top">
	    <table width="100%" border="0" cellspacing="4" cellpadding="5">
        <tr>
          <td bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC"><form action="admin.php" method="post" style="margin:0px; padding:0px" name="frmac">
			<input type="hidden" name="ac" value="">
            <select name="color_set_id" >
<?php
	$sql = "SELECT * FROM ".$TABLES["COLORS"]." ORDER BY SET_NAME ASC";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	while ($COLOR = mysql_fetch_assoc($sql_result)) {
		if ($_REQUEST["color_set_id"] == $COLOR["ID"]) { $selected=' selected'; } else { $selected=''; };
		echo "<option value='".$COLOR["ID"]."'$selected>".stripslashes(utf8_decode($COLOR["SET_NAME"]))."</option>";
	};
?>			
			</select>
            <input name="submit2" type="submit" onclick="document.frmac.ac.value='color_sets'" value="Edit" style="font-size:16px; cursor:pointer; font-weight:bold" />
            <input name="submit" type="submit" onclick="document.frmac.ac.value='del_color_set'" value="Delete" style="font-size:16px; cursor:pointer; font-weight:bold" />
		  	</form>		   </td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF"  style="border:1px solid #CCCCCC">

<?php
if ($_REQUEST["color_set_id"] > 0) {
	$sql = "SELECT * FROM ".$TABLES["COLORS"]." WHERE ID='".$_REQUEST["color_set_id"]."'";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	$COLOR_SET_DATA = mysql_fetch_assoc($sql_result);
};
?>	
	  	<form action="admin.php" method="post" style="margin:0px; padding:0px" name="frmColors">
		<input type="hidden" name="ac" value="save_color_set">
		<input type="hidden" name="color_set_id" value="<?php echo $_REQUEST["color_set_id"]; ?>">
		  <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td colspan="2" ><strong>Manage color set </strong></td>
              </tr>
            <tr>
              <td >Color set name </td>
              <td ><label>
                <input name="set_name" type="text"  id="set_name" value="<?php echo str_replace('"','&quot;',stripslashes(utf8_decode($COLOR_SET_DATA["SET_NAME"]))); ?>" size="30" maxlength="30">
              </label></td>
            </tr>
            <tr>
              <td width="43%" >Poll background</td>
              <td width="57%" >        
				<input name="poll_bg" type="text" id="poll_bg" style="background-color:#<?php echo $COLOR_SET_DATA["POLL_BG"]; ?>; color:#<?php echo oppColor($COLOR_SET_DATA["POLL_BG"]); ?>" size="8" maxlength="7" value="<?php echo $COLOR_SET_DATA["POLL_BG"]; ?>" /> <a href="#" onClick="cp.select(frmColors.poll_bg,'pick2');return false;" name="pick2" id="pick2"><img src="images/paintspill16.gif" alt="Select color" title="Select color" name="Image99" width="16" height="16" border="0" align="absmiddle" id="Image99" /></a>        
        		</td>
            </tr>
            <tr>
              <td >Question background</td>
              <td >
				<input name="question_bg" type="text" id="question_bg" style="background-color:#<?php echo $COLOR_SET_DATA["QUESTION_BG"]; ?>; color:#<?php echo oppColor($COLOR_SET_DATA["QUESTION_BG"]); ?>" size="8" maxlength="7" value="<?php echo $COLOR_SET_DATA["QUESTION_BG"]; ?>" /> <a href="#" onClick="cp.select(frmColors.question_bg,'pick2');return false;" name="pick2" id="pick2"><img src="images/paintspill16.gif" alt="Select color" title="Select color" name="Image99" width="16" height="16" border="0" align="absmiddle" id="Image99" /></a>         
        	  </td>
            </tr>
            <tr>
              <td >Question text</td>
              <td >
				<input name="question_txt" type="text" id="question_txt" style="background-color:#<?php echo $COLOR_SET_DATA["QUESTION_TXT"]; ?>; color:#<?php echo oppColor($COLOR_SET_DATA["QUESTION_TXT"]); ?>" size="8" maxlength="7" value="<?php echo $COLOR_SET_DATA["QUESTION_TXT"]; ?>" /> <a href="#" onClick="cp.select(frmColors.question_txt,'pick2');return false;" name="pick2" id="pick2"><img src="images/paintspill16.gif" alt="Select color" title="Select color" name="Image99" width="16" height="16" border="0" align="absmiddle" id="Image99" /></a>         
        	  </td>
            </tr>
            <tr>
              <td >Answers text</td>
              <td >
				<input name="answer_txt" type="text" id="answer_txt" style="background-color:#<?php echo $COLOR_SET_DATA["ANSWER_TXT"]; ?>; color:#<?php echo oppColor($COLOR_SET_DATA["ANSWER_TXT"]); ?>" size="8" maxlength="7" value="<?php echo $COLOR_SET_DATA["ANSWER_TXT"]; ?>" /> <a href="#" onClick="cp.select(frmColors.answer_txt,'pick2');return false;" name="pick2" id="pick2"><img src="images/paintspill16.gif" alt="Select color" title="Select color" name="Image99" width="16" height="16" border="0" align="absmiddle" id="Image99" /></a>           
        	  </td>
            </tr>
            <tr>
              <td >On mouse over</td>
              <td >
				<input name="mouse_over" type="text" id="mouse_over" style="background-color:#<?php echo $COLOR_SET_DATA["MOUSE_OVER"]; ?>; color:#<?php echo oppColor($COLOR_SET_DATA["MOUSE_OVER"]); ?>" size="8" maxlength="7" value="<?php echo $COLOR_SET_DATA["MOUSE_OVER"]; ?>" /> <a href="#" onClick="cp.select(frmColors.mouse_over,'pick2');return false;" name="pick2" id="pick2"><img src="images/paintspill16.gif" alt="Select color" title="Select color" name="Image99" width="16" height="16" border="0" align="absmiddle" id="Image99" /></a>          
        	  </td>
            </tr>
            <tr>
              <td >Vote button background</td>
              <td >
 				<input name="vote_btn_txt" type="text" id="vote_btn_txt" style="background-color:#<?php echo $COLOR_SET_DATA["VOTE_BTN_BG"]; ?>; color:#<?php echo oppColor($COLOR_SET_DATA["VOTE_BTN_BG"]); ?>" size="8" maxlength="7" value="<?php echo $COLOR_SET_DATA["VOTE_BTN_BG"]; ?>" /> <a href="#" onClick="cp.select(frmColors.vote_btn_txt,'pick2');return false;" name="pick2" id="pick2"><img src="images/paintspill16.gif" alt="Select color" title="Select color" name="Image99" width="16" height="16" border="0" align="absmiddle" id="Image99" /></a>         
        	  </td>
            </tr>
            <tr>
              <td >Vote button text</td>
              <td >
 				<input name="vote_btn" type="text" id="vote_btn" style="background-color:#<?php echo $COLOR_SET_DATA["VOTE_BTN_TXT"]; ?>; color:#<?php echo oppColor($COLOR_SET_DATA["VOTE_BTN_TXT"]); ?>" size="8" maxlength="7" value="<?php echo $COLOR_SET_DATA["VOTE_BTN_TXT"]; ?>" /> <a href="#" onClick="cp.select(frmColors.vote_btn,'pick2');return false;" name="pick2" id="pick2"><img src="images/paintspill16.gif" alt="Select color" title="Select color" name="Image99" width="16" height="16" border="0" align="absmiddle" id="Image99" /></a>              
        	  </td>
            </tr>
            <tr>
              <td >Total votes color</td>
              <td >
 				<input name="total_votes" type="text" id="total_votes" style="background-color:#<?php echo $COLOR_SET_DATA["TOTAL_VOTES"]; ?>; color:#<?php echo oppColor($COLOR_SET_DATA["TOTAL_VOTES"]); ?>" size="8" maxlength="7" value="<?php echo $COLOR_SET_DATA["TOTAL_VOTES"]; ?>" /> <a href="#" onClick="cp.select(frmColors.total_votes,'pick2');return false;" name="pick2" id="pick2"><img src="images/paintspill16.gif" alt="Select color" title="Select color" name="Image99" width="16" height="16" border="0" align="absmiddle" id="Image99" /></a>        
        	  </td>
            </tr>
            <tr>
              <td >Votes bar </td>
              <td >
 				<input name="votes_bar" type="text" id="votes_bar" style="background-color:#<?php echo $COLOR_SET_DATA["VOTES_BAR"]; ?>; color:#<?php echo oppColor($COLOR_SET_DATA["VOTES_BAR"]); ?>" size="8" maxlength="7" value="<?php echo $COLOR_SET_DATA["VOTES_BAR"]; ?>" /> <a href="#" onClick="cp.select(frmColors.votes_bar,'pick2');return false;" name="pick2" id="pick2"><img src="images/paintspill16.gif" alt="Select color" title="Select color" name="Image99" width="16" height="16" border="0" align="absmiddle" id="Image99" /></a>          
        	  </td>
            </tr>
            <tr>
              <td colspan="2" align="center" ><input type="submit" name="Submit2" value="Save color set" style="font-size:16px; cursor:pointer; font-weight:bold">
			  <?php
			  if ($_REQUEST["color_set_id"] > 0) {
			  ?>
                 <br /><br />
                or change the color set name above and then click button below to<br /><input type="submit" name="Submit22" value="Save as new color set" onClick="document.frmColors.color_set_id.value=''" style="font-size:16px; cursor:pointer; font-weight:bold">
			 <?php }; ?>				</td>
              </tr>
          </table>
		  </form>		  </td>
        </tr>
	</table>
	</td>
	<td align="center" valign="top">
	Demo poll to preview colors<br />
    <?php
		$adminPoll = true;
		$_REQUEST["id"] = 1;
		$colorSet = $_REQUEST["color_set_id"];
		include("poll.php");    
	?>	
	</td>
	</tr>
	</table>
<?php
} elseif ($_REQUEST["ac"]=='update') {
?>
Below you can see available script updates, current promotions and latest news.<br /><br />
<iframe width="90%" height="350" src="http://www.phpscripthelper.com/updates.php?script=<?php echo $SETTINGS["scriptid"]; ?>&version=<?php echo $SETTINGS["version"]; ?>" scrolling="auto" frameborder="0"></iframe>
<?php
}
?>
</td>
</tr>
<tr>
    <td align="left" valign="top"><img src="images/main-bottom.jpg" width="981" height="8" /></td>
    </tr>
  <tr>
    <td height="20" align="left" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="bottom" style="padding-top:0px"><a href="http://www.phpjabbers.com" target="_blank"><img src="images/logo.jpg" width="267" height="45" border="0" /></a></td>
  </tr>
  <tr>
    <td align="right" valign="top" style="padding-top:0px"><a href="http://www.phpjabbers.com" target="_blank"><img src="images/logo-bottom.jpg" width="267" height="8" border="0" /></a></td>
  </tr>
</table>	  
<?php	
//////////////////////////////////// LOGED ////////////////////////////
} else { /////////// LOGIN BOX
?><br /><br /><br /><br />
<?php echo $message; ?>
<form action="admin.php" method="post">
<input type="hidden" name="ac" value="login">
<table width="267" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="267" height="55" valign="top" style="background-image:url(images/help-middle-back.jpg); background-repeat:repeat-y"><img src="images/login-title.jpg" width="267" height="49" /></td>
  </tr>
  <tr>
    <td valign="top" class="HelpCell" style="background-image:url(images/help-middle-back.jpg); background-repeat:repeat-y">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="33%" align="right"><strong>Username:</strong></td>
        <td width="67%" align="left"><input name="user" type="text" id="user" style="width:90px" /></td>
      </tr>
      <tr>
        <td align="right"><strong>Password:</strong></td>
        <td align="left"><input name="pass" type="password" id="pass" style="width:90px"  /></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td align="left"><input type="submit" name="button" id="button" value="Login"  style="font-size:16px; cursor:pointer; font-weight:bold" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="55" valign="bottom" style="background-image:url(images/help-middle-back.jpg); background-repeat:repeat-y"><a href="http://www.phpjabbers.com" target="PHP scripts and website tools"><img src="images/logo.jpg" width="267" height="45" border="0" /></a></td>
  </tr>
  <tr>
    <td valign="top"><img src="images/logo-bottom.jpg" width="267" height="8" /></td>
  </tr>
</table>
</form>
<?php
};
?>
</center>
</body>
</html>
