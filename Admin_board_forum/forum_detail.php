<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once("../webassist/ckeditor/ckeditor.php"); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO forum_reply (ID, reply_id, reply_content, reply_by, reply_date) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['reply_id'], "int"),
                       GetSQLValueString($_POST['reply_content'], "text"),
                       GetSQLValueString($_POST['reply_by'], "text"),
                       GetSQLValueString($_POST['reply_date'], "date"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "forum_index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE forum_posts SET `last_reply` = (SELECT MAX(reply_date) FROM forum_reply WHERE forum_posts.post_ID=forum_reply.reply_id)",
                       GetSQLValueString($_POST['last_reply'], "date"),
                       GetSQLValueString($_POST['post_ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
}

$colname_forum_topic = "-1";
if (isset($_GET['postdet'])) {
  $colname_forum_topic = $_GET['postdet'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_forum_topic = sprintf("SELECT date_format(post_date,'%%b %%e, %%Y, %%r') AS PostOn, date_format(post_edit_on,'%%b %%e, %%Y, %%r') AS PostEdit, post_ID, post_order, post_category, post_topic, post_content, post_by, post_date, post_edit_on, post_edit_by, post_current, forum_posts.admin_comment FROM forum_posts WHERE post_ID = %s", GetSQLValueString($colname_forum_topic, "int"));
$forum_topic = mysql_query($query_forum_topic, $connvbsa) or die(mysql_error());
$row_forum_topic = mysql_fetch_assoc($forum_topic);
$totalRows_forum_topic = mysql_num_rows($forum_topic);

$colname_forum_reply = "-1";
if (isset($_GET['postdet'])) {
  $colname_forum_reply = $_GET['postdet'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_forum_reply = sprintf("SELECT date_format(reply_edit,'%%b %%e, %%Y, %%r') AS EditOn, date_format(reply_date,'%%b %%e, %%Y, %%r') AS ReplyOn,  ID, reply_id, reply_content, reply_by, reply_date, reply_edit, forum_posts.post_ID, CASE WHEN reply_edit >= reply_date THEN reply_edit ELSE reply_date END AS MostRecentReply FROM forum_reply, forum_posts WHERE reply_id=forum_posts.post_ID AND reply_id = %s ORDER BY MostRecentReply DESC", GetSQLValueString($colname_forum_reply, "int"));
$forum_reply = mysql_query($query_forum_reply, $connvbsa) or die(mysql_error());
$row_forum_reply = mysql_fetch_assoc($forum_reply);
$totalRows_forum_reply = mysql_num_rows($forum_reply);

$colname_attach = "-1";
if (isset($_GET['postdet'])) {
  $colname_attach = $_GET['postdet'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_attach = sprintf("SELECT ID, attach_id, attach_name, Attachment, upload_on, upload_by, forum_posts.post_ID, forum_posts.post_topic FROM forum_attach, forum_posts WHERE forum_posts.post_ID=attach_id AND attach_id = %s ORDER BY forum_attach.upload_on ASC", GetSQLValueString($colname_attach, "int"));
$attach = mysql_query($query_attach, $connvbsa) or die(mysql_error());
$row_attach = mysql_fetch_assoc($attach);
$totalRows_attach = mysql_num_rows($attach);

$colname_postID = "-1";
if (isset($_GET['postdet'])) {
  $colname_postID = $_GET['postdet'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_postID = sprintf("SELECT post_ID FROM forum_posts WHERE post_ID = %s", GetSQLValueString($colname_postID, "int"));
$postID = mysql_query($query_postID, $connvbsa) or die(mysql_error());
$row_postID = mysql_fetch_assoc($postID);
$totalRows_postID = mysql_num_rows($postID);

$colname_comment = "-1";
if (isset($_GET['postdet'])) {
  $colname_comment = $_GET['postdet'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_comment = sprintf("SELECT post_ID, admin_comment FROM forum_posts WHERE post_ID=%s", GetSQLValueString($colname_comment, "int"));
$comment = mysql_query($query_comment, $connvbsa) or die(mysql_error());
$row_comment = mysql_fetch_assoc($comment);
$totalRows_comment = mysql_num_rows($comment);

mysql_select_db($database_connvbsa, $connvbsa);
$query_forum_users = "SELECT * FROM vbsaorga_users WHERE email is not null ORDER BY vbsaorga_users.name";
$forum_users = mysql_query($query_forum_users, $connvbsa) or die(mysql_error());
$row_forum_users = mysql_fetch_assoc($forum_users);
$totalRows_forum_users = mysql_num_rows($forum_users);

$colname_reply_date = "-1";
if (isset($_GET['postdet'])) {
  $colname_reply_date = $_GET['postdet'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_reply_date = sprintf("SELECT post_ID, last_reply, forum_reply.reply_id, MAX(forum_reply.reply_date) FROM forum_posts, forum_reply WHERE post_ID=reply_id AND post_ID = %s", GetSQLValueString($colname_reply_date, "int"));
$reply_date = mysql_query($query_reply_date, $connvbsa) or die(mysql_error());
$row_reply_date = mysql_fetch_assoc($reply_date);
$totalRows_reply_date = mysql_num_rows($reply_date);

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../Admin_xx_CSS/forum_db.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/forum_db_links.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="forum_header">
  <div id="logo"><img src="../images/VBSA1.jpg" alt="" width="90" height="87" /></div>

<table width="870" align="right">
  <tr>

    <td class="red_bold">VBSA Administrators Forum</td>
    <td align="right" class="bluebg"><a href="How%20to%20use%20the%20VBSA%20Board%20Forum.pdf" target="_blank">How to use the Forum</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="red_bold"><span class="red_bld_txt">Topic Detail - Lists Topic &amp; all replies, scroll to bottom of page to insert a new reply</span></td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

<div id="ContentDB">
    
  <div class="detail">
    <?php do { ?>
      <div class="content_header">
        
        <div class="edit"><a href="forum_edit_post.php?post_edit=<?php echo $row_forum_topic['post_ID']; ?>">
          <img src="../Admin_Images/edit_butt.png" width="18" height="18" title="Edit this topic" /></a>
        </div>
        
        <div class="upload"> <a href="forum_attach_insert.php?upload=<?php echo $row_forum_topic['post_ID']; ?>">
          <img src="../Admin_Images/Up_attach.png" width="35" height="18" title="Upload/Edit/Delete attachment/s to this topic" /></a>
        </div>
        
        <strong><?php echo $row_forum_topic['post_topic']; ?></strong>, &nbsp; &nbsp; &nbsp;
        by: <em><?php echo $row_forum_topic['post_by']; ?></em>&nbsp; &nbsp; &nbsp;
        On: <em><?php echo $row_forum_topic['PostOn']; ?></em>&nbsp; &nbsp; &nbsp; 
        <?php
		if($row_forum_topic['PostEdit']=='')
		{
		echo '';
		}
	    elseif($row_forum_topic['PostEdit']>="1")
		{
		echo "Edited: ";
		echo '<em>';
		echo ($row_forum_topic['PostEdit']);
		echo '</em>';
		}
		?>
      </div> 
      
      
      
      <div class="content"><?php echo $row_forum_topic['post_content']; ?></div>
      <?php } while ($row_forum_topic = mysql_fetch_assoc($forum_topic)); ?>
    <div class="footer">

<?php 
        if($row_attach['post_ID']=='')
		{
		echo '';
		}
        elseif($row_attach['post_ID']<>'')
		{
		?>
		<span>

			<div class="edit"><a href="forum_attach_all.php?attach_id=<?php echo $row_attach['post_ID']; ?>">
    		<img src="../Admin_Images/edit_butt.png" width="18" height="18" title="Edit attachments" /></a>
  			</div>

        </span> 
		<?php
		}
        ?>
  
    
        <div class="name">
          <?php
		if($row_attach['attach_name']=='')
		{
		echo '';
		}
	    elseif($row_attach['attach_name']<>'')
		{
		echo "Attachments: ";
		
		}
		?>
        </div>
      <?php do { ?>     
          <div class="open"> 
            <?php 
        if($row_attach['attach_name']=='')
		{
		echo '';
		}
        elseif($row_attach['attach_name']<>'')
		{
		echo ($row_attach['attach_name']);
		}
        ?>
          </div>
          
        <div class="view">
            
            <?php 
        if($row_attach['attach_name']=='')
		{
		echo '';
		}
        elseif($row_attach['attach_name']<>'')
		{
		?>
            <span>
              
            <a href="<?php echo "/Admin_board_forum/forum_upload_attachments/".$row_attach['Attachment']; ?>" target="_blank">
            <img src="../Admin_Images/Open_attach.fw.png" width="26" height="18"  title="Open Attachment" /></a>
              
            </span> 
            <?php
		}
        ?>
            
            
        </div>
        <?php } while ($row_attach = mysql_fetch_assoc($attach)); ?>
    </div> <!--Close footer -->   

  <div class="comment"> 
    <?php do { ?>
      <?php
		if($row_comment['admin_comment']=='')
		{
		echo '';
		}
	    elseif($row_comment['admin_comment']<>'')
		{
		echo '<span class="red_bold"><strong>Administrators Comment: </strong></span>';
		echo ($row_comment['admin_comment']);
		}
		?>
      <?php } while ($row_comment = mysql_fetch_assoc($comment)); ?>
  </div>  



      
      
  
        
        
        
</div>      <!--Close Detail -->    

  

  
    <?php do { ?>
    <div class="forum_reply">
        <div class="forum_reply_header">
          <div class="forum_reply_by">
            <?php
		if($row_forum_reply['reply_by']=='')
		{
		echo '';
		}
	    elseif($row_forum_reply['reply_by']<>'')
		{
		echo 'Reply By: ';
		echo $row_forum_reply['reply_by'];
		}
		?>
          </div>
          <div class="forum_reply_on">
            <?php
		if($row_forum_reply['ReplyOn']=='')
		{
		echo '';
		}
	    elseif($row_forum_reply['ReplyOn']<>'')
		{
		echo 'Replied on: ';
		echo $row_forum_reply['ReplyOn'];
		}
		?>
          </div>
          <div class="forum_reply_edited_on">
            <?php
		if($row_forum_reply['EditOn']=='')
		{
		echo '';
		}
	    elseif($row_forum_reply['EditOn']<>'')
		{
		echo "Edited: ";
		echo ($row_forum_reply['EditOn']);
		}
		?>
          </div>
          
          <div class="forum_topic_reply_edit">
            <?php
		if($row_forum_reply['reply_id']=='')
		{
		echo 'No Replies to this topic';
		}
	    elseif($row_forum_reply['reply_id']==$row_forum_reply['post_ID'])
		{
		
		echo '<a href="forum_edit_reply.php?reply_edit=' . $row_forum_reply['ID'] . '">
			<img src="../Admin_Images/edit_butt.png" width="20" height="20" title="Edit this reply" />
			</a>'; 
		}
		?>
          </div>
          
        </div>
        
        
        
        <div class="forum_reply_content"><?php echo $row_forum_reply['reply_content']; ?></div>
    </div>
    <?php } while ($row_forum_reply = mysql_fetch_assoc($forum_reply)); ?>
  <div class="forum_reply_insert">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table width="990" align="center">
        <tr valign="baseline">
          <td width="173" align="right" valign="top" nowrap="nowrap"><p>Insert a new reply to this topic</p>
          <p>Type your reply:</p></td>
          <td width="805"><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "";
$CKEditor = new CKEditor();
$CKEditor->basePath = "../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "forum1";
$CKEditor_config["wa_preset_file"] = "forum1.xml";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "200px";
$CKEditor_config["skin"] = "kama";
$CKEditor_config["docType"] = "<" ."!" ."DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
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
array( 'Undo','Redo','Find','Replace'));
$CKEditor_config["contentsLangDirection"] = "ltr";
$CKEditor_config["entities"] = false;
$CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
$CKEditor_config["pasteFromWordRemoveStyles"] = false;
$CKEditor->editor("reply_content", $CKEditor_initialValue, $CKEditor_config);
?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Reply By</td>
          <td><?php echo $row_getusername['name']; ?> (auto inserted)</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Submit reply" /></td>
        </tr>
      </table>
      <input type="hidden" name="ID" value="" />
      <input type="hidden" name="reply_id" value="<?php echo $row_postID['post_ID']; ?>" />
      <input type="hidden" name="reply_date" value="" />
      <input type="hidden" name="reply_by" value="<?php echo $row_getusername['name']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
      <input type="hidden" name="MM_update" value="form1" />
      
      
    </form>

  </div><!--Close Content -->
</div>

</body>
</html>
<?php
mysql_free_result($forum_topic);

mysql_free_result($forum_reply);

mysql_free_result($attach);

mysql_free_result($postID);

mysql_free_result($comment);

mysql_free_result($forum_users);

mysql_free_result($reply_date);

mysql_free_result($getusername);
?>
