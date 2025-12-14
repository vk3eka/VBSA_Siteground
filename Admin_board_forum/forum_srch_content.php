<?php require_once('../Connections/connvbsa.php'); ?>
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

$colname_forum_topic = "-1";
if (isset($_GET['forum_content'])) {
  $colname_forum_topic = $_GET['forum_content'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_forum_topic = sprintf("SELECT date_format( post_date, '%%b %%e, %%Y, %%r' ) AS PostOn, date_format( post_edit_on, '%%b %%e, %%Y, %%r' ) AS PostEdit, post_ID, post_order, post_category, post_topic, post_content, post_by, post_date, post_edit_on, post_edit_by, post_current, last_post, date_format( last_reply, '%%b %%e, %%Y, %%r' ) AS Most_Recent_reply, CASE WHEN last_post >= last_reply THEN last_post WHEN last_reply IS NULL THEN last_post ELSE last_reply END AS MostRecentDate, forum_posts.admin_comment FROM forum_posts WHERE Blocked = 'No' AND post_content LIKE %s ORDER BY MostRecentDate DESC", GetSQLValueString("%" . $colname_forum_topic . "%", "text"));
$forum_topic = mysql_query($query_forum_topic, $connvbsa) or die(mysql_error());
$row_forum_topic = mysql_fetch_assoc($forum_topic);
$totalRows_forum_topic = mysql_num_rows($forum_topic);

$colname_forum_reply = "-1";
if (isset($_GET['postdet'])) {
  $colname_forum_reply = $_GET['postdet'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_forum_reply = sprintf("SELECT reply_id, reply_content, reply_by, reply_date, forum_posts.post_ID FROM forum_reply, forum_posts WHERE reply_id=forum_posts.post_ID AND reply_id = %s ORDER BY forum_reply.reply_date", GetSQLValueString($colname_forum_reply, "int"));
$forum_reply = mysql_query($query_forum_reply, $connvbsa) or die(mysql_error());
$row_forum_reply = mysql_fetch_assoc($forum_reply);
$totalRows_forum_reply = mysql_num_rows($forum_reply);

mysql_select_db($database_connvbsa, $connvbsa);
$query_AdComm = "SELECT post_ID, post_topic, admin_comment FROM forum_posts WHERE forum_posts.Blocked='Yes'";
$AdComm = mysql_query($query_AdComm, $connvbsa) or die(mysql_error());
$row_AdComm = mysql_fetch_assoc($AdComm);
$totalRows_AdComm = mysql_num_rows($AdComm);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Board Members Forum</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>
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
    <td colspan="2" align="left" class="red_bold">Forum Content search result - All Topics both Current and Archived</td>
  </tr>
  </table>
  
<?php include '../admin_xx_includes/forum_nav.php';?>

</div>

<div id="ContentDB">
<div class="page_header">Select  a topic and you may then read all current replies, submit a reply, open, edit or upload  attachments.</div>

<div class="search_wrapper">
  
  <div class="search_content">
    <form id="form6" name="form6" method="get" action="forum_srch_title.php">
      <div class="textfield_search"><input name="forum_title" type="text" id="forum_title" class="textfield_effect" /></div>
      <input type="submit" name="forum_title_srch" id="forum_title_srch" value="Search Topics by title"  class="button_srch" />
      </form>
    </div>
  
  <div class="search_content">
    <form id="form5" name="form5" method="get" action="forum_srch_content.php">
      <div class="textfield_search"><input name="forum_content" type="text" id="forum_content" class="textfield_effect" /></div>
      <input type="submit" name="forum_content_srch" id="forum_content_srch" value="Search Topics by content"  class="button_srch" />
      </form>
    </div>
</div>

  <?php do { ?>
    <div class="post"><a href="forum_detail.php?postdet=<?php echo $row_forum_topic['post_ID']; ?>">
    <div class="post_bg">
    
    <div id="admin_comment">
    <?php 
        if($row_forum_topic['admin_comment']=='')
		{
		echo '';
		}
        elseif($row_forum_topic['admin_comment']<>'')
		{
		?>
		<span>
        <img src="../Admin_Images/red_flag.fw.png" width="24" height="24" title="Administrators comment" />
        </span> 
		<?php
		}
        ?>
    </div> 
    
    
      <div class="content_header">
        
        <strong><?php echo $row_forum_topic['post_topic']; ?></strong>&nbsp; &nbsp; &nbsp;
        by: <em><?php echo $row_forum_topic['post_by']; ?></em>&nbsp; &nbsp; &nbsp;
		On: <em><?php echo $row_forum_topic['PostOn']; ?></em>&nbsp; &nbsp; &nbsp;
        <?php
		if($row_forum_topic['PostEdit']=='')
		{
		echo '';
		}
	    elseif($row_forum_topic['PostEdit']<>'')
		{
		echo "Edited: ";
		echo '<em>';
		echo ($row_forum_topic['PostEdit']);
		echo '</em>';
		}
		?>
        &nbsp; &nbsp; &nbsp;
        <?php
		if($row_forum_topic['Most_Recent_reply']=='')
		{
		echo '';
		}
	    elseif($row_forum_topic['Most_Recent_reply']<>'')
		{
		echo "Most Recent Reply: ";
		echo '<em>';
		echo ($row_forum_topic['Most_Recent_reply']);
		echo '</em>';
		}
		?>
        </div> 
        
        
        
      <div class="content">

      
      
      
<?php

//explode the text (string) into sections that end with </p>
$text = $string. $row_forum_topic["post_content"];

$exptext = explode("</p>", $text);


if(strlen($exptext[0]) < 150)
{
echo $exptext[0];

}
// if exploded text exceeds 150 characters break at a space
	
elseif(strlen($exptext[0]) > 150)
	{ 
$position=150; // Define how many characters you want to display.

$message=$row_forum_topic['post_content'];

 

// Find what is the last character.
$post = substr($message,$position,1);


// In this step, if the last character is not " "(space) run this code.

// Find until we found that last character is " "(space)
// by $position+1 (14+1=15, 15+1=16 until we found " "(space) that mean character no.20)
if($post !=" "){


while($post !=" "){
$i=1;
$position=$position+$i;

$message=$row_forum_topic['post_content'];
$post = substr($message,$position,1);
}

}

$post = substr($message,0,$position);

// Display your message
echo $post;
echo "...";

}

?>
</div>
</div>
</a>
</div>
    
    <?php } while ($row_forum_topic = mysql_fetch_assoc($forum_topic)); ?>
</div>

</body>
</html>
<?php
mysql_free_result($forum_topic);

mysql_free_result($forum_reply);

mysql_free_result($AdComm);
?>
