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
include("options.php");

if (!isset($_REQUEST["id"])) $pollId = '1'; 
else $pollId = $_REQUEST["id"];

$loadPoll = '
<div id="PHPPoll'.$_REQUEST["id"].'"></div>
<script language="javascript">
	ajaxpage("'.$SETTINGS["installFolder"].'poll.php?id='.$pollId.'","PHPPoll'.$_REQUEST["id"].'","get");
</script>
';

$loadPoll = preg_replace("/\n/","",$loadPoll);
$loadPoll = preg_replace("/\r/","",$loadPoll);

?>

var flagCaptcha = false;
var flagFields = true;
var message = 'Please fill in all mandatory fields ! \n';

var bustcachevar=1; //bust potential caching of external pages after initial request? (1=yes, 0=no)
var bustcacheparameter="";

function createRequestObject(){
	try	{
		xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	}	catch(e)	{
		alert('Sorry, but your browser doesn\'t support XMLHttpRequest.');
	};
	return xmlhttp;
};

function ajaxpage(url, containerid, requesttype){
	var page_request = createRequestObject();

	if (requesttype=='get'){
		if (bustcachevar) bustcacheparameter=(url.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
		page_request.open('GET', url+bustcacheparameter, true)
		page_request.send(null)
	} else if (requesttype=='post') {
		page_request.open('POST', url, true);
		page_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		page_request.setRequestHeader("Content-length", poststr.length);
		page_request.setRequestHeader("Connection", "close");
		page_request.send(poststr);
	};

	page_request.onreadystatechange=function(){
		loadpage(page_request, containerid)
	}

}

function loadpage(page_request, containerid){
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)) {
		document.getElementById(containerid).innerHTML=page_request.responseText;
	};
}

function submitVote<?php echo $_REQUEST["id"]; ?>(element, id) {
	var selectedAnswers = '';
	var answersCount = document.PollFrm<?php echo $_REQUEST["id"]; ?>.answers<?php echo $_REQUEST["id"]; ?>.length;
    	for (i=0; i< answersCount; i++) {
		if (document.PollFrm<?php echo $_REQUEST["id"]; ?>.answers<?php echo $_REQUEST["id"]; ?>[i].checked) {
			if(element=="radio") selectedAnswers = document.PollFrm<?php echo $_REQUEST["id"]; ?>.answers<?php echo $_REQUEST["id"]; ?>[i].value;
			else if(element=="checkbox") selectedAnswers = selectedAnswers + "-" + document.PollFrm<?php echo $_REQUEST["id"]; ?>.answers<?php echo $_REQUEST["id"]; ?>[i].value;
		}
	}
    if (selectedAnswers=='') {
    } else {
		ajaxpage('<?php echo $SETTINGS["installFolder"]; ?>poll.php?ac=vote&id='+id+'&answers='+selectedAnswers,'poll_'+id,'get');
    };
};

function SelectFormElement<?php echo $_REQUEST["id"]; ?>(el,id) {
	if(el=="radio"){
		document.PollFrm<?php echo $_REQUEST["id"]; ?>.answers<?php echo $_REQUEST["id"]; ?>[id].checked = "checked";
	} else if(el=="checkbox"){
		document.PollFrm<?php echo $_REQUEST["id"]; ?>.answers<?php echo $_REQUEST["id"]; ?>[id].checked = !document.PollFrm<?php echo $_REQUEST["id"]; ?>.answers<?php echo $_REQUEST["id"]; ?>[id].checked;
	}
}


loadPoll = '<?php echo $loadPoll; ?>';
document.writeln(loadPoll);