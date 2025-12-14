<?php
error_reporting(0);
$SETTINGS["installFolder"]='/Poll/';
$SETTINGS["installURL"]='vbsa.org.au/Poll/';
$SETTINGS["path"]='/home/vbsaorga/public_html/Poll/';
$SETTINGS["admin_username"]='admin';
$SETTINGS["admin_password"]='pass';
$SETTINGS["mysql_user"]='vbsaorga_vbsa147';
$SETTINGS["mysql_pass"]='Lurch147';
$SETTINGS["hostname"]='localhost';
$SETTINGS["mysql_database"]='vbsaorga_vbsa2';

$SETTINGS["useCookie"]=false;
$SETTINGS["maxAnswers"]=50;
///////////////////////////////////////////////////////////////////////
///				DO NOT EDIT BELOW THIS LINE			   			//////
//////////////////////////////////////////////////////////////////////
$TABLES["QUESTIONS"] = 'phppoll_questions';
$TABLES["ANSWERS"] = 'phppoll_answers';
$TABLES["COLORS"] = 'phppoll_colors';
$TABLES["VOTES"] = 'phppoll_votes';

$SETTINGS["version"] = '3.0';
$SETTINGS["scriptid"] = '11';

if ($install != '1') {
	$connection = mysql_connect($SETTINGS["hostname"], $SETTINGS["mysql_user"], $SETTINGS["mysql_pass"]) or die ('request "Unable to connect to MySQL server."');
	$db = mysql_select_db($SETTINGS["mysql_database"], $connection) or die ('request "Unable to select database."');
};

$monthnames_arr = Array("","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$fonts_arr = Array("Arial", "Century", "Courier New", "Serif", "Tahoma", "Times New Roman", "Verdana");

function oppColor($c, $inverse=false){
	$temp[0] = $c[0].$c[1];
	$temp[1] = $c[2].$c[3];
	$temp[2] = $c[4].$c[5];
	$temp[0] = hexdec($temp[0]);
	$temp[1] = hexdec($temp[1]);
	$temp[2] = hexdec($temp[2]);
	if (array_sum($temp) > 255*1.5) {
		return '000000';
	} else {
		return 'FFFFFF';
	};
};
?>