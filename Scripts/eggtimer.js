// JavaScript Document
var timerID = 0;
var tStart = null;
var msRefresh = 1000;
var startTimeObject = new Object();
var minutesTotal = 0;
var msTotal = 0;
var alertString = '';
function initTimer() {
startTimeObject = document.getElementById ?
document.getElementById("started") : document.all.started;
var args = getArguments();
minutesTotal = (args.minutes) ? parseFloat(args.minutes) : get_cookie('minutes');
//minutesTotal = get_cookie('minutes');
if (minutesTotal.length <= 0){
minutesTotal = 60;
} else {
document.adjustTimer.minutes.value = minutesTotal;
}
msTotal = (60000 * minutesTotal);
alertString = 'time is up';
resetTimer();
goTimer();
}
function setMinutes() {
minutesTotal = document.adjustTimer.minutes.value;
document.cookie = "minutes=" + minutesTotal;
msTotal = (60000 * minutesTotal);
alertString = 'time is up';
resetTimer();
goTimer();
}
function getArguments() {
var args = new Object();
window.location.search.replace(
new RegExp( "([^?=&]+)(=([^&]*))?","g"), function($0,$1,$2,$3){
args[$1] = $3;
}
);
return args;
}
function get_cookie(cookieName) {
var search = cookieName + '=';
var returnvalue = '';
if (document.cookie.length > 0) {
offset = document.cookie.indexOf(search);
if (offset != -1) { // if cookie exists
offset += search.length
end = document.cookie.indexOf(';', offset);
if (end == -1) end = document.cookie.length;
returnvalue=unescape(document.cookie.substring(offset,end));
}
}
return returnvalue;
}
function UpdateTimer() {
if (timerID) clearTimeout(timerID);
if (!tStart) {
tStart = new Date();
startTimeObject.innerHTML = 'started at: ' + tStart;
}
var tDate = new Date(); //get time now
var tDiff = tDate.getTime() - tStart.getTime(); //ms since start
var tLeft = (msTotal - tDiff) / 1000; //secs remaining
if (tLeft <= 0) {
if (document.adjustTimer.alertme.checked) {
alert(alertString);
}
document.theTimer.countdown.value = '00:00';
document.title = '00:00';
} else {
var theMins = Math.floor(tLeft / 60);
var theSecs = Math.floor(tLeft % 60);
if (theMins < 10) theMins = '0' + theMins;
if (theSecs < 10) theSecs = '0' + theSecs;
document.theTimer.countdown.value = theMins + ':' + theSecs;
timerID = setTimeout("UpdateTimer()", msRefresh);
}
}
function goTimer() {
if (!tStart) {
tStart = new Date();
startTimeObject.innerHTML = 'started at: ' + tStart;
}
timerID = setTimeout("UpdateTimer()", msRefresh);
}
function resetTimer() {
if (timerID) clearTimeout(timerID);
tStart = null;
document.adjustTimer.minutes.value = minutesTotal;
goTimer();
}
function cleanExit() {
if(timerID) { clearTimeout(timerID); timerID = 0; }
}