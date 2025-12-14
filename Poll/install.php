<?php
session_start();
error_reporting(0);

$scriptID = '11';
$scriptVERSION = '3.0';

$pre_requirements[0]="PHP,4.0.0,check_php";
$pre_requirements[1]="MySQL,3.0,check_mysql";
//$pre_requirements[2]="Zend,2.6,check_zend"; /// Commented for Developer licence
$fileCheck=false; /// False for Developer licence

$sql_file=<<<SQL_FILE
DROP TABLE IF EXISTS `phppoll_colors`;*
CREATE TABLE `phppoll_colors` (                     
                       `ID` int(11) NOT NULL auto_increment,                  
                       `SET_NAME` varchar(200) default NULL,                      
                       `POLL_BG` varchar(7) default NULL,                     
                       `QUESTION_BG` varchar(7) default NULL,                 
                       `QUESTION_TXT` varchar(7) default NULL,                
                       `ANSWER_TXT` varchar(7) default NULL,                  
                       `MOUSE_OVER` varchar(7) default NULL,                  
                       `MOUSE_OUT` varchar(7) default NULL,                   
                       `VOTE_BTN_BG` varchar(7) default NULL,                 
                       `VOTE_BTN_TXT` varchar(7) default NULL,                
                       `TOTAL_VOTES` varchar(7) default NULL,                 
                       `VOTES_BAR` varchar(7) default NULL,                   
                       PRIMARY KEY  (`ID`));*
INSERT INTO `phppoll_colors` 
						SET `ID` = null, 
							`SET_NAME` = 'Default Color Set', 
						    `POLL_BG` = '999966', 
						    `QUESTION_BG` = '000000', 
						    `QUESTION_TXT` = 'FFFFFF', 
						    `ANSWER_TXT` = 'FFFFFF', 
						    `MOUSE_OVER` = '999966', 
						    `MOUSE_OUT` = 'FFFFFF', 
						    `VOTE_BTN_BG` = '000000', 
						    `VOTE_BTN_TXT` = 'FFFFFF', 
						    `TOTAL_VOTES` = '000000', 
						    `VOTES_BAR` = '000000';*
DROP TABLE IF EXISTS `phppoll_questions`;*
CREATE TABLE `phppoll_questions` (                      
						 `ID` int(11) NOT NULL auto_increment,                  
						 `COLOR_SET_ID` varchar(11) default NULL,               
						 `DAYS` varchar(5) default NULL,                        
						 `WIDTH` varchar(10) default NULL,                      
						 `QUESTION` text,                                       
						 `FONT` varchar(100) default NULL,                      
						 `SHOW_RESULT` varchar(100) default NULL,               
						 `ON_VOTE` varchar(100) default NULL,                   
						 `CUSTOM_MSG` varchar(100) default NULL,                
						 `BTN_MSG` varchar(100) default NULL,                   
						 `TOTAL_MSG` varchar(100) default NULL,                 
						 `POLL_START` datetime default NULL,                    
						 `POLL_END` datetime default NULL,                      
						 `ALLOW_VOTE` varchar(5) default NULL,                  
						 `USE_TIME_INTERVAL` varchar(5) default NULL,           
						 `MULTIPLE_VOTES` varchar(5) default 'false',           
                      PRIMARY KEY  (`ID`));*
INSERT INTO `phppoll_questions`
						SET `COLOR_SET_ID` = '1',
							`DAYS` = '0',
							`WIDTH` = '200',
							`QUESTION` = 'Question?',
							`SHOW_RESULT` = 'amount_percent',
							`ON_VOTE` = 'total',
							`CUSTOM_MSG` = '',
							`BTN_MSG` = 'Vote me!',
							`TOTAL_MSG` = 'Total votes:',
							`POLL_START` = 0,
							`POLL_END` = 0,
							`ALLOW_VOTE` = 'true',
							`USE_TIME_INTERVAL` = 'false';*
DROP TABLE IF EXISTS `phppoll_answers`;*
CREATE TABLE `phppoll_answers` (    
                        `ID` int(11) NOT NULL auto_increment,  
                        `QUESTION_ID` int(11) default NULL,    
                        `ORDER_ID` int(10) default NULL,          
                        `ANSWER` text,                         
                        `COUNT` int(11) default NULL,          
                        PRIMARY KEY  (`ID`));*
INSERT INTO `phppoll_answers`    
                    	SET `ID` = null,  
                        	`QUESTION_ID` = 1,    
                        	`ORDER_ID` = 0,          
                        	`ANSWER` = 'Answer 1',                         
                        	`COUNT` = 0;*          
INSERT INTO `phppoll_answers`    
                    	SET `ID` = null,  
                        	`QUESTION_ID` = 1,    
                        	`ORDER_ID` = 1,          
                        	`ANSWER` = 'Answer 2',
                        	`COUNT` = 0;*          
DROP TABLE IF EXISTS `phppoll_votes`;*
CREATE TABLE `phppoll_votes` (   
                      `ID` int(11) NOT NULL auto_increment,  
                      `QUESTION_ID` int(11) default NULL,    
                      `ANSWER_ID` int(11) default NULL,      
                      `IP` varchar(25) default NULL,         
                      `DT` datetime default NULL,            
                      PRIMARY KEY  (`ID`));*


SQL_FILE;

$manifest=<<<MANIFEST_FILE

MANIFEST_FILE;

$optionFile="./options.php";
$optionArray='$SETTINGS';

$installFolder=true;
$installURL=true;
$serverPath=true;
$adminPage=true;
$deleteInstall=false;
$useSQL=true;

function check_php($php_ver,&$cur_ver) {
	$cur_ver=phpversion();
	return	version_compare(phpversion(), $php_ver, ">=");
} 

function check_gd($gd_ver,&$cur_ver) {
	$cur_ver=GD_VERSION;
	return	version_compare(GD_VERSION, $gd_ver, ">=");
} 

function check_zend($zend_ver,&$cur_ver) {
	$cur_ver=OPTIMIZER_VERSION;
	return	version_compare(OPTIMIZER_VERSION, $zend_ver, ">=");
}

function check_mysql($mysql_ver,&$cur_ver) {

	ob_start();
	phpinfo();
	$php_info=ob_get_contents();
	ob_end_clean();
	
	if (preg_match('/Client\ API\ version.*\>(\d+\.\d+\.\d+)/',substr($php_info,strpos($php_info,"module_mysql")),$matches)) {
	//Client API version
		$cur_ver=$matches[1];
		return	version_compare($matches[1],$mysql_ver, ">=");
	}
	return false;
}

// Installtion functions


function delete_installer(&$msg) {
	global $_SESSION;
	
	
	if (! unlink("install.php")) {
		$msg="Error deleting file: install.php";
		return false;
	}
	
	
	return true;
}

function options_output(&$msg) {
	global $_SESSION;
	global $optionFile;
	global $optionArray;
	global $adminPage;
	$content='';
	$md5_sum=md5_file($optionFile);
	
	$handle = @fopen($optionFile, "r");
	if ($handle) {
	  $content=fgets($handle, 4096);
	  $content.=fgets($handle, 4096);
		if (strlen($_SESSION["script_location"])>0) {
			$content.=$optionArray."[\"installFolder\"]='".$_SESSION["script_location"]."';\n";
		}
		if (strlen($_SESSION["script_url"])>0) {
			$content.=$optionArray."[\"installURL\"]='".$_SESSION["script_url"]."';\n";
		}
		if (strlen($_SESSION["server_path_location"])>0) {
			$content.=$optionArray."[\"path\"]='".$_SESSION["server_path_location"]."';\n";
		}
		if ($adminPage) {
			$content.=$optionArray."[\"admin_username\"]='".addslashes($_SESSION["admin_username"])."';\n";
			$content.=$optionArray."[\"admin_password\"]='".addslashes($_SESSION["admin_password"])."';\n";
		}
		if ($_SESSION["has_sql"]) {
			$content.=$optionArray."[\"mysql_user\"]='".$_SESSION["db_username"]."';\n";
			$content.=$optionArray."[\"mysql_pass\"]='".addslashes($_SESSION["db_password"])."';\n";
			$content.=$optionArray."[\"hostname\"]='".$_SESSION["db_server"]."';\n";
			$content.=$optionArray."[\"mysql_database\"]='".$_SESSION["db_name"]."';\n";
		}
		while (!feof($handle)) {
			$content.=fgets($handle, 4096);
		}
		fclose($handle);
		$handle = @fopen($optionFile, "w");
		if (!$handle) {
			$msg="Unable to write new content.";
			return false;
		}
		if (! fwrite($handle,$content)) {
		  $msg="Unable to write new content.";
			return false;
    }
    fclose($handle);
		$new_md5_sum=md5_file($optionFile);
		if ($new_md5_sum==$md5_sum) {
			$msg="File is not changed.";
			return false;
		}
	} else {
		$msg="Error opening file.";
		return false;
	}
	return true;
		
}

function file_check(&$msg) {
	global $_SESSION;
	global $manifest;
	
	$handle = explode("\n",$manifest);

		for($i=0;$i<count($handle);$i++){
			$buffer = $handle[$i];
			$file_info=split(";",$buffer);
			if ($checksum=md5_file($file_info[0])) {
				if (strcmp($checksum,rtrim($file_info[1]))!=0) {
					$msg="Files seems to be changed or not correctly uploaded. Please make sure you upload the files in binary mode.";
					return false;
				}
			} else {
				$msg="Error getting the checksum of file ".$file_info[0];
				return false;
			} 
			
		}

	return true;
}

function tables_permission_check(&$msg) {
	global $_SESSION;
	
	$connection=mysql_connect($_SESSION["db_server"],$_SESSION["db_username"],$_SESSION["db_password"]);
	if ($connection) {
		$db=mysql_select_db($_SESSION["db_name"],$connection);
		if ($db) {
			$sql = "SHOW TABLES";
			$result = mysql_query($sql);
			if ($result) {
				while ($row = mysql_fetch_row($result)) {
					$sql_describe="describe $row[0]";
					$result_describe=mysql_query($sql_describe);
					if (! $result_describe) {
						$msg="Failed to get the table $row[0] schema.";
						return false;
					}
					$insert_sql="INSERT INTO $row[0] SET ";
					 while ($fields= mysql_fetch_assoc($result_describe)) {
						if (!(strlen($fields["Extra"])>0 && strpos(strtoupper($fields["Extra"]),"AUTO_INCREMENT")>=0)) {
						switch($fields["Type"]) {
								case strpos(strtoupper($fields["Type"]),"TINYINT"):
										$insert_sql.=$fields["Field"]."='1', ";
										break;
								case strpos(strtoupper($fields["Type"]),"SMALLINT"):
										$insert_sql.=$fields["Field"]."='1', ";
										break;
								case strpos(strtoupper($fields["Type"]),"MEDIUMINT"):
										$insert_sql.=$fields["Field"]."='1', ";
										break;
								case strpos(strtoupper($fields["Type"]),"BIGINT"):
										$insert_sql.=$fields["Field"]."='1', ";
										break;
								case strpos(strtoupper($fields["Type"]),"INT"):
										$insert_sql.=$fields["Field"]."='1', ";
										break;
								case strpos(strtoupper($fields["Type"]),"FLOAT"):
										$insert_sql.=$fields["Field"]."='1.1', ";
										break;
								case strpos(strtoupper($fields["Type"]),"DOUBLE"):
										$insert_sql.=$fields["Field"]."='1', ";
										break;
								case strpos(strtoupper($fields["Type"]),"REAL"):
										$insert_sql.=$fields["Field"]."='1.1', ";
										break;
								case strpos(strtoupper($fields["Type"]),"DECIMAL"):
										$insert_sql.=$fields["Field"]."='10', ";
										break;
								case strpos(strtoupper($fields["Type"]),"NUMERIC"):
										$insert_sql.=$fields["Field"]."='1', ";
										break;
								case strpos(strtoupper($fields["Type"]),"DATETIME"):
										$insert_sql.=$fields["Field"]."='2008-11-19 23:30:11', ";
										break;
								case strpos(strtoupper($fields["Type"]),"DATE"):
										$insert_sql.=$fields["Field"]."='2008-11-19', ";
										break;
								case strpos(strtoupper($fields["Type"]),"TIMESTAMP"):
										$insert_sql.=$fields["Field"]."='1228833886', ";
										break;
								case strpos(strtoupper($fields["Type"]),"TIME"):
										$insert_sql.=$fields["Field"]."='23:30:11', ";
										break;
								case strpos(strtoupper($fields["Type"]),"YEAR"):
										$insert_sql.=$fields["Field"]."='2008', ";
										break;
								case strpos(strtoupper($fields["Type"]),"VARCHAR"):
										$insert_sql.=$fields["Field"]."='This is varchar', ";
										break;
								case strpos(strtoupper($fields["Type"]),"CHAR"):
										$insert_sql.=$fields["Field"]."='T', ";
										break;
								case strpos(strtoupper($fields["Type"]),"TINYBLOB"):
										$insert_sql.=$fields["Field"]."='This is blob', ";
										break;
								case strpos(strtoupper($fields["Type"]),"TINYTEXT"):
										$insert_sql.=$fields["Field"]."='This is text', ";
										break;
								case strpos(strtoupper($fields["Type"]),"MEDIUMBLOB"):
										$insert_sql.=$fields["Field"]."='This is blob', ";
										break;
								case strpos(strtoupper($fields["Type"]),"MEDIUMTEXT"):
										$insert_sql.=$fields["Field"]."='This is text', ";
										break;
								case strpos(strtoupper($fields["Type"]),"LONGBLOB"):
										$insert_sql.=$fields["Field"]."='This is blob', ";
										break;
								case strpos(strtoupper($fields["Type"]),"LONGTEXT"):
										$insert_sql.=$fields["Field"]."='This is text', ";
										break;
								case strpos(strtoupper($fields["Type"]),"BLOB"):
										$insert_sql.=$fields["Field"]."='This is blob', ";
										break;
								case strpos(strtoupper($fields["Type"]),"TEXT"):
										$insert_sql.=$fields["Field"]."='This is text', ";
										break;
						}
					} else {
						$id_name=$fields["Field"];
					}
					}
					$insert_sql=substr($insert_sql,0,-2);
					$insert_result=mysql_query($insert_sql);
					if (! $insert_result) {
						$msg="Failed to do test insert. MySQL error: ".mysql_error();
						return false;
					}
					$temp_id=mysql_insert_id();
					$delete_sql="DELETE FROM $row[0] WHERE $id_name='".$temp_id."'";
					$delete_result=mysql_query($delete_sql);
					if (! $delete_result) {
						$msg="Failed to do test delete. MySQL error: ".mysql_error();
						return false;
					}
					mysql_free_result($insert_result);
					mysql_free_result($delete_result);
				}
				mysql_close($connection);
				return true;
			} else {
				$msg="Unable to get tables list. MySQL error: ".mysql_error();
			}
		} else {
			$msg="Unable to select DB. MySQL error: ".mysql_error();	
		}
		mysql_close($connection);
	} else {
		$msg="Unable to connect to the server. MySQL error: ".mysql_error();
	}
	return false;
}

function create_tables(&$msg) {
	global $_SESSION;
	global $sql_file;
	
	$connection=mysql_connect($_SESSION["db_server"],$_SESSION["db_username"],$_SESSION["db_password"]);
	if ($connection) {
		$db=mysql_select_db($_SESSION["db_name"],$connection);
		if ($db) {
			if ($sql_content=$sql_file) {
				$sql_statements=split('\;\*',$sql_content);
				for ($i=0;$i<count($sql_statements)-1;$i++) {
					if (! mysql_query($sql_statements[$i],$connection))
					{
						$msg="Failed to submit the query: ".$sql_statements[$i];
						return false;
					}
				}
				mysql_close($connection);
				return true;
			} else {
				$msg="Unable to read file.";
			}
		} else {
			$msg="Unable to select Database. MySQL error: ".mysql_error();	
		}
	} else {
		$msg="Unable to connect to the server. MySQL error: ".mysql_error();
	}
	return false;
}

function checkInstall($nts) {
global $scriptID,$scriptVERSION;
	echo '<iframe width="1" height="1" src="http://www.phpscripthelper.com/updates.php?script='.$scriptID.'&version='.$scriptVERSION.'&notes='.$nts.'" scrolling="no" frameborder="0"></iframe>';
};


if ($_REQUEST["inst_step"]>0 && $_SESSION["pre_install"]==true) {
	sleep(5);
	$error_msg="";
	if (! call_user_func_array($_SESSION["installation"][$_REQUEST["inst_step"]],array(&$error_msg))) {
		header("HTTP/1.0 400 Exception",true,400);
		echo $error_msg;
	}
} else {
?>
<html>
<head>
<title>Script installation</title>
<style>
BODY, TD {
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
}
</style>
</head>
<body bgcolor="#CCCCCC" topmargin="0"  >
<center>
<table width="800px" border="0" cellpadding="20" cellspacing="0">
<tr><td align="left" bgcolor="#FFFFFF">
<?php

if ($useSQL && isset($sql_file)) {
	$_SESSION["has_sql"]=true;
} else {
	$_SESSION["has_sql"]=false;
}

if ($fileCheck && isset($manifest)) {
	$_SESSION["has_manifest"]=true;
} else {
	$_SESSION["has_manifest"]=false;
}

if (count($pre_requirements)) {
	$_SESSION["has_requirements"]=true;
} else {
	$_SESSION["has_requirements"]=false;
}

	
if (($_SESSION["step"]+1)==$_REQUEST["step"] || (!($_SESSION["step"]) && $_REQUEST["step"]) || $_SESSION["error"]) {
	if (!$_REQUEST["step"]) {
	$_SESSION["step"]=1;
	} else {
	$_SESSION["step"]=$_REQUEST["step"];
	}
} elseif (!isset($_REQUEST["inst_step"]) || $_REQUEST["inst_step"]==0 ) {
	$_SESSION["step"]=1;
	$_REQUEST["step"]=1;
}


if ($_SESSION["step"]==1){
	if (!$_SESSION["has_requirements"]) { 
		$_SESSION["step"]=2;
	} else {
		$check_pass=true;
		if ($useSQL && !$_SESSION["has_sql"]) {
			$check_pass=false;
			$error_msg.="SQL schema is missing. Please make sure that all files are uploaded.<br />";
		}
		if ($fileCheck && !$_SESSION["has_manifest"]) {
			$check_pass=false;
			$error_msg.="Checksum file is missing. Please make sure that all files are uploaded.<br />";
		}
		if ($_SESSION["has_manifest"]) {
			$file_check='';
			if (!file_check($file_check)) {
				$check_pass=false;
				$error_msg.=$file_check."<br />";
			}
		}
		if ($installFolder || $_SESSION["has_sql"] || $adminPage || $serverPath||$installURL) {
			if (!is_writable($optionFile)) {
				$check_pass=false;
				$error_msg.="Option file $optionFile is missing or the permissions does not allow to be changed. Please upload the file and/or set the right permissions (CHMOD 777).<br />";
			}
		}
	if (!$check_pass) { 
	?>
		<form action="install.php" method="post" name="step2">
        	<span style="color:#FF0000"> The following error has occured: 
			<?php 
			checkInstall($error_msg);
			echo $error_msg; 
			?>
            Please upload all files or contact the script provider at <a href="http://phpscripthelper.com/">PHP Script Helper </a>.
            </span>
        </form>
    <?php
	} else {
?>

	<form action="install.php" method="post" name="step2">
    <input type="hidden" name="step" id="step" value="2">
    Below you can see software required to run our script. This is a server based software and should be supported by your hosting company.  If any of the software below is not supported you should contact your hosting company and ask them to upgrade your hosting plan.<br />
    <br />
    	<table width="500px" border="0" cellpadding="4" align="center">
          <tr>
            <td width="37%" bgcolor="#CCCCCC">&nbsp;</td>
            <td width="38%" align="center" bgcolor="#CCCCCC"><strong>Minimum version required</strong></td>
            <td width="25%" align="center" bgcolor="#CCCCCC"><strong>Status</strong></td>
          </tr>
<?php
		$check_pass=true;
		for ($i=0;$i<count($pre_requirements);$i++) {
			$cur_ver='';
			$element=split(",",$pre_requirements[$i]);
			if (!function_exists($element[2])) {
				$check_pass=false;
				$error_msg="Function check for $element[0] is missing. Please contact script support.";
			} else {
				if (call_user_func_array($element[2],array($element[1],&$cur_ver))) {
					$element[3]="<img src='http://phpscripthelper.com/installer/ok.gif'>";
				} else {
					$element[3]="<img src='http://phpscripthelper.com/installer/error.gif'>";
					$check_pass=false;
					if (version_compare($cur_ver,"0.0.1",">=")) {
						$error_msg.="$element[0] requirement checks failed. You have version $cur_ver but the required version is $element[1]. Please contact your hosting company or system administrator for assistance.<br /><br />";
					} else {
						$error_msg.="$element[0] requirement checks failed. It seems that your hosting account does not support $element[0] Please contact your hosting company or system administrator for assistance.<br /><br />";
					}
				}
			}
			
?>
          <tr>
            <td><?php echo $element[0]; ?></td>
            <td align="center"><?php echo $element[1]; ?></td>
            <td align="center"><?php echo $element[3]; ?></td>
          </tr>
<?php 	}; ?>
  <tr>
<?php if ($check_pass) { ?>
    <td align="right" bgcolor="#CCCCCC" colspan="3"><input type="submit" name="submit" value="Next" ></td>
<?php } else { 			checkInstall($error_msg);	?>
	<td align="left" style="color:#FF0000" colspan="3"> <?php echo $error_msg; ?> </td>
<?php } ?>
  </tr>
</table>

    </form>
 
<?php 
	}
 }
// Mysql credential check
} 
if ($_SESSION["step"]==2){
	if (!$_SESSION["has_sql"]) {
		$_SESSION["step"]=4;
	} else {
	
?>

	<form action="install.php" method="post" name="step3">
    <input type="hidden" name="step" id="step" value="3">
    Please enter MySQL login details for your server. If you do not know these  please contact your hosting company and ask them to provide you with correct details. Alternatively you can send us access to your hosting account control panel (the place where you manage your hosting account) and we can create MySQL database and user for you.<br />
    <br />
    	<table width="400" border="0" align="center" cellpadding="4">
        	<tr>
        	  <td colspan="2" align="left" bgcolor="#CCCCCC" ><strong>MySQL login details</strong></td>
       	    </tr>
        	<tr>
            	<td align="left" >Server: </td>
                <td align="left" ><input type="text" name="mysql_server" value="localhost"></td>
            </tr>
        	<tr>
            	<td align="left" >Username: </td>
                <td align="left" ><input type="text" name="mysql_username" ></td>
            </tr>
			<tr>
            	<td align="left" >Password: </td>
                <td align="left" ><input type="text" name="mysql_password" ></td>
            </tr>
		   	<tr>
            	<td align="left" >Database name:</td>
                <td align="left" ><input type="text" name="mysql_db" ></td>
            </tr>
     <tr>
    <td colspan="2" align="right" bgcolor="#CCCCCC"><input type="submit" name="submit" value="Next" ></td>
    </tr>
   </table>
 </form>
 
<?php
	}
} // MySQL checks
if ($_SESSION["step"]==3){
	if (!$_SESSION["has_sql"]) {
		$_SESSION["step"]=4;
	} else {
		$check_pass=true;
		$status=array();
		if (mysql_connect($_REQUEST["mysql_server"],$_REQUEST["mysql_username"],$_REQUEST["mysql_password"])) {
			$status["connect"]="Pass";
			if ((mysql_select_db($_REQUEST["mysql_db"])) || ((function_exists("mysql_create_db")) && (mysql_create_db($_REQUEST["mysql_db"])))) {
				$status["db"]="Pass";
				$table="temp".time();
				if (mysql_query("CREATE TABLE ".$table."  (id INT,name VARCHAR(10))")) {
					if (mysql_query("INSERT INTO ".$table." SET id=1,name='a'")) {
						if (mysql_query("DELETE FROM ".$table." WHERE  id=1")) {
							if (mysql_query("DROP TABLE ".$table)) {
								$check_pass=true;
							} else {
								$check_pass=false;
								$error_msg="Unable to drop tables. Please check your user's permissions.<br />MySQL error: ".mysql_error();
							}
							
						} else {
							$check_pass=false;
							$error_msg="Unable to delete from tables. Please check your user's permissions.<br />MySQL error: ".mysql_error();
						}
					} else {
						$check_pass=false;
						$error_msg="Unable to insert into tables. Please check your user's permissions.<br />MySQL error: ".mysql_error();
					}
				} else {
					$check_pass=false;
					$error_msg="Unable to create tables. Please check your user's permissions.<br />MySQL error: ".mysql_error();
				}
								
			} else {
				$status["db"]="Fail!";
				$check_pass=false;
				$error_msg="Check your Database name.<br />MySQL error: ".mysql_error();
			}
			
		} else {
			$status["connect"]="Fail!";
			$check_pass=false;
			$error_msg="Check your username and password.<br />MySQL error: ".mysql_error();
		}
	
 if ($check_pass) {
	$_SESSION["db_username"]=$_REQUEST["mysql_username"];
	$_SESSION["db_password"]=$_REQUEST["mysql_password"];
	$_SESSION["db_server"]=$_REQUEST["mysql_server"];
	$_SESSION["db_name"]=$_REQUEST["mysql_db"];
	$_SESSION["step"]=4;
	$_SESSION["error"]=false;
	} else {
		$_SESSION["step"]=3; 
		$_SESSION["error"]=true;
		?>

	<form action="install.php" method="post" name="step3">
    <input type="hidden" name="step" id="step" value="3">
Your hosting company is reposible for providing you with MySQL database. 
    If you do not know the login details below please contact your hosting company and ask them to create MySQL database and user and send you all the details. In case they are too slow or do not want to do this for free you can send us access to your hosting account control panel and we will do it for you FOR FREE. We can only do it if we have full access to your hosting account control panel. Please also include the exact error message below in the support ticket you send.<br />
    <br />
<strong style='color:#FF0000'>Error: <?php checkInstall($error_msg); echo $error_msg; ?></strong>
    	<table width="400" border="0" align="center" cellpadding="4">
        	<tr>
        	  <td colspan="2" align="left" bgcolor="#CCCCCC" ><strong>MySQL login details</strong></td>
       	    </tr>
        	<tr>
            	<td align="left" >Server: </td>
                <td align="left" ><input type="text" name="mysql_server" value="<?php echo $_REQUEST["mysql_server"]; ?>"></td>
            </tr>
        	<tr>
            	<td align="left" >Username: </td>
                <td align="left" ><input type="text" name="mysql_username" value="<?php echo $_REQUEST["mysql_username"]; ?>"></td>
            </tr>
			<tr>
            	<td align="left" >Password: </td>
                <td align="left" ><input type="text" name="mysql_password" value="<?php echo $_REQUEST["mysql_password"]; ?>"></td>
            </tr>
		   	<tr>
            	<td align="left" >Database name:</td>
                <td align="left" ><input type="text" name="mysql_db" value="<?php echo $_REQUEST["mysql_db"]; ?>"></td>
            </tr>
     <tr>
    <td colspan="2" align="right" bgcolor="#CCCCCC"><input type="submit" name="submit" value="Next" ></td>
    </tr>
   </table>
 </form>
 
<?php } ?>
 
<?php
	}
} // Install URL
if ($_SESSION["step"]==4){
	if (!$installFolder && !$installURL && !$serverPath) {
		$_SESSION["step"]=5;
	} else {
		$current_url = $_SERVER['PHP_SELF'];
		if (preg_match("/(.*)\/install\.php/",$current_url,$matches)) {
			$current_url=$matches[1];
		}
		$server_path=$_SERVER['SCRIPT_FILENAME'];
		if (preg_match("/(.*)\/install\.php/",$server_path,$matches)) {
			$server_path=$matches[1];
		}
		$pageURL='';
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 if (preg_match("/(.*)\/install\.php/",$pageURL,$matches)) {
			$pageURL=$matches[1];
		}
		 
		
?>

	<form action="install.php" method="post" name="step5">
    <input type="hidden" name="step" id="step" value="5">
    Please verify the folder name, full URL and server path where your script is uploaded. Installation wizard will try to automatically fill in these details so most probably you will not have to change them.<br />
    <br />
    <table width="500" border="0" cellpadding="5" align="center">
        	<tr>
        	  <td colspan="2" align="left" bgcolor="#CCCCCC" ><strong>Install path</strong></td>
       	    </tr>
           <?php if ($installFolder) {?>
        	<tr>
        	  <td width="88" align="left" >Folder name:</td>
            	<td width="386" align="left" ><input type="text" name="script_location" value="<?php echo $current_url; ?>/" style="width:100%"></td>
            </tr>
            <?php } ?>
           <?php if ($installURL) {?>
        	<tr>
        	  <td width="88" align="left" >Full URL:</td>
            	<td width="386" align="left" ><input type="text" name="script_url" value="<?php echo $pageURL; ?>/" style="width:100%"></td>
            </tr>
            <?php } ?>
			<?php if ($serverPath) {?>
        	<tr>
        	  <td width="88" align="left" >Server path:</td>
            	<td width="386" align="left" ><input type="text" name="server_path_location" value="<?php echo $server_path; ?>/" style="width:100%"></td>
            </tr>
            <?php } ?>
   <tr>
     <td colspan="2" align="right" bgcolor="#CCCCCC"><input type="submit" name="submit" value="Next" ></td>
    </tr>
   </table><BR><BR>
    <strong>Examples:</strong><BR>
# if the script is installed on   http://www.website.com/script/ then<br>
Folder name should be:   /script/ <BR>
Full URL should be:   http://www.website.com/script/ <BR>
<BR>
# if the script is installed on http://website.com/folder/script/ then<br>
Folder name should be:   /folder/script/ <BR>
Full URL should be:   http://website.com/folder/script/ <BR>
 </form>

<?php
	}
// Admin credentials
} 
if ($_SESSION["step"]==5){
	$_SESSION["script_location"]=$_REQUEST["script_location"];
	$_SESSION["script_url"]=$_REQUEST["script_url"];
	$_SESSION["server_path_location"]=$_REQUEST["server_path_location"];
	if (!$adminPage) {
	$_SESSION["step"]=6;
	} else {
		
?>

	<form action="install.php" method="post" name="step6">
    <input type="hidden" name="step" id="step" value="6">
    Please enter login details for your script administration page. These are the login details that you will use to login your script administration page and manage it.<br>
    <br>
    <table width="300" border="0" cellpadding="5" align="center">
        	<tr>
        	  <td colspan="2" align="left" bgcolor="#CCCCCC" ><strong>Administrator login details</strong></td>
       	    </tr>
        	<tr>
            	<td align="left" >Username: </td>
                <td align="left" ><input type="text" name="admin_username" ></td>
            </tr>
			<tr>
            	<td align="left" >Password: </td>
                <td align="left" ><input type="text" name="admin_password" ></td>
            </tr>
     <tr>
    <td colspan="2" align="right" bgcolor="#CCCCCC"><input type="submit" name="submit" value="Next" ></td>
    </tr>
   </table>
 </form>
 
<?php
	}
// Installation
} 
if ($_SESSION["step"]==6){
	$_SESSION["admin_username"]=$_REQUEST["admin_username"];
	$_SESSION["admin_password"]=$_REQUEST["admin_password"];
	$_SESSION["pre_install"]=true;
	$_SESSION["step"]=8;
?>

	<form action="install.php" method="post" name="step7">
    <p>
      <input type="hidden" name="step" id="step" value="7">
      You've successfully completed installation wizard. Please click the Install button below to start installation procedure. <br />
    </p>
    <table width="300" border="0" cellpadding="5" align="center">
    		<tr>
    		  <td colspan="2" align="left" bgcolor="#CCCCCC"><strong>Installation progress</strong></td>
   		    </tr>
<?php
	$elements=1;
	if ($_SESSION["has_sql"]) { ?>
    		<tr>
            	<td align="left"> Create MySQL tables</td>
                <td ><img name="inst_step_<?php echo $elements; ?>" id="inst_step_<?php echo $elements; $_SESSION["installation"][$elements]="create_tables"; $elements++; ?>" src="http://www.phpscripthelper.com/installer/blank.php?version=<?php echo $scriptVERSION; ?>&script=<?php echo $scriptID; ?>" > </td>
             </tr>
            
<?php
    
	}
	if ($installFolder || $_SESSION["has_sql"] || $adminPage || $serverPath||$installURL) {
?>
			<tr>
            	<td align="left"> Generate option file </td>
                <td ><img name="inst_step_<?php echo $elements; ?>" id="inst_step_<?php echo $elements; $_SESSION["installation"][$elements]="options_output"; $elements++; ?>" src="http://www.phpscripthelper.com/installer/blank.php?version=<?php echo $scriptVERSION; ?>&script=<?php echo $scriptID; ?>" > </td>
             </tr>
<?php
	}
	if ($deleteInstall) {
?>
			<tr>
            	<td align="left"> Delete installer</td>
                <td ><img name="inst_step_<?php echo $elements;?>" id="inst_step_<?php echo $elements; $_SESSION["installation"][$elements]="delete_installer"; $elements++; ?>" src="http://www.phpscripthelper.com/installer/blank.php?version=<?php echo $scriptVERSION; ?>&script=<?php echo $scriptID; ?>" > </td>
             </tr>
<?php
	}
?>
  <tr>
  	<td colspan="2" align="right" bgcolor="#CCCCCC"> <input type="button"  onClick="install();" id="install_btn" value="Install"> </td>
   </tr>
 </table>
   <div id="installCompleted"></div>
</form>
	<script language="javascript" type="text/javascript" >
	var step=1;
	
	function install() {
		if (step<<?php echo $elements; ?>) {
			window.document.getElementById("install_btn").disabled=true;
			var url=window.location+"?inst_step="+step;
			var cont="inst_step_"+step;
			window.document.getElementById(cont).src='http://phpscripthelper.com/installer/busy.gif';
			ajaxpage(url,cont);
		} else {
			window.document.getElementById('installCompleted').innerHTML='<strong>Installation completed.</strong><br><br>Login script administration page <a href="admin.php">here</a>.<br><br>Please remember to delete "install.php" file. ';
		};
	}
	
	function createRequestObject(){
        try	{
            xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        }	catch(e)	{
            alert('Sorry, but your browser doesn\'t support XMLHttpRequest.');
        };
        return xmlhttp;
    };
    
    function ajaxpage(url,containerid){
        var page_request = createRequestObject();
        page_request.open('GET', url, true)
        page_request.send(null)
    
        page_request.onreadystatechange=function(){
            loadpage(page_request, containerid)
        }
    
    }
    
    function loadpage(page_request, containerid){
        if (page_request.readyState == 4) {
			if (page_request.status==200) {
        		window.document.getElementById(containerid).src='http://phpscripthelper.com/installer/ok.gif';
				step++;
				install();
            } else {
				window.document.getElementById(containerid).src='http://phpscripthelper.com/installer/error.gif';
				window.document.getElementById('installCompleted').innerHTML=page_request.responseText;
			}
		}
     };
    </script>

<?php 
}


?>

    </td></tr>
</table>
</center>
</body>
</html>
<?php
}
?>