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
if(!$adminPoll){
	error_reporting(0);
	include("options.php");
}

$access_ip = (getenv(HTTP_X_FORWARDED_FOR)) ?  getenv(HTTP_X_FORWARDED_FOR) :  getenv(REMOTE_ADDR);

$sql = "SELECT * FROM ".$TABLES["QUESTIONS"]." WHERE ID='".$_REQUEST["id"]."'";
$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
$QUESTION = mysql_fetch_assoc($sql_result);

$sql = "SELECT * FROM ".$TABLES["VOTES"]." WHERE QUESTION_ID='".$QUESTION["ID"]."' AND DATE_SUB(CURDATE(),INTERVAL ".$QUESTION["DAYS"]." DAY) <= DATE_FORMAT(DT, '%Y-%m-%d') AND IP='".$access_ip."'";
$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
$alreadyVoted = mysql_num_rows($sql_result);

if(!$colorSet) $sql = "SELECT * FROM ".$TABLES["COLORS"]." WHERE ID='".$QUESTION["COLOR_SET_ID"]."'";
else $sql = "SELECT * FROM ".$TABLES["COLORS"]." WHERE ID='".$colorSet."'";
$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
$COLORSET	 = mysql_fetch_assoc($sql_result);
if (mysql_num_rows($sql_result) == 0 ){
	$sql = "SELECT * FROM ".$TABLES["COLORS"]." WHERE ID='1'";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	$COLORSET	 = mysql_fetch_assoc($sql_result);
}
if($QUESTION["DAYS"]>0 && $alreadyVoted>0) {
	$QUESTION["ALLOW_VOTE"] = "false";
	$_REQUEST["ac"] = 'vote';
};

if ($_REQUEST["ac"] == 'vote') {

	if (($QUESTION["DAYS"]>0 && $alreadyVoted==0) or $QUESTION["DAYS"]==0) {

		$selectedAnswers = explode("-",$_REQUEST["answers"]);
		for($i=0;$i<count($selectedAnswers);$i++){
			$orderId = $selectedAnswers[$i];
			if($orderId == "") continue;
			$sql = "UPDATE ".$TABLES["ANSWERS"]." SET `COUNT` = `COUNT`+1 WHERE `QUESTION_ID` = '".$_REQUEST["id"]."' AND `ORDER_ID` = '".$orderId."'";
			$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
			
			$sql = "SELECT ID FROM ".$TABLES["ANSWERS"]." WHERE `QUESTION_ID` = '".$_REQUEST["id"]."' AND `ORDER_ID` = '".$orderId."'";
			$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
			$answer = mysql_fetch_assoc($sql_result);
			
			$sql = "INSERT INTO `".$TABLES["VOTES"]."` 
					SET `QUESTION_ID` = '".$_REQUEST["id"]."', 
						`ANSWER_ID` = ".$orderId.",
						`IP` = '".$access_ip."',
						`DT` = now()";
			$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
		}
	};
?>
	<div id="poll_<?php echo $QUESTION["ID"]; ?>" style="width:<?php echo $QUESTION["WIDTH"]; ?>px; cursor:default; background-color:#<?php echo $COLORSET["POLL_BG"]; ?>; font-family:<?php echo $QUESTION["FONT"]; ?>;">
    	<div id="poll_question" style="width:100%; padding:0px; background-color:#<?php echo $COLORSET["QUESTION_BG"]; ?>; color:#<?php echo $COLORSET["QUESTION_TXT"]; ?>; text-align:left">
        <div style="padding:4px"><?php echo stripslashes(utf8_decode($QUESTION["QUESTION"])); ?></div></div>

		<?php
            $sql = "SELECT sum(COUNT) FROM ".$TABLES["ANSWERS"]." WHERE QUESTION_ID='".$QUESTION["ID"]."'";
            $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);	
			$Votes = mysql_fetch_assoc($sql_result);
			$totalVotes = $Votes["sum(COUNT)"];
		
            $sql = "SELECT * FROM ".$TABLES["ANSWERS"]." WHERE QUESTION_ID='".$QUESTION["ID"]."' ORDER BY `ORDER_ID`";
            $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
            while ($ANSWER = mysql_fetch_assoc($sql_result)) {
				$barPercent = round(($ANSWER["COUNT"] / $totalVotes)*100);
				$barWidth = ceil($QUESTION["WIDTH"] * $barPercent / 100);
				if ($barPercent>=90) {
					$barWidth = $barWidth - 12;
				} elseif ($barPercent==0) {
					$barWidth = 4;
				};
        ?>
            <div style="padding:10px 5px 2px 5px; color:#<?php echo $COLORSET["ANSWER_TXT"]; ?>; text-align:left">
                <?php echo stripslashes(utf8_decode($ANSWER["ANSWER"])); ?><br />
                <?php
                if ($QUESTION["SHOW_RESULT"]!=='nothing') {
                ?>
				<div style="width:<?php echo $barWidth; ?>px; height:10px; margin:5px 2px 0px 0px; background-color:#<?php echo $COLORSET["VOTES_BAR"]; ?>; padding:2px; font-size:1px;">&nbsp;</div>
                <div>
				<?php 
						if ($QUESTION["SHOW_RESULT"]=='amount') echo $ANSWER["COUNT"];
						elseif ($QUESTION["SHOW_RESULT"]=='percent') echo round(($ANSWER["COUNT"] / $totalVotes)*100) . "%"; 
						elseif ($QUESTION["SHOW_RESULT"]=='amount_percent') echo $ANSWER["COUNT"] . " / " . round(($ANSWER["COUNT"] / $totalVotes)*100) . "%"; 
				?>
                </div>
                <?php
				};
				?>
                	
            </div>
        <?php
        	}
        ?>
    	<div style="text-align:center; padding:10px 0px 10px 0px; color:#<?php echo $COLORSET["TOTAL_VOTES"]; ?>">
            <?php 
				if ($QUESTION["ON_VOTE"]=='message') echo stripslashes(utf8_decode($QUESTION["CUSTOM_MSG"]));
				elseif($QUESTION["ON_VOTE"]=='total') echo stripslashes(utf8_decode($QUESTION["TOTAL_MSG"])) . " " . $totalVotes;
			?>
        </div>         
    </div>
<?php
} else {
	if($QUESTION["USE_TIME_INTERVAL"] == "true"){
		$currentTime = time();
		$sTime = strtotime($QUESTION["POLL_START"]);
		$eTime = strtotime($QUESTION["POLL_END"]);
		if(($currentTime < $sTime) or ($currentTime > $eTime)) {
			$QUESTION["ALLOW_VOTE"] = "false";
		};
	};
	if($QUESTION["MULTIPLE_VOTES"] == "true") $voteElement = "checkbox";
	else $voteElement = "radio";
?>
<form name="PollFrm<?php echo $QUESTION["ID"]; ?>" action="poll.php" method="post">
	<div id="poll_<?php echo $QUESTION["ID"]; ?>" style="width:<?php echo $QUESTION["WIDTH"]; ?>px; background-color:#<?php echo $COLORSET["POLL_BG"]; ?>; font-family:<?php echo $QUESTION["FONT"]; ?>;">
    	<div id="poll_question" style="width:100%; padding:0px; background-color:#<?php echo $COLORSET["QUESTION_BG"]; ?>; color:#<?php echo $COLORSET["QUESTION_TXT"]; ?>; text-align:left">
        <div style="padding:4px"><?php echo stripslashes(utf8_decode($QUESTION["QUESTION"])); ?></div></div>

		<?php
			$answerIndx = 0;
            $sql = "SELECT * FROM ".$TABLES["ANSWERS"]." WHERE QUESTION_ID='".$QUESTION["ID"]."' ORDER BY `ORDER_ID`";
            $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
            while ($ANSWER = mysql_fetch_assoc($sql_result)) {
        ?>
            <div id="answer_<?php echo $ANSWER["ORDER_ID"]; ?>" style="cursor:pointer; color:#<?php echo $COLORSET["ANSWER_TXT"]; ?>; text-align:left; padding:2px 5px 2px 5px" onmouseover="style.backgroundColor='#<?php echo $COLORSET["MOUSE_OVER"]; ?>';" onmouseout="style.backgroundColor='#<?php echo $COLORSET["POLL_BG"]; ?>';" onclick="SelectFormElement<?php echo $QUESTION["ID"]; ?>('<?php echo $voteElement; ?>',<?php echo $answerIndx; ?>)">
                	<input type="<?php echo $voteElement; ?>" name="answers<?php echo $QUESTION["ID"]; ?>" id="answers<?php echo $QUESTION["ID"]; ?>" value="<?php echo $ANSWER["ORDER_ID"]; ?>" onclick="SelectFormElement<?php echo $QUESTION["ID"]; ?>('<?php echo $voteElement; ?>',<?php echo $answerIndx; ?>)" /> 
					<?php echo stripslashes(utf8_decode($ANSWER["ANSWER"])); ?>
            </div>
        <?php
			$answerIndx++;
        	}
        ?>
        
        <?php if($QUESTION["ALLOW_VOTE"]<>'false'){ ?>
    	<div id="poll_btn" style="text-align:center; padding:10px 0px 10px 0px; cursor:pointer;" onclick="submitVote<?php echo $QUESTION["ID"]; ?>('<?php echo $voteElement; ?>',<?php echo $QUESTION["ID"]; ?>);">
        	<span style="padding: 5px 5px 5px 5px; background-color:#<?php echo $COLORSET["VOTE_BTN_BG"]; ?>; color:#<?php echo $COLORSET["VOTE_BTN_TXT"]; ?>"><?php echo stripslashes(utf8_decode($QUESTION["BTN_MSG"])); ?></span>
        </div> 
        <?php } ?>
        
    </div>
</form>

<?php
};
?>
