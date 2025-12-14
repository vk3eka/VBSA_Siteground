finished=false;
function showHTML(){
	if(mode!="html"){
		if(document.getElementById("editor_form").SendAs.checked){
			alert("Design mode is disabled when sending messages in \"Plain Text\" versus \"HTML\"")
		}
		else{
			mode="html";
			document.getElementById("htmltab").className="activetab";
			document.getElementById("editor_form").keep_source.value=document.getElementById("editor_form").m_source.value;
			if(document.getElementById("editor_form").keep_source.value=="" && ie){document.getElementById("editor_form").keep_source.value="&nbsp;"}
			document.getElementById("sourcetab").className="tab";
			document.getElementById("editor_form").m_source.style.display="none";
			document.getElementById("sourceholder").style.display="block";
			
			html_box.focus();
				with(ed){
					designMode="On";
					if (ie){
						execCommand("MultipleSelection");
						execCommand("LiveResize");
					}
				}
				//IE tweak
				if(first_time){
					setTimeout("designView()", 50); 
					first_time=false;
					}
				else{designView(); }
				if(!ie){setTimeout("ed.execCommand('useCSS', false, true)", 100);}
		}
	}
}

function designView(c){
	adjustButtons(1);
	if(ie){
		ed.write(formatHTML(document.getElementById("editor_form").keep_source.value));
	}
	else{
		ed.body.innerHTML=formatHTML(document.getElementById("editor_form").keep_source.value);
	}
	html_box.focus();
	reflectSelection();
	ed.onselectionchange=reflectSelection;
}

function formatHTML(s){
	var b="";
	var bh=document.getElementById("editor_form").BaseURL.value;
	if(bh!=""){
		if(bh.charAt(bh.length-1)!="/"){bh=bh+"/"}
		var b="<base href=\""+bh+"\">";
	}
	//Old href
	var h=/<base href=[^>]*>/i;
	if(ie){
		var body_open="";var body_close="";
		var store_head="<HEAD>"+b+"</HEAD>";
		
		//Create body tag
		if(!reg_body_open.test(s)){
			body_open="<BODY>";
			body_close="</BODY>";
		}
		if(h.test(s)){
			s=s.replace(h,"");
		}
		if(reg_head_tag.test(s)){
			store_head=s.match(reg_head_tag).toString();
			store_head=store_head.replace(reg_head_open,"");
			var new_head="<HEAD>"+b+store_head+body_open;
			s=s.replace(reg_head_tag,new_head)+body_close;
		}
		else{
			s=store_head+body_open+s+body_close;
		}
	}
	else{
		s=b+s.replace(h,"");
	}
	return s;
}
function showSource(){
	if(mode!="plain_text"){
		codeView();
		fh=document.getElementById("sourceholder").innerHTML;
		document.getElementById("sourceholder").style.display="none";
		document.getElementById("htmltab").className="tab";
		document.getElementById("sourcetab").className="activetab";
		document.getElementById("editor_form").m_source.style.display="block";
		adjustButtons(0);
		mode="plain_text";
		if(ie){
			document.getElementById("sourceholder").innerHTML="";
			document.getElementById("sourceholder").innerHTML=fh;
			ed=html_box.document;
		}
	}
}

//Adjust Code
function codeView(){
	var reg_head_open=/<head>/i;
	var s;
	if(mode=="html"){
		if(ie){
			var open_head=(reg_head_open.test(ed.body.parentNode.innerHTML))?"":"<HEAD>"
			s=formatHTML(open_head+ed.body.parentNode.innerHTML);
		}
		else{
			s=formatHTML(ed.body.innerHTML);
		}
	}
	document.getElementById("editor_form").m_source.value=document.getElementById("editor_form").keep_source.value=s;
}
function indent(){
	if(mode=="plain_text"){
	wrapInTag("<blockquote>","</blockquote>");
  }
  else{
	  htmlCommand('Indent', null);
  }
}
function makeBold(){
	if(mode=="plain_text"){
	wrapInTag("<b>","</b>");
  }
  else{
  ed.execCommand('bold', false, null); 
  }
}
function makeItalic(){
	if(mode=="plain_text"){
	wrapInTag("<i>","</i>");
  }
  else{
  ed.execCommand('italic', false, null); 
  }
}
function wrapInTag(start,end){
	if(ie){
	//IE
		var str = document.selection.createRange().text;
		document.getElementById("editor_form").m_source.focus();
		var sel = document.selection.createRange();
		if(end==""){var str=""}
		sel.text = start+ str + end;
		return;
	}
	else{
	//Gecko
		var f=document.getElementById("editor_form").m_source;
		f.focus();
		var r = f.value;
		str=r.substring(f.selectionStart, f.selectionEnd);
		if(end==""){var str=""}
		f.value = r.substring(0, f.selectionStart)+start+str+end + r.substr(f.selectionEnd);
	}
}


//============== Editor Functions =============================
function insert_link(p,i) {
	if(mode=="plain_text"){
		var m=window.prompt("Please enter a URL",p);
		if(m!=null){wrapInTag("<a href=\"" + m + "\">","</a>")};
	}
	else{
		if(!i || !ie){
			var m=window.prompt("Please enter a URL",p);
			if(m!=null){ed.execCommand('createlink',false,m)}
		}
		else{
			ed.execCommand('createlink',true,p);
		}
	}
}

//Insert image dialog box
function insert_image(im) {
	if(mode=="plain_text"){
		var m=window.prompt("Please enter the image URL",(im==null)?"http://www.":im);
		if(m!=null){wrapInTag("<img src=\"" + m + "\" />","")};
	}
	else{
		if(ie){
			html_box.focus();ed.execCommand('insertimage',(im==null)?true:false,im);
		}
		else{
			var m=window.prompt("Please enter the image URL",(im==null)?"http://www.":im);
			if(m!=null){html_box.focus();ed.execCommand('insertimage',false,m)}
		}
	}
}

//Execute execCommand
function htmlCommand(c,v){
	if(mode!="plain_text"){
	html_box.focus();
		ed.execCommand(c, false,v);
	}
}

//Text format
function setFormat(v) {
	if (v == '<body>') {
		if (mode != "plain_text") {
			ed.execCommand('formatBlock', '', 'Normal');
			ed.execCommand('removeFormat');
		}
	} else {
		if (mode != "plain_text") {
			ed.execCommand('formatblock', '', v);
		} else {
			wrapInTag(v, "</"+v.substring(1, v.length));
		}
	}
}

//Set test send mode
function testMode(v){
	with(document.getElementById("To")){
		if(v.checked){
			readOnly=false;
		}
		else{
			readOnly=true;
		}
		value="";
	}
	//Reset start number when flipping the mode
	document.getElementById("StartNumber").value="1";
}

//Insert arbitrary HTML
function insertObject(o){
	if(mode=="plain_text"){
		wrapInTag(o,"");
	}
	else{
		if(ie){
		var s=html_box;
		s.focus();
		var sel = s.document.selection;
			if (sel!=null) {
				var rng = sel.createRange();
				if (rng!=null){
					rng.pasteHTML(o);
					s.focus();
				}
			}
		}
		else{
		n=ed.createTextNode(o);
		 insertNodeAtSelection(html_box,n);
		}
	}
}

//Insert execCommand compatible HTML
function insertElement(c,param,st,et){
	if(mode=="plain_text"){
		wrapInTag(st,et);
	}
	else{
		htmlCommand(c,param);
	}
}

//Insert text
function insertDate(){
var now=new Date();
var months=new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"); 
var d=(months[now.getMonth()]+" "+now.getDate()+", "+ now.getFullYear());
insertObject(d);
}
//Open insert table dialog box 
function tableDialog(){
	win=window.open('about:blank','table','width=360, height=220, modal=yes');
	doc=win.document;
	with (doc){
		writeln('<html><head><title>Insert Table</title><style>body, td{background-color:ButtonFace;font-family:Tahoma, Helvetica, sans-serif;font-size:11px;}</style>');
		writeln('<sc'+'ript>function makeTable(){args=new Array();with(document.forms[0]){args[1]=Rows.value;args[2]=Columns.value;args[3]=tWidth.value+units.value;args[5]=tborder.value;args[6]=tspacing.value;args[7]=tpadding.value;};with(window.opener){insertTable(args[1],args[2],args[3],args[5],args[7],args[6]);};setTimeout(\"window.close()\",100);}'+'</sc'+'ript>'+'</head><body onLoad=\"self.focus()\"><form name=\"form1\" method=\"post\" action=\"\">  <fieldset>  <legend><b>Table Properties</b></legend>  <table width=\"300\"  border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr><td align=\"right\">Rows</td><td width=\"40\"><input name=\"Rows\" type=\"text\" id=\"Rows\" value=\"3\" size=\"3\" maxlength=\"2\"></td><td align=\"right\">Columns</td><td><input name=\"Columns\" type=\"text\" id=\"Columns\" value=\"3\" size=\"3\" maxlength=\"2\"></td></tr><tr><td align=\"right\">Table width: </td><td><input name=\"tWidth\" type=\"text\" id=\"tWidth\" value=\"100\" size=\"5\" maxlength=\"4\"></td><td colspan=\"2\"><select name=\"units\" id=\"units\">  <option value=\"%\" selected>percent</option>  <option>pixels</option></select></td></tr><tr><tr><td align=\"right\">Border width: </td><td><input name=\"tborder\" type=\"text\" id=\"tborder\" value=\"1\" size=\"3\" maxlength=\"2\"></td><td colspan=\"2\">&nbsp;</td></tr><td align=\"right\">Cellpadding:</td><td><input name=\"tpadding\" type=\"text\" id=\"tpadding\" value=\"3\" size=\"3\" maxlength=\"2\"></td><td align=\"right\">Cellspacing</td><td><input name=\"tspacing\" type=\"text\" id=\"tspacing\" value=\"3\" size=\"3\" maxlength=\"2\"></td></tr><tr><td colspan=\"2\">&nbsp;</td><td colspan=\"2\">&nbsp;</td></tr><tr><td colspan=\"2\" align=\"right\"><input name=\"commit\" type=\"button\" id=\"commit\" value=\"OK\" onClick=\"makeTable()\"></td><td colspan=\"2\"><input name=\"cancel\" type=\"button\" id=\"cancel\" value=\"Cancel\" onClick=\"window.close()\"></td></tr>  </table>  </fieldset></form></body></html>');
	close();	
	}
}

//Open insert table dialog box 
function openFile(){
	if(mode=="html"){
		showSource();
	}
	win=window.open('php_mail_merge.php?mode=openTemplate','file','width=776, height=360, modal=yes, status=yes');
}
//Open add file dialog box 
function openAddFile(){
	win=window.open('php_mail_merge.php?mode=addFile','file','width=482, height=360, modal=yes, status=yes');
}
function saveFile(){
	if(mode=="html"){
		showSource();
	}
	win=window.open('php_mail_merge.php?mode=saveTemplate','file','width=776, height=360, modal=yes, status=yes');
}

function loadTemplate(s){
	document.getElementById("editor_form").m_source.value=s;
}
//Preview page in the browser
function previewPage(){
	var s;
	if(mode=="plain_text"){
		s=formatHTML(document.getElementById("editor_form").m_source.value);
		document.getElementById("editor_form").keep_source.value=document.getElementById("editor_form").m_source.value=s;
	}
	else{
		if(ie){
			var open_head=(reg_head_open.test(ed.body.parentNode.innerHTML))?"":"<HEAD>";
			s=formatHTML(open_head+ed.body.parentNode.innerHTML);
		}
		else{
			s=formatHTML(ed.body.innerHTML);
		}
	}
	var win=window.open('about:blank','table','width=600, height=400, resizable=yes, scrollbars=yes, modal=yes');
	var doc=win.document;
	with (doc){
		writeln(s);
	}
}

function saveLocal(what){
	if(mode!="plain_text"){
		codeView();
	}
	what.form.Do_Send.value='save';
	what.form.submit();
}

function reflectSelection(){
	if(mode=="html"){
		var f=ed;
		for(i=0;i<senseb.length;i++){
			buttonState(senseb[i], true);
		}
		document.getElementById("fonts").value=f.queryCommandValue("fontname");
		document.getElementById("size").value=f.queryCommandValue("fontsize");
		document.getElementById("formatblock").value=tf[f.queryCommandValue("formatblock")];
		document.getElementById("fontColor").style.backgroundColor=multselectColor("forecolor");
		document.getElementById("backgrColor").style.backgroundColor=multselectColor("backcolor");
	}
}

function multselectColor(what){
	var f=ed;
	var c;
	//hilite instead of BackColor for Mozilla
	if(color_what=="backgrColor" && !ie){what="hilitecolor";useCSS(false)};
	//Deal with empty value
	if(f.queryCommandValue(what)==null || f.queryCommandValue(what)=="" || f.queryCommandValue(what)=="transparent"){c=""}else{c=convert2_Color(f.queryCommandValue(what));}
	return c;
}

function buttonState(b){
	if(ie){
	var f=ed;
		if(f.queryCommandState(b)){
			document.getElementById(b).className="interface_button on";
		}
		else{
			if(document.getElementById(b).className!="interface_button dis"){document.getElementById(b).className="interface_button"};
		}
	}
	else{
		flipClass(document.getElementById(b), 'interface_button')
	}
}

function convert2_Color(v) {
	function hex(d) {
		return (d < 16) ? ("0" + d.toString(16)) : d.toString(16);
	};
	if (typeof v == "number") {
		var r = v & 0xFF;
		var g = (v >> 8) & 0xFF;
		var b = (v >> 16) & 0xFF;
		return "#" + hex(r) + hex(g) + hex(b);
	}

	if (v.substr(0, 3) == "rgb") {
		var re = /rgb\s*\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*\)/;
		if (v.match(re)) {
			var r = parseInt(RegExp.$1);
			var g = parseInt(RegExp.$2);
			var b = parseInt(RegExp.$3);
			return "#" + hex(r) + hex(g) + hex(b);
		}
		return null;
	}
	if (v[0] == "#") {
		return v;
	}
	return null;
};

function customColor(what,swatch){
	color_what=swatch;
	var val=multselectColor(what);
	what=(!ie && what=="BackColor")?"hilitecolor":what;
	var v=prompt("Enter a valid color name or hexadecimal value",val);
	if(v!=null){
		htmlCommand(what,v);
		useCSS(true);
		document.getElementById(swatch).style.backgroundColor=v;
	}
}
//============== End Editor Functions ============================

//=================Color Picker Functions=========================
function setColor(){
	document.getElementById("colorValue").innerHTML=this.bgColor;

	document.getElementById("swatch").bgColor=this.bgColor;
}
function insertColor(){
	document.getElementById("palette_div").style.visibility="hidden";	
	colorWhat(this.bgColor);
}
//Remove color
function clearColor(){
	document.getElementById("colorValue").innerHTML="";
	document.getElementById("swatch").bgColor="";
	document.getElementById("palette_div").style.visibility="hidden";
	document.getElementById(color_what).style.backgroundColor="";
	//The "clear" parameter
	var c;
	if(ie){c=null;}else{c=(color_what=="backgrColor")?"inherit":"null"};
	colorWhat(c);
}
function setPalette(){
	var p=document.getElementById("palette");
	if(ie){//IE
		var l=p.cells.length;
		for(i=0;i<l;i++){
			p.cells[i].onmouseover=setColor;
			p.cells[i].onmousedown=insertColor;
		}
	}
	else{//GECKO
		var r=p.rows.length;
		for(i=0;i<r;i++){
			var cells=p.rows[i].cells.length;
			for(j=0;j<cells;j++){
				p.rows[i].cells[j].onmouseover=setColor;
				p.rows[i].cells[j].onclick=insertColor;
			}
		}

	}
}
function findPosY(obj){
	var curtop = 0;
	if (obj.offsetParent){
		while (obj.offsetParent){
			curtop += obj.offsetTop;
			obj = obj.offsetParent;
		}
	}
	return curtop;
}
function findPosX(obj){
	var curleft = 0;
	if (obj.offsetParent){
		while (obj.offsetParent){
			curleft += obj.offsetLeft;
			obj = obj.offsetParent;
		}
	}
	return curleft;
}
function showPalette(which,s){
//Show palette only in design mode
	if(mode!="plain_text"){
		color_what=s;
		var p=document.getElementById("palette_div");
		p.style.visibility="visible";
		
		//If IE -account for margins
		var t=0;var l=0;
		if(ie){t=Number(document.body.topMargin);l=Number(document.body.leftMargin)}
			p.style.top=findPosY(which)+t+"px";
			p.style.left=findPosX(which)+which.offsetWidth+l+"px";
	}
}

function colorWhat(how){
document.getElementById(color_what).style.backgroundColor=how;
	if(color_what=="fontColor"){
	ed.execCommand("ForeColor",false,how);
	}
	else if(color_what=="backgrColor"){
		useCSS(false);
		what=(!ie)?"hilitecolor":"BackColor"; 
		ed.execCommand(what,false,how);
		useCSS(true);
	}
}
function useCSS(b){
	if(!ie){
		ed.execCommand('useCSS', false, b)
	}
}
function initPMM(){
	//Base vars
	mode="plain_text";color_what="";first_time=true;ie=(!document.all)?false:true;ed=html_box.document;
	//Set palette
	setPalette();
	//Formatting arrays
	dis=new Array("JustifyLeft","JustifyCenter","JustifyRight","InsertUnorderedList","InsertOrderedList","Outdent","b_cut","b_copy","b_paste","b_print","b_undo","b_redo","b_font","b_hilite","b_unlink");
	disf=new Array("size","fonts");
	senseb = new Array("bold","italic","JustifyLeft","JustifyCenter","JustifyRight","InsertUnorderedList","InsertOrderedList");
	//textformat
	tf=new Array();
	var tfi=document.getElementById("formatblock").options
	var tfd=tfi.length;
	for(i=0; i<tfd;i++){tf[tfi[i].text]=tfi[i].value;}
	//Reset buttons& bars
	adjustButtons(0);
	if(!ie){document.getElementById("file_bar").style.display="none";}
	reg_head_tag=/<head>[^|]*<\/head>/i;
	reg_head_open=/<head>/i;
	reg_body_open=/<body/i;
}
function printIframe(){
	if(!ie){
		with(html_box){
		focus();
		print();
		}
	}
	else{
	htmlCommand('Print',null);
	}	
}
function insertTable(rows,cols,w,b,cp,csp){
      table = ed.createElement("table");
      table.setAttribute("border", b);
      table.setAttribute("cellpadding",cp);
      table.setAttribute("cellspacing", csp);
	  table.setAttribute("width", w);
      tbody = ed.createElement("tbody");
      for (var i=0; i < rows; i++) {
        tr =ed.createElement("tr");
        for (var j=0; j < cols; j++) {
          td =ed.createElement("td");
          br =ed.createElement("br");
          td.appendChild(br);
          tr.appendChild(td);
        }
        tbody.appendChild(tr);
      }
      table.appendChild(tbody);
	 if(mode!="plain_text"){
      insertNodeAtSelection(html_box, table);
	}
	else{
	  wrapInTag("<table border='"+b+"' cellpadding='"+cp+"' cellspacing='"+csp+"' width='"+w+"'>"+table.innerHTML+"</table>","");
	  }
    }

//Gecko function to insert arbitrary HTML
  function insertNodeAtSelection(win, insertNode){
  html_box.focus();
  if(ie){
   sel = win.document.selection.createRange();
   sel.pasteHTML(insertNode.outerHTML);

  }
  else{
      var sel = win.getSelection();
      var range = sel.getRangeAt(0);
      sel.removeAllRanges();
      range.deleteContents();
      var container = range.startContainer;
      var pos = range.startOffset;
      range=document.createRange();
      if (container.nodeType==3 && insertNode.nodeType==3) {
        container.insertData(pos, insertNode.nodeValue);
        range.setEnd(container, pos+insertNode.length);
        range.setStart(container, pos+insertNode.length);
      } else {
        var afterNode;
        if (container.nodeType==3) {
          var textNode = container;
          container = textNode.parentNode;
          var text = textNode.nodeValue;
          var textBefore = text.substr(0,pos);
          var textAfter = text.substr(pos);
          var beforeNode = document.createTextNode(textBefore);
          afterNode = document.createTextNode(textAfter);
          container.insertBefore(afterNode, textNode);
          container.insertBefore(insertNode, afterNode);
          container.insertBefore(beforeNode, insertNode);
          container.removeChild(textNode);
        } else {
          afterNode = container.childNodes[pos];
          container.insertBefore(insertNode, afterNode);
        }
        range.setEnd(afterNode, 0);
        range.setStart(afterNode, 0);
      }
      sel.addRange(range);
	  }
  };
  
//Adjust buttons state for Code view buttons
function sp_buttonState(what){
	//if(mode=='plain_text' || !ie){flipClass(what, 'interface_button')}
}

function adjustButtons(how){
	var dis_l=dis.length;
	var disf_l=disf.length;
	var c=(how==1)?"interface_button":"interface_button dis";
	for(i=0;i<dis_l;i++){
		document.getElementById(dis[i]).className=c;
		document.getElementById(dis[i]).disabled=!how;
	}
	for(i=0;i<disf_l;i++){
		document.getElementById(disf[i]).disabled=!how;
	}
	document.getElementById("fontColor").style.backgroundColor="";
	document.getElementById("backgrColor").style.backgroundColor="";
	document.getElementById("bold").className="interface_button";
	document.getElementById("italic").className="interface_button";
}
//================= End Color Picker Functions======================

//================= Application Functions ==========================
function flipClass(what, how){
	if(what.className!="interface_button_dis"){
		what.className=how;
	}
}


function newPage(){
	if(confirm('All changes will be lost! Continue?')){
		location.replace(location.href)
	}
}
// Handle Message Format
function flipMessageFormat(m){
	showSource();
	if(m.checked){
		document.getElementById("format_bar").style.visibility="hidden";
		document.getElementById("objects_bar").style.visibility="hidden";
	}
	else{
		document.getElementById("format_bar").style.visibility="visible";
		document.getElementById("objects_bar").style.visibility="visible";
	}
}

function validateMessage(){
	with(document.getElementById("editor_form")){
		if(From.value=="" || To.value=="" || Subject.value==""){
			alert('"From","To" and "Subject" are required fields');
			return false;
		}
		else{return true;}
	}
}

function refreshSource(){
	var s;
	if(mode=="plain_text"){
		if(document.getElementById("editor_form").SendAs.checked){
			s=document.getElementById("editor_form").m_source.value;
		}
		else{
			s=formatHTML(document.getElementById("editor_form").m_source.value);
		}
	}else{
		if(ie){
			var open_head=(reg_head_open.test(ed.body.parentNode.innerHTML))?"":"<HEAD>";
			s=formatHTML(open_head+ed.body.parentNode.innerHTML);
		}
		else{
			s=formatHTML(ed.body.innerHTML);
		}
	}
	document.getElementById("editor_form").keep_source.value=document.getElementById("editor_form").m_source.value=s;
}
function sendMessage(){
	if(validateMessage()){
		if(confirm("This will send the message. Continue?")){
			finished=false;
			refreshSource();
			resetSendDialog();
			processMessage();
			showStatus();
		}
	}
}


//AJAX FUNCTIONS, April 12 2007

function processMessage(){
	refreshSource();
	//Send Email			
	document.getElementById("editor_form").Do_Send.value="Send";
	var url=location.href;
	document.getElementById("in_progress").innerHTML="Operation in progress, please wait ...";
	var email_data=setQueryString("editor_form");
	httpRequest("POST",url,true,afterSent,email_data);
	//disable pause button while communicating with the server
	document.getElementById("Pause").disabled=true;
}


function repeatSend(){
	with(document.getElementById("editor_form")){
		Do_Send.value="Send";
		submit();
	}
}
function sendOnOff(v){
	if(v.innerHTML.indexOf("Delay")!=-1){
		window.clearInterval(monitorsend);
		v.innerHTML="<img src='php_mail_merge/play_email.png' width='17' height='16' align='absmiddle' /> Resume";
		document.getElementById("in_progress").innerHTML="Paused";
	}else{	
		processMessage();
		v.innerHTML="<img src='php_mail_merge/pause_email.png' width='17' height='16' align='absmiddle' /> Delay";
		document.getElementById("in_progress").innerHTML="Operation in progress, please wait ...";
	}
}

var monitorsend="";

function stopSend(){
	if(finished==false){
		if(confirm("Are you sure you want to abort?")){
			if(monitorsend!=""){window.clearTimeout(monitorsend)}
			document.getElementById("in_progress").innerHTML="Done";
			document.getElementById("Send").disabled=false;
			request.abort();
			closePopup();
		}
	}
}
function skipNext(step){
	//Can skip only if there is next
	if(finished==false && !document.getElementById("SendMode").checked){
		document.getElementById("StartNumber").value=Number(document.getElementById("StartNumber").value)+step;
		processMessage();
		if(monitorsend!=""){window.clearInterval(monitorsend)};
		document.getElementById("Pause").innerHTML="<img src='php_mail_merge/pause_email.png' width='17' height='16' align='absmiddle' /> Delay";
		document.getElementById("in_progress").innerHTML="Operation in progress, please wait ...";
	}
}

var errorsNum=0;

var request = null;

function httpRequest(reqType, url, asynch, respHandle) {
	//Mozilla-based browsers
	if (window.XMLHttpRequest) {
		request = new XMLHttpRequest();
		//if the reqType parameter is POST, then the
		//5th argument to the function is the POSTed data
		if (reqType.toLowerCase() != "post") {
			initReq(reqType, url, asynch, respHandle);
		} else {
			//the POSTed data
			var args = arguments[4];
			if (args != null && args.length>0) {
				initReq(reqType, url, asynch, respHandle, args);
			}
		}
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Msxml2.XMLHTTP");
		if (!request) {
			request = new ActiveXObject("Microsoft.XMLHTTP");
		}
		if (request) {
			//if the reqType parameter is POST, then the
			//4th argument to the function is the POSTed data
			if (reqType.toLowerCase() != "post") {
				initReq(reqType, url, asynch, respHandle);
			} else {
				var args = arguments[4];
				if (args != null && args.length>0) {
					initReq(reqType, url, asynch, respHandle, args);
				}
			}
		} else {
			alert("Your browser is not supported.");
		}
	} else {
		alert("Your browser is not supported.");
	}
}


monitorsend="";
next_send_in=0;
function afterSent(){
	if(request.readyState == 1){
		document.getElementById("in_progress").innerHTML="Sending...";
	}
	if(request.readyState == 4){
		 //If response is received from the server
		if(request.status == 200){
			//Response if formed properly - generated by mailing script
			if(request.responseText!="" && request.responseXML.hasChildNodes){
				//Update error count
				var error_report=(window.XMLHttpRequest)?(request.responseXML.lastChild.lastChild.textContent):(request.responseXML.lastChild.lastChild.text);
				//Stop sending if attachment file path is wrong
				if(error_report!="" && error_report.indexOf("Error attaching file")!=-1){
					alert("Error attaching file. Please correct the file path.");
					if(monitorsend!=""){window.clearTimeout(monitorsend)}
					document.getElementById("in_progress").innerHTML="Done";
					document.getElementById("Send").disabled=false;
					request.abort();
					closePopup();
				}else{
					//Start showing error summary if there has been an error
					if(error_report!=""){
						document.getElementById("errors_summary").style.visibility="visible";
						document.getElementById("errors").value+=error_report+"\r\n";
						errorsNum++;
					}
					//Show and update summary
					document.getElementById("summary_content").style.visibility="visible";
					//How many sent already
					var totalErrors=(errorsNum>0)?(" ("+errorsNum+" errors)"):"";
					//Display errors in the errors text area
					document.getElementById("was_sent").innerHTML=Number(document.getElementById("StartNumber").value)+totalErrors;
					//How many total
					document.getElementById("was_to_send").innerHTML=(document.getElementById("SendMode").checked)?"1":request.responseXML.lastChild.getAttribute("total");
					//Who was the last recepient
					document.getElementById("send_status").innerHTML="Last sent to "+request.responseXML.lastChild.getAttribute("to");
					document.getElementById("send_status").style.visibility="visible";
					//Update progress bar
					var percent_done=Math.floor((document.getElementById("StartNumber").value/request.responseXML.lastChild.getAttribute("total"))*100)+"%";
					document.getElementById("progress_bar").style.width=percent_done;
					document.getElementById("progress_percent").innerHTML=percent_done;
					//If done or a test message
					if(document.getElementById("StartNumber").value==request.responseXML.lastChild.getAttribute("total") || document.getElementById("SendMode").checked || document.getElementById("in_progress").innerHTML=="Done"){
						//max up progress
						document.getElementById("progress_bar").style.width="100%";
						document.getElementById("progress_percent").innerHTML="100%";
						//hide progress
						document.getElementById('small_status').innerHTML=document.getElementById("in_progress").innerHTML="Done";
						document.getElementById('small_progress').src="php_mail_merge/progress_done.gif";
						document.getElementById("Send").disabled=false;
						alert("Message has been sent to all the recipients");
						finished=true;
						document.getElementById("StartNumber").value=1;
					}else{
					//Increment starting point for the next recepient
						document.getElementById("StartNumber").value=Number(document.getElementById("StartNumber").value)+1;
						//If sending in cycles
						if(!isNaN(document.getElementById("delay").value) && Number(document.getElementById("delay").value)>0 && !isNaN(document.getElementById("NumOfRecords").value) && Number(document.getElementById("NumOfRecords").value)>0){
							//If time to pause
							if((Number(document.getElementById("StartNumber").value)-1)%document.getElementById("NumOfRecords").value==0){
								next_send_in=Number(60*document.getElementById("delay").value);
								monitorsend=window.setInterval("refreshMonitor()",1000);
								document.getElementById("Pause").disabled=false;
							}else{
								//If time to send - send to the next recepient
								document.getElementById("in_progress").innerHTML="Operation in progress, please wait ...";
								document.getElementById("Pause").disabled=true;
								if(monitorsend!=""){clearInterval(monitorsend);}
								processMessage();
							}
						}else{
							//If not sedning in cycle
							processMessage();
							document.getElementById("in_progress").innerHTML="Operation in progress, please wait ...";
						}
					}
				}
			}		
		} else {
			//When request is aborted an error may return. If not aborted present options
			if(document.getElementById("in_progress").innerHTML!="Done"){
				if(confirm("A problem occurred while attempting to send the last message.\nThis might be due to a 'bad' email address or a server problem.\nClick 'OK' to retry sending to the last address or 'Cancel' to skip to the next one.")){
					skipNext(0);
				}else{
					skipNext(1);
				}
			}else{
				alert("Operation aborted.");	
			}
		}
	}
}

function resetSendDialog(){
	document.getElementById('progress_minimized').style.visibility="hidden";
	document.getElementById("in_progress").innerHTML="Operation in progress, please wait ...";
	document.getElementById("Pause").disabled=true;
	document.getElementById("Send").disabled=true;
	//document.getElementById("StartNumber").value=1;
	document.getElementById("in_progress").innerHTML="";
	document.getElementById('small_status').innerHTML="Sending...";
	document.getElementById("was_sent").innerHTML="";
	document.getElementById("was_to_send").innerHTML="";
	document.getElementById('small_progress').src="php_mail_merge/progress.gif";
	document.getElementById("errors_summary").style.visibility="hidden";
	document.getElementById("progress_bar").style.width="1px";
	document.getElementById("errors").value="";
	document.getElementById("summary_content").style.visibility="hidden";
	document.getElementById("send_status").innerHTML="";
	errorsNum=0;
}

function refreshMonitor(){
	if(next_send_in>0){
		next_send_in--;
		document.getElementById("in_progress").innerHTML="Next cycle in "+next_send_in+" seconds";
	}else{
		if(monitorsend!=""){clearInterval(monitorsend);}
		processMessage();
	}
}

var px=(document.all && !window.opera)?"":"px";
function showStatus(){
	with(document.getElementById('shield')){
		style.display='block';
		style.height=document.body.offsetHeight+100+px;
	}
	with(document.getElementById('status_viewer')){
		style.display = 'block';
		style.top = document.body.parentNode.scrollTop+px;
	}
	if(document.all && !window.opera){
		document.getElementById('table_holder').style.height = screenHeight;
	}
	document.getElementById("in_progress").style.display="block";
}

function showFilters(what){
	document.getElementById("filters").style.display=(what.checked)?"block":"none";
}

function closePopup(){
	//Close only if finished
	if(document.getElementById("in_progress").innerHTML=="Done"){
		document.getElementById('status_viewer').style.display='none';
		document.getElementById('shield').style.display='none';
	}
}

function minimizePopup(){
	document.getElementById('status_viewer').style.display='none';
	document.getElementById('shield').style.display='none';
	document.getElementById('progress_minimized').style.visibility='visible';
}

function maximizePopup(){
	document.getElementById('status_viewer').style.display='block';
	document.getElementById('shield').style.display='block';
	document.getElementById('progress_minimized').style.visibility='hidden';
}

function open_upload_image(){
	win=window.open('php_mail_merge.php?mode=uploadImage','file','width=560, height=140, modal=yes, status=yes');	
}

function open_insert_image(){
	win=window.open('php_mail_merge.php?mode=insertImage','file','width=760, height=480, modal=yes, status=yes');	
}
function openTemplate(what){
	httpRequest("GET","php_mail_merge.php?template_open="+escape(what),true,placeTemplate,"");
}
function saveTemplate(what){
	httpRequest("POST","php_mail_merge.php",true,notifyTemplateSaved,"template_name="+escape(what)+"&content="+document.getElementById("editor_form").m_source.value.replace( /&/g, '%26' ));
}
function placeTemplate(){
	if(request.readyState == 1){
	}
	if(request.readyState == 4){
		if(request.status == 200){
			document.getElementById("editor_form").m_source.value=request.responseText;
		}else {
			alert("There has been a problem opening the template.\nPlease check you have 'read' permissions to open this file.");
		}
	}
}
function notifyTemplateSaved(){
	if(request.readyState == 1){
	}
	if(request.readyState == 4){
		if(request.status == 200){
			alert(request.responseText);
		}else {
			 alert("There has been a problem saving the template.\nPlease check you have 'write' permissions to save this file.");
		}
	}
}
/* Initialize a Request object that is already constructed */
function initReq(reqType, url, bool, respHandle) {
	try {
		/* Specify the function that will handle the HTTP response */
		request.onreadystatechange = respHandle;
		request.open(reqType, url, bool);
		//if the reqType parameter is POST, then the 5th argument to the function is the POSTed data
		if (reqType.toLowerCase() == "post") {
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
			request.send(arguments[4]);
		} else {
			request.send(null);
		}
	} catch (errv) {
		alert("The application cannot contact the server at the moment. Please try again in a few seconds.\n"+"Error detail: "+errv.message);
	}
}
val_email=/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
function validateEmail(v){
	return val_email.test(v);
}

function setQueryString(f){
    queryString="";
    var frm = document.getElementById(f);
    var numberElements =  frm.elements.length;
    for(var i = 0; i < numberElements; i++) {
		if(frm.elements[i].type!="checkbox" || (frm.elements[i].type=="checkbox" && frm.elements[i].checked)){
			if(i < numberElements-1) {
				queryString += frm.elements[i].name+"="+encodeURIComponent(frm.elements[i].value)+"&";
			} else {
				queryString += frm.elements[i].name+"="+encodeURIComponent(frm.elements[i].value);
			}
		}
    }
	return queryString;
}

function showSMTP(what){
	document.getElementById("smtp_table").style.display=(what.checked)?"block":"none";
}
//END AJAX FUNCTIONS
showFilters(document.getElementById('show_filters'));
showSMTP(document.getElementById('show_smtp'))
//window.onerror=function(){return false;}
//================= End Application Functions ==========================