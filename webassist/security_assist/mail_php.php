<?php

function WA_SecurityAssist_getEmailArray($emailStr)     {
  $retArray = array();
  $emailArr = explode(";",$emailStr);
  foreach ($emailArr AS $emailString)     {
    if (strpos($emailString,"@") > 0)     {
      $emailArr2 = explode("|WA|", $emailString);
      if (sizeof($emailArr2) == 1)     {
        $tempArray    = array(2);
        $tempArray[0] = "";
        $tempArray[1] = trim($emailString);
        $retArray[]   = $tempArray;
      }
      else     {
        $tempArr = array("", "");
		$eArr0 = $emailArr2[0];
		$eArr1 = $emailArr2[1];
        if ((strpos($eArr1, "@") > 0 || strpos($eArr1, "@") === 0) && (strpos($eArr1, " ") === false))      {
          $tempArr[0] = trim($emailArr2[0]);
          $tempArr[1] = trim($emailArr2[1]);
        }
        else     {
          $tempArr[0] = trim($emailArr2[1]);
          $tempArr[1] = trim($emailArr2[0]);
        }
        $retArray[] = $tempArr;
      }
    }
  }
  return $retArray;
}

function WA_SecurityAssist_Definition($serverName,$serverPort,$retPath,$organization,$xMailer,$charSet)     {
  $mailObj = new WA_SECURITYASSIST_MAILOBJ($serverName,$serverPort,$organization,$xMailer,"",$charSet);
  return $mailObj;
}

class WA_SECURITYASSIST_MAILOBJ {
  var $SMTP;
  var $Port;
  var $ReturnPath;
  var $ReplyTo;
  var $Organization;
  var $XMailer;
  var $CharSet;
  var $Importance;
  var $BodyFormat;
  var $attachments;
  var $recipients;
  var $ccrecip;
  var $bccrecip;
  var $useAddParams;
  function WA_SECURITYASSIST_MAILOBJ($serverName,$serverPort,$organization,$xMailer,$useAddParams,$charSet) {
    if ($charSet == "notdefined") {
      $charSet = $useAddParams;
      $useAddParams = "";
    }
    $this->SMTP         = $serverName;
    $this->Port         = $serverPort;
    $this->ReturnPath   = "";
    $this->ReplyTo      = "";
    $this->Organization = $organization;
    $this->XMailer      = $xMailer;
    $this->CharSet      = $charSet;
    if ($serverName != "") ini_set("SMTP", $serverName);
    if ($serverPort != "") ini_set("smtp_port", $serverPort); 
    $this->Importance = "";
    $this->BodyFormat = "";
    $this->attachments = array();
    $this->recipients  = array();
    $this->ccrecip     = array();
    $this->bccrecip    = array();
    $this->useAddParams = $useAddParams;
  }
}

function WA_SecurityAssist_SendMail($mailObj,$mailAttachments,$mailBCC,$mailCC,$mailTo,$mailImportance,$mailFrom,$mailSubject,$mailBody,$mailRef="WA_MailObject")     {
	if (strpos($mailTo,"@") < 0)  {
		return;
	}
	$fromArray = WA_SecurityAssist_getEmailArray($mailFrom);
	$mailTo2 = "";
	$mailContent = "";
	$mailHeader = "";
	$mailFrom = $fromArray[0][1];
	if ($fromArray[0][0] != "")     {
		$mailFrom = $fromArray[0][0]." <".$fromArray[0][1].">";
	}
	$mailObj->BodyFormat = ( (isset($mailObj->BodyFormat) && $mailObj->BodyFormat == 2) ? $mailObj->BodyFormat : ( (preg_match("/<body/i", $mailBody) != 0) ? 0 : 1 ) );
	$isIIS =  (isset($_SERVER['SERVER_SOFTWARE']) && stristr($_SERVER['SERVER_SOFTWARE'],'microsoft'));
	if ($isIIS) {
		$lineEnd = "\r\n";
	}else{
		$lineEnd = "\n";
	}
	if ($mailObj->ReturnPath == "")  {
		$mailObj->ReturnPath = $fromArray[0][1];
	}
	if ($mailObj->ReplyTo == "")  {
		$mailObj->ReplyTo = $fromArray[0][1];
	}
	$mailHeader .= "MIME-Version: 1.0".$lineEnd;
	if (sizeof($mailObj->attachments) > 0 && is_array($mailObj->attachments[0]))     {
		$mailHeader .= "Content-Type: multipart/mixed";
		if ($mailObj->CharSet != "") $mailHeader .= preg_replace("/[\r\n]/", "", "; charset=\"".$mailObj->CharSet."\"");
		$mailHeader .= "; boundary=\"WAMULTIBREAKWA\"".$lineEnd;
	}
	else if ($mailObj->BodyFormat == 2 || $mailObj->BodyFormat == 0) {
		$mailHeader .= "Content-Type: multipart/alternative";
		if ($mailObj->CharSet != "") $mailHeader .= preg_replace("/[\r\n]/", "", "; charset=\"".$mailObj->CharSet."\"");
		$mailHeader .= "; boundary=\"WAMULTIBREAKWA\"".$lineEnd;
		$headers  = "";
	}
	else {
		if ($mailObj->BodyFormat == 1) $mailHeader .= "Content-Type: text/plain";
		if ($mailObj->CharSet != "") $mailHeader .= preg_replace("/[\r\n]/", "", "; charset=\"".$mailObj->CharSet."\"").$lineEnd;
		else $mailHeader .= $lineEnd;
	}
	foreach ($mailObj->recipients AS $emailArr) {
		if ($mailTo != "") $mailTo .= ", ";
		if ($mailTo2 != "") $mailTo2 .= ", ";
		if ($emailArr[0] != "") $mailTo .= $emailArr[0];
		if ($emailArr[1] != "" && ! $isIIS)  $mailTo2 .= $emailArr[1]." <".$emailArr[0].">"; 
		else $mailTo2 .= $emailArr[0];
	}
	
	if (strpos($mailTo2, "@")) {
		$mailTo = $mailTo2;
	}
	$mailHeader .= preg_replace("/[\r\n]/", "", "From: ".$mailFrom).$lineEnd;
	foreach ($mailObj->ccrecip AS $emailArr) {
		if ($mailCC != "") $mailCC .= ", ";
		if ($emailArr[1] != "" && ! $isIIS) $mailCC .= $emailArr[1]." <".$emailArr[0].">";
		else $mailCC .= $emailArr[0];
	}
	if (strpos($mailCC, "@")) {
		$mailHeader .= preg_replace("/[\r\n]/", "", "Cc: ".$mailCC).$lineEnd;
	}
	
	foreach ($mailObj->bccrecip AS $emailArr) {
		if ($mailBCC != "") $mailBCC .= ", ";
		if ($emailArr[1] != "" && ! $isIIS) $mailBCC .= $emailArr[1]." <".$emailArr[0].">";
		else $mailBCC .= $emailArr[0];
	}
	if (strpos($mailBCC, "@")) {
		$mailHeader .= preg_replace("/[\r\n]/", "", "Bcc: ".$mailBCC).$lineEnd;
	}
	
	$mailHeader .= preg_replace("/[\r\n]/", "", "Reply-To: ".$mailObj->ReplyTo).$lineEnd;
	$mailHeader .= preg_replace("/[\r\n]/", "", "Return-Path: ".$mailObj->ReturnPath."").$lineEnd;
	$mailHeader .= preg_replace("/[\r\n]/", "", "X-Sender: ".$mailFrom).$lineEnd;
	$mailHeader .= preg_replace("/[\r\n]/", "", "X-Priority: ".$mailObj->Importance).$lineEnd;
	$mailHeader .= "Date: ". date('r (T)').$lineEnd;
	$theMSGID = $fromArray[0][1];
	$theMSGID = explode("@", $theMSGID);
	if (sizeof($theMSGID)==1) $theMSGID[] = $theMSGID[0];
	$theMSGID = "<".rand().".".md5($theMSGID[0])."@".$theMSGID[1].">";
	$mailHeader .= preg_replace("/[\r\n]/", "", "Message-ID: ".$theMSGID).$lineEnd;
	if ($mailObj->Organization != "") {
		$mailHeader .= preg_replace("/[\r\n]/", "", "Organization: ".$mailObj->Organization).$lineEnd;
	}
	if ($mailObj->XMailer != "") {
		$mailHeader .= preg_replace("/[\r\n]/", "", "X-Mailer: ".$mailObj->XMailer).$lineEnd;
	}
	if ($mailObj->BodyFormat == 2 || $mailObj->BodyFormat == 0 || sizeof($mailObj->attachments) > 0)     {
		$mailContent = $lineEnd."--WAMULTIBREAKWA".$lineEnd;
		switch ($mailObj->BodyFormat)   {
			case 0:
			case 2:
				$splitBreak = "--WAMULTIBREAKWA";
				if (sizeof($mailObj->attachments) > 0)  {
					$mailContent .= "Content-Type: multipart/alternative";
					if ($mailObj->CharSet != "") $mailHeader .= preg_replace("/[\r\n]/", "", "; charset=\"".$mailObj->CharSet."\"");
					$mailContent .= '; boundary="WAATTBREAKWA"'.$lineEnd.$lineEnd."--WAATTBREAKWA".$lineEnd;
					$splitBreak = "--WAATTBREAKWA";
				}
				$mailContent .= "Content-Type: text/plain";
				if ($mailObj->CharSet != "") $mailContent .= preg_replace("/[\r\n]/", "", "; charset=\"".$mailObj->CharSet."\"").$lineEnd;
				else $mailContent .= $lineEnd;
				$mailContent .= "Content-Transfer-Encoding: 8bit".$lineEnd;
				$theReplace  = $lineEnd.$splitBreak.$lineEnd;
				$theReplace .= "Content-Type: text/html";
				if ($mailObj->CharSet != "") $theReplace .= "; charset=\"".$mailObj->CharSet."\"";
				$theReplace  .= $lineEnd.$lineEnd;
				if (strpos($mailBody,"<multipartbreak>") === false)  {
					$mailBody = WA_SecurityAssist_StripTags($mailBody) . "<multipartbreak>" . $mailBody;
				}
				$mailBody    = str_replace("<multipartbreak>", $theReplace, $mailBody);
				$mailContent .= $lineEnd.$mailBody;
				$mailContent .= $lineEnd.$splitBreak."--".$lineEnd;
				break;
			case 1:
				$mailContent .= "Content-Type: text/plain";
				if ($mailObj->CharSet != "") $mailContent .= preg_replace("/[\r\n]/", "", "; charset=\"".$mailObj->CharSet."\"").$lineEnd;
				else $mailContent .= $lineEnd;
				$mailContent .= "Content-Transfer-Encoding: 8bit".$lineEnd;
				$mailContent .= $lineEnd.$mailBody;
				break;
		}
	}
	else {
		$mailContent .= $mailBody;
	}
	if(sizeof($mailObj->attachments) > 0)    {
		foreach ($mailObj->attachments as $fileArr)    {
			if (is_readable($fileArr[3]))    {
				if (strtolower($fileArr[1]) == "base64")     {
					$data = chunk_split(base64_encode(implode("", file($fileArr[3]))));
				}
				else     {
					$data = implode("", file($fileArr[3]));
				}
				$mailAttachments .= $lineEnd."--WAMULTIBREAKWA";
				$mailAttachments .= $lineEnd."Content-Type: ".$fileArr[0];
				if ($fileArr[2] != "")  {
					$mailAttachments .= "; name=\"".basename($fileArr[2])."\"".$lineEnd;
				}
				else  {
					$mailAttachments .= "; name=\"".basename($fileArr[3])."\"".$lineEnd;
				}
				$mailAttachments .= "Content-Transfer-Encoding: ".$fileArr[1].$lineEnd;
				$mailAttachments .= "Content-Disposition: inline;";
				if ($fileArr[2] != "")  {
					$mailAttachments .= " filename=\"".basename($fileArr[2])."\"".$lineEnd.$lineEnd;
				}
				else  {
					$mailAttachments .= " filename=\"".basename($fileArr[3])."\"".$lineEnd.$lineEnd;
				}
				$mailAttachments .= $data;
			}
		}
	}
	$mailContent = str_replace("<multipartbreak>", "--WAMULTIBREAKWA".$lineEnd, $mailContent);
	$mailHeader  = str_replace("<multipartbreak>", "--WAMULTIBREAKWA".$lineEnd, $mailHeader);
	$mailContent = $mailContent.$mailAttachments;
	//Set up mail object for logging purposes
	
	if (!isset($GLOBALS[$mailRef."_Index"] )) $GLOBALS[$mailRef."_Index"]  = 1;
	if (!isset($GLOBALS[$mailRef."_Log"] ) || $GLOBALS[$mailRef."_Index"]  == 1) $GLOBALS[$mailRef."_Log"]  = "";
	$GLOBALS[$mailRef."_From"] = $mailFrom;
	$GLOBALS[$mailRef."_To"] = $mailTo;
	$GLOBALS[$mailRef."_Subject"] = $mailSubject;
	$GLOBALS[$mailRef."_Body"] = $mailContent;
	$GLOBALS[$mailRef."_Header"] = $mailHeader;
	$GLOBALS[$mailRef."_Log"] .=  "Sending To: " .$mailTo  . "... ";
	set_error_handler(create_function('$errno, $errstr','$GLOBALS["'.$mailRef.'_Error"] = ($errstr); return true;'));
	if ($mailObj->useAddParams) {
		$mailObj = @mail($mailTo,$mailSubject,$mailContent,$mailHeader,"-f".$mailFrom." -r".$mailObj->ReturnPath);
	}
	else {
		$mailObj = @mail($mailTo,$mailSubject,$mailContent,$mailHeader);
	}
	restore_error_handler();
	if ( $mailObj)  {
		$GLOBALS[$mailRef."_Status"] = "Success";
		$GLOBALS[$mailRef."_Log"] .=  "Success <br>\n";
	}
	else  {
		$GLOBALS[$mailRef."_Status"] = "Failure";
		$GLOBALS[$mailRef."_Log"] .=  $GLOBALS[$mailRef."_Error"]. " - Failure <br>\n";
	}
	return $mailObj;
}

if (!function_exists("WA_SecurityAssist_StripTags")) {
function WA_SecurityAssist_StripTags($bodytext)  {
	if (strpos($bodytext,"<body") !== false)
		$bodytext = substr($bodytext,strpos($bodytext,"<body"));
	$bodytext = preg_replace("/\s{1,}/"," ",$bodytext); 
	$bodytext = preg_replace("/ {1,}/"," ",$bodytext);
	$bodytext = preg_replace("/<(p|br|tr)>/i","\r\n",$bodytext);
	$bodytext = preg_replace("/<(p |br |tr )([^>]*>)/i","\r\n",$bodytext); 
	$bodytext = preg_replace("/<(li|td|th)>/i","\t",$bodytext);
	$bodytext = preg_replace("/<(li |td |th )([^>]*>)/i","\t",$bodytext);
	$bodytext = preg_replace("/(<\/?)(\w+)([^>]*>)/","",$bodytext);
	return $bodytext;
}
}

?>